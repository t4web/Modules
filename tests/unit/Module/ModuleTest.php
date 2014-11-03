<?php
namespace Modules\UnitTest\Module;

use Modules\Module\Module;

class ModuleTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $moduleRaw = [
            'id' => 1,
            'name' => 'Some/Name',
            'version' => '0.2.33',
        ];

        $module = new Module($moduleRaw['name'], $moduleRaw['version']);

        $this->assertAttributeSame($moduleRaw['name'], 'name', $module);
        $this->assertAttributeSame($moduleRaw['version'], 'version', $module);
        $this->assertEquals($moduleRaw['name'], $module->getName());
        $this->assertEquals($moduleRaw['version'], $module->getVersion());
    }

}