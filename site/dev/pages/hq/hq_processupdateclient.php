<? // hq_processupdateclient.php

require_once '../connect_toorphoto.php';
require_once "hq_security.php";

$c_id=$_REQUEST['c_id'];
$c_id=mysql_real_escape_string($c_id);

$c_first=$_REQUEST['c_first'];
//$c_first=str_replace("'","\'",$c_first);
$c_first=mysql_real_escape_string($c_first);

$c_last=$_REQUEST['c_last'];
$c_last=mysql_real_escape_string($c_last);

$delete=$_REQUEST['delete'];

if ($delete!='') {
// make sure subclient has no pending orders before deleting
$okaytodelete=true;
// make sure client has no orders before deleting
$opstr="select * from order_parts where op_cid=".mysql_real_escape_string($c_id);
$opres=mysql_query($opstr);
$oprow=mysql_fetch_array($opres);
if (!empty($oprow)) {$okaytodelete=false;} // can't delete if orders started for this client

if ($okaytodelete) {
		$str="delete from clients where c_id=".mysql_real_escape_string($c_id);
		$res=mysql_query($str);
		$gotopage="/?page=hq";
			$msg=$c_first." ".$c_last.' was deleted';
	} else {
		$gotopage="/?page=hq&clientid=".$c_id;
		$msg=$c_first." ".$c_last." has orders so can't be deleted";
	}
} else {


$str="select * from clients where c_subclientof=0 and c_id<>".$c_id." and c_first='".$c_first."' and c_last='".$c_last."'";
$res=mysql_query($str);
if (mysql_num_rows($res)>0) {
	$msg="That client name is already in the system.";
	$gotopage="/?page=hqclient&clientid=".$c_id;

} else {


$c_title=$_REQUEST['c_title'];
$c_title=mysql_real_escape_string($c_title);

$c_ttladj=$_REQUEST['c_ttladj'];
if (!is_numeric($c_ttladj)) {$c_ttladj=0;}
$c_ttladj=mysql_real_escape_string($c_ttladj);

$c_email=$_REQUEST['c_email'];
$c_email=mysql_real_escape_string($c_email);

$c_mobile=$_REQUEST['c_mobile'];
$c_mobile=mysql_real_escape_string($c_mobile);

$c_home=$_REQUEST['c_home'];
$c_home=mysql_real_escape_string($c_home);

$c_address=$_REQUEST['c_address'];
$c_address=mysql_real_escape_string($c_address);

$c_city=$_REQUEST['c_city'];
$c_city=mysql_real_escape_string($c_city);

$c_state=$_REQUEST['c_state'];
$c_state=mysql_real_escape_string($c_state);

$c_zip=$_REQUEST['c_zip'];
$c_zip=mysql_real_escape_string($c_zip);

$c_notes=$_REQUEST['c_notes'];
$c_notes=mysql_real_escape_string($c_notes);

$c_message=$_REQUEST['c_message'];
$c_message=mysql_real_escape_string($c_message);

$c_linkmessage=$_REQUEST['c_linkmessage'];
$c_linkmessage=mysql_real_escape_string($c_linkmessage);

$c_ordermessage=$_REQUEST['c_ordermessage'];
$c_ordermessage=mysql_real_escape_string($c_ordermessage);


$c_fromname=$_REQUEST['c_fromname'];
if ($c_fromname=='') {$c_fromname=$_REQUEST['c_first'].' '.$_REQUEST['c_last'];}
$c_fromname=mysql_real_escape_string($c_fromname);


$c_subject=$_REQUEST['c_subject'];
$c_subject=mysql_real_escape_string($c_subject);


$c_pass=$_REQUEST['c_pass'];
$c_pass=mysql_real_escape_string($c_pass);

$c_directory=$_REQUEST['c_directory'];
$c_directory=mysql_real_escape_string($c_directory);

$c_numberfree=$_REQUEST['c_numberfree'];
$c_numberfree=mysql_real_escape_string($c_numberfree);


$c_hide=$_REQUEST['c_hide'];
if ($c_hide!='') {$c_hide=1;} else {$c_hide=0;}

if (($c_first!='') || ($c_last!='')) {

$str="update clients set c_first='".$c_first."',c_last='".$c_last."',c_title='".$c_title."',c_ttladj='".$c_ttladj."',c_email='".$c_email."',c_mobile='".$c_mobile."',c_home='".$c_home."',c_address='".$c_address."',c_city='".$c_city."',c_state='".$c_state."',c_zip='".$c_zip."',c_notes='".$c_notes."',c_message='".$c_message."',c_linkmessage='".$c_linkmessage."',c_ordermessage='".$c_ordermessage."',c_subject='".$c_subject."',c_fromname='".$c_fromname."',c_pass='".$c_pass."',c_directory='".$c_directory."',c_numberfree='".$c_numberfree."',c_hide=".$c_hide." where c_id=".$c_id;
//error_log($str);
$res=mysql_query($str);

if ($res) {
	$msg=$c_first." ".$c_last." was updated";
} else {
	$msg="error updating client ".mysql_error();
}

} else {
	$msg="Please enter a first or last name";
}


$gotopage="/?page=hqclient&clientid=".$c_id;
}
}

$_SESSION['msg']=$msg;

header("Location: ".$gotopage);
exit;

echo $msg;