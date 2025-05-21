<?php
// Optional admin settings page (leave empty or add your plugin configuration here).

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_registration_validation', get_string('pluginname', 'local_registration_validation'));
    $ADMIN->add('localplugins', $settings);

    // Add settings here if needed
}
