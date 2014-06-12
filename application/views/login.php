<!DOCTYPE html>
<!--[if lt IE 7]> <html id="ie6" dir="ltr" lang="en-US"> <![endif]-->
<!--[if IE 7]>    <html id="ie7" dir="ltr" lang="en-US"> <![endif]-->
<!--[if IE 8]>    <html id="ie8" dir="ltr" lang="en-US"> <![endif]-->
<!--[if gt IE 8]><!--> <html dir="ltr" lang="en-US"> <!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<title>Medika &mdash; <?php echo $title; ?></title>
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><![endif]-->
	<meta name="viewport" content="width=device-width" />
	<meta name="robots" content="noindex, nofollow">
	
	<!-- stylesheet -->
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/reset.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/style.css'); ?>">
	<!-- /stylesheet -->
	
	<!-- javascript -->
	<script type="text/javascript" src="<?php echo asset_url('js/jquery.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo asset_url('js/jquery-ui.min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo asset_url('js/plugins/forms/jquery.uniform.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo asset_url('js/login.js'); ?>"></script>
	<!-- /javascript -->
	
	<noscript>
		<style>
			.loginWrapper { opacity: 1; }
		</style>
	</noscript>
</head>
<body>
	<?php echo $content_for_layout ?>
	<!-- end login.php -->
</body>
</html>
