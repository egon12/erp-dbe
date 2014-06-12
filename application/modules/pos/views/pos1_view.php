<!DOCTYPE html>
<html>
  <meta charset="utf-8" />
  <title>Medika | POS</title>
  <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><![endif]-->
  <meta name="viewport" content="width=device-width" />
  <meta name="robots" content="noindex, nofollow">

  <!-- stylesheet -->
  <link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/reset.css'); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/font.css'); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/bootstrap.css') ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/style.css'); ?>"> 
  <link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/plugins.css'); ?>">
  <link rel="stylesheet" type="text/css" href="<?php echo asset_url('css/typeahead.css'); ?>">
  <!-- /stylesheet -->

  <!-- javascript -->
  <script type="text/javascript" src="<?php echo asset_url('js/jquery.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo asset_url('js/plugins/template/hogan-2.0.0.min.js'); ?>"></script>
  <script type="text/javascript" src="<?php echo asset_url('js/plugins/forms/typeahead.min.js'); ?>"></script>
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
      font-size:18px;
    }

    .payment_detail input {
      text-align:right;
    }

    #total_number {
      font-size:36px;
      text-align:right;
    }

  </style>

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

      <div id="fast-report" class="col-lg-3 col-md-3"> 
        <div class="panel panel-default">
          <div class="panel-heading"> <?php echo lang('fast_report')?> </div>
          <ul class="list-group">
            <li class="list-group-item">
              <span id="todays_income" class="badge"><?php echo $todays_income ?></span>
              <a href="<?php echo site_url('history') ?>" ><?php echo lang('todays_income')?></a><br>
              <small><?php echo lang('in_thousand') ?></small>
            </li>
            <li class="list-group-item">
              <span id="transactions_number" class="badge"><?php echo $transactions_number ?></span>
              <?php echo lang('todays_transaction') ?>
            </li>
            <li class="list-group-item">
              <span id="customers_number" class="badge"><?php echo $customers_number ?></span>
              <?php echo lang('todays_customer') ?>
            </li>
            <li class="list-group-item">
              <span id='new_customers_number' class="badge"><?php echo $new_customers_number ?></span>
              <a href="<?php echo site_url('customer/add') ?>" target="_blank" ><?php echo lang('new_customer') ?></a>
            </li>
            <li class="list-group-item">
              <a href="<?php echo site_url() ?>" ><?php echo lang('to_dashboard') ?></a>
            </li>
          </ul>
        </div>
      </div> <!-- end fast report -->

      <div id="sales-admin" class="col-lg-6 col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">Sales Administration</div>

          <ul class="list-group">
            <li class="list-group-item">
              <form action="<?php echo site_url('pos/set_customer')?>" method="post" class="cashier">
                <div class="form-group">
                  <label for="customer_input"><?php echo lang('customer_label')?></label>
                  <input id="customer_input" type="text" name="query" class="form-control" placeholder="<?php echo lang('customer_pl')?>">
                </div>
              </form>
              <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                  Customer: <span id="customer_name">Not yet specified</span>
                  <a id="customer_details"><?php echo lang ('customer_details') ?></a>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                  Last visit: <span id="customer_last_visit"></span>
                </div>
              </div>
            </li>


            <li class="list-group-item">
              <div class="row">
                <div class="col-lg-10 col-md-10">
                  <form class="cashier" method="post" action="<?php echo site_url('pos/set_product')?>">
                    <label for="product_input"><?php echo lang('produk')?></label>
                    <input id="product_input" type="text" name="query" placeholder="<?php echo lang('iproduk')?>" class="form-control">
                  </form>
                </div>
                <div class="col-lg-2 col-md-2">
                  <form id="jumlah" class="cashier" method="post" action="<?php echo site_url('pos/set_quantity') ?>">
                    <label for="product_input"><?php echo lang('jumlah')?></label>
                    <input id="quantity_input" type="text" name="query" placeholder="<?php echo lang ('ijumlah') ?>" class="form-control" >
                  </form>
                </div>
              </div>
            </li>
            <li class="list-group-item">
              <table id="purchase_item" class="table" >
                <thead>
                  <tr>
                    <th><?php echo lang ('produk')?></th>
                    <th><?php echo lang ('jumlah')?></th>
                    <th><?php echo lang ('harga')?></th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </li>
          </ul>
        </div>
      </div> <!-- end sales admin -->

      <div id="payment-detail" class="col-lg-3 col-md-3">
        <div class="payment_detail panel panel-default">
          <div class="panel-heading"> <?php echo lang('total')?> </div>
          <ul class="list-group">
            <li class="list-group-item">
              <?php echo lang('total')?>
              <div id="total_number">0</div>

            </li>
            <li class="list-group-item">
              <?php echo lang('discount')?>
              <form class="cashier" method="post" action="<?php echo site_url('pos/set_discount') ?>">
                <input id="discount_input" type="text" name="query" value="0" class="form-control" >
              </form>
            </li>
            <li class="list-group-item">
              <?php echo lang('method')?>
              <form class="cashier" method="post" action="<?php echo site_url('pos/set_method') ?>">
                <select id="method_input" name="query" class="form-control">
                  <option value="cash">Cash</option>
                  <option value="debit_card">Debit Card</option>
                  <option value="credit_card">Credit Card</option>
                </select>
              </form>
            </li>
            <li class="list-group-item">
              <?php echo lang('payment')?>
              <form class="cashier" method="post" action="<?php echo site_url('pos/set_payment') ?>">
                <input id="payment_input" type="text" name="query" value="0" class="form-control" >
              </form>
            </li>
          </ul>
        </div>
      </div><!-- end payment-detail -->

    </div> <!-- row -->
  </div> <!-- wrapper -->
   <applet name="jzebra" code="jzebra.PrintApplet.class" archive="<?php echo base_url('assets/jzebra.jar') ?>" height="10px">
        <param name="printer" value="zebra">
        </applet>


  <script type="text/javascript">

    $(function(){

      // phpjquerycallback plugin to execute javascript the response from server
      $('form.cashier').phpjquerycallback();

      // select all on focus 
      $("input:text").focus(function() { $(this).select(); } );

      // for product input
      var findProductUrl = "<?php echo site_url('pos/find_product') ?>/%QUERY";
      var tmpl = "<strong>{{code}}</strong>: {{name}}, {{price}}";
      productInput = $('#product_input');
      productInput.typeahead({ limit:12, valueKey: 'code', remote: findProductUrl, template:tmpl, engine: Hogan, });
      productInput.on ('typeahead:selected', function (e, datum) { $(this.form).submit(); });
      productInput.keydown(function (e) { if (e.keyCode == 13) { $(this.form).submit() } });

      // for customer Input
      var findCustomerUrl = "<?php echo site_url('pos/find_customer') ?>/%QUERY";
      var tmpl = "{{id}}: <strong>{{name}}</strong>, {{address}} phone: {{phone}}";
      customerInput = $('#customer_input');
      customerInput.typeahead({ limit:10, valueKey: 'id', remote: findCustomerUrl, template:tmpl, engine: Hogan, });
      customerInput.on ('typeahead:selected', function (e, datum) { $(this.form).submit(); });
      customerInput.keydown(function (e) { if (e.keyCode == 13) { $(this.form).submit() } });

      customerInput.focus();

      // for method input because it is a select
      $('#method_input').keydown(function (e) { if (e.keyCode == 13) { $(this.form).submit() }  });

  });
  </script>

</html>
