
<div id="footer" class="fullspan">

	<div class="container_16">
		<div class="grid_8 powered">
			<p>&copy; <?php
 the_time('Y'); ?> <?php bloginfo(); ?>. Powered by <a href="http://www.wordpress.org" title="WordPress">WordPress</a></p>
		</div>
        <div class="grid_8 omega credit">
			<p><a href="http://www.woothemes.com">Snapshot Theme</a> by <a href="http://www.woothemes.com" title="WooThemes - Premium WordPress Themes"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/design/woothemes.png" alt="WooThemes - Premium Wordpress Themes" /></a></p>
		</div>
	</div><!-- /container_16 -->

</div><!-- /footer -->

</div><!-- /wrap -->

<?php wp_footer(); ?>

<?php if ( get_option('woo_google_analytics') <> "" ) { echo stripslashes(get_option('woo_google_analytics')); } ?>

</body>
</html>