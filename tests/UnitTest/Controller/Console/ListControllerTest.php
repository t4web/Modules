<?php
namespace Modules\UnitTest\Controller\Console;

use Modules\Controller\Console\ListController;

class ListControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ListController
     */
    private $controller;

    protected function setUp()
    {
        $moduleManagerMock = $this->getMockBuilder('\Zend\ModuleManager\ModuleManager')
            ->disableOriginalConstructor()
            ->getMock();

        $this->controller = new ListController($moduleManagerMock);
    }

    public function testShowAction()
    {
        $this->controller->showAction();
    }

}