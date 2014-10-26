<?php
namespace Modules\UnitTest\ServiceLocator\Controller\Console;

use Modules\Module;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\Config;
use Zend\Mvc\Controller\PluginManager as ControllerPluginManager;
use Zend\EventManager\EventManager;
use Zend\EventManager\SharedEventManager;

class InitControllerTest extends \PHPUnit_Framework_TestCase
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
        $dbAdapterMock = $this->getMockBuilder('Zend\Db\Adapter\Adapter')
            ->disableOriginalConstructor()
            ->getMock();

        $dbMetadata = $this->getMockBuilder('Zend\Db\Metadata\Metadata')
            ->disableOriginalConstructor()
            ->getMock();

        $this->serviceManager->setService('Zend\Db\Adapter\Adapter', $dbAdapterMock);
        $this->serviceManager->setService('Zend\Db\Metadata\Metadata', $dbMetadata);

        $this->assertTrue($this->controllerManager->has('Modules\Controller\Console\Init'));

        $controller = $this->controllerManager->get('Modules\Controller\Console\Init');

        $this->assertInstanceOf('Modules\Controller\Console\InitController', $controller);
        $this->assertAttributeSame($dbAdapterMock, 'dbAdapter', $controller);
    }

}