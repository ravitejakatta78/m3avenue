<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_adminid(); 

if(empty($userid)){

	header("Location: index.php");

}

$executive = $_GET['executive']; 

if(empty($executive)){

	header("Location: executive-list.php");

}

$message = '';
if(!empty($_POST['submit'])){
			if(!empty($_POST['name'])){
	   $pagerarray  = array();

		  if (!file_exists('../executiveimage')) {	
		mkdir('../executiveimage', 0777, true);	
		}
	    $target_dir = '../executiveimage/';									

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
				$uniqueusers = (int)runQuery("select max(ID) as id from executive order by ID desc")['id'];
				$newuniquid = $uniqueusers+1;
				 
                $pagerarray['user_id'] = $userid; 
                $pagerarray['user_role'] = 'superadmin'; 
                $pagerarray['employee_id'] = mysqli_real_escape_string($conn,$_POST['employee']);   
                $pagerarray['name'] = mysqli_real_escape_string($conn,$_POST['name']); 
                $pagerarray['unique_id'] = 'M3E'.sprintf('%05d',$newuniquid);
                $pagerarray['email'] = mysqli_real_escape_string($conn,$_POST['email']);
                $pagerarray['mobile'] = mysqli_real_escape_string($conn,$_POST['mobile']);
                $pagerarray['password'] = password_hash(trim($_POST['password']),PASSWORD_DEFAULT); 
                $pagerarray['location'] = mysqli_real_escape_string($conn,$_POST['location']);  
                $pagerarray['status'] = 1; 
	            $result = insertQuery($pagerarray,'executive');
				if(!$result){
					header("Location: executive-list.php?success=success");
                    }
	    
		 	}else{

		$message .=" First Name Field is Empty";

	}

}

if(!empty($_GET['delete'])){
	$sql = "DELETE FROM executive WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: executive-list.php?success=success");
   
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
									<div class="col-12">
										<h3 class="kt-portlet__head-title">Executive Reports</h3>
									</div> 
										<a href="executive-list.php" class="btn btn-primary pull-right" >Executive List</a>  
							</div>
						</div>     
							<div class="kt-portlet__body">
							<form method="get" action="" >
							<div class="row from-group">
							<div class="col-3">
									<select class="form-control" name="month" >
									<option value="">-Select--</option>
									<?php for($i=1;$i<=12;$i++){?>
											<option value="<?php echo $i;?>" <?php if(!empty($_GET['month'])&&$_GET['month']==$i){ echo 'selected';}?>><?php echo date('F',mktime(0,0,0,$i,10));?></option>
									<?php	} 	?>
									</select>
								
								</div>  
							<div class="col-3"> 
									<select class="form-control" name="year" >
									<option value="">-Select--</option>
									<?php for($y=2019;$y<=2030;$y++){?>
											<option value="<?php echo $y;?>" <?php if(!empty($_GET['month'])&&$_GET['year']==$y){ echo 'selected';}?>><?php echo $y;?></option>
									<?php	} 	?>
									</select>
									
								</div>  
							<div class="col-3">
									<select class="form-control" name="campaign" >
									<option value="">-Select--</option>
									<?php $campaignsarray = runloopQuery("select ID,title from campaigns where find_in_set('".$executive."',executive) order by ID asc");
										foreach($campaignsarray as $campaigns){ ?>
											<option value="<?php echo $campaigns['ID'];?>" <?php if(!empty($_GET['campaign'])&&$_GET['campaign']==$campaigns['ID']){ echo 'selected';}?>><?php echo $campaigns["title"];?></option>
									<?php	} 	?>
									</select>
								
								</div> 
							<div class="col-3">
								<input type="submit" class="btn btn-success" Value="Submit" />
								<input type="hidden" name="executive" Value="<?php echo $executive;?>" />
								</div>  
								</div>
							</form>
									<div class="row">
									<?php $wherearray = '';
										if(!empty($_GET['campaign'])){
											$campaignid = mysqli_real_escape_string($conn,$_GET['campaign']);
											$wherearray = " campaign_id = '".$campaignid."' and ";
										}
										if(!empty($_GET['month'])&&!empty($_GET['month'])){
											$month = mysqli_real_escape_string($conn,$_GET['month']);
											$year = mysqli_real_escape_string($conn,$_GET['year']); 
										}else{
											$month = date('n');
											$year = date('Y'); 
										}
										
											$feedbackarray = runloopQuery("select ID,title from feedbackoptions order by ID asc");
										$bargraphoptionstitle = $piegraphoptionstitle = array();
										$days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
											for($i=1;$i<=$days;$i++){
												$totaldays[] = (string)date("d",mktime(0,0,0,$month,$i,$year));  
											} 
											
										foreach($feedbackarray as $feedback){
															$bargraphoptions = array();
											$days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
											for($i=1;$i<=$days;$i++){ 
												$datecur = date("Y-m-d",mktime(0,0,0,$month,$i,$year)); 
												$bardata = runQuery("select count(*) as count from campaigns_users where $wherearray executive_id = '".$executive."' and callstatus = '".$feedback['ID']."' and duration >='".$datecur." 00:00:00'  and duration <='".$datecur." 23:59:59'")['count'];   
												$bargraphoptions[] = (int)$bardata;
											} 
											$bargraphoptionstitle[] = array("name"=>$feedback['title'],"data"=>$bargraphoptions);
											
											$first_second = date('01-m-Y 00:00:00', mktime(0,0,0,$month,10,$year));
											$last_second  = date('t-m-Y 23:59:59', mktime(0,0,0,$month,10,$year)); // A leap year!
											
											$bardata = runQuery("select count(*) as count from campaigns_users where $wherearray executive_id = '".$executive."' and callstatus = '".$feedback['ID']."' and duration >='".$first_second."'  and duration <='".$last_second."'")['count']; 
										 
											
											$piegraphoptionstitle[] = array("name"=>$feedback['title'],"y"=>(int)$bardata);
											
											
										}  
							
									?>
									<div class="col-12">
				<div class="col-lg-6 col-xl-4 order-lg-1 order-xl-1">
		<!--begin::Portlet-->
<div class="kt-portlet kt-portlet--height-fluid">
	<div class="kt-portlet__head kt-portlet__head--noborder">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">Calls Duration</h3>
		</div>
		<div class="kt-portlet__head-toolbar">
			<div class="kt-portlet__head-toolbar-wrapper"> 
			</div>		
		</div>
	</div>
	<div class="kt-portlet__body kt-portlet__body--fluid">
		<div class="kt-widget-19">
			<div class="kt-widget-19__title">
				<div class="kt-widget-19__label">
					<?php if(!empty($_GET['campaign'])){
							$callingtime = runQuery("select sum(callduration) as cduration from campaigns_users where campaign_id = '".$_GET['campaign']."' and executive_id = '".$executive."'")['cduration'];
						}else{
							$callingtime = runQuery("select sum(callduration) as cduration from campaigns_users where executive_id = '".$executive."'")['cduration']; 
						}
							echo  date('H:i:s',strtotime($callingtime)); 
						
						?>
				</div>
				<img class="kt-widget-19__bg" src="/keen/themes/keen/theme/demo1/dist/assets/media/misc/iconbox_bg.png" alt="bg">
			</div>
			<!--<div class="kt-widget-19__data"> 
				<div class="kt-widget-19__chart">
					<div class="kt-widget-19__bar"><div class="kt-widget-19__bar-45" data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title="" data-original-title="45"></div></div>
					<div class="kt-widget-19__bar"><div class="kt-widget-19__bar-95" data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title="" data-original-title="95"></div></div>
					<div class="kt-widget-19__bar"><div class="kt-widget-19__bar-63" data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title="" data-original-title="63"></div></div>
					<div class="kt-widget-19__bar"><div class="kt-widget-19__bar-11" data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title="" data-original-title="11"></div></div>
					<div class="kt-widget-19__bar"><div class="kt-widget-19__bar-46" data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title="" data-original-title="46"></div></div>
					<div class="kt-widget-19__bar"><div class="kt-widget-19__bar-88" data-toggle="kt-tooltip" data-skin="brand" data-placement="top" title="" data-original-title="88"></div></div>
				</div>
			</div> -->
		</div>
	</div>
</div>
<!--end::Portlet-->	</div>
			</div>
									<div class="col-12">
										<div id="barcharts"></div> 
									</div>
									<div class="col-6">
										<div id="piechartgrah"></div>
									</div>
								
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
		
Highcharts.chart('barcharts', {

    title: {
        text: 'Executive for the month <?php echo date('M Y',mktime(0,0,0,$month,10,$year));?>'
    },

    subtitle: {
        text: ''
    },

    yAxis: {
        title: {
            text: 'Calls'
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    plotOptions: {
        series: {
            label: {
                connectorAllowed: false
            },
            pointStart: 01
        }
    },

    series:  <?php echo json_encode($bargraphoptionstitle);?>,

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

}); 

// Build the chart
Highcharts.chart('piechartgrah', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Complete Feedbacks <?php echo date('M Y',mktime(0,0,0,$month,10,$year));?>'
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
        name: 'Feedbacks',
        colorByPoint: true,
        data: <?php echo json_encode($piegraphoptionstitle);?>
    }]
});  

	</script>
	</body>

	<!-- end::Body -->
</html>