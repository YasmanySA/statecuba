{**
 * Copyright (C) 2017-2019 thirty bees
 * Copyright (C) 2007-2016 PrestaShop SA
 *
 * thirty bees is an extension to the PrestaShop software by PrestaShop SA.
 *
 * NOTICE OF LICENSE
 * *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@thirtybees.com so we can send you a copy immediately.
 *
 * @author    thirty bees <modules@thirtybees.com>
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2017-2019 thirty bees
 * @copyright 2007-2016 PrestaShop SA
 * @license   Academic Free License (AFL 3.0)
 * PrestaShop is an internationally registered trademark of PrestaShop SA.
 *}

{capture name=path}
  <a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html':'UTF-8'}" title="{l s='Go back to the Checkout' mod='tasaseltoque'}">{l s='Checkout' mod='tasaseltoque'}</a>
  <span class="navigation-pipe">{$navigationPipe}</span>{l s='Bank-wire payment' mod='tasaseltoque'}
{/capture}

{include file="$tpl_dir./breadcrumb.tpl"}

<h2>{l s='Order summary' mod='tasaseltoque'}</h2>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if $nbProducts <= 0}
  <p class="warning">{l s='Your shopping cart is empty.' mod='tasaseltoque'}</p>
{else}
  <h3>{l s='Bank-wire payment' mod='tasaseltoque'}</h3>
<form action="{$link->getModuleLink('tasaseltoque', 'loadpay', [], true)|escape:'html'}" method="post">
  
  
    <p>
      <img src="{$this_path_bw}enzona.jpg" alt="{l s='Bank wire' mod='tasaseltoque'}" width="86" height="49" style="float:left; margin: 0px 10px 5px 0px;"/>
      {l s='You have chosen to pay by bank wire.' mod='tasaseltoque'}
      <br/><br/>
      {l s='Here is a short summary of your order:' mod='tasaseltoque'}
    </p>
    <p style="margin-top:20px;">
      - {l s='The total amount of your order is' mod='tasaseltoque'}
      <span id="amount" class="price">{displayPrice price=$total}</span>
      
      {if $use_taxes == 1}
        {l s='(tax incl.)' mod='tasaseltoque'}
      {/if}
    </p>
    <p>
      -
      {if $currencies|@count > 1}
        {l s='We allow several currencies to be sent via bank wire.' mod='tasaseltoque'}
        <br/>
        <br/>
        {l s='Choose one of the following:' mod='tasaseltoque'}
        <select id="currency_payement" name="currency_payement" onchange="setCurrency($('#currency_payement').val());">
          {foreach from=$currencies item=currency}
            <option value="{$currency.id_currency}" {if $currency.id_currency == $cust_currency}selected="selected"{/if}>{$currency.name}</option>
          {/foreach}
        </select>
      {else}
        {l s='We allow the following currency to be sent via bank wire:' mod='tasaseltoque'}&nbsp;
        <b>{$currencies[0].name}</b>
        <input type="hidden" name="currency_payement" value="{$currencies.0.id_currency}"/>
      {/if}
    </p>
    <p>
      {l s='Bank wire account information will be displayed on the next page.' mod='tasaseltoque'}
      <br/><br/>
      <b>{l s='Please confirm your order by clicking "I confirm my order".' mod='tasaseltoque'}</b>
    </p>
    <p class="cart_navigation" id="cart_navigation">
      <input type="submit" value="{l s='I confirm my order' mod='tasaseltoque'}" class="exclusive_large"/>
      <a href="{$link->getPageLink('order', true, NULL, "step=3")|escape:'html'}" class="button_large">{l s='Other payment methods' mod='tasaseltoque'}</a>
    </p>
  </form>
{/if}
