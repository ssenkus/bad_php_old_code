<? // index.php  DSC Design WOrks

session_start();
error_reporting(0);

// get the server path to the root for the galleries. used in gallery.php
$dirname=dirname(__FILE__).'/';

require_once $dirname.'pages/connect_dsc.php';
require_once $dirname.'pages/functions.php';


$request=$_SERVER['REQUEST_URI'];
$request=substr($request,1);

if (strpos($request,'?')!==false) {
// if there is at least one '?' in the query string then parse it as usual
parse_str(substr($request,1), $params);
$page=$params['page'];
$getclientid=$params['clientid'];
$clientid=$getclientid;

$getpasscode=$params['passcode'];
$passcode=$getpasscode;

$custid=$params['custid'];
$paypalcomplete=$params['paypalcomplete'];
$getorder_id=$params['orderid'];
$getorder_pass=$params['orderpass'];

} else {

if (strpos($request,'/')!==false) {
		$parameters=explode("/",$request);
		$page=$parameters[0];
		} else {	
				$page=$request;
		}
}

if ($getorder_id=='') { // get the order_id from the session - for DSC
	$getorder_id=$_SESSION['ordernum'];
}
$order_id=$getorder_id;

if ($getorder_pass=='') { // get the order_id from the session - for DSC
	$getorder_pass=$_SESSION['orderpass'];
}
$order_pass=$getorder_pass;	

//echo 'page:'.$page. ' order_id:'.$order_id;

$gallerynamearray=array(); // list of art gallery names
$galleryidarray=array();// associate array of gallery id's by name

// get a list of art gallery names so they can be passed in as the url
	$gallerystr="select * from clients where c_directory<>''";
	$galleryres=mysql_query($gallerystr);
	while ($galleryrow=mysql_fetch_array($galleryres)) {
		$galleryname=$galleryrow['c_title'];
		$gallerynamearray[]=strtolower($galleryname);
		$galleryidarray[strtolower($galleryname)]=$galleryrow['c_id'];
		
	}

$page=str_replace("_"," ",$page); // allow underscore in place of %20 in urls - makes it cleaner

//echo print_r($gallerynamearray);
//echo print_r($galleryidarray);

// if the page is in the gallery array then it is an art gallery
if (in_array(urldecode(strtolower($page)),$gallerynamearray)) {
$gallerytitle=urldecode($page);
$page='artgallery';
$clientid=$galleryidarray[strtolower($gallerytitle)];
//echo "gallery title:".$gallerytitle." client id:".$clientid;
		
		if (isset($parameters[1])) {
				$passcode=$parameters[1]; // the passcode will be the second parameter in this gallery format
		}

}


if ($page=='') {$page='home';}
if ($page=='clientgallery') {$page='artgallery';}
if ($page=='clientorder') {$page='artorder';}
if ($page=='clientpricelist') {$page='artpricelist';}



// set the order session
if ($getorder_id=='clear') {
	$order_id='';
	$passcode='';
	$_SESSION['ordernum']='';
	$_SESSION['orderpass']='';
} else {
	if ($order_id!='') {$_SESSION['ordernum']=$order_id;}
	if ($order_pass!='') { $_SESSION['orderpass']=$order_pass; }
}

if (($page=='artgallery') && ($clientid=='')) { // default to first public art gallery if none specified
	$gallerystr="select * from clients where c_directory<>'' and c_pass=''";
	$galleryres=mysql_query($gallerystr);
	$galleryrow=mysql_fetch_array($galleryres);
	$clientid=$galleryrow['c_id'];
}

$clienthqpage=false; // is this a client HQ page?
if (($page=='hq') && ($getclientid!='')) {$clienthqpage=true;}

$artgallerypage=false;
if ((substr($page,0,6)=='client') || (substr($page,0,3)=='art')) {$artgallerypage=true;}
//if (($page=='artgallery') || ($page=='artorder')) {$artgallerypage=true;}

$bg=$_GET['bg'];

$draft=$_GET['draft'];
if ($draft=='yes') {$showdraft=true;} else {$showdraft=false;}

if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) {
	$IE=true;
	} else {
	$IE=false;
}


	$pagewidth=960;
	$pagecontentwidth=960;
	$barleftmargin=0;
	$barrightmargin=0;


if (($page=='hqlogin') || ($page=='hq')) {
	if ($_SESSION['security']=='ok') {
		$page='hq';
		$pagefile="hq.php";
	} else {
		$page='hqlogin';
		$pagefile="hq_login.php";
	}
	$pagewidth=1150;
	$pagecontentwidth=1150;
}


// get the description and keywords for the site
$hqstr="select * from pages where page_param='hq'";
$hqres=mysql_query($hqstr);
if ($hqres) {
$hqrow=mysql_fetch_array($hqres);
	$keywords=$hqrow['page_keywords'];
	$description=$hqrow['page_description'];
	$hqtitle=$hqrow['page_title']; // this is the site-wide title
	$sitebgcolor=$hqrow['page_color']; // this will be the background color for the site
} 

$str="select * from pages where page_param='".mysql_real_escape_string($page)."'";
$res=mysql_query($str);
if ($res) {
$row=mysql_fetch_array($res);

//$securityok=true;

	$pagetype=$row['page_type'];
	if ($pagetype=='hq') {
		if ($_SESSION['security']!='ok') {
			$page='hqlogin';
			$pagefile="hq_login.php";
			//$securityok=false;
		}
	}

	$bgcolor=$row['page_bgcolor'];
	$color=$row['page_color'];
	$ttl=$row['page_title'];
	if ($page=='hq') {
		$ttl="HQ";
		$header="HQ";
	} /* the hq page title is used as the whole site title - so need to specifically set it here*/

	$ttladj=$row['page_titleadj'];
	if ($pagefile=='') {$pagefile=$row['page_includepage'];}
	$keywords=$keywords.','.$row['page_keywords']; // add the keywords together
	if ($row['page_description']!='') {
		$description=$row['page_description']; // use the page description if it exists
	}
} 

if ($pagetype=='gallery') {
		$gallery=$page;
	if ($gallery=='') {$gallery='Remodels';}


	$gallerypath=$row['page_gallerypath'];
	$ttladj=$row['page_titleadj'];
	$title=" | Portfolio | ".$ttl;
	$header=$ttl;

//get the gallery page info
$gallerypagestr="select * from pages where page_param='portfolio'";
$gallerypageres=mysql_query($gallerypagestr);
$gallerypagerow=mysql_fetch_array($gallerypageres);
$bgcolor=$gallerypagerow['page_bgcolor'];
	if (($bgcolor!='') &&(substr($bgcolor,0,1)!='#')) {$bgcolor='#'.$bgcolor;}
	$color=$gallerypagerow['page_color'];
	if (($color!='') && (substr($color,0,1)!='#')) {$color='#'.$color;}
	if ($pagefile=='') {$pagefile=$gallerypagerow['page_includepage'];}
	$keywords=$keywords.','.$gallerypagerow['page_keywords']; // add the keywords together
	if ($gallerypagerow['page_description']!='') {
		$description=$description.$gallerypagerow['page_description']; // use the page description if it exists
	}
	$gallerytitle=$gallerypagerow['page_title'];
	$title=" | ".$gallerytitle." | ".$ttl;

} else {
	if ($page=='artgallery') { // get the page title and header if a client page
		$clientstr="select * from clients where c_id=".mysql_real_escape_string ($clientid);
		$clientres=mysql_query($clientstr);
		$clientrow=mysql_fetch_array($clientres);
			if (empty($clientrow)) {
				// no client with this id
			} else {
			$pass=$clientrow['c_pass'];
			if ($clienthqpage) {$passcode=$pass;} // if a clienthqpage then provide the passcode
			if ($passcode!=$pass) {
				// passcode doesn't match
			} else { // password match so okay
				
				$subclientof = $clientrow['c_subclientof'];
				if ($subclientof==0) { // primary client

						$clienttitle=$clientrow['c_title'];
						$first=$clientrow['c_first'];
						$last=$clientrow['c_last'];
						$clientname=$first.' '.$last;
						if ($clienttitle=='') {$clienttitle=$clientname;}
						$ttladj=$clientrow['c_ttladj'];
						$ttl=$clienttitle;
						$title=" | ".$ttl." ";
						$header=$ttl;
						$subclient=false;
				} else {
						$primaryclientstr="select * from clients where c_id=".mysql_real_escape_string ($subclientof);
						$primaryclientres=mysql_query($primaryclientstr);
						$primaryclientrow=mysql_fetch_array($primaryclientres);

						$clienttitle=$primaryclientrow['c_title'];
						$first=$primaryclientrow['c_first'];
						$last=$primaryclientrow['c_last'];
						$clientname=$first.' '.$last;
						if ($clienttitle=='') {$clienttitle=$clientname;}
						$ttladj=$primaryclientrow['c_ttladj'];
						$ttl=$clienttitle;
						$title=" | ".$ttl." ";
						$header=$ttl;
						$subclient=true;
				}
			}
		}
	} else {
		$title=" | ".$ttl." ";
		$header=$ttl;
	}
}

$id=$_GET['id'];
if (($page=='hq') && ($id!='')) { /* get the colors for the hq manage page page */
$pagestr="select * from pages where page_id='".mysql_real_escape_string($id)."'";
$pageres=mysql_query($pagestr);
	if ($pageres) {
		$pagerow=mysql_fetch_array($pageres);
		if ($pagerow['page_type']!='gallery') {
		$bgcolor=$pagerow['page_bgcolor'];
		$color=$pagerow['page_color'];
		}
		$header=$pagerow['page_title'];
		$hqpageparam=$pagerow['page_param'];
		$hqgallerypath=$pagerow['page_gallerypath'];
		$ttladj=$pagerow['page_titleadj'];
		$editingpagetype=$pagerow['page_type'];
	} 
}





$bgcolor='#E57C45'; //'#690505'; // OVERRIDE OTHER COLORS
$color='#fff'; // OVERRIDE OTHER COLORS



$title=$hqtitle.$title;

// space out header
$spacedheader='';
$headerarray=str_split($header);
$i=0;
foreach($headerarray as $h) {
$i++;
$spacedheader.=$h;
if ($i<count($headerarray)) {$spacedheader.=' ';} /* add a space between letters if it's not the last letter*/
}

?><? if (true) { ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><? } else { ?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><? } ?>
	<head>
		<title><?=$title?></title>
		<meta name="description" content="<?=$description?>">
		<meta name="keywords" content="<?=$keywords?>">
		<link rel="shortcut icon" href="/dsc_favicon.ico" />
		<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1" >
		<meta http-equiv="X-UA-Compatible" content="IE=9" />
		<link rel="stylesheet" href="/pages/css/basic.css" type="text/css" >
		<link rel="stylesheet" href="/pages/css/galleriffic.css" type="text/css" >
		<script type="text/javascript" src="/pages/js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="/pages/js/jquery.galleriffic.js"></script>
		<script type="text/javascript" src="/pages/js/jquery.opacityrollover.js"></script>

<? if ($artgallerypage) { ?>
	<script type="text/javascript" src="/pages/fancybox/jquery.easing-1.3.pack.js"></script> 
	<script type="text/javascript" src="/pages/fancybox/jquery.mousewheel-3.0.4.pack.js"></script> 
	<script type="text/javascript" src="/pages/fancybox/jquery.fancybox-1.3.4.js"></script> 
	<link rel="stylesheet" type="text/css" href="/pages/fancybox/jquery.fancybox-1.3.4.css" media="screen" /> 
<? } ?>

		<style type="text/css">


#jsddm
{	margin: 0;
	padding: 0px 0px 0px 0px;;
	z-index:10;}
	
	#jsddm li
	{	float: left;
		list-style: none;
		z-index:10;
		padding-right:2px;
		}

	#jsddm li a
	{	display: block;
		/*background: <?=$bgcolor?>;*/
		color: <?=$color?>;
		text-decoration: none;
		font-family: 'Century Gothic',arial;
		white-space: nowrap;
		position:relative;
		z-index:10;
		}

	#jsddm li a:hover
	{ /*	background: <?=$bgcolor?>;*/
		color: <?=$color?>;}
		
		#jsddm li ul
		{	margin: 0;
			margin-top:10px;	 /* THIS IS THE GAP OF THE DROPDOWN MENU*/		
			padding: 5px 3px 3px 3px;
			position: absolute;
			visibility: hidden;
			border-top: 1px solid white  /* for some reason a ; breaks everything ? */
			height:18px;
			background-color:<?=$color?>;
			border: 2px solid <?=$bgcolor?>;
			z-index:9;
			text-align:center;
			}
		
		#jsddm li ul li
		{	float: none;
			display: inline;
			z-index:1000;
			padding: 0px 0px 3px 0px;
			}
		
		#jsddm li ul li a
		{	width: auto;
			color: <?=$bgcolor?>;
			background: <?=$color?>;
			z-index:1000;
			}
		
		#jsddm li ul li a:hover
		{	color: <?=$color?>;
			background: <?=$bgcolor?>;
		}


			a.barlink, #jsddm li a{color:<?=$color?>;	font-family:'Century Gothic',arial;font-size:12px;text-decoration:none;letter-spacing:2px;font-weight:bold;padding:0px 3px 0px 3px;height:18px;}
			a.barlink:hover, #jsddm li a:hover {;color:<?=$bgcolor?>;height:18px;text-decoration:none;}

		</style>
<? if (true) { // ($page=='gallery')?>
<script type="text/javascript">
var timeout    = 500;
var closetimer = 0;
var ddmenuitem = 0;

function jsddm_open()
{  jsddm_canceltimer();
   jsddm_close();
   ddmenuitem = $(this).find('ul').css('visibility', 'visible');}

function jsddm_close()
{  if(ddmenuitem) ddmenuitem.css('visibility', 'hidden');}

function jsddm_timer()
{  closetimer = window.setTimeout(jsddm_close, timeout);}

function jsddm_canceltimer()
{  if(closetimer)
   {  window.clearTimeout(closetimer);
      closetimer = null;}}

$(document).ready(function()
{  $('#jsddm > li').bind('mouseover', jsddm_open)
   $('#jsddm > li').bind('mouseout',  jsddm_timer)});

document.onclick = jsddm_close;
// source: http://javascript-array.com/scripts/jquery_simple_drop_down_menu/
</script>
<? } ?>
	</head>
	<body>

	<div style="margin-left:auto;margin-right:auto;margin-top:20px;width:1100px;background-color:#fff;">

		<div style="width:1100px;height:24px;background-color:#fff;">
			
		</div>
		<div style="width:1100px;vertical-align:bottom;background-color:#fff;">
			<a href="/" style="float:left;margin-left:76px;"><img src="/pages/galleries/pics/site/DSClogo.jpg" width="350" height="100" border="0" alt="DSC Design Works"></a>
			<div id="title" style="float:right;margin-top:40px;margin-right:60px;"><img src="/pages/galleries/pics/site/DesignBuildRemodel.jpg" width="200" height="50" border="0" alt="Design Build Remodel"></div>
		</div>
		<br clear="all" style="background-color:#fff;">

		<!-- MENU BAR -->
		<?
			$menuspacing=10; // spacing between menu items
			$menutopspacing=0; // spacing above menu items
			$menustart=384; // where does the first menu item start
		?>
		<div style="width:1100px;height:30px;text-align:right;vertical-align:top;background-color:#e57c45;">
		<ul id="jsddm" style="<? if ($IE) {?>margin-top:0px;<? } ?>">
			<li style="margin-left:<?=$menustart?>px;margin-top:<?=$menutopspacing?>px;<? if ($IE) {?>width:74px;<? } ?>"><a id="menu-home"  href="/home"><img class="hoverbutton" src="/pages/galleries/pics/site/menu/home.jpg" hover="/pages/galleries/pics/site/menu/home_over.jpg" width="74" height="30" border="0" alt="home" ></a>
				<? 
				$menuadjstr="select page_menuadj from pages where page_param='home'"; 
				$menuadjres=mysql_query($menuadjstr);
				$menuadjrow=mysql_fetch_array($menuadjres);
				$menuadj=$menuadjrow['page_menuadj']; 
				?>
        
				<? $parentstr="select * from pages where page_parent='home' and page_status<>'hide' order by page_order,page_title";
					$parentres=mysql_query($parentstr);
					if (mysql_num_rows($parentres)>0) {
					?><ul style="margin-left:<?=$menuadj?>px;"><?
					while ($parentrow=mysql_fetch_array($parentres)){
					?>
            <li><a href="/<?=urlencode($parentrow['page_param'])?>" ><?=$parentrow['page_title']?></a></li>
					<? } ?></ul><? }?>
		</li>


			<li <? if ($IE) {?>style="width:101px;"<? } ?>><a id="menu-about" href="/dscdesignworks" style="margin-left:<?=$menuspacing?>px;margin-top:<?=$menutopspacing?>px;" ><img class="hoverbutton" src="/pages/galleries/pics/site/menu/about.jpg" hover="/pages/galleries/pics/site/menu/about_over.jpg" width="74" height="30" border="0" alt="about"></a>
				<? 
				$menuadjstr="select page_menuadj from pages where page_param='about'"; 
				$menuadjres=mysql_query($menuadjstr);
				$menuadjrow=mysql_fetch_array($menuadjres);
				$menuadj=$menuadjrow['page_menuadj']; 
				?>
        
				<? $parentstr="select * from pages where page_parent='About' and page_status<>'hide' order by page_order,page_title";
					$parentres=mysql_query($parentstr);
					if (mysql_num_rows($parentres)>0) {
					?><ul style="margin-left:<?=$menuadj?>px;"><?
					while ($parentrow=mysql_fetch_array($parentres)){
					?>
            <li <? if ($IE) {?>style="width:101px;"<? } ?>><a href="/<?=urlencode($parentrow['page_param'])?>" ><?=$parentrow['page_title']?></a></li>
					<? } ?></ul><?}?>
        
		</li>

			

			<?
			// get the default page - the first one that comes up
				$parentstr="select * from pages where page_type='gallery' and page_parent='portfolio' and page_status<>'hide' order by page_order,page_title";
				$parentres=mysql_query($parentstr);
				$parentrow=mysql_fetch_array($parentres);
?>
		 <li <? if ($IE) {?>style="width:114px;"<? } ?>><a id="menu-portfolio"  href="/<?=urlencode($parentrow['page_param'])?>" style="margin-left:<?=$menuspacing?>px;margin-top:<?=$menutopspacing?>px;"><img class="hoverbutton" src="/pages/galleries/pics/site/menu/portfolio.jpg" hover="/pages/galleries/pics/site/menu/portfolio_over.jpg" width="103" height="30" border="0" alt="portfolio"></a>
	

				<? 
				$menuadjstr="select page_menuadj from pages where page_param='portfolio'"; 
				$menuadjres=mysql_query($menuadjstr);
				$menuadjrow=mysql_fetch_array($menuadjres);
				$menuadj=$menuadjrow['page_menuadj']; 
				?>
				<? 
				$parentstr="select * from pages where page_type='gallery' and page_parent='portfolio' and page_status<>'hide' order by page_order,page_title";
					$parentres=mysql_query($parentstr);
					$i=1;
					while ($parentrow=mysql_fetch_array($parentres)){
						$gallerytitlelink=$parentrow['c_title'];
						$gallerytitlelink=str_replace(" ","_",$gallerytitlelink); // use underscore instead of space - makes it cleaner
					?>
					  <? if ($i==1) { ?><ul style="margin-left:<?=$menuadj?>px;"><? } ?>
            <li <? if ($IE) {?>style="width:101px;"<? } ?>><a href="/<?=urlencode($parentrow['page_param'])?>"><?=$parentrow['page_title']?></a></li>

					<? $i++;
					} ?>
					<? if ($i>1) { ?></ul><? } ?>
    </li>

		<?
		// get the default page - the first one that comes up
		$parentstr="select * from pages where page_parent='Services' and page_status<>'hide' order by page_order,page_title";
		$parentres=mysql_query($parentstr);
		$parentrow=mysql_fetch_array($parentres);
		?>


		<li <? if ($IE) {?>style="width:110px;"<? } ?>><a id="menu-services"  href="/<?=urlencode($parentrow['page_param'])?>" style="margin-left:<?=$menuspacing?>px;margin-top:<?=$menutopspacing?>px;" ><img class="hoverbutton" src="/pages/galleries/pics/site/menu/services.jpg" hover="/pages/galleries/pics/site/menu/services_over.jpg" width="91" height="30" border="0" alt="services"></a>
				<? 
				$menuadjstr="select page_menuadj from pages where page_param='services'"; 
				$menuadjres=mysql_query($menuadjstr);
				$menuadjrow=mysql_fetch_array($menuadjres);
				$menuadj=$menuadjrow['page_menuadj']; 
				?>
        
				<? $parentstr="select * from pages where page_parent='Services' and page_status<>'hide' order by page_order,page_title";
					$parentres=mysql_query($parentstr);
					if (mysql_num_rows($parentres)>0) {
					?><ul style="margin-left:<?=$menuadj?>px;"><?
					while ($parentrow=mysql_fetch_array($parentres)){
					?>
            <li <? if ($IE) {?>style="width:76px;"<? } ?>><a href="/<?=urlencode($parentrow['page_param'])?>" ><?=$parentrow['page_title']?></a></li>
					<? } ?></ul><? }?>

        
		</li>


			<li <? if ($IE) {?>style="width:133px;"<? } ?>><a id="menu-testimonials"  href="/testimonials" style="margin-left:<?=$menuspacing?>px;margin-top:<?=$menutopspacing?>px;" ><img class="hoverbutton" src="/pages/galleries/pics/site/menu/testimonials.jpg" hover="/pages/galleries/pics/site/menu/testimonials_over.jpg" width="131" height="30" border="0" alt="testimonials"></a>
				<? 
				$menuadjstr="select page_menuadj from pages where page_param='testimonials'"; 
				$menuadjres=mysql_query($menuadjstr);
				$menuadjrow=mysql_fetch_array($menuadjres);
				$menuadj=$menuadjrow['page_menuadj']; 
				?>
        
				<? $parentstr="select * from pages where page_parent='Testimonials' and page_status<>'hide' order by page_order,page_title";
					$parentres=mysql_query($parentstr);
					if (mysql_num_rows($parentres)>0) {
					?><ul style="margin-left:<?=$menuadj?>px;"><?
					while ($parentrow=mysql_fetch_array($parentres)){
					?>
            <li <? if ($IE) {?>style="width:89px;"<? } ?>><a href="/<?=urlencode($parentrow['page_param'])?>" ><?=$parentrow['page_title']?></a></li>
					<? } ?></ul><? }?>
        
		</li>

			<li <? if ($IE) {?>style="width:89px;"<? } ?>><a id="menu-contact"  href="/contact" style="margin-left:<?=$menuspacing?>px;margin-top:<?=$menutopspacing?>px;" title="CONTACT"><img class="hoverbutton" src="/pages/galleries/pics/site/menu/contact.jpg" hover="/pages/galleries/pics/site/menu/contact_over.jpg" width="94" height="30" border="0" alt="contact"></a>
				<? 
				$menuadjstr="select page_menuadj from pages where page_param='contact'"; 
				$menuadjres=mysql_query($menuadjstr);
				$menuadjrow=mysql_fetch_array($menuadjres);
				$menuadj=$menuadjrow['page_menuadj']; 
				?>
        
				<? $parentstr="select * from pages where page_parent='Contact' and page_status<>'hide' order by page_order,page_title";
					$parentres=mysql_query($parentstr);
					if (mysql_num_rows($parentres)>0) {
					?><ul style="margin-left:<?=$menuadj?>px;"><?
					while ($parentrow=mysql_fetch_array($parentres)){
					?>
            <li <? if ($IE) {?>style="width:89px;"<? } ?>><a href="/<?=urlencode($parentrow['page_param'])?>" ><?=$parentrow['page_title']?></a></li>
					<? } ?></ul><? }?>
        
		</li>

		</div>
		<!-- MENU BAR -->
<script>
		$(function() {
    $('.hoverbutton').hover(function() {
        var currentImg = $(this).attr('src');
        $(this).attr('src', $(this).attr('hover'));
        $(this).attr('hover', currentImg);
    }, function() {
        var currentImg = $(this).attr('src');
        $(this).attr('src', $(this).attr('hover'));
        $(this).attr('hover', currentImg);
    });
});
//source: http://www.selfcontained.us/2008/03/08/simple-jquery-image-rollover-script/
</script>
  <div  id="content" style="margin-top:10px;margin-left:auto;margin-right:auto;width:1100px;background-color:#fff;">

		<? 		
		if ($pagefile!='') {
				include "pages/".$pagefile;
		} else { // show the content from the CMS if there is not pagefile
		

		if ($showdraft) {
			$content=$row['page_draft'];
		} else {
			$content=$row['page_content'];
		}

	?><div style="width:1100px;float:left;background-color:#fff;">
	
	<?=$content;?>
	
	</div>
	
	<?
}
		
		
		
		
		?>
<div style="width:1100px;margin-bottom:10px;">
<img src="/pages/galleries/pics/site/footer.jpg" width="1100" height="101" border="0" alt="">
</div>


<? if (false) { // ($page!='contact') causes a gap for some reason on that page ?>
<br style="clear: both;height:0px;" />
<? } ?>
	</div>


				<!-- <div style="margin-left:auto;margin-right:auto;width:1100px;height:35px;padding-top:4px;text-align:left;">
					<img src="/pages/galleries/pics/site/copyright.jpg" width="99" height="18" border="0" alt="copyright" style="vertical-align:text-bottom;margin-left:6px;"><span style="font-size:18px;color:black;font-family:'Century Gothic',arial;margin-left:3px;margin-right:3px;vertical-align:bottom;"><?=date("Y")?></span><img src="/pages/galleries/pics/site/yumilife-small.jpg" width="111" height="18" border="0" alt="DSCDesignWorks.com"  style="vertical-align:text-bottom;">
				</div> -->

<!-- <script type="text/javascript"> 
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script> 
<script type="text/javascript"> 
try {
var pageTracker = _gat._getTracker("UA-7916273-1");
pageTracker._trackPageview();
} catch(err) {}</script>  -->

<!-- page:<?=$page?> -->
	</body>
</html>
