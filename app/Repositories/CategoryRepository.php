<?php

namespace App\Repositories;

use App\Models\ModelMain;

/**
 * Class CategoryRepository
 * @package App\Repositories
 */
class CategoryRepository
{
    /**
     * @var ModelMain
     */
    private $model;

    /**
     * CategoryRepository constructor.
     */
    public function __construct()
    {
        $this->model = new ModelMain();
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->rows("SELECT * FROM category ORDER BY id DESC", [], \PDO::FETCH_BOTH);
    }

    /**
     * @param $name
     * @param null $id
     * @return mixed
     */
    public function create($name, $id = NULL)
    {
        if ($id) {
            return $this->model->row("INSERT INTO category (name, parent_id) VALUES ('" . $name. "','" . $id . "')");
        }

        return $this->model->row("INSERT INTO category (name) VALUES ('" . $name. "')");
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model->row("DELETE FROM category WHERE id = '".$id."' OR parent_id = '".$id."'");
    }
}