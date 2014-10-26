<?php
namespace Modules\Console;

use Modules\FunctionalTester;
use Zend\Mvc\Router\RouteMatch;
use Modules\Controller\Console\ListController;
use Zend\Console\Request as ConsoleRequest;
use League\CLImate\CLImate;
use League\CLImate\Util\Output;
use ComposerLockParser\ComposerInfo;
use Codeception\Util\Stub;

class ListCest
{
    protected $event;
    protected $routeMatch;
    protected $stdOutWriter;

    public $content;

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

        $that = $this;

        $this->stdOutWriter = Stub::make(
            'League\CLImate\Util\Writer\StdOut',
            [
                'write' => function ($content) use ($that) { $that->content .= $content; }
            ]
        );

        $this->controller = new ListController(
            $application->getServiceManager()->get('ModuleManager'),
            new CLImate(new Output($this->stdOutWriter)),
            new ComposerInfo('composer.lock')
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

        $response = $this->controller->getResponse();

        \PHPUnit_Framework_Assert::assertEquals(200, $response->getStatusCode());
        \PHPUnit_Framework_Assert::assertEquals('', $result);
        \PHPUnit_Framework_Assert::assertContains('zendframework/zendframework', $this->content);
        \PHPUnit_Framework_Assert::assertContains('t4web/modules', $this->content);
    }
}