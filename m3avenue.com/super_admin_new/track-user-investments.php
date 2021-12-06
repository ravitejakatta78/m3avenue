<?php
session_start();
error_reporting(E_ALL); 
include('../functions.php');
$userid = current_adminid(); 
if(empty($userid)){
	header("Location: login.php");
} 
$message = '';

if(!empty($_POST['invesmentsubmit'])){
	   $pagerarray  = array();  
                $pagerwherearray['ID'] = mysqli_real_escape_string($conn,$_POST['invesmentsubmit']);
                $pagerarray['payin'] = mysqli_real_escape_string($conn,$_POST['payin']); 
                $pagerarray['growth'] = mysqli_real_escape_string($conn,$_POST['growth']); 
                $pagerarray['status'] = mysqli_real_escape_string($conn,$_POST['status']); 
	            $result = updateQuery($pagerarray,'user_investment',$pagerwherearray);
				if(!$result){
					header("Location: track-user-investment.php?success=success");
                    } 
} 

if(!empty($_GET['delete'])){
	$sql = "DELETE FROM clients WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: track-user-investment.php?success=success");
   
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
							<h4 class="page-title" style="color:#886CC0">Track Client</h4>
							
						</div>
						<div class="card-body">

							<?php if(!empty($_GET['success'])){?>
								<br>
								<div class="alert alert-success">
								Track Client details Added Succussfully
								</div>
								<?php } ?>
								<?php if(!empty($message)){?>
								<div class="alert alert-danger">
								<?=$message?>
								</div>
								<br>
								<?php } ?>

							<form method="get" action="">
							<div class="form-group row">
						
								<label for="example-text-input" class="col-2 col-form-label">User</label>
		                        <div class="col-4">
		                            <input class="form-control" type="text" placeholder="User id"  value="<?php echo !empty($_GET['userid']) ? $_GET['userid']: '';?>" name="userid" id="example-text-input">
		                        </div>
		                         <div class="col-4">
		                             <button type="submit"  class="btn btn-primary btn-elevate btn-pill">Submit</button>
									  <button type="reset"  class="btn btn-primary btn-elevate btn-pill" >Clear</button>
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
                            <th>Client Name</th>
							<th>Reg Date</th>
							<th>mobile</th>
                            <th>Invested Type</th>
							<th>Loan/Invested Amount</th>
							<th>PayIn/Payout</th>
							<th>growth</th>
							<th>Status</th>
							<th>View</th>
						</tr>
					</thead>
					<tbody>
						 <?php
						 if(!empty($_GET['userid'])){
							 $empid = user_id($_GET['userid']);
							$sql = runloopQuery("SELECT * FROM user_investment where user_id = '".$empid."' order by ID desc");
						 }else{
							$sql = runloopQuery("SELECT * FROM user_investment order by ID desc");
						 }
					   $x=1;  foreach($sql as $row)
							{
					?>
<tr>
<td><?php echo  $x;?></td>
<td><?php echo user_details($row["user_id"],'fname');?>(<?php echo user_details($row["user_id"],'unique_id');?>)</td>
<td><?php echo date('d/m/Y',strtotime($row["applieddate"]));?></td>
<td><?php echo user_details($row["user_id"],'mobile');?></td>
<td><?php echo $row["investedtype"];?></td>
<td><?php echo $row["loanamount"];?></td>
<td><?php echo $row["payin"];?></td>
<td><?php echo $row["growth"];?></td>
<td><?php echo $row["status"]==1 ? 'Active' : 'Pending';?></td>
<td><a href="?invesmentview=<?php echo $row["ID"];?>">View</a></td>
</tr>
<?php
     $x++; }
?>					
						
							</tbody>
						</table>

									<!--end: Datatable -->
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
</div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <?php include('footer_scripts.php');?>

</body>
</html>