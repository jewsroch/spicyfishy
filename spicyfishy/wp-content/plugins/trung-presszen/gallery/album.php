<div class="">
	<h2><?php
 printAlbumTitle(true); ?></h2>
	<div class="galleryinfo">
		<?php $number = getNumImages(); if ($number > $conf['images_per_page']) 
		echo trung_presszen_rewritepath(trung_presszen_capture('printPageListWithNav', array("&laquo; prev", "next &raquo;"))); ?>
	</div>
	<ul class="galleries">
	<?php while (next_album()): ?>
		<li>
			<a href="<?php echo getAlbumLinkURL();?>" title="View album: <?php echo getAlbumTitle();?>" class="img">
       				<?php printCustomAlbumThumbImage(getAlbumTitle(), null, 230, null, 210, 60, null, null, 'reflect', null); ?>
            </a>
			<h3><a href="<?php echo getAlbumLinkURL();?>" title="View album: <?php echo getAlbumTitle();?>"><?php printAlbumTitle(); ?></a></h3>
			<p><em>(<? $number = getNumImages(); if ($number > 1) $number .= " photos"; else $number .=" photo"; echo$number; ?>)</em> <?php $text = getAlbumDesc(); if( strlen($text) > 50) $text = preg_replace("/[^ ]*$/", '', substr($text, 0, 50))."&#8230;"; echo$text; ?></p>
		</li>
	<?php endwhile; ?>
	</ul>  
	<ul class="slides">
		<?php while (next_image()): ?>
		<li><a href="<?php echo trung_presszen_rewritepath(getImageLinkURL());?>" title="<?php echo getImageTitle();?>"><?php printImageThumb(getImageTitle()); ?></a></li>
		<?php endwhile; ?>
	</ul>
   
	<br style="clear:both" />
	<div class="galleryinfo">
		<?php $number = getNumImages(); if ($number > $conf['images_per_page']) 
		echo trung_presszen_rewritepath(trung_presszen_capture('printPageListWithNav', array("&laquo; prev", "next &raquo;"))); ?>
	</div>
</div>