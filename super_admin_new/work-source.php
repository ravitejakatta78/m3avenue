<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');

$userid = current_adminid(); 
if(empty($userid)){
	header("Location: index.php");
}
$title_type_arr = ['1' => 'Work Source','2' => 'Service Type'];
$message = '';
if(!empty($_POST['pointssubmit'])){
	if(!empty($_POST['title'])){ 
	   $pagerarray  = array();  
                $pagerarray['title'] = mysqli_real_escape_string($conn,$_POST['title']);
				$pagerarray['title_type'] = mysqli_real_escape_string($conn,$_POST['title_type']); 
				$pagerarray['created_by'] = $userid;
	            $result = insertQuery($pagerarray,'worksource');
				if(!$result){
					header("Location: work-source.php?success=success");
                    } 
				 
		 	}else{
				$message .="Title Field is Empty";
			} 
}
if(!empty($_POST['pointsupdate'])){
	$sql = "update  worksource set title='".$_POST['title']."',title_type='".$_POST['title_type']."' WHERE ID=".$_GET['editworksource']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: work-source.php?success=success");
   
} else {
    echo "Error deleting record: " . $conn->error;
}    
}
if(!empty($_GET['delete'])){
	$sql = "DELETE FROM worksource WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: work-source.php?success=success");
   
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
						<div class="card-header">
							<h4 class="page-title" style="color:#886CC0">Configuration</h4>
						</div>
						<div class="card-body">
							<?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
									Configuration Updated Succussfully
								</div>
							<?php } ?>
							<?php if(!empty($message)){?>
								<div class="alert alert-danger">
									<?=$message?>
								</div>
							<?php } ?> 
							<form method="post" action="">
								<div class="form-group row">
									<label for="example-text-input" class="col-2 col-form-label">Type</label>
                        			<div class="col-4">
                            			<select class="form-control"  required name="title_type" >
											<option value="">Select</option>
											<option value="1">Work Source</option>
											<option value="2">Service Type</option>
										</select>
                        			</div>
									<label for="example-text-input" class="col-2 col-form-label">Title</label>
									<div class="col-4">
										<input class="form-control" type="text" placeholder="Title"  required name="title" id="example-text-input">
									</div> 
                    			</div>
					 			<div class="form-group row">
									<div class="col-4">
										<button type="submit" name="pointssubmit" value="Submit" class="btn btn-primary">Submit</button>
									</div>
                    			</div> 
							</form>
							<div class="table table-responsive">
								<table id="example3" style="min-width: 845px" class="table table-bordered table-hover table-checkable display">
									<thead>
										<tr>
											<th>S.No</th>
											<th>Type</th>
											<th>Title</th>  
											<th>Reg date</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
											$sql = runloopQuery("SELECT * FROM worksource where created_by = '".$userid."' order by ID desc");
											$x=1;  foreach($sql as $row)
											{
										?>
											<tr>
												<td><?php echo  $x;?></td> 
												<td><?php echo $title_type_arr[$row["title_type"]] ?? '' ;?></td>
												<td><?php echo $row["title"];?></td> 
												<td><?php echo reg_date($row["reg_date"]);?></td>
												<td>
													<label class="switch">
												<input type="checkbox" id="dailerstatusid"  <?php if($row['status']=='1'){ echo 'checked';}?> onChange="changeworksourcestatus('worksourcestatus',<?php echo $row['ID'];?>);">
												<span class="slider round"></span>
												</label>
												</td>
												<td>
													<a title="Edit" class="btn btn-primary"  href="?editworksource=<?php echo $row["ID"];?>"><i class="fa fa-pen" aria-hidden="true"></i></a>
													<a title="Delete"  class="btn btn-danger" onclick="return confirm('Are you sure want to delete??');"  aria-label="Delete"  href="?delete=<?php echo $row["ID"];?>">
														<i class="fa fa-trash" aria-hidden="true"></i></a>
												</td>
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
			</div>					
		</div>
    </div>
</div>
<?php include('footer_scripts.php');?>

<?php if(!empty($_GET['editworksource'])){ 
$sql = runQuery("SELECT * FROM worksource where ID = '".$_REQUEST['editworksource']."' order by ID desc");

	?>
		<!-- Modal -->
<div class="modal" id="myModal1" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Work Source</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal">
        </button>
      </div>
<form method="post" action="">
      <div class="modal-body">

					<div class="form-group row">
					<label for="example-text-input" class="col-2 col-form-label">Type</label>
                        <div class="col-4">
                            <select class="form-control"  required name="title_type" >
									<option value="">Select</option>
									<option value="1" <?php if($sql['title_type'] ==  1) echo 'selected';  ?>>Work Source</option>
									<option value="2" <?php if($sql['title_type'] ==  2) echo 'selected';  ?>>Service Type</option>
							</select>
                        </div>
						<label for="example-text-input" class="col-2 col-form-label">Title</label>
                        <div class="col-4">
                            <input class="form-control" type="text" autocomplete="off" placeholder="Title"  value="<?= $sql['title']; ?>" required name="title" id="example-text-input">
                        </div> 
                    </div>

					
				
      </div>
      <div class="modal-footer">
         <button type="submit" name="pointsupdate" value="Submit" class="btn btn-brand btn-elevate btn-pill">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      </form>	
    </div>
  </div>
</div>
	<script>
		$(document).ready(function(){
			$("#myModal1").modal('show');
		})
				
	</script>
<?php }?>
