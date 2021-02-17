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
 
class KlarnaOfficialCallbackValidationModuleFrontController extends ModuleFrontController
{
    public $display_column_left = false;
    public $display_column_right = false;
    public $ssl = true;

    public function init()
    {
        parent::init();
        $klarnadata = Tools::file_get_contents('php://input');
        $klarnaorder = Tools::jsonDecode($klarnadata, true);
        

        if (isset($klarnaorder["merchant_reference2"])) {
            //This is a KCO V3 ORDER
            //Convert Data
            $klarnaorder["merchant_reference"]["orderid2"] = $klarnaorder["merchant_reference2"];
            $klarnaorder["cart"]["items"] = $klarnaorder["order_lines"];
        }
        //DO THE CHECKS ON THE CART
        if (isset($klarnaorder["merchant_reference"]["orderid2"])) {
            $id_cart = (int)$klarnaorder["merchant_reference"]["orderid2"];
            if ($id_cart > 0) {
                $cart = new Cart($id_cart);
                $this->context->cart = $cart;
                if (Validate::isLoadedObject($cart) && $cart->OrderExists() == false) {
                    //Check stock
                    if (!$cart->checkQuantities()) {
                        $this->redirectKCO();
                    }
                    if ($cart->nbProducts() < 1) {
                        $this->redirectKCO();
                    }
                    if (isset($klarnaorder['order_amount'])) {
                        $totalCartValue = $this->context->cart->getOrderTotal(true, Cart::BOTH);
                        $order_amount = $klarnaorder['order_amount'];
                        $order_amount = $order_amount / 100;
                        if ($order_amount != $totalCartValue) {
                            $this->redirectKCO();
                        }
                    }
                    
                    require_once dirname(__FILE__).'/../../libraries/commonFeatures.php';
                    $KlarnaCheckoutCommonFeatures = new KlarnaCheckoutCommonFeatures();
                    
                    $language = new Language($this->context->cart->id_lang);
                    if (isset($this->module->shippingreferences[$language->iso_code])) {
                        $shippingReference = $this->module->shippingreferences[$language->iso_code];
                    } else {
                        $shippingReference = $this->module->shippingreferences['en'];
                    }
                    if (isset($this->module->wrappingreferences[$language->iso_code])) {
                        $wrappingreference = $this->module->wrappingreferences[$language->iso_code];
                    } else {
                        $wrappingreference = $this->module->wrappingreferences['en'];
                    }
                    
                    $checkoutcart = $KlarnaCheckoutCommonFeatures->BuildCartArray(
                        $this->context->cart,
                        $shippingReference,
                        $wrappingreference,
                        $this->module->getL('Inslagning'),
                        $this->module->getL('Discount')
                    );
                    foreach ($klarnaorder["cart"]["items"] as $klarnakey => $itemInKlarna) {
                        foreach ($checkoutcart as $pskey => $itemInPrestashop) {
                            if ($itemInKlarna["type"] == $itemInPrestashop["type"] &&
                                $itemInKlarna["name"] == $itemInPrestashop["name"] &&
                                $itemInKlarna["quantity"] == $itemInPrestashop["quantity"]
                            ) {
                                unset($klarnaorder["cart"]["items"][$klarnakey]);
                                unset($checkoutcart[$pskey]);
                            }
                        }
                    }
                    
                    if (is_array($klarnaorder["cart"]["items"]) && count($klarnaorder["cart"]["items"]) > 0) {
                        $this->redirectKCO();
                    }
                    if (is_array($checkoutcart) && count($checkoutcart) > 0) {
                        $this->redirectKCO();
                    }
                    //ALL IS OK
                    exit;
                } else {
                    $this->redirectKCO();
                }
            } else {
                $this->redirectKCO();
            }
        } else {
            $this->redirectKCO();
        }
    }
    public function redirectKCO($url = false)
    {
        header('HTTP/1.1 303 See Other');
        header('Cache-Control: no-cache');

        if ($url === false) {
            if (Tools::getIsset("v3")) {
                $url = $this->context->link->getModuleLink(
                    'klarnaofficial',
                    'checkoutklarnakco',
                    array("changed" => 1),
                    true
                );
            } else {
                $url = $this->context->link->getModuleLink(
                    'klarnaofficial',
                    'checkoutklarna',
                    array("changed" => 1),
                    true
                );
            }
        }
        
        Tools::redirect($url);
        exit;
    }
    
    protected function displayMaintenancePage()
    {

    }
}
