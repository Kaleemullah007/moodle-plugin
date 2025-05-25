<?php
require('../../config.php');
require_login(0, false); // Do not force login

unset($SESSION->registration_success);
$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/registrationvalidation/success.php'));
$PAGE->set_title('Registration Successful');
$PAGE->set_heading('Registration Successful');

echo $OUTPUT->header();

echo $OUTPUT->notification(
    '✅ Your account has been created. Once an admin approves your account, you will be able to log in and view your enrolled subjects.',
    'notifysuccess'
);

echo $OUTPUT->continue_button(new moodle_url('/register.php'));

echo $OUTPUT->footer();
