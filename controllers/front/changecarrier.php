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

class KlarnaOfficialChangeCarrierModuleFrontController extends ModuleFrontController
{
    public $display_column_left = false;
    public $display_column_right = false;
    public $ssl = true;

    public function init()
    {
        $klarnadata = Tools::file_get_contents('php://input');
        $klarnadata = json_decode($klarnadata);
        if ($klarnadata == false) {
            //something went wrong with the data, redirect.
            $this->redirectKCO();
        }
        Hook::exec('actionKlarnaChangeCarrier', array('klarnadata' => $klarnadata));
        $id_cart = (int) Tools::getValue('cartid');
        $id_carrier = (int) $klarnadata->selected_shipping_option->id;
        
        if ($id_cart > 0 && $id_carrier > 0) {
            $cart = new Cart($id_cart);
            $this->context->cart = $cart;
            $cart->id_carrier = $id_carrier;
            $cart->update();
            
            $new_delivery_options = array();
            $new_delivery_options[(int) $cart->id_address_delivery] = $cart->id_carrier.',';
            
            if (version_compare(_PS_VERSION_, "1.6.1.21", "<")) {
                $new_delivery_options_serialized = serialize($new_delivery_options);
            } else {
                $new_delivery_options_serialized = json_encode($new_delivery_options);
            }
            
            $update_sql = 'UPDATE '._DB_PREFIX_.'cart '.
                'SET delivery_option=\''.
                pSQL($new_delivery_options_serialized).
                '\' WHERE id_cart='.
                (int) $cart->id;
            Db::getInstance()->execute($update_sql);
            
            unset($cart);
            $cart = new Cart($id_cart);
        }
        
        require_once dirname(__FILE__).'/../../libraries/commonFeatures.php';
        $KlarnaCheckoutCommonFeatures = new KlarnaCheckoutCommonFeatures();
        //$cart->setDeliveryOption($cart->getDeliveryOption());
        $language = new Language((int)$cart->id_lang);
        if (isset($this->module->shippingreferences[$language->iso_code])) {
            $shippingReference = $this->module->shippingreferences[$language->iso_code];
        } else {
            $shippingReference = $this->module->shippingreferences["en"];
        }
        if (isset($this->module->wrappingreferences[$language->iso_code])) {
            $wrappingreference = $this->module->wrappingreferences[$language->iso_code];
        } else {
            $wrappingreference = $this->module->wrappingreferences["en"];
        }
        
        $carrieraddress = new Address($cart->id_address_delivery);
        $country_on_cart = new Country($carrieraddress->id_country);
        $this->context->country = $country_on_cart;
        
        $this->context->currency = new Currency((int)$cart->id_currency);
        $order_lines = $KlarnaCheckoutCommonFeatures->BuildCartArray(
            $cart,
            $shippingReference,
            $wrappingreference,
            $this->module->l('Wrapping', 'KlarnaOfficialChangeCarrierModuleFrontController'),
            $this->module->l('Discount', 'KlarnaOfficialChangeCarrierModuleFrontController'),
            true
        );
        $klarnadata->order_lines = $order_lines;
        $totalCartValue = $cart->getOrderTotal(true, Cart::BOTH, null, $cart->id_carrier, false);
        $totalCartValue_tax_excl = $cart->getOrderTotal(false, Cart::BOTH, null, $cart->id_carrier, false);
        $total_tax_value = 0;
        // $total_tax_value = $totalCartValue - $totalCartValue_tax_excl;
        $klarnadata->order_amount = $totalCartValue * 100;
        
        foreach ($order_lines as $item) {
            $total_tax_value += $item['total_tax_amount'];
        }
                        
        $klarnadata->order_tax_amount = $total_tax_value;
        
        echo json_encode($klarnadata);
        exit;
    }
    
    public function redirectKCO($url = false)
    {
        header('HTTP/1.1 303 See Other');
        header('Cache-Control: no-cache');

        if ($url === false) {
            $url = $this->context->link->getModuleLink(
                'klarnaofficial',
                'checkoutklarnakco',
                array("changed" => 1),
                true
            );
        }
        
        Tools::redirect($url);
        exit;
    }
}
