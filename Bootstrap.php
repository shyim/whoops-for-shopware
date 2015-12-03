<?php
class Shopware_Plugins_Core_WhoopsForShopware_Bootstrap extends Shopware_Components_Plugin_Bootstrap {
    public function getLabel() {
        return 'Whoops for Shopware';
    }

    public function getInfo() {
        return array(
            'version' => '1.0.0',
            'autor' => 'Shyim',
            'label' => $this->getLabel(),
            'support' => 'https://github.com/Shyim/whoops-for-shopware',
            'link' => 'https://github.com/Shyim/whoops-for-shopware'
        );
    }

    public function install() {
        $this->subscribeEvent(
            'Enlight_Controller_Front_StartDispatch',
            'onShopwareStartDispatch'
        );
        return true;
    }

    public function uninstall() {
        return true;
    }

    public function onShopwareStartDispatch(Enlight_Event_EventArgs $args) {
        if($args->getSubject()->getParam('noErrorHandler')) {
            if(strstr($_SERVER['REQUEST_URI'], '/backend')) {
                return;
            }

            spl_autoload_register(array($this, 'loader'));

            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            $whoops->register();
            restore_error_handler();
        }
    }

    public function loader($className) {
        if(!strstr($className, 'Whoops')) {
            return;
        }
        $className = str_replace('\\','/',$className);
        require_once($this->Path() . '/vendor/filp/' . $className . '.php');
    }
}