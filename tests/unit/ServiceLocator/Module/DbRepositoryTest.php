<?php
namespace Modules\UnitTest\ServiceLocator\Module;

use Modules\Module;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;

class DbRepositoryTest extends \PHPUnit_Framework_TestCase
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
        $tableGatewayMock = $this->getMockBuilder('Zend\Db\TableGateway\TableGatewayInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->serviceManager->setService('Modules\Module\TableGateway', $tableGatewayMock);

        $this->assertTrue($this->serviceManager->has('Modules\Module\DbRepository'));

        $repository = $this->serviceManager->get('Modules\Module\DbRepository');

        $this->assertInstanceOf('Modules\Module\DbRepository', $repository);
        $this->assertAttributeSame($tableGatewayMock, 'tableGateway', $repository);
        $this->assertAttributeInstanceOf('Modules\Module\Mapper', 'mapper', $repository);
    }

}