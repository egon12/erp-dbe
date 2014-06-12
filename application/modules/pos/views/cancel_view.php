<div class="widget">
    <div class="whead">
        <div class="titleIcon"><span class="icon-users"></span></div>
        <h6>Transaction on <?php echo $date ?></h6>
        <div class="clear"></div>
    </div>
    <div class="shownpars">
        <?php if (count ($receipts) > 0): ?>
        <table id="cancelTable1" class="dTable">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Id</th>
                    <th>Customers Name</th>
                    <th>Cashier</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($receipts as $receipt):?>
                <tr>
                    <td><?php echo date("H:i:s", strtotime($receipt->timestamp)) ?></td>
                    <td><?php echo $receipt->id ?></td>
                    <td><?php echo $receipt->customer_name ?></td>
                    <td><?php echo $receipt->user_name ?></td>
                    <td><?php echo $receipt->products ?></td>
                    <td class="num"><?php echo $receipt->total ?></td>
                    <td class="tableActs">
                        <a class="detail tablectrl_small bDefault tipS" title="Detail" original-title="Detail" 
                            href="<?php echo site_url("pos/detail/$receipt->id") ?>" >
                            <span class="iconb" data-icon="&#xe1f7;"></span>
                        </a><a class="cancel tablectrl_small bDefault tipS" title="Cancel" original-title="Cancel"
                            href="<?php echo site_url("pos/cancel/$receipt->id") ?>" ><span class="iconb" data-icon="&#xe136;"></span>
                        </a>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th><?php echo number_format($receipts_total->number) ?></th>
                    <th>Customer Number = <?php echo number_format($receipts_total->customer) ?></th>
                    <th></th>
                    <th>Item Number = <?php echo number_format($receipts_total->quantity) ?></th>
                    <th class="num"><?php echo number_format($receipts_total->total) ?></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
        <?php else: ?>
        There are no Transaction today
        <?php endif ?>
    </div>
</div>
<div class="widget">
    <div class="whead">
        <div class="titleIcon"><span class="icon-users"></span></div>
        <h6>Cancelled Transaction on <?php echo $date ?></h6>
        <div class="clear"></div>
    </div>
    <div class="shownpars">
        <?php if (count ($receipts_failed) > 0): ?>
        <table id="cancelTable2" class="dTable">
            <thead>
                <tr>
                    <th>Cancelled Time</th>
                    <th>Id</th>
                    <th>Customer Name</th>
                    <th>Users</th>
                    <th>Canceled Reason</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($receipts_failed as $receipt):?>
                <tr>
                    <td><?php echo date ('H:i:s', strtotime($receipt->timestamp)) ?></td>
                    <td><?php echo $receipt->id ?></td>
                    <td><?php echo $receipt->customer_name ?></td>
                    <td><?php echo $receipt->user_name ?></td>
                    <td><?php echo $receipt->inactive_reason ?></td>
                    <td class="num"><?php echo number_format($receipt->total) ?></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <?php else: ?>
        There are no Cancelled Transaction Today
        <?php endif ?>
    </div>
</div>
<div id=detail style="position:absolute;background-color:#fff;"> </div>
<script language="javascript" type=text/javascript >
    $(function() {
        $('table#cancelTable1').dataTable({
            "aoColumns" : [ {"sWidth" : "30px"}, {"sWidth" : "30px"}, null, null, null, {"sType":"formatted-num"}, {"bSearchable": false, "bSortable": false, "sWidth" : "50px"}],
        });

        $('table#cancelTable2').dataTable({
            "aoColumns" : [ {"sWidth" : "30px"}, {"sWidth" : "30px"}, null, null, null, {"sType":"formatted-num"}],
        });

		$("select, .check, .check:checkbox, input:radio, input:file").uniform({
			selectAutoWidth: false
		});

        $('#cancelTable1').on('mouseenter', 'a.detail', function (e) {
            $('#detail').load( $(this).attr('href'), function () {
                $(this).offset({
                    top:e.pageY  - $(this).height()/2,
                    left:e.pageX - $(this).width() - 20
                });
            });
        }); 
            
        $('#cancelTable1').on('mouseleave', 'a.detail', function (e) {
            $('#detail').html('');
        });

        $('#cancelTable1').on('click', 'a.cancel', function (e) {
            e.preventDefault();
            var inactive_reason = prompt ("Alasan Pembatalan?");
            location = ($(this).attr('href') +'?inactive_reason=' +inactive_reason);
        });

    }); 
</script>
