<?php

namespace Modules\Controller\Console;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\ModuleManager\ModuleManagerInterface;
use League\CLImate\CLImate;
use Zend\Json\Json;
use Zend\Loader\AutoloaderFactory;
use ComposerLockParser\ComposerInfo;
use ComposerLockParser\Package;
use Zend\View\Model\ViewModel;

class ListController extends AbstractActionController
{

    /**
     * @var ModuleManagerInterface
     */
    private $moduleManager;

    /**
     * @var CLImate;
     */
    private $cli;

    /**
     * @var ComposerInfo
     */
    private $composerInfo;

    public function __construct(
        ModuleManagerInterface $moduleManager,
        CLImate $cli,
        ComposerInfo $composerInfo)
    {
        $this->moduleManager = $moduleManager;
        $this->cli = $cli;
        $this->composerInfo = $composerInfo;
    }

    public function showAction()
    {
        $this->composerInfo->parse();

        $this->cli->bold('Used packages:');

        $this->cli->backgroundLightCyan(
            '  <black>'
            . sprintf("%-16s", 'Version')
            . sprintf("%-20s", 'Namespace')
            . sprintf("%-40s", 'Name')
            . "</black>"
        );

        $packages = [];
        /** @var Package $package */
        foreach ($this->composerInfo->getPackages() as $package) {

            $namespace = $package->getNamespace();

            $this->cli->out(
                '  '
                . sprintf("%-16s", $package->getVersion())
                . sprintf("%-20s", $namespace)
                . "<green>"
                . sprintf("%-40s", $package->getName())
                . "</green>"
            );

            $packages[$namespace] = array(
                'name'    => $package->getName(),
                'version' => $package->getVersion(),
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

        foreach ($namespaces as $moduleName => $path) {

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
