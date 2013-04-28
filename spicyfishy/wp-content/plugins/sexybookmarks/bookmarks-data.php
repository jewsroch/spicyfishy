<?php

// array of bookmarks
$sexy_bookmarks_data=array(
	'sexy-scriptstyle'=>array(
		'check'=>'Check this box to include Script &amp; Style in your bookmarking menu',
		'share'=>'Submit this to Script &amp; Style',
		'baseUrl'=>'http://scriptandstyle.com/submit?url=PERMALINK&amp;title=TITLE',
	),
	'sexy-blinklist'=>array(
		'check'=>'Check this box to include Blinklist in your bookmarking menu',
		'share'=>'Share this on Blinklist',
		'baseUrl'=>'http://www.blinklist.com/index.php?Action=Blink/addblink.php&amp;Url=PERMALINK&amp;Title=TITLE',
	),
	'sexy-delicious'=>array(
		'check'=>'Check this box to include Delicious in your bookmarking menu',
		'share'=>'Share this on del.icio.us',
		'baseUrl'=>'http://del.icio.us/post?url=PERMALINK&amp;title=TITLE',
	),
	'sexy-digg'=>array(
		'check'=>'Check this box to include Digg in your bookmarking menu',
		'share'=>'Digg this!',
		'baseUrl'=>'http://digg.com/submit?phase=2&amp;url=PERMALINK&amp;title=TITLE',
	),
	'sexy-diigo'=>array(
		'check'=>'Check this box to include Diigo in your bookmarking menu',
		'share'=>'Post this on Diigo',
		'baseUrl'=>'http://www.diigo.com/post?url=PERMALINK&amp;title=TITLE&amp;desc=SEXY_TEASER',
	),
	'sexy-reddit'=>array(
		'check'=>'Check this box to include Reddit in your bookmarking menu',
		'share'=>'Share this on Reddit',
		'baseUrl'=>'http://reddit.com/submit?url=PERMALINK&amp;title=TITLE',
	),
	'sexy-yahoobuzz'=>array(
		'check'=>'Check this box to include Yahoo! Buzz in your bookmarking menu',
		'share'=>'Buzz up!',
		'baseUrl'=>'http://buzz.yahoo.com/submit/?submitUrl=PERMALINK&amp;submitHeadline=TITLE&amp;submitSummary=YAHOOTEASER&amp;submitCategory=YAHOOCATEGORY&amp;submitAssetType=YAHOOMEDIATYPE',
	),
	'sexy-stumbleupon'=>array(
		'check'=>'Check this box to include Stumbleupon in your bookmarking menu',
		'share'=>'Stumble upon something good? Share it on StumbleUpon',
		'baseUrl'=>'http://www.stumbleupon.com/submit?url=PERMALINK&amp;title=TITLE',
	),
	'sexy-technorati'=>array(
		'check'=>'Check this box to include Technorati in your bookmarking menu',
		'share'=>'Share this on Technorati',
		'baseUrl'=>'http://technorati.com/faves?add=PERMALINK',
	),
	'sexy-mixx'=>array(
		'check'=>'Check this box to include Mixx in your bookmarking menu',
		'share'=>'Share this on Mixx',
		'baseUrl'=>'http://www.mixx.com/submit?page_url=PERMALINK&amp;title=TITLE',
	),
	'sexy-myspace'=>array(
		'check'=>'Check this box to include MySpace in your bookmarking menu',
		'share'=>'Post this to MySpace',
		'baseUrl'=>'http://www.myspace.com/Modules/PostTo/Pages/?u=PERMALINK&amp;t=TITLE',
	),
	'sexy-designfloat'=>array(
		'check'=>'Check this box to include DesignFloat in your bookmarking menu',
		'share'=>'Submit this to DesignFloat',
		'baseUrl'=>'http://www.designfloat.com/submit.php?url=PERMALINK&amp;title=TITLE',
	),
	'sexy-facebook'=>array(
		'check'=>'Check this box to include Facebook in your bookmarking menu',
		'share'=>'Share this on Facebook',
		'baseUrl'=>'http://www.facebook.com/share.php?u=PERMALINK&amp;t=TITLE',
	),
	'sexy-twitter'=>array(
		'check'=>'Check this box to include Twitter in your bookmarking menu',
		'share'=>'Tweet This!',
		'baseUrl'=>'http://www.twitter.com/home?status=POST_BYSHORT_TITLE+-+FETCH_URL',
	),
	'sexy-mail'=>array(
		'check'=>'Check this box to include \'Email to a Friend\' link in your bookmarking menu',
		'share'=>'Email this to a friend?',
		'baseUrl'=>'mailto:?&amp;subject=MAIL_SUBJECT&amp;body=STRIP_TEASER - PERMALINK',
	),
	'sexy-comfeed'=>array(
		'check'=>'Check this box to include a \'Subscribe to Comments\' link in your bookmarking menu',
		'share'=>'Subscribe to the comments for this post?',
		'baseUrl'=>'PERMALINK',
	),
	'sexy-linkedin'=>array(
		'check'=>'Check this box to include Linkedin in your bookmarking menu',
		'share'=>'Share this on Linkedin',
		'baseUrl'=>'http://www.linkedin.com/shareArticle?mini=true&amp;url=PERMALINK&amp;title=TITLE&amp;summary=POST_SUMMARY&amp;source=SITE_NAME',
	),
	'sexy-newsvine'=>array(
		'check'=>'Check this box to include Newsvine in your bookmarking menu',
		'share'=>'Seed this on Newsvine',
		'baseUrl'=>'http://www.newsvine.com/_tools/seed&amp;save?u=PERMALINK&amp;h=TITLE',
	),
	'sexy-devmarks'=>array(
		'check'=>'Check this box to include Devmarks in your bookmarking menu',
		'share'=>'Share this on Devmarks',
		'baseUrl'=>'http://devmarks.com/index.php?posttext=POST_SUMMARY&amp;posturl=PERMALINK&amp;posttitle=TITLE',
	),
	'sexy-google'=>array(
		'check'=>'Check this box to include Google Bookmarks in your bookmarking menu',
		'share'=>'Add this to Google Bookmarks',
		'baseUrl'=>'http://www.google.com/bookmarks/mark?op=add&amp;bkmk=PERMALINKtitle=TITLE',
	),
	'sexy-misterwong'=>array(
		'check'=>'Check this box to include Mister Wong in your bookmarking menu',
		'share'=>'Add this to Mister Wong',
		'baseUrl'=>'http://www.mister-wong.com/addurl/?bm_url=PERMALINK&amp;bm_description=TITLE&amp;plugin=sexybookmarks',
	),
	'sexy-izeby'=>array(
		'check'=>'Check this box to include Izeby in your bookmarking menu',
		'share'=>'Add this to Izeby',
		'baseUrl'=>'http://izeby.com/add_story.php?story_url=PERMALINK',
	),
	'sexy-tumblr'=>array(
		'check'=>'Check this box to include Tumblr in your bookmarking menu',
		'share'=>'Share this on Tumblr',
		'baseUrl'=>'http://www.tumblr.com/share?v=3&amp;u=PERMALINK&amp;t=TITLE&amp;s=',
	),
	'sexy-tipd'=>array(
		'check'=>'Check this box to include Tipd in your bookmarking menu',
		'share'=>'Share this on Tipd',
		'baseUrl'=>'http://tipd.com/submit.php?url=PERMALINK',
	),
	'sexy-pfbuzz'=>array(
		'check'=>'Check this box to include PFBuzz in your bookmarking menu',
		'share'=>'Share this on PFBuzz',
		'baseUrl'=>'http://pfbuzz.com/submit?url=PERMALINK&amp;title=TITLE',
	),
);
?>
