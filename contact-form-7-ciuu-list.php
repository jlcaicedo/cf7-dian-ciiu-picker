<?php

/**
* Plugin Name: Lista de Códigos CIIU para Contact Form 7
* Plugin URI: http://josecaicedo.co
* Description: Este plugin es exclusivo para Contact Form 7 y permite mostrar la Clasificación CIIU de Colombia según la DIAN en una lista seleccionable.
* Author: Jose Caicedo
* Version: 1.0.1
* Author URI: http://josecaicedo.co
* Text Domain: cf7-ciuu-list
* License: GPL v2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

define('LOCCFCF7_PLUGIN_DIR', plugin_dir_path(__FILE__));

function generate_dropdown_list() {
    $json_path = LOCCFCF7_PLUGIN_DIR . 'asset/CIIU.json';  
    $json = file_get_contents($json_path);
    $data = json_decode($json, true);

    $options = array('' => 'Seleccione una opción --->');  // Default option

    if ($data !== null) {
        foreach ($data as $letter => $group) {
            foreach ($group['divisiones'] as $divisionCode => $division) {
                foreach ($division['subdivisiones'] as $subdivisionCode => $subdivision) {
                    foreach ($subdivision['actividades'] as $activityCode => $activity) {
                        if (is_numeric($activityCode)) {
                            $options[$activityCode] = $activityCode . ' - ' . $activity;
                        }
                    }
                }
            }
        }
    }
    
    return $options;
}

function modify_cf7_tag($tag) {
    if ($tag['type'] != 'select' || $tag['name'] != 'codigos_ciuu') {
        return $tag;
    }

    $options = generate_dropdown_list();
    
    $tag['raw_values'] = array_keys($options);
    $tag['values'] = array_values($options);
    
    $tag['options'][] = 'required:required';  // Making selection required
    
    return $tag;
}

function init_plugin() {
    add_filter('wpcf7_form_tag', 'modify_cf7_tag', 10, 2);
}

add_action('plugins_loaded', 'init_plugin');
?>