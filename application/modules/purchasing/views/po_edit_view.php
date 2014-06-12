<script type="text/javascript">

    function add(e) {
        e.preventDefault();

        var inDiv = $('.poline:last').html();
        var number = parseInt (   inDiv.match(/code\[(\d)/m)[1]  );
        number += 1;

        inDiv = inDiv.replace(/code\[(\d)/m, 'code['+number);
        inDiv = inDiv.replace(/quantity\[(\d)/m, 'quantity['+number);
        inDiv = inDiv.replace(/price\[(\d)/m, 'price['+number);
        $('.poline:last').after('<div class="formRow poline">'+inDiv+'</div>');
        $('.delButton').prop('disabled', false);
    }

    function del(object) {

        $(object).parent().parent().remove();
        if ($('.poline').length < 2) {
            $('.delButton').prop('disabled', true);
        }
        return false;
    }


    $('.proAddButton').click(function () {
        console.log($(this).parent());
        $('.poline:last').after( $(this).parent().html() );

    });

    //on start
    $(function () {
        if ($('.poline').size() < 2) {
            $('.delButton').prop('disabled', true);
        }
        //$('.delButton').on('click','button',del);
        $('.addButton').click(add);
        $("select, .check, .check:checkbox, input:radio, input:file").uniform({
            selectAutoWidth: false
        });
    });

</script>
<div class="infoMessage"></div>
<div class="fluid">
    <div class="widget">
        <div class="whead">
            <div class="titleIcon"><span class="icon-document"></span></div>
            <h6>Edit Purchase Order</h6>
            <div class="clear"></div>
        </div>
        <form method="POST" action="<?php echo current_url() ?>" autocomplete=off>
            <div class="formRow">
                <div class="grid3"><label>Vendor</label></div>
                <div class="grid9">
	            	<?php
					$options = array();
					foreach ($vendors as $vendor) {
						$options[$vendor->id] = $vendor->name;
					}
					$first_key = $po->vendor_id;
					echo form_dropdown('vendor_id', $options, $first_key);
					?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid3"><label>Tanggal dibuat</label></div>
                <div class="grid9">
                    <input type="text" name="created_on" value="<?php echo date('d M Y H:i', strtotime($po->created_on)) ?>"/>	
                </div>
                <div class="clear"></div>
            </div>
            <?php 
            $i = 0;
            foreach ($po->lines as $line) : 
                $i += 1;
            ?>
            <div class="formRow poline">
                <div class="grid1"></div>
                <div class="grid5">
                    <input type="text" placeholder="Kode Barang" list="code" name="code[<?php echo $i?>]" value="<?php echo $line->code ?>"/>
                </div>
                <div class="grid1">
                    <input type="text" placeholder="Jumlah" name="quantity[<?php echo $i ?>]" value="<?php echo $line->quantity ?>"/>
                </div>
                <div class="grid3">
                    <input type="text" placeholder="Harga Satuan" name="price[<?php echo $i?>]" value="<?php echo number_format($line->price) ?>"/>
                </div>
                <div class="grid2"><button type="button" class="delButton buttonM bRed formSubmit" onClick="del(this)" >Delete</button></div>
                <div class="clear"></div>
            </div>
            <?php endforeach ?>
            <div class="formRow">
                <button type="button" class="buttonM bBlue addButton">Tambah Item</button>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid3"><label>Diskon</label></div>
                <div class="grid9">
                    <input type="text" name="discount" value="<?php echo number_format($po->discount) ?>"/>	
                </div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid3"><label>PPN (Pajak)</label></div>
                <div class="grid9">
                    <input type="text" name="tax" value="<?php echo number_format($po->tax) ?>"/>	
                </div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid3"><label>Ongkos Kirim</label></div>
                <div class="grid9">
                    <input type="text" name="postage" value="<?php echo number_format($po->postage) ?>"/>	
                </div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid3"><label>Keterangan</label></div>
                <div class="grid9">
                    <textarea name="description"><?php echo $po->description ?></textarea>
                </div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid3"><label>Down Payment (DP)</label></div>
                <div class="grid9">
                <input type=text name="payment" value="<?php echo number_format($po->payments_total) ?>" /></div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <?php echo form_submit('submit', 'Simpan', 'class="buttonM bBlue formSubmit"');?>
                <div class="clear"></div>
            </div>
            <datalist id=code>
                <?php foreach ($products as $product): ?>
                <option value="<?php echo $product->code ?>"><?php echo $product->code ?> <?php echo $product->name ?></option>
                <?php endforeach ?>
            </datalist>
        </form>
        <!--
            <?php echo form_hidden('id', $data['user']->id);?>
            <?php echo form_hidden($data['csrf']); ?>
            -->
    </div>
</div>
