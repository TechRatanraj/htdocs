<?php
// HTML Colors Array
 $HTMLcolors = 'IndianRed,CD5C5C|LightCoral,F08080|Salmon,FA8072|DarkSalmon,E9967A|LightSalmon,FFA07A|Crimson,DC143C|Red,FF0000|FireBrick,B22222|DarkRed,8B0000|Pink,FFC0CB|LightPink,FFB6C1|HotPink,FF69B4|DeepPink,FF1493|MediumVioletRed,C71585|PaleVioletRed,DB7093|LightSalmon,FFA07A|Coral,FF7F50|Tomato,FF6347|OrangeRed,FF4500|DarkOrange,FF8C00|Orange,FFA500|Gold,FFD700|Yellow,FFFF00|LightYellow,FFFFE0|LemonChiffon,FFFACD|LightGoldenrodYellow,FAFAD2|PapayaWhip,FFEFD5|Moccasin,FFE4B5|PeachPuff,FFDAB9|PaleGoldenrod,EEE8AA|Khaki,F0E68C|DarkKhaki,BDB76B|Lavender,E6E6FA|Thistle,D8BFD8|Plum,DDA0DD|Violet,EE82EE|Orchid,DA70D6|Fuchsia,FF00FF|Magenta,FF00FF|MediumOrchid,BA55D3|MediumPurple,9370DB|RebeccaPurple,663399|BlueViolet,8A2BE2|DarkViolet,9400D3|DarkOrchid,9932CC|DarkMagenta,8B008B|Purple,800080|Indigo,4B0082|SlateBlue,6A5ACD|DarkSlateBlue,483D8B|MediumSlateBlue,7B68EE|GreenYellow,ADFF2F|Chartreuse,7FFF00|LawnGreen,7CFC00|Lime,00FF00|LimeGreen,32CD32|PaleGreen,98FB98|LightGreen,90EE90|MediumSpringGreen,00FA9A|SpringGreen,00FF7F|MediumSeaGreen,3CB371|SeaGreen,2E8B57|ForestGreen,228B22|Green,008000|DarkGreen,006400|YellowGreen,9ACD32|OliveDrab,6B8E23|Olive,808000|DarkOliveGreen,556B2F|MediumAquamarine,66CDAA|DarkSeaGreen,8FBC8B|LightSeaGreen,20B2AA|DarkCyan,008B8B|Teal,008080|Aqua,00FFFF|Cyan,00FFFF|LightCyan,E0FFFF|PaleTurquoise,AFEEEE|Aquamarine,7FFFD4|Turquoise,40E0D0|MediumTurquoise,48D1CC|DarkTurquoise,00CED1|CadetBlue,5F9EA0|SteelBlue,4682B4|LightSteelBlue,B0C4DE|PowderBlue,B0E0E6|LightBlue,ADD8E6|SkyBlue,87CEEB|LightSkyBlue,87CEFA|DeepSkyBlue,00BFFF|DodgerBlue,1E90FF|CornflowerBlue,6495ED|MediumSlateBlue,7B68EE|RoyalBlue,4169E1|Blue,0000FF|MediumBlue,0000CD|DarkBlue,00008B|Navy,000080|MidnightBlue,191970|Cornsilk,FFF8DC|BlanchedAlmond,FFEBCD|Bisque,FFE4C4|NavajoWhite,FFDEAD|Wheat,F5DEB3|BurlyWood,DEB887|Tan,D2B48C|RosyBrown,BC8F8F|SandyBrown,F4A460|Goldenrod,DAA520|DarkGoldenrod,B8860B|Peru,CD853F|Chocolate,D2691E|SaddleBrown,8B4513|Sienna,A0522D|Brown,A52A2A|Maroon,800000|White,FFFFFF|Snow,FFFAFA|HoneyDew,F0FFF0|MintCream,F5FFFA|Azure,F0FFFF|AliceBlue,F0F8FF|GhostWhite,F8F8FF|WhiteSmoke,F5F5F5|SeaShell,FFF5EE|Beige,F5F5DC|OldLace,FDF5E6|FloralWhite,FFFAF0|Ivory,FFFFF0|AntiqueWhite,FAEBD7|Linen,FAF0E6|LavenderBlush,FFF0F5|MistyRose,FFE4E1|Gainsboro,DCDCDC|LightGray,D3D3D3|Silver,C0C0C0|DarkGray,A9A9A9|Gray,808080|DimGray,696969|LightSlateGray,778899|SlateGray,708090|DarkSlateGray,2F4F4F|Black,000000';

// Database Query
 $stmt="SELECT admin_home_url,enable_tts_integration,callcard_enabled,custom_fields_enabled,allow_emails,level_8_disable_add,allow_chats,enable_languages,admin_row_click,admin_screen_colors,user_new_lead_limit,user_territories_active,qc_features_active,agent_soundboards,enable_drop_lists,allow_ip_lists,admin_web_directory from system_settings;";
 $rslt=mysql_to_mysqli($stmt, $link);
 $row=mysqli_fetch_row($rslt);
 $admin_home_url_LU = $row[0];
 $SSenable_tts_integration = $row[1];
 $SScallcard_enabled = $row[2];
 $SScustom_fields_enabled = $row[3];
 $SSemail_enabled = $row[4];
 $SSlevel_8_disable_add = $row[5];
 $SSchat_enabled = $row[6];
 $SSenable_languages = $row[7];
 $SSadmin_row_click = $row[8];
 $SSadmin_screen_colors = $row[9];
 $SSuser_new_lead_limit = $row[10];
 $SSuser_territories_active = $row[11];
 $SSqc_features_active = $row[12];
 $SSagent_soundboards = $row[13];
 $SSenable_drop_lists = $row[14];
 $SSallow_ip_lists = $row[15];
 $SSadmin_web_directory = $row[16];

if (strlen($SSadmin_home_url) > 5) {$admin_home_url_LU = $SSadmin_home_url;}
if(!isset($ADMIN)){$ADMIN = "../$SSadmin_web_directory/admin.php";}

// Default Colors
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
 $SSbutton_color='EFEFEF';

// Get Custom Colors
if ($SSadmin_screen_colors != 'default') {
    $stmt = "SELECT menu_background,frame_background,std_row1_background,std_row2_background,std_row3_background,std_row4_background,std_row5_background,alt_row1_background,alt_row2_background,alt_row3_background,web_logo,button_color FROM vicidial_screen_colors where colors_id='$SSadmin_screen_colors';";
    $rslt=mysql_to_mysqli($stmt, $link);
    if ($DB) {echo "$stmt\n";}
    $colors_ct = mysqli_num_rows($rslt);
    if ($colors_ct > 0) {
        $row=mysqli_fetch_row($rslt);
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
        $SSweb_logo = $row[10];
        $SSbutton_color = $row[11];
    }
}

 $Mhead_color = $SSstd_row5_background;
 $Mmain_bgcolor = $SSmenu_background;

// Logo Selection
 $selected_logo = "./images/vicidial_admin_web_logo.png";
 $selected_small_logo = "./images/vicidial_admin_web_logo.png";
 $logo_new=0;
 $logo_old=0;
 $logo_small_old=0;
if (file_exists('./images/vicidial_admin_web_logo.png')) {$logo_new++;}
if (file_exists('vicidial_admin_web_logo_small.gif')) {$logo_small_old++;}
if (file_exists('vicidial_admin_web_logo.gif')) {$logo_old++;}
if ($SSweb_logo=='default_new') {
    $selected_logo = "./images/vicidial_admin_web_logo.png";
    $selected_small_logo = "./images/vicidial_admin_web_logo.png";
}
if (($SSweb_logo=='default_old') and ($logo_old > 0)) {
    $selected_logo = "./vicidial_admin_web_logo.gif";
    $selected_small_logo = "./vicidial_admin_web_logo_small.gif";
}
if (($SSweb_logo!='default_new') and ($SSweb_logo!='default_old')) {
    if (file_exists("./images/vicidial_admin_web_logo$SSweb_logo")) {
        $selected_logo = "./images/vicidial_admin_web_logo$SSweb_logo";
        $selected_small_logo = "./images/vicidial_admin_web_logo$SSweb_logo";
    }
}

// Dynamic Header Content
if ($hh=='users') {
    $users_hh="CLASS=\"head_style_selected\""; $users_fc="$users_font"; $users_bold="$header_selected_bold";
    $users_icon="<i class=\"fas fa-users\" style=\"color:#000;\"></i>";
} else {
    $users_hh="CLASS=\"head_style\""; $users_fc='WHITE'; $users_bold="$header_nonselected_bold";
    $users_icon="<i class=\"fas fa-users\" style=\"color:#fff;\"></i>";
}
if ($hh=='campaigns') {
    $campaigns_hh="CLASS=\"head_style_selected\""; $campaigns_fc="$campaigns_font"; $campaigns_bold="$header_selected_bold";
    $campaigns_icon="<i class=\"fas fa-bullhorn\" style=\"color:#000;\"></i>";
} else {
    $campaigns_hh="CLASS=\"head_style\""; $campaigns_fc='WHITE'; $campaigns_bold="$header_nonselected_bold";
    $campaigns_icon="<i class=\"fas fa-bullhorn\" style=\"color:#fff;\"></i>";
}
if ($hh=='lists') {
    $lists_hh="CLASS=\"head_style_selected\""; $lists_fc="$lists_font"; $lists_bold="$header_selected_bold";
    $lists_icon="<i class=\"fas fa-list\" style=\"color:#000;\"></i>";
} else {
    $lists_hh="CLASS=\"head_style\""; $lists_fc='WHITE'; $lists_bold="$header_nonselected_bold";
    $lists_icon="<i class=\"fas fa-list\" style=\"color:#fff;\"></i>";
}
if ($hh=='ingroups') {
    $ingroups_hh="CLASS=\"head_style_selected\""; $ingroups_fc="$ingroups_font"; $ingroups_bold="$header_selected_bold";
    $inbound_icon="<i class=\"fas fa-phone-alt\" style=\"color:#000;\"></i>";
} else {
    $ingroups_hh="CLASS=\"head_style\""; $ingroups_fc='WHITE'; $ingroups_bold="$header_nonselected_bold";
    $inbound_icon="<i class=\"fas fa-phone-alt\" style=\"color:#fff;\"></i>";
}
if ($hh=='remoteagent') {
    $remoteagent_hh="CLASS=\"head_style_selected\""; $remoteagent_fc="$remoteagent_font"; $remoteagent_bold="$header_selected_bold";
    $remoteagents_icon="<i class=\"fas fa-laptop\" style=\"color:#000;\"></i>";
} else {
    $remoteagent_hh="CLASS=\"head_style\""; $remoteagent_fc='WHITE'; $remoteagent_bold="$header_nonselected_bold";
    $remoteagents_icon="<i class=\"fas fa-laptop\" style=\"color:#fff;\"></i>";
}
if ($hh=='usergroups') {
    $usergroups_hh="CLASS=\"head_style_selected\""; $usergroups_fc="$usergroups_font"; $usergroups_bold="$header_selected_bold";
    $usergroups_icon="<i class=\"fas fa-users-cog\" style=\"color:#000;\"></i>";
} else {
    $usergroups_hh="CLASS=\"head_style\""; $usergroups_fc='WHITE'; $usergroups_bold="$header_nonselected_bold";
    $usergroups_icon="<i class=\"fas fa-users-cog\" style=\"color:#fff;\"></i>";
}
if ($hh=='scripts') {
    $scripts_hh="CLASS=\"head_style_selected\""; $scripts_fc="$scripts_font"; $scripts_bold="$header_selected_bold";
    $scripts_icon="<i class=\"fas fa-file-alt\" style=\"color:#000;\"></i>";
} else {
    $scripts_hh="CLASS=\"head_style\""; $scripts_fc='WHITE'; $scripts_bold="$header_nonselected_bold";
    $scripts_icon="<i class=\"fas fa-file-alt\" style=\"color:#fff;\"></i>";
}
if ($hh=='filters') {
    $filters_hh="CLASS=\"head_style_selected\""; $filters_fc="$filters_font"; $filters_bold="$header_selected_bold";
    $filters_icon="<i class=\"fas fa-filter\" style=\"color:#000;\"></i>";
} else {
    $filters_hh="CLASS=\"head_style\""; $filters_fc='WHITE'; $filters_bold="$header_nonselected_bold";
    $filters_icon="<i class=\"fas fa-filter\" style=\"color:#fff;\"></i>";
}
if ($hh=='admin') {
    $admin_hh="CLASS=\"head_style_selected\""; $admin_fc="$admin_font"; $admin_bold="$header_selected_bold";
    $admin_icon="<i class=\"fas fa-cog\" style=\"color:#000;\"></i>";
} else {
    $admin_hh="CLASS=\"head_style\""; $admin_fc='WHITE'; $admin_bold="$header_nonselected_bold";
    $admin_icon="<i class=\"fas fa-cog\" style=\"color:#fff;\"></i>";
}
if ($hh=='reports') {
    $reports_hh="CLASS=\"head_style_selected\""; $reports_fc="$reports_font"; $reports_bold="$header_selected_bold";
    $reports_icon="<i class=\"fas fa-chart-bar\" style=\"color:#000;\"></i>";
} else {
    $reports_hh="CLASS=\"head_style\""; $reports_fc='WHITE'; $reports_bold="$header_nonselected_bold";
    $reports_icon="<i class=\"fas fa-chart-bar\" style=\"color:#fff;\"></i>";
}
if ($hh=='qc') {
    $qc_hh="CLASS=\"head_style_selected\""; $qc_fc="$qc_font"; $qc_bold="$header_selected_bold";
    $qc_icon="<i class=\"fas fa-clipboard-check\" style=\"color:#000;\"></i>";
} else {
    $qc_hh="CLASS=\"head_style\""; $qc_fc='WHITE'; $qc_bold="$header_nonselected_bold";
    $qc_icon="<i class=\"fas fa-clipboard-check\" style=\"color:#fff;\"></i>";
}

// HTML Output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vicidial Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #<?php echo $SSmenu_background; ?>;
            --secondary-color: #<?php echo $SSstd_row5_background; ?>;
            --accent-color: #<?php echo $SSstd_row1_background; ?>;
            --text-color: #ffffff;
            --hover-bg: rgba(255, 255, 255, 0.15);
            --border-radius: 12px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
        }

        .admin-header {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 20px;
            overflow: hidden;
            position: relative;
            transition: var(--transition);
        }

        .admin-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05));
            pointer-events: none;
        }

        .header-container {
            display: flex;
            align-items: center;
            padding: 16px 24px;
            position: relative;
            z-index: 1;
        }

        .logo-container {
            display: flex;
            align-items: center;
            padding-right: 24px;
            border-right: 1px solid rgba(255, 255, 255, 0.15);
        }

        .logo-link {
            display: inline-block;
            transition: var(--transition);
            opacity: 0.9;
        }

        .logo-link:hover {
            opacity: 1;
            transform: scale(1.05);
        }

        .logo-img {
            display: block;
            max-height: 45px;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .nav-menu {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            padding: 0 16px;
            gap: 8px;
            flex-grow: 1;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            color: var(--text-color);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            font-family: 'Segoe UI', Arial, sans-serif;
            border-radius: 8px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .nav-link:hover::before {
            left: 100%;
        }

        .nav-link:hover {
            background-color: var(--hover-bg);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .nav-link.active {
            background-color: var(--secondary-color);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .nav-icon {
            font-size: 16px;
            transition: var(--transition);
        }

        .nav-link:hover .nav-icon {
            transform: scale(1.1);
        }

        .mobile-header {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 20px;
            overflow: hidden;
            position: relative;
        }

        .mobile-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
        }

        .mobile-logo {
            transition: var(--transition);
            opacity: 0.9;
        }

        .mobile-logo:hover {
            opacity: 1;
            transform: scale(1.05);
        }

        .mobile-nav-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            color: var(--text-color);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            transition: var(--transition);
            backdrop-filter: blur(10px);
        }

        .mobile-nav-link:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .logo-only-header {
            background: #ffffff;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 20px;
            overflow: hidden;
            transition: var(--transition);
        }

        .logo-only-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #<?php echo $SSbutton_color; ?>, #e0e0e0);
            border: none;
            border-radius: 8px;
            color: #333;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: var(--transition);
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            background: linear-gradient(135deg, #e0e0e0, #d0d0d0);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn:active {
            transform: translateY(0);
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #555;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: var(--transition);
            background-color: #fff;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(<?php echo hex2rgb($SSmenu_background); ?>, 0.1);
        }

        .table-modern {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .table-modern th {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
        }

        .table-modern td {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f0;
        }

        .table-modern tr:hover td {
            background-color: #f8f9fa;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            transition: var(--transition);
        }

        .card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .card-header {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 12px;
            background: var(--primary-color);
            color: white;
        }

        .progress {
            width: 100%;
            height: 8px;
            background: #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            transition: width 0.3s ease;
        }

        @media (max-width: 768px) {
            .nav-menu {
                flex-direction: column;
                align-items: stretch;
            }
            
            .nav-item {
                width: 100%;
            }
            
            .nav-link {
                width: 100%;
                justify-content: flex-start;
            }
            
            .header-container {
                flex-direction: column;
                gap: 16px;
            }
            
            .logo-container {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.15);
                padding-right: 0;
                padding-bottom: 16px;
            }
        }
    </style>
</head>
<body>
    <?php
    if ($short_header) {
        if ($no_header) {
            // Display nothing
        } else {
            // Logo-only mode for reports
            if (($LOGreports_header_override == 'LOGO_ONLY_SMALL') || ($LOGreports_header_override == 'LOGO_ONLY_LARGE')) {
                $temp_logo = $selected_logo;
                $temp_logo_size = 'width="170" height="45"';
                
                if ($LOGreports_header_override == 'LOGO_ONLY_SMALL') {
                    $temp_logo = $selected_small_logo;
                    $temp_logo_size = 'width="71" height="22"';
                }
                ?>
                <div class="logo-only-header">
                    <div class="logo-only-container">
                        <a href="<?php echo htmlspecialchars($admin_home_url_LU); ?>" class="logo-link">
                            <img src="<?php echo htmlspecialchars($temp_logo); ?>" <?php echo $temp_logo_size; ?> border="0" alt="System logo" class="logo-img">
                        </a>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <header class="admin-header">
                    <div class="header-container">
                        <div class="logo-container">
                            <a href="<?php echo htmlspecialchars($ADMIN); ?>" class="logo-link">
                                <img src="<?php echo htmlspecialchars($selected_small_logo); ?>" width="71" height="22" border="0" alt="System logo" class="logo-img">
                            </a>
                        </div>
                        
                        <nav class="nav-menu">
                            <?php
                            // Full access menu
                            if (($reports_only_user < 1) && ($qc_only_user < 1)) {
                                $menuItems = [
                                    ['url' => $ADMIN . '?ADD=999999', 'icon' => $reports_icon, 'text' => _QXZ("Reports")],
                                    ['url' => $ADMIN . '?ADD=0A', 'icon' => $users_icon, 'text' => _QXZ("Users")],
                                    ['url' => $ADMIN . '?ADD=10', 'icon' => $campaigns_icon, 'text' => _QXZ("Campaigns")],
                                    ['url' => $ADMIN . '?ADD=100', 'icon' => $lists_icon, 'text' => _QXZ("Lists")],
                                    ['url' => $ADMIN . '?ADD=1000000', 'icon' => $scripts_icon, 'text' => _QXZ("Scripts")],
                                    ['url' => $ADMIN . '?ADD=10000000', 'icon' => $filters_icon, 'text' => _QXZ("Filters")],
                                    ['url' => $ADMIN . '?ADD=1001', 'icon' => $inbound_icon, 'text' => _QXZ("Inbound")],
                                    ['url' => $ADMIN . '?ADD=100000', 'icon' => $usergroups_icon, 'text' => _QXZ("User Groups")],
                                    ['url' => $ADMIN . '?ADD=10000', 'icon' => $remoteagents_icon, 'text' => _QXZ("Remote Agents")],
                                    ['url' => $ADMIN . '?ADD=999998', 'icon' => $admin_icon, 'text' => _QXZ("Admin")]
                                ];
                                
                                // Add QC menu if authorized
                                if (($SSqc_features_active == '1') && ($qc_auth == '1')) {
                                    array_splice($menuItems, 3, 0, [['url' => $ADMIN . '?ADD=100000000000000', 'icon' => $qc_icon, 'text' => _QXZ("Quality Control")]]);
                                }
                                
                                foreach ($menuItems as $item) {
                                    ?>
                                    <div class="nav-item">
                                        <a href="<?php echo htmlspecialchars($item['url']); ?>" class="nav-link">
                                            <span class="nav-icon"><?php echo $item['icon']; ?></span>
                                            <span><?php echo $item['text']; ?></span>
                                        </a>
                                    </div>
                                    <?php
                                }
                            }
                            // Limited access menu
                            else {
                                ?>
                                <div style="flex-grow: 1;"></div>
                                <?php
                                if ($reports_only_user > 0) {
                                    ?>
                                    <div class="nav-item">
                                        <a href="<?php echo htmlspecialchars($ADMIN . '?ADD=999999'); ?>" class="nav-link active">
                                            <?php echo _QXZ("Reports"); ?>
                                        </a>
                                    </div>
                                    <?php
                                } else {
                                    if (($SSqc_features_active == '1') && ($qc_auth == '1')) {
                                        ?>
                                        <div class="nav-item">
                                            <a href="<?php echo htmlspecialchars($ADMIN . '?ADD=100000000000000'); ?>" class="nav-link active">
                                                <?php echo _QXZ("Quality Control"); ?>
                                            </a>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </nav>
                    </div>
                </header>
                <?php
            }
        }
    }
    // Mobile Header
    else if ($android_header) {
        ?>
        <header class="mobile-header">
            <div class="mobile-container">
                <a href="./admin_mobile.php" class="mobile-logo">
                    <img src="<?php echo htmlspecialchars($selected_small_logo); ?>" width="71" height="22" border="0" alt="System logo" class="logo-img">
                </a>
                <a href="admin_mobile.php?ADD=999990" class="mobile-nav-link">
                    <span><?php echo $admin_icon; ?></span>
                    <span><?php echo _QXZ("Admin"); ?></span>
                </a>
            </div>
        </header>
        <?php
    }
    // Full Header
    else {
        ?>
        <header class="admin-header">
            <div class="header-container">
                <div class="logo-container">
                    <a href="<?php echo htmlspecialchars($ADMIN); ?>" class="logo-link">
                        <img src="<?php echo htmlspecialchars($selected_small_logo); ?>" width="71" height="22" border="0" alt="System logo" class="logo-img">
                    </a>
                </div>
                
                <nav class="nav-menu">
                    <?php
                    // Full access menu
                    if (($reports_only_user < 1) && ($qc_only_user < 1)) {
                        $menuItems = [
                            ['url' => $ADMIN . '?ADD=999999', 'icon' => $reports_icon, 'text' => _QXZ("Reports")],
                            ['url' => $ADMIN . '?ADD=0A', 'icon' => $users_icon, 'text' => _QXZ("Users")],
                            ['url' => $ADMIN . '?ADD=10', 'icon' => $campaigns_icon, 'text' => _QXZ("Campaigns")],
                            ['url' => $ADMIN . '?ADD=100', 'icon' => $lists_icon, 'text' => _QXZ("Lists")],
                            ['url' => $ADMIN . '?ADD=1000000', 'icon' => $scripts_icon, 'text' => _QXZ("Scripts")],
                            ['url' => $ADMIN . '?ADD=10000000', 'icon' => $filters_icon, 'text' => _QXZ("Filters")],
                            ['url' => $ADMIN . '?ADD=1001', 'icon' => $inbound_icon, 'text' => _QXZ("Inbound")],
                            ['url' => $ADMIN . '?ADD=100000', 'icon' => $usergroups_icon, 'text' => _QXZ("User Groups")],
                            ['url' => $ADMIN . '?ADD=10000', 'icon' => $remoteagents_icon, 'text' => _QXZ("Remote Agents")],
                            ['url' => $ADMIN . '?ADD=999998', 'icon' => $admin_icon, 'text' => _QXZ("Admin")]
                        ];
                        
                        // Add QC menu if authorized
                        if (($SSqc_features_active == '1') && ($qc_auth == '1')) {
                            array_splice($menuItems, 3, 0, [['url' => $ADMIN . '?ADD=100000000000000', 'icon' => $qc_icon, 'text' => _QXZ("Quality Control")]]);
                        }
                        
                        foreach ($menuItems as $item) {
                            ?>
                            <div class="nav-item">
                                <a href="<?php echo htmlspecialchars($item['url']); ?>" class="nav-link">
                                    <span class="nav-icon"><?php echo $item['icon']; ?></span>
                                    <span><?php echo $item['text']; ?></span>
                                </a>
                            </div>
                            <?php
                        }
                    }
                    // Limited access menu
                    else {
                        ?>
                        <div style="flex-grow: 1;"></div>
                        <?php
                        if ($reports_only_user > 0) {
                            ?>
                            <div class="nav-item">
                                <a href="<?php echo htmlspecialchars($ADMIN . '?ADD=999999'); ?>" class="nav-link active">
                                    <?php echo _QXZ("Reports"); ?>
                                </a>
                            </div>
                            <?php
                        } else {
                            if (($SSqc_features_active == '1') && ($qc_auth == '1')) {
                                ?>
                                <div class="nav-item">
                                    <a href="<?php echo htmlspecialchars($ADMIN . '?ADD=100000000000000'); ?>" class="nav-link active">
                                        <?php echo _QXZ("Quality Control"); ?>
                                    </a>
                                </div>
                                <?php
                            }
                        }
                    }
                    ?>
                </nav>
            </div>
        </header>
        <?php
    }
    ?>

    <script language="Javascript">
    var field_name = '';
    var user = '<?php echo $PHP_AUTH_USER; ?>';
    var epoch = '<?php echo date("U"); ?>';

    <?php if ($TCedit_javascript > 0) { ?>
    function run_submit() {
        calculate_hours();
        var go_submit = document.getElementById("go_submit");
        if (go_submit.disabled == false) {
            document.edit_log.submit();
        }
    }

    function calculate_hours() {
        var now_epoch = '<?php echo $StarTtimE ?>';
        var local_gmt_sec = '<?php echo $local_gmt_sec ?>';
        var local_gmt_sec = (local_gmt_sec * 1);
        var i=0;
        var total_percent=0;
        var SPANlogin_time = document.getElementById("LOGINlogin_time");
        var LI_date = document.getElementById("LOGINbegin_date");
        var LO_date = document.getElementById("LOGOUTbegin_date");
        var LI_datetime = LI_date.value;
        var LO_datetime = LO_date.value;
        var LI_datetime_array=LI_datetime.split(" ");
        var LI_date_array=LI_datetime_array[0].split("-");
        var LI_time_array=LI_datetime_array[1].split(":");
        var LO_datetime_array=LO_datetime.split(" ");
        var LO_date_array=LO_datetime_array[0].split("-");
        var LO_time_array=LO_datetime_array[1].split(":");

        var LI_date_epoch = Date.UTC(LI_date_array[0], (LI_date_array[1]-1), LI_date_array[2], LI_time_array[0], LI_time_array[1], LI_time_array[2]);
        var LO_date_epoch = Date.UTC(LO_date_array[0], (LO_date_array[1]-1), LO_date_array[2], LO_time_array[0], LO_time_array[1], LO_time_array[2]);
        var temp_LI_epoch = ( (LI_date_epoch / 1000 ) + local_gmt_sec);
        var temp_LO_epoch = ( (LO_date_epoch / 1000 ) + local_gmt_sec);
        var epoch_diff = (temp_LO_epoch - temp_LI_epoch);
        var temp_diff = epoch_diff;

        document.getElementById("login_time").innerHTML = "ERROR, Please check date fields";

        var go_submit = document.getElementById("go_submit");
        go_submit.disabled = true;
        
        if ( (epoch_diff < 86401) && (epoch_diff > 0) && (temp_LI_epoch < now_epoch) && (temp_LO_epoch < now_epoch) ) {
            go_submit.disabled = false;

            hours = Math.floor(temp_diff / (60 * 60)); 
            temp_diff -= hours * (60 * 60);

            mins = Math.floor(temp_diff / 60); 
            temp_diff -= mins * 60;
            if (mins < 10) {mins = "0" + mins;}

            secs = Math.floor(temp_diff); 
            temp_diff -= secs;

            document.getElementById("login_time").innerHTML = hours + ":" + mins;

            var form_LI_epoch = document.getElementById("LOGINepoch");
            var form_LO_epoch = document.getElementById("LOGOUTepoch");
            form_LI_epoch.value = temp_LI_epoch;
            form_LO_epoch.value = temp_LO_epoch;
        }
    }
    <?php } ?>

    <?php if ( ( ($ADD==34) or ($ADD==31) or ($ADD==49) ) and ($SUB==29) and ($LOGmodify_campaigns==1) and ( (preg_match("/$campaign_id/i", $LOGallowed_campaigns)) or (preg_match("/ALL\-CAMPAIGNS/i",$LOGallowed_campaigns)) ) ) { ?>
    function mod_mix_status(stage,vcl_id,entry) {
        if (stage=="ALL") {
            var count=0;
            var ROnew_statuses = document.getElementById("ROstatus_X_" + vcl_id);

            while (count < entry) {
                var old_statuses = document.getElementById("status_" + count + "_" + vcl_id);
                var ROold_statuses = document.getElementById("ROstatus_" + count + "_" + vcl_id);

                old_statuses.value = ROnew_statuses.value;
                ROold_statuses.value = ROnew_statuses.value;
                count++;
            }
        } else {
            if (stage=="EMPTY") {
                var count=0;
                var ROnew_statuses = document.getElementById("ROstatus_X_" + vcl_id);

                while (count < entry) {
                    var old_statuses = document.getElementById("status_" + count + "_" + vcl_id);
                    var ROold_statuses = document.getElementById("status_" + count + "_" + vcl_id);
                    
                    if (ROold_statuses.value.length < 3) {
                        old_statuses.value = ROnew_statuses.value;
                        ROold_statuses.value = ROnew_statuses.value;
                    }
                    count++;
                }
            } else {
                var mod_status = document.getElementById("dial_status_" + entry + "_" + vcl_id);
                if (mod_status.value.length < 1) {
                    alert("You must select a status first");
                } else {
                    var old_statuses = document.getElementById("status_" + entry + "_" + vcl_id);
                    var ROold_statuses = document.getElementById("ROstatus_" + entry + "_" + vcl_id);
                    var MODstatus = new RegExp(" " + mod_status.value + " ","g");
                    if (stage=="ADD") {
                        if (old_statuses.value.match(MODstatus)) {
                            alert("The status " + mod_status.value + " is already present");
                        } else {
                            var new_statuses = " " + mod_status.value + "" + old_statuses.value;
                            old_statuses.value = new_statuses;
                            ROold_statuses.value = new_statuses;
                            mod_status.value = "";
                        }
                    }
                    if (stage=="REMOVE") {
                        var MODstatus = new RegExp(" " + mod_status.value + " ","g");
                        old_statuses.value = old_statuses.value.replace(MODstatus, " ");
                        ROold_statuses.value = ROold_statuses.value.replace(MODstatus, " ");
                    }
                }
            }
        }
    }

    function mod_mix_percent(vcl_id,entries) {
        var i=0;
        var total_percent=0;
        var percent_diff='';
        while(i < entries) {
            var mod_percent_field = document.getElementById("percentage_" + i + "_" + vcl_id);
            temp_percent = mod_percent_field.value * 1;
            total_percent = (total_percent + temp_percent);
            i++;
        }

        var mod_diff_percent = document.getElementById("PCT_DIFF_" + vcl_id);
        percent_diff = (total_percent - 100);
        if (percent_diff > 0) {
            percent_diff = '+' + percent_diff;
        }
        var mix_list_submit = document.getElementById("submit_" + vcl_id);
        if ( (percent_diff > 0) || (percent_diff < 0) ) {
            mix_list_submit.disabled = true;
            document.getElementById("ERROR_" + vcl_id).innerHTML = "<font color=red><B>The Difference % must be 0</B></font>";
        } else {
            mix_list_submit.disabled = false;
            document.getElementById("ERROR_" + vcl_id).innerHTML = "";
        }

        mod_diff_percent.value = percent_diff;
    }

    function submit_mix(vcl_id,entries) {
        var h=1;
        var j=1;
        var list_mix_container='';
        var mod_list_mix_container_field = document.getElementById("list_mix_container_" + vcl_id);
        while(h < 41) {
            var i=0;
            while(i < entries) {
                var mod_list_id_field = document.getElementById("list_id_" + i + "_" + vcl_id);
                var mod_priority_field = document.getElementById("priority_" + i + "_" + vcl_id);
                var mod_percent_field = document.getElementById("percentage_" + i + "_" + vcl_id);
                var mod_statuses_field = document.getElementById("status_" + i + "_" + vcl_id);
                if (mod_priority_field.value==h) {
                    list_mix_container = list_mix_container + mod_list_id_field.value + "|" + j + "|" + mod_percent_field.value + "|" + mod_statuses_field.value + "|:";
                    j++;
                }
                i++;
            }
            h++;
        }
        mod_list_mix_container_field.value = list_mix_container;
        var form_to_submit = document.getElementById("" + vcl_id);
        form_to_submit.submit();
    }
    <?php } ?>

    <?php if ( ( ($ADD==34) or ($ADD==31) or ($ADD==44) or ($ADD==41) ) and ($LOGmodify_campaigns==1) and ( (preg_match("/$campaign_id/i", $LOGallowed_campaigns)) or (preg_match("/ALL\-CAMPAIGNS/i",$LOGallowed_campaigns)) ) ) { ?>
    function ConfirmListStatusChange(system_setting, listForm) {
        if (!system_setting) {
            listForm.submit();
            return false;
        }

        var previous_list_statuses=document.getElementById('last_list_statuses').value;
        var new_list_statuses="";

        var lists = document.getElementsByName('list_active_change[]');
        var no_selected=0;
        for (var i=0; i<lists.length; i++) {
            new_list_statuses+=lists[i].value+"|";
            if (lists[i].checked) {
                new_list_statuses+="Y|";
            } else {
                new_list_statuses+="N|";
            }
        }

        if (previous_list_statuses==new_list_statuses) {
            listForm.submit();
            return false;
        } else {
            var prev_array=previous_list_statuses.split("|");
            var new_array=new_list_statuses.split("|");
            if (prev_array.length!=new_array.length) {alert("List error. Reload the page and try again."); return false;}

            var altered_lists="";
            for(i=0; i<prev_array.length; i+=2) {
                prev_status=prev_array[(i+1)];
                new_status=new_array[(i+1)];
                if (prev_status!=new_status) {
                    altered_lists+=" - List "+new_array[i]+": "+prev_status+" => "+new_status+"\n";
                }
            }

            var proceed=confirm("You have changed the active status of the following lists:\n\n"+altered_lists+"\nWould you like to proceed with committing the changes?");
            if (proceed) {
                listForm.submit();
            }
        }
    }
    <?php } ?>

    <?php if ( ( ($ADD==31) or ($ADD==41) ) and ($LOGmodify_campaigns==1) and ( (preg_match("/$campaign_id/i", $LOGallowed_campaigns)) or (preg_match("/ALL\-CAMPAIGNS/i",$LOGallowed_campaigns)) ) ) { ?>
    function AgentCallHangupRouteChange(ACHR_new_value) {
        var ACHR_list = document.getElementById("agent_hangup_route");
        var ACHR_route = ACHR_list.value;
        var ACHR_value = document.getElementById("agent_hangup_value");
        var ACHR_title = document.getElementById("agent_hangup_value_title");
        var ACHR_chooser = document.getElementById("agent_hangup_value_chooser");

        if (ACHR_route=='HANGUP') {
            ACHR_title.innerHTML = '-<?php echo _QXZ("no value required") ?>-';
            ACHR_chooser.innerHTML = '';
            ACHR_value.value='';
        }
        if (ACHR_route=='MESSAGE') {
            ACHR_title.innerHTML = '<?php echo _QXZ("Agent Hangup Message") ?>';
            ACHR_chooser.innerHTML = " <a href=\"javascript:launch_chooser('agent_hangup_value','date');\"><?php echo _QXZ("audio chooser") ?></a> ";
            ACHR_value.value='';
        }
        if (ACHR_route=='EXTENSION') {
            ACHR_title.innerHTML = '<?php echo _QXZ("Agent Hangup Dialplan Extension") ?>';
            ACHR_chooser.innerHTML = '';
            ACHR_value.value='';
        }
        if (ACHR_route=='IN_GROUP') {
            ACHR_title.innerHTML = '<?php echo _QXZ("Agent Hangup In-Group") ?>';
            ACHR_chooser.innerHTML = " <a href=\"javascript:launch_ingroup_chooser('agent_hangup_value','group_id');\"><?php echo _QXZ("in-group chooser") ?></a> ";
            ACHR_value.value='';
        }
        if (ACHR_route=='CALLMENU') {
            ACHR_title.innerHTML = '<?php echo _QXZ("Agent Hangup Call Menu") ?>';
            ACHR_chooser.innerHTML = " <a href=\"javascript:launch_callmenu_chooser('agent_hangup_value','menu_id');\"><?php echo _QXZ("call menu chooser") ?></a> ";
            ACHR_value.value='';
        }
    }
    <?php } ?>

    var weak = new Image();
    weak.src = "images/weak.png";
    var medium = new Image();
    medium.src = "images/medium.png";
    var strong = new Image();
    strong.src = "images/strong.png";

    function pwdChanged(pwd_field_str, pwd_img_str, pwd_len_field, pwd_len_min) {
        var pwd_field = document.getElementById(pwd_field_str);
        var pwd_field_value = pwd_field.value;
        var pwd_img = document.getElementById(pwd_img_str);
        var pwd_len = pwd_field_value.length

        var strong_regex = new RegExp( "^(?=.{20,})(?=.*[a-zA-Z])(?=.*[0-9])", "g" );
        var medium_regex = new RegExp( "^(?=.{10,})(?=.*[a-zA-Z])(?=.*[0-9])", "g" );

        if (strong_regex.test(pwd_field.value) ) {
            if (pwd_img.src != strong.src) {pwd_img.src = strong.src;}
        } else if (medium_regex.test( pwd_field.value) ) {
            if (pwd_img.src != medium.src) {pwd_img.src = medium.src;}
        } else {
            if (pwd_img.src != weak.src) {pwd_img.src = weak.src;}
        }
        if ( (pwd_len_min > 0) && (pwd_len_min > pwd_len) ) {
            document.getElementById(pwd_len_field).innerHTML = "<font color=red><b>" + pwd_len + "</b></font>";
        } else {
            document.getElementById(pwd_len_field).innerHTML = "<font color=black><b>" + pwd_len + "</b></font>";
        }
    }

    function openNewWindow(url) {
        window.open (url,"",'width=620,height=300,scrollbars=yes,menubar=yes,address=yes');
    }

    function scriptInsertField() {
        openField = '--A--';
        closeField = '--B--';
        var textBox = document.scriptForm.script_text;
        var scriptIndex = document.getElementById("selectedField").selectedIndex;
        var insValue =  document.getElementById('selectedField').options[scriptIndex].value;
        if (document.selection) {
            textBox = document.scriptForm.script_text;
            insValue = document.scriptForm.selectedField.options[document.scriptForm.selectedField.selectedIndex].text;
            textBox.focus();
            sel = document.selection.createRange();
            sel.text = openField + insValue + closeField;
        } else if (textBox.selectionStart || textBox.selectionStart == 0) {
            var startPos = textBox.selectionStart;
            var endPos = textBox.selectionEnd;
            textBox.value = textBox.value.substring(0, startPos)
            + openField + insValue + closeField
            + textBox.value.substring(endPos, textBox.value.length);
        } else {
            textBox.value += openField + insValue + closeField;
        }
    }

    <?php if ( ($SSadmin_modify_refresh > 1) and (preg_match("/^3|^4/",$ADD)) ) { ?>
    var ar_seconds=<?php echo "$SSadmin_modify_refresh;"; ?>;
    function modify_refresh_display() {
        if (ar_seconds > 0) {
            ar_seconds = (ar_seconds - 1);
            document.getElementById("refresh_countdown").innerHTML = "<font color=black> screen refresh in: " + ar_seconds + " seconds</font>";
            setTimeout("modify_refresh_display()",1000);
        }
    }
    <?php } ?>

    <?php if ( ($ADD==1) or ($ADD=="1A") ) { ?>
    function user_auto() {
        var user_toggle = document.getElementById("user_toggle");
        var user_field = document.getElementById("user");
        if (user_toggle.value < 1) {
            user_field.value = 'AUTOGENERATEZZZ';
            user_field.disabled = true;
            user_toggle.value = 1;
        } else {
            user_field.value = '';
            user_field.disabled = false;
            user_toggle.value = 0;
        }
    }

    function user_submit() {
        var user_field = document.getElementById("user");
        user_field.disabled = false;
        document.userform.submit();
    }
    <?php } ?>

    <?php if ( ($ADD==131111111) or ($ADD==331111111) or ($ADD==431111111) ) { ?>
    function shift_time() {
        var start_time = document.getElementById("shift_start_time");
        var end_time = document.getElementById("shift_end_time");
        var length = document.getElementById("shift_length");

        var st_value = start_time.value;
        var et_value = end_time.value;
        while (st_value.length < 4) {st_value = "0" + st_value;}
        while (et_value.length < 4) {et_value = "0" + et_value;}
        var st_hour=st_value.substring(0,2);
        var st_min=st_value.substring(2,4);
        var et_hour=et_value.substring(0,2);
        var et_min=et_value.substring(2,4);
        if (st_hour > 23) {st_hour = 23;}
        if (et_hour > 23) {et_hour = 23;}
        if (st_min > 59) {st_min = 59;}
        if (et_min > 59) {et_min = 59;}
        start_time.value = st_hour + "" + st_min;
        end_time.value = et_hour + "" + et_min;

        var start_time_hour=start_time.value.substring(0,2);
        var start_time_min=start_time.value.substring(2,4);
        var end_time_hour=end_time.value.substring(0,2);
        var end_time_min=end_time.value.substring(2,4);
        start_time_hour=(start_time_hour * 1);
        start_time_min=(start_time_min * 1);
        end_time_hour=(end_time_hour * 1);
        end_time_min=(end_time_min * 1);

        if (start_time.value == end_time.value) {
            var shift_length = '24:00';
        } else {
            if ( (start_time_hour > end_time_hour) || ( (start_time_hour == end_time_hour) && (start_time_min > end_time_min) ) ) {
                var shift_hour = ( (24 - start_time_hour) + end_time_hour);
                var shift_minute = ( (60 - start_time_min) + end_time_min);
                if (shift_minute >= 60) {
                    shift_minute = (shift_minute - 60);
                } else {
                    shift_hour = (shift_hour - 1);
                }
            } else {
                var shift_hour = (end_time_hour - start_time_hour);
                var shift_minute = (end_time_min - start_time_min);
            }
            if (shift_minute < 0) {
                shift_minute = (shift_minute + 60);
                shift_hour = (shift_hour - 1);
            }

            if (shift_hour < 10) {shift_hour = '0' + shift_hour}
            if (shift_minute < 10) {shift_minute = '0' + shift_minute}
            var shift_length = shift_hour + ':' + shift_minute;
        }

        length.value = shift_length;
    }
    <?php } ?>

    <?php if ( ($ADD==3111) or ($ADD==4111) or ($ADD==5111) or ($ADD==3811) or ($ADD==4811) or ($ADD==5811) or ($ADD==3911) or ($ADD==4911) or ($ADD==5911) or ($ADD==31) or ($ADD==34) or ($ADD==202) or ($ADD==396111111111) or ($ADD==496111111111) ) { ?>
    function FORM_selectall(temp_count,temp_fields,temp_option,temp_span) {
        var fields_array=temp_fields.split('|');
        var inc=0;
        while (temp_count >= inc) {
            if (fields_array[inc].length > 0) {
                if (temp_option == 'off') {
                    document.getElementById(fields_array[inc]).checked=false;
                } else {
                    document.getElementById(fields_array[inc]).checked=true;
                }
            }
            inc++;
        }
        if (temp_option == 'off') {
            document.getElementById(temp_span).innerHTML = "<a href=\"#\" onclick=\"FORM_selectall('" + temp_count + "','" + temp_fields + "','on','" + temp_span + "');return false;\"><font size=1><?php echo _QXZ("select all"); ?></font></a>";
        } else {
            document.getElementById(temp_span).innerHTML = "<a href=\"#\" onclick=\"FORM_selectall('" + temp_count + "','" + temp_fields + "','off','" + temp_span + "');return false;\"><font size=1><?php echo _QXZ("deselect all"); ?></font></a>";
        }
    }
    <?php } ?>

    mouseY=0;
    function getMousePos(event) {
        mouseY=event.pageY;
    }
    document.addEventListener("click", getMousePos);

    var chooser_field='';
    var chooser_field_td='';
    var chooser_type='';

    function launch_chooser(fieldname,stage) {
        var h = window.innerHeight;		
        var vposition=mouseY;

        var audiolistURL = "./non_agent_api.php";
        var audiolistQuery = "source=admin&function=sounds_list&user=" + user + "&pass=" + pass + "&format=selectframe&stage=" + stage + "&comments=" + fieldname;
        var Iframe_content = '<IFRAME SRC="' + audiolistURL + '?' + audiolistQuery + '"  style="width:740;height:440;background-color:white;" scrolling="NO" frameborder="0" allowtransparency="true" id="audio_chooser_frame' + epoch + '" name="audio_chooser_frame" width="740" height="460" STYLE="z-index:2"> </IFRAME>';

        document.getElementById("audio_chooser_span").style.position = "absolute";
        document.getElementById("audio_chooser_span").style.left = "220px";
        document.getElementById("audio_chooser_span").style.top = vposition + "px";
        document.getElementById("audio_chooser_span").style.visibility = 'visible';
        document.getElementById("audio_chooser_span").innerHTML = Iframe_content;
    }

    function launch_moh_chooser(fieldname,stage) {
        var h = window.innerHeight;		
        var vposition=mouseY;

        var audiolistURL = "./non_agent_api.php";
        var audiolistQuery = "source=admin&function=moh_list&user=" + user + "&pass=" + pass + "&format=selectframe&stage=" + stage + "&comments=" + fieldname;
        var Iframe_content = '<IFRAME SRC="' + audiolistURL + '?' + audiolistQuery + '"  style="width:740;height:440;background-color:white;" scrolling="NO" frameborder="0" allowtransparency="true" id="audio_chooser_frame' + epoch + '" name="audio_chooser_frame" width="740" height="460" STYLE="z-index:2"> </IFRAME>';

        document.getElementById("audio_chooser_span").style.position = "absolute";
        document.getElementById("audio_chooser_span").style.left = "220px";
        document.getElementById("audio_chooser_span").style.top = vposition + "px";
        document.getElementById("audio_chooser_span").style.visibility = 'visible';
        document.getElementById("audio_chooser_span").innerHTML = Iframe_content;
    }

    function launch_ingroup_chooser(fieldname,stage) {
        var h = window.innerHeight;		
        var vposition=mouseY;

        var apilistURL = "./non_agent_api.php";
        var apilistQuery = "source=admin&function=ingroup_list&user=" + user + "&pass=" + pass + "&format=selectframe&stage=" + stage + "&comments=" + fieldname;
        var Iframe_content = '<IFRAME SRC="' + apilistURL + '?' + apilistQuery + '"  style="width:740;height:440;background-color:white;" scrolling="NO" frameborder="0" allowtransparency="true" id="audio_chooser_frame' + epoch + '" name="audio_chooser_frame" width="740" height="460" STYLE="z-index:2"> </IFRAME>';

        document.getElementById("audio_chooser_span").style.position = "absolute";
        document.getElementById("audio_chooser_span").style.left = "220px";
        document.getElementById("audio_chooser_span").style.top = vposition + "px";
        document.getElementById("audio_chooser_span").style.visibility = 'visible';
        document.getElementById("audio_chooser_span").innerHTML = Iframe_content;
    }

    function launch_callmenu_chooser(fieldname,stage) {
        var h = window.innerHeight;		
        var vposition=mouseY;

        var apilistURL = "./non_agent_api.php";
        var apilistQuery = "source=admin&function=callmenu_list&user=" + user + "&pass=" + pass + "&format=selectframe&stage=" + stage + "&comments=" + fieldname;
        var Iframe_content = '<IFRAME SRC="' + apilistURL + '?' + apilistQuery + '"  style="width:740;height:440;background-color:white;" scrolling="NO" frameborder="0" allowtransparency="true" id="audio_chooser_frame' + epoch + '" name="audio_chooser_frame" width="740" height="460" STYLE="z-index:2"> </IFRAME>';

        document.getElementById("audio_chooser_span").style.position = "absolute";
        document.getElementById("audio_chooser_span").style.left = "220px";
        document.getElementById("audio_chooser_span").style.top = vposition + "px";
        document.getElementById("audio_chooser_span").style.visibility = 'visible';
        document.getElementById("audio_chooser_span").innerHTML = Iframe_content;
    }

    function launch_container_chooser(fieldname,stage,type) {
        var h = window.innerHeight;		
        var vposition=mouseY;

        var apilistURL = "./non_agent_api.php";
        var apilistQuery = "source=admin&function=container_list&user=" + user + "&pass=" + pass + "&format=selectframe&stage=" + stage + "&comments=" + fieldname + "&type=" + type;
        var Iframe_content = '<IFRAME SRC="' + apilistURL + '?' + apilistQuery + '"  style="width:740;height:440;background-color:white;" scrolling="NO" frameborder="0" allowtransparency="true" id="audio_chooser_frame' + epoch + '" name="audio_chooser_frame" width="740" height="460" STYLE="z-index:2"> </IFRAME>';

        document.getElementById("audio_chooser_span").style.position = "absolute";
        document.getElementById("audio_chooser_span").style.left = "220px";
        document.getElementById("audio_chooser_span").style.top = vposition + "px";
        document.getElementById("audio_chooser_span").style.visibility = 'visible';
        document.getElementById("audio_chooser_span").innerHTML = Iframe_content;
    }

    function launch_vm_chooser(fieldname,stage) {
        var h = window.innerHeight;		
        var vposition=mouseY;

        var audiolistURL = "./non_agent_api.php";
        var audiolistQuery = "source=admin&function=vm_list&user=" + user + "&pass=" + pass + "&format=selectframe&stage=" + stage + "&comments=" + fieldname;
        var Iframe_content = '<IFRAME SRC="' + audiolistURL + '?' + audiolistQuery + '"  style="width:740;height:440;background-color:white;" scrolling="NO" frameborder="0" allowtransparency="true" id="audio_chooser_frame' + epoch + '" name="audio_chooser_frame" width="740" height="460" STYLE="z-index:2"> </IFRAME>';

        document.getElementById("audio_chooser_span").style.position = "absolute";
        document.getElementById("audio_chooser_span").style.left = "220px";
        document.getElementById("audio_chooser_span").style.top = vposition + "px";
        document.getElementById("audio_chooser_span").style.visibility = 'visible';
        document.getElementById("audio_chooser_span").innerHTML = Iframe_content;
    }

    function launch_color_chooser(fieldname,stage,type) {
        var h = window.innerHeight;		
        var vposition=mouseY;
        chooser_field = fieldname;
        chooser_field_td = fieldname + '_td';
        chooser_type = type;
        
        <?php
        $color_chooser_output .= " &nbsp; <a href=\\\"javascript:close_chooser();\\\"><font size=1 face='Arial,Helvetica'>"._QXZ("close frame")."</font></a> &nbsp; <BR>";
        $color_chooser_output .= "<div id='select_color_frame' style=\\\"height:400px;width:400px;overflow:scroll;background-color:white;\\\">";
        $color_chooser_output .= '<table border=0 cellpadding=2 cellspacing=2 width=400 bgcolor=white>';
        $HTMLcolorsARY = explode('|',$HTMLcolors);
        $HTMLcolorsARYcount = count($HTMLcolorsARY);
        $HTMLct=0;
        while ($HTMLcolorsARYcount > $HTMLct) {
            $HTMLcolorsLINE = explode(',',$HTMLcolorsARY[$HTMLct]);
            if (preg_match("/1$|3$|5$|7$|9$/i", $HTMLct)) {
                $bgcolor='#E6E6E6'; 
            } else {
                $bgcolor='#F6F6F6';
            }
            $color_chooser_output .= "<tr bgcolor=\\\"$bgcolor\\\"><td>$HTMLcolorsLINE[0] </td><td><a href=\\\"javascript:choose_color('$HTMLcolorsLINE[1]');\\\"><font size=1 face='Arial,Helvetica'>#$HTMLcolorsLINE[1]</a> </td><td bgcolor='#$HTMLcolorsLINE[1]'> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </td></tr>";
            $HTMLct++;
        }
        $color_chooser_output .= '</table></div>';
        ?>

        var span_content = '<span id="color_chooser_frame' + epoch + '" name="color_chooser_frame" style="width:740;height:440;background-color:white;overflow:scroll;z-index:2;">' + "<?php  echo $color_chooser_output ?></span>";

        document.getElementById("audio_chooser_span").style.position = "absolute";
        document.getElementById("audio_chooser_span").style.left = "220px";
        document.getElementById("audio_chooser_span").style.top = vposition + "px";
        document.getElementById("audio_chooser_span").style.visibility = 'visible';
        document.getElementById("audio_chooser_span").style.backgroundcolor = 'white';
        document.getElementById("audio_chooser_span").innerHTML = span_content;
    }

    function choose_color(colorname) {
        if (colorname.length > 0) {
            if (chooser_type == '2') {
                document.getElementById(chooser_field).value = colorname;
                document.getElementById(chooser_field_td).style.backgroundColor = '#' + colorname;
            } else {
                document.getElementById(chooser_field).value = '#' + colorname;
                document.getElementById(chooser_field_td).style.backgroundColor = '#' + colorname;
            }
            close_chooser();
        }
    }

    function close_chooser() {
        document.getElementById("audio_chooser_span").style.visibility = 'hidden';
        document.getElementById("audio_chooser_span").innerHTML = '';
    }

    function play_browser_sound(temp_element,temp_volume) {
        var taskIndex = document.getElementById(temp_element).selectedIndex;
        var taskValue = document.getElementById(temp_element).options[taskIndex].value;
        var temp_selected_element = 'BAS_' + taskValue;
        if ( (taskValue != '---NONE---') && (taskValue != '---DISABLED---') && (taskValue != '') ) {
            var temp_audio = document.getElementById(temp_selected_element);
            var taskVolIndex = document.getElementById(temp_volume).selectedIndex;
            var taskVolValue = document.getElementById(temp_volume).options[taskVolIndex].value;
            var temp_js_volume = (taskVolValue * .01);
            temp_audio.volume = temp_js_volume;
            temp_audio.play();
        }
    }

    <?php
    // Get call menu list
    $stmt="SELECT menu_id,menu_name from vicidial_call_menu $whereLOGadmin_viewable_groupsSQL order by menu_id limit 10000;";
    $rslt=mysql_to_mysqli($stmt, $link);
    $menus_to_print = mysqli_num_rows($rslt);
    $call_menu_list='';
    $i=0;
    while ($i < $menus_to_print) {
        $row=mysqli_fetch_row($rslt);
        $call_menu_list .= "<option value=\"$row[0]\">$row[0] - $row[1]</option>";
        $i++;
    }

    // Dynamic route displays
    if ( ($ADD==3511) or ($ADD==2511) or ($ADD==2611) or ($ADD==4511) or ($ADD==5511) or ($ADD==3111) or ($ADD==2111) or ($ADD==2011) or ($ADD==4111) or ($ADD==5111) ) {
        $stmt="SELECT did_pattern,did_description,did_route from vicidial_inbound_dids where did_active='Y' $LOGadmin_viewable_groupsSQL order by did_pattern;";
        $rslt=mysql_to_mysqli($stmt, $link);
        $dids_to_print = mysqli_num_rows($rslt);
        $did_list='';
        $i=0;
        while ($i < $dids_to_print) {
            $row=mysqli_fetch_row($rslt);
            $did_list .= "<option value=\"$row[0]\">$row[0] - $row[1] - $row[2]</option>";
            $i++;
        }

        $stmt="SELECT group_id,group_name from vicidial_inbound_groups where active='Y' and group_id NOT LIKE \"AGENTDIRECT%\" $LOGadmin_viewable_groupsSQL order by group_id;";
        $rslt=mysql_to_mysqli($stmt, $link);
        $ingroups_to_print = mysqli_num_rows($rslt);
        $ingroup_list='';
        $i=0;
        while ($i < $ingroups_to_print) {
            $row=mysqli_fetch_row($rslt);
            $ingroup_list .= "<option value=\"$row[0]\">$row[0] - $row[1]</option>";
            $i++;
        }

        $stmt="SELECT campaign_id,campaign_name from vicidial_campaigns where active='Y' $LOGallowed_campaignsSQL order by campaign_id;";
        $rslt=mysql_to_mysqli($stmt, $link);
        $IGcampaigns_to_print = mysqli_num_rows($rslt);
        $IGcampaign_id_list='';
        $i=0;
        while ($i < $IGcampaigns_to_print) {
            $row=mysqli_fetch_row($rslt);
            $IGcampaign_id_list .= "<option value=\"$row[0]\">$row[0] - $row[1]</option>";
            $i++;
        }

        $IGhandle_method_list = '<option>CID</option><option>CIDLOOKUP</option><option>CIDLOOKUPRL</option><option>CIDLOOKUPRC</option><option>CIDLOOKUPALT</option><option>CIDLOOKUPRLALT</option><option>CIDLOOKUPRCALT</option><option>CIDLOOKUPADDR3</option><option>CIDLOOKUPRLADDR3</option><option>CIDLOOKUPRCADDR3</option><option>CIDLOOKUPALTADDR3</option><option>CIDLOOKUPRLALTADDR3</option><option>CIDLOOKUPRCALTADDR3</option><option>ANI</option><option>ANILOOKUP</option><option>ANILOOKUPRL</option><option>VIDPROMPT</option><option>VIDPROMPTLOOKUP</option><option>VIDPROMPTLOOKUPRL</option><option>VIDPROMPTLOOKUPRC</option><option>VIDPROMPTSPECIALLOOKUP</option><option>VIDPROMPTSPECIALLOOKUPRL</option><option>VIDPROMPTSPECIALLOOKUPRC</option><option>CLOSER</option><option>3DIGITID</option><option>4DIGITID</option><option>5DIGITID</option><option>10DIGITID</option><option>CIDLOOKUPOWNERCUSTOMX</option><option>CIDLOOKUPRLOWNERCUSTOMX</option><option>CIDLOOKUPRCOWNERCUSTOMX</option><option>CIDLOOKUPALTOWNERCUSTOMX</option><option>CIDLOOKUPRLALTOWNERCUSTOMX</option><option>CIDLOOKUPRCALTOWNERCUSTOMX</option><option>CIDLOOKUPADDR3OWNERCUSTOMX</option><option>CIDLOOKUPRLADDR3OWNERCUSTOMX</option><option>CIDLOOKUPRCADDR3OWNERCUSTOMX</option><option>CIDLOOKUPALTADDR3OWNERCUSTOMX</option><option>CIDLOOKUPRLALTADDR3OWNERCUSTOMX</option><option>CIDLOOKUPRCALTADDR3OWNERCUSTOMX</option>';

        $IGsearch_method_list = '<option value=\\"LB\\">LB - '._QXZ("Load Balanced").'</option><option value=\\"LO\\">LO - '._QXZ("Load Balanced Overflow").'</option><option value=\\"SO\\">SO - '._QXZ("Server Only").'</option>';

        $stmt="SELECT login,server_ip,extension,dialplan_number from phones where active='Y' $LOGadmin_viewable_groupsSQL order by login,server_ip;";
        $rslt=mysql_to_mysqli($stmt, $link);
        $phones_to_print = mysqli_num_rows($rslt);
        $phone_list='';
        $i=0;
        while ($i < $phones_to_print) {
            $row=mysqli_fetch_row($rslt);
            $phone_list .= "<option value=\"$row[0]\">$row[0] - $row[1] - $row[2] - $row[3]</option>";
            $i++;
        }
    }

    if ( ($ADD==3511) or ($ADD==2511) or ($ADD==2611) or ($ADD==4511) or ($ADD==5511) ) {
    ?>
    function call_menu_option(option,route,value,value_context,chooser_height) {
        var call_menu_list = '<?php echo $call_menu_list ?>';
        var ingroup_list = '<?php echo $ingroup_list ?>';
        var IGcampaign_id_list = '<?php echo $IGcampaign_id_list ?>';
        var IGhandle_method_list = '<?php echo $IGhandle_method_list ?>';
        var IGsearch_method_list = "<?php echo $IGsearch_method_list ?>";
        var did_list = '<?php echo $did_list ?>';
        var phone_list = '<?php echo $phone_list ?>';
        var selected_value = '';
        var selected_context = '';
        var new_content = '';

        var select_list = document.getElementById("option_route_" + option);
        var selected_route = select_list.value;
        var span_to_update = document.getElementById("option_value_value_context_" + option);

        if (selected_route=='CALLMENU') {
            if (route == selected_route) {
                selected_value = '<option SELECTED value="' + value + '">' + value + "</option>\n";
            } else {
                value='';
            }
            new_content = '<span name=option_route_link_' + option + ' id=option_route_link_' + option + "><a href=\"<?php echo $ADMIN ?>?ADD=3511&menu_id=" + value + "\"><?php echo _QXZ("Call Menu"); ?>: </a></span><select size=1 name=option_route_value_" + option + " id=option_route_value_" + option + " onChange=\"call_menu_link('" + option + "','CALLMENU');\">" + call_menu_list + "\n" + selected_value + '</select>';
        }
        if (selected_route=='INGROUP') {
            if (value_context.length < 10) {
                value_context = 'CID,LB,998,TESTCAMP,1,,,,,';
            }
            var value_context_split = value_context.split(",");
            var IGhandle_method = value_context_split[0];
            var IGsearch_method = value_context_split[1];
            var IGlist_id = value_context_split[2];
            var IGcampaign_id = value_context_split[3];
            var IGphone_code = value_context_split[4];
            var IGvid_enter_filename = value_context_split[5];
            var IGvid_id_number_filename = value_context_split[6];
            var IGvid_confirm_filename = value_context_split[7];
            var IGvid_validate_digits = value_context_split[8];
            var IGvid_container = value_context_split[9];

            if (route == selected_route) {
                selected_value = '<option SELECTED>' + value + '</option>';
            }

            new_content = '<input type=hidden name=option_route_value_context_' + option + ' id=option_route_value_context_' + option + ' value="' + selected_value + '">';
            new_content = new_content + '<span name="option_route_link_' + option + '" id="option_route_link_' + option + '">';
            new_content = new_content + "<a href=\"<?php echo $ADMIN ?>?ADD=3111&group_id=" + value + "\"><?php echo _QXZ("In-Group"); ?>:</a> </span>";
            new_content = new_content + '<select size=1 name=option_route_value_' + option + ' id=option_route_value_' + option + " onChange=\"call_menu_link('" + option + "','INGROUP');\">";
            new_content = new_content + '' + ingroup_list + "\n" + selected_value + '<option>DYNAMIC_INGROUP_VAR</option></select>';
            new_content = new_content + " &nbsp; <?php echo _QXZ("Handle Method"); ?>: <select size=1 name=IGhandle_method_" + option + ' id=IGhandle_method_' + option + '>';
            new_content = new_content + '' + '<option SELECTED>' + IGhandle_method + '</option>' + IGhandle_method_list + '</select>' + "\n";
            new_content = new_content + ' &nbsp; <IMG SRC=\"help.png\" onClick=\"FillAndShowHelpDiv(event, \'call_menu-ingroup_settings\')\" WIDTH=20 HEIGHT=20 BORDER=0 ALT="HELP" ALIGN=TOP>';
            new_content = new_content + "<BR><?php echo _QXZ("Search Method"); ?>: <select size=1 name=IGsearch_method_" + option + ' id=IGsearch_method_' + option + '>';
            new_content = new_content + '' + IGsearch_method_list + "\n" + '<option SELECTED>' + IGsearch_method + '</select>';
            new_content = new_content + " &nbsp; <?php echo _QXZ("List ID"); ?>: <input type=text size=5 maxlength=14 name=IGlist_id_" + option + ' id=IGlist_id_' + option + ' value="' + IGlist_id + '">';
            new_content = new_content + "<BR><?php echo _QXZ("Campaign ID"); ?>: <select size=1 name=IGcampaign_id_" + option + ' id=IGcampaign_id_' + option + '>';
            new_content = new_content + '' + IGcampaign_id_list + "\n" + '<option SELECTED>' + IGcampaign_id + '</select>';
            new_content = new_content + " &nbsp; <?php echo _QXZ("Phone Code"); ?>: <input type=text size=5 maxlength=14 name=IGphone_code_" + option + ' id=IGphone_code_' + option + ' value="' + IGphone_code + '">';
            new_content = new_content + "<BR> &nbsp; <?php echo _QXZ("VID Enter Filename"); ?>: <input type=text name=IGvid_enter_filename_" + option + " id=IGvid_enter_filename_" + option + " size=40 maxlength=255 value=\"" + IGvid_enter_filename + "\"> <a href=\"javascript:launch_chooser('IGvid_enter_filename_" + option + "','date');\"><?php echo _QXZ("audio chooser"); ?></a>";
            new_content = new_content + "<BR> &nbsp; <?php echo _QXZ("VID ID Number Filename"); ?>: <input type=text name=IGvid_id_number_filename_" + option + " id=IGvid_id_number_filename_" + option + " size=40 maxlength=255 value=\"" + IGvid_id_number_filename + "\"> <a href=\"javascript:launch_chooser('IGvid_id_number_filename_" + option + "','date');\"><?php echo _QXZ("audio chooser"); ?></a>";
            new_content = new_content + "<BR> &nbsp; <?php echo _QXZ("VID Confirm Filename"); ?>: <input type=text name=IGvid_confirm_filename_" + option + " id=IGvid_confirm_filename_" + option + " size=40 maxlength=255 value=\"" + IGvid_confirm_filename + "\"> <a href=\"javascript:launch_chooser('IGvid_confirm_filename_" + option + "','date');\"><?php echo _QXZ("audio chooser"); ?></a>";
            new_content = new_content + " &nbsp; <?php echo _QXZ("VID Digits"); ?>: <input type=text size=3 maxlength=3 name=IGvid_validate_digits_" + option + ' id=IGvid_validate_digits_' + option + ' value="' + IGvid_validate_digits + '">';
            new_content = new_content + "<BR> &nbsp; <?php echo _QXZ("VID Container"); ?>: <input type=text size=40 maxlength=40 name=IGvid_container_" + option + ' id=IGvid_container_' + option + ' value="' + IGvid_container + '">';
            new_content = new_content + " &nbsp; <IMG SRC=\"help.png\" onClick=\"FillAndShowHelpDiv(event, 'call_menu-ingroup_vid_container')\" WIDTH=20 HEIGHT=20 BORDER=0 ALT=\"HELP\" ALIGN=TOP> <a href=\"javascript:launch_container_chooser('IGvid_container_" + option + "','date','CM_VIDPROMPT_SPECIAL');\"><?php echo _QXZ("container chooser"); ?></a>";
        }
        if (selected_route=='DID') {
            if (route == selected_route) {
                selected_value = '<option SELECTED value="' + value + '">' + value + "</option>\n";
            } else {
                value='';
            }
            new_content = '<span name=option_route_link_' + option + ' id=option_route_link_' + option + '><a href="<?php echo $ADMIN ?>?ADD=3311&did_pattern=' + value + "\"><?php echo _QXZ("DID"); ?>:</a> </span><select size=1 name=option_route_value_" + option + ' id=option_route_value_' + option + " onChange=\"call_menu_link('" + option + "','DID');\">" + did_list + "\n" + selected_value + '</select>';
        }
        if (selected_route=='HANGUP') {
            if (route == selected_route) {
                selected_value = value;
            } else {
                value='vm-goodbye';
            }
            new_content = "<?php echo _QXZ("Audio File"); ?>: <input type=text name=option_route_value_" + option + " id=option_route_value_" + option + " size=40 maxlength=255 value=\"" + selected_value + "\"> <a href=\"javascript:launch_chooser('option_route_value_" + option + "','date');\"><?php echo _QXZ("audio chooser"); ?></a>";
        }
        if (selected_route=='EXTENSION') {
            if (route == selected_route) {
                selected_value = value;
                selected_context = value_context;
            } else {
                value='8304';
            }
            new_content = "<?php echo _QXZ("Extension"); ?>: <input type=text name=option_route_value_" + option + " id=option_route_value_" + option + " size=20 maxlength=255 value=\"" + selected_value + "\"> &nbsp; <?php echo _QXZ("Context"); ?>: <input type=text name=option_route_value_context_" + option + " id=option_route_value_context_" + option + " size=20 maxlength=255 value=\"" + selected_context + "\"> ";
        }
        if (selected_route=='PHONE') {
            if (route == selected_route) {
                selected_value = '<option SELECTED value="' + value + '">' + value + "</option>\n";
            } else {
                value='';
            }
            new_content = "<?php echo _QXZ("Phone"); ?>: <select size=1 name=option_route_value_" + option + ' id=option_route_value_' + option + '>' + phone_list + "\n" + selected_value + '</select>';
        }
        if ( (selected_route=='VOICEMAIL') || (selected_route=='VMAIL_NO_INST') ) {
            if (route == selected_route) {
                selected_value = value;
            } else {
                value='';
            }
            new_content = "<?php echo _QXZ("Voicemail Box"); ?>: <input type=text name=option_route_value_" + option + " id=option_route_value_" + option + " size=12 maxlength=10 value=\"" + selected_value + "\"> <a href=\"javascript:launch_vm_chooser('option_route_value_" + option + "','date');\"><?php echo _QXZ("voicemail chooser"); ?></a>";
        }
        if (selected_route=='AGI') {
            if (route == selected_route) {
                selected_value = value;
            } else {
                value='';
            }
            new_content = "<?php echo _QXZ("AGI"); ?>: <input type=text name=option_route_value_" + option + " id=option_route_value_" + option + " size=80 maxlength=255 value=\"" + selected_value + "\"> ";
        }

        if (new_content.length < 1) {
            new_content = selected_route
        }

        span_to_update.innerHTML = new_content;
    }

    function call_menu_link(option,route) {
        var selected_value = '';
        var new_content = '';

        var select_list = document.getElementById("option_route_value_" + option);
        var selected_value = select_list.value;
        var span_to_update = document.getElementById("option_route_link_" + option);

        if (route=='CALLMENU') {
            new_content = "<a href=\"<?php echo $ADMIN ?>?ADD=3511&menu_id=" + selected_value + "\"><?php echo _QXZ("Call Menu"); ?>:</a>";
        }
        if (route=='INGROUP') {
            new_content = "<a href=\"<?php echo $ADMIN ?>?ADD=3111&group_id=" + selected_value + "\"><?php echo _QXZ("In-Group"); ?>:</a>";
        }
        if (route=='DID') {
            new_content = "<a href=\"<?php echo $ADMIN ?>?ADD=3311&did_pattern=" + selected_value + "\"><?php echo _QXZ("DID"); ?>:</a>";
        }

        if (new_content.length < 1) {
            new_content = selected_value
        }

        span_to_update.innerHTML = new_content;
    }

    function copy_prev_cm_option(item_number,item_height) {
        var prev_item_number = (item_number - 1)

        var temp_option_value = 'option_value_' + item_number;
        var temp_option_description = 'option_description_' + item_number;
        var temp_option_route = 'option_route_' + item_number;
        var temp_option_route_value = 'option_route_value_' + item_number;
        var temp_option_route_value_context = 'option_route_value_context_' + item_number;

        var temp_prev_option_value = 'option_value_' + prev_item_number;
        var temp_prev_option_description = 'option_description_' + prev_item_number;
        var temp_prev_option_route = 'option_route_' + prev_item_number;
        var temp_prev_option_route_value = 'option_route_value_' + prev_item_number;
        var temp_prev_option_route_value_context = 'option_route_value_context_' + prev_item_number;

        var temp_ValIndex = document.getElementById(temp_prev_option_value).selectedIndex;
        var temp_ValLength = document.getElementById(temp_prev_option_value).length;
        var temp_ValValue =  document.getElementById(temp_prev_option_value).options[temp_ValIndex].value;
        var temp_ValNewLength = document.getElementById(temp_option_value).length;
        var NEWtemp_ValIndex = (temp_ValIndex + 1);
        if (temp_ValNewLength >= 21) {
            if (temp_ValNewLength > temp_ValLength) {
                NEWtemp_ValIndex = (NEWtemp_ValIndex + 1);
            }
            if (NEWtemp_ValIndex > 21) {NEWtemp_ValIndex=1;}
        } else {
            if (NEWtemp_ValIndex > 20) {NEWtemp_ValIndex=0;}
        }
        document.getElementById(temp_option_value).selectedIndex = NEWtemp_ValIndex;

        var temp_optionIndex = document.getElementById(temp_prev_option_route).selectedIndex;
        var temp_optionValue =  document.getElementById(temp_prev_option_route).options[temp_optionIndex].value;

        var rIndex = 0;
        if (temp_optionValue == 'CALLMENU') {rIndex = 0;}
        if (temp_optionValue == 'INGROUP') {rIndex = 1;}
        if (temp_optionValue == 'DID') {rIndex = 2;}
        if (temp_optionValue == 'HANGUP') {rIndex = 3;}
        if (temp_optionValue == 'EXTENSION') {rIndex = 4;}
        if (temp_optionValue == 'PHONE') {rIndex = 5;}
        if (temp_optionValue == 'VOICEMAIL') {rIndex = 6;}
        if (temp_optionValue == 'VMAIL_NO_INST') {rIndex = 7;}
        document.getElementById(temp_option_route).selectedIndex = rIndex;
        document.getElementById(temp_option_description).value = document.getElementById(temp_prev_option_description).value;

        call_menu_option(item_number,temp_optionValue,'','',item_height);

        if ( (temp_optionValue == 'CALLMENU') || (temp_optionValue == 'DID') || (temp_optionValue == 'PHONE') ) {
            var temp_prev_optionVal = document.getElementById(temp_prev_option_route_value);
            var temp_prev_optionValIndex = temp_prev_optionVal.selectedIndex;
            var temp_prev_optionValValue = temp_prev_optionVal.options[temp_prev_optionValIndex].value;

            var temp_optionVal = document.getElementById(temp_option_route_value);
            var temp_optionValIndex = 0;
            var temp_optionValLength = temp_optionVal.length;
            var temp_counter=0; 
            while (temp_counter < temp_optionValLength) {
                if (temp_prev_optionValValue == temp_optionVal.options[temp_counter].value) {
                    temp_optionValIndex = temp_counter;
                }
                temp_counter++;
            }
            document.getElementById(temp_option_route_value).selectedIndex = temp_optionValIndex;

            if ( (temp_optionValue == 'CALLMENU') || (temp_optionValue == 'DID') ) {
                call_menu_link(item_number,temp_optionValue);
            }
        }

        if ( (temp_optionValue == 'HANGUP') || (temp_optionValue == 'VOICEMAIL') || (temp_optionValue == 'VMAIL_NO_INST') ) {
            var temp_prev_optionVal = document.getElementById(temp_prev_option_route_value);
            var temp_optionVal = document.getElementById(temp_option_route_value);

            temp_optionVal.value = temp_prev_optionVal.value;
        }

        if (temp_optionValue == 'EXTENSION') {
            var temp_prev_optionVal = document.getElementById(temp_prev_option_route_value);
            var temp_optionVal = document.getElementById(temp_option_route_value);
            var temp_prev_optionValContext = document.getElementById(temp_prev_option_route_value_context);
            var temp_optionValContext = document.getElementById(temp_option_route_value_context);

            temp_optionVal.value = temp_prev_optionVal.value;
            temp_optionValContext.value = temp_prev_optionValContext.value;
        }

        if (temp_optionValue == 'INGROUP') {
            // In-Group select list
            var temp_prev_optionVal = document.getElementById(temp_prev_option_route_value);
            var temp_prev_optionValIndex = temp_prev_optionVal.selectedIndex;
            var temp_prev_optionValValue = temp_prev_optionVal.options[temp_prev_optionValIndex].value;

            var temp_optionVal = document.getElementById(temp_option_route_value);
            var temp_optionValIndex = 0;
            var temp_optionValLength = temp_optionVal.length;
            var temp_counter=0; 
            while (temp_counter < temp_optionValLength) {
                if (temp_prev_optionValValue == temp_optionVal.options[temp_counter].value) {
                    temp_optionValIndex = temp_counter;
                }
                temp_counter++;
            }
            document.getElementById(temp_option_route_value).selectedIndex = temp_optionValIndex;
            call_menu_link(item_number,temp_optionValue);

            // In-Group Handle Method
            var temp2_option_route_value = 'IGhandle_method_' + item_number;
            var temp2_prev_option_route_value = 'IGhandle_method_' + prev_item_number;

            var temp2_prev_optionVal = document.getElementById(temp2_prev_option_route_value);
            var temp2_prev_optionValIndex = temp2_prev_optionVal.selectedIndex;
            var temp2_prev_optionValValue = temp2_prev_optionVal.options[temp2_prev_optionValIndex].value;

            var temp2_optionVal = document.getElementById(temp2_option_route_value);
            var temp2_optionValIndex = 0;
            var temp2_optionValLength = temp2_optionVal.length;
            var temp2_counter=0; 
            while (temp2_counter < temp2_optionValLength) {
                if (temp2_prev_optionValValue == temp2_optionVal.options[temp2_counter].value) {
                    temp2_optionValIndex = temp2_counter;
                }
                temp2_counter++;
            }
            document.getElementById(temp2_option_route_value).selectedIndex = temp2_optionValIndex;

            // In-Group Search Method
            var temp3_option_route_value = 'IGsearch_method_' + item_number;
            var temp3_prev_option_route_value = 'IGsearch_method_' + prev_item_number;

            var temp3_prev_optionVal = document.getElementById(temp3_prev_option_route_value);
            var temp3_prev_optionValIndex = temp3_prev_optionVal.selectedIndex;
            var temp3_prev_optionValValue = temp3_prev_optionVal.options[temp3_prev_optionValIndex].value;

            var temp3_optionVal = document.getElementById(temp3_option_route_value);
            var temp3_optionValIndex = 0;
            var temp3_optionValLength = temp3_optionVal.length;
            var temp3_counter=0; 
            while (temp3_counter < temp3_optionValLength) {
                if (temp3_prev_optionValValue == temp3_optionVal.options[temp3_counter].value) {
                    temp3_optionValIndex = temp3_counter;
                }
                temp3_counter++;
            }
            document.getElementById(temp3_option_route_value).selectedIndex = temp3_optionValIndex;

            // In-Group Campaign
            var temp4_option_route_value = 'IGcampaign_id_' + item_number;
            var temp4_prev_option_route_value = 'IGcampaign_id_' + prev_item_number;

            var temp4_prev_optionVal = document.getElementById(temp4_prev_option_route_value);
            var temp4_prev_optionValIndex = temp4_prev_optionVal.selectedIndex;
            var temp4_prev_optionValValue = temp4_prev_optionVal.options[temp4_prev_optionValIndex].value;

            var temp4_optionVal = document.getElementById(temp4_option_route_value);
            var temp4_optionValIndex = 0;
            var temp4_optionValLength = temp4_optionVal.length;
            var temp4_counter=0; 
            while (temp4_counter < temp4_optionValLength) {
                if (temp4_prev_optionValValue == temp4_optionVal.options[temp4_counter].value) {
                    temp4_optionValIndex = temp4_counter;
                }
                temp4_counter++;
            }
            document.getElementById(temp4_option_route_value).selectedIndex = temp4_optionValIndex;

            // In-Group List ID
            var temp5 = 'IGlist_id_' + item_number;
            var temp5_prev = 'IGlist_id_' + prev_item_number;
            var temp5_optionVal = document.getElementById(temp5);
            var temp5_prev_optionVal = document.getElementById(temp5_prev);
            temp5_optionVal.value = temp5_prev_optionVal.value;

            // In-Group Phone Code
            var temp6 = 'IGphone_code_' + item_number;
            var temp6_prev = 'IGphone_code_' + prev_item_number;
            var temp6_optionVal = document.getElementById(temp6);
            var temp6_prev_optionVal = document.getElementById(temp6_prev);
            temp6_optionVal.value = temp6_prev_optionVal.value;

            // In-Group VID Enter Filename
            var temp7 = 'IGvid_enter_filename_' + item_number;
            var temp7_prev = 'IGvid_enter_filename_' + prev_item_number;
            var temp7_optionVal = document.getElementById(temp7);
            var temp7_prev_optionVal = document.getElementById(temp7_prev);
            temp7_optionVal.value = temp7_prev_optionVal.value;

            // In-Group VID ID Number Filename
            var temp8 = 'IGvid_id_number_filename_' + item_number;
            var temp8_prev = 'IGvid_id_number_filename_' + prev_item_number;
            var temp8_optionVal = document.getElementById(temp8);
            var temp8_prev_optionVal = document.getElementById(temp8_prev);
            temp8_optionVal.value = temp8_prev_optionVal.value;

            // In-Group VID Confirm Filename
            var temp9 = 'IGvid_confirm_filename_' + item_number;
            var temp9_prev = 'IGvid_confirm_filename_' + prev_item_number;
            var temp9_optionVal = document.getElementById(temp9);
            var temp9_prev_optionVal = document.getElementById(temp9_prev);
            temp9_optionVal.value = temp9_prev_optionVal.value;

            // In-Group VID Digits
            var temp10 = 'IGvid_validate_digits_' + item_number;
            var temp10_prev = 'IGvid_validate_digits_' + prev_item_number;
            var temp10_optionVal = document.getElementById(temp10);
            var temp10_prev_optionVal = document.getElementById(temp10_prev);
            temp10_optionVal.value = temp10_prev_optionVal.value;

            // In-Group VID Container
            var temp11 = 'IGvid_container_' + item_number;
            var temp11_prev = 'IGvid_container_' + prev_item_number;
            var temp11_optionVal = document.getElementById(temp11);
            var temp11_prev_optionVal = document.getElementById(temp11_prev);
            temp11_optionVal.value = temp11_prev_optionVal.value;
        }
    }
    <?php } ?>

    <?php if ( ($ADD==2811) or ($ADD==3811) or ($ADD==3111) or ($ADD==2111) or ($ADD==2011) or ($ADD==4111) or ($ADD==5111) ) { ?>
    function dynamic_call_action(option,route,value,chooser_height) {
        var call_menu_list = '<?php echo $call_menu_list ?>';
        var ingroup_list = '<?php echo $ingroup_list ?>';
        var IGcampaign_id_list = '<?php echo $IGcampaign_id_list ?>';
        var IGhandle_method_list = '<?php echo $IGhandle_method_list ?>';
        var IGsearch_method_list = "<?php echo $IGsearch_method_list ?>";
        var did_list = '<?php echo $did_list ?>';
        var selected_value = '';
        var selected_context = '';
        var new_content = '';

        var select_list = document.getElementById(option + "");
        var selected_route = select_list.value;
        var span_to_update = document.getElementById(option + "_value_span");

        if (selected_route=='CALLMENU') {
            if (route == selected_route) {
                selected_value = '<option SELECTED value="' + value + '">' + value + "</option>\n";
            } else {
                value = '';
            }
            new_content = '<span name=' + option + '_value_link id=' + option + '_value_link><a href="<?php echo $ADMIN ?>?ADD=3511&menu_id=' + value + "\"><?php echo _QXZ("Call Menu"); ?>: </a></span><select size=1 name=" + option + '_value id=' + option + "_value onChange=\"dynamic_call_action_link('" + option + "','CALLMENU');\">" + call_menu_list + "\n" + selected_value + '</select>';
        }
        if (selected_route=='INGROUP') {
            if ( (route != selected_route) || (value.length < 10) ) {
                value = 'SALESLINE,CID,LB,998,TESTCAMP,1,,,,,';
            }
            var value_split = value.split(",");
            var IGgroup_id = value_split[0];
            var IGhandle_method = value_split[1];
            var IGsearch_method = value_split[2];
            var IGlist_id = value_split[3];
            var IGcampaign_id = value_split[4];
            var IGphone_code = value_split[5];
            var IGvid_enter_filename = value_split[6];
            var IGvid_id_number_filename = value_split[7];
            var IGvid_confirm_filename = value_split[8];
            var IGvid_validate_digits = value_split[9];
            var IGvid_container = value_split[10];

            if (route == selected_route) {
                selected_value = '<option SELECTED>' + IGgroup_id + '</option>';
            }

            new_content = new_content + '<span name=' + option + '_value_link id=' + option + '_value_link><a href="<?php echo $ADMIN ?>?ADD=3111&group_id=' + IGgroup_id + "\"><?php echo _QXZ("In-Group"); ?>:</a> </span> ";
            new_content = new_content + '<select size=1 name=IGgroup_id_' + option + ' id=IGgroup_id_' + option + " onChange=\"dynamic_call_action_link('IGgroup_id_" + option + "','INGROUP');\">";
            new_content = new_content + '' + ingroup_list + "\n" + selected_value + '</select>';
            new_content = new_content + " &nbsp; <?php echo _QXZ("Handle Method"); ?>: <select size=1 name=IGhandle_method_" + option + ' id=IGhandle_method_' + option + '>';
            new_content = new_content + '' + '<option SELECTED>' + IGhandle_method + '</option>' + IGhandle_method_list + '</select>' + "\n";
            new_content = new_content + "<BR><?php echo _QXZ("Search Method"); ?>: <select size=1 name=IGsearch_method_" + option + ' id=IGsearch_method_' + option + '>';
            new_content = new_content + '' + IGsearch_method_list + "\n" + '<option SELECTED>' + IGsearch_method + '</select>';
            new_content = new_content + " &nbsp; <?php echo _QXZ("List ID"); ?>: <input type=text size=5 maxlength=14 name=IGlist_id_" + option + ' id=IGlist_id_' + option + ' value="' + IGlist_id + '">';
            new_content = new_content + "<BR><?php echo _QXZ("Campaign ID"); ?>: <select size=1 name=IGcampaign_id_" + option + ' id=IGcampaign_id_' + option + '>';
            new_content = new_content + '' + IGcampaign_id_list + "\n" + '<option SELECTED>' + IGcampaign_id + '</select>';
            new_content = new_content + " &nbsp; <?php echo _QXZ("Phone Code"); ?>: <input type=text size=5 maxlength=14 name=IGphone_code_" + option + ' id=IGphone_code_' + option + ' value="' + IGphone_code + '">';
        }
        if (selected_route=='DID') {
            if (route == selected_route) {
                selected_value = '<option SELECTED value="' + value + '">' + value + "</option>\n";
            } else {
                value = '';
            }
            new_content = '<span name=' + option + '_value_link id=' + option + '_value_link><a href="<?php echo $ADMIN ?>?ADD=3311&did_pattern=' + value + "\"><?php echo _QXZ("DID"); ?>:</a> </span><select size=1 name=" + option + '_value id=' + option + "_value onChange=\"dynamic_call_action_link('" + option + "','DID');\">" + did_list + "\n" + selected_value + '</select>';
        }
        if (selected_route=='MESSAGE') {
            if (route == selected_route) {
                selected_value = value;
            } else {
                value = 'nbdy-avail-to-take-call|vm-goodbye';
            }
            new_content = "<?php echo _QXZ("Audio File"); ?>: <input type=text name=" + option + "_value id=" + option + "_value size=40 maxlength=255 value=\"" + value + "\"> <a href=\"javascript:launch_chooser('" + option + "_value','date');\"><?php echo _QXZ("audio chooser"); ?></a>";
        }
        if (selected_route=='EXTENSION') {
            if ( (route != selected_route) || (value.length < 3) ) {
                value = '8304,default';
            }
            var value_split = value.split(",");
            var EXextension = value_split[0];
            var EXcontext = value_split[1];

            new_content = "<?php echo _QXZ("Extension"); ?>: <input type=text name=EXextension_" + option + " id=EXextension_" + option + " size=20 maxlength=255 value=\"" + EXextension + "\"> &nbsp; <?php echo _QXZ("Context"); ?>: <input type=text name=EXcontext_" + option + " id=EXcontext_" + option + " size=20 maxlength=255 value=\"" + EXcontext + "\"> ";
        }
        if ( (selected_route=='VOICEMAIL') || (selected_route=='VMAIL_NO_INST') ) {
            if (route == selected_route) {
                selected_value = value;
            } else {
                value = '101';
            }
            new_content = "<?php echo _QXZ("Voicemail Box"); ?>: <input type=text name=" + option + "_value id=" + option + "_value size=12 maxlength=10 value=\"" + value + "\"> <a href=\"javascript:launch_vm_chooser('" + option + "_value','date');\"><?php echo _QXZ("voicemail chooser"); ?></a>";
        }

        if (new_content.length < 1) {
            new_content = selected_route
        }

        span_to_update.innerHTML = new_content;
    }

    function dynamic_call_action_link(field,route) {
        var selected_value = '';
        var new_content = '';

        if ( (route=='CALLMENU') || (route=='DID') ) {
            var select_list = document.getElementById(field + "_value");
        }
        if (route=='INGROUP') {
            var select_list = document.getElementById(field + "");
            field = field.replace(/IGgroup_id_/, "");
        }
        var selected_value = select_list.value;
        var span_to_update = document.getElementById(field + "_value_link");

        if (route=='CALLMENU') {
            new_content = '<a href="<?php echo $ADMIN ?>?ADD=3511&menu_id=' + selected_value + "\"><?php echo _QXZ("Call Menu"); ?>:</a>";
        }
        if (route=='INGROUP') {
            new_content = '<a href="<?php echo $ADMIN ?>?ADD=3111&group_id=' + selected_value + "\"><?php echo _QXZ("In-Group"); ?>:</a>";
        }
        if (route=='DID') {
            new_content = '<a href="<?php echo $ADMIN ?>?ADD=3311&did_pattern=' + selected_value + "\"><?php echo _QXZ("DID"); ?>:</a>";
        }

        if (new_content.length < 1) {
            new_content = selected_route
        }

        span_to_update.innerHTML = new_content;
    }
    <?php } ?>

    var pass = '<?php echo $PHP_AUTH_PW; ?>';
    </script>

    <span id="audio_chooser_span" style="position:absolute;visibility:hidden;z-index:2000;"></span>

    <?php if ($ADD == '730000000000000') { ?>
    <style type="text/css">
    .diff table {
        margin: 1px 1px 1px 1px;
        border-collapse: collapse;
        border-spacing: 0;
    }
    .diff td {
        vertical-align: top;
        font-family: monospace;
        font-size: 9;
    }
    .diff span {
        display:block;
        min-height:1px;
        margin-top:-1px;
        padding:1px 1px 1px 1px;
    }
    * html .diff span {
        height:1px;
    }
    .diff span:first-child {
        margin-top:1px;
    }
    .diffDeleted span {
        border:1px solid rgb(255,51,0);
        background:rgb(255,173,153);
    }
    .diffInserted span {
        border:1px solid rgb(51,204,51);
        background:rgb(102,255,51);
    }
    </style>
    <?php } ?>

    <style type="text/css">
    .auraltext {
        position: absolute;
        font-size: 0;
        left: -1000px;
    }
    .chart_td {
        background-image: url(images/gridline58.gif); 
        background-repeat: repeat-x; 
        background-position: left top; 
        border-left: 1px solid #e5e5e5; 
        border-right: 1px solid #e5e5e5; 
        padding:0; 
        border-bottom: 1px solid #e5e5e5; 
        background-color:transparent;
    }
    .head_style {
        background-color: #<?php echo $Mmain_bgcolor; ?>;
        transition: all 0.3s ease;
    }
    .head_style:hover {
        background-color: #<?php echo darken_color($Mmain_bgcolor, 10); ?>;
    }
    .head_style_selected {
        background-color: #<?php echo $Mhead_color; ?>;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
    }
    .subhead_style {
        background-color: #<?php echo $Msubhead_color; ?>;
        transition: all 0.3s ease;
    }
    .subhead_style:hover {
        background-color: white;
    }
    .subhead_style_selected {
        background-color: #<?php echo $Mselected_color; ?>;
    }
    .adminmenu_style_selected {
        background-color: white;
        transition: all 0.3s ease;
    }
    .adminmenu_style_selected:hover {
        background-color: #E6E6E6;
    }
    .records_list_x {
        background-color: #<?php echo $SSstd_row2_background; ?>;
        transition: all 0.3s ease;
    }
    .records_list_x:hover {
        background-color: #<?php echo lighten_color($SSstd_row2_background, 10); ?>;
    }
    .records_list_y {
        background-color: #<?php echo $SSstd_row1_background; ?>;
        transition: all 0.3s ease;
    }
    .records_list_y:hover {
        background-color: #<?php echo lighten_color($SSstd_row1_background, 10); ?>;
    }
    .horiz_line {
        height: 0px;
        margin: 0px;
        border-bottom: 1px solid #E6E6E6;
        font-size: 1px;
    }
    .horiz_line_grey {
        height: 0px;
        margin: 0px;
        border-bottom: 1px solid #9E9E9E;
        font-size: 1px;
    }
    .sub_sub_head_links {
        font-family:HELVETICA;
        font-size:11;
        color:BLACK;
    }
    </style>

    <?php
function hex2rgb($hex) {
    $hex = str_replace("#", "", $hex);
    if(strlen($hex) == 3) {
        $r = hexdec(substr($hex,0,1).substr($hex,0,1));
        $g = hexdec(substr($hex,1,1).substr($hex,1,1));
        $b = hexdec(substr($hex,2,1).substr($hex,2,1));
    } else {
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
    }
    return "$r,$g,$b";
}

function darken_color($hex, $percent) {
    $hex = str_replace("#", "", $hex);
    $r = hexdec(substr($hex,0,2));
    $g = hexdec(substr($hex,2,2));
    $b = hexdec(substr($hex,4,2));
    
    $r = max(0, $r - ($r * $percent / 100));
    $g = max(0, $g - ($g * $percent / 100));
    $b = max(0, $b - ($b * $percent / 100));
    
    return sprintf("%02x%02x%02x", $r, $g, $b);
}

function lighten_color($hex, $percent) {
    $hex = str_replace("#", "", $hex);
    $r = hexdec(substr($hex,0,2));
    $g = hexdec(substr($hex,2,2));
    $b = hexdec(substr($hex,4,2));
    
    $r = min(255, $r + (255 - $r) * $percent / 100);
    $g = min(255, $g + (255 - $g) * $percent / 100);
    $b = min(255, $b + (255 - $b) * $percent / 100);
    
    return sprintf("%02x%02x%02x", $r, $g, $b);
}
?>

<?php
if ($no_title < 1) {
    echo "</title>\n";
}
?>

<script language="Javascript">
// Additional JavaScript functions for modern interactions
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scroll behavior
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add loading states to forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('input[type="submit"], button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            }
        });
    });

    // Add tooltip functionality
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.getAttribute('data-tooltip');
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
        });
        
        element.addEventListener('mouseleave', function() {
            const tooltip = document.querySelector('.tooltip');
            if (tooltip) {
                tooltip.remove();
            }
        });
    });
});

// Modal functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Close modal on outside click
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
});

// Tab functionality
function openTab(tabName, contentId) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => {
        content.style.display = 'none';
    });
    
    // Remove active class from all tabs
    const tabs = document.querySelectorAll('.tab-button');
    tabs.forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Show selected tab content
    const selectedContent = document.getElementById(contentId);
    if (selectedContent) {
        selectedContent.style.display = 'block';
    }
    
    // Add active class to clicked tab
    event.target.classList.add('active');
}

// Accordion functionality
function toggleAccordion(element) {
    const content = element.nextElementSibling;
    const allAccordions = document.querySelectorAll('.accordion-content');
    const allHeaders = document.querySelectorAll('.accordion-header');
    
    // Close all other accordions
    allAccordions.forEach(acc => {
        if (acc !== content) {
            acc.style.maxHeight = null;
            acc.classList.remove('active');
        }
    });
    
    allHeaders.forEach(header => {
        if (header !== element) {
            header.classList.remove('active');
        }
    });
    
    // Toggle current accordion
    if (content.style.maxHeight) {
        content.style.maxHeight = null;
        element.classList.remove('active');
    } else {
        content.style.maxHeight = content.scrollHeight + "px";
        element.classList.add('active');
    }
}

// Search functionality
function performSearch(searchInput, containerId) {
    const searchValue = searchInput.value.toLowerCase();
    const container = document.getElementById(containerId);
    const items = container.querySelectorAll('.searchable-item');
    
    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(searchValue)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}

// Copy to clipboard function
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        showNotification('Copied to clipboard!', 'success');
    }, function(err) {
        console.error('Could not copy text: ', err);
        showNotification('Failed to copy', 'error');
    });
}

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

// Confirm dialog replacement
function showConfirm(message, onConfirm, onCancel) {
    const modal = document.createElement('div');
    modal.className = 'modal';
    modal.style.display = 'flex';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3>Confirm Action</h3>
            </div>
            <div class="modal-body">
                <p>${message}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="this.closest('.modal').remove()">Cancel</button>
                <button class="btn btn-primary" id="confirmBtn">Confirm</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
    
    const confirmBtn = modal.querySelector('#confirmBtn');
    confirmBtn.addEventListener('click', function() {
        modal.remove();
        document.body.style.overflow = 'auto';
        if (onConfirm) onConfirm();
    });
}

// Export data function
function exportData(format, data) {
    let content, filename, type;
    
    switch(format) {
        case 'csv':
            content = convertToCSV(data);
            filename = 'export.csv';
            type = 'text/csv';
            break;
        case 'json':
            content = JSON.stringify(data, null, 2);
            filename = 'export.json';
            type = 'application/json';
            break;
        default:
            return;
    }
    
    const blob = new Blob([content], { type: type });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

function convertToCSV(data) {
    if (!data.length) return '';
    
    const headers = Object.keys(data[0]);
    const csvHeaders = headers.join(',');
    
    const csvRows = data.map(row => {
        return headers.map(header => {
            const value = row[header];
            return typeof value === 'string' && value.includes(',') 
                ? `"${value}"` 
                : value;
        }).join(',');
    });
    
    return [csvHeaders, ...csvRows].join('\n');
}

// Print function
function printElement(elementId) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Print</title>
            <style>
                body { font-family: Arial, sans-serif; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                @media print { .no-print { display: none; } }
            </style>
        </head>
        <body>
            ${element.innerHTML}
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

// Date picker enhancement
function initDatePicker() {
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            const date = new Date(this.value);
            const today = new Date();
            if (date < today) {
                this.classList.add('past-date');
            } else {
                this.classList.remove('past-date');
            }
        });
    });
}

// Initialize on page load
window.addEventListener('load', function() {
    initDatePicker();
    
    // Add fade-in animation to elements
    const elements = document.querySelectorAll('.fade-in');
    elements.forEach((element, index) => {
        setTimeout(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl+S to save
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        const saveBtn = document.querySelector('input[type="submit"], button[type="submit"]');
        if (saveBtn) {
            saveBtn.click();
        }
    }
    
    // Escape to close modals
    if (e.key === 'Escape') {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (modal.style.display === 'flex') {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });
    }
});

// Auto-resize textareas
function autoResize(textarea) {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
}

document.addEventListener('input', function(e) {
    if (e.target.tagName.toLowerCase() === 'textarea') {
        autoResize(e.target);
    }
});

// Progress bar animation
function animateProgressBar(progressBar, targetPercent) {
    let currentPercent = 0;
    const increment = targetPercent / 50;
    
    const timer = setInterval(() => {
        currentPercent += increment;
        if (currentPercent >= targetPercent) {
            currentPercent = targetPercent;
            clearInterval(timer);
        }
        progressBar.style.width = currentPercent + '%';
        progressBar.setAttribute('aria-valuenow', currentPercent);
    }, 20);
}

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.classList.add('error');
            
            // Remove error on input
            input.addEventListener('input', function() {
                this.classList.remove('error');
            }, { once: true });
        }
    });
    
    if (!isValid) {
        showNotification('Please fill in all required fields', 'error');
    }
    
    return isValid;
}

// Lazy load images
function lazyLoadImages() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
}

// Initialize lazy loading
lazyLoadImages();
</script>

<style>
/* Additional Modern Styles */
.tooltip {
    position: absolute;
    background: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 12px;
    z-index: 1000;
    pointer-events: none;
    white-space: nowrap;
}

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
}

.modal-content {
    background: white;
    border-radius: 12px;
    padding: 0;
    max-width: 600px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.3s ease;
}

.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.modal-header h3 {
    margin: 0;
    color: #333;
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid #e0e0e0;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    border-radius: 8px;
    padding: 16px 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    z-index: 2000;
    transform: translateX(400px);
    transition: transform 0.3s ease;
    max-width: 350px;
}

.notification.show {
    transform: translateX(0);
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.notification-success {
    border-left: 4px solid #28a745;
}

.notification-error {
    border-left: 4px solid #dc3545;
}

.notification-info {
    border-left: 4px solid #17a2b8;
}

.tab-content {
    display: none;
    padding: 20px;
    background: white;
    border-radius: 8px;
    margin-top: -1px;
}

.tab-button {
    padding: 10px 20px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    cursor: pointer;
    transition: all 0.3s ease;
}

.tab-button:first-child {
    border-radius: 8px 0 0 0;
}

.tab-button:last-child {
    border-radius: 0 8px 0 0;
}

.tab-button.active {
    background: white;
    border-bottom-color: white;
    position: relative;
    z-index: 1;
}

.accordion-header {
    padding: 16px 20px;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.accordion-header:hover {
    background: #e9ecef;
}

.accordion-header.active {
    background: var(--primary-color);
    color: white;
}

.accordion-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    background: white;
    border: 1px solid #dee2e6;
    border-top: none;
}

.accordion-content.active {
    max-height: 1000px;
}

.searchable-item {
    transition: all 0.3s ease;
}

.past-date {
    border-color: #dc3545 !important;
    background-color: #f8d7da !important;
}

.error {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1) !important;
}

.fade-in {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.5s ease;
}

.lazy {
    filter: blur(5px);
    transition: filter 0.3s ease;
}

.lazy.loaded {
    filter: blur(0);
}

.progress {
    width: 100%;
    height: 20px;
    background: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
    margin: 10px 0;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
    transition: width 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
    font-weight: 600;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from {
        transform: translateY(50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
    
    .modal {
        display: none !important;
    }
    
    .notification {
        display: none !important;
    }
    
    body {
        background: white !important;
    }
    
    .admin-header {
        box-shadow: none !important;
        border: 1px solid #000 !important;
    }
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .modal-content {
        width: 95%;
        margin: 10px;
    }
    
    .notification {
        right: 10px;
        left: 10px;
        max-width: none;
    }
    
    .nav-menu {
        flex-direction: column;
        width: 100%;
    }
    
    .nav-item {
        width: 100%;
    }
    
    .nav-link {
        width: 100%;
        justify-content: center;
    }
}
</style>

</head>
<body BGCOLOR=white marginheight=0 marginwidth=0 leftmargin=0 topmargin=0>
<!-- INTERNATIONALIZATION-LINKS-PLACEHOLDER-VICIDIAL -->

<?php
if ($header_font_size < 4) {$header_font_size='12';}
if ($subheader_font_size < 4) {$subheader_font_size='11';}
if ($subcamp_font_size < 4) {$subcamp_font_size='11';}
?>

<!-- Main Content Container -->
<div class="main-container" style="max-width: 1400px; margin: 0 auto; padding: 20px;">
    <!-- Content will be dynamically inserted here based on the page -->
</div>

<!-- Footer -->
<footer class="footer" style="background: linear-gradient(135deg, #<?php echo $SSmenu_background; ?>, #<?php echo $SSmenu_background; ?>dd); color: white; padding: 20px; margin-top: 40px; border-radius: 12px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
        <div>
            <p style="margin: 0;">&copy; <?php echo date('Y'); ?> Vicidial Admin Panel</p>
            <p style="margin: 5px 0 0 0; font-size: 12px; opacity: 0.8;">Version <?php echo $vicidial_version ?? 'Unknown'; ?></p>
        </div>
        <div style="display: flex; gap: 20px;">
            <a href="#" onclick="openModal('helpModal'); return false;" style="color: white; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                <i class="fas fa-question-circle"></i>
                <span>Help</span>
            </a>
            <a href="#" onclick="openModal('aboutModal'); return false;" style="color: white; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                <i class="fas fa-info-circle"></i>
                <span>About</span>
            </a>
        </div>
    </div>
</footer>

<!-- Help Modal -->
<div id="helpModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Help & Documentation</h3>
            <button onclick="closeModal('helpModal')" style="background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
        </div>
        <div class="modal-body">
            <h4>Quick Links</h4>
            <ul style="list-style: none; padding: 0;">
                <li style="margin: 10px 0;"><a href="#" style="color: var(--primary-color);">User Guide</a></li>
                <li style="margin: 10px 0;"><a href="#" style="color: var(--primary-color);">API Documentation</a></li>
                <li style="margin: 10px 0;"><a href="#" style="color: var(--primary-color);">Video Tutorials</a></li>
                <li style="margin: 10px 0;"><a href="#" style="color: var(--primary-color);">FAQ</a></li>
            </ul>
            
            <h4>Keyboard Shortcuts</h4>
            <table style="width: 100%; margin-top: 10px;">
                <tr>
                    <td style="padding: 5px;"><kbd>Ctrl + S</kbd></td>
                    <td style="padding: 5px;">Save current form</td>
                </tr>
                <tr>
                    <td style="padding: 5px;"><kbd>Esc</kbd></td>
                    <td style="padding: 5px;">Close modal/dialog</td>
                </tr>
            </table>
        </div>
    </div>
</div>

<!-- About Modal -->
<div id="aboutModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>About Vicidial</h3>
            <button onclick="closeModal('aboutModal')" style="background: none; border: none; font-size: 24px; cursor: pointer;">&times;</button>
        </div>
        <div class="modal-body">
            <p>Vicidial is a sophisticated contact center suite designed for high-volume inbound and outbound campaigns.</p>
            <p style="margin-top: 15px;"><strong>Version:</strong> <?php echo $vicidial_version ?? 'Unknown'; ?></p>
            <p><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></p>
            <p><strong>Database:</strong> <?php echo $DB_type ?? 'MySQL'; ?></p>
            <p style="margin-top: 15px;">© 2024 Vicidial Group. All rights reserved.</p>
        </div>
    </div>
</div>

<!-- Notification Container -->
<div id="notificationContainer"></div>
?>
<!-- Modern Sidebar Navigation -->
<div class="sidebar-container">
    <div class="sidebar-header">
        <a href="<?php echo $ADMIN ?>" class="logo-link">
            <img src="<?php echo $selected_logo; ?>" alt="System logo" class="logo-img">
        </a>
        <h2 class="sidebar-title"><?php echo _QXZ("ADMINISTRATION"); ?></h2>
    </div>
    
    <nav class="sidebar-nav">
        <div class="nav-section">
            <?php
            if (($reports_only_user < 1) and ($qc_only_user < 1)) {
            ?>
            <!-- Reports Navigation -->
            <div class="nav-item">
                <a href="<?php echo $ADMIN ?>?ADD=999999" class="nav-link <?php echo $reports_hh ?>">
                    <span class="nav-icon"><?php echo $reports_icon ?></span>
                    <span class="nav-text"><?php echo _QXZ("Reports"); ?></span>
                </a>
            </div>

            <!-- Users Navigation -->
            <div class="nav-item">
                <a href="<?php echo $ADMIN ?>?ADD=0A" class="nav-link <?php echo $users_hh ?>">
                    <span class="nav-icon"><?php echo $users_icon ?></span>
                    <span class="nav-text"><?php echo _QXZ("Users"); ?></span>
                </a>
                
                <?php
                if (strlen($users_hh) > 25) {
                    $list_sh = ($sh == 'list') ? "subnav-active" : "subnav-item";
                    $new_sh = ($sh == 'new') ? "subnav-active" : "subnav-item";
                    $copy_sh = ($sh == 'copy') ? "subnav-active" : "subnav-item";
                    $search_sh = ($sh == 'search') ? "subnav-active" : "subnav-item";
                    $stats_sh = ($sh == 'stats') ? "subnav-active" : "subnav-item";
                    $status_sh = ($sh == 'status') ? "subnav-active" : "subnav-item";
                    $sheet_sh = ($sh == 'sheet') ? "subnav-active" : "subnav-item";
                    $territory_sh = ($sh == 'territory') ? "subnav-active" : "subnav-item";
                    $newlimit_sh = ($sh == 'newlimit') ? "subnav-active" : "subnav-item";
                ?>
                <div class="subnav-container">
                    <a href="<?php echo $ADMIN ?>?ADD=0A" class="<?php echo $list_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-list"></i></span>
                        <span><?php echo _QXZ("Show Users"); ?></span>
                    </a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=1" class="<?php echo $new_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-user-plus"></i></span>
                        <span><?php echo _QXZ("Add A New User"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=1A" class="<?php echo $copy_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-copy"></i></span>
                        <span><?php echo _QXZ("Copy User"); ?></span>
                    </a>
                    <?php } ?>
                    <a href="<?php echo $ADMIN ?>?ADD=550" class="<?php echo $search_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-search"></i></span>
                        <span><?php echo _QXZ("Search For A User"); ?></span>
                    </a>
                    <a href="./user_stats.php?user=<?php echo $user ?>" class="<?php echo $stats_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-chart-bar"></i></span>
                        <span><?php echo _QXZ("User Stats"); ?></span>
                    </a>
                    <a href="./user_status.php?user=<?php echo $user ?>" class="<?php echo $status_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-info-circle"></i></span>
                        <span><?php echo _QXZ("User Status"); ?></span>
                    </a>
                    <a href="./AST_agent_time_sheet.php?agent=<?php echo $user ?>" class="<?php echo $sheet_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-clock"></i></span>
                        <span><?php echo _QXZ("Time Sheet"); ?></span>
                    </a>
                    <?php
                    if (($SSuser_territories_active > 0) or ($user_territories_active > 0)) {
                    ?>
                    <a href="./user_territories.php?agent=<?php echo $user ?>" class="<?php echo $territory_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-map-marked-alt"></i></span>
                        <span><?php echo _QXZ("User Territories"); ?></span>
                    </a>
                    <?php
                    }
                    if ($SSuser_new_lead_limit > 0) {
                    ?>
                    <a href="./admin_user_list_new.php?user=---ALL---&list_id=NONE&stage=overall" class="<?php echo $newlimit_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-list-ol"></i></span>
                        <span><?php echo _QXZ("Overall New Lead Limits"); ?></span>
                    </a>
                    <?php
                    }
                    ?>
                </div>
                <?php
                }
                ?>
            </div>

            <!-- Campaigns Navigation -->
            <div class="nav-item">
                <a href="<?php echo $ADMIN ?>?ADD=10" class="nav-link <?php echo $campaigns_hh ?>">
                    <span class="nav-icon"><?php echo $campaigns_icon ?></span>
                    <span class="nav-text"><?php echo _QXZ("Campaigns"); ?></span>
                </a>
                
                <?php
                if (strlen($campaigns_hh) > 25) {
                    $list_sh = ($sh == 'list') ? "subnav-active" : "subnav-item";
                    $status_sh = ($sh == 'status') ? "subnav-active" : "subnav-item";
                    $hotkey_sh = ($sh == 'hotkey') ? "subnav-active" : "subnav-item";
                    $recycle_sh = ($sh == 'recycle') ? "subnav-active" : "subnav-item";
                    $autoalt_sh = ($sh == 'autoalt') ? "subnav-active" : "subnav-item";
                    $pause_sh = ($sh == 'pause') ? "subnav-active" : "subnav-item";
                    $listmix_sh = ($sh == 'listmix') ? "subnav-active" : "subnav-item";
                    $preset_sh = ($sh == 'preset') ? "subnav-active" : "subnav-item";
                    $accid_sh = ($sh == 'accid') ? "subnav-active" : "subnav-item";
                ?>
                <div class="subnav-container">
                    <a href="<?php echo $ADMIN ?>?ADD=10" class="<?php echo $list_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-th-list"></i></span>
                        <span><?php echo _QXZ("Campaigns Main"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=32" class="<?php echo $status_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-tags"></i></span>
                        <span><?php echo _QXZ("Statuses"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=33" class="<?php echo $hotkey_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-keyboard"></i></span>
                        <span><?php echo _QXZ("HotKeys"); ?></span>
                    </a>
                    <?php
                    if ($SSoutbound_autodial_active > 0) {
                    ?>
                    <a href="<?php echo $ADMIN ?>?ADD=35" class="<?php echo $recycle_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-recycle"></i></span>
                        <span><?php echo _QXZ("Lead Recycle"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=36" class="<?php echo $autoalt_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-exchange-alt"></i></span>
                        <span><?php echo _QXZ("Auto-Alt Dial"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=39" class="<?php echo $listmix_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-layer-group"></i></span>
                        <span><?php echo _QXZ("List Mix"); ?></span>
                    </a>
                    <?php
                    }
                    ?>
                    <a href="<?php echo $ADMIN ?>?ADD=37" class="<?php echo $pause_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-pause-circle"></i></span>
                        <span><?php echo _QXZ("Pause Codes"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=301" class="<?php echo $preset_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-cog"></i></span>
                        <span><?php echo _QXZ("Presets"); ?></span>
                    </a>
                    <?php
                    if ($SScampaign_cid_areacodes_enabled > 0) {
                    ?>
                    <a href="<?php echo $ADMIN ?>?ADD=302" class="<?php echo $accid_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-phone-alt"></i></span>
                        <span><?php echo _QXZ("AC-CID"); ?></span>
                    </a>
                    <?php
                    }
                    ?>
                </div>
                <?php
                }
                ?>
            </div>

            <!-- Lists Navigation -->
            <?php
            if ($SSoutbound_autodial_active > 0) {
            ?>
            <div class="nav-item">
                <a href="<?php echo $ADMIN ?>?ADD=100" class="nav-link <?php echo $lists_hh ?>">
                    <span class="nav-icon"><?php echo $lists_icon ?></span>
                    <span class="nav-text"><?php echo _QXZ("Lists"); ?></span>
                </a>
                
                <?php
                if (strlen($lists_hh) > 25) {
                    $list_sh = ($sh == 'list') ? "subnav-active" : "subnav-item";
                    $new_sh = ($sh == 'new') ? "subnav-active" : "subnav-item";
                    $search_sh = ($sh == 'search') ? "subnav-active" : "subnav-item";
                    $lead_sh = ($sh == 'lead') ? "subnav-active" : "subnav-item";
                    $load_sh = ($sh == 'load') ? "subnav-active" : "subnav-item";
                    $dnc_sh = ($sh == 'dnc') ? "subnav-active" : "subnav-item";
                    $custom_sh = ($sh == 'custom') ? "subnav-active" : "subnav-item";
                    $cpcust_sh = ($sh == 'cpcust') ? "subnav-active" : "subnav-item";
                    $droplist_sh = ($sh == 'droplist') ? "subnav-active" : "subnav-item";

                    if ($LOGdelete_from_dnc > 0) {
                        $DNClink = _QXZ("Add-Delete DNC Number");
                    } else {
                        $DNClink = _QXZ("Add DNC Number");
                    }
                ?>
                <div class="subnav-container">
                    <a href="<?php echo $ADMIN ?>?ADD=100" class="<?php echo $list_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-list"></i></span>
                        <span><?php echo _QXZ("Show Lists"); ?></span>
                    </a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=111" class="<?php echo $new_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-plus"></i></span>
                        <span><?php echo _QXZ("Add A New List"); ?></span>
                    </a>
                    <?php } ?>
                    <a href="admin_search_lead.php" class="<?php echo $search_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-search"></i></span>
                        <span><?php echo _QXZ("Search For A Lead"); ?></span>
                    </a>
                    <a href="admin_modify_lead.php" class="<?php echo $lead_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-user-plus"></i></span>
                        <span><?php echo _QXZ("Add A New Lead"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=121" class="<?php echo $dnc_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-phone-slash"></i></span>
                        <span><?php echo $DNClink; ?></span>
                    </a>
                    <a href="./admin_listloader_fourth_gen.php" class="<?php echo $load_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-upload"></i></span>
                        <span><?php echo _QXZ("Load New Leads"); ?></span>
                    </a>
                    <?php
                    if ($SScustom_fields_enabled > 0) {
                        $admin_lists_custom = 'admin_lists_custom.php';
                    ?>
                    <a href="./<?php echo $admin_lists_custom ?>" class="<?php echo $custom_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-columns"></i></span>
                        <span><?php echo _QXZ("List Custom Fields"); ?></span>
                    </a>
                    <a href="./<?php echo $admin_lists_custom ?>?action=COPY_FIELDS_FORM" class="<?php echo $cpcust_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-copy"></i></span>
                        <span><?php echo _QXZ("Copy Custom Fields"); ?></span>
                    </a>
                    <?php
                    }
                    if ($SSenable_drop_lists > 0) {
                    ?>
                    <a href="<?php echo $ADMIN ?>?ADD=130" class="<?php echo $droplist_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-filter"></i></span>
                        <span><?php echo _QXZ("Drop Lists"); ?></span>
                    </a>
                    <?php
                    }
                    ?>
                </div>
                <?php
                }
                ?>
            </div>
            <?php
            }
            ?>

            <!-- QC Navigation -->
            <?php
            if (($SSqc_features_active == '1') && ($qc_auth == '1')) {
            ?>
            <div class="nav-item">
                <a href="<?php echo $ADMIN ?>?ADD=100000000000000" class="nav-link <?php echo $qc_hh ?>">
                    <span class="nav-icon"><?php echo $qc_icon ?></span>
                    <span class="nav-text"><?php echo _QXZ("Quality Control"); ?></span>
                </a>
                
                <?php
                if (strlen($qc_hh) > 25) {
                    $campaign_sh = ($qc_display_group_type == "CAMPAIGN") ? "subnav-active" : "subnav-item";
                    $ingroup_sh = ($qc_display_group_type == "INGROUP") ? "subnav-active" : "subnav-item";
                    $list_sh = ($qc_display_group_type == "LIST") ? "subnav-active" : "subnav-item";
                    $scorecard_sh = ($qc_display_group_type == "SCORECARD") ? "subnav-active" : "subnav-item";
                    $modify_sh = ($sh == 'modify') ? "subnav-active" : "subnav-item";
                ?>
                <div class="subnav-container">
                    <a href="<?php echo $ADMIN ?>?ADD=100000000000000&qc_display_group_type=CAMPAIGN" class="<?php echo $campaign_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-bullhorn"></i></span>
                        <span><?php echo _QXZ("QC Calls by Campaign"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=100000000000000&qc_display_group_type=LIST" class="<?php echo $list_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-list"></i></span>
                        <span><?php echo _QXZ("QC Calls by List"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=100000000000000&qc_display_group_type=INGROUP" class="<?php echo $ingroup_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-phone-alt"></i></span>
                        <span><?php echo _QXZ("QC Calls by Ingroup"); ?></span>
                    </a>
                    <a href="qc_scorecards.php" class="<?php echo $scorecard_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-clipboard-check"></i></span>
                        <span><?php echo _QXZ("Show QC Scorecards"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=341111111111111" class="<?php echo $modify_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-edit"></i></span>
                        <span><?php echo _QXZ("Modify QC Codes"); ?></span>
                    </a>
                </div>
                <?php
                }
                ?>
            </div>
            <?php
            }
            ?>

            <!-- Scripts Navigation -->
            <div class="nav-item">
                <a href="<?php echo $ADMIN ?>?ADD=1000000" class="nav-link <?php echo $scripts_hh ?>">
                    <span class="nav-icon"><?php echo $scripts_icon ?></span>
                    <span class="nav-text"><?php echo _QXZ("Scripts"); ?></span>
                </a>
                
                <?php
                if (strlen($scripts_hh) > 25) {
                    $list_sh = ($sh == 'list') ? "subnav-active" : "subnav-item";
                    $new_sh = ($sh == 'new') ? "subnav-active" : "subnav-item";
                ?>
                <div class="subnav-container">
                    <a href="<?php echo $ADMIN ?>?ADD=1000000" class="<?php echo $list_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-file-alt"></i></span>
                        <span><?php echo _QXZ("Show Scripts"); ?></span>
                    </a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=1111111" class="<?php echo $new_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-plus"></i></span>
                        <span><?php echo _QXZ("Add A New Script"); ?></span>
                    </a>
                    <?php } ?>
                </div>
                <?php
                }
                ?>
            </div>

            <!-- Filters Navigation -->
            <?php
            if ($SSoutbound_autodial_active > 0) {
            ?>
            <div class="nav-item">
                <a href="<?php echo $ADMIN ?>?ADD=10000000" class="nav-link <?php echo $filters_hh ?>">
                    <span class="nav-icon"><?php echo $filters_icon ?></span>
                    <span class="nav-text"><?php echo _QXZ("Filters"); ?></span>
                </a>
                
                <?php
                if (strlen($filters_hh) > 25) {
                    $list_sh = ($sh == 'list') ? "subnav-active" : "subnav-item";
                    $new_sh = ($sh == 'new') ? "subnav-active" : "subnav-item";
                ?>
                <div class="subnav-container">
                    <a href="<?php echo $ADMIN ?>?ADD=10000000" class="<?php echo $list_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-filter"></i></span>
                        <span><?php echo _QXZ("Show Filters"); ?></span>
                    </a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=11111111" class="<?php echo $new_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-plus"></i></span>
                        <span><?php echo _QXZ("Add A New Filter"); ?></span>
                    </a>
                    <?php } ?>
                </div>
                <?php
                }
                ?>
            </div>
            <?php
            }
            ?>

            <!-- Inbound Navigation -->
            <div class="nav-item">
                <a href="<?php echo $ADMIN ?>?ADD=1001" class="nav-link <?php echo $ingroups_hh ?>">
                    <span class="nav-icon"><?php echo $inbound_icon ?></span>
                    <span class="nav-text"><?php echo _QXZ("Inbound"); ?></span>
                </a>
                
                <?php
                if (strlen($ingroups_hh) > 25) {
                    $listIG_sh = ($sh == 'listIG') ? "subnav-active" : "subnav-item";
                    $newIG_sh = ($sh == 'newIG') ? "subnav-active" : "subnav-item";
                    $copyIG_sh = ($sh == 'copyIG') ? "subnav-active" : "subnav-item";
                    $listEG_sh = ($sh == 'listEG') ? "subnav-active" : "subnav-item";
                    $newEG_sh = ($sh == 'newEG') ? "subnav-active" : "subnav-item";
                    $copyEG_sh = ($sh == 'copyEG') ? "subnav-active" : "subnav-item";
                    $listCG_sh = ($sh == 'listCG') ? "subnav-active" : "subnav-item";
                    $newCG_sh = ($sh == 'newCG') ? "subnav-active" : "subnav-item";
                    $copyCG_sh = ($sh == 'copyCG') ? "subnav-active" : "subnav-item";
                    $listDID_sh = ($sh == 'listDID') ? "subnav-active" : "subnav-item";
                    $newDID_sh = ($sh == 'newDID') ? "subnav-active" : "subnav-item";
                    $copyDID_sh = ($sh == 'copyDID') ? "subnav-active" : "subnav-item";
                    $didRA_sh = ($sh == 'didRA') ? "subnav-active" : "subnav-item";
                    $listCM_sh = ($sh == 'listCM') ? "subnav-active" : "subnav-item";
                    $newCM_sh = ($sh == 'newCM') ? "subnav-active" : "subnav-item";
                    $copyCM_sh = ($sh == 'copyCM') ? "subnav-active" : "subnav-item";
                    $listFPG_sh = ($sh == 'listFPG') ? "subnav-active" : "subnav-item";
                    $newFPG_sh = ($sh == 'newFPG') ? "subnav-active" : "subnav-item";
                    $addFPG_sh = ($sh == 'addFPG') ? "subnav-active" : "subnav-item";

                    if ($LOGdelete_from_dnc > 0) {
                        $FPGlink = _QXZ("Add-Delete FPG Number");
                    } else {
                        $FPGlink = _QXZ("Add FPG Number");
                    }
                ?>
                <div class="subnav-container">
                    <a href="<?php echo $ADMIN ?>?ADD=1000" class="<?php echo $listIG_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-phone-alt"></i></span>
                        <span><?php echo _QXZ("Show In-Groups"); ?></span>
                    </a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=1111" class="<?php echo $newIG_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-plus"></i></span>
                        <span><?php echo _QXZ("Add A New In-Group"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=1211" class="<?php echo $copyIG_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-copy"></i></span>
                        <span><?php echo _QXZ("Copy In-Group"); ?></span>
                    </a>
                    <?php } ?>
                    
                    <div class="subnav-divider"></div>
                    
                    <?php
                    if ($SSemail_enabled > 0) {
                    ?>
                    <a href="<?php echo $ADMIN ?>?ADD=1800" class="<?php echo $listEG_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-envelope"></i></span>
                        <span><?php echo _QXZ("Show Email Groups"); ?></span>
                    </a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=1811" class="<?php echo $newEG_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-plus"></i></span>
                        <span><?php echo _QXZ("Add New Email Group"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=1911" class="<?php echo $copyEG_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-copy"></i></span>
                        <span><?php echo _QXZ("Copy Email Group"); ?></span>
                    </a>
                    <?php } ?>
                    
                    <div class="subnav-divider"></div>
                    <?php
                    }
                    
                    if ($SSchat_enabled > 0) {
                    ?>
                    <a href="<?php echo $ADMIN ?>?ADD=1900" class="<?php echo $listCG_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-comments"></i></span>
                        <span><?php echo _QXZ("Show Chat Groups"); ?></span>
                    </a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=18111" class="<?php echo $newCG_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-plus"></i></span>
                        <span><?php echo _QXZ("Add New Chat Group"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=19111" class="<?php echo $copyCG_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-copy"></i></span>
                        <span><?php echo _QXZ("Copy Chat Group"); ?></span>
                    </a>
                    <?php } ?>
                    
                    <div class="subnav-divider"></div>
                    <?php
                    }
                    ?>
                    
                    <a href="<?php echo $ADMIN ?>?ADD=1300" class="<?php echo $listDID_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-phone"></i></span>
                        <span><?php echo _QXZ("Show DIDs"); ?></span>
                    </a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=1311" class="<?php echo $newDID_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-plus"></i></span>
                        <span><?php echo _QXZ("Add A New DID"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=1411" class="<?php echo $copyDID_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-copy"></i></span>
                        <span><?php echo _QXZ("Copy DID"); ?></span>
                    </a>
                    <?php
                    }
                    if ($SSdid_ra_extensions_enabled > 0) {
                    ?>
                    <a href="<?php echo $ADMIN ?>?ADD=1320" class="<?php echo $didRA_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-plug"></i></span>
                        <span><?php echo _QXZ("RA Extensions"); ?></span>
                    </a>
                    <?php
                    }
                    ?>
                    
                    <div class="subnav-divider"></div>
                    
                    <a href="<?php echo $ADMIN ?>?ADD=1500" class="<?php echo $listCM_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-sitemap"></i></span>
                        <span><?php echo _QXZ("Show Call Menus"); ?></span>
                    </a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=1511" class="<?php echo $newCM_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-plus"></i></span>
                        <span><?php echo _QXZ("Add A New Call Menu"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=1611" class="<?php echo $copyCM_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-copy"></i></span>
                        <span><?php echo _QXZ("Copy Call Menu"); ?></span>
                    </a>
                    <?php } ?>
                    
                    <div class="subnav-divider"></div>
                    
                    <a href="<?php echo $ADMIN ?>?ADD=1700" class="<?php echo $listFPG_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-filter"></i></span>
                        <span><?php echo _QXZ("Filter Phone Groups"); ?></span>
                    </a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=1711" class="<?php echo $newFPG_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-plus"></i></span>
                        <span><?php echo _QXZ("Add Filter Phone Group"); ?></span>
                    </a>
                    <?php } ?>
                    <a href="<?php echo $ADMIN ?>?ADD=171" class="<?php echo $addFPG_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-phone-slash"></i></span>
                        <span><?php echo $FPGlink; ?></span>
                    </a>
                </div>
                <?php
                }
                ?>
            </div>

            <!-- User Groups Navigation -->
            <div class="nav-item">
                <a href="<?php echo $ADMIN ?>?ADD=100000" class="nav-link <?php echo $usergroups_hh ?>">
                    <span class="nav-icon"><?php echo $usergroups_icon ?></span>
                    <span class="nav-text"><?php echo _QXZ("User Groups"); ?></span>
                </a>
                
                <?php
                if (strlen($usergroups_hh) > 25) {
                    $list_sh = ($sh == 'list') ? "subnav-active" : "subnav-item";
                    $new_sh = ($sh == 'new') ? "subnav-active" : "subnav-item";
                    $hour_sh = ($sh == 'hour') ? "subnav-active" : "subnav-item";
                    $bulk_sh = ($sh == 'bulk') ? "subnav-active" : "subnav-item";
                ?>
                <div class="subnav-container">
                    <a href="<?php echo $ADMIN ?>?ADD=100000" class="<?php echo $list_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-users"></i></span>
                        <span><?php echo _QXZ("Show User Groups"); ?></span>
                    </a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=111111" class="<?php echo $new_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-plus"></i></span>
                        <span><?php echo _QXZ("Add A New User Group"); ?></span>
                    </a>
                    <?php } ?>
                    <a href="group_hourly_stats.php" class="<?php echo $hour_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-chart-line"></i></span>
                        <span><?php echo _QXZ("Group Hourly Report"); ?></span>
                    </a>
                    <a href="user_group_bulk_change.php" class="<?php echo $bulk_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-exchange-alt"></i></span>
                        <span><?php echo _QXZ("Bulk Group Change"); ?></span>
                    </a>
                </div>
                <?php
                }
                ?>
            </div>

            <!-- Remote Agents Navigation -->
            <div class="nav-item">
                <a href="<?php echo $ADMIN ?>?ADD=10000" class="nav-link <?php echo $remoteagent_hh ?>">
                    <span class="nav-icon"><?php echo $remoteagents_icon ?></span>
                    <span class="nav-text"><?php echo _QXZ("Remote Agents"); ?></span>
                </a>
                
                <?php
                if (strlen($remoteagent_hh) > 25) {
                    $list_sh = ($sh == 'list') ? "subnav-active" : "subnav-item";
                    $new_sh = ($sh == 'new') ? "subnav-active" : "subnav-item";
                    $listEG_sh = ($sh == 'listEG') ? "subnav-active" : "subnav-item";
                    $newEG_sh = ($sh == 'newEG') ? "subnav-active" : "subnav-item";
                ?>
                <div class="subnav-container">
                    <a href="<?php echo $ADMIN ?>?ADD=10000" class="<?php echo $list_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-laptop"></i></span>
                        <span><?php echo _QXZ("Show Remote Agents"); ?></span>
                    </a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=11111" class="<?php echo $new_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-plus"></i></span>
                        <span><?php echo _QXZ("Add New Remote Agents"); ?></span>
                    </a>
                    <?php } ?>
                    <a href="<?php echo $ADMIN ?>?ADD=12000" class="<?php echo $listEG_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-phone"></i></span>
                        <span><?php echo _QXZ("Show Extension Groups"); ?></span>
                    </a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=12111" class="<?php echo $newEG_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-plus"></i></span>
                        <span><?php echo _QXZ("Add Extension Group"); ?></span>
                    </a>
                    <?php } ?>
                </div>
                <?php
                }
                ?>
            </div>

            <!-- Admin Navigation -->
            <div class="nav-item">
                <a href="<?php echo $ADMIN ?>?ADD=999998" class="nav-link <?php echo $admin_hh ?>">
                    <span class="nav-icon"><?php echo $admin_icon ?></span>
                    <span class="nav-text"><?php echo _QXZ("Admin"); ?></span>
                </a>
                
                <?php
                if (strlen($admin_hh) > 25) {
                    $times_sh = ($sh == 'times') ? "subnav-active" : "subnav-item";
                    $shifts_sh = ($sh == 'shifts') ? "subnav-active" : "subnav-item";
                    $templates_sh = ($sh == 'templates') ? "subnav-active" : "subnav-item";
                    $carriers_sh = ($sh == 'carriers') ? "subnav-active" : "subnav-item";
                    $phones_sh = ($sh == 'phones') ? "subnav-active" : "subnav-item";
                    $server_sh = ($sh == 'server') ? "subnav-active" : "subnav-item";
                    $conference_sh = ($sh == 'conference') ? "subnav-active" : "subnav-item";
                    $settings_sh = ($sh == 'settings') ? "subnav-active" : "subnav-item";
                    $label_sh = ($sh == 'label') ? "subnav-active" : "subnav-item";
                    $colors_sh = ($sh == 'colors') ? "subnav-active" : "subnav-item";
                    $status_sh = ($sh == 'status') ? "subnav-active" : "subnav-item";
                    $audio_sh = ($sh == 'audio') ? "subnav-active" : "subnav-item";
                    $moh_sh = ($sh == 'moh') ? "subnav-active" : "subnav-item";
                    $languages_sh = ($sh == 'languages') ? "subnav-active" : "subnav-item";
                    $soundboard_sh = ($sh == 'soundboard') ? "subnav-active" : "subnav-item";
                    $vm_sh = ($sh == 'vm') ? "subnav-active" : "subnav-item";
                    $tts_sh = ($sh == 'tts') ? "subnav-active" : "subnav-item";
                    $cc_sh = ($sh == 'cc') ? "subnav-active" : "subnav-item";
                    $cts_sh = ($sh == 'cts') ? "subnav-active" : "subnav-item";
                    $sc_sh = ($sh == 'sc') ? "subnav-active" : "subnav-item";
                    $sg_sh = ($sh == 'sg') ? "subnav-active" : "subnav-item";
                    $cg_sh = ($sh == 'cg') ? "subnav-active" : "subnav-item";
                    $vmmg_sh = ($sh == 'vmmg') ? "subnav-active" : "subnav-item";
                    $qg_sh = ($sh == 'qg') ? "subnav-active" : "subnav-item";
                    $emails_sh = ($sh == 'emails') ? "subnav-active" : "subnav-item";
                    $ar_sh = ($sh == 'ar') ? "subnav-active" : "subnav-item";
                    $il_sh = ($sh == 'il') ? "subnav-active" : "subnav-item";
                ?>
                <div class="subnav-container">
                    <a href="<?php echo $ADMIN ?>?ADD=100000000" class="<?php echo $times_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-clock"></i></span>
                        <span><?php echo _QXZ("Call Times"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=130000000" class="<?php echo $shifts_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-calendar-alt"></i></span>
                        <span><?php echo _QXZ("Shifts"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=10000000000" class="<?php echo $phones_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-phone"></i></span>
                        <span><?php echo _QXZ("Phones"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=130000000000" class="<?php echo $templates_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-file-alt"></i></span>
                        <span><?php echo _QXZ("Templates"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=140000000000" class="<?php echo $carriers_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-globe"></i></span>
                        <span><?php echo _QXZ("Carriers"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=100000000000" class="<?php echo $server_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-server"></i></span>
                        <span><?php echo _QXZ("Servers"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=1000000000000" class="<?php echo $conference_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-users"></i></span>
                        <span><?php echo _QXZ("Conferences"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=311111111111111" class="<?php echo $settings_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-cog"></i></span>
                        <span><?php echo _QXZ("System Settings"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=180000000000" class="<?php echo $label_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-tag"></i></span>
                        <span><?php echo _QXZ("Screen Labels"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=182000000000" class="<?php echo $colors_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-palette"></i></span>
                        <span><?php echo _QXZ("Screen Colors"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=321111111111111" class="<?php echo $status_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-info-circle"></i></span>
                        <span><?php echo _QXZ("System Statuses"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=193000000000" class="<?php echo $sg_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-tags"></i></span>
                        <span><?php echo _QXZ("Status Groups"); ?></span>
                    </a>
                    <?php
                    if ($SScampaign_cid_areacodes_enabled > 0) {
                    ?>
                    <a href="<?php echo $ADMIN ?>?ADD=196000000000" class="<?php echo $cg_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-phone"></i></span>
                        <span><?php echo _QXZ("CID Groups"); ?></span>
                    </a>
                    <?php
                    }
                    ?>
                    <a href="<?php echo $ADMIN ?>?ADD=170000000000" class="<?php echo $vm_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-voicemail"></i></span>
                        <span><?php echo _QXZ("Voicemail"); ?></span>
                    </a>
                    <?php
                    if ($SSemail_enabled > 0) {
                    ?>
                    <a href="admin_email_accounts.php" class="<?php echo $emails_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-envelope"></i></span>
                        <span><?php echo _QXZ("Email Accounts"); ?></span>
                    </a>
                    <?php
                    }
                    if (($sounds_central_control_active > 0) or ($SSsounds_central_control_active > 0)) {
                    ?>
                    <a href="audio_store.php" class="<?php echo $audio_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-music"></i></span>
                        <span><?php echo _QXZ("Audio Store"); ?></span>
                    </a>
                    <a href="<?php echo $ADMIN ?>?ADD=160000000000" class="<?php echo $moh_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-headphones"></i></span>
                        <span><?php echo _QXZ("Music On Hold"); ?></span>
                    </a>
                    <?php
                    if ($SSenable_languages > 0) {
                    ?>
                    <a href="admin_languages.php?ADD=163000000000" class="<?php echo $languages_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-language"></i></span>
                        <span><?php echo _QXZ("Languages"); ?></span>
                    </a>
                    <?php
                    }
                    if ((preg_match("/soundboard/",$SSactive_modules)) or ($SSagent_soundboards > 0)) {
                    ?>
                    <a href="admin_soundboard.php?ADD=162000000000" class="<?php echo $soundboard_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-microphone-alt"></i></span>
                        <span><?php echo _QXZ("Audio Soundboards"); ?></span>
                    </a>
                    <?php
                    }
                    ?>
                    <a href="<?php echo $ADMIN ?>?ADD=197000000000" class="<?php echo $vmmg_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-voicemail"></i></span>
                        <span><?php echo _QXZ("VM Message Groups"); ?></span>
                    </a>
                    <?php
                    }
                    if ($SSenable_tts_integration > 0) {
                    ?>
                    <a href="<?php echo $ADMIN ?>?ADD=150000000000" class="<?php echo $tts_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-volume-up"></i></span>
                        <span><?php echo _QXZ("Text To Speech"); ?></span>
                    </a>
                    <?php
                    }
                    if ($SScallcard_enabled > 0) {
                    ?>
                    <a href="callcard_admin.php" class="<?php echo $cc_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-id-card"></i></span>
                        <span><?php echo _QXZ("CallCard Admin"); ?></span>
                    </a>
                    <?php
                    }
                    if ($SScontacts_enabled > 0) {
                    ?>
                    <a href="<?php echo $ADMIN ?>?ADD=190000000000" class="<?php echo $cts_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-address-book"></i></span>
                        <span><?php echo _QXZ("Contacts"); ?></span>
                    </a>
                    <?php
                    }
                    ?>
                    <a href="<?php echo $ADMIN ?>?ADD=192000000000" class="<?php echo $sc_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-cogs"></i></span>
                        <span><?php echo _QXZ("Settings Containers"); ?></span>
                    </a>
                    <?php
                    if ($SSenable_auto_reports > 0) {
                    ?>
                    <a href="<?php echo $ADMIN ?>?ADD=194000000000" class="<?php echo $ar_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-chart-bar"></i></span>
                        <span><?php echo _QXZ("Automated Reports"); ?></span>
                    </a>
                    <?php
                    }
                    if ($SSallow_ip_lists > 0) {
                    ?>
                    <a href="<?php echo $ADMIN ?>?ADD=195000000000" class="<?php echo $il_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-shield-alt"></i></span>
                        <span><?php echo _QXZ("IP Lists"); ?></span>
                    </a>
                    <?php
                    }
                    ?>
                    <a href="<?php echo $ADMIN ?>?ADD=198000000000" class="<?php echo $qg_sh ?>">
                        <span class="subnav-icon"><i class="fas fa-users-cog"></i></span>
                        <span><?php echo _QXZ("Queue Groups"); ?></span>
                    </a>
                </div>
                <?php
                }
                ?>
            </div>
            <?php
            } else {
                // Limited access menu
                if ($reports_only_user > 0) {
                ?>
                <!-- Reports Navigation -->
                <div class="nav-item">
                    <a href="<?php echo $ADMIN ?>?ADD=999999" class="nav-link <?php echo $reports_hh ?>">
                        <span class="nav-icon"><?php echo $reports_icon ?></span>
                        <span class="nav-text"><?php echo _QXZ("Reports"); ?></span>
                    </a>
                </div>
                <?php
                } else {
                    if (($SSqc_features_active == '1') && ($qc_auth == '1')) {
                    ?>
                    <!-- QC Navigation -->
                    <div class="nav-item">
                        <a href="<?php echo $ADMIN ?>?ADD=100000000000000" class="nav-link <?php echo $qc_hh ?>">
                            <span class="nav-icon"><?php echo $qc_icon ?></span>
                            <span class="nav-text"><?php echo _QXZ("Quality Control"); ?></span>
                        </a>
                        
                        <?php
                        if (strlen($qc_hh) > 25) {
                        ?>
                        <div class="subnav-container">
                            <a href="<?php echo $ADMIN ?>?ADD=100000000000000" class="subnav-item">
                                <span class="subnav-icon"><i class="fas fa-bullhorn"></i></span>
                                <span><?php echo _QXZ("Show QC Campaigns"); ?></span>
                            </a>
                            <a href="<?php echo $ADMIN ?>?ADD=100000000000000" class="subnav-item">
                                <span class="subnav-icon"><i class="fas fa-tasks"></i></span>
                                <span><?php echo _QXZ("Enter QC Queue"); ?></span>
                            </a>
                            <a href="<?php echo $ADMIN ?>?ADD=341111111111111" class="subnav-item">
                                <span class="subnav-icon"><i class="fas fa-edit"></i></span>
                                <span><?php echo _QXZ("Modify QC Codes"); ?></span>
                            </a>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                    <?php
                    }
                }
            }
            ?>
        </div>
    </nav>
    
    <div class="sidebar-footer">
        <div class="user-info">
            <span class="user-name"><?php echo $PHP_AUTH_USER ?></span>
            <a href="<?php echo $ADMIN ?>?force_logout=1" class="logout-link">
                <i class="fas fa-sign-out-alt"></i>
                <span><?php echo _QXZ("Logout"); ?></span>
            </a>
        </div>
    </div>
</div>

<!-- Main Content Area -->
<div class="main-content">
    <div class="top-bar">
        <div class="breadcrumb">
            <a href="<?php echo $admin_home_url_LU ?>" class="breadcrumb-item">
                <i class="fas fa-home"></i>
                <span><?php echo _QXZ("HOME"); ?></span>
            </a>
            <a href="../agc/timeclock.php?referrer=admin" class="breadcrumb-item">
                <i class="fas fa-clock"></i>
                <span><?php echo _QXZ("Timeclock"); ?></span>
            </a>
            <a href="manager_chat_interface.php" class="breadcrumb-item">
                <i class="fas fa-comments"></i>
                <span><?php echo _QXZ("Chat"); ?></span>
            </a>
            <?php
            if ($SSenable_languages == '1') {
            ?>
            <a href="<?php echo $ADMIN ?>?ADD=999989" class="breadcrumb-item">
                <i class="fas fa-language"></i>
                <span><?php echo _QXZ("Change language"); ?></span>
            </a>
            <?php
            }
            ?>
        </div>
        <div class="datetime">
            <i class="fas fa-calendar-alt"></i>
            <span><?php echo date("l F j, Y G:i:s A"); ?></span>
        </div>
    </div>
    
    <div class="content-area">
        <!-- Content will be dynamically inserted here based on the page -->
    </div>
</div>

<!-- Audio Chooser Span (Hidden by Default) -->
<span style="position:absolute;left:300px;top:30px;z-index:1;visibility:hidden;" id="audio_chooser_span"></span>

<style>
/* Sidebar Styles */
.sidebar-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100vh;
    background: linear-gradient(180deg, #<?php echo $SSmenu_background; ?>, #<?php echo darken_color($SSmenu_background, 20); ?>);
    color: white;
    overflow-y: auto;
    z-index: 1000;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.sidebar-header {
    padding: 20px;
    text-align: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.logo-link {
    display: inline-block;
    margin-bottom: 15px;
    transition: transform 0.3s ease;
}

.logo-link:hover {
    transform: scale(1.05);
}

.logo-img {
    max-width: 100%;
    height: auto;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
}

.sidebar-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.sidebar-nav {
    padding: 10px 0;
}

.nav-section {
    margin-bottom: 20px;
}

.nav-item {
    margin-bottom: 5px;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.nav-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.1);
    transition: left 0.3s ease;
}

.nav-link:hover::before {
    left: 0;
}

.nav-link:hover {
    color: white;
    background: rgba(255, 255, 255, 0.1);
}

.nav-link.head_style_selected {
    color: white;
    background: rgba(255, 255, 255, 0.2);
    border-left: 4px solid white;
}

.nav-icon {
    margin-right: 12px;
    font-size: 16px;
    width: 20px;
    text-align: center;
}

.nav-text {
    font-weight: 500;
    font-size: 14px;
}

.subnav-container {
    background: rgba(0, 0, 0, 0.2);
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.nav-item.expanded .subnav-container {
    max-height: 1000px;
}

.subnav-item, .subnav-active {
    display: flex;
    align-items: center;
    padding: 10px 20px 10px 52px;
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    font-size: 13px;
    transition: all 0.3s ease;
}

.subnav-item:hover, .subnav-active:hover {
    color: white;
    background: rgba(255, 255, 255, 0.05);
}

.subnav-active {
    color: white;
    background: rgba(255, 255, 255, 0.1);
    border-left: 3px solid rgba(255, 255, 255, 0.5);
}

.subnav-icon {
    margin-right: 10px;
    font-size: 14px;
    width: 18px;
    text-align: center;
}

.subnav-divider {
    height: 1px;
    background: rgba(255, 255, 255, 0.1);
    margin: 8px 20px;
}

.sidebar-footer {
    padding: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    margin-top: auto;
}

.user-info {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.user-name {
    font-weight: 600;
    margin-bottom: 10px;
    font-size: 14px;
}

.logout-link {
    display: flex;
    align-items: center;
    gap: 8px;
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    font-size: 13px;
    transition: color 0.3s ease;
}

.logout-link:hover {
    color: white;
}

/* Main Content Styles */
.main-content {
    margin-left: 250px;
    min-height: 100vh;
    background-color: #<?php echo $SSframe_background; ?>;
}

.top-bar {
    background: linear-gradient(135deg, #<?php echo $SSmenu_background; ?>, #<?php echo darken_color($SSmenu_background, 10); ?>);
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.breadcrumb {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
    gap: 5px;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    font-size: 14px;
    transition: color 0.3s ease;
}

.breadcrumb-item:hover {
    color: white;
}

.breadcrumb-item:not(:last-child)::after {
    content: '/';
    margin-left: 15px;
    color: rgba(255, 255, 255, 0.5);
}

.datetime {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
}

.content-area {
    padding: 20px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .sidebar-container {
        transform: translateX(-100%);
    }
    
    .sidebar-container.active {
        transform: translateX(0);
    }
    
    .main-content {
        margin-left: 0;
    }
    
    .top-bar {
        padding: 10px 15px;
    }
    
    .breadcrumb {
        gap: 10px;
    }
    
    .breadcrumb-item {
        font-size: 12px;
    }
    
    .datetime {
        font-size: 12px;
    }
    
    .menu-toggle {
        display: block;
        position: fixed;
        top: 15px;
        left: 15px;
        z-index: 1001;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        border-radius: 4px;
        padding: 8px 12px;
        cursor: pointer;
    }
}

@media (min-width: 769px) {
    .menu-toggle {
        display: none;
    }
}

/* Animation for expanding/collapsing subnav */
.nav-item .nav-link::after {
    content: '\f107';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    margin-left: auto;
    transition: transform 0.3s ease;
}

.nav-item.expanded .nav-link::after {
    transform: rotate(180deg);
}
</style>

<script>
// Toggle sidebar on mobile
document.addEventListener('DOMContentLoaded', function() {
    // Create menu toggle button for mobile
    const menuToggle = document.createElement('button');
    menuToggle.className = 'menu-toggle';
    menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
    document.body.appendChild(menuToggle);
    
    const sidebar = document.querySelector('.sidebar-container');
    
    menuToggle.addEventListener('click', function() {
        sidebar.classList.toggle('active');
    });
    
    // Toggle subnav on click
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        const subnav = item.querySelector('.subnav-container');
        if (subnav) {
            const navLink = item.querySelector('.nav-link');
            navLink.addEventListener('click', function(e) {
                e.preventDefault();
                item.classList.toggle('expanded');
            });
        }
    });
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('active');
        }
    });
});
</script>