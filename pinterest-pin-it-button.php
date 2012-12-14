<?php
/*
  Plugin Name: Pinterest "Pin It" Button Lite
  Plugin URI: http://pinterestplugin.com
  Description: Add a Pinterest "Pin It" Button to your posts and pages allowing your readers easily pin your images. Includes shortcode and widget.
  Author: Phil Derksen
  Author URI: http://pinterestplugin.com
  Version: 1.4.3
  License: GPLv2
  Copyright 2012 Phil Derksen (phil@pinterestplugin.com)
*/

define( 'PIB_IS_PRO', false );

/***************************
* Global Constants
***************************/

define( 'PIB_BASE_NAME', plugin_basename( __FILE__ ) );	    // pinterest-pin-it-button/pinterest-pin-it-button.php
define( 'PIB_BASE_DIR_SHORT', dirname( PIB_BASE_NAME ) );	// pinterest-pin-it-button
define( 'PIB_BASE_DIR_LONG', dirname( __FILE__ ) );			// ../wp-content/plugins/pinterest-pin-it-button (physical file path)
define( 'PIB_INC_DIR', PIB_BASE_DIR_LONG . '/inc/' );		// ../wp-content/plugins/pinterest-pin-it-button/inc/  (physical file path)
define( 'PIB_BASE_URL', plugin_dir_url( __FILE__ ) );		// http://mysite.com/wp-content/plugins/pinterest-pin-it-button/
define( 'PIB_IMAGES_URL', PIB_BASE_URL . 'img/' );			// http://mysite.com/wp-content/plugins/pinterest-pin-it-button/img/
define( 'PIB_CSS_URL', PIB_BASE_URL . 'css/' );
define( 'PIB_JS_URL', PIB_BASE_URL . 'js/' );

define( 'PIB_UPGRADE_URL_BASE', 'http://pinterestplugin.com/pin-it-button-pro/' );
define( 'PIB_DEFAULT_CUSTOM_BUTTON_IMAGE_URL', PIB_IMAGES_URL . 'pin-it-buttons/set01/a04.png' );

register_activation_hook( __FILE__, 'pib_install' );

$pib_options = get_option( 'pib_options' );

/***************************
* Includes
***************************/

require_once( PIB_INC_DIR . 'admin-general-functions-shared.php' );
require_once( PIB_INC_DIR . 'admin-display-functions-shared.php' );
require_once( PIB_INC_DIR . 'public-display-functions.php' );

if ( PIB_IS_PRO ) {
    require_once( PIB_INC_DIR . 'admin-general-functions-pro.php' );
    require_once( PIB_INC_DIR . 'admin-display-functions-pro.php' );
    require_once( PIB_INC_DIR . 'public-display-functions-pro.php' );
}
else {
    require_once( PIB_INC_DIR . 'admin-display-functions-lite.php' );
}

require_once( PIB_INC_DIR . 'widget.php' );
require_once( PIB_INC_DIR . 'shortcode.php' );
require_once( PIB_INC_DIR . 'post-meta.php' );
require_once( PIB_INC_DIR . 'category-meta.php' );

/***************************
* Debug Code
***************************/

function pib_debug_print( $value ) {
	print_r( $value );
    echo '<br/>';
}
