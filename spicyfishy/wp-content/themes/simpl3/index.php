<?php
 get_header(); ?>
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
			<ul class="pmeta">
				<li>Posted by <?php the_author() ?></li>
				<li>On <?php the_time('F j, Y') ?></li>
				<li><br />Filed under <?php the_category(', ') ?></li>
				<?php if (function_exists('the_tags')) { the_tags('<ul class="pmeta-tags"><li>Tags: ',',</li> <li>','</li></ul>'); } ?>
				<li><br /><?php comments_popup_link('No Comments', '1 Comment', '% Comments' );?></li>
				<?php edit_post_link('Edit', '<li>', '</li>'); ?>
			</ul>
			<div class="apost">
				<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<div class="pmain">
					<!-- spost -->

<?php the_content('Read more...'); ?>

					<!-- epost -->
				</div>
<?php if ($count==1) { ?>

<!-- PLACE YOUR 468x60 ADSENSE CODE (OR BANNER) BELOW -->


<!-- PLACE YOUR 468x60 ADSENSE CODE (OR BANNER) ABOVE -->

<?php } ?>
<?php $count = $count + 1; ?>

			</div>

			<div class="extra"></div>
<?php endwhile; ?>

			<div class="lead">
				<span class="ppre"><?php next_posts_link('&laquo; Previous Posts') ?></span>
				<span class="pnex"><?php previous_posts_link('Next Posts &raquo;') ?></span>
			</div>
<?php else : ?>
			<div class="apost">
				<h2 class="subh">Oops!</h2>
				<p class="nopost">Sorry, but you are looking for something that isn't here.</p>
			</div>
<?php endif; ?>
		</div>
	</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
