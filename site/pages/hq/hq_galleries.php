<? // hq_galleries.php
require_once "hq_security.php";

//if ($path!='') {$path=$path."/";}
if  (($path!='') && (substr($path,-1)!='/')) {$path=$path."/";}

$firstslash=strpos($path,"/",1);
$rootpath=substr($path,0,$firstslash); // get the first part of the path

$startingpath="pages/galleries/";
$fullpath = $startingpath.$path;

//using the opendir function
$dir_handle = @opendir($fullpath) or die("Unable to open $fullpath");
?>
<span class="title1">Manage Gallery Photos</span><br>
<? if ($fullpath!=$startingpath) { ?>
<a href="/?page=hq">galleries</a><? 
}
if ($path!='') { 
?>/<a href="/?page=hq&path=<?=$rootpath?>"><?=$path?></a><br>
<? } ?>



<? if ($fullpath!=$startingpath) {?>
<form enctype="multipart/form-data" action="/pages/hq/hq_processupload.php" method="POST">
<!-- <input type="hidden" name="MAX_FILE_SIZE" value="30000" /> -->
<input type="hidden" name="path" value="<?=$path?>">
<input name="userfile" type="file" />
<input type="submit" value="upload" />
</form>
<? } ?>

<table>

<?
// sort the files
$files=array();
while ($file = trim(readdir($dir_handle))) {
   $files[$file] = $file; 
}
sort($files);


//running the while loop
//while ($file = trim(readdir($dir_handle))) { /* loop through the gallery folders and files and display them - recursively navivgating*/
foreach($files as $file) {
   if($file!='.' && $file!='..') {

		if (is_dir($fullpath.$file)) { 

      ?><a href="/?page=hq&path=<?=urlencode($path.$file)?>"><?=$file?></a><br><?

		} else {

			$pathinfo = pathinfo($file);
			$ext= strtolower($pathinfo['extension']);


			?>
			<tr>
				<?
				if (($ext=='jpg') || ($ext=='jpeg')  || ($ext=='gif') || ($ext=='png')) { /* show thumb if image */

				list($width, $height, $type, $attr) = getimagesize($fullpath.$file);
				$filesize=filesize($fullpath.$file)/1024;
				$filesizef=number_format($filesize,2);
				
				if (($width<=800) && ($height<=800)) {
					$oktoshow=true;
				} else {
					$oktoshow=false;
				}
					?>
					<? if ($oktoshow) { ?>


					<td><a href="<?=$fullpath.$file?>"  target="_blank"><img src="<?=$fullpath.$file?>"  alt="<?=$file?>"></a>&nbsp;</td>
					<? 	} else { ?>
					<td><a href="<?=$fullpath.$file?>" target="_blank"><?=$file?></a>&nbsp;</td>
					<? } ?>
					<td><?=$file?><br>
						<?=$filesizef?> kb<br>
						<?=$width?> x <?=$height?>
						</td>
						<td>
						<a href="" onclick="confirmdelete('<?=$file?>','<?=$path?>','<?=$fullpath?>','<?=$fullpath.$file?>');return false;">delete</a>
						</td>
				</tr><?
		}


	 }

}

}
?>

</table>

<script>
function confirmdelete(file,path,fullpath,fullpathandfile) {
var confirmdelete=confirm('Okay to delete ' + file);
if (confirmdelete) {
	//$('deletefilefullpath').value=fullpath;
	//$('deletefileform').submit();
	document.forms.deletefileform.file.value=file;
	document.forms.deletefileform.path.value=path;
	document.forms.deletefileform.fullpath.value=fullpath;
	document.forms.deletefileform.fullpathandfile.value=fullpathandfile;
	document.forms.deletefileform.submit();
	//alert('okay to delete' + fullpath);
} 
}
</script>

<form name="deletefileform" id="deletefileform"  action="/pages/hq/hq_processdeletefile.php" method="post">
<input type="hidden" name="file" id="deletefile" value="">
<input type="hidden" name="path" id="deletefilepath" value="">
<input type="hidden" name="fullpath" id="deletefilefullpath" value="">
<input type="hidden" name="fullpathandfile" id="deletfullpathandfile" value="">
</form>

<? if ($fullpath==$startingpath) { /* create new galleries from the galleries home page*/?>

<div id="newgallerydivlink"><a href="" onclick="$('#newgallerydivlink').hide();$('#newgallerydiv').fadeIn(700);return false;">add new gallery</a></div>
<div id="newgallerydiv" style="display:none;">
<form name="newgalleryform" method="post" action="/pages/hq/hq_processnewgallery.php">
<input type="text" name="newgallery" style="width:150px;"><br><input type="submit" value="add new gallery"><a href="" onclick="$('#newgallerydivlink').fadeIn(700);$('#newgallerydiv').hide();return false;">cancel</a>
</form>
</div>

<br>

<div id="deletegallerydivlink"><a href="" onclick="$('#deletegallerydivlink').hide();$('#deletegallerydiv').fadeIn(700);return false;">delete a gallery</a></div>
<div id="deletegallerydiv" style="display:none;">
<form name="deletegalleryform" method="post" action="/pages/hq/hq_processdeletegallery.php">
<select name="deletegallery" id="deletegallery"><option value="">Choose a gallery to delete</option>
<?
	foreach($files as $file) {
   if($file!='.' && $file!='..') {
		if (is_dir($fullpath.$file) && ($file!=='pics')) { 
      ?><option value="<?=$file?>"><?=$file?></option><?
		}
	}
}
?>
</select><br><input type="submit" value="delete gallery"><a href="" onclick="$('#deletegallerydivlink').fadeIn(700);$('#deletegallerydiv').hide();return false;">cancel</a><br>
<!-- &nbsp;&nbsp;<i>Be careful!! Cannot be undone.</i> -->
</form>
</div>



<? } ?>
<br><br>
<?
$freespace=disk_free_space("/");
	$freespaceGB=$freespace/1024/1024/1024;
?>Disk Free Space: <?=number_format($freespaceGB,2)?>GB