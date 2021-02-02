<?php
/**
 * 2015 Prestaworks AB.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@prestaworks.se so we can send you a copy immediately.
 *
 *  @author    Prestaworks AB <info@prestaworks.se>
 *  @copyright 2015 Prestaworks AB
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of Prestaworks AB
 */

function upgrade_module_1_10_6($module)
{
    $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'klarna_osm_configurations` (
            `id_klarna_osm_configurations` INT(11) NOT NULL AUTO_INCREMENT,
            `id_country` INT(11) NOT NULL,
            `product_page` VARCHAR(255) NOT NULL,
            `product_page_theme` VARCHAR(255) NOT NULL,
            `cart_page` VARCHAR(255) NOT NULL,
            `cart_page_theme` VARCHAR(255) NOT NULL,
            `id_shop` INT(11) NOT NULL,
            `active` TINYINT(1) NOT NULL,
            PRIMARY KEY  (`id_klarna_osm_configurations`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

    if (Db::getInstance()->execute($sql) == false) {
        return false;
    }

    $module->installTabs();
    return true;
}
