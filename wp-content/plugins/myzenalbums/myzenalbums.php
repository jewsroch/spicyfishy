<?php

/*
Plugin Name: MYZenAlbums
Version: 1.2
Plugin URI: http://basri.my
Description: Plugin to show latest albums from ZenPhoto site. 
Author: Basri Mahayedin
Author URI: http://basri.my/
*/

//$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_settings('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';

//defaults
add_option('mzp_zentitle', 'Latest Albums');
add_option('mzp_zendbhost', 'localhost'); 
add_option('mzp_zendbname', 'database_name'); 
add_option('mzp_zendbuser', 'database_user'); 
add_option('mzp_zendbpass', 'database_password'); 
add_option('mzp_zenurl', 'http://yourdomain.com/gallery/'); 
add_option('mzp_zenpath', '/home/yourname/public_html/gallery/'); 
add_option('mzp_zenalbums', '3');
add_option('mzp_zenx', '100'); 
add_option('mzp_zeny', '100'); 
add_option('mzp_zenclass', ''); 


function myzenalbums_header()
{
?>
header
<?php
}

function myzenalbums()
{
	echo myzenalbums_return();		
}

function slug($str)
{
      $str = strtolower(trim($str));
      $str = preg_replace('/[^a-z0-9-\.]/', '-', $str);
      $str = preg_replace('/-+/', "-", $str);
      return $str;
}

function myzenalbums_return()
{	
	$zendbhost=get_option('mzp_zendbhost');
	$zendbname=get_option('mzp_zendbname');
	$zendbuser=get_option('mzp_zendbuser');
	$zendbpass=get_option('mzp_zendbpass');	
	
	$zenlink = mysql_connect($zendbhost, $zendbuser, $zendbpass) or die("Could not connect : " . mysql_error());
	
	mysql_select_db($zendbname) or die("could not open database");
	
	$sqlquery="select id,folder,title,thumb from zp_albums order by id desc LIMIT ".get_option('mzp_zenalbums');

	$albumresult = mysql_query($sqlquery) or die("Query failed : " . mysql_error());	

	while($picline = mysql_fetch_row($albumresult))
	{
		$x_size = get_option('mzp_zenx');
		$y_size = get_option('mzp_zeny');
		$thumb_w = get_option('mzp_zenx');
		$thumb_h = get_option('mzp_zeny');
		$zenclass = get_option('mzp_zenclass');
		
		//$imagepath = myzenalbums_slash(get_option('mzp_zenurl')) . $picline[1] . '/' . str_replace(" ","-",$picline[3]);		
		//$imagepath = myzenalbums_slash(get_option('mzp_zenurl')) . $picline[1] . '/' . $picline[3];		
		$imagepath = myzenalbums_slash(get_option('mzp_zenurl')) . $picline[1] . '/';		
		//$imagefullpath = myzenalbums_slash(get_option('mzp_zenpath')).'albums/'. $picline[1] . '/' . str_replace(" ","-",$picline[3]);				
		$imagefullpath = myzenalbums_slash(get_option('mzp_zenpath')).'albums/'. $picline[1] . '/' . $picline[3];				
		
		$thumbfullpath =myzenalbums_slash(get_option('mzp_zenpath')).'cache/'. $picline[1] . '-' . slug($picline[3]);
		$tempthumb = myzenalbums_slash(get_option('mzp_zenurl')).'cache/'. $picline[1] . '-' . slug($picline[3]);				

		if(!file_exists($thumbfullpath) || !myzenalbums_checkdimensions($thumbfullpath,$x_size,$y_size))
		{
			$img=ImageCreateFromJPEG($imagefullpath);

			$orig_w = ImageSX($img);
			$orig_h = ImageSY($img);

			if ($thumb_w/$orig_w*$orig_h>$thumb_h)
		   	   $thumb_w=round($thumb_h*$orig_w/$orig_h);
			else
			   $thumb_h=round($thumb_w*$orig_h/$orig_w);

			$thumbnail = imagecreatetruecolor($thumb_w, $thumb_h);
			imageCopyResampled($thumbnail,$img,0,0,0,0,$thumb_w,$thumb_h,ImageSX($img),ImageSY($img));
			
		
			imagejpeg($thumbnail, $thumbfullpath, 100);
			//echo $imagefullpath.'<br />';

		}
		$output = $output . "<li><a href='$imagepath' target=_new>".$picline[2]."</a><br /><a href='$imagepath' target=_new><img src='$tempthumb' style='$zenclass'></a><br />&nbsp;</li>";
	}

	mysql_free_result($albumresult);
	return("<ul>".$output."</ul>");
}

function myzenalbums_control()
{
	$filename = $_GET['page'];
	$filen=str_replace('\\\\','\\',$_GET['page']);
		
	if (isset($_POST['info_update'])) {

		?><div id="message" class="updated fade"><p><strong><?php 	
		
		update_option('mzp_zentitle', (string) $_POST["zentitle"]);	
		update_option('mzp_zendbhost', (string) $_POST["zendbhost"]);					
		update_option('mzp_zendbname', (string) $_POST["zendbname"]);					
		update_option('mzp_zendbuser', (string) $_POST["zendbuser"]);					
		update_option('mzp_zendbpass', (string) $_POST["zendbpass"]);
		update_option('mzp_zenurl', (string) $_POST["zenurl"]);					
		update_option('mzp_zenpath', (string) $_POST["zenpath"]);		
		update_option('mzp_zenalbums', (string) $_POST["zenalbums"]);
		update_option('mzp_zenx', (string) $_POST["zenx"]);										
		update_option('mzp_zeny', (string) $_POST["zeny"]);										
		update_option('mzp_zenclass', (string) $_POST["zenclass"]);
					
		echo "Configuration Updated!";

	    ?></strong></p></div><?php

	} 
	?>

	<div class="wrap">

	<h2>MYZenAlbums V1.2</h2>

	<form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?page='.$filen; ?>">
	<input type="hidden" name="info_update" id="info_update" value="true" />

	<fieldset class="options"> 
	<legend>General Options</legend>

	<table width="100%" border="0" cellspacing="0" cellpadding="6">
	
	<tr>
		<td width="15%" valign=top>Widget Title: </td>
		<td align="left"><input type="text" name="zentitle"  size="30" value="<?php echo get_option('mzp_zentitle'); ?>"></td>
	</tr>	

	<tr>
		<td width="15%" valign=top>Database host: </td>
		<td align="left"><input type="text" name="zendbhost"  size="30" value="<?php echo get_option('mzp_zendbhost'); ?>"></td>
	</tr>
	
	<tr>
		<td width="15%" valign=top>Database name: </td>
		<td align="left"><input type="text" name="zendbname"  size="30" value="<?php echo get_option('mzp_zendbname'); ?>"></td>
	</tr>	
	
	<tr>
		<td width="15%" valign=top>Database user: </td>
		<td align="left"><input type="text" name="zendbuser" size="30" value="<?php echo get_option('mzp_zendbuser'); ?>"></td>
	</tr>	
	
	<tr>
		<td width="15%" valign=top>Database password: </td>
		<td align="left"><input type="text" name="zendbpass" size="30" value="<?php echo get_option('mzp_zendbpass'); ?>"></td>
	</tr>	
	
	<tr>
		<td width="15%" valign=top>URL of Your ZenPhoto: </td>
		<td align="left"><input type="text" name="zenurl" size="60" value="<?php echo get_option('mzp_zenurl'); ?>"></td>
	</tr>
	
	<tr>
		<td width="15%" valign=top>Path of Your ZenPhoto: </td>
		<td align="left"><input type="text" name="zenpath" size="60" value="<?php echo get_option('mzp_zenpath'); ?>"></td>
	</tr>
	
	<tr>
		<td width="15%" valign=top>How many albums? (3 or 5 or 7): </td>
		<td align="left"><input type="text" name="zenalbums" size="10" value="<?php echo get_option('mzp_zenalbums'); ?>"></td>
	</tr>
	
	<tr>
		<td width="15%" valign=top>Max Thumb Width: </td>
		<td align="left"><input type="text" name="zenx" size="10" value="<?php echo get_option('mzp_zenx'); ?>"></td>
	</tr>
	
	<tr>
		<td width="15%" valign=top>Max Thumb Height: </td>
		<td align="left"><input type="text" name="zeny" size="10" value="<?php echo get_option('mzp_zeny'); ?>"></td>
	</tr>
	
	<tr>
		<td width="15%" valign=top>IMG Style: </td>
		<td align="left"><input type="text" name="zenclass" size="60" value="<?php echo get_option('mzp_zenclass'); ?>"></td>
	</tr>

	</table>

	<div class="submit">
		<input type="submit" name="info_update" value="<?php _e('Update options'); ?> &raquo;" />
	</div>
	</form>
	
	</div>

	<?php
}

function myzenalbums_addMenu()
{
	add_options_page("MYZenAlbums", "MYZenAlbums" , 8, __FILE__, 'myzenalbums_optionsMenu');
}	


function myzenalbums_optionsMenu()
{

	myzenalbums_control();

}

function widget_myzenalbums_init()
{
	if (!function_exists('register_sidebar_widget'))
	   return;
	   
	   //This displays the plugin's output as a widget.  You shouldn't need to modify it.
	   function widget_myzenalbums($args)
	   {
		extract($args);
		$title = get_option('mzp_zentitle');
	 	echo $before_widget;
        echo $before_title . $title . $after_title;
    	  myzenalbums();
		echo $after_widget;
	   }
										
										
										
		register_sidebar_widget('MYZenAlbums', 'widget_myzenalbums');
}

function myzenalbums_checkdimensions($filename, $wantedx_size, $wantedy_size) {
//check file dimensions against wanted dimensions. Return false if they do not match

$img=ImageCreateFromJPEG($filename);

$img_w = ImageSX($img);
$img_h = ImageSY($img);

if(($img_w != $wantedx_size) && ($img_w != $wantedy_size) && ($img_h != $wantedx_size) && ($img_h != $wantedy_size))
	   return false;

return true;
}



function myzenalbums_slash($path){
 
    $slash_type = (strpos($path, '\\')===0) ? 'win' : 'unix'; 
 
    $last_char = substr($path, strlen($path)-1, 1);
 
    if ($last_char != '/' and $last_char != '\\') {
        // no slash:
        $path .= ($slash_type == 'win') ? '\\' : '/';
    }
 
    return $path;
}


//Uncomment this if you want the options panel to appear under the Admin Options interface
add_action('admin_menu', 'myzenalbums_addMenu');

//Uncomment this is you need to include some code in the header
//add_action('wp_head', 'zenlatest_header');

//Uncomment this if you want the token to be called using a token in a post (<!--zenlatest-->)
//add_filter('the_content', 'content_zenlatest');

//You can comment this out if you're not creating this as a widget
add_action('plugins_loaded', 'widget_myzenalbums_init');

?>
