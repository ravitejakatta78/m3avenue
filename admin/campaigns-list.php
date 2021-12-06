<?php 
   session_start(); 
   
   include('../functions.php');
   
   
   
   $userid = current_adminid(); 
   
   if(empty($userid)){
   
   	header("Location: index.php");
   
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
   			$pagerarray['unique_id'] = 'M3C'.sprintf('%05d',$newuniquid);
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
      <meta charset="utf-8" />
      <title>M3  | Dashboard</title>
      <meta name="description" content="Latest updates and statistic charts">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <?php include('headerscripts.php');?>
      <style>
         select + .select2-container {
         width: 100% !important;
         }
      </style>
   </head>
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
                     <div class="kt-portlet kt-portlet--mobile">
                        <div class="kt-portlet__head">
                           <div class="kt-portlet__head-label">
                              <div class="col-10">
                                 <h3 class="kt-portlet__head-title">Campaigns List</h3>
                              </div>
                              <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                                 <button type="button" id="add_campaign_btn" class="btn  btn btn-primary  mt-2" style="float:right" data-toggle="modal" data-target="#add_campaign_model">Add Campaign</button>    
                              </div>
                              <a href="csv-campaigns.php" class="btn btn-primary pull-right" >Download</a> 
                           </div>
                        </div>
                        <div class="kt-portlet__body">
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
                              <table class="table table-bordered table-hover table-checkable" id="kt_table_1">
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
                                       $sql = runloopQuery("SELECT * FROM campaigns  order by ID desc");
                                       
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
                              <!--end: Datatable -->
                           </div>
                        </div>
                     </div>
                  </div>
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
      <!-- Modal -->
      <div class="modal fade" id="add_campaign_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
         <div class="modal-dialog modal-dialog-scrollable h-75 w-100 modal-lg"  role="document">
         <form  id="addcmpform" method="post" action=""  autocomplete="off" >   
         <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalScrollableTitle">Add Campaign</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
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
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
   <!-- end::Body -->
</html>
