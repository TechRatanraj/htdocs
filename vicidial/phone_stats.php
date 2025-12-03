<?php
# phone_stats.php
# 
# Copyright (C) 2022  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
# 

 $startMS = microtime();

require("dbconnect_mysqli.php");
require("functions.php");

/**
 * PhoneStatsReport class to handle phone statistics reporting
 */
class PhoneStatsReport {
    private $db;
    private $link;
    private $PHP_AUTH_USER;
    private $PHP_AUTH_PW;
    private $PHP_SELF;
    private $extension;
    private $server_ip;
    private $begin_date;
    private $end_date;
    private $full_name;
    private $user;
    private $DB;
    private $LOGuser_group;
    private $LOGfullname;
    private $LOGallowed_reports;
    private $LOGadmin_viewable_groups;
    private $LOGadmin_viewable_call_times;
    private $SSbutton_color;
    private $SSallow_web_debug;
    private $non_latin;
    private $VUselected_language;
    private $report_log_id;
    
    /**
     * Constructor
     */
    public function __construct() {
        global $link;
        $this->link = $link;
        $this->PHP_AUTH_USER = $_SERVER['PHP_AUTH_USER'];
        $this->PHP_AUTH_PW = $_SERVER['PHP_AUTH_PW'];
        $this->PHP_SELF = $_SERVER['PHP_SELF'];
        $this->PHP_SELF = preg_replace('/\.php.*/i', '.php', $this->PHP_SELF);
        
        $this->initializeParameters();
        $this->validateAuthentication();
        $this->loadSystemSettings();
        $this->sanitizeInputs();
        $this->logPageVisit();
        $this->loadUserDetails();
        $this->validatePermissions();
    }
    
    /**
     * Initialize parameters from GET/POST
     */
    private function initializeParameters() {
        $params = ['begin_date', 'end_date', 'extension', 'server_ip', 'user', 'full_name', 'submit', 'SUBMIT', 'DB'];
        
        foreach ($params as $param) {
            if (isset($_GET[$param])) {
                $this->$param = $_GET[$param];
            } elseif (isset($_POST[$param])) {
                $this->$param = $_POST[$param];
            }
        }
        
        $this->DB = preg_replace('/[^0-9]/', '', $this->DB);
        
        if (!isset($this->begin_date)) {
            $this->begin_date = date("Y-m-d");
        }
        if (!isset($this->end_date)) {
            $this->end_date = date("Y-m-d");
        }
    }
    
    /**
     * Validate user authentication
     */
    private function validateAuthentication() {
        $auth_message = user_authorization($this->PHP_AUTH_USER, $this->PHP_AUTH_PW, 'REPORTS', 1, 0);
        
        if ($auth_message != 'GOOD') {
            if ($auth_message == 'LOCK') {
                $VDdisplayMESSAGE = _QXZ("Too many login attempts, try again in 15 minutes");
            } elseif ($auth_message == 'IPBLOCK') {
                $VDdisplayMESSAGE = _QXZ("Your IP Address is not allowed") . ": " . getenv("REMOTE_ADDR");
            } else {
                $VDdisplayMESSAGE = _QXZ("Login incorrect, please try again");
            }
            
            Header("Content-type: text/html; charset=utf-8");
            echo "$VDdisplayMESSAGE: |$this->PHP_AUTH_USER|$auth_message|\n";
            exit;
        }
        
        $this->checkReportPermissions();
    }
    
    /**
     * Check if user has permission to view reports
     */
    private function checkReportPermissions() {
        $stmt = "SELECT count(*) from vicidial_users where user='$this->PHP_AUTH_USER' and user_level > 7 and view_reports='1';";
        $rslt = mysql_to_mysqli($stmt, $this->link);
        $row = mysqli_fetch_row($rslt);
        $admin_auth = $row[0];
        
        $stmt = "SELECT count(*) from vicidial_users where user='$this->PHP_AUTH_USER' and user_level > 6 and view_reports='1';";
        $rslt = mysql_to_mysqli($stmt, $this->link);
        $row = mysqli_fetch_row($rslt);
        $reports_auth = $row[0];
        
        if ($reports_auth < 1) {
            $VDdisplayMESSAGE = _QXZ("You are not allowed to view reports");
            Header("Content-type: text/html; charset=utf-8");
            echo "$VDdisplayMESSAGE: |$this->PHP_AUTH_USER|$auth_message|\n";
            exit;
        }
        
        if (($reports_auth > 0) and ($admin_auth < 1)) {
            $_GET['ADD'] = 999999;
            $_SESSION['reports_only_user'] = 1;
        }
    }
    
    /**
     * Load system settings
     */
    private function loadSystemSettings() {
        $stmt = "SELECT use_non_latin,webroot_writable,outbound_autodial_active,user_territories_active,enable_languages,language_method,slave_db_server,reports_use_slave_db,allow_web_debug FROM system_settings;";
        $rslt = mysql_to_mysqli($stmt, $this->link);
        $qm_conf_ct = mysqli_num_rows($rslt);
        
        if ($qm_conf_ct > 0) {
            $row = mysqli_fetch_row($rslt);
            $this->non_latin = $row[0];
            $this->SSallow_web_debug = $row[8];
        }
        
        if ($this->SSallow_web_debug < 1) {
            $this->DB = 0;
        }
    }
    
    /**
     * Sanitize input parameters
     */
    private function sanitizeInputs() {
        $this->extension = preg_replace("/\<|\>|\'|\"|\\\\|;/", '', $this->extension);
        $this->server_ip = preg_replace("/\<|\>|\'|\"|\\\\|;/", '', $this->server_ip);
        $this->begin_date = preg_replace("/\<|\>|\'|\"|\\\\|;/", "", $this->begin_date);
        $this->end_date = preg_replace("/\<|\>|\'|\"|\\\\|;/", "", $this->end_date);
        $this->full_name = preg_replace("/\<|\>|\'|\"|\\\\|;/", "", $this->full_name);
        $this->submit = preg_replace("/\<|\>|\'|\"|\\\\|;/", "", $this->submit);
        $this->SUBMIT = preg_replace("/\<|\>|\'|\"|\\\\|;/", "", $this->SUBMIT);
        
        if ($this->non_latin < 1) {
            $this->PHP_AUTH_USER = preg_replace('/[^-_0-9a-zA-Z]/', '', $this->PHP_AUTH_USER);
            $this->PHP_AUTH_PW = preg_replace('/[^-_0-9a-zA-Z]/', '', $this->PHP_AUTH_PW);
            $this->user = preg_replace('/[^-_0-9a-zA-Z]/', '', $this->user);
        } else {
            $this->PHP_AUTH_USER = preg_replace('/[^-_0-9\p{L}]/u', '', $this->PHP_AUTH_USER);
            $this->PHP_AUTH_PW = preg_replace('/[^-_0-9\p{L}]/u', '', $this->PHP_AUTH_PW);
            $this->user = preg_replace('/[^-_0-9\p{L}]/u', '', $this->user);
        }
    }
    
    /**
     * Log page visit to database
     */
    private function logPageVisit() {
        $LOGip = getenv("REMOTE_ADDR");
        $LOGbrowser = getenv("HTTP_USER_AGENT");
        $LOGscript_name = getenv("SCRIPT_NAME");
        $LOGserver_name = getenv("SERVER_NAME");
        $LOGserver_port = getenv("SERVER_PORT");
        $LOGrequest_uri = getenv("REQUEST_URI");
        $LOGhttp_referer = getenv("HTTP_REFERER");
        
        $LOGbrowser = preg_replace("/\'|\"|\\\\/", "", $LOGbrowser);
        $LOGrequest_uri = preg_replace("/\'|\"|\\\\/", "", $LOGrequest_uri);
        $LOGhttp_referer = preg_replace("/\'|\"|\\\\/", "", $LOGhttp_referer);
        
        if (preg_match("/443/i", $LOGserver_port)) {
            $HTTPprotocol = 'https://';
        } else {
            $HTTPprotocol = 'http://';
        }
        
        if (($LOGserver_port == '80') or ($LOGserver_port == '443')) {
            $LOGserver_port = '';
        } else {
            $LOGserver_port = ":$LOGserver_port";
        }
        
        $LOGfull_url = "$HTTPprotocol$LOGserver_name$LOGserver_port$LOGrequest_uri";
        
        $LOGhostname = php_uname('n');
        if (strlen($LOGhostname) < 1) {
            $LOGhostname = 'X';
        }
        if (strlen($LOGserver_name) < 1) {
            $LOGserver_name = 'X';
        }
        
        $stmt = "SELECT webserver_id FROM vicidial_webservers where webserver='$LOGserver_name' and hostname='$LOGhostname' LIMIT 1;";
        $rslt = mysql_to_mysqli($stmt, $this->link);
        $webserver_id_ct = mysqli_num_rows($rslt);
        
        if ($webserver_id_ct > 0) {
            $row = mysqli_fetch_row($rslt);
            $webserver_id = $row[0];
        } else {
            $stmt = "INSERT INTO vicidial_webservers (webserver,hostname) values('$LOGserver_name','$LOGhostname');";
            $rslt = mysql_to_mysqli($stmt, $this->link);
            $webserver_id = mysqli_insert_id($this->link);
        }
        
        $report_name = 'Phone Stats';
        $stmt = "INSERT INTO vicidial_report_log set event_date=NOW(), user='$this->PHP_AUTH_USER', ip_address='$LOGip', report_name='$report_name', browser='$LOGbrowser', referer='$LOGhttp_referer', notes='$LOGserver_name:$LOGserver_port $LOGscript_name |$this->end_date, $shift, $file_download, $report_display_type|', url='$LOGfull_url', webserver='$webserver_id';";
        $rslt = mysql_to_mysqli($stmt, $this->link);
        $this->report_log_id = mysqli_insert_id($this->link);
    }
    
    /**
     * Load user details from database
     */
    private function loadUserDetails() {
        $stmt = "SELECT selected_language from vicidial_users where user='$this->PHP_AUTH_USER';";
        $rslt = mysql_to_mysqli($stmt, $this->link);
        $sl_ct = mysqli_num_rows($rslt);
        
        if ($sl_ct > 0) {
            $row = mysqli_fetch_row($rslt);
            $this->VUselected_language = $row[0];
        }
        
        $stmt = "SELECT user_group,full_name from vicidial_users where user='$this->PHP_AUTH_USER';";
        $rslt = mysql_to_mysqli($stmt, $this->link);
        $row = mysqli_fetch_row($rslt);
        $this->LOGuser_group = $row[0];
        $this->LOGfullname = $row[1];
        
        $stmt = "SELECT allowed_campaigns,allowed_reports,admin_viewable_groups,admin_viewable_call_times from vicidial_user_groups where user_group='$this->LOGuser_group';";
        $rslt = mysql_to_mysqli($stmt, $this->link);
        $row = mysqli_fetch_row($rslt);
        $this->LOGallowed_campaigns = $row[0];
        $this->LOGallowed_reports = $row[1];
        $this->LOGadmin_viewable_groups = $row[2];
        $this->LOGadmin_viewable_call_times = $row[3];
    }
    
    /**
     * Validate user permissions
     */
    private function validatePermissions() {
        if ((!preg_match("/Phone Stats/", $this->LOGallowed_reports)) and (!preg_match("/ALL REPORTS/", $this->LOGallowed_reports))) {
            Header("WWW-Authenticate: Basic realm=\"CONTACT-CENTER-ADMIN\"");
            Header("HTTP/1.0 401 Unauthorized");
            echo "You are not allowed to view this report: |$this->PHP_AUTH_USER|Phone Stats|\n";
            exit;
        }
    }
    
    /**
     * Get phone details
     */
    private function getPhoneDetails() {
        $stmt = "SELECT fullname from phones where server_ip='$this->server_ip' and extension='$this->extension';";
        $rsltx = mysql_to_mysqli($stmt, $this->link);
        $rowx = mysqli_fetch_row($rsltx);
        return $rowx[0];
    }
    
    /**
     * Get call time statistics
     */
    private function getCallTimeStats() {
        $stmt = "SELECT count(*),channel_group, sum(length_in_sec) from call_log where extension='" . mysqli_real_escape_string($this->link, $this->extension) . "' and server_ip='" . mysqli_real_escape_string($this->link, $this->server_ip) . "' and start_time >= '" . mysqli_real_escape_string($this->link, $this->begin_date) . " 0:00:01' and start_time <= '" . mysqli_real_escape_string($this->link, $this->end_date) . " 23:59:59' group by channel_group order by channel_group";
        $rslt = mysql_to_mysqli($stmt, $this->link);
        return $rslt;
    }
    
    /**
     * Get recent calls
     */
    private function getRecentCalls() {
        $stmt = "SELECT number_dialed,channel_group,start_time,length_in_min from call_log where extension='" . mysqli_real_escape_string($this->link, $this->extension) . "' and server_ip='" . mysqli_real_escape_string($this->link, $this->server_ip) . "' and start_time >= '" . mysqli_real_escape_string($this->link, $this->begin_date) . " 0:00:01' and start_time <= '" . mysqli_real_escape_string($this->link, $this->end_date) . " 23:59:59' LIMIT 1000";
        $rslt = mysql_to_mysqli($stmt, $this->link);
        return $rslt;
    }
    
    /**
     * Calculate total call time
     */
    private function getTotalCallTime() {
        $stmt = "SELECT sum(length_in_sec) from call_log where extension='" . mysqli_real_escape_string($this->link, $this->extension) . "' and server_ip='" . mysqli_real_escape_string($this->link, $this->server_ip) . "' and start_time >= '" . mysqli_real_escape_string($this->link, $this->begin_date) . " 0:00:01' and start_time <= '" . mysqli_real_escape_string($this->link, $this->end_date) . " 23:59:59'";
        $rslt = mysql_to_mysqli($stmt, $this->link);
        $row = mysqli_fetch_row($rslt);
        return $row[0];
    }
    
    /**
     * Render the page header
     */
    private function renderHeader() {
        $admin_page = './admin.php';
        require("screen_colors.php");
        
        echo "<!DOCTYPE html>\n";
        echo "<html lang=\"en\">\n";
        echo "<head>\n";
        echo "<meta charset=\"UTF-8\">\n";
        echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
        echo "<title>" . _QXZ("ADMIN: Phone Stats") . "</title>\n";
        echo "<link href=\"https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&display=swap\" rel=\"stylesheet\">\n";
        echo "<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css\">\n";
        echo "<style>\n";
        echo ":root {\n";
        echo "    --primary-color: #3498db;\n";
        echo "    --secondary-color: #2980b9;\n";
        echo "    --success-color: #2ecc71;\n";
        echo "    --warning-color: #f39c12;\n";
        echo "    --danger-color: #e74c3c;\n";
        echo "    --light-color: #ecf0f1;\n";
        echo "    --dark-color: #2c3e50;\n";
        echo "    --border-radius: 8px;\n";
        echo "    --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);\n";
        echo "    --transition: all 0.3s ease;\n";
        echo "}\n";
        echo "* {\n";
        echo "    margin: 0;\n";
        echo "    padding: 0;\n";
        echo "    box-sizing: border-box;\n";
        echo "}\n";
        echo "body {\n";
        echo "    font-family: 'Open Sans', sans-serif;\n";
        echo "    background-color: #f5f7fa;\n";
        echo "    color: #333;\n";
        echo "    line-height: 1.6;\n";
        echo "}\n";
        echo ".container {\n";
        echo "    max-width: 1200px;\n";
        echo "    margin: 0 auto;\n";
        echo "    padding: 0 1rem;\n";
        echo "}\n";
        echo ".card {\n";
        echo "    background-color: white;\n";
        echo "    border-radius: var(--border-radius);\n";
        echo "    box-shadow: var(--box-shadow);\n";
        echo "    margin-bottom: 2rem;\n";
        echo "    overflow: hidden;\n";
        echo "}\n";
        echo ".card-header {\n";
        echo "    background-color: var(--dark-color);\n";
        echo "    color: white;\n";
        echo "    padding: 1rem 1.5rem;\n";
        echo "    display: flex;\n";
        echo "    align-items: center;\n";
        echo "    justify-content: space-between;\n";
        echo "}\n";
        echo ".card-header h2 {\n";
        echo "    font-size: 1.2rem;\n";
        echo "    font-weight: 600;\n";
        echo "    margin: 0;\n";
        echo "}\n";
        echo ".card-body {\n";
        echo "    padding: 1.5rem;\n";
        echo "}\n";
        echo ".nav {\n";
        echo "    background-color: #f8f9fa;\n";
        echo "    padding: 1rem 1.5rem;\n";
        echo "    display: flex;\n";
        echo "    flex-wrap: wrap;\n";
        echo "    gap: 1rem;\n";
        echo "}\n";
        echo ".nav a {\n";
        echo "    color: var(--dark-color);\n";
        echo "    text-decoration: none;\n";
        echo "    font-weight: 500;\n";
        echo "    padding: 0.5rem 0;\n";
        echo "    border-bottom: 2px solid transparent;\n";
        echo "    transition: var(--transition);\n";
        echo "}\n";
        echo ".nav a:hover {\n";
        echo "    color: var(--primary-color);\n";
        echo "    border-bottom-color: var(--primary-color);\n";
        echo "}\n";
        echo ".filter-form {\n";
        echo "    display: flex;\n";
        echo "    flex-wrap: wrap;\n";
        echo "    gap: 1rem;\n";
        echo "    align-items: center;\n";
        echo "    margin-bottom: 1.5rem;\n";
        echo "}\n";
        echo ".form-group {\n";
        echo "    display: flex;\n";
        echo "    flex-direction: column;\n";
        echo "    gap: 0.5rem;\n";
        echo "}\n";
        echo ".form-group label {\n";
        echo "    font-weight: 500;\n";
        echo "    font-size: 0.9rem;\n";
        echo "    color: var(--dark-color);\n";
        echo "}\n";
        echo ".form-control {\n";
        echo "    padding: 0.75rem;\n";
        echo "    border: 1px solid #ddd;\n";
        echo "    border-radius: var(--border-radius);\n";
        echo "    font-size: 0.9rem;\n";
        echo "    transition: var(--transition);\n";
        echo "}\n";
        echo ".form-control:focus {\n";
        echo "    border-color: var(--primary-color);\n";
        echo "    outline: none;\n";
        echo "    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);\n";
        echo "}\n";
        echo ".btn {\n";
        echo "    display: inline-block;\n";
        echo "    padding: 0.75rem 1.5rem;\n";
        echo "    background-color: var(--primary-color);\n";
        echo "    color: white;\n";
        echo "    border: none;\n";
        echo "    border-radius: var(--border-radius);\n";
        echo "    font-size: 0.9rem;\n";
        echo "    font-weight: 500;\n";
        echo "    cursor: pointer;\n";
        echo "    text-decoration: none;\n";
        echo "    transition: var(--transition);\n";
        echo "}\n";
        echo ".btn:hover {\n";
        echo "    background-color: var(--secondary-color);\n";
        echo "}\n";
        echo ".stats-table {\n";
        echo "    width: 100%;\n";
        echo "    border-collapse: collapse;\n";
        echo "    margin-top: 1rem;\n";
        echo "}\n";
        echo ".stats-table th {\n";
        echo "    background-color: var(--dark-color);\n";
        echo "    color: white;\n";
        echo "    padding: 0.75rem;\n";
        echo "    text-align: left;\n";
        echo "    font-weight: 500;\n";
        echo "}\n";
        echo ".stats-table td {\n";
        echo "    padding: 0.75rem;\n";
        echo "    border-bottom: 1px solid #eee;\n";
        echo "}\n";
        echo ".stats-table tr:nth-child(even) {\n";
        echo "    background-color: #f8f9fa;\n";
        echo "}\n";
        echo ".stats-table tr:hover {\n";
        echo "    background-color: #e9ecef;\n";
        echo "}\n";
        echo ".user-info {\n";
        echo "    display: flex;\n";
        echo "    align-items: center;\n";
        echo "    gap: 0.5rem;\n";
        echo "    margin-bottom: 1rem;\n";
        echo "}\n";
        echo ".user-avatar {\n";
        echo "    width: 40px;\n";
        echo "    height: 40px;\n";
        echo "    border-radius: 50%;\n";
        echo "    background-color: var(--primary-color);\n";
        echo "    color: white;\n";
        echo "    display: flex;\n";
        echo "    align-items: center;\n";
        echo "    justify-content: center;\n";
        echo "    font-weight: 600;\n";
        echo "    font-size: 1.2rem;\n";
        echo "}\n";
        echo ".user-details {\n";
        echo "    display: flex;\n";
        echo "    flex-direction: column;\n";
        echo "    gap: 0.25rem;\n";
        echo "}\n";
        echo ".user-name {\n";
        echo "    font-weight: 600;\n";
        echo "    font-size: 1.1rem;\n";
        echo "    color: var(--dark-color);\n";
        echo "}\n";
        echo ".user-extension {\n";
        echo "    font-size: 0.9rem;\n";
        echo "    color: #6c757d;\n";
        echo "}\n";
        echo ".stats-section {\n";
        echo "    margin-top: 2rem;\n";
        echo "}\n";
        echo ".stats-section h3 {\n";
        echo "    font-size: 1.2rem;\n";
        echo "    font-weight: 600;\n";
        echo "    margin-bottom: 1rem;\n";
        echo "    color: var(--dark-color);\n";
        echo "    display: flex;\n";
        echo "    align-items: center;\n";
        echo "    gap: 0.5rem;\n";
        echo "}\n";
        echo ".stats-section h3 i {\n";
        echo "    color: var(--primary-color);\n";
        echo "}\n";
        echo ".stats-summary {\n";
        echo "    display: grid;\n";
        echo "    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));\n";
        echo "    gap: 1rem;\n";
        echo "    margin-bottom: 2rem;\n";
        echo "}\n";
        echo ".stat-card {\n";
        echo "    background-color: white;\n";
        echo "    border-radius: var(--border-radius);\n";
        echo "    box-shadow: var(--box-shadow);\n";
        echo "    padding: 1.5rem;\n";
        echo "    text-align: center;\n";
        echo "    transition: var(--transition);\n";
        echo "}\n";
        echo ".stat-card:hover {\n";
        echo "    transform: translateY(-5px);\n";
        echo "    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);\n";
        echo "}\n";
        echo ".stat-value {\n";
        echo "    font-size: 2rem;\n";
        echo "    font-weight: 700;\n";
        echo "    color: var(--primary-color);\n";
        echo "    margin-bottom: 0.5rem;\n";
        echo "}\n";
        echo ".stat-label {\n";
        echo "    font-size: 0.9rem;\n";
        echo "    color: #6c757d;\n";
        echo "    text-transform: uppercase;\n";
        echo "    letter-spacing: 1px;\n";
        echo "}\n";
        echo ".runtime {\n";
        echo "    text-align: center;\n";
        echo "    font-size: 0.8rem;\n";
        echo "    color: #6c757d;\n";
        echo "    margin-top: 2rem;\n";
        echo "}\n";
        echo "@media (max-width: 768px) {\n";
        echo "    .filter-form {\n";
        echo "        flex-direction: column;\n";
        echo "        align-items: stretch;\n";
        echo "    }\n";
        echo "    .stats-summary {\n";
        echo "        grid-template-columns: 1fr;\n";
        echo "    }\n";
        echo "}\n";
        echo "</style>\n";
        echo "</head>\n";
        echo "<body>\n";
        echo "<div class=\"container\">\n";
    }
    
    /**
     * Render the navigation menu
     */
    private function renderNavigation() {
        $admin_page = './admin.php';
        
        echo "<div class=\"card\">\n";
        echo "<div class=\"card-header\">\n";
        echo "<h2>" . _QXZ("ADMIN: Administration") . "</h2>\n";
        echo "<div>" . date("l F j, Y G:i:s A") . "</div>\n";
        echo "</div>\n";
        echo "<div class=\"nav\">\n";
        echo "<a href=\"$admin_page?ADD=10000000000\">" . _QXZ("LIST ALL PHONES") . "</a>\n";
        echo "<a href=\"$admin_page?ADD=11111111111\">" . _QXZ("ADD A NEW PHONE") . "</a>\n";
        echo "<a href=\"$admin_page?ADD=551\">" . _QXZ("SEARCH FOR A PHONE") . "</a>\n";
        echo "<a href=\"$admin_page?ADD=111111111111\">" . _QXZ("ADD A SERVER") . "</a>\n";
        echo "<a href=\"$admin_page?ADD=100000000000\">" . _QXZ("LIST ALL SERVERS") . "</a>\n";
        echo "<a href=\"$admin_page?ADD=1000000000000\">" . _QXZ("SHOW ALL CONFERENCES") . "</a>\n";
        echo "<a href=\"$admin_page?ADD=1111111111111\">" . _QXZ("ADD A NEW CONFERENCE") . "</a>\n";
        echo "</div>\n";
        echo "</div>\n";
    }
    
    /**
     * Render the filter form
     */
    private function renderFilterForm() {
        $fullname = $this->getPhoneDetails();
        
        echo "<div class=\"card\">\n";
        echo "<div class=\"card-header\">\n";
        echo "<h2>" . _QXZ("Phone Statistics") . "</h2>\n";
        echo "</div>\n";
        echo "<div class=\"card-body\">\n";
        echo "<form action=\"$this->PHP_SELF\" method=\"POST\" class=\"filter-form\">\n";
        echo "<input type=\"hidden\" name=\"extension\" value=\"$this->extension\">\n";
        echo "<input type=\"hidden\" name=\"server_ip\" value=\"$this->server_ip\">\n";
        
        echo "<div class=\"form-group\">\n";
        echo "<label for=\"begin_date\">" . _QXZ("Start Date") . "</label>\n";
        echo "<input type=\"date\" id=\"begin_date\" name=\"begin_date\" value=\"$this->begin_date\" class=\"form-control\">\n";
        echo "</div>\n";
        
        echo "<div class=\"form-group\">\n";
        echo "<label for=\"end_date\">" . _QXZ("End Date") . "</label>\n";
        echo "<input type=\"date\" id=\"end_date\" name=\"end_date\" value=\"$this->end_date\" class=\"form-control\">\n";
        echo "</div>\n";
        
        echo "<button type=\"submit\" name=\"submit\" class=\"btn\">" . _QXZ("Apply Filter") . "</button>\n";
        echo "</form>\n";
        
        echo "<div class=\"user-info\">\n";
        echo "<div class=\"user-avatar\">" . substr($fullname, 0, 1) . "</div>\n";
        echo "<div class=\"user-details\">\n";
        echo "<div class=\"user-name\">$fullname</div>\n";
        echo "<div class=\"user-extension\">Extension: $this->extension | Server: $this->server_ip</div>\n";
        echo "</div>\n";
        echo "</div>\n";
        echo "</div>\n";
        echo "</div>\n";
    }
    
    /**
     * Render the call time statistics
     */
    private function renderCallTimeStats() {
        $rslt = $this->getCallTimeStats();
        $statuses_to_print = mysqli_num_rows($rslt);
        
        echo "<div class=\"stats-section\">\n";
        echo "<h3><i class=\"fas fa-clock\"></i> " . _QXZ("CALL TIME AND CHANNELS") . "</h3>\n";
        
        echo "<table class=\"stats-table\">\n";
        echo "<thead>\n";
        echo "<tr>\n";
        echo "<th>" . _QXZ("CHANNEL GROUP") . "</th>\n";
        echo "<th>" . _QXZ("COUNT") . "</th>\n";
        echo "<th>" . _QXZ("HOURS:MINUTES") . "</th>\n";
        echo "</tr>\n";
        echo "</thead>\n";
        echo "<tbody>\n";
        
        $total_calls = 0;
        $o = 0;
        
        while ($statuses_to_print > $o) {
            $row = mysqli_fetch_row($rslt);
            
            $call_seconds = $row[2];
            $call_hours = MathZDC($call_seconds, 3600);
            $call_hours = round($call_hours, 2);
            $call_hours_int = intval("$call_hours");
            $call_minutes = ($call_hours - $call_hours_int);
            $call_minutes = ($call_minutes * 60);
            $call_minutes_int = round($call_minutes, 0);
            
            if ($call_minutes_int < 10) {
                $call_minutes_int = "0$call_minutes_int";
            }
            
            echo "<tr>\n";
            echo "<td>$row[1]</td>\n";
            echo "<td>$row[0]</td>\n";
            echo "<td>$call_hours_int:$call_minutes_int</td>\n";
            echo "</tr>\n";
            
            $total_calls = ($total_calls + $row[0]);
            $call_seconds = 0;
            $o++;
        }
        
        $stmt = "SELECT sum(length_in_sec) from call_log where extension='" . mysqli_real_escape_string($this->link, $this->extension) . "' and server_ip='" . mysqli_real_escape_string($this->link, $this->server_ip) . "' and start_time >= '" . mysqli_real_escape_string($this->link, $this->begin_date) . " 0:00:01' and start_time <= '" . mysqli_real_escape_string($this->link, $this->end_date) . " 23:59:59'";
        $rslt = mysql_to_mysqli($stmt, $this->link);
        $row = mysqli_fetch_row($rslt);
        $call_seconds = $row[0];
        $call_hours = MathZDC($call_seconds, 3600);
        $call_hours = round($call_hours, 2);
        $call_hours_int = intval("$call_hours");
        $call_minutes = ($call_hours - $call_hours_int);
        $call_minutes = ($call_minutes * 60);
        $call_minutes_int = round($call_minutes, 0);
        
        if ($call_minutes_int < 10) {
            $call_minutes_int = "0$call_minutes_int";
        }
        
        echo "<tr>\n";
        echo "<td><strong>" . _QXZ("TOTAL CALLS") . "</strong></td>\n";
        echo "<td><strong>$total_calls</strong></td>\n";
        echo "<td><strong>$call_hours_int:$call_minutes_int</strong></td>\n";
        echo "</tr>\n";
        
        echo "</tbody>\n";
        echo "</table>\n";
        echo "</div>\n";
    }
    
    /**
     * Render the recent calls table
     */
    private function renderRecentCalls() {
        $rslt = $this->getRecentCalls();
        $events_to_print = mysqli_num_rows($rslt);
        
        echo "<div class=\"stats-section\">\n";
        echo "<h3><i class=\"fas fa-phone\"></i> " . _QXZ("LAST 1000 CALLS FOR DATE RANGE") . "</h3>\n";
        
        echo "<table class=\"stats-table\">\n";
        echo "<thead>\n";
        echo "<tr>\n";
        echo "<th>" . _QXZ("NUMBER") . "</th>\n";
        echo "<th>" . _QXZ("CHANNEL GROUP") . "</th>\n";
        echo "<th>" . _QXZ("DATE") . "</th>\n";
        echo "<th>" . _QXZ("LENGTH(MIN.)") . "</th>\n";
        echo "</tr>\n";
        echo "</thead>\n";
        echo "<tbody>\n";
        
        $o = 0;
        while ($events_to_print > $o) {
            $row = mysqli_fetch_row($rslt);
            
            echo "<tr>\n";
            echo "<td>$row[0]</td>\n";
            echo "<td>$row[1]</td>\n";
            echo "<td>$row[2]</td>\n";
            echo "<td>$row[3]</td>\n";
            echo "</tr>\n";
            
            $call_seconds = 0;
            $o++;
        }
        
        echo "</tbody>\n";
        echo "</table>\n";
        echo "</div>\n";
    }
    
    /**
     * Render the page footer
     */
    private function renderFooter() {
        $ENDtime = date("U");
        $STARTtime = date("U");
        $RUNtime = ($ENDtime - $STARTtime);
        
        echo "<div class=\"runtime\">" . _QXZ("Script runtime") . ": $RUNtime " . _QXZ("seconds") . "</div>\n";
        echo "</div>\n";
        echo "</body>\n";
        echo "</html>\n";
        
        // Update report log with runtime
        $endMS = microtime();
        $startMSary = explode(" ", $startMS);
        $endMSary = explode(" ", $endMS);
        $runS = ($endMSary[0] - $startMSary[0]);
        $runM = ($endMSary[1] - $startMSary[1]);
        $TOTALrun = ($runS + $runM);
        
        $stmt = "UPDATE vicidial_report_log set run_time='$TOTALrun' where report_log_id='$this->report_log_id';";
        $rslt = mysql_to_mysqli($stmt, $this->link);
    }
    
    /**
     * Handle slave database connection if needed
     */
    private function handleSlaveDB() {
        global $slave_db_server, $reports_use_slave_db, $db_source;
        
        if ((strlen($slave_db_server) > 5) and (preg_match("/Phone Stats/", $reports_use_slave_db))) {
            mysqli_close($this->link);
            $use_slave_server = 1;
            $db_source = 'S';
            require("dbconnect_mysqli.php");
        }
    }
    
    /**
     * Render the complete page
     */
    public function render() {
        $this->handleSlaveDB();
        $this->renderHeader();
        $this->renderNavigation();
        $this->renderFilterForm();
        $this->renderCallTimeStats();
        $this->renderRecentCalls();
        $this->renderFooter();
    }
}

// Initialize and render the report
 $phoneStatsReport = new PhoneStatsReport();
 $phoneStatsReport->render();

// Close slave database connection if it was used
if ($db_source == 'S') {
    mysqli_close($link);
    $use_slave_server = 0;
    $db_source = 'M';
    require("dbconnect_mysqli.php");
}

exit;
?>