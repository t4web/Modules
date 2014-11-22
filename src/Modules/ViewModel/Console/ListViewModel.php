<?php

namespace Modules\ViewModel\Console;

use Zend\View\Model\ViewModel;
use Zend\Loader\AutoloaderFactory;
use ComposerLockParser\PackagesCollection;
use Modules\Module\ModulesCollection;
use Modules\Module\Service as ModuleService;

class ListViewModel extends ViewModel {

    /**
     * @var PackagesCollection
     */
    private $packages;

    /**
     * @var ModulesCollection
     */
    private $modules;

    /**
     * @var ModuleService
     */
    private $moduleService;

    /**
     * @return PackagesCollection
     */
    public function getPackages()
    {
        return $this->packages;
    }

    /**
     * @param PackagesCollection $packages
     */
    public function setPackages(PackagesCollection $packages)
    {
        $this->packages = $packages;
    }

    /**
     * @param ModulesCollection $modules
     */
    public function setModules(ModulesCollection $modules)
    {
        $this->modules = $modules;
    }

    /**
     * @return ModulesCollection
     */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * @return ModuleService
     */
    public function getModuleService()
    {
        return $this->moduleService;
    }

    /**
     * @param ModuleService $moduleService
     */
    public function setModuleService($moduleService)
    {
        $this->moduleService = $moduleService;
    }

} 