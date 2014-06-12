<div class="widget">
    <div class="whead">
        <div class="titleIcon"><span class="icon-users"></span></div>
        <h6>Transaction History</h6>
        <div class="clear"></div>
    </div>
    <div class="shownpars">
        <table class="dataTable">
            <thead>
                <tr>
                    <th>Date and Time</th>
                    <th>Customers ID</th>
                    <th>Customers</th>
                    <th>Products</th>
                    <th>Subtotal</th>
                    <th>Discount</th>
                    <th>Total</th>
                    <th>Cashier</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4">Total</th>
                    <th id="subtotal"></th>
                    <th id="discount"></th>
                    <th id="total"></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div id=detail style="position:absolute;background-color:#fff;"> </div>
<script language="javascript" type=text/javascript >
    $(function() {
        var base = "<?php echo site_url('history') ?>/";
        var now = "<?php echo date('Y-m-d') ?>";
        var tomorrow = "<?php echo date('Y-m-d', strtotime('tomorrow')) ?>";

        function num_process (number, active) {
          if (active == 1) 
          {
            return numeral(number).format();
          } else {
            return '<del>'+numeral(number).format()+'</del>';
          }
        }

        var historyTable = $('table.dataTable').dataTable({
          "bProcessing": true,
          "bServerSide": true,
          "sAjaxSource": base + 'datatables',
          //"sDom":'rtip',
            "fnServerData":function ( sSource, aoData, fnCallback ) {
              /* Add some extra data to the sender */
              // todo add some security?
              var dStart = now;
              var dEnd = tomorrow;
              var bNew = 0;
              var bCancelled = 0;
              if ($('input[name="dStart"]').val() != undefined) 
              { 
                dStart = $('input[name="dStart"]').val(); 
                dEnd = $('input[name="dEnd"]').val(); 
                bNew = $('input[name="bNew"]').is(':checked') ? 1 : 0; 
                bCancelled = $('input[name="bCancelled"]').is(':checked') ? 1 : 0; 

              } 

              aoData.push( 
                { name:"dStart", value:dStart },
                { name:"dEnd", value:dEnd },
                { name:"bNew", value:bNew },
                { name:"bCancelled", value:bCancelled }
              );
              $.getJSON( sSource, aoData, function (json) {
                  /* Do whatever additional processing you want on the callback, then tell DataTables */
                  console.log(json);

                  $('#subtotal').html(numeral(json.fSubtotal).format());
                  $('#discount').html(numeral(json.fDiscount).format());
                  $('#total').html(numeral(json.fTotal).format());
                  fnCallback(json)
              } );
            },

          // set the column
          "aoColumns" : [
            {'mDataProp':function (source) { return moment(source.timestamp).format('lll')}}, 
            {'mDataProp':'customer_id'},
            {'mDataProp':function (source) { return (source.new == 0) ? source.customer_name : ( '<strong>'+source.customer_name+'</strong>' )}},
            {'mDataProp':function (source) {
              if (source.active == 1) {
                return source.products;
              } else {
                return source.products + ' ' + source.inactive_reason;
              }
            }}, 
            {'mDataProp':function (source) { return num_process(source.subtotal, source.active)}, 'sClass':'num'},
            {'mDataProp':function (source) { return num_process(source.discount, source.active)}, 'sClass':'num'},
            {'mDataProp':function (source) { return num_process(source.total, source.active) },    'sClass':'num'},
            {'mDataProp':'user_name'},
          ],
        });

        // set on change not on keyup
        $('.dataTables_filter input').unbind('keypress keyup');
        $('.dataTables_filter input').addClass('sSearch');
        //$('.dataTables_filter').append(' <label>From <input type="date" class="dStart" name="dStart" value="'+now+'"></label>');
        //$('.dataTables_filter').append(' <label>To <input type="date" class="dEnd" name="dEnd" value="'+tomorrow+'"></label>');
        $('.dataTables_filter').append(' <label>From <input type="text" class="dStart" name="dStart" value="'+now+'" style="width:55px"></label>');
        $('.dataTables_filter').append(' <label>To <input type="text" class="dEnd" name="dEnd" value="'+tomorrow+'" style="width:55px"></label>');
        $('.dataTables_filter').append(' <label>New<input type="checkbox" class="bNew" name="bNew" ></label>');
        $('.dataTables_filter').append(' <label>Cancelled <input type="checkbox" class="bCancelled" name="bCancelled" ></label>');

        $('.dStart').datepicker();
        $('.dEnd').datepicker();

        // reparing because somthing is broke
        $('.dataTables_filter input.sSearch').change(function () {historyTable.fnFilter($(this).val())});
        $('input.dStart').change(function () {historyTable.fnFilter($('.dataTables_filter .sSearch').val())});
        $('input.dStart').change(function () {historyTable.fnFilter($('.dataTables_filter .sSearch').val())});
        $('input.bNew').change(function () {historyTable.fnFilter($('.dataTables_filter .sSearch').val())});
        $('input.bCancelled').change(function () {historyTable.fnFilter($('.dataTables_filter .sSearch').val())});


		$("select, .check, .check:checkbox, input:radio, input:file").uniform({
			selectAutoWidth: false
		});

	  // for download.
	
    $('<button type="button">Download</button>').
    appendTo('.dataTables_filter').click( function () {
	
    		var returnObject = {};

    		returnObject.dStart = $('input[name="dStart"]').val(); 
    		returnObject.dEnd = $('input[name="dEnd"]').val(); 
    		returnObject.bNew = $('input[name="bNew"]').is(':checked') ? 1 : 0; 
    		returnObject.bCancelled = $('input[name="bCancelled"]').is(':checked') ? 1 : 0; 
    		returnObject.sSearch = $('input[name="sSearch"]').val(); 

    		window.location = base + "download?" + $.param(returnObject);
    });
	
}); 
   
</script>
