<?php

class KlarnaCheckoutCommonFeatures
{
    public function BuildCartArray($cart, $shippingReference, $wrappingreference, $wrappingname, $discountname) {
        $totalCartValue = 0;
        $highest_tax_rate = 0;
        
        foreach ($cart->getProducts() as $product) {
            $product_url = Context::getContext()->link->getProductLink($product['id_product'], $product['link_rewrite'], $product['category'], null, null, $product['id_shop'], $product['id_product_attribute'], false, false, true);
            $image_url = Context::getContext()->link->getImageLink($product['link_rewrite'], $product['id_image'], 'large_default');
            
            $price = Tools::ps_round($product['price_wt'], 2);
            $tax_value = (Tools::ps_round($product['price_wt'], 2) - Tools::ps_round($product['price'], 2));
            $tax_value = Tools::ps_round($tax_value, 2);
            $tax_value = ($tax_value * (int) ($product['cart_quantity']));
            $rowvalue = ($price * (int) ($product['cart_quantity']));
            $totalCartValue += $rowvalue;

            $tax_rate = (int) (Tools::ps_round($product['rate'],2) * 100);
            
            if (0 == $tax_value) {
                $tax_rate = 0;
            }
            
            $rate = $product['rate'];
            if (0 == $rate) {
                $tax_rate = 0;
                $tax_value = 0;
            } else {
                $tax_value = $price - ($price / (1+($rate/100)));
                $tax_value = ($tax_value * (int) ($product['cart_quantity']));
                $tax_value = Tools::ps_round($tax_value,2);
            }
            
            if ($tax_rate > $highest_tax_rate) {
                $highest_tax_rate = $tax_rate;
            }
            
            $depth = round($product['depth'], 0);
            $height = round($product['height'], 0);
            $width = round($product['width'], 0);
            $dimensions['height'] = (int) $height;
            $dimensions['length'] = (int) $width;
            $dimensions['depth'] = (int) $depth;
            $shipping_attributes = array(
                'weight' => round(($product['weight']*1000), 0),
                'dimensions' => $dimensions,
            );
            
            $price = $price * 100;
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
                'unit_price' => (int) $price,
                'product_url' => $product_url,
                'image_url' => $image_url,
                'tax_rate' => (int) $tax_rate,
                'total_amount' => (int) ($rowvalue * 100),
                'total_tax_amount' => (int) ($tax_value * 100),
                'shipping_attributes' => $shipping_attributes,
            );
        }
        $shipping_cost_with_tax = $cart->getOrderTotal(true, Cart::ONLY_SHIPPING, null, $cart->id_carrier, false);
        $shipping_cost_without_tax = $cart->getOrderTotal(false, Cart::ONLY_SHIPPING, null, $cart->id_carrier, false);
        $carrier = new Carrier($cart->id_carrier);
        $carrieraddress = new Address($cart->id_address_delivery);
        
        if ($shipping_cost_without_tax > 0) {
            if (Configuration::get('PS_ATCP_SHIPWRAP')) {
                $shipping_tax_rate = round(($shipping_cost_with_tax / $shipping_cost_without_tax) -1, 2) * 100;
            } else {
                $shipping_tax_rate = $carrier->getTaxesRate($carrieraddress);
            }
            
            if (($shipping_cost_with_tax != $shipping_cost_without_tax) && $shipping_tax_rate == 0) {
                //Prestashop error due to EU module?
                $shipping_tax_rate = round(($shipping_cost_with_tax / $shipping_cost_without_tax) -1, 2) * 100;
            }
            //$shipping_tax_value = ($shipping_cost_with_tax - $shipping_cost_without_tax);
            $shipping_tax_value = $shipping_cost_with_tax - ($shipping_cost_with_tax / (1+($shipping_tax_rate/100)));
            $shipping_tax_value = Tools::ps_round($shipping_tax_value, 2);
            $totalCartValue += $shipping_cost_with_tax;
            
            $shipping_tax_rate = (int) ($shipping_tax_rate * 100);
            
            if ($shipping_tax_rate > $highest_tax_rate) {
                $highest_tax_rate = $shipping_tax_rate;
            }
            
            $checkoutcart[] = array(
                'type' => 'shipping_fee',
                'reference' => $shippingReference,
                'name' => strip_tags($carrier->name),
                'quantity' => 1,
                'unit_price' => (int) ($shipping_cost_with_tax * 100),
                'tax_rate' => (int) $shipping_tax_rate,
                'total_amount' => (int) ($shipping_cost_with_tax * 100),
                'total_tax_amount' => (int) ($shipping_tax_value * 100),
            );
        }
        if ($cart->gift == 1) {
            $wrapping_cost_incl = $cart->getOrderTotal(true, Cart::ONLY_WRAPPING);
            if ($wrapping_cost_incl > 0) {
                $wrapping_cost_excl = $cart->getOrderTotal(false, Cart::ONLY_WRAPPING);
                $wrapping_vat = Tools::ps_round(($wrapping_cost_incl / $wrapping_cost_excl) -1, 2) * 100;
                $wrapping_tax_value = $wrapping_cost_incl - ($wrapping_cost_incl / (1+($wrapping_vat/100)));
                
                $wrapping_cost_incl = Tools::ps_round($wrapping_cost_incl, 2);
                $totalCartValue += $wrapping_cost_incl;
                
                $wrapping_vat = (int) ($wrapping_vat * 100);
                if ($wrapping_vat > $highest_tax_rate) {
                    $highest_tax_rate = $wrapping_vat;
                }
                
                $wrapping_tax_value = Tools::ps_round($wrapping_tax_value, 2);
                
                $checkoutcart[] = array(
                    'reference' => $wrappingreference,
                    'name' => $wrappingname,
                    'quantity' => 1,
                    'unit_price' => (int) ($wrapping_cost_incl * 100),
                    'tax_rate' => (int) $wrapping_vat,
                    'total_amount' => (int) ($wrapping_cost_incl * 100),
                    'total_tax_amount' => (int) ($wrapping_tax_value * 100),
                );
            }
        }

        //DISCOUNTS
        $rules = $cart->getCartRules(CartRule::FILTER_ACTION_ALL, false);
        foreach($rules as $rule) {
            $common_tax_rate = (($rule["value_real"] / $rule["value_tax_exc"]) - 1) * 100;
            $common_tax_rate = Tools::ps_round($common_tax_rate, 2);
            $discountname = $rule["name"];
            $value_real = Tools::ps_round($rule["value_real"], 2);

            if ($value_real > 0) {
                $tax_value = $value_real - ($value_real / (1+($common_tax_rate/100)));
                $tax_value = Tools::ps_round($tax_value, 2);
            } else {
                $tax_value = 0;
            }
            
            if ($highest_tax_rate == 0) {
                $tax_value = 0;
                $common_tax_rate = 0;
            }
            if ($value_real > 0) {
                $checkoutcart[] = array(
                    'type' => 'discount',
                    'reference' => '',
                    'name' => $discountname,
                    'quantity' => 1,
                    'unit_price' => (int) (-($value_real * 100)),
                    'tax_rate' => (int) ($common_tax_rate * 100),
                    'total_amount' => (int) (-($value_real * 100)),
                    'total_tax_amount' => (int) (-($tax_value * 100)),
                );                    
            }
        }
        
        return $checkoutcart;
    }

    public function getFromKlarna($mid, $sharedSecret, $headers, $endpoint)
    {
        $url = $this->getKlarnaUrl(Configuration::get('KCO_TESTMODE')).$endpoint;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, $mid.':'.$sharedSecret);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $contents = curl_exec($ch);
        curl_close ($ch);
        return $contents;
    }
    
    public function postToKlarna($data, $mid, $sharedSecret, $headers, $endpoint, $patch = false)
    {
        $url = $this->getKlarnaUrl(Configuration::get('KCO_TESTMODE')).$endpoint;
        $ch = curl_init($url);
        if (true === $patch) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        } else {
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, $mid.':'.$sharedSecret);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $contents = curl_exec($ch);
        curl_close ($ch);
        return $contents;
    }
    
    public function getKlarnaUrl($kcoTestMode)
    {
        if ($kcoTestMode == 1) {
            $url = 'https://api.playground.klarna.com';
        } else {
            $url = 'https://api.klarna.com';
        }
        return $url;
    }
    
}