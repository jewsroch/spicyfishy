<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die(__('You are not allowed to call this page directly.', 'nggflow')); }
/**
 * NextGEN ImageFlow Widget
 *
 * Copyright (c) 2008, Boris Glumpler & ShabuShabu Webdesign
 * All rights reserved.
 *
 * @package NextGEN ImageFLow
 * @copyright 2009, Boris Glumpler & ShabuShabu Webdesign
 * @license http://www.opensource.org/licenses/gpl-2.0.php GPL License
 */
class nggImageFlowWidget extends WP_Widget
{
	function nggImageFlowWidget()
	{
		$control_ops = array( 'width' => 300, 'height' => 350, 	'id_base' => 'flow_ngg' );
		$widget_ops = array( 'classname' => 'flow_ngg', 'description' => __( 'Advanced widget that displays an ImageFlow slideshow in widget-enabled areas.', 'nggflow' ) );
		$this->WP_Widget( 'flow_ngg', __( 'NextGEN ImageFlow', 'nggflow' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance )
	{
		extract( $args, EXTR_SKIP );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __('Slideshow', 'nggflow') : $instance['title']);

		if( $instance['mode'] == 'gallery')
			$value = $instance['gallery'];
			
		elseif($instance['mode'] == 'album')
			$value = $instance['album'];
			
		elseif($instance['mode'] == 'tags')
			$value = $instance['tags'];
			
		elseif($instance['mode'] == 'recent' || $instance['mode'] == 'random')
			$value = $instance['maximages'];
			
		elseif($instance['mode'] == 'thumbs')
			$value = $instance['thumbs'];
		
		$out = nggShowFlow( $instance['mode'], $value );

		echo $before_widget;
		echo $before_title . $title . $after_title;
		echo $out;
		echo $after_widget;
	}
 
	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['mode'] = strip_tags($new_instance['mode']);
		$instance['gallery'] = (int) $new_instance['gallery'];
		$instance['album'] = (int) $new_instance['album'];
		$instance['thumbs'] = strip_tags($new_instance['thumbs']);
		$instance['tags'] = strip_tags($new_instance['tags']);
		$instance['maximages'] = (int) $new_instance['maximages'];

		return $instance;
	}
 
	function form( $instance )
	{
		global $wpdb;
		
		//Defaults
		$instance = wp_parse_args( (array) $instance, array(
			'title' => 'Slideshow',
			'mode' => 'normal',
			'gallery' => '',
			'tags' => '',
			'album' => '',
			'maximages' => 30,
			'thumbs' => ''
		));
		
		$title  = strip_tags( $instance['title'] );
		$mode  = strip_tags( $instance['mode'] );
		$gallery  = strip_tags( $instance['gallery'] );
		$album  = strip_tags( $instance['album'] );
		$tags  = strip_tags( $instance['tags'] );
		$thumbs  = strip_tags( $instance['thumbs'] );
		$maximages  = strip_tags( $instance['maximages'] );
		?>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery(".album-show,.tags-show,.max-show,.gal-show,.thumbs-show").hide();
		jQuery("").click(function(){
			if (jQuery(".album").is(":checked")) { jQuery(".album-show").slideDown("fast"); } else { jQuery(".album-show").slideUp("fast"); }
			if (jQuery(".tags").is(":checked")) { jQuery(".tags-show").slideDown("fast"); } else { jQuery(".tags-show").slideUp("fast"); }
			if (jQuery(".recent,.random").is(":checked")) { jQuery(".max-show").slideDown("fast"); } else { jQuery(".max-show").slideUp("fast"); }
			if (jQuery(".gallery").is(":checked")) { jQuery(".gal-show").slideDown("fast"); } else { jQuery(".gal-show").slideUp("fast"); }
			if (jQuery(".thumbs").is(":checked")) { jQuery(".thumbs-show").slideDown("fast"); } else { jQuery(".thumbs-show").slideUp("fast"); }
		});
	});
</script>
		<p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('mode'); ?>"><?php _e('Mode:'); ?></label><br />
            <input name="<?php echo $this->get_field_name('mode'); ?>" class="gallery" type="radio" value="gallery" <?php if( $mode == 'gallery' ) echo " checked='checked'"; ?>/> <?php _e('Gallery', 'nggflow') ;?><br />
            <input name="<?php echo $this->get_field_name('mode'); ?>" class="tags" type="radio" value="tags" <?php if( $mode == 'tags' ) echo " checked='checked'"; ?>/> <?php _e('Tags', 'nggflow') ;?><br />
            <input name="<?php echo $this->get_field_name('mode'); ?>" class="thumbs" type="radio" value="thumbs" <?php if( $mode == 'thumbs' ) echo " checked='checked'"; ?>/> <?php _e('Thumbs', 'nggflow') ;?><br />
            <input name="<?php echo $this->get_field_name('mode'); ?>" class="random" type="radio" value="random" <?php if( $mode == 'random' ) echo " checked='checked'"; ?>/> <?php _e('Random', 'nggflow') ;?><br />
            <input name="<?php echo $this->get_field_name('mode'); ?>" class="recent" type="radio" value="recent" <?php if( $mode == 'recent' ) echo " checked='checked'"; ?>/> <?php _e('Recent', 'nggflow') ;?><br />
            <input name="<?php echo $this->get_field_name('mode'); ?>" class="album" type="radio" value="album" <?php if( $mode == 'album' ) echo " checked='checked'"; ?>/> <?php _e('Album', 'nggflow') ;?>
		</p>
        <p class="gal-show">
            <label for="<?php echo $this->get_field_id('gallery'); ?>"><?php _e('Gallery:'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('gallery'); ?>" name="<?php echo $this->get_field_name('gallery'); ?>">
				<option value="" <?php if (empty( $gallery ) ) echo "selected='selected' "; ?> ><?php _e('--', 'nggflow'); ?></option>
                <?php
                $gallerylist = $wpdb->get_results("SELECT * FROM $wpdb->nggallery ORDER BY gid ASC");
                if(is_array($gallerylist)) {
                    foreach($gallerylist as $gal) {
                        echo '<option value="'.$gal->gid.'"';
						if ($gal->gid == $gallery) echo "selected='selected' ";
						echo '>'. $gal->gid . ' - '. $gal->title.'</option>'."\n";
                    }
                }
                ?>
            </select>
        </p>
		<p class="album-show">
            <label for="<?php echo $this->get_field_id('album'); ?>"><?php _e('Album:'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('album'); ?>" name="<?php echo $this->get_field_name('album'); ?>">
				<option value="" <?php if (empty( $album ) ) echo "selected='selected' "; ?> ><?php _e('--', 'nggflow'); ?></option>
				<?php
                $albumlist = $wpdb->get_results("SELECT * FROM $wpdb->nggalbum ORDER BY id ASC");
                if(is_array($albumlist)) {
                    foreach($albumlist as $alb) {
                        echo '<option value="'.$alb->id.'"';
						if ($alb->id == $instance['album']) echo "selected='selected' ";
						echo '>'. $alb->id . ' - '. $alb->name.'</option>'."\n";
                    }
                }
                ?>
            </select>
		</p>
		<p class="thumbs-show">
            <label for="<?php echo $this->get_field_id('thumbs'); ?>"><?php _e('Thumbs:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('thumbs'); ?>" name="<?php echo $this->get_field_name('thumbs'); ?>" type="text" value="<?php echo $thumbs; ?>" />
		</p>
		<p class="tags-show">
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>" type="text" value="<?php echo $tags; ?>" />
		</p>
		<p class="max-show">
            <label for="<?php echo $this->get_field_id('maximages'); ?>"><?php _e('Max. images:', 'nggflow'); ?></label>
            <input id="<?php echo $this->get_field_id('maximages'); ?>" name="<?php echo $this->get_field_name('maximages'); ?>" type="text" style="padding: 3px; width: 45px;" value="<?php echo $maximages; ?>" />
        </p>
	<?php		
	}
} // End class
add_action('widgets_init', create_function('', 'return register_widget("nggImageFlowWidget");'));
?>