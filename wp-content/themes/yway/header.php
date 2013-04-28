<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<meta http-equiv="Content-Type" content="<?php
 bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>"  />
<title><?php if (is_home () ) { bloginfo('name'); echo " - "; bloginfo('description'); 
} elseif (is_category() ) {single_cat_title(); echo " - "; bloginfo('name');
} elseif (is_single() || is_page() ) {single_post_title(); echo " - "; bloginfo('name');
} elseif (is_search() ) {bloginfo('name'); echo " search results: "; echo wp_specialchars($s);
} else { wp_title('',true); }?></title>
<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
<meta name="robots" content="follow, all" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/comment.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/menu.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/util.js"></script>
<?php wp_head(); ?>

<!-- this product is released under General Public License. Please see the attached file for details. You can also find details about the license at http://www.opensource.org/licenses/gpl-license.php -->


<script type="text/javascript"><!--//--><![CDATA[//><!--
sfHover = function() {
	if (!document.getElementsByTagName) return false;
	var sfEls = document.getElementById("nav").getElementsByTagName("li");

	for (var i=0; i<sfEls.length; i++) {
		sfEls[i].onmouseover=function() {
			this.className+=" sfhover";
		}
		sfEls[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
		}
	}

}
if (window.attachEvent) window.attachEvent("onload", sfHover);
//--><!]]></script>


<!--[if lt IE 8]>
<link href="<?php bloginfo('template_url'); ?>/ie.css" rel="stylesheet" type="text/css" />
<![endif]-->

<!--[if lt IE 7]>
<link href="<?php bloginfo('template_url'); ?>/ie6.css" rel="stylesheet" type="text/css" />
<script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE7.js" type="text/javascript"></script>
<![endif]-->



</head>

<body>




<div id="wrapper">

<div id="header">

  
    <div id="topright">
       <img src="<?php bloginfo('template_directory'); ?>/images/rss.jpg" align="absmiddle" title="<?php bloginfo('name'); ?>" style="padding-bottom:2px;"/>  <a href="<?php bloginfo('rss2_url'); ?>">Entries RSS </a>  |   <a href="<?php bloginfo('comments_rss2_url'); ?>"> Comments RSS</a>
    </div>
   
    <div id="logo">
        <h1><a href="<?php echo get_option('home'); ?>"><?php bloginfo('name'); ?></a></h1>
        <span><?php bloginfo('description'); ?></span>
        
    </div>


   


<div id="catnav">
   
    <ul id="nav">
      <li><a href="<?php echo get_option('home'); ?>">Blog</a></li>
     <?php wp_list_pages('depth=1&title_li=0&sort_column=menu_order'); ?>    
     </ul>
   </div> <!-- Closes catnav -->



</div> <!-- Closes header -->





<div class="cleared"></div>