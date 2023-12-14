<?php
/**
 * Plugin Name: CF7 DIAN CIIU Picker
 * Plugin URI: http://josecaicedo.co
 * Description: This plugin is exclusively for Contact Form 7 and allows displaying the CIIU Classification of Colombia according to DIAN in a selectable list.
 * Author: Jose Caicedo
 * Version: 1.0.2
 * Author URI: http://josecaicedo.co
 * Text Domain: cf7-dian-ciiu-picker
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

define('CF7DCP_PLUGIN_DIR', plugin_dir_path(__FILE__));

function cf7dcp_generate_dropdown_list() {
    $json_path = CF7DCP_PLUGIN_DIR . 'asset/CIIU.json';
    if (!file_exists($json_path)) {
        return array('' => __('Select an option --->', 'cf7-dian-ciiu-picker'));
    }

    $json = file_get_contents($json_path);
    $data = json_decode($json, true);

    $options = array('' => __('Select an option --->', 'cf7-dian-ciiu-picker'));

    if ($data !== null) {
        foreach ($data as $group) {
            foreach ($group['divisiones'] as $division) {
                foreach ($division['subdivisiones'] as $subdivision) {
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

function cf7dcp_modify_cf7_tag($tag) {
    if ($tag['type'] != 'select' || $tag['name'] != 'codigos_ciuu') {
        return $tag;
    }

    $options = cf7dcp_generate_dropdown_list();

    $tag['raw_values'] = array_keys($options);
    $tag['values'] = array_values($options);

    $tag['options'][] = 'required:required';

    return $tag;
}

function cf7dcp_init_plugin() {
    if (!is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
        add_action('admin_notices', 'cf7dcp_admin_notice');
        return;
    }

    add_filter('wpcf7_form_tag', 'cf7dcp_modify_cf7_tag', 10, 2);
}

function cf7dcp_admin_notice() {
    ?>
    <div class="notice notice-warning is-dismissible">
        <p><?php _e('CF7 DIAN CIIU Picker requires Contact Form 7 to be installed and active for optimal functionality.', 'cf7-dian-ciiu-picker'); ?></p>
    </div>
    <?php
}

add_action('plugins_loaded', 'cf7dcp_init_plugin');