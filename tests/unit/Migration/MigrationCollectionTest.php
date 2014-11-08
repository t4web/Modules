<?php
namespace Modules\UnitTest\Migration;

use Modules\Migration\MigrationsCollection;
use Modules\Migration\Migration;
use Codeception\Util\Stub;

class MigrationCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MigrationsCollection
     */
    private $migrationsCollection;

    protected function setUp()
    {
        $this->migrationsCollection = new MigrationsCollection();
    }

    public function testFillMigrationsCollection()
    {
        $migrations = [
            '0.0.1' => [
                'run' => 'runClassName1',
                'next' => '0.0.2'
            ],
            '0.0.2' => [
                'run' => 'runClassName2',
                'next' => '0.1.0'
            ],
            '0.1.0' => [
                'run' => 'runClassName3',
                'next' => '0.1.2'
            ],
        ];

        foreach ($migrations as $version => $entry) {
            $entry['version'] = $version;
            $entry['serviceManager'] = Stub::make('Zend\ServiceManager\ServiceManager');
            $this->migrationsCollection[$version] = Migration::factory($entry);
        }

        $this->assertArrayHasKey('0.0.1', $this->migrationsCollection->getArrayCopy());
        $this->assertArrayHasKey('0.0.2', $this->migrationsCollection->getArrayCopy());
        $this->assertArrayHasKey('0.1.0', $this->migrationsCollection->getArrayCopy());

        $m001 = $this->migrationsCollection->getFrom('0.0.1');

        $this->assertInstanceOf('Modules\Migration\Migration', $m001);
        $this->assertEquals('0.0.1', $m001->getVersion());

        $m002 = $this->migrationsCollection->getNext($m001);

        $this->assertInstanceOf('Modules\Migration\Migration', $m002);
        $this->assertEquals('0.0.2', $m002->getVersion());

        $m010 = $this->migrationsCollection->getNext($m002);

        $this->assertInstanceOf('Modules\Migration\Migration', $m010);
        $this->assertEquals('0.1.0', $m010->getVersion());
    }
}