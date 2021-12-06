<!-- begin:: Header -->
					<div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">

						<!-- begin:: Header Menu -->
						<button class="kt-header-menu-wrapper-close" id="kt_header_menu_mobile_close_btn"><i class="la la-close"></i></button>
						<div class="kt-header-menu-wrapper" id="kt_header_menu_wrapper">
							<div id="kt_header_menu" class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout- ">
								<ul class="kt-menu__nav ">
									
								
									
								</ul>
							</div>
						</div>

						<!-- end:: Header Menu -->
<?php
$userid = current_adminid(); 
$usedetails = runQuery("select * from employee where ID = '".$userid."'");
?>
						<!-- begin:: Header Topbar -->
						<div class="kt-header__topbar">

							<!--begin: User Bar -->
							<div class="kt-header__topbar-item kt-header__topbar-item--user">
								<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px, 0px">
									<div class="kt-header__topbar-user">
										<span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
										<span class="kt-header__topbar-username kt-hidden-mobile"><?= $usedetails['fname'].' '.$usedetails['lname']; ?></span>
										<img alt="Pic" src="<?php echo !empty($usedetails['profilepic']) ? EMPLOYEE_IMAGE.$usedetails['profilepic'] : './assets/media/logos/logo-6.png'; ?>" />

										<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
										<span class="kt-badge kt-badge--username kt-badge--lg kt-badge--brand kt-hidden kt-badge--bold">S</span>
									</div>
								</div>
								<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-sm">
									<div class="kt-user-card kt-margin-b-40 kt-margin-b-30-tablet-and-mobile" style="background-image: url(./assets/media/misc/head_bg_sm.jpg)">
										<div class="kt-user-card__wrapper">
											<div class="kt-user-card__pic">
												<img alt="Pic" src="<?php echo !empty($usedetails['profilepic']) ? EMPLOYEE_IMAGE.$usedetails['profilepic'] : './assets/media/logos/logo-6.png'; ?>" />
											</div>
											<div class="kt-user-card__details">
												<a href="profile.php"><div class="kt-user-card__name">Profile</div></a>
												<div class="kt-user-card__position"></div>
											</div>
										</div>
									</div>
									
									
								</div>
							</div>

							<!--end: User Bar -->

							<!--begin:: Quick Panel Toggler -->
							

							<!--end:: Quick Panel Toggler -->
						</div>

						<!-- end:: Header Topbar -->
					</div>
