<?php
namespace Modules;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Mvc\Controller\ControllerManager;
use Zend\Console\Adapter\AdapterInterface as ConsoleAdapterInterface;
use Zend\Db\Metadata\Metadata;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\ServiceManager;
use Zend\EventManager\EventManager;
use ComposerLockParser\ComposerInfo;
use Zend\View\Renderer\PhpRenderer;
use Modules\ViewModel\Console\ListViewModel;
use Modules\Migration\Service as MigrationService;
use Modules\Migration\Config;
use Modules\Migration\Mapper as MigrationMapper;
use Modules\Module\DbRepository;
use Modules\Module\Mapper as ModuleMapper;
use Modules\Module\Service as ModuleService;
use Modules\Module\Service\StatusCalculator;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ControllerProviderInterface,
                        ServiceProviderInterface, ConsoleUsageProviderInterface
{

    public function getConsoleUsage(ConsoleAdapterInterface $console)
    {
        return array(
            'modules init' => 'Initialize modules migrations',
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
        return array(
            'factories' => array(
                'Zend\Db\Metadata\Metadata' => function (ServiceManager $sl) {
                    return new Metadata($sl->get('Zend\Db\Adapter\Adapter'));
                },
            )
        );
    }

    public function getControllerConfig()
    {
        return array(
            'factories' => array(
                'Modules\Controller\Console\List' => function (ControllerManager $cm) {
                    $sl = $cm->getServiceLocator();

                    $renderer = new PhpRenderer();
                    $renderer->resolver()->setPaths([__DIR__ . '/view']);

                    return new Controller\Console\ListController(
                        new ComposerInfo('composer.lock'),
                        $sl->get('ModuleManager'),
                        new ListViewModel(),
                        $renderer
                    );
                },
                'Modules\Controller\Console\Init' => function (ControllerManager $cm) {
                    $sl = $cm->getServiceLocator();

                    return new Controller\Console\InitController(
                        $sl->get('Zend\Db\Adapter\Adapter'),
                        $sl->get('Zend\Db\Metadata\Metadata')
                    );
                },
            )
        );
    }
}
