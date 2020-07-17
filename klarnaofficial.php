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

class KlarnaOfficial extends PaymentModule
{
    public $Pending_risk = 'Pending';
    
    const OSM_THEME_DEFAULT = 'default';
    const OSM_THEME_DARK = 'dark';
    const OSM_THEME_CUSTOM = '';
    
    const OSM_PLACEMENTS = array(
        'top-strip-promotion-standard',
        'credit-promotion-small',
        'credit-promotion-standard',
        'homepage-promotion-wide',
        'homepage-promotion-box',
        'homepage-promotion-tall',
        'sidebar-promotion-auto-size',
    );
    
    const OSM_VALID_COUNTRY_CURRENCY_COMBINATION = array(
        'SE' => 'SEK',
        'DK' => 'DKK',
        'GB' => 'GBP',
        'NO' => 'NOK',
        'US' => 'USD',
        'CH' => 'CHF'
    );
    
    public $shippingreferences = array(
        'sv' => 'Frakt',
        'da' => 'Fragt',
        'de' => 'Versand',
        'en' => 'Shipping',
        'no' => 'Frakt',
        'nb' => 'Frakt',
        'fi' => 'Rahti',
        'it' => 'Shipping',
        'nl' => 'Versand'
    );
    public $wrappingreferences = array(
        'sv' => 'Inslagning',
        'da' => 'Indpakning',
        'de' => 'Verpackung',
        'en' => 'Wrapping',
        'it' => 'Wrapping',
        'no' => 'Innpakning',
        'nb' => 'Innpakning',
        'fi' => 'Kääre',
        'nl' => 'Verpackung'
    );
    
    public $configuration_params = array(
        'KPM_ACCEPTED_PP',
        'KPM_SHOW_IN_PAYMENTS',
        'KPM_DISABLE_INVOICE',
        'KPM_ACCEPTED_INVOICE',
        'KPM_PENDING_PP',
        'KPM_PENDING_INVOICE',
        'KPM_LOGO',
        'KPM_AT_SECRET',
        'KPM_AT_EID',
        'KPM_INVOICEFEE',
        'KPM_NL_EID',
        'KPM_NL_SECRET',
        'KPM_DE_SECRET',
        'KPM_DE_EID',
        'KPM_DA_SECRET',
        'KPM_DA_EID',
        'KPM_FI_SECRET',
        'KPM_FI_EID',
        'KPM_NO_SECRET',
        'KPM_NO_EID',
        'KPM_SV_SECRET',
        'KPM_SV_EID',
        
        'KLARNA_ONSITE_MESSAGE',
        'KLARNA_ONSITE_MESSAGE_DCI',
        'KLARNA_ONSITEMESSAGING_CONFIGURATION',
        'KLARNA_ONSITEMESSAGING_LIBRARY_PATH_COUNTRY',

        'KCOV3',
        'KCOV3_USEGUESTACCOUNTS',
        'KCOV3_PREFILNOT',
        'KCOV3_MID',
        'KCOV3_SECRET',
        'KCOV3_FOOTERBANNER',
        'KCOV3_CUSTOM_CHECKBOX',
        'KCOV3_CUSTOM_CHECKBOX_REQUIRED',
        'KCOV3_CUSTOM_CHECKBOX_PRECHECKED',
        'KCOV3_CUSTOM_CHECKBOX_TEXT',
        
        'KCOV3_EXTERNAL_PAYMENT_METHOD_ACTIVE',
        'KCOV3_EXTERNAL_PAYMENT_METHOD_FEE',
        'KCOV3_EXTERNAL_PAYMENT_METHOD_IMGURL',
        'KCOV3_EXTERNAL_PAYMENT_METHOD_COUNTRIES',
        'KCOV3_EXTERNAL_PAYMENT_METHOD_LABEL',
        'KCOV3_EXTERNAL_PAYMENT_METHOD_DESC',
        'KCOV3_EXTERNAL_PAYMENT_METHOD_OPTION',
        'KCOV3_EXTERNAL_PAYMENT_METHOD_EXTERNALURL',
       
        'KCO_ALLOWMESSAGE',
        'KCO_DOBMAN',
        'KCO_CALLBACK_CHECK',
        'KCO_FOOTERLAYOUT',
        'KCO_PRODUCTPAGELAYOUT',
        'KCO_SHOWPRODUCTPAGE',
        'KCO_TERMS_PAGE',
        'KCO_RADIUSBORDER',
        'KCO_ADD_NEWSLETTERBOX',
        'KCO_DE_PREFILNOT',
        'KCO_SHOWLINK',
        'KCO_SENDTYPE',
        'KCO_IS_ACTIVE',
        'KCO_CANCEL_STATE',
        'KCO_ACTIVATE_STATE',
        'KCO_ORDERID',
        'KCO_GERMANY',
        'KCO_SWEDEN_B2B',
        'KCO_SWEDEN',
        'KCO_NL',
        'KCO_US',
        'KCO_UK',
        'KCO_FINLAND_B2B',
        'KCO_FINLAND',
        'KCO_FINLAND_EID',
        'KCO_NORWAY_B2B',
        'KCO_NORWAY',
        'KCO_LAYOUT',
        'KCO_ALLOWED_TYPES',
        'KCO_NIN_MANDATORY',
        'KCO_TESTMODE',
        'KCO_SHOW_SUBTOT',
        'KCO_AUTOFOCUS',
        'KCO_FORCEPHONE',
        'KCO_ALLOWSEPADDR',
        'KCO_ROUNDOFF',
        'KCO_SHOW_IN_PAYMENTS',
        'KCO_NORWAY_ADDR',
        'KCO_SWEDEN_ADDR',
        'KCO_NL_ADDR',
        'KCO_FINLAND_ADDR',
        'KCO_UK_ADDR',
        'KCO_US_ADDR',
        'KCO_GERMANY_ADDR',
        'KCO_AUSTRIA_ADDR',
        'KCO_COLORBUTTON',
        'KCO_COLORBUTTONTEXT',
        'KCO_COLORCHECKBOX',
        'KCO_COLORCHECKBOXMARK',
        'KCO_COLORHEADER',
        'KCO_COLORLINK',
        'KCO_UK_SECRET',
        'KCO_NL_SECRET',
        'KCO_US_SECRET',
        'KCO_SWEDEN_SECRET',
        'KCO_FINLAND_SECRET',
        'KCO_NORWAY_SECRET',
        'KCO_GERMANY_SECRET',
        'KCO_AUSTRIA',
        'KCO_AUSTRIA_SECRET',
        'KCO_UK_EID',
        'KCO_US_EID',
        'KCO_NL_EID',
        'KCO_SWEDEN_EID',
        'KCO_NORWAY_EID',
        'KCO_GERMANY_EID',
        'KCO_AUSTRIA_EID',
        'KCO_TITLEMAN',
        'KCO_PREFILL',
        'KCO_SHOW_SHIPDETAILS',
        'KCO_CANCEL_PAGE'
    );
    
    public $osm_fields = array(
        'KLARNA_ONSITEMESSAGING_SWITCH_COUNTRY_',
        'KLARNA_ONSITEMESSAGING_PRODUCT_PAGE_THEME_COUNTRY_',
        // 'KLARNA_ONSITEMESSAGING_CART_PLACEMENT_THEME_COUNTRY_',
        'KLARNA_ONSITEMESSAGING_PRODUCT_PAGE_COUNTRY_',
        // 'KLARNA_ONSITEMESSAGING_CART_PLACEMENT_COUNTRY_',
    );
    
    public function __construct()
    {
        $this->name = 'klarnaofficial';
        $this->tab = 'payments_gateways';
        $this->version = '1.9.51';
        $this->author = 'Prestaworks AB';
        $this->module_key = 'b803c9b20c1ec71722eab517259b8ddf';
        $this->need_instance = 1;
        $this->bootstrap = true;
        $this->currencies = true;
        $this->currencies_mode = 'checkbox';

        parent::__construct();

        $this->displayName = $this->l('Klarna');
        $this->description = $this->l('Pay Now. Pay Later. Slice It. A smoooth payments experience.');
    }

    public function uninstall()
    {
        if (parent::uninstall() == false) {
            return false;
        }
        foreach ($this->configuration_params as $param) {
            Configuration::deleteByName($param);
        }
        $this->dropTables();

        return true;
    }
    public function install()
    {
        if (parent::install() == false
            || $this->registerHook('header') == false
            || $this->registerHook('footer') == false
            || $this->registerHook('updateOrderStatus') == false
            || $this->registerHook('displayProductButtons') == false
            || $this->registerHook('displayShoppingCart') == false
            || $this->registerHook('payment') == false
            || $this->registerHook('paymentReturn') == false
            || $this->registerHook('displayAdminOrder') == false
            || Configuration::updateValue('KCO_ALLOWMESSAGE', 1) == false
            || Configuration::updateValue('KCO_ROUNDOFF', 0) == false
            || Configuration::updateValue('KCOV3', 1) == false
            || Configuration::updateValue('KCO_IS_ACTIVE', 1) == false
            || Configuration::updateValue('KCO_TESTMODE', 1) == false
            || $this->setKCOCountrySettings() == false
            ) {
            return false;
        }
        $this->createTables();

        $states = OrderState::getOrderStates(Configuration::get('PS_LANG_DEFAULT'));
        $name = $this->l('Klarna pending invoice');
        $config_name = 'KPM_PENDING_INVOICE';
        $this->createOrderStatus($name, $states, $config_name, false);

        $name = $this->l('Klarna pending partpayment');
        $config_name = 'KPM_PENDING_PP';
        $this->createOrderStatus($name, $states, $config_name, false);

        $name = $this->l('Klarna accepted invoice');
        $config_name = 'KPM_ACCEPTED_INVOICE';
        $this->createOrderStatus($name, $states, $config_name, true);

        $name = $this->l('Klarna accepted partpayment');
        $config_name = 'KPM_ACCEPTED_PP';
        $this->createOrderStatus($name, $states, $config_name, true);
        
        $name = $this->l('Klarna pending payment');
        $config_name = 'KCO_PENDING_PAYMENT';
        $this->createOrderStatus($name, $states, $config_name, false);
        
        $name = $this->l('Klarna payment accepted');
        $config_name = 'KCO_PENDING_PAYMENT_ACCEPTED';
        $this->createOrderStatus($name, $states, $config_name, false);
        
        $name = $this->l('Klarna payment rejected');
        $config_name = 'KCO_PENDING_PAYMENT_REJECTED';
        $this->createOrderStatus($name, $states, $config_name, false);

        $metas = array();
        $metas[] = $this->setMeta('module-klarnaofficial-checkoutklarna');
        $metas[] = $this->setMeta('module-klarnaofficial-checkoutklarnauk');
        $metas[] = $this->setMeta('module-klarnaofficial-kpmpartpayment');
        $metas[] = $this->setMeta('module-klarnaofficial-thankyou');
        $metas[] = $this->setMeta('module-klarnaofficial-thankyouuk');
        foreach (Theme::getThemes() as $theme) {
            $theme->updateMetas($metas, false);
        }
            
        return true;
    }
    
    public function setMeta($name)
    {
        $metas = array();
        $name = pSQL($name);
        $sql = "SELECT id_meta FROM `"._DB_PREFIX_."meta` WHERE page='$name'";
        $id_meta = Db::getInstance()->getValue($sql);
        if ((int)$id_meta==0) {
            $meta = new Meta();
            $meta->page = $name;
            $meta->configurable = false;
            $meta->add();

            $metas['id_meta'] = (int)$meta->id;
            $metas['left'] = 0;
            $metas['right'] = 0;
        } else {
            $metas['id_meta'] = (int)$id_meta;
            $metas['left'] = 0;
            $metas['right'] = 0;
        }
        return $metas;
    }

    public function createOrderStatus($name, $states, $config_name, $paid)
    {
        $exists = false;
        foreach ($states as $state) {
            if ($state['name'] == $name) {
                $exists = true;
                Configuration::updateValue($config_name, $state['id_order_state']);

                return;
            }
        }

        $names = array();
        $templates = array();
        if ($exists == false) {
            $orderstate = new OrderState();
            foreach (Language::getLanguages(false) as $language) {
                $names[$language['id_lang']] = $name;
                $templates[$language['id_lang']] = '';
            }
            $orderstate->name = $names;
            $orderstate->send_email = false;
            $orderstate->invoice = true;
            if ("KCO_PENDING_PAYMENT_REJECTED"==$config_name) {
                $orderstate->color = '#DC143C';
            } else {
                $orderstate->color = '#008cd4';
            }
            $orderstate->unremovable = true;
            $orderstate->hidden = true;
            $orderstate->logable = true;
            $orderstate->paid = $paid;
            $orderstate->save();
            Configuration::updateValue($config_name, $orderstate->id);

            ImageManager::resize(
                dirname(__FILE__).'/views/img/klarna_os.gif',
                _PS_IMG_DIR_.'os/'.$orderstate->id.'.gif',
                null,
                null,
                'gif'
            );
        }
    }

    public function dropTables()
    {
        include(dirname(__FILE__).'/sql/uninstall.php');
    }
    public function createTables()
    {
        include(dirname(__FILE__).'/sql/install.php');
    }

    public function getContent()
    {
        $isSaved = false;
        $address_check_done = false;
        $errorMSG = '';
        $countries = Country::getCountries($this->context->language->id, true);

        if (Tools::isSubmit('runcheckup') && Tools::getValue('runcheckup') == '1') {
            $address_check_done = $this->setKCOCountrySettings();
        }
        if (Tools::isSubmit('btnKPMSubmit') ||
            Tools::isSubmit('btnCommonSubmit') ||
            Tools::isSubmit('btnKCOCommonSubmit') ||
            Tools::isSubmit('btnKCOV3Submit') ||
            Tools::isSubmit('btnOSMSubmit') ||
            Tools::isSubmit('btnKCOSubmit')
        ) {
            $OSMconfig = array();
            
            $multi_selectfields = array(
                "KCO_ACTIVATE_STATE",
                "KCO_CANCEL_STATE",
            );
            
            foreach ($this->configuration_params as $param) {
                if (in_array($param, $multi_selectfields)) {
                    if (Tools::getIsset($param)) {
                        $_POST[$param] = Tools::jsonEncode(Tools::getValue($param));
                    }
                }
                if ("KCOV3_CUSTOM_CHECKBOX_TEXT" == $param) {
                    $texts = array();
                    $has_custom_text = false;
                    foreach (Language::getLanguages(false, false, true) as $id_lang) {
                        if (Tools::getIsset($param.'_'.$id_lang)) {
                            $has_custom_text = true;
                            $texts[$id_lang] = Tools::getValue($param.'_'.$id_lang);
                        }
                    }
                    if ($has_custom_text) {
                        $_POST[$param] = Tools::jsonEncode($texts);
                    }
                }
                
                if ("KCOV3_EXTERNAL_PAYMENT_METHOD_DESC" == $param) {
                    $texts = array();
                    $has_custom_text = false;
                    foreach (Language::getLanguages(false, false, true) as $id_lang) {
                        if (Tools::getIsset($param.'_'.$id_lang)) {
                            $has_custom_text = true;
                            $texts[$id_lang] = Tools::getValue($param.'_'.$id_lang);
                        }
                    }
                    if ($has_custom_text) {
                        $_POST[$param] = Tools::jsonEncode($texts);
                    }
                }
                
                if ("KLARNA_ONSITEMESSAGING_CONFIGURATION" == $param &&
                    Tools::isSubmit('btnOSMSubmit')
                ) {
                    foreach ($countries as $country) {
                        foreach ($this->osm_fields as $osmfield) {
                            $Key = $osmfield.$country['iso_code'];
                            $OSMconfig[$country['iso_code']][$Key] = Tools::getValue($Key);
                        }
                    }
                    $OSMconfigJson = Tools::jsonEncode($OSMconfig);
                    Configuration::updateValue($param, $OSMconfigJson);
                }
                
                if (Tools::getIsset($param)) {
                     Configuration::updateValue($param, Tools::getValue($param));
                     $isSaved = true;
                }
            }
            if (1 === (int)Tools::getValue("KCOV3")) {
                Configuration::updateValue("KCO_GERMANY", Tools::getValue("KCO_GERMANY"));
                Configuration::updateValue("KCO_SWEDEN", Tools::getValue("KCO_SWEDEN"));
                Configuration::updateValue("KCO_FINLAND", Tools::getValue("KCO_FINLAND"));
                Configuration::updateValue("KCO_NORWAY", Tools::getValue("KCO_NORWAY"));
                Configuration::updateValue("KCO_AUSTRIA", Tools::getValue("KCO_AUSTRIA"));
            }
        }

        $invoice_fee_not_found = false;
        if (Configuration::get('KPM_INVOICEFEE') != '') {
            $feeproduct = $this->getByReference(Configuration::get('KPM_INVOICEFEE'));
            if (!Validate::isLoadedObject($feeproduct)) {
                $invoice_fee_not_found = true;
            }
        }

        if (Tools::getIsset('deleteklarnaofficial')) {
            $segments = explode('-', Tools::getValue('key_id'));
            if (count($segments) === 2) {
                list($eid, $pclass) = $segments;
                $eid = pSQL($eid);
                $pclass = pSQL($pclass);
                $delete_sql = "DELETE FROM `"._DB_PREFIX_."kpmpclasses` WHERE id=$pclass AND eid=$eid";
                Db::getInstance()->execute($delete_sql);
            }
        }
        if (Tools::getIsset('updateplcassklarnaofficial')) {
            $eids = array();
            if (Configuration::get('KPM_SV_EID') != '') {
                $eids[] = array(
                    'eid' => Configuration::get('KPM_SV_EID'),
                    'secret' => Configuration::get('KPM_SV_SECRET'),
                    'lang' => 'sv',
                    'country' => 'se',
                    'currency' => 'sek'
                );
            }
            if (Configuration::get('KPM_NO_EID') != '') {
                $eids[] = array(
                    'eid' => Configuration::get('KPM_NO_EID'),
                    'secret' => Configuration::get('KPM_NO_SECRET'),
                    'lang' => 'no',
                    'country' => 'no',
                    'currency' => 'nok'
                );
            }
            if (Configuration::get('KPM_FI_EID') != '') {
                $eids[] = array(
                    'eid' => Configuration::get('KPM_FI_EID'),
                    'secret' => Configuration::get('KPM_FI_SECRET'),
                    'lang' => 'fi',
                    'country' => 'fi',
                    'currency' => 'eur'
                );
            }
            if (Configuration::get('KPM_DA_EID') != '') {
                $eids[] = array(
                    'eid' => Configuration::get('KPM_DA_EID'),
                    'secret' => Configuration::get('KPM_DA_SECRET'),
                    'lang' => 'da',
                    'country' => 'dk',
                    'currency' => 'dkk'
                );
            }
            if (Configuration::get('KPM_DE_EID') != '') {
                $eids[] = array(
                    'eid' => Configuration::get('KPM_DE_EID'),
                    'secret' => Configuration::get('KPM_DE_SECRET'),
                    'lang' => 'de',
                    'country' => 'de',
                    'currency' => 'eur'
                );
            }
            if (Configuration::get('KPM_NL_EID') != '') {
                $eids[] = array(
                    'eid' => Configuration::get('KPM_NL_EID'),
                    'secret' => Configuration::get('KPM_NL_SECRET'),
                    'lang' => 'nl',
                    'country' => 'nl',
                    'currency' => 'eur'
                );
            }
            if (Configuration::get('KPM_AT_EID') != '') {
                $eids[] = array(
                    'eid' => Configuration::get('KPM_AT_EID'),
                    'secret' => Configuration::get('KPM_AT_SECRET'),
                    'lang' => 'de',
                    'country' => 'at',
                    'currency' => 'eur'
                );
            }

            foreach ($eids as $eid) {
                $k = $this->initKlarnaAPI(
                    $eid['eid'],
                    $eid['secret'],
                    $eid['country'],
                    $eid['lang'],
                    $eid['currency']
                );
                
                try {
                    $k->fetchPClasses();
                    $k->getAllPClasses();
                } catch (Exception $e) {
                    $errorMSG = "{$e->getMessage()} (#{$e->getCode()})";
                }
            }
        }

        $PS_COUNTRY_DEFAULT = (int)Configuration::get('PS_COUNTRY_DEFAULT');
        $country = new Country($PS_COUNTRY_DEFAULT);
        $country_iso_code = $country->iso_code;
        
        $cron_token = Tools::encrypt(Tools::encrypt(Tools::encrypt($this->name)));
        
        $showbanner = true;
        $showbanner1 = false;
        
        if (Configuration::get('KCO_IS_ACTIVE') == 1 ||
            Configuration::get('KPM_SHOW_IN_PAYMENTS') == 1
        ) {
            $showbanner = false;
        }
        
        if (Configuration::get('KCO_SWEDEN') == 1 ||
            Configuration::get('KCO_NORWAY') == 1 ||
            Configuration::get('KCO_FINLAND') == 1 ||
            Configuration::get('KCO_GERMANY') == 1 ||
            Configuration::get('KCO_AUSTRIA') == 1
        ) {
            $showbanner1 = true;
        }
        
        $platformVersion = _PS_VERSION_;
        $plugin = $this->name;
        $pluginVersion = $this->version;
        
        $isPHP7_warning = false;
        if (version_compare(phpversion(), '7.0.0', '>') && Configuration::get('KPM_SHOW_IN_PAYMENTS')) {
            $isPHP7_warning = true;
        }
        
        $isMAINTENANCE_warning = false;
        if (0 === (int)Configuration::get('PS_SHOP_ENABLE')) {
            $isMAINTENANCE_warning = true;
        }
        
        $isRounding_warning = false;
        if (1 !== (int)Configuration::get('PS_ROUND_TYPE')) {
            $isRounding_warning = true;
        }
        
        $isNoSll_warning = false;
        if (0 === (int)Configuration::get('PS_SSL_ENABLED')) {
            $isNoSll_warning = true;
        }
        
        $isNoDecimal_warning = false;
        if (0 === (int)Configuration::get('PS_PRICE_DISPLAY_PRECISION')) {
            $isNoDecimal_warning = true;
        }
        
        $kcov3_is_active = false;
        if (1 === (int)Configuration::get('KCOV3')) {
            $kcov3_is_active = true;
        }
        
        $toggle_js_inputs = array();

        $numInputs = 2;
        foreach (Country::getCountries(Configuration::get('PS_LANG_DEFAULT'), true) as $country) {
            $toggle_js_inputs['KLARNA_ONSITEMESSAGING_SWITCH_COUNTRY_'.$country['iso_code']] = $numInputs;
        }
        
        $cron_domain = $this->context->link->getBaseLink(null, true, false);

        $this->context->smarty->assign(array(
            'klarnaisocodedef' => $country_iso_code,
            'country' => $country_iso_code,
            'kcov3_is_active' => $kcov3_is_active,
            'showbanner1' => $showbanner1,
            'showbanner' => $showbanner,
            'platformVersion' => $platformVersion,
            'pluginVersion' => $pluginVersion,
            'plugin' => $plugin,
            'isMAINTENANCE_warning' => $isMAINTENANCE_warning,
            'isPHP7_warning' => $isPHP7_warning,
            'isRounding_warning' => $isRounding_warning,
            'isNoDecimal_warning' => $isNoDecimal_warning,
            'isNoSll_warning' => $isNoSll_warning,
            'cron_token' => $cron_token,
            'cron_domain' => $cron_domain,
            'errorMSG' => $errorMSG,
            'address_check_done' => $address_check_done,
            'isSaved' => $isSaved,
            'toggle_js_inputs' => Tools::jsonEncode($toggle_js_inputs),
            'osmform' => $this->createOSMForm(),
            'invoice_fee_not_found' => $invoice_fee_not_found,
            'commonform' => $this->createCommonForm(),
            'kpmform' => $this->createKPMForm(),
            'kcocommonform' => $this->createKCOCommonForm(),
            'kcov3form' => $this->createKCOV3Form(),
            'kcoform' => $this->createKCOForm(),
            'pclasslist' => $this->renderPclassList(),
            'REQUEST_URI' => Tools::safeOutput($_SERVER['REQUEST_URI']),
        ));

        return '<script type="text/javascript">var pwd_base_uri = "'.
        __PS_BASE_URI__.'";var pwd_refer = "'.
        (int) Tools::getValue('ref').'";</script>'.
        $this->display(__FILE__, 'views/templates/admin/klarna_admin.tpl');
    }

    public function createOSMForm()
    {
        $placements = array();
        $placements[] = array('value' => '0', 'label' => $this->l('Select type'));
        foreach (self::OSM_PLACEMENTS as $placementID) {
            $placements[] = array('value' => $placementID, 'label' => $placementID);
        }
        
        $countries = Country::getCountries($this->context->language->id, true);
        $fields_form = array();
        $fields_form[0]['form'] = array(
            'legend' => array(
                'icon' => 'icon-cogs',
                'title' => $this->l('On site messaging'),
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Activate on-site messaging'),
                    'name' => 'KLARNA_ONSITE_MESSAGE',
                    'is_bool' => true,
                    'desc' => $this->l('Activate Klarna On-Site Messaging'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => true,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => false,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Use USA library'),
                    'name' => 'KLARNA_ONSITEMESSAGING_LIBRARY_PATH_COUNTRY',
                    'is_bool' => true,
                    'desc' => $this->l('Default is EU library'),
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => true,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => false,
                            'label' => $this->l('No')
                        )
                    ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Data Client ID'),
                    'desc' => $this->l('Enter the data client ID for your shop'),
                    'name' => 'KLARNA_ONSITE_MESSAGE_DCI',
                    'class' => 'fixed-width-lg',
                    'required' => false,
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ),
        );
        
        foreach (Country::getCountries(Configuration::get('PS_LANG_DEFAULT'), true) as $country) {
            $fields_form[0]['form']['input'][] = array(
                'label' => '',
                'type' => 'html',
                'name' => '',
                'html_content' => '
                    <div style="border-bottom:1px solid #eee;font-size:18px;font-weight:600;padding-bottom:10px;">'
                        .$country['name'] . ' ('.$country['iso_code'].')
                    </div>',
            );

            $fields_form[0]['form']['input'][] =  array(
                'tab' => 'onsite_messaging',
                'type' => 'switch',
                'desc' => $this->l('Activate On-Site Messaging for ').$country['name'],
                'name' => 'KLARNA_ONSITEMESSAGING_SWITCH_COUNTRY_'.$country['iso_code'],
                'is_bool' => true,
                'values' => array(
                    array(
                        'id' => 'active_on',
                        'value' => true,
                        'label' => $this->l('Yes')
                    ),
                    array(
                        'id' => 'active_off',
                        'value' => false,
                        'label' => $this->l('No')
                    )
                ),
            );

            /*Product placement*/
            $fields_form[0]['form']['input'][] =  array(
                'tab' => 'onsite_messaging',
                'type' => 'select',
                'label' => $this->l('Product page placement'),
                'name' => 'KLARNA_ONSITEMESSAGING_PRODUCT_PAGE_COUNTRY_'.$country['iso_code'],
                'options' => array(
                    'query' => $placements,
                    'id' => 'value',
                    'name' => 'label',
                ),
            );
            $fields_form[0]['form']['input'][] =  array(
                'tab' => 'onsite_messaging',
                'type' => 'select',
                'label' => $this->l('Product page placement theme'),
                'name' => 'KLARNA_ONSITEMESSAGING_PRODUCT_PAGE_THEME_COUNTRY_'.$country['iso_code'],
                'options' => array(
                    'query' => array(
                        array(
                            'value' => self::OSM_THEME_DEFAULT,
                            'label' => $this->l('Default'), ),
                        array(
                            'value' => self::OSM_THEME_DARK,
                            'label' => $this->l('Dark'), ),
                        array(
                            'value' => self::OSM_THEME_CUSTOM,
                            'label' => $this->l('Custom'), ),
                    ),
                    'id' => 'value',
                    'name' => 'label',
                ),
            );
            /*Cart placement*/
            // $fields_form[0]['form']['input'][] =  array(
                // 'tab' => 'onsite_messaging',
                // 'type' => 'select',
                // 'label' => $this->l('Cart placement'),
                // 'name' => 'KLARNA_ONSITEMESSAGING_CART_PLACEMENT_COUNTRY_'.$country['iso_code'],
                // 'options' => array(
                    // 'query' => $placements,
                    // 'id' => 'value',
                    // 'name' => 'label',
                // ),
            // );
            // $fields_form[0]['form']['input'][] =  array(
                // 'tab' => 'onsite_messaging',
                // 'type' => 'select',
                // 'label' => $this->l('Cart placement theme'),
                // 'name' => 'KLARNA_ONSITEMESSAGING_CART_PLACEMENT_THEME_COUNTRY_'.$country['iso_code'],
                // 'options' => array(
                    // 'query' => array(
                        // array(
                            // 'value' => self::OSM_THEME_DEFAULT,
                            // 'label' => $this->l('Default'), ),
                        // array(
                            // 'value' => self::OSM_THEME_DARK,
                            // 'label' => $this->l('Dark'), ),
                        // array(
                            // 'value' => self::OSM_THEME_CUSTOM,
                            // 'label' => $this->l('Custom'), ),
                    // ),
                    // 'id' => 'value',
                    // 'name' => 'label',
                // ),
            // );
        }
        
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        if (Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG')) {
            $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        } else {
            $helper->allow_employee_form_lang = 0;
        }
        
        $helper->submit_action = 'btnOSMSubmit';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).
        '&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm($fields_form);
    }
    
    public function renderPclassList()
    {
        $fields_list = array(
            'eid' => array(
                'title' => $this->l('EID'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'id' => array(
                'title' => $this->l('Pclass'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'type' => array(
                'title' => $this->l('Type'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'description' => array(
                'title' => $this->l('Description'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'months' => array(
                'title' => $this->l('Months'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'interestrate' => array(
                'title' => $this->l('Interest rate'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'invoicefee' => array(
                'title' => $this->l('Invoice fee'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'startfee' => array(
                'title' => $this->l('Start fee'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'minamount' => array(
                'title' => $this->l('Min amount'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'country' => array(
                'title' => $this->l('Country'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
            'expire' => array(
                'title' => $this->l('Valid to'),
                'type' => 'text',
                'search' => false,
                'orderby' => false,
            ),
        );

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->identifier = 'key_id';
        $helper->actions = array('delete');
        $helper->show_toolbar = true;
        $helper->toolbar_btn['new'] = array(
            'href' => AdminController::$currentIndex.'&configure='.
            $this->name.'&updateplcass'.$this->name.
            '&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Update pclasses'),
        );

        $helper->title = $this->l('Pclasses');
        $helper->table = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        $sql = "SELECT *, CONCAT(eid, '-', id) as key_id FROM `"._DB_PREFIX_."kpmpclasses`";
        $content = Db::getInstance()->ExecuteS($sql);

        return $helper->generateList($content, $fields_list);
    }

    public function createCommonForm()
    {
        $states = OrderState::getOrderStates((int) $this->context->cookie->id_lang);
        foreach ($states as $key => $state) {
            if ($state['id_order_state'] === Configuration::get('PS_OS_PAYMENT')) {
                unset($states[$key]);
            }
        }
        $states[] = array('id_order_state' => '-1', 'name' => $this->l('Deactivated'));
        
        $fields_form = array();
        $fields_form[0]['form'] = array(
                'legend' => array(
                    'title' => $this->l('Common Settings'),
                    'icon' => 'icon-cogs',
                  ),
                'input' => array(
                //common settings
                array(
                    'type' => 'switch',
                    'label' => $this->l('Testdrive'),
                    'name' => 'KCO_TESTMODE',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'testmode_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'testmode_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('Activate test drive.'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Send invoice method'),
                    'name' => 'KCO_SENDTYPE',
                    'desc' => $this->l('Send invoices by e-mail or regular mail.'),
                    'options' => array(
                        'query' => array(
                        array(
                            'value' => 1,
                            'label' => $this->l('E-mail'), ),
                        array(
                            'value' => 0,
                            'label' => $this->l('Mail'), ),
                    ),
                        'id' => 'value',
                        'name' => 'label',
                    ),
                ),

                array(
                    'type' => 'select',
                    'label' => $this->l('Activate order status'),
                    'name' => 'KCO_ACTIVATE_STATE',
                    'multiple' => true,
                    'desc' => $this->l('Activate order will be sent to klarna when this order status is set.'),
                    'options' => array(
                        'query' => $states,
                        'id' => 'id_order_state',
                        'name' => 'name',
                    ),
                ),

                array(
                    'type' => 'select',
                    'label' => $this->l('Cancel reservation status'),
                    'name' => 'KCO_CANCEL_STATE',
                    'multiple' => true,
                    'desc' => $this->l('Cancel order will be sent to klarna when this order status is set.'),
                    'options' => array(
                        'query' => $states,
                        'id' => 'id_order_state',
                        'name' => 'name',
                    ),
                ),

                array(
                    'type' => 'select',
                    'label' => $this->l('Show Payment Widget'),
                    'name' => 'KCO_SHOWPRODUCTPAGE',
                    'desc' => $this->l('Display payment options on the product page.'),
                    'options' => array(
                        'query' => array(
                        array(
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                        'id' => 'value',
                        'name' => 'label',
                    ),
                ),

                array(
                    'type' => 'select',
                    'label' => $this->l('Payment Widget Layout'),
                    'name' => 'KCO_PRODUCTPAGELAYOUT',
                    'desc' => $this->l('Choose a layout for the Payment widget.'),
                    'options' => array(
                        'query' => array(
                        array(
                            'value' => 'pale-v2',
                            'label' => $this->l('pale-v2'), ),
                        array(
                            'value' => 'dark-v2',
                            'label' => $this->l('dark-v2'), ),
                        array(
                            'value' => 'deep-v2',
                            'label' => $this->l('deep-v2'), ),
                        array(
                            'value' => 'deep-extra-v2',
                            'label' => $this->l('deep-extra-v2'), ),
                    ),
                        'id' => 'value',
                        'name' => 'label',
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Footer Tooltip Layout'),
                    'name' => 'KCO_FOOTERLAYOUT',
                    'desc' => $this->l('Choose a layout for the Footer Toolip.'),
                    'options' => array(
                        'query' => array(
                        array(
                            'value' => 'long-blue',
                            'label' => $this->l('long-blue (KCO)'), ),
                        array(
                            'value' => 'long-white',
                            'label' => $this->l('long-white (KCO)'), ),
                        array(
                            'value' => 'short-blue',
                            'label' => $this->l('short-blue (KCO)'), ),
                        array(
                            'value' => 'short-white',
                            'label' => $this->l('short-white (KCO)'), ),
                        array(
                            'value' => 'blue-black',
                            'label' => $this->l('blue-black (KPM)'), ),
                        array(
                            'value' => 'white',
                            'label' => $this->l('white (KPM)'), ),
                    ),
                        'id' => 'value',
                        'name' => 'label',
                    ),
                ),
                //common settings
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        if (Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG')) {
            $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        } else {
            $helper->allow_employee_form_lang = 0;
        }
        
        $helper->submit_action = 'btnCommonSubmit';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).
        '&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm($fields_form);
    }
    public function createKCOCommonForm()
    {
        $cms_pages = CMS::listCms(null, false, false);
                
        $fields_form = array();
        $fields_form[0]['form'] = array(
                'legend' => array(
                    'title' => $this->l('KCO Settings'),
                    'icon' => 'icon-AdminAdmin',
                  ),
                'input' => array(
                //KCO: GENERAL
                array(
                    'type' => 'switch',
                    'label' => $this->l('Active KCO in this shop'),
                    'name' => 'KCO_IS_ACTIVE',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'kco_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'kco_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('Activate KCO for this show, if set to no, KPM will be used.'),
                ),

                array(
                    'type' => 'switch',
                    'label' => $this->l('Send id_order or order identifier'),
                    'name' => 'KCO_ORDERID',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'orderidentifier_on',
                            'value' => 1,
                            'label' => $this->l('id_order'), ),
                        array(
                            'id' => 'orderidentifier_off',
                            'value' => 0,
                            'label' => $this->l('Reference'), ),
                    ),
                    'desc' => $this->l('Order identifier sent to Klarna Online, Yes = order reference, No = id_order.'),
                ),

                array(
                    'type' => 'switch',
                    'label' => $this->l('Round off total'),
                    'name' => 'KCO_ROUNDOFF',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'roundoff_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'roundoff_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('Round off total value.'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Allow messages'),
                    'name' => 'KCO_ALLOWMESSAGE',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'allowmessage_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'allowmessage_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('Allow customers to add a message to the order.'),
                ),

                array(
                    'type' => 'switch',
                    'label' => $this->l('Use two column checkout'),
                    'name' => 'KCO_LAYOUT',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'layout_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'layout_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('Use the two column layout.'),
                ),
                
                array(
                    'type' => 'switch',
                    'label' => $this->l('Force phone')." *",
                    'name' => 'KCO_FORCEPHONE',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'forcephone_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'forcephone_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('Force customers to enter phone number in KCO'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Allow separate delivery address'),
                    'name' => 'KCO_ALLOWSEPADDR',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'kco_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'kco_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('Allow customer to choose different delivery and invoice address.'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Activate Autofocus'),
                    'name' => 'KCO_AUTOFOCUS',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'autofocus_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'autofocus_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('Recommended setting is no.'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show shipping details'),
                    'name' => 'KCO_SHOW_SHIPDETAILS',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'showship_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'showship_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('Show carrier delay info on thank you page.'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Activate Callback'),
                    'name' => 'KCO_CALLBACK_CHECK',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'callback_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'callback_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('A quick check is done before order is accepted that the cart has not been modified, requires SSL support.'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show link to old checkout'),
                    'name' => 'KCO_SHOWLINK',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'showlink_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'showlink_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('Show a link to the old checkout.'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show link to KCO in checkout'),
                    'name' => 'KCO_SHOW_IN_PAYMENTS',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'showlink_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'showlink_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('Show a link to KCO in the old checkout.'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Date of birth mandatory')." *",
                    'name' => 'KCO_DOBMAN',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'kcodobman_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'kcodobman_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('Require that customer enters date of birth.'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Prefill customer info'),
                    'name' => 'KCO_PREFILL',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'coprefill_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'kcoprefill_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('IF customer is logged in, prefill info'),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Offer newsletter signup'),
                    'name' => 'KCO_ADD_NEWSLETTERBOX',
                    'desc' => $this->l('Show checkbox in kco window.'),
                    'options' => array(
                        'query' => array(
                        array(
                            'value' => 0,
                            'label' => $this->l('Yes, show sign up box'), ),
                        array(
                            'value' => 1,
                            'label' => $this->l('Yes, show sign up box (prechecked)'), ),
                        array(
                            'value' => 2,
                            'label' => $this->l('No, do not show (all customers set to subscribers)'), ),
                        array(
                            'value' => 3,
                            'label' => $this->l('No, do not show, customers are not set as subscribers'), ),
                    ),
                        'id' => 'value',
                        'name' => 'label',
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Terms page'),
                    'name' => 'KCO_TERMS_PAGE',
                    'desc' => $this->l('Set CMS page that contains the terms and conditions.'),
                    'options' => array(
                        'query' => $cms_pages,
                        'id' => 'id_cms',
                        'name' => 'meta_title',
                    ),
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Cancelation terms page'),
                    'name' => 'KCO_CANCEL_PAGE',
                    'desc' => $this->l('Set CMS page that contains the cancelation terms.'),
                    'options' => array(
                        'query' => $cms_pages,
                        'id' => 'id_cms',
                        'name' => 'meta_title',
                    ),
                ),
                array(
                    'type' => 'html',
                    'name' => '',
                    'label' => $this->l('KCO V3 Only settings below'),
                    'desc' => '<hr />',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Allowed customer types'),
                    'name' => 'KCO_ALLOWED_TYPES',
                    'desc' => $this->l('Select what customer types you allow to shop.'),
                    'options' => array(
                        'query' => array(
                        array(
                            'value' => 0,
                            'label' => $this->l('All customer types')),
                        array(
                            'value' => 1,
                            'label' => $this->l('Private customers (b2c)')),
                        array(
                            'value' => 2,
                            'label' => $this->l('Company customers (b2b)')),
                    ),
                        'id' => 'value',
                        'name' => 'label',
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show subtotal'),
                    'name' => 'KCO_SHOW_SUBTOT',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'showsubtot_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'showsubtot_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('Show subtotal in the KCO iframe'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('National identification number mandatory'),
                    'name' => 'KCO_NIN_MANDATORY',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'nidnum_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'nidnum_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('Makes national id number mandatory in the checkout.'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Title mandatory'),
                    'name' => 'KCO_TITLEMAN',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'kcotitle_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'kcotitle_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('Require that customer enters title.'),
                ),
                //KCO
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
        );
        
        $fields_form[1]['form'] = array(
                'legend' => array(
                    'title' => $this->l('Color settings'),
                    'icon' => 'icon-AdminParentPreferences',
                  ),
                'input' => array(
                //KCO: COLOR SETTINGS
                array(
                        'type' => 'color',
                        'class' => 'color mColorPickerInput',
                        'label' => $this->l('Color buttons'),
                        'name' => 'KCO_COLORBUTTON',
                        'desc' => $this->l('Adjust the color of buttons in KCO window.'),
                        'required' => false,
                    ),
                array(
                        'type' => 'color',
                        'class' => 'color mColorPickerInput',
                        'label' => $this->l('Color button text'),
                        'name' => 'KCO_COLORBUTTONTEXT',
                        'desc' => $this->l('Adjust the color of texts on buttons in KCO window.'),
                        'required' => false,
                    ),
                array(
                        'type' => 'color',
                        'class' => 'color mColorPickerInput',
                        'label' => $this->l('Color checkbox'),
                        'name' => 'KCO_COLORCHECKBOX',
                        'desc' => $this->l('Adjust the color of checkbox in KCO window.'),
                        'required' => false,
                    ),
                array(
                        'type' => 'color',
                        'class' => 'color mColorPickerInput',
                        'label' => $this->l('Color checkbox marker'),
                        'name' => 'KCO_COLORCHECKBOXMARK',
                        'desc' => $this->l('Adjust the color of checkbox marker in KCO window.'),
                        'required' => false,
                    ),
                array(
                        'type' => 'color',
                        'class' => 'color mColorPickerInput',
                        'label' => $this->l('Color header'),
                        'name' => 'KCO_COLORHEADER',
                        'desc' => $this->l('Adjust the color of titles in KCO window.'),
                        'required' => false,
                    ),
                array(
                        'type' => 'color',
                        'class' => 'color mColorPickerInput',
                        'label' => $this->l('Color link'),
                        'desc' => $this->l('Adjust the color of all links in KCO window.'),
                        'name' => 'KCO_COLORLINK',
                        'required' => false,
                    ),
                array(
                        'type' => 'text',
                        'label' => $this->l('Radius border'),
                        'desc' => $this->l('Set the radius border'),
                        'name' => 'KCO_RADIUSBORDER',
                        'class' => 'fixed-width-lg',
                        'required' => false,
                    ),
                //KCO
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        if (Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG')) {
            $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        } else {
            $helper->allow_employee_form_lang = 0;
        }
        $helper->submit_action = 'btnKCOCommonSubmit';
        $helper->currentIndex = $this->context->link->getAdminLink(
            'AdminModules',
            false
        ).'&configure='.$this->name.
        '&tab_module='.$this->tab.'&module_name='.$this->name;
        
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm($fields_form);
    }
    public function createKPMForm()
    {
        $fields_form = array();

        $fields_form[0] = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('General settings'),
                    'icon' => 'icon-AdminAdmin',
                  ),
                'input' => array(
                //KPM
                array(
                    'type' => 'select',
                    'label' => $this->l('Klarna logo'),
                    'name' => 'KPM_LOGO',
                    'desc' => $this->l('Select what logo is used in the checkout.'),
                    'options' => array(
                        'query' => array(
                        array(
                            'value' => 'blue-black',
                            'label' => $this->l('Light background'), ),
                        array(
                            'value' => 'white',
                            'label' => $this->l('Dark background'), ),
                    ),
                        'id' => 'value',
                        'name' => 'label',
                    ),
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Invoice fee product'),
                    'name' => 'KPM_INVOICEFEE',
                    'class' => 'fixed-width-lg',
                    'required' => false,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Show link in payments options'),
                    'name' => 'KPM_SHOW_IN_PAYMENTS',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'kpmshowlink_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'kpmshowlink_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('Show the link to KPM payments in Checkout.'),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Disable invoices'),
                    'name' => 'KPM_DISABLE_INVOICE',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'kpminvoice_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'kpminvoice_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('Disables Invoice option in KPM.'),
                ),
                //KPM
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $fields_form[1] = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('General settings'),
                    'icon' => 'icon-AdminAdmin',
                  ),
                  //KPM: SWEDEN
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Sweden EID'),
                        'name' => 'KPM_SV_EID',
                        'class' => 'fixed-width-lg',
                        'required' => false,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Sweden shared secret'),
                        'name' => 'KPM_SV_SECRET',
                        'required' => false,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $fields_form[2] = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('General settings'),
                    'icon' => 'icon-AdminAdmin',
                  ),
                  //KPM: NORWAY
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Norway EID'),
                        'name' => 'KPM_NO_EID',
                        'class' => 'fixed-width-lg',
                        'required' => false,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Norway shared secret'),
                        'name' => 'KPM_NO_SECRET',
                        'required' => false,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $fields_form[3] = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('General settings'),
                    'icon' => 'icon-AdminAdmin',
                  ),
                  //KPM: FINLAND
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Finland EID'),
                        'class' => 'fixed-width-lg',
                        'name' => 'KPM_FI_EID',
                        'required' => false,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Finland shared secret'),
                        'name' => 'KPM_FI_SECRET',
                        'required' => false,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $fields_form[4] = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('General settings'),
                    'icon' => 'icon-AdminAdmin',
                  ),
                  //KPM: DENMARK
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Denmark EID'),
                        'class' => 'fixed-width-lg',
                        'name' => 'KPM_DA_EID',
                        'required' => false,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Denmark shared secret'),
                        'name' => 'KPM_DA_SECRET',
                        'required' => false,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $fields_form[5] = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('General settings'),
                    'icon' => 'icon-AdminAdmin',
                  ),
                  //KPM: GERMANY
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Germany EID'),
                        'class' => 'fixed-width-lg',
                        'name' => 'KPM_DE_EID',
                        'required' => false,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Germany shared secret'),
                        'name' => 'KPM_DE_SECRET',
                        'required' => false,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $fields_form[6] = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('General settings'),
                    'icon' => 'icon-AdminAdmin',
                  ),
                  //KPM: NETHERLANDS
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Netherlands EID'),
                        'class' => 'fixed-width-lg',
                        'name' => 'KPM_NL_EID',
                        'required' => false,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Netherlands shared secret'),
                        'name' => 'KPM_NL_SECRET',
                        'required' => false,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $fields_form[7] = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('General settings'),
                    'icon' => 'icon-AdminAdmin',
                  ),
                  //KPM: AUSTRIA
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Austria EID'),
                        'class' => 'fixed-width-lg',
                        'name' => 'KPM_AT_EID',
                        'required' => false,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Austria shared secret'),
                        'name' => 'KPM_AT_SECRET',
                        'required' => false,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        if (Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG')) {
            $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        } else {
            $helper->allow_employee_form_lang = 0;
        }
        $helper->submit_action = 'btnKPMSubmit';
        $helper->currentIndex = $this->context->link->getAdminLink(
            'AdminModules',
            false
        ).'&configure='.$this->name.
        '&tab_module='.$this->tab.'&module_name='.$this->name;
        
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm($fields_form);
    }
    public function createKCOV3Form()
    {
        $fields_form = array();

        $fields_form[0]['form'] = array(
                'legend' => array(
                    'title' => $this->l('Klarna Checkout V3'),
                    'icon' => 'icon-AdminParentLocalization',
                  ),
                'input' => array(
                //KCOV3: FI
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Active KCO V3'),
                        'name' => 'KCOV3',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'int3_on',
                                'value' => 1,
                                'label' => $this->l('Yes'), ),
                            array(
                                'id' => 'int3_off',
                                'value' => 0,
                                'label' => $this->l('No'), ),
                        ),
                        'desc' => $this->l('Activate KCO V3.'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Username'),
                        'name' => 'KCOV3_MID',
                        'class' => 'fixed-width-lg',
                        'required' => true,
                    ),
                array(
                        'type' => 'text',
                        'label' => $this->l('Password'),
                        'name' => 'KCOV3_SECRET',
                        'required' => true,
                    ),
                array(
                        'type' => 'switch',
                        'label' => $this->l('Use Guest accounts'),
                        'name' => 'KCOV3_USEGUESTACCOUNTS',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'guestaccount_on',
                                'value' => 1,
                                'label' => $this->l('Yes'), ),
                            array(
                                'id' => 'guestaccount_off',
                                'value' => 0,
                                'label' => $this->l('No'), ),
                        ),
                        'desc' => $this->l('Create guest accounts when new customer.'),
                    ),
                array(
                        'type' => 'switch',
                        'label' => $this->l('Active Prefill notification'),
                        'name' => 'KCOV3_PREFILNOT',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'deprefill3_on',
                                'value' => 1,
                                'label' => $this->l('Yes'), ),
                            array(
                                'id' => 'deprefill3_off',
                                'value' => 0,
                                'label' => $this->l('No'), ),
                        ),
                        'desc' => $this->l('Activate prefill notification for logged in customers.'),
                    ),
                array(
                        'type' => 'select',
                        'label' => $this->l('Show Klarna banner in footer.'),
                        'name' => 'KCOV3_FOOTERBANNER',
                        'desc' => $this->l('Select what banner to show in the footer.'),
                        'options' => array(
                            'query' => array(
                            array(
                                'value' => 0,
                                'label' => $this->l('Deactivated'), ),
                            array(
                                'value' => 1,
                                'label' => $this->l('Long version, White background'), ),
                            array(
                                'value' => 2,
                                'label' => $this->l('Long version, Black background'), ),
                            array(
                                'value' => 3,
                                'label' => $this->l('Short version, White background'), ),
                            array(
                                'value' => 4,
                                'label' => $this->l('Short version, Black background'), ),
                        ),
                            'id' => 'value',
                            'name' => 'label',
                        ),
                    ),
                    
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Activate custom checkbox'),
                        'name' => 'KCOV3_CUSTOM_CHECKBOX',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'cc_on',
                                'value' => 1,
                                'label' => $this->l('Yes'), ),
                            array(
                                'id' => 'cc_off',
                                'value' => 0,
                                'label' => $this->l('No'), ),
                        ),
                        'desc' => $this->l('Activate custom checkbox.'),
                    ),
                    
                array(
                    'type' => 'switch',
                    'label' => $this->l('Custom checkbox prechecked'),
                    'name' => 'KCOV3_CUSTOM_CHECKBOX_PRECHECKED',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'ccpc_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'ccpc_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('the checkbox is prechecked.'),
                ),
                
                array(
                    'type' => 'switch',
                    'label' => $this->l('Custom checkbox required'),
                    'name' => 'KCOV3_CUSTOM_CHECKBOX_REQUIRED',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'ccr_on',
                            'value' => 1,
                            'label' => $this->l('Yes'), ),
                        array(
                            'id' => 'ccr_off',
                            'value' => 0,
                            'label' => $this->l('No'), ),
                    ),
                    'desc' => $this->l('the checkbox is required to be checked.'),
                ),
                    
                array(
                        'type' => 'text',
                        'label' => $this->l('Custom Checkbox text'),
                        'name' => 'KCOV3_CUSTOM_CHECKBOX_TEXT',
                        'required' => false,
                        'lang' => true,
                    ),
                    
                array(
                        'type' => 'switch',
                        'label' => $this->l('Activate EPM'),
                        'name' => 'KCOV3_EXTERNAL_PAYMENT_METHOD_ACTIVE',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'epm_on',
                                'value' => 1,
                                'label' => $this->l('Yes'), ),
                            array(
                                'id' => 'epm_off',
                                'value' => 0,
                                'label' => $this->l('No'), ),
                        ),
                        'desc' => $this->l('Active External Payment Method (EPM). Approval required, check documentation'),
                    ),
                array(
                        'type' => 'select',
                        'label' => $this->l('EPM label'),
                        'name' => 'KCOV3_EXTERNAL_PAYMENT_METHOD_OPTION',
                        'desc' => $this->l('Choose from the drop down list of supported EPMs'),
                        'options' => array(
                            'query' => array(
                            array(
                                'value' => 0,
                                'label' => $this->l('Nothing selected'), ),
                            array('value' => 'Achteraf betalen', 'label' => 'Achteraf betalen'),
                            array('value' => 'Alipay', 'label' => 'Alipay'),
                            array('value' => 'Amazon', 'label' => 'Amazon'),
                            array('value' => 'Amazon Pay', 'label' => 'Amazon Pay'),
                            array('value' => 'Amex', 'label' => 'Amex'),
                            array('value' => 'Apple Pay', 'label' => 'Apple Pay'),
                            array('value' => 'Bancontact', 'label' => 'Bancontact'),
                            array('value' => 'Bank Transfer', 'label' => 'Bank Transfer'),
                            array('value' => 'Bankoverførsel', 'label' => 'Bankoverførsel'),
                            array('value' => 'Banköverföring', 'label' => 'Banköverföring'),
                            array('value' => 'Barzahlen', 'label' => 'Barzahlen'),
                            array('value' => 'Betal i butikk', 'label' => 'Betal i butikk'),
                            array('value' => 'Betal på verkstedet', 'label' => 'Betal på verkstedet'),
                            array('value' => 'Betala i butik', 'label' => 'Betala i butik'),
                            array('value' => 'Betala på plats', 'label' => 'Betala på plats'),
                            array('value' => 'Betala på station', 'label' => 'Betala på station'),
                            array('value' => 'Betalning på betjäningsstället', 'label' => 'Betalning på betjäningsstället'),
                            array('value' => 'bitcoin Global', 'label' => 'bitcoin Global'),
                            array('value' => 'Bonifico Bancario', 'label' => 'Bonifico Bancario'),
                            array('value' => 'Bussiennakko', 'label' => 'Bussiennakko'),
                            array('value' => 'Card via PayPal', 'label' => 'Card via PayPal'),
                            array('value' => 'Carte Bancaires', 'label' => 'Carte Bancaires'),
                            array('value' => 'Carte Bleue', 'label' => 'Carte Bleue'),
                            array('value' => 'Cash on Delivery', 'label' => 'Cash on Delivery'),
                            array('value' => 'Cash on Hand', 'label' => 'Cash on Hand'),
                            array('value' => 'ClickandBuy', 'label' => 'ClickandBuy'),
                            array('value' => 'CoinPayments', 'label' => 'CoinPayments'),
                            array('value' => 'Dankort', 'label' => 'Dankort'),
                            array('value' => 'Consors Finanz', 'label' => 'Consors Finanz'),
                            array('value' => 'Delbetal i ditt eget tempo', 'label' => 'Delbetal i ditt eget tempo'),
                            array('value' => 'Delbetalning', 'label' => 'Delbetalning'),
                            array('value' => 'Divide.Connect', 'label' => 'Divide.Connect'),
                            array('value' => 'EAN Fakturering', 'label' => 'EAN Fakturering'),
                            array('value' => 'EU- standaard bankoverschrijving', 'label' => 'EU- standaard bankoverschrijving'),
                            array('value' => 'EU-Standard Bank Transfer', 'label' => 'EU-Standard Bank Transfer'),
                            array('value' => 'Ennakkomaksu', 'label' => 'Ennakkomaksu'),
                            array('value' => 'Faktura', 'label' => 'Faktura'),
                            array('value' => 'Faktura 14 dagar', 'label' => 'Faktura 14 dagar'),
                            array('value' => 'Faktura 14 dage', 'label' => 'Faktura 14 dage'),
                            array('value' => 'Fast delbetaling', 'label' => 'Fast delbetaling'),
                            array('value' => 'Forskudd', 'label' => 'Forskudd'),
                            array('value' => 'Förskottsbetalning', 'label' => 'Förskottsbetalning'),
                            array('value' => 'Gavekort', 'label' => 'Gavekort'),
                            array('value' => 'Giropay', 'label' => 'Giropay'),
                            array('value' => 'Google Wallet', 'label' => 'Google Wallet'),
                            array('value' => 'iDeal', 'label' => 'iDeal'),
                            array('value' => 'Konto', 'label' => 'Konto'),
                            array('value' => 'Kort', 'label' => 'Kort'),
                            array('value' => 'Kortti', 'label' => 'Kortti'),
                            array('value' => 'Kreditkarte', 'label' => 'Kreditkarte'),
                            array('value' => 'Lasku 14 päivää', 'label' => 'Lasku 14 päivää'),
                            array('value' => 'Lastschrift', 'label' => 'Lastschrift'),
                            array('value' => 'M-Cash', 'label' => 'M-Cash'),
                            array('value' => 'Maksu noudon yhteydessä', 'label' => 'Maksu noudon yhteydessä'),
                            array('value' => 'Maksu liikkeessä', 'label' => 'Maksu liikkeessä'),
                            array('value' => 'Maksu palvelupisteellä', 'label' => 'Maksu palvelupisteellä'),
                            array('value' => 'MobilePay', 'label' => 'MobilePay'),
                            array('value' => 'Multibanco', 'label' => 'Multibanco'),
                            array('value' => 'Nachnahme', 'label' => 'Nachnahme'),
                            array('value' => 'Pagamento Alla Consegna', 'label' => 'Pagamento Alla Consegna'),
                            array('value' => 'Partner', 'label' => 'Partner'),
                            array('value' => 'Pay at office', 'label' => 'Pay at office'),
                            array('value' => 'Pay at station', 'label' => 'Pay at station'),
                            array('value' => 'Pay by Card or PayPal', 'label' => 'Pay by Card or PayPal'),
                            array('value' => 'Pay in-store', 'label' => 'Pay in-store'),
                            array('value' => 'Paydirekt', 'label' => 'Paydirekt'),
                            array('value' => 'PayPal', 'label' => 'PayPal'),
                            array('value' => 'PayPalExpress', 'label' => 'PayPalExpress'),
                            array('value' => 'Postförskott', 'label' => 'Postförskott'),
                            array('value' => 'Postiennakko', 'label' => 'Postiennakko'),
                            array('value' => 'Postoppkrav', 'label' => 'Postoppkrav'),
                            array('value' => 'SOFORT Überweisung', 'label' => 'SOFORT Überweisung'),
                            array('value' => 'Strix', 'label' => 'Strix'),
                            array('value' => 'Swish', 'label' => 'Swish'),
                            array('value' => 'Transferencia Bancaria', 'label' => 'Transferencia Bancaria'),
                            array('value' => 'Verkkolasku', 'label' => 'Verkkolasku'),
                            array('value' => 'Verkkomaksu', 'label' => 'Verkkomaksu'),
                            array('value' => 'Vipps', 'label' => 'Vipps'),
                            array('value' => 'Virement bancaire', 'label' => 'Virement bancaire'),
                            array('value' => 'Vorkasse', 'label' => 'Vorkasse'),
                            array('value' => 'Vorkasse Banküberweisung', 'label' => 'Vorkasse Banküberweisung'),
                            array('value' => 'Wire Transfer', 'label' => 'Wire Transfer'),
                            array('value' => 'Zahlung bei Abholung', 'label' => 'Zahlung bei Abholung'),
                            array('value' => '銀行振込', 'label' => '銀行振込'),
                        ),
                            'id' => 'value',
                            'name' => 'label',
                        ),
                    ),
                array(
                        'type' => 'text',
                        'label' => $this->l('EPM image url'),
                        'name' => 'KCOV3_EXTERNAL_PAYMENT_METHOD_IMGURL',
                        'required' => false,
                        'desc' => $this->l('Requires URI with https. Logo is displayed in checkout footer and in the list of offered payment methods'),
                    ),
                array(
                        'type' => 'text',
                        'label' => $this->l('EPM Fee'),
                        'name' => 'KCOV3_EXTERNAL_PAYMENT_METHOD_FEE',
                        'required' => false,
                        'desc' => $this->l('Enter in minor units, fee to be displayed in the checkout'),
                    ),
                array(
                        'type' => 'text',
                        'label' => $this->l('EPM limit countries'),
                        'name' => 'KCOV3_EXTERNAL_PAYMENT_METHOD_COUNTRIES',
                        'required' => false,
                        'desc' => $this->l('Enter ISO codes separated by a comma (se,de) if you want to limit the option to specific countries.'),
                    ),
                array(
                        'type' => 'text',
                        'label' => $this->l('EPM external url'),
                        'name' => 'KCOV3_EXTERNAL_PAYMENT_METHOD_EXTERNALURL',
                        'required' => false,
                        'desc' => $this->l('Enter external redirect url. You as a merchant are responsible to implement the handling of the payments and order handling after redirect. If empty, redirected to Prestashop default checkout.'),
                    ),
                array(
                        'type' => 'text',
                        'label' => $this->l('EPM Description'),
                        'name' => 'KCOV3_EXTERNAL_PAYMENT_METHOD_DESC',
                        'required' => false,
                        'desc' => 'Custom description displayed when EPM selected (max 500 characters). Else default text displayed (i.e. for English: PayPal is provided by "Your business name")',
                        'lang' => true,
                    ),
                array(
                        'type' => 'select',
                        'label' => $this->l('EPM label'),
                        'name' => 'KCOV3_EXTERNAL_PAYMENT_METHOD_LABEL',
                        'desc' => $this->l('Choose the label text'),
                        'options' => array(
                            'query' => array(
                            array(
                                'value' => 0,
                                'label' => $this->l('Continue'), ),
                            array(
                                'value' => 1,
                                'label' => $this->l('Complete'), ),
                        ),
                            'id' => 'value',
                            'name' => 'label',
                        ),
                    ),

                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
        );
        
        $fields_form[1]['form'] = array(
                'legend' => array(
                    'title' => $this->l('United states'),
                    'icon' => 'icon-AdminParentLocalization',
                  ),
                'input' => array(
                //KCO: US
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Active KCO US'),
                        'name' => 'KCO_US',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'us_on',
                                'value' => 1,
                                'label' => $this->l('Yes'), ),
                            array(
                                'id' => 'us_off',
                                'value' => 0,
                                'label' => $this->l('No'), ),
                        ),
                        'desc' => $this->l('Activate KCO for US, USD and EN language required.'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('EID'),
                        'name' => 'KCO_US_EID',
                        'class' => 'fixed-width-lg',
                        'required' => true,
                    ),

                array(
                        'type' => 'text',
                        'label' => $this->l('Shared secret'),
                        'name' => 'KCO_US_SECRET',
                        'required' => true,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
        );
        
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        if (Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG')) {
            $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        } else {
            $helper->allow_employee_form_lang = 0;
        }
        $helper->submit_action = 'btnKCOV3Submit';
        $helper->currentIndex = $this->context->link->getAdminLink(
            'AdminModules',
            false
        ).'&configure='.$this->name.
        '&tab_module='.$this->tab.'&module_name='.$this->name;
        
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm($fields_form);
    }
    
    public function createKCOForm()
    {
        $fields_form = array();

        $fields_form[0]['form'] = array(
                'legend' => array(
                    'title' => $this->l('Sweden'),
                    'icon' => 'icon-AdminParentLocalization',
                  ),
                'input' => array(
                //KCO: SWEDEN
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Active KCO Sweden'),
                        'name' => 'KCO_SWEDEN',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'sweden_on',
                                'value' => 1,
                                'label' => $this->l('Yes'), ),
                            array(
                                'id' => 'sweden_off',
                                'value' => 0,
                                'label' => $this->l('No'), ),
                        ),
                        'desc' => $this->l('Activate KCO for Sweden, SEK and SE language required.'),
                    ),
                    
                    array(
                        'type' => 'select',
                        'label' => $this->l('Active B2B for Sweden.'),
                        'name' => 'KCO_SWEDEN_B2B',
                        'desc' => $this->l('Activate B2B KCO for Sweden, SEK and SE language required.'),
                        'options' => array(
                            'query' => array(
                            array(
                                'value' => 0,
                                'label' => $this->l('No'), ),
                            array(
                                'value' => 1,
                                'label' => $this->l('Yes, B2C preselected.'), ),
                            array(
                                'value' => 2,
                                'label' => $this->l('Yes, B2B preselected.'), ),
                            array(
                                'value' => 3,
                                'label' => $this->l('Yes, B2C deactivated.'), ),
                        ),
                            'id' => 'value',
                            'name' => 'label',
                        ),
                    ),

                    array(
                        'type' => 'text',
                        'label' => $this->l('EID'),
                        'name' => 'KCO_SWEDEN_EID',
                        'class' => 'fixed-width-lg',
                        'required' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Shared secret'),
                        'name' => 'KCO_SWEDEN_SECRET',
                        'required' => true,
                    ),

                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
        );

        $fields_form[1]['form'] = array(
                'legend' => array(
                    'title' => $this->l('Norway'),
                    'icon' => 'icon-AdminParentLocalization',
                  ),
                'input' => array(
                //KCO: NORWAY
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Active KCO Norway'),
                        'name' => 'KCO_NORWAY',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'norway_on',
                                'value' => 1,
                                'label' => $this->l('Yes'), ),
                            array(
                                'id' => 'norway_off',
                                'value' => 0,
                                'label' => $this->l('No'), ),
                        ),
                        'desc' => $this->l('Activate KCO for Norway, NOK and NO language required.'),
                    ),
                    
                    array(
                        'type' => 'select',
                        'label' => $this->l('Active B2B for Norway.'),
                        'name' => 'KCO_NORWAY_B2B',
                        'desc' => $this->l('Activate B2B KCO for Norway, NOK and NO language required.'),
                        'options' => array(
                            'query' => array(
                            array(
                                'value' => 0,
                                'label' => $this->l('No'), ),
                            array(
                                'value' => 1,
                                'label' => $this->l('Yes, B2C preselected.'), ),
                            array(
                                'value' => 2,
                                'label' => $this->l('Yes, B2B preselected.'), ),
                            array(
                                'value' => 3,
                                'label' => $this->l('Yes, B2C deactivated.'), ),
                        ),
                            'id' => 'value',
                            'name' => 'label',
                        ),
                    ),

                    array(
                        'type' => 'text',
                        'label' => $this->l('EID'),
                        'name' => 'KCO_NORWAY_EID',
                        'class' => 'fixed-width-lg',
                        'required' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Shared secret'),
                        'name' => 'KCO_NORWAY_SECRET',
                        'required' => true,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
        );

        $fields_form[2]['form'] = array(
                'legend' => array(
                    'title' => $this->l('Finland'),
                    'icon' => 'icon-AdminParentLocalization',
                  ),
                'input' => array(
                //KCO: FINLAND
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Active KCO Finland'),
                        'name' => 'KCO_FINLAND',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'finland_on',
                                'value' => 1,
                                'label' => $this->l('Yes'), ),
                            array(
                                'id' => 'finland_off',
                                'value' => 0,
                                'label' => $this->l('No'), ),
                        ),
                        'desc' => $this->l('Activate KCO for Finland, EUR and FI and or SE languages required.'),
                    ),
                    
                    array(
                        'type' => 'select',
                        'label' => $this->l('Active B2B for Finland.'),
                        'name' => 'KCO_FINLAND_B2B',
                        'desc' => $this->l('Activate B2B KCO for Finland, EUR and FI or SV language required.'),
                        'options' => array(
                            'query' => array(
                            array(
                                'value' => 0,
                                'label' => $this->l('No'), ),
                            array(
                                'value' => 1,
                                'label' => $this->l('Yes, B2C preselected.'), ),
                            array(
                                'value' => 2,
                                'label' => $this->l('Yes, B2B preselected.'), ),
                            array(
                                'value' => 3,
                                'label' => $this->l('Yes, B2C deactivated.'), ),
                        ),
                            'id' => 'value',
                            'name' => 'label',
                        ),
                    ),
                    
                    array(
                        'type' => 'text',
                        'label' => $this->l('EID'),
                        'name' => 'KCO_FINLAND_EID',
                        'class' => 'fixed-width-lg',
                        'required' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Shared secret'),
                        'name' => 'KCO_FINLAND_SECRET',
                        'required' => true,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
        );

        $fields_form[3]['form'] = array(
                'legend' => array(
                    'title' => $this->l('Germany'),
                    'icon' => 'icon-AdminParentLocalization',
                  ),
                'input' => array(
                //KCO: GERMANY
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Active KCO Germany'),
                        'name' => 'KCO_GERMANY',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'germany_on',
                                'value' => 1,
                                'label' => $this->l('Yes'), ),
                            array(
                                'id' => 'germany_off',
                                'value' => 0,
                                'label' => $this->l('No'), ),
                        ),
                        'desc' => $this->l('Activate KCO for Germany, EUR and DE language required.'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Prefill notification'),
                        'name' => 'KCO_DE_PREFILNOT',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'germanyprefill_on',
                                'value' => 1,
                                'label' => $this->l('Yes'), ),
                            array(
                                'id' => 'germanyprefill_off',
                                'value' => 0,
                                'label' => $this->l('No'), ),
                        ),
                        'desc' => $this->l('Activate Prefill notification.'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('EID'),
                        'name' => 'KCO_GERMANY_EID',
                        'class' => 'fixed-width-lg',
                        'required' => true,
                    ),

                array(
                        'type' => 'text',
                        'label' => $this->l('Shared secret'),
                        'name' => 'KCO_GERMANY_SECRET',
                        'required' => true,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
        );

        $fields_form[4]['form'] = array(
                'legend' => array(
                    'title' => $this->l('Austria'),
                    'icon' => 'icon-AdminParentLocalization',
                  ),
                'input' => array(
                //KCO: Austria
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Active KCO Austria'),
                        'name' => 'KCO_AUSTRIA',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'austria_on',
                                'value' => 1,
                                'label' => $this->l('Yes'), ),
                            array(
                                'id' => 'austria_off',
                                'value' => 0,
                                'label' => $this->l('No'), ),
                        ),
                        'desc' => $this->l('Activate KCO for Austria, EUR and DE language required.'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('EID'),
                        'name' => 'KCO_AUSTRIA_EID',
                        'class' => 'fixed-width-lg',
                        'required' => true,
                    ),

                array(
                        'type' => 'text',
                        'label' => $this->l('Shared secret'),
                        'name' => 'KCO_AUSTRIA_SECRET',
                        'required' => true,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
        );
        
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        if (Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG')) {
            $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        } else {
            $helper->allow_employee_form_lang = 0;
        }
        $helper->submit_action = 'btnKCOSubmit';
        $helper->currentIndex = $this->context->link->getAdminLink(
            'AdminModules',
            false
        ).'&configure='.$this->name.
        '&tab_module='.$this->tab.'&module_name='.$this->name;
        
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm($fields_form);
    }

    public function getConfigFieldsValues()
    {
        $returnarray = array();
        
        $json_fields = array(
            "KCOV3_CUSTOM_CHECKBOX_TEXT",
            "KCOV3_EXTERNAL_PAYMENT_METHOD_DESC",
        );
        $multi_selectfields = array(
            "KCO_ACTIVATE_STATE",
            "KCO_CANCEL_STATE",
        );
        
        foreach ($this->configuration_params as $param) {
            if (in_array($param, $json_fields)) {
                $jsonstring = Configuration::get($param);
                $dataarray = Tools::jsonDecode($jsonstring, true);
                $returnarray[$param] = $dataarray;
            } elseif (in_array($param, $multi_selectfields)) {
                $jsonstring = Configuration::get($param);
                $dataarray = Tools::jsonDecode($jsonstring, true);
                $returnarray[$param."[]"] = $dataarray;
            } elseif ("KLARNA_ONSITEMESSAGING_CONFIGURATION" == $param) {
                $jsonstring = Configuration::get($param);
                $dataarray = Tools::jsonDecode($jsonstring, true);
                $countries = Country::getCountries($this->context->language->id, true);
               
                foreach ($countries as $country) {
                    foreach ($this->osm_fields as $osmfield) {
                        $Key = $osmfield.$country['iso_code'];
                        if (isset($dataarray[$country['iso_code']]) && isset($dataarray[$country['iso_code']][$Key])) {
                            $returnarray[$Key] = $dataarray[$country['iso_code']][$Key];
                        } else {
                            $returnarray[$Key] = "";
                        }
                    }
                }
            } else {
                $returnarray[$param] = Tools::getValue($param, Configuration::get($param));
            }
        }
        return $returnarray;
    }

    // Onsite messaging
    public function displayOnsiteMessagingPlacements($displayLocation, $extraParams = null)
    {
        if (!self::isValidCountryCurrencyOSM()) {
            return;
        }

        $OSMconfig = Tools::jsonDecode(Configuration::get('KLARNA_ONSITEMESSAGING_CONFIGURATION'), true);
        $use_osm_key = 'KLARNA_ONSITEMESSAGING_SWITCH_COUNTRY_'.$this->context->country->iso_code;

        if (!isset($OSMconfig[$this->context->country->iso_code]) ||
            !isset($OSMconfig[$this->context->country->iso_code][$use_osm_key])
        ) {
            return;
        }
        $klarna_placement = array();
        // Check if country is active
        if ((bool) $OSMconfig[$this->context->country->iso_code][$use_osm_key]) {
            $languageIsoCode = $this->context->language->iso_code;
            $languageIsoCode = str_replace('gb', 'en', $languageIsoCode);
            
            if ("cart" == $displayLocation) {
                $theme_key = 'KLARNA_ONSITEMESSAGING_CART_PLACEMENT_THEME_COUNTRY_'.$this->context->country->iso_code;
                $placement_key = 'KLARNA_ONSITEMESSAGING_CART_PLACEMENT_COUNTRY_'.$this->context->country->iso_code;
                
                if (!isset($OSMconfig[$this->context->country->iso_code]) ||
                    !isset($OSMconfig[$this->context->country->iso_code][$theme_key]) ||
                    !isset($OSMconfig[$this->context->country->iso_code][$placement_key]) ||
                    '0' === $OSMconfig[$this->context->country->iso_code][$placement_key]
                ) {
                    return;
                }

                $klarna_placement['purchase_amount'] = Tools::ps_round($extraParams, 2) * 100;
                $klarna_placement['theme'] = $OSMconfig[$this->context->country->iso_code][$theme_key];
                $klarna_placement['id'] = $OSMconfig[$this->context->country->iso_code][$placement_key];
                $klarna_placement['locale'] = $languageIsoCode.'-'.$this->context->country->iso_code;
            }
            if ("product" == $displayLocation) {
                $theme_key = 'KLARNA_ONSITEMESSAGING_PRODUCT_PAGE_THEME_COUNTRY_'.$this->context->country->iso_code;
                $placement_key = 'KLARNA_ONSITEMESSAGING_PRODUCT_PAGE_COUNTRY_'.$this->context->country->iso_code;

                if (!isset($OSMconfig[$this->context->country->iso_code]) ||
                    !isset($OSMconfig[$this->context->country->iso_code][$theme_key]) ||
                    !isset($OSMconfig[$this->context->country->iso_code][$placement_key]) ||
                    '0' === $OSMconfig[$this->context->country->iso_code][$placement_key]
                ) {
                    return;
                }
                if (isset($extraParams['product']->price)) {
                    $purchase_amount = Tools::ps_round($extraParams['product']->price, 2);
                    $klarna_placement['purchase_amount'] = $purchase_amount * 100;
                    $klarna_placement['theme'] = $OSMconfig[$this->context->country->iso_code][$theme_key];
                    $klarna_placement['id'] = $OSMconfig[$this->context->country->iso_code][$placement_key];
                    $klarna_placement['locale'] = $languageIsoCode.'-'.$this->context->country->iso_code;
                }
            }
            $this->smarty->assign('klarna_placement', $klarna_placement);
            return $this->display(__FILE__, 'onsite_messaging.tpl');
        }
    }
    public static function isValidCountryCurrencyOSM()
    {
        $countryIsoCode = Context::getContext()->country->iso_code;

        // Check if country+currency matches any of the non-EUR cases defined in the constant, else should be EUR
        $php5compatiblefix = self::OSM_VALID_COUNTRY_CURRENCY_COMBINATION;
        if (isset($php5compatiblefix[$countryIsoCode])) {
            $defaultCurrency = self::OSM_VALID_COUNTRY_CURRENCY_COMBINATION[$countryIsoCode];

            if ($defaultCurrency === Context::getContext()->currency->iso_code) {
                return true;
            }
            
            return false;
        } else {
            if (Context::getContext()->currency->iso_code === 'EUR') {
                return true;
            }

            return false;
        }
    }
    
    public static function getOnSiteMessagingUrl()
    {
        $eu_test_path = 'https://eu-library.playground.klarnaservices.com/lib.js';
        $eu_path = 'https://eu-library.klarnaservices.com/lib.js';
        
        $us_test_path = 'https://us-library.playground.klarnaservices.com/lib.js';
        $us_path = 'https://us-library.klarnaservices.com/lib.js';
        
        $url = $eu_path;
        if ((int) (Configuration::get('KCO_TESTMODE')) == 1) {
            if ((int) (Configuration::get('KLARNA_ONSITEMESSAGING_LIBRARY_PATH_COUNTRY')) == 1) {
                $url = $us_test_path;
            } else {
                $url = $eu_test_path;
            }
        } else {
            if ((int) (Configuration::get('KLARNA_ONSITEMESSAGING_LIBRARY_PATH_COUNTRY')) == 1) {
                $url = $us_path;
            } else {
                $url = $eu_path;
            }
        }
        
        return $url;
    }
    // Onsite messaging
    public function hookDisplayShoppingCart()
    {
        if ((bool) Configuration::get('KLARNA_ONSITE_MESSAGE')) {
            $totalAmount = $this->context->cart->getOrderTotal();
            return $this->displayOnsiteMessagingPlacements('cart', $totalAmount);
        }
    }
    
    public function hookDisplayProductButtons($params)
    {
        if ((bool) Configuration::get('KLARNA_ONSITE_MESSAGE')) {
            return $this->displayOnsiteMessagingPlacements('product', $params);
        }
        
        if ((int) Configuration::get('KCO_SHOWPRODUCTPAGE') == 0) {
            return;
        }
        if (Configuration::get('PS_CATALOG_MODE')) {
            return;
        }
        if (configuration::get('KPM_INVOICEFEE') != '') {
            $invoicefee = $this->getByReference(Configuration::get('KPM_INVOICEFEE'));
            if (Validate::isLoadedObject($invoicefee)) {
                $klarna_invoice_fee = $invoicefee->getPrice();
            } else {
                $klarna_invoice_fee = 0;
            }
        } else {
            $klarna_invoice_fee = 0;
        }

        $klarna_eid = '';
        $country_iso = '';
        if (isset($this->context->cart) &&
        isset($this->context->cart->id_address_delivery) &&
        (int) $this->context->cart->id_address_delivery > 0) {
            $address = new Address($this->context->cart->id_address_delivery);
            $country_iso = Country::getIsoById($address->id_country);
        } else {
            if (isset($this->context->language) &&
            isset($this->context->language->id) &&
            (int) $this->context->language->id > 0) {
                $language_iso = Language::getIsoById((int) $this->context->language->id);
            } else {
                $language_iso = Language::getIsoById(Configuration::get('PS_LANG_DEFAULT'));
            }
            $language_iso = Tools::strtolower($language_iso);
            if ($language_iso == 'sv') {
                $country_iso = 'se';
            }
            if ($language_iso == 'no' || $language_iso == 'nn' || $language_iso == 'nb') {
                $country_iso = 'no';
            }
            if ($language_iso == 'fi') {
                $country_iso = 'fi';
            }
            if ($language_iso == 'de') {
                $country_iso = 'de';
            }
            if ($language_iso == 'da') {
                $country_iso = 'da';
            }
            if ($language_iso == 'at') {
                $country_iso = 'at';
            }
            if ($language_iso == 'en') {
                $country_iso = 'gb';
            }
        }

        if ($country_iso == '') {
            $country_iso = Country::getIsoById(Configuration::get('PS_COUNTRY_DEFAULT'));
        }
        $country_iso = Tools::strtolower($country_iso);

        if ($country_iso == 'se') {
            if ((int) Configuration::get('KCO_SWEDEN', null, null, $this->context->shop->id) == 1) {
                $klarna_eid = Configuration::get('KCO_SWEDEN_EID', null, null, $this->context->shop->id);
            } else {
                $klarna_eid = Configuration::get('KPM_SV_EID', null, null, $this->context->shop->id);
            }
        }
        if ($country_iso == 'no') {
            if ((int) Configuration::get('KCO_NORWAY', null, null, $this->context->shop->id) == 1) {
                $klarna_eid = Configuration::get('KCO_NORWAY_EID', null, null, $this->context->shop->id);
            } else {
                $klarna_eid = Configuration::get('KPM_NO_EID', null, null, $this->context->shop->id);
            }
        }
        if ($country_iso == 'de') {
            if ((int) Configuration::get('KCO_GERMANY', null, null, $this->context->shop->id) == 1) {
                $klarna_eid = Configuration::get('KCO_GERMANY_EID', null, null, $this->context->shop->id);
            } else {
                $klarna_eid = Configuration::get('KPM_DE_EID', null, null, $this->context->shop->id);
            }
        }
        if ($country_iso == 'da') {
            $klarna_eid = Configuration::get('KPM_DA_EID', null, null, $this->context->shop->id);
        }
        if ($country_iso == 'fi') {
            if ((int) Configuration::get('KCO_FINLAND', null, null, $this->context->shop->id) == 1) {
                $klarna_eid = Configuration::get('KCO_FINLAND_EID', null, null, $this->context->shop->id);
            } else {
                $klarna_eid = Configuration::get('KPM_FI_EID', null, null, $this->context->shop->id);
            }
        }
        if ($country_iso == 'nl') {
            $klarna_eid = Configuration::get('KPM_NL_EID', null, null, $this->context->shop->id);
        }
        if ($country_iso == 'at') {
            if ((int) Configuration::get('KCO_AUSTRIA', null, null, $this->context->shop->id) == 1) {
                $klarna_eid = Configuration::get('KCO_AUSTRIA_EID', null, null, $this->context->shop->id);
            } else {
                $klarna_eid = Configuration::get('KPM_AT_EID', null, null, $this->context->shop->id);
            }
        }
        if ($country_iso == 'gb') {
            return;
        }

        if ($klarna_eid == '') {
            return;
        }

        $this->context->smarty->assign('kcoeid', $klarna_eid);
        $productPrice = Product::getPriceStatic(
            (int) Tools::getValue('id_product'),
            true,
            null,
            6,
            null,
            false,
            true,
            1,
            false
        );
        $this->context->smarty->assign('kcoproductPrice', $productPrice);
        $klarna_locale = $this->getKlarnaLocale();

        $this->context->smarty->assign('klarna_invoice_fee', $klarna_invoice_fee);
        $this->context->smarty->assign('klarna_locale', $klarna_locale);
        $this->context->smarty->assign('klarna_widget_layout', Configuration::get('KCO_PRODUCTPAGELAYOUT'));

        return $this->display(__FILE__, 'klarnaproductpage.tpl');
    }
    public function hookFooter($params)
    {
        if (Configuration::get('PS_CATALOG_MODE')) {
            return;
        }
        if (isset($this->context->language) &&
        isset($this->context->language->id) &&
        (int) $this->context->language->id > 0) {
            $language_iso = Language::getIsoById((int) $this->context->language->id);
        } else {
            $language_iso = Language::getIsoById(Configuration::get('PS_LANG_DEFAULT'));
        }

        $kco_active = false;
        $eid = '';
        if ($language_iso == 'sv') {
            if (Configuration::get('KCO_IS_ACTIVE')) {
                $eid = Configuration::get('KCO_SWEDEN_EID');
                $kco_active = true;
            } else {
                $eid = Configuration::get('KPM_SV_EID');
            }
        }
        if ($language_iso == 'nb' || $language_iso == 'no' || $language_iso == 'nn') {
            if (Configuration::get('KCO_IS_ACTIVE')) {
                $eid = Configuration::get('KCO_NORWAY_EID');
                $kco_active = true;
            } else {
                $eid = Configuration::get('KPM_NO_EID');
            }
        }
        if ($language_iso == 'de') {
            if (Configuration::get('KCO_IS_ACTIVE')) {
                $eid = Configuration::get('KCO_GERMANY_EID');
                $kco_active = true;
            } else {
                $eid = Configuration::get('KPM_DE_EID');
            }
        }
        if ($language_iso == 'fi') {
            if (Configuration::get('KCO_IS_ACTIVE')) {
                $eid = Configuration::get('KCO_FINLAND_EID');
                $kco_active = true;
            } else {
                $eid = Configuration::get('KPM_FI_EID');
            }
        }
        if ($language_iso == 'en') {
            if (Configuration::get('KCO_IS_ACTIVE')) {
                $eid = Configuration::get('KCO_UK_EID');
                $kco_active = true;
            } else {
                $eid = Configuration::get('KPM_UK_EID');
            }
        }
        if ($language_iso == 'nl') {
            $eid = Configuration::get('KPM_NL_EID');
        }
        if ($eid == '') {
            return;
        }
        $klarna_locale = $this->getKlarnaLocale();
        
        if ($klarna_locale == 'sv_fi') {
            $klarna_locale = 'fi_fi';
        }
        $this->smarty->assign('klarna_footer_layout', Configuration::get('KCO_FOOTERLAYOUT'));
        $this->smarty->assign('klarnav3_footer_layout', Configuration::get('KCOV3_FOOTERBANNER'));
        $this->smarty->assign('kco_footer_active', $kco_active);
        $this->smarty->assign('kco_footer_eid', $eid);
        $this->smarty->assign('kco_footer_locale', $klarna_locale);

        return $this->display(__FILE__, 'klarnafooter.tpl');
    }

    private function getOSMHeaderHtml()
    {
        if (Tools::getValue('controller') === 'order' ||
            Tools::getValue('controller') === 'order-opc' ||
            Tools::getValue('controller') === 'product' ||
            Tools::getValue('controller') === 'checkoutklarnakco'
        ) {
            if (!self::isValidCountryCurrencyOSM()) {
                return;
            }

            $this->context->controller->addJS($this->_path.'/views/js/onsite_messaging.js');

            $OSMconfig = Tools::jsonDecode(Configuration::get('KLARNA_ONSITEMESSAGING_CONFIGURATION'), true);
            $key = 'KLARNA_ONSITEMESSAGING_SWITCH_COUNTRY_'.$this->context->country->iso_code;
            if (!isset($OSMconfig[$this->context->country->iso_code]) ||
                !isset($OSMconfig[$this->context->country->iso_code][$key])
            ) {
                return;
            }
 
            $this->context->smarty->assign(
                'klarna_onsite_messaging_url',
                self::getOnSiteMessagingUrl()
            );
            $this->context->smarty->assign(
                'klarna_onsite_messaging_dci',
                Configuration::get('KLARNA_ONSITE_MESSAGE_DCI')
            );
            return $this->display(__FILE__, 'views/templates/hook/onsite_messaging_script.tpl');
        }
    }
    public function hookHeader()
    {
        if (Tools::getIsset("recover_cart")) {
            Tools::redirect('index.php');
        }
        if (Configuration::get('PS_CATALOG_MODE')) {
            return;
        }
        $returnData = null;
        $this->context->controller->addCSS(($this->_path).'views/css/kpm_common.css', 'all');
        if (Configuration::get('KCO_IS_ACTIVE')) {
            $this->context->controller->addJS(($this->_path).'views/js/kco_common.js');
            $this->smarty->assign(
                'kco_checkout_url',
                $this->context->link->getModuleLink('klarnaofficial', 'checkoutklarna', array(), true)
            );

            $returnData = $this->display(__FILE__, 'header.tpl');
        }
        if ((bool) Configuration::get('KLARNA_ONSITE_MESSAGE')) {
            $returnData = $this->getOSMHeaderHtml();
        }
        return $returnData;
    }

    /*public function hookTop($params)
    {
        return $this->hookRightColumn($params);
    }*/

    //Copied from block cart
    public function assignContentVars(&$params)
    {
        // Set currency
        if ((int) $params['cart']->id_currency && (int) $params['cart']->id_currency != $this->context->currency->id) {
            $currency = new Currency((int) $params['cart']->id_currency);
        } else {
            $currency = $this->context->currency;
        }

        $taxCalculationMethod = Group::getPriceDisplayMethod((int) Group::getCurrent()->id);

        $useTax = !($taxCalculationMethod == PS_TAX_EXC);

        $products = $params['cart']->getProducts(true);
        $nbTotalProducts = 0;
        foreach ($products as $product) {
            $nbTotalProducts += (int) $product['cart_quantity'];
        }
        $cart_rules = $params['cart']->getCartRules();

        $base_shipping = $params['cart']->getOrderTotal($useTax, Cart::ONLY_SHIPPING);
        $shipping_cost = Tools::displayPrice($base_shipping, $currency);
        $shipping_cost_float = Tools::convertPrice($base_shipping, $currency);
        $wrappingCost = (float) ($params['cart']->getOrderTotal($useTax, Cart::ONLY_WRAPPING));
        $totalToPay = $params['cart']->getOrderTotal($useTax);

        if ($useTax && Configuration::get('PS_TAX_DISPLAY') == 1) {
            $totalToPayWithoutTaxes = $params['cart']->getOrderTotal(false);
            $this->smarty->assign('tax_cost', Tools::displayPrice($totalToPay - $totalToPayWithoutTaxes, $currency));
        }

        // The cart content is altered for display
        foreach ($cart_rules as &$cart_rule) {
            if ($cart_rule['free_shipping']) {
                $shipping_cost = Tools::displayPrice(0, $currency);
                $shipping_cost_float = 0;
                $cart_rule['value_real'] -= Tools::convertPrice(
                    $params['cart']->getOrderTotal(
                        true,
                        Cart::ONLY_SHIPPING
                    ),
                    $currency
                );
                $cart_rule['value_tax_exc'] = Tools::convertPrice(
                    $params['cart']->getOrderTotal(
                        false,
                        Cart::ONLY_SHIPPING
                    ),
                    $currency
                );
            }
            if ($cart_rule['gift_product']) {
                foreach ($products as &$product) {
                    if ($product['id_product'] == $cart_rule['gift_product'] &&
                    $product['id_product_attribute'] == $cart_rule['gift_product_attribute']) {
                        $product['is_gift'] = 1;
                        $product['total_wt'] = Tools::ps_round(
                            $product['total_wt'] - $product['price_wt'],
                            (int) $currency->decimals * _PS_PRICE_DISPLAY_PRECISION_
                        );
                        
                        $product['total'] = Tools::ps_round(
                            $product['total'] - $product['price'],
                            (int) $currency->decimals * _PS_PRICE_DISPLAY_PRECISION_
                        );
                        $cart_rule['value_real'] = Tools::ps_round(
                            $cart_rule['value_real'] - $product['price_wt'],
                            (int) $currency->decimals * _PS_PRICE_DISPLAY_PRECISION_
                        );
                        $cart_rule['value_tax_exc'] = Tools::ps_round(
                            $cart_rule['value_tax_exc'] - $product['price'],
                            (int) $currency->decimals * _PS_PRICE_DISPLAY_PRECISION_
                        );
                    }
                }
            }
        }

        $total_free_shipping = 0;
        if ($free_shipping = Tools::convertPrice((float)Configuration::get('PS_SHIPPING_FREE_PRICE'), $currency)) {
            $calculation2 = $params['cart']->getOrderTotal(true, Cart::ONLY_DISCOUNTS);
            $calculation1 = $params['cart']->getOrderTotal(true, Cart::ONLY_PRODUCTS) + $calculation2;
            $total_free_shipping = (float)($free_shipping - $calculation1);
            $discounts = $params['cart']->getCartRules(CartRule::FILTER_ACTION_SHIPPING);
            if ($total_free_shipping < 0) {
                $total_free_shipping = 0;
            }
            if (is_array($discounts) && count($discounts)) {
                $total_free_shipping = 0;
            }
        }

        $this->smarty->assign(array(
            'products' => $products,
            'customizedDatas' => Product::getAllCustomizedDatas((int) ($params['cart']->id)),
            'CUSTOMIZE_FILE' => _CUSTOMIZE_FILE_,
            'CUSTOMIZE_TEXTFIELD' => _CUSTOMIZE_TEXTFIELD_,
            'discounts' => $cart_rules,
            'nb_total_products' => (int) ($nbTotalProducts),
            'shipping_cost' => $shipping_cost,
            'shipping_cost_float' => $shipping_cost_float,
            'show_wrapping' => $wrappingCost > 0 ? true : false,
            'show_tax' => (int) (Configuration::get('PS_TAX_DISPLAY') == 1 && (int) Configuration::get('PS_TAX')),
            'wrapping_cost' => Tools::displayPrice($wrappingCost, $currency),
            'product_total' => Tools::displayPrice(
                $params['cart']->getOrderTotal($useTax, Cart::BOTH_WITHOUT_SHIPPING),
                $currency
            ),
            'total' => Tools::displayPrice($totalToPay, $currency),
            'order_process' => Configuration::get('PS_ORDER_PROCESS_TYPE') ? 'order-opc' : 'order',
            'ajax_allowed' => (int) (Configuration::get('PS_BLOCK_CART_AJAX')) == 1 ? true : false,
            'static_token' => Tools::getToken(false),
            'free_shipping' => $total_free_shipping,
        ));
        if (isset($this->context->cookie->ajax_blockcart_display)) {
            $this->smarty->assign('colapseExpandStatus', $this->context->cookie->ajax_blockcart_display);
        }
    }

    public function hookDisplayAdminOrder($params)
    {
        $order = new Order((int) Tools::getValue('id_order'));
        if ($order->module != $this->name) {
            return;
        }

        $sql = 'SELECT * FROM  `'._DB_PREFIX_.'klarna_checkbox` WHERE id_cart='.(int) $order->id_cart;
        $klarna_checkbox_info = Db::getInstance()->getRow($sql);
        $sql = 'SELECT * FROM  `'._DB_PREFIX_.'klarna_orders` WHERE id_order='.(int) Tools::getValue('id_order');
        $klarna_orderinfo = Db::getInstance()->getRow($sql);
        $sql = 'SELECT error_message FROM `'._DB_PREFIX_.
        'klarna_errors` WHERE id_order='.(int) Tools::getValue('id_order');
        $klarna_errors = Db::getInstance()->executeS($sql);
        $this->context->smarty->assign('klarna_checkbox_info', $klarna_checkbox_info);
        $this->context->smarty->assign('klarnacheckout_ssn', $klarna_orderinfo['ssn']);
        $this->context->smarty->assign('klarnacheckout_invoicenumber', $klarna_orderinfo['invoicenumber']);
        $this->context->smarty->assign('klarnacheckout_reservation', $klarna_orderinfo['reservation']);
        $this->context->smarty->assign('klarnacheckout_risk_status', $klarna_orderinfo['risk_status']);
        $this->context->smarty->assign('klarnacheckout_eid', $klarna_orderinfo['eid']);
        $this->context->smarty->assign('klarna_errors', $klarna_errors);
        if (Tools::strlen($klarna_orderinfo['invoicenumber']) > 0) {
            $invoice_number = $klarna_orderinfo['invoicenumber'];
            $eid = $klarna_orderinfo['eid'];
            $eid_ss_comb = $this->getAllEIDSScombinations($order->id_shop);
            $shared_secret = $eid_ss_comb[$eid];
            $digest_secret = "$eid:$invoice_number:$shared_secret";
            $digest_secret = hash("sha512", $digest_secret);
            $digest_secret = pack('H*', $digest_secret);
            $digest_secret = base64_encode($digest_secret);
            $digest_secret = urlencode($digest_secret);
            $invoice_download_link = "https://online.klarna.com/invoices/$invoice_number.pdf?secret=$digest_secret";
            $this->context->smarty->assign('invoice_download_link', $invoice_download_link);
        }
        return $this->display(__FILE__, 'klarnaofficial_adminorder.tpl');
    }

    public function hookUpdateOrderStatus($params)
    {
        $newOrderStatus = $params['newOrderStatus'];
        $order = new Order((int) $params['id_order']);
        
        if ($order->module == 'klarnaofficial') {
            $id_shop = (int) $order->id_shop;
            $KCO_CANCEL_STATES = Configuration::get('KCO_CANCEL_STATE', null, null, $id_shop);
            $KCO_CANCEL_STATES = Tools::jsonDecode($KCO_CANCEL_STATES);
            $KCO_ACTIVATE_STATE = Configuration::get('KCO_ACTIVATE_STATE', null, null, $id_shop);
            $KCO_ACTIVATE_STATES = Tools::jsonDecode($KCO_ACTIVATE_STATE);
            
            if (in_array($newOrderStatus->id, $KCO_CANCEL_STATES)) {
                $countryIso = '';
                $languageIso = '';
                $currencyIso = '';
                $sql = 'SELECT reservation, invoicenumber, eid, id_shop FROM '.
                _DB_PREFIX_.'klarna_orders WHERE id_order='.
                (int) $params['id_order'];
                $order_data = Db::getInstance()->getRow($sql);
                $reservation_number = $order_data['reservation'];
                $invoice_number = $order_data['invoicenumber'];
                $id_shop = $order_data['id_shop'];
                $eid = $order_data['eid'];

                $eid_ss_comb = $this->getAllEIDSScombinations($id_shop);
                $shared_secret = $eid_ss_comb[$eid];
                if ($reservation_number != '') {
                    try {
                        if ($eid == Configuration::get('KCOV3_MID', null, null, $order->id_shop)) {
                            require_once dirname(__FILE__).'/libraries/KCOUK/autoload.php';
                            
                            if ((int) (Configuration::get('KCO_TESTMODE')) == 1) {
                                $url = \Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL;
                            } else {
                                $url = \Klarna\Rest\Transport\ConnectorInterface::EU_BASE_URL;
                            }
                            
                            $connector = \Klarna\Rest\Transport\Connector::create(
                                $eid,
                                $shared_secret,
                                $url
                            );

                            if ($invoice_number != '') {
                                $kcoorder = new \Klarna\Rest\OrderManagement\Order(
                                    $connector,
                                    $reservation_number
                                );
                                $kcoorder->fetch();

                                $data = array(
                                    'refunded_amount' => $kcoorder['order_amount'],
                                    'description' => 'Refund all of the order',
                                    'order_lines' => $kcoorder['order_lines'],
                                );

                                $kcoorder->refund($data);
                                $sql = 'UPDATE `'._DB_PREFIX_.
                                "klarna_orders` SET risk_status='credit' WHERE id_order=".
                                (int) $params['id_order'];
                                Db::getInstance()->execute($sql);
                            } else {
                                $kcoorder = new \Klarna\Rest\OrderManagement\Order(
                                    $connector,
                                    $reservation_number
                                );

                                $kcoorder->cancel();
                                $sql = 'UPDATE `'._DB_PREFIX_.
                                "klarna_orders` SET risk_status='cancel' WHERE id_order=".
                                (int) $params['id_order'];
                                Db::getInstance()->execute($sql);
                            }
                        } else {
                            $k = $this->initKlarnaAPI($eid, $shared_secret, $countryIso, $languageIso, $currencyIso);
                            if ($invoice_number != '') {
                                $result = $k->creditInvoice("$invoice_number");
                            } else {
                                $result = $k->cancelReservation("$reservation_number");
                            }
                            //$invoice_number = '';
                            $risk_status = '';

                            if ($invoice_number != '') {
                                $sql = 'UPDATE `'._DB_PREFIX_.
                                "klarna_orders` SET risk_status='credit' WHERE id_order=".
                                (int) $params['id_order'];
                                Db::getInstance()->execute($sql);
                            } else {
                                $sql = 'UPDATE `'._DB_PREFIX_.
                                "klarna_orders` SET risk_status='cancel' WHERE id_order=".
                                (int) $params['id_order'];
                                Db::getInstance()->execute($sql);
                            }
                        }
                    } catch (Exception $e) {
                        $this->storemessageonorder((int) $params['id_order'], $e->getMessage());
                    }
                }
            }
            if (in_array($newOrderStatus->id, $KCO_ACTIVATE_STATES)) {
                $countryIso = '';
                $languageIso = '';
                $currencyIso = '';
                $sql = 'SELECT reservation, invoicenumber, eid, id_shop FROM '._DB_PREFIX_.
                'klarna_orders WHERE id_order='.
                (int) $params['id_order'];
                $order_data = Db::getInstance()->getRow($sql);
                $reservation_number = $order_data['reservation'];
                $invoice_number = $order_data['invoicenumber'];
                $eid = $order_data['eid'];
                $id_shop = $order_data['id_shop'];
                
                $eid_ss_comb = $this->getAllEIDSScombinations($id_shop);
                $shared_secret = $eid_ss_comb[$eid];

                if ($reservation_number != '') {
                    try {
                        $invoice_number = '';
                        $risk_status = '';

                        if ($eid == Configuration::get('KCOV3_MID', null, null, $order->id_shop)) {
                            require_once dirname(__FILE__).'/libraries/KCOUK/autoload.php';
                            
                            if ((int) (Configuration::get('KCO_TESTMODE')) == 1) {
                                $url = \Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL;
                            } else {
                                $url = \Klarna\Rest\Transport\ConnectorInterface::EU_BASE_URL;
                            }
                            
                            $connector = \Klarna\Rest\Transport\Connector::create(
                                $eid,
                                $shared_secret,
                                $url
                            );

                            $kcoorder = new \Klarna\Rest\OrderManagement\Order(
                                $connector,
                                $reservation_number
                            );
                            $kcoorder->fetch();

                            $data = array(
                                'captured_amount' => $kcoorder['order_amount'],
                                'description' => 'Shipped all of the order',
                                'order_lines' => $kcoorder['order_lines'],
                            );
                            
                            if ("" != $order->shipping_number) {
                                $carrier = new Carrier((int)($order->id_carrier), (int)($order->id_lang));
                                if ("" != $carrier->url) {
                                    $tracking_uri = urlencode(str_replace('@', $order->shipping_number, $carrier->url));
                                } else {
                                    $tracking_uri = "";
                                }
                                $shipping_info = array(
                                    'tracking_number' => $order->shipping_number,
                                    'tracking_uri' => $tracking_uri,
                                );
                                $data['shipping_info'] = array($shipping_info);
                            }

                            $kcoorder->createCapture($data);
                            $invoice_number = $kcoorder['klarna_reference'];
                            $risk_status = $kcoorder['fraud_status'];
                        } else {
                            $k = $this->initKlarnaAPI($eid, $shared_secret, $countryIso, $languageIso, $currencyIso);
                            $method = Configuration::get('KCO_SENDTYPE', null, null, $order->id_shop);
                            if ($method == 1) {
                                $method = KlarnaFlags::RSRV_SEND_BY_EMAIL;
                            } else {
                                $method = KlarnaFlags::RSRV_SEND_BY_MAIL;
                            }

                            $result = $k->activate(
                                "$reservation_number",
                                null,
                                $method
                            );
                            if (isset($result[0])) {
                                $risk_status = $result[0];
                            }
                            if (isset($result[1])) {
                                $invoice_number = $result[1];
                            }
                        }
                        $risk_status = pSQL($risk_status);
                        $invoice_number = pSQL($invoice_number);
                        $sql = 'UPDATE `'._DB_PREFIX_.
                        "klarna_orders` SET risk_status='$risk_status' ,invoicenumber='$invoice_number' ".
                        "WHERE id_order=".(int) $params['id_order'];
                        Db::getInstance()->execute($sql);
                    } catch (Exception $e) {
                        $this->storemessageonorder((int) $params['id_order'], $e->getMessage());
                    }
                }
            }
        }
    }

    public function storemessageonorder($id_order, $message)
    {
        $id_order = (int) $id_order;
        $message = pSQL($message);
        $sql = 'INSERT INTO `'._DB_PREFIX_.
        "klarna_errors` (`id_order`, `error_message`) VALUES($id_order, '$message');";
        Db::getInstance()->execute($sql);
    }

    public function initKlarnaAPI($eid, $sharedSecret, $countryIso, $languageIso, $currencyIso, $id_shop = null)
    {
        require_once dirname(__FILE__).'/libraries/lib/Klarna.php';
        require_once dirname(__FILE__).'/libraries/lib/transport/xmlrpc-3.0.0.beta/lib/xmlrpc.inc';
        require_once dirname(__FILE__).'/libraries/lib/transport/xmlrpc-3.0.0.beta/lib/xmlrpc_wrappers.inc';

        if ($countryIso == 'se') {
            $klarna_country = KlarnaCountry::SE;
        } elseif ($countryIso == 'no') {
            $klarna_country = KlarnaCountry::NO;
        } elseif ($countryIso == 'de') {
            $klarna_country = KlarnaCountry::DE;
        } elseif ($countryIso == 'da' || $countryIso == 'dk') {
            $klarna_country = KlarnaCountry::DK;
        } elseif ($countryIso == 'fi') {
            $klarna_country = KlarnaCountry::FI;
        } elseif ($countryIso == 'nl') {
            $klarna_country = KlarnaCountry::NL;
        } elseif ($countryIso == 'at') {
            $klarna_country = KlarnaCountry::AT;
        } else {
            $klarna_country = "";
        }

        if ($currencyIso == 'sek') {
            $klarna_currency = KlarnaCurrency::SEK;
        } elseif ($currencyIso == 'nok') {
            $klarna_currency = KlarnaCurrency::NOK;
        } elseif ($currencyIso == 'eur') {
            $klarna_currency = KlarnaCurrency::EUR;
        } elseif ($currencyIso == 'dkk') {
            $klarna_currency = KlarnaCurrency::DKK;
        } else {
            $klarna_currency = "";
        }

        if ($languageIso == 'sv') {
            $klarna_lang = KlarnaLanguage::SV;
        } elseif ($languageIso == 'no' || $languageIso == 'nb' || $languageIso == 'nn') {
            $klarna_lang = KlarnaLanguage::NB;
        } elseif ($languageIso == 'de') {
            $klarna_lang = KlarnaLanguage::DE;
        } elseif ($languageIso == 'da') {
            $klarna_lang = KlarnaLanguage::DA;
        } elseif ($languageIso == 'fi') {
            $klarna_lang = KlarnaLanguage::FI;
        } elseif ($languageIso == 'nl') {
            $klarna_lang = KlarnaLanguage::NL;
        } elseif ($languageIso == 'en') {
            $klarna_lang = KlarnaLanguage::EN;
        } else {
            $klarna_lang = "";
        }

        if ($id_shop == null) {
            $id_shop = $this->context->shop->id;
        }

        if (Configuration::get('KCO_TESTMODE', null, null, $id_shop)) {
            $server = Klarna::BETA;
        } else {
            $server = Klarna::LIVE;
        }
        $k = new Klarna();

        $dbsettings = array(
            'user' => _DB_USER_,
            'passwd' => _DB_PASSWD_,
            'dsn' => _DB_SERVER_,
            'db' => _DB_NAME_,
            'table' => _DB_PREFIX_.'kpmpclasses',
          );

        $k->config(
            $eid,
            ''.$sharedSecret,
            $klarna_country,
            $klarna_lang,
            $klarna_currency,
            $server,
            'mysql',
            $dbsettings
        );

        return $k;
    }

    public function hookPayment($params)
    {
        if (!$this->active) {
            return;
        }
        if (!$this->checkCurrency($this->context->cart)) {
            return;
        }
        
        $iso = $this->getKlarnaLocale();
        if ($iso == '') {
            $iso = 'sv_se';
        }
        $hide_partpayment = false;
        $hide_invoicepayment = false;
        
        if ((int)Configuration::get('KPM_DISABLE_INVOICE')==1) {
            $hide_invoicepayment = true;
        }
        if ($iso == 'de_at') {
            $hide_partpayment = true;
        }

        if (Configuration::get('KCO_IS_ACTIVE')) {
            $KCO_SHOW_IN_PAYMENTS = Configuration::get('KCO_SHOW_IN_PAYMENTS');
            if ($KCO_SHOW_IN_PAYMENTS) {
                $address = new Address($this->context->cart->id_address_delivery);
                $country = new Country($address->id_country);
                if ($country->iso_code=="DE") {
                    $active_in_country = Configuration::get('KCO_GERMANY');
                } elseif ($country->iso_code=="NO") {
                    $active_in_country = Configuration::get('KCO_NORWAY');
                } elseif ($country->iso_code=="FI") {
                    $active_in_country = Configuration::get('KCO_FINLAND');
                } elseif ($country->iso_code=="SE") {
                    $active_in_country = Configuration::get('KCO_SWEDEN');
                } elseif ($country->iso_code=="GB") {
                    $active_in_country = Configuration::get('KCO_UK');
                } elseif ($country->iso_code=="AT") {
                    $active_in_country = Configuration::get('KCO_AUSTRIA');
                } else {
                    $active_in_country = false;
                }
                
                if (!$active_in_country) {
                    $KCO_SHOW_IN_PAYMENTS = false;
                }
            }
        } else {
            $KCO_SHOW_IN_PAYMENTS = false;
        }
        $KPM_SHOW_IN_PAYMENTS = Configuration::get('KPM_SHOW_IN_PAYMENTS');
        $this->smarty->assign('KPM_SHOW_IN_PAYMENTS', $KPM_SHOW_IN_PAYMENTS);
        $this->smarty->assign('KCO_SHOW_IN_PAYMENTS', $KCO_SHOW_IN_PAYMENTS);
        $this->smarty->assign('hide_partpayment', $hide_partpayment);
        $this->smarty->assign('hide_invoicepayment', $hide_invoicepayment);
        $this->smarty->assign('KPM_LOGO', Configuration::get('KPM_LOGO'));
        $this->smarty->assign('KPM_LOGO_ISO_CODE', $iso);

        return $this->display(__FILE__, 'kpm_payment.tpl');
    }

    public function hookPaymentReturn($params)
    {
        if (!$this->active) {
            return;
        }
        
        if (Tools::getIsset("kcotp")) {
            session_start();
            if (isset($_SESSION['klarna_checkout'])) {
                require_once dirname(__FILE__).'/libraries/Checkout.php';
                Klarna_Checkout_Order::$contentType = 'application/vnd.klarna.checkout.aggregated-order-v2+json';
                $sid = Tools::getValue('sid');
                if ($sid == 'se') {
                    $secret = Configuration::get('KCO_SWEDEN_SECRET');
                }
                if ($sid == 'de') {
                    $secret = Configuration::get('KCO_GERMANY_SECRET');
                }
                if ($sid == 'at') {
                    $secret = Configuration::get('KCO_AUSTRIA_SECRET');
                }
                if ($sid == 'fi') {
                    $secret = Configuration::get('KCO_FINLAND_SECRET');
                }
                if ($sid == 'no') {
                    $secret = Configuration::get('KCO_NORWAY_SECRET');
                }
                $connector = Klarna_Checkout_Connector::create($secret);

                $checkoutId = $_SESSION['klarna_checkout'];
                $klarnaorder = new Klarna_Checkout_Order($connector, $checkoutId);
                $klarnaorder->fetch();
                $snippet = $klarnaorder['gui']['snippet'];
                $this->context->smarty->assign('orderreference', $params["objOrder"]->reference);
                $this->context->smarty->assign('orderid', $params["objOrder"]->id);
                $this->context->smarty->assign('snippet', $snippet);
                unset($_SESSION['klarna_checkout']);
                return $this->display(__FILE__, 'kco_payment_return.tpl');
            } else {
                Tools::redirect('index.php');
            }
        } elseif (Tools::getIsset("kcotpv3")) {
            require_once dirname(__FILE__).'/libraries/KCOUK/autoload.php';
            $sid = Tools::getValue('sid');

            if (Configuration::get('KCOV3')) {
                $merchantId = Configuration::get('KCOV3_MID');
                $sharedSecret = Configuration::get('KCOV3_SECRET');
            }

            if ((int) (Configuration::get('KCO_TESTMODE')) == 1) {
                $connector = \Klarna\Rest\Transport\Connector::create(
                    $merchantId,
                    $sharedSecret,
                    \Klarna\Rest\Transport\ConnectorInterface::EU_TEST_BASE_URL
                );
           
                $orderId = Tools::getValue('klarna_order_id');

                $checkout = new \Klarna\Rest\Checkout\Order($connector, $orderId);
                $checkout->fetch();
            } else {
                $connector = \Klarna\Rest\Transport\Connector::create(
                    $merchantId,
                    $sharedSecret,
                    \Klarna\Rest\Transport\ConnectorInterface::EU_BASE_URL
                );
              
                $orderId = Tools::getValue('klarna_order_id');
                $checkout = new \Klarna\Rest\Checkout\Order($connector, $orderId);
                $checkout->fetch();
            }

            $snippet = $checkout['html_snippet'];
            
            $this->context->smarty->assign('orderreference', $params["objOrder"]->reference);
            $this->context->smarty->assign('orderid', $params["objOrder"]->id);
            $this->context->smarty->assign('snippet', $snippet);
            if (isset($_SESSION['klarna_checkout_uk'])) {
                unset($_SESSION['klarna_checkout_uk']);
            }
            return $this->display(__FILE__, 'kco_payment_return.tpl');
        } else {
            $this->context->smarty->assign('orderreference', $params["objOrder"]->reference);
            $this->context->smarty->assign('orderid', $params["objOrder"]->id);
            return $this->display(__FILE__, 'kpm_payment_return.tpl');
        }
    }

    public function checkCurrency($cart)
    {
        $currency_order = new Currency($cart->id_currency);
        $currencies_module = $this->getCurrency($cart->id_currency);

        if (is_array($currencies_module)) {
            foreach ($currencies_module as $currency_module) {
                if ($currency_order->id == $currency_module['id_currency']) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getByReference($invoiceref)
    {
        $invoiceref = pSQL($invoiceref);
        $result = Db::getInstance()->getRow(
            'SELECT id_product FROM `'._DB_PREFIX_.
            "product` WHERE reference='$invoiceref'"
        );
        if (isset($result['id_product']) and (int) ($result['id_product']) > 0) {
            $feeproduct = new Product((int) ($result['id_product']), true);

            return $feeproduct;
        } else {
            return;
        }
    }

    public function getCustomerAddress($ssn)
    {
        require_once dirname(__FILE__).'/libraries/lib/Klarna.php';
        require_once dirname(__FILE__).'/libraries/lib/transport/xmlrpc-3.0.0.beta/lib/xmlrpc.inc';
        require_once dirname(__FILE__).'/libraries/lib/transport/xmlrpc-3.0.0.beta/lib/xmlrpc_wrappers.inc';

        $eid = Configuration::get('KPM_SV_EID', null, null, $this->context->shop->id);
        $sharedSecret = Configuration::get('KPM_SV_SECRET', null, null, $this->context->shop->id);

        $md5 = Tools::getValue('kpm_md5key');
        $secret = Tools::encrypt($sharedSecret);
        $ourmd5 = MD5($ssn.$secret);
        $result = array();
        
        if ($ourmd5 != $md5) {
            $result['hasError'] = true;
            $result['error'] = 'Bad token!';
            die(Tools::jsonEncode($result));
        }

        $k = $this->initKlarnaAPI($eid, $sharedSecret, 'se', 'sv', 'sek', $this->context->shop->id);
        $k->setCountry('se');

        //setcookie('kpm_ssn', $ssn, time() + 86400, '/'); // 86400 = 1 day
        Context::getContext()->cookie->kpm_ssn = $ssn;
        try {
            $results = array();
            $addrs = $k->getAddresses($ssn);
            foreach ($addrs as $addr) {
                if ($addr->isCompany) {
                    //company
                    $result['firstname'] = utf8_encode($addr->fname);
                    $result['lastname'] = utf8_encode($addr->lname);
                    $result['company'] = utf8_encode($addr->company);
                    $result['address'] = utf8_encode($addr->street);
                    $result['address2'] = utf8_encode($addr->careof);
                    $result['zip'] = utf8_encode($addr->zip);
                    $result['city'] = utf8_encode($addr->city);
                    $result['country'] = utf8_encode($addr->country);
                    $result['iscompany'] = $addr->isCompany;
                } else {
                    //consumer
                    $result['firstname'] = utf8_encode($addr->fname);
                    $result['lastname'] = utf8_encode($addr->lname);
                    $result['address'] = utf8_encode($addr->street);
                    $result['address2'] = utf8_encode($addr->careof);
                    $result['zip'] = utf8_encode($addr->zip);
                    $result['city'] = utf8_encode($addr->city);
                    $result['country'] = utf8_encode($addr->country);
                    $result['iscompany'] = utf8_encode($addr->isCompany);
                }
                $results[] = $result;
            }
            die(Tools::jsonEncode($results));
        } catch (Exception $e) {
            $result['hasError'] = true;
            $result['error'] = "{$e->getMessage()} (#{$e->getCode()})\n";
            die(Tools::jsonEncode($result));
        }
    }

    public function changeAddressOnCart(
        $firstname,
        $lastname,
        $address1,
        $address2,
        $company,
        $postcode,
        $city,
        $old_address,
        $klarna_phone
    ) {
        $customer = new Customer($old_address->id_customer);
        $delivery_address_id = 0;
        foreach ($customer->getAddresses($this->context->cart->id_lang) as $address) {
            if ($address['firstname'] == $firstname and $address['lastname'] == $lastname
                and $address['city'] == $city
                and $address['address2'] == $address2
                and $address['company'] == $company and $address['address1'] == $address1
                and $address['postcode'] == $postcode and $address['phone_mobile'] == $klarna_phone) {
                //LOAD SHIPPING ADDRESS
                    $delivery_address_id = $address['id_address'];
            }
        }
        if ($delivery_address_id == 0) {
            $new_address = new Address();
            $new_address->id_customer = $this->context->cart->id_customer;
            $new_address->firstname = (Tools::strlen($firstname) > 0 ? $firstname : $old_address->firstname);
            $new_address->lastname = (Tools::strlen($lastname) > 0 ? $lastname : $old_address->lastname);
            $new_address->address1 = $address1;
            $new_address->address2 = $address2;
            $new_address->company = $company;
            $new_address->postcode = $postcode;
            $new_address->city = $city;
            $new_address->phone = $old_address->phone;
            $new_address->phone_mobile = $klarna_phone;
            $new_address->id_country = $old_address->id_country;
            $new_address->alias = 'Klarna';
            $new_address->add();
            $this->context->cart->id_address_delivery = $new_address->id;
            $this->context->cart->id_address_invoice = $new_address->id;
        } else {
            $this->context->cart->id_address_delivery = $delivery_address_id;
            $this->context->cart->id_address_invoice = $delivery_address_id;
        }
        Db::getInstance()->Execute(
            'UPDATE '._DB_PREFIX_.
            'cart_product SET id_address_delivery='.
            (int) $this->context->cart->id_address_delivery.
            ' WHERE id_cart='.(int) $this->context->cart->id
        );
        $delivery_option_serialized = Db::getInstance()->getValue(
            'SELECT delivery_option FROM '
            ._DB_PREFIX_.'cart WHERE id_cart='.
            (int) $this->context->cart->id
        );
        if ($delivery_option_serialized and $delivery_option_serialized != '') {
            if (version_compare(_PS_VERSION_, "1.6.1.21", "<")) {
                $delivery_option_values = unserialize($delivery_option_serialized);
            } else {
                $delivery_option_values = json_decode($delivery_option_serialized);
            }
            
            $new_delivery_options = array();
            foreach ($delivery_option_values as $value) {
                $new_delivery_options[(int) $this->context->cart->id_address_delivery] = $value;
            }
            
            if (version_compare(_PS_VERSION_, "1.6.1.21", "<")) {
                $new_delivery_options_serialized = serialize($new_delivery_options);
            } else {
                $new_delivery_options_serialized = json_encode($new_delivery_options);
            }
            
            $update_sql = 'UPDATE '._DB_PREFIX_.'cart SET delivery_option=\''.
            pSQL($new_delivery_options_serialized).
            '\' WHERE id_cart='.(int) $this->context->cart->id;
            $this->context->cart->delivery_option = $new_delivery_options_serialized;
            Db::getInstance()->Execute($update_sql);
        }
        $this->context->cart->update(true);
        $this->context->cart->getPackageList(true);
        $this->context->cart->getDeliveryOptionList(null, true);
    }

    public function getL($key)
    {
        $translations = array(
            'Inslagning' => $this->l('Inslagning'),
            'Discount' => $this->l('Discount'),
            'extra_info' => $this->l('Flexible - Pay in your own tempo.'),
            'interestRate' => $this->l('interestRate: '),
            'monthlyFee' => $this->l('monthlyFee: '),
            'monthlyCost' => $this->l('monthlyCost: '),
            'Missing_field_SSN' => $this->l('Missing field SSN'),
            'Invoice' => $this->l('Invoice'),
            'Klarna Account' => $this->l('Klarna Account'),
            'Subscribe to our newsletter.' => $this->l('Subscribe to our newsletter.'),
        );

        return $translations[$key];
    }

    public function setKCOCountrySettings()
    {
        $norway_done = false;
        $finland_done = false;
        $sweden_done = false;
        $germany_done = false;
        $austria_done = false;
        $uk_done = false;
        $us_done = false;
        $nl_done = false;

        $sql = 'SELECT id_address FROM '._DB_PREFIX_.'address WHERE alias=\'KCO_SVERIGE_DEFAULT\'';
        $id_address_sweden = Db::getInstance()->getValue($sql);
        if ((int) ($id_address_sweden) > 0) {
            Configuration::updateValue('KCO_SWEDEN_ADDR', $id_address_sweden);
            $sweden_done = true;
        } else {
            $id_country = (int) Country::getByIso('SE');
            $insert_sql = 'INSERT INTO '._DB_PREFIX_.
            "address (id_country, id_state, id_customer, id_manufacturer, id_supplier, id_warehouse,".
            " alias, company, lastname, firstname, address1, address2, postcode,".
            " city, other,phone, phone_mobile, vat_number, dni, active, deleted, date_add, date_upd) ".
            "VALUES ($id_country, 0,0,0,0,0,'KCO_SVERIGE_DEFAULT','','Sverige', 'Person', ".
            "'Standardgatan 1', '', '12345', 'Stockholm', '', '1234567890','','','',1,0, NOW(), NOW());";
            Db::getInstance()->execute($insert_sql);
            $id_address_sweden = Db::getInstance()->getValue($sql);
            if ((int) ($id_address_sweden) > 0) {
                Configuration::updateValue('KCO_SWEDEN_ADDR', $id_address_sweden);
                $sweden_done = true;
            }
        }

        $sql = 'SELECT id_address FROM '._DB_PREFIX_.'address WHERE alias=\'KCO_UNITED_DEFAULT\'';
        $id_address_us = Db::getInstance()->getValue($sql);
        if ((int) ($id_address_us) > 0) {
            Configuration::updateValue('KCO_US_ADDR', $id_address_us);
            $us_done = true;
        } else {
            $id_country = (int) Country::getByIso('US');
            $insert_sql = 'INSERT INTO '._DB_PREFIX_.
            "address (id_country, id_state, id_customer, id_manufacturer, id_supplier, id_warehouse,".
            " alias, company, lastname, firstname, address1, address2, postcode,".
            " city, other,phone, phone_mobile, vat_number, dni, active, deleted, date_add, date_upd) ".
            "VALUES ($id_country, 0,0,0,0,0,'KCO_UNITED_DEFAULT','','United', 'States', ".
            "'1st street', '', '20001', 'Washington', '', '1234567890','','','',1,0, NOW(), NOW());";
            Db::getInstance()->execute($insert_sql);
            $id_address_us = Db::getInstance()->getValue($sql);
            if ((int) ($id_address_us) > 0) {
                Configuration::updateValue('KCO_US_ADDR', $id_address_us);
                $us_done = true;
            }
        }
        
        $sql = 'SELECT id_address FROM '._DB_PREFIX_.'address WHERE alias=\'KCO_NL_DEFAULT\'';
        $id_address_nl = Db::getInstance()->getValue($sql);
        if ((int) ($id_address_nl) > 0) {
            Configuration::updateValue('KCO_NL_ADDR', $id_address_nl);
            $nl_done = true;
        } else {
            $id_country = (int) Country::getByIso('NL');
            $insert_sql = 'INSERT INTO '._DB_PREFIX_.
            "address (id_country, id_state, id_customer, id_manufacturer, id_supplier, id_warehouse,".
            " alias, company, lastname, firstname, address1, address2, postcode,".
            " city, other,phone, phone_mobile, vat_number, dni, active, deleted, date_add, date_upd) ".
            "VALUES ($id_country, 0,0,0,0,0,'KCO_NL_DEFAULT','','Netherlands', 'Netherlands', ".
            "'1st street', '', '20001', 'Amsterdam', '', '1234567890','','','',1,0, NOW(), NOW());";
            Db::getInstance()->execute($insert_sql);
            $id_address_nl = Db::getInstance()->getValue($sql);
            if ((int) ($id_address_nl) > 0) {
                Configuration::updateValue('KCO_NL_ADDR', $id_address_nl);
                $nl_done = true;
            }
        }
        
        $sql = 'SELECT id_address FROM '._DB_PREFIX_.'address WHERE alias=\'KCO_NORGE_DEFAULT\'';
        $id_address_norway = Db::getInstance()->getValue($sql);
        if ((int) ($id_address_norway) > 0) {
            Configuration::updateValue('KCO_NORWAY_ADDR', $id_address_norway);
            $norway_done = true;
        } else {
            $id_country = (int) Country::getByIso('NO');
            $insert_sql = 'INSERT INTO '._DB_PREFIX_."address (id_country, id_state, id_customer, ".
            "id_manufacturer, id_supplier, id_warehouse, alias, company, lastname, firstname, address1,".
            " address2, postcode, city, other,phone, phone_mobile, vat_number, dni, active, deleted,".
            " date_add, date_upd) VALUES ($id_country, 0,0,0,0,0,'KCO_NORGE_DEFAULT','','Norge', 'Person',".
            " 'Standardgatan 1', '', '12345', 'Oslo', '', '1234567890','','','',1,0, NOW(), NOW());";
            Db::getInstance()->execute($insert_sql);
            $id_address_norway = Db::getInstance()->getValue($sql);
            if ((int) ($id_address_norway) > 0) {
                Configuration::updateValue('KCO_NORWAY_ADDR', $id_address_norway);
                $norway_done = true;
            }
        }
        $sql = 'SELECT id_address FROM '._DB_PREFIX_.'address WHERE alias=\'KCO_FINLAND_DEFAULT\'';
        $id_address_finland = Db::getInstance()->getValue($sql);
        if ((int) ($id_address_finland) > 0) {
            Configuration::updateValue('KCO_FINLAND_ADDR', $id_address_finland);
            $finland_done = true;
        } else {
            $id_country = (int) Country::getByIso('FI');
            $insert_sql = 'INSERT INTO '._DB_PREFIX_."address (id_country, id_state, id_customer, ".
            "id_manufacturer, id_supplier, id_warehouse, alias, company, lastname, firstname, address1, ".
            "address2, postcode, city, other,phone, phone_mobile, vat_number, ".
            "dni, active, deleted, date_add, date_upd) ".
            "VALUES ($id_country, 0,0,0,0,0,'KCO_FINLAND_DEFAULT','','Finland', 'Person', ".
            "'Standardgatan 1', '', '12345', 'Helsinkki', '', '1234567890','','','',1,0, NOW(), NOW());";
            Db::getInstance()->execute($insert_sql);
            $id_address_finland = Db::getInstance()->getValue($sql);
            if ((int) ($id_address_finland) > 0) {
                Configuration::updateValue('KCO_FINLAND_ADDR', $id_address_finland);
                $finland_done = true;
            }
        }

        $sql = 'SELECT id_address FROM '._DB_PREFIX_.'address WHERE alias=\'KCO_GERMANY_DEFAULT\'';
        $id_address_germany = Db::getInstance()->getValue($sql);
        if ((int) ($id_address_germany) > 0) {
            Configuration::updateValue('KCO_GERMANY_ADDR', $id_address_germany);
            $germany_done = true;
        } else {
            $id_country = (int) Country::getByIso('DE');
            $insert_sql = 'INSERT INTO '._DB_PREFIX_."address (id_country, id_state, id_customer, id_manufacturer,".
            " id_supplier, id_warehouse, alias, company, lastname, firstname, address1, address2, postcode, city, ".
            "other,phone, phone_mobile, vat_number, dni, active, deleted, date_add, date_upd) VALUES ($id_country, ".
            "0,0,0,0,0,'KCO_GERMANY_DEFAULT','','Tyskland', 'Person', 'Standardgatan 1', '', '12345', 'Berlin', '',".
            " '1234567890','','','',1,0, NOW(), NOW());";
            Db::getInstance()->execute($insert_sql);
            $id_address_germany = Db::getInstance()->getValue($sql);
            if ((int) ($id_address_germany) > 0) {
                Configuration::updateValue('KCO_GERMANY_ADDR', $id_address_germany);
                $germany_done = true;
            }
        }
        
        $sql = 'SELECT id_address FROM '._DB_PREFIX_.'address WHERE alias=\'KCO_AUSTRIA_DEFAULT\'';
        $id_address_austria = Db::getInstance()->getValue($sql);
        if ((int) ($id_address_austria) > 0) {
            Configuration::updateValue('KCO_AUSTRIA_ADDR', $id_address_austria);
            $austria_done = true;
        } else {
            $id_country = (int) Country::getByIso('AT');
            $insert_sql = 'INSERT INTO '._DB_PREFIX_."address (id_country, id_state, id_customer, id_manufacturer,".
            " id_supplier, id_warehouse, alias, company, lastname, firstname, address1, address2, postcode, city, ".
            "other,phone, phone_mobile, vat_number, dni, active, deleted, date_add, date_upd) VALUES ($id_country, ".
            "0,0,0,0,0,'KCO_AUSTRIA_DEFAULT','','Tyskland', 'Person', 'Standardgatan 1', '', '12345', 'Vienna', '',".
            " '1234567890','','','',1,0, NOW(), NOW());";
            Db::getInstance()->execute($insert_sql);
            $id_address_austria = Db::getInstance()->getValue($sql);
            if ((int) ($id_address_austria) > 0) {
                Configuration::updateValue('KCO_AUSTRIA_ADDR', $id_address_austria);
                $austria_done = true;
            }
        }

        $sql = 'SELECT id_address FROM '._DB_PREFIX_.'address WHERE alias=\'KCO_UK_DEFAULT\'';
        $id_address_uk = Db::getInstance()->getValue($sql);
        if ((int) ($id_address_uk) > 0) {
            Configuration::updateValue('KCO_UK_ADDR', $id_address_uk);
            $uk_done = true;
        } else {
            $id_country = (int) Country::getByIso('GB');
            $insert_sql = 'INSERT INTO '._DB_PREFIX_."address (id_country, id_state, id_customer, id_manufacturer, ".
            "id_supplier, id_warehouse, alias, company, lastname, firstname, address1, address2, postcode, city, ".
            "other,phone, phone_mobile, vat_number, dni, active, deleted, date_add, date_upd) VALUES ($id_country,".
            " 0,0,0,0,0,'KCO_UK_DEFAULT','','United Kingdom', 'Person', 'Standardgatan 1', '', '12345', 'London', ".
            "'', '1234567890','','','',1,0, NOW(), NOW());";
            Db::getInstance()->execute($insert_sql);
            $id_address_uk = Db::getInstance()->getValue($sql);
            if ((int) ($id_address_uk) > 0) {
                Configuration::updateValue('KCO_UK_ADDR', $id_address_uk);
                $uk_done = true;
            }
        }

        if ($finland_done === true &&
        $norway_done === true &&
        $sweden_done === true &&
        $germany_done === true &&
        $austria_done === true &&
        $us_done === true &&
        $nl_done === true &&
        $uk_done === true) {
            return true;
        } else {
            return false;
        }
    }

    public function getRequiredKPMFields($iso_code)
    {
        if (Tools::strtolower($iso_code) == 'at') {
            return array(
                'ssn' => false,
                'birthdate' => true,
                'gender' => true,
                'firstname' => true,
                'lastname' => true,
                'streetname' => true,
                'company' => false,
                'housenumber' => false,
                'housenumberext' => false,
                'zipcode' => true,
                'city' => true,
                'country' => false,
                'phone' => true,
                'mobilephone' => true,
                'email' => true
            );
        } elseif (Tools::strtolower($iso_code) == 'dk') {
            return array(
                'ssn' => true,
                'birthdate' => false,
                'gender' => false,
                'firstname' => true,
                'lastname' => true,
                'company' => false,
                'streetname' => true,
                'housenumber' => false,
                'housenumberext' => false,
                'zipcode' => true,
                'city' => true,
                'country' => false,
                'phone' => true,
                'mobilephone' => true,
                'email' => true
            );
        } elseif (Tools::strtolower($iso_code) == 'fi') {
            return array(
                'ssn' => true,
                'birthdate' => false,
                'gender' => false,
                'firstname' => true,
                'lastname' => true,
                'company' => false,
                'streetname' => true,
                'housenumber' => false,
                'housenumberext' => false,
                'zipcode' => true,
                'city' => true,
                'country' => false,
                'phone' => true,
                'mobilephone' => true,
                'email' => true
            );
        } elseif (Tools::strtolower($iso_code) == 'de') {
            return array(
                'ssn' => false,
                'birthdate' => true,
                'gender' => true,
                'firstname' => true,
                'company' => false,
                'lastname' => true,
                'streetname' => true,
                'housenumber' => true,
                'housenumberext' => false,
                'zipcode' => true,
                'city' => true,
                'country' => false,
                'phone' => true,
                'mobilephone' => true,
                'email' => true
            );
        } elseif (Tools::strtolower($iso_code) == 'nl') {
            return array(
                'ssn' => false,
                'birthdate' => true,
                'gender' => true,
                'firstname' => true,
                'lastname' => true,
                'company' => false,
                'streetname' => true,
                'housenumber' => true,
                'housenumberext' => true,
                'zipcode' => true,
                'city' => true,
                'country' => false,
                'phone' => true,
                'mobilephone' => true,
                'email' => true
            );
        } elseif (Tools::strtolower($iso_code) == 'no') {
            return array(
                'ssn' => true,
                'birthdate' => false,
                'gender' => false,
                'firstname' => true,
                'lastname' => true,
                'streetname' => true,
                'company' => false,
                'housenumber' => false,
                'housenumberext' => false,
                'zipcode' => true,
                'city' => true,
                'country' => false,
                'phone' => true,
                'mobilephone' => true,
                'email' => true
            );
        } elseif (Tools::strtolower($iso_code) == 'se') {
            return array(
                'ssn' => true,
                'birthdate' => false,
                'gender' => false,
                'firstname' => true,
                'company' => true,
                'lastname' => true,
                'streetname' => true,
                'housenumber' => false,
                'housenumberext' => false,
                'zipcode' => true,
                'city' => true,
                'country' => false,
                'phone' => true,
                'mobilephone' => true,
                'email' => true
            );
        }
    }

    public function truncateValue($string, $length, $abconly = false)
    {
        //$string = utf8_decode($string);
        if ($abconly) {
            $string = preg_replace("/[^\p{L}\p{N} -]/u", '', $string);
            $string = preg_replace('/[0-9]+/', '', $string);
            $string = trim($string);
        }
        //$string = utf8_encode($string);
        if (Tools::strlen($string) > $length) {
            return Tools::substr($string, 0, $length);
        } else {
            return $string;
        }
    }

    public function getKlarnaLocale()
    {
        if (isset($this->context->cart) &&
        isset($this->context->cart->id_address_delivery) &&
        (int) $this->context->cart->id_address_delivery > 0) {
            $address = new Address($this->context->cart->id_address_delivery);
            $country_iso = Country::getIsoById($address->id_country);
        } else {
            $country_iso = '';
        }
        
        if (isset($this->context->language) &&
        isset($this->context->language->id) &&
        (int) $this->context->language->id > 0) {
            $language_iso = Language::getIsoById((int) $this->context->language->id);
        } else {
            $language_iso = '';
        }

        $country_iso = Tools::strtolower($country_iso);
        $language_iso = Tools::strtolower($language_iso);
        if ($country_iso == '' && $language_iso == '') {
            $language_iso = Language::getIsoById((int) $this->context->language->id);
            $country_iso = Country::getIsoById(Configuration::get('PS_COUNTRY_DEFAULT'));
            $country_iso = Tools::strtolower($country_iso);
            $language_iso = Tools::strtolower($language_iso);
        }
        
        if ($country_iso == 'de') {
            return 'de_de';
        }
        if ($country_iso == 'at') {
            return 'de_at';
        }
        if ($country_iso == 'nl') {
            return 'nl_nl';
        }
        if ($country_iso == 'dk') {
            return 'da_dk';
        }
        
        if ($country_iso == 'fi') {
            if ($language_iso == 'sv') {
                return 'sv_fi';
            } else {
                return 'fi_fi';
            }
        }
        if ($country_iso == 'se') {
            if ($language_iso == 'sv' || $country_iso == 'se') {
                return 'sv_se';
            } else {
                return 'en_se';
            }
        }
        if ($country_iso == 'no') {
            if ($language_iso == 'nb' || $language_iso == 'nn' || $language_iso == 'no') {
                return 'nb_no';
            } else {
                return 'nb_no';
            }
        }

        if ($language_iso == 'sv') {
            return 'sv_se';
        }
        if ($language_iso == 'no' || $language_iso == 'nb' || $language_iso == 'nn') {
            return 'nb_no';
        }
        if ($language_iso == 'fi') {
            return 'fi_fi';
        }
        if ($language_iso == 'de') {
            return 'de_de';
        }
        if ($language_iso == 'nl') {
            return 'nl_nl';
        }
        if ($language_iso == 'en') {
            return 'en_gb';
        }
        return 'en_gb';
    }

    public function getAllEIDSScombinations($id_shop)
    {
        $combosArray = array();
        $KCO_SWEDEN_EID = Configuration::get('KCO_SWEDEN_EID', null, null, $id_shop);
        $KCO_NORWAY_EID = Configuration::get('KCO_NORWAY_EID', null, null, $id_shop);
        $KCO_FINLAND_EID = Configuration::get('KCO_FINLAND_EID', null, null, $id_shop);
        $KCO_GERMANY_EID = Configuration::get('KCO_GERMANY_EID', null, null, $id_shop);
        $KCO_AUSTRIA_EID = Configuration::get('KCO_AUSTRIA_EID', null, null, $id_shop);
        $KPM_SV_EID = Configuration::get('KPM_SV_EID', null, null, $id_shop);
        $KPM_NO_EID = Configuration::get('KPM_NO_EID', null, null, $id_shop);
        $KPM_FI_EID = Configuration::get('KPM_FI_EID', null, null, $id_shop);
        $KPM_DA_EID = Configuration::get('KPM_DA_EID', null, null, $id_shop);
        $KPM_DE_EID = Configuration::get('KPM_DE_EID', null, null, $id_shop);
        $KPM_NL_EID = Configuration::get('KPM_NL_EID', null, null, $id_shop);
        $KPM_AT_EID = Configuration::get('KPM_AT_EID', null, null, $id_shop);
        $KCO_UK_EID = Configuration::get('KCO_UK_EID', null, null, $id_shop);
        $KCO_US_EID = Configuration::get('KCO_US_EID', null, null, $id_shop);
        $KCO_NL_EID = Configuration::get('KCO_NL_EID', null, null, $id_shop);
        $KCOV3_SWEDEN_EID = Configuration::get('KCOV3_SWEDEN_EID', null, null, $id_shop);
        $KCOV3_NORWAY_EID = Configuration::get('KCOV3_NORWAY_EID', null, null, $id_shop);
        $KCOV3_FINLAND_EID = Configuration::get('KCOV3_FINLAND_EID', null, null, $id_shop);
        $KCOV3_GERMANY_EID = Configuration::get('KCOV3_GERMANY_EID', null, null, $id_shop);
        $KCOV3_AUSTRIA_EID = Configuration::get('KCOV3_AUSTRIA_EID', null, null, $id_shop);
        $KCOV3_MID = Configuration::get('KCOV3_MID', null, null, $id_shop);
        
        $combosArray[$KCOV3_MID] = Configuration::get('KCOV3_SECRET', null, null, $id_shop);
        $combosArray[$KCOV3_SWEDEN_EID] = Configuration::get('KCOV3_SWEDEN_SECRET', null, null, $id_shop);
        $combosArray[$KCOV3_NORWAY_EID] = Configuration::get('KCOV3_NORWAY_SECRET', null, null, $id_shop);
        $combosArray[$KCOV3_FINLAND_EID] = Configuration::get('KCOV3_FINLAND_SECRET', null, null, $id_shop);
        $combosArray[$KCOV3_GERMANY_EID] = Configuration::get('KCOV3_GERMANY_SECRET', null, null, $id_shop);
        $combosArray[$KCOV3_AUSTRIA_EID] = Configuration::get('KCOV3_AUSTRIA_SECRET', null, null, $id_shop);
        $combosArray[$KCO_AUSTRIA_EID] = Configuration::get('KCO_AUSTRIA_SECRET', null, null, $id_shop);
        $combosArray[$KCO_SWEDEN_EID] = Configuration::get('KCO_SWEDEN_SECRET', null, null, $id_shop);
        $combosArray[$KCO_NORWAY_EID] = Configuration::get('KCO_NORWAY_SECRET', null, null, $id_shop);
        $combosArray[$KCO_FINLAND_EID] = Configuration::get('KCO_FINLAND_SECRET', null, null, $id_shop);
        $combosArray[$KCO_GERMANY_EID] = Configuration::get('KCO_GERMANY_SECRET', null, null, $id_shop);
        $combosArray[$KPM_SV_EID] = Configuration::get('KPM_SV_SECRET', null, null, $id_shop);
        $combosArray[$KPM_NO_EID] = Configuration::get('KPM_NO_SECRET', null, null, $id_shop);
        $combosArray[$KPM_FI_EID] = Configuration::get('KPM_FI_SECRET', null, null, $id_shop);
        $combosArray[$KPM_DA_EID] = Configuration::get('KPM_DA_SECRET', null, null, $id_shop);
        $combosArray[$KPM_DE_EID] = Configuration::get('KPM_DE_SECRET', null, null, $id_shop);
        $combosArray[$KPM_NL_EID] = Configuration::get('KPM_NL_SECRET', null, null, $id_shop);
        $combosArray[$KPM_AT_EID] = Configuration::get('KPM_AT_SECRET', null, null, $id_shop);
        $combosArray[$KCO_UK_EID] = Configuration::get('KCO_UK_SECRET', null, null, $id_shop);
        $combosArray[$KCO_US_EID] = Configuration::get('KCO_US_SECRET', null, null, $id_shop);
        $combosArray[$KCO_NL_EID] = Configuration::get('KCO_NL_SECRET', null, null, $id_shop);

        return $combosArray;
    }

    public function hookDisplayPaymentEU($params)
    {
        if (!$this->active) {
            return;
        }

        if (!$this->checkCurrency($params['cart'])) {
            return;
        }

        $iso = $this->getKlarnaLocale();
        if ($iso == '') {
            $iso = 'sv_se';
        }

        $payment_options = array(
            'cta_text' => $this->l('Klarna'),
            'logo' => 'https://cdn.klarna.com/1.0/shared/image/generic/logo/'.$iso.'/basic/blue-black.png?width=200',
            'action' => $this->context->link->getModuleLink($this->name, 'kpmpartpayment', array(), true)
        );

        return $payment_options;
    }
    
    public function checkPendingStatus($id_order, $output_result = false)
    {
        $sql = 'SELECT reservation, invoicenumber, eid, id_shop FROM '.
        _DB_PREFIX_.'klarna_orders WHERE id_order='.
        (int) $id_order;
        $order_data = Db::getInstance()->getRow($sql);
        $reservation_number = $order_data['reservation'];
        $id_shop = $order_data['id_shop'];
        $eid = $order_data['eid'];
        $eid_ss_comb = $this->getAllEIDSScombinations($id_shop);
        
        if ($eid != "") {
            $shared_secret = $eid_ss_comb[$eid];
            $countryIso = '';
            $languageIso = '';
            $currencyIso = '';
            $k = $this->initKlarnaAPI($eid, $shared_secret, $countryIso, $languageIso, $currencyIso, $id_shop);
            $status = $k->checkOrderStatus($reservation_number);
            if ($status == KlarnaFlags::ACCEPTED) {
                $order = new Order($id_order);
                $sql_update = "UPDATE "._DB_PREFIX_."klarna_orders SET risk_status='ok' ".
                    "WHERE reservation='$reservation_number'";
                    
                Db::getInstance()->execute($sql_update);
                if ($output_result) {
                    echo " APPROVED<br />";
                }
                if (Validate::isLoadedObject($order)) {
                    $new_status = Configuration::get('KPM_ACCEPTED_INVOICE', null, null, $order->id_shop);
                    $history = new OrderHistory();
                    $history->id_order = $id_order;
                    $history->changeIdOrderState((int)$new_status, $id_order, true);
                    $history->addWithemail(true, null);
                }
            } elseif ($status == KlarnaFlags::DENIED) {
                $order = new Order($id_order);
                $sql_update = "UPDATE "._DB_PREFIX_."klarna_orders SET risk_status='Denied' ".
                    "WHERE reservation='$reservation_number'";
                Db::getInstance()->execute($sql_update);
                echo " DENIED<br />";
                if (Validate::isLoadedObject($order)) {
                    if ((int)(Configuration::get('PS_OS_CANCELED'))>0) {
                        $cancel_status = Configuration::get('PS_OS_CANCELED');
                    } else {
                        $cancel_status = _PS_OS_CANCELED_;
                    }
                    $history = new OrderHistory();
                    $history->id_order = $id_order;
                    $history->changeIdOrderState((int)$cancel_status, $id_order, true);
                    $history->addWithemail(true, null);
                }
            }
        } else {
            //Order is missing EID, set as payment error
            $order = new Order($id_order);
            if (Validate::isLoadedObject($order)) {
                if ((int)(Configuration::get('PS_OS_ERROR'))>0) {
                    $error_status = Configuration::get('PS_OS_ERROR');
                } else {
                    $error_status = _PS_OS_ERROR_;
                }
                $history = new OrderHistory();
                $history->id_order = $id_order;
                $history->changeIdOrderState((int)$error_status, $id_order, true);
                $history->addWithemail(true, null);
                echo " MISSING EID<br />";
            }
        }
    }
    public function createAddress($coutry_iso_code, $setting_name, $city, $country, $alias)
    {
        $coutry_iso_code = pSQL($coutry_iso_code);
        $setting_name = pSQL($setting_name);
        $city = pSQL($city);
        $alias = pSQL($alias);
        $country = pSQL($country);
        
        $addressidtoupd = (int)Configuration::get($setting_name);
        $id_country = (int)Country::getByIso($coutry_iso_code);
        if ($addressidtoupd > 0) {
            //since opc is active with alot of countries, it is possible that it can reset the default address
            $sql_fix = "UPDATE "._DB_PREFIX_."address SET id_customer=0, ".
            "id_state=0, id_manufacturer=0, id_supplier=0,id_warehouse=0, ".
            "alias='$alias', company='', lastname='$country',firstname='Person', ".
            "address1='Standardgatan 1', address2='', postcode='12345',city='$city', ".
            "other='', phone='1234567890', phone_mobile='',vat_number='', ".
            "dni='', active='', deleted='',date_upd=NOW(), ".
            "id_country=$id_country WHERE id_address=$addressidtoupd";
            Db::getInstance()->execute($sql_fix);
        } else {
            $sql_fix = "INSERT INTO  "._DB_PREFIX_."address (id_customer, ".
            "id_state, id_manufacturer, id_supplier,id_warehouse, ".
            "alias, company, lastname,firstname, ".
            "address1, address2, postcode,city, ".
            "other, phone, phone_mobile,vat_number, ".
            "dni, active, deleted, id_country) ".
            "VALUES(0,0, 0, 0,0, ".
            "'$alias', '', '$country','Person', ".
            "'Standardgatan 1', '', '12345','$city', ".
            "'', '1234567890', '','', '', 1, 0, $id_country);";
            Db::getInstance()->execute($sql_fix);
            $new_address_id = Db::getInstance()->Insert_ID();
            Configuration::updateValue($setting_name, $new_address_id);
        }
    }
    
    public function changeCurrencyonCart($currency, $iso_code_required)
    {
        if ($currency->iso_code != $iso_code_required) {
            $new_currency = Currency::getIdByIsoCode($iso_code_required);
            $this->context->cookie->id_currency = (int)$new_currency;
            Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarna');
        }
    }
    
    public function fixPrestashopRoundingIssues($value, $multiplier, $scale = 0)
    {
        // if (function_exists('bcmul')) {
            // return bcmul($value, $multiplier, $scale);
        // } else {
            return (string) round($value * $multiplier, $scale);
        // }
    }
    
    public function getKlarnaCountryInformation($currency_iso_code, $language_iso_code)
    {
        if (!Configuration::get('KCO_IS_ACTIVE')) {
            return false;
        }
        
        if (Configuration::get('KCOV3')) {
            $language_code = $this->context->language->language_code;
            
            if ($this->context->cart->id_address_delivery > 0) {
                $temporary_address_object = new Address((int) $this->context->cart->id_address_delivery);
                if ($temporary_address_object->id_customer > 0
                    && $this->context->customer->id > 0 &&
                    $this->context->customer->id == $temporary_address_object->id_customer
                ) {
                    $id_shop_country = $temporary_address_object->id_country;
                }
            }
            
            if (isset($this->context->country)) {
                $id_shop_country = (int)$this->context->country->id;
            }
            if ($id_shop_country == 0) {
                $id_shop_country = (int)Configuration::get('PS_COUNTRY_DEFAULT');
                if ($id_shop_country == 0) {
                    $id_shop_country = (int)Configuration::get('PS_SHOP_COUNTRY_ID');
                }
            }
            
            $shop_country = new Country($id_shop_country);
            return array(
                'locale' => $language_code,
                'purchase_currency' => $currency_iso_code,
                'purchase_country' => $shop_country->iso_code
            );
        }
        
        if ($language_iso_code == 'nb' || $language_iso_code == 'nn') {
            $language_iso_code = 'no';
        }
        
        if (Configuration::get('KCO_SWEDEN') == 0) {
            $sweden_is_active = Configuration::get('KCOV3_SWEDEN');
        } else {
            $sweden_is_active = Configuration::get('KCO_SWEDEN');
        }
        
        if (Configuration::get('KCO_NORWAY') == 0) {
            $norway_is_active = Configuration::get('KCOV3_NORWAY');
        } else {
            $norway_is_active = Configuration::get('KCO_NORWAY');
        }
        
        if (Configuration::get('KCO_UK') == 0) {
            $uk_is_active = Configuration::get('KCOV3_UK');
        } else {
            $uk_is_active = Configuration::get('KCO_UK');
        }
        
        if (Configuration::get('KCO_FINLAND') == 0) {
            $finland_is_active = Configuration::get('KCOV3_FINLAND');
        } else {
            $finland_is_active = Configuration::get('KCO_FINLAND');
        }
        
        if (Configuration::get('KCO_GERMANY') == 0) {
            $germany_is_active = Configuration::get('KCOV3_GERMANY');
        } else {
            $germany_is_active = Configuration::get('KCO_GERMANY');
        }
        
        if (Configuration::get('KCO_AUSTRIA') == 0) {
            $austria_is_active = Configuration::get('KCOV3_AUSTRIA');
        } else {
            $austria_is_active = Configuration::get('KCO_AUSTRIA');
        }

        if (Configuration::get('KCO_NL') == 0) {
            $nl_is_active = Configuration::get('KCOV3_NL');
        } else {
            $nl_is_active = Configuration::get('KCO_NL');
        }
        
        $us_is_active = Configuration::get('KCO_US');
        
        if ($currency_iso_code == 'SEK' &&
         $sweden_is_active == 1) {
            return array('locale' => 'sv-se', 'purchase_currency' => 'SEK', 'purchase_country' => 'SE');
        } elseif ($currency_iso_code == 'NOK' &&
        $norway_is_active == 1) {
            return array('locale' => 'nb-no', 'purchase_currency' => 'NOK', 'purchase_country' => 'NO');
        } elseif ($currency_iso_code == 'GBP' &&
        $uk_is_active == 1) {
            return array('locale' => 'en-gb', 'purchase_currency' => 'GBP', 'purchase_country' => 'GB');
        } elseif ($currency_iso_code == 'USD' &&
        $us_is_active == 1) {
            return array('locale' => 'en-us', 'purchase_currency' => 'USD', 'purchase_country' => 'US');
        } elseif ($currency_iso_code == 'EUR' &&
        $language_iso_code == 'fi' &&
        $finland_is_active == 1) {
            return array('locale' => 'fi-fi', 'purchase_currency' => 'EUR', 'purchase_country' => 'FI');
        } elseif ($currency_iso_code == 'EUR' &&
        $language_iso_code == 'sv' &&
        $finland_is_active == 1) {
            return array('locale' => 'sv-fi', 'purchase_currency' => 'EUR', 'purchase_country' => 'FI');
        } elseif ($currency_iso_code == 'EUR' &&
        $language_iso_code == 'de' &&
        $germany_is_active == 1) {
            return array('locale' => 'de-de', 'purchase_currency' => 'EUR', 'purchase_country' => 'DE');
        } elseif ($currency_iso_code == 'EUR' &&
        $language_iso_code == 'de' &&
        $austria_is_active == 1) {
            return array('locale' => 'de-at', 'purchase_currency' => 'EUR', 'purchase_country' => 'AT');
        } elseif ($currency_iso_code == 'EUR' &&
        $language_iso_code == 'nl' &&
        $nl_is_active == 1) {
            return array('locale' => 'nl-nl', 'purchase_currency' => 'EUR', 'purchase_country' => 'NL');
        } else {
            $id_shop_country = (int)Configuration::get('PS_SHOP_COUNTRY_ID');
            $shop_country = new Country($id_shop_country);
            
            if ($shop_country->iso_code == 'FI' &&
                Configuration::get('KCO_FINLAND') == 1
                && $language_iso_code == 'sv'
            ) {
                return array('locale' => 'sv-fi', 'purchase_currency' => 'EUR', 'purchase_country' => 'FI');
            } elseif ($shop_country->iso_code == 'FI' &&
                Configuration::get('KCO_FINLAND') == 1 &&
                $language_iso_code != 'sv'
            ) {
                return array('locale' => 'fi-fi', 'purchase_currency' => 'EUR', 'purchase_country' => 'FI');
            } elseif ($shop_country->iso_code == 'SE' && Configuration::get('KCO_SWEDEN') == 1) {
                return array('locale' => 'sv-se', 'purchase_currency' => 'SEK', 'purchase_country' => 'SE');
            } elseif ($shop_country->iso_code == 'NO' && Configuration::get('KCO_NORWAY') == 1) {
                return array('locale' => 'nb-no', 'purchase_currency' => 'NOK', 'purchase_country' => 'NO');
            } elseif ($shop_country->iso_code == 'DE' && Configuration::get('KCO_GERMANY') == 1) {
                return array('locale' => 'de-de', 'purchase_currency' => 'EUR', 'purchase_country' => 'DE');
            } elseif ($shop_country->iso_code == 'AT' && Configuration::get('KCO_AUSTRIA') == 1) {
                return array('locale' => 'de-at', 'purchase_currency' => 'EUR', 'purchase_country' => 'AT');
            } elseif ($shop_country->iso_code == 'NL' && Configuration::get('KCO_NL') == 1) {
                return array('locale' => 'nl-nl', 'purchase_currency' => 'EUR', 'purchase_country' => 'NL');
            } elseif ($shop_country->iso_code == 'UK' && Configuration::get('KCO_UK') == 1) {
                return array('locale' => 'en-gb', 'purchase_currency' => 'GBP', 'purchase_country' => 'GB');
            } elseif ($shop_country->iso_code == 'FI' &&
                Configuration::get('KCOV3_FINLAND') == 1 &&
                $language_iso_code == 'sv'
            ) {
                return array('locale' => 'sv-fi', 'purchase_currency' => 'EUR', 'purchase_country' => 'FI');
            } elseif ($shop_country->iso_code == 'FI' &&
                Configuration::get('KCOV3_FINLAND') == 1 &&
                $language_iso_code != 'sv'
            ) {
                return array('locale' => 'fi-fi', 'purchase_currency' => 'EUR', 'purchase_country' => 'FI');
            } elseif ($shop_country->iso_code == 'SE' && Configuration::get('KCOV3_SWEDEN') == 1) {
                return array('locale' => 'sv-se', 'purchase_currency' => 'SEK', 'purchase_country' => 'SE');
            } elseif ($shop_country->iso_code == 'NO' && Configuration::get('KCOV3_NORWAY') == 1) {
                return array('locale' => 'nb-no', 'purchase_currency' => 'NOK', 'purchase_country' => 'NO');
            } elseif ($shop_country->iso_code == 'DE' && Configuration::get('KCOV3_GERMANY') == 1) {
                return array('locale' => 'de-de', 'purchase_currency' => 'EUR', 'purchase_country' => 'DE');
            } elseif ($shop_country->iso_code == 'AT' && Configuration::get('KCOV3_AUSTRIA') == 1) {
                return array('locale' => 'de-at', 'purchase_currency' => 'EUR', 'purchase_country' => 'AT');
            }
        }
        return false;
    }
}
