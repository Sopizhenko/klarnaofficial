/*
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
*/
$(document).ready(function()
{
    prestashop.on(
      'updateCart',
      function (event) {
          if (event.reason != "KCOorderChange") {
                updateKCOV3();
          }
      }
    );
});

$(document).ready(function(){ 
    if(isv3) {
        window._klarnaCheckout(function(api) {
          api.on({
            'order_total_change': function(data) {
                prestashop.emit('updateCart', {reason: 'KCOorderChange'});
            }
          });
        });
    }
    $('.kco-trigger').each(function(){
        var el = $(this);
        var elTarget = el.parent().parent().find('.kco-target');
        el.click(function(){
            el.toggleClass('kco-trigger--inactive');
            elTarget.fadeToggle(150);
        });
    });
    $('.kco-sel-list__item').each(function(){
        var el = $(this);
        el.click(function(){
            el.siblings().removeClass('selected');
            el.addClass('selected');
        });
    });
});

function updateKCOV3()
{
    window._klarnaCheckout(function (api) {
        api.suspend();
    });
    $.ajax({
		type: 'GET',
		url: kcourl,
		async: false,
		cache: false,
		data: 'kco_update=1',
		success: function(jsonData)
		{
            if ('error' == jsonData) {
                location.href = kcocarturl;
            }
			window._klarnaCheckout(function (api) {
              api.resume();
            });
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert(jsonData);
		}
    });
}
