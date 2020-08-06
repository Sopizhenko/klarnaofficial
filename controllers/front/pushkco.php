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
 
class KlarnaOfficialPushKcoModuleFrontController extends ModuleFrontController
{
    public $display_column_left = false;
    public $display_column_right = false;
    public $ssl = true;

    public function postProcess()
    {
        if (!Tools::getIsset('klarna_order_id')) {
            Logger::addLog('KCO V3: bad push by:'.Tools::getRemoteAddr(), 1, null, null, null, true);
            die('missing parameters');
        }
        //$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        //Logger::addLog($url, 1, null, null, null, true);

        try {
            $merchantId = Configuration::get('KCOV3_MID');
            $sharedSecret = Configuration::get('KCOV3_SECRET');
            
            require_once dirname(__FILE__).'/../../libraries/commonFeatures.php';
            $KlarnaCheckoutCommonFeatures = new KlarnaCheckoutCommonFeatures();
            $version = $this->module->version;
            $klarna_order_id = pSQL(Tools::getValue('klarna_order_id'));
            
            $checkout = $KlarnaCheckoutCommonFeatures->getFromKlarna($merchantId, $sharedSecret, $version, '/checkout/v3/orders/'.$klarna_order_id);
            $checkout = json_decode($checkout, true);
            
            if ($checkout['status'] == 'checkout_complete') {
                $id_cart = $checkout['merchant_reference2'];
                $cart = new Cart((int) ($id_cart));

                Context::getContext()->currency = new Currency((int) $cart->id_currency);

                if ($cart->OrderExists()) {
                    
                    $sql = 'SELECT m.transaction_id, o.id_order FROM `'._DB_PREFIX_.
                    'order_payment` m LEFT JOIN `'._DB_PREFIX_.
                    'orders` o ON m.order_reference=o.reference WHERE o.id_cart='.(int) ($id_cart);
                    
                    $messages = Db::getInstance()->ExecuteS($sql);
                    foreach ($messages as $message) {
                        //Check if reference matches
                        if ($message['transaction_id']==$klarna_order_id) {
                            //Already created, send create
                            $order_reference = (int) $message['id_order'];
                            if (Configuration::get('KCO_ORDERID') == 1) {
                                $order = new Order($order_reference);
                                $order_reference = $order->reference;
                            }
                            $data = array(
                                'merchant_reference1' => ''.$order_reference,
                                'merchant_reference2' => ''.(int) $id_cart,
                            );

                            $endpoint = '/ordermanagement/v1/orders/'.$klarna_order_id.'/merchant-references';
                            $KlarnaCheckoutCommonFeatures->postToKlarna($data, $merchantId, $sharedSecret, $version, $endpoint);
                            
                            $endpoint = '/ordermanagement/v1/orders/'.$klarna_order_id.'/acknowledge';
                            $update = $KlarnaCheckoutCommonFeatures->postToKlarna($data, $merchantId, $sharedSecret, $version, $endpoint, true);
                            $update = json_decode($update, true);
                            Logger::addLog(
                                'KCO: created sent: '.$id_cart.' res:'.$klarna_order_id,
                                1,
                                null,
                                null,
                                null,
                                true
                            );
                            die;
                        }
                    }
                    //Duplicate reservation, cancel reservation.
                    Logger::addLog(
                        'KCO: Duplicate reservation: id_cart:'.$id_cart.' res:'.$klarna_order_id,
                        1,
                        null,
                        null,
                        null,
                        true
                    );
                } else {
                    //Create the order
                    $shipping = $checkout['shipping_address'];
                    $billing = $checkout['billing_address'];

                    if (!Validate::isEmail($shipping['email'])) {
                        $shipping['email'] = 'ingen_mejl_'.$id_cart.'@ingendoman.cc';
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

                    $id_customer = (int) (Customer::customerExists($shipping['email'], true, true));
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
                        $customer->firstname = $this->module->truncateValue($shipping['given_name'], 32, true);
                        $customer->lastname = $this->module->truncateValue($shipping['family_name'], 32, true);
                        $customer->email = $shipping['email'];
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
                    // $shipping_iso = $country_iso_codes[$shipping['country']];
                    // $invocie_iso = $country_iso_codes[$billing['country']];
                    $invocie_iso = Tools::strtoupper($billing['country']);
                    $shipping_country_id = Country::getByIso($shipping_iso);
                    $invocie_country_id = Country::getByIso($invocie_iso);

                    if (!isset($shipping['care_of'])) {
                        $shipping['care_of'] = "";
                    }
                    if (!isset($billing['care_of'])) {
                        $billing['care_of'] = "";
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
                        and $address['phone_mobile'] == $shipping['phone']
                        and $address['other'] == $shipping['attention']
                        and $address['company'] == $shipping['organization_name']
                        and $address['id_country'] == $shipping_country_id) {
                            //LOAD SHIPPING ADDRESS
                            $cart->id_address_delivery = $address['id_address'];
                            $delivery_address_id = $address['id_address'];
                        }
                        if ($address['firstname'] == $billing['given_name']
                        and $address['lastname'] == $billing['family_name']
                        and $address['city'] == $billing['city']
                        and $address['address2'] == $billing['care_of']
                        and $address['address1'] == $billing['street_address']
                        and $address['postcode'] == $billing['postal_code']
                        and $address['phone_mobile'] == $billing['phone']
                        and $address['other'] == $billing['attention']
                        and $address['company'] == $billing['organization_name']
                        and $address['id_country'] == $invocie_country_id) {
                            //LOAD SHIPPING ADDRESS
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
                        $address->other = $billing['attention'];
                        $address->company = $billing['organization_name'];
                        $address->phone = $billing['phone'];
                        $address->phone_mobile = $billing['phone'];
                        $address->city = $billing['city'];
                        $address->id_country = $invocie_country_id;
                        $address->id_customer = $customer->id;
                        $address->alias = 'Klarna Address';
                        $address->add();
                        $cart->id_address_invoice = $address->id;
                        $invoice_address_id = $address->id;
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

                    $new_delivery_options = array();
                    $new_delivery_options[(int) ($delivery_address_id)] = $cart->id_carrier.',';
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

                    $merchantId = pSQL($merchantId);
                    
                    $extra = array();
                    $extra['transaction_id'] = $klarna_order_id;
                    $cache_id = 'objectmodel_cart_'.$cart->id.'_0_0';
                    Cache::clean($cache_id);
                    $cart = new Cart($cart->id);

                    $id_shop = (int) $cart->id_shop;
                    
                    $sql = 'INSERT INTO `'._DB_PREFIX_.
                        "klarna_orders`(eid, id_order, id_cart, id_shop, ssn, invoicenumber,risk_status ,reservation) ".
                        "VALUES('$merchantId', 0, ".
                        (int) $cart->id.", $id_shop, '', '', '', '$klarna_order_id');";
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
                        'merchant_reference2' => ''.(int) $cart->id,
                    );

                    $endpoint = '/ordermanagement/v1/orders/'.$klarna_order_id.'/merchant-references';
                    $KlarnaCheckoutCommonFeatures->postToKlarna($data, $merchantId, $sharedSecret, $version, $endpoint, true);
                    
                    $endpoint = '/ordermanagement/v1/orders/'.$klarna_order_id.'/acknowledge';
                    $KlarnaCheckoutCommonFeatures->postToKlarna($data, $merchantId, $sharedSecret, $version, $endpoint);
                    
                    $klarnaorder = $KlarnaCheckoutCommonFeatures->getFromKlarna($merchantId, $sharedSecret, $version, '/ordermanagement/v1/orders/'.$klarna_order_id);
                    $klarnaorder = json_decode($klarnaorder, true);

                    if (isset($klarnaorder['fraud_status']) && $klarnaorder['fraud_status'] == "PENDING") {
                        $new_pending_status = Configuration::get('KCO_PENDING_PAYMENT');
                        $history = new OrderHistory();
                        $history->id_order = $this->module->currentOrder;
                        $history->changeIdOrderState((int)$new_pending_status, $this->module->currentOrder, true);
                        $templateVars = array();
                        $history->addWithemail(true, $templateVars);
                    }
                    
                    $sql = 'UPDATE `'._DB_PREFIX_.
                        "klarna_orders` SET id_order=".
                        (int) $this->module->currentOrder.
                        " WHERE id_order=0 AND id_cart=".
                        (int) $cart->id;

                    Db::getInstance()->execute($sql);
                    
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
                }
            }
        } catch (Exception $e) {
            Logger::addLog('Klarna Checkout: '.htmlspecialchars($e->getMessage()), 1, null, null, null, true);
        }
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
