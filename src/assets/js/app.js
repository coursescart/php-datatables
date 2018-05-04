var $users_list_table;
jQuery(document).ready(function() {
    $('#users_list_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'ajax.php?action=get_users_list_table_json',
            type: 'POST'
        },
        columns: [
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'action2', name: 'action2', orderable: false, searchable: false},
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'gender', name: 'gender'},
            {data: 'phone', name: 'phone'},
            {data: 'city', name: 'city'},
            {data: 'country', name: 'country'},
        ],
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;
                $column_footer = $(column.footer());
                var input = document.createElement('input');
                $(input).attr('placeholder','Search By Column');
                if($column_footer[0].hasAttribute('search')){
                    $(input).appendTo($column_footer.empty())
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        column.search(val ? val : '', true, false).draw();                  
                    });                    
                }
            });
            
            $('.user_delete_confirm').click(function() {
                $action = $(this).attr('data-action');
                $('.user_delete_confirm_form').attr('action',$action);
            });
        }
    });

	$('#users_list_table_2').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'ajax.php?action=get_users_list_table_json_2',
            type: 'POST'
        },
        columns: [
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'gender', name: 'gender'},
        ],
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;
                $column_footer = $(column.footer());
                var input = document.createElement('input');
                $(input).attr('placeholder','Search By Column');
                if($column_footer[0].hasAttribute('search')){
                    $(input).appendTo($column_footer.empty())
                    .on('change', function () {
                        var val = $.fn.dataTable.util.escapeRegex($(this).val());
                        column.search(val ? val : '', true, false).draw();                  
                    });                    
                }
            });
		    
		    $('.user_delete_confirm').click(function() {
		        $action = $(this).attr('data-action');
		        $('.user_delete_confirm_form').attr('action',$action);
		    });
        }
    });

	jQuery(".refresh_users_list_table_2").click(function(){
		$('#users_list_table_2').DataTable().ajax.reload();
	});
});
