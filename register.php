
<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/registration_validation/classes/form/registration_form.php');

use local_registration_validation\form\registration_form;

$PAGE->set_url(new moodle_url('/local/registration_validation/register.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('pluginname', 'local_registration_validation'));
$PAGE->set_heading(get_string('pluginname', 'local_registration_validation'));

$form = new registration_form();

if ($form->is_cancelled()) {
    redirect(new moodle_url('/'));
} else if ($data = $form->get_data()) {
    global $DB;

    // Check for existing username, email or DPI before inserting
    if ($DB->record_exists('local_registration_users', ['username' => $data->username])) {
        $form->set_data($data);
        $form->add_error('username', get_string('usernameexists', 'local_registration_validation'));
    } else if ($DB->record_exists('local_registration_users', ['email' => $data->email])) {
        $form->set_data($data);
        $form->add_error('email', get_string('emailexists', 'local_registration_validation'));
    } else if ($DB->record_exists('local_registration_users', ['dpi' => $data->dpi])) {
        $form->set_data($data);
        $form->add_error('dpi', get_string('dpiexists', 'local_registration_validation'));
    } else {
        // Insert new record
        $record = new stdClass();
        $record->dpi = $data->dpi;
        $record->username = $data->username;
        $record->password = password_hash($data->password, PASSWORD_DEFAULT);
        $record->email = $data->email;
        $record->firstname = $data->firstname;
        $record->lastname = $data->lastname;
        $record->gender = $data->gender;
        $record->phone = $data->phone;
        $record->age = (int)$data->age;
        $record->department = $data->department;
        $record->municipality = $data->municipality;
        $record->ethnicity = $data->ethnicity;
        $record->professional_sector = $data->professional_sector;
        $record->institution = $data->institution;
        $record->professional_college = $data->professional_college;
        $record->member_number = $data->member_number;
        $record->timecreated = time();
        $record->timemodified = time();

        $DB->insert_record('local_registration_users', $record);
        $SESSION->registration_success = true;
        redirect(new moodle_url('/local/registration_validation/success.php'));
    }
}

echo $OUTPUT->header();
$form->display();
echo $OUTPUT->footer();
?>