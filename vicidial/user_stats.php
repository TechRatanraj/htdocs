<?php
# user_stats.php

 $startMS = microtime();

require("dbconnect_mysqli.php");
require("functions.php");

 $report_name = 'User Stats';
 $db_source = 'M';

 $firstlastname_display_user_stats = 0;
 $add_copy_disabled = 0;
if (file_exists('options.php')) {
    require('options.php');
}

 $PHP_AUTH_USER = $_SERVER['PHP_AUTH_USER'];
 $PHP_AUTH_PW = $_SERVER['PHP_AUTH_PW'];
 $PHP_SELF = $_SERVER['PHP_SELF'];
 $PHP_SELF = preg_replace('/\.php.*/i', '.php', $PHP_SELF);
if (isset($_GET["did_id"])) { $did_id = $_GET["did_id"]; }
elseif (isset($_POST["did_id"])) { $did_id = $_POST["did_id"]; }
if (isset($_GET["did"])) { $did = $_GET["did"]; }
elseif (isset($_POST["did"])) { $did = $_POST["did"]; }
if (isset($_GET["begin_date"])) { $begin_date = $_GET["begin_date"]; }
elseif (isset($_POST["begin_date"])) { $begin_date = $_POST["begin_date"]; }
if (isset($_GET["end_date"])) { $end_date = $_GET["end_date"]; }
elseif (isset($_POST["end_date"])) { $end_date = $_POST["end_date"]; }
if (isset($_GET["user"])) { $user = $_GET["user"]; }
elseif (isset($_POST["user"])) { $user = $_POST["user"]; }
if (isset($_GET["call_status"])) { $call_status = $_GET["call_status"]; }
elseif (isset($_POST["call_status"])) { $call_status = $_POST["call_status"]; }
if (isset($_GET["DB"])) { $DB = $_GET["DB"]; }
elseif (isset($_POST["DB"])) { $DB = $_POST["DB"]; }
if (isset($_GET["submit"])) { $submit = $_GET["submit"]; }
elseif (isset($_POST["submit"])) { $submit = $_POST["submit"]; }
if (isset($_GET["SUBMIT"])) { $SUBMIT = $_GET["SUBMIT"]; }
elseif (isset($_POST["SUBMIT"])) { $SUBMIT = $_POST["SUBMIT"]; }
if (isset($_GET["file_download"])) { $file_download = $_GET["file_download"]; }
elseif (isset($_POST["file_download"])) { $file_download = $_POST["file_download"]; }
if (isset($_GET["pause_code_rpt"])) { $pause_code_rpt = $_GET["pause_code_rpt"]; }
elseif (isset($_POST["pause_code_rpt"])) { $pause_code_rpt = $_POST["pause_code_rpt"]; }
if (isset($_GET["park_rpt"])) { $park_rpt = $_GET["park_rpt"]; }
elseif (isset($_POST["park_rpt"])) { $park_rpt = $_POST["park_rpt"]; }
if (isset($_GET["search_archived_data"])) { $search_archived_data = $_GET["search_archived_data"]; }
elseif (isset($_POST["search_archived_data"])) { $search_archived_data = $_POST["search_archived_data"]; }
if (isset($_GET["NVAuser"])) { $NVAuser = $_GET["NVAuser"]; }
elseif (isset($_POST["NVAuser"])) { $NVAuser = $_POST["NVAuser"]; }

 $DB = preg_replace("/[^0-9a-zA-Z]/", "", $DB);

 $STARTtime = date("U");
 $TODAY = date("Y-m-d");

if (!isset($begin_date) or (strlen($begin_date) < 10)) { $begin_date = $TODAY; }
if (!isset($end_date) or (strlen($end_date) < 10)) { $end_date = $TODAY; }

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
 $stmt = "SELECT use_non_latin,outbound_autodial_active,slave_db_server,reports_use_slave_db,user_territories_active,webroot_writable,allow_emails,level_8_disable_add,enable_languages,language_method,log_recording_access,admin_screen_colors,mute_recordings,allow_web_debug,hopper_hold_inserts FROM system_settings;";
 $rslt = mysql_to_mysqli($stmt, $link);
#if ($DB) {$MAIN.="$stmt\n";}
 $qm_conf_ct = mysqli_num_rows($rslt);
if ($qm_conf_ct > 0) {
    $row = mysqli_fetch_row($rslt);
    $non_latin = $row[0];
    $SSoutbound_autodial_active = $row[1];
    $slave_db_server = $row[2];
    $reports_use_slave_db = $row[3];
    $user_territories_active = $row[4];
    $webroot_writable = $row[5];
    $allow_emails = $row[6];
    $SSlevel_8_disable_add = $row[7];
    $SSenable_languages = $row[8];
    $SSlanguage_method = $row[9];
    $log_recording_access = $row[10];
    $SSadmin_screen_colors = $row[11];
    $SSmute_recordings = $row[12];
    $SSallow_web_debug = $row[13];
    $SShopper_hold_inserts = $row[14];
}
if ($SSallow_web_debug < 1) { $DB = 0; }
##### END SETTINGS LOOKUP #####
###########################################

### ARCHIVED DATA CHECK CONFIGURATION
 $archives_available = "N";
 $log_tables_array = array("vicidial_log", "vicidial_agent_log", "vicidial_closer_log", "vicidial_user_log", "vicidial_timeclock_log", "vicidial_user_closer_log", "vicidial_email_log", "call_log", "recording_log", "user_call_log", "vicidial_lead_search_log", "vicidial_agent_skip_log", "vicidial_agent_visibility_log");
for ($t = 0; $t < count($log_tables_array); $t++) {
    $table_name = $log_tables_array[$t];
    $archive_table_name = use_archive_table($table_name);
    if ($archive_table_name != $table_name) { $archives_available = "Y"; }
}

if ($search_archived_data) {
    $vicidial_closer_log_table = use_archive_table("vicidial_closer_log");
    $vicidial_user_log_table = use_archive_table("vicidial_user_log");
    $vicidial_agent_log_table = use_archive_table("vicidial_agent_log");
    $vicidial_agent_visibility_log_table = "vicidial_agent_visibility_log";
    $vicidial_timeclock_log_table = use_archive_table("vicidial_timeclock_log");
    $vicidial_user_closer_log_table = use_archive_table("vicidial_user_closer_log");
    $vicidial_email_log_table = use_archive_table("vicidial_email_log");
    $recording_log_table = use_archive_table("recording_log");
    $user_call_log_table = use_archive_table("user_call_log");
    $vicidial_lead_search_log_table = use_archive_table("vicidial_lead_search_log");
    $vicidial_agent_skip_log_table = use_archive_table("vicidial_agent_skip_log");
    $vicidial_agent_function_log_table = use_archive_table("vicidial_agent_function_log");
    $call_log_table = use_archive_table("call_log");
    $vicidial_log_table = use_archive_table("vicidial_log");
    $vicidial_hci_log_table = use_archive_table("vicidial_hci_log");
} else {
    $vicidial_closer_log_table = "vicidial_closer_log";
    $vicidial_user_log_table = "vicidial_user_log";
    $vicidial_agent_log_table = "vicidial_agent_log";
    $vicidial_agent_visibility_log_table = "vicidial_agent_visibility_log";
    $vicidial_timeclock_log_table = "vicidial_timeclock_log";
    $vicidial_user_closer_log_table = "vicidial_user_closer_log";
    $vicidial_email_log_table = "vicidial_email_log";
    $recording_log_table = "recording_log";
    $user_call_log_table = "user_call_log";
    $vicidial_lead_search_log_table = "vicidial_lead_search_log";
    $vicidial_agent_skip_log_table = "vicidial_agent_skip_log";
    $vicidial_agent_function_log_table = "vicidial_agent_function_log";
    $call_log_table = "call_log";
    $vicidial_log_table = "vicidial_log";
    $vicidial_hci_log_table = "vicidial_hci_log";
}
#############

 $did_id = preg_replace('/[^-\+\_0-9a-zA-Z]/', "", $did_id);
 $did = preg_replace('/[^-\+\_0-9a-zA-Z]/', "", $did);
 $begin_date = preg_replace('/[^- \:\_0-9a-zA-Z]/', '', $begin_date);
 $end_date = preg_replace('/[^- \:\_0-9a-zA-Z]/', '', $end_date);
 $file_download = preg_replace('/[^-_0-9a-zA-Z]/', '', $file_download);
 $pause_code_rpt = preg_replace('/[^-_0-9a-zA-Z]/', '', $pause_code_rpt);
 $park_rpt = preg_replace('/[^-_0-9a-zA-Z]/', '', $park_rpt);
 $search_archived_data = preg_replace('/[^-_0-9a-zA-Z]/', '', $search_archived_data);
 $submit = preg_replace('/[^-_0-9a-zA-Z]/', '', $submit);
 $SUBMIT = preg_replace('/[^-_0-9a-zA-Z]/', '', $SUBMIT);

if ($non_latin < 1) {
    $PHP_AUTH_USER = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_USER);
    $PHP_AUTH_PW = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_PW);
    $NVAuser = preg_replace('/[^-_0-9a-zA-Z]/', ',', $NVAuser);
    $user = preg_replace('/[^-_0-9a-zA-Z]/', '', $user);
    $call_status = preg_replace('/[^-_0-9a-zA-Z]/', '', $call_status);
} else {
    $PHP_AUTH_USER = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_USER);
    $PHP_AUTH_PW = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_PW);
    $NVAuser = preg_replace('/[^-_0-9\p{L}]/u', ',', $NVAuser);
    $user = preg_replace('/[^-_0-9\p{L}]/u', '', $user);
    $call_status = preg_replace('/[^-_0-9\p{L}]/u', '', $call_status);
}

if ($call_status != "") {
    $query_call_status = "and status='$call_status'";
    $VLquery_call_status = "and vlog.status='$call_status'";
} else {
    $query_call_status = '';
    $VLquery_call_status = '';
}

 $CS_vicidial_id_list = '';
 $CS_vicidial_id_list_SQL = '';

 $stmt = "SELECT selected_language from vicidial_users where user='$PHP_AUTH_USER';";
if ($DB) { echo "|$stmt|\n"; }
 $rslt = mysql_to_mysqli($stmt, $link);
 $sl_ct = mysqli_num_rows($rslt);
if ($sl_ct > 0) {
    $row = mysqli_fetch_row($rslt);
    $VUselected_language = $row[0];
}

 $auth = 0;
 $reports_auth = 0;
 $admin_auth = 0;
 $auth_message = user_authorization($PHP_AUTH_USER, $PHP_AUTH_PW, 'REPORTS', 1, 0);
if (($auth_message == 'GOOD') or ($auth_message == '2FA')) {
    $auth = 1;
    if ($auth_message == '2FA') {
        header("Content-type: text/html; charset=utf-8");
        echo _QXZ("Your session is expired") . "<a href=\"admin.php\">" . _QXZ("Click here to log in") . "</a>.\n";
        exit;
    }
}

if ($auth > 0) {
    $stmt = "SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and user_level > 7 and view_reports='1';";
    if ($DB) { echo "|$stmt|\n"; }
    $rslt = mysql_to_mysqli($stmt, $link);
    $row = mysqli_fetch_row($rslt);
    $admin_auth = $row[0];

    $stmt = "SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and user_level > 6 and view_reports='1';";
    if ($DB) { echo "|$stmt|\n"; }
    $rslt = mysql_to_mysqli($stmt, $link);
    $row = mysqli_fetch_row($rslt);
    $reports_auth = $row[0];

    if ($reports_auth < 1) {
        $VDdisplayMESSAGE = _QXZ("You are not allowed to view reports");
        Header("Content-type: text/html; charset=utf-8");
        echo "$VDdisplayMESSAGE: |$PHP_AUTH_USER|$auth_message|\n";
        exit;
    }
    if (($reports_auth > 0) and ($admin_auth < 1)) {
        $ADD = 999999;
        $reports_only_user = 1;
    }
}

require("screen_colors.php");

 $Mhead_color = $SSstd_row5_background;
 $Mmain_bgcolor = $SSmenu_background;
 $Mhead_color = $SSstd_row5_background;

 $date = date("r");
 $ip = getenv("REMOTE_ADDR");
 $browser = getenv("HTTP_USER_AGENT");

##### BEGIN log visit to vicidial_report_log table #####
 $LOGip = getenv("REMOTE_ADDR");
 $LOGbrowser = getenv("HTTP_USER_AGENT");
 $LOGscript_name = getenv("SCRIPT_NAME");
 $LOGserver_name = getenv("SERVER_NAME");
 $LOGserver_port = getenv("SERVER_PORT");
 $LOGrequest_uri = getenv("REQUEST_URI");
 $LOGhttp_referer = getenv("HTTP_REFERER");
 $LOGbrowser = preg_replace("/<|>|\'|\"|\\\\/", "", $LOGbrowser);
 $LOGrequest_uri = preg_replace("/<|>|\'|\"|\\\\/", "", $LOGrequest_uri);
 $LOGhttp_referer = preg_replace("/<|>|\'|\"|\\\\/", "", $LOGhttp_referer);
if (preg_match("/443/i", $LOGserver_port)) { $HTTPprotocol = 'https://'; }
    else { $HTTPprotocol = 'http://'; }
if (($LOGserver_port == '80') or ($LOGserver_port == '443')) { $LOGserver_port = ''; }
    else { $LOGserver_port = ":$LOGserver_port"; }
 $LOGfull_url = "$HTTPprotocol$LOGserver_name$LOGserver_port$LOGrequest_uri";

 $LOGhostname = php_uname('n');
if (strlen($LOGhostname) < 1) { $LOGhostname = 'X'; }
if (strlen($LOGserver_name) < 1) { $LOGserver_name = 'X'; }

 $stmt = "SELECT webserver_id FROM vicidial_webservers where webserver='$LOGserver_name' and hostname='$LOGhostname' LIMIT 1;";
 $rslt = mysql_to_mysqli($stmt, $link);
if ($DB) { echo "$stmt\n"; }
 $webserver_id_ct = mysqli_num_rows($rslt);
if ($webserver_id_ct > 0) {
    $row = mysqli_fetch_row($rslt);
    $webserver_id = $row[0];
} else {
    ##### insert webserver entry
    $stmt = "INSERT INTO vicidial_webservers (webserver,hostname) values('$LOGserver_name','$LOGhostname');";
    if ($DB) { echo "$stmt\n"; }
    $rslt = mysql_to_mysqli($stmt, $link);
    $affected_rows = mysqli_affected_rows($link);
    $webserver_id = mysqli_insert_id($link);
}

 $stmt = "INSERT INTO vicidial_report_log set event_date=NOW(), user='$PHP_AUTH_USER', ip_address='$LOGip', report_name='$report_name', browser='$LOGbrowser', referer='$LOGhttp_referer', notes='$LOGserver_name:$LOGserver_port $LOGscript_name | $user, $query_date, $end_date, $call_status, $shift, $file_download, $report_display_type|', url='$LOGfull_url', webserver='$webserver_id';";
if ($DB) { echo "|$stmt|\n"; }
 $rslt = mysql_to_mysqli($stmt, $link);
 $report_log_id = mysqli_insert_id($link);
##### END log visit to vicidial_report_log table #####

if (strlen($slave_db_server) > 5) and (preg_match("/$report_name/", $reports_use_slave_db)) ) {
    mysqli_close($link);
    $use_slave_server = 1;
    $db_source = 'S';
    require("dbconnect_mysqli.php");
    $MAIN .= "<!-- Using slave server $slave_db_server $db_source -->\n";
}

 $stmt = "SELECT full_name,user_group,admin_hide_lead_data,admin_hide_phone_data from vicidial_users where user='$PHP_AUTH_USER';";
 $rslt = mysql_to_mysqli($stmt, $link);
 $row = mysqli_fetch_row($rslt);
 $LOGfullname = $row[0];
 $LOGuser_group = $row[1];
 $LOGadmin_hide_lead_data = $row[2];
 $LOGadmin_hide_phone_data = $row[3];

 $stmt = "SELECT allowed_campaigns,allowed_reports,admin_viewable_groups from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) { $MAIN .= "|$stmt|\n"; }
 $rslt = mysql_to_mysqli($stmt, $link);
 $row = mysqli_fetch_row($rslt);
 $LOGallowed_campaigns = $row[0];
 $LOGallowed_reports = $row[1];
 $LOGadmin_viewable_groups = $row[2];
 $LOGadmin_viewable_groupsSQL = '';
 $vuLOGadmin_viewable_groupsSQL = '';
 $whereLOGadmin_viewable_groupsSQL = '';
if (!preg_match('/\-\-ALL\-\-/i', $LOGadmin_viewable_groups) and (strlen($LOGadmin_viewable_groups) > 3)) {
    $rawLOGadmin_viewable_groupsSQL = preg_replace("/ -/", '', $LOGadmin_viewable_groupsSQL);
    $rawLOGadmin_viewable_groupsSQL = preg_replace("/ /", ',', $rawLOGadmin_viewable_groupsSQL);
    $LOGadmin_viewable_groupsSQL = "and user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
    $whereLOGadmin_viewable_groupsSQL = "where user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
    $vuLOGadmin_viewable_groupsSQL = "and vicidial_users.user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";

if (strlen($user) > 0) {
    if ($did > 0) {
        $stmt = "SELECT count(*) from vicidial_inbound_dids where did_pattern='$user' $LOGadmin_viewable_groupsSQL;";
        $rslt = mysql_to_mysqli($stmt, $link);
        $row = mysqli_fetch_row($rslt);
        $allowed_count = $row[0];
    } else {
        $stmt = "SELECT count(*) from vicidial_users where user='$user' $LOGadmin_viewable_groupsSQL;";
        $rslt = mysql_to_mysqli($stmt, $link);
        $row = mysqli_fetch_row($rslt);
        $allowed_count = $row[0];
    }

    if ($allowed_count < 1) {
        echo _QXZ("This user does not exist") . ": |$PHP_AUTH_USER|$user|\n";
        exit;
    }
}

if (!preg_match("/$report_name/", $LOGallowed_reports) and !preg_match("/ALL REPORTS/", $LOGallowed_reports)) {
    Header("WWW-Authenticate: Basic realm=\"CONTACT-CENTER-ADMIN\"");
    Header("HTTP/1.0 401 Unauthorized");
    echo _QXZ("You are not allowed to view this report") . ": |$PHP_AUTH_USER|$report_name|\n";
    exit;
}

if ($did > 0) {
    $stmt = "SELECT did_description from vicidial_inbound_dids where did_pattern='$user' $LOGadmin_viewable_groupsSQL;";
    $rslt = mysql_to_mysqli($stmt, $link);
    $row = mysqli_fetch_row($rslt);
    $full_name = $row[0];
} else {
    $stmt = "SELECT full_name from vicidial_users where user='$user' $LOGadmin_viewable_groupsSQL;";
    $rslt = mysql_to_mysqli($stmt, $link);
    $row = mysqli_fetch_row($rtd);
    $full_name = $row[0];
}

 $HEADER .= "<!DOCTYPE html>\n";
 $HEADER .= "<html lang='en'>\n";
 $HEADER .= "<head>\n";
 $HEADER .= "<meta charset='UTF-8'>\n";
 $HEADER .= "<meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
 $HEADER .= "<title>" . _QXZ("ADMINISTRATION") . ": . _QXZ("$report_name") . "</title>\n";
 $HEADER .= "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>\n";
 $HEADER .= "<link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' rel='stylesheet'>\n";
 $HEADER .= "<style>\n";
 $HEADER .= "body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; margin: 0; padding: 0; }\n";
 $HEADER .= ".navbar { background: linear-gradient(135deg, #4a6cf7 0%, #2575fc 100%); color: white; padding: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }\n";
 $HEADER .= ".navbar-brand { color: #fff; font-weight: 500; font-size: 1.5rem; }\n";
 $HEADER .= ".navbar-nav { margin-right: 1rem; }\n";
 $HEADER .= ".nav-link { color: rgba(255,255,255,0.8); font-weight: 500; padding: 0.5rem 1rem; border-radius: 8px; transition: all 0.3s ease; }\n";
 $HEADER .= ".nav-link:hover { background-color: rgba(255,255,255,0.2); color: #fff; }\n";
 $HEADER .= ".nav-link.active { background: linear-gradient(135deg, #4a6cf7 0%, #2575fc 100%); color: white; }\n";
 $HEADER .= ".sidebar { background: #343a40; height: calc(100vh - 80px); position: sticky; top: 80px; overflow-y: auto; transition: all 0.3s ease; z-index: 1000; }\n";
 $HEADER .= ".sidebar.collapsed { transform: translateX(-100%); }\n";
 $HEADER .= ".main-content { margin-left: 320px; padding: 2rem; transition: all 0.3s ease; }\n";
 $HEADER .= ".main-content.collapsed { margin-left: 0; }\n";
 $HEADER .= ".card { border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); margin-bottom: 1.5rem; background: white; }\n";
 $HEADER .= ".card-header { background: linear-gradient(135deg, #4a6cf7 0%, #2575fc 100%); color: white; padding: 1rem; border-radius: 8px 8px 0 0; border-bottom: 1px solid #e9ecef; font-weight: 600; }\n";
 $HEADER .= ".card-title { color: #495057; font-size: 1.25rem; font-weight: 600; }\n";
 $HEADER .= ".form-group { margin-bottom: 1rem; }\n";
 $HEADER .= ".form-label { font-weight: 500; color: #495057; margin-bottom: 0.5rem; }\n";
 $HEADER .= ".form-control { border-radius: 8px; border: 1px solid #ced4da; padding: 0.75rem; transition: all 0.3s ease; }\n";
 $HEADER .= ".form-control:focus { border-color: #4a6cf7; box-shadow: 0 0 0 0.2rem rgba(74, 108, 247, 0.15); }\n";
 $HEADER .= ".btn { border-radius: 8px; padding: 0.75rem 1rem; font-weight: 500; transition: all 0.3s ease; }\n";
 $HEADER .= ".btn-primary { background: linear-gradient(135deg, #4a6cf7 0%, #2575fc 100%); border: none; color: white; }\n";
 $HEADER .= ".btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(74, 108, 247, 0.3); }\n";
 $HEADER .= ".btn-secondary { background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%); border: none; color: white; }\n";
 $HEADER .= ".btn-secondary:hover { background: linear-gradient(135deg, #5a6268 100%); border: none; color: white; }\n";
 $HEADER .= ".btn-danger { background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); border: none; color: white; }\n";
 $HEADER .= ".btn-danger:hover { background: linear-gradient(135deg, #dc3545 0%, #e53e3e 100%); border: none; color: white; }\n";
 $HEADER .= ".btn-info { background: linear-gradient(135deg, #38b2ac 0%, #319795 100%); border: none; color: white; }\n";
 $HEADER .= ".btn-info:hover { background: linear-gradient(135deg, #319795 100%); border: none; color: white; }\n";
 $HEADER .= ".stats-table { width: 100%; border-collapse: collapse; margin-top: 1rem; }\n";
 $HEADER .= ".stats-table th { background: #f8f9fa; color: #495057; font-weight: 600; text-align: left; padding: 0.75rem; }\n";
 $HEADER .= ".stats-table td { padding: 0.75rem; border-bottom: 1px solid #e9ecef; }\n";
 $HEADER .= ".stats-table tr:hover { background-color: #f1f3f4; }\n";
 $HEADER .= ".dataTables_wrapper { overflow-x: auto; }\n";
 $HEADER .= ".download-section { text-align: center; margin: 2rem; }\n";
 $HEADER .= ".download-btn { margin: 0.5rem; }\n";
 $HEADER .= "</style>\n";
 $HEADER .= "</head>\n";
 $HEADER .= "<body>\n";
 $HEADER .= "<div class='sidebar'>\n";
 $HEADER .= "<div class='sidebar-toggle'><i class='fas fa-bars'></i></div>\n";
 $HEADER .= "<div class='sidebar-content'>\n";
 $HEADER .= "<div class='main-content'>\n";

// Navigation Tabs
 $HEADER .= "<div class='nav-tabs'>\n";
 $HEADER .= "<div class='nav-item'><a href='#basic-stats' class='nav-link active' data-tab='basic-stats'>" . _QXZ("Basic Stats") . "</a></div>\n";
 $HEADER .= "<div class='nav-item'><a href='#call-stats' class='nav-link' data-tab='call-stats'>" . _QXZ("Call Stats") . "</a></div>\n";
 $HEADER .= "<div class='nav-item'><a href='#activity-stats' class='nav-link' data-tab='activity-stats'>" . _QXZ("Activity Stats") . "</a></div>\n";
 $HEADER .= "<div class='nav-item'><a href='#recordings-stats' class='nav-link' data-tab='recordings-stats'>" . _QXZ("Recordings") . "</a></div></div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "<div class='tab-content active' id='basic-stats'>\n";
 $HEADER .= "<div class='card mb-3'>\n";
 $HEADER .= "<h5><i class='fas fa-user me-2'></i>" . _QXZ("Basic User Information") . "</h5>\n";
 $HEADER .= "<div class='form-group row'>\n";
 $HEADER .= "<label class='col-sm-3 col-form-label'>" . _QXZ("User") . ":</label>\n";
 $HEADER .= "<div class='col-sm-9'>\n";
 $HEADER .= "<input type='text' class='form-control' value='$user' readonly>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "<div class='form-group row'>\n";
 $HEADER .= "<label class='col-sm-3 col-form-label'>" . _QXZ("Full Name") . ":</label>\n";
 $HEADER .= "<div class='col-sm-9'>\n";
 $HEADER .= "<input type='text' class='form-control' value='$full_name'>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "<div class='form-group row'>\n";
 $HEADER .= "<label class='col-sm-3 col-form-label'>" . _QXZ("User Level") . ":</label>\n";
 $HEADER .= "<div class='col-sm-9'>\n";
 $HEADER .= "<select class='form-control' name='user_level'>\n";
for ($h = 1; $h <= $LOGuser_level; $h++) {
    $selected = ($h == $user_level) ? 'selected' : '';
    echo "<option value='$h' $selected>" . _QXZ("Level $h") . "</option>\n";
}
 $HEADER .= "</select>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "<div class='form-group row'>\n";
 $HEADER .= "<label class='col-sm-3 col-form-label'>" . _QXZ("User Group") . ":</label>\n";
 $HEADER .= "<div class='col-sm-9'>\n";
 $HEADER .= "<select class='form-control' name='user_group'>\n";
 $stmt = "SELECT user_group,group_name from vicidial_user_groups $whereLOGadmin_viewable_groupsSQL order by user_group;";
 $rslt = mysql_to_mysqli($stmt, $link);
 $Ugroups_to_print = mysqli_num_rows($rslt);
 $o = 0;
while ($Ugroups_to_print > $o) {
    $rowx = mysqli_fetch_row($rslt);
    echo "<option value=\"$rowx[0]\" " . ($rowx[0] == $user_group ? 'selected' : '') . ">$rowx[0] - $rowx[1]</option>\n";
    $o++;
}
 $HEADER .= "</select>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "<div class='form-group row'>\n";
 $HEADER .= "<label class='col-sm-3 col-form-label'>" . _QXZ("Email") . ":</label>\n";
 $HEADER .= "<div class='col-sm-9'>\n";
 $HEADER .= "<input type='email' class='form-control' value='$email'>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "<div class='form-group row'>\n";
 $HEADER .= "<label class='col-sm-3 col-form-label'>" . _QXZ("Mobile Number") . ":</label>\n";
 $HEADER .= "<div class='col-sm-9'>\n";
 $HEADER .= "<input type='tel' class='form-control' value='$mobile_number'>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "<div class='form-group row'>\n";
 $HEADER .= "<label class='col-sm-3 col-form-label'>" . _QXZ("Status") . ":</label>\n";
 $HEADER .= "<div class='col-sm-9'>\n";
 $HEADER .= "<select class='form-control' name='active'>\n";
 $HEADER .= "<option value='Y' " . ($active == 'Y' ? 'selected' : '') . ">" . _QXZ("Active") . "</option>\n";
 $HEADER .= "<option value='N' " . ($active == 'N' ? 'selected' : '') . ">" . _QXZ("Inactive") . "</option>\n";
 $HEADER .= "</select>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "<div class='form-group row'>\n";
 $HEADER .= "<label class='col-sm-3 col-form-label'>" . _QXZ("Date Range") . ":</label>\n";
 $HEADER .= "<div class='col-sm-4'>\n";
 $HEADER .= "<input type='date' class='form-control' name='begin_date' value='$begin_date'>\n";
 $HEADER .= "<span class='input-group-text'>" . _QXZ("to") . "</span>\n";
 $HEADER .= "<input type='date' class='form-control' name='end_date' value='$end_date'>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "<div class='form-group row'>\n";
 $HEADER .= "<label class='col-sm-3 col-form-label'>" . _QXZ("Call Status") . ":</label>\n";
 $HEADER .= "<div class='col-sm-4'>\n";
 $HEADER .= "<select class='form-control' name='call_status'>\n";
 $HEADER .= "<option value=''>" . _QXZ("All") . "</option>\n";
if ($call_status != "") {
    echo "<option value='$call_status' SELECTED>" . _QXZ($call_status) . "</option>\n";
 $HEADER .= "</select>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "<div class='form-group row'>\n";
 $HEADER .= "<div class='col-sm-3 col-form-label'></div>\n";
 $HEADER .= "<div class='col-sm-9'>\n";
 $HEADER .= "<button type='submit' class='btn btn-primary btn-sm' onclick='generateBasicStats()'>" . _QXZ("Generate Report") . "</button>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "<div class='tab-content' id='call-stats'>\n";
 $HEADER .= "<div class='card mb-3'>\n";
 $HEADER .= "<h5><i class='fas fa-phone me-2'></i>" . _QXZ("Call Statistics") . "</h5>\n";
 $HEADER .= "<div class='form-group row'>\n";
 $HEADER .= "<label class='col-sm-3 col-form-label'>" . _QXZ("Call Status") . ":</label>\n";
 $HEADER .= "<div class='col-sm-9'>\n";
 $HEADER .= "<select class='form-control' name='call_status_filter'>\n";
 $HEADER .= "<option value=''>" . _QXZ("All") . "</option>\n";
if ($call_status != "") {
    echo "<option value='$call_status' SELECTED>" . _QXZ($call_status) . "</option>\n";
 $HEADER .= "</select>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "<div class='form-group row'>\n";
 $HEADER .= "<div class='col-sm-3 col-form-label'></div>\n";
 $HEADER .= "<div class='col-sm-9'>\n";
 $HEADER .= "<button type='submit' class='btn btn-primary btn-sm' onclick='generateCallStats()'>" . _QXZ("Generate Report") . "</button>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "<div class='tab-content' id='activity-stats'>\n";
 $HEADER .= "<div class='card mb-3'>\n";
 $HEADER .= "<h5><i class='fa-chart-line me-2'></i>" . _QXZ("Activity Statistics") . "</h5>\n";
 $HEADER .= "<div class='form-group row'>\n";
 $HEADER .= "<label class='col-sm-3 col-form-label'>" . _QXZ("Activity Type") . ":</label>\n";
 $HEADER .= "<div class='col-sm-9'>\n";
 $HEADER .= "<select class='form-control' name='activity_type'>\n";
 $HEADER .= "<option value='LOGIN'>" . _QXZ("Login/Logout") . "</option>\n";
 $HEADER .= "<option value='PAUSE'>" . _QXZ("Pause") . "</option>\n";
 $HEADER .= "<option value='LOGOUT'" . _QXZ("Logout") . "</option>\n";
 $HEADER .= "<option value='TIMEOUTLOGOUT') " . _QXX("Timeout Logout") . "</option>\n";
 $HEADER .= "<option value='TIMEOUT' " _QXZ("Logout") . "</option>\n";
 $HEADER .= "<option value='LOGOUT' " . _QXZ("Logout") . "</option>\n";
if ($activity_type == 'LOGIN') {
    echo "<option value='LOGIN' SELECTED>" . _QXZ("Login/Logout") . "</option>\n";
} elseif ($activity_type == 'PAUSE') {
    echo "<option value='PAUSE' SELECTED>" . _QXZ("Pause") . "</option>\n";
} elseif ($activity_type == 'LOGOUT') {
    echo "<option value='LOGOUT' SELECTED>" . _QXZ("Logout") . "</option>\n";
} else {
    echo "<option value='TIMEOUT' SELECTED>" . _QXZ("Logout") . "</option>\n";
}
 $HEADER .= "</select>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "<div class='tab-content' id='recordings'>\n";
 $HEADER .= "<div class='card mb-3'>\n";
 $HEADER .= "<h5><i class='fas fa-microphone me-2'></i>" . _QXZ("Recording Statistics") . "</h5>\n";
 $HEADER .= "<div class='form-group row'>\n";
 $HEADER .= "<label class='col-sm-3 col-form-label'>" . _QXZ("Recording Period") . ":</label>\n";
 $HEADER .= "<div class='col-sm-9'>\n";
 $HEADER .= "<select class='form-control' name='recording_period'>\n";
 $HEADER .= "<option value='TODAY'>" . _QXZ("Today") . "</option>\n";
 $HEADER .= "<option value='WEEK'>" . _QXZ("This Week") . "</option>\n";
 $HEADER .= "<option value='WEEK'" . _QXZ("Last Week") . "</option>\n";
 $HEADER .= "<option value='MONTH'>" . _QXZ("This Month") . "</option>\n";
 $HEADER .= "<option value='LAST'" . _QXZ("Last Month") . "</option>\n";
 $HEADER .= "<option value='LASTWEEK' " . _QX("Last Week") . "</option>\n";
 $HEADER .= "<option value='LASTMONTH' " . _QX("Last Month") . "</option>\n";
 $HEADER .= "<option value='ALLTIME' " . _QX("All Time") . "</option>\n";
if ($recording_period == 'TODAY') {
    echo "<option value='TODAY' SELECTED>" . _QXZ("Today") . "</option>\n";
} elseif ($recording_period == 'WEEK') {
    echo "<option value='WEEK' SELECTED>" . _QXZ("This Week") . "</option>\n";
} elseif ($recording_period == 'MONTH') {
    echo "<option value='MONTH' SELECTED>" . _QXZ("This Month") . "</option>\n";
} elseif ($recording_period == 'LASTWEEK') {
    echo "<option value='LASTWEEK' SELECTED>" . _QXZ("Last Week") . "</option>\n";
} elseif ($recording_period == 'LASTMONTH') {
    echo "<option value='LASTMONTH' SELECTED>" . _QXZ("Last Month") . "</option>\n";
} else {
    echo "<option value='ALLTIME' SELECTED>" . _QXZ("All Time") . "</option>\n";
}
 $HEADER .= "</select>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "<div class='form-group row'>\n";
 $HEADER .= "<label class='col-sm-3 col-form-label'></div>\n";
 $HEADER .= "<div class='col-sm-9'>\n";
 $HEADER .= "<button type='submit' class='btn btn-primary btn-sm' onclick='generateRecordingStats()'>" . _QXZ("Generate Report") . "</button>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>\n";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $HEADER .= "</div>";
 $ HEADER .= "</div>";
 $ HEADER = "</div>";
}

// Basic Stats Generation Function
function generateBasicStats() {
    const beginDate = document.querySelector('input[name=begin_date]').value;
    const endDate = document.querySelector('end_date').value;
    
    // Validate dates
    if (!beginDate || !endDate) {
        alert(_QXZ("Please select both start and end dates"));
        return;
    }
    
    const startDate = new Date(beginDate);
    const endDate = new Date(endDate);
    if (startDate > endDate) {
        alert(_QXZ("Start date cannot be after end date"));
        return;
    }
    
    // Build API URL with parameters
    const params = new URLSearchParams({
        user: user,
        begin_date: beginDate.toISOString().split('T')[0],
        end_date: endDate.toISOString().split('T')[0],
        file_download: '1'
    });
    
    const url = `$PHP_SELF?${params.toString()}`;
    
    // Create download link
    const downloadLink = document.createElement('a');
    downloadLink.href = url;
    downloadLink.className = 'btn btn btn-info btn-sm download-link';
    downloadLink.innerHTML = '<i class="fas fa-download"></i> ' . _QXZ("Download CSV");
    downloadLink.onclick = function(e) {
        e.preventDefault();
        const fileDownload = this.getAttribute('data-file');
        const csvContent = document.querySelector(`#csv-1`).textContent;
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `user_stats_basic_${fileDownload}.csv`;
        a.click();
    };
    
    downloadLink.setAttribute('data-file', '1');
    document.getElementById('download-links').appendChild(downloadLink);
}

// Call Stats Generation Function
function generateCallStats() {
    const beginDate = document.querySelector('input[name=begin_date]').value;
    const endDate = document.querySelector('end_date').value;
    
    // Validate dates
    if (!beginDate || !endDate) {
        alert(_QXZ("Please select both start and end dates"));
        return;
    }
    
    const startDate = new Date(beginDate);
    const endDate = new Date(endDate);
    if (startDate > endDate) {
        alert(_QXZ("Start date cannot be after end date"));
        return;
    }
    
    // Build API URL with parameters
    const params = new URLSearchParams({
        user: user,
        begin_date: startDate.toISOString().split('T')[0],
        end_date: endDate.toISOString().split('T')[0],
        file_download: '2'
    });
    
    const url = `$PHP_SELF?${params.toString()}`;
    
    // Create download link
    const downloadLink = document.createElement('a');
    downloadLink.href = url;
    downloadLink.className = 'btn btn-info btn-sm download-link';
    downloadLink.innerHTML = '<i class="fas fa-download"></i> ' . _QXZ("Download CSV");
    downloadLink.onclick = function(e) {
        e.preventDefault();
        const fileDownload = this.getAttribute('data-file');
        const csvContent = document.querySelector(`csv-2`).textContent;
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `user_stats_call_${fileDownload}.csv`;
        a.click();
    };
    
    downloadLink.setAttribute('data-file', '2');
    document.getElementById('download-links').appendChild(downloadLink);
}

// Activity Stats Generation Function
function generateActivityStats() {
    const beginDate = document.querySelector('input[name=begin_date]').value;
    const endDate = document.querySelector('end_date').value;
    
    // Validate dates
    if (!beginDate || !endDate) {
        alert(_QXZ("Please select both start and end dates"));
        return;
    }
    
    const startDate = new Date(beginDate);
    const endDate = new Date(endDate);
    if (startDate > endDate) {
        alert(_QXZ("Start date cannot be after end date"));
        return;
    }
    
    // Build API URL with parameters
    const params = new URLSearchParams({
        user: user,
        begin_date: startDate.toISOString().split('T')[0],
        end_date: endDate.toISOString().split('T')[0],
        file_download: '3'
    });
    
    const url = `$PHP_SELF?${params.toString()}`;
    
    // Create download link
    const downloadLink = document.createElement('a');
    downloadLink.href = url;
    downloadLink.className = 'btn btn-info btn-sm download-link';
    downloadLink.innerHTML = '<i class="fas fa-download"></i> ' . _QXZ("Download CSV");
    downloadLink.onclick = function(e) {
        e.preventDefault();
        const fileDownload = this.getAttribute('data-file');
        const csvContent = document.querySelector(`csv-3`).textContent;
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `user_stats_activity_${fileDownload}.csv`;
        a.click();
    };
    
    downloadLink.setAttribute('data-file', '3');
    document.getElementById('download-links').appendChild(downloadLink);
}

// Recording Stats Generation Function
function generateRecordingStats() {
    const beginDate = document.querySelector('input[name=begin_date]').value;
    const endDate = document.querySelector('end_date').value;
    
    // Validate dates
    if (!beginDate || !endDate) {
        alert(_QXZ("Please select both start and end dates"));
        return;
    }
    
    const startDate = new Date(beginDate);
    const endDate = new Date(endDate);
    if (startDate > endDate) {
        alert(_QXZ("Start date cannot be after end date"));
        return;
    }
    
    // Build API URL with parameters
    const params = new URLSearchParams({
        user: user,
        begin_date: startDate.toISOString().split('T')[0],
        end_date: endDate.toISOString().split('T')[0],
        file_download: '4'
    });
    
    const url = `$PHP_SELF?${params.toString()}`;
    
    // Create download link
    const downloadLink = document.createElement('a');
    downloadLink.href = url;
    downloadLink.className = 'btn btn-info btn-sm download-link';
    downloadLink.innerHTML = '<i class="fas fa-download"></i> ' . _QXZ("Download CSV");
    downloadLink.onclick = function(e) {
        e.preventDefault();
        const fileDownload = this.getAttribute('data-file');
        const csvContent = document.querySelector(`csv-4`).textContent;
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `user_stats_recordings_${fileDownload}.csv`;
        a.click();
    };
    
    downloadLink.setAttribute('data-file', '4');
    document.getElementById('download-links').appendChild(downloadLink);
}

// Main JavaScript
echo "<script>\n";
echo "// Tab switching functionality\n";
echo "document.addEventListener('DOMContentLoaded', function() {\n";
echo "  const tabLinks = document.querySelectorAll('.nav-link');\n";
echo "  const tabContents = document.querySelectorAll('.tab-content');\n";
echo "  \n";
echo "  tabLinks.forEach(link => {\n";
echo "    link.addEventListener('click', function(e) {\n";
echo "      e.preventDefault();\n";
echo "      const targetTab = this.getAttribute('data-tab');\n";
echo "      // Hide all tab contents\n";
echo "      tabContents.forEach(content => {\n";
echo "        content.classList.remove('active');\n";
echo "      });\n";
echo "      // Remove active class from all tab links\n";
echo "      tabLinks.forEach(link => {\n";
echo "        link.classList.remove('active');\n";
echo "      });\n";
echo "      \n";
echo "      // Show target tab content\n";
echo "      document.getElementById(targetTab).classList.add('active');\n";
echo "      // Add active class to clicked tab link\n";
echo "      this.classList.add('active');\n";
echo "    });\n";
echo "  });\n";
echo "  \n";
echo "  // Password visibility toggle\n";
echo "  const passwordToggles = document.querySelectorAll('.password-toggle');\n";
echo "  passwordToggles.forEach(toggle => {\n";
echo "    toggle.addEventListener('click', function() {\n";
echo "      const targetId = this.getAttribute('data-target');\n";
echo "      const passwordField = document.getElementById(targetId) || document.getElementsByName(targetId)[0];\n";
echo "      if (passwordField) {\n";
echo "        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';\n";
echo "        passwordField.setAttribute('type', type);\n";
echo "        this.classList.toggle('fa-eye');\n";
echo "        this.classList.toggle('fa-eye-slash');\n";
echo "      }\n";
echo "    });\n";
echo "  \n";
echo "  // Date picker initialization\n";
echo "  const dateInputs = document.querySelectorAll('input[type=\"date\"]');\n";
echo "  dateInputs.forEach(input => {\n";
echo "    input.addEventListener('change', function() {\n";
echo "      // Date validation logic\n";
echo "    });\n";
echo "  });\n");
echo "  \n";
echo "  // Responsive sidebar toggle\n";
echo "  const sidebar = document.querySelector('.sidebar');\n";
echo "  const mainContent = document.querySelector('.main-content');\n";
echo "  const toggleBtn = document.querySelector('.sidebar-toggle');\n";
echo "  if (toggleBtn) {\n";
echo "    toggleBtn.addEventListener('click', function() {\n";
echo "      const sidebar = document.querySelector('.sidebar');\n";
echo "      sidebar.classList.toggle('collapsed');\n";
echo "      const mainContent = document.querySelector('.main-content');\n";
echo "      mainContent.classList.toggle('collapsed');\n";
echo "      const isCollapsed = sidebar.classList.contains('collapsed');\n";
echo "      toggleBtn.innerHTML = isCollapsed ? '<i class=\"fas fa-bars\"></i>' : '<i class=\"fas fa-times\"></i>';\n";
echo "    });\n";
echo "  }\n";
echo "  \n";
echo "  // Download functionality\n";
echo "  const downloadLinks = document.querySelectorAll('.download-link');\n";
echo "  downloadLinks.forEach(link => {\n";
echo "    link.addEventListener('click', function(e) {\n";
echo "      e.preventDefault();\n";
echo "      const fileDownload = this.getAttribute('data-file');\n";
echo "      const csvContent = document.querySelector(`csv-${fileDownload}`)?.textContent;\n";
echo "      if (csvContent) {\n";
echo "        const blob = new Blob([csvContent], { type: 'text/csv' });\n";
echo "        const url = window.URL.createObjectURL(blob);\n";
echo "        const a = document.createElement('a');\n";
echo "        a.href = url;\n";
echo "        a.download = `user_stats_${fileDownload}.csv`;\n";
echo "        a.click();\n";
echo "      }\n");
echo "    });\n");
echo "  \n");
echo "</script>\n";
echo "</body>\n";
echo "</html>\n";
 $endMS = microtime();
 $runMS = ($endMS - $startMS);
echo "<!-- Script execution time: $runMS seconds -->\n";
?>