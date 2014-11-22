<?php

namespace Modules\Module\Service;

use ComposerLockParser\PackagesCollection;
use Modules\Module\ModulesCollection;
use Modules\Module\Module;

class StatusCalculator {

    /**
     * @var array
     */
    private $loadedModules;

    public function __construct(array $loadedModules)
    {
        $this->loadedModules = $loadedModules;
    }

    public function calculate(
        ModulesCollection $modulesCollection,
        PackagesCollection $packagesCollection)
    {

        foreach($modulesCollection as $module) {
            if (!array_key_exists($module->getNamespace(), $this->loadedModules)) {
                $module->setAbsent();
            }
        }

        foreach($this->loadedModules as $loadedModuleName => $loadedModule) {
            $module = null;
            $package = null;

            if ($modulesCollection->hasByNamespace($loadedModuleName)) {
                $module = $modulesCollection->getByNamespace($loadedModuleName);
            }

            if ($packagesCollection->hasByNamespace($loadedModuleName)) {
                $package = $packagesCollection->getByNamespace($loadedModuleName);
            }

            if (is_null($module) && is_null($package)) {
                $module = new Module($loadedModuleName, $loadedModuleName, 'unknown');
                $module->setNeedInstallation();
                $modulesCollection[] = $module;
                continue;
            }

            if (is_null($module) && !is_null($package)) {
                $module = new Module($package->getName(), $package->getNamespace(), $package->getVersion());
                $module->setNeedInstallation();
                $modulesCollection[] = $module;
                continue;
            }

            if (!is_null($module) && is_null($package)) {
                $moduleConfig = $loadedModule->getConfig();

                if (array_key_exists('version', $moduleConfig)) {
                    if (version_compare($moduleConfig['version'], $module->getVersion()) == 1) {
                        $module->setNeedUpgrade();
                    } else {
                        $module->setActive();
                    }
                }

                continue;
            }

            if (!is_null($module) && !is_null($package)) {
                if (version_compare($package->getVersion(), $module->getVersion()) == 1) {
                    $module->setNeedUpgrade();
                } else {
                    $module->setActive();
                }
                continue;
            }
        }

    }
}