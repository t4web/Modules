<?php
namespace Modules\UnitTest\ServiceLocator\Controller\Console;

use Modules\Module;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\Config;
use Zend\Mvc\Controller\PluginManager as ControllerPluginManager;
use Zend\EventManager\EventManager;
use Zend\EventManager\SharedEventManager;

class InstallControllerTest extends \PHPUnit_Framework_TestCase
{
    private $serviceManager;

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
        $moduleServiceMock = $this->getMockBuilder('Modules\Module\Service')
            ->disableOriginalConstructor()
            ->getMock();

        $this->serviceManager->setService('Modules\Module\Service', $moduleServiceMock);

        $this->assertTrue($this->controllerManager->has('Modules\Controller\Console\Install'));

        $controller = $this->controllerManager->get('Modules\Controller\Console\Install');

        $this->assertInstanceOf('Modules\Controller\Console\InstallController', $controller);
        $this->assertAttributeSame($moduleServiceMock, 'moduleService', $controller);
        $this->assertAttributeInstanceOf('ComposerLockParser\ComposerInfo', 'composerInfo', $controller);
    }

}