<div class="infoMessage"><?php echo $data['message'];?></div>

<div class="widget">
	<div class="whead">
        <h6><?php echo $title ?></h6>
		<div class="clear"></div>
	</div>
	<div class="shownpars">
        Report
        <table class='tDefault'>
        <tbody>
        <?php foreach ($reports as $row) :?>
        <tr>
        <td><?php echo $row ?></td>
        <td><?php echo anchor(site_url('reportgen/table/'.$row), 'view') ?></td>
        <td><?php echo anchor(site_url('reportgen/download/'.$row), 'download') ?></td>
        </tr>
        <?php endforeach ?>        
        </tbody>
        </table>

        Raw
        <table class='tDefault'>
        <tbody>

        <?php foreach ($raw as $row) :?>
        <tr>
        <td><?php echo $row ?></td>
        <td><?php echo anchor(site_url('reportgen/table/'.$row), 'view') ?></td>
        <td><?php echo anchor(site_url('reportgen/download/'.$row), 'download') ?></td>
        </tr>
        <?php endforeach ?>        
        </tbody>
        </table>

	</div><!-- .shownpars -->
</div>
