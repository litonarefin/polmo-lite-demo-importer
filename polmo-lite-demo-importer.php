<?php
/**
 * Plugin Name: Polmo Lite Demo Importer
 * Description: Demo content setup for the Polmo Lite theme
 * Plugin URI: https://prowptheme.com/themes/polmo-business-wordpress-theme/
 * Author: Jewel Theme
 * Version: 1.0.0
 * Author URI: https://master-addons.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: brooklyn-lite
 * Domain Path: /languages
 */


// No, Direct access Sir !!!
 if ( !defined( 'ABSPATH' ) ) exit;


//This plugin is only useful for the Polmo Lite Theme
$theme  = wp_get_theme();
$parent = wp_get_theme()->parent();
if ( ( $theme != 'Polmo Lite' ) && ( $parent != 'Polmo Lite' ) )
    return;

//Dir
define( 'POLMO_LITE_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );    
define( 'POLMO_LITE_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );




// Demo Import Files
function polmo_lite_import_files() {

    $demos = array( 'agency', 'business');

    foreach ( $demos as $demo ) {

        $demo_sites[] = array(
            'import_file_name'                  => ucfirst( preg_replace('/[0-9]+/', '', $demo ) ),
            'local_import_file'                 => POLMO_LITE_DIR . 'demo-content/polmo-dc-' . $demo . '.xml',   
            'local_import_widget_file'          => POLMO_LITE_DIR . 'demo-content/polmo-w-' . $demo . '.wie',
            'local_import_customizer_file'      => POLMO_LITE_DIR . 'demo-content/polmo-c-' . $demo . '.dat',
            'import_preview_image_url'          => POLMO_LITE_URI . 'demo-content/previews/' . $demo . '-hero-thumb.png', 
            'preview_url'                       => 'https://demo.prowptheme.com/polmo-' . $demo,
        );
    }

    return $demo_sites;
}
add_filter( 'pt-ocdi/import_files', 'polmo_lite_import_files' );


/**
 * Actions that happen after import
 */
function polmo_lite_after_import_setup( $selected_import ) {


	// Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Menu', 'nav_menu' );
    set_theme_mod( 'nav_menu_locations', array(
            'main-menu' => $main_menu->term_id,
        )
    );

    //Asign the static front page and the blog page
    $demos = array( 'agency', 'business');
    $front_page = '';
    foreach ( $demos as $demo ) {
    	$home_title = ucwords($demo) . ' - Home';
    	$front_page = get_page_by_title( $home_title );	
    }
    
    $blog_page  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page -> ID );
    update_option( 'page_for_posts', $blog_page -> ID );

    //Assign the Front Page template
    update_post_meta( $front_page -> ID, '_wp_page_template', 'page-templates/template_page-builder.php' );
}

add_action( 'pt-ocdi/after_import', 'polmo_lite_after_import_setup' );


/**
* Remove branding
*/
add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );

/**
* Stop Regenerate Thumbnails while importing
*/
add_filter( 'pt-ocdi/regenerate_thumbnails_in_content_import', '__return_false' );

/**
* Waiting Message
*/
function polmo_lite_plugin_intro_text( $default_text ) {
    $default_text .= '<div class="ocdi__intro-text"><strong>'. esc_html('Demo Importing will take few minutes depending on your Hosting Server.','polmo-lite') . '</strong></div><br>';

    return $default_text;
}
add_filter( 'pt-ocdi/plugin_intro_text', 'polmo_lite_plugin_intro_text' );


/**
* Menu Settings
*/
function polmo_lite_plugin_page_setup( $default_settings ) {
    $default_settings['parent_slug'] = 'polmo-lite-info.php';
    $default_settings['page_title']  = esc_html__( 'Polmo Lite - Import Demo Data' , 'polmo-lite' );
    $default_settings['menu_title']  = esc_html__( 'Demo Importer' , 'polmo-lite' );
    $default_settings['capability']  = 'import';
    $default_settings['menu_slug']   = 'polmo-lite-demo-importer';

    return $default_settings;
}
add_filter( 'pt-ocdi/plugin_page_setup', 'polmo_lite_plugin_page_setup' );


/**
* Warning Dialog options
*/
function polmo_lite_confirmation_dialog_options ( $options ) {
    return array_merge( $options, array(
        'width'       => 300,
        'dialogClass' => 'wp-dialog',
        'resizable'   => false,
        'height'      => 'auto',
        'modal'       => true,
    ) );
}
add_filter( 'pt-ocdi/confirmation_dialog_options', 'polmo_lite_confirmation_dialog_options', 10, 1 );