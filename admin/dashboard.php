<?php 
session_start(); 
error_reporting(E_ALL);

include('../functions.php');
 
$userid = current_adminid(); 

if(empty($userid)){

	header("Location: index.php");

}

if(!empty($_GET['delete'])){
	$sql = "DELETE FROM loans WHERE id=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: loan.php?success=success");
   
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

		<!--begin::Fonts -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
			WebFont.load({
				google: {
					"families": ["Poppins:300,400,500,600,700"]
				},
				active: function() {
					sessionStorage.fonts = true;
				}
			});
		</script>

		<!--end::Fonts -->

		<!--begin::Page Vendors Styles(used by this page) -->
		<link href="./assets/vendors/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />

		<!--end::Page Vendors Styles -->

		<!--begin:: Global Mandatory Vendors -->
		<link href="./assets/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" type="text/css" />

		<!--end:: Global Mandatory Vendors -->

		<!--begin:: Global Optional Vendors -->
		<link href="./assets/vendors/general/tether/dist/css/tether.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/nouislider/distribute/nouislider.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/owl.carousel/dist/assets/owl.carousel.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/owl.carousel/dist/assets/owl.theme.default.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/dropzone/dist/dropzone.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/summernote/dist/summernote.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/bootstrap-markdown/css/bootstrap-markdown.min.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/animate.css/animate.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/toastr/build/toastr.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/morris.js/morris.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/sweetalert2/dist/sweetalert2.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/socicon/css/socicon.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/custom/vendors/line-awesome/css/line-awesome.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/custom/vendors/flaticon/flaticon.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/custom/vendors/flaticon2/flaticon.css" rel="stylesheet" type="text/css" />
		<link href="./assets/vendors/general/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />

		<!--end:: Global Optional Vendors -->

		<!--begin::Global Theme Styles(used by all pages) -->
		<link href="./assets/css/demo1/style.bundle.css" rel="stylesheet" type="text/css" />

		<!--end::Global Theme Styles -->

		<!--begin::Layout Skins(used by all pages) -->
		<link href="./assets/css/demo1/skins/header/base/light.css" rel="stylesheet" type="text/css" />
		<link href="./assets/css/demo1/skins/header/menu/light.css" rel="stylesheet" type="text/css" />
		<link href="./assets/css/demo1/skins/brand/navy.css" rel="stylesheet" type="text/css" />
		<link href="./assets/css/demo1/skins/aside/navy.css" rel="stylesheet" type="text/css" />
		<link href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />

		<!--end::Layout Skins -->
		<link rel="shortcut icon" href="./assets/media/logos/favicon.ico" />
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
								<h3 class="kt-portlet__head-title">Dashboard</h3>
							</div>
						</div> 
		<div class="kt-portlet__body"> 
								<form  method="get" action="" enctype="multipart/form-data">
									<div class="row">
									<div class="col-md-4">
								  <div class="form-group"> 
										<select class="form-control" name="year">
											<option value="">--Select--</option> 
											<option value="2019" <?php if(!empty($_GET['year'])&&$_GET['year']=='2019'){ echo 'selected';}?>>2019</option> 
											<option value="2020" <?php if(!empty($_GET['year'])&&$_GET['year']=='2020'){ echo 'selected';}?>>2020</option> 
											<option value="2021" <?php if(!empty($_GET['year'])&&$_GET['year']=='2021'){ echo 'selected';}?>>2021</option> 
											<option value="2022" <?php if(!empty($_GET['year'])&&$_GET['year']=='2022'){ echo 'selected';}?>>2022</option> 
										</select>
										</div> 
										</div> 
								<div class="col-md-4">
								  <div class="form-group"> 
										<select class="form-control" name="month">
											<option value="">--Select--</option>
											<?php for($i=1;$i<=12;$i++){?>
											<option value="<?php echo $i;?>" <?php if(!empty($_GET['month'])&&$_GET['month']==$i){ echo 'selected';}?> ><?php echo date('F', mktime(0, 0, 0, $i, 10));?></option>
											<?php }?>
										</select>
									</div>
									</div>
									<div class="col-md-4">
								  <div class="form-group"> 
										<button class="btn btn-success">Search</button>
										<a href="dashboard.php" class="btn btn-danger">Reset</a>
									</div>
									</div>
									</div>
									</form>
									
							 <div class="row">
					<div class="col-12">
							<div id="container" style="height: 400px; margin: 0 auto"></div>
									</div>
		 <div class="col-12">
							<div id="container1" style="height: 400px; margin: 0 auto"></div>
							
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
						<!-- end:: Content -->
			</div>  

						<!-- end:: Content -->
					</div>

					<!-- begin:: Footer -->
					<div class="kt-footer kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop">
						<!--<div class="kt-footer__copyright">
							2019&nbsp;&copy;&nbsp;<a href="#" target="_blank" class="kt-link">Company.com</a>
						</div>
						<div class="kt-footer__menu">
							<a href="http://keenthemes.com/keen" target="_blank" class="kt-footer__menu-link kt-link">About</a>
							<a href="http://keenthemes.com/keen" target="_blank" class="kt-footer__menu-link kt-link">Team</a>
							<a href="http://keenthemes.com/keen" target="_blank" class="kt-footer__menu-link kt-link">Contact</a>
						</div>-->
					</div>

					<!-- end:: Footer -->
				</div>

				<!-- end:: Wrapper -->
			</div>

			<!-- end:: Page -->
		</div>

		<!-- end:: Root -->
 
		<!-- end::Global Config -->

		<!--begin:: Global Mandatory Vendors -->
		<script src="./assets/vendors/general/jquery/dist/jquery.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/popper.js/dist/umd/popper.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/js-cookie/src/js.cookie.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/moment/min/moment.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/tooltip.js/dist/umd/tooltip.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/sticky-js/dist/sticky.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/wnumb/wNumb.js" type="text/javascript"></script>

		<!--end:: Global Mandatory Vendors -->

		<!--begin:: Global Optional Vendors -->
		<script src="./assets/vendors/general/jquery-form/dist/jquery.form.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/custom/js/vendors/bootstrap-datepicker.init.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/custom/js/vendors/bootstrap-timepicker.init.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/bootstrap-maxlength/src/bootstrap-maxlength.js" type="text/javascript"></script>
		<script src="./assets/vendors/custom/vendors/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/typeahead.js/dist/typeahead.bundle.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/handlebars/dist/handlebars.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/inputmask/dist/jquery.inputmask.bundle.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/inputmask/dist/inputmask/inputmask.date.extensions.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/inputmask/dist/inputmask/inputmask.numeric.extensions.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/nouislider/distribute/nouislider.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/owl.carousel/dist/owl.carousel.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/autosize/dist/autosize.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/clipboard/dist/clipboard.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/dropzone/dist/dropzone.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/summernote/dist/summernote.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/markdown/lib/markdown.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/bootstrap-markdown/js/bootstrap-markdown.js" type="text/javascript"></script>
		<script src="./assets/vendors/custom/js/vendors/bootstrap-markdown.init.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/jquery-validation/dist/jquery.validate.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/jquery-validation/dist/additional-methods.js" type="text/javascript"></script>
		<script src="./assets/vendors/custom/js/vendors/jquery-validation.init.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/raphael/raphael.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/morris.js/morris.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/chart.js/dist/Chart.bundle.js" type="text/javascript"></script>
		<script src="./assets/vendors/custom/vendors/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/custom/vendors/jquery-idletimer/idle-timer.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/waypoints/lib/jquery.waypoints.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/counterup/jquery.counterup.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/es6-promise-polyfill/promise.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/sweetalert2/dist/sweetalert2.min.js" type="text/javascript"></script>
		<script src="./assets/vendors/custom/js/vendors/sweetalert2.init.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/jquery.repeater/src/lib.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/jquery.repeater/src/jquery.input.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/jquery.repeater/src/repeater.js" type="text/javascript"></script>
		<script src="./assets/vendors/general/dompurify/dist/purify.js" type="text/javascript"></script>
		<script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" type="text/javascript"></script>
		<script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>

		<!--end:: Global Optional Vendors -->

		<!--begin::Global Theme Bundle(used by all pages) -->
		<script src="./assets/js/demo1/scripts.bundle.js" type="text/javascript"></script>

		<!--end::Global Theme Bundle -->

		<!--begin::Page Vendors(used by this page) -->
		<script src="./assets/vendors/custom/fullcalendar/fullcalendar.bundle.js" type="text/javascript"></script>

		<!--end::Page Vendors -->

		<!--begin::Page Scripts(used by this page) -->
		<script src="./assets/js/demo1/pages/dashboard.js" type="text/javascript"></script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

		<?php 
		 
	$totalarray = array();
	
	
	if(!empty($_GET['year'])&&!empty($_GET['month'])){ 	
	$year =  $_GET['year'];
		$month =  $_GET['month'];
 
   for($x=1;$x<=31;$x++){
	   $dateslist[] = $x;
	   $datet = strlen($x)<2 ? '0'.$x : $x; 
	   $fromdate = $year.'-'.$month."-".$datet; 
	   $todate = $year.'-'.$month."-".$datet;  
			$sql = runQuery("SELECT count(*) as count FROM track_work where  reg_date >='".$fromdate." 00:00:00' and reg_date <='".$todate." 23:59:59' order by ID desc"); 
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
        name: 'Leads',
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
			$sql = runQuery("SELECT count(*) as count FROM track_work where reg_date >='".$fromdate." 00:00:00' and reg_date <='".$todate." 23:59:59' order by ID desc");
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
			$sql = runQuery("SELECT count(*) as count FROM track_work where reg_date >='".$fromdate." 00:00:00' and reg_date <='".$todate." 23:59:59' order by ID desc"); 
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
	
	<?php } 
	 $totalemparray = array();
	
	
	if(!empty($_GET['year'])&&!empty($_GET['month'])){ 	
	$year =  $_GET['year'];
		$month =  $_GET['month'];
 
		$employeearray = runloopQuery("select ID,fname from employee order by ID desc");
		foreach($employeearray as $employee){ 
			   for($x=1;$x<=31;$x++){
				   $dateslist[] = $x;
				   $datet = strlen($x)<2 ? '0'.$x : $x; 
				   $fromdate = $year.'-'.$month."-".$datet; 
				   $todate = $year.'-'.$month."-".$datet;  
						$sql = runQuery("SELECT count(*) as count FROM track_work where employee_id = '".$employee['ID']."' and	reg_date >='".$fromdate." 00:00:00' and reg_date <='".$todate." 23:59:59' order by ID desc"); 
					
					if(!empty($sql)){
						$totalarray[] = $sql['count'];
					}else{
						$totalarray[] = 0;
					}
				}
		$totalemparray[$employee['fname']] = $totalarray;
   }
	$datscomma = implode("', '",$dateslist);
	$datetext = $year.' '.date("F", mktime(0, 0, 0, $month, 10));
   ?>
   <script>
   Highcharts.chart('container1', {
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
    series: [
	<?php 
		$latarary = array_keys($totalemparray);
		$last_key = end($latarary);
		foreach($totalemparray as  $totalemp =>  $tottalvalues){?>
			{
				name: '<?php echo $totalemp;?>',
        data: [<?php echo implode(", ",$tottalvalues);?>]
			} <?php if ($totalemp!=$last_key) { ?>,<?php }?>
		<?php }?>
		 
	]
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
			$sql = runQuery("SELECT count(*) as count FROM track_work where reg_date >='".$fromdate." 00:00:00' and reg_date <='".$todate." 23:59:59' order by ID desc");
		if(!empty($sql)){
			$totalarray[] = $sql['count'];
		}else{
			$totalarray[] = 0;
		}
   }?>
   <script>
   Highcharts.chart('container1', {
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
			$sql = runQuery("SELECT count(*) as count FROM track_work where reg_date >='".$fromdate." 00:00:00' and reg_date <='".$todate." 23:59:59' order by ID desc"); 
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
   Highcharts.chart('container1', {
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
	
	$pendingclients = runQuery("select count(*) as count from clients where  status = 'Pending' and mod_date >='".$fromdate." 00:00:00' and mod_date <='".$todate." 23:59:59' order by ID desc");
	$approvedclients = runQuery("select count(*) as count from clients where  status = 'Approved' and mod_date >='".$fromdate." 00:00:00' and mod_date <='".$todate." 23:59:59' order by ID desc");
	$rejectclients = runQuery("select count(*) as count from clients where  status = 'Reject' and mod_date >='".$fromdate." 00:00:00' and mod_date <='".$todate." 23:59:59' order by ID desc"); 
	 
	
	$totalclientsarray = runloopQuery("select * from clients where  mod_date >='".$fromdate." 00:00:00' and mod_date <='".$todate." 23:59:59' order by ID desc");
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
			 $sorcount = runQuery("select  count(*) as count from track_work where  source = '".$srctitle."'");
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
        text: '<?php echo $datatext;?> Clients'
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
	   $todate = $year.'-'.date("m", mktime(0, 0, 0, $x, 10))."-31"; 
			$sql = runQuery("SELECT count(*) as count FROM employee where reg_date >='".$fromdate." 00:00:00' and reg_date <='".$todate." 23:59:59' order by ID desc");
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
	</body>

	<!-- end::Body -->
</html>