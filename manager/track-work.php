<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');

$userid = current_managerid(); 
if(empty($userid)){
	header("Location: index.php");
}
$monthstartdate = date('Y-m-01');
$currentdate = date('Y-m-d');
$usedetails = runQuery("select * from employee where ID = '".$userid."'");

$teamdetails = runloopQuery("select *,concat(fname,' ',lname) name from employee where leader = '".$usedetails['unique_id']."'");
$teamselect = array_column($teamdetails,'name',"ID");
$teamselect[$userid] = $usedetails['fname'].' '.$usedetails['lname'];

$message = '';
if(!empty($_POST['submit'])){
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
		 	}else{

		$message .=" Lead Name Field is Empty";

	}

}

if(!empty($_POST['clientfinalsubmit'])){
	
	if(!empty($_POST['clientname'])){
	   $pagerarray  = array();
	   $empid = mysqli_real_escape_string($conn,$_POST['employee_id']);
	   
	   $employeeid = runQuery("select * from employee where ID = '".$empid."'");
	   
			if(!empty($employeeid['ID'])){ 
			    $maxleadtrack = runQuery("select MAX(ID) as id from clients");
		        $maxid = @$maxleadtrack['id']+1;

		        $pagerarray['employee_id'] = $employeeid['ID']; 
                $pagerarray['client_id'] = "M3A".$usedetails['unique_id'].'C'.$maxid;
                $pagerarray['clientname'] = mysqli_real_escape_string($conn,$_POST['clientname']);
                $pagerarray['regdate'] = mysqli_real_escape_string($conn,$_POST['regdate']);
                $pagerarray['mobile'] = mysqli_real_escape_string($conn,$_POST['mobile']);
                $pagerarray['servicetype'] = mysqli_real_escape_string($conn,$_POST['servicetype']);
                $pagerarray['loanamount'] = mysqli_real_escape_string($conn,$_POST['loanamount']);
                $pagerarray['companyname'] = mysqli_real_escape_string($conn,$_POST['companyname']);
                $pagerarray['pointstype'] = mysqli_real_escape_string($conn,$_POST['pointstype']);
                $pagerarray['location'] = mysqli_real_escape_string($conn,$_POST['location']);
                $pagerarray['type_of_client'] = mysqli_real_escape_string($conn,$_POST['type_of_client']);
                $pagerarray['variant'] = mysqli_real_escape_string($conn,$_POST['variant']);
                $pagerarray['variant_location'] = mysqli_real_escape_string($conn,$_POST['variant_location']);
                $pagerarray['status'] = $_POST['status'];
                $pagerarray['track_work_id'] = mysqli_real_escape_string($conn,$_POST['trackworkid']);
				$pagerarray['mod_date'] = date('Y-m-d H:i:s A');
	            $result = insertQuery($pagerarray,'clients');
				emp_monthly_income_check(['empid' => $employeeid['ID']]);
				$trid['ID'] =mysqli_real_escape_string($conn,$_POST['trackworkid']); 
	                $docstatus['doc_status'] = 2;
                    $result = updateQuery($docstatus,'track_work',$trid);

	            
	            
				if(!$result){
					header("Location: track-client.php?success=success");
                } 
				}else{ 
					$message .="Invalid employee id";
				}
		 	}else{
				$message .="  Lead Name  Field is Empty";
			}

}
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
if(!empty($_POST['remarksubmit'])  && !empty($_POST['remark'])){
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
	$uploadtrackwork = $_POST['uploadtrackwork'];
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
    <title>M3 Dashboard</title>
    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- toast CSS -->
    <link href="../plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- morris CSS -->
    <link href="../plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <!-- color CSS -->
    <link href="css/colors/blue-dark.css" id="theme" rel="stylesheet">
	<link href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
<style>
.tablestyle {
      border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;

}

.icon-border {
    padding : 8px;
    margin : 2px;
    border : 1px solid black;
}

</style>
<div id="wrapper">

        <!-- Navigation -->
               <?php include('header.php');?>
        <!-- Left navbar-header end -->
		
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">TRACK LEAD</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
					<ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li class="active">TRACK  Lead</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div> 
				<button type="button" class="btn btn-brand btn-success btn-pill " style="margin-top:-25px ; margin-bottom: 10px;float:right" data-toggle="modal" data-target="#addLeadModal">Add Lead</button>    

                <!-- /row -->
                <div class="row" style="display:none">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title">Track Lead</h3>
                            <div class="table-responsive">
                             
						
							<div class="white-box">
								<?php if(!empty($_GET['statusupdate'])){?>

									<div class="alert alert-success">

									Status updated Successfully

									</div>

								<?php }?>
                          <?php if(!empty($_GET['success'])){?>

								<div class="alert alert-success">

								Track Work Details Added Successfully

								</div>

								<?php }?>

								<?php if(!empty($message)){?>

								<div class="alert alert-danger">

								<?=$message;?>

								</div>

								<?php }?>

				  
				<form  style="display:none" method="post" action="" enctype="multipart/form-data">	
                          <div class="form-group row">			 
								<label class="col-md-2">Lead Name*</label>
								<div class="col-md-4">
									<input type="text" placeholder="Lead Name" name="clientname" class="form-control form-control-line"  required> 
                               </div>
						
								<label class="col-md-2">Service Type*</label>
                                    <div class="col-md-4">
                                            <select class="form-control" required name="selecttype" required>
											<option value="">--select--</option>
											<?php $pointsourcearray = runloopQuery("select * from pointset order by ID desc");
												foreach($pointsourcearray as $pointsource){
											?>
											<option value="<?php echo $pointsource['title'];?>" <?php if($pointsource['title']==@$_POST['selecttype']){ echo 'selected';}?>><?php echo $pointsource['title'];?></option>
												<?php }?>
										</select> 
                                <!--    <input type="text" placeholder="Service Type" name="selecttype" class="form-control form-control-line"   required>  --> 
                                    </div>
                         </div>
						 
						<div class="form-group row">
                                <label class="col-md-2">Mobile No *</label>
                             <div class="col-md-4">
							 
							    <input type="text" class="form-control form-control-line"  onkeypress="return isNumberKey(event)" placeholder="Mobile No"  name="mobile" required maxlength="10" pattern="\d{10}"    >
								 
                             </div> 
                                <label class="col-md-2">Email Id</label>
                             <div class="col-md-4">
								<input type="email" placeholder="Email Id" name="email" class="form-control form-control-line"  > 
                             </div>
                          </div> 
					   <div class="form-group row">
							<label class="col-md-2">Deal Amount *</label>
								<div class="col-md-4">
										<input type="text" placeholder="Deal Amount"  onkeypress="return isNumberKey(event)"  name="amount" class="form-control form-control-line" required  > 
                                </div> 
							<label class="col-md-2">Location *</label>
								<div class="col-md-4">
										<input type="text" placeholder="Enter Address" name="address" class="form-control form-control-line" required  > 
                                </div> 
                                </div> 
							<div class="form-group row">
                                    	<label class="col-md-2">Company Name</label>
                                    <div class="col-md-4">
                          <input type="text" placeholder="Company Name" name="company" class="form-control form-control-line"  required > 
                                       </div>
                                    	<label class="col-md-2">Salary / income *</label>
                                    <div class="col-md-4">
                          <input type="text" placeholder="Salary / income" name="income" onkeypress="return isNumberKey(event)" class="form-control form-control-line" required  > 
                                       </div>
                                </div>
							<div class="form-group row">
                                    	<label class="col-md-2">Follow up date *</label>
                                    <div class="col-md-4">
                          <input type="date" placeholder="Follow up date" name="followup"  value="<?php echo date('Y-m-d');?>" min="<?= date('Y-m-d'); ?>" class="form-control form-control-line" required > 
                                       </div>
                                    	<label class="col-md-2">Remark *</label>
                                    <div class="col-md-4">
                          <input type="text" placeholder="Write Remark" name="remark" class="form-control form-control-line" required  > 
                                       </div>
                                </div>
							<div class="form-group row">
                                    	<label class="col-md-2">Assign to</label>
                                    <div class="col-md-4">
                          <input type="text" placeholder="Employee id" name="assingedto" class="form-control form-control-line"  > 
                                       </div>
                                    	<label class="col-md-2">Source *</label>
                                    <div class="col-md-4"> 
										<select class="form-control" required name="source" >
											<option value="">--select--</option>
											<?php $pointsetarray = runloopQuery("select * from worksource order by ID desc");
												foreach($pointsetarray as $pointset){
											?>
											<option value="<?php echo $pointset['title'];?>"><?php echo $pointset['title'];?></option>
												<?php }?>
										</select> 
                                       </div>
                                </div>
								<br>
                                  <div class="col-md-6">
                                        <button type="submit" class="btn btn-success" name="submit" value="submit">Add Lead</button>
                            </div>
                                </form>
                                </div>
                              
                                
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
                <!-- /.row -->
                
                <!-- /row -->
                <div class="row">
                    <div class="col-sm-4">
					    <h3 class="box-title">Track My Work</h3>
					</div>

					<div class="col-sm-12">
						<div class="white-box">

						<?php if(!empty($_GET['statusupdate'])){?>

							<div class="alert alert-success">

							Status updated Successfully

							</div>

							<?php }?>
							<?php if(!empty($_GET['success'])){?>

							<div class="alert alert-success">

							Track Work Details Added Successfully

							</div>

							<?php }?>

							<?php if(!empty($message)){?>

							<div class="alert alert-danger">

							<?=$message;?>

							</div>

							<?php }?>

							
				<form method="get" action="">
										<div class="form-group row">

										<label for="example-text-input" class="col-md-1 col-form-label">Employee</label>
										<div class="col-md-2">
										    <select name="team_member" class="form-control">
							<option value="">select</option>
							<?php foreach($teamselect as $key => $value) {?>
								<option value="<?= $key ;?>" <?php if(isset($_REQUEST['team_member'])) { if($_REQUEST['team_member'] == $key) { ?> selected <?php } } ?>)><?= $value ;?></option>
							<?php } ?>
						</select>
										</div>
										<label for="example-text-input" class="col-md-1 col-form-label">Start Date</label>
										<div class="col-md-2">
										<input class="form-control" type="date" name="sdate" value="<?= @$_GET['sdate'] ?? $monthstartdate; ?>">
										</div>
										<label for="example-text-input" class="col-md-1 ">End Date</label>
										<div class="col-md-2">
										<input class="form-control" type="date" name="edate" value="<?= @$_GET['edate'] ?? date('Y-m-d');?>">
										</div>
										<div class="col-md-3 " style="display: flex;  justify-content: center;" >
										<button type="submit"  class="btn btn-brand btn-elevate btn-success">Submit</button>
										</div>
										</div>
										 
									</form>
                            <div class="table-responsive">
                                <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_1" >
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
                                          $startdate = !empty($_GET['sdate']) ? $_GET['sdate'] : $monthstartdate ;
							 $enddate = !empty($_GET['sdate']) ? $_GET['edate'] : $currentdate ;
                                         $sqlstring = "";
                                         if(@$_GET['statusdocs'] == '1' ){
                                             $sqlstring = " and doc_status = 0 ";
                                         }
                                         if(@$_GET['statusdocs'] == '2' ){
                                             $sqlstring = " and doc_status = 1 ";
                                         }
                                         $sqlstring .= " order by reg_date desc ";
										 if(!empty($_REQUEST['team_member']  )) {
											 $sql = runloopQuery("SELECT * FROM track_work where employee_id = '".$_REQUEST['team_member']."' and doc_status in ('0','1') and date(reg_date) between '".$startdate."' and '".$enddate."' $sqlstring");
										}else{
											$sql = runloopQuery("SELECT * FROM track_work where 
											employee_id = '".$userid."' and doc_status in ('0','1') and date(reg_date) between '".$startdate."' and '".$enddate."' UNION SELECT * FROM track_work 
											where employee_id in 
											(select ID from employee where leader = '".$usedetails['unique_id']."') and doc_status in ('0','1') and date(reg_date) between '".$startdate."' and '".$enddate."' $sqlstring ");

										 }
											

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
										<!--	<td><?php echo $row["remark"];?></td> -->
											<td><?php echo $row["company"];?></td>
											<td><?php echo number_format((float)$row["income"],2);?></td>
										<!--	<td><?php echo date('d F Y',strtotime($row["followup"]));?></td>
											<td><?php echo $row["assingedto"];?></td>
											<td><?php echo $row["source"];?></td> -->
											<td><?php echo reg_date($row["reg_date"]);?></td> 
											<td>
											<?php if($row['doc_status'] == '1' || $row['doc_status'] == '2') { ?>
												<button type="button" class="btn btn-success" onclick="preview_doc(<?php echo $row['ID'];?>)">
											Preview Documents
											</button>
<br>
<a href="../makezip/makezipfile.php?id=<?php echo $row['ID'];?>" class="btn btn-primary mt-2">Download</a>
											<?php  } else {?>
											<button type="button" class="btn btn-success" onclick="upload_doc(<?php echo $row['ID'];?>)">
											Upload
											</button>
											<?php } ?>
											</td>
											<!--<td><a href="?status=<?php echo $row["ID"];?>&statuscontent=<?php echo $row["status"];?>"><i class="fa fa-pencil"></i></a></td>-->
											<td style="display:flex"><a href="?remark=<?php echo $row["ID"];?>"><i class="fa fa-pencil icon-border"></i></a>
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
	
    <script src="js/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    	<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
    <!-- Custom Theme JavaScript -->
    
	<!--end::Page Scripts -->


	<!--add Modal -->
<div class="modal fade" id="addLeadModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
	  <form  method="post" action="" enctype="multipart/form-data">	
  
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title page-title">Add Client</h4>
        </div>
        <div class="modal-body">

                          <div class="form-group row">			 
								<label class="col-md-2">Lead Name*</label>
								<div class="col-md-4">
									<input type="text" placeholder="Lead Name" name="clientname" class="form-control form-control-line"  required> 
                               </div>
						
								<label class="col-md-2">Service Type*</label>
                                    <div class="col-md-4">
                                            <select class="form-control" required name="selecttype" required>
											<option value="">--select--</option>
											<?php $pointsourcearray = runloopQuery("select * from pointset order by ID desc");
												foreach($pointsourcearray as $pointsource){
											?>
											<option value="<?php echo $pointsource['title'];?>" <?php if($pointsource['title']==@$_POST['selecttype']){ echo 'selected';}?>><?php echo $pointsource['title'];?></option>
												<?php }?>
										</select> 
                                <!--    <input type="text" placeholder="Service Type" name="selecttype" class="form-control form-control-line"   required>  --> 
                                    </div>
                         </div>
						 
						<div class="form-group row">
                                <label class="col-md-2">Mobile No *</label>
                             <div class="col-md-4">
							 
							    <input type="text" class="form-control form-control-line"  onkeypress="return isNumberKey(event)" placeholder="Mobile No"  name="mobile" required maxlength="10" pattern="\d{10}"    >
								 
                             </div> 
                                <label class="col-md-2">Email Id</label>
                             <div class="col-md-4">
								<input type="email" placeholder="Email Id" name="email" class="form-control form-control-line"  > 
                             </div>
                          </div> 
					   <div class="form-group row">
							<label class="col-md-2">Deal Amount *</label>
								<div class="col-md-4">
										<input type="text" placeholder="Deal Amount"  onkeypress="return isNumberKey(event)"  name="amount" class="form-control form-control-line" required  > 
                                </div> 
							<label class="col-md-2">Location *</label>
								<div class="col-md-4">
										<input type="text" placeholder="Enter Address" name="address" class="form-control form-control-line" required  > 
                                </div> 
                                </div> 
							<div class="form-group row">
                                    	<label class="col-md-2">Company Name</label>
                                    <div class="col-md-4">
                          <input type="text" placeholder="Company Name" name="company" class="form-control form-control-line"  required > 
                                       </div>
                                    	<label class="col-md-2">Salary / income *</label>
                                    <div class="col-md-4">
                          <input type="text" placeholder="Salary / income" name="income" onkeypress="return isNumberKey(event)" class="form-control form-control-line" required  > 
                                       </div>
                                </div>
							<div class="form-group row">
                                    	<label class="col-md-2">Follow up date *</label>
                                    <div class="col-md-4">
                          <input type="date" placeholder="Follow up date" name="followup"  value="<?php echo date('Y-m-d');?>" min="<?= date('Y-m-d'); ?>" class="form-control form-control-line" required > 
                                       </div>
                                    	<label class="col-md-2">Remark *</label>
                                    <div class="col-md-4">
                          <input type="text" placeholder="Write Remark" name="remark" class="form-control form-control-line" required  > 
                                       </div>
                                </div>
							<div class="form-group row">
                                    	<label class="col-md-2">Assign to</label>
                                    <div class="col-md-4">
                          <input type="text" placeholder="Employee id" name="assingedto" class="form-control form-control-line"  > 
                                       </div>
                                    	<label class="col-md-2">Source *</label>
					<div class="col-md-4"> 
						<select class="form-control" required name="source" >
							<option value="">--select--</option>
							<?php $pointsetarray = runloopQuery("select * from worksource order by ID desc");
								foreach($pointsetarray as $pointset){
							?>
							<option value="<?php echo $pointset['title'];?>"><?php echo $pointset['title'];?></option>
								<?php }?>
						</select> 
						</div>
                    </div>
					<br>
                    <div class="col-md-6">
                            <!-- <button type="submit" class="btn btn-success" name="submit" value="submit">Add Lead</button> -->
                	</div>
            				
		</div>
        <div class="modal-footer">
		  <button type="submit" name="submit" value="submit" class="btn btn-brand btn-elevate btn-success" id="addclientsubmitid">Add Client</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
		</form>	
      </div>
      
    </div>
  </div>

	
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
							 
							    <input type="text" class="form-control form-control-line"  onkeypress="return isNumberKey(event)" placeholder="Mobile No"  name="mobile" required maxlength="10" pattern="\d{10}"  value="<?php echo $sql['mobile'];?>"  >
								 
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



<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Upload Documents</h4>
      </div>
      <div class="modal-body">
		<form method="post" action="" id="upload-data-form-id" enctype="multipart/form-data">
		<input type="hidden"  id="uploadtrackworkid" name="uploadtrackwork">
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
					<button id="btnAddRow" class="btn btn-success" type="button">Add Row</button>
					<input type="submit" id="uploadData" class="btn btn-success" name="uploaddata" value="Upload Data">
			</div>
		</form>
      </div>
    </div>

  </div>
</div>


<div id="myModal3" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Uploaded Documents</h4>
      </div>
    <form action="" method="POST">  
    <input type="hidden" id="trackworkid" name="trackworkid" />
        <input type="hidden" id="clientname" name="clientname">
        <input type="hidden" id="regdate" name="regdate" value="<?= date('Y-m-d'); ?>">
        <input type="hidden" id="mobile" name="mobile">
        <input type="hidden" id="servicetype" name="servicetype">
        <input type="hidden" id="loanamount" name="loanamount">
        <input type="hidden" id="companyname" name="companyname">
        <input type="hidden" id="pointstype" name="pointstype">
        <input type="hidden" id="location" name="location">
		<input type="hidden" id="employee_id" name="employee_id">
				<input type="hidden" id="type_of_client" name="type_of_client">
						<input type="hidden" id="variant" name="variant">
								<input type="hidden" id="variant_location" name="variant_location">
		
      <div class="modal-body" id="docbody">
        
	  </div>
	  <div class="modal-footer">
      <button type="submit" name="clientfinalsubmit" value="submit" id="convertid" class="btn btn-brand btn-elevate btn-pill">Convert To Client</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </form>
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

function upload_doc(trackworkid)
{
	$("#uploadtrackworkid").val(trackworkid);
	$("#myModal2").modal('show');

}

function preview_doc(trackworkid){
	$("#myModal3").modal('show');
	
	$("#trackworkid").val(trackworkid);
    $.ajax({				
	 url : '../team/_ajaxtrackworkdocs.php',		
	 
	 type: "POST",		
	 data: {			
		trackworkid:trackworkid			
	 },				
	 success: function(res){	
		var result = JSON.parse(res);
        $("#clientname").val(result[0]['clientname']);
        $("#mobile").val(result[0]['mobile']);
        $("#servicetype").val(result[0]['selecttype']);
        $("#loanamount").val(result[0]['amount']);
        $("#companyname").val(result[0]['company']);
        $("#location").val(result[0]['address']);
        $("#employee_id").val(result[0]['employee_id'])
		$("#docbody").html('');
		$("#docbody").append('<div class="row"><div class="col">');
        //for(i=0; i < result.length ; i++) {
		//	$("#docbody").append(`<a target="_blank" href="./../upload_documents/${trackworkid}/${result[i]['doc_name']}"><img src="./../upload_documents/${trackworkid}/${result[i]['doc_name']}"
		//	 class="borders" style="display: inline-block;width: 70px;height: 70px;margin: 6px;" ></a>`);
		//}
		$("#docbody").append(`<table width="100%" id="doctable"><tr><th class="tablestyle">S.No</th><th class="tablestyle">File Name</th><th class="tablestyle">File</th></tr>`);
		for(i=0; i < result.length ; i++) {
		    $("#doctable").append(`<tr><td class="tablestyle">${(i+1)}</td><td class="tablestyle">${result[i]['file_name']}</td><td class="tablestyle"><a target="_blank" href="./../upload_documents/${trackworkid}/${result[i]['doc_name']}"><img src="./../upload_documents/${trackworkid}/${result[i]['doc_name']}"
			 class="borders" style="display: inline-block;width: 70px;height: 70px;margin: 6px;"></a></td></tr>`);
		}
        $("#docbody").append('</div></div>')
        if(result[0]['doc_status'] == '1') {
        $("#docbody").append(`<br>
        
  <div class="form-group row">
  				<label class="col-md-2">Status :</label>
				<div class="col-md-4">
                <select required id="status" class="form-control" name="status">
                        <option value="">--Select--</option>
										<option value="Pending">Pending</option>
										<option value="Approved">Approved</option>
										<option value="Rejected">Reject</option>
                        </select>			</div>
                        	<label class="col-md-2">Points Set :</label>
				<div class="col-md-4">
        <select  required class="form-control" name="pointstype" onchange="getvariant()" id="pointsetpopupid" >
								<option value="">--select--</option>
								<?php $pointsetarray = runloopQuery("select * from pointset order by ID desc");
									foreach($pointsetarray as $pointset){
								?>
								<option value="<?php echo $pointset['ID'];?>"><?php echo $pointset['title'];?></option>
									<?php }?>
							</select>			
							</div>
							
			</div>
			  <div class="form-group row">
			    <label class="col-md-2">Type Of Client :</label>
				<div class="col-md-4">
                        <select name="type_of_client" class="form-control">
                                <option value="1">Direct Client</option>
                                <option value="2">Indirect Client</option>
                            </select>   
                </div>
                <label class="col-md-2">Variant :</label>
				<div class="col-md-4">
                        <select class="form-control" type="text" name="variant"  placeholder="Variant" id="variantpopupid" onchange="getvariantlocation()">
                            <option value="">---select---</option>
                        </select>
                </div>
                
			  </div>
			  <div class="form-group row">
			  <label class="col-md-2">Variant Location:</label>
				<div class="col-md-4">
                            <select class="form-control" name="variant_location"  id="variantlocpopupid">
                                <option value="">---select---</option>
                            </select>
                </div>
			  </div>
        <div class="row mt-3"><div class="col">
                        
        </div></div>
        <div class="row mt-3"><div class="col">
                        
                            </div></div>
        `);
        }else{
            $("#convertid").hide();
        }
        
	 }					
	 });

}


			$("#kt_table_1").DataTable({
			      "scrollX": true
			});
$("#updatelead").click(function(){
   $("#editleadformid").submit(); 
});

//$( "#kt_table_1_wrapper > .row:nth-child(1) > div:nth-child(2)" ).css( "border", "3px double red" );
$( "#kt_table_1_wrapper > .row:nth-child(1) > div:nth-child(2)" ).removeClass( "col-md-6" ).addClass( "col-md-3" );
$( " #kt_table_1_wrapper > .row:nth-child(1) > div:nth-child(2)" ).before( '<div class="col-sm-12 col-md-3">\
<select class="form-control input-normal" id="statusdocsearch"  onchange="statusdoc()">\
<option value="0" >All</option>\
<option value="1" <?php if(@$_GET['statusdocs'] == 1) echo "selected"; ?>>Pending Clients</option>\
<option value="2" <?php if(@$_GET['statusdocs'] == 2) echo "selected"; ?>>Uploaded Clients</option>\
</select><div>' );

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

function getvariant()
{
    var pointsetid = $("#pointsetpopupid").val();
    $.ajax({				
	 url : '../admin/ajax/commonajax.php',		
	 
	 type: "POST",		
	 data: {		
	    action : 'getpointvariant',
		pointsetid:pointsetid			
	 },				
	 success: function(res){
	     var result = JSON.parse(res);
	     var optval = '';
	     $("#variantpopupid").html(optval);
	     optval += '<option value="">---select---</option>';
	     for(var i=0;i<result.length;i++){
	         optval += '<option value="'+result[i]['variant']+'">'+result[i]['variant']+'</option>'; 
	     }
	     $("#variantpopupid").html(optval);
	 }
	 });
}

function getvariantlocation()
{
     var pointsetid = $("#pointsetpopupid").val();
     var variantpopupid = $("#variantpopupid").val();
    $.ajax({				
	 url : '../admin/ajax/commonajax.php',		
	 
	 type: "POST",		
	 data: {		
	    action : 'getvariantlocation',
		pointsetid:pointsetid,
		variantpopupid:variantpopupid
	 },				
	 success: function(res){
	     var result = JSON.parse(res);
	     var optvals = '';
	     $("#variantlocpopupid").html(optvals);
	     optvals += '<option value="">---select---</option>';
	     for(var i=0;i<result.length;i++){
	         optvals += '<option value="'+result[i]['variant_location']+'">'+result[i]['variant_location']+'</option>'; 
	     }
	     $("#variantlocpopupid").html(optvals);
	 }
	 });   
}

</script>

</body>

</html>