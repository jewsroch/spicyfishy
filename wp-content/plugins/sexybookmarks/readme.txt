=== SexyBookmarks ===
Contributors: eight7teen, normanyung
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3415856
Tags: sexy,social bookmarking,bookmarks menu,digg,delicious,furl,myspace,twitter,facebook,technorati,reddit,yahoo 
Requires at least: 2.7
Tested up to: 2.7.1
Stable tag: 2.5.1

Adds a social bookmarking menu to your posts, pages, index, or any combination of the three.

== Description ==

= Recent Updates =
* Fixed problem with auto-centering and animation slide effect not working
* Added new background image (share the wealth)
* Added new "smart options" to admin area
* SexyBookmarks now works in harmony with the [Twitter Friendly Links Plugin](http://wordpress.org/extend/plugins/twitter-friendly-links/) so that you can now host your own Short URLs rather than using a third party service.
* Added patch by [Sascha Carlin](http://itst.net/) to really enhance the capabilites of the "manual mode" and the use of only one implementation/instance of the menu.
* Fixed CSS problem for those of you who get colored boxes on hover
* A few minor CSS fixes/tweaks
* Added a check of permalink structure so that ALL subscribe to comments links will work no matter how your permalinks are set up.



= Latest News =
There are many big changes in v2.5! Take a look at the changelog to see everything you can now use in your plugin... Also, if you are interested in translating the plugin to your language please contact me for more details. I am working on releasing a localized version of the plugin in v3.0



== Credits ==
* Credit goes to [Sascha Carlin](http://itst.net/) for the patch to make the plugin work sitewide with a single instance of the menu
* Credit goes to [Kieran Smith](http://www.kieransmith.net/) for finding/fixing the ever elusive "elseif" bug...
* Credit goes to [Norman Yung](http://www.robotwithaheart.com/) for `just about` every improvement in v2.1.3

= Thanks =
* Thanks [Saidmade Labs](http://labs.saidmade.com/ "Saidmade labs") for the original plugin core [WP-Social-Bookmark-Menu](http://wordpress.org/extend/plugins/wp-social-bookmark-menu)
* Thanks to [Liam McKay](http://wefunction.com/2008/07/function-free-icon-set/ "Function Web Design Studio") for the original "Function Icon Set"
* Thanks to [Kieran Smith](http://www.kieransmith.net/) for additional development help.
* Thanks to [Crey Design](http://creydesign.com) for the new background image.

== Screenshots ==

1. A quick preview of the final outcome

== Installation ==

1. Upload the extracted archive to 'wp-content/plugins/'
2. Activate the plugin through the 'Plugins' menu
3. Open the plugin settings page Settings -> SexyBookmarks
4. Adjust settings to your liking
4. Enjoy!

= Manual Usage =
**As of v2.5 the menu can be inserted once anywhere within your site (even outside the loop) and it will still pull the appropriate data for the dynamic links**

If you would like to insert the menu manually, simply choose "Manually insert" from the options page, then place the following code into your theme files where you want the menu to appear:

`<?php if(function_exists('selfserv_sexy')) { selfserv_sexy(); } ?>`

You can still configure the other options available when inserting manually and they will be passed to the function. This is for those of you who have requested to be able to place the menu anywhere you choose... Enjoy!


== Frequently Asked Questions ==

= I've uploaded the plugin and activated, but it's not showing up or it's broken… =

This is normally due to styles in your Wordpress theme overriding the styles of the plugin. Check your theme's stylesheet for rules like !important; as these may be overriding the styles defined by the plugin.

= My favorite bookmarking site isn't listed! =

You can contact me with the name of the site and the URL, and I will work on releasing it with a future update.

= I'm a Wordpress theme developer, and I'd like to bundle your plugin with my themes. Is this okay? =

Absolutely! Please just let me know where the themes will be released so that I can post about it on my site as well!

= I've found a bug not covered here, where do I report it? =

Please report all bugs via the comment form below for quickest response and notation time. Otherwise, you can choose to email me via the [contact form](http://eight7teen.com/contact "Contact Form") located on my site


== Changelog ==

* 1.0		Initial release
* 1.1		Added Twitter to the list, also added more admin options
* 1.1.1	Added a custom CSS section for styling the container DIV
* 1.1.2	Fixed issue with custom css section overlapping icons in options page, also added custom background styles to the container DIV
* 1.1.3	Fixed bug that caused pages to disappear, also now plugin only displays on single posts
* 1.1.4	Resolved issue that caused the menu to be placed at top of post even if "below post" was chosen
* 1.2		Critical namespace update, also added function to allow you to choose page, post, or both
* 1.2.1	Fixed issue people have been having with an additional overlay of the menu where it shouldn't be (other plugin conflicts)
* 1.3		Corrected a css bug causing the DIV's background image to show
* 1.3.1	Fixed my goof from last night that caused images to disappear. Also added extra functionality for Twitter link (auto @reply with your Twitter id) and runs URLs through the API at [IS.GD](http://is.gd) to automatically shorten URLs for the Twitter link.
* 1.3.2	Added a custom function so that you can now insert the menu into your theme anywhere you choose
* 1.3.3	Fixed Twitter links (http://is.gd has a new api with tighter restrictions, so now the plugin uses http://ri.ms to shorten links)
* 1.3.4	Done away with all third party URL shortening services. Now using my own service so that you will not receive errors when the max API limit has been reached.
* 1.4		FAIL - abandoned development and skipped ahead
* 2.0		Added newsvine, devmarks, linkedin, "Email to friend", and "Subscribe to comments". Also fixed a security issue regarding file_get_contents, it now uses cURL instead. Got rid of the table based layout for the admin options area, and replaced it with DIVs. Also restructured the options area and added another option for choosing the background image of the DIV that contains the menu.
* 2.0.1	Fixed the problem with your blogname showing up in each post. Also fixed the encoding of : and ? characters.
* 2.0.2	Fixed the display error for Yahoo and Stumbleupon when using manual method.
* 2.0.3	Fixed error causing RSS and Email icons not to show up when using manual method.
* 2.1		Added ability to display menu on main page, 2 minor bug fixes with email link, Shortened URLs are now static and do not change with each page refresh.
* 2.1.1	Fixed the bug causing your sites to crash right and left due to timeouts with the URL shortening service
* 2.1.2	Added ability to choose which URL shortening service to use. Also added a fallback to file_get_contents() if cURL is not enabled on your server. Then if all else fails and file_get_contents() isn't enabled either, the URL won't be shortened and will simply print the permalink of the post.
* 2.1.3	Replaced cURL command with custom function to reduce server load. Replaced Furl with Diigo since Furl no longer exists. Now only fetching short URL if Twitter is selected to be displayed in the menu.
* 2.1.4	Fixed small bug that was messing up the "Quick Edit" styles in the dashboard (minor update, not critical)
* 2.1.5	Fixed bug causing email link to break layouts in some cases (minor update, only critical to those using NextGen plugin)
* 2.2		Icons are now rearrangeable as well as you can now pick your own URL shortening service. Code is more efficient and puts less strain on the server.
* 2.2.1	Fixed problem with short tags that caused an array to print at top of your pages. Fixed urlencode of subject and body of email link. Also fixed code validity and now the title text shows up correctly rather than displaying the word "Array" when hovering over links.
* 2.2.2 Added option to vertically expand multi-rowed bookmark lines on mouseover using jquery
* 2.2.3 Fixed minor CSS issue introduced in 2.2.2. Added option to auto-center the bookmarks menu (via JS).
* 2.2.4 Added Izeby and Mister Wong
* 2.3.0 Restyled the admin panel and logically grouped the options/settings. Removed use of inline styles (most of them anyway). A minor bug fix for servers that don't support short tags (i.e. you're getting all the Array Array Array messages).
* 2.3.1 Fix auto-centering js not included when it should be. Also fixed minor bug causing apostrophes to not be encoded properly for email subject/body
* 2.3.2 Added option to reset/refresh all stored short URLs.
* 2.3.3 Fix Snipr link. Quick fix CSS issue that was being bad.
* 2.3.4 Small CSS fix for those of you who don't get the "hover" effect on mouseover
* 2.4 Added Tipd, Tumblr, and PFBuzz to the list
* 2.4.1 Small CSS fix for anyone having CSS generated content placed in the menu by their theme's stylesheet. Fixed validation error for PFBuzz link. Also fixed italian accent characters not being encoded properly.
* 2.4.2 Fixed typo with one of the URL shortening services. Also fixed the subscribe to comments feed error I created.
* 2.4.3 Replaced the deceased Yahoo! MyWeb with Yahoo! Buzz and a few custom features for that particular service. Fixed error with images not showing up for Tipd, Tumblr, and PFBuzz in v2.4.2
* 2.5 Added a permalink structure check so that ALL subscribe to comments links will work no matter how your permalinks are configured. Also fixed my css goof for people who's theme was applying a background color rather than the desired image. Lastly, I've added the ability to host your own short URLs by using the [Twitter Friendly Links Plugin](http://wordpress.org/extend/plugins/twitter-friendly-links/) Oh, and you can now choose to place the menu on your site anywhere **once** and it will work throughout the entire site rather than having it printed on every page/article. Added new "smart options" in the admin area (dependent options). Added new background image "Share the wealth!", also updated the "Sharing is sexy!" and "Sharing is caring!" image(s).
* 2.5.1 - Fixed problem with auto-centering and animation slide effect not working