<!DOCTYPE html>
<html>
  <meta charset="UTF-8">
  <title>BVAP Pos</title>

  <link href="<?php echo base_url('assets/css/bootstrap.css') ?>" rel="stylesheet" >
  <link href="<?php echo base_url('assets/css/smoothness/jquery.ui.all.css') ?>" rel="stylesheet" >
  <link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/plugins.css'); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/smoothness/jquery.ui.all.css'); ?>">
  <script type="text/javascript" src="<?php echo asset_url('js/jquery.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo asset_url('js/jquery-ui.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo asset_url('js/plugins/forms/jquery.uniform.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo asset_url('js/plugins/tables/jquery.dataTables.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo asset_url('js/plugins/tables/dataTables.formattedNum.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo asset_url('js/plugins/modal/jquery.reveal.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo asset_url('js/mobile.js'); ?>"></script>

  <div class="navbar navbar-image">
    <div class="container">
      <ul class="navbar-header">
      </ul>
      <a class="navbar-brand" href="#">BVAP Point Of Sales</a>
      <ul class="nav navbar-nav navbar-right">
        <li> <a href="#"><span class="glyphicon glyphicon-user"></span> Hello, egon </a></li>
        <li> <a href="<?php echo site_url() ?>"><span class="glyphicon glyphicon-dashboard"></span> Back to Dashboard </a></li>
        <li> <a href="#"><span class="glyphicon glyphicon-log-out"></span> Logout </a> </li>
      </ul>
    </div>
  </div>

  <div class="container">
    <div class="row">

      <div class="col-lg-3 col-md-4">
        <?php echo $this->load->view('partials/navigation'); ?>
      </div>

      <div class="col-lg-9 col-md-8">

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

      </div>
  </div> <!-- container -->
</html>
