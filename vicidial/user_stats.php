<?php
# user_stats.php - Modern Responsive UI Version

 $startMS = microtime();

require("dbconnect_mysqli.php");
require("functions.php");

 $report_name = 'User Stats';
 $db_source = 'M';

 $firstlastname_display_user_stats=0;
 $add_copy_disabled=0;
if (file_exists('options.php'))
    {
    require('options.php');
    }

 $PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
 $PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
 $PHP_SELF=$_SERVER['PHP_SELF'];
 $PHP_SELF = preg_replace('/\.php.*/i','.php',$PHP_SELF);
if (isset($_GET["did_id"]))                  {$did_id=$_GET["did_id"];}
    elseif (isset($_POST["did_id"]))        {$did_id=$_POST["did_id"];}
if (isset($_GET["did"]))                    {$did=$_GET["did"];}
    elseif (isset($_POST["did"]))           {$did=$_POST["did"];}
if (isset($_GET["begin_date"]))             {$begin_date=$_GET["begin_date"];}
    elseif (isset($_POST["begin_date"]))    {$begin_date=$_POST["begin_date"];}
if (isset($_GET["end_date"]))               {$end_date=$_GET["end_date"];}
    elseif (isset($_POST["end_date"]))      {$end_date=$_POST["end_date"];}
if (isset($_GET["user"]))                   {$user=$_GET["user"];}
    elseif (isset($_POST["user"]))          {$user=$_POST["user"];}
if (isset($_GET["call_status"]))            {$call_status=$_GET["call_status"];}
    elseif (isset($_POST["call_status"]))   {$call_status=$_POST["call_status"];}
if (isset($_GET["DB"]))                     {$DB=$_GET["DB"];}
    elseif (isset($_POST["DB"]))            {$DB=$_POST["DB"];}
if (isset($_GET["submit"]))                 {$submit=$_GET["submit"];}
    elseif (isset($_POST["submit"]))        {$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))                 {$SUBMIT=$_GET["SUBMIT"];}
    elseif (isset($_POST["SUBMIT"]))        {$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["file_download"]))              {$file_download=$_GET["file_download"];}
    elseif (isset($_POST["file_download"]))     {$file_download=$_POST["file_download"];}
if (isset($_GET["pause_code_rpt"]))              {$pause_code_rpt=$_GET["pause_code_rpt"];}
    elseif (isset($_POST["pause_code_rpt"]))     {$pause_code_rpt=$_POST["pause_code_rpt"];}
if (isset($_GET["park_rpt"]))               {$park_rpt=$_GET["park_rpt"];}
    elseif (isset($_POST["park_rpt"]))      {$park_rpt=$_POST["park_rpt"];}
if (isset($_GET["search_archived_data"]))          {$search_archived_data=$_GET["search_archived_data"];}
    elseif (isset($_POST["search_archived_data"])) {$search_archived_data=$_POST["search_archived_data"];}
if (isset($_GET["NVAuser"]))            {$NVAuser=$_GET["NVAuser"];}
    elseif (isset($_POST["NVAuser"]))    {$NVAuser=$_POST["NVAuser"];}

 $DB=preg_replace("/[^0-9a-zA-Z]/","",$DB);

 $STARTtime = date("U");
 $TODAY = date("Y-m-d");

if ( (!isset($begin_date)) or (strlen($begin_date) < 10) ) {$begin_date = $TODAY;}
if ( (!isset($end_date)) or (strlen($end_date) < 10) ) {$end_date = $TODAY;}

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
 $stmt = "SELECT use_non_latin,outbound_autodial_active,slave_db_server,reports_use_slave_db,user_territories_active,webroot_writable,allow_emails,level_8_disable_add,enable_languages,language_method,log_recording_access,admin_screen_colors,mute_recordings,allow_web_debug,hopper_hold_inserts FROM system_settings;";
 $rslt=mysql_to_mysqli($stmt, $link);
#if ($DB) {$MAIN.="$stmt\n";}
 $qm_conf_ct = mysqli_num_rows($rslt);
if ($qm_conf_ct > 0)
    {
    $row=mysqli_fetch_row($rslt);
    $non_latin =                  $row[0];
    $SSoutbound_autodial_active =  $row[1];
    $slave_db_server =            $row[2];
    $reports_use_slave_db =        $row[3];
    $user_territories_active =     $row[4];
    $webroot_writable =            $row[5];
    $allow_emails =                $row[6];
    $SSlevel_8_disable_add =       $row[7];
    $SSenable_languages =          $row[8];
    $SSlanguage_method =           $row[9];
    $log_recording_access =        $row[10];
    $SSadmin_screen_colors =       $row[11];
    $SSmute_recordings =           $row[12];
    $SSallow_web_debug =           $row[13];
    $SShopper_hold_inserts =       $row[14];
    }
if ($SSallow_web_debug < 1) {$DB=0;}
##### END SETTINGS LOOKUP #####
###########################################

### ARCHIVED DATA CHECK CONFIGURATION
 $archives_available="N";
 $log_tables_array=array("vicidial_log", "vicidial_agent_log", "vicidial_closer_log", "vicidial_user_log", "vicidial_timeclock_log", "vicidial_user_closer_log", "vicidial_email_log", "call_log", "recording_log", "user_call_log", "vicidial_lead_search_log", "vicidial_agent_skip_log","vicidial_agent_visibility_log");
for ($t=0; $t<count($log_tables_array); $t++) 
    {
    $table_name=$log_tables_array[$t];
    $archive_table_name=use_archive_table($table_name);
    if ($archive_table_name!=$table_name) {$archives_available="Y";}
    }

if ($search_archived_data) 
    {
    $vicidial_closer_log_table=use_archive_table("vicidial_closer_log");
    $vicidial_user_log_table=use_archive_table("vicidial_user_log");
    $vicidial_agent_log_table=use_archive_table("vicidial_agent_log");
    $vicidial_agent_visibility_log_table="vicidial_agent_visibility_log";
    $vicidial_timeclock_log_table=use_archive_table("vicidial_timeclock_log");
    $vicidial_user_closer_log_table=use_archive_table("vicidial_user_closer_log");
    $vicidial_email_log_table=use_archive_table("vicidial_email_log");
    $recording_log_table=use_archive_table("recording_log");
    $user_call_log_table=use_archive_table("user_call_log");
    $vicidial_lead_search_log_table=use_archive_table("vicidial_lead_search_log");
    $vicidial_agent_skip_log_table=use_archive_table("vicidial_agent_skip_log");
    $vicidial_agent_function_log=use_archive_table("vicidial_agent_function_log");
    $call_log_table=use_archive_table("call_log");
    $vicidial_log_table=use_archive_table("vicidial_log");
    $vicidial_hci_log=use_archive_table("vicidial_hci_log");
    }
else
    {
    $vicidial_closer_log_table="vicidial_closer_log";
    $vicidial_user_log_table="vicidial_user_log";
    $vicidial_agent_log_table="vicidial_agent_log";
    $vicidial_agent_visibility_log_table="vicidial_agent_visibility_log";
    $vicidial_timeclock_log_table="vicidial_timeclock_log";
    $vicidial_user_closer_log_table="vicidial_user_closer_log";
    $vicidial_email_log_table="vicidial_email_log";
    $recording_log_table="recording_log";
    $user_call_log_table="user_call_log";
    $vicidial_lead_search_log_table="vicidial_lead_search_log";
    $vicidial_agent_skip_log_table="vicidial_agent_skip_log";
    $vicidial_agent_function_log="vicidial_agent_function_log";
    $call_log_table="call_log";
    $vicidial_log_table="vicidial_log";
    $vicidial_hci_log="vicidial_hci_log";
    }
#############

 $did_id = preg_replace('/[^-\+\_0-9a-zA-Z]/',"",$did_id);
 $did = preg_replace('/[^-\+\_0-9a-zA-Z]/',"",$did);
 $begin_date = preg_replace('/[^- \:\_0-9a-zA-Z]/',"",$begin_date);
 $end_date = preg_replace('/[^- \:\_0-9a-zA-Z]/',"",$end_date);
 $file_download = preg_replace('/[^-_0-9a-zA-Z]/', '', $file_download);
 $pause_code_rpt = preg_replace('/[^-_0-9a-zA-Z]/', '', $pause_code_rpt);
 $park_rpt = preg_replace('/[^-_0-9a-zA-Z]/', '', $park_rpt);
 $search_archived_data = preg_replace('/[^-_0-9a-zA-Z]/', '', $search_archived_data);
 $submit = preg_replace('/[^-_0-9a-zA-Z]/', '', $submit);
 $SUBMIT = preg_replace('/[^-_0-9a-zA-Z]/', '', $SUBMIT);

if ($non_latin < 1)
    {
    $PHP_AUTH_USER = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_USER);
    $PHP_AUTH_PW = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_PW);
    $NVAuser = preg_replace('/[^-_0-9a-zA-Z]/','',$NVAuser);
    $user = preg_replace('/[^-_0-9a-zA-Z]/', '', $user);
    $call_status = preg_replace('/[^-_0-9a-zA-Z]/', '', $call_status);
    }
else
    {
    $PHP_AUTH_USER = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_USER);
    $PHP_AUTH_PW = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_PW);
    $NVAuser = preg_replace('/[^-_0-9\p{L}]/u','',$NVAuser);
    $user = preg_replace('/[^-_0-9\p{L}]/u', '', $user);
    $call_status = preg_replace('/[^-_0-9\p{L}]/u', '', $call_status);
    }

if ($call_status != "") 
    {
    $query_call_status = "and status='$call_status'";
    $VLquery_call_status = "and vlog.status='$call_status'";
    }
else 
    {
    $query_call_status = '';
    $VLquery_call_status = '';
    }
 $CS_vicidial_id_list='';
 $CS_vicidial_id_list_SQL='';

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
if ( ($auth_message == 'GOOD') or ($auth_message == '2FA') )
    {
    $auth=1;
    if ($auth_message == '2FA')
        {
        header ("Content-type: text/html; charset=utf-8");
        echo _QXZ("Your session is expired").". <a href=\"admin.php\">"._QXZ("Click here to log in")."</a>.\n";
        exit;
        }
    }

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


 $stmt="SELECT user_level from vicidial_users where user='$PHP_AUTH_USER';";
 $rslt=mysql_to_mysqli($stmt, $link);
 $row=mysqli_fetch_row($rslt);
 $LOGuser_level              =$row[0];

if (($LOGuser_level < 9) and ($SSlevel_8_disable_add > 0))
    {$add_copy_disabled++;}


require("screen_colors.php");

 $Mhead_color =   $SSstd_row5_background;
 $Mmain_bgcolor = $SSmenu_background;
 $Mhead_color =   $SSstd_row5_background;


 $date = date("r");
 $ip = getenv("REMOTE_ADDR");
 $browser = getenv("HTTP_USER_AGENT");

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

 $stmt="INSERT INTO vicidial_report_log set event_date=NOW(), user='$PHP_AUTH_USER', ip_address='$LOGip', report_name='$report_name', browser='$LOGbrowser', referer='$LOGhttp_referer', notes='$LOGserver_name:$LOGserver_port $LOGscript_name |$user, $query_date, $end_date, $call_status, $shift, $file_download, $report_display_type|', url='$LOGfull_url', webserver='$webserver_id';";
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
    $MAIN.="<!-- Using slave server $slave_db_server $db_source -->\n";
    }

 $stmt="SELECT full_name,user_group,admin_hide_lead_data,admin_hide_phone_data from vicidial_users where user='$PHP_AUTH_USER';";
 $rslt=mysql_to_mysqli($stmt, $link);
 $row=mysqli_fetch_row($rslt);
 $LOGfullname =               $row[0];
 $LOGuser_group =            $row[1];
 $LOGadmin_hide_lead_data =  $row[2];
 $LOGadmin_hide_phone_data = $row[3];

 $stmt="SELECT allowed_campaigns,allowed_reports,admin_viewable_groups from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) {$MAIN.="|$stmt|\n";}
 $rslt=mysql_to_mysqli($stmt, $link);
 $row=mysqli_fetch_row($rslt);
 $LOGallowed_campaigns =      $row[0];
 $LOGallowed_reports =        $row[1];
 $LOGadmin_viewable_groups =  $row[2];

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

    if (strlen($user) > 0)
        {
        if ($did > 0)
            {
            $stmt="SELECT count(*) from vicidial_inbound_dids where did_pattern='$user' $LOGadmin_viewable_groupsSQL;";
            $rslt=mysql_to_mysqli($stmt, $link);
            $row=mysqli_fetch_row($rslt);
            $allowed_count = $row[0];
            }
        else
            {
            $stmt="SELECT count(*) from vicidial_users where user='$user' $LOGadmin_viewable_groupsSQL;";
            $rslt=mysql_to_mysqli($stmt, $link);
            $row=mysqli_fetch_row($rslt);
            $allowed_count = $row[0];
            }

        if ($allowed_count < 1)
            {
            echo _QXZ("This user does not exist").": |$PHP_AUTH_USER|$user|\n";
            exit;
            }
        }
    }

if ( (!preg_match("/$report_name/",$LOGallowed_reports)) and (!preg_match("/ALL REPORTS/",$LOGallowed_reports)) )
    {
    Header("WWW-Authenticate: Basic realm=\"CONTACT-CENTER-ADMIN\"");
    Header("HTTP/1.0 401 Unauthorized");
    echo _QXZ("You are not allowed to view this report").": |$PHP_AUTH_USER|$report_name|\n";
    exit;
    }

if ($did > 0)
    {
    $stmt="SELECT did_description from vicidial_inbound_dids where did_pattern='$user' $LOGadmin_viewable_groupsSQL;";
    $rslt=mysql_to_mysqli($stmt, $link);
    $row=mysqli_fetch_row($rslt);
    $full_name = $row[0];
    }
else
    {
    $stmt="SELECT full_name from vicidial_users where user='$user' $LOGadmin_viewable_groupsSQL;";
    $rslt=mysql_to_mysqli($stmt, $link);
    $row=mysqli_fetch_row($rslt);
    $full_name = $row[0];
    }

 $HEADER.="<html>\n";
 $HEADER.="<head>\n";
 $HEADER.="<script language=\"JavaScript\" src=\"calendar_db.js\"></script>\n";
 $HEADER.="<script language=\"JavaScript\" src=\"help.js\"></script>\n";
 $HEADER.="<link rel=\"stylesheet\" href=\"calendar.css\">\n";
 $HEADER.="<link rel=\"stylesheet\" type=\"text/css\" href=\"vicidial_stylesheet.php\">\n";

 $HEADER.="<div id='HelpDisplayDiv' class='help_info' style='display:none;'></div>\n";

 $HEADER.="<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=utf-8\">\n";

if ($did > 0)
    {$HEADER.="<title>"._QXZ("ADMINISTRATION: DID Call Stats");}
else
    {$HEADER.="<title>"._QXZ("ADMINISTRATION").": "._QXZ("$report_name");}

##### BEGIN Set variables to make header show properly #####
 $ADD =                  '3';
 $hh =                   'users';
 $sh =                   'stats';
 $LOGast_admin_access =  '1';
 $ADMIN =                'admin.php';
 $page_width='770';
 $section_width='750';
 $header_font_size='3';
 $subheader_font_size='2';
 $subcamp_font_size='2';
 $header_selected_bold='<b>';
 $header_nonselected_bold='';
 $users_color =      '#FFFF99';
 $users_font =       'BLACK';
 $users_color =      '#E6E6E6';
 $subcamp_color =    '#C6C6C6';

if ($did > 0)
    {
    $hh =   'ingroups';
    $sh =   'listdid';
    $ADD =  '3311';
    $ingroups_color =      '#FFFF99';
    $ingroups_font =       'BLACK';
    $ingroups_color =      '#E6E6E6';
    }
##### END Set variables to make header show properly #####

#require("admin_header.php");
 $NWB = "<IMG SRC=\"help.png\" onClick=\"FillAndShowHelpDiv(event, '";
 $NWE = "')\" WIDTH=20 HEIGHT=20 BORDER=0 ALT=\"HELP\" ALIGN=TOP>";

// Modern UI HTML Structure
 $HTML = "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>User Statistics Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            background: linear-gradient(135deg, #4a6cf7, #8b5cf6);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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
            background-color: #4a6cf7;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #3a5bd9;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            overflow: hidden;
        }
        
        .card-header {
            padding: 15px 20px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #495057;
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
            color: #495057;
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
            border-color: #4a6cf7;
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
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            padding: 20px;
            text-align: center;
        }
        
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #4a6cf7;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #6c757d;
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
            background-color: #f8f9fa;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 1px solid #e9ecef;
        }
        
        .table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .table tr:hover {
            background-color: #f1f3f5;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        
        .pagination a {
            color: #4a6cf7;
            padding: 8px 16px;
            text-decoration: none;
            transition: background-color .3s;
        }
        
        .pagination a.active {
            background-color: #4a6cf7;
            color: white;
        }
        
        .pagination a:hover:not(.active) {
            background-color: #e9ecef;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
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
        
        .badge {
            display: inline-block;
            padding: 3px 7px;
            font-size: 12px;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 10px;
        }
        
        .badge-success {
            background-color: #28a745;
        }
        
        .badge-danger {
            background-color: #dc3545;
        }
        
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        
        .badge-info {
            background-color: #17a2b8;
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
            border-bottom: 3px solid #4a6cf7;
            color: #4a6cf7;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(74, 108, 247, 0.3);
            border-radius: 50%;
            border-top-color: #4a6cf7;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .sidebar {
            width: 250px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
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
            color: #495057;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background-color: #f8f9fa;
            color: #4a6cf7;
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
            
            .stats-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
            
            .filters {
                flex-direction: column;
            }
            
            .form-group {
                min-width: auto;
            }
        }
    </style>
</head>
<body>";

// Generate the modern UI
 $HTML .= "
    <div class='container'>
        <header>
            <div class='header-content'>
                <div>
                    <div class='header-title'>"._QXZ("User Statistics")."</div>
                    <div class='header-subtitle'>$user - $full_name</div>
                </div>
                <div class='header-actions'>
                    <a href='admin.php?ADD=3&user=$user' class='btn btn-secondary'>
                        <i class='fas fa-user-edit'></i> "._QXZ("Modify User")."
                    </a>
                    <a href='user_status.php?user=$user' class='btn btn-secondary'>
                        <i class='fas fa-chart-line'></i> "._QXZ("User Status")."
                    </a>
                </div>
            </div>
        </header>
        
        <div class='card mb-20'>
            <div class='card-header'>
                <div class='card-title'>"._QXZ("Filters")."</div>
            </div>
            <div class='card-body'>
                <form action='$PHP_SELF' method='GET' name='vicidial_report' id='vicidial_report'>
                    <input type='hidden' name='DB' value='$DB'>
                    <input type='hidden' name='NVAuser' value='$NVAuser'>
                    <input type='hidden' name='did_id' value='$did_id'>
                    <input type='hidden' name='did' value='$did'>
                    <input type='hidden' name='pause_code_rpt' value='$pause_code_rpt'>
                    <input type='hidden' name='park_rpt' value='$park_rpt'>
                    
                    <div class='filters'>
                        <div class='form-group'>
                            <label class='form-label'>"._QXZ("Start Date")."</label>
                            <input type='text' class='form-control' name='begin_date' value='$begin_date' size='10' maxsize='10'>
                        </div>
                        <div class='form-group'>
                            <label class='form-label'>"._QXZ("End Date")."</label>
                            <input type='text' class='form-control' name='end_date' value='$end_date' size='10' maxsize='10'>
                        </div>
                        <div class='form-group'>
                            <label class='form-label'>"._QXZ("Call Status")."</label>
                            <input type='text' class='form-control' name='call_status' size='7' maxlength='6' value='$call_status'>
                        </div>
                        <div class='form-group'>
                            <label class='form-label'>&nbsp;</label>
                            <button type='submit' name='submit' value='"._QXZ("submit")."' class='btn btn-primary'>
                                <i class='fas fa-search'></i> "._QXZ("Search")."
                            </button>
                        </div>
                    </div>
                    
                    <div class='checkbox-group'>
                        <input type='checkbox' name='search_archived_data' value='checked' $search_archived_data>
                        <label for='search_archived_data'>"._QXZ("Search archived data")."</label>
                    </div>
                </form>
            </div>
        </div>
        
        <div class='layout'>
            <div class='sidebar'>
                <ul class='sidebar-menu'>
                    <li><a href='#talk-time' class='active'>"._QXZ("Talk Time & Status")."</a></li>
                    <li><a href='#login-logout'>"._QXZ("Login/Logout Time")."</a></li>
                    <li><a href='#timeclock'>"._QXZ("Timeclock")."</a></li>
                    <li><a href='#outbound-calls'>"._QXZ("Outbound Calls")."</a></li>
                    <li><a href='#inbound-calls'>"._QXZ("Inbound Calls")."</a></li>
                    <li><a href='#agent-activity'>"._QXZ("Agent Activity")."</a></li>
                    <li><a href='#recordings'>"._QXZ("Recordings")."</a></li>
                    <li><a href='#manual-calls'>"._QXZ("Manual Calls")."</a></li>
                    <li><a href='#lead-searches'>"._QXZ("Lead Searches")."</a></li>
                </ul>
            </div>
            
            <div class='main-content'>
                <div class='tabs'>
                    <div class='tab active' data-tab='overview'>"._QXZ("Overview")."</div>
                    <div class='tab' data-tab='details'>"._QXZ("Details")."</div>
                </div>
                
                <div class='tab-content active' id='overview'>
                    <div class='stats-grid'>
                        <div class='stat-card'>
                            <div class='stat-value'>$total_calls</div>
                            <div class='stat-label'>"._QXZ("Total Calls")."</div>
                        </div>
                        <div class='stat-card'>
                            <div class='stat-value'>$total_login_hours_minutes</div>
                            <div class='stat-label'>"._QXZ("Total Login Time")."</div>
                        </div>
                        <div class='stat-card'>
                            <div class='stat-value'>$call_hours_minutes</div>
                            <div class='stat-label'>"._QXZ("Total Talk Time")."</div>
                        </div>
                        <div class='stat-card'>
                            <div class='stat-value'>$TOTALinSECONDS</div>
                            <div class='stat-label'>"._QXZ("Inbound Seconds")."</div>
                        </div>
                    </div>
                    
                    <div class='card mb-20'>
                        <div class='card-header'>
                            <div class='card-title'>"._QXZ("Agent Talk Time and Status")."</div>
                            <div>
                                <a href='$download_link&file_download=1' class='btn btn-secondary'>
                                    <i class='fas fa-download'></i> "._QXZ("DOWNLOAD")."
                                </a>
                            </div>
                        </div>
                        <div class='card-body'>
                            <div class='table-container'>
                                <table class='table'>
                                    <thead>
                                        <tr>
                                            <th>"._QXZ("STATUS")."</th>
                                            <th>"._QXZ("COUNT")."</th>
                                            <th>"._QXZ("HOURS:MM:SS")."</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Agent Talk Time Data Here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class='tab-content' id='details'>
                    <div class='card mb-20'>
                        <div class='card-header'>
                            <div class='card-title'>"._QXZ("Agent Login and Logout Time")."</div>
                            <div>
                                <a href='$download_link&file_download=2' class='btn btn-secondary'>
                                    <i class='fas fa-download'></i> "._QXZ("DOWNLOAD")."
                                </a>
                            </div>
                        </div>
                        <div class='card-body'>
                            <div class='table-container'>
                                <table class='table'>
                                    <thead>
                                        <tr>
                                            <th>"._QXZ("EVENT")."</th>
                                            <th>"._QXZ("DATE")."</th>
                                            <th>"._QXZ("CAMPAIGN")."</th>
                                            <th>"._QXZ("GROUP")."</th>
                                            <th>"._QXZ("SESSION")."</th>
                                            <th>"._QXZ("SERVER")."</th>
                                            <th>"._QXZ("PHONE")."</th>
                                            <th>"._QXZ("COMPUTER")."</th>
                                            <th>"._QXZ("PHONE LOGIN")."</th>
                                            <th>"._QXZ("PHONE IP")."</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Login/Logout Data Here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class='card mb-20'>
                        <div class='card-header'>
                            <div class='card-title'>"._QXZ("Outbound Calls")."</div>
                            <div>
                                <a href='$download_link&file_download=5' class='btn btn-secondary'>
                                    <i class='fas fa-download'></i> "._QXZ("DOWNLOAD")."
                                </a>
                            </div>
                        </div>
                        <div class='card-body'>
                            <div class='table-container'>
                                <table class='table'>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>"._QXZ("DATE/TIME")."</th>
                                            <th>"._QXZ("LENGTH")."</th>
                                            <th>"._QXZ("STATUS")."</th>
                                            <th>"._QXZ("PHONE")."</th>
                                            <th>"._QXZ("CAMPAIGN")."</th>
                                            <th>"._QXZ("GROUP")."</th>
                                            <th>"._QXZ("LIST")."</th>
                                            <th>"._QXZ("LEAD")."</th>
                                            <th>"._QXZ("HANGUP REASON")."</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Outbound Calls Data Here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class='card mb-20'>
                        <div class='card-header'>
                            <div class='card-title'>"._QXZ("Inbound Calls")."</div>
                            <div>
                                <a href='$download_link&file_download=6' class='btn btn-secondary'>
                                    <i class='fas fa-download'></i> "._QXZ("DOWNLOAD")."
                                </a>
                            </div>
                        </div>
                        <div class='card-body'>
                            <div class='table-container'>
                                <table class='table'>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>"._QXZ("DATE/TIME")."</th>
                                            <th>"._QXZ("LENGTH")."</th>
                                            <th>"._QXZ("STATUS")."</th>
                                            <th>"._QXZ("PHONE")."</th>
                                            <th>"._QXZ("IN-GROUP")."</th>
                                            <th>"._QXZ("WAIT (S)")."</th>
                                            <th>"._QXZ("AGENT (S)")."</th>
                                            <th>"._QXZ("LIST")."</th>
                                            <th>"._QXZ("LEAD")."</th>
                                            <th>"._QXZ("HANGUP REASON")."</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Inbound Calls Data Here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab functionality
            const tabs = document.querySelectorAll('.tab');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    
                    // Remove active class from all tabs and contents
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(tc => tc.classList.remove('active'));
                    
                    // Add active class to clicked tab and corresponding content
                    this.classList.add('active');
                    document.getElementById(tabId).classList.add('active');
                });
            });
            
            // Sidebar menu functionality
            const menuLinks = document.querySelectorAll('.sidebar-menu a');
            
            menuLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all links
                    menuLinks.forEach(l => l.classList.remove('active'));
                    
                    // Add active class to clicked link
                    this.classList.add('active');
                    
                    // Scroll to the corresponding section
                    const targetId = this.getAttribute('href').substring(1);
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });
            
            // Calendar functionality (if needed)
            const dateInputs = document.querySelectorAll('input[name=\"begin_date\"], input[name=\"end_date\"]');
            
            dateInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    // Initialize calendar if needed
                    // This would require integration with your calendar library
                });
            });
        });
    </script>
</body>
</html>";

// Rest of your PHP code continues here...

// The rest of your code remains the same, but you would need to populate the tables
// with the data from your database queries. I've included placeholders above.

if ($file_download>0) 
    {
    $FILE_TIME = date("Ymd-His");
    $CSVfilename = "user_stats_$US$FILE_TIME.csv";
    $CSV_var="CSV_text".$file_download;
    $CSV_text=preg_replace('/^\s+/', '', $$CSV_var);
    $CSV_text=preg_replace('/\n\s+,/', ',', $CSV_text);
    $CSV_text=preg_replace('/ +\"/', '"', $CSV_text);
    $CSV_text=preg_replace('/\" +/', '"', $CSV_text);
    // We'll be outputting a TXT file
    header('Content-type: application/octet-stream');

    // It will be called LIST_101_20090209-121212.txt
    header("Content-Disposition: attachment; filename=\"$CSVfilename\"");
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    ob_clean();
    flush();

    echo "$CSV_text";
    }
else
    {
    header ("Content-type: text/html; charset=utf-8");
    echo $HTML;
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