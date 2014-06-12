<!-- Start of Style -->
<?php 
foreach($css_files as $file):
    echo '<link type="text/css" rel="stylesheet" href="' . $file . '" />';
endforeach; 
?>
<!-- End of style -->
<?php echo $output ?>

<!-- Start of Script -->
<?php
foreach($js_files as $file):
    echo '<script src="' . $file . '"></script>';
endforeach; 
?>
<!-- End of Script -->
