<div class="widget">
	<div class="whead">
		<div class="titleIcon"><span class="icos-admin2"></span></div>
        <h6>Sejarah Konsumen</h6>
		<div class="clear"></div>
	</div>
    <div>
        <?php foreach ($data as $row) : ?>
            <?php echo implode(get_object_vars($row), ',')?><br />

        <?php endforeach ?>
    </div>
</div>
