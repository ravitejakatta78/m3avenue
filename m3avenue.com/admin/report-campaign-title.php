<?php 
   session_start(); 
   
   include('../functions.php');
   $userid = current_adminid(); 
   $monthstartdate = date('Y-m-01');
   $currentdate = date('Y-m-d');
   $startdate = !empty($_GET['sdate']) ? $_GET['sdate'] : $monthstartdate ;
   $enddate = !empty($_GET['sdate']) ? $_GET['edate'] : $currentdate ;
   if(empty($userid)){
   	header("Location: index.php");
   }
   
   $teamdetails = runloopQuery("select *,concat(fname,' ',lname) name from employee where role_id = 4 order by ID desc");
   $teamselect = array_column($teamdetails,'name',"unique_id");


   $employeedetails = [];

   $empid = employee_id($_GET['employeeid']);
   $employeedetails = 'select c.title,c.reg_date,cu.executive_id,count(cu.ID) campaign_user_count
   ,concat(e.fname,\' \',e.lname) executive_name
   ,e.unique_id executive_unique_id
   ,sum(case when cu.callstatus != 0 and callduration != 0 then 1 else 0 end) calls_count
,sum(callduration) callduration  
,sum(case when cu.callstatus = 1 then 1 else 0 end) leads_count
 from campaigns c 
left join campaigns_users cu on c.ID = cu.campaign_id
left join employee e on cu.executive_id = e.ID
where 
 date(c.reg_date) between \''.$startdate.'\' and \''.$enddate.'\' and cu.executive_id <> "" ';
 if(!empty($_GET['employeeid'])){ 
$employeedetails .=' and executive_id = \''.$empid.'\' ';
  } 
 $employeedetails .=' group by c.title,c.reg_date,cu.executive_id
order by c.ID desc';  
$employeedetails = runloopQuery($employeedetails);
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <title>M3  | Dashboard</title>
      <meta name="description" content="Latest updates and statistic charts">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <?php include('headerscripts.php');?>
      <style>
         select + .select2-container {
         width: 100% !important;
         }
      </style>
   </head>
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
                     <div class="kt-portlet kt-portlet--mobile">
                        <div class="kt-portlet__head">
                           <div class="kt-portlet__head-label">
                                 <h3 class="kt-portlet__head-title">Campaign Title</h3>
                           </div>
                        </div>
                        <div class="kt-portlet__body">
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
                              <!--begin: Datatable -->
                              <table class="table table-bordered table-hover table-checkable" id="kt_table_1">
                                 <thead>
                                 <tr>
			<th scope="col" rowspan="2">S.No</th>
			<th scope="col" rowspan="2">Executive Name</th>
			<th scope="col" rowspan="2">Emp I'd</th>
         <th scope="col" rowspan="2">Campaign Name</th>

         <th scope="col" rowspan="2">Last Active Date</th>
			<th scope="col" rowspan="2">Created date</th>
			<th scope="col" rowspan="2">Manager	Name</th>
			<th scope="col" rowspan="2">Mang I'd</th>
			<th scope="col" colspan="5" style="text-align:center;">Work Report</th>
			<th scope="col" rowspan="2">Results</th>
			</tr>
	 
			<tr>

			<th scope="col">Calls</th>
			<th scope="col">Time</th>
			<th scope="col">Leads</th>
			<th scope="col">Duck</th>
			<th scope="col">Leave</th>
			</tr>
                                 </thead>
                                 <tbody>
<?php
for($i=0;$i<count($employeedetails);$i++){
  $manageruniqueiddet = runQuery('select leader from employee where ID = \''.$employeedetails[$i]["executive_id"].'\'');
  $manageruniqueid = $manageruniqueiddet['leader'];
    
   ?>
   <tr>
      <td><?= $i+1 ;?></td>
      <td><?= $employeedetails[$i]['executive_name']; ?></td>
      <td><?= $employeedetails[$i]['executive_unique_id']; ?></td>
      <td><?= $employeedetails[$i]['title']; ?></td>
      <td></td>
      <td><?= date('d-M-Y', strtotime($employeedetails[$i]['reg_date']));; ?></td>
      <td><?= manager_details_with_unique_id($manageruniqueid,'fname').' '.manager_details_with_unique_id($manageruniqueid,'lname') ?></td>
      <td><?= $manageruniqueid ; ?></td>
      <td><?= $employeedetails[$i]['calls_count'] ; ?></td>
      <td><?=  gmdate("H:i:s", $employeedetails[$i]['callduration']);  ?></td>
      <td><?= $employeedetails[$i]['leads_count']; ?></td>
      <td></td>
      <td></td>
      <td></td>
   </tr>
<?php }

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

</script>  
        </body>
   <!-- end::Body -->
</html>
