<script type="text/javascript">
	jQuery(document).ready(function($){
		var temp = {};
		$(".datepicker").datepicker({ 
			defaultDate: +7,
			showOtherMonths:true,
			autoSize: true,
			appendText: '(dd-mm-yyyy)',
			dateFormat: 'dd-mm-yy'
		});
		
		$( ".datepicker" ).datepicker();
		
		var now = new Date();
		$('input#age').on('change', function(){
			var age_now = $('input#age').val();
			if (age_now != '') {
				temp['year'] = now.getFullYear() - age_now;
				temp['month'] = (now.getMonth() < 10) ? '0'+now.getMonth() : now.getMonth();
				temp['day'] = (now.getDay() < 10) ? '0'+now.getDay() : now.getDay();
				$('input#birth_date').val(temp['day']+'-'+temp['month']+'-'+temp['year']);
			}
		})
		
	});
</script>
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
	            <div class="grid3"><label><?php echo lang('edit_name_label');?><span class="req">*</span></label></div>
	            <div class="grid9"><?php echo form_input($data['name']);?></div>
				<div class="clear"></div>
	        </div>
			<div class="formRow">
	            <div class="grid3"><?php echo lang('edit_address_label', 'address');?></div>
	            <div class="grid9"><?php echo form_input($data['address']);?></div>
				<div class="clear"></div>
	        </div>
			<div class="formRow">
	            <div class="grid3"><label><?php echo lang('edit_phone_label');?><span class="req">*</span></label></div>
	            <div class="grid9"><?php echo form_input($data['phone']);?></div>
				<div class="clear"></div>
	        </div>
			<div class="formRow">
	            <div class="grid3"><?php echo lang('edit_birth_place_label', 'birth_place');?></div>
	            <div class="grid9"><?php echo form_input($data['birth_place']);?></div>
				<div class="clear"></div>
	        </div>
			<div class="formRow">
	            <div class="grid3"><?php echo lang('edit_age_label', 'age');?></div>
	            <div class="grid9"><?php echo form_input($data['age']);?></div>
				<div class="clear"></div>
	        </div>
			<div class="formRow">
	            <div class="grid3"><label><?php echo lang('edit_birth_date_label');?><span class="req">*</span></label></div>
	            <div class="grid9"><?php echo form_input($data['birth_date']);?></div>
				<div class="clear"></div>
	        </div>
			<div class="formRow">
	            <div class="grid3"><?php echo lang('edit_description_label', 'description');?></div>
	            <div class="grid9"><?php echo form_textarea($data['description']);?></div>
				<div class="clear"></div>
	        </div>
			<div class="formRow">
				<div class="formSubmit">
					<?php echo form_submit('submit', lang('edit_submit_btn'), 'class="buttonM bBlue mr10"');?>
					<?php echo anchor('customer', 'Cancel', 'title="Cancel"" class="buttonM bRed"'); ?>
				</div>
				<div class="clear"></div>
			</div>
			<?php echo form_hidden('id', $data['customer']->id);?>
			<?php echo form_hidden($data['csrf']); ?>
		</div>
	</fieldset>
<?php echo form_close();?>
</div>
