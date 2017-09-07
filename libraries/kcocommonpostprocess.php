<?php
function clearSessions()
{
    if (isset($_SESSION['klarna_checkout'])) {
        unset($_SESSION['klarna_checkout']);
    }
    if (isset($_SESSION['klarna_checkout_us'])) {
        unset($_SESSION['klarna_checkout_us']);
    }
    if (isset($_SESSION['klarna_checkout_uk'])) {
        unset($_SESSION['klarna_checkout_uk']);
    }
}

if (Tools::isSubmit('kco_change_country')) {
    $id_lang = 0;
    $id_currency = 0;
    if (Tools::getValue('kco_change_country') == 'gb') {
        $id_lang = Language::getIdByIso('en');
        $id_currency = Currency::getIdByIsoCode('GBP');
        $id_tmp_address = (int) Configuration::get('KCO_UK_ADDR');
    }
    if (Tools::getValue('kco_change_country') == 'nl') {
        $id_lang = Language::getIdByIso('nl');
        $id_currency = Currency::getIdByIsoCode('EUR');
        $id_tmp_address = (int) Configuration::get('KCO_NL_ADDR');
    }
    if (Tools::getValue('kco_change_country') == 'us') {
        $id_lang = Language::getIdByIso('en');
        $id_currency = Currency::getIdByIsoCode('USD');
        $id_tmp_address = (int) Configuration::get('KCO_US_ADDR');
    }
    if (Tools::getValue('kco_change_country') == 'sv') {
        $id_lang = Language::getIdByIso('sv');
        $id_currency = Currency::getIdByIsoCode('SEK');
        $id_tmp_address = (int) Configuration::get('KCO_SWEDEN_ADDR');
    }
    if (Tools::getValue('kco_change_country') == 'fi') {
        $id_lang = Language::getIdByIso('fi');
        if ((int) ($id_lang) == 0) {
            $id_lang = Language::getIdByIso('sv');
        }
        $id_currency = Currency::getIdByIsoCode('EUR');
        $id_tmp_address = (int) Configuration::get('KCO_FINLAND_ADDR');
    }
    if (Tools::getValue('kco_change_country') == 'de') {
        $id_lang = Language::getIdByIso('de');
        $id_currency = Currency::getIdByIsoCode('EUR');
        $id_tmp_address = (int) Configuration::get('KCO_GERMANY_ADDR');
    }
    if (Tools::getValue('kco_change_country') == 'at') {
        $id_lang = Language::getIdByIso('de');
        $id_currency = Currency::getIdByIsoCode('EUR');
        $id_tmp_address = (int) Configuration::get('KCO_AUSTRIA_ADDR');
    }
    if (Tools::getValue('kco_change_country') == 'no') {
        $id_lang = Language::getIdByIso('no');
        if ((int) $id_lang == 0) {
            $id_lang = Language::getIdByIso('nb');
        }
        if ((int) $id_lang == 0) {
            $id_lang = Language::getIdByIso('nn');
        }
        $id_currency = Currency::getIdByIsoCode('NOK');
        $id_tmp_address = (int) Configuration::get('KCO_NORWAY_ADDR');
    }
    
    if ($id_lang > 0 and $id_currency > 0) {
        $_GET['id_lang'] = $id_lang;
        $_POST['id_lang'] = $id_lang;
        $_POST['id_currency'] = $id_currency;
        $_POST['SubmitCurrency'] = $id_currency;
        Tools::switchLanguage();
        Tools::setCurrency($this->context->cookie);
        $this->context->cart->id_lang = $id_lang;
        $this->context->cart->id_currency = $id_currency;
        
        //IF CUSTOMER IS LOGGED IN, WE NEED TO CREATE A NEW ADDRESS FOR THIS CUSTOMER.
        if (isset($this->context->customer) && $this->context->customer->id > 0) {
            $address_to_check = new Address($id_tmp_address);
            $simple_address_found = false;
            if ($address_to_check->id_customer != $this->context->customer->id) {
                foreach($this->context->customer->getSimpleAddresses() as $simple_address) {
                    if ($simple_address["id_country"] == $address_to_check->id_country) {
                        $id_tmp_address = $simple_address["id"];
                        $simple_address_found = true;
                    }
                }
                if (false == $simple_address_found) {
                    unset($address_to_check->id);
                    $address_to_check->id_customer = $this->context->customer->id;
                    $address_to_check->add();
                    $id_tmp_address = $address_to_check->id;
                }
            }
        }
        $this->context->cart->id_address_delivery = $id_tmp_address;
        $this->context->cart->id_address_invoice = $id_tmp_address;
        $this->context->cart->update();
        SetNewAddressOnCartPost($this->context->cart->id_address_delivery, $this->context->cart->id);
        $tmp_ad = new Address((int)$id_tmp_address);
        $country = new Country((int)$tmp_ad->id_country);
        $this->context->country = $country;
        //KILL THE SESSION TO START A NEW
        clearSessions();
        

        if (Tools::getValue('kco_change_country') == 'gb') {
            Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarnauk');
        } elseif (Tools::getValue('kco_change_country') == 'us') {
            Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarnaus');
        } elseif (Tools::getValue('kco_change_country') == 'nl') {
            Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarnauk');
        } else {
            Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarna');
        }
    }
}
if (Tools::isSubmit('savemessagebutton')) {
    $messageContent = Tools::getValue('message');
    $message_result = $this->updateMessage($messageContent, $this->context->cart);
    if (!$message_result) {
        $this->context->smarty->assign('gift_error', $this->module->l('Invalid message', 'kcocommonpostprocess'));
    }
}
if (Tools::isSubmit('savegift')) {
    $this->context->cart->gift = (int) (Tools::getValue('gift'));
    $gift_error = '';
    if (!Validate::isMessage($_POST['gift_message'])) {
        $gift_error = $this->module->l('Invalid gift message', 'kcocommonpostprocess');
    } else {
        $this->context->cart->gift_message = strip_tags(Tools::getValue('gift_message'));
    }
    $this->context->cart->update();
    $this->context->smarty->assign('gift_error', $gift_error);
}
if (CartRule::isFeatureActive()) {
    $vouchererrors = '';
    if (Tools::isSubmit('submitAddDiscount')) {
        $code = trim(Tools::getValue('discount_name'));
        $code = Tools::purifyHTML($code);
        if (!($code)) {
            $vouchererrors = $this->module->l('You must enter a voucher code', 'kcocommonpostprocess');
        } elseif (!Validate::isCleanHtml($code)) {
            $vouchererrors = $this->module->l('Voucher code invalid', 'kcocommonpostprocess');
        } else {
            if (($cartRule = new CartRule(CartRule::getIdByCode($code))) &&
            Validate::isLoadedObject($cartRule)) {
                if ($error = $cartRule->checkValidity(
                    $this->context,
                    false,
                    true
                )) {
                    $vouchererrors = $error;
                } else {
                    $this->context->cart->addCartRule($cartRule->id);
                    $url = 'index.php?fc=module&module=klarnaofficial&controller=checkoutklarna';
                    Tools::redirect($url);
                }
            } else {
                $vouchererrors = $this->module->l('This voucher does not exists', 'kcocommonpostprocess');
            }
        }
        //FORCE html_entity_decode SINCE PRESTASHOP Demand escape:html in
        //tpl files but already does this on displayError..
        $this->context->smarty->assign(array(
            'vouchererrors' => html_entity_decode($vouchererrors),
            'discount_name' => Tools::safeOutput($code),
        ));
    } elseif (($id_cart_rule = (int) Tools::getValue('deleteDiscount')) &&
    Validate::isUnsignedId($id_cart_rule)) {
        $this->context->cart->removeCartRule($id_cart_rule);
        $url = 'index.php?fc=module&module=klarnaofficial&controller=checkoutklarna';
        Tools::redirect($url);
    }
}

if (Tools::getIsset('delivery_option')) {
    if ($this->validateDeliveryOption(Tools::getValue('delivery_option'))) {
        $this->context->cart->setDeliveryOption(Tools::getValue('delivery_option'));
    }

    if (!$this->context->cart->update()) {
        $this->context->smarty->assign(array(
            'vouchererrors' => $this->module->l('Could not save carrier selection', 'kcocommonpostprocess'),
        ));
    }

    // Carrier has changed, so we check if the cart rules still apply
    CartRule::autoRemoveFromCart($this->context);
    CartRule::autoAddToCart($this->context);
}

function SetNewAddressOnCartPost($id_address_delivery, $id_cart)
{
    $update_sql = 'UPDATE '._DB_PREFIX_.'cart_product '.
        'SET id_address_delivery='.(int) $id_address_delivery.
        ' WHERE id_cart='.(int) $id_cart;
        
    Db::getInstance()->execute($update_sql);

    $update_sql = 'UPDATE '._DB_PREFIX_.'customization '.
        'SET id_address_delivery='.(int) $id_address_delivery.
        ' WHERE id_cart='.(int) $id_cart;
        
    Db::getInstance()->execute($update_sql);

}