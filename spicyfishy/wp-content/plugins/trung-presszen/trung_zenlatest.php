<h2>Latest photos</h2>
<div id="myGallery">
	<?php
 
	$images = getImageStatistic(4, "latest", 0);

	foreach ($images as $image)
	{
		$percent = $image->getWidth() / 100; // getting 1 percent from the width
		$heightpercent = $image->getHeight(); // $percent; // getting how many percent the height of the width;
		// if the image is a quadrat
		if ($image->getWidth() === $image->getHeight())
		{
			$s = 400;
			$w = 400;
			$h=400;
		}
		// the height should never be more than 61 percent (494 are 61.75 percent from maxwidth 800)
		elseif ($heightpercent >= 61)
		{
			$s=null;
			$w=null;
			$h=250;
		}
		else
		{
			$s=400;
			$w=400;
			$h=250;
		}

		$title = $image->name;
		//$description = $image->description;
		list($size, $width, $height, $cw, $ch, $cx, $cy, $quality) = getImageParameters(array($s, $w, $h));
		$url1 = $image->getCustomImage($size, $width, $height, $cw, $ch, $cx, $cy, $quality);
		$url2 = $image->getCustomImage($size, $width, $height, $cw, $ch, $cx, $cy, $quality);
		echo
		<<<EOF
		<div class="imageElement">
			<h3>Newst gallery</h3>
			<p></p>
			<a href="#" title="open image" class="open"></a>
			<img src="{$url1}" class="full" />
			<img src="{$url2}" class="thumbnail" />
		</div>
EOF;

}
	?>
</div>