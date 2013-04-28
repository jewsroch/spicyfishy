<?php
 // Do not delete these lines
if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) die ('Please do not load this page directly. Thanks!');
if (!empty($post->post_password)) { // if there's a password
            if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
?>
				<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
<?php
				return;
	}
}
/* This variable is for alternating comment background */
$oddcomment = 'alt';
?>
<?php if ($comments) : ?>
				<h3 id="comments"><?php comments_number('No comments', 'One comment', '% comments' );?>...What do you think?</h3>
				<ol class="commentlist">
<?php foreach ($comments as $comment) : ?>

<?php $comment_type = get_comment_type(); ?>
<?php if($comment_type == 'comment') { ?>

<?php $newclass = 'commentlist'; if (apply_filters('user_id',$comment->user_id) == 1) { $newclass = ' commentlist-author'; } ?>
<li class="<?php echo $oddcomment.$newclass; ?>" id="comment-<?php comment_ID() ?>">




						<span class="cauth">
 Posted by <?php comment_author_link() ?>
<?php if ($comment->comment_approved == '0') : ?>
						<em>(Your comment is awaiting moderation)</em>
<?php endif; ?></span>
						<span class="cdate"><a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date('jS F, Y') ?> at <?php comment_time('g:i a') ?></a><?php edit_comment_link('Edit',' | ',''); ?></span>
						<?php comment_text() ?>
					</li>
<?php /* Changes every other comment to a different class */	
		if ('alt' == $oddcomment) $oddcomment = '';
		else $oddcomment = 'alt';
?>
<?php } else { $trackback = true; } /* End of is_comment statement */ ?>
<?php endforeach; /* end for each comment */ ?>
				</ol>

<?php if ($trackback === true) { ?>
<h3 id="comments">Trackbacks...</h3>
<ol class="trackbacklist">
<?php foreach ($comments as $comment) : ?>
<?php $comment_type = get_comment_type(); ?>
<?php if($comment_type != 'comment') { ?>
<li><?php comment_author_link() ?></li>
<?php } ?>
<?php endforeach; ?></ol>
<?php } ?>

<?php else : // this is displayed if there are no comments so far ?>
<?php if ('open' == $post->comment_status) : ?> 
<?php else : // comments are closed ?>
				<p class="nocomments">Comments are closed.</p>
<?php endif; ?>
<?php endif; ?>
<?php if ('open' == $post->comment_status) : ?>
				<h3 id="respond">What do you think? Join the discussion...</h3>
<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
				<p class="nocomments">You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php the_permalink(); ?>">logged in</a> to post a comment.</p>
<?php else : ?>
				<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
<?php if ( $user_ID ) : ?>
					<p>Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="Log out of this account">Logout &raquo;</a></p>
<?php else : ?>
						<p><label for="author"><strong>Name</strong> <?php if ($req) echo "(required)"; ?></label>
						<input type="text" name="author" id="author" value="<?php echo $comment_author; ?>" tabindex="1" /></p>
						<p><label for="email"><strong>E-mail</strong> (<?php if ($req) echo "required"; ?>)</label>
						<input type="text" name="email" id="email" value="<?php echo $comment_author_email; ?>" tabindex="2" /></p>
						<p><label for="url"><strong>Website</strong></label>
						<input type="text" name="url" id="url" value="<?php echo $comment_author_url; ?>" tabindex="3" /></p>
<?php endif; ?>
						<!-- <p><small><strong>XHTML:</strong> You can use these tags: <?php echo allowed_tags(); ?></small></p> -->
						<p><label for="comment"><strong>Your comment</strong></label>
						<textarea name="comment" id="comment" cols="" rows="" tabindex="4"></textarea></p>
						<p>
							<input name="submit" type="image" src="<?php bloginfo('template_directory'); ?>/images/submit.gif" alt="Submit Comment" id="submit" tabindex="5" value="submit comment" />
							<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
						</p>
<?php do_action('comment_form', $post->ID); ?>
				</form>
<?php endif; // If registration required and not logged in ?>
<?php endif; // if you delete this the sky will fall on your head ?>