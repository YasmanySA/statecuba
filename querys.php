<?php
/*
 * Copyright (c) 2024. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 * Morbi non lorem porttitor neque feugiat blandit. Ut vitae ipsum eget quam lacinia accumsan.
 * Etiam sed turpis ac ipsum condimentum fringilla. Maecenas magna.
 * Proin dapibus sapien vel ante. Aliquam erat volutpat. Pellentesque sagittis ligula eget metus.
 * Vestibulum commodo. Ut rhoncus gravida arcu.
 */

class querysSQL
{
// Definir constantes
    // Definir constantes

    const USD = 'USD';
    const EUR = 'EUR';
    const MLC = 'MLC';
    const CUP = 'CUP';

    /**
     * Helper method to execute sql statement
     *
     * @param string $iso_code
     * @param string $name
     * @param string $iso_code_num
     * @param float $conversion_rate
     * @param string[]|null $logs
     */
    public function insertmoney($iso_code, $name, $iso_code_num, $conversion_rate, $logs = null)
    {
        // Define the new conversion rate value
        $new_conversion_rate = $conversion_rate;

        // Define the query to get id_shop
        $query_get_id_shop = "SELECT id_shop FROM tb_shop WHERE name = 'AmazonCuba' LIMIT 1";
        $result = Db::getInstance()->executeS($query_get_id_shop);
//        $result = $this->executeQuery($query_get_id_shop);
        $id_shop = $result[0]['id_shop'];

        // Define the queries
        $queries = [
            // Delete all records with iso_code if there are more than one
            "DELETE FROM tb_currency WHERE iso_code = '$iso_code' AND (SELECT COUNT(*) FROM tb_currency WHERE iso_code = '$iso_code') > 1",

            // Delete the record with iso_code if the column deleted is marked
            "DELETE FROM tb_currency WHERE iso_code = '$iso_code' AND deleted = 1",

            // Delete the corresponding record in tb_currency_shop
            "DELETE FROM tb_currency_shop WHERE id_currency = (SELECT id_currency FROM tb_currency WHERE iso_code = '$iso_code' AND deleted = 1)",

            // Update the record if it exists and is not marked as deleted
            "UPDATE tb_currency SET conversion_rate = $new_conversion_rate WHERE iso_code = '$iso_code' AND deleted = 0",

            // Insert the record if it does not exist or was deleted
            "INSERT INTO tb_currency (name, iso_code, iso_code_num, sign, blank, format, decimals, decimal_places, conversion_rate, deleted, active)
         SELECT '$name', '$iso_code', '$iso_code_num', '$', 0, 0, 1, 2, $new_conversion_rate, 0, 1
         WHERE NOT EXISTS (SELECT 1 FROM tb_currency WHERE iso_code = '$iso_code' AND deleted = 0)",

            // Get the id_currency of the inserted or updated record
            "SET @id_currency = (SELECT id_currency FROM tb_currency WHERE iso_code = '$iso_code' LIMIT 1)",

            // Insert into tb_currency_shop
            "INSERT INTO tb_currency_shop (id_currency, id_shop, conversion_rate)
         VALUES (@id_currency, $id_shop, $new_conversion_rate)
         ON DUPLICATE KEY UPDATE conversion_rate = $new_conversion_rate"
        ];

        // Execute the queries
        foreach ($queries as $query) {
            Db::getInstance()->execute($query);
//            $this->executeQuery($query);
        }
    }

    // Método para insertar las cuatro monedas base
    public function insertarMonedasBase()
    {
        $this->insertmoney(self::USD, 'Dólar Estadounidense', '840', 1.00); // USD primero
        $this->insertmoney(self::EUR, 'Euro', '978', 0.85); // EUR segundo
        $this->insertmoney(self::MLC, 'Moneda Libremente Convertible', '999', 24.00); // MLC tercero
        $this->insertmoney(self::CUP, 'Peso Cubano', '192', 1.00); // CUP último

        $currencyUpdater = new rates();
        $currencyUpdater->updateAllCurrencies(Configuration::get('PS_CURRENCY_DEFAULT'));
    }


    /**
     * Helper method to execute sql statement
     *
     * @param int $id_currency
     * @param string[]|null $logs
     */
    public function moneydefalut($id_currency)
    {
        // Define the query to get iso_code using id_currency
        $query_get_iso_code = "SELECT iso_code FROM tb_currency WHERE id_currency = $id_currency LIMIT 1";
        $result = Db::getInstance()->executeS($query_get_iso_code);


        // Check if the result is not empty
        if (!empty($result)) {
            $iso_code = $result[0]['iso_code'];
            return $iso_code;
        } else {
            return null; // or handle the case where no record is found
        }
    }

//    /**
//     * Helper method to execute sql statement
//     *
//     * @param int $id_currency
//     * @param string[]|null $logs
//     */
//    public function Getmoneydefalut($id_currency)
//    {
//        // Define the query to get iso_code using id_currency
//        $query_get_iso_code = "SELECT iso_code FROM tb_currency WHERE id_currency = $id_currency LIMIT 1";
//        $result = Db::getInstance()->executeS($query_get_iso_code);
//
//
//        // Check if the result is not empty
//        if (!empty($result)) {
//            $iso_code = $result[0]['iso_code'];
//            return $iso_code;
//        } else {
//            return null; // or handle the case where no record is found
//        }
//    }


}