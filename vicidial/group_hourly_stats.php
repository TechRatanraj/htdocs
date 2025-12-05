<?php
# group_hourly_stats.php
# 


$startMS = microtime();

$report_name='User Group Hourly Stats';

require("dbconnect_mysqli.php");
require("functions.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
$PHP_SELF = preg_replace('/\.php.*/i','.php',$PHP_SELF);
if (isset($_GET["group"]))				{$group=$_GET["group"];}
	elseif (isset($_POST["group"]))		{$group=$_POST["group"];}
if (isset($_GET["status"]))				{$status=$_GET["status"];}
	elseif (isset($_POST["status"]))	{$status=$_POST["status"];}
if (isset($_GET["date_with_hour"]))				{$date_with_hour=$_GET["date_with_hour"];}
	elseif (isset($_POST["date_with_hour"]))	{$date_with_hour=$_POST["date_with_hour"];}
if (isset($_GET["submit"]))				{$submit=$_GET["submit"];}
	elseif (isset($_POST["submit"]))	{$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))				{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))	{$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["DB"]))						{$DB=$_GET["DB"];}
	elseif (isset($_POST["DB"]))			{$DB=$_POST["DB"];}

$DB=preg_replace("/[^0-9a-zA-Z]/","",$DB);

$STARTtime = date("U");
$TODAY = date("Y-m-d");
$date_with_hour_default = date("Y-m-d H");
$date_no_hour_default = $TODAY;
$date = date("r");
$ip = getenv("REMOTE_ADDR");
$browser = getenv("HTTP_USER_AGENT");
if (!isset($date_with_hour)) {$date_with_hour = $date_with_hour_default;}
	$date_no_hour = $date_with_hour;
	$date_no_hour = preg_replace('/\s([0-9]{2})/i','',$date_no_hour);
if (!isset($begin_date)) {$begin_date = $TODAY;}
if (!isset($end_date)) {$end_date = $TODAY;}

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
$stmt = "SELECT use_non_latin,webroot_writable,outbound_autodial_active,user_territories_active,enable_languages,language_method,allow_web_debug FROM system_settings;";
$rslt=mysql_to_mysqli($stmt, $link);
#if ($DB) {echo "$stmt\n";}
$qm_conf_ct = mysqli_num_rows($rslt);
if ($qm_conf_ct > 0)
	{
	$row=mysqli_fetch_row($rslt);
	$non_latin =					$row[0];
	$webroot_writable =				$row[1];
	$SSoutbound_autodial_active =	$row[2];
	$user_territories_active =		$row[3];
	$SSenable_languages =			$row[4];
	$SSlanguage_method =			$row[5];
	$SSallow_web_debug =			$row[6];
	}
if ($SSallow_web_debug < 1) {$DB=0;}
##### END SETTINGS LOOKUP #####
###########################################

$group = preg_replace("/\<|\>|\'|\"|\\\\|;/","",$group);
$status = preg_replace("/\<|\>|\'|\"|\\\\|;/","",$status);
$date_with_hour = preg_replace("/\<|\>|\'|\"|\\\\|;/","",$date_with_hour);
$SUBMIT = preg_replace('/[^-_0-9a-zA-Z]/', '', $SUBMIT);
$submit = preg_replace('/[^-_0-9a-zA-Z]/', '', $submit);

if ($non_latin < 1)
	{
	$PHP_AUTH_USER = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_USER);
	$PHP_AUTH_PW = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_PW);
	}
else
	{
	$PHP_AUTH_USER = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_USER);
	$PHP_AUTH_PW = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_PW);
	}

$stmt="SELECT selected_language,user_group from vicidial_users where user='$PHP_AUTH_USER';";
if ($DB) {echo "|$stmt|\n";}
$rslt=mysql_to_mysqli($stmt, $link);
$sl_ct = mysqli_num_rows($rslt);
if ($sl_ct > 0)
	{
	$row=mysqli_fetch_row($rslt);
	$VUselected_language =		$row[0];
	$LOGuser_group =			$row[1];
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

$stmt="SELECT allowed_campaigns,allowed_reports,admin_viewable_groups,admin_viewable_call_times from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) {$HTML_text.="|$stmt|\n";}
$rslt=mysql_to_mysqli($stmt, $link);
$row=mysqli_fetch_row($rslt);
$LOGallowed_campaigns =			$row[0];
$LOGallowed_reports =			$row[1];
$LOGadmin_viewable_groups =		$row[2];
$LOGadmin_viewable_call_times =	$row[3];

$LOGallowed_campaignsSQL='';
$whereLOGallowed_campaignsSQL='';
if ( (!preg_match('/\-ALL/i', $LOGallowed_campaigns)) )
	{
	$rawLOGallowed_campaignsSQL = preg_replace("/ -/",'',$LOGallowed_campaigns);
	$rawLOGallowed_campaignsSQL = preg_replace("/ /","','",$rawLOGallowed_campaignsSQL);
	$LOGallowed_campaignsSQL = "and campaign_id IN('$rawLOGallowed_campaignsSQL')";
	$whereLOGallowed_campaignsSQL = "where campaign_id IN('$rawLOGallowed_campaignsSQL')";
	}
$regexLOGallowed_campaigns = " $LOGallowed_campaigns ";

if ( (!preg_match("/$report_name/",$LOGallowed_reports)) and (!preg_match("/ALL REPORTS/",$LOGallowed_reports)) )
	{
    Header("WWW-Authenticate: Basic realm=\"CONTACT-CENTER-ADMIN\"");
    Header("HTTP/1.0 401 Unauthorized");
    echo "You are not allowed to view this report: |$PHP_AUTH_USER|$report_name|\n";
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

$stmt="INSERT INTO vicidial_report_log set event_date=NOW(), user='$PHP_AUTH_USER', ip_address='$LOGip', report_name='$report_name', browser='$LOGbrowser', referer='$LOGhttp_referer', notes='$LOGserver_name:$LOGserver_port $LOGscript_name |$group, $status|', url='$LOGfull_url', webserver='$webserver_id';";
if ($DB) {echo "|$stmt|\n";}
$rslt=mysql_to_mysqli($stmt, $link);
$report_log_id = mysqli_insert_id($link);
##### END log visit to the vicidial_report_log table #####


$stmt="SELECT full_name,user_group from vicidial_users where user='$PHP_AUTH_USER';";
$rslt=mysql_to_mysqli($stmt, $link);
$row=mysqli_fetch_row($rslt);
$LOGfullname =		$row[0];
$LOGuser_group =	$row[1];

$stmt="SELECT allowed_campaigns,allowed_reports,admin_viewable_groups,admin_viewable_call_times from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) {$HTML_text.="|$stmt|\n";}
$rslt=mysql_to_mysqli($stmt, $link);
$row=mysqli_fetch_row($rslt);
$LOGallowed_campaigns =			$row[0];
$LOGallowed_reports =			$row[1];
$LOGadmin_viewable_groups =		$row[2];
$LOGadmin_viewable_call_times =	$row[3];

$LOGadmin_viewable_groupsSQL='';
$whereLOGadmin_viewable_groupsSQL='';
if ( (!preg_match('/\-\-ALL\-\-/i',$LOGadmin_viewable_groups)) and (strlen($LOGadmin_viewable_groups) > 3) )
	{
	$rawLOGadmin_viewable_groupsSQL = preg_replace("/ -/",'',$LOGadmin_viewable_groups);
	$rawLOGadmin_viewable_groupsSQL = preg_replace("/ /","','",$rawLOGadmin_viewable_groupsSQL);
	$LOGadmin_viewable_groupsSQL = "and user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
	$whereLOGadmin_viewable_groupsSQL = "where user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
	}


?>
<html>
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<title><?php echo _QXZ("ADMINISTRATION: Group Hourly Stats"); ?></title>
<style>
body {
    background: #f3f4f6;
    margin: 0;
    padding: 0;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
</style>
</head>
<body>

<?php
##### BEGIN Set variables to make header show properly #####
$ADD = '311111';
$hh = 'usergroups';
$sh = 'hour';
$LOGast_admin_access = '1';
$ADMIN = 'admin.php';
$page_width = '770';
$section_width = '750';
$header_font_size = '3';
$subheader_font_size = '2';
$subcamp_font_size = '2';
$header_selected_bold = '<b>';
$header_nonselected_bold = '';
$usergroups_color = '#FFFF99';
$usergroups_font = 'BLACK';
$usergroups_color = '#E6E6E6';
$subcamp_color = '#C6C6C6';
##### END Set variables to make header show properly #####

require("admin_header.php");
?>

<div style="max-width:1200px;margin:0 auto;padding:20px;">

<!-- Header Card -->
<div style="background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);overflow:hidden;margin-bottom:20px;">
    <div style="background:linear-gradient(135deg,#015B91,#0284c7);color:#fff;padding:20px 30px;">
        <h2 style="margin:0;font-size:20px;font-weight:600;">📊 <?php echo _QXZ("Group Hourly Stats"); ?> <?php echo $group ?></h2>
    </div>

<?php 
if (($group) and ($status) and ($date_with_hour)) {
    $stmt = "SELECT user,full_name from vicidial_users where user_group = '" . mysqli_real_escape_string($link, $group) . "' $LOGadmin_viewable_groupsSQL order by full_name desc;";
    if ($DB) {echo "$stmt\n";}
    $rslt = mysql_to_mysqli($stmt, $link);
    $tsrs_to_print = mysqli_num_rows($rslt);
    $o = 0;
    while($o < $tsrs_to_print) {
        $row = mysqli_fetch_row($rslt);
        $VDuser[$o] = "$row[0]";
        $VDname[$o] = "$row[1]";
        $o++;
    }

    $o = 0;
    while($o < $tsrs_to_print) {
        $stmt = "select count(*) from vicidial_log where call_date >= '" . mysqli_real_escape_string($link, $date_with_hour) . ":00:00' and  call_date <= '" . mysqli_real_escape_string($link, $date_with_hour) . ":59:59' and user='$VDuser[$o]' $LOGadmin_viewable_groupsSQL;";
        if ($DB) {echo "$stmt\n";}
        $rslt = mysql_to_mysqli($stmt, $link);
        $row = mysqli_fetch_row($rslt);
        $VDtotal[$o] = "$row[0]";

        $stmt = "select count(*) from vicidial_log where call_date >= '" . mysqli_real_escape_string($link, $date_no_hour) . " 00:00:00' and  call_date <= '" . mysqli_real_escape_string($link, $date_no_hour) . " 23:59:59' and user='$VDuser[$o]' and status='" . mysqli_real_escape_string($link, $status) . "' $LOGadmin_viewable_groupsSQL;";
        if ($DB) {echo "$stmt\n";}
        $rslt = mysql_to_mysqli($stmt, $link);
        $row = mysqli_fetch_row($rslt);
        $VDday[$o] = "$row[0]";

        $stmt = "select count(*) from vicidial_log where call_date >= '" . mysqli_real_escape_string($link, $date_with_hour) . ":00:00' and  call_date <= '" . mysqli_real_escape_string($link, $date_with_hour) . ":59:59' and user='$VDuser[$o]' and status='" . mysqli_real_escape_string($link, $status) . "' $LOGadmin_viewable_groupsSQL;";
        if ($DB) {echo "$stmt\n";}
        $rslt = mysql_to_mysqli($stmt, $link);
        $row = mysqli_fetch_row($rslt);
        $VDcount[$o] = "$row[0]";
        $o++;
    }

    echo "<div style='padding:30px;'>\n";
    echo "<div style='text-align:center;margin-bottom:25px;'>\n";
    echo "<span style='font-size:18px;font-weight:600;color:#1f2937;'>"._QXZ("TSR HOUR COUNTS").": <a href='./admin.php?ADD=3111&group_id=$group' style='color:#2563eb;text-decoration:none;'>$group</a> | <span style='color:#059669;'>$status</span> | <span style='color:#6b7280;'>$date_with_hour | $date_no_hour</span></span>\n";
    echo "</div>\n";

    echo "<div style='overflow-x:auto;'>\n";
    echo "<table style='width:100%;border-collapse:collapse;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.05);'>\n";
    echo "<thead>\n";
    echo "<tr style='background:linear-gradient(135deg,#3b82f6,#2563eb);'>\n";
    echo "<th style='padding:15px 20px;text-align:left;color:#fff;font-size:14px;font-weight:600;'>"._QXZ("TSR")."</th>\n";
    echo "<th style='padding:15px 20px;text-align:left;color:#fff;font-size:14px;font-weight:600;'>"._QXZ("ID")."</th>\n";
    echo "<th style='padding:15px 20px;text-align:right;color:#fff;font-size:14px;font-weight:600;'>$status</th>\n";
    echo "<th style='padding:15px 20px;text-align:right;color:#fff;font-size:14px;font-weight:600;'>"._QXZ("TOTAL CALLS")."</th>\n";
    echo "<th style='padding:15px 20px;text-align:right;color:#fff;font-size:14px;font-weight:600;'>$status "._QXZ("DAY")."</th>\n";
    echo "<th style='padding:15px 20px;text-align:center;color:#fff;font-size:14px;font-weight:600;'>"._QXZ("Actions")."</th>\n";
    echo "</tr>\n";
    echo "</thead>\n";
    echo "<tbody>\n";

    $day_calls = 0;
    $hour_calls = 0;
    $total_calls = 0;
    $o = 0;
    while($o < $tsrs_to_print) {
        $row_bg = ($o % 2 == 0) ? '#fff' : '#f9fafb';
        echo "<tr style='background:$row_bg;border-bottom:1px solid #e5e7eb;'>\n";
        echo "<td style='padding:12px 20px;font-size:14px;color:#374151;font-weight:500;'>$VDuser[$o]</td>\n";
        echo "<td style='padding:12px 20px;font-size:14px;color:#6b7280;'>$VDname[$o]</td>\n";
        echo "<td style='padding:12px 20px;text-align:right;font-size:15px;color:#059669;font-weight:600;'>$VDcount[$o]</td>\n";
        echo "<td style='padding:12px 20px;text-align:right;font-size:14px;color:#374151;font-weight:500;'>$VDtotal[$o]</td>\n";
        echo "<td style='padding:12px 20px;text-align:right;font-size:14px;color:#6b7280;'>$VDday[$o]</td>\n";
        echo "<td style='padding:12px 20px;text-align:center;font-size:13px;'>\n";
        echo "<a href='./admin.php?ADD=3&user=$VDuser[$o]' style='color:#2563eb;text-decoration:none;font-weight:600;margin-right:10px;'>"._QXZ("MODIFY")."</a>\n";
        echo "<a href='./user_stats.php?user=$VDuser[$o]' style='color:#059669;text-decoration:none;font-weight:600;'>"._QXZ("STATS")."</a>\n";
        echo "</td>\n";
        echo "</tr>\n";
        $total_calls = ($total_calls + $VDtotal[$o]);
        $hour_calls = ($hour_calls + $VDcount[$o]);
        $day_calls = ($day_calls + $VDday[$o]);
        $o++;
    }

    echo "<tr style='background:#f0f9ff;border-top:2px solid #3b82f6;'>\n";
    echo "<td style='padding:15px 20px;font-size:15px;color:#1f2937;font-weight:700;'>"._QXZ("TOTAL")."</td>\n";
    echo "<td style='padding:15px 20px;font-size:14px;color:#6b7280;font-weight:600;'>$status</td>\n";
    echo "<td style='padding:15px 20px;text-align:right;font-size:16px;color:#059669;font-weight:700;'>$hour_calls</td>\n";
    echo "<td style='padding:15px 20px;text-align:right;font-size:15px;color:#374151;font-weight:700;'>$total_calls</td>\n";
    echo "<td style='padding:15px 20px;text-align:right;font-size:15px;color:#6b7280;font-weight:600;'>$day_calls</td>\n";
    echo "<td></td>\n";
    echo "</tr>\n";
    echo "</tbody>\n";
    echo "</table>\n";
    echo "</div>\n";
    echo "</div>\n";
}
?>

<!-- Search Form Card -->
<div style="background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);overflow:hidden;padding:30px;margin-top:20px;">
    <form action="<?php echo $PHP_SELF ?>" method="GET">
        <input type="hidden" name="DB" value="<?php echo $DB ?>">
        
        <div style="margin-bottom:20px;">
            <h3 style="margin:0 0 20px 0;font-size:18px;font-weight:600;color:#1f2937;">
                <?php echo _QXZ("Please enter the group you want to get hourly stats for"); ?>
            </h3>
        </div>

        <div style="display:grid;grid-template-columns:1fr;gap:20px;max-width:600px;">
            <!-- Group Selection -->
            <div>
                <label style="display:block;font-weight:600;color:#374151;font-size:15px;margin-bottom:8px;">
                    <?php echo _QXZ("Group"); ?>:
                </label>
                <select name="group" style="width:100%;padding:11px 16px;border:1px solid #d1d5db;border-radius:6px;font-size:15px;background:#fff;">
                    <?php
                    $stmt = "SELECT user_group,group_name from vicidial_user_groups $whereLOGadmin_viewable_groupsSQL order by user_group";
                    $rslt = mysql_to_mysqli($stmt, $link);
                    $groups_to_print = mysqli_num_rows($rslt);
                    $o = 0;
                    while ($groups_to_print > $o) {
                        $rowx = mysqli_fetch_row($rslt);
                        $selected = ($group == $rowx[0]) ? 'selected' : '';
                        echo "<option value='$rowx[0]' $selected>$rowx[0] - $rowx[1]</option>\n";
                        $o++;
                    }
                    ?>
                </select>
            </div>

            <!-- Status Input -->
            <div>
                <label style="display:block;font-weight:600;color:#374151;font-size:15px;margin-bottom:8px;">
                    <?php echo _QXZ("Status"); ?>:
                </label>
                <input type="text" name="status" size="10" maxlength="10" value="<?php echo $status ?>" 
                       style="width:100%;padding:11px 16px;border:1px solid #d1d5db;border-radius:6px;font-size:15px;background:#fff;"
                       placeholder="<?php echo _QXZ("example").": "._QXZ("XFER"); ?>">
                <span style="font-size:13px;color:#6b7280;margin-top:5px;display:block;">
                    (<?php echo _QXZ("example"); ?>: <?php echo _QXZ("XFER"); ?>)
                </span>
            </div>

            <!-- Date with Hour Input -->
            <div>
                <label style="display:block;font-weight:600;color:#374151;font-size:15px;margin-bottom:8px;">
                    <?php echo _QXZ("Date with hour"); ?>:
                </label>
                <input type="text" name="date_with_hour" size="14" maxlength="13" value="<?php echo $date_with_hour ?>" 
                       style="width:100%;padding:11px 16px;border:1px solid #d1d5db;border-radius:6px;font-size:15px;background:#fff;"
                       placeholder="2004-06-25 14">
                <span style="font-size:13px;color:#6b7280;margin-top:5px;display:block;">
                    (<?php echo _QXZ("example"); ?>: 2004-06-25 14)
                </span>
            </div>

            <!-- Submit Button -->
            <div style="margin-top:10px;">
                <input type="submit" name="submit" value="<?php echo _QXZ("SUBMIT"); ?>" 
                       style="width:100%;padding:12px 24px;background:#2563eb;color:#fff;font-size:15px;font-weight:600;border:none;border-radius:6px;cursor:pointer;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
            </div>
        </div>
    </form>
</div>

<?php
$ENDtime = date("U");
$RUNtime = ($ENDtime - $STARTtime);

echo "<div style='text-align:center;margin-top:30px;padding:20px;'>\n";
echo "<span style='font-size:12px;color:#9ca3af;'>"._QXZ("script runtime").": $RUNtime "._QXZ("seconds")."</span>\n";
echo "</div>\n";
?>

</div>
</div>

</body>
</html>

<?php
if ($db_source == 'S') {
    mysqli_close($link);
    $use_slave_server = 0;
    $db_source = 'M';
    require("dbconnect_mysqli.php");
}

$endMS = microtime();
$startMSary = explode(" ", $startMS);
$endMSary = explode(" ", $endMS);
$runS = ($endMSary[0] - $startMSary[0]);
$runM = ($endMSary[1] - $startMSary[1]);
$TOTALrun = ($runS + $runM);

$stmt = "UPDATE vicidial_report_log set run_time='$TOTALrun' where report_log_id='$report_log_id';";
if ($DB) {echo "|$stmt|\n";}
$rslt = mysql_to_mysqli($stmt, $link);

exit;
?>
