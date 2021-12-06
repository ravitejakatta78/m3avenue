 <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header"> <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="fa fa-bars"></i></a>
                <div class="top-left-part"><a class="logo" href="index.php"><b>
					<img class="logo-new" src="img/m3-logo.png" alt="home" /></b><span class="hidden-xs">M3<span></a></div>
               
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <li>
                        <a class="profile-pic" href="#"> <img src="img/m3-logo.png" alt="user-img" width="36" class="img-circle"><b class="hidden-xs">Venu</b> </a>
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
                    <li class="<?php if($pagename=='track-investment.php'){ echo 'active';}?>">
                        <a href="track-investment.php" class="waves-effect"><i class="fa fa-table fa-fw" aria-hidden="true"></i><span class="hide-menu">Track Investment</span></a>
                    </li>
                    <li class="<?php if($pagename=='support.php'){ echo 'active';}?>">
                        <a href="support.php" class="waves-effect"><i class="fa fa-font fa-fw" aria-hidden="true"></i><span class="hide-menu">Support</span></a>
                    </li>
                    <li class="<?php if($pagename=='logout.php'){ echo 'active';}?>">
                        <a href="logout.php" class="waves-effect" style="text-align:center;"><i aria-hidden="true"></i><span class="hide-menu">Logout</span></a>
                    </li>
                </ul>
              
            </div>
        </div>