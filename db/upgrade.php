<?php
function xmldb_local_registration_validation_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2025052605) {

        $table = new xmldb_table('local_registration_users');

       if (!$dbman->field_exists($table, 'experience_start_date')) {
            $field = new xmldb_field('experience_start_date', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, 0);
            $dbman->add_field($table, $field);
        }

        // Add experience_expiry_date column
        if (!$dbman->field_exists($table, 'experience_expiry_date')) {
            $field = new xmldb_field('experience_expiry_date', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, 0);
            $dbman->add_field($table, $field);
        }

        // Savepoint to mark upgrade done
        upgrade_plugin_savepoint(true, 2025052605, 'local', 'registration_validation');
    }

    return true;
}

