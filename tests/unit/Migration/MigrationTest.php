<?php
namespace Modules\UnitTest\Migration;

use Modules\Migration\Migration;
use Codeception\Util\Stub;

class MigrationTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $migrationRaw = [
            'version' => '0.1.0',
            'run' => 'runClassName3',
            'next' => '0.1.2',
            'serviceManager' => Stub::make('Zend\ServiceManager\ServiceManager')
        ];

        $migration = Migration::factory($migrationRaw);

        $this->assertInstanceOf('Modules\Migration\Migration', $migration);
        $this->assertEquals($migrationRaw['version'], $migration->getVersion());
        $this->assertEquals($migrationRaw['run'], $migration->getRun());
        $this->assertEquals($migrationRaw['next'], $migration->getNext());
    }

}