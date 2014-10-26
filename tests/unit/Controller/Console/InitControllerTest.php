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
    private $metadataMock;

    protected function setUp()
    {
        $this->dbAdapterMock = $this->getMockBuilder('Zend\Db\Adapter\Adapter')
            ->disableOriginalConstructor()
            ->getMock();

        $this->metadataMock = $this->getMockBuilder('Zend\Db\Metadata\Metadata')
            ->disableOriginalConstructor()
            ->getMock();

        $this->controller = new InitController($this->dbAdapterMock, $this->metadataMock);
    }

    public function testShowActionAlreadyInitialized()
    {
        $resultSet = $this->getMockBuilder('Zend\Db\ResultSet\AbstractResultSet')
            ->disableOriginalConstructor()
            ->getMock();

        $resultSet->expects($this->any(0))
            ->method('count')
            ->will($this->returnValue(1));

        $this->dbAdapterMock->expects($this->at(0))
            ->method('query')
            ->will($this->returnValue($resultSet));

        $this->metadataMock->expects($this->once())
            ->method('getSchemas')
            ->will($this->returnValue([0 => 'databasename']));

        $result = $this->controller->runAction();

        $this->assertEquals("Already initialized" . PHP_EOL, $result);
    }

    public function testShowActionSuccess()
    {
        $resultSet = $this->getMockBuilder('Zend\Db\ResultSet\AbstractResultSet')
            ->disableOriginalConstructor()
            ->getMock();

        $resultSet->expects($this->any(0))
            ->method('count')
            ->will($this->returnValue(0));

        $this->dbAdapterMock->expects($this->at(0))
            ->method('query')
            ->will($this->returnValue($resultSet));

        $this->metadataMock->expects($this->once())
            ->method('getSchemas')
            ->will($this->returnValue([0 => 'databasename']));

        $result = $this->controller->runAction();

        $this->assertEquals("Success completed" . PHP_EOL, $result);
    }

}