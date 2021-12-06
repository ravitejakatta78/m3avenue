<?php

	session_start();

	error_reporting(E_ALL);

	include('../functions.php');




	$userid = current_adminid();
$user_unique_id = employee_details($userid,'unique_id');
$tree = employeehirerachy($user_unique_id);
$empString = !empty($tree) ? implode("','",array_column($tree,'ID')) : ''; 



	if(empty($userid)){

		header("Location: index.php");

	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<meta name="robots" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template" />
	<meta property="og:title" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template" />
	<meta property="og:description" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template" />
	<meta property="og:image" content="https:/fillow.dexignlab.com/xhtml/social-image.png" />
	<meta name="format-detection" content="telephone=no">
	
	<!-- PAGE TITLE HERE -->
	<title>Fillow Saas Admin Dashboard</title>
	
	
	<?php include('header_scripts.php');?>

	<!-- Datatable -->
	<link href="assets/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body>

    <!--*******************
        Preloader start
        ********************-->
        <div id="preloader">
        	<div class="lds-ripple">
        		<div></div>
        		<div></div>
        	</div>
        </div>
    <!--*******************
        Preloader end
        ********************-->

    <!--**********************************
        Main wrapper start
        ***********************************-->
        <div id="main-wrapper">


        	<?php include('header.php');?>


		<!--**********************************
            Content body start
            ***********************************-->
            <div class="content-body">
            	<!-- row -->
            	<div class="container-fluid">

            		<div class="row">

            			<div class="col-12">
            				<div class="card">
            					<div class="card-header">
            						<h4 class="page-title" style="color:#886CC0">Track Team</h4> 
            					</div>
            					<div class="card-body">

            						<form method="get" action="">
            							<div class="form-group row">

            								<label for="example-text-input" class="col-2 col-form-label">Employee</label>
            								<div class="col-4">
            									<input class="form-control" type="text" placeholder="Employee id"  value="<?php echo !empty($_GET['employeeid']) ? $_GET['employeeid']: '';?>" name="employeeid" id="example-text-input">
            								</div>
            								<label for="example-text-input" class="col-2 col-form-label">Employee mobile</label>
            								<div class="col-4">
            									<input class="form-control" type="text" name="employeemobile" value="<?php echo !empty($_GET['employeemobile']) ? $_GET['employeemobile']: '';?>" placeholder="client mobile" id="example-text-input">
            								</div>
            							</div>
            							<br>
            							<div class="form-group row">
            								<div class="col-4">
            									<button type="submit"  class="btn btn-primary btn-elevate btn-pill">Submit</button>
            									<a href="track_team.php" class="btn btn-primary btn-elevate btn-pill">Clear</a>
            									<a href="track-team-download.php" class="btn btn-primary btn-elevate btn-pill">Download</a>
            								</div>
            							</div> 
            						</form>
            						<br>


            						<div class="table table-responsive">

            							<!--begin: Datatable -->
            							<table id="example3" style="min-width: 845px" class="table table-bordered table-hover table-checkable display">
            								<thead>
            									<tr>
            										<th>S.No</th>
            										<th>Leader ID</th>
            										<th>Employee ID</th>
            										<th>Mobile</th>
            										<th>Clients</th>
            										<th>Invested Amount</th>
            										<th>Points</th>
            										<th>Converted</th>
            										<th>Converted amount</th>
            										<th>Points</th>
            									</tr>
            								</thead>
            								<tbody>
            									<?php

            										if(!empty($_GET['employeeid'])){
							 $empid = employee_id($_GET['employeeid']);
							$sql = runloopQuery("SELECT * FROM employee where ID = '".$empid."' order by ID desc");
						 }else if(!empty($_GET['employeemobile'])){ 
							 $empid = $_GET['employeemobile'];
							$sql = runloopQuery("SELECT * FROM employee where mobile = '".$empid."' order by ID desc");
						 }else{
							$sql = runloopQuery("SELECT * FROM employee where ID in ('".$empString."')order by ID desc");
						 }


            										$x=1;
            										foreach($sql as $row)
            										{
            											$totalclients = runQuery("select count(*) as count from clients where employee_id = '".$row['ID']."'");
            											$totalinvestedamount = runQuery("select sum(loanamount) as count from clients where employee_id = '".$row['ID']."'");

            											$totalinvestedamountarray = runloopQuery("select loanamount,pointstype from clients where employee_id = '".$row['ID']."'");
            											$totalinvespoints = 0;
            											foreach($totalinvestedamountarray as $totalinvested){
            												$totalinvespoints += points($totalinvested["loanamount"],$totalinvested["pointstype"]);
            											}
            											$totalconvertedclients = runQuery("select count(*) as count from clients where employee_id = '".$row['ID']."' and status = 'Active'");
            											$totalconvertedamountarray = runloopQuery("select loanamount,pointstype from clients where employee_id = '".$row['ID']."' and status = 'Active'");
            											$totalconvertedamount = runQuery("select sum(loanamount) as count from clients where employee_id = '".$row['ID']."' and status = 'Active'");
            											$totalconetpoints = 0;
            											foreach($totalconvertedamountarray as $totalconverted){
            												$totalconetpoints += points($totalconverted["loanamount"],$totalconverted["pointstype"]);
            											}

            										?>
            										<tr>
            											<td><?php echo  $x;?></td>
            											<td><?php echo $row["leader"];?></td>
            											<td><?php echo $row["fname"];?>(<?php echo $row["unique_id"];?>)</td>
            											<td><?php echo $row["mobile"];?></td>
            											<td><?php echo $totalclients["count"];?></td>
            											<td><?php echo $totalinvestedamount["count"];?></td>
            											<td><?php echo $totalinvespoints;?></td>
            											<td><?php echo $totalconvertedclients["count"];?></td>
            											<td><?php echo $totalconvertedamount["count"];?></td>
            											<td><?php echo $totalconetpoints;?></td>
            										</tr>
            										<?php
            											$x++; }
            										?>					
            									</tbody>
            								</table>
            							</div>
            						</div>
            					</div>
            				</div>
            			</div>


            		</div>
            	</div>
        <!--**********************************
            Content body end
            ***********************************-->


        </div>
    <!--**********************************
        Main wrapper end
        ***********************************-->

        <?php include('footer_scripts.php');?>

         <!-- Datatable -->
        <script src="assets/vendor/datatables/js/jquery.dataTables.min.js"></script>
        <script src="assets/js/plugins-init/datatables.init.js"></script>

    </body>
    </html>