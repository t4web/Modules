<?php

namespace Modules\Controller\Console;

use Zend\Mvc\Controller\AbstractActionController;
use Modules\Module\Service;
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

    public function __construct(Service $moduleService, ComposerInfo $composerInfo){
        $this->moduleService = $moduleService;
        $this->composerInfo = $composerInfo;
    }

    public function runAction() {
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

        return "Installation $moduleName success completed" . PHP_EOL;
    }

}
