<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_adminid(); 

if(empty($userid)){

	header("Location: index.php");

}

$message = '';
if(!empty($_POST['vendorsubmit'])){
	

				unset($_POST['vendorsubmit']);
				$uniqueusers = (int)runQuery("select max(ID) as id from df_vendor_application order by ID desc")['id'];
				$newuniquid = $uniqueusers+1;
				$_POST['application_unique_id'] = 'VENDAPP'.sprintf('%05d',$newuniquid);
				$lastid = insertIDQuery($_POST,'df_vendor_application');
				if(!empty($lastid)){
					$insertarr['edi'] =	$_POST['edi'];
					$insertarr['vendor_id'] = $_POST['vendor_id'];
					$insertarr['application_id'] = $lastid;
					$insertarr['installment_date'] = date('Y-m-d');
					$insertarr['paid_amount'] = 0;
					$insertarr['pending_amount'] = 0;
					$insertarr['penalty'] = 0;
					$insertarr['balance_amount'] = $_POST['principal_amount'];
					$res = insertQuery($insertarr,'df_transaction_details');	
					if(!$res){
						header("Location: df-application-list.php?success=success");
					}
					
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
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
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
				<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
					<h4 class="page-title">Application list</h4> 
				</div>
				<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
					<button type="button" class="btn btn-primary" style="float:right" data-toggle="modal" data-target="#newModal">Add Application</button>    
				</div>
			</div>    
			<?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Application Added Succussfully
								</div>
								<?php } ?>
			
						   <?php if(!empty($_GET['psuccess'])){?>
								<div class="alert alert-success">
								<?php echo $_GET['psuccess'];?>
								</div>
								<?php } ?>
								<?php if(!empty($message)){?>
								<div class="alert alert-danger">
								<?=$message?>
								</div>
								<?php } ?>
		<div class="kt-portlet__body">
		<div class="table table-responsive">
						<!--begin: Datatable -->
			<table class="table table-bordered table-hover table-checkable" id="kt_table_1">
					<thead>
					<tr>
								<th>S.No</th>
								<th>Vendor Name</th>
								<th>Application Id</th>
								<th>Status</th>
								<th>Date</th>
								<th>View</th>

							</tr>
						
							</thead>
								<tbody>

								<?php
							$sql = runloopQuery("SELECT dva.*,dv.vendor_name,dv.vendor_location FROM df_vendor_application dva inner join 
							df_vendor dv on dv.ID = dva.vendor_id  order by dva.ID desc");
							$x=1;  foreach($sql as $row){
							    
                            	$sqloutstanding = runQuery("select balance_amount,pending_amount,edi from df_transaction_details where application_id ='".$row['ID']."'  order by ID desc limit 1");

							$mydate =	strtotime(date('d-M-Y',strtotime($row["reg_date"])));
							?>
							<tr>
							<td><?= $x; ?></td>
							<td><?php echo $row["vendor_name"].' ('.$row["vendor_location"].")";?></td>
							<td><a  style="cursor:pointer" onclick="preview(<?= $row['ID'] ?>)"><span class="w3-tag w3-orange"><?php echo @$row["application_unique_id"];?></span></a> </td>
							<td><?php echo $row["status"] == 1 ? 'Active' : 'Closed' ; ?></td>
							<td data-sort="<?= $mydate; ?>"><?php echo date('d-M-Y',strtotime($row["reg_date"]));?></td>
							<td><a href="df-detail-application.php?id=<?= $row['ID'] ?>" > View </a> </td>
							</tr>
<?php
								$x++; }
							?>
                          </tbody>
					</table>
				<!--end: Datatable -->
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
		<div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Vendor</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	  <form  method="post" action="" enctype="multipart/form-data" id="addvendorform" autocomplete="off" >
						<div class="form-group row">
						    
                        <label for="example-text-input" class="col-md-2 col-form-label">Vendor Name</label>
                        <div class="col-md-4">
						<select name="vendor_id" class="form-control select2" style="width: 100%" id="vendor_id" required>
							<option value="">Select </option>
							<?php 
								$sqlvendors = runloopQuery("select * from df_vendor order by ID");
								
								for($i=0;$i<count($sqlvendors);$i++){ ?>
									<option value="<?= $sqlvendors[$i]['ID']; ?>"><?= $sqlvendors[$i]['vendor_name'].' ('.$sqlvendors[$i]['vendor_location'].')'?></option>
								<?php }
							?>
							</select>
							<input class="form-control" name="vendorsubmit" type="hidden"  id="vendorsubmit" value="1" >

						</div>


                        <label for="example-text-input"  class="col-md-2 col-form-label" >Principle Amount</label>
                        <div class="col-md-4"  >
						<input class="form-control" name="principal_amount" type="text" placeholder="Enter Principle Amount" onchange="docals()" id="principal_amount" required>
                        </div>

						</div>
						<div class="form-group row">
						    
                        <label for="example-text-input" class="col-md-2 col-form-label">Tenure</label>
                        <div class="col-md-4">
							<select class="form-control " id="tenure" name="tenure" onchange="docals()" required>
								<option value="">Selectr Tenure</option>
								<option value="25">25</option>
								<option value="50">50</option>
								<option value="75">75</option>
								<option value="100">100</option>
							</select>
					</div>
						<label for="example-text-input" class="col-2 col-form-label">Enter Intrest</label>
                        <div class="col-4">
                            <input class="form-control" name="intrest" type="text" placeholder="Enter Intrest" id="intrest" readonly required>
                        </div>
						</div>
						<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Enter Processing Fee</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="pf" placeholder="Enter Processing Fee" id="pf" readonly required>
                        </div>
						<label for="example-text-input" class="col-2 col-form-label">Enter Net Amount</label>
                        <div class="col-4">
                              <input type="text" class="form-control form-control-line" placeholder="Enter Net Amount"  name="net_amount" id="net_amount" readonly  required   >
                        </div>
                    </div>
						
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Enter E.D.I</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="edi" placeholder="Enter E.D.I" id="edi" required>
                        </div>
						<label for="example-text-input" class="col-2 col-form-label">Enter Mode Of Payment</label>
                        <div class="col-4">
                            <input class="form-control" type="text" name="mode_of_payment" placeholder="Enter Mode Of Payment" id="mode_of_payment" required>
                        </div>
                    </div>	
					<div class="form-group row">
                        
						<label for="example-text-input" class="col-2 col-form-label">Enter Transaction ID</label>
                        <div class="col-4">
						<input class="form-control" type="text" name="transaction_id" placeholder="Enter Bank Name" id="transaction_id" required>          	            
					  </div>
                    </div>	

					
					
		
 
					</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addvendorsubmitid">Add Application</button>
      </div>
    </div>
  </div>
</div> 


<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Application Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		  <div class="form-group row">
			  <label class="col-md-6">Principle Amount : <span id="text_principle_amount"></span></label>
			  <label class="col-md-6">Tenure : <span id="text_tenure"></span></label>
								</div>
							<div class="form-group row">		  
			  <label class="col-md-6">Intrest : <span id="text_intrest"></span></label>
			  <label class="col-md-6">Processing Fee : <span id="text_processing_fee"></span></label>
			  
								</div>  
								<div class="form-group row">		  
			  <label class="col-md-6">Net Amount : <span id="text_net_amount"></span></label>
			  <label class="col-md-6">E.D.I : <span id="text_edi"></span></label>
			  
								</div>  
								<div class="form-group row">		  
			  <label class="col-md-6">Application Balance : <span id="text_application_balance"></span></label>
			  <label class="col-md-6">Mode Of Payment : <span id="text_mode_of_payment"></span></label>
			  
								</div>  
								<div class="form-group row">		  
			  <label class="col-md-6">Transaction Id : <span id="text_transaction_id"></span></label>
			  <label class="col-md-6">Status : <span id="text_status"></span></label>
			  
								</div>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div> 

	<?php include("footerscripts.php");?>
	<script>
	$("#addvendorsubmitid").click(function(){
		var user_input_value;
		var err_value = 0
		$('#addvendorform').find('input,select,textarea').each(function(){
			if($(this).prop('required')){
				user_input_value  = $("#"+this.id).val();
				if(user_input_value == ''){
					if(err_value == 0){
						document.getElementById(this.id).focus();
					}
					err_value = err_value + 1;
					$("#"+this.id).css('border-color', 'red');
				}else{
					$("#"+this.id).css('border-color', '#e4e7ea');
				}
			}	 
		});
		
		if(err_value == 0)
		{
			$("#addvendorsubmitid").hide();
			$("#addvendorform").submit();	
		}
		
	});

	

function docals()
{
	var principal_amount = $("#principal_amount").val();
	var tenure = $("#tenure").val();
	var intrest,pf,net_amount,edi;
	if(principal_amount != '' && tenure != ''){
		intrest =  ((tenure * principal_amount * 0.14) / 100).toFixed(2);
		pf = (0.33 * intrest).toFixed(2);
		$("#intrest").val(intrest);
		$("#pf").val(pf);
		net_amount = (principal_amount - intrest - pf).toFixed(2);
		edi = (principal_amount/tenure).toFixed(2);
		$("#net_amount").val(net_amount);
		$("#edi").val(edi);

	}
	else{
		$("#intrest").val('');
		$("#pf").val('');
		$("#net_amount").val('');
		$("#edi").val('');
	}

}

function preview(id)
{
	$.ajax({
            type: 'post',
            url: 'ajax/commonajax.php',
            data: {
                application_id: id,
                action : 'get_df_application_details',
			
            },
            success: function(response) {
				var res = JSON.parse(response);
				$("#text_principle_amount").html(res['principal_amount']);
				$("#text_tenure").html(res['tenure']);
				$("#text_intrest").html(res['intrest']);
				$("#text_processing_fee").html(res['pf']);
				$("#text_net_amount").html(res['net_amount']);
				$("#text_edi").html(res['edi']);
				$("#text_application_balance").html(res['balance_amount']);
				$("#text_mode_of_payment").html(res['mode_of_payment']);
				$("#text_transaction_id").html(res['transaction_id']);
				$("#text_status").html(res['status']);
			
		        

			}
			});

	$("#detailModal").modal('show');

}

	</script>
	</body>

	<!-- end::Body -->
</html>