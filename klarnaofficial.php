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

class KlarnaOfficial extends PaymentModule
{
    public $Pending_risk = 'Pending';
    
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

        'KCOV3',
        'KCOV3_PREFILNOT',
        'KCOV3_MID',
        'KCOV3_SECRET',
        'KCOV3_FOOTERBANNER',
       
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
        'KCO_GLOBAL_SECRET',
        'KCO_GLOBAL_EID',
        'KCO_GLOBAL',
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
    
    public function __construct()
    {
        $this->name = 'klarnaofficial';
        $this->tab = 'payments_gateways';
        $this->version = '2.0.8';
        $this->author = 'Prestaworks AB';
        $this->module_key = '0969b3c2f7f0d687c526fbcb0906e204';
        $this->need_instance = 1;
        $this->bootstrap = true;
        $this->currencies = true;
        $this->currencies_mode = 'checkbox';

        parent::__construct();

        $this->displayName = $this->l('Klarna');
        $this->description = $this->l('Gateway for Klarna (KCO and KPM).');
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
            || $this->registerHook('actionOrderStatusUpdate') == false
            || $this->registerHook('displayProductButtons') == false
            || $this->registerHook('paymentOptions') == false
            || $this->registerHook('displayOrderConfirmation') == false
            || $this->registerHook('displayAdminOrder') == false
            || Configuration::updateValue('KCO_ROUNDOFF', 0) == false
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
        
        return true;
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

        if (Tools::isSubmit('runcheckup') && Tools::getValue('runcheckup') == '1') {
            $address_check_done = $this->setKCOCountrySettings();
        }
        if (Tools::isSubmit('btnKPMSubmit') ||
            Tools::isSubmit('btnCommonSubmit') ||
            Tools::isSubmit('btnKCOCommonSubmit') ||
            Tools::isSubmit('btnKCOV3Submit') ||
            Tools::isSubmit('btnKCOSubmit')
        ) {
            foreach ($this->configuration_params as $param) {
                if (Tools::getIsset($param)) {
                     Configuration::updateValue($param, Tools::getValue($param));
                     $isSaved = true;
                }
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
        
        $cron_token = Tools::hash(Tools::hash(Tools::hash($this->name)));
        
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
        
        $this->context->smarty->assign(array(
            'klarnaisocodedef' => $country->iso_code,
            'errorMSG' => $errorMSG,
            'address_check_done' => $address_check_done,
            'isSaved' => $isSaved,
            'country' => $country->iso_code,
            'showbanner1' => $showbanner1,
            'showbanner' => $showbanner,
            'platformVersion' => $platformVersion,
            'pluginVersion' => $pluginVersion,
            'plugin' => $plugin,
            'cron_token' => $cron_token,
            'invoice_fee_not_found' => $invoice_fee_not_found,
            'commonform' => $this->createCommonForm(),
            'kcocommonform' => $this->createKCOCommonForm(),
            'kcov3form' => $this->createKCOV3Form(),
            'kpmform' => $this->createKPMForm(),
            'kcoform' => $this->createKCOForm(),
            'pclasslist' => $this->renderPclassList(),
            'REQUEST_URI' => Tools::safeOutput($_SERVER['REQUEST_URI']),
        ));

        return '<script type="text/javascript">var pwd_base_uri = "'.
        __PS_BASE_URI__.'";var pwd_refer = "'.
        (int) Tools::getValue('ref').'";</script>'.
        $this->display(__FILE__, 'views/templates/admin/klarna_admin.tpl');
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
                        /*array(
                            'value' => 'blue+tuv',
                            'label' => $this->l('blue+tuv (KPM)'), ),
                        array(
                            'value' => 'white+tuv',
                            'label' => $this->l('white+tuv (KPM)'), )*/
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
        foreach ($this->configuration_params as $param) {
            $returnarray[$param] = Tools::getValue($param, Configuration::get($param));
        }
        return $returnarray;
    }

    public function hookDisplayProductButtons($params)
    {
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
        $this->smarty->assign('kco_footer_active', $kco_active);
        $this->smarty->assign('kco_footer_eid', $eid);
        $this->smarty->assign('kco_footer_locale', $klarna_locale);

        return $this->display(__FILE__, 'klarnafooter.tpl');
    }

    public function hookDisplayHeader()
    {
        if (Tools::getIsset("recover_cart")) {
            Tools::redirect('index.php');
        }
        if (Configuration::get('PS_CATALOG_MODE')) {
            return;
        }
        $this->context->controller->addCSS(($this->_path).'views/css/kpm_common.css', 'all');
        $this->context->controller->addJS(($this->_path).'views/js/kco_common.js');
        Media::addJsDef(array('kco_checkout_url' => $this->context->link->getModuleLink('klarnaofficial', 'checkoutklarna', array(), true)));
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

        $sql = 'SELECT * FROM  `'._DB_PREFIX_.'klarna_orders` WHERE id_order='.(int) Tools::getValue('id_order');
        $klarna_orderinfo = Db::getInstance()->getRow($sql);
        $sql = 'SELECT error_message FROM `'._DB_PREFIX_.
        'klarna_errors` WHERE id_order='.(int) Tools::getValue('id_order');
        $klarna_errors = Db::getInstance()->executeS($sql);
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
            if ($newOrderStatus->id == Configuration::get('KCO_CANCEL_STATE', null, null, $order->id_shop)) {
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
            if ($newOrderStatus->id == Configuration::get(
                'KCO_ACTIVATE_STATE',
                null,
                null,
                $order->id_shop
            )) {
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
    
    public function hookPaymentOptions($params)
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
                    if (false == $active_in_country) {
                        $active_in_country = Configuration::get('KCOV3_GERMANY');    
                    }
                } elseif ($country->iso_code=="NO") {
                    $active_in_country = Configuration::get('KCO_NORWAY');
                    if (false == $active_in_country) {
                        $active_in_country = Configuration::get('KCOV3_NORWAY');    
                    }
                } elseif ($country->iso_code=="FI") {
                    $active_in_country = Configuration::get('KCO_FINLAND');
                    if (false == $active_in_country) {
                        $active_in_country = Configuration::get('KCOV3_FINLAND');    
                    }
                } elseif ($country->iso_code=="SE") {
                    $active_in_country = Configuration::get('KCO_SWEDEN');
                    if (false == $active_in_country) {
                        $active_in_country = Configuration::get('KCOV3_SWEDEN');    
                    }
                } elseif ($country->iso_code=="GB") {
                    $active_in_country = Configuration::get('KCO_UK');
                } elseif ($country->iso_code=="AT") {
                    $active_in_country = Configuration::get('KCO_AUSTRIA');
                    if (false == $active_in_country) {
                        $active_in_country = Configuration::get('KCOV3_AUSTRIA');    
                    }
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
        
        $newOptions = array();
        
        $KPM_SHOW_IN_PAYMENTS = Configuration::get('KPM_SHOW_IN_PAYMENTS');
        $KPM_LOGO = Configuration::get('KPM_LOGO');
        $KPM_LOGO_ISO_CODE = $iso;
            
        if (true == $KPM_SHOW_IN_PAYMENTS && (false == $hide_partpayment || false == $hide_invoicepayment)) {
            $newOption = new PaymentOption();
            if (true == $hide_partpayment) {
                $paymentText = $this->l('Pay by Invoice');
            } elseif (true == $hide_invoicepayment) {
                $paymentText = $this->l('Pay by Partpayment');
            } else {
                $paymentText = $this->l('Pay by Invoice / Partpayment');
            }
            
            $paymentAdditionalText = '<img src="https://cdn.klarna.com/1.0/shared/image/generic/logo/'.
                $KPM_LOGO_ISO_CODE.'/basic/'.$KPM_LOGO.'.png?width=200" />';
            
            $newOption->setCallToActionText($paymentText)
                ->setAction($this->context->link->getModuleLink($this->name, 'kpmpartpayment', array(), true))
                ->setAdditionalInformation($paymentAdditionalText);

            $newOptions[] = $newOption;
        }
        
        if (true == $KCO_SHOW_IN_PAYMENTS) {
            $paymentAdditionalText = '<img src="https://cdn.klarna.com/1.0/shared/image/generic/logo/'.
                $KPM_LOGO_ISO_CODE.'/basic/'.$KPM_LOGO.'.png?width=200" />';
            
            $newOption = new PaymentOption();
            $newOption->setCallToActionText($this->l('Klarna Checkout'))
                ->setAction($this->context->link->getModuleLink($this->name, 'checkoutklarna', array(), true))
                ->setAdditionalInformation($paymentAdditionalText);

            $newOptions[] = $newOption;
        }
        
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
        
        if (Tools::getIsset("kcotp")) {
            if (version_compare(phpversion(), '5.4.0', '>=')) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
            } else {
                if (session_id() === '') {
                    session_start();
                }
            }
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
                $this->context->smarty->assign('orderreference', $params["order"]->reference);
                $this->context->smarty->assign('orderid', $params["order"]->id);
                $this->context->smarty->assign('snippet', $snippet);
                unset($_SESSION['klarna_checkout']);
                return $this->display(__FILE__, 'kco_payment_return.tpl');
            } else {
                Tools::redirect('index.php');
            }
        } elseif (Tools::getIsset("kcotpv3")) {
            if (version_compare(phpversion(), '5.4.0', '>=')) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
            } else {
                if (session_id() === '') {
                    session_start();
                }
            }
            $sql = "SELECT reservation FROM "._DB_PREFIX_."klarna_orders WHERE id_order=".(int)$params["order"]->id;
            $orderId = Db::getInstance()->getValue($sql);
            
            require_once dirname(__FILE__).'/libraries/KCOUK/autoload.php';
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

                $checkout = new \Klarna\Rest\Checkout\Order($connector, $orderId);
                $checkout->fetch();
            } else {
                $connector = \Klarna\Rest\Transport\Connector::create(
                    $merchantId,
                    $sharedSecret,
                    \Klarna\Rest\Transport\ConnectorInterface::EU_BASE_URL
                );
              
                $checkout = new \Klarna\Rest\Checkout\Order($connector, $orderId);
                $checkout->fetch();
            }

            $snippet = $checkout['html_snippet'];
            
            $this->context->smarty->assign('orderreference', $params["order"]->reference);
            $this->context->smarty->assign('orderid', $params["order"]->id);
            $this->context->smarty->assign('snippet', $snippet);
            if (isset($_SESSION['klarna_checkout_uk'])) {
                unset($_SESSION['klarna_checkout_uk']);
            }
            return $this->display(__FILE__, 'kco_payment_return.tpl');
        } else {
            $this->context->smarty->assign('orderreference', $params["order"]->reference);
            $this->context->smarty->assign('orderid', $params["order"]->id);
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

    public function changeAddressOnKCOCart(
        $shipping,
        $billing,
        $country_iso_codes,
        $customer,
        $cart
    ) {
        $delivery_address_id = 0;
        $invoice_address_id = 0;
        $shipping_iso = $country_iso_codes[$shipping['country']];
        $invocie_iso = $country_iso_codes[$billing['country']];
        $shipping_country_id = Country::getByIso($shipping_iso);
        $invocie_country_id = Country::getByIso($invocie_iso);

        if (!isset($shipping['care_of'])) {
            $shipping['care_of'] = "";
        }
        if (!isset($billing['care_of'])) {
            $billing['care_of'] = "";
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
            and $address['id_country'] == $invocie_country_id) {
                //LOAD SHIPPING ADDRESS
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
        $cart->resetStaticCache();
        $cart->getDeliveryOptionList(null, true);
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
        
        Db::getInstance()->Execute(
            'UPDATE '._DB_PREFIX_.'customization '.
            'SET id_address_delivery='.(int) $this->context->cart->id_address_delivery.
            ' WHERE id_cart='.(int) $this->context->cart->id
        );
        
        $new_delivery_options = array();
        $new_delivery_options[(int) ($this->context->cart->id_address_delivery)] = $this->context->cart->id_carrier.',';
        if (version_compare(_PS_VERSION_, '1.7.3.0', '<')) {
            $new_delivery_options_serialized = serialize($new_delivery_options);
        } else {
            $new_delivery_options_serialized = json_encode($new_delivery_options);
        }
        
        $update_sql = 'UPDATE '._DB_PREFIX_.'cart '.
                'SET delivery_option=\''.
                pSQL($new_delivery_options_serialized).
                '\' WHERE id_cart='.
                (int) $this->context->cart->id;
        
        Db::getInstance()->execute($update_sql);
        
        if ($this->context->cart->id_carrier > 0) {
            $this->context->cart->delivery_option = $new_delivery_options_serialized;
        } else {
            $this->context->cart->delivery_option = '';
        }
        
        $this->context->cart->update(true);
        $this->context->cart->getPackageList(true);
        $this->context->cart->getDeliveryOptionList(null, true);
        $cache_id = 'objectmodel_cart_'.$this->context->cart->id.'*';
        Cache::clean($cache_id);
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
        
        $combosArray[$KCOV3_SWEDEN_EID] = Configuration::get('KCOV3_SWEDEN_SECRET', null, null, $id_shop);
        $combosArray[$KCOV3_NORWAY_EID] = Configuration::get('KCOV3_NORWAY_SECRET', null, null, $id_shop);
        $combosArray[$KCOV3_FINLAND_EID] = Configuration::get('KCOV3_FINLAND_SECRET', null, null, $id_shop);
        $combosArray[$KCOV3_GERMANY_EID] = Configuration::get('KCOV3_GERMANY_SECRET', null, null, $id_shop);
        $combosArray[$KCOV3_AUSTRIA_EID] = Configuration::get('KCOV3_AUSTRIA_SECRET', null, null, $id_shop);
        $combosArray[$KCOV3_MID] = Configuration::get('KCOV3_SECRET', null, null, $id_shop);
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
    
    public function createNewCustomer($given_name, $family_name, $email, $newsletter, $id_gender = 9, $date_of_birth = "", $cart = null)
    {
        $password = Tools::passwdGen(8);
        $customer = new Customer();
        $customer->firstname = $this->truncateValue($given_name, 32, true);
        $customer->lastname = $this->truncateValue($family_name, 32, true);
        $customer->email = $email;
        $customer->passwd = Tools::encrypt($password);
        $customer->is_guest = 0;
        $customer->id_default_group = (int) (Configuration::get(
            'PS_CUSTOMER_GROUP',
            null,
            $cart->id_shop
        ));
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
        return $customer;
    }
    
    public function getKlarnaCountryInformation($currency_iso_code, $language_iso_code)
    {
        if (!Configuration::get('KCO_IS_ACTIVE')) {
            return false;
        }
        
        if (Configuration::get('KCOV3')) {
            $language_code = $this->context->language->language_code;
            $id_shop_country = (int)Configuration::get('PS_SHOP_COUNTRY_ID');
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
