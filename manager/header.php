 <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header"> <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="fa fa-bars"></i></a>
			<?php $mangername = manager_details($userid,'fname');?>
                <div class="top-left-part"><a class="logo" href="index.php"><b>
					<img class="logo-new" src="img/m3-logo.png" alt="home" /></b><span class="hidden-xs"><?php echo $mangername;?><span></a></div>
               
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <li>
                        <a class="profile-pic" href="profile.php"> <img src="img/m3-logo.png" alt="user-img" width="36" class="img-circle"><b class="hidden-xs"></b><?php echo $mangername;?></a>
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
                    <li class="<?php if($pagename=='profile.php'){ echo 'active';}?>">
                        <a href="profile.php" class="waves-effect"><i class="fa fa-user fa-fw" aria-hidden="true"></i><span class="hide-menu">Personal Details</span></a>
                    </li>
                    <li class="<?php if($pagename=='track-work.php'){ echo 'active';}?>">
                        <a href="track-work.php" class="waves-effect" ><i class="fa fa-user fa-fw" ria-hidden="true"></i><span class="hide-menu">Track Leads</span></a>
                    </li>
                    <li class="<?php if($pagename=='track-client.php'){ echo 'active';}?>">
                        <a href="track-client.php" class="waves-effect" ><i class="fa fa-user fa-fw" ria-hidden="true"></i><span class="hide-menu">Track Clients</span></a>
                    </li>
                    <li class="<?php if($pagename=='today-track-work.php'){ echo 'active';}?>">
                        <a href="today-track-work.php" class="waves-effect" ><i class="fa fa-user fa-fw" ria-hidden="true"></i><span class="hide-menu">Follow Up's</span></a>
                    </li>             
                    <li class="<?php if($pagename=='track-team.php'){ echo 'active';}?>">
                        <a href="track-team.php" class="waves-effect"><i class="fa fa-font fa-fw" aria-hidden="true"></i><span class="hide-menu">Track My Team</span></a>
                    </li>
                    <!-- <li class="<?php if($pagename=='assigned-campaings.php'){ echo 'active';}?>">
                        <a href="assigned-campaings.php" class="waves-effect"><i class="fa fa-font fa-list  fa-fw" aria-hidden="true"></i><span class="hide-menu">Campaigns Details</span></a>
                    </li> -->
                    <li class="<?php if($pagename=='campaigns-list.php'){ echo 'active';}?>">
                        <a href="campaigns-list.php" class="waves-effect"><i class="fa fa-font fa-list fa-fw" aria-hidden="true"></i><span class="hide-menu">Dailer Campaigns</span></a>
                    </li>
                    <li class="<?php if($pagename=='executive-list.php'){ echo 'active';}?>">
                        <a href="executive-list.php" class="waves-effect"><i class="fa fa-user fa-fw" aria-hidden="true"></i><span class="hide-menu">Team List</span></a>
                    </li>
                    <li class="<?php if($pagename=='hire-induction.php'){ echo 'active';}?>">
                        <a href="hire-induction.php" class="waves-effect"><i class="fa fa-user fa-fw" aria-hidden="true"></i><span class="hide-menu">Event List</span></a>
                    </li>
                    <li class="<?php if($pagename=='logout.php'){ echo 'active';}?>">
                        <a href="logout.php" class="waves-effect" style="text-align:center;"><i aria-hidden="true"></i><span class="hide-menu">Logout</span></a>
                    </li>

                    

                </ul>
              
            </div>
        </div>