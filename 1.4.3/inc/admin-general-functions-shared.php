<?php

//*** Admin General Functions - Lite & Pro Shared ***

//Plugin installation/activation
//register_activation_hook needs to be in main plugin file

function pib_install() {
    global $pib_options;
    
    //Need to retrieve options here
    $pib_options = get_option( 'pib_options' );
    
	//Deactivate plugin if WP version too low
    if ( version_compare( get_bloginfo( 'version' ), '3.2', '<' ) ) {
        deactivate_plugins( basename( __FILE__ ) );
    }
    
    //Set default options for plugin, but don't overwrite existing values
    //Boolean values should be set to 1 for true, 0 for false
    $default_options = array(
        'button_style' => 'user_selects_image',
        'count_layout' => 'none',        
        
        'display_home_page' => 1,
        'display_front_page' => 0,
        'display_posts' => 1,
        'display_pages' => 0,
        'display_archives' => 0,
        'display_above_content' => 0,
        'display_below_content' => 1,
        'display_on_post_excerpts' => 0,
        
        'uninstall_save_settings' => 1,
        'custom_css' => '',
        'remove_div' => 0,
        
        //Pro options
        'pib_license_key' => '',
        'use_featured_image' => 0,
        
        'use_custom_img_btn' => 0,
        'custom_btn_img_url' => PIB_DEFAULT_CUSTOM_BUTTON_IMAGE_URL,
        
        'use_other_sharing_buttons' => 0,
        'share_btn_1' => 'pinterest',
        'share_btn_2' => 'facebook',
        'share_btn_3' => 'twitter',
        'share_btn_4' => 'gplus',
        'sharebar_btn_width' => '100',
        'sharebar_hide_count' => 0
    );
    
    //Loop through global options and set them to defaults
    //If already set (from previous install - check with isset) don't overwrite
    foreach ( $default_options as $option => $value ) {        
        if ( !isset( $pib_options[$option] ) ) {
            $pib_options[$option] = $value;
        }
    }

	//Save default option values
	update_option( 'pib_options', $pib_options );
}

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

//Admin URL

function pib_admin_url() {
    return admin_url( 'admin.php?page=pib_pin_it_button' );
}

//Add Settings link to the left of Deactivate on plugins list page

function pib_plugin_settings_link( $links ) {
	$url = pib_admin_url();
	$settings_link = '<a href="' . $url . '">Settings</a>';
	array_unshift( $links, $settings_link );
	return $links;
}

add_filter( 'plugin_action_links_' . PIB_BASE_NAME, 'pib_plugin_settings_link' );

//Google campaign tracking URL (querystring part)

function pib_campaign_url( $medium, $campaign ) {
    return '?utm_source=pib_' . ( PIB_IS_PRO ? 'pro' : 'lite' ) . '&utm_medium=' . $medium . '&utm_campaign=' . $campaign;
}
