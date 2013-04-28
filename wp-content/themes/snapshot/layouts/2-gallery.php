            <div class="grid_6 alpha">	
									
						<div class="post">
				
								<div class="screenshot">
				
										<div class="screenimg">
						
											<a href="<?php
 echo get_post_meta($post->ID, "url", $single = true); ?>" target="_blank"  title="View <?php the_title(); ?>"><img src="<?php echo get_post_meta($post->ID, "image", $single = true); ?>" alt="<?php the_title(); ?>" /></a>
					
										</div><!-- /screenimg -->
				
								</div><!-- /screenshot-->
					
						</div><!-- /post -->
            
          </div><!-- /grid_6 omega -->
            
          <div class="grid_6 omega">	
									
						<div class="post">
            
            				<h2><?php the_title(); ?></h2>
            
            				<p class="date"><strong>Submitted on:</strong> <?php the_time('d M y'); ?></p>
            
            				<p><strong>Website Address:</strong> <a title="<?php the_title(); ?>" href="<?php echo get_post_meta($post->ID, "url", $single = true); ?>" target="_blank" rel="bookmark"><?php echo get_post_meta($post->ID, "url", $single = true); ?></a>	</p>
            
            				<p><strong>Category:</strong> <?php the_category(','); ?></p>
            
            				<p><strong>Website Rating:</strong> <?php if(function_exists('the_ratings')) { the_ratings(); } ?></p>
					
						</div><!-- /post -->
            
           </div><!-- /grid_6 omega -->

            <div style="clear:both;height:15px;"></div>
            
            <div>
            	<h3>Author's Description:</h3>
                <?php the_content(); ?>
            </div>