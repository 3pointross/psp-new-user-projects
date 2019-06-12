<?php
/*
Plugin Name: Project Panorama - New User Projects
Plugin URI: https://www.projectpanorama.com/
Description: Automatically creates and assigns a project to a new user
Version: 1.2.2
Author: SnapOrbital
Author URI: https://www.snaporbital.com/
Text Domain: psp-projects
Domain Path: /languages
*/

add_action( 'plugins_loaded', 'psp_auto_init', 99999 );
function psp_auto_init() {

    do_action( 'psp_auto_before_init' );

    if( function_exists('psp_get_option') ) {
        require_once( 'init.php' );
    } else {
        add_action( 'admin_notices', 'psp_auto_needs_panorama' );
    }

    do_action( 'psp_auto_after_init' );

}

function psp_auto_needs_panorama() { ?>

    <div class="notice notice-error is-dismissible">
        <p><?php esc_html_e( 'Auto Generate Projects requires Project Panorama to run', 'psp_projects' ); ?></p>
    </div>

    <?php
}


 add_action( 'plugins_loaded', 'psp_auto_localize_init' );
 function psp_auto_localize_init() {
     load_plugin_textdomain( 'psp-auto', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
 }

$constants = array(
    'PSP_AUTO_URL'        =>  plugin_dir_url( __FILE__ ),
    'PSP_AUTO_PATH'       =>  plugin_dir_path( __FILE__ ),
    'PSP_AUTO_VER'        =>  '1.2.2',
);

foreach( $constants as $constant => $val ) {
    if( !defined( $constant ) ) define( $constant, $val );
}
