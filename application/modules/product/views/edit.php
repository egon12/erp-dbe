<div class="infoMessage"><?php echo $data['message'];?></div>
<div class="fluid">
<?php echo form_open(uri_string());?>
	<fieldset>
		<div class="widget">
			<div class="whead">
				<div class="titleIcon"><span class="icos-pencil"></span></div>
				<h6><?php echo lang('edit_heading'); ?></h6>
				<div class="clear"></div>
			</div>
			<div class="formRow">
	            <div class="grid3"><label><?php echo lang('edit_code_label');?><span class="req">*</span></label></div>
	            <div class="grid9"><?php echo form_input($data['code']);?></div>
				<div class="clear"></div>
	        </div>
			<div class="formRow">
	            <div class="grid3"><label><?php echo lang('edit_name_label');?><span class="req">*</span></label></div>
	            <div class="grid9"><?php echo form_input($data['name']);?></div>
				<div class="clear"></div>
	        </div>
			<div class="formRow">
	            <div class="grid3"><label><?php echo lang('edit_price_label');?><span class="req">*</span></label></div>
	            <div class="grid9"><?php echo form_input($data['price']);?><span class="note">Separate with comma (,)</span></div>
				<div class="clear"></div>
	        </div>
			<div class="formRow">
	            <div class="grid3"><?php echo lang('edit_description_label', 'description');?></div>
	            <div class="grid9"><?php echo form_textarea($data['description']);?></div>
				<div class="clear"></div>
	        </div>
			<div class="formRow">
				<div class="formSubmit">
					<?php echo anchor('product', 'Cancel', 'title="Cancel"" class="buttonM bRed mr10"'); ?>
					<?php echo form_submit('submit', lang('edit_submit_btn'), 'class="buttonM bBlue"');?>
				</div>
				<div class="clear"></div>
			</div>
			<?php echo form_hidden('id', $data['product']->id);?>
			<?php echo form_hidden($data['csrf']); ?>
		</div>
	</fieldset>
<?php echo form_close();?>
</div>
