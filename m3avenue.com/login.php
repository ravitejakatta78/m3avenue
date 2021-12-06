<?php
session_start();
error_reporting(E_ALL);
include("admin/functions.php");
$userid = current_userid();
if(!empty($userid)){	
header("Location: loans.php");
}
$loginmessage='';
if(!empty($_POST['adminlogin'])){
	if(!empty($_POST['username'])){	
	if(!empty($_POST['password'])){	
	$mymailid = mysqli_real_escape_string($conn,$_POST['username']);
	$mypassword = mysqli_real_escape_string($conn,$_POST['password']);
	if (filter_var($mymailid, FILTER_VALIDATE_EMAIL)){	
	$logedusere = runQuery("SELECT * FROM user WHERE email = '$mymailid'");
	} else {	
	$logedusere = runQuery("SELECT * FROM user WHERE mobile = '$mymailid'");
	}
	if(!empty($logedusere['ID'])){	
	if(password_verify($mypassword,$logedusere['password'])){	
	$_SESSION['m3avenue_id'] = $logedusere['ID'];	
	$_SESSION['m3avenue_role'] = $logedusere['role'];	
	header("Location: client/profile.php");	
	}else{	
	$loginmessage .="Invalid password.";	
	}			
	}else{		
	$loginmessage .="Invalid email please use another email.";
	}		
	}else{		
	$loginmessage .="Password is empty.";	
	}	
	}else{		
	$loginmessage .="Email is empty.";
	}}
	?>
    <!DOCTYPE html>
    <html lang="en">
    <!--head start-->

    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <!--meta tag start-->
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="bizface">
        <meta name="author" content="ripplethemes">
        <meta name="copyright" content="ripplethemes">
        <!--title-->
        <title>Login M3</title>
        <!--title end-->
        <!-- faveicon start   -->
        <link rel="icon" href="images/favicon.png" type="image/x-icon">
        <!-- stylesheet start -->
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> </head>
    <!--head end-->

    <body>
        <!--header start-->
        <header class="main-header">
            <!-- Start Navigation -->
            <div id="masthead" class="site-header menu">
                <div class="container-fluid">
                    <div class="site-branding">
                        <!--<a href="index.html" class="navbar-brand logo">--><img class="brand-logo" src="images/m3-logo.png"> </div>
                    <!-- .site-branding -->
                    <div class="header-nav-search">
                        <div class="toggle-button"> <span></span> <span></span> <span></span> </div>
                        <div id="main-navigation">
                            <nav class="main-navigation">
                                <div class="close-icon"> <i class="fa fa-close"></i> </div>
                                <ul class="nav-menu">
                                    <li><a href="index.php">HOME</a></li>
                                    <li><a href="about-us.html">ABOUT US</a> </li>
                                    <li><a href="#">LOANS</a></li>
                                    <li class="#"><a href="index.html">INVESTMENTS</a></li>
                                    <li><a href="contact-us.html">CONTACT</a> </li>
                                    <li><a href="login.php">LOGIN/REGISTER</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Navigation -->
        </header>
        <!--header end-->
        <!--header end-->
        <div class="clearfix"></div>
        <!-- breadcrumb start -->
        <!--<section class="bizface-breadcrumb" style="background: url(images/bc/about-img.jpg) no-repeat center;">        <div class="bizface-breadcrumb-overlay"></div>        <div class="bizface-breadcrumb-title">                    </div>    </section>-->
        <!-- breadcrumb end -->
        <!--login-page-->
        <div class="page login-page">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12 col-xs-offset-0">
                        <div class="login-form">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="navbar-brand">
                                    <!--<img src="images/m3-logo.png" class="img-responsive" alt="">-->
                                    <h3 style="   padding-bottom: 20px;">Login</h3> </div>
                            </div>
                            <form method="post" action="">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <input type="Email" name="username" placeholder="Enter your email / User name"> </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <input type="password" name="password" placeholder=" Password"> </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="button-box">
                                        <!--<input checked="" data-toggle="toggle" type="checkbox" name="remember">                                <span>Remember Me </span>--><span class="pull-right"><a href="#">Forgot password ?</a></span> </div>
                                    <button type="submit" name="adminlogin" value="adminlogin" class="login-btn">Login</button>
                                </div>
                            </form>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="doyou"> <span>Don't have an account ?</span><span><a href="register.php">Create new account</a></span> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end>                     <!--Footer Bottom-->
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="text text-left">
                            <a href="#"></a>Copyright @2019 CompanyName.</div>
                    </div>
                    <div class="col-md-6 col-sm-6 ">
                        <a href="http://www.chenchalas.com/" target="_blank"> <img class="cpy-img" src="images/logo1.png"></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- scroll top --><a class="scroll-top" href="javascript:void(0)"><i class="fa fa-angle-up"></i></a>
        <!-- srolltop end -->
        <!-- js library start -->
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/owl.carousel.min.js"></script>
        <script src=js/jquery.mixitup.min.js></script>
        <script src="js/jquery.magnific-popup.min.js"></script>
        <script src="js/waypoints.min.js"></script>
        <script src="js/jquery.counterup.min.js"></script>
        <script src=js/wow.js></script>
        <script src="js/script.js"></script>
        <!-- js library end -->
    </body>

    </html>