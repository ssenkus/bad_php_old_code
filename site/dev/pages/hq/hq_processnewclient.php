<? // hq_processnewclient.php

require_once '../connect_toorphoto.php';
require_once "hq_security.php";

$c_title=$_REQUEST['c_title'];
$c_title=mysql_real_escape_string($c_title);



$str="select * from clients where c_subclientof=0 and c_title='".$c_title."'";
$res=mysql_query($str);
if (mysql_num_rows($res)>0) {
	$msg="That gallery title is already in the system.";
} else if ($c_title!='') {

//$newpasscode=randomPassword(10);
$newpasscode='';

$str="insert into clients (c_title,c_subclientof,c_pass) values ('".$c_title."',0,'".$newpasscode."')";
$res=mysql_query($str);

if ($res) {
	$msg=$page_title.' gallery was added';
} else {
	$msg="error adding gallery ".mysql_error();
}

} else {
	$msg="Please enter a title";
}

$_SESSION['msg'].=$msg;

$gotopage="/2010/hq";

header("Location: ".$gotopage);
exit;

//echo $msg;


function randomPassword($length)
{
  $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	$len = strlen($chars);
  $pass = "";
  for($x=0;$x<$length;$x++)
  {
    $i =rand(0, $len-1);
    $pass.=substr($chars,$i,1);
  }
  return $pass;
}