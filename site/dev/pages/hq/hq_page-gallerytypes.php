<? // hq_page.php

$nothq=true;

if (strtoupper(substr($page,0,2))=='HQ') {$nothq=false;} /* HQ pages only get top part */
 
?>
Manage Page<br>
<? 
$str="select * from pages where page_id=".mysql_real_escape_string ($id);
$res=mysql_query($str);
$row=mysql_fetch_array($res);
?>
<form name="updatepage" action="/2010/hq/hq_processupdatepage.php" method="post">
<input name="page_id" value="<?=$row['page_id']?>" type="hidden">

Title: <input name="page_title" value="<?=$row['page_title']?>" type="text" style="width:300px;">&nbsp;
Page Parameter: <input name="page_param" value="<?=$row['page_param']?>" type="text" style="width:100px;"><br>

<? if ($row['page_type']!='gallery') {?>
Bar Color: <input name="page_bgcolor" value="<?=$row['page_bgcolor']?>" type="text" style="width:100px;">&nbsp;&nbsp;
Text Color: <input name="page_color" value="<?=$row['page_color']?>" type="text" style="width:100px;"><br>
<? } ?>

<? if ($row['page_type']=='content') { ?>
Use page: <input name="page_includepage" value="<?=$row['page_includepage']?>" type="text" style="width:150px;"><br>
<? } ?>

<? if ($nothq) {?>

Order: <input name="page_order" value="<?=$row['page_order']?>" type="text" style="width:50px;">&nbsp;

Status: <select name="page_status">
<option value="show">show</option>
<option value="hide">hide</option>
</select>&nbsp;

<br>
Description:<br>
<textarea name="page_description" style="width:800px;height:50px;"><?=$row['page_content']?></textarea><br>

Keywords:<br>
<textarea name="page_keywords" style="width:800px;height:50px;"><?=$row['page_draft']?></textarea>
<Br>

<? if ($row['page_type']=='gallery') {?>
Gallery Path: <input name="page_gallerypath" value="<?=$row['page_gallerypath']?>" type="text" style="width:300px;"><br>
<? } ?>

<? if (($row['page_type']=='content') && ($row['page_includepage']=='')) { ?>
Content:<br>
<textarea name="page_content" style="width:800px;height:200px;"><?=$row['page_content']?></textarea><br>

Draft:<br>
<textarea name="page_draft" style="width:800px;height:200px;"><?=$row['page_draft']?></textarea>
<Br>
<? } ?>

<? } ?>

<input type="submit" value="update page">
</form>

<br><br>