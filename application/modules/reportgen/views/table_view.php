<div class="infoMessage"><?php echo $data['message'];?></div>

<div class="widget">
	<div class="whead">
        <h6><?php echo $title ?></h6>
		<div class="clear"></div>
	</div>
	<div class="shownpars">
        <?php echo $htmlTable ?>
	</div><!-- .shownpars -->
</div>

<script type="text/javascript">
$(function() {
    <?php echo $javascript; ?>
});
</script>
