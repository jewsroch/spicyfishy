=== Plugin Name ===
Contributors: hqtrung
Donate link: http://trunghuynh.com/
Tags: images, zenphoto, gallery
Requires at least: 2.2
Tested up to: 2.5
Stable tag: trunk


This is an extension for Wordpress to integrate ZenPhoto into your Wordpress installation. INSTRUCTION CHANGED FOR ZP 1.1.7
== Description ==
This is an extension for Wordpress to integrate ZenPhoto into your Wordpress installation. It required ZenPhoto installed on the same server and very easy to install (thanks to Wordpress easy plug-in system).
ver 0.9.4 : 
- fix error when misconfigured the path of ZP.
- Pagination
- Installation instruction for ZP 1.1.7

 ATTENTION : FROM VERSION 1.1.7 OF ZENPHOTO, STEP 2 ARE NOLONGER REQUIRED, SKIP IT IF YOUR ZP VERSION > 1.1.7 
== Installation ==
This is an extension for Wordpress to integrate ZenPhoto into your Wordpress installation. It required ZenPhoto installed on the same server and very easy to install (thanks to Wordpress easy plug-in system).

There is one minor conflict in 2 systems in gettext functions, you will have to modify 2 lines of code in Zenphoto to make this work. No danger at all.

Installation :
 ATTENTION : FROM VERSION 1.1.7 OF ZENPHOTO, STEP 2 ARE NOLONGER REQUIRED, SKIP IT IF YOUR ZP VERSION > 1.1.7 
1. Download the zip file and extract to Wordpress plug-in directory.

2. Edit {ZENPHOTO}/zp-core/lib-gettext/gettext.inc

About line 33, replace

require('streams.php');
require('gettext.php');

with
if (!class_exists('StreamReader'))
{
require('streams.php');
}
if (!class_exists("gettext_reader"))
{
require('gettext.php');
}

and about line 190:

replace

function __($msgid) {

return _gettext($msgid);
}

with
if (!function_exists('__'))
{
function __($msgid) {

return _gettext($msgid);
}
}

3. Go to wordpress admin page, activate the plug-in, then go to Trung_ZenPress Configuration under plug-in tab, fill in the required information .

    * Zenpath : Path to zenphoto installation
    * Slug : the slug name of the page you want to be gallery (create a page and fill its slug here)

There you go, ZenPhoto is resided in your wordpress page.


== Screenshots ==


== Arbitrary section ==

== A brief Markdown Example ==

Ordered list:

1. Zenphoto integration
1. lastest photos widget
