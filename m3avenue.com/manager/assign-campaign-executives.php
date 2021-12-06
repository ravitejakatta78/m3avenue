<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');

$userid = current_managerid(); 
$empdet = runQuery("select * from employee where ID = '".$userid."'");
if(empty($userid)){

	header("Location: index.php");

}
$campainid = $_GET['campaign'];
if(empty($userid)){

	header("Location: campaigns-list.php");

}
$compaindetails = runQuery("select * from campaigns where ID = '".$campainid."'");
if(empty($compaindetails)){

	header("Location: campaigns-list.php");

}
$message = '';
if(!empty($_POST['submit'])){ 
	   $pagerarray  =	   $pagewhererarray  = array();
	   $pagewhererarray['ID'] = $_POST['submit'];
	$execuitveids = !empty($_POST['executivelist']) ? implode(',',$_POST['executivelist']) : '';
	$pagerarray['executive'] = $execuitveids;  
	
	$result = updateQuery($pagerarray,'campaigns',$pagewhererarray);
	if(!$result){
		header("Location: assign-campaign-executives.php?campaign=".$pagewhererarray['ID']."&success=success");
		}
	     

}

if(!empty($_GET['delete'])){
	$sql = "DELETE FROM executive WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: executive-list.php?success=success");
   
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
                        <h4 class="page-title">Assign Executives</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li class="active">Assign Executives</li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
               <div class="col-12">
								 Select Executives for <?php echo $compaindetails['title'];?> 
								</div>  
								<a href="campaigns-list.php" class="btn btn-primary pull-right" >Back to Campaigns</a> 
                <div class="row"> 
                    <div class="col-md-12 col-xs-12">
                        <div class="white-box">
                        <div class="table table-responsive">

						<!--begin: Datatable -->
		 <?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Executives Assigned Succussfully
								</div>
								<?php } ?>
						   <?php if(!empty($_GET['psuccess'])){?>
								<div class="alert alert-success">
								<?php echo $_GET['psuccess'];?>
								</div>
								<?php } ?>
								<?php if(!empty($message)){?>
								<div class="alert alert-danger">
								<?=$message?>
								</div>
								<?php } ?>
		<form method="post" action="">
						<!--begin: Datatable -->
			<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>S.No</th>
                            <th>By</th>   
							<th>Executive Id</th>
                            <th>Full Name</th>   
                            <th>Location</th>  
						</tr> 
							</thead>
								<tbody>
								  <?php
								  
								  $executivearray = explode(',',$compaindetails['executive']);
					$managerexe = $campaignrexe = $totalarray = array();			  
$managerexe = runloopQuery("SELECT * FROM employee where role_id = '4' and status = '1' and leader = '".$empdet['unique_id']."' order by ID desc");
$campaignrexe = runloopQuery("SELECT * FROM employee where status = '1' and FIND_IN_SET_X('".$compaindetails['executive']."',ID)");
								   
$totalarray = array_merge($campaignrexe,$managerexe);
   $x=1;  foreach($totalarray as $row)
		{
?>
<tr>
<td><input type="checkbox" name="executivelist[]" value="<?php echo $row["ID"];?>" <?php if(in_array($row["ID"],$executivearray)){ echo 'checked';}?>  /></td>
<td><?php echo $row['role_id']=='1' ? 'Admin' : ucwords(manager_details($userid,'fname'));?>
<br>
<?php echo  $row['role_id']=='3' ? ucwords(roles($row['role_id'])) : '';?></td>
<td><?php echo $row["unique_id"];?></td>
<td><?php echo $row["fname"].' '.$row["lname"];?> 
<td><?php echo $row["location"];?></td>   
</tr>
<?php
     $x++; }
?>
                          </tbody>
					</table>
					
					<div class="">
						<button type="submit" class="btn btn-primary" name="submit" value="<?php echo $compaindetails['ID'];?>" >Submit</button>
					</div>
				<!--end: Datatable --> 
				</form>
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
