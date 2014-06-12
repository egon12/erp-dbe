<div class="widget">
    <div class="whead">
        <div class="titleIcon"><span class="icon-document"></span></div>
        <h6>Kuitansi No <?php echo $id ?></h6>
        <div class="clear"></div>
    </div>
    <div class="shownpars">
        No : <?php echo $id ?><br />
        Tanggal : <?php echo date ('d, M Y H:i:s', strtotime($timestamp)) ?><br />
        Nama Pelanggan:<?php echo $customer_name?><br />
        Nama Kasir:<?php echo $user_name ?><br />
        <?php if (count($lines) > 0) :?>
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
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
                </tr>
                <?php endforeach ?> 
            </tbody>
            <tfoot>
                <tr>
                    <td> </td>
                    <td colspan=3>Subtotal</td>
                    <td colspan=2><?php echo number_format($subtotal) ?></td>
                </tr>
                <tr>
                    <td> </td>
                    <td colspan=3>Discount</td>
                    <td colspan=2><?php echo number_format($discount) ?></td>
                </tr>
                <tr>
                    <td> </td>
                    <td colspan=3>Total</td>
                    <td colspan=2><?php echo number_format($total) ?></td>
                </tr>
            </tfoot>
        </table>
        <?php endif ?>
    </div>
</div>
