<?php
namespace Modules\UnitTest\Migration;

use Modules\Migration\Service;
use Codeception\Util\Stub;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Service
     */
    private $migrationService;

    private $configMock;
    private $mapperMock;

    protected function setUp()
    {
        $this->configMock = $this->getMockBuilder('Modules\Migration\Config')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mapperMock = $this->getMockBuilder('Modules\Migration\Mapper')
            ->disableOriginalConstructor()
            ->getMock();

        $this->migrationService = new Service($this->configMock, $this->mapperMock);
    }

    public function testRunMigrations()
    {
        $this->configMock->expects($this->once())
            ->method('exists')
            ->will($this->returnValue(true));

        $this->configMock->expects($this->once())
            ->method('load')
            ->will($this->returnValue([]));


        $migration1Mock = Stub::make(
            'Modules\Migration\Migration',
            ['run' => true]
        );

        $migration2Mock = Stub::make(
            'Modules\Migration\Migration',
            ['run' => true]
        );

        $migrationsCollectionMock = Stub::make(
            'Modules\Migration\MigrationsCollection',
            [
                'getFrom' => $migration1Mock,
                'getNext' => Stub::consecutive($migration2Mock, false)
            ]
        );

        $this->mapperMock->expects($this->once())
            ->method('fromConfigRows')
            ->will($this->returnValue($migrationsCollectionMock));

        $moduleMock = Stub::make(
            'Modules\Module\Module',
            ['getName' => 'some/module']
        );

        $this->migrationService->run($moduleMock, 'unknown');
    }
}