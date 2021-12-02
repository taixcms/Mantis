<?php
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);


//fclose(STDIN);
//fclose(STDOUT);
//fclose(STDERR);
//$STDIN = fopen('/dev/null', 'r');
//$STDOUT = fopen('/home/support.qwertynetworks.com/logs/app.log', 'wb');
//$STDERR = fopen('/home/support.qwertynetworks.com/logs/errlog.log', 'wb');
//
//
//
//$contents = ob_get_contents();
//ob_end_clean();
//file_put_contents('/home/support.qwertynetworks.com/logs/errlog.log',$contents);
$g_hostname               = 'localhost';
$g_db_type                = 'mysqli';
$g_database_name          = '';
$g_db_username            = '';
$g_db_password            = '';
$g_default_language = 'russian';
$g_default_timezone       = 'UTC';
$g_custom_headers = array ('Content-Security-Policy:');
$g_crypto_master_salt     = 'mnBs5YQnhm8LRgS5F1iOJ8SDqmnkprevV9BI6YkkPQU=';

require_api( 'lang_api.php' );
$g_enable_email_notification = ON;
$g_phpMailer_method = 2;
$g_smtp_host = '';
$g_smtp_username = '';
$g_smtp_password = '';
$g_smtp_connection_mode = '';
$g_smtp_port = 465;

$g_administrator_email = '';
$g_webmaster_email = '';
$g_from_email = '';
$g_return_path_email = '';
$g_log_level = LOG_EMAIL | LOG_EMAIL_RECIPIENT | LOG_DATABASE;
$g_log_destination = '';
$g_from_name = 'Mantis Bug Tracker';
$g_allow_signup			= OFF;
$g_bug_report_page_fields = array(
    'additional_info',
    'attachments',
    'category_id',
    'due_date',
    'handler',
    'priority',
    'reproducibility',
    'severity',
    'steps_to_reproduce',
    'tags',
    'target_version',
    'view_state',
);

$g_bug_view_page_fields = array(
    'additional_info',
    'attachments',
    'category_id',
    'date_submitted',
    'description',
    'due_date',
    'eta',
    'fixed_in_version',
    'handler',
    'id',
    'last_updated',
    'priority',
    'project',
    'projection',
    'reporter',
    'reproducibility',
    'resolution',
    'severity',
    'status',
    'steps_to_reproduce',
    'summary',
    'tags',
    'target_version',
    'view_state',
);

$g_bug_update_page_fields = array(
    'additional_info',
    'category_id',
    'date_submitted',
    'description',
    'due_date',
    'eta',
    'fixed_in_version',
    'handler',
    'id',
    'last_updated',
    'priority',
    'project',
    'projection',
    'reporter',
    'reproducibility',
    'resolution',
    'severity',
    'status',
    'steps_to_reproduce',
    'summary',
    'target_version',
    'view_state',
);

$g_csv_columns = array(
    'id', 'project_id', 'reporter_id', 'handler_id', 'priority',
    'severity', 'reproducibility', 'version', 'projection', 'category_id',
    'date_submitted', 'eta', 'view_state',
    'last_updated', 'summary', 'status', 'resolution', 'fixed_in_version'
);

$g_excel_columns = array(
    'id', 'project_id', 'reporter_id', 'handler_id', 'priority', 'severity',
    'reproducibility', 'version', 'projection', 'category_id',
    'date_submitted', 'eta', 'view_state',
    'last_updated', 'summary', 'status', 'resolution', 'fixed_in_version'
);

 $g_main_menu_custom_options = array(
     array(
         'title'        =>  lang_get( 'task_schedule' ),
         'access_level' => ANYBODY,
         'url'          => 'usertabstat.php?userid=0&year='.date("Y").'&mount='.date("m"),
         'icon'         => 'fa-bar-chart-o'
    )
  );

$g_view_summary_threshold = 9999999;
$g_roadmap_view_threshold = 9999999;
$g_view_changelog_threshold = 9999999;