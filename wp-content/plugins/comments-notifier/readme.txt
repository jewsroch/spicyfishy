=== Comments Notifier ===
Contributors: idsocietywp
Tags: comments, moderate, moderation, notification, email
Requires at least: 2.0.2
Tested up to: 2.2
Stable tag: trunk

Send email notifications to multiple addresses (not just admin) when new comments are posted on your site.

== Description ==

Currently WordPress only sends an email to the email address entered in the Options->General->E-mail address field when any new comment is submitted or that comment is awaiting moderation (depending on what you have selected for the comment moderation options in your installation). 

For those of us who need more than one email address to be notified, no solution existed.  Until now. Comments Notifier was developed to address this problem.
  
Uses:

*   If you have more than one person modding your site (or any moderater other than the administrator), they can now receive emails alerting them that new comments are awaiting moderation.
*   Send general comment notifications to yourself at multiple email addresses

== Installation ==

1. Download Comments Notifier and unzip it.
2. Upload the `comments-notifier/` folder to the `/wp-content/plugins/` directory on your site.
3. Activate the plugin through the 'Plugins' menu in WordPress.
4. The configuration options for Comment Notifier can be found under the "Options" tab of the WordPress Admin.  Simply navigate to "Options" and then click on "Comments Notifier" in the sub-navigation.


== Use and Configuration ==
Simply enter the email addresses one at a time into the plugin.  Validly formatted email addresses are required.  You may not add the same address twice.  There is no limit on the number of email addresses you can enter, though for the sake of performance, I would recommend that no more than 20 be entered.

In the "Notification Options" section you can configure the plugin to send emails only for comments that require notification, as opposed to all comments.

If you wish to remove an email address, simply click on the "Remove" button beneath the corresponding email address in the "Current Notification List" section of the admin.


== Frequently Asked Questions ==
= Do the email addresses entered have to correspond with registered users of my site? =

No!  Anyone you choose to allow to be notified can receive an email.  However, if you want them to moderate your comments that user must still have access to an account that has the permissions to do so.

= If I use your plugin AND I have the Options->Discussion->E-mail me whenever check boxes checked, what happens? =

Comments notifier was designed to work independently of the built-in comment notification functionality of WordPress.  The Options->Discussion->E-mail me whenever check boxes have no effect on this plugin. If you have the above options checked, the email address listed under the Options->General->E-mail address field will also receive an email as is standard with WordPress.  If you choose to leave those options unchecked, the main administrative email address will not be notified and Comments Notifier will still function and send out the appropriate emails to your list.
























