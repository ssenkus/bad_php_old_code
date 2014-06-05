<? // dynfont.php
$font=$_REQUEST["font"];
if ($font=='') {	$font="CenturyGothic.ttf";}
$fontpath="fonts/".$font;
//error_log('fontpath:'.$fontpath);
$adj=$_REQUEST['adj'];

$font_size=$_REQUEST["font_size"];
if ($font_size=="") $font_size = 18;
$wrd=$_REQUEST["wrd"];
if ($wrd=='') {$wrd=' ';}
$wrd= (!get_magic_quotes_gpc()) ? $wrd : stripslashes($wrd);

$wd=(strlen($wrd)*.6*($font_size)); 
//$wd=(strlen($wrd)*.8*($font_size)); 
if ($adj!='') {$wd=$wd+$adj;} /* width adjustment */
// Get the height. This seems to be the "magic" formula -DW
//$rectheight=$font_size+($font_size/4);
$rectheight=$font_size*1.4;
$angle = 0;
$x_start = 0;
$y_start = $font_size+2;

// Set the content-type
header("Content-type: image/png");
$color=$_GET["color"];
if ($color=="") $color="#9c0004";

$color1=$_GET["color1"];
if ($color1=="") $color1="#fff";
$color3=$_GET["color3"];
if ($color3=="") $color3="#690505"; // background color
// Create the image
//$im = imagecreatetruecolor($wd, $rectheight);
// Create some colors
// Use GD library to create transparent color PNG image of the pixel size
$ImgGD = imagecreate($wd, $rectheight);
$thisrgb= html2rgb($color);

//error_log($thisrgb[0].' '.$thisrgb[1].' '.$thisrgb[2]);

$dynamiccolor =  imagecolorallocate($ImgGD,$thisrgb[0], $thisrgb[1], $thisrgb[2]);

$bgcolorrgb=html2rgb($color3);
$bgcolor = imagecolorallocate($ImgGD, $bgcolorrgb[0], $bgcolorrgb[1], $bgcolorrgb[2]);

$white = imagecolorallocate($ImgGD, 255, 255, 255);
$grey = imagecolorallocate($ImgGD, 221, 222, 223);
$black = imagecolorallocate($ImgGD, 0, 0, 0);
$darkgrey = imagecolorallocate($ImgGD, 63, 63, 63);
$medgrey = imagecolorallocate($ImgGD, 127, 127, 127);
$red =  imagecolorallocate($ImgGD, 255, 0, 0); // 690505

imagefill($ImgGD, 0, 0, $bgcolor);
//imagecolortransparent($ImgGD, $bgcolor);
imagettftext($ImgGD, $font_size, $angle, $x_start, $y_start, $dynamiccolor, $fontpath, $wrd);

// Capture STDOUT to buffer
ob_start();
imagepng($ImgGD);
$Image = ob_get_contents();
ob_end_clean();

// Remember to free your memory
imagedestroy($ImgGD);

// Print image to web browser.
print $Image;




function html2rgb($color)
{
    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
        return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    return array($r, $g, $b);
}
// credit: http://www.anyexample.com/programming/php/php_convert_rgb_from_to_html_hex_color.xml

?>