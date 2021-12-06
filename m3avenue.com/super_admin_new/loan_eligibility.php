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
	$sqlpincode = runQuery("select * from pl_pincode where pincode = '".$_POST['pincode']."'");
	if(!empty($sqlpincode)){
		$sqlcompany = runloopQuery("select * from pl_company_category 
			where company_name = '".$_POST['tags']."'");
		$companiecat_arr = array_column($sqlcompany,'company_category');
		$catstring = implode("','",$companiecat_arr);
		$sqldata = runloopQuery("select pd.*,bank_name from pl_data pd inner join pl_banks pb on pb.ID = pd.bank_id where pd.state = '".$sqlpincode['state_code']."' 
			and pd.company_category in ('".$catstring."')  and pd.salary_start <= '".$_POST['salary']."' 
			and pd.salary_end >= '".$_POST['salary']."'");
	}

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="" />
	<meta name="author" content="" />
	<meta name="robots" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template" />
	<meta property="og:title" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template" />
	<meta property="og:description" content="Fillow : Fillow Saas Admin  Bootstrap 5 Template" />
	<meta property="og:image" content="https:/fillow.dexignlab.com/xhtml/social-image.png" />
	<meta name="format-detection" content="telephone=no">
	
	<!-- PAGE TITLE HERE -->
	<title>Fillow Saas Admin Dashboard</title>
	
	
	<?php include('header_scripts.php');?>
</head>
<body>

    <!--*******************
        Preloader start
        ********************-->
        <div id="preloader">
        	<div class="lds-ripple">
        		<div></div>
        		<div></div>
        	</div>
        </div>
    <!--*******************
        Preloader end
        ********************-->

    <!--**********************************
        Main wrapper start
        ***********************************-->
        <div id="main-wrapper">


        	<?php include('header.php');?>


		<!--**********************************
            Content body start
            ***********************************-->
            <div class="content-body">
            	<!-- row -->
            	<div class="container-fluid">


            		<div class="row">

            			<div class="col-12">
            				<div class="card">
            					<div class="card-header">
            						<h4 class="page-title" style="color:#886CC0">PL Check</h4>
            						<!-- <button type="button" class="btn btn-primary" style="float:right" data-toggle="modal" data-target="#newModal">Add Bank</button>  -->
            					</div>
            					<div class="card-body">


            						<form method="POST">
            							<div class="form-group row">
            								<label for="tags" class="col-md-2 col-form-label">Company Name</label>
            								<div class="col-sm-4">
            									<input type="text"  class="form-control" id="tags" name="tags"  placeholder="Company Name" value="<?= !empty($_POST['tags']) ? $_POST['tags'] : null ?>">
            								</div>

            								<label for="salary" class="col-md-2 col-form-label">Net Credited Salary (Month) </label>
            								<div class="col-md-4">
            									<input type="text"  class="form-control" id="salary" name="salary" autocomplete="off" placeholder="Salary" value="<?= !empty($_POST['salary']) ? $_POST['salary'] : 0 ?>">
            								</div>
            							</div>
            							<div class="form-group row">
            								<label for="pincode" class="col-md-2 col-form-label">Pincode</label>
            								<div class="col-md-4">
            									<input type="text"  class="form-control" id="pincode" name="pincode" autocomplete="off" value="<?= !empty($_POST['pincode']) ? $_POST['pincode'] : null ?>">
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
            						<br>


            						<div class="table table-responsive">

            							<!--begin: Datatable -->
            							<table id="example3" style="min-width: 845px" class="table table-bordered table-hover table-checkable display">

            								<thead>
            									<tr>
            										<th>S.No</th>
            										<th>Bank Name</th>
            										<th>ROI</th>
            										<th>FOIR</th>
            										<th>Tenure</th>
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
            										$tenure = $pl_category_tenure['tenure'];
            										$obligation = !empty($_POST['obligation']) ? $_POST['obligation'] : 0 ;
            										$emi = ($_POST['salary']  * ($foir/100)) -  $obligation;
            										$loan_amount = (( $emi * ( pow(1+$roi_year,$tenure) -1) ) / ($roi_year * pow(1+$roi_year,$tenure)));
            										?>
            										<tr>
            											<td><?= ($i+1); ?></td>
            											<td><?= $sqldata[$i]['bank_name']; ?></td>
            											<td><?= $roi; ?></td>
            											<td><?= $sqldata[$i]['foir']; ?></td>
            											<td><?= $tenure; ?></td>
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

            	</div>
            </div>
        <!--**********************************
            Content body end
            ***********************************-->


        </div>
    <!--**********************************
        Main wrapper end
        ***********************************-->

        <?php include('footer_scripts.php');?>

        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script>
        	$( function() {

        		var sqlcompanies = <?= json_encode($sqlcompanies); ?>;

        		var availableTags = sqlcompanies;
        		$( "#tags" ).autocomplete({
        			source: availableTags
        		});
        	} );


        </script>

    </body>
    </html>