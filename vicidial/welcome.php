<?php
# welcome.php - VICIDIAL welcome page
# 
# Copyright (C) 2023  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
# CHANGELOG:
# 141007-2140 - Finalized adding QXZ translation to all admin files
# 161106-1920 - Changed to use newer design and dynamic links
# 220228-1100 - Added allow_web_debug system setting
# 231119-1540 - Added HCI Screen link if hopper_hold_inserts are allowed on the system
# 240615-1200 - Modernized UI with cxoTel design and improved UX

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
    --primary-color: #6a11cb;
    --secondary-color: #2575fc;
    --accent-color: #ffffff;
    --text-color: #333333;
    --light-text: #ffffff;
    --border-radius: 8px;
    --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    --transition: all 0.3s ease;
}

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: var(--text-color);
    height: 100vh;
    overflow: hidden;
}

.login-container { display: flex; height: 100vh; }

.left-panel {
    flex: 1;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    display: flex; flex-direction: column; justify-content: center; align-items: center;
    color: var(--light-text); padding: 2rem; position: relative; overflow: hidden;
}
.left-panel::before {
    content: ''; position: absolute; inset: 0;
    background: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"100\" height=\"100\" viewBox=\"0 0 100 100\"><rect width=\"100\" height=\"100\" fill=\"none\"/><circle cx=\"50\" cy=\"50\" r=\"40\" stroke=\"rgba(255,255,255,0.1)\" stroke-width=\"0.5\" fill=\"none\"/></svg>') 0 0/100px 100px;
    opacity: .3; z-index: 1;
}
.left-content { text-align: center; z-index: 2; }
.headphones-icon { font-size: 5rem; margin-bottom: 2rem; animation: pulse 2s infinite; }
@keyframes pulse { 0%{transform:scale(1)} 50%{transform:scale(1.05)} 100%{transform:scale(1)} }
.tagline { font-size: 1.8rem; font-weight: 300; margin-bottom: 2rem; letter-spacing: .5px; }

.loading-dots { display:flex; justify-content:center; margin-top:2rem; }
.dot { width:10px; height:10px; border-radius:50%; background:var(--light-text); margin:0 5px; animation:loading 1.5s infinite ease-in-out; }
.dot:nth-child(1){animation-delay:-.32s} .dot:nth-child(2){animation-delay:-.16s}
@keyframes loading { 0%,80%,100%{transform:scale(0);opacity:.5} 40%{transform:scale(1);opacity:1} }

.right-panel { flex:1; background:var(--accent-color); display:flex; flex-direction:column; justify-content:center; align-items:center; padding:2rem; }
.logo-container { margin-bottom:2rem; }
.logo { height:50px; width:auto; }
.welcome-title { font-size:2.5rem; font-weight:600; margin-bottom:1rem; color:var(--text-color); }
.access-prompt { font-size:1.1rem; margin-bottom:2.5rem; color:#666; }

.access-options { width:100%; max-width:400px; }
.access-button {
    display:block; width:100%; padding:15px 20px; margin-bottom:1rem;
    background:#f8f9fa; border:1px solid #e9ecef; border-radius:var(--border-radius);
    color:var(--text-color); text-decoration:none; font-weight:500; font-size:1.1rem;
    text-align:center; transition:var(--transition); cursor:pointer;
}
.access-button:hover { background:#e9ecef; transform:translateY(-2px); box-shadow:var(--box-shadow); }
.access-button i { margin-right:10px; color:var(--primary-color); }

.footer { position:absolute; bottom:20px; text-align:center; font-size:.8rem; color:#666; }
.social-icons { margin-top:1rem; display:flex; justify-content:center; gap:15px; }
.social-icon { color:#666; font-size:1.2rem; transition:var(--transition); }
.social-icon:hover { color:var(--primary-color); }

.loading-overlay {
    position:fixed; inset:0; background:rgba(255,255,255,.9);
    display:flex; justify-content:center; align-items:center; z-index:1000;
    opacity:0; visibility:hidden; transition:opacity .3s, visibility .3s;
}
.loading-overlay.active { opacity:1; visibility:visible; }
.loading-spinner { width:50px; height:50px; border:5px solid #f3f3f3; border-top:5px solid var(--primary-color); border-radius:50%; animation:spin 1s linear infinite; }
@keyframes spin { 0%{transform:rotate(0)} 100%{transform:rotate(360deg)} }

@media (max-width: 768px){
  .login-container{flex-direction:column}
  .left-panel{flex:0 0 40%; min-height:40vh}
  .right-panel{flex:1; min-height:60vh}
  .headphones-icon{font-size:3rem}
  .tagline{font-size:1.4rem}
  .welcome-title{font-size:2rem}
}
@media (max-width: 480px){
  .left-panel{flex:0 0 30%; min-height:30vh}
  .right-panel{flex:1; min-height:70vh}
  .headphones-icon{font-size:2.5rem}
  .tagline{font-size:1.2rem}
  .welcome-title{font-size:1.8rem}
  .access-button{padding:12px 15px; font-size:1rem}
}
";
echo"</style>\n";
echo"</head>\n";
echo"<body>\n";

# Loading overlay
echo"<div class=\"loading-overlay\" id=\"loadingOverlay\">\n";
echo"    <div class=\"loading-spinner\"></div>\n";
echo"</div>\n";

echo"<div class=\"login-container\">\n";
echo"    <div class=\"left-panel\">\n";
echo"        <div class=\"left-content\">\n";
echo"            <div class=\"headphones-icon\">\n";
echo"                <i class=\"fas fa-headset\"></i>\n";
echo"            </div>\n";
echo"            <h2 class=\"tagline\">Keeping you always connected</h2>\n";
echo"            <div class=\"loading-dots\">\n";
echo"                <div class=\"dot\"></div>\n";
echo"                <div class=\"dot\"></div>\n";
echo"                <div class=\"dot\"></div>\n";
echo"            </div>\n";
echo"        </div>\n";
echo"    </div>\n";
echo"    <div class=\"right-panel\">\n";
echo"        <div class=\"logo-container\">\n";
echo"            <img src=\"$selected_logo\" class=\"logo\" alt=\"cxoTel Logo\" />\n";
echo"        </div>\n";
echo"        <h1 class=\"welcome-title\">"._QXZ("Welcome")."</h1>\n";
echo"        <p class=\"access-prompt\">"._QXZ("Please select your access type")."</p>\n";
echo"        <div class=\"access-options\">\n";

# ---------- Agent Login (existing) ----------
echo"            <a href=\"../agc/$SSagent_script\" class=\"access-button\" id=\"agentLoginLink\">\n";
echo"                <i class=\"fas fa-sign-in-alt\"></i>\n";
echo"                "._QXZ("Agent Login")."\n";
echo"            </a>\n";

# ---------- NEW: Agent Direct Deep Link (your requested hyperlink) ----------
$agent_deeplink = 'https://cxotel.call.1bill.in/agc/vicidial.php?relogin=YES&session_epoch=1762343261&session_id=8600051&session_name=1762343254_100214236676&VD_login=testing&VD_campaign=001&phone_login=1002&phone_pass=test&VD_pass=testing&LOGINvarONE=&LOGINvarTWO=&LOGINvarTHREE=&LOGINvarFOUR=&LOGINvarFIVE=&hide_relogin_fields=';
$agent_deeplink_html = htmlspecialchars($agent_deeplink, ENT_QUOTES, 'UTF-8');

echo"            <a href=\"{$agent_deeplink_html}\" class=\"access-button\" id=\"agentDeepLink\">\n";
echo"                <i class=\"fas fa-link\"></i>\n";
echo"                "._QXZ("Direct Agent Login (auto-fill)")."\n";
echo"            </a>\n";
# ---------- /NEW ----------

if ($hide_timeclock_link < 1) {
echo"            <a href=\"../agc/timeclock.php?referrer=welcome\" class=\"access-button\" id=\"timeclockLink\">\n";
echo"                <i class=\"fas fa-clock\"></i>\n";
echo"                "._QXZ("Timeclock")."\n";
echo"            </a>\n";
}

if ($SShopper_hold_inserts > 0) {
echo"            <a href=\"../$admin_web_directory/hci_screen.php\" class=\"access-button\" id=\"hciScreenLink\">\n";
echo"                <i class=\"fas fa-users\"></i>\n";
echo"                "._QXZ("HCI Screen")."\n";
echo"            </a>\n";
}

echo"            <a href=\"../$admin_web_directory/admin.php\" class=\"access-button\" id=\"adminLink\">\n";
echo"                <i class=\"fas fa-cogs\"></i>\n";
echo"                "._QXZ("Administration")."\n";
echo"            </a>\n";
echo"        </div>\n";
echo"        <div class=\"footer\">\n";
echo"            <p>&copy; " . date("Y") . " cxoTel - "._QXZ("Open Source Contact Center Suite")."</p>\n";
echo"            <div class=\"social-icons\">\n";
echo"                <a href=\"#\" class=\"social-icon\"><i class=\"fab fa-facebook-f\"></i></a>\n";
echo"                <a href=\"#\" class=\"social-icon\"><i class=\"fab fa-twitter\"></i></a>\n";
echo"                <a href=\"#\" class=\"social-icon\"><i class=\"fab fa-linkedin-in\"></i></a>\n";
echo"                <a href=\"#\" class=\"social-icon\"><i class=\"fab fa-instagram\"></i></a>\n";
echo"            </div>\n";
echo"        </div>\n";
echo"    </div>\n";
echo"</div>\n";

echo"<script>\n";
echo"
// Add loading functionality to all links
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('.access-button');
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    links.forEach(link => {
        link.addEventListener('click', function() {
            loadingOverlay.classList.add('active');
        });
    });
    
    // Keyboard shortcuts: 1=Agent Login, 2=Next option, 3=Admin
    document.addEventListener('keydown', function(e) {
        const links = document.querySelectorAll('.access-button');
        let targetIndex = -1;
        switch(e.key) {
            case '1': targetIndex = 0; break;
            case '2': targetIndex = 1; break;
            case '3': targetIndex = links.length - 1; break;
        }
        if (targetIndex >= 0 && links[targetIndex]) {
            links[targetIndex].click();
        }
    });

    // Social icon placeholders
    const socialIcons = document.querySelectorAll('.social-icon');
    socialIcons.forEach(icon => {
        icon.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Social media link clicked');
        });
    });
});
";
echo"</script>\n";
echo"</body>\n";
echo"</html>\n";
exit;
?>
