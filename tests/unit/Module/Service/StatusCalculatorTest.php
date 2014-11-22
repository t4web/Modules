<?php
namespace Modules\UnitTest\Module\Service;

use Modules\Module\Service\StatusCalculator;
use ComposerLockParser\PackagesCollection;
use Modules\Module\ModulesCollection;
use Codeception\Util\Stub;

class StatusCalculatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StatusCalculator
     */
    private $statusCalculator;

    public function testCalculate()
    {
        $localInstalledModule = Stub::make(
            'Modules\Module',
            [
                'getAutoloaderConfig' => [],
                'getConfig' => ['version' => '0.11.0']
            ]
        );

        $localInstalledModuleChanged = Stub::make(
            'Modules\Module',
            [
                'getAutoloaderConfig' => [],
                'getConfig' => ['version' => '0.1.1']
            ]
        );

        $composerInstalledModule = Stub::make('Modules\Module', ['getAutoloaderConfig' => []]);
        $composerInstalledModuleChanged = Stub::make('Modules\Module', ['getAutoloaderConfig' => []]);

        $loadedModules = [
            'LocalNotInstalledModule' => Stub::make('Modules\Module', ['getAutoloaderConfig' => []]),
            'LocalInstalledModule' => $localInstalledModule,
            'LocalInstalledModuleChanged' => $localInstalledModuleChanged,
            'ComposerNotInstalledModule' => Stub::make('Modules\Module', ['getAutoloaderConfig' => []]),
            'ComposerInstalledModule' => $composerInstalledModule,
            'ComposerInstalledModuleChanged' => $composerInstalledModuleChanged,
        ];

        $this->statusCalculator = new StatusCalculator($loadedModules);

        $modulesCollection = new ModulesCollection();
        $modulesCollection[] = Stub::make(
            'Modules\Module\Module',
            [
                'getName' => 'LocalInstalledModule',
                'getNamespace' => 'LocalInstalledModule',
                'getVersion' => '0.11.0',
            ]
        );
        $modulesCollection[] = Stub::make(
            'Modules\Module\Module',
            [
                'getName' => 'LocalInstalledModuleChanged',
                'getNamespace' => 'LocalInstalledModuleChanged',
                'getVersion' => '0.1.0',
            ]
        );
        $modulesCollection[] = Stub::make(
            'Modules\Module\Module',
            [
                'getName' => 'ComposerInstalledModule',
                'getNamespace' => 'ComposerInstalledModule',
                'getVersion' => '0.22.0',
            ]
        );
        $modulesCollection[] = Stub::make(
            'Modules\Module\Module',
            [
                'getName' => 'ComposerInstalledModuleChanged',
                'getNamespace' => 'ComposerInstalledModuleChanged',
                'getVersion' => '0.2.0',
            ]
        );
        $modulesCollection[] = Stub::make(
            'Modules\Module\Module',
            [
                'getName' => 'RemovedModule',
                'getNamespace' => 'RemovedModule'
            ]
        );

        $packagesCollection = new PackagesCollection();
        $packagesCollection[] = Stub::make(
            'ComposerLockParser\Package',
            [
                'getName' => 'ComposerInstalledModule',
                'getVersion' => '0.22.0',
                'getNamespace' => 'ComposerInstalledModule',
            ]
        );
        $packagesCollection[] = Stub::make(
            'ComposerLockParser\Package',
            [
                'getName' => 'ComposerInstalledModuleChanged',
                'getVersion' => '0.2.1',
                'getNamespace' => 'ComposerInstalledModuleChanged',
            ]
        );
        $packagesCollection[] = Stub::make(
            'ComposerLockParser\Package',
            [
                'getName' => 'ComposerNotInstalledModule',
                'getNamespace' => 'ComposerNotInstalledModule',
            ]
        );
        $packagesCollection[] = Stub::make(
            'ComposerLockParser\Package',
            [
                'getName' => 'OtherComposerModule',
                'getNamespace' => 'OtherComposerModule',
            ]
        );

        $this->statusCalculator->calculate($modulesCollection, $packagesCollection);

        $this->assertTrue($modulesCollection->getByName('LocalNotInstalledModule')->isNeedInstallation());
        $this->assertTrue($modulesCollection->getByName('LocalInstalledModule')->isActive());
        $this->assertTrue($modulesCollection->getByName('LocalInstalledModuleChanged')->isNeedUpgrade());
        $this->assertTrue($modulesCollection->getByName('RemovedModule')->isAbsent());
        $this->assertTrue($modulesCollection->getByName('ComposerInstalledModule')->isActive());
        $this->assertTrue($modulesCollection->getByName('ComposerInstalledModuleChanged')->isNeedUpgrade());
        $this->assertTrue($modulesCollection->getByName('ComposerNotInstalledModule')->isNeedInstallation());
        $this->assertFalse($modulesCollection->hasByName('OtherComposerModule'));
    }
}