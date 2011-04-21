<?php defined('BASEPATH') || exit("No direct script access allowed");

if (!function_exists('set_tree_icons')) {
    function set_tree_icons(&$value, $key, $icon) {
        if (isset($value['text']) && !isset($value['icon'])) {
            $value['icon'] = $icon;
        }
    }
}