<?php
namespace Modules\Migration;

use Zend\ServiceManager\ServiceManager;

class Mapper {

    /**
     * @var ServiceManager
     */
    private $serviceManager;

    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @param array $migrationConfig
     *
     * @return MigrationsCollection
     */
    public function fromConfigRows(array $migrationConfig) {
        $migrationsCollection = new MigrationsCollection();

        foreach ($migrationConfig as $version => $entry) {
            $entry['version'] = $version;
            $entry['serviceManager'] = $this->serviceManager;
            $migrationsCollection[$version] = Migration::factory($entry);
        }

        return $migrationsCollection;
    }

} 