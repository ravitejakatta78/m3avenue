<?php
session_start();
error_reporting(E_ALL); 
include('../functions.php');
$userid = current_adminid(); 
$user_unique_id = employee_details($userid,'unique_id');

if(empty($userid)){
	header("Location: login.php");
} 
$message = '';

$monthstartdate = date('Y-m-01');
$currentdate = date('Y-m-d');

$teamdetails = runloopQuery("select *,concat(fname,' ',lname) name from employee where leader = '".$user_unique_id."' order by ID desc");
$teamselect = array_column($teamdetails,'name',"unique_id");


$message = '';
if(!empty($_POST['submit'])){
	if(!empty($_POST['clientname'])){
		$pagerarray  = array();
		$empid = mysqli_real_escape_string($conn,$_POST['employee_id']);
		$employeeid = runQuery("select * from employee where unique_id = '".$empid."'");
		if(!empty($employeeid['ID'])){ 
			$pagerarray['employee_id'] = $employeeid['ID']; 
			$pagerarray['clientname'] = mysqli_real_escape_string($conn,$_POST['clientname']);
			$pagerarray['regdate'] = mysqli_real_escape_string($conn,$_POST['regdate']);
			$pagerarray['mobile'] = mysqli_real_escape_string($conn,$_POST['mobile']);
			$pagerarray['servicetype'] = mysqli_real_escape_string($conn,$_POST['servicetype']);
			$pagerarray['bankname'] = mysqli_real_escape_string($conn,$_POST['bankname']);
			$pagerarray['loanamount'] = mysqli_real_escape_string($conn,$_POST['loanamount']);
			$pagerarray['status'] = '';
			$result = insertQuery($pagerarray,'clients');
			if(!$result){
				header("Location: financiar-list.php?success=success");
			} 
		}else{ 
			$message .="Invalid employee id";
		}
	}else{
		$message .="  client Name  Field is Empty";
	}

} 

if(!empty($_GET['delete'])){
	$sql = "DELETE FROM clients WHERE ID=".$_GET['delete']."";

	if ($conn->query($sql) === TRUE) {

		header("Location: financiar-list.php?success=success");

	} else {
		echo "Error deleting record: " . $conn->error;
	}
}
if(!empty($_POST['remarksubmit']) && !empty($_POST['remark'])){
	$statuscontent = mysqli_real_escape_string($conn,$_POST['remark']);
	
	$pagerarray['track_work_id'] = $_POST['remarksubmit'];
	$pagerarray['employee_id'] = $userid;
	$pagerarray['remark_desc'] = $statuscontent;
	
	$result = insertQuery($pagerarray,'remarks_history');

	if(!$result){
		header("Location: financiar-list.php?success=success");
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
            						<h4 class="page-title" style="color:#886CC0">Track Client</h4>
            						<a href="csv-loanenquires.php?table=clients"><button type="button" class="btn btn-primary" style="float:right" >Download</button></a> 
            					</div>
            					<div class="card-body">

            						<form method="get" action="">
            							<div class="form-group row">

            								<label for="example-text-input" class="col-md-1 col-form-label">Employee</label>
            								<div class="col-md-2">
            									<select name="employeeid" class="form-control">
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
            								<div class="col-md-3"  justify-content: center;" >
            									<button type="submit" class="btn btn-primary" style="float:right" >Submit</button>
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
            										<th>Employee ID</th>
            										<th>Client Name</th>
            										<th>Reg Date</th>
            										<th>Mobile</th>
            										<th>Service Type</th>
            										<th>Bank name</th>
            										<th>Loan/Invested Amount</th>
            										<th>Status</th>
            										<th>Reg date</th>
            										<th>Delete</th>
            									</tr>
            								</thead>
            								<tbody>
            									<?php
            									$startdate = !empty($_GET['sdate']) ? $_GET['sdate'] : $monthstartdate ;
            									$enddate = !empty($_GET['sdate']) ? $_GET['edate'] : $currentdate ;
            									if(!empty($_GET['employeeid'])){
            										$empid = employee_id($_GET['employeeid']);
            										$sql = runloopQuery("SELECT * FROM clients where employee_id = '".$empid."' and date(reg_date) between '".$startdate."' and '".$enddate."' order by ID desc");
            									}else if(!empty($_GET['clientmobile'])){ 
            										$empid = $_GET['clientmobile'];
            										$sql = runloopQuery("SELECT * FROM clients where mobile = '".$empid."' and date(reg_date) between '".$startdate."' and '".$enddate."' order by ID desc");
            									}else{
            										$sql = runloopQuery("SELECT * FROM clients where date(reg_date) between '".$startdate."' and '".$enddate."' order by ID desc");
            									}
            									$x=1;  foreach($sql as $row)
            									{
            										?>
            										<tr>
            											<td><?php echo  $x;?></td>
            											<td><?php echo employee_details($row["employee_id"],'fname');?>(<?php echo employee_details($row["employee_id"],'unique_id');?>)</td>
            											<td><?php echo $row["clientname"];?></td>
            											<td><?php echo $row["regdate"];?></td>
            											<td><?php echo $row["mobile"];?></td>
            											<td><?php echo $row["servicetype"];?></td>
            											<td><?php echo $row["bankname"];?></td>
            											<td><?php echo $row["loanamount"];?></td>
            											<td>
            												<?php if($row['status']==''||$row['status']=='Pending'){ echo 'Pending';}?>
            												<?php if($row['status']=='Approved'){ echo 'Approved';}?>
            												<?php if($row['status']=='Reject'){ echo 'Rejected';}?>    

            											</td>
            											<td><?php echo reg_date($row["reg_date"]);?></td>
            											<!--<td><a href="edit-employee-list.php?edit=<?php echo $row["ID"];?>">Edit</a></td>-->
            											<!--<td><a onclick="return confirm('Are you sure want to delete??');"  href="?delete=<?php echo $row["ID"];?>">Delete</a></td>-->
            											<td>
            												<?php if($row['track_work_id'] > 0 && !empty($row['track_work_id'])) { ?>
            													<a href="?remark=<?php echo $row["track_work_id"];?>"><i class="fa fa-pencil icon-border">Remark</i></a>
            												<?php } ?>
            											</td>
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

          <?php if(!empty($_GET['view'])){?>
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
		<?php $m3avenue = runQuery("select * from clients where ID = ".$_GET['view']."");?>

		   <div class="form-group">
		    <select class="form-control" name="status">
			 <option value="">Select Status</option>
			 <option value="Accept">Accept</option>
			 <option value="Reject">Reject</option>
			</select>
		   </div>
		   <div class="form-group">
		    <button type="submit" class="btn btn-success" name="submit" value="<?php echo $m3avenue['ID'];?>">Submit</button>
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
  	<?php if(!empty($_GET['remark'])){ 
	    echo $_GET['remark'];
$sql = runQuery("SELECT * FROM track_work where ID = '".$_REQUEST['remark']."' order by ID desc");
$remarks_history = runloopQuery("SELECT * FROM remarks_history where track_work_id = '".$_GET['remark']."' order by ID desc");
 
	?>
		<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Remark</h4>
      </div>
      <div class="modal-body">
       <form method="post" action="" >
           <div class="form-group">
				<label>Follow up date</label>
				<input type="date" placeholder="Follow up date" name="followup" min="<?= date('Y-m-d'); ?>" value="<?php echo $sql['followup'];?>" class="form-control form-control-line" required> 
			</div>
			<div class="form-group">
				<label>Remark</label>
				<textarea class="form-control"  name="remark" ></textarea>
			</div>
			<div class="form-group">
			 <button type="submit" class="btn btn-success" value="<?php echo $sql['ID'];?>" name="remarksubmit" >Submit</button>
			</div>
	   </form>
	   <table class="table table-bordered table-striped">
	       <tr>
	           <th>S.No</th>
	           <th>Employee Name</th>
	           <th>Remarks</th>
	           <th>Date</th>
	       </tr>
	       <?php
	       for($r=0; $r < count($remarks_history); $r++)
	       { ?>
	           <tr>
	               <td><?php echo ($r+1); ?></td>
	               <td><?php echo @employee_details($remarks_history[$r]['employee_id'],'fname')  ??  'admin'; ?></td>
	               <td><?php echo $remarks_history[$r]['remark_desc']; ?></td>
	               <td><?php echo date('Y-m-d H:i:s A',strtotime($remarks_history[$r]['reg_date'])); ?></td>

	           </tr>
	       <?php } ?>
	  </table>     
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>




	<script>
			$("#myModal").modal('show');
		</script>
	<?php }?>

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
	 $(document).on('change','.changestatus',function(){	
	 var checked = $(this).data('clientid');					
	 var changestatus = $(this).val();					
	 $.ajax({				
	 url : 'ajax/trackclientchangestatus.php',		
	 
	 type: "POST",		
	 data: {			
	 checked:checked,			
	 changestatus:changestatus			
	 },				
	 success: function(res){	
	 /* slience is golden */	
	 }					
	 });			
	 });
	 </script>


    </body>
    </html>