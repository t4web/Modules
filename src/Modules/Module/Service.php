<?php

namespace Modules\Module;

use ComposerLockParser\Package;

class Service {

    /**
     * @var DbRepository
     */
    private $repository;

    public function __construct(DbRepository $repository)
    {
        $this->repository = $repository;
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
     * @param Module  $module
     * @param Package $package
     */
    public function calculateStatus(Module &$module = null, Package $package)
    {
        if (is_null($module)) {
            $module = new Module($package->getName(), $package->getVersion());
            $module->setNeedInstallation();
        }
    }

    /**
     * @param Module $module
     */
    public function install(Module $module)
    {
        $this->repository->add($module);
    }

} 