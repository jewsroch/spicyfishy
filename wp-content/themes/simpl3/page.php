<?php
 get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
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

<?php the_content('Read the rest of this entry &raquo;'); ?>
<p><?php link_pages('<p><strong>Pages:</strong> ', '</p>', 'number'); ?></p>

					<!-- epost -->
				</div>
			</div>
			<div class="extra"></div>
<?php endwhile; endif; ?>
		</div>
	</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
