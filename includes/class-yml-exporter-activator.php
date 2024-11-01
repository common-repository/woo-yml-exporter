<?php

/**
 * Fired during plugin activation
 *
 * @link       https://laputa.seomarket.ua
 * @since      1.0.0
 *
 * @package    Yml_Exporter
 * @subpackage Yml_Exporter/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Yml_Exporter
 * @subpackage Yml_Exporter/includes
 * @author     Seomarket Ukraine <zinchenko@seomarket.ua>
 */
class Yml_Exporter_Activator {

	/**
	 * @since    1.0.0
	 */
	public static function activate() {
	    if( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ){
	        throw new Exception("Плагин Woocommerce неактивен. Активируйте его и повторите активацию плагина Yml Export");
        }

        self::add_schedule();
	}


    /**
     *
     */
    private static function add_schedule(){
        require_once YML_EXPORTER_PLUGIN_DIR . '/admin/class-yml-exporter-admin.php';
        require_once YML_EXPORTER_PLUGIN_DIR . '/component/Xml.php';
        add_action( Yml_Exporter_Admin::YML_EXPORTER_CRON_GENERATE, [ new Xml, 'generate' ] );
        if ( ! wp_next_scheduled( Yml_Exporter_Admin::YML_EXPORTER_CRON_GENERATE ) ) {
            wp_schedule_event( time(), 'twicedaily', Yml_Exporter_Admin::YML_EXPORTER_CRON_GENERATE );
        }
    }

}
