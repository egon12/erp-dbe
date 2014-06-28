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
  <link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/datepicker3.css'); ?>">
  <!-- /stylesheet -->

  <!-- javascript -->
  <script type="text/javascript" src="<?php echo asset_url('js/jquery.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo asset_url('js/plugins/bootstrap/bootstrap.min.js') ?>"></script>
  <script type="text/javascript" src="<?php echo asset_url('js/plugins/charts/jquery.flot.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo asset_url('js/plugins/date/moment.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo asset_url('js/plugins/forms/phpjquery.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo asset_url('js/plugins/forms/bootstrap-datepicker.js'); ?>"></script>
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

    input[type="number"], input[type="number"].form-control
    {
      background:none repeat scroll 0% 0% rgb(255,255,255);
      border:solid 1px #ccc;
      border-radius:3px;
      text-align: right;
      cursor: text;
      outline:medium none;
      padding: 3px 5px;
      font-size:18px;
      width:50px;
      -moz-appearance:textfield;
    }

    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    span.satuan {
      display:inline-block;
      border: 1px solid #ccc;
      padding:5px 3px 1px;
      margin-left:-6px;
      background-color:#e8e8e8;
      border-radius:0 3px 3px 0;
      font-size:11px;

    }

    input.datepicker {
      width:100px;

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

    textarea#sugestion_note {
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

    .chart {
      height:300px;

    }

  </style>

  <!-- topNav -->
  <?php $this->load->view('menu_view') ?>
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
    </div>

      <!-- messages end -->

    
    <div class="row">

      <div id="patient" class="col-md-3"> 

        <div class="panel panel-default">
          <div class="panel-heading"><h4 class="panel-title">Patient</h4></div>
            <?php $this->load->view('healthrecord_patient_view') ?>
        </div>

      </div> <!-- end patient -->

      <div id="right_panel_container" class="col-md-9 panel-group">
        <div class="panel panel-default">

          <div class="panel-body">
            <ul id="tab_right" class="nav nav-tabs" role="tablist">
              <li class="active"><a href="#general_tab" data-toggle="tab">General</a></li>
              <li><a href="#card_tab" data-toggle="tab">History</a></li>
            </ul>

            <div id="tabContent" class="tab-content">
              <div id="general_tab" class="tab-pane active">
                <?php $this->load->view('healthrecord_general_input_view'); ?>
              </div>

              <div id="card_tab" class="tab-pane">
                <div id="general_result_table" data-url="<?php echo site_url('healthrecord/get_card') ?>"></div>
              </div>
            </div>
          </div>
        </div>
      </div>


    </div> <!-- row -->
  </div> <!-- wrapper -->
  <div id="modal" class="modal fade"></div>

  <script type="text/javascript">

    $(function(){
      function setGeneralData(data) { 
        var systolic = [];
        var diastolic = [];
        var ldl = [];
        var blood_glucose = [];
        var uric_acid = [];
        for (i=0; i< data.length; i++) {
          var date = moment(data[i].timestamp).format('l');

          systolic.push([i,data[i].systolic]);
          diastolic.push([i,data[i].diastolic]);
          ldl.push([i,data[i].ldl]);
          blood_glucose.push([i,data[i].blood_glucose]);
          uric_acid.push([i,data[i].uric_acid]);

        }

        var processedData = [
          { label: 'Systolic', data: systolic, lines: {show: true}, points: {show: true} } ,
          { label: 'Diastolic', data: diastolic, lines: {show: true}, points: {show: true} } ,
          { label: 'LDL', data: ldl, lines: {show: true}, points: {show: true} } ,
          { label: 'Blood Glucose', data: blood_glucose, lines: {show: true}, points: {show: true} } ,
          { label: 'Uric Acid', data: uric_acid, lines: {show: true}, points: {show: true} } 
        ];

        $.plot('#general_chart', processedData);
      }

      function setDiseaseData(data) {
        $('#disease_table tbody').html('');

        for (i = 0; i<data.length; i++) {
          var tr = $('<tr>');
          tr.appendTo('#disease_table tbody');
          // adding image
          var td = $('<td>').appendTo(tr);
          $('<img>').prop('src', data[i].picture).appendTo(td);
          // adding timestamp
          $('<td>').appendTo(tr).html(data[i].timestamp);
        };
      }

      function setCustomer(customer_id) {

        $('#general_result_table').load($('#general_result_table').data('url') + '/' + customer_id);

        // set all input 
        $('.customer_id_input').val(customer_id);
        $('.save-button').prop('disabled', '');
        $('#customer_name').html($('#customer_list option[value="' + customer_id + '"]').html());

      }


      $('.costumer_select option').prop('selected', false);
      $('.save-button').prop('disabled', 'disabled');

      $('.customer_select').on('change', function(e) {

        var customer_id = e.currentTarget.value;
        setCustomer(customer_id);

      });

      $('#customer_search').on('change', function(e) {
        $('.customer_id_input').val(e.currentTarget.value);
      });

      // dropzone
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

          url = $this.data('url');
          query = $this.serialize();

          r = setTimeout( function () {
            $.getJSON ( url, query, function ( data ) {
              $.processPHPJQueryCallback (data);
            });
          }, 300 );
        } 
      });


      $('#sugestion_key').on('change', function(e) {
        var url = $('#sugestion_key').data('url') +'/' + e.currentTarget.value;
        $.getJSON( url, function(data) {
          $('#sugestion_note').html(data.sugestion);
        });
      });

      $('.datepicker_input').datepicker({format:"yyyy-mm-dd"});

      $('#tab_right a').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
      });



    });

  </script>

</html>
