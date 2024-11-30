<?php
/*
 * Copyright (c) 2024. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 * Morbi non lorem porttitor neque feugiat blandit. Ut vitae ipsum eget quam lacinia accumsan.
 * Etiam sed turpis ac ipsum condimentum fringilla. Maecenas magna.
 * Proin dapibus sapien vel ante. Aliquam erat volutpat. Pellentesque sagittis ligula eget metus.
 * Vestibulum commodo. Ut rhoncus gravida arcu.
 */

require_once 'FileEditor.php';

class Code
{
    public function insert($archive)
    {
        $str = $_SERVER['DOCUMENT_ROOT'] . '/shop/'; // Añadí una barra al final de la ruta

        if ($archive == 'AdminCurrenciesController') {
            $file_path = $str . 'controllers/admin/AdminCurrenciesController.php';
            $line_find = "if (Tools::isSubmit('SubmitExchangesRates')) {";
            $new_line = "\$currencyUpdater = new rates();\n        \$currencyUpdater->updateAllCurrencies(Configuration::get('PS_CURRENCY_DEFAULT'));"; // Añadí las líneas requeridas
            $line_to_insert = "require_once \$_SERVER['DOCUMENT_ROOT'] . \"/shop/modules/tasaseltoque/rates.php\";"; // Línea que se insertará en la línea 2
            $position = 1; // Posición 1 para insertar en la segunda línea (0-indexada)
        }

        $file_editor = new FileEditor();
        $file_editor->insertLines($file_path, $line_find, $new_line);
        $file_editor->insertLineAtPosition($file_path, $line_to_insert, $position); // Inserta la línea en la posición 2
    }

    /**
     * Elimina líneas específicas de un archivo.
     *
     * Esta función busca y elimina varias líneas específicas en un archivo PHP.
     * La ruta del archivo y las líneas a eliminar se determinan en función del valor del parámetro `$archive`.
     *
     * @param string $archive El archivo a modificar. Actualmente, soporta 'AdminCurrenciesController'.
     *
     * Ejemplo de uso:
     * $obj = new Code();
     * $obj->Delete('AdminCurrenciesController');
     */
    public function Delete($archive)
    {
        $str = $_SERVER['DOCUMENT_ROOT'] . '/shop/'; // Definir la ruta base del documento raíz del servidor

        // Comprobar si el archivo es 'AdminCurrenciesController'
        if ($archive == 'AdminCurrenciesController') {
            $file_path = $str . 'controllers/admin/AdminCurrenciesController.php'; // Ruta completa del archivo
            $lines_to_find = [
                "\$currencyUpdater = new rates();",
                "\$currencyUpdater->updateAllCurrencies(Configuration::get('PS_CURRENCY_DEFAULT'));",
                "require_once \$_SERVER['DOCUMENT_ROOT'] . \"/shop/modules/tasaseltoque/rates.php\";"
            ]; // Líneas a eliminar
        }

        $file_editor = new FileEditor(); // Instanciar la clase FileEditor
        $file_editor->deleteLines($file_path, $lines_to_find); // Eliminar las líneas especificadas
    }


}
