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
			if(!empty($_POST['fname'])){
	   $pagerarray  = array();

		if (!file_exists('empimage')) {	
		mkdir('empimage', 0777, true);	
		}
	    $target_dir = 'empimage/';									

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
		  if (!file_exists('empimage')) {	
		mkdir('empimage', 0777, true);	
		}
	    $target_dir = 'empimage/';									

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
				$uniqueusers = (int)runQuery("select max(ID) as id from employee order by ID desc")['id'];
				$newuniquid = $uniqueusers+1;
				
				if($_POST['role_id'] == 2)
				{
					$unique_prefix = strtoupper(substr($_POST['fname'],0,2));
				}
				else{
					$sqlleader  = runQuery("select * from employee where unique_id = '".$_POST['leader']."' limit 1");
					$unique_prefix = strtoupper(substr($sqlleader['unique_id'],0,2));
				}
				$pagerarray['role_id'] = mysqli_real_escape_string($conn,$_POST['role_id']) ?? 2;
                $pagerarray['leader'] = mysqli_real_escape_string($conn,$_POST['leader']);
                $pagerarray['fname'] = mysqli_real_escape_string($conn,$_POST['fname']);
                $pagerarray['lname'] = mysqli_real_escape_string($conn,$_POST['lname']);
                $pagerarray['unique_id'] = $unique_prefix.sprintf('%05d',$newuniquid);
                $pagerarray['email'] = mysqli_real_escape_string($conn,$_POST['email']);
                $pagerarray['mobile'] = mysqli_real_escape_string($conn,$_POST['mobile']);
                $pagerarray['password'] = password_hash(trim($_POST['password']),PASSWORD_DEFAULT);
                $pagerarray['bankdetails'] = mysqli_real_escape_string($conn,$_POST['bankdetails']);
                $pagerarray['accntnum'] = mysqli_real_escape_string($conn,$_POST['accntnum']);
                $pagerarray['ifsccode'] = mysqli_real_escape_string($conn,$_POST['ifsccode']);
                $pagerarray['address'] = mysqli_real_escape_string($conn,$_POST['address']);
                $pagerarray['income'] = mysqli_real_escape_string($conn,$_POST['income']);
                $pagerarray['pannum'] = mysqli_real_escape_string($conn,$_POST['pannum']);
                $pagerarray['status'] = '0';
				$result = insertQuery($pagerarray,'employee');
				if(!$result){
					header("Location: employe-list.php?success=success");
                    }
	    
		 	}else{

		$message .=" First Name Field is Empty";

	}

}
if(!empty($_POST['dailerstatusid'])){
    	$sql = "update  employee dialer_status = ".$_POST['dailerstatusid']."  WHERE ID=".$_POST['id']."";
    	echo $sql;exit; 
if ($conn->query($sql) === TRUE) {
   
header("Location: employe-list.php?success=success");
   
} else {
    echo "Error deleting record: " . $conn->error;
}
    
}

if(!empty($_GET['delete'])){
	$sql = "DELETE FROM employee WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: employe-list.php?success=success");
   
} else {
    echo "Error deleting record: " . $conn->error;
}
}
$roles = roles();

$superadmins = runloopQuery("SELECT unique_id,concat(fname,' ',lname) leadername FROM employee where role_id = 2 order by ID desc");
$managers = runloopQuery("SELECT unique_id,concat(fname,' ',lname) leadername FROM employee where role_id = 3 order by ID desc");
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
					    	<h3 class="kt-portlet__head-title">Add Employee</h3>
					    </div>
			    	</div>
                    <div class="kt-portlet__body">
						   <?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Employe-list Added Succussfully
								</div>
								<?php } ?>
								<?php if(!empty($_GET['osuccess'])){?>
							<div class="alert alert-success">
							Offer Letter Generated Succussfully
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
						    
                        <label for="example-text-input" class="col-2 col-form-label">Role</label>
                        <div class="col-3">

                            <select class="form-control"  name="role_id" id="role_id" onchange="rolebasedisplay()" required>
								<option value="">Select</option>
								<?php foreach($roles as $roleid => $rolename) { if ($roleid != '1') {?> 
									<option value="<?= $roleid; ?>"><?= $rolename; ?></option> 
								<?php } } ?>
							</select>
							
                        </div>


                        <label for="example-text-input" id="rolebaselabel" class="col-2 col-form-label" ></label>
                        <div class="col-3" id="rolebaseid" style="display:none">

                            <select class="form-control"  name="leader" id="leader">
								
							</select>
							
                        </div>

						</div>
						<div class="form-group row">
						    
                        <label for="example-text-input" class="col-2 col-form-label">Enter First Name</label>
                        <div class="col-3">
                            <input class="form-control" name="fname" type="text" placeholder="Enter First Name" id="example-text-input" required>
                        </div>
						<label for="example-text-input" class="col-2 col-form-label">Enter Last Name</label>
                        <div class="col-3">
                            <input class="form-control" name="lname" type="text" placeholder="Enter Last Name" id="example-text-input" required>
                        </div>
						</div>
						
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Enter Email</label>
                        <div class="col-3">
                            <input class="form-control" type="text" name="email" placeholder="Enter employee email" id="example-text-input" required>
                        </div>
						<label for="example-text-input" class="col-2 col-form-label">Enter Mobile Number</label>
                        <div class="col-3">
                              <input type="text" class="form-control form-control-line"  onkeypress="return isNumberKey(event)" placeholder="Mobile No"  name="mobile" value=""  required maxlength="10" pattern="\d{10}"  >
                        </div>
                    </div>	
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Password</label>
                        <div class="col-3">
                            <input class="form-control" type="password" name="password" placeholder="Enter Password" id="example-text-input" required>
                        </div>
                        <label  for="example-text-input" class="col-2 col-form-label">CTC</label> 
                        	<div class="col-3">
										<input type="text" name="income" placeholder="Enter CTC"  class="form-control" required>
							</div> 
	
                    </div>
					
					
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Enter PAN Number</label>
                        <div class="col-3">
                            <input class="form-control" type="text" name="pannum" placeholder="Enter PAN Number" id="example-text-input" required>
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
                        <label for="example-text-input" class="col-2 col-form-label">Address</label>
                        <div class="col-11">
						<div class="form-group">
							<textarea class="form-control" name="address" id="exampleTextarea" rows="3" required></textarea>
						</div>

                        </div>
                    
                       <div class="col-3">
                             <button type="submit" name="submit" value="submit" class="btn btn-brand btn-elevate btn-pill">Add Employee</button>
						</div>
                    </div>  
					</form>
					 
						</div>
						</div>
                          
		    <div class="kt-portlet kt-portlet--mobile"> 
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<div class="col-10">
								<h3 class="kt-portlet__head-title">Team List</h3>
								</div> 
								<a href="csv-loanenquires.php?table=employee" class="btn btn-primary pull-right" >Download</a> 
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
							<th>Role</th>
                            <th>Bank details</th>
                            <th>Pan Card</th>
                            <th>Adhaar Card</th>
                            <th>Address</th>
                            <th>Reg date</th>
							<th>Offer Letter</th>
                            <th>Action</th>
							<th>Dailer Status</th>
						</tr>
						
							</thead>
								<tbody>
								  <?php
$sql = runloopQuery("SELECT * FROM employee order by ID desc");

   $x=1;  foreach($sql as $row)
		{
		    			$path = '../offerletter/Offer_letters/'.$row["unique_id"].'_offerletter.pdf';

?>
<tr>
<td><?php echo  $x;?></td>
<td><?php echo $row["unique_id"];?></td>
<td><?php echo $row["fname"];?> <?php echo $row["lname"];?>
<br/><?php echo $row["email"];?>
<br/><?php echo $row["mobile"];?></td>
<td><?php echo $row['role_id'] ? roles($row['role_id']) : 'No Role Assigned'; ?></td>

<td><?php echo $row["bankdetails"];?>
<br/><?php echo $row["accntnum"];?>
<br/><?php echo $row["ifsccode"];?></td>
<td><?php if($row["panimg"]){?><a href="empimage/<?php echo $row["panimg"];?>" class="html5lightbox" >View</a><?php }?></td>
<td><?php if($row["adhaarimg"]){?><a href="empimage/<?php echo $row["adhaarimg"];?>" class="html5lightbox" >View</a><?php }?></td> 
<td><?php echo $row["address"];?></td>
<td><?php echo reg_date($row["reg_date"]);?></td>
<td>	<?php if(file_exists($path)){
	echo "Generated";
	?>

<?php } else { ?>
<a class="btn btn-info" href="../offerletter/offerletter.php?id=<?php echo $row['ID']?>">Generate</a>
<?php } ?>
<br>
<?php echo "Requested By:".lead_details($row["leader"],'fname'); ?>
</td>
<td><a href="edit-employee-list.php?edit=<?php echo $row["ID"];?>">Edit</a> | <a onclick="return confirm('Are you sure want to delete??');"  href="?delete=<?php echo $row["ID"];?>">Delete</a></td>
<td>
    <label class="switch">
  <input type="checkbox" id="dailerstatusid"  <?php if($row['dialer_status']=='1'){ echo 'checked';}?> onChange="changedialerstatus('dailerstatus',<?php echo $row['ID'];?>);">
  <span class="slider round"></span>
</label>

    
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
	<script>
	function rolebasedisplay()
	{
		$("#rolebaselabel").html('');

		var role_id = $("#role_id").val();
		if(role_id == '3') {
			$("#rolebaseid").show();
			var leaders = '<?= json_encode($superadmins) ; ?>';
			var label = 'Super Admin';
		}
		else if(role_id == '4') {
			$("#rolebaseid").show();
			var leaders = '<?= json_encode($managers) ; ?>';
			var label = 'Managers';
		}
		else {
			$("#rolebaseid").hide();
			return false;
		}
		$("#rolebaselabel").html(label);
		var leaderarray =JSON.parse(leaders);
		$("#leader").html('');
		$("#leader").append(`<option value = ''>Select</option>`);
		for(var i=0;i<leaderarray.length; i++) { 
			$("#leader").append(`<option value = "${leaderarray[i]['unique_id']}">${leaderarray[i]['leadername']}</option>`)
		}
	}
	function changedialerstatus(name,id){
	    
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