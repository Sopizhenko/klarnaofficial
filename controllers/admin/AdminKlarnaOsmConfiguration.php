<?php
/**
* Prestaworks AB
*
* NOTICE OF LICENSE
*
* This source file is subject to the End User License Agreement(EULA)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://license.prestaworks.se/license.html
*
* @author Prestaworks AB <info@prestaworks.se>
* @copyright Copyright Prestaworks AB (https://www.prestaworks.se/)
* @license http://license.prestaworks.se/license.html
*/

class AdminKlarnaOsmConfigurationController extends ModuleAdminController
{
    const OSM_THEME_DEFAULT = 'default';
    const OSM_THEME_DARK = 'dark';
    const OSM_THEME_CUSTOM = '';
    const OSM_PLACEMENTS = [
        'top-strip-promotion-standard',
        'credit-promotion-small',
        'credit-promotion-standard',
        'homepage-promotion-wide',
        'homepage-promotion-box',
        'homepage-promotion-tall',
        'sidebar-promotion-auto-size',
    ];

    public function __construct()
    {
        $this->table = 'klarna_osm_configurations';
        $this->className = 'KlarnaOsmConfiguration';
        $this->bootstrap = true;
        $this->list_no_link = true;
        $this->lang = false;

        parent::__construct();

        if (Shop::getContext() == Shop::CONTEXT_ALL) {
            $this->errors[] = $this->l('You need to select a shop');
            return;
        }

        $this->default_form_language = $this->context->language->id;

        $this->_select .= 'cl.name as country_name';
        $this->_join .= ' LEFT JOIN `'._DB_PREFIX_.'country_lang` cl ON (
                            a.`id_country` = cl.`id_country` AND cl.id_lang = '.$this->context->language->id.'
                        )';

        $this->_where = ' AND a.id_shop = '.(int) $this->context->shop->id;

        $this->fields_list = array(
            'id_klarna_osm_configurations' => array(
                'title' => $this->l('ID'),
                'type' => 'number',
                'orderBy' => true,
                'search' => true,
                'align' => 'text-center',
                'class' => 'fixed-width-xs',
            ),
            'country_name' => array(
                'title' => $this->l('Country'),
                'type' => 'text',
                'orderBy' => true,
                'search' => true,
                'filter_key' => 'cl!name',
            ),
            'product_page' => array(
                'title' => $this->l('Product page'),
                'type' => 'text',
                'orderBy' => true,
                'search' => true,
            ),
            'product_page_theme' => array(
                'title' => $this->l('Product page theme'),
                'type' => 'text',
                'orderBy' => true,
                'search' => true,
            ),
            'cart_page' => array(
                'title' => $this->l('Cart'),
                'type' => 'text',
                'orderBy' => true,
                'search' => true,
            ),
            'cart_page_theme' => array(
                'title' => $this->l('Cart theme'),
                'type' => 'text',
                'orderBy' => true,
                'search' => true,
            ),
            'id_shop' => array(
                'title' => $this->l('Shop'),
                'callback' => 'getShopName',
                'search' => false,
            ),
            'active' => array(
                'title' =>  $this->l('Active'),
                'active' => 'status',
                'align' => 'text-center',
                'type' => 'bool',
                'class' => 'fixed-width-sm',
                'orderby' => true,
            ),
        );

        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash',
            ],
        ];
    }

    public function renderForm()
    {
        $sql = 'SELECT * 
                FROM '._DB_PREFIX_.'country c
                LEFT JOIN '._DB_PREFIX_.'country_lang cl ON (
                    c.id_country = cl.id_country AND cl.id_lang = '.$this->context->language->id.'
                )
                ORDER BY cl.name ASC';

        $countries = Db::getInstance()->executeS($sql);
       
        $countryOptions = [];
        foreach ($countries as $country) {
            $countryOptions[] = [
                'id' => $country['id_country'],
                'name' => $country['name']
            ];
        }

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Configure OSM country'),
                'icon' => 'icon-list'
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->l('OSM Country'),
                    'hint' => $this->l('Select a country'),
                    'name' => 'id_country',
                    'options' => array(
                        'query' => $countryOptions,
                        'id' => 'id',
                        'name' => 'name',
                    ),
                ),

                // product
                array(
                    'type' => 'html',
                    'desc' => $this->l('Product placements'),
                    'name' => ''
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Product page placement'),
                    'desc' => $this->l('Enter data-key for product page placement'),
                    'name' => 'product_page',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Product page placement theme'),
                    'name' => 'product_page_theme',
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
                ),

                // Cart
                array(
                    'type' => 'html',
                    'desc' => $this->l('Cart placements'),
                    'name' => ''
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Cart page placement'),
                    'desc' => $this->l('Enter data-key for cart page placement'),
                    'name' => 'cart_page',
                ),
                array(
                    'type' => 'select',
                    'label' => $this->l('Cart page placement theme'),
                    'name' => 'cart_page_theme',
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
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Active'),
                    'name' => 'active',
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
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'stay' => false,
            ),
        );

        return parent::renderForm();
    }

    public function getShopName($shopId)
    {
        $shop = new Shop($shopId);
        $shopName = $shop->name;

        return $shopName;
    }
}
