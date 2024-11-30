<!doctype html>
<html>
<head>
<meta charset="utf-8">


<title>Documento sin título</title>

    <style>

        .errorpage{


            font-size: 38px;

            display: block;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }

        .div {
            margin-bottom: 25px;
            padding: 20px;
            border: 2px solid #047acd;
            border-radius: 39px;
        }

        .text {
            margin-bottom: 25px;
            padding: 0px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .imgsleep{

            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>


</head>

<body>
<div  class="div">

    <h1 class="text">Lo sentimos hay problemas de conexion con la plataforma Enzona.Intentelo mas tarde. </h1>
    <h1 class="text">Si el problema persiste contacte con el Administrador de la tienda. </h1>

    <h2 class="errorpage">ENZONA  </h2>

    {*{$url}*}
    <img class="imgsleep" src="{$url}enzona_sleep.jpg">


</div>

{*<img src="{$module_dir|escape:'htmlall':'UTF-8'}img/pay.png" alt="" width="48" height="48"*}
{*     class="img"/>*}

</body>
</html>
