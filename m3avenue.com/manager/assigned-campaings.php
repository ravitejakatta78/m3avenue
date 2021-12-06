<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_managerid(); 

if(empty($userid)){

	header("Location: index.php");

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
    <title>M3 Manager Campaigns Details</title>
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
                        <h4 class="page-title">Campaigns Details</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li class="active">Campaigns Details</li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
              
                <div class="row"> 
                    <div class="col-md-12 col-xs-12">
                        <div class="white-box">
                        <div class="table table-responsive">
<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>S.No</th>
							<th>Campaigns Id</th>
                            <th>Title</th>  
                            <th>Timeframe (seconds)</th>  
                            <th>Service</th> 
                            <th>Status</th> 
                            <th>Assign executives</th> 
                            <th>Upload excell</th> 
                            <th>Reg date</th>
                            <th>Delete</th>
							<!--<th>Status:</th>-->
						</tr>
						
							</thead>
								<tbody>
								  <?php
$sql = runloopQuery("SELECT * FROM campaigns where FIND_IN_SET('".$userid."',manager_id) order by ID desc");

   $x=1;  foreach($sql as $row)
		{
?>
<tr>
<td><?php echo  $x;?></td>
<td><?php echo $row["unique_id"];?></td>
<td><?php echo $row["title"];?></td>
<td><?php echo $row["timeframe"];?></td>
<td><?php echo $row["service"];?></td>  
<td><?php echo status_details($row["status"]);?></td> 
<td><a href="assign-campaign-executives.php?campaign=<?php echo $row["ID"];?>">Assign</a></td>
<td><a href="upload-campaign-excell.php?campaign=<?php echo $row["ID"];?>">Upload</a></td>
<td><?php echo reg_date($row["reg_date"]);?></td>
<td><a href="edit-campaigns-list.php?edit=<?php echo $row["ID"];?>">Edit</a> | <a onclick="return confirm('Are you sure want to delete??');"  href="?delete=<?php echo $row["ID"];?>">Delete</a></td>
</tr>
<?php
     $x++; }
?>
                          </tbody>
					</table>
					 
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
