<div class="infoMessage"><?php echo $messages ?></div>
<?php if (count($table) > 0): ?>
<div class="widget">
    <div class="whead wheadWarning">
        <div class="titleIcon"><span class="icon-drawer-2"></span></div>
        <h6>Barang yang belum datang</h6>
        <div class="clear"></div>
    </div>
    <form method="POST" action="" >
        <table id="arriveTable" class="dTable">
            <thead>
                <tr>
                    <th>No PO</th>
                    <th>Vendor</th>
                    <th>Ordered At</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>qty</th>
                    <th>check</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($table as $row) : ?>
                <?php foreach ($row->lines as $line) : ?>
                <?php if ($line->arrived_at == NULL): ?>
                <tr>
                    <td><?php echo $row->id ?></td>
                    <td><?php echo $row->name ?></td>
                    <td><?php echo date('d, M Y', strtotime($row->sent_on)) ?></td>
                    <td><?php echo $line->code ?></td>
                    <td><?php echo $line->name ?></td>
                    <td class="num"><?php echo $line->quantity ?></td>
                    <td class="tableActs"><input type=checkbox name="r[<?php echo $line->id ?>]" /></td>
                </tr>
                <?php endif ?>
                <?php endforeach ?>
                <?php endforeach ?>
            </tbody>
        </table>
        <div class="tableFooter">
            <?php //todo ok package atau biaya pengiriman itu giaman ?>
            <!-- <input type="text" name="package" placeholder="Biaya Pengambilan Barang" /> -->
            <input type="submit" class="buttonM formSubmit bBlue" value="Masukan"/>
        </div>
    </form>
</div>
<?php endif ?>
<div class="widget">
    <div class="whead">
        <div class="titleIcon"><i class="icon-document"></i></div>
        <h6>Stocks</h6>
        <a href="<?php echo site_url('stocks/add') ?>" class="buttonS bGreen formSubmit" >Add Item</a>
        <div class="clear"></div>
    </div>
    <div class="dateFilter desktopOnly">
        <form action="" method="POST">
            <div>
                <label for=start>Dari : </label>
                <input type=text id=start name=start value="<?php echo $start ?>" />
            </div>
            <div>
                <label for=end>Sampai : </label>
                <input type=text id=end name=end value="<?php echo $end ?>" />
            </div>
            <div>
                <input class="buttonM bBlue" type=submit />
            </div>
        </form>
    </div>
    <table id="stockTable" class="dTable">
        <thead>
            <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Start</th>
                <th>In</th>
                <th>Out</th>
                <th>End</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $stock_list as $stock ) : ?>
            <tr 
                <?php if ($stock->stock_end < 1): ?> class="rowFailure" 
                <?php elseif ($stock->stock_end < $stock->low): ?> class="rowWarning"
                <?php endif ?> 
                data-id="<?php echo $stock->id ?>" data-code="<?php echo $stock->code ?>">
                <td><?php echo $stock->code          ?></td>
                <td><?php echo $stock->name          ?></td>
                <td class="num"><?php echo $stock->stock_start   ?></td>
                <td class="num"><?php echo $stock->stock_in      ?></td>
                <td class="num"><?php echo $stock->stock_out     ?></td>
                <td class="num"><?php echo $stock->stock_end     ?></td>
                <td class="tableActs">
                    <a class="tablectrl_small bDefault tipS" title="Detail" original-title="Detail" 
                        href="<?php echo current_url().'/'.$stock->code ?>" >
                        <span class="iconb" data-icon="&#xe081;"></span>
                    </a>
                    <a class="tablectrl_small bDefault tipS" title="Stock In" original-title="Stock In" 
                        href="<?php echo site_url('stocks/in/'.$stock->code) ?>" >
                        <span class="iconb" data-icon="&#xe099;"></span>
                    </a>
                    <a class="tablectrl_small bDefault tipS" title="Stock Out" original-title="Stock Out" 
                        href="<?php echo site_url('stocks/out/'.$stock->code) ?>" >
                        <span class="iconb" data-icon="&#xe098;"></span>
                    </a>
                    <a class="tablectrl_small bDefault tipS" title="Edit" original-title="Stock Out" 
                        href="<?php echo site_url('stocks/edit/'.$stock->id) ?>" >
                        <span class="iconb" data-icon="&#xe004;"></span>
                    </a>
                    <a class="tablectrl_small bDefault tipS" title="Delete" original-title="Stock Out" 
                        onclick='return confirm("Are you sure want drop <?php echo $stock->name ?>")'
                        href="<?php echo site_url('stocks/delete/'.$stock->id) ?>" >
                        <span class="iconb" data-icon="&#xe136;"></span>
                    </a>
                </td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<div id="myModal" class="reveal-modal">
     <h1></h1>
     <p>What Do you want to do?</p>
     <div class="modalButtonWrapper">
         <a id="modalDetail" class="buttonM bGreyish modalButton" href="" >
             <span class="icon-eye-2"></span><span>Detail</span>
         </a>
     </div>
     <div class="modalButtonWrapper">
         <a id="modalIn" class="buttonM bGreyish modalButton" href="" >
             <span class="icon-plus-2"></span><span>Stock In</span>
         </a>
     </div>
     <div class="modalButtonWrapper">
        <a id="modalOut" class="buttonM bGreyish modalButton" href="" >
            <span class="icon-minus-2"></span><span>Stock Out</span>
        </a>
     </div>
     <div class="modalButtonWrapper">
        <a id="modalEdit" class="buttonM bGreyish modalButton" href="" >
            <span class="icon-pencil-2"></span><span>Edit Item</span>
        </a>
     </div>
     <div class="modalButtonWrapper">
         <a id="modalDelete" class="buttonM bGreyish modalButton" onclick='return confirm("Are you sure want drop this Item?")' href="" > 
             <span class="icon-x"></span><span>Delete</span>
        </a>
    </div>
     <a class="close-reveal-modal">&#215;</a>
</div>
<script type="text/javascript">

function hideColumn() {
    if ($('#arriveTable').length) {
        var arriveTable = $('table#arriveTable').dataTable();
    }
    var stockTable = $('table#stockTable').dataTable();

    if (  $(window).width() < 800 ) {
        if ($('#arriveTable').length) {
            arriveTable.fnSetColumnVis(0, false);
            arriveTable.fnSetColumnVis(2, false);
            arriveTable.fnSetColumnVis(3, false);
        }
        stockTable.fnSetColumnVis(2, false);
        stockTable.fnSetColumnVis(3, false);
        stockTable.fnSetColumnVis(4, false);
        stockTable.fnSetColumnVis(6, false);

        $('table#stockTable').on ('click', 'tr', function(e) {
            var tr = $(e.currentTarget);
            var id = tr.data('id');
            var code = tr.data('code');


            //set links
            $('#modalDetail').prop('href', '<?php echo current_url().'/'?>' + code);
            $('#modalIn').prop('href', '<?php echo site_url('stocks/in').'/'?>' + code);
            $('#modalOut').prop('href', '<?php echo site_url('stocks/out').'/'?>' + code);
            $('#modalEdit').prop('href', '<?php echo site_url('stocks/edit').'/'?>' + id);
            $('#modalDelete').prop('href', '<?php echo site_url('stocks/delete').'/'?>' + id);

            //reveal finally
            $('#myModal').reveal();
        });

    } else  {
        if ($('#arriveTable').length) {
            arriveTable.fnSetColumnVis(0, true);
            arriveTable.fnSetColumnVis(2, true);
            arriveTable.fnSetColumnVis(3, true);
        }
        stockTable.fnSetColumnVis(2, true);
        stockTable.fnSetColumnVis(3, true);
        stockTable.fnSetColumnVis(4, true);
        stockTable.fnSetColumnVis(6, true);
    }

}


$(window).resize(function () {
    hideColumn();
});


$('table#arriveTable').dataTable({
    'sDom':'t',
    'iDisplayLength':'100' ,
    'aoColumns': [ null, null, null, null, null, null, {'bSortable' : false, 'bSearchable' : false} ]

});

$('table#stockTable').dataTable({
    'bAutoWidth':false

});
hideColumn();


$("select, .check, .check:checkbox, input:radio, input:file").uniform({
    selectAutoWidth: false
});
</script>
