<?php
namespace Modules\FunctionalTest\Module;

use Modules\FunctionalTester;
use Zend\ServiceManager\ServiceManager;
use Zend\Db\Adapter\Adapter;

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
    public function tryFindByName(FunctionalTester $I)
    {
        $moduleService = $this->serviceManager->get('Modules\Module\Service');

        $module = $moduleService->getModuleByName('t4web/modules');

        \PHPUnit_Framework_Assert::assertInstanceOf('Modules\Module\Module', $module);
        \PHPUnit_Framework_Assert::assertEquals('t4web/modules', $module->getName());
    }

    public function tryGetAll(FunctionalTester $I)
    {
        $modulesFromDb = $this->getModulesFromDb();

        $moduleService = $this->serviceManager->get('Modules\Module\Service');

        $modules = $moduleService->getAll();

        \PHPUnit_Framework_Assert::assertEquals(count($modulesFromDb), $modules->count());

        foreach($modulesFromDb as $moduleFromDb) {
            \PHPUnit_Framework_Assert::assertTrue($modules->hasByName($moduleFromDb['name']));
        }

    }

    private function getModulesFromDb()
    {
        $dbAdapter = $this->serviceManager->get('Zend\Db\Adapter\Adapter');

        $result = $dbAdapter->query(
            "SELECT *
            FROM t4_modules",
            Adapter::QUERY_MODE_EXECUTE);

        return $result->toArray();
    }
}