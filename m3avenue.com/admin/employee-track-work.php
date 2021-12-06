<?php 
session_start(); 
error_reporting(E_ALL); 
include('../functions.php'); 
$userid = current_adminid(); 
if(empty($userid)){ 
	header("Location: index.php"); 
}
$usedetails = runQuery("select * from employee where ID = '".$userid."'");

$monthstartdate = date('Y-m-01');
$currentdate = date('Y-m-d');

$teamdetails = runloopQuery("select *,concat(fname,' ',lname) name from employee order by ID desc");
$teamselect = array_column($teamdetails,'name',"unique_id");

if(!empty($_GET['delete'])){
	$sql = "DELETE FROM track_work WHERE ID=".$_GET['delete']."";
	if ($conn->query($sql) === TRUE) { 
		header("Location: employee-track-work.php?employeeid=".$_GET['employeeid']."&success=success"); 
	} else {
		echo "Error deleting record: " . $conn->error;
	}
}




$message = '';
if(!empty($_POST['submit'])){
	if(!empty($_POST['clientname'])){
	   $pagerarray  = array();
	   $empid = mysqli_real_escape_string($conn,$_REQUEST['employeeid']);
			$employeeid = runQuery("select * from employee where unique_id = '".$empid."'");
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
				$message .="  client Name  Field is Empty";
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
				header("Location: employee-track-work.php?success=success");
				}
		
	 }else{

$message .= $invalidme;

}
	 }else{

$message .=" Lead Name Field is Empty";

}

}

if(!empty($_POST['remarksubmit']) && !empty($_POST['remark'])){
	$statuscontent = mysqli_real_escape_string($conn,$_POST['remark']);
	
	$pagerarray['track_work_id'] = $_POST['remarksubmit'];
	$pagerarray['employee_id'] = '0';
	$pagerarray['remark_desc'] = $statuscontent;
	
	$result = insertQuery($pagerarray,'remarks_history');
	$followup = date('Y-m-d',strtotime($_POST['followup']));
$sql = "UPDATE track_work set remark = '".$statuscontent."',followup = '".$followup."' WHERE ID=".$_POST['remarksubmit']."";
	$conn->query($sql);
	if(!$result){
			header("Location: employee-track-work.php?success=success");
	}
}
?>
<!DOCTYPE html>

<html lang="en">
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
	<!-- begin::Head -->
	<head>

		<!--begin::Base Path (base relative path for assets of this page) -->
		

		<!--end::Base Path -->
		<meta charset="utf-8" />
		<title>M3 | Dashboard</title>
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
								<h3 class="kt-portlet__head-title">Employee report</h3>
							</div>
						</div>
                                
		<div class="kt-portlet__body">
					<!-- <form method="get" action="">
					<div class="form-group row">
				
						<label for="example-text-input" class="col-2 col-form-label">Employee</label>
                        <div class="col-4">
                            <input class="form-control" type="text" placeholder="Employee id"  value="<?php echo !empty($_GET['employeeid']) ? $_GET['employeeid']: '';?>" name="employeeid" id="example-text-input">
                        </div>
                       <div class="col-4">
                             <button type="submit"  class="btn btn-brand btn-elevate btn-pill">Submit</button>
						</div> 
							<?php 
									  if(!empty($_GET['employeeid'])){
										  $empid = employee_id($_GET['employeeid']); 
						
						?>   
                          <div class="col-2"> 
						  <a href="csv-emp-track-work.php?emp=<?php echo  $empid; ?>" class="btn btn-primary" >Download</a></div>
									  <?php }?>
                    </div> 
					</form> -->
					<form method="get" action="">
										<div class="form-group row">

										<label for="example-text-input" class="col-md-1 col-form-label">Employee</label>
										<div class="col-md-2">
										    <select name="employeeid" class="form-control select2" >
							<option value="">select</option>
							<?php foreach($teamselect as $key => $value) {?>
								<option value="<?= $key ;?>" <?php if(isset($_REQUEST['employeeid'])) { if($_REQUEST['employeeid'] == $key) { ?> selected <?php  } } ?>><?= $value ;?></option>
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

                        		<div class="table table-responsive">
									<table class="table  table-bordered table-hover table-checkable" id="kt_table_1">
                                    <thead>
                                        <tr>
											<th>S.NO</th>
											<th>Employee Id</th>
											<th>Manager</th>
												<th>Lead Name</th>
												<th>select type</th>
												<th>Mobile No</th>
												<th>Email Id</th>
												<th>Amount</th>
												<th>Location</th>
												<th>Remark</th>
												<th>Status</th>
												<th>Reg date</th>
												<th>Delete</th>
											</tr>
                                    </thead>
                                    <tbody>
                                         <?php
                                         $startdate = !empty($_GET['sdate']) ? $_GET['sdate'] : $monthstartdate ;
							             $enddate = !empty($_GET['sdate']) ? $_GET['edate'] : $currentdate ;
									  if(!empty($_GET['employeeid'])){
									      $empid = employee_id($_GET['employeeid']);
$sql = runloopQuery("SELECT * FROM track_work where employee_id = '".$empid."' and doc_status in ('0','1') and date(reg_date) between '".$startdate."' and '".$enddate."'  order by ID desc");
}
else{
$sql = runloopQuery("SELECT * FROM track_work  where date(reg_date) between '".$startdate."' and '".$enddate."' and doc_status in ('0','1') order by ID desc");
    
}

   $x=1;  foreach($sql as $row)
		{
?>
<tr>
<td><?php echo  $x;?></td>
<td><?php echo employee_details($row["employee_id"],'fname').' '. employee_details($row["employee_id"],'lname').' ('.employee_details($row["employee_id"],'unique_id').')' ;?></td>
<td><?= lead_details(employee_details($row["employee_id"],'leader'),'fname'). ' ' .lead_details(employee_details($row["employee_id"],'leader'),'lname') ; ?></td>
<td><?php echo $row["clientname"];?></td>
<td><?php echo $row["selecttype"];?></td>
<td><?php echo $row["mobile"];?></td>
<td><?php echo $row["email"];?></td>
<td><?php echo $row["amount"];?></td>
<td><?php echo $row["address"];?></td>
<td><?php echo $row["remark"];?></td>
<!--<td><?php echo $row["status"];?></td>-->
<td>
<?php if($row['doc_status'] == '1'  || $row['doc_status'] == '2') { ?>
	<button type="button" class="btn btn-primary" onclick="preview_doc(<?php echo $row['ID'];?>)">
  Preview 
</button>

<a href="../makezip/makezipfile.php?id=<?php echo $row['ID'];?>" class="btn btn-primary mt-2">Download</a>
<?php  } else {?>
    <span class="badge badge-warning">Documents Pending</span>
<?php } ?>
</td>
<td><?php echo reg_date($row["reg_date"]);?></td>

<!--<td><a href="edit-trackwork.php?edit=<?php echo $row["ID"];?>">Edit</a></td>-->
<td><a onclick="return confirm('Are you sure want to delete??');" href="?employeeid=<?php echo $_GET['employeeid'];?>&delete=<?php echo $row["ID"];?>">Delete</a>
<a href="?remark=<?php echo $row["ID"];?>"><i class="fa fa-pencil icon-border">Remark</i></a>
<a href="?editlead=<?php echo $row["ID"];?>"><i class="fa fa-pencil icon-border">Edit</i></a>

</td>
</tr>
<?php
     $x++; }
?>
                                        
                                    </tbody>
                                </table>
								 
									  <?php //}?>
                                </div>		<!--end: Datatable -->
						</div>
						 
						<!-- end:: Content -->
			</div>  
					<?php 
									  if(!empty($_GET['employeeid'])){
										  $empid = employee_id($_GET['employeeid']); 
						
						?>   
				<div class="kt-portlet kt-portlet--mobile">
					  
						<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<h3 class="kt-portlet__head-title">Employee report</h3>
							</div>
						</div>
								
		<div class="kt-portlet__body"> 
							 <div class="row">
							 <div class="col-12">
							<div id="container" style="height: 400px; margin: 0 auto"></div>
							
									</div>
									</div>
									
					   <div class="row">
		 <div class="col-6">
			<div id="chartone" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
			
			</div>
		 <div class="col-6">
			<div id="charttwo" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
			
			</div>
			</div>
							 <div class="row">
		 <div class="col-6">
			<div id="chartleads" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
			
			</div> 
			<div class="col-6"> 
			 <div id="emppoints" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
			</div> 
			</div>
									<!--end: Datatable -->
							</div>
						 
						<!-- end:: Content -->
			</div>  

									  <?php }?>
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
      <div class="modal-body" id="docbody">
        
	  </div>
	  <div class="modal-footer">
      <button type="submit" name="submit" value="submit" id="convertid" class="btn btn-brand btn-elevate btn-pill">Convert To Client</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </form>
    </div>
	</div>
	
  </div>
</div>



	<?php include("footerscripts.php");?>
	
	<?php 
		  if(!empty($_GET['employeeid'])){
			  $empid = employee_id($_GET['employeeid']); 

	$totalarray = array();
	
	
	if(!empty($_GET['year'])&&!empty($_GET['month'])){ 	
	$year =  $_GET['year'];
		$month =  $_GET['month'];
 
   for($x=1;$x<=31;$x++){
	   $dateslist[] = $x;
	   $datet = strlen($x)<2 ? '0'.$x : $x; 
	   $fromdate = $year.'-'.$month."-".$datet; 
	   $todate = $year.'-'.$month."-".$datet;  
			$sql = runQuery("SELECT count(*) as count FROM track_work where employee_id = '".$empid."' and reg_date >='".$fromdate." 00:00:00' and reg_date <='".$todate." 23:59:59' order by ID desc"); 
		if(!empty($sql)){
			$totalarray[] = $sql['count'];
		}else{
			$totalarray[] = 0;
		}
   }
	$datscomma = implode("', '",$dateslist);
	$datetext = $year.' '.date("F", mktime(0, 0, 0, $month, 10));
   ?>
   <script>
   Highcharts.chart('container', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Leads'
    },
    subtitle: {
        text: '<?php echo $datetext;?> Leads'
    },
    xAxis: {
        categories: ['<?php echo $datscomma;?>']
    },
    yAxis: {
        title: {
            text: 'Leads'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'My Leads',
        data: [<?php echo implode(", ",$totalarray);?>]
    }]
});
   </script>
   
	<?php 
	
	}elseif(!empty($_GET['year'])&&empty($_GET['month'])){ 
		if(!empty($_GET['year'])){
			$year =   $_GET['year'];
		}else{ 
			$year =   date('Y');
		}
		
   for($x=1;$x<=12;$x++){
	   $fromdate = $year.'-'.date("m", mktime(0, 0, 0, $x, 10))."-01"; 
	   $todate = $year.'-'.date("m", mktime(0, 0, 0, $x, 10))."-31"; 
			$sql = runQuery("SELECT count(*) as count FROM track_work where employee_id = '".$empid."' and reg_date >='".$fromdate." 00:00:00' and reg_date <='".$todate." 23:59:59' order by ID desc");
		if(!empty($sql)){
			$totalarray[] = $sql['count'];
		}else{
			$totalarray[] = 0;
		}
   }?>
   <script>
   Highcharts.chart('container', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Leads'
    },
    subtitle: {
        text: '<?php echo $year;?> Leads'
    },
    xAxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    yAxis: {
        title: {
            text: 'Leads'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'My Leads',
        data: [<?php echo implode(", ",$totalarray);?>]
    }]
});
   </script>
   
	<?php }else{  
	$year =  date('Y');
		$month =   date('m');
 
   for($x=1;$x<=31;$x++){
	   $dateslist[] = $x;
	   $datet = strlen($x)<2 ? '0'.$x : $x; 
	   $fromdate = $year.'-'.$month."-".$datet; 
	   $todate = $year.'-'.$month."-".$datet;  
			$sql = runQuery("SELECT count(*) as count FROM track_work where employee_id = '".$empid."' and reg_date >='".$fromdate." 00:00:00' and reg_date <='".$todate." 23:59:59' order by ID desc"); 
		if(!empty($sql)){
			$totalarray[] = $sql['count'];
		}else{
			$totalarray[] = 0;
		}
   }
	$datscomma = implode("', '",$dateslist);
	$datetext = $year.' '.date("F", mktime(0, 0, 0, $month, 10));
   ?>
   <script>
   Highcharts.chart('container', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Leads'
    },
    subtitle: {
        text: '<?php echo $datetext;?> Leads'
    },
    xAxis: {
        categories: ['<?php echo $datscomma;?>']
    },
    yAxis: {
        title: {
            text: 'Leads'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'My Leads',
        data: [<?php echo implode(", ",$totalarray);?>]
    }]
});
   </script>
	
	<?php }?>
	
	<?php 
	
	if(!empty($_GET['year'])&&!empty($_GET['month'])){
		$year = $_GET['year'];
		$month = $_GET['month'];
		 $fromdate = $year.'-'.date("m", mktime(0, 0, 0, $month, 10))."-01"; 
	   $todate = $year.'-'.date("m", mktime(0, 0, 0, $month, 10))."-31"; 
	   $datatext = date('Y F',strtotime($fromdate));
	}else{
		$year =  date('Y');
		$month =   date('m');
		 $fromdate = $year.'-'.$month."-01"; 
	   $todate = $year.'-'.$month."-31"; 
	   $datatext = date('Y F',strtotime($fromdate));
	}
	
	$pendingclients = runQuery("select count(*) as count from clients where employee_id = '".$empid."' and status = 'Pending' and mod_date >='".$fromdate." 00:00:00' and mod_date <='".$todate." 23:59:59' order by ID desc");
	$approvedclients = runQuery("select count(*) as count from clients where employee_id = '".$empid."' and status = 'Approved' and mod_date >='".$fromdate." 00:00:00' and mod_date <='".$todate." 23:59:59' order by ID desc");
	$rejectclients = runQuery("select count(*) as count from clients where employee_id = '".$empid."' and status = 'Reject' and mod_date >='".$fromdate." 00:00:00' and mod_date <='".$todate." 23:59:59' order by ID desc"); 
	
	
	$totalclientsarray = runloopQuery("select * from clients where employee_id = '".$empid."' and mod_date >='".$fromdate." 00:00:00' and mod_date <='".$todate." 23:59:59' order by ID desc");
			$pendingclientspoints = $approvedclientspoints = $rejectclientspoints = 0;
					$appamount =$rejectamount = 0;
			$x=1;  foreach($totalclientsarray as $totalclient){
				switch($totalclient["status"]){
					case 'Pending': 
					$pendingamount = $totalclient["loanamount"];
					$pendingclientspoints += (int)points($pendingamount,$totalclient["pointstype"]); 
					break;
					case 'Approved':  
					$appamount = $totalclient["loanamount"];
					$approvedclientspoints += (int)points($appamount,$totalclient["pointstype"]);
					break;
					case 'Reject':  
					$rejectamount = $totalclient["loanamount"];
					$rejectclientspoints += (int)points($rejectamount,$totalclient["pointstype"]);
					break;
				} 
			} 
			$sourcepoints = array();
	 $worksourcearray = runloopQuery("select * from worksource order by ID desc");
		 foreach($worksourcearray as $worksourcearray){
			 $srctitle = $worksourcearray['title'];
			 $sorcount = runQuery("select  count(*) as count from track_work where employee_id = '".$empid."' and source = '".$srctitle."'");
			 $sourcepoints[$srctitle] = $sorcount['count'];
			 
		 } 
	?>
	
	
	<script>
			// Build the chart
Highcharts.chart('chartone', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: '<?php echo $datatext;?> My Clients'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Clients',
        colorByPoint: true,
        data: [{
            name: 'Pending',
            y: <?php echo $pendingclients['count'];?>
        }, {
            name: 'Approved',
            y: <?php echo $approvedclients['count'];?>
        }, {
            name: 'Reject',
            y: <?php echo $rejectclients['count'];?>
        } ]
    }]
});
			// Build the chart
Highcharts.chart('charttwo', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: '<?php echo $datatext;?> Clients Points'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Clients Points',
        colorByPoint: true,
        data: [{
            name: 'Pending Points',
            y: <?php echo $pendingclientspoints;?>
        }, {
            name: 'Approved Points',
            y: <?php echo $approvedclientspoints;?>
        }, {
            name: 'Reject Points',
            y: <?php echo $rejectclientspoints;?>
        } ]
    }]
});
			// Build the chart
Highcharts.chart('chartleads', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: '<?php echo $datatext;?> Source Leads'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Lead Source',
        colorByPoint: true,
        data: [
		<?php 
		$latarary = array_keys($sourcepoints);
		$last_key = end($latarary);
		foreach($sourcepoints as  $sourcekey =>  $sourcevaue){?>
			{
				name: '<?php echo $sourcekey;?>',
				y: <?php echo $sourcevaue;?>
			} <?php if ($sourcekey!=$last_key) { ?>,<?php }?>
		<?php }?>
		
		]
    }]
});
		</script>
		<?php 
		$totalemparray = array();
		 for($x=1;$x<=12;$x++){
	   $fromdate = $year.'-'.date("m", mktime(0, 0, 0, $x, 10))."-01"; 
	   $todate = $year.'-'.date("m", mktime(0, 0, 0, $month, 10))."-31"; 
			$sql = runQuery("SELECT count(*) as count FROM employee where leader = '".$_GET['employeeid']."' and reg_date >='".$fromdate." 00:00:00' and reg_date <='".$todate." 23:59:59' order by ID desc");
		if(!empty($sql)){
			$totalemparray[] = $sql['count'];
		}else{
			$totalemparray[] = 0;
		}
   }
		 ?>
		<script>
		 Highcharts.chart('emppoints', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Team Count'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    yAxis: {
        title: {
            text: 'Team Count'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'Month',
        data: [<?php echo implode(", ",$totalemparray);?>]
    }]
});
</script>
<?php } ?>
<script>
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
        
		$("#docbody").html('');
		$("#docbody").append('<div class="row"><div class="col">');
		$("#docbody").append(`<table width="100%" id="doctable"><tr><th class="tablestyle">S.No</th><th class="tablestyle">File Name</th><th class="tablestyle">File</th></tr>`);
        for(i=0; i < result.length ; i++) {
		//	$("#docbody").append(`<a target="_blank" href="./../upload_documents/${trackworkid}/${result[i]['doc_name']}"><img src="./../upload_documents/${trackworkid}/${result[i]['doc_name']}"
		//	 class="borders" style="display: inline-block;width: 70px;height: 70px;margin: 6px;" ></a>`);
				    $("#doctable").append(`<tr><td class="tablestyle">${(i+1)}</td><td class="tablestyle">${result[i]['file_name']}</td><td class="tablestyle"><a target="_blank" href="./../upload_documents/${trackworkid}/${result[i]['doc_name']}"><img src="./../upload_documents/${trackworkid}/${result[i]['doc_name']}"
			 class="borders" style="display: inline-block;width: 70px;height: 70px;margin: 6px;"></a></td></tr>`);

            
        }
		$("table#doctable tr:last").after('</table>');
        $("#docbody").append('</div></div>');
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
        <select  required class="form-control" name="pointstype" onchange="getvariant()" id="pointsetpopupid">
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
                        <select class="form-control" type="text" name="variant"  id="variantpopupid" onchange="getvariantlocation()">
                            <option value="">---select---</option>
                        </select>                </div>
                
			  </div>
			  <div class="form-group row">
			  <label class="col-md-2">Variant Location:</label>
				<div class="col-md-4">
                            <select class="form-control" name="variant_location"  id="variantlocpopupid">
                                <option value="">---select---</option>
                            </select>                </div>
			  </div>
        <div class="row mt-3"><div class="col">
                        
                            </div></div>
        
        `);
        }else{
            $("#convertid").hide();
        }
        
	 }					
	 });

}


		</script>
	<?php if(!empty($_GET['remark'])){ 
	    
$sql = runQuery("SELECT * FROM track_work where ID = '".$_REQUEST['remark']."' order by ID desc");
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
	               <td><?php echo ($r+1); ?></td>
	               <td><?php echo @employee_details($remarks_history[$r]['employee_id'],'fname')  ??  'admin'; ?></td>
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
	<div class="modal" id="editleadmodal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Lead</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
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
                                </form>
      </div>
      <div class="modal-footer">
	  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success" id="updatelead"  value="submit">Update Lead</button>
      </div>
    </div>
  </div>
</div>
<script>
			$("#editleadmodal").modal('show');
		</script>
<?php } ?>
<script>
$("#updatelead").click(function(){
   $("#editleadformid").submit(); 
});
$(document).ready(function() {
    $('#employeeid').select2();
});

function getvariant()
{
    var pointsetid = $("#pointsetpopupid").val();
    $.ajax({				
	 url : 'ajax/commonajax.php',		
	 
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
	 url : 'ajax/commonajax.php',		
	 
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

	<!-- end::Body -->
</html>