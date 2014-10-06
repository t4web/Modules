<?php

namespace Modules\Controller\Console;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\ModuleManager\ModuleManagerInterface;
use League\CLImate\CLImate;
use Zend\Json\Json;
use Zend\Loader\AutoloaderFactory;

class ListController extends AbstractActionController {

    /**
     * @var ModuleManagerInterface
     */
    private $moduleManager;

    /**
     * @var CLImate;
     */
    private $cli;

    public function __construct(ModuleManagerInterface $moduleManager, CLImate $cli){
        $this->moduleManager = $moduleManager;
        $this->cli = $cli;
    }
    
    public function showAction() {

        if (!file_exists('composer.lock')) {
            return "You not use Composer? File composer.lock in your project folder not found.";
        }

        $composerLockFile = file_get_contents('composer.lock');

        $packagesInfo = Json::decode($composerLockFile, Json::TYPE_ARRAY);

        $this->cli->bold('Used packages:');

        $this->cli->backgroundLightCyan(
            '  <black>'
            . sprintf("%-16s", 'Version')
            . sprintf("%-20s", 'Namespace')
            . sprintf("%-40s", 'Name')
            . "</black>"
        );

        $packages = [];
        foreach ($packagesInfo['packages'] as $package) {
            $namespace = $this->getNamespace($package);

            $this->cli->out(
                '  '
                . sprintf("%-16s", $package['version'])
                . sprintf("%-20s", $namespace)
                . "<green>"
                . sprintf("%-40s", $package['name'])
                . "</green>"
            );

            $packages[$namespace] = array(
                'name' => $package['name'],
                'version' => $package['version'],
            );
        }

        $this->cli->br();
        $this->cli->bold('Used modules:');
        $this->cli->backgroundLightCyan(
            '  <black>'
            . sprintf("%-16s", 'Version')
            . sprintf("%-40s", 'Name')
            . "</black>"
        );

        $loadedModules = $this->moduleManager->getLoadedModules();

        $namespaces = $this->collectNamespaces($loadedModules);

        foreach ($namespaces as $moduleName=>$path) {

            $version = 'unknown';
            if (isset($packages[$moduleName])) {
                $version = $packages[$moduleName]['version'];
            }

            $this->cli->out(
                '  '
                . sprintf("%-16s", $version)
                . "<green>"
                . sprintf("%-40s", $moduleName)
                . "</green>"
            );

        }

        return '';
    }

    private function getNamespace(array $package)
    {
        $autoload = array();

        if (isset($package['autoload']['psr-0'])) {
            $autoload = $package['autoload']['psr-0'];
        } elseif (isset($package['autoload']['psr-4'])) {
            $autoload = $package['autoload']['psr-4'];
        }

        return trim(key($autoload), '\\');
    }

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
}
