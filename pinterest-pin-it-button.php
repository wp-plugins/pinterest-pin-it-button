<?php
/*
  Plugin Name: Pinterest "Pin It" Button
  Plugin URI: http://pinterestplugin.com/
  Description: Add a Pinterest "Pin It" button to your posts and images.
  Version: 1.0.2
  Author: Phil Derksen
  Author URI: http://pinterestplugin.com/
*/

/*  Copyright 2011 Phil Derksen (pderksen@gmail.com)

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
		'display_on_post_excerpts' => 0
	);

	//Save default option values
	update_option( 'pib_options', $pib_options );
}

register_activation_hook( __FILE__, 'pib_install' );

// TODO Display admin notice to proceed to options
/*
function pib_plugin_activate_notice() {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
    
    if(is_plugin_active(PIB_PLUGIN_BASENAME))

    {
        //ADD message to only Plugin Page
        global $pagenow;
        if($pagenow == 'plugins.php')
            {
                echo "<div class='updated'><p>" . 
                    sprintf( __( '<a href="%1$s">Set where your "Pin It" Button is displayed</a>' ), "admin.php?page=".PIB_PLUGIN_BASENAME ) . 
                    "</p></div>";
            }
    }
}

add_action('admin_notices', 'pib_plugin_activate_notice');
*/


/********************
  Public-Only Functions
********************/

//Add Public CSS/JS

function pib_add_public_css_js() {
	wp_enqueue_style( 'pinterest-pin-it-button', plugins_url( '/css/pinterest-pin-it-button.css' , __FILE__ ) );
    wp_enqueue_script( 'pinterest-pin-it-button', plugins_url( '/js/pinterest-pin-it-button.js', __FILE__ ), array( 'jquery' ) );
}

add_action( 'wp_enqueue_scripts', 'pib_add_public_css_js' );

//Button html to render

function pib_button_html() {

    $btn_html = 
		'<div class="pinit-button-wrapper">' .
        '<a href="javascript:exec_pinmarklet();" id="PinItButton" title="Pin It on Pinterest"></a>' .
        '</div>';

    return $btn_html;
}

//Add Pin it button to pages

function pib_render_btn( $content )
{
    $render_btn = false;

	//Load options array
	$pib_options = get_option( 'pib_options' );
    
    //Determine if displayed on current page
    if ( is_home() && ( $pib_options['display_home_page'] ) ) {
        $render_btn = true;
    }
	
	if ( is_front_page() && ( $pib_options['display_front_page'] ) ) {
        $render_btn = true;
    }
    
    if ( is_single() && ( $pib_options['display_posts'] ) ) {
        $render_btn = true;
    }    

    if ( is_page() && ( $pib_options['display_pages'] ) ) {
        $render_btn = true;
    }    

    if ( is_archive() && ( $pib_options['display_archives'] ) ) {
        $render_btn = true;
    }    
    
    if ( $render_btn ) {
        //Display above and/or below content
        if ( $pib_options['display_above_content'] )	{
            $content = pib_button_html() . $content;
        }

        if ( $pib_options['display_below_content'] )	{
            $content .= pib_button_html();
        }
    }
    
	return $content;
}

add_filter( 'the_content', 'pib_render_btn' );

//Render button on excerpts if option checked
//Test excerpt content on home/archives with Woothemes Canvas theme

function pib_render_btn_excerpt( $content )
{
    $render_btn = false;

	//Load options array
	$pib_options = get_option( 'pib_options' );
    
    if ( $pib_options['display_on_post_excerpts'] ) {
    
        if ( is_home() && ( $pib_options['display_home_page'] ) ) {
            $render_btn = true;
        }

        if ( is_front_page() && ( $pib_options['display_front_page'] ) ) {
            $render_btn = true;
        }
        
        if ( is_archive() && ( $pib_options['display_archives'] ) ) {
            $render_btn = true;
        }    
    }
    
    if ( $render_btn ) {
        //Display above and/or below content
        if ( $pib_options['display_above_content'] )	{
            $content = pib_button_html() . $content;
        }

        if ( $pib_options['display_below_content'] )	{
            $content .= pib_button_html();
        }
    }
    
	return $content;
}

add_filter( 'the_excerpt', 'pib_render_btn_excerpt' );

//Register shortcode: [pinit]

function pib_shortcode_pinit() {
	return pib_button_html();
}

add_shortcode( 'pinit', 'pib_shortcode_pinit' );


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

	?>
        <div class="wrap">
            <a href="http://pinterestplugin.com/" target="_blank"><div id="pinit-button-icon-32" class="icon32"
                style="background: url(<?php echo plugins_url( '/img/pinit-button-icon-med.png', __FILE__ ); ?>) no-repeat;"><br /></div></a>
            <h2>Pinterest "Pin It" Button Settings</h2>
            
            <?php settings_errors(); //Display status messages after action ("settings saved", errors) ?>
            
            <form method="post" action="options.php">
                <?php settings_fields( 'pib-settings-group' ); ?>
                
                <h3>What types of pages should the button appear on?</h3>
                
                <?php //Not using <th scope="row"> as it's too wide for the checkboxes ?>
                <table class="form-table">
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
                
                <h3>Where on each page should the button appear?</h3>
                
                <table class="form-table">
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
                            <input name="Submit" type="submit" value="Save Changes" class="button-primary" />
                        </td>
                    </tr>
                </table>
            </form> 
            <h3>Use the shortcode <code style="font-size: 14px;">[pinit]</code> to display the button within the content.</h3>
        </div>
    <?php
}

// Add a link to the settings page to the plugins list

function pib_plugin_action_links( $links, $file ) {
	if ( $file != PIB_PLUGIN_BASENAME )
		return $links;

	$url = admin_url('admin.php?page='.PIB_PLUGIN_BASENAME);

	$settings_link = '<a href="' . esc_attr( $url ) . '">'
		. esc_html( __( 'Settings') ) . '</a>';

	array_unshift( $links, $settings_link );

	return $links;
}

add_filter( 'plugin_action_links', 'pib_plugin_action_links', 10, 2 );

?>