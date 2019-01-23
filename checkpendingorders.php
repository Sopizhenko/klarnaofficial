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

require_once(dirname(__FILE__). '/../../config/config.inc.php');
require_once(_PS_ROOT_DIR_.'/init.php');
require_once(dirname(__FILE__).'/klarnaofficial.php');
require_once dirname(__FILE__).'/libraries/KCOUK/autoload.php';
$klarnaofficial = new KlarnaOfficial();

$cron_token = Tools::hash(Tools::hash(Tools::hash($klarnaofficial->name)));
$entered_token = Tools::getValue("cron_token");
if ($cron_token != $entered_token) {
    exit;
}


$order_status = (int)Configuration::get('KCO_PENDING_PAYMENT');
$sql = "SELECT id_order, id_shop FROM "._DB_PREFIX_."orders WHERE current_state=$order_status;";
$result = Db::getInstance()->executeS($sql);

foreach ($result as $row) {
    try {
        $id_order = (int)$row["id_order"];
        $id_shop = (int)$row["id_shop"];
        echo "CHECKING ORDER $id_order<br>";
        $sql = "SELECT eid, reservation FROM "._DB_PREFIX_."klarna_orders WHERE id_order='$id_order';";
        $row_date = Db::getInstance()->getRow($sql);
        $eid = $row_date["eid"];
        $reservation = $row_date["reservation"];
        $eid_ss_comb = $klarnaofficial->getAllEIDSScombinations($id_shop);
        $shared_secret = $eid_ss_comb[$eid];


        if ((int) (Configuration::get('KCO_TESTMODE', null, null, $id_shop)) == 1) {
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

        $order = new Klarna\Rest\OrderManagement\Order($connector, $reservation);
        $order->fetch();

        if (isset($order['fraud_status']) && $order['fraud_status'] != "PENDING") {
            if ($order['fraud_status'] == "ACCEPTED") {
                $new_pending_status = Configuration::get('KCO_PENDING_PAYMENT_ACCEPTED');
            } elseif ($order['fraud_status'] == "REJECTED") {
                $new_pending_status = Configuration::get('KCO_PENDING_PAYMENT_REJECTED');
            }
            
            $history = new OrderHistory();
            $history->id_order = $id_order;
            $history->changeIdOrderState((int)$new_pending_status, $id_order, true);
            $templateVars = array();
            $history->addWithemail(true, $templateVars);
        }
    } catch (Exception $e) {
        $msg = "Check pending: $id_order " . $e->getMessage();
        Logger::addLog($msg, 1, null, 'klarnaofficial', $id_order, true);
    }
}

/*KLARNA KPM PENDING*/
$risk_status = pSQL($klarnaofficial->Pending_risk);
$sql = "SELECT id_order FROM "._DB_PREFIX_."klarna_orders WHERE risk_status='$risk_status';";

$result = Db::getInstance()->executeS($sql);

foreach ($result as $row) {
    $id_order = (int)$row["id_order"];
    echo "CHECKING ORDER $id_order";
    try {
        $klarnaofficial->checkPendingStatus($id_order, true);
    } catch (Exception $e) {
        $msg = "Check pending: $id_order " . $e->getMessage();
        Logger::addLog($msg, 1, null, 'klarnaofficial', $id_order, true);
    }
}
echo "<br />Done checking orders";
