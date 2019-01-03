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
{extends $layout}

{block name='content'}
{if isset($klarna_checkout_cart_changed) && $klarna_checkout_cart_changed}
<div class="alert alert-warning">
    {l s='Your cart have changed.' mod='klarnaofficial'}<br />
    {l s='Please check all information below and then continue with the checkout.' mod='klarnaofficial'}
</div>
{/if}

{capture name=path}{l s='Checkout' mod='klarnaofficial'}{/capture}

{if isset($klarna_error)}
{if isset($connectionerror)}
    {if $connectionerror}
        <a href="{$link->getPageLink("order", true)|escape:'html':'UTF-8'}" class="button btn btn-default button-medium">{l s='Go to checkout' mod='klarnaofficial'}</a><br /><br />
    {/if}
{/if}
<div class="alert alert-warning">
	{if $klarna_error=='empty_cart'}
	{l s='Your cart is empty' mod='klarnaofficial'}
	{else}
	{$klarna_error|escape:'html':'UTF-8'}
	{/if}
</div>
{else}
{if isset($vouchererrors) && $vouchererrors!=''}
<div class="alert alert-warning">
	{$vouchererrors|escape:'html':'UTF-8'}
</div>
{/if}
	
<script type="text/javascript">
	// <![CDATA[
	var txtProduct = "{l s='product' js=1 mod='klarnaofficial'}";
	var txtProducts = "{l s='products' js=1 mod='klarnaofficial'}";
	var freeShippingTranslation = "{l s='Free Shipping!' js=1 mod='klarnaofficial'}";
	var kcourl = "{$kcourl|escape:'javascript':'UTF-8'}";
    var isv3 = {$isv3};
	// ]]>
</script>
<style type="text/css">
    .kco-btn--default {
        background: #343434;
        color: #fff;
    }
    .kco-btn--default:hover {
        background: #191919;
        color: #fff;
    }
    .kco-btn--default:active, .kco-btn--default:focus {
        background: #343434;
        color: #fff;
    }
</style>
<div class="kco-cf kco-main">
	<div id="kco_cart_summary_div">
    <div class="cart-grid-body col-xs-12 col-lg-8">

        <!-- cart products detailed -->
        <div class="card cart-container">
          <div class="card-block">
            <h1 class="h1">{l s='Shopping Cart' mod='klarnaofficial'}</h1>
          </div>
          <hr class="separator">
        {include file='checkout/_partials/cart-detailed.tpl' cart=$cart}
        </div>
        </div>
        
        <div class="cart-grid-right col-xs-12 col-lg-4">

        {block name='cart_summary'}
          <div class="card cart-summary">

            {block name='hook_shopping_cart'}
              {hook h='displayShoppingCart'}
            {/block}

            {block name='cart_totals'}
              {include file='checkout/_partials/cart-detailed-totals.tpl' cart=$cart}
            {/block}

          </div>
        {/block}


      </div>
      
	</div><!-- /#kco_cart_summary_div -->
		<div class="col-xs-12">
			{if isset($left_to_get_free_shipping) AND $left_to_get_free_shipping>0}
			<div class="kco-infobox">
				{l s='By shopping for' mod='klarnaofficial'}&nbsp;<strong>{Tools::displayPrice($left_to_get_free_shipping)}</strong>&nbsp;{l s='more, you will qualify for free shipping.' mod='klarnaofficial'}
			</div>
			{/if}
		</div><!-- /.col-xs-12-->

		<div class="cart-grid-body kco-box col-xs-12">
            <div class="card">
                <div class="row">
                    <div class="col-md-4">
                        {if !$isv3}
                        <div class="xcard">
                            <div class="card-block">
                                <span class="kco-step-heading">{l s='Step 1' mod='klarnaofficial'}</span>
                                <h1 class="h1">
                                    {l s='Shipping' mod='klarnaofficial'}
                                </h1>
                            </div>
                                <div class="card-block">
                                    <span class="kco-step-heading">{l s='Carrier' mod='klarnaofficial'}</span>
                                    {if $no_active_countries > 1}
                                    <form action="{$link->getModuleLink('klarnaofficial', $controllername, [], true)|escape:'html':'UTF-8'}" method="post" id="kco_change_country">
                                        <select name="kco_change_country" class="kco-select kco-select--full kco-select--margin" onchange="$('#kco_change_country').submit();">
                                            {if $show_sweden}<option value="sv" {if $kco_selected_country=='SE'}selected="selected"{/if}>{l s='Sweden' mod='klarnaofficial'}</option>{/if}
                                            {if $show_norway}<option value="no" {if $kco_selected_country=='NO'}selected="selected"{/if}>{l s='Norway' mod='klarnaofficial'}</option>{/if}
                                            {if $show_finland}<option value="fi" {if $kco_selected_country=='FI'}selected="selected"{/if}>{l s='Finland' mod='klarnaofficial'}</option>{/if}
                                            {if $show_germany}<option value="de" {if $kco_selected_country=='DE'}selected="selected"{/if}>{l s='Germany' mod='klarnaofficial'}</option>{/if}
                                            {if $show_austria}<option value="at" {if $kco_selected_country=='AT'}selected="selected"{/if}>{l s='Austria' mod='klarnaofficial'}</option>{/if}
                                            {if $show_uk}<option value="gb" {if $kco_selected_country=='GB'}selected="selected"{/if}>{l s='United Kingdom' mod='klarnaofficial'}</option>{/if}
                                            {if $show_us}<option value="us" {if $kco_selected_country=='US'}selected="selected"{/if}>{l s='United States' mod='klarnaofficial'}</option>{/if}
                                            {if $show_nl}<option value="nl" {if $kco_selected_country=='NL'}selected="selected"{/if}>{l s='Netherlands' mod='klarnaofficial'}</option>{/if}
                                        </select>
                                    </form><!-- /#kco_change_country -->
                                    {/if}
                                    <form action="{$link->getModuleLink('klarnaofficial', $controllername, [], true)|escape:'html':'UTF-8'}" method="post" id="klarnacarrier">
                                    <ul class="kco-sel-list has-tooltips">
                                        {foreach from=$delivery_options item=carrier key=carrier_id}
                                        <li class="kco-sel-list__item {if $delivery_option == $carrier_id}selected{/if}">
                                            <div class="radio" style="display:none;">
                                                <span class="checked">
                                                    <input onchange="$('#klarnacarrier').submit()" type="radio" class="hidden_kco_radio" name="delivery_option[{$id_address}]" id="delivery_option_{$carrier.id}" value="{$carrier_id}"{if $delivery_option == $carrier_id} checked{/if}>
                                                </span>
                                            </div>
                                            <label for="delivery_option_{$carrier.id}" class="kco-sel-list__item__label">
                                                <span class="kco-sel-list__item__status">
                                                    <i class="material-icons">done</i>
                                                </span>
                                                <span class="kco-sel-list__item__title">
                                                    {$carrier.name|escape:'html':'UTF-8'}
                                                </span>
                                                <span class="kco-sel-list__item__nbr">
                                                    {if $carrier.price && !$free_shipping}
                                                        {Tools::displayPrice($carrier.price)}
                                                    {else}
                                                        {l s='Free!' mod='klarnaofficial'}
                                                    {/if}
                                                </span>
                                                <span class="kco-sel-list__item__info">
                                                        {$carrier.delay}
                                                        {*$carrier.extraContent*}
                                                </span>
                                            </label>
                                        </li>
                                        {/foreach}
                                    </ul>
                                    </form>
                            </div>
                        </div>
                        {/if}
                        <div class="xcard">
                                <form action="{$link->getModuleLink('klarnaofficial', $controllername, [], true)|escape:'html':'UTF-8'}" method="post" id="klarnamessage">
                                    <div class="">
                                        <div class="card-block">
                                            <span class="kco-step-heading">
                                            {l s='Message' mod='klarnaofficial'}
                                            </span>
                                            <p id="messagearea">
                                                <textarea id="message" name="message" class="kco-input kco-input--area kco-input--full" placeholder="{l s='Add additional information to your order (optional)' mod='klarnaofficial'}">{$message.message|escape:'htmlall':'UTF-8'}</textarea>
                                                <button type="submit" name="savemessagebutton" id="savemessagebutton" class="kco-btn kco-btn--default">
                                                    <span>{l s='Save' mod='klarnaofficial'}</span>
                                                </button>
                                            </p><!-- /#messagearea -->
                                        </div>
                                    </div><!-- /.kco-target -->
                                </form><!-- /#klarnamessage -->
                        </div>
                        <div class="xcard">
                            {if $giftAllowed==1}
                                <form action="{$link->getModuleLink('klarnaofficial', $controllername, [], true)|escape:'html':'UTF-8'}" method="post" id="klarnagift">
                                    <div class="card-block">
                                        <h1 class="h1 kco-trigger {if !$message.message}kco-trigger--inactive{/if}">
                                            {l s='Gift-wrapping' mod='klarnaofficial'}
                                        </h1>
                                    </div>
                                    <div class="kco-target" {if $gift_message == '' && (!isset($gift) || $gift==0)}style="display: none;"{/if}>
                                        <div class="card-block">
                                            <p id="giftmessagearea_long">
                                                <textarea id="gift_message" name="gift_message" class="kco-input kco-input--area kco-input--full" placeholder="{l s='Gift message (optional)' mod='klarnaofficial'}">{$gift_message|escape:'htmlall':'UTF-8'}</textarea>
                                                <input type="hidden" name="savegift" id="savegift" value="1" />
                                                <button type="submit" name="savegiftbutton" id="savegiftbutton" class="btn btn-primary">
                                                    <span>{l s='Save' mod='klarnaofficial'}</span>
                                                </button>
                                                <span class="kco-check-group fl-r">
                                                    <input type="checkbox" onchange="$('#klarnagift').submit();" class="giftwrapping_radio" id="gift" name="gift" value="1"{if isset($gift) AND $gift==1} checked="checked"{/if} />
                                                    <span id="giftwrappingextracost">{l s='Additional cost:' mod='klarnaofficial'} {Tools::displayPrice($gift_wrapping_price)}</span>
                                                </span>
                                            </p><!-- /#giftmessagearea_long -->
                                        </div>
                                    </div><!-- /.kco-target -->
                                </form><!-- /#klarnagift -->
                            {/if}
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="xcard">
                            <div class="card-block">
                                <span class="kco-step-heading">{l s='Step 2' mod='klarnaofficial'}</span>
                                <h1 class="h1">
                                    {l s='Pay for your order' mod='klarnaofficial'}
                                    <span>
                                        {if isset($KCO_SHOWLINK) && $KCO_SHOWLINK}
                                            <a
                                                href="{$link->getPageLink('order', true, NULL, 'step=1')|escape:'html':'UTF-8'}"
                                                class="alternative_methods"
                                                title="{l s='Alternative payment methods' mod='klarnaofficial'}">
                                                <span>{l s='Alternative payment methods' mod='klarnaofficial'}<i class="icon-chevron-right right"></i></span>
                                            </a>
                                        {/if}
                                    </span>
                                </h1>
                            </div>
                            <div class="card-block">
                                <div id="checkoutdiv">{$klarna_checkout nofilter}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
<!-- /#checkoutdiv.col-xs-12 -->
</div><!-- /#height_kco_div -->
{/if}
{/block}