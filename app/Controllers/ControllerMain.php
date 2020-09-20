<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\CategoryRepository;

/**
 * Class ControllerMain
 * @package App\Controllers
 */
class ControllerMain extends Controller
{
    /**
     * @var CategoryRepository
     */
    public $repository;

    /**
     * ControllerMain constructor.
     */
    public function __construct()
    {
        $this->repository = new CategoryRepository();

        parent::__construct();
    }

    /**
     * Index action
     *
     * return view
     */
    public function actionIndex()
    {
        return $this->view->generate('main.php');
    }

    /**
     * Show all menu
     */
    public function actionList()
    {
        $list = $this->repository->getAll();

        foreach ($list as $item) {
            $sub_data["id"] = $item["id"];
            $sub_data["name"] = $item["name"];
            $sub_data["text"] = $item["name"];
            $sub_data["parent_id"] = $item["parent_id"];
            $data[] = $sub_data;
        }

        foreach($data as $key => &$value) {
            $output[$value["id"]] = &$value;
        }

        foreach($data as $key => &$value) {
            if($value["parent_id"] && isset($output[$value["parent_id"]])) {
                $output[$value["parent_id"]]["nodes"][] = &$value;
            }
        }

        foreach($data as $key => &$value) {
            if($value["parent_id"] && isset($output[$value["parent_id"]])) {
                unset($data[$key]);
            }
        }

        print_r(json_encode(array_values($data), JSON_UNESCAPED_UNICODE));
    }

    /**
     * Add new category
     */
    public function actionAdd()
    {
        if (isset($_POST['item_name']) && isset($_POST['item_id']) && !empty($_POST['item_name'])) {
            $itemId = $_POST['item_id'] == 0 ? NULL : $_POST['item_id'];

            $this->repository->create($_POST['item_name'], $itemId);
        }
    }

    /**
     * Delete category
     */
    public function actionDelete()
    {
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $this->repository->delete($_POST['id']);
        }
    }

    /**
     * Get all categories to select
     */
    public function actionSub()
    {
        $list = $this->repository->getAll();

        $output = '<option value="0">Parent Item</option>';

        foreach($list as $row) {
            $output .= '<option value="'.$row["id"].'">'.$row["name"].'</option>';
        }

        echo $output;
    }

    protected function buildTree(array &$elements, $parentId = null) {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['nodes'] = $children;
                }
                $branch[$element['id']] = $element;
                unset($elements[$element['id']]);
            }
        }

        return $branch;
    }
}