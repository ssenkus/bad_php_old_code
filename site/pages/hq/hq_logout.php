<? // hqlogout.php
session_start();
$_SESSION = array();
session_destroy();
	$gotopage="/";
	header("Location: ".$gotopage);
	exit;