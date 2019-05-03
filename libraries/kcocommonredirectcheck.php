<?php

if ($country_information === false) {
    Tools::redirect('index.php?controller=order&step=1');
}

$kcov3link = $this->context->link->getModuleLink($this->module->name, 'checkoutklarnakco', array(), true);
if (Tools::getIsset("kco_update")) {
    $extra = array("kco_update" => 1);
} else {
    $extra = array();
}
$kcolink = $this->context->link->getModuleLink($this->module->name, 'checkoutklarna', $extra, true);

$tmp_address = new Address((int) ($this->context->cart->id_address_delivery));
$country = new Country($tmp_address->id_country);

$skipCountryCheck = false;
if (Configuration::get('KCO_PREFILL') && Configuration::get('KCOV3') && $tmp_address->id_customer == $this->context->cart->id_customer) {
    $skipCountryCheck = true;
}

if ($country_information['purchase_country'] == 'SE') {
    if (Configuration::get('KCOV3')) {
        $eid = Configuration::get('KCOV3_EID');
        $sharedSecret = Configuration::get('KCOV3_SECRET');
    } else {
        $eid = (int) (Configuration::get('KCO_SWEDEN_EID'));
        $sharedSecret = Configuration::get('KCO_SWEDEN_SECRET');
    }
    $ssid = 'se';
    if ($country->iso_code != 'SE' && $skipCountryCheck === false) {
        if ($this->context->cart->id_address_delivery==Configuration::get('KCO_SWEDEN_ADDR')) {
            $this->module->createAddress(
                'SE',
                'KCO_SWEDEN_ADDR',
                'Stockholm',
                'Sverige',
                'KCO_SVERIGE_DEFAULT'
            );
        }
        
        $this->context->cart->id_address_delivery = (int) Configuration::get('KCO_SWEDEN_ADDR');
        $this->context->cart->id_address_invoice = (int) Configuration::get('KCO_SWEDEN_ADDR');
        $this->context->cart->update();
        $update_sql = 'UPDATE '._DB_PREFIX_.'cart_product '.
                    'SET id_address_delivery='.(int) Configuration::get('KCO_SWEDEN_ADDR').
                    ' WHERE id_cart='.(int) $this->context->cart->id;
        Db::getInstance()->execute($update_sql);
        if (Configuration::get('KCOV3')) {
            Tools::redirect($kcov3link);
        } else {
            Tools::redirect($kcolink);
        }
    }
    if ((bool)Configuration::get('KCOV3')==false) {
        $this->module->changeCurrencyonCart($currency, "SEK");
    }
    
    if ($this->current_kco == 'NORDICS' && Configuration::get('KCOV3')) {
        Tools::redirect($kcov3link);
    } elseif ($this->current_kco != 'NORDICS' && Configuration::get('KCO_SWEDEN')) {
        Tools::redirect($kcolink);
    }
} elseif ($country_information['purchase_country'] == 'FI') {
    if (Configuration::get('KCOV3')) {
        $eid = Configuration::get('KCOV3_EID');
        $sharedSecret = Configuration::get('KCOV3_SECRET');
    } else {
        $eid = (int) (Configuration::get('KCO_FINLAND_EID'));
        $sharedSecret = Configuration::get('KCO_FINLAND_SECRET');
    }
    $ssid = 'fi';
    if ($country->iso_code != 'FI' && $skipCountryCheck === false) {
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
        $this->context->cart->id_address_invoice = Configuration::get('KCO_FINLAND_ADDR');
        $this->context->cart->update();
        $update_sql = 'UPDATE '._DB_PREFIX_.'cart_product '.
                    'SET id_address_delivery='.(int) Configuration::get('KCO_FINLAND_ADDR').
                    ' WHERE id_cart='.(int) $this->context->cart->id;
        Db::getInstance()->execute($update_sql);
        if (Configuration::get('KCOV3')) {
            Tools::redirect($kcov3link);
        } else {
            Tools::redirect($kcolink);
        }
    }
    
    if ((bool)Configuration::get('KCOV3')==false) {
        $this->module->changeCurrencyonCart($currency, "EUR");
    }
    
    if ($this->current_kco == 'NORDICS' && Configuration::get('KCOV3')) {
        Tools::redirect($kcov3link);
    } elseif ($this->current_kco != 'NORDICS' && Configuration::get('KCO_FINLAND')) {
        Tools::redirect($kcolink);
    }
} elseif ($country_information['purchase_country'] == 'NO') {
    if (Configuration::get('KCOV3')) {
        $eid = Configuration::get('KCOV3_EID');
        $sharedSecret = Configuration::get('KCOV3_SECRET');
    } else {
        $eid = (int) (Configuration::get('KCO_NORWAY_EID'));
        $sharedSecret = Configuration::get('KCO_NORWAY_SECRET');
    }
    
    $ssid = 'no';
    if ($country->iso_code != 'NO' && $skipCountryCheck === false) {
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
        $this->context->cart->id_address_invoice = Configuration::get('KCO_NORWAY_ADDR');
        $this->context->cart->update();
        $update_sql = 'UPDATE '._DB_PREFIX_.'cart_product '.
                    'SET id_address_delivery='.(int) Configuration::get('KCO_NORWAY_ADDR').
                    ' WHERE id_cart='.(int) $this->context->cart->id;
        Db::getInstance()->execute($update_sql);
        if (Configuration::get('KCOV3')) {
            Tools::redirect($kcov3link);
        } else {
            Tools::redirect($kcolink);
        }
    }
    if ((bool)Configuration::get('KCOV3')==false) {
        $this->module->changeCurrencyonCart($currency, "NOK");
    }

    if ($this->current_kco == 'NORDICS' && Configuration::get('KCOV3')) {
        Tools::redirect($kcov3link);
    } elseif ($this->current_kco != 'NORDICS' && Configuration::get('KCO_NORWAY')) {
        Tools::redirect($kcolink);
    }
} elseif ($country_information['purchase_country'] == 'DE') {
    if (Configuration::get('KCOV3')) {
        $eid = Configuration::get('KCOV3_EID');
        $sharedSecret = Configuration::get('KCOV3_SECRET');
    } else {
        $eid = (int) (Configuration::get('KCO_GERMANY_EID'));
        $sharedSecret = Configuration::get('KCO_GERMANY_SECRET');
    }
    $ssid = 'de';
    if ($country->iso_code != 'DE' && $skipCountryCheck === false) {
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
        $this->context->cart->id_address_invoice = Configuration::get('KCO_GERMANY_ADDR');
        $this->context->cart->update();
        $update_sql = 'UPDATE '._DB_PREFIX_.'cart_product '.
                    'SET id_address_delivery='.(int) Configuration::get('KCO_GERMANY_ADDR').
                    ' WHERE id_cart='.(int) $this->context->cart->id;
        Db::getInstance()->execute($update_sql);
        if (Configuration::get('KCOV3')) {
            Tools::redirect($kcov3link);
        } else {
            Tools::redirect($kcolink);
        }
    }
    if ((bool)Configuration::get('KCOV3')==false) {
        $this->module->changeCurrencyonCart($currency, "EUR");
    }

    if ($this->current_kco == 'NORDICS' && Configuration::get('KCOV3')) {
        Tools::redirect($kcov3link);
    } elseif ($this->current_kco != 'NORDICS' && Configuration::get('KCO_GERMANY')) {
        Tools::redirect($kcolink);
    }
} elseif ($country_information['purchase_country'] == 'AT') {
    
    if (Configuration::get('KCOV3')) {
        $eid = Configuration::get('KCOV3_EID');
        $sharedSecret = Configuration::get('KCOV3_SECRET');
    } else {
        $eid = (int) (Configuration::get('KCO_AUSTRIA_EID'));
        $sharedSecret = Configuration::get('KCO_AUSTRIA_SECRET');
    }
    $ssid = 'at';
    if ($country->iso_code != 'AT' && $skipCountryCheck === false) {
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
        $this->context->cart->id_address_invoice = Configuration::get('KCO_AUSTRIA_ADDR');
        $this->context->cart->update();
        $update_sql = 'UPDATE '._DB_PREFIX_.'cart_product '.
                    'SET id_address_delivery='.(int) Configuration::get('KCO_AUSTRIA_ADDR').
                    ' WHERE id_cart='.(int) $this->context->cart->id;
        Db::getInstance()->execute($update_sql);
        if (Configuration::get('KCOV3')) {
            Tools::redirect($kcov3link);
        } else {
            Tools::redirect($kcolink);
        }
    }
    if ((bool)Configuration::get('KCOV3')==false) {
        $this->module->changeCurrencyonCart($currency, "EUR");
    }

    if ($this->current_kco == 'NORDICS' && Configuration::get('KCOV3')) {
        Tools::redirect($kcov3link);
    } elseif ($this->current_kco != 'NORDICS' && Configuration::get('KCO_AUSTRIA')) {
        Tools::redirect($kcolink);
    }
} elseif ($country_information['purchase_country'] == 'NL') {
    $eid = Configuration::get('KCO_NL_EID');
    $sharedSecret = Configuration::get('KCO_NL_SECRET');
    $ssid = 'nl';
    if ($country->iso_code != 'NL' && $skipCountryCheck === false) {
        if ($this->context->cart->id_address_delivery==Configuration::get('KCO_NL_ADDR')) {
            $this->module->createAddress(
                'NL',
                'KCO_NL_ADDR',
                'Amsterdam',
                'Netherlands',
                'KCO_NL_DEFAULT'
            );
        }
        
        $this->context->cart->id_address_delivery = (int) Configuration::get('KCO_NL_ADDR');
        $this->context->cart->id_address_invoice = (int) Configuration::get('KCO_NL_ADDR');
        $this->context->cart->update();
        $update_sql = 'UPDATE '._DB_PREFIX_.'cart_product '.
                    'SET id_address_delivery='.(int) Configuration::get('KCO_NL_ADDR').
                    ' WHERE id_cart='.(int) $this->context->cart->id;
        Db::getInstance()->execute($update_sql);
        Tools::redirect($kcov3link);
    }
    if ((bool)Configuration::get('KCOV3')==false) {
        $this->module->changeCurrencyonCart($currency, "EUR");
    }

    if ($this->current_kco != 'UKNL') {
        Tools::redirect($kcov3link);
    }
} elseif ($country_information['purchase_country'] == 'GB') {
    $eid = Configuration::get('KCO_UK_EID');
    $sharedSecret = Configuration::get('KCO_UK_SECRET');
    $ssid = 'gb';
    if ($country->iso_code != 'GB' && $skipCountryCheck === false) {
        
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
        $this->context->cart->id_address_invoice = (int) Configuration::get('KCO_UK_ADDR');
        $this->context->cart->update();
        $update_sql = 'UPDATE '._DB_PREFIX_.'cart_product '.
                    'SET id_address_delivery='.(int) Configuration::get('KCO_UK_ADDR').
                    ' WHERE id_cart='.(int) $this->context->cart->id;
        Db::getInstance()->execute($update_sql);
        Tools::redirect($kcov3link);
    }
    if ((bool)Configuration::get('KCOV3')==false) {
        $this->module->changeCurrencyonCart($currency, "GBP");
    }

    if ($this->current_kco != 'UKNL') {
        Tools::redirect($kcov3link);
    }
   
} elseif ($country_information['purchase_country'] == 'US') {
    $eid = Configuration::get('KCO_US_EID');
    $sharedSecret = Configuration::get('KCO_US_SECRET');
    $ssid = 'us';
    if ($country->iso_code != 'US' && $skipCountryCheck === false) {
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
        $this->context->cart->id_address_invoice = (int) Configuration::get('KCO_US_ADDR');
        $this->context->cart->update();
        $update_sql = 'UPDATE '._DB_PREFIX_.'cart_product '.
                    'SET id_address_delivery='.(int) Configuration::get('KCO_US_ADDR').
                    ' WHERE id_cart='.(int) $this->context->cart->id;
        Db::getInstance()->execute($update_sql);
        Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarnaus');
    }
    if ((bool)Configuration::get('KCOV3')==false) {
        $this->module->changeCurrencyonCart($currency, "USD");
    }
    if ($this->current_kco != 'US') {
        Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarnaus');
    }
}