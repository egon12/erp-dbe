<script type="text/javascript">
	jQuery(document).ready(function($){
		
		//===== Form elements styling =====//

		$("select, .check, .check:checkbox, input:radio, input:file").uniform({
			selectAutoWidth: false
		});
	});
</script>
<div class="fluid">
	
<?php
	$save_status = '';
	// flashdata or message
	if(isset($messages) && $messages) {
		foreach ($messages as $key => $message) {
			if ($message){
				$save_status = $key;
				if($key == 'success') echo '<div class="nNote nSuccess"><p>'.$message.'</p></div>';
			}
		}
	}
?>

<?php echo validation_errors('<div class="nNote nFailure"><p>','</p></div>'); ?>
<?php echo form_open('user/add') ?>
	<fieldset>
        <div class="widget">
            <div class="whead">
				<div class="titleIcon"><span class="icon-plus-3"></span></div>
				<h6>Add User</h6>
				<div class="clear"></div>
			</div>
            <div class="formRow">
                <div class="grid3"><label>Username:<span class="req">*</span></label></div>
                <div class="grid9"><input type="text" class="validate[required]" name="username" value="<?php if($save_status != 'success') echo set_value('username'); ?>" /></div>
				<div class="clear"></div>
            </div>
			<div class="formRow">
                <div class="grid3"><label>Name:<span class="req">*</span></label></div>
                <div class="grid9"><input type="text" class="validate[required]" name="name" value="<?php if($save_status != 'success') echo set_value('name'); ?>" /></div>
				<div class="clear"></div>
            </div>
			<div class="formRow">
                <div class="grid3"><label>Password:<span class="req">*</span></label></div>
                <div class="grid9"><input type="password" class="validate[required]" name="password" /></div>
				<div class="clear"></div>
            </div>
			<div class="formRow">
                <div class="grid3"><label>Email:<span class="req">*</span></label></div>
                <div class="grid9"><input type="email" class="validate[required]" name="email" value="<?php if($save_status != 'success') echo set_value('email'); ?>" /></div>
				<div class="clear"></div>
            </div>
			<div class="formRow">
                <div class="grid3"><label>Role:</label></div>
                <div class="grid9">
                	<?php
					$options = array();
					foreach ($roles as $role) {
						$options[$role->id] = ucfirst($role->role);
					}
					$first_key = key($options);
					echo form_dropdown('role', $options, $first_key);
					?>
                </div>
				<div class="clear"></div>
            </div>
			<div class="formRow">
				<input type="submit" value="Submit" class="buttonM bBlue formSubmit">
				<div class="clear"></div>
			</div>
	</fieldset>
<?php echo form_close(); ?>
</div>