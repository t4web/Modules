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
     * @param Module $module
     */
    public function install(Module $module)
    {
        $this->repository->add($module);
    }

    /**
     * @return ModulesCollection
     */
    public function getAll()
    {
        return $this->repository->findAll();
    }

}