<? // hq_processnewprice.php

require_once '../connect_toorphoto.php';
require_once "hq_security.php";

$p_cid=$_REQUEST['p_cid'];
$p_cid=mysql_real_escape_string($p_cid);

$p_type=$_REQUEST['p_type'];
$p_type=mysql_real_escape_string($p_type);

$p_finish=$_REQUEST['p_finish'];
$p_finish=mysql_real_escape_string($p_finish);

$p_size=$_REQUEST['p_size'];
$p_size=mysql_real_escape_string($p_size);

$p_descrip=$_REQUEST['p_descrip'];
$p_descrip=mysql_real_escape_string($p_descrip);

$p_order=$_REQUEST['p_order'];
if ($p_order=='') {$p_order=0;}
$p_order=mysql_real_escape_string($p_order);

$p_price=$_REQUEST['p_price'];
$p_price=mysql_real_escape_string($p_price);

if ($p_price!='') {

$page_param=preg_replace("/[^a-zA-Z0-9]/", "", $page_title);
$page_param=strtolower($page_param);

$str="insert into prices (p_cid,p_type,p_finish,p_size,p_descrip,p_order,p_price) values ('".$p_cid."','".$p_type."','".$p_finish."','".$p_size."','".$p_descrip."','".$p_order."',".$p_price.")";
$res=mysql_query($str);

if ($res) {
	$msg=$page_title.' price was added';
} else {
	$msg="error adding price ".mysql_error();
}

} else {
	$msg='Please enter a price.';
}

$_SESSION['msg'].=$msg;

$gotopage="/2010/hq";

header("Location: ".$gotopage);
exit;

echo $msg;
?>