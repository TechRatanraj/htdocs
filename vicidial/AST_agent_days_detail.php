<?php
# AST_agent_days_detail.php - Modern Responsive UI Version

 $startMS = microtime();

require("dbconnect_mysqli.php");
require("functions.php");

 $PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
 $PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
 $PHP_SELF=$_SERVER['PHP_SELF'];
 $PHP_SELF = preg_replace('/\.php.*/i','.php',$PHP_SELF);
if (isset($_GET["query_date"]))                {$query_date=$_GET["query_date"];}
    elseif (isset($_POST["query_date"]))    {$query_date=$_POST["query_date"];}
if (isset($_GET["end_date"]))              {$end_date=$_GET["end_date"];}
    elseif (isset($_POST["end_date"]))      {$end_date=$_POST["end_date"];}
if (isset($_GET["group"]))                   {$group=$_GET["group"];}
    elseif (isset($_POST["group"]))          {$group=$_POST["group"];}
if (isset($_GET["user"]))                    {$user=$_GET["user"];}
    elseif (isset($_POST["user"]))           {$user=$_POST["user"];}
if (isset($_GET["shift"]))                   {$shift=$_GET["shift"];}
    elseif (isset($_POST["shift"]))          {$shift=$_POST["shift"];}
if (isset($_GET["stage"]))                   {$stage=$_GET["stage"];}
    elseif (isset($_POST["stage"]))          {$stage=$_POST["stage"];}
if (isset($_GET["file_download"]))          {$file_download=$_GET["file_download"];}
    elseif (isset($_POST["file_download"]))  {$file_download=$_POST["file_download"];}
if (isset($_GET["DB"]))                      {$DB=$_GET["DB"];}
    elseif (isset($_POST["DB"]))             {$DB=$_POST["DB"];}
if (isset($_GET["submit"]))                  {$submit=$_GET["submit"];}
    elseif (isset($_POST["submit"]))        {$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))                  {$SUBMIT=$_GET["SUBMIT"];}
    elseif (isset($_POST["SUBMIT"]))        {$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["report_display_type"]))          {$report_display_type=$_GET["report_display_type"];}
    elseif (isset($_POST["report_display_type"]))  {$report_display_type=$_POST["report_display_type"];}
if (isset($_GET["search_archived_data"]))          {$search_archived_data=$_GET["search_archived_data"];}
    elseif (isset($_POST["search_archived_data"]))  {$search_archived_data=$_POST["search_archived_data"];}

 $MT=array();
 $MT[0]='';
 $NOW_DATE = date("Y-m-d");
 $NOW_TIME = date("Y-m-d H:i:s");
 $STARTtime = date("U");
if (!is_array($group)) {$group = array();}
if (!isset($query_date)) {$query_date = $NOW_DATE;}
if (!isset($end_date)) {$end_date = $NOW_DATE;}
if (strlen($shift)<2) {$shift='ALL';}

 $DB=preg_replace("/[^0-9a-zA-Z]/","",$DB);

 $report_name = 'Single Agent Daily';
 $db_source = 'M';

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
 $stmt = "SELECT use_non_latin,outbound_autodial_active,slave_db_server,reports_use_slave_db,enable_languages,language_method,report_default_format,allow_web_debug FROM system_settings;";
 $rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {echo "$stmt\n";}
 $qm_conf_ct = mysqli_num_rows($rslt);
if ($qm_conf_ct > 0)
    {
    $row=mysqli_fetch_row($rslt);
    $non_latin =                  $row[0];
    $outbound_autodial_active =  $row[1];
    $slave_db_server =            $row[2];
    $reports_use_slave_db =        $row[3];
    $SSenable_languages =          $row[4];
    $SSlanguage_method =           $row[5];
    $SSreport_default_format =     $row[6];
    $SSallow_web_debug =          $row[7];
    }
if ($SSallow_web_debug < 1) {$DB=0;}
##### END SETTINGS LOOKUP #####
###########################################

### ARCHIVED DATA CHECK CONFIGURATION
 $archives_available="N";
 $log_tables_array=array("vicidial_agent_log");
for ($t=0; $t<count($log_tables_array); $t++) 
    {
    $table_name=$log_tables_array[$t];
    $archive_table_name=use_archive_table($table_name);
    if ($archive_table_name!=$table_name) {$archives_available="Y";}
    }

if ($search_archived_data) 
    {
    $vicidial_agent_log_table=use_archive_table("vicidial_agent_log");
    }
else
    {
    $vicidial_agent_log_table="vicidial_agent_log";
    }
#############

 $report_display_type = preg_replace('/[^-_0-9a-zA-Z]/', '', $report_display_type);
 $query_date = preg_replace('/[^- \:\_0-9a-zA-Z]/',"",$query_date);
 $end_date = preg_replace('/[^- \:\_0-9a-zA-Z]/',"",$end_date);
 $file_download = preg_replace('/[^-_0-9a-zA-Z]/', '', $file_download);
 $search_archived_data = preg_replace('/[^-_0-9a-zA-Z]/', '', $search_archived_data);
 $submit = preg_replace('/[^-_0-9a-zA-Z]/', '', $submit);
 $SUBMIT = preg_replace('/[^-_0-9a-zA-Z]/', '', $SUBMIT);
 $stage = preg_replace('/[^-_0-9a-zA-Z]/', '', $stage);

if ($non_latin < 1)
    {
    $PHP_AUTH_USER = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_USER);
    $PHP_AUTH_PW = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_PW);
    $group = preg_replace('/[^-_0-9a-zA-Z]/','',$group);
    $shift = preg_replace('/[^-_0-9a-zA-Z]/', '', $shift);
    $user = preg_replace('/[^-_0-9a-zA-Z]/', '', $user);
    }
else
    {
    $PHP_AUTH_USER = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_USER);
    $PHP_AUTH_PW = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_PW);
    $group = preg_replace('/[^-_0-9\p{L}]/u','',$group);
    $shift = preg_replace('/[^-_0-9\p{L}]/u', '', $shift);
    $user = preg_replace('/[^-_0-9\p{L}]/u', '', $user);
    }

 $stmt="SELECT selected_language from vicidial_users where user='$PHP_AUTH_USER';";
if ($DB) {echo "|$stmt|\n";}
 $rslt=mysql_to_mysqli($stmt, $link);
 $sl_ct = mysqli_num_rows($rslt);
if ($sl_ct > 0)
    {
    $row=mysqli_fetch_row($rslt);
    $VUselected_language =       $row[0];
    }

 $auth=0;
 $reports_auth=0;
 $admin_auth=0;
 $auth_message = user_authorization($PHP_AUTH_USER,$PHP_AUTH_PW,'REPORTS',1,0);
if ($auth_message == 'GOOD')
    {$auth=1;}

if ($auth > 0)
    {
    $stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and user_level > 7 and view_reports='1';";
    if ($DB) {echo "|$stmt|\n";}
    $rslt=mysql_to_mysqli($stmt, $link);
    $row=mysqli_fetch_row($rslt);
    $admin_auth=$row[0];

    $stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and user_level > 6 and view_reports='1';";
    if ($DB) {echo "|$stmt|\n";}
    $rslt=mysql_to_mysqli($stmt, $link);
    $row=mysqli_fetch_row($rslt);
    $reports_auth=$row[0];

    if ($reports_auth < 1)
        {
        $VDdisplayMESSAGE = _QXZ("You are not allowed to view reports");
        Header ("Content-type: text/html; charset=utf-8");
        echo "$VDdisplayMESSAGE: |$PHP_AUTH_USER|$auth_message|\n";
        exit;
        }
    if ( ($reports_auth > 0) and ($admin_auth < 1) )
        {
        $ADD=999999;
        $reports_only_user=1;
        }
    }
else
    {
    $VDdisplayMESSAGE = _QXZ("Login incorrect, please try again");
    if ($auth_message == 'LOCK')
        {
        $VDdisplayMESSAGE = _QXZ("Too many login attempts, try again in 15 minutes");
        Header ("Content-type: text/html; charset=utf-8");
        echo "$VDdisplayMESSAGE: |$PHP_AUTH_USER|$auth_message|\n";
        exit;
        }
    if ($auth_message == 'IPBLOCK')
        {
        $VDdisplayMESSAGE = _QXZ("Your IP Address is not allowed") . ": $ip";
        Header ("Content-type: text/html; charset=utf-8");
        echo "$VDdisplayMESSAGE: |$PHP_AUTH_USER|$auth_message|\n";
        exit;
        }
    Header("WWW-Authenticate: Basic realm=\"CONTACT-CENTER-ADMIN\"");
    Header("HTTP/1.0 401 Unauthorized");
    echo "$VDdisplayMESSAGE: |$PHP_AUTH_USER|$PHP_AUTH_PW|$auth_message|\n";
    exit;
    }

##### BEGIN log visit to the vicidial_report_log table #####
 $LOGip = getenv("REMOTE_ADDR");
 $LOGbrowser = getenv("HTTP_USER_AGENT");
 $LOGscript_name = getenv("SCRIPT_NAME");
 $LOGserver_name = getenv("SERVER_NAME");
 $LOGserver_port = getenv("SERVER_PORT");
 $LOGrequest_uri = getenv("REQUEST_URI");
 $LOGhttp_referer = getenv("HTTP_REFERER");
 $LOGbrowser=preg_replace("/<|>|\'|\"|\\\\/","",$LOGbrowser);
 $LOGrequest_uri=preg_replace("/<|>|\'|\"|\\\\/","",$LOGrequest_uri);
 $LOGhttp_referer=preg_replace("/<|>|\'|\"|\\\\/","",$LOGhttp_referer);
if (preg_match("/443/i",$LOGserver_port)) {$HTTPprotocol = 'https://';}
  else {$HTTPprotocol = 'http://';}
if (($LOGserver_port == '80') or ($LOGserver_port == '443') ) {$LOGserver_port='';}
else {$LOGserver_port = ":$LOGserver_port";}
 $LOGfull_url = "$HTTPprotocol$LOGserver_name$LOGserver_port$LOGrequest_uri";

 $LOGhostname = php_uname('n');
if (strlen($LOGhostname)<1) {$LOGhostname='X';}
if (strlen($LOGserver_name)<1) {$LOGserver_name='X';}

 $stmt="SELECT webserver_id FROM vicidial_webservers where webserver='$LOGserver_name' and hostname='$LOGhostname' LIMIT 1;";
 $rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {echo "$stmt\n";}
 $webserver_id_ct = mysqli_num_rows($rslt);
if ($webserver_id_ct > 0)
    {
    $row=mysqli_fetch_row($rslt);
    $webserver_id = $row[0];
    }
else
    {
    ##### insert webserver entry
    $stmt="INSERT INTO vicidial_webservers (webserver,hostname) values('$LOGserver_name','$LOGhostname');";
    if ($DB) {echo "$stmt\n";}
    $rslt=mysql_to_mysqli($stmt, $link);
    $affected_rows = mysqli_affected_rows($link);
    $webserver_id = mysqli_insert_id($link);
    }

 $stmt="INSERT INTO vicidial_report_log set event_date=NOW(), user='$PHP_AUTH_USER', ip_address='$LOGip', report_name='$report_name', browser='$LOGbrowser', referer='$LOGhttp_referer', notes='$LOGserver_name:$LOGserver_port $LOGscript_name |$query_date, $end_date, $shift, $file_download, $report_display_type|', url='$LOGfull_url', webserver='$webserver_id';";
if ($DB) {echo "|$stmt|\n";}
 $rslt=mysql_to_mysqli($stmt, $link);
 $report_log_id = mysqli_insert_id($link);
##### END log visit to the vicidial_report_log table #####

if ( (strlen($slave_db_server)>5) and (preg_match("/$report_name/",$reports_use_slave_db)) )
    {
    mysqli_close($link);
    $use_slave_server=1;
    $db_source = 'S';
    require("dbconnect_mysqli.php");
    echo "<!-- Using slave server $slave_db_server $db_source -->\n";
    }

 $stmt="SELECT user_group from vicidial_users where user='$PHP_AUTH_USER';";
if ($DB) {echo "|$stmt|\n";}
 $rslt=mysql_to_mysqli($stmt, $link);
 $row=mysqli_fetch_row($rslt);
 $LOGuser_group =            $row[0];

 $stmt="SELECT allowed_campaigns,allowed_reports,admin_viewable_groups,admin_viewable_call_times from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) {echo "|$stmt|\n";}
 $rslt=mysql_to_mysqli($stmt, $link);
 $row=mysqli_fetch_row($rslt);
 $LOGallowed_campaigns =          $row[0];
 $LOGallowed_reports =            $row[1];
 $LOGadmin_viewable_groups =      $row[2];
 $LOGadmin_viewable_call_times =  $row[3];

if ( (!preg_match("/$report_name/",$LOGallowed_reports)) and (!preg_match("/ALL REPORTS/",$LOGallowed_reports)) )
    {
    Header("WWW-Authenticate: Basic realm=\"CONTACT-CENTER-ADMIN\"");
    Header("HTTP/1.0 401 Unauthorized");
    echo _QXZ("You are not allowed to view this report").": |$PHP_AUTH_USER|$report_name|\n";
    exit;
    }

 $LOGadmin_viewable_groupsSQL='';
 $vuLOGadmin_viewable_groupsSQL='';
 $whereLOGadmin_viewable_groupsSQL='';
if ( (!preg_match('/\-\-ALL\-\-/i',$LOGadmin_viewable_groups)) and (strlen($LOGadmin_viewable_groups) > 3) )
    {
    $rawLOGadmin_viewable_groupsSQL = preg_replace("/ -/",'',$LOGadmin_viewable_groups);
    $rawLOGadmin_viewable_groupsSQL = preg_replace("/ /","','",$rawLOGadmin_viewable_groupsSQL);
    $LOGadmin_viewable_groupsSQL = "and user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
    $whereLOGadmin_viewable_groupsSQL = "where user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
    $vuLOGadmin_viewable_groupsSQL = "and vicidial_users.user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
    }

 $LOGadmin_viewable_call_timesSQL='';
 $whereLOGadmin_viewable_call_timesSQL='';
if ( (!preg_match('/\-\-ALL\-\-/i', $LOGadmin_viewable_call_times)) and (strlen($LOGadmin_viewable_call_times) > 3) )
    {
    $rawLOGadmin_viewable_call_timesSQL = preg_replace("/ -/",'',$LOGadmin_viewable_call_times);
    $rawLOGadmin_viewable_call_timesSQL = preg_replace("/ /","','",$rawLOGadmin_viewable_call_timesSQL);
    $LOGadmin_viewable_call_timesSQL = "and call_time_id IN('---ALL---','$rawLOGadmin_viewable_call_timesSQL')";
    $whereLOGadmin_viewable_call_timesSQL = "where call_time_id IN('---ALL---','$rawLOGadmin_viewable_call_timesSQL')";
    }

 $stmt="select user_group from vicidial_user_groups $whereLOGadmin_viewable_groupsSQL order by user_group;";
 $rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {echo "$stmt\n";}
 $user_groups_to_print = mysqli_num_rows($rslt);
 $i=0;
 $user_groups=array();
while ($i < $user_groups_to_print)
    {
    $row=mysqli_fetch_row($rslt);
    $user_groups[$i] =$row[0];
    $i++;
    }

 $stmt="select campaign_id from vicidial_campaigns $whereLOGadmin_viewable_groupsSQL order by campaign_id;";
 $rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {echo "$stmt\n";}
 $campaigns_to_print = mysqli_num_rows($rslt);
 $i=0;
 $campaigns=array();
while ($i < $campaigns_to_print)
    {
    $row=mysqli_fetch_row($rslt);
    $campaigns[$i] =$row[0];
    $i++;
    }

 $i=0;
 $group_string='|';
 $group_ct = count($group);
while ($i < $group_ct)
    {
    if ( (preg_match("/ $group[$i] /",$LOGallowed_campaigns)) or (preg_match("/-ALL/",$LOGallowed_campaigns)) )
        {
        $group_string .= "$group[$i]|";
        $group_SQL .= "'$group[$i]',";
        $groupQS .= "&group[]=$group[$i]";
        }
    $i++;
    }
if ( (preg_match('/\-\-ALL\-\-/i', $group_string) ) or ($group_ct < 1) )
    {$group_SQL = "";}
else
    {
    $group_SQL = preg_replace('/,$/i', '', $group_SQL);
    $group_SQL = "and campaign_id IN($group_SQL)";
    }

 $stmt="select status from vicidial_statuses where human_answered='Y';";
 $rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {echo "$stmt\n";}
 $statha_to_print = mysqli_num_rows($rslt);
 $i=0;
while ($i < $statha_to_print)
    {
    $row=mysqli_fetch_row($rslt);
    $customer_interactive_statuses .= "|$row[0]";
    $i++;
    }
 $stmt="select status from vicidial_campaign_statuses where human_answered='Y';";
 $rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {echo "$stmt\n";}
 $statha_to_print = mysqli_num_rows($rslt);
 $i=0;
while ($i < $statha_to_print)
    {
    $row=mysqli_fetch_row($rslt);
    $customer_interactive_statuses .= "|$row[0]";
    $i++;
    }
if (strlen($customer_interactive_statuses)>0)
    {$customer_interactive_statuses .= '|';}

 $LINKbase = "$PHP_SELF?query_date=$query_date&end_date=$end_date&shift=$shift&DB=$DB&user=$user$groupQS&search_archived_data=$search_archived_data&report_display_type=$report_display_type";

 $NWB = "<IMG SRC=\"help.png\" onClick=\"FillAndShowHelpDiv(event, '";
 $NWE = "')\" WIDTH=20 HEIGHT=20 BORDER=0 ALT=\"HELP\" ALIGN=TOP>";

if ($file_download < 1)
    {
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agent Days Detail Report</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">
        <link rel="stylesheet" href="calendar.css">
        <script src="calendar_db.js"></script>
        <script src="help.js"></script>
        <script src="chart/Chart.js"></script>
        <script src="vicidial_chart_functions.js"></script>
        <style>
            :root {
                --primary-color: #4a6cf7;
                --secondary-color: #6c757d;
                --success-color: #28a745;
                --danger-color: #dc3545;
                --warning-color: #ffc107;
                --info-color: #17a2b8;
                --light-color: #f8f9fa;
                --dark-color: #343a40;
                --sidebar-bg: #2c3e50;
                --sidebar-hover: #34495e;
                --card-shadow: 0 4px 6px rgba(0,0,0,0.05);
            }
            
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Roboto', sans-serif;
            }
            
            body {
                background-color: #f5f7fa;
                color: #333;
                line-height: 1.6;
            }
            
            .container {
                max-width: 1400px;
                margin: 0 auto;
                padding: 20px;
            }
            
            header {
                background: linear-gradient(135deg, var(--primary-color), #8b5cf6);
                color: white;
                padding: 20px;
                border-radius: 10px;
                margin-bottom: 25px;
                box-shadow: var(--card-shadow);
            }
            
            .header-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
            }
            
            .header-title {
                font-size: 28px;
                font-weight: 600;
            }
            
            .header-subtitle {
                font-size: 16px;
                opacity: 0.9;
                margin-top: 5px;
            }
            
            .header-actions {
                display: flex;
                gap: 10px;
            }
            
            .btn {
                padding: 10px 15px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-weight: 500;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
            }
            
            .btn-primary {
                background-color: var(--primary-color);
                color: white;
            }
            
            .btn-primary:hover {
                background-color: #3a5bd9;
            }
            
            .btn-secondary {
                background-color: var(--secondary-color);
                color: white;
            }
            
            .btn-secondary:hover {
                background-color: #5a6268;
            }
            
            .btn-success {
                background-color: var(--success-color);
                color: white;
            }
            
            .btn-success:hover {
                background-color: #218838;
            }
            
            .card {
                background: white;
                border-radius: 10px;
                box-shadow: var(--card-shadow);
                margin-bottom: 25px;
                overflow: hidden;
            }
            
            .card-header {
                padding: 15px 20px;
                background-color: var(--light-color);
                border-bottom: 1px solid #e9ecef;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            
            .card-title {
                font-size: 18px;
                font-weight: 600;
                color: var(--dark-color);
            }
            
            .card-body {
                padding: 20px;
            }
            
            .filters {
                display: flex;
                flex-wrap: wrap;
                gap: 15px;
                margin-bottom: 20px;
            }
            
            .form-group {
                flex: 1;
                min-width: 200px;
            }
            
            .form-label {
                display: block;
                margin-bottom: 5px;
                font-weight: 500;
                color: var(--dark-color);
            }
            
            .form-control {
                width: 100%;
                padding: 10px;
                border: 1px solid #ced4da;
                border-radius: 5px;
                font-size: 14px;
            }
            
            .form-control:focus {
                outline: none;
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.2);
            }
            
            .checkbox-group {
                display: flex;
                align-items: center;
                margin-top: 25px;
            }
            
            .checkbox-group input {
                margin-right: 8px;
            }
            
            .tabs {
                display: flex;
                border-bottom: 1px solid #e9ecef;
                margin-bottom: 20px;
            }
            
            .tab {
                padding: 10px 20px;
                cursor: pointer;
                border-bottom: 3px solid transparent;
                font-weight: 500;
            }
            
            .tab.active {
                border-bottom: 3px solid var(--primary-color);
                color: var(--primary-color);
            }
            
            .tab-content {
                display: none;
            }
            
            .tab-content.active {
                display: block;
            }
            
            .table-container {
                overflow-x: auto;
            }
            
            .table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            
            .table th {
                background-color: var(--light-color);
                padding: 12px 15px;
                text-align: left;
                font-weight: 600;
                color: var(--dark-color);
                border-bottom: 1px solid #e9ecef;
            }
            
            .table td {
                padding: 12px 15px;
                border-bottom: 1px solid #e9ecef;
            }
            
            .table tr:nth-child(even) {
                background-color: var(--light-color);
            }
            
            .table tr:hover {
                background-color: #f1f3f5;
            }
            
            .chart-container {
                margin-top: 20px;
                height: 400px;
                position: relative;
            }
            
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 20px;
                margin-bottom: 25px;
            }
            
            .stat-card {
                background: white;
                border-radius: 10px;
                box-shadow: var(--card-shadow);
                padding: 20px;
                text-align: center;
            }
            
            .stat-value {
                font-size: 32px;
                font-weight: 700;
                color: var(--primary-color);
                margin-bottom: 5px;
            }
            
            .stat-label {
                font-size: 14px;
                color: var(--secondary-color);
            }
            
            .alert {
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 5px;
            }
            
            .alert-warning {
                background-color: #fff3cd;
                color: #856404;
                border: 1px solid #ffeaa7;
            }
            
            .text-center {
                text-align: center;
            }
            
            .mt-20 {
                margin-top: 20px;
            }
            
            .mb-20 {
                margin-bottom: 20px;
            }
            
            .d-flex {
                display: flex;
            }
            
            .justify-content-between {
                justify-content: space-between;
            }
            
            .align-items-center {
                align-items: center;
            }
            
            .gap-10 {
                gap: 10px;
            }
            
            .loading {
                display: inline-block;
                width: 20px;
                height: 20px;
                border: 3px solid rgba(74, 108, 247, 0.3);
                border-radius: 50%;
                border-top-color: var(--primary-color);
                animation: spin 1s ease-in-out infinite;
            }
            
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
            
            .sidebar {
                width: 250px;
                background-color: white;
                border-radius: 10px;
                box-shadow: var(--card-shadow);
                padding: 20px;
                height: fit-content;
                position: sticky;
                top: 20px;
            }
            
            .sidebar-menu {
                list-style: none;
            }
            
            .sidebar-menu li {
                margin-bottom: 10px;
            }
            
            .sidebar-menu a {
                display: block;
                padding: 10px 15px;
                color: var(--dark-color);
                text-decoration: none;
                border-radius: 5px;
                transition: all 0.3s ease;
            }
            
            .sidebar-menu a:hover, .sidebar-menu a.active {
                background-color: var(--light-color);
                color: var(--primary-color);
            }
            
            .main-content {
                flex: 1;
            }
            
            .layout {
                display: flex;
                gap: 20px;
            }
            
            @media (max-width: 992px) {
                .layout {
                    flex-direction: column;
                }
                
                .sidebar {
                    width: 100%;
                    position: static;
                }
            }
            
            @media (max-width: 768px) {
                .header-content {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 15px;
                }
                
                .filters {
                    flex-direction: column;
                }
                
                .form-group {
                    min-width: auto;
                }
                
                .stats-grid {
                    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <header>
                <div class="header-content">
                    <div>
                        <div class="header-title"><?php echo _QXZ("Agent Days Detail Report"); ?></div>
                        <div class="header-subtitle"><?php echo "$user - $NOW_TIME ($db_source)"; ?></div>
                    </div>
                    <div class="header-actions">
                        <a href="user_status.php?user=<?php echo $user; ?>" class="btn btn-secondary">
                            <i class="fas fa-user"></i> <?php echo _QXZ("User Status"); ?>
                        </a>
                        <a href="user_stats.php?user=<?php echo $user; ?>" class="btn btn-secondary">
                            <i class="fas fa-chart-bar"></i> <?php echo _QXZ("User Stats"); ?>
                        </a>
                        <a href="admin.php?ADD=3&user=<?php echo $user; ?>" class="btn btn-secondary">
                            <i class="fas fa-user-edit"></i> <?php echo _QXZ("Modify User"); ?>
                        </a>
                    </div>
                </div>
            </header>
            
            <div class="layout">
                <div class="sidebar">
                    <ul class="sidebar-menu">
                        <li><a href="#filters" class="active"><?php echo _QXZ("Filters"); ?></a></li>
                        <li><a href="#summary"><?php echo _QXZ("Summary Stats"); ?></a></li>
                        <li><a href="#details"><?php echo _QXZ("Daily Details"); ?></a></li>
                        <li><a href="#charts"><?php echo _QXZ("Charts"); ?></a></li>
                    </ul>
                </div>
                
                <div class="main-content">
                    <div class="card mb-20" id="filters">
                        <div class="card-header">
                            <div class="card-title"><?php echo _QXZ("Report Filters"); ?></div>
                        </div>
                        <div class="card-body">
                            <form action="<?php echo $PHP_SELF; ?>" method="GET" name="vicidial_report" id="vicidial_report">
                                <input type="hidden" name="DB" value="<?php echo $DB; ?>">
                                <input type="hidden" name="user" value="<?php echo $user; ?>">
                                
                                <div class="filters">
                                    <div class="form-group">
                                        <label class="form-label"><?php echo _QXZ("Start Date"); ?></label>
                                        <input type="text" class="form-control" name="query_date" value="<?php echo $query_date; ?>" size="10" maxsize="10">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"><?php echo _QXZ("End Date"); ?></label>
                                        <input type="text" class="form-control" name="end_date" value="<?php echo $end_date; ?>" size="10" maxsize="10">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"><?php echo _QXZ("User"); ?></label>
                                        <input type="text" class="form-control" name="user" value="<?php echo $user; ?>" size="20" maxsize="20">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"><?php echo _QXZ("Shift"); ?></label>
                                        <select class="form-control" name="shift">
                                            <option value="<?php echo $shift; ?>" selected><?php echo _QXZ($shift); ?></option>
                                            <option value="">--</option>
                                            <option value="AM"><?php echo _QXZ("AM"); ?></option>
                                            <option value="PM"><?php echo _QXZ("PM"); ?></option>
                                            <option value="ALL"><?php echo _QXZ("ALL"); ?></option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"><?php echo _QXZ("Campaigns"); ?></label>
                                        <select class="form-control" name="group[]" multiple>
                                            <?php
                                            if (preg_match('/\-\-ALL\-\-/i', $group_string)) {
                                                echo "<option value=\"--ALL--\" selected>" . _QXZ("ALL CAMPAIGNS") . " --</option>\n";
                                            } else {
                                                echo "<option value=\"--ALL--\">" . _QXZ("ALL CAMPAIGNS") . " --</option>\n";
                                            }
                                            for ($o=0; $o<count($campaigns); $o++) {
                                                if (preg_match("/$campaigns[$o]/i", $group_string)) {
                                                    echo "<option selected value=\"$campaigns[$o]\">$campaigns[$o]</option>\n";
                                                } else {
                                                    echo "<option value=\"$campaigns[$o]\">$campaigns[$o]</option>\n";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label"><?php echo _QXZ("Display as"); ?></label>
                                        <select class="form-control" name="report_display_type">
                                            <?php
                                            if ($report_display_type) {
                                                echo "<option value=\"$report_display_type\" selected>" . _QXZ($report_display_type) . "</option>\n";
                                            }
                                            ?>
                                            <option value="TEXT"><?php echo _QXZ("TEXT"); ?></option>
                                            <option value="HTML"><?php echo _QXZ("HTML"); ?></option>
                                        </select>
                                    </div>
                                </div>
                                
                                <?php
                                if ($archives_available=="Y") {
                                    echo "<div class=\"checkbox-group\">\n";
                                    echo "<input type='checkbox' name='search_archived_data' value='checked' $search_archived_data>\n";
                                    echo "<label for='search_archived_data'>" . _QXZ("Search archived data") . "</label>\n";
                                    echo "</div>\n";
                                }
                                ?>
                                
                                <div class="d-flex justify-content-between mt-20">
                                    <button type="submit" name="SUBMIT" value="<?php echo _QXZ("SUBMIT"); ?>" class="btn btn-primary">
                                        <i class="fas fa-search"></i> <?php echo _QXZ("Submit"); ?>
                                    </button>
                                    
                                    <?php
                                    if (strlen($user) > 1) {
                                        echo "<a href=\"$LINKbase&stage=$stage&file_download=1\" class=\"btn btn-success\">\n";
                                        echo "<i class=\"fas fa-download\"></i> " . _QXZ("DOWNLOAD") . "\n";
                                        echo "</a>\n";
                                    }
                                    ?>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <?php
                    if (strlen($user) < 1) {
                        echo "<div class=\"card mb-20\">\n";
                        echo "<div class=\"card-body\">\n";
                        echo "<div class=\"alert alert-warning\">\n";
                        echo "<i class=\"fas fa-exclamation-triangle\"></i> " . _QXZ("PLEASE SELECT A USER AND DATE-TIME ABOVE AND CLICK SUBMIT") . "\n";
                        echo _QXZ("NOTE: stats taken from shift specified") . "\n";
                        echo "</div>\n";
                        echo "</div>\n";
                        echo "</div>\n";
                    } else {
                        // Set date range based on shift
                        if ($shift == 'AM') {
                            $time_BEGIN = "03:45:00";
                            $time_END = "15:14:59";
                            if (strlen($time_BEGIN) < 6) {$time_BEGIN = "03:45:00";}
                            if (strlen($time_END) < 6) {$time_END = "15:14:59";}
                        }
                        if ($shift == 'PM') {
                            $time_BEGIN = "15:15:00";
                            $time_END = "23:15:59";
                            if (strlen($time_BEGIN) < 6) {$time_BEGIN = "15:15:00";}
                            if (strlen($time_END) < 6) {$time_END = "23:15:59";}
                        }
                        if ($shift == 'ALL') {
                            $time_BEGIN = "00:00:00";
                            $time_END = "23:59:59";
                            if (strlen($time_BEGIN) < 6) {$time_BEGIN = "00:00:00";}
                            if (strlen($time_END) < 6) {$time_END = "23:59:59";}
                        }
                        
                        $query_date_BEGIN = "$query_date $time_BEGIN";
                        $query_date_END = "$query_date $time_END";
                        $end_date_BEGIN = "$end_date $time_BEGIN";
                        $end_date_END = "$end_date $time_END";
                        
                        // Get agent data
                        $stmt="select full_name from vicidial_users where user='$user' $vuLOGadmin_viewable_groupsSQL;";
                        $rslt=mysql_to_mysqli($stmt, $link);
                        if ($DB) {echo "$stmt\n";}
                        $row=mysqli_fetch_row($rslt);
                        $full_name = $row[0];
                        
                        // Process agent log data
                        $statuses = array();
                        $dates = array();
                        $calls = array();
                        $status_ary = array();
                        $dates_ary = array();
                        
                        $stmt="select date_format(event_time, '%Y-%m-%d') as date, count(*) as calls, status from vicidial_users,".$vicidial_agent_log_table." where event_time <= '$query_date_END' and event_time >= '$query_date_BEGIN' and vicidial_users.user=".$vicidial_agent_log_table.".user and ".$vicidial_agent_log_table.".user='$user' $group_SQL $vuLOGadmin_viewable_groupsSQL group by date,status order by date,status desc limit 500000;";
                        $rslt=mysql_to_mysqli($stmt, $link);
                        if ($DB) {echo "$stmt\n";}
                        $rows_to_print = mysqli_num_rows($rslt);
                        
                        $i=0;
                        while ($i < $rows_to_print) {
                            $row=mysqli_fetch_row($rslt);
                            if ($row[1] > 0 && strlen($row[2]) > 0) {
                                $dates[$i] = $row[0];
                                $calls[$i] = $row[1];
                                $status[$i] = $row[2];
                                
                                if (!preg_match("/\-$status[$i]\-/i", $statuses) && strlen($status[$i]) > 0) {
                                    $statuses .= "$status[$i]-";
                                    $status_ary[] = $status[$i];
                                }
                                
                                if (!preg_match("/\-$dates[$i]\-/i", $dates)) {
                                    $dates .= "$dates[$i]-";
                                    $dates_ary[] = $dates[$i];
                                }
                            }
                            $i++;
                        }
                        
                        // Generate summary stats
                        $total_calls = 0;
                        $customer_interactive_count = 0;
                        $dncci_count = 0;
                        
                        foreach ($status_ary as $status) {
                            $stmt="select count(*) from vicidial_users,".$vicidial_agent_log_table." where event_time <= '$query_date_END' and event_time >= '$query_date_BEGIN' and vicidial_users.user=".$vicidial_agent_log_table.".user and ".$vicidial_agent_log_table.".user='$user' $group_SQL $vuLOGadmin_viewable_groupsSQL and status='$status' limit 1;";
                            $rslt=mysql_to_mysqli($stmt, $link);
                            $row=mysqli_fetch_row($rslt);
                            $total_calls += $row[0];
                            
                            if (preg_match("/\|$status\|/i", $customer_interactive_statuses)) {
                                $customer_interactive_count += $row[0];
                            }
                            
                            if (preg_match("/DNC/i", $status)) {
                                $dncci_count += $row[0];
                            }
                        }
                        
                        $dncci_percent = 0;
                        if ($total_calls > 0) {
                            $dncci_percent = round(($dncci_count / $total_calls) * 100, 2);
                        }
                        
                        // Generate chart data
                        $chart_data = array();
                        foreach ($dates_ary as $date) {
                            $date_calls = 0;
                            $date_dncci = 0;
                            
                            foreach ($status_ary as $status) {
                                $stmt="select count(*) from vicidial_users,".$vicidial_agent_log_table." where event_time <= '$date 23:59:59' and event_time >= '$date 00:00:00' and vicidial_users.user=".$vicidial_agent_log_table.".user and ".$vicidial_agent_log_table.".user='$user' $group_SQL $vuLOGadmin_viewable_groupsSQL and status='$status' limit 1;";
                                $rslt=mysql_to_mysqli($stmt, $link);
                                $row=mysqli_fetch_row($rslt);
                                $date_calls += $row[0];
                                
                                if (preg_match("/DNC/i", $status)) {
                                    $date_dncci += $row[0];
                                }
                            }
                            
                            $date_dncci_percent = 0;
                            if ($date_calls > 0) {
                                $date_dncci_percent = round(($date_dncci / $date_calls) * 100, 2);
                            }
                            
                            $chart_data[] = array(
                                'date' => $date,
                                'total_calls' => $date_calls,
                                'dncci_count' => $date_dncci,
                                'dncci_percent' => $date_dncci_percent
                            );
                        }
                        
                        // Generate HTML output
                        echo "<div class=\"tabs\">\n";
                        echo "    <div class=\"tab active\" data-tab=\"summary\">" . _QXZ("Summary Stats") . "</div>\n";
                        echo "    <div class=\"tab\" data-tab=\"details\">" . _QXZ("Daily Details") . "</div>\n";
                        echo "    <div class=\"tab\" data-tab=\"charts\">" . _QXZ("Charts") . "</div>\n";
                        echo "</div>\n";
                        
                        // Summary Stats Tab
                        echo "<div class=\"tab-content active\" id=\"summary\">\n";
                        echo "    <div class=\"stats-grid\">\n";
                        echo "        <div class=\"stat-card\">\n";
                        echo "            <div class=\"stat-value\">$total_calls</div>\n";
                        echo "            <div class=\"stat-label\">" . _QXZ("Total Calls") . "</div>\n";
                        echo "        </div>\n";
                        echo "        <div class=\"stat-card\">\n";
                        echo "            <div class=\"stat-value\">$customer_interactive_count</div>\n";
                        echo "            <div class=\"stat-label\">" . _QXZ("Customer Interactive") . "</div>\n";
                        echo "        </div>\n";
                        echo "        <div class=\"stat-card\">\n";
                        echo "            <div class=\"stat-value\">$dncci_count</div>\n";
                        echo "            <div class=\"stat-label\">" . _QXZ("DNC/DNCCI") . "</div>\n";
                        echo "        </div>\n";
                        echo "        <div class=\"stat-card\">\n";
                        echo "            <div class=\"stat-value\">$dncci_percent%</div>\n";
                        echo "            <div class=\"stat-label\">" . _QXZ("DNC/DNCCI %") . "</div>\n";
                        echo "        </div>\n";
                        echo "    </div>\n";
                        echo "</div>\n";
                        
                        // Daily Details Tab
                        echo "<div class=\"tab-content\" id=\"details\">\n";
                        echo "    <div class=\"table-container\">\n";
                        echo "        <table class=\"table\">\n";
                        echo "            <thead>\n";
                        echo "                <tr>\n";
                        echo "                    <th>" . _QXZ("Date") . "</th>\n";
                        echo "                    <th>" . _QXZ("Calls") . "</th>\n";
                        echo "                    <th>" . _QXZ("CIcalls") . "</th>\n";
                        echo "                    <th>" . _QXZ("DNC/CI") . "%</th>\n";
                        echo "                    <th>" . _QXZ("Statuses") . "</th>\n";
                        echo "                </tr>\n";
                        echo "            </thead>\n";
                        echo "            <tbody>\n";
                        
                        $total_calls_display = 0;
                        $total_ci_display = 0;
                        $total_dnc_display = 0;
                        
                        foreach ($dates_ary as $i => $date) {
                            $date_calls = 0;
                            $date_ci = 0;
                            $date_dnc = 0;
                            $date_statuses = "";
                            
                            foreach ($status_ary as $status) {
                                $stmt="select count(*) from vicidial_users,".$vicidial_agent_log_table." where event_time <= '$date 23:59:59' and event_time >= '$date 00:00:00' and vicidial_users.user=".$vicidial_agent_log_table.".user and ".$vicidial_agent_log_table.".user='$user' $group_SQL $vuLOGadmin_viewable_groupsSQL and status='$status' limit 1;";
                                $rslt=mysql_to_mysqli($stmt, $link);
                                $row=mysqli_fetch_row($rslt);
                                $date_calls += $row[0];
                                $total_calls_display += $row[0];
                                
                                if (preg_match("/DNC/i", $status)) {
                                    $date_dnc += $row[0];
                                    $total_dnc_display += $row[0];
                                } else {
                                    $date_ci += $row[0];
                                    $total_ci_display += $row[0];
                                }
                                
                                if ($row[0] > 0) {
                                    $date_statuses .= "$status, ";
                                }
                            }
                            
                            $date_dncci_percent = 0;
                            if ($date_calls > 0) {
                                $date_dncci_percent = round(($date_dnc / $date_calls) * 100, 2);
                            }
                            
                            echo "                <tr>\n";
                            echo "                    <td>$date</td>\n";
                            echo "                    <td>$date_calls</td>\n";
                            echo "                    <td>$date_ci</td>\n";
                            echo "                    <td>$date_dncci_percent%</td>\n";
                            echo "                    <td>$date_statuses</td>\n";
                            echo "                </tr>\n";
                        }
                        
                        echo "                <tr>\n";
                        echo "                    <td><strong>" . _QXZ("TOTAL") . "</strong></td>\n";
                        echo "                    <td><strong>$total_calls_display</strong></td>\n";
                        echo "                    <td><strong>$total_ci_display</strong></td>\n";
                        echo "                    <td><strong>";
                        if ($total_calls_display > 0) {
                            echo round(($total_dnc_display / $total_calls_display) * 100, 2) . "%";
                        } else {
                            echo "0%";
                        }
                        echo "</strong></td>\n";
                        echo "                    <td><strong>" . _QXZ("All Statuses") . "</strong></td>\n";
                        echo "                </tr>\n";
                        echo "            </tbody>\n";
                        echo "        </table>\n";
                        echo "    </div>\n";
                        echo "</div>\n";
                        
                        // Charts Tab
                        echo "<div class=\"tab-content\" id=\"charts\">\n";
                        echo "    <div class=\"chart-container\">\n";
                        echo "        <canvas id=\"dncciChart\"></canvas>\n";
                        echo "    </div>\n";
                        echo "</div>\n";
                        
                        // JavaScript for tabs and chart
                        echo "<script>\n";
                        echo "document.addEventListener('DOMContentLoaded', function() {\n";
                        echo "    // Tab functionality\n";
                        echo "    const tabs = document.querySelectorAll('.tab');\n";
                        echo "    const tabContents = document.querySelectorAll('.tab-content');\n";
                        echo "    \n";
                        echo "    tabs.forEach(tab => {\n";
                        echo "        tab.addEventListener('click', function() {\n";
                        echo "            // Remove active class from all tabs and contents\n";
                        echo "            tabs.forEach(t => t.classList.remove('active'));\n";
                        echo "            tabContents.forEach(tc => tc.classList.remove('active'));\n";
                        echo "            \n";
                        echo "            // Add active class to clicked tab and corresponding content\n";
                        echo "            this.classList.add('active');\n";
                        echo "            const tabId = this.getAttribute('data-tab');\n";
                        echo "            document.getElementById(tabId).classList.add('active');\n";
                        echo "        });\n";
                        echo "    });\n";
                        echo "    \n";
                        echo "    // Chart functionality\n";
                        echo "    const ctx = document.getElementById('dncciChart').getContext('2d');\n";
                        echo "    const dncciChart = new Chart(ctx, {\n";
                        echo "        type: 'line',\n";
                        echo "        data: {\n";
                        echo "            labels: [";
                        
                        $first = true;
                        foreach ($chart_data as $data) {
                            if (!$first) {
                                echo ",";
                            }
                            echo "'$data[date]'";
                            $first = false;
                        }
                        
                        echo "],\n";
                        echo "            datasets: [{\n";
                        echo "                label: '" . _QXZ("DNC/DNCCI %") . "',\n";
                        echo "                data: [";
                        
                        $first = true;
                        foreach ($chart_data as $data) {
                            if (!$first) {
                                echo ",";
                            }
                            echo "$data[dncci_percent]";
                            $first = false;
                        }
                        
                        echo "],\n";
                        echo "                backgroundColor: 'rgba(220, 53, 69, 0.2)',\n";
                        echo "                borderColor: 'rgba(220, 53, 69, 1)',\n";
                        echo "                borderWidth: 2,\n";
                        echo "                pointBackgroundColor: 'rgba(220, 53, 69, 1)',\n";
                        echo "                pointRadius: 5,\n";
                        echo "                fill: false,\n";
                        echo "                tension: 0.1\n";
                        echo "            }]\n";
                        echo "        }\n";
                        echo "    });\n";
                        echo "});\n";
                        echo "</script>\n";
                    }
                    ?>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    } else {
        // Handle file download
        if ($file_download > 0) {
            $FILE_TIME = date("Ymd-His");
            $CSVfilename = "AGENT_DAYS_$user" . "_$FILE_TIME.csv";
            
            // Generate CSV content
            $CSV_content = _QXZ("Agent Days Detail Report") . " - $NOW_TIME\n";
            $CSV_content .= _QXZ("Time range") . ": $query_date_BEGIN " . _QXZ("to") . " $query_date_END\n";
            $CSV_content .= _QXZ("AGENT DAYS DETAIL") . ": $user - $full_name\n\n";
            $CSV_content .= _QXZ("Date") . "," . _QXZ("Calls") . "," . _QXZ("CIcalls") . "," . _QXZ("DNC/CI") . "%," . _QXZ("Statuses") . "\n";
            
            foreach ($dates_ary as $i => $date) {
                $date_calls = 0;
                $date_ci = 0;
                $date_dnc = 0;
                $date_statuses = "";
                
                foreach ($status_ary as $status) {
                    $stmt="select count(*) from vicidial_users,".$vicidial_agent_log_table." where event_time <= '$date 23:59:59' and event_time >= '$date 00:00:00' and vicidial_users.user=".$vicidial_agent_log_table.".user and ".$vicidial_agent_log_table.".user='$user' $group_SQL $vuLOGadmin_viewable_groupsSQL and status='$status' limit 1;";
                    $rslt=mysql_to_mysqli($stmt, $link);
                    $row=mysqli_fetch_row($rslt);
                    $date_calls += $row[0];
                    
                    if (preg_match("/DNC/i", $status)) {
                        $date_dnc += $row[0];
                    } else {
                        $date_ci += $row[0];
                    }
                    
                    if ($row[0] > 0) {
                        $date_statuses .= "$status, ";
                    }
                }
                
                $date_dncci_percent = 0;
                if ($date_calls > 0) {
                    $date_dncci_percent = round(($date_dnc / $date_calls) * 100, 2);
                }
                
                $CSV_content .= "$date,$date_calls,$date_ci,$date_dncci_percent,\"$date_statuses\"\n";
            }
            
            // Output CSV file
            header('Content-type: application/octet-stream');
            header("Content-Disposition: attachment; filename=\"$CSVfilename\"");
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            ob_clean();
            flush();
            
            echo $CSV_content;
            exit;
        }
    }

if ($db_source == 'S')
    {
    mysqli_close($link);
    $use_slave_server=0;
    $db_source = 'M';
    require("dbconnect_mysqli.php");
    }

 $endMS = microtime();
 $startMSary = explode(" ",$startMS);
 $endMSary = explode(" ",$endMS);
 $runS = ($endMSary[0] - $startMSary[0]);
 $runM = ($endMSary[1] - $startMSary[1]);
 $TOTALrun = ($runS + $runM);

 $stmt="UPDATE vicidial_report_log set run_time='$TOTALrun' where report_log_id='$report_log_id';";
if ($DB) {echo "|$stmt|\n";}
 $rslt=mysql_to_mysqli($stmt, $link);

exit;
?>
                        // Charts Tab Content
                        echo "<div class=\"tab-content\" id=\"charts\">\n";
                        echo "    <div class=\"card mb-20\">\n";
                        echo "        <div class=\"card-header\">\n";
                        echo "            <div class=\"card-title\">"._QXZ("Call Status Charts")."</div>\n";
                        echo "        </div>\n";
                        echo "        <div class=\"card-body\">\n";
                        echo "            <div class=\"chart-container\">\n";
                        echo "                <canvas id=\"statusChart\"></canvas>\n";
                        echo "            </div>\n";
                        echo "        </div>\n";
                        echo "    </div>\n";
                        
                        // DNC/DNCCI Percentage Chart
                        echo "    <div class=\"card mb-20\">\n";
                        echo "        <div class=\"card-header\">\n";
                        echo "            <div class=\"card-title\">"._QXZ("DNC/DNCCI Percentage")."</div>\n";
                        echo "        </div>\n";
                        echo "        <div class=\"card-body\">\n";
                        echo "            <div class=\"chart-container\">\n";
                        echo "                <canvas id=\"dncciChart\"></canvas>\n";
                        echo "            </div>\n";
                        echo "        </div>\n";
                        echo "    </div>\n";
                        
                        // JavaScript for charts
                        echo "    <script>\n";
                        echo "        document.addEventListener('DOMContentLoaded', function() {\n";
                        echo "            // Status Chart\n";
                        echo "            const statusCtx = document.getElementById('statusChart').getContext('2d');\n";
                        echo "            const statusChart = new Chart(statusCtx, {\n";
                        echo "                type: 'bar',\n";
                        echo "                data: {\n";
                        echo "                    labels: [";
                        
                        $first = true;
                        foreach ($dates_ary as $date) {
                            if (!$first) {
                                echo ",";
                            }
                            echo "'".preg_replace('/ +/', ' ', $date)."'";
                            $first = false;
                        }
                        
                        echo "],\n";
                        echo "                    datasets: [{\n";
                        echo "                        label: '"._QXZ("Number of Calls")."',\n";
                        echo "                        data: [";
                        
                        $first = true;
                        foreach ($dates_ary as $date) {
                            if (!$first) {
                                echo ",";
                            }
                            $date_calls = 0;
                            foreach ($status_ary as $status) {
                                $stmt="select count(*) from vicidial_users,".$vicidial_agent_log_table." where event_time <= '$date 23:59:59' and event_time >= '$date 00:00:00' and vicidial_users.user=".$vicidial_agent_log_table.".user and ".$vicidial_agent_log_table.".user='$user' $group_SQL $vuLOGadmin_viewable_groupsSQL and status='$status' limit 1;";
                                $rslt=mysql_to_mysqli($stmt, $link);
                                $row=mysqli_fetch_row($rslt);
                                $date_calls += $row[0];
                            }
                            echo "$date_calls";
                            $first = false;
                        }
                        
                        echo "],\n";
                        echo "                        backgroundColor: 'rgba(74, 108, 247, 0.7)',\n";
                        echo "                        borderColor: 'rgba(74, 108, 247, 1)',\n";
                        echo "                        borderWidth: 1\n";
                        echo "                    }]\n";
                        echo "                }\n";
                        echo "            });\n";
                        echo "            \n";
                        echo "            // DNC/DNCCI Chart\n";
                        echo "            const dncciCtx = document.getElementById('dncciChart').getContext('2d');\n";
                        echo "            const dncciChart = new Chart(dncciCtx, {\n";
                        echo "                type: 'line',\n";
                        echo "                data: {\n";
                        echo "                    labels: [";
                        
                        $first = true;
                        foreach ($dates_ary as $date) {
                            if (!$first) {
                                echo ",";
                            }
                            echo "'".preg_replace('/ +/', ' ', $date)."'";
                            $first = false;
                        }
                        
                        echo "],\n";
                        echo "                    datasets: [{\n";
                        echo "                        label: '"._QXZ("DNC/DNCCI %")."',\n";
                        echo "                        data: [";
                        
                        $first = true;
                        foreach ($dates_ary as $date) {
                            if (!$first) {
                                echo ",";
                            }
                            $date_dncci_percent = 0;
                            if (isset($chart_data[$date])) {
                                $date_dncci_percent = $chart_data[$date]['dncci_percent'];
                            }
                            echo "$date_dncci_percent";
                            $first = false;
                        }
                        
                        echo "],\n";
                        echo "                        backgroundColor: 'rgba(220, 53, 69, 0.7)',\n";
                        echo "                        borderColor: 'rgba(220, 53, 69, 1)',\n";
                        echo "                        borderWidth: 2,\n";
                        echo "                        pointBackgroundColor: 'rgba(220, 53, 69, 1)',\n";
                        echo "                        pointBorderColor: 'rgba(220, 53, 69, 1)',\n";
                        echo "                        pointRadius: 5,\n";
                        echo "                        fill: false\n";
                        echo "                    }]\n";
                        echo "                }\n";
                        echo "            });\n";
                        echo "        });\n";
                        echo "    </script>\n";
                        echo "</div>\n";
                        echo "    </div>\n";
                        echo "</div>\n";
                        echo "</div>\n";
                    
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>