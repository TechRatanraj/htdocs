<?php
# user_group_bulk_change.php
# 
# Copyright (C) 2022  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2

#

header ("Content-type: text/html; charset=utf-8");

require("dbconnect_mysqli.php");
require("functions.php");

$PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
$PHP_SELF=$_SERVER['PHP_SELF'];
$PHP_SELF = preg_replace('/\.php.*/i','.php',$PHP_SELF);
if (isset($_GET["old_group"]))			{$old_group=$_GET["old_group"];}
	elseif (isset($_POST["old_group"]))	{$old_group=$_POST["old_group"];}
if (isset($_GET["group"]))				{$group=$_GET["group"];}
	elseif (isset($_POST["group"]))		{$group=$_POST["group"];}
if (isset($_GET["stage"]))				{$stage=$_GET["stage"];}
	elseif (isset($_POST["stage"]))		{$stage=$_POST["stage"];}
if (isset($_GET["DB"]))					{$DB=$_GET["DB"];}
	elseif (isset($_POST["DB"]))		{$DB=$_POST["DB"];}
if (isset($_GET["submit"]))				{$submit=$_GET["submit"];}
	elseif (isset($_POST["submit"]))	{$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))				{$SUBMIT=$_GET["SUBMIT"];}
	elseif (isset($_POST["SUBMIT"]))	{$SUBMIT=$_POST["SUBMIT"];}

$DB=preg_replace("/[^0-9a-zA-Z]/","",$DB);

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
$stmt = "SELECT use_non_latin,webroot_writable,outbound_autodial_active,enable_languages,language_method,allow_web_debug FROM system_settings;";
$rslt=mysql_to_mysqli($stmt, $link);
#if ($DB) {echo "$stmt\n";}
$qm_conf_ct = mysqli_num_rows($rslt);
if ($qm_conf_ct > 0)
	{
	$row=mysqli_fetch_row($rslt);
	$non_latin =					$row[0];
	$webroot_writable =				$row[1];
	$SSoutbound_autodial_active =	$row[2];
	$SSenable_languages =			$row[3];
	$SSlanguage_method =			$row[4];
	$SSallow_web_debug =			$row[5];
	}
if ($SSallow_web_debug < 1) {$DB=0;}
##### END SETTINGS LOOKUP #####
###########################################

$stage = preg_replace('/[^-_0-9a-zA-Z]/',"",$stage);
$submit = preg_replace('/[^-_0-9a-zA-Z]/',"",$submit);
$SUBMIT = preg_replace('/[^-_0-9a-zA-Z]/',"",$SUBMIT);

if ($non_latin < 1)
	{
	$PHP_AUTH_USER = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_USER);
	$PHP_AUTH_PW = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_PW);
	$group = preg_replace('/[^-_0-9a-zA-Z]/', '', $group);
	$old_group = preg_replace('/[^-_0-9a-zA-Z]/', '', $old_group);
	}
else
	{
	$PHP_AUTH_USER = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_USER);
	$PHP_AUTH_PW = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_PW);
	$group = preg_replace('/[^-_0-9\p{L}]/u', '', $group);
	$old_group = preg_replace('/[^-_0-9\p{L}]/u', '', $old_group);
	}

$StarTtimE = date("U");
$TODAY = date("Y-m-d");
$NOW_TIME = date("Y-m-d H:i:s");
$ip = getenv("REMOTE_ADDR");

if (!isset($begin_date)) {$begin_date = $TODAY;}
if (!isset($end_date)) {$end_date = $TODAY;}

$date = date("r");
$ip = getenv("REMOTE_ADDR");
$browser = getenv("HTTP_USER_AGENT");

$disable_user_group_bulk_change=0;
if (file_exists('options.php'))
	{
	require('options.php');
	}

$stmt="SELECT selected_language from vicidial_users where user='$PHP_AUTH_USER';";
if ($DB) {echo "|$stmt|\n";}
$rslt=mysql_to_mysqli($stmt, $link);
$sl_ct = mysqli_num_rows($rslt);
if ($sl_ct > 0)
	{
	$row=mysqli_fetch_row($rslt);
	$VUselected_language =		$row[0];
	}

$auth=0;
$auth_message = user_authorization($PHP_AUTH_USER,$PHP_AUTH_PW,'',1,0);
if ($auth_message == 'GOOD')
	{$auth=1;}

if ($auth < 1)
	{
	$VDdisplayMESSAGE = _QXZ("Login incorrect, please try again");
	if ($auth_message == 'LOCK')
		{
		$VDdisplayMESSAGE = ("Too many login attempts, try again in 15 minutes");
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

$stmt="SELECT full_name,change_agent_campaign,modify_timeclock_log,user_group,modify_users from vicidial_users where user='$PHP_AUTH_USER';";
$rslt=mysql_to_mysqli($stmt, $link);
$row=mysqli_fetch_row($rslt);
$LOGfullname =				$row[0];
$change_agent_campaign =	$row[1];
$modify_timeclock_log =		$row[2];
$LOGuser_group =			$row[3];
$modify_users =				$row[4];

# check their permissions
if ( ($change_agent_campaign < 1 ) or ($modify_users < 1) )
	{
	header ("Content-type: text/html; charset=utf-8");
	echo _QXZ("You do not have permissions to modify users")."\n";
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

$LOGadmin_viewable_groupsSQL='';
$whereLOGadmin_viewable_groupsSQL='';
if ( (!preg_match('/\-\-ALL\-\-/i',$LOGadmin_viewable_groups)) and (strlen($LOGadmin_viewable_groups) > 3) )
	{
	$rawLOGadmin_viewable_groupsSQL = preg_replace("/ -/",'',$LOGadmin_viewable_groups);
	$rawLOGadmin_viewable_groupsSQL = preg_replace("/ /","','",$rawLOGadmin_viewable_groupsSQL);
	$LOGadmin_viewable_groupsSQL = "and user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
	$whereLOGadmin_viewable_groupsSQL = "where user_group IN('---ALL---','$rawLOGadmin_viewable_groupsSQL')";
	}

$stmt="SELECT user_group,group_name from vicidial_user_groups $whereLOGadmin_viewable_groupsSQL order by user_group desc;";
$rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {echo "$stmt\n";}
$groups_to_print = mysqli_num_rows($rslt);
$i=0;
while ($i < $groups_to_print)
	{
	$row=mysqli_fetch_row($rslt);
	$groups[$i] =		$row[0];
	$group_names[$i] =	$row[1];
	$i++;
	}


?>
<html>
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<title><?php echo _QXZ("ADMINISTRATION: User Group Bulk Change"); ?></title>
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
$sh = 'bulk';
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

<div style="max-width:1000px;margin:0 auto;padding:20px;">

<!-- Header Card -->
<div style="background:#fff;border-radius:12px;box-shadow:0 1px 3px rgba(0,0,0,0.1);overflow:hidden;margin-bottom:20px;">
    <div style="background:linear-gradient(135deg,#015B91,#0284c7);color:#fff;padding:20px 30px;">
        <h2 style="margin:0;font-size:20px;font-weight:600;">👥 <?php echo _QXZ("User Group Bulk Change"); ?></h2>
    </div>

    <div style="padding:30px;">

<?php
if ($disable_user_group_bulk_change > 0) {
    echo "<div style='background:#fee2e2;border-left:4px solid #ef4444;padding:16px 20px;border-radius:8px;'>\n";
    echo "<strong style='color:#991b1b;font-size:16px;'>⚠️ " . _QXZ("This utility has been disabled") . "</strong>\n";
    echo "</div>\n";
    echo "</div></div></div></body></html>\n";
    exit;
}

##### GROUP CHANGE FOR ALL USERS IN A USER GROUP #####
if ($stage == "one_user_group_change") {
    $stmt = "UPDATE vicidial_users set user_group='" . mysqli_real_escape_string($link, $group) . "' where user_group='" . mysqli_real_escape_string($link, $old_group) . "' $LOGadmin_viewable_groupsSQL;";
    $rslt = mysql_to_mysqli($stmt, $link);

    echo "<div style='background:#d1fae5;border-left:4px solid #10b981;padding:16px 20px;border-radius:8px;margin-bottom:20px;'>\n";
    echo "<strong style='color:#065f46;font-size:16px;'>✅ " . _QXZ("All User Group %1s Users changed to the %2s User Group", 0, '', $old_group, $group) . "</strong>\n";
    echo "</div>\n";
    
    ### LOG INSERTION Admin Log Table ###
    $SQL_log = "$stmt|";
    $SQL_log = preg_replace('/;/', '', $SQL_log);
    $SQL_log = addslashes($SQL_log);
    $stmt = "INSERT INTO vicidial_admin_log set event_date='$NOW_TIME', user='$PHP_AUTH_USER', ip_address='$ip', event_section='USERGROUPS', event_type='MODIFY', record_id='$group', event_code='ADMIN BULK USER GROUP CHANGE', event_sql=\"$SQL_log\", event_notes='Old Group: $old_group';";
    if ($DB) {echo "|$stmt|\n";}
    $rslt = mysql_to_mysqli($stmt, $link);

    exit;
}

##### GROUP CHANGE FOR ALL USERS IN THE SYSTEM EXCEPT FOR LEVEL > 6 AND ADMIN GROUP #####
if ($stage == "all_user_group_change") {
    $stmt = "UPDATE vicidial_users set user_group='" . mysqli_real_escape_string($link, $group) . "' where user_group!='ADMIN' and user_level < 7 $LOGadmin_viewable_groupsSQL;";
    $rslt = mysql_to_mysqli($stmt, $link);

    echo "<div style='background:#d1fae5;border-left:4px solid #10b981;padding:16px 20px;border-radius:8px;margin-bottom:20px;'>\n";
    echo "<strong style='color:#065f46;font-size:16px;'>✅ " . _QXZ("All non-Admin Users changed to the") . " $group " . _QXZ("User Group") . "</strong>\n";
    echo "</div>\n";
    
    ### LOG INSERTION Admin Log Table ###
    $SQL_log = "$stmt|";
    $SQL_log = preg_replace('/;/', '', $SQL_log);
    $SQL_log = addslashes($SQL_log);
    $stmt = "INSERT INTO vicidial_admin_log set event_date='$NOW_TIME', user='$PHP_AUTH_USER', ip_address='$ip', event_section='USERGROUPS', event_type='MODIFY', record_id='$group', event_code='ADMIN BULK USER GROUP CHANGE', event_sql=\"$SQL_log\", event_notes='ALL NON-ADMIN';";
    if ($DB) {echo "|$stmt|\n";}
    $rslt = mysql_to_mysqli($stmt, $link);

    exit;
}

### One user_group change
echo "<div style='background:#fef3c7;border-left:4px solid #f59e0b;padding:20px;border-radius:8px;margin-bottom:25px;'>\n";
echo "<h3 style='margin:0 0 20px 0;font-size:18px;font-weight:600;color:#92400e;'>🔄 " . _QXZ("Change Users in Specific Group") . "</h3>\n";
echo "<form action='$PHP_SELF' method='POST'>\n";
echo "<input type='hidden' name='DB' value='$DB'>\n";
echo "<input type='hidden' name='stage' value='one_user_group_change'>\n";

echo "<div style='display:grid;grid-template-columns:1fr;gap:20px;max-width:600px;'>\n";
echo "<div>\n";
echo "<label style='display:block;font-weight:600;color:#78350f;font-size:15px;margin-bottom:8px;'>" . _QXZ("Change Users in this group") . ":</label>\n";
echo "<select name='old_group' style='width:100%;padding:11px 16px;border:1px solid #d1d5db;border-radius:6px;font-size:15px;background:#fff;'>\n";
$o = 0;
while ($groups_to_print > $o) {
    echo "<option value='$groups[$o]'>$groups[$o] - $group_names[$o]</option>\n";
    $o++;
}
echo "</select>\n";
echo "</div>\n";

echo "<div>\n";
echo "<label style='display:block;font-weight:600;color:#78350f;font-size:15px;margin-bottom:8px;'>" . _QXZ("to this group") . ":</label>\n";
echo "<select name='group' style='width:100%;padding:11px 16px;border:1px solid #d1d5db;border-radius:6px;font-size:15px;background:#fff;'>\n";
$o = 0;
while ($groups_to_print > $o) {
    echo "<option value='$groups[$o]'>$groups[$o] - $group_names[$o]</option>\n";
    $o++;
}
echo "</select>\n";
echo "</div>\n";

echo "<div style='margin-top:10px;'>\n";
echo "<input type='submit' name='submit' value='" . _QXZ("SUBMIT") . "' style='width:100%;padding:12px 24px;background:#f59e0b;color:#fff;font-size:15px;font-weight:600;border:none;border-radius:6px;cursor:pointer;box-shadow:0 1px 3px rgba(0,0,0,0.1);'>\n";
echo "</div>\n";
echo "</div>\n";

echo "</form>\n";
echo "</div>\n";

### All user_group change
echo "<div style='background:#fee2e2;border-left:4px solid #ef4444;padding:20px;border-radius:8px;margin-bottom:25px;'>\n";
echo "<h3 style='margin:0 0 20px 0;font-size:18px;font-weight:600;color:#991b1b;'>⚠️ " . _QXZ("Change ALL Non-Admin Users") . "</h3>\n";
echo "<form action='$PHP_SELF' method='POST'>\n";
echo "<input type='hidden' name='DB' value='$DB'>\n";
echo "<input type='hidden' name='stage' value='all_user_group_change'>\n";

echo "<div style='display:grid;grid-template-columns:1fr;gap:20px;max-width:600px;'>\n";
echo "<div>\n";
echo "<label style='display:block;font-weight:600;color:#7f1d1d;font-size:15px;margin-bottom:8px;'>" . _QXZ("Change ALL non-Admin Users to this group") . ":</label>\n";
echo "<select name='group' style='width:100%;padding:11px 16px;border:1px solid #d1d5db;border-radius:6px;font-size:15px;background:#fff;'>\n";
$o = 0;
while ($groups_to_print > $o) {
    echo "<option value='$groups[$o]'>$groups[$o] - $group_names[$o]</option>\n";
    $o++;
}
echo "</select>\n";
echo "</div>\n";

echo "<div style='margin-top:10px;'>\n";
echo "<input type='submit' name='submit' value='" . _QXZ("SUBMIT") . "' style='width:100%;padding:12px 24px;background:#ef4444;color:#fff;font-size:15px;font-weight:600;border:none;border-radius:6px;cursor:pointer;box-shadow:0 1px 3px rgba(0,0,0,0.1);'>\n";
echo "</div>\n";
echo "</div>\n";

echo "</form>\n";
echo "</div>\n";

$ENDtime = date("U");
$RUNtime = ($ENDtime - $StarTtimE);

echo "<div style='text-align:center;margin-top:30px;padding:20px;'>\n";
echo "<span style='font-size:12px;color:#9ca3af;'>" . _QXZ("script runtime") . ": $RUNtime " . _QXZ("seconds") . "</span>\n";
if ($DB) {
    echo "<br><span style='font-size:11px;color:#d1d5db;'>|$stage|$group|</span>\n";
}
echo "</div>\n";
?>

    </div>
</div>

</div>

</body>
</html>

<?php
exit;
?>
