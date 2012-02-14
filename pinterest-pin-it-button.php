<?php
/*
  Plugin Name: Pinterest "Pin It" Button
  Plugin URI: http://pinterestplugin.com/
  Description: Add a Pinterest "Pin It" button to your posts and pages.
  Version: 1.1.0
  Author: Phil Derksen
  Author URI: http://pinterestplugin.com/
*/

/*  Copyright 2012 Phil Derksen (pderksen@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//Start session if it doesn't exist

if( ! session_id() ) {
	session_start();
}

//Set global variables

if ( ! defined( 'PIB_PLUGIN_BASENAME' ) )
	define( 'PIB_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
    
define( 'BASE_BTN_HTML', '<a href="javascript:void(0)" class="pin-it-btn" title="Pin It on Pinterest"></a>' );
	
//Plugin install/activation

function pib_install() {
	//Deactivate plugin if WP version too low
    if ( version_compare( get_bloginfo( 'version' ), '3.0', '<' ) ) {
        deactivate_plugins( basename( __FILE__ ) );
    }
	
	//Setup default settings
	$pib_options = array(
		'display_home_page' => 0,
		'display_front_page' => 0,
		'display_posts' => 1,
		'display_pages' => 1,
		'display_archives' => 0,
		'display_above_content' => 0,
		'display_below_content' => 1,
		'display_on_post_excerpts' => 0,
		'remove_div' => 0,
		'custom_css' => ''
	);

	//Save default option values
	update_option( 'pib_options', $pib_options );
	
	//Set session variable to display admin notice
	$_SESSION['msg'] = 1;	
}

register_activation_hook( __FILE__, 'pib_install' );

//Display admin notice to proceed to options

function pib_plugin_activate_notice() {
    //Add message to only Plugin Page
	global $current_screen;
    
    if ( $current_screen -> parent_base == 'plugins' ) {
        if( $_SESSION['msg'] == 1 ) {
            echo '<div class="updated"><p>' . 
                sprintf( __( '<a href="%1$s" class="activate-notice-link">Update Your "Pin It" Button Settings</a>' ),
                'admin.php?page=' . PIB_PLUGIN_BASENAME ) . 
                '</p></div>';
        }
    }	
}

add_action('admin_notices', 'pib_plugin_activate_notice');

//Destroy session variable when navigate to other page

function pib_session_destroy() {
	if ( $current_screen->parent_base != 'plugins' ) {
	    session_destroy();
	}
}

add_action('admin_menu', 'pib_session_destroy');

/********************
  Public-Only Functions
********************/

//Add Public CSS/JS

function pib_add_public_css_js() {
    wp_enqueue_script( 'jquery' );
    
	wp_enqueue_style( 'pinterest-pin-it-button', plugins_url( '/css/pinterest-pin-it-button.css' , __FILE__ ) );
    wp_enqueue_script( 'pinterest-pin-it-button', plugins_url( '/js/pinterest-pin-it-button.js', __FILE__ ), array( 'jquery' ) );
}

add_action( 'wp_enqueue_scripts', 'pib_add_public_css_js' );

//Add Custom CSS

function pib_add_custom_css() {
	$pib_options = get_option( 'pib_options' );
    $custom_css = trim( $pib_options['custom_css'] );
    
	if ( $custom_css != '' ) {
        echo "\n" . '<style type="text/css">' . "\n" . $custom_css . "\n" . '</style>' . "\n";
	}
}

add_action( 'wp_head', 'pib_add_custom_css' );

//Button html to render

function pib_button_html() {
    $pib_options = get_option( 'pib_options' );
    
	//Check that remove surrounding div checkbox is selected
	if ( $pib_options['remove_div'] ) {
        return BASE_BTN_HTML;
	} else {
        //Surround with div tag
        return '<div class="pin-it-btn-wrapper">' . BASE_BTN_HTML . '</div>';
	}
}

//Register shortcode: [pinit]

function pib_button_shortcode_html() {
    return '<div class="pin-it-btn-shortcode-wrapper">' . BASE_BTN_HTML . '</div>';
}

add_shortcode( 'pinit', 'pib_button_shortcode_html' );

//Render button on pages with regular content

function pib_render_btn( $content ) {
	//Load options array
	$pib_options = get_option( 'pib_options' );
    
    //Determine if displayed on current page
    if (
        ( is_home() && ( $pib_options['display_home_page'] ) ) ||
        ( is_front_page() && ( $pib_options['display_front_page'] ) ) ||
        ( is_single() && ( $pib_options['display_posts'] ) ) ||
        ( is_page() && ( $pib_options['display_pages'] ) ) ||
        ( is_archive() && ( $pib_options['display_archives'] ) )
       ) {
        if ( $pib_options['display_above_content'] ) {
            $content = pib_button_html() . $content;
        }

        if ( $pib_options['display_below_content'] ) {
            $content .= pib_button_html();
        }
    }
    
	return $content;
}

add_filter( 'the_content', 'pib_render_btn' );

//Render button on pages with excerpts if option checked

function pib_render_btn_excerpt( $content ) {
	//Load options array
	$pib_options = get_option( 'pib_options' );
    
    if ( $pib_options['display_on_post_excerpts'] ) {
        if (
            ( is_home() && ( $pib_options['display_home_page'] ) ) ||
            ( is_front_page() && ( $pib_options['display_front_page'] ) ) ||
            ( is_archive() && ( $pib_options['display_archives'] ) )
           ) {
            if ( $pib_options['display_above_content'] ) {
                $content = pib_button_html() . $content;
            }

            if ( $pib_options['display_below_content'] ) {
                $content .= pib_button_html();
            }
        }
    }

	return $content;
}

add_filter( 'the_excerpt', 'pib_render_btn_excerpt' );


/********************
  Admin-Only Functions
********************/

//Add settings page to admin menu

function pib_create_menu() {
    add_menu_page( 'Pin It Button Settings', 'Pin It Button', 'manage_options', __FILE__, 'pib_create_settings_page', 
        plugins_url( '/img/pinit-button-icon-small.png', __FILE__ ) );
}

add_action( 'admin_menu', 'pib_create_menu' );

//Add Admin CSS/JS

function pib_add_admin_css_js() {
	wp_enqueue_script( 'jquery' );
	
	wp_enqueue_style( 'pinterest-pin-it-button', plugins_url( '/css/pinterest-pin-it-button-admin.css' , __FILE__ ) );
    wp_enqueue_script( 'pinterest-pin-it-button', plugins_url( '/js/pinterest-pin-it-button-admin.js', __FILE__ ), array( 'jquery' ) );
}

add_action( 'admin_enqueue_scripts', 'pib_add_admin_css_js' );


//Register settings

function pib_register_settings() {
	register_setting( 'pib-settings-group', 'pib_options' );
}

add_action( 'admin_init', 'pib_register_settings' );

//Create settings page

function pib_create_settings_page() {
	//Load options array
	$pib_options = get_option( 'pib_options' );
    $custom_css = trim( $pib_options['custom_css'] );

	?>
        <div class="wrap">
			
            <a href="http://pinterestplugin.com/" target="_blank"><div id="pinit-button-icon-32" class="icon32"
                style="background: url(<?php echo plugins_url( '/img/pinit-button-icon-med.png', __FILE__ ); ?>) no-repeat;"><br /></div></a>
            <h2>Pinterest "Pin It" Button Settings</h2>
            
            <div class="metabox-holder">
                <div class="pib-settings postbox-container">
					<div class="meta-box-sortables ui-sortable">
						<?php settings_errors(); //Display status messages after action ("settings saved", errors) ?>
					
						<form method="post" action="options.php">
							<?php settings_fields( 'pib-settings-group' ); ?>
							
							<div id="pib-options" class="postbox pib-postbox">
								 <!--Collapsable-->
								<div class="handlediv" title="Click to toggle"><br /></div>						
								<h3 class="hndle">What types of pages should the button appear on?</h3>
								
								<table class="form-table inside">
									<tr valign="top">
										<td>
											<input id="display_home_page" name="pib_options[display_home_page]" type="checkbox" 
												<?php if ( $pib_options['display_home_page'] ) echo 'checked="checked"'; ?> />
											<label for="display_home_page">Blog Home Page (or Latest Posts Page)</label>
										</td>
									</tr>
									<tr valign="top">
										<td>
											<input id="display_front_page" name="pib_options[display_front_page]" type="checkbox" 
												<?php if ( $pib_options['display_front_page'] ) echo 'checked="checked"'; ?> />
											<label for="display_front_page">Front Page (different from Home Page only if set in Settings > Reading)</label>
										</td>
									</tr>					
									<tr valign="top">
										<td>
											<input id="display_posts" name="pib_options[display_posts]" type="checkbox" 
												<?php if ( $pib_options['display_posts'] ) echo 'checked="checked"'; ?> />
											<label for="display_posts">Individual Posts</label>
										</td>
									</tr>
									<tr valign="top">
										<td>
											<input id="display_pages" name="pib_options[display_pages]" type="checkbox" 
												<?php if ( $pib_options['display_pages'] ) echo 'checked="checked"'; ?> />
											<label for="display_pages">WordPress Static "Pages"</label>
										</td>
									</tr>
									<tr valign="top">
										<td>
											<input id="display_archives" name="pib_options[display_archives]" type="checkbox" 
												<?php if ( $pib_options['display_archives'] ) echo 'checked="checked"'; ?> />
											<label for="display_archives">Archives Page (Category, Tag, Author, time-based, etc.)</label>
										</td>
									</tr>
								</table>
							</div>
								
							<div id="button-show" class="postbox pib-postbox">
								<div class="handlediv" title="Click to toggle"><br /></div>								
								<h3 class="hndle">Where on each page should the button appear?</h3>
								
								<table class="form-table inside">
									<tr valign="top">
										<td>
											<input id="display_above_content" name="pib_options[display_above_content]" type="checkbox" <?php if ( $pib_options['display_above_content'] ) echo 'checked="checked"'; ?> />
											<label for="display_above_content">Above Content</label>
										</td>
									</tr>
									<tr valign="top">
										<td>
											<input id="display_below_content" name="pib_options[display_below_content]" type="checkbox" <?php if ( $pib_options['display_below_content'] ) echo 'checked="checked"'; ?> />
											<label for="display_below_content">Below Content</label>
										</td>
									</tr>
									<tr valign="top">
										<td>
											<input id="display_on_post_excerpts" name="pib_options[display_on_post_excerpts]" type="checkbox" <?php if ( $pib_options['display_on_post_excerpts'] ) echo 'checked="checked"'; ?> />
											<label for="display_on_post_excerpts">On Post Excerpts</label>
										</td>
									</tr>
									<tr valign="top">
										<td>
											Use the shortcode <code>[pinit]</code> to display the button within content.<br/>
											Use the function <code><?php echo htmlentities('<?php echo do_shortcode(\'[pinit]\'); ?>'); ?></code>
												to display within template or theme files.
										</td>
									</tr>                    
								</table>
							</div>
								
							<div id="style-options" class="postbox pib-postbox">                         
								<div class="handlediv" title="Click to toggle"><br /></div>								
								<h3 class="hndle">Additional layout and styling options</h3>
								
								<table class="form-table inside">
									<tr valign="top">
										<td>
											<label for="custom_css">Custom CSS</label>
										</td>
									</tr>
									<tr valign="top">
										<td>
											<textarea name="pib_options[custom_css]" rows="6" cols="8"><?php echo $custom_css; ?></textarea>
									   </td>
									</tr>
									<tr valign="top">
										<td>
											<input id="remove_div" name="pib_options[remove_div]" type="checkbox" <?php if ( $pib_options['remove_div'] ) echo 'checked="checked"'; ?> />
											<label for="remove_div">Remove surrounding <code><?php echo htmlentities('<div class="pin-it-btn-wrapper"></div>'); ?></code> tag</label>
										</td>
									</tr>
									<tr valign="top">
										<td>
											<a href="http://pinterestplugin.com/pin-it-button-custom-css/" target="_blank">See custom CSS examples</a> aligning the button
											with other social sharing plugins.
										</td>
									</tr>
								</table>
							</div>
							
							<table class="form-table inside">
								 <tr valign="top">
									<td>
										<input name="Submit" type="submit" value="Save Changes" class="button-primary" />
									</td>
								</tr>
							</table>                        
						</form>
					</div>
                </div>
                
                <div class="pib-right-column postbox-container">
					<div class="meta-box-sortables ui-sortable">
						<div id="email-signup">
							<h4>Like This Plugin?</h4>
							
							<p class="large-text">
								Join the mailing list to be notified when new features are released.
							</p>
							
							<!-- Begin MailChimp Signup Form -->
							<div id="mc_embed_signup">
								<form action="http://pinterestplugin.us1.list-manage.com/subscribe/post?u=bfa8cc8ba2614b0796d33a238&amp;id=80e1043ae4" method="post"
									id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">
									
									<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required>
									
									<div class="clear">
										<input type="submit" value="Sign Up!" name="subscribe" id="mc-embedded-subscribe" class="awesome large red">
									</div>
								</form>
							</div>
							<!--End mc_embed_signup-->
							
							<p>
								No spam. Unsubscribe anytime.
							</p>
						</div>

						<div id="other-links" class="postbox pib-postbox">
							<div class="handlediv" title="Click to toggle"><br /></div>
							<h3 class="hndle">Other Links</h3>
							
							<div class="inside">
                                <ul>
                                    <li><a href="http://pinterestplugin.com/">Official Plugin Site</a></li>
                                    <li><a href="https://pinterestplugin.uservoice.com/">User Support &amp; Feature Requests</a></li>                                    
                                </ul>
							</div>
						</div>
                        
						<div id="news-links" class="postbox pib-postbox">
							<div class="handlediv" title="Click to toggle"><br /></div>
							<h3 class="hndle">News from PinterestPlugin.com</h3>
							
							<div class="inside">
                                <?php // Get RSS Feed(s) -- code from http://codex.wordpress.org/Function_Reference/fetch_feed
                                include_once(ABSPATH . WPINC . '/feed.php');

                                // Get a SimplePie feed object from the specified feed source.
                                $rss = fetch_feed('http://pinterestplugin.com/feed/');
                                
                                if (!is_wp_error( $rss ) ) : // Checks that the object is created correctly 
                                    // Figure out how many total items there are, but limit it to 5. 
                                    $maxitems = $rss->get_item_quantity(5); 

                                    // Build an array of all the items, starting with element 0 (first element).
                                    $rss_items = $rss->get_items(0, $maxitems); 
                                endif;
                                ?>

                                <ul>
                                    <?php if ($maxitems == 0) echo '<li>No items.</li>';
                                    else
                                    // Loop through each feed item and display each item as a hyperlink.
                                    foreach ( $rss_items as $item ) : ?>
                                    <li>
                                        <a href='<?php echo esc_url( $item->get_permalink() ); ?>'
                                        title='<?php echo 'Posted ' . $item->get_date('j F Y | g:i a'); ?>'>
                                        <?php echo esc_html( $item->get_title() ); ?></a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
							</div>
						</div>
                    </div>
                </div>
                
			</div>
        </div>
    <?php
}

//Add a link to the settings page to the plugins list

function pib_plugin_action_links( $links, $file ) {
	if ( $file != PIB_PLUGIN_BASENAME )
		return $links;

	$url = admin_url( 'admin.php?page=' . PIB_PLUGIN_BASENAME );
	$settings_link = '<a href="' . esc_attr( $url ) . '">' . esc_html( __( 'Settings') ) . '</a>';

	array_unshift( $links, $settings_link );

	return $links;
}

add_filter( 'plugin_action_links', 'pib_plugin_action_links', 10, 2 );

?>