<? // hq_query.php 
?>
<div style="margin:20px;">
<?

include "hq_menu.php"; 


session_start();

if ($_SESSION['security']=='ok') {

require_once 'connect_toorphoto.php';

$query=$_REQUEST['query'];

if ($query!="") {

if (substr($query,0,7)=='system:') { // for a system command like mysqldump start with the 'system:'
		try {
		$query=substr($query,7); // remove 'system:' from the query
		$res=system(escapeshellcmd($query),$retval);
		//$res=system('dir',$retval); 
		if ($retval) {
			?><br>Query was successful<br><?=$retval?> <br><?=$res?><br><?
		} else {
			?><br>Query was unsuccessful <br><?=$retval?> <br><?=$res?><br><?
		}
	} catch (Exception $e) {
		?>Error: <?=$e?><?
	}
} else {
	try {

	$res=mysql_query($query);
	//error_log($query);
		if ($res) {
			?>Query was successful<br>
			<table>
		<? 
				while($row = mysql_fetch_row($res))
				{
					echo "<tr>";
					foreach($row as $cell) {
						echo "<td>$cell</td>";
					}
					echo "</tr>\n";
				} 
				echo "</table>";
		
		} else {
			?>Query was unsuccessful<br>
			<?=mysql_error()?><?
		}
	} catch (Exception $e) {
		?>Error: <?=$e?><?
	} ?>
<? } ?>
<? } ?>

<form name="hqquery" method="post" action="">
<textarea name="query" style="width:700px;height:200px;"><?=$query?></textarea><br>
<input type="submit" value=">">
</form>
<? } ?>
</div>