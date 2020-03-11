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

function upgrade_module_2_1_24($module)
{
    $sql = "SELECT * FROM `"._DB_PREFIX_."configuration` WHERE `name`='KCO_CANCEL_STATE'";
    $cancel_states = Db::getInstance()->executeS($sql);
   
    foreach ($cancel_states as $cancel_state) {
        $old_value = (int) $cancel_state["value"];
        $id_configuration = (int) $cancel_state["id_configuration"];
        $to_save = array();
        $to_save[] = $old_value;
        $new_value = Tools::jsonEncode($to_save);
        $update_sql = "UPDATE `"._DB_PREFIX_."configuration` ".
            "SET `value`='$new_value' WHERE `id_configuration`=".$id_configuration;
        Db::getInstance()->execute($update_sql);
    }
   
    $sql = "SELECT * FROM  `"._DB_PREFIX_."configuration` WHERE `name`='KCO_ACTIVATE_STATE'";
    $activate_states = Db::getInstance()->executeS($sql);
   
    foreach ($activate_states as $activate_state) {
        $old_value = (int) $activate_state["value"];
        $id_configuration = (int) $activate_state["id_configuration"];
        $to_save = array();
        $to_save[] = $old_value;
        $new_value = Tools::jsonEncode($to_save);
        $update_sql = "UPDATE `"._DB_PREFIX_."configuration` ".
            "SET `value`='$new_value' WHERE `id_configuration`=".$id_configuration;
        Db::getInstance()->execute($update_sql);
    }
    $module->registerHook('displayShoppingCart');
    return true;
}
