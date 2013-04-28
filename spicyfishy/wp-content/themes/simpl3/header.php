<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="<?php
 bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<title><?php if (is_home()) { bloginfo('name'); ?><?php } elseif (is_archive() || is_page() ||is_single()) { ?> <?php wp_title(''); ?> (<?php bloginfo('name'); ?>)<?php } ?></title>
<meta http-equiv="imagetoolbar" content="no" />
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
<meta name="description" content="" />
<meta name="author" content="" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_head(); ?>
</head>
<body>

<div id="topwrap">
	<div id="header">

<ul>
                          <li <?php if(is_home()){echo 'class="current_page_item"';}?>><a href="<?php bloginfo('siteurl'); ?>/" title="Home">Home</a></li>
                          <?php wp_list_pages('title_li=&depth=1'); ?>
		</ul>



		<h1>
                          <a href="<?php echo get_settings('home'); ?>/"><?php bloginfo('name'); ?><span></span></a><span id="des"><?php bloginfo('description'); ?></span>
                </h1>

       

                         
<div id="hd-inner">
<?php include (TEMPLATEPATH . '/searchform.php'); ?>

<a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/feed.png"></a>
<a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/delicious.png"></a>
<a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/digg.png"></a>
<a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/facebook.png"></a>
<a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/twitter.png"></a>
<a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/reddit.png"></a>
<a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/stumbleupon.png"></a>
<a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/technorati.png"></a>

</div>



	</div>
</div>
<div id="wrap">
	<div id="main">
		<div id="content">
