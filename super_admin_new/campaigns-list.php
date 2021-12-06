<?php
session_start();
error_reporting(E_ALL); 
include('../functions.php');
$userid = current_adminid(); 
$user_unique_id = employee_details($userid,'unique_id');
if(empty($userid)){
	header("Location: login.php");
} 
$message = '';

   if(!empty($_POST['submit']))
   {
   		if(!empty($_POST['title']))
   		{
   			$pagerarray  = array();
   
   			$uniqueusers = (int)runQuery("select max(ID) as id from campaigns order by ID desc")['id'];
   			$newuniquid = $uniqueusers+1; 
   			$pagerarray['title'] = mysqli_real_escape_string($conn,$_POST['title']); 
   			$pagerarray['user_id'] = $userid; 
   			$pagerarray['user_role'] = 'superadmin'; 
   			$pagerarray['unique_id'] = $user_unique_id;
   			$pagerarray['manager_id'] = !empty($_POST['manager']) ? implode(',',$_POST['manager']) : ''; 
   			$pagerarray['service'] = mysqli_real_escape_string($conn,$_POST['servicetype']);  
   			$pagerarray['timeframe'] = mysqli_real_escape_string($conn,$_POST['timeframe']);  
   			$pagerarray['feedbackoptions'] = !empty($_POST['feedbackoptions']) ? implode(',',$_POST['feedbackoptions']) : '';
   
   			$pagerarray['status'] = 1; 
   			
   			$result = insertQuery($pagerarray,'campaigns');
   			if(!$result){
   				header("Location: campaigns-list.php?success=success");
   				}
   
   		}else{
   			$message .="Campaigns title is Empty";
   	}
   
   }
   
   if(!empty($_GET['delete'])){
   	$sql = "DELETE FROM campaigns WHERE ID=".$_GET['delete']."";
   
   if ($conn->query($sql) === TRUE) {
      
   header("Location: campaigns-list.php?success=success");
      
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
							<h4 class="page-title" style="color:#886CC0">Campaigns List</h4>

							<button type="button" class="btn btn-primary" style="float:right" data-bs-toggle="modal" data-bs-target=".add_campaign_form" data-toggle="modal" data-target="#add_campaign_model">Add Campaign</button> 

							<a href="csv-campaigns.php"><button type="button" class="btn btn-primary" style="float:right">Download</button></a> 
						</div>
						<div class="card-body">

							 <?php if(!empty($_GET['success'])){?>
                           <div class="alert alert-success">
                              Campaigns Added Succussfully
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
                                       <th>Created By</th>
                                       <th>Campaigns Id</th>
                                       <th>Title</th>
                                       <th>Timeframe (seconds)</th>
                                       <th>Service</th>
                                       <th>Feedback options</th>
                                       <th>Manager</th>
                                       <th>Status</th>
                                       <th>Assign executives</th>
                                       <th>Upload excell</th>
                                       <th>Pending data</th>
                                       <th>Reg date</th>
                                       <th>Delete</th>
                                       <!--<th>Status:</th>-->
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <?php
                                       $sql = runloopQuery("SELECT * FROM campaigns where unique_id='".$user_unique_id."' order by ID desc");

                                       print_r($user_unique_id);
                                       
                                          $x=1;  foreach($sql as $row)
                                       		{
                                       			$feedbacknames = runQuery("SELECT group_concat(title) as campname FROM feedbackoptions WHERE  FIND_IN_SET_X('".$row['feedbackoptions']."',ID)");
                                       			$managersnames = array();
                                       			if(!empty($row['manager_id'])){
                                       			$managersnames =  runloopQuery("SELECT name FROM `manager` WHERE find_in_set_x('".$row['manager_id']."',ID)");
                                       			if(!empty($managersnames)){
                                       			$managersnames = array_column($managersnames, 'name'); 
                                       			}  
                                       			}  
                                       ?>
                                    <tr>
                                       <td><?php echo  $x;?></td>
                                       <td><?php echo $row['user_role']=='superadmin' ? 'Admin' : ucwords(manager_details($row['user_id'],'name'));?>
                                          <br>
                                          <?php echo  $row['user_role']=='manager' ? ucwords($row['user_role']) : '';?>
                                       </td>
                                       <td><?php echo $row["unique_id"];?></td>
                                       <td><?php echo $row["title"];?></td>
                                       <td><?php echo $row["timeframe"];?></td>
                                       <td><?php echo $row["service"];?></td>
                                       <td><?php echo $feedbacknames["campname"];?></td>
                                       <td><?php echo implode(',',$managersnames);?></td>
                                       <td><label class="switch">
                                          <input type="checkbox" <?php if($row['status']=='1'){ echo 'checked';}?> onChange="changestatus('campaigns',<?php echo $row['ID'];?>);">
                                          <span class="slider round"></span>
                                          </label>
                                       </td>
                                       <td><a href="assign-campaign-executives.php?campaign=<?php echo $row["ID"];?>">Assign</a></td>
                                       <td><a href="upload-campaign-excell.php?campaign=<?php echo $row["ID"];?>">Upload</a></td>
                                       <td><a href="campaigns-users.php?campaign=<?php echo $row["ID"];?>">Data</a></td>
                                       <td><?php echo reg_date($row["reg_date"]);?></td>
                                       <td><a href="edit-campaigns-list.php?edit=<?php echo $row["ID"];?>">Edit</a> | <a onclick="return confirm('Are you sure want to delete??');"  href="?delete=<?php echo $row["ID"];?>">Delete</a></td>
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

     <!-- Modal -->
      <div class="modal fade add_campaign_form" id="add_campaign_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
         <div class="modal-dialog modal-dialog-scrollable h-75 w-100 modal-lg"  role="document">
         <form  id="addcmpform" method="post" action=""  autocomplete="off" >   
         <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalScrollableTitle">Add Campaign</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal">
                                                    </button>
                  </button>
               </div>
               
                  <div class="modal-body">
                     <div class="row">
                        <div class="form-group col-md-6">
                           <label for="example-text-input" class="col-12 col-form-label">Enter title</label>
                           <div class="col-md-12">
                              <input class="form-control" required name="title" type="text" placeholder="Enter title" id="campaign_title">
                           </div>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="example-text-input" class="col-12 col-form-label">Time frame (seconds)</label>
                           <div class="col-md-12">
                              <input class="form-control"  name="timeframe" type="text" placeholder="Enter Time frame"  >
                           </div>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="example-text-input" class="col-12 col-form-label">Service type</label>
                           <div class="col-md-12">
                              <input class="form-control"  name="servicetype" type="text" placeholder="Enter Service type"  >
                           </div>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="example-text-input" class="col-12 col-form-label">Manager</label>
                           <div class="col-md-12">
                              <select class=" col-12 form-control select2" name="manager[]" multiple  >
                                 <option value="">--Select Manager--</option>
                                 <?php $campaignsmanagers = runloopQuery("select * from manager order by ID desc");
                                    foreach($campaignsmanagers as $campaigns){
                                    ?>
                                 <option value="<?php echo $campaigns['ID'];?>"><?php echo $campaigns['name'];?></option>
                                 <?php }?>
                              </select>
                           </div>
                        </div>
                        <div class="form-group col-md-6">
                           <label for="example-text-input" class="col-md-12 col-form-label">Feedback list</label>
                           <div class="col-md-12">
                              <select class="form-control select2" name="feedbackoptions[]" multiple >
                                 <option value="">--Select Feedback--</option>
                                 <?php $campaignsmanagers = runloopQuery("select * from feedbackoptions order by ID asc");
                                    foreach($campaignsmanagers as $campaigns){ ?>
                                 <option value="<?php echo $campaigns['ID'];?>"><?php echo $campaigns['title'];?></option>
                                 <?php }?>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="form-group row">
                        <div class="col-3">
                           <!-- <button type="submit" name="submit"  id="addcmpsubmitid" value="submit" class="btn btn-brand btn-elevate btn-pill">Add Campaigns</button> -->
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                     <button type="submit" name="submit"  id="addcmpsubmitid"  value="submit" class="btn btn-primary">Add Campaign</button>
                     <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                  </div>
            </div>
               </form>
         </div>
      </div>
      <script>
         $('#add_campaign_btn').on('click',function()
         {
         	$("#add_campaign_model").modal('show');
         });
         
         $("#addcmpsubmitid").click(function(){
         	var user_input_value;
         	var err_value = 0;
         	$('#addcmpform').find('input,select,select2').each(function()
         	{
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
         		$("#addcmpsubmitid").hide();
         		$("#addcmpform").submit();	
         	}
         });
         
         
      </script>

</body>
</html>