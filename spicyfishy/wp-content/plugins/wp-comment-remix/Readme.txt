=== Plugin Name ===
Contributors: Pressography
Donate link: http://pressography.com/donate/
Tags: comments, comment, reply, quote, discuss, discussion, tag, tags
Requires at least: 2.5
Tested up to: 2.5.1
Stable tag: trunk

WP Comment Remix adds a plethora of new options and features to Wordpress.

== Description ==

**For the readers**
WP Comment Remix adds a number of great features that you can turn on or off, but they include: 

*Reply Link*
You can add a Reply link to each comment, which, when clicked, adds "@OriginalPoster", and links it to the anchor of that comment. For instance, if Steve left comment #23, and you click the reply link for that comment, WP Comment Remix automatically adds:

&lt;a href="#comment-23"&gt;@Steve&lt;/a&gt; - 

to the reply box.

*Quote Link*
Clicking the Quote link has a similar action to the Reply link, but it adds the entire comment in a blockquote tag, including "&lt;a href="#comment-23"&gt;Originally posted by Steve&lt;/a&gt;"

*Comment Ordering*
You can choose how you want to order the comments for your readers - By Author name or Date, and Ascending or Descending

*Separate or Remove Trackbacks*
You can choose to separate the Trackbacks from the comments and move them to the bottom of the list, or remove them completely

*Comment Tags*
Comment tags allow your readers (and you) to find comments more easily, instead of having to read through potentially hundreds of comments in a single post to find something specific

**In the admin area**
WP Comment Remix also adds some awesome admin functionality for comments.

*New 'In Need Of Reply' notifier*
WPCR adds a button, similar to the "Awaiting Moderation" button that shows up on the right hand side of the Comments menu link. The new button tells you how many comments are in need of a reply, and when clicked, takes you to a list of those comments

*New Links In Comment Admin Area*
WPCR adds new links to each comment display: Edit, Reply, Quote, and Mark Replied

* Edit does exactly that: sends you to the edit comment page.
* Reply and Quote function much like the Reply and Quote links in the public comment area, except instead of sending you to the post page to enter your comment, WPCR simply pops up an ajax reply box where you can make your reply, then save it - all without leaving the page
* Mark Replied tells WPCR that you're not going to reply to that comment. This makes it easy to hide comments in the "In need of reply" list if you're not going to reply to them

*Added AJAX functionality on the Manage Posts page*
Now you can click on the comments image on the Manage Posts page and the comments for that post will drop down in the same window, so you can edit them right there!

**Widgets**
WP Comment Remix also adds 4 powerful new widgets. All of the widgets enable you to set a display template, and use similar tokens to control that template.

*Recent Comments Remix*
The recent comments remix widget allows you to control the look and feel of the display of recent comments. Also included is the ability to add gravatars to each recent comment displayed

*Recent Trackbacks Remix*
We pulled the trackbacks from the recent comments control, and put them into their own widget, so you can effectively feature both on your sidebar.

*Most Active Discussions*
This widget displays the most active discussions (posts with the highest comment counts) so you can wear your most popular posts like a badge of honor

*Most Active Commenters*
Looking for a way to honor frequent commenters? Look no further. The most active commenters widget allows you to display your blog's top commenters proudly, as well as give them some love by linking their name to their site

*Tokens*
Each of the widgets is template-enabled, which means you can easily tell the widget how you want things displayed. You simply enter HTML, with one or more of the tokens below in it:

Tokens available for Recent Comments and Recent Trackbacks
* %ct - Comment title
* %cd - Comment date
* %g - Gravatar
* %pt - Post title
* %pu - Post url (the permalink)
* %au - Author url
* %an - Author name

Tokens available for Most Active Discussions
* %pd - Post date
* %pt - Post title
* %pu - Post url (the permalink)
* %c - Count

Tokens available for Most Active Commenters
* %g - Gravatar
* %au - Author url
* %an - Author name
* %c - Count

Template Examples:
You use the tokens in place of data in the widget templates. For example, if you typed 

&lt;a href='%au'&gt;%an&lt;/a&gt; - %c Comments

into the Most Active Commenters widget template field, it would display something like this for each commenter:

&lt;a href='http://pressography.com'&gt;Jason DeVelvis&lt;/a&gt; - 10 Comments

**FAQ, Support, Screenshots, Help**
These can all be found at the plugin's page at Pressography:
http://pressography.com/plugins/WP-Comment-Remix

**Subscribe**
To keep track of new updates to this and all of Pressography's plugins, subscribe to [Pressography's RSS feed](http://feeds.feedburner.com/Pressography)

== Installation ==

1. Upload the entire '/wpcommentremix/' directory to your `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to the Settings &gt; Comment Remix page and choose what options and features you want to use
Then you're done!