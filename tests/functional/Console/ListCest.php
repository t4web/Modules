<?php
namespace Modules\FunctionalTest\Console;

use Zend\Mvc\Router\RouteMatch;
use Zend\Console\Request as ConsoleRequest;
use Zend\View\Renderer\PhpRenderer;
use Modules\FunctionalTester;
use Modules\Controller\Console\ListController;
use Modules\ViewModel\Console\ListViewModel;
use ComposerLockParser\ComposerInfo;

class ListCest
{
    protected $event;
    protected $routeMatch;
    protected $stdOutWriter;
    protected $controller;

    /**
     * @var Modules\ViewModel\Console\ListViewModel
     */
    protected $viewModel;

    public function _before(FunctionalTester $I)
    {
        $application = $I->getApplication();
        $this->event = $application->getMvcEvent();

        $this->routeMatch = new RouteMatch(
            array(
                'controller' => 'Modules\Controller\Console\List',
            )
        );
        $this->event->setRouteMatch($this->routeMatch);

        $renderer = new PhpRenderer();
        $renderer->resolver()->setPaths([dirname(dirname(dirname(__DIR__))) . '/view']);

        $this->viewModel = new ListViewModel();

        $this->controller = new ListController(
            new ComposerInfo('composer.lock'),
            $application->getServiceManager()->get('Modules\Module\Service'),
            $application->getServiceManager()->get('Modules\Module\Service\StatusCalculator'),
            $this->viewModel,
            $renderer
        );

        $this->controller->setEvent($this->event);
        $this->controller->setEventManager($application->getEventManager());
        $this->controller->setServiceLocator($application->getServiceManager());
    }

    // tests
    public function tryList(FunctionalTester $I)
    {
        $I->wantTo("Check module list output");

        $this->routeMatch->setParam('action', 'show');

        $result = $this->controller->dispatch(
            new ConsoleRequest(
                array(
                    0 => 'public/index.php',
                    1 => 'modules',
                    2 => 'list',
                )
            )
        );

        /** @var Zend\Http\PhpEnvironment\Response $response */
        $response = $this->controller->getResponse();

        \PHPUnit_Framework_Assert::assertEquals(200, $response->getStatusCode());
        \PHPUnit_Framework_Assert::assertEquals('', $result);

        \PHPUnit_Framework_Assert::assertInstanceOf('ComposerLockParser\PackagesCollection', $this->viewModel->getPackages());
        \PHPUnit_Framework_Assert::assertGreaterThan(0, $this->viewModel->getPackages()->count());
        \PHPUnit_Framework_Assert::assertInstanceOf('ComposerLockParser\Package', $this->viewModel->getPackages()->getByName('t4web/modules'));
    }
}