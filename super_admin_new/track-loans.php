<?php
session_start();
error_reporting(E_ALL); 
include('../functions.php');
$userid = current_adminid(); 
if(empty($userid)){
	header("Location: login.php");
} 
$message = '';

if(!empty($_POST['loanssubmit'])){
	if(!empty($_POST['clientname'])){
	   $pagerarray  = array();  
                $pagerwherearray['ID'] = mysqli_real_escape_string($conn,$_POST['loanssubmit']);
                $pagerarray['status'] = mysqli_real_escape_string($conn,$_POST['status']); 
	            $result = updateQuery($pagerarray,'user_loans',$pagerwherearray);
				if(!$result){
					header("Location: track-user-loans.php?success=success");
                    } 
				}else{ 
					$message .="Invalid employee id";
				} 
} 
if(!empty($_GET['delete'])){
	$sql = "DELETE FROM clients WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: track-user-loans.php?success=success");
   
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
							<h4 class="page-title" style="color:#886CC0">Track Loans</h4>
							
						</div>
						<div class="card-body">

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
							<th>Status</th>		
							<th>Reg date</th>
							<th>View</th>
						</tr>
					</thead>
					<tbody>
						 <?php
							 if(!empty($_GET['userid'])){
							 $empid = user_id($_GET['userid']);
							$sql = runloopQuery("SELECT * FROM user_loans where user_id = '".$empid."' order by ID desc");
						 }else{
							$sql = runloopQuery("SELECT * FROM user_loans order by ID desc");
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
<td><?php echo $row["requiredamount"];?></td>
<td><?php echo $row["status"]==1 ? 'Active' : 'Pending';?></td><td><?php echo reg_date($row["reg_date"]);?></td>
<td><a href="?loansview=<?php echo $row["ID"];?>">View</a></td>
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

    <?php if(!empty($_GET['loansview'])){?>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
<!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
          <form method="post" action="">
		<?php $m3avenue = runQuery("select * from user_loans where ID = ".$_GET['loansview']."");?>

		   <div class="form-group">
		    <select class="form-control" name="status">
			 <option value="">Select Status</option>
			 <option value="1" <?php if($m3avenue['status']==1){ echo 'selected';}?>>Active</option>
			 <option value="2" <?php if($m3avenue['status']==2){ echo 'selected';}?>>Reject</option>
			</select>
		   </div>
		  
		   <div class="form-group">
		    <button type="submit" class="btn btn-success" name="loanssubmit" value="<?php echo $m3avenue['ID'];?>">Submit</button>
		   </div>
		  </form>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>

</div>
<script>$("#myModal").modal('show');</script>

  <?php } ?>
  <script>	
	 $(document).on('change','.itemready',function(){	
	 var checked = $(this).val();					
	 $.ajax({				
	 url : 'ajax/trackclientstatus.php',		
	 
	 type: "POST",		
	 data: {			
	 checked:checked			
	 },				
	 success: function(res){	
	 /* slience is golden */	
	 }					
	 });			
	 });
	 </script>

</body>
</html>