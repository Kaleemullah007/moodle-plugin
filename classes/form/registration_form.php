<?php
namespace local_registration_validation\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class registration_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('text', 'dpi', get_string('dpicode', 'local_registration_validation'));
        $mform->setType('dpi', PARAM_TEXT);
        $mform->addRule('dpi', null, 'required', null, 'client');
        $mform->addRule('dpi', get_string('dpicodeerror', 'local_registration_validation'), 'regex', '/^\d{13}$/');

        $mform->addElement('text', 'username', get_string('username'));
        $mform->setType('username', PARAM_ALPHANUMEXT);
        $mform->addRule('username', null, 'required', null, 'client');

        $mform->addElement('password', 'password', get_string('password'));
        $mform->setType('password', PARAM_RAW);
        $mform->addRule('password', null, 'required', null, 'client');

        $mform->addElement('text', 'email', get_string('email'));
        $mform->setType('email', PARAM_EMAIL);
        $mform->addRule('email', null, 'required', null, 'client');
        $mform->addRule('email', get_string('invalidemail', 'local_registration_validation'), 'email');

        $mform->addElement('text', 'firstname', get_string('firstname'));
        $mform->setType('firstname', PARAM_TEXT);
        $mform->addRule('firstname', null, 'required', null, 'client');

        $mform->addElement('text', 'lastname', get_string('lastname'));
        $mform->setType('lastname', PARAM_TEXT);
        $mform->addRule('lastname', null, 'required', null, 'client');

        $genders = [
            'male' => get_string('male', 'local_registration_validation'),
            'female' => get_string('female', 'local_registration_validation'),
            'other' => get_string('other', 'local_registration_validation')
        ];
        $mform->addElement('select', 'gender', get_string('gender', 'local_registration_validation'), $genders);
        $mform->addRule('gender', null, 'required', null, 'client');

        $mform->addElement('text', 'phone', get_string('phone'));
        $mform->setType('phone', PARAM_TEXT);
        $mform->addRule('phone', null, 'required', null, 'client');

        $mform->addElement('text', 'age', get_string('age'));
        $mform->setType('age', PARAM_INT);
        $mform->addRule('age', null, 'required', null, 'client');

        $mform->addElement('text', 'department', get_string('department', 'local_registration_validation'));
        $mform->setType('department', PARAM_TEXT);
        $mform->addRule('department', null, 'required', null, 'client');

        $mform->addElement('text', 'municipality', get_string('municipality', 'local_registration_validation'));
        $mform->setType('municipality', PARAM_TEXT);
        $mform->addRule('municipality', null, 'required', null, 'client');

        $mform->addElement('text', 'ethnicity', get_string('ethnicity', 'local_registration_validation'));
        $mform->setType('ethnicity', PARAM_TEXT);
        $mform->addRule('ethnicity', null, 'required', null, 'client');

        $mform->addElement('text', 'professional_sector', get_string('professionalsector', 'local_registration_validation'));
        $mform->setType('professional_sector', PARAM_TEXT);
        $mform->addRule('professional_sector', null, 'required', null, 'client');

        $mform->addElement('text', 'institution', get_string('institution', 'local_registration_validation'));
        $mform->setType('institution', PARAM_TEXT);
        $mform->addRule('institution', null, 'required', null, 'client');

        $mform->addElement('text', 'professional_college', get_string('professionalcollege', 'local_registration_validation'));
        $mform->setType('professional_college', PARAM_TEXT);
        $mform->addRule('professional_college', null, 'required', null, 'client');

        $mform->addElement('text', 'member_number', get_string('membernumber', 'local_registration_validation'));
        $mform->setType('member_number', PARAM_TEXT);
        $mform->addRule('member_number', null, 'required', null, 'client');

        $this->add_action_buttons(false, get_string('register', 'local_registration_validation'));
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

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

        return $errors;
    }
