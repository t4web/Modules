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

    private $cliMateMock;

    private $composerInfo;

    protected function setUp()
    {
        $this->moduleManagerMock = $this->getMockBuilder('\Zend\ModuleManager\ModuleManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->cliMateMock = $this->getMock('\League\CLImate\CLImate');
        $this->composerInfo = $this->getMockBuilder('\ComposerLockParser\ComposerInfo')
            ->disableOriginalConstructor()
            ->getMock();

        $this->controller = new ListController(
            $this->moduleManagerMock,
            $this->cliMateMock,
            $this->composerInfo
        );
    }

    public function testShowAction()
    {
        $this->composerInfo->expects($this->once())
            ->method('parse');

        $this->composerInfo->expects($this->once())
            ->method('getPackages')
            ->will($this->returnValue(new PackagesCollection()));

        $this->cliMateMock->expects($this->any())
            ->method('black');

        $this->cliMateMock->expects($this->any())
            ->method('backgroundLightCyan')
            ->will($this->returnSelf());

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