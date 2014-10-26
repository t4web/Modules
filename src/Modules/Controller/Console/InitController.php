<?php

namespace Modules\Controller\Console;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Db\Adapter\Adapter;

class InitController extends AbstractActionController {

    /**
     * @var Adapter
     */
    private $dbAdapter;

    public function __construct(Adapter $dbAdapter){
        $this->dbAdapter = $dbAdapter;
    }

    public function runAction() {

        $databaseName = $this->getDatabaseName();

        if (empty($databaseName)) {
            return "Db access not configured" . PHP_EOL;
        }

        $result = $this->dbAdapter->query(
            "SELECT *
            FROM information_schema.tables
            WHERE table_schema = '$databaseName'
                AND table_name = 't4_modules'
            LIMIT 1;",
            Adapter::QUERY_MODE_EXECUTE
        );

        if ($result->count() > 0) {
            return "Already initialized" . PHP_EOL;
        }

        $this->dbAdapter->query(
            "CREATE TABLE IF NOT EXISTS `t4_modules` (
              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
              `version` varchar(20) COLLATE utf8_unicode_ci DEFAULT '',
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;",
            Adapter::QUERY_MODE_EXECUTE
        );

        $this->dbAdapter->query(
            "INSERT INTO `t4_modules` (`name`, `version`)
             VALUES ('Modules', '0.2.1');",
            Adapter::QUERY_MODE_EXECUTE
        );

        return "Success completed" . PHP_EOL;
    }

    private function getDatabaseName()
    {
        $metadata = new \Zend\Db\Metadata\Metadata($this->dbAdapter);

        $shemas = $metadata->getSchemas();

        if (!array_key_exists(0, $shemas)) {
            return;
        }

        return $shemas[0];
    }
}