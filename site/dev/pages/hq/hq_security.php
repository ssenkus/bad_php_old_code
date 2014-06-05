<? // hq_security.php
session_start();
if ($_SESSION['security']!='ok') {
	session_destroy();
	$gotopage="/2010.php";
	header("Location: ".$gotopage);
	exit;
}