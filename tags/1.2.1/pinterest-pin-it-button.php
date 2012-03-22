<?php
/*
  Plugin Name: Pinterest "Pin It" Button
  Plugin URI: http://pinterestplugin.com/
  Description: Add a Pinterest "Pin It" button to your posts and pages.
  Version: 1.2.1
  Author: Phil Derksen
  Author URI: http://pinterestplugin.com/
*/

/*  Copyright 2012 Phil Derksen (phil@pinterestplugin.com)

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
		//'admin_activate_notice' => 0,
		'display_home_page' => 1,
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
	
}

register_activation_hook( __FILE__, 'pib_install' );

//Display admin notice only once, then set option to turn off
//Using saved option instead of session which was causing errors for some
//TODO Removed until further testing

/* 
function pib_plugin_activate_notice() {
	$pib_options = get_option( 'pib_options' );
		
	if ( !$pib_options['admin_activate_notice'] ) {
		echo '<div class="updated"><p>' . 
			'<strong>Your "Pin It" Button is activated!</strong> ' .
			sprintf( __( '<a href="%1$s" class="activate-notice-link">Customize Your Settings</a>' ),
			'admin.php?page=' . PIB_PLUGIN_BASENAME ) . 
			'</p></div>';
			
		$pib_options['admin_activate_notice'] = 1;
		update_option( 'pib_options', $pib_options );		
	}
}

add_action('admin_notices', 'pib_plugin_activate_notice');
*/

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
											<label for="display_archives">Archives (includes Category, Tag, Author and time-based pages)</label>
										</td>
									</tr>
									<tr valign="top">
										<td>
											When editing a post, page or category, scroll down to find the option to hide the "Pin It" button for it.
										</td>
									</tr>
									<tr valign="top">
										<td>
                                            Go to <a href="<?php echo admin_url( 'widgets.php' ); ?>">Widgets</a> to add a "Pin It" button to the sidebar.
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
						
						<div id="promo-buttons" class="postbox pib-postbox">
							<div class="handlediv" title="Click to toggle"><br /></div>
							<h3 class="hndle">Spread the Word!</h3>
							
							<div class="inside">
								<p>The more people using this plugin the more we can add to it for you. Just take a sec and share it!</p>
								
								<table>
									<tr>
										<td><?php echo pib_share_facebook(); ?></td>
									</tr>
									<tr>
										<td><?php echo pib_share_twitter(); ?></td>
									</tr>
									<tr>
										<td><?php echo pib_share_google_plus(); ?></td>
									</tr>
									<tr>
										<td><?php echo pib_share_pinterest(); ?></td>
									</tr>
								</table>
							</div>
						</div>

						<div id="other-links" class="postbox pib-postbox">
							<div class="handlediv" title="Click to toggle"><br /></div>
							<h3 class="hndle">Other Links</h3>
							
							<div class="inside">
                                <ul>
                                    <li><a href="http://pinterestplugin.com/" target="_blank">Official Site - PinterestPlugin.com</a></li>
                                    <li><a href="https://pinterestplugin.uservoice.com/" target="_blank">User Support &amp; Feature Requests</a></li>                                    
                                </ul>
							</div>
						</div>
                        
						<div id="news-links" class="postbox pib-postbox">
							<div class="handlediv" title="Click to toggle"><br /></div>
							<h3 class="hndle">News from PinterestPlugin.com</h3>
							
							<div class="inside">
								<? echo pib_rss_news(); ?>
							</div>
						</div>
                    </div>
                </div>
                
			</div>
        </div>
    <?php
}

//Render rss items from pinterestplugin.com
//http://codex.wordpress.org/Function_Reference/fetch_feed
function pib_rss_news() {
	// Get RSS Feed(s)
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
			title='<?php echo 'Posted ' . $item->get_date('j F Y | g:i a'); ?>'
			target="_blank">
			<?php echo esc_html( $item->get_title() ); ?></a>
		</li>
		<?php endforeach; ?>
	</ul>
	
	<?php
}

//Render Facebook Share button
//http://developers.facebook.com/docs/share/
function pib_share_facebook() {
	?>
	
	<a name="fb_share" type="button" share_url="http://pinterestplugin.com/" alt="Share on Facebook"></a> 
	<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>
	
	<?php
}

//Render Twitter button
//https://twitter.com/about/resources/buttons
function pib_share_twitter() {
	?>
	
	<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://pinterestplugin.com/" data-text="Check out the Pinterest &quot;Pin It&quot; Button Plugin for WordPress" alt="Tweet this!">Tweet</a>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>	
	
	<?php
}

//Render Google +1 button
//http://www.google.com/intl/en/webmasters/+1/button/index.html
function pib_share_google_plus() {
	?>
	
	<!-- Place this tag where you want the +1 button to render -->
	<div class="g-plusone" data-size="small" data-annotation="inline" data-href="http://pinterestplugin.com/"></div>

	<!-- Place this render call where appropriate -->
	<script type="text/javascript">
	  (function() {
		var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		po.src = 'https://apis.google.com/js/plusone.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
	  })();
	</script>
	
	<?php
}

//Render Pin It button
function pib_share_pinterest() {
	?>
    <iframe src='<?php echo plugins_url( '/inc/admin-pin-it-button-iframe.html' , __FILE__ ) ?>' scrolling="no" frameborder="0" allowtransparency="true" 
        style="border:none; overflow:hidden; width:px; height:20px"></iframe>
	<?php
}

//Render Facebook Like button
//http://developers.facebook.com/docs/reference/plugins/like/
//TODO Not using since correct image isn't coming up for now
function pib_like_facebook() {
	?>
	
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=144056775628952";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	
	<div class="fb-like" data-href="http://pinterestplugin.com/" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div>	
	
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

//Adds a meta box to the main column on the Post and Page edit screens 

function pib_sharing_add_meta_box() {
	add_meta_box( 'pib_sharing_meta','"Pin It" Button Display', 'pib_sharing_meta_box_content', 'page', 'advanced', 'high' );
	add_meta_box( 'pib_sharing_meta','"Pin It" Button Display', 'pib_sharing_meta_box_content', 'post', 'advanced', 'high' );
}

add_action( 'admin_init', 'pib_sharing_add_meta_box' );

//Renders the post/page meta box checkbox html

function pib_sharing_meta_box_content( $post ) {
	$pib_sharing_checked = get_post_meta( $post->ID, 'pib_sharing_disabled', 1 );

	if ( empty( $pib_sharing_checked ) || $pib_sharing_checked === false )
		$pib_sharing_checked = ' checked="checked"';
	else
		$pib_sharing_checked = '';
	?>
	
	<p>
		<input name="pib_enable_post_sharing" id="pib_enable_post_sharing" value="1" <?php echo $pib_sharing_checked; ?> type="checkbox" />
		<label for="pib_enable_post_sharing">Show "Pin It" button on this post/page.</label>
		<p class="description">
			<!-- <span style="font-size: 11px;"> -->
			If checked displays the button for this post/page (if <strong>Individual Posts</strong> (for posts) or <strong>WordPress Static "Pages"</strong> 
			(for pages) is also checked in <a href='<?php echo 'admin.php?page=' . PIB_PLUGIN_BASENAME ?>'>"Pin It" Button Settings</a>).
            If unchecked the button will <strong>always</strong> be hidden for this post/page.
		</p>
		<input type="hidden" name="pib_sharing_status_hidden" value="1" />
	</p>
		
	<?php
}

//Saves display option for individual post/page

function pib_sharing_meta_box_save( $post_id ) {
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
		return $post_id;

	// Record sharing disable
	if ( isset( $_POST['post_type'] ) && ( 'post' == $_POST['post_type'] || 'page' == $_POST['post_type'] ) ) {
		if ( current_user_can( 'edit_post', $post_id ) ) {
			if ( isset( $_POST['pib_sharing_status_hidden'] ) ) {
				if ( !isset( $_POST['pib_enable_post_sharing'] ) )
					update_post_meta( $post_id, 'pib_sharing_disabled', 1 );
				else
					delete_post_meta( $post_id, 'pib_sharing_disabled' );
			}
		}
	}

	return $post_id;
}

add_action( 'save_post', 'pib_sharing_meta_box_save' );

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

function pib_button_html($postID) {
	 
	$pib_options = get_option( 'pib_options' );
	
	
	if (get_post_meta($postID,'pib_sharing_disabled', 1)) {
			
			return "";
	}
	else {	
	
		//Check that remove surrounding div checkbox is selected
			if ( $pib_options['remove_div'] ) {
				return BASE_BTN_HTML;
			} else {
				//Surround with div tag
				return '<div class="pin-it-btn-wrapper">' . BASE_BTN_HTML . '</div>';
			}
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
 	global $post;
	$postID = $post->ID;
			
    //Determine if displayed on current page
    if (
        ( is_home() && ( $pib_options['display_home_page'] ) ) ||
        ( is_front_page() && ( $pib_options['display_front_page'] ) ) ||
		( is_single() && ( $pib_options['display_posts'] ) ) ||
        ( is_page() && ( $pib_options['display_pages'] ) )
       ) {
        if ( $pib_options['display_above_content'] ) {
            $content = pib_button_html($postID) . $content;
        }

        if ( $pib_options['display_below_content'] ) {
            $content .= pib_button_html($postID);
        }
    }
	
	 	
	//Determine if displayed on Category on the base of category edit Screen Option
	if( is_archive() && ( $pib_options['display_archives'] ) ) {
		
		$tag_extra_fields = get_option(PIB_CATEGORY_FIELDS);
		$category_ids = get_all_category_ids();
		foreach($category_ids as $term_id) {
					 
			if($tag_extra_fields[$term_id]['checkbox'] != true) {
						
				if(is_category($term_id)) {	
					if ( $pib_options['display_above_content'] ) {
						$content = pib_button_html($postID) . $content;
					}
							
					if ( $pib_options['display_below_content'] ) {
						$content .= pib_button_html($postID);
					}
				}
			}				
		}
	}
		
	return $content;
}

add_filter( 'the_content', 'pib_render_btn' );


//Render button on pages with excerpts if option checked

function pib_render_btn_excerpt( $content ) {
	//Load options array
	$pib_options = get_option( 'pib_options' );
    global $post;
	$postID = $post->ID;
	
    if ( $pib_options['display_on_post_excerpts'] ) {
        if (
            ( is_home() && ( $pib_options['display_home_page'] ) ) ||
            ( is_front_page() && ( $pib_options['display_front_page'] ) ) 
           
           ) {
            if ( $pib_options['display_above_content'] ) {
                $content = pib_button_html($postID) . $content;
            }

            if ( $pib_options['display_below_content'] ) {
                $content .= pib_button_html($postID);
            }
        }
   
	
		//Determine if displayed on Category on the base of category edit Screen Option
		if( is_archive() && ( $pib_options['display_archives'] ) ) {
				
            $tag_extra_fields = get_option(PIB_CATEGORY_FIELDS);
            $category_ids = get_all_category_ids();
            foreach($category_ids as $term_id) {
                     
                if($tag_extra_fields[$term_id]['checkbox'] != true) {
                            
                    
                    if(is_category($term_id)) {	
                        if ( $pib_options['display_above_content'] ) {
                            $content = pib_button_html($postID) . $content;
                        }
                                
                        if ( $pib_options['display_below_content'] ) {
                            $content .= pib_button_html($postID);
                        }
                    }
                }
				
                
            }
		}
	}
	return $content;
}

add_filter( 'the_excerpt', 'pib_render_btn_excerpt' );

//Option name
define('PIB_CATEGORY_FIELDS', 'pib_category_fields_option');

//Add Checkbox to Category Edit Screen 
add_action('edit_category_form_fields', 'pib_category_fields');

function pib_category_fields($tag) {
	$t_id = $tag->term_id;
    $tag_extra_fields = get_option(PIB_CATEGORY_FIELDS);
	
	 	if ( $tag_extra_fields[$t_id]['checkbox'] == true)
			$pib_category_checked = '';
		else
			$pib_category_checked = 'checked="checked"';

    ?>
		
    <table class="form-table">
        <tr class="form-field">
            <th scope="row" valign="top">
            <h3>"Pin It" Button Settings</h3>
            </th>
        </tr>
        <tbody>
            <tr class="form-field">
                <th scope="row" valign="top">
                    <label for="pib_category_field">Show "Pin It" Button</label>
                </th>
                <td>
                    <input name="pib_category_field" id="pib_category_field" type="checkbox" value="true" <?php echo $pib_category_checked; ?>>
                    <p class="description">
                        If checked displays the button for this category (if <strong>Archives</strong> also checked in
                        <a href='<?php echo 'admin.php?page=' . PIB_PLUGIN_BASENAME ?>'>"Pin It" Button Settings</a>).
                        If unchecked the button will <strong>always</strong> be hidden for this category.
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
    
    <?php
}

// when the form gets submitted, and the category gets updated (in your case the option will get updated with the values of your custom fields above
add_action('edited_category', 'update_pib_category_fields');

function update_pib_category_fields($term_id) {
  if($_POST['taxonomy'] == 'category'):
    $tag_extra_fields = get_option(PIB_CATEGORY_FIELDS);
    $tag_extra_fields[$term_id]['checkbox'] = strip_tags($_POST['pib_category_field']);
   if( $_POST['pib_category_field'] != true){
   		$tag_extra_fields[$term_id]['checkbox'] = true;
    	update_option(PIB_CATEGORY_FIELDS, $tag_extra_fields );
	}
	if( $_POST['pib_category_field'] == true){
   		$tag_extra_fields[$term_id]['checkbox'] = "";
    	update_option(PIB_CATEGORY_FIELDS, $tag_extra_fields );
	}
  endif;
}

//Add Pinterest Pin It Button widget to sidebar

class Pib_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'pib_widget_button', 'description' => __( 'Add a "Pin It" button to your sidebar with this widget') );
		parent::__construct('pib_button', __('Pinterest - "Pin It" Button'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		echo '<div class="pin-it-btn-wrapper">' . BASE_BTN_HTML . '</div>';
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '') );
		$instance['title'] = strip_tags($new_instance['title']);
	
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = strip_tags($instance['title']);
		
        ?>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		<p>

        <?php
	}
}

//Add function to the widgets_init hook. 
add_action( 'widgets_init', 'pib_load_widgets' );

// Function that register Pin It Button widget. 
function pib_load_widgets() {
	register_widget( 'Pib_Widget' );
}

//Add Pinterest Follow Button Widget to sidebar

class Pib_Follow_Button_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'pib_widget_follow_button', 'description' => __( 'Add a Pinterest "Follow" button to your sidebar with this widget') );
		parent::__construct('pib_follow_button', __('Pinterest - "Follow" Button'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$pib_img_option = $instance['pib_follow_button_radio'];
		$pibusername = $instance['pibusername'];
		$newwindow = $instance['newwindow'] ? '1' : '0';
		
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;            
	
		if($pib_img_option == 1){
			if($newwindow){
				echo '<a href="http://pinterest.com/'.$pibusername.'/" target="_blank"><img src="http://passets-cdn.pinterest.com/images/follow-on-pinterest-button.png" width="156" height="26" alt="Follow Me on Pinterest" /></a>';
			}
			else{
			echo '<a href="http://pinterest.com/'.$pibusername.'/"><img src="http://passets-cdn.pinterest.com/images/follow-on-pinterest-button.png" width="156" height="26" alt="Follow Me on Pinterest" /></a>';
			}
		}
		elseif($pib_img_option == 2){
			if($newwindow){
				echo'<a href="http://pinterest.com/'.$pibusername.'/" target="_blank"><img src="http://passets-cdn.pinterest.com/images/pinterest-button.png" width="78" height="26" alt="Follow Me on Pinterest" /></a>';
			}
			else {
			echo'<a href="http://pinterest.com/'.$pibusername.'/"><img src="http://passets-cdn.pinterest.com/images/pinterest-button.png" width="78" height="26" alt="Follow Me on Pinterest" /></a>';
			}
		}
		elseif($pib_img_option == 3){
			if($newwindow){
				echo'<a href="http://pinterest.com/'.$pibusername.'/" target="_blank"><img src="http://passets-cdn.pinterest.com/images/big-p-button.png" width="61" height="61" alt="Follow Me on Pinterest" /></a>';
			}
			else {
			echo'<a href="http://pinterest.com/'.$pibusername.'/"><img src="http://passets-cdn.pinterest.com/images/big-p-button.png" width="61" height="61" alt="Follow Me on Pinterest" /></a>';
			}
		}
		elseif($pib_img_option == 4){
			if($newwindow){
				echo'<a href="http://pinterest.com/'.$pibusername.'/" target="_blank"><img src="http://passets-cdn.pinterest.com/images/small-p-button.png" width="16" height="16" alt="Follow Me on Pinterest" /></a>';
			}
			else {
			echo'<a href="http://pinterest.com/'.$pibusername.'/"><img src="http://passets-cdn.pinterest.com/images/small-p-button.png" width="16" height="16" alt="Follow Me on Pinterest" /></a>';
			}
		}
		
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '',  'pibusername' => '', 'pib_follow_button_radio' => '1') );
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['pibusername'] = strip_tags($new_instance['pibusername']);
		$instance['pib_follow_button_radio'] = strip_tags($new_instance['pib_follow_button_radio']);
		$instance['newwindow'] = !empty($new_instance['newwindow']) ? 1 : 0;

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'pibusername' => '', 'pib_follow_button_radio' => '1') );
		$title = strip_tags($instance['title']); 
		$pibusername = strip_tags($instance['pibusername']);
		$pib_follow_button_radio = $instance['pib_follow_button_radio'];
		$newwindow = isset( $instance['newwindow'] ) ? (bool) $instance['newwindow'] : false;
		
        ?>
        
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		<p>
		
		<p><label for="<?php echo $this->get_field_id('pibusername'); ?>"><?php _e('Pinterest Username:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('pibusername'); ?>" name="<?php echo $this->get_field_name('pibusername'); ?>" type="text" value="<?php echo esc_attr($pibusername); ?>" /></p>
		<p>
		
        <p><label>Button image:</label></p>
        
		<table>
			<tr>
				<td><input type="radio" <?php if($pib_follow_button_radio == 1){ echo'checked="checked"';} ?> name="<?php echo $this->get_field_name('pib_follow_button_radio'); ?>" id="<?php echo $this->get_field_id('follow-on-pinterest-button'); ?>" value="1" /></td>
				<td><img src="http://passets-cdn.pinterest.com/images/follow-on-pinterest-button.png" width="156" height="26" alt="Follow Me on Pinterest" /></td>
			</tr>
			<tr>
				<td><input type="radio"  <?php if($pib_follow_button_radio == 2){ echo'checked="checked"';} ?> name="<?php echo $this->get_field_name('pib_follow_button_radio'); ?>" id="<?php echo $this->get_field_id('pinterest-button'); ?>" value="2"/></td>
				<td><img src="http://passets-cdn.pinterest.com/images/pinterest-button.png" width="78" height="26" alt="Follow Me on Pinterest" /></td>
			</tr>
			<tr>
				<td><input type="radio"  <?php if($pib_follow_button_radio == 3){ echo'checked="checked"';} ?> name="<?php echo $this->get_field_name('pib_follow_button_radio'); ?>" id="<?php echo $this->get_field_id('big-p-button'); ?>" value="3"/></td>
				<td><img src="http://passets-cdn.pinterest.com/images/big-p-button.png" width="61" height="61" alt="Follow Me on Pinterest" /></td>
			</tr>
			<tr>
				<td><input type="radio"  <?php if($pib_follow_button_radio == 4){ echo'checked="checked"';} ?> name="<?php echo $this->get_field_name('pib_follow_button_radio'); ?>" id="<?php echo $this->get_field_id('small-p-button'); ?>" value="4"/></td>
				<td><img src="http://passets-cdn.pinterest.com/images/small-p-button.png" width="16" height="16" alt="Follow Me on Pinterest" /></td>
			</tr>
		</table>
		<br />
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('newwindow'); ?>" name="<?php echo $this->get_field_name('newwindow'); ?>" <?php checked( $newwindow ); ?> />
		<label for="<?php echo $this->get_field_id('newwindow'); ?>"><?php _e( 'Open in a new window' ); ?></label><br />
			
        <?php
	}
}

//Add function to the widgets_init hook. 
add_action( 'widgets_init', 'pib_load_follow_button_widget' );

// Function that registers Follow Button widget. 
function pib_load_follow_button_widget() {
	register_widget( 'Pib_Follow_Button_Widget' );
}



?>