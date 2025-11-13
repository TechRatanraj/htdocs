<?php
# 
# screen_colors.php    version 2.15 (Modernized)
#
# include file to generate screen colors - normally in admin_header.php, 
# but most reports include admin_header.php after the colors need to be set
#
# Copyright (C) 2020  Joseph Johnson <freewermadmin@gmail.com>, Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2
#
#
# CHANGES:
# 170828-2345 - First Build
# 200814-2247 - Added button color
# 241113-1600 - Security improvements: prepared statements, input validation
#

##### BEGIN Define colors and logo #####
$SSmenu_background      = '015B91';
$SSframe_background     = 'D9E6FE';
$SSstd_row1_background  = '9BB9FB';
$SSstd_row2_background  = 'B9CBFD';
$SSstd_row3_background  = '8EBCFD';
$SSstd_row4_background  = 'B6D3FC';
$SSstd_row5_background  = 'FFFFFF';
$SSalt_row1_background  = 'BDFFBD';
$SSalt_row2_background  = '99FF99';
$SSalt_row3_background  = 'CCFFCC';
$SSbutton_color         = 'EFEFEF';
$SSweb_logo             = '';

##### Query 1: Get admin screen colors setting #####
$screen_color_stmt = "SELECT admin_screen_colors FROM system_settings LIMIT 1";
$screen_color_rslt = mysql_to_mysqli($screen_color_stmt, $link);

if ($screen_color_rslt && mysqli_num_rows($screen_color_rslt) > 0) {
    $screen_color_row = mysqli_fetch_row($screen_color_rslt);
    $agent_screen_colors = $screen_color_row[0];
} else {
    $agent_screen_colors = 'default';
}

##### Query 2: Load custom colors if not default #####
if ($agent_screen_colors != 'default' && !empty($agent_screen_colors)) {
    
    // Use prepared statement to prevent SQL injection
    $asc_stmt = $link->prepare("
        SELECT 
            menu_background,
            frame_background,
            std_row1_background,
            std_row2_background,
            std_row3_background,
            std_row4_background,
            std_row5_background,
            alt_row1_background,
            alt_row2_background,
            alt_row3_background,
            web_logo,
            button_color 
        FROM vicidial_screen_colors 
        WHERE colors_id = ? 
        LIMIT 1
    ");
    
    if ($asc_stmt) {
        // Bind the colors_id parameter
        $asc_stmt->bind_param('s', $agent_screen_colors);
        
        // Execute the query
        $asc_stmt->execute();
        
        // Get the result
        $asc_rslt = $asc_stmt->get_result();
        $qm_conf_ct = $asc_rslt->num_rows;
        
        if ($qm_conf_ct > 0) {
            $asc_row = $asc_rslt->fetch_row();
            
            // Validate and assign color values
            $SSmenu_background      = preg_match('/^[a-fA-F0-9]{6}$/', $asc_row[0]) ? $asc_row[0] : $SSmenu_background;
            $SSframe_background     = preg_match('/^[a-fA-F0-9]{6}$/', $asc_row[1]) ? $asc_row[1] : $SSframe_background;
            $SSstd_row1_background  = preg_match('/^[a-fA-F0-9]{6}$/', $asc_row[2]) ? $asc_row[2] : $SSstd_row1_background;
            $SSstd_row2_background  = preg_match('/^[a-fA-F0-9]{6}$/', $asc_row[3]) ? $asc_row[3] : $SSstd_row2_background;
            $SSstd_row3_background  = preg_match('/^[a-fA-F0-9]{6}$/', $asc_row[4]) ? $asc_row[4] : $SSstd_row3_background;
            $SSstd_row4_background  = preg_match('/^[a-fA-F0-9]{6}$/', $asc_row[5]) ? $asc_row[5] : $SSstd_row4_background;
            $SSstd_row5_background  = preg_match('/^[a-fA-F0-9]{6}$/', $asc_row[6]) ? $asc_row[6] : $SSstd_row5_background;
            $SSalt_row1_background  = preg_match('/^[a-fA-F0-9]{6}$/', $asc_row[7]) ? $asc_row[7] : $SSalt_row1_background;
            $SSalt_row2_background  = preg_match('/^[a-fA-F0-9]{6}$/', $asc_row[8]) ? $asc_row[8] : $SSalt_row2_background;
            $SSalt_row3_background  = preg_match('/^[a-fA-F0-9]{6}$/', $asc_row[9]) ? $asc_row[9] : $SSalt_row3_background;
            $SSweb_logo             = !empty($asc_row[10]) ? filter_var($asc_row[10], FILTER_SANITIZE_URL) : $SSweb_logo;
            $SSbutton_color         = preg_match('/^[a-fA-F0-9]{6}$/', $asc_row[11]) ? $asc_row[11] : $SSbutton_color;
        }
        
        $asc_stmt->close();
    } else {
        // Log error if prepare fails (optional)
        error_log("Screen colors prepare statement failed: " . $link->error);
    }
}
?>
