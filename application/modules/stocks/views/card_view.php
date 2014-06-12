<div class="infoMessage"><?php echo $messages ?></div>
<div class="widget">
    <div class="whead">
        <div class="titleIcon"><span class="icon-document"></span></div>
        <h6>Stock Card <?php echo $name ?> (<?php echo $code ?>)</h6>
        <div class="clear"></div>
    </div>
    <div class="dateFilter">
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
    <table class="dTable">
        <thead>
            <tr>
                <th>Date</th>
                <th>In / Out</th>
                <th>Stock</th>
                <th>Desc</th>
                <th>User</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($table as $row) : ?>
            <tr>
                <td><?php echo date ('d M Y H:i:s', strtotime($row->timestamp))  ?></td>
                <td class="num"><?php echo $row->in_out       ?></td>
                <td class="num"><?php echo $row->stock        ?></td>
                <td><?php echo $row->description  ?></td>
                <td><?php echo $row->username     ?></td>
                <td><a class="cancel tablectrl_small bDefault tipS" original-title="Non Aktifkan"
                            onclick='return confirm("Are you sure want to delete <?php echo $row->description.'('.$row->in_out.')' ?> ")'
                            href="<?php echo site_url("stocks/cancel/$row->id") ?>" >
                            <span class="iconb" data-icon="&#xe136;"></span>
                        </a></td>
            </tr>
            <?php endforeach ?> 
        </tbody>
    </table>
</div>
<script type="text/javascript">
$(function () {
    dTable = $('table.dTable').dataTable();

    $("select, .check, .check:checkbox, input:radio, input:file").uniform({
        selectAutoWidth: false
    });
});
</script>
