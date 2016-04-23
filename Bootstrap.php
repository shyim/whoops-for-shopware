<?php
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class Shopware_Plugins_Core_WhoopsForShopware_Bootstrap extends Shopware_Components_Plugin_Bootstrap
{
    public function getLabel()
    {
        return 'Whoops for Shopware';
    }

    public function getInfo()
    {
        return array(
            'version' => '1.0.0',
            'autor' => 'Shyim',
            'label' => $this->getLabel(),
            'support' => 'https://github.com/Shyim/whoops-for-shopware',
            'link' => 'https://github.com/Shyim/whoops-for-shopware'
        );
    }

    public function install()
    {
        $this->subscribeEvent(
            'Enlight_Controller_Front_StartDispatch',
            'onShopwareStartDispatch'
        );
        return true;
    }

    public function uninstall()
    {
        return true;
    }

    public function onShopwareStartDispatch(Enlight_Event_EventArgs $args)
    {
        /** @var \Enlight_Controller_Front $subject */
        $subject = $args->get('subject');

        if ($subject->getParam('noErrorHandler')) {
            $requestUri = $subject->Request()->getRequestUri();
            spl_autoload_register(array($this, 'loader'));

            $whoops = new Run;

            if ($subject->Request()->isXmlHttpRequest() || strstr($requestUri, '/backend') || strstr($requestUri, '/ajax')) {
                $whoops->pushHandler(new JsonResponseHandler());
            } else {
                $whoops->pushHandler(new PrettyPageHandler);
            }

            $whoops->register();
            restore_error_handler();
        }
    }

    public function loader($className)
    {
        if (!strstr($className, 'Whoops')) {
            return;
        }
        $className = str_replace('\\', '/', $className);
        require_once($this->Path() . '/vendor/filp/whoops/src/' . $className . '.php');
    }
}