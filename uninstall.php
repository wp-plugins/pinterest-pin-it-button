<?php
//If uninstall/delete not called from WordPress then exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

//Note this will run on manual deactivate, but should not run when upgrading/overwriting plugin

//Remove option records from options table
delete_option( 'pib_options' );
delete_option( 'pib_category_fields_option' );

//Remove any additional options and custom tables

?>
