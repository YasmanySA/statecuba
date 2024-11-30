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
        }
    }

    private function getRemoteVersion()
    {
        $url = 'https://raw.githubusercontent.com/YasmanySA/tasaseltoque/main/composer.json'; // URL del archivo con la versión más reciente
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
}
