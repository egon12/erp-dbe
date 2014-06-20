<!DOCTYPE html>
<html>
  <meta charset="utf-8" />
  <title>Medika | Health Record Sugestion</title>
  <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><![endif]-->
  <meta name="viewport" content="width=device-width" />
  <meta name="robots" content="noindex, nofollow">

  <!-- stylesheet -->
  <link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/reset.css'); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/font.css'); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/bootstrap.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/style.css'); ?>"> 
  <link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/plugins.css'); ?>">
  <!-- /stylesheet -->

  <!-- javascript -->
  <script type="text/javascript" src="<?php echo asset_url('js/jquery.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo asset_url('js/plugins/bootstrap/bootstrap.min.js') ?>"></script>

  <style>
    th input {
      border:1px solid #aaa;
      padding:3px 10px;
      width:100%;

    }

    .table thead tr th {
      vertical-align:middle;
    }

  </style>

  <?php $this->load->view('menu_view') ?>

  <div class="container">
    <div class="col-sm-12">
      <br>
      <?php if ($info) : ?>
      <div class="alert alert-info"><?php echo $info ?></div>
      <?php endif ?>
      <?php if ($danger) : ?>
      <div class="alert alert-danger"><?php echo $danger ?></div>
      <?php endif ?>

      <form id="sugestion_add" action="<?php echo site_url('healthrecord/sugestion/add') ?>" method="POST"></form>
      <table class="table">
        <thead>
          <tr>
            <th>Profesional Diagnostic</th>
            <th>Number Therapy's needed</th>
            <th>Electrostatics</th>
            <th>Biowater Consumption</th>
            <th>IR Sauna</th>
            <th>Electric Massage</th>
            <th>Others</th>
            <th>Action</th>
          </tr>
          <tr>
            <th><input type="text" name="diagnostic" form="sugestion_add"></th>
            <th><input type="text" name="number_therapy" form="sugestion_add"></th>
            <th><input type="text" name="electrostatic" form="sugestion_add"></th>
            <th><input type="text" name="biowater" form="sugestion_add"></th>
            <th><input type="text" name="sauna" form="sugestion_add"></th>
            <th><input type="text" name="massage" form="sugestion_add"></th>
            <th><input type="text" name="others" form="sugestion_add"></th>
            <th><input type="submit" class="btn btn-primary" form="sugestion_add" value="Add"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($list as $item): ?>
          <tr>
            <td><?php echo $item->diagnostic ?></td>
            <td><?php echo $item->number_therapy ?></td>
            <td><?php echo $item->electrostatic ?></td>
            <td><?php echo $item->biowater ?></td>
            <td><?php echo $item->sauna ?></td>
            <td><?php echo $item->massage ?></td>
            <td><?php echo $item->others ?></td>
            <td><a href="<?php echo site_url('healthrecord/sugestion/delete/'.$item->id); ?>" class="btn btn-danger">Delete</a></td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>
</html>
