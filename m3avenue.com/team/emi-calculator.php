<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');
$userid = current_employeeid();
if(empty($userid)){
	header("Location:index.php");
	}

$message = '';
$usedetails = runQuery("select * from employee where ID = '".$userid."'");
if(!empty($_POST['submit'])){
          if(!empty($_POST['fname'])){
			if(!empty($_POST['lname'])){
				if(!empty($_POST['mobile'])){
					if(!empty($_POST['pannum'])){
						if(!empty($_POST['marital_status'])){
					         	if(!empty($_POST['gender'])){
							        if(!empty($_POST['occuption'])){
							        if(!empty($_POST['income'])){
							        if(!empty($_POST['emergency_contact'])){
							 

			$m3avenuearray = $m3avenuewherearray = array(); 
$m3avenuewherearray['ID']=$userid;
$m3avenuearray['fname']=mysqli_real_escape_string($conn,$_POST['fname']);
$m3avenuearray['lname']=mysqli_real_escape_string($conn,$_POST['lname']);
$m3avenuearray['dob']=mysqli_real_escape_string($conn,$_POST['dob']);
$m3avenuearray['mobile']=mysqli_real_escape_string($conn,$_POST['mobile']);
$m3avenuearray['pannum']=mysqli_real_escape_string($conn,$_POST['pannum']);
$m3avenuearray['marital_status']=mysqli_real_escape_string($conn,$_POST['marital_status']);
$m3avenuearray['gender']=mysqli_real_escape_string($conn,$_POST['gender']);
$m3avenuearray['occuption']=mysqli_real_escape_string($conn,$_POST['occuption']);
$m3avenuearray['income']=mysqli_real_escape_string($conn,$_POST['income']);
$m3avenuearray['emergency_contact']=mysqli_real_escape_string($conn,$_POST['emergency_contact']);

	if(!empty($_FILES["profilepic"]['name'])){
  if (!file_exists('employeeimages')) {	
		mkdir('employeeimages', 0777, true);	
		}
	    $target_dir = 'employeeimages/';									

		$file = $_FILES["profilepic"]['name'];				
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
			if (move_uploaded_file($_FILES["profilepic"]["tmp_name"], $target_file)){				
				$m3avenuearray['profilepic'] = strtolower($file);						
			} else {
				$message .= "Sorry, There Was an Error Uploading Your File.";			
				}
			}
	}
			$result= updateQuery($m3avenuearray,'employee',$m3avenuewherearray);
			
 
if(!$result){

 
header("Location:profile.php?success=success");
}else{
		$message .=$result;
}

		}else{
		$message .="Emergency Contact field is empty";
	}
	}else{
		$message .="Income field is empty";
	}
	}else{
		$message .="Occuption field is empty";
	}
	}else{
		$message .="Gender field is empty";
	}	
	}else{
		$message .="marital status field is empty";
	}
	}else{
		$message .="Pancard Number  field is empty";
	}
	
	}else{
		$message .="Mobile  field is empty";
	}
	
	}else{
		$message .="Last Name field is empty";
	}
	
	}else{
		$message .="First Name field is empty";
	}
	
}

if(!empty($_POST['password-submit'])){
	if(!empty($_POST['new_pass'])){
		if(!empty($_POST['confirm_pass'])){
			if($_POST['new_pass']===$_POST['confirm_pass']){
				$newepass = mysqli_real_escape_string($conn,$_POST['new_pass']);
				$confirmepass = mysqli_real_escape_string($conn,$_POST['confirm_pass']); 
					$newpass = password_hash($newepass,PASSWORD_DEFAULT);
					$insert_sql = "update employee set password = '".$newpass."' where ID = $userid";
					if($conn->query($insert_sql)===TRUE){ 
						header("location: profile.php?password=Password Changed successfully");
						}else{
							$loginmessage .=$conn->error;
						}
					}else{
					$loginmessage .="Password mistach";
					}
				}else{
				$loginmessage .="please enter the confirm password";	
				}
			}else{	
			$loginmessage .="please enter the new password";	
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
    <title>M3 Dashboard EMI Calculator</title>
   
       <?php include('headerscripts.php');?>
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
                        <h4 class="page-title">EMI Calculator</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li class="active">EMI Calculator</li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
                <!-- .row -->
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <div class="white-box">
                            <div class="user-btm-box">
						 <div id="contact_results"></div>
                           <form  method="post" action="emi-form.php" id="form1" enctype="multipart/form-data">
							 
									<div class="col-md-12">
								  <div class="form-group">
										<label>Principal  amount</label>
										<input type="text" name="pamount"  class="form-control form-control-line"> 
										</div> 
								  <div class="form-group">
									<label >Rate Of Interest per month</label> 
										<input type="text" name="interestm" class="form-control form-control-line"> 
									</div>
								  <div class="form-group">
									<label >No of Months</label> 
										<input type="text" name="noofmonths" class="form-control form-control-line"> 
									</div>
								  <div class="form-group"> 
										<button  type="submit" class="btn btn-success">Submit</button>
										<a  href="javascript:void(0);" class="btn btn-danger resetbutton">Reset</a>
									</div>
									</div>
									</form>
							
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
   
   <?php include("footerscripts.php");?>
   
   
<script>

$(".resetbutton").click(function(){
	$("#contact_results").html("");
						 $( "#form1" )[0].reset();
});
$("#form1").submit(function(event){
		event.preventDefault();
		proceed = true;

		if(proceed){ 
			var post_url = $(this).attr("action"); 
			var request_method = $(this).attr("method"); 
			var form_data = new FormData(this);
			
			$.ajax({
				url : post_url,
				type: request_method,
				data : form_data,
				dataType : "json",
				contentType: false,
				cache: false,
				processData:false,
				beforeSend:     
				function() {   
						$("#contact_results").show();
						$("#contact_results").html('<div class="alert alert-danger">Calculator Please wait..</div>');
				},
				success: function(res){
			   if(res.type == "error"){
						$("#contact_results").show();
						$("#contact_results").html('<div class="alert alert-danger">'+res.text+'</div>');
					}
					if(res.type == "done"){
						$("#contact_results").hide();
						$("#contact_results").show();
						$("#contact_results").html('<div class="alert alert-success">'+res.text+'</div>'); 
						 
					}
				}
				});
		}
	});
</script>
</body>

</html>
