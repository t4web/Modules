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
     * @param array $modules
     */
    public function setLoadedModules(array $modules)
    {
        $this->modules = $modules;
    }

    /**
     * @return array
     */
    public function getLoadedModules()
    {
        return $this->modules;
    }

}