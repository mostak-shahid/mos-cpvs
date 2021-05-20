<?php
/*
Plugin Name: Variable to Simple Product Converter
Description: Base of future plugin
Version: 0.0.1
Author: Md. Mostak Shahid
*/


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define MOS_CPVS_FILE.
if ( ! defined( 'MOS_CPVS_FILE' ) ) {
	define( 'MOS_CPVS_FILE', __FILE__ );
}
// Define MOS_CPVS_SETTINGS.
if ( ! defined( 'MOS_CPVS_SETTINGS' ) ) {
  //define( 'MOS_CPVS_SETTINGS', admin_url('/edit.php?post_type=post_type&page=plugin_settings') );
	define( 'MOS_CPVS_SETTINGS', admin_url('/options-general.php?page=mos_cpvs_settings') );
}
$mos_cpvs_options = get_option( 'mos_cpvs_options' );
$plugin = plugin_basename(MOS_CPVS_FILE); 
require_once ( plugin_dir_path( MOS_CPVS_FILE ) . 'mos-cpvs-functions.php' );
require_once ( plugin_dir_path( MOS_CPVS_FILE ) . 'mos-cpvs-settings.php' );
//require_once ( plugin_dir_path( MOS_CPVS_FILE ) . 'custom-settings.php' );

require_once('plugins/update/plugin-update-checker.php');
$pluginInit = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/mostak-shahid/update/master/mos-cpvs.json',
	MOS_CPVS_FILE,
	'mos-cpvs'
);


register_activation_hook(MOS_CPVS_FILE, 'mos_cpvs_activate');
add_action('admin_init', 'mos_cpvs_redirect');
 
function mos_cpvs_activate() {
    $mos_cpvs_option = array();
    // $mos_cpvs_option['mos_login_type'] = 'basic';
    // update_option( 'mos_cpvs_option', $mos_cpvs_option, false );
    add_option('mos_cpvs_do_activation_redirect', true);
}
 
function mos_cpvs_redirect() {
    if (get_option('mos_cpvs_do_activation_redirect', false)) {
        delete_option('mos_cpvs_do_activation_redirect');
        if(!isset($_GET['activate-multi'])){
            wp_safe_redirect(MOS_CPVS_SETTINGS);
        }
    }
}

// Add settings link on plugin page
function mos_cpvs_settings_link($links) { 
  $settings_link = '<a href="'.MOS_CPVS_SETTINGS.'">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
} 
add_filter("plugin_action_links_$plugin", 'mos_cpvs_settings_link' );



