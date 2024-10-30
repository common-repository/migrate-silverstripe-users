<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
Plugin Name: Migrate SilverStripe Users
Description: A plugin to migrate users from SilverStripe to WordPress.
Author: Ramlal Solanki
Author URI: https://about.me/ramlal
Version: 1.0
*/
//to add custom page in admin section
add_action('admin_menu', 'migrate_SilverStripe_users_plugin');
function migrate_SilverStripe_users_plugin(){
	$plugins_url	=	plugin_dir_url( __FILE__ ) . 'images/stow.jpg' ;
	add_menu_page( 'Migrate SilverStripe Users', 'Migrate SilverStripe Users', 'manage_options', 'migrate-SilverStripe-users-plugin', 'migrate_SilverStripe_users_init', $plugins_url );
}

function migrate_SilverStripe_users_init(){
	require plugin_dir_path( __FILE__ ) . 'migrate_SilverStripe_users.php';
}
?>