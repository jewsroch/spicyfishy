=== NextGEN-ImageFlow ===
Contributors: travel-junkie
Donate link: http://shabushabu-webdesign.com/donation/
Tags: gallery, image, javascript, imageflow, admin, nextgen
Requires at least: 2.9
Tested up to: 3,0-alpha
Stable tag: 1.3.1

Finn Rudolphs picture gallery for NextGEN Gallery. Digital animation for thumbing through a physical image stack.
<strong>This plugin is not being actively developed at this time!</strong>

== Description ==

[Finn Rudolphs](http://imageflow.finnrudolph.de/) picture gallery for NextGEN Gallery. Digital animation for thumbing through a physical image stack. Go to the [our forum](http://shabushabu-webdesign.com/support-forum/) for support and more information.

Check [NextGEN FlashViewer](http://wordpress.org/extend/plugins/nextgen-flashviewer/) for another add-on for your NextGEN Gallery.


== Installation ==

1. Upload the files (except reflect2.php and reflect3.php) to wp-content/plugins/nextgen-imageflow

2. **IMPORTANT:** Check if reflect2.php and reflect3.php have been copied to your Wordpress root folder. If not, then copy them over manually.

3. Activate the plugin

4. Go to Gallery->ImageFlow and change the options to your liking

5. Go to your post and enter tag like [imageflow id="GALLERY-ID"]

That's it ... enjoy!


== Frequently Asked Questions ==

= I installed everything, but I don't see any images. What am I doing wrong? =

Check if you have uploaded reflect2/3.php to your Wordpress root folder (the directory where your wp-config.php file is in).

= My images don't seem to get cached? =

For the caching to work you have to use the NGG default paths, as NextGEN ImageFlow uses the same cache folder as NGG. So that would be wp-content/gallery/cache.

= But I changed that path. What can I do? =

Open reflect2/3.php, go down to around line 80 and adjust this line $cache_dir = dirname(__FILE__).'/wp-content/gallery/cache';

= Anything else I should know? =

Well, now you can use a reflection with any image on your blog that is not part of your NextGEN Gallery. Instead of referencing the URL of your image you can now write something like this:
src="path/to/WP-root-folder/reflect2.php?img=relative/path/to/image.jpg&amp;bgc=ffffff".

== Changelog ==

= v1.3.1 =
* Bugfix:	added stripcslashes to onClick option

= v1.3 =
* NEW:		updated imageflow.js to v1.2.1 modified and minified it
* NEW:		Settings can now be changed through custom fields
* NEW:		Add imageflow link to galleries
* NEW:		Color Picker for the backend
* NEW:		A configurable widget to display ImageFlow in widget-enabled areas
* NEW:		Intuitive way to add shortcodes to editor
* NEW:		Imageflow can be set as default slideshow
* NEW:		Several new shortcodes for random, recent, album, thumb and tag display
* NEW:		reflect2/3 are being moved to root automatically now
* Added:	Admin dashboard widget (ShabuShabu Feeds)
* Added:	Danish language files - THX to Adamson(http://wordpress.blogos.dk)
* Added:	Russian language files - THX to Nikolay Ivanets(http://ivanets.kiev.ua/)
* Added:	Inline help
* Added:	Template tags for all functions
* Added:	Update notice and min WP Version check
* Bugfix:	General housekeeping and small fixes
* Removed:	localisation function; now part of NGG 

= v1.2 =
* NEW:		Compatibility with qTranslate and Polyglot
* Bugfix:	getimagesize uses absolute path now

= v1.1 =
* NEW:		updated imageflow.js to v1.0.2 - It is now possible to have more than one instance of ImageFlow on one page
* NEW:		Automatic unistall via register_uninstall_hook()
* NEW:		new options (adjustible via admin panel)
* Added:	Compatibility with WP 2.7 and NextGEN Gallery 1.0
* Added:	lib/shortcodes.php for use of WP shortcodes
* Changed:	moved functions to lib/functions.php
* Changed:	rewritten plugin as a class
* Changed:	moved options to admin/install.php
* Changed:	rewritten admin area as a class

= v1.0 =
* Added:	Compatibility with WP 2.6

= v0.90 =
* Added:	Option to switch reflection on/off
* Added:	Spanish language files added  - THX to Karin(http://es-xchange.com/)
* Bugfix:	IE display problems solved

= v0.81 =
* Added:	Italian language files - THX to Gianni Diurno(http://gidibao.net/)

= v0.80 =
* initial release

= up to v0.70 =
* internal releases


== Screenshots ==

1. ImageFlow in action
2. ImageFlow Admin


== Credits ==

This programm is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA