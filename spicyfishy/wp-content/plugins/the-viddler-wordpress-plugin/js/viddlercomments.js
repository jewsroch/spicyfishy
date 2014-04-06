$v=jQuery.noConflict();

$v(document).ready(function() {
	$v.facebox.settings.closeImage = 'http://static.cdn-ec.viddler.com/wp-plugin/v1/images/closelabel.gif'
	$v.facebox.settings.loadingImage = 'http://static.cdn-ec.viddler.com/wp-plugin/v1/images/loading.gif'

	// Wait for Facebox
	$v('a[rel*=facebox]').click(function(e) {
		e.preventDefault();
		
		$v.facebox(viddler_loadrecorder);
	});
	
	$v('a[rel*=reload]').facebox(function(){
		$v("#viddlerdiv").text('Change.');
	});
	
	$v('a[rel*=viddlervideo]').facebox();
	
});

function viddler_loadrecorder() {
	var gw = $v('#viddlergateway').val()+'viddlergateway.php';
	var m = 'viddler.videos.getRecordToken';
	
	$v.ajax({
		url: gw,
		type: 'GET',
		data: 'm='+m,
		dataType: 'text',
		timeout: 2500,
		error: function(text){
			$v('#facebox .content').html('Recorder not available. Please try again.').show();
			$v('#facebox .loading').hide();
		},
		success: function(token){			
		   $v('#facebox .content').html('<h2>Record your comment!</h2><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="449" height="380" id="viddler_recorder" align="middle"><param name="allowScriptAccess" value="always" /><param name="allowNetworking" value="all" /><param name="movie" value="http://static.cdn-ec.viddler.com/flash/recorder.swf" /><param name="quality" value="high" /><param name="scale" value="noScale"><param name="bgcolor" value="#000000" /><param name="flashvars" value="fake=1&recQuality=M&recordToken='+token+'" /><embed src="http://static.cdn-ec.viddler.com/flash/recorder.swf" quality="high" scale="noScale" bgcolor="#000000" allowScriptAccess="always" allowNetworking="all" width="449" height="380" name="viddler_recorder" flashvars="fake=1&recQuality=M&recordToken='+token+'" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object><p>When you\'re done recording, we\'ll pop the appropriate code into the comment box on the page.</p>').show();
			 $v('#facebox .loading').hide();
		}});
	
	
}


function viddler_addtocomment(vid) {

	var textareaboxid = $v('#viddlercommentpost').val();
	
	var thecomment = $v('#'+textareaboxid).val();
	
	if (textareaboxid == 'content') {
	
		viddler_insertAtCursor(document.post.content,'[viddler id-'+vid+' h-370 w-437]');
	
		//$v('#'+textareaboxid).val(thecomment+'\n'+'[viddler_video='+vid+',437,370]');
	} else {
		$v('#'+textareaboxid).val(thecomment+'\n'+'[viddler id-'+vid+']');
	}
	
	$v('#videoselect-'+vid).addClass('active');
	
}

function recordDone(u,uvn,vid) {
  	var html = '[viddler id-'+vid+']'+"\n";
  	
  	var textareaboxid = $v('#viddlercommentpost').val();
  	
  	var commentsofar = $v('#'+textareaboxid).val();
  	var csf = commentsofar;
  	$v('#'+textareaboxid).val(csf+"\n"+html);
  	
  	var earl = $v('#viddlerposturl').val();
  	var posttitle = $v('#viddlerposttitle').val();
  	var customtags = $v('#viddlercustomtags').val();
  	
  	var gw = $v('#viddlergateway').val()+'viddlergateway.php';
	var m = 'viddler.videos.setDetails';
	var s = 'video_id='+vid+'&earl='+earl+'&posttitle='+posttitle+'&customtags='+customtags+'&admin=N';
	
	$v.ajax({
		url: gw,
		type: 'GET',
		data: 'm='+m+'&'+s,
		dataType: 'text',
		timeout: 8200,
		error: function(){
			$v('#facebox .content').html('The video was not saved properly. Please try again.');
		},
		success: function(response){
			$v('#facebox .content').html('<h2>Video saved!</h2><p>Your video has been stored in the text box.  You may now close this window, or choose or record another video.  Thanks for commenting!</p>');
		}
	});
}

function loadViddlerVideo(rn,vid,playertype,width,height) {

	width +='';
	height +='';
	
	if (width == '' || !width) {
		width = '320';
	}
	if (height == '' || !width) {
		height = '282';
	}
	
	if (playertype=='simple') {
		playerurl = 'http://www.viddler.com/simple/'+vid+'/';
	} else {
		playerurl = 'http://www.viddler.com/player/'+vid+'/';
	}
	
	html = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'+width+'" height="'+height+'" id="viddler"><param name="wmode" value="opaque"><param name="bgcolor" value="#000" /><param name="movie" value="'+playerurl+'" /><param name="allowScriptAccess" value="always" /><param name="flashvars" value="autoplay=t" /><param name="allowFullScreen" value="true" /><embed src="'+playerurl+'" wmode="opaque" bgcolor="#000" width="'+width+'" height="'+height+'" type="application/x-shockwave-flash" allowScriptAccess="always" allowFullScreen="true" flashvars="autoplay=t" name="viddler"></embed></object>';
	
	$v('#viddlervideo-'+rn+'-'+vid).html(html);

}

function get_cookie (cookie_name) {
  var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );

  if ( results )
    return ( unescape ( results[2] ) );
  else
    return null;
}

function viddler_insertAtCursor(myField, myValue) {

  //IE support

  if (document.selection) {

    myField.focus();

    sel = document.selection.createRange();

    sel.text = myValue;

  }

  //MOZILLA/NETSCAPE support

  else if (myField.selectionStart || myField.selectionStart == '0') {

    var startPos = myField.selectionStart;

    var endPos = myField.selectionEnd;

    myField.value = myField.value.substring(0, startPos)

                  + myValue

                  + myField.value.substring(endPos, myField.value.length);

  } else {

    myField.value += myValue;

  }

}

// Only used to supress Firebug errors
function playStarted() {
return;
}
