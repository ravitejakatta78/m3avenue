<?php 
   session_start(); 
   
   include('../functions.php');
   $userid = current_adminid(); 
   
   if(empty($userid)){
   	header("Location: index.php");
   }
   
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
                           <div class="table table-responsive">
                              <!--begin: Datatable -->
                              <table class="table table-bordered table-hover table-checkable" id="kt_table_1">
                                 <thead>
                                 <tr>
                                 <th scope="col" rowspan="2">S.No</th>
			<th scope="col" rowspan="2">Emp I'd</th>
			<th scope="col" rowspan="2">Reporting Manager	</th>
			<th scope="col" rowspan="2">Campaign Name</th>
			<th scope="col" rowspan="2">Last Active Date</th>
			<th scope="col" rowspan="2">Created date</th>
			<th scope="col" colspan="5" style="text-align:center;">Work Report</th>
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
