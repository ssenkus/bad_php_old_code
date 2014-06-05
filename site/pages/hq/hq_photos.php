<? // hq_photos.php

$str="select * from photos";
$res=mysql_query($str);
while ($row=mysql_fetch_array($res)){
?><?=$row['photo_path']?><br><?

}