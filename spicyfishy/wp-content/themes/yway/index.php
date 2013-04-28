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
  <div class="topComments"><?php comments_popup_link('No Comment', '1 Comment', '% Comments'); ?></div>
 <div class="topContent"><?php the_content('(continue reading...)'); ?></div>
 
 
 
 
 
  <div class="topTags"><?php the_tags('<em>Tags</em> : ', ', ', ''); ?></div>


  <div id="line2"></div><br>
<div class="cleared"></div>
</div> <!-- Closes topPost --><br/>



<?php endwhile; ?>

<?php else : ?>

<div class="topPost">
  <h2 class="topTitle"><a href="<?php the_permalink() ?>">Not Found</a></h2>
  <div class="topContent"><p>Sorry, but you are looking for something that isn't here. You can search again by using <a href="#searchform">this form</a>...</p></div>
</div> <!-- Closes topPost -->

<?php endif; ?>

<div id="nextprevious">
<div class="alignleft"><?php posts_nav_link('','',' <em>Previous</em> ') ?></div>
<div class="alignright"><?php posts_nav_link('','<em> Next</em> ','') ?></div>
<div class="cleared"></div>
</div>
</div> <!-- Closes contentwrapper-->



<?php get_sidebar(); ?>
<div class="cleared"></div>

</div><!-- Closes Main -->


<?php get_footer(); ?>