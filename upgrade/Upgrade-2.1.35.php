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

function upgrade_module_2_1_35($module)
{
    $kpm_settings = array('KPM_ACCEPTED_PP',
        'KPM_SHOW_IN_PAYMENTS',
        'KPM_DISABLE_INVOICE',
        'KPM_ACCEPTED_INVOICE',
        'KPM_PENDING_PP',
        'KPM_PENDING_INVOICE',
        'KPM_LOGO',
        'KPM_AT_SECRET',
        'KPM_AT_EID',
        'KPM_INVOICEFEE',
        'KPM_NL_EID',
        'KPM_NL_SECRET',
        'KPM_DE_SECRET',
        'KPM_DE_EID',
        'KPM_DA_SECRET',
        'KPM_DA_EID',
        'KPM_FI_SECRET',
        'KPM_FI_EID',
        'KPM_NO_SECRET',
        'KPM_NO_EID',
        'KPM_SV_SECRET',
        'KPM_SV_EID'
    );
    foreach ($kpm_settings as $kpm_setting) {
        @Configuration::deleteByName($kpm_setting);
    }
    $sql = array();
    $sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'kpmpclasses`';
    foreach ($sql as $query) {
        @Db::getInstance()->execute($query);
    }
    
    try {
        /*REMOVE KPM*/
        unlink(dirname(__FILE__). '/../views/templates/hook/kpm_payment.tpl');
    } catch (Exception $e) {
        /*REMOVAL IS NOT ESSENTIAL*/
    }
    try {
        /*REMOVE KPM*/
        unlink(dirname(__FILE__). '/../views/templates/hook/kpm_payment_return.tpl');
    } catch (Exception $e) {
        /*REMOVAL IS NOT ESSENTIAL*/
    }
    try {
        /*REMOVE KPM*/
        unlink(dirname(__FILE__). '/../views/templates/front/kpm_partpayment.tpl');
    } catch (Exception $e) {
        /*REMOVAL IS NOT ESSENTIAL*/
    }
    try {
        /*REMOVE KPM*/
        unlink(dirname(__FILE__). '/../views/templates/front/kpm_notavailable.tpl');
    } catch (Exception $e) {
        /*REMOVAL IS NOT ESSENTIAL*/
    }
    try {
        /*REMOVE KPM*/
        unlink(dirname(__FILE__). '/../views/js/kpm_common.js');
    } catch (Exception $e) {
        /*REMOVAL IS NOT ESSENTIAL*/
    }
    try {
        /*REMOVE KPM*/
        unlink(dirname(__FILE__). '/../views/css/kpm_common.css');
    } catch (Exception $e) {
        /*REMOVAL IS NOT ESSENTIAL*/
    }
    try {
        /*REMOVE KPM*/
        unlink(dirname(__FILE__). '/../views/css/kpm_css.css');
    } catch (Exception $e) {
        /*REMOVAL IS NOT ESSENTIAL*/
    }
    try {
        /*REMOVE KPM*/
        unlink(dirname(__FILE__). '/../controllers/front/klarnapp.php___');
    } catch (Exception $e) {
        /*REMOVAL IS NOT ESSENTIAL*/
    }
    try {
        /*REMOVE KPM*/
        unlink(dirname(__FILE__). '/../controllers/front/kpmgetaddress.php');
    } catch (Exception $e) {
        /*REMOVAL IS NOT ESSENTIAL*/
    }
    try {
        /*REMOVE KPM*/
        unlink(dirname(__FILE__). '/../controllers/front/kpmpartpayment.php');
    } catch (Exception $e) {
        /*REMOVAL IS NOT ESSENTIAL*/
    }
    return true;
}
