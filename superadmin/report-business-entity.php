<?php 
session_start(); 
error_reporting(E_ALL); 
include('../functions.php'); 
$userid = current_adminid(); 
$user_unique_id = employee_details($userid,'unique_id');
$tree = employeehirerachy($user_unique_id);
$empString = !empty($tree) ? implode("','",array_column($tree,'ID')) : '';
if(empty($userid)){ 
	header("Location: index.php"); 
}
$usedetails = runQuery("select * from employee where ID = '".$userid."'");

$monthstartdate = date('Y-m-01');
$currentdate = date('Y-m-d');

$teamdetails = runloopQuery("select *,concat(fname,' ',lname) name from employee where ID in ('".$empString."') order by ID desc");
$teamselect = array_column($teamdetails,'name',"unique_id");

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
				<div class="row mx-3" style="display:flex;align-items: center;min-height:60px;">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Business Entity Staff Report</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                    </div>
                </div>
					  
				                
		<div class="kt-portlet__body">
				
					<form method="get" action="">
										<div class="form-group row">

									
										<label for="example-text-input" class="col-md-1 col-form-label">Start Date</label>
										<div class="col-md-2">
										<input class="form-control" type="date" name="sdate" value="<?= @$_GET['sdate'] ?? $monthstartdate; ?>">
										</div>
										<label for="example-text-input" class="col-md-1 col-form-label">End Date</label>
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
											<th>Business Entity</th>
											<th>Staff Name</th>
											<th>Service Type</th>
											<th>Cumulative Deal Amount</th>
											<th>Number of Applications</th>
											<th>Success Ratio</th>
											</tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                         $startdate = !empty($_GET['sdate']) ? $_GET['sdate'] : $monthstartdate ;
							             $enddate = !empty($_GET['sdate']) ? $_GET['edate'] : $currentdate ;
                    $cumm_app_details = runloopQuery("select entity_name
                    ,sum(deal_amount) deal_amount
                    ,count(la.status) no_of_application
                    ,w.title as service_name,concat(es.first_name,' ',es.last_name) staff_name
                    from lead_application la 
				    left join entity e on e.ID = la.entity_id 
				    inner join worksource w on w.ID = la.service_type
				    left join entity_staff es on es.ID = la.staff_id
				    where date(la.reg_date) between '".$startdate."' and '".$enddate."' and la.created_by in ('".$empString."') 
				    group by e.entity_name,w.title,es.first_name,es.last_name
				    ");							             
                                    for($i=0;$i<count($cumm_app_details);$i++){ ?>
                                        <tr>
                                            <td><?= $i+1 ;?></td>
                                            <td><?= $cumm_app_details[$i]['entity_name'] ;?></td>
                                            <td><?= $cumm_app_details[$i]['staff_name'] ;?></td>
                                            <td><?= $cumm_app_details[$i]['service_name'] ;?></td>
                                            <td><?= $cumm_app_details[$i]['deal_amount'] ;?></td>
                                            <td><?= $cumm_app_details[$i]['no_of_application'] ;?></td>
                                            <td></td>
                                        </tr>    
                                    <?php }  ?>
                                    
                                        
                                    </tbody>
                                </table>
								 
									  <?php //}?>
                                </div>		<!--end: Datatable -->
						</div>
						 
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




	<?php include("footerscripts.php");?>
	
   




</body>

	<!-- end::Body -->
</html>