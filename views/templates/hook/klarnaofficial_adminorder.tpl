{*
* 2015 Prestaworks AB
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
*}
<div class="row">
	<div class="col-lg-7">
		<div class="panel">
			<div class="panel-heading">{l s='Klarna' mod='klarnaofficial'}</div>
			<div>
				{*{l s='Social security number' mod='klarnaofficial'}: {$klarnacheckout_ssn|escape:'html':'UTF-8'}<br />*}
                {if (isset($klarna_checkbox_info.text_at_time_of_purchase))}
                    {l s='Custom checkbox' mod='klarnaofficial'}:{if ($klarna_checkbox_info.checked)}{l s='True' mod='klarnaofficial'}{else}{l s='False' mod='klarnaofficial'}{/if}
                    {$klarna_checkbox_info.text_at_time_of_purchase|escape:'html':'UTF-8'}<br />
                {/if}
				{l s='Invoice number' mod='klarnaofficial'}: {$klarnacheckout_invoicenumber|escape:'html':'UTF-8'}<br />
				{l s='Reservation' mod='klarnaofficial'}: {$klarnacheckout_reservation|escape:'html':'UTF-8'}<br />
				<span{if $klarnacheckout_risk_status == 'Pending'} style="color:orange; font-weight:bold;"{/if}{if $klarnacheckout_risk_status == 'cancel' || $klarnacheckout_risk_status == 'credit'} style="color:red; font-weight:bold;"{/if}{if $klarnacheckout_risk_status == 'ok' || $klarnacheckout_risk_status == 'ACCEPTED'}  style="color:green; font-weight:bold;"{/if}>
				{l s='Risk status' mod='klarnaofficial'}: {$klarnacheckout_risk_status|escape:'html':'UTF-8'}<br />
				</span>
                {if $klarnacheckout_risk_status == 'Pending'}<a href="../modules/klarnaofficial/checkpendingorders.php" target="_blank">{l s='Check Pending status' mod='klarnaofficial'}</a>{/if}
                {if isset($invoice_download_link)}<a href="{$invoice_download_link|escape:'url':'UTF-8'}" target="_blank">{l s='Download PDF' mod='klarnaofficial'}</a>{/if}
			</div>
			{foreach from=$klarna_errors item=klarna_error}
				<div class="alert alert-danger">{$klarna_error.error_message|escape:'html':'UTF-8'}</div>
			{/foreach}
		</div>
	</div>
</div>
{if $show_kco_v2_warning}
<div class="row">
	<div class="col-lg-7">
		<div class="panel">
			<div class="panel-heading">{l s='Klarna' mod='klarnaofficial'}</div>
            {if $show_kco_v2_warning}<div class="alert alert-danger">{l s='It seems you are using Klarna KCO V2, this service will be discontinued at the end of October 2020.' mod='klarnaofficial'}</div>{/if}
            <div>
            <a href="https://www.klarna.com/international/business/migration-faq/" target="_blank">{l s='More information can be found here https://www.klarna.com/international/business/migration-faq/.' mod='klarnaofficial'}</a>
            </div>
            </div>
	</div>
</div>
{/if}
<script type="text/javascript">
var confirmchangeklarnatext = "{l s='If you change the address, Klarna will not accept the risk of the order. Do you wish to continue?' js=1 mod='klarnaofficial'}";

$("#addressShipping .btn").off("click").on("click", function(e){
return confirm(confirmchangeklarnatext);
});
$("#addressInvoice .btn").off("click").on("click", function(e){
return confirm(confirmchangeklarnatext);
});
</script>