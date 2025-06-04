<?php
defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'local/registration_validation:view' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => [
            // 'user' => CAP_ALLOW,
            'manager' => CAP_ALLOW, 
        ]
    ],
];
