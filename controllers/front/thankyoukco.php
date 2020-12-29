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
 
class KlarnaOfficialThankYouKcoModuleFrontController extends ModuleFrontController
{
    public $display_column_left = false;
    public $display_column_right = false;
    public $ssl = true;

    public function setMedia()
    {
        parent::setMedia();
        $this->context->controller->addCSS(_MODULE_DIR_.'klarnaofficial/views/css/klarnacheckout.css', 'all');
    }

    public function initContent()
    {
        parent::initContent();

        session_start();
        if (!Tools::getIsset('klarna_order_id')) {
            Tools::redirect('index.php');
        }
        try {
            $merchantId = Configuration::get('KCOV3_MID');
            $sharedSecret = Configuration::get('KCOV3_SECRET');
            $ssid = Tools::getValue('sid');

            require_once dirname(__FILE__).'/../../libraries/commonFeatures.php';
            $KlarnaCheckoutCommonFeatures = new KlarnaCheckoutCommonFeatures();
            $version = $this->module->version;
            $klarna_order_id = pSQL(Tools::getValue('klarna_order_id'));
            $checkout = $KlarnaCheckoutCommonFeatures->getFromKlarna($merchantId, $sharedSecret, $version, '/checkout/v3/orders/'.$klarna_order_id);
            $checkout = json_decode($checkout, true);
            

            $snippet = $checkout['html_snippet'];

            if ($checkout['status'] == 'checkout_incomplete') {
                Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarnakco');
            }

            $id_cart = (int)$checkout['merchant_reference2'];
            
            $sql_cart_select = 'SELECT id_order FROM `'._DB_PREFIX_.
                        "klarna_orders` WHERE id_order>0 AND id_cart=".
                        (int) $id_cart;

            $result = array();
            $id_order = (int)Db::getInstance()->getValue($sql_cart_select);
            if ($id_order > 0) {
                $result['id_order'] = $id_order;
            } else {
                if ($checkout['status'] == 'checkout_complete') {
                    $country_iso_codes = array(
                    'SWE' => 'SE',
                    'NOR' => 'NO',
                    'FIN' => 'FI',
                    'DNK' => 'DK',
                    'DEU' => 'DE',
                    'NLD' => 'NL',
                    'se' => 'SE',
                    'no' => 'NO',
                    'fi' => 'FI',
                    'dk' => 'DK',
                    'de' => 'DE',
                    'nl' => 'NL',
                    'gb' => 'GB',
                    'it' => 'IT',
                    'fr' => 'FR',
                    'us' => 'US',
                    );

                    $cart = new Cart((int) ($id_cart));
                    
                    if ($id_order > 0) {
                        //ORDER ALREADY EXIST
                    }
                    
                    Context::getContext()->currency = new Currency((int) $cart->id_currency);

                    $reference = Tools::getValue('klarna_order_id');
                    // $klarna_reservation = Tools::getValue('klarna_order_id');
                    $shipping = $checkout['shipping_address'];
                    $billing = $checkout['billing_address'];

                    if (!Validate::isEmail($billing['email'])) {
                        $billing['email'] = 'ingen_mejl_'.$id_cart.'@ingendoman.cc';
                    }
                    
                    $newsletter = 0;
                    $newsletter_setting = (int)Configuration::get('KCO_ADD_NEWSLETTERBOX', null, $cart->id_shop);
                    if ($newsletter_setting == 0 || $newsletter_setting == 1) {
                        if (isset($checkout['merchant_requested']) &&
                            isset($checkout['merchant_requested']['additional_checkbox']) &&
                            $checkout['merchant_requested']['additional_checkbox'] == true
                        ) {
                            $newsletter = 1;
                        }
                    } elseif ($newsletter_setting == 2) {
                        $newsletter = 1;
                    }

                    $KCOV3_USEGUESTACCOUNTS = Configuration::get(
                        'KCOV3_USEGUESTACCOUNTS',
                        null,
                        $cart->id_shop
                    );
        
                    $id_customer = (int) (Customer::customerExists($billing['email'], true, true));
                    if ($id_customer > 0) {
                        $customer = new Customer($id_customer);
                        if ($newsletter == 1) {
                            $sql_update_customer = "UPDATE "._DB_PREFIX_."customer SET newsletter=1".
                            " WHERE id_customer=$id_customer;";
                            Db::getInstance()->execute(pSQL($sql_update_customer));
                        }
                    } else {
                        //add customer
                        $password = Tools::passwdGen(8);
                        $customer = new Customer();
                        $customer->firstname = $this->module->truncateValue($billing['given_name'], 32, true);
                        $customer->lastname = $this->module->truncateValue($billing['family_name'], 32, true);
                        $customer->email = $billing['email'];
                        $customer->passwd = Tools::encrypt($password);
                        $customer->is_guest = 0;
                        if (0 == (int) $KCOV3_USEGUESTACCOUNTS) {
                            $customer->id_default_group = (int) (Configuration::get(
                                'PS_CUSTOMER_GROUP',
                                null,
                                $cart->id_shop
                            ));
                            $customer->is_guest = 0;
                        } else {
                            $customer->id_default_group = (int) (Configuration::get(
                                'PS_GUEST_GROUP',
                                null,
                                $cart->id_shop
                            ));
                            $customer->is_guest = 1;
                        }
                        
                        $customer->newsletter = $newsletter;
                        $customer->optin = 0;
                        $customer->active = 1;
                        $customer->id_gender = 9;
                        $customer->add();
                        if (0 == $customer->is_guest) {
                            if (!$this->sendConfirmationMail($customer, $cart->id_lang, $password)) {
                                Logger::addLog(
                                    'KCO: Failed sending welcome mail to: '.$shipping['email'],
                                    1,
                                    null,
                                    null,
                                    null,
                                    true
                                );
                            }
                        }
                    }

                    $delivery_address_id = 0;
                    $invoice_address_id = 0;
                    $shipping_iso = Tools::strtoupper($shipping['country']);
                    $invocie_iso = Tools::strtoupper($billing['country']);
                    // $shipping_iso = $country_iso_codes[$shipping['country']];
                    // $invocie_iso = $country_iso_codes[$billing['country']];
                    $shipping_country_id = Country::getByIso($shipping_iso);
                    $invocie_country_id = Country::getByIso($invocie_iso);
                    
                    $is_same = true;
                    foreach ($shipping as $key => $value) {
                        if ($billing[$key] != $value) {
                            $is_same = false;
                            continue;
                        }
                    }
                    if (!isset($billing['street_address2'])) {
                        $billing['street_address2'] = "";
                    }
                    if (!isset($shipping['street_address2'])) {
                        $shipping['street_address2'] = "";
                    }
                                
                    if (!isset($shipping['care_of'])) {
                        $shipping['care_of'] = $shipping['street_address2'];
                    }
                    if (!isset($billing['care_of'])) {
                        $billing['care_of'] = $billing['street_address2'];
                    }
                    if (!isset($billing['organization_name'])) {
                        $billing['organization_name'] = "";
                    }
                    if (!isset($shipping['organization_name'])) {
                        $shipping['organization_name'] = "";
                    }
                    if (!isset($billing['attention'])) {
                        $billing['attention'] = "";
                    }
                    if (!isset($shipping['attention'])) {
                        $shipping['attention'] = "";
                    }
                    
                    if ($shipping_iso == "IT" || $shipping_iso == "US") {
                        if (isset($shipping['region'])) {
                            $shippingregion = $shipping['region'];
                            $shipping_state_id = State::getIdByIso($shippingregion, $shipping_country_id);
                            if (!$shipping_state_id>0) {
                                $shippingregion = Tools::ucfirst(Tools::strtolower($shippingregion));
                                $shipping_state_id = State::getIdByName($shippingregion);
                                $statetmp = new State($shipping_state_id);
                                if ($statetmp->id_country != $shipping_country_id) {
                                    $shipping_state_id = 0;
                                }
                            }
                        }
                        if (isset($billing['region'])) {
                            $billingregion = $billing['region'];
                            $invoice_state_id = State::getIdByIso($billingregion, $invocie_country_id);
                            if (!$invoice_state_id>0) {
                                $billingregion = Tools::ucfirst(Tools::strtolower($billingregion));
                                $invoice_state_id = State::getIdByName($billingregion);
                                $statetmp = new State($invoice_state_id);
                                if ($statetmp->id_country != $invocie_country_id) {
                                    $invoice_state_id = 0;
                                }
                            }
                        }
                    }
                    
                    foreach ($customer->getAddresses($cart->id_lang) as $address) {
                        if ($address['firstname'] == $shipping['given_name']
                        and $address['lastname'] == $shipping['family_name']
                        and $address['city'] == $shipping['city']
                        and $address['address2'] == $shipping['care_of']
                        and $address['address1'] == $shipping['street_address']
                        and $address['postcode'] == $shipping['postal_code']
                        and $address['other'] == $shipping['attention']
                        and $address['company'] == $shipping['organization_name']
                        and $address['phone_mobile'] == $shipping['phone']
                        and $address['id_country'] == $shipping_country_id) {
                            //LOAD SHIPPING ADDRESS
                            $cart->id_address_delivery = $address['id_address'];
                            $delivery_address_id = $address['id_address'];
                        }
                        if ($address['firstname'] == $billing['given_name']
                        and $address['lastname'] == $billing['family_name']
                        and $address['city'] == $billing['city']
                        and $address['address2'] == $billing['care_of']
                        and $address['other'] == $billing['attention']
                        and $address['company'] == $billing['organization_name']
                        and $address['address1'] == $billing['street_address']
                        and $address['postcode'] == $billing['postal_code']
                        and $address['phone_mobile'] == $billing['phone']
                        and $address['id_country'] == $invocie_country_id) {
                            //LOAD BILLING ADDRESS
                            $cart->id_address_invoice = $address['id_address'];
                            $invoice_address_id = $address['id_address'];
                        }
                    }

                    if ($invoice_address_id == 0) {
                        //Create address
                        $address = new Address();
                        $address->firstname = $this->module->truncateValue($billing['given_name'], 32, true);
                        $address->lastname = $this->module->truncateValue($billing['family_name'], 32, true);
                        if (isset($billing['care_of']) && Tools::strlen($billing['care_of']) > 0) {
                            $address->address1 = $billing['care_of'];
                            $address->address2 = $billing['street_address'];
                        } else {
                            $address->address1 = $billing['street_address'];
                        }

                        $address->postcode = $billing['postal_code'];
                        $address->phone = $billing['phone'];
                        $address->other = $billing['attention'];
                        $address->company = $billing['organization_name'];
                        $address->phone_mobile = $billing['phone'];
                        $address->city = $billing['city'];
                        $address->id_country = $invocie_country_id;
                        $address->id_customer = $customer->id;
                        
                        if (isset($shipping_state_id) && $shipping_state_id > 0) {
                            $address->id_state = $shipping_state_id;
                        }
                            
                        $address->alias = 'Klarna Address';
                        $address->add();
                        $cart->id_address_invoice = $address->id;
                        $invoice_address_id = $address->id;
                        if ($is_same) {
                            $delivery_address_id = $invoice_address_id;
                            $cart->id_address_delivery = $address->id;
                        }
                    }
                    if ($delivery_address_id == 0) {
                        //Create address
                        $address = new Address();
                        $address->firstname = $this->module->truncateValue($shipping['given_name'], 32, true);
                        $address->lastname = $this->module->truncateValue($shipping['family_name'], 32, true);

                        if (isset($shipping['care_of']) && Tools::strlen($shipping['care_of']) > 0) {
                            $address->address1 = $shipping['care_of'];
                            $address->address2 = $shipping['street_address'];
                        } else {
                            $address->address1 = $shipping['street_address'];
                        }

                        if ($shipping_state_id > 0) {
                            $address->id_state = $shipping_state_id;
                        }
                            
                        $address->city = $shipping['city'];
                        $address->postcode = $shipping['postal_code'];
                        $address->phone = $shipping['phone'];
                        $address->phone_mobile = $shipping['phone'];
                        $address->other = $shipping['attention'];
                        $address->company = $shipping['organization_name'];
                        $address->id_country = $shipping_country_id;
                        $address->id_customer = $customer->id;
                        $address->alias = 'Klarna Address';
                        $address->add();
                        $cart->id_address_delivery = $address->id;
                        $delivery_address_id = $address->id;
                    }

                    // $id_carrier_selected = (int) $checkout["selected_shipping_option"]["id"];
                    // $cart->id_carrier = $id_carrier_selected;
                    $new_delivery_options = array();
                    $new_delivery_options[(int) ($delivery_address_id)] = $cart->id_carrier.',';
                    // $new_delivery_options[(int) ($delivery_address_id)] = $id_carrier_selected.',';
                    // $new_delivery_options_serialized = serialize($new_delivery_options);
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
                    if ($cart->id_carrier > 0) {
                        $cart->delivery_option = $new_delivery_options_serialized;
                    } else {
                        $cart->delivery_option = '';
                    }
                    
                    $update_sql = 'UPDATE '._DB_PREFIX_.
                        'cart_product SET id_address_delivery='.
                        (int) $delivery_address_id.
                        ' WHERE id_cart='.
                        (int) $cart->id;
                        
                    Db::getInstance()->execute($update_sql);

                    $update_sql = 'UPDATE '._DB_PREFIX_.
                    'customization SET id_address_delivery='.
                    (int) $delivery_address_id.
                    ' WHERE id_cart='.
                    (int) $cart->id;
                    
                    Db::getInstance()->execute($update_sql);

                    $cart->getPackageList(true);
                    $cart->getDeliveryOptionList(null, true);
                    
                    $amount = (int) ($checkout['order_amount']);
                    $amount = (float) ($amount / 100);

                    $cart->id_customer = $customer->id;
                    $cart->secure_key = $customer->secure_key;
                    $cart->save();

                    $update_sql = 'UPDATE '._DB_PREFIX_.
                    'cart SET id_customer='.
                    (int) $customer->id.
                    ', secure_key=\''.
                    pSQL($customer->secure_key).
                    '\' WHERE id_cart='.
                    (int) $cart->id;
                    
                    Db::getInstance()->execute($update_sql);
                    
                    if (Configuration::get('KCO_ROUNDOFF') == 1) {
                        $total_cart_price_before_round = $cart->getOrderTotal(true, Cart::BOTH);
                        $total_cart_price_after_round = round($total_cart_price_before_round);
                        $diff = abs($total_cart_price_after_round - $total_cart_price_before_round);
                        if ($diff > 0) {
                            $amount = $total_cart_price_before_round;
                        }
                    }

                    $reference = pSQL($reference);
                    $merchantId = pSQL($merchantId);
                    
                    $extra = array();
                    $extra['transaction_id'] = $reference;
                    $cache_id = 'objectmodel_cart_'.$cart->id.'_0_0';
                    Cache::clean($cache_id);
                    $cart = new Cart($cart->id);

                    $id_shop = (int) $cart->id_shop;
                    
                    $sql = 'INSERT INTO `'._DB_PREFIX_.
                        "klarna_orders`(eid, id_order, id_cart, id_shop, ssn, invoicenumber,risk_status ,reservation) ".
                        "VALUES('$merchantId', 0, ".
                        (int) $cart->id.", $id_shop, '', '', '','$reference');";
                    Db::getInstance()->execute($sql);
                    
                    $this->module->validateOrder(
                        $cart->id,
                        Configuration::get('PS_OS_PAYMENT'),
                        number_format($amount, 2, '.', ''),
                        $this->module->displayName,
                        '',
                        $extra,
                        $cart->id_currency,
                        false,
                        $customer->secure_key
                    );

                    $order_reference = $this->module->currentOrder;
                    if (Configuration::get('KCO_ORDERID') == 1) {
                        $order = new Order((int) $this->module->currentOrder);
                        $order_reference = $order->reference;
                    }
                    $data = array(
                        'merchant_reference1' => ''.$order_reference,
                        'merchant_reference2' => ''.$cart->id,
                    );

                    $endpoint = '/ordermanagement/v1/orders/'.$reference.'/merchant-references';
                    $KlarnaCheckoutCommonFeatures->postToKlarna($data, $merchantId, $sharedSecret, $version, $endpoint, true);
                    
                    $endpoint = '/ordermanagement/v1/orders/'.$reference.'/acknowledge';
                    $KlarnaCheckoutCommonFeatures->postToKlarna($data, $merchantId, $sharedSecret, $version, $endpoint);
                    
                    $sql = 'UPDATE `'._DB_PREFIX_.
                        "klarna_orders` SET id_order=".
                        (int) $this->module->currentOrder.
                        " WHERE id_order=0 AND id_cart=".
                        (int) $cart->id;

                    Db::getInstance()->execute($sql);
                    
                    $result['id_order'] = $this->module->currentOrder;
                    $klarnaorder = $KlarnaCheckoutCommonFeatures->getFromKlarna($merchantId, $sharedSecret, $version, '/ordermanagement/v1/orders/'.$reference);
                    $klarnaorder = json_decode($klarnaorder, true);
                    if (isset($klarnaorder['fraud_status']) && $klarnaorder['fraud_status'] == "PENDING") {
                        $new_pending_status = Configuration::get('KCO_PENDING_PAYMENT');
                        $history = new OrderHistory();
                        $history->id_order = $this->module->currentOrder;
                        $history->changeIdOrderState((int)$new_pending_status, $this->module->currentOrder, true);
                        $templateVars = array();
                        $history->addWithemail(true, $templateVars);
                    }
                }
            }

            if (isset($result['id_order'])) {
                $payment_type_allows_increase = '';
                if (isset($checkout['payment_type_allows_increase']) &&
                1 === (int)$checkout['payment_type_allows_increase']) {
                    $payment_type_allows_increase = '&ptai=1';
                }
                
                $additional_checkboxes = array();
                if (isset($checkout['options']) && isset($checkout['options']['additional_checkboxes'])) {
                    foreach ($checkout['options']['additional_checkboxes'] as $additional_checkbox) {
                        $additional_checkboxes[$additional_checkbox['id']] = $additional_checkbox['text'];
                    }
                }
                if (isset($checkout['merchant_requested']) &&
                isset($checkout['merchant_requested']['additional_checkboxes'])) {
                    foreach ($checkout['merchant_requested']['additional_checkboxes'] as $additional_checkbox) {
                        if (isset($additional_checkboxes[$additional_checkbox['id']])) {
                            $text_at_time_of_purchase = pSQL($additional_checkboxes[$additional_checkbox['id']]);
                        } else {
                            $text_at_time_of_purchase = pSQL($additional_checkbox['id']);
                        }
                        $id_cart = (int) $cart->id;
                        $checked = (int) $additional_checkbox['checked'];
                        $sql = "INSERT INTO `"._DB_PREFIX_."klarna_checkbox`".
                        " (id_cart, text_at_time_of_purchase, checked)".
                        " VALUES($id_cart, '$text_at_time_of_purchase', $checked);";
                        Db::getInstance()->execute($sql);
                    }
                }
                
                unset($_SESSION['klarna_checkout_uk']);
                //If order is created, we can redirect to normal thankyou page.
                $order = new Order((int) $result['id_order']);
                $id_customer = $order->id_customer;
                $customer = new Customer((int)$id_customer);
                Tools::redirect(
                    'order-confirmation.php?key='.
                    $customer->secure_key.
                    '&kcotpv3=1'.
                    $payment_type_allows_increase.
                    '&klarna_order_id='.
                    $klarna_order_id.
                    '&id_cart='.
                    (int) ($checkout['merchant_reference2']).
                    '&id_module='.
                    $this->module->id
                );
            } else {
                //order was not created, show fake confirmation and unload cart
                $cart = $this->context->cart;
                unset($this->context->cookie->id_cart, $cart, $this->context->cart);
                $this->context->cart = new Cart();
                
                $this->context->smarty->assign(array(
                    'klarna_html' => $snippet,
                    'cart_qties' => 0,
                    'cart' => $this->context->cart
                ));
                unset($_SESSION['klarna_checkout_uk']);
            }
        } catch (Exception $e) {
            //var_dump($e);
            $this->context->smarty->assign('klarna_error', $e->getMessage());
        }

        $this->setTemplate('kco_thankyoupage.tpl');
    }

    public function displayOrderConfirmation($id_order)
    {
        if (Validate::isUnsignedId($id_order)) {
            $params = array();
            $order = new Order($id_order);
            $currency = new Currency($order->id_currency);

            if (Validate::isLoadedObject($order)) {
                $params['total_to_pay'] = $order->getOrdersTotalPaid();
                $params['currency'] = $currency->sign;
                $params['objOrder'] = $order;
                $params['currencyObj'] = $currency;

                return Hook::exec('displayOrderConfirmation', $params);
            }
        }

        return false;
    }
    
    protected function sendConfirmationMail($customer, $id_lang, $psw)
    {
        if (!Configuration::get('PS_CUSTOMER_CREATION_EMAIL')) {
            return true;
        }
        try {
            return Mail::Send(
                $id_lang,
                'account',
                Mail::l('Welcome!', $id_lang),
                array(
                    '{firstname}' => $customer->firstname,
                    '{lastname}' => $customer->lastname,
                    '{email}' => $customer->email,
                    '{passwd}' => $psw
                ),
                $customer->email,
                $customer->firstname.' '.$customer->lastname
            );
        } catch (Exception $e) {
            Logger::addLog('Klarna Checkout: '.htmlspecialchars($e->getMessage()), 1, null, null, null, true);

            return false;
        }
    }
}
