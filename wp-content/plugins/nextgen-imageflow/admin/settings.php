<?php  
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

function showImageFlowPage()
{	
	global $wpdb, $wp_version, $nggRewrite, $nggflow;

	// same as $_SERVER['REQUEST_URI'], but should work under IIS 6.0
	$filepath    = admin_url() . 'admin.php?page='.$_GET['page'];

	if ( isset($_POST['updateoption']) )
	{	
		check_admin_referer('ngg_settings');
		
		// get the hidden option fields, taken from WP core
		if( $_POST['page_options'] )	
			$options = explode(',', stripslashes($_POST['page_options']));
			
		if( $options )
		{
			foreach( $options as $option )
			{
				$option = trim( $option );
				$value = trim( $_POST[$option] );
				$nggflow->options[$option] = $value;
			}
		}
		// Save options
		update_option('ngg_if_options', $nggflow->options);
		
	 	nggGallery::show_message( __( 'Update Successfully','nggallery' ) );
	}

	if( isset( $_POST['resetdefault'] ) )
	{	
		check_admin_referer('ngg_uninstall');

			include_once ( dirname (__FILE__).  '/install.php');
			
			ngg_if_default_options();
			$nggflow->load_options();

			nggGallery::show_message(__('Reset all settings to default parameter','nggallery'));
	}

	if (isset($_POST['uninstall'])) {	
		check_admin_referer('ngg_uninstall');

			include_once ( dirname (__FILE__).  '/install.php');
			nggflow_uninstall();

			nggGallery::show_message(__('Uninstall sucessfull ! Now delete the plugin and enjoy your life ! Good luck !','nggallery'));
	}
	
	// message windows
	if(!empty($messagetext)) { echo '<!-- Last Action --><div id="message" class="updated fade"><p>'.$messagetext.'</p></div>'; }

	?>

	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('.picker').ColorPicker({
				onSubmit: function(hsb, hex, rgb, el) {
					jQuery(el).val(hex);
					jQuery(el).ColorPickerHide();
				},
				onBeforeShow: function () {
					jQuery(this).ColorPickerSetColor(this.value);
				}
			})
			.bind('keyup', function(){
				jQuery(this).ColorPickerSetColor(this.value);
			});
		});
		jQuery(function() {
			jQuery('#slider').tabs({ fxFade: true, fxSpeed: 'fast' });	
		});
	</script>
	
	<div id="slider" class="wrap">
	
		<ul id="tabs">
			<li><a href="#if_general"><?php _e('General Settings', 'nggflow') ;?></a></li>
			<li><a href="#if_setup"><?php _e('Setup', 'nggflow') ;?></a></li>
			<li><a href="#if_help"><?php _e('Help', 'nggflow') ;?></a></li>
			<li><a href="#if_credits"><?php _e('Credits', 'nggflow') ;?></a></li>
			<li><a href="#if_donation"><?php _e('Donate', 'nggflow') ;?></a></li>
		</ul>

		<!-- General settings -->
		<div id="if_general">
            <form name="general" method="post" action="<?php echo $filepath.'#if_general'; ?>" >
                <?php wp_nonce_field('ngg_settings') ?>
                <input type="hidden" name="page_options" value="ngg_if_aspectRatio,ngg_if_buttons,ngg_if_captions,ngg_if_imageCursor,ngg_if_imageFocusM,ngg_if_imageFocusMax,ngg_if_imageScaling,ngg_if_imagesHeight,ngg_if_imagesM,ngg_if_onClick,ngg_if_opacity,ngg_if_opacityArray,ngg_if_percentLandscape,ngg_if_percentOther,ngg_if_preloadImages,ngg_if_preloadImagesText,ngg_if_reflections,ngg_if_reflectionGET,ngg_if_reflectionP,ngg_if_reflectionPNG,ngg_if_scrollbarP,ngg_if_slider,ngg_if_sliderCursor,ngg_if_sliderWidth,ngg_if_startID,ngg_if_startAnimation,ngg_if_xStep,ngg_if_animationSpeed,ngg_if_singleItemTag,ngg_if_slideshow,ngg_if_slideshowInterval,ngg_if_slideshowLeftToRight,ngg_if_cycle,ngg_if_use_style,galShowFlow,galTextFlow,ngg_if_default" />
                <h2><?php _e('General Settings','nggflow'); ?></h2>
                    <table class="form-table">
                        <tr>
                            <th><?php _e('Aspect Ratio','nggflow') ?>:<br /><code>if_aspectRatio</code></th>
                            <td><input type="text" size="4" maxlength="5" id="ngg_if_aspectRatio" name="ngg_if_aspectRatio" value="<?php echo $nggflow->options['ngg_if_aspectRatio']; ?>" /> <small>(<?php _e('Aspect ratio of the ImageFlow container (width divided by height)', 'nggflow'); ?>)</small></td>
                        </tr>
                        <tr>
                            <th><?php _e('Show the buttons?','nggflow') ?>:<br /><code>if_buttons</code></th>
                            <td>
                                <select size="1" id="ngg_if_buttons" name="ngg_if_buttons">
                                    <option value="true" <?php selected( 'true', $nggflow->options['ngg_if_buttons']); ?> ><?php _e('Yes', 'nggflow') ;?></option>
                                    <option value="false" <?php selected( 'false', $nggflow->options['ngg_if_buttons']); ?> ><?php _e('No', 'nggflow') ;?></option>
                                </select> <small>(<?php _e('Sets whether to show the buttons or not.', 'nggflow'); ?>)</small>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Captions','nggflow') ?>:<br /><code>if_captions</code></th>
                            <td>
                                <select size="1" id="ngg_if_captions" name="ngg_if_captions">
                                    <option value="true" <?php selected( 'true', $nggflow->options['ngg_if_captions']); ?> ><?php _e('Yes', 'nggflow') ;?></option>
                                    <option value="false" <?php selected( 'false', $nggflow->options['ngg_if_captions']); ?> ><?php _e('No', 'nggflow') ;?></option>
                                </select> <small>(<?php _e('Disables / enables captions', 'nggflow'); ?>)</small>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Image Cursor Type','nggflow') ?>:<br /><code>if_imageCursor</code></th>
                            <td><input type="text" size="30" id="ngg_if_imageCursor" name="ngg_if_imageCursor" value="<?php echo $nggflow->options['ngg_if_imageCursor']; ?>" /> <small>(<?php _e('Sets the cursor type for all images. Default is "pointer".', 'nggflow'); ?>)</small></td>
                        </tr>				
                        <tr>
                            <th><?php _e('Image Focus','nggflow') ?>:<br /><code>if_imageFocusM</code></th>
                            <td><input type="text" size="3" maxlength="3" id="ngg_if_imageFocusM" name="ngg_if_imageFocusM" value="<?php echo $nggflow->options['ngg_if_imageFocusM']; ?>" /> <small>(<?php _e('Multiplicator for the focussed image size in percent (1.0 = 100%, 0 = 0%).', 'nggflow'); ?>)</small></td>
                        </tr>
                        <tr>
                            <th><?php _e('Image Focus Max','nggflow') ?>:<br /><code>if_imageFocusMax</code></th>
                            <td><input type="text" size="3" maxlength="3" id="ngg_if_imageFocusMax" name="ngg_if_imageFocusMax" value="<?php echo $nggflow->options['ngg_if_imageFocusMax']; ?>" /> <small>(<?php _e('Max number of images on each side of the focussed one.', 'nggflow'); ?>)</small></td>
                        </tr>
                        <tr>
                            <th><?php _e('Image Scaling','nggflow') ?>:<br /><code>if_imageScaling</code></th>
                            <td>
                                <select size="1" id="ngg_if_imageScaling" name="ngg_if_imageScaling">
                                    <option value="true" <?php selected( 'true', $nggflow->options['ngg_if_imageScaling']); ?> ><?php _e('Yes', 'nggflow') ;?></option>
                                    <option value="false" <?php selected( 'false', $nggflow->options['ngg_if_imageScaling']); ?> ><?php _e('No', 'nggflow') ;?></option>
                                </select> <small>(<?php _e('Toggle image scaling.', 'nggflow'); ?>)</small>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Image Height','nggflow') ?>:<br /><code>if_imagesHeight</code></th>
                            <td><input type="text" size="4" maxlength="5" id="ngg_if_imagesHeight" name="ngg_if_imagesHeight" value="<?php echo $nggflow->options['ngg_if_imagesHeight']; ?>" /> <small>(<?php _e('Height of the images div container in percent', 'nggflow'); ?>)</small></td>
                        </tr>
                        <tr>
                            <th><?php _e('Image Multiplicator','nggflow') ?>:<br /><code>if_imagesM</code></th>
                            <td><input type="text" size="4" maxlength="5" id="ngg_if_imagesM" name="ngg_if_imagesM" value="<?php echo $nggflow->options['ngg_if_imagesM']; ?>" /> <small>(<?php _e('Multiplicator for all images in percent', 'nggflow'); ?>)</small></td>
                        </tr>
                        <tr>
                            <th><?php _e('onClick Behaviour','nggflow') ?>:<br /><code>if_onClick</code></th>
                            <td>
                                <select id="if_choose_onClick">
                                    <option onclick="jQuery('input#ngg_if_onClick').val('function() { document.location = this.url; };');"><?php _e('Default', 'nggflow') ;?></option>
                                    <option onclick="jQuery('input#ngg_if_onClick').val('function() { return hs.expand(this,{ src: this.getAttribute(\'longdesc\')})};');"><?php _e('Highslide', 'nggflow') ;?></option>
                                    <option onclick="jQuery('input#ngg_if_onClick').val('function() { window.open(this.url, \'_blank\')};');"><?php _e('New Window', 'nggflow') ;?></option>
                                    <option onclick="jQuery('input#ngg_if_onClick').val('function() { window.open(this.url, \'_blank\', \'width=500,height=500,left=200,top=200\')};');"><?php _e('New Window (scaled)', 'nggflow') ;?></option>
                                  </select><br />
                                <input type="text" size="100" id="ngg_if_onClick" name="ngg_if_onClick" value="<?php echo stripcslashes( $nggflow->options['ngg_if_onClick'] ); ?>" /><br />
                                <small>(<?php _e('The effect to open up the focused image with.', 'nggflow'); ?>)</small>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Opacity','nggflow') ?>:<br /><code>if_opacity</code></th>
                            <td>
                                <select size="1" id="ngg_if_opacity" name="ngg_if_opacity">
                                    <option value="true" <?php selected( 'true', $nggflow->options['ngg_if_opacity']); ?> ><?php _e('Yes', 'nggflow') ;?></option>
                                    <option value="false" <?php selected( 'false', $nggflow->options['ngg_if_opacity']); ?> ><?php _e('No', 'nggflow') ;?></option>
                                </select> <small>(<?php _e('Disables / enables image opacity', 'nggflow'); ?>)</small>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Image Opacity Range','nggflow') ?>:<br /><code>if_opacityArray</code></th>
                            <td><input type="text" size="30" id="ngg_if_opacityArray" name="ngg_if_opacityArray" value="<?php echo $nggflow->options['ngg_if_opacityArray']; ?>" /> <small>(<?php _e('Image opacity range - first value is for the focussed image (0 = 100% opacity, 10 = 0% opacity)', 'nggflow'); ?>)</small></td>
                        </tr>				
                        <tr>
                            <th><?php _e('Landscape Format','nggflow') ?>:<br /><code>if_percentLandscape</code></th>
                            <td><input type="text" size="4" maxlength="5" id="ngg_if_percentLandscape" name="ngg_if_percentLandscape" value="<?php echo $nggflow->options['ngg_if_percentLandscape']; ?>" /> <small>(<?php _e('Scale landscape format', 'nggflow'); ?>)</small></td>
                        </tr>
                        <tr>
                            <th><?php _e('Portrait &amp; Square Format','nggflow') ?>:<br /><code>if_percentOther</code></th>
                            <td><input type="text" size="4" maxlength="5" id="ngg_if_percentOther" name="ngg_if_percentOther" value="<?php echo $nggflow->options['ngg_if_percentOther']; ?>" /> <small>(<?php _e('Scale portrait and square format', 'nggflow'); ?>)</small></td>
                        </tr>
                        <tr>
                            <th><?php _e('Preload Images','nggflow') ?>:<br /><code>if_preloadImages</code></th>
                            <td>
                                <select size="1" id="ngg_if_preloadImages" name="ngg_if_preloadImages">
                                    <option value="true" <?php selected( 'true', $nggflow->options['ngg_if_preloadImages']); ?> ><?php _e('Yes', 'nggflow') ;?></option>
                                    <option value="false" <?php selected( 'false', $nggflow->options['ngg_if_preloadImages']); ?> ><?php _e('No', 'nggflow') ;?></option>
                                </select> <small>(<?php _e('Disables / enables the loading bar and image preloading.', 'nggflow'); ?>)</small>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Preload Image Text','nggflow') ?>:<br /><code>if_preloadImagesText</code></th>
                            <td><input type="text" size="4" id="ngg_if_preloadImagesText" name="ngg_if_preloadImagesText" value="<?php echo $nggflow->options['ngg_if_preloadImagesText']; ?>" /> <small>(<?php _e('Text to be shown on preload', 'nggflow'); ?>)</small></td>
                        </tr>
                        <tr>
                            <th><?php _e('Use reflections?','nggflow') ?>:<br /><code>if_reflections</code></th>
                            <td>
                                <select size="1" id="ngg_if_reflections" name="ngg_if_reflections">
                                    <option value="true" <?php selected( 'true', $nggflow->options['ngg_if_reflections']); ?> ><?php _e('Yes', 'nggflow') ;?></option>
                                    <option value="false" <?php selected( 'false', $nggflow->options['ngg_if_reflections']); ?> ><?php _e('No', 'nggflow') ;?></option>
                                </select> <small>(<?php _e('Sets whether to use the reflection or not.', 'nggflow'); ?>)</small>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('GET Variables','nggflow') ?>:<br /><code>if_reflectionGET</code></th>
                            <td><input type="text" size="40" id="ngg_if_reflectionGET" name="ngg_if_reflectionGET" value="<?php echo $nggflow->options['ngg_if_reflectionGET']; ?>" /> <small>(<?php _e('Pass variables via the GET method to the reflect_.php script. Possible values: bgc, fade_start, fade_end, tint. Example: &bgc=ffffff&fade_start=20%', 'nggflow'); ?>)</small></td>
                        </tr>
                        <tr>
                            <th><?php _e('Reflection Height','nggflow') ?>:<br /><code>if_reflectionP</code></th>
                            <td><input type="text" size="3" maxlength="4" id="ngg_if_reflectionP" name="ngg_if_reflectionP" value="<?php echo $nggflow->options['ngg_if_reflectionP']; ?>" /> <small>(<?php _e('Sets the height of the reflection in percent of the source image.', 'nggflow'); ?>)</small></td>
                        </tr>
                        <tr>
                            <th><?php _e('Use a PNG as reflection','nggflow') ?>:<br /><code>if_reflectionPNG</code></th>
                            <td>
                                <select size="1" id="ngg_if_reflectionPNG" name="ngg_if_reflectionPNG">
                                    <option value="2" <?php selected( 2, $nggflow->options['ngg_if_reflectionPNG']); ?> ><?php _e('No (reflect2.php)', 'nggflow') ;?></option>
                                    <option value="3" <?php selected( 3, $nggflow->options['ngg_if_reflectionPNG']); ?> ><?php _e('Yes (reflect3.php)', 'nggflow') ;?></option>
                                </select> <small>(<?php _e('Switches from reflect2.php to reflect3.php for PNG transparency support.', 'nggflow'); ?>)</small>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Scrollbar Width','nggflow') ?>:<br /><code>if_scrollbarP</code></th>
                            <td><input type="text" size="4" maxlength="5" id="ngg_if_scrollbarP" name="ngg_if_scrollbarP" value="<?php echo $nggflow->options['ngg_if_scrollbarP']; ?>" /> <small>(<?php _e('Width of the scrollbar in percent', 'nggflow'); ?>)</small></td>
                        </tr>
                        <tr>
                            <th><?php _e('Scrollbar','nggflow') ?>:<br /><code>if_slider</code></th>
                            <td>
                                <select size="1" id="ngg_if_slider" name="ngg_if_slider">
                                    <option value="true" <?php selected( 'true', $nggflow->options['ngg_if_slider']); ?> ><?php _e('Yes', 'nggflow') ;?></option>
                                    <option value="false" <?php selected( 'false', $nggflow->options['ngg_if_slider']); ?> ><?php _e('No', 'nggflow') ;?></option>
                                </select> <small>(<?php _e('Disables / enables the scrollbar', 'nggflow'); ?>)</small>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Slider Cursor','nggflow') ?>:<br /><code>ngg_if_sliderCursor</code></th>
                            <td><input type="text" size="30" id="ngg_if_sliderCursor" name="ngg_if_sliderCursor" value="<?php echo $nggflow->options['ngg_if_sliderCursor']; ?>" /> <small>(<?php _e('Sets the slider cursor type. Default is "e-resize".', 'nggflow'); ?>)</small></td>
                        </tr>				
                        <tr>
                            <th><?php _e('Slider Width','nggflow') ?>:<br /><code>ngg_if_sliderWidth</code></th>
                            <td><input type="text" size="3" maxlength="4" id="ngg_if_sliderWidth" name="ngg_if_sliderWidth" value="<?php echo $nggflow->options['ngg_if_sliderWidth']; ?>" /> <small>(<?php _e('Sets the width of the slider div in px. Do not change unless you replace the slider image!', 'nggflow'); ?>)</small></td>
                        </tr>
                        <tr>
                            <th><?php _e('Slideshow Starting Point','nggflow') ?>:<br /><code>if_startID</code></th>
                            <td><input type="text" size="3" maxlength="4" id="ngg_if_startID" name="ngg_if_startID" value="<?php echo $nggflow->options['ngg_if_startID']; ?>" /> <small>(<?php _e('Sets which image the slideshow will start with.', 'nggflow'); ?>)</small></td>
                        </tr>
                        <tr>
                            <th><?php _e('Start Animation','nggflow') ?>:<br /><code>if_startAnimation</code></th>
                            <td>
                                <select size="1" id="ngg_if_startAnimation" name="ngg_if_startAnimation">
                                    <option value="true" <?php selected( 'true', $nggflow->options['ngg_if_startAnimation']); ?> ><?php _e('Yes', 'nggflow') ;?></option>
                                    <option value="false" <?php selected( 'false', $nggflow->options['ngg_if_startAnimation']); ?> ><?php _e('No', 'nggflow') ;?></option>
                                </select> <small>(<?php _e('Animate images moving in from the right on startup', 'nggflow'); ?>)</small>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Step Width','nggflow') ?>:<br /><code>if_xStep</code></th>
                            <td><input type="text" size="3" maxlength="4" id="ngg_if_xStep" name="ngg_if_xStep" value="<?php echo $nggflow->options['ngg_if_xStep']; ?>" />px <small>(<?php _e('Step width on the x-axis in px.', 'nggflow'); ?>)</small></td>
                        </tr>
                        <tr>
                            <th><?php _e('Animation Speed','nggflow') ?>:<br /><code>if_animationSpeed</code></th>
                            <td><input type="text" size="3" maxlength="5" id="ngg_if_animationSpeed" name="ngg_if_animationSpeed" value="<?php echo $nggflow->options['ngg_if_animationSpeed']; ?>" /> <small>(<?php _e('Time in milliseconds for one transition.', 'nggflow'); ?>)</small></td>
                        </tr>
                        <tr>
                            <th><?php _e('Single Item Tag','nggflow') ?>:<br /><code>if_singleItemTag</code></th>
                            <td><input type="text" id="ngg_if_singleItemTag" name="ngg_if_singleItemTag" value="<?php echo $nggflow->options['ngg_if_singleItemTag']; ?>" /> <small>(<?php _e('tagname which will wrap a single item (default IMG, you may want to set it e.g. LI).', 'nggflow'); ?>)</small></td>
                        </tr>
                        <tr>
                            <th><?php _e('Enable Slideshow','nggflow') ?>:<br /><code>if_slideshow</code></th>
                            <td>
                                <select size="1" id="ngg_if_slideshow" name="ngg_if_slideshow">
                                    <option value="true" <?php selected( 'true', $nggflow->options['ngg_if_slideshow']); ?> ><?php _e('Yes', 'nggflow') ;?></option>
                                    <option value="false" <?php selected( 'false', $nggflow->options['ngg_if_slideshow']); ?> ><?php _e('No', 'nggflow') ;?></option>
                                </select> <small>(<?php _e('Set to \'Yes\' to enable the slideshow feature.', 'nggflow'); ?>)</small>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Slideshow Interval','nggflow') ?>:<br /><code>if_slideshowInterval</code></th>
                            <td><input type="text" size="4" maxlength="5" id="ngg_if_slideshowInterval" name="ngg_if_slideshowInterval" value="<?php echo $nggflow->options['ngg_if_slideshowInterval']; ?>" /> <small>(<?php _e('Time in milliseconds for holding one image.', 'nggflow'); ?>)</small></td>
                        </tr>
                        <tr>
                            <th><?php _e('Slideshow Direction','nggflow') ?>:<br /><code>if_slideshowLeftToRight</code></th>
                            <td>
                                <select size="1" id="ngg_if_slideshowLeftToRight" name="ngg_if_slideshowLeftToRight">
                                    <option value="true" <?php selected( 'true', $nggflow->options['ngg_if_slideshowLeftToRight']); ?> ><?php _e('Left to right', 'nggflow') ;?></option>
                                    <option value="false" <?php selected( 'false', $nggflow->options['ngg_if_slideshowLeftToRight']); ?> ><?php _e('Right to left', 'nggflow') ;?></option>
                                </select> <small>(<?php _e('Cycle images from left to right or right to left', 'nggflow'); ?>)</small>
                            </td>
                        </tr>
                         <tr>
                            <th><?php _e('Cycle Slideshow?','nggflow') ?>:<br /><code>if_cycle</code></th>
                            <td>
                                <select size="1" id="ngg_if_cycle" name="ngg_if_cycle">
                                    <option value="true" <?php selected( 'true', $nggflow->options['ngg_if_cycle']); ?> ><?php _e('Yes', 'nggflow') ;?></option>
                                    <option value="false" <?php selected( 'false', $nggflow->options['ngg_if_cycle']); ?> ><?php _e('No', 'nggflow') ;?></option>
                                </select> <small>(<?php _e('Create cyclic image flow: Mousewheel and Buttons will be able to switch from last to first and vice versa', 'nggflow'); ?>)</small>
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Use the stylesheet?','nggflow') ?>:</th>
                            <td>
                            <select size="1" id="ngg_if_use_style" name="ngg_if_use_style">
                                <option value="true" <?php selected( 'true', $nggflow->options['ngg_if_use_style']); ?> ><?php _e('Yes', 'nggflow') ;?></option>
                                <option value="false" <?php selected( 'false', $nggflow->options['ngg_if_use_style']); ?> ><?php _e('No', 'nggflow') ;?></option>
                            </select> <small>(<?php _e('Sets whether to use the stylesheet or not. Set to no if you want to copy the styles to your themes style.css.', 'nggflow'); ?>)</small></td>
                        </tr>
                        <tr>
                            <th valign="top"><?php _e('Integrate ImageFlow','nggflow') ?>:</th>
                            <td><input name="galShowFlow" type="checkbox" value="1" <?php checked('1', $nggflow->options['galShowFlow']); ?> />
                                <input type="text" name="galTextFlow" value="<?php echo $nggflow->options['galTextFlow'] ?>" size="50" />
                            </td>
                        </tr>
                        <tr>
                            <th><?php _e('Use ImageFlow as default slideshow?','nggflow') ?>:</th>
                            <td>
                            <select size="1" id="ngg_if_default" name="ngg_if_default">
                                <option value="true" <?php selected( 'true', $nggflow->options['ngg_if_default']); ?> ><?php _e('Yes', 'nggflow') ;?></option>
                                <option value="false" <?php selected( 'false', $nggflow->options['ngg_if_default']); ?> ><?php _e('No', 'nggflow') ;?></option>
                            </select> <small>(<?php _e('Chose yes to substitute Imagerotator with ImageFlow.', 'nggflow'); ?>)</small></td>
                        </tr>
                    </table>
                    <div class="clear"> &nbsp; </div>
                    <div class="submit"><input type="submit" name="updateoption" value="<?php _e('Update') ;?> &raquo;"/></div>
            </form>
		</div>
		
		<!-- Setup -->
		<div id="if_setup">
            <div class="wrap">
                <h2><?php _e('Reset options', 'nggallery') ;?></h2>
                    <form name="resetsettings" method="post">
                        <?php wp_nonce_field('ngg_uninstall') ?>
                        <p><?php _e('Reset all options/settings to the default installation.', 'nggallery') ;?></p>
                        <div align="center"><input type="submit" class="button" name="resetdefault" value="<?php _e('Reset settings', 'nggallery') ;?>" onclick="javascript:check=confirm('<?php _e('Reset all options to default settings ?\n\nChoose [Cancel] to Stop, [OK] to proceed.\n','nggallery'); ?>');if(check==false) return false;" /></div>
                    </form>
            </div>
    
            <div class="wrap">
            <h2><?php _e('Uninstall options from the database', 'nggflow') ;?></h2>
            
                <form name="resetsettings" method="post">
                    <?php wp_nonce_field('ngg_uninstall') ?>
                    <p><?php _e('You don\'t like NextGEN ImageFlow?', 'nggflow') ;?></p>
                    <p><?php _e('No problem, before you deactivate this plugin press the Uninstall Button, because deactivating NextGEN ImageFlow does not remove any data that may have been created. ', 'nggflow') ;?></p>
                    <div align="center"><input type="submit" name="uninstall" class="button delete" value="<?php _e('Uninstall plugin', 'nggallery') ?>" onclick="javascript:check=confirm('<?php _e('You are about to Uninstall this plugin from WordPress.\nThis action is not reversible.\n\nChoose [Cancel] to Stop, [OK] to Uninstall.\n','nggallery'); ?>');if(check==false) return false;"/></div>
                </form>
            </div>
		</div>

		<!-- Help -->
		<div id="if_help">
			<h2><?php _e('Custom Settings','nggflow'); ?></h2>
			<p><?php _e('Below all the different settings you can find the custom field names, for example <code>if_background_color</code>, with which you can override the default settings. All you need to do is enter them as a new custom field for a certain post and add new values. Only Settings that display a custom field below them can be changed!!','nggflow'); ?></p>
			<h2><?php _e('Shortcodes','nggflow'); ?></h2>
			<p><?php _e('All the following tags can be used on posts and pages','nggflow'); ?></p>
			<p>
			<code>[imageflow <span class="blue">id</span>=""]</code><br />
			<code>[if-tags <span class="green">tags</span>=""]</code><br />
			<code>[if-thumb <span class="orange">id</span>=""]</code><br />
			<code>[if-rand <span class="red">max</span>=""]</code><br />
			<code>[if-recent <span class="red">max</span>=""]</code><br />
			<code>[if-album <span class="antra">id</span>=""]</code><br />
            <ul class="help">
                <li><?php _e('<span class="blue">id</span> is the gallery id.','nggflow'); ?></li>
                <li><?php _e('<span class="green">tags</span> is a comma seperated list of image tags','nggflow'); ?></li>
                <li><?php _e('<span class="orange">id</span> are image IDs','nggflow'); ?></li>
				<li><?php _e('<span class="red">max</span> is the maximum amount of mages retrieved','nggflow'); ?></li>
                <li><?php _e('<span class="antra">id</span> is the album id','nggflow'); ?></li>
                <li><?php _e('Example: <em>[if-album id="3"]</em>','nggflow'); ?></li>
			</ul></p>

			<h2><?php _e('Template tags','nggflow'); ?></h2>
			<p><?php _e('If you want to show ImageFlow outside a post or a page (in a template), then you can use any of the following template tags. Have a look at the shortcode descriptions to see what all the variables mean.','nggflow'); ?></p>
			<ul class="help">
				<li><code>&lt;?php imageflow_gallery( $id ); ?&gt;</code></li>
				<li><code>&lt;?php imageflow_tags( $tags ); ?&gt;</code></li>
				<li><code>&lt;?php imageflow_album( $id ); ?&gt;</code></li>
				<li><code>&lt;?php imageflow_recent( $max ); ?&gt;</code></li>
				<li><code>&lt;?php imageflow_random( $max ); ?&gt;</code></li>
				<li><code>&lt;?php imageflow_thumbs( $id ); ?&gt;</code></li>
				<li><?php _e('Example:','nggflow'); ?></li> <em>&lt;?php imageflow_album( 3 ); ?&gt;</em></li>
			</ul>
		</div>

		<!-- Credits -->
		<div id="if_credits">
			<h2><?php _e('Credits','nggflow'); ?></h2>
			<p><?php _e('As always, when something has been created there are people to thank. This is that list:','nggflow'); ?></p>
				<p><a href="http://www.maiermartin.de/" title="<?php _e('for the idea for this plugin','nggflow'); ?>">Martin Maier</a>, <a href="http://194.95.111.244/~countzero/myCMS/index.php?c_id=5&s_id=21" title="<?php _e('for ImageFlow','nggflow'); ?>">Finn Rudolph</a>, <a href="http://alexrabe.boelinger.com/" title="<?php _e('for the great NextGEN Gallery','nggflow'); ?>">Alex Rabe</a>, <a href="http://es-xchange.com/" title="<?php _e('for the Spanish language files','nggflow'); ?>">Karin</a>, <a href="http://gidibao.net/" title="<?php _e('for the Italian language files','nggflow'); ?>">Gianni Diurno</a>, <a href="http://wordpress.blogos.dk/" title="<?php _e('for the Danish language files','nggflow'); ?>">Adamson</a></p>
		</div>

		<!-- Donate -->
		<div id="if_donation">
			<h2><?php _e('Donate','nggflow'); ?></h2>
			<p><?php _e('We spend a lot of time and effort on implementing new features and on the maintenance of this plugin, so if you feel generous and have a few bucks to spare, then please consider to donate.','nggflow'); ?></p>
			<p><?php _e('Click on the button below and you will be redirected to the PayPal site where you can make a safe donation','nggflow'); ?></p>
			<p>
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" >
                    <input type="hidden" name="cmd" value="_xclick"/><input type="hidden" name="business" value="mail@shabushabu-webbdesign.com"/>
                    <input type="hidden" name="item_name" value="<?php _e('NextGEN ImageFlow Plugin @ http://shabushabu-webdesign.com','nggflow'); ?>"/>
                    <input type="hidden" name="no_shipping" value="1"/><input type="hidden" name="return" value="http://shabushabu-webdesign.com/" />
                    <input type="hidden" name="cancel_return" value="http://shabushabu-webdesign.com/"/>
                    <input type="hidden" name="lc" value="US" /> 
                    <input type="hidden" name="currency_code" value="USD"/>
                    <input type="hidden" name="tax" value="0"/>
                    <input type="hidden" name="bn" value="PP-DonationsBF"/>
                    <input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but21.gif" name="submit" alt="<?php _e('Make payments with PayPal - it\'s fast, free and secure!','nggflow'); ?>" style="border: none;"/>
				</form>
			</p>
			<p><?php _e('Thank you and all the best!','nggflow'); ?><br />ShabuShabu Webdesign Team</p>
		</div>
	</div>	
	<?php
}
?>