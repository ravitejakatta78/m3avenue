<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_adminid(); 

if(empty($userid)){

	header("Location: index.php");

}

$sqlpl_data = runloopQuery("select distinct company_name from pl_company_category");
$sqlcompanies = array_column($sqlpl_data,'company_name');

$message = '';
$sqldata = [];
if(!empty($_POST))
{
	$sqlpincode = runloopQuery("select * from pl_pincode where pin_code = '".$_POST['pincode']."'");
	if(!empty($sqlpincode)){
		$bankid_arr = array_column($sqlpincode,'bank_id');
		$bankidstring = implode("','",$bankid_arr);
		$sqlcompany = runloopQuery("select * from pl_company_category 
		where company_name = '".$_POST['tags']."' and bank_id in ('".$bankidstring."') ");
		$companiecat_arr = array_column($sqlcompany,'company_category');
		$catstring = implode("','",$companiecat_arr);
		$sqldata = runloopQuery("select pd.*,bank_name from pl_data pd inner join pl_banks pb on pb.ID = pd.bank_id where pd.company_category in ('".$catstring."')  and pd.salary_start <= '".$_POST['salary']."' 
		and pd.salary_end >= '".$_POST['salary']."'");
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
		<title>M3  | PL Check</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />

		<?php include('headerscripts.php');?>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">

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
					<h4 class="page-title">PL Check</h4> 
				</div>
				<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
				</div>
			</div>    
			
		<div class="kt-portlet__body">

		<form method="POST">
  <div class="form-group row">
    <label for="tags" class="col-md-2 col-form-label">Company Name</label>
    <div class="col-sm-4">
      <input type="text"  class="form-control" id="tags" name="tags"  placeholder="Search With Min 3 Char of Name.." value="<?= !empty($_POST['tags']) ? $_POST['tags'] : null ?>">
    </div>

	<label for="salary" class="col-md-2 col-form-label">Net Credited Salary (Month) </label>
    <div class="col-md-4">
      <input type="text"  class="form-control" id="salary" name="salary" autocomplete="off" placeholder="Salary" value="<?= !empty($_POST['salary']) ? $_POST['salary'] : 0 ?>">
    </div>
  </div>
  <div class="form-group row">
     <label for="pincode" class="col-md-2 col-form-label">Pincode</label>
    <div class="col-md-4">
      <input type="text"  class="form-control" id="pincode" name="pincode" autocomplete="off" placeholder="Pincode" value="<?= !empty($_POST['pincode']) ? $_POST['pincode'] : null ?>">
    </div> 

	<label for="obligation" class="col-md-2 col-form-label">Obligation</label>
    <div class="col-md-4">
      <input type="text"  class="form-control" id="obligation" name="obligation" value="<?= !empty($_POST['obligation']) ? $_POST['obligation'] : 0 ?>">
    </div>
  </div>

  <div class="modal-footer">
        <input type="submit" value="Check" class="btn btn-primary" >
      </div>
</form>	

<div class="table table-responsive">

						<!--begin: Datatable -->
			<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
						<tr>
							<th>S.No</th>
							<th>Bank Name</th>
							<th>ROI</th>
							<th>FOIR</th>
							<th>Tenure</th>
							<th>Processing Fee</th>
							<th>Proposed EMI</th>
							<th>Loan Amount</th>
						</tr> 
							</thead>
								<tbody>
<?php for($i=0;$i<count($sqldata);$i++) {
	$pl_category_tenure = runQuery("select * from pl_category_tenure where bank_id='".$sqldata[$i]['bank_id']."' and company_category='".$sqldata[$i]['company_category']."'");
	$roi = $sqldata[$i]['r1'];
	$roi_year = ( $roi / (12 * 100) ); 
	$foir = $sqldata[$i]['foir'];
	$tenure = $sqldata[$i]['tenure'];
	$obligation = !empty($_POST['obligation']) ? $_POST['obligation'] : 0 ;
	$emi = ($_POST['salary']  * ($foir/100)) -  $obligation;
	$actual_loan_amount = (( $emi * ( pow(1+$roi_year,$tenure) -1) ) / ($roi_year * pow(1+$roi_year,$tenure)));
	if(!empty($sqldata[$i]['multiplier'])){
	    
	    // $sqldata[$i]['multiplier'] * $_POST['salary'];
	    $multiplier = $sqldata[$i]['multiplier'] * $_POST['salary'];    
    	$multiplier_loan_amount =  min([$actual_loan_amount,$multiplier]);
	}
	else{
	    $multiplier_loan_amount = $actual_loan_amount;
	}
	
	$loan_amount =  min([$multiplier_loan_amount,$sqldata[$i]['max_loan_amount']]);
	?>
<tr>
<td><?= ($i+1); ?></td>
<td><?= $sqldata[$i]['bank_name']; ?></td>
<td><?= $roi; ?></td>
<td><?= $sqldata[$i]['foir']; ?></td>
<td><?= $tenure; ?></td>
<td><?= $sqldata[$i]['processing']; ?></td>
<td><?= $emi; ?></td>
<td><?= round($loan_amount,2); ?></td>
</tr>
<?php } ?>
								</tbody>
</table>
</div>

</div>
							
			</div>
		</div>
				 
</div>
	    
	    
<?php include('footer.php');?>

					<!-- end:: Footer -->
				</div>

				<!-- end:: Wrapper -->
			</div>

			<!-- end:: Page -->
		</div>

		<!-- end:: Root -->
		
	<?php include("footerscripts.php");?>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script>
	  $( function() {
		  
		var sqlcompanies = <?= json_encode($sqlcompanies); ?>;
		
    var availableTags = sqlcompanies;
    $( "#tags" ).autocomplete({
		source: function(request, response) {
        var results = $.ui.autocomplete.filter(availableTags, request.term);
        response(results.slice(0, 5));
    },
     
	  minLength: 3,
    });
  } );

	
	</script>

	</body>

	<!-- end::Body -->
</html>