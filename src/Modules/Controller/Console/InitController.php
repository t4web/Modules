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
            VALUES  ('t4web/modules', '0.9.1');",
            Adapter::QUERY_MODE_EXECUTE
        );

        return "Success completed" . PHP_EOL;
    }
}
