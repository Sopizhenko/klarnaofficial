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

use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

if (!class_exists('KlarnaOsmConfiguration')) {
    require_once dirname(__FILE__) . '/classes/KlarnaOsmConfiguration.php';
}

class KlarnaOfficial extends PaymentModule
{
    public $Pending_risk = 'Pending';
    
    const OSM_THEME_DEFAULT = 'default';
    const OSM_THEME_DARK = 'dark';
    const OSM_THEME_CUSTOM = '';
    
    const OSM_EU_LIBRARY = 1;
    const OSM_USA_LIBRARY = 2;
    const OSM_OC_LIBRARY = 3;
    
    public static $OSM_VALID_COUNTRY_CURRENCY_COMBINATION = array(
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
        'nl' => 'Versand'
    );
    public $wrappingreferences = array(
        'sv' => 'Inslagning',
        'da' => 'Indpakning',
        'de' => 'Verpackung',
        'en' => 'Wrapping',
        'no' => 'Innpakning',
        'nb' => 'Innpakning',
        'fi' => 'Kääre',
        'nl' => 'Verpackung'
    );
    
    public $osm_fields = array(
        'KLARNA_ONSITEMESSAGING_SWITCH_COUNTRY_',
        'KLARNA_ONSITEMESSAGING_PRODUCT_PAGE_THEME_COUNTRY_',
        'KLARNA_ONSITEMESSAGING_CART_PLACEMENT_THEME_COUNTRY_',
        'KLARNA_ONSITEMESSAGING_PRODUCT_PAGE_COUNTRY_',
        'KLARNA_ONSITEMESSAGING_CART_PLACEMENT_COUNTRY_',
    );
    public $configuration_params = array(
        'KLARNA_ONSITE_MESSAGE',
        'KLARNA_ONSITE_MESSAGE_DCI',
        'KLARNA_ONSITEMESSAGING_CONFIGURATION',
        'KLARNA_ONSITEMESSAGING_LIBRARY_PATH_COUNTRY',
        
        'KCOV3',
        'KCOV3_USEGUESTACCOUNTS',
        'KCOV3_PREFILNOT',
        'KCOV3_MID',
        'KCOV3_SECRET',
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
        'KCO_TERMS_PAGE',
        'KCO_RADIUSBORDER',
        'KCO_ADD_NEWSLETTERBOX',
        'KCO_SHOWLINK',
        'KCO_CANCEL_STATE',
        'KCO_ACTIVATE_STATE',
        'KCO_ORDERID',
        'KCO_UK',
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
        'KCO_TITLEMAN',
        'KCO_PREFILL',
        'KCO_SHOW_SHIPDETAILS',
        'KCO_CANCEL_PAGE'
    );
    
    public function __construct()
    {
        $this->name = 'klarnaofficial';
        $this->tab = 'payments_gateways';
        $this->version = '2.2.10';
        $this->author = 'Prestaworks AB';
        $this->module_key = 'b803c9b20c1ec71722eab517259b8ddf';
        $this->need_instance = 1;
        $this->bootstrap = true;
        $this->currencies = true;
        $this->currencies_mode = 'checkbox';

        parent::__construct();

        $this->displayName = $this->l('Klarna');
        $this->description = $this->l('This module offers support for Klarna Checkout and Klarna Payment Methods products.');
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
            || $this->registerHook('displayHeader') == false
            || $this->registerHook('displayFooter') == false
            || $this->registerHook('displayBanner') == false
            || $this->registerHook('displayRightColumn') == false
            || $this->registerHook('displayLeftColumn') == false
            || $this->registerHook('actionOrderStatusUpdate') == false
            || $this->registerHook('displayProductAdditionalInfo') == false
            || $this->registerHook('paymentOptions') == false
            || $this->registerHook('displayOrderConfirmation') == false
            || $this->registerHook('displayShoppingCart') == false
            || $this->registerHook('displayAdminOrder') == false
            || Configuration::updateValue('KCO_ROUNDOFF', 0) == false
            || Configuration::updateValue('KCOV3', 1) == false
            || Configuration::updateValue('KCO_ALLOWMESSAGE', 1) == false
            || Configuration::updateValue('KCO_TESTMODE', 1) == false
            || $this->installTabs() == false
            || $this->setKCOCountrySettings() == false
            ) {
            return false;
        }
        $this->createTables();

        $states = OrderState::getOrderStates(Configuration::get('PS_LANG_DEFAULT'));

        $name = $this->l('Klarna pending payment');
        $config_name = 'KCO_PENDING_PAYMENT';
        $this->createOrderStatus($name, $states, $config_name, false);
        
        $name = $this->l('Klarna payment accepted');
        $config_name = 'KCO_PENDING_PAYMENT_ACCEPTED';
        $this->createOrderStatus($name, $states, $config_name, false);
        
        $name = $this->l('Klarna payment rejected');
        $config_name = 'KCO_PENDING_PAYMENT_REJECTED';
        $this->createOrderStatus($name, $states, $config_name, false);
        
        return true;
    }
    
    public function installTabs()
    {
        $controllerAlreadyExists = Tab::getIdFromClassName('AdminKlarnaOsmConfiguration');

        if ((int) $controllerAlreadyExists === 0) {
            return (bool) $this->createTab(0, $this->l('Klarna OSM Configuration'), 'AdminKlarnaOsmConfiguration');
        }

        return true;
    }

    public function createTab($id_parent, $name, $class_name, $class = '')
    {
        $tab = new Tab();
        $names = [];

        foreach (Language::getLanguages(false) as $lang) {
            $names[$lang['id_lang']] = $name;
        }

        $tab->name = $names;
        $tab->class_name = $class_name;
        $tab->icon = $class;
        
        $tab->id_parent = $id_parent;
        $tab->module = $this->name;
        $tab->add();

        return $tab->id;
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

            if (!copy(
                dirname(__FILE__).'/views/img/klarna_os.gif',
                _PS_IMG_DIR_.'os/'.$orderstate->id.'.gif'
            )) {
                return false;
            }
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

        if (Tools::isSubmit('btnCommonSubmit') ||
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

        $PS_COUNTRY_DEFAULT = (int)Configuration::get('PS_COUNTRY_DEFAULT');
        $country = new Country($PS_COUNTRY_DEFAULT);
        $country_iso_code = $country->iso_code;
        
        $cron_token = Tools::hash(Tools::hash(Tools::hash($this->name)));
        
        $platformVersion = _PS_VERSION_;
        $plugin = $this->name;
        $pluginVersion = $this->version;
        
        $isRounding_warning = false;
        if (1 !== (int)Configuration::get('PS_ROUND_TYPE')) {
            $isRounding_warning = true;
        }
        
        $isNoSll_warning = false;
        if (0 === (int)Configuration::get('PS_SSL_ENABLED')) {
            $isNoSll_warning = true;
        }
        
        $toggle_js_inputs = array();

        $numInputs = 4;
        foreach (Country::getCountries(Configuration::get('PS_LANG_DEFAULT'), true) as $country_data) {
            $toggle_js_inputs['KLARNA_ONSITEMESSAGING_SWITCH_COUNTRY_'.$country_data['iso_code']] = $numInputs;
        }

        $cron_domain = $this->context->link->getBaseLink(null, true, false);
        if ('' == Configuration::get('KCOV3_MID')) {
            $showbanner1 = true;
        } else {
            $showbanner1 = false;
        }
        $this->context->smarty->assign(array(
            'linkToOsmConfig' => $this->context->link->getAdminLink('AdminKlarnaOsmConfiguration'),
            'klarnaisocodedef' => $country_iso_code,
            'showbanner1' => $showbanner1,
            'errorMSG' => $errorMSG,
            'address_check_done' => $address_check_done,
            'isSaved' => $isSaved,
            'toggle_js_inputs' => Tools::jsonEncode($toggle_js_inputs),
            'country' => $country->iso_code,
            'platformVersion' => $platformVersion,
            'pluginVersion' => $pluginVersion,
            'plugin' => $plugin,
            'isRounding_warning' => $isRounding_warning,
            'isNoSll_warning' => $isNoSll_warning,
            'cron_token' => $cron_token,
            'cron_domain' => $cron_domain,
            'osmform' => $this->createOSMForm(),
            'commonform' => $this->createCommonForm(),
            'kcocommonform' => $this->createKCOCommonForm(),
            'kcov3form' => $this->createKCOV3Form(),
            'REQUEST_URI' => Tools::safeOutput($_SERVER['REQUEST_URI']),
        ));
        
        return '<script type="text/javascript">var pwd_base_uri = "'.
        __PS_BASE_URI__.'";var pwd_refer = "'.
        (int) Tools::getValue('ref').'";</script>'.
        $this->display(__FILE__, 'views/templates/admin/klarna_admin.tpl');
    }

    public function createOSMForm()
    {
        
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
                    'type' => 'select',
                    'label' => $this->l('Select library'),
                    'name' => 'KLARNA_ONSITEMESSAGING_LIBRARY_PATH_COUNTRY',
                    'desc' => $this->l('Default is EU library'),
                    'options' => array(
                        'query' => array(
                            array(
                                'value' => self::OSM_EU_LIBRARY,
                                'label' => $this->l('EU'),
                            ),
                            array(
                                'value' => self::OSM_USA_LIBRARY,
                                'label' => $this->l('NA'),
                            ),
                            array(
                                'value' => self::OSM_OC_LIBRARY,
                                'label' => $this->l('OC'),
                            ),
                        ),
                        'id' => 'value',
                        'name' => 'label',
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
    public function createKCOV3Form()
    {
        $fields_form = array();

        $fields_form[0]['form'] = array(
                'legend' => array(
                    'title' => $this->l('Klarna Checkout V3'),
                    'icon' => 'icon-AdminParentLocalization',
                  ),
                'input' => array(
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
                        'label' => $this->l('Klarna API Username'),
                        'name' => 'KCOV3_MID',
                        'class' => 'fixed-width-lg',
                        'required' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Klarna API Password'),
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
            } else {
                $returnarray[$param] = Tools::getValue($param, Configuration::get($param));
            }
        }
        return $returnarray;
    }

    public function hookDisplayBanner($params)
    {
        if ((bool) Configuration::get('KLARNA_ONSITE_MESSAGE')) {
            return $this->displayOnsiteMessagingPlacements('topofpage', $params);
        }
    }
    
    public function hookDisplayLeftColumn($params)
    {
        if ((bool) Configuration::get('KLARNA_ONSITE_MESSAGE')) {
            return $this->displayOnsiteMessagingPlacements('leftcolumn', $params);
        }
    }
    
    public function hookDisplayRightColumn($params)
    {
        if ((bool) Configuration::get('KLARNA_ONSITE_MESSAGE')) {
            return $this->displayOnsiteMessagingPlacements('rightcolumn', $params);
        }
    }
    
    public function hookDisplayFooter($params)
    {
        if ((bool) Configuration::get('KLARNA_ONSITE_MESSAGE')) {
            return $this->displayOnsiteMessagingPlacements('footer', $params);
        }
    }
    
    public function hookDisplayProductAdditionalInfo($params)
    {
        if ((bool) Configuration::get('KLARNA_ONSITE_MESSAGE')) {
            return $this->displayOnsiteMessagingPlacements('product', $params);
        }
    }
    
    // Onsite messaging
    public function displayOnsiteMessagingPlacements($displayLocation, $extraParams = null)
    {
        if (!self::isValidCountryCurrencyOSM()) {
            return;
        }

        $osmConfig = KlarnaOsmConfiguration::getByCountry((int) $this->context->country->id);

        if ((empty($osmConfig)) ||
            ($displayLocation === 'cart' && empty($osmConfig['cart_page'])) ||
            ($displayLocation === 'checkoutklarnakco' && empty($osmConfig['cart_page'])) ||
            ($displayLocation === 'footer' && empty($osmConfig['footer_placement'])) ||
            ($displayLocation === 'topofpage' && empty($osmConfig['topofpage_placement'])) ||
            ($displayLocation === 'leftcolumn' && empty($osmConfig['leftcolumn_placement'])) ||
            ($displayLocation === 'rightcolumn' && empty($osmConfig['rightcolumn_placement'])) ||
            ($displayLocation === 'product' && empty($osmConfig['product_page']))
        ) {
            return;
        }

        $klarna_placement = [];
        
        $languageIsoCode = $this->context->language->iso_code;
        $languageIsoCode = str_replace('gb', 'en', $languageIsoCode);
        $languageIsoCode = str_replace('au', 'en', $languageIsoCode);

        if ("footer" == $displayLocation ||
            "topofpage" == $displayLocation ||
            "leftcolumn" == $displayLocation ||
            "rightcolumn" == $displayLocation
        ) {
            $placement = $osmConfig[$displayLocation.'_placement'];
            $theme = $osmConfig[$displayLocation.'_theme'];
        } else {
            $placement = $osmConfig[$displayLocation.'_page'];
            $theme = $osmConfig[$displayLocation.'_page_theme'];
        }
        
        if ("cart" == $displayLocation) {
            $klarna_placement = [
                'purchase_amount' => (Tools::ps_round($extraParams, 2) * 100),
                'theme' => $theme,
                'id' => $placement,
                'locale' => $languageIsoCode.'-'.$this->context->country->iso_code
            ];
        } elseif ("product" == $displayLocation && isset($extraParams['product']['price_amount'])) {
            $productId = $extraParams['product']['id_product'];
            $groupPriceDisplayMethod = (int) Group::getPriceDisplayMethod((int) $this->context->customer->id_default_group);
            $purchase_amount = Product::getPriceStatic(
                (int) $productId, 
                $groupPriceDisplayMethod === 1 ? false : true,
                null,
                (int) Configuration::get('PS_PRICE_DISPLAY_PRECISION'),
                null,
                false,
                true
            );
            $klarna_placement = [
                'purchase_amount' => ($purchase_amount * 100),
                'theme' => $theme,
                'id' => $placement,
                'locale' => $languageIsoCode.'-'.$this->context->country->iso_code
            ];
        } else {
            $klarna_placement = [
                'theme' => $theme,
                'id' => $placement,
                'locale' => $languageIsoCode.'-'.$this->context->country->iso_code
            ];
        }

        $this->smarty->assign('klarna_placement', $klarna_placement);
        return $this->display(__FILE__, 'onsite_messaging.tpl');
    }
    
    // Onsite messaging
    public function hookDisplayShoppingCart()
    {
        if ((bool) Configuration::get('KLARNA_ONSITE_MESSAGE')) {
            $totalAmount = $this->context->cart->getOrderTotal();
            return $this->displayOnsiteMessagingPlacements('cart', $totalAmount);
        }
    }

    public function hookDisplayHeader()
    {
        if (Tools::getIsset("recover_cart")) {
            Tools::redirect('index.php');
        }
        if (Configuration::get('PS_CATALOG_MODE')) {
            return;
        }
        $this->context->controller->addJS(($this->_path).'views/js/kco_common.js');
        $kco_checkout_url = $this->context->link->getModuleLink('klarnaofficial', 'checkoutklarnakco', array(), true);
        Media::addJsDef(array('kco_checkout_url' => $kco_checkout_url));
        
        if (!(bool) Configuration::get('KLARNA_ONSITE_MESSAGE')) {
            return;
        }
        if (!self::isValidCountryCurrencyOSM()) {
            return;
        }

        $controllerName = Tools::getValue('controller');
        $this->context->controller->addJS($this->_path.'/views/js/onsite_messaging.js');
  
        $osmConfig = KlarnaOsmConfiguration::getByCountry((int) $this->context->country->id);
        if (empty($osmConfig)) {
            return;
        }
        $this->context->smarty->assign([
            'klarna_onsite_messaging_dci' => Configuration::get('KLARNA_ONSITE_MESSAGE_DCI'),
            'klarna_onsite_messaging_url' => self::getOnSiteMessagingUrl()
        ]);
        return $this->display(__FILE__, 'views/templates/hook/onsite_messaging_script.tpl');
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
                            $headers = $this->getKlarnaHeaders();
                            require_once dirname(__FILE__).'/libraries/commonFeatures.php';
                            $KlarnaCheckoutCommonFeatures = new KlarnaCheckoutCommonFeatures();
                            $kcoorder = $KlarnaCheckoutCommonFeatures->getFromKlarna($eid, $shared_secret, $headers, '/ordermanagement/v1/orders/'.$reservation_number);
                            $kcoorder = json_decode($kcoorder, true);
                            
                            if ($invoice_number != '') {
                                $data = array(
                                    'refunded_amount' => $kcoorder['order_amount'],
                                    'description' => 'Refund all of the order',
                                    'order_lines' => $kcoorder['order_lines'],
                                );
                                $KlarnaCheckoutCommonFeatures->postToKlarna($data, $eid, $shared_secret, $headers, '/ordermanagement/v1/orders/'.$reservation_number.'/refunds');
                                $sql = 'UPDATE `'._DB_PREFIX_.
                                "klarna_orders` SET risk_status='credit' WHERE id_order=".
                                (int) $params['id_order'];
                                Db::getInstance()->execute($sql);
                            } else {
                                $KlarnaCheckoutCommonFeatures->postToKlarna($data, $eid, $shared_secret, $headers, '/ordermanagement/v1/orders/'.$reservation_number.'/cancel');
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
                            require_once dirname(__FILE__).'/libraries/commonFeatures.php';
                            $KlarnaCheckoutCommonFeatures = new KlarnaCheckoutCommonFeatures();
                            $headers = $this->getKlarnaHeaders();
                            $kcoorder = $KlarnaCheckoutCommonFeatures->getFromKlarna($eid, $shared_secret, $headers, '/ordermanagement/v1/orders/'.$reservation_number);
                            $kcoorder = json_decode($kcoorder, true);
                            $risk_status = pSQL($kcoorder['fraud_status']);
                            
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
                            
                            $KlarnaCheckoutCommonFeatures->postToKlarna($data, $eid, $shared_secret, $headers, '/ordermanagement/v1/orders/'.$reservation_number.'/captures');
                            $kcoorder = $KlarnaCheckoutCommonFeatures->getFromKlarna($eid, $shared_secret, $headers, '/ordermanagement/v1/orders/'.$reservation_number.'/captures');
                            $kcoorder = json_decode($kcoorder, true);
                            $invoice_number = $kcoorder[0]['klarna_reference'];
                        }
                        $risk_status = pSQL($risk_status);
                        if ("" != $risk_status) {
                            /*only keep this for legacy, remove when v2 and kpm is removed*/
                            $riskupdate = "risk_status='$risk_status' ,";
                        }
                        $invoice_number = pSQL($invoice_number);
                        $sql = 'UPDATE `'._DB_PREFIX_.
                        "klarna_orders` SET $riskupdate invoicenumber='$invoice_number' ".
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

    public function hookDisplayPaymentEU($params)
    {
        if (!$this->active) {
            return;
        }
        if (!$this->checkCurrency($params['cart'])) {
            return;
        }
        if (1 !== (int) Configuration::get('KCO_SHOW_IN_PAYMENT')) {
            return;
        }

        $iso = $this->getKlarnaLocale();
        if ($iso == '') {
            $iso = 'sv_se';
        }

        $payment_options = array(
            'cta_text' => $this->l('Klarna'),
            'logo' => 'https://cdn.klarna.com/1.0/shared/image/generic/logo/'.$iso.'/basic/blue-black.png?width=200',
            'action' => $this->context->link->getModuleLink($this->name, 'checkoutklarnakco', array(), true)
        );

        return $payment_options;
    }
    
    public function hookPaymentOptions($params)
    {
        if (!$this->active) {
            return;
        }
        if (!$this->checkCurrency($this->context->cart)) {
            return;
        }
        if (1 !== (int) Configuration::get('KCO_SHOW_IN_PAYMENT')) {
            return;
        }
        
        $newOption = new PaymentOption();
            
            
        $newOption->setCallToActionText($this->l('Klarna Checkout'))
        ->setAction($this->context->link->getModuleLink($this->name, 'checkoutklarnakco', array(), true));

        $newOptions = array();
        $newOptions[] = $newOption;

        return $newOptions;
    }

    public function hookdisplayOrderConfirmation($params)
    {
        if (!$this->active) {
            return;
        }
        
        if ($params["order"]->module != $this->name) {
            return;
        }
        
        if (Tools::getIsset("kcotpv3")) {
            if (version_compare(phpversion(), '5.4.0', '>=')) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
            } else {
                if (session_id() === '') {
                    session_start();
                }
            }
            $sql = "SELECT reservation FROM "._DB_PREFIX_."klarna_orders WHERE id_order=".(int) $params["order"]->id;
            $klarna_order_id = Db::getInstance()->getValue($sql);
            $merchantId = Configuration::get('KCOV3_MID');
            $sharedSecret = Configuration::get('KCOV3_SECRET');
            require_once dirname(__FILE__).'/libraries/commonFeatures.php';
            $KlarnaCheckoutCommonFeatures = new KlarnaCheckoutCommonFeatures();
            $headers = $this->getKlarnaHeaders();
            $checkout = $KlarnaCheckoutCommonFeatures->getFromKlarna($merchantId, $sharedSecret, $headers, '/checkout/v3/orders/'.$klarna_order_id);
            $checkout = json_decode($checkout, true);
            $snippet = $checkout['html_snippet'];
            
            $this->context->smarty->assign('orderreference', $params["order"]->reference);
            $this->context->smarty->assign('orderid', $params["order"]->id);
            $this->context->smarty->assign('snippet', $snippet);
            if (isset($_SESSION['klarna_checkout_uk'])) {
                unset($_SESSION['klarna_checkout_uk']);
            }
            return $this->display(__FILE__, 'kco_payment_return.tpl');
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

    public function changeAddressOnKCOCart(
        $shipping,
        $billing,
        $customer,
        $cart
    ) {
        $delivery_address_id = 0;
        $invoice_address_id = 0;
        $shipping_iso = Tools::strtoupper($shipping['country']);
        $invocie_iso = Tools::strtoupper($billing['country']);
        $shipping_country_id = Country::getByIso($shipping_iso);
        $invocie_country_id = Country::getByIso($invocie_iso);

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
        
        if (!isset($shipping['organization_name'])) {
            $shipping['organization_name'] = "";
        }
        if (!isset($billing['organization_name'])) {
            $billing['organization_name'] = "";
        }
        if (!isset($shipping['attention'])) {
            $shipping['attention'] = "";
        }
        if (!isset($billing['attention'])) {
            $billing['attention'] = "";
        }
        
        $shipping_state_id = 0;
        $invoice_state_id = 0;
        
        if ($shipping_iso == "IT" || $shipping_iso == "US") {
            if (isset($shipping['region'])) {
                $shippingregion = $shipping['region'];
                $shipping_state_id = State::getIdByIso($shippingregion, $shipping_country_id);
                if (!$shipping_state_id>0) {
                    $shipping_state_id = State::getIdByName(Tools::ucfirst(Tools::strtolower($shippingregion)));
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
                    $invoice_state_id = State::getIdByName(Tools::ucfirst(Tools::strtolower($billingregion)));
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
                //LOAD BILLING ADDRESS
                $cart->id_address_invoice = $address['id_address'];
                $invoice_address_id = $address['id_address'];
            }
        }

        if ($invoice_address_id == 0) {
            //Create address
            $address = new Address();
            $address->firstname = $this->truncateValue($billing['given_name'], 32, true);
            $address->lastname = $this->truncateValue($billing['family_name'], 32, true);
            if (isset($billing['care_of']) && Tools::strlen($billing['care_of']) > 0) {
                $address->address1 = $billing['care_of'];
                $address->address2 = $billing['street_address'];
            } else {
                $address->address1 = $billing['street_address'];
            }

            $address->postcode = $billing['postal_code'];
            $address->phone = $billing['phone'];
            $address->phone_mobile = $billing['phone'];
            $address->city = $billing['city'];
            $address->id_country = $invocie_country_id;
            $address->other = $billing['attention'];
            $address->company = $billing['organization_name'];
            $address->id_customer = $customer->id;
            
            if ($shipping_state_id > 0) {
                $address->id_state = $shipping_state_id;
            }
                
            $address->alias = 'Klarna Address';
            $address->add();
            $cart->id_address_invoice = $address->id;
            $invoice_address_id = $address->id;
            if ($delivery_address_id == 0 && $shipping == $billing) {
                $delivery_address_id = $address->id;
                $cart->id_address_delivery = $address->id;
            }
        }
        if ($delivery_address_id == 0) {
            //Create address
            $address = new Address();
            $address->firstname = $this->truncateValue($shipping['given_name'], 32, true);
            $address->lastname = $this->truncateValue($shipping['family_name'], 32, true);

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
            $address->id_country = $shipping_country_id;
            $address->other = $shipping['attention'];
            $address->company = $shipping['organization_name'];
            $address->id_customer = $customer->id;
            $address->alias = 'Klarna Address';
            $address->add();
            $cart->id_address_delivery = $address->id;
            $delivery_address_id = $address->id;
        }

        $new_delivery_options = array();
        $new_delivery_options[(int) ($delivery_address_id)] = $cart->id_carrier.',';
        if (version_compare(_PS_VERSION_, '1.7.3.0', '<')) {
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

        $cart->id_customer = $customer->id;
        $cart->secure_key = $customer->secure_key;
        $cart->update(true);

        $update_sql = 'UPDATE '._DB_PREFIX_.
        'cart SET id_customer='.
        (int) $customer->id.
        ', secure_key=\''.
        pSQL($customer->secure_key).
        '\' WHERE id_cart='.
        (int) $cart->id;
        
        Db::getInstance()->execute($update_sql);
        
        $cache_id = 'objectmodel_cart_'.$cart->id.'_*';
        Cache::clean($cache_id);
        Cache::clean('getContextualValue*');
        Cache::clean('getPackageShippingCost_'.$cart->id.'_*');
        $cart = new Cart($cart->id);
        if (version_compare(_PS_VERSION_, '1.7.2.5', '>')) {
            $cart->resetStaticCache();
        }
        $cart->getDeliveryOptionList(null, true);
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

        $sql = 'SELECT id_address FROM '._DB_PREFIX_.'address WHERE deleted=0 AND alias=\'KCO_SVERIGE_DEFAULT\'';
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

        $sql = 'SELECT id_address FROM '._DB_PREFIX_.'address WHERE deleted=0 AND alias=\'KCO_UNITED_DEFAULT\'';
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
        
        $sql = 'SELECT id_address FROM '._DB_PREFIX_.'address WHERE deleted=0 AND alias=\'KCO_NL_DEFAULT\'';
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
        
        $sql = 'SELECT id_address FROM '._DB_PREFIX_.'address WHERE deleted=0 AND alias=\'KCO_NORGE_DEFAULT\'';
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
        $sql = 'SELECT id_address FROM '._DB_PREFIX_.'address WHERE deleted=0 AND alias=\'KCO_FINLAND_DEFAULT\'';
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

        $sql = 'SELECT id_address FROM '._DB_PREFIX_.'address WHERE deleted=0 AND alias=\'KCO_GERMANY_DEFAULT\'';
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
        
        $sql = 'SELECT id_address FROM '._DB_PREFIX_.'address WHERE deleted=0 AND alias=\'KCO_AUSTRIA_DEFAULT\'';
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

        $sql = 'SELECT id_address FROM '._DB_PREFIX_.'address WHERE deleted=0 AND alias=\'KCO_UK_DEFAULT\'';
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
            $string = str_replace("-", '', $string);
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
        $KCOV3_SWEDEN_EID = Configuration::get('KCOV3_SWEDEN_EID', null, null, $id_shop);
        $KCOV3_NORWAY_EID = Configuration::get('KCOV3_NORWAY_EID', null, null, $id_shop);
        $KCOV3_FINLAND_EID = Configuration::get('KCOV3_FINLAND_EID', null, null, $id_shop);
        $KCOV3_GERMANY_EID = Configuration::get('KCOV3_GERMANY_EID', null, null, $id_shop);
        $KCOV3_AUSTRIA_EID = Configuration::get('KCOV3_AUSTRIA_EID', null, null, $id_shop);
        $KCOV3_MID = Configuration::get('KCOV3_MID', null, null, $id_shop);
        
        $combosArray[$KCOV3_SWEDEN_EID] = Configuration::get('KCOV3_SWEDEN_SECRET', null, null, $id_shop);
        $combosArray[$KCOV3_NORWAY_EID] = Configuration::get('KCOV3_NORWAY_SECRET', null, null, $id_shop);
        $combosArray[$KCOV3_FINLAND_EID] = Configuration::get('KCOV3_FINLAND_SECRET', null, null, $id_shop);
        $combosArray[$KCOV3_GERMANY_EID] = Configuration::get('KCOV3_GERMANY_SECRET', null, null, $id_shop);
        $combosArray[$KCOV3_AUSTRIA_EID] = Configuration::get('KCOV3_AUSTRIA_SECRET', null, null, $id_shop);
        $combosArray[$KCOV3_MID] = Configuration::get('KCOV3_SECRET', null, null, $id_shop);

        return $combosArray;
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
            "dni='', active=1, deleted=0,date_upd=NOW(), ".
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
    
    public function createNewCustomer(
        $given_name,
        $family_name,
        $email,
        $newsletter,
        $id_gender = 9,
        $date_of_birth = "",
        $cart = null
    ) {
        $password = Tools::passwdGen(8);
        $customer = new Customer();
        $customer->firstname = $this->truncateValue($given_name, 32, true);
        $customer->lastname = $this->truncateValue($family_name, 32, true);
        $customer->email = $email;
        $customer->passwd = Tools::encrypt($password);
        
        $KCOV3_USEGUESTACCOUNTS = Configuration::get(
            'KCOV3_USEGUESTACCOUNTS',
            null,
            $cart->id_shop
        );
        
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
        
        if (Tools::strlen($date_of_birth) > 0) {
            if (Validate::isBirthDate($date_of_birth)) {
                $customer->birthday = $date_of_birth;
            }
        }
        $customer->newsletter = $newsletter;
        $customer->optin = 0;
        $customer->active = 1;
        $customer->id_gender = (int)$id_gender;
        $customer->add();
        if (0 == $customer->is_guest) {
            if (!$this->sendConfirmationMail($customer, $cart->id_lang, $password)) {
                Logger::addLog(
                    'KCO: Failed sending welcome mail to: '.$email,
                    1,
                    null,
                    null,
                    null,
                    true
                );
            }
        }
        return $customer;
    }
    
    public function changeCurrencyonCart($currency, $iso_code_required)
    {
        if ($currency->iso_code != $iso_code_required) {
            $new_currency = Currency::getIdByIsoCode($iso_code_required);
            $this->context->cookie->id_currency = (int)$new_currency;
            Tools::redirect('index.php?fc=module&module=klarnaofficial&controller=checkoutklarna');
        }
    }
    
    public static function isValidCountryCurrencyOSM()
    {
        $countryIsoCode = Context::getContext()->country->iso_code;

        // Check if country+currency matches any of the non-EUR cases defined in the constant, else should be EUR
        if (isset(self::$OSM_VALID_COUNTRY_CURRENCY_COMBINATION[$countryIsoCode])) {
            $defaultCurrency = self::$OSM_VALID_COUNTRY_CURRENCY_COMBINATION[$countryIsoCode];

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
        $endpoints = [
            self::OSM_EU_LIBRARY => [
                1 => 'https://eu-library.playground.klarnaservices.com/lib.js',
                0 => 'https://eu-library.klarnaservices.com/lib.js'
            ],
            self::OSM_USA_LIBRARY => [
                1 => 'https://us-library.playground.klarnaservices.com/lib.js',
                0 => 'https://us-library.klarnaservices.com/lib.js'
            ],
            self::OSM_OC_LIBRARY => [
                1 => 'https://oc-library.playground.klarnaservices.com/lib.js',
                0 => 'https://oc-library.klarnaservices.com/lib.js'
            ]
        ];
        $osmLibrary = (int) Configuration::get('KLARNA_ONSITEMESSAGING_LIBRARY_PATH_COUNTRY');
        $test_mode = (int) Configuration::get('KCO_TESTMODE');
        
        switch ($osmLibrary) {
            case self::OSM_EU_LIBRARY:
                return $endpoints[self::OSM_EU_LIBRARY][$test_mode];
            case self::OSM_USA_LIBRARY:
                return $endpoints[self::OSM_USA_LIBRARY][$test_mode];
            case self::OSM_OC_LIBRARY:
                return $endpoints[self::OSM_OC_LIBRARY][$test_mode];
            default:
                return $endpoints[self::OSM_EU_LIBRARY][$test_mode];
        }
    }
    
    public function fixPrestashopRoundingIssues($value, $multiplier, $scale = 0)
    {
        if (function_exists('bcmul')) {
            return bcmul($value, $multiplier, $scale);
        } else {
            return round($value * $multiplier, $scale);
        }
    }
    
    public function getAttachment()
    {
        return false;
    }
    
    public function getKlarnaCountryInformation($currency_iso_code, $language_iso_code)
    {
        if (!Configuration::get('KCOV3')) {
            return false;
        }
        
        $language_code = $this->context->language->language_code;
        
        if ($this->context->cart->id_address_delivery > 0) {
            $temporary_address_object = new Address((int) $this->context->cart->id_address_delivery);
            if ($temporary_address_object->id_customer > 0 &&
            $this->context->customer->id > 0 &&
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
    
    public function getKlarnaHeaders()
    {
        $headers = array(
            'Content-Type:application/json',
            'UserAgent:Prestaworks.Klarna.kco_rest_php:prestashop:version:'._PS_VERSION_.':klarnaofficial:version:'.$this->version
        );
        return $headers;
    }
}
