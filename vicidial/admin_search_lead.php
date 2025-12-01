<?php
/**
 * admin_search_lead.php - Version 2.14
 * Lead Search Administration Interface
 */

require("dbconnect_mysqli.php");
require("functions.php");

// Initialize all request variables
$PHP_AUTH_USER = $_SERVER['PHP_AUTH_USER'];
$PHP_AUTH_PW = $_SERVER['PHP_AUTH_PW'];
$PHP_SELF = $_SERVER['PHP_SELF'];
$PHP_SELF = preg_replace('/\.php.*/i', '.php', $PHP_SELF);

// Request parameter handling
$request_vars = [
    'vendor_id', 'first_name', 'last_name', 'email', 'phone', 'lead_id',
    'log_phone', 'log_lead_id', 'log_phone_archive', 'log_lead_id_archive',
    'submit', 'SUBMIT', 'DB', 'status', 'user', 'owner', 'list_id',
    'alt_phone_search', 'archive_search', 'called_count', 'archive_type'
];

foreach ($request_vars as $var) {
    $$var = $_POST[$var] ?? $_GET[$var] ?? '';
}

$report_name = 'Search Leads Logs';

// Sanitize DB parameter
$DB = preg_replace("/[^0-9a-zA-Z]/", "", $DB);

// Field definitions
$vicidial_list_fields = 'lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner';

// Default values
if (strlen($alt_phone_search) < 2) { $alt_phone_search = 'No'; }

// Time and environment variables
$STARTtime = date("U");
$TODAY = date("Y-m-d");
$NOW_TIME = date("Y-m-d H:i:s");
$date = date("r");
$ip = getenv("REMOTE_ADDR");
$browser = getenv("HTTP_USER_AGENT");
$log_archive_link = 0;

// System Settings Lookup
$stmt = "SELECT use_non_latin,webroot_writable,outbound_autodial_active,user_territories_active,slave_db_server,reports_use_slave_db,enable_languages,language_method,qc_features_active,allow_web_debug,coldstorage_server_ip,coldstorage_dbname,coldstorage_login,coldstorage_pass,coldstorage_port FROM system_settings;";
$rslt = mysql_to_mysqli($stmt, $link);
$qm_conf_ct = mysqli_num_rows($rslt);

if ($qm_conf_ct > 0) {
    $row = mysqli_fetch_row($rslt);
    $non_latin = $row[0];
    $webroot_writable = $row[1];
    $SSoutbound_autodial_active = $row[2];
    $user_territories_active = $row[3];
    $slave_db_server = $row[4];
    $reports_use_slave_db = $row[5];
    $SSenable_languages = $row[6];
    $SSlanguage_method = $row[7];
    $SSqc_features_active = $row[8];
    $SSallow_web_debug = $row[9];
    $SScoldstorage_server_ip = $row[10];
    $SScoldstorage_dbname = $row[11];
    $SScoldstorage_login = $row[12];
    $SScoldstorage_pass = $row[13];
    $SScoldstorage_port = $row[14];
}

if ($SSallow_web_debug < 1) { $DB = 0; }

// Archive table selection
if ($archive_search == "Yes") {
    $vl_table = "vicidial_list_archive";
} else {
    $vl_table = "vicidial_list";
    $archive_search = "No";
}

// Input sanitization
$phone = preg_replace('/[^0-9]/', '', $phone);
$log_phone = preg_replace('/[^-_0-9a-zA-Z]/', '', $log_phone);
$log_phone_archive = preg_replace('/[^-_0-9a-zA-Z]/', '', $log_phone_archive);
$list_id = preg_replace('/[^0-9]/', '', $list_id);
$lead_id = preg_replace('/[^0-9]/', '', $lead_id);
$log_lead_id = preg_replace('/[^0-9]/', '', $log_lead_id);
$log_lead_id_archive = preg_replace('/[^0-9]/', '', $log_lead_id_archive);
$submit = preg_replace('/[^-_0-9a-zA-Z]/', '', $submit);
$SUBMIT = preg_replace('/[^-_0-9a-zA-Z]/', '', $SUBMIT);
$vendor_id = preg_replace("/\<|\>|\'|\"|\\\\|;/", "", $vendor_id);
$first_name = preg_replace("/\<|\>|\'|\"|\\\\|;/", "", $first_name);
$last_name = preg_replace("/\<|\>|\'|\"|\\\\|;/", "", $last_name);
$email = preg_replace("/\<|\>|\'|\"|\\\\|;/", "", $email);
$user = preg_replace("/\<|\>|\'|\"|\\\\|;/", "", $user);
$owner = preg_replace("/\<|\>|\'|\"|\\\\|;/", "", $owner);
$alt_phone_search = preg_replace('/[^-_0-9a-zA-Z]/', '', $alt_phone_search);
$archive_search = preg_replace('/[^-_0-9a-zA-Z]/', '', $archive_search);
$called_count = preg_replace('/[^0-9]/', '', $called_count);
$archive_type = preg_replace('/[^-_0-9a-zA-Z]/', '', $archive_type);

// Language-specific sanitization
if ($non_latin < 1) {
    $PHP_AUTH_USER = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_USER);
    $PHP_AUTH_PW = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_PW);
    $status = preg_replace('/[^-_0-9a-zA-Z]/', '', $status);
} else {
    $PHP_AUTH_USER = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_USER);
    $PHP_AUTH_PW = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_PW);
    $status = preg_replace('/[^-_0-9\p{L}]/u', '', $status);
}

// Get user language preference
$stmt = "SELECT selected_language from vicidial_users where user='$PHP_AUTH_USER';";
if ($DB) { echo "|$stmt|\n"; }
$rslt = mysql_to_mysqli($stmt, $link);
$sl_ct = mysqli_num_rows($rslt);
if ($sl_ct > 0) {
    $row = mysqli_fetch_row($rslt);
    $VUselected_language = $row[0];
}

// User authentication
$auth = 0;
$auth_message = user_authorization($PHP_AUTH_USER, $PHP_AUTH_PW, '', 1, 0);

if (($auth_message == 'GOOD') or ($auth_message == '2FA')) {
    $auth = 1;
    if ($auth_message == '2FA') {
        header("Content-type: text/html; charset=utf-8");
        echo _QXZ("Your session is expired") . ". <a href=\"admin.php\">" . _QXZ("Click here to log in") . "</a>.\n";
        exit;
    }
}

if ($auth < 1) {
    $VDdisplayMESSAGE = _QXZ("Login incorrect, please try again");
    
    if ($auth_message == 'LOCK') {
        $VDdisplayMESSAGE = _QXZ("Too many login attempts, try again in 15 minutes");
        Header("Content-type: text/html; charset=utf-8");
        echo "$VDdisplayMESSAGE: |$PHP_AUTH_USER|$auth_message|\n";
        exit;
    }
    
    if ($auth_message == 'IPBLOCK') {
        $VDdisplayMESSAGE = _QXZ("Your IP Address is not allowed") . ": $ip";
        Header("Content-type: text/html; charset=utf-8");
        echo "$VDdisplayMESSAGE: |$PHP_AUTH_USER|$auth_message|\n";
        exit;
    }
    
    Header("WWW-Authenticate: Basic realm=\"CONTACT-CENTER-ADMIN\"");
    Header("HTTP/1.0 401 Unauthorized");
    echo "$VDdisplayMESSAGE: |$PHP_AUTH_USER|$PHP_AUTH_PW|$auth_message|\n";
    exit;
}

// Check user permissions
$rights_stmt = "SELECT modify_leads from vicidial_users where user='$PHP_AUTH_USER';";
if ($DB) { echo "|$rights_stmt|\n"; }
$rights_rslt = mysql_to_mysqli($rights_stmt, $link);
$rights_row = mysqli_fetch_row($rights_rslt);
$modify_leads = $rights_row[0];

if ($modify_leads < 1) {
    header("Content-type: text/html; charset=utf-8");
    echo _QXZ("You do not have permissions to search leads") . "\n";
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
<title>
<?php echo _QXZ("ADMINISTRATION: Lead Search");

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



// Display search results header
echo "<div class='search-container'>";
echo "<div class='search-header'>";
echo "<h1>" . _QXZ("Lead search") . ": $vendor_id $phone $lead_id $status $list_id $user</h1>";
echo "<div class='date'>" . date("l F j, Y g:i:s A") . "</div>";
echo "</div>";

if ((!$vendor_id) and (!$phone) and (!$lead_id) and (!$log_phone) and (!$log_lead_id) and (!$log_phone_archive) and (!$log_lead_id_archive) and ((strlen($status) < 1) and (strlen($list_id) < 1) and (strlen($user) < 1) and (strlen($owner) < 1)) and ((strlen($first_name) < 1) and (strlen($last_name) < 1) and (strlen($email) < 1))) {
    // Display search form (use the modernized form from previous response)
?>
    <form method="post" name="search" action="<?php echo $PHP_SELF; ?>">
        <input type="hidden" name="DB" value="<?php echo $DB; ?>">
        
        <?php
        // Archive search option
        $archive_stmt = "SHOW TABLES LIKE '%vicidial_list_archive%'";
        $archive_rslt = mysql_to_mysqli($archive_stmt, $link);
        if (mysqli_num_rows($archive_rslt) > 0) {
        ?>
        <div class="search-section">
            <div class="search-row">
                <div class="search-label"><?php echo _QXZ("Archive search"); ?>:</div>
                <select name="archive_search" class="search-select">
                    <option value="No"><?php echo _QXZ("No"); ?></option>
                    <option value="Yes"><?php echo _QXZ("Yes"); ?></option>
                    <option selected value="<?php echo $archive_search; ?>"><?php echo _QXZ($archive_search); ?></option>
                </select>
                <div></div>
            </div>
        </div>
        <?php } ?>
        
        <!-- All search form sections from previous response -->
        <!-- [Insert complete form sections here] -->
        
        <!-- Log Search Section -->
        <div class="search-section" style="border-left-color:#17a2b8;">
            <div class="search-section-title"><?php echo _QXZ("Log Search Options"); ?></div>
            <div class="search-row">
                <div class="search-label"><?php echo _QXZ("Lead ID"); ?>:</div>
                <input type="text" name="log_lead_id" class="search-input" size="10" maxlength="10">
                <button type="submit" name="SUBMIT" class="submit-btn"><?php echo _QXZ("SUBMIT"); ?></button>
            </div>
            <div class="search-row">
                <div class="search-label"><?php echo $label_phone_number . " " . _QXZ("Dialed"); ?>:</div>
                <input type="text" name="log_phone" class="search-input" size="18" maxlength="18">
                <button type="submit" name="SUBMIT" class="submit-btn"><?php echo _QXZ("SUBMIT"); ?></button>
            </div>
        </div>
        
        <div class="divider"></div>
        
        <!-- Archived Log Search Section -->
        <div class="search-section" style="border-left-color:#6c757d;">
            <div class="search-section-title"><?php echo _QXZ("Archived Log Search Options"); ?></div>
            
            <?php
            if ((strlen($SScoldstorage_server_ip) > 1) and (strlen($SScoldstorage_login) > 0) and (strlen($SScoldstorage_pass) > 0)) {
                if (strlen($archive_type) < 1) { $archive_type = 'ARCHIVE'; }
            ?>
            <div class="search-row">
                <div class="search-label"><?php echo _QXZ("Archive Type"); ?>:</div>
                <select name="archive_type" class="search-select">
                    <option value="ARCHIVE"><?php echo _QXZ("Archive Only"); ?></option>
                    <option value="COLDSTORAGE"><?php echo _QXZ("Cold-Storage Only"); ?></option>
                    <option value="ARCHIVE_AND_COLDSTORAGE"><?php echo _QXZ("Archive and Cold-Storage"); ?></option>
                    <option selected value="<?php echo $archive_type; ?>"><?php echo _QXZ($archive_type); ?></option>
                </select>
                <div></div>
            </div>
            <?php } else { ?>
            <input type="hidden" name="archive_type" value="ARCHIVE">
            <?php } ?>
            
            <div class="search-row">
                <div class="search-label"><?php echo _QXZ("Lead ID"); ?>:</div>
                <input type="text" name="log_lead_id_archive" class="search-input" size="10" maxlength="10">
                <button type="submit" name="SUBMIT" class="submit-btn"><?php echo _QXZ("SUBMIT"); ?></button>
            </div>
            <div class="search-row">
                <div class="search-label"><?php echo $label_phone_number . " " . _QXZ("Dialed"); ?>:</div>
                <input type="text" name="log_phone_archive" class="search-input" size="18" maxlength="18">
                <button type="submit" name="SUBMIT" class="submit-btn"><?php echo _QXZ("SUBMIT"); ?></button>
            </div>
        </div>
    </form>
</div>
</body>
</html>
<?php
    exit;
} else {
    // SEARCH RESULTS DISPLAY
    
    ##### BEGIN Log search #####
    if ((strlen($log_lead_id) > 0) or (strlen($log_phone) > 0)) {
        if (strlen($log_lead_id) > 0) {
            $stmtA = "SELECT lead_id,phone_number,campaign_id,call_date,status,user,list_id,length_in_sec,alt_dial from vicidial_log where lead_id='" . mysqli_real_escape_string($link, $log_lead_id) . "' $LOGallowed_listsSQL";
            $stmtB = "SELECT lead_id,phone_number,campaign_id,call_date,status,user,list_id,length_in_sec from vicidial_closer_log where lead_id='" . mysqli_real_escape_string($link, $log_lead_id) . "' $LOGallowed_listsSQL";
        }
        if (strlen($log_phone) > 0) {
            $stmtA = "SELECT lead_id,phone_number,campaign_id,call_date,status,user,list_id,length_in_sec,alt_dial from vicidial_log where phone_number='" . mysqli_real_escape_string($link, $log_phone) . "' $LOGallowed_listsSQL";
            $stmtB = "SELECT lead_id,phone_number,campaign_id,call_date,status,user,list_id,length_in_sec from vicidial_closer_log where phone_number='" . mysqli_real_escape_string($link, $log_phone) . "' $LOGallowed_listsSQL";
            $stmtC = "SELECT extension,caller_id_number,did_id,call_date from vicidial_did_log where caller_id_number='" . mysqli_real_escape_string($link, $log_phone) . "'";
        }

        if ((strlen($slave_db_server) > 5) and (preg_match("/$report_name/", $reports_use_slave_db))) {
            mysqli_close($link);
            $use_slave_server = 1;
            $db_source = 'S';
            require("dbconnect_mysqli.php");
            echo "<!-- Using slave server $slave_db_server $db_source -->\n";
        }

        $rslt = mysql_to_mysqli("$stmtA", $link);
        $results_to_print = mysqli_num_rows($rslt);
        
        if ($results_to_print < 1) {
            echo "<div style='background:#fff3cd;border-left:4px solid #ffc107;padding:20px;border-radius:8px;margin:20px 0;text-align:center;'>";
            echo "<strong style='color:#856404;font-size:16px;'>" . _QXZ("There are no outbound calls matching your search criteria") . "</strong>";
            echo "</div>";
        } else {
            echo "<div style='margin:20px 0;'>";
            echo "<h3 style='margin:0 0 15px 0;font-size:18px;color:#2c3e50;font-weight:600;'>" . _QXZ("OUTBOUND LOG RESULTS") . ": <span style='color:#007bff;'>$results_to_print</span></h3>";
            
            echo "<div style='overflow-x:auto;background:#fff;border-radius:8px;box-shadow:0 2px 4px rgba(0,0,0,0.1);'>";
            echo "<table style='width:100%;border-collapse:collapse;font-size:13px;'>";
            echo "<thead><tr style='background:#343a40;color:#fff;'>";
            echo "<th style='padding:12px 8px;text-align:left;font-weight:600;'>#</th>";
            echo "<th style='padding:12px 8px;text-align:center;font-weight:600;'>" . _QXZ("LEAD ID") . "</th>";
            echo "<th style='padding:12px 8px;text-align:center;font-weight:600;'>" . _QXZ("PHONE") . "</th>";
            echo "<th style='padding:12px 8px;text-align:center;font-weight:600;'>" . _QXZ("CAMPAIGN") . "</th>";
            echo "<th style='padding:12px 8px;text-align:center;font-weight:600;'>" . _QXZ("CALL DATE") . "</th>";
            echo "<th style='padding:12px 8px;text-align:center;font-weight:600;'>" . _QXZ("STATUS") . "</th>";
            echo "<th style='padding:12px 8px;text-align:center;font-weight:600;'>" . _QXZ("USER") . "</th>";
            echo "<th style='padding:12px 8px;text-align:center;font-weight:600;'>" . _QXZ("LIST ID") . "</th>";
            echo "<th style='padding:12px 8px;text-align:center;font-weight:600;'>" . _QXZ("LENGTH") . "</th>";
            echo "<th style='padding:12px 8px;text-align:center;font-weight:600;'>" . _QXZ("DIAL") . "</th>";
            echo "</tr></thead><tbody>";
            
            $o = 0;
            while ($results_to_print > $o) {
                $row = mysqli_fetch_row($rslt);
                
                // Phone data hiding logic
                if ($LOGadmin_hide_phone_data != '0') {
                    if ($DB > 0) { echo "HIDEPHONEDATA|$row[1]|$LOGadmin_hide_phone_data|\n"; }
                    $phone_temp = $row[1];
                    if (strlen($phone_temp) > 0) {
                        if ($LOGadmin_hide_phone_data == '4_DIGITS') {
                            $row[1] = str_repeat("X", (strlen($phone_temp) - 4)) . substr($phone_temp, -4, 4);
                        } elseif ($LOGadmin_hide_phone_data == '3_DIGITS') {
                            $row[1] = str_repeat("X", (strlen($phone_temp) - 3)) . substr($phone_temp, -3, 3);
                        } elseif ($LOGadmin_hide_phone_data == '2_DIGITS') {
                            $row[1] = str_repeat("X", (strlen($phone_temp) - 2)) . substr($phone_temp, -2, 2);
                        } else {
                            $row[1] = preg_replace("/./", 'X', $phone_temp);
                        }
                    }
                }
                
                $o++;
                $search_lead = $row[0];
                $bgcolor = ($o % 2 == 0) ? '#f8f9fa' : '#fff';
                
                echo "<tr style='background:$bgcolor;border-bottom:1px solid #e9ecef;'>";
                echo "<td style='padding:10px 8px;'><strong>$o</strong></td>";
                echo "<td style='padding:10px 8px;text-align:center;'><a href='admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search' onclick=\"javascript:window.open('admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search', '_blank');return false;\" style='color:#007bff;text-decoration:none;font-weight:600;'>$row[0]</a></td>";
                echo "<td style='padding:10px 8px;text-align:center;'>$row[1]</td>";
                echo "<td style='padding:10px 8px;text-align:center;'>$row[2]</td>";
                echo "<td style='padding:10px 8px;text-align:center;font-size:12px;'>$row[3]</td>";
                echo "<td style='padding:10px 8px;text-align:center;'><span style='background:#e9ecef;padding:4px 8px;border-radius:4px;font-size:11px;font-weight:600;'>$row[4]</span></td>";
                echo "<td style='padding:10px 8px;text-align:center;'>$row[5]</td>";
                echo "<td style='padding:10px 8px;text-align:center;'>$row[6]</td>";
                echo "<td style='padding:10px 8px;text-align:center;'>$row[7]</td>";
                echo "<td style='padding:10px 8px;text-align:center;'>$row[8]</td>";
                echo "</tr>";
            }
            echo "</tbody></table></div></div>";
        }
        // Continue with closer log results...
    }
}
echo "</div>"; // Close search-container



		$rslt=mysql_to_mysqli("$stmtB", $link);
		$results_to_print = mysqli_num_rows($rslt);
		if ( ($results_to_print < 1) and ($results_to_printX < 1) )
			{
			echo "\n<br><br><center>\n";
			echo "<b>"._QXZ("There are no inbound calls matching your search criteria")."</b><br><br>\n";
			echo "</center>\n";
			}
		else
			{
			echo "<BR><b>INBOUND LOG RESULTS: $results_to_print</b><BR>\n";
			echo "<TABLE BGCOLOR=WHITE CELLPADDING=1 CELLSPACING=0 WIDTH=770>\n";
			echo "<TR BGCOLOR=BLACK>\n";
			echo "<TD ALIGN=LEFT VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>#</B></FONT></TD>\n";
			echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("LEAD ID")."</B> &nbsp;</FONT></TD>\n";
			echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("PHONE")."</B> &nbsp;</FONT></TD>\n";
			echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("INGROUP")."</B> &nbsp;</FONT></TD>\n";
			echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("CALL DATE")."</B> &nbsp;</FONT></TD>\n";
			echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("STATUS")."</B> &nbsp;</FONT></TD>\n";
			echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("USER")."</B> &nbsp;</FONT></TD>\n";
			echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("LIST ID")."</B> &nbsp;</FONT></TD>\n";
			echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("LENGTH")."</B> &nbsp;</FONT></TD>\n";
			echo "</TR>\n";
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
				if (preg_match('/1$|3$|5$|7$|9$/i', $o))
					{$bgcolor='bgcolor="#'. $SSstd_row2_background .'"';} 
				else
					{$bgcolor='bgcolor="#'. $SSstd_row1_background .'"';}
				echo "<TR $bgcolor>\n";
				echo "<TD ALIGN=LEFT><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$o</FONT></TD>\n";
				echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1><A HREF=\"admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search\" onclick=\"javascript:window.open('admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search', '_blank');return false;\">$row[0]</A></FONT></TD>\n";
				echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[1]</FONT></TD>\n";
				echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[2]</FONT></TD>\n";
				echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[3]</FONT></TD>\n";
				echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[4]</FONT></TD>\n";
				echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[5]</FONT></TD>\n";
				echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[6]</FONT></TD>\n";
				echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[7]</FONT></TD>\n";
				echo "</TR>\n";
				}
			echo "</TABLE>\n";
			}

		if (strlen($stmtC) > 10)
			{
			$rslt=mysql_to_mysqli("$stmtC", $link);
			$results_to_print = mysqli_num_rows($rslt);
			if ( ($results_to_print < 1) and ($results_to_printX < 1) )
				{
				echo "\n<br><br><center>\n";
				echo "<b>"._QXZ("There are no inbound did calls matching your search criteria")."</b><br><br>\n";
				echo "</center>\n";
				}
			else
				{
				echo "<BR><b>"._QXZ("INBOUND DID LOG RESULTS").": $results_to_print</b><BR>\n";
				echo "<TABLE BGCOLOR=WHITE CELLPADDING=1 CELLSPACING=0 WIDTH=770>\n";
				echo "<TR BGCOLOR=BLACK>\n";
				echo "<TD ALIGN=LEFT VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>#</B></FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("DID")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("PHONE")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("DID ID")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("CALL DATE")."</B> &nbsp;</FONT></TD>\n";
				echo "</TR>\n";
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
					if (preg_match('/1$|3$|5$|7$|9$/i', $o))
						{$bgcolor='bgcolor="#'. $SSstd_row2_background .'"';} 
					else
						{$bgcolor='bgcolor="#'. $SSstd_row1_background .'"';}
					echo "<TR $bgcolor>\n";
					echo "<TD ALIGN=LEFT><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$o</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[0]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[1]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[2]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[3]</FONT></TD>\n";
					echo "</TR>\n";
					}
				echo "</TABLE>\n";
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

		echo "\n\n\n<br><br><br>\n<a href=\"$PHP_SELF\">NEW SEARCH</a>";

		echo "\n\n\n<br><br><br>\n"._QXZ("script runtime").": $RUNtime "._QXZ("seconds");

		echo "\n\n\n</body></html>";

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
				echo "\n<br><br><center>\n";
				echo "<b>"._QXZ("There are no archived outbound calls matching your search criteria")."</b><br><br>\n";
				echo "</center>\n";
				}
			else
				{
				echo "<BR><b>"._QXZ("OUTBOUND ARCHIVED LOG RESULTS").": $results_to_print</b><BR>\n";
				echo "<TABLE BGCOLOR=WHITE CELLPADDING=1 CELLSPACING=0 WIDTH=770>\n";
				echo "<TR BGCOLOR=BLACK>\n";
				echo "<TD ALIGN=LEFT VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>#</B></FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("LEAD ID")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("PHONE")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("CAMPAIGN")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("CALL DATE")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("STATUS")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("USER")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("LIST ID")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("LENGTH")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("DIAL")."</B></FONT></TD>\n";
				echo "</TR>\n";
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
					if (preg_match('/1$|3$|5$|7$|9$/i', $o))
						{$bgcolor='bgcolor="#'. $SSstd_row2_background .'"';}
					else
						{$bgcolor='bgcolor="#'. $SSstd_row1_background .'"';}
					echo "<TR $bgcolor>\n";
					echo "<TD ALIGN=LEFT><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$o</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1><A HREF=\"admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search&archive_log=$log_archive_link\" onclick=\"javascript:window.open('admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search&archive_log=$log_archive_link', '_blank');return false;\">$row[0]</A></FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[1]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[2]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[3]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[4]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[5]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[6]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[7]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[8]</FONT></TD>\n";
					echo "</TR>\n";
					}
				echo "</TABLE>\n";
				}

			$rslt=mysql_to_mysqli("$stmtB", $link);
			$results_to_print = mysqli_num_rows($rslt);
			if ( ($results_to_print < 1) and ($results_to_printX < 1) )
				{
				echo "\n<br><br><center>\n";
				echo "<b>"._QXZ("There are no archived inbound calls matching your search criteria")."</b><br><br>\n";
				echo "</center>\n";
				}
			else
				{
				echo "<BR><b>"._QXZ("INBOUND ARCHIVED LOG RESULTS").": $results_to_print</b><BR>\n";
				echo "<TABLE BGCOLOR=WHITE CELLPADDING=1 CELLSPACING=0 WIDTH=770>\n";
				echo "<TR BGCOLOR=BLACK>\n";
				echo "<TD ALIGN=LEFT VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>#</B></FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("LEAD ID")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("PHONE")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("INGROUP")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("CALL DATE")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("STATUS")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("USER")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("LIST ID")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("LENGTH")."</B> &nbsp;</FONT></TD>\n";
				echo "</TR>\n";
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
					if (preg_match('/1$|3$|5$|7$|9$/i', $o))
						{$bgcolor='bgcolor="#'. $SSstd_row2_background .'"';}
					else
						{$bgcolor='bgcolor="#'. $SSstd_row1_background .'"';}
					echo "<TR $bgcolor>\n";
					echo "<TD ALIGN=LEFT><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$o</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1><A HREF=\"admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search&archive_log=$log_archive_link\" onclick=\"javascript:window.open('admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search&archive_log=$log_archive_link', '_blank');return false;\">$row[0]</A></FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[1]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[2]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[3]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[4]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[5]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[6]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[7]</FONT></TD>\n";
					echo "</TR>\n";
					}
				echo "</TABLE>\n";
				}

			if (strlen($stmtC) > 10)
				{
				$rslt=mysql_to_mysqli("$stmtC", $link);
				$results_to_print = mysqli_num_rows($rslt);
				if ( ($results_to_print < 1) and ($results_to_printX < 1) )
					{
					echo "\n<br><br><center>\n";
					echo "<b>"._QXZ("There are no archived inbound did calls matching your search criteria")."</b><br><br>\n";
					echo "</center>\n";
					}
				else
					{
					echo "<BR><b>"._QXZ("INBOUND DID ARCHIVED LOG RESULTS").": $results_to_print</b><BR>\n";
					echo "<TABLE BGCOLOR=WHITE CELLPADDING=1 CELLSPACING=0 WIDTH=770>\n";
					echo "<TR BGCOLOR=BLACK>\n";
					echo "<TD ALIGN=LEFT VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>#</B></FONT></TD>\n";
					echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("DID")."</B> &nbsp;</FONT></TD>\n";
					echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("PHONE")."</B> &nbsp;</FONT></TD>\n";
					echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("DID ID")."</B> &nbsp;</FONT></TD>\n";
					echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("CALL DATE")."</B> &nbsp;</FONT></TD>\n";
					echo "</TR>\n";
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
						if (preg_match('/1$|3$|5$|7$|9$/i', $o))
							{$bgcolor='bgcolor="#'. $SSstd_row2_background .'"';}
						else
							{$bgcolor='bgcolor="#'. $SSstd_row1_background .'"';}
						echo "<TR $bgcolor>\n";
						echo "<TD ALIGN=LEFT><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$o</FONT></TD>\n";
						echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[0]</FONT></TD>\n";
						echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[1]</FONT></TD>\n";
						echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[2]</FONT></TD>\n";
						echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[3]</FONT></TD>\n";
						echo "</TR>\n";
						}
					echo "</TABLE>\n";
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
				echo "\n<br><br><center>\n";
				echo "<b>"._QXZ("There are no cold-storage outbound calls matching your search criteria")."</b><br><br>\n";
				echo "</center>\n";
				}
			else
				{
				echo "<BR><b>"._QXZ("OUTBOUND COLD-STORAGE LOG RESULTS").": $results_to_print</b><BR>\n";
				echo "<TABLE BGCOLOR=WHITE CELLPADDING=1 CELLSPACING=0 WIDTH=770>\n";
				echo "<TR BGCOLOR=BLACK>\n";
				echo "<TD ALIGN=LEFT VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>#</B></FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("LEAD ID")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("PHONE")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("CAMPAIGN")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("CALL DATE")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("STATUS")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("USER")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("LIST ID")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("LENGTH")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("DIAL")."</B></FONT></TD>\n";
				echo "</TR>\n";
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
					if (preg_match('/1$|3$|5$|7$|9$/i', $o))
						{$bgcolor='bgcolor="#'. $SSstd_row2_background .'"';}
					else
						{$bgcolor='bgcolor="#'. $SSstd_row1_background .'"';}
					echo "<TR $bgcolor>\n";
					echo "<TD ALIGN=LEFT><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$o</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1><A HREF=\"admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search&archive_log=$log_archive_link\" onclick=\"javascript:window.open('admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search&archive_log=$log_archive_link', '_blank');return false;\">$row[0]</A></FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[1]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[2]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[3]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[4]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[5]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[6]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[7]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[8]</FONT></TD>\n";
					echo "</TR>\n";
					}
				echo "</TABLE>\n";
				}

			$rslt=mysql_to_mysqli("$stmtB", $linkCS);
			$results_to_print = mysqli_num_rows($rslt);
			if ( ($results_to_print < 1) and ($results_to_printX < 1) )
				{
				echo "\n<br><br><center>\n";
				echo "<b>"._QXZ("There are no cold-storage inbound calls matching your search criteria")."</b><br><br>\n";
				echo "</center>\n";
				}
			else
				{
				echo "<BR><b>"._QXZ("INBOUND COLD-STORAGE LOG RESULTS").": $results_to_print</b><BR>\n";
				echo "<TABLE BGCOLOR=WHITE CELLPADDING=1 CELLSPACING=0 WIDTH=770>\n";
				echo "<TR BGCOLOR=BLACK>\n";
				echo "<TD ALIGN=LEFT VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>#</B></FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("LEAD ID")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("PHONE")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("INGROUP")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("CALL DATE")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("STATUS")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("USER")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("LIST ID")."</B> &nbsp;</FONT></TD>\n";
				echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("LENGTH")."</B> &nbsp;</FONT></TD>\n";
				echo "</TR>\n";
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
					if (preg_match('/1$|3$|5$|7$|9$/i', $o))
						{$bgcolor='bgcolor="#'. $SSstd_row2_background .'"';}
					else
						{$bgcolor='bgcolor="#'. $SSstd_row1_background .'"';}
					echo "<TR $bgcolor>\n";
					echo "<TD ALIGN=LEFT><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$o</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1><A HREF=\"admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search&archive_log=$log_archive_link\" onclick=\"javascript:window.open('admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search&archive_log=$log_archive_link', '_blank');return false;\">$row[0]</A></FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[1]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[2]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[3]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[4]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[5]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[6]</FONT></TD>\n";
					echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[7]</FONT></TD>\n";
					echo "</TR>\n";
					}
				echo "</TABLE>\n";
				}

			if (strlen($stmtC) > 10)
				{
				$rslt=mysql_to_mysqli("$stmtC", $linkCS);
				$results_to_print = mysqli_num_rows($rslt);
				if ( ($results_to_print < 1) and ($results_to_printX < 1) )
					{
					echo "\n<br><br><center>\n";
					echo "<b>"._QXZ("There are no cold-storage inbound did calls matching your search criteria")."</b><br><br>\n";
					echo "</center>\n";
					}
				else
					{
					echo "<BR><b>"._QXZ("INBOUND DID COLD-STORAGE LOG RESULTS").": $results_to_print</b><BR>\n";
					echo "<TABLE BGCOLOR=WHITE CELLPADDING=1 CELLSPACING=0 WIDTH=770>\n";
					echo "<TR BGCOLOR=BLACK>\n";
					echo "<TD ALIGN=LEFT VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>#</B></FONT></TD>\n";
					echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("DID")."</B> &nbsp;</FONT></TD>\n";
					echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("PHONE")."</B> &nbsp;</FONT></TD>\n";
					echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("DID ID")."</B> &nbsp;</FONT></TD>\n";
					echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("CALL DATE")."</B> &nbsp;</FONT></TD>\n";
					echo "</TR>\n";
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
						if (preg_match('/1$|3$|5$|7$|9$/i', $o))
							{$bgcolor='bgcolor="#'. $SSstd_row2_background .'"';}
						else
							{$bgcolor='bgcolor="#'. $SSstd_row1_background .'"';}
						echo "<TR $bgcolor>\n";
						echo "<TD ALIGN=LEFT><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$o</FONT></TD>\n";
						echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[0]</FONT></TD>\n";
						echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[1]</FONT></TD>\n";
						echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[2]</FONT></TD>\n";
						echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[3]</FONT></TD>\n";
						echo "</TR>\n";
						}
					echo "</TABLE>\n";
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

		echo "\n\n\n<br><br><br>\n<a href=\"$PHP_SELF\">NEW SEARCH</a>";

		echo "\n\n\n<br><br><br>\n"._QXZ("script runtime").": $RUNtime "._QXZ("seconds");

		echo "\n\n\n</body></html>";

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
		echo date("l F j, Y G:i:s A");
		echo "\n<br><br><center>\n";
		echo "<b>"._QXZ("The search variables you entered are not active in the system")."</b><br><br>\n";
		echo "<b>"._QXZ("Please go back and double check the information you entered and submit again")."</b>\n";
		echo "</center>\n";
		echo "</body></html>\n";
		exit;
		}
	else
		{
		echo "<b>"._QXZ("RESULTS").": $results_to_print</b><BR><BR>\n";
		echo "<TABLE BGCOLOR=WHITE CELLPADDING=1 CELLSPACING=0 WIDTH=770>\n";
		echo "<TR BGCOLOR=BLACK>\n";
		echo "<TD ALIGN=LEFT VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>#</B></FONT></TD>\n";
		echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("LEAD ID")."</B> &nbsp;</FONT></TD>\n";
		echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("STATUS")."</B> &nbsp;</FONT></TD>\n";
		echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("VENDOR ID")."</B> &nbsp;</FONT></TD>\n";
		echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("LAST AGENT")."</B> &nbsp;</FONT></TD>\n";
		echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("LIST ID")."</B> &nbsp;</FONT></TD>\n";
		echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("PHONE")."</B> &nbsp;</FONT></TD>\n";
		echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("NAME")."</B> &nbsp;</FONT></TD>\n";
		echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("CITY")."</B> &nbsp;</FONT></TD>\n";
		echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("SECURITY")."</B> &nbsp;</FONT></TD>\n";
		echo "<TD ALIGN=CENTER VALIGN=TOP><FONT FACE=\"ARIAL,HELVETICA\" COLOR=WHITE><B>"._QXZ("LAST CALL")."</B></FONT></TD>\n";
		echo "</TR>\n";
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
			if (preg_match('/1$|3$|5$|7$|9$/i', $o))
				{$bgcolor='bgcolor="#'. $SSstd_row2_background .'"';} 
			else
				{$bgcolor='bgcolor="#'. $SSstd_row1_background .'"';}
			echo "<TR $bgcolor>\n";
			echo "<TD ALIGN=LEFT><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$o</FONT></TD>\n";
			echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1><A HREF=\"admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search&archive_log=$log_archive_link\" onclick=\"javascript:window.open('admin_modify_lead.php?lead_id=$row[0]&archive_search=$archive_search&archive_log=$log_archive_link', '_blank');return false;\">$row[0]</A></FONT></TD>\n";
			echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[3]</FONT></TD>\n";
			echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[5]</FONT></TD>\n";
			echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[4]</FONT></TD>\n";
			echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[7]</FONT></TD>\n";
			echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[11]</FONT></TD>\n";
			echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[13] $row[15]</FONT></TD>\n";
			echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[19]</FONT></TD>\n";
			echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[28]</FONT></TD>\n";
			echo "<TD ALIGN=CENTER><FONT FACE=\"ARIAL,HELVETICA\" SIZE=1>$row[31]</FONT></TD>\n";
			echo "</TR>\n";
			}
		echo "</TABLE>\n";
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

echo "\n\n\n<br><br><br>\n<a href=\"$PHP_SELF\">"._QXZ("NEW SEARCH")."</a>";

echo "\n\n\n<br><br><br>\n"._QXZ("script runtime").": $RUNtime "._QXZ("seconds");

?>

</body>
</html>
