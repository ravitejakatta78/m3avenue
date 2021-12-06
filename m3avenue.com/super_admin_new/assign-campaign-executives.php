<?php
session_start();
error_reporting(E_ALL); 
include('../functions.php');
$userid = current_adminid(); 
if(empty($userid)){
	header("Location: login.php");
} 
$message = '';
$user_unique_id = employee_details($userid,'unique_id');

$campainid = $_GET['campaign'];
if(empty($userid)){

	header("Location: campaigns-list.php");

}
$compaindetails = runQuery("select * from campaigns where ID = '".$campainid."'");
if(empty($compaindetails)){

	header("Location: campaigns-list.php");
}
$message = '';
if(!empty($_POST['submited'])){ 
	   $pagerarray  =	   $pagewhererarray  = array();
	   $pagewhererarray['ID'] = $_POST['submited'];
	   $execuitveids = !empty($_POST['executivelist']) ? implode(',',$_POST['executivelist']) : '';
	$pagerarray['executive'] = $execuitveids;  
	
	$result = updateQuery($pagerarray,'campaigns',$pagewhererarray);
	if(!$result){
		header("Location: assign-campaign-executives.php?campaign=".$pagewhererarray['ID']."&success=success");
		}
	     

}

if(!empty($_GET['delete'])){
	$sql = "DELETE FROM executive WHERE ID=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: executive-list.php?success=success");
   
} else {
    echo "Error deleting record: " . $conn->error;
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
							<h4 class="page-title" style="color:#886CC0">Select Executives for <?php echo $compaindetails['title'];?></h4>

							 

							<a href="campaigns-list.php" class="btn btn-primary pull-right" >Back to Campaigns</a> 
						</div>
						<div class="card-body">

							 <?php if(!empty($_GET['success'])){?>
								<div class="alert alert-success">
								Executives Assigned Succussfully
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
							<div class="table table-responsive">

								<!--begin: Datatable -->
								<table id="example3" style="min-width: 845px" class="table table-bordered table-hover table-checkable display">
									<thead>
										<tr>
							<th>S.No</th>
							<th>Executive Id</th>
                            <th>Full Name</th>   
                            <th>Manager</th>   
                            <th>Location</th>  
						</tr> 
							</thead>
								<tbody>
								  <?php
								  
								  $executivearray = explode(',',$compaindetails['executive']);
$sql = runloopQuery("SELECT * FROM employee where leader='".$user_unique_id."' and role_id in ('4','3') and status = '1' order by ID desc");
//print_r($sql);
   $x=1;  foreach($sql as $row)
		{
?>
<tr>
<td><input type="checkbox" id="checkbox_<?php echo $row["ID"];?>" name="executivelist[]" class="class1" value="<?php echo $row["ID"];?>" <?php if(in_array($row["ID"],$executivearray)){ echo 'checked';}?>  /></td>
<td><?php echo $row["unique_id"];?></td>
<td><?php echo $row["fname"].' '.$row["lname"];?> </td>
<td><?php echo $row['role_id']=='1' ? 'Admin' : $row['leader']==' '?'' :ucwords(manager_details_with_unique_id($row['leader'],'fname'));?>
<br>
<?php echo  $row['role_id']=='3' ? 'Manager' : '';?></td>
<td><?php echo $row["location"];?></td>   
</tr>
<?php
     $x++; }
?>
                          </tbody>
					</table>

					</form>
				<div class="">
						<button type="submit" id="checksubmit" class="btn btn-primary" name="submit" value="<?php echo $compaindetails['ID'];?>" >Submit</button>
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

    <script>
var myArray = [];
var id = "";
var oTable = $("#example3").dataTable();

$('#checksubmit').click( function() {
var checksubmit = $("#checksubmit").val();
	let arr= [];
let checkedvalues = oTable.$('input:checked').each(function () {
	var splitArr = $(this).attr('id').split("_");
arr.push(splitArr[1])
});
if(arr.length > 0) {
//arr = arr.toString();
var form=document.createElement('form');
        form.setAttribute('method','post');
        form.setAttribute('action','assign-campaign-executives.php?campaign='+checksubmit);
//        form.setAttribute('target','_blank');

    var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "submited");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", checksubmit);
    form.appendChild(hiddenField);

	var hiddenField = document.createElement("input");
    hiddenField.setAttribute("name", "executivelist[]");
    hiddenField.setAttribute("type", "hidden");
    hiddenField.setAttribute("value", arr);
    form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();    
}

});	
 
</script>

</body>
</html>