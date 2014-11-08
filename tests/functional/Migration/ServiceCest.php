<?php
namespace Modules\FunctionalTest\Migration;

use Modules\FunctionalTester;
use Zend\ServiceManager\ServiceManager;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Metadata;
use Codeception\Util\Stub;

class ServiceCest
{
    /**
     * @var ServiceManager
     */
    private $serviceManager;

    public function _before(FunctionalTester $I)
    {
        $application = $I->getApplication();

        $this->serviceManager = $application->getServiceManager();
    }

    // tests
    public function tryRunAll(FunctionalTester $I)
    {
        $dbAdapter = $this->serviceManager->get('Zend\Db\Adapter\Adapter');

        $dbAdapter->query("DROP TABLE IF EXISTS test_migration, test_migration2", Adapter::QUERY_MODE_EXECUTE);

        $migrationService = $this->serviceManager->get('Modules\Migration\Service');

        $moduleMock = Stub::make('Modules\Module\Module', ['getName' => 't4web/modules/tests/_data/Assets/SomeModule']);

        $this->manualAutoloadClasses();

        $migrationService->run($moduleMock, 'unknown');



        $metadata = new Metadata($dbAdapter);
        $schemas = $metadata->getSchemas();
        $databaseName = $schemas[0];

        $result = $dbAdapter->query(
            "SELECT *
            FROM information_schema.tables
            WHERE table_schema = '$databaseName'
                AND table_name = 'test_migration'
                OR table_name = 'test_migration2'
            LIMIT 2;",
            Adapter::QUERY_MODE_EXECUTE
        );

        \PHPUnit_Framework_Assert::assertEquals(2, $result->count());

        $result = $dbAdapter->query(
            "SHOW COLUMNS FROM test_migration",
            Adapter::QUERY_MODE_EXECUTE
        );

        $fields = $result->toArray();

        \PHPUnit_Framework_Assert::assertEquals('id', $fields[0]['Field']);
        \PHPUnit_Framework_Assert::assertEquals('name', $fields[1]['Field']);
        \PHPUnit_Framework_Assert::assertEquals('type', $fields[2]['Field']);

        $dbAdapter->query("DROP TABLE IF EXISTS test_migration, test_migration2", Adapter::QUERY_MODE_EXECUTE);
    }

    public function tryRunFrom(FunctionalTester $I)
    {
        $dbAdapter = $this->serviceManager->get('Zend\Db\Adapter\Adapter');

        $dbAdapter->query("DROP TABLE IF EXISTS test_migration2", Adapter::QUERY_MODE_EXECUTE);

        $migrationService = $this->serviceManager->get('Modules\Migration\Service');

        $moduleMock = Stub::make('Modules\Module\Module', ['getName' => 't4web/modules/tests/_data/Assets/SomeModule']);

        $this->manualAutoloadClasses();

        $migrationService->run($moduleMock, '0.1.0');



        $metadata = new Metadata($dbAdapter);
        $schemas = $metadata->getSchemas();
        $databaseName = $schemas[0];

        $result = $dbAdapter->query(
            "SELECT *
            FROM information_schema.tables
            WHERE table_schema = '$databaseName'
                AND table_name = 'test_migration2'
            LIMIT 1;",
            Adapter::QUERY_MODE_EXECUTE
        );

        \PHPUnit_Framework_Assert::assertEquals(1, $result->count());

        $dbAdapter->query("DROP TABLE IF EXISTS test_migration2", Adapter::QUERY_MODE_EXECUTE);
    }

    private function manualAutoloadClasses()
    {
        include_once "vendor/t4web/modules/tests/_data/Assets/SomeModule/Migrations/Migration_0_0_1.php";
        include_once "vendor/t4web/modules/tests/_data/Assets/SomeModule/Migrations/Migration_0_0_2.php";
        include_once "vendor/t4web/modules/tests/_data/Assets/SomeModule/Migrations/Migration_0_1_0.php";
        include_once "vendor/t4web/modules/tests/_data/Assets/SomeModule/Migrations/Migration_0_1_3.php";
    }
}