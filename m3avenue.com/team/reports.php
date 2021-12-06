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
                        <h4 class="page-title">TRACK MY WORK</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
					<ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li class="active">My Reports</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /row -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title">My Reports</h3>
                            <div class="table-responsive">
							 <a href="track-team-download.php" class="btn btn-primary">Download</a>
                                <table class="table" id="kt_table_1">
                                    	<thead>
						<tr> 
                            <th>SL no</th> 
                            <th>Reg date</th> 
                            <th>Client name</th> 
                            <th>Client mobile</th> 
							<th>Pending points</th>
							<th>Earned points</th>
							<th>Reject points</th>
						</tr>
							</thead>
								<tbody>
									 <?php 
						 
							
			$totalclientsarray = runloopQuery("select * from clients where employee_id = '".$userid."'");
			$x=1;  foreach($totalclientsarray as $totalclient){
				$pendingamount = $totalclient["loanamount"];
				$appamount =$rejectamount = 0;
				if($totalclient["status"] == 'Approved'){
					$pendingamount = 0;
					$appamount = $totalclient["loanamount"];
				}
				if($totalclient["status"] == 'Reject'){
					$pendingamount = 0;
					$rejectamount = $totalclient["loanamount"];
				}
	?>
	<tr> 
	<td><?php echo $x;?></td>
	<td><?php echo date('d F Y',strtotime($totalclient["regdate"]));?></td>
	<td><?php echo $totalclient["clientname"];?></td>
	<td><?php echo $totalclient["mobile"];?></td>
	<td><?php echo points($pendingamount,$totalclient["pointstype"]);?></td>
	<td><?php echo points($appamount,$totalclient["pointstype"]);?></td>
	<td><?php echo points($rejectamount,$totalclient["pointstype"]);?></td>
	</tr> 			
			 <?php $x++; }?>	
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
</body>

</html>
