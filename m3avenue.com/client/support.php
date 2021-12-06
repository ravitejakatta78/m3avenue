<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');
$userid = current_userid();
if(empty($userid)){
	header("Location:profile.php");
	}
	$message = ''; 
$usedetails = runQuery("select * from user where ID = '".$userid."'");
if(empty($userid)){
	header("Location: ../index.php");
}
if(!empty($_POST['submit'])){
	$signuparray['user_id'] = $userid;
	$signuparray['query'] = $_POST['query'];
	$result= insertQuery($signuparray,'support');
	if(!$result){	
	header("Location: support.php?usersuccess=success");
	}
}
?><!DOCTYPE html>
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
                        <h4 class="page-title">Support</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li class="active">Support</li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
                <!-- .row -->
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <div class="white-box">
                            <div class="user-bg">
                                <div class="overlay-box">
                                    <div class="user-content">
                                        <a href="javascript:void(0)"><img src="img/m3-logo.png" class="thumb-lg img-circle" alt="img"></a>
                                       
									</div>
                                </div>
                            </div>
                            <div class="user-btm-box">
                                <div class="col-md-12 col-sm-6 text-center">
									<h5 class="text-black"><span>Call Us: </span> +91 912345 67890</h5>
								</div>

								<div class="col-md-12 col-sm-6 text-center">
									<h5 class="text-black"><span>Mail Us: </span> support@m3avanue.com</h5>
								</div>								
                        </div>
						</div>
                    </div>
                    <div class="col-md-8 col-xs-12">
                        <div class="white-box">
                            <form method="post" action="" class="form-horizontal form-material">
								 <div class="form-group">
                                    <label for="example-email" class="col-md-12">Support Query</label>
                                    <div class="col-md-12">
                                        <textarea placeholder="Please Enter Your Query" required class="form-control form-control-line" name="query"></textarea>
									</div> 
                                    <div class="col-sm-6">
                                        <button type="submit" name="submit" value="submit" class="btn btn-success">Submit</button>
                                    </div>
									 
                                </div>
                            </form>
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
</body>

</html>
