<?  // hq_menu.php 
require_once "hq_security.php";
?>
<a href="/pages/hq/">hq home</a> | 
<a href="pages/hq/hq_logout.php">logout</a>
<? if ($id!='') { 
	if ($editingpagetype!='gallery') {
	?>
	| <a href="/<?=$hqpageparam?>" target="_blank">view page</a>
	<? } else { ?>
	| <a href="/<?=urlencode($hqpageparam)?>" target="_blank">view page</a>
	<? } 
 } ?>
<br><br>
