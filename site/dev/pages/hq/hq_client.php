<? // hq_client.php
require_once "hq_security.php";

$str="select * from clients where c_id=".mysql_real_escape_string ($clientid);
$res=mysql_query($str);
$row=mysql_fetch_array($res);

$id=$row['c_id'];
$first=$row['c_first'];
$last=$row['c_last'];
$title=$row['c_title'];
$ttladj=$row['c_ttladj'];
$email=$row['c_email'];
$mobile=$row['c_mobile'];
$home=$row['c_home'];
$address=$row['c_address'];
$city=$row['c_city'];
$state=$row['c_state'];
$zip=$row['c_zip'];
$notes=$row['c_notes'];
$message=$row['c_message'];
$linkmessage=$row['c_linkmessage'];
$ordermessage=$row['c_ordermessage'];
$subject=$row['c_subject'];
$fromname=$row['c_fromname'];
$pass=$row['c_pass'];
$directory=$row['c_directory'];
$numberfree=$row['c_numberfree'];
$hide=$row['c_hide'];

	$gallerytitlelink=$row['c_title'];
	$gallerytitlelink=str_replace(" ","_",$gallerytitlelink); // use underscore instead of space - makes it cleaner

 
?>
<div style="padding:15px;overflow:auto;">
<br><a href="/2010/hq/">hq home</a> | <a href="2010/hq/hq_logout.php">logout</a><br><br>
<?
if ($_SESSION['msg']!='') {

?><span style="color:#339966;font-size:14px;font-weight:bold;"><?=$_SESSION['msg']?></span><br><br><?

$_SESSION['msg']='';
} 
?>
<b>Manage Art Gallery</b><br>


<div id="clientform" >
<form name="updateclient" action="/2010/hq/hq_processupdateclient.php" method="post">
<input name="c_id" value="<?=$id?>" type="hidden">

Title: <input name="c_title" value="<?=$title?>" type="text" style="width:220px;">&nbsp;

<? if (($first=='Sarah')&& ($last=='Toor')) { ?>
First: <?=$first?>&nbsp;<input name="c_first" value="<?=$first?>" type="hidden">
Last: <?=$last?>&nbsp;<input name="c_last" value="<?=$last?>" type="hidden">
<? } else { ?>
First: <input name="c_first" value="<?=$first?>" type="text" style="width:120px;">&nbsp;
Last: <input name="c_last" value="<?=$last?>" type="text" style="width:120px;">&nbsp;
<? } ?>

Title Width Adjustment: <input name="c_ttladj" value="<?=$ttladj?>" type="text" style="width:30px;"><br>

Email: <input name="c_email" value="<?=$email?>" type="text" style="width:180px;">&nbsp;
From name: <input name="c_fromname" value="<?=$fromname?>" type="text" style="width:180px;"><br>

<!-- Mobile Phone: <input name="c_mobile" value="<?=$mobile?>" type="text" style="width:120px;">&nbsp;
Home Phone: <input name="c_home" value="<?=$home?>" type="text" style="width:120px;"><br>

Address: <input name="c_address" value="<?=$address?>" type="text" style="width:160px;">&nbsp;
City: <input name="c_city" value="<?=$city?>" type="text" style="width:120px;">&nbsp;
State: <input name="c_state" value="<?=$state?>" type="text" style="width:30px;" maxlength="2">&nbsp;
Zip: <input name="c_zip" value="<?=$zip?>" type="text" style="width:120px;"><br> -->


<!-- Number of comp photos: <input name="c_numberfree" value="<?=$numberfree?>" type="text" style="width:30px;"><br> -->


Notes:<br><textarea name="c_notes" style="width:600px;height:80px;"><?=$notes?></textarea><br><br>

<?

$startingpath="2010/galleries/";
$fullpath = $startingpath;
$dir_handle = @opendir($fullpath) or die("Unable to open $fullpath");
// sort the files
$files=array();
while ($file = trim(readdir($dir_handle))) {
   $files[$file] = $file; 
}
sort($files);


?>

Gallery Path: <? if ($directory=='') { ?><select name="c_directory" id="c_directory"><option value="">Choose a directory</option>
<?
	foreach($files as $file) {
   if($file!='.' && $file!='..') {
		if (is_dir($fullpath.$file) && ($file!=='pics')) { 
      ?><option value="<?=$file?>"><?=$file?></option><?
		}
	}
}
?>
</select><? } else { ?><?=$directory?><input type="hidden" name="c_directory" id="c_directory" value="<?=$directory?>"><? } ?>
&nbsp;&nbsp;
<? if ($directory!='') { 
	if ($pass!='') {
	?><a href="/<?=$gallerytitlelink?>/<?=$pass?>" target="_blank">View Client Gallery</a>&nbsp;&nbsp;<? 
	} else {
	?><a href="/<?=$gallerytitlelink?>" target="_blank">View Client Gallery</a>&nbsp;&nbsp;<? 
	}
	
	} ?>

Passcode: <input name="c_pass" id="c_pass" value="<?=$pass?>" type="text" style="width:120px;">&nbsp;
<a href="" onclick="generatepasscode();return false;">generate new passcode</a>
<br>
<br>
 <!-- Client's message to family and friends:<br>

Email subject:<input name="c_subject" id="c_subject" value="<?=$subject?>" type="text" style="width:600px;"><br>
<textarea name="c_message" style="width:600px;height:80px;"><?=$message?></textarea><br><br> -->


<? if (($first=='Sarah')&& ($last=='Toor')) { ?>
Link message at the bottom of each message:<br>
<textarea name="c_linkmessage" style="width:600px;height:80px;"><?=$linkmessage?></textarea><br><br>

Message to send to clients after they have paid for their order:<br>
<textarea name="c_ordermessage" style="width:600px;height:80px;"><?=$ordermessage?></textarea><br><br> 

<? } ?>


<script type="text/javascript">
function generatepasscode() {
$('#c_pass').val(randomPassword(10));
}

function randomPassword(length)
{
  var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	var len = chars.length
  var pass = "";
  for(x=0;x<length;x++)
  {
    var i = Math.floor(Math.random() * len);
    pass += chars.charAt(i);
  }
  return pass;
}
// source: http://javascript.internet.com/passwords/random-password-generator.html
</script>


<input type="submit" value="update" style="margin-right:200px;">


&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="c_hide" <?=ischecked(1,$hide)?>>Hide&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?
 if (($first=='Sarah')&& ($last=='Toor')) { 
	 $okaytodelete=false;} else { 
	$okaytodelete=true;
// make sure client has no pending orders before deleting
$opstr="select * from order_parts left join orders on op_oid=o_id where o_cid=".mysql_real_escape_string($id);
$opres=mysql_query($opstr);
$oprow=mysql_fetch_array($opres);
if (!empty($oprow)) {$okaytodelete=false;} // can't delete if orders started for this client

// make sure no subclients have pending orders before deleting
$opstr="select * from order_parts left join orders on op_oid=o_id left join clients on o_cid=c_id where c_subclientof=".mysql_real_escape_string($id);
$opres=mysql_query($opstr);
$oprow=mysql_fetch_array($opres);
if (!empty($oprow)) {$okaytodelete=false;} // can't delete if orders started for this client
	 

if ($okaytodelete) { ?>
<input type="checkbox" name="delete">Delete Art Gallery
<? } else { ?><div style="font-size:11px;display:inline;color:#999;">Has orders so can't delete</div><? } 
}?>
</form>

</div>

<br style="clear:both;">
<br><br>
</div>