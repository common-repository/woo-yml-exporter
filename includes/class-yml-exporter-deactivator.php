<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://laputa.seomarket.ua
 * @since      1.0.0
 *
 * @package    Yml_Exporter
 * @subpackage Yml_Exporter/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Yml_Exporter
 * @subpackage Yml_Exporter/includes
 * @author     Seomarket Ukraine <zinchenko@seomarket.ua>
 */
class Yml_Exporter_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
	    require_once YML_EXPORTER_PLUGIN_DIR . '/admin/class-yml-exporter-admin.php';
	    require_once YML_EXPORTER_PLUGIN_DIR . '/component/GenerateStatus.php';
	    require_once YML_EXPORTER_PLUGIN_DIR . '/component/Offer.php';

	    // Schedule
        wp_clear_scheduled_hook( Yml_Exporter_Admin::YML_EXPORTER_CRON_GENERATE );

        // Options
        delete_site_option( GenerateStatus::STATUS_SETTING_NAME );
        delete_site_option( Offer::YML_EXPORT_VENDOR_FIELD );
        delete_site_option( Offer::YML_EXPORT_PICKUP_FIELD );
        delete_site_option( Offer::YML_EXPORT_MODEL_FIELD );
	}

}
