<?php
/*
Plugin Name: Project Panorama - New User Projects
Plugin URI: https://www.projectpanorama.com/
Description: Automatically creates and assigns a project to a new user
Version: 1.5
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
        <p><?php esc_html_e( 'New user projects requires Project Panorama to run', 'psp_projects' ); ?></p>
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
    'PSP_AUTO_VER'        =>  '1.5',
);

foreach( $constants as $constant => $val ) {
    if( !defined( $constant ) ) define( $constant, $val );
}

add_action( 'admin_enqueue_scripts', 'psp_nup_admin_assets' );
function psp_nup_admin_assets( $hook ) {

     wp_register_style( 'psp-nup-admin', PSP_AUTO_URL . 'assets/css/admin.css', array(), PSP_AUTO_VER );
     wp_register_script( 'psp-nup-admin', PSP_AUTO_URL . 'assets/js/admin.js', array('jquery'), PSP_AUTO_VER );

     if( $hook == 'settings_page_panorama-license') {
          wp_enqueue_style('psp-nup-admin');
          wp_enqueue_script('psp-nup-admin');
     }

}
