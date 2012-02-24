<?php
//If uninstall/delete not called from WordPress then exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

//Remove option records from options table
delete_option( 'pib_options' );

//Remove any additional options and custom tables

?>
