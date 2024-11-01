<?php
/**
 * Created by PhpStorm.
 * User: maksim
 * Date: 25.07.17
 * Time: 14:27
 */
require_once YML_EXPORTER_PLUGIN_DIR . '/component/GenerateStatus.php';
?>

<h1>Yml Exporter</h1>
<p>Экспортировать товары магазина в локальный yml-файл</p>

<strong>Статус плагина WooCommerce: <?= GenerateStatus::getWooPluginStatus(); ?></strong>