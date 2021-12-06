<?php
session_start();
error_reporting(E_ALL); 
include('../functions.php');
$userid = current_adminid(); 
if(empty($userid)){
	header("Location: login.php");
} 
$message = '';

$campaignsdetails = runQuery("select * from campaigns where ID = '".$_GET['edit']."'");
$message = '';

$message = '';
if(!empty($_POST['submit'])){
			if(!empty($_POST['title'])){
	   $pagerarray  = array();
  
				$pagewererarray['ID'] = $campaignsdetails['ID'];  
                $pagerarray['title'] = mysqli_real_escape_string($conn,$_POST['title']); 
                $pagerarray['user_id'] = $userid; 
                $pagerarray['manager_id'] = !empty($_POST['manager']) ? implode(',',$_POST['manager']) : ''; 
                $pagerarray['service'] = mysqli_real_escape_string($conn,$_POST['servicetype']); 
                $pagerarray['status'] = mysqli_real_escape_string($conn,$_POST['status']); 
                $pagerarray['timeframe'] = mysqli_real_escape_string($conn,$_POST['timeframe']);  
                $pagerarray['feedbackoptions'] = !empty($_POST['feedbackoptions']) ? implode(',',$_POST['feedbackoptions']) : '';
	            $result = updateQuery($pagerarray,'campaigns',$pagewererarray);
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

				<div class="row">

				<div class="col-12">
					<div class="card">

						<div class="card-body">

							 <form  method="post" action="" enctype="multipart/form-data" autocomplete="off" >
					 
						<div class="row">
						<div class="form-group col-md-6">
						<label for="example-text-input" class="col-12 col-form-label">Enter Title</label>
                        <div class="col-md-12"> 			
                            <input class="form-control" name="title" type="text" placeholder="Enter Title"  value="<?php echo $campaignsdetails['title']; ?>" >
                        </div> 
						</div>
						<div class="form-group col-md-6">
                        <label for="example-text-input" class="col-12 col-form-label">Time frame (seconds)</label>
                        <div class="col-md-12"> 			
                            <input class="form-control" name="timeframe" type="text" placeholder="Enter Time frame" value="<?php echo $campaignsdetails['timeframe']; ?>"  >
                        </div>  
						</div>   
						<div class="form-group col-md-6">
                        <label for="example-text-input" class="col-12 col-form-label">Service type</label>
                        <div class="col-md-12"> 		
                            <input class="form-control" name="servicetype" type="text" placeholder="Enter Service type" value="<?php echo $campaignsdetails['service']; ?>" >
                        </div> 
                    </div>   
						<div class="form-group col-md-6">
                        <label for="example-text-input" class="col-12 col-form-label">Manager</label>
                        <div class="col-md-12"> 			
                            <select class="form-control select2" name="manager[]" multiple >
							<option value="">--Select Manager--</option>
							<?php 
							$campaignsarray = explode(',',$campaignsdetails['manager_id']);
							$campaignsmanagers = runloopQuery("select * from manager order by ID desc");
								foreach($campaignsmanagers as $campaigns){
							?>
							<option value="<?php echo $campaigns['ID'];?>" <?php if(in_array($campaigns['ID'],$campaignsarray)){ echo 'selected';}?>><?php echo $campaigns['name'];?></option>
								<?php }?>
							</select>
                        </div> 
						</div>
					 <?php $currentfeebacks = explode(',',$campaignsdetails['feedbackoptions']);?>
						<div class="form-group col-md-6">
                        <label for="example-text-input" class="col-md-12 col-form-label">Feedback list</label>
                        <div class="col-md-12"> 			
                            <select class="form-control select2" name="feedbackoptions[]" multiple >
							<option value="">--Select Feedback--</option>
							<?php $campaignsmanagers = runloopQuery("select * from feedbackoptions order by ID asc");
								foreach($campaignsmanagers as $campaigns){ ?>
									<option value="<?php echo $campaigns['ID'];?>" <?php if(in_array($campaigns['ID'],$currentfeebacks)){ echo 'selected';}?>><?php echo $campaigns['title'];?></option>
								<?php }?>
							</select>
                        </div> 
						</div> 
						
						<div class="form-group col-md-6">
						<label for="example-text-input" class="col-12 col-form-label">Status</label>
                        <div class="col-md-12"> 			
							<select class="form-control" name="status">
								<option value="">--Select--</option> 
								<option value="0" <?php if($campaignsdetails['status']=='0'){ echo 'selected';}?>>Pending</option>
								<option value="1" <?php if($campaignsdetails['status']=='1'){ echo 'selected';}?>>Active</option>
								<option value="2" <?php if($campaignsdetails['status']=='2'){ echo 'selected';}?>>Inactive</option>
							</select>
                        </div>
						</div>	 
					  
						</div> 
					
					 <div class="form-group row">  
                        <div class="col-md-6"> 			
                             <button type="submit" name="submit" value="submit" class="btn btn-brand btn-elevate btn-pill">Update Campaigns</button>
						</div>
                    </div>  
					</form>
		
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

</body>
</html>