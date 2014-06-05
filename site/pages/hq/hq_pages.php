<? // hq_pages.php

$edithq=$_REQUEST['edithq']; // &edithq=yes to edit the HQ pages
?>
<span class="title1">Manage Pages</span><br><br>

<B>Content Pages</B><br>
<? 
$str="select * from pages where page_type='content' order by page_order,page_title";
$res=mysql_query($str);
while ($row=mysql_fetch_array($res)){
?>
<?=$row['page_title']?> (<?=$row['page_param']?>)&nbsp;&nbsp;<a href="/?page=hq&id=<?=$row['page_id']?>">edit</a>&nbsp;&nbsp;&nbsp;<a href="/<?=$row['page_param']?>" target="_blank">view</a><br>
<? } ?>

<form name="newpage" action="/pages/hq/hq_processnewpage.php" method="post">
<input type="hidden" name="page_type" value="content">
New content page title:<Br>
<input type="text" name="page_title"><input type="submit" value="create new page">
</form>

<br>
<B>Gallery Pages</B><br>
<? 
$str="select * from pages where page_type='gallery' order by page_title,page_order,page_title";
$res=mysql_query($str);
while ($row=mysql_fetch_array($res)){
?>
<?=$row['page_title']?> (<?=$row['page_param']?>)&nbsp;&nbsp;<a href="/?page=hq&id=<?=$row['page_id']?>">edit</a>&nbsp;&nbsp;&nbsp;<a href="/<?=$row['page_param']?>" target="_blank">view</a><br>
<? } ?>

<form name="newpage" action="/pages/hq/hq_processnewpage.php" method="post">
<input type="hidden" name="page_type" value="gallery">
<input type="hidden" name="page_param" value="gallery">
New gallery page title:<br>
<input type="text" name="page_title"><input type="submit" value="create new page">
</form>


<br>
<B>HQ Pages</B>&nbsp;&nbsp;<a href="/?page=hq&edithq=yes" style="color:white;">edit HQ pages</a><br>
<? 
$str="select * from pages where page_type='hq' order by page_title";
$res=mysql_query($str);
while ($row=mysql_fetch_array($res)){
?>
<?=$row['page_title']?> (<?=$row['page_param']?>)&nbsp;&nbsp;

<a href="/?page=hq&id=<?=$row['page_id']?>">edit</a>&nbsp;&nbsp;&nbsp;
<a href="/?page=<?=$row['page_param']?>">view</a><br>
<? } ?>

<? if ($edithq=='yes') { ?>
<form name="newpage" action="/pages/hq/hq_processnewpage.php" method="post">
<input type="hidden" name="page_type" value="hq">
New HQ page title:<Br>
<input type="text" name="page_title"><input type="submit" value="create new page">
</form>
<? } ?>
