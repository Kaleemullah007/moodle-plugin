<?php
define('AJAX_SCRIPT', true);
require('../../config.php');

$email = required_param('email', PARAM_EMAIL);

header('Content-Type: application/json');

// Search in the user table
if ($DB->record_exists('local_registration_users', ['email' => $email])) {
    echo json_encode(['exists' => true]);
} else {
    echo json_encode(['exists' => false]);
}
exit;
