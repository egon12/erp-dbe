<h1>Kasir</h1>
<form id="no_nama" class="cashier" method="get" action="<?php echo site_url('pos/tulis_nama') ?>">
    <input id="ino_nama" class="input_text ui-button ui-widget ui-state-default ui-corner-all"  type="text" name="query" placeholder="Masukan nama atau no pelanggan" data-source="<?php echo site_url('pos/cari_orang')?>"  style='width: 800px;background:none repeat scroll 0% 0% rgb(255,255,255);text-align: left; cursor: text; outline:medium none; padding: 8px 3px;font-size:18px;font-family:inherit;'>
</form>
<div id="customer_info"> </div>
<br />
<table id="purchase_item">
    <thead>
        <tr>
            <th style="padding:9px">Produk</th>
            <th style="padding:9px">Jumlah</th>
            <th style="padding:9px">Harga</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        </tr>
    </tbody>
    <tfoot>
        <tr class="baris_input">
            <td> 
                <form id="produk" class="cashier" method="get" action="<?php echo site_url('pos/tulis_produk') ?>">
                    <input id="iproduk" class="input_text ui-button ui-widget ui-state-default ui-corner-all"  type="text" name="query" width="20" placeholder="Masukkan kode produk" data-source="<?php echo site_url('pos/cari_produk')?>"  style='width: 400px;background:none repeat scroll 0% 0% rgb(255,255,255);text-align: left; cursor: text; outline:medium none; padding: 8px 3px;font-size:18px;font-family:inherit;'>
                </form>
            </td>
            <td>
                <form id="jumlah" class="cashier" method="get" action="<?php echo site_url('pos/tulis_jumlah') ?>">
                    <input type="text" class="input_number ui-button ui-widget ui-state-default ui-corner-all" name="query" placeholder="jumlah" style='width: 100px;background:none repeat scroll 0% 0% rgb(255,255,255);text-align: right; cursor: text; outline:medium none; padding: 8px 3px;font-size:18px;font-family:inherit;'>
                </form>
            </td>
            <td>
                <input type="text" class="input_number ui-button ui-widget ui-state-default ui-corner-all" name="query" value="0" readonly="readonly" style='background:none repeat scroll 0% 0% rgb(255,255,255);text-align: right; cursor: text; outline:medium none; padding: 8px 3px;font-size:18px;font-family:inherit;'>
            </td>
        </tr>
    </tfoot>
</table>
<br />
<table>
    <tr>
        <td></td>
        <td>Subtotal</td>
        <td id="subtotal">
            <input type="text" class="input_number ui-button ui-widget ui-state-default ui-corner-all" name="query" value="0" readonly="readonly" style='background:none repeat scroll 0% 0% rgb(255,255,255);text-align: right; cursor: text; outline:medium none; padding: 8px 3px;font-size:18px;font-family:inherit;'>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>Diskon</td>
        <td>
            <form id="diskon" class="cashier" method="get" action="<?php echo site_url('pos/tulis_diskon') ?>">
                <input type="text" class="input_number ui-button ui-widget ui-state-default ui-corner-all" name="query" placeholder="Diskon" style='background:none repeat scroll 0% 0% rgb(255,255,255);text-align: right; cursor: text; outline:medium none; padding: 8px 3px;font-size:18px;font-family:inherit;'>
            </form>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>Total</td>
        <td id="total">
            <input type="text" class="input_number ui-button ui-widget ui-state-default ui-corner-all" name="query" value="0" readonly="readonly" style='background:none repeat scroll 0% 0% rgb(255,255,255);text-align: right; cursor: text; outline:medium none; padding: 8px 3px;font-size:18px;font-family:inherit;'>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>Pembayaran</td>
        <td>
            <form id="pembayaran" class="cashier" method="get" action="<?php echo site_url('pos/tulis_pembayaran') ?>">
                <input type="text" class="input_number ui-button ui-widget ui-state-default ui-corner-all" name="query" placeholder="Jumlah Pembayaran" style='background:none repeat scroll 0% 0% rgb(255,255,255);text-align: right; cursor: text; outline:medium none; padding: 8px 3px;font-size:18px;font-family:inherit;'>
            </form>
        </td>
    </tr>
    <tr>
        <td></td>
        <td>Kembalian</td>
        <td id="kembalian">
            <input type="text" class="input_number ui-button ui-widget ui-state-default ui-corner-all" name="query" value="0" readonly="readonly" style='background:none repeat scroll 0% 0% rgb(255,255,255);text-align: right; cursor: text; outline:medium none; padding: 8px 3px;font-size:18px;font-family:inherit;'>
        </td>
    </tr>
</table>

        <applet name="jzebra" code="jzebra.PrintApplet.class" archive="<?php echo base_url('assets/jzebra.jar') ?>" height="10px">
        <param name="printer" value="zebra">
        </applet>

        <script type="text/javascript">

            $(function(){
                function proccessPHPJQueryCallback (data) {
                    //uncomment line di bawah ini untuk aktifin log
                    if (data.log) { 
                        console.log(data.log); 
                    }
                    if (data.jsprint) { 
                        //require jzebra
                        try {
                            var applet = document.jzebra;
                            applet.findPrinter();
                            applet.append(data.jsprint);
                            applet.append("\n\n\n\n\n\n\n\n\n\n");
                            applet.append("\x1bm");
                            applet.print();
                        } catch (err) {
                            alert ("Error\nPrinter bermasalah:\n");
                        }
                    }
                    if (data.alert) { 
                        alert(data.alert); 
                    }
                    if (data.before) {
                        for (i=0;i<data.before.length;i++) {
                            $(data.before[i].selector).before(data.before[i].msg);
                        }
                    }
                    if (data.after) {
                        for (i=0;i<data.after.length;i++) {
                            $(data.after[i].selector).after(data.after[i].msg);
                        }
                    }
                    if (data.html) {
                        for (i=0;i<data.html.length;i++) {
                            $(data.html[i].selector).html(data.html[i].msg);
                        }
                    }
                    if (data.append) {
                        for (i=0;i<data.append.length;i++) {
                            $(data.append[i].selector).append(data.append[i].msg);
                        }
                    }
                    if (data.val) {
                        for (i=0;i<data.val.length;i++) {
                            $(data.val[i].selector).val(data.val[i].msg);
                        }
                    }
                    if (data.focus) { 
                        $(data.focus).focus(); 
                    }
                    if (data.jseval) {
                        jseval = decodeURIComponent ((data.jseval +'').replace(/\+/g, '%20'))
                            eval (jseval);
                    }
                    if (data.redirect) {
                        window.location = data.redirect
                    }
                }

                $('form.cashier').removeAttr('onsubmit')
                    .submit(function (e) {
                        e.preventDefault();
                        $.ajax({
                            url      : $(this).attr('action'), 
                            data     : $(this).serialize(),
                            dataType : 'json',
                            success  : proccessPHPJQueryCallback,
                            error    : function (obj,stat) {
                                alert (stat + ":" + obj.responseText);
                            }
                        });
                    });

                $("input:text").focus(function() { $(this).select(); } );


                $('#ino_nama').autocomplete ({ 
                    source: function ( request,response ) {
                        $.getJSON(
                            $('#ino_nama').data('source'),
                            request,
                            response
                        )},
                    delay: 0,
                    minLength: 1,
                    select: function (event, ui ) {
                        event.preventDefault();
                        this.value = ui.item.value;
                        $(this).closest('form').submit(); 
                    }
                });

                $('#iproduk').autocomplete ({
                    source: function ( request,response ) {
                        $.getJSON(
                            $('#iproduk').data('source'),
                            request,
                            response
                        )},
                    delay: 0,
                    minLength: 1,
                    select: function (event, ui ) {
                        this.value = ui.item.value;
                        $(this).closest('form').submit(); 
                    },
                });


                $("#ino_nama").focus();
            });
        </script>
