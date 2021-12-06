<?php
session_start();
error_reporting(E_ALL); 
include('../functions.php');
$userid = current_adminid(); 
if(empty($userid)){
	header("Location: login.php");
} 
$message = '';

if(!empty($_GET['delete'])){
	$sql = "DELETE FROM investment WHERE id=".$_GET['delete']."";

if ($conn->query($sql) === TRUE) {
   
header("Location: investment.php?success=success");
   
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
							<h4 class="page-title" style="color:#886CC0">Investment Enquirys</h4>
							<a href="csv-loanenquires.php?table=investment"><button type="button" class="btn btn-primary" style="float:right" data-toggle="modal" data-target="#exampleModalScrollable">Download</button></a> 
						</div>
						<div class="card-body">

							<div class="table table-responsive">

								<!--begin: Datatable -->
								<table id="example3" style="min-width: 845px" class="table table-bordered table-hover table-checkable display">
									<thead>
										<tr>
							<th>S.No</th>

                            <th>Name</th>
							<th>Phone No</th>
							<th>Email</th>
							<th>Location</th>
							<th>Invest Amount</th>
							<th>Intersted In</th> 
							<th>Reg date</th> 
							<th>Delete</th> 
						</tr>
							</thead>
								<tbody>
									 <?php
$sql = runloopQuery("SELECT * FROM investment order by ID desc");

   $x=1;  foreach($sql as $row)
		{
?>
<tr>
<td><?php echo  $x;?></td>
<td><?php echo $row["name"];?></td>
<td><?php echo $row["phone"];?></td>
<td><?php echo $row["email"];?></td>
<td><?php echo $row["location"];?></td>
<td><?php echo $row["investment"];?></td>
<td><?php echo $row["interestedin"];?></td>
<td><?php echo reg_date($row["reg_date"]);?></td>

<td><a onclick="return confirm('Are you sure want to delete??');"  href="?delete=<?php echo $row["ID"];?>">Delete</a></td>
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