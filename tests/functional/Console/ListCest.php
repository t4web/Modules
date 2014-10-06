<?php
namespace Modules\Console;

use Modules\FunctionalTester;
use Zend\Mvc\Router\RouteMatch;
use Modules\Controller\Console\ListController;
use Zend\Console\Request as ConsoleRequest;
use League\CLImate\CLImate;

class ListCest
{
    protected $event;
    protected $routeMatch;

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

        $this->controller = new ListController(
            $application->getServiceManager()->get('ModuleManager'),
            new CLImate()
        );
        $this->controller->setEvent($this->event);
        $this->controller->setEventManager($application->getEventManager());
        $this->controller->setServiceLocator($application->getServiceManager());
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function tryList(FunctionalTester $I)
    {
        $I->wantTo("Check module list output");

        $this->routeMatch->setParam('action', 'show');

        ob_start();

        $result = $this->controller->dispatch(
            new ConsoleRequest(
                array(
                    0 => 'public/index.php',
                    1 => 'modules',
                    2 => 'list',
                )
            )
        );
        $output = ob_get_flush();

        $response = $this->controller->getResponse();

        \PHPUnit_Framework_Assert::assertEquals(200, $response->getStatusCode());
        \PHPUnit_Framework_Assert::assertEquals('', $result);
        \PHPUnit_Framework_Assert::assertContains('zendframework/zendframework', $output);
        \PHPUnit_Framework_Assert::assertContains('t4web/modules', $output);
    }
}