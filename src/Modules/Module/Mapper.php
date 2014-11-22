<?php
namespace Modules\Module;


class Mapper {

    /**
     * @var array keys - db fields, values entity attributes
     */
    protected $columnsAsAttributesMap;

    public function __construct() {
        $this->columnsAsAttributesMap = [
            'id' => 'id',
            'name' => 'name',
            'namespace' => 'namespace',
            'version' => 'version',
        ];
    }

    /**
     * @param array $rows
     *
     * @return ModulesCollection
     */
    public function fromTableRows(array $rows) {
        $modules = new ModulesCollection();

        foreach ($rows as $row) {
            $modules[] = $this->fromTableRow($row);
        }

        return $modules;
    }

    /**
     * @param array $row
     *
     * @return Module
     */
    public function fromTableRow(array $row) {
        $attributesValues = $this->getIntersectValuesAsKeys(array_flip($this->columnsAsAttributesMap), $row);

        return new Module(
            $attributesValues['name'],
            $attributesValues['namespace'],
            $attributesValues['version']
        );
    }

    public function toTableRow(Module $module) {
        $objectState = [
            'name' => $module->getName(),
            'namespace' => $module->getNamespace(),
            'version' => $module->getVersion()
        ];

        return $this->getIntersectValuesAsKeys($this->columnsAsAttributesMap, $objectState);
    }

    private function getIntersectValuesAsKeys($array1, $array2) {
        $result = array();

        foreach ($array1 as $key => $value) {
            if (array_key_exists($value, $array2)) {
                $result[$key] = $array2[$value];
            }
        }

        return $result;
    }

} 