 <!--**********************************
            Nav header start
        ***********************************-->
		<div class="nav-header">
            <a href="index.html" class="brand-logo">
				<img src="assets/images/logo.png" width="55" height="55">
					
			
				<div class="brand-title">
					<h2 class="">M3 </h2>
					<span class="brand-sub-title">M3 AVENUE</span>
				</div>
            </a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

<!--**********************************
            Header start
        ***********************************-->

        <?php $userid = current_adminid(); 


        	$employee_details= runQuery("select selected_modules from tbl_super_admin_details where super_admin_id = '".$userid."'");

        	$selected_modules=explode(",", $employee_details['selected_modules']);




         ?>

        <div class="header border-bottom">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
							<div class="dashboard_bar">
                                Dashboard
                            </div>
                        </div>
                 
							
							<li class="nav-item dropdown  header-profile">
								<a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
									<img src="assets/images/logo.png" width="56" alt=""/>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="./app-profile.html" class="dropdown-item ai-icon">
										<svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
										<span class="ms-2">Profile </span>
									</a>
									
									<a href="./login.html" class="dropdown-item ai-icon">
										<svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
										<span class="ms-2">Logout </span>
									</a>
								</div>
							</li>
                   
                    </div>
				</nav>
			</div>
		</div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="dlabnav">
            <div class="dlabnav-scroll">
				<ul class="metismenu" id="menu">
                    <li><a href="index.php">
							<i class="fas fa-home"></i>
							<span class="nav-text">Dashboard</span>
						</a>
                      
                    </li>
                    <li><a  href="profile.php">
							<i class="fas fa-user"></i>
							<span class="nav-text">Profile</span>
						</a>
                      
                    </li>

                    <?php if(in_array(1,$selected_modules)) { ?>
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
							<i class="fab fa-bootstrap"></i>
							<span class="nav-text">Employee Management</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="employee_list.php">Employee List</a></li>
                            <li><a href="employee-track-work.php">Employee Track Work</a></li>
							<li><a href="track_team.php">Track Team</a></li>
							<li><a href="ticket_history.php">Ticket History</a></li>
                        </ul>
                    </li>
                	<?php  } ?>

                	<?php if(in_array(2,$selected_modules)) { ?>
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
							<i class="fab fa-bootstrap"></i>
							<span class="nav-text">Lead Management </span>
						</a>
                        <ul aria-expanded="false">
                            <ul aria-expanded="false">
                        	<li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
								<span class="nav-text">Enquiries</span>
								</a>
		                        <ul aria-expanded="false">
		                            <li><a href="loans.php">Loan</a></li>
		                            <li><a href="investment.php">Investment</a></li>
		                    		<li><a href="website-users.php">Website Users</a></li>
		                    		<li><a href="track-user-investments.php">Track Investments</a></li>
		                    		<li><a href="track-loans.php">Track Loans</a></li>
		                        </ul>
		                    </li>
		                </ul>
                            <li><a href="leads.php">Lead List</a></li>
							<li><a href="clients.php">Client List</a></li>
							<li><a href="work-source.php">Work Source</a></li>
							<li><a href="follow-up-leads.php">Follow Up Leads</a></li>
							<!-- <li><a href="follow-up-clients.php">Follow Up Clients</a></li> -->
                        </ul>
                    </li>
                	<?php } ?>
					

                	<?php if(in_array(3,$selected_modules)) { ?>
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
							<i class="bi bi-telephone-inbound"></i>
							<span class="nav-text">Dailer</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="feedback-options.php">Feedback Options</a></li>
                            <li><a href="campaigns-list.php">Campaigns List</a></li>
                       
                        </ul>
                    </li>
                    <?php  } ?>

                	<?php if(in_array(4,$selected_modules)) { ?>
					<li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
							<i class="fab fa-bootstrap"></i>
							<span class="nav-text">PL Eligibility</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="pl_data.php">PL Data</a></li>
                            <li><a href="loan_eligibility.php">Loan Eligibility</a></li>
                        </ul>
                    </li>
                    <?php  } ?>

                	<?php if(in_array(5,$selected_modules)) { ?>

                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
							<i class="fab fa-bootstrap"></i>
							<span class="nav-text">Tools & Configurations</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="#">Feature Level assigning to emp</a></li>
                            <li><a href="#">Points Set</a></li>
							<li><a href="#">ABB</a></li>
							<li><a href="#">Booster Kits</a></li>
                        	<li><a href="#">EMI Calculator</a></li>

                        </ul>
                    </li>
                    <?php  } ?>

                	<?php if(in_array(6,$selected_modules)) { ?>

                    <li><a href="events.php">
							<i class="fas fa-home"></i>
							<span class="nav-text">Events</span>
						</a>
                    </li>
                    <?php  } ?>
                    <?php if(in_array(15,$selected_modules)) { ?>

                    <li><a href="points-set.php">
							<i class="fas fa-home"></i>
							<span class="nav-text">Points Set</span>
						</a>
                    </li>
                    <?php  } ?>
                     <?php if(in_array(16,$selected_modules)) { ?>

                    <li><a href="boosterkit-list.php">
							<i class="fas fa-home"></i>
							<span class="nav-text">Booster Kits</span>
						</a>
                    </li>
                    <?php  } ?>

                	<?php if(in_array(7,$selected_modules)) { ?>

                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
							<i class="fab fa-bootstrap"></i>
							<span class="nav-text">Daily Finance</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="df-vendor-list.php">Vendor List</a></li>
                            <li><a href="df-applications-list.php">Applications List</a></li>
							<li><a href="df-overall-performance.php">Performance</a></li>
							<li><a href="df-budget.php">Budget</a></li>
                        </ul>
                    </li>
                    <?php  } ?>

                	<?php if(in_array(8,$selected_modules)) { ?>
                    
					<li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
							<i class="bi bi-menu-up"></i>
							<span class="nav-text">HRMS</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="#">Offer letter generator</a></li>
                            <li><a href="attendance_list.php">Attendance</a></li>
							<li><a href="#">Payroll</a></li>
                    		<li><a href="#">Auto Pay slip Generator</a></li>
                        </ul>
                    </li>
                    <?php  } ?>

                	<?php if(in_array(9,$selected_modules)) { ?>
                 
					<li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
							<i class="fas fa-heart"></i>
							<span class="nav-text">Communication Services</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="#">Meetings (Audio & Video) </a></li>
                            <li><a href="#">Chat & Call</a></li>
                    		<li><a href="#">Official Mail integration</a></li>
                    		<li><a href="#">Calendar</a></li>
                        </ul>
                    </li>
                    <?php  } ?>

                	<?php if(in_array(10,$selected_modules)) { ?>
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
							<i class="fas fa-heart"></i>
							<span class="nav-text">Task Management</span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="#">Task Assigning</a></li>
                            <li><a href="#">Create Task</a></li>
                    		<li><a href="#">To Do List</a></li>
                        </ul>
                    </li>
                    <?php  } ?>

                	<?php if(in_array(11,$selected_modules)) { ?>
                    <li><a href="operations.php">
							<i class="fas fa-file-alt"></i>
							<span class="nav-text">Operations</span>
						</a>
					<?php  } ?>

                	<?php if(in_array(12,$selected_modules)) { ?>	
                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
							<i class="fas fa-heart"></i>
							<span class="nav-text">Capital Management</span>
						</a>
                        <ul aria-expanded="false">
                        	<li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
								<i class="fas fa-heart"></i>
								<span class="nav-text">Expenses</span>
								</a>
		                        <ul aria-expanded="false">
		                            <li><a href="#">Petty cash</a></li>
		                            <li><a href="#">Salary Structures</a></li>
		                    		<li><a href="#">Fixed Payments</a></li>
		                    		<li><a href="#">Vendor payments</a></li>
		                    		<li><a href="#">Taxation</a></li>
		                        </ul>
		                    </li>
		                </ul>
		                <ul aria-expanded="false">
                        	<li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
								<i class="fas fa-heart"></i>
								<span class="nav-text">Revenue</span>
								</a>
		                        <ul aria-expanded="false">
		                            <li><a href="#">Invoices</a></li>
		                            <li><a href="#">Revenue Addition Forms</a></li>
		                        </ul>
		                    </li>
		                </ul>
                    </li>
                    <?php  } ?>

                	<?php if(in_array(13,$selected_modules)) { ?>

                    <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
							<i class="fas fa-heart"></i>
							<span class="nav-text">Business Kit </span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="#">Id cards</a></li>
                            <li><a href="#">Business Cards</a></li>
                    		<li><a href="#">Letter heads</a></li>
                    		<li><a href="#">Invoices</a></li>
                        </ul>
                    </li>
                    <?php  } ?>

                	<?php if(in_array(14,$selected_modules)) { ?>

                     <li><a class="has-arrow " href="javascript:void()" aria-expanded="false">
							<i class="fas fa-heart"></i>
							<span class="nav-text">Marketing Configurations </span>
						</a>
                        <ul aria-expanded="false">
                            <li><a href="#">Mail</a></li>
                            <li><a href="#">SMS</a></li>
                    		<li><a href="#">Notifications</a></li>
                        </ul>
                    </li>
                    <?php  } ?>

					
					
					  <li><a href="logout.php" >
							<i class="fas fa-file-alt"></i>
							<span class="nav-text">Logout</span>
						</a>
                     
                    </li>
					
                </ul>
				<div class="side-bar-profile">
					<div class="d-flex align-items-center justify-content-between mb-3">
						<div class="side-bar-profile-img">
							<img src="assets/images/user.jpg" alt="">
						</div>
						<div class="profile-info1">
							<h4 class="fs-18 font-w500">Levi Siregar</h4>
							<span>leviregar@mail.com</span>
						</div>
						<div class="profile-button">
							<i class="fas fa-caret-down scale5 text-light"></i>
						</div>
					</div>	
					<div class="d-flex justify-content-between mb-2 progress-info">
						<span class="fs-12"><i class="fas fa-star text-orange me-2"></i>Task Progress</span>
						<span class="fs-12">20/45</span>
					</div>
					<div class="progress default-progress">
						<div class="progress-bar bg-gradientf progress-animated" style="width: 45%; height:10px;" role="progressbar">
							<span class="sr-only">45% Complete</span>
						</div>
					</div>
				</div>
				
			</div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->