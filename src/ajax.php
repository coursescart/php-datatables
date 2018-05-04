<?php

class Ajax
{
	public $JsDT;
	function __construct()
	{
		$AppDb = [
		    'host' => 'localhost',
		    'username' => 'root', 
		    'password' => '',
		    'db'=> 'jagjit_datatables',
		    'port' => 3306,
		    'prefix' => '',
		    'charset' => 'utf8'
		];
		require_once 'core/JsDataTables.php';
		$this->JsDT = new JsDataTables($AppDb);

		if(!isset($_GET['action'])) return;
		$this->$_GET['action']();
	}

	public function get_users_list_table_json() {
		return 
		$this->JsDT
		->select(array( 'id', 'name', 'email', 'gender', 'phone', 'city', 'country'))
		->from('users')
		->indexColumn('id','desc')
		->editColumn('email', function ($user) {
			return '<a href="mailto:'.$user->email.'" class="btn btn-xs btn-success">'.$user->email.'</a>';
		})
		// ->editColumn('name','Edited WithOut CallBack Functoin')
		->addColumn('action', function ($user) {
			return '<div class="center actions_div"> <a href="'.$user->id.'" class="btn btn-xs btn-success">Show</a> <a href="'.$user->id.'" class="btn btn-xs btn-primary">Edit</a> <button href="#" data-action="'.$user->id.'" data-toggle="modal" data-target="#DeleteUserModal" class="user_delete_confirm btn btn-xs btn-danger">Delete</button><div>';
		})
		->addColumn('action2', 'Added WithOut CallBack Functoin')
		->make(true)
		;
	} // End of function getPropertiesListTableJson();

	public function get_users_list_table_json_2() {
		return 
		$this->JsDT
		->select(array( 'id', 'name','gender'))
		->from('users')
		->indexColumn('id','desc')
		->addColumn('action', function ($user) {
			return '<div class="center actions_div"> <a href="'.$user->id.'" class="btn btn-xs btn-success">Show</a> <a href="'.$user->id.'" class="btn btn-xs btn-primary">Edit</a> <button href="#" data-action="'.$user->id.'" data-toggle="modal" data-target="#DeleteUserModal" class="user_delete_confirm btn btn-xs btn-danger">Delete</button><div>';
		})
		->make()
		;
	} // End of function getPropertiesListTableJson();

} //class
new Ajax;

// For a debuging
function p($c = ''){
	echo "<pre>";
	print_r($c);
	echo "</pre>";
}

function pd($c = ''){
	echo "<pre>";
	print_r($c);
	echo "</pre>";
	die;
}