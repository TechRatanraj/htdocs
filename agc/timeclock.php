<?php

# Copyright (C) 2022  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#

#

 $version = '2.14-22';
 $build = '220921-1702';
 $php_script = 'timeclock.php';

 $StarTtimE = date("U");
 $NOW_TIME = date("Y-m-d H:i:s");
    $last_action_date = $NOW_TIME;

 $US='_';
 $CL=':';
 $AT='@';
 $DS='-';
 $date = date("r");
 $ip = getenv("REMOTE_ADDR");
 $browser = getenv("HTTP_USER_AGENT");
 $script_name = getenv("SCRIPT_NAME");
 $server_name = getenv("SERVER_NAME");
 $server_port = getenv("SERVER_PORT");
if (preg_match("/443/i",$server_port)) {$HTTPprotocol = 'https://';}
  else {$HTTPprotocol = 'http://';}
if (($server_port == '80') or ($server_port == '443') ) {$server_port='';}
else {$server_port = "$CL$server_port";}
 $agcPAGE = "$HTTPprotocol$server_name$server_port$script_name";
 $agcDIR = preg_replace('/timeclock\.php/i','',$agcPAGE);


if (isset($_GET["DB"]))							{$DB=$_GET["DB"];}
        elseif (isset($_POST["DB"]))			{$DB=$_POST["DB"];}
if (isset($_GET["phone_login"]))				{$phone_login=$_GET["phone_login"];}
        elseif (isset($_POST["phone_login"]))	{$phone_login=$_POST["phone_login"];}
if (isset($_GET["phone_pass"]))					{$phone_pass=$_GET["phone_pass"];}
        elseif (isset($_POST["phone_pass"]))	{$phone_pass=$_POST["phone_pass"];}
if (isset($_GET["VD_login"]))					{$VD_login=$_GET["VD_login"];}
        elseif (isset($_POST["VD_login"]))		{$VD_login=$_POST["VD_login"];}
if (isset($_GET["VD_pass"]))					{$VD_pass=$_GET["VD_pass"];}
        elseif (isset($_POST["VD_pass"]))		{$VD_pass=$_POST["VD_pass"];}
if (isset($_GET["VD_campaign"]))				{$VD_campaign=$_GET["VD_campaign"];}
        elseif (isset($_POST["VD_campaign"]))	{$VD_campaign=$_POST["VD_campaign"];}
if (isset($_GET["stage"]))						{$stage=$_GET["stage"];}
        elseif (isset($_POST["stage"]))			{$stage=$_POST["stage"];}
if (isset($_GET["commit"]))						{$commit=$_GET["commit"];}
        elseif (isset($_POST["commit"]))		{$commit=$_POST["commit"];}
if (isset($_GET["referrer"]))					{$referrer=$_GET["referrer"];}
        elseif (isset($_POST["referrer"]))		{$referrer=$_POST["referrer"];}
if (isset($_GET["user"]))						{$user=$_GET["user"];}
        elseif (isset($_POST["user"]))			{$user=$_POST["user"];}
if (isset($_GET["pass"]))						{$pass=$_GET["pass"];}
        elseif (isset($_POST["pass"]))			{$pass=$_POST["pass"];}
if (strlen($VD_login)<1) {$VD_login = $user;}

if (!isset($phone_login)) 
    {
    if (isset($_GET["pl"]))					{$phone_login=$_GET["pl"];}
            elseif (isset($_POST["pl"]))	{$phone_login=$_POST["pl"];}
    }
if (!isset($phone_pass))
    {
    if (isset($_GET["pp"]))					{$phone_pass=$_GET["pp"];}
            elseif (isset($_POST["pp"]))	{$phone_pass=$_POST["pp"];}
    }

### security strip all non-alphanumeric characters out of the variables ###
 $DB=preg_replace("/[^0-9a-z]/","",$DB);
 $VD_login=preg_replace("/\'|\"|\\\\|;| /","",$VD_login);
 $VD_pass=preg_replace("/\'|\"|\\\\|;| /","",$VD_pass);
 $user=preg_replace("/\'|\"|\\\\|;| /","",$user);
 $pass=preg_replace("/\'|\"|\\\\|;| /","",$pass);

require_once("dbconnect_mysqli.php");
require_once("functions.php");

# if options file exists, use the override values for the above variables
#   see the options-example.php file for more information
if (file_exists('options.php'))
    {
    require('options.php');
    }

#############################################
##### START SYSTEM_SETTINGS AND USER LANGUAGE LOOKUP #####
 $stmt = "SELECT use_non_latin,admin_home_url,admin_web_directory,enable_languages,language_method,default_language,agent_screen_colors,agent_script,allow_web_debug FROM system_settings;";
#if ($DB) {echo "$stmt\n";}
 $rslt=mysql_to_mysqli($stmt, $link);
    if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'00XXX',$VD_login,$server_ip,$session_name,$one_mysql_log);}
 $qm_conf_ct = mysqli_num_rows($rslt);
if ($qm_conf_ct > 0)
    {
    $row=mysqli_fetch_row($rslt);
    $non_latin =			$row[0];
    $welcomeURL =			$row[1];
    $admin_web_directory =	$row[2];
    $SSenable_languages =	$row[3];
    $SSlanguage_method =	$row[4];
    $SSdefault_language =	$row[5];
    $agent_screen_colors =	$row[6];
    $SSagent_script =		$row[7];
    $SSallow_web_debug =	$row[8];
    }
if ($SSallow_web_debug < 1) {$DB=0;}

 $VUselected_language = '';
 $stmt="SELECT user,selected_language from vicidial_users where user='$VD_login';";
if ($DB) {echo "|$stmt|\n";}
 $rslt=mysql_to_mysqli($stmt, $link);
    if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'00XXX',$VD_login,$server_ip,$session_name,$one_mysql_log);}
 $sl_ct = mysqli_num_rows($rslt);
if ($sl_ct > 0)
    {
    $row=mysqli_fetch_row($rslt);
    $VUuser =				$row[0];
    $VUselected_language =	$row[1];
    }

if (strlen($VUselected_language) < 1)
    {$VUselected_language = $SSdefault_language;}
##### END SETTINGS LOOKUP #####
###########################################

 $user=preg_replace("/\'|\"|\\\\|;| /","",$user);
 $pass=preg_replace("/\'|\"|\\\\|;| /","",$pass);
 $phone_login=preg_replace("/\'|\"|\\\\|;| /","",$phone_login);
 $phone_pass=preg_replace("/\'|\"|\\\\|;| /","",$phone_pass);
 $stage=preg_replace("/[^0-9a-zA-Z]/","",$stage);
 $commit=preg_replace("/[^0-9a-zA-Z]/","",$commit);
 $referrer=preg_replace("/[^0-9a-zA-Z]/","",$referrer);

if ($non_latin < 1)
    {
    $user=preg_replace("/[^-_0-9a-zA-Z]/","",$user);
    $pass=preg_replace("/[^-\.\+\/\=_0-9a-zA-Z]/","",$pass);
    $VD_login=preg_replace("/[^-_0-9a-zA-Z]/","",$VD_login);
    $VD_pass=preg_replace("/[^-_0-9a-zA-Z]/","",$VD_pass);
    $VD_campaign=preg_replace("/[^-_0-9a-zA-Z]/","",$VD_campaign);
    $phone_login=preg_replace("/[^\,0-9a-zA-Z]/","",$phone_login);
    $phone_pass=preg_replace("/[^-_0-9a-zA-Z]/","",$phone_pass);
    }
else
    {
    $user=preg_replace("/[^-_0-9\p{L}]/u","",$user);
    $pass = preg_replace('/[^-\.\+\/\=_0-9\p{L}]/u','',$pass);
    $VD_login=preg_replace("/[^-_0-9\p{L}]/u","",$VD_login);
    $VD_pass=preg_replace("/[^-_0-9\p{L}]/u","",$VD_pass);
    $VD_campaign=preg_replace("/[^-_0-9\p{L}]/u","",$VD_campaign);
    $phone_login=preg_replace("/[^\,0-9\p{L}]/u","",$phone_login);
    $phone_pass=preg_replace("/[^-_0-9\p{L}]/u","",$phone_pass);
    }

header ("Content-type: text/html; charset=utf-8");
header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0


##### BEGIN Define colors and logo #####
 $SSmenu_background='015B91';
 $SSframe_background='D9E6FE';
 $SSstd_row1_background='9BB9FB';
 $SSstd_row2_background='B9CBFD';
 $SSstd_row3_background='8EBCFD';
 $SSstd_row4_background='B6D3FC';
 $SSstd_row5_background='A3C3D6';
 $SSalt_row1_background='BDFFBD';
 $SSalt_row2_background='99FF99';
 $SSalt_row3_background='CCFFCC';

if ($agent_screen_colors != 'default')
    {
    $stmt = "SELECT menu_background,frame_background,std_row1_background,std_row2_background,std_row3_background,std_row4_background,std_row5_background,alt_row1_background,alt_row2_background,alt_row3_background,web_logo FROM vicidial_screen_colors where colors_id='$agent_screen_colors';";
    $rslt=mysql_to_mysqli($stmt, $link);
        if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01XXX',$VD_login,$server_ip,$session_name,$one_mysql_log);}
    if ($DB) {echo "$stmt\n";}
    $qm_conf_ct = mysqli_num_rows($rslt);
    if ($qm_conf_ct > 0)
        {
        $row=mysqli_fetch_row($rslt);
        $SSmenu_background =		$row[0];
        $SSframe_background =		$row[1];
        $SSstd_row1_background =	$row[2];
        $SSstd_row2_background =	$row[3];
        $SSstd_row3_background =	$row[4];
        $SSstd_row4_background =	$row[5];
        $SSstd_row5_background =	$row[6];
        $SSalt_row1_background =	$row[7];
        $SSalt_row2_background =	$row[8];
        $SSalt_row3_background =	$row[9];
        $SSweb_logo =				$row[10];
        }
    }
 $Mhead_color =	$SSstd_row5_background;
 $Mmain_bgcolor = $SSmenu_background;
 $Mhead_color =	$SSstd_row5_background;

 $selected_logo = "./images/vicidial_admin_web_logo.png";
 $logo_new=0;
 $logo_old=0;
if (file_exists('../$admin_web_directory/images/vicidial_admin_web_logo.png')) {$logo_new++;}
if (file_exists('vicidial_admin_web_logo.gif')) {$logo_old++;}
if ($SSweb_logo=='default_new')
    {
    $selected_logo = "./images/vicidial_admin_web_logo.png";
    }
if ( ($SSweb_logo=='default_old') and ($logo_old > 0) )
    {
    $selected_logo = "../$admin_web_directory/vicidial_admin_web_logo.gif";
    }
if ( ($SSweb_logo!='default_new') and ($SSweb_logo!='default_old') )
    {
    if (file_exists("../$admin_web_directory/images/vicidial_admin_web_logo$SSweb_logo")) 
        {
        $selected_logo = "../$admin_web_directory/images/vicidial_admin_web_logo$SSweb_logo";
        }
    }
##### END Define colors and logo #####

// Modern UI Header
echo"<!DOCTYPE html>\n";
echo"<html lang=\"en\">\n";
echo"<head>\n";
echo"<meta charset=\"utf-8\">\n";
echo"<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
echo"<title>"._QXZ("Agent Timeclock")."</title>\n";
echo"<link rel=\"stylesheet\" type=\"text/css\" href=\"../agc/css/style.css\" />\n";
echo"<link rel=\"stylesheet\" type=\"text/css\" href=\"../agc/css/custom.css\" />\n";
echo"<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css\">\n";
echo"<style>\n";
echo"
:root {
    --primary-color: #6a11cb;
    --secondary-color: #2575fc;
    --accent-color: #ffffff;
    --text-color: #333333;
    --light-text: #ffffff;
    --error-color: #dc3545;
    --success-color: #28a745;
    --border-radius: 8px;
    --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: var(--text-color);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.container {
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
}

.card {
    background-color: var(--accent-color);
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    overflow: hidden;
    margin-bottom: 20px;
    transition: var(--transition);
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}

.card-header {
    background-color: var(--primary-color);
    color: var(--light-text);
    padding: 15px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.logo {
    height: 40px;
    width: auto;
    max-width: 150px;
}

.card-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
}

.card-body {
    padding: 25px;
}

.alert {
    padding: 15px;
    border-radius: var(--border-radius);
    margin-bottom: 20px;
    font-weight: 500;
}

.alert-error {
    background-color: rgba(220, 53, 69, 0.1);
    color: var(--error-color);
    border-left: 4px solid var(--error-color);
}

.alert-success {
    background-color: rgba(40, 167, 69, 0.1);
    color: var(--success-color);
    border-left: 4px solid var(--success-color);
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: var(--text-color);
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: var(--border-radius);
    font-size: 1rem;
    transition: var(--transition);
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(106, 17, 203, 0.2);
}

.btn {
    display: inline-block;
    padding: 12px 24px;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: var(--light-text);
    border: none;
    border-radius: var(--border-radius);
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
    text-align: center;
    text-decoration: none;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
}

.btn-block {
    display: block;
    width: 100%;
}

.status-message {
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: var(--border-radius);
    margin-bottom: 20px;
    text-align: center;
}

.status-time {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 10px;
}

.status-text {
    color: var(--text-color);
    line-height: 1.5;
}

.footer {
    text-align: center;
    color: var(--light-text);
    font-size: 0.8rem;
    margin-top: 20px;
}

.back-link {
    display: inline-block;
    margin-top: 15px;
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
}

.back-link:hover {
    color: var(--secondary-color);
    text-decoration: underline;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        max-width: 100%;
    }
    
    .card-header {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }
    
    .card-title {
        font-size: 1.3rem;
    }
}
";
echo"</style>\n";
echo"</head>\n";
echo"<body>\n";

if ( ($stage == 'login') or ($stage == 'logout') )
    {
    ### see if user/pass exist for this user in vicidial_users table
    $valid_user=0;
    $auth_message = user_authorization($user,$pass,'',1,0,0,0,'timeclock');
    if ($auth_message == 'GOOD')
        {$valid_user=1;}

    # case-sensitive check for user
    if($valid_user>0)
        {
        if ($user != "$VUuser")
            {
            $valid_user=0;
            print "<!-- case check $user|$VD_login|$VUuser:   |$valid_user| -->\n";
            }
        }

    print "<!-- vicidial_users active count for $user:   |$valid_user| -->\n";

    if ($valid_user < 1)
        {
        ### NOT A VALID USER/PASS
        $VDdisplayMESSAGE = _QXZ("The user and password you entered are not active in the system<BR>Please try again:");
        if ($auth_message == 'LOCK')
            {$VDdisplayMESSAGE = _QXZ("Too many login attempts, try again in 15 minutes")."<br />";}
        if ($auth_message == 'ERRNETWORK')
            {$VDdisplayMESSAGE = _QXZ("Too many network errors, please contact your administrator")."<br />";}
        if ($auth_message == 'ERRSERVERS')
            {$VDdisplayMESSAGE = _QXZ("No available servers, please contact your administrator")."<br />";}
        if ($auth_message == 'ERRPHONES')
            {$VDdisplayMESSAGE = _QXZ("No available phones, please contact your administrator")."<br />";}
        if ($auth_message == 'ERRDUPLICATE')
            {$VDdisplayMESSAGE = _QXZ("You are already logged in, please log out of your other session first")."<br />";}
        if ($auth_message == 'ERRAGENTS')
            {$VDdisplayMESSAGE = _QXZ("Too many agents logged in, please contact your administrator")."<br />";}
        if ($auth_message == 'ERRCAMPAGENTS')
            {$VDdisplayMESSAGE = _QXZ("Too many agents logged in to this campaign, please contact your manager")."<br />";}
        if ($auth_message == 'ERRCASE')
            {$VDdisplayMESSAGE = _QXZ("Login incorrect, user names are case sensitive")."<br />";}
        if ($auth_message == 'IPBLOCK')
            {$VDdisplayMESSAGE = _QXZ("Your IP Address is not allowed").": $ip<br />";}

        echo"<div class=\"container\">\n";
        echo"<div class=\"card\">\n";
        echo"<div class=\"card-header\">\n";
        echo"<img src=\"$selected_logo\" class=\"logo\" alt=\"VICIDIAL Logo\" />\n";
        echo"<h1 class=\"card-title\">"._QXZ("Timeclock")."</h1>\n";
        echo"</div>\n";
        echo"<div class=\"card-body\">\n";
        echo"<div class=\"alert alert-error\">$VDdisplayMESSAGE</div>\n";
        echo"<form name=\"vicidial_form\" id=\"vicidial_form\" action=\"$agcPAGE\" method=\"post\">\n";
        echo"<input type=\"hidden\" name=\"referrer\" value=\"$referrer\">\n";
        echo"<input type=\"hidden\" name=\"stage\" value=\"login\">\n";
        echo"<input type=\"hidden\" name=\"DB\" value=\"$DB\">\n";
        echo"<input type=\"hidden\" name=\"phone_login\" value=\"$phone_login\">\n";
        echo"<input type=\"hidden\" name=\"phone_pass\" value=\"$phone_pass\">\n";
        echo"<input type=\"hidden\" name=\"VD_login\" value=\"$VD_login\">\n";
        echo"<input type=\"hidden\" name=\"VD_pass\" value=\"$VD_pass\">\n";
        echo"<div class=\"form-group\">\n";
        echo"<label for=\"user\" class=\"form-label\">"._QXZ("User Login").":</label>\n";
        echo"<input type=\"text\" name=\"user\" id=\"user\" class=\"form-control\" size=\"10\" maxlength=\"20\" value=\"$VD_login\">\n";
        echo"</div>\n";
        echo"<div class=\"form-group\">\n";
        echo"<label for=\"pass\" class=\"form-label\">"._QXZ("User Password").":</label>\n";
        echo"<input type=\"password\" name=\"pass\" id=\"pass\" class=\"form-control\" size=\"10\" maxlength=\"20\">\n";
        echo"</div>\n";
        echo"<button type=\"submit\" name=\"SUBMIT\" class=\"btn btn-block\">"._QXZ("SUBMIT")."</button>\n";
        echo"</form>\n";
        echo"</div>\n";
        echo"</div>\n";
        echo"<div class=\"footer\">"._QXZ("VERSION:")." $version &nbsp; &nbsp; &nbsp; "._QXZ("BUILD:")." $build</div>\n";
        echo"</div>\n";
        echo"</body>\n";
        echo"</html>\n";

        exit;
        }
    else
        {
        ### VALID USER/PASS, CONTINUE

        ### get name and group for this user
        $stmt="SELECT full_name,user_group from vicidial_users where user='$user' and active='Y';";
        if ($DB) {echo "|$stmt|\n";}
        $rslt=mysql_to_mysqli($stmt, $link);
        $row=mysqli_fetch_row($rslt);
        $full_name =	$row[0];
        $user_group =	$row[1];
        print "<!-- vicidial_users name and group for $user:   |$full_name|$user_group| -->\n";

        ### get vicidial_timeclock_status record count for this user
        $stmt="SELECT count(*) from vicidial_timeclock_status where user='$user';";
        if ($DB) {echo "|$stmt|\n";}
        $rslt=mysql_to_mysqli($stmt, $link);
        $row=mysqli_fetch_row($rslt);
        $vts_count =	$row[0];

        $last_action_sec=99;

        if ($vts_count > 0)
            {
            ### vicidial_timeclock_status record found, grab status and date of last activity
            $stmt="SELECT status,event_epoch from vicidial_timeclock_status where user='$user';";
            if ($DB) {echo "|$stmt|\n";}
            $rslt=mysql_to_mysqli($stmt, $link);
            $row=mysqli_fetch_row($rslt);
            $status =		$row[0];
            $event_epoch =	$row[1];
            $last_action_date = date("Y-m-d H:i:s", $event_epoch);
            $last_action_sec = ($StarTtimE - $event_epoch);
            if ($last_action_sec > 0)
                {
                $totTIME_H = ($last_action_sec / 3600);
                $totTIME_H_int = round($totTIME_H, 2);
                $totTIME_H_int = intval("$totTIME_H");
                $totTIME_M = ($totTIME_H - $totTIME_H_int);
                $totTIME_M = ($totTIME_M * 60);
                $totTIME_M_int = round($totTIME_M, 2);
                $totTIME_M_int = intval("$totTIME_M");
                $totTIME_S = ($totTIME_M - $totTIME_M_int);
                $totTIME_S = ($totTIME_S * 60);
                $totTIME_S = round($totTIME_S, 0);
                if (strlen($totTIME_H_int) < 1) {$totTIME_H_int = "0";}
                if ($totTIME_M_int < 10) {$totTIME_M_int = "0$totTIME_M_int";}
                if ($totTIME_S < 10) {$totTIME_S = "0$totTIME_S";}
                $totTIME_HMS = "$totTIME_H_int:$totTIME_M_int:$totTIME_S";
                }
            else 
                {
                $totTIME_HMS='0:00:00';
                }

            print "<!-- vicidial_timeclock_status previous status for $user:   |$status|$event_epoch|$last_action_sec| -->\n";
            }
        else
            {
            ### No vicidial_timeclock_status record found, insert one
            $stmt="INSERT INTO vicidial_timeclock_status set status='START', user='$user', user_group='$user_group', event_epoch='$StarTtimE', ip_address='$ip';";
            if ($DB) {echo "$stmt\n";}
            $rslt=mysql_to_mysqli($stmt, $link);
                $status='START';
                $totTIME_HMS='0:00:00';
            $affected_rows = mysqli_affected_rows($link);
            print "<!-- NEW vicidial_timeclock_status record inserted for $user:   |$affected_rows| -->\n";
            }
        if ( ($last_action_sec < 30) and ($status != 'START') )
            {
            ### You cannot log in or out within 30 seconds of your last login/logout
            $VDdisplayMESSAGE = _QXZ("You cannot log in or out within 30 seconds of your last login or logout");

            echo"<div class=\"container\">\n";
            echo"<div class=\"card\">\n";
            echo"<div class=\"card-header\">\n";
            echo"<img src=\"$selected_logo\" class=\"logo\" alt=\"VICIDIAL Logo\" />\n";
            echo"<h1 class=\"card-title\">"._QXZ("Timeclock")."</h1>\n";
            echo"</div>\n";
            echo"<div class=\"card-body\">\n";
            echo"<div class=\"alert alert-error\">$VDdisplayMESSAGE</div>\n";
            echo"<form name=\"vicidial_form\" id=\"vicidial_form\" action=\"$agcPAGE\" method=\"post\">\n";
            echo"<input type=\"hidden\" name=\"stage\" value=\"login\">\n";
            echo"<input type=\"hidden\" name=\"referrer\" value=\"$referrer\">\n";
            echo"<input type=\"hidden\" name=\"DB\" value=\"$DB\">\n";
            echo"<input type=\"hidden\" name=\"phone_login\" value=\"$phone_login\">\n";
            echo"<input type=\"hidden\" name=\"phone_pass\" value=\"$phone_pass\">\n";
            echo"<input type=\"hidden\" name=\"VD_login\" value=\"$VD_login\">\n";
            echo"<input type=\"hidden\" name=\"VD_pass\" value=\"$VD_pass\">\n";
            echo"<div class=\"form-group\">\n";
            echo"<label for=\"user\" class=\"form-label\">"._QXZ("User Login").":</label>\n";
            echo"<input type=\"text\" name=\"user\" id=\"user\" class=\"form-control\" size=\"10\" maxlength=\"20\" value=\"$VD_login\">\n";
            echo"</div>\n";
            echo"<div class=\"form-group\">\n";
            echo"<label for=\"pass\" class=\"form-label\">"._QXZ("User Password").":</label>\n";
            echo"<input type=\"password\" name=\"pass\" id=\"pass\" class=\"form-control\" size=\"10\" maxlength=\"20\">\n";
            echo"</div>\n";
            echo"<button type=\"submit\" name=\"SUBMIT\" class=\"btn btn-block\">"._QXZ("SUBMIT")."</button>\n";
            echo"</form>\n";
            echo"</div>\n";
            echo"</div>\n";
            echo"<div class=\"footer\">"._QXZ("VERSION:")." $version &nbsp; &nbsp; &nbsp; "._QXZ("BUILD:")." $build</div>\n";
            echo"</div>\n";
            echo"</body>\n";
            echo"</html>\n";

            exit;
            }

        if ($commit == 'YES')
            {
            if ( ( ($status=='AUTOLOGOUT') or ($status=='START') or ($status=='LOGOUT') or ($status=='TIMEOUTLOGOUT') ) and ($stage=='login') )
                {
                $VDdisplayMESSAGE = _QXZ("You have now logged-in");
                $LOGtimeMESSAGE = _QXZ("You logged in at")." $NOW_TIME";

                ### Add a record to the timeclock log
                $stmt="INSERT INTO vicidial_timeclock_log set event='LOGIN', user='$user', user_group='$user_group', event_epoch='$StarTtimE', ip_address='$ip', event_date='$NOW_TIME';";
                if ($DB) {echo "$stmt\n";}
                $rslt=mysql_to_mysqli($stmt, $link);
                $affected_rows = mysqli_affected_rows($link);
                $timeclock_id = mysqli_insert_id($link);
                print "<!-- NEW vicidial_timeclock_log record inserted for $user:   |$affected_rows|$timeclock_id| -->\n";

                ### Update the user's timeclock status record
                $stmt="UPDATE vicidial_timeclock_status set status='LOGIN', user_group='$user_group', event_epoch='$StarTtimE', ip_address='$ip' where user='$user';";
                if ($DB) {echo "$stmt\n";}
                $rslt=mysql_to_mysqli($stmt, $link);
                $affected_rows = mysqli_affected_rows($link);
                print "<!-- vicidial_timeclock_status record updated for $user:   |$affected_rows| -->\n";

                ### Add a record to the timeclock audit log
                $stmt="INSERT INTO vicidial_timeclock_audit_log set timeclock_id='$timeclock_id', event='LOGIN', user='$user', user_group='$user_group', event_epoch='$StarTtimE', ip_address='$ip', event_date='$NOW_TIME';";
                if ($DB) {echo "$stmt\n";}
                $rslt=mysql_to_mysqli($stmt, $link);
                $affected_rows = mysqli_affected_rows($link);
                print "<!-- NEW vicidial_timeclock_audit_log record inserted for $user:   |$affected_rows| -->\n";
                }

            if ( ($status=='LOGIN') and ($stage=='logout') )
                {
                $VDdisplayMESSAGE = _QXZ("You have now logged-out");
                $LOGtimeMESSAGE = _QXZ("You logged out at")." $NOW_TIME<BR>"._QXZ("Amount of time you were logged-in:")." $totTIME_HMS";

                ### Add a record to the timeclock log
                $stmt="INSERT INTO vicidial_timeclock_log set event='LOGOUT', user='$user', user_group='$user_group', event_epoch='$StarTtimE', ip_address='$ip', login_sec='$last_action_sec', event_date='$NOW_TIME';";
                if ($DB) {echo "$stmt\n";}
                $rslt=mysql_to_mysqli($stmt, $link);
                $affected_rows = mysqli_affected_rows($link);
                $timeclock_id = mysqli_insert_id($link);
                print "<!-- NEW vicidial_timeclock_log record inserted for $user:   |$affected_rows|$timeclock_id| -->\n";

                ### Update last login record in the timeclock log
                $stmt="UPDATE vicidial_timeclock_log set login_sec='$last_action_sec',tcid_link='$timeclock_id' where event='LOGIN' and user='$user' order by timeclock_id desc limit 1;";
                if ($DB) {echo "$stmt\n";}
                $rslt=mysql_to_mysqli($stmt, $link);
                $affected_rows = mysqli_affected_rows($link);
                print "<!-- vicidial_timeclock_log record updated for $user:   |$affected_rows| -->\n";

                ### Update the user's timeclock status record
                $stmt="UPDATE vicidial_timeclock_status set status='LOGOUT', user_group='$user_group', event_epoch='$StarTtimE', ip_address='$ip' where user='$user';";
                if ($DB) {echo "$stmt\n";}
                $rslt=mysql_to_mysqli($stmt, $link);
                $affected_rows = mysqli_affected_rows($link);
                print "<<!-- vicidial_timeclock_status record updated for $user:   |$affected_rows| -->\n";

                ### Add a record to the timeclock audit log
                $stmt="INSERT INTO vicidial_timeclock_audit_log set timeclock_id='$timeclock_id', event='LOGOUT', user='$user', user_group='$user_group', event_epoch='$StarTtimE', ip_address='$ip', login_sec='$last_action_sec', event_date='$NOW_TIME';";
                if ($DB) {echo "$stmt\n";}
                $rslt=mysql_to_mysqli($stmt, $link);
                $affected_rows = mysqli_affected_rows($link);
                print "<!-- NEW vicidial_timeclock_audit_log record inserted for $user:   |$affected_rows| -->\n";

                ### Update last login record in the timeclock audit log
                $stmt="UPDATE vicidial_timeclock_audit_log set login_sec='$last_action_sec',tcid_link='$timeclock_id' where event='LOGIN' and user='$user' order by timeclock_id desc limit 1;";
                if ($DB) {echo "$stmt\n";}
                $rslt=mysql_to_mysqli($stmt, $link);
                $affected_rows = mysqli_affected_rows($link);
                print "<!-- vicidial_timeclock_audit_log record updated for $user:   |$affected_rows| -->\n";
                }

            if ( ( ( ($status=='AUTOLOGOUT') or ($status=='START') or ($status=='LOGOUT') or ($status=='TIMEOUTLOGOUT') ) and ($stage=='logout') ) or ( ($status=='LOGIN') and ($stage=='login') ) )
                {echo _QXZ("ERROR: timeclock log entry already made:")." $status|$stage";  exit;}

            $BACKlink='';
            if ($referrer=='agent') 
                {$BACKlink = "<a href=\"./$SSagent_script?pl=$phone_login&pp=$phone_pass&VD_login=$user\" class=\"back-link\">"._QXZ("BACK to Agent Login Screen")."</a>";}
            if ($referrer=='admin') 
                {$BACKlink = "<a href=\"/$admin_web_directory/admin.php\" class=\"back-link\">"._QXZ("BACK to Administration")."</a>";}
            if ( ($referrer=='welcome') or (strlen($BACKlink) < 10) )
                {$BACKlink = "<a href=\"$welcomeURL\" class=\"back-link\">"._QXZ("BACK to Welcome Screen")."</a>";}

            echo"<div class=\"container\">\n";
            echo"<div class=\"card\">\n";
            echo"<div class=\"card-header\">\n";
            echo"<img src=\"$selected_logo\" class=\"logo\" alt=\"VICIDIAL Logo\" />\n";
            echo"<h1 class=\"card-title\">"._QXZ("Timeclock")."</h1>\n";
            echo"</div>\n";
            echo"<div class=\"card-body\">\n";
            echo"<div class=\"alert alert-success\">$VDdisplayMESSAGE</div>\n";
            echo"<div class=\"status-message\">\n";
            echo"<div class=\"status-text\">$LOGtimeMESSAGE</div>\n";
            echo"</div>\n";
            echo"<div class=\"text-center\">$BACKlink</div>\n";
            echo"</div>\n";
            echo"</div>\n";
            echo"<div class=\"footer\">"._QXZ("VERSION:")." $version &nbsp; &nbsp; &nbsp; "._QXZ("BUILD:")." $build</div>\n";
            echo"</div>\n";
            echo"</body>\n";
            echo"</html>\n";

            exit;
            }




        if ( ($status=='AUTOLOGOUT') or ($status=='START') or ($status=='LOGOUT') or ($status=='TIMEOUTLOGOUT') )
            {
            $VDdisplayMESSAGE = _QXZ("Time since you were last logged-in:")." $totTIME_HMS";
            $log_action = 'login';
            $button_name = _QXZ("LOGIN");;
            $LOGtimeMESSAGE = _QXZ("You last logged-out at:")." $last_action_date<BR><BR>"._QXZ("Click LOGIN below to log-in");
            }
        if ($status=='LOGIN')
            {
            $VDdisplayMESSAGE = _QXZ("Amount of time you have been logged-in:")." $totTIME_HMS";
            $log_action = 'logout';
            $button_name = _QXZ("LOGOUT");
            $LOGtimeMESSAGE = _QXZ("You logged-in at:")." $last_action_date<BR>"._QXZ("Amount of time you have been logged-in:")." $totTIME_HMS<BR><BR>"._QXZ("Click LOGOUT below to log-out");
            }

        echo"<div class=\"container\">\n";
        echo"<div class=\"card\">\n";
        echo"<div class=\"card-header\">\n";
        echo"<img src=\"$selected_logo\" class=\"logo\" alt=\"VICIDIAL Logo\" />\n";
        echo"<h1 class=\"card-title\">"._QXZ("Timeclock")."</h1>\n";
        echo"</div>\n";
        echo"<div class=\"card-body\">\n";
        echo"<div class=\"status-message\">\n";
        echo"<div class=\"status-time\">$VDdisplayMESSAGE</div>\n";
        echo"<div class=\"status-text\">$LOGtimeMESSAGE</div>\n";
        echo"</div>\n";
        echo"<form name=\"vicidial_form\" id=\"vicidial_form\" action=\"$agcPAGE\" method=\"post\">\n";
        echo"<input type=\"hidden\" name=\"stage\" value=\"$log_action\">\n";
        echo"<input type=\"hidden\" name=\"commit\" value=\"YES\">\n";
        echo"<input type=\"hidden\" name=\"referrer\" value=\"$referrer\">\n";
        echo"<input type=\"hidden\" name=\"DB\" value=\"$DB\">\n";
        echo"<input type=\"hidden\" name=\"phone_login\" value=\"$phone_login\">\n";
        echo"<input type=\"hidden\" name=\"phone_pass\" value=\"$phone_pass\">\n";
        echo"<input type=\"hidden\" name=\"VD_login\" value=\"$VD_login\">\n";
        echo"<input type=\"hidden\" name=\"VD_pass\" value=\"$VD_pass\">\n";
        echo"<input type=\"hidden\" name=\"user\" value=\"$user\">\n";
        echo"<input type=\"hidden\" name=\"pass\" value=\"$pass\">\n";
        echo"<button type=\"submit\" name=\"$button_name\" class=\"btn btn-block\">$button_name</button>\n";
        echo"</form>\n";
        echo"</div>\n";
        echo"</div>\n";
        echo"<div class=\"footer\">"._QXZ("VERSION:")." $version &nbsp; &nbsp; &nbsp; "._QXZ("BUILD:")." $build</div>\n";
        echo"</div>\n";
        echo"</body>\n";
        echo"</html>\n";

        exit;
        }



    }

else
    {
    echo"<div class=\"container\">\n";
    echo"<div class=\"card\">\n";
    echo"<div class=\"card-header\">\n";
    echo"<img src=\"$selected_logo\" class=\"logo\" alt=\"VICIDIAL Logo\" />\n";
    echo"<h1 class=\"card-title\">"._QXZ("Timeclock")."</h1>\n";
    echo"</div>\n";
    echo"<div class=\"card-body\">\n";
    echo"<form name=\"vicidial_form\" id=\"vicidial_form\" action=\"$agcPAGE\" method=\"post\">\n";
    echo"<input type=\"hidden\" name=\"stage\" value=\"login\">\n";
    echo"<input type=\"hidden\" name=\"referrer\" value=\"$referrer\">\n";
    echo"<input type=\"hidden\" name=\"DB\" value=\"$DB\">\n";
    echo"<input type=\"hidden\" name=\"phone_login\" value=\"$phone_login\">\n";
    echo"<input type=\"hidden\" name=\"phone_pass\" value=\"$phone_pass\">\n";
    echo"<input type=\"hidden\" name=\"VD_login\" value=\"$VD_login\">\n";
    echo"<input type=\"hidden\" name=\"VD_pass\" value=\"$VD_pass\">\n";
    echo"<div class=\"form-group\">\n";
    echo"<label for=\"user\" class=\"form-label\">"._QXZ("User Login").":</label>\n";
    echo"<input type=\"text\" name=\"user\" id=\"user\" class=\"form-control\" size=\"10\" maxlength=\"20\" value=\"$VD_login\">\n";
    echo"</div>\n";
    echo"<div class=\"form-group\">\n";
    echo"<label for=\"pass\" class=\"form-label\">"._QXZ("User Password").":</label>\n";
    echo"<input type=\"password\" name=\"pass\" id=\"pass\" class=\"form-control\" size=\"10\" maxlength=\"20\">\n";
    echo"</div>\n";
    echo"<button type=\"submit\" name=\"SUBMIT\" class=\"btn btn-block\">"._QXZ("SUBMIT")."</button>\n";
    echo"</form>\n";
    echo"</div>\n";
    echo"</div>\n";
    echo"<div class=\"footer\">"._QXZ("VERSION:")." $version &nbsp; &nbsp; &nbsp; "._QXZ("BUILD:")." $build</div>\n";
    echo"</div>\n";
    echo"</body>\n";
    echo"</html>\n";
    }

exit;

?>