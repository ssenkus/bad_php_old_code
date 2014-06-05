<? // hq_processupload.php

require_once "hq_security.php";

$path=$_REQUEST['path'];

if  (($path!='') && (substr($path,-1)!='/')) {$path=$path."/";}

$startingpath="../galleries/";

$fullpath = $startingpath.$path;

$gotopage="/?page=hq&path=".$path;

$uploadfile = $fullpath . basename($_FILES['userfile']['name']);

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
	$_SESSION['msg']=$_FILES['userfile']['name'].' was uploaded.';
} else {
  	$_SESSION['msg']='An error occurred during the upload process.';
 
}

//$_FILES

header("Location: ".$gotopage);
exit;