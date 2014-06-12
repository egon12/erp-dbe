<script type="text/javascript">
	jQuery(document).ready(function($){
		
		//===== Form elements styling =====//

		$("select, .check, .check:checkbox, input:radio, input:file").uniform({
			selectAutoWidth: false
		});
	});
</script>
<div class="infoMessage"><?php echo $data['message'];?></div>
<div class="fluid">
<?php echo form_open(uri_string());?>
	<fieldset>
		<div class="widget">
			<div class="whead">
				<div class="titleIcon"><span class="icon-pen"></span></div>
				<h6><?php echo lang('edit_user_heading'); ?></h6>
				<div class="clear"></div>
			</div>
			<div class="formRow">
                <div class="grid3"><?php echo lang('edit_user_username_label', 'username');?></div>
                <div class="grid9"><?php echo form_input($data['username']);?></div>
				<div class="clear"></div>
            </div>
			<div class="formRow">
                <div class="grid3"><?php echo lang('edit_user_fname_label', 'first_name');?></div>
                <div class="grid9"><?php echo form_input($data['first_name']);?></div>
				<div class="clear"></div>
            </div>
			<div class="formRow">
                <div class="grid3"><?php echo lang('edit_user_lname_label', 'last_name');?></div>
                <div class="grid9"><?php echo form_input($data['last_name']);?></div>
				<div class="clear"></div>
            </div>
			<div class="formRow">
                <div class="grid3"><?php echo lang('edit_user_company_label', 'company');?></div>
                <div class="grid9"><?php echo form_input($data['company']);?></div>
				<div class="clear"></div>
            </div>
			<div class="formRow">
                <div class="grid3"><?php echo lang('edit_user_phone_label', 'phone');?></div>
                <div class="grid9"><?php echo form_input($data['phone']);?></div>
				<div class="clear"></div>
            </div>
			<div class="formRow">
                <div class="grid3"><?php echo lang('edit_user_email_label', 'email');?></div>
                <div class="grid9"><?php echo form_input($data['email']);?></div>
				<div class="clear"></div>
            </div>
			<div class="formRow">
                <div class="grid3"><?php echo lang('edit_user_password_label', 'password');?></div>
                <div class="grid9">
					<?php echo form_input($data['password']);?>
					<span class="note"><?php echo lang('edit_user_password_note_label')?></span>
				</div>
				<div class="clear"></div>
            </div>
			<div class="formRow">
                <div class="grid3"><?php echo lang('edit_user_password_confirm_label', 'password_confirm');?></div>
                <div class="grid9">
					<?php echo form_input($data['password_confirm']);?>
					<span class="note"><?php echo lang('edit_user_password_note_label')?></span>
				</div>
				<div class="clear"></div>
            </div>
			<div class="formRow">
	            <div class="grid3"><?php echo lang('add_user_groups_heading', 'groups');?></div>
	            <div class="grid9">
	            	<?php
					$options = array();
					foreach ($data['groups'] as $key => $group) {
						$options[$group['id']] = $group['name'];
					}
					$first_key = $data['currentGroups'][0]->id;
					echo form_dropdown('groups', $options, $first_key);
					//echo '<pre>', print_r($data['currentGroups'][0]->id, true), '</pre>';
					?>
	            </div>
				<div class="clear"></div>
	        </div>
			<div class="formRow">
				<?php echo form_submit('submit', lang('edit_user_submit_btn'), 'class="buttonM bBlue formSubmit"');?>
				<div class="clear"></div>
			</div>
			<?php echo form_hidden('id', $data['user']->id);?>
			<?php echo form_hidden($data['csrf']); ?>
		</div>
	</fieldset>
<?php echo form_close();?>
</div>
