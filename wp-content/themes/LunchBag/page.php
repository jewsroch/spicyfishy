<?php
 get_header(); ?>

<div id="content">
<div id="entry_content">
  <?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
      <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>




<div class="entry">
  <?php the_content('Read the rest of this entry &raquo;'); ?>
    <?php if (is_single()) { ?>
      <?php comments_template(); ?>
        <?php } ?>
</div>

<?php endwhile; ?>

<div class="navigation">
<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
</div>
		
<?php else : ?>

<div class="entry">
  <p>You broke the Interwebs!</p>
</div>

<?php endif; ?>
	
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>