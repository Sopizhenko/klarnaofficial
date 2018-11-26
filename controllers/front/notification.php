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
 
class KlarnaOfficialNotificationModuleFrontController extends ModuleFrontController
{
    public $display_column_left = false;
    public $display_column_right = false;
    public $ssl = true;

    public function postProcess()
    {
        $klarnadata = Tools::file_get_contents('php://input');
        
        $klarna_result = json_decode($klarnadata, true);
        // $occurred_at = $klarna_result["occurred_at"];
        $event_type = $klarna_result["event_type"];
        $order_id = $klarna_result["order_id"];
        
        $sql = "SELECT id_cart, id_order FROM `"._DB_PREFIX_.
                        "klarna_orders` WHERE `reservation`='$order_id'";
        $row = Db::getInstance()->getRow($sql);
        $id_order = 0;
        
        if (0 == (int)$row["id_order"]) {
            /*NO ORDER FOUND, CHECK IF CART HAS ORDER*/
            if (0 == (int)$row["id_cart"]) {
                /*NO CART FOUND, KILL PROCESS*/
                exit;
            } else {
                $sql = 'SELECT `id_order` FROM '._DB_PREFIX_.'orders WHERE `id_cart` = '.(int)$row["id_cart"];
                $id_order = Db::getInstance()->getValue($sql);
            }
        } else {
            $id_order = (int)$row["id_order"];
        }
        
        if (0 == (int)$id_order) {
            /*RESERVATION NOT FOUND IN SYSTEM, KILL PROCESS*/
            exit;
        }
        
        $new_pending_status = 0;
        if ("FRAUD_RISK_REJECTED" == $event_type) {
            $new_pending_status = Configuration::get('PS_OS_ERROR', _PS_OS_ERROR_);
        } elseif ("FRAUD_RISK_ACCEPTED" == $event_type) {
            $new_pending_status = Configuration::get('KCO_PENDING_PAYMENT_ACCEPTED');
        } elseif ("FRAUD_RISK_STOPPED" == $event_type) {
            $new_pending_status = Configuration::get('KCO_PENDING_PAYMENT');
        }

        if (0 != (int)$id_order && 0 != (int)$new_pending_status) {
            $history = new OrderHistory();
            $history->id_order = $id_order;
            $history->changeIdOrderState((int)$new_pending_status, $id_order, true);
            $templateVars = array();
            $history->addWithemail(true, $templateVars); 
        }
    }
}
