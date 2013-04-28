	<div id="sidebar-one">
<?php
 if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Right Sidebar') ) : else : ?>


        <ul id="ads">
			<li><a href="http://addlink.com/"><img src="<?php bloginfo('template_directory'); ?>/images/advertise.gif" width="125" height="125" alt="Advertise" /></a></li>
			
                        <li><a href="http://addlink.com/"><img src="<?php bloginfo('template_directory'); ?>/images/advertise.gif" width="125" height="125" alt="Advertise" /></a></li>

                        <li><a href="http://addlink.com/"><img src="<?php bloginfo('template_directory'); ?>/images/advertise.gif" width="125" height="125" alt="Advertise" /></a></li>

                        <li><a href="http://addlink.com/"><img src="<?php bloginfo('template_directory'); ?>/images/advertise.gif" width="125" height="125" alt="Advertise" /></a></li>
		</ul>



		<h3>Recent Posts</h3>
		<ul>
			<?php get_archives('postbypost','10','custom','<li>','</li>'); ?>
		</ul>

		<h3>Categories</h3>
		<ul>
			<?php wp_list_cats('sort_column=name&optioncount=0&hierarchical=0'); ?>
		</ul>

                <h3>Archives</h3>
		<ul>
        		<?php wp_get_archives('type=monthly'); ?>
		</ul>

        
		
<?php endif; ?>

	</div>