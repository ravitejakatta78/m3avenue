<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_adminid(); 

if(empty($userid)){

	header("Location: index.php");

}

$message = '';
if(!empty($_POST['modulesubmit']))
{
	if(!empty($_POST['module_name']))
    {
 
	   $pagerarray  = array();  
       $pagerarray['module_name'] = mysqli_real_escape_string($conn,$_POST['module_name']); 
	   $result = insertQuery($pagerarray,'tbl_modules');

	   if(!$result)
       {
			header("Location: module.php?success=success");
        } 
		}
        else{
				$message .="Module name is Empty";
			} 
}

if(!empty($_POST['moduleupdate'])){
	$sql = "update  tbl_modules set module_name='".$_POST['module_name']."' WHERE ID=".$_POST['id']."";

    if ($conn->query($sql) === TRUE) {
        header("Location: module.php?usuccess=success");
    } else {
        echo "Error deleting record: " . $conn->error;
    }    
}

if(!empty($_GET['delete'])){

	$sql = "DELETE FROM tbl_modules WHERE id=".$_GET['delete'];

    if ($conn->query($sql) === TRUE) {
    
    header("Location: module.php?dsuccess=success");
    
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
								<h3 class="kt-portlet__head-title">Modules</h3>
								</div> 
							</div>
						</div>
						<br>
						     <?php if(!empty($_GET['usuccess'])){?>
								<div class="alert alert-success">
								Module Updated Succussfully
								</div>
								<?php } ?>
								<?php if(!empty($_GET['dsuccess'])){?>
								<div class="alert alert-success">
								Module Deleted Succussfully
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
				
						<label for="example-text-input" class="col-2 col-form-label">Module name</label>
                        <div class="col-4">
                            <input class="form-control" type="text" placeholder="Name"  required name="module_name" id="example-text-input">
                        </div> 
                    </div>
					 <div class="form-group row">
                       <div class="col-4">
                             <button type="submit" name="modulesubmit" value="Submit" class="btn btn-brand btn-elevate btn-pill">Submit</button>
						</div>
                    </div> 
					</form>	
						<!--begin: Datatable -->
			<table class="table table-striped- table-bordered" >
					<thead>
						<tr>
							<th>S.No</th>
                            <th>Module name</th>  
							<th>Reg date</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						 <?php
						 
							$sql = runloopQuery("SELECT * FROM tbl_modules order by id desc");
						 
					   $x=1;  foreach($sql as $row)
							{
					?>
<tr>
<td><?php echo  $x;?></td> 
<td><?php echo $row["module_name"];?></td> 
<td><?php echo reg_date($row["reg_date"]);?></td>
<td>
    <label class="switch">
  <input type="checkbox" id="dailerstatusid"  <?php if($row['status']=='1'){ echo 'checked';}?> onChange="changemodulestatus('modulesetstatus',<?php echo $row["ID"];?>);">
  <span class="slider round"></span>
</label>
</td>
<td>
    <a title="Edit" class="btn btn-primary"  href="?editmodule=<?php echo $row["ID"];?>"><i class="fa fa-pen" aria-hidden="true"></i></a>
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
                        <?php $m3avenue = runQuery("select * from clients where id = ".$_GET['view']."");?>

                        <div class="form-group">
                            <select class="form-control" name="status">
                            <option value="">Select Status</option>
                            <option value="Accept">Accept</option>
                            <option value="Reject">Reject</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success" name="submit" value="<?php echo $m3avenue[ID];?>">Submit</button>
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
  
  	<?php if(!empty($_GET['editmodule'])){ 
        $sql = runQuery("SELECT * FROM tbl_modules where ID = '".$_REQUEST['editmodule']."' order by id desc");


	?>
		<!-- Modal -->
<div class="modal" id="myModal1" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Module name</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
<form method="post" action="">
      <div class="modal-body">

					<div class="form-group row">
				
						<label for="example-text-input" class="col-2 col-form-label">Module name</label>
                        <div class="col-10">
                            <input class="form-control" type="text" autocomplete="off" placeholder="Name"  value="<?= $sql['module_name']; ?>" required name="module_name" id="example-text-input">
                            <input type="hidden" name="id" value="<?= $sql['ID']; ?>" />
                        </div> 
                    </div>

					
				
      </div>
      <div class="modal-footer">
         <button type="submit" name="moduleupdate" value="Submit" class="btn btn-brand btn-elevate btn-pill">Update</button>
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
	 $(document).on('change','.modulestatus',function(){	
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
	 
	 function changemodulestatus(name,id){
	    
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
	 
	 </script>
	</body>

	<!-- end::Body -->
</html>

