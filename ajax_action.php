<?php
require('../../config.php');
require_login();
require_sesskey();

$entryid = required_param('id', PARAM_INT);
$action = required_param('action', PARAM_ALPHA);

global $DB;

if (!in_array($action, ['approve', 'reject'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
    exit;
}

try {
    $DB->update_record('local_registration_users', (object)[
        'id' => $entryid,
        'status' => $action
    ]);
    echo json_encode(['success' => true, 'status' => ucfirst($action)]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
exit;
