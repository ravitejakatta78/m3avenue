<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_adminid(); 

if(empty($userid)){

	header("Location: index.php");

}

$message = '';
if(!empty($_POST['submit'])){
			if(!empty($_POST['name'])){
	   $pagerarray  = array();

		  if (!file_exists('managerimage')) {	
		mkdir('managerimage', 0777, true);	
		}
	    $target_dir = 'managerimage/';									

		$file = $_FILES["panimg"]['name'];				
		$target_file = $target_dir . strtolower($file);		

		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);								

		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" )

			{
		$message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
		$uploadOk = 0;						
		}
	    if ($uploadOk == 0) {			
		$message .= "Sorry, your file was not uploaded.";		
		} else {
			if (move_uploaded_file($_FILES["panimg"]["tmp_name"], $target_file)){				
				$pagerarray['panimg'] = strtolower($file);						
				} else {
					$message .= "Sorry, There Was an Error Uploading Your File.";			
					}
				}
				//adhaarimg
		  if (!file_exists('managerimage')) {	
		mkdir('managerimage', 0777, true);	
		}
	    $target_dir = 'managerimage/';									

		$file = $_FILES["adhaarimg"]['name'];				
		$target_file = $target_dir . strtolower($file);		

		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);								

		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" )

			{
		$message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
		$uploadOk = 0;						
		}
	    if ($uploadOk == 0) {			
		$message .= "Sorry, your file was not uploaded.";		
		} else {
			if (move_uploaded_file($_FILES["adhaarimg"]["tmp_name"], $target_file)){				
				$pagerarray['adhaarimg'] = strtolower($file);						
				} else {
					$message .= "Sorry, There Was an Error Uploading Your File.";			
					}
				}
				$uniqueusers = (int)runQuery("select max(ID) as id from manager order by ID desc")['id'];
				$newuniquid = $uniqueusers+1;
				 
                $pagerarray['name'] = mysqli_real_escape_string($conn,$_POST['name']); 
                $pagerarray['unique_id'] = 'M3B'.sprintf('%05d',$newuniquid);
                $pagerarray['email'] = mysqli_real_escape_string($conn,$_POST['email']);
                $pagerarray['mobile'] = mysqli_real_escape_string($conn,$_POST['mobile']);
                $pagerarray['password'] = password_hash(trim($_POST['password']),PASSWORD_DEFAULT);
                $pagerarray['bankdetails'] = mysqli_real_escape_string($conn,$_POST['bankdetails']);
                $pagerarray['accntnum'] = mysqli_real_escape_string($conn,$_POST['accntnum']);
                $pagerarray['ifsccode'] = mysqli_real_escape_string($conn,$_POST['ifsccode']); 
                $pagerarray['location'] = mysqli_real_escape_string($conn,$_POST['location']); 
                $pagerarray['branchname'] = mysqli_real_escape_string($conn,$_POST['branchname']); 
                $pagerarray['status'] = 1; 
	            $result = insertQuery($pagerarray,'manager');
				if(!$result){
					header("Location: manager-list.php?success=success");
                    }
	    
		 	}else{

		$message .=" First Name Field is Empty";

	}

}

if(!empty($_GET['delete'])){
	$sql = "DELETE FROM manager WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: manager-list.php?success=success");
   
} else {
    echo "Error deleting record: " . $conn->error;
}
}
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
					    	<h3 class="kt-portlet__head-title">Add Manager</h3>
					    </div>
			    	</div>
                    <div class="kt-portlet__body">
						   <?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Manager Added Succussfully
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
						<div class="form-group row">
						    
                        <label for="example-text-input" class="col-2 col-form-label">Enter Name</label>
                        <div class="col-3">
                            <input class="form-control" name="name" type="text" placeholder="Enter Name" id="example-text-input">
                        </div> 
                        <label for="example-text-input" class="col-2 col-form-label">Enter Email</label>
                        <div class="col-3">
                            <input class="form-control" type="text" name="email" placeholder="Enter manager email" id="example-text-input">
                        </div>
						</div>
						
					<div class="form-group row">
						<label for="example-text-input" class="col-2 col-form-label">Enter Mobile Number</label>
                        <div class="col-3">
                              <input type="text" class="form-control form-control-line"  onkeypress="return isNumberKey(event)" placeholder="Mobile No"  name="mobile" value=""  required maxlength="10" pattern="\d{10}"  >
                        </div>
                        <label for="example-text-input" class="col-2 col-form-label">Password</label>
                        <div class="col-3">
                            <input class="form-control" type="password" name="password" placeholder="Enter Password" id="example-text-input">
                        </div>   
                    </div>	 
					
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Enter Bank Details</label>
                        <div class="col-3">
                            <input class="form-control" type="text" name="bankdetails" placeholder="Enter Bank Name" id="example-text-input">
                        </div>
                        <div class="col-3">
                            <input class="form-control" type="text" name="accntnum" placeholder="Enter Account Number" id="example-text-input">
                        </div>
						<div class="col-3">
                            <input class="form-control" type="text" name="ifsccode" placeholder="Enter IFSC Code" id="example-text-input">
                        </div>
                    </div>
					
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Upload PAN Card</label>
                        <div class="col-3">
							<div class="custom-file">
                                <input type="file" class="custom-file-input" name="panimg" id="customFile">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
						</div>
						
						<label for="example-text-input" class="col-2 col-form-label">Upload ADHAAR Card</label>
                        <div class="col-3">
							<div class="custom-file">
                                <input type="file" class="custom-file-input" name="adhaarimg" id="customFile">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
						</div>
                    </div>
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Location</label>
                        <div class="col-3">
                            <input class="form-control" type="text" name="location"     placeholder="Enter location" id="example-text-input">
                        </div>
                        <label for="example-text-input" class="col-2 col-form-label">Branch name</label>
                        <div class="col-3">
                            <input class="form-control" type="text" name="branchname"   placeholder="Enter branchname" id="example-text-input">
                        </div> 
                    </div>
					
					 <div class="form-group row"> 
                    
                       <div class="col-3">
                             <button type="submit" name="submit" value="submit" class="btn btn-brand btn-elevate btn-pill">Add Manager</button>
						</div>
                    </div>  
					</form>
					</form>
					 
						</div>
						</div>
                          
		    <div class="kt-portlet kt-portlet--mobile"> 
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<div class="col-10">
								<h3 class="kt-portlet__head-title">Team List</h3>
								</div> 
								<a href="csv-loanenquires.php?table=manager" class="btn btn-primary pull-right" >Download</a> 
							</div>
						</div>     
		<div class="kt-portlet__body">
		<div class="table table-responsive">

						<!--begin: Datatable -->
			<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>S.No</th>
							<th>EMP Id</th>
                            <th>Full Name</th> 
                            <th>Bank details</th>
                            <th>Pan Card</th>
                            <th>Adhaar Card</th>
                            <th>Location</th>
                            <th>Branch name</th>
                            <th>Status</th>
                            <th>Reg date</th>
                            <th>Delete</th>
							<!--<th>Status:</th>-->
						</tr>
						
							</thead>
								<tbody>
								  <?php
$sql = runloopQuery("SELECT * FROM manager order by ID desc");

   $x=1;  foreach($sql as $row)
		{
?>
<tr>
<td><?php echo  $x;?></td>
<td><?php echo $row["unique_id"];?></td>
<td><?php echo $row["name"];?>
<br/><?php echo $row["email"];?>
<br/><?php echo $row["mobile"];?></td>
<td><?php echo $row["bankdetails"];?>
<br/><?php echo $row["accntnum"];?>
<br/><?php echo $row["ifsccode"];?></td>
<td><?php if($row["panimg"]){?><a href="managerimage/<?php echo $row["panimg"];?>" class="html5lightbox" >View</a><?php }?></td>
<td><?php if($row["adhaarimg"]){?><a href="managerimage/<?php echo $row["adhaarimg"];?>" class="html5lightbox" >View</a><?php }?></td> 
<td><?php echo $row["location"];?></td>
<td><?php echo $row["branchname"];?></td>
 <td><label class="switch">
	  <input type="checkbox" <?php if($row['status']=='1'){ echo 'checked';}?> onChange="changestatus('manager',<?php echo $row['ID'];?>);">
	  <span class="slider round"></span>
	</label>
</td>
<td><?php echo reg_date($row["reg_date"]);?></td>
<td><a href="edit-manager-list.php?edit=<?php echo $row["ID"];?>">Edit</a> | <a onclick="return confirm('Are you sure want to delete??');"  href="?delete=<?php echo $row["ID"];?>">Delete</a></td>
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
	</body>

	<!-- end::Body -->
</html>