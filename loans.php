<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors","on");
include("functions.php");
$message = '';
if(!empty($_POST['submit'])){
	if(!empty($_POST['name'])){
		if(!empty($_POST['dob'])){	
		if(!empty($_POST['phone'])){
			if(!empty($_POST['email'])){	
			if(!empty($_POST['address'])){	
			if(!empty($_POST['district'])){		
			if(!empty($_POST['state'])){	
			if(!empty($_POST['comments'])){	
			$pagearray = array();
			$pagearray['name']=mysqli_real_escape_string($conn,$_POST['name']);
			$pagearray['dob']=mysqli_real_escape_string($conn,$_POST['dob']);
			$pagearray['phone']=mysqli_real_escape_string($conn,$_POST['phone']);
			$pagearray['email']=mysqli_real_escape_string($conn,$_POST['email']);
			$pagearray['address']=mysqli_real_escape_string($conn,$_POST['address']);
			$pagearray['district']=mysqli_real_escape_string($conn,$_POST['district']);
			$pagearray['state']=mysqli_real_escape_string($conn,$_POST['state']);
			$pagearray['yearlyincome']=mysqli_real_escape_string($conn,$_POST['yearlyincome']);
			$pagearray['sourceincome']=mysqli_real_escape_string($conn,$_POST['sourceincome']);
			$pagearray['loantype']=mysqli_real_escape_string($conn,$_POST['loantype']);
			$pagearray['existingloan']=mysqli_real_escape_string($conn,$_POST['existingloan']);
			$pagearray['bankdetails']=mysqli_real_escape_string($conn,$_POST['bankdetails']);
			$pagearray['branch']=mysqli_real_escape_string($conn,$_POST['branch']);
			$pagearray['ifsccode']=mysqli_real_escape_string($conn,$_POST['ifsccode']);
			$pagearray['customerofbranch']=mysqli_real_escape_string($conn,$_POST['customerofbranch']);
			$pagearray['investment']=mysqli_real_escape_string($conn,$_POST['investment']);
			$pagearray['interestedin']=mysqli_real_escape_string($conn,$_POST['interestedin']);
			$pagearray['comments']=mysqli_real_escape_string($conn,$_POST['comments']);	
			$result= insertQuery($pagearray,'loans');
			if(!$result){header("Location: loans.php?success=success");
			}		
			}else{		
			$message .="comments field is empty";	
			}	
			}else{	
			$message .="country  field is empty";	
			}	
			}else{	
			$message .="mobile  field is empty";	
			}	
			}else{	
			$message .="email  field is empty";	}
			}else{	
			$message .="Email  field is empty";	}	
			}else{	
			$message .="Phone Number  field is empty";	
			}	
			}else{	
			$message .="DOB  field is empty";	
			}	}else{	
			$message .="Name field is empty";
			
}	}if(!empty($_GET['delete'])){
	$sql = "DELETE FROM loans WHERE id=".$_GET['delete']."";
	if ($conn->query($sql) === TRUE) { 
	header("Location: loans.php?success=success");  
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
      <title>Loans M3 Avenue</title>
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
                           <li class="active"><a href="loans.php">LOANS</a></li>
                           <li class="#"><a href="investments.php">INVESTMENTS</a></li>
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
      
 <!--header end-->

    <div class="clearfix"></div>
    <!-- breadcrumb start -->
    <!--<section class="bizface-breadcrumb" style="background: url(images/bc/about-img.jpg) no-repeat center;">
        <div class="bizface-breadcrumb-overlay"></div>
        <div class="bizface-breadcrumb-title">
            
        </div>
    </section>-->
    <!-- breadcrumb end -->
			<div class="container-fluid">
            	<div class="row">
						
                	<div class="col-md-6 col-xs-12">
							<img  src="images/Business-Wallpapers-HD-For-Desktop.jpg"class="opl">
                    	<h2 class="head2" >Application Process</h2>
                       
                        <p class="para1">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
                        <h2 class="head2" >Borrower Eligibility</h2>
                        <p class="para1">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
                        <h2 class="head2" >Loan Features</h2>
                        <p class="para1">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
                        <h2 class="head2">Benefits</h2>
                        <p class="para1">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
                    </div>
                    	<div class="col-md-6 contact-right-form">
                        <div class="contact-page-title">
                     <h4 class="head21">Fill This Form</h4
                        ></div>
						
						   <?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Loan details Added Succussfully
								</div>
								<?php } ?>
								<?php if(!empty($message)){?>
								<div class="alert alert-danger">
								<?=$message?>
								</div>
								<?php } ?>
                                           <form class="width80" method="post" action="" >
                        
                            <div class="row">
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label>Name* :</label>
                                        <input type="text" class="form-control" name="name" placeholder=" Enter Your Name" required>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label>DOB :</label>
                                        <input type="date" class="form-control" name="dob" placeholder="name">
                                    </div>
                                </div>
                                
                                </div>
                                
                                <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone* :</label>
                                        <input type="number"  name="phone" class="form-control" placeholder="Enter Phone Number" required>
                                    </div>
                                </div>
                                
                                 <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label>Email* :</label>
                                        <input type="text" class="form-control" name="email" placeholder="Email" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Address* :</label>
                                        <input type="text"  class="form-control" name="address" placeholder="Address">
                                    </div>
                                </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                        <label>District* :</label>
                                        <input type="text"  class="form-control" name="district" placeholder="Enter Your District">
                                    </div>
                                </div>
                                
                                 <div class="col-md-6 ">
                                    <div class="form-group">
                                       
                                        <div class="form-group">
  <label for="state">State* :</label>
  <select class="form-control" id="state" name="state">
    <option value="AndhraPradesh"> Andhra Pradesh</option>
    <option value="Telangana">Telangana</option>
    <option value="Karantaka">Karantaka</option>
    <option value="Tamil nadu">Tamil nadu</option>
  </select>
</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            	<div class="col-md-6">
                                    <div class="form-group">
                                        <label>Yearly Income* :</label>
                                        <input type="text"  name="yearlyincome" class="form-control" placeholder="Enter Your Income">
                                    </div>
                                </div>
                                 <div class="col-md-6 ">
                                    <div class="form-group">
                                       
                                        <div class="form-group">
  <label for="Source Of Income">Source Of Income* :</label>
  <select class="form-control" id="Source Of Income" name="sourceincome">
    <option value="Business">Business</option>
    <option value="Education">Education</option>
    <option></option>
    <option></option>
  </select>
</div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="row">
                            
                            	<div class="col-md-6 ">
                                    <div class="form-group">
                                       
                                        <div class="form-group">
  <label for="loan"> Loan Type* :</label>
  <select class="form-control" id="loan" name="loantype">
    <option value="Shishu">Shishu</option>
    <option value="Education">Education</option>

  </select>
</div>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                       
                                        <div class="form-group">
  <label for="Any Existing Loan">Any Existing Loan* :</label>
  <select class="form-control" id="Any Existing Loan" name="existingloan">
    <option value="Yes">Yes</option>
    <option value="No">No</option>
    <option></option>
    <option></option>
  </select>
</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            	<div class="col-md-6 ">
                                    <div class="form-group">
                                       
                                        <div class="form-group">
  <label for="bank">Bank Details* :</label>
  <select class="form-control" id="bank" name="bankdetails">
    <option>SBI bank</option>
    <option>ICIC bank</option>

  </select>
</div>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                       
                                        <label>Branch*:</label>
                                        <input type="text" class="form-control" name="branch" placeholder="Enter Your Branch">
                                    </div>
                                </div>
                            	
                            </div>
                            <div class="row">

                                <div class="col-md-6 ">
                                    <div class="form-group">
                                       
                                        <label>IFSC Code*:</label>
                                        <input type="text"  name="ifsccode" class="form-control" placeholder="Enter Your IFSC Code">
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                       
                                        <div class="form-group">
  <label for="customer">Are You Customer Of The Branch* :</label>
  <select class="form-control" name="customerofbranch" id="customer">
    <option>Yes</option>
    <option>No</option>
  </select>
</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                       
                                        <label>How Much Do You Want To Invest :</label>
                                        <input type="text" class="form-control"  name="investment" placeholder="Enter How Much  ">
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">                                    <div class="form-group">
  <label for="invest">INTRESTED IN* :</label>
  <select class="form-control" id="invest"  name="interestedin">
    <option>INSURANCE     </option>
    <option>STRATAGICAL TRADING </option>
    <option>MUTUAL FUNDS </option>
    <option>THEMATTIC INVESTMENT
</option>
<option>SELECT ALL
 </option>
  </select>
</div>
                                    </div>
                                </div>
                            	
                            </div>
                            	<div class="row">
                                	<div class="col-md-12">
                                    <div class="form-group">
                                        <label>Comments :</label>
                                        <textarea rows="3" cols="4"  name="comments"  class="form-control"></textarea>
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
    
    </div>
     <!--login-page-->
    
         <!-- end>
      </div>
      
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