<?php

namespace Modules\Controller\Console;

use Zend\Mvc\Controller\AbstractActionController;
use \Zend\ModuleManager\ModuleManagerInterface;
//use League\CLImate\CLImate;

class ListController extends AbstractActionController {

    /**
     * @var ModuleManagerInterface
     */
    private $moduleManager;

    public function __construct(ModuleManagerInterface $moduleManager){
        $this->moduleManager = $moduleManager;
    }
    
    public function showAction() {



        return;
    }
}
