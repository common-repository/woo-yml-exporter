<?php

require_once 'GenerateStatus.php';
require_once 'Offer.php';
/**
 * Created by PhpStorm.
 * User: maksim
 * Date: 19.07.17
 * Time: 14:32
 */
class Xml
{
    const LAPUTA_FEED_URL = 'laputa_feed_url';

    /**
     * @var string
     */
    private $currency;

    /**
     * @var int
     */
    private $timer_start;


    function __construct()
    {
        $this->currency = self::getCurrency();
    }


    /**
     * @return string
     */
    public static function getFileRelativeName()
    {
        return "/wp-content/uploads/feed-yml.xml";
    }


    /**
     *
     */
    function generate(){

        self::checkWooCommerce();

        $this->setStartStatus();

        try{

            $result_yml = $this->getFeedXmlString();

            // Сохранить файл
            if( false === file_put_contents( self::getFileName(), $result_yml ) ){
                throw new ErrorException("Ошибка сохранения файла выгрузки в {$result_yml}");
            }else{
                (get_site_option( self::LAPUTA_FEED_URL ) === false)
                    ? add_site_option( self::LAPUTA_FEED_URL, self::getFileRelativeName() )
                    : update_site_option( self::LAPUTA_FEED_URL, self::getFileRelativeName() );
            }
            // Статус - завершен
            $this->setSuccessStatus();

        }catch ( Exception $e ){
            $time = date('Y-m-d H:i:s');
            $exception_message = "[{$time}] ({$e->getCode()}) {$e->getMessage()}:{$e->getLine()}";

            $this->setErrorStatus( $exception_message );
        }

    }


    /**
     *
     */
    private static function checkWooCommerce()
    {
        if ( !self::is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            print 'WooCommerce is not active';
            die;
        }
    }

    private static function is_plugin_active($plugin){
        return in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) || is_plugin_active_for_network( $plugin );
    }


    /**
     * @return string
     */
    private function getCommonFields()
    {
        $blogname = get_site_option( 'blogname' );
        $home_url = home_url( '/' );
        $generate_time = current_time( 'Y-m-d H:i' );
        $result_yml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<yml_catalog date=\"{$generate_time}\">\n"
            . "<shop>\n<name>{$blogname}</name>\n"
            . "<company>{$blogname}</company>\n"
            . "<url>{$home_url}</url>\n";


        return $result_yml;
    }


    /**
     * @return string
     * @throws ErrorException
     */
    private function getCurrencies()
    {
        $yml_currency = self::getCurrency();

        return "<currencies>\n<currency id=\"{$yml_currency}\" rate=\"1\"/>\n</currencies>\n";
    }



    /**
     * @return array
     */
    static function getAvaiableCurrencies(){
        return [
            "RUB" => "RUR",
            "USD" => "USD",
            "UAH" => "UAH",
            "KZT" => "KZT",
        ];
    }


    /**
     * @return string
     */
    private function getCategories()
    {
        $category_list = get_terms( "product_cat" );
        $count = count( $category_list );
        $categories_string = '';
        if ( $count > 0 ) {
            $categories_string .= "<categories>\n";
            foreach ( $category_list as $category ) {
                if ( $category->parent !== 0 ) {
                    $parent = "parentId=\"{$category->parent}\"";
                } else {
                    $parent = '';
                }
                $categories_string .= "<category id=\"{$category->term_id}\" {$parent}>{$category->name}</category>\n";
            }
            $categories_string .= '</categories>' . "\n";
        }


        return $categories_string;
    }


    /**
     * @return string
     */
    private function getProducts()
    {
        $offer_string = '<offers>' . "\n";
        $products = get_posts( [
            'post_type' => 'product',
            'posts_per_page' => -1,
        ] );

        if(   !empty( $products )   ) {
            foreach ( $products as $product ) {
                /**
                 * @var WP_Post $product
                 */
                $offer = new Offer( $product, $this->currency );
                $offer_string .= $offer->getOfferString();
            }
        }

        $offer_string .= "</offers>\n</shop>\n</yml_catalog>";


        return $offer_string;
    }


    /**
     * @return mixed
     * @throws ErrorException
     */
    static function getCurrency()
    {
        $yml_currency = null;
        $aviable_currency_list = self::getAvaiableCurrencies();

        $currency = get_woocommerce_currency(); // получаем валюта магазина

        if ( array_key_exists( $currency, $aviable_currency_list ) ) {
            $yml_currency = $aviable_currency_list[ $currency ];
        } else {
            throw new ErrorException( "Валюта не поддерживается: {$currency}. " .
                "Доступны такие валюты: " .
                print_r(
                    array_keys( $aviable_currency_list ),
                    true
                )
            );
        }


        return $yml_currency;
    }


    /**
     * @return string
     */
    private static function getFileName()
    {
        return ABSPATH . self::getFileRelativeName();
    }


    /**
     * @return string
     */
    private function getFeedXmlString()
    {
        // Заполнить общие поля
        $result_yml = $this->getCommonFields();

        // Заполнить валюты
        $result_yml .= $this->getCurrencies();

        // Заполнить категории
        $result_yml .= $this->getCategories();

        // Заполнить товары
        $result_yml .= $this->getProducts();


        return $result_yml;
    }


    private function setStartStatus()
    {
        GenerateStatus::set(
            json_encode(
                [
                    GenerateStatus::STATUS => GenerateStatus::RUNNING,
                    GenerateStatus::DATETIME => date( GenerateStatus::DATETIME_FORMAT ),
                    GenerateStatus::PID => getmypid(),
                ]
            )
        );
        $this->timer_start = time();
    }


    /**
     *
     */
    private function setSuccessStatus()
    {
        GenerateStatus::set(
            json_encode(
                [
                    GenerateStatus::STATUS => GenerateStatus::SUCCESS,
                    GenerateStatus::DATETIME => current_time( 'mysql' ),
                    GenerateStatus::SPEND_TIME => GenerateStatus::formatDateDiff( date( GenerateStatus::DATETIME_FORMAT, $this->timer_start ) ),
                ]
            )
        );
    }


    /**
     * @param $error_message
     */
    private function setErrorStatus( $error_message )
    {
        GenerateStatus::set(
            json_encode(
                [
                    GenerateStatus::STATUS => GenerateStatus::ERROR,
                    GenerateStatus::DATETIME => date( GenerateStatus::DATETIME_FORMAT ),
                    GenerateStatus::SPEND_TIME => GenerateStatus::formatDateDiff( date( GenerateStatus::DATETIME_FORMAT, $this->timer_start) ),
                    GenerateStatus::ERROR_MESSAGE => $error_message,
                ]
            )
        );
    }
}