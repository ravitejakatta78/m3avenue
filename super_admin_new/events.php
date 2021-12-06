<?php
session_start();
error_reporting(E_ALL); 
include('../functions.php');
$userid = current_adminid(); 
if(empty($userid)){
	header("Location: login.php");
} 
$message = '';

$sqlsuperadmins = runloopQuery("select * from employee where role_id = 2 and status = 1");

if(!empty($_POST['addsubmitid'])){
	
	if(!empty($_POST['induction_date'])){
		// include('../offerletter/offerletter.php');
		$pagerarray  = array();

			  $_POST['emp_id'] = $_POST['superadmin']; 
			  
				unset($_POST['addsubmitid'],$_POST['superadmin']);
				$uniqueusers = (int)runQuery("select max(ID) as id from hire_induction order by ID desc")['id'];
				$newuniquid = $uniqueusers+1;
				$_POST['induction_unique_id'] = 'INDH'.sprintf('%05d',$newuniquid);
			  $result = insertQuery($_POST,'hire_induction');
			  if(!$result){
				  header("Location: hire-induction.php?success=success");
				  }
		
	}
}

if(!empty($_POST['hiresubmitid'])){
	
	if(!empty($_POST['mobile_number'])){
		// include('../offerletter/offerletter.php');
		$pagerarray  = array();

		if (!file_exists('../hiredocs')) {	
	  mkdir('../hiredocs', 0777, true);	
	  }
	  $target_dir = '../hiredocs/';									

	  $file = @$_FILES["resume_path"]['name'];	
	  if(@$file)	{
		$extn = pathinfo($file, PATHINFO_EXTENSION);
		$file = strtolower(base_convert(time(), 10, 36) . '_res_' . md5(microtime())).'.'.$extn;				
		$target_file = $target_dir . strtolower($file);		
		

	  $uploadOk = 1;
	  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);								

	  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	   && $imageFileType != "gif" && $imageFileType != "doc" && $imageFileType != "docx" )
	  {
	  $message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
	  $uploadOk = 0;						
	  }
	  if ($uploadOk == 0) {			
	  $message .= "Sorry, your file was not uploaded.";		
	  } else {
		   if (move_uploaded_file($_FILES["resume_path"]["tmp_name"], $target_file)){				
			   $_POST['resume_path'] = strtolower($file);						
			   } else {
				   $message .= "Sorry, There Was an Error Uploading Your File.";			
				   }
			  }
	}		  

			   
			  $_POST['emp_id'] = $userid; 
			  
				unset($_POST['hiresubmitid']);
			  $result = insertQuery($_POST,'hire');
			  if(!$result){
				  header("Location: hire-induction.php?success=success");
				  }
		
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
							<h4 class="page-title" style="color:#886CC0">Event list</h4>

							<button type="button" class="btn btn-primary" style="float:right" 
							data-bs-toggle="modal" data-bs-target=".add_employee_form"data-toggle="modal" data-target="#exampleModalScrollable">Add Event</button> 

							 
						</div>
						<div class="card-body">

							<?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Event Added Succussfully
								</div>
								<?php } ?>
						   <?php if(!empty($_GET['psuccess'])){?>
								<div class="alert alert-success">
								<?php echo $_GET['psuccess'];?>
								</div>
								<?php } ?>
								<?php if(!empty($message)){?>
								<div class="alert alert-danger">
								<?=$message?>
								</div>
								<?php } ?>

							<div class="table table-responsive">

								<!--begin: Datatable -->
								<table id="example3" style="min-width: 845px" class="table table-bordered table-hover table-checkable display">
									<thead>
										<tr>
											<th>S.No</th>
							<th>Event Unique Id</th>
							<th>Event Title</th>
							<th>Event Date</th>
                            <th>Event Members</th>
                            <th>Event Description</th>
							<th>Event Orginizer</th>  
                            <th>Action</th>
							<!--<th>Status:</th>-->
						</tr>
						
							</thead>
									<tbody>
									  <?php
	$sql = runloopQuery("SELECT hi.induction_unique_id,hi.emp_id,hi.ID,hi.induction_date,hi.induction_title,count(h.ID) induction_members,hi.induction_description FROM 
	hire_induction hi left join hire h on h.induction_id = hi.ID  
	group by hi.induction_unique_id,hi.induction_date,hi.ID ,hi.induction_title,hi.emp_id
	order by hi.ID desc");

	   $x=1;  foreach($sql as $row)
			{
	?>
	<tr>
	<td><?php echo  $x;?></td>
	<td><?php echo $row["induction_unique_id"];?></td>
	<td><?php echo $row["induction_title"];?></td>
	<td><?php echo $row["induction_date"];?></td>
	<td><?= $row["induction_members"]; ?></td>
	<td><?= $row["induction_description"]; ?></td>
	<td><?= employee_details($row["emp_id"],'fname'). ' '.employee_details($row["emp_id"],'lname'); ?></td>
	<td><button type="button" class="btn btn-brand btn-success btn-pill" onclick="addhire('<?= $row['ID']; ?>')">Add Invitee</button>
<button class="btn btn-brand btn-success btn-pill" onclick="viewhire('<?= $row['ID']; ?>')">View</button>
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

    <div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Event</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <form  method="post" action="" enctype="multipart/form-data" id="addempform" autocomplete="off" > 
						<div class="form-group row"> 
						<label for="example-text-input" class="col-md-2 col-form-label">Enter Event Title</label>
                        <div class="col-md-4">
                            <textarea class="form-control" name="induction_title"  placeholder="Enter Event Title" id="induction_title" required></textarea>
						</div>


						<label for="example-text-input" class="col-2 col-form-label">Enter Description</label>
                        <div class="col-4">
                            <textarea class="form-control" name="induction_description"  placeholder="Enter Event Description" id="induction_description" required></textarea>
						</div>
						</div> 
						<div class="form-group row"> 
						<label for="example-text-input" class="col-2 col-form-label">Enter Event Date</label>
                        <div class="col-4">
                            <input class="form-control" name="induction_date" type="datetime-local" placeholder="Enter Event Date" id="induction_date" required>
                            <input class="form-control" name="addsubmitid" type="hidden"  id="addsubmitid" value="1">
						</div>
						<label for="example-text-input" class="col-2 col-form-label">Super Admin</label>
                        <div class="col-4">
                          <select class="form-control select2 bigdrop" name="superadmin" >
						<option value="">Select</option>
						<?php for($s=0;$s<count($sqlsuperadmins);$s++){?>
							<option value="<?= $sqlsuperadmins[$s]['ID']; ?>"><?= $sqlsuperadmins[$s]['fname'].' '.$sqlsuperadmins[$s]['lname']; ?>
							</option>						
						<?php } ?>	
						</select>
						</div>
						
						</div>
						
						


					
					   
					</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addempsubmitid">Add Event</button>
      </div>
    </div>
  </div>
</div> 


	<?php include("footer_scripts.php");?>
	
<div class="modal fade" id="hireModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Invitee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <form  method="post" action="" enctype="multipart/form-data" id="addhireform" autocomplete="off" > 
						<div class="form-group row">

						<label for="example-text-input" class="col-md-2 col-form-label">Enter Candidate Name</label>
                        <div class="col-md-4">
                            <input class="form-control" name="candidate_name" type="text" placeholder="Enter Candidate Name" id="candidate_name" required>
                            <input class="form-control" name="hiresubmitid" type="hidden"  id="hiresubmitid" value="1">

						</div>

						

						<label for="example-text-input" class="col-md-2 col-form-label">Enter Mobile Number</label>
                        <div class="col-md-4">
                            <input class="form-control" name="mobile_number" type="text" placeholder="Enter Mobile Number" maxlength="10" pattern="\d{10}" id="mobile_number" required>
                        </div>
						</div>
						<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Enter Location</label>
						<div class="col-4">
							<input class="form-control" type="text" required name="location" placeholder="Enter Location" id="location" required>
						</div>
						

						<label for="example-text-input" class="col-2 col-form-label">Enter Experience</label>
						<div class="col-4">
							  <input type="text" id="experience" class="form-control" required  placeholder="Enter Experience"  name="experience" value=""  required >
						</div>
						</div>
						<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Designation Opting</label>
						<div class="col-4">
							<input class="form-control" type="text" name="designation_opting" placeholder="Enter Designation Opting" id="designation_opting" required>
						</div>   
						

                        

						<label for="example-text-input" class="col-2 col-form-label">Current CTC</label>
						<div class="col-4">
							<input class="form-control" type="text" name="current_ctc" placeholder="Enter Current CTC" id="current_ctc" >
						</div>   
			</div>
			<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Resume Path</label>
						<div class="col-4">
							<input class="form-control" type="file" name="resume_path"  id="resume_path" >
						</div>   


						<label for="example-text-input" class="col-2 col-form-label">Reference</label>
						<div class="col-4">
							<input class="form-control" type="text" name="reference" placeholder="Enter Reference" id="reference" >
							<input class="form-control" type="hidden" name="induction_id" placeholder="Enter Reference" id="induction_id" >

   
						</div>
						</div>
						
					
					   
					</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addhiresubmitid">Add Invitee</button>
      </div>
    </div>
  </div>
</div> 

<div class="modal fade" id="viewhireModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">View Invitee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="hirebodyid">
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div> 

<script>
		function addhire(inductionid){
		$("#induction_id").val(inductionid);
		$("#hireModal").modal();
	}

	function viewhire(inductionid){
		//$("#induction_id").val(inductionid);
		$.ajax({
            type: 'post',
            url: '../admin/ajax/commonajax.php',
            data: {
                inductionid: inductionid,
                action : 'hireview'
            },
            success: function(response) { 
				
				var str = '';
				str += '<div class="table table-responsive">\
<table class="table table-bordered table-hover table-checkable" id="kt_table_2">\
<thead>\
<tr>\
<th>S.No</th>\
<th>Candidate Name</th>\
<th>Mobile Number</th>\
<th>Location</th>\
<th>Experience</th>\
<th>Designation Opting</th>\
<th>Current CTC</th>\
<th>Resume</th>\
<th>Reference</th>\
<th>Date</th>\
</tr>\
</thead>\
   <tbody>';
   row = JSON.parse(response);
   for(var i=0;i<row.length;i++){
	var path = '../hiredocs/'+row[i]["resume_path"];

	str += '<tr>\
<td>'+(i+1)+'</td>\
<td>'+row[i]["candidate_name"]+'</td>\
<td>'+row[i]["mobile_number"]+'</td>\
<td>'+row[i]["location"]+'</td>\
<td>'+row[i]["experience"]+'</td>\
<td>'+row[i]["designation_opting"]+'</td>\
<td>'+row[i]["current_ctc"]+'</td>\
<td>';
	if(row[i]["resume_path"]){
		str += '<a href="'+path+'" download>Link </a>'; 
	  } 
	  str +='</td>\
<td>'+row[i]["reference"]+'</td>\
<td>'+row[i]["reg_date"]+'</td>\
</tr>';
	}
str +='</tbody></table></div>';
	$("#hirebodyid").html(str)
				//location.reload();
			}
			});
		$("#viewhireModal").modal();
	}

	

		$("#addhiresubmitid").click(function(){
		var user_input_value;
		var err_value = 0
		$('#addhireform').find('input,select').each(function(){
			if($(this).prop('required')){
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
			$("#addhiresubmitid").hide();
			$("#addhireform").submit();	
		}
		
	});		

			$("#addempsubmitid").click(function(){
		var user_input_value;
		var err_value = 0
		$('#addempform').find('input,select,date,textarea').each(function(){
			if($(this).prop('required')){
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
			$("#addempsubmitid").hide();
			$("#addempform").submit();	
		}
		
	});	
	$( document ).ready(function() {
				$("#kt_table_2").DataTable();
				$("#e1").select2({dropdownCssClass : 'bigdrop'}); 

});
	</script>

</body>
</html>