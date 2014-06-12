<div id="po" style="border:thin solid;padding: 5px">
<?php /* todo bikin back otomatis di javascript taruh urlnya di sini */ ?>
<p>No Purchase Order : <?php echo $id ?></p>
<p>Tanggal : <?php echo date ('d, M Y', strtotime($created_on)) ?></p>
<p><?php echo $name?></p>
<p><?php echo $address?></p>
<p><?php echo $phone?></p>
<p><?php echo $email?></p>
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
            <td><?php echo number_format($subtotal) ?></td>
        </tr>
        <tr>
            <td> </td>
            <td colspan=3>Discount</td>
            <td><?php echo number_format($discount) ?></td>
        </tr>
        <tr>
            <td> </td>
            <td colspan=3>PPN</td>
            <td><?php echo number_format($tax) ?></td>
        </tr>
        <tr>
            <td> </td>
            <td colspan=3>Total</td>
            <td><?php echo number_format($total) ?></td>
        </tr>
    </tfoot>
</table>
<?php endif ?>
<p><?php echo $description ?></p>
<p>Hormat kami,</p>
<?php echo "<p>$user_create</p>" ?><br />

<?php echo ($user_approve != NULL) ? (($user_approve != $user_create) ?"<p>Mengetahui</p><p>$user_approve</p>" : "") : "Belum disetujui oleh pihak yang berwenang" ?><br /><br />
</div>
<button type=button onclick="printer()">Print</button>
