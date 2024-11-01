<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://laputa.seomarket.ua
 * @since      1.0.0
 *
 * @package    Yml_Exporter
 * @subpackage Yml_Exporter/admin/partials
 */
require_once YML_EXPORTER_PLUGIN_DIR . '/component/GenerateStatus.php';
require_once YML_EXPORTER_PLUGIN_DIR . '/component/Xml.php';
?>
<h1>Yml Exporter</h1>
<p>Экспортировать товары магазина в локальный yml-файл</p>

<hr>
<p>Статус генерации: <?= GenerateStatus::get() ?>.</p>
<p>Статус плагина WooCommerce: <?= GenerateStatus::getWooPluginStatus(); ?></p>
<p>Статус плагина Транслитерации(CyrToLat): <?= GenerateStatus::getTransliteratePluginStatus(); ?></p>

<div id="postbox-container-1" class="postbox-container">
    <div class="meta-box-sortables">
        <div class="postbox">
            <div class="inside">
                <form action="<?= $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
                    <p><label for="yfym_run_cron">Настройка соответствия полей:</label><hr/>

                    <p>
                        <label for="yml_pickup">Возможность самовывоза:</label><br/>
                        <select id="yml_pickup" name="<?= Offer::YML_EXPORT_PICKUP_FIELD?>">
                            <?php $pickup = get_option( Offer::YML_EXPORT_PICKUP_FIELD ); ?>
                            <option value="true" <?php selected( $pickup, 'true' ); ?>>Возможен</option>
                            <option value="false" <?php selected( $pickup, 'false' ); ?>>Недоступен</option>
                        </select>
                    </p>

                    <p>
                        <label for="yml_model">Model</label><br/>
                        <select id="yml_model" name="<?= Offer::YML_EXPORT_MODEL_FIELD?>">
                            <?php $model = get_option( Offer::YML_EXPORT_MODEL_FIELD ); ?>
                            <option
                                value="off"
                                <?php selected( $model, 'none' ); ?>>none</option>
                            <?php
                            foreach ( wc_get_attribute_taxonomies() as $attribute ) : ?>
                                <option
                                    value="<?= $attribute->attribute_name; ?>"
                                    <?php selected( $model, $attribute->attribute_name ); ?>
                                ><?= "{$attribute->attribute_label} ({$attribute->attribute_name})"; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>

                    <p>
                        <label for="yml_vendor">Vendor</label><br/>
                        <select id="yml_vendor" name="<?= Offer::YML_EXPORT_VENDOR_FIELD?>">
                            <?php $vendor = get_option( Offer::YML_EXPORT_VENDOR_FIELD ); ?>
                            <option value="off" <?php selected( $vendor, 'none' ); ?>>none</option>
                            <?php
                            foreach ( wc_get_attribute_taxonomies() as $attribute ) : ?>
                                <option
                                        value="<?= $attribute->attribute_name; ?>"
                                    <?php selected( $vendor, $attribute->attribute_name ); ?>
                                ><?= "{$attribute->attribute_label} ({$attribute->attribute_name})"; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </p>

                    <?php wp_nonce_field( Yml_Exporter_Admin::YML_NONCE_ACTION, Yml_Exporter_Admin::YML_NONCE_FIELD ); ?>
                    <p><input class="button-primary" type="submit" name="<?= Yml_Exporter_Admin::YML_SUBMIT_ACTION ?>"
                              value="Сохранить"/></p>
                </form>
            </div>
        </div>
    </div>
</div>



