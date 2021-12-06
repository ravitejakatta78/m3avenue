<?php
session_start();
error_reporting(E_ALL); 
include('../functions.php');
$userid = current_adminid(); 
if(empty($userid)){
	header("Location: login.php");
} 
$message = '';

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<meta name="robots" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template" />
	<meta property="og:title" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template" />
	<meta property="og:description" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template" />
	<meta property="og:image" content="https:/fillow.dexignlab.com/xhtml/social-image.png" />
	<meta name="format-detection" content="telephone=no">
	
	<!-- PAGE TITLE HERE -->
	<title>Fillow Saas Admin Dashboard</title>
	
	
	<?php include('header_scripts.php');?>

	<style>
.our-team .team-content .signature-content{
    font-size: 0;
    padding: 0;

    margin: 0;
    list-style: none;
}
.our-team .team-content .signature-content li{
    color: #000;
    font-size: 18px;
    letter-spacing: 0.5px;
    margin: 5px 30px 0;
    vertical-align: top;
    display: inline-block;
	
}
.our-team .team-content .signature-content li span{
    font-size: 18px;
  
}

.our-team {
  font-family: 'Roboto', sans-serif;
  padding: 10px 0 50px;
  margin-bottom: 30px;
  background-color: #C2B4DF;
  text-align:center;
  padding-right:450px;
  overflow: hidden;
  position: relative;
}


.our-team .picture {
  display: inline-block;
  height: 200px;
  width: 200px;
  margin-bottom: 50px;
  z-index: 1;
  position: relative;
}

.our-team .picture::before {
  content: "";
  width: 100%;
  height: 0;
  border-radius: 50%;
  background-color: #FFAFD7;
  position: absolute;
  bottom: 135%;
  right: 0;
  left: 0;
  opacity: 0.9;
  transform: scale(3);
  transition: all 0.3s linear 0s;
}

.our-team:hover .picture::before {
  height: 80%;
 
}




.our-team .picture::after {
  content: "";
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background-color: #FFAFD7;
  position: absolute;
  top: 0;
  left: 0;
  z-index: -1;
}

.our-team .picture img {
  width: 100%;
  height: auto;
  border-radius: 50%;
  transform: scale(1);
  transition: all 0.9s ease 0s;
}

.our-team:hover .picture img {
  box-shadow: 0 0 0 14px #f7f5ec;
  transform: scale(0.7);
}


.our-team .team-content{
   

    right:100px;
    vertical-align: top;
    display: inline-block;
	position:absolute;
}
.our-team .title{
    color: #111;
	
    font-size: 22px;
    font-weight: 700;
    text-transform: capitalize;
    margin: 0;
}
.our-team .title span{ color: #5bc908; }
.our-team .post{
    color: #333;
    font-size: 14px;
    font-weight: 500;
    text-transform: capitalize;
    margin: 0 0 7px;
    display: block;
}


.our-team .social {
  width: 100%;
  padding: 10px;
  margin: 0;
  background-color: #FFAFD7;
  position: absolute;
  bottom: -100px;
  height:50px;
  left: 0;
  transition: all 0.5s ease 0s;
    list-style: none;
}

.our-team:hover .social {
  bottom: 0;
}

.our-team .social li {
  display: inline-block;
  margin:-5px 20px;
  
  
}

.our-team .social li a {
  display: block;
  padding: 10px;
 
  font-size: 15px;
  color: #F83673;
  background-color: #fff;
  line-height: 28px;
  height: 40px;
  width: 40px;
  border-radius: 50%;
  transition: all 0.3s ease 0s;
  text-decoration: none;
  
   
  
  
}

.our-team .social li a:hover {
  
   color: #fff;
    background-color: #F83673;
    text-shadow: 0 0 5px #333;
}


.team-content .owner-content{
    color: #000;
	font-size:15px;
    font-weight: 500;
    letter-spacing: 0.5px;
    line-height: 28px;
    padding: 0;
    margin: 0;
    list-style: none;
    display: inline-block;
}


	
ul.ks-cboxtags {
    list-style: none;
    padding: 20px;
}
ul.ks-cboxtags li{
  display: inline;
}
ul.ks-cboxtags li label{
    display: inline-block;
    background-color: rgba(255, 255, 255, .9);
    border: 2px solid rgba(139, 139, 139, .3);
    color: #adadad;
    border-radius: 25px;
    white-space: nowrap;
    margin: 3px 0px;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
    transition: all .2s;
}

ul.ks-cboxtags li label {
    padding: 8px 12px;
    cursor: pointer;
}

ul.ks-cboxtags li label::before {
    display: inline-block;
    font-style: normal;
    font-variant: normal;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    font-size: 12px;
    padding: 2px 6px 2px 2px;
    content: "\f067";
    transition: transform .3s ease-in-out;
}

ul.ks-cboxtags li input[type="checkbox"]:checked + label::before {
    content: "\f00c";
    transform: rotate(-360deg);
    transition: transform .3s ease-in-out;
}

ul.ks-cboxtags li input[type="checkbox"]:checked + label {
    border: 2px solid #fff;
    background-color: #886CC0;
    color: #fff;
    transition: all .2s;
}

ul.ks-cboxtags li input[type="checkbox"] {
  display: absolute;
}
ul.ks-cboxtags li input[type="checkbox"] {
  position: absolute;
  opacity: 0;
}
ul.ks-cboxtags li input[type="checkbox"]:focus + label {
  border: 2px solid #e9a1ff;
}

.container{
  width: 85%;
  background: #fff;
  border-radius: 6px;
  padding: 20px 60px 30px 40px;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
}
.container .content{
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.container .content .left-side{
  width: 25%;
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  margin-top: 15px;
  position: relative;
}
.content .left-side::before{
  content: '';
  position: absolute;
  height: 70%;
  width: 2px;
  right: -15px;
  top: 50%;
  transform: translateY(-50%);
  background: #afafb6;
}
.content .left-side .details{
  margin: 14px;
  text-align: center;
}
.content .left-side .details i{
  font-size: 30px;
  color: #3e2093;
  margin-bottom: 10px;
}
.content .left-side .details .topic{
  font-size: 18px;
  font-weight: 500;
}
.content .left-side .details .text-one,
.content .left-side .details .text-two{
  font-size: 14px;
  color: #afafb6;
}
.container .content .right-side{
  width: 75%;
  margin-left: 75px;
  margin-top:50px;
  
}
.content .right-side .topic-text{
  font-size: 23px;
  font-weight: 800;
  color: #3e2093;
}
.right-side .input-box{
  height: 50px;
  width: 100%;
  margin: 12px 0;
}
.right-side .input-box input,
.right-side .input-box textarea{
  height: 100%;
  width: 100%;
  border: none;
  outline: none;
  font-size: 16px;
  background: #F0F1F8;
  border-radius: 6px;
  padding: 0 15px;
  resize: none;
}
.right-side .message-box{
  min-height: 110px;
}
.right-side .input-box textarea{
  padding-top: 6px;
}
.right-side .button{
  display: inline-block;
  margin-top: 12px;
}
.right-side .button input[type="button"]{
  color: #fff;
  font-size: 18px;
  outline: none;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  background: #3e2093;
  cursor: pointer;
  transition: all 0.3s ease;
}
.button input[type="button"]:hover{
  background: #5029bc;
}

@media (max-width: 950px) {
  .container{
    width: 90%;
    padding: 30px 40px 40px 35px ;
  }
  .container .content .right-side{
   width: 75%;
   margin-left: 55px;
}
}
@media (max-width: 820px) {
  .container{
    margin: 40px 0;
    height: 100%;
  }
  .container .content{
    flex-direction: column-reverse;
  }
 .container .content .left-side{
   width: 100%;
   flex-direction: row;
   margin-top: 40px;
   justify-content: center;
   flex-wrap: wrap;
 }
 .container .content .left-side::before{
   display: none;
 }
 .container .content .right-side{
   width: 100%;
   margin-left: 0;
 }
}


		

</style>

</head>
<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
		<div class="lds-ripple">
			<div></div>
			<div></div>
		</div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

       
		<?php include('header.php');?>
		
		
		<!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->
			<div class="container-fluid">

				<div class="row page-titles">
					<ol class="breadcrumb">
						<li class="breadcrumb-item active"><a href="javascript:void(0)">App</a></li>
						<li class="breadcrumb-item"><a href="javascript:void(0)">Profile</a></li>
					</ol>
                </div>
                <!-- row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="profile card card-body px-3 pt-3 pb-0">
							<div class="our-team">
							
							<div class="picture">
							<img class="img-fluid" src="images/logo.png"><br>
								<h3> Company Name</h3>
								
							</div>
        
			
			
		
							<div class="team-content">
							
						<h2 class="title">Owner <span>Name</span></h2><br>
                        <span class="post">Company type</span>
						 <br>
						 
						 <ul class="signature-content">
                        <li><i class="fa fa-phone"></i> 9999 999 999  </li>
                        <li><i class="fa fa-envelope"></i> example1@info.com</li>
                      </ul>
							
							
							</div>
        
						 <ul class="social">
                            <li><a href="#" class="fab fa-facebook-f"></a></li>
                            <li><a href="#" class="fab fa-twitter"></a></li>
                            <li><a href="#" class="fab fa-linkedin"></a></li>
                            <li><a href="#" class="fab fa-instagram"></a></li>
                        </ul>
							</div>
						</div>
                    </div>
                </div>
                <div class="row">
                   
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body t-2">
                                <div class="profile-tab">
                                    <div class="custom-tab-1">
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item"><a href="#subscribe" data-bs-toggle="tab" class="nav-link active show">Current Subscription</a>
                                            </li>
                                          
										  <li class="nav-item"><a href="#extend" data-bs-toggle="tab" class="nav-link">Extend Subscription</a>
                                            </li>
										  
											<li class="nav-item"><a href="#payment" data-bs-toggle="tab" class="nav-link">Payment History</a>
                                            </li>
											


											<li class="nav-item"><a href="#contact" data-bs-toggle="tab" class="nav-link">Contact Us</a>
                                            </li>
											
											
											
										</ul>
                                        <div class="tab-content">
                                            <div id="subscribe" class="tab-pane active show">
												<div id="Subscription" class="tab-pane active show">
											 <div class="col-lg-12">
												<div class="card">
												
												
    												<div class="card-body">
    												<div class="table-responsive">
    												<table class="table header-border table-hover verticle-middle">
        												<thead>
        												<tr>


        													<th scope="col">S.no</th>
        													<th scope="col">Feature</th>
        													<th scope="col">No Of Emp</th>
        													<th scope="col">From</th>
        													<th scope="col">Time period<br>(In Months)</th>
        													<th scope="col">Price <br>(perEmp/mnth)</th>
        													<th scope="col">Total</th>
        												</tr>
        												</thead>
                                            
        											<tbody>
            												<tr>
                                                                <th>1</th>
                                                                <td> Points Set </td>
                                                                <td>30days<br><h6>(10employees)</h6></td>
                												<td>10days<br><h6>(5 employees)</h6></td>
                                                                <td>10/11/2021<br><h6>(1day left)</h6></td>
                												<td>11/11/2021<br><a  href="#" style="color:red;">Extend </a></td>
            												
        												
        												    </tr>
                                                
        												<tr>
                                                        <th>2</th>
                                                        <td>  Marketing Configurations  </td>
                                                        <td>30days<br><h6>(10employees)</h6></td>
        												<td>10days<br><h6>(5 employees)</h6></td>
                                                        <td>10/11/2021<br><h6>(1day left)</h6></td>
        													<td>11/11/2021<br><a href="#" style="color:red;">Extend </a></td>
        												</tr>
        												
        												<tr>
                                                        <th>3</th>
                                                        <td>  Business Kit </td>
                                                        <td>30days<br><h6>(10employees)</h6></td>
        												<td>10days<br><h6>(5 employees)</h6></td>
                                                        <td>10/11/2021<br><h6>(1day left)</h6></td>
        												<td>11/11/2021<br><a href="#" style="color:red;">Extend </a></td>
        												</tr>
        												
        												<tr>
                                                        <th>4</th>
                                                        <td> Capital Management </td>
                                                        <td>30days<br><h6>(10employees)</h6></td>
        												<td>10days<br><h6>(5 employees)</h6></td>
                                                        <td>10/11/2021<br><h6>(1day left)</h6></td>
        													<td>11/11/2021<br><a style="color:red;">Extend </a></td>
        												</tr>
        												
        												<tr>
                                                        <th>5</th>
                                                        <td> Operations </td>
                                                        <td>30days<br><h6>(10employees)</h6></td>
        												<td>10days<br><h6>(5 employees)</h6></td>
                                                        <td>10/11/2021<br><h6>(1day left)</h6></td>
        													<td>11/11/2021<br><a style="color:red;">Extend </a></td>
        												</tr>
        												
        												<tr>
                                                        <th>6</th>
                                                        <td> Task Management </td>
                                                        <td>30days<br><h6>(10employees)</h6></td>
        												<td>10days<br><h6>(5 employees)</h6></td>
                                                        <td>10/11/2021<br><h6>(1day left)</h6></td>
        													<td>11/11/2021<br><a style="color:red;">Extend </a></td>
        												</tr>
        												
        												<tr>
                                                        <th>7</th>
                                                        <td> Communication Services  </td>
                                                        <td>30days<br><h6>(10employees)</h6></td>
        												<td>10days<br><h6>(5 employees)</h6></td>
                                                        <td>10/11/2021<br><h6>(1day left)</h6></td>
        												<td>11/11/2021<br><a href="#" style="color:red;">Extend </a></td>
        												</tr>
        												
        												<tr>
                                                        <th>8</th>
                                                        <td>  HRMS </td>
                                                        <td>30days<br><h6>(10employees)</h6></td>
        												<td>10days<br><h6>(5 employees)</h6></td>
                                                        <td>10/11/2021<br><h6>(1day left)</h6></td>
        													<td>11/11/2021<br><a style="color:red;">Extend </a></td>
        												</tr>
        			
        												<tr>
                                                        <th>9</th>
                                                        <td> Daily Finance </td>
                                                        <td>30days<br><h6>(10employees)</h6></td>
        												<td>10days<br><h6>(5 employees)</h6></td>
                                                        <td>10/11/2021<br><h6>(1day left)</h6></td>
        												<td>11/11/2021<br><a href="#" style="color:red;">Extend </a>
        												</td>
        												</tr>
        												
        												<tr>
                                                        <th>10</th>
                                                        <td> Events  </td>
                                                        <td>30days<br><h6>(10employees)</h6></td>
        												<td>10days<br><h6>(5 employees)</h6></td>
                                                        <td>10/11/2021<br><h6>(1day left)</h6></td>
        													<td>11/11/2021<br><a style="color:red;">Extend </a></td>
        												</tr>
        												
        												<tr>
                                                        <th>11</th>
                                                        <td> Tools & Configuration  </td>
                                                        <td>30days<br><h6>(10employees)</h6></td>
        												<td>10days<br><h6>(5 employees)</h6></td>
                                                        <td>10/11/2021<br><h6>(1day left)</h6></td>
        												<td>11/11/2021<br><a href="#" style="color:red;">Extend </a></td>
        												</tr>
        												
        												<tr>
                                                        <th>12</th>
                                                        <td>  PI-Eligibility </td>
                                                        <td>30days<br><h6>(10employees)</h6></td>
        												<td>10days<br><h6>(5 employees)</h6></td>
                                                        <td>10/11/2021<br><h6>(1day left)</h6></td>
        													<td>11/11/2021<br><a style="color:red;">Extend </a></td>
        												</tr>
        												
        												<tr>
                                                        <th>13</th>
                                                        <td> Dialer  </td>
                                                        <td>30days<br><h6>(10employees)</h6></td>
        												<td>10days<br><h6>(5 employees)</h6></td>
                                                        <td>10/11/2021<br><h6>(1day left)</h6></td>
        													<td>11/11/2021<br><a style="color:red;">Extend </a></td>
        												</tr>
        												
        												<tr>
                                                        <th>14</th>
                                                        <td> Lead Management  </td>
                                                        <td>30days<br><h6>(10employees)</h6></td>
        												<td>10days<br><h6>(5 employees)</h6></td>
                                                        <td>10/11/2021<br><h6>(1day left)</h6></td>
        													<td>11/11/2021<br><a style="color:red;">Extend </a></td>
        												</tr>
        												
        												<tr>
                                                        <th>15</th>
                                                        <td>Employee Management  </td>
                                                        <td>30days<br><h6>(10employees)</h6></td>
        												<td>10days<br><h6>(5 employees)</h6></td>
                                                        <td>10/11/2021<br><h6>(1day left)</h6></td>
        													<td>11/11/2021<br><a style="color:red;">Extend </a></td>
        												</tr>
    											
                                                
    												</tbody>
    												</table>
    												</div>
												</div>
												</div>
											 </div>											
											</div>			
											
											</div>
							
							
										
                                            <div id="extend" class="tab-pane fade">
											
												<div id="subscribe" class="tab-pane active show">
												<div id="Subscription" class="tab-pane active show">
												<div class="col-lg-12">
												<div class="card">
												<div class="card-body">
												<div class="table-responsive">
												<table class="table header-border table-hover verticle-middle">
												<thead>
												<tr>


													<th scope="col">S.no</th>
													<th scope="col">Feature</th>
													<th scope="col">Active For<br>(No Of Emp)</th>
													<th scope="col">From</th>
													<th scope="col">Time Period</th>
													<th scope="col">Price</th>
													<th>Total</th>
												</tr>
												</thead>
                                        
												<tbody>
												
												<tr>
											
                                                <th> <input type="checkbox" id="group1"></th>
                                                <td>  Point set </td>
												<td>   <input type="text" class="group1"></td>
                                                <td> <input type="text" class="group1"></td>
												<td><input type="text" class="group1"><td>
												<td>Cost</td>
												<th>Total</th>
												</tr>
                                       						
												<tr>
                                               <th> <input type="checkbox" id="group2"></th>
											   <td>  Marketing Configurations  </td>
												<td>   <input type="text" class="group1"></td>
                                                <td> <input type="text" class="group1"></td>
												<td><input type="text" class="group1"><td>
												<td>Cost</td>
												<th>Total</th></tr>
												
												<tr>
                                               <th> <input type="checkbox" id="group3"></th>
                                                <td>  Business Kit </td>
                                               <td>   <input type="text" class="group1"></td>
                                                <td> <input type="text" class="group1"></td>
												<td><input type="text" class="group1"><td>
												<td>Cost</td>
												<th>Total</th></tr>
												
												<tr>
                                                <th> <input type="checkbox" id="group4"></th>
                                                <td> Capital Management </td>
                                                <td>   <input type="text" class="group1"></td>
                                                <td> <input type="text" class="group1"></td>
												<td><input type="text" class="group1"><td>
												<td>Cost</td>
												<th>Total</th></tr>
												
												<tr>
                                                <th> <input type="checkbox" id="group5"></th>
                                                <td> Operations </td>
                                                <td>   <input type="text" class="group1"></td>
                                                <td> <input type="text" class="group1"></td>
												<td><input type="text" class="group1"><td>
												<td>Cost</td>
												<td>Total</td>
												</tr>
												
												<tr>
                                                <th> <input type="checkbox" id="group6"></th>
                                                <td> Task Management </td>
                                                 <td>   <input type="text" class="group1"></td>
                                                <td> <input type="text" class="group1"></td>
												<td><input type="text" class="group1"><td>
												<td>Cost</td>
												<td>Total</td>
												</tr>
												
												<tr>
                                                <th> <input type="checkbox" id="group7"></th>
                                                <td> Communication Services  </td>
                                                 <td>   <input type="text" class="group1"></td>
                                                <td> <input type="text" class="group1"></td>
												<td><input type="text" class="group1"><td>
												<td>Cost</td>
												<td>Total</td>
												</tr>
												
												<tr>
                                               <th> <input type="checkbox" id="group8"></th>
                                                <td>  HRMS </td>
                                                 <td>   <input type="text" class="group1"></td>
                                                <td> <input type="text" class="group1"></td>
												<td><input type="text" class="group1"><td>
												<td>Cost</td>
												<td>Total</td>
												</tr>
			
												<tr>
                                                <th> <input type="checkbox" id="group9"></th>
                                                <td> Daily Finance </td>
                                                 <td>   <input type="text" class="group1"></td>
                                                <td> <input type="text" class="group1"></td>
												<td><input type="text" class="group1"><td>
												<td>Cost</td>
												<td>Total</td>
												</tr>
												
												<tr>
                                              <th> <input type="checkbox" id="group10"></th>
                                                <td> Events  </td>
                                                 <td>   <input type="text" class="group1"></td>
                                                <td> <input type="text" class="group1"></td>
												<td><input type="text" class="group1"><td>
												<td>Cost</td>
												<td>Total</td></tr>
												
												<tr>
                                                <th> <input type="checkbox" id="group11"></th>
                                                <td> Tools & Configuration  </td>
                                                 <td>   <input type="text" class="group1"></td>
                                                <td> <input type="text" class="group1"></td>
												<td><input type="text" class="group1"><td>
												<td>Cost</td>
												<td>Total</td></tr>
												
												<tr>
                                               <th> <input type="checkbox" id="group12"></th>
                                                <td>  PI-Eligibility </td>
                                                 <td>   <input type="text" class="group1"></td>
                                                <td> <input type="text" class="group1"></td>
												<td><input type="text" class="group1"><td>
												<td>Cost</td>
												<td>Total</td></tr>
												
												<tr>
                                                <th> <input type="checkbox" id="group13"></th>
                                                <td> Dialer  </td>
                                                 <td>   <input type="text" class="group1"></td>
                                                <td> <input type="text" class="group1"></td>
												<td><input type="text" class="group1"><td>
												<td>Cost</td>
												<td>Total</td></tr>
												
												<tr>
                                                <th> <input type="checkbox" id="group14"></th>
                                                <td> Lead Management  </td>
                                                 <td>   <input type="text" class="group1"></td>
                                                <td> <input type="text" class="group1"></td>
												<td><input type="text" class="group1"><td>
												<td>Cost</td>
												<td>Total</td></tr>
												
												<tr>
                                               <th> <input type="checkbox" id="group15"></th>
                                                <td>Employee Management  </td>
                                                 <td>   <input type="text" class="group1"></td>
                                                <td> <input type="text" class="group1"></td>
												<td><input type="text" class="group1"><td>
												<td>Cost</td>
												<td>Total</td></tr>
											
                                            
												</tbody>
												</table>
												</div>
												</div>
												</div>
											
											</div>	
							
							
							
							
							
							
							
							
							
							
							
                                            <div id="payment" class="tab-pane fade">
											
												<div class="col-lg-12">
												<div class="card">
												
												<div class="card-body">
												<div class="table-responsive">
												<table class="table header-border table-hover verticle-middle">
												<thead>
												<tr>
													<th scope="col">S.no</th>
													<th scope="col">Transaction Id</th>
													<th scope="col">Date/Time</th>
													<th scope="col">Bill Details</th>
													
												</tr>
												</thead>
                                        
												<tbody>
												<tr>
                                                <th>1</th>
                                                <td>Id no</td>
                                                <td>00/00/00<br>02:00</td>
												<td>  <button type="button" class="btn  btn-square btn-primary">View</button>
												</td>
                                               
												</tr>
                                            
												<tr>
                                                <th>2</th>
                                                <td>Id no</td>
                                                <td>00/00/00<br>02:00</td>
												<td> <button type="button" class="btn  btn-square btn-primary">View</button></td>
                                               
												</tr>
                                            
												<tr>
                                                 <th>3</th>
                                                <td>Id no</td>
                                                <td>00/00/00<br>02:00</td>
												<td> <button type="button" class="btn  btn-square btn-primary">View</button></td>
                                                </td>
												</tr>
                                            
												</tbody>
												</table>
												</div>
												</div>
												</div>
												</div>
											</div>				
											
												
											<div id="contact" class="tab-pane fade">
												<div class="container">
												<div class="content">
												<div class="left-side">
												<div class="address details">
												<i class="fas fa-map-marker-alt"></i>
												<div class="topic">Address</div>
												<div class="text-one">Company Address</div>
												<div class="text-two">Full Address</div>
												</div>
		 
												<div class="phone details">
												<i class="fas fa-phone-alt"></i>
												<div class="topic">Phone</div>
												<div class="text-one">+0091 0000 0000</div>
												<div class="text-two">+0091 0000 0000</div>
												</div>
												
												<div class="email details">
												<i class="fas fa-envelope"></i>
												<div class="topic">Email</div>
												<div class="text-one">info@m3avenue.com</div>
												</div>
		 
												<div class="email details">
												<i class="fas fa-window-maximize"></i>
												<div class="topic">Website</div>
												<div class="text-one">info@m3avenue.in</div>
												</div>
		
											</div>
      
											<div class="right-side">
											<div class="topic-text">Send us a message</div>
											<p> Any matter paragraph Any matter paragraph Any matter paragraph Any matter paragraph Any matter paragraph </p>
											<form action="#">
											<div class="input-box">
											<input type="text" placeholder="Enter your name">
											</div>
											<div class="input-box">
											<input type="text" placeholder="Enter your email">
											</div>
											<div class="input-box message-box">
          
											</div>
											<div class="button">
											<input type="button" value="Send Now" >
											</div>
											</form>
											</div>
											</div>
											</div>
											</div>
									
									
									
									
									
									
									
										</div>
                                    </div>
                                </div>
								
                            </div>
                        </div>
                    </div>
                </div>
				
			
			 						
				
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->


	</div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <?php include('footer_scripts.php');?>

     <script>
   
   
   
   $(function() {
  enable_cb();
  $("#group1").click(enable_cb);
});

function enable_cb() {
  if (this.checked) {
    $("input.group1").removeAttr("disabled");
  } else {
    $("input.group1").attr("disabled", true);
  }
}
   
 
   
   
   </script>

</body>
</html>