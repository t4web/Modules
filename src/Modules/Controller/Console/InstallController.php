<?php

namespace Modules\Controller\Console;

use Zend\Mvc\Controller\AbstractActionController;
use Modules\Module\Service as ModuleService;
use Modules\Migration\Service as MigrationService;
use ComposerLockParser\ComposerInfo;

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

    public function __construct(
        ModuleService $moduleService,
        ComposerInfo $composerInfo,
        MigrationService $migrationService)
    {
        $this->moduleService = $moduleService;
        $this->composerInfo = $composerInfo;
        $this->migrationService = $migrationService;
    }

    public function runAction()
    {
        $moduleName = $this->params('moduleName');

        $module = $this->moduleService->getModuleByName($moduleName);

        $this->composerInfo->parse();

        $packagesCollection = $this->composerInfo->getPackages();

        if (!$packagesCollection->hasPackage($moduleName)) {
            return "Module $moduleName not exists" . PHP_EOL;
        }

        $this->moduleService->calculateStatus(
            $module,
            $packagesCollection->getByName($moduleName)
        );

        if (!$module->isNeedInstallation()) {
            return "Module $moduleName not need installation" . PHP_EOL;
        }

        $this->moduleService->install($module);

        $this->migrationService->run($module, 'unknown');

        return "Installation $moduleName success completed" . PHP_EOL;
    }

}
