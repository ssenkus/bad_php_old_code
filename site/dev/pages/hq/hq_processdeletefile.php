<?// hq_processdeletefile.php
require_once "hq_security.php";

$file=$_REQUEST['file'];
$path=$_REQUEST['path'];
$fullpath=$_REQUEST['fullpath'];
//$fullpathandfile=$_REQUEST['fullpathandfile'];

$gotopage="/?page=hq&path=".$path;

//if (substr($fullpathandfile,0,14)=='2010/galleries') { /* safety feature -can only delete files below this level */ 
$fullpathandfile='../galleries/'.$path.$file;

//echo $path will be deleted

if (unlink($fullpathandfile)) {
	$_SESSION['msg']=$file.' was deleted.';
} else {
	$_SESSION['msg']='Error occurred while deleting '.$fullpathandfile;
}

//} 


header("Location: ".$gotopage);
exit;
