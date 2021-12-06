<?php
session_start();
error_reporting(E_ALL);
include('../functions.php');
$userid = current_employeeid();
if(empty($userid)){
	header("Location:index.php");
	}
$message = '';
$usedetails = runQuery("select * from employee where ID = '".$userid."'");
if(!empty($_POST['submit'])){
          if(!empty($_POST['fname'])){
			if(!empty($_POST['lname'])){
				if(!empty($_POST['mobile'])){
					if(!empty($_POST['pannum'])){
						if(!empty($_POST['marital_status'])){
					         	if(!empty($_POST['gender'])){
							        if(!empty($_POST['occuption'])){
							        if(!empty($_POST['income'])){
							        if(!empty($_POST['emergency_contact'])){ 
			$m3avenuearray = $m3avenuewherearray = array(); 
$m3avenuewherearray['ID']=$userid;
$m3avenuearray['fname']=mysqli_real_escape_string($conn,$_POST['fname']);
$m3avenuearray['lname']=mysqli_real_escape_string($conn,$_POST['lname']);
$m3avenuearray['dob']=mysqli_real_escape_string($conn,$_POST['dob']);
$m3avenuearray['mobile']=mysqli_real_escape_string($conn,$_POST['mobile']);
$m3avenuearray['pannum']=mysqli_real_escape_string($conn,$_POST['pannum']);
$m3avenuearray['marital_status']=mysqli_real_escape_string($conn,$_POST['marital_status']);
$m3avenuearray['gender']=mysqli_real_escape_string($conn,$_POST['gender']);
$m3avenuearray['occuption']=mysqli_real_escape_string($conn,$_POST['occuption']);
$m3avenuearray['income']=mysqli_real_escape_string($conn,$_POST['income']);
$m3avenuearray['emergency_contact']=mysqli_real_escape_string($conn,$_POST['emergency_contact']);

	if(!empty($_FILES["profilepic"]['name'])){
  if (!file_exists('employeeimages')) {	
		mkdir('employeeimages', 0777, true);	
		}
	    $target_dir = 'employeeimages/';									

		$file = $_FILES["profilepic"]['name'];				
		$target_file = $target_dir . strtolower($file);		

		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);								

		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" )

			{
		$message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
		$uploadOk = 0;						
		}
	    if ($uploadOk == 0) {			
		$message .= "Sorry, your file was not uploaded.";		
		} else {
			if (move_uploaded_file($_FILES["profilepic"]["tmp_name"], $target_file)){				
				$m3avenuearray['profilepic'] = strtolower($file);						
			} else {
				$message .= "Sorry, There Was an Error Uploading Your File.";			
				}
			}
	}
			$result= updateQuery($m3avenuearray,'employee',$m3avenuewherearray);
			
 
if(!$result){

 
header("Location:profile.php?success=success");
}else{
		$message .=$result;
}

		}else{
		$message .="Emergency Contact field is empty";
	}
	}else{
		$message .="Income field is empty";
	}
	}else{
		$message .="Occuption field is empty";
	}
	}else{
		$message .="Gender field is empty";
	}	
	}else{
		$message .="marital status field is empty";
	}
	}else{
		$message .="Pancard Number  field is empty";
	}
	
	}else{
		$message .="Mobile  field is empty";
	}
	
	}else{
		$message .="Last Name field is empty";
	}
	
	}else{
		$message .="First Name field is empty";
	}
	
}

if(!empty($_POST['password-submit'])){
	if(!empty($_POST['new_pass'])){
		if(!empty($_POST['confirm_pass'])){
			if($_POST['new_pass']===$_POST['confirm_pass']){
				$newepass = mysqli_real_escape_string($conn,$_POST['new_pass']);
				$confirmepass = mysqli_real_escape_string($conn,$_POST['confirm_pass']); 
					$newpass = password_hash($newepass,PASSWORD_DEFAULT);
					$insert_sql = "update employee set password = '".$newpass."' where ID = $userid";
					if($conn->query($insert_sql)===TRUE){ 
						header("location: profile.php?password=Password Changed successfully");
						}else{
							$loginmessage .=$conn->error;
						}
					}else{
					$loginmessage .="Password mistach";
					}
				}else{
				$loginmessage .="please enter the confirm password";	
				}
			}else{	
			$loginmessage .="please enter the new password";	
			}	
		}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../plugins/images/favicon.png">
    <title>M3 Dashboard</title>
   
       <?php include('headerscripts.php');?>
</head>

<body>
    <!-- Preloader -->
   
    <div id="wrapper">
        <!-- Navigation -->
       <?php include('header.php');?>
        <!-- Left navbar-header end -->
		
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Dashboard</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb"> 
                            <li class="active">Dashboard</li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
                <!-- .row -->
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <div class="white-box"> 
								
									<div class="row">
								<form  method="get" action="" enctype="multipart/form-data">
									<div class="col-md-4">
								  <div class="form-group"> 
										<select class="form-control" name="year">
											<option value="">--Select--</option> 
											<option value="2019" <?php if(!empty($_GET['year'])&&$_GET['year']=='2019'){ echo 'selected';}?>>2019</option> 
											<option value="2020" <?php if(!empty($_GET['year'])&&$_GET['year']=='2020'){ echo 'selected';}?>>2020</option> 
											<option value="2021" <?php if(!empty($_GET['year'])&&$_GET['year']=='2021'){ echo 'selected';}?>>2021</option> 
											<option value="2022" <?php if(!empty($_GET['year'])&&$_GET['year']=='2022'){ echo 'selected';}?>>2022</option> 
										</select>
										</div> 
										</div> 
								<div class="col-md-4">
								  <div class="form-group"> 
										<select class="form-control" name="month">
											<option value="">--Select--</option>
											<?php for($i=1;$i<=12;$i++){?>
											<option value="<?php echo $i;?>" <?php if(!empty($_GET['month'])&&$_GET['month']==$i){ echo 'selected';}?> ><?php echo date('F',mktime(0, 0, 0, $i, 10));?></option>
											<?php }?>
										</select>
									</div>
									</div>
									<div class="col-md-4">
								  <div class="form-group"> 
										<button class="btn btn-success">Search</button>
										<a href="dashboard.php" class="btn btn-danger">Reset</a>
									</div>
									</div>
									</form>
									</div>
									
							 <div class="row">
							<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
							
									</div>
							
							 <div class="row">
		 <div class="col-md-6">
			<div id="chartone" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
			
			</div>
		 <div class="col-md-6">
			<div id="charttwo" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
			
			</div>
			</div>
							 <div class="row">
		 <div class="col-md-6">
			<div id="chartleads" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
			
			</div> 
			<div class="col-md-6"> 
			 <div id="emppoints" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
			</div> 
			</div>
			
			
						</div> 
                    </div>
                     
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
<?php include('footer.php');?>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
   
   <?php include("footerscripts.php");?>
   <?php 
	$totalarray = array();
	
	
	if(!empty($_GET['year'])&&!empty($_GET['month'])){ 	
	$year =  $_GET['year'];
		$month =  $_GET['month'];
 
   for($x=1;$x<=31;$x++){
	   $dateslist[] = $x;
	   $datet = strlen($x)<2 ? '0'.$x : $x; 
	   $fromdate = $year.'-'.$month."-".$datet; 
	   $todate = $year.'-'.$month."-".$datet;  
			$sql = runQuery("SELECT count(*) as count FROM track_work where employee_id = '".$userid."' and reg_date >='".$fromdate." 00:00:00' and reg_date <='".$todate." 23:59:59' order by ID desc"); 
		if(!empty($sql)){
			$totalarray[] = $sql['count'];
		}else{
			$totalarray[] = 0;
		}
   }
	$datscomma = implode("', '",$dateslist);
	$datetext = $year.' '.date("F", mktime(0, 0, 0, $month, 10));
   ?>
   <script>
   Highcharts.chart('container', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Leads'
    },
    subtitle: {
        text: '<?php echo $datetext;?> Leads'
    },
    xAxis: {
        categories: ['<?php echo $datscomma;?>']
    },
    yAxis: {
        title: {
            text: 'Leads'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'My Leads',
        data: [<?php echo implode(", ",$totalarray);?>]
    }]
});
   </script>
   
	<?php 
	
	}elseif(!empty($_GET['year'])&&empty($_GET['month'])){ 
		if(!empty($_GET['year'])){
			$year =   $_GET['year'];
		}else{ 
			$year =   date('Y');
		}
		
   for($x=1;$x<=12;$x++){
	   $fromdate = $year.'-'.date("m", mktime(0, 0, 0, $x, 10))."-01"; 
	   $todate = $year.'-'.date("m", mktime(0, 0, 0, $x, 10))."-31"; 
			$sql = runQuery("SELECT count(*) as count FROM track_work where employee_id = '".$userid."' and reg_date >='".$fromdate." 00:00:00' and reg_date <='".$todate." 23:59:59' order by ID desc");
		if(!empty($sql)){
			$totalarray[] = $sql['count'];
		}else{
			$totalarray[] = 0;
		}
   }?>
   <script>
   Highcharts.chart('container', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Leads'
    },
    subtitle: {
        text: '<?php echo $year;?> Leads'
    },
    xAxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    yAxis: {
        title: {
            text: 'Leads'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'My Leads',
        data: [<?php echo implode(", ",$totalarray);?>]
    }]
});
   </script>
   
	<?php }else{  
	$year =  date('Y');
		$month =   date('m');
 
   for($x=1;$x<=31;$x++){
	   $dateslist[] = $x;
	   $datet = strlen($x)<2 ? '0'.$x : $x; 
	   $fromdate = $year.'-'.$month."-".$datet; 
	   $todate = $year.'-'.$month."-".$datet;  
			$sql = runQuery("SELECT count(*) as count FROM track_work where employee_id = '".$userid."' and reg_date >='".$fromdate." 00:00:00' and reg_date <='".$todate." 23:59:59' order by ID desc"); 
		if(!empty($sql)){
			$totalarray[] = $sql['count'];
		}else{
			$totalarray[] = 0;
		}
   }
	$datscomma = implode("', '",$dateslist);
	$datetext = $year.' '.date("F", mktime(0, 0, 0, $month, 10));
   ?>
   <script>
   Highcharts.chart('container', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Leads'
    },
    subtitle: {
        text: '<?php echo $datetext;?> Leads'
    },
    xAxis: {
        categories: ['<?php echo $datscomma;?>']
    },
    yAxis: {
        title: {
            text: 'Leads'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'My Leads',
        data: [<?php echo implode(", ",$totalarray);?>]
    }]
});
   </script>
	
	<?php }?>
	
	<?php 
	
	if(!empty($_GET['year'])&&!empty($_GET['month'])){
		$year = $_GET['year'];
		$month = $_GET['month'];
		 $fromdate = $year.'-'.date("m", mktime(0, 0, 0, $month, 10))."-01"; 
	   $todate = $year.'-'.date("m", mktime(0, 0, 0, $month, 10))."-31"; 
	   $datatext = date('Y F',strtotime($fromdate));
	}else{
		$year =  date('Y');
		$month =   date('m');
		 $fromdate = $year.'-'.$month."-01"; 
	   $todate = $year.'-'.$month."-31"; 
	   $datatext = date('Y F',strtotime($fromdate));
	}
	
	$pendingclients = runQuery("select count(*) as count from clients where employee_id = '".$userid."' and status = 'Pending' and reg_date >='".$fromdate." 00:00:00' and reg_date <='".$todate." 23:59:59' order by ID desc");
	$approvedclients = runQuery("select count(*) as count from clients where employee_id = '".$userid."' and status = 'Approved' and reg_date >='".$fromdate." 00:00:00' and reg_date <='".$todate." 23:59:59' order by ID desc");
	$rejectclients = runQuery("select count(*) as count from clients where employee_id = '".$userid."' and status = 'Reject' and reg_date >='".$fromdate." 00:00:00' and reg_date <='".$todate." 23:59:59' order by ID desc"); 
	
	
	$totalclientsarray = runloopQuery("select * from clients where employee_id = '".$userid."' and reg_date >='".$fromdate." 00:00:00' and reg_date <='".$todate." 23:59:59' order by ID desc");
			$pendingclientspoints = $approvedclientspoints = $rejectclientspoints = 0;
					$appamount =$rejectamount = 0;
			$x=1;  foreach($totalclientsarray as $totalclient){
				switch($totalclient["status"]){
					case 'Pending': 
					$pendingamount = $totalclient["loanamount"];
					$pendingclientspoints += (int)points($pendingamount,$totalclient["pointstype"]); 
					break;
					case 'Approved':  
					$appamount = $totalclient["loanamount"];
					$approvedclientspoints += (int)points($appamount,$totalclient["pointstype"]);
					break;
					case 'Reject':  
					$rejectamount = $totalclient["loanamount"];
					$rejectclientspoints += (int)points($rejectamount,$totalclient["pointstype"]);
					break;
				} 
			} 
			$sourcepoints = array();
	 $worksourcearray = runloopQuery("select * from worksource order by ID desc");
		 foreach($worksourcearray as $worksourcearray){
			 $srctitle = $worksourcearray['title'];
			 $sorcount = runQuery("select  count(*) as count from track_work where employee_id = '".$userid."' and source = '".$srctitle."'");
			 $sourcepoints[$srctitle] = $sorcount['count'];
			 
		 } 
	?>
	
	
	<script>
			// Build the chart
Highcharts.chart('chartone', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: '<?php echo $datatext;?> My Clients'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Clients',
        colorByPoint: true,
        data: [{
            name: 'Pending',
            y: <?php echo $pendingclients['count'];?>
        }, {
            name: 'Approved',
            y: <?php echo $approvedclients['count'];?>
        }, {
            name: 'Reject',
            y: <?php echo $rejectclients['count'];?>
        } ]
    }]
});
			// Build the chart
Highcharts.chart('charttwo', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: '<?php echo $datatext;?> Clients Points'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Clients Points',
        colorByPoint: true,
        data: [{
            name: 'Pending Points',
            y: <?php echo $pendingclientspoints;?>
        }, {
            name: 'Approved Points',
            y: <?php echo $approvedclientspoints;?>
        }, {
            name: 'Reject Points',
            y: <?php echo $rejectclientspoints;?>
        } ]
    }]
});
			// Build the chart
Highcharts.chart('chartleads', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: '<?php echo $datatext;?> Source Leads'
    },
    tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
    },
    plotOptions: {
        pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
                enabled: false
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Lead Source',
        colorByPoint: true,
        data: [
		<?php 
		$latarary = array_keys($sourcepoints);
		$last_key = end($latarary);
		foreach($sourcepoints as  $sourcekey =>  $sourcevaue){?>
			{
				name: '<?php echo $sourcekey;?>',
				y: <?php echo $sourcevaue;?>
			} <?php if ($sourcekey!=$last_key) { ?>,<?php }?>
		<?php }?>
		
		]
    }]
});
		</script>
		<?php 
		$totalemparray = array();
		 for($x=1;$x<=12;$x++){
	   $fromdate = $year.'-'.date("m", mktime(0, 0, 0, $x, 10))."-01"; 
	   $todate = $year.'-'.date("m", mktime(0, 0, 0, $x, 10))."-31"; 
			$sql = runQuery("SELECT count(*) as count FROM employee where leader = '".$usedetails['unique_id']."' and reg_date >='".$fromdate." 00:00:00' and reg_date <='".$todate." 23:59:59' order by ID desc");
		if(!empty($sql)){
			$totalemparray[] = $sql['count'];
		}else{
			$totalemparray[] = 0;
		}
   }
		 ?>
		<script>
		 Highcharts.chart('emppoints', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Team Count'
    },
    subtitle: {
        text: ''
    },
    xAxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
    },
    yAxis: {
        title: {
            text: 'Team Count'
        }
    },
    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'Month',
        data: [<?php echo implode(", ",$totalemparray);?>]
    }]
});
		</script>
		
</body>

</html>
