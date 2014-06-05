<? // hq_processupdatepage.php

require_once '../connect_dsc.php';
require_once "hq_security.php";

$page_param=$_REQUEST['page_param']; $page_param=mysql_real_escape_string($page_param);
$page_id=$_REQUEST['page_id']; $page_id=mysql_real_escape_string($page_id);
$page_title=$_REQUEST['page_title'];	$page_title=mysql_real_escape_string($page_title);
$page_titleadj=$_REQUEST['page_titleadj'];	$page_titleadj=mysql_real_escape_string($page_titleadj);
$page_menuadj=$_REQUEST['page_menuadj'];	$page_menuadj=mysql_real_escape_string($page_menuadj);
$page_titleimg=$_REQUEST['page_titleimg'];	$page_titleimg=mysql_real_escape_string($page_titleimg);
$page_bgcolor=$_REQUEST['page_bgcolor'];	$page_bgcolor=mysql_real_escape_string($page_bgcolor);
$page_color=$_REQUEST['page_color'];	$page_color=mysql_real_escape_string($page_color);
$page_type=$_REQUEST['page_type'];	$page_type=mysql_real_escape_string($page_type);
$page_gallerypath=$_REQUEST['page_gallerypath'];$page_gallerypath=mysql_real_escape_string($page_gallerypath);
//if ($page_gallerypath!='') $page_param=$page_gallerypath;  // assign the gallery path to the page param

$page_includepage=$_REQUEST['page_includepage'];
if ($page_includepage=='') {$hasincludepage=false;} else {$hasincludepage=true;}
$page_includepage=mysql_real_escape_string($page_includepage);
$page_content=$_REQUEST['page_content'];	$page_content=mysql_real_escape_string($page_content);
$page_draft=$_REQUEST['page_draft'];	$page_draft=mysql_real_escape_string($page_draft);
$page_status=$_REQUEST['page_status'];	$page_status=mysql_real_escape_string($page_status);
$page_parent=$_REQUEST['page_parent'];	$page_parent=mysql_real_escape_string($page_parent);
$page_order=$_REQUEST['page_order'];	$page_order=mysql_real_escape_string($page_order);
if ($page_order=='') {$page_order=1;}
$page_description=$_REQUEST['page_description'];$page_description=mysql_real_escape_string($page_description);
$page_keywords=$_REQUEST['page_keywords']; $page_keywords=mysql_real_escape_string($page_keywords);

$delete=$_REQUEST['delete'];

if ($page_title!='') {

$str="update pages set ";
$str.="page_param='".$page_param."',";
$str.="page_title='".$page_title."',";	
$str.="page_titleadj='".$page_titleadj."',";	
$str.="page_menuadj='".$page_menuadj."',";	
$str.="page_titleimg='".$page_titleimg."',";
$str.="page_bgcolor='".$page_bgcolor."',";	
$str.="page_color='".$page_color."',";	
if ($page_type!='') $str.="page_type='".$page_type."',";	
$str.="page_gallerypath='".$page_gallerypath."',";
$str.="page_includepage='".$page_includepage."',";
if ($hasincludepage==false) { // only update these if the include page is empty
	$str.="page_content='".$page_content."',";	
	$str.="page_draft='".$page_draft."',";	
}
$str.="page_status='".$page_status."',";	
$str.="page_parent='".$page_parent."',";	
$str.="page_order=".$page_order.",";	
$str.="page_description='".$page_description."',";
$str.="page_keywords='".$page_keywords."'";
$str.=" where page_id=".$page_id;

//error_log($str);

if ($delete!='') { // delete page

$str="Delete from pages where page_id=".$page_id;
$res=mysql_query($str);
$msg=$page_title.' page was deleted';

$gotopage="/?page=hq"; 

} else {
$res=mysql_query($str);

if ($res) {
	$msg=$page_title.' page was updated. ';
} else {
	$msg="error updating page ".$page_title.' '.mysql_error();
}

$gotopage="/?page=hq&id=".$page_id; 


}

} else {
	$msg="The page must have a title";

$gotopage="/?page=hq&id=".$page_id; 

}

$_SESSION['msg']=$msg;


header("Location: ".$gotopage);
exit;

//echo $msg;