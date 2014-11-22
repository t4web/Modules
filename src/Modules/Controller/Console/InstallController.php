<?php

namespace Modules\Controller\Console;

use Zend\Mvc\Controller\AbstractActionController;
use ComposerLockParser\ComposerInfo;
use Modules\Module\Service as ModuleService;
use Modules\Migration\Service as MigrationService;
use Modules\Module\Service\StatusCalculator;

class InstallController extends AbstractActionController {

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

        if (!$module->isNeedInstallation()) {
            return "Module $moduleName not need installation" . PHP_EOL;
        }

        $this->moduleService->install($module);

        $this->migrationService->run($module, 'unknown');

        return "Installation $moduleName success completed" . PHP_EOL;
    }

}
