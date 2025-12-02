<?php
# admin_listloader_fourth_gen.php - version 2.14

 $version = '2.14-81';
 $build = '240801-1131';

require("dbconnect_mysqli.php");
require("functions.php");

 $enable_status_mismatch_leadloader_option = 0;

if (file_exists('options.php')) {
    require('options.php');
}

 $US = '_';
 $MT[0] = '';

 $PHP_AUTH_USER = $_SERVER['PHP_AUTH_USER'];
 $PHP_AUTH_PW = $_SERVER['PHP_AUTH_PW'];
 $PHP_SELF = $_SERVER['PHP_SELF'];
 $PHP_SELF = preg_replace('/\.php.*/i', '.php', $PHP_SELF);
 $leadfile = $_FILES["leadfile"];
 $LF_orig = $_FILES['leadfile']['name'];
 $LF_path = $_FILES['leadfile']['tmp_name'];

// Process form submissions
 $formFields = [
    'submit_file', 'submit', 'SUBMIT', 'leadfile_name', 'file_layout', 'OK_to_process',
    'vendor_lead_code_field', 'source_id_field', 'list_id_field', 'phone_code_field',
    'phone_number_field', 'title_field', 'first_name_field', 'middle_initial_field',
    'last_name_field', 'address1_field', 'address2_field', 'address3_field', 'city_field',
    'state_field', 'province_field', 'postal_code_field', 'country_code_field', 'gender_field',
    'date_of_birth_field', 'alt_phone_field', 'email_field', 'security_phrase_field',
    'comments_field', 'rank_field', 'owner_field', 'list_id_override', 'master_list_override',
    'lead_file', 'dupcheck', 'dedupe_statuses', 'dedupe_statuses_override', 'status_mismatch_action',
    'postalgmt', 'phone_code_override', 'DB', 'template_id', 'usacan_check', 'state_conversion',
    'web_loader_phone_length', 'international_dnc_scrub'
];

foreach ($formFields as $field) {
    if (isset($_GET[$field])) {
        $$field = $_GET[$field];
    } elseif (isset($_POST[$field])) {
        $$field = $_POST[$field];
    }
}

// Special handling for file upload
if (isset($_FILES["leadfile"])) {
    $leadfile_name = $_FILES['leadfile']['name'];
}

// Sanitize numeric fields
 $list_id_override = preg_replace('/\D/', '', $list_id_override);
 $phone_code_override = preg_replace('/\D/', '', $phone_code_override);

// Clear override values if set to "in_file"
if ($list_id_override == "in_file") { 
    $list_id_override = ""; 
}
if ($phone_code_override == "in_file") { 
    $phone_code_override = ""; 
}

// Field regex pattern
 $field_regx = "[\"`\\;]";

 $vicidial_list_fields = '|lead_id|vendor_lead_code|source_id|list_id|gmt_offset_now|called_since_last_reset|phone_code|phone_number|title|first_name|middle_initial|last_name|address1|address2|address3|city|state|province|postal_code|country_code|gender|date_of_birth|alt_phone|email|security_phrase|comments|called_count|last_local_call_time|rank|owner|entry_list_id|';

// System settings lookup
 $stmt = "SELECT use_non_latin,admin_web_directory,custom_fields_enabled,webroot_writable,enable_languages,language_method,active_modules,admin_screen_colors,web_loader_phone_length,enable_international_dncs,web_loader_phone_strip,allow_web_debug FROM system_settings;";
 $rslt = mysql_to_mysqli($stmt, $link);
if ($DB) {
    echo "$stmt\n";
}
 $qm_conf_ct = mysqli_num_rows($rslt);
if ($qm_conf_ct > 0) {
    $row = mysqli_fetch_row($rslt);
    $non_latin = $row[0];
    $admin_web_directory = $row[1];
    $custom_fields_enabled = $row[2];
    $webroot_writable = $row[3];
    $SSenable_languages = $row[4];
    $SSlanguage_method = $row[5];
    $SSactive_modules = $row[6];
    $SSadmin_screen_colors = $row[7];
    $SSweb_loader_phone_length = $row[8];
    $SSenable_international_dncs = $row[9];
    $SSweb_loader_phone_strip = $row[10];
    $SSallow_web_debug = $row[11];
}
if ($SSallow_web_debug < 1) {
    $DB = 0;
}

// Sanitize variables
 $sanitizeFields = [
    'list_id_override', 'phone_code_override', 'web_loader_phone_length', 'international_dnc_scrub',
    'master_list_override', 'usacan_check', 'state_conversion', 'status_mismatch_action',
    'postalgmt', 'dupcheck', 'lead_file', 'vendor_lead_code_field', 'source_id_field',
    'list_id_field', 'phone_code_field', 'phone_number_field', 'title_field', 'first_name_field',
    'middle_initial_field', 'last_name_field', 'address1_field', 'address2_field', 'address3_field',
    'city_field', 'state_field', 'province_field', 'postal_code_field', 'country_code_field',
    'gender_field', 'date_of_birth_field', 'alt_phone_field', 'email_field', 'security_phrase_field',
    'comments_field', 'rank_field', 'owner_field', 'submit_file', 'submit', 'SUBMIT',
    'file_layout', 'OK_to_process'
];

foreach ($sanitizeFields as $field) {
    if (isset($$field)) {
        $$field = preg_replace('/[^-_0-9a-zA-Z]/', '', $$field);
    }
}

// Special sanitization for file name
if (isset($lead_file)) {
    $lead_file = preg_replace("/\<|\>|\'|\"|\\\\|;/", "", $lead_file);
}

// Handle dedupe_statuses array
if (is_array($dedupe_statuses)) {
    if (count($dedupe_statuses) > 0) {
        for ($ds = 0; $ds < count($dedupe_statuses); $ds++) {
            $dedupe_statuses[$ds] = preg_replace('/[^-_0-9\p{L}]/u', '', $dedupe_statuses[$ds]);
        }
    }
} else {
    $dedupe_statuses = array();
}

if (strlen($dedupe_statuses_override) > 0) {
    $dedupe_statuses_override = preg_replace('/[^- \,\_0-9a-zA-Z]/', '', $dedupe_statuses_override);
    $dedupe_statuses = explode(",", $dedupe_statuses_override);
}

// Handle non-latin characters
if ($non_latin < 1) {
    $PHP_AUTH_USER = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_USER);
    $PHP_AUTH_PW = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_PW);
    $template_id = preg_replace('/[^-_0-9a-zA-Z]/', '', $template_id);
} else {
    $PHP_AUTH_USER = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_USER);
    $PHP_AUTH_PW = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_PW);
    $template_id = preg_replace('/[^-_0-9\p{L}]/u', '', $template_id);
}

// Time and date variables
 $STARTtime = date("U");
 $TODAY = date("Y-m-d");
 $NOW_TIME = date("Y-m-d H:i:s");
 $FILE_datetime = $STARTtime;
 $date = date("r");
 $ip = getenv("REMOTE_ADDR");
 $browser = getenv("HTTP_USER_AGENT");

// Set character set if non-latin
if ($non_latin > 0) {
    $rslt = mysql_to_mysqli("SET NAMES 'UTF8'", $link);
}

// Get user language preference
 $stmt = "SELECT selected_language from vicidial_users where user='$PHP_AUTH_USER';";
if ($DB) {
    echo "|$stmt|\n";
}
 $rslt = mysql_to_mysqli($stmt, $link);
 $sl_ct = mysqli_num_rows($rslt);
if ($sl_ct > 0) {
    $row = mysqli_fetch_row($rslt);
    $VUselected_language = $row[0];
}

// User authentication
 $auth = 0;
 $auth_message = user_authorization($PHP_AUTH_USER, $PHP_AUTH_PW, '', 1, 0);
if (($auth_message == 'GOOD') || ($auth_message == '2FA')) {
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
 $stmt = "SELECT load_leads,user_group from vicidial_users where user='$PHP_AUTH_USER';";
 $rslt = mysql_to_mysqli($stmt, $link);
 $row = mysqli_fetch_row($rslt);
 $LOGload_leads = $row[0];
 $LOGuser_group = $row[1];

if ($LOGload_leads < 1) {
    Header("Content-type: text/html; charset=utf-8");
    echo _QXZ("You do not have permissions to load leads") . "\n";
    exit;
}

// Validate file name
if (preg_match("/;|:|\/|\^|\[|\]|\"|\'|\*/", $LF_orig)) {
    echo _QXZ("ERROR: Invalid File Name") . ":: $LF_orig\n";
    exit;
}

// Handle file upload errors
 $upload_error = $_FILES['leadfile']['error'];
if ($upload_error != UPLOAD_ERR_OK) {
    $errorMessages = [
        UPLOAD_ERR_INI_SIZE => "ERROR: The uploaded file exceeds the maximum upload size of " . ini_get("upload_max_filesize") . " set for your system.",
        UPLOAD_ERR_FORM_SIZE => "ERROR: The uploaded file exceeds the MAX_FILE_SIZE directive for this HTML form.",
        UPLOAD_ERR_PARTIAL => "ERROR: The uploaded file was only partially uploaded.",
        UPLOAD_ERR_NO_FILE => "ERROR: No file was uploaded.",
        UPLOAD_ERR_NO_TMP_DIR => "ERROR: A temporary directory is missing. Review your system configuration.",
        UPLOAD_ERR_CANT_WRITE => "ERROR: Failed to write the uploaded file to disk.",
        UPLOAD_ERR_EXTENSION => "ERROR: An unknown php extension has stopped the file upload. Review your system configuration."
    ];
    
    if (isset($errorMessages[$upload_error])) {
        echo $errorMessages[$upload_error];
    } else {
        echo "ERROR: Unknown file upload error occurred.";
    }
    exit;
}

// Get user group permissions
 $stmt = "SELECT allowed_campaigns,allowed_reports,admin_viewable_groups,admin_viewable_call_times from vicidial_user_groups where user_group='$LOGuser_group';";
if ($DB) {
    echo "|$upload_error|<BR>\n|$stmt|\n";
}
 $rslt = mysql_to_mysqli($stmt, $link);
 $row = mysqli_fetch_row($rslt);
 $LOGallowed_campaigns = $row[0];
 $LOGallowed_reports = $row[1];
 $LOGadmin_viewable_groups = $row[2];
 $LOGadmin_viewable_call_times = $row[3];

// Build campaign filters
 $camp_lists = '';
 $LOGallowed_campaignsSQL = '';
 $whereLOGallowed_campaignsSQL = '';
if (!preg_match('/\-ALL/i', $LOGallowed_campaigns)) {
    $rawLOGallowed_campaignsSQL = preg_replace("/ -/", '', $LOGallowed_campaigns);
    $rawLOGallowed_campaignsSQL = preg_replace("/ /", "','", $rawLOGallowed_campaignsSQL);
    $LOGallowed_campaignsSQL = "and campaign_id IN('$rawLOGallowed_campaignsSQL')";
    $whereLOGallowed_campaignsSQL = "where campaign_id IN('$rawLOGallowed_campaignsSQL')";
}
 $regexLOGallowed_campaigns = " $LOGallowed_campaigns ";

// Set up admin directory and help variables
 $script_name = getenv("SCRIPT_NAME");
 $server_name = getenv("SERVER_NAME");
 $server_port = getenv("SERVER_PORT");
if (preg_match("/443/i", $server_port)) {
    $HTTPprotocol = 'https://';
} else {
    $HTTPprotocol = 'http://';
}
 $admDIR = "$HTTPprotocol$server_name$script_name";
 $admDIR = preg_replace('/admin_listloader_fourth_gen\.php/i', '', $admDIR);
 $admDIR = "/vicidial/";
 $admSCR = 'admin.php';
 $NWB = "<IMG SRC=\"help.png\" onClick=\"FillAndShowHelpDiv(event, '";
 $NWE = "')\" WIDTH=20 HEIGHT=20 BORDER=0 ALT=\"HELP\" ALIGN=TOP>";

// Time variables
 $secX = date("U");
 $hour = date("H");
 $min = date("i");
 $sec = date("s");
 $mon = date("m");
 $mday = date("d");
 $year = date("Y");
 $isdst = date("I");
 $Shour = date("H");
 $Smin = date("i");
 $Ssec = date("s");
 $Smon = date("m");
 $Smday = date("d");
 $Syear = date("Y");
 $pulldate0 = "$year-$mon-$mday $hour:$min:$sec";
 $inSD = $pulldate0;
 $dsec = ((($hour * 3600) + ($min * 60)) + $sec);

// Get server GMT value
 $stmt = "SELECT local_gmt FROM servers where server_ip = '$server_ip';";
 $rslt = mysql_to_mysqli($stmt, $link);
 $gmt_recs = mysqli_num_rows($rslt);
if ($gmt_recs > 0) {
    $row = mysqli_fetch_row($rslt);
    $DBSERVER_GMT = "$row[0]";
    if (strlen($DBSERVER_GMT) > 0) {
        $SERVER_GMT = $DBSERVER_GMT;
    }
    if ($isdst) {
        $SERVER_GMT++;
    }
} else {
    $SERVER_GMT = date("O");
    $SERVER_GMT = preg_replace('/\+/i', '', $SERVER_GMT);
    $SERVER_GMT = ($SERVER_GMT + 0);
    $SERVER_GMT = MathZDC($SERVER_GMT, 100);
}

 $LOCAL_GMT_OFF = $SERVER_GMT;
 $LOCAL_GMT_OFF_STD = $SERVER_GMT;

// Get dedupe status options
 $dedupe_status_select = '';
 $stmt = "SELECT status, status_name from vicidial_statuses order by status;";
 $rslt = mysql_to_mysqli($stmt, $link);
 $stat_num_rows = mysqli_num_rows($rslt);
 $snr_count = 0;
while ($stat_num_rows > $snr_count) {
    $row = mysqli_fetch_row($rslt);
    $dedupe_status_select .= "\t\t\t<option value='$row[0]'>$row[0] - $row[1]</option>\n";
    $snr_count++;
}

// Set default screen colors
 $SSmenu_background = '015B91';
 $SSframe_background = 'D9E6FE';
 $SSstd_row1_background = '9BB9FB';
 $SSstd_row2_background = 'B9CBFD';
 $SSstd_row3_background = '8EBCFD';
 $SSstd_row4_background = 'B6D3FC';
 $SSstd_row5_background = 'A3C3D6';
 $SSalt_row1_background = 'BDFFBD';
 $SSalt_row2_background = '99FF99';
 $SSalt_row3_background = 'CCFFCC';

// Get custom screen colors if set
if ($SSadmin_screen_colors != 'default') {
    $stmt = "SELECT menu_background,frame_background,std_row1_background,std_row2_background,std_row3_background,std_row4_background,std_row5_background,alt_row1_background,alt_row2_background,alt_row3_background FROM vicidial_screen_colors where colors_id='$SSadmin_screen_colors';";
    $rslt = mysql_to_mysqli($stmt, $link);
    if ($DB) {
        echo "$stmt\n";
    }
    $colors_ct = mysqli_num_rows($rslt);
    if ($colors_ct > 0) {
        $row = mysqli_fetch_row($rslt);
        $SSmenu_background = $row[0];
        $SSframe_background = $row[1];
        $SSstd_row1_background = $row[2];
        $SSstd_row2_background = $row[3];
        $SSstd_row3_background = $row[4];
        $SSstd_row4_background = $row[5];
        $SSstd_row5_background = $row[6];
        $SSalt_row1_background = $row[7];
        $SSalt_row2_background = $row[8];
        $SSalt_row3_background = $row[9];
    }
}
 $Mhead_color = $SSstd_row5_background;
 $Mmain_bgcolor = $SSmenu_background;
 $Mhead_color = $SSstd_row5_background;

header("Content-type: text/html; charset=utf-8");

echo "<!DOCTYPE html>\n";
echo "<html lang=\"en\">\n";
echo "<head>\n";
echo "<meta charset=\"UTF-8\">\n";
echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
echo "<meta http-equiv=\"X-UA-Compatible\" content=\"ie=edge\">\n";
echo "<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css\">\n";
echo "<link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css\">\n";
echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"vicidial_stylesheet.php\">\n";
echo "<script language=\"JavaScript\" src=\"help.js\"></script>\n";
echo "<div id='HelpDisplayDiv' class='help_info' style='display:none;'></div>";
echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=utf-8\">\n";
echo "<!-- VERSION: $version     BUILD: $build -->\n";
echo "<!-- SEED TIME  $secX:   $year-$mon-$mday $hour:$min:$sec  LOCAL GMT OFFSET NOW: $LOCAL_GMT_OFF  DST: $isdst -->\n";

function macfontfix($fontsize) {
    $browser = getenv("HTTP_USER_AGENT");
    $pctype = explode("(", $browser);
    if (preg_match('/Mac/', $pctype[1])) {
        /* Browser is a Mac. If not Netscape 6, raise fonts */
        $blownbrowser = explode('/', $browser);
        $ver = explode(' ', $blownbrowser[1]);
        $ver = $ver[0];
        if ($ver >= 5.0) return $fontsize; else return ($fontsize + 2);
    } else return $fontsize;    /* Browser is not a Mac - don't touch fonts */
}

echo "<style type=\"text/css\">\n";
echo "/* Modern UI Styles */\n";
echo "body {\n";
echo "    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;\n";
echo "    background-color: #f8f9fa;\n";
echo "    color: #333;\n";
echo "    line-height: 1.6;\n";
echo "}\n";
echo ".admin-container {\n";
echo "    max-width: 1200px;\n";
echo "    margin: 0 auto;\n";
echo "    padding: 20px;\n";
echo "}\n";
echo ".card {\n";
echo "    border: none;\n";
echo "    border-radius: 10px;\n";
echo "    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);\n";
echo "    margin-bottom: 20px;\n";
echo "}\n";
echo ".card-header {\n";
echo "    background-color: #015B91;\n";
echo "    color: white;\n";
echo "    border-radius: 10px 10px 0 0 !important;\n";
echo "    padding: 15px 20px;\n";
echo "    font-weight: 600;\n";
echo "}\n";
echo ".form-label {\n";
echo "    font-weight: 500;\n";
echo "    margin-bottom: 8px;\n";
echo "    color: #495057;\n";
echo "}\n";
echo ".form-control, .form-select {\n";
echo "    border-radius: 6px;\n";
echo "    border: 1px solid #ced4da;\n";
echo "    padding: 10px 12px;\n";
echo "    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;\n";
echo "}\n";
echo ".form-control:focus, .form-select:focus {\n";
echo "    border-color: #80bdff;\n";
echo "    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);\n";
echo "}\n";
echo ".btn-primary {\n";
echo "    background-color: #015B91;\n";
echo "    border-color: #015B91;\n";
echo "    border-radius: 6px;\n";
echo "    padding: 10px 20px;\n";
echo "    font-weight: 500;\n";
echo "}\n";
echo ".btn-primary:hover {\n";
echo "    background-color: #014a75;\n";
echo "    border-color: #014a75;\n";
echo "}\n";
echo ".btn-secondary {\n";
echo "    background-color: #6c757d;\n";
echo "    border-color: #6c757d;\n";
echo "    border-radius: 6px;\n";
echo "    padding: 10px 20px;\n";
echo "    font-weight: 500;\n";
echo "}\n";
echo ".btn-secondary:hover {\n";
echo "    background-color: #5a6268;\n";
echo "    border-color: #545b62;\n";
echo "}\n";
echo ".section-title {\n";
echo "    font-size: 1.2rem;\n";
echo "    font-weight: 600;\n";
echo "    color: #015B91;\n";
echo "    margin-bottom: 15px;\n";
echo "    padding-bottom: 8px;\n";
echo "    border-bottom: 2px solid #e9ecef;\n";
echo "}\n";
echo ".help-icon {\n";
echo "    cursor: pointer;\n";
echo "    margin-left: 5px;\n";
echo "    color: #6c757d;\n";
echo "}\n";
echo ".help-icon:hover {\n";
echo "    color: #015B91;\n";
echo "}\n";
echo ".footer-info {\n";
echo "    font-size: 0.85rem;\n";
echo "    color: #6c757d;\n";
echo "    margin-top: 20px;\n";
echo "    text-align: center;\n";
echo "}\n";
echo ".alert-warning {\n";
echo "    background-color: #fff3cd;\n";
echo "    border-color: #ffeeba;\n";
echo "    color: #856404;\n";
echo "    border-radius: 6px;\n";
echo "    padding: 12px 15px;\n";
echo "    margin-bottom: 20px;\n";
echo "}\n";
echo ".form-check-input:checked {\n";
echo "    background-color: #015B91;\n";
echo "    border-color: #015B91;\n";
echo "}\n";
echo ".input-group-text {\n";
echo "    background-color: #e9ecef;\n";
echo "    border: 1px solid #ced4da;\n";
echo "}\n";
echo "</style>\n";
echo "<title>" . _QXZ("ADMINISTRATION: Lead Loader") . "</title>\n";
echo "</head>\n";
echo "<body>\n";
echo "<div class=\"admin-container\">\n";

// Include header
 $short_header = 1;
//require("admin_header.php");

// Check NANPA options
if ((preg_match("/NANPA/", $usacan_check)) or (preg_match("/NANPA/", $tz_method))) {
    $stmt = "SELECT count(*) from vicidial_nanpa_prefix_codes;";
    $rslt = mysql_to_mysqli($stmt, $link);
    $row = mysqli_fetch_row($rslt);
    $vicidial_nanpa_prefix_codes_count = $row[0];
    if ($vicidial_nanpa_prefix_codes_count < 10) {
        $usacan_check = preg_replace("/NANPA/", '', $usacan_check);
        $tz_method = preg_replace("/NANPA/", '', $tz_method);
        echo "<div class=\"alert alert-warning\"><i class=\"fas fa-exclamation-triangle me-2\"></i>NOTICE: NANPA options disabled, NANPA prefix data not loaded: $vicidial_nanpa_prefix_codes_count</div>\n";
    }
}

// Main form
if ((!$OK_to_process) or (($leadfile) and ($file_layout != "standard" && $file_layout != "template"))) {
    ?>
    <form action="<?php echo $PHP_SELF ?>" method="post" onSubmit="ParseFileName()" enctype="multipart/form-data">
        <input type="hidden" name='leadfile_name' value="<?php echo $leadfile_name ?>">
        <input type="hidden" name='DB' value="<?php echo $DB ?>">
        
        <?php if ($file_layout != "custom") { ?>
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><i class="fas fa-upload me-2"></i><?php echo _QXZ("Lead Loader Configuration"); ?></h4>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="leadfile" class="form-label"><?php echo _QXZ("Load leads from this file"); ?>:</label>
                            <div class="input-group">
                                <input type="file" name="leadfile" id="leadfile" class="form-control" value="<?php echo $leadfile ?>">
                                <span class="input-group-text"><?php echo "$NWB#list_loader$NWE"; ?></span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="list_id_override" class="form-label"><?php echo _QXZ("List ID Override"); ?>:</label>
                            <div class="input-group">
                                <select name='list_id_override' id="list_id_override" class="form-select" onchange="PopulateStatuses(this.value)">
                                    <option value='in_file' selected='yes'><?php echo _QXZ("Load from Lead File"); ?></option>
                                    <?php
                                    $stmt = "SELECT list_id, list_name from vicidial_lists $whereLOGallowed_campaignsSQL order by list_id;";
                                    $rslt = mysql_to_mysqli($stmt, $link);
                                    $num_rows = mysqli_num_rows($rslt);

                                    $count = 0;
                                    while ($num_rows > $count) {
                                        $row = mysqli_fetch_row($rslt);
                                        echo "<option value='$row[0]'>$row[0] - $row[1]</option>\n";
                                        $count++;
                                    }
                                    ?>
                                </select>
                                <div class="form-check ms-2 d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" name="master_list_override" id="master_list_override" value="1">
                                    <label class="form-check-label ms-1" for="master_list_override"><?php echo _QXZ("override template setting"); ?></label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone_code_override" class="form-label"><?php echo _QXZ("Phone Code Override"); ?>:</label>
                            <select name='phone_code_override' id="phone_code_override" class="form-select">
                                <option value='in_file' selected='yes'><?php echo _QXZ("Load from Lead File"); ?></option>
                                <?php
                                $stmt = "SELECT distinct country_code, country from vicidial_phone_codes;";
                                $rslt = mysql_to_mysqli($stmt, $link);
                                $num_rows = mysqli_num_rows($rslt);

                                $count = 0;
                                while ($num_rows > $count) {
                                    $row = mysqli_fetch_row($rslt);
                                    echo "<option value='$row[0]'>$row[0] - $row[1]</option>\n";
                                    $count++;
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"><?php echo _QXZ("File layout to use"); ?>:</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="file_layout" id="layout_standard" value="standard" checked>
                                    <label class="form-check-label" for="layout_standard"><?php echo _QXZ("Standard Format"); ?></label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="file_layout" id="layout_custom" value="custom">
                                    <label class="form-check-label" for="layout_custom"><?php echo _QXZ("Custom layout"); ?></label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="file_layout" id="layout_template" value="template">
                                    <label class="form-check-label" for="layout_template"><?php echo _QXZ("Custom Template"); ?></label>
                                </div>
                                <span class="help-icon"><?php echo "$NWB#list_loader-file_layout$NWE"; ?></span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="template_id" class="form-label"><?php echo _QXZ("Custom Layout to Use"); ?>:</label>
                            <div class="input-group">
                                <select name="template_id" id="template_id" class="form-select">
                                    <?php
                                    $template_stmt = "SELECT template_id, template_name FROM vicidial_custom_leadloader_templates WHERE list_id IN (SELECT list_id FROM vicidial_lists $whereLOGallowed_campaignsSQL) ORDER BY template_id asc;";
                                    $template_rslt = mysql_to_mysqli($template_stmt, $link);
                                    if (mysqli_num_rows($template_rslt) > 0) {
                                        echo "<option value='' selected>--" . _QXZ("Select custom template") . "--</option>";
                                        while ($row = mysqli_fetch_array($template_rslt)) {
                                            echo "<option value='$row[template_id]'>$row[template_id] - $row[template_name]</option>";
                                        }
                                    } else {
                                        echo "<option value='' selected>--" . _QXZ("No custom templates defined") . "--</option>";
                                    }
                                    ?>
                                </select>
                                <span class="input-group-text"><?php echo "$NWB#list_loader-template_id$NWE"; ?></span>
                            </div>
                            <div class="mt-2">
                                <a href='AST_admin_template_maker.php' class="btn btn-sm btn-outline-secondary"><i class="fas fa-tools me-1"></i><?php echo _QXZ("template builder"); ?></a>
                                <a href='#' onClick="TemplateSpecs()" class="btn btn-sm btn-outline-secondary ms-2"><i class="fas fa-info-circle me-1"></i><?php echo _QXZ("View template info"); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="section-title"><i class="fas fa-shield-alt me-2"></i><?php echo _QXZ("Validation & Processing"); ?></h5>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="dupcheck" class="form-label"><?php echo _QXZ("Lead Duplicate Check"); ?>:</label>
                            <div class="input-group">
                                <select size=1 name=dupcheck id="dupcheck" class="form-select">
                                    <option selected value="NONE"><?php echo _QXZ("NO DUPLICATE CHECK"); ?></option>
                                    <option value="DUPLIST"><?php echo _QXZ("CHECK FOR DUPLICATES BY PHONE IN LIST ID"); ?></option>
                                    <option value="DUPCAMP"><?php echo _QXZ("CHECK FOR DUPLICATES BY PHONE IN ALL CAMPAIGN LISTS"); ?></option>
                                    <option value="DUPSYS"><?php echo _QXZ("CHECK FOR DUPLICATES BY PHONE IN ENTIRE SYSTEM"); ?></option>
                                    <option value="DUPLIST30DAY"><?php echo _QXZ("CHECK FOR DUPLICATES LOADED IN LAST 30 DAYS BY PHONE IN LIST ID"); ?></option>
                                    <option value="DUPCAMP30DAY"><?php echo _QXZ("CHECK FOR DUPLICATES LOADED IN LAST 30 DAYS BY PHONE IN ALL CAMPAIGN LISTS"); ?></option>
                                    <option value="DUPSYS30DAY"><?php echo _QXZ("CHECK FOR DUPLICATES LOADED IN LAST 30 DAYS BY PHONE IN ENTIRE SYSTEM"); ?></option>
                                    <option value="DUPLIST60DAY"><?php echo _QXZ("CHECK FOR DUPLICATES LOADED IN LAST 60 DAYS BY PHONE IN LIST ID"); ?></option>
                                    <option value="DUPCAMP60DAY"><?php echo _QXZ("CHECK FOR DUPLICATES LOADED IN LAST 60 DAYS BY PHONE IN ALL CAMPAIGN LISTS"); ?></option>
                                    <option value="DUPSYS60DAY"><?php echo _QXZ("CHECK FOR DUPLICATES LOADED IN LAST 60 DAYS BY PHONE IN ENTIRE SYSTEM"); ?></option>
                                    <option value="DUPLIST90DAY"><?php echo _QXZ("CHECK FOR DUPLICATES LOADED IN LAST 90 DAYS BY PHONE IN LIST ID"); ?></option>
                                    <option value="DUPCAMP90DAY"><?php echo _QXZ("CHECK FOR DUPLICATES LOADED IN LAST 90 DAYS BY PHONE IN ALL CAMPAIGN LISTS"); ?></option>
                                    <option value="DUPSYS90DAY"><?php echo _QXZ("CHECK FOR DUPLICATES LOADED IN LAST 90 DAYS BY PHONE IN ENTIRE SYSTEM"); ?></option>
                                    <option value="DUPLIST180DAY"><?php echo _QXZ("CHECK FOR DUPLICATES LOADED IN LAST 180 DAYS BY PHONE IN LIST ID"); ?></option>
                                    <option value="DUPCAMP180DAY"><?php echo _QXZ("CHECK FOR DUPLICATES LOADED IN LAST 180 DAYS BY PHONE IN ALL CAMPAIGN LISTS"); ?></option>
                                    <option value="DUPSYS180DAY"><?php echo _QXZ("CHECK FOR DUPLICATES LOADED IN LAST 180 DAYS BY PHONE IN ENTIRE SYSTEM"); ?></option>
                                    <option value="DUPLIST360DAY"><?php echo _QXZ("CHECK FOR DUPLICATES LOADED IN LAST 360 DAYS BY PHONE IN LIST ID"); ?></option>
                                    <option value="DUPCAMP360DAY"><?php echo _QXZ("CHECK FOR DUPLICATES LOADED IN LAST 360 DAYS BY PHONE IN ALL CAMPAIGN LISTS"); ?></option>
                                    <option value="DUPSYS360DAY"><?php echo _QXZ("CHECK FOR DUPLICATES LOADED IN LAST 360 DAYS BY PHONE IN ENTIRE SYSTEM"); ?></option>
                                    <option value="DUPTITLEALTPHONELIST"><?php echo _QXZ("CHECK FOR DUPLICATES BY TITLE/ALT-PHONE IN LIST ID"); ?></option>
                                    <option value="DUPTITLEALTPHONESYS"><?php echo _QXZ("CHECK FOR DUPLICATES BY TITLE/ALT-PHONE IN ENTIRE SYSTEM"); ?></option>
                                </select>
                                <span class="input-group-text"><?php echo "$NWB#list_loader-duplicate_check$NWE"; ?></span>
                            </div>
                        </div>
                        
                        <?php
                        if ($SSenable_international_dncs) {
                            $dnc_stmt = "select iso3, country_name from vicidial_country_iso_tld where iso3 is not null and iso3!='' order by country_name asc";
                            $dnc_rslt = mysql_to_mysqli($dnc_stmt, $link);
                            $available_countries = 0;
                            while ($dnc_row = mysqli_fetch_row($dnc_rslt)) {
                                $iso = $dnc_row[0];
                                $country_name = $dnc_row[1];
                                $dnc_table_stmt = "show tables like 'vicidial_dnc_" . $iso . "'";
                                $dnc_table_rslt = mysql_to_mysqli($dnc_table_stmt, $link);
                                if (mysqli_num_rows($dnc_table_rslt) > 0) {
                                    $available_countries++;
                                    $drop_down_dnc_options .= "\t\t\t\t<option value='$iso'>$iso - $country_name</option>\n";
                                }
                            }
                        ?>
                        <div class="mb-3">
                            <label for="international_dnc_scrub" class="form-label"><?php echo _QXZ("DNC Scrub by Country"); ?>:</label>
                            <select size='1' name='international_dnc_scrub' id="international_dnc_scrub" class="form-select">
                                <?php
                                if ($available_countries > 0) {
                                    echo "\t\t\t\t<option>-- SELECT COUNTRY DNC LIST--</option>\n";
                                    echo $drop_down_dnc_options;
                                } else {
                                    echo "\t\t\t\t<option>-- NO COUNTRY DNC TABLES EXIST --</option>\n";
                                }
                                ?>
                            </select>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="dedupe_statuses" class="form-label"><?php echo _QXZ("Status Duplicate Check"); ?>:</label>
                            <div id='statuses_display'>
                                <select id='dedupe_statuses' name='dedupe_statuses[]' size=5 multiple class="form-select">
                                    <option value='--ALL--' selected>--<?php echo _QXZ("ALL DISPOSITIONS"); ?>--</option>
                                    <?php echo $dedupe_status_select ?>
                                </select>
                            </div>
                        </div>
                        
                        <?php if ($enable_status_mismatch_leadloader_option > 0) { ?>
                        <div class="mb-3">
                            <label for="status_mismatch_action" class="form-label"><?php echo _QXZ("Status Mismatch Action"); ?>:</label>
                            <div class="input-group">
                                <div id='status_mismatch_display'>
                                    <select id='status_mismatch_action' name='status_mismatch_action' class="form-select">
                                        <option value='' selected><?php echo _QXZ("NONE"); ?></option>
                                        <option value='MOVE RECENT FROM SYSTEM'><?php echo _QXZ("MOVE MOST RECENT PHONE DUPLICATE, CHECK ENTIRE SYSTEM"); ?></option>
                                        <option value='MOVE ALL FROM SYSTEM'><?php echo _QXZ("MOVE ALL PHONE DUPLICATES, CHECK ENTIRE SYSTEM"); ?></option>
                                        <option value='MOVE RECENT USING CHECK'><?php echo _QXZ("MOVE MOST RECENT PHONE FROM DUPLICATE CHECK TO CURRENT LIST"); ?></option>
                                        <option value='MOVE ALL USING CHECK'><?php echo _QXZ("MOVE ALL PHONES FROM DUPLICATE CHECK TO CURRENT LIST"); ?></option>
                                    </select>
                                </div>
                                <span class="input-group-text"><?php echo "$NWB#list_loader-status_mismatch_action$NWE"; ?></span>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="section-title"><i class="fas fa-cogs me-2"></i><?php echo _QXZ("Advanced Settings"); ?></h5>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="usacan_check" class="form-label"><?php echo _QXZ("USA-Canada Check"); ?>:</label>
                            <select size=1 name=usacan_check id="usacan_check" class="form-select">
                                <option selected value="NONE"><?php echo _QXZ("NO USACAN VALID CHECK"); ?></option>
                                <option value="PREFIX"><?php echo _QXZ("CHECK FOR VALID PREFIX"); ?></option>
                                <option value="AREACODE"><?php echo _QXZ("CHECK FOR VALID AREACODE"); ?></option>
                                <option value="PREFIX_AREACODE"><?php echo _QXZ("CHECK FOR VALID PREFIX and AREACODE"); ?></option>
                                <option value="NANPA"><?php echo _QXZ("CHECK FOR VALID NANPA PREFIX and AREACODE"); ?></option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="postalgmt" class="form-label"><?php echo _QXZ("Lead Time Zone Lookup"); ?>:</label>
                            <select size=1 name=postalgmt id="postalgmt" class="form-select">
                                <option selected value="AREA"><?php echo _QXZ("COUNTRY CODE AND AREA CODE ONLY"); ?></option>
                                <option value="POSTAL"><?php echo _QXZ("POSTAL CODE FIRST"); ?></option>
                                <option value="TZCODE"><?php echo _QXZ("OWNER TIME ZONE CODE FIRST"); ?></option>
                                <option value="NANPA"><?php echo _QXZ("NANPA AREACODE PREFIX FIRST"); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="state_conversion" class="form-label"><?php echo _QXZ("State Abbreviation Lookup"); ?>:</label>
                            <select size=1 name=state_conversion id="state_conversion" class="form-select">
                                <option selected value=""><?php echo _QXZ("DISABLED"); ?></option>
                                <option value="STATELOOKUP"><?php echo _QXZ("FULL STATE NAME TO ABBREVIATION"); ?></option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="web_loader_phone_length" class="form-label"><?php echo _QXZ("Required Phone Number Length"); ?>:</label>
                            <select size=1 name=web_loader_phone_length id="web_loader_phone_length" class="form-select">
                                <?php if ($SSweb_loader_phone_length == 'DISABLED') { ?>
                                <option selected value=""><?php echo _QXZ("DISABLED"); ?>
                                <?php }
                                if ($SSweb_loader_phone_length == 'CHOOSE') { ?>
                                <option selected value=""><?php echo _QXZ("DISABLED"); ?></option>
                                <option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option><option>12</option><option>13</option><option>14</option><option>15</option><option>16</option><option>17</option><option>18</option>
                                <?php }
                                if ((strlen($SSweb_loader_phone_length) > 0) and (strlen($SSweb_loader_phone_length) < 3) and ($SSweb_loader_phone_length > 4) and ($SSweb_loader_phone_length < 19)) { ?>
                                <option selected value="<?php echo $SSweb_loader_phone_length ?>"><?php echo $SSweb_loader_phone_length ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        <button type="submit" name="submit_file" class="btn btn-primary"><i class="fas fa-check-circle me-2"></i><?php echo _QXZ("SUBMIT"); ?></button>
                        <button type="button" onClick="javascript:document.location='admin_listloader_fourth_gen.php'" class="btn btn-secondary"><i class="fas fa-redo me-2"></i><?php echo _QXZ("START OVER"); ?></button>
                    </div>
                    <div>
                        <a href="admin.php?ADD=100" target="_parent" class="btn btn-sm btn-outline-primary"><i class="fas fa-arrow-left me-1"></i><?php echo _QXZ("BACK TO ADMIN"); ?></a>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <div>
                    <span class="text-muted"><?php echo _QXZ("LIST LOADER 4th Gen"); ?> | <a href="admin_listloader_fifth_gen.php"><?php echo _QXZ("5th Gen"); ?></a></span>
                </div>
                <div>
                    <span class="text-muted"><?php echo _QXZ("VERSION"); ?>: <?php echo $version ?></span>
                    <span class="text-muted ms-3"><?php echo _QXZ("BUILD"); ?>: <?php echo $build ?></span>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    ?>
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0"><i class="fas fa-check-circle me-2"></i><?php echo _QXZ("Lead File Processing"); ?></h4>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-5 fw-bold text-end"><?php echo _QXZ("Lead file"); ?>:</div>
                <div class="col-md-7"><?php echo $leadfile_name ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-5 fw-bold text-end"><?php echo _QXZ("List ID Override"); ?>:</div>
                <div class="col-md-7"><?php echo $list_id_override ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-5 fw-bold text-end"><?php echo _QXZ("Phone Code Override"); ?>:</div>
                <div class="col-md-7"><?php echo $phone_code_override ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-5 fw-bold text-end"><?php echo _QXZ("USA-Canada Check"); ?>:</div>
                <div class="col-md-7"><?php echo $usacan_check ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-5 fw-bold text-end"><?php echo _QXZ("Lead Duplicate Check"); ?>:</div>
                <div class="col-md-7"><?php echo $dupcheck ?></div>
            </div>
            <?php
            if ($SSenable_international_dncs) {
            ?>
            <div class="row mb-3">
                <div class="col-md-5 fw-bold text-end"><?php echo _QXZ("International DNC scrub"); ?>:</div>
                <div class="col-md-7"><?php echo $international_dnc_scrub ?></div>
            </div>
            <?php
            }
            ?>
            <div class="row mb-3">
                <div class="col-md-5 fw-bold text-end"><?php echo _QXZ("Lead Time Zone Lookup"); ?>:</div>
                <div class="col-md-7"><?php echo $postalgmt ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-5 fw-bold text-end"><?php echo _QXZ("State Abbreviation Lookup"); ?>:</div>
                <div class="col-md-7"><?php echo $state_conversion ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-md-5 fw-bold text-end"><?php echo _QXZ("Required Phone Number Length"); ?>:</div>
                <div class="col-md-7"><?php echo $web_loader_phone_length ?></div>
            </div>
        </div>
        <div class="card-footer text-center">
            <form action="<?php echo $PHP_SELF ?>" method="get" onSubmit="ParseFileName()" enctype="multipart/form-data">
                <input type="hidden" name='leadfile_name' value="<?php echo $leadfile_name ?>">
                <input type="hidden" name='DB' value="<?php echo $DB ?>">
                <a href="admin_listloader_fourth_gen.php" class="btn btn-success"><i class="fas fa-plus-circle me-2"></i><?php echo _QXZ("Load Another Lead File"); ?></a>
                <div class="mt-3 text-muted">
                    <?php echo _QXZ("VERSION"); ?>: <?php echo $version ?> | 
                    <?php echo _QXZ("BUILD"); ?>: <?php echo $build ?>
                </div>
            </form>
        </div>
    </div>
    <?php
}
?>

<script language="JavaScript1.2">
function openNewWindow(url) {
    window.open(url, "", 'width=700,height=300,scrollbars=yes,menubar=yes,address=yes');
}

function ShowProgress(good, bad, total, dup, inv, post, moved) {
    parent.lead_count.document.open();
    parent.lead_count.document.write('<html><head><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"></head><body><div class="container mt-4"><div class="card"><div class="card-header bg-primary text-white"><h5 class="mb-0"><?php echo _QXZ("Current file status"); ?></h5></div><div class="card-body"><div class="row"><div class="col-md-6"><div class="alert alert-success mb-2"><strong><?php echo _QXZ("Good"); ?>:</strong> ' + good + '</div><div class="alert alert-success mb-2"><strong><?php echo _QXZ("Duplicate"); ?>:</strong> ' + dup + '</div><div class="alert alert-success mb-2"><strong><?php echo _QXZ("Moved"); ?>:</strong> ' + moved + '</div></div><div class="col-md-6"><div class="alert alert-danger mb-2"><strong><?php echo _QXZ("Bad"); ?>:</strong> ' + bad + '</div><div class="alert alert-info mb-2"><strong><?php echo _QXZ("Total"); ?>:</strong> ' + total + '</div><div class="alert alert-warning mb-2"><strong><?php echo _QXZ("Invalid"); ?>:</strong> ' + inv + '</div><div class="alert alert-info mb-2"><strong><?php echo _QXZ("Postal Match"); ?>:</strong> ' + post + '</div></div></div></div></div></div></body></html>');
    parent.lead_count.document.close();
}

function ParseFileName() {
    if (!document.forms[0].OK_to_process) {
        var endstr = document.forms[0].leadfile.value.lastIndexOf('\\');
        if (endstr > -1) {
            endstr++;
            var filename = document.forms[0].leadfile.value.substring(endstr);
            document.forms[0].leadfile_name.value = filename;
        }
    }
}

function TemplateSpecs() {
    var template_field = document.getElementById("template_id");
    var template_id_value = template_field.options[template_field.selectedIndex].value;
    var xmlhttp = false;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xmlhttp = false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    if (xmlhttp && template_id_value != "") {
        var vs_query = "&template_id=" + template_id_value;
        xmlhttp.open('POST', 'leadloader_template_display.php');
        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        xmlhttp.send(vs_query);
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var TemplateInfo = null;
                TemplateInfo = xmlhttp.responseText;
                if (TemplateInfo.length > 0) {
                    // Use a modern modal instead of alert
                    var modal = document.createElement('div');
                    modal.className = 'modal fade';
                    modal.id = 'templateInfoModal';
                    modal.innerHTML = '<div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title"><?php echo _QXZ("Template Information"); ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body">' + TemplateInfo + '</div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo _QXZ("Close"); ?></button></div></div></div>';
                    document.body.appendChild(modal);
                    var myModal = new bootstrap.Modal(document.getElementById('templateInfoModal'));
                    myModal.show();
                }
            }
        }
        delete xmlhttp;
    }
}

function PopulateStatuses(list_id) {
    var xmlhttp = false;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xmlhttp = false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    if (xmlhttp) {
        var vs_query = "&form_action=no_template&list_id=" + list_id;
        xmlhttp.open('POST', 'leadloader_template_display.php');
        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        xmlhttp.send(vs_query);
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var StatSpanText = null;
                StatSpanText = xmlhttp.responseText;
                document.getElementById("statuses_display").innerHTML = StatSpanText;
            }
        }
        delete xmlhttp;
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</div>
</body>
</html>
<?php

##### BEGIN custom fields submission #####
if ($OK_to_process) 
	{
	print "<script language='JavaScript1.2'>\nif(document.forms[0].leadfile) {document.forms[0].leadfile.disabled=true;}\ndocument.forms[0].list_id_override.disabled=true;\ndocument.forms[0].phone_code_override.disabled=true;\nif(document.forms[0].submit_file) {document.forms[0].submit_file.disabled=true;}\nif(document.forms[0].reload_page) {document.forms[0].reload_page.disabled=true;}\n</script>";
	flush();
	$total=0; $good=0; $bad=0; $dup=0; $inv=0; $post=0; $moved=0; $phone_list='';

	$file=fopen("$lead_file", "r");
	if ($webroot_writable > 0)
		{
		$stmt_file=fopen("listloader_stmts.txt", "w");
		}
	$buffer=fgets($file, 4096);
	$tab_count=substr_count($buffer, "\t");
	$pipe_count=substr_count($buffer, "|");

	if ($tab_count>$pipe_count) {$delimiter="\t";  $delim_name="tab";} else {$delimiter="|";  $delim_name="pipe";}
	$field_check=explode($delimiter, $buffer);

	if (count($field_check)>=2) 
		{
		flush();
		$file=fopen("$lead_file", "r");
		print "<center><font face='arial, helvetica' size=3 color='#009900'><B>"._QXZ("Processing file")."...\n";

		if (is_array($dedupe_statuses) && count($dedupe_statuses)>0) 
			{
			$statuses_clause=" and status in (";
			$status_dedupe_str="";
			for($ds=0; $ds<count($dedupe_statuses); $ds++) 
				{
				$dedupe_statuses[$ds] = preg_replace('/[^-_0-9\p{L}]/u', '', $dedupe_statuses[$ds]);
				$statuses_clause.="'$dedupe_statuses[$ds]',";
				$status_dedupe_str.="$dedupe_statuses[$ds], ";
				if (preg_match('/\-\-ALL\-\-/', $dedupe_statuses[$ds])) 
					{
					$status_mismatch_action="";  # Important - if user selects all dispositions, then there is no possibility of the status mismatch being needed
					$statuses_clause="";
					$status_dedupe_str="";
					break;
					}
				}
			$statuses_clause=preg_replace('/,$/', "", $statuses_clause);
			$status_dedupe_str=preg_replace('/,\s$/', "", $status_dedupe_str);
			if ($statuses_clause!="") {$statuses_clause.=")";}
	
			if ($status_mismatch_action) 
				{
				$mismatch_clause=" and status not in ('".implode("','", $dedupe_statuses)."') ";
				if (preg_match('/RECENT/', $status_mismatch_action)) {$mismatch_limit=" limit 1 ";} else {$mismatch_limit="";}
				}
			}
		

		if (strlen($list_id_override)>0) 
			{
			print "<BR><BR>"._QXZ("LIST ID OVERRIDE FOR THIS FILE").": $list_id_override<BR><BR>";
			}

		if (strlen($phone_code_override)>0) 
			{
			print "<BR><BR>"._QXZ("PHONE CODE OVERRIDE FOR THIS FILE").": $phone_code_override<BR><BR>";
			}
		if (strlen($dupcheck)>0) 
			{
			print "<BR>"._QXZ("LEAD DUPLICATE CHECK").": $dupcheck<BR>\n";
			}
		if (strlen($international_dnc_scrub)>0) 
			{
			print "<BR>"._QXZ("INTERNATIONAL DNC SCRUB").": $international_dnc_scrub<BR>\n";
			}
		if (strlen($status_dedupe_str)>0) 
			{
			print "<BR>"._QXZ("OMITTING DUPLICATES AGAINST FOLLOWING STATUSES ONLY").": $status_dedupe_str<BR>\n";
			}
		if (strlen($status_mismatch_action)>0) 
			{
			print "<BR>"._QXZ("ACTION FOR DUPLICATE NOT ON STATUS LIST").": $status_mismatch_action<BR>\n";
			}
		if (strlen($state_conversion)>9)
			{
			print "<BR>"._QXZ("CONVERSION OF STATE NAMES TO ABBREVIATIONS ENABLED").": $state_conversion<BR>\n";
			}
		if ( (strlen($web_loader_phone_length)>0) and (strlen($web_loader_phone_length)< 3) )
			{
			print "<BR>"._QXZ("REQUIRED PHONE NUMBER LENGTH").": $web_loader_phone_length<BR>\n";
			}
		if ( (strlen($SSweb_loader_phone_strip)>0) and ($SSweb_loader_phone_strip != 'DISABLED') )
			{
			print "<BR>"._QXZ("PHONE NUMBER PREFIX STRIP SYSTEM SETTING ENABLED").": $SSweb_loader_phone_strip<BR>\n";
			}
		$multidaySQL='';
		if (preg_match("/30DAY|60DAY|90DAY|180DAY|360DAY/i",$dupcheck))
			{
			$day_val=30;
			if (preg_match("/30DAY/i",$dupcheck)) {$day_val=30;}
			if (preg_match("/60DAY/i",$dupcheck)) {$day_val=60;}
			if (preg_match("/90DAY/i",$dupcheck)) {$day_val=90;}
			if (preg_match("/180DAY/i",$dupcheck)) {$day_val=180;}
			if (preg_match("/360DAY/i",$dupcheck)) {$day_val=360;}
			$multiday = date("Y-m-d H:i:s", mktime(date("H"),date("i"),date("s"),date("m"),date("d")-$day_val,date("Y")));
			$multidaySQL = "and entry_date > \"$multiday\"";
			if ($DB > 0) {echo "DEBUG: $day_val day SQL: |$multidaySQL|";}
			}

		if ($custom_fields_enabled > 0)
			{
			$tablecount_to_print=0;
			$fieldscount_to_print=0;
			$fields_to_print=0;

			$stmt="SHOW TABLES LIKE \"custom_$list_id_override\";";
			if ($DB>0) {echo "$stmt\n";}
			$rslt=mysql_to_mysqli($stmt, $link);
			$tablecount_to_print = mysqli_num_rows($rslt);

			if ($tablecount_to_print > 0) 
				{
				$stmt="SELECT count(*) from vicidial_lists_fields where list_id='$list_id_override' and field_duplicate!='Y';";
				if ($DB>0) {echo "$stmt\n";}
				$rslt=mysql_to_mysqli($stmt, $link);
				$fieldscount_to_print = mysqli_num_rows($rslt);

				if ($fieldscount_to_print > 0) 
					{
					$stmt="SELECT field_label,field_type,field_encrypt from vicidial_lists_fields where list_id='$list_id_override' and field_duplicate!='Y' order by field_rank,field_order,field_label;";
					if ($DB>0) {echo "$stmt\n";}
					$rslt=mysql_to_mysqli($stmt, $link);
					$fields_to_print = mysqli_num_rows($rslt);
					$fields_list='';
					$o=0;
					while ($fields_to_print > $o) 
						{
						$rowx=mysqli_fetch_row($rslt);
						$A_field_label[$o] =	$rowx[0];
						$A_field_type[$o] =		$rowx[1];
						$A_field_encrypt[$o] =	$rowx[2];
						$A_field_value[$o] =	'';
						$o++;
						}
					}
				}
			}

		#  If a list is being scrubbed against a country's DNC list, block the list from being dialed and purge any lead from the hopper that belongs to that list.
		if (strlen($international_dnc_scrub)>0 && strlen($list_id_override)>0 && $SSenable_international_dncs)
			{
			$upd_dnc_stmt="update vicidial_settings_containers set container_entry=concat('$list_id_override => $international_dnc_scrub', if(length(container_entry)>0, '\r\n', ''), if(container_entry is null, '', container_entry)) where container_id='DNC_CURRENT_BLOCKED_LISTS'";
			$upd_dnc_rslt=mysql_to_mysqli($upd_dnc_stmt, $link);

			$delete_hopper_stmt="delete from vicidial_hopper where list_id='$list_id_override'";
			$delete_hopper_rslt=mysql_to_mysqli($delete_hopper_stmt, $link);
			}

		while (!feof($file)) 
			{
			$record++;
			$buffer=rtrim(fgets($file, 4096));
			$buffer=stripslashes($buffer);

			if (strlen($buffer)>0) 
				{
				$row=explode($delimiter, preg_replace('/[\"]/i', '', $buffer));

				$pulldate=date("Y-m-d H:i:s");
				$entry_date =			"$pulldate";
				$modify_date =			"";
				$status =				"NEW";
				$user ="";
				$vendor_lead_code =		$row[$vendor_lead_code_field];
				$source_code =			$row[$source_id_field];
				$source_id=$source_code;
				$list_id =				$row[$list_id_field];
				$gmt_offset =			'0';
				$called_since_last_reset='N';
				$phone_code =			preg_replace('/[^0-9]/i', '', $row[$phone_code_field]);
				$phone_number =			preg_replace('/[^0-9]/i', '', $row[$phone_number_field]);
				$title =				$row[$title_field];
				$first_name =			$row[$first_name_field];
				$middle_initial =		$row[$middle_initial_field];
				$last_name =			$row[$last_name_field];
				$address1 =				$row[$address1_field];
				$address2 =				$row[$address2_field];
				$address3 =				$row[$address3_field];
				$city =$row[$city_field];
				$state =				$row[$state_field];
				$province =				$row[$province_field];
				$postal_code =			$row[$postal_code_field];
				$country_code =			$row[$country_code_field];
				$gender =				$row[$gender_field];
				$date_of_birth =		$row[$date_of_birth_field];
				$alt_phone =			preg_replace('/[^0-9]/i', '', $row[$alt_phone_field]);
				$email =				$row[$email_field];
				$security_phrase =		$row[$security_phrase_field];
				$comments =				trim($row[$comments_field]);
				$rank =					$row[$rank_field];
				$owner =				$row[$owner_field];
				
				# replace ' " ` \ ; with nothing
				$vendor_lead_code =		preg_replace("/$field_regx/i", "", $vendor_lead_code);
				$source_code =			preg_replace("/$field_regx/i", "", $source_code);
				$source_id = 			preg_replace("/$field_regx/i", "", $source_id);
				$list_id =				preg_replace("/$field_regx/i", "", $list_id);
				$phone_code =			preg_replace("/$field_regx/i", "", $phone_code);
				$phone_number =			preg_replace("/$field_regx/i", "", $phone_number);
				$title =				preg_replace("/$field_regx/i", "", $title);
				$first_name =			preg_replace("/$field_regx/i", "", $first_name);
				$middle_initial =		preg_replace("/$field_regx/i", "", $middle_initial);
				$last_name =			preg_replace("/$field_regx/i", "", $last_name);
				$address1 =				preg_replace("/$field_regx/i", "", $address1);
				$address2 =				preg_replace("/$field_regx/i", "", $address2);
				$address3 =				preg_replace("/$field_regx/i", "", $address3);
				$city =					preg_replace("/$field_regx/i", "", $city);
				$state =				preg_replace("/$field_regx/i", "", $state);
				$province =				preg_replace("/$field_regx/i", "", $province);
				$postal_code =			preg_replace("/$field_regx/i", "", $postal_code);
				$country_code =			preg_replace("/$field_regx/i", "", $country_code);
				$gender =				preg_replace("/$field_regx/i", "", $gender);
				$date_of_birth =		preg_replace("/$field_regx/i", "", $date_of_birth);
				$alt_phone =			preg_replace("/$field_regx/i", "", $alt_phone);
				$email =				preg_replace("/$field_regx/i", "", $email);
				$security_phrase =		preg_replace("/$field_regx/i", "", $security_phrase);
				$comments =				preg_replace("/$field_regx/i", "", $comments);
				$rank =					preg_replace("/$field_regx/i", "", $rank);
				$owner =				preg_replace("/$field_regx/i", "", $owner);
				
				$USarea = 			substr($phone_number, 0, 3);
				$USprefix = 		substr($phone_number, 3, 3);

				if (strlen($list_id_override)>0) 
					{
				#	print "<BR><BR>LIST ID OVERRIDE FOR THIS FILE: $list_id_override<BR><BR>";
					$list_id = $list_id_override;
					}
				if (strlen($phone_code_override)>0) 
					{
					$phone_code = $phone_code_override;
					}
				if (strlen($phone_code)<1) {$phone_code = '1';}

				if ( ($state_conversion == 'STATELOOKUP') and (strlen($state) > 3) )
					{
					$stmt = "SELECT state from vicidial_phone_codes where geographic_description='$state' and country_code='$phone_code' limit 1;";
					if ($DB>0) {echo "DEBUG: state conversion query - $stmt\n";}
					$rslt=mysql_to_mysqli($stmt, $link);
					$sc_recs = mysqli_num_rows($rslt);
					if ($sc_recs > 0)
						{
						$row=mysqli_fetch_row($rslt);
						$state_abbr=$row[0];
						if ( (strlen($state_abbr) > 0) and (strlen($state_abbr) < 3 ) )
							{
							if ($DB>0) {echo "DEBUG: state conversion found - $state|$state_abbr\n";}
							$state = $state_abbr;
							}
						}
					}

				##### BEGIN custom fields columns list ###
				$custom_SQL='';
				if ($custom_fields_enabled > 0)
					{
					if ($tablecount_to_print > 0) 
						{
						if ($fieldscount_to_print > 0)
							{
							$o=0;
							while ($fields_to_print > $o) 
								{
								$A_field_value[$o] =	'';
								$field_name_id = $A_field_label[$o] . "_field";

							#	if ($DB>0) {echo "$A_field_label[$o]|$A_field_type[$o]\n";}

								if ( ($A_field_type[$o]!='DISPLAY') and ($A_field_type[$o]!='SCRIPT') and ($A_field_type[$o]!='SWITCH') and ($A_field_type[$o]!='BUTTON') )
									{
									if (!preg_match("/\|$A_field_label[$o]\|/",$vicidial_list_fields))
										{
										if (isset($_GET["$field_name_id"]))				{$form_field_value=$_GET["$field_name_id"];}
											elseif (isset($_POST["$field_name_id"]))	{$form_field_value=$_POST["$field_name_id"];}
										$form_field_value = preg_replace("/\<|\>|\"|\\\\|;/","",$form_field_value);

										if ($form_field_value >= 0)
											{
											$A_field_value[$o] =	$row[$form_field_value];
											# replace ' " ` \ ; with nothing
											$A_field_value[$o] =	preg_replace("/$field_regx/i", "", $A_field_value[$o]);

											if ( ($A_field_encrypt[$o] == 'Y') and (preg_match("/cf_encrypt/",$SSactive_modules)) and (strlen($A_field_value[$o]) > 0) )
												{
												$field_enc=$MT;
												$A_field_value[$o] = base64_encode($A_field_value[$o]);
												exec("../agc/aes.pl --encrypt --text=$A_field_value[$o]", $field_enc);
												$field_enc_ct = count($field_enc);
												$k=0;
												$field_enc_all='';
												while ($field_enc_ct > $k)
													{
													$field_enc_all .= $field_enc[$k];
													$k++;
													}
												$A_field_value[$o] = preg_replace("/CRYPT: |\n|\r|\t/",'',$field_enc_all);
												}

											$custom_SQL .= "$A_field_label[$o]=\"$A_field_value[$o]\",";
											}
										}
									}
								$o++;
								}
							}
						}
					}
				##### END custom fields columns list ###

				$custom_SQL = preg_replace("/,$/","",$custom_SQL);

				if ( (strlen($SSweb_loader_phone_strip)>0) and ($SSweb_loader_phone_strip != 'DISABLED') )
					{
					$phone_number = preg_replace("/^$SSweb_loader_phone_strip/",'',$phone_number);
					}

				##### Check for duplicate phone numbers in vicidial_list table for all lists in a campaign #####
				if (preg_match("/DUPCAMP/i",$dupcheck))
					{
					$dup_lead=0; $moved_lead=0;
					$dup_lists='';
					$stmt="SELECT campaign_id from vicidial_lists where list_id='$list_id';";
					$rslt=mysql_to_mysqli($stmt, $link);
					$ci_recs = mysqli_num_rows($rslt);
					if ($ci_recs > 0)
						{
						$row=mysqli_fetch_row($rslt);
						$dup_camp =			$row[0];

						$stmt="SELECT list_id from vicidial_lists where campaign_id='$dup_camp';";
						$rslt=mysql_to_mysqli($stmt, $link);
						$li_recs = mysqli_num_rows($rslt);
						if ($li_recs > 0)
							{
							$L=0;
							while ($li_recs > $L)
								{
								$row=mysqli_fetch_row($rslt);
								$dup_lists .=	"'$row[0]',";
								$L++;
								}
							$dup_lists = preg_replace('/,$/i', '',$dup_lists);

							if ($status_mismatch_action) 
								{
								if (preg_match('/USING CHECK/', $status_mismatch_action)) 
									{
									$stmt="SELECT list_id, lead_id from vicidial_list where phone_number='$phone_number' and list_id IN($dup_lists) $multidaySQL $mismatch_clause order by entry_date desc $mismatch_limit";
									} 
								else 
									{
									$stmt="SELECT list_id, lead_id from vicidial_list where phone_number='$phone_number' $mismatch_clause order by entry_date desc $mismatch_limit";
									}
								if ($DB>0) {print $stmt."<BR>";}
								$rslt=mysql_to_mysqli($stmt, $link);
								while ($row=mysqli_fetch_row($rslt)) # switch to upd_row if problem 
									{
									$upd_stmt="update vicidial_list set list_id='$list_id' where lead_id='$row[1]'";
									if ($DB>0) {print $upd_stmt."<BR>";}
									$upd_rslt=mysql_to_mysqli($upd_stmt, $link);
									$moved+=mysqli_affected_rows($link);
									$moved_lead+=mysqli_affected_rows($link);
									$dup_lead=1;
									$dup_lead_list =	$row[0];
									}
								}


							if ($dup_lead < 1)
								{
								$stmt="SELECT list_id from vicidial_list where phone_number='$phone_number' and list_id IN($dup_lists) $multidaySQL $statuses_clause limit 1;";
								$rslt=mysql_to_mysqli($stmt, $link);
								$pc_recs = mysqli_num_rows($rslt);
								if ($pc_recs > 0)
									{
									$dup_lead=1;
									$row=mysqli_fetch_row($rslt);
									$dup_lead_list =	$row[0];
									}
								}
							if ($dup_lead < 1)
								{
								if (preg_match("/$phone_number$US$list_id/i", $phone_list))
									{$dup_lead++; $dup++;}
								}
							}
						}
					}

				##### Check for duplicate phone numbers in vicidial_list table entire database #####
				if (preg_match("/DUPSYS/i",$dupcheck))
					{
					$dup_lead=0; $moved_lead=0;

					if ($status_mismatch_action) 
						{
						if (preg_match('/USING CHECK/', $status_mismatch_action)) 
							{
							$stmt="SELECT list_id, lead_id from vicidial_list where phone_number='$phone_number' $multidaySQL $mismatch_clause order by entry_date desc $mismatch_limit";
							} 
						else 
							{
							$stmt="SELECT list_id, lead_id from vicidial_list where phone_number='$phone_number' $mismatch_clause order by entry_date desc $mismatch_limit";
							}

						if ($DB>0) {print $stmt."<BR>";}
						$rslt=mysql_to_mysqli($stmt, $link);
						while ($row=mysqli_fetch_row($rslt)) # switch to upd_row if problem 
							{
							$upd_stmt="update vicidial_list set list_id='$list_id' where lead_id='$row[1]'";
							if ($DB>0) {print $upd_stmt."<BR>";}
							$upd_rslt=mysql_to_mysqli($upd_stmt, $link);
							$moved+=mysqli_affected_rows($link);
							$moved_lead+=mysqli_affected_rows($link);
							$dup_lead=1;
							$dup_lead_list =	$row[0];
							}
						}

					
					if ($dup_lead < 1)
						{
						$stmt="SELECT list_id from vicidial_list where phone_number='$phone_number' $multidaySQL $statuses_clause;";
						$rslt=mysql_to_mysqli($stmt, $link);
						$pc_recs = mysqli_num_rows($rslt);
						if ($pc_recs > 0)
							{
							$dup_lead=1;
							$row=mysqli_fetch_row($rslt);
							$dup_lead_list =	$row[0];
							}
						}

					if ($dup_lead < 1)
						{
						if (preg_match("/$phone_number$US$list_id/i", $phone_list))
							{$dup_lead++; $dup++;}
						}
					}

				##### Check for duplicate phone numbers in vicidial_list table for one list_id #####
				if (preg_match("/DUPLIST/i",$dupcheck))
					{
					$dup_lead=0; $moved_lead=0;

					if ($status_mismatch_action) 
						{
						if (preg_match('/USING CHECK/', $status_mismatch_action)) 
							{
							$stmt="SELECT list_id, lead_id from vicidial_list where phone_number='$phone_number' and list_id='$list_id' $multidaySQL $mismatch_clause order by entry_date desc $mismatch_limit";
							} 
						else 
							{
							$stmt="SELECT list_id, lead_id from vicidial_list where phone_number='$phone_number' $mismatch_clause order by entry_date desc $mismatch_limit";
							}
						if ($DB>0) {print $stmt."<BR>";}
						$rslt=mysql_to_mysqli($stmt, $link);
						while ($row=mysqli_fetch_row($rslt)) # switch to upd_row if problem 
							{
							$upd_stmt="update vicidial_list set list_id='$list_id' where lead_id='$row[1]'";
							if ($DB>0) {print $upd_stmt."<BR>";}
							$upd_rslt=mysql_to_mysqli($upd_stmt, $link);
							$moved+=mysqli_affected_rows($link);
							$moved_lead+=mysqli_affected_rows($link);
							$dup_lead=1;
							$dup_lead_list =	$row[0];
							}
						}

					if ($dup_lead < 1)
						{
						$stmt="SELECT count(*) from vicidial_list where phone_number='$phone_number' and list_id='$list_id' $multidaySQL $statuses_clause;";
						$rslt=mysql_to_mysqli($stmt, $link);
						$pc_recs = mysqli_num_rows($rslt);
						if ($pc_recs > 0)
							{
							$row=mysqli_fetch_row($rslt);
							$dup_lead =			$row[0];
							$dup_lead_list =	$list_id;
							}
						}

					if ($dup_lead < 1)
						{
						if (preg_match("/$phone_number$US$list_id/i", $phone_list))
							{$dup_lead++; $dup++;}
						}
					}

				##### Check for duplicate title and alt-phone in vicidial_list table for one list_id #####
				if (preg_match("/DUPTITLEALTPHONELIST/i",$dupcheck))
					{
					$dup_lead=0; $moved_lead=0;

					if ($status_mismatch_action) 
						{
						if (preg_match('/USING CHECK/', $status_mismatch_action)) 
							{
							$stmt="SELECT list_id, lead_id from vicidial_list where title='$title' and alt_phone='$alt_phone' and list_id='$list_id' $multidaySQL $mismatch_clause order by entry_date desc $mismatch_limit";
							} 
						else 
							{
							$stmt="SELECT list_id, lead_id from vicidial_list where title='$title' and alt_phone='$alt_phone' $mismatch_clause order by entry_date desc $mismatch_limit";
							}
						if ($DB>0) {print $stmt."<BR>";}
						$rslt=mysql_to_mysqli($stmt, $link);
						while ($row=mysqli_fetch_row($rslt)) # switch to upd_row if problem 
							{
							$upd_stmt="update vicidial_list set list_id='$list_id' where lead_id='$row[1]'";
							if ($DB>0) {print $upd_stmt."<BR>";}
							$upd_rslt=mysql_to_mysqli($upd_stmt, $link);
							$moved+=mysqli_affected_rows($link);
							$moved_lead+=mysqli_affected_rows($link);
							$dup_lead=1;
							$dup_lead_list =	$row[0];
							}
						}

					if ($dup_lead < 1)
						{
						$stmt="SELECT count(*) from vicidial_list where title='$title' and alt_phone='$alt_phone' and list_id='$list_id' $multidaySQL $statuses_clause;";
						$rslt=mysql_to_mysqli($stmt, $link);
						$pc_recs = mysqli_num_rows($rslt);
						if ($pc_recs > 0)
							{
							$row=mysqli_fetch_row($rslt);
							$dup_lead =			$row[0];
							$dup_lead_list =	$list_id;
							}
						}

					if ($dup_lead < 1)
						{
						if (preg_match("/$alt_phone$title$US$list_id/i",$phone_list))
							{$dup_lead++; $dup++;}
						}
					}

				##### Check for duplicate phone numbers in vicidial_list table entire database #####
				if (preg_match("/DUPTITLEALTPHONESYS/i",$dupcheck))
					{
					$dup_lead=0; $moved_lead=0;

					if ($status_mismatch_action) 
						{
						if (preg_match('/USING CHECK/', $status_mismatch_action)) 
							{
							$stmt="SELECT list_id, lead_id from vicidial_list where title='$title' and alt_phone='$alt_phone' $multidaySQL $mismatch_clause order by entry_date desc $mismatch_limit";
							} 
						else 
							{
							$stmt="SELECT list_id, lead_id from vicidial_list where title='$title' and alt_phone='$alt_phone' $mismatch_clause order by entry_date desc $mismatch_limit";
							}
						if ($DB>0) {print $stmt."<BR>";}
						$rslt=mysql_to_mysqli($stmt, $link);
						while ($row=mysqli_fetch_row($rslt)) # switch to upd_row if problem 
							{
							$upd_stmt="update vicidial_list set list_id='$list_id' where lead_id='$row[1]'";
							if ($DB>0) {print $upd_stmt."<BR>";}
							$upd_rslt=mysql_to_mysqli($upd_stmt, $link);
							$moved+=mysqli_affected_rows($link);
							$moved_lead+=mysqli_affected_rows($link);
							$dup_lead=1;
							$dup_lead_list =	$row[0];
							}
						}

					if ($dup_lead < 1)
						{
						$stmt="SELECT list_id from vicidial_list where title='$title' and alt_phone='$alt_phone' $multidaySQL $statuses_clause;";
						$rslt=mysql_to_mysqli($stmt, $link);
						$pc_recs = mysqli_num_rows($rslt);
						if ($pc_recs > 0)
							{
							$dup_lead=1;
							$row=mysqli_fetch_row($rslt);
							$dup_lead_list =	$row[0];
							}
						}

					if ($dup_lead < 1)
						{
						if (preg_match("/$alt_phone$title$US$list_id/i",$phone_list))
							{$dup_lead++; $dup++;}
						}
					}

				$valid_number=1;
				$invalid_reason='';
				if ( (strlen($phone_number)<5) || (strlen($phone_number)>18) )
					{
					$valid_number=0;
					$invalid_reason = _QXZ("INVALID PHONE NUMBER LENGTH");
					}
				if ( (strlen($web_loader_phone_length)>0) and (strlen($web_loader_phone_length)< 3) and ( (strlen($phone_number) > $web_loader_phone_length) or (strlen($phone_number) < $web_loader_phone_length) ) )
					{
					$valid_number=0;
					$invalid_reason = _QXZ("INVALID REQUIRED PHONE NUMBER LENGTH");
					}
				if ( (preg_match("/PREFIX/",$usacan_check)) and ($valid_number > 0) )
					{
					$USprefix = 	substr($phone_number, 3, 1);
					if ($DB>0) {echo "DEBUG: usacan prefix check - $USprefix|$phone_number\n";}
					if ($USprefix < 2)
						{
						$valid_number=0;
						$invalid_reason = _QXZ("INVALID PHONE NUMBER PREFIX");
						}
					}
				if ( (preg_match("/AREACODE/",$usacan_check)) and ($valid_number > 0) )
					{
					$phone_areacode = substr($phone_number, 0, 3);
					$stmt = "SELECT count(*) from vicidial_phone_codes where areacode='$phone_areacode' and country_code='1';";
					if ($DB>0) {echo "DEBUG: usacan areacode query - $stmt\n";}
					$rslt=mysql_to_mysqli($stmt, $link);
					$row=mysqli_fetch_row($rslt);
					$valid_number=$row[0];
					if ($valid_number < 1)
						{
						$invalid_reason = _QXZ("INVALID PHONE NUMBER AREACODE");
						}
					}
				if ( (preg_match("/NANPA/",$usacan_check)) and ($valid_number > 0) )
					{
					$phone_areacode = substr($phone_number, 0, 3);
					$phone_prefix = substr($phone_number, 3, 3);
					$stmt = "SELECT count(*) from vicidial_nanpa_prefix_codes where areacode='$phone_areacode' and prefix='$phone_prefix';";
					if ($DB>0) {echo "DEBUG: usacan nanpa query - $stmt\n";}
					$rslt=mysql_to_mysqli($stmt, $link);
					$row=mysqli_fetch_row($rslt);
					$valid_number=$row[0];
					if ($valid_number < 1)
						{
						$invalid_reason = _QXZ("INVALID PHONE NUMBER NANPA AREACODE PREFIX");
						}
					}
				if ($international_dnc_scrub and $valid_number > 0)
					{
					$dnc_table_name="vicidial_dnc_".$international_dnc_scrub;
					$dnc_stmt="select count(*) from $dnc_table_name where phone_number='$phone_number'";
					if ($DB>0) {echo "DEBUG: $international_dnc_scrub DNC query - $dnc_stmt\n";}
					$dnc_rslt=mysql_to_mysqli($dnc_stmt, $link);
					$dnc_row=mysqli_fetch_row($dnc_rslt);
					$dnc_matches=$dnc_row[0];
					if ($dnc_matches >0)
						{
						$invalid_reason = _QXZ("NUMBER FOUND IN $international_dnc_scrub DNC LIST");
						}
					}

				if ( ($valid_number>0)  and ($dnc_matches<1) and ($dup_lead<1) and ($list_id >= 100 ))
					{
					if (preg_match("/TITLEALTPHONE/i",$dupcheck))
						{$phone_list .= "$alt_phone$title$US$list_id|";}
					else
						{$phone_list .= "$phone_number$US$list_id|";}

					$gmt_offset = lookup_gmt($phone_code,$USarea,$state,$LOCAL_GMT_OFF_STD,$Shour,$Smin,$Ssec,$Smon,$Smday,$Syear,$postalgmt,$postal_code,$owner,$USprefix);

					if (strlen($custom_SQL)>3)
						{
						$stmtZ = "INSERT INTO vicidial_list (lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner,entry_list_id) values('',\"$entry_date\",\"$modify_date\",\"$status\",\"$user\",\"$vendor_lead_code\",\"$source_id\",\"$list_id\",\"$gmt_offset\",\"$called_since_last_reset\",\"$phone_code\",\"$phone_number\",\"$title\",\"$first_name\",\"$middle_initial\",\"$last_name\",\"$address1\",\"$address2\",\"$address3\",\"$city\",\"$state\",\"$province\",\"$postal_code\",\"$country_code\",\"$gender\",\"$date_of_birth\",\"$alt_phone\",\"$email\",\"$security_phrase\",\"$comments\",0,\"2008-01-01 00:00:00\",\"$rank\",\"$owner\",'$list_id');";
						$rslt=mysql_to_mysqli($stmtZ, $link);
						$affected_rows = mysqli_affected_rows($link);
						$lead_id = mysqli_insert_id($link);
						if ($DB > 0) {echo "<!-- $affected_rows|$lead_id|$stmtZ -->";}
						if ( ($webroot_writable > 0) and ($DB>0) )
							{fwrite($stmt_file, $stmtZ."\r\n");}
						$multistmt='';

						$custom_SQL_query = "INSERT INTO custom_$list_id_override SET lead_id='$lead_id',$custom_SQL;";
						$rslt=mysql_to_mysqli($custom_SQL_query, $link);
						$affected_rows = mysqli_affected_rows($link);
						if ($DB > 0) {echo "<!-- $affected_rows|$custom_SQL_query -->";}
						}
					else
						{
						if ($multi_insert_counter > 8) 
							{
							### insert good record into vicidial_list table ###
							$stmtZ = "INSERT INTO vicidial_list (lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner,entry_list_id) values$multistmt('',\"$entry_date\",\"$modify_date\",\"$status\",\"$user\",\"$vendor_lead_code\",\"$source_id\",\"$list_id\",\"$gmt_offset\",\"$called_since_last_reset\",\"$phone_code\",\"$phone_number\",\"$title\",\"$first_name\",\"$middle_initial\",\"$last_name\",\"$address1\",\"$address2\",\"$address3\",\"$city\",\"$state\",\"$province\",\"$postal_code\",\"$country_code\",\"$gender\",\"$date_of_birth\",\"$alt_phone\",\"$email\",\"$security_phrase\",\"$comments\",0,\"2008-01-01 00:00:00\",\"$rank\",\"$owner\",'0');";
							$rslt=mysql_to_mysqli($stmtZ, $link);
							if ( ($webroot_writable > 0) and ($DB>0) )
								{fwrite($stmt_file, $stmtZ."\r\n");}
							$multistmt='';
							$multi_insert_counter=0;
							}
						else
							{
							$multistmt .= "('',\"$entry_date\",\"$modify_date\",\"$status\",\"$user\",\"$vendor_lead_code\",\"$source_id\",\"$list_id\",\"$gmt_offset\",\"$called_since_last_reset\",\"$phone_code\",\"$phone_number\",\"$title\",\"$first_name\",\"$middle_initial\",\"$last_name\",\"$address1\",\"$address2\",\"$address3\",\"$city\",\"$state\",\"$province\",\"$postal_code\",\"$country_code\",\"$gender\",\"$date_of_birth\",\"$alt_phone\",\"$email\",\"$security_phrase\",\"$comments\",0,\"2008-01-01 00:00:00\",\"$rank\",\"$owner\",'0'),";
							$multi_insert_counter++;
							}
						}
					$good++;
					}
				else
					{
					if ($bad < 1000000)
						{
						if ( $list_id < 100 )
							{
							print "<BR></b><font size=1 color=red>"._QXZ("record")." $total "._QXZ("BAD- PHONE").": $phone_number "._QXZ("ROW").": |$row[0]| "._QXZ("INVALID LIST ID")."</font><b>\n";
							}
						else
							{
							if ($valid_number < 1)
								{
								print "<BR></b><font size=1 color=red>"._QXZ("record")." $total "._QXZ("BAD- PHONE").": $phone_number "._QXZ("ROW").": |$row[0]| "._QXZ("INV").": $phone_number</font><b>\n";
								}
							else if ($dnc_matches > 0)
								{
								print "<BR></b><font size=1 color=red>record $total "._QXZ("BAD- PHONE").": $phone_number "._QXZ("ROW").": |$row[0]| "._QXZ("DNC")."($invalid_reason): $phone_number</font><b>\n";
								}
							else
								{
								print "<BR></b><font size=1 color=red>"._QXZ("record")." $total "._QXZ("BAD- PHONE").": $phone_number "._QXZ("ROW").": |$row[0]| "._QXZ("DUP").": $dup_lead  $dup_lead_list</font><b>\n";
								}
							if ($moved_lead>0) {print "<font size=1 color=blue>| Moved $moved_lead leads </font>\n";}
							}
						}
					$bad++;
					}
				$total++;
				if ($total%100==0) 
					{
					print "<script language='JavaScript1.2'>ShowProgress($good, $bad, $total, $dup, $inv, $post)</script>";
					usleep(1000);
					flush();
					}
				}
			}
		if ($multi_insert_counter!=0) 
			{
			$stmtZ = "INSERT INTO vicidial_list (lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner,entry_list_id) values".substr($multistmt, 0, -1).";";
			mysql_to_mysqli($stmtZ, $link);
			if ( ($webroot_writable > 0) and ($DB>0) )
				{fwrite($stmt_file, $stmtZ."\r\n");}
			}

		### LOG INSERTION Admin Log Table ###
		$stmt="INSERT INTO vicidial_admin_log set event_date='$NOW_TIME', user='$PHP_AUTH_USER', ip_address='$ip', event_section='LISTS', event_type='LOAD', record_id='$list_id_override', event_code='ADMIN LOAD LIST CUSTOM', event_sql='', event_notes='File Name: $leadfile_name, GOOD: $good, BAD: $bad, MOVED: $moved, TOTAL: $total, DEBUG: dedupe_statuses:$dedupe_statuses[0]| dedupe_statuses_override:$dedupe_statuses_override| dupcheck:$dupcheck| status mismatch action: $status_mismatch_action| lead_file:$lead_file| list_id_override:$list_id_override| phone_code_override:$phone_code_override| postalgmt:$postalgmt| template_id:$template_id| usacan_check:$usacan_check| dnc_country_scrub:$international_dnc_scrub| state_conversion:$state_conversion| web_loader_phone_length:$web_loader_phone_length| web_loader_phone_strip:$SSweb_loader_phone_strip|';";
		if ($DB) {echo "|$stmt|\n";}
		$rslt=mysql_to_mysqli($stmt, $link);

		if ($moved>0) {$moved_str=" &nbsp; &nbsp; &nbsp; "._QXZ("MOVED").": $moved ";} else {$moved_str="";}

		print "<BR><BR>"._QXZ("Done")."</B> "._QXZ("GOOD").": $good &nbsp; &nbsp; &nbsp; "._QXZ("BAD").": $bad $moved_str &nbsp; &nbsp; &nbsp; "._QXZ("TOTAL").": $total</font></center>";
		} 
	else 
		{
		print "<center><font face='arial, helvetica' size=3 color='#990000'><B>"._QXZ("ERROR").": "._QXZ("The file does not have the required number of fields to process it").".</B></font></center>";
		}
	}
##### END custom fields submission #####

if (($leadfile) && ($LF_path))
	{
	$total=0; $good=0; $bad=0; $dup=0; $inv=0; $post=0; $moved=0; $phone_list='';

	### LOG INSERTION Admin Log Table ###
	$stmt="INSERT INTO vicidial_admin_log set event_date='$NOW_TIME', user='$PHP_AUTH_USER', ip_address='$ip', event_section='LISTS', event_type='LOAD', record_id='$list_id_override', event_code='ADMIN LOAD LIST', event_sql='', event_notes='File Name: $leadfile_name, DEBUG: dedupe_statuses:$dedupe_statuses[0]| dedupe_statuses_override:$dedupe_statuses_override| dupcheck:$dupcheck | status mismatch action: $status_mismatch_action| lead_file:$lead_file| list_id_override:$list_id_override| phone_code_override:$phone_code_override| postalgmt:$postalgmt| template_id:$template_id| usacan_check:$usacan_check| dnc_country_scrub:$international_dnc_scrub| state_conversion:$state_conversion| web_loader_phone_length:$web_loader_phone_length| web_loader_phone_strip:$SSweb_loader_phone_strip|';";
	if ($DB) {echo "|$stmt|\n";}
	$rslt=mysql_to_mysqli($stmt, $link);

	##### BEGIN process TEMPLATE file layout #####
	if ($file_layout=="template"  && $template_id) 
		{

		$template_stmt="SELECT * from vicidial_custom_leadloader_templates where template_id='$template_id'";
		$template_rslt=mysql_to_mysqli($template_stmt, $link);
		if (mysqli_num_rows($template_rslt)==0) 
			{
			echo _QXZ("Error - template no longer exists"); die;
			} 
		else 
			{
			$template_row=mysqli_fetch_array($template_rslt);
			$template_id=$template_row["template_id"];
			$template_name=$template_row["template_name"];
			$template_description=$template_row["template_description"];
			# Added 2/9/2015 to allow dropdown to override template
			if (!$master_list_override) {
				$list_id_override=$template_row["list_id"];
			}
			$standard_variables=$template_row["standard_variables"];
#			$custom_table=$template_row["custom_table"];
			if (!$master_list_override) {
				$custom_table=$template_row["custom_table"];
			}
			else {
				$custom_table= "custom_$list_id_override";
			}
			$custom_variables=$template_row["custom_variables"];
			$template_statuses=$template_row["template_statuses"];
			if (strlen($template_statuses)>0) {
				$template_statuses=preg_replace('/\|/', "','", $template_statuses);
				$statuses_clause=" and status in ('$template_statuses') ";
			} else {
				$status_mismatch_action="";
			}

			if ($status_mismatch_action) {
				$mismatch_clause=" and status NOT in ('$template_statuses') ";
				if (preg_match('/RECENT/', $status_mismatch_action)) {$mismatch_limit=" limit 1 ";} else {$mismatch_limit="";}
			}

			$standard_fields_ary=explode("|", $standard_variables);
			for ($i=0; $i<count($standard_fields_ary); $i++) 
				{
				if (strlen($standard_fields_ary[$i])>0) 
					{
					$fieldno_ary=explode(",", $standard_fields_ary[$i]);
					$varname=$fieldno_ary[0]."_field";
					$$varname=$fieldno_ary[1];
					} 
				}
			$custom_fields_ary=explode("|", $custom_variables);
			if (count($custom_fields_ary)>0 && strlen($custom_table)>0) 
				{
				$custom_ins_stmt="INSERT INTO $custom_table(";
				for ($i=0; $i<count($custom_fields_ary); $i++)
					{
					if (strlen($custom_fields_ary[$i])>0) 
						{
						$fieldno_ary=explode(",", $custom_fields_ary[$i]);
						$custom_ins_stmt.="$fieldno_ary[0],";
						$varname=$fieldno_ary[0]."_field";
						$$varname=$fieldno_ary[1];
						} 
					}
				$custom_ins_stmt=substr($custom_ins_stmt, 0, -1).") VALUES (";
				}
			}

		print "<script language='JavaScript1.2'>\nif(document.forms[0].leadfile) {document.forms[0].leadfile.disabled=true;}\nif(document.forms[0].submit_file) {document.forms[0].submit_file.disabled=true;}\nif(document.forms[0].reload_page) {document.forms[0].reload_page.disabled=false;}\n</script>";
		flush();

		$delim_set=0;
		# csv xls xlsx ods sxc conversion
		if (preg_match("/\.csv$|\.xls$|\.xlsx$|\.ods$|\.sxc$/i", $leadfile_name)) 
			{
			$leadfile_name = preg_replace('/[^-\.\_0-9a-zA-Z]/','_',$leadfile_name);
			copy($LF_path, "/tmp/$leadfile_name");
			$new_filename = preg_replace("/\.csv$|\.xls$|\.xlsx$|\.ods$|\.sxc$/i", '.txt', $leadfile_name);
			$convert_command = "$WeBServeRRooT/$admin_web_directory/sheet2tab.pl /tmp/$leadfile_name /tmp/$new_filename";
			passthru("$convert_command");
			$lead_file = "/tmp/$new_filename";
			if ($DB > 0) {echo "|$convert_command|";}

			if (preg_match("/\.csv$/i", $leadfile_name)) {$delim_name="CSV: "._QXZ("Comma Separated Values");}
			if (preg_match("/\.xls$/i", $leadfile_name)) {$delim_name="XLS: MS Excel 2000-XP";}
			if (preg_match("/\.xlsx$/i", $leadfile_name)) {$delim_name="XLSX: MS Excel 2007+";}
			if (preg_match("/\.ods$/i", $leadfile_name)) {$delim_name="ODS: OpenOffice.org OpenDocument "._QXZ("Spreadsheet");}
			if (preg_match("/\.sxc$/i", $leadfile_name)) {$delim_name="SXC: OpenOffice.org "._QXZ("First Spreadsheet");}
			$delim_set=1;
			}
		else
			{
			copy($LF_path, "/tmp/vicidial_temp_file.txt");
			$lead_file = "/tmp/vicidial_temp_file.txt";
			}
		$file=fopen("$lead_file", "r");
		if ($webroot_writable > 0)
			{$stmt_file=fopen("$WeBServeRRooT/$admin_web_directory/listloader_stmts.txt", "w");}

		$buffer=fgets($file, 4096);
		$tab_count=substr_count($buffer, "\t");
		$pipe_count=substr_count($buffer, "|");

		if ($delim_set < 1)
			{
			if ($tab_count>$pipe_count)
				{$delim_name=_QXZ("tab-delimited");} 
			else 
				{$delim_name=_QXZ("pipe-delimited");}
			} 
		if ($tab_count>$pipe_count)
			{$delimiter="\t";}
		else 
			{$delimiter="|";}

		$field_check=explode($delimiter, $buffer);

		if (count($field_check)>=2) 
			{
			flush();
			$file=fopen("$lead_file", "r");
			$total=0; $good=0; $bad=0; $dup=0; $inv=0; $post=0; $moved=0; $phone_list='';
			print "<center><font face='arial, helvetica' size=3 color='#009900'><B>"._QXZ("Processing")." $delim_name "._QXZ("file using template")." $template_id... ($tab_count|$pipe_count)\n";
			if (strlen($list_id_override)>0) 
				{
				print "<BR>"._QXZ("LIST ID OVERRIDE FOR THIS FILE").": $list_id_override<BR>";
				}
			if (strlen($phone_code_override)>0) 
				{
				print "<BR>"._QXZ("PHONE CODE OVERRIDE FOR THIS FILE").": $phone_code_override<BR>\n";
				}
			if (strlen($dupcheck)>0) 
				{
				print "<BR>"._QXZ("LEAD DUPLICATE CHECK").": $dupcheck<BR>\n";
				}
			if (strlen($international_dnc_scrub)>0) 
				{
				print "<BR>"._QXZ("INTERNATIONAL DNC SCRUB").": $international_dnc_scrub<BR>\n";
				}
			if (strlen($template_statuses)>0) 
				{
				print "<BR>"._QXZ("OMITTING DUPLICATES AGAINST FOLLOWING STATUSES ONLY").": ".preg_replace('/\'/', '', $template_statuses)."<BR>\n";
				}
			if (strlen($status_mismatch_action)>0) 
				{
				print "<BR>"._QXZ("ACTION FOR DUPLICATE NOT ON STATUS LIST").": $status_mismatch_action<BR>\n";
				}
			if (strlen($state_conversion)>9)
				{
				print "<BR>"._QXZ("CONVERSION OF STATE NAMES TO ABBREVIATIONS ENABLED").": $state_conversion<BR>\n";
				}
			if ( (strlen($web_loader_phone_length)>0) and (strlen($web_loader_phone_length)< 3) )
				{
				print "<BR>"._QXZ("REQUIRED PHONE NUMBER LENGTH").": $web_loader_phone_length<BR>\n";
				}
			if ( (strlen($SSweb_loader_phone_strip)>0) and ($SSweb_loader_phone_strip != 'DISABLED') )
				{
				print "<BR>"._QXZ("PHONE NUMBER PREFIX STRIP SYSTEM SETTING ENABLED").": $SSweb_loader_phone_strip<BR>\n";
				}
			$multidaySQL='';
			if (preg_match("/30DAY|60DAY|90DAY|180DAY|360DAY/i",$dupcheck))
				{
				$day_val=30;
				if (preg_match("/30DAY/i",$dupcheck)) {$day_val=30;}
				if (preg_match("/60DAY/i",$dupcheck)) {$day_val=60;}
				if (preg_match("/90DAY/i",$dupcheck)) {$day_val=90;}
				if (preg_match("/180DAY/i",$dupcheck)) {$day_val=180;}
				if (preg_match("/360DAY/i",$dupcheck)) {$day_val=360;}
				$multiday = date("Y-m-d H:i:s", mktime(date("H"),date("i"),date("s"),date("m"),date("d")-$day_val,date("Y")));
				$multidaySQL = "and entry_date > \"$multiday\"";
				if ($DB > 0) {echo "DEBUG: $day_val day SQL: |$multidaySQL|";}
				}

			#  If a list is being scrubbed against a country's DNC list, block the list from being dialed and purge any lead from the hopper that belongs to that list.
			if (strlen($international_dnc_scrub)>0 && strlen($list_id_override)>0 && $SSenable_international_dncs)
				{
				$upd_dnc_stmt="update vicidial_settings_containers set container_entry=concat('$list_id_override => $international_dnc_scrub', if(length(container_entry)>0, '\r\n', ''), if(container_entry is null, '', container_entry)) where container_id='DNC_CURRENT_BLOCKED_LISTS'";
				$upd_dnc_rslt=mysql_to_mysqli($upd_dnc_stmt, $link);

				$delete_hopper_stmt="delete from vicidial_hopper where list_id='$list_id_override'";
				$delete_hopper_rslt=mysql_to_mysqli($delete_hopper_stmt, $link);
				}


			while (!feof($file)) 
				{
				$record++;
				$buffer=rtrim(fgets($file, 4096));
				$buffer=stripslashes($buffer);

				if (strlen($buffer)>0) 
					{
					$row=explode($delimiter, preg_replace('/[\"]/i', '', $buffer));
					$custom_fields_row=$row;
					$pulldate=date("Y-m-d H:i:s");
					$entry_date =			"$pulldate";
					$modify_date =			"";
					$status =				"NEW";
					$user ="";
					$vendor_lead_code =		$row[$vendor_lead_code_field];
					$source_code =			$row[$source_id_field];
					$source_id=$source_code;
					$list_id =				$row[$list_id_field];
					# Added 2/9/2015 to allow dropdown to override template
					if ($master_list_override) {
						$list_id=$list_id_override;
					}
					$gmt_offset =			'0';
					$called_since_last_reset='N';
					$phone_code =			preg_replace('/[^0-9]/i', '', $row[$phone_code_field]);
					$phone_number =			preg_replace('/[^0-9]/i', '', $row[$phone_number_field]);
					$title =				$row[$title_field];
					$first_name =			$row[$first_name_field];
					$middle_initial =		$row[$middle_initial_field];
					$last_name =			$row[$last_name_field];
					$address1 =				$row[$address1_field];
					$address2 =				$row[$address2_field];
					$address3 =				$row[$address3_field];
					$city =$row[$city_field];
					$state =				$row[$state_field];
					$province =				$row[$province_field];
					$postal_code =			$row[$postal_code_field];
					$country_code =			$row[$country_code_field];
					$gender =				$row[$gender_field];
					$date_of_birth =		$row[$date_of_birth_field];
					$alt_phone =			preg_replace('/[^0-9]/i', '', $row[$alt_phone_field]);
					$email =				$row[$email_field];
					$security_phrase =		$row[$security_phrase_field];
					$comments =				trim($row[$comments_field]);
					$rank =					$row[$rank_field];
					$owner =				$row[$owner_field];
					
					# replace ' " ` \ ; with nothing
					$vendor_lead_code =		preg_replace("/$field_regx/i", "", $vendor_lead_code);
					$source_code =			preg_replace("/$field_regx/i", "", $source_code);
					$source_id = 			preg_replace("/$field_regx/i", "", $source_id);
					$list_id =				preg_replace("/$field_regx/i", "", $list_id);
					$phone_code =			preg_replace("/$field_regx/i", "", $phone_code);
					$phone_number =			preg_replace("/$field_regx/i", "", $phone_number);
					$title =				preg_replace("/$field_regx/i", "", $title);
					$first_name =			preg_replace("/$field_regx/i", "", $first_name);
					$middle_initial =		preg_replace("/$field_regx/i", "", $middle_initial);
					$last_name =			preg_replace("/$field_regx/i", "", $last_name);
					$address1 =				preg_replace("/$field_regx/i", "", $address1);
					$address2 =				preg_replace("/$field_regx/i", "", $address2);
					$address3 =				preg_replace("/$field_regx/i", "", $address3);
					$city =					preg_replace("/$field_regx/i", "", $city);
					$state =				preg_replace("/$field_regx/i", "", $state);
					$province =				preg_replace("/$field_regx/i", "", $province);
					$postal_code =			preg_replace("/$field_regx/i", "", $postal_code);
					$country_code =			preg_replace("/$field_regx/i", "", $country_code);
					$gender =				preg_replace("/$field_regx/i", "", $gender);
					$date_of_birth =		preg_replace("/$field_regx/i", "", $date_of_birth);
					$alt_phone =			preg_replace("/$field_regx/i", "", $alt_phone);
					$email =				preg_replace("/$field_regx/i", "", $email);
					$security_phrase =		preg_replace("/$field_regx/i", "", $security_phrase);
					$comments =				preg_replace("/$field_regx/i", "", $comments);
					$rank =					preg_replace("/$field_regx/i", "", $rank);
					$owner =				preg_replace("/$field_regx/i", "", $owner);
					
					$USarea = 			substr($phone_number, 0, 3);
					$USprefix = 		substr($phone_number, 3, 3);

					if (strlen($list_id_override)>0) 
						{
						$list_id = $list_id_override;
						}
					if (strlen($phone_code_override)>0) 
						{
						$phone_code = $phone_code_override;
						}
					if (strlen($phone_code)<1) {$phone_code = '1';}

					if ( ($state_conversion == 'STATELOOKUP') and (strlen($state) > 3) )
						{
						$stmt = "SELECT state from vicidial_phone_codes where geographic_description='$state' and country_code='$phone_code' limit 1;";
						if ($DB>0) {echo "DEBUG: state conversion query - $stmt\n";}
						$rslt=mysql_to_mysqli($stmt, $link);
						$sc_recs = mysqli_num_rows($rslt);
						if ($sc_recs > 0)
							{
							$row=mysqli_fetch_row($rslt);
							$state_abbr=$row[0];
							if ( (strlen($state_abbr) > 0) and (strlen($state_abbr) < 3 ) )
								{
								if ($DB>0) {echo "DEBUG: state conversion found - $state|$state_abbr\n";}
								$state = $state_abbr;
								}
							}
						}

					if ( (strlen($SSweb_loader_phone_strip)>0) and ($SSweb_loader_phone_strip != 'DISABLED') )
						{
						$phone_number = preg_replace("/^$SSweb_loader_phone_strip/",'',$phone_number);
						}

					##### Check for duplicate phone numbers in vicidial_list table for all lists in a campaign #####
					if (preg_match("/DUPCAMP/i",$dupcheck))
						{
							$dup_lead=0; $moved_lead=0;
							$dup_lists='';
						$stmt="SELECT campaign_id from vicidial_lists where list_id='$list_id';";
						$rslt=mysql_to_mysqli($stmt, $link);
						$ci_recs = mysqli_num_rows($rslt);
						if ($ci_recs > 0)
							{
							$row=mysqli_fetch_row($rslt);
							$dup_camp =			$row[0];

							$stmt="SELECT list_id from vicidial_lists where campaign_id='$dup_camp';";
							$rslt=mysql_to_mysqli($stmt, $link);
							$li_recs = mysqli_num_rows($rslt);
							if ($li_recs > 0)
								{
								$L=0;
								while ($li_recs > $L)
									{
									$row=mysqli_fetch_row($rslt);
									$dup_lists .=	"'$row[0]',";
									$L++;
									}
								$dup_lists = preg_replace('/,$/i', '',$dup_lists);

								if ($status_mismatch_action) 
									{
									if (preg_match('/USING CHECK/', $status_mismatch_action)) 
										{
										$stmt="SELECT list_id, lead_id from vicidial_list where phone_number='$phone_number' and list_id IN($dup_lists) $multidaySQL $mismatch_clause order by entry_date desc $mismatch_limit";
										} 
									else 
										{
										$stmt="SELECT list_id, lead_id from vicidial_list where phone_number='$phone_number' $mismatch_clause order by entry_date desc $mismatch_limit";
										}
									if ($DB>0) {print $stmt."<BR>";}
									$rslt=mysql_to_mysqli($stmt, $link);
									while ($row=mysqli_fetch_row($rslt)) # switch to upd_row if problem 
										{
										$upd_stmt="update vicidial_list set list_id='$list_id' where lead_id='$row[1]'";
										if ($DB>0) {print $upd_stmt."<BR>";}
										$upd_rslt=mysql_to_mysqli($upd_stmt, $link);
										$moved+=mysqli_affected_rows($link);
										$moved_lead+=mysqli_affected_rows($link);
										$dup_lead=1;
										$dup_lead_list =	$row[0];
										}
									}

								if ($dup_lead < 1)
									{
									$stmt="SELECT list_id from vicidial_list where phone_number='$phone_number' and list_id IN($dup_lists) $multidaySQL $statuses_clause limit 1;";
									$rslt=mysql_to_mysqli($stmt, $link);
									$pc_recs = mysqli_num_rows($rslt);
									if ($pc_recs > 0)
										{
										$dup_lead=1;
										$row=mysqli_fetch_row($rslt);
										$dup_lead_list =	$row[0];
										}
									}

								if ($dup_lead < 1)
									{
									if (preg_match("/$phone_number$US$list_id/i", $phone_list))
										{$dup_lead++; $dup++;}
									}
								}
							}
						}
//Yeto to be done from above 

					##### Check for duplicate phone numbers in vicidial_list table entire database #####
					if (preg_match("/DUPSYS/i",$dupcheck))
						{
						$dup_lead=0; $moved_lead=0;

						if ($status_mismatch_action) 
							{
							if (preg_match('/USING CHECK/', $status_mismatch_action)) 
								{
								$stmt="SELECT list_id, lead_id from vicidial_list where phone_number='$phone_number' and list_id IN($dup_lists) $multidaySQL $mismatch_clause order by entry_date desc $mismatch_limit";
								} 
							else 
								{
								$stmt="SELECT list_id, lead_id from vicidial_list where phone_number='$phone_number' $mismatch_clause order by entry_date desc $mismatch_limit";
								}
							if ($DB>0) {print $stmt."<BR>";}
							$rslt=mysql_to_mysqli($stmt, $link);
							while ($row=mysqli_fetch_row($rslt)) # switch to upd_row if problem 
								{
								$upd_stmt="update vicidial_list set list_id='$list_id' where lead_id='$row[1]'";
								if ($DB>0) {print $upd_stmt."<BR>";}
								$upd_rslt=mysql_to_mysqli($upd_stmt, $link);
								$moved+=mysqli_affected_rows($link);
								$moved_lead+=mysqli_affected_rows($link);
								$dup_lead=1;
								$dup_lead_list =	$row[0];
								}
							}

						if ($dup_lead < 1)
							{
							$stmt="SELECT list_id from vicidial_list where phone_number='$phone_number' $multidaySQL $statuses_clause;";
							$rslt=mysql_to_mysqli($stmt, $link);
							$pc_recs = mysqli_num_rows($rslt);
							if ($pc_recs > 0)
								{
								$dup_lead=1;
								$row=mysqli_fetch_row($rslt);
								$dup_lead_list =	$row[0];
								}
							}

						if ($dup_lead < 1)
							{
							if (preg_match("/$phone_number$US$list_id/i", $phone_list))
								{$dup_lead++; $dup++;}
							}
						}

					##### Check for duplicate phone numbers in vicidial_list table for one list_id #####
					if (preg_match("/DUPLIST/i",$dupcheck))
						{
						$dup_lead=0; $moved_lead=0;

						if ($status_mismatch_action) 
							{
							if (preg_match('/USING CHECK/', $status_mismatch_action)) 
								{
								$stmt="SELECT list_id, lead_id from vicidial_list where phone_number='$phone_number' and list_id='$list_id' $multidaySQL $mismatch_clause order by entry_date desc $mismatch_limit";
								} 
							else 
								{
								$stmt="SELECT list_id, lead_id from vicidial_list where phone_number='$phone_number' $mismatch_clause order by entry_date desc $mismatch_limit";
								}
							if ($DB>0) {print $stmt."<BR>";}
							$rslt=mysql_to_mysqli($stmt, $link);
							while ($row=mysqli_fetch_row($rslt)) # switch to upd_row if problem 
								{
								$upd_stmt="update vicidial_list set list_id='$list_id' where lead_id='$row[1]'";
								if ($DB>0) {print $upd_stmt."<BR>";}
								$upd_rslt=mysql_to_mysqli($upd_stmt, $link);
								$moved+=mysqli_affected_rows($link);
								$moved_lead+=mysqli_affected_rows($link);
								$dup_lead=1;
								$dup_lead_list =	$row[0];
								}
							}

						if ($dup_lead < 1)
							{
							$stmt="SELECT count(*) from vicidial_list where phone_number='$phone_number' and list_id='$list_id' $multidaySQL $statuses_clause;";
							$rslt=mysql_to_mysqli($stmt, $link);
							$pc_recs = mysqli_num_rows($rslt);
							if ($pc_recs > 0)
								{
								$row=mysqli_fetch_row($rslt);
								$dup_lead =			$row[0];
								}
							}

						if ($dup_lead < 1)
							{
							if (preg_match("/$phone_number$US$list_id/i", $phone_list))
								{$dup_lead++; $dup++;}
							}
						}

					##### Check for duplicate title and alt-phone in vicidial_list table for one list_id #####
					if (preg_match("/DUPTITLEALTPHONELIST/i",$dupcheck))
						{
						$dup_lead=0; $moved_lead=0;

						if ($status_mismatch_action) 
							{
							if (preg_match('/USING CHECK/', $status_mismatch_action)) 
								{
								$stmt="SELECT list_id, lead_id from vicidial_list where title='$title' and alt_phone='$alt_phone' and list_id='$list_id' $multidaySQL $mismatch_clause order by entry_date desc $mismatch_limit";
								} 
							else 
								{
								$stmt="SELECT list_id, lead_id from vicidial_list where title='$title' and alt_phone='$alt_phone' $mismatch_clause order by entry_date desc $mismatch_limit";
								}
							if ($DB>0) {print $stmt."<BR>";}
							$rslt=mysql_to_mysqli($stmt, $link);
							while ($row=mysqli_fetch_row($rslt)) # switch to upd_row if problem 
								{
								$upd_stmt="update vicidial_list set list_id='$list_id' where lead_id='$row[1]'";
								if ($DB>0) {print $upd_stmt."<BR>";}
								$upd_rslt=mysql_to_mysqli($upd_stmt, $link);
								$moved+=mysqli_affected_rows($link);
								$moved_lead+=mysqli_affected_rows($link);
								$dup_lead=1;
								$dup_lead_list =	$row[0];
								}
							}

						if ($dup_lead < 1)
							{
							$stmt="SELECT count(*) from vicidial_list where title='$title' and alt_phone='$alt_phone' and list_id='$list_id' $multidaySQL $statuses_clause;";
							$rslt=mysql_to_mysqli($stmt, $link);
							$pc_recs = mysqli_num_rows($rslt);
							if ($pc_recs > 0)
								{
								$row=mysqli_fetch_row($rslt);
								$dup_lead =			$row[0];
								$dup_lead_list =	$list_id;
								}
							}

						if ($dup_lead < 1)
							{
							if (preg_match("/$alt_phone$title$US$list_id/i",$phone_list))
								{$dup_lead++; $dup++;}
							}
						}

					##### Check for duplicate phone numbers in vicidial_list table entire database #####
					if (preg_match("/DUPTITLEALTPHONESYS/i",$dupcheck))
						{
						$dup_lead=0; $moved_lead=0;

						if ($status_mismatch_action) 
							{
							if (preg_match('/USING CHECK/', $status_mismatch_action)) 
								{
								$stmt="SELECT list_id, lead_id from vicidial_list where title='$title' and alt_phone='$alt_phone' $multidaySQL $mismatch_clause order by entry_date desc $mismatch_limit";
								} 
							else 
								{
								$stmt="SELECT list_id, lead_id from vicidial_list where title='$title' and alt_phone='$alt_phone' $mismatch_clause order by entry_date desc $mismatch_limit";
								}
							if ($DB>0) {print $stmt."<BR>";}
							$rslt=mysql_to_mysqli($stmt, $link);
							while ($row=mysqli_fetch_row($rslt)) # switch to upd_row if problem 
								{
								$upd_stmt="update vicidial_list set list_id='$list_id' where lead_id='$row[1]'";
								if ($DB>0) {print $upd_stmt."<BR>";}
								$upd_rslt=mysql_to_mysqli($upd_stmt, $link);
								$moved+=mysqli_affected_rows($link);
								$moved_lead+=mysqli_affected_rows($link);
								$dup_lead=1;
								$dup_lead_list =	$row[0];
								}
							}

						if ($dup_lead < 1)
							{
							$stmt="SELECT list_id from vicidial_list where title='$title' and alt_phone='$alt_phone' $multidaySQL $statuses_clause;";
							$rslt=mysql_to_mysqli($stmt, $link);
							$pc_recs = mysqli_num_rows($rslt);
							if ($pc_recs > 0)
								{
								$dup_lead=1;
								$row=mysqli_fetch_row($rslt);
								$dup_lead_list =	$row[0];
								}
							}

						if ($dup_lead < 1)
							{
							if (preg_match("/$alt_phone$title$US$list_id/i",$phone_list))
								{$dup_lead++; $dup++;}
							}
						}

					$valid_number=1;
					$invalid_reason='';
					if ( (strlen($phone_number)<5) || (strlen($phone_number)>18) )
						{
						$valid_number=0;
						$invalid_reason = _QXZ("INVALID PHONE NUMBER LENGTH");
						}
					if ( (strlen($web_loader_phone_length)>0) and (strlen($web_loader_phone_length)< 3) and ( (strlen($phone_number) > $web_loader_phone_length) or (strlen($phone_number) < $web_loader_phone_length) ) )
						{
						$valid_number=0;
						$invalid_reason = _QXZ("INVALID REQUIRED PHONE NUMBER LENGTH");
						}
					if ( (preg_match("/PREFIX/",$usacan_check)) and ($valid_number > 0) )
						{
						$USprefix = 	substr($phone_number, 3, 1);
						if ($DB>0) {echo _QXZ("DEBUG: usacan prefix check")." - $USprefix|$phone_number\n";}
						if ($USprefix < 2)
							{
							$valid_number=0;
							$invalid_reason = _QXZ("INVALID PHONE NUMBER PREFIX");
							}
						}
					if ( (preg_match("/AREACODE/",$usacan_check)) and ($valid_number > 0) )
						{
						$phone_areacode = substr($phone_number, 0, 3);
						$stmt = "SELECT count(*) from vicidial_phone_codes where areacode='$phone_areacode' and country_code='1';";
						if ($DB>0) {echo "DEBUG: usacan areacode query - $stmt\n";}
						$rslt=mysql_to_mysqli($stmt, $link);
						$row=mysqli_fetch_row($rslt);
						$valid_number=$row[0];
						if ($valid_number < 1)
							{
							$invalid_reason = _QXZ("INVALID PHONE NUMBER AREACODE");
							}
						}
					if ( (preg_match("/NANPA/",$usacan_check)) and ($valid_number > 0) )
						{
						$phone_areacode = substr($phone_number, 0, 3);
						$phone_prefix = substr($phone_number, 3, 3);
						$stmt = "SELECT count(*) from vicidial_nanpa_prefix_codes where areacode='$phone_areacode' and prefix='$phone_prefix';";
						if ($DB>0) {echo "DEBUG: usacan nanpa query - $stmt\n";}
						$rslt=mysql_to_mysqli($stmt, $link);
						$row=mysqli_fetch_row($rslt);
						$valid_number=$row[0];
						if ($valid_number < 1)
							{
							$invalid_reason = _QXZ("INVALID PHONE NUMBER NANPA AREACODE PREFIX");
							}
						}
					if ($international_dnc_scrub and $valid_number > 0)
						{
						$dnc_table_name="vicidial_dnc_".$international_dnc_scrub;
						$dnc_stmt="select count(*) from $dnc_table_name where phone_number='$phone_number'";
						if ($DB>0) {echo "DEBUG: $international_dnc_scrub DNC query - $dnc_stmt\n";}
						$dnc_rslt=mysql_to_mysqli($dnc_stmt, $link);
						$dnc_row=mysqli_fetch_row($dnc_rslt);
						$dnc_matches=$dnc_row[0];
						if ($dnc_matches >0)
							{
							$invalid_reason = _QXZ("NUMBER FOUND IN $international_dnc_scrub DNC LIST");
							}
						}

					if ( ($valid_number>0) and ($dnc_matches<1) and ($dup_lead<1) and ($list_id >= 100 ))
						{
						if (preg_match("/TITLEALTPHONE/i",$dupcheck))
							{$phone_list .= "$alt_phone$title$US$list_id|";}
						else
							{$phone_list .= "$phone_number$US$list_id|";}

						$gmt_offset = lookup_gmt($phone_code,$USarea,$state,$LOCAL_GMT_OFF_STD,$Shour,$Smin,$Ssec,$Smon,$Smday,$Syear,$postalgmt,$postal_code,$owner,$USprefix);

/*						if ($multi_insert_counter > 8) 
#							{
							### insert good deal into pending_transactions table ###
							$stmtZ = "INSERT INTO vicidial_list (lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner,entry_list_id) values$multistmt('','$entry_date','$modify_date','$status','$user','$vendor_lead_code','$source_id','$list_id','$gmt_offset','$called_since_last_reset','$phone_code','$phone_number','$title','$first_name','$middle_initial','$last_name','$address1','$address2','$address3','$city','$state','$province','$postal_code','$country_code','$gender','$date_of_birth','$alt_phone','$email','$security_phrase','$comments',0,'2008-01-01 00:00:00','$rank','$owner','0');";
							$rslt=mysql_to_mysqli($stmtZ, $link);
							if ( ($webroot_writable > 0) and ($DB>0) )
								{fwrite($stmt_file, $stmtZ."\r\n");}
							$multistmt=''; */
							$multi_insert_counter=0;
							$stmtZ = "INSERT INTO vicidial_list (lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner,entry_list_id) values('',\"$entry_date\",\"$modify_date\",\"$status\",\"$user\",\"$vendor_lead_code\",\"$source_id\",\"$list_id\",\"$gmt_offset\",\"$called_since_last_reset\",\"$phone_code\",\"$phone_number\",\"$title\",\"$first_name\",\"$middle_initial\",\"$last_name\",\"$address1\",\"$address2\",\"$address3\",\"$city\",\"$state\",\"$province\",\"$postal_code\",\"$country_code\",\"$gender\",\"$date_of_birth\",\"$alt_phone\",\"$email\",\"$security_phrase\",\"$comments\",0,\"2008-01-01 00:00:00\",\"$rank\",\"$owner\",'$list_id');";
							$rslt=mysql_to_mysqli($stmtZ, $link);
							$affected_rows = mysqli_affected_rows($link);
							$lead_id = mysqli_insert_id($link);
							if ($DB > 0) {echo "<!-- $affected_rows|$lead_id|$stmtZ -->";}
							if ( ($webroot_writable > 0) and ($DB>0) )
								{fwrite($stmt_file, $stmtZ."\r\n");}
							$multistmt='';

							#$custom_SQL_query = "INSERT INTO custom_$list_id_override SET lead_id='$lead_id',$custom_SQL;";
							#$rslt=mysql_to_mysqli($custom_SQL_query, $link);
							#$affected_rows = mysqli_affected_rows($link);

							$custom_tbl_stmt="SHOW TABLES LIKE '$custom_table'";
							$custom_tbl_rslt=mysql_to_mysqli($custom_tbl_stmt, $link);
							if(mysqli_num_rows($custom_tbl_rslt)>0)
								{
								$custom_ins_stmt="INSERT INTO $custom_table(lead_id";
								$custom_SQL_values="";
								for ($q=0; $q<count($custom_fields_ary); $q++) 
									{
									if (strlen($custom_fields_ary[$q])>0) 
										{
										$fieldno_ary=explode(",", $custom_fields_ary[$q]);
										$varname=$fieldno_ary[0]."_field";
										$$varname=$fieldno_ary[1];
										$custom_ins_stmt.=",$fieldno_ary[0]";

										if ( (preg_match("/cf_encrypt/",$SSactive_modules)) and (strlen($custom_fields_row[$$varname]) > 0) )
											{
											$field_encrypt='N';
											$stmt = "SELECT field_encrypt from vicidial_lists_fields where list_id='$list_id' and field_label='$fieldno_ary[0]' limit 1;";
											if ($DB>0) {echo "DEBUG: cf_encrypt query - $stmt\n";}
											$rslt=mysql_to_mysqli($stmt, $link);
											$sc_recs = mysqli_num_rows($rslt);
											if ($sc_recs > 0)
												{
												$row=mysqli_fetch_row($rslt);
												$field_encrypt = $row[0];
												}
											if ($field_encrypt == 'Y')
												{
												$field_enc=$MT;
												$field_value = $custom_fields_row[$$varname];
												$field_value = base64_encode($field_value);
												exec("../agc/aes.pl --encrypt --text=$field_value", $field_enc);
												$field_enc_ct = count($field_enc);
												$k=0;
												$field_enc_all='';
												while ($field_enc_ct > $k)
													{
													$field_enc_all .= $field_enc[$k];
													$k++;
													}
												$custom_fields_row[$$varname] = preg_replace("/CRYPT: |\n|\r|\t/",'',$field_enc_all);
												}
											}

										$custom_SQL_values.=",\"".$custom_fields_row[$$varname]."\"";
										} 
									}
								$custom_ins_stmt.=") VALUES('$lead_id'$custom_SQL_values)";
								$custom_rslt=mysql_to_mysqli($custom_ins_stmt, $link);
								$affected_rows = mysqli_affected_rows($link);
								echo "<!-- $custom_ins_stmt //-->\n";
								if ( ($webroot_writable > 0) and ($DB>0) )
									{fwrite($stmt_file, $custom_ins_stmt."\r\n");}
								}
/*								
							} 
						else 
							{
							$multistmt .= "('','$entry_date','$modify_date','$status','$user','$vendor_lead_code','$source_id','$list_id','$gmt_offset','$called_since_last_reset','$phone_code','$phone_number','$title','$first_name','$middle_initial','$last_name','$address1','$address2','$address3','$city','$state','$province','$postal_code','$country_code','$gender','$date_of_birth','$alt_phone','$email','$security_phrase','$comments',0,'2008-01-01 00:00:00','$rank','$owner','0'),";

							$custom_multistmt.="(";
							for ($q=0; $q<count($custom_fields_ary); $q++)
								{
								if (strlen($custom_fields_ary[$q])>0) 
									{
									$custom_fieldno_ary=explode(",", $custom_fields_ary[$q]);
									$varname=$custom_fieldno_ary[0];
									$$varname=$row[$custom_fieldno_ary[1]];
									$custom_multistmt.="'".$$varname."',";
									}
								}
							$custom_multistmt=substr($custom_multistmt, 0, -1)."),";
							$multi_insert_counter++;
							} */
						$good++;
						}
					else
						{
						if ($bad < 1000000)
							{
							if ( $list_id < 100 )
								{
								print "<BR></b><font size=1 color=red>"._QXZ("record")." $total "._QXZ("BAD- PHONE").": $phone_number "._QXZ("ROW").":|$row[0]| "._QXZ("INVALID LIST ID")."</font><b>\n";
								}
							else
								{
								if ($valid_number < 1)
									{
									print "<BR></b><font size=1 color=red>"._QXZ("record")." $total "._QXZ("BAD- PHONE").": $phone_number "._QXZ("ROW").":|$row[0]| "._QXZ("INV").": $phone_number</font><b>\n";
									}
								else if ($dnc_matches > 0)
									{
									print "<BR></b><font size=1 color=red>record $total "._QXZ("BAD- PHONE").": $phone_number "._QXZ("ROW").": |$row[0]| "._QXZ("DNC")."($invalid_reason): $phone_number</font><b>\n";
									}
								else
									{
									print "<BR></b><font size=1 color=red>"._QXZ("record")." $total "._QXZ("BAD- PHONE").": $phone_number "._QXZ("ROW").":|$row[0] | "._QXZ("DUP").": $dup_lead  $dup_lead_list</font><b>\n";
									}
								if ($moved_lead>0) {print "<font size=1 color=blue>| Moved $moved_lead leads </font>\n";}
								}
							}
						$bad++;
						}
					$total++;
					if ($total%100==0) 
						{
						print "<script language='JavaScript1.2'>ShowProgress($good, $bad, $total, $dup, $inv, $post)</script>";
						usleep(1000);
						flush();
						}
					}
				}
			if ($multi_insert_counter!=0) 
				{
				$stmtZ = "INSERT INTO vicidial_list (lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner,entry_list_id) values".substr($multistmt, 0, -1).";";
				mysql_to_mysqli($stmtZ, $link);
				if ( ($webroot_writable > 0) and ($DB>0) )
					{fwrite($stmt_file, $stmtZ."\r\n");}

				$custom_ins_stmt="INSERT INTO $custom_table(";
				for ($q=0; $q<count($custom_fields_ary); $q++) 
					{
					if (strlen($custom_fields_ary[$q])>0) 
						{
						$fieldno_ary=explode(",", $custom_fields_ary[$q]);
						$custom_ins_stmt.="$fieldno_ary[0],";
						$varname=$fieldno_ary[0]."_field";
						$$varname=$fieldno_ary[1];
						} 
					}
				$custom_ins_stmt=substr($custom_ins_stmt, 0, -1).") VALUES".substr($custom_multistmt, 0, -1);
				mysql_to_mysqli($custom_ins_stmt, $link);
				if ( ($webroot_writable > 0) and ($DB>0) )
					{fwrite($stmt_file, $custom_ins_stmt."\r\n");}
				}
			### LOG INSERTION Admin Log Table ###
			$stmt="INSERT INTO vicidial_admin_log set event_date='$NOW_TIME', user='$PHP_AUTH_USER', ip_address='$ip', event_section='LISTS', event_type='LOAD', record_id='$list_id_override', event_code='ADMIN LOAD LIST STANDARD', event_sql='', event_notes='File Name: $leadfile_name, GOOD: $good, BAD: $bad, MOVED: $moved, TOTAL: $total, DEBUG: dedupe_statuses:$dedupe_statuses[0]| dedupe_statuses_override:$dedupe_statuses_override| dupcheck:$dupcheck| status mismatch action: $status_mismatch_action| lead_file:$lead_file| list_id_override:$list_id_override| phone_code_override:$phone_code_override| postalgmt:$postalgmt| template_id:$template_id| usacan_check:$usacan_check| dnc_country_scrub:$international_dnc_scrub| state_conversion:$state_conversion| web_loader_phone_length:$web_loader_phone_length| web_loader_phone_strip:$SSweb_loader_phone_strip|';";
			if ($DB) {echo "|$stmt|\n";}
			$rslt=mysql_to_mysqli($stmt, $link);

			if ($moved>0) {$moved_str=" &nbsp; &nbsp; &nbsp; "._QXZ("MOVED").": $moved ";} else {$moved_str="";}

			print "<BR><BR>"._QXZ("Done")."</B> "._QXZ("GOOD").": $good &nbsp; &nbsp; &nbsp; "._QXZ("BAD").": $bad $moved_str &nbsp; &nbsp; &nbsp; "._QXZ("TOTAL").": $total</font></center>";
			}
		else 
			{
			print "<center><font face='arial, helvetica' size=3 color='#990000'><B>"._QXZ("ERROR: The file does not have the required number of fields to process it").".</B></font></center>";
			}
					
		}

	##### BEGIN process standard file layout #####
if ($file_layout == "standard") {
    // Disable form elements during processing
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            const formElements = ['leadfile', 'submit_file', 'reload_page'];
            formElements.forEach(id => {
                const element = document.getElementById(id);
                if (element) element.disabled = true;
            });
        });
    </script>";
    flush();

    // Create modern progress display
    echo "<div class='card mb-4'>
        <div class='card-header bg-primary text-white'>
            <h4 class='mb-0'><i class='fas fa-file-alt me-2'></i>" . _QXZ("Processing Standard File Layout") . "</h4>
        </div>
        <div class='card-body'>
            <div id='progress-container' class='mb-3'>
                <div class='progress' style='height: 25px;'>
                    <div id='progress-bar' class='progress-bar progress-bar-striped progress-bar-animated' role='progressbar' style='width: 0%' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100'>0%</div>
                </div>
            </div>
            <div id='progress-stats' class='row text-center'>
                <div class='col-md-3'>
                    <div class='card bg-success text-white'>
                        <div class='card-body py-2'>
                            <h5 class='mb-0'>" . _QXZ("Good") . ": <span id='good-count'>0</span></h5>
                        </div>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='card bg-danger text-white'>
                        <div class='card-body py-2'>
                            <h5 class='mb-0'>" . _QXZ("Bad") . ": <span id='bad-count'>0</span></h5>
                        </div>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='card bg-warning text-dark'>
                        <div class='card-body py-2'>
                            <h5 class='mb-0'>" . _QXZ("Duplicates") . ": <span id='dup-count'>0</span></h5>
                        </div>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class='card bg-info text-white'>
                        <div class='card-body py-2'>
                            <h5 class='mb-0'>" . _QXZ("Total") . ": <span id='total-count'>0</span></h5>
                        </div>
                    </div>
                </div>
            </div>
            <div id='processing-log' class='mt-3' style='height: 200px; overflow-y: auto; background-color: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace; font-size: 0.9rem;'></div>
        </div>
    </div>";
    
    flush();

    $delim_set = 0;
    // csv xls xlsx ods sxc conversion
    if (preg_match("/\.csv$|\.xls$|\.xlsx$|\.ods$|\.sxc$/i", $leadfile_name)) {
        $leadfile_name = preg_replace('/[^-\.\_0-9a-zA-Z]/', '_', $leadfile_name);
        copy($LF_path, "/tmp/$leadfile_name");
        $new_filename = preg_replace("/\.csv$|\.xls$|\.xlsx$|\.ods$|\.sxc$/i", '.txt', $leadfile_name);
        $convert_command = "$WeBServeRRooT/$admin_web_directory/sheet2tab.pl /tmp/$leadfile_name /tmp/$new_filename";
        passthru("$convert_command");
        $lead_file = "/tmp/$new_filename";
        if ($DB > 0) {
            echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-info mb-1\">|$convert_command|</div>';</script>";
        }

        if (preg_match("/\.csv$/i", $leadfile_name)) {
            $delim_name = "CSV: " . _QXZ("Comma Separated Values");
        }
        if (preg_match("/\.xls$/i", $leadfile_name)) {
            $delim_name = "XLS: MS Excel 2000-XP";
        }
        if (preg_match("/\.xlsx$/i", $leadfile_name)) {
            $delim_name = "XLSX: MS Excel 2007+";
        }
        if (preg_match("/\.ods$/i", $leadfile_name)) {
            $delim_name = "ODS: OpenOffice.org OpenDocument " . _QXZ("Spreadsheet");
        }
        if (preg_match("/\.sxc$/i", $leadfile_name)) {
            $delim_name = "SXC: OpenOffice.org " . _QXZ("First Spreadsheet");
        }
        $delim_set = 1;
    } else {
        copy($LF_path, "/tmp/vicidial_temp_file.txt");
        $lead_file = "/tmp/vicidial_temp_file.txt";
    }
    
    $file = fopen("$lead_file", "r");
    if ($webroot_writable > 0) {
        $stmt_file = fopen("$WeBServeRRooT/$admin_web_directory/listloader_stmts.txt", "w");
    }

    $buffer = fgets($file, 4096);
    $tab_count = substr_count($buffer, "\t");
    $pipe_count = substr_count($buffer, "|");

    if ($delim_set < 1) {
        if ($tab_count > $pipe_count) {
            $delim_name = _QXZ("tab-delimited");
        } else {
            $delim_name = _QXZ("pipe-delimited");
        }
    }
    
    if ($tab_count > $pipe_count) {
        $delimiter = "\t";
    } else {
        $delimiter = "|";
    }

    $field_check = explode($delimiter, $buffer);

    if (count($field_check) >= 2) {
        flush();
        $file = fopen("$lead_file", "r");
        $total = 0;
        $good = 0;
        $bad = 0;
        $dup = 0;
        $inv = 0;
        $post = 0;
        $moved = 0;
        $phone_list = '';
        
        // Add processing log entry
        echo "<script>
            document.getElementById('processing-log').innerHTML += '<div class=\"text-success mb-1\"><i class=\"fas fa-check-circle me-1\"></i>" . _QXZ("Processing") . " $delim_name " . _QXZ("file") . "... ($tab_count|$pipe_count)</div>';
        </script>";

        // Handle dedupe statuses
        if (count($dedupe_statuses) > 0) {
            $statuses_clause = " and status in (";
            $status_dedupe_str = "";
            for ($ds = 0; $ds < count($dedupe_statuses); $ds++) {
                $dedupe_statuses[$ds] = preg_replace('/[^-_0-9\p{L}]/u', '', $dedupe_statuses[$ds]);
                $statuses_clause .= "'$dedupe_statuses[$ds]',";
                $status_dedupe_str .= "$dedupe_statuses[$ds], ";
                if (preg_match('/\-\-ALL\-\-/', $dedupe_statuses[$ds])) {
                    $status_mismatch_action = ""; // Important - if ALL statuses are selected there's no need for this feature
                    $statuses_clause = "";
                    $status_dedupe_str = "";
                    break;
                }
            }
            $statuses_clause = preg_replace('/,$/', "", $statuses_clause);
            $status_dedupe_str = preg_replace('/,\s$/', "", $status_dedupe_str);
            if ($statuses_clause != "") {
                $statuses_clause .= ")";
            }

            if ($status_mismatch_action) {
                $mismatch_clause = " and status not in ('" . implode("','", $dedupe_statuses) . "') ";
                if (preg_match('/RECENT/', $status_mismatch_action)) {
                    $mismatch_limit = " limit 1 ";
                } else {
                    $mismatch_limit = "";
                }
            }
        }

        // Display configuration settings
        $config_display = "<div class='card mb-3'><div class='card-body'><h5 class='card-title'><i class='fas fa-cog me-2'></i>" . _QXZ("Configuration Settings") . "</h5><ul class='list-group list-group-flush'>";
        
        if (strlen($list_id_override) > 0) {
            $config_display .= "<li class='list-group-item'><strong>" . _QXZ("List ID Override") . ":</strong> $list_id_override</li>";
        }
        
        if (strlen($phone_code_override) > 0) {
            $config_display .= "<li class='list-group-item'><strong>" . _QXZ("Phone Code Override") . ":</strong> $phone_code_override</li>";
        }
        
        if (strlen($dupcheck) > 0) {
            $config_display .= "<li class='list-group-item'><strong>" . _QXZ("Lead Duplicate Check") . ":</strong> $dupcheck</li>";
        }
        
        if (strlen($international_dnc_scrub) > 0) {
            $config_display .= "<li class='list-group-item'><strong>" . _QXZ("International DNC Scrub") . ":</strong> $international_dnc_scrub</li>";
        }
        
        if (strlen($status_dedupe_str) > 0) {
            $config_display .= "<li class='list-group-item'><strong>" . _QXZ("Omitting Duplicates Against Following Statuses Only") . ":</strong> $status_dedupe_str</li>";
        }
        
        if (strlen($status_mismatch_action) > 0) {
            $config_display .= "<li class='list-group-item'><strong>" . _QXZ("Action For Duplicate Not On Status List") . ":</strong> $status_mismatch_action</li>";
        }
        
        if (strlen($state_conversion) > 9) {
            $config_display .= "<li class='list-group-item'><strong>" . _QXZ("Conversion Of State Names To Abbreviations Enabled") . ":</strong> $state_conversion</li>";
        }
        
        if ((strlen($web_loader_phone_length) > 0) and (strlen($web_loader_phone_length) < 3)) {
            $config_display .= "<li class='list-group-item'><strong>" . _QXZ("Required Phone Number Length") . ":</strong> $web_loader_phone_length</li>";
        }
        
        if ((strlen($SSweb_loader_phone_strip) > 0) and ($SSweb_loader_phone_strip != 'DISABLED')) {
            $config_display .= "<li class='list-group-item'><strong>" . _QXZ("Phone Number Prefix Strip System Setting Enabled") . ":</strong> $SSweb_loader_phone_strip</li>";
        }
        
        $config_display .= "</ul></div></div>";
        echo $config_display;
        
        // Handle multiday duplicate checks
        $multidaySQL = '';
        if (preg_match("/30DAY|60DAY|90DAY|180DAY|360DAY/i", $dupcheck)) {
            $day_val = 30;
            if (preg_match("/30DAY/i", $dupcheck)) {
                $day_val = 30;
            }
            if (preg_match("/60DAY/i", $dupcheck)) {
                $day_val = 60;
            }
            if (preg_match("/90DAY/i", $dupcheck)) {
                $day_val = 90;
            }
            if (preg_match("/180DAY/i", $dupcheck)) {
                $day_val = 180;
            }
            if (preg_match("/360DAY/i", $dupcheck)) {
                $day_val = 360;
            }
            $multiday = date("Y-m-d H:i:s", mktime(date("H"), date("i"), date("s"), date("m"), date("d") - $day_val, date("Y")));
            $multidaySQL = "and entry_date > \"$multiday\"";
            if ($DB > 0) {
                echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-info mb-1\">DEBUG: $day_val day SQL: |$multidaySQL|</div>';</script>";
            }
        }

        // Handle international DNC scrub
        if (strlen($international_dnc_scrub) > 0 && strlen($list_id_override) > 0 && $SSenable_international_dncs) {
            $upd_dnc_stmt = "update vicidial_settings_containers set container_entry=concat('$list_id_override => $international_dnc_scrub', if(length(container_entry)>0, '\r\n', ''), if(container_entry is null, '', container_entry)) where container_id='DNC_CURRENT_BLOCKED_LISTS'";
            $upd_dnc_rslt = mysql_to_mysqli($upd_dnc_stmt, $link);

            $delete_hopper_stmt = "delete from vicidial_hopper where list_id='$list_id_override'";
            $delete_hopper_rslt = mysql_to_mysqli($delete_hopper_stmt, $link);
        }

        // Process each record in the file
        while (!feof($file)) {
            $record++;
            $buffer = rtrim(fgets($file, 4096));
            $buffer = stripslashes($buffer);

            if (strlen($buffer) > 0) {
                $row = explode($delimiter, preg_replace('/[\"]/i', '', $buffer));

                $pulldate = date("Y-m-d H:i:s");
                $entry_date = "$pulldate";
                $modify_date = "";
                $status = "NEW";
                $user = "";
                $vendor_lead_code = $row[0];
                $source_code = $row[1];
                $source_id = $source_code;
                $list_id = $row[2];
                $gmt_offset = '0';
                $called_since_last_reset = 'N';
                $phone_code = preg_replace('/[^0-9]/i', '', $row[3]);
                $phone_number = preg_replace('/[^0-9]/i', '', $row[4]);
                $title = $row[5];
                $first_name = $row[6];
                $middle_initial = $row[7];
                $last_name = $row[8];
                $address1 = $row[9];
                $address2 = $row[10];
                $address3 = $row[11];
                $city = $row[12];
                $state = $row[13];
                $province = $row[14];
                $postal_code = $row[15];
                $country_code = $row[16];
                $gender = $row[17];
                $date_of_birth = $row[18];
                $alt_phone = preg_replace('/[^0-9]/i', '', $row[19]);
                $email = $row[20];
                $security_phrase = $row[21];
                $comments = trim($row[22]);
                $rank = $row[23];
                $owner = $row[24];
                
                // Sanitize fields
                $vendor_lead_code = preg_replace("/$field_regx/i", "", $vendor_lead_code);
                $source_code = preg_replace("/$field_regx/i", "", $source_code);
                $source_id = preg_replace("/$field_regx/i", "", $source_id);
                $list_id = preg_replace("/$field_regx/i", "", $list_id);
                $phone_code = preg_replace("/$field_regx/i", "", $phone_code);
                $phone_number = preg_replace("/$field_regx/i", "", $phone_number);
                $title = preg_replace("/$field_regx/i", "", $title);
                $first_name = preg_replace("/$field_regx/i", "", $first_name);
                $middle_initial = preg_replace("/$field_regx/i", "", $middle_initial);
                $last_name = preg_replace("/$field_regx/i", "", $last_name);
                $address1 = preg_replace("/$field_regx/i", "", $address1);
                $address2 = preg_replace("/$field_regx/i", "", $address2);
                $address3 = preg_replace("/$field_regx/i", "", $address3);
                $city = preg_replace("/$field_regx/i", "", $city);
                $state = preg_replace("/$field_regx/i", "", $state);
                $province = preg_replace("/$field_regx/i", "", $province);
                $postal_code = preg_replace("/$field_regx/i", "", $postal_code);
                $country_code = preg_replace("/$field_regx/i", "", $country_code);
                $gender = preg_replace("/$field_regx/i", "", $gender);
                $date_of_birth = preg_replace("/$field_regx/i", "", $date_of_birth);
                $alt_phone = preg_replace("/$field_regx/i", "", $alt_phone);
                $email = preg_replace("/$field_regx/i", "", $email);
                $security_phrase = preg_replace("/$field_regx/i", "", $security_phrase);
                $comments = preg_replace("/$field_regx/i", "", $comments);
                $rank = preg_replace("/$field_regx/i", "", $rank);
                $owner = preg_replace("/$field_regx/i", "", $owner);
                
                $USarea = substr($phone_number, 0, 3);
                $USprefix = substr($phone_number, 3, 3);

                // Apply overrides
                if (strlen($list_id_override) > 0) {
                    $list_id = $list_id_override;
                }
                if (strlen($phone_code_override) > 0) {
                    $phone_code = $phone_code_override;
                }
                if (strlen($phone_code) < 1) {
                    $phone_code = '1';
                }

                // State conversion
                if (($state_conversion == 'STATELOOKUP') and (strlen($state) > 3)) {
                    $stmt = "SELECT state from vicidial_phone_codes where geographic_description='$state' and country_code='$phone_code' limit 1;";
                    if ($DB > 0) {
                        echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-info mb-1\">DEBUG: state conversion query - $stmt</div>';</script>";
                    }
                    $rslt = mysql_to_mysqli($stmt, $link);
                    $sc_recs = mysqli_num_rows($rslt);
                    if ($sc_recs > 0) {
                        $row = mysqli_fetch_row($rslt);
                        $state_abbr = $row[0];
                        if ((strlen($state_abbr) > 0) and (strlen($state_abbr) < 3)) {
                            if ($DB > 0) {
                                echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-info mb-1\">DEBUG: state conversion found - $state|$state_abbr</div>';</script>";
                            }
                            $state = $state_abbr;
                        }
                    }
                }

                // Phone number strip
                if ((strlen($SSweb_loader_phone_strip) > 0) and ($SSweb_loader_phone_strip != 'DISABLED')) {
                    $phone_number = preg_replace("/^$SSweb_loader_phone_strip/", '', $phone_number);
                }

                // Check for duplicate phone numbers in vicidial_list table for all lists in a campaign
                if (preg_match("/DUPCAMP/i", $dupcheck)) {
                    $dup_lead = 0;
                    $moved_lead = 0;
                    $dup_lists = '';
                    $stmt = "SELECT campaign_id from vicidial_lists where list_id='$list_id';";
                    $rslt = mysql_to_mysqli($stmt, $link);
                    $ci_recs = mysqli_num_rows($rslt);
                    if ($ci_recs > 0) {
                        $row = mysqli_fetch_row($rslt);
                        $dup_camp = $row[0];

                        $stmt = "SELECT list_id from vicidial_lists where campaign_id='$dup_camp';";
                        $rslt = mysql_to_mysqli($stmt, $link);
                        $li_recs = mysqli_num_rows($rslt);
                        if ($li_recs > 0) {
                            $L = 0;
                            while ($li_recs > $L) {
                                $row = mysqli_fetch_row($rslt);
                                $dup_lists .= "'$row[0]',";
                                $L++;
                            }
                            $dup_lists = preg_replace('/,$/i', '', $dup_lists);

                            if ($status_mismatch_action) {
                                if (preg_match('/USING CHECK/', $status_mismatch_action)) {
                                    $stmt = "SELECT list_id, lead_id from vicidial_list where phone_number='$phone_number' and list_id IN($dup_lists) $multidaySQL $mismatch_clause order by entry_date desc $mismatch_limit";
                                } else {
                                    $stmt = "SELECT list_id, lead_id from vicidial_list where phone_number='$phone_number' $mismatch_clause order by entry_date desc $mismatch_limit";
                                }
                                if ($DB > 0) {
                                    echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-info mb-1\">$stmt</div>';</script>";
                                }
                                $rslt = mysql_to_mysqli($stmt, $link);
                                while ($row = mysqli_fetch_row($rslt)) {
                                    $upd_stmt = "update vicidial_list set list_id='$list_id' where lead_id='$row[1]'";
                                    if ($DB > 0) {
                                        echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-info mb-1\">$upd_stmt</div>';</script>";
                                    }
                                    $upd_rslt = mysql_to_mysqli($upd_stmt, $link);
                                    $moved += mysqli_affected_rows($link);
                                    $moved_lead += mysqli_affected_rows($link);
                                    $dup_lead = 1;
                                    $dup_lead_list = $row[0];
                                }
                            }

                            if ($dup_lead < 1) {
                                $stmt = "SELECT list_id from vicidial_list where phone_number='$phone_number' and list_id IN($dup_lists) $multidaySQL $statuses_clause limit 1;";
                                $rslt = mysql_to_mysqli($stmt, $link);
                                $pc_recs = mysqli_num_rows($rslt);
                                if ($pc_recs > 0) {
                                    $dup_lead = 1;
                                    $row = mysqli_fetch_row($rslt);
                                    $dup_lead_list = $row[0];
                                }
                            }

                            if ($dup_lead < 1) {
                                if (preg_match("/$phone_number$US$list_id/i", $phone_list)) {
                                    $dup_lead++;
                                    $dup++;
                                }
                            }
                        }
                    }
                }

                // Check for duplicate phone numbers in vicidial_list table entire database
                if (preg_match("/DUPSYS/i", $dupcheck)) {
                    $dup_lead = 0;
                    $moved_lead = 0;

                    if ($status_mismatch_action) {
                        if (preg_match('/USING CHECK/', $status_mismatch_action)) {
                            $stmt = "SELECT list_id, lead_id from vicidial_list where phone_number='$phone_number' $multidaySQL $mismatch_clause order by entry_date desc $mismatch_limit";
                        } else {
                            $stmt = "SELECT list_id, lead_id from vicidial_list where phone_number='$phone_number' $mismatch_clause order by entry_date desc $mismatch_limit";
                        }
                        if ($DB > 0) {
                            echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-info mb-1\">$stmt</div>';</script>";
                        }
                        $rslt = mysql_to_mysqli($stmt, $link);
                        while ($row = mysqli_fetch_row($rslt)) {
                            $upd_stmt = "update vicidial_list set list_id='$list_id' where lead_id='$row[1]'";
                            if ($DB > 0) {
                                echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-info mb-1\">$upd_stmt</div>';</script>";
                            }
                            $upd_rslt = mysql_to_mysqli($upd_stmt, $link);
                            $moved += mysqli_affected_rows($link);
                            $moved_lead += mysqli_affected_rows($link);
                            $dup_lead = 1;
                            $dup_lead_list = $row[0];
                        }
                    }

                    if ($dup_lead < 1) {
                        $stmt = "SELECT list_id from vicidial_list where phone_number='$phone_number' $multidaySQL $statuses_clause;";
                        $rslt = mysql_to_mysqli($stmt, $link);
                        $pc_recs = mysqli_num_rows($rslt);
                        if ($pc_recs > 0) {
                            $dup_lead = 1;
                            $row = mysqli_fetch_row($rslt);
                            $dup_lead_list = $row[0];
                        }
                    }

                    if ($dup_lead < 1) {
                        if (preg_match("/$phone_number$US$list_id/i", $phone_list)) {
                            $dup_lead++;
                            $dup++;
                        }
                    }
                }

                // Check for duplicate phone numbers in vicidial_list table for one list_id
                if (preg_match("/DUPLIST/i", $dupcheck)) {
                    $dup_lead = 0;
                    $moved_lead = 0;

                    if ($status_mismatch_action) {
                        if (preg_match('/USING CHECK/', $status_mismatch_action)) {
                            $stmt = "SELECT list_id, lead_id from vicidial_list where phone_number='$phone_number' and list_id='$list_id' $multidaySQL $mismatch_clause order by entry_date desc $mismatch_limit";
                        } else {
                            $stmt = "SELECT list_id, lead_id from vicidial_list where phone_number='$phone_number' $mismatch_clause order by entry_date desc $mismatch_limit";
                        }
                        if ($DB > 0) {
                            echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-info mb-1\">$stmt</div>';</script>";
                        }
                        $rslt = mysql_to_mysqli($stmt, $link);
                        while ($row = mysqli_fetch_row($rslt)) {
                            $upd_stmt = "update vicidial_list set list_id='$list_id' where lead_id='$row[1]'";
                            if ($DB > 0) {
                                echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-info mb-1\">$upd_stmt</div>';</script>";
                            }
                            $upd_rslt = mysql_to_mysqli($upd_stmt, $link);
                            $moved += mysqli_affected_rows($link);
                            $moved_lead += mysqli_affected_rows($link);
                            $dup_lead = 1;
                            $dup_lead_list = $row[0];
                        }
                    }

                    if ($dup_lead < 1) {
                        $stmt = "SELECT count(*) from vicidial_list where phone_number='$phone_number' and list_id='$list_id' $multidaySQL $statuses_clause;";
                        $rslt = mysql_to_mysqli($stmt, $link);
                        $pc_recs = mysqli_num_rows($rslt);
                        if ($pc_recs > 0) {
                            $row = mysqli_fetch_row($rslt);
                            $dup_lead = $row[0];
                        }
                    }

                    if ($dup_lead < 1) {
                        if (preg_match("/$phone_number$US$list_id/i", $phone_list)) {
                            $dup_lead++;
                            $dup++;
                        }
                    }
                }

                // Check for duplicate title and alt-phone in vicidial_list table for one list_id
                if (preg_match("/DUPTITLEALTPHONELIST/i", $dupcheck)) {
                    $dup_lead = 0;
                    $moved_lead = 0;

                    if ($status_mismatch_action) {
                        if (preg_match('/USING CHECK/', $status_mismatch_action)) {
                            $stmt = "SELECT list_id, lead_id from vicidial_list where title='$title' and alt_phone='$alt_phone' and list_id='$list_id' $multidaySQL $mismatch_clause order by entry_date desc $mismatch_limit";
                        } else {
                            $stmt = "SELECT list_id, lead_id from vicidial_list where title='$title' and alt_phone='$alt_phone' $mismatch_clause order by entry_date desc $mismatch_limit";
                        }
                        if ($DB > 0) {
                            echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-info mb-1\">$stmt</div>';</script>";
                        }
                        $rslt = mysql_to_mysqli($stmt, $link);
                        while ($row = mysqli_fetch_row($rslt)) {
                            $upd_stmt = "update vicidial_list set list_id='$list_id' where lead_id='$row[1]'";
                            if ($DB > 0) {
                                echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-info mb-1\">$upd_stmt</div>';</script>";
                            }
                            $upd_rslt = mysql_to_mysqli($upd_stmt, $link);
                            $moved += mysqli_affected_rows($link);
                            $moved_lead += mysqli_affected_rows($link);
                            $dup_lead = 1;
                            $dup_lead_list = $row[0];
                        }
                    }

                    if ($dup_lead < 1) {
                        $stmt = "SELECT count(*) from vicidial_list where title='$title' and alt_phone='$alt_phone' and list_id='$list_id' $multidaySQL $statuses_clause;";
                        $rslt = mysql_to_mysqli($stmt, $link);
                        $pc_recs = mysqli_num_rows($rslt);
                        if ($pc_recs > 0) {
                            $row = mysqli_fetch_row($rslt);
                            $dup_lead = $row[0];
                        }
                    }

                    if ($dup_lead < 1) {
                        if (preg_match("/$alt_phone$title$US$list_id/i", $phone_list)) {
                            $dup_lead++;
                            $dup++;
                        }
                    }
                }

                // Check for duplicate phone numbers in vicidial_list table entire database
                if (preg_match("/DUPTITLEALTPHONESYS/i", $dupcheck)) {
                    $dup_lead = 0;
                    $moved_lead = 0;

                    if ($status_mismatch_action) {
                        if (preg_match('/USING CHECK/', $status_mismatch_action)) {
                            $stmt = "SELECT list_id, lead_id from vicidial_list where title='$title' and alt_phone='$alt_phone' $multidaySQL $mismatch_clause order by entry_date desc $mismatch_limit";
                        } else {
                            $stmt = "SELECT list_id, lead_id from vicidial_list where title='$title' and alt_phone='$alt_phone' $mismatch_clause order by entry_date desc $mismatch_limit";
                        }
                        if ($DB > 0) {
                            echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-info mb-1\">$stmt</div>';</script>";
                        }
                        $rslt = mysql_to_mysqli($stmt, $link);
                        while ($row = mysqli_fetch_row($rslt)) {
                            $upd_stmt = "update vicidial_list set list_id='$list_id' where lead_id='$row[1]'";
                            if ($DB > 0) {
                                echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-info mb-1\">$upd_stmt</div>';</script>";
                            }
                            $upd_rslt = mysql_to_mysqli($upd_stmt, $link);
                            $moved += mysqli_affected_rows($link);
                            $moved_lead += mysqli_affected_rows($link);
                            $dup_lead = 1;
                            $dup_lead_list = $row[0];
                        }
                    }

                    if ($dup_lead < 1) {
                        $stmt = "SELECT list_id from vicidial_list where title='$title' and alt_phone='$alt_phone' $multidaySQL $statuses_clause;";
                        $rslt = mysql_to_mysqli($stmt, $link);
                        $pc_recs = mysqli_num_rows($rslt);
                        if ($pc_recs > 0) {
                            $dup_lead = 1;
                            $row = mysqli_fetch_row($rslt);
                            $dup_lead_list = $row[0];
                        }
                    }

                    if ($dup_lead < 1) {
                        if (preg_match("/$alt_phone$title$US$list_id/i", $phone_list)) {
                            $dup_lead++;
                            $dup++;
                        }
                    }
                }

                // Validate phone number
                $valid_number = 1;
                $dnc_matches = 0;
                $invalid_reason = '';
                if ((strlen($phone_number) < 5) || (strlen($phone_number) > 18)) {
                    $valid_number = 0;
                    $invalid_reason = _QXZ("INVALID PHONE NUMBER LENGTH");
                }
                if ((strlen($web_loader_phone_length) > 0) and (strlen($web_loader_phone_length) < 3) and ((strlen($phone_number) > $web_loader_phone_length) or (strlen($phone_number) < $web_loader_phone_length))) {
                    $valid_number = 0;
                    $invalid_reason = _QXZ("INVALID REQUIRED PHONE NUMBER LENGTH");
                }
                if ((preg_match("/PREFIX/", $usacan_check)) and ($valid_number > 0)) {
                    $USprefix = substr($phone_number, 3, 1);
                    if ($DB > 0) {
                        echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-info mb-1\">DEBUG: usacan prefix check - $USprefix|$phone_number</div>';</script>";
                    }
                    if ($USprefix < 2) {
                        $valid_number = 0;
                        $invalid_reason = _QXZ("INVALID PHONE NUMBER PREFIX");
                    }
                }
                if ((preg_match("/AREACODE/", $usacan_check)) and ($valid_number > 0)) {
                    $phone_areacode = substr($phone_number, 0, 3);
                    $stmt = "SELECT count(*) from vicidial_phone_codes where areacode='$phone_areacode' and country_code='1';";
                    if ($DB > 0) {
                        echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-info mb-1\">DEBUG: usacan areacode query - $stmt</div>';</script>";
                    }
                    $rslt = mysql_to_mysqli($stmt, $link);
                    $row = mysqli_fetch_row($rslt);
                    $valid_number = $row[0];
                    if ($valid_number < 1) {
                        $invalid_reason = _QXZ("INVALID PHONE NUMBER AREACODE");
                    }
                }
                if ((preg_match("/NANPA/", $usacan_check)) and ($valid_number > 0)) {
                    $phone_areacode = substr($phone_number, 0, 3);
                    $phone_prefix = substr($phone_number, 3, 3);
                    $stmt = "SELECT count(*) from vicidial_nanpa_prefix_codes where areacode='$phone_areacode' and prefix='$phone_prefix';";
                    if ($DB > 0) {
                        echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-info mb-1\">DEBUG: usacan nanpa query - $stmt</div>';</script>";
                    }
                    $rslt = mysql_to_mysqli($stmt, $link);
                    $row = mysqli_fetch_row($rslt);
                    $valid_number = $row[0];
                    if ($valid_number < 1) {
                        $invalid_reason = _QXZ("INVALID PHONE NUMBER NANPA AREACODE PREFIX");
                    }
                }
                if ($international_dnc_scrub and $valid_number > 0) {
                    $dnc_table_name = "vicidial_dnc_" . $international_dnc_scrub;
                    $dnc_stmt = "select count(*) from $dnc_table_name where phone_number='$phone_number'";
                    if ($DB > 0) {
                        echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-info mb-1\">DEBUG: $international_dnc_scrub DNC query - $dnc_stmt</div>';</script>";
                    }
                    $dnc_rslt = mysql_to_mysqli($dnc_stmt, $link);
                    $dnc_row = mysqli_fetch_row($dnc_rslt);
                    $dnc_matches = $dnc_row[0];
                    if ($dnc_matches > 0) {
                        $invalid_reason = _QXZ("NUMBER FOUND IN $international_dnc_scrub DNC LIST");
                    }
                }

                // Insert valid records
                if (($valid_number > 0) and ($dnc_matches < 1) and ($dup_lead < 1) and ($list_id >= 100)) {
                    if (preg_match("/TITLEALTPHONE/i", $dupcheck)) {
                        $phone_list .= "$alt_phone$title$US$list_id|";
                    } else {
                        $phone_list .= "$phone_number$US$list_id|";
                    }

                    $gmt_offset = lookup_gmt($phone_code, $USarea, $state, $LOCAL_GMT_OFF_STD, $Shour, $Smin, $Ssec, $Smon, $Smday, $Syear, $postalgmt, $postal_code, $owner, $USprefix);

                    if ($multi_insert_counter > 8) {
                        // Insert good record into vicidial_list table
                        $stmtZ = "INSERT INTO vicidial_list (lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner,entry_list_id) values$multistmt('',\"$entry_date\",\"$modify_date\",\"$status\",\"$user\",\"$vendor_lead_code\",\"$source_id\",\"$list_id\",\"$gmt_offset\",\"$called_since_last_reset\",\"$phone_code\",\"$phone_number\",\"$title\",\"$first_name\",\"$middle_initial\",\"$last_name\",\"$address1\",\"$address2\",\"$address3\",\"$city\",\"$state\",\"$province\",\"$postal_code\",\"$country_code\",\"$gender\",\"$date_of_birth\",\"$alt_phone\",\"$email\",\"$security_phrase\",\"$comments\",0,\"2008-01-01 00:00:00\",\"$rank\",\"$owner\",'0');";
                        $rslt = mysql_to_mysqli($stmtZ, $link);
                        if (($webroot_writable > 0) and ($DB > 0)) {
                            fwrite($stmt_file, $stmtZ . "\r\n");
                        }
                        $multistmt = '';
                        $multi_insert_counter = 0;
                    } else {
                        $multistmt .= "('',\"$entry_date\",\"$modify_date\",\"$status\",\"$user\",\"$vendor_lead_code\",\"$source_id\",\"$list_id\",\"$gmt_offset\",\"$called_since_last_reset\",\"$phone_code\",\"$phone_number\",\"$title\",\"$first_name\",\"$middle_initial\",\"$last_name\",\"$address1\",\"$address2\",\"$address3\",\"$city\",\"$state\",\"$province\",\"$postal_code\",\"$country_code\",\"$gender\",\"$date_of_birth\",\"$alt_phone\",\"$email\",\"$security_phrase\",\"$comments\",0,\"2008-01-01 00:00:00\",\"$rank\",\"$owner\",'0'),";
                        $multi_insert_counter++;
                    }
                    $good++;
                } else {
                    if ($bad < 1000000) {
                        if ($list_id < 100) {
                            echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-danger mb-1\"><i class=\"fas fa-exclamation-triangle me-1\"></i>record $total " . _QXZ("BAD- PHONE") . ": $phone_number " . _QXZ("ROW") . ": |$row[0]| " . _QXZ("INVALID LIST ID") . "</div>';</script>";
                        } else {
                            if ($valid_number < 1) {
                                echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-danger mb-1\"><i class=\"fas fa-exclamation-triangle me-1\"></i>record $total " . _QXZ("BAD- PHONE") . ": $phone_number " . _QXZ("ROW") . ": |$row[0]| " . _QXZ("INV") . "($invalid_reason): $phone_number</div>';</script>";
                            } else if ($dnc_matches > 0) {
                                echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-danger mb-1\"><i class=\"fas fa-exclamation-triangle me-1\"></i>record $total " . _QXZ("BAD- PHONE") . ": $phone_number " . _QXZ("ROW") . ": |$row[0]| " . _QXZ("DNC") . "($invalid_reason): $phone_number</div>';</script>";
                            } else {
                                echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-danger mb-1\"><i class=\"fas fa-exclamation-triangle me-1\"></i>record $total " . _QXZ("BAD- PHONE") . ": $phone_number " . _QXZ("ROW") . ": |$row[0]| " . _QXZ("DUP") . ": $dup_lead  $dup_lead_list</div>';</script>";
                            }
                            if ($moved_lead > 0) {
                                echo "<script>document.getElementById('processing-log').innerHTML += '<div class=\"text-primary mb-1\"><i class=\"fas fa-info-circle me-1\"></i> Moved $moved_lead leads</div>';</script>";
                            }
                        }
                    }
                    $bad++;
                }
                $total++;
                
                // Update progress every 100 records
                if ($total % 100 == 0) {
                    $progress_percent = min(100, round(($total / 10000) * 100));
                    echo "<script>
                        document.getElementById('progress-bar').style.width = '$progress_percent%';
                        document.getElementById('progress-bar').setAttribute('aria-valuenow', '$progress_percent');
                        document.getElementById('progress-bar').textContent = '$progress_percent%';
                        document.getElementById('good-count').textContent = '$good';
                        document.getElementById('bad-count').textContent = '$bad';
                        document.getElementById('dup-count').textContent = '$dup';
                        document.getElementById('total-count').textContent = '$total';
                    </script>";
                    usleep(1000);
                    flush();
                }
            }
        }
        
        // Insert any remaining records
        if ($multi_insert_counter != 0) {
            $stmtZ = "INSERT INTO vicidial_list (lead_id,entry_date,modify_date,status,user,vendor_lead_code,source_id,list_id,gmt_offset_now,called_since_last_reset,phone_code,phone_number,title,first_name,middle_initial,last_name,address1,address2,address3,city,state,province,postal_code,country_code,gender,date_of_birth,alt_phone,email,security_phrase,comments,called_count,last_local_call_time,rank,owner,entry_list_id) values" . substr($multistmt, 0, -1) . ";";
            mysql_to_mysqli($stmtZ, $link);
            if (($webroot_writable > 0) and ($DB > 0)) {
                fwrite($stmt_file, $stmtZ . "\r\n");
            }
        }

        // Log the action
        $stmt = "INSERT INTO vicidial_admin_log set event_date='$NOW_TIME', user='$PHP_AUTH_USER', ip_address='$ip', event_section='LISTS', event_type='LOAD', record_id='$list_id_override', event_code='ADMIN LOAD LIST STANDARD', event_sql='', event_notes='File Name: $leadfile_name, GOOD: $good, BAD: $bad, MOVED: $moved, TOTAL: $total, DEBUG: dedupe_statuses:$dedupe_statuses[0]| dedupe_statuses_override:$dedupe_statuses_override| dupcheck:$dupcheck| status mismatch action: $status_mismatch_action| lead_file:$lead_file| list_id_override:$list_id_override| phone_code_override:$phone_code_override| postalgmt:$postalgmt| template_id:$template_id| usacan_check:$usacan_check| dnc_country_scrub:$international_dnc_scrub| state_conversion:$state_conversion| web_loader_phone_length:$web_loader_phone_length| web_loader_phone_strip:$SSweb_loader_phone_strip|';";
        if ($DB) {
            echo "|$stmt|\n";
        }
        $rslt = mysql_to_mysqli($stmt, $link);

        if ($moved > 0) {
            $moved_str = " &nbsp; &nbsp; &nbsp; " . _QXZ("MOVED") . ": $moved ";
        } else {
            $moved_str = "";
        }

        // Display completion message
        echo "<div class='alert alert-success mt-4'>
            <h4 class='alert-heading'><i class='fas fa-check-circle me-2'></i>" . _QXZ("Processing Complete") . "</h4>
            <p class='mb-0'>" . _QXZ("GOOD") . ": $good &nbsp; &nbsp; &nbsp; " . _QXZ("BAD") . ": $bad $moved_str &nbsp; &nbsp; &nbsp; " . _QXZ("TOTAL") . ": $total</p>
        </div>";
    } else {
        echo "<div class='alert alert-danger'>
            <h4 class='alert-heading'><i class='fas fa-exclamation-triangle me-2'></i>" . _QXZ("ERROR") . "</h4>
            <p class='mb-0'>" . _QXZ("The file does not have the required number of fields to process it") . ".</p>
        </div>";
    }
}
##### END process standard file layout #####

##### BEGIN field chooser #####
else if ($file_layout == "custom") {
    // Disable form elements during processing
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            const formElements = ['leadfile', 'submit_file', 'reload_page'];
            formElements.forEach(id => {
                const element = document.getElementById(id);
                if (element) element.disabled = true;
            });
        });
    </script><hr>";
    flush();
    
    // Create modern field chooser interface
    echo "<div class='card mb-4'>
        <div class='card-header bg-primary text-white'>
            <h4 class='mb-0'><i class='fas fa-columns me-2'></i>" . _QXZ("Field Mapping Configuration") . "</h4>
        </div>
        <div class='card-body'>
            <div class='table-responsive'>
                <table class='table table-striped table-hover'>
                    <thead class='table-dark'>
                        <tr>
                            <th width='40%'>" . _QXZ("VICIDIAL Column") . "</th>
                            <th width='60%'>" . _QXZ("File Data") . "</th>
                        </tr>
                    </thead>
                    <tbody>";

    $fields_stmt = "SELECT vendor_lead_code, source_id, list_id, phone_code, phone_number, title, first_name, middle_initial, last_name, address1, address2, address3, city, state, province, postal_code, country_code, gender, date_of_birth, alt_phone, email, security_phrase, comments, rank, owner from vicidial_list limit 1";

    // Handle custom fields
    if ($custom_fields_enabled > 0) {
        $stmt = "SHOW TABLES LIKE \"custom_$list_id_override\";";
        if ($DB > 0) {
            echo "$stmt\n";
        }
        $rslt = mysql_to_mysqli($stmt, $link);
        $tablecount_to_print = mysqli_num_rows($rslt);
        if ($tablecount_to_print > 0) {
            $stmt = "SELECT count(*) from vicidial_lists_fields where list_id='$list_id_override' and field_duplicate!='Y';";
            if ($DB > 0) {
                echo "$stmt\n";
            }
            $rslt = mysql_to_mysqli($stmt, $link);
            $fieldscount_to_print = mysqli_num_rows($rslt);
            if ($fieldscount_to_print > 0) {
                $rowx = mysqli_fetch_row($rslt);
                $custom_records_count = $rowx[0];

                $custom_SQL = '';
                $stmt = "SELECT field_id,field_label,field_name,field_description,field_rank,field_help,field_type,field_options,field_size,field_max,field_default,field_cost,field_required,multi_position,name_position,field_order,field_encrypt from vicidial_lists_fields where list_id='$list_id_override' and field_duplicate!='Y' order by field_rank,field_order,field_label;";
                if ($DB > 0) {
                    echo "$stmt\n";
                }
                $rslt = mysql_to_mysqli($stmt, $link);
                $fields_to_print = mysqli_num_rows($rslt);
                $fields_list = '';
                $o = 0;
                while ($fields_to_print > $o) {
                    $rowx = mysqli_fetch_row($rslt);
                    $A_field_label[$o] = $rowx[1];
                    $A_field_type[$o] = $rowx[6];
                    $A_field_encrypt[$o] = $rowx[16];

                    if ($DB > 0) {
                        echo "$A_field_label[$o]|$A_field_type[$o]\n";
                    }

                    if (($A_field_type[$o] != 'DISPLAY') and ($A_field_type[$o] != 'SCRIPT') and ($A_field_type[$o] != 'SWITCH') and ($A_field_type[$o] != 'BUTTON')) {
                        if (!preg_match("/\|$A_field_label[$o]\|/", $vicidial_list_fields)) {
                            $custom_SQL .= ",$A_field_label[$o]";
                        }
                    }
                    $o++;
                }

                $fields_stmt = "SELECT vendor_lead_code, source_id, list_id, phone_code, phone_number, title, first_name, middle_initial, last_name, address1, address2, address3, city, state, province, postal_code, country_code, gender, date_of_birth, alt_phone, email, security_phrase, comments, rank, owner $custom_SQL from vicidial_list, custom_$list_id_override limit 1";
            }
        }
    }

    if ($DB > 0) {
        echo "$fields_stmt\n";
    }
    $rslt = mysql_to_mysqli("$fields_stmt", $link);

    // Handle file conversion
    $delim_set = 0;
    if (preg_match("/\.csv$|\.xls$|\.xlsx$|\.ods$|\.sxc$/i", $leadfile_name)) {
        $leadfile_name = preg_replace('/[^-\.\_0-9a-zA-Z]/', '_', $leadfile_name);
        copy($LF_path, "/tmp/$leadfile_name");
        $new_filename = preg_replace("/\.csv$|\.xls$|\.xlsx$|\.ods$|\.sxc$/i", '.txt', $leadfile_name);
        $convert_command = "$WeBServeRRooT/$admin_web_directory/sheet2tab.pl /tmp/$leadfile_name /tmp/$new_filename";
        passthru("$convert_command");
        $lead_file = "/tmp/$new_filename";
        if ($DB > 0) {
            echo "|$convert_command|";
        }

        if (preg_match("/\.csv$/i", $leadfile_name)) {
            $delim_name = "CSV: " . _QXZ("Comma Separated Values");
        }
        if (preg_match("/\.xls$/i", $leadfile_name)) {
            $delim_name = "XLS: MS Excel 2000-XP";
        }
        if (preg_match("/\.xlsx$/i", $leadfile_name)) {
            $delim_name = "XLSX: MS Excel 2007+";
        }
        if (preg_match("/\.ods$/i", $leadfile_name)) {
            $delim_name = "ODS: OpenOffice.org OpenDocument " . _QXZ("Spreadsheet");
        }
        if (preg_match("/\.sxc$/i", $leadfile_name)) {
            $delim_name = "SXC: OpenOffice.org " . _QXZ("First Spreadsheet");
        }
        $delim_set = 1;
    } else {
        copy($LF_path, "/tmp/vicidial_temp_file.txt");
        $lead_file = "/tmp/vicidial_temp_file.txt";
    }
    
    $file = fopen("$lead_file", "r");
    if ($webroot_writable > 0) {
        $stmt_file = fopen("$WeBServeRRooT/$admin_web_directory/listloader_stmts.txt", "w");
    }

    $buffer = fgets($file, 4096);
    $tab_count = substr_count($buffer, "\t");
    $pipe_count = substr_count($buffer, "|");

    if ($delim_set < 1) {
        if ($tab_count > $pipe_count) {
            $delim_name = _QXZ("tab-delimited");
        } else {
            $delim_name = _QXZ("pipe-delimited");
        }
    }
    
    if ($tab_count > $pipe_count) {
        $delimiter = "\t";
    } else {
        $delimiter = "|";
    }

    $field_check = explode($delimiter, $buffer);

    if (count($dedupe_statuses) > 0) {
        $status_dedupe_str = "";
        for ($ds = 0; $ds < count($dedupe_statuses); $ds++) {
            $dedupe_statuses[$ds] = preg_replace('/[^-_0-9\p{L}]/u', '', $dedupe_statuses[$ds]);
            $status_dedupe_str .= "$dedupe_statuses[$ds],";
            if (preg_match('/\-\-ALL\-\-/', $dedupe_statuses[$ds])) {
                $status_mismatch_action = ""; // Important - if ALL statuses are selected there's no need for this feature
                $status_dedupe_str = "";
                break;
            }
        }
        $status_dedupe_str = preg_replace('/\,$/', "", $status_dedupe_str);
    }

    if ($status_mismatch_action) {
        $mismatch_clause = " and status not in ('" . implode("','", $dedupe_statuses) . "') ";
        if (preg_match('/RECENT/', $status_mismatch_action)) {
            $mismatch_limit = " limit 1 ";
        } else {
            $mismatch_limit = "";
        }
    }

    flush();
    $file = fopen("$lead_file", "r");
    
    // Display configuration settings
    $config_display = "<div class='card mb-3'><div class='card-body'><h5 class='card-title'><i class='fas fa-cog me-2'></i>" . _QXZ("Configuration Settings") . "</h5><ul class='list-group list-group-flush'>";
    
    if (strlen($list_id_override) > 0) {
        $config_display .= "<li class='list-group-item'><strong>" . _QXZ("List ID Override") . ":</strong> $list_id_override</li>";
    }
    
    if (strlen($phone_code_override) > 0) {
        $config_display .= "<li class='list-group-item'><strong>" . _QXZ("Phone Code Override") . ":</strong> $phone_code_override</li>";
    }
    
    if (strlen($dupcheck) > 0) {
        $config_display .= "<li class='list-group-item'><strong>" . _QXZ("Lead Duplicate Check") . ":</strong> $dupcheck</li>";
    }
    
    if (strlen($international_dnc_scrub) > 0) {
        $config_display .= "<li class='list-group-item'><strong>" . _QXZ("International DNC Scrub") . ":</strong> $international_dnc_scrub</li>";
    }
    
    if (strlen($status_dedupe_str) > 0) {
        $config_display .= "<li class='list-group-item'><strong>" . _QXZ("Omitting Duplicates Against Following Statuses Only") . ":</strong> $status_dedupe_str</li>";
    }
    
    if (strlen($status_mismatch_action) > 0) {
        $config_display .= "<li class='list-group-item'><strong>" . _QXZ("Action For Duplicate Not On Status List") . ":</strong> $status_mismatch_action</li>";
    }
    
    if (strlen($state_conversion) > 9) {
        $config_display .= "<li class='list-group-item'><strong>" . _QXZ("Conversion Of State Names To Abbreviations Enabled") . ":</strong> $state_conversion</li>";
    }
    
    if ((strlen($web_loader_phone_length) > 0) and (strlen($web_loader_phone_length) < 3)) {
        $config_display .= "<li class='list-group-item'><strong>" . _QXZ("Required Phone Number Length") . ":</strong> $web_loader_phone_length</li>";
    }
    
    if ((strlen($SSweb_loader_phone_strip) > 0) and ($SSweb_loader_phone_strip != 'DISABLED')) {
        $config_display .= "<li class='list-group-item'><strong>" . _QXZ("Phone Number Prefix Strip System Setting Enabled") . ":</strong> $SSweb_loader_phone_strip</li>";
    }
    
    $config_display .= "</ul></div></div>";
    echo $config_display;

    $buffer = rtrim(fgets($file, 4096));
    $buffer = stripslashes($buffer);
    $row = explode($delimiter, preg_replace('/[\"]/i', '', $buffer));

    while ($fieldinfo = mysqli_fetch_field($rslt)) {
        $rslt_field_name = $fieldinfo->name;
        if (($rslt_field_name == "list_id" and $list_id_override != "") or ($rslt_field_name == "phone_code" and $phone_code_override != "")) {
            echo "<!-- skipping " . $rslt_field_name . " -->\n";
        } else {
            echo "<tr>\n";
            echo "<td><strong>" . strtoupper(preg_replace('/_/i', ' ', $rslt_field_name)) . ":</strong></td>\n";
            echo "<td><select name='" . $rslt_field_name . "_field' class='form-select'>\n";
            echo "<option value='-1'>(" . _QXZ("none") . ")</option>\n";

            for ($j = 0; $j < count($row); $j++) {
                preg_replace('/\"/i', '', $row[$j]);
                echo "<option value='$j'>\"" . htmlspecialchars($row[$j]) . "\"</option>\n";
            }

            echo "</select></td>\n";
            echo "</tr>\n";
        }
    }
    
    echo "</tbody>
        </table>
    </div>
    
    <div class='row mt-4'>
        <div class='col-12'>
            <input type='hidden' name='international_dnc_scrub' value=\"$international_dnc_scrub\">
            <input type='hidden' name='dedupe_statuses_override' value=\"$status_dedupe_str\">
            <input type='hidden' name='status_mismatch_action' value=\"$status_mismatch_action\">
            <input type='hidden' name='dupcheck' value=\"$dupcheck\">
            <input type='hidden' name='usacan_check' value=\"$usacan_check\">
            <input type='hidden' name='state_conversion' value=\"$state_conversion\">
            <input type='hidden' name='web_loader_phone_length' value=\"$web_loader_phone_length\">
            <input type='hidden' name='postalgmt' value=\"$postalgmt\">
            <input type='hidden' name='lead_file' value=\"$lead_file\">
            <input type='hidden' name='list_id_override' value=\"$list_id_override\">
            <input type='hidden' name='phone_code_override' value=\"$phone_code_override\">
            <input type='hidden' name='DB' value=\"$DB\">
            <div class='d-flex justify-content-between'>
                <button type='submit' name='OK_to_process' class='btn btn-primary'><i class='fas fa-check-circle me-2'></i>" . _QXZ("OK TO PROCESS") . "</button>
                <button type='button' onClick=\"javascript:document.location='admin_listloader_fourth_gen.php'\" class='btn btn-secondary'><i class='fas fa-redo me-2'></i>" . _QXZ("START OVER") . "</button>
            </div>
        </div>
    </div>
</div>
</div>";

    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            const formElements = ['leadfile', 'submit_file', 'reload_page'];
            formElements.forEach(id => {
                const element = document.getElementById(id);
                if (element) element.disabled = false;
            });
        });
    </script>";
}
##### END field chooser #####
}
?>

</form>
</body>
</html>