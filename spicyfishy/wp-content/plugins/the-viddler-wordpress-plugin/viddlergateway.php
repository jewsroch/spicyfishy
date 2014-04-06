<?php 
// Include phpViddler
include('phpviddler/phpviddler.php');
require( '../../../wp-load.php' );
include('legacy_support.php');

// API Key specifically for this plugin
$v = new Viddler_v2('0118093f713643444556524f452f');

// Redirect to url
function redir($url)
{
	header('Location: ' . $url);
	exit;
}

// Is this the admin y?
$admin = (isset($_GET['admin'])) ? $_GET['admin'] : NULL;

if (isset($_GET['m']) && ! empty($_GET['m'])) {
	$m = urldecode(trim($_GET['m']));

	if ($m == 'viddler.users.auth') {
			
		$username = get_option('viddler_yourusername');
		$password = get_option('viddler_yourpassword');
		if (empty($username) || empty($password)) {
			echo 'Authentication error. You must supply your Viddler username and password.';
			exit;
		}
			
		$sessionid = $v->viddler_users_auth(array('user'=>get_option('viddler_yourusername'),'password'=>get_option('viddler_yourpassword')));
			
		if (array_key_exists('error',$sessionid)) {
			if ($admin == 'y') {
				redir('Location: '.$_GET['viddlercallback'].'&response=error');
			}
			echo 'error';
		} else {
			if ($admin == 'y') {
					
				// Set the cookie
				setcookie("viddlerwp[sessionid]", $sessionid['auth']['sessionid'], time()+3600);
				
				// Change header location to an error
				redir($_GET['viddlercallback'].'&sessionid='.$sessionid['auth']['sessiond']);
			}
			echo $sessionid['auth']['sessionid'];
		}
	}

	elseif ($m == 'viddler.users.register') {
		$user = $_GET['u'];
		$password = $_GET['p'];
		$fname = $_GET['fname'];
		$lname = $_GET['lname'];
		$email = $_GET['email'];
		$question = 'What is your favorite color? Say red.';
		$answer = 'red';
		$lang = 'en';
		$termsaccepted = '1';
		
		$userinfo = array(
      'user'=>$user,
		  'password'=>$password,
		  'fname'=>$fname,
		  'lname'=>$lname,
		  'email'=>$email,
		  'question'=>$question,
		  'answer'=>$answer,
		  'lang'=>$lang,
		  'termsaccepted'=>$termsaccepted
		);
		
		$userresponse = $v->viddler_user_register($userinfo);
		
		if (strlen($userresponse) > 250) {
			// There is probably an error.
			$userresponse['error'] = 'error';
		}
		
		if (array_key_exists('error',$userresponse)) {
			switch($userresponse['error']['code']) {
				case '105':
					echo 'error username';
				break;
				case '106':
					echo 'error email';
				break;
				default:
					echo 'error';
				break;
			}
		} else {
			echo $userresponse['user']['username'];
		}
	}

	elseif ($m == 'viddler.videos.getRecordToken') {
		$token = NULL;
		$auth = $v->viddler_users_auth(array('user'=>get_option('viddler_yourusername'),'password'=>get_option('viddler_yourpassword')));
		if (isset($auth['auth']['sessionid'])) {
			$token = $v->viddler_videos_getRecordToken(array('sessionid'=>$auth['auth']['sessionid']));
			$token = $token['record_token']['value'];
		}
	  echo $token;
	}

	elseif ($m == 'viddler.encoding.getStatus2') {
		$token = NULL;
		$auth = $v->viddler_users_auth(array('user'=>get_option('viddler_yourusername'),'password'=>get_option('viddler_yourpassword')));
		if (isset($auth['auth']['sessionid'])) {
			$response = $v->viddler_encoding_getStatus2(array('sessionid'=>$auth['auth']['sessionid']));
			echo json_encode($response);
		}
	}

	elseif ($m == 'viddler.videos.prepareUpload') {
		$auth = $v->viddler_users_auth(array('user'=>get_option('viddler_yourusername'),'password'=>get_option('viddler_yourpassword')));
		if (isset($auth['auth']['sessionid'])) {
			$response = $v->viddler_videos_prepareUpload(array('sessionid'=>$auth['auth']['sessionid']));
			echo json_encode($response['upload']);
		}
  }
	elseif ($m == 'viddler.videos.getDetails') {
		$auth = $v->viddler_users_auth(array('user'=>get_option('viddler_yourusername'),'password'=>get_option('viddler_yourpassword')));
		if (isset($auth['auth']['sessionid'])) {
			$response = $v->viddler_videos_getDetails(array('sessionid'=>$auth['auth']['sessionid'], 'video_id' => $_GET['video_id']));
			echo json_encode($response['video']);
		}
  }
	elseif ($m == 'viddler.videos.setDetails') {
		$auth = $v->viddler_users_auth(array('user'=>get_option('viddler_yourusername'),'password'=>get_option('viddler_yourpassword')));
		$sessionid = $auth['auth']['sessionid'];

		$video_id = $_GET['video_id'];
		$earl = $_GET['earl'];
		$posttitle = $_GET['posttitle'];
		$customtags = $_GET['customtags'];
		
		if (!$earl || $earl == '') {
			$title = 'Recorded with web cam';
			$tags = 'webcam';
			$description = '<p>No description given.</p>';
		} else {
			$title = 'In reply to: '.addslashes($posttitle);
			$tags = 'videocomment,comment,webcam,'.$customtags;
			$description = '<p>This is a video comment replying to a weblog entry entitled: <a href="'.$earl.'"><i>'.addslashes($posttitle).'</i></a>.</p>';
		}
		
		$video = $v->viddler_videos_setDetails(array(
			"sessionid" => $sessionid,
			"video_id" => $video_id,
			"title" => $title,
			"description" => $description,
			"tags" => $tags
		));
		
		if (array_key_exists('error',$video)) {
			echo 'error';
		} else {
			echo 'success';
		}
	}

	elseif ($m == 'viddler.videos.getByUser') {
		$sessionid = $_GET['sessionid'];
		$username = $_GET['u'];
		$p = $_GET['page'];
		
		$p = (isset($p) && ! empty($p) && is_numeric($p)) ? $p : 1;
		
		$params = array(
		 'visibility'=>'public,invite,embed',
		 'page'=>$p,
		 'per_page'=>9
		);
		
		if (! empty($sessionid)) {
		 $params['sessionid'] = $sessionid;
		}
		else {
		 $params['user'] = $username;
		}

		$videos = $v->viddler_videos_getByUser($params);
		$html = '';
		
		$numofvideos= count($videos['list_result']['video_list']);
		
		if ($numofvideos > 1) {
			foreach ($videos['list_result']['video_list'] as $video) {
				$html .= '<li id="videoselect-'.$video['id'].'"><a href="#comment" onclick="viddler_addtocomment(\''.$video['id'].'\');"><p><img width="85" height="62" src="'.$video['thumbnail_url'].'" class="viddlervideothumb" /></p><p class="videotitle">'.substr($video['title'],0,11).'...</a></p></li>';
			}
		} else {
			$html .= '<li  id="videoselect-'.$videos['video_list']['video']['id'].'"><a href="#comment" onclick="viddler_addtocomment(\''.$videos['video_list']['video']['id'].'\');"><p><img width="85" height="62" src="'.$videos['video_list']['video']['thumbnail_url'].'" class="viddlervideothumb" /></p><p class="videotitle">'.substr($videos['video_list']['video']['title'],0,11).'...</a></p></li>';
		}
	
		echo '<ul class="videos">'.$html.'</ul>';
		
		// Figure out paging
		echo "\n".'<p style="clear:both;" class="paging">';
		
		if ($numofvideos > 9) {
			// Previous
			if ($p > 1) {
				$prevpage = $p-1;
				echo '<a href="#choose" onclick="viddler_loadvideos(\''.$sessionid.'\',\''.$username.'\',\''.$prevpage.'\')">&laquo; previous set</a> |';
			}
			$numofpages = round($numofvideos / 9);
			echo ' <strong>set '.$p.' of '.$numofpages.'</strong> ';
			
			// Next
			if ($p < $numofpages) {
				$nextpage = $p+1;
				echo '| <a href="#choose" onclick="viddler_loadvideos(\''.$sessionid.'\',\''.$username.'\',\''.$nextpage.'\')">next set &raquo;</a>';
			}
		}
		echo '</p>';
	}
}
