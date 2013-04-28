<?php

//Widgets.php - broken out into its own file to make editing easier
//Contains all widget code for WP Comment Remix
/*
* ============================
* Display Upcoming Posts Widget
* ============================
*/                        
function wpcr_widget_register() {                                    
    if ( function_exists('wp_register_sidebar_widget') ) {
        //============================
        //Display Recent Comments Widget
        //============================
        function wpcr_display_recent_comments_widget($args) {
            $options = get_option('widget_wpcr_display_recent_comments');
            
            extract($args);
            echo $before_widget . $before_title . $options['title'] . $after_title;
            echo '<ul>';
            wpcr_display_recent_comments($options['template'], $options['max'], $options['hide_admin_comments']);
            echo '</ul>';
            echo $after_widget;
        }                                                                             
        function wpcr_display_recent_comments_widget_control() {            
            if(!$options = get_option('widget_wpcr_display_recent_comments')) $options = array('title'=>'Recent Comments','template'=>'%g <a href="%au">%an</a> on <a href="%pu#comment-%cid">%pt</a>','max'=>'5','hide_admin_comments'=>'1');   
            if ( $_POST["wpcr_display_recent_comments_submit"] ) {
                $options['title'] = stripslashes($_POST["wpcr-comments-title"]);        
                $options['template'] = stripslashes($_POST["wpcr-comments-template"]);
                $options['max'] = strip_tags(stripslashes($_POST["wpcr-comments-max"]));
                $options['hide_admin_comments'] = ($_POST["wpcr-hide-admin-comments"]=='on'?'':'1');
                update_option('widget_wpcr_display_recent_comments', $options);
            }                                                                  
            $title = htmlspecialchars($options['title'], ENT_QUOTES);
            $template = htmlspecialchars($options['template'], ENT_QUOTES);
            $max = htmlspecialchars($options['max'], ENT_QUOTES);
            $hide_admin_comments = $options['hide_admin_comments'];      
        ?>
            <p><label for="wpcr-comments-title"><?php _e('Title:', 'comment-remix'); ?> <input style="width: 250px;" id="wpcr-comments-title" name="wpcr-comments-title" type="text" value="<?= $title; ?>" /></label></p>               
            <p><label for="wpcr-comments-template"><?php _e('Template:', 'comment-remix'); ?> <input style="width: 250px;" id="wpcr-comments-template" name="wpcr-comments-template" type="text" value="<?= $template; ?>" /></label></p>
            <p><?php _e('The template is made up of HTML and tokens. You can get a list of available tokens at the', 'comment-remix'); ?> <a href='http://pressography.com/plugins/wp-comment-remix/#tokens-recent' target='_blank'><?php _e('plugin page', 'comment-remix'); ?></a></p>
            <p><label for="wpcr-comments-max"><?php _e('Max Displayed:', 'comment-remix'); ?> <input style="width: 50px;" id="wpcr-comments-max" name="wpcr-comments-max" type="text" value="<?= $max; ?>" /></label></p>
            <p><input id="wpcr-hide-admin-comments" name="wpcr-hide-admin-comments" type="checkbox" <?= ($hide_admin_comments=='1')?'':'checked="CHECKED"'; ?> /> <label for="wpcr-hide-admin-comments"><?php _e('Show Admin Comments', 'comment_remix'); ?></label></p>
            <input type="hidden" id="wpcr_display_recent_comments_submit" name="wpcr_display_recent_comments_submit" value="1" />
        <?php
        }
        $widget_ops = array('classname' => 'widget_recent_comments', 'description' => __( 'Displays the most recent comments', 'comment-remix' ) );
        wp_register_sidebar_widget('recent-comments', __('Recent Comments (Remix)', 'comment-remix'), 'wpcr_display_recent_comments_widget', $widget_ops);
        wp_register_widget_control('recent-comments', __('Recent Comments (Remix)', 'comment-remix'), 'wpcr_display_recent_comments_widget_control');

        function wpcr_display_recent_comments($template, $max, $hide_admin) {
            global $wpdb;
            $sql = "SELECT c.*, p.post_title FROM $wpdb->comments c INNER JOIN $wpdb->posts p ON (c.comment_post_id=p.ID) WHERE comment_approved = '1' AND comment_type not in ('trackback','pingback')";
            if ($hide_admin)
                $sql .= " AND c.user_id != 1";
            $sql .= " ORDER BY comment_date DESC LIMIT $max";
            $results = $wpdb->get_results($sql);
            
            $echoed=0;
            foreach ($results as $row) {
                $tags = array('%ct','%cd','%g','%pt','%pu','%au','%an','%cid');
                $replacements = array($row->comment_title,$row->comment_date,get_avatar($row->comment_author_email,'32'),$row->post_title,get_permalink($row->comment_post_ID),$row->comment_author_url,$row->comment_author,$row->comment_ID);
                echo '<li>' . str_replace($tags,$replacements,$template) . '</li>';
                $echoed=1;
            }
            if ($echoed==0)
                echo '<li>' . __('None','comment_remix') . '</li>';
        }
        
        //============================
        //Display Recent Trackbacks Widget
        //============================
        function wpcr_display_recent_trackbacks_widget($args) {
            $options = get_option('widget_wpcr_display_recent_trackbacks');
            
            extract($args);
            echo $before_widget . $before_title . $options['title'] . $after_title;
            echo '<ul>';
            wpcr_display_recent_trackbacks($options['template'], $options['max']);
            echo '</ul>';
            echo $after_widget;
        }                                                                             
        function wpcr_display_recent_trackbacks_widget_control() {            
            if(!$options = get_option('widget_wpcr_display_recent_trackbacks')) $options = array('title'=>'Recent Trackbacks','template'=>'<a href="%au">%an</a> on <a href="%pu">%pt</a>','max'=>'5');   
            if ( $_POST["wpcr_display_recent_trackbacks_submit"] ) {
                $options['title'] = stripslashes($_POST["wpcr-trackbacks-title"]);        
                $options['template'] = stripslashes($_POST["wpcr-trackbacks-template"]);
                $options['max'] = strip_tags(stripslashes($_POST["wpcr-trackbacks-max"]));
                update_option('widget_wpcr_display_recent_trackbacks', $options);
            }                                                                  
            $title = htmlspecialchars($options['title'], ENT_QUOTES);
            $template = htmlspecialchars($options['template'], ENT_QUOTES);
            $max = htmlspecialchars($options['max'], ENT_QUOTES);
        ?>
            <p><label for="wpcr-trackbacks-title"><?php _e('Title:', 'comment-remix'); ?> <input style="width: 250px;" id="wpcr-trackbacks-title" name="wpcr-trackbacks-title" type="text" value="<?= $title; ?>" /></label></p>               
            <p><label for="wpcr-trackbacks-template"><?php _e('Template:', 'comment-remix'); ?> <input style="width: 250px;" id="wpcr-trackbacks-template" name="wpcr-trackbacks-template" type="text" value="<?= $template; ?>" /></label></p>
            <p><?php _e('The template is made up of HTML and tokens. You can get a list of available tokens at the', 'comment-remix'); ?> <a href='http://pressography.com/plugins/wp-comment-remix/#tokens-recent' target='_blank'><?php _e('plugin page', 'comment-remix'); ?></a></p>
            <p><label for="wpcr-trackbacks-max"><?php _e('Max Displayed:', 'comment-remix'); ?> <input style="width: 50px;" id="wpcr-trackbacks-max" name="wpcr-trackbacks-max" type="text" value="<?= $max; ?>" /></label></p>
            <input type="hidden" id="wpcr_display_recent_trackbacks_submit" name="wpcr_display_recent_trackbacks_submit" value="1" />
        <?php
        }
        $widget_ops = array('classname' => 'widget_recent_trackbacks', 'description' => __( 'Displays the most recent trackbacks', 'comment-remix' ) );
        wp_register_sidebar_widget('recent-trackbacks', __('Recent Trackbacks (Remix)', 'comment-remix'), 'wpcr_display_recent_trackbacks_widget', $widget_ops);
        wp_register_widget_control('recent-trackbacks', __('Recent Trackbacks (Remix)', 'comment-remix'), 'wpcr_display_recent_trackbacks_widget_control');

        function wpcr_display_recent_trackbacks( $template, $max) {
            global $wpdb;
            $results = $wpdb->get_results("SELECT c.*, p.post_title FROM $wpdb->comments c INNER JOIN $wpdb->posts p ON (c.comment_post_id=p.ID) WHERE comment_approved = '1' AND comment_type in ('trackback','pingback') ORDER BY comment_date DESC LIMIT $max");
            //echo "SELECT c.*, p.post_title FROM $wpdb->trackbacks c INNER JOIN $wpdb->trackbacks p ON (c.comment_post_id=p.ID) WHERE comment_approved = '1' AND comment_type not in ('trackback','pingback') ORDER BY comment_date LIMIT $max";
            
            $echoed=0;
            foreach ($results as $row) {
                $tags = array('%ct','%cd','%g','%pt','%pu','%au','%an','%cid');
                $replacements = array($row->comment_title,$row->comment_date,get_avatar($row->comment_author_email,'32'),$row->post_title,get_permalink($row->comment_post_id),$row->comment_author_url,$row->comment_author,$row->comment_ID);
                echo '<li>' . str_replace($tags,$replacements,$template) . '</li>';
                $echoed=1;
            }
            if ($echoed==0)
                echo '<li>' . __('None','comment_remix') . '</li>';          
        }
        
        //============================
        //Display Most Active Discussions Widget
        //============================
        function wpcr_display_active_discussions_widget($args) {
            $options = get_option('widget_wpcr_display_active_discussions');
            
            extract($args);
            echo $before_widget . $before_title . $options['title'] . $after_title;
            echo '<ul>';
            wpcr_display_active_discussions($options['template'], $options['max'],$options['start_number'],$options['start_type']);
            echo '</ul>';
            echo $after_widget;
        }                                                                             
        function wpcr_display_active_discussions_widget_control() {            
            if(!$options = get_option('widget_wpcr_display_active_discussions')) $options = array('title'=>'Most Active Discussions','template'=>'<a href="%pu">%pt</a> (%c comments)','max'=>'5');   
            if ( $_POST["wpcr_display_active_discussions_submit"] ) {
                $options['title'] = stripslashes($_POST["wpcr-discussions-title"]);        
                $options['template'] = stripslashes($_POST["wpcr-discussions-template"]);  
                $options['max'] = strip_tags(stripslashes($_POST["wpcr-discussions-max"]));
                if ($_POST['wpcr-start-date']) {
                    $options['start_number'] = strip_tags(stripslashes($_POST["wpcr-comments-start-number"]));
                    $options['start_type'] = strip_tags(stripslashes($_POST["wpcr-comments-start-type"]));
                } else {
                    $options['start_number'] = '';
                    $options['start_type'] = '';
                }
                
                update_option('widget_wpcr_display_active_discussions', $options);
            }                                                                  
            $title = htmlspecialchars($options['title'], ENT_QUOTES);
            $template = htmlspecialchars($options['template'], ENT_QUOTES);
            $max = htmlspecialchars($options['max'], ENT_QUOTES);
            $start_number = htmlspecialchars($options['start_number'], ENT_QUOTES);
            $start_type = htmlspecialchars($options['start_type'], ENT_QUOTES);
        ?>
            <p><label for="wpcr-discussions-title"><?php _e('Title:', 'comment-remix'); ?> <input style="width: 250px;" id="wpcr-discussions-title" name="wpcr-discussions-title" type="text" value="<?= $title; ?>" /></label></p>               
            <p><label for="wpcr-comments-template"><?php _e('Template:', 'comment-remix'); ?> <input style="width: 250px;" id="wpcr-discussions-template" name="wpcr-discussions-template" type="text" value="<?= $template; ?>" /></label></p>
            <p><?php _e('The template is made up of HTML and tokens. You can get a list of available tokens at the', 'comment-remix'); ?> <a href='http://pressography.com/plugins/wp-comment-remix/#tokens-discussions' target='_blank'><?php _e('plugin page', 'comment-remix'); ?></a></p>
            <p><label for="wpcr-discussions-max"><?php _e('Max Displayed:', 'comment-remix'); ?> <input style="width: 50px;" id="wpcr-discussions-max" name="wpcr-discussions-max" type="text" value="<?= $max; ?>" /></label></p>
            <p><label for="wpcr-start-date"><input id="wpcr-start-date" name="wpcr-start-date" type="checkbox" <?=($start_number!='')?'checked="CHECKED"':''; ?> /> 
            <?php _e('Show Only Discussions From The Past ','comment_remix'); ?></label>
            <input style="width: 50px;" id="wpcr-comments-start-number" name="wpcr-comments-start-number" type="text" value="<?=$start_number;?>" />
                <select style="width: 150px;" id="wpcr-comments-start-type" name="wpcr-comments-start-type">
                    <option value='YEAR' <?= ($start_type=='YEAR')?'SELECTED':''; ?>><?_e('Year(s)','comment_remix');?></option>
                    <option value='MONTH' <?= ($start_type=='MONTH')?'SELECTED':''; ?>><?_e('Month(s)','comment_remix');?></option>
                    <option value='DAY' <?= ($start_type=='DAY')?'SELECTED':''; ?>><?_e('Day(s)','comment_remix');?></option>
                </select>
            </p>
            <input type="hidden" id="wpcr_display_active_discussions_submit" name="wpcr_display_active_discussions_submit" value="1" />
        <?php
        }
        $widget_ops = array('classname' => 'widget_active_discussions', 'description' => __( 'Displays the most active discussions', 'comment-remix' ) );
        wp_register_sidebar_widget('active-discussions', __('Most Active Discussions (Remix)', 'comment-remix'), 'wpcr_display_active_discussions_widget', $widget_ops);
        wp_register_widget_control('active-discussions', __('Most Active Discussions (Remix)', 'comment-remix'), 'wpcr_display_active_discussions_widget_control');

        function wpcr_display_active_discussions( $template, $max, $start_number='', $start_type='MONTH') {
            global $wpdb;
            $sql = "SELECT p.*, c.comment_count FROM $wpdb->posts p INNER JOIN (SELECT comment_post_id, count(comment_ID) as comment_count from $wpdb->comments WHERE comment_approved='1'";
            if ($start_number)
                $sql .= " AND comment_date + INTERVAL $start_number $start_type >= '" . date("Y-m-d h:i:s") . "'";
            $sql .= " GROUP BY comment_post_id) c ON (c.comment_post_id=p.ID) ORDER BY c.comment_count DESC LIMIT $max";
            $results = $wpdb->get_results($sql);
            
            $echoed=0;
            foreach ($results as $row) {
                $tags = array('%pd','%pt','%pu','%c');
                $replacements = array($row->post_date,$row->post_title,get_permalink($row->ID),$row->comment_count);
                echo '<li>' . str_replace($tags,$replacements,$template) . '</li>';
                $echoed=1;
            }
            if ($echoed==0)
                echo '<li>' . __('None','comment_remix') . '</li>';
        }
        
        //============================
        //Display Most Active Commenters Widget
        //============================
        function wpcr_display_active_commenters_widget($args) {
            $options = get_option('widget_wpcr_display_active_commenters');
            
            extract($args);
            echo $before_widget . $before_title . $options['title'] . $after_title;
            echo '<ul>';
            wpcr_display_active_commenters($options['template'], $options['max'], $options['hide_admin'],$options['start_number'],$options['start_type']);
            echo '</ul>';
            echo $after_widget;
        }                                                                             
        function wpcr_display_active_commenters_widget_control() {            
            if(!$options = get_option('widget_wpcr_display_active_commenters')) $options = array('title'=>'Most Active Commenters','template'=>'<a href="%au">%g %an</a> (%c comments)','max'=>'5','hide_admin'=>'1');   
            if ( $_POST["wpcr_display_active_commenters_submit"] ) {
                $options['title'] = stripslashes($_POST["wpcr-commenters-title"]);        
                $options['template'] = stripslashes($_POST["wpcr-commenters-template"]);
                $options['max'] = strip_tags(stripslashes($_POST["wpcr-commenters-max"]));
                $options['hide_admin'] = ($_POST["wpcr-hide-admin"]=='on'?'':'1');
                if ($_POST['wpcr-active-commenters-start-date']) {
                    $options['start_number'] = strip_tags(stripslashes($_POST["wpcr-active-commenters-start-number"]));
                    $options['start_type'] = strip_tags(stripslashes($_POST["wpcr-active-commenters-start-type"]));
                } else {
                    $options['start_number'] = '';
                    $options['start_type'] = '';
                }
                update_option('widget_wpcr_display_active_commenters', $options);
            }                                                                  
            $title = htmlspecialchars($options['title'], ENT_QUOTES);
            $template = htmlspecialchars($options['template'], ENT_QUOTES);
            $max = htmlspecialchars($options['max'], ENT_QUOTES);
            $hide_admin = $options['hide_admin']; 
            $start_number = htmlspecialchars($options['start_number'], ENT_QUOTES);
            $start_type = htmlspecialchars($options['start_type'], ENT_QUOTES);
        ?>
            <p><label for="wpcr-commenters-title"><?php _e('Title:', 'comment-remix'); ?> <input style="width: 250px;" id="wpcr-commenters-title" name="wpcr-commenters-title" type="text" value="<?= $title; ?>" /></label></p>               
            <p><label for="wpcrcomments--template"><?php _e('Template:', 'comment-remix'); ?> <input style="width: 250px;" id="wpcr-commenters-template" name="wpcr-commenters-template" type="text" value="<?= $template; ?>" /></label></p>
            <p><?php _e('The template is made up of HTML and tokens. You can get a list of available tokens at the', 'comment-remix'); ?> <a href='http://pressography.com/plugins/wp-comment-remix/#tokens-active' target='_blank'><?php _e('plugin page', 'comment-remix'); ?></a></p>
            <p><label for="wpcr-commenters-max"><?php _e('Max Displayed:', 'comment-remix'); ?> <input style="width: 50px;" id="wpcr-commenters-max" name="wpcr-commenters-max" type="text" value="<?= $max; ?>" /></label></p>
            <p><label for="wpcr-hide-admin"><input id="wpcr-hide-admin" name="wpcr-hide-admin" type="checkbox" <?= ($hide_admin=='1')?'':'checked="CHECKED"'; ?> /> <?php _e('Show Admin Comments?', 'comment_remix'); ?></label></p>
            <p><label for="wpcr-active-commenters-start-date"><input id="wpcr-active-commenters-start-date" name="wpcr-active-commenters-start-date" type="checkbox" <?=($start_number!='')?'checked="CHECKED"':''; ?> /> 
            <?php _e('Show Only Discussions From The Past ','comment_remix'); ?> </label>
            <input style="width: 50px;" id="wpcr-active-commenters-start-number" name="wpcr-active-commenters-start-number" type="text" value="<?=$start_number;?>" />
                <select style="width: 150px;" id="wpcr-active-commenters-start-type" name="wpcr-active-commenters-start-type">
                    <option value='YEAR' <?= ($start_type=='YEAR')?'SELECTED':''; ?>><?_e('Year(s)','comment_remix');?></option>
                    <option value='MONTH' <?= ($start_type=='MONTH')?'SELECTED':''; ?>><?_e('Month(s)','comment_remix');?></option>
                    <option value='DAY' <?= ($start_type=='DAY')?'SELECTED':''; ?>><?_e('Day(s)','comment_remix');?></option>
                </select>
            </p>
            <input type="hidden" id="wpcr_display_active_commenters_submit" name="wpcr_display_active_commenters_submit" value="1" />
        <?php
        }
        $widget_ops = array('classname' => 'widget_active_commenters', 'description' => __( 'Displays the most active Commenters', 'comment-remix' ) );
        wp_register_sidebar_widget('active-commenters', __('Most Active Commenters (Remix)', 'comment-remix'), 'wpcr_display_active_commenters_widget', $widget_ops);
        wp_register_widget_control('active-commenters', __('Most Active Commenters (Remix)', 'comment-remix'), 'wpcr_display_active_commenters_widget_control');

        function wpcr_display_active_commenters($template, $max, $hide_admin, $start_number='', $start_type='MONTH') {
            global $wpdb;
            $sql = "SELECT comment_author, comment_author_url, comment_author_email, count(comment_ID) as comment_count FROM $wpdb->comments WHERE comment_approved = '1' AND comment_type not in ('trackback','pingback')";
            if ($hide_admin)
                $sql .= " AND user_id != 1";
            if ($start_number)
                $sql .= " AND comment_date + INTERVAL $start_number $start_type >= '" . date("Y-m-d h:i:s") . "'";
            $sql .= " GROUP BY comment_author, comment_author_url, comment_author_email ORDER BY comment_count DESC LIMIT $max";
            $results = $wpdb->get_results($sql);
            
            $echoed=0;
            foreach ($results as $row) {
                $tags = array('%g','%au','%an','%c');
                $replacements = array(get_avatar($row->comment_author_email,'32'),$row->comment_author_url,$row->comment_author,$row->comment_count);
                echo '<li>' . str_replace($tags,$replacements,$template) . '</li>';
                $echoed=1;
            }
            if ($echoed==0)
                echo '<li>' . __('None','comment_remix') . '</li>';
        }
        
        
    }  
} 

//Widget Registration Actions
add_action('plugins_loaded', 'wpcr_widget_register');
?>
