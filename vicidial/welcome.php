<?php
# welcome.php - VICIDIAL welcome page
# 
# Copyright (C) 2023  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# CHANGELOG:
# 141007-2140 - Finalized adding QXZ translation to all admin files
# 161106-1920 - Changed to use newer design and dynamic links
# 220228-1109 - Added allow_web_debug system setting
# 231119-1540 - Added HCI Screen link if hopper_hold_inserts are allowed on the system
# 240615-1200 - Modernized UI with responsive design and improved UX

header ("Content-type: text/html; charset=utf-8");

require_once("dbconnect_mysqli.php");
require("functions.php");

# if options file exists, use the override values for the above variables
#   see the options-example.php file for more information
if (file_exists('options.php'))
    {
    require('options.php');
    }

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
 $stmt = "SELECT use_non_latin,enable_languages,language_method,default_language,agent_screen_colors,admin_web_directory,agent_script,allow_web_debug,hopper_hold_inserts FROM system_settings;";
 $rslt=mysql_to_mysqli($stmt, $link);
    if ($mel > 0) {mysql_error_logging($NOW_TIME,$link,$mel,$stmt,'01001',$VD_login,$server_ip,$session_name,$one_mysql_log);}
#if ($DB) {echo "$stmt\n";}
 $qm_conf_ct = mysqli_num_rows($rslt);
if ($qm_conf_ct > 0)
    {
    $row=mysqli_fetch_row($rslt);
    $non_latin =				$row[0];
    $SSenable_languages =		$row[1];
    $SSlanguage_method =		$row[2];
    $default_language =			$row[3];
    $agent_screen_colors =		$row[4];
    $admin_web_directory =		$row[5];
    $SSagent_script =			$row[6];
    $SSallow_web_debug =		$row[7];
    $SShopper_hold_inserts =	$row[8];
    }
if ($SSallow_web_debug < 1) {$DB=0;}
##### END SETTINGS LOOKUP #####
###########################################

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

echo"<!DOCTYPE html>\n";
echo"<html lang=\"en\">\n";
echo"<head>\n";
echo"<meta charset=\"utf-8\">\n";
echo"<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
echo"<title>"._QXZ("Welcome Screen")."</title>\n";
echo"<link rel=\"stylesheet\" type=\"text/css\" href=\"../agc/css/style.css\" />\n";
echo"<link rel=\"stylesheet\" type=\"text/css\" href=\"../agc/css/custom.css\" />\n";
echo"<link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css\">\n";
echo"<style>\n";
echo"
:root {
    --primary-color: #$SSmenu_background;
    --secondary-color: #$SSframe_background;
    --accent-color: #$SSstd_row1_background;
    --text-color: #333;
    --light-text: #fff;
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
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    color: var(--text-color);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.container {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.welcome-card {
    background-color: white;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
    width: 100%;
    max-width: 500px;
    overflow: hidden;
    transition: var(--transition);
}

.welcome-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
}

.card-header {
    background-color: var(--primary-color);
    color: var(--light-text);
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.logo {
    height: 45px;
    width: auto;
    max-width: 170px;
}

.welcome-title {
    font-size: 1.8rem;
    font-weight: 600;
    margin: 0;
}

.card-body {
    padding: 30px 20px;
}

.menu-list {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.menu-item {
    border-radius: var(--border-radius);
    overflow: hidden;
    transition: var(--transition);
}

.menu-link {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    background-color: var(--secondary-color);
    color: var(--text-color);
    text-decoration: none;
    font-weight: 500;
    font-size: 1.1rem;
    transition: var(--transition);
    border-left: 4px solid transparent;
}

.menu-link:hover {
    background-color: var(--accent-color);
    border-left: 4px solid var(--primary-color);
    transform: translateX(5px);
}

.menu-link i {
    margin-right: 15px;
    font-size: 1.2rem;
    color: var(--primary-color);
}

.footer {
    text-align: center;
    padding: 20px;
    color: var(--text-color);
    font-size: 0.9rem;
}

@media (max-width: 600px) {
    .welcome-card {
        max-width: 100%;
    }
    
    .card-header {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .welcome-title {
        font-size: 1.5rem;
    }
    
    .menu-link {
        padding: 12px 15px;
        font-size: 1rem;
    }
    
    .menu-link i {
        margin-right: 10px;
    }
}

/* Loading animation */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s, visibility 0.3s;
}

.loading-overlay.active {
    opacity: 1;
    visibility: visible;
}

.loading-spinner {
    width: 50px;
    height: 50px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
";
echo"</style>\n";
echo"</head>\n";
echo"<body>\n";

// Loading overlay
echo"<div class=\"loading-overlay\" id=\"loadingOverlay\">\n";
echo"    <div class=\"loading-spinner\"></div>\n";
echo"</div>\n";

echo"<div class=\"container\">\n";
echo"    <div class=\"welcome-card\">\n";
echo"        <div class=\"card-header\">\n";
echo"            <img src=\"$selected_logo\" class=\"logo\" alt=\"VICIDIAL Logo\" />\n";
echo"            <h1 class=\"welcome-title\">"._QXZ("Welcome")."</h1>\n";
echo"        </div>\n";
echo"        <div class=\"card-body\">\n";
echo"            <ul class=\"menu-list\">\n";
echo"                <li class=\"menu-item\">\n";
echo"                    <a href=\"../agc/$SSagent_script\" class=\"menu-link\" id=\"agentLoginLink\">\n";
echo"                        <i class=\"fas fa-sign-in-alt\"></i>\n";
echo"                        "._QXZ("Agent Login")."\n";
echo"                    </a>\n";
echo"                </li>\n";

if ($hide_timeclock_link < 1) {
echo"                <li class=\"menu-item\">\n";
echo"                    <a href=\"../agc/timeclock.php?referrer=welcome\" class=\"menu-link\" id=\"timeclockLink\">\n";
echo"                        <i class=\"fas fa-clock\"></i>\n";
echo"                        "._QXZ("Timeclock")."\n";
echo"                    </a>\n";
echo"                </li>\n";
}

if ($SShopper_hold_inserts > 0) {
echo"                <li class=\"menu-item\">\n";
echo"                    <a href=\"../$admin_web_directory/hci_screen.php\" class=\"menu-link\" id=\"hciScreenLink\">\n";
echo"                        <i class=\"fas fa-users\"></i>\n";
echo"                        "._QXZ("HCI Screen")."\n";
echo"                    </a>\n";
echo"                </li>\n";
}

echo"                <li class=\"menu-item\">\n";
echo"                    <a href=\"../$admin_web_directory/admin.php\" class=\"menu-link\" id=\"adminLink\">\n";
echo"                        <i class=\"fas fa-cogs\"></i>\n";
echo"                        "._QXZ("Administration")."\n";
echo"                    </a>\n";
echo"                </li>\n";
echo"            </ul>\n";
echo"        </div>\n";
echo"    </div>\n";
echo"</div>\n";

echo"<div class=\"footer\">\n";
echo"    <p>&copy; " . date("Y") . " VICIDIAL - "._QXZ("Open Source Contact Center Suite")."</p>\n";
echo"</div>\n";

echo"<script>\n";
echo"
// Add loading functionality to all links
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('.menu-link');
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            // Show loading overlay
            loadingOverlay.classList.add('active');
            
            // If it's an external link, let it navigate normally
            // The loading overlay will remain until the new page loads
        });
    });
    
    // Add some interactive effects
    const menuItems = document.querySelectorAll('.menu-item');
    
    menuItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
    
    // Add keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key >= '1' && e.key <= '4') {
            const index = parseInt(e.key) - 1;
            const links = document.querySelectorAll('.menu-link');
            if (links[index]) {
                links[index].click();
            }
        }
    });
});
";
echo"</script>\n";
echo"</body>\n";
echo"</html>\n";
exit;
?>