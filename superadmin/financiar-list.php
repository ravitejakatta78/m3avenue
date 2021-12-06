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

$monthstartdate = date('Y-m-01');
$currentdate = date('Y-m-d');

$teamdetails = runloopQuery("select *,concat(fname,' ',lname) name from employee where ID in ('".$empString."') order by ID desc");
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

$message = '';
if(!empty($_POST['add_client_submit'])){
	if(!empty($_POST['clientname'])){
	   $pagerarray  = array();
	   $empid = mysqli_real_escape_string($conn,$empdet['unique_id']);
			$employeeid = runQuery("select * from employee where unique_id = '".$empid."'");
			if(!empty($employeeid['ID'])){ 
                $pagerarray['employee_id'] = user_id(mysqli_real_escape_string($conn,$_POST['employee_id'])); 
                $pagerarray['clientname'] = mysqli_real_escape_string($conn,$_POST['clientname']);
                $pagerarray['regdate'] = mysqli_real_escape_string($conn,$_POST['regdate']);
                $pagerarray['mobile'] = mysqli_real_escape_string($conn,$_POST['mobile']);
                $pagerarray['servicetype'] = mysqli_real_escape_string($conn,$_POST['servicetype']);
                $pagerarray['bankname'] = mysqli_real_escape_string($conn,$_POST['bankname']);
                $pagerarray['loanamount'] = mysqli_real_escape_string($conn,$_POST['loanamount']);
                $pagerarray['companyname'] = mysqli_real_escape_string($conn,$_POST['companyname']);
                $pagerarray['pointstype'] = mysqli_real_escape_string($conn,$_POST['pointstype']);
                $pagerarray['location'] = mysqli_real_escape_string($conn,$_POST['location']);
                $pagerarray['status'] = 'Pending';
				$pagerarray['mod_date'] = date('Y-m-d H:i:s A');
	            $result = insertQuery($pagerarray,'clients');
				if(!$result){
					//emp_monthly_points_calculation(['empid' => $employeeid['ID']]);
					header("Location: track-client.php?success=success");
                    } 
				}else{ 
					$message .="Invalid employee id";
				}
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
 <style>
.tablestyle {
      border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;

}

.icon-border {
    padding : 8px;
    border : 1px solid black;
}

</style>
	<head>
 
		<!--end::Base Path -->
		<meta charset="utf-8" />
		<title>M3  | Dashboard</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />

		<?php include('headerscripts.php');?>
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--fixed kt-page--loading">

		<!-- begin:: Header Mobile -->
		<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
			<div class="kt-header-mobile__logo">
				<a href="demo1/index.html">
					<img alt="Logo" src="./assets/media/logos/logo-6.png" />
				</a>
			</div>
			<div class="kt-header-mobile__toolbar">
				<button class="kt-header-mobile__toolbar-toggler kt-header-mobile__toolbar-toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
				<button class="kt-header-mobile__toolbar-toggler" id="kt_header_mobile_toggler"><span></span></button>
				<button class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
			</div>
		</div>

		<!-- end:: Header Mobile -->

		<!-- begin:: Root -->
		<div class="kt-grid kt-grid--hor kt-grid--root">

			<!-- begin:: Page -->
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
 
<?php include('header.php');?>
 

				<!-- end:: Aside -->

				<!-- begin:: Wrapper -->
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

					<!-- begin:: Header -->

<?php include('headernav.php');?>
					<!-- end:: Header -->
					<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

					<!-- begin:: Subheader -->

<br>
<!-- end:: Subheader -->

						<!-- begin:: Content -->
				<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
		 
				<div class="kt-portlet kt-portlet--mobile">
					<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<div class="col-10">
								<h3 class="kt-portlet__head-title">Track Client</h3>
								</div>
								<a href="csv-loanenquires.php?table=clients" class="btn btn-primary" >Download</a>
								<button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#add_client_modal">Add Client</button>    

							</div>
						</div>
						<br>
						     <?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Track Client details Added Succussfully
								</div>
								<?php } ?>
								<?php if(!empty($message)){?>
								<div class="alert alert-danger">
								<?=$message?>
								</div>
								<?php } ?> 


		<div class="kt-portlet__body">
		<!--		<form method="get" action="">
					<div class="form-group row">
				
						<label for="example-text-input" class="col-2 col-form-label">Employee</label>
                        <div class="col-4">
                            <input class="form-control" type="text" placeholder="Employee id"  value="<?php echo !empty($_GET['employeeid']) ? $_GET['employeeid']: '';?>" name="employeeid" id="example-text-input">
                        </div>
                        <label for="example-text-input" class="col-2 col-form-label">Client mobile</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="clientmobile" value="<?php echo !empty($_GET['clientmobile']) ? $_GET['clientmobile']: '';?>" placeholder="client mobile" id="example-text-input">
                        </div>
                    </div>
					 <div class="form-group row">
                       <div class="col-4">
                             <button type="submit"  class="btn btn-brand btn-elevate btn-pill">Submit</button>
						</div>
                    </div> 
					</form>	 -->
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
										<label for="example-text-input" class="col-md-1 col-form-label">End Date</label>
										<div class="col-md-2">
										<input class="form-control" type="date" name="edate" value="<?= @$_GET['edate'] ?? date('Y-m-d');?>">
										</div>
										<div class="col-md-3 " style="display: flex;  justify-content: center;" >
										<button type="submit"  class="btn btn-success">Submit</button>
										</div>
										</div>
										 
									</form>

						<!--begin: Datatable -->
						                        		<div class="table table-responsive">
			<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1">
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
							$sql = runloopQuery("SELECT * FROM clients where date(reg_date) between '".$startdate."' and '".$enddate."'  and employee_id in ('".$empString."')  order by ID desc");
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
<!--<select class="form-control changestatus" name="status" data-clientid="<?php echo $row["ID"];?>">
	<option value="">--Select--</option>
	<option value="Pending" <?php if($row['status']==''||$row['status']=='Pending'){ echo 'selected';}?>>Pending</option>
	<option value="Approved" <?php if($row['status']=='Approved'){ echo 'selected';}?>>Approved</option>
	<option value="Rejected" <?php if($row['status']=='Reject'){ echo 'selected';}?>>Reject</option>
</select> -->
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
									<!--end: Datatable -->
							</div>
						</div>
					 
						<!-- end:: Content -->
			</div>  

						<!-- end:: Content -->
					

						<!-- end:: Content -->
					</div>

					<!-- begin:: Footer -->
					<div class="kt-footer kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop">
						<div class="kt-footer__copyright">
							2019&nbsp;&copy;&nbsp;<a href="https://sansdigitals.com" target="_blank" class="kt-link">sansdigitals.com</a>
						</div>
					 
					</div>
					<!-- end:: Footer -->
				</div>

				<!-- end:: Wrapper -->
			</div>

			<!-- end:: Page -->
		</div>

		<!-- end:: Root -->

	<?php include("footerscripts.php");?>


<!--add Modal -->
<div class="modal fade" id="add_client_modal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title page-title">Add Client</h4>
        </div>
        <div class="modal-body">
		<form  method="post" action="" enctype="multipart/form-data" id="addclientform" autocomplete="off" > 
						<div class="row">
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-12 col-form-label">Client Name</label>
                        <div class="col-12">
                            <input class="form-control" type="text" name="clientname" id="clientname" value="<?php echo !empty($_POST['clientname']) ? $_POST['clientname']: '';?>" placeholder="Enter Client Name" required>
						</div>
						</div> 
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-12 col-form-label">Employee Id</label>
                        <div class="col-12">
                             <input class="form-control" type="text" name="Employee Id" value="<?php echo !empty($_POST['employee_id']) ? $_POST['clientname']: '';?>" placeholder="Enter Employee Id" > 
                        </div>
						</div>

						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-12 col-form-label">Reg Date</label>
						<div class="col-12">
							<input class="form-control" type="date" name="regdate" value="<?php echo !empty($_POST['regdate']) ? $_POST['regdate']: '';?>" placeholder="DD/MM/YYYY" >
						</div>
						</div>
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-12 col-form-label">Mobile Number</label>
						<div class="col-12">
							  <input class="form-control" type="text" name="mobile" value="<?php echo !empty($_POST['mobile']) ? $_POST['mobile']: '';?>" placeholder="Enter Mobile Number" >
						</div>
						</div>
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-12 col-form-label">Service type</label>
						<div class="col-12">
							<input class="form-control" type="text" name="servicetype" value="<?php echo !empty($_POST['servicetype']) ? $_POST['servicetype']: '';?>" placeholder="Enter Service type" >
						</div>   
						</div>
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-12 col-form-label">Loan/Invested Amount</label>
						<div class="col-12">
							<input class="form-control" type="text" placeholder="Loan/Invested Amount"  value="<?php echo !empty($_POST['loanamount']) ? $_POST['loanamount']: '';?>" name="loanamount" >
						</div>   
						</div>
                        
                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-12 col-form-label">Company name</label>
						<div class="col-12">
							<input class="form-control" type="text" name="companyname" value="<?php echo !empty($_POST['companyname']) ? $_POST['companyname']: '';?>" placeholder="Enter Company Name" > 
						</div>   
						</div>

                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-12 col-form-label">Bank name</label>
						<div class="col-12">
							<input class="form-control" type="text" name="bankname" value="<?php echo !empty($_POST['bankname']) ? $_POST['bankname']: '';?>" placeholder="Enter Bank name" >
						</div>   
						</div>

                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-12 col-form-label">Points type</label>
						<div class="col-12">
							<select class="form-control" required name="pointstype" id="pointstype">
								<option value="">--select--</option>
								<?php $pointsetarray = runloopQuery("select * from pointset order by ID desc");
								foreach($pointsetarray as $pointset){
								?>
								<option value="<?php echo $pointset['ID'];?>"><?php echo $pointset['title'];?></option>
								<?php }?>
								</select>
						</div>   
						</div>

                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-12 col-form-label">Location</label>
						<div class="col-12">
							<input class="form-control" type="text" name="location" value="<?php echo !empty($_POST['location']) ? $_POST['location']: '';?>" placeholder="Enter location" >
						</div>   
						</div>
		
						
						</div>
					
					   
					</form> 
	</div>
        <div class="modal-footer">
		  <button type="submit" name="add_client_submit" value="submit" class="btn btn-brand btn-elevate btn-success" id="addclientsubmitid">Add Client</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
<!-- end of add modal -->	
		
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

	 $("#addclientsubmitid").click(function(){
		var user_input_value;
		var err_value = 0;
		$('#addclientform').find('input,select').each(function(){
			if($(this).prop('required')){
				user_input_value  = $("#"+this.id).val();
				if(user_input_value == ''){
					if(err_value == 0){
						document.getElementById(this.id).focus();
					}
					err_value = err_value + 1;
					$("#"+this.id).css('border-color', 'red');
					//alert(user_input_value+this.id);
				}else{
					$("#"+this.id).css('border-color', '#e4e7ea');
					
				}
			}	 
		});
		
		if(err_value == 0)
		{
			$("#addclientsubmitid").hide();
			$("#addclientform").submit();	
		}
		
	});

	 </script>
	</body>

	<!-- end::Body -->
</html>