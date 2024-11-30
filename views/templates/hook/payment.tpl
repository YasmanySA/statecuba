{** * Copyright (C) 2017-2019 thirty bees * Copyright (C) 2007-2016 PrestaShop
SA * * thirty bees is an extension to the PrestaShop software by PrestaShop SA.
* * NOTICE OF LICENSE * * This source file is subject to the Academic Free
License (AFL 3.0) * that is bundled with this package in the file LICENSE.md. *
It is also available through the world-wide-web at this URL: *
https://opensource.org/licenses/afl-3.0.php * If you did not receive a copy of
the license and are unable to * obtain it through the world-wide-web, please
send an email * to license@thirtybees.com so we can send you a copy immediately.
* * @author thirty bees
<modules@thirtybees.com>
  * @author PrestaShop SA
  <contact@prestashop.com>
    * @copyright 2017-2019 thirty bees * @copyright 2007-2016 PrestaShop SA *
    @license Academic Free License (AFL 3.0) * PrestaShop is an internationally
    registered trademark of PrestaShop SA. *}

    <script
      type="text/javascript"
      src="{$module_dir|escape:'htmlall':'UTF-8'}js/jquery.js"
    ></script>
    <script type="text/javascript" lang="javascript"></script>

    <style type="text/css">

      .payment_module a.enzona {
        padding-top: 23px;
        font-size: 1.5em;
        font-style: normal;
        background: url("{$module_dir|escape:'htmlall':'UTF-8'}img/payment.png")
        19px 13px no-repeat;

      }

      .img {
        border: 0;
          /* width: 245px; */
          height: 39px;
      }


    </style>

    <form
            id="payment_enzona" name="payment_enzona" method="post"
      action="{$link->getModuleLink('tasaseltoque', 'payment')|escape:'htmlall':'UTF-8'}" title="{l s='Pay by bank wire' mod='tasaseltoque'}"
    ></form>

    <p class="payment_module">

      <script
        type="text/javascript"
        src="{$module_dir|escape:'htmlall':'UTF-8'}js/loadingoverlay.min.js"
      ></script>
      <script
        type="text/javascript"
        src="{$module_dir|escape:'htmlall':'UTF-8'}js/Loadanimate.js"
      ></script>

      <a
        class="enzona"
        href="#"
{*        href="{$link->getModuleLink('tasaseltoque', 'payment')|escape:'htmlall':'UTF-8'}#"*}
        onclick=" Load1();$('#payment_enzona').submit();"
        title="{l s='Pay by bank wire' mod='tasaseltoque'}"
      >
        <img class="img"
          src="{$module_dir|escape:'htmlall':'UTF-8'}img/enzonalogobuton.gif"


        />
        {l s='El pedido se valida automaticamente' mod='tasaseltoque'}&nbsp;<span
          >{l s='(order processing will be longer)' mod='tasaseltoque'}</span

      </a>
    </p>
  </contact@prestashop.com></modules@thirtybees.com
>
