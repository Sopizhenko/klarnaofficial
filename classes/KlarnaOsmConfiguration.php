<?php
/**
* Prestaworks AB
*
* NOTICE OF LICENSE
*
* This source file is subject to the End User License Agreement(EULA)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://license.prestaworks.se/license.html
*
* @author Prestaworks AB <info@prestaworks.se>
* @copyright Copyright Prestaworks AB (https://www.prestaworks.se/)
* @license http://license.prestaworks.se/license.html
*/

class KlarnaOsmConfiguration extends ObjectModel
{
    public $id;

    public $id_country;

    public $product_page;

    public $product_page_theme;

    public $cart_page;

    public $cart_page_theme;

    public $id_shop;

    public $active;

    public static $definition = array(
        'table' => 'klarna_osm_configurations',
        'primary' => 'id_klarna_osm_configurations',
        // 'multilang_shop' => true,
        // 'multishop' => true,
        'fields' => array(
            'id_country' => array(
                'type' => self::TYPE_INT
            ),
            'product_page' => array(
                'type' => self::TYPE_STRING
            ),
            'product_page_theme' => array(
                'type' => self::TYPE_STRING
            ),
            'cart_page' => array(
                'type' => self::TYPE_STRING
            ),
            'cart_page_theme' => array(
                'type' => self::TYPE_STRING
            ),
            'id_shop' => array(
                'type' => self::TYPE_INT
            ),
            'active' => array(
                'type' => self::TYPE_BOOL
            ),
        ),
    );

    public function add($auto_date = true, $null_values = false)
    {
        $this->id_shop = Context::getContext()->shop->id;

        return parent::add($auto_date, $null_values);
    }

    public function save($null_values = false, $auto_date = true)
    {
        $this->id_shop = Context::getContext()->shop->id;
        
        return parent::add($null_values, $auto_date);
    }

    public static function getByCountry($countryId)
    {
        $sql = 'SELECT * FROM '._DB_PREFIX_.self::$definition['table'].' WHERE id_country = '.(int) $countryId.' AND active = 1 AND id_shop = '.(int) Context::getContext()->shop->id;

        return Db::getInstance()->getRow($sql);
    }
}
