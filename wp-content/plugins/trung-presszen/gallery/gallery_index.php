<div class='gallerypage'>	
	<ul class="galleries">
	<?php
 while (next_album()):	 ?>
		<li>
			<a href="<?php echo trung_presszen_rewritepath(getAlbumLinkURL());?>" title="View album: <?php echo html_entity_decode(getAlbumTitle());?>" class="img">
			<?php printCustomAlbumThumbImage(getAlbumTitle(), null, 230, null, 210, 60, null, null, 'reflect', null); ?>
			</a>
		  	<h3><a href="<?php echo trung_presszen_rewritepath(getAlbumLinkURL());?>" title="View album: <?php getAlbumTitle();?>"><?php printAlbumTitle(); ?></a></h3>
		  	<p><em>(<?php $number = getNumImages(); if ($number > 1) $number .= " photos"; else $number .=" photo"; echo$number; ?>)</em> <?php $text = getAlbumDesc(); if( strlen($text) > 50) $text = preg_replace("/[^ ]*$/", '', substr($text, 0, 50))."&#8230;"; echo$text; ?></p>
		</li>
  <?php endwhile; ?>
	</ul>
	
	<br style="clear:both" />
	<div class="galleryinfo">
		<?php printPageListWithNav("&laquo; ".gettext("prev"), gettext("next")." &raquo;"); ?>
	</div>	
</div>	