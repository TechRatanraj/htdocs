<?php
# admin_header.php - VICIDIAL administration header (Modernized)
#
# Copyright (C) 2023  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
# 

 $HTMLcolors = 'IndianRed,CD5C5C|LightCoral,F08080|Salmon,FA8072|DarkSalmon,E9967A|LightSalmon,FFA07A|Crimson,DC143C|Red,FF0000|FireBrick,B22222|DarkRed,8B0000|Pink,FFC0CB|LightPink,FFB6C1|HotPink,FF69B4|DeepPink,FF1493|MediumVioletRed,C71585|PaleVioletRed,DB7093|LightSalmon,FFA07A|Coral,FF7F50|Tomato,FF6347|OrangeRed,FF4500|DarkOrange,FF8C00|Orange,FFA500|Gold,FFD700|Yellow,FFFF00|LightYellow,FFFFE0|LemonChiffon,FFFACD|LightGoldenrodYellow,FAFAD2|PapayaWhip,FFEFD5|Moccasin,FFE4B5|PeachPuff,FFDAB9|PaleGoldenrod,EEE8AA|Khaki,F0E68C|DarkKhaki,BDB76B|Lavender,E6E6FA|Thistle,D8BFD8|Plum,DDA0DD|Violet,EE82EE|Orchid,DA70D6|Fuchsia,FF00FF|Magenta,FF00FF|MediumOrchid,BA55D3|MediumPurple,9370DB|RebeccaPurple,663399|BlueViolet,8A2BE2|DarkViolet,9400D3|DarkOrchid,9932CC|DarkMagenta,8B008B|Purple,800080|Indigo,4B0082|SlateBlue,6A5ACD|DarkSlateBlue,483D8B|MediumSlateBlue,7B68EE|GreenYellow,ADFF2F|Chartreuse,7FFF00|LawnGreen,7CFC00|Lime,00FF00|LimeGreen,32CD32|PaleGreen,98FB98|LightGreen,90EE90|MediumSpringGreen,00FA9A|SpringGreen,00FF7F|MediumSeaGreen,3CB371|SeaGreen,2E8B57|ForestGreen,228B22|Green,008000|DarkGreen,006400|YellowGreen,9ACD32|OliveDrab,6B8E23|Olive,808000|DarkOliveGreen,556B2F|MediumAquamarine,66CDAA|DarkSeaGreen,8FBC8B|LightSeaGreen,20B2AA|DarkCyan,008B8B|Teal,008080|Aqua,00FFFF|Cyan,00FFFF|LightCyan,E0FFFF|PaleTurquoise,AFEEEE|Aquamarine,7FFFD4|Turquoise,40E0D0|MediumTurquoise,48D1CC|DarkTurquoise,00CED1|CadetBlue,5F9EA0|SteelBlue,4682B4|LightSteelBlue,B0C4DE|PowderBlue,B0E0E6|LightBlue,ADD8E6|SkyBlue,87CEEB|LightSkyBlue,87CEFA|DeepSkyBlue,00BFFF|DodgerBlue,1E90FF|CornflowerBlue,6495ED|MediumSlateBlue,7B68EE|RoyalBlue,4169E1|Blue,0000FF|MediumBlue,0000CD|DarkBlue,00008B|Navy,000080|MidnightBlue,191970|Cornsilk,FFF8DC|BlanchedAlmond,FFEBCD|Bisque,FFE4C4|NavajoWhite,FFDEAD|Wheat,F5DEB3|BurlyWood,DEB887|Tan,D2B48C|RosyBrown,BC8F8F|SandyBrown,F4A460|Goldenrod,DAA520|DarkGoldenrod,B8860B|Peru,CD853F|Chocolate,D2691E|SaddleBrown,8B4513|Sienna,A0522D|Brown,A52A2A|Maroon,800000|White,FFFFFF|Snow,FFFAFA|HoneyDew,F0FFF0|MintCream,F5FFFA|Azure,F0FFFF|AliceBlue,F0F8FF|GhostWhite,F8F8FF|WhiteSmoke,F5F5F5|SeaShell,FFF5EE|Beige,F5F5DC|OldLace,FDF5E6|FloralWhite,FFFAF0|Ivory,FFFFF0|AntiqueWhite,FAEBD7|Linen,FAF0E6|LavenderBlush,FFF0F5|MistyRose,FFE4E1|Gainsboro,DCDCDC|LightGray,D3D3D3|Silver,C0C0C0|DarkGray,A9A9A9|Gray,808080|DimGray,696969|LightSlateGray,778899|SlateGray,708090|DarkSlateGray,2F4F4F|Black,000000';

 $stmt="SELECT admin_home_url,enable_tts_integration,callcard_enabled,custom_fields_enabled,allow_emails,level_8_disable_add,allow_chats,enable_languages,admin_row_click,admin_screen_colors,user_new_lead_limit,user_territories_active,qc_features_active,agent_soundboards,enable_drop_lists,allow_ip_lists,admin_web_directory from system_settings;";
 $rslt=mysql_to_mysqli($stmt, $link);
 $row=mysqli_fetch_row($rslt);
 $admin_home_url_LU =		$row[0];
 $SSenable_tts_integration = $row[1];
 $SScallcard_enabled =		$row[2];
 $SScustom_fields_enabled =	$row[3];
 $SSemail_enabled =			$row[4];
 $SSlevel_8_disable_add =	$row[5];
 $SSchat_enabled =			$row[6];
 $SSenable_languages =		$row[7];
 $SSadmin_row_click =		$row[8];
 $SSadmin_screen_colors =	$row[9];
 $SSuser_new_lead_limit =	$row[10];
 $SSuser_territories_active = $row[11];
 $SSqc_features_active =		$row[12];
 $SSagent_soundboards =		$row[13];
 $SSenable_drop_lists =		$row[14];
 $SSallow_ip_lists =			$row[15];
 $SSadmin_web_directory =	$row[16];

if (strlen($SSadmin_home_url) > 5) {$admin_home_url_LU = $SSadmin_home_url;}
if(!isset($ADMIN)){$ADMIN = "../$SSadmin_web_directory/admin.php";}

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

if ($SSadmin_screen_colors != 'default')
    {
    $stmt = "SELECT menu_background,frame_background,std_row1_background,std_row2_background,std_row3_background,std_row4_background,std_row5_background,alt_row1_background,alt_row2_background,alt_row3_background,web_logo,button_color FROM vicidial_screen_colors where colors_id='$SSadmin_screen_colors';";
    $rslt=mysql_to_mysqli($stmt, $link);
    if ($DB) {echo "$stmt\n";}
    $colors_ct = mysqli_num_rows($rslt);
    if ($colors_ct > 0)
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
        $SSweb_logo =			$row[10];
        $SSbutton_color = 		$row[11];
        }
    }
 $Mhead_color =	$SSstd_row5_background;
 $Mmain_bgcolor = $SSmenu_background;
 $Mhead_color =	$SSstd_row5_background;

 $selected_logo = "./images/vicidial_admin_web_logo.png";
 $selected_small_logo = "./images/vicidial_admin_web_logo.png";
 $logo_new=0;
 $logo_old=0;
 $logo_small_old=0;
if (file_exists('./images/vicidial_admin_web_logo.png')) {$logo_new++;}
if (file_exists('vicidial_admin_web_logo_small.gif')) {$logo_small_old++;}
if (file_exists('vicidial_admin_web_logo.gif')) {$logo_old++;}
if ($SSweb_logo=='default_new')
    {
    $selected_logo = "./images/vicidial_admin_web_logo.png";
    $selected_small_logo = "./images/vicidial_admin_web_logo.png";
    }
if ( ($SSweb_logo=='default_old') and ($logo_old > 0) )
    {
    $selected_logo = "./vicidial_admin_web_logo.gif";
    $selected_small_logo = "./vicidial_admin_web_logo_small.gif";
    }
if ( ($SSweb_logo!='default_new') and ($SSweb_logo!='default_old') )
    {
    if (file_exists("./images/vicidial_admin_web_logo$SSweb_logo")) 
        {
        $selected_logo = "./images/vicidial_admin_web_logo$SSweb_logo";
        $selected_small_logo = "./images/vicidial_admin_web_logo$SSweb_logo";
        }
    }


##### BEGIN populate dynamic header content #####
if ($hh=='users') 
    {
    $users_hh="CLASS=\"head_style_selected\""; $users_fc="$users_font"; $users_bold="$header_selected_bold";
    $users_icon="<img src=\"images/icon_black_users.png\" border=0 alt=\"Users\" width=14 height=14 valign=middle\">";
    }
else 
    {
    $users_hh="CLASS=\"head_style\""; $users_fc='WHITE'; $users_bold="$header_nonselected_bold";
    $users_icon="<img src=\"images/icon_users.png\" border=0 alt=\"Users\" width=14 height=14 valign=middle\">";
    }
if ($hh=='campaigns') 
    {
    $campaigns_hh="CLASS=\"head_style_selected\""; $campaigns_fc="$campaigns_font"; $campaigns_bold="$header_selected_bold";
    $campaigns_icon="<img src=\"images/icon_black_campaigns.png\" border=0 alt=\"Campaigns\" width=14 height=14 valign=middle\">";
    }
else 
    {
    $campaigns_hh="CLASS=\"head_style\""; $campaigns_fc='WHITE'; $campaigns_bold="$header_nonselected_bold";
    $campaigns_icon="<img src=\"images/icon_campaigns.png\" border=0 alt=\"Campaigns\" width=14 height=14 valign=middle\">";
    }
if ($hh=='lists') 
    {
    $lists_hh="CLASS=\"head_style_selected\""; $lists_fc="$lists_font"; $lists_bold="$header_selected_bold";
    $lists_icon="<img src=\"images/icon_black_lists.png\" border=0 alt=\"Lists\" width=14 height=14 valign=middle\">";
    }
else 
    {
    $lists_hh="CLASS=\"head_style\""; $lists_fc='WHITE'; $lists_bold="$header_nonselected_bold";
    $lists_icon="<img src=\"images/icon_lists.png\" border=0 alt=\"Lists\" width=14 height=14 valign=middle\">";
    }
if ($hh=='ingroups') 
    {
    $ingroups_hh="CLASS=\"head_style_selected\""; $ingroups_fc="$ingroups_font"; $ingroups_bold="$header_selected_bold";
    $inbound_icon="<img src=\"images/icon_black_inbound.png\" border=0 alt=\"Inbound\" width=14 height=14 valign=middle\">";
    }
else 
    {
    $ingroups_hh="CLASS=\"head_style\""; $ingroups_fc='WHITE'; $ingroups_bold="$header_nonselected_bold";
    $inbound_icon="<img src=\"images/icon_inbound.png\" border=0 alt=\"Inbound\" width=14 height=14 valign=middle\">";
    }
if ($hh=='remoteagent') 
    {
    $remoteagent_hh="CLASS=\"head_style_selected\""; $remoteagent_fc="$remoteagent_font"; $remoteagent_bold="$header_selected_bold";
    $remoteagents_icon="<img src=\"images/icon_black_remoteagents.png\" border=0 alt=\"Remote Agents\" width=14 height=14 valign=middle\">";
    }
else 
    {
    $remoteagent_hh="CLASS=\"head_style\""; $remoteagent_fc='WHITE'; $remoteagent_bold="$header_nonselected_bold";
    $remoteagents_icon="<img src=\"images/icon_remoteagents.png\" border=0 alt=\"Remote Agents\" width=14 height=14 valign=middle\">";
    }
if ($hh=='usergroups') 
    {
    $usergroups_hh="CLASS=\"head_style_selected\""; $usergroups_fc="$usergroups_font"; $usergroups_bold="$header_selected_bold";
    $usergroups_icon="<img src=\"images/icon_black_usergroups.png\" border=0 alt=\"User Groups\" width=14 height=14 valign=middle\">";
    }
else 
    {
    $usergroups_hh="CLASS=\"head_style\""; $usergroups_fc='WHITE'; $usergroups_bold="$header_nonselected_bold";
    $usergroups_icon="<img src=\"images/icon_usergroups.png\" border=0 alt=\"User Groups\" width=14 height=14 valign=middle\">";
    }
if ($hh=='scripts') 
    {
    $scripts_hh="CLASS=\"head_style_selected\""; $scripts_fc="$scripts_font"; $scripts_bold="$header_selected_bold";
    $scripts_icon="<img src=\"images/icon_black_scripts.png\" border=0 alt=\"Scripts\" width=14 height=14 valign=middle\">";
    }
else 
    {
    $scripts_hh="CLASS=\"head_style\""; $scripts_fc='WHITE'; $scripts_bold="$header_nonselected_bold";
    $scripts_icon="<img src=\"images/icon_scripts.png\" border=0 alt=\"Scripts\" width=14 height=14 valign=middle\">";
    }
if ($hh=='filters') 
    {
    $filters_hh="CLASS=\"head_style_selected\""; $filters_fc="$filters_font"; $filters_bold="$header_selected_bold";
    $filters_icon="<img src=\"images/icon_black_filters.png\" border=0 alt=\"Filters\" width=14 height=14 valign=middle\">";
    }
else 
    {
    $filters_hh="CLASS=\"head_style\""; $filters_fc='WHITE'; $filters_bold="$header_nonselected_bold";
    $filters_icon="<img src=\"images/icon_filters.png\" border=0 alt=\"Filters\" width=14 height=14 valign=middle\">";
    }
if ($hh=='admin') 
    {
    $admin_hh="CLASS=\"head_style_selected\""; $admin_fc="$admin_font"; $admin_bold="$header_selected_bold";
    $admin_icon="<img src=\"images/icon_black_admin.png\" border=0 alt=\"Admin\" width=14 height=14 valign=middle\">";
    }
else 
    {
    $admin_hh="CLASS=\"head_style\""; $admin_fc='WHITE'; $admin_bold="$header_nonselected_bold";
    $admin_icon="<img src=\"images/icon_admin.png\" border=0 alt=\"Admin\" width=14 height=14 valign=middle\">";
    }
if ($hh=='reports') 
    {
    $reports_hh="CLASS=\"head_style_selected\""; $reports_fc="$reports_font"; $reports_bold="$header_selected_bold";
    $reports_icon="<img src=\"images/icon_black_reports.png\" border=0 alt=\"Reports\" width=14 height=14 valign=middle\">";
    }
else 
    {
    $reports_hh="CLASS=\"head_style\""; $reports_fc='WHITE'; $reports_bold="$header_nonselected_bold";
    $reports_icon="<img src=\"images/icon_reports.png\" border=0 alt=\"Reports\" width=14 height=14 valign=middle\">";
    }
if ($hh=='qc')
    {
    $qc_hh="CLASS=\"head_style_selected\""; $qc_fc="$qc_font"; $qc_bold="$header_selected_bold";
    $qc_icon="<img src=\"images/icon_black_qc.png\" border=0 alt=\"Quality Control\" width=14 height=14 valign=middle\">";
    }
else 
    {
    $qc_hh="CLASS=\"head_style\""; $qc_fc='WHITE'; $qc_bold="$header_nonselected_bold";
    $qc_icon="<img src=\"images/icon_qc.png\" border=0 alt=\"Quality Control\" width=14 height=14 valign=middle\">";
    }
##### END populate dynamic header content #####

if ($short_header) {
    if ($no_header) {
        // Display nothing
    }
    else {
        // Logo-only mode for reports
        if (($LOGreports_header_override == 'LOGO_ONLY_SMALL') || ($LOGreports_header_override == 'LOGO_ONLY_LARGE')) {
            $temp_logo = $selected_logo;
            $temp_logo_size = 'width="170" height="45"';
            
            if ($LOGreports_header_override == 'LOGO_ONLY_SMALL') {
                $temp_logo = $selected_small_logo;
                $temp_logo_size = 'width="71" height="22"';
            }
            ?>
            <div class="modern-header-logo">
                <a href="<?php echo htmlspecialchars($admin_home_url_LU); ?>" class="logo-link">
                    <img src="<?php echo htmlspecialchars($temp_logo); ?>" <?php echo $temp_logo_size; ?> border="0" alt="System logo" class="logo-img">
                </a>
            </div>
            <?php
        }
        else {
            ?>
            <header class="modern-header">
                <div class="header-container">
                    <div class="header-left">
                        <a href="<?php echo htmlspecialchars($ADMIN); ?>" class="logo-link">
                            <img src="<?php echo htmlspecialchars($selected_small_logo); ?>" width="71" height="22" border="0" alt="System logo" class="logo-img">
                        </a>
                    </div>
                    
                    <nav class="header-nav">
                        <?php
                        // Full access menu
                        if (($reports_only_user < 1) && ($qc_only_user < 1)) {
                            ?>
                            <a href="<?php echo htmlspecialchars($ADMIN . '?ADD=999999'); ?>" class="nav-item">
                                <?php echo $reports_icon; ?> <span><?php echo _QXZ("Reports"); ?></span>
                            </a>
                            <a href="<?php echo htmlspecialchars($ADMIN . '?ADD=0A'); ?>" class="nav-item">
                                <?php echo $users_icon; ?> <span><?php echo _QXZ("Users"); ?></span>
                            </a>
                            <a href="<?php echo htmlspecialchars($ADMIN . '?ADD=10'); ?>" class="nav-item">
                                <?php echo $campaigns_icon; ?> <span><?php echo _QXZ("Campaigns"); ?></span>
                            </a>
                            
                            <?php
                            // QC menu if authorized
                            if (($SSqc_features_active == '1') && ($qc_auth == '1')) {
                                ?>
                                <a href="<?php echo htmlspecialchars($ADMIN . '?ADD=100000000000000'); ?>" class="nav-item">
                                    <?php echo $qc_icon; ?> <span><?php echo _QXZ("Quality Control"); ?></span>
                                </a>
                                <?php
                            }
                            ?>
                            <a href="<?php echo htmlspecialchars($ADMIN . '?ADD=100'); ?>" class="nav-item">
                                <?php echo $lists_icon; ?> <span><?php echo _QXZ("Lists"); ?></span>
                            </a>
                            <a href="<?php echo htmlspecialchars($ADMIN . '?ADD=1000000'); ?>" class="nav-item">
                                <?php echo $scripts_icon; ?> <span><?php echo _QXZ("Scripts"); ?></span>
                            </a>
                            <a href="<?php echo htmlspecialchars($ADMIN . '?ADD=10000000'); ?>" class="nav-item">
                                <?php echo $filters_icon; ?> <span><?php echo _QXZ("Filters"); ?></span>
                            </a>
                            <a href="<?php echo htmlspecialchars($ADMIN . '?ADD=1001'); ?>" class="nav-item">
                                <?php echo $inbound_icon; ?> <span><?php echo _QXZ("Inbound"); ?></span>
                            </a>
                            <a href="<?php echo htmlspecialchars($ADMIN . '?ADD=100000'); ?>" class="nav-item">
                                <?php echo $usergroups_icon; ?> <span><?php echo _QXZ("User Groups"); ?></span>
                            </a>
                            <a href="<?php echo htmlspecialchars($ADMIN . '?ADD=10000'); ?>" class="nav-item">
                                <?php echo $remoteagents_icon; ?> <span><?php echo _QXZ("Remote Agents"); ?></span>
                            </a>
                            <a href="<?php echo htmlspecialchars($ADMIN . '?ADD=999998'); ?>" class="nav-item">
                                <?php echo $admin_icon; ?> <span><?php echo _QXZ("Admin"); ?></span>
                            </a>
                            <?php
                        }
                        // Limited access menu
                        else {
                            ?>
                            <div class="nav-spacer"></div>
                            <?php
                            
                            if ($reports_only_user > 0) {
                                ?>
                                <a href="<?php echo htmlspecialchars($ADMIN . '?ADD=999999'); ?>" class="nav-item nav-item-active">
                                    <span><?php echo _QXZ("Reports"); ?></span>
                                </a>
                                <?php
                            }
                            else {
                                if (($SSqc_features_active == '1') && ($qc_auth == '1')) {
                                    ?>
                                    <a href="<?php echo htmlspecialchars($ADMIN . '?ADD=100000000000000'); ?>" class="nav-item nav-item-active">
                                        <span><?php echo _QXZ("Quality Control"); ?></span>
                                    </a>
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
######################### SMALL HTML HEADER END #######################################


######################### MOBILE HTML HEADER BEGIN ####################################
// ============================================
// ANDROID MOBILE HEADER - INLINE MODERNIZED
// Purple Gradient + Responsive Design
// ============================================

else if ($android_header) {
    ?>
    <header class="mobile-header">
        <div class="mobile-header-container">
            <div class="mobile-header-left">
                <a href="./admin_mobile.php" class="logo-link">
                    <img src="<?php echo htmlspecialchars($selected_small_logo); ?>" width="71" height="22" border="0" alt="System logo" class="logo-img">
                </a>
            </div>
            <div class="mobile-header-right">
                <a href="admin_mobile.php?ADD=999990" class="mobile-nav-item">
                    <?php echo $admin_icon; ?> <span><?php echo _QXZ("Admin"); ?></span>
                </a>
            </div>
        </div>
    </header>
    <?php
}

######################### FULL HTML HEADER BEGIN #######################################
else
{
if ($no_title < 1) {echo "</title>\n";}
echo "<script language=\"Javascript\">\n";
echo "var field_name = '';\n";
echo "var user = '$PHP_AUTH_USER';\n";
echo "var epoch = '" . date("U") . "';\n";

if ($TCedit_javascript > 0)
    {
     ?>

    function run_submit()
        {
        calculate_hours();
        var go_submit = document.getElementById("go_submit");
        if (go_submit.disabled == false)
            {
            document.edit_log.submit();
            }
        }

    // Calculate login time
    function calculate_hours() 
        {
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

        // Calculate milliseconds since 1970 for each date string and find diff
        var LI_date_epoch = Date.UTC(LI_date_array[0], (LI_date_array[1]-1), LI_date_array[2], LI_time_array[0], LI_time_array[1], LI_time_array[2]);
        var LO_date_epoch = Date.UTC(LO_date_array[0], (LO_date_array[1]-1), LO_date_array[2], LO_time_array[0], LO_time_array[1], LO_time_array[2]);
        var temp_LI_epoch = ( (LI_date_epoch / 1000 ) + local_gmt_sec);
        var temp_LO_epoch = ( (LO_date_epoch / 1000 ) + local_gmt_sec);
        var epoch_diff = (temp_LO_epoch - temp_LI_epoch);
        var temp_diff = epoch_diff;

        document.getElementById("login_time").innerHTML = "ERROR, Please check date fields";

    //	document.getElementById("login_time").innerHTML = LI_date_epoch + '|' + temp_LI_epoch + '|' + LO_date_epoch + '|' + temp_LO_epoch + '|' + (Date.UTC(LI_date_array[0], LI_date_array[1], LI_date_array[2]) / 1000) + '|' + (Date.UTC(LO_date_array[0], LO_date_array[1], LO_date_array[2]) / 1000) + "\n diff " +  epoch_diff + "\n LI " +  temp_LI_epoch + "\n LO " +  temp_LO_epoch + "\n Now " +  now_epoch + "\n local" + local_gmt_sec;

        var go_submit = document.getElementById("go_submit");
        go_submit.disabled = true;
        // length is a positive number and no more than 24 hours, datetime is earlier than right now
        if ( (epoch_diff < 86401) && (epoch_diff > 0) && (temp_LI_epoch < now_epoch) && (temp_LO_epoch < now_epoch) )
            {
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



    <?php
    }
######################
# ADD=31 or 34 and SUB=29 for list mixes
######################
if ( ( ($ADD==34) or ($ADD==31) or ($ADD==49) ) and ($SUB==29) and ($LOGmodify_campaigns==1) and ( (preg_match("/$campaign_id/i", $LOGallowed_campaigns)) or (preg_match("/ALL\-CAMPAIGNS/i",$LOGallowed_campaigns)) ) ) 
    {

    ?>
    // List Mix status add and remove
    function mod_mix_status(stage,vcl_id,entry) 
        {
        if (stage=="ALL")
            {
            var count=0;
            var ROnew_statuses = document.getElementById("ROstatus_X_" + vcl_id);

            while (count < entry)
                {
                var old_statuses = document.getElementById("status_" + count + "_" + vcl_id);
                var ROold_statuses = document.getElementById("ROstatus_" + count + "_" + vcl_id);

                old_statuses.value = ROnew_statuses.value;
                ROold_statuses.value = ROnew_statuses.value;
                count++;
                }
            }
        else
            {
            if (stage=="EMPTY")
                {
                var count=0;
                var ROnew_statuses = document.getElementById("ROstatus_X_" + vcl_id);

                while (count < entry)
                    {
                    var old_statuses = document.getElementById("status_" + count + "_" + vcl_id);
                    var ROold_statuses = document.getElementById("ROstatus_" + count + "_" + vcl_id);
                    
                    if (ROold_statuses.value.length < 3)
                        {
                        old_statuses.value = ROnew_statuses.value;
                        ROold_statuses.value = ROnew_statuses.value;
                        }
                    count++;
                    }
                }

            else
                {
                var mod_status = document.getElementById("dial_status_" + entry + "_" + vcl_id);
                if (mod_status.value.length < 1)
                    {
                    alert("You must select a status first");
                    }
                else
                    {
                    var old_statuses = document.getElementById("status_" + entry + "_" + vcl_id);
                    var ROold_statuses = document.getElementById("ROstatus_" + entry + "_" + vcl_id);
                    var MODstatus = new RegExp(" " + mod_status.value + " ","g");
                    if (stage=="ADD")
                        {
                        if (old_statuses.value.match(MODstatus))
                            {
                            alert("The status " + mod_status.value + " is already present");
                            }
                        else
                            {
                            var new_statuses = " " + mod_status.value + "" + old_statuses.value;
                            old_statuses.value = new_statuses;
                            ROold_statuses.value = new_statuses;
                            mod_status.value = "";
                            }
                        }
                    if (stage=="REMOVE")
                        {
                        var MODstatus = new RegExp(" " + mod_status.value + " ","g");
                        old_statuses.value = old_statuses.value.replace(MODstatus, " ");
                        ROold_statuses.value = ROold_statuses.value.replace(MODstatus, " ");
                        }
                    }
                }
            }
        }

    // List Mix percent difference calculation and warning message
    function mod_mix_percent(vcl_id,entries) 
        {
        var i=0;
        var total_percent=0;
        var percent_diff='';
        while(i < entries)
            {
            var mod_percent_field = document.getElementById("percentage_" + i + "_" + vcl_id);
            temp_percent = mod_percent_field.value * 1;
            total_percent = (total_percent + temp_percent);
            i++;
            }

        var mod_diff_percent = document.getElementById("PCT_DIFF_" + vcl_id);
        percent_diff = (total_percent - 100);
        if (percent_diff > 0)
            {
            percent_diff = '+' + percent_diff;
            }
        var mix_list_submit = document.getElementById("submit_" + vcl_id);
        if ( (percent_diff > 0) || (percent_diff < 0) )
            {
            mix_list_submit.disabled = true;
            document.getElementById("ERROR_" + vcl_id).innerHTML = "<font color=red><B>The Difference % must be 0</B></font>";
            }
        else
            {
            mix_list_submit.disabled = false;
            document.getElementById("ERROR_" + vcl_id).innerHTML = "";
            }

        mod_diff_percent.value = percent_diff;
        }

    function submit_mix(vcl_id,entries) 
        {
        var h=1;
        var j=1;
        var list_mix_container='';
        var mod_list_mix_container_field = document.getElementById("list_mix_container_" + vcl_id);
        while(h < 41)
            {
            var i=0;
            while(i < entries)
                {
                var mod_list_id_field = document.getElementById("list_id_" + i + "_" + vcl_id);
                var mod_priority_field = document.getElementById("priority_" + i + "_" + vcl_id);
                var mod_percent_field = document.getElementById("percentage_" + i + "_" + vcl_id);
                var mod_statuses_field = document.getElementById("status_" + i + "_" + vcl_id);
                if (mod_priority_field.value==h)
                    {
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
    <?php
    }
    ?>

    <?php
    if ( ( ($ADD==34) or ($ADD==31) or ($ADD==44) or ($ADD==41) ) and ($LOGmodify_campaigns==1) and ( (preg_match("/$campaign_id/i", $LOGallowed_campaigns)) or (preg_match("/ALL\-CAMPAIGNS/i",$LOGallowed_campaigns)) ) ) 
        {
    ?>
    // List status change confirmation
    function ConfirmListStatusChange(system_setting, listForm) {
        if (!system_setting) {
            // if list change confirmation system setting is off, just submit form
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
            // if none of lists active status has been changed, just submit form
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
    <?php
        }

    if ( ( ($ADD==31) or ($ADD==41) ) and ($LOGmodify_campaigns==1) and ( (preg_match("/$campaign_id/i", $LOGallowed_campaigns)) or (preg_match("/ALL\-CAMPAIGNS/i",$LOGallowed_campaigns)) ) ) 
        {
    ?>
    // Agent Call Hangup Route change trigger
    function AgentCallHangupRouteChange(ACHR_new_value) 
        {
        var ACHR_list = document.getElementById("agent_hangup_route");
        var ACHR_route = ACHR_list.value;
        var ACHR_value = document.getElementById("agent_hangup_value");
        var ACHR_title = document.getElementById("agent_hangup_value_title");
        var ACHR_chooser = document.getElementById("agent_hangup_value_chooser");

        if (ACHR_route=='HANGUP')
            {
            ACHR_title.innerHTML = '-<?php echo _QXZ("no value required") ?>-';
            ACHR_chooser.innerHTML = '';
            ACHR_value.value='';
            }
        if (ACHR_route=='MESSAGE')
            {
            ACHR_title.innerHTML = '<?php echo _QXZ("Agent Hangup Message") ?>';
            ACHR_chooser.innerHTML = " <a href=\"javascript:launch_chooser('agent_hangup_value','date');\"><?php echo _QXZ("audio chooser") ?></a> ";
            ACHR_value.value='';
            }
        if (ACHR_route=='EXTENSION')
            {
            ACHR_title.innerHTML = '<?php echo _QXZ("Agent Hangup Dialplan Extension") ?>';
            ACHR_chooser.innerHTML = '';
            ACHR_value.value='';
            }
        if (ACHR_route=='IN_GROUP')
            {
            ACHR_title.innerHTML = '<?php echo _QXZ("Agent Hangup In-Group") ?>';
            ACHR_chooser.innerHTML = " <a href=\"javascript:launch_ingroup_chooser('agent_hangup_value','group_id');\"><?php echo _QXZ("in-group chooser") ?></a> ";
            ACHR_value.value='';
            }
        if (ACHR_route=='CALLMENU')
            {
            ACHR_title.innerHTML = '<?php echo _QXZ("Agent Hangup Call Menu") ?>';
            ACHR_chooser.innerHTML = " <a href=\"javascript:launch_callmenu_chooser('agent_hangup_value','menu_id');\"><?php echo _QXZ("call menu chooser") ?></a> ";
            ACHR_value.value='';
            }
        }

    <?php
    }
    ?>

    var weak = new Image();
    weak.src = "images/weak.png";
    var medium = new Image();
    medium.src = "images/medium.png";
    var strong = new Image();
    strong.src = "images/strong.png";

    function pwdChanged(pwd_field_str, pwd_img_str, pwd_len_field, pwd_len_min) 
        {
        var pwd_field = document.getElementById(pwd_field_str);
        var pwd_field_value = pwd_field.value;
        var pwd_img = document.getElementById(pwd_img_str);
        var pwd_len = pwd_field_value.length

    //	var strong_regex = new RegExp( "^(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])", "g" );
    //	var medium_regex = new RegExp( "^(?=.{6,})(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9]))).*$", "g" );
        var strong_regex = new RegExp( "^(?=.{20,})(?=.*[a-zA-Z])(?=.*[0-9])", "g" );
        var medium_regex = new RegExp( "^(?=.{10,})(?=.*[a-zA-Z])(?=.*[0-9])", "g" );

        if (strong_regex.test(pwd_field.value) ) 
            {
            if (pwd_img.src != strong.src)
                {pwd_img.src = strong.src;}
            } 
        else if (medium_regex.test( pwd_field.value) ) 
            {
            if (pwd_img.src != medium.src) 
                {pwd_img.src = medium.src;}
            }
        else 
            {
            if (pwd_img.src != weak.src) 
                {pwd_img.src = weak.src;}
            }
        if ( (pwd_len_min > 0) && (pwd_len_min > pwd_len) )
            {document.getElementById(pwd_len_field).innerHTML = "<font color=red><b>" + pwd_len + "</b></font>";}
        else
            {document.getElementById(pwd_len_field).innerHTML = "<font color=black><b>" + pwd_len + "</b></font>";}
        }

    function openNewWindow(url) 
        {
        window.open (url,"",'width=620,height=300,scrollbars=yes,menubar=yes,address=yes');
        }
    function scriptInsertField() 
        {
        openField = '--A--';
        closeField = '--B--';
        var textBox = document.scriptForm.script_text;
        var scriptIndex = document.getElementById("selectedField").selectedIndex;
        var insValue =  document.getElementById('selectedField').options[scriptIndex].value;
        if (document.selection) 
            {
            //IE
            textBox = document.scriptForm.script_text;
            insValue = document.scriptForm.selectedField.options[document.scriptForm.selectedField.selectedIndex].text;
            textBox.focus();
            sel = document.selection.createRange();
            sel.text = openField + insValue + closeField;
            } 
        else if (textBox.selectionStart || textBox.selectionStart == 0) 
            {
            //Mozilla
            var startPos = textBox.selectionStart;
            var endPos = textBox.selectionEnd;
            textBox.value = textBox.value.substring(0, startPos)
            + openField + insValue + closeField
            + textBox.value.substring(endPos, textBox.value.length);
            }
        else 
            {
            textBox.value += openField + insValue + closeField;
            }
        }

    <?php

#### Javascript for auto-generate of user ID Button
if ( ($SSadmin_modify_refresh > 1) and (preg_match("/^3|^4/",$ADD)) )
    {
    ?>
    var ar_seconds=<?php echo "$SSadmin_modify_refresh;"; ?>

    function modify_refresh_display()
        {
        if (ar_seconds > 0)
            {
            ar_seconds = (ar_seconds - 1);
            document.getElementById("refresh_countdown").innerHTML = "<font color=black> screen refresh in: " + ar_seconds + " seconds</font>";
            setTimeout("modify_refresh_display()",1000);
            }
        }

    <?php
    }

#### BEGIN Javascript for auto-generate of user ID Button
if ( ($ADD==1) or ($ADD=="1A") )
    {
    ?>

    function user_auto()
        {
        var user_toggle = document.getElementById("user_toggle");
        var user_field = document.getElementById("user");
        if (user_toggle.value < 1)
            {
            user_field.value = 'AUTOGENERATEZZZ';
            user_field.disabled = true;
            user_toggle.value = 1;
            }
        else
            {
            user_field.value = '';
            user_field.disabled = false;
            user_toggle.value = 0;
            }
        }

    function user_submit()
        {
        var user_field = document.getElementById("user");
        user_field.disabled = false;
        document.userform.submit();
        }

    <?php
    }
#### END Javascript for auto-generate of user ID Button

else
    {
    echo "	var pass = '$PHP_AUTH_PW';\n";
    ?>

    mouseY=0;
    function getMousePos(event) {
        mouseY=event.pageY;
    }
    document.addEventListener("click", getMousePos);

    var chooser_field='';
    var chooser_field_td='';
    var chooser_type='';

    function launch_chooser(fieldname,stage)
        {
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

    function launch_moh_chooser(fieldname,stage)
        {
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

    function launch_ingroup_chooser(fieldname,stage)
        {
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

    function launch_callmenu_chooser(fieldname,stage)
        {
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

    function launch_container_chooser(fieldname,stage,type)
        {
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

    function launch_vm_chooser(fieldname,stage)
        {
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

    function launch_color_chooser(fieldname,stage,type)
        {
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
    while ($HTMLcolorsARYcount > $HTMLct)
        {
        $HTMLcolorsLINE = explode(',',$HTMLcolorsARY[$HTMLct]);
        if (preg_match("/1$|3$|5$|7$|9$/i", $HTMLct))
            {$bgcolor='#E6E6E6';} 
        else
            {$bgcolor='#F6F6F6';}

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

    function choose_color(colorname)
        {
        if (colorname.length > 0)
            {
            if (chooser_type == '2')
                {
                document.getElementById(chooser_field).value = colorname;
                document.getElementById(chooser_field_td).style.backgroundColor = '#' + colorname;
                }
            else
                {
                document.getElementById(chooser_field).value = '#' + colorname;
                document.getElementById(chooser_field_td).style.backgroundColor = '#' + colorname;
                }
            close_chooser();
            }
        }

    function close_chooser()
        {
        document.getElementById("audio_chooser_span").style.visibility = 'hidden';
        document.getElementById("audio_chooser_span").innerHTML = '';
        }

    function user_submit()
        {
        var user_field = document.getElementById("user");
        user_field.disabled = false;
        document.userform.submit();
        }

    function play_browser_sound(temp_element,temp_volume)
        {
        var taskIndex = document.getElementById(temp_element).selectedIndex;
        var taskValue = document.getElementById(temp_element).options[taskIndex].value;
        var temp_selected_element = 'BAS_' + taskValue;
        if ( (taskValue != '---NONE---') && (taskValue != '---DISABLED---') && (taskValue != '') )
            {
            var temp_audio = document.getElementById(temp_selected_element);
            var taskVolIndex = document.getElementById(temp_volume).selectedIndex;
            var taskVolValue = document.getElementById(temp_volume).options[taskVolIndex].value;
            var temp_js_volume = (taskVolValue * .01);
            temp_audio.volume = temp_js_volume;
        //	alert(temp_selected_element + ' ' + temp_js_volume);
            temp_audio.play();
            }
        }
    <?php
    }

### Javascript for shift end-time calculation and display
if ( ($ADD==131111111) or ($ADD==331111111) or ($ADD==431111111) )
    {
    ?>
    function shift_time()
        {
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

        if (start_time.value == end_time.value)
            {
            var shift_length = '24:00';
            }
        else
            {
            if ( (start_time_hour > end_time_hour) || ( (start_time_hour == end_time_hour) && (start_time_min > end_time_min) ) )
                {
                var shift_hour = ( (24 - start_time_hour) + end_time_hour);
                var shift_minute = ( (60 - start_time_min) + end_time_min);
                if (shift_minute >= 60) 
                    {
                    shift_minute = (shift_minute - 60);
                    }
                else
                    {
                    shift_hour = (shift_hour - 1);
                    }
                }
            else
                {
                var shift_hour = (end_time_hour - start_time_hour);
                var shift_minute = (end_time_min - start_time_min);
                }
            if (shift_minute < 0) 
                {
                shift_minute = (shift_minute + 60);
                shift_hour = (shift_hour - 1);
                }

            if (shift_hour < 10) {shift_hour = '0' + shift_hour}
            if (shift_minute < 10) {shift_minute = '0' + shift_minute}
            var shift_length = shift_hour + ':' + shift_minute;
            }
    //	alert(start_time_hour + '|' + start_time_min + '|' + end_time_hour + '|' + end_time_min + '|--|' + shift_hour + ':' + shift_minute + '|' + shift_length + '|');

        length.value = shift_length;
        }

<?php
    }




### Javascript for selecting and deselecting all AC-CIDs and other checkboxes "active" on the modify page
if ( ($ADD==3111) or ($ADD==4111) or ($ADD==5111) or ($ADD==3811) or ($ADD==4811) or ($ADD==5811) or ($ADD==3911) or ($ADD==4911) or ($ADD==5911) or ($ADD==31) or ($ADD==34) or ($ADD==202) or ($ADD==396111111111) or ($ADD==496111111111) )
    {
?>
    function FORM_selectall(temp_count,temp_fields,temp_option,temp_span)
        {
        var fields_array=temp_fields.split('|');
        var inc=0;
        while (temp_count >= inc)
            {
            if (fields_array[inc].length > 0)
                {
                if (temp_option == 'off')
                    {document.getElementById(fields_array[inc]).checked=false;}
                else
                    {document.getElementById(fields_array[inc]).checked=true;}
                }
            inc++;
            }
        if (temp_option == 'off')
            {
            document.getElementById(temp_span).innerHTML = "<a href=\"#\" onclick=\"FORM_selectall('" + temp_count + "','" + temp_fields + "','on','" + temp_span + "');return false;\"><font size=1><?php echo _QXZ("select all"); ?></font></a>";
            }
        else
            {
            document.getElementById(temp_span).innerHTML = "<a href=\"#\" onclick=\"FORM_selectall('" + temp_count + "','" + temp_fields + "','off','" + temp_span + "');return false;\"><font size=1><?php echo _QXZ("deselect all"); ?></font></a>";
            }
        }

    <?php
    }


 $stmt="SELECT menu_id,menu_name from vicidial_call_menu $whereLOGadmin_viewable_groupsSQL order by menu_id limit 10000;";
 $rslt=mysql_to_mysqli($stmt, $link);
 $menus_to_print = mysqli_num_rows($rslt);
 $call_menu_list='';
 $i=0;
while ($i < $menus_to_print)
    {
    $row=mysqli_fetch_row($rslt);
    $call_menu_list .= "<option value=\"$row[0]\">$row[0] - $row[1]</option>";
    $i++;
    }

### select list contents generation for dynamic route displays in call menu and in-group screens
if ( ($ADD==3511) or ($ADD==2511) or ($ADD==2611) or ($ADD==4511) or ($ADD==5511) or ($ADD==3111) or ($ADD==2111) or ($ADD==2011) or ($ADD==4111) or ($ADD==5111) )
    {
    $stmt="SELECT did_pattern,did_description,did_route from vicidial_inbound_dids where did_active='Y' $LOGadmin_viewable_groupsSQL order by did_pattern;";
    $rslt=mysql_to_mysqli($stmt, $link);
    $dids_to_print = mysqli_num_rows($rslt);
    $did_list='';
    $i=0;
    while ($i < $dids_to_print)
        {
        $row=mysqli_fetch_row($rslt);
        $did_list .= "<option value=\"$row[0]\">$row[0] - $row[1] - $row[2]</option>";
        $i++;
        }

    $stmt="SELECT group_id,group_name from vicidial_inbound_groups where active='Y' and group_id NOT LIKE \"AGENTDIRECT%\" $LOGadmin_viewable_groupsSQL order by group_id;";
    $rslt=mysql_to_mysqli($stmt, $link);
    $ingroups_to_print = mysqli_num_rows($rslt);
    $ingroup_list='';
    $i=0;
    while ($i < $ingroups_to_print)
        {
        $row=mysqli_fetch_row($rslt);
        $ingroup_list .= "<option value=\"$row[0]\">$row[0] - $row[1]</option>";
        $i++;
        }

    $stmt="SELECT campaign_id,campaign_name from vicidial_campaigns where active='Y' $LOGallowed_campaignsSQL order by campaign_id;";
    $rslt=mysql_to_mysqli($stmt, $link);
    $IGcampaigns_to_print = mysqli_num_rows($rslt);
    $IGcampaign_id_list='';
    $i=0;
    while ($i < $IGcampaigns_to_print)
        {
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
    while ($i < $phones_to_print)
        {
        $row=mysqli_fetch_row($rslt);
        $phone_list .= "<option value=\"$row[0]\">$row[0] - $row[1] - $row[2] - $row[3]</option>";
        $i++;
        }
    }

# dynamic options for options in call_menu screen
if ( ($ADD==3511) or ($ADD==2511) or ($ADD==2611) or ($ADD==4511) or ($ADD==5511) )
    {

    ?>
    function call_menu_option(option,route,value,value_context,chooser_height)
        {
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

        if (selected_route=='CALLMENU')
            {
            if (route == selected_route)
                {
                selected_value = '<option SELECTED value="' + value + '">' + value + "</option>\n";
                }
            else
                {value='';}
            new_content = '<span name=option_route_link_' + option + ' id=option_route_link_' + option + "><a href=\"<?php echo $ADMIN ?>?ADD=3511&menu_id=" + value + "\"><?php echo _QXZ("Call Menu"); ?>: </a></span><select size=1 name=option_route_value_" + option + " id=option_route_value_" + option + " onChange=\"call_menu_link('" + option + "','CALLMENU');\">" + call_menu_list + "\n" + selected_value + '</select>';
            }
        if (selected_route=='INGROUP')
            {
            if (value_context.length < 10)
                {value_context = 'CID,LB,998,TESTCAMP,1,,,,,';}
            var value_context_split =		value_context.split(",");
            var IGhandle_method =			value_context_split[0];
            var IGsearch_method =			value_context_split[1];
            var IGlist_id =					value_context_split[2];
            var IGcampaign_id =				value_context_split[3];
            var IGphone_code =				value_context_split[4];
            var IGvid_enter_filename =		value_context_split[5];
            var IGvid_id_number_filename =	value_context_split[6];
            var IGvid_confirm_filename =	value_context_split[7];
            var IGvid_validate_digits =		value_context_split[8];
            var IGvid_container =			value_context_split[9];

            if (route == selected_route)
                {
                selected_value = '<option SELECTED>' + value + '</option>';
                }

            new_content = '<input type=hidden name=option_route_value_context_' + option + ' id=option_route_value_context_' + option + ' value="' + selected_value + '">';
            new_content = new_content + '<span name="option_route_link_' + option + '" id="option_route_link_' + option + '">';
            new_content = new_content + "<a href=\"<?php echo $ADMIN ?>?ADD=3111&group_id=" + value + "\"><?php echo _QXZ("In-Group"); ?>:</a> </span>";
            new_content = new_content + '<select size=1 name=option_route_value_' + option + ' id=option_route_value_' + option + " onChange=\"call_menu_link('" + option + "','INGROUP');\">";
            new_content = new_content + '' + ingroup_list + "\n" + selected_value + '<option>DYNAMIC_INGROUP_VAR</option></select>';
            new_content = new_content + " &nbsp; <?php echo _QXZ("Handle Method"); ?>: <select size=1 name=IGhandle_method_" + option + ' id=IGhandle_method_' + option + '>';
            new_content = new_content + '' + '<option SELECTED>' + IGhandle_method + '</option>' + IGhandle_method_list + '</select>' + "\n";
            new_content = new_content + " &nbsp; <IMG SRC=\"help.png\" onClick=\"FillAndShowHelpDiv(event, \'call_menu-ingroup_settings\')\" WIDTH=20 HEIGHT=20 BORDER=0 ALT=\"HELP\" ALIGN=TOP>";
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
        if (selected_route=='DID')
            {
            if (route == selected_route)
                {
                selected_value = '<option SELECTED value="' + value + '">' + value + "</option>\n";
                }
            else
                {value='';}
            new_content = '<span name=option_route_link_' + option + ' id=option_route_link_' + option + '><a href="<?php echo $ADMIN ?>?ADD=3311&did_pattern=' + value + "\"><?php echo _QXZ("DID"); ?>:</a> </span><select size=1 name=option_route_value_" + option + ' id=option_route_value_' + option + " onChange=\"call_menu_link('" + option + "','DID');\">" + did_list + "\n" + selected_value + '</select>';
            }
        if (selected_route=='HANGUP')
            {
            if (route == selected_route)
                {
                selected_value = value;
                }
            else
                {value='vm-goodbye';}
            new_content = "<?php echo _QXZ("Audio File"); ?>: <input type=text name=option_route_value_" + option + " id=option_route_value_" + option + " size=40 maxlength=255 value=\"" + selected_value + "\"> <a href=\"javascript:launch_chooser('option_route_value_" + option + "','date');\"><?php echo _QXZ("audio chooser"); ?></a>";
            }
        if (selected_route=='EXTENSION')
            {
            if (route == selected_route)
                {
                selected_value = value;
                selected_context = value_context;
                }
            else
                {value='8304';}
            new_content = "<?php echo _QXZ("Extension"); ?>: <input type=text name=option_route_value_" + option + " id=option_route_value_" + option + " size=20 maxlength=255 value=\"" + selected_value + "\"> &nbsp; <?php echo _QXZ("Context"); ?>: <input type=text name=option_route_value_context_" + option + " id=option_route_value_context_" + option + " size=20 maxlength=255 value=\"" + selected_context + "\"> ";
            }
        if (selected_route=='PHONE')
            {
            if (route == selected_route)
                {
                selected_value = '<option SELECTED value="' + value + '">' + value + "</option>\n";
                }
            else
                {value='';}
            new_content = "<?php echo _QXZ("Phone"); ?>: <select size=1 name=option_route_value_" + option + ' id=option_route_value_' + option + '>' + phone_list + "\n" + selected_value + '</select>';
            }
        if ( (selected_route=='VOICEMAIL') || (selected_route=='VMAIL_NO_INST') )
            {
            if (route == selected_route)
                {
                selected_value = value;
                }
            else
                {value='';}
            new_content = "<?php echo _QXZ("Voicemail Box"); ?>: <input type=text name=option_route_value_" + option + " id=option_route_value_" + option + " size=12 maxlength=10 value=\"" + selected_value + "\"> <a href=\"javascript:launch_vm_chooser('option_route_value_" + option + "','date');\"><?php echo _QXZ("voicemail chooser"); ?></a>";
            }
        if (selected_route=='AGI')
            {
            if (route == selected_route)
                {
                selected_value = value;
                }
            else
                {value='';}
            new_content = "<?php echo _QXZ("AGI"); ?>: <input type=text name=option_route_value_" + option + " id=option_route_value_" + option + " size=80 maxlength=255 value=\"" + selected_value + "\"> ";
            }

        if (new_content.length < 1)
            {new_content = selected_route}

        span_to_update.innerHTML = new_content;
        }

    function call_menu_link(option,route)
        {
        var selected_value = '';
        var new_content = '';

        var select_list = document.getElementById("option_route_value_" + option);
        var selected_value = select_list.value;
        var span_to_update = document.getElementById("option_route_link_" + option);

        if (route=='CALLMENU')
            {
            new_content = "<a href=\"<?php echo $ADMIN ?>?ADD=3511&menu_id=" + selected_value + "\"><?php echo _QXZ("Call Menu"); ?>:</a>";
            }
        if (route=='INGROUP')
            {
            new_content = "<a href=\"<?php echo $ADMIN ?>?ADD=3111&group_id=" + selected_value + "\"><?php echo _QXZ("In-Group"); ?>:</a>";
            }
        if (route=='DID')
            {
            new_content = "<a href=\"<?php echo $ADMIN ?>?ADD=3311&did_pattern=" + selected_value + "\"><?php echo _QXZ("DID"); ?>:</a>";
            }

        if (new_content.length < 1)
            {new_content = selected_route}

        span_to_update.innerHTML = new_content;
        }

    function copy_prev_cm_option(item_number,item_height)
        {
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
        if (temp_ValNewLength >= 21) 
            {
            if (temp_ValNewLength > temp_ValLength) 
                {
                NEWtemp_ValIndex = (NEWtemp_ValIndex + 1);
                }
            if (NEWtemp_ValIndex > 21) {NEWtemp_ValIndex=1;}
            }
        else
            {
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

        if ( (temp_optionValue == 'CALLMENU') || (temp_optionValue == 'DID') || (temp_optionValue == 'PHONE') )
            {
            var temp_prev_optionVal = document.getElementById(temp_prev_option_route_value);
            var temp_prev_optionValIndex = temp_prev_optionVal.selectedIndex;
            var temp_prev_optionValValue = temp_prev_optionVal.options[temp_prev_optionValIndex].value;

            var temp_optionVal = document.getElementById(temp_option_route_value);
            var temp_optionValIndex = 0;
            var temp_optionValLength = temp_optionVal.length;
            var temp_counter=0; 
            while (temp_counter < temp_optionValLength)
                {
                if (temp_prev_optionValValue == temp_optionVal.options[temp_counter].value)
                    {temp_optionValIndex = temp_counter;}

                temp_counter++;
                }
            document.getElementById(temp_option_route_value).selectedIndex = temp_optionValIndex;

            if ( (temp_optionValue == 'CALLMENU') || (temp_optionValue == 'DID') )
                {
                call_menu_link(item_number,temp_optionValue);
                }
            }

        if ( (temp_optionValue == 'HANGUP') || (temp_optionValue == 'VOICEMAIL') || (temp_optionValue == 'VMAIL_NO_INST') )
            {
            var temp_prev_optionVal = document.getElementById(temp_prev_option_route_value);
            var temp_optionVal = document.getElementById(temp_option_route_value);

            temp_optionVal.value = temp_prev_optionVal.value;
            }

        if (temp_optionValue == 'EXTENSION') 
            {
            var temp_prev_optionVal = document.getElementById(temp_prev_option_route_value);
            var temp_optionVal = document.getElementById(temp_option_route_value);
            var temp_prev_optionValContext = document.getElementById(temp_prev_option_route_value_context);
            var temp_optionValContext = document.getElementById(temp_option_route_value_context);

            temp_optionVal.value = temp_prev_optionVal.value;
            temp_optionValContext.value = temp_prev_optionValContext.value;
            }

        if (temp_optionValue == 'INGROUP') 
            {
            // In-Group select list
            var temp_prev_optionVal = document.getElementById(temp_prev_option_route_value);
            var temp_prev_optionValIndex = temp_prev_optionVal.selectedIndex;
            var temp_prev_optionValValue = temp_prev_optionVal.options[temp_prev_optionValIndex].value;

            var temp_optionVal = document.getElementById(temp_option_route_value);
            var temp_optionValIndex = 0;
            var temp_optionValLength = temp_optionVal.length;
            var temp_counter=0; 
            while (temp_counter < temp_optionValLength)
                {
                if (temp_prev_optionValValue == temp_optionVal.options[temp_counter].value)
                    {temp_optionValIndex = temp_counter;}

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
            while (temp2_counter < temp2_optionValLength)
                {
                if (temp2_prev_optionValValue == temp2_optionVal.options[temp2_counter].value)
                    {temp2_optionValIndex = temp_counter;}

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
            while (temp3_counter < temp3_optionValLength)
                {
                if (temp3_prev_optionValValue == temp3_optionVal.options[temp3_counter].value)
                    {temp3_optionValIndex = temp_counter;}

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
            while (temp4_counter < temp4_optionValLength)
                {
                if (temp4_prev_optionValValue == temp4_optionVal.options[temp4_counter].value)
                    {temp4_optionValIndex = temp_counter;}

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
    <?php
    }

<?php
// Check if we should include the dynamic JavaScript
 $validAddValues = [2811, 3811, 3111, 2111, 2011, 4111, 5111];
if (in_array($ADD, $validAddValues)) {
?>
<script>
// Configuration object for dynamic options
const dynamicOptionsConfig = {
    callMenuList: '<?php echo $call_menu_list ?>',
    inGroupList: '<?php echo $ingroup_list ?>',
    igCampaignIdList: '<?php echo $IGcampaign_id_list ?>',
    igHandleMethodList: '<?php echo $IGhandle_method_list ?>',
    igSearchMethodList: "<?php echo $IGsearch_method_list ?>",
    didList: '<?php echo $did_list ?>',
    adminUrl: '<?php echo $ADMIN ?>'
};

// Main function to handle dynamic call actions
function dynamic_call_action(option, route, value, chooser_height) {
    const selectList = document.getElementById(option);
    const selectedRoute = selectList.value;
    const spanToUpdate = document.getElementById(`${option}_value_span`);
    
    let newContent = '';
    
    switch (selectedRoute) {
        case 'CALLMENU':
            newContent = buildCallMenuContent(option, route, value);
            break;
        case 'INGROUP':
            newContent = buildInGroupContent(option, route, value);
            break;
        case 'DID':
            newContent = buildDidContent(option, route, value);
            break;
        case 'MESSAGE':
            newContent = buildMessageContent(option, route, value);
            break;
        case 'EXTENSION':
            newContent = buildExtensionContent(option, route, value);
            break;
        case 'VOICEMAIL':
        case 'VMAIL_NO_INST':
            newContent = buildVoicemailContent(option, route, value);
            break;
        default:
            newContent = selectedRoute;
    }
    
    spanToUpdate.innerHTML = newContent;
}

// Helper function to build CALLMENU content
function buildCallMenuContent(option, route, value) {
    const selectedValue = (route === 'CALLMENU') 
        ? `<option selected value="${value}">${value}</option>\n` 
        : '';
    
    return `
        <span name="${option}_value_link" id="${option}_value_link">
            <a href="${dynamicOptionsConfig.adminUrl}?ADD=3511&menu_id=${value}"><?php echo _QXZ("Call Menu"); ?>:</a>
        </span>
        <select size="1" name="${option}_value" id="${option}_value" 
                onchange="dynamic_call_action_link('${option}', 'CALLMENU')">
            ${dynamicOptionsConfig.callMenuList}
            ${selectedValue}
        </select>
    `;
}

// Helper function to build INGROUP content
function buildInGroupContent(option, route, value) {
    if (route !== 'INGROUP' || value.length < 10) {
        value = 'SALESLINE,CID,LB,998,TESTCAMP,1,,,,,';
    }
    
    const valueParts = value.split(',');
    const [
        igGroupId, igHandleMethod, igSearchMethod, igListId, 
        igCampaignId, igPhoneCode, igVidEnterFilename, 
        igVidIdNumberFilename, igVidConfirmFilename, 
        igVidValidateDigits, igVidContainer
    ] = valueParts;
    
    const selectedValue = (route === 'INGROUP') 
        ? `<option selected>${igGroupId}</option>` 
        : '';
    
    return `
        <span name="${option}_value_link" id="${option}_value_link">
            <a href="${dynamicOptionsConfig.adminUrl}?ADD=3111&group_id=${igGroupId}"><?php echo _QXZ("In-Group"); ?>:</a>
        </span>
        <select size="1" name="IGgroup_id_${option}" id="IGgroup_id_${option}" 
                onchange="dynamic_call_action_link('IGgroup_id_${option}', 'INGROUP')">
            ${dynamicOptionsConfig.inGroupList}
            ${selectedValue}
        </select>
        &nbsp; <?php echo _QXZ("Handle Method"); ?>: 
        <select size="1" name="IGhandle_method_${option}" id="IGhandle_method_${option}">
            <option selected>${igHandleMethod}</option>
            ${dynamicOptionsConfig.igHandleMethodList}
        </select>
        <br><?php echo _QXZ("Search Method"); ?>: 
        <select size="1" name="IGsearch_method_${option}" id="IGsearch_method_${option}">
            ${dynamicOptionsConfig.igSearchMethodList}
            <option selected>${igSearchMethod}</option>
        </select>
        &nbsp; <?php echo _QXZ("List ID"); ?>: 
        <input type="text" size="5" maxlength="14" name="IGlist_id_${option}" 
               id="IGlist_id_${option}" value="${igListId}">
        <br><?php echo _QXZ("Campaign ID"); ?>: 
        <select size="1" name="IGcampaign_id_${option}" id="IGcampaign_id_${option}">
            ${dynamicOptionsConfig.igCampaignIdList}
            <option selected>${igCampaignId}</option>
        </select>
        &nbsp; <?php echo _QXZ("Phone Code"); ?>: 
        <input type="text" size="5" maxlength="14" name="IGphone_code_${option}" 
               id="IGphone_code_${option}" value="${igPhoneCode}">
    `;
}

// Helper function to build DID content
function buildDidContent(option, route, value) {
    const selectedValue = (route === 'DID') 
        ? `<option selected value="${value}">${value}</option>\n` 
        : '';
    
    return `
        <span name="${option}_value_link" id="${option}_value_link">
            <a href="${dynamicOptionsConfig.adminUrl}?ADD=3311&did_pattern=${value}"><?php echo _QXZ("DID"); ?>:</a>
        </span>
        <select size="1" name="${option}_value" id="${option}_value" 
                onchange="dynamic_call_action_link('${option}', 'DID')">
            ${dynamicOptionsConfig.didList}
            ${selectedValue}
        </select>
    `;
}

// Helper function to build MESSAGE content
function buildMessageContent(option, route, value) {
    const selectedValue = (route === 'MESSAGE') ? value : 'nbdy-avail-to-take-call|vm-goodbye';
    
    return `
        <?php echo _QXZ("Audio File"); ?>: 
        <input type="text" name="${option}_value" id="${option}_value" 
               size="40" maxlength="255" value="${selectedValue}"> 
        <a href="javascript:launch_chooser('${option}_value', 'date');"><?php echo _QXZ("audio chooser"); ?></a>
    `;
}

// Helper function to build EXTENSION content
function buildExtensionContent(option, route, value) {
    if (route !== 'EXTENSION' || value.length < 3) {
        value = '8304,default';
    }
    
    const [exExtension, exContext] = value.split(',');
    
    return `
        <?php echo _QXZ("Extension"); ?>: 
        <input type="text" name="EXextension_${option}" id="EXextension_${option}" 
               size="20" maxlength="255" value="${exExtension}"> 
        &nbsp; <?php echo _QXZ("Context"); ?>: 
        <input type="text" name="EXcontext_${option}" id="EXcontext_${option}" 
               size="20" maxlength="255" value="${exContext}">
    `;
}

// Helper function to build VOICEMAIL content
function buildVoicemailContent(option, route, value) {
    const selectedValue = (route === 'VOICEMAIL' || route === 'VMAIL_NO_INST') ? value : '101';
    
    return `
        <?php echo _QXZ("Voicemail Box"); ?>: 
        <input type="text" name="${option}_value" id="${option}_value" 
               size="12" maxlength="10" value="${selectedValue}"> 
        <a href="javascript:launch_vm_chooser('${option}_value', 'date');"><?php echo _QXZ("voicemail chooser"); ?></a>
    `;
}

// Function to handle dynamic call action links
function dynamic_call_action_link(field, route) {
    let selectList;
    
    if (route === 'CALLMENU' || route === 'DID') {
        selectList = document.getElementById(`${field}_value`);
    } else if (route === 'INGROUP') {
        selectList = document.getElementById(field);
        field = field.replace(/IGgroup_id_/, "");
    }
    
    const selectedValue = selectList.value;
    const spanToUpdate = document.getElementById(`${field}_value_link`);
    
    let newContent = '';
    
    switch (route) {
        case 'CALLMENU':
            newContent = `<a href="${dynamicOptionsConfig.adminUrl}?ADD=3511&menu_id=${selectedValue}"><?php echo _QXZ("Call Menu"); ?>:</a>`;
            break;
        case 'INGROUP':
            newContent = `<a href="${dynamicOptionsConfig.adminUrl}?ADD=3111&group_id=${selectedValue}"><?php echo _QXZ("In-Group"); ?>:</a>`;
            break;
        case 'DID':
            newContent = `<a href="${dynamicOptionsConfig.adminUrl}?ADD=3311&did_pattern=${selectedValue}"><?php echo _QXZ("DID"); ?>:</a>`;
            break;
        default:
            newContent = selectedValue;
    }
    
    spanToUpdate.innerHTML = newContent;
}
</script>
<?php
}
echo "</script>\n";
?>

<style type="text/css">
/* CSS Variables for colors */
:root {
    --main-bg-color: <?php echo $Mmain_bgcolor ?>;
    --head-color: <?php echo $Mhead_color ?>;
    --subhead-color: <?php echo $Msubhead_color ?>;
    --selected-color: <?php echo $Mselected_color ?>;
    --std-row1-bg: #<?php echo $SSstd_row1_background ?>;
    --std-row2-bg: #<?php echo $SSstd_row2_background ?>;
    --menu-bg: #<?php echo $SSmenu_background ?>;
    --frame-bg: #<?php echo $SSframe_background ?>;
}

/* Base styles */
body {
    margin: 0;
    padding: 0;
    font-family: 'Helvetica Neue', Arial, sans-serif;
    background-color: white;
}

/* Utility classes */
.auraltext {
    position: absolute;
    font-size: 0;
    left: -1000px;
}

.chart-td {
    background-image: url(images/gridline58.gif);
    background-repeat: repeat-x;
    background-position: left top;
    border-left: 1px solid #e5e5e5;
    border-right: 1px solid #e5e5e5;
    padding: 0;
    border-bottom: 1px solid #e5e5e5;
    background-color: transparent;
}

/* Header styles */
.head-style {
    background-color: var(--main-bg-color);
    transition: background-color 0.2s ease;
}

.head-style:hover {
    background-color: #262626;
}

.head-style-selected {
    background-color: var(--head-color);
}

.head-style-selected:hover {
    background-color: var(--head-color);
}

/* Subheader styles */
.subhead-style {
    background-color: var(--subhead-color);
    transition: background-color 0.2s ease;
}

.subhead-style:hover {
    background-color: white;
}

.subhead-style-selected {
    background-color: var(--selected-color);
}

.subhead-style-selected:hover {
    background-color: var(--selected-color);
}

/* Admin menu styles */
.adminmenu-style-selected {
    background-color: white;
}

.adminmenu-style-selected:hover {
    background-color: #E6E6E6;
}

/* Records list styles */
.records-list-x {
    background-color: var(--std-row2-bg);
    transition: background-color 0.2s ease;
}

.records-list-x:hover {
    background-color: #E6E6E6;
}

.records-list-y {
    background-color: var(--std-row1-bg);
    transition: background-color 0.2s ease;
}

.records-list-y:hover {
    background-color: #E6E6E6;
}

/* Horizontal line styles */
.horiz-line {
    height: 0;
    margin: 0;
    border-bottom: 1px solid #E6E6E6;
    font-size: 1px;
}

.horiz-line-grey {
    height: 0;
    margin: 0;
    border-bottom: 1px solid #9E9E9E;
    font-size: 1px;
}

/* Sub-subhead links */
.sub-sub-head-links {
    font-family: 'Helvetica Neue', Arial, sans-serif;
    font-size: 11px;
    color: black;
}

/* Diff styles for special case */
<?php if ($ADD == '730000000000000'): ?>
.diff table {
    margin: 1px;
    border-collapse: collapse;
    border-spacing: 0;
}

.diff td {
    vertical-align: top;
    font-family: monospace;
    font-size: 9px;
}

.diff span {
    display: block;
    min-height: 1px;
    margin-top: -1px;
    padding: 1px;
}

* html .diff span {
    height: 1px;
}

.diff span:first-child {
    margin-top: 1px;
}

.diff-deleted span {
    border: 1px solid rgb(255, 51, 0);
    background: rgb(255, 173, 153);
}

.diff-inserted span {
    border: 1px solid rgb(51, 204, 51);
    background: rgb(102, 255, 51);
}
<?php endif; ?>
</style>

</head>
<body <?php 
    if (($SSadmin_modify_refresh > 1) && preg_match("/^3|^4/", $ADD)) {
        echo 'onload="modify_refresh_display();"';
    } 
?>>
<!-- INTERNATIONALIZATION-LINKS-PLACEHOLDER-VICIDIAL -->

<?php
// Set default font sizes if not set
 $header_font_size = ($header_font_size < 4) ? '12' : $header_font_size;
 $subheader_font_size = ($subheader_font_size < 4) ? '11' : $subheader_font_size;
 $subcamp_font_size = ($subcamp_font_size < 4) ? '11' : $subcamp_font_size;
?>

<div class="admin-container">
    <header class="admin-header">
        <center>
            <table bgcolor="white" cellpadding="0" cellspacing="0">
                <tr>
                    <!-- BEGIN SIDEBAR NAVIGATION -->
                    <td valign="top" width="170" bgcolor="<?php echo $SSmenu_background ?>" align="center" valign="middle">
                        <a href="<?php echo $ADMIN ?>">
                            <img src="<?php echo $selected_logo; ?>" width="170" height="45" border="0" alt="System logo">
                        </a>
                        <b><font face="Arial, Helvetica" color="white"><?php echo _QXZ("ADMINISTRATION"); ?></font></b><br>
                        
                        <nav class="admin-nav">
                            <table cellpadding="2" cellspacing="0" bgcolor="<?php echo $SSmenu_background ?>" width="160">
                                <?php
                                // Check user permissions
                                if (($reports_only_user < 1) && ($qc_only_user < 1)) {
                                    // Reports navigation
                                    echo '<tr width="160"><td><div class="horiz-line"></div></td></tr>';
                                    echo '<tr bgcolor="' . $SSmenu_background;
                                    if ($SSadmin_row_click > 0) {
                                        echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=999999\';"';
                                    }
                                    echo '"><td align="left" ' . $reports_hh . '>';
                                    echo '<a href="' . $ADMIN . '?ADD=999999" style="text-decoration:none;">' . $reports_icon . ' <font style="font-family:HELVETICA;font-size:' . $header_font_size . ';color:' . $reports_fc . '">' . $reports_bold . ' ' . _QXZ("Reports") . ' </a>';
                                    echo '</td></tr>';
                                    
                                    // Users navigation
                                    echo '<tr width="100%"><td><div class="horiz-line"></div></td></tr>';
                                    echo '<tr width="160" bgcolor="' . $SSmenu_background;
                                    if ($SSadmin_row_click > 0) {
                                        echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=0A\';"';
                                    }
                                    echo '"><td align="left" ' . $users_hh . ' width="160">';
                                    echo '<a href="' . $ADMIN . '?ADD=0A" style="text-decoration:none;">' . $users_icon . ' <font style="font-family:HELVETICA;font-size:' . $header_font_size . ';color:' . $users_fc . '">' . $users_bold . _QXZ("Users") . '</a>';
                                    echo '</td></tr>';
                                    
                                    // User sub-navigation
                                    if (strlen($users_hh) > 25) {
                                        $list_sh = ($sh == 'list') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $new_sh = ($sh == 'new') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $copy_sh = ($sh == 'copy') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $search_sh = ($sh == 'search') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $stats_sh = ($sh == 'stats') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $status_sh = ($sh == 'status') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $sheet_sh = ($sh == 'sheet') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $territory_sh = ($sh == 'territory') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $newlimit_sh = ($sh == 'newlimit') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        
                                        echo '<tr ' . $list_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=0A\';"';
                                        }
                                        echo '><td align="left">';
                                        echo '&nbsp; <a href="' . $ADMIN . '?ADD=0A" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subheader_font_size . ';color:BLACK">' . _QXZ("Show Users") . ' </a>';
                                        echo '</tr><tr ' . $new_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1\';"';
                                        }
                                        echo '><td align="left">';
                                        if ($add_copy_disabled < 1) {
                                            echo '&nbsp; <a href="' . $ADMIN . '?ADD=1" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subheader_font_size . ';color:BLACK">' . _QXZ("Add A New User") . ' </a>';
                                            echo '</tr><tr ' . $copy_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1A\';"';
                                            }
                                            echo '><td align="left">';
                                            echo '&nbsp; <a href="' . $ADMIN . '?ADD=1A" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subheader_font_size . ';color:BLACK">' . _QXZ("Copy User") . ' </a>';
                                        }
                                        echo '</tr><tr ' . $search_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=550\';"';
                                        }
                                        echo '><td align="left">';
                                        echo '&nbsp; <a href="' . $ADMIN . '?ADD=550" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subheader_font_size . ';color:BLACK">' . _QXZ("Search For A User") . ' </a>';
                                        echo '</tr><tr ' . $stats_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'./user_stats.php?user=' . $user . '\';"';
                                        }
                                        echo '><td align="left">';
                                        echo '&nbsp; <a href="./user_stats.php?user=' . $user . '" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subheader_font_size . ';color:BLACK">' . _QXZ("User Stats") . ' </a>';
                                        echo '</tr><tr ' . $status_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'./user_status.php?user=' . $user . '\';"';
                                        }
                                        echo '><td align="left">';
                                        echo '&nbsp; <a href="./user_status.php?user=' . $user . '" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subheader_font_size . ';color:BLACK">' . _QXZ("User Status") . ' </a>';
                                        echo '</tr><tr ' . $sheet_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'./AST_agent_time_sheet.php?agent=' . $user . '\';"';
                                        }
                                        echo '><td align="left">';
                                        echo '&nbsp; <a href="./AST_agent_time_sheet.php?agent=' . $user . '" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subheader_font_size . ';color:BLACK">' . _QXZ("Time Sheet") . ' </a></td></tr>';
                                        
                                        // User territories
                                        if (($SSuser_territories_active > 0) || ($user_territories_active > 0)) {
                                            echo '</tr><tr ' . $territory_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'./user_territories.php?agent=' . $user . '\';"';
                                            }
                                            echo '><td align="left">';
                                            echo '&nbsp; <a href="./user_territories.php?agent=' . $user . '" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subheader_font_size . ';color:BLACK">' . _QXZ("User Territories") . ' </a></td></tr>';
                                        }
                                        
                                        // User new lead limits
                                        if ($SSuser_new_lead_limit > 0) {
                                            echo '</tr><tr ' . $newlimit_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'./admin_user_list_new.php?user=---ALL---&list_id=NONE&stage=overall\';"';
                                            }
                                            echo '><td align="left">';
                                            echo '&nbsp; <a href="./admin_user_list_new.php?user=---ALL---&list_id=NONE&stage=overall" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subheader_font_size . ';color:BLACK">' . _QXZ("Overall New Lead Limits") . ' </a></td></tr>';
                                        }
                                    }
                                    
                                    // Campaigns navigation
                                    echo '<tr width="160"><td><div class="horiz-line"></div></td></tr>';
                                    echo '<tr bgcolor="' . $SSmenu_background;
                                    if ($SSadmin_row_click > 0) {
                                        echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=10\';"';
                                    }
                                    echo '"><td align="left" ' . $campaigns_hh . '>';
                                    echo '<a href="' . $ADMIN . '?ADD=10" style="text-decoration:none;">' . $campaigns_icon . ' <font style="font-family:HELVETICA;font-size:' . $header_font_size . ';color:' . $campaigns_fc . '">' . $campaigns_bold . _QXZ("Campaigns") . '</a>';
                                    echo '</td></tr>';
                                    
                                    // Campaign sub-navigation
                                    if (strlen($campaigns_hh) > 25) {
                                        if ($sh == 'basic' || $sh == 'detail' || $sh == 'dialstat') {
                                            $sh = 'list';
                                        }
                                        
                                        $list_sh = ($sh == 'list') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $status_sh = ($sh == 'status') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $hotkey_sh = ($sh == 'hotkey') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $recycle_sh = ($sh == 'recycle') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $autoalt_sh = ($sh == 'autoalt') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $pause_sh = ($sh == 'pause') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $listmix_sh = ($sh == 'listmix') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $preset_sh = ($sh == 'preset') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $accid_sh = ($sh == 'accid') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        
                                        echo '<tr ' . $list_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=10\';"';
                                        }
                                        echo '><td align="left" ' . $list_sh . '>&nbsp; <a href="' . $ADMIN . '?ADD=10" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subheader_font_size . ';color:BLACK">' . _QXZ("Campaigns Main") . '</a></td>';
                                        echo '</tr><tr ' . $status_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=32\';"';
                                        }
                                        echo '><td align="left" ' . $status_sh . '>&nbsp; <a href="' . $ADMIN . '?ADD=32" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subheader_font_size . ';color:BLACK">' . _QXZ("Statuses") . '</a></td>';
                                        echo '</tr><tr ' . $hotkey_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=33\';"';
                                        }
                                        echo '><td align="left" ' . $hotkey_sh . '>&nbsp; <a href="' . $ADMIN . '?ADD=33" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subheader_font_size . ';color:BLACK">' . _QXZ("HotKeys") . '</a></td>';
                                        
                                        if ($SSoutbound_autodial_active > 0) {
                                            echo '</tr><tr ' . $recycle_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=35\';"';
                                            }
                                            echo '><td align="left" ' . $recycle_sh . '>&nbsp; <a href="' . $ADMIN . '?ADD=35" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subheader_font_size . ';color:BLACK">' . _QXZ("Lead Recycle") . '</a></td>';
                                            echo '</tr><tr ' . $autoalt_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=36\';"';
                                            }
                                            echo '><td align="left" ' . $autoalt_sh . '>&nbsp; <a href="' . $ADMIN . '?ADD=36" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subheader_font_size . ';color:BLACK">' . _QXZ("Auto-Alt Dial") . '</a></td>';
                                            echo '</tr><tr ' . $listmix_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=39\';"';
                                            }
                                            echo '><td align="left" ' . $listmix_sh . '>&nbsp; <a href="' . $ADMIN . '?ADD=39" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subheader_font_size . ';color:BLACK">' . _QXZ("List Mix") . '</a></td>';
                                        }
                                        
                                        echo '</tr><tr ' . $pause_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=37\';"';
                                        }
                                        echo '><td align="left" ' . $pause_sh . '>&nbsp; <a href="' . $ADMIN . '?ADD=37" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subheader_font_size . ';color:BLACK">' . _QXZ("Pause Codes") . '</a></td>';
                                        echo '</tr><tr ' . $preset_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=301\';"';
                                        }
                                        echo '><td align="left" ' . $preset_sh . '>&nbsp; <a href="' . $ADMIN . '?ADD=301" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subheader_font_size . ';color:BLACK">' . _QXZ("Presets") . '</a></td>';
                                        
                                        if ($SScampaign_cid_areacodes_enabled > 0) {
                                            echo '</tr><tr ' . $accid_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=302\';"';
                                            }
                                            echo '><td align="left" ' . $accid_sh . '>&nbsp; <a href="' . $ADMIN . '?ADD=302" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subheader_font_size . ';color:BLACK">' . _QXZ("AC-CID") . '</a></td>';
                                        }
                                    }
                                    
                                    // Lists navigation
                                    if ($SSoutbound_autodial_active > 0) {
                                        echo '<tr width="160"><td><div class="horiz-line"></div></td></tr>';
                                        echo '<tr bgcolor="' . $SSmenu_background;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=100\';"';
                                        }
                                        echo '"><td align="left" ' . $lists_hh . '>';
                                        echo '<a href="' . $ADMIN . '?ADD=100" style="text-decoration:none;">' . $lists_icon . ' <font style="font-family:HELVETICA;font-size:' . $header_font_size . ';color:' . $lists_fc . '">' . $lists_bold . _QXZ("Lists") . '</a>';
                                        echo '</td></tr>';
                                        
                                        // Lists sub-navigation
                                        if (strlen($lists_hh) > 25) {
                                            $list_sh = ($sh == 'list') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                            $new_sh = ($sh == 'new') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                            $search_sh = ($sh == 'search') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                            $lead_sh = ($sh == 'lead') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                            $load_sh = ($sh == 'load') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                            $dnc_sh = ($sh == 'dnc') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                            $custom_sh = ($sh == 'custom') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                            $cpcust_sh = ($sh == 'cpcust') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                            $droplist_sh = ($sh == 'droplist') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                            
                                            $DNClink = ($LOGdelete_from_dnc > 0) ? _QXZ("Add-Delete DNC Number") : _QXZ("Add DNC Number");
                                            
                                            echo '<tr ' . $list_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=100\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                            echo '<a href="' . $ADMIN . '?ADD=100" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Show Lists") . ' </a>';
                                            echo '</tr><tr ' . $new_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=111\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                            if ($add_copy_disabled < 1) {
                                                echo '<a href="' . $ADMIN . '?ADD=111" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Add A New List") . ' </a>';
                                                echo '</tr><tr ' . $search_sh;
                                                if ($SSadmin_row_click > 0) {
                                                    echo ' onclick="window.document.location=\'admin_search_lead.php\';"';
                                                }
                                                echo '><td align="left">&nbsp;';
                                            }
                                            echo '<a href="admin_search_lead.php" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Search For A Lead") . ' </a>';
                                            echo '</tr><tr ' . $lead_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'admin_modify_lead.php\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                            echo '<a href="admin_modify_lead.php" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Add A New Lead") . ' </a>';
                                            echo '</tr><tr ' . $dnc_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=121\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                            echo '<a href="' . $ADMIN . '?ADD=121" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . $DNClink . ' </a>';
                                            echo '</tr><tr ' . $load_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'admin_listloader_fourth_gen.php\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                            echo '<a href="./admin_listloader_fourth_gen.php" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Load New Leads") . ' </a>';
                                            
                                            if ($SScustom_fields_enabled > 0) {
                                                $admin_lists_custom = 'admin_lists_custom.php';
                                                echo '</tr><tr ' . $custom_sh;
                                                if ($SSadmin_row_click > 0) {
                                                    echo ' onclick="window.document.location=\'' . $admin_lists_custom . '\';"';
                                                }
                                                echo '><td align="left">&nbsp;';
                                                echo '<a href="./' . $admin_lists_custom . '" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("List Custom Fields") . ' </a>';
                                                echo '</tr><tr ' . $cpcust_sh;
                                                if ($SSadmin_row_click > 0) {
                                                    echo ' onclick="window.document.location=\'' . $admin_lists_custom . '?action=COPY_FIELDS_FORM\';"';
                                                }
                                                echo '><td align="left">&nbsp;';
                                                echo '<a href="./' . $admin_lists_custom . '?action=COPY_FIELDS_FORM" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Copy Custom Fields") . ' </a>';
                                            }
                                            
                                            if ($SSenable_drop_lists > 0) {
                                                echo '<tr ' . $droplist_sh;
                                                if ($SSadmin_row_click > 0) {
                                                    echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=100\';"';
                                                }
                                                echo '><td align="left">&nbsp;';
                                                echo '<a href="' . $ADMIN . '?ADD=130" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Drop Lists") . ' </a>';
                                            }
                                            
                                            echo '</td></tr>';
                                        }
                                    }
                                    
                                    // QC navigation
                                    if (($SSqc_features_active == '1') && ($qc_auth == '1')) {
                                        echo '<tr width="160"><td><div class="horiz_line"></div></td></tr>';
                                        echo '<tr bgcolor="' . $SSmenu_background;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=100000000000000\';"';
                                        }
                                        echo '"><td align="left" ' . $qc_hh . '>';
                                        echo '<a href="' . $ADMIN . '?ADD=100000000000000" style="text-decoration:none;">' . $qc_icon . ' <font style="font-family:HELVETICA;font-size:' . $header_font_size . ';color:' . $qc_fc . '">' . $qc_bold . ' ' . _QXZ("Quality Control") . ' </font></a>';
                                        echo '</td></tr>';
                                        
                                        if (strlen($qc_hh) > 25) {
                                            if ($qc_display_group_type == "CAMPAIGN") {
                                                $sh = "campaign";
                                            } else if ($qc_display_group_type == "INGROUP") {
                                                $sh = "ingroup";
                                            } else if ($qc_display_group_type == "LIST") {
                                                $sh = "list";
                                            } else if ($qc_display_group_type == "SCORECARD") {
                                                $sh = "scorecard";
                                            }
                                            
                                            if (!$sh) {
                                                $sh = "campaign";
                                            }
                                            
                                            $campaign_sh = ($sh == 'campaign') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                            $ingroup_sh = ($sh == 'ingroup') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                            $list_sh = ($sh == 'list') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                            $enter_sh = ($sh == 'enter') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                            $modify_sh = ($sh == 'modify') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                            $scorecard_sh = ($sh == 'scorecard') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                            
                                            echo '<tr ' . $campaign_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=100000000000000&qc_display_group_type=CAMPAIGN\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                            echo '<a href="' . $ADMIN . '?ADD=100000000000000&qc_display_group_type=CAMPAIGN" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("QC Calls by Campaign") . ' </font></a>';
                                            echo '</td></tr>';
                                            echo '<tr ' . $list_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=100000000000000&qc_display_group_type=LIST\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                            echo '<a href="' . $ADMIN . '?ADD=100000000000000&qc_display_group_type=LIST" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("QC Calls by List") . ' </font></a>';
                                            echo '</td></tr>';
                                            echo '<tr ' . $ingroup_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=100000000000000&qc_display_group_type=INGROUP\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                            echo '<a href="' . $ADMIN . '?ADD=100000000000000&qc_display_group_type=INGROUP" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("QC Calls by Ingroup") . ' </font></a>';
                                            echo '</td></tr>';
                                            echo '<tr ' . $scorecard_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="qc_scorecards.php\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                            echo '<a href="qc_scorecards.php" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Show QC Scorecards") . ' </font></a>';
                                            echo '</td></tr>';
                                            echo '<tr ' . $modify_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=341111111111111\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                            echo '<a href="' . $ADMIN . '?ADD=341111111111111" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Modify QC Codes") . ' </font></a>';
                                            echo '</td></tr>';
                                        }
                                    }
                                    
                                    // Scripts navigation
                                    echo '<tr width="160"><td><div class="horiz_line"></div></td></tr>';
                                    echo '<tr bgcolor="' . $SSmenu_background;
                                    if ($SSadmin_row_click > 0) {
                                        echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1000000\';"';
                                    }
                                    echo '"><td align="left" ' . $scripts_hh . '>';
                                    echo '<a href="' . $ADMIN . '?ADD=1000000" style="text-decoration:none;">' . $scripts_icon . ' <font style="font-family:HELVETICA;font-size:' . $header_font_size . ';color:' . $scripts_fc . '">' . $scripts_bold . ' ' . _QXZ("Scripts") . ' </a>';
                                    echo '</td></tr>';
                                    
                                    if (strlen($scripts_hh) > 25) {
                                        $list_sh = ($sh == 'list') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $new_sh = ($sh == 'new') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        
                                        echo '<tr ' . $list_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1000000\';"';
                                        }
                                        echo '><td align="left">&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=1000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Show Scripts") . ' </a>';
                                        echo '</tr><tr ' . $new_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1111111\';"';
                                        }
                                        echo '><td align="left">&nbsp;';
                                        if ($add_copy_disabled < 1) {
                                            echo '<a href="' . $ADMIN . '?ADD=1111111" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Add A New Script") . ' </a>';
                                        }
                                        echo '</td></tr>';
                                    }
                                    
                                    // Filters navigation
                                    if ($SSoutbound_autodial_active > 0) {
                                        echo '<tr width="160"><td><div class="horiz_line"></div></td></tr>';
                                        echo '<tr bgcolor="' . $SSmenu_background;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=10000000\';"';
                                        }
                                        echo '"><td align="left" ' . $filters_hh . '>';
                                        echo '<a href="' . $ADMIN . '?ADD=10000000" style="text-decoration:none;">' . $filters_icon . ' <font style="font-family:HELVETICA;font-size:' . $header_font_size . ';color:' . $filters_fc . '">' . $filters_bold . ' ' . _QXZ("Filters") . ' </a>';
                                        echo '</td></tr>';
                                        
                                        if (strlen($filters_hh) > 25) {
                                            $list_sh = ($sh == 'list') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                            $new_sh = ($sh == 'new') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                            
                                            echo '<tr ' . $list_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=10000000\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                            echo '<a href="' . $ADMIN . '?ADD=10000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Show Filters") . ' </a>';
                                            echo '</tr><tr ' . $new_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=11111111\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                            if ($add_copy_disabled < 1) {
                                                echo '<a href="' . $ADMIN . '?ADD=11111111" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Add A New Filter") . ' </a>';
                                            }
                                            echo '</td></tr>';
                                        }
                                    }
                                    
                                    // Ingroups navigation
                                    echo '<tr width="160"><td><div class="horiz_line"></div></td></tr>';
                                    echo '<tr bgcolor="' . $SSmenu_background;
                                    if ($SSadmin_row_click > 0) {
                                        echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1001\';"';
                                    }
                                    echo '"><td align="left" ' . $ingroups_hh . '>';
                                    echo '<a href="' . $ADMIN . '?ADD=1001" style="text-decoration:none;">' . $inbound_icon . ' <font style="font-family:HELVETICA;font-size:' . $header_font_size . ';color:' . $ingroups_fc . '">' . $ingroups_bold . ' ' . _QXZ("Inbound") . ' </a>';
                                    echo '</td></tr>';
                                    
                                    if (strlen($ingroups_hh) > 25) {
                                        $listIG_sh = ($sh == 'listIG') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $newIG_sh = ($sh == 'newIG') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $copyIG_sh = ($sh == 'copyIG') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $listEG_sh = ($sh == 'listEG') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $newEG_sh = ($sh == 'newEG') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $copyEG_sh = ($sh == 'copyEG') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $listCG_sh = ($sh == 'listCG') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $newCG_sh = ($sh == 'newCG') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $copyCG_sh = ($sh == 'copyCG') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $listDID_sh = ($sh == 'listDID') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $newDID_sh = ($sh == 'newDID') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $copyDID_sh = ($sh == 'copyDID') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $didRA_sh = ($sh == 'didRA') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $listCM_sh = ($sh == 'listCM') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $newCM_sh = ($sh == 'newCM') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $copyCM_sh = ($sh == 'copyCM') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $listFPG_sh = ($sh == 'listFPG') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $newFPG_sh = ($sh == 'newFPG') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $addFPG_sh = ($sh == 'addFPG') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        
                                        $FPGlink = ($LOGdelete_from_dnc > 0) ? _QXZ("Add-Delete FPG Number") : _QXZ("Add FPG Number");
                                        
                                        echo '<tr ' . $listIG_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1000\';"';
                                        }
                                        echo '><td align="left">&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=1000" style="text-decoration:none;"><img src="images/icon_black_inbound.png" border="0" alt="In-Groups" width="14" height="14" valign="middle"> <font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Show In-Groups") . ' </a>';
                                        echo '</td></tr><tr ' . $newIG_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1111\';"';
                                        }
                                        echo '><td align="left">&nbsp;';
                                        if ($add_copy_disabled < 1) {
                                            echo '<a href="' . $ADMIN . '?ADD=1111" style="text-decoration:none;"><img src="images/blank.gif" border="0" alt=" " width="14" height="14" valign="middle"> <font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Add A New In-Group") . ' </a>';
                                            echo '</td></tr><tr ' . $copyIG_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1211\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                            echo '<a href="' . $ADMIN . '?ADD=1211" style="text-decoration:none;"><img src="images/blank.gif" border="0" alt=" " width="14" height="14" valign="middle"> <font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Copy In-Group") . ' </a>';
                                        }
                                        echo '<tr width="160" class="subhead_style"><td><div class="horiz_line_grey"></div></td></tr>';
                                        
                                        if ($SSemail_enabled > 0) {
                                            echo '<tr ' . $listEG_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1800\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                            echo '<a href="' . $ADMIN . '?ADD=1800" style="text-decoration:none;"><img src="images/icon_email.png" border="0" alt="Email Groups" width="14" height="14" valign="middle"> <font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Show Email Groups") . ' </a>';
                                            echo '</td></tr><tr ' . $newEG_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1811\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                            if ($add_copy_disabled < 1) {
                                                echo '<a href="' . $ADMIN . '?ADD=1811" style="text-decoration:none;"><img src="images/blank.gif" border="0" alt=" " width="14" height="14" valign="middle"> <font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Add New Email Group") . ' </a>';
                                                echo '</td></tr><tr ' . $copyEG_sh;
                                                if ($SSadmin_row_click > 0) {
                                                    echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1911\';"';
                                                }
                                                echo '><td align="left">&nbsp;';
                                                echo '<a href="' . $ADMIN . '?ADD=1911" style="text-decoration:none;"><img src="images/blank.gif" border="0" alt=" " width="14" height="14" valign="middle"> <font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Copy Email Group") . ' </a>';
                                            }
                                            echo '<tr width="160" class="subhead_style"><td><div class="horiz_line_grey"></div></td></tr>';
                                        }
                                        
                                        if ($SSchat_enabled > 0) {
                                            echo '<tr ' . $listCG_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1900\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                            echo '<a href="' . $ADMIN . '?ADD=1900" style="text-decoration:none;"><img src="images/icon_chat.png" border="0" alt=" Chat Groups" width="14" height="14" valign="middle"> <font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Show Chat Groups") . ' </a>';
                                            echo '</td></tr><tr ' . $newCG_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=18111\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                            if ($add_copy_disabled < 1) {
                                                echo '<a href="' . $ADMIN . '?ADD=18111" style="text-decoration:none;"><img src="images/blank.gif" border="0" alt=" " width="14" height="14" valign="middle"> <font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Add New Chat Group") . ' </a>';
                                                echo '</td></tr><tr ' . $copyCG_sh;
                                                if ($SSadmin_row_click > 0) {
                                                    echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=19111\';"';
                                                }
                                                echo '><td align="left">&nbsp;';
                                                echo '<a href="' . $ADMIN . '?ADD=19111" style="text-decoration:none;"><img src="images/blank.gif" border="0" alt=" " width="14" height="14" valign="middle"> <font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Copy Chat Group") . ' </a>';
                                            }
                                            echo '<tr width="160" class="subhead_style"><td><div class="horiz_line_grey"></div></td></tr>';
                                        }
                                        
                                        echo '</td></tr><tr ' . $listDID_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1300\';"';
                                        }
                                        echo '><td align="left">&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=1300" style="text-decoration:none;"><img src="images/icon_cidgroups.png" border="0" alt="DIDs" width="14" height="14" valign="middle"> <font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Show DIDs") . ' </a>';
                                        echo '</td></tr><tr ' . $newDID_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1311\';"';
                                        }
                                        echo '><td align="left">&nbsp;';
                                        if ($add_copy_disabled < 1) {
                                            echo '<a href="' . $ADMIN . '?ADD=1311" style="text-decoration:none;"><img src="images/blank.gif" border="0" alt=" " width="14" height="14" valign="middle"> <font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Add A New DID") . ' </a>';
                                            echo '</td></tr><tr ' . $copyDID_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1411\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                            echo '<a href="' . $ADMIN . '?ADD=1411" style="text-decoration:none;"><img src="images/blank.gif" border="0" alt=" " width="14" height="14" valign="middle"> <font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Copy DID") . ' </a>';
                                        }
                                        
                                        if ($SSdid_ra_extensions_enabled > 0) {
                                            echo '</td></tr><tr ' . $didRA_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1320\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                            echo '<a href="' . $ADMIN . '?ADD=1320" style="text-decoration:none;"><img src="images/blank.gif" border="0" alt=" " width="14" height="14" valign="middle"> <font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("RA Extensions") . '</a>';
                                        }
                                        
                                        echo '<tr width="160" class="subhead_style"><td><div class="horiz_line_grey"></div></td></tr>';
                                        echo '</td></tr><tr ' . $listCM_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1500\';"';
                                        }
                                        echo '><td align="left">&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=1500" style="text-decoration:none;"><img src="images/icon_callmenu.png" border="0" alt="Call Menus" width="14" height="14" valign="middle"> <font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Show Call Menus") . ' </a>';
                                        echo '</td></tr><tr ' . $newCM_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1511\';"';
                                        }
                                        echo '><td align="left">&nbsp;';
                                        if ($add_copy_disabled < 1) {
                                            echo '<a href="' . $ADMIN . '?ADD=1511" style="text-decoration:none;"><img src="images/blank.gif" border="0" alt=" " width="14" height="14" valign="middle"> <font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Add A New Call Menu") . ' </a>';
                                            echo '</td></tr><tr ' . $copyCM_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1611\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                            echo '<a href="' . $ADMIN . '?ADD=1611" style="text-decoration:none;"><img src="images/blank.gif" border="0" alt=" " width="14" height="14" valign="middle"> <font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Copy Call Menu") . ' </a>';
                                        }
                                        
                                        echo '<tr width="160" class="subhead_style"><td><div class="horiz_line_grey"></div></td></tr>';
                                        echo '</td></tr><tr ' . $listFPG_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1700\';"';
                                        }
                                        echo '><td align="left">&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=1700" style="text-decoration:none;"><img src="images/icon_filterphonegroup.png" border="0" alt="Filter Phone Groups" width="14" height="14" valign="middle"> <font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Filter Phone Groups") . ' </a>';
                                        echo '</td></tr><tr ' . $newFPG_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1711\';"';
                                        }
                                        echo '><td align="left">&nbsp;';
                                        if ($add_copy_disabled < 1) {
                                            echo '<a href="' . $ADMIN . '?ADD=1711" style="text-decoration:none;"><img src="images/blank.gif" border="0" alt=" " width="14" height="14" valign="middle"> <font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Add Filter Phone Group") . ' </a>';
                                            echo '</td></tr><tr ' . $addFPG_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=171\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                        }
                                        echo '<a href="' . $ADMIN . '?ADD=171" style="text-decoration:none;"><img src="images/blank.gif" border="0" alt=" " width="14" height="14" valign="middle"> <font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . $FPGlink . ' </a>';
                                        echo '</td></tr>';
                                    }
                                    
                                    // Usergroups navigation
                                    echo '<tr width="160"><td><div class="horiz_line"></div></td></tr>';
                                    echo '<tr bgcolor="' . $SSmenu_background;
                                    if ($SSadmin_row_click > 0) {
                                        echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=100000\';"';
                                    }
                                    echo '"><td align="left" ' . $usergroups_hh . '>';
                                    echo '<a href="' . $ADMIN . '?ADD=100000" style="text-decoration:none;">' . $usergroups_icon . ' <font style="font-family:HELVETICA;font-size:' . $header_font_size . ';color:' . $usergroups_fc . '">' . $usergroups_bold . ' ' . _QXZ("User Groups") . ' </a>';
                                    echo '</td></tr>';
                                    
                                    if (strlen($usergroups_hh) > 25) {
                                        $list_sh = ($sh == 'list') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $new_sh = ($sh == 'new') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $hour_sh = ($sh == 'hour') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $bulk_sh = ($sh == 'bulk') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        
                                        echo '<tr ' . $list_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=100000\';"';
                                        }
                                        echo '><td align="left">&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=100000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Show User Groups") . ' </a>';
                                        echo '</tr><tr ' . $new_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=111111\';"';
                                        }
                                        echo '><td align="left">&nbsp;';
                                        if ($add_copy_disabled < 1) {
                                            echo '<a href="' . $ADMIN . '?ADD=111111" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Add A New User Group") . ' </a>';
                                            echo '</tr><tr ' . $hour_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'group_hourly_stats.php\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                        }
                                        echo '<a href="group_hourly_stats.php" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Group Hourly Report") . ' </a>';
                                        echo '</tr><tr ' . $bulk_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'user_group_bulk_change.php\';"';
                                        }
                                        echo '><td align="left">&nbsp;';
                                        echo '<a href="user_group_bulk_change.php" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Bulk Group Change") . ' </a>';
                                        echo '</td></tr>';
                                    }
                                    
                                    // Remote agents navigation
                                    echo '<tr width="160"><td><div class="horiz_line"></div></td></tr>';
                                    echo '<tr bgcolor="' . $SSmenu_background;
                                    if ($SSadmin_row_click > 0) {
                                        echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=10000\';"';
                                    }
                                    echo '"><td align="left" ' . $remoteagent_hh . '>';
                                    echo '<a href="' . $ADMIN . '?ADD=10000" style="text-decoration:none;">' . $remoteagents_icon . ' <font style="font-family:HELVETICA;font-size:' . $header_font_size . ';color:' . $remoteagent_fc . '">' . $remoteagent_bold . ' ' . _QXZ("Remote Agents") . ' </a>';
                                    echo '</td></tr>';
                                    
                                    if (strlen($remoteagent_hh) > 25) {
                                        $list_sh = ($sh == 'list') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $new_sh = ($sh == 'new') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $listEG_sh = ($sh == 'listEG') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $newEG_sh = ($sh == 'newEG') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        
                                        echo '<tr ' . $list_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=10000\';"';
                                        }
                                        echo '><td align="left">&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=10000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Show Remote Agents") . ' </a>';
                                        echo '</tr><tr ' . $new_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=11111\';"';
                                        }
                                        echo '><td align="left">&nbsp;';
                                        if ($add_copy_disabled < 1) {
                                            echo '<a href="' . $ADMIN . '?ADD=11111" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Add New Remote Agents") . ' </a>';
                                            echo '</tr><tr ' . $listEG_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=12000\';"';
                                            }
                                            echo '><td align="left">&nbsp;';
                                        }
                                        echo '<a href="' . $ADMIN . '?ADD=12000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Show Extension Groups") . ' </a>';
                                        echo '</tr><tr ' . $newEG_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=12111\';"';
                                        }
                                        echo '><td align="left">&nbsp;';
                                        if ($add_copy_disabled < 1) {
                                            echo '<a href="' . $ADMIN . '?ADD=12111" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Add Extension Group") . ' </a>';
                                        }
                                        echo '</td></tr>';
                                    }
                                    
                                    // Admin navigation
                                    echo '<tr width="160"><td><div class="horiz_line"></div></td></tr>';
                                    echo '<tr bgcolor="' . $SSmenu_background;
                                    if ($SSadmin_row_click > 0) {
                                        echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=999998\';"';
                                    }
                                    echo '"><td align="left" ' . $admin_hh . '>';
                                    echo '<a href="' . $ADMIN . '?ADD=999998" style="text-decoration:none;">' . $admin_icon . ' <font style="font-family:HELVETICA;font-size:' . $header_font_size . ';color:' . $admin_fc . '">' . $admin_bold . ' ' . _QXZ("Admin") . ' </a>';
                                    echo '</td></tr>';
                                    
                                    if (strlen($admin_hh) > 25) {
                                        $times_sh = ($sh == 'times') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $shifts_sh = ($sh == 'shifts') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $templates_sh = ($sh == 'templates') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $carriers_sh = ($sh == 'carriers') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $phones_sh = ($sh == 'phones') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $server_sh = ($sh == 'server') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $conference_sh = ($sh == 'conference') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $settings_sh = ($sh == 'settings') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $label_sh = ($sh == 'label') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $colors_sh = ($sh == 'colors') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $status_sh = ($sh == 'status') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $audio_sh = ($sh == 'audio') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $moh_sh = ($sh == 'moh') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $languages_sh = ($sh == 'languages') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $soundboard_sh = ($sh == 'soundboard') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $vm_sh = ($sh == 'vm') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $tts_sh = ($sh == 'tts') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $cc_sh = ($sh == 'cc') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $cts_sh = ($sh == 'cts') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $sc_sh = ($sh == 'sc') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $sg_sh = ($sh == 'sg') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $cg_sh = ($sh == 'cg') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $vmmg_sh = ($sh == 'vmmg') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $qg_sh = ($sh == 'qg') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $emails_sh = ($sh == 'emails') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $ar_sh = ($sh == 'ar') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        $il_sh = ($sh == 'il') ? 'class="subhead_style_selected"' : 'class="subhead_style"';
                                        
                                        echo '<tr ' . $times_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=100000000\';"';
                                        }
                                        echo '><td align="left" ' . $times_sh . ' colspan="2">&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=100000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_calltimes.png" border="0" alt="Users" width="14" height="14" valign="middle"> ' . _QXZ("Call Times") . ' </a></td>';
                                        echo '</tr><tr ' . $shifts_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=130000000\';"';
                                        }
                                        echo '><td align="left" ' . $shifts_sh . '>&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=130000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_shifts.png" border="0" alt="Users" width="14" height="14" valign="middle"> ' . _QXZ("Shifts") . ' </a></td>';
                                        echo '</tr><tr ' . $phones_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=10000000000\';"';
                                        }
                                        echo '><td align="left" ' . $phones_sh . '>&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=10000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_phones.png" border="0" alt="Users" width="14" height="14" valign="middle"> ' . _QXZ("Phones") . ' </a></td>';
                                        echo '</tr><tr ' . $templates_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=130000000000\';"';
                                        }
                                        echo '><td align="left" ' . $templates_sh . '>&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=130000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_templates.png" border="0" alt="Users" width="14" height="14" valign="middle"> ' . _QXZ("Templates") . ' </a></td>';
                                        echo '</tr><tr ' . $carriers_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=140000000000\';"';
                                        }
                                        echo '><td align="left" ' . $carriers_sh . '>&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=140000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_carriers.png" border="0" alt="Users" width="14" height="14" valign="middle"> ' . _QXZ("Carriers") . ' </a></td>';
                                        echo '</tr><tr ' . $server_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=100000000000\';"';
                                        }
                                        echo '><td align="left" ' . $server_sh . '>&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=100000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_servers.png" border="0" alt="Users" width="14" height="14" valign="middle"> ' . _QXZ("Servers") . ' </a></td>';
                                        echo '</tr><tr ' . $conference_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=1000000000000\';"';
                                        }
                                        echo '><td align="left" ' . $conference_sh . '>&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=1000000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_conferences.png" border="0" alt="Users" width="14" height="14" valign="middle"> ' . _QXZ("Conferences") . ' </a></td>';
                                        echo '</tr><tr ' . $settings_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=311111111111111\';"';
                                        }
                                        echo '><td align="left" ' . $settings_sh . '>&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=311111111111111" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_settings.png" border="0" alt="Users" width="14" height="14" valign="middle"> ' . _QXZ("System Settings") . ' </a></td>';
                                        echo '</tr><tr ' . $label_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=180000000000\';"';
                                        }
                                        echo '><td align="left" ' . $label_sh . '>&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=180000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_screenlabels.png" border="0" alt="Labels" width="14" height="14" valign="middle"> ' . _QXZ("Screen Labels") . ' </a></td>';
                                        echo '</tr><tr ' . $colors_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=182000000000\';"';
                                        }
                                        echo '><td align="left" ' . $colors_sh . '>&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=182000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_screencolors.png" border="0" alt="Colors" width="14" height="14" valign="middle"> ' . _QXZ("Screen Colors") . ' </a></td>';
                                        echo '</tr><tr ' . $status_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=321111111111111\';"';
                                        }
                                        echo '><td align="left" ' . $status_sh . '>&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=321111111111111" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_statuses.png" border="0" alt="Statuses" width="14" height="14" valign="middle"> ' . _QXZ("System Statuses") . ' </a></td>';
                                        echo '</tr><tr ' . $sg_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=193000000000\';"';
                                        }
                                        echo '><td align="left" ' . $sg_sh . '>&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=193000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_statusgroups.png" border="0" alt="Status Groups" width="14" height="14" valign="middle"> ' . _QXZ("Status Groups") . ' </a></td>';
                                        
                                        if ($SScampaign_cid_areacodes_enabled > 0) {
                                            echo '</tr><tr ' . $cg_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=196000000000\';"';
                                            }
                                            echo '><td align="left" ' . $cg_sh . '>&nbsp;';
                                            echo '<a href="' . $ADMIN . '?ADD=196000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_cidgroups.png" border="0" alt="CID Groups" width="14" height="14" valign="middle"> ' . _QXZ("CID Groups") . ' </a></td>';
                                        }
                                        
                                        echo '</tr><tr ' . $vm_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=170000000000\';"';
                                        }
                                        echo '><td align="left" ' . $vm_sh . '>&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=170000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_voicemail.png" border="0" alt="Users" width="14" height="14" valign="middle"> ' . _QXZ("Voicemail") . ' </a></td>';
                                        echo '</tr>';
                                        
                                        if ($SSemail_enabled > 0) {
                                            echo '<tr ' . $emails_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'admin_email_accounts.php\';"';
                                            }
                                            echo '><td align="left" ' . $emails_sh . '>&nbsp;';
                                            echo '<a href="admin_email_accounts.php" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_email.png" border="0" alt="Users" width="14" height="14" valign="middle"> ' . _QXZ("Email Accounts") . ' </a></td>';
                                            echo '</tr>';
                                        }
                                        
                                        if (($sounds_central_control_active > 0) || ($SSsounds_central_control_active > 0)) {
                                            echo '<tr ' . $audio_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'audio_store.php\';"';
                                            }
                                            echo '><td align="left" ' . $audio_sh . '>&nbsp;';
                                            echo '<a href="audio_store.php" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_audiostore.png" border="0" alt="Users" width="14" height="14" valign="middle"> ' . _QXZ("Audio Store") . ' </a></td>';
                                            echo '</tr>';
                                            echo '<tr ' . $moh_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=160000000000\';"';
                                            }
                                            echo '><td align="left" ' . $moh_sh . '>&nbsp;';
                                            echo '<a href="' . $ADMIN . '?ADD=160000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_musiconhold.png" border="0" alt="Users" width="14" height="14" valign="middle"> ' . _QXZ("Music On Hold") . ' </a></td>';
                                            echo '</tr>';
                                            
                                            if ($SSenable_languages > 0) {
                                                echo '<tr ' . $languages_sh;
                                                if ($SSadmin_row_click > 0) {
                                                    echo ' onclick="window.document.location=\'admin_languages.php?ADD=163000000000\';"';
                                                }
                                                echo '><td align="left" ' . $languages_sh . '>&nbsp;';
                                                echo '<a href="admin_languages.php?ADD=163000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_languages.png" border="0" alt="Users" width="14" height="14" valign="middle"> ' . _QXZ("Languages") . ' </a></td>';
                                                echo '</tr>';
                                            }
                                            
                                            if ((preg_match("/soundboard/", $SSactive_modules)) || ($SSagent_soundboards > 0)) {
                                                echo '<tr ' . $soundboard_sh;
                                                if ($SSadmin_row_click > 0) {
                                                    echo ' onclick="window.document.location=\'admin_soundboard.php?ADD=162000000000\';"';
                                                }
                                                echo '><td align="left" ' . $soundboard_sh . '>&nbsp;';
                                                echo '<a href="admin_soundboard.php?ADD=162000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_audiosoundboards.png" border="0" alt="Audio Soundboards" width="14" height="14" valign="middle"> ' . _QXZ("Audio Soundboards") . ' </a></td>';
                                                echo '</tr>';
                                            }
                                            
                                            echo '<tr ' . $vmmg_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=197000000000\';"';
                                            }
                                            echo '><td align="left" ' . $vmmg_sh . '>&nbsp;';
                                            echo '<a href="' . $ADMIN . '?ADD=197000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_vm_messages.png" border="0" alt="VM Message Groups" width="14" height="14" valign="middle"> ' . _QXZ("VM Message Groups") . ' </a></td>';
                                            echo '</tr>';
                                        }
                                        
                                        if ($SSenable_tts_integration > 0) {
                                            echo '<tr ' . $tts_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=150000000000\';"';
                                            }
                                            echo '><td align="left" ' . $tts_sh . '>&nbsp;';
                                            echo '<a href="' . $ADMIN . '?ADD=150000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_texttospeech.png" border="0" alt="Text To Speech" width="14" height="14" valign="middle"> ' . _QXZ("Text To Speech") . ' </a></td>';
                                            echo '</tr>';
                                        }
                                        
                                        if ($SScallcard_enabled > 0) {
                                            echo '<tr ' . $cc_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'callcard_admin.php\';"';
                                            }
                                            echo '><td align="left" ' . $cc_sh . '>&nbsp;';
                                            echo '<a href="callcard_admin.php" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_callcard.png" border="0" alt="Users" width="14" height="14" valign="middle"> ' . _QXZ("CallCard Admin") . ' </a></td>';
                                            echo '</tr>';
                                        }
                                        
                                        if ($SScontacts_enabled > 0) {
                                            echo '<tr ' . $cts_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=190000000000\';"';
                                            }
                                            echo '><td align="left" ' . $cts_sh . '>&nbsp;';
                                            echo '<a href="' . $ADMIN . '?ADD=190000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_contacts.png" border="0" alt="Users" width="14" height="14" valign="middle"> ' . _QXZ("Contacts") . ' </a></td>';
                                            echo '</tr>';
                                        }
                                        
                                        echo '</tr><tr ' . $sc_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=192000000000\';"';
                                        }
                                        echo '><td align="left" ' . $sc_sh . '>&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=192000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_settingscontainer.png" border="0" alt="Users" width="14" height="14" valign="middle"> ' . _QXZ("Settings Containers") . ' </a></td>';
                                        echo '</tr>';
                                        
                                        if ($SSenable_auto_reports > 0) {
                                            echo '<tr ' . $ar_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=194000000000\';"';
                                            }
                                            echo '><td align="left" ' . $ar_sh . '>&nbsp;';
                                            echo '<a href="' . $ADMIN . '?ADD=194000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_autoreports.png" border="0" alt="Automated Reports" width="14" height="14" valign="middle"> ' . _QXZ("Automated Reports") . ' </a></td>';
                                            echo '</tr>';
                                        }
                                        
                                        if ($SSallow_ip_lists > 0) {
                                            echo '<tr ' . $il_sh;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=195000000000\';"';
                                            }
                                            echo '><td align="left" ' . $il_sh . '>&nbsp;';
                                            echo '<a href="' . $ADMIN . '?ADD=195000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_iplists.png" border="0" alt="IP Lists" width="14" height="14" valign="middle"> ' . _QXZ("IP Lists") . ' </a></td>';
                                            echo '</tr>';
                                        }
                                        
                                        echo '</tr><tr ' . $qg_sh;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=198000000000\';"';
                                        }
                                        echo '><td align="left" ' . $qg_sh . '>&nbsp;';
                                        echo '<a href="' . $ADMIN . '?ADD=198000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">&nbsp; <img src="images/icon_queuegroups.png" border="0" alt="Queue Groups" width="14" height="14" valign="middle"> ' . _QXZ("Queue Groups") . ' </a></td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    // Reports only user
                                    if ($reports_only_user > 0) {
                                        echo '<tr width="160"><td><div class="horiz_line"></div></td></tr>';
                                        echo '<tr bgcolor="' . $SSmenu_background;
                                        if ($SSadmin_row_click > 0) {
                                            echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=999999\';"';
                                        }
                                        echo '"><td align="left" ' . $reports_hh . '>';
                                        echo '<a href="' . $ADMIN . '?ADD=999999" style="text-decoration:none;">' . $reports_icon . ' <font style="font-family:HELVETICA;font-size:' . $header_font_size . ';color:' . $reports_fc . '">' . $reports_bold . ' ' . _QXZ("Reports") . ' </a>';
                                        echo '</td></tr>';
                                    } else {
                                        // QC only user
                                        if (($SSqc_features_active == '1') && ($qc_auth == '1')) {
                                            echo '<tr width="160"><td><div class="horiz_line"></div></td></tr>';
                                            echo '<tr bgcolor="' . $SSmenu_background;
                                            if ($SSadmin_row_click > 0) {
                                                echo ' onclick="window.document.location=\'' . $ADMIN . '?ADD=100000000000000\';"';
                                            }
                                            echo '"><td align="left" ' . $qc_hh . '>';
                                            echo '<a href="' . $ADMIN . '?ADD=100000000000000" style="text-decoration:none;">' . $reports_icon . ' <font style="font-family:HELVETICA;font-size:' . $header_font_size . ';color:' . $reports_fc . '">' . $qc_bold . ' ' . _QXZ("Quality Control") . ' </font></a>';
                                            echo '</td></tr>';
                                            
                                            if (strlen($qc_hh) > 25) {
                                                echo '<tr bgcolor="' . $qc_color . '">';
                                                echo '<td align="left">&nbsp;';
                                                echo '<a href="' . $ADMIN . '?ADD=100000000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Show QC Campaigns") . ' </font></a>';
                                                echo '</td></tr>';
                                                echo '<tr bgcolor="' . $qc_color . '">';
                                                echo '<td align="left">&nbsp;';
                                                echo '<a href="' . $ADMIN . '?ADD=100000000000000" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Enter QC Queue") . ' </font></a>';
                                                echo '</td></tr>';
                                                echo '<tr bgcolor="' . $qc_color . '">';
                                                echo '<td align="left">&nbsp;';
                                                echo '<a href="' . $ADMIN . '?ADD=341111111111111" style="text-decoration:none;"><font style="font-family:HELVETICA;font-size:' . $subcamp_font_size . ';color:BLACK;">' . _QXZ("Modify QC Codes") . ' </font></a>';
                                                echo '</td></tr>';
                                            }
                                        }
                                    }
                                }
                                ?>
                                <tr width="160"><td><div class="horiz_line"></div></td></tr>
                            </table>
                        </nav>
                        <br>&nbsp;
                    </td>
                    <td valign="top" width="<?php echo $page_width ?>" bgcolor="<?php echo $SSframe_background ?>">
                        <!-- END SIDEBAR NAVIGATION -->
                        
                        <div class="audio-chooser-container" style="position:absolute;left:300px;top:30px;z-index:1;visibility:hidden;" id="audio_chooser_span">
                            <!-- Audio chooser content -->
                        </div>
                        
                        <table bgcolor="<?php echo $SSframe_background ?>" cellpadding="2" cellspacing="0" width="<?php echo $page_width ?>" height="15">
                            <tr bgcolor="<?php echo $SSmenu_background ?>">
                                <td align="left" bgcolor="<?php echo $SSmenu_background ?>">
                                    <font face="Arial, Helvetica" color="white" size="2">
                                        <b>
                                            <a href="<?php echo $admin_home_url_LU ?>" style="text-decoration:none;">
                                                <font face="Arial, Helvetica" color="white" size="1"><?php echo _QXZ("HOME"); ?></font>
                                            </a> | 
                                            <a href="../agc/timeclock.php?referrer=admin" style="text-decoration:none;">
                                                <font face="Arial, Helvetica" color="white" size="1"><?php echo _QXZ("Timeclock"); ?></font>
                                            </a> | 
                                            <a href="manager_chat_interface.php" style="text-decoration:none;">
                                                <font face="Arial, Helvetica" color="white" size="1"><?php echo _QXZ("Chat"); ?></font>
                                            </a> | 
                                            <a href="<?php echo $ADMIN ?>?force_logout=1" style="text-decoration:none;">
                                                <font face="Arial, Helvetica" color="white" size="1"><?php echo _QXZ("Logout"); ?></a>
                                            </a>
                                            <font face="Arial, Helvetica" color="white" size="1">(<?php echo $PHP_AUTH_USER ?>)</font>
                                            
                                            <?php if ($SSenable_languages == '1'): ?>
                                                | <a href="<?php echo $ADMIN ?>?ADD=999989" style="text-decoration:none;">
                                                    <font face="Arial, Helvetica" color="white" size="1"><?php echo _QXZ("Change language"); ?></font>
                                                </a>
                                            <?php endif; ?>
                                        </b>
                                    </font>
                                </td>
                                <td align="right">
                                    <font face="Arial, Helvetica" color="white" size="2">
                                        <b><?php echo date("l F j, Y G:i:s A"); ?> &nbsp;</b>
                                    </font>
                                </td>
                            </tr>
                            <tr bgcolor="<?php echo $SSmenu_background ?>">
                                <!-- Additional header content -->
                            </tr>
                        </table>
                        
                        <main class="admin-main-content">
                            <!-- Main content area -->
                            <?php 
                            ######################### FULL HTML HEADER END #######################################
                            ?>
                        </main>
                    </td>
                </tr>
            </table>
        </center>
    </header>
</div>            new_content = '<a href="<?php echo $ADMIN ?>?ADD=3311&did_pattern=' + selected_value + "\"><?php echo _QXZ