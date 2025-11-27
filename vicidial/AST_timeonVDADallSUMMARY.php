<?php 
# AST_timeonVDADallSUMMARY.php
#

require("dbconnect_mysqli.php");
require("functions.php");

 $PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
 $PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
 $PHP_SELF=$_SERVER['PHP_SELF'];
 $PHP_SELF = preg_replace('/\.php.*/i','.php',$PHP_SELF);
if (isset($_GET["RR"]))					{$RR=$_GET["RR"];}
    elseif (isset($_POST["RR"]))		{$RR=$_POST["RR"];}
if (isset($_GET["DB"]))					{$DB=$_GET["DB"];}
    elseif (isset($_POST["DB"]))		{$DB=$_POST["DB"];}
if (isset($_GET["adastats"]))			{$adastats=$_GET["adastats"];}
    elseif (isset($_POST["adastats"]))	{$adastats=$_POST["adastats"];}
if (isset($_GET["types"]))				{$types=$_GET["types"];}
    elseif (isset($_POST["types"]))		{$types=$_POST["types"];}
if (isset($_GET["submit"]))				{$submit=$_GET["submit"];}
    elseif (isset($_POST["submit"]))	{$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))				{$SUBMIT=$_GET["SUBMIT"];}
    elseif (isset($_POST["SUBMIT"]))	{$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["file_download"]))				{$file_download=$_GET["file_download"];}
    elseif (isset($_POST["file_download"]))	{$file_download=$_POST["file_download"];}

 $DB=preg_replace("/[^0-9a-zA-Z]/","",$DB);

 $NOW_TIME = date("Y-m-d H:i:s");
 $STARTtime = date("U");
if (!isset($RR))	{$RR=4;}
if (!isset($types))	{$types='SHOW ALL CAMPAIGNS';}

 $report_name = 'Real-Time Campaign Summary';
 $db_source = 'M';

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
 $stmt = "SELECT use_non_latin,outbound_autodial_active,slave_db_server,reports_use_slave_db,enable_languages,language_method,allow_web_debug FROM system_settings;";
 $rslt=mysql_to_mysqli($stmt, $link);
#if ($DB) {$MAIN.="$stmt\n";}
 $qm_conf_ct = mysqli_num_rows($rslt);
if ($qm_conf_ct > 0)
    {
    $row=mysqli_fetch_row($rslt);
    $non_latin =					$row[0];
    $outbound_autodial_active =		$row[1];
    $slave_db_server =				$row[2];
    $reports_use_slave_db =			$row[3];
    $SSenable_languages =			$row[4];
    $SSlanguage_method =			$row[5];
    $SSallow_web_debug =			$row[6];
    }
if ($SSallow_web_debug < 1) {$DB=0;}
##### END SETTINGS LOOKUP #####
###########################################

 $submit = preg_replace('/[^-_0-9a-zA-Z]/', '', $submit);
 $SUBMIT = preg_replace('/[^-_0-9a-zA-Z]/', '', $SUBMIT);
 $RR = preg_replace('/[^-_0-9a-zA-Z]/', '', $RR);
 $file_download = preg_replace('/[^-_0-9a-zA-Z]/', '', $file_download);
 $adastats = preg_replace('/[^-_0-9a-zA-Z]/', '', $adastats);
 $types = preg_replace('/[^- \_0-9a-zA-Z]/', '', $types);

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

if ( (strlen($slave_db_server)>5) and (preg_match("/$report_name/",$reports_use_slave_db)) )
    {
    mysqli_close($link);
    $use_slave_server=1;
    $db_source = 'S';
    require("dbconnect_mysqli.php");
    $MAIN.="<!-- Using slave server $slave_db_server $db_source -->\n";
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

 $stmt="SELECT user_group from vicidial_users where user='$PHP_AUTH_USER';";
if ($DB) {$MAIN.="|$stmt|\n";}
 $rslt=mysql_to_mysqli($stmt, $link);
 $row=mysqli_fetch_row($rslt);
 $LOGuser_group =			$row[0];

 $stmt="SELECT allowed_campaigns,allowed_reports from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) {$MAIN.="|$stmt|\n";}
 $rslt=mysql_to_mysqli($stmt, $link);
 $row=mysqli_fetch_row($rslt);
 $LOGallowed_campaigns = $row[0];
 $LOGallowed_reports =	$row[1];

if ( (!preg_match("/$report_name/",$LOGallowed_reports)) and (!preg_match("/ALL REPORTS/",$LOGallowed_reports)) )
    {
    Header("WWW-Authenticate: Basic realm=\"CONTACT-CENTER-ADMIN\"");
    Header("HTTP/1.0 401 Unauthorized");
    echo _QXZ("You are not allowed to view this report").": |$PHP_AUTH_USER|$report_name|"._QXZ("$report_name")."|\n";
    exit;
    }

 $LOGallowed_campaignsSQL='';
 $whereLOGallowed_campaignsSQL='';
if ( (!preg_match('/\-ALL/i', $LOGallowed_campaigns)) )
    {
    $rawLOGallowed_campaignsSQL = preg_replace("/ -/",'',$LOGallowed_campaigns);
    $rawLOGallowed_campaignsSQL = preg_replace("/ /","','",$rawLOGallowed_campaignsSQL);
    $LOGallowed_campaignsSQL = "and campaign_id IN('$rawLOGallowed_campaignsSQL')";
    $whereLOGallowed_campaignsSQL = "where campaign_id IN('$rawLOGallowed_campaignsSQL')";
    }

 $campaign_typeSQL='';
if ($types == 'AUTO-DIAL ONLY')			{$campaign_typeSQL="and dial_method IN('RATIO','ADAPT_HARD_LIMIT','ADAPT_TAPERED','ADAPT_AVERAGE')";} 
if ($types == 'MANUAL ONLY')			{$campaign_typeSQL="and dial_method IN('MANUAL','INBOUND_MAN')";} 
if ($types == 'INBOUND ONLY')			{$campaign_typeSQL="and campaign_allow_inbound='Y'";} 

 $stmt="select campaign_id from vicidial_campaigns where active='Y' $LOGallowed_campaignsSQL $campaign_typeSQL order by campaign_id;";
 $rslt=mysql_to_mysqli($stmt, $link);
if (!isset($DB))   {$DB=0;}
if ($DB) {$MAIN.="$stmt\n";}
 $groups_to_print = mysqli_num_rows($rslt);
 $groups=array();
 $i=0;
while ($i < $groups_to_print)
    {
    $row=mysqli_fetch_row($rslt);
    $groups[$i] =$row[0];
    $i++;
    }

require("screen_colors.php");

 $NWB = "<IMG SRC=\"help.png\" onClick=\"FillAndShowHelpDiv(event, '";
 $NWE = "')\" WIDTH=20 HEIGHT=20 BORDER=0 ALT=\"HELP\" ALIGN=TOP\">";

 $HEADER.="<HTML>\n";
 $HEADER.="<HEAD>\n";
 $HEADER.="<link rel=\"stylesheet\" type=\"text/css\" href=\"vicidial_stylesheet.php\">\n";
 $HEADER.="<script language=\"JavaScript\" src=\"help.js\"></script>\n";
 $HEADER.="<script src=\"https://cdn.jsdelivr.net/npm/chart.js\"></script>\n";

// Modern CSS styles
 $HEADER.="<style>
:root {
    --primary-color: #4a6cf7;
    --secondary-color: #f5f7ff;
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --dark-color: #1f2937;
    --light-color: #f9fafb;
    --border-radius: 8px;
    --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --transition: all 0.3s ease;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: var(--light-color);
    color: var(--dark-color);
    margin: 0;
    padding: 0;
    line-height: 1.6;
}

.dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.dashboard-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, #6366f1 100%);
    color: white;
    padding: 20px;
    border-radius: var(--border-radius);
    margin-bottom: 20px;
    box-shadow: var(--box-shadow);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dashboard-title {
    font-size: 24px;
    font-weight: 600;
    margin: 0;
}

.dashboard-controls {
    display: flex;
    gap: 15px;
    align-items: center;
}

.control-group {
    display: flex;
    gap: 10px;
    align-items: center;
}

.refresh-controls a {
    background-color: rgba(255, 255, 255, 0.2);
    color: white;
    padding: 8px 15px;
    border-radius: var(--border-radius);
    text-decoration: none;
    transition: var(--transition);
    font-weight: 500;
}

.refresh-controls a:hover {
    background-color: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

.btn {
    background-color: white;
    color: var(--primary-color);
    border: none;
    padding: 8px 15px;
    border-radius: var(--border-radius);
    cursor: pointer;
    font-weight: 500;
    transition: var(--transition);
}

.btn:hover {
    background-color: var(--secondary-color);
    transform: translateY(-2px);
}

select {
    padding: 8px 12px;
    border-radius: var(--border-radius);
    border: 1px solid #d1d5db;
    background-color: white;
}

.campaign-card {
    background-color: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    margin-bottom: 25px;
    overflow: hidden;
    transition: var(--transition);
}

.campaign-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.campaign-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e2e8f0;
}

.campaign-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
    color: var(--dark-color);
}

.campaign-title a {
    color: var(--primary-color);
    text-decoration: none;
}

.campaign-title a:hover {
    text-decoration: underline;
}

.campaign-actions a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
}

.campaign-actions a:hover {
    text-decoration: underline;
}

.campaign-content {
    padding: 20px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.stat-item {
    background-color: var(--secondary-color);
    padding: 15px;
    border-radius: var(--border-radius);
    border-left: 4px solid var(--primary-color);
}

.stat-label {
    font-size: 12px;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 5px;
}

.stat-value {
    font-size: 18px;
    font-weight: 600;
    color: var(--dark-color);
}

.danger {
    border-left-color: var(--danger-color);
    color: var(--danger-color);
}

.warning {
    border-left-color: var(--warning-color);
    color: var(--warning-color);
}

.success {
    border-left-color: var(--success-color);
    color: var(--success-color);
}

.call-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 20px;
}

.call-stat {
    background-color: var(--secondary-color);
    padding: 10px 15px;
    border-radius: var(--border-radius);
    font-size: 14px;
}

.call-stat strong {
    font-weight: 600;
    color: var(--dark-color);
}

.agent-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 20px;
}

.agent-stat {
    background-color: var(--secondary-color);
    padding: 10px 15px;
    border-radius: var(--border-radius);
    font-size: 14px;
}

.agent-stat strong {
    font-weight: 600;
    color: var(--dark-color);
}

.category-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 15px;
}

.category-stat {
    background-color: var(--secondary-color);
    padding: 10px 15px;
    border-radius: var(--border-radius);
    font-size: 14px;
}

.category-stat strong {
    font-weight: 600;
    color: var(--dark-color);
}

.time-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.time-stat {
    background-color: var(--secondary-color);
    padding: 10px 15px;
    border-radius: var(--border-radius);
    font-size: 14px;
}

.time-stat strong {
    font-weight: 600;
    color: var(--dark-color);
}

.footer-info {
    text-align: right;
    color: #6b7280;
    font-size: 12px;
    margin-top: 20px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .dashboard-controls {
        flex-direction: column;
        align-items: flex-start;
        width: 100%;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .campaign-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}
</style>";

 $HEADER.="<META HTTP-EQUIV=Refresh CONTENT=\"$RR; URL=$PHP_SELF?RR=$RR&DB=$DB&adastats=$adastats&types=$types\">\n";
 $HEADER.="<TITLE>"._QXZ("$report_name")."</TITLE></HEAD><BODY BGCOLOR=WHITE marginheight=0 marginwidth=0 leftmargin=0 topmargin=0>\n";
 $HEADER.="<div id='HelpDisplayDiv' class='help_info' style='display:none;'></div>";

 $short_header=1;

 $MAIN.="<div class='dashboard-container'>";
 $MAIN.="<div class='dashboard-header'>";
 $MAIN.="<h1 class='dashboard-title'>"._QXZ("$report_name")." $NWB#CampaignSUMMARY$NWE</h1>";
 $MAIN.="<div class='dashboard-controls'>";
 $MAIN.="<div class='control-group refresh-controls'>";
 $MAIN.="<a href=\"$PHP_SELF?group=$group&RR=4000&DB=$DB&adastats=$adastats&types=$types\">"._QXZ("STOP")."</a>";
 $MAIN.="<a href=\"$PHP_SELF?group=$group&RR=40&DB=$DB&adastats=$adastats&types=$types\">"._QXZ("SLOW")."</a>";
 $MAIN.="<a href=\"$PHP_SELF?group=$group&RR=4&DB=$DB&adastats=$adastats&types=$types\">"._QXZ("GO")."</a>";
 $MAIN.="</div>";
 $MAIN.="<div class='control-group'>";
if ($adastats<2)
    {
    $MAIN.="<a href=\"$PHP_SELF?group=$group&RR=$RR&DB=$DB&adastats=2&types=$types\">+ "._QXZ("VIEW MORE SETTINGS")."</a>";
    }
else
    {
    $MAIN.="<a href=\"$PHP_SELF?group=$group&RR=$RR&DB=$DB&adastats=1&types=$types\">- "._QXZ("VIEW LESS SETTINGS")."</a>";
    }
 $MAIN.="</div>";
 $MAIN.="<div class='control-group'>";
 $MAIN.="<a href=\"$PHP_SELF?group=$group&RR=$RR&DB=$DB&adastats=$adastats&types=$types&file_download=1\">"._QXZ("DOWNLOAD")."</a>";
 $MAIN.="<a href=\"./admin.php?ADD=999999\">"._QXZ("REPORTS")."</a>";
 $MAIN.="</div>";
 $MAIN.="</div>";
 $MAIN.="</div>";

 $MAIN.="<FORM action=$PHP_SELF method=POST class='filter-form'>";
 $MAIN.="<div class='filter-controls'>";
 $MAIN.="<input type=hidden name=RR value=$RR>";
 $MAIN.="<input type=hidden name=DB value=$DB>";
 $MAIN.="<input type=hidden name=adastats value=$adastats>";
 $MAIN.="<select size=1 name=types>";
 $MAIN.="<option value=\"SHOW ALL CAMPAIGNS\"";
    if ($types == 'SHOW ALL CAMPAIGNS') {$MAIN.=" selected";} 
 $MAIN.=">"._QXZ("SHOW ALL CAMPAIGNS")."</option>";
 $MAIN.="<option value=\"AUTO-DIAL ONLY\"";
    if ($types == 'AUTO-DIAL ONLY') {$MAIN.=" selected";} 
 $MAIN.=">"._QXZ("AUTO-DIAL ONLY")."</option>";
 $MAIN.="<option value=\"MANUAL ONLY\"";
    if ($types == 'MANUAL ONLY') {$MAIN.=" selected";} 
 $MAIN.=">"._QXZ("MANUAL ONLY")."</option>";
 $MAIN.="<option value=\"INBOUND ONLY\"";
    if ($types == 'INBOUND ONLY') {$MAIN.=" selected";} 
 $MAIN.=">"._QXZ("INBOUND ONLY")."</option>";
 $MAIN.="</select>";
 $MAIN.="<button type='submit' name='submit' class='btn'>"._QXZ("SUBMIT")."</button>";
 $MAIN.="</div>";
 $MAIN.="</FORM>";

 $k=0;
while($k<$groups_to_print)
{
 $NFB = '<b><font size=3 face="courier">';
 $NFE = '</font></b>';
 $F=''; $FG=''; $B=''; $BG='';

 $group = $groups[$k];

 $MAIN.="<div class='campaign-card'>";
 $MAIN.="<div class='campaign-header'>";
 $MAIN.="<h2 class='campaign-title'><a href=\"./realtime_report.php?group=$group&RR=$RR&DB=$DB&adastats=$adastats\">$group</a></h2>";
 $MAIN.="<div class='campaign-actions'>";
 $MAIN.="<a href=\"./admin.php?ADD=34&campaign_id=$group\">"._QXZ("Modify")."</a>";
 $MAIN.="</div>";
 $MAIN.="</div>";

 $CSV_text.="\"$group\"\n";

 $stmt = "select count(*) from vicidial_campaigns where campaign_id='$group' and campaign_allow_inbound='Y';";
 $rslt=mysql_to_mysqli($stmt, $link);
    $row=mysqli_fetch_row($rslt);
    $campaign_allow_inbound = $row[0];

 $stmt="select auto_dial_level,dial_status_a,dial_status_b,dial_status_c,dial_status_d,dial_status_e,lead_order,lead_filter_id,hopper_level,dial_method,adaptive_maximum_level,adaptive_dropped_percentage,adaptive_dl_diff_target,adaptive_intensity,available_only_ratio_tally,adaptive_latest_server_time,local_call_time,dial_timeout,dial_statuses from vicidial_campaigns where campaign_id='" . mysqli_real_escape_string($link, $group) . "';";
 $rslt=mysql_to_mysqli($stmt, $link);
 $row=mysqli_fetch_row($rslt);
 $DIALlev =		$row[0];
 $DIALstatusA =	$row[1];
 $DIALstatusB =	$row[2];
 $DIALstatusC =	$row[3];
 $DIALstatusD =	$row[4];
 $DIALstatusE =	$row[5];
 $DIALorder =	$row[6];
 $DIALfilter =	$row[7];
 $HOPlev =		$row[8];
 $DIALmethod =	$row[9];
 $maxDIALlev =	$row[10];
 $DROPmax =		$row[11];
 $targetDIFF =	$row[12];
 $ADAintense =	$row[13];
 $ADAavailonly =	$row[14];
 $TAPERtime =	$row[15];
 $CALLtime =		$row[16];
 $DIALtimeout =	$row[17];
 $DIALstatuses =	$row[18];
    $DIALstatuses = (preg_replace("/ -$|^ /","",$DIALstatuses));
    $DIALstatuses = (preg_replace('/\s/', ', ', $DIALstatuses));

 $stmt="select count(*) from vicidial_hopper where campaign_id='" . mysqli_real_escape_string($link, $group) . "';";
 $rslt=mysql_to_mysqli($stmt, $link);
 $row=mysqli_fetch_row($rslt);
 $VDhop = $row[0];

 $stmt="select dialable_leads,calls_today,drops_today,drops_answers_today_pct,differential_onemin,agents_average_onemin,balance_trunk_fill,answers_today,status_category_1,status_category_count_1,status_category_2,status_category_count_2,status_category_3,status_category_count_3,status_category_4,status_category_count_4,agent_calls_today,agent_wait_today,agent_custtalk_today,agent_acw_today,agent_pause_today from vicidial_campaign_stats where campaign_id='" . mysqli_real_escape_string($link, $group) . "';";
 $rslt=mysql_to_mysqli($stmt, $link);
 $row=mysqli_fetch_row($rslt);
 $DAleads =			$row[0];
 $callsTODAY =		$row[1];
 $dropsTODAY =		$row[2];
 $drpctTODAY =		$row[3];
 $diffONEMIN =		$row[4];
 $agentsONEMIN =		$row[5];
 $balanceFILL =		$row[6];
 $answersTODAY =		$row[7];
 $VSCcat1 =			$row[8];
 $VSCcat1tally =		$row[9];
 $VSCcat2 =			$row[10];
 $VSCcat2tally =		$row[11];
 $VSCcat3 =			$row[12];
 $VSCcat3tally =		$row[13];
 $VSCcat4 =			$row[14];
 $VSCcat4tally =		$row[15];
 $VSCagentcalls =	$row[16];
 $VSCagentwait =		$row[17];
 $VSCagentcust =		$row[18];
 $VSCagentacw =		$row[19];
 $VSCagentpause =	$row[20];

 $diffpctONEMIN = ( MathZDC($diffONEMIN, $agentsONEMIN) * 100);
 $diffpctONEMIN = sprintf("%01.2f", $diffpctONEMIN);

 $stmt="select sum(local_trunk_shortage) from vicidial_campaign_server_stats where campaign_id='" . mysqli_real_escape_string($link, $group) . "';";
 $rslt=mysql_to_mysqli($stmt, $link);
 $row=mysqli_fetch_row($rslt);
 $balanceSHORT = $row[0];

 $MAIN.="<div class='campaign-content'>";
 $MAIN.="<div class='stats-grid'>";
 $MAIN.="<div class='stat-item'>";
 $MAIN.="<div class='stat-label'>"._QXZ("DIAL LEVEL")."</div>";
 $MAIN.="<div class='stat-value'>$DIALlev</div>";
 $MAIN.="</div>";
 $MAIN.="<div class='stat-item'>";
 $MAIN.="<div class='stat-label'>"._QXZ("TRUNK SHORT/FILL")."</div>";
 $MAIN.="<div class='stat-value'>$balanceSHORT / $balanceFILL</div>";
 $MAIN.="</div>";
 $MAIN.="<div class='stat-item'>";
 $MAIN.="<div class='stat-label'>"._QXZ("FILTER")."</div>";
 $MAIN.="<div class='stat-value'>$DIALfilter</div>";
 $MAIN.="</div>";
 $MAIN.="<div class='stat-item'>";
 $MAIN.="<div class='stat-label'>"._QXZ("TIME")."</div>";
 $MAIN.="<div class='stat-value'>$NOW_TIME</div>";
 $MAIN.="</div>";
 $MAIN.="</div>";

 $CSV_text.="\""._QXZ("DIAL LEVEL").":\",\"$DIALlev\",\""._QXZ("TRUNK SHORT/FILL").":\",\"$balanceSHORT / $balanceFILL\",\""._QXZ("FILTER").":\",\"$DIALfilter\",\""._QXZ("TIME").":\",\"$NOW_TIME\"\n";

if ($adastats>1)
    {
    $MAIN.="<div class='stats-grid'>";
    $MAIN.="<div class='stat-item'>";
    $MAIN.="<div class='stat-label'>"._QXZ("MAX LEVEL")."</div>";
    $MAIN.="<div class='stat-value'>$maxDIALlev</div>";
    $MAIN.="</div>";
    $MAIN.="<div class='stat-item'>";
    $MAIN.="<div class='stat-label'>"._QXZ("DROPPED MAX")."</div>";
    $MAIN.="<div class='stat-value'>$DROPmax%</div>";
    $MAIN.="</div>";
    $MAIN.="<div class='stat-item'>";
    $MAIN.="<div class='stat-label'>"._QXZ("TARGET DIFF")."</div>";
    $MAIN.="<div class='stat-value'>$targetDIFF</div>";
    $MAIN.="</div>";
    $MAIN.="<div class='stat-item'>";
    $MAIN.="<div class='stat-label'>"._QXZ("INTENSITY")."</div>";
    $MAIN.="<div class='stat-value'>$ADAintense</div>";
    $MAIN.="</div>";
    $MAIN.="</div>";
    
    $MAIN.="<div class='stats-grid'>";
    $MAIN.="<div class='stat-item'>";
    $MAIN.="<div class='stat-label'>"._QXZ("DIAL TIMEOUT")."</div>";
    $MAIN.="<div class='stat-value'>$DIALtimeout</div>";
    $MAIN.="</div>";
    $MAIN.="<div class='stat-item'>";
    $MAIN.="<div class='stat-label'>"._QXZ("TAPER TIME")."</div>";
    $MAIN.="<div class='stat-value'>$TAPERtime</div>";
    $MAIN.="</div>";
    $MAIN.="<div class='stat-item'>";
    $MAIN.="<div class='stat-label'>"._QXZ("LOCAL TIME")."</div>";
    $MAIN.="<div class='stat-value'>$CALLtime</div>";
    $MAIN.="</div>";
    $MAIN.="<div class='stat-item'>";
    $MAIN.="<div class='stat-label'>"._QXZ("AVAIL ONLY")."</div>";
    $MAIN.="<div class='stat-value'>$ADAavailonly</div>";
    $MAIN.="</div>";
    $MAIN.="</div>";
    
    $CSV_text.="\""._QXZ("MAX LEVEL").":\",\"$maxDIALlev\",\""._QXZ("DROPPED MAX").":\",\"$DROPmax\",\""._QXZ("TARGET DIFF").":\",\"$targetDIFF\",\""._QXZ("INTENSITY").":\",\"$ADAintense\"\n";
    $CSV_text.="\""._QXZ("DIAL TIMEOUT").":\",\"$DIALtimeout\",\""._QXZ("TAPER TIME").":\",\"$TAPERtime\",\""._QXZ("LOCAL TIME").":\",\"$CALLtime\",\""._QXZ("AVAIL ONLY").":\",\"$ADAavailonly\"\n";
    }

 $MAIN.="<div class='stats-grid'>";
 $MAIN.="<div class='stat-item'>";
 $MAIN.="<div class='stat-label'>"._QXZ("DIALABLE LEADS")."</div>";
 $MAIN.="<div class='stat-value'>$DAleads</div>";
 $MAIN.="</div>";
 $MAIN.="<div class='stat-item'>";
 $MAIN.="<div class='stat-label'>"._QXZ("CALLS TODAY")."</div>";
 $MAIN.="<div class='stat-value'>$callsTODAY</div>";
 $MAIN.="</div>";
 $MAIN.="<div class='stat-item'>";
 $MAIN.="<div class='stat-label'>"._QXZ("AVG AGENTS")."</div>";
 $MAIN.="<div class='stat-value'>$agentsONEMIN</div>";
 $MAIN.="</div>";
 $MAIN.="<div class='stat-item'>";
 $MAIN.="<div class='stat-label'>"._QXZ("DIAL METHOD")."</div>";
 $MAIN.="<div class='stat-value'>$DIALmethod</div>";
 $MAIN.="</div>";
 $MAIN.="</div>";
 $CSV_text.="\""._QXZ("DIALABLE LEADS").":\",\"$DAleads\",\""._QXZ("CALLS TODAY").":\",\"$callsTODAY\",\""._QXZ("AVG AGENTS").":\",\"$agentsONEMIN\",\""._QXZ("DIAL METHOD").":\",\"$DIALmethod\"\n";

 $MAIN.="<div class='stats-grid'>";
 $MAIN.="<div class='stat-item'>";
 $MAIN.="<div class='stat-label'>"._QXZ("HOPPER LEVEL")."</div>";
 $MAIN.="<div class='stat-value'>$HOPlev</div>";
 $MAIN.="</div>";
 $MAIN.="<div class='stat-item'>";
 $MAIN.="<div class='stat-label'>"._QXZ("DROPPED / ANSWERED")."</div>";
 $MAIN.="<div class='stat-value'>$dropsTODAY / $answersTODAY</div>";
 $MAIN.="</div>";
 $MAIN.="<div class='stat-item'>";
 $MAIN.="<div class='stat-label'>"._QXZ("DL DIFF")."</div>";
 $MAIN.="<div class='stat-value'>$diffONEMIN</div>";
 $MAIN.="</div>";
 $MAIN.="<div class='stat-item'>";
 $MAIN.="<div class='stat-label'>"._QXZ("STATUSES")."</div>";
 $MAIN.="<div class='stat-value'>$DIALstatuses</div>";
 $MAIN.="</div>";
 $MAIN.="</div>";
 $CSV_text.="\""._QXZ("HOPPER LEVEL").":\",\"$HOPlev\",\""._QXZ("DROPPED / ANSWERED").":\",\"$dropsTODAY / $answersTODAY\",\""._QXZ("DL DIFF").":\",\"$diffONEMIN\",\""._QXZ("STATUSES").":\",\"$DIALstatuses\"\n";

 $MAIN.="<div class='stats-grid'>";
 $MAIN.="<div class='stat-item'>";
 $MAIN.="<div class='stat-label'>"._QXZ("LEADS IN HOPPER")."</div>";
 $MAIN.="<div class='stat-value'>$VDhop</div>";
 $MAIN.="</div>";
 $dropClass = ($drpctTODAY >= $DROPmax) ? "danger" : "";
 $MAIN.="<div class='stat-item $dropClass'>";
 $MAIN.="<div class='stat-label'>"._QXZ("DROPPED PERCENT")."</div>";
 $MAIN.="<div class='stat-value'>$drpctTODAY%</div>";
 $MAIN.="</div>";
 $MAIN.="<div class='stat-item'>";
 $MAIN.="<div class='stat-label'>"._QXZ("DIFF")."</div>";
 $MAIN.="<div class='stat-value'>$diffpctONEMIN%</div>";
 $MAIN.="</div>";
 $MAIN.="<div class='stat-item'>";
 $MAIN.="<div class='stat-label'>"._QXZ("ORDER")."</div>";
 $MAIN.="<div class='stat-value'>$DIALorder</div>";
 $MAIN.="</div>";
 $MAIN.="</div>";
 $CSV_text.="\""._QXZ("LEADS IN HOPPER").":\",\"$VDhop\",\""._QXZ("DROPPED PERCENT").":\",\"$drpctTODAY%\",\""._QXZ("DIFF").":\",\"$diffpctONEMIN%\",\""._QXZ("ORDER").":\",\"$DIALorder\"\n";

 $categoryStats = "";
if ( (!preg_match('/NULL/i',$VSCcat1)) and (strlen($VSCcat1)>0) )
    {$categoryStats .= "<div class='category-stat'><strong>$VSCcat1:</strong> $VSCcat1tally</div>\n";}
if ( (!preg_match('/NULL/i',$VSCcat2)) and (strlen($VSCcat2)>0) )
    {$categoryStats .= "<div class='category-stat'><strong>$VSCcat2:</strong> $VSCcat2tally</div>\n";}
if ( (!preg_match('/NULL/i',$VSCcat3)) and (strlen($VSCcat3)>0) )
    {$categoryStats .= "<div class='category-stat'><strong>$VSCcat3:</strong> $VSCcat3tally</div>\n";}
if ( (!preg_match('/NULL/i',$VSCcat4)) and (strlen($VSCcat4)>0) )
    {$categoryStats .= "<div class='category-stat'><strong>$VSCcat4:</strong> $VSCcat4tally</div>\n";}

if (!empty($categoryStats)) {
    $MAIN.="<div class='category-stats'>$categoryStats</div>";
}
 $CSV_text.="\"$VSCcat1:\",\"$VSCcat1tally\",\"$VSCcat2:\",\"$VSCcat2tally\",\"$VSCcat3:\",\"$VSCcat3tally\",\"$VSCcat4:\",\"$VSCcat4tally\"\n";

if ($VSCagentcalls > 0)
    {
    $avgpauseTODAY = MathZDC($VSCagentpause, $VSCagentcalls);
    $avgpauseTODAY = round($avgpauseTODAY, 0);
    $avgpauseTODAY = sprintf("%01.0f", $avgpauseTODAY);

    $avgwaitTODAY = MathZDC($VSCagentwait, $VSCagentcalls);
    $avgwaitTODAY = round($avgwaitTODAY, 0);
    $avgwaitTODAY = sprintf("%01.0f", $avgwaitTODAY);

    $avgcustTODAY = MathZDC($VSCagentcust, $VSCagentcalls);
    $avgcustTODAY = round($avgcustTODAY, 0);
    $avgcustTODAY = sprintf("%01.0f", $avgcustTODAY);

    $avgacwTODAY = MathZDC($VSCagentacw, $VSCagentcalls);
    $avgacwTODAY = round($avgacwTODAY, 0);
    $avgacwTODAY = sprintf("%01.0f", $avgacwTODAY);

    $MAIN.="<div class='time-stats'>";
    $MAIN.="<div class='time-stat'><strong>"._QXZ("AGENT AVG WAIT").":</strong> $avgwaitTODAY</div>";
    $MAIN.="<div class='time-stat'><strong>"._QXZ("AVG CUSTTIME").":</strong> $avgcustTODAY</div>";
    $MAIN.="<div class='time-stat'><strong>"._QXZ("AVG ACW").":</strong> $avgacwTODAY</div>";
    $MAIN.="<div class='time-stat'><strong>"._QXZ("AVG PAUSE").":</strong> $avgpauseTODAY</div>";
    $MAIN.="</div>";
    $CSV_text.="\""._QXZ("AGENT AVG WAIT").":\",\"$avgwaitTODAY\",\""._QXZ("AVG CUSTTIME").":\",\"$avgcustTODAY\",\""._QXZ("AVG ACW").":\",\"$avgacwTODAY\",\""._QXZ("AVG PAUSE").":\",\"$avgpauseTODAY\"\n";
    }

### Header finish





################################################################################
### START calculating calls/agents
################################################################################

################################################################################
###### OUTBOUND CALLS
################################################################################
if ($campaign_allow_inbound > 0)
    {
    $stmt="select closer_campaigns from vicidial_campaigns where campaign_id='" . mysqli_real_escape_string($link, $group) . "';";
    $rslt=mysql_to_mysqli($stmt, $link);
    $row=mysqli_fetch_row($rslt);
    $closer_campaigns = preg_replace("/^ | -$/","",$row[0]);
    $closer_campaigns = preg_replace("/ /","','",$closer_campaigns);
    $closer_campaigns = "'$closer_campaigns'";

    $stmt="select status from vicidial_auto_calls where status NOT IN('XFER') and ( (call_type='IN' and campaign_id IN($closer_campaigns)) or (campaign_id='" . mysqli_real_escape_string($link, $group) . "' and call_type='OUT') );";
    }
else
    {
    if ($group=='XXXX-ALL-ACTIVE-XXXX') {$groupSQL = '';}
    else {$groupSQL = " and campaign_id='" . mysqli_real_escape_string($link, $group) . "'";}

    $stmt="select status from vicidial_auto_calls where status NOT IN('XFER') $groupSQL;";
    }
 $rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
 $parked_to_print = mysqli_num_rows($rslt);
    if ($parked_to_print > 0)
    {
    $i=0;
    $out_total=0;
    $out_ring=0;
    $out_live=0;
    $in_ivr=0;
    while ($i < $parked_to_print)
        {
        $row=mysqli_fetch_row($rslt);

        if (preg_match("/LIVE/i",$row[0])) 
            {$out_live++;}
        else
            {
            if (preg_match("/IVR/i",$row[0])) 
                {$in_ivr++;}
            if (preg_match("/CLOSER/i",$row[0])) 
                {$nothing=1;}
            else 
                {$out_ring++;}
            }
        $out_total++;
        $i++;
        }

        if ($out_live > 0) {$F='<FONT class="r1">'; $FG='</FONT>';}
        if ($out_live > 4) {$F='<FONT class="r2">'; $FG='</FONT>';}
        if ($out_live > 9) {$F='<FONT class="r3">'; $FG='</FONT>';}
        if ($out_live > 14) {$F='<FONT class="r4">'; $FG='</FONT>';}

        $callClass = ($out_live > 9) ? "danger" : (($out_live > 4) ? "warning" : "success");
        
        if ($campaign_allow_inbound > 0)
            {
            $MAIN.="<div class='call-stats'>";
            $MAIN.="<div class='call-stat'><strong>"._QXZ("current active calls").":</strong> $out_total</div>";
            $MAIN.="<div class='call-stat'><strong>"._QXZ("calls ringing").":</strong> $out_ring</div>";
            $MAIN.="<div class='call-stat $callClass'><strong>"._QXZ("calls waiting for agents").":</strong> $out_live</div>";
            $MAIN.="<div class='call-stat'><strong>"._QXZ("calls in IVR").":</strong> $in_ivr</div>";
            $MAIN.="</div>";
            $CSV_text.="\"$out_total "._QXZ("current active calls")."\",\"\"\n";
            }
        else
            {
            $MAIN.="<div class='call-stats'>";
            $MAIN.="<div class='call-stat'><strong>"._QXZ("calls being placed").":</strong> $out_total</div>";
            $MAIN.="<div class='call-stat'><strong>"._QXZ("calls ringing").":</strong> $out_ring</div>";
            $MAIN.="<div class='call-stat $callClass'><strong>"._QXZ("calls waiting for agents").":</strong> $out_live</div>";
            $MAIN.="<div class='call-stat'><strong>"._QXZ("calls in IVR").":</strong> $in_ivr</div>";
            $MAIN.="</div>";
            $CSV_text.="\"$NFB$out_total$NFE "._QXZ("calls being placed")."\",\"\"\n";
            }
        
        $CSV_text.="\"$out_ring "._QXZ("calls ringing")."\",\"$out_live "._QXZ("calls waiting for agents")."\",\"$in_ivr "._QXZ("calls in IVR")."\"\n";
        }
    else
    {
    $MAIN.="<div class='call-stats'>";
    $MAIN.="<div class='call-stat'>"._QXZ("NO LIVE CALLS WAITING")."</div>";
    $MAIN.="</div>";
    $CSV_text.="\""._QXZ("NO LIVE CALLS WAITING")."\"\n";
    }


###################################################################################
###### TIME ON SYSTEM
###################################################################################

 $agent_incall=0;
 $agent_ready=0;
 $agent_paused=0;
 $agent_total=0;

 $stmt="select extension,user,conf_exten,status,server_ip,UNIX_TIMESTAMP(last_call_time),UNIX_TIMESTAMP(last_call_finish),call_server_ip,campaign_id from vicidial_live_agents where campaign_id='" . mysqli_real_escape_string($link, $group) . "';";
 $rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {$MAIN.="$stmt\n";}
 $talking_to_print = mysqli_num_rows($rslt);
    if ($talking_to_print > 0)
    {
    $i=0;
    $agentcount=0;
    while ($i < $talking_to_print)
        {
        $row=mysqli_fetch_row($rslt);
            if (preg_match("/READY|PAUSED/i",$row[3]))
            {
            $row[5]=$row[6];
            }
        $Lstatus =			$row[3];
        $status =			sprintf("%-6s", $row[3]);
        if (!preg_match("/INCALL|QUEUE/i",$row[3]))
            {$call_time_S = ($STARTtime - $row[6]);}
        else
            {$call_time_S = ($STARTtime - $row[5]);}

        $call_time_M = MathZDC($call_time_S, 60);
        $call_time_M = round($call_time_M, 2);
        $call_time_M_int = intval("$call_time_M");
        $call_time_SEC = ($call_time_M - $call_time_M_int);
        $call_time_SEC = ($call_time_SEC * 60);
        $call_time_SEC = round($call_time_SEC, 0);
        if ($call_time_SEC < 10) {$call_time_SEC = "0$call_time_SEC";}
        $call_time_MS = "$call_time_M_int:$call_time_SEC";
        $call_time_MS =		sprintf("%7s", $call_time_MS);
        $G = '';		$EG = '';
        if (preg_match("/PAUSED/i",$row[3])) 
            {
            if ($call_time_M_int >= 30) 
                {$i++; continue;} 
            else
                {
                $agent_paused++;  $agent_total++;
                }
            }

        if ( (preg_match("/INCALL/i",$status)) or (preg_match("/QUEUE/i",$status)) ) {$agent_incall++;  $agent_total++;}
        if ( (preg_match("/READY/i",$status)) or (preg_match("/CLOSER/i",$status)) ) {$agent_ready++;  $agent_total++;}
        $agentcount++;


        $i++;
        }

        if ($agent_ready > 0) {$B='<FONT class="b1">'; $BG='</FONT>';}
        if ($agent_ready > 4) {$B='<FONT class="b2">'; $BG='</FONT>';}
        if ($agent_ready > 9) {$B='<FONT class="b3">'; $BG='</FONT>';}
        if ($agent_ready > 14) {$B='<FONT class="b4">'; $BG='</FONT>';}

        $readyClass = ($agent_ready > 9) ? "success" : (($agent_ready > 4) ? "warning" : "");

        $MAIN.="<div class='agent-stats'>";
        $MAIN.="<div class='agent-stat'><strong>"._QXZ("agents logged in").":</strong> $agent_total</div>";
        $MAIN.="<div class='agent-stat'><strong>"._QXZ("agents in calls").":</strong> $agent_incall</div>";
        $MAIN.="<div class='agent-stat $readyClass'><strong>"._QXZ("agents waiting").":</strong> $agent_ready</div>";
        $MAIN.="<div class='agent-stat'><strong>"._QXZ("paused agents").":</strong> $agent_paused</div>";
        $MAIN.="</div>";
        $CSV_text.="\"$agent_total "._QXZ("agents logged in")."\",\"$agent_incall "._QXZ("agents in calls")."\",\"$agent_ready "._QXZ("agents waiting")."\",\"$agent_paused "._QXZ("paused agents")."\"\n\n";
    }
    else
    {
    $MAIN.="<div class='agent-stats'>";
    $MAIN.="<div class='agent-stat'>"._QXZ("NO AGENTS ON CALLS")."</div>";
    $MAIN.="</div>";
    $CSV_text.="\""._QXZ("NO AGENTS ON CALLS")."\"\n\n";
    }

################################################################################
### END calculating calls/agents
################################################################################

 $MAIN.="</div>"; // End campaign-content
 $MAIN.="</div>"; // End campaign-card

 $k++;
}

 $MAIN.="<div class='footer-info'>";
 $MAIN.="$db_source";
 $MAIN.="</div>";

 $MAIN.="</div>"; // End dashboard-container

    if ($file_download>0) {
        $FILE_TIME = date("Ymd-His");
        $CSVfilename = "AST_timeonVDADallSUMMARY_$US$FILE_TIME.csv";
        $CSV_text=preg_replace('/\n +,/', ',', $CSV_text);
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

        exit;
    } else {
        header ("Content-type: text/html; charset=utf-8");

        echo $HEADER;
        //require("admin_header.php");
        echo $MAIN;
    }

?>