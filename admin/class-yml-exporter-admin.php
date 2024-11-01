<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://laputa.seomarket.ua
 * @since      1.0.0
 *
 * @package    Yml_Exporter
 * @subpackage Yml_Exporter/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Yml_Exporter
 * @subpackage Yml_Exporter/admin
 * @author     Seomarket Ukraine <zinchenko@seomarket.ua>
 */
require_once YML_EXPORTER_PLUGIN_DIR . '/component/Offer.php';

class Yml_Exporter_Admin {

    const YML_EXPORTER_CRON_GENERATE = 'yml_exporter_generate';
    const YML_SUBMIT_ACTION = 'yml_submit_action';
    const YML_NONCE_ACTION = 'yml_nonce_action';
    const YML_NONCE_FIELD = 'yml_nonce_field';

    /**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Yml_Exporter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Yml_Exporter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/yml-exporter-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Yml_Exporter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Yml_Exporter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/yml-exporter-admin.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Add sub menu page to the WooCommerce menu.
     *
     * @since 0.0.1
     */
    public function add_admin_page() {
        if( ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) ){
            add_submenu_page(
                'woocommerce',
                'Yml Exporter',
                'Yml Exporter',
                'manage_options',
                $this->plugin_name,
                array( $this, 'display_admin_page' )
            );
        }else{
            add_menu_page(
                'Yml Exporter',
                'Yml Exporter',
                'manage_options',
                $this->plugin_name,
                array( $this, 'display_admin_page' )
            );
        }

    }

    /**
     * Display plugin page.
     *
     * @since 0.0.1
     */
    public function display_admin_page() {
        if(!empty($_POST[ self::YML_SUBMIT_ACTION ])){
            $this->saveOptions();
        }

        if(  is_plugin_active( 'woocommerce/woocommerce.php' )  ){
            require_once plugin_dir_path( __FILE__ ) . 'partials/yml-exporter-admin-display.php';
        }else{
            require_once plugin_dir_path( __FILE__ ) . 'partials/woocommerce-is-disabled.php';
        }
    }


    /**
     *
     */
    public function add_schedule(){
        if( defined('DOING_CRON') && DOING_CRON ){
            require_once YML_EXPORTER_PLUGIN_DIR . 'component/Xml.php';
            add_action( self::YML_EXPORTER_CRON_GENERATE, [ new Xml, 'generate' ] );
            if ( ! wp_next_scheduled( self::YML_EXPORTER_CRON_GENERATE ) ) {
                wp_schedule_event( time(), 'twicedaily', self::YML_EXPORTER_CRON_GENERATE );
            }
        }
    }


    /**
     *
     */
    private function saveOptions()
    {
        if(    wp_verify_nonce(  $_REQUEST[ self::YML_NONCE_FIELD ], self::YML_NONCE_ACTION  )    ){
            $fields = [
                Offer::YML_EXPORT_PICKUP_FIELD,
                Offer::YML_EXPORT_MODEL_FIELD,
                Offer::YML_EXPORT_VENDOR_FIELD,
            ];

            foreach ( $fields as $field ) {
                if( !empty( $_POST[ $field ] ) ){
                    if ( ( get_site_option( $field ) === false ) ) {
                        add_site_option( $field, $_POST[ $field ] );
                    } else {
                        update_site_option( $field, $_POST[ $field ] );
                    }
                }
            }
        }else{
            echo '<p>Ошибка проверки кода csrf</p>';
        }
    }

}
