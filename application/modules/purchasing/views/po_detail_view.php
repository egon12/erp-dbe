<div class="fluid">
    <div class="widget">
        <div class="whead">
            <div class="titleIcon"><span class="icon-document"></span></div>
            <h6>Purchase Order no <?php echo $id?></h6>
            <div class="clear"></div>
        </div>
        <div class="formRow">
            <?php /* todo ok kenapa jadi pengformatan ada di sinsi */ ?>
            Status Pemesanan : <?php echo ($sent_on != NULL) ? "Dikirim tanggl ".date('d, M Y', strtotime($sent_on)) : "Belum dikirimkan" ?><br/>
            Kepada:<br />
            <strong><?php echo $name?></strong> <br />
            <?php echo $address?><br />
            Telp:<?php echo $phone?><br />
            Email:<?php echo $email?><br />

            Dibuat Tanggal : <?php echo date ('d M Y H:i', strtotime($created_on)) ?> Oleh : <?php echo $user_create ?> <br />
            Disetujui Tanggal : <?php echo date ('d M Y H:i', strtotime($approved_on)) ?> Oleh : <?php echo $user_approve ?><br /> 
        </div>

        <div class="formRow">
        <?php if (count($lines) > 0) :?>
        <table cellspacing=0>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
                    <th>Tanggal Tiba</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lines as $row) : ?>
                <tr>
                    <td><?php echo $row->code ?></td>
                    <td><?php echo $row->name ?></td>
                    <td><?php echo number_format($row->quantity) ?></td>
                    <td><?php echo number_format($row->price) ?></td>
                    <td><?php echo number_format($row->total) ?></td>
                    <td><?php echo ($row->arrived_at != NULL) ? date('d, M Y', strtotime($row->arrived_at)) : "Belum Tiba" ?></td>
                </tr>
                <?php endforeach ?> 
            </tbody>
            <tfoot>
                <tr>
                    <td colspan=4>Subtotal</td>
                    <td><?php echo number_format($subtotal) ?></td>
                </tr>
                <tr>
                    <td colspan=4>Discount</td>
                    <td><?php echo number_format($discount) ?></td>
                </tr>
                <tr>
                    <td colspan=4>PPN</td>
                    <td><?php echo number_format($tax) ?></td>
                </tr>
                <tr>
                    <td colspan=4>Total</td>
                    <td><?php echo number_format($total) ?></td>
                </tr>
            </tfoot>
        </table>
        <?php endif ?>
        </div>

        <div class="formRow">
            Keterangan: <br />
            <p><?php echo $description ?></p>
        </div>

        <?php if (count($payments) > 0) : ?>
        <div class="formRow">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?php echo date ('d M Y H:i', strtotime($payment->timestamp)) ?></td>
                    <td><?php echo number_format($payment->amount) ?></td>
                    <td>
                        <a class="tablectrl_small bDefault tipS" original-title="Batalkan" 
                            href="<?php echo site_url('purchasing/cancel_pay/'.$payment->id); ?>" >
                            <span class="iconb" data-icon="&#xe136;"></span></a>
                    </td> 
                </tr>
                <?php endforeach ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>Total</td><td><?php echo number_format($payments_total) ?></td>
                    <td>
                        <?php echo ($total > $payments_total) ? '(Belum lunas)' : '' ?>
                        <?php echo ($total < $payments_total) ? '(Lebih bayar)' : '' ?>
                        <?php echo ($total == $payments_total) ? '(Lunas)' : '' ?>
                    </td>
                </tr>
            </tfoot>
        </table> 
        </div>
        <?php endif ?>
        <?php if ($active && !isset($ajax) ) : ?>
        <div id="pembayaran" class="formRow">
            <form method="POST" action="<?php echo site_url('purchasing/pay') ?>" > 
                <div class="grid2"><label for="pay_date">Tanggal pembayaran</label></div>
                <div class="grid2"><input id="pay_date" type="text" name="date" value="<?php echo date('d M Y H:i:s') ?>" /></div>
                <div class="grid2"><label for="pay_amount">Jumlah pembayaran</label></div>
                <div class="grid2"><input id="pay_amount" type="text" name="amount" /></div>
                <input type="hidden" name="po_id" value="<?php echo $id?>" />
                <div class="grid2"><input type="submit" class="buttonM bBlue formSubmit" /></div>
            </form>
        </div>
        <br /><br />
        <?php endif  ?>
    </div>
</div>
