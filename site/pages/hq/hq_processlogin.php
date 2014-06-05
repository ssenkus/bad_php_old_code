<? // hq_processlogin.php
session_start();

$pw=$_REQUEST['pw'];
if ($pw=='csd') {
	$_SESSION['security']='ok';
	$_SESSION['msg']='Welcome to HQ';
	$gotopage="/hq";
} else {
	$_SESSION['msg']='Sorry. Try again.';
	$gotopage="/hqlogin";
}

header("Location: ".$gotopage);
exit;