<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');
include('../inc/PHPExcel.php');
include('../inc/PHPExcel/IOFactory.php'); 

$userid = current_adminid(); 

if(empty($userid)){

	header("Location: index.php");

}
$campainid = !empty($_GET['campaign']) ? $_GET['campaign'] : '';
$campaignsheet = !empty($_GET['campaignsheet']) ? $_GET['campaignsheet'] : '';
if(empty($userid)){

	header("Location: campaigns-list.php");

}
$compaindetails = runQuery("select * from campaigns where ID = '".$campainid."'");
$compainsheetdetails = runQuery("select * from campaigns_excell where ID = '".$campaignsheet."'");
if(empty($compaindetails)){ 
	header("Location: campaigns-list.php");

} 
if(!empty($_GET['delete'])){
	$sql = "DELETE FROM campaigns_users WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: upload-campaign-excell.php?campaign=".$_GET['campaign']."&dsuccess=success");
   
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
							<?php if(!empty($compainsheetdetails['ID'])){?>
							<h3 class="kt-portlet__head-title"><?php echo $compaindetails['title'];?> With sheet name <?php echo $compainsheetdetails['excellname'];?></h3>
							<?php }else{ ?>
							<h3 class="kt-portlet__head-title">Report for <?php echo $compaindetails['title'];?></h3>
							<?php }?>
							<?php if(!empty($compainsheetdetails)){?>
								<a href="csv-campaign-report.php?campaign=<?php echo $compaindetails["ID"];?>&sheet=<?php echo $compainsheetdetails["ID"] ?: 0;?>" class="btn btn-primary" >Download</a>
							<?php }else{ ?>
								<a href="csv-campaign-report.php?campaign=<?php echo $compaindetails["ID"];?>" class="btn btn-primary" >Download</a>
							<?php }?>
						</div>
					<div class="table table-responsive">
						<table id="example3" style="min-width: 845px" class="table table-bordered table-hover table-checkable display">
						<thead>
						<tr>
							<th>S.No</th> 
                            <th>Executive</th>    
                            <th>Full Name,Mobile</th>    
                            <th>Call duration</th>  
                            <th>Call status</th>  
                            <th>Call message</th>  
                            <th>Called on</th>  
                            <th>Reg date</th>  
                            <th>Action</th>  
						</tr> 
							</thead>
							<tbody>
								  <?php
								   if(!empty($compainsheetdetails['ID'])){
							$sql = runloopQuery("SELECT cu.*,e.name as ename FROM campaigns_users cu,executive e where cu.executive_id = e.ID and cu.campaign_id = '".$compaindetails['ID']."' and cu.campaignexcell_id = '".$compainsheetdetails['ID']."' order by cu.ID desc");
								   }else{
							$sql = runloopQuery("SELECT cu.*,e.name as ename FROM campaigns_users cu,executive e where cu.executive_id = e.ID and cu.campaign_id = '".$compaindetails['ID']."'  order by cu.ID desc");
								   }
							   $x=1;  foreach($sql as $row)
									{
							?>
							<tr>
							<td><?php echo  $x;?></td>  
							<td><?php echo $row["ename"];?> </td>
							<td><?php echo $row["name"];?> </td>
							<br/><?php echo $row["mobile"];?></td>   
							<td><?php echo $row["callduration"];?> Sec</td> 
							<td><?php echo feedbackstatus($row["callstatus"]);?></td> 
							<td><?php echo $row["callmessage"];?></td> 
							<td><?php echo reg_date($row["duration"]);?></td> 
							<td><?php echo reg_date($row["reg_date"]);?></td>
							<td><a onclick="return confirm('Are you sure want to delete??');"  href="?campaign=<?php echo $compaindetails['ID']; ?>&delete=<?php echo $row["ID"];?>">Delete</a></td>
							</tr>
							<?php
								 $x++; }
							?>
													  </tbody>
													  
							<tbody>
							 <?php ?>
							</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
			 						
				
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->


	</div>
	
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <?php include('footer_scripts.php');?>

</body>
</html>