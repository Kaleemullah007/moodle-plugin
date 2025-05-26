<?php
require('../../config.php');
// require_login(0, false); // Do not force login

if(!$SESSION->registration_success){
    redirect(new moodle_url('/local/registration_validation/register.php'));
}

$userid = $SESSION->userid;
unset($SESSION->registration_success);


$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/registrationvalidation/success.php'));
$PAGE->set_title('Registration Successful');
$PAGE->set_heading('Registration Successful');
global $SESSION;
echo $OUTPUT->header();


$context = \context_user::instance($userid);  // same context used for saving
$itemid = $userid;  // or itemid you used for saving file


$fs = get_file_storage();
$files = $fs->get_area_files($context->id, 'local_registration_validation', 'certificate',$userid , 'filename', false);
// // echo $context->id;

// // print_r($files );
// // die();


foreach ($files as $file) {
    if (!$file->is_directory()) {
       $filename = $file->get_filename();
        //$filename = 'Bestway To Upgrade Mariadb 10-4 to 10-6 .png';
        $fileurl = \moodle_url::make_pluginfile_url(
            $context->id,
            'local_registration_validation',
            'certificate',
            $userid,
            '/',
            $filename
        );

        // if (preg_match('/\.(jpe?g|png)$/i', $filename)) {
            echo '<img src="'.$fileurl.'" alt="'.$filename.'" style="max-width:300px;">';
            
echo '<a href="' . $fileurl . '" target="_blank">Download Certificate</a>';

        // } else {
        //           echo '<img src="'.$fileurl.'" alt="'.$filename.'" style="max-width:300px;">';
        // }
    }
}


echo $OUTPUT->notification(
    '✅ Your account has been created. Once an admin approves your account, you will be able to log in and view your enrolled subjects.',
    'notifysuccess'
);

echo $OUTPUT->continue_button(new moodle_url('/login.php'));

echo $OUTPUT->footer();
