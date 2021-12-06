<?php
session_start();
error_reporting(E_ALL); 
include('../functions.php');
$userid = current_adminid(); 
if(empty($userid)){
	header("Location: login.php");
} 
$message = '';

if(!empty($_POST['pointsubmit'])){
	if(!empty($_POST['title'])){
	if(!empty($_POST['amount'])){
	   $pagerarray  = array();  
                $pagerarray['title'] = mysqli_real_escape_string($conn,$_POST['title']);
                $pagerarray['amount'] = mysqli_real_escape_string($conn,$_POST['amount']);
                $pagerarray['type_of_client'] = mysqli_real_escape_string($conn,$_POST['type_of_client']);
                $pagerarray['executive_share'] = mysqli_real_escape_string($conn,$_POST['executive_share']);
                $pagerarray['manager_share'] = mysqli_real_escape_string($conn,$_POST['manager_share']);
                $pagerarray['r1_share'] = mysqli_real_escape_string($conn,$_POST['r1_share']);
                $pagerarray['r2_share'] = mysqli_real_escape_string($conn,$_POST['r2_share']);
	            $result = insertQuery($pagerarray,'pointset');
				if(!$result){
					header("Location: points-set.php?success=success");
                    } 
				 
		 	}else{
				$message .="Amount Field is Empty";
			}
		 	}else{
				$message .="Title Field is Empty";
			} 
}
if(!empty($_POST['pointsupdate'])){	
    if(!empty($_POST['title'])){
	if(!empty($_POST['amount'])){
	   $pagerarray  = $pagerwherearray = array();  
                $pagerarray['title'] = mysqli_real_escape_string($conn,$_POST['title']);
                $pagerarray['amount'] = mysqli_real_escape_string($conn,$_POST['amount']);
                $pagerarray['type_of_client'] = mysqli_real_escape_string($conn,$_POST['type_of_client']); 
                $pagerarray['executive_share'] = mysqli_real_escape_string($conn,$_POST['executive_share']);
                $pagerarray['manager_share'] = mysqli_real_escape_string($conn,$_POST['manager_share']);
                $pagerarray['r1_share'] = mysqli_real_escape_string($conn,$_POST['r1_share']);
                $pagerarray['r2_share'] = mysqli_real_escape_string($conn,$_POST['r2_share']);
	            $pagerwherearray['ID'] =mysqli_real_escape_string($conn,$_GET['editpointset']); 
	                
                $result = updateQuery($pagerarray,'pointset',$pagerwherearray);
				if(!$result){
					header("Location: points-set.php?success=success");
                    } 
				 
		 	}else{
				$message .="Amount Field is Empty";
			}
		 	}else{
				$message .="Title Field is Empty";
			}     
}
if(!empty($_GET['delete'])){
	$sql = "DELETE FROM pointset WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: points-set.php?success=success");
   
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
							<h4 class="page-title" style="color:#886CC0">Points Set</h4>

							<button type="button" class="btn btn-primary" style="float:right" 
							data-bs-toggle="modal" data-bs-target=".add_employee_form"data-toggle="modal" data-target="#newModal">Add Points Set</button> 

							
						</div>
						<div class="card-body">

							 <?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Points Updated Succussfully
								</div>
								<?php } ?>
								<?php if(!empty($message)){?>
								<div class="alert alert-danger">
								<?=$message?>
								</div>
								<?php } ?> 

							<div class="table table-responsive">

								<!--begin: Datatable -->
								<table id="example3" style="min-width: 845px" class="table table-bordered table-hover table-checkable display">
									<thead>
										<tr>
							<th>S.No</th>
                            <th>Title</th> 
                            <th>Revenue per unit</th>
                            <th>Type Of Client</th>
                            <th>Executive Share</th>
                            <th>Manager Share</th>
                            <th>R1 Share</th>
                            <th>R2 Share</th>
                            <th>Variant</th>
							<th>Reg date</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						 <?php
						 
							$sql = runloopQuery("SELECT * FROM pointset order by ID desc");
						 
					   $x=1;  foreach($sql as $row)
							{
					?>
<tr>
<td><?php echo  $x;?></td> 
<td><?php echo $row["title"];?></td>
<td><?php echo $row["amount"];?></td> 
<td><?php if($row["type_of_client"] == '1') echo 'Direct CLient'; else echo 'Indirect Client';?></td>
<td><?php echo $row["executive_share"]; ?></td>
<td><?php echo $row["manager_share"]; ?></td>
<td><?php echo $row["r1_share"]; ?></td>
<td><?php echo $row["r2_share"]; ?></td>
<td>
<a title="Edit" class="btn btn-primary"  href="points-set-variant.php?pointsid=<?= $row["ID"]; ?>"><i class="fa fa-eye" aria-hidden="true"></i></a>

</td> 
<td><?php echo reg_date($row["reg_date"]);?></td> 
<td>
    <label class="switch">
  <input type="checkbox" id="dailerstatusid"  <?php if($row['status']=='1'){ echo 'checked';}?> onChange="changepointsetstatus('pointsetstatus',<?php echo $row['ID'];?>);">
  <span class="slider round"></span>
</label>
</td>

<td>
    <a title="Edit" class="btn btn-primary"  href="?editpointset=<?php echo $row["ID"];?>"><i class="fa fa-pen" aria-hidden="true"></i></a>
    <a onclick="return confirm('Are you sure want to delete??');" class="btn btn-danger" href="?delete=<?php echo $row["ID"];?>"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
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
  
    	<?php if(!empty($_GET['editpointset'])){ 
$sql = runQuery("SELECT * FROM pointset where ID = '".$_REQUEST['editpointset']."' order by ID desc");

	?>
		<!-- Modal -->
<div class="modal" id="myModal1" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Pointset</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
<form method="post" action="">
      <div class="modal-body">

<div class="form-group row">
				
						<label for="example-text-input" class="col-2 col-form-label">Title</label>
                        <div class="col-4">
                            <input class="form-control" type="text" placeholder="Title" required value="<?= $sql['title']; ?>" name="title" id="example-text-input">
                        </div>
                        <label for="example-text-input" class="col-2 col-form-label">Revenue per unit</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="amount" required value="<?= $sql['amount']; ?>" placeholder="Revenue per unit" id="example-text-input">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Type Of Client</label>
                        <div class="col-4">
                            <select name="type_of_client" class="form-control">
                                <option value="1" <?php if($sql['type_of_client'] == '1') { echo 'selected'; } ?>>Direct Client</option>
                                <option value="2" <?php if($sql['type_of_client'] == '2') { echo 'selected'; } ?>>Indirect Client</option>
                            </select>    
                        </div>
                        
                        <label for="example-text-input" class="col-2 col-form-label">Executive Share</label>
                        <div class="col-4">
                            <input class="form-control" type="text" value="<?= $sql['executive_share']; ?>" name="executive_share"  placeholder="Executive Share" id="example-text-input">
                        </div>
                    </div>
                    <div class="form-group row">
					   <label for="example-text-input" class="col-2 col-form-label">Manager Share</label>
                        <div class="col-4">
                            <input class="form-control" type="text" value="<?= $sql['manager_share']; ?>" name="manager_share"  placeholder="Manager Share" id="example-text-input">
                        </div>
                        <label for="example-text-input" class="col-2 col-form-label">R1 Share</label>
                        <div class="col-4">
                            <input class="form-control" type="text" value="<?= $sql['r1_share']; ?>" name="r1_share"  placeholder="Variant" id="example-text-input">
                        </div>
					</div>
					                    <div class="form-group row">
					   <label for="example-text-input" class="col-2 col-form-label">R2 Share</label>
                        <div class="col-4">
                            <input class="form-control" type="text" value="<?= $sql['r2_share']; ?>" name="r2_share"  placeholder="R2 Share" id="example-text-input">
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
			$("#myModal1").modal('show');
		</script>
	<?php }?>
	<div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Points Set</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <form method="post" action="" id="addform">
					<div class="form-group row">
				
						<label for="example-text-input" class="col-2 col-form-label">Title</label>
                        <div class="col-4">
                            <input class="form-control" type="text" placeholder="Title"  name="title" id="title" required>
                        </div>
                        <label for="example-text-input" class="col-2 col-form-label">Revenue per unit</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="amount"  placeholder="Revenue per unit" id="amount" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Type Of Client</label>
                        <div class="col-4">
                            <select name="type_of_client" id="type_of_client" class="form-control" required> 
                                <option value="1">Direct Client</option>
                                <option value="2">Indirect Client</option>
                            </select>    
                        </div>
                        

                    </div>
                    <div class="form-group row">
					    <label for="example-text-input" class="col-2 col-form-label">Executive Share</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="executive_share"  placeholder="Executive Share" id="executive_share" required>
                        </div>
                        <label for="example-text-input" class="col-2 col-form-label">Manager Share</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="manager_share"  placeholder="Manager Share" id="manager_share" required>
                        </div>
					</div>
					
					   <div class="form-group row">
					   
                        <label for="example-text-input" class="col-2 col-form-label">R1 Share</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="r1_share"  placeholder="R1 Share" id="r1_share" required>
                        </div>
                        <label for="example-text-input" class="col-2 col-form-label">R2 Share</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="r2_share"  placeholder="R2 Share" id="r2_share" required>
                            <input class="form-control" type="hidden" name="pointsubmit"  value="1" id="pointsubmit" >
                            
                        </div>
					</div>
					


					</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addsubmitid">Add Points Set</button>
      </div>
    </div>
  </div>
</div> 

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
	  function changepointsetstatus(name,id){
	    
	        $.ajax({
            type: 'post',
            url: 'ajax/ajax.php',
            data: {
                conditionname: name,
                tableid: id
            },
            success: function(response) { 
			}
        });
     
	}
	$("#addsubmitid").click(function(){
		var user_input_value;
		var err_value = 0
		$('#addform').find('input,select,textarea').each(function(){
			if($(this).prop('required')){
				user_input_value  = $("#"+this.id).val();
				if(user_input_value == ''){
					if(err_value == 0){
						document.getElementById(this.id).focus();
					}
					err_value = err_value + 1;
					$("#"+this.id).css('border-color', 'red');
				}else{
					$("#"+this.id).css('border-color', '#e4e7ea');
				}
			}	 
		});
		$('#addform').find('input[type=file]').each(function(){
			
			if($('#'+this.id)[0].files.length === 0){
				err_value = err_value + 1;
				alert("Please Upload File");
				//$("#"+this.id).css('border','1px solid red','padding','2px');
			}
			else{
				var fileInput = document.getElementById(this.id);
				var filePath = fileInput.value;
				// Allowing file type
				var allowedExtensions = 
						/(\.jpg|\.jpeg|\.png|\.gif)$/i;
				if (!allowedExtensions.exec(filePath)) {
					err_value = err_value + 1;
					alert('Invalid file type');
					$("#"+this.id).css('border','1px solid red','padding','2px');
					fileInput.value = '';
					
				}
				else{
					$("#"+this.id).css('border','1px solid black','padding','2px');
				}

			}
		});	
		if(err_value == 0)
		{
			$("#addsubmitid").hide();
			$("#addform").submit();	
		}
		
	});
	 </script>

</body>
</html>