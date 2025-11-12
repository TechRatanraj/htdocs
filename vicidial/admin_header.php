<?php
# admin_header.php - VICIDIAL administration header
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

 $SSmenu_background='000000';           // Black menu header
 $SSframe_background='FFFFFF';          // Pure white background
 $SSstd_row1_background='F5F5F5';       // Light gray - Standard row 1
 $SSstd_row2_background='FFFFFF';       // White - Standard row 2
 $SSstd_row3_background='F0F0F0';       // Light gray - Standard row 3
 $SSstd_row4_background='FFFFFF';       // White - Standard row 4
 $SSstd_row5_background='E8E8E8';       // Light gray - Standard row 5
 $SSalt_row1_background='FFFFFF';       // White - Alternative row 1
 $SSalt_row2_background='F5F5F5';       // Light gray - Alternative row 2
 $SSalt_row3_background='FFFFFF';       // White - Alternative row 3
 $SSbutton_color='F0F0F0';              // Light gray - Button color


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
            <div class="modern-header-container">
                <div class="logo-container">
                    <a href="<?php echo htmlspecialchars($admin_home_url_LU); ?>" class="logo-link">
                        <img src="<?php echo htmlspecialchars($temp_logo); ?>" <?php echo $temp_logo_size; ?> border="0" alt="System logo" class="logo-image">
                    </a>
                </div>
            </div>
            <?php
        }
        else {
            ?>
            <div class="modern-header-container">
                <div class="header-top">
                    <div class="logo-container">
                        <a href="<?php echo htmlspecialchars($ADMIN); ?>" class="logo-link">
                            <img src="<?php echo htmlspecialchars($selected_small_logo); ?>" width="71" height="22" border="0" alt="System logo" class="logo-image">
                        </a>
                    </div>
                    
                    <div class="nav-menu">
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
                                <a href="<?php echo htmlspecialchars($ADMIN . '?ADD=999999'); ?>" class="nav-item nav-active">
                                    <span><?php echo _QXZ("Reports"); ?></span>
                                </a>
                                <?php
                            }
                            else {
                                if (($SSqc_features_active == '1') && ($qc_auth == '1')) {
                                    ?>
                                    <a href="<?php echo htmlspecialchars($ADMIN . '?ADD=100000000000000'); ?>" class="nav-item nav-active">
                                        <span><?php echo _QXZ("Quality Control"); ?></span>
                                    </a>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
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
    <div class="modern-header-container mobile">
        <div class="header-top">
            <div class="logo-container">
                <a href="./admin_mobile.php" class="logo-link">
                    <img src="<?php echo htmlspecialchars($selected_small_logo); ?>" width="71" height="22" border="0" alt="System logo" class="logo-image">
                </a>
            </div>
            <div class="nav-menu">
                <a href="admin_mobile.php?ADD=999990" class="nav-item">
                    <?php echo $admin_icon; ?> <span><?php echo _QXZ("Admin"); ?></span>
                </a>
            </div>
        </div>
    </div>
    <?php
}

//Done till small header modernization at line 309
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
            // if list change confirmation system setting is off, just submit the form
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
            // if none of the lists active status has been changed, just submit the form
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
            new_content = new_content + "<BR><?php echo_QXZ("Campaign ID"); ?>: <select size=1 name=IGcampaign_id_" + option + ' id=IGcampaign_id_' + option + '>';
            new_content = new_content + '' + IGcampaign_id_list + "\n" + '<option SELECTED>' + IGcampaign_id + '</select>';
            new_content = new_content + " &nbsp; <?php echo _QXZ("Phone Code"); ?>: <input type=text size=5 maxlength=14 name=IGphone_code_" + option + ' id=IGphone_code_' + option + ' value="' + IGphone_code + '">';
            new_content = new_content + "<BR> &nbsp; <?php echo _QXZ("VID Enter Filename"); ?>: <input type=text name=IGvid_enter_filename_" + option + " id=IGvid_enter_filename_" + option + " size=40 maxlength=255 value=\"" + IGvid_enter_filename + "\"> <a href=\"javascript:launch_chooser('IGvid_enter_filename_" + option + "','date');\"><?php echo _QXZ("audio chooser"); ?></a>";
            new_content = new_content + "<BR> &nbsp; <?php echo _QXZ("VID ID Number Filename"); ?>: <input type=text name=IGvid_id_number_filename_" + option + " id=IGvid_id_number_filename_" + option + " size=40 maxlength=255 value=\"" + IGvid_id_number_filename + "\"> <a href=\"javascript:launch_chooser('IGvid_id_number_filename_" + option + "','date');\"><?php echo _QXZ("audio chooser"); ?></a>";
            new_content = new_content + "<BR> &nbsp; <?php echo _QXZ("VID Confirm Filename"); ?>: <input type=text name=IGvid_confirm_filename_" + option + " id=IGvid_confirm_filename_" + option + " size=40 maxlength=255 value=\"" + IGvid_confirm_filename + "\"> <a href=\"javascript:launch_chooser('IGvid_confirm_filename_" + option + "','date');\"><?php echo _QXZ("audio chooser"); ?></a>";
            new_content = new_content + " &nbsp; <?php echo _QXZ("VID Digits"); ?>: <input type=text size=3 maxlength=3 name=IGvid_validate_digits_" + option + ' id=IGvid_validate_digits_' + option + ' value="' + IGvid_validate_digits + '">';
            new_content = new_content + "<BR> &nbsp; <?php echo _QXZ("VID Container"); ?>: <input type=text size=40 maxlength=40 name=IGvid_container_" + option + ' id=IGvid_container_' + option + ' value="' + IGvid_container_ + '">';
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


### Javascript for dynamic in-group option value entries
if ( ($ADD==2811) or ($ADD==3811) or ($ADD==3111) or ($ADD==2111) or ($ADD==2011) or ($ADD==4111) or ($ADD==5111) )
    {

    ?>
    function dynamic_call_action(option,route,value,chooser_height)
        {
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

        if (selected_route=='CALLMENU')
            {
            if (route == selected_route)
                {
                selected_value = '<option SELECTED value="' + value + '">' + value + "</option>\n";
                }
            else
                {value = '';}
            new_content = '<span name=' + option + '_value_link id=' + option + '_value_link><a href="<?php echo $ADMIN ?>?ADD=3511&menu_id=' + value + "\"><?php echo _QXZ("Call Menu"); ?>: </a></span><select size=1 name=" + option + '_value id=' + option + "_value onChange=\"dynamic_call_action_link('" + option + "','CALLMENU');\">" + call_menu_list + "\n" + selected_value + '</select>';
            }
        if (selected_route=='INGROUP')
            {
            if ( (route != selected_route) || (value.length < 10) )
                {value = 'SALESLINE,CID,LB,998,TESTCAMP,1,,,,,';}
            var value_split = value.split(",");
            var IGgroup_id =				value_split[0];
            var IGhandle_method =			value_split[1];
            var IGsearch_method =			value_split[2];
            var IGlist_id =					value_split[3];
            var IGcampaign_id =				value_split[4];
            var IGphone_code =				value_split[5];
            var IGvid_enter_filename =		value_split[6];
            var IGvid_id_number_filename =	value_split[7];
            var IGvid_confirm_filename =	value_split[8];
            var IGvid_validate_digits =		value_split[9];
            var IGvid_container =			value_split[10];

            if (route == selected_route)
                {
                selected_value = '<option SELECTED>' + IGgroup_id + '</option>';
                }

            new_content = new_content + '<span name=' + option + '_value_link id=' + option + '_value_link><a href="<?php echo $ADMIN ?>?ADD=3111&group_id=' + IGgroup_id + "\"><?php echo _QXZ("In-Group"); ?>:</a> </span> ';
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
        //	new_content = new_content + "<BR> &nbsp; <?php echo _QXZ("VID Enter Filename"); ?>: <input type=text name=IGvid_enter_filename_" + option + " id=IGvid_enter_filename_" + option + " size=40 maxlength=255 value=\"" + IGvid_enter_filename + "\"> <a href=\"javascript:launch_chooser('IGvid_enter_filename_" + option + "','date');\"><?php echo _QXZ("audio chooser"); ?></a>";
        //	new_content = new_content + "<BR> &nbsp; <?php echo _QXZ("VID ID Number Filename"); ?>: <input type=text name=IGvid_id_number_filename_" + option + " id=IGvid_id_number_filename_" + option + " size=40 maxlength=255 value=\"" + IGvid_id_number_filename + "\"> <a href=\"javascript:launch_chooser('IGvid_id_number_filename_" + option + "','date');\"><?php echo _QXZ("audio chooser"); ?></a>";
        //	new_content = new_content + "<BR> &nbsp; <?php echo _QXZ("VID Confirm Filename"); ?>: <input type=text name=IGvid_confirm_filename_" + option + " id=IGvid_confirm_filename_" + option + " size=40 maxlength=255 value=\"" + IGvid_confirm_filename + "\"> <a href=\"javascript:launch_chooser('IGvid_confirm_filename_" + option + "','date');\"><?php echo _QXZ("audio chooser"); ?></a>";
        //	new_content = new_content + ' &nbsp; <?php echo _QXZ("VID Digits"); ?>: <input type=text size=3 maxlength=3 name=IGvid_validate_digits_' + option + ' id=IGvid_validate_digits_' + option + ' value="' + IGvid_validate_digits + '">';
        //	new_content = new_content + '<BR> &nbsp; <?php echo _QXZ("VID Container"); ?>: <input type=text size=40 maxlength=40 name=IGvid_container_' + option + ' id=IGvid_container_' + option + ' value="' + IGvid_container_ + '">';

            }
        if (selected_route=='DID')
            {
            if (route == selected_route)
                {
                selected_value = '<option SELECTED value="' + value + '">' + value + "</option>\n";
                }
            else
                {value = '';}
            new_content = '<span name=' + option + '_value_link id=' + option + '_value_link><a href="<?php echo $ADMIN ?>?ADD=3311&did_pattern=' + value + "\"><?php echo _QXZ("DID"); ?>:</a> </span><select size=1 name=" + option + '_value id=' + option + "_value onChange=\"dynamic_call_action_link('" + option + "','DID');\">" + did_list + "\n" + selected_value + '</select>';
            }
        if (selected_route=='MESSAGE')
            {
            if (route == selected_route)
                {
                selected_value = value;
                }
            else
                {value = 'nbdy-avail-to-take-call|vm-goodbye';}
            new_content = "<?php echo _QXZ("Audio File"); ?>: <input type=text name=" + option + "_value id=" + option + "_value size=40 maxlength=255 value=\"" + value + "\"> <a href=\"javascript:launch_chooser('" + option + "_value','date');\"><?php echo _QXZ("audio chooser"); ?></a>";
            }
        if (selected_route=='EXTENSION')
            {
            if ( (route != selected_route) || (value.length < 3) )
                {value = '8304,default';}
            var value_split = value.split(",");
            var EXextension =	value_split[0];
            var EXcontext =		value_split[1];

            new_content = "<?php echo _QXZ("Extension"); ?>: <input type=text name=EXextension_" + option + " id=EXextension_" + option + " size=20 maxlength=255 value=\"" + EXextension + "\"> &nbsp; <?php echo _QXZ("Context"); ?>: <input type=text name=EXcontext_" + option + " id=EXcontext_" + option + " size=20 maxlength=255 value=\"" + EXcontext + "\"> ";
            }
        if ( (selected_route=='VOICEMAIL') || (selected_route=='VMAIL_NO_INST') )
            {
            if (route == selected_route)
                {
                selected_value = value;
                }
            else
                {value = '101';}
            new_content = "<?php echo _QXZ("Voicemail Box"); ?>: <input type=text name=" + option + "_value id=" + option + "_value size=12 maxlength=10 value=\"" + selected_value + "\"> <a href=\"javascript:launch_vm_chooser('" + option + "_value','date');\"><?php echo _QXZ("voicemail chooser"); ?></a>";
            }

        if (new_content.length < 1)
            {new_content = selected_route}

        span_to_update.innerHTML = new_content;
        }

    function dynamic_call_action_link(field,route)
        {
        var selected_value = '';
        var new_content = '';

        if ( (route=='CALLMENU') || (route=='DID') )
            {var select_list = document.getElementById(field + "_value");}
        if (route=='INGROUP')
            {
            var select_list = document.getElementById(field + "");
            field = field.replace(/IGgroup_id_/, "");
            }
        var selected_value = select_list.value;
        var span_to_update = document.getElementById(field + "_value_link");

        if (route=='CALLMENU')
            {
            new_content = '<a href="<?php echo $ADMIN ?>?ADD=3511&menu_id=' + selected_value + "\"><?php echo _QXZ("Call Menu"); ?>:</a>";
            }
        if (route=='INGROUP')
            {
            new_content = '<a href="<?php echo $ADMIN ?>?ADD=3111&group_id=' + selected_value + "\"><?php echo _QXZ("In-Group"); ?>:</a>";
            }
        if (route=='DID')
            {
            new_content = '<a href="<?php echo $ADMIN ?>?ADD=3311&did_pattern=' + selected_value + "\"><?php echo _QXZ("DID"); ?>:</a>";
            }

        if (new_content.length < 1)
            {new_content = selected_route}

        span_to_update.innerHTML = new_content;
        }

    <?php
    }
echo "</script>\n";

##### BEGIN - bar chart CSS style #####
?>

<style type="text/css">
/* Modern Admin Layout - Fixed Version */
* {
    box-sizing: border-box !important;
}

body {
    margin: 0 !important;
    padding: 0 !important;
    overflow-x: hidden !important;
}

.admin-layout {
    display: flex !important;
    min-height: 100vh !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    background: #f5f7fa !important;
}

.admin-sidebar {
    width: 220px !important;
    min-width: 220px !important;
    max-width: 220px !important;
    background: linear-gradient(180deg, #0b2447 0%, #19376d 100%) !important;
    position: fixed !important;
    height: 100vh !important;
    left: 0 !important;
    top: 0 !important;
    z-index: 1000 !important;
    overflow-y: auto !important;
    box-shadow: 2px 0 10px rgba(0,0,0,0.15) !important;
}

.admin-content {
    flex: 1 !important;
    margin-left: 220px !important;
    width: calc(100% - 220px) !important;
    background: #ffffff !important;
    min-height: 100vh !important;
    padding: 0 !important;
    position: relative !important;
}

.modern-header-container {
    width: 100% !important;
    margin: 0 !important;
    padding: 8px 20px !important;
    background: linear-gradient(135deg, #0b2447 0%, #19376d 100%) !important;
    box-shadow: 0 2px 10px rgba(0,0,0,0.15) !important;
    border-radius: 0 !important;
    position: relative !important;
}

.content-body {
    padding: 25px !important;
    background: #ffffff !important;
    width: 100% !important;
    min-height: calc(100vh - 60px) !important;
    box-sizing: border-box !important;
}

/* Header Styles */
.header-top {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    padding: 0 !important;
    height: 44px !important;
}

.logo-container {
    display: flex !important;
    align-items: center !important;
}

.logo-link {
    display: inline-block !important;
    transition: transform 0.3s ease, opacity 0.3s ease !important;
    opacity: 0.9 !important;
}

.logo-link:hover {
    transform: scale(1.05) !important;
    opacity: 1 !important;
}

.logo-image {
    display: block !important;
    border-radius: 4px !important;
    max-height: 35px !important;
}

.nav-menu {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    flex-wrap: wrap !important;
}

.nav-item {
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    padding: 6px 12px !important;
    color: #ffffff !important;
    text-decoration: none !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif !important;
    border-radius: 6px !important;
    transition: all 0.3s ease !important;
    background: rgba(255,255,255,0.08) !important;
    border: 1px solid rgba(255,255,255,0.1) !important;
}

.nav-item:hover {
    background: rgba(255,255,255,0.2) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2) !important;
}

.nav-item.nav-active {
    background: rgba(255,255,255,0.25) !important;
    border: 1px solid rgba(255,255,255,0.3) !important;
    font-weight: 600 !important;
}

/* Sidebar Styles */
.sidebar-header {
    padding: 16px !important;
    text-align: center !important;
    border-bottom: 1px solid rgba(255,255,255,0.1) !important;
}

.sidebar-logo {
    max-width: 100% !important;
    height: auto !important;
    border-radius: 6px !important;
    transition: transform 0.3s ease !important;
}

.sidebar-logo:hover {
    transform: scale(1.05) !important;
}

.sidebar-title {
    color: #ffffff !important;
    font-size: 15px !important;
    font-weight: 600 !important;
    margin-top: 8px !important;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
}

.nav-section {
    padding: 10px 0 !important;
}

.nav-section-title {
    color: rgba(255,255,255,0.6) !important;
    font-size: 11px !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 1px !important;
    padding: 10px 20px 5px !important;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
}

.nav-item-sidebar {
    display: flex !important;
    align-items: center !important;
    padding: 10px 20px !important;
    color: #ffffff !important;
    text-decoration: none !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    transition: all 0.3s ease !important;
    border-left: 3px solid transparent !important;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
}

.nav-item-sidebar:hover {
    background: rgba(255,255,255,0.1) !important;
    border-left-color: #3498db !important;
    transform: translateX(3px) !important;
}

.nav-item-sidebar.active {
    background: rgba(255,255,255,0.15) !important;
    border-left-color: #3498db !important;
    font-weight: 600 !important;
}

.nav-item-sidebar img {
    margin-right: 10px !important;
    width: 16px !important;
    height: 16px !important;
}

.nav-subitem {
    display: flex !important;
    align-items: center !important;
    padding: 8px 20px 8px 45px !important;
    color: rgba(255,255,255,0.8) !important;
    text-decoration: none !important;
    font-size: 13px !important;
    transition: all 0.3s ease !important;
    border-left: 3px solid transparent !important;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
}

.nav-subitem:hover {
    background: rgba(255,255,255,0.05) !important;
    color: #ffffff !important;
    border-left-color: #3498db !important;
    transform: translateX(3px) !important;
}

.nav-subitem.active {
    background: rgba(255,255,255,0.1) !important;
    color: #ffffff !important;
    border-left-color: #3498db !important;
    font-weight: 500 !important;
}

.nav-divider {
    height: 1px !important;
    background: rgba(255,255,255,0.1) !important;
    margin: 10px 20px !important;
}

/* Content Area */
.content-header {
    background: #ffffff !important;
    padding: 15px 25px !important;
    border-bottom: 1px solid #e1e8ed !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05) !important;
}

.content-title {
    font-size: 22px !important;
    font-weight: 600 !important;
    color: #2c3e50 !important;
    margin: 0 !important;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
}

.content-subtitle {
    font-size: 13px !important;
    color: #7f8c8d !important;
    margin-top: 5px !important;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
}

/* Responsive Design */
@media (max-width: 768px) {
    .admin-sidebar {
        transform: translateX(-100%) !important;
    }
    
    .admin-sidebar.open {
        transform: translateX(0) !important;
    }
    
    .admin-content {
        margin-left: 0 !important;
        width: 100% !important;
    }
    
    .nav-menu {
        flex-direction: column !important;
        gap: 5px !important;
    }
    
    .header-top {
        flex-direction: column !important;
        gap: 10px !important;
        height: auto !important;
    }
}
</style>


<?php

echo "<!-- INTERNATIONALIZATION-LINKS-PLACEHOLDER-VICIDIAL -->\n";

if ($header_font_size < 4) {$header_font_size='12';}
if ($subheader_font_size < 4) {$subheader_font_size='11';}
if ($subcamp_font_size < 4) {$subcamp_font_size='11';}

?>
<div class="admin-layout">
    <div class="admin-sidebar">
        <div class="sidebar-header">
            <a href="<?php echo $ADMIN; ?>">
                <img src="<?php echo $selected_logo; ?>" width="170" height="45" border="0" alt="System logo" class="sidebar-logo">
            </a>
            <div class="sidebar-title"><?php echo _QXZ("ADMINISTRATION"); ?></div>
        </div>
        
        <nav class="nav-section">
            <?php
            if ( ($reports_only_user < 1) and ($qc_only_user < 1) ) {
                ?>
                <div class="nav-section-title"><?php echo _QXZ("Main"); ?></div>
                <a href="<?php echo $ADMIN ?>?ADD=999999" class="nav-item-sidebar <?php echo ($hh=='reports') ? 'active' : ''; ?>">
                    <?php echo $reports_icon; ?> <?php echo _QXZ("Reports"); ?>
                </a>
                <a href="<?php echo $ADMIN ?>?ADD=0A" class="nav-item-sidebar <?php echo ($hh=='users') ? 'active' : ''; ?>">
                    <?php echo $users_icon; ?> <?php echo _QXZ("Users"); ?>
                </a>
                <a href="<?php echo $ADMIN ?>?ADD=10" class="nav-item-sidebar <?php echo ($hh=='campaigns') ? 'active' : ''; ?>">
                    <?php echo $campaigns_icon; ?> <?php echo _QXZ("Campaigns"); ?>
                </a>
                
                <?php if ($SSoutbound_autodial_active > 0) { ?>
                <a href="<?php echo $ADMIN ?>?ADD=100" class="nav-item-sidebar <?php echo ($hh=='lists') ? 'active' : ''; ?>">
                    <?php echo $lists_icon; ?> <?php echo _QXZ("Lists"); ?>
                </a>
                <?php } ?>
                
                <a href="<?php echo $ADMIN ?>?ADD=1000000" class="nav-item-sidebar <?php echo ($hh=='scripts') ? 'active' : ''; ?>">
                    <?php echo $scripts_icon; ?> <?php echo _QXZ("Scripts"); ?>
                </a>
                
                <?php if ($SSoutbound_autodial_active > 0) { ?>
                <a href="<?php echo $ADMIN ?>?ADD=10000000" class="nav-item-sidebar <?php echo ($hh=='filters') ? 'active' : ''; ?>">
                    <?php echo $filters_icon; ?> <?php echo _QXZ("Filters"); ?>
                </a>
                <?php } ?>
                
                <a href="<?php echo $ADMIN ?>?ADD=1001" class="nav-item-sidebar <?php echo ($hh=='ingroups') ? 'active' : ''; ?>">
                    <?php echo $inbound_icon; ?> <?php echo _QXZ("Inbound"); ?>
                </a>
                
                <a href="<?php echo $ADMIN ?>?ADD=100000" class="nav-item-sidebar <?php echo ($hh=='usergroups') ? 'active' : ''; ?>">
                    <?php echo $usergroups_icon; ?> <?php echo _QXZ("User Groups"); ?>
                </a>
                
                <a href="<?php echo $ADMIN ?>?ADD=10000" class="nav-item-sidebar <?php echo ($hh=='remoteagent') ? 'active' : ''; ?>">
                    <?php echo $remoteagents_icon; ?> <?php echo _QXZ("Remote Agents"); ?>
                </a>
                
                <a href="<?php echo $ADMIN ?>?ADD=999998" class="nav-item-sidebar <?php echo ($hh=='admin') ? 'active' : ''; ?>">
                    <?php echo $admin_icon; ?> <?php echo _QXZ("Admin"); ?>
                </a>
                
                <?php if (($SSqc_features_active=='1') && ($qc_auth=='1')) { ?>
                <div class="nav-divider"></div>
                <div class="nav-section-title"><?php echo _QXZ("Quality Control"); ?></div>
                <a href="<?php echo $ADMIN ?>?ADD=100000000000000" class="nav-item-sidebar <?php echo ($hh=='qc') ? 'active' : ''; ?>">
                    <?php echo $qc_icon; ?> <?php echo _QXZ("Quality Control"); ?>
                </a>
                <?php } ?>
                
                <?php
                // Submenu items for expanded sections
                if (strlen($users_hh) > 25) {
                    ?>
                    <div class="nav-subitem <?php echo ($sh=='list') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=0A" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Show Users"); ?>
                        </a>
                    </div>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <div class="nav-subitem <?php echo ($sh=='new') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Add A New User"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='copy') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1A" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Copy User"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <div class="nav-subitem <?php echo ($sh=='search') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=550" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Search For A User"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='stats') ? 'active' : ''; ?>">
                        <a href="./user_stats.php?user=<?php echo $user ?>" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("User Stats"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='status') ? 'active' : ''; ?>">
                        <a href="./user_status.php?user=<?php echo $user ?>" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("User Status"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='sheet') ? 'active' : ''; ?>">
                        <a href="./AST_agent_time_sheet.php?agent=<?php echo $user ?>" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Time Sheet"); ?>
                        </a>
                    </div>
                    <?php
                }
                
                if (strlen($campaigns_hh) > 25) {
                    ?>
                    <div class="nav-subitem <?php echo ($sh=='list') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=10" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Campaigns Main"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='status') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=32" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Statuses"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='hotkey') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=33" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("HotKeys"); ?>
                        </a>
                    </div>
                    <?php if ($SSoutbound_autodial_active > 0) { ?>
                    <div class="nav-subitem <?php echo ($sh=='recycle') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=35" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Lead Recycle"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='autoalt') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=36" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Auto-Alt Dial"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='listmix') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=39" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("List Mix"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <div class="nav-subitem <?php echo ($sh=='pause') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=37" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Pause Codes"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='preset') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=301" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Presets"); ?>
                        </a>
                    </div>
                    <?php if ($SScampaign_cid_areacodes_enabled > 0) { ?>
                    <div class="nav-subitem <?php echo ($sh=='accid') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=302" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("AC-CID"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <?php
                }
                
                if (strlen($lists_hh) > 25) {
                    ?>
                    <div class="nav-subitem <?php echo ($sh=='list') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=100" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Show Lists"); ?>
                        </a>
                    </div>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <div class="nav-subitem <?php echo ($sh=='new') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=111" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Add A New List"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <div class="nav-subitem <?php echo ($sh=='search') ? 'active' : ''; ?>">
                        <a href="admin_search_lead.php" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Search For A Lead"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='lead') ? 'active' : ''; ?>">
                        <a href="admin_modify_lead.php" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Add A New Lead"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='dnc') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=121" style="color: inherit; text-decoration: none;">
                            <?php echo $DNClink; ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='load') ? 'active' : ''; ?>">
                        <a href="./admin_listloader_fourth_gen.php" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Load New Leads"); ?>
                        </a>
                    </div>
                    <?php if ($SScustom_fields_enabled > 0) { ?>
                    <div class="nav-subitem <?php echo ($sh=='custom') ? 'active' : ''; ?>">
                        <a href="./admin_lists_custom.php" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("List Custom Fields"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='cpcust') ? 'active' : ''; ?>">
                        <a href="./admin_lists_custom.php?action=COPY_FIELDS_FORM" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Copy Custom Fields"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <?php if ($SSenable_drop_lists > 0) { ?>
                    <div class="nav-subitem <?php echo ($sh=='droplist') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=130" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Drop Lists"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <?php
                }
                
                if (strlen($scripts_hh) > 25) {
                    ?>
                    <div class="nav-subitem <?php echo ($sh=='list') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Show Scripts"); ?>
                        </a>
                    </div>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <div class="nav-subitem <?php echo ($sh=='new') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1111111" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Add A New Script"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <?php
                }
                
                if (strlen($filters_hh) > 25) {
                    ?>
                    <div class="nav-subitem <?php echo ($sh=='list') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=10000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Show Filters"); ?>
                        </a>
                    </div>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <div class="nav-subitem <?php echo ($sh=='new') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=11111111" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Add A New Filter"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <?php
                }
                
                if (strlen($ingroups_hh) > 25) {
                    ?>
                    <div class="nav-subitem <?php echo ($sh=='listIG') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Show In-Groups"); ?>
                        </a>
                    </div>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <div class="nav-subitem <?php echo ($sh=='newIG') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1111" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Add A New In-Group"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='copyIG') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1211" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Copy In-Group"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <?php if ($SSemail_enabled > 0) { ?>
                    <div class="nav-subitem <?php echo ($sh=='listEG') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1800" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Show Email Groups"); ?>
                        </a>
                    </div>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <div class="nav-subitem <?php echo ($sh=='newEG') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1811" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Add New Email Group"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='copyEG') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1911" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Copy Email Group"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <?php } ?>
                    <?php if ($SSchat_enabled > 0) { ?>
                    <div class="nav-subitem <?php echo ($sh=='listCG') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1900" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Show Chat Groups"); ?>
                        </a>
                    </div>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <div class="nav-subitem <?php echo ($sh=='newCG') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=18111" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Add New Chat Group"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='copyCG') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=19111" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Copy Chat Group"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <?php } ?>
                    <div class="nav-subitem <?php echo ($sh=='listDID') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1300" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Show DIDs"); ?>
                        </a>
                    </div>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <div class="nav-subitem <?php echo ($sh=='newDID') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1311" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Add A New DID"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='copyDID') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1411" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Copy DID"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <?php if ($SSdid_ra_extensions_enabled > 0) { ?>
                    <div class="nav-subitem <?php echo ($sh=='didRA') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1320" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("RA Extensions"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <div class="nav-subitem <?php echo ($sh=='listCM') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1500" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Show Call Menus"); ?>
                        </a>
                    </div>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <div class="nav-subitem <?php echo ($sh=='newCM') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1511" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Add A New Call Menu"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='copyCM') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1611" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Copy Call Menu"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <div class="nav-subitem <?php echo ($sh=='listFPG') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1700" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Filter Phone Groups"); ?>
                        </a>
                    </div>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <div class="nav-subitem <?php echo ($sh=='newFPG') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1711" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Add Filter Phone Group"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <div class="nav-subitem <?php echo ($sh=='addFPG') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=171" style="color: inherit; text-decoration: none;">
                            <?php echo $FPGlink; ?>
                        </a>
                    </div>
                    <?php
                }
                
                if (strlen($usergroups_hh) > 25) {
                    ?>
                    <div class="nav-subitem <?php echo ($sh=='list') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=100000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Show User Groups"); ?>
                        </a>
                    </div>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <div class="nav-subitem <?php echo ($sh=='new') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=111111" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Add A New User Group"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <div class="nav-subitem <?php echo ($sh=='hour') ? 'active' : ''; ?>">
                        <a href="group_hourly_stats.php" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Group Hourly Report"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='bulk') ? 'active' : ''; ?>">
                        <a href="user_group_bulk_change.php" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Bulk Group Change"); ?>
                        </a>
                    </div>
                    <?php
                }
                
                if (strlen($remoteagent_hh) > 25) {
                    ?>
                    <div class="nav-subitem <?php echo ($sh=='list') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=10000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Show Remote Agents"); ?>
                        </a>
                    </div>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <div class="nav-subitem <?php echo ($sh=='new') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=11111" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Add New Remote Agents"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <div class="nav-subitem <?php echo ($sh=='listEG') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=12000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Show Extension Groups"); ?>
                        </a>
                    </div>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <div class="nav-subitem <?php echo ($sh=='newEG') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=12111" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Add Extension Group"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <?php
                }
                
                if (strlen($admin_hh) > 25) {
                    ?>
                    <div class="nav-subitem <?php echo ($sh=='times') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=100000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Call Times"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='shifts') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=130000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Shifts"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='phones') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=10000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Phones"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='templates') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=130000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Templates"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='carriers') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=140000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Carriers"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='server') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=100000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Servers"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='conference') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=1000000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Conferences"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='settings') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=311111111111111" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("System Settings"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='label') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=180000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Screen Labels"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='colors') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=182000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Screen Colors"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='status') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=321111111111111" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("System Statuses"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='sg') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=193000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Status Groups"); ?>
                        </a>
                    </div>
                    <?php if ($SScampaign_cid_areacodes_enabled > 0) { ?>
                    <div class="nav-subitem <?php echo ($sh=='cg') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=196000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("CID Groups"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <div class="nav-subitem <?php echo ($sh=='vm') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=170000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Voicemail"); ?>
                        </a>
                    </div>
                    <?php if ($SSemail_enabled > 0) { ?>
                    <div class="nav-subitem <?php echo ($sh=='emails') ? 'active' : ''; ?>">
                        <a href="admin_email_accounts.php" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Email Accounts"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <?php if (($sounds_central_control_active > 0) or ($SSsounds_central_control_active > 0)) { ?>
                    <div class="nav-subitem <?php echo ($sh=='audio') ? 'active' : ''; ?>">
                        <a href="audio_store.php" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Audio Store"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='moh') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=160000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Music On Hold"); ?>
                        </a>
                    </div>
                    <?php if ($SSenable_languages > 0) { ?>
                    <div class="nav-subitem <?php echo ($sh=='languages') ? 'active' : ''; ?>">
                        <a href="admin_languages.php?ADD=163000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Languages"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <?php if ((preg_match("/soundboard/",$SSactive_modules) ) or ($SSagent_soundboards > 0)) { ?>
                    <div class="nav-subitem <?php echo ($sh=='soundboard') ? 'active' : ''; ?>">
                        <a href="admin_soundboard.php?ADD=162000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Audio Soundboards"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <div class="nav-subitem <?php echo ($sh=='vmmg') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=197000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("VM Message Groups"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <?php if ($SSenable_tts_integration > 0) { ?>
                    <div class="nav-subitem <?php echo ($sh=='tts') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=150000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Text To Speech"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <?php if ($SScallcard_enabled > 0) { ?>
                    <div class="nav-subitem <?php echo ($sh=='cc') ? 'active' : ''; ?>">
                        <a href="callcard_admin.php" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("CallCard Admin"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <?php if ($SScontacts_enabled > 0) { ?>
                    <div class="nav-subitem <?php echo ($sh=='cts') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=190000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Contacts"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <div class="nav-subitem <?php echo ($sh=='sc') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=192000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Settings Containers"); ?>
                        </a>
                    </div>
                    <?php if ($SSenable_auto_reports > 0) { ?>
                    <div class="nav-subitem <?php echo ($sh=='ar') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=194000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Automated Reports"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <?php if ($SSallow_ip_lists > 0) { ?>
                    <div class="nav-subitem <?php echo ($sh=='il') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=195000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("IP Lists"); ?>
                        </a>
                    </div>
                    <?php } ?>
                    <div class="nav-subitem <?php echo ($sh=='qg') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=198000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Queue Groups"); ?>
                        </a>
                    </div>
                    <?php
                }
                
                if (strlen($qc_hh) > 25) {
                    ?>
                    <div class="nav-subitem <?php echo ($sh=='campaign') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=100000000000000&qc_display_group_type=CAMPAIGN" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("QC Calls by Campaign"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='list') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=100000000000000&qc_display_group_type=LIST" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("QC Calls by List"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='ingroup') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=100000000000000&qc_display_group_type=INGROUP" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("QC Calls by Ingroup"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='enter') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=100000000000000" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Enter QC Queue"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='scorecard') ? 'active' : ''; ?>">
                        <a href="qc_scorecards.php" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Show QC Scorecards"); ?>
                        </a>
                    </div>
                    <div class="nav-subitem <?php echo ($sh=='modify') ? 'active' : ''; ?>">
                        <a href="<?php echo $ADMIN ?>?ADD=341111111111111" style="color: inherit; text-decoration: none;">
                            <?php echo _QXZ("Modify QC Codes"); ?>
                        </a>
                    </div>
                    <?php
                }
            } else {
                if ($reports_only_user > 0) {
                    ?>
                    <div class="nav-section-title"><?php echo _QXZ("Reports"); ?></div>
                    <a href="<?php echo $ADMIN ?>?ADD=999999" class="nav-item-sidebar active">
                        <?php echo $reports_icon; ?> <?php echo _QXZ("Reports"); ?>
                    </a>
                    <?php
                } else {
                    if (($SSqc_features_active=='1') && ($qc_auth=='1')) {
                        ?>
                        <div class="nav-section-title"><?php echo _QXZ("Quality Control"); ?></div>
                        <a href="<?php echo $ADMIN ?>?ADD=100000000000000" class="nav-item-sidebar active">
                            <?php echo $qc_icon; ?> <?php echo _QXZ("Quality Control"); ?>
                        </a>
                        <?php
                    }
                }
            }
            ?>
        </nav>
        
        <div style="padding: 20px; margin-top: auto;">
            <div style="color: rgba(255,255,255,0.6); font-size: 12px; text-align: center;">
                <?php echo date("Y-m-d H:i:s"); ?>
            </div>
        </div>
    </div>
    
    <div class="admin-content">
        <div class="modern-header-container">
            <div class="header-top">
                <div class="logo-container">
                    <a href="<?php echo htmlspecialchars($ADMIN); ?>" class="logo-link">
                        <img src="<?php echo htmlspecialchars($selected_small_logo); ?>" width="71" height="22" border="0" alt="System logo" class="logo-image">
                    </a>
                </div>
                
                <div class="nav-menu">
                    <a href="<?php echo htmlspecialchars($admin_home_url_LU); ?>" class="nav-item">
                        <span><?php echo _QXZ("HOME"); ?></span>
                    </a>
                    <a href="../agc/timeclock.php?referrer=admin" class="nav-item">
                        <span><?php echo _QXZ("Timeclock"); ?></span>
                    </a>
                    <a href="manager_chat_interface.php" class="nav-item">
                        <span><?php echo _QXZ("Chat"); ?></span>
                    </a>
                    <?php if ($SSenable_languages == '1') { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=999989" class="nav-item">
                        <span><?php echo _QXZ("Change language"); ?></span>
                    </a>
                    <?php } ?>
                    <a href="<?php echo $ADMIN ?>?force_logout=1" class="nav-item">
                        <span><?php echo _QXZ("Logout"); ?></span>
                    </a>
                    <div class="nav-item" style="background: rgba(255,255,255,0.2); cursor: default;">
                        <span><?php echo $PHP_AUTH_USER; ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="content-body">
            <span style="position:absolute;left:300px;top:30px;z-index:1;visibility:hidden;" id="audio_chooser_span"></span>
            
            <?php
            if ( (strlen($list_sh) > 25) and (strlen($campaigns_hh) > 25) ) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Campaign Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=10" class="modern-btn secondary">
                                <?php echo _QXZ("Show Campaigns"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=11" class="modern-btn">
                                <?php echo _QXZ("Add A New Campaign"); ?>
                            </a>
                            <a href="<?php echo $ADMIN ?>?ADD=12" class="modern-btn secondary">
                                <?php echo _QXZ("Copy Campaign"); ?>
                            </a>
                            <?php } ?>
                            <a href="./AST_timeonVDADallSUMMARY.php" class="modern-btn secondary">
                                <?php echo _QXZ("Real-Time Campaigns Summary"); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if (strlen($droplist_sh) > 25) {
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Drop List Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=130" class="modern-btn secondary">
                                <?php echo _QXZ("Show Drop Lists"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=131" class="modern-btn">
                                <?php echo _QXZ("Add A New Drop List"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if (strlen($times_sh) > 25) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Call Time Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=100000000" class="modern-btn secondary">
                                <?php echo _QXZ("Show Call Times"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=111111111" class="modern-btn">
                                <?php echo _QXZ("Add A New Call Time"); ?>
                            </a>
                            <?php } ?>
                            <a href="<?php echo $ADMIN ?>?ADD=1000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Show State Call Times"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=1111111111" class="modern-btn">
                                <?php echo _QXZ("Add A New State Call Time"); ?>
                            </a>
                            <?php } ?>
                            <a href="<?php echo $ADMIN ?>?ADD=1200000000" class="modern-btn secondary">
                                <?php echo _QXZ("Holidays"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=1211111111" class="modern-btn">
                                <?php echo _QXZ("Add Holiday"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if (strlen($shifts_sh) > 25) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Shift Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=130000000" class="modern-btn secondary">
                                <?php echo _QXZ("Show Shifts"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=131111111" class="modern-btn">
                                <?php echo _QXZ("Add A New Shift"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if (strlen($phones_sh) > 25) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Phone Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=10000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Show Phones"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=11111111111" class="modern-btn">
                                <?php echo _QXZ("Add A New Phone"); ?>
                            </a>
                            <a href="<?php echo $ADMIN ?>?ADD=12222222222" class="modern-btn secondary">
                                <?php echo _QXZ("Copy an Existing Phone"); ?>
                            </a>
                            <?php } ?>
                            <a href="<?php echo $ADMIN ?>?ADD=12000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Phone Alias List"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=12111111111" class="modern-btn">
                                <?php echo _QXZ("Add A New Phone Alias"); ?>
                            </a>
                            <?php } ?>
                            <a href="<?php echo $ADMIN ?>?ADD=13000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Group Alias List"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=13111111111" class="modern-btn">
                                <?php echo _QXZ("Add A New Group Alias"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if (strlen($conference_sh) > 25) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Conference Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=1000000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Show Conferences"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=1111111111111" class="modern-btn">
                                <?php echo _QXZ("Add A New Conference"); ?>
                            </a>
                            <?php } ?>
                            <a href="<?php echo $ADMIN ?>?ADD=10000000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Show VICIDIAL Conferences"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=11111111111111" class="modern-btn">
                                <?php echo _QXZ("Add A New VICIDIAL Conference"); ?>
                            </a>
                            <?php } ?>
                            <a href="<?php echo $ADMIN ?>?ADD=12000000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Show ConfBridges"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=12111111111111" class="modern-btn">
                                <?php echo _QXZ("Add ConfBridge"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if ( (strlen($server_sh) > 25) and (strlen($admin_hh) > 25) ) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Server Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=100000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Show Servers"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=111111111111" class="modern-btn">
                                <?php echo _QXZ("Add A New Server"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if ( (strlen($templates_sh) > 25) and (strlen($admin_hh) > 25) ) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Template Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=130000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Show Templates"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=131111111111" class="modern-btn">
                                <?php echo _QXZ("Add A New Template"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if ( (strlen($carriers_sh) > 25) and (strlen($admin_hh) > 25) ) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Carrier Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=140000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Show Carriers"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=141111111111" class="modern-btn">
                                <?php echo _QXZ("Add A New Carrier"); ?>
                            </a>
                            <a href="<?php echo $ADMIN ?>?ADD=140111111111" class="modern-btn secondary">
                                <?php echo _QXZ("Copy A Carrier"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if ( (strlen($emails_sh) > 25) and (strlen($admin_hh) > 25) ) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Email Account Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="admin_email_accounts.php" class="modern-btn secondary">
                                <?php echo _QXZ("Show Email Accounts"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="admin_email_accounts.php?eact=ADD" class="modern-btn">
                                <?php echo _QXZ("Add A New Account"); ?>
                            </a>
                            <a href="admin_email_accounts.php?eact=COPY" class="modern-btn secondary">
                                <?php echo _QXZ("Copy An Account"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if ( (strlen($tts_sh) > 25) and (strlen($admin_hh) > 25) ) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Text-to-Speech Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=150000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Show TTS Entries"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=151111111111" class="modern-btn">
                                <?php echo _QXZ("Add A New TTS Entry"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if ( (strlen($cc_sh) > 25) and (strlen($admin_hh) > 25) ) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("CallCard Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="callcard_admin.php" class="modern-btn secondary">
                                <?php echo _QXZ("CallCard Summary"); ?>
                            </a>
                            <a href="callcard_admin.php?action=CALLCARD_RUNS" class="modern-btn secondary">
                                <?php echo _QXZ("Runs"); ?>
                            </a>
                            <a href="callcard_admin.php?action=CALLCARD_BATCHES" class="modern-btn secondary">
                                <?php echo _QXZ("Batches"); ?>
                            </a>
                            <a href="callcard_admin.php?action=SEARCH" class="modern-btn secondary">
                                <?php echo _QXZ("CallCard Search"); ?>
                            </a>
                            <a href="callcard_report_export.php" class="modern-btn secondary">
                                <?php echo _QXZ("CallCard Log Export"); ?>
                            </a>
                            <a href="callcard_admin.php?action=GENERATE" class="modern-btn">
                                <?php echo _QXZ("CallCard Generate New Numbers"); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if ( (strlen($moh_sh) > 25) and (strlen($admin_hh) > 25) ) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Music On Hold Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=160000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Show MOH Entries"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=161111111111" class="modern-btn">
                                <?php echo _QXZ("Add A New MOH Entry"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if ( (strlen($languages_sh) > 25) and (strlen($admin_hh) > 25) and ($SSenable_languages > 0) ) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Language Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="admin_languages.php?ADD=163000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Show Languages"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="admin_languages.php?ADD=163111111111" class="modern-btn">
                                <?php echo _QXZ("Add A New Language"); ?>
                            </a>
                            <a href="admin_languages.php?ADD=163211111111" class="modern-btn secondary">
                                <?php echo _QXZ("Copy A Languages Entry"); ?>
                            </a>
                            <a href="admin_languages.php?ADD=163311111111" class="modern-btn secondary">
                                <?php echo _QXZ("Import Phrases"); ?>
                            </a>
                            <a href="admin_languages.php?ADD=163411111111" class="modern-btn secondary">
                                <?php echo _QXZ("Export Phrases"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if ( (strlen($soundboard_sh) > 25) and (strlen($admin_hh) > 25) and ((preg_match("/soundboard/",$SSactive_modules) ) or ($SSagent_soundboards > 0)) ) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Soundboard Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="admin_soundboard.php?ADD=162000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Show Soundboard Entries"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="admin_soundboard.php?ADD=162111111111" class="modern-btn">
                                <?php echo _QXZ("Add A New Soundboard Entry"); ?>
                            </a>
                            <a href="admin_soundboard.php?ADD=162211111111" class="modern-btn secondary">
                                <?php echo _QXZ("Copy A Soundboard Entry"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if ( (strlen($vm_sh) > 25) and (strlen($admin_hh) > 25) ) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Voicemail Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=170000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Show Voicemail Entries"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=171111111111" class="modern-btn">
                                <?php echo _QXZ("Add A New Voicemail Entry"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if (strlen($settings_sh) > 25) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("System Settings"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <a href="<?php echo $ADMIN ?>?ADD=311111111111111" class="modern-btn">
                            <?php echo _QXZ("System Settings"); ?>
                        </a>
                    </div>
                </div>
                <?php 
            }
            
            if (strlen($label_sh) > 25) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Screen Label Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=180000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Screen Labels"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=181111111111" class="modern-btn">
                                <?php echo _QXZ("Add A Screen Label"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if (strlen($colors_sh) > 25) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Screen Color Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=182000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Screen Colors"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=182111111111" class="modern-btn">
                                <?php echo _QXZ("Add A Screen Colors"); ?>
                            </a>
                            <?php } ?>
                            <a href="<?php echo $ADMIN ?>?ADD=311111111111111#screen_colors" class="modern-btn secondary">
                                <?php echo _QXZ("Change Active Screen Colors"); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if (strlen($cts_sh) > 25) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Contact Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=190000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Contacts"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=191111111111" class="modern-btn">
                                <?php echo _QXZ("Add A Contact"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if (strlen($sc_sh) > 25) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Settings Container Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=192000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Settings Containers"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=192111111111" class="modern-btn">
                                <?php echo _QXZ("Add A Settings Container"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if (strlen($ar_sh) > 25) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Automated Reports Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=194000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Automated Reports"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=194111111111" class="modern-btn">
                                <?php echo _QXZ("Add An Automated Report"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if (strlen($il_sh) > 25) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("IP List Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=195000000000" class="modern-btn secondary">
                                <?php echo _QXZ("IP Lists"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=195111111111" class="modern-btn">
                                <?php echo _QXZ("Add An IP List"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if (strlen($sg_sh) > 25) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Status Group Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=193000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Status Groups"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=193111111111" class="modern-btn">
                                <?php echo _QXZ("Add A Status Group"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if (strlen($cg_sh) > 25) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("CID Group Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=196000000000" class="modern-btn secondary">
                                <?php echo _QXZ("CID Groups"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=196111111111" class="modern-btn">
                                <?php echo _QXZ("Add A CID Group"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if (strlen($vmmg_sh) > 25) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("VM Message Group Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=197000000000" class="modern-btn secondary">
                                <?php echo _QXZ("VM Message Groups"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=197111111111" class="modern-btn">
                                <?php echo _QXZ("Add A VM Message Group"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if (strlen($qg_sh) > 25) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("Queue Group Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=198000000000" class="modern-btn secondary">
                                <?php echo _QXZ("Queue Groups"); ?>
                            </a>
                            <?php if ($add_copy_disabled < 1) { ?>
                            <a href="<?php echo $ADMIN ?>?ADD=198111111111" class="modern-btn">
                                <?php echo _QXZ("Add A Queue Group"); ?>
                            </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php 
            }
            
            if ( (strlen($status_sh) > 25) and (!preg_match('/campaign|user/i',$hh) ) ) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("System Status Management"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=321111111111111" class="modern-btn secondary">
                                <?php echo _QXZ("System Statuses"); ?>
                            </a>
                            <a href="<?php echo $ADMIN ?>?ADD=331111111111111" class="modern-btn secondary">
                                <?php echo _QXZ("Status Categories"); ?>
                            </a>
                            <a href="<?php echo $ADMIN ?>?ADD=341111111111111" class="modern-btn secondary">
                                <?php echo _QXZ("QC Status Codes"); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <?php 
            }

            if ( ($ADD=='3') or ($ADD=='3') ) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("User Information"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="./user_stats.php?user=<?php echo $user ?>" class="modern-btn secondary">
                                <?php echo _QXZ("User Stats"); ?>
                            </a>
                            <a href="./user_status.php?user=<?php echo $user ?>" class="modern-btn secondary">
                                <?php echo _QXZ("User Status"); ?>
                            </a>
                            <a href="./AST_agent_time_sheet.php?agent=<?php echo $user ?>" class="modern-btn secondary">
                                <?php echo _QXZ("Time Sheet"); ?>
                            </a>
                            <a href="./AST_agent_days_detail.php?user=<?php echo $user ?>" class="modern-btn secondary">
                                <?php echo _QXZ("Days Status"); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <?php 
            }

            if ( ($ADD=='999988') or ($ADD=='999987') or ($ADD=='999986') or ($ADD=='999985') ) { 
                ?>
                <div class="modern-card">
                    <div class="modern-card-header">
                        <h3 class="modern-card-title"><?php echo _QXZ("System Reference Data"); ?></h3>
                    </div>
                    <div class="modern-card-body">
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="<?php echo $ADMIN ?>?ADD=999988" class="modern-btn secondary">
                                <?php echo _QXZ("Available Timezones"); ?>
                            </a>
                            <a href="<?php echo $ADMIN ?>?ADD=999987" class="modern-btn secondary">
                                <?php echo _QXZ("Phone Codes"); ?>
                            </a>
                            <a href="<?php echo $ADMIN ?>?ADD=999986" class="modern-btn secondary">
                                <?php echo _QXZ("Postal Codes"); ?>
                            </a>
                            <a href="<?php echo $ADMIN ?>?ADD=999985" class="modern-btn secondary">
                                <?php echo _QXZ("Postal Codes Cities"); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <?php 
            }
            else {
                if (strlen($reports_hh) > 25) { 
                    ?>
                    <div class="modern-card">
                        <div class="modern-card-header">
                            <h3 class="modern-card-title"><?php echo _QXZ("Reports Dashboard"); ?></h3>
                        </div>
                        <div class="modern-card-body">
                            <div style="color: #5a6c7d; font-style: italic;">
                                <?php echo _QXZ("Select a report category from the sidebar to view available reports"); ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                } 
            }
            ?>
        </div>
    </div>
</div>

<?php
######################### FULL HTML HEADER END #######################################
}
?>