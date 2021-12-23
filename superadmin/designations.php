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
 
		<!--end::Base Path -->
		<meta charset="utf-8" />
		<title>M3  | Dashboard</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />

		<?php include('headerscripts.php');?>
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--transparent kt-aside--enabled kt-aside--fixed kt-page--loading">

		<!-- begin:: Header Mobile -->
		<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
			<div class="kt-header-mobile__logo">
				<a href="demo1/index.html">
					<img alt="Logo" src="./assets/media/logos/logo-6.png" />
				</a>
			</div>
			<div class="kt-header-mobile__toolbar">
				<button class="kt-header-mobile__toolbar-toggler kt-header-mobile__toolbar-toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
				<button class="kt-header-mobile__toolbar-toggler" id="kt_header_mobile_toggler"><span></span></button>
				<button class="kt-header-mobile__toolbar-topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-more"></i></button>
			</div>
		</div>

		<!-- end:: Header Mobile -->

		<!-- begin:: Root -->
		<div class="kt-grid kt-grid--hor kt-grid--root">

			<!-- begin:: Page -->
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
 
<?php include('header.php');?>
 

				<!-- end:: Aside -->

				<!-- begin:: Wrapper -->
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

					<!-- begin:: Header -->

<?php include('headernav.php');?>
					<!-- end:: Header -->
					<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

					<!-- begin:: Subheader -->

<br>
<!-- end:: Subheader -->

						<!-- begin:: Content -->
				<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
		 
				<div class="kt-portlet kt-portlet--mobile">
					<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<div class="col-12">
								<h3 class="kt-portlet__head-title">Designations</h3>
								</div> 
							</div>
						</div>
						<br>
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
		    <div class="kt-portlet__body">
				<form method="post" action="">
					<div class="form-group row">
				
						<label for="designation_name" class="col-2 col-form-label">Designation Name</label>
                        <div class="col-4">
                            <input class="form-control"  type="text" placeholder="Name"  required name="designation_name" id="designation_name">
                        </div> 


                    </div>

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
                             <button type="submit" name="add_designation_submit" value="Submit" class="btn btn-brand btn-elevate btn-pill">Submit</button>
						</div>
                    </div> 
					</form>	
						<!--begin: Datatable -->
			<table class="table table-striped- table-bordered" >
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
						 
					   $x=1;  foreach($sql as $row)
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

									<!--end: Datatable -->
							</div>
						</div>
					 
						<!-- end:: Content -->
			</div>  

						<!-- end:: Content -->
					

						<!-- end:: Content -->
					</div>

					<!-- begin:: Footer -->
					<div class="kt-footer kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop">
						<div class="kt-footer__copyright">
							2019&nbsp;&copy;&nbsp;<a href="https://sansdigitals.com" target="_blank" class="kt-link">sansdigitals.com</a>
						</div>
					 
					</div>
					<!-- end:: Footer -->
				</div>

				<!-- end:: Wrapper -->
			</div>

			<!-- end:: Page -->
		</div>

		<!-- end:: Root -->

	<?php include("footerscripts.php");?>
		
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
<script>
$("#myModal").modal('show');


</script>

  <?php } ?>
  
  	<?php if(!empty($_GET['editworksource']))
      { 
        $sql = runQuery("SELECT * FROM worksource where ID = '".$_REQUEST['editworksource']."' order by ID desc");

	?>
		<!-- Modal -->
<div class="modal" id="myModal1" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Work Source</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
<form method="post" action="">
      <div class="modal-body">

					<div class="form-group row">
				
						<label for="example-text-input" class="col-2 col-form-label">Title</label>
                        <div class="col-10">
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
			$("#myModal1").modal('show');
		</script>
	<?php }?>

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
	</body>

	<!-- end::Body -->
</html>