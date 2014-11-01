<?php

namespace Modules\ViewModel\Console;

use Zend\View\Model\ViewModel;
use Zend\Loader\AutoloaderFactory;
use ComposerLockParser\PackagesCollection;

class ListViewModel extends ViewModel {

    /**
     * @var PackagesCollection
     */
    private $packages;

    /**
     * @var array
     */
    private $loadedModules;

    /**
     * @return array
     */
    public function getLoadedModules()
    {
        return $this->collectNamespaces($this->loadedModules);
    }

    /**
     * @param array $loadedModules
     *
     * @return array
     */
    private function collectNamespaces(array $loadedModules)
    {
        $namespaces = [];

        foreach ($loadedModules as $module) {

            if (!method_exists($module, 'getAutoloaderConfig')) {
                continue;
            }
            $autoloaderConfig = $module->getAutoloaderConfig();

            if (!array_key_exists(AutoloaderFactory::STANDARD_AUTOLOADER, $autoloaderConfig)) {
                continue;
            }

            $namespaces += $autoloaderConfig[AutoloaderFactory::STANDARD_AUTOLOADER]['namespaces'];
        }

        return $namespaces;
    }

    /**
     * @param array $loadedModules
     */
    public function setLoadedModules(array $loadedModules)
    {
        $this->loadedModules = $loadedModules;
    }

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

} 