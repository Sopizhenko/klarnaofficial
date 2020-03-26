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

use Symfony\Component\Translation\TranslatorInterface;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\ObjectPresenter;

class KlarnaOfficialCheckoutKlarnaKcoModuleFrontController extends ModuleFrontController
{
    public $display_column_left = false;
    public $display_column_right = false;
    public $ssl = true;
    public $current_kco = 'UKNL';

    public function setMedia()
    {
        parent::setMedia();
        $this->context->controller->addCSS(_MODULE_DIR_.'klarnaofficial/views/css/klarnacheckout.css', 'all');
        $this->addJS(_MODULE_DIR_.'klarnaofficial/views/js/klarna_checkout.js');
    }

    public function postProcess()
    {
        if (version_compare(phpversion(), '5.4.0', '>=')) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        } else {
            if (session_id() === '') {
                session_start();
            }
        }
        
        require_once dirname(__FILE__).'/../../libraries/kcocommonpostprocess.php';
    }
    public function initContent()
    {
        $ssid = 'gb';
        $eid = '';
        $sharedSecret = '';
        parent::initContent();
        
        $checkout_url = $this->context->link->getModuleLink(
            'klarnaofficial',
            'checkoutklarnakco',
            array('sid' => $ssid),
            true
        );
        
        $checkout_url_v2 = $this->context->link->getModuleLink(
            'klarnaofficial',
            'checkoutklarna',
            array(),
            true
        );
        
        $checkSQL = "SELECT COUNT(id_address_delivery) FROM "._DB_PREFIX_."cart_product WHERE id_cart=".
        (int) $this->context->cart->id. " AND id_address_delivery <> ".(int) $this->context->cart->id_address_delivery;
        $finds = Db::getInstance()->getValue($checkSQL);
        if ($finds > 0) {
            $update_sql = 'UPDATE '._DB_PREFIX_.'cart_product '.
                'SET id_address_delivery='.(int) $this->context->cart->id_address_delivery.
                ' WHERE id_cart='.(int) $this->context->cart->id;
            Db::getInstance()->execute($update_sql);
            if (Configuration::get('KCOV3')) {
                Tools::redirect($checkout_url);
                // Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarnakco');
            } else {
                Tools::redirect($checkout_url_v2);
                // Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarna');
            }
        }
        
        

        if (!$this->context->cart->getDeliveryOption(null, true)) {
            $this->context->cart->setDeliveryOption($this->context->cart->getDeliveryOption());
        }
        
        $checkValue = Tools::jsonDecode($this->context->cart->delivery_option, true);
        if ($this->context->cart->delivery_option != "" &&
            $checkValue !== false &&
            $checkValue !== null &&
            (int)$this->context->cart->id_address_delivery > 0
        ) {
            if (!isset($checkValue[(int)$this->context->cart->id_address_delivery])) {
                $this->context->cart->delivery_option = "";
                $this->context->cart->update();
                
                $update_sql = 'UPDATE '._DB_PREFIX_.'cart_product '.
                    'SET id_address_delivery='.(int)$this->context->cart->id_address_delivery.
                    ' WHERE id_cart='.(int) $this->context->cart->id;
                    
                Db::getInstance()->execute($update_sql);

                $update_sql = 'UPDATE '._DB_PREFIX_.'customization '.
                    'SET id_address_delivery='.(int)$this->context->cart->id_address_delivery.
                    ' WHERE id_cart='.(int) $this->context->cart->id;
                    
                Db::getInstance()->execute($update_sql);
                
                Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarna');
            }
        }
        
        //Make a check on reload
        CartRule::autoRemoveFromCart($this->context);
        CartRule::autoAddToCart($this->context);
        
        $checkoutcart = array();
        $create  = array();
        
        if (Tools::getIsset('kco_update') and Tools::getValue('kco_update') == '1') {
            if (!$this->context->cart->checkQuantities()) {
                die;
            }
            if ($this->context->cart->nbProducts() < 1) {
                die;
            }
        }

        if (!isset($this->context->cart->id)) {
            Tools::redirect('index.php');
        }

        $currency = new Currency($this->context->cart->id_currency);
        $language = new Language($this->context->cart->id_lang);

        $country_information = $this->module->getKlarnaCountryInformation($currency->iso_code, $language->iso_code);
        require_once dirname(__FILE__).'/../../libraries/kcocommonredirectcheck.php';
        require_once dirname(__FILE__).'/../../libraries/commonFeatures.php';

        $KlarnaCheckoutCommonFeatures = new KlarnaCheckoutCommonFeatures();
        if (Configuration::get('KCOV3')) {
            $eid = Configuration::get('KCOV3_MID');
            $sharedSecret = Configuration::get('KCOV3_SECRET');
        }
        $shipping_options = array();
        $carrieraddress = new Address($this->context->cart->id_address_delivery);
        
        foreach ($this->context->cart->getDeliveryOptionList() as $options) {
            foreach ($options as $option) {
                foreach ($option["carrier_list"] as $carrieroption) {
                    $carrierobject = $carrieroption["instance"];
                    $shipping_option = array();
                    $shipping_option["id"] = $carrierobject->id;
                    if ($this->context->cart->id_carrier == $carrierobject->id) {
                        $shipping_option["preselected"] = true;
                    } else {
                        $shipping_option["preselected"] = false;
                    }
                    $shipping_option["name"] = $carrierobject->name;
                    $shipping_option["description"] = $carrierobject->delay[(int)$this->context->cart->id_lang];
                    $shipping_option["price"] = $option["total_price_with_tax"] * 100;
                    $taxamount = (($option["total_price_with_tax"] - $option["total_price_without_tax"]) * 100);
                    $shipping_option["tax_amount"] = $taxamount;
                    $shipping_tax_rate = $carrierobject->getTaxesRate($carrieraddress);
                    $shipping_option["tax_rate"] = $shipping_tax_rate*100;
                    $shipping_options[] = $shipping_option;
                }
            }
        }

        $layout = 'desktop';
        if (Context::getContext()->getDevice() == Context::DEVICE_MOBILE) {
            $layout = 'mobile';
        }

        if (isset($this->context->cart) and $this->context->cart->nbProducts() > 0) {
            if (!$this->context->cart->checkQuantities()) {
                Tools::redirect('index.php?controller=order&step=1');
            } else {
                $minimal_purchase = Tools::convertPrice((float)Configuration::get('PS_PURCHASE_MINIMUM'), $currency);
                if ($this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS) < $minimal_purchase) {
                    Tools::redirect('index.php?controller=order&step=1');
                }
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
                    $this->module->getL('Discount'),
                    true
                );
                
                $callbackPage = $this->context->link->getModuleLink(
                    'klarnaofficial',
                    'thankyoukco',
                    array('sid' => $ssid)
                );
                $callbackPage .= '&klarna_order_id={checkout.order.id}';

                $notification_url = $this->context->link->getModuleLink(
                    'klarnaofficial',
                    'notification',
                    array()
                );
                $pushPage = $this->context->link->getModuleLink(
                    'klarnaofficial',
                    'pushkco',
                    array('sid' => $ssid)
                );
                $pushPage .= '&klarna_order_id={checkout.order.id}';

                $checkout_url .= '&klarna_order_id={checkout.order.id}';

                $cms = new CMS(
                    (int) (Configuration::get('KCO_TERMS_PAGE')),
                    (int) ($this->context->cookie->id_lang)
                );
                $cms2 = new CMS(
                    (int) (Configuration::get('KCO_CANCEL_PAGE')),
                    (int) ($this->context->cookie->id_lang)
                );
                $termsPage = $this->context->link->getCMSLink($cms, $cms->link_rewrite, true);
                $link_cancelation = $this->context->link->getCMSLink($cms2, $cms2->link_rewrite, true);
                $country_change_url = $this->context->link->getModuleLink(
                    'klarnaofficial',
                    'changeaddress',
                    array('sid' => $ssid, 'cartid' => (int) $this->context->cart->id),
                    true
                );
                
                $country_change_url .= '&klarna_order_id={checkout.order.id}';
                
                $shipping_option_update_url = $this->context->link->getModuleLink(
                    'klarnaofficial',
                    'changecarrier',
                    array('sid' => $ssid, 'cartid' => (int) $this->context->cart->id),
                    true
                );
                $shipping_option_update_url .= '&klarna_order_id={checkout.order.id}';
                
                $shipping_countries = array();
                foreach (Country::getCountries($this->context->cookie->id_lang, true, false, false) as $country) {
                    $shipping_countries[] = $country["iso_code"];
                }
                $create['shipping_countries'] = $shipping_countries;
                $create['shipping_options'] = $shipping_options;
                
                try {
                    if (version_compare(phpversion(), '5.4.0', '<')) {
                        $this->context->smarty->assign('klarna_error', 'PHP 5.4 Required');
                    } else {
                        require_once dirname(__FILE__).'/../../libraries/KCOUK/autoload.php';
                        $connector = $KlarnaCheckoutCommonFeatures->getConnector(
                            $ssid,
                            $eid,
                            $sharedSecret,
                            (int) (Configuration::get('KCO_TESTMODE')),
                            $this->module->version
                        );

                        $checkout = new \Klarna\Rest\Checkout\Order($connector);

                        $totalCartValue = $this->context->cart->getOrderTotal(true, Cart::BOTH);
                        // $totalCartValue_tax_excl = $this->context->cart->getOrderTotal(false, Cart::BOTH);
                        // $total_tax_value = $totalCartValue - $totalCartValue_tax_excl;
                        $total_tax_value = 0;
                        
                        $create['purchase_country'] = $country_information['purchase_country'];
                        $create['purchase_currency'] = $country_information['purchase_currency'];
                        $create['locale'] = $country_information['locale'];
                        // $create['order_amount'] = $totalCartValue * 100;
                        $create['order_amount'] = $this->module->fixPrestashopRoundingIssues($totalCartValue, 100, 0);
                        
                        // $create['order_tax_amount'] = $total_tax_value * 100;
                       
                        if (0 == (int) Configuration::get('KCO_AUTOFOCUS')) {
                            $create['gui']['options'] = array('disable_autofocus');
                        }
                        if (1 == (int) Configuration::get('KCO_SHOW_SHIPDETAILS')) {
                            if (isset($this->context->cart) && (int)$this->context->cart->id_carrier > 0) {
                                $carrier = new Carrier((int)$this->context->cart->id_carrier);
                                $create['options']['shipping_details'] = $carrier->delay[$this->context->language->id];
                            }
                        }
                        if (1 == (int) Configuration::get('KCO_CALLBACK_CHECK')) {
                            $create['options']['require_validate_callback_success'] = true;
                        }
                        if (Configuration::get('KCO_ALLOWSEPADDR')) {
                            $create['options']['allow_separate_shipping_address'] = true;
                        }
                        if (Configuration::get('KCO_FORCEPHONE')) {
                            $create['options']['phone_mandatory'] = true;
                        }
                        if (Configuration::get('KCOV3_EXTERNAL_PAYMENT_METHOD_ACTIVE')) {
                            if (1 == (int) Configuration::get('KCOV3_EXTERNAL_PAYMENT_METHOD_LABEL')) {
                                $KCOV3_EXTERNAL_PAYMENT_METHOD_LABEL = 'continue';
                            } else {
                                $KCOV3_EXTERNAL_PAYMENT_METHOD_LABEL = 'complete';
                            }
                            
                            $KCOV3_EPM_DESC_JSON = Configuration::get('KCOV3_EXTERNAL_PAYMENT_METHOD_DESC');
                            $KCOV3_EPM_DESC = Tools::jsonDecode($KCOV3_EPM_DESC_JSON, true);
                            $KCOV3_EPM_DESC = $KCOV3_EPM_DESC[(int) $this->context->language->id];
                            
                            if ("" != Configuration::get('KCOV3_EXTERNAL_PAYMENT_METHOD_EXTERNALURL')) {
                                $original_checkout_url = Configuration::get('KCOV3_EXTERNAL_PAYMENT_METHOD_EXTERNALURL');
                            } else {
                                $original_checkout_url = $this->context->link->getPageLink('order');
                            }
                            
                            $external_payment_method = array(
                                'name' => Configuration::get('KCOV3_EXTERNAL_PAYMENT_METHOD_OPTION'),
                                'redirect_url' => $original_checkout_url,
                                'image_url' => Configuration::get('KCOV3_EXTERNAL_PAYMENT_METHOD_IMGURL'),
                                'description' => $KCOV3_EPM_DESC,
                                'label' => $KCOV3_EXTERNAL_PAYMENT_METHOD_LABEL,
                            );
                            
                            if ("" != Configuration::get('KCOV3_EXTERNAL_PAYMENT_METHOD_COUNTRIES')) {
                                $KCOV3_EPM_COUNTRIES = Configuration::get('KCOV3_EXTERNAL_PAYMENT_METHOD_COUNTRIES');
                                $KCOV3_EPM_COUNTRIES = explode(',', $KCOV3_EPM_COUNTRIES);
                                $external_payment_method["countries"] = $KCOV3_EPM_COUNTRIES;
                            }
                            if (Configuration::get('KCOV3_EXTERNAL_PAYMENT_METHOD_FEE') > 0) {
                                $KCOV3_EPM_FEE = (int) Configuration::get('KCOV3_EXTERNAL_PAYMENT_METHOD_FEE');
                                $external_payment_method["fee"] = $KCOV3_EPM_FEE;
                            }
                            $external_payment_methods = array();
                            $external_payment_methods[] = $external_payment_method;
                            $create['external_payment_methods'] = $external_payment_methods;
                        }

                        $create['gui']['layout'] = $layout;
                        $create['merchant_urls']['terms'] = $termsPage;
                        $create['merchant_urls']['cancellation_terms'] = $link_cancelation;
                        $create['merchant_urls']['checkout'] = $checkout_url;
                        $create['merchant_urls']['confirmation'] = $callbackPage;
                        $create['merchant_urls']['notification'] = $notification_url;
                        $create['merchant_urls']['country_change'] = $country_change_url;
                        $create['merchant_urls']['shipping_option_update'] = $shipping_option_update_url;
                        $create['merchant_urls']['push'] = $pushPage;
                        if (Configuration::get('KCO_CALLBACK_CHECK') == 1) {
                            $create['merchant_urls']['validation'] = ''.
                            $this->context->link->getModuleLink(
                                $this->module->name,
                                'callbackvalidation',
                                array('v3' => 1),
                                true
                            );
                        }
                        
                        $create['merchant_reference2'] = ''.(int) ($this->context->cart->id);
                        if ((int)Configuration::get('KCO_ADD_NEWSLETTERBOX') == 0) {
                            $create['options']['additional_checkbox']['text'] = ''.
                            $this->module->getL('Subscribe to our newsletter.');
                            $create['options']['additional_checkbox']['checked'] = false;
                            $create['options']['additional_checkbox']['required'] = false;
                        } elseif ((int)Configuration::get('KCO_ADD_NEWSLETTERBOX') == 1) {
                            $create['options']['additional_checkbox']['text'] = ''.
                            $this->module->getL('Subscribe to our newsletter.');
                            $create['options']['additional_checkbox']['checked'] = true;
                            $create['options']['additional_checkbox']['required'] = false;
                        }
                        
                        if (1 == (int)Configuration::get('KCOV3_CUSTOM_CHECKBOX')) {
                            $json_encoded_string = Configuration::get('KCOV3_CUSTOM_CHECKBOX_TEXT');
                            $text_array = Tools::jsonDecode($json_encoded_string, true);
                            $custom_textbox_text = $text_array[(int) $this->context->language->id];
                            $additional_checkbox = array (
                                'id' => 'customcheckbox',
                                'checked' => (bool) Configuration::get('KCOV3_CUSTOM_CHECKBOX_PRECHECKED'),
                                'required' => (bool) Configuration::get('KCOV3_CUSTOM_CHECKBOX_REQUIRED'),
                                'text' => $custom_textbox_text
                            );
                            $create['options']['additional_checkboxes'][] = $additional_checkbox;
                        }
                        
                        
                        
                        if (Configuration::get('KCO_COLORBUTTON') != '') {
                            $create['options']['color_button'] = ''.Configuration::get('KCO_COLORBUTTON');
                        }
                        if (Configuration::get('KCO_COLORBUTTONTEXT') != '') {
                            $create['options']['color_button_text'] = ''.Configuration::get('KCO_COLORBUTTONTEXT');
                        }
                        if (Configuration::get('KCO_COLORCHECKBOX') != '') {
                            $create['options']['color_checkbox'] = ''.Configuration::get('KCO_COLORCHECKBOX');
                        }
                        if (Configuration::get('KCO_COLORCHECKBOXMARK') != '') {
                            $create['options']['color_checkbox_checkmark'] = ''.
                            Configuration::get('KCO_COLORCHECKBOXMARK');
                        }
                        if (Configuration::get('KCO_COLORHEADER') != '') {
                            $create['options']['color_header'] = ''.Configuration::get('KCO_COLORHEADER');
                        }
                        if (Configuration::get('KCO_COLORLINK') != '') {
                            $create['options']['color_link'] = ''.Configuration::get('KCO_COLORLINK');
                        }
                        if (Configuration::get('KCO_RADIUSBORDER') != '') {
                            $create['options']['radius_border'] = ''.Configuration::get('KCO_RADIUSBORDER');
                        }
                        if (Configuration::get('KCO_DOBMAN')) {
                            $create['options']['date_of_birth_mandatory'] = true;
                        }
                        if (Configuration::get('KCO_TITLEMAN')) {
                            $create['options']['title_mandatory'] = true;
                        }
                        if (Configuration::get('KCO_NIN_MANDATORY')) {
                            $create['options']['national_identification_number_mandatory'] = true;
                        }
                        if (Configuration::get('KCO_SHOW_SUBTOT')) {
                            $create['options']['show_subtotal_detail'] = true;
                        }
                        
                        if (1 == (int)Configuration::get('KCO_ALLOWED_TYPES')) {
                            $create['options']['allowed_customer_types'] = array("person");
                        } elseif (2 == (int)Configuration::get('KCO_ALLOWED_TYPES')) {
                            $create['options']['allowed_customer_types'] = array("organization");
                        } else {
                            $create['options']['allowed_customer_types'] = array("person", "organization");
                        }

                        if (Configuration::get('KCO_PREFILL')) {
                            if ($this->context->customer->isLogged()) {
                                $address_delivery = new Address((int)$this->context->cart->id_address_delivery);
                                if ($address_delivery->id_customer == $this->context->cart->id_customer) {
                                    /*PREFILL CUSTOMER INFO*/
                                    $create['shipping_address']['given_name'] = $address_delivery->firstname;
                                    $create['shipping_address']['family_name'] = $address_delivery->lastname;
                                    $create['shipping_address']['email'] = $this->context->customer->email;
                                    $create['shipping_address']['street_address'] = $address_delivery->address1;
                                    $create['shipping_address']['care_of'] = $address_delivery->address2;
                                    $create['shipping_address']['postal_code'] = $address_delivery->postcode;
                                    $create['shipping_address']['city'] = $address_delivery->city;
                                    $create['shipping_address']['phone'] = $address_delivery->phone_mobile;
                                    $delivCountry = new Country((int)$address_delivery->id_country);
                                    $create['shipping_address']['country'] = $delivCountry->iso_code;
                                    
                                    if ((int)$address_delivery->id_state > 0) {
                                        $delivState = new State((int)$address_delivery->id_state);
                                        $create['shipping_address']['region'] = $delivState->iso_code;
                                    }
                                    
                                    $id_address_invoice = $this->context->cart->id_address_invoice;
                                    $id_address_delivery = $this->context->cart->id_address_delivery;
                                    if ($id_address_invoice == $id_address_delivery) {
                                        $create['billing_address'] = $create['shipping_address'];
                                    } else {
                                        $address_invoice = new Address((int)$this->context->cart->id_address_invoice);
                                        if ($address_invoice->id_customer == $this->context->cart->id_customer) {
                                            $create['billing_address']['given_name'] = $address_invoice->firstname;
                                            $create['billing_address']['family_name'] = $address_invoice->lastname;
                                            $create['billing_address']['email'] = $this->context->customer->email;
                                            $create['billing_address']['street_address'] = $address_invoice->address1;
                                            $create['billing_address']['care_of'] = $address_invoice->address2;
                                            $create['billing_address']['postal_code'] = $address_invoice->postcode;
                                            $create['billing_address']['city'] = $address_invoice->city;
                                            $create['billing_address']['phone'] = $address_invoice->phone_mobile;
                                            $billingCountry = new Country((int)$address_invoice->id_country);
                                            $create['billing_address']['country'] = $billingCountry->iso_code;
                                            
                                            if ((int)$address_delivery->id_state > 0) {
                                                $billingState = new State((int)$address_invoice->id_state);
                                                $create['billing_address']['region'] = $billingState->iso_code;
                                            }
                                        }
                                    }
                                    /*PREFILL CUSTOMER INFO*/
                                }
                            }
                        }
                        foreach ($checkoutcart as $item) {
                            $create['order_lines'][] = $item;
                            $total_tax_value += $item['total_tax_amount'];
                        }
                        
                        if ($total_tax_value < 0) {
                            //Total tax can never be negative.
                            $total_tax_value = 0;
                        }
                        $create['order_tax_amount'] = $total_tax_value;
                        
                        // echo "------------".$eid;
                        // echo "<pre>";print_r($create);echo "</pre>";
                         
                        $this->assignSmartyVars($ssid, $country_information);
                        
                        Hook::exec('actionKlarnaBeforeSendData', array('create' => &$create));
                        
                        if (!isset($_SESSION['klarna_checkout_uk'])) {
                            $checkout->create($create);
                            $checkout->fetch();
                            $_SESSION['klarna_checkout_uk'] = $checkout['order_id'];
                        } else {
                            $checkout = new \Klarna\Rest\Checkout\Order(
                                $connector,
                                $_SESSION['klarna_checkout_uk']
                            );
                            $checkout->update($create);
                            $checkout->fetch();
                        }

                        $snippet = $checkout['html_snippet'];
                        if (Tools::getIsset('kco_update') and Tools::getValue('kco_update') == '1') {
                            die($snippet);
                        }
                        
                        if (Tools::getIsset('changed')) {
                            $this->context->smarty->assign('klarna_checkout_cart_changed', true);
                        }
                        $this->context->smarty->assign('klarna_checkout', $snippet);
                    }
                } catch (Exception $e) {
                    unset($_SESSION['klarna_checkout_uk']);
                    $klarna_error = $e->getMessage();
                    if (strpos($klarna_error, 'purchase_currency') !== false) {
                        $klarna_error = 'purchase_currency';
                        $this->context->smarty->assign('klarnaCurrency', $country_information['purchase_currency']);
                        $this->context->smarty->assign('klarnaCountry', $country_information['purchase_country']);
                    }
                    $this->context->smarty->assign('klarna_error', $klarna_error);
                }
            }
        } else {
            $this->context->smarty->assign('klarna_error', 'empty_cart');
        }
        
        if (Configuration::get('KCO_LAYOUT') == 1) {
            $this->setTemplate('module:klarnaofficial/views/templates/front/kco_checkout.tpl');
        } else {
            $this->setTemplate('module:klarnaofficial/views/templates/front/kco_checkout_topdown.tpl');
        }
    }

    protected function validateDeliveryOption($delivery_option)
    {
        if (!is_array($delivery_option)) {
            return false;
        }

        foreach ($delivery_option as $option) {
            if (!preg_match('/(\d+,)?\d+/', $option)) {
                return false;
            }
        }

        return true;
    }

    protected function updateMessage($messageContent, $cart)
    {
        if ($messageContent) {
            if (!Validate::isMessage($messageContent)) {
                return false;
            } elseif ($oldMessage = Message::getMessageByCartId((int) ($cart->id))) {
                $message = new Message((int) ($oldMessage['id_message']));
                $message->message = $messageContent;
                $message->update();
            } else {
                $message = new Message();
                $message->message = $messageContent;
                $message->id_cart = (int) ($cart->id);
                $message->id_customer = (int) ($cart->id_customer);
                $message->add();
            }
        } else {
            if ($oldMessage = Message::getMessageByCartId((int) ($cart->id))) {
                $message = new Message((int) ($oldMessage['id_message']));
                $message->delete();
            }
        }

        return true;
    }

    protected function assignSmartyVars($ssid, $country_information)
    {
        $this->context->smarty->assign(
            'message',
            Message::getMessageByCartId((int) ($this->context->cart->id))
        );

        $id_country = 0;
        if ($country_information['purchase_country'] == 'GB') {
            $id_country = Country::getByIso('gb');
        } elseif ($country_information['purchase_country'] == 'US') {
            $id_country = Country::getByIso('us');
        }
        
        if ($id_country > 0) {
            $delivery_option_list = $this->context->cart->getDeliveryOptionList(
                new Country($id_country),
                true
            );
        } else {
            $delivery_option_list = $this->context->cart->getDeliveryOptionList();
        }
        
        $delivery_option = $this->context->cart->getDeliveryOption(
            new Country($id_country),
            false,
            false
        );

        $free_shipping = false;
        foreach ($this->context->cart->getCartRules() as $rule) {
            if ($rule['free_shipping']) {
                $free_shipping = true;
                break;
            }
        }
        $free_fees_price = 0;
        $configuration = Configuration::getMultiple(
            array(
                'PS_SHIPPING_FREE_PRICE',
                'PS_SHIPPING_FREE_WEIGHT'
            )
        );
        
        if (isset($configuration['PS_SHIPPING_FREE_PRICE']) &&
        $configuration['PS_SHIPPING_FREE_PRICE'] > 0) {
            $free_fees_price = Tools::convertPrice(
                (float) $configuration['PS_SHIPPING_FREE_PRICE'],
                Currency::getCurrencyInstance((int) $this->context->cart->id_currency)
            );
            $orderTotalwithDiscounts = $this->context->cart->getOrderTotal(
                true,
                Cart::BOTH_WITHOUT_SHIPPING,
                null,
                null,
                false
            );
            $left_to_get_free_shipping = $free_fees_price - $orderTotalwithDiscounts;
            $this->context->smarty->assign('left_to_get_free_shipping', $left_to_get_free_shipping);
        }
        
        if (isset($configuration['PS_SHIPPING_FREE_WEIGHT']) &&
        $configuration['PS_SHIPPING_FREE_WEIGHT'] > 0) {
            $free_fees_weight = $configuration['PS_SHIPPING_FREE_WEIGHT'];
            $total_weight = $this->context->cart->getTotalWeight();
            $left_to_get_free_shipping_weight = $free_fees_weight - $total_weight;
            $this->context->smarty->assign(
                'left_to_get_free_shipping_weight',
                $left_to_get_free_shipping_weight
            );
        }

        
        $wrapping_fees_tax_inc = $this->context->cart->getGiftWrappingPrice(true);
        
        $this->context->cart->getSummaryDetails();
        $this->context->smarty->assign('discounts', $this->context->cart->getCartRules());
        $this->context->smarty->assign('cart_is_empty', false);
        $this->context->smarty->assign('gift', $this->context->cart->gift);
        $this->context->smarty->assign('gift_message', $this->context->cart->gift_message);
        $this->context->smarty->assign('giftAllowed', (int) (Configuration::get('PS_GIFT_WRAPPING')));
        $this->context->smarty->assign(
            'gift_wrapping_price',
            Tools::convertPrice(
                $wrapping_fees_tax_inc,
                new Currency($this->context->cart->id_currency)
            )
        );
        
        $this->assignSummaryInformations();
        if (Configuration::get('KCOV3_PREFILNOT')) {
            $show_prefil_link = true;
        } else {
            $show_prefil_link = false;
        }
        
        $update_cart_url = $this->context->link->getModuleLink(
            'klarnaofficial',
            'reloadCart',
            array('content_only' => 1),
            true
        );
        $button_text_color = Configuration::get('KCO_COLORBUTTONTEXT');
        $button_color = Configuration::get('KCO_COLORBUTTON');
        $this->context->smarty->assign('klarna_buttontext_color', $button_text_color);
        $this->context->smarty->assign('klarna_button_color', $button_color);
        $this->context->smarty->assign('klarna_update_cart_url', $update_cart_url);
        $this->context->smarty->assign('haserror', Tools::getIsset('haserror'));
    
        $checkout_url = $this->context->link->getModuleLink(
            'klarnaofficial',
            'checkoutklarnakco',
            array('sid' => $ssid),
            true
        );
        
        $this->context->smarty->assign(array(
            'isv3' => true,
            'no_active_countries' => 0,
            'show_prefil_link' => $show_prefil_link,
            'controllername' => 'checkoutklarnakco',
            'free_shipping' => $free_shipping,
            'token_cart' => $this->context->cart->secure_key,
            'delivery_option_list' => $delivery_option_list,
            'delivery_option' => $delivery_option,
            'KCO_SHOWLINK' => (int) Configuration::get('KCO_SHOWLINK'),
            'kcourl' => $checkout_url,
            'back' => '',
            'discount_name' => '',
        ));
    }

    protected function assignSummaryInformations()
    {
        $summary = $this->context->cart->getSummaryDetails();
        $customizedDatas = Product::getAllCustomizedDatas($this->context->cart->id);

        // override customization tax rate with real tax (tax rules)
        if ($customizedDatas) {
            foreach ($summary['products'] as &$productUpdate) {
                if (isset($productUpdate['id_product'])) {
                    $productId = (int)$productUpdate['id_product'];
                } else {
                    $productId = (int)$productUpdate['product_id'];
                }
                
                if (isset($productUpdate['id_product_attribute'])) {
                    $productAttributeId = (int)$productUpdate['id_product_attribute'];
                } else {
                    $productAttributeId = (int)$productUpdate['product_attribute_id'];
                }

                if (isset($customizedDatas[$productId][$productAttributeId])) {
                    $productUpdate['tax_rate'] = Tax::getProductTaxRate(
                        $productId,
                        $this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}
                    );
                }
            }

            Product::addCustomizationPrice($summary['products'], $customizedDatas);
        }

        $cart_product_context = Context::getContext()->cloneContext();
        foreach ($summary['products'] as $key => &$product) {
            $product['quantity'] = $product['cart_quantity'];// for compatibility with 1.2 themes

            if ($cart_product_context->shop->id != $product['id_shop']) {
                $cart_product_context->shop = new Shop((int) $product['id_shop']);
            }
            $specific_price_output = null;
            $product['price_without_specific_price'] = Product::getPriceStatic(
                $product['id_product'],
                !Product::getTaxCalculationMethod(),
                $product['id_product_attribute'],
                2,
                null,
                false,
                false,
                1,
                false,
                null,
                null,
                null,
                $specific_price_output,
                true,
                true,
                $cart_product_context
            );

            if (Product::getTaxCalculationMethod()) {
                $product['is_discounted'] = $product['price_without_specific_price'] != $product['price'];
            } else {
                $product['is_discounted'] = $product['price_without_specific_price'] != $product['price_wt'];
            }
        }

        // Get available cart rules and unset the cart rules already in the cart
        $available_cart_rules = CartRule::getCustomerCartRules(
            $this->context->language->id,
            (isset($this->context->customer->id) ? $this->context->customer->id : 0),
            true,
            true,
            true,
            $this->context->cart
        );
        
        $cart_cart_rules = $this->context->cart->getCartRules();
        foreach ($available_cart_rules as $key => $available_cart_rule) {
            if (!$available_cart_rule['highlight']
            || strpos($available_cart_rule['code'], 'BO_ORDER_') === 0) {
                unset($available_cart_rules[$key]);
                continue;
            }
            foreach ($cart_cart_rules as $cart_cart_rule) {
                if ($available_cart_rule['id_cart_rule'] == $cart_cart_rule['id_cart_rule']) {
                    unset($available_cart_rules[$key]);
                    continue 2;
                }
            }
        }

        $show_option_allow_separate_package = (!$this->context->cart->isAllProductsInStock(true)
        && Configuration::get('PS_SHIP_WHEN_AVAILABLE'));

        $this->context->smarty->assign($summary);
        $this->context->smarty->assign(array(
            'token_cart' => Tools::getToken(false),
            'isVirtualCart' => $this->context->cart->isVirtualCart(),
            'productNumber' => $this->context->cart->nbProducts(),
            'voucherAllowed' => CartRule::isFeatureActive(),
            'shippingCost' => $this->context->cart->getOrderTotal(true, Cart::ONLY_SHIPPING),
            'shippingCostTaxExc' => $this->context->cart->getOrderTotal(false, Cart::ONLY_SHIPPING),
            'customizedDatas' => $customizedDatas,
            'CUSTOMIZE_FILE' => Product::CUSTOMIZE_FILE,
            'CUSTOMIZE_TEXTFIELD' => Product::CUSTOMIZE_TEXTFIELD,
            'lastProductAdded' => $this->context->cart->getLastProduct(),
            'displayVouchers' => $available_cart_rules,
            'advanced_payment_api' => true,
            'show_option_allow_separate_package' => $show_option_allow_separate_package,
            'smallSize' => Image::getSize(ImageType::getFormatedName('small')),

        ));

        $this->context->smarty->assign(array(
            'HOOK_SHOPPING_CART' => Hook::exec('displayShoppingCartFooter', $summary),
            'HOOK_SHOPPING_CART_EXTRA' => Hook::exec('displayShoppingCart', $summary),
        ));
    }
    
    public function getConnector($ssid, $eid, $sharedSecret)
    {
        if ((int) (Configuration::get('KCO_TESTMODE')) == 1) {
            if ($ssid=='us') {
                $url = 'https://api-na.playground.klarna.com/';
            } else {
                $url = \Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL;
            }
            
            $klarna_agent = \Klarna\Rest\Transport\UserAgent::createDefault();
            $klarna_agent->setField('Prestashop', _PS_VERSION_);
            $klarna_agent->setField('Klarnaofficial', $this->module->version);
            $connector = \Klarna\Rest\Transport\Connector::create(
                $eid,
                $sharedSecret,
                $url,
                $klarna_agent
            );
        } else {
            if ($ssid=='us') {
                $url = 'https://api-na.klarna.com/';
            } else {
                $url = \Klarna\Rest\Transport\ConnectorInterface::EU_BASE_URL;
            }
            $connector = \Klarna\Rest\Transport\Connector::create(
                $eid,
                $sharedSecret,
                $url
            );
        }
        return $connector;
    }
    
    protected function getCheckoutSession()
    {
        $deliveryOptionsFinder = new DeliveryOptionsFinder(
            $this->context,
            $this->getTranslator(),
            $this->objectPresenter,
            new PriceFormatter()
        );

        $session = new CheckoutSession(
            $this->context,
            $deliveryOptionsFinder
        );

        return $session;
    }
}
