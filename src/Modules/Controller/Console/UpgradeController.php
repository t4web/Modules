<?php

namespace Modules\Controller\Console;

use Zend\Mvc\Controller\AbstractActionController;
use ComposerLockParser\ComposerInfo;
use Modules\Module\Service as ModuleService;
use Modules\Migration\Service as MigrationService;
use Modules\Module\Service\StatusCalculator;

class UpgradeController extends AbstractActionController {

    /**
     * @var Service
     */
    private $moduleService;

    /**
     * @var ComposerInfo
     */
    private $composerInfo;

    /**
     * @var MigrationService
     */
    private $migrationService;

    /**
     * @var StatusCalculator
     */
    private $statusCalculator;

    public function __construct(
        ModuleService $moduleService,
        ComposerInfo $composerInfo,
        MigrationService $migrationService,
        StatusCalculator $statusCalculator)
    {
        $this->moduleService = $moduleService;
        $this->composerInfo = $composerInfo;
        $this->migrationService = $migrationService;
        $this->statusCalculator = $statusCalculator;
    }

    public function runAction()
    {
        $moduleName = $this->params('moduleName');

        $this->composerInfo->parse();

        $modules = $this->moduleService->getAll();
        $packages = $this->composerInfo->getPackages();

        $this->statusCalculator->calculate($modules, $packages);

        if (!$modules->hasByName($moduleName)) {
            return "Module $moduleName not exists" . PHP_EOL;
        }

        $module = $modules->getByName($moduleName);

        if (!$module->isNeedUpgrade()) {
            return "Module $moduleName not need upgrade" . PHP_EOL;
        }

        $this->migrationService->getEvents()->attach($this->moduleService);

        $this->migrationService->run($module, $module->getVersion());

        return "Upgrade $moduleName success completed" . PHP_EOL;
    }

}
