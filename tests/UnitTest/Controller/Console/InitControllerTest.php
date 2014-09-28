<?php
namespace Modules\UnitTest\Controller\Console;

use Modules\Controller\Console\InitController;

class InitControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var InitController
     */
    private $controller;

    private $dbAdapterMock;

    protected function setUp()
    {
        $this->dbAdapterMock = $this->getMockBuilder('Zend\Db\Adapter\Adapter')
            ->disableOriginalConstructor()
            ->getMock();

        $this->controller = new InitController($this->dbAdapterMock);
    }

    public function testShowAction()
    {
        $this->dbAdapterMock->expects($this->any())
            ->method('query');

        $result = $this->controller->runAction();

        $this->assertEquals("Success completed" . PHP_EOL, $result);
    }

}