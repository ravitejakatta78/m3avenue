<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');


 
$userid = current_managerid(); 
$empdet = runQuery("select * from employee where ID = '".$userid."'");

$teamdetails = runloopQuery("select *,concat(fname,' ',lname) name from employee where leader = '".$empdet['unique_id']."'");
$teamselect = array_column($teamdetails,'name',"ID");
$teamselect[$userid] = $empdet['fname'].' '.$empdet['lname'];


if(empty($userid)){

	header("Location: index.php");

}
$monthstartdate = date('Y-m-01');
$currentdate = date('Y-m-d');
$message = '';
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



if(!empty($_GET['delete'])){
	$sql = "DELETE FROM clients WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: track-client.php?success=success");
   
} else {
    echo "Error deleting record: " . $conn->error;
}
}

if(!empty($_POST['remarksubmit'])  && !empty($_POST['remark'])){
	$statuscontent = mysqli_real_escape_string($conn,$_POST['remark']);
	
	$pagerarray['track_work_id'] = $_POST['remarksubmit'];
	$pagerarray['employee_id'] = $userid;
	$pagerarray['remark_desc'] = $statuscontent;
	$result = insertQuery($pagerarray,'remarks_history');

	if(!$result){
			header("Location: track-client.php?success=success");
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
    margin : 2px;
    border : 1px solid black;
}

</style> 
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

	<!-- end::Head -->
	<!-- start::Body -->
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
                        <h4 class="page-title">TRACK Client</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
					<ol class="breadcrumb">
                            <li><a href="#">Track Client</a></li>
                            <li class="active">TRACK  Client</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
            </div> 

            <button type="button" class="btn btn-brand btn-success btn-pill " style="margin-top:-25px ; margin-bottom: 10px;float:right" data-toggle="modal" data-target="#newModal">Add Client</button>    

			<div class="row">
                    <div class="col-sm-12">
                    	<?php if(!empty($_GET['statusupdate']) || !empty($_GET['success']) || !empty($message)){ ?>
                        <div class="white-box" id="temp">
						<?php if(!empty($_GET['success'])){?>
								
								<div class="alert alert-success alert-dismissible " role="alert">
  <strong>Track Client details Added Succussfully</strong> 
  <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="$('#temp').remove();">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
								<?php } ?>
								<?php if(!empty($message)){?>
								<div class="alert alert-danger alert-dismissible " role="alert">
  <strong><?=$message;?></strong> 
  <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="$('#temp').remove();">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
								<?php } ?>

							
						</div>
					<?php } ?>
					</div>
			</div>
			<div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">		
							<div class="kt-portlet kt-portlet--mobile">
								<div class="kt-portlet__head">
									<div class="kt-portlet__head-label">
										<h3 class="kt-portlet__head-title">Client Data</h3>
									</div>
								</div>
								<div class="kt-portlet__body">
									<form method="get" action="">
										<div class="form-group row">

										<label for="example-text-input" class="col-md-1 col-form-label">Employee</label>
										<div class="col-md-2">
										    <select name="employeeid" class="form-control">
							<option value="">select</option>
							<?php foreach($teamselect as $key => $value) {?>
								<option value="<?= $key ;?>" <?php if(isset($_REQUEST['employeeid'])) { if($_REQUEST['employeeid'] == $key) { ?> selected <?php } } ?>)><?= $value ;?></option>
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
							 <th>Action</th>  
						</tr>
					</thead>
					<tbody>
						 <?php
							 $startdate = !empty($_GET['sdate']) ? $_GET['sdate'] : $monthstartdate ;
							 $enddate = !empty($_GET['sdate']) ? $_GET['edate'] : $currentdate ;
						 if(!empty($_GET['employeeid'])){
							 $empid = ($_GET['employeeid']);

							$sql = runloopQuery("SELECT * FROM clients where employee_id = '".$empid."' and date(reg_date) between '".$startdate."' and '".$enddate."' order by ID desc");
						 }
						 else{
							$sql = runloopQuery("SELECT * FROM clients where employee_id = '".$userid."' and date(reg_date) between '".$startdate."' and '".$enddate."' UNION 
							SELECT * FROM clients 
							where employee_id in 
							(select ID from employee where leader = '".$empdet['unique_id']."') and date(reg_date) between '".$startdate."' and '".$enddate."' ");
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
    <?php if($row['status'] == "Pending") { ?>
<select class="form-control changestatus" name="clientstatus" id="clientstatus" data-clientid="<?php echo $row["ID"];?>">
	<option value="">--Select--</option>
	<option value="Pending" <?php if($row['status']==''||$row['status']=='Pending'){ echo 'selected';}?>>Pending</option>
	<option value="Approved" <?php if($row['status']=='Approved'){ echo 'selected';}?>>Approved</option>
	<option value="Rejected" <?php if($row['status']=='Reject'){ echo 'selected';}?>>Reject</option>
</select>
<?php } else { 
echo $row['status'] == 'Approved' ? 'Approved' :  'Reject';
} ?>
</td>
<td><?php echo reg_date($row["reg_date"]);?></td>
<!--<td><a href="edit-employee-list.php?edit=<?php echo $row["ID"];?>">Edit</a></td>
<td><a onclick="return confirm('Are you sure want to delete??');"  href="?delete=<?php echo $row["ID"];?>">Delete</a></td>-->

<td style="display:flex">
    <?php if(!empty($row["track_work_id"]) && $row["track_work_id"] > 0) { ?>
    <a href="?remark=<?php echo $row["track_work_id"];?>"><i class="fa fa-pencil icon-border"></i></a></td>
    <?php } else { echo "Added as direct client"; } ?>
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
<script>

	$("#myModal").modal('show');
</script>
  <?php } ?>
	<?php if(!empty($_GET['remark'])){
$sql = runQuery("SELECT * FROM track_work where ID = '".$_GET['remark']."' order by ID desc");
$remarks_history = runloopQuery("SELECT * FROM remarks_history where track_work_id = '".$_GET['remark']."' order by ID desc");

	?>
		<!-- Modal -->
<div id="myModal1" class="modal fade" role="dialog">
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
	               <td><?php echo $r+1; ?></td>
	               <td><?php echo @employee_details($remarks_history[$r]['employee_id'],'fname') ?? 'Admin'; ?></td>
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
			$("#myModal1").modal('show');
		</script>
	<?php }?>


<!--add Modal -->
<div class="modal fade" id="newModal" role="dialog">
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
						<label for="example-text-input" class="col-2 col-form-label">Client Name</label>
                        <div class="col-3">
                            <input class="form-control" type="text" name="clientname" id="clientname" value="<?php echo !empty($_POST['clientname']) ? $_POST['clientname']: '';?>" placeholder="Enter Client Name" required>
						</div>
						</div> 
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Employee Id</label>
                        <div class="col-3">
                             <input class="form-control" type="text" name="Employee Id" value="<?php echo !empty($_POST['employee_id']) ? $_POST['clientname']: '';?>" placeholder="Enter Employee Id" > 
                        </div>
						</div>

						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Reg Date</label>
						<div class="col-3">
							<input class="form-control" type="date" name="regdate" value="<?php echo !empty($_POST['regdate']) ? $_POST['regdate']: '';?>" placeholder="DD/MM/YYYY" >
						</div>
						</div>
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Mobile Number</label>
						<div class="col-3">
							  <input class="form-control" type="text" name="mobile" value="<?php echo !empty($_POST['mobile']) ? $_POST['mobile']: '';?>" placeholder="Enter Mobile Number" >
						</div>
						</div>
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Service type</label>
						<div class="col-3">
							<input class="form-control" type="text" name="servicetype" value="<?php echo !empty($_POST['servicetype']) ? $_POST['servicetype']: '';?>" placeholder="Enter Service type" >
						</div>   
						</div>
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Loan/Invested Amount</label>
						<div class="col-3">
							<input class="form-control" type="text" placeholder="Loan/Invested Amount"  value="<?php echo !empty($_POST['loanamount']) ? $_POST['loanamount']: '';?>" name="loanamount" >
						</div>   
						</div>
                        
                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Company name</label>
						<div class="col-3">
							<input class="form-control" type="text" name="companyname" value="<?php echo !empty($_POST['companyname']) ? $_POST['companyname']: '';?>" placeholder="Enter Company Name" > 
						</div>   
						</div>

                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Bank name</label>
						<div class="col-3">
							<input class="form-control" type="text" name="bankname" value="<?php echo !empty($_POST['bankname']) ? $_POST['bankname']: '';?>" placeholder="Enter Bank name" >
						</div>   
						</div>

                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Points type</label>
						<div class="col-3">
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
						<label for="example-text-input" class="col-2 col-form-label">Location</label>
						<div class="col-3">
							<input class="form-control" type="text" name="location" value="<?php echo !empty($_POST['location']) ? $_POST['location']: '';?>" placeholder="Enter location" >
						</div>   
						</div>
		
						
						</div>
					
					   
					</form> 
	</div>
        <div class="modal-footer">
		  <button type="submit" name="submit" value="submit" class="btn btn-brand btn-elevate btn-success" id="addclientsubmitid">Add Client</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
<!-- end of add modal -->

  <script>	
	$("#kt_table_1").DataTable();
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
	  $('.changestatus').on('change',function(){ 
		  
	 var checked = $(this).data('clientid');					
	 var changestatus = $("#clientstatus").val();
	 $.ajax({				
	 url : '../admin/ajax/trackclientchangestatus.php',		
	 
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