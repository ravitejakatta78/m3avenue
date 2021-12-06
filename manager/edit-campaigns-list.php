<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');
 
$userid = current_managerid(); 

if(empty($userid)){

	header("Location: index.php");

}

$campaignscurrent = runQuery("select * from campaigns where ID = '".$_GET['edit']."'"); 
$message = '';
if(!empty($_POST['submit'])){
			if(!empty($_POST['title'])){
	   $pagerarray  =  $pagewhererarray  = array();
				$pagewhererarray['ID'] = $campaignscurrent['ID'];
                $pagerarray['title'] = mysqli_real_escape_string($conn,$_POST['title']);   
                $pagerarray['service'] = mysqli_real_escape_string($conn,$_POST['servicetype']);  
                $pagerarray['timeframe'] = mysqli_real_escape_string($conn,$_POST['timeframe']);   
                $pagerarray['feedbackoptions'] = !empty($_POST['feedbackoptions']) ? implode(',',$_POST['feedbackoptions']) : '';
	            $result = updateQuery($pagerarray,'campaigns',$pagewhererarray);
				if(!$result){
					header("Location: campaigns-list.php?update=success");
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
    <!-- color CSS -->
	<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
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
                            <input class="form-control" name="title" type="text" placeholder="Enter Name" value="<?php echo $campaignscurrent['title'];?>" id="example-text-input">
                        </div>  
						</div>  
						<div class="form-group col-md-6">
						    
                        <label for="example-text-input" class="col-2 col-form-label">Time frame (seconds)</label>
                        <div class="col-3">
                            <input class="form-control" name="timeframe" type="text" placeholder="Enter Time frame" value="<?php echo $campaignscurrent['timeframe'];?>"  >
                        </div>  
						</div>   
						<div class="form-group col-md-6">
                        <label for="example-text-input" class="col-2 col-form-label">Service type</label>
                        <div class="col-3">
                            <input class="form-control" name="servicetype" type="text" placeholder="Enter Service type" value="<?php echo $campaignscurrent['service'];?>"  >
                        </div> 
						</div>   
					 <?php $currentfeebacks = explode(',',$campaignscurrent['feedbackoptions']);?>
						<div class="form-group col-md-6">
                        <label for="example-text-input" class="col-md-12 col-form-label">Feedback list</label>
                        <div class="col-md-12">
                            <select class="select2" name="feedbackoptions[]" multiple >
							<option value="">--Select Feedback--</option>
							<?php $campaignsmanagers = runloopQuery("select * from feedbackoptions order by ID asc");
								foreach($campaignsmanagers as $campaigns){ ?>
									<option value="<?php echo $campaigns['ID'];?>" <?php if(in_array($campaigns['ID'],$currentfeebacks)){ echo 'selected';}?>><?php echo $campaigns['title'];?></option>
								<?php }?>
							</select>
                        </div> 
						</div> 
					
						</div> 
						<div class="row">
						<div class="form-group col-md-6">
                       <div class="col-3">
                             <button type="submit" name="submit" value="submit" class="btn btn-brand btn-elevate btn-pill">Update Campaigns</button>
						</div>
						</div>  
						</div>  
					</form> 
                        </div>
                    </div>
                </div>
				
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
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
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
