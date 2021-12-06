<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_managerid(); 

if(empty($userid)){

	header("Location: index.php");

}

$executive = $_GET['executive']; 

if(empty($executive)){

	header("Location: executive-list.php");

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
    <title>M3 Manager Executive Reports</title>
    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- toast CSS -->
    <link href="../plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- morris CSS -->
    <link href="../plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="css/colors/blue-dark.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
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
                        <h4 class="page-title">Executive Reports</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <ol class="breadcrumb">
                            <li><a href="profile.php">Dashboard</a></li>
                            <li class="active">Executive Reports</li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
                <!-- .row -->
                <div class="row"> 
                    <div class="col-md-12 col-xs-12">
                        <div class="white-box">
                        <div class="row from-group">
						<form method="get" action="" >
							<div class="col-md-3">
									<select class="form-control" name="month" >
									<option value="">-Select Month--</option>
									<?php for($i=1;$i<=12;$i++){?>
											<option value="<?php echo $i;?>" <?php if(!empty($_GET['month'])&&$_GET['month']==$i){ echo 'selected';}?>><?php echo date('F',mktime(0,0,0,$i,10));?></option>
									<?php	} 	?>
									</select>
								
								</div>  
							<div class="col-md-3">
									<select class="form-control" name="year" >
									<option value="">-Select year--</option>
									<?php for($y=2019;$y<=2030;$y++){?>
											<option value="<?php echo $y;?>" <?php if(!empty($_GET['month'])&&$_GET['year']==$y){ echo 'selected';}?>><?php echo $y;?></option>
									<?php	} 	?>
									</select>
									
								</div>  
							<div class="col-md-3">
									<select class="form-control" name="campaign" >
									<option value="">-Select campaign--</option>
									<?php $campaignsarray = runloopQuery("select ID,title from campaigns where user_id = '".$userid."' and user_role = 'manager' order by ID asc");
										foreach($campaignsarray as $campaigns){ ?>
											<option value="<?php echo $campaigns['ID'];?>" <?php if(!empty($_GET['campaign'])&&$_GET['campaign']==$campaigns['ID']){ echo 'selected';}?>><?php echo $campaigns["title"];?></option>
									<?php	} 	?>
									</select>
								
								</div> 
							<div class="col-md-3">
								<input type="submit" class="btn btn-success" Value="Submit" />
								<input type="hidden" name="executive" Value="<?php echo $executive;?>" />
								</div> 
							</form> 
								</div>
                        </div>
                    </div>
                </div>
                <div class="row"> 
                    <div class="col-md-12 col-xs-12">
                        <div class="white-box"> 
									<div class="row">
									<?php $wherearray = '';
										if(!empty($_GET['campaign'])){
											$campaignid = mysqli_real_escape_string($conn,$_GET['campaign']);
											$wherearray = " campaign_id = '".$campaignid."' and ";
										}
										if(!empty($_GET['month'])&&!empty($_GET['month'])){
											$month = mysqli_real_escape_string($conn,$_GET['month']);
											$year = mysqli_real_escape_string($conn,$_GET['year']); 
										}else{
											$month = date('n');
											$year = date('Y'); 
										}
										
											$feedbackarray = runloopQuery("select ID,title from feedbackoptions order by ID asc");
										$bargraphoptionstitle = $piegraphoptionstitle = array();
										$days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
											for($i=1;$i<=$days;$i++){
												$totaldays[] = (string)date("d",mktime(0,0,0,$month,$i,$year));  
											} 
											
										foreach($feedbackarray as $feedback){
															$bargraphoptions = array();
											$days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
											for($i=1;$i<=$days;$i++){ 
												$datecur = date("Y-m-d",mktime(0,0,0,$month,$i,$year)); 
												$bardata = runQuery("select count(*) as count from campaigns_users where $wherearray executive_id = '".$executive."' and callstatus = '".$feedback['ID']."' and duration >='".$datecur." 00:00:00'  and duration <='".$datecur." 23:59:59'")['count'];   
												$bargraphoptions[] = (int)$bardata;
											} 
											$bargraphoptionstitle[] = array("name"=>$feedback['title'],"data"=>$bargraphoptions);
											
											$first_second = date('01-m-Y 00:00:00', mktime(0,0,0,$month,10,$year));
											$last_second  = date('t-m-Y 23:59:59', mktime(0,0,0,$month,10,$year)); // A leap year!
											
											$bardata = runQuery("select count(*) as count from campaigns_users where $wherearray executive_id = '".$executive."' and callstatus = '".$feedback['ID']."' and duration >='".$first_second."'  and duration <='".$last_second."'")['count']; 
										 
											
											$piegraphoptionstitle[] = array("name"=>$feedback['title'],"y"=>(int)$bardata);
											
											
										}  
							
									?>
							<div class="col-md-12">
										<div id="barcharts"></div> 
									</div>
							<div class="col-md-6">
										<div id="piechartgrah"></div>
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
    <!-- jQuery -->
    <script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/custom.min.js"></script>
		  <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script>
		
Highcharts.chart('barcharts', {

    title: {
        text: 'Executive for the month <?php echo date('M Y',mktime(0,0,0,$month,10,$year));?>'
    },

    subtitle: {
        text: ''
    },

    yAxis: {
        title: {
            text: 'Calls'
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    plotOptions: {
        series: {
            label: {
                connectorAllowed: false
            },
            pointStart: 01
        }
    },

    series:  <?php echo json_encode($bargraphoptionstitle);?>,

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

}); 

// Build the chart
Highcharts.chart('piechartgrah', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Complete Feedbacks <?php echo date('M Y',mktime(0,0,0,$month,10,$year));?>'
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
        name: 'Feedbacks',
        colorByPoint: true,
        data: <?php echo json_encode($piegraphoptionstitle);?>
    }]
});  

	</script>
</body>

</html>
