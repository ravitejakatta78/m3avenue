 <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header"> <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="fa fa-bars"></i></a>
                <div class="top-left-part">
					<a class="logo" href="dashboard.php"><b>
					<img class="logo-new" src="img/m3-logo.png" alt="home" /></b><span class="hidden-xs">M3 Team<span></a>
					</div> 
               
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <li>
                        <a class="profile-pic" href="dashboard.php"> <img src="<?php echo !empty($usedetails['profilepic']) ? EMPLOYEE_IMAGE.$usedetails['profilepic'] : 'img/m3-logo.png' ;?>" alt="user-img" width="36" class="img-circle"><b class="hidden-xs"><?php echo ucwords($usedetails['fname'].' '.$usedetails['lname']);?></b> </a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-header -->
            <!-- /.navbar-top-links -->
            <!-- /.navbar-static-side -->
        </nav>
        <!-- Left navbar-header -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse slimscrollsidebar">
                <ul class="nav" id="side-menu">
				<!--
                    <li style="padding: 10px 0 0;">
                        <a href="index.php" class="waves-effect"><i class="fa fa-clock-o fa-fw" aria-hidden="true"></i><span class="hide-menu">Dashboard</span></a>
                    </li>
				-->
				
		 <?php $pagename = basename($_SERVER['PHP_SELF']);?>
                    <li class="<?php if($pagename=='dashboard.php'){ echo 'active';}?>">
                        <a href="dashboard.php" class="waves-effect"><i class="fa fa-user fa-fw" aria-hidden="true"></i><span class="hide-menu">Dashboard</span></a>
                    </li>
                    <li class="<?php if($pagename=='profile.php'){ echo 'active';}?>">
                        <a href="profile.php" class="waves-effect"><i class="fa fa-user fa-fw" aria-hidden="true"></i><span class="hide-menu">Employee Details</span></a>
                    </li>
                   <!-- <li class="<?php if($pagename=='teammember-register.php'){ echo 'active';}?>">
                        <a href="teammember-register.php" class="waves-effect"><i class="fa fa-table fa-fw" aria-hidden="true"></i><span class="hide-menu">Register</span></a>
                    </li>-->
                    <li class="<?php if($pagename=='track-client.php'){ echo 'active';}?>">
                        <a href="track-client.php" class="waves-effect"><i class="fa fa-table fa-fw" aria-hidden="true"></i><span class="hide-menu">Track My Clients</span></a>
                    </li>
                    
                    <li class="<?php if($pagename=='track-work.php'){ echo 'active';}?>">
                        <a href="track-work.php" class="waves-effect"><i class="fa fa-globe fa-fw" aria-hidden="true"></i><span class="hide-menu">Track My Lead</span></a>
                    </li>
                    <li class="<?php if($pagename=='today-track-work.php'){ echo 'active';}?>">
                        <a href="today-track-work.php" class="waves-effect"><i class="fa fa-globe fa-fw" aria-hidden="true"></i><span class="hide-menu">Follow up's</span></a>
                    </li>
                    <li class="<?php if($pagename=='emi-calculator.php'){ echo 'active';}?>">
                        <a href="emi-calculator.php" class="waves-effect"><i class="fa fa-globe fa-fw" aria-hidden="true"></i><span class="hide-menu">EMI Calculator</span></a>
                    </li>
                    <li class="<?php if($pagename=='reports.php'){ echo 'active';}?>">
                        <a href="reports.php" class="waves-effect"><i class="fa fa-columns fa-fw" aria-hidden="true"></i><span class="hide-menu">My Reports</span></a>
                    </li>
                    <li class="<?php if($pagename=='assingnedworks.php'){ echo 'active';}?>">
                        <a href="assingnedworks.php" class="waves-effect"><i class="fa fa-columns fa-fw" aria-hidden="true"></i><span class="hide-menu">Assinged works</span></a>
                    </li>
                    <li class="<?php if($pagename=='logout.php'){ echo 'active';}?>">
                        <a href="logout.php" class="waves-effect" style="text-align:center;"><i aria-hidden="true"></i><span class="hide-menu">Logout</span></a>
                    </li>
                   
                </ul>
              
            </div>
        </div>