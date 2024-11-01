=== WooCommerce Yml Exporter ===
Contributors: zinchenkomax
Donate link: https://laputa.seomarket.ua/
Tags: xml, yml, yandex market, export, feed, adwords, context, ad, woocommerce
Requires at least: 3.0.1
Tested up to: 4.8
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Export WooCommerce store products to local xml-file(yml) accessible by http.

== Description ==

    Context advertising for today remains one of the main source of sales for most online stores.
 To publish contextual advertising, you often need a file with a list of goods online store.
 The most popular for this purpose is the xml format. It is also necessary that this file is
 available for downloading from the Internet. In order for the information about the products
 in the xml-file to be up-to-date, it is necessary to regenerate this file periodically.
 Then when you change the prices of goods or their availability, information about them will
  be relevant in the xml-file.

    This file allows you to configure the so-called commodity and category contextual advertising.
A Shopping Ad is an ad that advertises a particular product. Accordingly, a category
advertisement advertises a separate category. And if you have ads for each product separately,
then the horizon opens for a sharp increase in the cost effectiveness of advertising.
The effectiveness is related to the cost of the advertising click and the conversion landing page. But this is a separate story.
Out of the box, WooCommerce does not have the ability to export all available products
to an xml file.

    The WooCommerce Yml Export plugin fills this gap. It allows you to create an xml-file with
all the goods of the online store twice a day and save it in the uploads folder available
from the Internet under the link http://your-domain.com/wp-content/uploads/feed-yml.xml.
In the future, this link can be used to publish contextual advertising with special tools.
 For example, such as Laputa (https://laputa.seomarket.ua).

    There is an embedded version of Laputa, it can be used with
the WooCommerce Build-in Laputa plug-in.


    Контекстная реклама на сегодня остается одним из основных источником продаж для большинства
интернет-магазинов. Для публикации контекстной рекламы зачастую требуется файл со списком
товаров интернет-магазина. Наиболее популярным для этой цели является формат xml.
Так же необходимо, чтобы этот файл был доступен для скачивания из Интернета. Чтобы информация
о товарах в xml-файле была актуальной, необходимо периодически по расписанию формировать этот
файл. Тогда при изменении цен товаров или их наличия, информация о них будет актуальна в в
xml-файле.

    Такой файл позволяет настроить так называемую товарную и категорийную контекстную рекламу.
Товарное объявление — это объявление, которое рекламирует отдельный товар. Соответственно
категорийное объявление рекламирует отдельную категорию. И если у вас есть объявления отдельно
для каждого товара, то открывается горизонт для резкого повышения эффективности затрат на рекламу. Эффективность связана со стоимостью рекламного клика и конверсионностью посадочной странице. Но это уже отдельная история.
Из коробки WooCommerce не имеет возможности экспортировать все имеющиеся товары в xml-файл.

    Плагин WooCommerce Yml Export восполняет этот пробел. Он позволяет дважды в сутки формировать
 xml-файл со всеми товарами интернет-магазина и сохранять его в папке uploads, доступной
 из интернета по ссылке http://your-domain.com/wp-content/uploads/feed-yml.xml.

    В дальнейшем эта ссылка может быть использована для публикации контекстной рекламы
специальными инструментами. Например, такими как Лапута (https://laputa.seomarket.ua).

    Существует встраиваемая версия Лапуты, её можно использовать
с помощь плагина WooCommerce Build-in Laputa.

== Installation ==

1. Upload `yml-exporter.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Adjust the conformity of product fields and fields of xml-feed


== Screenshots ==

1. Main plugin screen
2. Screen with disabled WooCommerce. WooCommerce is required.

== Changelog ==

= 1.0 =
Init
