<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 08.01.18
 * Time: 16:54
 */

if (! function_exists('base_path')) {
    function base_path($path = null) {
        return dirname(__DIR__ ) . '/' . $path;
    }
}