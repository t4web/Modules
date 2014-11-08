<?php

namespace Modules\Migration;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceManager;

class Migration {

    /**
     * @var string
     */
    private $version;

    /**
     * @var string
     */
    private $run;

    /**
     * @var string
     */
    private $next;

    /**
     * @var ServiceManager
     */
    private $serviceManager;

    private function __construct($version, $run, $next, ServiceManager $serviceManager)
    {
        $this->version = $version;
        $this->run = $run;
        $this->next = $next;
        $this->serviceManager = $serviceManager;
    }

    public static function factory(array $migrationEntry)
    {
        return new self(
            $migrationEntry['version'],
            $migrationEntry['run'],
            $migrationEntry['next'],
            $migrationEntry['serviceManager']
        );
    }

    /**
     * @return string
     */
    public function getNext()
    {
        return $this->next;
    }

    /**
     * @return string
     */
    public function getRun()
    {
        return $this->run;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    public function run()
    {
        if (!class_exists($this->run)) {
            throw new \RuntimeException('Migration ' . $this->run . ' not exists');
        }

        $script = new $this->run;

        if (!is_callable($script)) {
            throw new \RuntimeException('Migration ' . $this->run . ' not callable');
        }

        if ($script instanceof ServiceLocatorAwareInterface) {
            $script->setServiceLocator($this->serviceManager);
        }

        $script();
    }
}