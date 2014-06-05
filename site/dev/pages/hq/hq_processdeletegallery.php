<? // hq_processdeletegallery.php
require_once "hq_security.php";

$path='../galleries/';
$archivepath="../archivedgalleries/";
$gotopage="/?page=hq";
$deletegallery=$_REQUEST['deletegallery'];
if ($deletegallery=='') {
	$_SESSION['msg']='No gallery was chosen.  ';
} else {
	$deletegallerypath=$path.$deletegallery;
	$fullarchivepath=$archivepath.$deletegallery;

		if (file_exists($deletegallerypath)) {
          try {
			//rrmdir($deletegallerypath);
			rename($deletegallerypath,$fullarchivepath);
			$_SESSION['msg'].=$deletegallery.' gallery was deleted';
         } catch (MyException $e) {
                //throw $e;
					$_SESSION['msg']=$e.'  ';
       }

		} else {
	$_SESSION['msg']="Gallery $deletegallerypath not found.  ";

		}
	}

 function rrmdir($dir) { 
   if (is_dir($dir)) { 
     $objects = scandir($dir); 
     foreach ($objects as $object) { 
       if ($object != "." && $object != "..") { 
         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
       } 
     } 
     reset($objects); 
     rmdir($dir); 
   } 
 } 
// http://www.php.net/manual/en/function.rmdir.php#98622

header("Location: ".$gotopage);
exit;