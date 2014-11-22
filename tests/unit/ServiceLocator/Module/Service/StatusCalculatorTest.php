<?php
namespace Modules\UnitTest\ServiceLocator\Module;

use Modules\Module;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;

class StatusCalculatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceManager
     */
    private $serviceManager;

    protected function setUp()
    {
        $module = new Module();

        $this->serviceManager = new ServiceManager(new Config($module->getServiceConfig()));
        $this->serviceManager->setAllowOverride(true);
    }

    public function testCreation()
    {
        $moduleManagerMock = $this->getMockBuilder('Zend\ModuleManager\ModuleManager')
            ->disableOriginalConstructor()
            ->getMock();

        $loadedModules = [];

        $moduleManagerMock->expects($this->once())
                ->method('getLoadedModules')
                ->will($this->returnValue($loadedModules));

        $this->serviceManager->setService('ModuleManager', $moduleManagerMock);

        $this->assertTrue($this->serviceManager->has('Modules\Module\Service\StatusCalculator'));

        $service = $this->serviceManager->get('Modules\Module\Service\StatusCalculator');

        $this->assertInstanceOf('Modules\Module\Service\StatusCalculator', $service);
        $this->assertAttributeSame($loadedModules, 'loadedModules', $service);
    }

}