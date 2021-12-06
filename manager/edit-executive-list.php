<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');
 
$userid = current_managerid(); 
$employee_det = runQuery("select * from employee where ID = '".$userid."'");

if(empty($userid)){

	header("Location: index.php");

}
$edit_id=$_GET['edit'];
$executivedetails = runQuery("select * from employee where ID = '".$_GET['edit']."'");
$message = '';
// if(!empty($_POST['submit'])){
// 			if(!empty($_POST['fname'])){
// 	   $pagerarray  = array();

//                $pagewererarray['ID'] = $executivedetails['ID'];  
        
//                $pagerarray['role_id'] = 4; 
//                $pagerarray['leader'] = $employee_det['unique_id']; 
//                $pagerarray['fname'] = mysqli_real_escape_string($conn,$_POST['fname']);
//                $pagerarray['lname'] = mysqli_real_escape_string($conn,$_POST['lname']); 
               
//                $pagerarray['email'] = mysqli_real_escape_string($conn,$_POST['email']);
//                $pagerarray['mobile'] = mysqli_real_escape_string($conn,$_POST['mobile']);
//                $pagerarray['location'] = mysqli_real_escape_string($conn,$_POST['location']);
//                $pagerarray['joining_date'] = mysqli_real_escape_string($conn,$_POST['join_date']);
//                $pagerarray['income'] = mysqli_real_escape_string($conn,$_POST['ctc']);
//                $pagerarray['bankdetails'] = mysqli_real_escape_string($conn,$_POST['bankdetails']);
//                $pagerarray['accntnum'] = mysqli_real_escape_string($conn,$_POST['accntnum']);
//                $pagerarray['ifsccode'] = mysqli_real_escape_string($conn,$_POST['ifsccode']);
//                $pagerarray['payment_type'] = mysqli_real_escape_string($conn,$_POST['payment']);
//                $pagerarray['designation'] = mysqli_real_escape_string($conn,$_POST['designation']);
//                $pagerarray['address'] = mysqli_real_escape_string($conn,$_POST['address']);;
//                $pagerarray['status'] = 1;
//                $pagerarray['pannum'] = mysqli_real_escape_string($conn,$_POST['pannum']);;


         
// 	            $result = updateQuery($pagerarray,'employee',$pagewererarray);
// 				if(!$result){
// 					header("Location: executive-list.php?success=success");
//                     }
	    
// 		 	}else{

// 		$message .=" First Name Field is Empty";

// 	}

// }

if(!empty($_POST['password-submit'])){
					if(!empty($_POST['new_pass'])){
						if(!empty($_POST['confirm_pass'])){
							if($_POST['new_pass']===$_POST['confirm_pass']){
								$newepass = mysqli_real_escape_string($conn,$_POST['new_pass']);
								$confirmepass = mysqli_real_escape_string($conn,$_POST['confirm_pass']);
								$id = $executivedetails['ID'];
									$newpass = password_hash($newepass,PASSWORD_DEFAULT);
									$insert_sql = "update executive set password = '".$newpass."' where ID = $id";
									if($conn->query($insert_sql)===TRUE){ 
										header("location: executive-list.php?psuccess=Password Changed successfully");
										}else{
											$message .=$conn->error;
										}
									}else{
									$message .="Password mistach";
									}
								}else{
								$message .="please enter the confirm password";	
								}
							}else{	
							$message .="please enter the new password";	
							}	
			}
			

if(!empty($_GET['delete'])){
	$sql = "DELETE FROM executive WHERE ID=".$_GET['delete']."";

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
                        <ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li class="active">Executive list</li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
                <!-- .row -->
                <div class="row"> 
                    <div class="col-md-12 col-xs-12">
                        <div class="white-box">
							   <?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Executive list Added Succussfully
								</div>
								<?php } ?>
								<?php if(!empty($message)){?>
								<div class="alert alert-danger">
								<?=$message?>
								</div>
								<?php } ?>
		 <form  method="post" action="javascript:void(0)" id="employee_edit_form" enctype="multipart/form-data" autocomplete="off" >
					 
         <div class="row">
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Enter First Name</label>
                        <div class="col-3">
                            <input class="form-control" name="fname" value="<?= $executivedetails['fname']; ?>" required type="text" placeholder="Enter First Name" id="example-text-input">
                        </div>
						</div> 
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Enter Last Name</label>
                        <div class="col-3">
                            <input class="form-control" name="lname" value="<?= $executivedetails['lname']; ?>" required type="text" placeholder="Enter Last Name" id="example-text-input">
                        </div>
						</div>

						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Enter Email</label>
						<div class="col-3">
							<input class="form-control" type="email" value="<?= $executivedetails['email']; ?>" required required name="email" placeholder="Enter executive email" id="example-text-input">
						</div>
						</div>
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Enter Mobile Number</label>
						<div class="col-3">
							  <input type="text" class="form-control form-control-line" required onkeypress="return isNumberKey(event)" required placeholder="Mobile No"  name="mobile" value="<?= $executivedetails['mobile']; ?>" value="" maxlength="10" pattern="\d{10}"  >
						</div>
						</div>

						
                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Pin code</label>
						<div class="col-3">
							<input class="form-control" type="number" name="pin_code" value="<?= $executivedetails['pin_code']; ?>"  placeholder="Enter pincode" id="example-text-input">
						</div>   
						</div>

                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Joining Date</label>
						<div class="col-3">
                            <input class="form-control" type="date" name="join_date" value="<?= $executivedetails['joining_date']; ?>"  required id="example-text-input" value="<?php echo date('Y-m-d'); ?>" required/>
						</div>   
						</div>

						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label" >Payment Type</label>
						<div class="col-3">
							<select class="form-select form-control" aria-label="Default select example" name='payment' id="payment" required>
							<option value="">Select</option>
							<option value="review_pay" <?php if($executivedetails['payment_type'] == 'review_pay') { echo 'Selected' ; } ?>>Review Pay</option>
							<option value="variable_pay" <?php if($executivedetails['payment_type'] == 'variable_pay') { echo 'Selected' ; } ?>Variable Pay</option>
							<option value="royalty_pay" <?php if($executivedetails['payment_type'] == 'royalty_pay') { echo 'Selected' ; } ?>Royalty Pay</option>
							</select>
						</div>
						</div>				
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">CTC</label>
						<div class="col-3">
							<input class="form-control" type="number" value="<?= $executivedetails['income']; ?>" name="ctc" placeholder="CTC" id="ctc" required>
						</div>   
						</div>
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">PAN Number</label>
						<div class="col-3">
							<input class="form-control" type="text" value="<?= $executivedetails['pannum']; ?>" name="pannum" placeholder="PAN Number" id="pannum" required>
						</div>   
						</div>

						<div class="form-group col-md-12"> 
						<label for="example-text-input" class="col-2 col-form-label">Enter Bank Details</label>
						<div class="form-group row">
                        
                        <div class="col-md-4">
                            <input class="form-control" type="text" name="bankdetails" value="<?= $executivedetails['bankdetails']; ?>" placeholder="Enter Bank Name" id="example-text-input">
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="text" name="accntnum" value="<?= $executivedetails['accntnum']; ?>" placeholder="Enter Account Number" id="example-text-input">
                        </div>
						<div class="col-md-4">
                            <input class="form-control" type="text" name="ifsccode" value="<?= $executivedetails['ifsccode']; ?>" placeholder="Enter IFSC Code" id="example-text-input">
                        </div>
                    </div>
					</div>

						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label" >Designation</label>
						<div class="col-3">
							<select class="form-select form-control" aria-label="Default select example" name="designation" id="example-text-input" required>
							<option value="">Select</option>
							<option value="Telecaller" <?php if($executivedetails['designation'] == 'Telecaller') { echo 'Selected' ; } ?> >Telecaller</option>
							<option value="Business Development Associate" <?php if($executivedetails['designation'] == 'Digital Marketing Executive') { echo 'Selected' ; } ?>>Business Development Associate</option>
							<option value="Digital Marketing Executive" <?php if($executivedetails['designation'] == 'Digital Marketing Executive') { echo 'Selected' ; } ?> >Digital Marketing Executive</option>
							<option value="HR Manager" <?php if($executivedetails['designation'] == 'HR Manager') { echo 'Selected' ; } ?>>HR Manager</option>
							<option value="Team Leader" <?php if($executivedetails['designation'] == 'Team Leader') { echo 'Selected' ; } ?>>Team Leader</option>
							</select>
						</div>
						</div>	
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Location</label>
						<div class="col-3">
							<input class="form-control" type="text" name="location"    value="<?= $executivedetails['location']; ?>" required placeholder="Enter location" id="example-text-input">
						</div> 
						</div>


                        <div class="form-group col-md-12"> 
						<label for="example-text-input" class="col-2 col-form-label">Address</label>
						<div class="col-3">
							<textarea class="form-control"  name="address"   placeholder="Enter Address" required id="example-text-input"><?= $executivedetails['address'] ?? ''; ?></textarea>
						</div> 
						</div>			



						
						</div>
					
					 <div class="form-group row"> 
					
					   <div class="col-3">
							 <button type="submit" name="submit" value="submit" class="btn btn-brand btn-elevate btn-pill">Update Executive</button>
						</div>
					</div>  
					</form>
					
                         
                        </div>
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-md-12 col-xs-12">
                        <div class="white-box">
                        <div class="table table-responsive">

					 
	    	</div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
			<?php include('footerscripts.php');?>

<?php include('footer.php');?>			
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->

	<!-- Modal -->
	<div class="modal fade" id="delete_ticket_model" role="dialog">
    	<div class="modal-dialog">
		<form  method="post" action="submit_edit_employee.php" enctype="multipart/form-data" id="addticket" autocomplete="off" > 

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

						<input class="form-control" name="requested_for" type="hidden" value="<?php echo $edit_id; ?>" id="requested_for" required />

						<input class="form-control" name="values" type="hidden"  id="values" required />

						
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
    <script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/custom.min.js"></script>

	<script type="text/javascript">

		

		$('#employee_edit_form').submit(function(e)
		{
			var form= $("#employee_edit_form");
			var data=form.serialize();
			console.log(data); 
			//var id=$(this).data('id');
			$('#delete_ticket_model').modal('show');
			$('#values').val(data);

			// $.ajax({
			// 	type: form.attr('method'),
			// 	url: 'submit_edit_employee.php',
			// 	data: data,
			// 	success: function (response) {
			// 	var result=response;
			// 		console.log(result);
			// 	}
			// });
		});
		

	</script>
</body>

</html>
