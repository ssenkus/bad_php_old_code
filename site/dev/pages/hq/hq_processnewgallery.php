<? // hq_processnewgallery.php
require_once "hq_security.php";

$path='../galleries/';
$gotopage="/?page=hq";
$newgallery=$_REQUEST['newgallery'];
if ($newgallery!='') {
	$newgallerypath=$path.$newgallery;

		if (!file_exists($newgallerypath)) {
          try {
			mkdir($newgallerypath);
			mkdir($newgallerypath.'/large');
			mkdir($newgallerypath.'/med');
			mkdir($newgallerypath.'/thumbs');
			mkdir($newgallerypath.'/data');
         } catch (MyException $e) {
                //throw $e;
					$_SESSION['msg']=$e.'  ';
       }

			$_SESSION['msg'].=$newgallery.' gallery was created';
		}
	}


header("Location: ".$gotopage);
exit;