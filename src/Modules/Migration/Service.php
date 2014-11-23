<?php

namespace Modules\Migration;

use Zend\EventManager\EventManagerInterface;
use Modules\Module\Module;

class Service {

    const MIGRATION_SUCCESS_COMPLETE = 'migration-success-complete';

    /**
     * @var Config
     */
    private $configService;

    /**
     * @var Mapper
     */
    private $mapper;

    /**
     * @var EventManagerInterface
     */
    private $events;

    public function __construct(Config $configService, Mapper $mapper, EventManagerInterface $events)
    {
        $this->configService = $configService;
        $this->mapper = $mapper;
        $this->events = $events;
    }

    /**
     * @return EventCollection
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param Module $module
     * @param string $fromVersion
     */
    public function run(Module $module, $fromVersion)
    {
        if (!$this->configService->exists($module->getName())) {
            throw new \RuntimeException('Migrations for module ' . $module->getName() . ' not found ');
        }

        $migrationsCollection = $this->generateMigrations($module);

        /** @var Migration $migration */
        $migration = $migrationsCollection->getFrom($fromVersion);

        while ($migration) {

            if ($migration->isCurrent()) {
                $this->getEvents()->trigger(self::MIGRATION_SUCCESS_COMPLETE, $migration, ['module' => $module]);
                break;
            }

            $migration->run();

            $this->getEvents()->trigger(self::MIGRATION_SUCCESS_COMPLETE, $migration, ['module' => $module]);

            $migration = $migrationsCollection->getNext($migration);
        }
    }

    /**
     * @param Module $module
     *
     * @return MigrationsCollection
     */
    private function generateMigrations(Module $module)
    {
        $migrationConfig = $this->configService->load($module->getName());

        return $this->mapper->fromConfigRows($migrationConfig);
    }

} 