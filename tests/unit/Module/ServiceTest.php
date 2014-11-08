<?php
namespace Modules\UnitTest\Module;

use Modules\Module\Service;
use Codeception\Util\Stub;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Service
     */
    private $moduleService;

    protected function setUp()
    {
        $repositoryMock = $this->getMockBuilder('Modules\Module\DbRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $this->moduleService = new Service($repositoryMock);
    }

    public function testCalculationStatusWhenModuleNotExists()
    {
        $module = null;
        $package = Stub::make(
            'ComposerLockParser\Package',
            [
                'getName' => 'some/module',
                'getVersion' => '0.33.44'
            ]
        );

        $this->moduleService->calculateStatus(
            $module,
            $package
        );

        $this->assertEquals('some/module', $module->getName());
        $this->assertEquals('0.33.44', $module->getVersion());
        $this->assertTrue($module->isNeedInstallation());
    }
}