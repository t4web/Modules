<?php
namespace Modules\UnitTest\ServiceLocator\Controller\Console;

use Modules\Module;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\Config;
use Zend\Mvc\Controller\PluginManager as ControllerPluginManager;
use Zend\EventManager\EventManager;
use Zend\EventManager\SharedEventManager;

class ListControllerTest extends \PHPUnit_Framework_TestCase
{
    private $serviceManager;
    private $serviceManagerConfig;

    /**
     * @var ControllerManager
     */
    private $controllerManager;

    protected function setUp()
    {
        $module = new Module();

        $events = new EventManager();
        $sharedEvents = new SharedEventManager;
        $events->setSharedManager($sharedEvents);

        $plugins = new ControllerPluginManager();
        $this->serviceManager = new ServiceManager();
        $this->serviceManager->setService('Zend\ServiceManager\ServiceLocatorInterface', $this->serviceManager);
        $this->serviceManager->setService('EventManager', $events);
        $this->serviceManager->setService('SharedEventManager', $sharedEvents);
        $this->serviceManager->setService('ControllerPluginManager', $plugins);

        $this->controllerManager = new ControllerManager(new Config($module->getControllerConfig()));
        $this->controllerManager->setServiceLocator($this->serviceManager);
        $this->controllerManager->addPeeringServiceManager($this->serviceManager);
    }

    public function testCreation()
    {
        $moduleManagerMock = $this->getMockBuilder('\Zend\ModuleManager\ModuleManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->serviceManager->setService('ModuleManager', $moduleManagerMock);

        $this->assertTrue($this->controllerManager->has('Modules\Controller\Console\List'));

        $controller = $this->controllerManager->get('Modules\Controller\Console\List');

        $this->assertInstanceOf('Modules\Controller\Console\ListController', $controller);
        $this->assertAttributeSame($moduleManagerMock, 'moduleManager', $controller);
    }

}