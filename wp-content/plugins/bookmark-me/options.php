<?php
function bkmrk_request($name, $default=null) 
{
	if (!isset($_REQUEST[$name])) return $default;
	if (get_magic_quotes_gpc()) return bkmrk_stripslashes($_REQUEST[$name]);
	else return $_REQUEST[$name];
}

function bkmrk_stripslashes($value)
{
	$value = is_array($value) ? array_map('bkmrk_stripslashes', $value) : stripslashes($value);
	return $value;
}
function bkmrk_field_text($name, $label='', $tips='', $attrs='')
{
  global $options;
  if (strpos($attrs, 'size') === false) $attrs .= 'size="30"';
  echo '<tr><th scope="row">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
  echo '<td><input type="text" ' . $attrs . ' name="options[' . $name . ']" value="' . 
    htmlspecialchars($options[$name]) . '"/>';
  echo ' ' . $tips;
  echo '</td></tr>';
}

function bkmrk_field_text2($name, $label='', $tips='', $attrs='')
{
  global $options;
  if (strpos($attrs, 'size') === false) $attrs .= 'size="30"';
  echo '<th scope="row">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
  echo '<td><input type="text" ' . $attrs . ' name="options[' . $name . ']" value="' . 
    htmlspecialchars($options[$name]) . '"/>';
  echo ' ' . $tips;
  echo '</td>';
}


function bkmrk_field_checkbox($name, $label='', $tips='', $attrs='')
{
  global $options;
  echo '<tr><th scope="row">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
  echo '<td><input type="checkbox" ' . $attrs . ' name="options[' . $name . ']" value="1" ' . 
    ($options[$name]!= null?'checked':'') . '/>';
  echo ' ' . $tips;
  echo '</td></tr>';
}

function bkmrk_field_checkbox2($name, $label='', $tips='', $attrs='')
{
  global $options;
  echo '<th scope="row">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
  echo '<td><input type="checkbox" ' . $attrs . ' name="options[' . $name . ']" value="1" ' . 
    ($options[$name]!= null?'checked':'') . '/>';
  echo ' ' . $tips;
  echo '</td>';
}
	

if (isset($_POST['save']))
{
  $options = bkmrk_request('options');
  update_option('bkmrk', $options);
}
else 
{
    $options = get_option('bkmrk');
}

$image_url = get_option('home') . '/wp-content/plugins/bookmark-me/images/';
?>	
<div class="wrap">
<form method="post">

<h2>Bookmark Me</h2>

<p><strong>Many thanks and kisses to Cynthia Lockley for a lot new bookmark sites and
corections.</strong></p>

        <p>
            My other plugins:
            <a href="http://www.satollo.com/english/wordpress/post-layout">Post Layout</a>,
            <a href="http://www.satollo.com/english/wordpress/feed-layout">Feed Layout</a>,
            <a href="http://www.satollo.com/english/wordpress/hyper-cache">Hyper Cache</a>,
        </p>


<? if (defined('POST_LAYOUT')) { ?>
<p>You have the Post Layout plugin installed. You can print the bookmark buttons directly from
post layout using the code &lt;?php echo bookmark_me(); ?&gt; in the textareas where you put the html code to be
added before, after or in the middle of a post.</p>
<? } ?>

<table class="form-table">
<?php bkmrk_field_checkbox('post_layout', 'Do not inject the buttons', '(I call the function from the theme or I use the Post Layout plugin)'); ?>
</table>

<h3>Where and how to show the buttons</h3>
<h4>On a single post</h4>
<table class="form-table">
<tr>
<?php bkmrk_field_checkbox2('post_before', 'Before the post content', ''); ?>
<?php bkmrk_field_checkbox2('post_after', 'After the post content', ''); ?>
</tr>
</table>

<h4>On a single page</h4>
<table class="form-table">
<tr>
<?php bkmrk_field_checkbox2('page_before', 'Before the page content', ''); ?>
<?php bkmrk_field_checkbox2('page_after', 'After the page content', ''); ?>
</tr>
</table>

<h4>On home page</h4>
<table class="form-table">
<tr>
<?php bkmrk_field_checkbox2('home_before', 'Before the post content - home page', ''); ?>
<?php bkmrk_field_checkbox2('home_after', 'After the post content - home page', ''); ?>
</tr>
</table>

<h4>Other options</h4>
<table class="form-table">
<tr>
<?php bkmrk_field_checkbox2('target', 'Open the site in a new window', ''); ?>
<?php bkmrk_field_text2('label', 'Text before the buttons', ''); ?>
<?php bkmrk_field_text2('label_after', 'Text after the buttons', ''); ?>
</tr>
</table>


<h3>Buttons to display</h3>
<h4>English/International</h4>
<table class="form-table">
<?php bkmrk_field_checkbox('buzz', '<img src="' . $image_url . 'buzz.png">', 'Yahoo Buzz (buzz.yahoo.com)'); ?>
<?php bkmrk_field_checkbox('digg', '<img src="' . $image_url . 'digg.png">', 'Digg (digg.com)'); ?>
<?php bkmrk_field_checkbox('technorati', '<img src="' . $image_url . 'technorati.png">', 'Technorati (technorati.com)'); ?>
<?php bkmrk_field_checkbox('facebook', '<img src="' . $image_url . 'facebook.png">', 'Facebook (facebook.com)'); ?>
<?php bkmrk_field_checkbox('stumbleupon', '<img src="' . $image_url . 'stumbleupon.png">', 'Stumbleupon (stumbleupon.com)'); ?>
<?php bkmrk_field_checkbox('yahoo', '<img src="' . $image_url . 'yahoo.png">', 'Yahoo (yahoo.com)'); ?>
<?php bkmrk_field_checkbox('delicious', '<img src="' . $image_url . 'delicious.png">', 'Delicious (del.icio.us)'); ?>
<?php bkmrk_field_checkbox('google', '<img src="' . $image_url . 'google.png">', 'Google Bookmarks (www.google.com/bookmarks)'); ?>
<?php bkmrk_field_checkbox('fark', '<img src="' . $image_url . 'fark.png">', 'Fark (fark.com)'); ?>
<?php bkmrk_field_checkbox('furl', '<img src="' . $image_url . 'furl.png">', 'Furl (furl.com)'); ?>
<?php bkmrk_field_checkbox('linkarena', '<img src="' . $image_url . 'linkarena.png">', 'Linkarena (linkarena.de)'); ?>
<?php bkmrk_field_checkbox('folkd', '<img src="' . $image_url . 'folkd.png">', 'Folkd (folkd.com)'); ?>

<?php bkmrk_field_checkbox('magnolia', '<img src="' . $image_url . 'magnolia.png">', 'Magnolia (magnolia.com)'); ?>
<?php bkmrk_field_checkbox('mixx', '<img src="' . $image_url . 'mixx.png">', 'Mixx (mixx.com)'); ?>
<?php bkmrk_field_checkbox('reddit', '<img src="' . $image_url . 'reddit.png">', 'Reddit (reddit.com)'); ?>
<?php bkmrk_field_checkbox('propeller', '<img src="' . $image_url . 'propeller.png">', 'Propeller (propeller.com)'); ?>

<?php bkmrk_field_checkbox('windowslive', '<img src="' . $image_url . 'windowslive.png">', 'Windows Live (www.live.com)'); ?>

<?php bkmrk_field_checkbox('dzone', '<img src="' . $image_url . 'dzone.png">', 'DZone (dzone.com)'); ?>
<?php bkmrk_field_checkbox('linkedin', '<img src="' . $image_url . 'linkedin.png">', 'LinkedIn (linkedin.com)'); ?>
<?php bkmrk_field_checkbox('twitter', '<img src="' . $image_url . 'twitter.png">', 'TwitThis (twitthis.com)'); ?>
<?php bkmrk_field_checkbox('yahoomyweb', '<img src="' . $image_url . 'yahoomyweb.png">', 'YahooMyWeb (myweb2.search.yahoo.com)'); ?>
<?php bkmrk_field_checkbox('jamespot', '<img src="' . $image_url . 'jamespot.png">', 'Jamespot (www.jamespot.com)'); ?>
<?php bkmrk_field_checkbox('blinklist', '<img src="' . $image_url . 'blinklist.png">', 'Blinklist (www.blinklist.com)'); ?>

<?php bkmrk_field_checkbox('blogmarks', '<img src="' . $image_url . 'blogmarks.png">', 'Blogmarks (blogmarks.net)'); ?>

<?php bkmrk_field_checkbox('blogospherenews', '<img src="' . $image_url . 'blogospherenews.png">', 'Blogospherenews (www.blogospherenews.com)'); ?>

<?php bkmrk_field_checkbox('blogsvine', '<img src="' . $image_url . 'blogsvine.png">', 'Blogsvine (blogsvine.com)'); ?>

<?php bkmrk_field_checkbox('faves', '<img src="' . $image_url . 'faves.png">', 'Faves (faves.com)'); ?>

<?php bkmrk_field_checkbox('myspace', '<img src="' . $image_url . 'myspace.png">', 'Myspace (www.myspace.com)'); ?>

<?php bkmrk_field_checkbox('newsvine', '<img src="' . $image_url . 'newsvine.png">', 'Newsvine (www.newsvine.com)'); ?>
</table>
<p>More? Let me know: <a href="mailto:satollo@gmail.com">satollo@gmail.com</a>.</p>

<h4>English with topic (health, science, ...)</h4>
<table class="form-table">
<?php bkmrk_field_checkbox('healthranker', '<img src="' . $image_url . 'healthranker.png">', 'Heath Ranker (www.healthranker.com)'); ?>
</table>


<h4>Espa&ntilde;ol</h4>
<table class="form-table">
<?php bkmrk_field_checkbox('es_meneame', '<img src="' . $image_url . 'es_meneame.png">', 'Men&egrave;ame (meneame.net)'); ?>
<?php bkmrk_field_checkbox('es_fresqui', '<img src="' . $image_url . 'es_fresqui.png">', 'Fresqui (fresqui.com)'); ?>
<?php bkmrk_field_checkbox('es_blogmemes', '<img src="' . $image_url . 'es_blogmemes.png">', 'Blog Memes (www.blogmemes.com)'); ?>
</table>
<p>More? Let me know: <a href="mailto:satollo@gmail.com">satollo@gmail.com</a>.</p>

<h4>Fran&ccedil;ais</h4>
<table class="form-table">
<?php bkmrk_field_checkbox('fr_wikio', '<img src="' . $image_url . 'fr_wikio.png">', 'Wikio.fr (wikio.fr)'); ?>
</table>
<p>More? Let me know: <a href="mailto:satollo@gmail.com">satollo@gmail.com</a>.</p>

<h4>Deutsche (German)</h4>
<table class="form-table">
<?php bkmrk_field_checkbox('de_mister-wong', '<img src="' . $image_url . 'de_mister-wong.png">', 'Mister wong (www.mister-wong.de)'); ?>
<?php bkmrk_field_checkbox('de_icio', '<img src="' . $image_url . 'de_icio.png">', 'Icio.de (icio.de)'); ?>
<?php bkmrk_field_checkbox('de_webnews', '<img src="' . $image_url . 'de_webnews.png">', 'Webnews (www.webnews.de)'); ?>
<?php bkmrk_field_checkbox('de_oneview', '<img src="' . $image_url . 'de_oneview.png">', 'Oneview (www.oneview.de)'); ?>
<?php bkmrk_field_checkbox('de_yigg', '<img src="' . $image_url . 'de_yigg.png">', 'Yigg (yigg.de)'); ?>
<?php bkmrk_field_checkbox('de_newstube', '<img src="' . $image_url . 'de_newstube.png">', 'Newstube (www.newstube.de)'); ?>
<?php bkmrk_field_checkbox('de_wikio', '<img src="' . $image_url . 'de_wikio.png">', 'Wikio.de (wikio.de)'); ?>

<?php bkmrk_field_checkbox('de_favit', '<img src="' . $image_url . 'de_favit.png">', 'Favit (favit.de)'); ?>
<?php bkmrk_field_checkbox('de_newsider', '<img src="' . $image_url . 'de_newsider.png">', 'Newsider (newsider.de)'); ?>
<?php bkmrk_field_checkbox('de_linksilo', '<img src="' . $image_url . 'de_linksilo.png">', 'Linksilo (linksilo.de)'); ?>
<?php bkmrk_field_checkbox('de_readster', '<img src="' . $image_url . 'de_readster.png">', 'Readster (readster.de)'); ?>
<?php bkmrk_field_checkbox('de_kledy', '<img src="' . $image_url . 'de_kledy.png">', 'Kledy (kledy.de)'); ?>
<?php bkmrk_field_checkbox('de_bonitrust', '<img src="' . $image_url . 'de_bonitrust.png">', 'BoniTrust (bonitrust.de)'); ?>
<?php bkmrk_field_checkbox('de_favoriten', '<img src="' . $image_url . 'de_favoriten.png">', 'Favoriten (favoriten.de)'); ?>
</table>
<p>More? Let me know: <a href="mailto:satollo@gmail.com">satollo@gmail.com</a>.</p>

<h4>Italian</h4>
<table class="form-table">
<?php bkmrk_field_checkbox('it_oknotizie', '<img src="' . $image_url . 'it_oknotizie.png">', 'OK Notizie (oknotizie.alice.it)'); ?>
<?php bkmrk_field_checkbox('it_segnalo', '<img src="' . $image_url . 'it_segnalo.png">', 'Segnalo (segnalo.com)'); ?>
<?php bkmrk_field_checkbox('it_diggita', '<img src="' . $image_url . 'it_diggita.png">', 'Diggita (www.diggita.it)'); ?>
<?php bkmrk_field_checkbox('it_upnews', '<img src="' . $image_url . 'it_upnews.png">', 'UpNews (www.upnews.it)'); ?>
<?php bkmrk_field_checkbox('it_bookmark', '<img src="' . $image_url . 'it_bookmark.png">', 'Bookmark (www.bookmark.it)'); ?>
<?php bkmrk_field_checkbox('it_wikio', '<img src="' . $image_url . 'it_wikio.png">', 'Wikio (www.wikio.it)'); ?>
<?php bkmrk_field_checkbox('it_badzu', '<img src="' . $image_url . 'it_badzu.png">', 'Badzu (www.badzu.net)'); ?>
</table>
<p>More? Let me know: <a href="mailto:satollo@gmail.com">satollo@gmail.com</a>.</p>

<h4>Netherlands</h4>
<table class="form-table">
<?php bkmrk_field_checkbox('nl_nujij', '<img src="' . $image_url . 'nl_nujij.png">', '(nujij.nl)'); ?>
<?php bkmrk_field_checkbox('nl_ekudos', '<img src="' . $image_url . 'nl_ekudos.png">', '(www.ekudos.nl)'); ?>
<?php bkmrk_field_checkbox('nl_msnreporter', '<img src="' . $image_url . 'nl_msnreporter.png">', '(reporter.msn.nl)'); ?>
<?php bkmrk_field_checkbox('nl_tipt', '<img src="' . $image_url . 'nl_tipt.png">', '(www.tipt.nl)'); ?>
<?php bkmrk_field_checkbox('nl_wvwo', '<img src="' . $image_url . 'nl_wvwo.png">', '(watvindenwijover.nl)'); ?>
<?php bkmrk_field_checkbox('nl_tagmos', '<img src="' . $image_url . 'nl_tagmos.png">', '(www.tagmos.nl)'); ?>
</table>
<p>More? Let me know: <a href="mailto:satollo@gmail.com">satollo@gmail.com</a>.</p>


<h4>Arabian</h4>
<table class="form-table">
<?php bkmrk_field_checkbox('ar_darabet', '<img src="' . $image_url . 'ar_darabet.png">', '&#1590;&#1585;&#1576;&#1578; (darabet.com)'); ?>
<?php bkmrk_field_checkbox('ar_khabbr', '<img src="' . $image_url . 'ar_khabbr.png">', '&#1582;&#1576;&#1585; (www.khabbr.com)'); ?>

<?php bkmrk_field_checkbox('ar_wapher', '<img src="' . $image_url . 'ar_wapher.png">', '&#1608;&#1575;&#1601;&#1585; (www.wapher.com)'); ?>
<?php bkmrk_field_checkbox('ar_qulqal', '<img src="' . $image_url . 'ar_qulqal.png">', '&#1602;&#1608;&#1604; &#1602;&#1575;&#1604; (www.qulqal.com)'); ?>
<?php bkmrk_field_checkbox('ar_ef7at', '<img src="' . $image_url . 'ar_ef7at.png">', '&#1575;&#1601;&#1581;&#1578; (www.ef7at.com)'); ?>
<?php bkmrk_field_checkbox('ar_efleg', '<img src="' . $image_url . 'ar_efleg.png">', '&#1575;&#1601;&#1604;&#1602; (www.efleg.com)'); ?>
<?php bkmrk_field_checkbox('ar_adifni', '<img src="' . $image_url . 'ar_adifni.png">', 'Adifni (www.adifni.com)'); ?>
</table>
<p>More? Let me know: <a href="mailto:satollo@gmail.com">satollo@gmail.com</a>.</p>

<h4>Chinese</h4>
<table class="form-table">
<?php bkmrk_field_checkbox('haohao', '<img src="' . $image_url . 'haohao.png">', 'HaoHao Report (haohaoreport.com)'); ?>
</table>
<p>More? Let me know: <a href="mailto:satollo@gmail.com">satollo@gmail.com</a>.</p>

<h4>Czech</h4>
<table class="form-table">
<?php bkmrk_field_checkbox('cz_jagg', '<img src="' . $image_url . 'cz_jagg.png">', 'Jagg (www.jagg.cz)'); ?>
<?php bkmrk_field_checkbox('cz_linkuj', '<img src="' . $image_url . 'cz_linkuj.png">', 'Linkuj (www.linkuj.cz)'); ?>
<?php bkmrk_field_checkbox('cz_topclanky', '<img src="' . $image_url . 'cz_topclanky.png">', 'Topcl�nky (www.topclanky.cz)'); ?>
</table>
<p>More? Let me know: <a href="mailto:satollo@gmail.com">satollo@gmail.com</a>.</p>

<h4>Indian</h4>
<table class="form-table">
<?php bkmrk_field_checkbox('indianpad', '<img src="' . $image_url . 'indianpad.png">', 'Indianpad (indianpad.com)'); ?>
</table>
<p>More? Let me know: <a href="mailto:satollo@gmail.com">satollo@gmail.com</a>.</p>

<h4>Lithuanian</h4>
<table class="form-table">
<?php bkmrk_field_checkbox('lt_cut', '<img src="' . $image_url . 'lt_cut.png">', '(cut.lt)'); ?>
<?php bkmrk_field_checkbox('lt_topix', '<img src="' . $image_url . 'lt_topix.png">', '(topix.lt)'); ?>
<?php bkmrk_field_checkbox('lt_zynios', '<img src="' . $image_url . 'lt_zynios.png">', '(zynios.lt)'); ?>
</table>
<p>More? Let me know: <a href="mailto:satollo@gmail.com">satollo@gmail.com</a>.</p>

<h4>Russian</h4>
<table class="form-table">
<?php bkmrk_field_checkbox('ru_momesto', '<img src="' . $image_url . 'ru_moemesto.png">', '(moemesto.ru)'); ?>
<?php bkmrk_field_checkbox('ru_memori', '<img src="' . $image_url . 'ru_memori.png">', '(memori.ru)'); ?>
</table>
<p>More? Let me know: <a href="mailto:satollo@gmail.com">satollo@gmail.com</a>.</p>

<h4>Slovak</h4>
<table class="form-table">
<?php bkmrk_field_checkbox('sk_linkuj', '<img src="' . $image_url . 'sk_linkuj.png">', 'Linkuj (www.linkuj.sk)'); ?>
<?php bkmrk_field_checkbox('sk_vybrali', '<img src="' . $image_url . 'sk_vybrali.png">', 'Vybrali (vybrali.sme.sk)'); ?>
</table>
<p>More? Let me know: <a href="mailto:satollo@gmail.com">satollo@gmail.com</a>.</p>

<h4>Sweden</h4>
<table class="form-table">
<?php bkmrk_field_checkbox('se_pusha', '<img src="' . $image_url . 'se_pusha.png">', '(www.pusha.se)'); ?>
</table>
<p>More? Let me know: <a href="mailto:satollo@gmail.com">satollo@gmail.com</a>.</p>

<p class="sumit"><input type="submit" name="save" value="Save"/></p>

</form>
</div>