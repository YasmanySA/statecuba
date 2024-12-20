{**
 * Copyright (C) 2017-2019 thirty bees
 * Copyright (C) 2007-2016 PrestaShop SA
 *
 * thirty bees is an extension to the PrestaShop software by PrestaShop SA.
 *
 * NOTICE OF LICENSE
 *
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

{if $status == 'ok'}
  <p>{l s='Your order on %s is complete.' sprintf=$shop_name mod='tasaseltoque'}
    <br/><br/>
    {l s='Please send us a bank wire with' mod='tasaseltoque'}
    <br/><br/>- {l s='Enzona Operation Id' mod='tasaseltoque'} <span class="price"><strong>{$id_transacion}</strong></span>
    <br/><br/>- {l s='Amount' mod='tasaseltoque'} <span class="price"><strong>{$currency}{$total_to_pay}</strong></span>
    <br/><br/>- {l s='Name of account owner' mod='tasaseltoque'} <strong>{if $enzonaOwner}{$enzonaOwner|escape:'htmlall':'UTF-8'}{else}___________{/if}</strong>
    <br/><br/>- {l s='Include these details' mod='tasaseltoque'} <strong>{if $enzonaDetails}{$enzonaDetails|escape:'htmlall':'UTF-8'}{else}___________{/if}</strong>
  
    {if !isset($reference)}
      <br/>
      <br/>
      - {l s='Do not forget to insert your order number #%d in the subject of your bank wire.' sprintf=$id_order mod='tasaseltoque'}
    {else}
      <br/>
      <br/>
      - {l s='Do not forget to insert your order reference %s in the subject of your bank wire.' sprintf=$reference mod='tasaseltoque'}
    {/if} <br/><br/>{l s='An email has been sent with this information.' mod='tasaseltoque'}
    <br/><br/> <strong>{l s='Your order will be sent as soon as we receive payment.' mod='tasaseltoque'}</strong>
    <br/><br/>{l s='If you have questions, comments or concerns, please contact our' mod='tasaseltoque'} <a href="{$link->getPageLink('contact', true)|escape:'htmlall':'UTF-8'}">{l s='expert customer support team' mod='tasaseltoque'}</a>.
  </p>
{else}
  <p class="warning">
    {l s='We noticed a problem with your order. If you think this is an error, feel free to contact our' mod='tasaseltoque'}
    <a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='expert customer support team' mod='tasaseltoque'}</a>.
  </p>
{/if}
