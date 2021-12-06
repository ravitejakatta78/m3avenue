<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors","on");

include("admin/functions.php");

$userid = current_userid();
$userrole = user_role();
if(!empty($userid)){
	header("Location: index.php");
}

 $message = '';
if(!empty($_POST['user-submit'])){
         if(!empty($_POST['user-fname'])){
         if(!empty($_POST['user-lname'])){
				if(!empty($_POST['user-email'])){
					if(!empty($_POST['user-password'])){
					if(!empty($_POST['user-confirmpassword'])){
						if(!empty($_POST['user-mobile'])){
						if($_POST['user-password']===$_POST['user-confirmpassword']){
					         	
			$signuparray = array();
$signuparray['fname']=mysqli_real_escape_string($conn,$_POST['user-fname']);
$signuparray['lname']=mysqli_real_escape_string($conn,$_POST['user-lname']);
$signuparray['email']=mysqli_real_escape_string($conn,$_POST['user-email']);
$signuparray['password']=password_hash(mysqli_real_escape_string($conn,$_POST['user-password']),PASSWORD_DEFAULT);
$signuparray['mobile']=mysqli_real_escape_string($conn,$_POST['user-mobile']);

$prevuser = runQuery("select * from user where email = '".$signuparray['email']."'");
if(empty($prevuser['ID'])){
$prevuser = runQuery("select * from user where mobile = '".$signuparray['mobile']."'");
if(empty($prevuser['ID'])){
	$result= insertQuery($signuparray,'user');
	
	if(!$result){	
	header("Location: register.php?usersuccess=success");
	}
}else{
		$message .="Mobile number already exists";
	}
}else{
		$message .="Email id already exists";
	}
    }else{
		$message .="Password mismatch";
	}
	}else{
		$message .="Mobile  field is empty";
	}
	}else{
		$message .="Confirm Password  field is empty";
	}
	}else{
		$message .="Password  field is empty";
	}
	}else{
		$message .="Email-Id  field is empty";
	}
	}else{
		$message .="Last Name field is empty";
	}
	}else{
		$message .="First Name field is empty";
	}
	
}

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
      <title>Regestration M3</title>
      <!--title end-->
      <!-- faveicon start   -->
      <link rel="icon" href="images/favicon.png" type="image/x-icon">
      <!-- stylesheet start -->
      <link rel="stylesheet" type="text/css" href="css/style.css">
      <link rel="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
      
   </head>
   <!--head end-->
   <body>
      <!--header start-->
      <header class="main-header">
         <!-- Start Navigation -->
         <div id="masthead" class="site-header menu">
            <div class="container-fluid">
               <div class="site-branding"><!--<a href="index.html" class="navbar-brand logo">-->
               			<img class="brand-logo" src="images/m3-logo.png">
               </div>
               <!-- .site-branding -->
               <div class="header-nav-search">
                  <div class="toggle-button">
                     <span></span>
                     <span></span>
                     <span></span>
                  </div>
                  <div id="main-navigation">
                     <nav class="main-navigation">
                        <div class="close-icon">
                           <i class="fa fa-close"></i>
                        </div>
                        <ul class="nav-menu">
                           <li><a href="index.php">HOME</a></li>
                           <li><a href="about-us.html">ABOUT US</a> </li>
                           <li><a href="loans.php">LOANS</a></li>
                           <li class="#"><a href="index.html">INVESTMENTS</a></li>
                           <li><a href="user-us.html">SUPPORT</a> </li>
                           <li class="active"><a href="login.html">LOGIN/REGISTER</a></li>
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
    <!--<section class="bizface-breadcrumb" style="background: url(images/bc/about-img.jpg) no-repeat center;">
        <div class="bizface-breadcrumb-overlay"></div>
        <div class="bizface-breadcrumb-title">
            
        </div>
    </section>-->
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
                                <h3 style="   padding-bottom: 20px;">Register Now</h3>
                            </div>
                        </div>
                          <div class="">
                   <?php if(!empty($_GET['usersuccess'])){
$file = SITE_URL.('/login.php');
			?>
			<div class="alert alert-success">
			Account Created Successfully
		<a href="<?php echo $file;?>"  >Click here</a> to Login.
			</div>
			<?php }?>
	<?php if(!empty($message)){?>
			<div class="alert alert-danger">
			<?php echo $message;?>
			</div>
			<?php }?>
			<div id="user_results" class="message"></div>
             <form class="tm-contact-form" id="user-form" method="post" action="user-form.php">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <input type="text" placeholder="Your first name" name="user-fname">
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <input type="text" placeholder="Your last name" name="user-lname">
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <input type="Email" placeholder="Enter your email" name="user-email">
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <input type="number" placeholder="Enter your Number" name="user-mobile">
                            </div>
                        </div>

                        
                        
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <input type="password" placeholder=" Password" name="user-password">
                            </div>
                        </div>
                        
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <input type="password" placeholder="Re-Password" name="user-confirmpassword">
                            </div>
                        </div>
                        
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="button-box">
                                
                            </div>
                            <button type="submit" name="user-submit" value="submit" class="login-btn">Register Now</button>
                        </div>
						</form>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="doyou">
                                <span>Already have an account ?</span><span><a href="login.php">Login</a></span>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
         <!-- end>
      
      
         <!--Footer Bottom-->
      <div class="footer-bottom">
         <div class="container">
            <div class="row">
               <div class="col-md-6 col-sm-6">
                  <div class="text text-left"> <a href="#"></a>Copyright @2019 CompanyName.</div>
               </div>
               <div class="col-md-6 col-sm-6 ">
                  <a href="http://www.chenchalas.com/" target="_blank"> <img class="cpy-img" src="images/logo1.png"></a>
               </div>
            </div>
         </div>
      </div>
      <!-- scroll top -->
      <a class="scroll-top" href="javascript:void(0)"><i class="fa fa-angle-up"></i></a>
      <!-- srolltop end -->
      <!-- js library start -->
      <script  src="js/jquery-3.2.1.min.js"></script>
      <script  src="js/bootstrap.min.js"></script>
      <script  src="js/owl.carousel.min.js"></script>
      <script  src=js/jquery.mixitup.min.js></script>
      <script  src="js/jquery.magnific-popup.min.js"></script>  
      <script  src="js/waypoints.min.js"></script>
      <script  src="js/jquery.counterup.min.js"></script>
      <script  src=js/wow.js></script>
      <script  src="js/script.js"></script>
	  
	  
	  <script type="text/javascript">
$("#user-form").submit(function(event){  
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
  $("#user_results").show();	
  $("#user_results").html('Sending mail Please wait..'); 
  },	
  success: function(res){ 
  if(res.type == "error"){	
  $("#user_results").show();
  $("#user_results").html(res.text);		
  }			
  if(res.type == "done"){		
  $("#user_results").hide();	
  $("#user_results").show();		
  $("#user_results").html(res.text);	
  $( "#user-form" )[0].reset();	
  }       
  }		
  });
  }
  });	
  </script>
      <!-- js library end -->
   </body>
</html>