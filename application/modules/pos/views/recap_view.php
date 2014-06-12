<div class="widget">
    <div class="whead">
        <div class="titleIcon"><span class="icon-users"></span></div>
        <h6>Sales Recap</h6>
        <div class="clear"></div>
    </div>
    <div class="shownpars">
    </div>
        <table class="dTable">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product->code ?></td>
                    <td><?php echo $product->name ?></td>
                    <td class="num"><?php echo number_format($product->price) ?></td>
                    <td class="num"><?php echo number_format($product->quantity) ?></td>
                    <td class="num"><?php echo number_format($product->total) ?></td>
                </tr>
                <?php endforeach ?>
                <tr>
                    <td>9999</td>
                    <td>Diskon</td>
                    <td></td>
                    <td></td>
                    <td class="num"><?php echo number_format($products_total->discount) ?></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan=4>Total</th>
                    <th class="num"><?php echo number_format($products_total->total) ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<script type="text/javascript">
$(function() {
    var start = '<?php echo $start ?>';
    var end = '<?php echo $end ?>';

    dTable = $('table.dTable').dataTable({
        "iDisplayLength" : 25,
        "aoColumns" : [ null, null, {'sType':'formatted-num'}, {'sType':'formatted-num'},{'sType':'formatted-num'} ],
    });

    function hideColumn () {
        if (  $(window).width() < 800 ) {
            dTable.fnSetColumnVis(0, false);
            dTable.fnSetColumnVis(2, false);
        } else  {
            dTable.fnSetColumnVis(0, true);
            dTable.fnSetColumnVis(2, true);
        } 
    }

    

    $('.dataTables_filter').append ('<label>Start : <input type=text name=start class="dStart" value="'+start+'" ></label> \
<label for=end>End : <input type=text class="dEnd" name=end value="'+end+'" ></label>');

    $('.dStart').datepicker();
    $('.dEnd').datepicker();
    $('.dStart, .dEnd').change(function () { window.location="?"+$('.dStart').serialize()+"&"+$('.dEnd').serialize()});


    hideColumn();

    $(window).resize = function () {
        hideColumn();
    }
    $("select, .check, .check:checkbox, input:radio, input:file").uniform({
        selectAutoWidth: false
    });
});
</script>
