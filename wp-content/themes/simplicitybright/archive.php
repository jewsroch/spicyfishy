<?php get_header(); ?>

	<div id="content">


		<?php if (have_posts()) : ?>

		 <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
<?php /* If this is a category archive */ if (is_category()) { ?>				
		<h4>Archive for the Category <?php echo single_cat_title(); ?> </h4>
		
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h4>TArchive for <?php the_time('j. F Y'); ?></h4>
		
	 <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h4>Archive for <?php the_time('F Y'); ?></h4>

		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h4>Archive for <?php the_time('Y'); ?></h4>
				
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h4>Author Archive</h4>

		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>

		<?php } ?>

<div>&nbsp;</div>
<div>&nbsp;</div>
		

		<?php $first = 1; while (have_posts()) : the_post(); ?>

		<div class="post">
				<h1 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h1>
				
				
				<div class="entry">
					
<?php if ( 1 == $first && is_category() && !is_paged() ) { 
  the_content('<br />Den ganzen Beitrag lesen...<br />','relaxation');
  $first = 0;
} else {
the_content('<br />Den ganzen Beitrag lesen...<br />','relaxation');
} ?>
				</div>
		
				

<p class="postmetadata"><?php the_time(__('F jS, Y')) ?> | <?php the_category(', ') ?> | Tags: <?php the_tags( '', ', ', ''); ?> | <?php comments_popup_link('0 Comments', '1 Comment', '% Comment'); ?></p>

			</div>
	
		<?php endwhile; ?>

<div class="pagenavigation2">
<div class="alignleft"><?php posts_nav_link('','',__('&laquo; Previous Posts','')) ?></div>
<div class="alignright"><?php posts_nav_link('',__('Next Posts &raquo;',''),'') ?></div>
</div>
	
	<?php else : ?>

		<h2 class="center">Not found.</h2>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<?php endif; ?>
		
	</div>

</div>
<?php get_sidebar(); ?>

<?php get_footer(); ?>
