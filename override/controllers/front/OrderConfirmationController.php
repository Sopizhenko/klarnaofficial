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

class OrderConfirmationController extends OrderConfirmationControllerCore
{
    /**
    * Initialize order confirmation controller
    * @see FrontController::init() 
    */
    public function init()
    {
        $id_cart = (int)(Tools::getValue('id_cart', 0));
        $id_order = Order::getOrderByCartId((int)($id_cart));
        $secure_key = Tools::getValue('key', false);
        $order = new Order((int)($id_order));
        if ($order->module=='klarnaofficial') {
            $customer = new Customer((int) $order->id_customer);
            if ($customer->secure_key == $secure_key) {
                $this->context->customer = $customer;
            }
        }
        parent::init();
    }
}
