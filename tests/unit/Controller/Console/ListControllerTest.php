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
    private $moduleManager;
    private $viewModelMock;
    private $rendererMock;

    protected function setUp()
    {
        $this->composerInfo = $this->getMockBuilder('ComposerLockParser\ComposerInfo')
            ->disableOriginalConstructor()
            ->getMock();

        $this->moduleManager = $this->getMockBuilder('Zend\ModuleManager\ModuleManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->viewModelMock = $this->getMock('Modules\ViewModel\Console\ListViewModel');

        $this->rendererMock = $this->getMock('Zend\View\Renderer\PhpRenderer');

        $this->controller = new ListController(
            $this->composerInfo,
            $this->moduleManager,
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

        $this->moduleManager->expects($this->once())
            ->method('getLoadedModules')
            ->will($this->returnValue(array()));

        $this->controller->showAction();
    }

}