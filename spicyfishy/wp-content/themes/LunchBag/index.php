<?php
 get_header(); ?>

<div id="content">

<div id="entry_content">
  <?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
	  <?php if(is_home()) { if ( function_exists('wp_list_comments') ) { ?> <div <?php post_class(); ?>> <?php }} ?>
        <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
          <p class="date"><?php the_time('F jS, Y') ?> | <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
          </p>
				
<div class="entry">
  <?php the_content('Continue Reading...'); ?>
    <?php if (is_single()) { ?>
      <?php comments_template(); ?>
        <?php } ?>
</div>

<?php if(is_home()) { if ( function_exists('wp_list_comments') ) { ?></div><!-- close post_class --><?php }} ?>

<?php endwhile; ?>

<div class="navigation">
  <p class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></p>
  <p class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></p>
</div>

<?php else : ?>

<div class="entry">
  <p>You broke the Interwebs!</p>
</div>

<?php endif; ?>
	
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>