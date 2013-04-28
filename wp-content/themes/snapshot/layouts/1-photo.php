            <div class="grid_12 alpha">	

						<div class="post">
            
            				<h2><?php
 the_title(); ?></h2>
            				
            		</div><!-- /post -->            
            
            </div>

            <div class="grid_12 alpha">	

								<div class="large-screenshot">
				
										<div class="large-screenimg">
						
											<img src="<?php echo get_post_meta($post->ID, "large-image", $single = true); ?>" alt="<?php the_title(); ?>" />
					
										</div><!-- /screenimg -->
				
								</div><!-- /screenshot--> 
            
            </div>      
            
            <div style="clear:both;height:15px;"></div>      
                        
          <div class="grid_6 alpha">	
									
						<div class="post">
            
            				<h3>Photo Information</h3>
            
            				<p class="date"><strong>Submitted on:</strong> <?php the_time('d M y'); ?></p>
            
            				<p><strong>Category:</strong> <?php the_category(','); ?></p>
            
            				<p><strong>Photo Rating:</strong> <?php if(function_exists('the_ratings')) { the_ratings(); } ?></p>
					
						</div><!-- /post -->
            
           </div><!-- /grid_6 omega -->

          <div class="grid_6 omega">	
									
						<div class="post">
            
            				<h3>Author's Description:</h3>
                
                			<?php the_content(); ?>
					
						</div><!-- /post -->
            
           </div><!-- /grid_6 omega -->
           