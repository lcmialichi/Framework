<?php

namespace Source\Model\ORM;

interface ORMInterface {

    public function import(object $values);
    public function getId();
    public function all(bool $useAlias = false);
    public function update(array $values);
    public function select(mixed $get, bool $useAlias = false);
    public function insert(array $values);
    public static function table(string $tableName);

}