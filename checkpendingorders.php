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

$klarnaofficial = new KlarnaOfficial();

$cron_token = Tools::hash(Tools::hash(Tools::hash($klarnaofficial->name)));
$entered_token = Tools::getValue("cron_token");
if ($cron_token != $entered_token) {
    exit;
}
$shops = Shop::getShops(true, null, true);

$risk_status = $klarnaofficial->Pending_risk;
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
