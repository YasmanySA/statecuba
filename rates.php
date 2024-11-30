<?php
/*
 * Copyright (c) 2024. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 * Morbi non lorem porttitor neque feugiat blandit. Ut vitae ipsum eget quam lacinia accumsan.
 * Etiam sed turpis ac ipsum condimentum fringilla. Maecenas magna.
 * Proin dapibus sapien vel ante. Aliquam erat volutpat. Pellentesque sagittis ligula eget metus.
 * Vestibulum commodo. Ut rhoncus gravida arcu.
 */
require_once 'querys.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class rates
{
    /**
     * Helper method to execute sql statement
     *
     * @param string $iso_code
     * @param float $conversion_rate
     */
    public function updatemoney($iso_code, $conversion_rate)
    {
        // Define the new conversion rate value
        $new_conversion_rate = $conversion_rate;

        // Define the query to get id_currency using iso_code
        $query_get_id_currency = "SELECT id_currency FROM tb_currency WHERE iso_code = '$iso_code'";
        $result = Db::getInstance()->executeS($query_get_id_currency);

        // Check if the result is not empty
        if (!empty($result)) {
            $id_currency = $result[0]['id_currency'];

            // Define the update queries
            $update_tb_currency = "UPDATE tb_currency SET conversion_rate = $new_conversion_rate WHERE iso_code = '$iso_code'";
            $update_tb_currency_shop = "UPDATE tb_currency_shop SET conversion_rate = $new_conversion_rate WHERE id_currency = $id_currency";

            // Execute the update queries
            Db::getInstance()->execute($update_tb_currency);
            Db::getInstance()->execute($update_tb_currency_shop);

            // Optionally update the rate_CUP configuration if needed
            Configuration::updateValue('rate_CUP', $new_conversion_rate, true);

            return $id_currency;
        } else {
            return null; // Handle the case where no record is found
        }
    }

// Método para actualizar todas las monedas
    public function updateAllCurrencies($currencydefault)
    {
        // Obtener los valores de la API
        $json_values = $this->Getrate($currencydefault);

        // Verificar si se obtuvieron valores de la API
        if ($json_values === false) {
            // Manejar el error si no se obtuvieron valores
            return false;
        }

        // Decodificar el JSON a un array asociativo
        $values = json_decode($json_values, true);

        // Verificar si la decodificación fue exitosa
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Manejar el error de decodificación
            return false;
        }

        // Actualizar cada moneda
        foreach ($values as $value) {
            $iso_code = $value['iso_code'];
            $conversion_rate = $value['rate'];

            // Verificar si los valores son válidos
            if (!empty($iso_code) && is_numeric($conversion_rate)) {
                $this->updatemoney($iso_code, $conversion_rate);
            }
        }

        return true;
    }


    public function Getrate($id_currencydefault)
    {
        $today = date('Y-m-d');
        $date_from = $today . " 00:00:01";
        $date_to = $today . " 23:59:01";

        // URL base de la API
        $base_url = "https://tasas.eltoque.com/v1/trmi";
        $url = Configuration::get('urltoque') . "?date_from=" . urlencode($date_from) . "&date_to=" . urlencode($date_to);

        // Inicializar Guzzle Client
        $client = new Client();

        try {
            // Hacer la solicitud GET con Guzzle
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . Configuration::get('KEY_ELTOQUE')
                ],
                'verify' => false
            ]);

            // Decodificar la respuesta JSON
            $data = json_decode($response->getBody()->getContents(), true);

            $querysSQL = new querysSQL();
            $codeisodefault = $querysSQL->moneydefalut($id_currencydefault);

            // Obtener los valores de MLC, USD y ECU
            $mlc = $data['tasas']['MLC'];
            $usd = $data['tasas']['USD'];
            $eur = $data['tasas']['ECU'];

            // Valores de las tasas iniciales
            $rates = [
                'USD' => $usd,
                'EUR' => $eur,
                'MLC' => $mlc,
                'CUP' => 1, // El valor de CUP se considera 1 como base
            ];

            // Calcular los valores de las tasas y redondear por exceso
            foreach ($rates as $code => $rate) {
                if ($codeisodefault == $code) {
                    $usd_value = ceil($rate / $usd * 100) / 100;
                    $eur_value = ceil($rate / $eur * 100) / 100;
                    $mlc_value = ceil($rate / $mlc * 100) / 100;
                    $cup_value = ceil($rate * 100) / 100;
                    if ($code == 'USD') $usd_value = 1;
                    if ($code == 'EUR') $eur_value = 1;
                    if ($code == 'MLC') $mlc_value = 1;
                    if ($code == 'CUP') $cup_value = 1;
                }
            }

            // Arreglo de los valores de las tasas
            $values = [
                ['iso_code' => 'USD', 'rate' => $usd_value],
                ['iso_code' => 'EUR', 'rate' => $eur_value],
                ['iso_code' => 'MLC', 'rate' => $mlc_value],
                ['iso_code' => 'CUP', 'rate' => $cup_value],
            ];

            $this->action = 'exchangeRates';
            return json_encode($values);

        } catch (RequestException $e) {
            // Manejar errores
            return json_encode(['error' => 'Error: ' . $e->getMessage()]);
        }
    }


//        return true;


}


