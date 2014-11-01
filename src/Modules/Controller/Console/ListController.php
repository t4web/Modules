<?php

namespace Modules\Controller\Console;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\View\Renderer\PhpRenderer;
use ComposerLockParser\ComposerInfo;
use ComposerLockParser\Package;
use Modules\ViewModel\Console\ListViewModel;

class ListController extends AbstractActionController
{

    /**
     * @var ModuleManagerInterface
     */
    private $moduleManager;

    /**
     * @var ComposerInfo
     */
    private $composerInfo;

    /**
     * @var ViewModel
     */
    private $viewModel;

    /**
     * @var PhpRenderer
     */
    private $renderer;

    public function __construct(
        ModuleManagerInterface $moduleManager,
        ComposerInfo $composerInfo,
        ListViewModel $viewModel,
        PhpRenderer $renderer)
    {
        $this->moduleManager = $moduleManager;
        $this->composerInfo = $composerInfo;
        $this->viewModel = $viewModel;
        $this->renderer = $renderer;
    }

    public function showAction()
    {
        $this->composerInfo->parse();

        $this->viewModel->setPackages($this->composerInfo->getPackages());
        $this->viewModel->setLoadedModules($this->moduleManager->getLoadedModules());

        $this->viewModel->setTemplate('list-show');

        return $this->renderer->render($this->viewModel);
    }
}
