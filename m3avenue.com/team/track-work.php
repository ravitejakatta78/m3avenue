<?php
session_start(); 
error_reporting(0);
include('../functions.php');
$userid = current_employeeid();
if(empty($userid)){
	header("Location:index.php");
	}
$usedetails = runQuery("select * from employee where ID = '".$userid."'");
$message = '';

//if(!empty($_POST['submit'])){
			if(!empty($_POST['clientname'])){
	   $pagerarray  = array(); 
	   $processform = true;
               $assingedto = mysqli_real_escape_string($conn,$_POST['assingedto']);
			   if(!empty($assingedto)){
				$prevuser = runQuery("select * from employee where unique_id = '".$assingedto."'");
				if(empty($prevuser)){ 
					$processform = false;
					$invalidme = "Empoyee id does not exists";
				}
			   }
			   if($processform){
			       		$maxleadtrack = runQuery("select MAX(ID) as id from track_work");
		                $maxid = @$maxleadtrack['id']+1;

					$pagerarray['employee_id'] = $userid;
					$pagerarray['lead_id'] = "M3A".$usedetails['unique_id'].'L'.$maxid;
					$pagerarray['clientname'] = mysqli_real_escape_string($conn,$_POST['clientname']);
					$pagerarray['selecttype'] = mysqli_real_escape_string($conn,$_POST['selecttype']);
					$pagerarray['mobile'] = mysqli_real_escape_string($conn,$_POST['mobile']); 
					$pagerarray['email'] = mysqli_real_escape_string($conn,$_POST['email']); 
					$pagerarray['amount'] = mysqli_real_escape_string($conn,$_POST['amount']);
					$pagerarray['address'] = mysqli_real_escape_string($conn,$_POST['address']);
					$pagerarray['remark'] = mysqli_real_escape_string($conn,$_POST['remark']);
					$pagerarray['company'] = mysqli_real_escape_string($conn,$_POST['company']);
					$pagerarray['income'] = mysqli_real_escape_string($conn,$_POST['income']);
					$pagerarray['assingedto'] = $assingedto;
					$pagerarray['source'] = mysqli_real_escape_string($conn,$_POST['source']);
					$pagerarray['followup'] = date('Y-m-d',strtotime($_POST['followup']));
					$pagerarray['status'] = 'Yes';
					$result = insertQuery($pagerarray,'track_work');
					if(!$result){
						header("Location: track-work.php?success=success");
						}
				
		 	}else{

		$message .= $invalidme;

	}
		 	}
// 		 	else{
// 				$message .="  Lead Name  Field is Empty";
// 			}

// }



if(!empty($_POST['updatelead'])){


			if(!empty($_POST['clientname'])){
	   $pagerarray  = array(); 
	   $processform = true;
               $assingedto = mysqli_real_escape_string($conn,$_POST['assingedto']);
			   if(!empty($assingedto)){
				$prevuser = runQuery("select * from employee where unique_id = '".$assingedto."'");
				if(empty($prevuser)){ 
					$processform = false;
					$invalidme = "Empoyee id does not exists";
				}
			   }
			   if($processform){
			                      $pagewererarray['ID'] = $_GET['editlead'];  

                	$pagerarray['clientname'] = mysqli_real_escape_string($conn,$_POST['clientname']);
					$pagerarray['selecttype'] = mysqli_real_escape_string($conn,$_POST['selecttype']);
					//$pagerarray['mobile'] = mysqli_real_escape_string($conn,$_POST['mobile']); 
					$pagerarray['email'] = mysqli_real_escape_string($conn,$_POST['email']); 
					$pagerarray['amount'] = mysqli_real_escape_string($conn,$_POST['amount']);
					$pagerarray['address'] = mysqli_real_escape_string($conn,$_POST['address']);
					$pagerarray['remark'] = mysqli_real_escape_string($conn,$_POST['remark']);
					$pagerarray['company'] = mysqli_real_escape_string($conn,$_POST['company']);
					$pagerarray['income'] = mysqli_real_escape_string($conn,$_POST['income']);
					$pagerarray['assingedto'] = $assingedto;
					$pagerarray['source'] = mysqli_real_escape_string($conn,$_POST['source']);
					$pagerarray['followup'] = date('Y-m-d',strtotime($_POST['followup']));
					$pagerarray['additional_number'] = mysqli_real_escape_string($conn,$_POST['additional_number']);
					$pagerarray['status'] = 'Yes';
				
		            $result = updateQuery($pagerarray,'track_work',$pagewererarray);

					if(!$result){
						header("Location: track-work.php?success=success");
						}
				
		 	}else{

		$message .= $invalidme;

	}
		 	}else{

		$message .=" Lead Name Field is Empty";

	}

}


if(!empty($_GET['delete'])){
	$sql = "DELETE FROM track_work WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: track-work.php?success=success");
   
} else {
    echo "Error deleting record: " . $conn->error;
}
}
if(!empty($_GET['status'])){
	$statuscontent = $_GET['statuscontent']=='Yes' ? 'No' : 'Yes';
	$sql = "UPDATE track_work set status = '".$statuscontent."' WHERE ID=".$_GET['status']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: track-work.php?statusupdate=success");
   
} else {
    echo "Error deleting record: " . $conn->error;
}
}
if(!empty($_POST['remarksubmit']) && !empty($_POST['remark'])){
	$statuscontent = mysqli_real_escape_string($conn,$_POST['remark']);
	
	$pagerarray['track_work_id'] = $_POST['remarksubmit'];
	$pagerarray['employee_id'] = $userid;
	$pagerarray['remark_desc'] = $statuscontent;
	$result = insertQuery($pagerarray,'remarks_history');
		$followup = date('Y-m-d',strtotime($_POST['followup']));
	$sql = "UPDATE track_work set remark = '".$statuscontent."',followup = '".$followup."' WHERE ID=".$_POST['remarksubmit']."";
	$conn->query($sql);
	if(!$result){
			header("Location: track-work.php?success=success");
	}
}


if(!empty($_POST['uploaddata'])){
    $uploadtrackwork = @$_POST['uploadtrackwork'] ?? @$_POST['uploadtrackworkdup'];
    if(!empty(array_filter($_FILES['uploadedfile']['name']))) {
		foreach ($_FILES['uploadedfile']['tmp_name'] as $key => $value) {
			$file_tmpname = $_FILES['uploadedfile']['tmp_name'][$key];
            $file_name = $_FILES['uploadedfile']['name'][$key];
            $file_size = $_FILES['uploadedfile']['size'][$key];
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);		
			$newname = date('YmdHis',time()).mt_rand().'.'.$file_ext;
			$path = './../upload_documents/'.$uploadtrackwork;
			if (!is_dir($path)) {
				mkdir($path, 0777, true);
			}

			move_uploaded_file($file_tmpname,$path.'/'.$newname);
			$trackerdocarray['file_name'] = $_POST['uploadedfilename'][$key];
			$trackerdocarray['doc_name'] = $newname;
			$trackerdocarray['employee_id'] = $userid;
			$trackerdocarray['track_work_id'] = $uploadtrackwork;
			$trackerdocarray['reg_date'] = date('Y-m-d');
			$trackerdocarray['created_on'] = date('Y-m-d H:i:s A');
			$result = insertQuery($trackerdocarray,'track_work_documents');
		}
	}

	$sql = "UPDATE track_work set doc_status = '1' WHERE ID=".$_POST['uploadtrackwork']."";

	if ($conn->query($sql) === TRUE) {
	   
	header("Location: track-work.php?statusupdate=success");
	   
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../plugins/images/favicon.png">
<style>
.tablestyle {
      border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;

}

.icon-border {
    padding : 8px;
    border : 1px solid black;
}

</style>
    <title>M3 Dashboard</title>
    
   <?php include("headerscripts.php");?>
</head>

<body>
    <!-- Preloader -->
   
    <div id="wrapper">
        <!-- Navigation -->
               <?php include('header.php');?>
        <!-- Left navbar-header end -->
		
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">TRACK MY LEAD</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
					<ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li class="active">TRACK  My Lead</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div> 
                 <button type="button" class="btn btn-brand btn-success btn-pill " style="margin-top:-25px ; margin-bottom: 10px;float:right" data-toggle="modal" data-target="#newModal">Add Client</button> 
                <!-- /row -->
                	<?php if(!empty($_GET['statusupdate']) || !empty($_GET['success']) || !empty($message)){ ?>
                <div class="row" id="temp">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title">Track  My Lead</h3>
                            <div class="table-responsive">
                             
						
							<div class="white-box">
								<? if(!empty($_GET['statusupdate'])){?>

									
									<div class="alert alert-success alert-dismissible " role="alert" >
  <strong>Status updated Successfully</strong> 
  <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="$('#temp').remove();">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

								<? }?>
                          <? if(!empty($_GET['success'])){?>

								
								<div class="alert alert-success alert-dismissible " role="alert" >
  <strong>Track Work Details Added Successfully</strong> 
  <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="$('#temp').remove();">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

								<? }?>

								<? if(!empty($message)){?>

								<div class="alert alert-danger alert-dismissible " role="alert">
  <strong><?=$message;?></strong> 
  <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="$('#temp').remove();">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

								<? }?>

				  
                                </div>
                              
                                
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
            <?php } ?>
                <!-- /.row -->
                
                <!-- /row -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title">Track My Work</h3>
                            <div class="table-responsive">
                                
                                <table class="table" id="kt_table_1" >
                                    <thead>
                                        <tr>
											<th>S.NO</th>
											<th>Lead Number</th>
													<th>Lead Name</th>
												<th>Service Type</th>
												<th>Mobile No</th>
												<th>Email Id</th>
												<th>Amount</th>
												<th>Address</th>
											<!--	<th>Remark</th> -->
												<th>Company</th>
												<th>Salary</th>
											<!--	<th>Follow up</th> 
												<th>Assingned</th>
												<th>Source</th> -->
												<th>Reg date</th>
												<th>Upload Docs</th>
												<!--<th>Status</th>-->
												<th>Action</th>
											</tr>
                                    </thead>
                                    <tbody>
                                         <?php
                                         $sqlstring = "SELECT * FROM track_work where employee_id = '".$userid."' ";
                                         if(@$_GET['statusdocs'] == '1' ){
                                             $sqlstring .= " and doc_status = 0 ";
                                         }
                                         else if(@$_GET['statusdocs'] == '2' ){
                                             $sqlstring .= " and doc_status = 1 ";
                                         }
                                         else {
                                             $sqlstring .= " and doc_status in ('0','1')  ";
                                         }
                                         $sqlstring .= " order by reg_date desc ";
$sql = runloopQuery($sqlstring);


   $x=1;  foreach($sql as $row)
		{
?>
<tr>
<td><?php echo  $x;?></td>
<td><?php echo $row["lead_id"];?></td>
<td><?php echo $row["clientname"];?></td>
<td><?php echo $row["selecttype"];?></td>
<td><?php echo $row["mobile"];?></td>
<td><?php echo $row["email"];?></td>
<td><?php echo number_format((float)$row["amount"],2);?></td>
<td><?php echo $row["address"];?></td>
<!-- <td><?php echo $row["remark"];?></td> -->
<td><?php echo $row["company"];?></td>
<td><?php echo number_format((float)$row["income"],2);?></td>
<!--<td><?php echo date('d F Y',strtotime($row["followup"]));?></td>
<td><?php echo $row["assingedto"];?></td>
<td><?php echo $row["source"];?></td> -->
<td><?php echo reg_date($row["reg_date"]);?></td> 
<td>
<?php if($row['doc_status'] == '1' || $row['doc_status'] == '2' ) { ?>
	<button type="button" class="btn btn-success" onclick="preview_doc(<?php echo $row['ID'];?>)">
  Preview Documents
</button>
<?php  } else {?>
<button type="button" class="btn btn-success" onclick="upload_doc(<?php echo $row['ID'];?>)">
  Upload
</button>
<?php } ?>
</td>
<!--<td><a href="?status=<?php echo $row["ID"];?>&statuscontent=<?php echo $row["status"];?>"><i class="fa fa-pencil"></i></a></td>-->
<td><a href="?remark=<?php echo $row["ID"];?>"><i class="fa fa-pencil icon-border"></i></a>
<a href="?editlead=<?php echo $row["ID"];?>"><i class="fa fa-eye icon-border"></i></a>
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
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
<?php include('footer.php');?>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
	
   <?php include("footerscripts.php");?>


    
	<!--end::Page Scripts -->

	<!--add Modal -->
<div class="modal fade" id="newModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
<div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title page-title">Add Lead</h4>
        </div>
        <div class="modal-body" style=" height:60vh;
    overflow-y: auto;">
		<form  method="post" action="" enctype="multipart/form-data" id="addleadform" autocomplete="off"> 
						<div class="row">
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Lead Name*</label>
                        <div class="col-3">
                            <input type="text" placeholder="Lead Name" name="clientname" required class="form-control form-control-line" id="addclientname" >
						</div>
						</div> 
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Service Type*</label>
                        <div class="col-3">
                             <select class="form-control required" id="addselecttype" name="selecttype" required>
											<option value="">--select--</option>
											<?php $pointsourcearray = runloopQuery("select * from pointset order by ID desc");
												foreach($pointsourcearray as $pointsource){
											?>
											<option value="<?php echo $pointsource['title'];?>" <?php if($pointsource['title']==@$_POST['selecttype']){ echo 'selected';}?>><?php echo $pointsource['title'];?></option>
												<?php }?>
										</select> 
                        </div>
						</div>

						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Mobile No *</label>
						<div class="col-3">
							<input type="text" class="form-control form-control-line required"  onkeypress="return isNumberKey(event)" placeholder="Mobile No" id="addmobile"  name="mobile" required maxlength="10" pattern="\d{10}"    >
						</div>
						</div>
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Email Id</label>
						<div class="col-3">
							  <input type="email" placeholder="Email Id" name="email" class="form-control form-control-line"  >
						</div>
						</div>
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Deal Amount *</label>
						<div class="col-3">
							<input type="text" placeholder="Deal Amount" id="addamount" onkeypress="return isNumberKey(event)"  name="amount" class="form-control form-control-line" required  >
						</div>   
						</div>
						
						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Location *</label>
						<div class="col-3">
							<input type="text" placeholder="Enter Address" name="address" class="form-control form-control-line" required  id="addaddress">
						</div>   
						</div>
                        
                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Company Name</label>
						<div class="col-3">
							<input type="text" placeholder="Company Name" id="addcompany" name="company" class="form-control form-control-line"  required > 
						</div>   
						</div>

                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Salary / income *</label>
						<div class="col-3">
							<input type="text" placeholder="Salary / income" name="income" onkeypress="return isNumberKey(event)" class="form-control form-control-line" required  id="addincome">
						</div>   
						</div>

                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Follow up date *</label>
						<div class="col-3">
							<input type="date" placeholder="Follow up date" name="followup"  value="<?php echo date('Y-m-d');?>" min="<?= date('Y-m-d'); ?>" class="form-control form-control-line" required id="addfollowup">
						</div>   
						</div>

                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Remark *</label>
						<div class="col-3">
							<input type="text" placeholder="Write Remark" name="remark" class="form-control form-control-line" required  id="addremark">
						</div>   
						</div>

                        <div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label">Assign to</label>
						<div class="col-3">
                            <input type="text" placeholder="Employee id" name="assingedto" class="form-control form-control-line"  >
						</div>   
						</div>

						<div class="form-group col-md-6"> 
						<label for="example-text-input" class="col-2 col-form-label" >Source *</label>
						<div class="col-3">
							<select class="form-control" required name="source" id="addsource">
											<option value="">--select--</option>
											<?php $pointsetarray = runloopQuery("select * from worksource order by ID desc");
												foreach($pointsetarray as $pointset){
											?>
											<option value="<?php echo $pointset['title'];?>"><?php echo $pointset['title'];?></option>
												<?php }?>
										</select>
						</div>
						</div>				
						
						</div>
					
					   
					</form> 
	</div>
	<div class="modal-footer">
		  <button type="submit" name="submit" value="submit" id="addleadsubmitid" class="btn btn-brand btn-elevate btn-pill btn-pill btn-success">Add Client</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        
</div>
      
    </div>
  </div>
  
<!-- end of add modal -->

	<?php if(!empty($_GET['remark'])){ 
$sql = runQuery("SELECT * FROM track_work where ID = '".$_GET['remark']."' order by ID desc");
$remarks_history = runloopQuery("SELECT * FROM remarks_history where track_work_id = '".$_GET['remark']."' order by ID desc");

	?>
		<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Remark</h4>
      </div>
      <div class="modal-body">
       <form method="post" action="" >
           <div class="form-group">
				<label>Follow up date</label>
				<input type="date" placeholder="Follow up date" name="followup" min="<?= date('Y-m-d'); ?>" value="<?php echo $sql['followup'];?>" class="form-control form-control-line" required> 
			</div>
			<div class="form-group">
				<label>Remark</label>
				<textarea class="form-control"  name="remark" ></textarea>
			</div>
			<div class="form-group">
			 <button type="submit" class="btn btn-success" value="<?php echo $sql['ID'];?>" name="remarksubmit" >Submit</button>
			</div>
	   </form>
	   <table class="table table-bordered table-striped">
	       <tr>
	           <th>S.No</th>
	           <th>Employee Name</th>
	           <th>Remarks</th>
	           <th>Date</th>
	       </tr>
	       <?php
	       for($r=0; $r < count($remarks_history); $r++)
	       { ?>
	           <tr>
	               <td><?php echo $r+1; ?></td>
	               <td><?php echo employee_details($remarks_history[$r]['employee_id'],'fname'); ?></td>
	               <td><?php echo $remarks_history[$r]['remark_desc']; ?></td>
	               <td><?php echo date('Y-m-d H:i:s A',strtotime($remarks_history[$r]['reg_date'])); ?></td>

	           </tr>
	       <?php } ?>
	  </table>     
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
	<?php }?>
	
	
		<?php if(!empty($_GET['editlead'])){ 
$sql = runQuery("SELECT * FROM track_work where ID = '".$_GET['editlead']."' order by ID desc");

	?>
		<!-- Modal -->
<div id="myModaleditlead" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Lead</h4>
      </div>
      <div class="modal-body">
<form  method="post" action="" id="editleadformid">	
                          <div class="form-group row">			 
								<label class="col-md-2">Lead Name*</label>
								<div class="col-md-4">
									<input type="text" placeholder="Lead Name" name="clientname" class="form-control form-control-line" value="<?php echo $sql['clientname'];?>" required> 
                               </div>
						
								<label class="col-md-2">Service Type*</label>
                                    <div class="col-md-4">
                                        
                                        <select class="form-control" required name="selecttype" >
											<option value="">--select--</option>
											<?php $pointsourcearray = runloopQuery("select * from pointset order by ID desc");
												foreach($pointsourcearray as $pointsource){
											?>
											<option value="<?php echo $pointsource['title'];?>" <?php if($pointsource['title']==$sql['selecttype']){ echo 'selected';}?>><?php echo $pointsource['title'];?></option>
												<?php }?>
										</select> 
                               <!--     <input type="text" placeholder="Service Type" name="selecttype" class="form-control form-control-line" value="<?php echo $sql['selecttype'];?>"  required>  -->  
                                    </div>
                         </div>
						 
						<div class="form-group row">
                                <label class="col-md-2">Mobile No *</label>
                             <div class="col-md-4">
							 
							    <!--<input type="text" class="form-control form-control-line"  onkeypress="return isNumberKey(event)" placeholder="Mobile No"  name="mobile" required maxlength="10" pattern="\d{10}"  value="<?php echo $sql['mobile'];?>"  > -->
							    <?php echo $sql['mobile'];?>
								 
                             </div> 
                                <label class="col-md-2">Email Id</label>
                             <div class="col-md-4">
								<input type="email" placeholder="Email Id" name="email" class="form-control form-control-line" value="<?php echo $sql['email'];?>" > 
                             </div>
                          </div> 
					   <div class="form-group row">
							<label class="col-md-2">Deal Amount *</label>
								<div class="col-md-4">
										<input type="text" placeholder="Deal Amount"  onkeypress="return isNumberKey(event)"  name="amount" class="form-control form-control-line" required value="<?php echo $sql['amount'];?>" > 
                                </div> 
							<label class="col-md-2">Location *</label>
								<div class="col-md-4">
										<input type="text" placeholder="Enter Address" name="address" class="form-control form-control-line" required value="<?php echo $sql['address'];?>" > 
                                </div> 
                                </div> 
							<div class="form-group row">
                                    	<label class="col-md-2">Company Name</label>
                                    <div class="col-md-4">
                          <input type="text" placeholder="Company Name" name="company" class="form-control form-control-line" required value="<?php echo $sql['company'];?>" > 
                                       </div>
                                    	<label class="col-md-2">Salary / income *</label>
                                    <div class="col-md-4">
                          <input type="text" placeholder="Salary / income" name="income" onkeypress="return isNumberKey(event)" class="form-control form-control-line" required value="<?php echo $sql['income'];?>" > 
                                       </div>
                                </div>
							<div class="form-group row">
                                    	<label class="col-md-2">Follow up date *</label>
                                    <div class="col-md-4">
                          <input type="date"   name="followup" class="form-control form-control-line" required value="<?php echo $sql['followup'] ?? date('Y-m-d');?>" min="<?= date('Y-m-d'); ?>" > 
                          
                                       </div>
                                    	<label class="col-md-2">Remark *</label>
                                    <div class="col-md-4">
                          <input type="text" placeholder="Write Remark" name="remark" class="form-control form-control-line" required value="<?php echo $sql['remark'];?>" > 
                                       </div>
                                </div>
							<div class="form-group row">
                                    	<label class="col-md-2">Assign to</label>
                                    <div class="col-md-4">
                          <input type="text" placeholder="Employee id" name="assingedto" class="form-control form-control-line" value="<?php echo $sql['assingedto'];?>" > 
                                       </div>
                                    	<label class="col-md-2">Source *</label>
                                    <div class="col-md-4"> 
										<select class="form-control" required name="source" >
											<option value="">--select--</option>
											<?php $pointsetarray = runloopQuery("select * from worksource order by ID desc");
												foreach($pointsetarray as $pointset){
											?>
											<option value="<?php echo $pointset['title'];?>" <?php if($pointset['title']==$sql['source']){ echo 'selected';}?>><?php echo $pointset['title'];?></option>
												<?php }?>
										</select> 
                                       </div>
                                </div>
                                <div class="form-group row">
                                    	<label class="col-md-2">Additional Number</label>
                         
                                    <div class="col-md-4">
                          <input type="text" placeholder="Additional umber" name="additional_number" class="form-control form-control-line"  value="<?php echo $sql['additional_number'];?>" > 
                                       </div>
                                </div>
								<br>
                                 <input type="hidden" name="updatelead" value="1">
                                </form>      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success" id="updatelead"  value="submit">Update Lead</button>

      </div>
    </div>

  </div>
</div>




	<script>
			$("#myModaleditlead").modal('show');
		</script>
	<?php }?>


<div id="myModal2" class="modal fade" role="dialog" tabindex="-1">
  <div class="modal-dialog" class="modal-dialog modal-dialog-scrollable" role="document">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload Documents</h4>
      </div>
      <div class="modal-body">
		<form method="post" action="" id="upload-data-form-id" enctype="multipart/form-data">
		<input type="hidden"  id="uploadtrackworkid" name="uploadtrackwork">
		<input type="hidden"  id="uploadorinsert" name="uploadorinsert" value="2">
			<table id="tblAddRow" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>File Name</th>
						<th>File</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
						<input type="text" name="uploadedfilename[]" class="form-control">
						</td>
						<td>
						<input type="file" name="uploadedfile[]" class="form-control">
						</td>
					</tr>
				</tbody>
			</table>

			<div class="modal-footer">
					<button id="btnAddRow" class="btn btn-success  " type="button">Add Row</button>
					<input type="submit" id="uploadData" class="btn btn-success " name="uploaddata" value="Upload Data">
			</div>
		</form>
      </div>
    </div>

  </div>
</div>


<div id="myModal3" class="modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-scrollable" role="document">    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload Documents</h4>
      </div>
      <div class="modal-body" id="docbody">
      
	  </div>
	  		<form method="post" action="" id="upload-data-form-id-dup" enctype="multipart/form-data">
		<input type="hidden"  id="uploadtrackworkiddup" name="uploadtrackworkdup">
		<input type="hidden"  id="uploadorinsert" name="uploadorinsert" value="2">
			<table id="tblAddRows" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>File Name</th>
						<th>File</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
						<input type="text" name="uploadedfilename[]" class="form-control">
						</td>
						<td>
						<input type="file" name="uploadedfile[]" class="form-control">
						</td>
					</tr>
				</tbody>
			</table>
	  <div class="modal-footer">
					<button id="btnAddRows" class="btn btn-success  " type="button">Add Row</button>
					<input type="submit" id="uploadData" class="btn btn-success " name="uploaddata" value="Upload Data">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
	</div>
	
  </div>
</div>



<script>
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


$('#tblAddRows tbody tr')
    .find('td')
    //.append('<input type="button" value="Delete" class="del"/>')
    .parent() //traversing to 'tr' Element
    .append('<td><a href="#" class="delrow"><i class="fa fa-trash border-red text-red"></i></a></td>');


// Add row the table
$('#btnAddRows').on('click', function() {
    var lastRow = $('#tblAddRows tbody tr:last').html();
    //alert(lastRow);
    $('#tblAddRows tbody').append('<tr>' + lastRow + '</tr>');
    $('#tblAddRows tbody tr:last input').val('');
});
// Delete row on click in the table
$('#tblAddRows').on('click', 'tr a', function(e) {
    var lenRow = $('#tblAddRows tbody tr').length;
    e.preventDefault();
    if (lenRow == 1 || lenRow <= 1) {
        alert("Can't remove all row!");
    } else {
        $(this).parents('tr').remove();
    }
});

function upload_doc(trackworkid)
{
	$("#uploadtrackworkid").val(trackworkid);
	$("#myModal2").modal('show');

}

function preview_doc(trackworkid){
	$("#uploadtrackworkiddup").val(trackworkid);

	$("#myModal3").modal('show');
	$.ajax({				
	 url : '_ajaxtrackworkdocs.php',		
	 
	 type: "POST",		
	 data: {			
		trackworkid:trackworkid			
	 },				
	 success: function(res){	
		var result = JSON.parse(res);
		$("#docbody").html('');
		$("#docbody").append(`<table width="100%" id="doctable"><tr><th class="tablestyle">S.No</th><th class="tablestyle">File Name</th><th class="tablestyle">File</th></tr>`);
		for(i=0; i < result.length ; i++) {
		    
			$("table#doctable").append(`<tr><td class="tablestyle">${(i+1)}</td><td class="tablestyle">${result[i]['file_name']}</td><td class="tablestyle"><a target="_blank" href="./../upload_documents/${trackworkid}/${result[i]['doc_name']}"><img src="./../upload_documents/${trackworkid}/${result[i]['doc_name']}"
			 class="borders" style="display: inline-block;width: 70px;height: 70px;margin: 6px;"></a></td></tr>`);
		}
        $("table#doctable tr:last").after('</table>');

	 }					
	 });

}
			$("#kt_table_1").DataTable({
			      "scrollX": true
			});

$("#updatelead").click(function(){
   $("#editleadformid").submit(); 
});

//$( "#kt_table_1_wrapper > .row > div:nth-child(2)" ).css( "border", "3px double red" );
$( ".table-responsive > #kt_table_1_wrapper:first-child > .row > div:nth-child(2)" ).removeClass( "col-md-6" ).addClass( "col-md-3" );
$( ".table-responsive > #kt_table_1_wrapper:first-child > .row > div:nth-child(2)" ).before( '<div class="col-sm-12 col-md-3">\
<select class="form-control input-normal" id="statusdocsearch"  onchange="statusdoc()">\
<option value="0" >All</option>\
<option value="1" <?php if($_GET['statusdocs'] == 1) echo "selected"; ?>>Pending Clients</option>\
<option value="2" <?php if($_GET['statusdocs'] == 2) echo "selected"; ?>>Uploaded Clients</option>\
</select><div>' );
$( "#kt_table_1_wrapper > .row:nth-child(3) > .col-md-3 > #statusdocsearch ").css( "display", "none" );
function statusdoc(){
    var statusdocsearch = $("#statusdocsearch").val();
              var form=document.createElement('form');
          form.setAttribute('method','get');
          form.setAttribute('action','track-work.php');

      var hiddenField = document.createElement("input");
      hiddenField.setAttribute("name", "statusdocs");
      hiddenField.setAttribute("type", "hidden");
      hiddenField.setAttribute("value", statusdocsearch);
      form.appendChild(hiddenField);

      document.body.appendChild(form);
      form.submit();    

}

$("#addleadsubmitid").click(function(){
	
		var user_input_value;
		var err_value = 0;
		$('#addleadform').find('input,select').each(function(){
			console.log('haiioid');
			if($(this).prop('required')){
				user_input_value  = $("#"+this.id).val();
				if(user_input_value == ''){
					if(err_value == 0){
						document.getElementById(this.id).focus();
					}
					err_value = err_value + 1;
					$("#"+this.id).css('border-color', 'red');
					//alert(user_input_value+this.id);
				}else{
					$("#"+this.id).css('border-color', '#e4e7ea');
					
				}
			}	 
		});
		
		if(err_value == 0)
		{
			$("#addleadsubmitid").hide();
			$("#addleadform").submit();	
		}
		
	});



</script>

</body>

</html>
