<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');
$userid = current_employeeid();
if(empty($userid)){
	header("Location:index.php");
	}
$usedetails = runQuery("select * from employee where ID = '".$userid."'");

$message = '';
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
   
   <?php include("headerscripts.php");?>
</head>

<body>
    <!-- Preloader -->
   
    <div id="wrapper">
        <!-- Navigation -->
              <?php include('header.php');?>
        <!-- Left navbar-header end -->
		
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">TRACK MY  CLIENTS</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
					<ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li class="active">TRACK MY CLIENTS</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /row -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title">Track My Clients</h3>
                            <div class="table-responsive">
                                <table class="table" id="kt_table_1">
                                  <thead>
						<tr>
							<th>S.No</th>
                            <th>Client Name</th>
							<th>Reg Date</th>
							<th>Mobile</th>
                            <th>Service Type</th>
                            <th>Company name</th>
                            <th>Bank name</th>
							<th>Status</th> 
						</tr>
					</thead>
					<tbody>
						 <?php 
							$sql = runloopQuery("SELECT * FROM clients where employee_id = '".$userid."' order by ID desc");
						 
					   $x=1;  foreach($sql as $row)
							{
					?>
<tr>
<td><?php echo  $x;?></td>
<td><?php echo $row["clientname"];?></td>
<td><?php echo reg_date($row["reg_date"]);?></td>
<td><?php echo $row["mobile"];?></td>
<td><?php echo $row["servicetype"];?></td>
<td><?php echo $row["companyname"];?></td>
<td><?php echo $row["bankname"];?></td>
<td><?php echo $row["status"];?></td>
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
   
   <?php include("footerscripts.php");?>
   	<script>
			$("#kt_table_1").DataTable();
		</script>
</body>

</html>
