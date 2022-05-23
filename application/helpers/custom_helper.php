<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('var_dump_pre')) {

    function var_dump_pre($var = array()) {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }

}

if (!function_exists('print_r_pre')) {

    function print_r_pre($var = array(), $fileName="") {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
		echo '<h3> This function use in '.$fileName.'</h3>';
		die();
    }
}





if (!function_exists('responsJson')) {

   function responsJson($var = array()) {
        header('Content-Type: application/json');
        return json_encode($var);
    }
}
