<?php

/**
 * @link              https://laputa.seomarket.ua
 * @since             1.0.0
 * @package           Yml_Exporter
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Yml Exporter
 * Plugin URI:        http://laputa.seomarket.ua/wiki/yml-exporter/
 * Description:       Export WooCommerce store products to local xml-file(yml) accessible by http. ( <a href="https://laputa.seomarket.ua/wiki/17">About yml-format<a> )
 * Version:           1.0.0
 * Author:            Seomarket Ukraine
 * Author URI:        http://seomarket.ua/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       yml-exporter
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-yml-exporter-activator.php
 */
function activate_yml_exporter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-yml-exporter-activator.php';
	Yml_Exporter_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-yml-exporter-deactivator.php
 */
function deactivate_yml_exporter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-yml-exporter-deactivator.php';
	Yml_Exporter_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_yml_exporter' );
register_deactivation_hook( __FILE__, 'deactivate_yml_exporter' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-yml-exporter.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_yml_exporter() {

    define('YML_EXPORTER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	$plugin = new Yml_Exporter();
	$plugin->run();

}
run_yml_exporter();
