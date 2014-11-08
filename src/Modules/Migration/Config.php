<?php
namespace Modules\Migration;


class Config {

    public function exists($moduleName)
    {
        return file_exists("vendor/$moduleName/config/migrations.config.php");
    }

    public function load($moduleName)
    {
        return include "vendor/$moduleName/config/migrations.config.php";
    }

} 