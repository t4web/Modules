<?php

namespace Modules\Migration;

use ArrayObject;

class MigrationsCollection extends ArrayObject
{

    /**
     * @param string $fromVersion
     *
     * @return Migration
     */
    public function getFrom($fromVersion)
    {
        if (!array_key_exists((string)$fromVersion, $this->getArrayCopy())) {
            return;
        }

        return $this->getArrayCopy()[$fromVersion];
    }

    public function getNext(Migration $migration)
    {
        if (is_null($migration->getNext())) {
            return;
        }

        return $this->getFrom($migration->getNext());
    }
}