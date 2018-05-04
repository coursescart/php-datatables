<?php

class JsDataTables
{
	protected 
	$_add_columns = [],
	$_data = [],
	$_edit_columns = [],
	$_indexColumn,
	$_indexColumnDir,
	$_select,
	$_table,
	$_draw,
	$_recordsTotal,
	$_recordsFiltered,
	$_orderBy,
	$_orderType,
	$_sqlColumns = [],
	$_limtStart,
	$_limtLength,
	$_connection = [];

	function __construct($AppDb)
	{
		if (empty($_POST)) {
			$_POST = $_REQUEST;
		}
		if (empty($_POST)) die;
		if ($AppDb == 'wpdb') {
			// pd(DB_HOST.'/'.DB_USER.'/'.DB_PASSWORD.'/'.DB_NAME);
		    $this->_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) OR DIE("Impossible to access to DB : " . mysqli_connect_error());
		}else{
		    $this->_connection = mysqli_connect($AppDb['host'], $AppDb['username'], $AppDb['password'], $AppDb['db']) OR DIE("Impossible to access to DB : " . mysqli_connect_error());
		}
	    $this->setSqlColumns();
	    $this->defaultOrderSet();
    }
    
    public function defaultOrderSet()
    {
    	/* Useful $_POST Variables coming from the plugin */
		$this->_draw = $_POST["draw"];//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
	    if(!empty($this->_indexColumn) && $this->_draw == 1){
		    $this->_orderBy = $this->_indexColumn;//Get name of the sorting column from its index
		    $this->_orderType = $this->_indexColumnDir; // ASC or DESC
	    }else{
		    $orderByColumnIndex  = $_POST['order'][0]['column'];// index of the sorting column (0 index based - i.e. 0 is the first record)
		    if($_POST['columns'][$orderByColumnIndex]['orderable'] !== 'false'){
			    $this->_orderBy = $_POST['columns'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
			    $this->_orderType = $_POST['order'][0]['dir']; // ASC or DESC
		    }else{
	   		    $this->_orderType = $_POST['order'][0]['dir']; // ASC or DESC
			    $this->_orderBy = reset($this->_sqlColumns);
		    }
	    }
	    $this->_limtStart  = $_POST["start"];//Paging first record indicator.
	    $this->_limtLength = $_POST['length'];//Number of records that the table can display in the current draw
	    // $this->p($_POST['columns'][$orderByColumnIndex]);
	    // $this->p($this->_sqlColumns);
	    /* END of POST variables */
    }

    public function setSqlColumns()
    {
	    $orderableCols = [];
	    foreach ($_POST['columns'] as $r => $value) {
	    	if ($value['orderable'] === 'true') {
		    	$this->_sqlColumns[] = $value['data'];
	    	}
	    }
    }

	public function from($table)
	{
		$this->_table = $table;
		$this->processData();
		return $this;
	}
	
	public function indexColumn($indexColumn,$indexColumnDir = 'asc')
	{
		$this->_indexColumn = $indexColumn;
		$this->_indexColumnDir = $indexColumnDir;
		return $this;
	}
	
	public function select($cols)
	{
		$this->_select = $cols;
		return $this;
	}
	
	public function convertSqlCols($cols = '')
	{
		if (empty($cols)) return '*';
		if (!is_array($cols)) return '`'.$cols.'`';
		return '`'.str_replace(" , ", " ", implode("`, `", $cols)).'`';
	}

	public function addColumn($name,$content_or_callback){
		$countCols = count($this->_add_columns);
		$whereSQL = $this->getSearchFilterWhere();
        $resultTotalQuery = sprintf("SELECT %s FROM %s %s ORDER BY %s %s limit %d , %d ",$this->convertSqlCols($this->_select), $this->_table,$whereSQL,$this->_orderBy,$this->_orderType ,$this->_limtStart , $this->_limtLength);
	    $resultTotal = $this->getData($resultTotalQuery,'object');
		if (is_callable($content_or_callback)) {
			foreach ($resultTotal as $key => $resultRow) {
				$content = call_user_func($content_or_callback, $resultRow);
				$this->_add_columns[$countCols][] = ['name' => $name,'content' => $content];
			}
		}else{
			$content = $content_or_callback;
			foreach ($resultTotal as $key => $resultRow) {
				$this->_add_columns[$countCols][] = ['name' => $name,'content' => $content];
			}
		}
		return $this;
	}

	public function editColumn($name,$content_or_callback)
	{
		if (!in_array($name, $this->_select)) {
			die('Not access {'.$name.'} column');
		}
		$countCols = count($this->_edit_columns);
		$whereSQL = $this->getSearchFilterWhere();
        $resultTotalQuery = sprintf("SELECT %s FROM %s %s ORDER BY %s %s limit %d , %d ",$this->convertSqlCols($this->_select), $this->_table,$whereSQL,$this->_orderBy,$this->_orderType ,$this->_limtStart , $this->_limtLength);
	    $resultTotal = $this->getData($resultTotalQuery,'object');
		if (is_callable($content_or_callback)) {
			foreach ($resultTotal as $key => $resultRow) {
				$content = call_user_func($content_or_callback, $resultRow);
				$this->_edit_columns[$countCols][] = ['name' => $name,'content' => $content];
			}
		}else{
			$content = $content_or_callback;
			foreach ($resultTotal as $key => $resultRow) {
				$this->_edit_columns[$countCols][] = ['name' => $name,'content' => $content];
			}
		}
		return $this;
	}


	public function getColNameByKey($k = 0)
	{
		return $_REQUEST['columns'][$k]['name'];
	}

	public function getData($sql,$type = 'array'){
		// $bt = debug_backtrace();
		// $caller = array_shift($bt);

        $query = mysqli_query($this->_connection, $sql); // OR DIE ("Can't get Data from DB , Query is : ( ".$sql.' ) on line no : '.$caller['line']);
        /*if ($this->_connection = 'wpdb') {
	        $_return_type = ($type == 'object')?'OBJECT':'ARRAY_A';
			$results = $wpdb->get_results($sql, $_return_type);
		}else{
			if ($type == 'object') {
		        $result = array();
		        foreach ($query as $row ) {
		            $result[] = (object) $row;
		        }
	        }else{
		        $result = array();
		        foreach ($query as $row ) {
		            $result[] = $row;
		        }
	        }
        }*/
		if ($type == 'object') {
	        $result = array();
	        foreach ($query as $row ) {
	            $result[] = (object) $row;
	        }
        }else{
	        $result = array();
	        foreach ($query as $row ) {
	            $result[] = $row;
	        }
        }
        return $result;
    }

	public function processData(){
		$recordsTotalQuery = sprintf("SELECT %s FROM ".$this->_table,$this->convertSqlCols($this->_select));
	    $this->_recordsTotal = count($this->getData($recordsTotalQuery));

	    /* SEARCH CASE : Filtered data */
	    if(!empty($_POST['search']['value'])){
	    	// $this->searchFilter();
	    }
	    /* END SEARCH */
	    else {
	        $sql = sprintf("SELECT %s FROM %s ORDER BY %s %s limit %d , %d ",$this->convertSqlCols($this->_select), $this->_table ,$this->_orderBy,$this->_orderType ,$this->_limtStart , $this->_limtLength);
	        $this->_data = $this->getData($sql);
	        $this->_recordsFiltered = $this->_recordsTotal;
	    }
    }

    public function getSearchFilterWhere()
    {
        foreach ($this->_sqlColumns as $key => $column) {
            $where[] = "$column like '%".$_POST['search']['value']."%'";
        }
        return $where = "WHERE ".implode(" OR " , $where);// id like '%searchValue%' or name like '%searchValue%' ....
    }

	public function searchFilter(){
        $whereSQL = $this->getSearchFilterWhere();
        $sql = sprintf("SELECT %s FROM %s %s",$this->convertSqlCols($this->_select), $this->_table , $whereSQL);//Search query without limit clause (No pagination)
        $this->_recordsFiltered = count($this->getData($sql));//Count of search result
        /* SQL Query for search with limit and orderBy clauses*/
        $sql = sprintf("SELECT %s FROM %s %s ORDER BY %s %s limit %d , %d ",$this->convertSqlCols($this->_select), $this->_table , $whereSQL ,$this->_orderBy, $this->_orderType ,$this->_limtStart,$this->_limtLength  );
        $this->_data = $this->getData($sql);
        return $this;
	}

    public function dataAddColumns(){
		foreach ($this->_add_columns as $r => $row) {
			foreach ($row as $c => $col) {
				$this->_data[$c][$col['name']] = $col['content'];
			}
		}
    }

    public function dataEditColumns(){
		foreach ($this->_edit_columns as $r => $row) {
			foreach ($row as $c => $col) {
				$this->_data[$c][$col['name']] = $col['content'];
			}
		}
    }

	public function make($make = true) {
		if (!$make) return;
	    if(!empty($_POST['search']['value'])){
	    	$this->searchFilter();
	    }
	    if (!empty($this->_data)) {
		    $this->dataAddColumns(); //  for add columns in data
		    $this->dataEditColumns(); //  for edit columns in data
	    }
	    /* Response to client before JSON encoding */
	    $response = array(
	        "draw" => intval($this->_draw),
	        "recordsTotal" => $this->_recordsTotal,
	        "recordsFiltered" => $this->_recordsFiltered,
	        "data" => $this->_data
	    );
	    echo json_encode($response);
	    die;
	}
} // End of class
