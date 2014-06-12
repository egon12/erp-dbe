<div class="infoMessage"><?php echo $data['message'];?></div>
<div class="fluid">
<?php echo form_open(uri_string());?>
	<fieldset>
		<div class="widget">
			<div class="whead">
				<div class="titleIcon"><span class="icos-create"></span></div>
				<h6><?php echo lang('add_heading'); ?></h6>
				<div class="clear"></div>
			</div>
			<div class="formRow">
	            <div class="grid3"><label><?php echo lang('add_name_label');?><span class="req">*</span></label></div>
	            <div class="grid9"><?php echo form_input($data['name']);?></div>
				<div class="clear"></div>
	        </div>
			<div class="formRow">
	            <div class="grid3"><label><?php echo lang('add_address_label');?><span class="req">*</span></label></div>
	            <div class="grid9"><?php echo form_input($data['address']);?></div>
				<div class="clear"></div>
	        </div>
			<div class="formRow">
	            <div class="grid3"><label><?php echo lang('add_phone_label');?><span class="req">*</span></label></div>
	            <div class="grid9"><?php echo form_input($data['phone']);?></div>
				<div class="clear"></div>
	        </div>
			<div class="formRow">
	            <div class="grid3"><label><?php echo lang('add_email_label');?></label></div>
	            <div class="grid9"><?php echo form_input($data['email']);?></div>
				<div class="clear"></div>
	        </div>
			<div class="formRow">
	            <div class="grid3"><?php echo lang('add_description_label', 'description');?></div>
	            <div class="grid9"><?php echo form_textarea($data['description']);?></div>
				<div class="clear"></div>
	        </div>
			<div class="formRow">
					<div class="formSubmit">
						<?php echo anchor('vendors', 'Cancel', 'title="Cancel"" class="buttonM bRed mr10"'); ?>
						<?php echo form_submit('submit', lang('add_submit_btn'), 'class="buttonM bBlue"');?>
					</div>
				<div class="clear"></div>
			</div>
		</div>
	</fieldset>
<?php echo form_close();?>
