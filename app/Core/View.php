<?php

namespace App\Core;

/**
 * Class View
 * @package App\Core
 */
class View
{
    function generate($template_view, $data = null)
    {
        if(is_array($data)) {
            extract($data);
        }

        include 'app/Views/'.$template_view;
    }
}