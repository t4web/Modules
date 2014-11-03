<?php
namespace Modules\UnitTest\ServiceLocator\Module;

use Modules\Module;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;

class ServiceTest extends \PHPUnit_Framework_TestCase
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
        $dbRepositoryMock = $this->getMockBuilder('Modules\Module\DbRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $this->serviceManager->setService('Modules\Module\DbRepository', $dbRepositoryMock);

        $this->assertTrue($this->serviceManager->has('Modules\Module\Service'));

        $service = $this->serviceManager->get('Modules\Module\Service');

        $this->assertInstanceOf('Modules\Module\Service', $service);
        $this->assertAttributeSame($dbRepositoryMock, 'repository', $service);
    }

}