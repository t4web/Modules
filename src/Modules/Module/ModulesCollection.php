<?php

namespace Modules\Module;

use ArrayObject;

class ModulesCollection extends ArrayObject
{
    /**
     * @var array
     */
    private $indexedBy;

    /**
     * @param string $name
     *
     * @return Module
     */
    public function getByName($name)
    {
        if (!$this->hasByName($name)) {
            throw new \UnexpectedValueException("Module $name not exists");
        }

        return $this->getIndexedByName()[$name];
    }

    /**
     * @param string $namespace
     *
     * @return Module
     */
    public function getByNamespace($namespace)
    {
        if (!$this->hasByNamespace($namespace)) {
            throw new \UnexpectedValueException("Module $namespace not exists");
        }

        return $this->getIndexedByNamespace()[$namespace];
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasByName($name)
    {
        return array_key_exists($name, $this->getIndexedByName());
    }

    /**
     * @param string $namespace
     *
     * @return bool
     */
    public function hasByNamespace($namespace)
    {
        return array_key_exists($namespace, $this->getIndexedByNamespace());
    }

    public function offsetSet($index, $module)
    {
        if ($module instanceof Module) {
            $this->indexedBy['name'][$module->getName()] = $module;
            $this->indexedBy['namespace'][$module->getNamespace()] = $module;
        }

        return parent::offsetSet($index, $module);
    }

    /**
     * @return array
     */
    private function getIndexedByName()
    {
        if (!empty($this->indexedBy['name'])) {
            return $this->indexedBy['name'];
        }

        /** @var Module $module */
        foreach($this->getArrayCopy() as $module) {
            if (!($module instanceof Module)) {
                continue;
            }
            $this->indexedBy['name'][$module->getName()] = $module;
        }

        return $this->indexedBy['name'];
    }

    /**
     * @return array
     */
    private function getIndexedByNamespace()
    {
        if (!empty($this->indexedBy['namespace'])) {
            return $this->indexedBy['namespace'];
        }

        /** @var Module $module */
        foreach($this->getArrayCopy() as $module) {
            if (!($module instanceof Module)) {
                continue;
            }
            $this->indexedBy['namespace'][$module->getNamespace()] = $module;
        }

        return $this->indexedBy['namespace'];
    }
}