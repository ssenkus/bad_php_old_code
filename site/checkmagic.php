<? // checkmagic.php
?>get_magic_quotes_gpc:<?=get_magic_quotes_gpc()?><?

$value=$_REQUEST['value'];
?>
<br><br>
value=<?=$value?><br><br>

<?
require_once 'pages/connect_dsc.php';

$newvalue=mysql_real_escape_string($value);
?>
newvalue=<?=$newvalue?>
