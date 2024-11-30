<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>

    <style>

        .errorpage{


            font-size: 38px;
            margin-left: 260px;

        }


    </style>


</head>

<body>


{*<img src="{$module_dir|escape:'htmlall':'UTF-8'}img/pay.png" alt="" width="48" height="48"*}
{*     class="img"/>*}
<h1>Lo sentimos ha ocurrido un error procesando su pago. Contacte con el Administrador de la tienda. </h1>
<h2 class="errorpage">Error {$error} </h2>
<h2 class="errorpage">            {$url|escape:'htmlall':'UTF-8'} </h2>
{*<img src="{$module_dir|escape:'htmlall':'UTF-8'}img/enzona_sleep.jpg">*}
</body>
</html>
