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

class KlarnaOfficialValidateAddressModuleFrontController extends ModuleFrontController
{
    public $display_column_left = false;
    public $display_column_right = false;
    public $ssl = true;

    public function init()
    {
        $klarnadata = Tools::file_get_contents('php://input');
        $id_cart = (int) Tools::getValue("cartid");
        PrestaShopLogger::addLog("id_cart: ".$id_cart, 3, null, '', 0, true);
        PrestaShopLogger::addLog(print_r($klarnadata, true), 3, null, '', 0, true);
        $klarnaorder = Tools::jsonDecode($klarnadata, true);
        if ($klarnadata["shipping_address"]["country"] == "us") {
            $iso_code = pSQL($klarnadata["shipping_address"]["region"]);
            $id_country = (int)Country::getByIso("us");
            if ($id_country > 0) {
                $sql = "SELECT id_state FROM `"._DB_PREFIX_."state` WHERE id_country=$id_country AND iso_code='$iso_code'";
                $id_state = (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
            } else {
                //US NOT FOUND
                exit;
            }
        } else {
            //NOT US
            exit;
        }
        
        //We need to set a temporary address here on the cart to calculate tax and shipping cost based on
        $cart = new Cart($id_cart);
        $language = new Language($cart->id_lang);
        $checkoutcart = array();
        
        $round_diff = 0;
        $totalCartValue = 0;
        $total_tax = 0;
        
        foreach ($cart->getProducts() as $product) {
            $price = Tools::ps_round($product['price'], 2);
            $rowvalue = ($price * (int) ($product['cart_quantity']));
            $totalCartValue += $rowvalue;

            $price = ($price * 100);
            $checkoutcart[] = array(
                'type' => 'physical',
                'reference' => (isset($product['reference']) &&
                $product['reference'] != '' ? $product['reference'] : $product['id_product']),
                'name' => strip_tags(
                    $product['name'].((isset($product['attributes']) &&
                    $product['attributes'] != null) ? ' - '.$product['attributes'] : '').
                    ((isset($product['instructions']) &&
                    $product['instructions'] != null) ? ' - '.$product['instructions'] : '')
                ),
                'quantity' => (int) ($product['cart_quantity']),
                'quantity_unit' => 'pcs',
                'total_discount_amount' => 0,
                'unit_price' => $price,
                'tax_rate' => 0,
                'total_amount' => (int) ($rowvalue * 100),
                'total_tax_amount' => 0,
            );
        }

        $shipping_cost_with_tax = $cart->getOrderTotal(true, Cart::ONLY_SHIPPING);
        $shipping_cost_without_tax = $cart->getOrderTotal(false, Cart::ONLY_SHIPPING);

        if ($shipping_cost_without_tax > 0) {
            $shipping_tax_rate = ($shipping_cost_with_tax / $shipping_cost_without_tax) - 1;
            $shipping_tax_value = ($shipping_cost_with_tax - $shipping_cost_without_tax);
            $totalCartValue += $shipping_cost_with_tax;

            $carrier = new Carrier($cart->id_carrier);
            $shippingReference = $this->module->shippingreferences[$language->iso_code];
            $total_tax += $shipping_tax_value;
            
            $checkoutcart[] = array(
                'type' => 'shipping_fee',
                'reference' => $shippingReference,
                'name' => strip_tags($carrier->name),
                'quantity' => 1,
                'unit_price' => ($shipping_cost_without_tax * 100),
                'tax_rate' => 0,
                'total_amount' => ($shipping_cost_without_tax * 100),
                'total_tax_amount' => 0,
            );
        }
        if ($cart->gift == 1) {
            $cart_wrapping = $cart->getOrderTotal(true, Cart::ONLY_WRAPPING);
            if ($cart_wrapping > 0) {
                $wrapping_cost_excl = $cart->getOrderTotal(false, Cart::ONLY_WRAPPING);
                $wrapping_cost_incl = $cart->getOrderTotal(true, Cart::ONLY_WRAPPING);
                $wrapping_vat = (($wrapping_cost_incl / $wrapping_cost_excl) - 1) * 100;
                $wrapping_tax_value = ($wrapping_cost_incl - $wrapping_cost_excl);
                
                $cart_wrapping = Tools::ps_round($cart_wrapping, 2);
                $totalCartValue += $cart_wrapping;
                
                $wrappingreference = $this->module->wrappingreferences[$language->iso_code];
                $total_tax += $wrapping_tax_value;
                $checkoutcart[] = array(
                    'reference' => $wrappingreference,
                    'name' => $this->module->getL('Inslagning'),
                    'quantity' => 1,
                    'unit_price' => ($wrapping_cost_excl * 100),
                    'tax_rate' => 0,
                    'total_amount' => ($wrapping_cost_excl * 100),
                    'total_tax_amount' => 0,
                );
            }
        }

        //DISCOUNTS
        $totalDiscounts = $cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS);
        if ($totalDiscounts > 0) {
            if ($totalDiscounts > $totalCartValue) {
                //Free order
                $totalCartValue = $cart->getOrderTotal(true, Cart::BOTH);
                $totalCartValue_tax_excl = $cart->getOrderTotal(false, Cart::BOTH);
                $common_tax_rate = (($totalCartValue / $totalCartValue_tax_excl) - 1) * 100;
                $common_tax_value = ($totalCartValue - $totalCartValue_tax_excl);
                
                $total_tax -= $common_tax_value;
                $checkoutcart[] = array(
                    'type' => 'discount',
                    'reference' => '',
                    'name' => $this->module->getL('Discount'),
                    'quantity' => 1,
                    'unit_price' => -($totalCartValue * 100),
                    'tax_rate' => 0,
                    'total_amount' => -($totalCartValue * 100),
                    'total_tax_amount' => -0,
                );
            } else {
                $totalDiscounts_tax_excl = $cart->getOrderTotal(false, Cart::ONLY_DISCOUNTS);
                $common_tax_rate = (($totalDiscounts / $totalDiscounts_tax_excl) - 1) * 100;
                $common_tax_rate = Tools::ps_round($common_tax_rate, 0);
                $common_tax_value = ($totalDiscounts - $totalDiscounts_tax_excl);
                $total_tax -= $common_tax_value;
                
                $checkoutcart[] = array(
                    'type' => 'discount',
                    'reference' => '',
                    'name' => $this->module->getL('Discount'),
                    'quantity' => 1,
                    'unit_price' => -number_format(($totalDiscounts * 100), 2, '.', ''),
                    'tax_rate' => 0,
                    'total_amount' => -number_format(($totalDiscounts * 100), 2, '.', ''),
                    'total_tax_amount' => -0,
                );
            }
        }

        if ($round_diff != 0) {
            $checkoutcart[] = array(
                'reference' => '',
                'name' => 'Avrundning',
                'quantity' => 1,
                'unit_price' => round(($round_diff * 100), 0),
                'discount_rate' => 0,
                'tax_rate' => 0,
                'total_amount' => round(($round_diff * 100), 0),
                'total_tax_amount' => 0,
            );
        }
        
        
        
        $totalCartValue = $cart->getOrderTotal(true, Cart::BOTH);
        $totalCartValue_tax_excl = $cart->getOrderTotal(false, Cart::BOTH);
        $total_tax_value = $totalCartValue - $totalCartValue_tax_excl;
        
        //FAKE
        $totalCartValue = $totalCartValue + 100;
        $total_tax_value = $total_tax_value + 20;
        $checkoutcart[] = array(
            'type' => 'physical',
            'reference' => "111-12",
            'name' => "test",
            //'quantity_unit' => 'pcs',
            'total_discount_amount' => 0,
            'quantity' => (int) 1,
            'unit_price' => 8000,
            'tax_rate' => 0,
            'total_amount' => 8000,
            'total_tax_amount' => 0,
        );
        //FAKE
        $object["order_amount"] = $totalCartValue * 100;
        $object["order_tax_amount"] = $total_tax_value * 100;
        
        
        $checkoutcart[] = array(
            'type' => 'sales_tax',
            'reference' => 'Sales Tax',
            'name' => 'Sales Tax',
            'quantity' => 1,
            'unit_price' => round(($total_tax_value * 100), 0),
            'discount_rate' => 0,
            'total_discount_amount' => 0,
            'tax_rate' => 0,
            'total_amount' => round(($total_tax_value * 100), 0),
            'total_tax_amount' => 0,
        );
        
        $object["order_lines"] = $checkoutcart;
        
        $shipping_options[] = array(
            "id" => "free",
          "name"=> "Free Shipping",
          "price"=> 0,
          "promo"=> "",
          "tax_amount"=> 0,
          "tax_rate"=> 0,
          "description"=> "Free shipping directly to your door",
          "preselected"=> true
        );
        
        $shipping_options[] = array(
           "id"=> "pickup",
          "name"=> "Pick up at closest store",
          "price"=> 385,
          "promo"=> "",
          "tax_amount"=> 35,
          "tax_rate"=> 1000,
          "description"=> "Your goods will be sent to the physical store closest to you."
        );
        
        $shipping_options[] = array(
           "id"=> "Shipping",
          "name"=> "My carrier",
          "price"=> 77,
          "promo"=> "",
          "tax_amount"=> 15,
          "tax_rate"=> 2500,
          "description"=> "Your goods will be sent to the physical store closest to you."
        );
        
        $shipping_options[] = array(
           "id"=> "pickup 2",
          "name"=> "Pick up at closest store 2",
          "price"=> 385,
          "promo"=> "",
          "tax_amount"=> 35,
          "tax_rate"=> 1000,
          "description"=> "Your goods will be sent to the physical store closest to you."
        );
        
        $object["shipping_options"] = $shipping_options;
        echo json_encode($object);
        exit;
    }
}
