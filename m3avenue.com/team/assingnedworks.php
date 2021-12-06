<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');
$userid = current_employeeid();
if(empty($userid)){
	header("Location:index.php");
	}

$usedetails = runQuery("select * from employee where ID = '".$userid."'");
$message = '';
if(!empty($_POST['submit'])){
			if(!empty($_POST['clientname'])){
	   $pagerarray  = array(); 
	   
              $pagerarray['employee_id'] = $userid;
              $pagerarray['clientname'] = mysqli_real_escape_string($conn,$_POST['clientname']);
                $pagerarray['selecttype'] = mysqli_real_escape_string($conn,$_POST['selecttype']);
				 $pagerarray['mobile'] = mysqli_real_escape_string($conn,$_POST['mobile']); 
                $pagerarray['email'] = mysqli_real_escape_string($conn,$_POST['email']); 
                $pagerarray['amount'] = mysqli_real_escape_string($conn,$_POST['amount']);
                $pagerarray['address'] = mysqli_real_escape_string($conn,$_POST['address']);
                $pagerarray['remark'] = mysqli_real_escape_string($conn,$_POST['remark']);
                $pagerarray['company'] = mysqli_real_escape_string($conn,$_POST['company']);
                $pagerarray['income'] = mysqli_real_escape_string($conn,$_POST['income']);
                $pagerarray['assingedto'] = mysqli_real_escape_string($conn,$_POST['assingedto']);
                $pagerarray['source'] = mysqli_real_escape_string($conn,$_POST['source']);
                $pagerarray['followup'] = date('Y-m-d',strtotime($_POST['followup']));
                $pagerarray['status'] = 'Yes';
	            $result = insertQuery($pagerarray,'track_work');
				if(!$result){
					header("Location: track-work.php?success=success");
                    }
	    
		 	}else{

		$message .=" Client Name Field is Empty";

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
if(!empty($_POST['remarksubmit'])){
	$statuscontent = mysqli_real_escape_string($conn,$_POST['remark']);
	$followup = date('Y-m-d',strtotime($_POST['followup']));
	$sql = "UPDATE track_work set remark = '".$statuscontent."',followup = '".$followup."' WHERE ID=".$_POST['remarksubmit']."";

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
                        <h4 class="page-title">Assingned works</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
					<ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li class="active">Assingned works</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div> 
                <!-- /row -->
 
                <!-- /row -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <h3 class="box-title">Assingned works</h3>
                            <div class="table-responsive">
                                <table class="table" id="kt_table_1" >
                                    <thead>
                                        <tr>
											<th>S.NO</th>
												<th>Client Name</th>
												<th>Service Type</th>
												<th>Mobile No</th>
												<th>Email Id</th>
												<th>Amount</th>
												<th>Address</th>
												<th>Remark</th>
												<th>Company</th>
												<th>Salary</th>
												<th>Follow up</th>
												<th>Assingned</th>
												<th>Source</th>
												<th>Reg date</th>
												<!--<th>Status</th>-->
												<th>Remark edit</th>
											</tr>
                                    </thead>
                                    <tbody>
                                         <?php
$sql = runloopQuery("SELECT * FROM track_work where assingedto = '".$usedetails['unique_id']."' order by ID desc");


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
<td><?php echo $row["assingedto"];?></td>
<td><?php echo $row["source"];?></td>
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
	
   <?php include("footerscripts.php");?>
    
	<!--end::Page Scripts -->
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
				<textarea class="form-control"  name="remark" ><?php echo $sql['remark'];?></textarea>
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
