<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');
$userid = current_managerid(); 
if(!empty($userid)){
	header("Location: index.php");
}
$loginmessage=$message = '';
if(!empty($_POST['adminlogin'])){

	if(!empty($_POST['username'])){
 
			$mymailid = mysqli_real_escape_string($conn,$_POST['username']);
if (filter_var($mymailid, FILTER_VALIDATE_EMAIL)) {
			$userdetils = runQuery("SELECT * FROM manager WHERE email = '$mymailid'");

				if(!empty($userdetils['ID'])){ 
					$headers = 'MIME-Version: 1.0' . "\r\n";
								$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
								$headers .= 'From:  info <'.MAILID.'>' . " \r\n" .
											'Reply-To:  '.MAILID.' '."\r\n" .
											'X-Mailer: PHP/' . phpversion();
				 $emailmessage = "Hello ".$userdetils['fname'].",
			  <br /><br />
			 Forgot password<br/>
			  Just click following link to change your password<br/>
			  <br /><br />
			  <a href='".SITE_URL."client/forgot-password.php?type=".encrypt('forgotpass')."&id=".encrypt($userdetils['ID'])."'>Click HERE to change password :)</a>
			  <br /><br />
			  Thanks";
			  $to = $userdetils['email'];
			/*  var_dump(mail($userdetils['email'],'Forgot password mail',$emailmessage,$headers));exit(); */
			if(mail($userdetils['email'],'Forgot password mail',$emailmessage,$headers)){
				if (!filter_var($to, FILTER_VALIDATE_EMAIL) === false) {
				$loginmessage .= " Hi! ".$userdetils['fname'].",  we have sent you a link to change your password on this - ".$userdetils['email']." ";
				
				} else {
				$userdetils['email'] = preg_replace('/(?<=.).(?=.*@)/', '*', $userdetils['email']);
				$loginmessage .= " Hi! ".$userdetils['fname'].",  we have sent you a link to change your password on this -".$userdetils['email']." ";
				}
			}else{
				$loginmessage .= "Mail failed try again.";
			}
					 
				}else{

				$loginmessage .="Invalid email please use another email.";

			}
			
			} else {
			$userdetils = runQuery("SELECT * FROM manager WHERE mobile = '$mymailid'");
			
				if(!empty($userdetils['ID'])){
					if($userdetils['del']==''){
						$otp = rand(1000,9999);
						$messsage = $otp." is the OTP for m3avenue forgot password.";
						send_sms($userdetils['mobile'],$messsage);
						$_SESSION['m3avenue_forgototp'] = $otp;
						$_SESSION['m3avenue_forgotuser'] = $userdetils['ID'];
						header("Location: forgot-password.php?otpsuccess=otpsuccess");
						}else{

						$loginmessage .="Your account has been deleted";

					}
				}else{

				$loginmessage .="Invalid email please use another email.";

			}
			
			}

	}else{

		$loginmessage .="Email is empty.";

	}

}
	 
if(!empty($_POST['password-submit'])){
					if(!empty($_POST['new_pass'])){
						if(!empty($_POST['confirm_pass'])){
							if($_POST['new_pass']===$_POST['confirm_pass']){
								$newepass = mysqli_real_escape_string($conn,$_POST['new_pass']);
								$confirmepass = mysqli_real_escape_string($conn,$_POST['confirm_pass']);
								$id = $_POST['id'];
									$newpass = password_hash($newepass,PASSWORD_DEFAULT);
									$insert_sql = "update manager set password = '".$newpass."' where ID = $id";
									if($conn->query($insert_sql)===TRUE){
											$_SESSION['m3avenue_forgototp'] = '';
						$_SESSION['m3avenue_forgotuser'] = '';
										header("location: index.php?success=Password Changed successfully");
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
if(!empty($_POST['otpsubmit'])){
					if(!empty($_POST['userotp'])){ 
					$sessionotp = $_SESSION['m3avenue_forgototp'];
					$forgotuser = $_SESSION['m3avenue_forgotuser'];
					$userotp = $_POST['userotp'];
							if($userotp==$sessionotp){ 
							$decrypttype = encrypt('mobile');
											header("Location: forgot-password.php?type=".$decrypttype);
										 
									}else{
									$loginmessage .="Invalid OTP";
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
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header"> <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="fa fa-bars"></i></a>
                <div class="top-left-part"><a class="logo" href="index.php"><b>
					<img class="logo-new" src="img/m3-logo.png" alt="home" /></b><span class="hidden-xs">M3<span></a></div>
               
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <li>
                        <a class="profile-pic" href="#"> <img src="img/m3-logo.png" alt="user-img" width="36" class="img-circle"><b class="hidden-xs">Venu</b> </a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-header -->
            <!-- /.navbar-top-links -->
            <!-- /.navbar-static-side -->
        </nav>
        <!-- Left navbar-header -->
       
        <!-- Left navbar-header end -->
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    
                    <!-- /.col-lg-12 -->
                </div>
                <!-- row -->
                    <!--col -->
					
					<div class="col-lg-2 col-md-4 col-sm-12 col-xs-12">
					
					</div>
					
                    <div class="col-lg-6 col-md-4 col-sm-12 col-xs-12">
                        
						<div class="white-box">
                          	<?php if(!empty($loginmessage)){?>
							<div class="alert alert-danger"><?php echo $loginmessage;?></div>
						<?php }?>
						<? if(!empty($_GET['type'])){?>
						<form name="login-form" method="post" action="" class="clearfix">
							<div class="form-group">
								<input type="password" class="form-control" name="new_pass" placeholder="New password">
							</div>
							
							<div class="form-group">
								<input type="password" class="form-control" name="confirm_pass" placeholder="Confirm password">
							</div>
							<?php $type= trim(decrypt($_GET['type']));
							if($type=='mobile'){
								$id = $_SESSION['m3avenue_forgotuser'];
							}else{
								$id = trim(decrypt($_GET['id']));
							}
					           ?>
						<input type="hidden" value="<?=$id;?>" name="id" />
							<button type="submit" name="password-submit" value="password-submit" class="btn btn-common log-btn" >Submit</button>
							</form><!-- form -->
							
					<? }else{?>
						<?php if(!empty($_GET['otpsuccess'])){?>
							<form name="login-form" method="post" action="" class="clearfix">
							<div class="form-group">
								<input type="text" class="form-control" name="userotp" placeholder="Please enter the OTP">
							</div>
							<button type="submit" name="otpsubmit" value="adminlogin" class="btn btn-common log-btn">Submit</button>
							</form><!-- form -->
					
						<? }else{?>
						<form name="login-form" method="post" action="" class="clearfix">
							<div class="form-group">
								<input type="text" class="form-control" name="username" placeholder="Email / Mobile number">
							</div>
							<button type="submit" name="adminlogin" value="adminlogin" class="btn btn-common log-btn" >Submit</button>
							</form><!-- form -->
					
						<? }?>
					<? }?>
                        </div>
						
                    <!-- /.col -->
                   
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
    <script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!--Counter js -->
    <script src="../plugins/bower_components/waypoints/lib/jquery.waypoints.js"></script>
    <script src="../plugins/bower_components/counterup/jquery.counterup.min.js"></script>
    <!--Morris JavaScript -->
    <script src="../plugins/bower_components/raphael/raphael-min.js"></script>
    <script src="../plugins/bower_components/morrisjs/morris.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/custom.min.js"></script>
    <script src="js/dashboard1.js"></script>
    <script src="../plugins/bower_components/toast-master/js/jquery.toast.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $.toast({
            heading: 'Welcome to Pixel admin',
            text: 'Use the predefined ones, or specify a custom position object.',
            position: 'top-right',
            loaderBg: '#ff6849',
            icon: 'info',
            hideAfter: 3500,
            stack: 6
        })
    });
    </script>
</body>

</html>
