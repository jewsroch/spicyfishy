<?php
/*
Plugin Name: Bookmark Me
Plugin URI: http://www.satollo.com/english/wordpress/bookmark-me
Description: Bookmark Me is a plugin to add bookmark buttons (even non english bookmark sites) to your post or page.
Version: 1.3.3
Author: Satollo 
Author URI: http://www.satollo.com
Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
*/

/*	Copyright 2008  Satollo  (email : satollo@gmail.com)

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

define('BOOKMARK_ME', true);

$bkmrk_options = get_option('bkmrk');

add_action('admin_head', 'bkmrk_admin_head');
function bkmrk_admin_head()
{
    add_options_page('Bookmark Me', 'Bookmark Me', 'manage_options', 'bookmark-me/options.php');
}

if (!$bkmrk_options['post_layout'])
{
	add_action('the_content', 'bkmrk_the_content');
	function bkmrk_the_content($content)
	{
	    global $bkmrk_options;
	    if ($bkmrk_options['post_layout']) return $content;

	    $buffer = '<p class="bookmark-me">' . $bkmrk_options['label'] . bookmark_me() . $bkmrk_options['label_after'] . '</p>';
	    
	    if (is_page()) {
	        if ($bkmrk_options['page_before']) $content = $buffer . $content;
	        if ($bkmrk_options['page_after']) $content .= $buffer ;
	    }
	    else if (is_single()) {
	        if ($bkmrk_options['post_before']) $content = $buffer . $content;
	        if ($bkmrk_options['post_after']) $content .= $buffer ;
	    }
	    else {
	        if ($bkmrk_options['home_before']) $content = $buffer . $content;
	        if ($bkmrk_options['home_after']) $content .= $buffer ;
	    }
	    return $content;
	}
}

// This function generates and return a string with bookmarks buttons and links. The
// function can be called directly from the theme pages in "the_loop" cycle or
// from the Post Layout plugin.
function bookmark_me()
{
    global $bkmrk_options;

    $title = get_the_title();
    $title_encoded = urlencode($title);
    $link = get_permalink();
    $link_encoded = urlencode($link);
    $target = '';
    $img_attrs = ' style="margin:0;border:0;padding:0" alt="bookmark"';
    if ($bkmrk_options['target']) $target .= ' target="_blank" rel="nofollow"' ;
    
    $image_url = get_option('siteurl') . '/wp-content/plugins/bookmark-me/images/';
    $buffer = '';
    
    // International/English
    if ($bkmrk_options['buzz']) 
    $buffer .= '
    <script type="text/javascript">
	    yahooBuzzArticleHeadline = "' . addslashes($title) . '";
	    yahooBuzzArticleId = "' . addslashes($link) . '";
    </script>
    <script type="text/javascript"
        src="http://d.yimg.com/ds/badge2.js"
        badgetype="logo">
    </script>    
    ';
    
    if ($bkmrk_options['technorati']) $buffer .= '<a title="technorati.com" href="http://www.technorati.com/faves?add=' . $link_encoded . '"' . $target . '><img src="' . $image_url . 'technorati.png"' . $img_attrs . '/></a> ';
    if ($bkmrk_options['delicious']) $buffer .= '<a title="del.icio.us" href="http://del.icio.us/post?url=' . $link_encoded  . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'delicious.png"' . $img_attrs . '/></a> ';
    if ($bkmrk_options['stumbleupon']) $buffer .= '<a title="stumbleupon.com" href="http://www.stumbleupon.com/submit?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'stumbleupon.png"' . $img_attrs . '/></a> ';
    if ($bkmrk_options['digg']) $buffer .= '<a title="digg.com" href="http://digg.com/submit?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'digg.png"' . $img_attrs . '/></a> ';
    if ($bkmrk_options['facebook']) $buffer .= '<a title="www.facebook.com" href="http://www.facebook.com/share.php?u=' . $link_encoded . '&amp;t=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'facebook.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['yahoo']) $buffer .= '<a title="bookmarks.yahoo.com" href="http://bookmarks.yahoo.com/toolbar/savebm?opener=tb&amp;u=' . $link_encoded  . '"' . $target . '><img src="' . $image_url . 'yahoo.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['google']) $buffer .= '<a title="www.google.com" href="http://www.google.com/bookmarks/mark?op=edit&amp;output=popup&amp;bkmk=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'google.png"' . $img_attrs . '/></a> ';
    if ($bkmrk_options['fark']) $buffer .= '<a title="fark.com" href="http://cgi.fark.com/cgi/fark/edit.pl?new_url=' . $link_encoded . '&amp;new_comment=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'fark.png"' . $img_attrs . '/></a> ';
    if ($bkmrk_options['furl']) $buffer .= '<a title="furl.com" href="http://www.furl.net/storeIt.jsp?u=' . $link_encoded . '&amp;t=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'furl.png"' . $img_attrs . '/></a> ';

	if ($bkmrk_options['linkarena']) $buffer .= '<a title="linkarena.de" href="http://linkarena.com/bookmarks/addlink/?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'linkarena.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['folkd']) $buffer .= '<a title="folkd.com" href="http://www.folkd.com/submit/' . $link_encoded . '"' . $target . '><img src="' . $image_url . 'folkd.png"' . $img_attrs . '/></a> ';

    if ($bkmrk_options['magnolia']) $buffer .= '<a title="magnolia.com" href="http://ma.gnolia.com/bookmarklet/add?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'magnolia.png"' . $img_attrs . '/></a> ';
    if ($bkmrk_options['mixx']) $buffer .= '<a title="mixx.com" href="http://www.mixx.com/submit?page_url=' . $link_encoded . '"' . $target . '><img src="' . $image_url . 'mixx.png"' . $img_attrs . '/></a> ';

    if ($bkmrk_options['reddit']) $buffer .= '<a title="reddit.com" href="http://reddit.com/submit?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'reddit.png"' . $img_attrs . '/></a> ';
    if ($bkmrk_options['propeller']) $buffer .= '<a title="propeller.com" href="http://www.propeller.com/submit/?U=' . $link_encoded . '&amp;T=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'propeller.png"' . $img_attrs . '/></a> ';

    if ($bkmrk_options['windowslive']) $buffer .= '<a title="windowslive.com" href="https://favorites.live.com/quickadd.aspx?mkt=en-us&amp;url=' . $link_encoded . '"' . $target . '><img src="' . $image_url . 'windowslive.png"' . $img_attrs . '/></a> ';

    if ($bkmrk_options['dzone']) $buffer .= '<a title="dzone.com" href="http://www.dzone.com/links/add.html?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'dzone.png"' . $img_attrs . '/></a> ';
    if ($bkmrk_options['yahoomyweb']) $buffer .= '<a title="myweb2.search.yahoo.com" href="http://myweb2.search.yahoo.com/myresults/bookmarklet?u=' . $link_encoded  . '"' . $target . '><img src="' . $image_url . 'yahoomyweb.png"' . $img_attrs . '/></a> ';
    if ($bkmrk_options['linkedin']) $buffer .= '<a title="linkedin.com" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'linkedin.png"' . $img_attrs . '/></a> ';
    if ($bkmrk_options['twitter']) $buffer .= '<a title="twitthis.com" href="http://twitthis.com/twit?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'twitter.png"' . $img_attrs . '/></a> ';
   
    if ($bkmrk_options['jamespot']) $buffer .= '<a title="jamespot.com" href="http://www.jamespot.com/?action=spotit&amp;url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'jamespot.png"' . $img_attrs . '/></a> ';

    if ($bkmrk_options['blinklist']) $buffer .= '<a title="blinklist.com" href="http://www.blinklist.com/index.php?Action=Blink/addblink.php&amp;url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'blinklist.png"' . $img_attrs . '/></a> ';

    if ($bkmrk_options['blogmarks']) $buffer .= '<a title="blogmarks.net" href="http://blogmarks.net/my/new.php?mini=1&amp;simple=1&amp;url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'blogmarks.png"' . $img_attrs . '/></a> ';

    if ($bkmrk_options['blogospherenews']) $buffer .= '<a title="blogospherenews.com" href="http://www.blogospherenews.com/submit.php?url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'blogospherenews.png"' . $img_attrs . '/></a> ';

    if ($bkmrk_options['blogsvine']) $buffer .= '<a title="blogsvine.com" href="http://blogsvine.com/submit.php?url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'blogsvine.png"' . $img_attrs . '/></a> ';

    if ($bkmrk_options['faves']) $buffer .= '<a title="faves.com" href="http://faves.com/Authoring.aspx?u=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'faves.png"' . $img_attrs . '/></a> ';

    if ($bkmrk_options['myspace']) $buffer .= '<a title="myspace.com" href="http://www.myspace.com/Modules/PostTo/Pages/?u=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'myspace.png"' . $img_attrs . '/></a> ';

    if ($bkmrk_options['newsvine']) $buffer .= '<a title="newsvine.com" href="http://www.newsvine.com/_tools/seed&amp;save?u=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'newsvine.png"' . $img_attrs . '/></a> ';

	// Specific
    if ($bkmrk_options['healthranker']) $buffer .= '<a title="healthranker.com" href="http://healthranker.com/submit.php?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'healthranker.png"' . $img_attrs . '/></a> ';

	// Espanol
    if ($bkmrk_options['es_meneame']) $buffer .= '<a title="meneame.net" href="http://meneame.net/submit.php?url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'es_meneame.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['es_fresqui']) $buffer .= '<a title="tec.fresqui.com" href="http://tec.fresqui.com/post?title=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'es_fresqui.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['es_blogmemes']) $buffer .= '<a title="www.blogmemes.com" href="http://www.blogmemes.com/post.php?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'es_blogmemes.png"' . $img_attrs . '/></a> ';  

	// Francais
    if ($bkmrk_options['fr_wikio']) $buffer .= '<a title="wikio.fr" href="http://www.wikio.fr/subscribe?url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'fr_wikio.png"' . $img_attrs . '/></a> ';  

	// German
    if ($bkmrk_options['de_mister-wong']) $buffer .= '<a title="mister-wong.de" href="http://www.mister-wong.de/index.php?action=addurl&bm_url=' . $link_encoded . '&amp;bm_tags=&amp;bm_notice=&bm_description=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'de_mister-wong.png"' . $img_attrs . '/></a> ';  

    if ($bkmrk_options['de_icio']) $buffer .= '<a title="icio.de" href="http://www.icio.de/login.php?popup=1&url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'de_icio.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['de_webnews']) $buffer .= '<a title="webnews.de" href="http://www.webnews.de/einstellen?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'de_webnews.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['de_oneview']) $buffer .= '<a title="oneview.de" href="http://www.oneview.de/quickadd/neu/addBookmark.jsf?URL=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'de_oneview.png"' . $img_attrs . '/></a> ';  
	
    if ($bkmrk_options['de_yigg']) $buffer .= '<a title="yigg.de" href="http://yigg.de/neu?exturl=' . $link_encoded . '"' . $target . '><img src="' . $image_url . 'de_yigg.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['de_newstube']) $buffer .= '<a title="newstube.de" href="http://www.newstube.de/submit.php?url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'de_newstube.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['de_wikio']) $buffer .= '<a title="wikio.de" href="http://www.wikio.de/vote?url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'de_wikio.png"' . $img_attrs . '/></a> ';  
	
    if ($bkmrk_options['de_favit']) $buffer .= '<a title="favit.de" href="http://www.favit.de/submit.php?url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'de_favit.png"' . $img_attrs . '/></a> ';
    if ($bkmrk_options['de_newsider']) $buffer .= '<a title="newsider.de" href="http://www.newsider.de/submit.php?url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'de_newsider.png"' . $img_attrs . '/></a> ';
    if ($bkmrk_options['de_linksilo']) $buffer .= '<a title="linksilo.de" href="http://www.linksilo.de/index.php?area=bookmarks&amp;func=bookmark_new&amp;addurl=' . $link_encoded . '&amp;addtitle=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'de_linksilo.png"' . $img_attrs . '/></a> ';
    if ($bkmrk_options['de_readster']) $buffer .= '<a title="readster.de" href="http://www.readster.de/submit/?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'de_readster.png"' . $img_attrs . '/></a> ';
    if ($bkmrk_options['de_kledy']) $buffer .= '<a title="kledy.de" href="http://www.kledy.de/submit.php?url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'de_kledy.png"' . $img_attrs . '/></a> ';
    if ($bkmrk_options['de_bonitrust']) $buffer .= '<a title="bonitrust.de" href="http://www.bonitrust.de/account/bookmark/?bookmark_url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'de_bonitrust.png"' . $img_attrs . '/></a> ';
    if ($bkmrk_options['de_favoriten']) $buffer .= '<a title="favoriten.de" href="http://www.favoriten.de/url-hinzufuegen.html?bm_url=' . $link_encoded . '&amp;bm_title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'de_favoriten.png"' . $img_attrs . '/></a> ';

	// Italian
    if ($bkmrk_options['it_oknotizie']) $buffer .= '<a title="oknotizie.alice.it" href="http://oknotizie.alice.it/post?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'it_oknotizie.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['it_segnalo']) $buffer .= '<a title="segnalo.com" href="http://segnalo.com/post.html.php?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'it_segnalo.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['it_diggita']) $buffer .= '<a title="www.diggita.it" href="http://www.diggita.it/submit.php?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'it_diggita.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['it_upnews']) $buffer .= '<a title="www.upnews.it" href="http://www.upnews.it/submit?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'it_upnews.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['it_bookmark']) $buffer .= '<a title="www.bookmark.it" href="http://www.bookmark.it/bookmark.php?url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'it_bookmark.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['it_wikio']) $buffer .= '<a title="www.wikio.it" href="http://www.wikio.it/vote?url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'it_wikio.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['it_badzu']) $buffer .= '<a title="www.badzu.net" href="http://www.badzu.net/submit?action=it&amp;link=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'it_badzu.png"' . $img_attrs . '/></a> ';  

	// Netherlands
    if ($bkmrk_options['nl_nujij']) $buffer .= '<a title="nujij.nl" href="http://nujij.nl/jij.lynkx?t=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'nl_nujij.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['nl_ekudos']) $buffer .= '<a title="ekudos.nl" href="http://www.ekudos.nl/artikel/nieuw?url=' . $link_encoded . '&amp;title=' . $title_encoded . '&amp;desc=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'nl_ekudos.png"' . $img_attrs . '/></a> ';   
    if ($bkmrk_options['nl_msnreporter']) $buffer .= '<a title="reporter.msn.nl" href="http://reporter.msn.nl/?f=contribute&Title=' . $title_encoded . '&amp;URL=' . '&amp;cat_id=6&amp;tag_id=31&amp;Remark=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'nl_msnreporter.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['nl_tipt']) $buffer .= '<a title="tipt.nl" href="http://www.tipt.nl/new_tip.php?title=' . $title_encoded . '&amp;url=' . $link_encoded . '"' . $target . '><img src="' . $image_url . 'nl_tipt.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['nl_wvwo']) $buffer .= '<a title="watvindenwijover.nl" href="http://watvindenwijover.nl/notes/new?nextaction=home&url=' . $link_encoded . '&amp;title=' . $title_encoded . '&amp;text=' . $title_encoded . '&amp;commit=Verder' . '"' . $target . '><img src="' . $image_url . 'nl_wvwo.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['nl_tagmos']) $buffer .= '<a title="tagmos.nl" href="http://www.tagmos.nl/bookmarks.php/?action=add&amp;noui=yes&amp;jump=close&amp;address=' . $link_encoded . '&amp;title=' . $title_encoded . '&amp;description=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'nl_tagmos.png"' . $img_attrs . '/></a> ';  

	// Arabian
    if ($bkmrk_options['ar_darabet']) $buffer .= '<a title="darabet.com" href="http://darabet.com/submit.php?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'ar_darabet.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['ar_khabbr']) $buffer .= '<a title="khabbr.com" href="http://www.khabbr.com/submit.php?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'ar_khabbr.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['ar_wapher']) $buffer .= '<a title="wapher.com" href="http://www.wapher.com/submit.php?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'ar_wapher.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['ar_qulqal']) $buffer .= '<a title="qulqal.com" href="http://www.qulqal.com/submit.php?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'ar_qulqal.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['ar_ef7at']) $buffer .= '<a title="ef7at.com" href="http://www.ef7at.com/submit.php?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'ar_ef7at.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['ar_efleg']) $buffer .= '<a title="efleg.com" href="http://www.efleg.com/submit.php?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'ar_efleg.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['ar_adifni']) $buffer .= '<a title="adifni.com" href="http://www.adifni.com/account/bookmark/?bookmark_url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'ar_adifni.png"' . $img_attrs . '/></a> ';  

	// Chinese
    if ($bkmrk_options['haohao']) $buffer .= '<a title="haohaoreport.com" href="http://www.haohaoreport.com/submit.php?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'haohao.png"' . $img_attrs . '/></a> ';

	// Czech
    if ($bkmrk_options['cz_jagg']) $buffer .= '<a title="jagg.cz" href="http://www.jagg.cz/bookmarks.php?action=add&address=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'cz_jagg.png"' . $img_attrs . '/></a> ';
    if ($bkmrk_options['cz_linkuj']) $buffer .= '<a title="linkuj.cz" href="http://linkuj.cz/?id=linkuj&url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'cz_linkuj.png"' . $img_attrs . '/></a> ';
    if ($bkmrk_options['cz_topclanky']) $buffer .= '<a title="topclanky.cz" href="http://www.topclanky.cz/pridat-odkaz/?kde=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'cz_topclanky.png"' . $img_attrs . '/></a> ';

	// Indian
    if ($bkmrk_options['indianpad']) $buffer .= '<a title="indianpad.com" href="http://www.indianpad.com/submit.php?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'indianpad.png"' . $img_attrs . '/></a> ';

	// Lithuanian
    if ($bkmrk_options['lt_cut']) $buffer .= '<a title="cut.lt" href="http://www.cut.lt/submit.php?url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'lt_cut.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['lt_topix']) $buffer .= '<a title="topix.lt" href="http://www.topix.lt/submit.php?url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'lt_topix.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['lt_zynios']) $buffer .= '<a title="zynios.lt" href="http://www.zynios.lt/skelbti?url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'lt_zynios.png"' . $img_attrs . '/></a> ';  

	// Russian
    if ($bkmrk_options['ru_momesto']) $buffer .= '<a title="moemesto.ru" href="http://moemesto.ru/post.php?url=' . $link_encoded . '&amp;title=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'ru_moemesto.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['ru_memori']) $buffer .= '<a title="memori.ru" href="http://memori.ru/link/?sm=1&u_data[url]=' . $link_encoded . '&amp;u_data[name]=' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'ru_memori.png"' . $img_attrs . '/></a> ';  

	// Slovak
    if ($bkmrk_options['sk_linkuj']) $buffer .= '<a title="linkuj.sk" href="http://linkuj.sk/submit.php?phase=1&amp;url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'sk_linkuj.png"' . $img_attrs . '/></a> ';  
    if ($bkmrk_options['sk_vybrali']) $buffer .= '<a title="vybrali.sme.sk" href="http://vybrali.sme.sk/submit.php?url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'sk_vybrali.png"' . $img_attrs . '/></a> ';  

	// Sweden
    if ($bkmrk_options['se_pusha']) $buffer .= '<a title="pusha.se" href="http://www.pusha.se/posta?url=' . $link_encoded . '"' . $title_encoded . '"' . $target . '><img src="' . $image_url . 'se_pusha.png"' . $img_attrs . '/></a> ';  

    
    return $buffer;
}

?>