<style type="text/css">
div.loader {width:1020px;}
div.slideshow a.advance-link {width:1020px;}
div.slideshow span.image-wrapper {vertical-align: top;}
div.slideshow img {	vertical-align: top;}
div.content {margin-left:0px;width:1020px;}
</style>
				
				<div id="thumbs" class="navigation" style="display:none;">
					<ul class="thumbs noscript" style="display:none;">
<?

$slideshowdir=$slideshowpath;
$lowergallerydir=strtolower($slideshowdir);
if ($lowergallerydir=='') {$slideshowdir='remodels';}

//define the path as relative
$medpath = "pages/galleries/".$slideshowdir."/med";

if (!file_exists($dirname.$medpath)) {
	$medpath = "pages/galleries/remodels/med";
	$gallerydir="remodels";
}


$thumbpath = "pages/galleries/".$slideshowdir."/thumbs";

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
							<a class="thumb" href="/<?=$medfullpath?>" title="click slideshow to stop / advance to next image - or use arrow keys">
								<img src="/<?=$thumbfullpath?>" alt="<?=$file?>" width="60" height="60">
							</a>
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
				<div id="gallery" class="content" style="width:1020px;height:410px;">
					<div class="slideshow-container"  >
						<div id="loading" class="loader"  ></div>
						<div id="slideshow" class="slideshow" ></div>
					</div>
					<div >
					</div>
				</div>



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
					delay:                     5000,
					numThumbs:                 12,
					preloadAhead:              2,
					enableTopPager:            false,
					enableBottomPager:         false,
					maxPagesToShow:            12,
					imageContainerSel:         '#slideshow',
					controlsContainerSel:      '#controls',
					startstopContainerSel:     '#startstop',
					captionContainerSel:       '#caption',
					loadingContainerSel:       '#loading',
					renderSSControls:          false,
					renderNavControls:         false,
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
					defaultTransitionDuration: 2200,
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