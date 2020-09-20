<?php

namespace App\Core;

use App\Core\View;

/**
 * Class Controller
 * @package App\Core
 */
class Controller {

    /**
     * @var \App\Core\Model
     */
    public $model;

    /**
     * @var \App\Core\View
     */
    public $view;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->view = new View();
    }

    function actionIndex() {}

}