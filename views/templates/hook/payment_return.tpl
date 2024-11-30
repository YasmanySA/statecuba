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
  * @copyright 2017-2021 thirty bees
  * @copyright 2007-2016 PrestaShop SA
  * @license   Academic Free License (AFL 3.0)
  * PrestaShop is an internationally registered trademark of PrestaShop SA.
  *}


<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Documento sin título</title>
    <style type="text/css">
        .idenzona {
            white-space: nowrap;
            background-color: #9ED3FF;
            font-style: italic;
            border-radius: 70px;
            border: 0px solid black;
            padding-right: 11px;
        }

        .paymodule {
            display: inline-block;
            color: #000000;
            font-family: Cambria, "Hoefler Text", "Liberation Serif", Times, "Times New Roman", serif;
            font-size: 28px;
            text-align: center;
            list-style-position: outside;
        }

        .pay {
            font-size: 18px;
            text-shadow: 0px 0px #000000;
            font-style: normal;
            font-weight: bold;
        }

        .div {
            background-color: #ffffff;
            border-radius: 0px;
            border-top-left-radius: 15px;
            border-top-right-radius: 0px;
            border-bottom-right-radius: 15px;
            border-bottom-left-radius: 0px;
            display: table;
            padding-left: 26px;
            padding-top: 2px;
            margin-bottom: -61px;
            margin-top: 12px;
            border: 1px solid #0537AD;
            -webkit-box-shadow: 0px 0px 14px;
            box-shadow: 0px 0px 3px;
        }

        .div h3 .acept {
            color: #199A00;
            padding-bottom: 72px;
            font-weight: bold;
            font-size: 23px;
        }

        .div h3 .total {
            white-space: nowrap;
            font-style: normal;
            border-radius: 70px;
            border: 0px solid black;
            padding-right: 11px;
            font-family: Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif;
            text-align: left;
            font-variant: normal;
            color: #008B02;
            font-size: 129%;
            -webkit-box-shadow: 0px 0px 0px;
            box-shadow: 0px 0px 0px;
            text-shadow: 0px 0px 21px;
        }

        .div a {
        }

        .div .imgpay {
            background-repeat: repeat;
            background-position: 34px 12px no-repeat #fbfbfb;
            background-image: url(../../../img/enzona.png);
            background-color: #fbfbfb;
            background-size: 26px 0px;
        }
    </style>
</head>


<body>
{if $status == 'ok'}


<div>
    <img src="{$module_dir|escape:'htmlall':'UTF-8'}img/logopay.png" alt="" width="72" height="72"/>
    <div class="paymodule">PLATAFORMA DE PAGO ENZONA</div>
    <div></div>
    <div class="div">
        <div class="pay"><br>
            {l s='Enzona Operation Id' mod='tasaseltoque'} <span class="idenzona"><strong>{$id_transacion}</strong></span>
            <br/></div>

        
        <h3>{l s='Your order in % s has been  ' sprintf=$shop_name mod='tasaseltoque'} <a
                    class="acept">{l s='Accepted.  ' sprintf=$shop_name mod='tasaseltoque'} </a></h3>
        <h3>  {l s='Order ' mod='tasaseltoque'} <a class="idenzona">No. {$id_order}  </a>
            <div></div>
            <a> {l s='Order reference ' sprintf=$reference mod='tasaseltoque'} <a
                        href="{$link->getPageLink('order-detail', true, NULL, "id_order={$id_order|intval}")|escape:'html':'UTF-8'}"
                        class="idenzona">    {l s='  %s' sprintf=$reference mod='tasaseltoque'} </a> </a></h3>
        <h3><img src="{$module_dir|escape:'htmlall':'UTF-8'}/img/pay.png" alt="" width="48" height="48"
                 class="img"/> {l s='Payment amount.' mod='tasaseltoque'} <a class="total">{$total_to_pay}</a> <a
                    class="total">{$isoCurrency} </a></h3>

        {*       {$link->getPageLink('order-detail', true, NULL, "id_order={$order.id_order|intval}")|escape:'html':'UTF-8'}*}

        <div><img src="{$module_dir|escape:'htmlall':'UTF-8'}img/mail2.jpg" alt="" width="48" height="46"
                  class="img"/> {l s='An email has been sent with this information.' mod='tasaseltoque'}</div>
    </div>
    <br/><br/>
    <br/><br/>{l s='If you have questions, comments or concerns, please contact our' mod='tasaseltoque'} <a
            href="{$link->getPageLink('contact', true)|escape:'htmlall':'UTF-8'}">{l s='expert customer support team' mod='tasaseltoque'}</a>.
    </h3>
    {else}

    </h3>
    <p class="warning">
        {l s='We noticed a problem with your order. If you think this is an error, feel free to contact our' mod='tasaseltoque'}
        <a href="{$link->getPageLink('contact', true)|escape:'html'}">{l s='expert customer support team' mod='tasaseltoque'}</a>.
    </p>
    {/if}

    <script src="{$module_dir|escape:'htmlall':'UTF-8'}js/howler.min.js"></script>

    <script>
        var sound = new Howl({
            src: ['{$module_dir|escape:'htmlall':'UTF-8'}sound/confirmation.mp3'],
            volume: 1.0,
            onend: function () {


            }
        });
        sound.play()
    </script>


    <audio>
        <source src="{$module_dir|escape:'htmlall':'UTF-8'}sound/confirmation.mp3" type="audio/mp3">
    </audio>
</body>
</div>
</html>
