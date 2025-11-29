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

// Process data for reports
 $download_link="$PHP_SELF?DB=$DB&pause_code_rpt=$pause_code_rpt&park_rpt=$park_rpt&did_id=$did_id&did=$did&begin_date=$begin_date&end_date=$end_date&user=$user&submit=$submit&search_archived_data=$search_archived_data&NVAuser=$NVAuser\n";

// Generate all report data
if ($pause_code_rpt >= 1) {
    // Agent Pause Logs
    $stmt="SELECT ".$vicidial_agent_log_table.".campaign_id,event_time,talk_sec,pause_sec,sec_to_time(pause_sec) as pause_length,wait_sec,dispo_sec,dead_sec,sub_status, vicidial_users.user_group from vicidial_users,".$vicidial_agent_log_table.",vicidial_pause_codes where sub_status is not null and event_time <= '$end_date 23:59:59' and event_time >= '$begin_date 00:00:00' and ".$vicidial_agent_log_table.".user='$user' and ".$vicidial_agent_log_table.".user=vicidial_users.user and pause_sec<65000 and wait_sec<65000 and talk_sec<65000 and dispo_sec<65000 and ".$vicidial_agent_log_table.".sub_status=vicidial_pause_codes.pause_code and ".$vicidial_agent_log_table.".campaign_id=vicidial_pause_codes.campaign_id order by event_time asc limit 500000;";
    $rslt=mysql_to_mysqli($stmt, $link);
    if ($DB) {$MAIN.="$stmt\n";}
    $pause_rows_to_print = mysqli_num_rows($rslt);
    
    $o=0; $total_pause_time=0;
    $pause_logs = array();
    while ($pause_row=mysqli_fetch_array($rslt)) {
        $total_pause_time+=$pause_row["pause_sec"];
        $pause_logs[] = $pause_row;
        $o++;
    }
    
    $total_pause_stmt="SELECT sec_to_time($total_pause_time)";
    $total_pause_rslt=mysql_to_mysqli($total_pause_stmt, $link);
    $total_pause_row=mysqli_fetch_row($total_pause_rslt);
    $total_pause_time_formatted=$total_pause_row[0];
}
elseif ($park_rpt >= 1) {
    // Agent Parked Call Logs
    $stmt="SELECT parked_time,status,lead_id,parked_sec from park_log where parked_time <= '$end_date 23:59:59' and parked_time >= '$begin_date 00:00:00' and user='$user' order by parked_time asc limit 500000;";
    $rslt=mysql_to_mysqli($stmt, $link);
    if ($DB) {$MAIN.="$stmt\n";}
    $park_rows_to_print = mysqli_num_rows($rslt);
    
    $o=0; $total_park_time=0;
    $park_logs = array();
    while ($park_row=mysqli_fetch_array($rslt)) {
        $total_park_time+=$park_row["parked_sec"];
        $park_logs[] = $park_row;
        $o++;
    }
    
    $total_park_stmt="SELECT sec_to_time($total_park_time)";
    $total_park_rslt=mysql_to_mysqli($total_park_stmt, $link);
    $total_park_row=mysqli_fetch_row($total_park_rslt);
    $total_park_time_formatted=$total_park_row[0];
}
else {
    // Regular reports
    if ($did < 1) {
        // Agent Talk Time and Status
        $stmt="SELECT count(*),status, sum(length_in_sec) from ".$vicidial_log_table." where user='" . mysqli_real_escape_string($link, $user) . "' and call_date >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and call_date <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' $query_call_status group by status order by status";
        $rslt=mysql_to_mysqli($stmt, $link);
        $VLstatuses_to_print = mysqli_num_rows($rslt);
        $total_calls=0;
        $o=0;   $p=0;
        $counts=array();
        $status=array();
        $call_sec=array();
        while ($VLstatuses_to_print > $o) {
            $row=mysqli_fetch_row($rslt);
            $counts[$p] =      $row[0];
            $status[$p] =      $row[1];
            $call_sec[$p] =    $row[2];
            $p++;
            $o++;
        }

        $stmt="SELECT count(*),status, sum(length_in_sec-queue_seconds) from ".$vicidial_closer_log_table." where user='" . mysqli_real_escape_string($link, $user) . "' and call_date >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and call_date <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' $query_call_status group by status order by status";
        $rslt=mysql_to_mysqli($stmt, $link);
        $VCLstatuses_to_print = mysqli_num_rows($rslt);
        $o=0;
        while ($VCLstatuses_to_print > $o) {
            $status_match=0;
            $r=0;
            $row=mysqli_fetch_row($rslt);
            while ($VLstatuses_to_print > $r) {
                if ($status[$r] == $row[1]) {
                    $counts[$r] = ($counts[$r] + $row[0]);
                    $call_sec[$r] = ($call_sec[$r] + $row[2]);
                    $status_match++;
                }
                $r++;
            }
            if ($status_match < 1) {
                $counts[$p] =      $row[0];
                $status[$p] =      $row[1];
                $call_sec[$p] =    $row[2];
                $VLstatuses_to_print++;
                $p++;
            }
            $o++;
        }

        $o=0;
        $total_sec=0;
        $talk_status_data = array();
        while ($o < $p) {
            $call_hours_minutes = sec_convert($call_sec[$o],'H');
            $talk_status_data[] = array(
                'status' => $status[$o],
                'count' => $counts[$o],
                'time' => $call_hours_minutes
            );
            $total_calls = ($total_calls + $counts[$o]);
            $total_sec = ($total_sec + $call_sec[$o]);
            $call_seconds=0;
            $o++;
        }

        $call_hours_minutes = sec_convert($total_sec,'H');

        // Agent Login and Logout Time
        $stmt="SELECT event,event_epoch,event_date,campaign_id,user_group,session_id,server_ip,extension,computer_ip,phone_login,phone_ip,if(event='LOGOUT' or event='TIMEOUTLOGOUT', 1, 0) as LOGpriority, webserver, login_url from ".$vicidial_user_log_table." where user='" . mysqli_real_escape_string($link, $user) . "' and event_date >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and event_date <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' order by event_date, LOGpriority asc;";
        $rslt=mysql_to_mysqli($stmt, $link);
        $events_to_print = mysqli_num_rows($rslt);

        $total_calls=0;
        $total_logins=0;
        $o=0;
        $event_start_seconds='';
        $event_stop_seconds='';
        $login_logout_data = array();
        $URL_MAIN = '';
        while ($events_to_print > $o) {
            $row=mysqli_fetch_row($rslt);
            if (preg_match("/LOGIN/i", $row[0])) {
                if ($row[10]=='LOOKUP') {$row[10]='';}
                $event_start_seconds = $row[1];
                $login_logout_data[] = array(
                    'type' => 'login',
                    'event' => $row[0],
                    'date' => $row[2],
                    'campaign' => $row[3],
                    'group' => $row[4],
                    'session' => $row[5],
                    'server' => $row[6],
                    'phone' => $row[7],
                    'computer' => $row[8],
                    'phone_login' => $row[9],
                    'phone_ip' => $row[10]
                );
                
                if ($LOGuser_level==9) {
                    if ($total_logins%2==0) {$url_bgcolor='bgcolor="#'. $SSstd_row2_background .'"';} 
                    else {$url_bgcolor='bgcolor="#'. $SSstd_row1_background .'"';}

                    $webserver_txt="";
                    $url_txt="";
                    if ($row[12]>0) {
                        $webserver_stmt="select webserver, hostname from vicidial_webservers where webserver_id='$row[12]'";
                        $webserver_rslt=mysql_to_mysqli($webserver_stmt, $link);
                        $webserver_row=mysqli_fetch_row($webserver_rslt);
                        $webserver_txt="$webserver_row[0] - $webserver_row[1]";
                        $webserver_txt=preg_replace('/^\s\-\s|\s\-\s$/', '', $webserver_txt);
                    }
                    if ($row[13]>0) {
                        $login_url_stmt="select url from vicidial_urls where url_id='$row[13]'";
                        $login_url_rslt=mysql_to_mysqli($login_url_stmt, $link);
                        $login_url_row=mysqli_fetch_row($login_url_rslt);
                        $url_txt=trim("$login_url_row[0]");
                    }

                    $URL_MAIN.="<tr $url_bgcolor>";
                    $URL_MAIN.="<td align=right><font size=2> $row[2] </td>\n";
                    $URL_MAIN.="<td align=right><font size=2> $row[3] </td>\n";
                    $URL_MAIN.="<td align=right><font size=2> $row[4] </td>\n";
                    $URL_MAIN.="<td align=right><font size=2> $row[6] </td>\n";
                    $URL_MAIN.="<td align=right><font size=2> ".(!$webserver_txt ? "&nbsp;" : "$webserver_txt")." </td>\n";
                    $URL_MAIN.="<td align=right><font size=2> ".(!$url_txt ? "&nbsp;" : "$url_txt")." </td>\n";
                    $URL_MAIN.="</tr>\n";
                }
                $total_logins++;
            }
            if (preg_match('/LOGOUT/', $row[0])) {
                if ($event_start_seconds) {
                    $event_stop_seconds = $row[1];
                    $event_seconds = ($event_stop_seconds - $event_start_seconds);
                    $total_login_time = ($total_login_time + $event_seconds);
                    $event_hours_minutes = sec_convert($event_seconds,'H');

                    $login_logout_data[] = array(
                        'type' => 'logout',
                        'event' => $row[0],
                        'date' => $row[2],
                        'campaign' => $row[3],
                        'group' => $row[4],
                        'session_time' => $event_hours_minutes,
                        'phone' => $row[7]
                    );
                    $event_start_seconds='';
                    $event_stop_seconds='';
                }
                else {
                    $login_logout_data[] = array(
                        'type' => 'logout',
                        'event' => $row[0],
                        'date' => $row[2],
                        'campaign' => $row[3],
                        'group' => $row[4],
                        'session_time' => '',
                        'phone' => $row[7]
                    );
                }
            }
            $total_calls++;
            $call_seconds=0;
            $o++;
        }
        $total_login_hours_minutes = sec_convert($total_login_time,'H');

        // Timeclock Login and Logout Time
        $SQday_ARY = explode('-',$begin_date);
        $EQday_ARY = explode('-',$end_date);
        $SQepoch = mktime(0, 0, 0, $SQday_ARY[1], $SQday_ARY[2], $SQday_ARY[0]);
        $EQepoch = mktime(23, 59, 59, $EQday_ARY[1], $EQday_ARY[2], $EQday_ARY[0]);

        $total_login_time=0;
        $stmt="SELECT event,event_epoch,user_group,login_sec,ip_address,timeclock_id,manager_user from ".$vicidial_timeclock_log_table." where user='" . mysqli_real_escape_string($link, $user) . "' and event_epoch >= '$SQepoch'  and event_epoch <= '$EQepoch';";
        if ($DB>0) {$MAIN.="|$stmt|";}
        $rslt=mysql_to_mysqli($stmt, $link);
        $events_to_print = mysqli_num_rows($rslt);

        $total_logs=0;
        $o=0;
        $timeclock_data = array();
        while ($events_to_print > $o) {
            $row=mysqli_fetch_row($rslt);
            $TC_log_date = date("Y-m-d H:i:s", $row[1]);
            $manager_edit='';
            if (strlen($row[6])>0) {$manager_edit = ' *';}

            if (preg_match('/LOGIN/', $row[0])) {
                $login_sec='';
                $timeclock_data[] = array(
                    'type' => 'login',
                    'id' => $row[5],
                    'manager_edit' => $manager_edit,
                    'event' => $row[0],
                    'date' => $TC_log_date,
                    'ip_address' => $row[4],
                    'group' => $row[2],
                    'session_time' => ''
                );
            }
            if (preg_match('/LOGOUT/', $row[0])) {
                $login_sec = $row[3];
                $total_login_time = ($total_login_time + $login_sec);
                $event_hours_minutes = sec_convert($login_sec,'H');

                $timeclock_data[] = array(
                    'type' => 'logout',
                    'id' => $row[5],
                    'manager_edit' => $manager_edit,
                    'event' => $row[0],
                    'date' => $TC_log_date,
                    'ip_address' => $row[4],
                    'group' => $row[2],
                    'session_time' => $event_hours_minutes
                );
            }
            $o++;
        }
        if (strlen($login_sec)<1) {
            $login_sec = ($EQepoch - $row[1]);
            $total_login_time = ($total_login_time + $login_sec);
        }
        $total_login_hours_minutes = sec_convert($total_login_time,'H');
	

        // Closer In-Group Selection Logs
        $stmt="SELECT user,campaign_id,event_date,blended,closer_campaigns,manager_change from ".$vicidial_user_closer_log_table." where user='" . mysqli_real_escape_string($link, $user) . "' and event_date >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and event_date <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' order by event_date desc limit 1000;";
        $rslt=mysql_to_mysqli($stmt, $link);
        $logs_to_print = mysqli_num_rows($rslt);

        $u=0;
        $closer_data = array();
        while ($logs_to_print > $u) {
            $row=mysqli_fetch_row($rslt);
            $u++;
            $closer_data[] = array(
                'num' => $u,
                'date' => $row[2],
                'campaign' => $row[1],
                'blend' => $row[3],
                'groups' => $row[4],
                'manager' => $row[5]
            );
        }

        // Outbound Calls
        $stmt="SELECT uniqueid,lead_id,list_id,campaign_id,call_date,start_epoch,end_epoch,length_in_sec,status,phone_code,phone_number,user,comments,processed,user_group,term_reason,alt_dial from ".$vicidial_log_table." where user='" . mysqli_real_escape_string($link, $user) . "' and call_date >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and call_date <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' $query_call_status order by call_date desc limit 10000;";
        if ($firstlastname_display_user_stats > 0) {
            $stmt="SELECT uniqueid,vlog.lead_id,vlog.list_id,campaign_id,call_date,start_epoch,end_epoch,length_in_sec,vlog.status,vlog.phone_code,vlog.phone_number,vlog.user,vlog.comments,processed,user_group,term_reason,alt_dial,first_name,last_name from ".$vicidial_log_table." vlog, vicidial_list vlist where vlog.user='" . mysqli_real_escape_string($link, $user) . "' and call_date >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and call_date <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' and vlog.lead_id=vlist.lead_id $VLquery_call_status order by call_date desc limit 10000;";
        }
        if ($DB) {$MAIN.="outbound calls|$stmt|";}
        $rslt=mysql_to_mysqli($stmt, $link);
        $logs_to_print = mysqli_num_rows($rslt);

        $u=0;
        $outbound_calls_data = array();
        while ($logs_to_print > $u) {
            $row=mysqli_fetch_row($rslt);
            if ($LOGadmin_hide_phone_data != '0') {
                if ($DB > 0) {echo "HIDEPHONEDATA|$row[10]|$LOGadmin_hide_phone_data|\n";}
                $phone_temp = $row[10];
                if (strlen($phone_temp) > 0) {
                    if ($LOGadmin_hide_phone_data == '4_DIGITS') {
                        $row[10] = str_repeat("X", (strlen($phone_temp) - 4)) . substr($phone_temp,-4,4);
                    } elseif ($LOGadmin_hide_phone_data == '3_DIGITS') {
                        $row[10] = str_repeat("X", (strlen($phone_temp) - 3)) . substr($phone_temp,-3,3);
                    } elseif ($LOGadmin_hide_phone_data == '2_DIGITS') {
                        $row[10] = str_repeat("X", (strlen($phone_temp) - 2)) . substr($phone_temp,-2,2);
                    } else {
                        $row[10] = preg_replace("/./",'X',$phone_temp);
                    }
                }
            }
            $u++;
            $outbound_calls_data[] = array(
                'num' => $u,
                'date' => $row[4],
                'length' => $row[7],
                'status' => $row[8],
                'phone' => $row[10],
                'campaign' => $row[3],
                'group' => $row[14],
                'list' => $row[2],
                'lead_id' => $row[1],
                'name' => ($firstlastname_display_user_stats > 0) ? "$row[17] $row[18]" : '',
                'term_reason' => $row[15]
            );
            if (strlen($query_call_status) > 5) {
                $CS_vicidial_id_list .= "'$row[0]',";
            }
        }

        // Outbound Emails
        if ($allow_emails>0) {
            $stmt="SELECT email_log_id,email_row_id,lead_id,email_date,user,email_to,message,campaign_id,attachments from ".$vicidial_email_log_table." where user='" . mysqli_real_escape_string($link, $user) . "' and email_date >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and email_date <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' order by email_date desc limit 10000;";
            $rslt=mysql_to_mysqli($stmt, $link);
            $logs_to_print = mysqli_num_rows($rslt);

            $u=0;
            $outbound_emails_data = array();
            while ($logs_to_print > $u) {
                $row=mysqli_fetch_row($rslt);
                if (strlen($row[6])>400) {$row[6]=substr($row[6],0,400)."...";}
                $row[8]=preg_replace('/\|/', ', ', $row[8]);
                $row[8]=preg_replace('/,\s+$/', '', $row[8]);
                $u++;

                $outbound_emails_data[] = array(
                    'num' => $u,
                    'date' => $row[3],
                    'campaign' => $row[7],
                    'email_to' => $row[5],
                    'attachments' => $row[8],
                    'lead_id' => $row[2],
                    'message' => $row[6]
                );
            }
        }

        // Agent Activity
        $Aevent_time=array();
        $Alead_id=array();
        $Acampaign_id=array();
        $Apause_sec=array();
        $Await_sec=array();
        $Atalk_sec=array();
        $Adispo_sec=array();
        $Adead_sec=array();
        $Astatus=array();
        $Apause_code=array();
        $Auser_group=array();
        $Acomments=array();
        $Acustomer_sec=array();
        $Aagent_log_id=array();

        $stmt="SELECT event_time,lead_id,campaign_id,pause_sec,wait_sec,talk_sec,dispo_sec,dead_sec,status,sub_status,user_group,comments,agent_log_id from ".$vicidial_agent_log_table." where user='" . mysqli_real_escape_string($link, $user) . "' and event_time >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and event_time <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' and ( (pause_sec > 0) or (wait_sec > 0) or (talk_sec > 0) or (dispo_sec > 0) ) $query_call_status order by event_time desc limit 10000;";
        if ($DB) {$MAIN.="agent activity|$stmt|";}
        $rslt=mysql_to_mysqli($stmt, $link);
        $logs_to_print = mysqli_num_rows($rslt);

        $u=0;
        $TOTALpauseSECONDS=0;
        $TOTALwaitSECONDS=0;
        $TOTALtalkSECONDS=0;
        $TOTALdispoSECONDS=0;
        $TOTALdeadSECONDS=0;
        $TOTALcustomerSECONDS=0;
        $TOT_HIDDEN=0;
        $TOT_VISIBLE=0;
        while ($logs_to_print > $u) {
            $row=mysqli_fetch_row($rslt);
            $Aevent_time[$u] =  $row[0];
            $Alead_id[$u] =      $row[1];
            $Acampaign_id[$u] =   $row[2];
            $Apause_sec[$u] =     $row[3];
            $Await_sec[$u] =      $row[4];
            $Atalk_sec[$u] =      $row[5];
            $Adispo_sec[$u] =     $row[6];
            $Adead_sec[$u] =      $row[7];
            $Astatus[$u] =        $row[8];
            $Apause_code[$u] =    $row[9];
            $Auser_group[$u] =    $row[10];
            $Acomments[$u] =      $row[11];
            $Aagent_log_id[$u] =   $row[12];
            $Acustomer_sec[$u] = ($Atalk_sec[$u] - $Adead_sec[$u]);
            if ($Acustomer_sec[$u] < 0) {$Acustomer_sec[$u]=0;}
            $u++;
        }
        $u=0;
        $agent_activity_data = array();
        while ($logs_to_print > $u) {
            $event_time =  $Aevent_time[$u];
            $lead_id =      $Alead_id[$u];
            $campaign_id =   $Acampaign_id[$u];
            $pause_sec =     $Apause_sec[$u];
            $wait_sec =      $Await_sec[$u];
            $talk_sec =      $Atalk_sec[$u];
            $dispo_sec =     $Adispo_sec[$u];
            $dead_sec =      $Adead_sec[$u];
            $status =        $Astatus[$u];
            $pause_code =    $Apause_code[$u];
            $user_group =    $Auser_group[$u];
            $comments =      $Acomments[$u];
            $agent_log_id =  $Aagent_log_id[$u];
            $customer_sec =  $Acustomer_sec[$u];

            $HIDDEN_sec=0;   $VISIBLE_sec=0;
            $stmt="select count(*),sum(length_in_sec),visibility from ".$vicidial_agent_visibility_log_table." where user='" . mysqli_real_escape_string($link, $user) . "' and db_time >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01' and db_time <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' and visibility IN('HIDDEN','VISIBLE') and agent_log_id='$agent_log_id' group by visibility;";
            $rslt=mysql_to_mysqli($stmt, $link);
            $visibility_results = mysqli_num_rows($rslt);
            if ($DB) {echo "$visibility_results|$stmt\n";}
            $v_ct=0;
            while ($visibility_results > $v_ct) {
                $row=mysqli_fetch_row($rslt);
                if ($row[2] == 'HIDDEN') {$HIDDEN_sec = $row[1];}
                if ($row[2] == 'VISIBLE') {$VISIBLE_sec = $row[1];}
                $v_ct++;
            }
            $TOT_HIDDEN =   ($TOT_HIDDEN + $HIDDEN_sec);
            $TOT_VISIBLE =  ($TOT_VISIBLE + $VISIBLE_sec);
            if ($HIDDEN_sec < 1) {$HIDDEN_sec='';}
            if ($VISIBLE_sec < 1) {$VISIBLE_sec='';}

            $TOTALpauseSECONDS = ($TOTALpauseSECONDS + $pause_sec);
            $TOTALwaitSECONDS = ($TOTALwaitSECONDS + $wait_sec);
            $TOTALtalkSECONDS = ($TOTALtalkSECONDS + $talk_sec);
            $TOTALdispoSECONDS = ($TOTALdispoSECONDS + $dispo_sec);
            $TOTALdeadSECONDS = ($TOTALdeadSECONDS + $dead_sec);
            $TOTALcustomerSECONDS = ($TOTALcustomerSECONDS + $customer_sec);

            $u++;
            $agent_activity_data[] = array(
                'num' => $u,
                'event_time' => $event_time,
                'pause_sec' => $pause_sec,
                'wait_sec' => $wait_sec,
                'talk_sec' => $talk_sec,
                'dispo_sec' => $dispo_sec,
                'dead_sec' => $dead_sec,
                'customer_sec' => $customer_sec,
                'visible_sec' => $VISIBLE_sec,
                'hidden_sec' => $HIDDEN_sec,
                'status' => $status,
                'lead_id' => $lead_id,
                'call_type' => (strlen($lead_id) > 0) ? 
                    (($comments == 'INBOUND') ? 'I' : 
                    (($comments == 'EMAIL') ? 'E' : 
                    (($comments == 'CHAT') ? 'C' : 
                    (($comments == 'MANUAL') ? 'M' : 'A')))) : '',
                'campaign' => $campaign_id,
                'pause_code' => $pause_code
            );
        }

        $TOTALpauseSECONDShh = sec_convert($TOTALpauseSECONDS,'H');
        $TOTALwaitSECONDShh = sec_convert($TOTALwaitSECONDS,'H');
        $TOTALtalkSECONDShh = sec_convert($TOTALtalkSECONDS,'H');
        $TOTALdispoSECONDShh = sec_convert($TOTALdispoSECONDS,'H');
        $TOTALdeadSECONDShh = sec_convert($TOTALdeadSECONDS,'H');
        $TOTALcustomerSECONDShh = sec_convert($TOTALcustomerSECONDS,'H');
        $TOTALvisibleSECONDShh = sec_convert($TOT_VISIBLE,'H');
        $TOTALhiddenSECONDShh = sec_convert($TOT_HIDDEN,'H');

        // Manual Outbound Calls
        $stmt="SELECT call_date,call_type,server_ip,phone_number,number_dialed,lead_id,callerid,group_alias_id,preset_name,customer_hungup,customer_hungup_seconds from ".$user_call_log_table." where user='" . mysqli_real_escape_string($link, $user) . "' and call_date >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and call_date <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' order by call_date desc limit 10000;";
        $rslt=mysql_to_mysqli($stmt, $link);
        $logs_to_print = mysqli_num_rows($rslt);

        $u=0;
        $manual_calls_data = array();
        while ($logs_to_print > $u) {
            $row=mysqli_fetch_row($rslt);
            if ($LOGadmin_hide_phone_data != '0') {
                if ($DB > 0) {echo "HIDEPHONEDATA|$row[10]|$LOGadmin_hide_phone_data|\n";}
                $phone_temp = $row[3];
                $dialed_temp = $row[4];
                if ($LOGadmin_hide_phone_data == '4_DIGITS') {
                    if (strlen($phone_temp) > 0) {
                        $row[3] = str_repeat("X", (strlen($phone_temp) - 4)) . substr($phone_temp,-4,4);
                    }
                    if (strlen($dialed_temp) > 0) {
                        $row[4] = str_repeat("X", (strlen($dialed_temp) - 4)) . substr($dialed_temp,-4,4);
                    }
                } elseif ($LOGadmin_hide_phone_data == '3_DIGITS') {
                    if (strlen($phone_temp) > 0) {
                        $row[3] = str_repeat("X", (strlen($phone_temp) - 3)) . substr($phone_temp,-3,3);
                    }
                    if (strlen($dialed_temp) > 0) {
                        $row[4] = str_repeat("X", (strlen($dialed_temp) - 3)) . substr($dialed_temp,-3,3);
                    }
                } elseif ($LOGadmin_hide_phone_data == '2_DIGITS') {
                    if (strlen($phone_temp) > 0) {
                        $row[3] = str_repeat("X", (strlen($phone_temp) - 2)) . substr($phone_temp,-2,2);
                    }
                    if (strlen($dialed_temp) > 0) {
                        $row[4] = str_repeat("X", (strlen($dialed_temp) - 2)) . substr($dialed_temp,-2,2);
                    }
                } else {
                    if (strlen($phone_temp) > 0) {
                        $row[3] = preg_replace("/./",'X',$phone_temp);
                    }
                    if (strlen($dialed_temp) > 0) {
                        $row[4] = preg_replace("/./",'X',$dialed_temp);
                    }
                }
            }

            $u++;
            $C3HU='';
            if ($row[9]=='BEFORE_CALL') {$row[9]='BC';}
            if ($row[9]=='DURING_CALL') {$row[9]='DC';}
            if (strlen($row[9]) > 1) {$C3HU = "$row[9] $row[10]";}

            $manual_calls_data[] = array(
                'num' => $u,
                'date' => $row[0],
                'call_type' => $row[1],
                'server' => $row[2],
                'phone' => $row[3],
                'dialed' => $row[4],
                'lead_id' => $row[5],
                'callerid' => $row[6],
                'alias' => $row[7],
                'preset' => $row[8],
                'c3hu' => $C3HU
            );
        }

        // Lead Searches
        $stmt="SELECT event_date,source,results,seconds,search_query from ".$vicidial_lead_search_log_table." where user='" . mysqli_real_escape_string($link, $user) . "' and event_date >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and event_date <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' order by event_date desc limit 10000;";
        $rslt=mysql_to_mysqli($stmt, $link);
        $logs_to_print = mysqli_num_rows($rslt);

        $u=0;
        $lead_searches_data = array();
        while ($logs_to_print > $u) {
            $row=mysqli_fetch_row($rslt);
            $row[4] = preg_replace("/SELECT count\(\*\) from vicidial_list where/",'',$row[4]);
            $row[4] = preg_replace('/SELECT lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner from vicidial_list where /','',$row[4]);

            if (strlen($row[4]) > 100) {$row[4] = substr($row[4], 0, 100);}

            $u++;
            $lead_searches_data[] = array(
                'num' => $u,
                'date' => $row[0],
                'source' => $row[1],
                'results' => $row[2],
                'seconds' => $row[3],
                'query' => $row[4]
            );
        }
        // Preview Lead Skips
        $stmt="SELECT user,event_date,lead_id,campaign_id,previous_status,previous_called_count from ".$vicidial_agent_skip_log_table." where user='" . mysqli_real_escape_string($link, $user) . "' and event_date >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and event_date <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' order by event_date desc limit 10000;";
        $rslt=mysql_to_mysqli($stmt, $link);
        $logs_to_print = mysqli_num_rows($rslt);

        $u=0;
        $preview_skips_data = array();
        while ($logs_to_print > $u) {
            $row=mysqli_fetch_row($rslt);
            $u++;
            $preview_skips_data[] = array(
                'num' => $u,
                'date' => $row[1],
                'lead_id' => $row[2],
                'status' => $row[4],
                'count' => $row[5],
                'campaign' => $row[3]
            );
        }

        // Agent Lead Switches
        $stmt="SELECT event_time,lead_id,stage,caller_code,uniqueid,comments,campaign_id from ".$vicidial_agent_function_log." where user='" . mysqli_real_escape_string($link, $user) . "' and event_time >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and event_time <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' and function='switch_lead' order by event_time desc limit 10000;";
        $rslt=mysql_to_mysqli($stmt, $link);
        $logs_to_print = mysqli_num_rows($rslt);

        $u=0;
        $lead_switches_data = array();
        while ($logs_to_print > $u) {
            $row=mysqli_fetch_row($rslt);
            $u++;
            $lead_switches_data[] = array(
                'num' => $u,
                'date' => $row[0],
                'from_lead_id' => $row[1],
                'to_lead_id' => $row[2],
                'call_id' => $row[3],
                'uniqueid' => $row[4],
                'phone' => $row[5],
                'campaign' => $row[6]
            );
        }

        // Manager Pause Code Approvals
        $stmt="SELECT event_time,user,user_group,campaign_id,comments from ".$vicidial_agent_function_log." where stage='" . mysqli_real_escape_string($link, $user) . "' and event_time >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and event_time <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' and function='mgrapr_pause_code' order by event_time desc limit 10000;";
        $rslt=mysql_to_mysqli($stmt, $link);
        $logs_to_print = mysqli_num_rows($rslt);

        $u=0;
        $pause_approvals_data = array();
        while ($logs_to_print > $u) {
            $row=mysqli_fetch_row($rslt);
            $u++;
            $pause_approvals_data[] = array(
                'num' => $u,
                'date' => $row[0],
                'agent' => $row[1],
                'agent_group' => $row[2],
                'campaign' => $row[3],
                'pause_code' => $row[4]
            );
        }

        // HCI Agent Log Records
        if ($SShopper_hold_inserts > 0) {
            $stmt="SELECT call_date,lead_id,phone_number,user_ip,campaign_id from ".$vicidial_hci_log." where user='" . mysqli_real_escape_string($link, $user) . "' and call_date >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and call_date <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' order by call_date desc limit 10000;";
            $rslt=mysql_to_mysqli($stmt, $link);
            $logs_to_print = mysqli_num_rows($rslt);
            if ($DB > 0) {echo "|$logs_to_print|$stmt|";}

            $u=0;
            $hci_log_data = array();
            while ($logs_to_print > $u) {
                $row=mysqli_fetch_row($rslt);
                $u++;
                $hci_log_data[] = array(
                    'num' => $u,
                    'date' => $row[0],
                    'lead_id' => $row[1],
                    'phone' => $row[2],
                    'user_ip' => $row[3],
                    'campaign' => $row[4]
                );
            }
        }
    }

    // Inbound Closer Calls
    $stmt="SELECT call_date,length_in_sec,status,phone_number,campaign_id,queue_seconds,list_id,lead_id,term_reason,closecallid from ".$vicidial_closer_log_table." where user='" . mysqli_real_escape_string($link, $user) . "' and call_date >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and call_date <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' $query_call_status order by call_date desc limit 10000;";
    if ($did > 0) {
        $stmt="SELECT start_time,length_in_sec,0,caller_code,0,0,0,extension,0,0,uniqueid from ".$call_log_table." where channel_group='DID_INBOUND' and number_dialed='" . mysqli_real_escape_string($link, $user) . "' and start_time >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and start_time <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' order by start_time desc limit 10000;";
    } else {
        if ($firstlastname_display_user_stats > 0) {
            $stmt="SELECT call_date,length_in_sec,vlog.status,vlog.phone_number,campaign_id,queue_seconds,vlog.list_id,vlog.lead_id,term_reason,closecallid,first_name,last_name from ".$vicidial_closer_log_table." vlog, vicidial_list vlist where vlog.user='" . mysqli_real_escape_string($link, $user) . "' and call_date >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and call_date <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' and vlog.lead_id=vlist.lead_id $VLquery_call_status order by call_date desc limit 10000;";
        }
    }
    if ($DB) {$MAIN.="inbound calls|$stmt|";}
    $rslt=mysql_to_mysqli($stmt, $link);
    $logs_to_print = mysqli_num_rows($rslt);

    $u=0;
    $TOTALinSECONDS=0;
    $TOTALagentSECONDS=0;
    $inbound_calls_data = array();
    while ($logs_to_print > $u) {
        $row=mysqli_fetch_row($rslt);
        if ($did > 0) {
            if (strlen($row[7]) > 17) {
                $row[7] = substr($row[7], -9);
                $row[7] = ($row[7] + 0);
            } else {
                $row[7]='0';
                $lead_id_stmt="select lead_id from vicidial_closer_log where uniqueid='$row[10]'";
                $lead_id_rslt=mysql_to_mysqli($lead_id_stmt, $link);
                if (mysqli_num_rows($lead_id_rslt)>0) {
                    $lead_id_row=mysqli_fetch_row($lead_id_rslt);
                    $row[7]=$lead_id_row[0];
                }
            }
        }
        $TOTALinSECONDS = ($TOTALinSECONDS + $row[1]);
        $AGENTseconds = ($row[1] - $row[5]);
        if ($AGENTseconds < 0) {$AGENTseconds=0;}
        $TOTALagentSECONDS = ($TOTALagentSECONDS + $AGENTseconds);

        if ($LOGadmin_hide_phone_data != '0') {
            if ($DB > 0) {echo "HIDEPHONEDATA|$row[10]|$LOGadmin_hide_phone_data|\n";}
            $phone_temp = $row[3];
            if (strlen($phone_temp) > 0) {
                if ($LOGadmin_hide_phone_data == '4_DIGITS') {
                    $row[3] = str_repeat("X", (strlen($phone_temp) - 4)) . substr($phone_temp,-4,4);
                } elseif ($LOGadmin_hide_phone_data == '3_DIGITS') {
                    $row[3] = str_repeat("X", (strlen($phone_temp) - 3)) . substr($phone_temp,-3,3);
                } elseif ($LOGadmin_hide_phone_data == '2_DIGITS') {
                    $row[3] = str_repeat("X", (strlen($phone_temp) - 2)) . substr($phone_temp,-2,2);
                } else {
                    $row[3] = preg_replace("/./",'X',$phone_temp);
                }
            }
        }

        $u++;
        $inbound_calls_data[] = array(
            'num' => $u,
            'date' => $row[0],
            'length' => $row[1],
            'status' => $row[2],
            'phone' => $row[3],
            'campaign' => $row[4],
            'wait' => $row[5],
            'agent' => $AGENTseconds,
            'list' => $row[6],
            'lead_id' => $row[7],
            'name' => ($firstlastname_display_user_stats > 0) ? "$row[10] $row[11]" : '',
            'term_reason' => $row[8]
        );
        if (strlen($query_call_status) > 5) {
            $CS_vicidial_id_list .= "'$row[9]',";
        }
    }

    // Recordings
    $mute_column='';   $mute_column_csv='';
    $agent_column='';   $agent_column_csv='';
    $ANI_column='';   $ANI_column_csv='';
    if ($SSmute_recordings > 0) {
        $mute_column = "<td align=center><font size=2>"._QXZ("MUTE")." &nbsp; </td>";
        $mute_column_csv = ",\""._QXZ("MUTE")."\"";
    }
    if ($NVAuser > 0) {
        $agent_column = "<td align=center><font size=2>"._QXZ("AGENT")." &nbsp; </td>";
        $agent_column_csv = ",\""._QXZ("AGENT")."\"";
        $ANI_column = "<td align=center><font size=2>"._QXZ("ANI")." &nbsp; </td>";
        $ANI_column_csv = ",\""._QXZ("ANI")."\"";
    }

    if (strlen($query_call_status) > 5) {
        $CS_vicidial_id_list .= "'X'";
        $CS_vicidial_id_list_SQL = "and vicidial_id IN($CS_vicidial_id_list)";
    }

    $stmt="SELECT recording_id,channel,server_ip,extension,start_time,start_epoch,end_time,end_epoch,length_in_sec,length_in_min,filename,location,lead_id,user,vicidial_id from ".$recording_log_table." where user='" . mysqli_real_escape_string($link, $user) . "' and start_time >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and start_time <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' $CS_vicidial_id_list_SQL order by recording_id desc limit 10000;";
    $rslt=mysql_to_mysqli($stmt, $link);
    $logs_to_print = mysqli_num_rows($rslt);
    if ($DB) {$MAIN.="agent activity|$stmt|";}

    $u=0;
    $recordings_data = array();
    while ($logs_to_print > $u) {
        $row=mysqli_fetch_row($rslt);
        $location = $row[11];
        $CSV_location=$row[11];
        $vicidial_id=$row[14];

        if (strlen($location)>2) {
            $URLserver_ip = $location;
            $URLserver_ip = preg_replace('/http:\/\//i', '',$URLserver_ip);
            $URLserver_ip = preg_replace('/https:\/\//i', '',$URLserver_ip);
            $URLserver_ip = preg_replace('/\/.*/i', '',$URLserver_ip);
            $stmt="SELECT count(*) from servers where server_ip='$URLserver_ip';";
            $rsltx=mysql_to_mysqli($stmt, $link);
            $rowx=mysqli_fetch_row($rsltx);
            
            if ($rowx[0] > 0) {
                $stmt="SELECT recording_web_link,alt_server_ip,external_server_ip from servers where server_ip='$URLserver_ip';";
                $rsltx=mysql_to_mysqli($stmt, $link);
                $rowx=mysqli_fetch_row($rsltx);
                
                if (preg_match("/ALT_IP/i",$rowx[0])) {
                    $location = preg_replace("/$URLserver_ip/i", "$rowx[1]", $location);
                }
                if (preg_match("/EXTERNAL_IP/i",$rowx[0])) {
                    $location = preg_replace("/$URLserver_ip/i", "$rowx[2]", $location);
                }
            }
        }
        if ($SSmute_recordings > 0) {
            $mute_events=0;
            $stmt="SELECT count(*) from ".$vicidial_agent_function_log." where user='" . mysqli_real_escape_string($link, $user) . "' and event_time >= '$row[4]' and event_time <= '$row[6]' and function='mute_rec' and lead_id='$row[12]' and stage='on';";
            $rsltx=mysql_to_mysqli($stmt, $link);
            $flogs_to_print = mysqli_num_rows($rsltx);
            if ($flogs_to_print > 0) {
                $rowx=mysqli_fetch_row($rsltx);
                $mute_events = $rowx[0];
            }
        }
        if ($NVAuser > 0) {
            $agent_user='';
            $stmt="SELECT user from ".$vicidial_agent_log_table." where lead_id='$row[12]' and event_time <= '$row[4]'  and event_time >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01' order by event_time;";
            $rsltx=mysql_to_mysqli($stmt, $link);
            $valogs_to_print = mysqli_num_rows($rsltx);
            if ($valogs_to_print > 0) {
                $rowx=mysqli_fetch_row($rsltx);
                $agent_user = $rowx[0];
            }

            $ANI='';
            $ANI_stmt="SELECT caller_code from call_log where uniqueid='$vicidial_id' order by start_time;";
            $ANI_rslt=mysql_to_mysqli($ANI_stmt, $link);
            $ANI_logs_to_print = mysqli_num_rows($ANI_rslt);
            if ($ANI_logs_to_print > 0) {
                $ANI_row=mysqli_fetch_row($ANI_rslt);
                $ANI = $ANI_row[0];
            }
        }

        if (strlen($location)>30) {
            $locat = substr($location,0,27);  $locat = "$locat...";
        } else {
            $locat = $location;
        }
        if ( (preg_match('/ftp/i',$location)) or (preg_match('/http/i',$location)) ) {
            if ($log_recording_access<1) {
                $location = "<a href=\"$location\">$locat</a>";
            } else {
                $location = "<a href=\"recording_log_redirect.php?recording_id=$row[0]&lead_id=$row[12]&search_archived_data=$search_archived_data\">$locat</a>";
            }
        } else {
            $location = $locat;
        }
        $u++;
        $recordings_data[] = array(
            'num' => $u,
            'agent_user' => ($NVAuser > 0) ? $agent_user : '',
            'lead_id' => $row[12],
            'ANI' => ($NVAuser > 0) ? $ANI : '',
            'date' => $row[4],
            'seconds' => $row[8],
            'recid' => $row[0],
            'filename' => $row[10],
            'location' => $location,
            'mute_events' => ($SSmute_recordings > 0) ? $mute_events : ''
        );
    }
}

// Generate HTML output
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

// Generate the HTML structure
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
        </div>";

// Display pause code report if selected
if ($pause_code_rpt >= 1) {
    $HTML .= "
        <div class='card mb-20'>
            <div class='card-header'>
                <div class='card-title'>"._QXZ("Agent Pause Logs")."</div>
                <div>
                    <a href='$download_link&file_download=11' class='btn btn-secondary'>
                        <i class='fas fa-download'></i> "._QXZ("DOWNLOAD")."
                    </a>
                </div>
            </div>
            <div class='card-body'>
                <div class='table-container'>
                    <table class='table'>
                        <thead>
                            <tr>
                                <th>"._QXZ("EVENT TIME")."</th>
                                <th>"._QXZ("CAMPAIGN ID")."</th>
                                <th>"._QXZ("USER GROUP")."</th>
                                <th>"._QXZ("PAUSE CODE")."</th>
                                <th>"._QXZ("PAUSE LENGTH (HH:MM:SS)")."</th>
                            </tr>
                        </thead>
                        <tbody>";
    
    foreach ($pause_logs as $pause_row) {
        $HTML .= "
                            <tr>
                                <td>$pause_row[event_time]</td>
                                <td>$pause_row[campaign_id]</td>
                                <td>$pause_row[user_group]</td>
                                <td>$pause_row[sub_status]</td>
                                <td>$pause_row[pause_length]</td>
                            </tr>";
    }
    
    $HTML .= "
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan='4' class='text-right'><strong>"._QXZ("TOTAL").":</strong></td>
                                <td>$total_pause_time_formatted</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class='mt-20'>
                    <a href='user_stats.php?DB=$DB&user=$user&begin_date=$begin_date&end_date=$end_date' class='btn btn-primary'>
                        "._QXZ("VIEW USER STATS")."
                    </a>
                </div>
            </div>
        </div>";
}
// Display park report if selected
elseif ($park_rpt >= 1) {
    $HTML .= "
        <div class='card mb-20'>
            <div class='card-header'>
                <div class='card-title'>"._QXZ("Agent Parked Call Logs")."</div>
                <div>
                    <a href='$download_link&file_download=12' class='btn btn-secondary'>
                        <i class='fas fa-download'></i> "._QXZ("DOWNLOAD")."
                    </a>
                </div>
            </div>
            <div class='card-body'>
                <div class='table-container'>
                    <table class='table'>
                        <thead>
                            <tr>
                                <th>"._QXZ("PARKED TIME")."</th>
                                <th>"._QXZ("STATUS")."</th>
                                <th>"._QXZ("LEAD ID")."</th>
                                <th>"._QXZ("PARKED SEC")."</th>
                            </tr>
                        </thead>
                        <tbody>";
    
    foreach ($park_logs as $park_row) {
        $HTML .= "
                            <tr>
                                <td>$park_row[parked_time]</td>
                                <td>$park_row[status]</td>
                                <td><a href='admin_modify_lead.php?lead_id=$park_row[lead_id]' target='_blank'>$park_row[lead_id]</a></td>
                                <td>$park_row[parked_sec]</td>
                            </tr>";
    }
    
    $HTML .= "
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan='3' class='text-right'><strong>"._QXZ("TOTAL").":</strong></td>
                                <td>$total_park_time_formatted</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class='mt-20'>
                    <a href='user_stats.php?DB=$DB&user=$user&begin_date=$begin_date&end_date=$end_date' class='btn btn-primary'>
                        "._QXZ("VIEW USER STATS")."
                    </a>
                </div>
            </div>
        </div>";
}
// Display regular reports
else {
    $HTML .= "
        <div class='layout'>
            <div class='sidebar'>
                <ul class='sidebar-menu'>
                    <li><a href='#talk-time' class='active'>"._QXZ("Talk Time & Status")."</a></li>
                    <li><a href='#login-logout'>"._QXZ("Login/Logout Time")."</a></li>
                    <li><a href='#timeclock'>"._QXZ("Timeclock")."</a></li>
                    <li><a href='#closer-groups'>"._QXZ("Closer Groups")."</a></li>
                    <li><a href='#outbound-calls'>"._QXZ("Outbound Calls")."</a></li>
                    <li><a href='#inbound-calls'>"._QXZ("Inbound Calls")."</a></li>
                    <li><a href='#agent-activity'>"._QXZ("Agent Activity")."</a></li>
                    <li><a href='#recordings'>"._QXZ("Recordings")."</a></li>
                    <li><a href='#manual-calls'>"._QXZ("Manual Calls")."</a></li>
                    <li><a href='#lead-searches'>"._QXZ("Lead Searches")."</a></li>
                    <li><a href='#preview-skips'>"._QXZ("Preview Skips")."</a></li>
                    <li><a href='#lead-switches'>"._QXZ("Lead Switches")."</a></li>
                    <li><a href='#pause-approvals'>"._QXZ("Pause Approvals")."</a></li>";
    
    if ($SShopper_hold_inserts > 0) {
        $HTML .= "
                    <li><a href='#hci-logs'>"._QXZ("HCI Logs")."</a></li>";
    }
    
    $HTML .= "
                </ul>
            </div>
            
            <div class='main-content'>
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
                </div>";
    
    // Talk Time and Status
    if ($did < 1) {
        $HTML .= "
                <div class='card mb-20' id='talk-time'>
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
                                <tbody>";
        
        foreach ($talk_status_data as $status_data) {
            $HTML .= "
                                    <tr>
                                        <td>$status_data[status]</td>
                                        <td>$status_data[count]</td>
                                        <td>$status_data[time]</td>
                                    </tr>";
        }
        
        $HTML .= "
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td><strong>"._QXZ("TOTAL CALLS")."</strong></td>
                                        <td><strong>$total_calls</strong></td>
                                        <td><strong>$call_hours_minutes</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>";
        
        // Login/Logout Time
        $HTML .= "
                <div class='card mb-20' id='login-logout'>
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
                                <tbody>";
        
        foreach ($login_logout_data as $event) {
            if ($event['type'] == 'login') {
                $HTML .= "
                                    <tr>
                                        <td>$event[event]</td>
                                        <td>$event[date]</td>
                                        <td>$event[campaign]</td>
                                        <td>$event[group]</td>
                                        <td>$event[session]</td>
                                        <td>$event[server]</td>
                                        <td>$event[phone]</td>
                                        <td>$event[computer]</td>
                                        <td>$event[phone_login]</td>
                                        <td>$event[phone_ip]</td>
                                    </tr>";
            } else {
                $HTML .= "
                                    <tr>
                                        <td>$event[event]</td>
                                        <td>$event[date]</td>
                                        <td>$event[campaign]</td>
                                        <td>$event[group]</td>
                                        <td>$event[session_time]</td>
                                        <td colspan='5'>$event[phone]</td>
                                    </tr>";
            }
        }
        
        $HTML .= "
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td><strong>"._QXZ("TOTAL")."</strong></td>
                                        <td colspan='4'></td>
                                        <td><strong>$total_login_hours_minutes</strong></td>
                                        <td colspan='4'></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>";
        
        // Timeclock
        $HTML .= "
                <div class='card mb-20' id='timeclock'>
                    <div class='card-header'>
                        <div class='card-title'>"._QXZ("Timeclock Login and Logout Time")."</div>
                        <div>
                            <a href='$download_link&file_download=3' class='btn btn-secondary'>
                                <i class='fas fa-download'></i> "._QXZ("DOWNLOAD")."
                            </a>
                        </div>
                    </div>
                    <div class='card-body'>
                        <div class='table-container'>
                            <table class='table'>
                                <thead>
                                    <tr>
                                        <th>"._QXZ("ID")."</th>
                                        <th>"._QXZ("EDIT")."</th>
                                        <th>"._QXZ("EVENT")."</th>
                                        <th>"._QXZ("DATE")."</th>
                                        <th>"._QXZ("IP ADDRESS")."</th>
                                        <th>"._QXZ("GROUP")."</th>
                                        <th>"._QXZ("HOURS:MM:SS")."</th>
                                    </tr>
                                </thead>
                                <tbody>";
        
        foreach ($timeclock_data as $event) {
            $HTML .= "
                                    <tr>
                                        <td><a href='timeclock_edit.php?timeclock_id=$event[id]'>$event[id]</a></td>
                                        <td>$event[manager_edit]</td>
                                        <td>$event[event]</td>
                                        <td>$event[date]</td>
                                        <td>$event[ip_address]</td>
                                        <td>$event[group]</td>
                                        <td>$event[session_time]</td>
                                    </tr>";
        }
        
        $HTML .= "
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan='5'></td>
                                        <td><strong>"._QXZ("TOTAL")."</strong></td>
                                        <td><strong>$total_login_hours_minutes</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>";
        
        // Closer In-Group Selection Logs
        $HTML .= "
                <div class='card mb-20' id='closer-groups'>
                    <div class='card-header'>
                        <div class='card-title'>"._QXZ("Closer In-Group Selection Logs")."</div>
                        <div>
                            <a href='$download_link&file_download=4' class='btn btn-secondary'>
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
                                        <th>"._QXZ("CAMPAIGN")."</th>
                                        <th>"._QXZ("BLEND")."</th>
                                        <th>"._QXZ("GROUPS")."</th>
                                        <th>"._QXZ("MANAGER")."</th>
                                    </tr>
                                </thead>
                                <tbody>";
        
        foreach ($closer_data as $row) {
            $HTML .= "
                                    <tr>
                                        <td>$row[num]</td>
                                        <td>$row[date]</td>
                                        <td>$row[campaign]</td>
                                        <td>$row[blend]</td>
                                        <td>$row[groups]</td>
                                        <td>$row[manager]</td>
                                    </tr>";
        }
        
        $HTML .= "
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>";
        
        // Outbound Calls
        $HTML .= "
                <div class='card mb-20' id='outbound-calls'>
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
                                        <th>"._QXZ("LEAD")."</th>";
        
        if ($firstlastname_display_user_stats > 0) {
            $HTML .= "
                                        <th>"._QXZ("NAME")."</th>";
        }
        
        $HTML .= "
                                        <th>"._QXZ("HANGUP REASON")."</th>
                                    </tr>
                                </thead>
                                <tbody>";
        
        foreach ($outbound_calls_data as $call) {
            $HTML .= "
                                    <tr>
                                        <td>$call[num]</td>
                                        <td>$call[date]</td>
                                        <td>$call[length]</td>
                                        <td>$call[status]</td>
                                        <td>$call[phone]</td>
                                        <td>$call[campaign]</td>
                                        <td>$call[group]</td>
                                        <td>$call[list]</td>
                                        <td><a href='admin_modify_lead.php?lead_id=$call[lead_id]' target='_blank'>$call[lead_id]</a></td>";
            
            if ($firstlastname_display_user_stats > 0) {
                $HTML .= "
                                        <td>$call[name]</td>";
            }
            
            $HTML .= "
                                        <td>$call[term_reason]</td>
                                    </tr>";
        }
        
        $HTML .= "
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>";
        
        // Outbound Emails
        if ($allow_emails > 0) {
            $HTML .= "
                <div class='card mb-20' id='outbound-emails'>
                    <div class='card-header'>
                        <div class='card-title'>"._QXZ("Outbound Emails")."</div>
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
                                        <th>"._QXZ("CAMPAIGN")."</th>
                                        <th>"._QXZ("EMAIL TO")."</th>
                                        <th>"._QXZ("ATTACHMENTS")."</th>
                                        <th>"._QXZ("LEAD")."</th>
                                    </tr>
                                </thead>
                                <tbody>";
            
            foreach ($outbound_emails_data as $email) {
                $HTML .= "
                                    <tr>
                                        <td>$email[num]</td>
                                        <td>$email[date]</td>
                                        <td>$email[campaign]</td>
                                        <td>$email[email_to]</td>
                                        <td>$email[attachments]</td>
                                        <td><a href='admin_modify_lead.php?lead_id=$email[lead_id]' target='_blank'>$email[lead_id]</a></td>
                                    </tr>
                                    <tr>
                                        <td colspan='6'><em>"._QXZ("MESSAGE").": $email[message]</em></td>
                                    </tr>";
            }
            
            $HTML .= "
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>";
        }
    }
    
    // Inbound Calls
    $HTML .= "
            <div class='card mb-20' id='inbound-calls'>
                <div class='card-header'>
                    <div class='card-title'>"._QXZ("Inbound Closer Calls")."</div>
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
                                    <th>"._QXZ("LEAD")."</th>";
    
    if ($firstlastname_display_user_stats > 0) {
        $HTML .= "
                                    <th>"._QXZ("NAME")."</th>";
    }
    
    $HTML .= "
                                    <th>"._QXZ("HANGUP REASON")."</th>
                                </tr>
                            </thead>
                            <tbody>";
    
    foreach ($inbound_calls_data as $call) {
        $HTML .= "
                                <tr>
                                    <td>$call[num]</td>
                                    <td>$call[date]</td>
                                    <td>$call[length]</td>
                                    <td>$call[status]</td>
                                    <td>$call[phone]</td>
                                    <td>$call[campaign]</td>
                                    <td>$call[wait]</td>
                                    <td>$call[agent]</td>
                                    <td>$call[list]</td>
                                    <td><a href='admin_modify_lead.php?lead_id=$call[lead_id]' target='_blank'>$call[lead_id]</a></td>";
        
        if ($firstlastname_display_user_stats > 0) {
            $HTML .= "
                                    <td>$call[name]</td>";
        }
        
        $HTML .= "
                                    <td>$call[term_reason]</td>
                                </tr>";
    }
    
    $HTML .= "
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan='2'><strong>"._QXZ("TOTALS")."</strong></td>
                                    <td><strong>$TOTALinSECONDS</strong></td>
                                    <td colspan='4'></td>
                                    <td><strong>$TOTALagentSECONDS</strong></td>
                                    <td colspan='";
    
    if ($firstlastname_display_user_stats > 0) {
        $HTML .= "3";
    } else {
        $HTML .= "2";
    }
    
    $HTML .= "'></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>";
    
    // Agent Activity
    if ($did < 1) {
        $HTML .= "
            <div class='card mb-20' id='agent-activity'>
                <div class='card-header'>
                    <div class='card-title'>"._QXZ("Agent Activity")."</div>
                    <div>
                        <a href='$download_link&file_download=7' class='btn btn-secondary'>
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
                                    <th>"._QXZ("PAUSE")."</th>
                                    <th>"._QXZ("WAIT")."</th>
                                    <th>"._QXZ("TALK")."</th>
                                    <th>"._QXZ("DISPO")."</th>
                                    <th>"._QXZ("DEAD")."</th>
                                    <th>"._QXZ("CUSTOMER")."</th>
                                    <th>"._QXZ("VISIBLE")."</th>
                                    <th>"._QXZ("HIDDEN")."</th>
                                    <th>"._QXZ("STATUS")."</th>
                                    <th>"._QXZ("LEAD")."</th>
                                    <th>"._QXZ("TYPE")."</th>
                                    <th>"._QXZ("CAMPAIGN")."</th>
                                    <th>"._QXZ("PAUSE CODE")."</th>
                                </tr>
                            </thead>
                            <tbody>";
        
        foreach ($agent_activity_data as $activity) {
            $HTML .= "
                                <tr>
                                    <td>$activity[num]</td>
                                    <td>$activity[event_time]</td>
                                    <td>$activity[pause_sec]</td>
                                    <td>$activity[wait_sec]</td>
                                    <td>$activity[talk_sec]</td>
                                    <td>$activity[dispo_sec]</td>
                                    <td>$activity[dead_sec]</td>
                                    <td>$activity[customer_sec]</td>
                                    <td>$activity[visible_sec]</td>
                                    <td>$activity[hidden_sec]</td>
                                    <td>$activity[status]</td>
                                    <td><a href='admin_modify_lead.php?lead_id=$activity[lead_id]' target='_blank'>$activity[lead_id]</a></td>
                                    <td>$activity[call_type]</td>
                                    <td>$activity[campaign]</td>
                                    <td>$activity[pause_code]</td>
                                </tr>";
        }
        
        $HTML .= "
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan='2'><strong>"._QXZ("TOTALS")."</strong></td>
                                    <td><strong>$TOTALpauseSECONDS</strong></td>
                                    <td><strong>$TOTALwaitSECONDS</strong></td>
                                    <td><strong>$TOTALtalkSECONDS</strong></td>
                                    <td><strong>$TOTALdispoSECONDS</strong></td>
                                    <td><strong>$TOTALdeadSECONDS</strong></td>
                                    <td><strong>$TOTALcustomerSECONDS</strong></td>
                                    <td><strong>$TOT_VISIBLE</strong></td>
                                    <td><strong>$TOT_HIDDEN</strong></td>
                                    <td colspan='5'></td>
                                </tr>
                                <tr>
                                    <td colspan='2'><em>("._QXZ("in HH:MM:SS").")</em></td>
                                    <td><em>$TOTALpauseSECONDShh</em></td>
                                    <td><em>$TOTALwaitSECONDShh</em></td>
                                    <td><em>$TOTALtalkSECONDShh</em></td>
                                    <td><em>$TOTALdispoSECONDShh</em></td>
                                    <td><em>$TOTALdeadSECONDShh</em></td>
                                    <td><em>$TOTALcustomerSECONDShh</em></td>
                                    <td><em>$TOTALvisibleSECONDShh</em></td>
                                    <td><em>$TOTALhiddenSECONDShh</em></td>
                                    <td colspan='5'></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>";
        
        // Manual Calls
        $HTML .= "
            <div class='card mb-20' id='manual-calls'>
                <div class='card-header'>
                    <div class='card-title'>"._QXZ("Manual Outbound Calls")."</div>
                    <div>
                        <a href='$download_link&file_download=9' class='btn btn-secondary'>
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
                                    <th>"._QXZ("CALL TYPE")."</th>
                                    <th>"._QXZ("SERVER")."</th>
                                    <th>"._QXZ("PHONE")."</th>
                                    <th>"._QXZ("DIALED")."</th>
                                    <th>"._QXZ("LEAD")."</th>
                                    <th>"._QXZ("CALLERID")."</th>
                                    <th>"._QXZ("ALIAS")."</th>
                                    <th>"._QXZ("PRESET")."</th>
                                    <th>"._QXZ("C3HU")."</th>
                                </tr>
                            </thead>
                            <tbody>";
        
        foreach ($manual_calls_data as $call) {
            $HTML .= "
                                <tr>
                                    <td>$call[num]</td>
                                    <td>$call[date]</td>
                                    <td>$call[call_type]</td>
                                    <td>$call[server]</td>
                                    <td>$call[phone]</td>
                                    <td>$call[dialed]</td>
                                    <td><a href='admin_modify_lead.php?lead_id=$call[lead_id]' target='_blank'>$call[lead_id]</a></td>
                                    <td>$call[callerid]</td>
                                    <td>$call[alias]</td>
                                    <td>$call[preset]</td>
                                    <td>$call[c3hu]</td>
                                </tr>";
        }
        
        $HTML .= "
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>";
        
        // Lead Searches
        $HTML .= "
            <div class='card mb-20' id='lead-searches'>
                <div class='card-header'>
                    <div class='card-title'>"._QXZ("Lead Searches")."</div>
                    <div>
                        <a href='$download_link&file_download=10' class='btn btn-secondary'>
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
                                    <th>"._QXZ("TYPE")."</th>
                                    <th>"._QXZ("RESULTS")."</th>
                                    <th>"._QXZ("SEC")."</th>
                                    <th>"._QXZ("QUERY")."</th>
                                </tr>
                            </thead>
                            <tbody>";
        
        foreach ($lead_searches_data as $search) {
            $HTML .= "
                                <tr>
                                    <td>$search[num]</td>
                                    <td>$search[date]</td>
                                    <td>$search[source]</td>
                                    <td>$search[results]</td>
                                    <td>$search[seconds]</td>
                                    <td>$search[query]</td>
                                </tr>";
        }
        
        $HTML .= "
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>";
        
        // Preview Lead Skips
        $HTML .= "
            <div class='card mb-20' id='preview-skips'>
                <div class='card-header'>
                    <div class='card-title'>"._QXZ("Preview Lead Skips")."</div>
                    <div>
                        <a href='$download_link&file_download=11' class='btn btn-secondary'>
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
                                    <th>"._QXZ("LEAD ID")."</th>
                                    <th>"._QXZ("STATUS")."</th>
                                    <th>"._QXZ("COUNT")."</th>
                                    <th>"._QXZ("CAMPAIGN")."</th>
                                </tr>
                            </thead>
                            <tbody>";
        
        foreach ($preview_skips_data as $skip) {
            $HTML .= "
                                <tr>
                                    <td>$skip[num]</td>
                                    <td>$skip[date]</td>
                                    <td><a href='admin_modify_lead.php?lead_id=$skip[lead_id]' target='_blank'>$skip[lead_id]</a></td>
                                    <td>$skip[status]</td>
                                    <td>$skip[count]</td>
                                    <td>$skip[campaign]</td>
                                </tr>";
        }
        
        $HTML .= "
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>";
        
        // Agent Lead Switches
        $HTML .= "
            <div class='card mb-20' id='lead-switches'>
                <div class='card-header'>
                    <div class='card-title'>"._QXZ("Agent Lead Switches")."</div>
                    <div>
                        <a href='$download_link&file_download=13' class='btn btn-secondary'>
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
                                    <th>"._QXZ("FROM LEAD ID")."</th>
                                    <th>"._QXZ("TO LEAD ID")."</th>
                                    <th>"._QXZ("CALL ID")."</th>
                                    <th>"._QXZ("UNIQUEID")."</th>
                                    <th>"._QXZ("PHONE")."</th>
                                    <th>"._QXZ("CAMPAIGN")."</th>
                                </tr>
                            </thead>
                            <tbody>";
        
        foreach ($lead_switches_data as $switch) {
            $HTML .= "
                                <tr>
                                    <td>$switch[num]</td>
                                    <td>$switch[date]</td>
                                    <td><a href='admin_modify_lead.php?lead_id=$switch[from_lead_id]' target='_blank'>$switch[from_lead_id]</a></td>
                                    <td><a href='admin_modify_lead.php?lead_id=$switch[to_lead_id]' target='_blank'>$switch[to_lead_id]</a></td>
                                    <td>$switch[call_id]</td>
                                    <td>$switch[uniqueid]</td>
                                    <td>$switch[phone]</td>
                                    <td>$switch[campaign]</td>
                                </tr>";
        }
        
        $HTML .= "
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>";
        
        // Manager Pause Code Approvals
        $HTML .= "
            <div class='card mb-20' id='pause-approvals'>
                <div class='card-header'>
                    <div class='card-title'>"._QXZ("Manager Pause Code Approvals")."</div>
                    <div>
                        <a href='$download_link&file_download=14' class='btn btn-secondary'>
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
                                    <th>"._QXZ("AGENT")."</th>
                                    <th>"._QXZ("AGENT USER GROUP")."</th>
                                    <th>"._QXZ("CAMPAIGN")."</th>
                                    <th>"._QXZ("PAUSE CODE")."</th>
                                </tr>
                            </thead>
                            <tbody>";
        
        foreach ($pause_approvals_data as $approval) {
            $HTML .= "
                                <tr>
                                    <td>$approval[num]</td>
                                    <td>$approval[date]</td>
                                    <td><a href='$PHP_SELF?user=$approval[agent]' target='_blank'>$approval[agent]</a></td>
                                    <td>$approval[agent_group]</td>
                                    <td>$approval[campaign]</td>
                                    <td>$approval[pause_code]</td>
                                </tr>";
        }
        
        $HTML .= "
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>";
        
        // HCI Agent Log Records
        if ($SShopper_hold_inserts > 0) {
            $HTML .= "
            <div class='card mb-20' id='hci-logs'>
                <div class='card-header'>
                    <div class='card-title'>"._QXZ("HCI Agent Log Records")."</div>
                    <div>
                        <a href='$download_link&file_download=16' class='btn btn-secondary'>
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
                                    <th>"._QXZ("LEAD ID")."</th>
                                    <th>"._QXZ("PHONE")."</th>
                                    <th>"._QXZ("CALL DATE")."</th>
                                    <th>"._QXZ("CAMPAIGN")."</th>
                                    <th>"._QXZ("USER IP")."</th>
                                </tr>
                            </thead>
                            <tbody>";
            
            foreach ($hci_log_data as $log) {
                $HTML .= "
                                <tr>
                                    <td>$log[num]</td>
                                    <td>$log[date]</td>
                                    <td><a href='admin_modify_lead.php?lead_id=$log[lead_id]' target='_blank'>$log[lead_id]</a></td>
                                    <td>$log[phone]</td>
                                    <td>$log[date]</td>
                                    <td>$log[campaign]</td>
                                    <td>$log[user_ip]</td>
                                </tr>";
            }
            
            $HTML .= "
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>";
        }
    }
    
    // Recordings
    $HTML .= "
            <div class='card mb-20' id='recordings'>
                <div class='card-header'>
                    <div class='card-title'>"._QXZ("Recordings")."</div>
                    <div>
                        <a href='$download_link&file_download=8' class='btn btn-secondary'>
                            <i class='fas fa-download'></i> "._QXZ("DOWNLOAD")."
                        </a>
                    </div>
                </div>
                <div class='card-body'>
                    <div class='table-container'>
                        <table class='table'>
                            <thead>
                                <tr>
                                    <th>#</th>";
    
    if ($NVAuser > 0) {
        $HTML .= "
                                    <th>"._QXZ("AGENT")."</th>";
    }
    
    $HTML .= "
                                    <th>"._QXZ("LEAD")."</th>";
    
    if ($NVAuser > 0) {
        $HTML .= "
                                    <th>"._QXZ("ANI")."</th>";
    }
    
    $HTML .= "
                                    <th>"._QXZ("DATE/TIME")."</th>
                                    <th>"._QXZ("SECONDS")."</th>
                                    <th>"._QXZ("RECID")."</th>
                                    <th>"._QXZ("FILENAME")."</th>
                                    <th>"._QXZ("LOCATION")."</th>";
    
    if ($SSmute_recordings > 0) {
        $HTML .= "
                                    <th>"._QXZ("MUTE")."</th>";
    }
    
    $HTML .= "
                                </tr>
                            </thead>
                            <tbody>";
    
    foreach ($recordings_data as $recording) {
        $HTML .= "
                                <tr>
                                    <td>$recording[num]</td>";
        
        if ($NVAuser > 0) {
            $HTML .= "
                                    <td>$recording[agent_user]</td>";
        }
        
        $HTML .= "
                                    <td><a href='admin_modify_lead.php?lead_id=$recording[lead_id]' target='_blank'>$recording[lead_id]</a></td>";
        
        if ($NVAuser > 0) {
            $HTML .= "
                                    <td>$recording[ANI]</td>";
        }
        
        $HTML .= "
                                    <td>$recording[date]</td>
                                    <td>$recording[seconds]</td>
                                    <td>$recording[recid]</td>
                                    <td>$recording[filename]</td>
                                    <td>$recording[location]</td>";
        
        if ($SSmute_recordings > 0) {
            $HTML .= "
                                    <td>$recording[mute_events]</td>";
        }
        
        $HTML .= "
                                </tr>";
    }
    
    $HTML .= "
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>";
    
    $HTML .= "
            </div>
        </div>
    </div>";
}

 $HTML .= "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>";

 $ENDtime = date("U");
 $RUNtime = ($ENDtime - $STARTtime);

// Generate CSV data for downloads
 $CSV_text1 = "\""._QXZ("Agent Talk Time and Status")."\"\n";
 $CSV_text1 .= "\"\",\""._QXZ("STATUS")."\",\""._QXZ("COUNT")."\",\""._QXZ("HOURS:MM:SS")."\"\n";

foreach ($talk_status_data as $status_data) {
    $CSV_text1 .= "\"\",\"$status_data[status]\",\"$status_data[count]\",\"$status_data[time]\"\n";
}
 $CSV_text1 .= "\"\",\""._QXZ("TOTAL CALLS")."\",\"$total_calls\",\"$call_hours_minutes\"\n";

 $CSV_text2 = "\""._QXZ("Agent Login and Logout Time")."\"\n";
 $CSV_text2 .= "\"\",\""._QXZ("EVENT")."\",\""._QXZ("DATE")."\",\""._QXZ("CAMPAIGN")."\",\""._QXZ("GROUP")."\",\""._QXZ("SESSION")."\",\""._QXZ("SERVER")."\",\""._QXZ("PHONE")."\",\""._QXZ("COMPUTER")."\",\""._QXZ("PHONE LOGIN")."\",\""._QXZ("PHONE IP")."\"\n";

foreach ($login_logout_data as $event) {
    if ($event['type'] == 'login') {
        $CSV_text2 .= "\"\",\"$event[event]\",\"$event[date]\",\"$event[campaign]\",\"$event[group]\",\"\",\"$event[session]\",\"$event[server]\",\"$event[phone]\",\"$event[computer]\",\"$event[phone_login]\",\"$event[phone_ip]\"\n";
    } else {
        $CSV_text2 .= "\"\",\"$event[event]\",\"$event[date]\",\"$event[campaign]\",\"$event[group]\",\"$event[session_time]\"\n";
    }
}
 $CSV_text2 .= "\"\",\""._QXZ("TOTAL")."\",\"\",\"\",\"\",\"\",\"$total_login_hours_minutes\"\n";

// Generate more CSV data for other sections as needed...

if ($file_download>0) {
    $FILE_TIME = date("Ymd-His");
    $CSVfilename = "user_stats_$user" . "_$FILE_TIME.csv";
    $CSV_var="CSV_text".$file_download;
    $CSV_text=preg_replace('/^\s+/', '', $$CSV_var);
    $CSV_text=preg_replace('/\n\s+,/', ',', $CSV_text);
    $CSV_text=preg_replace('/ +\"/', '"', $CSV_text);
    $CSV_text=preg_replace('/\" +/', '"', $CSV_text);
    
    // We'll be outputting a CSV file
    header('Content-type: application/octet-stream');
    
    // It will be called user_stats_YYYYMMDD-HHMMSS.csv
    header("Content-Disposition: attachment; filename=\"$CSVfilename\"");
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    ob_clean();
    flush();
    
    echo "$CSV_text";
} else {
    header ("Content-type: text/html; charset=utf-8");
    echo $HTML;
}

if ($db_source == 'S') {
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

 $stmt="UPDATE vicidial_report_log set run_time='$TOTALrun' where report_log_id='$report_log_id;";
if ($DB) {echo "|$stmt|\n";}
 $rslt=mysql_to_mysqli($stmt, $link);

exit;
?>