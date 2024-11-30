<?php

/*
 * Copyright (c) 2024. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 * Morbi non lorem porttitor neque feugiat blandit. Ut vitae ipsum dolor sit amet, consectetur adipiscing elit.
 * Morbi non lorem porttitor neque feugiat blandit. Ut vitae ipsum eget quam lacinia accumsan.
 * Etiam sed turpis ac ipsum condimentum fringilla. Maecenas magna.
 * Proin dapibus sapien vel ante. Aliquam erat volutpat. Pellentesque sagittis ligula eget metus.
 * Vestibulum commodo. Ut rhoncus gravida arcu.
 */

if (!defined('_CAN_LOAD_FILES_') || !defined('_TB_VERSION_')) {
    exit;
}

/**
 * Class StateCuba
 */
class statecuba extends Module
{
    const CACHE_TTL = 'statecuba';

    public function __construct()
    {
        $this->name = 'statecuba';
        $this->tab = 'dashboard';
        $this->version = '1.0';
        $this->author = 'Studio PlayAzul';
        $this->need_instance = false;
        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Provincias de Cuba');

        // Verificar si el módulo se está activando o desactivando
        $this->checkModuleStatus();

        // Verificar actualizaciones
        $this->checkForUpdates();
    }

    private function checkModuleStatus()
    {
        if (isset($_GET['enable']) && in_array($_GET['enable'], [0, 1])) {
            $enable = (int)$_GET['enable'];
            $this->toggleEnable($enable);
        }
    }

    private function checkForUpdates()
    {
        $current_version = $this->version;
        $remote_version = $this->getRemoteVersion();

        if (version_compare($current_version, $remote_version, '<')) {
            $this->context->controller->warnings[] = $this->l('Una nueva versión del módulo está disponible: ') . $remote_version;
            $this->context->controller->confirmations[] = $this->l('Haga clic en el botón de abajo para actualizar el módulo a la versión ') . $remote_version;
        }
    }

    private function getRemoteVersion()
    {
        $url = 'https://raw.githubusercontent.com/YasmanySA/statecuba/main/composer.json'; // URL del archivo con la versión más reciente
        $json = file_get_contents($url);

        if ($json === false) {
            return $this->version; // Si no se puede obtener la versión remota, retornar la versión actual
        }

        $data = json_decode($json, true);

        return isset($data['version']) ? trim($data['version']) : $this->version;
    }

    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        // Insertar provincias de Cuba
        $this->insertStates();

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        // Obtener el ID del país Cuba
        $id_country = $this->getCountryId('Cuba');

        if ($id_country) {
            // Eliminar las provincias de Cuba de la tabla tb_state
            Db::getInstance()->delete('state', 'id_country = ' . (int)$id_country);

            // Verificar si el último ID corresponde a una provincia de Cuba
            $last_state_id = $this->getLastStateId();
            $last_cuba_state_id = $this->getLastCubaStateId($id_country);

            if ($last_state_id == $last_cuba_state_id) {
                // Reiniciar el ID de la tabla state
                $this->resetStateAutoIncrement();
            }
        }

        return true;
    }

    public function getContent()
    {
        $html = '<h2>' . $this->displayName . '</h2>';

        if (Tools::isSubmit('update_statecuba')) {
            $this->performUpdate();
        }

        $html .= $this->renderUpdateButton();

        return $html;
    }

    private function performUpdate()
    {
        $url = 'https://github.com/YasmanySA/statecuba/archive/refs/heads/main.zip'; // URL del archivo ZIP del repositorio
        $zipFile = _PS_MODULE_DIR_ . $this->name . '/update.zip';
        $extractPath = _PS_MODULE_DIR_ . $this->name;

        // Descargar el archivo ZIP
        file_put_contents($zipFile, fopen($url, 'r'));

        // Extraer el archivo ZIP
        $zip = new ZipArchive;
        if ($zip->open($zipFile) === true) {
            $zip->extractTo($extractPath);
            $zip->close();
            unlink($zipFile); // Eliminar el archivo ZIP descargado

            // Reemplazar los archivos del módulo con los archivos extraídos
            $this->recurseCopy($extractPath . '/statecuba-main/', $extractPath);

            // Eliminar los archivos temporales extraídos
            $this->deleteDir($extractPath . '/statecuba-main/');

            // Confirmación de que el módulo se ha actualizado
            $this->context->controller->confirmations[] = $this->l('El módulo se ha actualizado a la última versión.');
        } else {
            $this->context->controller->errors[] = $this->l('No se pudo actualizar el módulo.');
        }
    }

    private function renderUpdateButton()
    {
        $remote_version = $this->getRemoteVersion();

        if (version_compare($this->version, $remote_version, '<')) {
            return '<form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
                        <input type="submit" name="update_statecuba" value="' . $this->l('Actualizar a la versión ') . $remote_version . '" class="btn btn-primary" />
                    </form>';
        }

        return '';
    }

    private function toggleEnable($enable)
    {
        // Obtener el ID del país Cuba
        $id_country = $this->getCountryId('Cuba');

        if ($id_country) {
            if ($enable) {
                // Activar las provincias de Cuba
                Db::getInstance()->update('state', ['active' => 1], 'id_country = ' . (int)$id_country);
            } else {
                // Desactivar las provincias de Cuba
                Db::getInstance()->update('state', ['active' => 0], 'id_country = ' . (int)$id_country);
            }
        }
    }

    private function insertStates()
    {
        // Obtener el ID del país Cuba
        $id_country = $this->getCountryId('Cuba');

        if ($id_country) {
            $provinces = [
                ['Pinar del Río', 'PRI'],
                ['Artemisa', 'ART'],
                ['La Habana', 'HAB'],
                ['Mayabeque', 'MAY'],
                ['Matanzas', 'MAT'],
                ['Cienfuegos', 'CFG'],
                ['Villa Clara', 'VCL'],
                ['Sancti Spíritus', 'SSP'],
                ['Ciego de Ávila', 'CAV'],
                ['Camagüey', 'CMG'],
                ['Las Tunas', 'LTU'],
                ['Holguín', 'HOL'],
                ['Granma', 'GRA'],
                ['Santiago de Cuba', 'SCU'],
                ['Guantánamo', 'GTM'],
                ['Isla de la Juventud', 'IJU']
            ];

            foreach ($provinces as $province) {
                Db::getInstance()->insert('state', [
                    'id_country' => (int)$id_country,
                    'id_zone' => 8,    // ID de la zona correspondiente
                    'name' => pSQL($province[0]),
                    'iso_code' => pSQL($province[1]),
                    'tax_behavior' => 0,
                    'active' => 1
                ]);
            }
        }
    }

    private function getCountryId($country_name)
    {
        $sql = new DbQuery();
        $sql->select('id_country');
        $sql->from('country_lang');
        $sql->where('name = \'' . pSQL($country_name) . '\'');

        return Db::getInstance()->getValue($sql);
    }

    private function getLastStateId()
    {
        return Db::getInstance()->getValue('SELECT MAX(id_state) FROM ' . _DB_PREFIX_ . 'state');
    }

    private function getLastCubaStateId($id_country)
    {
        return Db::getInstance()->getValue('SELECT MAX(id_state) FROM ' . _DB_PREFIX_ . 'state WHERE id_country = ' . (int)$id_country);
    }

    private function resetStateAutoIncrement()
    {
        // Obtener el último ID de la tabla state
        $last_id = Db::getInstance()->getValue('SELECT MAX(id_state) FROM ' . _DB_PREFIX_ . 'state');

        // Reiniciar el ID de la tabla state
        Db::getInstance()->execute('ALTER TABLE ' . _DB_PREFIX_ . 'state AUTO_INCREMENT = ' . ((int)$last_id + 1));
    }

    private function recurseCopy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurseCopy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    private function deleteDir($dirPath)
    {
        if (!is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
}
