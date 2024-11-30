<?php
/*
 * Copyright (c) 2024. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 * Morbi non lorem porttitor neque feugiat blandit. Ut vitae ipsum eget quam lacinia accumsan.
 * Etiam sed turpis ac ipsum condimentum fringilla. Maecenas magna.
 * Proin dapibus sapien vel ante. Aliquam erat volutpat. Pellentesque sagittis ligula eget metus.
 * Vestibulum commodo. Ut rhoncus gravida arcu.
 */

if (!defined('_TB_VERSION_')) {
    exit;
}

/**
 * Class ElToque
 */
class tasaseltoque
{

    const CACHE_TTL = 'tasaseltoque';
    // @codingStandardsIgnoreStart
    /** @var string $details */
    public $details;
    /** @var string $owner */
    public $owner;
    /** @var string $address */
    public $address;
    /** @var array $extra_mail_vars */
    public $extra_mail_vars;
    /** @var string $moduleHtml */
    protected $moduleHtml = '';
    /** @var array $postErrors */
    protected $postErrors = [];
    // @codingStandarsdIgnoreEnd

    /**
     * tasasElToque constructor.
     *
     * @throws PrestaShopException
     */
    public function __construct()
    {

        $this->name = 'tasaseltoque';
//        $this->tab = 'payments_gateways';
        $this->tab = 'dashboard';
        $this->version = '1.0';
        $this->author = 'Studio PlayAzul';
        $this->bootstrap = true;
        $this->need_instance = 0;
        $this->controllers = ['payment', 'loadpay', 'cancel', 'validation'];
        $this->is_eu_compatible = 1;

        // $this->currencies = true;
        // $this->currencies_mode = 'checkbox';

        $config = Configuration::getMultiple(['KEY_ELTOQUE']);
        if (!empty($config['KEY_ELTOQUE'])) {
            $this->uuid_comerce = $config['KEY_ELTOQUE'];
        }


        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('tasasElToque');
        $this->description = $this->l('Tasas  no oficial El Toque.');
        $this->tb_versions_compliancy = '>= 1.0';
        $this->tb_min_version = '1.0';
        $this->confirmUninstall = $this->l('Are you sure about removing these details?');

        if (!isset($this->uuid_comerce) || !isset($this->KEY_ELTOQUE) || !isset($this->consumersecret_producer)) {
            $this->warning = $this->l('The owner of the store must configure the ENZONA Production keys and its Commerce Uuid so that the store can function correctly.');
        }
        $paymentCurrencies = Currency::checkPaymentCurrencies($this->id);
        if (!is_array($paymentCurrencies) || !count($paymentCurrencies)) {
            $this->warning = $this->l('No currency has been set for this module.');
        }

        $this->extra_mail_vars = [
            '{rate_CUP}' => Configuration::get('rate_CUP'),
            '{enzona_details}' => nl2br(Configuration::get('BANK_WIRE_DETAILS')),
            '{enzona_address}' => nl2br(Configuration::get('BANK_WIRE_ADDRESS')),
//            '{enzona_UUID_COMERCE}' => Configuration::get('UUID_COMERCE'),
//            '{enzona_KEY_ELTOQUE}' => nl2br(Configuration::get('KEY_ELTOQUE')),
//            '{enzona_KEY_ELTOQUE}' => nl2br(Configuration::get('KEY_ELTOQUE')),

        ];
    }

    /**
     * @return bool
     * @throws PrestaShopException
     */
    public function install()
    {
        if (!parent::install()) {
            return false;
        }

//        $this->registerHook('displayMyAccountBlock');
//        $this->registerHook('displayPaymentEU');
//        $this->registerHook('paymentReturn');

        return true;
    }

    /**
     * @return bool
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function uninstall()
    {
        if (!Configuration::deleteByName('KEY_ELTOQUE')


            || !parent::uninstall()
        ) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    public function getContent()
    {
        if (Tools::isSubmit('btnSubmit')) {
            $this->postValidation();
            if (!is_array($this->postErrors) || !count($this->postErrors)) {
                $this->postProcess();
            } else {
                foreach ($this->postErrors as $err) {
                    $this->moduleHtml .= $this->displayError($err);
                }
            }
        } else {
            $this->moduleHtml .= '<br />';
        }

        $this->moduleHtml .= $this->displayElToque();
        $this->moduleHtml .= $this->renderForm();

        return $this->moduleHtml;
    }

    /**
     * @return string
     * @throws Exception
     * @throws PrestaShopException
     * @throws SmartyException
     */
    public function displayElToque()
    {
        return $this->display(__FILE__, 'infos.tpl');
    }

    /**
     * @return string
     * @throws Exception
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     * @throws SmartyException
     */
    public function renderForm()
    {
        $formFields = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Configura las Claves de la API de El Toque'),
                    'icon' => 'icon-envelope',
                ],
                'input' => [


                    [
                        'type' => 'text',
                        'label' => $this->l('KEY_ELTOQUE '),
                        'name' => 'KEY_ELTOQUE',
                        'required' => true,
                        'empty_message' => $this->l('Please fill the payment api url'),
                    ], [
                        'type' => 'text',
                        'label' => $this->l('1 USD/CUP '),
                        'name' => 'rate_CUP',
                        'required' => true,
                        'empty_message' => $this->l('Please fill the payment api url'),
                    ],


                ], 'button' => [
                    'title' => $this->l('Delete catalog'),
                    'class' => 'btn btn-default pull-right',
                    'name' => 'update_tasas',
                    'id' => 'update_tasas',
                ],

                'submit' => [
                    'title' => $this->l('Save'),
                ]
            ],
        ];

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'btnSubmit';

        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm([$formFields]);
    }

    /**
     * Get the configuration field values
     *
     * @return array
     * @throws PrestaShopException
     */
    public function getConfigFieldsValues()
    {
        return [


            'KEY_ELTOQUE' => Configuration::get('KEY_ELTOQUE'),
            'rate_CUP' => Configuration::get('rate_CUP')


        ];
    }

//    /**
//     * @return string
//     * @throws Exception
//     * @throws PrestaShopException
//     * @throws SmartyException
//     */
//    public function hookPayment()
//    {
//        if (!$this->active) {
//            return '';
//        }
//
//        $this->smarty->assign(
//            [
//                'this_path' => $this->_path,
//                'this_path_bw' => $this->_path,
//                'this_path_ssl' => Tools::getShopDomainSsl(true, true) . __PS_BASE_URI__ . 'modules/' . $this->name . '/',
//            ]
//        );
//
//        return $this->display(__FILE__, 'payment.tpl');
//    }

//    /**
//     * @return array|string
//     * @throws PrestaShopException
//     */
//    public function hookDisplayPaymentEU()
//    {
//        if (!$this->active) {
//            return '';
//        }
//
//        return [
//            'cta_text' => $this->l('Pagar con Enzona'),
//            'logo' => Media::getMediaPath(_PS_MODULE_DIR_ . $this->name . 'tasaseltoque.png'),
//            'action' => $this->context->link->getModuleLink($this->name, 'loadpay', [], true),
//        ];
//    }

//    /**
//     * @param array $params
//     *
//     * @return string
//     * @throws Exception
//     * @throws PrestaShopException
//     * @throws SmartyException
//     */
//    public function hookPaymentReturn($params)
//    {
//
//        if (!isset($params) || !isset($params['objOrder']) || !$params['objOrder'] instanceof Order || !$this->active) {
//            return '';
//        }
//
//        try {
//
//
//            $state = $params['objOrder']->getCurrentState();
//            if (in_array($state, [Configuration::get('PS_OS_PAYMENT'), Configuration::get('PS_OS_OUTOFSTOCK'), Configuration::get('PS_OS_OUTOFSTOCK_UNPAID')])) {
//                $this->smarty->assign(
//                    [
//                        //  'total_to_pay' => Tools::displayPrice($params['total_to_pay']),
//                        'total_to_pay' => number_format($params['total_to_pay'], 2),
//                        'enzonaDetails' => nl2br($this->details),
//                        'enzonaAddress' => nl2br($this->address),
//                        'enzonaOwner' => $this->owner,
//                        'status' => 'ok',
//                        'id_order' => $params['objOrder']->id,
//                        'currency' => $params['currency'],
//                        'id_transacion' => $_GET['id_transacion'],
//                        'img_url' => base64_decode($_GET['img']),
//                        'id_cart' => $_GET['id_cart'],
//                        'id_order' => $_GET['id_order'],
//                        'isoCurrency' => $this->context->currency->iso_code,
//
//
//                    ]
//                );
//                if (isset($params['objOrder']->reference) && !empty($params['objOrder']->reference)) {
//                    $this->smarty->assign('reference', $params['objOrder']->reference);
//                }
//            } else {
//                $this->smarty->assign('status', 'failed');
//            }
//        } catch (PrestaShopException $e) {
//            Logger::addLog("tasaseltoque module error: {$e->getMessage()}");
//
//            return '';
//        }
//
//        return $this->display(__FILE__, 'payment_return.tpl');
//    }

    /**
     * Post process
     *
     * @throws PrestaShopException
     */
    protected function postProcess()
    {
        if (Tools::isSubmit('btnSubmit')) {


            Configuration::updateValue('KEY_ELTOQUE', Tools::getValue('KEY_ELTOQUE'), true);
            Configuration::updateValue('rate_CUP', Tools::getValue('rate_CUP'), true);


        }
        $this->moduleHtml .= $this->displayConfirmation($this->l('Settings updated'));
    }

    /**
     * Post validation
     */
    protected function postValidation()
    {
        if (Tools::isSubmit('btnSubmit')) {
            if (!Tools::getValue('KEY_ELTOQUE')) {
                $this->postErrors[] = $this->l('Required KEY_ELTOQUE.');
            }
        }
    }
}
