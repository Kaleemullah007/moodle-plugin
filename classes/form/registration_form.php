<?php
namespace local_registration_validation\form;
defined('MOODLE_INTERNAL') || die();




// // Moodle login credentials
// $username = 'maglui7';
// $password = 'MoodleUp25+';

// // Moodle login URL
// $loginUrl = 'https://formacion.cgc.gob.gt/login/index.php';


// // Prepare POST data
// $postFields = http_build_query([
//     'username' => $username,
//     'password' => $password
// ]);

// // Initialize cURL session
// $ch = curl_init();

// curl_setopt_array($ch, [
//     CURLOPT_URL => $loginUrl,
//     CURLOPT_POST => true,
//     CURLOPT_POSTFIELDS => $postFields,
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_FOLLOWLOCATION => true,
//     CURLOPT_SSL_VERIFYPEER => false,
//     CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'
// ]);

// // Execute login request
// $response = curl_exec($ch);

// // Optional: Access a protected page after login

// // Close cURL session
// // curl_close($ch);

// // Show the dashboard HTML (for debug)
// echo "<pre>";
// print_r($response);
// die();

require_once($CFG->libdir . '/formslib.php');


// function add_field_in_col6($mform, $type, $name, $label, $options = []) {
//     $output = '<div class="col-md-6 d-flex align-items-center">';
//     $output .= '<div class="col-md-4"><label for="'.$name.'">'.$label.'</label></div>';
//     $output .= '<div class="col-md-8">';
//     $mform->addElement('html', $output);

//     switch ($type) {
//         case 'text':
//             if(!empty($options))
//             $mform->addElement('text', $name, '');
//             break;
//         case 'password':
//             $mform->addElement('password', $name, '');
//             break;
//         case 'select':
//             $mform->addElement('select', $name, '', $options);
//             break;
//     }

//     $mform->addElement('html', '</div></div>'); // close input div and col-md-6
// }


function add_field_in_col6($mform, $type, $name, $label, $options = []) {
    $output = '<div class="col-md-6 d-flex align-items-center">';
    $output .= '<div class="col-md-4"><label for="'.$name.'">'.$label.'</label></div>';
    $output .= '<div class="col-md-8">';
    $mform->addElement('html', $output);

    switch ($type) {
        case 'text':
            $attributes = !empty($options) ? $options : [];
            $mform->addElement('text', $name, '', $attributes);
            if($name == 'dpi')
            {
                $script = "<script>
                document.addEventListener('DOMContentLoaded', function () {
                    const input = document.querySelector('[name=\"$name\"]');
                    input.addEventListener('input', function () {
                        this.value = this.value.replace(/[^0-9]/g, '');
                    });
                });
            </script>";
            $mform->addElement('html', $script);
            }
            break;

        case 'password':
            $attributes = !empty($options) ? $options : [];
            $mform->addElement('password', $name, '', $attributes);
            break;

        case 'select':
            $mform->addElement('select', $name, '', $options);
            break;
    }

    $mform->addElement('html', '</div></div>'); // close input div and col-md-6
}




class registration_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;
        global $USER,$DB;
        
// $context = \context_user::instance($USER->id);

// $fs = get_file_storage();

// // Fetch all files for your plugin, filearea 'certificate', itemid 0 (adjust if needed)
// // $files = $fs->get_area_files($context->id, 'local_registration_validation', 'certificate', 0, 'filename', false);

// $files = $fs->get_area_files($context->id, 'local_registration_validation', 'certificate', 0, 'filename', false);

// foreach ($files as $file) {
//     // $file->get_filename() gives the original filename

//     // Generate the URL to serve the file
//     $url = \moodle_url::make_pluginfile_url(
//         $context->id,
//         'local_registration_validation',
//         'certificate',
//         0,
//         '/',
//         $file->get_filename()
//     );

//     // Output the image or link
//     echo \html_writer::empty_tag('img', ['src' => $url->out(false), 'alt' => $file->get_filename()]);
// }




// $id = required_param('id', PARAM_INT);
// $record = $DB->get_record('local_registration_users', ['id' => $id, 'userid' => $USER->id], '*', MUST_EXIST);
// $context = \context_user::instance(9);
// 



// // die();
// $mform->addElement('text', 'dpi', get_string('dpicode', 'local_registration_validation'), ['maxlength' => '13', 'pattern' => '[0-9]{1,13}', 'title' => 'Enter up to 13 digits only', 'inputmode' => 'numeric']);
// $mform->setType('dpi', PARAM_TEXT); // 
// add_field_in_col6($mform, 'text', 'dpi', get_string('dpicode', 'local_registration_validation'), [
//     'maxlength' => '13',
//     'pattern' => '[0-9]{1,13}',
//     'title' => 'Enter up to 13 digits only',
//     'inputmode' => 'numeric'
// ]);

$fields = [
    // ['type'=>'text', 'name'=>'dpi', 'label'=>get_string('dpicode', 'local_registration_validation'),'attributes' => [
    //     'maxlength' => 13,
    //     'pattern' => '\d{1,13}', // optional: ensures only digits
    //     'title' => 'Enter up to 13 digits only',
    //     'inputmode' => 'numeric' // optional: helps on mobile
    // ]],
    [
    'type' => 'text',
    'name' => 'dpi',
    'label' => get_string('dpicode', 'local_registration_validation'),
    'options' => [
        'maxlength' => '13',
        'pattern' => '[0-9]{1,13}',
        'title' => 'Enter up to 13 digits only',
        'inputmode' => 'numeric'
    ]
    ],
    ['type'=>'text', 'name'=>'username', 'label'=>get_string('username')],
    ['type'=>'password', 'name'=>'password', 'label'=>get_string('password')],
    ['type'=>'text', 'name'=>'email', 'label'=>get_string('email')],
    ['type'=>'text', 'name'=>'firstname', 'label'=>get_string('firstname')],
    ['type'=>'text', 'name'=>'lastname', 'label'=>get_string('lastname')],
    ['type'=>'select', 'name'=>'gender', 'label'=>get_string('gender', 'local_registration_validation'), 'options'=>[
        'male' => get_string('male', 'local_registration_validation'),
        'female' => get_string('female', 'local_registration_validation'),
        'other' => get_string('other', 'local_registration_validation')
    ]],
    ['type'=>'text', 'name'=>'phone', 'label'=>get_string('phone','local_registration_validation')],
    ['type'=>'text', 'name'=>'age', 'label'=>get_string('age','local_registration_validation')],
    ['type'=>'text', 'name'=>'department', 'label'=>get_string('department', 'local_registration_validation')],
    ['type'=>'text', 'name'=>'municipality', 'label'=>get_string('municipality', 'local_registration_validation')],
    ['type'=>'text', 'name'=>'ethnicity', 'label'=>get_string('ethnicity', 'local_registration_validation')],
    ['type'=>'text', 'name'=>'professional_sector', 'label'=>get_string('professionalsector', 'local_registration_validation')],
    ['type'=>'text', 'name'=>'institution', 'label'=>get_string('institution', 'local_registration_validation')],
    ['type'=>'text', 'name'=>'professional_college', 'label'=>get_string('professionalcollege', 'local_registration_validation')],
    ['type'=>'text', 'name'=>'member_number', 'label'=>get_string('membernumber', 'local_registration_validation')],
];

// Now render fields two per row
for ($i = 0; $i < count($fields); $i += 2) {
    $mform->addElement('html', '<div class="col-md-12 row mb-3">');  // start row

    // First field in col-md-6
    $f1 = $fields[$i];
    add_field_in_col6($mform, $f1['type'], $f1['name'], $f1['label'], $f1['options'] ?? []);

    // Second field in col-md-6 if exists
    if (isset($fields[$i + 1])) {
        $f2 = $fields[$i + 1];
        add_field_in_col6($mform, $f2['type'], $f2['name'], $f2['label'], $f2['options'] ?? []);
    } else {
        // If odd number of fields, you can add empty col-md-6 to balance layout
        $mform->addElement('html', '<div class="col-md-6"></div>');
    }

    $mform->addElement('html', '</div>');  // close row
}

// Now set types and rules for each field

$mform->setType('dpi', PARAM_TEXT);
$mform->addRule('dpi', null, 'required', null, 'client');
$mform->addRule('dpi', get_string('dpicodeerror', 'local_registration_validation'), 'regex', '/^\d{13}$/');

$mform->setType('username', PARAM_ALPHANUMEXT);
$mform->addRule('username', null, 'required', null, 'client');

$mform->setType('password', PARAM_RAW);
$mform->addRule('password', null, 'required', null, 'client');

$mform->setType('email', PARAM_EMAIL);
$mform->addRule('email', null, 'required', null, 'client');
$mform->addRule('email', get_string('invalidemail', 'local_registration_validation'), 'email');

$mform->setType('firstname', PARAM_TEXT);
$mform->addRule('firstname', null, 'required', null, 'client');

$mform->setType('lastname', PARAM_TEXT);
$mform->addRule('lastname', null, 'required', null, 'client');

$mform->addRule('gender', null, 'required', null, 'client');

$mform->setType('phone', PARAM_TEXT);
$mform->addRule('phone', null, 'required', null, 'client');

$mform->setType('age', PARAM_INT);
$mform->addRule('age', null, 'required', null, 'client');

$mform->setType('department', PARAM_TEXT);
$mform->addRule('department', null, 'required', null, 'client');

$mform->setType('municipality', PARAM_TEXT);
$mform->addRule('municipality', null, 'required', null, 'client');

$mform->setType('ethnicity', PARAM_TEXT);
$mform->addRule('ethnicity', null, 'required', null, 'client');

$mform->setType('professional_sector', PARAM_TEXT);
$mform->addRule('professional_sector', null, 'required', null, 'client');

$mform->setType('institution', PARAM_TEXT);
$mform->addRule('institution', null, 'required', null, 'client');

$mform->setType('professional_college', PARAM_TEXT);
$mform->addRule('professional_college', null, 'required', null, 'client');

$mform->setType('member_number', PARAM_TEXT);
$mform->addRule('member_number', null, 'required', null, 'client');


$mform->addElement('select', 'categoryid', get_string('category'));
$mform->setType('categoryid', PARAM_INT);

// // empty course dropdown to be filled by JS
$mform->addElement('select', 'courseid', get_string('course'));
$mform->setType('courseid', PARAM_INT);
$mform->getElement('courseid');//->setMultiple(true);

                                                     


// $mform->addElement('select', 'courseid', get_string('courses', 'local_yourplugin'), $choices, ['multiple' => 'multiple', 'size' => 5]);
// $mform->setType('courseid', PARAM_SEQUENCE);


// $courses = $DB->get_records_menu('course', null, 'fullname ASC', 'id, fullname');




$categories = $DB->get_records_menu('course_categories', null, 'name ASC', 'id, name');
$mform->getElement('categoryid')->load($categories);

// Experience Certificate File Upload
// $mform->addElement('filepicker', 'experience_certificate', get_string('experience_certificate', 'local_registration_validation'), null,
//     array('maxbytes' => 10485760, 'accepted_types' => array('.pdf', '.jpg', '.png'))); // 10MB max
// $mform->addRule('experience_certificate', null, 'required', null, 'client');
// $mform->addElement('filepicker', 'experience_certificate', get_string('experience_certificate', 'local_registration_validation'), null,
//     array('maxbytes' => 10485760, 'accepted_types' => array('.pdf', '.jpg', '.png')));


// $mform->addElement('filepicker', 'experience_certificate',
//     get_string('experience_certificate', 'local_registration_validation'), null,
//     array(
//         'maxbytes' => 10485760, // 10MB
//         'accepted_types' => array('.pdf', '.jpg', '.png')
//     )
// );

// $mform->addRule('experience_certificate', get_string('required'), 'required', null, 'client');


// $mform->addElement('filemanager', 'experience_certificate',
//     get_string('experience_certificate', 'local_registration_validation'), null,
//     [
//         'maxbytes' => 10485760,
//         'subdirs' => 0,
//         'accepted_types' => ['.pdf', '.jpg', '.png'],
//         'maxfiles' => 1
//     ]
// );

// $mform->addElement('filepicker', 'certificatefile', get_string('certificatefile', 'local_registration_validation'), null, [
//     'accepted_types' => ['.pdf', '.jpg', '.jpeg', '.png'],
//     'maxbytes' => 0
// ]);
// $mform->addRule('certificatefile', null, 'required', null, 'client');



// $mform->addElement('filemanager', 'certificatefile', get_string('certificatefile', 'local_registration_validation'), null, array(
//     'subdirs' => 0,
//     'maxbytes' => 10485760, // 10 MB
//     'maxfiles' => 1,
//     'accepted_types' => ['*.pdf', '*.jpg', '*.png']
// ));


// Optional: Add a help button or static info
// $mform->addHelpButton('experience_certificate', 'experience_certificate', 'local_registration_validation');
// $mform->addElement('static', 'experience_certificate_hint', '',
//     get_string('accepted_filetypes_hint', 'local_registration_validation'));
    

// Start Date of Experience
// $mform->addElement('file', 'testfile', 'Professional Certificate');
//         $mform->addRule('testfile', null, 'required');
// $mform->addElement('date_selector', 'experience_start_date', get_string('experience_start_date', 'local_registration_validation'));
// $mform->addRule('experience_start_date', null, 'required', null, 'client');

// // Expiry Date of Experience
// $mform->addElement('date_selector', 'experience_expiry_date', get_string('experience_expiry_date', 'local_registration_validation'));
// $mform->addRule('experience_expiry_date', null, 'required', null, 'client');

// Start a new row
// Open custom row container
// $mform->addElement('html', '<div class="row"><div class="col-12 mb-4">');
// $mform->addElement('html', '<div class="row"><div class="col-12">');
// $mform->addElement('file', 'testfile', 'Professional Certificate');
// $mform->addRule('testfile', null, 'required');
// $mform->addElement('html', '</div></div>');
// $mform->addElement('html', '<div class="row"><div class="col-12">');
// $mform->addElement('date_selector', 'experience_start_date', get_string('experience_start_date', 'local_registration_validation'));
// $mform->addRule('experience_start_date', null, 'required', null, 'client');
// $mform->addElement('html', '</div></div>');

// $mform->addElement('html', '<div class="row"><div class="col-12">');
// $mform->addElement('date_selector', 'experience_expiry_date', get_string('experience_expiry_date', 'local_registration_validation'));
// $mform->addRule('experience_expiry_date', null, 'required', null, 'client');
// $mform->addElement('html', '</div></div>');

// $mform->addElement('html', '</div></div>');
// Open row container
$mform->addElement('html', '<div class="row><div class=" mb-4">');

// File upload (Column 1)
$mform->addElement('html', '<div class="">');
$mform->addElement('file', 'testfile', 'Professional Certificate');
$mform->addRule('testfile', null, 'required');
$mform->addElement('html', '</div>');

// Start date (Column 2)
$mform->addElement('html', '<div class="">');
$mform->addElement('date_selector', 'experience_start_date', get_string('experience_start_date', 'local_registration_validation'));
$mform->addRule('experience_start_date', null, 'required', null, 'client');
$mform->addElement('html', '</div>');

// Expiry date (Column 3)
$mform->addElement('html', '<div class="">');
$mform->addElement('date_selector', 'experience_expiry_date', get_string('experience_expiry_date', 'local_registration_validation'));
$mform->addRule('experience_expiry_date', null, 'required', null, 'client');
$mform->addElement('html', '</div>');

// Close row container
$mform->addElement('html', '</div></div>');



$mform->addElement('html', '<div class="row><div class=" mb-4">');

// File upload (Column 1)
$mform->addElement('html', '<div class="">');

$mform->addElement('file', 'doc_dpi', 'Copy Of DPI');
        $mform->addRule('testfile', null, '');
$mform->addElement('html', '</div>');

// Start date (Column 2)
$mform->addElement('html', '<div class="">');


        $mform->addElement('file', 'contract', 'Contract ');
        $mform->addRule('contract', null, '');
$mform->addElement('html', '</div>');


// Close row container
$mform->addElement('html', '</div></div>');






// $this->add_action_buttons(false, get_string('register', 'local_registration_validation'));
// $mform->addElement('html', '<div class="row"><div class="col-md-12 d-flex justify-content-center">');
// $this->add_action_buttons(false, get_string('register', 'local_registration_validation'));
// $mform->addElement('html', '</div></div>');
$mform->addElement('html', '<div class="row"><div class="col-md-12 text-center mt-4">');
$mform->addElement('html', '<button type="submit" class="btn btn-primary">Register</button>');
$mform->addElement('html', '</div></div>');

//         // Start first row
// $mform->addElement('html', '<div class="row">');

// $mform->addElement('html', '<div class="col-md-6">');
// $mform->addElement('text', 'dpi', get_string('dpicode', 'local_registration_validation'));
// $mform->setType('dpi', PARAM_TEXT);
// $mform->addRule('dpi', null, 'required', null, 'client');
// $mform->addRule('dpi', get_string('dpicodeerror', 'local_registration_validation'), 'regex', '/^\d{13}$/');
// $mform->addElement('html', '</div>');

// $mform->addElement('html', '<div class="col-md-6">');
// $mform->addElement('text', 'username', get_string('username'));
// $mform->setType('username', PARAM_ALPHANUMEXT);
// $mform->addRule('username', null, 'required', null, 'client');
// $mform->addElement('html', '</div>');

// $mform->addElement('html', '<div class="col-md-6">');
// $mform->addElement('password', 'password', get_string('password'));
// $mform->setType('password', PARAM_RAW);
// $mform->addRule('password', null, 'required', null, 'client');
// $mform->addElement('html', '</div>');

// $mform->addElement('html', '</div>'); // close first row


// // Second row
// $mform->addElement('html', '<div class="row">');

// $mform->addElement('html', '<div class="col-md-6">');
// $mform->addElement('text', 'email', get_string('email'));
// $mform->setType('email', PARAM_EMAIL);
// $mform->addRule('email', null, 'required', null, 'client');
// $mform->addRule('email', get_string('invalidemail', 'local_registration_validation'), 'email');
// $mform->addElement('html', '</div>');

// $mform->addElement('html', '<div class="col-md-6">');
// $mform->addElement('text', 'firstname', get_string('firstname'));
// $mform->setType('firstname', PARAM_TEXT);
// $mform->addRule('firstname', null, 'required', null, 'client');
// $mform->addElement('html', '</div>');

// $mform->addElement('html', '<div class="col-md-6">');
// $mform->addElement('text', 'lastname', get_string('lastname'));
// $mform->setType('lastname', PARAM_TEXT);
// $mform->addRule('lastname', null, 'required', null, 'client');
// $mform->addElement('html', '</div>');

// $mform->addElement('html', '</div>'); // close second row


// // Third row
// $mform->addElement('html', '<div class="row">');

// $mform->addElement('html', '<div class="col-md-6">');
// $genders = [
//     'male' => get_string('male', 'local_registration_validation'),
//     'female' => get_string('female', 'local_registration_validation'),
//     'other' => get_string('other', 'local_registration_validation')
// ];
// $mform->addElement('select', 'gender', get_string('gender', 'local_registration_validation'), $genders);
// $mform->addRule('gender', null, 'required', null, 'client');
// $mform->addElement('html', '</div>');

// $mform->addElement('html', '<div class="col-md-6">');
// $mform->addElement('text', 'phone', get_string('phone'));
// $mform->setType('phone', PARAM_TEXT);
// $mform->addRule('phone', null, 'required', null, 'client');
// $mform->addElement('html', '</div>');

// $mform->addElement('html', '<div class="col-md-6">');
// $mform->addElement('text', 'age', get_string('age'));
// $mform->setType('age', PARAM_INT);
// $mform->addRule('age', null, 'required', null, 'client');
// $mform->addElement('html', '</div>');

// $mform->addElement('html', '</div>'); // close third row


// // Fourth row
// $mform->addElement('html', '<div class="row">');

// $mform->addElement('html', '<div class="col-md-6">');
// $mform->addElement('text', 'department', get_string('department', 'local_registration_validation'));
// $mform->setType('department', PARAM_TEXT);
// $mform->addRule('department', null, 'required', null, 'client');
// $mform->addElement('html', '</div>');

// $mform->addElement('html', '<div class="col-md-6">');
// $mform->addElement('text', 'municipality', get_string('municipality', 'local_registration_validation'));
// $mform->setType('municipality', PARAM_TEXT);
// $mform->addRule('municipality', null, 'required', null, 'client');
// $mform->addElement('html', '</div>');

// $mform->addElement('html', '<div class="col-md-6">');
// $mform->addElement('text', 'ethnicity', get_string('ethnicity', 'local_registration_validation'));
// $mform->setType('ethnicity', PARAM_TEXT);
// $mform->addRule('ethnicity', null, 'required', null, 'client');
// $mform->addElement('html', '</div>');

// $mform->addElement('html', '</div>'); // close fourth row


// // Fifth row
// $mform->addElement('html', '<div class="row">');

// $mform->addElement('html', '<div class="col-md-6">');
// $mform->addElement('text', 'professional_sector', get_string('professionalsector', 'local_registration_validation'));
// $mform->setType('professional_sector', PARAM_TEXT);
// $mform->addRule('professional_sector', null, 'required', null, 'client');
// $mform->addElement('html', '</div>');

// $mform->addElement('html', '<div class="col-md-6">');
// $mform->addElement('text', 'institution', get_string('institution', 'local_registration_validation'));
// $mform->setType('institution', PARAM_TEXT);
// $mform->addRule('institution', null, 'required', null, 'client');
// $mform->addElement('html', '</div>');

// $mform->addElement('html', '<div class="col-md-6">');
// $mform->addElement('text', 'professional_college', get_string('professionalcollege', 'local_registration_validation'));
// $mform->setType('professional_college', PARAM_TEXT);
// $mform->addRule('professional_college', null, 'required', null, 'client');
// $mform->addElement('html', '</div>');

// $mform->addElement('html', '</div>'); // close fifth row


// // Sixth row
// $mform->addElement('html', '<div class="row">');

// $mform->addElement('html', '<div class="col-md-6">');
// $mform->addElement('text', 'member_number', get_string('membernumber', 'local_registration_validation'));
// $mform->setType('member_number', PARAM_TEXT);
// $mform->addRule('member_number', null, 'required', null, 'client');
// $mform->addElement('html', '</div>');

// $mform->addElement('html', '<div class="col-md-8">'); 
// // Optional: you can leave this column blank or put something here if needed
// $mform->addElement('html', '</div>');

// $mform->addElement('html', '</div>'); // close sixth row


// // Action buttons (outside grid)
// $this->add_action_buttons(false, get_string('register', 'local_registration_validation'));


        // $mform->addElement('text', 'dpi', get_string('dpicode', 'local_registration_validation'));
        // $mform->setType('dpi', PARAM_TEXT);
        // $mform->addRule('dpi', null, 'required', null, 'client');
        // $mform->addRule('dpi', get_string('dpicodeerror', 'local_registration_validation'), 'regex', '/^\d{13}$/');

        // $mform->addElement('text', 'username', get_string('username'));
        // $mform->setType('username', PARAM_ALPHANUMEXT);
        // $mform->addRule('username', null, 'required', null, 'client');

        // $mform->addElement('password', 'password', get_string('password'));
        // $mform->setType('password', PARAM_RAW);
        // $mform->addRule('password', null, 'required', null, 'client');

        // $mform->addElement('text', 'email', get_string('email'));
        // $mform->setType('email', PARAM_EMAIL);
        // $mform->addRule('email', null, 'required', null, 'client');
        // $mform->addRule('email', get_string('invalidemail', 'local_registration_validation'), 'email');

        // $mform->addElement('text', 'firstname', get_string('firstname'));
        // $mform->setType('firstname', PARAM_TEXT);
        // $mform->addRule('firstname', null, 'required', null, 'client');

        // $mform->addElement('text', 'lastname', get_string('lastname'));
        // $mform->setType('lastname', PARAM_TEXT);
        // $mform->addRule('lastname', null, 'required', null, 'client');

        // $genders = [
        //     'male' => get_string('male', 'local_registration_validation'),
        //     'female' => get_string('female', 'local_registration_validation'),
        //     'other' => get_string('other', 'local_registration_validation')
        // ];
        // $mform->addElement('select', 'gender', get_string('gender', 'local_registration_validation'), $genders);
        // $mform->addRule('gender', null, 'required', null, 'client');

        // $mform->addElement('text', 'phone', get_string('phone'));
        // $mform->setType('phone', PARAM_TEXT);
        // $mform->addRule('phone', null, 'required', null, 'client');

        // $mform->addElement('text', 'age', get_string('age'));
        // $mform->setType('age', PARAM_INT);
        // $mform->addRule('age', null, 'required', null, 'client');

        // $mform->addElement('text', 'department', get_string('department', 'local_registration_validation'));
        // $mform->setType('department', PARAM_TEXT);
        // $mform->addRule('department', null, 'required', null, 'client');

        // $mform->addElement('text', 'municipality', get_string('municipality', 'local_registration_validation'));
        // $mform->setType('municipality', PARAM_TEXT);
        // $mform->addRule('municipality', null, 'required', null, 'client');

        // $mform->addElement('text', 'ethnicity', get_string('ethnicity', 'local_registration_validation'));
        // $mform->setType('ethnicity', PARAM_TEXT);
        // $mform->addRule('ethnicity', null, 'required', null, 'client');

        // $mform->addElement('text', 'professional_sector', get_string('professionalsector', 'local_registration_validation'));
        // $mform->setType('professional_sector', PARAM_TEXT);
        // $mform->addRule('professional_sector', null, 'required', null, 'client');

        // $mform->addElement('text', 'institution', get_string('institution', 'local_registration_validation'));
        // $mform->setType('institution', PARAM_TEXT);
        // $mform->addRule('institution', null, 'required', null, 'client');

        // $mform->addElement('text', 'professional_college', get_string('professionalcollege', 'local_registration_validation'));
        // $mform->setType('professional_college', PARAM_TEXT);
        // $mform->addRule('professional_college', null, 'required', null, 'client');

        // $mform->addElement('text', 'member_number', get_string('membernumber', 'local_registration_validation'));
        // $mform->setType('member_number', PARAM_TEXT);
        // $mform->addRule('member_number', null, 'required', null, 'client');

        // $this->add_action_buttons(false, get_string('register', 'local_registration_validation'));
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

         global $DB;
    if ($DB->record_exists('local_registration_users', ['dpi' => $data['dpi']])) {
        $errors['dpi'] = get_string('dpiexists', 'local_registration_validation');
    }




//     $draftitemid = $data['experience_certificate'] ?? 0;

// if (!$draftitemid) {
//     $errors['experience_certificate'] = get_string('required', 'local_registration_validation');
//     return $errors;
// }

// $context = \context_system::instance();
// $fs = get_file_storage();

// $files = $fs->get_area_files($context->id, 'user', 'draft', $draftitemid, 'id', false);

// if (empty($files)) {
//     $errors['experience_certificate'] = get_string('required', 'local_registration_validation');
// }


//     echo "<pre>";
//     print_r($files);
//     die();


// if (empty($files['experience_certificate'])) {
//         $errors['experience_certificate'] = get_string('requiredfield', 'local_registration_validation');
//     } else {
//         // Check file extension
//         $filename = $files['experience_certificate']['name'];
//         $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
//         $allowed = ['pdf', 'jpg', 'jpeg', 'png'];

//         if (!in_array($ext, $allowed)) {
//             $errors['experience_certificate'] = get_string('accepted_filetypes_hint', 'local_registration_validation');
//         }
//     }

//     $draftitemid = $data['experience_certificate'] ?? 0;

//     if (empty($draftitemid)) {
//         $errors['experience_certificate'] = get_string('required', 'local_registration_validation');
//         return $errors;
//     }

//     $context = \context_system::instance();
//     $fs = get_file_storage();

//     // Get the uploaded file(s) from the draft area
//     $userfiles = $fs->get_area_files($context->id, 'user', 'draft', $draftitemid, 'id', false);

//     if (empty($userfiles)) {
//         $errors['experience_certificate'] = get_string('required', 'local_registration_validation');
//         return $errors;
//     }

//     // OPTIONAL: Validate extension
//     $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
//     foreach ($userfiles as $file) {
//         $filename = $file->get_filename();
//         $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
//         if (!in_array($ext, $allowed)) {
//             $errors['experience_certificate'] = get_string('invalidfiletype', 'local_registration_validation');
//             break;
//         }
//     }
//     echo '<pre>';
// print_r($_FILES);
// print_r($data);
// echo '</pre>';
// die();
    
// $filename = $mform->get_new_filename('experience_certificate');
// if (!$filename) {
//     $errors['experience_certificate'] = get_string('required', 'local_registration_validation');
// }
// if ($filename) {
//     // System context (no user during signup)
//     $context = \context_system::instance();

//     // Save the uploaded file to plugin file area
//     $file = $mform->save_stored_file('experience_certificate', 
//         $context->id, 
//         'local_registration_validation', 
//         'experience_certificate', 
//         0, // itemid
//         ['subdirs' => false, 'maxfiles' => 1, 'accepted_types' => ['.pdf', '.jpg', '.png']]
//     );
// }
     
   

    // Optional: Extension check (extra safety)

    // foreach ($files as $file) {
    //     $filename = $file->get_filename();
    //    echo $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    //    die();
    //     if (!in_array($ext, ['pdf', 'jpg', 'jpeg', 'png'])) {
    //         $errors['experience_certificate'] = get_string('accepted_filetypes_hint', 'local_registration_validation');
    //         break;
    //     }
    // }

    // die();



        if (empty($data['dpi'])) {
            $errors['dpi'] = get_string('requiredfield', 'local_registration_validation');
        } else if (!preg_match('/^\d{13}$/', $data['dpi'])) {
            $errors['dpi'] = get_string('dpicodeerror', 'local_registration_validation');
        }

        if (empty($data['username'])) {
            $errors['username'] = get_string('requiredfield', 'local_registration_validation');
        } else if (!preg_match('/^[a-zA-Z0-9_-]+$/', $data['username'])) {
            $errors['username'] = get_string('usernameinvalid', 'local_registration_validation');
        }

        if (empty($data['password'])) {
            $errors['password'] = get_string('requiredfield', 'local_registration_validation');
        } else if (strlen($data['password']) < 6) {
            $errors['password'] = get_string('passwordminlength', 'local_registration_validation');
        }

        if (empty($data['email'])) {
            $errors['email'] = get_string('requiredfield', 'local_registration_validation');
        } else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = get_string('invalidemail', 'local_registration_validation');
        }

        if (empty(trim($data['firstname']))) {
            $errors['firstname'] = get_string('requiredfield', 'local_registration_validation');
        }

        if (empty(trim($data['lastname']))) {
            $errors['lastname'] = get_string('requiredfield', 'local_registration_validation');
        }

        $validgenders = ['male', 'female', 'other'];
        if (empty($data['gender']) || !in_array($data['gender'], $validgenders)) {
            $errors['gender'] = get_string('requiredfield', 'local_registration_validation');
        }

        if (empty(trim($data['phone']))) {
            $errors['phone'] = get_string('requiredfield', 'local_registration_validation');
        } else if (!preg_match('/^[\d\s\+\-\(\)]+$/', $data['phone'])) {
            $errors['phone'] = get_string('phoneinvalid', 'local_registration_validation');
        }

        if (empty($data['age'])) {
            $errors['age'] = get_string('requiredfield', 'local_registration_validation');
        } else if (!is_numeric($data['age']) || $data['age'] < 18 || $data['age'] > 120) {
            $errors['age'] = get_string('invalidage', 'local_registration_validation');
        }

        $requiredfields = ['department', 'municipality', 'ethnicity', 'professional_sector', 'institution', 'professional_college', 'member_number'];
        foreach ($requiredfields as $field) {
            if (empty(trim($data[$field]))) {
                $errors[$field] = get_string('requiredfield', 'local_registration_validation');
            }
        }

    //      if (empty($files['experience_certificate'])) {
    //     $errors['experience_certificate'] = get_string('requiredfield', 'local_registration_validation');
    // }

    // $context = context_system::instance();
    // $draftitemid = $data['experience_certificate'];

    // // Use the file storage API to check if any files uploaded in draft area
    // $fs = get_file_storage();
    // $filesinarea = $fs->get_area_files($context->id, 'user', 'draft', $draftitemid, 'id', false);


    // // echo "<pre>";
    // // print_r($filesinarea);
    // // die();
    // if (empty($filesinarea)) {
    //     $errors['experience_certificate'] = get_string('requiredfield', 'local_registration_validation');
    // }


    // Validate Start and Expiry Dates
    if (empty($data['experience_start_date'])) {
        $errors['experience_start_date'] = get_string('requiredfield', 'local_registration_validation');
    }

    if (empty($data['experience_expiry_date'])) {
        $errors['experience_expiry_date'] = get_string('requiredfield', 'local_registration_validation');
    }

    if (!empty($data['experience_start_date']) && !empty($data['experience_expiry_date'])) {
        if ($data['experience_expiry_date'] <= $data['experience_start_date']) {
            $errors['experience_expiry_date'] = get_string('expiry_after_start', 'local_registration_validation');
        }
    }

        return $errors;
    }

}



// $mform->addElement('html', '<div class="form-step" id="step1">');

// $mform->addElement('html', '<div class="form-step" id="step1">');

// add_field_in_col6($mform, 'text', 'dpi', get_string('dpicode', 'local_registration_validation'), [...]);
// add_field_in_col6($mform, 'text', 'username', get_string('username'));
// add_field_in_col6($mform, 'password', 'password', get_string('password'));
// add_field_in_col6($mform, 'text', 'email', get_string('email'));
// add_field_in_col6($mform, 'text', 'firstname', get_string('firstname'));
// add_field_in_col6($mform, 'text', 'lastname', get_string('lastname'));
// add_field_in_col6($mform, 'select', 'gender', get_string('gender', 'local_registration_validation'), [...]);
// add_field_in_col6($mform, 'text', 'phone', get_string('phone','local_registration_validation'));
// add_field_in_col6($mform, 'text', 'age', get_string('age','local_registration_validation'));

// $mform->addElement('html', '</div>'); // Close step1




// $mform->addElement('html', '<div class="form-step d-none" id="step2">');

// add_field_in_col6($mform, 'text', 'department', get_string('department', 'local_registration_validation'));
// add_field_in_col6($mform, 'text', 'municipality', get_string('municipality', 'local_registration_validation'));
// add_field_in_col6($mform, 'text', 'ethnicity', get_string('ethnicity', 'local_registration_validation'));
// add_field_in_col6($mform, 'text', 'professional_sector', get_string('professionalsector', 'local_registration_validation'));
// add_field_in_col6($mform, 'text', 'institution', get_string('institution', 'local_registration_validation'));
// add_field_in_col6($mform, 'text', 'professional_college', get_string('professionalcollege', 'local_registration_validation'));
// add_field_in_col6($mform, 'text', 'member_number', get_string('membernumber', 'local_registration_validation'));

// $mform->addElement('html', '</div>');


// $mform->addElement('html', '<div class="form-step d-none" id="step3">');

// // File
// $mform->addElement('file', 'testfile', 'Professional Certificate');
// $mform->addRule('testfile', null, 'required');

// $mform->addElement('date_selector', 'experience_start_date', get_string('experience_start_date', 'local_registration_validation'));
// $mform->addElement('date_selector', 'experience_expiry_date', get_string('experience_expiry_date', 'local_registration_validation'));

// $mform->addElement('file', 'doc_dpi', 'Copy Of DPI');
// $mform->addElement('file', 'contract', 'Contract');

// $mform->addElement('html', '</div>');



// $mform->addElement('html', '<div class="form-step d-none" id="step4">');

// $mform->addElement('select', 'categoryid', get_string('category'));
// $mform->setType('categoryid', PARAM_INT);
// $mform->addElement('select', 'courseid', get_string('course'));
// $mform->setType('courseid', PARAM_INT);

// $mform->addElement('html', '<div class="text-center mt-4">');
// $mform->addElement('submit', 'submitbutton', get_string('register', 'local_registration_validation'));
// $mform->addElement('html', '</div>');

// $mform->addElement('html', '</div>');


// $mform->addElement('html', '
//   <div class="step-buttons text-center mt-3">
//     <button type="button" class="btn btn-secondary prev-step">Previous</button>
//     <button type="button" class="btn btn-primary next-step">Next</button>
//   </div>
// ');

// <script>
// document.addEventListener("DOMContentLoaded", () => {
//   const steps = document.querySelectorAll(".form-step");
//   let current = 0;

//   function showStep(index) {
//     steps.forEach((s, i) => s.classList.toggle("d-none", i !== index));
//   }

//   document.querySelectorAll(".next-step").forEach(btn =>
//     btn.addEventListener("click", () => {
//       if (current < steps.length - 1) {
//         current++;
//         showStep(current);
//       }
//     })
//   );

//   document.querySelectorAll(".prev-step").forEach(btn =>
//     btn.addEventListener("click", () => {
//       if (current > 0) {
//         current--;
//         showStep(current);
//       }
//     })
//   );

//   showStep(current);
// });
// </script>



?>




<?php

// require_once(__DIR__.'/../../config.php');
// require_once($CFG->libdir.'/formslib.php');

// class multi_step_registration_form extends moodleform {
//     public function definition() {
//         $mform = $this->_form;

//         // Step 1: Personal Info
//         $mform->addElement('html', '<div class="form-step" id="step1">');

//         $mform->addElement('text', 'dpi', get_string('dpicode', 'local_registration_validation'));
//         $mform->setType('dpi', PARAM_TEXT);
//         $mform->addElement('text', 'username', get_string('username'));
//         $mform->setType('username', PARAM_TEXT);
//         $mform->addElement('password', 'password', get_string('password'));
//         $mform->setType('password', PARAM_TEXT);
//         $mform->addElement('text', 'email', get_string('email'));
//         $mform->setType('email', PARAM_EMAIL);
//         $mform->addElement('text', 'firstname', get_string('firstname'));
//         $mform->setType('firstname', PARAM_TEXT);
//         $mform->addElement('text', 'lastname', get_string('lastname'));
//         $mform->setType('lastname', PARAM_TEXT);
//         $mform->addElement('select', 'gender', get_string('gender', 'local_registration_validation'), ['Male' => 'Male', 'Female' => 'Female']);
//         $mform->addElement('text', 'phone', get_string('phone', 'local_registration_validation'));
//         $mform->setType('phone', PARAM_TEXT);
//         $mform->addElement('text', 'age', get_string('age', 'local_registration_validation'));
//         $mform->setType('age', PARAM_INT);

//         $mform->addElement('html', '<div class="step-buttons"><button type="button" class="btn btn-primary next-step">Next</button></div>');
//         $mform->addElement('html', '</div>');

//         // Step 2: Professional Info
//         $mform->addElement('html', '<div class="form-step d-none" id="step2">');

//         $mform->addElement('text', 'department', get_string('department', 'local_registration_validation'));
//         $mform->addElement('text', 'municipality', get_string('municipality', 'local_registration_validation'));
//         $mform->addElement('text', 'ethnicity', get_string('ethnicity', 'local_registration_validation'));
//         $mform->addElement('text', 'professional_sector', get_string('professionalsector', 'local_registration_validation'));
//         $mform->addElement('text', 'institution', get_string('institution', 'local_registration_validation'));
//         $mform->addElement('text', 'professional_college', get_string('professionalcollege', 'local_registration_validation'));
//         $mform->addElement('text', 'member_number', get_string('membernumber', 'local_registration_validation'));

//         $mform->addElement('html', '<div class="step-buttons">
//             <button type="button" class="btn btn-secondary prev-step">Previous</button>
//             <button type="button" class="btn btn-primary next-step">Next</button>
//         </div>');
//         $mform->addElement('html', '</div>');

//         // Step 3: Documents
//         $mform->addElement('html', '<div class="form-step d-none" id="step3">');

//         $mform->addElement('file', 'testfile', 'Professional Certificate');
//         $mform->addRule('testfile', null, 'required');
//         $mform->addElement('date_selector', 'experience_start_date', get_string('experience_start_date', 'local_registration_validation'));
//         $mform->addElement('date_selector', 'experience_expiry_date', get_string('experience_expiry_date', 'local_registration_validation'));
//         $mform->addElement('file', 'doc_dpi', 'Copy Of DPI');
//         $mform->addElement('file', 'contract', 'Contract');

//         $mform->addElement('html', '<div class="step-buttons">
//             <button type="button" class="btn btn-secondary prev-step">Previous</button>
//             <button type="button" class="btn btn-primary next-step">Next</button>
//         </div>');
//         $mform->addElement('html', '</div>');

//         // Step 4: Submit
//         $mform->addElement('html', '<div class="form-step d-none" id="step4">');

//         $mform->addElement('select', 'categoryid', get_string('category'));
//         $mform->setType('categoryid', PARAM_INT);
//         $mform->addElement('select', 'courseid', get_string('course'));
//         $mform->setType('courseid', PARAM_INT);

//         $mform->addElement('html', '<div class="step-buttons">
//             <button type="button" class="btn btn-secondary prev-step">Previous</button>
//             <div class="text-center mt-4">
//                 <input type="submit" name="submitbutton" value="'.get_string('register', 'local_registration_validation').'" class="btn btn-success" />
//             </div>
//         </div>');
//         $mform->addElement('html', '</div>');

//         // JS for step navigation
//         $mform->addElement('html', '<script>
//         document.addEventListener("DOMContentLoaded", () => {
//             const steps = document.querySelectorAll(".form-step");
//             let current = 0;

//             function showStep(index) {
//                 steps.forEach((s, i) => s.classList.toggle("d-none", i !== index));
//             }

//             document.querySelectorAll(".next-step").forEach(btn =>
//                 btn.addEventListener("click", () => {
//                     if (current < steps.length - 1) {
//                         current++;
//                         showStep(current);
//                     }
//                 })
//             );

//             document.querySelectorAll(".prev-step").forEach(btn =>
//                 btn.addEventListener("click", () => {
//                     if (current > 0) {
//                         current--;
//                         showStep(current);
//                     }
//                 })
//             );

//             showStep(current);
//         });
//         </script>');
//     }
// }
