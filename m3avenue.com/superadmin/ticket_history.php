<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_adminid(); 

if(empty($userid)){

	header("Location: index.php");

}
$roles = roles();
$message = '';

						 
if(!empty($_POST['add_designation_submit']))
{
	if(!empty($_POST['designation_name']))
    { 
        if(empty($_POST['designation_id']))
        { 
	            $pagerarray  = array();  
                $pagerarray['designation_name'] = mysqli_real_escape_string($conn,$_POST['designation_name']); 
                $pagerarray['role_under'] = mysqli_real_escape_string($conn,$_POST['role_id']); 
	            $result = insertQuery($pagerarray,'tbl_designations');
				if(!$result){
					header("Location: designations.php?success=success");
                }
        } 
        else
        {
            $sql = "update  tbl_designations set designation_name='".$_POST['designation_name']."',role_under='".$_POST['role_id']."' WHERE ID=".$_POST['designation_id']."";
            if ($conn->query($sql) === TRUE) {   
                header("Location: designations.php?esuccess=success"); 
            } 
            else {
                echo "Error deleting record: " . $conn->error;
            }   
        }				 
    }else{
        $message .="Designation Field is Empty";
    } 
}
if(!empty($_POST['pointsupdate'])){
	$sql = "update  tbl_designations set designation_name='".$_POST['designation_name']."',role_under='".$_POST['role_id']."' WHERE ID=".$_GET['editworksource']."";

    if ($conn->query($sql) === TRUE) {   
        header("Location: designations.php?esuccess=success"); 
    } 
    else {
        echo "Error deleting record: " . $conn->error;
    }    
}
if(!empty($_GET['delete'])){
	$sql = "DELETE FROM tbl_designations WHERE ID=".$_GET['delete']."";

    if ($conn->query($sql) === TRUE) {
    
    header("Location: designations.php?dsuccess=success");
    
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

if(!empty($_GET['approve']))
{
	$ticket_id=$_GET['approve'];


	$ticket_details = runloopQuery("SELECT * FROM tbl_tickets WHERE ID=".$_GET['approve']." order by ID desc");

	$ticket_values=json_decode($ticket_details[0]['modified_values']);

	//print_r($ticket_values); exit;

	$pagerarray  = array();

    $pagewererarray['ID'] = $ticket_details[0]['requested_for'];  
    
	if($ticket_details[0]['ticket_type']=='edit'){
	
		$pagerarray['fname'] = mysqli_real_escape_string($conn,$ticket_values->fname);
		$pagerarray['lname'] = mysqli_real_escape_string($conn,$ticket_values->lname); 
		$pagerarray['email'] = mysqli_real_escape_string($conn,$ticket_values->email);
		$pagerarray['mobile'] = mysqli_real_escape_string($conn,$ticket_values->mobile);
		$pagerarray['joining_date'] = mysqli_real_escape_string($conn,$ticket_values->join_date);
		$pagerarray['income'] = mysqli_real_escape_string($conn,$ticket_values->ctc);
		$pagerarray['bankdetails'] = mysqli_real_escape_string($conn,$ticket_values->bankdetails);
		$pagerarray['accntnum'] = mysqli_real_escape_string($conn,$ticket_values->accntnum);
		$pagerarray['ifsccode'] = mysqli_real_escape_string($conn,$ticket_values->ifsccode);
		$pagerarray['payment_type'] = mysqli_real_escape_string($conn,$ticket_values->payment);
		$pagerarray['designation'] = mysqli_real_escape_string($conn,$ticket_values->designation);
		$pagerarray['address'] = mysqli_real_escape_string($conn,$ticket_values->address);
		$pagerarray['location'] = mysqli_real_escape_string($conn,$ticket_values->location);;
		$pagerarray['status'] = 1;
		$pagerarray['pannum'] = mysqli_real_escape_string($conn,$ticket_values->pannum);;
		$result = updateQuery($pagerarray,'employee',$pagewererarray);
	}
	else if($ticket_details[0]['ticket_type']=='delete')
	{
		$pagerarray['status'] = 0;
		$result = updateQuery($pagerarray,'employee',$pagewererarray);
	}

	
	$sql = "update tbl_tickets set ticket_status='approved' WHERE ID=".$_GET['approve']."";


    if ($conn->query($sql) === TRUE) {
    
    header("Location: ticket_history.php?asuccess=success");
    
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
if(!empty($_GET['reject']))
{
	$ticket_id=$_GET['reject'];

	$sql = "update tbl_tickets set ticket_status='rejected' WHERE ID=".$_GET['reject']."";

    if ($conn->query($sql) === TRUE) {
    
    header("Location: ticket_history.php?rsuccess=success");
    
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>
<!DOCTYPE html>

<html lang="en">
 
	<head>
 
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
					<div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<div class="col-12">
								<h3 class="kt-portlet__head-title">Ticket History</h3>
								</div> 
							</div>
						</div>
						<br>
						     <?php if(!empty($_GET['asuccess'])){?>
								<div class="alert alert-success">
								Ticker Approved Succussfully
								</div>
								<?php } ?>
							<?php if(!empty($_GET['rsuccess'])){?>
								<div class="alert alert-success">
								Ticker Rejected Succussfully
								</div>
								<?php } ?>	
                                
		    <div class="kt-portlet__body">
				
						<!--begin: Datatable -->
			<table class="table table-striped- table-bordered" >
					<thead>
						<tr>
							<th>S.No</th>
                            <th>Ticket Type</th>
                            <th>Requested By</th>
                            <th>Requested for</th>  
                            <th>Ticket Summary</th>
							<th>Ticket Values</th>  
							<th>Ticket Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						 <?php
						 
						$sql = runloopQuery("SELECT * FROM tbl_tickets  order by ID desc");
						 
					   $x=1;  foreach($sql as $row)
							{
					?>
                        <tr>
                        <td><?php echo  $x;?></td> 
                        <td><?php echo $row["ticket_type"];?></td> 
                        <td><?php echo $row['requested_by']; ?></td>
                        <td><?php echo $row['requested_for'];?></td>
                        <td><?php echo $row['ticket_summary'];?></td>

						<?php $data = array();
							$data=json_decode($row['modified_values'],TRUE);
						?>
						<td><?php  if(count($data)>0) { 
							foreach($data as $key => $value) 
							{
								echo $key.'=>'.$value;echo '<br>';
							} } ?>
						</td>	
                        <td><?php echo $row['ticket_status'];?></td>                
                        <td>
							<?php if($row['ticket_status']=='pending'){ ?>
							<a class="btn btn-primary" onclick="confirm('Would you like to Approve this Ticket?')" href="?approve=<?php echo $row["ID"];?>" >Approve</a>
							<a class="btn btn-danger" onclick="confirm('Would you like to Reject this Ticket?')" href="?reject=<?php echo $row["ID"];?>"  >Reject</a>
							<?php } ?>
						</td>       
                        </tr>
                            <?php
                                $x++; }
                            ?>					
						
							</tbody>
						</table>

									<!--end: Datatable -->
							</div>
						</div>
					 
						<!-- end:: Content -->
			</div>  

						<!-- end:: Content -->
					

						<!-- end:: Content -->
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
		

  <div class="modal" id="myModal1" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Work Source</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
		<form method="post" action="">
			<div class="modal-body">
				<div class="form-group row">			
					<label for="example-text-input" class="col-4 col-form-label">Ticket type</label>
					<div class="col-8">
						<p id="mdl_ticket_type"></p>
					</div> 
				</div>
				<div class="form-group row">			
					<label for="example-text-input" class="col-4 col-form-label">Ticket Summary</label>
					<div class="col-8">
						<p id="mdl_ticket_summary"></p>
					</div> 
				</div>
				<div class="form-group row">			
					<label for="example-text-input" class="col-4 col-form-label">Ticket Values</label>
					<div class="col-8">
						<p id="mdl_ticket_values"></p>
					</div> 
				</div>					
		</div>
		<div class="modal-footer">
			<button type="submit" name="pointsupdate" value="Submit" class="btn btn-brand btn-elevate btn-pill">Update</button>
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      </form>	
    </div>
  </div>
</div>
  

  <script>	
	 
     $('.approve').on('click',function()
     {
		var index=$(this).data('index');
		$.ajax({
            type: 'post',
            url: '',
            data: {
                index:index,ticket_status:"approved",ticket_type:"approve",ticket_id: ticket_id
            },
            success: function(response) 
            {
                alert('Ticket Status Successfully Changed'); 
                location.reload();
            }
	    });
	});
	$('.reject').on('click',function()
     {

		$.ajax({
            type: 'post',
            url: '',
            data: 
			{
                index:index,ticket_status:"rejected",ticket_type:"reject",ticket_id: ticket_id
            },
            success: function(response) 
            {
                alert('Ticket Status Successfully Changed'); 
                location.reload();
            }
	    });	
     });

     function change_ticket_status(ticket_id)
     {
        $.ajax({
            type: 'post',
            url: '',
            data: {
                ticket_id: ticket_id
            },
            success: function(response) 
            {
                alert('Ticket Status Successfully Changed'); 
                location.reload();
            }
	    });
     }


	 function changeemployeestatus(name,id,ticket_id){
	    
		$.ajax({
            type: 'post',
            url: '../admin/ajax/commonajax.php',
            data: {
                action: 'employeechangestatus',
                empid: id
            },
            success: function(response) 
            {
                alert('Employee status Successfully Changed'); 
            }
	    });

    };



	 
	 </script>
	</body>

	<!-- end::Body -->
</html>