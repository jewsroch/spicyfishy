<?php
/*
 * For installations that are using a viddlerconfig.php file. If there are no credentials in the db, add them
 * */
if(file_exists('viddlerconfig.php')){
		include('viddlerconfig.php');
		if(isset($viddler_username) && $viddler_username != '' && get_option('viddler_yourusername') == ''){
			update_option('viddler_yourusername', $viddler_username);
			update_option('viddler_yourpassword', $viddler_password);
		}
}
?>
