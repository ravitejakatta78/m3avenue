<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_adminid(); 
//sendnotification($api);
if(empty($userid)){

	header("Location: index.php");

}


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
					<h4 class="page-title">Projection</h4> 
				</div>
								<div class="col-lg-4 col-sm-8 col-md-4 col-xs-12">
					<a href="df-budget.php"><button type="button" class="btn btn-primary" style="float:right" >Back</button></a>    
				</div>

			</div>    

		<div class="kt-portlet__body">
		<div class="table table-responsive">

						<!--begin: Datatable -->
			<table class="table table-bordered table-hover table-checkable" id="kt_table_2" >
					<thead>
					<tr>
								<th>S.No</th>
								<th>Investment</th>
								<th>Rev</th> 
								<th>E.D.I</th>
								<th>Total E.D.I</th>
								<th>Balance</th>
								</tr>
						
							</thead>
								<tbody>
                            <?php 
                            for($i=0;$i<30;$i++){ 
                                if($i==0){
                                    $invest = round($_GET['balance']/1000,2);
                                }else{
                                    $invest = floor($balance / 5) * 5;;
                                }
                                $rev = ($invest*10)/100;
                                $edi = $rev * 0.2;
                                if($i==0){
                                    $totaledi = $_GET['totaledi']/1000;
                                    $balance = $rev + $totaledi;
                                }else{
                                    $totaledi = $totaledi + $edi;
                                    $balance = $balance - $invest + $rev  + $totaledi;
                                    
                                }
                                ?>
                                <tr>
                                    <td><?= $i+1; ?></td>
                                    <td>
                                    <?php if($i==0){
                                        echo $invest;
                                         }else { ?>
                                        <input type="text" class="form-control" size="1" id="invest_<?= $i; ?>" onchange="project('<?= $i; ?>')"  value="<?= $invest;  ?>"> 
                                    <?php } ?>
                                    </td>
                                    <td><span id="revtext_<?= $i; ?>"><?= round($rev,2);  ?></span>
                                    <input type="hidden" id="rev_<?= $i; ?>" value="<?= round($rev,2); ?>">
                                
                                </td>
                                    <td><span id="editext_<?= $i; ?>"><?= round($edi,2);  ?></span>
                                    <input type="hidden" id="edi_<?= $i; ?>" value="<?= round($edi,2); ?>">

                                </td>
                                    <td><span id="totaleditext_<?= $i; ?>"><?= round($totaledi,2);  ?></span>
                                <input type="hidden" id="totaledi_<?= $i; ?>" value="<?= round($totaledi,2); ?>">
                                </td>
                                    <td><span id="balancetext_<?= $i; ?>"><?= round($balance,2) ;  ?></span>
                                    <input type="hidden" id="balance_<?= $i; ?>" value="<?= round($balance,2); ?>">

                                </td>
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
	    
<?php include('footer.php');?>


				</div>

				<!-- end:: Wrapper -->
			</div>

			<!-- end:: Page -->
		</div>

		<!-- end:: Root -->
	
		
	<?php include("footerscripts.php");?>
<script>
function project(i){
    var i = parseInt(i);
   var invest =  $("#invest_"+i).val();
   var rev = (parseFloat(invest)*10)/100;

   $("#revtext_"+i).html('');
   $("#revtext_"+i).html(rev);
    $("#rev_"+i).val(rev);
   
   var edi = rev * 0.2;

   var prevtotaledi = $("#totaledi_"+(i-1)).val();

   var totaledi = parseFloat(prevtotaledi) + edi;
   var balance = (parseFloat($("#balance_"+(i-1)).val()) - invest + rev  + totaledi).toFixed(2);
    
    $("#editext_"+i).html(edi);
    $("#edi_"+i).val(rev);

    $("#totaleditext_"+i).html(totaledi.toFixed(2));
    $("#totaledi_"+i).val(totaledi.toFixed(2));

    $("#balancetext_"+i).html(balance);
    $("#balance_"+i).val(balance);


for(var j = (i+1); j < 30 ; j++){
    var next_invest =Math.floor(balance/5) * 5;
    $("#invest_"+j).val(next_invest);
    var next_rev = (parseFloat(next_invest)*10)/100;

    $("#revtext_"+j).html(next_rev);
    $("#rev_"+j).val(next_rev);

    var next_edi = next_rev * 0.2;
    $("#editext_"+j).html(next_edi.toFixed(2));
    $("#edi_"+j).val(next_edi.toFixed(2));


    var nextprevtotaledi = $("#totaledi_"+(j-1)).val();
    var next_totaledi = parseFloat(nextprevtotaledi) + next_edi;

    $("#totaleditext_"+j).html(next_totaledi.toFixed(2));
    $("#totaledi_"+j).val(next_totaledi.toFixed(2));
    balance = (parseFloat($("#balance_"+(j-1)).val()) - next_invest + next_rev  + next_totaledi).toFixed(2);

    $("#balancetext_"+j).html(balance);
    $("#balance_"+j).val(balance);
}

                                
}
</script>

</body>

	<!-- end::Body -->
</html>