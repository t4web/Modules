<?php
namespace Modules\Module;

use Zend\Db\TableGateway\TableGatewayInterface;

class DbRepository {

    /**
     * @var TableGatewayInterface
     */
    private $tableGateway;

    /**
     * @var Mapper
     */
    private $mapper;

    public function __construct(
        TableGatewayInterface $tableGateway,
        Mapper $mapper) {

        $this->tableGateway = $tableGateway;
        $this->mapper = $mapper;
    }

    /**
     * @param array $criteria
     *
     * @return Module|null
     */
    public function find(array $criteria)
    {
        $row = $this->tableGateway->select($criteria)->toArray();

        if (empty($row) || !isset($row[0])) {
            return null;
        }

        $entity = $this->mapper->fromTableRow($row[0]);

        return $entity;
    }

    public function add(Module $module)
    {
        $row = $this->mapper->toTableRow($module);

        $this->tableGateway->insert($row);
    }

    public function remove(Module $module)
    {
        $row = $this->mapper->toTableRow($module);

        $this->tableGateway->delete([ 'name' => $module->getName()]);
    }

} 