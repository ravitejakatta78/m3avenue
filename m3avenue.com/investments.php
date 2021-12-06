<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors","on");
include("functions.php");
$message = '';
if(!empty($_POST['submit'])){
	if(!empty($_POST['name'])){
		if(!empty($_POST['phone'])){
			if(!empty($_POST['email'])){	
			if(!empty($_POST['location'])){	
			if(!empty($_POST['investment'])){		
			if(!empty($_POST['interestedin'])){	
			$pagearray = array();
			$pagearray['name']=mysqli_real_escape_string($conn,$_POST['name']);
			$pagearray['phone']=mysqli_real_escape_string($conn,$_POST['phone']);
			$pagearray['email']=mysqli_real_escape_string($conn,$_POST['email']);		
			$pagearray['location']=mysqli_real_escape_string($conn,$_POST['location']);		
			$pagearray['investment']=mysqli_real_escape_string($conn,$_POST['investment']);
			$pagearray['interestedin']=mysqli_real_escape_string($conn,$_POST['interestedin']);
			$result= insertQuery($pagearray,'investment');
			if(!$result){header("Location: investments.php?success=success");
			}		
			
			}else{	
			$message .="Interested In  field is empty";	
			}	
			}else{	
			$message .="Investment  field is empty";	
			}	
			}else{	
			$message .="Location  field is empty";	}
			}else{	
			$message .="Email  field is empty";	}	
			}else{	
			$message .="Phone Number  field is empty";	
			}	
	}else{	
			$message .="Name field is empty";
			
}
	}if(!empty($_GET['delete'])){
	$sql = "DELETE FROM investment WHERE id=".$_GET['delete']."";
	if ($conn->query($sql) === TRUE) { 
	header("Location: investments.php?success=success");  
	} else { 
	echo "Error deleting record: " . $conn->error;
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
      <title>M3 Avenue</title>
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
                           <li  ><a href="index.php">HOME</a></li>
                           <li><a href="about-us.html">ABOUT US</a> </li>
                           <li><a href="loans.php">LOANS</a></li>
                           <li class="active"><a href="investments.php">INVESTMENTS</a></li>
                           <li><a href="contact-us.html">SUPPORT</a> </li>
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
      <!-- marcquee start-->
      
      <!-- marcquee End-->
      
      
      <!-- about end -->
      <!--uder corousel-->

    <!-- service start -->
    
    		<div class="container-fluid">
            	<div class="row">
                
                	<div class="col-md-6 md6">
                    	<h2 class="head2">You work hard to earn money</h2>
							<h3 class="head2">We make your money earn more money to you</h3>
                   			 <img  src="images/Business-Wallpapers-HD-For-Desktop.jpg"class="opl">
                    			
                               <h2 class="head2" >WHY INVESTMENT</h2>
                       
                        <p class="para1">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p> 
                        
                         <h2 class="head2" >WHY WITH US</h2>
                       
                        <p class="para1">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p> 
                               
                         </div>
                    <div class="col-md-6 contact-right-form">
                        <div class="contact-page-title">
                            <h4 class="head21">ENQUIRY FORM</h4
                        ></div>
                     						   <?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Investment details Added Succussfully
								</div>
								<?php } ?>
								<?php if(!empty($message)){?>
								<div class="alert alert-danger">
								<?=$message?>
								</div>
								<?php } ?>
                                           <form class="width80" method="post" action="" >
                        <div class="row">
                                <div class="col-md-10 ">
                                    <div class="form-group">
                                        <label>Name :</label>
                                        <input type="text" class="form-control" name="name" placeholder=" Enter Your Name">
                                    </div>
                                </div>
                                <div class="col-md-10 ">
                                    <div class="form-group">
                                        <label>Mobile Number :</label>
                                        <input type="text" class="form-control" name="phone" placeholder="Enter Phone Number ">
                                    </div>
                                </div>
                                
                                </div>
                                <div class="row">
                                <div class="col-md-10 ">
                                    <div class="form-group">
                                        <label>Email :</label>
                                        <input type="email" class="form-control" name="email" placeholder=" Enter Your Mail">
                                    </div>
                                </div>
                                <div class="col-md-10 ">
                                    <div class="form-group">
                                        <label>Location</label>
                                        <input type="text" class="form-control" name="location" placeholder="Enter your location ">
                                    </div>
                                </div>
                                
                                </div>
                                <div class="row">
                            
                            	
                                <div class="col-md-10 ">
                                    <div class="form-group">
                                       
                                        <label>How Much Do You Want To Invest :</label>
                                        <input type="text" class="form-control" name="investment" placeholder="Enter How Much you Want To Invest ">
                                    </div>
                                </div>
                                <div class="col-md-10 ">
                                    <div class="form-group">
                                        <div class="form-group">
                                          <label for="invest">Intersted In :</label>
                                          <select class="form-control" name="interestedin" id="invest">
                                            <option value="INSURANCE">INSURANCE     </option>
                                            <option value="STRATAGICAL TRADING ">STRATAGICAL TRADING </option>
                                            <option value="MUTUAL FUNDS">MUTUAL FUNDS </option>
                                            <option value="THEMATTIC INVESTMENT">THEMATTIC INVESTMENT</option>
                                           <!-- <option>SELECT	 ALL</option>-->
                                          </select>
										</div>
                                    </div>
                                </div>
                            	
                            </div>
                            <div class="row">
                                        	<div class="col-md-4">
                                            					
		<div class="form-group">
                                         <button type="submit" name="submit" value="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                            </div>
                                        </div>
                        </form>
                    	
                    </div>
                </div>
            </div>
            <div class="container-fluid">
            <div class="md">
            	<div class="row">
                		
                		<h2 class="head2">Investment Plans</h2>
                	<div class="col-md-4">
                    		<div class="cont1">
                            		<h4 class="head2">Plan A</h4>
                                    <p class="para1">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum</p>
                                   <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#myModal">
        <i class="fa fa-money" aria-hidden="true" ></i> Invest Now
  </button>

  <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="margin-top:100px;">
      
        <!-- Modal Header -->
        <div class="modal-header">
         		 <h5 class="head2" style="text-align:center;">Enter Contact Details</h5>
				 <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
         <form action="/action_page.php">
         <div class="form-group">
    		<label for="pwd">Name:</label>
   			 <input type="text" class="form-control" id="txt" placeholder="Enter Name">
 		</div>
 		<div class="form-group">
    		<label for="pwd">Mobile:</label>
    		<input type="text" class="form-control" id="num" placeholder="Enter Mobilenumber">
 		</div>
 		<div class="form-group">
    		<label for="email">Email address:</label>
    		<input type="email" class="form-control" id="email" placeholder="Enter Mail">
  		</div>
	  <div class="form-group">
    		<label for="text">Address:</label>
    		<input type="text" class="form-control" id="text" placeholder="Enter Address">
  		</div>
	</form>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
            <div class="col-md-5 col-sm-12">
            	<p style="color:#06F;">Our Person Get Back Soon</p>
            </div>
            
        	<div class="col-md-7 col-sm-12">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Submit</button>
        	</div>
          
        </div>
        
      </div>
    </div>
  </div>
                            </div>
                    </div>
                    <div class="col-md-4">
                    	<div class="cont1">
                            		<h4 class="head2">Plan b</h4>
                                    <p class="para1">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum</p>
                                   <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#myModal">
        <i class="fa fa-money" aria-hidden="true" ></i> Invest Now
  </button>

  <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="margin-top:100px;">
      
        <!-- Modal Header -->
        <div class="modal-header">
         		 <h6 class="head2" style="text-align:center;">Enter Contact Details</h6>
				 <p>Our Person Get Back Soon</p>
         
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
         <form action="/action_page.php">
         <div class="form-group">
    <label for="pwd">Name:</label>
    <input type="text" class="form-control" id="txt" placeholder="Enter Name">
  </div>
  
  <div class="form-group">
    <label for="pwd">Mobile:</label>
    <input type="text" class="form-control" id="num" placeholder="Enter Mobilenumber">
  </div>
  <div class="form-group">
  
    <label for="email">Email address:</label>
    <input type="email" class="form-control" id="email" placeholder="Enter Mail">
  </div>
  <div class="form-group">
  
    <label for="text">Address:</label>
    <input type="text" class="form-control" id="text" placeholder="Enter Address">
  </div>
  
 
</form>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
		<button type="button" class="btn btn-primary" data-dismiss="modal">Submit</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
                            </div>
                    </div>
                    <div class="col-md-4">
                    	<div class="cont1">
                            		<h4 class="head2">Plan c</h4>
                                    <p class="para1">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum</p>
                                   <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#myModal">
        <i class="fa fa-money" aria-hidden="true" ></i> Invest Now
  </button>

  <!-- The Modal -->
  <div class="modal fade" id="myModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="margin-top:100px;">
      
        <!-- Modal Header -->
        <div class="modal-header">
         		 <h5 class="head2" style="text-align:center;">Enter Contact Details</h5>
				 <p>Our Person Get Back Soon</p>
         
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
         <form action="/action_page.php">
         <div class="form-group">
    <label for="pwd">Name:</label>
    <input type="text" class="form-control" id="txt" placeholder="Enter Name">
  </div>
  <div class="form-group">
    <label for="pwd">Mobile:</label>
    <input type="text" class="form-control" id="num" placeholder="Enter Mobilenumber">
  </div>
  <div class="form-group">
  
    <label for="email">Email address:</label>
    <input type="email" class="form-control" id="email" placeholder="Enter Mail">
  </div>
  <div class="form-group">
  
    <label for="text">Address:</label>
    <input type="text" class="form-control" id="text" placeholder="Enter Address">
  </div>
  
 
</form>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
		<button type="button" class="btn btn-primary" data-dismiss="modal">Submit</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
                            </div>
                    </div>
                    
                </div>
            </div>
            </div>
          <!-- end-->
      
      
      <!-- end of the section-->
      
       <!--bizface-counter-->
    
    <!-- counter end -->
      
      
      <!--section starts here--->
      
      <!--end of the section-->
      <!-- testominal section-->
      
      <!-- end-->
      
      <!--section start-->
     
      
      <!-- end-->
      
      <!-- happyclient start -->
    
    <!-- happyclient end -->
      
      
      
      <!-- top footer-->
      <!--<div class="container-fluid top-footer">
         <div class="row">
            <div class="col-md-3 col-sm-6">
               <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
            </div>
            <div 
               class="col-md-3 col-sm-6">
               <h4 class="ft">ABOUT</h4>
               <ul class="foot-list">
                  <li><a href="#">Leorem Ipsum</a></li>
                  <li><a href="#">Leorem Ipsum</a></li>
                  <li><a href="#">Leorem Ipsum</a></li>
                  <li><a href="#">Leorem Ipsum</a></li>
               </ul>
            </div>
            <div class="col-md-3 col-sm-6">
               <h4 class="ft">POLICY SERVICE</h4>
               <ul class="foot-list">
                  <li><a href="#">Leorem Ipsum</a></li>
                  <li><a href="#">Leorem Ipsum</a></li>
                  <li><a href="#">Leorem Ipsum</a></li>
                  <li><a href="#">Leorem Ipsum</a></li>
               </ul>
            </div>
            <div class="col-md-3 col-sm-6">
               <h4 class="ft">LOCATION</h4>
               <ul class="foot-list">
                  <li><a href="#">Leorem Ipsum</a></li>
                  <li><a href="#">Leorem Ipsum</a></li>
                  <li><a href="#">Leorem Ipsum</a></li>
                  <li><a href="#">Leorem Ipsum</a></li>
               </ul>
            </div>
         </div>
      </div>
-->      <!-- end>
      
      
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
      <!-- js library end -->
   </body>
</html>