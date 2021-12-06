<?php
session_start();
error_reporting(E_ALL); 
include('../functions.php');
$userid = current_adminid(); 
if(empty($userid)){
	header("Location: login.php");
} 
$message = '';

$sqlvendor = runloopQuery("select df.ID vendor_id,dva.ID application_id
,df.vendor_name,df.vendor_location,dva.edi
,dva.principal_amount,dva.pf,dva.intrest
,dva.tenure,date(dva.reg_date) loandate,dva.edi,dva.ID application_id from df_vendor df inner join df_vendor_application dva 
on dva.vendor_id = df.ID where dva.status = 1 order by dva.ID desc");

$message = '';

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
							<h4 class="page-title" style="color:#886CC0">Daily Finance Status</h4>

						

							
						</div>
						<div class="card-body">

								

								<div class="table table-responsive">

								<!--begin: Datatable -->
								<table id="example3" style="min-width: 845px" class="table table-bordered table-hover table-checkable display">
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
							unset($penltyamtarr);	
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

						<?php } ?>
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

</body>
</html>