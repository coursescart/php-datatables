<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Data table with php</title>
	<link rel="stylesheet" href="assets/css/jquery.dataTables.css">
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<section>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<button class="btn btn-primary refresh_users_list_table" style="margin: 32px 0px 19px 0px;"><?php echo 'Refresh'; ?></button>
				<table id="users_list_table">
				    <thead>
				        <tr>
				            <th width="185"> <?php echo 'Act.'; ?></th>
				            <th width="30"> <?php echo 'Act 2.'; ?></th>
				            <th width="30"> <?php echo 'Id.'; ?></th>
				            <th width="30"> <?php echo 'Name.'; ?></th>
				            <th width="130"><?php echo 'Email'; ?></th>
				            <th width="130"><?php echo 'Gender'; ?></th>
							<th width="130"><?php echo 'Phone'; ?></th>
							<th width="130"><?php echo 'City'; ?></th>
							<th width="130"><?php echo 'Country'; ?></th>
				        </tr>
				    </thead>
				    <tfoot>
				        <tr>
				            <th width="185"> <?php echo 'Act.'; ?></th>
				            <th width="30"> <?php echo 'Act 2.'; ?></th>
				            <th width="30"> <?php echo 'Id.'; ?></th>
				            <th width="30"> <?php echo 'Name.'; ?></th>
				            <th width="130"><?php echo 'Email'; ?></th>
				            <th width="130"><?php echo 'Gender'; ?></th>
							<th width="130"><?php echo 'Phone'; ?></th>
							<th width="130"><?php echo 'City'; ?></th>
							<th width="130"><?php echo 'Country'; ?></th>
				        </tr>
				    </tfoot>
				</table>
			</div>
			<hr>
			<div class="col-md-12">
				<button class="btn btn-primary refresh_users_list_table_2" style="margin: 32px 0px 19px 0px;"><?php echo 'Refresh 2'; ?></button>
				<table id="users_list_table_2">
				    <thead>
				        <tr>
				            <th width="30"> <?php echo 'Act.'; ?></th>
				            <th width="30"> <?php echo 'Id.'; ?></th>
				            <th width="30"> <?php echo 'Name.'; ?></th>
				            <th width="130"><?php echo 'Gender'; ?></th>
				        </tr>
				    </thead>
				    <tfoot>
				        <tr>
				            <th width="30"> <?php echo 'Act.'; ?></th>
				            <th width="30"> <?php echo 'Id.'; ?></th>
				            <th width="30"> <?php echo 'Name.'; ?></th>
				            <th width="130"><?php echo 'Gender'; ?></th>
				        </tr>
				    </tfoot>
				</table>
			</div>
		</div>
	</div>
</section>

	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/jquery.dataTables.js"></script>
	<script src="assets/js/app.js"></script>
 </body>
</html>