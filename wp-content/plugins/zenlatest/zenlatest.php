<?php

/*
Plugin Name: ZenPhoto Latest Posts
Version: 1.2.7
Plugin URI: http://www.fam-friis.dk/?page_id=172
Description: Plugin to show latest images from ZenPhoto site. 
Author: Jakob Friis
Author URI: http://www.fam-friis.dk/
*/

//$pluginpath = str_replace(str_replace('\\', '/', ABSPATH), get_settings('siteurl').'/', str_replace('\\', '/', dirname(__FILE__))).'/';

function zenlatest_header()
{

	//External javascript file in the plugin directory
	echo '<script type="text/javascript" src="'. $pluginpath . 'filename.js"></script>';

	//Embedded javascpript
	echo '<script type="text/javascript">
	     //some javascript code
	     	    '. /* some php code */ '' . '
		    </script>';

		    //External css file in the plugin directory
		    echo '<link rel="stylesheet" href="'.$pluginpath .'filename.css" type="text/css" media="screen" />';

		    //Embedded css
		    echo '<style type="text/css">
		    	 /* some CSS */
			 </style>';
}

function zenlatest($echo = 'true',$parameter1 = '',$parameter2 = '', $parameter3 = '', $parameter4 = '', $parameter5 = '', $parameter6 = '', $parameter7 = '', $parameter9 = '', $parameter10 = '', $parameter11 = '', $parameter12 = '', $parameter13 = '', $parameter14 = '')
{
	$options = get_option('zenlatest');
	$parameter1 = (($parameter1 != '') ? $parameter1 : $options['parameter1']);
	$parameter2 = (($parameter2 != '') ? $parameter2 : $options['parameter2']);
	$parameter3 = (($parameter3 != '') ? $parameter3 : $options['parameter3']);
	$parameter4 = (($parameter4 != '') ? $parameter4 : $options['parameter4']);
	$parameter5 = (($parameter5 != '') ? $parameter5 : $options['parameter5']);
	$parameter6 = (($parameter6 != '') ? $parameter6 : $options['parameter6']);
	$parameter7 = (($parameter7 != '') ? $parameter7 : $options['parameter7']);
	$parameter9 = (($parameter9 != '') ? $parameter9 : $options['parameter9']);
	$parameter10 = (($parameter10 != '') ? $parameter10 : $options['parameter10']);
	$parameter11 = (($parameter11 != '') ? $parameter11 : $options['parameter11']);
	$parameter12 = (($parameter12 != '') ? $parameter12 : $options['parameter12']);
	$parameter13 = (($parameter13 != '') ? $parameter13 : $options['parameter13']);
	$parameter14 = (($parameter14 != '') ? $parameter14 : $options['parameter14']);
	$parameter15 = (($parameter15 != '') ? $parameter15 : $options['parameter15']);
	$parameter16 = (($parameter16 != '') ? $parameter16 : $options['parameter16']);
	$parameter17 = (($parameter17 != '') ? $parameter17 : $options['parameter17']);
	$parameter18 = (($parameter18 != '') ? $parameter18 : $options['parameter18']);
	$parameter19 = (($parameter19 != '') ? $parameter19 : $options['parameter19']);

	if($echo)
	{
		echo zenlatest_return ($parameter1, $parameter2, $parameter3, $parameter4, $parameter5, $parameter6, $parameter7, $parameter9, $parameter10, $parameter11, $parameter12, $parameter13, $parameter14, $parameter15, $parameter16, $parameter17, $parameter18, $parameter19, 0);
		}
		else
		{
			return zenlatest_return ($parameter1, $parameter2, $parameter3, $parameter4, $parameter5, $parameter6, $parameter7, $parameter9, $parameter10, $parameter11, $parameter12, $parameter13, $parameter14, $parameter15, $parameter16, $parameter17, $parameter18, $parameter19, 0);
			}
}

function zenlatestpost($echo = 'true',$parameter1 = '',$parameter2 = '', $parameter3 = '', $parameter4 = '', $parameter5 = '', $parameter6 = '', $parameter7 = '', $parameter9 = '', $parameter10 = '', $parameter11 = '', $parameter12 = '', $parameter13 = '', $parameter14 = '')
{
	$options = get_option('zenlatest');
	$parameter1 = (($parameter1 != '') ? $parameter1 : $options['parameter1']);
	$parameter2 = (($parameter2 != '') ? $parameter2 : $options['parameter2']);
	$parameter3 = (($parameter3 != '') ? $parameter3 : $options['parameter3']);
	$parameter4 = (($parameter4 != '') ? $parameter4 : $options['parameter4']);
	$parameter5 = (($parameter5 != '') ? $parameter5 : $options['parameter5']);
	$parameter6 = (($parameter6 != '') ? $parameter6 : $options['parameter6']);
	$parameter7 = (($parameter7 != '') ? $parameter7 : $options['parameter7']);
	$parameter9 = (($parameter9 != '') ? $parameter9 : $options['parameter9']);
	$parameter10 = (($parameter10 != '') ? $parameter10 : $options['parameter10']);
	$parameter11 = (($parameter11 != '') ? $parameter11 : $options['parameter11']);
	$parameter12 = (($parameter12 != '') ? $parameter12 : $options['parameter12']);
	$parameter13 = (($parameter13 != '') ? $parameter13 : $options['parameter13']);
	$parameter14 = (($parameter14 != '') ? $parameter14 : $options['parameter14']);
	$parameter15 = (($parameter15 != '') ? $parameter15 : $options['parameter15']);
	$parameter16 = (($parameter16 != '') ? $parameter16 : $options['parameter16']);
	$parameter17 = (($parameter17 != '') ? $parameter17 : $options['parameter17']);
	$parameter18 = (($parameter18 != '') ? $parameter18 : $options['parameter18']);
	$parameter19 = (($parameter19 != '') ? $parameter19 : $options['parameter19']);

	if($echo)
	{
		echo zenlatest_return ($parameter1, $parameter2, $parameter3, $parameter4, $parameter5, $parameter6, $parameter7, $parameter9, $parameter10, $parameter11, $parameter12, $parameter13, $parameter14, $parameter15, $parameter16, $parameter17, $parameter18, $parameter19, 1);
		}
		else
		{
			return zenlatest_return ($parameter1, $parameter2, $parameter3, $parameter4, $parameter5, $parameter6, $parameter7, $parameter9, $parameter10, $parameter11, $parameter12, $parameter13, $parameter14, $parameter15, $parameter16, $parameter17, $parameter18, $parameter19, 1);
			}
}

function zenlatest_return ($parameter1, $parameter2, $parameter3, $parameter4, $parameter5, $parameter6, $parameter7, $parameter9, $parameter10, $parameter11, $parameter12, $parameter13, $parameter14, $parameter15, $parameter16, $parameter17, $parameter18, $parameter19, $parameter20)
{
	global $pluginpath;

	//customize the plugin
	$dbhost = $parameter4;
	$dbtable = $parameter5;
	$dbuser = $parameter6;
	$dbpassword = $parameter7;
	$zeninstallation = $parameter9;
	$zenpath = $parameter10;
	$plugincache = $parameter11;
	$finalthumbpath = $parameter12;	
	$showcount = $parameter1;

	if($parameter13 == 'on')
		$ahref=true;
	else
		$ahref=false;

	if($parameter19 == 'on')
		$ahrefalbums=true;
	else
		$ahrefalbums=false;

	$wphost=$parameter14;
	$wpdb=$parameter15;
	$wpuser=$parameter16;
	$wppass=$parameter17;
	$excludealbums = "";
	$albumpath = "";
	$useinpost = $parameter20;

	// Forbinder, vælger database
	
	$zenlink = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Could not connect : " . mysql_error());
	
	mysql_select_db($dbtable) or die("could not open database");

	//exclude albums. First split the string if needed
	if (strlen($parameter18) >0)
	{
	   $albcount = substr_count($parameter18, ",");
	   
	   $findalbum = explode(",",$parameter18);
	   
	   for($i = 0;$i<=$albcount;$i++)
	   {
		$excquery = "SELECT `id` FROM zp_albums where `title` = \"$findalbum[$i]\"";
		$excres = mysql_query($excquery) or die("Query failed : " . mysql_error());
		$test = mysql_fetch_row($excres);

		if($i==0)
			$excludealbums = $test[0];
		else
			$excludealbums = $excludealbums . " and albumid != " . $test[0]; 
  	   }	
	}	
	else
		$excludealbums = "0";

	// Udfører SQL forespørgsel
	$picquery = "SELECT `albumid`,`filename` FROM `zp_images` where albumid != $excludealbums order by `id` DESC limit 0,$showcount";

	$picresult = mysql_query($picquery) or die("Query failed : " . mysql_error());

	//open a handle to the directory
	$handle = opendir($plugincache);
	//intitialize our counter
	$count = 0;
	//loop through the directory
	while (false !== ($file = readdir($handle))) {
	      //removing the . & .. entries
	      if ($file !== '.' && $file !== '..') {
			$count++;
		}
	}
	if($count>$showcount)
	{		
		//open a handle to the directory
		$handle = opendir($plugincache);
		//intitialize our counter
		$count = 0;
		//loop through the directory
		while (false !== ($file = readdir($handle))) {
	      	      //removing the . & .. entries
	      	      if ($file !== '.' && $file !== '..') {
		      	 	//remove the file
				$filename = $plugincache . "/" . $file;
				unlink($filename);
			}
		}
	}		

	while($picline = mysql_fetch_row($picresult))
	{
		$x_size = $parameter3;
		$y_size = $parameter2;
		$thumb_w = $parameter3;
		$thumb_h = $parameter2;

		$albumquery = "SELECT `folder` FROM `zp_albums` where `id` = $picline[0]";
		$albumresult = mysql_query($albumquery) or die("Query failed : " . mysql_error());

 		$albumline = mysql_fetch_row($albumresult);
		$albumpath = str_replace("albums/", "", $zeninstallation);
		$albumpath = $albumpath . "/index.php?album=" . $albumline[0];
		$imagepath = $zeninstallation . "/" . $albumline[0] . "/" . $picline[1];
		$imagefullpath = $zenpath . "/" . $albumline[0] . "/" . $picline[1];
		$thumbfullpath = $plugincache . str_replace(" ","-",$picline[1]);
		$tempthumb = $finalthumbpath . str_replace(" ","-",$picline[1]);

		//Count the files in cache. If it does not match showcount - clear the directory and let the thumbs be regenerated (not optimal but easiest)
		
		//for each picture found in DB - check if the thumb parameters match the files dimensions - otherwise regenerate		
		if(!file_exists($thumbfullpath) || !checkdimensions($thumbfullpath,$x_size,$y_size))
		{
			echo $imagefullpath;
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
		}
		if($ahref==true)
		{
			if($ahrefalbums==true)
			{
				if($useinpost==1)
					$output = $output . "<a href=$albumpath target=_new><img src=$tempthumb></a>";
				else
					$output = $output . "<center><a href=$albumpath target=_new><img src=$tempthumb></a><br><br></center>";
			}
			else
			{
				if($useinpost==1)
					$output = $output . "<a href=$imagepath target=_new><img src=$tempthumb></a>&nbsp";
				else
					$output = $output . "<center><a href=$imagepath target=_new><img src=$tempthumb></a><br><br></center>";
			}
		}
		else
		{
			if($useinpost==1)
				$output = $output . "<img src=$tempthumb>&nbsp";
			else
				$output = $output . "<center><img src=$tempthumb><br><br></center>";
		}
	}
	// Befri resultatet fra hukommelseN
	mysql_free_result($picresult);
	mysql_free_result($albumresult);

	$wplink = mysql_connect($wphost, $wpuser, $wppass) or die("Could not connect to WP : " . mysql_error());
	
	mysql_select_db($wpdb) or die("could not open WP database");
	//mysql_select_db($dbwordpress) or die("Could not select database");

	return($output);
}

//This function can be used to insert the function output in a particular post.  
//In the code view of a Wordpress post, insert a tag like this: <!--shortname-->, or <!--shortname(paramater1value)-->.  
//The plugin will spit out its results at that point in the post content.
function content_zenlatest($content)
{
	
	if(preg_match('/<!--zenlatest-->/',$content,$matches))
	{
		//$parameter1 = $matches[1];
		$content = preg_replace('/<!--zenlatest(.*?)-->/',zenlatestpost(), $content);
	}
	
	//get the latest posts according to the main parameters.
	return $content;
}

//This function creates a backend option panel for the plugin.  It stores the options using the wordpress get_option function.
function zenlatest_control()
{
		$options = get_option('zenlatest');
			 if ( !is_array($options) )
			    {
				//This array sets the default options for the plugin when it is first activated.
		       	     $options = array('title'=>'Nyeste billeder', 'parameter1'=>'10', 'parameter2'=>'75', 'parameter3'=>'100');
		     	      }
			if ( $_POST['zenlatest-submit'] )
			{
				$options['title'] = strip_tags(stripslashes($_POST['zenlatest-title']));
				//One of these lines is needed for each parameter
				$options['parameter1'] = strip_tags(stripslashes($_POST['zenlatest-parameter1']));
				$options['parameter2'] = strip_tags(stripslashes($_POST['zenlatest-parameter2']));
				$options['parameter3'] = strip_tags(stripslashes($_POST['zenlatest-parameter3']));
				$options['parameter4'] = strip_tags(stripslashes($_POST['zenlatest-parameter4']));
				$options['parameter5'] = strip_tags(stripslashes($_POST['zenlatest-parameter5']));
				$options['parameter6'] = strip_tags(stripslashes($_POST['zenlatest-parameter6']));
				$options['parameter7'] = strip_tags(stripslashes($_POST['zenlatest-parameter7']));
				$options['parameter9'] = strip_tags(stripslashes($_POST['zenlatest-parameter9']));
				$options['parameter10'] = strip_tags(stripslashes($_POST['zenlatest-parameter10']));
				$options['parameter11'] = strip_tags(stripslashes($_POST['zenlatest-parameter11']));
				$options['parameter12'] = strip_tags(stripslashes($_POST['zenlatest-parameter12']));
				$options['parameter13'] = strip_tags(stripslashes($_POST['zenlatest-parameter13']));
$options['parameter14'] = strip_tags(stripslashes($_POST['zenlatest-parameter14']));
$options['parameter15'] = strip_tags(stripslashes($_POST['zenlatest-parameter15']));
$options['parameter16'] = strip_tags(stripslashes($_POST['zenlatest-parameter16']));
$options['parameter17'] = strip_tags(stripslashes($_POST['zenlatest-parameter17']));
				$options['parameter18'] = strip_tags(stripslashes($_POST['zenlatest-parameter18']));
$options['parameter19'] = strip_tags(stripslashes($_POST['zenlatest-parameter19']));
      			       update_option('zenlatest', $options);
			}
		   $title = htmlspecialchars($options['title'], ENT_QUOTES);
echo '<p style="text-align:right;"><label for="zenlatest-title">Title:</label><br> <input style="width: 200px;" id="zenlatest-title" name="zenlatest-title" type="text" value="'.$title.'" /></p>';

//You need one of these for each option/parameter.  You can use input boxes, radio buttons, checkboxes, etc.

echo '<p style="text-align:right;"><label for="zenlatest-parameter1">Image count:</label><br /> <input style="width: 200px;" id="zenlatest-parameter1" name="zenlatest-parameter1" type="text" value="'.$options['parameter1'].'" />';

echo '<p style="text-align:right;"><label for="zenlatest-parameter2">Thumbnail height (px):</label><br /> <input style="width: 200px;" id="zenlatest-parameter2" name="zenlatest-parameter2" type="text" value="'.$options['parameter2'].'" /></p>';

echo '<p style="text-align:right;"><label for="zenlatest-parameter3">Thumbnail width:</label><br /> <input style="width: 200px;" id="zenlatest-parameter3" name="zenlatest-parameter3" type="text" value="'.$options['parameter3'].'" /></p>';

//html table
echo '<TABLE border="0">';
echo '<TD>';
echo '<br><strong>Options for Zenphoto database</strong><br><br>';
echo '</TD>';
echo '<TD>';
echo '<br><strong>Options for Wordpress database</strong><br><br>';
echo '</TD>';

echo '<TR>';
echo '<TD>';
echo '<p style="text-align:right;"><label for="zenlatest-parameter4">Database host:</label><br /> <input style="width: 200px;" id="zenlatest-parameter4" name="zenlatest-parameter4" type="text" value="'.$options['parameter4'].'" /></p>';
echo '</TD>';
echo '<TD>';
echo '<p style="text-align:right;"><label for="zenlatest-parameter4">Database host:</label><br /> <input style="width: 200px;" id="zenlatest-parameter14" name="zenlatest-parameter14" type="text" value="'.$options['parameter14'].'" /></p>';
echo '</TD>';

echo '<TR>';
echo '<TD>';
echo '<p style="text-align:right;"><label for="zenlatest-parameter5">Database :</label><br /> <input style="width: 200px;" id="zenlatest-parameter5" name="zenlatest-parameter5" type="text" value="'.$options['parameter5'].'" /></p>';
echo '</TD>';
echo '<TD>';
echo '<p style="text-align:right;"><label for="zenlatest-parameter15">Database :</label><br /> <input style="width: 200px;" id="zenlatest-parameter15" name="zenlatest-parameter15" type="text" value="'.$options['parameter15'].'" /></p>';
echo '</TD>';
echo '</TR>';

echo '<TR>';
echo '<TD>';
echo '<p style="text-align:right;"><label for="zenlatest-parameter6">Database user:</label><br /> <input style="width: 200px;" id="zenlatest-parameter6" name="zenlatest-parameter6" type="text" value="'.$options['parameter6'].'" /></p>';
echo '</TD>';
echo '<TD>';
echo '<p style="text-align:right;"><label for="zenlatest-parameter16">Database user:</label><br /> <input style="width: 200px;" id="zenlatest-parameter16" name="zenlatest-parameter16" type="text" value="'.$options['parameter16'].'" /></p>';
echo '</TD>';
echo '</TR>';

echo '<TR>';
echo '<TD>';
echo '<p style="text-align:right;"><label for="zenlatest-parameter7">Database password:</label><br /> <input style="width: 200px;" id="zenlatest-parameter7" name="zenlatest-parameter7" type="text" value="'.$options['parameter7'].'" /></p>';
echo '</TD>';
echo '<TD>';
echo '<p style="text-align:right;"><label for="zenlatest-parameter17">Database password:</label><br /> <input style="width: 200px;" id="zenlatest-parameter17" name="zenlatest-parameter17" type="text" value="'.$options['parameter17'].'" /></p>';
echo '</TD>';
echo '</TR>';
echo '</TABLE>'; 

echo '<p style="text-align:right;"><label for="zenlatest-parameter9">ZenPhoto albums installation (http://.../albums/):</label><br /> <input style="width: 200px;" id="zenlatest-parameter9" name="zenlatest-parameter9" type="text" value="'.$options['parameter9'].'" /></p>';

echo '<p style="text-align:right;"><label for="zenlatest-parameter10">ZenPhoto path (/home/www/.../albums/):</label><br /> <input style="width: 200px;" id="zenlatest-parameter10" name="zenlatest-parameter10" type="text" value="'.$options['parameter10'].'" /></p>';

echo '<p style="text-align:right;"><label for="zenlatest-parameter11">ZenLatest cache path (/home/www/.../zenlatest/cache/):</label><br /> <input style="width: 200px;" id="zenlatest-parameter11" name="zenlatest-parameter11" type="text" value="'.$options['parameter11'].'" /></p>';

echo '<p style="text-align:right;"><label for="zenlatest-parameter12">Thumbnail path (http://www..../zenlatest/cache/):</label><br /> <input style="width: 200px;" id="zenlatest-parameter12" name="zenlatest-parameter12" type="text" value="'.$options['parameter12'].'" /></p>';

echo '<p style="text-align:right;"><label for="zenlatest-parameter18">Exclude albums (enter album name to exclude. Seperated with ,):</label><br /> <input style="width: 200px;" id="zenlatest-parameter18" name="zenlatest-parameter18" type="text" value="'.$options['parameter18'].'" /></p>';

if ($options['parameter13'] == 'on')
{
	echo '<p style="text-align:right;"><label for="zenlatest-parameter13">Display images as links:</label><br /> <input id="zenlatest-parameter13" name="zenlatest-parameter13" type="checkbox" checked=true /></p>';
}
else
{
	echo '<p style="text-align:right;"><label for="zenlatest-parameter13">Display images as links:</label><br /> <input id="zenlatest-parameter13" name="zenlatest-parameter13" type="checkbox" /></p>';
}
if ($options['parameter19'] == 'on')
{
	echo '<p style="text-align:right;"><label for="zenlatest-parameter19">Display images as links to albums:</label><br /> <input id="zenlatest-parameter19" name="zenlatest-parameter19" type="checkbox" checked=true /></p>';
}
else
{
	echo '<p style="text-align:right;"><label for="zenlatest-parameter19">Display images as links to albums:</label><br /> <input id="zenlatest-parameter19" name="zenlatest-parameter19" type="checkbox" /></p>';
}

echo '<input type="hidden" id="zenlatest-submit" name="zenlatest-submit" value="1" />';
}

//This function adds the options panel under the Options menu of the admin interface.  If you only want the options in the widget panel, you don't need this function, nor the zenlatest_optionsMenu one.
function zenlatest_addMenu()
{
	add_options_page("Zen Latest Posts", "Zen Latest Posts" , 8, __FILE__, 'zenlatest_optionsMenu');
}	

//This function is called by zenlatest_addMenu, and displays the options panel
function zenlatest_optionsMenu()
{
	echo '<div style="width:250px; margin:auto;"><form method="post">';
	zenlatest_control();
	echo '<p class="submit"><input value="Save Changes" type="submit"></form></p></div>';
}

//This function is a wrapper for all the widget specific functions
//You can find out more about widgets here: http://automattic.com/code/widgets/
function widget_zenlatest_init()
{
	if (!function_exists('register_sidebar_widget'))
	   return;
	   
	   //This displays the plugin's output as a widget.  You shouldn't need to modify it.
	   function widget_zenlatest($args)
	   {
		extract($args);
					
						$options = get_option('zenlatest');
							 $title = $options['title'];

							 	echo $before_widget;
								     echo $before_title . $title . $after_title;
								     	  zenlatest();
										echo $after_widget;
										}
										
										
										
										register_sidebar_widget('Zen Latest Posts', 'widget_zenlatest');
										//You'll need to modify these two numbers to get the widget control the right size for your options.  250 is a good width, but you'll need to change the 200 depending on how many options you add
										//register_widget_control('Zen Latest Posts', 'zenlatest_control', 300, 850);
}

function checkdimensions($filename, $wantedx_size, $wantedy_size) {
//check file dimensions against wanted dimensions. Return false if they do not match

$img=ImageCreateFromJPEG($filename);

$img_w = ImageSX($img);
$img_h = ImageSY($img);

if(($img_w != $wantedx_size) && ($img_w != $wantedy_size) && ($img_h != $wantedx_size) && ($img_h != $wantedy_size))
	   return false;

return true;
}

//Uncomment this if you want the options panel to appear under the Admin Options interface
add_action('admin_menu', 'zenlatest_addMenu');

//Uncomment this is you need to include some code in the header
//add_action('wp_head', 'zenlatest_header');

//Uncomment this if you want the token to be called using a token in a post (<!--zenlatest-->)
add_filter('the_content', 'content_zenlatest');

//You can comment this out if you're not creating this as a widget
add_action('plugins_loaded', 'widget_zenlatest_init');

?>
