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

class KlarnaOfficialCheckoutKlarnaUsModuleFrontController extends ModuleFrontController
{
    public $display_column_left = false;
    public $display_column_right = false;
    public $ssl = true;
    public $current_kco = 'US';

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
        $ssid = 'us';
        $eid = '';
        $sharedSecret = '';
        parent::initContent();

        if (!$this->context->cart->getDeliveryOption(null, true)) {
            $this->context->cart->setDeliveryOption($this->context->cart->getDeliveryOption());
        }
        
        $checkoutcart = array();
        $create  = array();
        
        if (Tools::getIsset('kco_update') and Tools::getValue('kco_update') == '1') {
            if ($this->context->cart->nbProducts() < 1) {
                die;
            }
        }

        if (!isset($this->context->cart->id)) {
            Tools::redirect('index.php');
        }

        $currency = new Currency($this->context->cart->id_currency);
        $language = new Language($this->context->cart->id_lang);

        $country_information = $this->getKlarnaCountryInformation($currency->iso_code, $language->iso_code);

        require_once dirname(__FILE__).'/../../libraries/kcocommonredirectcheck.php';
        
        $layout = 'desktop';
        //if($this->context->getMobileDevice())
        //$layout = 'mobile';
        require_once _PS_TOOL_DIR_.'mobile_Detect/Mobile_Detect.php';
        $mobile_detect_class = new Mobile_Detect();
        if ($mobile_detect_class->isMobile() or $mobile_detect_class->isMobile()) {
            $layout = 'mobile';
        }

        $totalCartValue = 0;
        $round_diff = 0;

        if (Configuration::get('KCO_ROUNDOFF') == 1) {
            $total_cart_price_before_round = $this->context->cart->getOrderTotal(true, Cart::BOTH);
            $total_cart_price_after_round = round($total_cart_price_before_round);
            $round_diff = $total_cart_price_after_round - $total_cart_price_before_round;
        }

        if (isset($this->context->cart) and $this->context->cart->nbProducts() > 0) {
            if (!$this->context->cart->checkQuantities()) {
                Tools::redirect('index.php?controller=order&step=1');
            } else {
                $minimal_purchase = Tools::convertPrice((float)Configuration::get('PS_PURCHASE_MINIMUM'), $currency);
                if ($this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS) < $minimal_purchase) {
                    Tools::redirect('index.php?controller=order&step=1');
                }

                foreach ($this->context->cart->getProducts() as $product) {
                    $price = Tools::ps_round($product['price_wt'], 2);
                    $tax_value = (Tools::ps_round($product['price_wt'], 2) - Tools::ps_round($product['price'], 2));
                    $tax_value = Tools::ps_round($tax_value, 2);
                    $tax_value = ($tax_value * (int) ($product['cart_quantity']));
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
                        'unit_price' => $price,
                        'tax_rate' => (int) ($product['rate']) * 100,
                        'total_amount' => (int) ($rowvalue * 100),
                        'total_tax_amount' => (int) ($tax_value * 100),
                    );
                }

                $shipping_cost_with_tax = $this->context->cart->getOrderTotal(true, Cart::ONLY_SHIPPING);
                $shipping_cost_without_tax = $this->context->cart->getOrderTotal(false, Cart::ONLY_SHIPPING);

                if ($shipping_cost_without_tax > 0) {
                    $shipping_tax_rate = ($shipping_cost_with_tax / $shipping_cost_without_tax) - 1;
                    $shipping_tax_value = ($shipping_cost_with_tax - $shipping_cost_without_tax);
                    $totalCartValue += $shipping_cost_with_tax;

                    $carrier = new Carrier($this->context->cart->id_carrier);
                    $shippingReference = $this->module->shippingreferences[$language->iso_code];
                    
                    $checkoutcart[] = array(
                        'type' => 'shipping_fee',
                        'reference' => $shippingReference,
                        'name' => strip_tags($carrier->name),
                        'quantity' => 1,
                        'unit_price' => ($shipping_cost_with_tax * 100),
                        'tax_rate' => (int) ($shipping_tax_rate * 10000),
                        'total_amount' => ($shipping_cost_with_tax * 100),
                        'total_tax_amount' => (int) ($shipping_tax_value * 100),
                    );
                }
                if ($this->context->cart->gift == 1) {
                    $cart_wrapping = $this->context->cart->getOrderTotal(true, Cart::ONLY_WRAPPING);
                    if ($cart_wrapping > 0) {
                        $wrapping_cost_excl = $this->context->cart->getOrderTotal(false, Cart::ONLY_WRAPPING);
                        $wrapping_cost_incl = $this->context->cart->getOrderTotal(true, Cart::ONLY_WRAPPING);
                        $wrapping_vat = (($wrapping_cost_incl / $wrapping_cost_excl) - 1) * 100;
                        $wrapping_tax_value = ($wrapping_cost_incl - $wrapping_cost_excl);
                        
                        $cart_wrapping = Tools::ps_round($cart_wrapping, 2);
                        $totalCartValue += $cart_wrapping;
                        
                        $wrappingreference = $this->module->wrappingreferences[$language->iso_code];
                        
                        $checkoutcart[] = array(
                            'reference' => $wrappingreference,
                            'name' => $this->module->getL('Inslagning'),
                            'quantity' => 1,
                            'unit_price' => ($cart_wrapping * 100),
                            'tax_rate' => (int) ($wrapping_vat * 100),
                            'total_amount' => ($cart_wrapping * 100),
                            'total_tax_amount' => (int) ($wrapping_tax_value * 100),
                        );
                    }
                }

                //DISCOUNTS
                $totalDiscounts = $this->context->cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS);
                if ($totalDiscounts > 0) {
                    if ($totalDiscounts > $totalCartValue) {
                        //Free order
                        $totalCartValue = $this->context->cart->getOrderTotal(true, Cart::BOTH);
                        $totalCartValue_tax_excl = $this->context->cart->getOrderTotal(false, Cart::BOTH);
                        $common_tax_rate = (($totalCartValue / $totalCartValue_tax_excl) - 1) * 100;
                        $common_tax_value = ($totalCartValue - $totalCartValue_tax_excl);
                        $checkoutcart[] = array(
                            'type' => 'discount',
                            'reference' => '',
                            'name' => $this->module->getL('Discount'),
                            'quantity' => 1,
                            'unit_price' => -($totalCartValue * 100),
                            'tax_rate' => (int) ($common_tax_rate * 100),
                            'total_amount' => -($totalCartValue * 100),
                            'total_tax_amount' => -(int) ($common_tax_value * 100),
                        );
                    } else {
                        $totalDiscounts_tax_excl = $this->context->cart->getOrderTotal(false, Cart::ONLY_DISCOUNTS);
                        $common_tax_rate = (($totalDiscounts / $totalDiscounts_tax_excl) - 1) * 100;
                        $common_tax_rate = Tools::ps_round($common_tax_rate, 0);
                        $common_tax_value = ($totalDiscounts - $totalDiscounts_tax_excl);

                        $checkoutcart[] = array(
                            'type' => 'discount',
                            'reference' => '',
                            'name' => $this->module->getL('Discount'),
                            'quantity' => 1,
                            'unit_price' => -number_format(($totalDiscounts * 100), 2, '.', ''),
                            'tax_rate' => (int) ($common_tax_rate * 100),
                            'total_amount' => -number_format(($totalDiscounts * 100), 2, '.', ''),
                            'total_tax_amount' => -(int) ($common_tax_value * 100),
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

                $callbackPage = $this->context->link->getModuleLink(
                    'klarnaofficial',
                    'thankyouuk',
                    array('sid' => $ssid)
                );
                $callbackPage .= '&klarna_order_id={checkout.order.id}';

                $address_updatePage = $this->context->link->getModuleLink(
                    'klarnaofficial',
                    'validateaddress',
                    array('sid' => $ssid, 'cartid' => $this->context->cart->id),
                    true
                );
                $pushPage = $this->context->link->getModuleLink(
                    'klarnaofficial',
                    'pushuk',
                    array('sid' => $ssid)
                );
                $pushPage .= '&klarna_order_id={checkout.order.id}';

                $checkout_url = $this->context->link->getModuleLink(
                    'klarnaofficial',
                    'checkoutklarnauk',
                    array('sid' => $ssid),
                    true
                );
                $checkout_url .= '&klarna_order_id={checkout.order.id}';

                $cms = new CMS(
                    (int) (Configuration::get('PS_CONDITIONS_CMS_ID')),
                    (int) ($this->context->cookie->id_lang)
                );
                $link_conditions = $this->context->link->getCMSLink($cms, $cms->link_rewrite, true);
                $termsPage = $link_conditions;

                try {
                    if (version_compare(phpversion(), '5.4.0', '<')) {
                        $this->context->smarty->assign('klarna_error', 'PHP 5.4 Required');
                    } else {
                        require_once dirname(__FILE__).'/../../libraries/KCOUK/autoload.php';
                        $connector = $this->getConnector($ssid, $eid, $sharedSecret);

                        $checkout = new \Klarna\Rest\Checkout\Order($connector);

                        $totalCartValue = $this->context->cart->getOrderTotal(true, Cart::BOTH);
                        $totalCartValue_tax_excl = $this->context->cart->getOrderTotal(false, Cart::BOTH);
                        $total_tax_value = $totalCartValue - $totalCartValue_tax_excl;
                        $create['purchase_country'] = $country_information['purchase_country'];
                        $create['purchase_currency'] = $country_information['purchase_currency'];
                        $create['locale'] = $country_information['locale'];
                        $create['order_amount'] = $totalCartValue * 100;
                        $create['order_tax_amount'] = $total_tax_value * 100;

                        if (Configuration::get('KCO_AUTOFOCUS') == 0) {
                            $create['gui']['options'] = array('disable_autofocus');
                        }
                        $create['gui']['layout'] = $layout;
                        $create['merchant_urls']['terms'] = $termsPage;
                        $create['merchant_urls']['checkout'] = $checkout_url;
                        $create['merchant_urls']['confirmation'] = $callbackPage;
                        $create['merchant_urls']['push'] = $pushPage;
                        if ($ssid=='us') {
                            $create['merchant_urls']['address_update'] = $address_updatePage;
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

                        foreach ($checkoutcart as $item) {
                            $create['order_lines'][] = $item;
                        }
                        if (!isset($_SESSION['klarna_checkout_us'])) {
                            $checkout->create($create);
                            $checkout->fetch();
                            $_SESSION['klarna_checkout_us'] = $checkout['order_id'];
                        } else {
                            $checkout = new \Klarna\Rest\Checkout\Order(
                                $connector,
                                $_SESSION['klarna_checkout_us']
                            );
                            $checkout->update($create);
                            $checkout->fetch();
                        }

                        $id_country = 0;

                        if ($country_information['purchase_country'] == 'GB') {
                            $id_country = Country::getByIso('gb');
                        } elseif ($country_information['purchase_country'] == 'US') {
                            $id_country = Country::getByIso('us');
                        }

                        $this->context->cart->getSummaryDetails();

                        $snippet = $checkout['html_snippet'];
                        if (Tools::getIsset('kco_update') and Tools::getValue('kco_update') == '1') {
                            die($snippet);
                        }

                        $this->context->smarty->assign('klarna_checkout', $snippet);

                        $wrapping_fees_tax_inc = $this->context->cart->getGiftWrappingPrice(true);

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
                        $this->context->smarty->assign(
                            'message',
                            Message::getMessageByCartId((int) ($this->context->cart->id))
                        );

                        if ($id_country > 0) {
                            $delivery_option_list = $this->context->cart->getDeliveryOptionList(
                                new Country($id_country),
                                true
                            );
                        } else {
                            $delivery_option_list = $this->context->cart->getDeliveryOptionList();
                        }

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

                        $delivery_option = $this->context->cart->getDeliveryOption(
                            new Country($id_country),
                            false,
                            false
                        );

                        $no_active_countries = 0;
                        $show_sweden = false;
                        $show_norway = false;
                        $show_finland = false;
                        $show_germany = false;
                        $show_austria = false;
                        $show_uk = false;
                        $show_us = false;
                        $show_nl = false;
                        if ((int) (Configuration::get('KCO_SWEDEN')) == 1) {
                            ++$no_active_countries;
                            $show_sweden = true;
                        }
                        if ((int) (Configuration::get('KCO_FINLAND')) == 1) {
                            ++$no_active_countries;
                            $show_finland = true;
                        }
                        if ((int) (Configuration::get('KCO_NL')) == 1) {
                            ++$no_active_countries;
                            $show_nl = true;
                        }
                        if ((int) (Configuration::get('KCO_NORWAY')) == 1) {
                            ++$no_active_countries;
                            $show_norway = true;
                        }
                        if ((int) (Configuration::get('KCO_GERMANY')) == 1) {
                            ++$no_active_countries;
                            $show_germany = true;
                        }
                        if ((int) (Configuration::get('KCO_UK')) == 1) {
                            ++$no_active_countries;
                            $show_uk = true;
                        }
                        if ((int) (Configuration::get('KCO_US')) == 1) {
                            ++$no_active_countries;
                            $show_us = true;
                        }
                        if ((int) (Configuration::get('KCO_AUSTRIA')) == 1) {
                            ++$no_active_countries;
                            $show_austria = true;
                        }
                        $this->assignSummaryInformations();
                        $this->context->smarty->assign(array(
                            'no_active_countries' => $no_active_countries,
                            'show_germany' => $show_germany,
                            'show_norway' => $show_norway,
                            'show_uk' => $show_uk,
                            'show_us' => $show_us,
                            'show_nl' => $show_nl,
                            'show_austria' => $show_austria,
                            'show_finland' => $show_finland,
                            'show_sweden' => $show_sweden,
                            'kco_selected_country' => $country_information['purchase_country'],
                            'klarna_checkout' => $snippet,
                            'controllername' => 'checkoutklarnauk',
                            'free_shipping' => $free_shipping,
                            'token_cart' => $this->context->cart->secure_key,
                            'delivery_option_list' => $delivery_option_list,
                            'delivery_option' => $delivery_option,
                            'KCO_SHOWLINK' => (int) Configuration::get('KCO_SHOWLINK'),
                            'layout' => $layout,
                            'kcourl' => $checkout_url,
                            'back' => '',
                            'discount_name' => '',
                        ));
                    }
                } catch (Exception $e) {
                    unset($_SESSION['klarna_checkout_us']);
                    $this->context->smarty->assign('klarna_error', $e->getMessage());
                }
            }
        } else {
            $this->context->smarty->assign('klarna_error', 'empty_cart');
        }
        if (Configuration::get('KCO_LAYOUT') == 1) {
            $this->setTemplate('kco_twocolumns.tpl');
        } else {
            $this->setTemplate('kco_height.tpl');
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

    protected function getKlarnaCountryInformation($currency_iso_code, $language_iso_code)
    {
        if ($language_iso_code == 'nb' || $language_iso_code == 'nn') {
            $language_iso_code = 'no';
        }
        if ($currency_iso_code == 'SEK' &&
        $language_iso_code == 'sv' &&
        Configuration::get('KCO_SWEDEN') == 1) {
            return array('locale' => 'sv-se', 'purchase_currency' => 'SEK', 'purchase_country' => 'SE');
        } elseif ($currency_iso_code == 'EUR' &&
        $language_iso_code == 'fi' &&
        Configuration::get('KCO_FINLAND') == 1) {
            return array('locale' => 'fi-fi', 'purchase_currency' => 'EUR', 'purchase_country' => 'FI');
        } elseif ($currency_iso_code == 'NOK' &&
        $language_iso_code == 'no' &&
        Configuration::get('KCO_NORWAY') == 1) {
            return array('locale' => 'nb-no', 'purchase_currency' => 'NOK', 'purchase_country' => 'NO');
        } elseif ($currency_iso_code == 'EUR' &&
        $language_iso_code == 'sv' &&
        Configuration::get('KCO_FINLAND') == 1) {
            return array('locale' => 'sv-fi', 'purchase_currency' => 'EUR', 'purchase_country' => 'FI');
        } elseif ($currency_iso_code == 'EUR' &&
        $language_iso_code == 'de' &&
        Configuration::get('KCO_GERMANY') == 1) {
            return array('locale' => 'de-de', 'purchase_currency' => 'EUR', 'purchase_country' => 'DE');
        } elseif ($currency_iso_code == 'GBP' &&
        $language_iso_code == 'en' &&
        Configuration::get('KCO_UK') == 1) {
            return array('locale' => 'en-gb', 'purchase_currency' => 'GBP', 'purchase_country' => 'GB');
        } elseif ($currency_iso_code == 'GBP' &&
        $language_iso_code == 'gb' &&
        Configuration::get('KCO_UK') == 1) {
            return array('locale' => 'en-gb', 'purchase_currency' => 'GBP', 'purchase_country' => 'GB');
        } elseif ($currency_iso_code == 'USD' &&
        $language_iso_code == 'en' &&
        Configuration::get('KCO_US') == 1) {
            return array('locale' => 'en-us', 'purchase_currency' => 'USD', 'purchase_country' => 'US');
        } elseif ($currency_iso_code == 'EUR' &&
        $language_iso_code == 'nl' &&
        Configuration::get('KCO_NL') == 1) {
            return array('locale' => 'nl-nl', 'purchase_currency' => 'EUR', 'purchase_country' => 'NL');
        } else {
            return false;
        }
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
            'currencySign' => $this->context->currency->sign,
            'currencyRate' => $this->context->currency->conversion_rate,
            'currencyFormat' => $this->context->currency->format,
            'currencyBlank' => $this->context->currency->blank,
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
            
            $connector = \Klarna\Rest\Transport\Connector::create(
                $eid,
                $sharedSecret,
                $url
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
}
