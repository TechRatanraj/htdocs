<?php
# vicidial_stylesheet.php
# 

require("dbconnect_mysqli.php");
require("functions.php");
header("Content-type: text/css");
require("screen_colors.php");

/* ============================================
   CSS CUSTOM PROPERTIES (Design System)
   ============================================ */
?>
<style>
:root {
    /* System Font Stack */
    --font-family-base: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, Helvetica, sans-serif;
    --font-family-mono: 'Courier New', Courier, monospace;
    
    /* Typography Scale */
    --font-size-xs: 12px;
    --font-size-sm: 14px;
    --font-size-base: 16px;
    --font-size-lg: 18px;
    --font-size-xl: 20px;
    --line-height-normal: 1.5;
    --line-height-relaxed: 1.6;
    
    /* Spacing */
    --spacing-xs: 4px;
    --spacing-sm: 8px;
    --spacing-md: 12px;
    --spacing-lg: 16px;
    --spacing-xl: 24px;
    
    /* Border Radius */
    --radius-sm: 4px;
    --radius-md: 6px;
    --radius-lg: 8px;
    
    /* Shadows */
    --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.15);
    --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.2);
    --shadow-focus: 0 0 0 3px rgba(59, 130, 246, 0.5);
    
    /* Transitions */
    --transition-fast: 150ms ease-in-out;
    --transition-base: 250ms ease-in-out;
}

/* ============================================
   TEXT / DISPLAY STYLES
   ============================================ */
.vertical-text {
    writing-mode: vertical-rl;
    text-orientation: mixed;
    white-space: nowrap;
    display: inline-block;
    min-width: 24px;
    min-height: 24px;
}

div.scrolling {
    height: auto;
    max-height: 300px;
    width: 100%;
    max-width: 400px;
    overflow-y: auto;
    overflow-x: hidden;
    border: 1px solid #666;
    border-radius: var(--radius-sm);
    background-color: #FFF;
    padding: var(--spacing-sm);
    scrollbar-width: thin;
    scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
}

div.scrolling::-webkit-scrollbar {
    width: 8px;
}

div.scrolling::-webkit-scrollbar-track {
    background: transparent;
}

div.scrolling::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.2);
    border-radius: var(--radius-sm);
}

div.scrolling::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.3);
}

redalert {
    font-size: var(--font-size-lg);
    font-weight: bold;
    font-family: var(--font-family-base);
    color: white;
    background: #FF0000;
    padding: var(--spacing-sm) var(--spacing-md);
    border-radius: var(--radius-sm);
    display: inline-block;
}

.sm_shadow {
    box-shadow: var(--shadow-md);
}

.round_corners {
    border-radius: var(--radius-md);
}

.embossed {
    text-shadow: -1px -1px 1px #fff, 1px 1px 1px #000;
    opacity: 1.0;
}

.embossed_bold {
    font-weight: bold;
    text-shadow: -1px -1px 1px #fff, 1px 1px 1px #000;
    opacity: 1.0;
}

.help_bold {
    font-weight: bold;
    font-size: var(--font-size-base);
    opacity: 1.0;
}

.bold {
    font-weight: bold;
}

.green {
    color: black;
    background-color: #99FF99;
    padding: var(--spacing-xs);
    border-radius: var(--radius-sm);
}

.red {
    color: black;
    background-color: #FF9999;
    padding: var(--spacing-xs);
    border-radius: var(--radius-sm);
}

.orange {
    color: black;
    background-color: #FFCC99;
    padding: var(--spacing-xs);
    border-radius: var(--radius-sm);
}

.white_text {
    color: #FFF;
}

/* ============================================
   BASIC FONTS
   ============================================ */
.small_standard {
    font-family: var(--font-family-base);
    font-size: var(--font-size-xs);
    line-height: var(--line-height-normal);
}

.small_standard_bold {
    font-family: var(--font-family-base);
    font-size: var(--font-size-xs);
    font-weight: bold;
    line-height: var(--line-height-normal);
}

.standard {
    font-family: var(--font-family-base);
    font-size: var(--font-size-sm);
    line-height: var(--line-height-normal);
}

.standard_bold {
    font-family: var(--font-family-base);
    font-size: var(--font-size-sm);
    font-weight: bold;
    line-height: var(--line-height-normal);
}

/* ============================================
   SYSTEM COLOR ROWS
   ============================================ */
.std_row1 {background-color: <?php echo $SSstd_row1_background; ?>}
.std_row2 {background-color: <?php echo $SSstd_row2_background; ?>}
.std_row3 {background-color: <?php echo $SSstd_row3_background; ?>}
.std_row4 {background-color: <?php echo $SSstd_row4_background; ?>}
.std_row5 {background-color: <?php echo $SSstd_row5_background; ?>}
.alt_row1 {background-color: <?php echo $SSalt_row1_background; ?>}
.alt_row2 {background-color: <?php echo $SSalt_row2_background; ?>}
.alt_row3 {background-color: <?php echo $SSalt_row3_background; ?>}
.std_btn {background-color: <?php echo $SSbutton_background; ?>;}

/* ============================================
   SPECIAL EFFECTS
   ============================================ */
.blink {
    animation: blinker 1.2s linear infinite;
    color: #900;
    font-family: var(--font-family-base);
    font-size: var(--font-size-sm);
    font-weight: bold;
    margin-bottom: var(--spacing-sm);
}

@keyframes blinker {
    50% {
        opacity: 0;
    }
}

.border2px {
    border: solid 2px #<?php echo $SSmenu_background; ?>;
}

/* ============================================
   RESPONSIVE ANDROID/MOBILE TYPOGRAPHY
   ============================================ */
.android_standard {
    font-family: var(--font-family-base);
    font-size: clamp(14px, calc(8px + 1vw), 20px);
    line-height: var(--line-height-normal);
}

.android_medium {
    font-family: var(--font-family-base);
    font-size: clamp(15px, calc(9px + 1vw), 22px);
    line-height: var(--line-height-normal);
}

.android_large {
    font-family: var(--font-family-base);
    font-size: clamp(16px, calc(10px + 1vw), 24px);
    font-weight: bold;
    line-height: var(--line-height-normal);
}

.android_auto {
    font-family: var(--font-family-base);
    font-weight: bold;
    font-size: clamp(14px, calc(14px + (26 - 14) * ((100vw - 300px) / (1600 - 300))), 26px);
    line-height: clamp(1.3em, calc(1.3em + (1.5 - 1.3) * ((100vw - 300px) / (1600 - 300))), 1.5em);
}

.android_small {
    font-family: var(--font-family-base);
    font-size: clamp(12px, calc(7px + 0.5vw), 16px);
    line-height: var(--line-height-normal);
}

.android_auto_small {
    font-family: var(--font-family-base);
    font-size: clamp(12px, calc(8px + (18 - 8) * ((100vw - 300px) / (1600 - 300))), 18px);
    line-height: clamp(1.3em, calc(1.3em + (1.5 - 1.3) * ((100vw - 300px) / (1600 - 300))), 1.5em);
}

.android_whiteboard_small {
    font-family: var(--font-family-base);
    font-size: clamp(12px, calc(8px + 0.5vw), 16px);
    line-height: clamp(1.3em, calc(1.3em + (1.5 - 1.3) * ((100vw - 300px) / (1600 - 300))), 1.5em);
}

.android_campaign_header {
    font-family: var(--font-family-mono);
    font-weight: bold;
    font-size: clamp(12px, calc(4px + (18 - 4) * ((100vw - 300px) / (1600 - 300))), 18px);
}

.android_auto_percent {
    font-family: var(--font-family-base);
    font-weight: bold;
    width: clamp(150px, 24vw, 300px);
    font-size: clamp(14px, calc(14px + (26 - 14) * ((100vw - 300px) / (1600 - 300))), 26px);
    line-height: clamp(1.3em, calc(1.3em + (1.5 - 1.3) * ((100vw - 300px) / (1600 - 300))), 1.5em);
}

/* ============================================
   MOBILE WHITEBOARD UTILITIES
   ============================================ */
.mobile_whiteboard_td_lg {
    width: clamp(200px, 40vw, 400px);
}

.mobile_whiteboard_td_sm {
    width: clamp(150px, 30vw, 300px);
}

.mobile_whiteboard_select {
    width: clamp(135px, 27vw, 270px);
}

.mobile_whiteboard_button {
    width: clamp(75px, 15vw, 150px);
}

.mobile_whiteboard_display {
    width: clamp(300px, 80vw, 800px);
    height: clamp(300px, 70vh, 800px);
}

.autosize_10 {
    width: clamp(80px, 10vw, 160px);
}

.autosize_12 {
    width: clamp(90px, 12vw, 180px);
}

/* ============================================
   DROPDOWN MENU (Modernized)
   ============================================ */
ul.dropdown_android {
    position: relative;
    list-style: none;
    margin: 0;
    padding: 0;
}

ul.dropdown_android li {
    font-weight: bold;
    float: left;
}

ul.dropdown_android a {
    display: block;
    padding: var(--spacing-sm) var(--spacing-md);
    color: #222;
    text-decoration: none;
    border-right: 1px solid #333;
    transition: all var(--transition-fast);
}

ul.dropdown_android a:hover {
    color: #000;
    background-color: rgba(0, 0, 0, 0.05);
}

ul.dropdown_android a:active {
    color: #ffa500;
}

ul.dropdown_android li:last-child a {
    border-right: none;
}

ul.dropdown_android li:hover {
    position: relative;
}

/* Level Two Menu */
ul.dropdown_android ul {
    width: clamp(100px, calc(25px + (150 - 25) * ((100vw - 300px) / (1600 - 300))), 150px);
    visibility: hidden;
    opacity: 0;
    position: absolute;
    top: 100%;
    left: -40px;
    background-color: #fff;
    box-shadow: var(--shadow-md);
    border-radius: var(--radius-sm);
    transition: opacity var(--transition-fast), visibility var(--transition-fast);
    z-index: 1000;
}

ul.dropdown_android ul li {
    font-weight: normal;
    float: none;
    margin-top: 0;
}

ul.dropdown_android ul li a {
    border-right: none;
    width: 100%;
    display: block;
}

/* Level Three Menu */
ul.dropdown_android ul ul {
    left: 100%;
    top: 0;
}

ul.dropdown_android li:hover > ul {
    visibility: visible;
    opacity: 1;
}

/* ============================================
   RECORDS LIST STYLING
   ============================================ */
.records_list_x {
    background-color: #<?php echo $SSstd_row2_background; ?>;
    transition: background-color var(--transition-fast);
}

.records_list_x:hover {
    background-color: #E6E6E6;
}

.records_list_y {
    background-color: #<?php echo $SSstd_row1_background; ?>;
    transition: background-color var(--transition-fast);
}

.records_list_y:hover {
    background-color: #E6E6E6;
}

.insetshadow {
    color: #202020;
    letter-spacing: 0.1em;
    text-shadow: -1px -1px 1px #111, 2px 2px 1px #363636;
}

.elegantshadow {
    color: #131313;
    background-color: #e7e5e4;
    letter-spacing: 0.15em;
    text-shadow: 
        1px -1px 0 #767676, 
        -1px 2px 1px #737272, 
        -2px 4px 1px #767474, 
        -3px 6px 1px #787777, 
        -4px 8px 1px #7b7a7a, 
        -5px 10px 1px #7f7d7d, 
        -6px 12px 1px #828181;
}

/* ============================================
   FORM ELEMENTS (Modernized)
   ============================================ */
.cust_form {
    font-family: var(--font-family-base);
    font-size: var(--font-size-sm);
    line-height: var(--line-height-normal);
    overflow: hidden;
}

.cust_form input,
.cust_form select,
.cust_form textarea {
    font-family: inherit;
    font-size: var(--font-size-sm);
}

.form_field {
    font-family: var(--font-family-base);
    font-size: var(--font-size-sm);
    margin-bottom: var(--spacing-xs);
    background-color: #<?php echo $SSalt_row3_background; ?>;
    padding: var(--spacing-sm);
    border: 1px solid #<?php echo $SSmenu_background; ?>;
    border-radius: var(--radius-sm);
    transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
}

.form_field:focus {
    outline: none;
    border-color: #<?php echo $SSmenu_background; ?>;
    box-shadow: var(--shadow-focus);
}

.form_field_android {
    font-family: var(--font-family-base);
    font-size: clamp(14px, calc(8px + 1vw), 20px);
}

.form_field_android_small {
    font-family: var(--font-family-base);
    font-size: clamp(12px, calc(6px + 0.5vw), 16px);
}

.form_field_whiteboard_android {
    font-family: var(--font-family-base);
    font-size: clamp(14px, calc(8px + 1vw), 20px);
    margin-bottom: var(--spacing-xs);
    background-color: #<?php echo $SSalt_row3_background; ?>;
    border: 1px solid #<?php echo $SSmenu_background; ?>;
    border-radius: var(--radius-sm);
    padding: var(--spacing-sm);
}

.required_field {
    font-family: var(--font-family-base);
    font-size: var(--font-size-sm);
    margin-bottom: var(--spacing-xs);
    background-color: #FFCCCC;
    padding: var(--spacing-sm);
    border: 2px solid #990000;
    border-radius: var(--radius-sm);
}

.required_field_whiteboard_android {
    font-family: var(--font-family-base);
    font-size: clamp(14px, calc(8px + 1vw), 20px);
    margin-bottom: var(--spacing-xs);
    background-color: #FFCCCC;
    border: 1px solid #990000;
    border-radius: var(--radius-sm);
    padding: var(--spacing-sm);
}

/* ============================================
   CHAT BOX TEXTAREAS
   ============================================ */
textarea.chat_box {
    font-family: var(--font-family-base);
    font-size: var(--font-size-sm);
    line-height: var(--line-height-normal);
    width: 100%;
    min-height: 80px;
    margin-bottom: var(--spacing-sm);
    padding: var(--spacing-sm);
    border: 1px solid #0056b3;
    border-radius: var(--radius-sm);
    background-color: #FFF;
    resize: vertical;
    transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
}

textarea.chat_box:focus {
    outline: none;
    border-color: #0056b3;
    box-shadow: 0 0 0 3px rgba(0, 86, 179, 0.25);
}

textarea.chat_box_ended {
    font-family: var(--font-family-base);
    font-size: var(--font-size-sm);
    line-height: var(--line-height-normal);
    width: 100%;
    min-height: 80px;
    margin-bottom: var(--spacing-sm);
    padding: var(--spacing-sm);
    border: 1px solid #999;
    border-radius: var(--radius-sm);
    background-color: #E5E5E5;
    color: #666;
    resize: vertical;
    cursor: not-allowed;
}

/* ============================================
   BUTTON SYSTEM (Modernized)
   ============================================ */

/* Red Buttons */
input.red_btn {
    font-family: var(--font-family-base);
    font-size: var(--font-size-xs);
    min-height: 36px;
    color: #FFFFFF;
    font-weight: bold;
    background: linear-gradient(180deg, #CC0000 0%, #990000 100%);
    border: 1px solid #660000;
    border-radius: var(--radius-sm);
    padding: var(--spacing-sm) var(--spacing-md);
    cursor: pointer;
    transition: all var(--transition-fast);
    box-shadow: var(--shadow-sm);
}

input.red_btn:hover {
    background: linear-gradient(180deg, #990000 0%, #660000 100%);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

input.red_btn:active {
    transform: translateY(0);
}

input.red_btn:focus-visible {
    outline: 2px solid #CC0000;
    outline-offset: 2px;
}

input.red_btn_mobile {
    font-family: var(--font-family-base);
    font-size: clamp(14px, calc(12px + 0.5vw), 18px);
    width: clamp(200px, 36vw, 600px);
    min-height: 44px;
    color: #FFFFFF;
    font-weight: bold;
    background: linear-gradient(180deg, #CC0000 0%, #990000 100%);
    border: 1px solid #660000;
    border-radius: var(--radius-sm);
    padding: var(--spacing-sm) var(--spacing-md);
    cursor: pointer;
    transition: all var(--transition-fast);
}

input.red_btn_mobile:hover {
    background: linear-gradient(180deg, #990000 0%, #660000 100%);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

input.red_btn_mobile_lg {
    font-family: var(--font-family-base);
    font-size: clamp(16px, calc(14px + 1vw), 22px);
    width: clamp(300px, 48vw, 800px);
    min-height: 48px;
    color: #FFFFFF;
    font-weight: bold;
    background: linear-gradient(180deg, #CC0000 0%, #990000 100%);
    border: 1px solid #660000;
    border-radius: var(--radius-sm);
    padding: var(--spacing-md) var(--spacing-lg);
    cursor: pointer;
    transition: all var(--transition-fast);
}

input.red_btn_mobile_sm {
    font-family: var(--font-family-base);
    font-size: clamp(12px, calc(12px + 0.2vw), 16px);
    width: clamp(150px, 30vw, 400px);
    min-height: 40px;
    color: #FFFFFF;
    font-weight: bold;
    background: linear-gradient(180deg, #CC0000 0%, #990000 100%);
    border: 1px solid #660000;
    border-radius: var(--radius-sm);
    padding: var(--spacing-sm) var(--spacing-md);
    cursor: pointer;
    transition: all var(--transition-fast);
}

input.red_btn_anywidth {
    font-family: var(--font-family-base);
    font-size: clamp(14px, calc(12px + 0.5vw), 18px);
    min-height: 44px;
    color: #FFFFFF;
    font-weight: bold;
    background: linear-gradient(180deg, #CC0000 0%, #990000 100%);
    border: 1px solid #660000;
    border-radius: var(--radius-sm);
    padding: var(--spacing-sm) var(--spacing-lg);
    cursor: pointer;
    transition: all var(--transition-fast);
}

/* Green Buttons */
input.green_btn {
    font-family: var(--font-family-base);
    font-size: var(--font-size-xs);
    min-height: 36px;
    color: #FFFFFF;
    font-weight: bold;
    background: linear-gradient(180deg, #00CC00 0%, #009900 100%);
    border: 1px solid #006600;
    border-radius: var(--radius-sm);
    padding: var(--spacing-sm) var(--spacing-md);
    cursor: pointer;
    transition: all var(--transition-fast);
    box-shadow: var(--shadow-sm);
}

input.green_btn:hover {
    background: linear-gradient(180deg, #009900 0%, #006600 100%);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

input.green_btn:active {
    transform: translateY(0);
}

input.green_btn:focus-visible {
    outline: 2px solid #00CC00;
    outline-offset: 2px;
}

input.green_btn_mobile {
    font-family: var(--font-family-base);
    font-size: clamp(14px, calc(12px + 0.5vw), 18px);
    width: clamp(100px, 11vw, 250px);
    min-height: 44px;
    color: #FFFFFF;
    font-weight: bold;
    background: linear-gradient(180deg, #00CC00 0%, #009900 100%);
    border: 1px solid #006600;
    border-radius: var(--radius-sm);
    padding: var(--spacing-sm) var(--spacing-md);
    cursor: pointer;
    transition: all var(--transition-fast);
}

input.green_btn_mobile_lg {
    font-family: var(--font-family-base);
    font-size: clamp(16px, calc(14px + 1vw), 22px);
    width: clamp(100px, 15vw, 200px);
    min-height: 48px;
    color: #FFFFFF;
    font-weight: bold;
    background: linear-gradient(180deg, #00CC00 0%, #009900 100%);
    border: 1px solid #006600;
    border-radius: var(--radius-sm);
    padding: var(--spacing-md) var(--spacing-lg);
    cursor: pointer;
    transition: all var(--transition-fast);
}

input.green_btn_anywidth {
    font-family: var(--font-family-base);
    font-size: clamp(14px, calc(12px + 0.5vw), 18px);
    min-height: 44px;
    color: #FFFFFF;
    font-weight: bold;
    background: linear-gradient(180deg, #00CC00 0%, #009900 100%);
    border: 1px solid #006600;
    border-radius: var(--radius-sm);
    padding: var(--spacing-sm) var(--spacing-lg);
    cursor: pointer;
    transition: all var(--transition-fast);
}

input.green_btn_anywidth_lg {
    font-family: var(--font-family-base);
    font-size: clamp(16px, calc(14px + 1vw), 22px);
    min-height: 48px;
    color: #FFFFFF;
    font-weight: bold;
    background: linear-gradient(180deg, #00CC00 0%, #009900 100%);
    border: 1px solid #006600;
    border-radius: var(--radius-sm);
    padding: var(--spacing-md) var(--spacing-lg);
    cursor: pointer;
    transition: all var(--transition-fast);
}

/* Blue Buttons */
input.blue_btn {
    font-family: var(--font-family-base);
    font-size: var(--font-size-xs);
    min-height: 36px;
    color: #FFFFFF;
    font-weight: bold;
    background: linear-gradient(180deg, #0000CC 0%, #000099 100%);
    border: 1px solid #000066;
    border-radius: var(--radius-sm);
    padding: var(--spacing-sm) var(--spacing-md);
    cursor: pointer;
    transition: all var(--transition-fast);
    box-shadow: var(--shadow-sm);
}

input.blue_btn:hover {
    background: linear-gradient(180deg, #000099 0%, #000066 100%);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

input.blue_btn:active {
    transform: translateY(0);
}

input.blue_btn:focus-visible {
    outline: 2px solid #0000CC;
    outline-offset: 2px;
}

input.blue_btn_mobile {
    font-family: var(--font-family-base);
    font-size: clamp(14px, calc(12px + 0.5vw), 18px);
    min-height: 44px;
    color: #FFFFFF;
    font-weight: bold;
    background: linear-gradient(180deg, #0000CC 0%, #000099 100%);
    border: 1px solid #000066;
    border-radius: var(--radius-sm);
    padding: var(--spacing-sm) var(--spacing-lg);
    cursor: pointer;
    transition: all var(--transition-fast);
}

/* Tiny Buttons (Modernized) */
input.tiny_red_btn {
    color: #FFFFFF;
    font-size: var(--font-size-xs);
    font-weight: bold;
    min-height: 32px;
    background: linear-gradient(180deg, #CC3333 0%, #993333 100%);
    border: 1px solid #660000;
    border-radius: var(--radius-sm);
    padding: 6px 12px;
    cursor: pointer;
    transition: all var(--transition-fast);
    box-shadow: var(--shadow-sm);
}

input.tiny_red_btn:hover {
    background: linear-gradient(180deg, #993333 0%, #660000 100%);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

input.tiny_red_btn:focus-visible {
    outline: 2px solid #CC3333;
    outline-offset: 2px;
}

input.tiny_blue_btn {
    color: #FFFFFF;
    font-size: var(--font-size-xs);
    font-weight: bold;
    min-height: 32px;
    background: linear-gradient(180deg, #5555FF 0%, #3333FF 100%);
    border: 1px solid #000066;
    border-radius: var(--radius-sm);
    padding: 6px 12px;
    cursor: pointer;
    transition: all var(--transition-fast);
    box-shadow: var(--shadow-sm);
}

input.tiny_blue_btn:hover {
    background: linear-gradient(180deg, #3333FF 0%, #0000CC 100%);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

input.tiny_blue_btn:focus-visible {
    outline: 2px solid #3333FF;
    outline-offset: 2px;
}

input.tiny_yellow_btn {
    color: #000000;
    font-size: var(--font-size-xs);
    font-weight: bold;
    min-height: 32px;
    background: linear-gradient(180deg, #FFFF33 0%, #FFFF00 100%);
    border: 1px solid #999900;
    border-radius: var(--radius-sm);
    padding: 6px 12px;
    cursor: pointer;
    transition: all var(--transition-fast);
    box-shadow: var(--shadow-sm);
}

input.tiny_yellow_btn:hover {
    background: linear-gradient(180deg, #FFFF00 0%, #CCCC00 100%);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

input.tiny_yellow_btn:focus-visible {
    outline: 2px solid #FFFF00;
    outline-offset: 2px;
}

input.tiny_green_btn {
    color: #FFFFFF;
    font-size: var(--font-size-xs);
    font-weight: bold;
    min-height: 32px;
    background: linear-gradient(180deg, #00CC00 0%, #009900 100%);
    border: 1px solid #006600;
    border-radius: var(--radius-sm);
    padding: 6px 12px;
    cursor: pointer;
    transition: all var(--transition-fast);
    box-shadow: var(--shadow-sm);
}

input.tiny_green_btn:hover {
    background: linear-gradient(180deg, #009900 0%, #006600 100%);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

input.tiny_green_btn:focus-visible {
    outline: 2px solid #00CC00;
    outline-offset: 2px;
}

/* ============================================
   TABLE ELEMENTS
   ============================================ */
TABLE.question_td {
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-lg);
    padding: var(--spacing-md);
    font-family: var(--font-family-base);
    color: black;
    font-size: var(--font-size-base);
    font-weight: bold;
    background: #<?php echo $SSframe_background; ?>;
    color: #000000;
    vertical-align: top;
    border: solid 2px #<?php echo $SSmenu_background; ?>;
}

TABLE.help_td {
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-lg);
    padding: var(--spacing-md);
    font-family: var(--font-family-base);
    color: black;
    font-size: var(--font-size-sm);
    background: #<?php echo $SSframe_background; ?>;
    color: #000000;
    vertical-align: top;
    border: solid 4px #<?php echo $SSmenu_background; ?>;
}

TABLE.panel_td {
    padding: var(--spacing-md);
    font-family: var(--font-family-base);
    color: black;
    font-size: var(--font-size-base);
    font-weight: bold;
    background: #<?php echo $SSframe_background; ?>;
    color: #000000;
    vertical-align: top;
}

TD.panel_td {
    padding: var(--spacing-md);
    font-family: var(--font-family-base);
    color: black;
    font-size: var(--font-size-base);
    font-weight: bold;
    background: #<?php echo $SSframe_background; ?>;
    color: #000000;
    vertical-align: top;
}

TD.search_td {
    border-radius: var(--radius-md);
    padding: var(--spacing-md);
    font-family: var(--font-family-base);
    color: black;
    font-size: var(--font-size-xs);
    font-weight: bold;
    background: #FB9BB9;
    color: #000000;
    vertical-align: top;
    border: solid 2px #600;
}

TD.bbottom {
    border-bottom: 1pt solid black;
}

TD.btop {
    border-top: 1pt solid black;
}

TD.bsides {
    border-left: 1pt solid black;
    border-right: 1pt solid black;
}

TD.text_overflow {
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}

/* ============================================
   DIV ELEMENTS (Shadowboxes & Android Buttons)
   ============================================ */
div.shadowbox {
    box-shadow: var(--shadow-md);
    border: 2px solid;
    border-radius: var(--radius-md);
    font-weight: bold;
    font-size: var(--font-size-base);
    font-family: var(--font-family-mono);
    padding-left: var(--spacing-xl);
}

div.shadowbox_1st {
    background-color: #66FF66;
    box-shadow: var(--shadow-md);
    border: 2px solid;
    border-radius: var(--radius-md);
    padding-left: var(--spacing-sm);
    font-weight: bold;
    font-size: var(--font-size-base);
    font-family: var(--font-family-mono);
}

div.shadowbox_2nd {
    background-color: #FFFF66;
    box-shadow: var(--shadow-md);
    border: 2px solid;
    border-radius: var(--radius-md);
    font-weight: bold;
    font-size: var(--font-size-base);
    font-family: var(--font-family-mono);
    padding-left: var(--spacing-sm);
}

div.shadowbox_3rd {
    background-color: #66FFFF;
    box-shadow: var(--shadow-md);
    border: 2px solid;
    border-radius: var(--radius-md);
    font-weight: bold;
    font-size: var(--font-size-base);
    font-family: var(--font-family-mono);
    padding-left: var(--spacing-sm);
}

div.shadowbox_4th {
    background-color: #FF6666;
    box-shadow: var(--shadow-md);
    border: 2px solid;
    border-radius: var(--radius-md);
    font-weight: bold;
    font-size: var(--font-size-base);
    font-family: var(--font-family-mono);
    padding-left: var(--spacing-sm);
}

/* Android Button System */
div.android_switchbutton {
    background-color: #FFCCCC;
    box-shadow: 3px 3px 1px #000000;
    border: 2px solid;
    border-radius: var(--radius-sm);
    width: clamp(80px, calc(25px + (200 - 25) * ((100vw - 300px) / (1600 - 300))), 200px);
    font-weight: bold;
    font-size: clamp(12px, calc(6px + (24 - 6) * ((100vw - 300px) / (1600 - 300))), 24px);
    font-family: var(--font-family-mono);
    padding-left: var(--spacing-xs);
    padding-right: var(--spacing-xs);
    cursor: pointer;
    transition: all var(--transition-fast);
}

div.android_switchbutton:hover {
    transform: translateY(-1px);
    box-shadow: 4px 4px 2px #000000;
}

div.android_switchbutton_blue {
    background-color: #99CCFF;
    box-shadow: 3px 3px 1px #000000;
    border: 2px solid;
    border-radius: var(--radius-sm);
    width: clamp(80px, calc(25px + (200 - 25) * ((100vw - 300px) / (1600 - 300))), 200px);
    font-weight: bold;
    font-size: clamp(12px, calc(6px + (24 - 6) * ((100vw - 300px) / (1600 - 300))), 24px);
    font-family: var(--font-family-mono);
    padding-left: var(--spacing-xs);
    padding-right: var(--spacing-xs);
    cursor: pointer;
    transition: all var(--transition-fast);
}

div.android_offbutton {
    background-color: #CCCCCC;
    box-shadow: 3px 3px 1px #000000;
    border: 2px solid;
    border-radius: var(--radius-sm);
    width: clamp(80px, calc(25px + (200 - 25) * ((100vw - 300px) / (1600 - 300))), 200px);
    font-weight: bold;
    color: white;
    font-size: clamp(12px, calc(6px + (24 - 6) * ((100vw - 300px) / (1600 - 300))), 24px);
    font-family: var(--font-family-mono);
    padding-left: var(--spacing-xs);
    padding-right: var(--spacing-xs);
    cursor: pointer;
    transition: all var(--transition-fast);
}

div.android_onbutton {
    background-color: #FFFFFF;
    box-shadow: 3px 3px 1px #000000;
    border: 2px solid;
    border-radius: var(--radius-sm);
    width: clamp(80px, calc(25px + (200 - 25) * ((100vw - 300px) / (1600 - 300))), 200px);
    font-weight: bold;
    font-size: clamp(12px, calc(6px + (24 - 6) * ((100vw - 300px) / (1600 - 300))), 24px);
    font-family: var(--font-family-mono);
    padding-left: var(--spacing-xs);
    padding-right: var(--spacing-xs);
    cursor: pointer;
    transition: all var(--transition-fast);
}

div.android_offbutton_noshadow {
    background-color: #CCCCCC;
    border: 2px solid;
    border-radius: var(--radius-sm);
    width: clamp(80px, calc(25px + (200 - 25) * ((100vw - 300px) / (1600 - 300))), 200px);
    font-weight: bold;
    color: white;
    font-size: clamp(12px, calc(6px + (24 - 6) * ((100vw - 300px) / (1600 - 300))), 24px);
    font-family: var(--font-family-mono);
    padding-left: var(--spacing-xs);
    padding-right: var(--spacing-xs);
    cursor: pointer;
    transition: all var(--transition-fast);
}

div.android_onbutton_noshadow {
    background-color: #FFFFFF;
    border: 2px solid;
    border-radius: var(--radius-sm);
    width: clamp(80px, calc(25px + (200 - 25) * ((100vw - 300px) / (1600 - 300))), 200px);
    font-weight: bold;
    font-size: clamp(12px, calc(6px + (24 - 6) * ((100vw - 300px) / (1600 - 300))), 24px);
    font-family: var(--font-family-mono);
    padding-left: var(--spacing-xs);
    padding-right: var(--spacing-xs);
    cursor: pointer;
    transition: all var(--transition-fast);
}

div.dropdown_android_button {
    background-color: #CCCCCC;
    box-shadow: 3px 3px 1px #000000;
    border: 2px solid;
    border-radius: var(--radius-sm);
    width: clamp(80px, calc(25px + (200 - 25) * ((100vw - 300px) / (1600 - 300))), 200px);
    font-weight: bold;
    font-size: clamp(12px, calc(6px + (24 - 6) * ((100vw - 300px) / (1600 - 300))), 24px);
    font-family: var(--font-family-mono);
    padding-left: var(--spacing-xs);
    padding-right: var(--spacing-xs);
    cursor: pointer;
    transition: all var(--transition-fast);
}

div.dropdown_android_button:hover {
    background-color: #F3D673;
    color: black;
}

div.android_offbutton_large {
    background-color: #999999;
    box-shadow: 3px 3px 1px #000000;
    border: 2px solid;
    border-radius: var(--radius-sm);
    width: clamp(150px, calc(50px + (320 - 50) * ((100vw - 300px) / (1600 - 300))), 320px);
    font-weight: bold;
    color: white;
    font-size: clamp(12px, calc(6px + (24 - 6) * ((100vw - 300px) / (1600 - 300))), 24px);
    font-family: var(--font-family-mono);
    padding-left: var(--spacing-xs);
    padding-right: var(--spacing-xs);
    cursor: pointer;
    transition: all var(--transition-fast);
}

div.android_onbutton_large {
    background-color: #FFFFFF;
    box-shadow: 3px 3px 1px #000000;
    border: 2px solid;
    border-radius: var(--radius-sm);
    width: clamp(180px, calc(60px + (320 - 60) * ((100vw - 300px) / (1600 - 300))), 320px);
    font-weight: bold;
    font-size: clamp(12px, calc(6px + (24 - 6) * ((100vw - 300px) / (1600 - 300))), 24px);
    font-family: var(--font-family-mono);
    padding-left: var(--spacing-xs);
    padding-right: var(--spacing-xs);
    cursor: pointer;
    transition: all var(--transition-fast);
}

div.android_table {
    width: 100vw;
    border: 5px solid;
    border-radius: var(--radius-md);
    padding-left: var(--spacing-xs);
    border-color: #<?php echo $SSframe_background; ?>;
    background-color: #<?php echo $SSframe_background; ?>;
}

div.android_settings_table {
    width: 94vw;
    border: 5px solid;
    border-radius: var(--radius-md);
    padding-left: var(--spacing-xs);
    border-color: #<?php echo $SSframe_background; ?>;
    background-color: #<?php echo $SSframe_background; ?>;
}

div.help_info {
    position: absolute;
    top: 0;
    left: 0;
    display: none;
}

/* ============================================
   SPAN ELEMENTS
   ============================================ */
span.android_offbutton {
    background-color: #999999;
    box-shadow: 3px 3px 1px #000000;
    border: 2px solid;
    border-radius: var(--radius-md);
    font-weight: bold;
    color: white;
    font-size: clamp(12px, calc(8px + (14 - 8) * ((100vw - 300px) / (1600 - 300))), 14px);
    font-family: var(--font-family-mono);
    padding-left: var(--spacing-xs);
    padding-right: var(--spacing-xs);
}

span.android_onbutton {
    background-color: #FFFFFF;
    box-shadow: 3px 3px 1px #000000;
    border: 2px solid;
    border-radius: var(--radius-md);
    font-weight: bold;
    font-size: clamp(12px, calc(8px + (14 - 8) * ((100vw - 300px) / (1600 - 300))), 14px);
    font-family: var(--font-family-mono);
    padding-left: var(--spacing-xs);
    padding-right: var(--spacing-xs);
}

/* ============================================
   HCI BUTTONS (Human-Computer Interface)
   ============================================ */
.button_active {
    background-color: #4CAF50;
    border: 1px solid green;
    border-radius: var(--radius-sm);
    color: white;
    padding: var(--spacing-sm) 22px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: var(--font-size-base);
    min-height: 44px;
    cursor: pointer;
    transition: all var(--transition-fast);
}

.button_active:hover {
    background-color: #45a049;
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.button_inactive {
    background-color: #4CAF50;
    border: 1px solid green;
    border-radius: var(--radius-sm);
    color: white;
    padding: var(--spacing-sm) 22px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: var(--font-size-base);
    min-height: 44px;
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
}

.button_hci_ready {
    background-color: #CCCCCC;
    border: 0px;
    border-radius: var(--radius-sm);
    color: #333333;
    padding: var(--spacing-sm) 22px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: var(--font-size-sm);
    min-height: 44px;
    cursor: pointer;
    transition: all var(--transition-fast);
}

.button_hci_ready:hover {
    background-color: #BBBBBB;
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
}

.button_hci_active {
    background-color: #FFCC99;
    border: 0px;
    border-radius: var(--radius-sm);
    color: #333333;
    padding: var(--spacing-sm) 22px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: var(--font-size-sm);
    min-height: 44px;
    cursor: pointer;
    transition: all var(--transition-fast);
}

.button_hci_active:hover {
    background-color: #FFBB77;
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
}

.button_hci_verify_confirm {
    background-color: #<?php echo $SSmenu_background; ?>;
    border: 2px solid #999999;
    border-radius: 0px;
    color: #FFFFFF;
    padding: var(--spacing-sm) 22px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: var(--font-size-xs);
    min-height: 40px;
    cursor: pointer;
    transition: all var(--transition-fast);
}

.button_hci_verify_confirm:hover {
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.button_hci_verify_cancel {
    background-color: #E6E6E6;
    border: 2px solid #999999;
    border-radius: 0px;
    color: #666666;
    padding: var(--spacing-sm) 22px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: var(--font-size-xs);
    min-height: 40px;
    cursor: pointer;
    transition: all var(--transition-fast);
}

.button_hci_verify_cancel:hover {
    background-color: #DDDDDD;
    transform: translateY(-1px);
    box-shadow: var(--shadow-sm);
}

/* ============================================
   RESPONSIVE ADJUSTMENTS
   ============================================ */
@media (max-width: 768px) {
    :root {
        --font-size-xs: 12px;
        --font-size-sm: 14px;
        --font-size-base: 16px;
    }
    
    /* Ensure touch-friendly targets on mobile */
    input[type="button"],
    input[type="submit"],
    button {
        min-height: 44px;
    }
}

/* ============================================
   PRINT STYLES
   ============================================ */
@media print {
    .button_active,
    .button_inactive,
    .button_hci_ready,
    .button_hci_active,
    input.red_btn,
    input.green_btn,
    input.blue_btn {
        box-shadow: none;
        border: 1px solid black;
    }
}
</style>