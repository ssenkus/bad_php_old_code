<? // hq_processupdateordernotes_order.php

require_once '../connect_yumilife.php';
require_once "hq_security.php";

$o_id=$_REQUEST['o_id'];
$o_id=mysql_real_escape_string($o_id);

$notes=$_REQUEST['notes'];
$notes=mysql_real_escape_string($notes);

$clientnotes=$_REQUEST['clientnotes'];
$clientnotes=mysql_real_escape_string($clientnotes);

$ordername=$_REQUEST['ordername'];

	$gotopage="/?page=hqorder&orderid=".$o_id;

$delete=$_REQUEST['delete'];


if ($delete!='') {

$str="delete from orders where o_id=".$o_id;
$res=mysql_query($str);
if ($res) {
	$msg=$ordername.' was deleted';
	 $gotopage="/hq";

} else {
	$msg="error updating notes ".$str.' '.mysql_error();
}

} else {

$str="update orders set o_notes='".$notes."',o_clientnotes='".$clientnotes."' where o_id=".$o_id;
$res=mysql_query($str);

if ($res) {
	$msg='Notes were updated';
} else {
	$msg="error updating notes ".$str.' '.mysql_error();
}

}



$_SESSION['msg']=$msg;


header("Location: ".$gotopage);
exit;

echo $msg;