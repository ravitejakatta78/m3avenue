<?php

session_start();

error_reporting(E_ALL);


include('../functions.php');


$userid = current_adminid(); 

if(empty($userid)){
	header("Location: index.php");
}

$message = '';
if(!empty($_POST['entity_name']))
{
	$pagerarray  = array();

	if (!file_exists('../uploads/entity_logo')) {	
		mkdir('../uploads/entity_logo', 0777, true);	
	}
	$target_dir = '../uploads/entity_logo/';
	
	$file = $_FILES["entity_logo"]['name'];
	$imageFileType = pathinfo($file,PATHINFO_EXTENSION);	
	$convertedfile = strtolower(base_convert(time(), 10, 36) . '_' . md5(microtime())).'.'.$imageFileType;				
	$target_file = $target_dir . strtolower($convertedfile);		

	$uploadOk = 1;
	
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" )
	{
		$message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
		$uploadOk = 0;						
	}
	if ($uploadOk == 0) {			
		$message .= "Sorry, your file was not uploaded.";		
	} else {
		if (move_uploaded_file($_FILES["entity_logo"]["tmp_name"], $target_file)){				
			$pagerarray['entity_logo'] = strtolower($convertedfile);						
		} else {
			$message .= "Sorry, There Was an Error Uploading Your File.";			
		}
	}

	$pagerarray['entity_name'] = mysqli_real_escape_string($conn,$_POST['entity_name']);
	$pagerarray['created_by'] = $userid;
	$pagerarray['entity_type'] = mysqli_real_escape_string($conn,$_POST['entity_type']);
	
	if(!empty($_POST['worksource_type'])){
		$worksource_string =	implode(",",$_POST['worksource_type']);
		$pagerarray['worksource_type'] = $worksource_string;
	}
	$result = insertIDQuery($pagerarray,'entity');
	if(!empty($result)){

			if(!empty($_POST['location'])){
				$locationarray = [];
				$locations = $_POST['location'];
				for($p=0;$p<count($locations);$p++){
					$locationarray['entity_id'] =  $result;
					$locationarray['location'] =  $locations[$p];
					$locationarray['created_by'] = $userid;
					$res = insertQuery($locationarray,'entity_location');
					
				}
	
			}
	
		

		header("Location: business-entity.php?success=success");
	}

}	
else{
	$message .=" Entity Name Field is Empty";
}

$entity = runloopQuery("select * from entity  where created_by = '".$userid."'");

$servicetypes = runloopQuery("select * from worksource  where created_by = '".$userid."' and  title_type = 2");

$entity_type_arr = ['1' => 'Direct Client','2' => 'Indirect Client'];
?>
<!DOCTYPE html>
<html lang="en">
<!-- begin::Head -->
<head>
	<!--begin::Base Path (base relative path for assets of this page) -->
	<!--end::Base Path -->
	<meta charset="utf-8" />
	<title>M3  | Dashboard</title>
	<meta name="description" content="Latest updates and statistic charts">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />

	<?php include('headerscripts.php');?>
<style>
	.modal-body .col-6{
		margin-bottom: 20px;
	}
	.select2-container--default .select2-selection--multiple {
    border: 1px solid #e1e5eb;
}
.select2-container .select2-selection--multiple {
    min-height: 38px;
}

.select2-container--default .select2-search--inline .select2-search__field {
	min-width : 100px;
}
</style>	
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
						<div class="kt-portlet__head row">
							<div class="kt-portlet__head-label col-lg-3 col-md-4 col-sm-4 col-xs-12">
								<h3 class="kt-portlet__head-title">Entity List</h3>
							</div>
							<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
								<!-- <a href="add_super_admin.php"><button type="button" class="btn  btn btn-primary  mt-2" style="float:right" data-toggle="modal" data-target="#exampleModalScrollable">Add Super Admin</button></a> -->   
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

								<button type="button" class="btn  btn btn-primary  mt-2" style="float:right" data-toggle="modal" data-target="#exampleModalScrollable">Add Entity</button>


							</div>
							<div class="kt-portlet kt-portlet--mobile">


								<div class="kt-portlet__body">
									<div class="kt-portlet kt-portlet--mobile"> 
										<div class="kt-portlet__head">
											<div class="kt-portlet__head-label">
									
											</div>
										</div>     
										<div class="kt-portlet__body">
										<?php if(!empty($_GET['success'])){?>
											<div class="alert alert-success">
												Employe-list Added Succussfully
											</div>
										<?php } ?>
											<div class="table table-responsive">

												<!--begin: Datatable -->
												<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
													<thead>
														<tr>
															<th>S.No</th>
															<th>Entity Name</th>
															<th>Logo</th>
															<th>Type</th>
														</tr>
													</thead>
													<tbody>
													<?php for($i=0;$i<count($entity);$i++){?>
														<tr>
															<td><?= $i+1; ?></td>
															<td><?= $entity[$i]['entity_name']; ?></td>
															<td><img style="height:100px;weight:100px" src="../uploads/entity_logo/<?= $entity[$i]['entity_logo']; ?>"></td>	
															<td><?= @$entity_type_arr[$entity[$i]['entity_type']]; ?></td>
														</tr>
													<?php } ?>	
													</tbody>
												</table>
												<!--end: Datatable -->
											</div>
										</div>

									</div>
								</div>


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

				<!-- Modal -->


				<!-- Modal -->
<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable  w-100 modal-lg"  role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalScrollableTitle">Add Entity</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="" enctype="multipart/form-data" id="addentityform" autocomplete="off">
					<div class="row">
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Entity Name</label>
							<input class="form-control" name="entity_name" type="text" placeholder="Enter Entity Name" id="entity_name" required>
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Logo</label>
							<input class="form-control" name="entity_logo" type="file"  id="entity_logo" required>
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Type</label>
			
							<select class="js-example-basic-multiple form-control" name="worksource_type[]" id="worksource_type" multiple="multiple">
							<?php for($i=0;$i<count($servicetypes);$i++){ ?>
								<option value="<?= $servicetypes[$i]['ID']; ?>" ><?= $servicetypes[$i]['title']; ?></opiton>
								<?php } ?>
								  </select>
						</div>
						<div class="col-6">    
							<label for="example-text-input" class="font-weight-bold">Preferred Work Type</label>
							<select class="form-control" name="entity_type" id="entity_type" required>
														<option value="">Select</option>
														<option value="1">Direct Client</option>
														<option value="2">Indirect Client</option>
							</select>
						</div>
					</div>
					<table id="tblAddRow" class="table table-bordered table-striped">
				<thead>
					<tr>
					    <th>Location</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<tr>
					    <td>
						<input type="text" name="location[]" class="form-control">
						</td>
					</tr>
				</tbody>
			</table>	
				</form>
			</div>
			<div class="modal-footer">
			<button id="btnAddRow" class="btn btn-success" type="button">Add Row</button>

				<button type="submit" name="submit" value="submit" id="addentitysubmitid" class="btn btn-primary">Add Entity</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script>
$("#addentitysubmitid").click(function(){
        	var user_input_value;
        	var err_value = 0
        	$('#addentityform').find('input').each(function(){
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
        	$('#addentityform').find('input[type=file]').each(function(){

        		if($('#'+this.id)[0].files.length === 0){
        			err_value = err_value + 1;
        			//alert("Please Upload File");
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
        		$("#addentitysubmitid").hide();
        		$("#addentityform").submit();	
        	}

        });
		$('#tblAddRow tbody tr')
    .find('td')
    //.append('<input type="button" value="Delete" class="del"/>')
    .parent() //traversing to 'tr' Element
    .append('<td><a href="#" class="delrow"><i class="fa fa-trash border-red text-red"></i></a></td>');


// Add row the table
$('#btnAddRow').on('click', function() {
    var lastRow = $('#tblAddRow tbody tr:last').html();
    //alert(lastRow);
    $('#tblAddRow tbody').append('<tr>' + lastRow + '</tr>');
    $('#tblAddRow tbody tr:last input').val('');
});
// Delete row on click in the table
$('#tblAddRow').on('click', 'tr a', function(e) {
    var lenRow = $('#tblAddRow tbody tr').length;
    e.preventDefault();
    if (lenRow == 1 || lenRow <= 1) {
        alert("Can't remove all row!");
    } else {
        $(this).parents('tr').remove();
    }
});
$(document).ready(function() {

	$(".js-example-basic-multiple").select2(); 
});
</script>
</body>

<!-- end::Body -->
</html>