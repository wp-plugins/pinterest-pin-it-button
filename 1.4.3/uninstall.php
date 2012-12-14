<?php

//If uninstall/delete not called from WordPress then exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

//Delete plugin options only if value checked

global $pib_options;

//Need to retrieve options here
$pib_options = get_option( 'pib_options' );

if ( !(bool)$pib_options['uninstall_save_settings'] ) {

    //Remove option records from options table
    delete_option( 'pib_options' );
    delete_option( 'pib_category_fields_option' );

    //Remove custom post meta fields
    $posts = get_posts( array( 'numberposts' => -1 ) );

    foreach( $posts as $post ) {
        delete_post_meta( $post->ID, 'pib_sharing_disabled' );
        delete_post_meta( $post->ID, 'pib_url_of_webpage' );
        delete_post_meta( $post->ID, 'pib_url_of_img' );
        delete_post_meta( $post->ID, 'pib_description' );
    }
}
