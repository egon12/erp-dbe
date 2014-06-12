<!DOCTYPE html>
<!--[if lt IE 7]> <html id="ie6" dir="ltr" lang="en-US"> <![endif]-->
<!--[if IE 7]>    <html id="ie7" dir="ltr" lang="en-US"> <![endif]-->
<!--[if IE 8]>    <html id="ie8" dir="ltr" lang="en-US"> <![endif]-->
<!--[if gt IE 8]><!--> <html dir="ltr" lang="en-US"> <!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title>Medika | <?php echo $title; ?></title>
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><![endif]-->
	<meta name="viewport" content="width=device-width" />
	<meta name="robots" content="noindex, nofollow">
	
	<!-- stylesheet -->
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/reset.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/font.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/style.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/plugins.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/smoothness/jquery.ui.all.css'); ?>">
	<!-- /stylesheet -->
	
	<!-- javascript -->
	<script type="text/javascript" src="<?php echo asset_url('js/jquery.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo asset_url('js/jquery-ui.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo asset_url('js/plugins/forms/jquery.uniform.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo asset_url('js/plugins/tables/jquery.dataTables.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo asset_url('js/plugins/tables/formattedNum.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo asset_url('js/plugins/tables/filteringDelay.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo asset_url('js/plugins/date/moment.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo asset_url('js/plugins/number/numeral.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo asset_url('js/plugins/modal/jquery.reveal.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo asset_url('js/mobile.js'); ?>"></script>
	<!-- /javascript -->
	
	<noscript></noscript>
</head>
<body>
	
	<!-- topNav -->
	<div id="topNav">
		<div class="fixed">
			<div class="wrapper">
				<div class="welcome">
					<img src="<?php echo asset_url('img/userPic.png'); ?>" alt="">
					<span>Hello, <?php echo $the_user->first_name.' '.$the_user->last_name; ?>!</span>
				</div>
				<div class="userNav">
					<ul>
						<li>
							<a id="logout" href="<?php echo site_url('auth/logout'); ?>" title="">
								<img src="<?php echo asset_url('img/icons/topnav/logout.png'); ?>" alt=""><span>Logout</span>
							</a>
						</li>
					</ul>
				</div>
				<div class="clear"></div>
			</div><!-- /wrapper -->
		</div>
	</div>
	<!-- /topNav -->
	
	<!-- header -->
	<header id="header" class="wrapper">
		<ul class="middleNavA">
            <li>
                <a class="mobileOnly mobileMenu" href="#">
                    <span class="iconb" data-icon="&#xe078;"></span>
                    <span>Mobile Menu</span>
                </a>
            </li>
            <!--
            <li class="desktopOnly">
				<a href="#" title="New tasks">
					<span class="iconb" data-icon="&#xe078;"></span>
					<span>New Tasks</span>
				</a>
				<strong>8</strong>
			</li>
            <li class="desktopOnly">
				<a href="<?php echo site_url('users'); ?>" title="User list">
					<span class="iconb" data-icon="&#xe1e1;"></span>
					<span>User List</span>
				</a>
			</li>
            <li class="desktopOnly">
				<a href="#" title="Billing Panel">
					<span class="iconb" data-icon="&#xe050;"></span>
					<span>Billing Panel</span>
				</a>
			</li>
            -->
        </ul>
		<div class="clear"></div>
	</header>
	<!-- /header -->
	
	<!-- main -->
	<div role="main" class="wrapper">
		<div class="leftNav">
            <?php echo $this->load->view('partials/navigation'); ?>
		</div><!-- /leftNav -->
		
		<div class="content">
			<div class="title"><h5><?php echo $title; ?></h5></div>

			<?php
				// load partial view for statistic widget
				echo $this->load->view('partials/statistic');
				
				// if main controller
				if($this->router->fetch_class() == 'main') {
					// load partial view for "main" -> application/views/partials/widget_home.php
					$this->load->view('partials/widget_home');
				} else {
					// load partial view from modules
					echo $content_for_layout;
				}
			?>
		</div><!-- /content -->
	</div>
	<!-- /main -->
	
	<!-- footer -->
	<footer id="footer">
	</footer>
	<!-- /footer -->
	
	<!-- end main.php -->
</body>
</html>
