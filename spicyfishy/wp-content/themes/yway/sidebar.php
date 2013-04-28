
		
<div id="sidebars">

<div id="sidebar_full">

<ul>

 <li>
<?php
 include (TEMPLATEPATH . '/welcome.php'); ?>
 </li>
</ul>

<div id="search2"><?php include (TEMPLATEPATH . '/searchform.php'); ?></div>
</div><!-- Closes Sidebar_full -->


<div id="sidebar_LR">

<div id="sidebar_left">

<ul>
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar_left') ) : ?>
<?php endif; ?>
</ul>

</div> 

<div id="sidebar_right">

<ul>
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar_right') ) : ?>


<?php endif; ?>
</ul>

</div> <!-- Closes Sidebar_right -->

</div>

<div class="cleared"></div>
</div> <!-- Closes Sidebars -->




