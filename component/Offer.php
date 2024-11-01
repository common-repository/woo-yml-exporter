<?php


/**
 * Created by PhpStorm.
 * User: maksim
 * Date: 21.07.17
 * Time: 15:16
 */
class Offer
{
    const YML_EXPORT_VENDOR_FIELD = 'yml_export_vendor_field';
    const YML_EXPORT_PICKUP_FIELD = 'yml_export_pickup_field';
    const YML_EXPORT_MODEL_FIELD = 'yml_export_model_field';
    /**
     * @var WP_Post
     */
    private $product;

    /**
     * @var WC_Product
     */
    private $wc_product;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $vendor_field;

    /**
     * @var string
     */
    private $pickup_field;

    /**
     * @var string
     */
    private $model_field;


    /**
     * Offer constructor.
     *
     * @param WP_Post $post
     * @param $currency
     */
    function __construct( WP_Post $post, $currency)
    {
        $this->product = $post;
        $this->wc_product = wc_get_product( $post->ID );
        $this->currency = $currency;
        $this->vendor_field = get_site_option( self::YML_EXPORT_VENDOR_FIELD );
        $this->pickup_field = get_site_option( self::YML_EXPORT_PICKUP_FIELD );
        $this->model_field = get_site_option( self::YML_EXPORT_MODEL_FIELD );
    }


    /**
     * @return string
     */
    function __toString()
    {
        return $this->getOfferString();
    }


    /**
     * @return string
     */
    function getOfferString()
    {
        // Header
        $offer_string = $this->getHeader();

        // Url
        $offer_string .= $this->getUrl();

        // Price
        $offer_string .= $this->getPrice();

        // Sale Price
        $offer_string .= $this->getSalePrice();

        // CurrencyId
        $offer_string .= $this->getCurrencyId();

        // CategoryId
        $offer_string .= $this->getCategoryId();

        // Picture
        $offer_string .= $this->getPicture();

        // Pickup
        $offer_string .= $this->getPickup();

        // Delivery
        $offer_string .= $this->getDelivery();

        // Name
        $offer_string .= $this->getName();

        // Description
        $offer_string .= $this->getDescription();

        // vendorCode
        $offer_string .= $this->getVendorCode();

        // weight
        $offer_string .= $this->getWeight();

        // store
        $offer_string .= $this->getStoreField();

        // sales_notes
        $offer_string .= $this->getSalesNotes();

        // vendor
        $offer_string .= $this->getVendor();

        // model
        $offer_string .= $this->getModel();

        // params
        $offer_string .= $this->getParams();

        // Bottom
        $offer_string .= "</offer>\n";



        return $offer_string;
    }


    /**
     * @return string
     */
    private function getHeader()
    {
        $available = ( $this->product->post_status == 'publish' ) ? 'true' : 'false';
        $offer_string = "<offer id=\"{$this->product->ID}\" available=\"{$available}\">\n";

        return $offer_string;
    }


    /**
     * @return string
     */
    private function getUrl()
    {
        return "<url>{$this->wc_product->get_permalink()}</url>\n";
    }


    /**
     * @return string
     */
    private function getPrice()
    {
        return "<price>{$this->wc_product->get_regular_price()}</price>\n";
    }


    /**
     * @return string
     */
    private function getSalePrice()
    {
        return "<sale_price>{$this->wc_product->get_sale_price()}</sale_price>\n";
    }


    /**
     * @return string
     */
    private function getCurrencyId()
    {
        return "<currencyId>{$this->currency}</currencyId>\n";
    }


    /**
     * @return string
     */
    private function getCategoryId()
    {
        $category_string = '';
        $category_list = $this->wc_product->get_category_ids();

        if( is_array( $category_list ) and ! empty( $category_list ) ) {
            $category = (string)reset( $category_list );
            $category_string = "<categoryId>{$category}</categoryId>\n";
        }


        return $category_string;
    }


    /**
     * @return null|string
     */
    private function getPicture()
    {
        $image_html = $this->wc_product->get_image();
        $picture = '';
        if( ! empty($image_html)){
            $image_html = trim($image_html, "<>");
            $image_html_parts = explode( ' ', $image_html );
            $tag_parsed = [];

            foreach ( $image_html_parts as $item ) {
                $parts = explode('=', $item);
                $attribute = array_shift($parts);
                $value = array_shift($parts);
                $tag_parsed[$attribute] = $value;
            }


            if( array_key_exists( 'src', $tag_parsed ) and $image_url = $tag_parsed['src'] ){
                $picture = "<picture>{$image_url}</picture>\n";
            }
        }


        return $picture;
    }


    /**
     * @return string
     */
    private function getPickup()
    {
        $pickup_string = '';
        if( $pickup = $this->wc_product->get_attribute( $this->vendor_field ) ){
            $pickup_string = "<pickup>$pickup</pickup>\n";
        }

        return $pickup_string;
    }


    /**
     * @return string
     */
    private function getDelivery()
    {
        $delivery_string = '';
        if($delivery = $this->wc_product->get_shipping_class()){
            $delivery_string = "<delivery>$delivery</delivery>\n";
        }

        return $delivery_string;
    }


    /**
     * @return string
     */
    private function getName()
    {
        return "<name>{$this->wc_product->get_name()}</name>\n";
    }


    /**
     * @return string
     */
    private function getDescription()
    {
        return "<description><![CDATA[{$this->wc_product->get_description()}]]></description>\n";
    }


    /**
     * @return string
     */
    private function getWeight()
    {
        return "<weight>{$this->wc_product->get_weight()}</weight>\n";
    }


    /**
     * @return string
     */
    private function getStoreField()
    {
        $available = ( $this->product->post_status == 'publish' ) ? 'true' : 'false';

        return "<store>{$available}</store>\n";
    }


    /**
     * @return string
     */
    private function getSalesNotes()
    {
        $sales_notes_string = '';
        if( $sales_notes = $this->wc_product->get_purchase_note() ){
            $sales_notes_string = "<sales_notes>$sales_notes</sales_notes>\n";
        }

        return $sales_notes_string;
    }


    /**
     * @return string
     */
    private function getVendorCode()
    {
        return "<vendorCode>{$this->wc_product->get_sku()}</vendorCode>\n";
    }


    /**
     * @return string
     */
    private function getVendor()
    {
        $vendor_string = '';
        if( $vendor = $this->wc_product->get_attribute( $this->vendor_field ) ){
            $vendor_string = "<vendor>$vendor</vendor>\n";
        }

        return $vendor_string;
    }


    /**
     * @return string
     */
    private function getModel()
    {
        $model_string = '';
        if( $model = $this->wc_product->get_attribute( $this->model_field ) ){
            $model_string = "<model>$model</model>\n";
        }

        return $model_string;
    }


    private function getParams()
    {
        $params_string = '';
        $offer_attributes = $this->wc_product->get_attributes();
        $offer_attribute_names = array_keys( $offer_attributes );

        $offer_attribute_names = array_map( function($item){
            return str_replace('pa_', '', $item);
        }, $offer_attribute_names );

        $left_offer_attribute_names = array_diff( $offer_attribute_names, $this->getPredefinedParams() );

        foreach ( $left_offer_attribute_names as $left_offer_attribute_name ) {
            $attribute_value = $this->wc_product->get_attribute( $left_offer_attribute_name );
            $params_string .= "<param name=\"{$left_offer_attribute_name}\" value=\"{$attribute_value}\" />\n";
        }


        return $params_string;
    }


    /**
     * Получить массив полей товара, которые применяются для фиксированный полей
     * например для vendor или model
     * @return array
     */
    private function getPredefinedParams()
    {
        $available_fixed_fields = [
            'vendor_field',
            'pickup_field',
            'model_field',
        ];
        $predefined_params = [];

        foreach ( $available_fixed_fields as $available_fixed_field ) {
            if(!empty($this->$available_fixed_field)){
                $predefined_params[] = $this->$available_fixed_field;
            }
        }


        return $predefined_params;
    }
}