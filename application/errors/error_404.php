<!DOCTYPE html>
<html lang="en">
<head>
<title>404 Page Not Found</title>

<?php $config =& get_config(); ?>

<link href="<?php echo $config['base_url'] ?>/assets/css/reset.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $config['base_url'] ?>assets/css/error_handling.css" rel="stylesheet" type="text/css" />

</head>
<body class="error-page">
	<div id="error-wrapper">
		<h1>Oops!</h1>
		<div class="error-code"><?php echo $heading; ?></div>
		<div class="error-message"><?php echo $message; ?></div>
		<div class="error-actions">
			<a href="<?php echo $config['base_url'] ?>" class="btn">Back to Dashboard</a>
		</div>
	</div>
</body>
</html>
