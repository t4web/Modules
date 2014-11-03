<?php
namespace Modules\FunctionalTest\Module;

use Modules\FunctionalTester;
use Modules\Module\DbRepository;
use Zend\ServiceManager\ServiceManager;

class RepositoryCest
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
        /** @var DbRepository $repository */
        $repository = $this->serviceManager->get('Modules\Module\DbRepository');

        $module = $repository->find([
            'name = ?' => 't4web/modules'
        ]);

        \PHPUnit_Framework_Assert::assertInstanceOf('Modules\Module\Module', $module);
        \PHPUnit_Framework_Assert::assertEquals('t4web/modules', $module->getName());
    }
}