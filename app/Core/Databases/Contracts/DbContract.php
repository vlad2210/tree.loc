<?php

namespace App\Core\Databases\Contracts;

/**
 * Interface DbContract
 * @package app\Core\Databases
 */
interface DbContract
{
    /**
     * @param string $sql
     * @return mixed
     */
    public function raw(string $sql);

    /**
     * @param string $sql
     * @param array $args
     * @return mixed
     */
    public function run(string $sql, array $args);

    /**
     * @param string $sql
     * @param array $args
     * @param int $fetchMode
     * @return mixed
     */
    public function rows(string $sql, array $args, int $fetchMode);

    /**
     * @param string $sql
     * @param array $args
     * @param int $fetchMode
     * @return mixed
     */
    public function row(string $sql, array $args, int $fetchMode);

    /**
     * @param $id
     * @param int $fetchMode
     * @return mixed
     */
    public function getById($id, int $fetchMode);

    /**
     * @param array $data
     * @return mixed
     */
    public function insert(array $data);

    /**
     * @param array $data
     * @param $where
     * @return mixed
     */
    public function update(array $data, $where);

    /**
     * @param $id
     * @return mixed
     */
    public function deleteById($id);

    /**
     * @param string $column
     * @param $ids
     * @return mixed
     */
    public function deleteByIds(string $column, $ids);

    /**
     * @return mixed
     */
    public function truncate();
}