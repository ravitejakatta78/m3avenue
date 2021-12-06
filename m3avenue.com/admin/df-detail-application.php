<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_adminid(); 
//sendnotification($api);
if(empty($userid)){

	header("Location: index.php");

}

$sqlvendor = runQuery("select df.ID vendor_id,dva.ID application_id,df.vendor_name,df.vendor_location,dva.principal_amount
,dva.tenure,date(dva.reg_date) loandate,dva.edi,dva.ID application_id from df_vendor df inner join df_vendor_application dva 
on dva.vendor_id = df.ID
where dva.ID = '".$_GET['id']."'");

$message = '';

if(!empty($_POST['updateval']))
{
	extract($_POST);
	$sqlapp = runQuery("select * from df_vendor_application where ID = '".$_POST['application_id']."'");		
	if($_POST['rowid'] != 1){
		$installment_date = date('Y-m-d', strtotime($_POST['installmentdate']. ' -1 days'));
	}
	else{
		$installment_date = $_POST['installmentdate'];
	}	
	$sqlapptrans = runloopQuery("select * from df_transaction_details where 
	installment_date >= '".$installment_date."' and application_id = '".$application_id."' order by ID,installment_date "); 
	$installmentdatearr = [];
	if(!empty($sqlapptrans)){
		$installmentdatearr = array_column($sqlapptrans,'installment_date');
	}
	if($_POST['rowid'] == 1){
		$arr['edi'] = $sqlapp['edi'];
		$arr['paid_amount'] = $paid_amount ?? 0;
		$arr['last_balance'] = $sqlapp['principal_amount']; 
		$arr['application_id'] = $application_id;
		$arr['installment_date'] = $installmentdate;
		$updaters = dfupdatecals($arr);
	}
	else{
		$prev_installment_date = date('Y-m-d', strtotime($installmentdate. ' -1 days'));

		$sqlapptransprev = runQuery("select * from df_transaction_details 
		where application_id = '".$sqlvendor['application_id']."' and installment_date = '".$prev_installment_date."' limit 1");
		$sqlappcurrent = runQuery("select * from df_transaction_details 
		where application_id = '".$sqlvendor['application_id']."' and installment_date = '".$_POST['installmentdate']."' limit 1");
		$arr['edi'] = $sqlappcurrent['edi'];
		$arr['paid_amount'] = $paid_amount ?? 0;
		$arr['last_balance'] = $sqlapptransprev['balance_amount']; 
		$arr['application_id'] = $application_id;
		$arr['installment_date'] = $installmentdate;
		$updaters = dfupdatecals($arr);
	}
	

	$next_installment_date = date('Y-m-d', strtotime($installmentdate. ' +1 days'));
	if($updaters['balance_amount'] > 0 && !in_array($next_installment_date,$installmentdatearr)){
		$insertarr['edi'] =	$sqlapp['edi'] + $updaters['pending_amount'] + $updaters['penalty'];
		$insertarr['vendor_id'] = $sqlvendor['vendor_id'];
		$insertarr['application_id'] = $_REQUEST['id'];
		$insertarr['installment_date'] = $next_installment_date;
		$insertarr['paid_amount'] = 0;
		$insertarr['pending_amount'] = 0;
		$insertarr['penalty'] = 0;
		$insertarr['balance_amount'] =  $updaters['balance_amount'];
		$res = insertQuery($insertarr,'df_transaction_details');	
	}
	else if($arr['last_balance'] > 0 && in_array($next_installment_date,$installmentdatearr)){
		
		$sqlapptransnext = runQuery("select * from df_transaction_details 
		where application_id = '".$sqlvendor['application_id']."' and installment_date = '".$next_installment_date."' limit 1");
		$updatearr['edi'] =	$sqlapp['edi'] + $updaters['pending_amount'] + $updaters['penalty'];
		//$updatearr['balance_amount'] = $updaters['balance_amount'] + $updatearr['edi'] + $sqlapptransnext['pending_amount']+$sqlapptransnext['penalty'];
		$updatearr['balance_amount'] = $updaters['balance_amount'] + $sqlapptransnext['penalty'];
		$updateidarr['installment_date'] = $next_installment_date;
		$updateidarr['application_id'] = $sqlvendor['application_id'];

		$resultupdate = updateQuery($updatearr,'df_transaction_details',$updateidarr);


	}
	else if($updaters['balance_amount'] == 0 )
	{
		$updateidarr['ID'] = $sqlvendor['application_id'];
		$updatearr['status'] = 2;
		$resultupdate = updateQuery($updatearr,'df_vendor_application',$updateidarr);
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
<style>
.bigdrop {
    width: 600px !important;
}
	</style>
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
				<div class="col-lg-8 col-md-8 col-sm-4 col-xs-12">
					<h4 class="page-title">Application Status of <?= $sqlvendor['vendor_name']. '('. $sqlvendor['vendor_location'].')' ; ?></h4> 
				</div>
								<div class="col-lg-4 col-sm-8 col-md-4 col-xs-12">
								    			<button type="button" class="btn btn-primary" style="float:right; border:1px solid #fff;" value="Create PDF" 
            id="btPrint" onclick="createPDF()" >PDF  </button>
					<a href="df-application-list.php"><button type="button" class="btn btn-primary" style="float:right" >Back</button></a>    
				</div>

			</div>    

		<div class="kt-portlet__body">
		<div class="table table-responsive">

						<!--begin: Datatable -->
			<table class="table table-bordered table-hover table-checkable" id="kt_table_2" >
					<thead>
					<tr>
								<th>S.No</th>
								<th>Installment Date</th>
								<th>E.D.I</th> 
								<th>Paid Amount</th>
								<th>Pending</th>
								<th>Penalty</th>
								<th>Balance</th>
								<th>Action</th>
							</tr>
						
							</thead>
								<tbody>

								<?php
						$Date = $sqlvendor['loandate'];
						// Add days to date and display it
						$df_transactions = runloopQuery("select * from df_transaction_details where application_id = '".$sqlvendor['application_id']."'");
						for($i=0;$i< count($df_transactions) ;$i++){
							$installmentdate = date('Y-m-d', strtotime($Date. ' + '.($i+1).' days'));
							
						?>
							<tr>
								<td><?= $i+1 ;?></td>
								<td><?= date('d-M-Y',strtotime($df_transactions[$i]['installment_date'])); ?></td>
								<td><?= $df_transactions[$i]['edi']; ?></td>
								<td><input type="text" class="form-control" id="paid_amount<?= ($i+1); ?>" name="paid_amount" style="width:50%" value="<?= $paidamt[] = $df_transactions[$i]['paid_amount']; ?>"></td>		
								<td><?=  $df_transactions[$i]['pending_amount']; ?></td>
								<td><?= $pnltyamt[] = $df_transactions[$i]['penalty']; ?></td>
								<td><?php if($i == 0) {
									if($df_transactions[$i]['balance_amount'] == 0)
									echo $sqlvendor['principal_amount'];
									else 
									echo  $df_transactions[$i]['balance_amount']; 
								}else{
									echo   $df_transactions[$i]['balance_amount']; 
								}  ?></td>
								<td>
								<?php if($df_transactions[$i]['installment_date'] <= date('Y-m-d')){ ?>
									<button class="btn btn-primary" onclick="updatetransaction('<?= $sqlvendor['application_id']; ?>','<?= ($i+1);?>','<?= $df_transactions[$i]['installment_date']; ?>','<?= $df_transactions[$i]['edi']; ?>')" >Update</button>
								<?php } ?>
								</td>
							</tr>
<?php
								}
							?>
                          </tbody>
                          <tfoot>
                              <tr>
                                  <?php 
                                  $installment_regdate = date('Y-m-d',strtotime($sqlvendor['loandate']));
                                  $end_installement_date =  date('Y-m-d', strtotime($installment_regdate. ' + '.($sqlvendor['tenure']-1).' days'));

                                  ?>
                                  <td><strong><?= $sqlvendor['tenure']; ?></strong></td>
                                  <td><strong><?= $end_installement_date; ?></strong></td>
                                  <td></td>
                                  <td><strong><?= array_sum($paidamt); ?></stron></td>
                                  <td></td>
                                  <td><strong><?= array_sum($pnltyamt); ?></strong></td>
                                  <td></td>
                                                                   <td><strong>Grand Total</strong></td> 
                            </tr>      
                            </tfoot>      
					</table>
				<!--end: Datatable -->
	    	</div>
	    	</div>
							
			</div>
		</div>
				 
</div>
	    
<?php include('footer.php');?>


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

		$(document).ready(function() {
    $('#kt_table_2').DataTable( {
        "paging":   false,
        "info":     false
    } );
} );	


function createPDF() {
        var sTable = document.getElementById('kt_content').innerHTML;
		

        var style = "<style>";
        style = style + "table {width: 100%;font: 17px Calibri;}";
        style = style + "table, th, td {border: solid 1px #DDD; border-collapse: collapse;";
        style = style + "padding: 2px 3px;text-align: center;}";
         style = style + "@media print {  table td:last-child {display:none}}";
		 style = style + "@media print {  table th:last-child {display:none}}";
		  style = style + "@media print { label  {display:none}}";
		style = style + "</style>";
	
        // CREATE A WINDOW OBJECT.
        var win = window.open('', '', 'height=700,width=700');
	
        win.document.write('<html><head>');
        win.document.write('<title>Profile</title>');   // <title> FOR PDF HEADER.
        win.document.write(style);          // ADD STYLE INSIDE THE HEAD TAG.
        win.document.write('</head>');
        win.document.write('<body>');
        win.document.write(sTable);         // THE TABLE CONTENTS INSIDE THE BODY TAG.
        win.document.write('</body></html>');

        win.document.close(); 	// CLOSE THE CURRENT WINDOW.

        win.print();    // PRINT THE CONTENTS.
    }
	</script>
	</body>

	<!-- end::Body -->
</html>