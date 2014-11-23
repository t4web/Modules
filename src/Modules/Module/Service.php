<?php

namespace Modules\Module;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventInterface;
use Modules\Migration\Service as MigrationService;
use Modules\Migration\Migration;

class Service implements ListenerAggregateInterface
{

    /**
     * @var DbRepository
     */
    private $repository;

    private $listeners = [];

    public function __construct(DbRepository $repository)
    {
        $this->repository = $repository;
    }

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MigrationService::MIGRATION_SUCCESS_COMPLETE, array($this, 'runUpdate'));
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * @param string $name
     *
     * @return Module
     */
    public function getModuleByName($name)
    {
        $module = $this->repository->find([
            'name' => $name
        ]);

        return $module;
    }

    /**
     * @param Module $module
     */
    public function install(Module $module)
    {
        $this->repository->add($module);
    }

    /**
     * @param EventInterface $event
     */
    public function runUpdate(EventInterface $event)
    {
        /** @var Module $module */
        $module = $event->getParam('module');

        /** @var Migration $migration */
        $migration = $event->getTarget();

        $module->setUpgradedTo($migration->getVersion());

        $this->update($module);
    }

    /**
     * @param Module $module
     */
    public function update(Module $module)
    {
        $this->repository->update($module);
    }

    /**
     * @return ModulesCollection
     */
    public function getAll()
    {
        return $this->repository->findAll();
    }

}