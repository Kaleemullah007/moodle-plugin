<?php

defined('MOODLE_INTERNAL') || die();

/**
 * Extend the admin navigation to include plugin settings
 */
function local_registration_validation_extend_navigation(global_navigation $nav) {
    if (has_capability('moodle/site:config', context_system::instance())) {
        $settingsnode = $nav->get('settings');
        if ($settingsnode) {
            $url = new moodle_url('/local/registration_validation/settings.php');
            $settingsnode->add(get_string('pluginname', 'local_registration_validation'), $url, navigation_node::TYPE_SETTING);
        }
    }
}

/**
 * Define capabilities used in the plugin
 */
function local_registration_validation_get_extra_capabilities() {
    return ['local/registration_validation:manage'];
}

/**
 * Enroll user into course after validation approval
 * @param int $userid
 * @param int $courseid
 * @return bool
 */
function local_registration_validation_enroll_user_in_course($userid, $courseid) {
    global $DB;

    require_once($CFG->libdir.'/enrollib.php');

    $enrol = enrol_get_plugin('manual');
    if (!$enrol) {
        debugging('Manual enrol plugin not enabled');
        return false;
    }

    // Check if user already enrolled
    $context = context_course::instance($courseid);
    if (is_enrolled($context, $userid)) {
        return true; // Already enrolled
    }

    // Get manual enrol instance for the course
    $instances = enrol_get_instances($courseid, true);
    foreach ($instances as $instance) {
        if ($instance->enrol === 'manual') {
            $manualinstance = $instance;
            break;
        }
    }

    if (empty($manualinstance)) {
        debugging('No manual enrolment instance found for course id ' . $courseid);
        return false;
    }

    $enrol->enrol_user($manualinstance, $userid, $manualinstance->roleid);
    return true;
}

/**
 * Send email notification to user
 * @param int $userid
 * @param string $subject
 * @param string $message
 * @return bool
 */
function local_registration_validation_notify_user($userid, $subject, $message) {
    global $DB;

    $user = $DB->get_record('user', ['id' => $userid], '*', MUST_EXIST);
    $supportuser = get_support_user();

    $eventdata = new \core\message\message();
    $eventdata->component         = 'local_registration_validation';
    $eventdata->name              = 'notification';
    $eventdata->userfrom          = $supportuser;
    $eventdata->userto            = $user;
    $eventdata->subject           = $subject;
    $eventdata->fullmessage       = $message;
    $eventdata->fullmessageformat = FORMAT_PLAIN;
    $eventdata->smallmessage      = $subject;

    return message_send($eventdata);
}

function local_registration_validation_get_file_areas($course, $cm, $context) {
    return [
        'experience_certificate' => get_string('experience_certificate', 'local_registration_validation')
    ];
}


function local_registration_validation_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($filearea !== 'certificate') {
        return false;
    }

    // require_login(); // Ensure user is logged in

    $itemid = array_shift($args);
    $filename = array_pop($args);
    $filepath = $args ? '/' . implode('/', $args) . '/' : '/';

    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'local_registration_validation', 'certificate', $itemid, $filepath, $filename);

    if (!$file || $file->is_directory()) {
        return false;
    }

    send_stored_file($file, 0, 0, $forcedownload, $options);
}
