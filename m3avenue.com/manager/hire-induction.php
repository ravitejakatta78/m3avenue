<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');


$userid = current_managerid(); 

$employee_det = runQuery("select * from employee where ID = '".$userid."'");

if(empty($userid)){

	header("Location: index.php");

}

$message = '';
if(!empty($_POST['addsubmitid'])){
	
	if(!empty($_POST['induction_date'])){
		// include('../offerletter/offerletter.php');
		$pagerarray  = array();


	 
	 		  

			   
			  $_POST['emp_id'] = $userid; 
			  
				unset($_POST['addsubmitid']);
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
	<link href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />

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
                        <h4 class="page-title">Event list</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    	<button type="button" class="btn btn-brand btn-success btn-pill" style="float:right" data-toggle="modal" data-target="#newModal">Add Event</button>    
					
                    </div>
                </div>
                <!-- /.row -->
                
                <div class="row"> 

				<div class="col-md-12 col-xs-12">
                        <div class="white-box">
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
			<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>S.No</th>
							<th>Event Unique Id</th>
							<th>Event Title</th>
							<th>Event Date</th>
                            <th>Event Members</th>
                            <th>Event Description</th>  
                            <th>Action</th>
							<!--<th>Status:</th>-->
						</tr>
						
							</thead>
									<tbody>
									  <?php
	$sql = runloopQuery("SELECT hi.induction_unique_id,hi.ID,hi.induction_date,hi.induction_title,count(h.ID) induction_members,hi.induction_description FROM 
	hire_induction hi left join hire h on h.induction_id = hi.ID where   hi.emp_id = '".$userid."' 
	group by hi.induction_unique_id,hi.induction_date,hi.ID ,hi.induction_title
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
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
<?php include('footer.php');?>			
        </div>
        <!-- /#page-wrapper -->
    </div>


	
    <!-- /#wrapper -->
    <!-- jQuery -->
	<?php include('footerscripts.php');?>
	
  <!-- Modal -->
  <div class="modal fade" id="newModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title page-title">Add Event</h4>
        </div>
        <div class="modal-body">
		<form  method="post" action="" enctype="multipart/form-data" id="addempform" autocomplete="off" > 
						<div class="row">
						<div class="form-group col-md-12"> 
						<label for="example-text-input" class="col-2 col-form-label">Enter Event Title</label>
                        <div class="col-3">
                            <textarea class="form-control" name="induction_title"  placeholder="Enter Event Title" id="induction_title" required></textarea>
						</div>
						</div>
						<div class="form-group col-md-12"> 
						<label for="example-text-input" class="col-2 col-form-label">Enter Description</label>
                        <div class="col-3">
                            <textarea class="form-control" name="induction_description"  placeholder="Enter Event Description" id="induction_description" required></textarea>
						</div>
						</div> 
						<div class="form-group col-md-12"> 
						<label for="example-text-input" class="col-2 col-form-label">Enter Event Date</label>
                        <div class="col-3">
                            <input class="form-control" name="induction_date" type="datetime-local" placeholder="Enter Event Date" id="induction_date" required>
                            <input class="form-control" name="addsubmitid" type="hidden"  id="addsubmitid" value="1">
						</div>
						</div>
						
						


						</div>
					
					   
					</form> 
	</div>
        <div class="modal-footer">
		  <button type="submit" name="submit" value="submit" id="addempsubmitid" class="btn btn-brand btn-elevate btn-pill btn-pill btn-success">Add Event</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>

<!-- Modal -->
<div class="modal fade" id="hireModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title page-title">Add Invitee</h4>
        </div>
        <div class="modal-body">
		<form  method="post" action="" enctype="multipart/form-data" id="addhireform" autocomplete="off" > 
						<div class="row">
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Enter Candidate Name</label>
                        <div class="col-3">
                            <input class="form-control" name="candidate_name" type="text" placeholder="Enter Candidate Name" id="candidate_name" required>
                            <input class="form-control" name="hiresubmitid" type="hidden"  id="hiresubmitid" value="1">

						</div>
						</div> 
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Enter Mobile Number</label>
                        <div class="col-3">
                            <input class="form-control" name="mobile_number" type="text" placeholder="Enter Mobile Number" maxlength="10" pattern="\d{10}" id="mobile_number" required>
                        </div>
						</div>

						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Enter Location</label>
						<div class="col-3">
							<input class="form-control" type="text" required name="location" placeholder="Enter Location" id="location" required>
						</div>
						</div>
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Enter Experience</label>
						<div class="col-3">
							  <input type="text" id="experience" class="form-control" required  placeholder="Enter Experience"  name="experience" value=""  required >
						</div>
						</div>
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Designation Opting</label>
						<div class="col-3">
							<input class="form-control" type="text" name="designation_opting" placeholder="Enter Designation Opting" id="designation_opting" required>
						</div>   
						</div>
						

                        
                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Current CTC</label>
						<div class="col-3">
							<input class="form-control" type="text" name="current_ctc" placeholder="Enter Current CTC" id="current_ctc" >
						</div>   
						</div>

                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Resume Path</label>
						<div class="col-3">
							<input class="form-control" type="file" name="resume_path"  id="resume_path" >
						</div>   
						</div>

                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Reference</label>
						<div class="col-3">
							<input class="form-control" type="text" name="reference" placeholder="Enter Reference" id="reference" >
							<input class="form-control" type="hidden" name="induction_id" placeholder="Enter Reference" id="induction_id" >

						</div>   
						</div>
						</div>
					
					   
					</form> 
	</div>
        <div class="modal-footer">
		  <button type="submit" name="submit" value="submit" id="addhiresubmitid" class="btn btn-brand btn-elevate btn-pill btn-pill btn-success">Add Invitee</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>

<div class="modal fade" id="viewhireModal" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-lg" ">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title page-title">View Invitee</h4>
        </div>
        <div class="modal-body" id="hirebodyid">
		</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>
		
	<script>
        
		$("#kt_table_1").DataTable();

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

});
    </script>
</body>

</html>
