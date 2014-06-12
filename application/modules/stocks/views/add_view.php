<div class="infoMessage"><?php echo $data['messages'];?></div>
<div class="fluid">
    <div class="widget">
        <div class="whead">
            <div class="titleIcon"><span class="icos-create"></span></div>
            <h6><?php echo lang('add_heading'); ?></h6>
            <div class="clear"></div>
        </div>
        <form action="" method="post" autocomplete="off">
            <div class="formRow">
                <div class="grid3"><label><?php echo lang('code_label');?><span class="req">*</span></label></div>
                <div class="grid9"><?php echo form_input($data['code']);?></div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="grid3"><label><?php echo lang('low_label');?><span class="req">*</span></label></div>
                <div class="grid9"><?php echo form_input($data['low']);?></div>
                <div class="clear"></div>
            </div>
            <div class="formRow">
                <div class="formSubmit">
                    <?php echo form_submit('submit', lang('add_submit_btn'), 'class="buttonM bBlue"');?>
                    <?php echo anchor('stocks', 'Cancel', 'title="Cancel"" class="buttonM bRed mr10"'); ?>
                </div>
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
