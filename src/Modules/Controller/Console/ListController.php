<?php

namespace Modules\Controller\Console;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Renderer\PhpRenderer;
use Zend\ModuleManager\ModuleManager;
use ComposerLockParser\ComposerInfo;
use Modules\ViewModel\Console\ListViewModel;
use Modules\Module\Service as ModuleService;

class ListController extends AbstractActionController
{

    /**
     * @var ComposerInfo
     */
    private $composerInfo;

    /**
     * @var ModuleManager
     */
    private $moduleManager;

    /**
     * @var ViewModel
     */
    private $viewModel;

    /**
     * @var PhpRenderer
     */
    private $renderer;

    public function __construct(
        ComposerInfo $composerInfo,
        ModuleManager $moduleManager,
        ListViewModel $viewModel,
        PhpRenderer $renderer)
    {
        $this->composerInfo = $composerInfo;
        $this->moduleManager = $moduleManager;
        $this->viewModel = $viewModel;
        $this->renderer = $renderer;
    }

    public function showAction()
    {
        $this->composerInfo->parse();

        $loadedModules = $this->moduleManager->getLoadedModules();
        $packages = $this->composerInfo->getPackages();

        $this->viewModel->setPackages($packages);
        $this->viewModel->setLoadedModules($loadedModules);

        $this->viewModel->setTemplate('list-show');

        return $this->renderer->render($this->viewModel);
    }
}
