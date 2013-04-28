<?php

    //Quick Reply Page for WP Comment Remix
    //http://www.Pressography.com/WP-Comment-Remix

    //This page gets imported into wpcommentremix.php and displayed as a popup reply box when the user clicks Reply or Quote
    
    $options = get_option('wpcr_options');
    
    if ($options['showtags'] == '1') {
        $height = '485px';
    } else {
        $height = '410px';
    }
?>
<style type="text/css">
    #full_page_shadow {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 98;
        background-color: #121212;
        opacity: .6;
        filter: alpha(opacity=60);
        position: fixed;
    }
    #quickreplypopup {
        padding: 0 2px 2px 0;
        margin: 0;
        width: 705px;
        height: <?=$height?>;
        position: absolute;
        background-color: #efefef;
        z-index: 99;
        position: fixed;
    }
    .quickreply {
        margin: 0;
        width: 705px;
        height: <?=$height?>;
        position: relative;
        background-color: #fff;
    }
    .quickreply textarea {
        position: relative;
        height: 150px;
        width: 650px;
        z-index: 100;
    }
    .quickreply #the_comment {
        height: 100px;
        overflow-y: scroll;
        padding: 0;
        margin: 0;
    }
    #wpcr_message {
        float: left;
        width: 490px;
        margin: 0 auto;
        text-align: center;
        font-weight: bold;
        color: #FF0000;
    }
</style>
<div id="full_page_shadow">
</div>
<div id="quickreplypopup">
<div class="quickreply">
<div class="wrap">
    <table class="form-table">
        <tbody>
            <tr>
                <td>
                    <div id="the_comment"></div>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">
                    <h3 style="padding: 0; margin: 0;"><?=_e('Reply To This Comment','comment_remix') . ' &raquo;';?></h3>
                    <textarea id='quickreplytext'></textarea>
                </td>
            </tr>
            <?php if ($options['showtags'] == '1') { ?>
            <tr>
                <td>
                    <?php wpcr_add_comment_tag_form() ?>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <td>
                    <button style="float: left;" id="quickreplycancel"><?=_e('Cancel','comment_remix');?></button>
                    <div id='wpcr_message'></div>
                    <button style="float: right;" id="quickreplysubmit"><?=_e('Save Reply','comment_remix');?> &raquo;</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</div>
</div>
<div id="new-comment"></div>