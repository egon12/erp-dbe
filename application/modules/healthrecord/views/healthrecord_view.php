<!DOCTYPE html>
<html>
  <meta charset="utf-8" />
  <title>Medika | Health Record</title>
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
  <script type="text/javascript" src="<?php echo asset_url('js/plugins/forms/phpjquery.js'); ?>"></script>
  <!-- /javascript -->

  <noscript></noscript>
  <style type="text/css">


    input[type="text"]
    {
      background:none repeat scroll 0% 0% rgb(255,255,255);
      text-align: left;
      cursor: text;
      outline:medium none;
      padding: 3px 3px;
      font-size:14px;
    }

    input[type="number"]
    {
      background:none repeat scroll 0% 0% rgb(255,255,255);
      border:solid 1px #aaa;
      border-radius:3px;
      text-align: right;
      cursor: text;
      outline:medium none;
      padding: 3px 3px;
      font-size:18px;
      width:50px;
      -moz-appearance:textfield;
    }

    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    select {
      width:100%;
      border:none;

    }

    select > option {
      padding:3px;
      font-family:"Helvetica ";
      font-size:14px;
    }

    textarea#patient_notes {
      height:200px;
    }

    .dropzone {
      position:relative;
      top:0;
      left:0;
      width:100%;
      height:300px;
      background-color:beige;
      background-size:contain;
      background-repeat:no-repeat;
      border:solid 1px #aaa;
      border-radius:3px;
      margin-bottom:10px;
    }

    .dropzone span {
      display:block;
      width:100%;
      padding-top:120px;
      text-align:center;
      vertical-align:middle;
    }

    .dropzone input[type="file"]{
      position:absolute;
      top:0;
      left:0;
      width:100%;
      height:100%;
      opacity:0;
    }

    .panel {
      box-shadow:5px 5px 10px #DCDCDC;

    }

  </style>

  <!-- topNav -->
  <div id="topNav">
    <div class="fixed">
      <div class="wrapper">
        <div class="welcome">
          <h4>Health Record</h4>
        </div>
        <div class="userNav">
          <ul>
            <li>
              <a href="#">
                <span>Hello, <?php echo $the_user->first_name.' '.$the_user->last_name; ?>!</span>
              </a>
            </li>
            <li>
              <a href="<?php echo site_url('auth/logout'); ?>" title="">
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

  <div id="page" class="wrapper">
    <div class="row">
      <div class="col-sm-12">
        <!-- place for messages -->
        <?php if ($alert_success): ?>
        <div class="alert alert-success"><?php echo $alert_success ?></div>
        <?php endif ?>
        <?php if ($alert_danger): ?>
        <div class="alert alert-danger"><?php echo $alert_danger ?></div>
        <?php endif ?>
      </div>

      <!-- messages end -->

    

      <div id="patient" class="col-md-3"> 

        <div class="panel panel-default">
          <div class="panel-heading"><h4 class="panel-title">Patient</h4></div>
          <ul class="list-group">
            <li class="list-group-item">
                <input id="customer_search" class="customer_id_input" type="text" name="query" placeholder="Search patient" data-url="<?php echo site_url('healthrecord/search_customer') ?>" autocomplete="off"/><br />
            </li>
            <li class="list-group-item">
                <select id="customer_list" class="customer_select" size="8">
                </select>
            </li>
            <li class="list-group-item">
              New Patient <br>
              <select class="customer_select" id="new_customer_select" size="8">
                <?php foreach ($new_customers as $customer): ?>
                  <option value="<?php echo $customer->id?>">
                    <?php echo $customer->name ?>
                  </option>
                <?php endforeach ?>
              </select>
            </li>
            <li class="list-group-item">
                <a href="<?php echo site_url('customer/add') ?>" target="_blank">Add New Patient</a>
            </li>
          </ul>
        </div>

      </div> <!-- end patient -->

      <div id="test" class="col-md-4 panel-group">


        <div id="general_panel" class="panel panel-default">
          <div class="panel-heading">
            <a data-toggle="collapse" data-parent="#test" href="#general_panel_content">
              <h4 class="panel-title">General</h4>
            </a>
          </div>
          <div id="general_panel_content" class="panel-collapse collapse in">
            <div  class="panel-body">
              <form class="form-horizontal" action="<?php echo site_url('healthrecord/add/general') ?>" method="POST" role="form">
                <input type="hidden" class="customer_id_input" name="customer_id">
                <div class="form-group">
                  <label for="systolic_input" class="col-sm-5">Systolic/Diastolic:</label>
                  <div class="col-sm-7">
                    <input type="number" name="systolic" id="systolic_input"> / <input type="number" name="diastolic">
                  </div>
                </div>
                <div class="form-group">
                  <label for="blood_glucose_input" class="col-sm-5">Blood Glucose:</label>
                  <div class="col-sm-7"><input type="number" name="blood_glucose" id="blood_glucose_input"></div>
                </div>
                <div class="form-group">
                  <label for="uric_acid_input" class="col-sm-5">Uric Acid:</label>
                  <div class="col-sm-7"><input type="number" name="uric_acid" id="uric_acid_input"></div>
                </div>
                <button class="btn btn-primary" type="submit">Save</button>
              </form>
            </div>
          </div>
        </div>


        <div id="disease_panel" class="panel panel-default">
          <div class="panel-heading">
            <a data-toggle="collapse" data-parent="#test" href="#disease_panel_content">
              <h4 class="panel-title">Disease</h4>
            </a>
          </div>
          <div id="disease_panel_content" class="panel-collapse collapse">
            <div class="panel-body">
              Blood Picture
              <div class="dropzone">
                <span>Drop image or click to add file</span>
                <input id="blood_file" type="file" name="file" multiple="false" data-picture="#blood_picture" />
              </div>
              <form action="<?php echo site_url('healthrecord/add/disease') ?>" method="POST" role="form">
                <input type="hidden" class="customer_id_input" name="customer_id" />
                <input id="blood_picture" type="hidden" name="picture" />
                <button class="btn btn-primary" type="submit">Save</button>
              </form>
            </div>
          </div>
        </div>


        <div id="beauty_panel" class="panel panel-default">
          <div class="panel-heading">
            <a data-toggle="collapse" data-parent="#test" href="#beauty_panel_content">
              <h4 class="panel-title">Beauty</h4>
            </a>
          </div>

          <div id="beauty_panel_content" class="panel-collapse collapse">
            <div class="panel-body">
              <form>
                Hair Picture
                <div class="dropzone">
                  <span>Drop image or click to add file</span>
                  <input id="hair_file" type="file" name="hair_file" multiple="false"/>
                </div>
                Skin Picture
                <div class="dropzone">
                  <span>Drop image or click to add file</span>
                  <input id="skin_file" type="file" name="skin_file" multiple="false"/>
                </div>
                <button class="btn btn-primary" type="submit">Save</button>
              </form>
            </div>
          </div>
        </div>


        <div id="observation_panel" class="panel panel-default">
          <div class="panel-heading">
            <a data-toggle="collapse" data-parent="#test" href="#observation_panel_content">
              <h4 class="panel-title">Observation and Interview</h4>
            </a>
          </div>
          <div id="observation_panel_content" class="panel-collapse collapse">
            <div class="panel-body">
              Notes:
              <textarea id="patient_notes" class="form-control"></textarea>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
          </div>
        </div>

      </div> <!-- end test -->

      <div id="result" class="col-md-5 panel-group">

        <div class="panel panel-default">
          <div class="panel-heading">
            <a data-toggle="collapse" data-parent="#result" href="#general_result_content">
              <h4 class="panel-title">General Result</h4>
            </a>
          </div>
          <div id="general_result_content" class="panel-collapse collapse in" data-url="<?php echo site_url('healthrecord/get_view') ?>"></div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
              <a data-toggle="collapse" data-parent="#result" href="#disease_result_content">
                <h4 class="panel-title">Disease Result</h4>
              </a>
            </div>
            <div id="disease_result_content" class="panel-collapse collapse" data-url="<?php echo site_url('healthrecord/get_view') ?>"></div>
        </div>
      </div><!-- end result panel-group -->

    </div> <!-- row -->
  </div> <!-- wrapper -->

  <script type="text/javascript">


    $(function(){

      $('.customer_select').on('change', function(e) {
        var customer_id = e.currentTarget.value;
        $('.customer_id_input').val(customer_id);

        var g = $('#general_result_content');
        g.load(g.data('url') + '/' + customer_id + '/general');

        var g = $('#disease_result_content');
        g.load(g.data('url') + '/' + customer_id + '/disease');

      });

      $('#customer_search').on('change', function(e) {
        $('.customer_id_input').val(e.currentTarget.value);
      });

      // dropzone
      //$("form").on('change', ".dropzone input[type='file']", function(e) {
      $(".dropzone input[type='file']").on('change', function(e) {
        var file = e.target.files[0];
        var reader = new FileReader();

        var a = $(e.target);
        reader.onload = function(e){
          var b = a.closest('.dropzone');
          b.css('backgroundImage', 'url(' + reader.result + ')');
          $(a.data('picture')).val(reader.result);
        };
        reader.readAsDataURL(file);
      });

      var ignoreKeys = [
        13, //enter
        33, //pgup
        34, //pgdn
        35, //home
        36, //end
        37, //left
        38, //up
        39, //right
        40, //down
      ];

      var r = null;

      $('input#customer_search').keyup(function (e) {
        if ( ignoreKeys.indexOf(e.keyCode) == -1 ) {
          clearTimeout(r);

          $this = $(this);

          if ($this.val() == '') {
            return;
          }

          url = $this.data('url');
          query = $this.serialize();
          
          r = setTimeout( function () {
            $.getJSON ( url, query, function ( data ) {
              $.processPHPJQueryCallback (data);
            });
          }, 300 );
        } 
      });


    });
  </script>

</html>
