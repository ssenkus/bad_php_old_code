<? // hq.php 

require_once "hq/hq_security.php";
$path=$_GET['path'];

?>
<div style="padding:15px;overflow:auto;width:1300px;">
<?

include "hq/hq_menu.php";

if ($_SESSION['msg']!='') {

?><span style="color:#339966;font-size:14px;font-weight:bold;"><?=$_SESSION['msg']?></span><br><br><?

$_SESSION['msg']='';
} 


if ($id!='') {
	include 'hq/hq_page.php';

} else {
?>
<div style="float:left;margin-left:30px;">
<?
include 'hq/hq_galleries.php';
?>
</div>
<?
if ($path=='') {
?>
<div style="float:left;padding-left:15px;margin-left:30px;">
<?
	include 'hq/hq_pages.php';
?>
</div>

<div style="float:left;padding-left:15px;">
<?
	//include 'hq/hq_clients.php';
?>
</div>
<?
}

}

//include 'hq/hq_pages.php';

?>
</div>