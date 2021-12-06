<?php
session_start();
error_reporting(E_ALL); 
include('../functions.php');
$userid = current_adminid(); 
if(empty($userid)){
	header("Location: login.php");
} 
$message = '';

$bal = 0;
if(!empty($_POST['addvendorsubmitid']) && !empty($_POST['budget'])){
	$insertarr['investment'] =	$_POST['investment'];
	$insertarr['withdraw'] = $_POST['withdraw'];
	$insertarr['budget'] = $_POST['budget'];
	$insertarr['roi'] = $_POST['roi'];
	$res = insertQuery($insertarr,'df_budget');	
	if(!$res){
		header("Location: df-budget.php?success=success");
	}
}


$sqloutstanding = [];
$pendingamtarr = [];
$penaltyamtarr = [];
$disbursalarr = [];
$liablitypendingamtarr = [];
$balance_amount = 0;
$pending_amount = $current_intrest = $current_pf = 0;
$edi = 0;

$sqltotalvendors = runloopQuery("select * from df_vendor");
$sqldisbursal = runloopQuery("select *,date(reg_date) loandate from df_vendor_application");
$totalvendors = count($sqltotalvendors);
for($j=0;$j< count($sqldisbursal) ;$j++){
	if($sqldisbursal[$j]['loandate'] >= date('Y-m-01')){
		$sqldisbursal[$j]['ID']."<br>";
		$current_pf += $sqldisbursal[$j]['pf'];
		$current_intrest += $sqldisbursal[$j]['intrest'];
	}
}


$sqlvendor = runloopQuery("select df.ID vendor_id,dva.ID application_id
	,df.vendor_name,df.vendor_location,dva.edi
	,dva.principal_amount,dva.pf,dva.intrest
	,dva.tenure,date(dva.reg_date) loandate,dva.edi,dva.ID application_id from df_vendor df 
	inner join df_vendor_application dva
	on dva.vendor_id = df.ID where dva.status = 1 order by dva.ID desc" );
$active_vendors = array_unique(array_column($sqlvendor,'vendor_id'));
if(!empty($active_vendors)){
	$active_vendors_count = count($active_vendors);
}else{
	$active_vendors_count = 0 ;
} 
$sqlpenalty[] = runQuery("select sum(case when date(installment_date) >= '".date('Y-m-01')."' then penalty else 0 end) curent_penalty,sum(penalty) total_penalty from df_transaction_details");
$sqloutstandinglasttwo=array();
for($i=0;$i< count($sqlvendor) ;$i++){
	if($sqlvendor[$i]['loandate'] >= date('Y-m-01')){
		$disbursalarr[] = $sqlvendor[$i]['principal_amount'];
		
	}
	$sqloutstanding[] = runQuery("select balance_amount,pending_amount,(case when edi < 0 then 0 else edi end) edi from df_transaction_details where application_id ='".$sqlvendor[$i]['application_id']."'  order by ID desc limit 1");
	$sqloutstandinglasttwo[] = runloopQuery("select application_id,balance_amount,pending_amount,penalty,date(reg_date) run_paid_date from df_transaction_details where application_id ='".$sqlvendor[$i]['application_id']."'  order by ID desc limit 2");

}
for($j=0;$j<count($sqloutstandinglasttwo);$j++){
	if(count($sqloutstandinglasttwo[$j]) == 2  ){
		if($sqloutstandinglasttwo[$j][0]['balance_amount'] > 0){
			if(($sqloutstandinglasttwo[$j][1]['run_paid_date'] >= date('Y-m-01')) && $sqloutstandinglasttwo[$j][1]['pending_amount'] > 0 ){
				$pendingamtarr[] = $sqloutstandinglasttwo[$j][1]['pending_amount'];    
			}

			if(($sqloutstandinglasttwo[$j][1]['run_paid_date'] < date('Y-m-01')) && $sqloutstandinglasttwo[$j][1]['pending_amount'] > 0){
				$liablitypendingamtarr[] = $sqloutstandinglasttwo[$j][1]['pending_amount'];    
			}
			

			//$penaltyamtarr[] = $sqloutstanding[$j][1]['penalty'];

		}
	}

}
//echo "<pre>";print_r($sqlpenalty);exit;
$outstanding = !empty($sqloutstanding) ? array_sum(array_column($sqloutstanding,'balance_amount')) : 0;
$pending_amount = !empty($pendingamtarr) ? array_sum($pendingamtarr) : 0;
$penalty_amount_total = !empty($sqlpenalty) ? array_sum(array_column($sqlpenalty,'total_penalty')) : 0;
$penalty_amount_current = !empty($sqlpenalty) ? array_sum(array_column($sqlpenalty,'curent_penalty')) : 0;
$edi = !empty($sqloutstanding) ? array_sum(array_column($sqloutstanding,'edi')) : 0;
$intrest = !empty($sqldisbursal) ? array_sum(array_column($sqldisbursal,'intrest')) : 0;
$pf = !empty($sqldisbursal) ? array_sum(array_column($sqldisbursal,'pf')) : 0;
if(count($disbursalarr) > 0){
	$avgdisbursal = array_sum($disbursalarr)/count($disbursalarr);
}else{
	$avgdisbursal = 0;
}

$disbursaltotal = !empty($sqldisbursal)  ? array_sum(array_column($sqldisbursal,'principal_amount'))/count($sqldisbursal) : 0;

$df_budget = runloopQuery("select * from df_budget");
if(!empty($df_budget)){
	$budget = array_sum(array_column($df_budget,'investment')) - array_sum(array_column($df_budget,'withdraw'));
	$roi_avg =  array_sum(array_column($df_budget,'roi')) / count($df_budget); 
}
else{
	$budget = 0;
	$roi_avg =  0;
}
$budget = $budget+round($pf+$intrest+$penalty_amount_total,2) ;
$current_pl =round($current_pf+$current_intrest+$penalty_amount_current,2);
$total_pl = round($pf+$intrest+$penalty_amount_total,2);
$date1=date_create(date('Y-m-01'));
$date2=date_create(date('Y-m-d'));
$diff=date_diff($date1,$date2);
$datediff =  (int)$diff->format("%a");
$spentdays = $datediff; 


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
            						<h4 class="page-title" style="color:#886CC0">Budget</h4>

            						<button type="button" class="btn btn-primary" style="float:right" 
            						data-bs-toggle="modal" data-bs-target=".add_employee_form"data-toggle="modal" data-target="#newModal">Add Budget</button> 

            						<button type="submit" id="projectnext" value="Projection" class="btn btn-primary">



            					</div>
            					<div class="card-body">

            						<div class="table table-responsive">

            							<!--begin: Datatable -->
            							<table id="example3" style="min-width: 845px" class="table table-bordered table-hover table-checkable display">

            								<thead>
            									<tr>
            										<th>S.No</th>
            										<th>Date</th>
            										<th>Investment</th>
            										<th>Withdraw</th> 
            										<th>ROI</th>

            									</tr>

            								</thead>
            								<tbody>

            									<?php
            									$sql = runloopQuery("SELECT * from df_budget  order by ID desc");
            									$x=1;  foreach($sql as $row){


            										?>
            										<tr>
            											<td><?= $x; ?></td>
            											<td><?php echo $row["reg_date"];?></td>
            											<td><?php echo $row["investment"];?> </td>
            											<td><?php echo $row["withdraw"];?> </td>
            											<td><?php echo $row["roi"];?></td>
            										</tr>
            										<?php
            										$x++; }
            										?>
            									</tbody>
            								</table>



            							</div>
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

        <div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Budget</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

      		<form method="POST" id="investmentform">
  <div class="form-group row">
  <label for="investment" class="col-sm-2 col-form-label">Investment</label>
    <div class="col-sm-4">
      <input type="text"  class="form-control" id="investment" name="investment" autocomplete="off" placeholder="Investment" value="0.00">
    </div> 
	<label for="withdraw" class="col-sm-2 col-form-label">Withdraw</label>
    <div class="col-sm-4">
      <input type="text"  class="form-control" id="withdraw" name="withdraw" autocomplete="off" placeholder="Withdraw" value="0.00">
    </div>

</div>

  <div class="form-group row">
  <label for="pl" class="col-sm-2 col-form-label">EDI</label>
    <div class="col-sm-4">
      <input type="text" readonly class="form-control-plaintext" id="edi" name="edi" value="<?= round($edi,2); ?>">
      <input type="hidden" readonly class="form-control-plaintext" id="addvendorsubmitid" name="addvendorsubmitid" value="1">
	</div>   
 
	<label for="disbursal" class="col-sm-2 col-form-label">Avg Disbursal (Current)</label>
    <div class="col-sm-1">
      <input type="text" readonly class="form-control-plaintext" id="disbursal" name="disbursal" value="<?= $avgdisbursal; ?>">
    </div>
    
    	<label for="disbursal" class="col-sm-2 col-form-label">Avg Disbursal (Total)</label>
    <div class="col-sm-1">
      <input type="text" readonly class="form-control-plaintext" id="disbursaltotal" name="disbursaltotal" value="<?= round($disbursaltotal,2); ?>">
    </div>
 
</div>

  <div class="form-group row">
  <label for="pl" class="col-sm-2 col-form-label">Merchants</label>
    <div class="col-sm-4">
		<select class="form-control">
			<option value="<?= $totalvendors; ?>">Total (<?= $totalvendors; ?>)</option>
			<option value="<?= $active_vendors_count; ?>">Active (<?= $active_vendors_count; ?>)</option>
			<option value="<?= count($sqlvendor); ?>">Active Applications (<?= count($sqlvendor); ?>)</option>
		</select>	
	</div>
    <label for="pending" class="col-sm-2 col-form-label">Pending</label>
    <div class="col-sm-4">
      <input type="text" readonly class="form-control-plaintext" id="pending" name="pending" value="<?= $pending_amount; ?>">
    </div>
</div>

  <div class="form-group row">
    <label for="liabilities" class="col-sm-2 col-form-label">Liabilities</label>
    <div class="col-sm-4">
      <input type="text" readonly class="form-control-plaintext" id="liabilities" name="liabilities" value="<?= !empty($liablitypendingamtarr) ? array_sum($liablitypendingamtarr) : 0; ?>">
    </div>
	<label for="roi" class="col-sm-1 col-form-label">ROI Current</label>
    <div class="col-sm-2">
      <input type="text" readonly class="form-control-plaintext" id="roi_current" name="roi_current" value="<?php echo round(((($current_pl/$budget)/$spentdays)*date("t")*12),2)*100  ?? 0 ?>">
    </div> 
    
    	<label for="roi" class="col-sm-1 col-form-label">ROI Avg</label>
    <div class="col-sm-2">
      <input type="text" readonly class="form-control-plaintext" id="roi" name="roi" value="<?= $roi_avg ?? 0 ?>">
    </div> 
</div>
  <div class="form-group row">
  <label for="pl" class="col-sm-1 col-form-label">P&L (Current)</label>
    <div class="col-sm-2">
      <input type="text" readonly class="form-control-plaintext" id="pl_current" name="pl_current" value="<?= $current_pl ?? 0 ; ?>">
    </div>
    <label for="pl" class="col-sm-1 col-form-label">P&L (Total)</label>
    <div class="col-sm-2">
      <input type="text" readonly class="form-control-plaintext" id="pl_total" name="pl_total" value="<?= $total_pl; ; ?>">
    </div>
   <label for="pl" class="col-sm-1 col-form-label">Penalty (Current)</label>
    <div class="col-sm-2">
      <input type="text" readonly class="form-control-plaintext" id="penalty_current" name="penalty_current" value="<?= round($penalty_amount_current,2) ; ?>">
    </div>
    <label for="pl" class="col-sm-1 col-form-label">Penalty (Total)</label>
    <div class="col-sm-2">
      <input type="text" readonly class="form-control-plaintext" id="penalty_total" name="penalty_total" value="<?= round($penalty_amount_total,2) ; ?>">
    </div>


  </div>
  <div class="form-group row">
        <label for="outsatanding" class="col-sm-2 col-form-label">Outsatanding</label>
    <div class="col-sm-4">
      <input type="text" readonly class="form-control-plaintext" id="outsatanding" name="outsatanding" value="<?= $outstanding; ?>">
    </div>
  <label for="balance" class="col-sm-2 col-form-label">Balance</label>
    <div class="col-sm-4">
      <input type="text" readonly class="form-control-plaintext" id="balance" name="balance" value="<?php echo round(($budget-$outstanding),2)  ?>">
    </div>   
	</div>
	<div class="form-group row">
	
	<label for="budget" class="col-sm-2 col-form-label">Budget</label>
    <div class="col-sm-4">
      <input type="text" readonly class="form-control-plaintext" id="budget" name="budget" autocomplete="off" placeholder="Budget" value="<?= $budget ; ?>">
      <input type="hidden" readonly class="form-control-plaintext" id="hiddenbudget" name="hiddenbudget" autocomplete="off" placeholder="Budget" value="<?= $budget ; ?>">

	</div>
	</div>    

</form>

       </div>
       

      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="button" id="budgetsubmit" class="btn btn-primary" >Add Budget</button>
      </div>
    </div>
  </div>
</div> 	

        <script>
        	$("#investment").change(function(){
        		var budget = parseFloat($("#investment").val()) +  parseFloat($("#hiddenbudget").val()) -  parseFloat($("#withdraw").val());
        		$("#budget").val(budget);
        		var balance = (budget - parseFloat($("#outsatanding").val())).toFixed(2);
        		$("#balance").val(balance);
        	});
        	$("#withdraw").change(function(){
        		var budget = parseFloat($("#hiddenbudget").val()) -  parseFloat($("#withdraw").val()) + parseFloat($("#investment").val());
        		$("#budget").val(budget);
        		var balance = (budget - parseFloat($("#outsatanding").val())).toFixed(2);
        		$("#balance").val(balance);
        	});

        	$("#budgetsubmit").click(function(){
        		$("#investmentform").submit();
        	});

        	$("#projectnext").click(function(){
        		var balance = $("#balance").val();
        		var edi = $("#edi").val();
        		window.location.assign("df-projection.php?balance="+balance+"&totaledi="+edi);	
        	});

        </script>

    </body>
    </html>