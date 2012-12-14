<?php

//*** Admin Display Functions - Lite Only ***

//Custom button image examples

function pib_custom_btn_img_examples_popup() {
    ?>
    
    <div id="custom_img_btn_examples" style="display:none;">
        <div id="custom_img_btn_examples_container">            
            <img src="//d2ghr62k8k1ljk.cloudfront.net/img/pin-it-button-pro-custom-image-examples.png" alt="<?php _e( 'Pin It Button Pro Custom Image Examples', 'pib' ); ?>" />
            
            <div class="upgrade-text">
                <?php _e( 'These button designs available in "Pin It" Button Pro.', 'pib' ); ?><br/>
                <a href="<?php echo PIB_UPGRADE_URL_BASE . pib_campaign_url( 'custom_button_popup', 'pro_upgrade' ); ?>" target="_blank" class="button-primary close"><?php _e( 'Upgrade Now', 'pib' ); ?></a>
                &nbsp;&nbsp;<a href="#" class="close"><?php _e( 'Close', 'pib' ); ?></a>
            </div>                
        </div>
    </div>
        
    <?php
}

//Upgrade banner

function pib_upgrade_banner_top() {
    ?>

    <div class="pib-admin-upgrade-banner-top">
        <a href="<?php echo PIB_UPGRADE_URL_BASE . pib_campaign_url( 'banner_top', 'pro_upgrade' ); ?>" target="_blank">
            <img src="//d2ghr62k8k1ljk.cloudfront.net/img/pin-it-button-pro-upgrade-banner.png" alt="<?php _e( 'Upgrade to the Pin It Button Pro Plugin', 'pib' ); ?>" />
        </a>
    </div>
    
    <?php
}

//Settings page sidebar (Lite)

function pib_settings_sidebar_lite() {
    ?>

    <div class="postbox">
        <?php pib_handlediv(); ?>
        <h3 class="hndle pib-hndle"><?php _e( 'Spread the Word', 'pib' ); ?></h3>
        
        <div class="inside">
            <p><?php _e( 'Like this plugin? A share would be awesome!', 'pib' ); ?></p>
            
            <table id="share_plugin_buttons">
                <tr>
                    <td><?php echo pib_share_twitter(); ?></td>
                    <td><?php echo pib_share_pinterest(); ?></td>
                </tr>
                <tr>
                    <td><?php echo pib_share_facebook(); ?></td>
                    <td><?php echo pib_share_gplus(); ?></td>
                </tr>
            </table>
            
            <p>
                &raquo; <a href="http://wordpress.org/extend/plugins/pinterest-pin-it-button/" target="_blank" class="external">
                    <?php _e( 'Rate it on WordPress', 'pib' ); ?></a>
            </p>
        </div>
    </div>

    <div class="postbox">
        <?php pib_handlediv(); ?>
        <h3 class="hndle pib-hndle"><?php _e( 'Plugin Support', 'tpp' ); ?></h3>
        
        <div class="inside">
            <p>
                &raquo; <a href="http://pinterestplugin.com/support<?php echo pib_campaign_url( 'sidebar_link', 'support_pib_lite' ); ?>" target="_blank" class="external">
                <?php _e( 'Support & Knowledge Base', 'pib' ); ?></a>
            </p>
            <p>
                <?php _e( 'Priority support provided to licensed users only.', 'pib' ); ?>
            </p>
        </div>
    </div>
    
    <div class="postbox">
        <?php pib_handlediv(); ?>
        <h3 class="hndle pib-hndle"><?php _e( 'More Pinterest Plugins', 'pib' ); ?></h3>
        
        <div class="inside">
            <ul>
                <li>&raquo; <a href="<?php echo PIB_UPGRADE_URL_BASE . pib_campaign_url( 'sidebar_link', 'pro_upgrade' ); ?>" target="_blank" class="external">"Pin It" Button Pro</a></li>
                <li>&raquo; <a href="http://pinterestplugin.com/top-pinned-posts/<?php echo pib_campaign_url( 'sidebar_link', 'top_pinned_posts' ); ?>" target="_blank" class="external">Top Pinned Posts</a></li>
                <li>&raquo; <a href="http://pinterestplugin.com/follow-button<?php echo pib_campaign_url( 'sidebar_link', 'follow_button' ); ?>" target="_blank" class="external">"Follow" Button</a></li>
                <li>&raquo; <a href="http://pinterestplugin.com/pinterest-block<?php echo pib_campaign_url( 'sidebar_link', 'pinterest_block' ); ?>" target="_blank" class="external">Pinterest Block</a></li>
            </ul>
        </div>
    </div>

    <?php pib_newsletter_subscribe(); ?>

    <?php pib_plugin_news(); ?>    

    <?php
}

//Render Facebook Like button
//https://developers.facebook.com/docs/reference/plugins/like/

function pib_share_facebook() {
	?>	
    <iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fpinterestplugin.com%2F&amp;send=false&amp;layout=button_count&amp;width=96&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=21&amp;appId=144056775628952" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:96px; height:21px;" allowTransparency="true"></iframe>
    <?php
}

//Render Twitter button
//https://twitter.com/about/resources/buttons
//https://dev.twitter.com/docs/tweet-button (scroll down for iframe button version)

function pib_share_twitter() {
	?>
    <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://pinterestplugin.com" data-text="I'm using the Pinterest &quot;Pin It&quot; Button Plugin for WordPress. It rocks!">Tweet</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>	
	<?php
}

//Render Pin It button
//Render in iframe otherwise it messes up the WP admin left menu

function pib_share_pinterest() {
	?>
	<a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fpinterestplugin.com%2F&media=http%3A%2F%2Fpinterestplugin.com%2Fimg%2Fpinterest-pin-it-button-plugin-for-wordpress.png&description=Add%20a%20Simple%20and%20Flexible%20%22Pin%20It%22%20Button%20to%20Your%20WordPress%20Site%20--%20http%3A%2F%2Fpinterestplugin.com%2F" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
	<script src="//assets.pinterest.com/js/pinit.js"></script>
	<?php
}

//Render Google + button
//https://developers.google.com/+/plugins/+1button/

function pib_share_gplus() {
	?>
    <div class="g-plusone" data-size="medium" data-href="http://pinterestplugin.com"></div>

    <script type="text/javascript">
      (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/plusone.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
      })();
    </script>
	<?php
}

//Add first-install pointer CSS/JS & functionality

function pib_add_admin_css_js_pointer() {
	wp_enqueue_style( 'wp-pointer' );
    wp_enqueue_script( 'wp-pointer' );
	
    add_action( 'admin_print_footer_scripts', 'pib_admin_print_footer_scripts' );
}

add_action( 'admin_enqueue_scripts', 'pib_add_admin_css_js_pointer' );

//Add pointer popup message when plugin first installed

function pib_admin_print_footer_scripts() {
    //Check option to hide pointer after initial display
    if ( !get_option( 'pib_hide_pointer' ) ) {
        $pointer_content = '<h3>' . __( 'Ready to be Pinned?', 'pib' ) . '</h3>';
        $pointer_content .= '<p>' . __( 'Congratulations. You have just installed the Pinterest "Pin It" Button Plugin. ' .
            'Now just configure your settings and start getting Pinned!', 'pib' ) . '</p>';
         
        $url = admin_url( 'admin.php?page=' . PIB_BASE_NAME );
        
        ?>

        <script type="text/javascript">
            //<![CDATA[
            jQuery(document).ready( function($) {
                $("#menu-plugins").pointer({
                    content: '<?php echo $pointer_content; ?>',
                    buttons: function( event, t ) {
                        button = $('<a id="pointer-close" class="button-secondary"><?php _e( 'Close', 'pib' ); ?></a>');
                        button.bind("click.pointer", function() {
                            t.element.pointer("close");
                        });
                        return button;
                    },
                    position: "left",
                    close: function() { }
            
                }).pointer("open");
              
                $("#pointer-close").after('<a id="pointer-primary" class="button-primary" style="margin-right: 5px;" href="<?php echo $url; ?>">' + 
                    '<?php _e( 'Pin It Button Settings', 'pib' ); ?>');
            });
            //]]>
        </script>

        <?php
        
        //Update option so this pointer is never seen again
        update_option( 'pib_hide_pointer', 1 );
	}
}
