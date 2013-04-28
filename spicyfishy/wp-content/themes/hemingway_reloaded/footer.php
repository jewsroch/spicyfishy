 <hr class="hide" />
<div id="footer"> 
  <div class="inside"> 

  <p class="copyright">Powered by <a href="http://startup365.com">Hemingway Reloaded</a> flavored <a href="http://wordpress.org">Wordpress</a> from <a href="http://startup365.com/">Startup Entrepreneurs</a> .</p>
    <p class="attributes"><a href="feed:<?php
 bloginfo('rss2_url'); ?>">Entries 
      RSS</a> <a href="feed:<?php bloginfo('comments_rss2_url'); ?>">Comments 
      RSS</a></p>
  </div>
</div>
<!-- [END] #footer -->
<div id="live-search"> 
  <div class="inside"> 
    <div id="search"> 
      <form method="get" id="sform" action="<?php bloginfo('home'); ?>/">
        <img src="<?php bloginfo('stylesheet_directory'); ?>/images/search.gif" alt="Search:" /> 
        <input type="text" id="q" value="<?php echo wp_specialchars($s, 1); ?>" name="s" size="15" />
      </form>
    </div>
  </div>
</div>
<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
<?php wp_footer(); ?>
</body>
</html>
