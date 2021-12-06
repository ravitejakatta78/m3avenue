<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');
$userid = current_managerid();
if(!empty($userid)){
	header("Location: profile.php");
	}
	 
	$message = '';
	if($_SERVER["REQUEST_METHOD"] == "POST") {	
	if(isset($_POST['adminlogin'])){	
	if(!empty($_POST['username'])){	
	if(!empty($_POST['password'])){		
	$mymailid = mysqli_real_escape_string($conn,$_POST['username']);	
	$mypassword = mysqli_real_escape_string($conn,$_POST['password']); 	
	if (filter_var($mymailid, FILTER_VALIDATE_EMAIL)) {
	$row = runQuery("SELECT * FROM employee WHERE email = '$mymailid' and role_id = '3'"); 
	} else {
		$row = runQuery("SELECT * FROM employee WHERE mobile = '$mymailid' and role_id = '3'"); 
	}

	if(!empty($row['ID'])){	
	if(password_verify($mypassword,$row['password'])){		
	$_SESSION['m3avenue_managerid'] = $row['ID'];		 		
	header("location: profile.php");
	} else {			
	$message .= "Your Login password is invalid";	
	}		
	}  else {	
	$message .= "Your Login email invalid";	
	}		
	}else{		
	$message .= "Enter your password";		
	}	
	}else{			
	$message .= "Enter your email";		
	}	   }	   }
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
					<img class="logo-new" src="img/m3-logo.png" alt="home" /></b>M3 Avenue</a></div>
               
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <li>
                        <a class="profile-pic" href="#"> <img src="img/m3-logo.png" alt="user-img" width="36" class="img-circle"><b class="hidden-xs">M3 Avenue</b> </a>
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
                        		<?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								 Succussfull
								</div>
								<?php } ?>
								<?php if(!empty($message)){?>
								<div class="alert alert-danger">
								<?=$message?>
								</div>
								<?php } ?>
                            <form class="form-horizontal form-material" method="post" action="">
                                <div class="form-group">
                                    <label class="col-md-12">Enter Email id/ Mobile number</label>
                                    <div class="col-md-8">
                                        <input type="text" placeholder="Email id" name="username" class="form-control form-control-line" required> </div>
                                </div>
                                <div class="form-group">
                                    <label for="example-email" class="col-md-12">Password</label>
                                    <div class="col-md-8">
                                        <input type="password" placeholder="Password" class="form-control form-control-line" name="password" id="example-email" required> </div>
                                </div>
								
								
								
                                <div class="form-group">
                                    <div class="col-sm-6 col-xs-6">
                                        <button class="btn btn-success" name="adminlogin" value="adminlogin">Login</button>
                                    </div>
									<p><a href="forgot-password.php">Forgot Password?</a></p>
									
                                </div>
                            </form>
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
