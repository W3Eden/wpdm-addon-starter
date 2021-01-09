<?php
/*
Plugin Name: WPDM - Add-on Starter
Description: Add-on Starter Script
Plugin URI: https://www.wpdownloadmanager.com/download/wpdm-add-on/
Author: WordPress Download Manager
Version: 1.0.0
Author URI: https://www.wpdownloadmanager.com/
Text Domain: wpdm-archive-page
*/

namespace WPDM\AddOn\CustomStats;


/**
 * Plugin version
 */
define('WPDMCSTAT_VERSION', '1.0.0');

/**
 * Text domain constant
 */
define('WPDMCSTAT_TEXT_DOMAIN', dirname(__DIR__));

/**
 * Plugin dir name
 */
define("WPDMCSTAT_DIR_NAME", basename(__DIR__));

/**
 * Plugin base dir
 */
define("WPDMCSTAT_BASE_DIR", dirname(__FILE__) . '/');

/**
 * Plugin base url
 */
define("WPDMCSTAT_BASE_URL", plugin_dir_url(__FILE__));

/**
 * Plugin asset url
 */
define("WPDMCSTAT_ASSET_URL", plugin_dir_url(__FILE__).'assets/');


class CustomStats
{


    function __construct()
    {

        $this->autoLoadClasses();

        $this->actions();


    }

    function actions()
    {
        add_action('plugins_loaded', array($this, 'loadEssentials') );

        add_action('wp_enqueue_scripts',array($this, 'enqueueScripts'));
        add_action('admin_enqueue_scripts',array($this, 'adminEnqueueScripts'));

    }

    /**
     * Class autoloader
     */
    function autoLoadClasses()
    {
        spl_autoload_register(function ($class) {

            // project-specific namespace prefix
            $prefix = 'WPDM\\AddOn\\CustomStats\\';

            // base directory for the namespace prefix
            $base_dir = __DIR__ . '/src/';

            // does the class use the namespace prefix?
            $len = strlen($prefix);
            if (strncmp($prefix, $class, $len) !== 0) {
                // no, move to the next registered autoloader
                return;
            }

            // get the relative class name
            $relative_class = substr($class, $len);

            // replace the namespace prefix with the base directory, replace namespace
            // separators with directory separators in the relative class name, append
            // with .php
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

            // if the file exists, require it
            if (file_exists($file)) {
                require $file;
            }
        });
    }

    /**
     * @usage Load essentials
     */
    function loadEssentials(){

        /**
         * Load text domain
         */
        load_plugin_textdomain(WPDMCSTAT_TEXT_DOMAIN, WPDMCSTAT_BASE_URL ."/languages/", WPDMCSTAT_TEXT_DOMAIN.'/languages/');
    }

    /**
     * Styles and scripts required for this plugin
     */
    function enqueueScripts(){
        global $post;

        if( is_object($post) ){
            if (
                !strpos($post->post_content,'wpdm_archive') &&
                !strpos($post->post_content,'wpdm_categories') &&
                !strpos($post->post_content,'wpdm_tags') &&
                !strpos($post->post_content,'wpdm_search_page') &&
                !strpos($post->post_content,'wpdm_simple_search')
            )
                return;
        }
        wp_enqueue_style("wpdmap-styles", WPDMCSTAT_ASSET_URL.'css/style.min.css');
        wp_enqueue_script("wpdmap-scripts", WPDMCSTAT_ASSET_URL.'js/scripts.js', ['wp-i18n'], WPDMCSTAT_VERSION);

    }
    function adminEnqueueScripts() {
        wp_enqueue_style("wpdmap-styles", WPDMCSTAT_ASSET_URL.'css/admin-style.min.css');
        wp_enqueue_script("wpdmap-scripts", WPDMCSTAT_ASSET_URL.'js/admin-scripts.js', ['wp-i18n'], WPDMCSTAT_VERSION);
    }

}

if(function_exists('WPDM'))
    $ArchivePages = new CustomStats();