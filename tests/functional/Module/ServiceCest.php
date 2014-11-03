<?php
namespace Modules\FunctionalTest\Module;

use Modules\FunctionalTester;
use Zend\ServiceManager\ServiceManager;

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
}