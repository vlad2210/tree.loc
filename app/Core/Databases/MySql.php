<?php

namespace App\Core\Databases;

use PDO;
use App\Core\Databases\Contracts\DbContract;

/**
 * Class MySql
 * @package app\Core\Databases
 */
class MySql extends PDO implements DbContract
{
    /**
     * Hold database connection
     */
    protected $db;

    /**
     * Model table name
     */
    protected $tableName;

    /**
     * MySql constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * DB connection
     */
    private function init()
    {
        $dbConfig = require __DIR__ . "/../../Config/database.php";

        $type     = 'mysql';
        $host     = $dbConfig['mysql']['host'];
        $charset  = $dbConfig['mysql']['charset'];
        $port     = isset($dbConfig['mysql']['port']) ? 'port=' . $dbConfig['mysql']['port'] . ';' : '';
        $password = $dbConfig['mysql']['password'];
        $database = $dbConfig['mysql']['database'];
        $username = $dbConfig['mysql']['username'];

        $this->db = new PDO("{$type}:host={$host};{$port}" . "dbname={$database};charset={$charset}", $username, $password);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Execute raw sql query
     *
     * @param string $sql
     */
    public function raw(string $sql)
    {
        $this->db->query($sql);
    }

    /**
     * Run sql query
     *
     * @param string $sql
     * @param array $args
     * @return mixed
     */
    public function run(string $sql, array $args = [])
    {
        if (empty($args)) {
            return $this->db->query($sql);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($args);

        return $stmt;
    }

    /**
     * Get arrays of records
     *
     * @param string $sql
     * @param array $args
     * @param int $fetchMode
     * @return mixed
     */
    public function rows(string $sql, array $args = [], int $fetchMode = PDO::FETCH_OBJ)
    {
        return $this->run($sql, $args)->fetchAll($fetchMode);
    }

    /**
     * Get array of records
     *
     * @param string $sql
     * @param array $args
     * @param int $fetchMode
     * @return mixed
     */
    public function row(string $sql, array $args = [], int $fetchMode = PDO::FETCH_OBJ)
    {
        return $this->run($sql, $args)->fetch($fetchMode);
    }

    /**
     * Get record by id
     *
     * @param $id
     * @param int $fetchMode
     * @return mixed
     */
    public function getById($id, int $fetchMode = PDO::FETCH_OBJ)
    {
        return $this->run("SELECT * FROM {$this->tableName} WHERE id = ?", [$id])->fetch($fetchMode);
    }

    /**
     * Insert record
     *
     * @param array $data
     * @return string
     */
    public function insert(array $data)
    {
        $columns = implode(',', array_keys($data));

        $values = array_values($data);

        $placeholders = array_map(function ($val) {
            return '?';
        }, array_keys($data));

        $placeholders = implode(',', array_values($placeholders));

        $this->run("INSERT INTO {$this->tableName} {$columns} VALUES {$placeholders}", $values);

        return true;
    }

    /**
     * Update record
     *
     * @param array $data
     * @param $where
     * @return mixed
     */
    public function update(array $data, $where)
    {
        $collection = array_merge($data, $where);

        $values = array_values($collection);

        $fieldDetails = null;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key = ?,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');

        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            $whereDetails .= $i == 0 ? "$key = ?" : " AND $key = ?";
            $i++;
        }

        $stmt = $this->run("UPDATE {$this->tableName} SET {$fieldDetails} WHERE {$whereDetails}", $values);

        return $stmt->rowCount();
    }

    /**
     * Delete record by id
     *
     * @param $id
     * @return mixed
     */
    public function deleteById($id)
    {
        $stmt = $this->run("DELETE FROM {$this->tableName} WHERE id = ?", [$id]);

        return $stmt->rowCount();
    }

    /**
     * Delete records by id's
     *
     * @param string $column
     * @param $ids
     * @return mixed
     */
    public function deleteByIds(string $column, $ids)
    {
        $stmt = $this->run("DELETE FROM {$this->tableName} WHERE {$column} IN ({$ids})");

        return $stmt->rowCount();
    }

    /**
     * Truncate table
     *
     * @return mixed
     */
    public function truncate()
    {
        $stmt = $this->run("TRUNCATE TABLE {$this->tableName}");

        return $stmt->rowCount();
    }
}