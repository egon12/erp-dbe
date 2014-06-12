<div class="infoMessage"><?php echo $messages; ?></div>
<div class="widget">
    <div class="whead">
        <div class="titleIcon"><span class="icon-drawer-2"></span></div>
        <h6><?php echo $title ?></h6>
        <div class="clear"></div>
    </div>
    <div class="fluid">
        <form method=POST action="" autocomplete="off">
            <div class="formRow">
                <div class="grid3"><label for="icode"><?php echo lang('code_label')?></label></div>
                <div class="grid9">
                    <input id="icode" type="text" name="code" placeholder="<?php echo lang('code_label') ?>" value="<?php echo $code ?>" list="code" required="required" />
                </div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid3"><label for="iquantity"><?php echo lang('quantity_label') ?></label></div>
                <div class="grid9">
                    <input id="iquantity" type="text" name="quantity" placeholder="<?php echo lang('quantity_label')?>"  required="required"/>
                </div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid3"><label for="idescription"><?php echo lang('description_label') ?></label></div>
                <div class="grid9">
                    <textarea id="idescription" name="description" placeholder="<?php echo lang('description_label') ?>" required="required"></textarea>
                </div>
                <div class="clear"></div>
            </div>
            <div class="formRow formSubmit">
                <input type="submit" class="buttonM bBlue" name=submit value="<?php echo $button_name?>" />
                <a href="<?php echo site_url('stocks') ?>" class="buttonM bRed" >Cancel</a>
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
<script type="text/javascript">
$(function () {
    $("select, .check, .check:checkbox, input:radio, input:file").uniform({
        selectAutoWidth: false
    });
});
</script>
