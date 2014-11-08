<?php

namespace Modules\Migration;

use Modules\Module\Module;

class Service {

    /**
     * @var Config
     */
    private $configService;

    /**
     * @var Mapper
     */
    private $mapper;

    public function __construct(Config $configService, Mapper $mapper)
    {
        $this->configService = $configService;
        $this->mapper = $mapper;
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
            $migration->run();

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