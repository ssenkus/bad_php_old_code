<? // hq_processnewpage.php

require_once '../connect_dsc.php';
require_once "hq_security.php";

$page_title=$_REQUEST['page_title'];
$page_title=mysql_real_escape_string($page_title);

$page_type=$_REQUEST['page_type'];
$page_type=mysql_real_escape_string($page_type);

$page_parent=$_REQUEST['page_parent'];
$page_parent=mysql_real_escape_string($page_parent);


if ($page_title!='') {

$page_param=preg_replace("/[^a-zA-Z0-9]/", "", $page_title);
$page_param=strtolower($page_param);

$str="insert into pages (page_title,page_param,page_type,page_parent) values ('".$page_title."','".$page_param."','".$page_type."','".$page_parent."')";
$res=mysql_query($str);

if ($res) {
	$msg=$page_title.' page was created';
} else {
	$msg="error creating page ".$page_title;
}

}

$_SESSION['msg'].=$msg;

$gotopage="/pages/hq";

header("Location: ".$gotopage);
exit;

echo $msg;