<?php
# admin_search_lead.php   version 2.14
#
# 240704-2358 - Added cold-storage archive logs search option
# 240705-0849 - Added archive_type option
#

require("dbconnect_mysqli.php");
require("functions.php");

 $PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
 $PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
 $PHP_SELF=$_SERVER['PHP_SELF'];
 $PHP_SELF = preg_replace('/\.php.*/i','.php',$PHP_SELF);
if (isset($_GET["vendor_id"]))			{$vendor_id=$_GET["vendor_id"];}
    elseif (isset($_POST["vendor_id"]))	{$vendor_id=$_POST["vendor_id"];}
if (isset($_GET["first_name"]))				{$first_name=$_GET["first_name"];}
    elseif (isset($_POST["first_name"]))	{$first_name=$_POST["first_name"];}
if (isset($_GET["last_name"]))			{$last_name=$_GET["last_name"];}
    elseif (isset($_POST["last_name"]))	{$last_name=$_POST["last_name"];}
if (isset($_GET["email"]))			{$email=$_GET["email"];}
    elseif (isset($_POST["email"]))	{$email=$_POST["email"];}
if (isset($_GET["phone"]))				{$phone=$_GET["phone"];}
    elseif (isset($_POST["phone"]))		{$phone=$_POST["phone"];}
if (isset($_GET["lead_id"]))			{$lead_id=$_GET["lead_id"];}
    elseif (isset($_POST["lead_id"]))	{$lead_id=$_POST["lead_id"];}
if (isset($_GET["log_phone"]))				{$log_phone=$_GET["log_phone"];}
    elseif (isset($_POST["log_phone"]))		{$log_phone=$_POST["log_phone"];}
if (isset($_GET["log_lead_id"]))			{$log_lead_id=$_GET["log_lead_id"];}
    elseif (isset($_POST["log_lead_id"]))   {$log_lead_id=$_POST["log_lead_id"];}
if (isset($_GET["log_phone_archive"]))			{$log_phone_archive=$_GET["log_phone_archive"];}
    elseif (isset($_POST["log_phone_archive"]))	{$log_phone_archive=$_POST["log_phone_archive"];}
if (isset($_GET["log_lead_id_archive"]))			{$log_lead_id_archive=$_GET["log_lead_id_archive"];}
    elseif (isset($_POST["log_lead_id_archive"]))   {$log_lead_id_archive=$_POST["log_lead_id_archive"];}
if (isset($_GET["submit"]))			     {$submit=$_GET["submit"];}
    elseif (isset($_POST["submit"]))	{$submit=$_POST["submit"];}
if (isset($_GET["SUBMIT"]))				{$SUBMIT=$_GET["SUBMIT"];}
    elseif (isset($_POST["SUBMIT"]))	{$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["DB"]))					{$DB=$_GET["DB"];}
    elseif (isset($_POST["DB"]))		{$DB=$_POST["DB"];}
if (isset($_GET["status"]))				{$status=$_GET["status"];}
    elseif (isset($_POST["status"]))	{$status=$_POST["status"];}
if (isset($_GET["user"]))				{$user=$_GET["user"];}
    elseif (isset($_POST["user"]))		{$user=$_POST["user"];}
if (isset($_GET["owner"]))				{$owner=$_GET["owner"];}
    elseif (isset($_POST["owner"]))		{$owner=$_POST["owner"];}
if (isset($_GET["list_id"]))			{$list_id=$_GET["list_id"];}
    elseif (isset($_POST["list_id"]))	{$list_id=$_POST["list_id"];}
if (isset($_GET["alt_phone_search"]))			{$alt_phone_search=$_GET["alt_phone_search"];}
    elseif (isset($_POST["alt_phone_search"]))	{$alt_phone_search=$_POST["alt_phone_search"];}
if (isset($_GET["archive_search"]))			{$archive_search=$_GET["archive_search"];}
    elseif (isset($_POST["archive_search"]))	{$archive_search=$_POST["archive_search"];}
if (isset($_GET["called_count"]))			{$called_count=$_GET["called_count"];}
    elseif (isset($_POST["called_count"]))	{$called_count=$_POST["called_count"];}
if (isset($_GET["archive_type"]))			{$archive_type=$_GET["archive_type"];}
    elseif (isset($_POST["archive_type"]))	{$archive_type=$_POST["archive_type"];}

 $report_name = 'Search Leads Logs';

 $DB=preg_replace("/[^0-9a-zA-Z]/","",$DB);

 $vicidial_list_fields = 'lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner';

if (strlen($alt_phone_search) < 2) {$alt_phone_search='No';}

 $STARTtime = date("U");
 $TODAY = date("Y-m-d");
 $NOW_TIME = date("Y-m-d H:i:s");
 $date = date("r");
 $ip = getenv("REMOTE_ADDR");
 $browser = getenv("HTTP_USER_AGENT");
 $log_archive_link=0;

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
 $stmt = "SELECT use_non_latin,webroot_writable,outbound_autodial_active,user_territories_active,slave_db_server,reports_use_slave_db,enable_languages,language_method,qc_features_active,allow_web_debug,coldstorage_server_ip,coldstorage_dbname,coldstorage_login,coldstorage_pass,coldstorage_port FROM system_settings;";
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
    $slave_db_server =				$row[4];
    $reports_use_slave_db =			$row[5];
    $SSenable_languages =			$row[6];
    $SSlanguage_method =			$row[7];
    $SSqc_features_active =			$row[8];
    $SSallow_web_debug =			$row[9];
    $SScoldstorage_server_ip =		$row[10];
    $SScoldstorage_dbname =			$row[11];
    $SScoldstorage_login =			$row[12];
    $SScoldstorage_pass =			$row[13];
    $SScoldstorage_port =			$row[14];
    }
if ($SSallow_web_debug < 1) {$DB=0;}
##### END SETTINGS LOOKUP #####
###########################################

if ($archive_search=="Yes") {$vl_table="vicidial_list_archive";} 
else {$vl_table="vicidial_list"; $archive_search="No";}

 $phone = preg_replace('/[^0-9]/','',$phone);
 $log_phone = preg_replace('/[^-_0-9a-zA-Z]/', '', $log_phone);
 $log_phone_archive = preg_replace('/[^-_0-9a-zA-Z]/', '', $log_phone_archive);
 $list_id = preg_replace('/[^0-9]/','',$list_id);
 $lead_id = preg_replace('/[^0-9]/','',$lead_id);
 $log_lead_id = preg_replace('/[^0-9]/','',$log_lead_id);
 $log_lead_id_archive = preg_replace('/[^0-9]/','',$log_lead_id_archive);
 $submit = preg_replace('/[^-_0-9a-zA-Z]/', '', $submit);
 $SUBMIT = preg_replace('/[^-_0-9a-zA-Z]/', '', $SUBMIT);
 $vendor_id = preg_replace("/\<|\>|\'|\"|\\\\|;/","",$vendor_id);
 $first_name = preg_replace("/\<|\>|\'|\"|\\\\|;/","",$first_name);
 $last_name = preg_replace("/\<|\>|\'|\"|\\\\|;/","",$last_name);
 $email = preg_replace("/\<|\>|\'|\"|\\\\|;/","",$email);
 $user = preg_replace("/\<|\>|\'|\"|\\\\|;/","",$user);
 $owner = preg_replace("/\<|\>|\'|\"|\\\\|;/","",$owner);
 $alt_phone_search = preg_replace('/[^-_0-9a-zA-Z]/', '', $alt_phone_search);
 $archive_search = preg_replace('/[^-_0-9a-zA-Z]/', '', $archive_search);
 $called_count = preg_replace('/[^0-9]/','',$called_count);
 $archive_type = preg_replace('/[^-_0-9a-zA-Z]/', '', $archive_type);

if ($non_latin < 1)
    {
    $PHP_AUTH_USER = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_USER);
    $PHP_AUTH_PW = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_PW);
    $status = preg_replace('/[^-_0-9a-zA-Z]/', '', $status);
    }
else
    {
    $PHP_AUTH_USER = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_USER);
    $PHP_AUTH_PW = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_PW);
    $status = preg_replace('/[^-_0-9\p{L}]/u', '', $status);
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

if ($auth < 1)
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

 $rights_stmt = "SELECT modify_leads from vicidial_users where user='$PHP_AUTH_USER';";
if ($DB) {echo "|$stmt|\n";}
 $rights_rslt=mysql_to_mysqli($rights_stmt, $link);
 $rights_row=mysqli_fetch_row($rights_rslt);
 $modify_leads =		$rights_row[0];

# check their permissions
if ( $modify_leads < 1 )
    {
    header ("Content-type: text/html; charset=utf-8");
    echo _QXZ("You do not have permissions to search leads")."\n";
    exit;
    }

 $stmt="SELECT full_name,modify_leads,admin_hide_lead_data,admin_hide_phone_data,user_group,ignore_group_on_search,qc_enabled from vicidial_users where user='$PHP_AUTH_USER';";
 $rslt=mysql_to_mysqli($stmt, $link);
 $row=mysqli_fetch_row($rslt);
 $LOGfullname =					$row[0];
 $LOGmodify_leads =				$row[1];
 $LOGadmin_hide_lead_data =		$row[2];
 $LOGadmin_hide_phone_data =		$row[3];
 $LOGuser_group =				$row[4];
 $LOGignore_group_on_search =	$row[5];
 $qc_auth =						$row[6];


 $stmt="SELECT allowed_campaigns,allowed_reports,admin_viewable_groups,admin_viewable_call_times from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) {echo "|$stmt|\n";}
 $rslt=mysql_to_mysqli($stmt, $link);
 $row=mysqli_fetch_row($rslt);
 $LOGallowed_campaigns =			$row[0];
 $LOGallowed_reports =			$row[1];
 $LOGadmin_viewable_groups =		$row[2];
 $LOGadmin_viewable_call_times =	$row[3];

 $camp_lists='';
 $LOGallowed_campaignsSQL='';
 $whereLOGallowed_campaignsSQL='';
 $LOGallowed_listsSQL='';
 $whereLOGallowed_listsSQL='';
if ( (!preg_match('/\-ALL/i', $LOGallowed_campaigns)) and ($LOGignore_group_on_search != '1') )
    {
    $rawLOGallowed_campaignsSQL = preg_replace("/ -/",'',$LOGallowed_campaigns);
    $rawLOGallowed_campaignsSQL = preg_replace("/ /","','",$rawLOGallowed_campaignsSQL);
    $LOGallowed_campaignsSQL = "and campaign_id IN('$rawLOGallowed_campaignsSQL')";
    $whereLOGallowed_campaignsSQL = "where campaign_id IN('$rawLOGallowed_campaignsSQL')";

    $stmt="SELECT list_id from vicidial_lists $whereLOGallowed_campaignsSQL;";
    $rslt=mysql_to_mysqli($stmt, $link);
    $lists_to_print = mysqli_num_rows($rslt);
    $o=0;
    while ($lists_to_print > $o) 
        {
        $rowx=mysqli_fetch_row($rslt);
        $camp_lists .= "'$rowx[0]',";
        $o++;
        }
    $camp_lists = preg_replace('/.$/i','',$camp_lists);;
    if (strlen($camp_lists)<2) {$camp_lists="''";}
    $LOGallowed_listsSQL = "and list_id IN($camp_lists)";
    $whereLOGallowed_listsSQL = "where list_id IN($camp_lists)";
    }
 $regexLOGallowed_campaigns = " $LOGallowed_campaigns ";


?>
<html>
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<title><?php echo _QXZ("ADMINISTRATION: Lead Search"); ?></title>
<style>
    :root {
        --primary-color: #4a6fa5;
        --secondary-color: #6c757d;
        --light-color: #f8f9fa;
        --dark-color: #343a40;
        --success-color: #28a745;
        --info-color: #17a2b8;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --border-color: #dee2e6;
        --font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }
    
    body {
        font-family: var(--font-family);
        background-color: var(--light-color);
        color: var(--dark-color);
        line-height: 1.6;
    }
    
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .header {
        background-color: var(--primary-color);
        color: white;
        padding: 15px 20px;
        border-radius: 5px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .header h1 {
        font-size: 1.8rem;
        font-weight: 600;
    }
    
    .header .date-time {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 25px;
        overflow: hidden;
    }
    
    .card-header {
        background-color: var(--primary-color);
        color: white;
        padding: 12px 20px;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -5px;
        margin-left: -5px;
    }
    
    .form-col {
        flex: 1;
        padding-right: 5px;
        padding-left: 5px;
        min-width: 250px;
    }
    
    label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }
    
    input[type="text"],
    select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        font-family: var(--font-family);
        font-size: 0.95rem;
    }
    
    input[type="text"]:focus,
    select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(74, 111, 165, 0.25);
    }
    
    .btn {
        display: inline-block;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        user-select: none;
        border: 1px solid transparent;
        padding: 8px 16px;
        font-size: 0.95rem;
        line-height: 1.5;
        border-radius: 4px;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out;
        cursor: pointer;
    }
    
    .btn-primary {
        color: white;
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
        background-color: #3a5a8a;
        border-color: #3a5a8a;
    }
    
    .btn-block {
        display: block;
        width: 100%;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
        background-color: transparent;
    }
    
    th, td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid var(--border-color);
        text-align: left;
    }
    
    thead th {
        vertical-align: bottom;
        border-bottom: 2px solid var(--border-color);
        background-color: var(--primary-color);
        color: white;
        font-weight: 600;
    }
    
    tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }
    
    .text-center {
        text-align: center;
    }
    
    .alert {
        position: relative;
        padding: 0.75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 4px;
    }
    
    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }
    
    .alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    }
    
    .footer {
        margin-top: 30px;
        padding: 15px 0;
        text-align: center;
        font-size: 0.9rem;
        color: var(--secondary-color);
    }
    
    .search-results {
        margin-top: 20px;
    }
    
    .result-count {
        font-weight: 600;
        margin-bottom: 15px;
    }
    
    .new-search-btn {
        display: inline-block;
        margin-top: 20px;
        text-decoration: none;
        color: var(--primary-color);
        font-weight: 500;
    }
    
    .new-search-btn:hover {
        text-decoration: underline;
    }
    
    .runtime-info {
        margin-top: 10px;
        font-size: 0.9rem;
        color: var(--secondary-color);
    }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1><?php echo _QXZ("ADMINISTRATION: Lead Search"); ?></h1>
        <div class="date-time"><?php echo date("l F j, Y G:i:s A"); ?></div>
    </div>

<?php
##### BEGIN Set variables to make header show properly #####
 $ADD =					'100';
 $hh =					'lists';
 $sh =					'search';
 $LOGast_admin_access =	'1';
 $SSoutbound_autodial_active = '1';
 $ADMIN =				'admin.php';
 $page_width='770';
 $section_width='750';
 $header_font_size='3';
 $subheader_font_size='2';
 $subcamp_font_size='2';
 $header_selected_bold='<b>';
 $header_nonselected_bold='';
 $lists_color =		'#FFFF99';
 $lists_font =		'BLACK';
 $lists_color =		'#E6E6E6';
 $subcamp_color =	'#C6C6C6';
##### END Set variables to make header show properly #####

require("admin_header.php");

 $label_title =				_QXZ("Title");
 $label_first_name =			_QXZ("First");
 $label_middle_initial =		_QXZ("MI");
 $label_last_name =			_QXZ("Last");
 $label_address1 =			_QXZ("Address1");
 $label_address2 =			_QXZ("Address2");
 $label_address3 =			_QXZ("Address3");
 $label_city =				_QXZ("City");
 $label_state =				_QXZ("State");
 $label_province =			_QXZ("Province");
 $label_postal_code =		_QXZ("Postal Code");
 $label_vendor_lead_code =	_QXZ("Vendor ID");
 $label_gender =				_QXZ("Gender");
 $label_phone_number =		_QXZ("Phone");
 $label_phone_code =			_QXZ("DialCode");
 $label_alt_phone =			_QXZ("Alt. Phone");
 $label_security_phrase =	_QXZ("Show");
 $label_email =				_QXZ("Email");
 $label_comments =			_QXZ("Comments");

### find any custom field labels
 $stmt="SELECT label_title,label_first_name,label_middle_initial,label_last_name,label_address1,label_address2,label_address3,label_city,label_state,label_province,label_postal_code,label_vendor_lead_code,label_gender,label_phone_number,label_phone_code,label_alt_phone,label_security_phrase,label_email,label_comments from system_settings;";
 $rslt=mysql_to_mysqli($stmt, $link);
 $row=mysqli_fetch_row($rslt);
if (strlen($row[0])>0)	{$label_title =				$row[0];}
if (strlen($row[1])>0)	{$label_first_name =		$row[1];}
if (strlen($row[2])>0)	{$label_middle_initial =	$row[2];}
if (strlen($row[3])>0)	{$label_last_name =			$row[3];}
if (strlen($row[4])>0)	{$label_address1 =			$row[4];}
if (strlen($row[5])>0)	{$label_address2 =			$row[5];}
if (strlen($row[6])>0)	{$label_address3 =			$row[6];}
if (strlen($row[7])>0)	{$label_city =				$row[7];}
if (strlen($row[8])>0)	{$label_state =				$row[8];}
if (strlen($row[9])>0)	{$label_province =			$row[9];}
if (strlen($row[10])>0) {$label_postal_code =		$row[10];}
if (strlen($row[11])>0) {$label_vendor_lead_code =	$row[11];}
if (strlen($row[12])>0) {$label_gender =			$row[12];}
if (strlen($row[13])>0) {$label_phone_number =		$row[13];}
if (strlen($row[14])>0) {$label_phone_code =		$row[14];}
if (strlen($row[15])>0) {$label_alt_phone =			$row[15];}
if (strlen($row[16])>0) {$label_security_phrase =	$row[16];}
if (strlen($row[17])>0) {$label_email =				$row[17];}
if (strlen($row[18])>0) {$label_comments =			$row[18];}

if ( (!$vendor_id) and (!$phone)  and (!$lead_id) and (!$log_phone)  and (!$log_lead_id) and (!$log_phone_archive)  and (!$log_lead_id_archive) and ( (strlen($status)<1) and (strlen($list_id)<1) and (strlen($user)<1) and (strlen($owner)<1) ) and ( (strlen($first_name)<1) and (strlen($last_name)<1) and (strlen($email)<1) ))
    {
    ### Lead search form
    ?>
    <div class="card">
        <div class="card-header"><?php echo _QXZ("Lead Search Options"); ?></div>
        <div class="card-body">
            <form method="post" name="search" action="<?php echo $PHP_SELF; ?>">
                <input type="hidden" name="DB" value="<?php echo $DB; ?>">
                
                <?php
                $archive_stmt="SHOW TABLES LIKE '%vicidial_list_archive%'";
                $archive_rslt=mysql_to_mysqli($archive_stmt, $link);
                if (mysqli_num_rows($archive_rslt)>0) 
                    {
                    ?>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="form-col">
                                <label for="archive_search"><?php echo _QXZ("Archive search"); ?>:</label>
                                <select id="archive_search" name="archive_search">
                                    <option value="No"><?php echo _QXZ("No"); ?></option>
                                    <option value="Yes"><?php echo _QXZ("Yes"); ?></option>
                                    <option selected value="<?php echo $archive_search; ?>"><?php echo _QXZ($archive_search); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                ?>
                
                <div class="form-group">
                    <div class="form-row">
                        <div class="form-col">
                            <label for="vendor_id"><?php echo $label_vendor_lead_code; ?> (<?php echo _QXZ("vendor lead code"); ?>):</label>
                            <input type="text" id="vendor_id" name="vendor_id" size="10" maxlength="20">
                        </div>
                        <div class="form-col">
                            <label for="phone"><?php echo $label_phone_number; ?>:</label>
                            <input type="text" id="phone" name="phone" size="14" maxlength="18">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="form-row">
                        <div class="form-col">
                            <label for="alt_phone_search"><?php echo $label_alt_phone; ?> <?php echo _QXZ("search"); ?>:</label>
                            <select id="alt_phone_search" name="alt_phone_search">
                                <option value="No"><?php echo _QXZ("No"); ?></option>
                                <option value="Yes"><?php echo _QXZ("Yes"); ?></option>
                                <option selected value="<?php echo $alt_phone_search; ?>"><?php echo _QXZ($alt_phone_search); ?></option>
                            </select>
                        </div>
                        <div class="form-col">
                            <label for="lead_id"><?php echo _QXZ("Lead ID"); ?>:</label>
                            <input type="text" id="lead_id" name="lead_id" size="10" maxlength="10">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="form-row">
                        <div class="form-col">
                            <label for="status"><?php echo _QXZ("Status"); ?>:</label>
                            <input type="text" id="status" name="status" size="7" maxlength="6">
                        </div>
                        <div class="form-col">
                            <label for="list_id"><?php echo _QXZ("List ID"); ?>:</label>
                            <input type="text" id="list_id" name="list_id" size="15" maxlength="14">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="form-row">
                        <div class="form-col">
                            <label for="user"><?php echo _QXZ("User"); ?>:</label>
                            <input type="text" id="user" name="user" size="15" maxlength="20">
                        </div>
                        <div class="form-col">
                            <label for="owner"><?php echo _QXZ("Owner"); ?>:</label>
                            <input type="text" id="owner" name="owner" size="15" maxlength="50">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="form-row">
                        <div class="form-col">
                            <label for="first_name"><?php echo $label_first_name; ?>:</label>
                            <input type="text" id="first_name" name="first_name" size="15" maxlength="30">
                        </div>
                        <div class="form-col">
                            <label for="last_name"><?php echo $label_last_name; ?>:</label>
                            <input type="text" id="last_name" name="last_name" size="15" maxlength="30">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="form-row">
                        <div class="form-col">
                            <label for="email"><?php echo $label_email; ?>:</label>
                            <input type="text" id="email" name="email" size="15" maxlength="30">
                        </div>
                        <div class="form-col">
                            <label>&nbsp;</label>
                            <button type="submit" name="SUBMIT" class="btn btn-primary btn-block"><?php echo _QXZ("SUBMIT"); ?></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header"><?php echo _QXZ("Log Search Options"); ?></div>
        <div class="card-body">
            <form method="post" name="log_search" action="<?php echo $PHP_SELF; ?>">
                <input type="hidden" name="DB" value="<?php echo $DB; ?>">
                
                <div class="form-group">
                    <div class="form-row">
                        <div class="form-col">
                            <label for="log_lead_id"><?php echo _QXZ("Lead ID"); ?>:</label>
                            <input type="text" id="log_lead_id" name="log_lead_id" size="10" maxlength="10">
                        </div>
                        <div class="form-col">
                            <label for="log_phone"><?php echo $label_phone_number; ?> <?php echo _QXZ("Dialed"); ?>:</label>
                            <input type="text" id="log_phone" name="log_phone" size="18" maxlength="18">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" name="SUBMIT" class="btn btn-primary"><?php echo _QXZ("SUBMIT"); ?></button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header"><?php echo _QXZ("Archived Log Search Options"); ?></div>
        <div class="card-body">
            <form method="post" name="archive_log_search" action="<?php echo $PHP_SELF; ?>">
                <input type="hidden" name="DB" value="<?php echo $DB; ?>">
                
                <?php
                if ( (strlen($SScoldstorage_server_ip) > 1) and (strlen($SScoldstorage_login) > 0) and (strlen($SScoldstorage_pass) > 0) )
                    {
                    if (strlen($archive_type) < 1) {$archive_type = 'ARCHIVE';}
                    ?>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="form-col">
                                <label for="archive_type"><?php echo _QXZ("Archive Type"); ?>:</label>
                                <select id="archive_type" name="archive_type">
                                    <option value="ARCHIVE"><?php echo _QXZ("Archive Only"); ?></option>
                                    <option value="COLDSTORAGE"><?php echo _QXZ("Cold-Storage Only"); ?></option>
                                    <option value="ARCHIVE_AND_COLDSTORAGE"><?php echo _QXZ("Archive and Cold-Storage"); ?></option>
                                    <option selected value="<?php echo $archive_type; ?>"><?php echo _QXZ($archive_type); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                else
                    {
                    ?>
                    <input type="hidden" name="archive_type" value="ARCHIVE">
                    <?php
                    }
                ?>
                
                <div class="form-group">
                    <div class="form-row">
                        <div class="form-col">
                            <label for="log_lead_id_archive"><?php echo _QXZ("Lead ID"); ?>:</label>
                            <input type="text" id="log_lead_id_archive" name="log_lead_id_archive" size="10" maxlength="10">
                        </div>
                        <div class="form-col">
                            <label for="log_phone_archive"><?php echo $label_phone_number; ?> <?php echo _QXZ("Dialed"); ?>:</label>
                            <input type="text" id="log_phone_archive" name="log_phone_archive" size="18" maxlength="18">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <button type="submit" name="SUBMIT" class="btn btn-primary"><?php echo _QXZ("SUBMIT"); ?></button>
                </div>
            </form>
        </div>
    </div>
    <?php
    echo "</div></body></html>\n";
    exit;
    }

else
    {
    ##### BEGIN Log search #####
    if ( (strlen($log_lead_id)>0) or (strlen($log_phone)>0) )
        {
        if (strlen($log_lead_id)>0)
            {
            $stmtA="SELECT lead_id,phone_number,campaign_id,call_date,status,user,list_id,length_in_sec,alt_dial from vicidial_log where lead_id='" . mysqli_real_escape_string($link, $log_lead_id) . "' $LOGallowed_listsSQL";
            $stmtB="SELECT lead_id,phone_number,campaign_id,call_date,status,user,list_id,length_in_sec from vicidial_closer_log where lead_id='" . mysqli_real_escape_string($link, $log_lead_id) . "' $LOGallowed_listsSQL";
            }
        if (strlen($log_phone)>0)
            {
            $stmtA="SELECT lead_id,phone_number,campaign_id,call_date,status,user,list_id,length_in_sec,alt_dial from vicidial_log where phone_number='" . mysqli_real_escape_string($link, $log_phone) . "' $LOGallowed_listsSQL";
            $stmtB="SELECT lead_id,phone_number,campaign_id,call_date,status,user,list_id,length_in_sec from vicidial_closer_log where phone_number='" . mysqli_real_escape_string($link, $log_phone) . "' $LOGallowed_listsSQL";
            $stmtC="SELECT extension,caller_id_number,did_id,call_date from vicidial_did_log where caller_id_number='" . mysqli_real_escape_string($link, $log_phone) . "'";
            }

        if ( (strlen($slave_db_server)>5) and (preg_match("/$report_name/",$reports_use_slave_db)) )
            {
            mysqli_close($link);
            $use_slave_server=1;
            $db_source = 'S';
            require("dbconnect_mysqli.php");
            echo "<!-- Using slave server $slave_db_server $db_source -->\n";
            }

        $rslt=mysql_to_mysqli("$stmtA", $link);
        $results_to_print = mysqli_num_rows($rslt);
        if ( ($results_to_print < 1) and ($results_to_printX < 1) )
            {
            echo "<div class=\"alert alert-warning text-center\">"._QXZ("There are no outbound calls matching your search criteria")."</div>\n";
            }
        else
            {
            echo "<div class=\"card search-results\">\n";
            echo "<div class=\"card-header\">"._QXZ("OUTBOUND LOG RESULTS").": $results_to_print</div>\n";
            echo "<div class=\"card-body\">\n";
            echo "<div class=\"table-container\">\n";
            echo "<table>\n";
            echo "<thead>\n";
            echo "<tr>\n";
            echo "<th>"._QXZ("#")."</th>\n";
            echo "<th>"._QXZ("LEAD ID")."</th>\n";
            echo "<th>"._QXZ("PHONE")."</th>\n";
            echo "<th>"._QXZ("CAMPAIGN")."</th>\n";
            echo "<th>"._QXZ("CALL DATE")."</th>\n";
            echo "<th>"._QXZ("STATUS")."</th>\n";
            echo "<th>"._QXZ("USER")."</th>\n";
            echo "<th>"._QXZ("LIST ID")."</th>\n";
            echo "<th>"._QXZ("LENGTH")."</th>\n";
            echo "<th>"._QXZ("DIAL")."</th>\n";
            echo "</tr>\n";
            echo "</thead>\n";
            echo "<tbody>\n";
            $o=0;
            while ($results_to_print > $o)
                {
                $row=mysqli_fetch_row($rslt);
                if ($LOGadmin_hide_phone_data != '0')
                    {
                    if ($DB > 0) {echo "HIDEPHONEDATA|$row[1]|$LOGadmin_hide_phone_data|\n";}
                    $phone_temp = $row[1];
                    if (strlen($phone_temp) > 0)
                        {
                        if ($LOGadmin_hide_phone_data == '4_DIGITS')
                            {$row[1] = str_repeat("X", (strlen($phone_temp) - 4)) . substr($phone_temp,-4,4);}
                        elseif ($LOGadmin_hide_phone_data == '3_DIGITS')
                            {$row[1] = str_repeat("X", (strlen($phone_temp) - 3)) . substr($phone_temp,-3,3);}
                        elseif ($LOGadmin_hide_phone_data == '2_DIGITS')
                            {$row[1] = str_repeat("X", (strlen($phone_temp) - 2)) . substr($phone_temp,-2,2);}
                        else
                            {$row[1] = preg_replace("/./",'X',$phone_temp);}
                        }
                    }
                $o++;
                $search_lead = $row[0];
                echo "<tr>\n";
                echo "<td>$o</td>\n";
                echo "<td><a href=\"admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search\" onclick=\"javascript:window.open('admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search', '_blank');return false;\">$row[0]</a></td>\n";
                echo "<td>$row[1]</td>\n";
                echo "<td>$row[2]</td>\n";
                echo "<td>$row[3]</td>\n";
                echo "<td>$row[4]</td>\n";
                echo "<td>$row[5]</td>\n";
                echo "<td>$row[6]</td>\n";
                echo "<td>$row[7]</td>\n";
                echo "<td>$row[8]</td>\n";
                echo "</tr>\n";
                }
            echo "</tbody>\n";
            echo "</table>\n";
            echo "</div>\n";
            echo "</div>\n";
            echo "</div>\n";
            }

        $rslt=mysql_to_mysqli("$stmtB", $link);
        $results_to_print = mysqli_num_rows($rslt);
        if ( ($results_to_print < 1) and ($results_to_printX < 1) )
            {
            echo "<div class=\"alert alert-warning text-center\">"._QXZ("There are no inbound calls matching your search criteria")."</div>\n";
            }
        else
            {
            echo "<div class=\"card search-results\">\n";
            echo "<div class=\"card-header\">INBOUND LOG RESULTS: $results_to_print</div>\n";
            echo "<div class=\"card-body\">\n";
            echo "<div class=\"table-container\">\n";
            echo "<table>\n";
            echo "<thead>\n";
            echo "<tr>\n";
            echo "<th>"._QXZ("#")."</th>\n";
            echo "<th>"._QXZ("LEAD ID")."</th>\n";
            echo "<th>"._QXZ("PHONE")."</th>\n";
            echo "<th>"._QXZ("INGROUP")."</th>\n";
            echo "<th>"._QXZ("CALL DATE")."</th>\n";
            echo "<th>"._QXZ("STATUS")."</th>\n";
            echo "<th>"._QXZ("USER")."</th>\n";
            echo "<th>"._QXZ("LIST ID")."</th>\n";
            echo "<th>"._QXZ("LENGTH")."</th>\n";
            echo "</tr>\n";
            echo "</thead>\n";
            echo "<tbody>\n";
            $o=0;
            while ($results_to_print > $o)
                {
                $row=mysqli_fetch_row($rslt);
                if ($LOGadmin_hide_phone_data != '0')
                    {
                    if ($DB > 0) {echo "HIDEPHONEDATA|$row[1]|$LOGadmin_hide_phone_data|\n";}
                    $phone_temp = $row[1];
                    if (strlen($phone_temp) > 0)
                        {
                        if ($LOGadmin_hide_phone_data == '4_DIGITS')
                            {$row[1] = str_repeat("X", (strlen($phone_temp) - 4)) . substr($phone_temp,-4,4);}
                        elseif ($LOGadmin_hide_phone_data == '3_DIGITS')
                            {$row[1] = str_repeat("X", (strlen($phone_temp) - 3)) . substr($phone_temp,-3,3);}
                        elseif ($LOGadmin_hide_phone_data == '2_DIGITS')
                            {$row[1] = str_repeat("X", (strlen($phone_temp) - 2)) . substr($phone_temp,-2,2);}
                        else
                            {$row[1] = preg_replace("/./",'X',$phone_temp);}
                        }
                    }
                $o++;
                $search_lead = $row[0];
                echo "<tr>\n";
                echo "<td>$o</td>\n";
                echo "<td><a href=\"admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search\" onclick=\"javascript:window.open('admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search', '_blank');return false;\">$row[0]</a></td>\n";
                echo "<td>$row[1]</td>\n";
                echo "<td>$row[2]</td>\n";
                echo "<td>$row[3]</td>\n";
                echo "<td>$row[4]</td>\n";
                echo "<td>$row[5]</td>\n";
                echo "<td>$row[6]</td>\n";
                echo "<td>$row[7]</td>\n";
                echo "</tr>\n";
                }
            echo "</tbody>\n";
            echo "</table>\n";
            echo "</div>\n";
            echo "</div>\n";
            echo "</div>\n";
            }

        if (strlen($stmtC) > 10)
            {
            $rslt=mysql_to_mysqli("$stmtC", $link);
            $results_to_print = mysqli_num_rows($rslt);
            if ( ($results_to_print < 1) and ($results_to_printX < 1) )
                {
                echo "<div class=\"alert alert-warning text-center\">"._QXZ("There are no inbound did calls matching your search criteria")."</div>\n";
                }
            else
                {
                echo "<div class=\"card search-results\">\n";
                echo "<div class=\"card-header\">"._QXZ("INBOUND DID LOG RESULTS").": $results_to_print</div>\n";
                echo "<div class=\"card-body\">\n";
                echo "<div class=\"table-container\">\n";
                echo "<table>\n";
                echo "<thead>\n";
                echo "<tr>\n";
                echo "<th>"._QXZ("#")."</th>\n";
                echo "<th>"._QXZ("DID")."</th>\n";
                echo "<th>"._QXZ("PHONE")."</th>\n";
                echo "<th>"._QXZ("DID ID")."</th>\n";
                echo "<th>"._QXZ("CALL DATE")."</th>\n";
                echo "</tr>\n";
                echo "</thead>\n";
                echo "<tbody>\n";
                $o=0;
                while ($results_to_print > $o)
                    {
                    $row=mysqli_fetch_row($rslt);
                    if ($LOGadmin_hide_phone_data != '0')
                        {
                        if ($DB > 0) {echo "HIDEPHONEDATA|$row[1]|$LOGadmin_hide_phone_data|\n";}
                        $phone_temp = $row[1];
                        if (strlen($phone_temp) > 0)
                            {
                            if ($LOGadmin_hide_phone_data == '4_DIGITS')
                                {$row[1] = str_repeat("X", (strlen($phone_temp) - 4)) . substr($phone_temp,-4,4);}
                            elseif ($LOGadmin_hide_phone_data == '3_DIGITS')
                                {$row[1] = str_repeat("X", (strlen($phone_temp) - 3)) . substr($phone_temp,-3,3);}
                            elseif ($LOGadmin_hide_phone_data == '2_DIGITS')
                                {$row[1] = str_repeat("X", (strlen($phone_temp) - 2)) . substr($phone_temp,-2,2);}
                            else
                                {$row[1] = preg_replace("/./",'X',$phone_temp);}
                            }
                        }
                    $o++;
                    $search_lead = $row[0];
                    echo "<tr>\n";
                    echo "<td>$o</td>\n";
                    echo "<td>$row[0]</td>\n";
                    echo "<td>$row[1]</td>\n";
                    echo "<td>$row[2]</td>\n";
                    echo "<td>$row[3]</td>\n";
                    echo "</tr>\n";
                    }
                echo "</tbody>\n";
                echo "</table>\n";
                echo "</div>\n";
                echo "</div>\n";
                echo "</div>\n";
                }
            }

        if ($db_source == 'S')
            {
            mysqli_close($link);
            $use_slave_server=0;
            $db_source = 'M';
            require("dbconnect_mysqli.php");
            }

        ### LOG INSERTION Admin Log Table ###
        $event_notes = "$DB|$SUBMIT|$alt_phone_search|$archive_search|$first_name|$last_name|$lead_id|$list_id|$log_lead_id|$log_lead_id_archive|$log_phone|$log_phone_archive|$owner|$phone|$status|$submit|$user|$vendor_id|$called_count";
        $event_notes = preg_replace("/\"|\\\\|;/","",$event_notes);
        $SQL_log = "$stmtA|$stmtB|$stmtC|";
        $SQL_log = preg_replace('/;/', '', $SQL_log);
        $SQL_log = addslashes($SQL_log);
        $stmt="INSERT INTO vicidial_admin_log set event_date='$NOW_TIME', user='$PHP_AUTH_USER', ip_address='$ip', event_section='LEADS', event_type='SEARCH', record_id='$search_lead', event_code='ADMIN SEARCH LEAD', event_sql=\"$SQL_log\", event_notes=\"$event_notes\";";
        if ($DB) {echo "|$stmt|\n";}
        $rslt=mysql_to_mysqli($stmt, $link);

        $ENDtime = date("U");

        $RUNtime = ($ENDtime - $STARTtime);

        echo "<div class=\"text-center\">\n";
        echo "<a href=\"$PHP_SELF\" class=\"new-search-btn\">"._QXZ("NEW SEARCH")."</a>\n";
        echo "<div class=\"runtime-info\">"._QXZ("script runtime").": $RUNtime "._QXZ("seconds")."</div>\n";
        echo "</div>\n";

        echo "</div></body></html>\n";

        exit;
        }
    ##### END Log search #####


    ##### BEGIN Log archive search #####
    if ( (strlen($log_lead_id_archive)>0) or (strlen($log_phone_archive)>0) )
        {
        if ( (strlen($SScoldstorage_server_ip) > 1) and (strlen($SScoldstorage_login) > 0) and (strlen($SScoldstorage_pass) > 0) and (preg_match("/COLDSTORAGE/",$archive_type)) )
            {
            $linkCS = mysqli_connect("$SScoldstorage_server_ip", "$SScoldstorage_login", "$SScoldstorage_pass", "$SScoldstorage_dbname", $SScoldstorage_port);
            if (!$linkCS) {echo "MySQL Cold-Storage connect ERROR:  " . mysqli_connect_error();}
            }
        if (strlen($log_lead_id_archive)>0)
            {
            $stmtA="SELECT lead_id,phone_number,campaign_id,call_date,status,user,list_id,length_in_sec,alt_dial from vicidial_log_archive where lead_id='" . mysqli_real_escape_string($link, $log_lead_id_archive) . "' $LOGallowed_listsSQL";
            $stmtB="SELECT lead_id,phone_number,campaign_id,call_date,status,user,list_id,length_in_sec from vicidial_closer_log_archive where lead_id='" . mysqli_real_escape_string($link, $log_lead_id_archive) . "' $LOGallowed_listsSQL";
            $log_archive_link='Yes';
            }
        if (strlen($log_phone_archive)>0)
            {
            $stmtA="SELECT lead_id,phone_number,campaign_id,call_date,status,user,list_id,length_in_sec,alt_dial from vicidial_log_archive where phone_number='" . mysqli_real_escape_string($link, $log_phone_archive) . "' $LOGallowed_listsSQL";
            $stmtB="SELECT lead_id,phone_number,campaign_id,call_date,status,user,list_id,length_in_sec from vicidial_closer_log_archive where phone_number='" . mysqli_real_escape_string($link, $log_phone_archive) . "' $LOGallowed_listsSQL";
            $stmtC="SELECT extension,caller_id_number,did_id,call_date from vicidial_did_log where caller_id_number='" . mysqli_real_escape_string($link, $log_phone_archive) . "'";
            $log_archive_link='Yes';
            }

        if ( (strlen($slave_db_server)>5) and (preg_match("/$report_name/",$reports_use_slave_db)) )
            {
            mysqli_close($link);
            $use_slave_server=1;
            $db_source = 'S';
            require("dbconnect_mysqli.php");
            echo "<!-- Using slave server $slave_db_server $db_source -->\n";
            }

        if (preg_match("/ARCHIVE/",$archive_type))
            {
            $rslt=mysql_to_mysqli("$stmtA", $link);
            $results_to_print = mysqli_num_rows($rslt);
            if ( ($results_to_print < 1) and ($results_to_printX < 1) )
                {
                echo "<div class=\"alert alert-warning text-center\">"._QXZ("There are no archived outbound calls matching your search criteria")."</div>\n";
                }
            else
                {
                echo "<div class=\"card search-results\">\n";
                echo "<div class=\"card-header\">"._QXZ("OUTBOUND ARCHIVED LOG RESULTS").": $results_to_print</div>\n";
                echo "<div class=\"card-body\">\n";
                echo "<div class=\"table-container\">\n";
                echo "<table>\n";
                echo "<thead>\n";
                echo "<tr>\n";
                echo "<th>"._QXZ("#")."</th>\n";
                echo "<th>"._QXZ("LEAD ID")."</th>\n";
                echo "<th>"._QXZ("PHONE")."</th>\n";
                echo "<th>"._QXZ("CAMPAIGN")."</th>\n";
                echo "<th>"._QXZ("CALL DATE")."</th>\n";
                echo "<th>"._QXZ("STATUS")."</th>\n";
                echo "<th>"._QXZ("USER")."</th>\n";
                echo "<th>"._QXZ("LIST ID")."</th>\n";
                echo "<th>"._QXZ("LENGTH")."</th>\n";
                echo "<th>"._QXZ("DIAL")."</th>\n";
                echo "</tr>\n";
                echo "</thead>\n";
                echo "<tbody>\n";
                $o=0;
                while ($results_to_print > $o)
                    {
                    $row=mysqli_fetch_row($rslt);
                    if ($LOGadmin_hide_phone_data != '0')
                        {
                        if ($DB > 0) {echo "HIDEPHONEDATA|$row[1]|$LOGadmin_hide_phone_data|\n";}
                        $phone_temp = $row[1];
                        if (strlen($phone_temp) > 0)
                            {
                            if ($LOGadmin_hide_phone_data == '4_DIGITS')
                                {$row[1] = str_repeat("X", (strlen($phone_temp) - 4)) . substr($phone_temp,-4,4);}
                            elseif ($LOGadmin_hide_phone_data == '3_DIGITS')
                                {$row[1] = str_repeat("X", (strlen($phone_temp) - 3)) . substr($phone_temp,-3,3);}
                            elseif ($LOGadmin_hide_phone_data == '2_DIGITS')
                                {$row[1] = str_repeat("X", (strlen($phone_temp) - 2)) . substr($phone_temp,-2,2);}
                            else
                                {$row[1] = preg_replace("/./",'X',$phone_temp);}
                            }
                        }
                    $o++;
                    $search_lead = $row[0];
                    echo "<tr>\n";
                    echo "<td>$o</td>\n";
                    echo "<td><a href=\"admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search&archive_log=$log_archive_link\" onclick=\"javascript:window.open('admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search&archive_log=$log_archive_link', '_blank');return false;\">$row[0]</a></td>\n";
                    echo "<td>$row[1]</td>\n";
                    echo "<td>$row[2]</td>\n";
                    echo "<td>$row[3]</td>\n";
                    echo "<td>$row[4]</td>\n";
                    echo "<td>$row[5]</td>\n";
                    echo "<td>$row[6]</td>\n";
                    echo "<td>$row[7]</td>\n";
                    echo "<td>$row[8]</td>\n";
                    echo "</tr>\n";
                    }
                echo "</tbody>\n";
                echo "</table>\n";
                echo "</div>\n";
                echo "</div>\n";
                echo "</div>\n";
                }

            $rslt=mysql_to_mysqli("$stmtB", $link);
            $results_to_print = mysqli_num_rows($rslt);
            if ( ($results_to_print < 1) and ($results_to_printX < 1) )
                {
                echo "<div class=\"alert alert-warning text-center\">"._QXZ("There are no archived inbound calls matching your search criteria")."</div>\n";
                }
            else
                {
                echo "<div class=\"card search-results\">\n";
                echo "<div class=\"card-header\">"._QXZ("INBOUND ARCHIVED LOG RESULTS").": $results_to_print</div>\n";
                echo "<div class=\"card-body\">\n";
                echo "<div class=\"table-container\">\n";
                echo "<table>\n";
                echo "<thead>\n";
                echo "<tr>\n";
                echo "<th>"._QXZ("#")."</th>\n";
                echo "<th>"._QXZ("LEAD ID")."</th>\n";
                echo "<th>"._QXZ("PHONE")."</th>\n";
                echo "<th>"._QXZ("INGROUP")."</th>\n";
                echo "<th>"._QXZ("CALL DATE")."</th>\n";
                echo "<th>"._QXZ("STATUS")."</th>\n";
                echo "<th>"._QXZ("USER")."</th>\n";
                echo "<th>"._QXZ("LIST ID")."</th>\n";
                echo "<th>"._QXZ("LENGTH")."</th>\n";
                echo "</tr>\n";
                echo "</thead>\n";
                echo "<tbody>\n";
                $o=0;
                while ($results_to_print > $o)
                    {
                    $row=mysqli_fetch_row($rslt);
                    if ($LOGadmin_hide_phone_data != '0')
                        {
                        if ($DB > 0) {echo "HIDEPHONEDATA|$row[1]|$LOGadmin_hide_phone_data|\n";}
                        $phone_temp = $row[1];
                        if (strlen($phone_temp) > 0)
                            {
                            if ($LOGadmin_hide_phone_data == '4_DIGITS')
                                {$row[1] = str_repeat("X", (strlen($phone_temp) - 4)) . substr($phone_temp,-4,4);}
                            elseif ($LOGadmin_hide_phone_data == '3_DIGITS')
                                {$row[1] = str_repeat("X", (strlen($phone_temp) - 3)) . substr($phone_temp,-3,3);}
                            elseif ($LOGadmin_hide_phone_data == '2_DIGITS')
                                {$row[1] = str_repeat("X", (strlen($phone_temp) - 2)) . substr($phone_temp,-2,2);}
                            else
                                {$row[1] = preg_replace("/./",'X',$phone_temp);}
                            }
                        }
                    $o++;
                    $search_lead = $row[0];
                    echo "<tr>\n";
                    echo "<td>$o</td>\n";
                    echo "<td><a href=\"admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search&archive_log=$log_archive_link\" onclick=\"javascript:window.open('admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search&archive_log=$log_archive_link', '_blank');return false;\">$row[0]</a></td>\n";
                    echo "<td>$row[1]</td>\n";
                    echo "<td>$row[2]</td>\n";
                    echo "<td>$row[3]</td>\n";
                    echo "<td>$row[4]</td>\n";
                    echo "<td>$row[5]</td>\n";
                    echo "<td>$row[6]</td>\n";
                    echo "<td>$row[7]</td>\n";
                    echo "</tr>\n";
                    }
                echo "</tbody>\n";
                echo "</table>\n";
                echo "</div>\n";
                echo "</div>\n";
                echo "</div>\n";
                }

            if (strlen($stmtC) > 10)
                {
                $rslt=mysql_to_mysqli("$stmtC", $link);
                $results_to_print = mysqli_num_rows($rslt);
                if ( ($results_to_print < 1) and ($results_to_printX < 1) )
                    {
                    echo "<div class=\"alert alert-warning text-center\">"._QXZ("There are no archived inbound did calls matching your search criteria")."</div>\n";
                    }
                else
                    {
                    echo "<div class=\"card search-results\">\n";
                    echo "<div class=\"card-header\">"._QXZ("INBOUND DID ARCHIVED LOG RESULTS").": $results_to_print</div>\n";
                    echo "<div class=\"card-body\">\n";
                    echo "<div class=\"table-container\">\n";
                    echo "<table>\n";
                    echo "<thead>\n";
                    echo "<tr>\n";
                    echo "<th>"._QXZ("#")."</th>\n";
                    echo "<th>"._QXZ("DID")."</th>\n";
                    echo "<th>"._QXZ("PHONE")."</th>\n";
                    echo "<th>"._QXZ("DID ID")."</th>\n";
                    echo "<th>"._QXZ("CALL DATE")."</th>\n";
                    echo "</tr>\n";
                    echo "</thead>\n";
                    echo "<tbody>\n";
                    $o=0;
                    while ($results_to_print > $o)
                        {
                        $row=mysqli_fetch_row($rslt);
                        if ($LOGadmin_hide_phone_data != '0')
                            {
                            if ($DB > 0) {echo "HIDEPHONEDATA|$row[1]|$LOGadmin_hide_phone_data|\n";}
                            $phone_temp = $row[1];
                            if (strlen($phone_temp) > 0)
                                {
                                if ($LOGadmin_hide_phone_data == '4_DIGITS')
                                    {$row[1] = str_repeat("X", (strlen($phone_temp) - 4)) . substr($phone_temp,-4,4);}
                                elseif ($LOGadmin_hide_phone_data == '3_DIGITS')
                                    {$row[1] = str_repeat("X", (strlen($phone_temp) - 3)) . substr($phone_temp,-3,3);}
                                elseif ($LOGadmin_hide_phone_data == '2_DIGITS')
                                    {$row[1] = str_repeat("X", (strlen($phone_temp) - 2)) . substr($phone_temp,-2,2);}
                                else
                                    {$row[1] = preg_replace("/./",'X',$phone_temp);}
                                }
                            }
                        $o++;
                        $search_lead = $row[0];
                        echo "<tr>\n";
                        echo "<td>$o</td>\n";
                        echo "<td>$row[0]</td>\n";
                        echo "<td>$row[1]</td>\n";
                        echo "<td>$row[2]</td>\n";
                        echo "<td>$row[3]</td>\n";
                        echo "</tr>\n";
                        }
                    echo "</tbody>\n";
                    echo "</table>\n";
                    echo "</div>\n";
                    echo "</div>\n";
                    echo "</div>\n";
                    }
                }
            }

        // Search through cold-storage archive logs
        if ( ($linkCS) and (preg_match("/COLDSTORAGE/",$archive_type)) )
            {
            $rslt=mysql_to_mysqli("$stmtA", $linkCS);
            $results_to_print = mysqli_num_rows($rslt);
            if ( ($results_to_print < 1) and ($results_to_printX < 1) )
                {
                echo "<div class=\"alert alert-warning text-center\">"._QXZ("There are no cold-storage outbound calls matching your search criteria")."</div>\n";
                }
            else
                {
                echo "<div class=\"card search-results\">\n";
                echo "<div class=\"card-header\">"._QXZ("OUTBOUND COLD-STORAGE LOG RESULTS").": $results_to_print</div>\n";
                echo "<div class=\"card-body\">\n";
                echo "<div class=\"table-container\">\n";
                echo "<table>\n";
                echo "<thead>\n";
                echo "<tr>\n";
                echo "<th>"._QXZ("#")."</th>\n";
                echo "<th>"._QXZ("LEAD ID")."</th>\n";
                echo "<th>"._QXZ("PHONE")."</th>\n";
                echo "<th>"._QXZ("CAMPAIGN")."</th>\n";
                echo "<th>"._QXZ("CALL DATE")."</th>\n";
                echo "<th>"._QXZ("STATUS")."</th>\n";
                echo "<th>"._QXZ("USER")."</th>\n";
                echo "<th>"._QXZ("LIST ID")."</th>\n";
                echo "<th>"._QXZ("LENGTH")."</th>\n";
                echo "<th>"._QXZ("DIAL")."</th>\n";
                echo "</tr>\n";
                echo "</thead>\n";
                echo "<tbody>\n";
                $o=0;
                while ($results_to_print > $o)
                    {
                    $row=mysqli_fetch_row($rslt);
                    if ($LOGadmin_hide_phone_data != '0')
                        {
                        if ($DB > 0) {echo "HIDEPHONEDATA|$row[1]|$LOGadmin_hide_phone_data|\n";}
                        $phone_temp = $row[1];
                        if (strlen($phone_temp) > 0)
                            {
                            if ($LOGadmin_hide_phone_data == '4_DIGITS')
                                {$row[1] = str_repeat("X", (strlen($phone_temp) - 4)) . substr($phone_temp,-4,4);}
                            elseif ($LOGadmin_hide_phone_data == '3_DIGITS')
                                {$row[1] = str_repeat("X", (strlen($phone_temp) - 3)) . substr($phone_temp,-3,3);}
                            elseif ($LOGadmin_hide_phone_data == '2_DIGITS')
                                {$row[1] = str_repeat("X", (strlen($phone_temp) - 2)) . substr($phone_temp,-2,2);}
                            else
                                {$row[1] = preg_replace("/./",'X',$phone_temp);}
                            }
                        }
                    $o++;
                    $search_lead = $row[0];
                    echo "<tr>\n";
                    echo "<td>$o</td>\n";
                    echo "<td><a href=\"admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search&archive_log=$log_archive_link\" onclick=\"javascript:window.open('admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search&archive_log=$log_archive_link', '_blank');return false;\">$row[0]</a></td>\n";
                    echo "<td>$row[1]</td>\n";
                    echo "<td>$row[2]</td>\n";
                    echo "<td>$row[3]</td>\n";
                    echo "<td>$row[4]</td>\n";
                    echo "<td>$row[5]</td>\n";
                    echo "<td>$row[6]</td>\n";
                    echo "<td>$row[7]</td>\n";
                    echo "<td>$row[8]</td>\n";
                    echo "</tr>\n";
                    }
                echo "</tbody>\n";
                echo "</table>\n";
                echo "</div>\n";
                echo "</div>\n";
                echo "</div>\n";
                }

            $rslt=mysql_to_mysqli("$stmtB", $linkCS);
            $results_to_print = mysqli_num_rows($rslt);
            if ( ($results_to_print < 1) and ($results_to_printX < 1) )
                {
                echo "<div class=\"alert alert-warning text-center\">"._QXZ("There are no cold-storage inbound calls matching your search criteria")."</div>\n";
                }
            else
                {
                echo "<div class=\"card search-results\">\n";
                echo "<div class=\"card-header\">"._QXZ("INBOUND COLD-STORAGE LOG RESULTS").": $results_to_print</div>\n";
                echo "<div class=\"card-body\">\n";
                echo "<div class=\"table-container\">\n";
                echo "<table>\n";
                echo "<thead>\n";
                echo "<tr>\n";
                echo "<th>"._QXZ("#")."</th>\n";
                echo "<th>"._QXZ("LEAD ID")."</th>\n";
                echo "<th>"._QXZ("PHONE")."</th>\n";
                echo "<th>"._QXZ("INGROUP")."</th>\n";
                echo "<th>"._QXZ("CALL DATE")."</th>\n";
                echo "<th>"._QXZ("STATUS")."</th>\n";
                echo "<th>"._QXZ("USER")."</th>\n";
                echo "<th>"._QXZ("LIST ID")."</th>\n";
                echo "<th>"._QXZ("LENGTH")."</th>\n";
                echo "</tr>\n";
                echo "</thead>\n";
                echo "<tbody>\n";
                $o=0;
                while ($results_to_print > $o)
                    {
                    $row=mysqli_fetch_row($rslt);
                    if ($LOGadmin_hide_phone_data != '0')
                        {
                        if ($DB > 0) {echo "HIDEPHONEDATA|$row[1]|$LOGadmin_hide_phone_data|\n";}
                        $phone_temp = $row[1];
                        if (strlen($phone_temp) > 0)
                            {
                            if ($LOGadmin_hide_phone_data == '4_DIGITS')
                                {$row[1] = str_repeat("X", (strlen($phone_temp) - 4)) . substr($phone_temp,-4,4);}
                            elseif ($LOGadmin_hide_phone_data == '3_DIGITS')
                                {$row[1] = str_repeat("X", (strlen($phone_temp) - 3)) . substr($phone_temp,-3,3);}
                            elseif ($LOGadmin_hide_phone_data == '2_DIGITS')
                                {$row[1] = str_repeat("X", (strlen($phone_temp) - 2)) . substr($phone_temp,-2,2);}
                            else
                                {$row[1] = preg_replace("/./",'X',$phone_temp);}
                            }
                        }
                    $o++;
                    $search_lead = $row[0];
                    echo "<tr>\n";
                    echo "<td>$o</td>\n";
                    echo "<td><a href=\"admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search&archive_log=$log_archive_link\" onclick=\"javascript:window.open('admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search&archive_log=$log_archive_link', '_blank');return false;\">$row[0]</a></td>\n";
                    echo "<td>$row[1]</td>\n";
                    echo "<td>$row[2]</td>\n";
                    echo "<td>$row[3]</td>\n";
                    echo "<td>$row[4]</td>\n";
                    echo "<td>$row[5]</td>\n";
                    echo "<td>$row[6]</td>\n";
                    echo "<td>$row[7]</td>\n";
                    echo "</tr>\n";
                    }
                echo "</tbody>\n";
                echo "</table>\n";
                echo "</div>\n";
                echo "</div>\n";
                echo "</div>\n";
                }

            if (strlen($stmtC) > 10)
                {
                $rslt=mysql_to_mysqli("$stmtC", $linkCS);
                $results_to_print = mysqli_num_rows($rslt);
                if ( ($results_to_print < 1) and ($results_to_printX < 1) )
                    {
                    echo "<div class=\"alert alert-warning text-center\">"._QXZ("There are no cold-storage inbound did calls matching your search criteria")."</div>\n";
                    }
                else
                    {
                    echo "<div class=\"card search-results\">\n";
                    echo "<div class=\"card-header\">"._QXZ("INBOUND DID COLD-STORAGE LOG RESULTS").": $results_to_print</div>\n";
                    echo "<div class=\"card-body\">\n";
                    echo "<div class=\"table-container\">\n";
                    echo "<table>\n";
                    echo "<thead>\n";
                    echo "<tr>\n";
                    echo "<th>"._QXZ("#")."</th>\n";
                    echo "<th>"._QXZ("DID")."</th>\n";
                    echo "<th>"._QXZ("PHONE")."</th>\n";
                    echo "<th>"._QXZ("DID ID")."</th>\n";
                    echo "<th>"._QXZ("CALL DATE")."</th>\n";
                    echo "</tr>\n";
                    echo "</thead>\n";
                    echo "<tbody>\n";
                    $o=0;
                    while ($results_to_print > $o)
                        {
                        $row=mysqli_fetch_row($rslt);
                        if ($LOGadmin_hide_phone_data != '0')
                            {
                            if ($DB > 0) {echo "HIDEPHONEDATA|$row[1]|$LOGadmin_hide_phone_data|\n";}
                            $phone_temp = $row[1];
                            if (strlen($phone_temp) > 0)
                                {
                                if ($LOGadmin_hide_phone_data == '4_DIGITS')
                                    {$row[1] = str_repeat("X", (strlen($phone_temp) - 4)) . substr($phone_temp,-4,4);}
                                elseif ($LOGadmin_hide_phone_data == '3_DIGITS')
                                    {$row[1] = str_repeat("X", (strlen($phone_temp) - 3)) . substr($phone_temp,-3,3);}
                                elseif ($LOGadmin_hide_phone_data == '2_DIGITS')
                                    {$row[1] = str_repeat("X", (strlen($phone_temp) - 2)) . substr($phone_temp,-2,2);}
                                else
                                    {$row[1] = preg_replace("/./",'X',$phone_temp);}
                                }
                            }
                        $o++;
                        $search_lead = $row[0];
                        echo "<tr>\n";
                        echo "<td>$o</td>\n";
                        echo "<td>$row[0]</td>\n";
                        echo "<td>$row[1]</td>\n";
                        echo "<td>$row[2]</td>\n";
                        echo "<td>$row[3]</td>\n";
                        echo "</tr>\n";
                        }
                    echo "</tbody>\n";
                    echo "</table>\n";
                    echo "</div>\n";
                    echo "</div>\n";
                    echo "</div>\n";
                    }
                }
            }

        if ($db_source == 'S')
            {
            mysqli_close($link);
            $use_slave_server=0;
            $db_source = 'M';
            require("dbconnect_mysqli.php");
            }

        ### LOG INSERTION Admin Log Table ###
        $event_notes = "$DB|$SUBMIT|$alt_phone_search|$archive_search|$first_name|$last_name|$lead_id|$list_id|$log_lead_id|$log_lead_id_archive|$log_phone|$log_phone_archive|$owner|$phone|$status|$submit|$user|$vendor_id|$called_count";
        $event_notes = preg_replace("/\"|\\\\|;/","",$event_notes);
        $SQL_log = "$stmtA|$stmtB|$stmtC|";
        $SQL_log = preg_replace('/;/', '', $SQL_log);
        $SQL_log = addslashes($SQL_log);
        $stmt="INSERT INTO vicidial_admin_log set event_date='$NOW_TIME', user='$PHP_AUTH_USER', ip_address='$ip', event_section='LEADS', event_type='SEARCH', record_id='$search_lead', event_code='ADMIN SEARCH LEAD', event_sql=\"$SQL_log\", event_notes=\"ARCHIVE   $event_notes\";";
        if ($DB) {echo "|$stmt|\n";}
        $rslt=mysql_to_mysqli($stmt, $link);

        $ENDtime = date("U");

        $RUNtime = ($ENDtime - $STARTtime);

echo "<div class=\"text-center\">\n";
       

echo "<a href=\"$PHP_SELF\" class=\"new-search-btn\">"._QXZ("NEW SEARCH")."</a>\n";
echo "<div class=\"runtime-info\">"._QXZ("script runtime").": $RUNtime "._QXZ("seconds")."</div>\n";
echo "</div>\n";

echo "</div></body></html>\n";

exit;
        }
    ##### END Log archive search #####

    ##### BEGIN Lead search #####
    if ($vendor_id)
        {
        $stmt="SELECT $vicidial_list_fields from $vl_table where vendor_lead_code='" . mysqli_real_escape_string($link, $vendor_id) . "' $LOGallowed_listsSQL";
        }
    else
        {
        if ($phone)
            {
            if ($alt_phone_search=="Yes")
                {
                $stmt="SELECT $vicidial_list_fields from $vl_table where phone_number='" . mysqli_real_escape_string($link, $phone) . "' or alt_phone='" . mysqli_real_escape_string($link, $phone) . "' or address3='" . mysqli_real_escape_string($link, $phone) . "' $LOGallowed_listsSQL";
                }
            else
                {
                $stmt="SELECT $vicidial_list_fields from $vl_table where phone_number='" . mysqli_real_escape_string($link, $phone) . "' $LOGallowed_listsSQL";
                }
            }
        else
            {
            if ($lead_id)
                {
                $stmt="SELECT $vicidial_list_fields from $vl_table where lead_id='" . mysqli_real_escape_string($link, $lead_id) . "' $LOGallowed_listsSQL";
                }
            else
                {
                if ( (strlen($status)>0) or (strlen($list_id)>0) or (strlen($user)>0) or (strlen($owner)>0) )
                    {
                    $statusSQL = '';
                    $list_idSQL = '';
                    $userSQL = '';
                    $ownerSQL = '';
                    $called_countSQL = '';
                    if (strlen($status)>0)
                        {
                        $statusSQL = "status='" . mysqli_real_escape_string($link, $status) . "'"; $SQLctA++;
                        }
                    if (strlen($list_id)>0)
                        {
                        if ($SQLctA > 0) {$andA = 'and';}
                        $list_idSQL = "$andA list_id='" . mysqli_real_escape_string($link, $list_id) . "'"; $SQLctB++;
                        }
                    if (strlen($user)>0)
                        {
                        if ( ($SQLctA > 0) or ($SQLctB > 0) ) {$andB = 'and';}
                        $userSQL = "$andB user='" . mysqli_real_escape_string($link, $user) . "'"; $SQLctC++;
                        }
                    if (strlen($owner)>0)
                        {
                        if ( ($SQLctA > 0) or ($SQLctB > 0) or ($SQLctC > 0) ) {$andC = 'and';}
                        $ownerSQL = "$andC owner='" . mysqli_real_escape_string($link, $owner) . "'";
                        }
                    if (strlen($called_count)>0)
                        {
                        $called_countSQL = "and called_count='" . mysqli_real_escape_string($link, $called_count) . "'";
                        if ($called_count > 99)
                            {$called_countSQL = "and called_count > " . mysqli_real_escape_string($link, $called_count);}
                        }
                    $stmt="SELECT $vicidial_list_fields from $vl_table where $statusSQL $list_idSQL $userSQL $ownerSQL $called_countSQL $LOGallowed_listsSQL";
                    }
                else
                    {
                    if ( (strlen($first_name)>0) or (strlen($last_name)>0) )
                        {
                        $first_nameSQL = '';
                        $last_nameSQL = '';
                        if (strlen($first_name)>0)	
                            {
                            $first_nameSQL = "first_name='" . mysqli_real_escape_string($link, $first_name) . "'"; $SQLctA++;
                            }
                        if (strlen($last_name)>0) 
                            {
                            if ($SQLctA > 0) {$andA = 'and';}
                            $last_nameSQL = "$andA last_name='" . mysqli_real_escape_string($link, $last_name) . "'";
                            }
                        $stmt="SELECT $vicidial_list_fields from $vl_table where $first_nameSQL $last_nameSQL $LOGallowed_listsSQL";
                        }
                    else
                        {
                        if ( (strlen($email)>0) )
                            {
                            $email_SQL = "email='" . mysqli_real_escape_string($link, $email) . "'";
                            $stmt="SELECT $vicidial_list_fields from $vl_table where $email_SQL $LOGallowed_listsSQL";
                            }
                        else
                            {
                            print _QXZ("ERROR: you must search for something! Go back and search for something");
                            exit;
                            }
                        }
                    }
                }
            }
        }

    if ( (strlen($slave_db_server)>5) and (preg_match("/$report_name/",$reports_use_slave_db)) )
      {
      mysqli_close($link);
      $use_slave_server=1;
      $db_source = 'S';
      require("dbconnect_mysqli.php");
      echo "<!-- Using slave server $slave_db_server $db_source -->\n";
      }

    $stmt_alt='';
    $results_to_printX=0;
    if ( ($alt_phone_search=="Yes") and (strlen($phone) > 4) )
        {
        $stmtX="SELECT lead_id from vicidial_list_alt_phones where phone_number='" . mysqli_real_escape_string($link, $phone) . "' $LOGallowed_listsSQL limit 10000;";
        $rsltX=mysql_to_mysqli($stmtX, $link);
        $results_to_printX = mysqli_num_rows($rsltX);
        if ($DB)
            {echo "\n\n$results_to_printX|$stmtX\n\n";}
        $o=0;
        while ($results_to_printX > $o)
            {
            $row=mysqli_fetch_row($rsltX);
            if ($o > 0) {$stmt_alt .= ",";}
            $stmt_alt .= "'$row[0]'";
            $o++;
            }
        if (strlen($stmt_alt) > 2)
            {$stmt_alt = "or lead_id IN($stmt_alt)";}
        }

    $stmt = "$stmt$stmt_alt order by modify_date desc limit 10000;";

    if ($DB)
        {
        echo "\n\n$stmt\n\n";
        }

    if ($db_source == 'S')
        {
        mysqli_close($link);
        $use_slave_server=0;
        $db_source = 'M';
        require("dbconnect_mysqli.php");
        }

    ### LOG INSERTION Search Log Table ###
    $SQL_log = "$stmt|";
    $SQL_log = preg_replace('/;/', '', $SQL_log);
    $SQL_log = addslashes($SQL_log);
    $stmtL="INSERT INTO vicidial_lead_search_log set event_date='$NOW_TIME', user='$PHP_AUTH_USER', source='admin', results='0', search_query=\"$SQL_log\";";
    if ($DB) {echo "|$stmtL|\n";}
    $rslt=mysql_to_mysqli($stmtL, $link);
    $search_log_id = mysqli_insert_id($link);

    if ( (strlen($slave_db_server)>5) and (preg_match("/$report_name/",$reports_use_slave_db)) )
        {
        mysqli_close($link);
        $use_slave_server=1;
        $db_source = 'S';
        require("dbconnect_mysqli.php");
        echo "<!-- Using slave server $slave_db_server $db_source -->\n";
        }


    $rslt=mysql_to_mysqli("$stmt", $link);
    $results_to_print = mysqli_num_rows($rslt);
    if ( ($results_to_print < 1) and ($results_to_printX < 1) )
        {
        echo "<div class=\"alert alert-warning text-center\">\n";
        echo "<strong>"._QXZ("The search variables you entered are not active in the system")."</strong><br>\n";
        echo _QXZ("Please go back and double check the information you entered and submit again")."\n";
        echo "</div>\n";
        echo "</div></body></html>\n";
        exit;
        }
    else
        {
        echo "<div class=\"card search-results\">\n";
        echo "<div class=\"card-header\">\n";
        echo "<div class=\"result-count\">"._QXZ("RESULTS").": $results_to_print</div>\n";
        echo "</div>\n";
        echo "<div class=\"card-body\">\n";
        echo "<div class=\"table-container\">\n";
        echo "<table>\n";
        echo "<thead>\n";
        echo "<tr>\n";
        echo "<th>"._QXZ("#")."</th>\n";
        echo "<th>"._QXZ("LEAD ID")."</th>\n";
        echo "<th>"._QXZ("STATUS")."</th>\n";
        echo "<th>"._QXZ("VENDOR ID")."</th>\n";
        echo "<th>"._QXZ("LAST AGENT")."</th>\n";
        echo "<th>"._QXZ("LIST ID")."</th>\n";
        echo "<th>"._QXZ("PHONE")."</th>\n";
        echo "<th>"._QXZ("NAME")."</th>\n";
        echo "<th>"._QXZ("CITY")."</th>\n";
        echo "<th>"._QXZ("SECURITY")."</th>\n";
        echo "<th>"._QXZ("LAST CALL")."</th>\n";
        echo "</tr>\n";
        echo "</thead>\n";
        echo "<tbody>\n";
        $o=0;
        while ($results_to_print > $o)
            {
            $row=mysqli_fetch_row($rslt);
            if ($LOGadmin_hide_phone_data != '0')
                {
                if ($DB > 0) {echo "HIDEPHONEDATA|$row[11]|$LOGadmin_hide_phone_data|\n";}
                $phone_temp = $row[11];
                if (strlen($phone_temp) > 0)
                    {
                    if ($LOGadmin_hide_phone_data == '4_DIGITS')
                        {$row[11] = str_repeat("X", (strlen($phone_temp) - 4)) . substr($phone_temp,-4,4);}
                    elseif ($LOGadmin_hide_phone_data == '3_DIGITS')
                        {$row[11] = str_repeat("X", (strlen($phone_temp) - 3)) . substr($phone_temp,-3,3);}
                    elseif ($LOGadmin_hide_phone_data == '2_DIGITS')
                        {$row[11] = str_repeat("X", (strlen($phone_temp) - 2)) . substr($phone_temp,-2,2);}
                    else
                        {$row[11] = preg_replace("/./",'X',$phone_temp);}
                    }
                }
            if ($LOGadmin_hide_lead_data != '0')
                {
                if ($DB > 0) {echo "HIDELEADDATA|$row[5]|$row[6]|$row[12]|$row[13]|$row[14]|$row[15]|$row[16]|$row[17]|$row[18]|$row[19]|$row[20]|$row[21]|$row[22]|$row[26]|$row[27]|$row[28]|$LOGadmin_hide_lead_data|\n";}
                if (strlen($row[5]) > 0)
                    {$data_temp = $row[5];   $row[5] = preg_replace("/./",'X',$data_temp);}
                if (strlen($row[6]) > 0)
                    {$data_temp = $row[6];   $row[6] = preg_replace("/./",'X',$data_temp);}
                if (strlen($row[12]) > 0)
                    {$data_temp = $row[12];   $row[12] = preg_replace("/./",'X',$data_temp);}
                if (strlen($row[13]) > 0)
                    {$data_temp = $row[13];   $row[13] = preg_replace("/./",'X',$data_temp);}
                if (strlen($row[14]) > 0)
                    {$data_temp = $row[14];   $row[14] = preg_replace("/./",'X',$data_temp);}
                if (strlen($row[15]) > 0)
                    {$data_temp = $row[15];   $row[15] = preg_replace("/./",'X',$data_temp);}
                if (strlen($row[16]) > 0)
                    {$data_temp = $row[16];   $row[16] = preg_replace("/./",'X',$data_temp);}
                if (strlen($row[17]) > 0)
                    {$data_temp = $row[17];   $row[17] = preg_replace("/./",'X',$data_temp);}
                if (strlen($row[18]) > 0)
                    {$data_temp = $row[18];   $row[18] = preg_replace("/./",'X',$data_temp);}
                if (strlen($row[19]) > 0)
                    {$data_temp = $row[19];   $row[19] = preg_replace("/./",'X',$data_temp);}
                if (strlen($row[20]) > 0)
                    {$data_temp = $row[20];   $row[20] = preg_replace("/./",'X',$data_temp);}
                if (strlen($row[21]) > 0)
                    {$data_temp = $row[21];   $row[21] = preg_replace("/./",'X',$data_temp);}
                if (strlen($row[22]) > 0)
                    {$data_temp = $row[22];   $row[22] = preg_replace("/./",'X',$data_temp);}
                if (strlen($row[26]) > 0)
                    {$data_temp = $row[26];   $row[26] = preg_replace("/./",'X',$data_temp);}
                if (strlen($row[27]) > 0)
                    {$data_temp = $row[27];   $row[27] = preg_replace("/./",'X',$data_temp);}
                if (strlen($row[28]) > 0)
                    {$data_temp = $row[28];   $row[28] = preg_replace("/./",'X',$data_temp);}
                }

            $o++;
            $search_lead = $row[0];
            echo "<tr>\n";
            echo "<td>$o</td>\n";
            echo "<td><a href=\"admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search&archive_log=$log_archive_link\" onclick=\"javascript:window.open('admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search&archive_log=$log_archive_link', '_blank');return false;\">$row[0]</a></td>\n";
            echo "<td>$row[3]</td>\n";
            echo "<td>$row[5]</td>\n";
            echo "<td>$row[4]</td>\n";
            echo "<td>$row[7]</td>\n";
            echo "<td>$row[11]</td>\n";
            echo "<td>$row[13] $row[15]</td>\n";
            echo "<td>$row[19]</td>\n";
            echo "<td>$row[28]</td>\n";
            echo "<td>$row[31]</td>\n";
            echo "</tr>\n";
            }
        echo "</tbody>\n";
        echo "</table>\n";
        echo "</div>\n";
        echo "</div>\n";
        echo "</div>\n";
        }
    
    if ($db_source == 'S')
        {
        mysqli_close($link);
        $use_slave_server=0;
        $db_source = 'M';
        require("dbconnect_mysqli.php");
        }

    ### LOG INSERTION Admin Log Table ###
    $SQL_log = "$stmt|";
    $SQL_log = preg_replace('/;/', '', $SQL_log);
    $SQL_log = addslashes($SQL_log);
    $stmt="INSERT INTO vicidial_admin_log set event_date='$NOW_TIME', user='$PHP_AUTH_USER', ip_address='$ip', event_section='LEADS', event_type='SEARCH', record_id='$search_lead', event_code='ADMIN SEARCH LEAD', event_sql=\"$SQL_log\", event_notes='';";
    if ($DB) {echo "|$stmt|\n";}
    $rslt=mysql_to_mysqli($stmt, $link);

    $end_process_time = date("U");
    $search_seconds = ($end_process_time - $STARTtime);

    $stmtL="UPDATE vicidial_lead_search_log set results='$o', seconds='$search_seconds' where search_log_id='$search_log_id';";
    if ($DB) {echo "|$stmtL|\n";}
    $rslt=mysql_to_mysqli($stmtL, $link);
    }
    ##### END Lead search #####

 $ENDtime = date("U");

 $RUNtime = ($ENDtime - $STARTtime);

echo "<div class=\"text-center\">\n";
echo "<a href=\"$PHP_SELF\" class=\"new-search-btn\">"._QXZ("NEW SEARCH")."</a>\n";
echo "<div class=\"runtime-info\">"._QXZ("script runtime").": $RUNtime "._QXZ("seconds")."</div>\n";
echo "</div>\n";

echo "</div>\n";
echo "</body>\n";
echo "</html>\n";
?>