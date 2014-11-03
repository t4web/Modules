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
            'version' => 'version',
        ];
    }

    public function fromTableRow(array $row) {
        $attributesValues = $this->getIntersectValuesAsKeys(array_flip($this->columnsAsAttributesMap), $row);

        return new Module(
            $attributesValues['name'],
            $attributesValues['version']
        );
    }

    public function toTableRow(Module $module) {
        $objectState = [
            'name' => $module->getName(),
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