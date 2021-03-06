<a href="<?php echo site_url('purchasing/not_active') ?>">Inactive Purchase Order</a>
<div class="widget">
    <div class="whead">
        <div class="titleIcon"><span class="icon-users"></span></div>
        <h6>Purchase Order Report</h6>
        <div class="clear"></div>
    </div>
    <div class="shownpars">
        <table class=dTable>
            <thead>
                <tr>
                    <th>No PO</th>
                    <th>Nama Vendor</th>
                    <th>Total Biaya</th>
                    <th>Tanggal Pemesanan</th>
                    <th>Status Terima</th>
                    <th>Telah dibayarkan</th>
                    <th>Status Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($table as $row) : ?>
                <tr>
                    <td><?php echo $row->id ?></td>
                    <td><?php echo $row->name ?></td>
                    <td><?php echo number_format($row->total) ?></td>
                    <td>
                        <?php if ($row->sent_on != NULL) : ?> 
                        <?php echo date('d M Y', strtotime($row->sent_on)) ?><br />
                        <?php endif ?>
                    </td>
                    <td><?php echo $row->arrived_status ?></td>
                    <td>
                        <?php echo number_format($row->payments_total) ?>
                    </td>
                    <td>
                        <?php echo ($row->total > $row->payments_total) ? '(Belum)' : '' ?>
                        <?php echo ($row->total < $row->payments_total) ? '(Lebih)' : '' ?>
                        <?php echo ($row->total == $row->payments_total) ? '(Lunas)' : '' ?>
                    </td>
                    <td>
                        <a class="detail tablectrl_small bDefault tipS" original-title="Detail" 
                            href="<?php echo site_url("purchasing/detail/$row->id") ?>" >
                            <span class="iconb" data-icon="&#xe1f7;"></span>
                        </a>
                        <?php if ($approve_button == TRUE) : ?>
                        <a class="tablectrl_small bDefault tipS" original-title="Approve"
                            href="<?php echo site_url("purchasing/approve/$row->id") ?>" >
                            <span class="iconb" data-icon="&#xe1f7;"></span>
                        </a>
                        <?php endif ?>
                        <a class="tablectrl_small bDefault tipS" original-title="Print" 
                            href="<?php echo site_url("purchasing/printer/$row->id") ?>" >
                            <span class="iconb" data-icon="&#xe1f7;"></span>
                        </a>
                        <a class="tablectrl_small bDefault tipS" original-title="Non Aktifkan"
                            href="<?php echo site_url("purchasing/cancel/$row->id") ?>" >
                            <span class="iconb" data-icon="&#xe136;"></span>
                        </a>
                    </td>
                </tr>
                <?php endforeach ?> 
            </tbody>
        </table>

    </div>
</div>
<div id=detail style="position:absolute;background-color:#fff"></div>
<script language="javascript" type=text/javascript >
    $(function() {

        dTable = $('table.dTable').dataTable({
            "aoColumnDefs" : [{"aTargets" : [7], "bSearchable": false, "bSortable": false, "sClass": 'tableActs', "sWidth" : "100px"}],
        });
        /**
        $('.dTable tbody tr').click(function() {
            if (dTable.fnIsOpen(this)) {
                dTable.fnClose(this);
            } else {
                alert($(this).children().html());
                dTable.fnOpen(this, "temporary row opened", "info_row");
            }
        });
        */

		$("select, .check, .check:checkbox, input:radio, input:file").uniform({
			selectAutoWidth: false
		});

        /* For Details overlay */

        $('a.detail').hover(function (e) {
            $('#detail').load( $(this).attr('href'), function () {
                $(this).offset({
                    top:e.pageY  - $(this).height()/2,
                        left:e.pageX - $(this).width() - 20
                });
            });
        }, function (e) {
            $('#detail').html('');
        });
    }); 
</script>
