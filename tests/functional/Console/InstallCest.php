<?php
namespace Modules\FunctionalTest\Console;

use Zend\Mvc\Router\RouteMatch;
use Zend\Console\Request as ConsoleRequest;
use Modules\FunctionalTester;
use Modules\Controller\Console\InstallController;
use ComposerLockParser\ComposerInfo;
use ComposerLockParser\PackagesCollection;
use ComposerLockParser\Package;
use Codeception\Util\Stub;

class InstallCest
{
    protected $event;
    protected $routeMatch;
    protected $stdOutWriter;
    protected $application;

    public function _before(FunctionalTester $I)
    {
        $this->application = $I->getApplication();
        $this->event = $this->application->getMvcEvent();

        $this->routeMatch = new RouteMatch(
            array(
                'controller' => 'Modules\Controller\Console\Install',
            )
        );
        $this->event->setRouteMatch($this->routeMatch);
    }

    // tests
    public function tryInstallNotExistingModule(FunctionalTester $I)
    {
        $I->wantTo("Check install not existing module");

        $this->routeMatch->setParam('action', 'run');
        $this->routeMatch->setParam('moduleName', 'test/module');

        $controller = new InstallController(
            $this->application->getServiceManager()->get('Modules\Module\Service'),
            new ComposerInfo('composer.lock'),
            $this->application->getServiceManager()->get('Modules\Migration\Service'),
            $this->application->getServiceManager()->get('Modules\Module\Service\StatusCalculator')
        );

        $controller->setEvent($this->event);
        $controller->setEventManager($this->application->getEventManager());
        $controller->setServiceLocator($this->application->getServiceManager());

        $result = $controller->dispatch(
            new ConsoleRequest(
                array(
                    0 => 'public/index.php',
                    1 => 'modules',
                    2 => 'install',
                )
            )
        );

        /** @var Zend\Http\PhpEnvironment\Response $response */
        $response = $controller->getResponse();

        \PHPUnit_Framework_Assert::assertEquals(200, $response->getStatusCode());
        \PHPUnit_Framework_Assert::assertEquals("Module test/module not exists" . PHP_EOL, $result);
    }

    public function tryInstallModule(FunctionalTester $I)
    {
        $I->wantTo("Check install module");

        $this->routeMatch->setParam('action', 'run');
        $this->routeMatch->setParam('moduleName', 'test/module');

        $this->mockModuleManager($this->application->getServiceManager());

        $composerInfoMock = $this->getComposerInfoStub();

        $controller = new InstallController(
            $this->application->getServiceManager()->get('Modules\Module\Service'),
            $composerInfoMock,
            Stub::make('Modules\Migration\Service', ['run' => true]),
            $this->application->getServiceManager()->get('Modules\Module\Service\StatusCalculator')
        );

        $controller->setEvent($this->event);
        $controller->setEventManager($this->application->getEventManager());
        $controller->setServiceLocator($this->application->getServiceManager());

        $result = $controller->dispatch(
            new ConsoleRequest(
                array(
                    0 => 'public/index.php',
                    1 => 'modules',
                    2 => 'install',
                )
            )
        );

        /** @var Zend\Http\PhpEnvironment\Response $response */
        $response = $controller->getResponse();

        \PHPUnit_Framework_Assert::assertEquals(200, $response->getStatusCode());
        \PHPUnit_Framework_Assert::assertEquals("Installation test/module success completed" . PHP_EOL, $result);

        /** @var Modules\Module\DbRepository $repository */
        $repository = $this->application->getServiceManager()->get('Modules\Module\DbRepository');

        $module = $repository->find(['name' => 'test/module']);

        \PHPUnit_Framework_Assert::assertInstanceOf('Modules\Module\Module', $module);
        \PHPUnit_Framework_Assert::assertEquals('test/module', $module->getName());
    }

    public function tryInstallInstalledModule(FunctionalTester $I)
    {
        $I->wantTo("Check install already installed module");

        $this->routeMatch->setParam('action', 'run');
        $this->routeMatch->setParam('moduleName', 'test/module');

        $composerInfoMock = $this->getComposerInfoStub();

        $controller = new InstallController(
            $this->application->getServiceManager()->get('Modules\Module\Service'),
            $composerInfoMock,
            Stub::make('Modules\Migration\Service', ['run' => true]),
            $this->application->getServiceManager()->get('Modules\Module\Service\StatusCalculator')
        );

        $controller->setEvent($this->event);
        $controller->setEventManager($this->application->getEventManager());
        $controller->setServiceLocator($this->application->getServiceManager());

        /** @var Modules\Module\DbRepository $repository */
        $repository = $this->application->getServiceManager()->get('Modules\Module\DbRepository');

        $module = Stub::make('Modules\Module\Module', ['getName' => 'test/module', 'getVersion' => '0.0.1']);
        $repository->add($module);

        $result = $controller->dispatch(
            new ConsoleRequest(
                array(
                    0 => 'public/index.php',
                    1 => 'modules',
                    2 => 'install',
                )
            )
        );

        /** @var Zend\Http\PhpEnvironment\Response $response */
        $response = $controller->getResponse();

        \PHPUnit_Framework_Assert::assertEquals(200, $response->getStatusCode());
        \PHPUnit_Framework_Assert::assertEquals("Module test/module not need installation" . PHP_EOL, $result);
    }

    private function mockModuleManager($serviceManager)
    {
        $serviceManager->setAllowOverride(true);

        $loadedModules = ['TestModule' => Stub::make('Modules\Module')];

        $moduleManagerMock = Stub::make('Zend\ModuleManager\ModuleManager', ['getLoadedModules' => $loadedModules]);

        $serviceManager->setService('ModuleManager', $moduleManagerMock);
    }

    private function getComposerInfoStub()
    {
        return Stub::make(
            'ComposerLockParser\ComposerInfo',
            [
                'parse'       => '',
                'getPackages' => new PackagesCollection(
                    [
                        0 => Package::factory(
                            [
                                'name'        => 'test/module',
                                'version'     => '0.11.22',
                                'source'      => ['source value'],
                                'dist'        => ['dist value'],
                                'require'     => ['require value'],
                                'requireDev'  => ['requireDev value'],
                                'type'        => 'type value',
                                'autoload'    => [
                                    "psr-4" => [
                                        "TestModule" => "src/"
                                    ]
                                ],
                                'license'     => ['license value'],
                                'authors'     => ['authors value'],
                                'description' => 'description value',
                                'keywords'    => ['keywords value'],
                                'time'        => '2014-10-13 22:29:58',
                            ]
                        )
                    ]
                ),
            ]
        );
    }

    public function _after(FunctionalTester $I)
    {
        $this->clearDB($I);
    }

    public function _failed(FunctionalTester $I, $fail)
    {
        $this->clearDB($I);
    }

    private function clearDB(FunctionalTester $I)
    {
        $application = $I->getApplication();

        /** @var Modules\Module\DbRepository $repository */
        $repository = $application->getServiceManager()->get('Modules\Module\DbRepository');

        $module = Stub::make('Modules\Module\Module', ['getName' => 'test/module']);

        $repository->remove($module);
    }

}