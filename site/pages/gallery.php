	<!-- Start Advanced Gallery Html Containers -->
<div style="margin-left:20px;">

				<div id="thumbs" class="navigation" style="width:340px;margin-right:10px;">
<div style="font-family:century gothic,arial;font-size:20px;font-weight:normal;color:#aaa;">Our&nbsp;<?=$ttl?>&nbsp;Gallery</div>
					<ul class="thumbs noscript" style="width:240px;">
<?

$galllerydir=$gallerypath;
$lowergallerydir=strtolower($galllerydir);
if ($lowergallerydir=='') {$galllerydir='remodels';}

//define the path as relative
$medpath = "pages/galleries/".$galllerydir."/med";

if (!file_exists($dirname.$medpath)) {
	$medpath = "pages/galleries/remodels/med";
	$gallerydir="remodels";
}


$thumbpath = "pages/galleries/".$galllerydir."/thumbs";

//using the opendir function
$dir_handle = @opendir($dirname.$medpath) or die("Unable to open $path");

// sort the files
$files=array();
while ($file = trim(readdir($dir_handle))) {
   $files[$file] = $file; 
}
sort($files);


//running the while loop
//while ($file = trim(readdir($dir_handle))) 
foreach($files as $file) 
{
$medfullpath=$medpath.'/'.$file;
$thumbfullpath=$thumbpath.'/'.$file;
if (!file_exists($dirname.$thumbfullpath)) {
$thumbfullpath=$medfullpath;
}
   if(($file!='.') && ($file!='..') && (substr($file,0,1)!='.')) { // don't show these files
      ?>
						<li>
							<a class="thumb" name="bigleaf" href="/<?=$medfullpath?>" title="">
								<img src="/<?=$thumbfullpath?>" alt="<?=$file?>" width="60" height="60">
							</a>
							<div class="caption">
								<!-- <div class="image-title">Title</div>
								<div class="image-desc">Description</div> -->
							</div>
						</li>			
			<?
	 }
}

//closing the directory
closedir($dir_handle);


// credit: http://www.spoono.com/php/tutorials/tutorial.php?id=10
?> 

					</ul>
				</div>
				<div id="gallery" class="content">
					<div class="slideshow-container">
						<div id="loading" class="loader"></div>
						<div id="slideshow" class="slideshow"></div>
					</div>
					<div >
						<div id="controls" class="controls"></div>
						<div id="startstop" class="startstop" style="float:right;margin-top:-20px;"></div>
					</div>
				</div>
</div>



	<div style="float:left;margin:0px;padding:0px;margin-left:50px;margin-top:-30px;height:36px;"><img src="/pages/galleries/pics/portfolio/additiondetails.jpg" width="255" height="36" border="0" alt="" ></div>
	<div style="float:left;"><img src="/pages/galleries/pics/portfolio/youcanview.jpg" width="1100" height="84" border="0" alt=""></div>
	<div style="float:left;margin-left:80px;"><img src="/pages/galleries/pics/portfolio/wheretostart.jpg" width="346" height="436" border="0" alt=""></div>
	<div style="float:left;margin-left:50px;margin-top:50px;"><? include "contactform.php"; ?></div>



		<script type="text/javascript">
			jQuery(document).ready(function($) {
				// We only want these styles applied when javascript is enabled
				$('div.navigation').css({'width' : '160px', 'float' : 'left'});
				$('div.content').css('display', 'block');

				// Initially set opacity on thumbs and add
				// additional styling for hover effect on thumbs
				var onMouseOutOpacity = 0.67;
				$('#thumbs ul.thumbs li').opacityrollover({
					mouseOutOpacity:   onMouseOutOpacity,
					mouseOverOpacity:  1.0,
					fadeSpeed:         'fast',
					exemptionSelector: '.selected'
				});
				
				// Initialize Advanced Galleriffic Gallery
				var gallery = $('#thumbs').galleriffic({
					delay:                     3000,
					numThumbs:                 12,
					preloadAhead:              2,
					enableTopPager:            false,
					enableBottomPager:         true,
					maxPagesToShow:            12,
					imageContainerSel:         '#slideshow',
					controlsContainerSel:      '#controls',
					startstopContainerSel:     '#startstop',
					captionContainerSel:       '#caption',
					loadingContainerSel:       '#loading',
					renderSSControls:          true,
					renderNavControls:         true,
					playLinkText:              'Start Slideshow',
					pauseLinkText:             'Stop Slideshow',
					prevLinkText:              '&lsaquo; Previous Photo',
					nextLinkText:              'Next Photo &rsaquo;',
					prevLinkImg:              '/pages/img/arrow-prev.jpg',
					nextLinkImg:              '/pages/img/arrow-next.jpg',
					nextPageLinkText:          '>',
					prevPageLinkText:          '<',
					enableHistory:             false,
					autoStart:                 false,
					syncTransitions:           true,
					defaultTransitionDuration: 1200,
					onSlideChange:             function(prevIndex, nextIndex) {
						// 'this' refers to the gallery, which is an extension of $('#thumbs')
						this.find('ul.thumbs').children()
							.eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
							.eq(nextIndex).fadeTo('fast', 1.0);
					},
					onPageTransitionOut:       function(callback) {
						this.fadeTo('fast', 0.0, callback);
					},
					onPageTransitionIn:        function() {
						this.fadeTo('fast', 1.0);
					}
				});

		setTimeout(function(){gallery.play()},3000);}); /* delay start for 3 seconds */

		</script>