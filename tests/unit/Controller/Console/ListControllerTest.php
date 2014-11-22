<?php
namespace Modules\UnitTest\Controller\Console;

use Modules\Controller\Console\ListController;
use Modules\Module\ModulesCollection;
use ComposerLockParser\PackagesCollection;

class ListControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ListController
     */
    private $controller;

    private $composerInfo;
    private $moduleService;
    private $viewModelMock;
    private $rendererMock;

    protected function setUp()
    {
        $this->composerInfo = $this->getMockBuilder('\ComposerLockParser\ComposerInfo')
            ->disableOriginalConstructor()
            ->getMock();

        $this->moduleService = $this->getMockBuilder('\Modules\Module\Service')
            ->disableOriginalConstructor()
            ->getMock();

        $this->statusCalculator = $this->getMockBuilder('\Modules\Module\Service\StatusCalculator')
            ->disableOriginalConstructor()
            ->getMock();

        $this->viewModelMock = $this->getMock('\Modules\ViewModel\Console\ListViewModel');

        $this->rendererMock = $this->getMock('\Zend\View\Renderer\PhpRenderer');

        $this->controller = new ListController(
            $this->composerInfo,
            $this->moduleService,
            $this->statusCalculator,
            $this->viewModelMock,
            $this->rendererMock
        );
    }

    public function testShowAction()
    {
        $this->composerInfo->expects($this->once())
            ->method('parse');

        $this->composerInfo->expects($this->once())
            ->method('getPackages')
            ->will($this->returnValue(new PackagesCollection()));

        $this->moduleService->expects($this->once())
            ->method('getAll')
            ->will($this->returnValue(new ModulesCollection()));

        $this->controller->showAction();
    }

}