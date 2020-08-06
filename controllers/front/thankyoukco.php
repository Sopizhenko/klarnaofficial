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
        if (version_compare(phpversion(), '5.4.0', '>=')) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        } else {
            if (session_id() === '') {
                session_start();
            }
        }
        if (!Tools::getIsset('klarna_order_id')) {
            Tools::redirect('index.php');
        }
        try {
            $merchantId = Configuration::get('KCOV3_MID');
            $sharedSecret = Configuration::get('KCOV3_SECRET');
            require_once dirname(__FILE__).'/../../libraries/commonFeatures.php';
            $KlarnaCheckoutCommonFeatures = new KlarnaCheckoutCommonFeatures();
            $version = $this->module->version;
            $klarna_order_id = pSQL(Tools::getValue('klarna_order_id'));
            
            $checkout = $KlarnaCheckoutCommonFeatures->getFromKlarna($merchantId, $sharedSecret, $version, '/checkout/v3/orders/'.$klarna_order_id);
            $checkout = json_decode($checkout, true);
            
            $snippet = $checkout['html_snippet'];

            if ($checkout['status'] == 'checkout_incomplete') {
                $url = Context::getContext()->link->getModuleLink($this->module->name, 'checkoutklarnakco', array(), true);
                Tools::redirect($url);
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
                    $cart = new Cart((int) ($id_cart));
                    
                    if ($id_order > 0) {
                        //ORDER ALREADY EXIST
                    }
                    
                    Context::getContext()->currency = new Currency((int) $cart->id_currency);

                    $reference = Tools::getValue('klarna_order_id');
                    // $klarna_reservation = Tools::getValue('klarna_order_id');
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

                    if (0 == (int)$cart->id_customer) {
                        $id_customer = (int) (Customer::customerExists($shipping['email'], true, true));
                    } else {
                        $id_customer = (int)$cart->id_customer;
                    }
                    if ($id_customer > 0) {
                        $customer = new Customer($id_customer);
                        if ($newsletter == 1) {
                            $sql_update_customer = "UPDATE "._DB_PREFIX_."customer SET newsletter=1".
                            " WHERE id_customer=$id_customer;";
                            Db::getInstance()->execute(pSQL($sql_update_customer));
                        }
                    } else {
                        $id_gender = 9;
                        $date_of_birth = "";
                        $customer = $this->module->createNewCustomer(
                            $shipping['given_name'],
                            $shipping['family_name'],
                            $shipping['email'],
                            $newsletter,
                            $id_gender,
                            $date_of_birth,
                            $cart
                        );
                    }

                    $this->module->changeAddressOnKCOCart($shipping, $billing, $customer, $cart);

                    $amount = (int) ($checkout['order_amount']);
                    $amount = (float) ($amount / 100);
        
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

                    $id_shop = (int) $cart->id_shop;
                    
                    
                    
                    $sql = 'INSERT INTO `'._DB_PREFIX_.
                        "klarna_orders`(eid, id_order, id_cart, id_shop, ssn, invoicenumber,risk_status ,reservation) ".
                        "VALUES('$merchantId', 0, ".
                        (int) $cart->id.", $id_shop, '', '', '','$reference');";
                    Db::getInstance()->execute($sql);
                    
                    Hook::exec('actionKlarnaOrderDone', array('klarnadata' => $checkout));
                    
                    $cache_id = 'objectmodel_cart_'.$cart->id.'_*';
                    Cache::clean($cache_id);
                    Cache::clean('getContextualValue*');
                    Cache::clean('getPackageShippingCost_'.$cart->id.'_*');
                    $cart = new Cart($cart->id);
                    if (version_compare(_PS_VERSION_, '1.7.2.5', '>')) {
                        $cart->resetStaticCache();
                    }
                    $cart->getDeliveryOptionList(null, true);
                    $cart->getDeliveryOption(null, false, true);
                    $this->context->cart->getDeliveryOption(null, false, false);
                    $this->context->cart->getDeliveryOptionList(null, true);
                    if (version_compare(_PS_VERSION_, '1.7.2.5', '>')) {
                        $this->context->cart->resetStaticCache();
                    }

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
                        $sql = "INSERT INTO `"._DB_PREFIX_.
                        "klarna_checkbox` (id_cart, text_at_time_of_purchase, checked)".
                        " VALUES($id_cart, '$text_at_time_of_purchase', $checked);";
                        Db::getInstance()->execute($sql);
                    }
                }
                
                $payment_type_allows_increase = '';
                if (isset($checkout['payment_type_allows_increase']) &&
                1 === (int)$checkout['payment_type_allows_increase']) {
                    $payment_type_allows_increase = '&ptai=1';
                    $payment_type_allows_increase .= '&klarna_order_id='.Tools::getValue('klarna_order_id');
                }
                
                //If order is created, we can redirect to normal thankyou page.
                $order = new Order((int) $result['id_order']);
                $id_customer = $order->id_customer;
                $customer = new Customer((int)$id_customer);
                Tools::redirect(
                    'order-confirmation.php?key='.
                    $customer->secure_key.
                    '&kcotpv3=1'.
                    $payment_type_allows_increase.
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
                
                Hook::exec('actionKlarnaOrderDone', array('klarnadata' => $checkout));
            }
        } catch (Exception $e) {
            echo $e->getTraceAsString();
            $this->context->smarty->assign('klarna_error', $e->getMessage());
        }

        $this->setTemplate('module:klarnaofficial/views/templates/front/kco_thankyoupage.tpl');
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
}
