<?php
session_start();
error_reporting(E_ALL); 
include('../functions.php');
$userid = current_adminid(); 
if(empty($userid)){
	header("Location: login.php");
}
$message = '';
$user_unique_id = employee_details($userid,'unique_id');
$get_roles_array = [3,4];
$tree = employeehirerachy($user_unique_id,$get_roles_array);
//echo "<pre>";print_r($tree);exit;
$empString = !empty($tree) ? implode("','",array_column($tree,'ID')) : '';
$startTime  = new \DateTime('2010-01-01 00:00');
$endTime    = new \DateTime('2010-01-01 23:55');
$timeStep   = 5;
$timeArray  = array();

while($startTime <= $endTime)
{
    $timeArray[] = $startTime->format('H:i');
    $startTime->add(new \DateInterval('PT'.$timeStep.'M'));
}

$roles = roles();
$attendancedetails = runQuery("select * from employee_attendance where ID = '".$_GET['edit']."'"); 
$employeedetails = runQuery("select * from employee where ID = '".$attendancedetails['emp_id']."'");
$managers = runloopQuery("SELECT ID,unique_id,concat(fname,' ',lname) leadername FROM employee 
where ID in  ('".$empString."')  and role_id = '3'");

$executives = runloopQuery("SELECT ID,unique_id,concat(fname,' ',lname) leadername FROM employee 
where ID in  ('".$empString."')  and role_id = '4'");

if($employeedetails['role_id'] == '4'){
	$labeltext = 'Executive';
	$leader_primary = $executives;
}
else {
    $labeltext = 'Managers';
    $leader_primary = $managers;
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

						<div class="card-body">



				<form  method="post" action=""  autocomplete="off" >


						<div class="form-group row">
						    
							<label for="example-text-input"  class="col-3 col-form-label">Role</label>
							<div class="col-3">
	                            <select class="form-control"  name="role_id" id="role_id" onchange="rolebasedisplay()">
									<option value="">Select</option>
									<?php foreach($roles as $roleid => $rolename) { if ($roleid != '0' && $roleid != '1' && $roleid != '2' && $roleid != '5' && $roleid != '6') {?> 
	        									<option value="<?php echo $roleid; ?>" <?php if($employeedetails['role_id'] == $roleid) { echo 'selected'; } ?>><?php echo $rolename; ?></option>
	        								<?php } } ?>
								</select>
							</div>
							<label for="example-text-input" id="rolebaselabel" class="col-3 col-form-label"><?= @$labeltext ?? ''; ?></label>
							<?php if(count($leader_primary) == 0) $displayleadprimary = "none"; else $displayleadprimary = "block"; ?>
							<div class="col-3" id="rolebaseid" >
							<select class="form-control"  name="leader" id="leader" style="display:<?php echo $displayleadprimary; ?>">
									<option value="">Select</option>
									<?php for($i=0; $i < count($leader_primary); $i++) { 
										//if($employeedetails['unique_id'] != $leader_primary[$i]['unique_id']) {
										?>

									<option value="<?= $leader_primary[$i]['ID'] ; ?>" <?php if($leader_primary[$i]['ID'] == $attendancedetails['emp_id']) { ?> selected <?php } ?>>
									<?= $leader_primary[$i]['leadername'] ; ?></option>
									<?php //} 
								} ?>
								</select>							
							</div>
							</div><br>
							<div class="row mt-2">
									<div class="col">
    									<label for="attendance_date" class="font-weight-bold">Attendance Date</label>
    									<input class="form-control" type="text" name="attendance_date" id="attendance_date" value="<?php echo date('d-M-Y',strtotime($attendancedetails['attendance_date'])); ?>" required/>
										<input class="form-control" type="hidden" name="attendance_hiiden" id="attendance_hiiden" value="1" required/>
    								</div>
        						</div>
        						<div class="row mt-2">
        							<div class="col">
        								<label class="font-weight-bold">Clock In</label>
										<select id="clockin" name="clockin" class="form-control clockin" onchange="caltotalhours()">
											<option value="">Select Clock In</option>
											<?php foreach($timeArray as $key => $value) { ?>
												<option value="<?= $value; ?>" <?php if($attendancedetails['clock_in'] == $value) {  echo 'selected'; } ?>><?= $value; ?></option>
											<?php } ?>	
										</select>      
									</div>
        							<div class="col">
        								<label class="font-weight-bold">Clock Out</label>
										<select name="clockout" id="clockout" class="form-control clockout" onchange="caltotalhours()">
											<option value="">Select Clock Out</option>
											<?php foreach($timeArray as $key => $value) { ?>
												<option value="<?= $value; ?>" <?php if($attendancedetails['clock_out'] == $value) {  echo 'selected'; } ?>><?= $value; ?></option>
											<?php } ?>	
										</select>         							</div>
        						</div>

								<div class="row">
									<div class="col-6">    
										<label for="example-text-input" class="font-weight-bold">Total Work Hours</label>
										<input type="text" name="total_hours" id="total_hours" value="<?= $attendancedetails['total_hours'];?>" class="form-control" required>
									</div>
									<div class="col-6">    
										<label for="example-text-input" class="font-weight-bold">Total Break Hours</label>
										<input type="text" name="total_break_hours" id="total_break_hours" value="<?= $attendancedetails['total_break_hours'];?>" class="form-control" >
									</div>
								</div>

								<div class="row">
									<div class="col-6">    
										<label for="example-text-input" class="font-weight-bold">Total Effective Hours</label>
										<input type="text" name="effective_hours" id="effective_hours" value="<?= $attendancedetails['effective_hours'];?>" class="form-control" required>
									</div>
								</div>
 
                    <div class="form-group row">
                       <div class="col-3">
                             <button type="submit" name="submit" value="submit" class="btn btn-primary btn-elevate btn-pill">Update Attendance</button>
						</div>
                    </div> 
                    <br> 
					</form>
						
					<table id="tblAddRow" class="table table-bordered table-striped mt-2">
						<thead>
							<tr>
							    <th>Break In</th>
								<th>Break Out</th>
						    </tr>
						</thead>
						<tbody>
							<?php 
							$breakdetails = runloopQuery("select * from employee_break_history where attendance_id = '".$attendancedetails['ID']."'"); 
							if(count($breakdetails) > 0){
							for($b=0;$b < count($breakdetails); $b++) {
							?>
							<tr>
							    <td>
					
								<select name="breakin[]" class="form-control breakin" onchange="calculatebreakhrs()">
									<option value="">Select Break In</option>
									<?php foreach($timeArray as $key => $value) { ?>
										<option value="<?= $value; ?>" <?php if($breakdetails[$b]['break_in'] == $value) {  echo 'selected'; } ?>><?= $value; ?></option>
									<?php } ?>	
										</select>
								</td>
								<td>
								<select name="breakout[]" class="form-control breakout" onchange="calculatebreakhrs()">
									<option value="">Select Break Out</option>
									<?php foreach($timeArray as $key => $value) { ?>
										<option value="<?= $value; ?>" <?php if($breakdetails[$b]['break_out'] == $value) {  echo 'selected'; } ?> ><?= $value; ?></option>
									<?php } ?>	
										</select>								</td>
							</tr>
							<?php } }else{ ?>
								<tr>
								<td colspan="2">No Details Found</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
			 						
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

    <script>

	function rolebasedisplay()
	{
		$("#rolebaselabel").html('');
		var unique_id = '<?= $employeedetails['unique_id'] ; ?>';

		var role_id = $("#role_id").val();
		var rolesJson = '<?= json_encode(roles()); ?>'
        var roles = JSON.parse(rolesJson);
		if(role_id == '4') {
			$("#rolebaseid").show();
			$("#leader").show();
			var leaders = '<?= json_encode($executives) ; ?>';
			var label = 'Executives';
		}
		else if(role_id == '3') {
			$("#rolebaseid").show();
			$("#leader").show();
			var leaders = '<?= json_encode($managers) ; ?>';
			var label = 'Managers';
		}
		else {
			$("#rolebaseid").hide();
			return false;
		}
		$("#rolebaselabel").html(label);
		var leaderarray =JSON.parse(leaders);
		$("#leader").html('');
		$("#leader").append(`<option value = ''>Select</option>`);
		for(var i=0;i<leaderarray.length; i++) { 
			if(unique_id != leaderarray[i]['unique_id']) {
			$("#leader").append(`<option value = "${leaderarray[i]['ID']}">${leaderarray[i]['leadername']}</option>`)
			}
		}
	}
	</script>

</body>
</html>