<?php
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
        if($order->module=='klarnaofficial') {
            $customer = new Customer((int) $order->id_customer);
            if ($customer->secure_key == $secure_key) {
                $this->context->customer = $customer;
            }
        }
		parent::init();
	}
}