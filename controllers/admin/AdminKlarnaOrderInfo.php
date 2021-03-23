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

class AdminKlarnaOrderInfoController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->klarnaorderinfo = '';
        parent::__construct();
    }

    public function renderForm()
    {
        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('View Klarna information for order'),
                'icon' => 'icon-list'
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Klarna reference'),
                    'desc' => $this->l('Enter the klarna order reference'),
                    'name' => 'klarna_order_reference',
                ),
            ),
            'submit' => array(
                'title' => $this->l('Get info'),
                'stay' => true,
            ),
        );
        return $this->klarnaorderinfo.parent::renderForm();
    }
    
    public function renderList()
	{
        return $this->renderForm();
	}
    
    public function postProcess()
	{
        if (Tools::isSubmit('klarna_order_reference')) {
            $reservation_number = Tools::getValue('klarna_order_reference');
            $eid = Configuration::get('KCOV3_MID');
            $shared_secret = Configuration::get('KCOV3_SECRET');
            $headers = $this->module->getKlarnaHeaders();
            
            require_once dirname(__FILE__).'/../../libraries/commonFeatures.php';
            $KlarnaCheckoutCommonFeatures = new KlarnaCheckoutCommonFeatures();
            $kcoorderdata = $KlarnaCheckoutCommonFeatures->getFromKlarna($eid, $shared_secret, $headers, '/ordermanagement/v1/orders/'.$reservation_number);
            
            $kcoorderdata = json_encode(json_decode($kcoorderdata), JSON_PRETTY_PRINT);
            
            $this->context->smarty->assign('kcoorderinfo', $kcoorderdata);
            $kcoorderinfo = $this->context->smarty->fetch('module:klarnaofficial/views/templates/admin/order_info_view.tpl');
            
            
            $this->klarnaorderinfo = $kcoorderinfo;
        }
        parent::postProcess();
    }
}
