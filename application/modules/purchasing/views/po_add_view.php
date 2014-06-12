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
        $('.delButton').prop('disabled', true);
        //$('.delButton').on('click','button',del);
        $('.addButton').click(add);
        $("select, .check, .check:checkbox, input:radio, input:file").uniform({
            selectAutoWidth: false
        });
    });

</script>
<div class="infoMessage">    
    <?php foreach ($proposal as $row): ?>
    <div class="nNote nWarning">
        <p>Tanggal <?php echo date ('d M Y', strtotime ($row->detected_at)) ?> 
            jam <?php echo date ('H:i:s', strtotime($row->detected_at)) ?>
            Sistem mengusulkan memesan <strong><?php echo $row->name ?> ( <?php echo $row->code?>) </strong>
            minimal sejumlah <?php echo number_format($row->quantity) ?>.
            <a href="<?php echo site_url('purchasing/del_proposal/'.$row->id) ?>" class="buttonM bRed">Hapus</a></p>
    </div>
    <?php endforeach  ?>
</div>
<div class="fluid">
    <div class="widget">
        <div class="whead">
            <div class="titleIcon"><span class="icon-document"></span></div>
            <h6>Buat Purchase Order</h6>
            <div class="clear"></div>
        </div>
        <form method="POST" action="<?php echo current_url() ?>" autocomplete=off>
            <div class="formRow">
                <div class="grid3"><label>Vendor</label></div>
                <div class="grid9">
                    <select name="vendor_id">
                        <?php foreach ($vendors as $vendor) :?>
                        <option value="<?php echo $vendor->id ?>"><?php echo $vendor->name ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid3"><label>Tanggal dibuat</label></div>
                <div class="grid9">
                    <input type="text" name="created_on" value="<?php echo date('d M Y H:i')?>"/>	
                </div>
                <div class="clear"></div>
            </div>
            <div class="formRow poline">
                <div class="grid1"></div>
                <div class="grid5">
                    <input type="text" placeholder="Kode Barang" list="code" name=code[1] required=required />
                </div>
                <div class="grid1">
                    <input type="text" placeholder="Jumlah" name="quantity[1]" required=required />
                </div>
                <div class="grid3">
                    <input type="text" placeholder="Harga Satuan" name=price[1] required=required />
                </div>
                <div class="grid2"><button type="button" class="delButton buttonM bRed formSubmit" onClick="del(this)" >Delete</button> </div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <button type="button" class="buttonM bBlue addButton">Tambah Item</button>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid3"><label>Diskon</label></div>
                <div class="grid9">
                    <input type="text" name="discount" placeholder="Diskon" />
                </div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid3"><label>PPN (Pajak)</label></div>
                <div class="grid9">
                    <input type="text" name="tax" placeholder="PPN (Pajak)"  />
                </div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid3"><label>Ongkos Kirim</label></div>
                <div class="grid9">
                    <input type="text" name="postage"  placeholder="Ongkos Kirim"  />
                </div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid3"><label>Keterangan</label></div>
                <div class="grid9">
                    <textarea name="description" placeholder="Keterangan"></textarea>
                </div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid3"><label>Down Payment (DP)</label></div>
                <div class="grid9">
                    <input type=text name="payment" placeholder="Down Payment (DP)" /></div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <input type=submit class="buttonM bBlue formSubmit" value="Simpan" />
                <div class="clear"></div>
            </div>
            <datalist id=code>
                <?php foreach ($products as $product): ?>
                <option value="<?php echo $product->code ?>"><?php echo $product->code ?> <?php echo $product->name ?></option>
                <?php endforeach ?>
            </datalist>
        </form>
    </div>
</div>
