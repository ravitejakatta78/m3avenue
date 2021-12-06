<?php 
   session_start(); 
   
   include('../functions.php');
   $userid = current_adminid(); 
   $monthstartdate = date('Y-m-01');
   $currentdate = date('Y-m-d');

   if(empty($userid)){
   	header("Location: index.php");
   }
   $startdate = !empty($_GET['sdate']) ? $_GET['sdate'] : $monthstartdate ;
   $enddate = !empty($_GET['sdate']) ? $_GET['edate'] : $currentdate ;
 $campaigns_list = runloopQuery('select c.*,count(cu.ID) campaign_user_count
,sum(case when cu.callstatus = 1 then 1 else 0 end) leads_count
,sum(case when cu.callstatus = 0 and callduration = 0 then 1 else 0 end) pending_count from campaigns c 
left join campaigns_users cu on c.ID = cu.campaign_id  where date(c.reg_date) between \''.$startdate.'\' and \''.$enddate.'\'
group by c.title 
order by c.ID desc');   
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
                                 <h3 class="kt-portlet__head-title">Campaigns Report</h3>
                           </div>
                        </div>
                        <div class="kt-portlet__body">
                        <form method="get" action="">
										<div class="form-group row">

										
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
                                    <th scope="col" rowspan="2">Campaign Name</th>
                                    <th scope="col" rowspan="2">Last Active Date</th>
                                    <th scope="col" rowspan="2">Created date</th>
                                    <th scope="col" rowspan="2">No of callers</th>
                                    <th scope="col" colspan="3" style="text-align:center;">Data</th>
                                    <th scope="col" rowspan="2">Results</th>
                                    </tr>
                              
                                    <tr>
                                    <th scope="col">Total</th>
                                    <th scope="col">Leads</th>
                                    <th scope="col">Pending</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                 <?php 

                                 for($i=0;$i<count($campaigns_list);$i++){
                                    $executivearr = explode(',',$campaigns_list[$i]['executive']);
                                    ?>
                                    <tr>
                                       <td><?= $i+1; ?></td>
                                       <td><?= $campaigns_list[$i]['title']; ?></td>
                                       <td><?php // $campaigns_list[$i]['']; ?></td>
                                       <td><?= date('d-M-Y', strtotime($campaigns_list[$i]['reg_date'])); ?></td>
                                       <td><?= count($executivearr) ?? 0 ; ?></td>
                                       <td><?= $campaigns_list[$i]['campaign_user_count']; ?></td>
                                       <td><?= $campaigns_list[$i]['leads_count']; ?></td>
                                       <td><?= $campaigns_list[$i]['pending_count']; ?></td>
                                       <td></td>
                                    </tr>
                                 <?php } ?>   

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
     
        </body>
   <!-- end::Body -->
</html>
