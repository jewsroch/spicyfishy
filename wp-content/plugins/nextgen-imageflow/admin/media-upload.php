<?php
function if_wp_upload_tabs( $tabs ) {

	$newtab = array('imageflow' => __('ImageFlow','nggflow'));
 
    return array_merge( $tabs, $newtab );
}
add_filter('media_upload_tabs', 'if_wp_upload_tabs');

function media_upload_imageflow()
{
	if ( isset( $_POST['save'] ) )
	{
		if( $_POST['mode'] == 'normal')
		{
			$html = '[imageflow id="'. $_POST['select_gal'] .'"]';
		}
		elseif( $_POST['mode'] == 'tags' )
		{
			$tags = implode( ',', $_POST['select_tags'] );
			$html = '[if-tags tags="'. $tags .'"]';
		}
		elseif( $_POST['mode'] == 'thumbs' )
		{
			$ids = implode( ',', $_POST['select_thumbs'] );
			$html = '[if-thumbs id="'. $ids .'"]';
		}
		elseif( $_POST['mode'] == 'random' )
		{
			$html = '[if-rand max="'. $_POST['maximages'] .'"]';
		}
		elseif( $_POST['mode'] == 'recent' )
		{
			$html = '[if-recent max="'. $_POST['maximages'] .'"]';
		}
		elseif( $_POST['mode'] == 'album' )
		{
			$html = '[if-album id="'. $_POST['select_album'] .'"]';
		}

		return media_send_to_editor( $html );
	}
		
	return wp_iframe( 'media_upload_if_form', $errors );
}
add_action('media_upload_imageflow', 'media_upload_imageflow');


function media_upload_if_form( $errors ) {
	global $wpdb;

	media_upload_header();
?>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery("#album-show,#thumbs-show,#tag-show,#max-show").hide();
		jQuery("#normal,#tags,#thumbs,#random,#recent,#album").click(function(){
			if (jQuery("#normal").is(":checked")) { jQuery("#norm-gallery").slideDown("fast"); } else { jQuery("#norm-gallery").slideUp("fast"); }
			if (jQuery("#tags").is(":checked")) { jQuery("#tag-show").slideDown("fast"); } else { jQuery("#tag-show").slideUp("fast"); }
			if (jQuery("#thumbs").is(":checked")) { jQuery("#thumbs-show").slideDown("fast"); } else { jQuery("#thumbs-show").slideUp("fast"); }
			if (jQuery("#random,#recent").is(":checked")) { jQuery("#max-show").slideDown("fast"); } else { jQuery("#max-show").slideUp("fast"); }
			if (jQuery("#album").is(":checked")) { jQuery("#album-show").slideDown("fast"); } else { jQuery("#album-show").slideUp("fast");	}
		});
	});
</script>
<style type="text/css">
	.totheright{float:left;width:380px;}
	h5{float:left;width:220px;padding:0;margin:0;}
	.ml-submit,.clear{clear:both;margin-top:10px;overflow:auto;}
</style>

<form id="filter" action="" method="post">
    <div class="tablenav">
        <div class="clear">
			<h5><?php _e('Select a mode','nggflow') ?></h5>
            <div class="totheright">
                <input name="mode" id="normal" type="radio" value="normal" checked="checked" /> <?php _e('Normal', 'nggflow') ;?><br />
                <input name="mode" id="tags" type="radio" value="tags" /> <?php _e('Tags', 'nggflow') ;?><br />
                <input name="mode" id="thumbs" type="radio" value="thumbs" /> <?php _e('Thumbs', 'nggflow') ;?><br />
                <input name="mode" id="random" type="radio" value="random" /> <?php _e('Random', 'nggflow') ;?><br />
                <input name="mode" id="recent" type="radio" value="recent" /> <?php _e('Recent', 'nggflow') ;?><br />
                <input name="mode" id="album" type="radio" value="album" /> <?php _e('Album', 'nggflow') ;?>
			</div>
        </div>
        <div id="norm-gallery" class="clear">
        	<h5><?php _e('Choose a gallery to insert','nggflow') ?></h5>
            <div class="totheright">
                <select id="select_gal" name="select_gal" style="width:200px;">
                    <?php
                    $gallerylist = $wpdb->get_results("SELECT * FROM $wpdb->nggallery ORDER BY gid ASC");
                    if(is_array($gallerylist)) {
                        foreach($gallerylist as $gallery) {
                            echo '<option value="'.$gallery->gid.'">'. $gallery->gid . ' - '. $gallery->title.'</option>'."\n";
                        }
                    }
                    ?>
                </select>
			</div>
        </div>
	    <div id="album-show" class="clear">
        	<h5><?php _e('Choose an album to insert','nggflow') ?></h5>
            <div class="totheright">
                <select id="select_album" name="select_album" style="width:200px;">
                    <?php
                    $albumlist = $wpdb->get_results("SELECT * FROM $wpdb->nggalbum ORDER BY id ASC");
                    if(is_array($albumlist)) {
                        foreach($albumlist as $album) {
                            echo '<option value="'.$album->id.'">'. $album->id . ' - '. $album->name.'</option>'."\n";
                        }
                    }
                    ?>
                </select>
			</div>
        </div>
    	<div id="thumbs-show" class="clear">
        	<h5><?php _e('Choose some thumbs to insert','nggflow') ?></h5>
            <div class="totheright">
                <select id="select_thumbs" multiple="multiple" size="7" name="select_thumbs[]" style="width:200px;">
                    <?php
                    $picturelist = $wpdb->get_results("SELECT * FROM $wpdb->nggpictures ORDER BY pid DESC");
                    if(is_array($picturelist)) {
                        foreach($picturelist as $picture) {
                            echo '<option value="' . $picture->pid . '" >'. $picture->pid . ' - ' . $picture->filename.'</option>'."\n";
                        }
                    }
                    ?>
                </select>
			</div>
        </div>
        <div id="tag-show" class="clear">
            <h5><?php _e('Image tags','nggflow') ?></h5>
            <div class="totheright">
                <select id="select_tags" multiple="multiple" size="7" name="select_tags[]" style="width:200px;">
                    <?php
					$param = 'hide_empty=false&orderby=name&order=asc';
                    $taglist = (array) nggTags::find_tags($param, true);
					if(is_array($taglist)) {
                        foreach($taglist as $tag) {
                            echo '<option value="' . $tag->name . '" >'. $tag->term_id . ' - ' . $tag->name.'</option>'."\n";
                        }
                    }
                    ?>
                </select>
			</div>
        </div>
        <div id="max-show" class="clear">
            <h5><?php _e('Max number of images','nggflow') ?></h5>
            <div class="totheright">
                <input type="text" size="3" maxlength="4" id="maximages" name="maximages" value="" />
			</div>
        </div>	
        <p class="ml-submit">
            <input type="submit" class="button savebutton" name="save" value="<?php attribute_escape( _e('Insert into editor','nggflow') ); ?>" />
        </p>
    </div>
    <br style="clear:both;" />
</form>
<?php
}
?>