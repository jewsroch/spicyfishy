<form method="get" class="searchform" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<div>
<input type="text" value="Search term..." name="s" class="s" onblur="if(this.value=='')this.value='Search term...';" onfocus="if(this.value=='Search term...')this.value='';" />
</div>
</form>