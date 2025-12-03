<?php
# phone_stats.php
# 
# Copyright (C) 2022  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
# 

 $startMS = microtime();

require("dbconnect_mysqli.php");
require("functions.php");

 $PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
 $PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
 $PHP_SELF=$_SERVER['PHP_SELF'];
 $PHP_SELF = preg_replace('/\.php.*/i','.php',$PHP_SELF);

# Initialize parameters
if (isset($_GET["begin_date"]))				{$begin_date=$_GET["begin_date"];}
    elseif (isset($_POST["begin_date"]))	{$begin_date=$_POST["begin_date"];}
if (isset($_GET["end_date"]))				{$end_date=$_GET["end_date"];}
    elseif (isset($_POST["end_date"]))		{$end_date=$_POST["end_date"];}
if (isset($_GET["extension"]))				{$extension=$_GET["extension"];}
    elseif (isset($_POST["extension"]))		{$extension=$_POST["extension"];}
if (isset($_GET["server_ip"]))				{$server_ip=$_GET["server_ip"];}
    elseif (isset($_POST["server_ip"]))		{$server_ip=$_POST["server_ip"];}
if (isset($_GET["user"]))				{$user=$_GET["user"];}
    elseif (isset($_POST["user"]))		{$user=$_POST["user"];}
if (isset($_GET["full_name"]))				{$full_name=$_GET["full_name"];}
    elseif (isset($_POST["full_name"]))		{$full_name=$_POST["full_name"];}
if (isset($_GET["submit"]))				{$submit=$_GET["submit"];}
    elseif (isset($_POST["submit"]))		{$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))				{$SUBMIT=$_GET["SUBMIT"];}
    elseif (isset($_POST["SUBMIT"]))		{$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["DB"]))						{$DB=$_GET["DB"];}
    elseif (isset($_POST["DB"]))			{$DB=$_POST["DB"];}

 $DB = preg_replace('/[^0-9]/','',$DB);

 $STARTtime = date("U");
 $TODAY = date("Y-m-d");
 $admin_page = './admin.php';
 $date = date("r");
 $ip = getenv("REMOTE_ADDR");
 $browser = getenv("HTTP_USER_AGENT");
if (!isset($begin_date)) {$begin_date = $TODAY;}
if (!isset($end_date)) {$end_date = $TODAY;}

 $report_name = 'Phone Stats';
 $db_source = 'M';

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
 $stmt="SELECT use_non_latin,webroot_writable,outbound_autodial_active,user_territories_active,enable_languages,language_method,slave_db_server,reports_use_slave_db,allow_web_debug FROM system_settings;";
 $rslt=mysql_to_mysqli($stmt, $link);
 $qm_conf_ct = mysqli_num_rows($rslt);
if ($qm_conf_ct > 0)
    {
    $row=mysqli_fetch_row($rslt);
    $non_latin =					$row[0];
    $webroot_writable =				$row[1];
    $SSoutbound_autodial_active =	$row[2];
    $user_territories_active =	$row[3];
    $SSenable_languages =			$row[4];
    $SSlanguage_method =			$row[5];
    $slave_db_server =				$row[6];
    $reports_use_slave_db =			$row[7];
    $SSallow_web_debug =			$row[8];
    }
if ($SSallow_web_debug < 1) {$DB=0;}
##### END SETTINGS LOOKUP #####
###########################################

# Sanitize inputs
 $extension = preg_replace("/\<|\>|\'|\"|\\\\|;/", '', $extension);
 $server_ip = preg_replace("/\<|\>|\'|\"|\\\\|;/", '', $server_ip);
 $begin_date = preg_replace("/\<|\>|\'|\"|\\\\|;/","",$begin_date);
 $end_date = preg_replace("/\<|\>|\'|\"|\\\\|;/","",$end_date);
 $full_name = preg_replace("/\<|\>|\'|\"|\\\\|;/","",$full_name);
 $submit = preg_replace("/\<|\>|\'|\"|\\\\|;/","",$submit);
 $SUBMIT = preg_replace("/\<|\>|\'|\"|\\\\|;/","",$SUBMIT);

if ($non_latin < 1)
    {
    $PHP_AUTH_USER = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_USER);
    $PHP_AUTH_PW = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_PW);
    $user = preg_replace('/[^-_0-9a-zA-Z]/', '', $user);
    }
else
    {
    $PHP_AUTH_USER = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_USER);
    $PHP_AUTH_PW = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_PW);
    $user = preg_replace('/[^-_0-9\p{L}]/u', '', $user);
    }

# Get user language
 $stmt="SELECT selected_language from vicidial_users where user='$PHP_AUTH_USER';";
 $rslt=mysql_to_mysqli($stmt, $link);
 $sl_ct = mysqli_num_rows($rslt);
if ($sl_ct > 0)
    {
    $row=mysqli_fetch_row($rslt);
    $VUselected_language =	$row[0];
    }

# Authentication check
 $auth=0;
 $reports_auth=0;
 $admin_auth=0;
 $auth_message = user_authorization($PHP_AUTH_USER,$PHP_AUTH_PW,'REPORTS',1,0);
if ($auth_message == 'GOOD')
    {$auth=1;}

if ($auth > 0)
    {
    $stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and user_level > 7 and view_reports='1';";
    $rslt=mysql_to_mysqli($stmt, $link);
    $row=mysqli_fetch_row($rslt);
    $admin_auth=$row[0];

    $stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and user_level > 6 and view_reports='1';";
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

##### BEGIN log visit to vicidial_report_log table #####
 $LOGip = getenv("REMOTE_ADDR");
 $LOGbrowser = getenv("HTTP_USER_AGENT");
 $LOGscript_name = getenv("SCRIPT_NAME");
 $LOGserver_name = getenv("SERVER_NAME");
 $LOGserver_port = getenv("SERVER_PORT");
 $LOGrequest_uri = getenv("REQUEST_URI");
 $LOGhttp_referer = getenv("HTTP_REFERER");
 $LOGbrowser=preg_replace("/\'|\"|\\\\/","",$LOGbrowser);
 $LOGrequest_uri=preg_replace("/\'|\"|\\\\/","",$LOGrequest_uri);
 $LOGhttp_referer=preg_replace("/\'|\"|\\\\/","",$LOGhttp_referer);
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

 $stmt="INSERT INTO vicidial_report_log set event_date=NOW(), user='$PHP_AUTH_USER', ip_address='$LOGip', report_name='$report_name', browser='$LOGbrowser', referer='$LOGhttp_referer', notes='$LOGserver_name:$LOGserver_port $LOGscript_name |$end_date, $shift, $file_download, $report_display_type|', url='$LOGfull_url', webserver='$webserver_id';";
if ($DB) {echo "|$stmt|\n";}
 $rslt=mysql_to_mysqli($stmt, $link);
 $report_log_id = mysqli_insert_id($link);
##### END log visit to vicidial_report_log table #####

# Get user group and permissions
 $stmt="SELECT user_group,full_name from vicidial_users where user='$PHP_AUTH_USER';";
 $rslt=mysql_to_mysqli($stmt, $link);
 $row=mysqli_fetch_row($rslt);
 $LOGuser_group =	$row[0];
 $LOGfullname =	$row[1];

 $stmt="SELECT allowed_campaigns,allowed_reports,admin_viewable_groups,admin_viewable_call_times from vicidial_user_groups where user_group='$LOGuser_group';";
 $rslt=mysql_to_mysqli($stmt, $link);
 $row=mysqli_fetch_row($rslt);
 $LOGallowed_campaigns =			$row[0];
 $LOGallowed_reports =			$row[1];
 $LOGadmin_viewable_groups =	$row[2];
 $LOGadmin_viewable_call_times =	$row[3];

# Check permissions
if ( (!preg_match("/$report_name/",$LOGallowed_reports)) and (!preg_match("/ALL REPORTS/",$LOGallowed_reports)) )
    {
    Header("WWW-Authenticate: Basic realm=\"CONTACT-CENTER-ADMIN\"");
    Header("HTTP/1.0 401 Unauthorized");
    echo "You are not allowed to view this report: |$PHP_AUTH_USER|$report_name|\n";
    exit;
    }

# Get phone details
 $stmt="SELECT fullname from phones where server_ip='$server_ip' and extension='$extension';";
 $rsltx=mysql_to_mysqli($stmt, $link);
 $rowx=mysqli_fetch_row($rsltx);
 $fullname = $rowx[0];

require("screen_colors.php");
 $SSbutton_color = (isset($SSbutton_color)) ? $SSbutton_color : '#3498db';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo _QXZ("ADMIN: Phone Stats"); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="font-family: 'Open Sans', sans-serif; background-color: #f5f7fa; color: #333; line-height: 1.6; margin: 0; padding: 0; box-sizing: border-box;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
        
        <!-- Navigation Card -->
        <div style="background-color: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); margin-bottom: 2rem; overflow: hidden;">
            <div style="background-color: #2c3e50; color: white; padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between;">
                <h2 style="font-size: 1.2rem; font-weight: 600; margin: 0;"><?php echo _QXZ("ADMIN: Administration"); ?></h2>
                <div style="font-size: 0.9rem;"><?php echo date("l F j, Y G:i:s A"); ?></div>
            </div>
            
            <div style="background-color: #f8f9fa; padding: 1rem 1.5rem; display: flex; flex-wrap: wrap; gap: 1rem;">
                <a href="<?php echo $admin_page ?>?ADD=10000000000" style="color: #2c3e50; text-decoration: none; font-weight: 500; padding: 0.5rem 0; border-bottom: 2px solid transparent; transition: all 0.3s;"><?php echo _QXZ("LIST ALL PHONES"); ?></a>
                <a href="<?php echo $admin_page ?>?ADD=11111111111" style="color: #2c3e50; text-decoration: none; font-weight: 500; padding: 0.5rem 0; border-bottom: 2px solid transparent; transition: all 0.3s;"><?php echo _QXZ("ADD A NEW PHONE"); ?></a>
                <a href="<?php echo $admin_page ?>?ADD=551" style="color: #2c3e50; text-decoration: none; font-weight: 500; padding: 0.5rem 0; border-bottom: 2px solid transparent; transition: all 0.3s;"><?php echo _QXZ("SEARCH FOR A PHONE"); ?></a>
                <a href="<?php echo $admin_page ?>?ADD=111111111111" style="color: #2c3e50; text-decoration: none; font-weight: 500; padding: 0.5rem 0; border-bottom: 2px solid transparent; transition: all 0.3s;"><?php echo _QXZ("ADD A SERVER"); ?></a>
                <a href="<?php echo $admin_page ?>?ADD=100000000000" style="color: #2c3e50; text-decoration: none; font-weight: 500; padding: 0.5rem 0; border-bottom: 2px solid transparent; transition: all 0.3s;"><?php echo _QXZ("LIST ALL SERVERS"); ?></a>
                <a href="<?php echo $admin_page ?>?ADD=1000000000000" style="color: #2c3e50; text-decoration: none; font-weight: 500; padding: 0.5rem 0; border-bottom: 2px solid transparent; transition: all 0.3s;"><?php echo _QXZ("SHOW ALL CONFERENCES"); ?></a>
                <a href="<?php echo $admin_page ?>?ADD=1111111111111" style="color: #2c3e50; text-decoration: none; font-weight: 500; padding: 0.5rem 0; border-bottom: 2px solid transparent; transition: all 0.3s;"><?php echo _QXZ("ADD A NEW CONFERENCE"); ?></a>
            </div>
        </div>
        
        <!-- Phone Info Card -->
        <div style="background-color: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); margin-bottom: 2rem; overflow: hidden;">
            <div style="background-color: #3498db; color: white; padding: 1rem 1.5rem;">
                <h2 style="font-size: 1.2rem; font-weight: 600; margin: 0;"><?php echo _QXZ("Phone Statistics"); ?></h2>
            </div>
            
            <div style="padding: 1.5rem;">
                <form action="<?php echo $PHP_SELF; ?>" method="POST" style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; margin-bottom: 1.5rem;">
                    <input type="hidden" name="extension" value="<?php echo $extension; ?>">
                    <input type="hidden" name="server_ip" value="<?php echo $server_ip; ?>">
                    
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label for="begin_date" style="font-weight: 500; font-size: 0.9rem; color: #2c3e50;"><?php echo _QXZ("Start Date"); ?></label>
                        <input type="date" id="begin_date" name="begin_date" value="<?php echo $begin_date; ?>" style="padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label for="end_date" style="font-weight: 500; font-size: 0.9rem; color: #2c3e50;"><?php echo _QXZ("End Date"); ?></label>
                        <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>" style="padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px; font-size: 0.9rem;">
                    </div>
                    
                    <button type="submit" name="submit" style="padding: 0.75rem 1.5rem; background-color: <?php echo $SSbutton_color; ?>; color: white; border: none; border-radius: 8px; font-size: 0.9rem; font-weight: 500; cursor: pointer; transition: all 0.3s;"><?php echo _QXZ("Apply Filter"); ?></button>
                </form>
                
                <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background-color: #f8f9fa; border-radius: 8px;">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background-color: #3498db; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 1.2rem;"><?php echo substr($fullname, 0, 1); ?></div>
                    <div>
                        <div style="font-weight: 600; font-size: 1.1rem; color: #2c3e50;"><?php echo $fullname; ?></div>
                        <div style="font-size: 0.9rem; color: #6c757d;">Extension: <?php echo $extension; ?> | Server: <?php echo $server_ip; ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Call Time Statistics Card -->
        <div style="background-color: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); margin-bottom: 2rem; overflow: hidden;">
            <div style="background-color: #3498db; color: white; padding: 1rem 1.5rem;">
                <h3 style="font-size: 1.2rem; font-weight: 600; margin: 0; display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-clock"></i> <?php echo _QXZ("CALL TIME AND CHANNELS"); ?></h3>
            </div>
            
            <div style="padding: 1.5rem;">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                        <thead>
                            <tr style="background-color: #2c3e50; color: white;">
                                <th style="padding: 12px 15px; text-align: left; font-weight: 500;"><?php echo _QXZ("CHANNEL GROUP"); ?></th>
                                <th style="padding: 12px 15px; text-align: right; font-weight: 500;"><?php echo _QXZ("COUNT"); ?></th>
                                <th style="padding: 12px 15px; text-align: right; font-weight: 500;"><?php echo _QXZ("HOURS:MINUTES"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt="SELECT count(*),channel_group, sum(length_in_sec) from call_log where extension='" . mysqli_real_escape_string($link, $extension) . "' and server_ip='" . mysqli_real_escape_string($link, $server_ip) . "' and start_time >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and start_time <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' group by channel_group order by channel_group";
                            $rslt=mysql_to_mysqli($stmt, $link);
                            $statuses_to_print = mysqli_num_rows($rslt);
                            
                            $total_calls=0;
                            $o=0;
                            while ($statuses_to_print > $o) {
                                $row=mysqli_fetch_row($rslt);
                                $rowColor = ($o % 2 == 0) ? '#f8f9fa' : '#ffffff';
                                
                                $call_seconds = $row[2];
                                $call_hours = MathZDC($call_seconds, 3600);
                                $call_hours = round($call_hours, 2);
                                $call_hours_int = intval("$call_hours");
                                $call_minutes = ($call_hours - $call_hours_int);
                                $call_minutes = ($call_minutes * 60);
                                $call_minutes_int = round($call_minutes, 0);
                                if ($call_minutes_int < 10) {$call_minutes_int = "0$call_minutes_int";}
                            ?>
                            <tr style="background-color: <?php echo $rowColor; ?>;">
                                <td style="padding: 12px 15px; border-bottom: 1px solid #eee;"><?php echo $row[1]; ?></td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #eee; text-align: right;"><?php echo $row[0]; ?></td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #eee; text-align: right;"><?php echo "$call_hours_int:$call_minutes_int"; ?></td>
                            </tr>
                            <?php
                                $total_calls = ($total_calls + $row[0]);
                                $call_seconds=0;
                                $o++;
                            }
                            
                            # Get total call time
                            $stmt="SELECT sum(length_in_sec) from call_log where extension='" . mysqli_real_escape_string($link, $extension) . "' and server_ip='" . mysqli_real_escape_string($link, $server_ip) . "' and start_time >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and start_time <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59'";
                            $rslt=mysql_to_mysqli($stmt, $link);
                            $row=mysqli_fetch_row($rslt);
                            $call_seconds = $row[0];
                            $call_hours = MathZDC($call_seconds, 3600);
                            $call_hours = round($call_hours, 2);
                            $call_hours_int = intval("$call_hours");
                            $call_minutes = ($call_hours - $call_hours_int);
                            $call_minutes = ($call_minutes * 60);
                            $call_minutes_int = round($call_minutes, 0);
                            if ($call_minutes_int < 10) {$call_minutes_int = "0$call_minutes_int";}
                            ?>
                            <tr style="background-color: #e9ecef; font-weight: 600;">
                                <td style="padding: 12px 15px; border-bottom: 1px solid #eee;"><?php echo _QXZ("TOTAL CALLS"); ?></td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #eee; text-align: right;"><?php echo $total_calls; ?></td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #eee; text-align: right;"><?php echo "$call_hours_int:$call_minutes_int"; ?></td>
                            </tr>
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Recent Calls Card -->
        <div style="background-color: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); margin-bottom: 2rem; overflow: hidden;">
            <div style="background-color: #3498db; color: white; padding: 1rem 1.5rem;">
                <h3 style="font-size: 1.2rem; font-weight: 600; margin: 0; display: flex; align-items: center; gap: 0.5rem;"><i class="fas fa-phone"></i> <?php echo _QXZ("LAST 1000 CALLS FOR DATE RANGE"); ?></h3>
            </div>
            
            <div style="padding: 1.5rem;">
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
                        <thead>
                            <tr style="background-color: #2c3e50; color: white;">
                                <th style="padding: 12px 15px; text-align: left; font-weight: 500;"><?php echo _QXZ("NUMBER"); ?></th>
                                <th style="padding: 12px 15px; text-align: left; font-weight: 500;"><?php echo _QXZ("CHANNEL GROUP"); ?></th>
                                <th style="padding: 12px 15px; text-align: left; font-weight: 500;"><?php echo _QXZ("DATE"); ?></th>
                                <th style="padding: 12px 15px; text-align: right; font-weight: 500;"><?php echo _QXZ("LENGTH(MIN.)"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt="SELECT number_dialed,channel_group,start_time,length_in_min from call_log where extension='" . mysqli_real_escape_string($link, $extension) . "' and server_ip='" . mysqli_real_escape_string($link, $server_ip) . "' and start_time >= '" . mysqli_real_escape_string($link, $begin_date) . " 0:00:01'  and start_time <= '" . mysqli_real_escape_string($link, $end_date) . " 23:59:59' LIMIT 1000";
                            $rslt=mysql_to_mysqli($stmt, $link);
                            $events_to_print = mysqli_num_rows($rslt);
                            
                            $o=0;
                            while ($events_to_print > $o) {
                                $row=mysqli_fetch_row($rslt);
                                $rowColor = ($o % 2 == 0) ? '#f8f9fa' : '#ffffff';
                            ?>
                            <tr style="background-color: <?php echo $rowColor; ?>;">
                                <td style="padding: 12px 15px; border-bottom: 1px solid #eee;"><?php echo $row[0]; ?></td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #eee;"><?php echo $row[1]; ?></td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #eee;"><?php echo $row[2]; ?></td>
                                <td style="padding: 12px 15px; border-bottom: 1px solid #eee; text-align: right;"><?php echo $row[3]; ?></td>
                            </tr>
                            <?php
                                $call_seconds=0;
                                $o++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Runtime Info -->
        <div style="text-align: center; font-size: 0.8rem; color: #6c757d; margin-top: 2rem;">
            <?php
            $ENDtime = date("U");
            $RUNtime = ($ENDtime - $STARTtime);
            echo _QXZ("Script runtime") . ": $RUNtime " . _QXZ("seconds");
            ?>
        </div>
    </div>
</body>
</html>

<?php
# Handle slave database connection if needed
if ( (strlen($slave_db_server)>5) and (preg_match("/$report_name/",$reports_use_slave_db)) )
    {
    mysqli_close($link);
    $use_slave_server=1;
    $db_source = 'S';
    require("dbconnect_mysqli.php");
    }

# Update report log with runtime
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