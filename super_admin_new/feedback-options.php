<?php
session_start();
error_reporting(E_ALL); 
include('../functions.php');
$userid = current_adminid(); 
if(empty($userid)){
	header("Location: login.php");
} 
$message = '';
if(!empty($_POST['pointssubmit'])){
	if(!empty($_POST['title'])){ 
	   $pagerarray  = array();  
                $pagerarray['title'] = mysqli_real_escape_string($conn,$_POST['title']); 
	            $result = insertQuery($pagerarray,'feedbackoptions');
				if(!$result){
					header("Location: feedback-options.php?success=success");
                    } 
				 
		 	}else{
				$message .="Title Field is Empty";
			} 
}

if(!empty($_GET['delete'])){
	$sql = "DELETE FROM feedbackoptions WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: feedback-options.php?success=success");
   
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
							<h4 class="page-title" style="color:#886CC0">Feedback Options</h4>

							
						</div>
						<div class="card-body">

							 <?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Feedback Options Updated Succussfully
								</div>
								<?php } ?>
								<?php if(!empty($message)){?>
								<div class="alert alert-danger">
								<?=$message?>
								</div>
								<?php } ?> 
							<form method="post" action="">
					<div class="form-group row">
				
						<label for="example-text-input" class="col-2 col-form-label">Title</label>
                        <div class="col-4">
                            <input class="form-control" type="text" placeholder="Title"  name="title" id="example-text-input">
                        </div> 
                    </div>
					 <div class="form-group row">
                       <div class="col-4">
                             <button type="submit" name="pointssubmit" value="Submit" class="btn btn-primary btn-elevate btn-pill">Submit</button>
						</div>
                    </div> 
					</form>	

							<div class="table table-responsive">

								<!--begin: Datatable -->
								<table id="example3" style="min-width: 845px" class="table table-bordered table-hover table-checkable display">
									<thead>
										<tr>
							<th>S.No</th>
                            <th>Title</th>  
							<th>Reg date</th>
							<th>Delete</th>
						</tr>
					</thead>
					<tbody>
						 <?php
						 
							$sql = runloopQuery("SELECT * FROM feedbackoptions order by ID desc");
						 
					   $x=1;  foreach($sql as $row)
							{
					?>
<tr>
<td><?php echo  $x;?></td> 
<td><?php echo $row["title"];?></td> 
<td><?php echo reg_date($row["reg_date"]);?></td> 
<td><a onclick="return confirm('Are you sure want to delete??');"  href="?delete=<?php echo $row["ID"];?>">Delete</a></td>
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
        <!--**********************************
            Content body end
        ***********************************-->


	</div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <?php include('footer_scripts.php');?>


		  <?php if(!empty($_GET['view'])){?>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
<!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
          <form method="post" action="">
		<?php $m3avenue = runQuery("select * from clients where ID = ".$_GET['view']."");?>

		   <div class="form-group">
		    <select class="form-control" name="status">
			 <option value="">Select Status</option>
			 <option value="Accept">Accept</option>
			 <option value="Reject">Reject</option>
			</select>
		   </div>
		   <div class="form-group">
		    <button type="submit" class="btn btn-success" name="submit" value="<?php echo $m3avenue['ID'];?>">Submit</button>
		   </div>
		  </form>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>

</div>
<script>$("#myModal").modal('show');</script>

  <?php } ?>
  <script>	
	 $(document).on('change','.itemready',function(){	
	 var checked = $(this).val();					
	 $.ajax({				
	 url : 'ajax/trackclientstatus.php',		
	 
	 type: "POST",		
	 data: {			
	 checked:checked			
	 },				
	 success: function(res){	
	 /* slience is golden */	
	 }					
	 });			
	 });
	 $(document).on('change','.changestatus',function(){	
	 var checked = $(this).data('clientid');					
	 var changestatus = $(this).val();					
	 $.ajax({				
	 url : 'ajax/trackclientchangestatus.php',		
	 
	 type: "POST",		
	 data: {			
	 checked:checked,			
	 changestatus:changestatus			
	 },				
	 success: function(res){	
	 /* slience is golden */	
	 }					
	 });			
	 });
	 </script>

</body>
</html>