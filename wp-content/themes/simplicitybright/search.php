<?php get_header(); ?>
<div id="content">
<?php if (have_posts()) : ?>
<h2 class="pagetitle"><?php _e('Search Results'); ?></h2>
<div>&nbsp;</div>
<div>&nbsp;</div>
<?php while (have_posts()) : the_post(); ?>
<div class="post">
<h1 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h1>


<div class="entry">
<?php the_excerpt() ?>
</div>


<p class="postmetadata"><?php the_time(__('F jS, Y')) ?> | <?php the_category(', ') ?> | Tags: <?php the_tags( '', ', ', ''); ?> | <?php comments_popup_link('0 Comments', '1 Comment', '% Comment'); ?></p>

</div>

<?php endwhile; ?>

<div class="pagenavigation2">
<div class="alignleft"><?php posts_nav_link('','',__('&laquo; Previous Posts','')) ?></div>
<div class="alignright"><?php posts_nav_link('',__('Next Posts &raquo;',''),'') ?></div>
</div>


<?php else : ?>
<h2><?php _e('Not found.') ?></h2>
<div>&nbsp;</div>
<div>&nbsp;</div>
<h2>Neue Suche</h2>
<?php include (TEMPLATEPATH . '/searchform.php'); ?>
<?php endif; ?>
</div>
</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>