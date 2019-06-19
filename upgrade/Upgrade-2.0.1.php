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

function upgrade_module_2_0_1($module)
{
    $result = true;
    $module->registerHook('displayOrderConfirmation');
    
    $sql = "SELECT `id_hook` FROM `"._DB_PREFIX_."hook` WHERE `name` = 'displayPaymentReturn'";
    $id_hook = Db::getInstance()->getValue($sql);
    if ((int)$id_hook) {
        $module->unregisterHook($id_hook);
    }
    
    return $result;
}
