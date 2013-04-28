<?php


$percent = getFullWidth() / 100; // getting 1 percent from the width
$heightpercent = getFullHeight() / $percent; // getting how many percent the height of the width;
// if the image is a quadrat
if (getFullWidth() === getFullHeight())
{
	$s = 800;
	$w = 800;
	$h=800;
}
// the height should never be more than 61 percent (494 are 61.75 percent from maxwidth 800)
elseif ($heightpercent >= 61)
{
	$s=null;
	$w=null;
	$h=494;
}
else
{
	$s=800;
	$w=800;
	$h=494;
}
?>

<div class="galleries" id="top">
	<p id="path"><a href="<?php echo trung_presszen_rewritepath(getGalleryIndexURL());?>" title="Gallery Index"><?php echo getGalleryTitle();?></a> >
		 <a href="<?php echo trung_presszen_rewritepath(getAlbumLinkURL());?>" title="Gallery Index"><?php echo getAlbumTitle();?></a> >
	     <a style="color:white;"><?php echo printImageTitle(true); ?></a>
	</p>
	<div class="imageview" >

	<p><a class="thickbox" href="<?php 
	list($size, $width, $height, $cw, $ch, $cx, $cy, $quality) = getImageParameters(array($s, $w, $h));
	 $url = $_zp_current_image->getCustomImage($size, $width, $height, $cw, $ch, $cx, $cy, $quality);
	echo $url = $_zp_current_image->getCustomImage($size, $width, $height, $cw, $ch, $cx, $cy, $quality);
	?>" title="<?php echo getImageTitle();?>"><?php printCustomSizedImage(getImageTitle(), $s, $w, $h); ?></a></li></p>
	
	</div>
	<div id="desc">
		<h1><?php printImageTitle(true); ?></h1>
		<p><?php printImageDesc(true); ?></p>
	</div>
	
	
</div>
<?php

$percent = getFullWidth() / 100; // getting 1 percent from the width
$heightpercent = getFullHeight() / $percent; // getting how many percent the height of the width;
// if the image is a quadrat
if (getFullWidth() === getFullHeight())
{
	$s = 800;
	$w = 800;
	$h=800;
}
// the height should never be more than 61 percent (494 are 61.75 percent from maxwidth 800)
elseif ($heightpercent >= 61)
{
	$s=null;
	$w=null;
	$h=494;
}
else
{
	$s=800;
	$w=800;
	$h=494;
}
?>
