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
		$file = strtolower(base_convert(time(), 10, 36) . '_pan_' . md5(microtime())).'.'.$extn;				
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
			  
				unset($_POST['addsubmitid']);
			  $result = insertQuery($_POST,'hire');
			  if(!$result){
				  header("Location: hire-list.php?success=success");
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
                        <h4 class="page-title">Hire list</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    	<button type="button" class="btn btn-brand btn-success btn-pill" style="float:right" data-toggle="modal" data-target="#newModal">Add Hire</button>    
					
                    </div>
                </div>
                <!-- /.row -->
                
                <div class="row"> 

				<div class="col-md-12 col-xs-12">
                        <div class="white-box">
						<?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Hire Added Succussfully
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
							<th>Candidate Name</th>
							<th>Mobile Number</th>
                            <th>Location</th>  
                            <th>Experience</th>
                            <th>Designation Opting</th>
                            <th>Scheduled Meet</th> 
                            <th>Current CTC</th> 
                            <th>Resume</th>  
                            <th>Reference</th>
							<th>Date</th>
                            <!--<th>Status:</th>-->
						</tr>
						
							</thead>
									<tbody>
									  <?php
	$sql = runloopQuery("SELECT * FROM hire where   emp_id = '".$userid."' order by ID desc");

	   $x=1;  foreach($sql as $row)
			{
		//	$path = '../offerletter/Offer_letters/'.$row["unique_id"].'_offerletter.pdf';
		$path = '../hiredocs/'.$row["resume_path"];
	?>
	<tr>
	<td><?php echo  $x;?></td>
	<td><?php echo $row["candidate_name"];?></td>
	<td><?php echo $row["mobile_number"];?></td>
	<td><?php echo $row["location"];?></td>
	<td><?php echo $row["experience"];?></td>
	<td><?php echo $row["designation_opting"];?></td> 
	<td><?php echo $row["sceduled_meet"];?></td>
	<td><?php echo $row["current_ctc"];?></td>
	<td>
	<?php if(!empty($row["resume_path"])){
		
		?>
	  <a href="<?= $path; ?>" download>Link </a>

	<?php  } ?>
	</td>
	<td><?php echo $row["reference"];?></td>
	<td><?php echo date('d-M-Y',strtotime($row["reg_date"]));?> </td>
	
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
          <h4 class="modal-title page-title">Add Hire</h4>
        </div>
        <div class="modal-body">
		<form  method="post" action="" enctype="multipart/form-data" id="addempform" autocomplete="off" > 
						<div class="row">
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Enter Candidate Name</label>
                        <div class="col-3">
                            <input class="form-control" name="candidate_name" type="text" placeholder="Enter Candidate Name" id="candidate_name" required>
                            <input class="form-control" name="addsubmitid" type="hidden"  id="addsubmitid" value="1">

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
						<label for="example-text-input" class="col-2 col-form-label">Scheduled Meet</label>
						<div class="col-3">
							<input class="form-control" type="datetime-local" name="sceduled_meet" placeholder="Enter Sceduled Meet" id="sceduled_meet" required>
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
						</div>   
						</div>
						</div>
					
					   
					</form> 
	</div>
        <div class="modal-footer">
		  <button type="submit" name="submit" value="submit" id="addempsubmitid" class="btn btn-brand btn-elevate btn-pill btn-pill btn-success">Add Hire</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>
		
	<script>
        
		$("#kt_table_1").DataTable({
			      "scrollX": true
			});

	$("#addempsubmitid").click(function(){
		var user_input_value;
		var err_value = 0
		$('#addempform').find('input,select').each(function(){
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
    </script>
</body>

</html>
