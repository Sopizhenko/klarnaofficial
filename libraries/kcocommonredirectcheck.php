<?php

if ($country_information === false) {
    Tools::redirect('index.php?controller=order&step=1');
}

$tmp_address = new Address((int) ($this->context->cart->id_address_delivery));
$country = new Country($tmp_address->id_country);
if ($country_information['purchase_country'] == 'SE') {
    $eid = (int) (Configuration::get('KCO_SWEDEN_EID'));
    $sharedSecret = Configuration::get('KCO_SWEDEN_SECRET');
    $ssid = 'se';
    if ($country->iso_code != 'SE') {
        if ($this->context->cart->id_address_delivery==Configuration::get('KCO_SWEDEN_ADDR')) {
            $this->module->createAddress(
                'SE',
                'KCO_SWEDEN_ADDR',
                'Stockholm',
                'Sverige',
                'KCO_SVERIGE_DEFAULT'
            );
        }
        
        $this->context->cart->id_address_delivery = Configuration::get('KCO_SWEDEN_ADDR');
        $this->context->cart->update();
        Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarna');
    }
    if ($this->current_kco != 'NORDICS') {
        Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarna');
    }
} elseif ($country_information['purchase_country'] == 'FI') {
    $eid = (int) (Configuration::get('KCO_FINLAND_EID'));
    $sharedSecret = Configuration::get('KCO_FINLAND_SECRET');
    $ssid = 'fi';
    if ($country->iso_code != 'FI') {
        if ($this->context->cart->id_address_delivery==Configuration::get('KCO_FINLAND_ADDR')) {
            $this->module->createAddress(
                'FI',
                'KCO_FINLAND_ADDR',
                'Helsinkki',
                'Finland',
                'KCO_FINLAND_DEFAULT'
            );
        }
        
        $this->context->cart->id_address_delivery = Configuration::get('KCO_FINLAND_ADDR');
        $this->context->cart->update();
    }
    if ($this->current_kco != 'NORDICS') {
        Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarna');
    }
} elseif ($country_information['purchase_country'] == 'NO') {
    $eid = (int) (Configuration::get('KCO_NORWAY_EID'));
    $sharedSecret = Configuration::get('KCO_NORWAY_SECRET');
    $ssid = 'no';
    if ($country->iso_code != 'NO') {
        if ($this->context->cart->id_address_delivery==Configuration::get('KCO_NORWAY_ADDR')) {
            $this->module->createAddress(
                'NO',
                'KCO_NORWAY_ADDR',
                'Oslo',
                'Norge',
                'KCO_NORGE_DEFAULT'
            );
        }
        
        $this->context->cart->id_address_delivery = Configuration::get('KCO_NORWAY_ADDR');
        $this->context->cart->update();
    }
    if ($this->current_kco != 'NORDICS') {
        Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarna');
    }
} elseif ($country_information['purchase_country'] == 'DE') {
    $eid = (int) (Configuration::get('KCO_GERMANY_EID'));
    $sharedSecret = Configuration::get('KCO_GERMANY_SECRET');
    $ssid = 'de';
    if ($country->iso_code != 'DE') {
        if ($this->context->cart->id_address_delivery==Configuration::get('KCO_GERMANY_ADDR')) {
            $this->module->createAddress(
                'DE',
                'KCO_GERMANY_ADDR',
                'Berlin',
                'Germany',
                'KCO_GERMANY_DEFAULT'
            );
        }
        
        $this->context->cart->id_address_delivery = Configuration::get('KCO_GERMANY_ADDR');
        $this->context->cart->update();
    }
    if ($this->current_kco != 'NORDICS') {
        Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarna');
    }
} elseif ($country_information['purchase_country'] == 'AT') {
    $eid = (int) (Configuration::get('KCO_AUSTRIA_EID'));
    $sharedSecret = Configuration::get('KCO_AUSTRIA_SECRET');
    $ssid = 'at';
    if ($country->iso_code != 'AT') {
        if ($this->context->cart->id_address_delivery==Configuration::get('KCO_AUSTRIA_ADDR')) {
            $this->module->createAddress(
                'AT',
                'KCO_AUSTRIA_ADDR',
                'Vienna',
                'Austria',
                'KCO_AUSTRIA_DEFAULT'
            );
        }
        
        $this->context->cart->id_address_delivery = Configuration::get('KCO_AUSTRIA_ADDR');
        $this->context->cart->update();
    }
    if ($this->current_kco != 'NORDICS') {
        Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarna');
    }
} elseif ($country_information['purchase_country'] == 'NL') {
    $eid = Configuration::get('KCO_NL_EID');
    $sharedSecret = Configuration::get('KCO_NL_SECRET');
    $ssid = 'nl';
    if ($country->iso_code != 'NL') {
        if ($this->context->cart->id_address_delivery==Configuration::get('KCO_NL_ADDR')) {
            $this->module->createAddress(
                'NL',
                'KCO_NL_ADDR',
                'Amsterdam',
                'Netherlands',
                'KCO_NL_DEFAULT'
            );
        }
        
        $this->context->cart->id_address_delivery = Configuration::get('KCO_NL_ADDR');
        $this->context->cart->update();
        Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarnauk');
    }
    if ($this->current_kco != 'UKNL') {
        Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarnauk');
    }
} elseif ($country_information['purchase_country'] == 'GB') {
    $eid = Configuration::get('KCO_UK_EID');
    $sharedSecret = Configuration::get('KCO_UK_SECRET');
    $ssid = 'gb';
    if ($country->iso_code != 'GB') {
        
        if ($this->context->cart->id_address_delivery==Configuration::get('KCO_UK_ADDR')) {
            $this->module->createAddress(
                'GB',
                'KCO_UK_ADDR',
                'London',
                'United Kingdom',
                'KCO_UK_DEFAULT'
            );
        }
        
        $this->context->cart->id_address_delivery = Configuration::get('KCO_UK_ADDR');
        $this->context->cart->update();
        Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarnauk');
    }
    if ($this->current_kco != 'UKNL') {
        Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarnauk');
    }
   
} elseif ($country_information['purchase_country'] == 'US') {
    $eid = Configuration::get('KCO_US_EID');
    $sharedSecret = Configuration::get('KCO_US_SECRET');
    $ssid = 'us';
    if ($country->iso_code != 'US') {
        if ($this->context->cart->id_address_delivery==Configuration::get('KCO_US_ADDR')) {
            $this->module->createAddress(
                'US',
                'KCO_US_ADDR',
                'Washington',
                'United State',
                'KCO_US_DEFAULT'
            );
        }
        
        $this->context->cart->id_address_delivery = Configuration::get('KCO_US_ADDR');
        $this->context->cart->update();
        Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarnaus');
    }
    if ($this->current_kco != 'US') {
        Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarnaus');
    }
}