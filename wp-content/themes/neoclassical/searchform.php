<form method="get" id="search_form" action="<?php
 bloginfo('home'); ?>/">
	<input type="text" class="search_input" value="To search, type and hit enter" name="s" id="s" onfocus="if (this.value == 'To search, type and hit enter') {this.value = '';}" onblur="if (this.value == '') {this.value = 'To search, type and hit enter';}" />
	<input type="hidden" id="searchsubmit" value="Search" />
</form>
<br/>
<form Method="POST"  id="search_form" target='_newsub'  action="http://www.feedblitz.com/f/f.fbz?AddNewUserDirect">
<input type="hidden" name="sub" value="433574">
<input name="EMAIL" class="search_input" maxlength="255" type="text" size="26" value="To subscribe, type your email" onfocus="if (this.value == 'To subscribe, type your email') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Enter your Email to subscribe!';}" /><br>
<input name="FEEDID" type="hidden" value="433574">
<input name="PUBLISHER" type="hidden" value="9572033">

</form> 