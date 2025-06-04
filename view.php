<?php
require('../../config.php');


global $USER;

// Try to get roles at system context
// $context = context_system::instance();

// $userroles = get_user_roles($context, $USER->id);
// if (empty($userroles)) {
//     echo "No roles assigned at system context.<br>";
// } else {
//     foreach ($userroles as $role) {
//         echo "Role at system context: " . $role->shortname . "<br>";
//     }
// }
// die();
require_login();
if (!is_siteadmin()) {
     print_error('nopermissions', 'error', '', 'You must be an admin to view this page.');
     die();
} 

// if (has_capability('local/registration_validation:view', context_system::instance())) {
//     echo "User HAS the capability.<br>";
// } else {
//     echo "User DOES NOT have the capability.<br>";
// }
// require_capability('local/registration_validation:view', \context_system::instance());


$PAGE->set_url(new moodle_url('/local/registration_validation/view.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Registration Entries');
$PAGE->set_heading('Registration Entries');

echo $OUTPUT->header();

require_once($CFG->libdir . '/tablelib.php');

// Pagination setup
$perpage = optional_param('perpage', 5, PARAM_INT);
$page = optional_param('page', 0, PARAM_INT);
$start = $page * $perpage;

// Setup table
$table = new flexible_table('registration_entries');
$table->define_baseurl(new moodle_url($PAGE->url, ['perpage' => $perpage]));
$table->define_columns(['id', 'username', 'firstname', 'lastname', 'gender', 'department', 'professional_status', 'actions']);
$table->define_headers(['ID', 'Username', 'First Name', 'Last Name', 'Gender', 'Department', 'Status', 'Actions']);
$table->sortable(true, 'id', SORT_DESC);
$table->pageable(true);
$table->set_attribute('class', 'generaltable generalbox');
$table->setup();

// Fetch data
$totalcount = $DB->count_records('local_registration_users');
$entries = $DB->get_records('local_registration_users', [], '', '*', $start, $perpage);

// Populate table rows
foreach ($entries as $entry) {
    $status = $entry->status ?? 'pending';

    if ($status === 'pending') {
        $actions = html_writer::tag('button', 'Approve', [
            'class' => 'btn btn-success btn-sm action-button',
            'data-id' => $entry->id,
            'data-action' => 'approve'
        ]) . ' ';

        $actions .= html_writer::tag('button', 'Reject', [
            'class' => 'btn btn-danger btn-sm action-button',
            'data-id' => $entry->id,
            'data-action' => 'reject'
        ]);
    } else {
        $actions = ucfirst($status);
    }

    $table->add_data([
        $entry->id,
        s($entry->username),
        s($entry->firstname),
        s($entry->lastname),
        s($entry->gender),
        s($entry->department),
        ucfirst($status),
        $actions
    ]);
}

// Render table and pagination
$table->finish_output();
echo $OUTPUT->paging_bar($totalcount, $page, $perpage, $PAGE->url);

// AJAX script
echo '
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".action-button").forEach(function(button) {
        button.addEventListener("click", function() {
            const entryId = this.getAttribute("data-id");
            const action = this.getAttribute("data-action");

            fetch("ajax_action.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "id=" + entryId + "&action=" + action + "&sesskey=' . sesskey() . '"
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.parentElement.innerHTML = data.status;
                } else {
                    alert("Error: " + data.message);
                }
            });
        });
    });
});
</script>';

echo $OUTPUT->footer();
