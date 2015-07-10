<?php
/**
 * Plugin Name: Save Menu As
 * Plugin URI: http://www.infogeek.gr
 * Description: Allows you to save as menus, in case you want a slightly different menu from one you already have.
 * Author: Konstantinos Tsatsarounos
 * Author URI: http://www.infogeek.gr
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

//Define plugin constant if not exists
if(!defined('SM_AS')){
    define('SM_AS', '1.0');

    define( 'SM_AS_FILE', __FILE__);
}

//Require MenuCloner
require_once plugin_dir_path(SM_AS_FILE).'/app/MenuCloner.php';


//Plugin ajax handler
if (!function_exists('sm_save_as_menu')) {
    function sm_save_as_menu()
    {

        if(current_user_can('activate_plugins')){
            $menu_name = filter_input(INPUT_POST,'name', FILTER_SANITIZE_MAGIC_QUOTES);
            $menu_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $key = filter_input(INPUT_POST, 'key', FILTER_SANITIZE_MAGIC_QUOTES);

            if ( !wp_verify_nonce($key, 'sm_action') ) {
                die('Please... don\'t make requests you know are not going to get the response you hope for!');
            }

            $cloner = new MenuCloner();

            $cloner->readMenu($menu_id);
            die( $cloner->createMenu($menu_name) );
        }
    }

    add_action('wp_ajax_sm_save_as_menu', 'sm_save_as_menu', 1);
}

if (!function_exists('sm_initialize_functionality')) {
    function sm_initialize_functionality()
    {
        $current_screen = get_current_screen();

        $localization_data = array(
            'url' => admin_url('admin-ajax.php'),
            'sm_cnonce' => wp_create_nonce('sm_action'),
        );

        if( $current_screen->id == 'nav-menus'){
            wp_enqueue_style('sm_style',plugins_url('/assets/style.css', SM_AS_FILE), array('wp-jquery-ui-dialog'), '1.0');
            wp_enqueue_script('sm_script', plugins_url('/assets/js.js', SM_AS_FILE), array('jquery', 'jquery-ui-core', 'jquery-ui-dialog'), '1.0', true);

            wp_localize_script('sm_script', 'sm_vars', $localization_data);
        }
    }

    add_action('admin_enqueue_scripts', 'sm_initialize_functionality', 1);
}
