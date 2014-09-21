<?php
namespace Modules;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as ConsoleAdapterInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ControllerProviderInterface,
                        ServiceProviderInterface, ConsoleUsageProviderInterface
{

    public function getConsoleUsage(ConsoleAdapterInterface $console)
    {
        return array(
            'modules list' => 'List available modules',
        );
    }

    public function getConfig($env = null)
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array();
    }

    public function getControllerConfig()
    {
        return array(
            'invokables' => array(
                'Modules\Controller\Console\List' => 'Modules\Controller\Console\ListController',
            )
        );
    }
}
