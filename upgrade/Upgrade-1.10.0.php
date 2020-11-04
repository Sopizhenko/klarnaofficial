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

function upgrade_module_1_10_0($module)
{
    $kpm_settings = array('KCO_SWEDEN',
        'KCO_SWEDEN_B2B',
        'KCO_SWEDEN_EID',
        'KCO_SWEDEN_SECRET',
        'KCO_NORWAY',
        'KCO_NORWAY_EID',
        'KCO_NORWAY_SECRET',
        'KCO_NORWAY_B2B',
        'KCO_FINLAND',
        'KCO_FINLAND_EID',
        'KCO_FINLAND_SECRET',
        'KCO_FINLAND_B2B',
        'KCO_GERMANY_EID',
        'KCO_GERMANY_SECRET',
        'KCO_AUSTRIA',
        'KCOV3_AUSTRIA',
        'KCO_AUSTRIA_EID',
        'KCO_AUSTRIA_SECRET',
        'KCO_GLOBAL_SECRET',
        'KCO_GLOBAL_EID',
        'KCO_GLOBAL',
        'KCO_GERMANY',
        'KCO_DE_PREFILNOT',
        'KCOV3_FINLAND',
        'KPM_INVOICEFEE',
        'KPM_NL_EID',
        'KPM_NL_SECRET',
        'KPM_UK_EID',
        'KPM_SV_EID',
        'KPM_SV_SECRET',
        'KPM_NO_EID',
        'KPM_NO_SECRET',
        'KPM_FI_EID',
        'KPM_FI_SECRET',
        'KPM_DE_EID',
        'KPM_DE_SECRET',
        'KPM_DA_EID',
        'KPM_DA_SECRET',
        'KPM_AT_EID',
        'KPM_AT_SECRET',
        'KCO_SHOWPRODUCTPAGE',
        'KCO_PRODUCTPAGELAYOUT',
        'KCO_FOOTERLAYOUT',
        'KCO_SENDTYPE',
        'KCO_UK_EID',
        'KCO_UK_SECRET',
        'KCO_NL',
        'KCO_NL_SECRET',
        'KCO_NL_EID',
        'KCO_US_SECRET',
        'KCO_US_EID',
        'KCO_US',
        'KCO_UK',
        'KCO_IS_ACTIVE'
    );
    foreach ($kpm_settings as $kpm_setting) {
        @Configuration::deleteByName($kpm_setting);
    }

    try {
        /*REMOVE kpm_payment_return tpl*/
        unlink(dirname(__FILE__). '/../views/templates/hook/kpm_payment_return.tpl');
    } catch (Exception $e) {
        /*REMOVAL IS NOT ESSENTIAL*/
    }

    try {
        /*REMOVE klarnaproductpage tpl*/
        unlink(dirname(__FILE__). '/../views/templates/hook/klarnaproductpage.tpl');
    } catch (Exception $e) {
        /*REMOVAL IS NOT ESSENTIAL*/
    }
    
    /*TRY TO REMOVE UNWANTED FILES*/
    try {
        rrmdir(dirname(__FILE__). '/../libraries/lib');
    } catch (Exception $e) {
        /*REMOVAL IS NOT ESSENTIAL*/
        return true;
    }
    try {
        rrmdir(dirname(__FILE__). '/../libraries/Checkout');
    } catch (Exception $e) {
        /*REMOVAL IS NOT ESSENTIAL*/
        return true;
    }
    
    $sql = "SELECT `id_hook` FROM `"._DB_PREFIX_."hook` WHERE `name` = 'displayFooter'";
    $id_hook = Db::getInstance()->getValue($sql);
    if ((int)$id_hook) {
        $module->unregisterHook($id_hook);
    }
    return true;
}

function rrmdir($dir)
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object)) {
                    rrmdir($dir. DIRECTORY_SEPARATOR .$object);
                } else {
                    unlink($dir. DIRECTORY_SEPARATOR .$object);
                }
            }
        }
        rmdir($dir);
    }
}
