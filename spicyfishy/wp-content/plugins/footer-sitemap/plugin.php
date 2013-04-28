<?php

/*
Plugin Name: Footer Sitemap
Plugin URI: http://consulting.dynamisart.com
Description: The Footer Sitemap plugin generates a footer sitemap, listing pages, categories, archives by year and customizable Terms of Use and Powered by fields, changes to the Terms of Use and Powered by text areas can be made in Settings >> Footer Sitemap Options.
Version: 1.4
Author: Sean Ham, Dynamis Consulting
Author URI: http://consulting.dynamisart.com
Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
*/

/*	Copyright 2008-2010  Dynamis  (email : sean@dynamisart.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
$fsitemap_options = get_option('fsitemap');

add_action('admin_menu', 'fsitemap_admin_menu');
function fsitemap_admin_menu()
{
    add_options_page('Footer Sitemap Options', 'Footer Sitemap Options', 'manage_options', 'footer-sitemap/options.php');
}

add_action('wp_footer', 'sitemap_wp_footer');
function sitemap_wp_footer()
{
     global $fsitemap_options;
    
   echo '
<div id="footersitemap">  
<div id="sitemapfooter-nav"> 
<dl id="sitemapfooter-pages" class="first-col"> 
<dt><label><b>Pages</b></label></dt> 
<dd>';

echo wp_list_pages('title_li=' );

echo '</dd> </dl>';
 
echo '<dl id="sitemapfooter-cats"><dt><label><b>Categories</b></label></dt><dd>';

echo wp_list_categories('title_li=' );

echo '</dd> </dl>';

echo '<dl id="sitemapfooter-archives"> 
<dt><label><b>Archives</b></label></dt> 
<dd>';

echo wp_get_archives('type=yearly&limit=12');


echo '</dd> 
</dl> 
</div><!--end footer-nav--> 

<div id="sitemapfooter-legal"> 
 
<div id="sitemapfooter-copyright"> 
<font style="font-size:12px;"><label><b>Terms of Use</b></label></font><br/>';

echo $fsitemap_options['termsofuse'];

echo '
 
</div><!--end footer-copyright--> 
 
<div id="sitemapfooter-logo"> 
<label>Powered by:</label><br>';
echo $fsitemap_options['poweredby'];

echo '</div><!--end footer-logo--> 
 
</div><!--end footer-legal--> 
 
</div><!--end footer-->';
}

add_action('wp_head', 'sitemap_wp_stylesheet');
function sitemap_wp_stylesheet()
{
    
    echo '<link rel="stylesheet" href="' . site_url() . '/wp-content/plugins/footer-sitemap/css/style.css' .'" type="text/css" media="screen" /> ';

}



?>