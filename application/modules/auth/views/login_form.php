<!-- Login wrapper -->
<div class="loginWrapper" style="opacity: 0;">
	
	<!-- User form -->
    <?php echo form_open("auth/login"); ?>
        <div class="loginPic">
            <img src="<?php echo asset_url('img/userLogin2.png'); ?>" alt="" />
        </div>
		
		<div class="pt5">
	        <!-- Messages section (error, warning, success) -->
			<?php if($data['message']) { ?>
				<?php echo $data['message']; ?>	
			<?php } ?>
		</div>
		
		<?php echo form_input($data['identity']); ?>
		<?php echo form_input($data['password']); ?>
		<?php echo form_input($data['referrer']); ?>
        
        <div class="logControl clearfix">
            <div class="memory">
				<?php echo form_checkbox('remember', '1', FALSE, 'class="check" id="remember"');?>
				<?php echo lang('login_remember_label', 'remember');?>
			</div>
			<?php echo form_submit('submit', lang('login_submit_btn'), 'class = "buttonM bBlue"');?>
        </div>
    <?php echo form_close(); ?>	
</div>
