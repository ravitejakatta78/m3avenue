<?php

session_start();
include('../functions.php');

$userid = current_adminid(); 
$user_unique_id = employee_details($userid,'unique_id');
$get_roles_array = [3,4];
$tree = employeehirerachy($user_unique_id,$get_roles_array);
//echo "<pre>";print_r($tree);exit;
$empString = !empty($tree) ? implode("','",array_column($tree,'ID')) : '';
if(empty($userid)){
    header("Location: index.php");
}

$message = '';

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


$managers = runloopQuery("SELECT ID,unique_id,concat(fname,' ',lname) leadername FROM employee 
where ID in  ('".$empString."')  and role_id = '3'");

$executives = runloopQuery("SELECT ID,unique_id,concat(fname,' ',lname) leadername FROM employee 
where ID in  ('".$empString."')  and role_id = '4'");


if(!empty($_POST['attendance_hiiden'])){
	$pagerarray  = array();
	$pagerarray['emp_id'] = $_POST['emp_id'];
	$pagerarray['attendance_date'] = date('Y-m-d',strtotime($_POST['attendance_date']));
	$pagerarray['clock_in'] = $_POST['clockin'];
	$pagerarray['clock_out'] = $_POST['clockout'];
	$pagerarray['total_hours'] = $_POST['total_hours'];
	$pagerarray['total_break_hours'] = $_POST['total_break_hours'];
	$pagerarray['effective_hours'] = $_POST['effective_hours'];
	$new_attendance_id = insertIDQuery($pagerarray,'employee_attendance');
	if($new_attendance_id){
		
		$breakin = $_POST['breakin'];
		$breakout = $_POST['breakout'];
		
		if(!empty($breakin[0]) && !empty($breakout[0])){
			for($i=0;$i<count($breakin);$i++){
				$breakarray = [];
				$breakarray['emp_id'] =  $_POST['emp_id'];
				$breakarray['attendance_id'] =  $new_attendance_id;
				$breakarray['break_in'] =  $breakin[$i];
				$breakarray['break_out'] =  $breakout[$i];
				$result = insertQuery($breakarray,'employee_break_history');
			}
		}
	}

	header("Location: attendance_list.php?success=success");

}

if(!empty($_GET['delete'])){
	$sql = "DELETE FROM employee_attendance WHERE ID=".$_GET['delete']."";
	$sqlbreak = "DELETE FROM employee_break_history WHERE attendance_id =".$_GET['delete']."";

	if ($conn->query($sql) === TRUE) {
		$conn->query($sqlbreak);
		header("Location: attendance_list.php?dsuccess=success");

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

			<!-- <div class="row page-titles">
				<ol class="breadcrumb">
					<li class="breadcrumb-item active"><a href="javascript:void(0)">EmployeesList</a></li>
					<li class="breadcrumb-item"><a href="javascript:void(0)">Datatable(emo</a></li>
				</ol>
			</div> -->
			<!-- row -->


			<div class="row">

				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<h4 class="page-title" style="color:#886CC0">Attendance list</h4>

							<button type="button" class="btn btn-primary" style="float:right" 
							data-bs-toggle="modal" data-bs-target=".add_employee_form"data-toggle="modal" data-target="#exampleModalScrollable">Add Attendance</button> 

						<!--	<a href="download-csv.php?table=employee&filter_by_role="><button type="button" class="btn btn-primary" style="float:right" >Download</button></a> --> 
						</div>
						<div class="card-body">
						<?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
									Attendance Added Successfully
								</div>
							<?php } ?>
							<?php if(!empty($_GET['dsuccess'])){?>
								<div class="alert alert-success">
									Deleted Successfully
								</div>
							<?php } ?>
							<div class="table table-responsive">
								<!--begin: Datatable -->
								<table id="example3" style="min-width: 845px" class="table table-bordered table-hover table-checkable display">
									<thead>
										<tr>
											<th>S.No</th>
											<th>Employee</th>
											<th>Reporting Manager</th>
                                            <th>Total Number of days</th>
										    <th>Present</th>
										    <th>Absent</th>
											<th>Leave</th>
											<th>Avg. Working Hrs</th>
											<th>Action</th>								
										</tr>
									</thead>
									<tbody>
									<?php 
										$sql = runloopQuery("SELECT ea.*,e.fname,e.lname,e.leader,e.unique_id FROM employee_attendance ea inner join employee e	on e.ID = ea.emp_id
										 where ea.emp_id in ('".$empString."')  order by ea.attendance_date desc");
										$x=1;  foreach($sql as $row)
										{
											$lead_details = lead_details($row["leader"]);
									?>
										<tr>
											<td><?= $x;?></td>
											<td><?php echo $row["fname"];?> <?php echo $row["lname"]."<br>".$row["unique_id"];?>
											<td><?php echo $lead_details['fname'].' '.$lead_details['lname']; ?></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td><a href="edit-attendance-list.php?edit=<?php echo $row["ID"];?>">Edit</a> | <a href="attendance_list.php?delete=<?php echo $row["ID"];?>" onclick="return confirm('Are you sure want to delete??');" >Delete</a></td>
										
										</tr>
									<?php 
										$x++; 
										} ?>
									</tbody>
								</table>
								<!--end: Datatable -->
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
	
      

        <div class="modal fade add_employee_form" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
        	<div class="modal-dialog modal-dialog-scrollable h-75 w-100 modal-lg"  role="document">
        		<div class="modal-content">
        			<div class="modal-header">
        				<h5 class="modal-title" id="exampleModalScrollableTitle">Add Employee</h5>
        				<button type="button" class="btn-close" data-bs-dismiss="modal">
                                                    </button>
        			</div>
        			<div class="modal-body">
        				<form method="post" action="" enctype="multipart/form-data" id="addattendanceform" autocomplete="off">
        					<div class="row">
        						<div class="col-6">    
        							<label for="example-text-input" class="font-weight-bold">Role</label>
        							<select class="form-control"  name="role_id" id="role_id" onchange="rolebasedisplay()" required>
        								<option value="">Select</option>
        								<?php foreach($roles as $roleid => $rolename) { if ($roleid != '0' && $roleid != '1' && $roleid != '2' && $roleid != '5' && $roleid != '6') {?> 
        									<option value="<?php echo $roleid; ?>"><?php echo $rolename; ?></option>
        								<?php } } ?>
        							</select>
        						</div>
        						<div class="col-6" id="rolebaseid" style="display:none">
        							<label for="example-text-input" id="rolebaselabel" class="font-weight-bold"></label>
        							<!-- <div class="col-4"  > -->

        								<select class="form-control"  name="emp_id" id="emp_id">

        								</select>

        								<!-- </div> -->
        							</div>

        						</div>
        						<div class="row mt-2">
									<div class="col">
    									<label for="attendance_date" class="font-weight-bold">Attendance Date</label>
    									<input class="form-control" type="text" name="attendance_date" id="attendance_date" value="<?php echo date('d-M-Y'); ?>" required/>
										<input class="form-control" type="hidden" name="attendance_hiiden" id="attendance_hiiden" value="1" required/>
    								</div>
        						</div>
        						<div class="row mt-2">
        							<div class="col">
        								<label class="font-weight-bold">Clock In</label>
										<select id="clockin" name="clockin" class="form-control clockin" onchange="caltotalhours()">
											<option value="">Select Clock In</option>
											<?php foreach($timeArray as $key => $value) { ?>
												<option value="<?= $value; ?>"><?= $value; ?></option>
											<?php } ?>	
										</select>      
									</div>
        							<div class="col">
        								<label class="font-weight-bold">Clock Out</label>
										<select name="clockout" id="clockout" class="form-control clockout" onchange="caltotalhours()">
											<option value="">Select Clock Out</option>
											<?php foreach($timeArray as $key => $value) { ?>
												<option value="<?= $value; ?>"><?= $value; ?></option>
											<?php } ?>	
										</select>         							</div>
        						</div>

								<div class="row">
									<div class="col-6">    
										<label for="example-text-input" class="font-weight-bold">Total Work Hours</label>
										<input type="text" name="total_hours" id="total_hours" class="form-control" required>
									</div>
									<div class="col-6">    
										<label for="example-text-input" class="font-weight-bold">Total Break Hours</label>
										<input type="text" name="total_break_hours" id="total_break_hours" class="form-control" >
									</div>
								</div>

								<div class="row">
									<div class="col-6">    
										<label for="example-text-input" class="font-weight-bold">Total Effective Hours</label>
										<input type="text" name="effective_hours" id="effective_hours" class="form-control" required>
									</div>
								</div>
			
									<table id="tblAddRow" class="table table-bordered table-striped mt-2">
						<thead>
							<tr>
							    <th>Break In</th>
								<th>Break Out</th>
						    </tr>
						</thead>
						<tbody>
							<tr>
							    <td>
					
								<select name="breakin[]" class="form-control breakin" onchange="calculatebreakhrs()">
									<option value="">Select Break In</option>
									<?php foreach($timeArray as $key => $value) { ?>
										<option value="<?= $value; ?>"><?= $value; ?></option>
									<?php } ?>	
										</select>
								</td>
								<td>
								<select name="breakout[]" class="form-control breakout" onchange="calculatebreakhrs()">
									<option value="">Select Break Out</option>
									<?php foreach($timeArray as $key => $value) { ?>
										<option value="<?= $value; ?>"><?= $value; ?></option>
									<?php } ?>	
										</select>								</td>
							</tr>
						</tbody>
					</table>

					<div class="modal-footer">
							<button id="btnAddRow" class="btn btn-success" type="button" >Add Row</button>
					</div>
        						</form>

        					</div>
        					<div class="modal-footer">
        						<button type="submit" name="submit" value="submit" id="addattendancesubmitid" class="btn btn-primary">Add Employee</button>
        						<button type="button" class="btn btn-default" data-bs-dismiss="modal" >Close</button>
        					</div>
        				</div>
        			</div>
        </div>



        		<script>
        			function rolebasedisplay()
        			{
        				$("#rolebaselabel").html('');
        				var role_id = $("#role_id").val();
        				if(role_id == '4') 
        				{
        					$("#rolebaseid").show();
        					var leaders = '<?= json_encode($executives) ; ?>';
        					var label = 'Executives';
        				}
        				else if(role_id == '3')
        				{
        					$("#rolebaseid").show();
							var leaders = '<?= json_encode($managers) ; ?>';
							var label = 'Managers';
						}
        				else 
        				{
        					$("#rolebaseid").hide();
        					return false;
        				}
        				$("#rolebaselabel").html(label);
	       				var leaderarray =JSON.parse(leaders);

        				$("#emp_id").html('');
        				$("#emp_id").append(`<option value = ''>Select</option>`);
        				for(var i=0;i<leaderarray.length; i++) { 
        					$("#emp_id").append(`<option value = "${leaderarray[i]['ID']}">${leaderarray[i]['leadername']}</option>`)
        				}
        			}

        $("#addattendancesubmitid").click(function(){
        	var user_input_value;
        	var err_value = 0
        	$('#addattendanceform').find('input,select,textarea').each(function(){
        		if($(this).prop('required'))
        		{
        			user_input_value  = $("#"+this.id).val();
        			if(user_input_value == ''){
        				if(err_value == 0){
        					document.getElementById(this.id).focus();
        				}
        				err_value = err_value + 1;
        				$("#"+this.id).css('border-color', 'red');
        			}else{
        				$("#"+this.id).css('border-color', '#e4e7ea');
        			}
        		}	 
        	});

        	

        	

        	if(err_value == 0)
        	{
        		$("#addattendancesubmitid").hide();
        		$("#addattendanceform").submit();	
        	}
        	
        });


		$('#tblAddRow tbody tr').find('td').parent() 
    		.append('<td><a href="#" class="delrow" onclick="calculatebreakhrs()"><i class="fa fa-trash border-red text-red"></i></a></td>');


		// Add row the table
		$('#btnAddRow').on('click', function() {
		    var lastRow = $('#tblAddRow tbody tr:last').html();
		    //alert(lastRow);
		    $('#tblAddRow tbody').append('<tr>' + lastRow + '</tr>');
		    $('#tblAddRow tbody tr:last input').val('');
		});
		// Delete row on click in the table
		$('#tblAddRow').on('click', 'tr a', function(e) {
		    var lenRow = $('#tblAddRow tbody tr').length;
		    e.preventDefault();
		    if (lenRow == 1 || lenRow <= 1) {
		        alert("Can't remove all row!");
		    } else {
		        $(this).parents('tr').remove();
		    }
		});

		

$('#attendance_date').datetimepicker({
	yearOffset:222,
	lang:'ch',
	timepicker:false,
	format:'d-M-Y',
	formatDate:'d-M-Y'

});




function caltotalhours(){
	var totalhrs = 0;
	var clock_in = $("#clockin").val();
	var clock_out = $("#clockout").val();
	clockin = clock_in.replace(":","");
	clockout = clock_out.replace(":","");
	totalhrs = parseInt(clockout) -  parseInt(clockin);
	if(totalhrs <= 0 && clock_out != ''){	
		alert("Please Provide valid clock in, clock out timings!!");
		$("#clockout").val('');
		return false;
	}
	else if(totalhrs > 0){
		var clock_in_arr  = clock_in.split(':');
		var clock_out_arr  = clock_out.split(':');
		var clock_in_mins = parseInt(clock_in_arr[0])*60 + parseInt(clock_in_arr[1]);
		var clock_out_mins = parseInt(clock_out_arr[0])*60 + parseInt(clock_out_arr[1]);
		var total_min = clock_out_mins - clock_in_mins;
		$("#total_hours").val((parseInt(total_min)/60).toFixed(2));
		effectivehrs();
	}
}

function calculatebreakhrs(){
	var breakInArr = [];
	var breakOutArr = [];
	var totalbreakhrs = 0;
	var total_break_min = 0;
	$('.breakin').each(function() {
	  breakInArr.push($(this).val()); 
    });

	$('.breakout').each(function() {
		breakOutArr.push($(this).val()); 
    });
	if(breakInArr.length == breakOutArr.length){
		for(var b=0;b < breakInArr.length;b++){
			breakin = breakInArr[b].replace(":","");
			breakout = breakOutArr[b].replace(":","");
			totalbreakhrs += parseInt(breakout) -  parseInt(breakin);
		
			var break_in_arr  = breakInArr[b].split(':');
			var break_out_arr  = breakOutArr[b].split(':');
			var break_in_mins = parseInt(break_in_arr[0])*60 + parseInt(break_in_arr[1]);
			var break_out_mins = parseInt(break_out_arr[0])*60 + parseInt(break_out_arr[1]);
			total_break_min += parseInt(break_out_mins) - parseInt(break_in_mins);
		}
	}

	if(total_break_min > 0){
		$("#total_break_hours").val((parseInt(total_break_min)/60).toFixed(2));
		effectivehrs();
	}
	else if(total_break_min <= 0){
		$("#total_break_hours").val('');
	}
}

function effectivehrs(){
	var total_hours = $("#total_hours").val();
	var total_break_hours = $("#total_break_hours").val();
	if(total_break_hours == ''){
		total_break_hours = 0;
	}
	if(total_hours != ''){
		var total_effective_mins = parseFloat(total_hours) * 60 - parseFloat(total_break_hours) * 60; 
		if(total_effective_mins > 0){
			var total_effective_hours =	(parseFloat(total_effective_mins) / 60).toFixed(2) 
			$("#effective_hours").val(total_effective_hours);
		}
		else{
			$("#effective_hours").val('');
		}
	}
	else{
		$("#effective_hours").val('');
	}
}
</script>


</body>
</html>