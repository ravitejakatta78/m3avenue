<?php

session_start();

error_reporting(E_ALL);

include('../functions.php');



$userid = current_adminid();
 

if(empty($userid)){

	header("Location: index.php");

}

?>
<!DOCTYPE html>

<html lang="en">

	<!-- begin::Head -->
	<head>

		<!--begin::Base Path (base relative path for assets of this page) -->
		

		<!--end::Base Path -->
		<meta charset="utf-8" />
		<title>M3  | Dashboard</title>
		<meta name="description" content="Latest updates and statistic charts">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />

		<?php include('headerscripts.php');?>
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
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

				<!-- begin:: Aside -->
				<button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
				<div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">

					<!-- begin::Aside Brand -->
					<div class="kt-aside__brand kt-grid__item " id="kt_aside_brand">
						<div class="kt-aside__brand-logo">
							<a href="demo1/index.html">
								<img alt="Logo" src="./assets/media/logos/logo-6.png" style="width: 67px;
    margin-left: 30px;
    margin-top: 20px;">
							</a>
						</div>
						<div class="kt-aside__brand-tools">
							<button class="kt-aside__brand-aside-toggler kt-aside__brand-aside-toggler--left" id="kt_aside_toggler"><span></span></button>
						</div>
					</div>

					<!-- end:: Aside Brand -->



					<!-- begin:: Aside Menu -->
<?php include('header.php');?>

<!-- end:: Aside Menu -->
				</div>

				<!-- end:: Aside -->

				<!-- begin:: Wrapper -->
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

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

						<!-- begin:: Header Topbar -->
						<div class="kt-header__topbar">

							


							

							<!--begin: User Bar -->
							<div class="kt-header__topbar-item kt-header__topbar-item--user">
								<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="0px, 0px">
									<div class="kt-header__topbar-user">
										<span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
										<span class="kt-header__topbar-username kt-hidden-mobile">Sean</span>
										<img alt="Pic" src="./assets/media/users/300_25.jpg" />

										<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
										<span class="kt-badge kt-badge--username kt-badge--lg kt-badge--brand kt-hidden kt-badge--bold">S</span>
									</div>
								</div>
								<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-top-unround dropdown-menu-sm">
									<div class="kt-user-card kt-margin-b-40 kt-margin-b-30-tablet-and-mobile" style="background-image: url(./assets/media/misc/head_bg_sm.jpg)">
										<div class="kt-user-card__wrapper">
											<div class="kt-user-card__pic">
												<img alt="Pic" src="./assets/media/users/300_21.jpg" />
											</div>
											<div class="kt-user-card__details">
												<div class="kt-user-card__name">Alex Stone</div>
												<div class="kt-user-card__position">CTO, Loop Inc.</div>
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

					<!-- end:: Header -->
					<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

					<!-- begin:: Subheader -->

<br>
<!-- end:: Subheader -->

						<!-- begin:: Content -->
				<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content"> 
				<div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__head">
							<div class="kt-portlet__head-label">
								<h3 class="kt-portlet__head-title">Add Employee</h3>
							</div>
						</div>
						<br>
						<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">FirstName</label>
                        <div class="col-4">
                            <input class="form-control" type="text" value="Dummy Name" id="example-text-input">
                        </div>
						<label for="example-text-input" class="col-2 col-form-label">LastName</label>
                        <div class="col-4">
                            <input class="form-control" type="text" value="Dummy Surname" id="example-text-input">
                        </div>
                    </div>
						
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Email</label>
                        <div class="col-4">
                            <input class="form-control" type="text" value="user@gmail.com" id="example-text-input">
                        </div>
						<label for="example-text-input" class="col-2 col-form-label">Mobile Number</label>
                        <div class="col-4">
                            <input class="form-control" type="text" value="91234567890" id="example-text-input">
                        </div>
                    </div>
					
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Enter Bank Details</label>
                        <div class="col-4">
                            <input class="form-control" type="text" value="BANK NAME" id="example-text-input">
                        </div>
                        <div class="col-3">
                            <input class="form-control" type="text" value="1234567890123" id="example-text-input">
                        </div>
						<div class="col-3">
                            <input class="form-control" type="text" Value="BANK123456" id="example-text-input">
                        </div>
                    </div>
					
					<div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Upload PAN Card</label>
                        <div class="col-4">
							<div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
						</div>
						
						<label for="example-text-input" class="col-2 col-form-label">Upload ADHAAR Card</label>
                        <div class="col-4">
							<div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
						</div>
                    </div>
					
					 <div class="form-group row">
                        <label for="example-text-input" class="col-2 col-form-label">Address</label>
                        <div class="col-10">
						<div class="form-group">
							<textarea class="form-control"id="exampleTextarea" rows="3">
							Kukatpally, Hyderabad - 500000
							</textarea>
						</div>

                        </div>
                    
                       <div class="col-4">
                             <button type="submit" class="btn btn-brand btn-elevate btn-pill">Add-Employee</button>
                  </div>
                    </div>  
      
						</div> 
						<!-- end:: Content -->
			</div>  

						<!-- end:: Content -->
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
	</body>

	<!-- end::Body -->
</html>