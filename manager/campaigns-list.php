<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_managerid(); 

if(empty($userid)){

	header("Location: index.php");

}

$message = '';
if(!empty($_POST['submit'])){
			if(!empty($_POST['title'])){
	   $pagerarray  = array();
 
				$uniqueusers = (int)runQuery("select max(ID) as id from campaigns order by ID desc")['id'];
				$newuniquid = $uniqueusers+1; 
                $pagerarray['title'] = mysqli_real_escape_string($conn,$_POST['title']); 
                $pagerarray['user_id'] = $userid; 
                $pagerarray['user_role'] = 'manager'; 
                $pagerarray['unique_id'] = 'M3C'.sprintf('%05d',$newuniquid);
                //$pagerarray['manager_id'] = mysqli_real_escape_string($conn,$_POST['manager']); 
                $pagerarray['service'] = mysqli_real_escape_string($conn,$_POST['servicetype']);  
                $pagerarray['timeframe'] = mysqli_real_escape_string($conn,$_POST['timeframe']);  
                $pagerarray['status'] = 1; 
                $pagerarray['feedbackoptions'] = !empty($_POST['feedbackoptions']) ? implode(',',$_POST['feedbackoptions']) : '';
	            $result = insertQuery($pagerarray,'campaigns');
				if(!$result){
					header("Location: campaigns-list.php?success=success");
                }
	    
		 	}else{

		$message .="Campaigns title is Empty";

	}

}

if(!empty($_GET['delete'])){
	$sql = "DELETE FROM campaigns WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: campaigns-list.php?success=success");
   
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
	<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
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
                <!-- .row -->
                <div class="row"> 
                    <div class="col-md-12 col-xs-12">
                        <div class="white-box">
                         <?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Campaigns Added Succussfully
								</div>
								<?php } ?>
                         <?php if(!empty($_GET['update'])){?>
								<div class="alert alert-success">
								Campaigns updated Succussfully
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
					<form  method="post" action="" enctype="multipart/form-data" autocomplete="off" > 
						<div class="row">
						<div class="form-group col-md-6">
						    
                        <label for="example-text-input" class="col-2 col-form-label">Enter title</label>
                        <div class="col-3">
                            <input class="form-control" name="title" type="text" placeholder="Enter Name" id="example-text-input">
                        </div>  
						</div>  
						<div class="form-group col-md-6">
						    
                        <label for="example-text-input" class="col-2 col-form-label">Time frame (seconds)</label>
                        <div class="col-3">
                            <input class="form-control" name="timeframe" type="text" placeholder="Enter Time frame"  >
                        </div>  
						</div>   
						<div class="form-group col-md-6">
                        <label for="example-text-input" class="col-2 col-form-label">Service type</label>
                        <div class="col-3">
                            <input class="form-control" name="servicetype" type="text" placeholder="Enter Service type"  >
                        </div> 
						</div>  
						<div class="form-group col-md-6">
                        <label for="example-text-input" class="col-md-12 col-form-label">Feedback list</label>
                        <div class="col-md-12">
                            <select class="select2" name="feedbackoptions[]" multiple >
							<option value="">--Select Feedback--</option>
							<?php $campaignsmanagers = runloopQuery("select * from feedbackoptions order by ID asc");
								foreach($campaignsmanagers as $campaigns){ ?>
									<option value="<?php echo $campaigns['ID'];?>"><?php echo $campaigns['title'];?></option>
								<?php }?>
							</select>
                        </div> 
						</div> 
					
						</div> 
						<div class="row">
						<div class="form-group col-md-6">
                       <div class="col-3">
                             <button type="submit" name="submit" value="submit" class="btn btn-brand btn-elevate btn-pill">Add Campaigns</button>
						</div>
						</div>  
						</div>  
					</form> 
                        </div>
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-md-12 col-xs-12">
                        <div class="white-box">
                             <a href="csv-campaigns.php" class="btn btn-brand btn-success btn-pill">Download</a>
                        <div class="table table-responsive">

						<!--begin: Datatable -->
			<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>S.No</th>
							<th>Campaigns Id</th>
                            <th>Title</th>  
                            <th>Timeframe (seconds)</th>  
                            <th>Service</th> 
                            <th>Feedback options</th> 
                            <th>Status</th> 
                            <th>Assign executives</th> 
                            <th>Upload excell</th> 
                            <th>Pending users</th> 
                            <th>Reg date</th>
                            <th>Delete</th>
							<!--<th>Status:</th>-->
						</tr>
						
							</thead>
								<tbody>
								  <?php
$sql = runloopQuery("SELECT * FROM campaigns where user_id = '".$userid."' and user_role = 'manager' order by ID desc");

   $x=1;  foreach($sql as $row)
		{
			
			$feedbacknames = runQuery("SELECT group_concat(title) as campname FROM feedbackoptions WHERE  FIND_IN_SET_X('".$row['feedbackoptions']."',ID)");
?>
<tr>
<td><?php echo  $x;?></td>
<td><?php echo $row["unique_id"];?></td>
<td><?php echo $row["title"];?></td>
<td><?php echo $row["timeframe"];?></td>
<td><?php echo $row["service"];?></td>  
<td><?php echo $feedbacknames["campname"];?></td>
<td><?php echo status_details($row["status"]);?></td> 
<td><a href="assign-campaign-executives.php?campaign=<?php echo $row["ID"];?>">Assign</a></td>
<td><a href="upload-campaign-excell.php?campaign=<?php echo $row["ID"];?>">Upload</a></td>
<td><a href="campaigns-users.php?campaign=<?php echo $row["ID"];?>">Users</a></td>
<td><?php echo reg_date($row["reg_date"]);?></td>
<td><a href="edit-campaigns-list.php?edit=<?php echo $row["ID"];?>">Edit</a> </td>
</tr>
<?php
     $x++; }
?>
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
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Menu Plugin JavaScript --> 
    <!--slimscroll JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/custom.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
		$(".select2").select2({
			width: '100%'
		});
	</script>
	
</body>

</html>
