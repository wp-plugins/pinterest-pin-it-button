<?php

//*** Admin General Functions - Lite & Pro Shared ***

//Plugin installation/activation

function pib_install() {
    global $pib_options;
    
	//Deactivate plugin if WP version too low
    if ( version_compare( get_bloginfo( 'version' ), '3.0', '<' ) ) {
        deactivate_plugins( basename( __FILE__ ) );
    }
    
	//Setup default options for values that don't exist and need to be set to 1/true/value (not 0/false/blank)
    //Done this way to preseve options saved in previous versions
    if ( !isset( $pib_options['button_style'] ) ) { $pib_options['button_style'] = 'user_selects_image'; }
    if ( !isset( $pib_options['count_layout'] ) ) { $pib_options['count_layout'] = 'none'; }
    
    if ( !isset( $pib_options['custom_btn_img_url'] ) ) { $pib_options['custom_btn_img_url'] = PIB_DEFAULT_CUSTOM_BUTTON_IMAGE_URL; }
    
    if ( !isset( $pib_options['share_btn_1'] ) ) { $pib_options['share_btn_1'] = 'pinterest'; }
    if ( !isset( $pib_options['share_btn_2'] ) ) { $pib_options['share_btn_2'] = 'facebook'; }
    if ( !isset( $pib_options['share_btn_3'] ) ) { $pib_options['share_btn_3'] = 'twitter'; }
    if ( !isset( $pib_options['share_btn_4'] ) ) { $pib_options['share_btn_4'] = 'gplus'; }    
    
    if ( !isset( $pib_options['display_home_page'] ) ) { $pib_options['display_home_page'] = 1; }
    if ( !isset( $pib_options['display_posts'] ) ) { $pib_options['display_posts'] = 1; }
    if ( !isset( $pib_options['display_pages'] ) ) { $pib_options['display_pages'] = 1; }
    if ( !isset( $pib_options['display_below_content'] ) ) { $pib_options['display_below_content'] = 1; }
    
	//Save default option values
	update_option( 'pib_options', $pib_options );
}

register_activation_hook( __FILE__, 'pib_install' );

//Register settings

function pib_register_settings() {
	register_setting( 'pib_settings_group', 'pib_options' );
}

add_action( 'admin_init', 'pib_sharing_add_meta_box' );
add_action( 'admin_init', 'pib_register_settings' );

//Add settings page to admin menu
//Use $page variable to load CSS/JS ONLY for this plugin's admin page

function pib_create_menu() {
	//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
    $page = add_menu_page( __( 'Pin It Button ' . pib_pro_or_lite() . ' Settings', 'pib' ), __( 'Pin It Button ' . pib_pro_or_lite(), 'pib' ), 'manage_options',
        'pib_pin_it_button', 'pib_settings_page', PIB_IMAGES_URL . 'pinterest-button-icon-small.png' );
    
	add_action( 'admin_print_styles-' . $page, 'pib_add_admin_css_js' );
}

add_action( 'admin_menu', 'pib_create_menu' );

//Add Admin CSS/JS

function pib_add_admin_css_js() {
	wp_enqueue_script( 'jquery' );

    //Add thickbox JS/CSS for custom button image gallery popup (and button examples)
    add_thickbox();
	
	wp_enqueue_style( 'pinterest-pin-it-button', PIB_CSS_URL . 'pinterest-pin-it-button-admin.css' );
    wp_enqueue_script( 'pinterest-pin-it-button', PIB_JS_URL . 'pinterest-pin-it-button-admin.js', array( 'jquery' ), '' );
}
