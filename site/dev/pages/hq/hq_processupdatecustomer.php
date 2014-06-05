<? // hq_processupdatecustomer.php

require_once '../connect_toorphoto.php';
require_once "hq_security.php";

$custid=$_REQUEST['custid'];$custid=mysql_real_escape_string($custid);
$email=$_REQUEST['email'];$email=mysql_real_escape_string($email);
$first=$_REQUEST['first'];$first=mysql_real_escape_string($first);
$last=$_REQUEST['last'];$last=mysql_real_escape_string($last);
$address=$_REQUEST['address'];$address=mysql_real_escape_string($address);
$city=$_REQUEST['city'];$city=mysql_real_escape_string($city);
$state=$_REQUEST['state'];$state=mysql_real_escape_string($state);
$zip=$_REQUEST['zip'];$zip=mysql_real_escape_string($zip);
$phone=$_REQUEST['phone'];$phone=mysql_real_escape_string($phone);
$notes=$_REQUEST['notes'];$notes=mysql_real_escape_string($notes);
$privatenotes=$_REQUEST['privatenotes'];$privatenotes=mysql_real_escape_string($privatenotes);


$delete=$_REQUEST['delete'];

$gotopage="/?page=hqcustomer&custid=".$custid;


if ($delete!='') {

	$str="select * from orders where o_custid='$custid'";
	$res=mysql_query($str);
	if (mysql_num_rows($res)==0) {

		$str="delete from customers where cust_id='$custid'";
		$res=mysql_query($str);
		$msg="Customer was deleted";
		$gotopage="/hq";

	} else {
			$msg="Customer has orders so can't be deleted until orders are deleted.";
	}

} else {

if ($email=='') {
	$msg="Please enter at least an email.";
} else {

	$str="update customers set cust_email='$email',cust_first='$first',cust_last='$last',cust_address='$address',cust_city='$city',cust_state='$state',cust_zip='$zip',cust_phone='$phone',cust_notes='$notes',cust_privatenotes='$privatenotes' where cust_id='$custid'";
	$res=mysql_query($str);
	if ($res) {
			$msg="Information was saved.";
	} else {
		$msg="Error saving information. Please try again.";
		error_log($str);
	}
}
}

$_SESSION['msg']=$msg;


header("Location: ".$gotopage);
exit;

echo $msg;