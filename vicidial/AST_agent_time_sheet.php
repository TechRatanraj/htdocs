<?php
# AST_agent_time_sheet.php - Modern Responsive UI Version

 $startMS = microtime();

require("dbconnect_mysqli.php");
require("functions.php");

 $report_name = 'User Time Sheet';
 $db_source = 'M';

 $PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
 $PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
 $PHP_SELF=$_SERVER['PHP_SELF'];
 $PHP_SELF = preg_replace('/\.php.*/i','.php',$PHP_SELF);
if (isset($_GET["agent"]))                {$agent=$_GET["agent"];}
    elseif (isset($_POST["agent"]))        {$agent=$_POST["agent"];}
if (isset($_GET["query_date"]))            {$query_date=$_GET["query_date"];}
    elseif (isset($_POST["query_date"]))    {$query_date=$_POST["query_date"];}
if (isset($_GET["end_date"]))              {$end_date=$_GET["end_date"];}
    elseif (isset($_POST["end_date"]))      {$end_date=$_POST["end_date"];}
if (isset($_GET["calls_summary"]))          {$calls_summary=$_GET["calls_summary"];}
    elseif (isset($_POST["calls_summary"]))  {$calls_summary=$_POST["calls_summary"];}
if (isset($_GET["submit"]))                {$submit=$_GET["submit"];}
    elseif (isset($_POST["submit"]))        {$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))                {$SUBMIT=$_GET["SUBMIT"];}
    elseif (isset($_POST["SUBMIT"]))        {$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["file_download"]))          {$file_download=$_GET["file_download"];}
    elseif (isset($_POST["file_download"]))  {$file_download=$_POST["file_download"];}
if (isset($_GET["search_archived_data"]))          {$search_archived_data=$_GET["search_archived_data"];}
    elseif (isset($_POST["search_archived_data"]))  {$search_archived_data=$_POST["search_archived_data"];}
if (isset($_GET["DB"]))                      {$DB=$_GET["DB"];}
    elseif (isset($_POST["DB"]))             {$DB=$_POST["DB"];}

 $NOW_DATE = date("Y-m-d");
 $NOW_TIME = date("Y-m-d H:i:s");
 $STARTtime = date("U");
if ( (!isset($query_date)) or (strlen($query_date) < 8) ) {$query_date = $NOW_DATE;}
if ( (!isset($end_date)) or (strlen($end_date) < 8) ) {$end_date = $NOW_DATE;}

 $DB=preg_replace("/[^0-9a-zA-Z]/","",$DB);

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
 $stmt = "SELECT use_non_latin,outbound_autodial_active,slave_db_server,reports_use_slave_db,user_territories_active,enable_languages,language_method,admin_screen_colors,report_default_format,allow_web_debug FROM system_settings;";
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
    $SSenable_languages =          $row[5];
    $SSlanguage_method =           $row[6];
    $SSadmin_screen_colors =      $row[7];
    $SSreport_default_format =     $row[8];
    $SSallow_web_debug =          $row[9];
    }
if ($SSallow_web_debug < 1) {$DB=0;}
##### END SETTINGS LOOKUP #####
###########################################

### ARCHIVED DATA CHECK CONFIGURATION
 $archives_available="N";
 $log_tables_array=array("vicidial_timeclock_log", "vicidial_agent_log");
for ($t=0; $t<count($log_tables_array); $t++) 
    {
    $table_name=$log_tables_array[$t];
    $archive_table_name=use_archive_table($table_name);
    if ($archive_table_name!=$table_name) {$archives_available="Y";}
    }

if ($search_archived_data) 
    {
    $vicidial_timeclock_log_table=use_archive_table("vicidial_timeclock_log");
    $vicidial_agent_log_table=use_archive_table("vicidial_agent_log");
    }
else
    {
    $vicidial_timeclock_log_table="vicidial_timeclock_log";
    $vicidial_agent_log_table="vicidial_agent_log";
    }
#############

 $query_date = preg_replace('/[^- \:\_0-9a-zA-Z]/',"",$query_date);
 $end_date = preg_replace('/[^- \:\_0-9a-zA-Z]/',"",$end_date);
 $file_download = preg_replace('/[^-_0-9a-zA-Z]/', '', $file_download);
 $search_archived_data = preg_replace('/[^-_0-9a-zA-Z]/', '', $search_archived_data);
 $submit = preg_replace('/[^-_0-9a-zA-Z]/', '', $submit);
 $SUBMIT = preg_replace('/[^-_0-9a-zA-Z]/', '', $SUBMIT);
 $calls_summary = preg_replace('/[^-_0-9a-zA-Z]/', '', $calls_summary);

if ($non_latin < 1)
    {
    $PHP_AUTH_USER = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_USER);
    $PHP_AUTH_PW = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_PW);
    $agent = preg_replace('/[^-_0-9a-zA-Z]/','',$agent);
    }
else
    {
    $PHP_AUTH_USER = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_USER);
    $PHP_AUTH_PW = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_PW);
    $agent = preg_replace('/[^-_0-9\p{L}]/u','',$agent);
    }

 $user=$agent;

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

 $stmt="INSERT INTO vicidial_report_log set event_date=NOW(), user='$PHP_AUTH_USER', ip_address='$LOGip', report_name='$report_name', browser='$LOGbrowser', referer='$LOGhttp_referer', notes='$LOGserver_name:$LOGserver_port $LOGscript_name |$user, $query_date, $end_date, $shift, $file_download, $report_display_type|', url='$LOGfull_url', webserver='$webserver_id';";
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

 $stmt="SELECT user_group from vicidial_users where user='$PHP_AUTH_USER';";
if ($DB) {$MAIN.="|$stmt|\n";}
 $rslt=mysql_to_mysqli($stmt, $link);
 $row=mysqli_fetch_row($rslt);
 $LOGuser_group =            $row[0];

 $stmt="SELECT allowed_campaigns,allowed_reports,admin_viewable_groups,admin_viewable_call_times from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) {$MAIN.="|$stmt|\n";}
 $rslt=mysql_to_mysqli($stmt, $link);
 $row=mysqli_fetch_row($rslt);
 $LOGallowed_campaigns =      $row[0];
 $LOGallowed_reports =        $row[1];
 $LOGadmin_viewable_groups =  $row[2];
 $LOGadmin_viewable_call_times = $row[3];

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

// Get agent info
 $stmt="SELECT full_name from vicidial_users where user='$agent' $vuLOGadmin_viewable_groupsSQL;";
 $rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {$MAIN.="|$stmt|\n";}
 $row=mysqli_fetch_row($rslt);
 $full_name = $row[0];

// Generate data for the report
 $query_date_BEGIN = "$query_date 00:00:00";   
 $query_date_END = "$query_date 23:59:59";
 $end_date_BEGIN = "$end_date 00:00:00";   
 $end_date_END = "$end_date 23:59:59";
 $time_BEGIN = "00:00:00";   
 $time_END = "23:59:59";

// Call Activity Summary
if ($calls_summary) {
    $stmt="select count(*) as calls,sum(talk_sec) as talk,avg(talk_sec),sum(pause_sec),avg(pause_sec),sum(wait_sec),avg(wait_sec),sum(dispo_sec),avg(dispo_sec) from ".$vicidial_agent_log_table." where event_time <= '" . mysqli_real_escape_string($link, $end_date_END) . "' and event_time >= '" . mysqli_real_escape_string($link, $query_date_BEGIN) . "' and user='" . mysqli_real_escape_string($link, $agent) . "' and pause_sec<48800 and wait_sec<48800 and talk_sec<48800 and dispo_sec<48800 limit 1;";
    $rslt=mysql_to_mysqli($stmt, $link);
    if ($DB) {$MAIN.="$stmt\n";}
    $row=mysqli_fetch_row($rslt);

    $TOTAL_TIME = ($row[1] + $row[3] + $row[5] + $row[7]);

    $TOTAL_TIME_HMS =      sec_convert($TOTAL_TIME,'H'); 
    $TALK_TIME_HMS =      sec_convert($row[1],'H'); 
    $PAUSE_TIME_HMS =     sec_convert($row[3],'H'); 
    $WAIT_TIME_HMS =      sec_convert($row[5],'H'); 
    $WRAPUP_TIME_HMS =   sec_convert($row[7],'H'); 
    $TALK_AVG_MS =          sec_convert($row[2],'H'); 
    $PAUSE_AVG_MS =        sec_convert($row[4],'H'); 
    $WAIT_AVG_MS =         sec_convert($row[6],'H'); 
    $WRAPUP_AVG_MS =     sec_convert($row[8],'H'); 

    $pfTOTAL_TIME_HMS =     sprintf("%8s", $TOTAL_TIME_HMS);
    $pfTALK_TIME_HMS =     sprintf("%8s", $TALK_TIME_HMS);
    $pfPAUSE_TIME_HMS =    sprintf("%8s", $PAUSE_TIME_HMS);
    $pfWAIT_TIME_HMS =     sprintf("%8s", $WAIT_TIME_HMS);
    $pfWRAPUP_TIME_HMS =   sprintf("%8s", $WRAPUP_TIME_HMS);
    $pfTALK_AVG_MS =      sprintf("%6s", $TALK_AVG_MS);
    $pfPAUSE_AVG_MS =     sprintf("%6s", $PAUSE_AVG_MS);
    $pfWAIT_AVG_MS =      sprintf("%6s", $WAIT_AVG_MS);
    $pfWRAPUP_AVG_MS =   sprintf("%6s", $WRAPUP_AVG_MS);

    // Prepare CSV data
    $CSV_text1 = "\""._QXZ("Agent Time Sheet")." - $NOW_TIME\"\n";
    $CSV_text1 .= "\""._QXZ("Time range").": $query_date_BEGIN "._QXZ("to")." $end_date_END\"\n";
    $CSV_text1 .= "\""._QXZ("AGENT TIME SHEET").": $agent - $full_name\"\n\n";
    $CSV_text1 .= "\"\",\""._QXZ("TOTAL CALLS TAKEN").": $row[0]\"\n";
    $CSV_text1 .= "\"\",\""._QXZ("TALK TIME").":\",\"$pfTALK_TIME_HMS\",\""._QXZ("AVERAGE").":\",\"$pfTALK_AVG_MS\"\n";
    $CSV_text1 .= "\"\",\""._QXZ("PAUSE TIME").":\",\"$pfPAUSE_TIME_HMS\",\""._QXZ("AVERAGE").":\",\"$pfPAUSE_AVG_MS\"\n";
    $CSV_text1 .= "\"\",\""._QXZ("WAIT TIME").":\",\"$pfWAIT_TIME_HMS\",\""._QXZ("AVERAGE").":\",\"$pfWAIT_AVG_MS\"\n";
    $CSV_text1 .= "\"\",\""._QXZ("WRAPUP TIME").":\",\"$pfWRAPUP_TIME_HMS\",\""._QXZ("AVERAGE").":\",\"$pfWRAPUP_AVG_MS\"\n";
    $CSV_text1 .= "\"\",\""._QXZ("TOTAL ACTIVE AGENT TIME").":\",\"$pfTOTAL_TIME_HMS\"\n\n";
}

// Timeclock records
 $total_login_time=0;
 $query_date_BEGIN_ary=explode(" ", $query_date_BEGIN);
 $end_date_END_ary=explode(" ", $end_date_END);
 $SQday_ARY =   explode('-',$query_date_BEGIN_ary[0]);
 $EQday_ARY =   explode('-',$end_date_END_ary[0]);
 $SQepoch = mktime(0, 0, 0, $SQday_ARY[1], $SQday_ARY[2], $SQday_ARY[0]);
 $EQepoch = mktime(23, 59, 59, $EQday_ARY[1], $EQday_ARY[2], $EQday_ARY[0]);

 $stmt="SELECT event,event_epoch,user_group,login_sec,ip_address,timeclock_id,manager_user from ".$vicidial_timeclock_log_table." where user='$agent' and event_epoch >= '$SQepoch'  and event_epoch <= '$EQepoch';";
if ($DB>0) {$MAIN.="|$stmt|";}
 $rslt=mysql_to_mysqli($stmt, $link);
 $events_to_print = mysqli_num_rows($rslt);

 $total_logs=0;
 $o=0;
 $timeclock_data = array();
while ($events_to_print > $o) {
    $row=mysqli_fetch_row($rslt);
    $timeclock_data[] = array(
        'id' => $row[5],
        'manager_user' => $row[6],
        'event' => $row[0],
        'event_epoch' => $row[1],
        'user_group' => $row[2],
        'login_sec' => $row[3],
        'ip_address' => $row[4],
        'event_date' => date("Y-m-d H:i:s", $row[1])
    );
    
    if (preg_match('/LOGOUT/', $row[0])) {
        $total_login_time = ($total_login_time + $row[3]);
    }
    $o++;
}

 $total_login_hours_minutes = sec_convert($total_login_time,'H');

// First and last activity
 $stmt="select event_time,UNIX_TIMESTAMP(event_time) from ".$vicidial_agent_log_table." where event_time <= '" . mysqli_real_escape_string($link, $end_date_END) . "' and event_time >= '" . mysqli_real_escape_string($link, $query_date_BEGIN) . "' and user='" . mysqli_real_escape_string($link, $agent) . "' order by event_time limit 1;";
 $rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {$MAIN.="|$stmt|\n";}
 $row=mysqli_fetch_row($rslt);
 $first_login_time = $row[0];
 $first_login_epoch = $row[1];

 $stmt="select event_time,UNIX_TIMESTAMP(event_time) from ".$vicidial_agent_log_table." where event_time <= '" . mysqli_real_escape_string($link, $end_date_END) . "' and event_time >= '" . mysqli_real_escape_string($link, $query_date_BEGIN) . "' and user='" . mysqli_real_escape_string($link, $agent) . "' order by event_time desc limit 1;";
 $rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {$MAIN.="|$stmt|\n";}
 $row=mysqli_fetch_row($rslt);
 $last_activity_time = $row[0];
 $last_activity_epoch = $row[1];

 $total_stmt="select sum(talk_sec+pause_sec+wait_sec+dispo_sec) from ".$vicidial_agent_log_table." where event_time <= '" . mysqli_real_escape_string($link, $end_date_END) . "' and event_time >= '" . mysqli_real_escape_string($link, $query_date_BEGIN) . "' and user='" . mysqli_real_escape_string($link, $agent) . "' and pause_sec<48800 and wait_sec<48800 and talk_sec<48800 and dispo_sec<48800 limit 1;";
 $total_rslt=mysql_to_mysqli($total_stmt, $link);
if ($DB) {$MAIN.="|$total_stmt|\n";}
 $total_row=mysqli_fetch_row($total_rslt);
 $login_time=$total_row[0];

 $LOGIN_TIME_HMS = sec_convert($login_time,'H');

// Prepare CSV data for timeclock
 $CSV_text2 = "\""._QXZ("Agent Time Sheet")." - $NOW_TIME\"\n";
 $CSV_text2 .= "\""._QXZ("Time range").": $query_date_BEGIN "._QXZ("to")." $end_date_END\"\n";
 $CSV_text2 .= "\""._QXZ("AGENT TIME SHEET").": $agent - $full_name\"\n\n";
 $CSV_text2 .= "\"\",\""._QXZ("FIRST LOGIN").":\",\"$first_login_time\"\n";
 $CSV_text2 .= "\"\",\""._QXZ("LAST LOG ACTIVITY").":\",\"$last_activity_time\"\n";
 $CSV_text2 .= "\"\",\""._QXZ("TOTAL LOGGED-IN TIME").":\",\"$LOGIN_TIME_HMS\"\n\n";
 $CSV_text2 .= "\""._QXZ("TIMECLOCK LOGIN/LOGOUT TIME").":\"\n";
 $CSV_text2 .= "\"\",\""._QXZ("ID")."\",\""._QXZ("EDIT")."\",\""._QXZ("EVENT")."\",\""._QXZ("DATE")."\",\""._QXZ("IP ADDRESS")."\",\""._QXZ("GROUP")."\",\""._QXZ("HOURS:MINUTES")."\"\n";

foreach ($timeclock_data as $event) {
    $manager_edit = '';
    if (strlen($event['manager_user']) > 0) {
        $manager_edit = ' * ';
    }
    
    if (preg_match('/LOGIN/', $event['event'])) {
        $CSV_text2 .= "\"\",\"$event[id]\",\"$manager_edit\",\"$event[event]\",\"$event[event_date]\",\"$event[ip_address]\",\"$event[user_group]\",\"\"\n";
    } else {
        $event_hours_minutes = sec_convert($event['login_sec'], 'H');
        $CSV_text2 .= "\"\",\"$event[id]\",\"$manager_edit\",\"$event[event]\",\"$event[event_date]\",\"$event[ip_address]\",\"$event[user_group]\",\"$event_hours_minutes\"\n";
    }
}

 $CSV_text2 .= "\"\",\"\",\"\",\"\",\"\",\"\",\"\",\""._QXZ("TOTAL")."\",\"$total_login_hours_minutes\"\n";

// Generate HTML output
 $HTML = "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Agent Time Sheet</title>
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
            max-width: 1200px;
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
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 16px;
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
        
        .summary-stats {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
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
            font-size: 24px;
            font-weight: 700;
            color: #4a6cf7;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #6c757d;
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
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .summary-stats {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }
    </style>
</head>
<body>";

 $HTML .= "
    <div class='container'>
        <header>
            <div class='header-content'>
                <div>
                    <div class='header-title'>"._QXZ("Agent Time Sheet")."</div>
                    <div class='header-subtitle'>$agent - $full_name</div>
                </div>
                <div class='header-actions'>
                    <a href='user_status.php?user=$agent' class='btn btn-secondary'>
                        <i class='fas fa-user'></i> "._QXZ("User Status")."
                    </a>
                    <a href='user_stats.php?user=$agent' class='btn btn-secondary'>
                        <i class='fas fa-chart-bar'></i> "._QXZ("User Stats")."
                    </a>
                    <a href='admin.php?ADD=3&user=$agent' class='btn btn-secondary'>
                        <i class='fas fa-user-edit'></i> "._QXZ("Modify User")."
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
                    <input type='hidden' name='agent' value='$agent'>
                    
                    <div class='filters'>
                        <div class='form-group'>
                            <label class='form-label'>"._QXZ("Date")."</label>
                            <input type='text' class='form-control' name='query_date' value='$query_date' size='10' maxsize='10'>
                        </div>
                        <div class='form-group'>
                            <label class='form-label'>"._QXZ("To")."</label>
                            <input type='text' class='form-control' name='end_date' value='$end_date' size='10' maxsize='10'>
                        </div>
                        <div class='form-group'>
                            <label class='form-label'>"._QXZ("User ID")."</label>
                            <input type='text' class='form-control' name='agent' value='$agent' size='20' maxsize='20'>
                        </div>
                        <div class='form-group'>
                            <label class='form-label'>&nbsp;</label>
                            <button type='submit' name='SUBMIT' value='"._QXZ("SUBMIT")."' class='btn btn-primary'>
                                <i class='fas fa-search'></i> "._QXZ("Submit")."
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

if (!$agent) {
    $HTML .= "
        <div class='card mb-20'>
            <div class='card-body'>
                <div class='alert alert-warning'>
                    <i class='fas fa-exclamation-triangle'></i> "._QXZ("PLEASE SELECT AN AGENT ID AND DATE-TIME ABOVE AND CLICK SUBMIT")."
                    <br>"._QXZ("NOTE: stats taken from available agent log data")."
                </div>
            </div>
        </div>";
} else {
    $HTML .= "
        <div class='info-grid mb-20'>
            <div class='info-item'>
                <div class='info-label'>"._QXZ("Agent")."</div>
                <div class='info-value'>$agent</div>
            </div>
            <div class='info-item'>
                <div class='info-label'>"._QXZ("Full Name")."</div>
                <div class='info-value'>$full_name</div>
            </div>
            <div class='info-item'>
                <div class='info-label'>"._QXZ("Time Range")."</div>
                <div class='info-value'>$query_date_BEGIN "._QXZ("to")." $end_date_END</div>
            </div>
        </div>";
    
    if ($calls_summary) {
        $HTML .= "
        <div class='card mb-20'>
            <div class='card-header d-flex justify-content-between'>
                <div class='card-title'>"._QXZ("Call Activity Summary")."</div>
                <div>
                    <a href='$PHP_SELF?calls_summary=$calls_summary&agent=$agent&query_date=$query_date&end_date=$end_date&file_download=1&search_archived_data=$search_archived_data' class='btn btn-secondary'>
                        <i class='fas fa-download'></i> "._QXZ("DOWNLOAD")."
                    </a>
                </div>
            </div>
            <div class='card-body'>
                <div class='summary-stats'>
                    <div class='stat-card'>
                        <div class='stat-value'>$row[0]</div>
                        <div class='stat-label'>"._QXZ("Total Calls Taken")."</div>
                    </div>
                    <div class='stat-card'>
                        <div class='stat-value'>$pfTALK_TIME_HMS</div>
                        <div class='stat-label'>"._QXZ("Talk Time")."</div>
                    </div>
                    <div class='stat-card'>
                        <div class='stat-value'>$pfPAUSE_TIME_HMS</div>
                        <div class='stat-label'>"._QXZ("Pause Time")."</div>
                    </div>
                    <div class='stat-card'>
                        <div class='stat-value'>$pfWAIT_TIME_HMS</div>
                        <div class='stat-label'>"._QXZ("Wait Time")."</div>
                    </div>
                    <div class='stat-card'>
                        <div class='stat-value'>$pfWRAPUP_TIME_HMS</div>
                        <div class='stat-label'>"._QXZ("Wrap-up Time")."</div>
                    </div>
                    <div class='stat-card'>
                        <div class='stat-value'>$pfTOTAL_TIME_HMS</div>
                        <div class='stat-label'>"._QXZ("Total Active Agent Time")."</div>
                    </div>
                </div>
                
                <div class='table-container mt-20'>
                    <table class='table'>
                        <thead>
                            <tr>
                                <th>"._QXZ("Activity")."</th>
                                <th>"._QXZ("Total Time")."</th>
                                <th>"._QXZ("Average")."</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>"._QXZ("Talk Time")."</td>
                                <td>$pfTALK_TIME_HMS</td>
                                <td>$pfTALK_AVG_MS</td>
                            </tr>
                            <tr>
                                <td>"._QXZ("Pause Time")."</td>
                                <td>$pfPAUSE_TIME_HMS</td>
                                <td>$pfPAUSE_AVG_MS</td>
                            </tr>
                            <tr>
                                <td>"._QXZ("Wait Time")."</td>
                                <td>$pfWAIT_TIME_HMS</td>
                                <td>$pfWAIT_AVG_MS</td>
                            </tr>
                            <tr>
                                <td>"._QXZ("Wrap-up Time")."</td>
                                <td>$pfWRAPUP_TIME_HMS</td>
                                <td>$pfWRAPUP_AVG_MS</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>";
    } else {
        $HTML .= "
        <div class='card mb-20'>
            <div class='card-body text-center'>
                <a href='$PHP_SELF?calls_summary=1&agent=$agent&query_date=$query_date&end_date=$end_date'
				        <div class='card mb-20'>
            <div class='card-body text-center'>
                <a href='$PHP_SELF?calls_summary=1&agent=$agent&query_date=$query_date&end_date=$end_date' class='btn btn-primary'>
                    <i class='fas fa-chart-pie'></i> "._QXZ("Call Activity Summary")."
                </a>
            </div>
        </div>";
    }
    
    // Timeclock records table
    $HTML .= "
        <div class='card mb-20'>
            <div class='card-header d-flex justify-content-between'>
                <div class='card-title'>"._QXZ("Timeclock Login/Logout Time")."</div>
                <div>
                    <a href='$PHP_SELF?calls_summary=$calls_summary&agent=$agent&query_date=$query_date&end_date=$end_date&file_download=2&search_archived_data=$search_archived_data' class='btn btn-secondary'>
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
                                <th>"._QXZ("HOURS:MINUTES")."</th>
                            </tr>
                        </thead>
                        <tbody>";
    
    foreach ($timeclock_data as $event) {
        if ($event['event'] == 'START' || $event['event'] == 'LOGIN') {
            $rowClass = 'table-success';
        } else {
            $rowClass = 'table-danger';
        }
        
        $manager_edit = '';
        if (strlen($event['manager_user']) > 0) {
            $manager_edit = ' * ';
        }
        
        if (preg_match('/LOGIN/', $event['event'])) {
            $HTML .= "
                            <tr class='$rowClass'>
                                <td><a href='timeclock_edit.php?timeclock_id=$event[id]' target='_blank'>$event[id]</a></td>
                                <td>$manager_edit</td>
                                <td>$event[event]</td>
                                <td>$event[event_date]</td>
                                <td>$event[ip_address]</td>
                                <td>$event[user_group]</td>
                                <td></td>
                            </tr>";
        } else {
            $event_hours_minutes = sec_convert($event['login_sec'], 'H');
            $HTML .= "
                            <tr class='$rowClass'>
                                <td><a href='timeclock_edit.php?timeclock_id=$event[id]' target='_blank'>$event[id]</a></td>
                                <td>$manager_edit</td>
                                <td>$event[event]</td>
                                <td>$event[event_date]</td>
                                <td>$event[ip_address]</td>
                                <td>$event[user_group]</td>
                                <td>$event_hours_minutes</td>
                            </tr>";
        }
    }
    
    $HTML .= "
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan='6'><strong>"._QXZ("TOTAL")."</strong></td>
                                <td><strong>$total_login_hours_minutes</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>";
}

 $HTML .= "
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add date picker functionality if needed
            const dateInputs = document.querySelectorAll('input[type=\"text\"][name=\"query_date\"], input[type=\"text\"][name=\"end_date\"]');
            
            dateInputs.forEach(input => {
                input.addEventListener('focus', function() {
                    // Simple date picker implementation
                    // In a real implementation, you would integrate with a date picker library
                });
            });
        });
    </script>
</body>
</html>";

// Handle file download
if ($file_download > 0) {
    $FILE_TIME = date("Ymd-His");
    $CSVfilename = "AST_agent_time_sheet_$agent" . "_$FILE_TIME.csv";
    
    if ($file_download == 1) {
        // Call Activity Summary CSV
        $CSV_text = $CSV_text1;
    } elseif ($file_download == 2) {
        // Timeclock Records CSV
        $CSV_text = $CSV_text2;
    } else {
        // Default to summary
        $CSV_text = $CSV_text1;
    }
    
    $CSV_text = preg_replace('/^\s+/', '', $CSV_text);
    $CSV_text = preg_replace('/\n\s+,/', ',', $CSV_text);
    $CSV_text = preg_replace('/ +\"/', '"', $CSV_text);
    $CSV_text = preg_replace('/\" +/', '"', $CSV_text);
    
    // We'll be outputting a CSV file
    header('Content-type: application/octet-stream');
    
    // It will be called AST_agent_time_sheet_YYYYMMDD-HHMMSS.csv
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