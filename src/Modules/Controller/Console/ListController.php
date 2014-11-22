<?php

namespace Modules\Controller\Console;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\View\Renderer\PhpRenderer;
use ComposerLockParser\ComposerInfo;
use Modules\ViewModel\Console\ListViewModel;
use Modules\Module\Service as ModuleService;
use Modules\Module\Service\StatusCalculator;

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
     * @var ModuleService
     */
    private $moduleService;

    /**
     * @var StatusCalculator
     */
    private $statusCalculator;

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
        ModuleService $moduleService,
        StatusCalculator $statusCalculator,
        ListViewModel $viewModel,
        PhpRenderer $renderer)
    {
        $this->composerInfo = $composerInfo;
        $this->moduleService = $moduleService;
        $this->statusCalculator = $statusCalculator;
        $this->viewModel = $viewModel;
        $this->renderer = $renderer;
    }

    public function showAction()
    {
        $this->composerInfo->parse();

        $modules = $this->moduleService->getAll();
        $packages = $this->composerInfo->getPackages();

        $this->statusCalculator->calculate($modules, $packages);

        $this->viewModel->setPackages($packages);
        $this->viewModel->setModules($modules);
        $this->viewModel->setModuleService($this->moduleService);

        $this->viewModel->setTemplate('list-show');

        return $this->renderer->render($this->viewModel);
    }
}
