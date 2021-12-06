<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');
$userid = current_userid();
if(empty($userid)){
	header("Location:profile.php");
	}
	$message = '';
$usedetails = runQuery("select * from user where ID = '".$userid."'");
if(empty($userid)){
	header("Location: ../index.php");
}
$message = '';
if(!empty($_POST['investedsubmit'])){ 
	   $pagerarray  = array();  
                $pagerarray['user_id'] = $userid; 
                 $pagerarray['applieddate'] = date('Y-m-d H:i:s');
                $pagerarray['investedtype'] = mysqli_real_escape_string($conn,$_POST['investedtype']);
                $pagerarray['loanamount'] = mysqli_real_escape_string($conn,$_POST['loanamount']);
                $pagerarray['status'] = 0;
	            $result = insertQuery($pagerarray,'user_investment');
				if(!$result){
					header("Location: track-investment.php?success=success");
                    }else{
						$message .= $result;
					} 
} 
if(!empty($_POST['loanssubmit'])){ 
	   $pagerarray  = array();  
                $pagerarray['user_id'] = $userid; 
                 $pagerarray['applieddate'] = date('Y-m-d H:i:s');
                $pagerarray['investedtype'] = mysqli_real_escape_string($conn,$_POST['loantype']);
                $pagerarray['requiredamount'] = mysqli_real_escape_string($conn,$_POST['requiredamount']);
                $pagerarray['status'] = 0;
	            $result = insertQuery($pagerarray,'user_loans');
				if(!$result){
					header("Location: track-investment.php?success=success");
                    }else{
						$message .=$result;
					}  
}

if(!empty($_GET['invesmentdelete'])){
	$sql = "DELETE FROM user_investment WHERE ID=".$_GET['invesmentdelete']."";

if ($conn->query($sql) === TRUE) {
   
					header("Location: track-investment.php?del=success");
   
} else {
    echo "Error deleting record: " . $conn->error;
}
}

if(!empty($_GET['loansdelete'])){
	$sql = "DELETE FROM user_loans WHERE ID=".$_GET['loansdelete']."";

if ($conn->query($sql) === TRUE) {
   
					header("Location: track-investment.php?del=success");
   
} else {
    echo "Error deleting record: " . $conn->error;
}
}
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
    <!-- color CSS -->
    <link href="css/colors/blue-dark.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
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
                            <li class="active">TRACK Work</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
					
                </div>
                <!-- /row -->
                <div class="row">
				 <?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Application submitted
								</div>
								<?php } ?>
				 <?php if(!empty($_GET['del'])){?>
								<div class="alert alert-success">
								Application Deleted
								</div>
								<?php } ?>
								<?php if(!empty($message)){?>
								<div class="alert alert-danger">
								<?=$message?>
								</div>
								<?php } ?>
                    <div class="col-sm-6">
                        <div class="white-box">
                            <h3 class="box-title">Investments</h3>
							   <form class="width80" method="post" action="" >
						 
						<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Invested Type</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="investedtype" value="<?php echo !empty($_POST['investedtype']) ? $_POST['investedtype']: '';?>" placeholder="Enter Invested Type" id="example-text-input">
                        </div>
						</div>
						
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Loan/Invested Amount</label>
                        <div class="col-4">
                            <input class="form-control" type="text" placeholder="Loan/Invested Amount"  value="<?php echo !empty($_POST['loanamount']) ? $_POST['loanamount']: '';?>" name="loanamount" id="example-text-input">
                        </div>
                    </div>
					
					 <div class="form-group row">
                       <div class="col-4">
                             <button type="submit" name="investedsubmit" value="submit" class="btn btn-brand btn-elevate btn-pill">Submit</button>
						</div>
                    </div> 
					</form>					
					 
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="white-box">
                            <h3 class="box-title">Loans</h3>
							   <form class="width80" method="post" action="" >
						 
						<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Invested Type</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="loantype" value="<?php echo !empty($_POST['loantype']) ? $_POST['loantype']: '';?>" placeholder="Enter Invested Type" id="example-text-input">
                        </div>
						</div>
						
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Requried Amount</label>
                        <div class="col-4">
                            <input class="form-control" type="text" placeholder="Requried Amount"  value="<?php echo !empty($_POST['requiredamount']) ? $_POST['requiredamount']: '';?>" name="requiredamount" id="example-text-input">
                        </div>
                    </div>
					
					 <div class="form-group row">
                       <div class="col-4">
                             <button type="submit" name="loanssubmit" value="submit" class="btn btn-brand btn-elevate btn-pill">Submit</button>
						</div>
                    </div> 
					</form>					
					 
                        </div>
                    </div>
                </div>
                <!-- /.row -->

            <div class="container-fluid">
			<div class="col-lg-12 col-md-12 col-sm-4 col-xs-12">
	<div class="tabs">
		<div class="tab">
					<div class="col-lg-12 col-md-12 col-sm-4 col-xs-12">
						<button class="tablinks" onclick="openCity(event, 'London')" id="defaultOpen">Track Investments</button>
						<button class="tablinks" onclick="openCity(event, 'Paris')">Track Loans</button>
					</div>
					<div class="col-lg-12 col-md-8 col-sm-4 col-xs-12">
							<div id="London" class="tabcontent">
								<div class="table-responsive">
								<p><b>Track Investments :</b></p>
										<table class="table">
											<thead>
												<tr>
													<th>S.NO</th>
													<th>Login Date</th>
													<th>Started Amount</th>
													<th>Invested Type</th>
													<th>PayIn/PayOut</th>
													<th>Growth %</th>
													<th>Status</th>
													<th>Reg date</th>
												</tr>
											</thead>
													<tbody>
						 <?php  
							$sql = runloopQuery("SELECT * FROM user_investment where user_id = '".$userid."' order by ID desc"); 
					   $x=1;  foreach($sql as $row)
							{
					?>
<tr>
<td><?php echo  $x;?></td>
<td><?php echo date('d/m/Y',strtotime($row["applieddate"]));?></td>
<td><?php echo $row["investedtype"];?></td>
<td><?php echo $row["loanamount"];?></td>
<td><?php echo $row["payin"];?></td>
<td><?php echo $row["growth"];?></td>
<td><?php echo $row["status"]==1 ? 'Active' : 'Pending';?></td>
<td><?php echo reg_date($row["reg_date"]);?></td>
</tr>
<?php
     $x++; }
?>					
						
							</tbody>
										</table>
									</div>
								</div>
							<div id="Paris" class="tabcontent">
									<p><b>Track Loans :</b></p>
							  <table class="table">
											<thead>
												<tr>
													<th>S.NO</th>
													<th>Application Date</th>
													<th>Requried Amount</th>
													<th>Applied Type</th>
													<th>Application Status</th>
													<th>EMI Chart</th>
												</tr>
											</thead> 
												<tbody>
						 <?php  
							$sql = runloopQuery("SELECT * FROM user_loans where user_id = '".$userid."' order by ID desc"); 
					   $x=1;  foreach($sql as $row)
							{
								$status = 'Pending';
								if($row["status"]==1){ $status = 'Active';}
								if($row["status"]==2){ $status = 'Reject';}
					?>
<tr>
<td><?php echo  $x;?></td>
<td><?php echo date('d/m/Y',strtotime($row["applieddate"]));?></td>
<td><?php echo $row["investedtype"];?></td>
<td><?php echo $row["requiredamount"];?></td>
<td><?php echo $status;?></td>
<td><a href="#">view</a></td>
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
            <!-- /.container-fluid -->
<?php include('footer.php');?>			
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
	
	<script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>
   
    <!-- jQuery -->
    <script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/custom.min.js"></script>
</body>

</html>
