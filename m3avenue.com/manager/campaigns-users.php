<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');
include('../inc/PHPExcel.php');
include('../inc/PHPExcel/IOFactory.php'); 

$userid = current_managerid(); 

if(empty($userid)){

	header("Location: index.php");

}
$campainid = !empty($_GET['campaign']) ? $_GET['campaign'] : '';
$campaignsheet = !empty($_GET['campaignsheet']) ? $_GET['campaignsheet'] : '';
if(empty($userid)){

	header("Location: campaigns-list.php");

}
$compaindetails = runQuery("select * from campaigns where ID = '".$campainid."'");
$compainsheetdetails = runQuery("select * from campaigns_excell where ID = '".$campaignsheet."'");
if(empty($compaindetails)){ 
	header("Location: campaigns-list.php");

} 
if(!empty($_GET['delete'])){
	$sql = "DELETE FROM campaigns_users WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: upload-campaign-excell.php?campaign=".$_GET['campaign']."&dsuccess=success");
   
} else {
    echo "Error deleting record: " . $conn->error;
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
       <?php include('header.php');?>
        <!-- Left navbar-header end -->
		
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Campaigns</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li class="active">Profile page</li>
                        </ol>
                    </div>
                </div>
                <!-- /.row --> 
                <div class="row"> 
                    <div class="col-md-12 col-xs-12">
                        <div class="white-box">
                        <div class="table table-responsive">

						<!--begin: Datatable -->
		<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>S.No</th>    
                            <th>Full Name,Mobile</th>    
                            <th>Reg date</th>  
                            <th>Action</th>  
						</tr> 
							</thead>
							<tbody>
								  <?php
								   if(!empty($compainsheetdetails['ID'])){
							$sql = runloopQuery("SELECT * FROM campaigns_users where  campaign_id = '".$compaindetails['ID']."' and  campaignexcell_id = '".$compainsheetdetails['ID']."' and callstatus = '0' order by ID desc");
								   }else{
							$sql = runloopQuery("SELECT * FROM campaigns_users where  campaign_id = '".$compaindetails['ID']."' and callstatus = '0' order by ID desc");
								   }
							   $x=1;  foreach($sql as $row)
									{
							?>
							<tr>
							<td><?php echo  $x;?></td>    
							<td><?php echo $row["name"];?> 
							<br/><?php echo $row["mobile"];?></td>     
							<td><?php echo reg_date($row["reg_date"]);?></td>
							<td><a onclick="return confirm('Are you sure want to delete??');"  href="?campaign=<?php echo $compaindetails['ID']; ?>&delete=<?php echo $row["ID"];?>">Delete</a></td>
							</tr>
							<?php
								 $x++; }
							?>
													  </tbody>
													  
							<tbody>
							 <?php ?>
							</tbody>
					</table>
				<!--end: Datatable -->
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
