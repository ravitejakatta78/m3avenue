<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');

$userid = current_managerid(); 
if(empty($userid)){
	header("Location: index.php");
}
$usedetails = runQuery("select * from employee where ID = '".$userid."'");

$teamdetails = runloopQuery("select *,concat(fname,' ',lname) name from employee where leader = '".$usedetails['unique_id']."' and status=1");
$teamselect = array_column($teamdetails,'name',"ID");
$message = '';
$monthstartdate = date('Y-m-01');
$currentdate = date('Y-m-d');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../plugins/images/favicon.png">
    <title>M3 Dashboard</title>
    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- toast CSS -->
    <link href="../plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- morris CSS -->
    <link href="../plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <!-- color CSS -->
    <link href="css/colors/blue-dark.css" id="theme" rel="stylesheet">
        	<link href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
<div id="wrapper">
        <!-- Navigation -->
               <?php include('header.php');?>
        <!-- Left navbar-header end -->
		
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">TRACK Team</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
					<ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li class="active">TRACK  Team</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div> 

                
                <!-- /row -->
                <div class="row">
                    <div class="col-sm-4">
					    <h3 class="box-title">Track Team</h3>
					</div>
					<div class="col-sm-8" style="text-align:right">
					
					</div>
					<div class="col-sm-12">
						<div class="white-box">
				
                            <div class="table-responsive">
                                <form method="get" action="">
										<div class="form-group row">

										<label for="example-text-input" class="col-md-1 col-form-label">Employee</label>
										<div class="col-md-2">
										    <select name="team_member" class="form-control">
							<option value="">select</option>
							<?php foreach($teamselect as $key => $value) {?>
								<option value="<?= $key ;?>" <?php if(isset($_REQUEST['team_member'])) { if($_REQUEST['team_member'] == $key) { ?> selected <?php } } ?>)><?= $value ;?></option>
							<?php } ?>
						</select>
										</div>
										<label for="example-text-input" class="col-md-1 col-form-label">Start Date</label>
										<div class="col-md-2">
										<input class="form-control" type="date" name="sdate" value="<?= @$_GET['sdate'] ?? $monthstartdate; ?>">
										</div>
										<label for="example-text-input" class="col-md-1 ">End Date</label>
										<div class="col-md-2">
										<input class="form-control" type="date" name="edate" value="<?= @$_GET['edate'] ?? date('Y-m-d');?>">
										</div>
										<div class="col-md-3 " style="display: flex;  justify-content: center;" >
										<button type="submit"  class="btn btn-brand btn-elevate btn-success">Submit</button>
										</div>
										</div>
										 
									</form>
                
                                <table class="table" id="kt_table_1">
                                  <thead>
						<tr>
							<th>S.No</th>
                            <th>Employee details</th>
                            <th>Leads</th>
                            <th>Clients</th>
							<th>Pending points</th>
							<th>Earned points</th>
							<th>Reject points</th>
						</tr>
							</thead>
								<tbody>
									 <?php
									 $startdate = !empty($_GET['sdate']) ? $_GET['sdate'] : $monthstartdate ;
							        $enddate = !empty($_GET['sdate']) ? $_GET['edate'] : $currentdate ;
$sql = runloopQuery("SELECT * FROM employee where status='1' and leader = '".$usedetails['unique_id']."' order by ID desc");
   $x=1;  foreach($sql as $row)
		{
			$totalleads = runQuery("select count(*) as count from track_work where date(reg_date) between '".$startdate."' and '".$enddate."' and employee_id = '".$row['ID']."'");
			$totalclients = runQuery("select count(*) as count from clients where date(reg_date) between '".$startdate."' and '".$enddate."' and  employee_id = '".$row['ID']."'");
			$totalinvestedamount = runQuery("select sum(loanamount) as count from clients where date(reg_date) between '".$startdate."' and '".$enddate."' and  employee_id = '".$row['ID']."' and status = 'Pending'"); 
			$totalconvertedamount = runQuery("select sum(loanamount) as count from clients where date(reg_date) between '".$startdate."' and '".$enddate."' and  employee_id = '".$row['ID']."' and status = 'Approved'");
			$totalrejectamount = runQuery("select sum(loanamount) as count from clients where date(reg_date) between '".$startdate."' and '".$enddate."' and  employee_id = '".$row['ID']."' and status = 'Reject'");
	?>
	<tr>
	<td><?php echo  $x;?></td>
	<td><?php echo $row["fname"];?> (<?php echo $row["unique_id"];?>)</td>
	<td><?php echo $totalleads["count"];?></td>
	<td><?php echo $totalclients["count"];?></td>
	<td><?php echo points($totalinvestedamount["count"]);?></td> 
	<td><?php echo points($totalconvertedamount["count"]);?></td>
	<td><?php echo points($totalrejectamount["count"]);?></td>
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
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
<?php include('footer.php');?>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
	
    <script src="js/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!-- Custom Theme JavaScript -->
        	<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
    
	<!--end::Page Scripts -->





  <script>	
	$("#kt_table_1").DataTable();
	</script>




</body>

</html>