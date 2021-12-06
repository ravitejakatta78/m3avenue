<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');

$userid = current_managerid(); 
if(empty($userid)){
	header("Location: index.php");
}
$usedetails = runQuery("select * from employee where ID = '".$userid."'");

$teamdetails = runloopQuery("select *,concat(fname,' ',lname) name from employee where leader = '".$usedetails['unique_id']."'");
$teamselect = array_column($teamdetails,'name',"ID");
$teamselect[$userid] = $usedetails['fname'].' '.$usedetails['lname'];
$message = '';
$teamidarr = [];
$monthstartdate = date('Y-m-01');
$currentdate = date('Y-m-d');
$teamidarr = array_column($teamdetails,'ID');
array_push($teamidarr,$userid);
$teamidarr = implode("','",$teamidarr);
if(!empty($_GET['delete'])){
	$sql = "DELETE FROM track_work WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: today-track-work.php?success=success");
   
} else {
    echo "Error deleting record: " . $conn->error;
}
}
if(!empty($_GET['status'])){
	$statuscontent = $_GET['statuscontent']=='Yes' ? 'No' : 'Yes';
	$sql = "UPDATE track_work set status = '".$statuscontent."' WHERE ID=".$_GET['status']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: today-track-work.php?statusupdate=success");
   
} else {
    echo "Error deleting record: " . $conn->error;
}
}
if(!empty($_POST['remarksubmit']) && !empty($_POST['remark'])){
	$statuscontent = mysqli_real_escape_string($conn,$_POST['remark']);
	$followup = date('Y-m-d',strtotime($_POST['followup']));
	$sql = "UPDATE track_work set remark = '".$statuscontent."',followup = '".$followup."' WHERE ID=".$_POST['remarksubmit']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: today-track-work.php?statusupdate=success");
   
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
<div id="wrapper">
        <!-- Navigation -->
               <?php include('header.php');?>
        <!-- Left navbar-header end -->
		
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">TRACK MY WORK</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
					<ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li class="active">TRACK  My Work</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div> 
                <!-- /row -->
              <!--  <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title">Track  My Work</h3>
                             <div class="table-responsive">
                             
						
                             <div class="white-box">
                                 
        
                          
                       <form  method="get" action="" enctype="multipart/form-data">	
                                  <div class="form-group row">			  
                                        <div class="col-md-4">
                                            <input type="date"  name="followup" class="form-control form-control-line" required> 
                                       </div>
                                 <button type="submit" class="btn btn-success">Update</button>
                                 </div>  
                                        </form> 
                                        </div>
                                      
                                        
                                    </div>
                                  
                            
                        </div>
                    </div>
                </div>-->
                <!-- /.row -->
                
                <!-- /row -->
                <div class="row">
                    

					<div class="col-sm-12">
						<div class="white-box">
						     <? if(!empty($_GET['statusupdate'])){?>
        
                                        <div class="alert alert-success">
        
                                        Status updated Successfully
        
                                        </div>
        
                                        <? }?>
                                  <? if(!empty($_GET['success'])){?>
        
                                        <div class="alert alert-success">
        
                                        Track Work Details Added Successfully
        
                                        </div>
        
                                        <? }?>
        
                                        <? if(!empty($message)){?>
        
                                        <div class="alert alert-danger">
        
                                        <?=$message;?>
        
                                        </div>
        
                                        <? }?>
				<form method="get" action="">
										<div class="form-group row">

										<label for="example-text-input" class="col-md-1 col-form-label">Employee</label>
										<div class="col-md-2">
										    <select name="employeeid" class="form-control">
							<option value="">select</option>
							<?php foreach($teamselect as $key => $value) {?>
								<option value="<?= $key ;?>" <?php if(isset($_REQUEST['employeeid'])) { if($_REQUEST['employeeid'] == $key) { ?> selected <?php } } ?>)><?= $value ;?></option>
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
                                <table class="table" id="kt_table_1">
                                    <thead>
                                        <tr>
											<th>S.NO</th>
												<th>Client Name</th>
												<th>Service type</th>
												<th>Mobile No</th>
												<th>Email Id</th>
												<th>Amount</th>
												<th>Address</th>
												<th>Remark</th>
												<th>Company</th>
												<th>Salary</th>
												<th>Follow up</th>
												<!--<th>Status</th>-->
												<th>Reg date</th>
												<!--<th>Status</th>-->
												<th>Remark edit</th>
											</tr>
                                    </thead>
                                    <tbody>
                                         <?php
											if(!empty($_GET['followup'])){
												$datee = date('Y-m-d',strtotime($_GET['followup']));
											}else{
												$datee = date('Y-m-d');
											}
											$startdate = !empty($_GET['sdate']) ? $_GET['sdate'] : $monthstartdate ;
							                $enddate = !empty($_GET['sdate']) ? $_GET['edate'] : $currentdate ;
							               if(!empty($_GET['employeeid'])){
							 $empid = ($_GET['employeeid']);
							$sql = runloopQuery("SELECT * FROM track_work where employee_id = '".$empid."' and followup >='".$startdate."' and followup <='".$enddate."' order by ID desc");
						 }
						 else{
							$sql = runloopQuery("SELECT * FROM track_work where employee_id = '".$userid."' and followup >='".$startdate."' and followup <='".$enddate."' UNION 
							SELECT * FROM track_work 
							where employee_id in 
							(select ID from employee where leader = '".$usedetails['unique_id']."') and followup >='".$startdate."' and followup <='".$enddate."' ");
						 } 
							                

   $x=1;  foreach($sql as $row)
		{
?>
<tr>
<td><?php echo  $x;?></td>
<td><?php echo $row["clientname"];?></td>
<td><?php echo $row["selecttype"];?></td>
<td><?php echo $row["mobile"];?></td>
<td><?php echo $row["email"];?></td>
<td><?php echo number_format((float)$row["amount"],2);?></td>
<td><?php echo $row["address"];?></td>
<td><?php echo $row["remark"];?></td>
<td><?php echo $row["company"];?></td>
<td><?php echo number_format((float)$row["income"],2);?></td>
<td><?php echo date('d F Y',strtotime($row["followup"]));?></td>
<!--<td><?php echo $row["status"];?></td>-->
<td><?php echo reg_date($row["reg_date"]);?></td> 
<!--<td><a href="?status=<?php echo $row["ID"];?>&statuscontent=<?php echo $row["status"];?>"><i class="fa fa-pencil"></i></a></td>-->
<td><a href="?remark=<?php echo $row["ID"];?>"><i class="fa fa-pencil"></i></a></td>
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
    <!-- Custom Theme JavaScript -->
    	<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" type="text/javascript"></script>
	<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>

	<!--end::Page Scripts -->


  <script>	
	$("#kt_table_1").DataTable();
	</script>





<?php if(!empty($_GET['remark'])){ 
$sql = runQuery("SELECT * FROM track_work where ID = '".$_GET['remark']."' order by ID desc");

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
				<input type="date" placeholder="Follow up date" name="followup" value="<?php echo $sql['followup'];?>" class="form-control form-control-line" required> 
			</div>
			<div class="form-group">
				<label>Remark</label>
				<textarea class="form-control"  name="remark" required ><?php echo $sql['remark'];?></textarea>
			</div>
			<div class="form-group">
			 <button type="submit" class="btn btn-success" value="<?php echo $sql['ID'];?>" name="remarksubmit" >Submit</button>
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
	<?php }?>

</body>

</html>