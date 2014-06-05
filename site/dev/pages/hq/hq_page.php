<? // hq_page.php
require_once "hq_security.php";

$str="select * from pages where page_id=".mysql_real_escape_string ($id);
$res=mysql_query($str);
$row=mysql_fetch_array($res);

$nothq=true;

if ($row['page_type']=='hq') {$nothq=false;} /* HQ pages don't get some parts */
?>
<script src="/pages/js/jquery.colorpicker.js" type="text/javascript"></script>

Manage Page<br>

<form name="updatepage" action="/pages/hq/hq_processupdatepage.php" method="post">
<input name="page_id" value="<?=$row['page_id']?>" type="hidden">

Title: <input name="page_title" value="<?=$row['page_title']?>" type="text" style="width:300px;">&nbsp;

Title Width Adjustment: <input name="page_titleadj" value="<?=$row['page_titleadj']?>" type="text" style="width:30px;">&nbsp;

Menu Width Adjustment: <input name="page_menuadj" value="<?=$row['page_menuadj']?>" type="text" style="width:30px;">&nbsp;

<? if ($row['page_type']=='gallery') { ?>

Page Parameter: <input name="page_param" value="<?=$row['page_param']?>" type="text" style="width:100px;"><br>

Gallery Path: <input name="page_gallerypath" value="<?=$row['page_gallerypath']?>" type="text" style="width:100px;">&nbsp;

Parent Gallery:<select name="page_parent">
<option value="" <?=isselected($row['page_parent'],'None')?>>None</option>
<option value="Portfolio" <?=isselected($row['page_parent'],'Portfolio')?>>Portfolio</option>

</select>

<? } else { ?>
<? if ($row['page_param']=='hq') { ?>
Page Parameter: <B><?=$row['page_param']?></B>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="page_param" value="<?=$row['page_param']?>" type="hidden">
<? } else { ?>
Page Parameter: <input name="page_param" value="<?=$row['page_param']?>" type="text" style="width:100px;">
<? } ?>


<br clear="all">


<!-- Bar Color: <input name="page_bgcolor" id="page_bgcolor" value="<?=$row['page_bgcolor']?>" type="text" style="width:100px;">&nbsp;&nbsp; -->
Text Color: <input name="page_color" id="page_color" value="<?=$row['page_color']?>" type="text" style="width:100px;">

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<? if ($nothq) {?>
Parent Page:<select name="page_parent">
<option value="" <?=isselected($row['page_parent'],'None')?>>None</option>
<option value="About" <?=isselected($row['page_parent'],'About')?>>About</option>
<option value="Services" <?=isselected($row['page_parent'],'Services')?>>Services</option>
<option value="Testimonials" <?=isselected($row['page_parent'],'Testimonials')?>>Testimonials</option>
</select>
<? } ?>
&nbsp;

Page Type:<select name="page_type">
<option value="content" <?=isselected($row['page_type'],'content')?>>Content</option>
<option value="gallery" <?=isselected($row['page_type'],'gallery')?>>Gallery</option>
<option value="client" <?=isselected($row['page_type'],'client')?>>Client</option>
<option value="hq" <?=isselected($row['page_type'],'hq')?>>HQ</option>
</select>

<br>

<script>
$(document).ready(function() {
	$('#page_bgcolor').css('background-color','<?=$row['page_bgcolor']?>');
	$('#page_color').css('background-color','<?=$row['page_color']?>');
	$("#page_bgcolor").addColorPicker({'colorBg':'yes', 'cursor':'pointer',});
	$("#page_color").addColorPicker({'colorBg':'yes', 'cursor':'pointer',});
});
</script>

<? if (($row['page_type']=='content') || ($row['page_type']=='client')  || ($row['page_type']=='hq')) { ?>
Use page: <input name="page_includepage" value="<?=$row['page_includepage']?>" type="text" style="width:150px;"><br>
<? } ?>

<? } ?>

Order: <input name="page_order" value="<?=$row['page_order']?>" type="text" style="width:50px;">&nbsp;

 Status: <select name="page_status">
<option value="show" <?=isselected($row['page_status'],'show')?>>show</option>
<option value="hide" <?=isselected($row['page_status'],'hide')?>>hide</option>
</select>&nbsp;<br>


<br>
Description:<br>
<textarea name="page_description" style="width:1000px;height:70px;"><?=$row['page_description']?></textarea><br>

Keywords:<br>
<textarea name="page_keywords" style="width:1000px;height:70px;"><?=$row['page_keywords']?></textarea>
<Br>




<? if ((($row['page_type']=='content') || ($row['page_type']=='client') || ($row['page_type']=='hq')) && ($row['page_includepage']=='')) { ?>
Content:&nbsp;&nbsp;&nbsp;<a href="/?page=<?=$hqpageparam?>" target="_blank">view page</a><br>
<textarea name="page_content" style="width:1000px;height:200px;"><?=$row['page_content']?></textarea><br>

Draft:&nbsp;&nbsp;&nbsp;<a href="/?page=<?=$hqpageparam?>&draft=yes" target="_blank">view draft</a><br>
<textarea name="page_draft" style="width:1000px;height:200px;"><?=$row['page_draft']?></textarea>
<Br>
<? } else { ?>
	<input type="hidden" name="page_content" value="<?=htmlspecialchars($row['page_content'])?>">
	<input type="hidden" name="page_draft" value="<?=htmlspecialchars($row['page_draft'])?>">
<? } ?>

<input type="submit" value="update page">
<? $page_param=$row['page_param'];
if (($page_param!='hq') && ($page_param!='hqlogin') && ($page_param!='hqquery') && ($page_param!='gallery')){ ?>  &nbsp;<input type="checkbox" name="delete" style="margin-left:450px;">Delete <?=$row['page_title']?>
<? } ?>
</form>

<br>

<? if ($row['page_param']=='hq') { ?>
This page sets sitewide settings for a number of parameters:<br>
<B>Title</B> is the sitewide first part of the browser title, i.e. DSC Design Works<br>
<B>Text Color</B> is the sitewide background color for non-gallery pages<br>
<B>Description</B> is the sitewide meta tag description for pages that don't have a description<br>
<B>Keywords</B> is the sitewide meta tag keywords that are included on every page on the site
<? } else if ($row['page_param']=='gallery') { ?>
This page sets settings for gallery pages:<br>
<B>Bar Color</B> and <B>Text Color</B> control the colors for the gallery pages
<B>Description</B> is the meta tag description for gallery pages that don't have a description<br>
<B>Keywords</B> is the meta tag keywords that are included on all gallery pages
<? } ?>

