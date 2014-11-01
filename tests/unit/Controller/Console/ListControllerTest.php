<?php
namespace Modules\UnitTest\Controller\Console;

use Modules\Controller\Console\ListController;
use ComposerLockParser\PackagesCollection;

class ListControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ListController
     */
    private $controller;

    private $moduleManagerMock;
    private $composerInfo;
    private $viewModelMock;
    private $rendererMock;

    protected function setUp()
    {
        $this->moduleManagerMock = $this->getMockBuilder('\Zend\ModuleManager\ModuleManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->composerInfo = $this->getMockBuilder('\ComposerLockParser\ComposerInfo')
            ->disableOriginalConstructor()
            ->getMock();

        $this->viewModelMock = $this->getMock('\Modules\ViewModel\Console\ListViewModel');

        $this->rendererMock = $this->getMock('\Zend\View\Renderer\PhpRenderer');

        $this->controller = new ListController(
            $this->moduleManagerMock,
            $this->composerInfo,
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

        $this->moduleManagerMock->expects($this->once())
            ->method('getLoadedModules')
            ->will(
                $this->returnValue(
                    array(
                        'Application'    => new \Application\Module(),
                        'Authentication' => new \Authentication\Module(),
                        'Modules'        => new \Modules\Module(),
                    )
                )
            );

        $this->controller->showAction();
    }

}