<?php
/*
  Plugin Name: Pinterest "Pin It" Button
  Plugin URI: http://pinterestplugin.com/
  Description: Add a Pinterest "Pin It" button to your posts and images.
  Version: 0.1.2
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

/*** Global Constants ***/

/*** Public Rendering Functions ***/

//Initialize page
/*
function pib_public_init()
{
	//Load CSS & JS files
	wp_enqueue_style('pinterest-pin-it-button', plugins_url('/pinterest-pin-it-button.css', __FILE__));
}

add_action('init', 'pib_public_init');
*/

function pib_add_styles_scripts()
{
	$css_url = plugins_url('/css/pinterest-pin-it-button.css', __FILE__);
	wp_register_style('pinterest-pin-it-button', $css_url);
	wp_enqueue_style('pinterest-pin-it-button');
    
    $js_url = plugins_url('/js/pinterest-pin-it-button.js', __FILE__);
    wp_register_script('pinterest-pin-it-button', $js_url);
    wp_enqueue_script('pinterest-pin-it-button');
}

add_action('wp_enqueue_scripts', 'pib_add_styles_scripts');

function add_pin_it_button($content)
{
	//Just single posts would use:
	//if (is_single())
	global $post;
	
	//TODO Pin count possible values: "horizontal", "vertical", "none" */
	$pinCount = "none";
        
    //OLD Create pin it button mimicking Pinterest official javascript bookmarklet
    $bookmarkletJS = "javascript:void((function(){var e=document.createElement('script');e.setAttribute('type','text/javascript');e.setAttribute('charset','UTF-8');e.setAttribute('src','http://assets.pinterest.com/js/pinmarklet.js?r=' + Math.random()*99999999);document.body.appendChild(e)})());";
    
    //Execute pin it javascript function with onclick event    
    $btnHtml = 
		'<div class="pin-it-button-wrapper">' .
		//'<a href="' . $bookmarkletJS . '" id="PinItButton" title="Pin it on Pinterest">Pin it</a>' .
        '<a href="#" onclick="exec_pinmarklet();" id="PinItButton" title="Pin it on Pinterest">Pin it</a>' .
        '</div>';
	
	//load our options array
	$pib_options = get_option('pib_options');

	//set variables from option values
	$display_location = $pib_options['display_location'];	
	
	//TODO Display above or below content
	if (is_null($display_location) || $display_location == 'above_content')
	{
		$content = $btnHtml . $content;
	}
	elseif ($display_location == 'below_content')
	{
		$content .= $btnHtml;
	}
	else
	{
		$content .= $btnHtml;
	}
	
	//TODO both above and below
	
	return $content;
}

//Add after content
add_filter("the_content", "add_pin_it_button");


/*** Install and Admin setup ***/

//Call function when plugin is activated
function pib_install()
{
	//setup our default option values
	$pib_options_arr = array(
		"display_location" => "above_content"
		);

	//save our default option values
	update_option('pib_options', $pib_options_arr);
}

register_activation_hook(__FILE__,'pib_install');

//Initialize the plugin in admin
function pib_admin_init()
{
	//TODO
}

add_action('admin_init', 'pib_admin_init');

//Register our option settings
function pib_register_settings()
{
	register_setting('pib-settings-group', 'pib_options');
	//TODO
}

add_action('admin_init', 'pib_register_settings');

//Add the plugin menu item
function pib_create_menu() {
	add_options_page("Pin It Button Settings Page", "Pin It Button", "administrator", __FILE__,	"pib_settings_page");
}

add_action('admin_menu', 'pib_create_menu');

function pib_settings_page()
{ 
	//load our options array
	$pib_options = get_option('pib_options');

	//set variables from option values
	$display_location = $pib_options['display_location'];
	?>
	<div class="wrap">
		<h2><?php _e('Pinterest "Pin It" Button Options', 'pib-plugin') ?></h2>

		<form method="post" action="options.php">
			<?php settings_fields('pib-settings-group'); ?>
			
			<table class="form-table">
				<tr valign="top">
				<th scope="row"><?php _e('Display Location', 'pib-plugin') ?></th>
				<td>
					<select name="pib_options[display_location]">
						<option value="above_content" <?php echo (($display_location == 'above_content') ? 'selected="selected"' : '') ?>>Above content</option>
						<option value="below_content" <?php echo (($display_location == 'below_content') ? 'selected="selected"' : '') ?>>Below content</option>
					</select>
				</td>
				</tr>
				 
			</table>
			
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'pib-plugin') ?>" /> 
			</p>

		</form>
	</div>
	<?php
}
?>
