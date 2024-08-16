<?php

require_once 'src/repository/AbstractRepository.php';

class DbPersist {

    private AbstractRepository $abstract_repository;

    public function __construct() {
        $this->abstract_repository = new AbstractRepository();
    }

    public function persist(Object $object):void {
        $table = strtolower($object->getName());
        $data = $this->createData($object);
        $id = $object->getId();

        $this->abstract_repository->persist($table, $data, $id);
    }

    private function createData(Object $object):array {
        $data = array();

        $objects_vars = $object->getAll();
        foreach($objects_vars as $name => $value) {
            $data[$name] = $value;
        }
        
        return $data;
    }
}