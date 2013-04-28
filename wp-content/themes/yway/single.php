<?php
 get_header(); ?>

<div id="main">

<div id="contentwrapper">

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

<div class="topPost">
  <h2 class="topTitle"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
  
   
  
   
  <div id="line"></div>
  <p class="topMeta">In : <?php the_category(', '); ?>, Posted by <?php the_author_posts_link(); ?> on <?php the_time('M.m, Y') ?> </p>
  <div class="topComments">
    	<a href="#respond"><?php _e('Leave a Comment', '1 Comment', '% Comments'); ?></a>
  
  	</div>
 <div class="topContent"><?php the_content('(continue reading...)'); ?></div>
 
 
 
 
   <div class="topTags"><?php the_tags('<em>Tags</em> : ', ', ', ''); ?></div>


<div class="cleared"></div></div> <!-- Closes topPost -->

<div id="numberofcomments"><?php comments_number('No comments for this entry yet...', '1 comment for this entry:', '% comments for this entry:' );?></div>
<div id="comment">
<?php comments_template(); ?>
</div> <!-- Closes Comment -->

<?php endwhile; ?>


<?php else : ?>

<div class="topPost">
  <h2 class="topTitle"><a href="<?php the_permalink() ?>">Not Found</a></h2>
  <div class="topContent"><p>Sorry, but you are looking for something that isn't here. You can search again by using <a href="#searchform">this form</a>...</p></div>
</div> <!-- Closes topPost -->

<?php endif; ?>

</div> <!-- Closes contentwrapper-->


<?php get_sidebar(); ?>
<div class="cleared"></div>

</div><!-- Closes Main -->


<?php get_footer(); ?>