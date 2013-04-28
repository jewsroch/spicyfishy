<?php get_header(); ?>
<div id="content">

<?php if (have_posts()) : ?><?php $first = 1; while (have_posts()) : the_post(); ?>
<div class="post">

<h1 id="post-<?php the_ID(); ?>"><a href="<?php echo get_permalink() ?>" rel="bookmark" title="Permanent Link: <?php the_title(); ?>"><?php the_title(); ?></a></h1>

<div class="entry">

<?php if ( 1 == $first && is_home() && !is_paged() ) { 
  the_content('<br />|Read the Rest of the Entry...<br />');
  $first = 0;
} else {
the_content('<br />|Read the Rest of the Entry...<br />');
} ?>
</div>

<p class="postmetadata"><?php the_time(__('F jS, Y')) ?> | <?php comments_popup_link('0 Comments', '1 Comment', '% Comment'); ?><br />
Category: <?php the_category(', ') ?> | Tags: <?php the_tags( '', ', ', ''); ?></p>

</div>

<?php endwhile; ?>

<div class="pagenavigation2">
<div class="alignleft"><?php posts_nav_link('','',__('&laquo; Previous Posts','')) ?></div>
<div class="alignright"><?php posts_nav_link('',__('Next Posts &raquo;',''),'') ?></div>
</div>


<?php else : ?>
<h2 class="center"><?php _e('Not found.'); ?></h2>
<p class="center"><?php _e("Sorry, but you are looking for something that isn't here."); ?></p>
<?php include (TEMPLATEPATH . "/searchform.php"); ?>
<?php endif; ?>
</div>

</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>

