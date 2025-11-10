<?php
/*
 Plugin Name: Yatco Connect
 Plugin URI: https://yatco.com
 Version: 5.0.7
 Author: <a href="https://yatco.com">Yatco</a>
 Description: The YATCO WordPress Plugin was created to integrate the YATCO BOSS Multiple Listing Service’s Database into an existing Brokerage’s Consumer Word Press Site.  This plugin provides and easier method for template management, SEO management, and allows you to gain leads that are added to your YATCO BOSS CRM automatically upon generation by a New or already existing customer.  All the data for the Plugin comes from YATCO’s Data API’s. Template builder is available if you need to enhance the look of the api contents which can be managed from backend. 
 Text Domain: yatco-wp
 License: GPLv3
 GNU GPLv3 License Origin: http://www.gnu.org/licenses/gpl-3.0.html
*/

define('YATCO_CONNECT_VERSION', '5.0.7'); // update this with every release

define( 'YATCO_PLUGIN_DIR', dirname(__FILE__).'/' );

define( 'YATCO_PLUGIN_TEMPLATES_DIR', dirname(__FILE__).'/src/yatcoConnect/partials' );

define( 'YATCO_PLUGIN_ASSETS', plugin_dir_url(__FILE__) . '');

$environmentVariable = defined('WP_YATCO_ENV') ? WP_YATCO_ENV : 'production';

define( 'YATCO_ENV', $environmentVariable );

define( 'YATCO_LANG_KEY', 'yatco-wp' );

spl_autoload_register( 'yatcoConnect_autoloader' );


function yatcoConnect_autoloader( $class_name ) {
	if ( false !== strpos( $class_name, 'yatcoConnect_' ) ) {
		$classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
		$class_file = str_replace( '_', DIRECTORY_SEPARATOR, $class_name ) . '.php';
		require_once $classes_dir . $class_file;
	}
}

//////////////////////////////////
// Run initialization
/////////////////////////////////

register_activation_hook(__FILE__, function() {
    $installed_version = get_option('yatco_connect_version', '0.0.0');
    if ( version_compare( $installed_version, YATCO_CONNECT_VERSION, '<' ) ) {
        $installer = new yatcoConnect_Installer();
        $installer->activate();
        update_option('yatco_connect_version', YATCO_CONNECT_VERSION); // ensure this happens here
    }
});

function yatco_connect_plugin_check_upgrade() {
    $installed_version = get_option('yatco_connect_version', '0.0.0');
    if ( version_compare( $installed_version, YATCO_CONNECT_VERSION, '<' ) ) {
        $installer__ = new yatcoConnect_Installer();
        $installer__->install();
        update_option('yatco_connect_version', YATCO_CONNECT_VERSION);
        update_option('yatco_connect_upgrade_redirect', true);
    }
}
add_action('plugins_loaded', 'yatco_connect_plugin_check_upgrade');

add_action('plugins_loaded', 'init_yatcoConnect');

function init_yatcoConnect() {
    // Only load and run the init function if we know PHP version can parse it
    include_once('yatcoConnectWP_init.php');
    yactoConnect_init(__FILE__);
}

//////////////////////////////////
// Run Updater
/////////////////////////////////
require __DIR__.'/src/plugin-update-checker/plugin-update-checker.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://bitbucket.org/yatcollc/yatco-connect/',
    __FILE__,
    'yatco-connect'
);


$myUpdateChecker->setAuthentication(array(
    'consumer_key' => 'AR4X3peS43qVgqhQyk',
    'consumer_secret' => 'aTyMHd3RxEJncyVvQ9G8B4XdYDK5ShCQ',
));

//Optional: Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

//adding contact-form functionality
include_once('contact-form/contact-form.php');

//adding google-analytics functionality
include_once('google-analytics.php');

//adding custom-fonts functionality
include_once('custom-fonts.php');