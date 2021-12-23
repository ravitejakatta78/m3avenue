<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');

$userid = current_adminid(); 
if(empty($userid)){
	header("Location: index.php");
}
$roles = roles();
$message = '';
if(!empty($_POST['add_designation_submit']))
{
	if(!empty($_POST['designation_name']))
    { 
        if(empty($_POST['designation_id']))
        { 
	            $pagerarray  = array();  
                $pagerarray['designation_name'] = mysqli_real_escape_string($conn,$_POST['designation_name']); 
                $pagerarray['role_under'] = mysqli_real_escape_string($conn,$_POST['role_id']); 
	            $result = insertQuery($pagerarray,'tbl_designations');
				if(!$result){
					header("Location: designations.php?success=success");
                }
        } 
        else
        {
            $sql = "update  tbl_designations set designation_name='".$_POST['designation_name']."',role_under='".$_POST['role_id']."' WHERE ID=".$_POST['designation_id']."";
            if ($conn->query($sql) === TRUE) {   
                header("Location: designations.php?esuccess=success"); 
            } 
            else {
                echo "Error deleting record: " . $conn->error;
            }   
        }				 
    }else{
        $message .="Designation Field is Empty";
    } 
}
if(!empty($_POST['pointsupdate'])){
	$sql = "update  tbl_designations set designation_name='".$_POST['designation_name']."',role_under='".$_POST['role_id']."' WHERE ID=".$_GET['editworksource']."";

    if ($conn->query($sql) === TRUE) {   
        header("Location: designations.php?esuccess=success"); 
    } 
    else {
        echo "Error deleting record: " . $conn->error;
    }    
}
if(!empty($_GET['delete'])){
	$sql = "DELETE FROM tbl_designations WHERE ID=".$_GET['delete']."";

    if ($conn->query($sql) === TRUE) {
    
    header("Location: designations.php?dsuccess=success");
    
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
								Designation Added Succussfully
								</div>
							<?php } ?>
                            <?php if(!empty($_GET['esuccess'])){?>
								<div class="alert alert-success">
								Designation Updated Succussfully
								</div>
							<?php } ?>
                            <?php if(!empty($_GET['dsuccess'])){?>
								<div class="alert alert-success">
								Designation Deleted Succussfully
								</div>
							<?php } ?>
							<?php if(!empty($message)){?>
								<div class="alert alert-danger">
								<?=$message?>
								</div>
							<?php } ?>
							<form method="post" action="">
								<div class="form-group row">
									<label for="designation_name" class="col-2 col-form-label">Designation Name</label>
                        			<div class="col-4">
                            			<input class="form-control"  type="text" placeholder="Name"  required name="designation_name" id="designation_name">
                        			</div> 
			                    </div>
								<br/>
							    <div class="form-group row">
									<label for="example-text-input" class="col-2 col-form-label">Role</label>
									<div class="col-4">
									<select class="form-control" id="designation_role_under"  name="role_id"  onchange="rolebasedisplay()" required>
												<option value="">Select</option>
												<?php foreach($roles as $roleid => $rolename) { if ($roleid != '1' && $roleid != '2' && $roleid != '') {?> 
													<option value="<?= $roleid; ?>"><?= $rolename; ?></option> 
												<?php } } ?>
									</select>
									</div>
                    				<input type="hidden" id="designation_id" name="designation_id" value="" />
                    			</div>
					 			<div class="form-group row">
									<div class="col-4">
										<button type="submit" name="add_designation_submit" value="Submit" class="btn btn-primary">Submit</button>
									</div>
                    			</div> 
							</form>
							<div class="table table-responsive">
								<table id="example3" style="min-width: 845px" class="table table-bordered table-hover table-checkable display">
								<thead>
						<tr>
							<th>S.No</th>
                            <th>Designation Name</th>
                            <th>Role Under</th>  
							<th>Reg date</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						 <?php
							$sql = runloopQuery("SELECT * FROM tbl_designations order by ID desc");
						   $x=1;  
						   foreach($sql as $row)
							{
						?>
                        <tr>
							<td><?php echo  $x;?></td> 
							<td><?php echo $row["designation_name"];?></td> 
							<td><?php echo $row['role_under'] ? roles($row['role_under']) : 'No Role Assigned'; ?></td>
							<td><?php echo reg_date($row["reg_date"]);?></td>
							<td>
								<label class="switch">
							<input type="checkbox" id="dailerstatusid"  <?php if($row['status']=='1'){ echo 'checked';}?> onChange="changedesignationstatus('designation_status',<?php echo $row['ID'];?>);">
							<span class="slider round"></span>
							</label>
							</td>
							<td>
								<a title="Edit" class="btn btn-primary edit_designation" data-name="<?php echo $row["designation_name"];?>"  data-id="<?php echo $row["ID"];?>" data-role="<?php echo $row["role_under"];?>" href="javascript:void(0)"><i class="fa fa-pen" aria-hidden="true"></i></a>
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
<script>
function changedesignationstatus(name,id){
	    
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

$('.edit_designation').on('click',function(){
	var id=$(this).data('id');
	var name=$(this).data('name');
	var role=$(this).data('role');

	$('#designation_name').val(name);
	$('#designation_id').val(id);
	$("#designation_role_under").val(role).attr("selected","selected");
});
</script>

