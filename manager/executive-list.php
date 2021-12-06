<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');


$userid = current_managerid(); 

$employee_det = runQuery("select * from employee where ID = '".$userid."'");

if(empty($userid)){

	header("Location: index.php");

}

if(!empty($_POST['add_ticket']))
{
	$pagerarray['requested_by']=mysqli_real_escape_string($conn,$employee_det['ID']);
	$pagerarray['requested_for'] = mysqli_real_escape_string($conn,$_POST['requested_for']);
	$pagerarray['ticket_type'] = mysqli_real_escape_string($conn,$_POST['ticket_type']);
	$pagerarray['ticket_summary'] = mysqli_real_escape_string($conn,$_POST['ticket_summary']);
	$pagerarray['ticket_status'] = mysqli_real_escape_string($conn,'pending');
	$result = insertQuery($pagerarray,'tbl_tickets');
	if(!$result){
		header("Location: executive-list.php?tsuccess=success");
	}

}

$message = '';
if(!empty($_POST['fname'])){
	
	if(!empty($_POST['mobile'])){
		
		$mobile_number = mysqli_real_escape_string($conn,$_POST['mobile']);
		$prev_mobile_det = runQuery("select * from employee where mobile = '".$mobile_number."'");
		if(!empty($prev_mobile_det))
		{
			$message .= "Mobile Number Already Taken.";
		}
		else{
		// include('../offerletter/offerletter.php');
		$pagerarray  = array();

		if (!file_exists('../executiveimage')) {	
	  mkdir('../executiveimage', 0777, true);	
	  }
	  $target_dir = '../executiveimage/';									

	  $file = $_FILES["panimg"]['name'];				
	  $target_file = $target_dir . strtolower($file);		

	  $uploadOk = 1;
	  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);								

	  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" )

		  {
	  $message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
	  $uploadOk = 0;						
	  }
	  if ($uploadOk == 0) {			
	  $message .= "Sorry, your file was not uploaded.";		
	  } else {
		   if (move_uploaded_file($_FILES["panimg"]["tmp_name"], $target_file)){				
			   $pagerarray['panimg'] = strtolower($file);						
			   } else {
				   $message .= "Sorry, There Was an Error Uploading Your File.";			
				   }
			  }
			  //adhaarimg
		
	  $file = $_FILES["adhaarimg"]['name'];				
	  $target_file = $target_dir . strtolower($file);		

	  $uploadOk = 1;
	  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);								

	  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" )

		  {
	  $message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
	  $uploadOk = 0;						
	  }
	  if ($uploadOk == 0) {			
	  $message .= "Sorry, your file was not uploaded.";		
	  } else {
		  if (move_uploaded_file($_FILES["adhaarimg"]["tmp_name"], $target_file)){				
			  $pagerarray['adhaarimg'] = strtolower($file);						
			  } else {
				  $message .= "Sorry, There Was an Error Uploading Your File.";			
				  }
			  }
			  $uniqueusers = (int)runQuery("select max(ID) as id from employee order by ID desc")['id'];
			  $newuniquid = $uniqueusers+1;
			   
			  $pagerarray['role_id'] = 4; 
			  $pagerarray['leader'] = $employee_det['unique_id']; 
			  $pagerarray['fname'] = mysqli_real_escape_string($conn,$_POST['fname']);
			  $pagerarray['lname'] = mysqli_real_escape_string($conn,$_POST['lname']); 
			  
			  $pagerarray['unique_id'] = 'M3E'.sprintf('%05d',$newuniquid);
			  $pagerarray['email'] = mysqli_real_escape_string($conn,$_POST['email']);
			  $pagerarray['mobile'] = mysqli_real_escape_string($conn,$_POST['mobile']);
			  $pagerarray['password'] = password_hash(trim($_POST['password']),PASSWORD_DEFAULT); 
			  $pagerarray['location'] = mysqli_real_escape_string($conn,$_POST['location']);
			  $pagerarray['joining_date'] = mysqli_real_escape_string($conn,$_POST['join_date']);
			  $pagerarray['income'] = mysqli_real_escape_string($conn,$_POST['ctc']);
			  $pagerarray['bankdetails'] = mysqli_real_escape_string($conn,$_POST['bankdetails']);
			  $pagerarray['accntnum'] = mysqli_real_escape_string($conn,$_POST['accntnum']);
			  $pagerarray['ifsccode'] = mysqli_real_escape_string($conn,$_POST['ifsccode']);
			  $pagerarray['payment_type'] = mysqli_real_escape_string($conn,$_POST['payment']);
			  $pagerarray['designation'] = mysqli_real_escape_string($conn,$_POST['designation']);
			  $address = mysqli_real_escape_string($conn,$_POST['house_no']).", ".mysqli_real_escape_string($conn,$_POST['street']).", ".mysqli_real_escape_string($conn,$_POST['city']).", ".
						  mysqli_real_escape_string($conn,$_POST['district']).", ".mysqli_real_escape_string($conn,$_POST['pin_code']);
			  $pagerarray['address'] = $address;
			  $pagerarray['status'] = '0'; 
			  $pagerarray['pannum'] = mysqli_real_escape_string($conn,$_POST['pannum']);

			  $result = insertQuery($pagerarray,'employee');
			  if(!$result){
				  header("Location: executive-list.php?success=success");
				  }
		}
	}
}


if(!empty($_GET['delete'])){
	$sql = "DELETE FROM employee WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: executive-list.php?success=success");
   
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
                        <h4 class="page-title">Executive list</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    	<button type="button" class="btn btn-brand btn-success btn-pill" style="float:right" data-toggle="modal" data-target="#newModal">Add Employee</button>    
					
                    </div>
                </div>
                <!-- /.row -->
                
                <div class="row"> 

				<div class="col-md-12 col-xs-12">
                        <div class="white-box">
						<?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Executive Added Succussfully
								</div>
								<?php } ?>
						<?php if(!empty($_GET['tsuccess'])){?>
								<div class="alert alert-success">
								Ticket Added Succussfully
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
						<a href="csv-executive.php" class="btn btn-brand btn-success btn-pill" style="margin-bottom:20px;float:right">Download</a>
			
							
							 <div class="table table-responsive">

					 			<!--begin: Datatable -->
			<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>S.No</th>
							<th>Executive Id</th>
							<th>Campaigns</th>
                            <th>Full Name</th>  
                            <th>Pan Card</th>
                            <th>Adhaar Card</th>
                            <th>Location</th> 
                            <th>Reports</th> 
                            <th>Logs</th>  
                            <th>Reg date</th>
							<th>Offer Letter</th>
                            <th>Delete</th>
							<!--<th>Status:</th>-->
						</tr>
						
							</thead>
									<tbody>
									  <?php
	$sql = runloopQuery("SELECT * FROM employee where role_id = '4' and leader = '".$employee_det['unique_id']."' and status=1 order by ID desc");

	   $x=1;  foreach($sql as $row)
			{
			$campaignnames = runQuery("SELECT group_concat(title) as campname FROM campaigns WHERE  FIND_IN_SET('".$row['ID']."',executive)");
			$path = '../offerletter/Offer_letters/'.$row["unique_id"].'_offerletter.pdf';
			
	?>
	<tr>
	<td><?php echo  $x;?></td>
	<td><?php echo $row["unique_id"];?></td>
	<td><?php echo $campaignnames["campname"];?></td>
	<td><?php echo $row["fname"]." ".$row["lname"];?>
	<br/><?php echo $row["email"];?>
	<br/><?php echo $row["mobile"];?></td> 
	<td><?php if($row["panimg"]){?><a href="../executiveimage/<?php echo $row["panimg"];?>" class="html5lightbox" >View</a><?php }?></td>
	<td><?php if($row["adhaarimg"]){?><a href="../executiveimage/<?php echo $row["adhaarimg"];?>" class="html5lightbox" >View</a><?php }?></td> 
	<td><?php echo $row["location"];?></td> 
	<td><a class="btn btn-warning" href="executive-reports.php?executive=<?php echo $row["ID"];?>">Reports</a></td> 
	<td><a class="btn btn-info" href="executive-logs.php?executive=<?php echo $row["ID"];?>">Logs</a></td>  
	<td><?php echo reg_date($row["reg_date"]);?> </td>
	
	<td>
	<?php if(file_exists($path)){ ?>
	<a class="btn btn-info" target="_blank" href="../offerletter/Offer_letters/<?php echo $row["unique_id"]?>_offerletter.pdf">Download</a>  
	<?php } else { echo "Not Yet Generated"; } ?>
		</td>
	
	<td><a class="delete_employee2" data-id="<?php echo $row["ID"];?>""  onclick="return confirm('Are you sure want to Edit??');" href="edit-executive-list.php?edit=<?php echo $row["ID"];?>">Edit</a> | 
	<a onclick="return confirm('Are you sure want to delete??');"  href="#" class="delete_employee" 
	data-id="<?php echo $row["ID"];?>" //href="?delete=<?php echo $row["ID"];?>">Delete</a></td>
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
	<div class="modal fade" id="delete_ticket_model" role="dialog">
    	<div class="modal-dialog">
		<form  method="post" action="" enctype="multipart/form-data" id="addticket" autocomplete="off" > 

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title page-title">Ticket for Update/Delete Emplyee</h4>
			</div>
        	<div class="modal-body">
				<div class="row">
					<div class="form-group col-md-12"> 
						<label for="example-text-input" class="col-2 col-form-label">Select Ticket Type</label>
						<div class="col-12">
							<select class="form-control" name="ticket_type" required>
								<option value="">--Select--</option>
								<option value="edit">Edit Employee Details</option>
								<option value="delete">Delete Employee</option>
							</select>
						</div><br>
						<label for="example-text-input" class="col-2 col-form-label">Ticket Summary</label>
						<div class="col-12">
							<textarea class="form-control" name="ticket_summary" rows="4"  placeholder="Enter User Details and Reason for delete/update Employee" required></textarea>
						</div>

						<input class="form-control" name="requested_for" type="hidden"  id="requested_for" required />

						
					</div> 
				</div> 	
		</div>
        <div class="modal-footer">
		  <button type="submit" name="add_ticket" value="submit"  class="btn btn-brand btn-elevate btn-pill btn-pill btn-success">Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
	  </form>

    </div>
  </div>
	
  <!-- Modal -->
  <div class="modal fade" id="newModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title page-title">Add Employee</h4>
        </div>
        <div class="modal-body">
		<form  method="post" action="" enctype="multipart/form-data" id="addempform" autocomplete="off" > 
						<div class="row">
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Enter First Name</label>
                        <div class="col-3">
                            <input class="form-control" name="fname" type="text" placeholder="Enter First Name" id="fname" required>
						</div>
						</div> 
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Enter Last Name</label>
                        <div class="col-3">
                            <input class="form-control" name="lname" type="text" placeholder="Enter Last Name" id="lname" required>
                        </div>
						</div>

						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Enter Email</label>
						<div class="col-3">
							<input class="form-control" type="email" required name="email" placeholder="Enter executive email" id="email" required>
						</div>
						</div>
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Enter Mobile Number</label>
						<div class="col-3">
							  <input type="text" id="mobile" class="form-control" required onkeypress="return isNumberKey(event)" placeholder="Mobile No"  name="mobile" value="" maxlength="10" pattern="\d{10}" required >
						</div>
						</div>
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Password</label>
						<div class="col-3">
							<input class="form-control" type="password" name="password" placeholder="Enter Password" id="password" required>
						</div>   
						</div>
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">House No</label>
						<div class="col-3">
							<input class="form-control" type="text" name="house_no" placeholder="Enter House No" id="house_no" required>
						</div>   
						</div>
                        
                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Street</label>
						<div class="col-3">
							<input class="form-control" type="text" name="street" placeholder="Enter street" id="street" required>
						</div>   
						</div>

                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">City</label>
						<div class="col-3">
							<input class="form-control" type="text" name="city" placeholder="Enter City" id="city" required>
						</div>   
						</div>

                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">District</label>
						<div class="col-3">
							<input class="form-control" type="text" name="district" placeholder="Enter District" id="district" required>
						</div>   
						</div>

                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Pin code</label>
						<div class="col-3">
							<input class="form-control" type="number" name="pin_code" placeholder="Enter pincode" id="pin_code" required>
						</div>   
						</div>

                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Joining Date</label>
						<div class="col-3">
                            <input class="form-control" type="date" name="join_date" id="join_date" value="<?php echo date('Y-m-d'); ?>" required/>
						</div>   
						</div>

						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label" >Payment Type</label>
						<div class="col-3">
							<select class="form-select form-control" aria-label="Default select example" name='payment' id="payment" required>
							<option value="">Select</option>
							<option value="review_pay">Review Pay</option>
							<option value="variable_pay">Variable Pay</option>
							<option value="royalty_pay">Royalty Pay</option>
							</select>
						</div>
						</div>				
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">CTC</label>
						<div class="col-3">
							<input class="form-control" type="number" name="ctc" placeholder="CTC" id="ctc" required>
						</div>   
						</div>
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">PAN Number</label>
						<div class="col-3">
							<input class="form-control" type="text" name="pannum" placeholder="PAN Number" id="pannum" required>
						</div>   
						</div>

						<div class="form-group col-md-12"> 
						<label for="example-text-input" class="col-2 col-form-label">Enter Bank Details</label>
						<div class="form-group row">
                        
                        <div class="col-md-4">
                            <input class="form-control" type="text" name="bankdetails" placeholder="Enter Bank Name" id="bankdetails">
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text" name="accntnum" placeholder="Enter Account Number" id="accntnum">
                        </div>
						<div class="col-md-4">
                            <input class="form-control" type="text" name="ifsccode" placeholder="Enter IFSC Code" id="ifsccode">
                        </div>
                    </div>
					</div>

						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label" >Designation</label>
						<div class="col-3">
							<select class="form-select form-control" aria-label="Default select example" name="designation" required id="designation">
							<option value="">Select</option>
							<option value="Telecaller">Telecaller</option>
							<option value="Business Development Associate">Business Development Associate</option>
							<option value="Digital Marketing Executive">Digital Marketing Executive</option>
							<option value="HR Manager">HR Manager</option>
							<option value="Team Leader">Team Leader</option>
							</select>
						</div>
						</div>	
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Location</label>
						<div class="col-3">
							<input class="form-control" type="text" name="location"     placeholder="Enter location" id="location" required>
						</div> 
						</div>			

						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Upload PAN Card</label>
						<div class="col-3">
							<div class="custom-file">
								<input type="file" class="custom-file-input" name="panimg" id="panimg">
								<label class="custom-file-label" for="customFile">Choose file</label>
							</div>
						</div>
						</div>
						<div class="form-group col-md-6"> 
						
						<label for="example-text-input" class="col-2 col-form-label">Upload ADHAAR Card</label>
						<div class="col-3">
							<div class="custom-file">
								<input type="file" class="custom-file-input" name="adhaarimg" id="adhaarimg">
								<label class="custom-file-label" for="customFile">Choose file</label>
							</div>
						</div>
						</div>

						
						</div>
					
					   
					</form> 
	</div>
        <div class="modal-footer">
		  <button type="submit" name="submit" value="submit" id="addempsubmitid" class="btn btn-brand btn-elevate btn-pill btn-pill btn-success">Add Executive</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>
		
	<script>

		$('.delete_employee').on('click',function()
		{
			var id=$(this).data('id');
			$('#delete_ticket_model').modal('show');
			$('#requested_for').val(id);
		});
        document.getElementById('payment').onchange = function () 
        {
            if (this.value == 'royalty_pay') 
            {
                //document.getElementById("ctc").disabled = true;
                //document.getElementsByName('ctc')[0].placeholder='Disabled';
            }

            else 
            {
                document.getElementById("ctc").disabled= false;
                document.getElementsByName('ctc')[0].placeholder='CTC';
			}
        }
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
		$('#addempform').find('input[type=file]').each(function(){
			if($('#'+this.id)[0].files.length === 0){
				err_value = err_value + 1;
				$("#"+this.id).css('border','1px solid red','padding','2px');
			}
			else{
				var fileInput = document.getElementById(this.id);
				var filePath = fileInput.value;
				// Allowing file type
				var allowedExtensions = 
						/(\.jpg|\.jpeg|\.png|\.gif)$/i;
				if (!allowedExtensions.exec(filePath)) {
					err_value = err_value + 1;
					alert('Invalid file type');
					$("#"+this.id).css('border','1px solid red','padding','2px');
					fileInput.value = '';
					
				}
				else{
					$("#"+this.id).css('border','1px solid black','padding','2px');
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
