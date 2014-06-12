<div class="panel-body">
<svg height="100" version="1.1" xmlns="http://www.w3.org/2000/svg" style="overflow: hidden; position: relative; width: 100%;">
<?php 
// todo place this in models or library
//
$x = 0;
$y = 0;
$str = 'M0,0';

foreach ($data as $d) {
    $x += 10;
    $str .= 'L'.$x.',-'.($d->systolic/2);
}
?>


    <path style="" fill="none" stroke="#000000" d="<?php echo $str ?>" transform="translate(0,100)">
</svg>
<table class="table">
<?php foreach ($data as $d): ?>
<tr>
<?php foreach (get_object_vars($d) as $v): ?>
<td>
<?php echo $v ?>
</td>
<?php endforeach ?>
</tr>
<?php endforeach ?>
</table>
</div>
