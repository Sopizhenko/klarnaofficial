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
        require_once dirname(__FILE__).'/../../libraries/KCOUK/autoload.php';
        $klarnadata = Tools::file_get_contents('php://input');
        $klarna_result = json_decode($klarnadata, true);
        $order_id = pSQL($klarna_result["order_id"]);
        
        $eid = Configuration::get('KCOV3_MID');
        $shared_secret = Configuration::get('KCOV3_SECRET');

        if ((int) Configuration::get('KCO_TESTMODE') == 1) {
            $connector = \Klarna\Rest\Transport\Connector::create(
                $eid,
                $shared_secret,
                \Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
            );
        } else {
            $connector = \Klarna\Rest\Transport\Connector::create(
                $eid,
                $shared_secret,
                \Klarna\Rest\Transport\ConnectorInterface::EU_BASE_URL
            );
        }
        
        $order = new Klarna\Rest\OrderManagement\Order($connector, $order_id);
        $order->fetch();
        $new_pending_status = 0;
        if (isset($order['fraud_status']) && $order['fraud_status'] != "PENDING") {
            if ($order['fraud_status'] == "ACCEPTED") {
                $new_pending_status = Configuration::get('KCO_PENDING_PAYMENT_ACCEPTED');
            } elseif ($order['fraud_status'] == "REJECTED") {
                $new_pending_status = Configuration::get('KCO_PENDING_PAYMENT_REJECTED');
            }
            
            if ($new_pending_status > 0) {
                $sql = "SELECT eid, id_shop, id_order FROM `"._DB_PREFIX_.
                        "klarna_orders` WHERE `reservation`='$order_id'";
                $row = Db::getInstance()->getRow($sql);
                
                $id_order = (int) $row["id_order"];
                $id_shop = (int) $row["id_shop"];
                if (0 == (int) $id_order) {
                    /*RESERVATION NOT FOUND IN SYSTEM, KILL PROCESS*/
                    exit;
                }
                if (0 == (int) $id_shop) {
                    /*ID SHOP NOT FOUND IN SYSTEM, KILL PROCESS*/
                    exit;
                }
                
                $history = new OrderHistory();
                $history->id_order = $id_order;
                $history->changeIdOrderState((int)$new_pending_status, $id_order, true);
                $templateVars = array();
                $history->addWithemail(true, $templateVars);
            }
        }
        exit;
    }
}
