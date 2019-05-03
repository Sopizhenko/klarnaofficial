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


<div class="klarna-banners">
{if $showbanner || $showbanner1}
	<section class="col-xs-12 col-md-6 banner banner--klarna{if $showbanner1}2{/if}">
		<h2 class="banner__title">
            {if $showbanner1}
                {l s='Klarna is entering a new world of smoooth.' mod='klarnaofficial'}
            {else}
                {l s='Go live with Klarna' mod='klarnaofficial'}
            {/if}
		</h2>
        {if $showbanner1}
        <p>
            {l s='The future proof integration is here! We would love for you to join us on the ride, and to do so you need to upgrade your new Klarna integration. You\'ll then always get the latest features Klarna develops!' mod='klarnaofficial'}
        </p>
        {else}
            <p>
                <strong>
                    {l s='You just downloaded our Klarna module. Fantastic!' mod='klarnaofficial'}
                </strong>
            </p>
            <p>
                {l s='To offer your customers the benefit of paying with Klarna, you must first retrieve your credentials. Sign up for a Klarna account and gain access to the Klarna Merchant Portal where you can generate and download your credentials. You\'ll be up an running in no time!' mod='klarnaofficial'}
            </p>
        {/if}
		<div style="position:relative;width:100%;">
			<a class="banner__cta" target="_blank" href="{if $showbanner1}https://hello.klarna.com/product-upgrade.html{else}https://eu.portal.klarna.com/signup/prestashop?country={$country|escape:'htmlall':'UTF-8'}&platformVersion={$platformVersion|escape:'htmlall':'UTF-8'}&plugin={$plugin|escape:'htmlall':'UTF-8'}&pluginVersion={$pluginVersion|escape:'htmlall':'UTF-8'}{/if}">
            {if $showbanner1}
                {l s='Check it out' mod='klarnaofficial'}
            {else}
                {l s='Go live now' mod='klarnaofficial'}
            {/if}
            </a>
			<img class="lockup" src="../modules/klarnaofficial/views/img/klarna_lockup_logo.png">
		</div>
	</section>
{/if}
	<section class="banner banner--docs {if !$showbanner && !$showbanner1}banner--small{/if}">
		<svg xmlns="http://www.w3.org/2000/svg" style="height:64px;width:64px;" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
		<h2 class="banner__title">
			{l s='Documentation' mod='klarnaofficial'}
		</h2>
		<p>
			{l s='Link and information to documentation comes here...' mod='klarnaofficial'}
		</p>
		<a class="banner__cta" href="{$module_dir|escape:'htmlall':'UTF-8'}doc/index.html?cron_token={$cron_token}" target="_blank" id="fancydocs" title="{l s='Read documentation here' mod='klarnaofficial'}">
			{l s='Read documentation here' mod='klarnaofficial'}
		</a>
	</section>
</div>

{if $isRounding_warning}
    <div class="alert alert-danger">
		 {l s='For the best experience, you should set rounding to "on each article".' mod='klarnaofficial'}
	</div>
{/if}
{if $isNoDecimal_warning}
    <div class="alert alert-danger">
		 {l s='You have turned off decimals in your shop. You may experience rounding issues and payment errors.' mod='klarnaofficial'}
	</div>
{/if}
{if $isPHP7_warning}
    <div class="alert alert-danger">
		 {l s='You use PHP 7, KPM library requires PHP 5' mod='klarnaofficial'}
	</div>
{/if}
{if $isMAINTENANCE_warning}
    <div class="alert alert-danger">
		 {l s='Your shop is in maintenance mode, no callbacks from Klarna will work.' mod='klarnaofficial'}
	</div>
{/if}
{if $isNoSll_warning}
    <div class="alert alert-danger">
		 {l s='Klarna Checkout V3 requires SSL' mod='klarnaofficial'}
	</div>
{/if}
{if $address_check_done}		
	<div class="alert alert-success">
		{l s='Address check done!' mod='klarnaofficial'}
	</div>
{/if}
{if $isSaved}	
	<div class="alert alert-success">
		{l s='Settings updated' mod='klarnaofficial'}
	</div>
{/if}
{if $errorMSG!=''}	
	<div class="alert alert-danger">
		 {$errorMSG|escape:'htmlall':'UTF-8'}
	</div>
{/if}
{if $invoice_fee_not_found}
	<div class="alert alert-danger">
		{l s='Invoice fee product not found!' mod='klarnaofficial'}
	</div>
{/if}
<link href="{$module_dir|escape:'htmlall':'UTF-8'}views/css/klarnacheckout_admin.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="{$module_dir|escape:'htmlall':'UTF-8'}views/js/admin.js"></script>

<div class="tabbable">
	<ul class="nav nav-tabs">
        <li class="active"><a href="#pane7" data-toggle="tab"><i class="icon-AdminParentOrders"></i> {l s='Klarna Checkout V3 (KCO)' mod='klarnaofficial'}</a></li>
		<li><a href="#pane1" data-toggle="tab"><i class="icon-AdminParentOrders"></i> {l s='Klarna Checkout V2 (KCO)' mod='klarnaofficial'}</a></li>
		<li><a href="#pane8" data-toggle="tab"><i class="icon-AdminParentOrders"></i> {l s='Klarna Checkout Common' mod='klarnaofficial'}</a></li>
        <li><a href="#pane2" data-toggle="tab"><i class="icon-AdminParentOrders"></i> {l s='Klarna Payment Method (KPM)' mod='klarnaofficial'}</a></li>
		<li><a href="#pane3" data-toggle="tab"><i class="icon-cogs"></i> {l s='Common settings' mod='klarnaofficial'}</a></li>
		<li><a href="#pane4" data-toggle="tab"><i class="icon-list-alt"></i> {l s='Pclasses' mod='klarnaofficial'}</a></li>
		<li><a href="#pane5" data-toggle="tab"><i class="icon-list-alt"></i> {l s='Terms and Conditions' mod='klarnaofficial'}</a></li>
		<li><a href="#pane6" data-toggle="tab"><i class="icon-list-alt"></i> {l s='Setup' mod='klarnaofficial'}</a></li>
	</ul>
	<div class="panel">
	<div class="tab-content">
		<div id="pane1" class="tab-pane">
			<div class="tabbable row klarnacheckout-admin">
				<div class="col-lg-12 tab-content">
					<div class="sidebar col-lg-2">
						<ul class="nav nav-tabs">							
							<li class="nav-item"><a href="javascript:;" title="{l s='Sweden' mod='klarnaofficial'}" data-panel="1" data-fieldset="0"><i class="icon-AdminParentLocalization"></i>{l s='Sweden' mod='klarnaofficial'}</a></li>
							<li class="nav-item"><a href="javascript:;" title="{l s='Norway' mod='klarnaofficial'}" data-panel="1" data-fieldset="1"><i class="icon-AdminParentLocalization"></i>{l s='Norway' mod='klarnaofficial'}</a></li>
							<li class="nav-item"><a href="javascript:;" title="{l s='Finland' mod='klarnaofficial'}" data-panel="1" data-fieldset="2"><i class="icon-AdminParentLocalization"></i>{l s='Finland' mod='klarnaofficial'}</a></li>
							<li class="nav-item"><a href="javascript:;" title="{l s='Germany' mod='klarnaofficial'}" data-panel="1" data-fieldset="3"><i class="icon-AdminParentLocalization"></i>{l s='Germany' mod='klarnaofficial'}</a></li>
							<li class="nav-item"><a href="javascript:;" title="{l s='Austria' mod='klarnaofficial'}" data-panel="1" data-fieldset="4"><i class="icon-AdminParentLocalization"></i>{l s='Austria' mod='klarnaofficial'}</a></li>
						</ul>
					</div>
					<div id="klarnacheckout-admin" class="col-lg-10">
						{$kcoform nofilter}
					</div>
				</div>
			</div>
		</div>
        
        <div id="pane8" class="tab-pane">
			<div class="tabbable row klarnacheckout-admin">
				<div class="col-lg-12 tab-content">
					<div class="sidebar col-lg-2">
						<ul class="nav nav-tabs">	
                            <li class="nav-item"><a href="javascript:;" title="{l s='General settings' mod='klarnaofficial'}" data-panel="8" data-fieldset="0"><i class="icon-AdminAdmin"></i>{l s='General settings' mod='klarnaofficial'}</a></li>
							<li class="nav-item"><a href="javascript:;" title="{l s='Color settings' mod='klarnaofficial'}" data-panel="8" data-fieldset="1"><i class="icon-AdminParentPreferences"></i>{l s='Color settings' mod='klarnaofficial'}</a></li>
                        </ul>
                    </div>
                    <div id="klarnacheckout-admin" class="col-lg-10">
                        {$kcocommonform nofilter}
                        * {l s='These fields are only applicable in certain markets' mod='klarnaofficial'}
                    </div>
                </div>
            </div>
        </div>
        <div id="pane7" class="tab-pane active">
			<div class="tabbable row klarnacheckout-admin">
				<div class="col-lg-12 tab-content">
					<div class="sidebar col-lg-2">
						<ul class="nav nav-tabs">
                            <li class="nav-item"><a href="javascript:;" title="{l s='KCO V3' mod='klarnaofficial'}" data-panel="7" data-fieldset="0"><i class="icon-AdminParentLocalization"></i>{l s='KCO V3' mod='klarnaofficial'}</a></li>
{* DISABLED FOR NOW			<li class="nav-item"><a href="javascript:;" title="{l s='US' mod='klarnaofficial'}" data-panel="7" data-fieldset="1"><i class="icon-AdminParentLocalization"></i>{l s='US' mod='klarnaofficial'}</a></li>*}
						</ul>
					</div>
					<div id="klarnacheckout-admin" class="col-lg-10">
						{$kcov3form nofilter}
					</div>
				</div>
			</div>
		</div>
		<div id="pane2" class="tab-pane">
			<div class="tabbable row klarnacheckout-admin">
				<div class="col-lg-12 tab-content">
					<div class="sidebar col-lg-2">
						<ul class="nav nav-tabs">
							<li class="nav-item"><a href="javascript:;" title="{l s='General settings' mod='klarnaofficial'}" data-panel="2" data-fieldset="0"><i class="icon-AdminAdmin"></i>{l s='General settings' mod='klarnaofficial'}</a></li>
							<li class="nav-item"><a href="javascript:;" title="{l s='Sweden' mod='klarnaofficial'}" data-panel="2" data-fieldset="1"><i class="icon-AdminParentLocalization"></i>{l s='Sweden' mod='klarnaofficial'}</a></li>
							<li class="nav-item"><a href="javascript:;" title="{l s='Norway' mod='klarnaofficial'}" data-panel="2" data-fieldset="2"><i class="icon-AdminParentLocalization"></i>{l s='Norway' mod='klarnaofficial'}</a></li>
							<li class="nav-item"><a href="javascript:;" title="{l s='Finland' mod='klarnaofficial'}" data-panel="2" data-fieldset="3"><i class="icon-AdminParentLocalization"></i>{l s='Finland' mod='klarnaofficial'}</a></li>
							<li class="nav-item"><a href="javascript:;" title="{l s='Denmark' mod='klarnaofficial'}" data-panel="2" data-fieldset="4"><i class="icon-AdminParentLocalization"></i>{l s='Denmark' mod='klarnaofficial'}</a></li>
							<li class="nav-item"><a href="javascript:;" title="{l s='Germany' mod='klarnaofficial'}" data-panel="2" data-fieldset="5"><i class="icon-AdminParentLocalization"></i>{l s='Germany' mod='klarnaofficial'}</a></li>
							<li class="nav-item"><a href="javascript:;" title="{l s='Netherlands' mod='klarnaofficial'}" data-panel="2" data-fieldset="6"><i class="icon-AdminParentLocalization"></i>{l s='Netherlands' mod='klarnaofficial'}</a></li>
							<li class="nav-item"><a href="javascript:;" title="{l s='Austria' mod='klarnaofficial'}" data-panel="2" data-fieldset="7"><i class="icon-AdminParentLocalization"></i>{l s='Austria' mod='klarnaofficial'}</a></li>
						</ul>
					</div>
					<div id="klarnacheckout-admin" class="col-lg-10">
						{$kpmform nofilter}
					</div>
				</div>
			</div>
		</div>
		<div id="pane3" class="tab-pane">
			<div class="tabbable row klarnacheckout-admin">
				<div class="col-lg-12 tab-content">
					<div class="sidebar col-lg-2" style="display: none;">
						<ul class="nav nav-tabs">
							<li class="nav-item"><a href="javascript:;" title="{l s='General settings' mod='klarnaofficial'}" data-panel="3" data-fieldset="0"><i class="icon-AdminAdmin"></i>{l s='General settings' mod='klarnaofficial'}</a></li>
						</ul>
					</div>
					<div id="klarnacheckout-admin" class="col-lg-12">
						{$commonform nofilter}
					</div>
				</div>
			</div>
		</div>
		<div id="pane4" class="tab-pane">
			{$pclasslist nofilter}
		</div>
		
		<div id="pane5" class="tab-pane">
			<h3>{l s='Germany' mod='klarnaofficial'}</h3>
			<p>{l s='The following text needs to be present in your terms and conditions page under AGP/Payments.' mod='klarnaofficial'}</p>
			
				In Zusammenarbeit mit Klarna bieten wir die folgenden Zahlungsoptionen an. <br />
				Die Zahlung erfolgt jeweils an Klarna:
			<ul>
				<li>Klarna Rechnung: Zahlbar innerhalb von 14 Tagen ab Rechnungsdatum. Die
				Rechnung wird bei Versand der Ware ausgestellt und per Email
				übersandt. Die Rechnungsbedingungen finden Sie hier (https://cdn.klarna.com/1.0/shared/content/legal/terms/<strong style="color:#00aff0">EID</strong>/de_de/invoice?fee=0).</li>
				<li>Klarna Ratenkauf: Mit dem Finanzierungsservice von Klarna können Sie Ihren Einkauf
				flexibel in monatlichen Raten von mindestens 1/24 des Gesamtbetrages (mindestens
				jedoch 6,95 EUR) bezahlen. Weitere Informationen zum Klarna Ratenkauf
				einschließlich der Allgemeinen Geschäftsbedingungen und der europäischen
				Standardinformationen für Verbraucherkredite finden Sie hier. (https://cdn.klarna.com/1.0/shared/content/legal/terms/<strong style="color:#00aff0">EID</strong>/de_de/account)</li>
				<li>Sofortüberweisung</li>
				<li>Kreditkarte (Visa/ Mastercard)</li>
				<li>Lastschrift</li>
			</ul>
				Die Zahlungsoptionen werden im Rahmen von Klarna Checkout angeboten. Nähere
				Informationen und die Nutzungsbedingungen für Klarna Checkout finden Sie hier(https://cdn.klarna.com/1.0/shared/content/legal/terms/<strong style="color:#00aff0">EID</strong>/de_de/checkout).
				Allgemeine Informationen zu Klarna erhalten Sie hier (https://www.klarna.com/de).
			<br />
				Ihre Personenangaben werden von Klarna in Übereinstimmung mit den geltenden Datenschutzbestimmungen und entsprechend den Angaben in Klarnas Datenschutzbestimmungen behandelt (https://cdn.klarna.com/1.0/shared/content/policy/data/de_at/data_protection.pdf).
		</div>
		
		<div id="pane6" class="tab-pane">
			<h3>{l s='Setup' mod='klarnaofficial'}</h3>
			<p>{l s='The following button will run a setup check and see if all default addresses is set up correctly for this shop.' mod='klarnaofficial'}</p>
			<div class="form-wrapper">
				<form class="defaultForm form-horizontal" method="post" action="index.php?controller=AdminModules&configure=klarnaofficial&token={$smarty.get.token|escape:'htmlall':'UTF-8'}&module_name=klarnaofficial">
				<input type="hidden" name="runcheckup" value="1" />
			</div>
			<div class="panel-footer">
				<button id="module_form_submit_btn" class="btn btn-default pull-right" name="btnRunaddressCheckSubmit" value="1" type="submit">
					<i class="process-icon-save"></i>{l s='Run address check' mod='klarnaofficial'}</button>
				</form>
			</div>
		</div>
		
	</div>
	</div>
</div>