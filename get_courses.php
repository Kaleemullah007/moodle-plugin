<?php 

require_once(__DIR__ . '/../../config.php');

$categoryid = required_param('categoryid', PARAM_INT);
require_login();

$context = \context_system::instance();
require_capability('moodle/course:view', $context);

$courses = $DB->get_records_menu('course', ['category' => $categoryid, 'visible' => 1], 'fullname ASC', 'id, fullname');

echo json_encode($courses);
die;
?>