<? // hq_processupdateshippeddate_order.php

require_once '../connect_yumilife.php';
require_once "hq_security.php";

$o_id=$_REQUEST['o_id'];
$o_id=mysql_real_escape_string($o_id);

$dateshipped=$_REQUEST['dateshipped'];
$dateshipped=mysql_real_escape_string($dateshipped);


if ($dateshipped!='') {
//$dateshippedformatted=date("Y-m-d H:i:s",strtotime($dateshipped));
$dateshippedformatted=$dateshipped;

$str="update orders set o_dateshipped='".$dateshippedformatted."' where o_id=".$o_id;
$res=mysql_query($str);

if ($res) {
	$msg=$page_title.' date was added';
} else {
	$msg="error adding date ".$str.' '.mysql_error();
}

} else {
	$msg='Please enter a shipping date.';
}


$_SESSION['msg']=$msg;

$gotopage="/?page=hqorder&orderid=".$o_id;

header("Location: ".$gotopage);
exit;

echo $msg;