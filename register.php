
<?php
require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/registration_validation/classes/form/registration_form.php');

use local_registration_validation\form\registration_form;

global $PAGE, $DB, $SESSION, $OUTPUT;
// require_login(null, false);

$PAGE->set_url(new moodle_url('/local/registration_validation/register.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('pluginname', 'local_registration_validation'));
$PAGE->set_heading(get_string('pluginname', 'local_registration_validation'));

$form = new registration_form();

if ($form->is_cancelled()) {
    redirect(new moodle_url('/'));
} else if ($data = $form->get_data()) {
    global $DB,$USER;


    

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
        $record->experience_start_date = $data->experience_start_date;
        $record->experience_expiry_date = $data->experience_expiry_date;
        
       $recordid =  $DB->insert_record('local_registration_users', $record);


       if (isset($data->certificatefile)) {
        $filecontent = $data->certificatefile;

        if ($filecontent) {
            $fs = get_file_storage();
            $filename =$data->certificatefile;

        $context = \context_user::instance($recordid); // Or use course/context/plugin context
            $fileinfo = [
                'contextid' => $context->id,
                'component' => 'local_registration_validation',
                'filearea'  => 'certificate',
                'itemid'    => 0,
                'filepath'  => '/',
                'filename'  => $filename,
            ];

            file_save_draft_area_files(
    $data->certificatefile, // This is a draft item ID, not the actual content or file name
    $context->id,
    'local_registration_validation',
    'certificate',
    $recordid,
    ['subdirs' => 0, 'maxbytes' => 0, 'accepted_types' => '*']
);


            // Optional: delete previous file
            // $fs->delete_area_files($context->id, 'local_registration_validation', 'certificate', 0);

            // $fs->create_file_from_string($fileinfo, $filecontent);
           
        } 
    }


        $SESSION->registration_success = true;
        $SESSION->userid = $recordid;

        redirect(new moodle_url('/local/registration_validation/success.php'));
    }
}

echo $OUTPUT->header();
$form->display();
echo $OUTPUT->footer();

?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let emailInput = document.getElementById('id_email');
    let statusSpan = document.getElementById('id_error_email');


    emailInput.addEventListener('blur', function () {
        const email = emailInput.value.trim();
        if (!email) return;

        fetch('./checkemail.php?email=' + encodeURIComponent(email))
            .then(response => response.json())
            .then(data => {

                if (data.exists) {
                    statusSpan.textContent = "This email is already registered.";
                    emailInput.classList.add('is-invalid');
                    statusSpan.classList.add('invalid-feedback');
                    statusSpan.style.display = 'block';
                } else {
                     statusSpan.textContent = "";
                    emailInput.classList.remove('is-invalid');
                    statusSpan.style.display = 'none';
                }
            })
            .catch(err => {
                statusSpan.textContent = "Error checking email.";
                emailInput.classList.add('is-invalid');
                statusSpan.style.display = 'block';
            });
    });

 $('#id_categoryid').on('change', function() {
                var catid = $(this).val();
                $.ajax({
                    url: './get_courses.php',
                    method: 'GET',
                    data: { categoryid: catid },
                    success: function(data) {
                        var courses = JSON.parse(data);
                        var $courseSelect = $('#id_courseid');
                        $courseSelect.empty();
                        $.each(courses, function(id, name) {
                            $courseSelect.append($('<option>', { value: id, text: name }));
                        });

                        $("#id_courseid").select2();
                    }
                });
            });

});

</script>
