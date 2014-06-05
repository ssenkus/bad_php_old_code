<? // hq_processupdateprice.php

require_once '../connect_toorphoto.php';
require_once "hq_security.php";

$p_id=$_REQUEST['p_id'];
$p_id=mysql_real_escape_string($p_id);

$p_type=$_REQUEST['p_type'];
$p_type=mysql_real_escape_string($p_type);

$p_finish=$_REQUEST['p_finish'];
$p_finish=mysql_real_escape_string($p_finish);

$p_size=$_REQUEST['p_size'];
$p_size=mysql_real_escape_string($p_size);

$p_descrip=$_REQUEST['p_descrip'];
$p_descrip=mysql_real_escape_string($p_descrip);

$p_order=$_REQUEST['p_order'];
$p_order=mysql_real_escape_string($p_order);

$p_price=$_REQUEST['p_price'];
$p_price=mysql_real_escape_string($p_price);


$delete=$_REQUEST['delete'];

if ($delete!='') {
	$str="delete from prices where p_id=".$p_id;
	$res=mysql_query($str);
	$msg=$p_descrip.' price was deleted';
} else {

if ($p_price!='') {

$str="update prices set p_type='".$p_type."',p_finish='".$p_finish."',p_size='".$p_size."',p_descrip='".$p_descrip."',p_order='".$p_order."',p_price=".$p_price." where p_id=".$p_id;
$res=mysql_query($str);

if ($res) {
	$msg=$page_title.' price was updated';
} else {
	$msg="error updating price ".$str.' '.mysql_error();
}

} else {
	$msg='Please enter a price.';
}
}

$_SESSION['msg']=$msg;

$gotopage="/2010/hq";

header("Location: ".$gotopage);
exit;

echo $msg;