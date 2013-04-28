=== ZenLatest ===
Contributors: jakobfriis
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=jf%40jakobf%2edk&item_name=ZenLatest&no_shipping=0&no_note=1&tax=0&currency_code=EUR&lc=DK&bn=PP%2dDonationsBF&charset=UTF%2d8
Tags: wordpress, zenphoto, sidebar
Requires at least: 2.3.3
Tested up to: 2.7
Stable tag: 1.2.7

ZenLatest is a plugin for Wordpress that can show the latest additions to a ZenPhoto installation. The plugin has been tested to work with Wordpress 2.6

The ZenLatest can now be used in posts just as in widgets. In your post, simply enter <\!--zenlatest--> and the images will appear using the same settings as with the widget.

== Description ==

The current version supports the following customizable parameters :

-Image count (default 10).

-Image and thumbnail paths.

-Customizable thumbnail sizes.

-Database connections. 

-Database tables.

-Option to display the images as links to the full photo in ZenPhoto.

The ZenLatest can now be used in posts just as in widgets. In your post, simply enter <\!--zenlatest--> and the images will appear using the same settings as with the widget.

The plugin creates thumbnails using GD, and stores them in plugins/zenlatest/cache. This directory should only contain the thumbnails that should be displayed on the webpage.

When loading the plugin, the directory is examined and if the directory contains more files than specified on the image count, the directory is cleaned (all files removed), and the thumbnails are regenerated.

The plugin also examines all the images, and compares the image size to the specified thumbnail size parameters. Should width or height not match any of the image dimensions, the file is regenerated using the current parameters.

The plugin is implemented as a widget and has been tested on Wordpress 2.3.3 and ZenPhoto 1.1.3 + 1.1.5 + 1.1.6 . but other version might work.

The plugin has support for WordPress and ZenPhoto on different databases og even different servers. If your WordPress and Zenphoto installations are on the same database and use an identical user, just fill in the same information for both database connections.

Please report any errors to jf@jakobf.dk

== Installation ==

1. Upload `zenlatest.php` to the `/wp-content/plugins/zenlatest` directory
2. Create a directory called `cache` under the installation
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Insert the sidebar through the administration interface

The configuration of ZenLatest is done through the setup menu in the main windows of the adminstration interface of Wordpress.

The following is a description of the parameters and their contents.

- Title
This is the title of the widget. This will be displayed above the images in wordpress.

- Image count
The number of images to be displayed in wordpress.

- Thumbnail height
The height in pixels for the thumbnails. 

- Thumbnail width
The width in pixels for the thumbnails.

- Database host
The database host. Usually localhost, but the plugin has support for this database being on a different server.

- Database
The database to use.

- Database user
The username to use in the connection.

- Database password
The password for the user. 

All these database options are duplicated. One set for ZenPhoto and one set for Wordpress. This is to enable support for the databases being on different servers and using different usernames/passwords. If you are using the same server, just duplicate the settings.

- ZenPhoto albums installation (http)
The web path to your ZenPhoto installation. Example : http://www.fam-friis.dk/zenphoto/albums/

- ZenPhoto path (/home)
The installation path (full path). Example : /home/www/fam-friis.dk/zenphoto/albums/
If you are running your site on a public server, you might need to contact your server administrator to get the full path.

- ZenLatest cache path (/home)
Full path to the ZenLatest cache. Example : /home/www/fam-friis.dk/zenphoto/albums/
This is to support the option of placing the cache directory elsewhere.

- Thumbnail path (http)
The web path to the cache directory. Example : http://www.fam-friis.dk/wp-content/plugins/zenlatest/cache/
You might not have directory listing access to this path.

- Exclude albums
A comma separated list of albums NOT to show pictures from. Leaving this empty will show pictures from all albums.

- Display images as links
Option to show the thumbnails as links or just plain thumbnails.

- Display images as links to albums
Option to have the links refer to the album directory instead of the individual images.

== Screenshots ==

1. Configuration
