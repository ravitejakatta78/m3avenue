<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_adminid(); 

if(empty($userid)){

	header("Location: index.php");

}

$sqlvendor = runloopQuery("select df.ID vendor_id,dva.ID application_id
,df.vendor_name,df.vendor_location,dva.edi
,dva.principal_amount,dva.pf,dva.intrest
,dva.tenure,date(dva.reg_date) loandate,dva.edi,dva.ID application_id from df_vendor df inner join df_vendor_application dva 
on dva.vendor_id = df.ID where dva.status = 1 order by dva.ID desc" );

$message = '';




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
			<div class="row mx-3" style="display:flex;align-items: center;min-height:60px;">
				<div class="col-lg-12 col-md-12 col-sm-4 col-xs-12">
					<h4 class="page-title">Daily Finance Status </h4> 
				</div>

			</div>    

		<div class="kt-portlet__body">
		<div class="table table-responsive">

						<!--begin: Datatable -->
			<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
					<tr>
								<th>S.No</th>
								<th>Vendor Name</th>
								<th>Date</th> 
								<th>Aggremental Amount</th>
								<th>EDI</th>
								<th>Processing Fee</th>
								<th>Disbursal</th>
								<th>Pending Amount</th>
								<th>Penalties</th>
								<th>Net Profit</th>
								<th>Outstanding</th>
								<th>Action</th>
							</tr>
						
							</thead>
								<tbody>

								<?php
						for($i=0;$i< count($sqlvendor) ;$i++){
							$sqlpay = runQuery("select sum('penalty') penalties from
							df_transaction_details where application_id ='".$sqlvendor[$i]['application_id']."'");

							$sqloutstanding = runQuery("select balance_amount,pending_amount from df_transaction_details where application_id ='".$sqlvendor[$i]['application_id']."'  order by ID desc limit 1");
							$sqloutstandinglasttwo = runloopQuery("select application_id,balance_amount,pending_amount,penalty from df_transaction_details where application_id ='".$sqlvendor[$i]['application_id']."'  order by ID desc limit 2");
							$pendingamtarr = []; 
							$penltyamtarr = [];
								if(count($sqloutstandinglasttwo) == 2  ){
									if($sqloutstandinglasttwo[0]['balance_amount'] > 0){
										$pendingamtarr[] = $sqloutstandinglasttwo[1]['pending_amount'];
										$penltyamtarr[] = $sqloutstandinglasttwo[1]['penalty'];

									}
								}
						?>
							<tr>
								<td><?= $i+1 ;?></td>
								<td><?= $sqlvendor[$i]['vendor_name']; ?></td>
								<td><?= $sqlvendor[$i]['loandate']; ?></td>
								<td><?= $principalamt[] = $sqlvendor[$i]['principal_amount']; ?></td>
								<td><?= $ediamt[] = $sqlvendor[$i]['edi']; ?></td>
								<td><?= $pfamt[] = $sqlvendor[$i]['pf']; ?></td>
								<td><?= $disbursalarr[] = $disbursal = $sqlvendor[$i]['principal_amount'] - $sqlvendor[$i]['intrest'] - $sqlvendor[$i]['pf']; ?></td>
								<td><?= $pendingamt[] = array_sum($pendingamtarr); ?></td>
								<td><?= $penltyamt[] = $penlitysingle =  !empty($penltyamtarr) ? array_sum($penltyamtarr) : 0; ?></td>
								<td><?= $profitamt[] = $sqlvendor[$i]['pf'] + ($penlitysingle) ?></td>
								<td><?= $outstanding[] =  $sqloutstanding['balance_amount'] ?></td>
								<td><a href="df-detail-application.php?id=<?= $sqlvendor[$i]['application_id'] ?>" > View </a> </td>
							</tr>
<?php
							unset($pendingamtarr);
							unset($penltyamtarr);	}
							?>
                          </tbody>
						  <tfoot>
							  <tr>
								<td colspan="3"><b>Grand Total</b></td>
								<td><b><?= array_sum($principalamt); ?></b></td>
								<td><b><?= array_sum($ediamt); ?></b></td>
								<td><b><?= array_sum($pfamt); ?></b></td>
								<td><b><?= array_sum($disbursalarr); ?></b></td>
								<td><b><?= array_sum($pendingamt); ?></b></td>
								<td><b><?= array_sum($penltyamt); ?></b></td>
								<td><b><?= array_sum($profitamt); ?></b></td>
								<td><b><?= array_sum($outstanding); ?></b></td>
								<td></td>
							</tr>
							</tfoot> 
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
		function updatetransaction(appid,rowid,installmentdate,edi)
		{
			var paid_amount = $("#paid_amount"+rowid).val();
			
	        $.ajax({
            type: 'post',
            url: 'df-detail-application.php?id='+appid,
            data: {
                application_id: appid,
                paid_amount : paid_amount,
				installmentdate : installmentdate,
				updateval : 1,
				rowid : rowid,
				edi:edi
            },
            success: function(response) { 
				location.reload();
			}
			});
		}

	

	</script>
	</body>

	<!-- end::Body -->
</html>