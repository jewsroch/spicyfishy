<?php
 get_header(); ?>
			<div class="postarchive">
				<h3 class="atitle">Archives by Month:</h3>
				<ul>

					<?php wp_get_archives('type=monthly'); ?>

				</ul>
				<h3 class="atitle">Archives by Subject:</h3>
				<ul>

					 <?php wp_list_cats(); ?>

				</ul>
			</div>
		</div>
	</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
