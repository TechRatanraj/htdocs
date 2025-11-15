######################
# ADD=34 modify campaign info in the system - Basic View
######################

if ( ($ADD==34) and ( (!preg_match("/$campaign_id/i", $LOGallowed_campaigns)) and (!preg_match("/ALL-CAMPAIGNS/i",$LOGallowed_campaigns)) ) ) 
	{$ADD=30;}	# send to not allowed screen if not in vicidial_user_groups allowed_campaigns list

if ($ADD==34)
	{
	if ($LOGmodify_campaigns==1)
		{
		if ( ($SSadmin_modify_refresh > 1) and ($modify_refresh_set < 1) )
			{
			$modify_url = "$PHP_SELF?ADD=31&campaign_id=$campaign_id&SUB=$SUB";
			$modify_footer_refresh=1;
			}
		if ($stage=='show_dialable')
			{
			$stmt="UPDATE vicidial_campaigns set display_dialable_count='Y' where campaign_id='$campaign_id';";
			$rslt=mysql_to_mysqli($stmt, $link);
			}
		if ($stage=='hide_dialable')
			{
			$stmt="UPDATE vicidial_campaigns set display_dialable_count='N' where campaign_id='$campaign_id';";
			$rslt=mysql_to_mysqli($stmt, $link);
			}
		if ($stage=='show_leadscount')
			{
			$stmt="UPDATE vicidial_campaigns set display_leads_count='Y' where campaign_id='$campaign_id';";
			$rslt=mysql_to_mysqli($stmt, $link);
			}
		if ($stage=='hide_leadscount')
			{
			$stmt="UPDATE vicidial_campaigns set display_leads_count='N' where campaign_id='$campaign_id';";
			$rslt=mysql_to_mysqli($stmt, $link);
			}

		$stmt="SELECT campaign_id,campaign_name,active,dial_status_a,dial_status_b,dial_status_c,dial_status_d,dial_status_e,lead_order,park_ext,park_file_name,web_form_address,allow_closers,hopper_level,auto_dial_level,next_agent_call,local_call_time,voicemail_ext,dial_timeout,dial_prefix,campaign_cid,campaign_vdad_exten,campaign_rec_exten,campaign_recording,campaign_rec_filename,campaign_script,get_call_launch,am_message_exten,amd_send_to_vmx,xferconf_a_dtmf,xferconf_a_number,xferconf_b_dtmf,xferconf_b_number,alt_number_dialing,scheduled_callbacks,lead_filter_id,drop_call_seconds,drop_action,safe_harbor_exten,display_dialable_count,wrapup_seconds,wrapup_message,closer_campaigns,use_internal_dnc,allcalls_delay,omit_phone_code,dial_method,available_only_ratio_tally,adaptive_dropped_percentage,adaptive_maximum_level,adaptive_latest_server_time,adaptive_intensity,adaptive_dl_diff_target,concurrent_transfers,auto_alt_dial,auto_alt_dial_statuses,agent_pause_codes_active,campaign_description,campaign_changedate,campaign_stats_refresh,campaign_logindate,dial_statuses,disable_alter_custdata,no_hopper_leads_logins,list_order_mix,campaign_allow_inbound,manual_dial_list_id,default_xfer_group,xfer_groups,queue_priority,drop_inbound_group,qc_enabled,qc_statuses,qc_lists,qc_shift_id,qc_get_record_launch,qc_show_recording,qc_web_form_address,qc_script,survey_first_audio_file,survey_dtmf_digits,survey_ni_digit,survey_opt_in_audio_file,survey_ni_audio_file,survey_method,survey_no_response_action,survey_ni_status,survey_response_digit_map,survey_xfer_exten,survey_camp_record_dir,disable_alter_custphone,display_queue_count,manual_dial_filter,agent_clipboard_copy,agent_extended_alt_dial,use_campaign_dnc,three_way_call_cid,three_way_dial_prefix,web_form_target,vtiger_search_category,vtiger_create_call_record,vtiger_create_lead_record,vtiger_screen_login,cpd_amd_action,agent_allow_group_alias,default_group_alias,vtiger_search_dead,vtiger_status_call,survey_third_digit,survey_third_audio_file,survey_third_status,survey_third_exten,survey_fourth_digit,survey_fourth_audio_file,survey_fourth_status,survey_fourth_exten,drop_lockout_time,quick_transfer_button,prepopulate_transfer_preset,drop_rate_group,view_calls_in_queue,view_calls_in_queue_launch,grab_calls_in_queue,call_requeue_button,pause_after_each_call,no_hopper_dialing,agent_dial_owner_only,agent_display_dialable_leads,web_form_address_two,waitforsilence_options,display_leads_count,user_group,allow_emails,call_count_limit,allow_chats,web_form_address_three,manual_vm_status_updates from vicidial_campaigns where campaign_id='$campaign_id' $LOGallowed_campaignsSQL;";
		$rslt=mysql_to_mysqli($stmt, $link);
		$row=mysqli_fetch_row($rslt);
		$dial_status_a = $row[3];
		$dial_status_b = $row[4];
		$dial_status_c = $row[5];
		$dial_status_d = $row[6];
		$dial_status_e = $row[7];
		$lead_order = $row[8];
		$hopper_level = $row[13];
		$auto_dial_level = $row[14];
		$next_agent_call = $row[15];
		$local_call_time = $row[16];
		$voicemail_ext = $row[17];
		$dial_timeout = $row[18];
		$dial_prefix = $row[19];
		$campaign_cid = $row[20];
		$campaign_vdad_exten = $row[21];
		$script_id = $row[25];
		$get_call_launch = $row[26];
		$lead_filter_id = $row[35];
			if ($lead_filter_id=='') {$lead_filter_id='NONE';}
		$display_dialable_count = $row[39];
		$closer_campaigns = $row[42];
		$dial_method = $row[46];
		$adaptive_intensity = $row[51];
		$auto_alt_dial = $row[54];
		$campaign_description = $row[57];
		$campaign_changedate = $row[58];
		$campaign_stats_refresh = $row[59];
		$campaign_logindate = $row[60];
		$dial_statuses = $row[61];
		$list_order_mix = $row[64];
		$default_xfer_group = $row[67];
		$campaign_allow_inbound = $row[65];
		$default_xfer_group = $row[67];
		$drop_lockout_time = $row[116];
		$display_leads_count = $row[130];
		$user_group = $row[131];
		$allow_emails = $row[132];
		$call_count_limit = $row[133];
		$allow_chats = $row[134];
		$web_form_address_three=$row[135];

	if (preg_match('/DISABLED/', $list_order_mix))
		{$DEFlistDISABLE = '';	$DEFstatusDISABLED=0;}
	else
		{$DEFlistDISABLE = 'disabled';	$DEFstatusDISABLED=1;}

	if ($auto_alt_dial == 'MULTI_LEAD')
		{$ALTmultiDISABLE=1;	$ALTmultiLINK="<a href=\"./admin_campaign_multi_alt.php?campaign_id=$campaign_id\">"._QXZ("Multi-Alt-Settings")."</a>";}
	else
		{$ALTmultiDISABLE=0;	$ALTmultiLINK='';}

	##### get status groups for the lists and in-groups within this campaign
	$stmt="SELECT status_group_id from vicidial_lists where campaign_id='$campaign_id' $LOGallowed_campaignsSQL;";
	$rslt=mysql_to_mysqli($stmt, $link);
	$lists_to_print = mysqli_num_rows($rslt);
	$camp_status_groups='';
	if ($DB) {echo "$lists_to_print|$stmt|\n";}
	$o=0;
	while ($lists_to_print > $o) 
		{
		$rowx=mysqli_fetch_row($rslt);
		if (strlen($rowx[0]) > 0) {$camp_status_groups .= "'$rowx[0]',";}
		$o++;
		}
	$closer_campaigns = preg_replace("/ -$/","",$closer_campaigns);
	$closer_campaigns = preg_replace("/ /","','",$closer_campaigns);
	$stmt="SELECT status_group_id from vicidial_inbound_groups where status_group_id NOT IN('','NONE') and group_id IN('$closer_campaigns') $LOGadmin_viewable_groupsSQL ;";
	$rslt=mysql_to_mysqli($stmt, $link);
	$lists_to_print = mysqli_num_rows($rslt);
	if ($DB) {echo "$lists_to_print|$stmt|\n";}
	$o=0;
	while ($lists_to_print > $o) 
		{
		$rowx=mysqli_fetch_row($rslt);
		if (strlen($rowx[0]) > 0) {$camp_status_groups .= "'$rowx[0]',";}
		$o++;
		}
#	$camp_status_groups = preg_replace('/.$/i','',$camp_status_groups);

	$stmt="SELECT status,status_name,selectable,human_answered,category,sale,dnc,customer_contact,not_interested,unworkable,scheduled_callback,completed,min_sec,max_sec,answering_machine from vicidial_statuses order by status;";
	$rslt=mysql_to_mysqli($stmt, $link);
	$statuses_to_print = mysqli_num_rows($rslt);
	$statuses_list='';
	$dial_statuses_list='';
	$o=0;
	while ($statuses_to_print > $o) 
		{
		$rowx=mysqli_fetch_row($rslt);
		$statuses_list .= "<option value=\"$rowx[0]\">$rowx[0] - $rowx[1]</option>\n";
		if ($rowx[0] != 'CBHOLD') {$dial_statuses_list .= "<option value=\"$rowx[0]\">$rowx[0] - $rowx[1]</option>\n";}
		$statname_list["$rowx[0]"] = "$rowx[1]";
		$LRstatuses_list .= "<option value=\"$rowx[0]-----$rowx[1]\">$rowx[0] - $rowx[1]</option>\n";
		if (preg_match('/Y/i', $rowx[2]))
			{$HKstatuses_list .= "<option value=\"$rowx[0]-----$rowx[1]\">$rowx[0] - $rowx[1]</option>\n";}
		$o++;
		}

	$stmt="SELECT status,status_name,selectable,campaign_id,human_answered,category,sale,dnc,customer_contact,not_interested,unworkable,scheduled_callback,completed,min_sec,max_sec,answering_machine from vicidial_campaign_statuses where campaign_id IN($camp_status_groups'$campaign_id') $LOGallowed_campaignsSQL order by status;";
	$rslt=mysql_to_mysqli($stmt, $link);
	$Cstatuses_to_print = mysqli_num_rows($rslt);

	$o=0;
	while ($Cstatuses_to_print > $o) 
		{
		$rowx=mysqli_fetch_row($rslt);
		$statuses_list .= "<option value=\"$rowx[0]\">$rowx[0] - $rowx[1]</option>\n";
		if ($rowx[0] != 'CBHOLD') {$dial_statuses_list .= "<option value=\"$rowx[0]\">$rowx[0] - $rowx[1]</option>\n";}
		$statname_list["$rowx[0]"] = "$rowx[1]";
		$LRstatuses_list .= "<option value=\"$rowx[0]-----$rowx[1]\">$rowx[0] - $rowx[1]</option>\n";
		if (preg_match('/Y/i', $rowx[2]))
			{$HKstatuses_list .= "<option value=\"$rowx[0]-----$rowx[1]\">$rowx[0] - $rowx[1]</option>\n";}
		$o++;
		}

	$dial_statuses = preg_replace("/ -$/","",$dial_statuses);
	$Dstatuses = explode(" ", $dial_statuses);
	$Ds_to_print = (count($Dstatuses) -1);

	$stmt="SELECT count(*) from vicidial_campaigns_list_mix where campaign_id='$campaign_id' and status='ACTIVE' $LOGallowed_campaignsSQL;";
	$rslt=mysql_to_mysqli($stmt, $link);
	$rowx=mysqli_fetch_row($rslt);
	if ($rowx[0] < 1)
		{
		$mixes_list="<option SELECTED value=\"DISABLED\">"._QXZ("DISABLED")."</option>\n";
		$mixname_list["DISABLED"] = "DISABLED";
		}
	else
		{
		##### get list_mix listings for dynamic pulldown
		$stmt="SELECT vcl_id,vcl_name from vicidial_campaigns_list_mix where campaign_id='$campaign_id' and status='ACTIVE' $LOGallowed_campaignsSQL limit 1;";
		$rslt=mysql_to_mysqli($stmt, $link);
		$mixes_to_print = mysqli_num_rows($rslt);
		$mixes_list="<option value=\"DISABLED\">"._QXZ("DISABLED")."</option>\n";

		$o=0;
		while ($mixes_to_print > $o)
			{
			$rowx=mysqli_fetch_row($rslt);
			$mixes_list .= "<option value=\"ACTIVE\">"._QXZ("ACTIVE")." ($rowx[0] - $rowx[1])</option>\n";
			$mixname_list["ACTIVE"] = "$rowx[0] - $rowx[1]";
			$o++;
			}
		}
		

	if ($SUB<1) {$camp_detail_color=$subcamp_color;}
else {$camp_detail_color=$campaigns_color;}
if ($SUB==22) {$camp_statuses_color=$subcamp_color;}
else {$camp_statuses_color=$campaigns_color;}
if ($SUB==23) {$camp_hotkeys_color=$subcamp_color;}
else {$camp_hotkeys_color=$campaigns_color;}
if ($SUB==25) {$camp_recycle_color=$subcamp_color;}
else {$camp_recycle_color=$campaigns_color;}
if ($SUB==26) {$camp_autoalt_color=$subcamp_color;}
else {$camp_autoalt_color=$campaigns_color;}
if ($SUB==27) {$camp_pause_color=$subcamp_color;}
else {$camp_pause_color=$campaigns_color;}
if ($SUB==29) {$camp_listmix_color=$subcamp_color;}
else {$camp_listmix_color=$campaigns_color;}

// Modern UI with inline styles
echo "<div style='max-width: 1400px; margin: 0 auto; background: #ffffff; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.1); overflow: hidden; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, sans-serif;'>";

// Campaign Header
echo "<div style='background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%); padding: 12px 20px; border-bottom: 2px solid #718096;'>";
echo "<div style='display: flex; align-items: center; gap: 10px;'>";
echo "<div style='width: 32px; height: 32px; background: #718096; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 16px; color: white; font-weight: bold;'>📢</div>";
echo "<h1 style='color: #f7fafc; font-size: 20px; font-weight: 600; margin: 0; letter-spacing: -0.3px;'>$campaign_id</h1>";
echo "</div>";
echo "</div>";

// Tab Navigation
echo "<div style='background: #ffffff; border-bottom: 2px solid #e8eaed; display: flex; padding: 0 20px; overflow-x: auto;'>";

echo "<a href='$PHP_SELF?ADD=34&campaign_id=$campaign_id' style='padding: 16px 24px; color: #1967d2; text-decoration: none; font-weight: 600; font-size: 14px; border-bottom: 3px solid #1967d2; background: #e8f0fe; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;'>";
echo "<span style='font-size: 18px;'>📊</span> "._QXZ("Basic View")."</a>";

echo "<a href='$PHP_SELF?ADD=31&campaign_id=$campaign_id' style='padding: 16px 24px; color: #5f6368; text-decoration: none; font-weight: 500; font-size: 14px; border-bottom: 3px solid transparent; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;'>";
echo "<span style='font-size: 18px;'>📋</span> "._QXZ("Detail View")."</a>";

if ($SSoutbound_autodial_active > 0) {
    echo "<a href='$PHP_SELF?ADD=34&SUB=29&campaign_id=$campaign_id' style='padding: 16px 24px; color: #5f6368; text-decoration: none; font-weight: 500; font-size: 14px; border-bottom: 3px solid transparent; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;'>";
    echo "<span style='font-size: 18px;'>📝</span> "._QXZ("List Mix")."</a>";
}

echo "<a href='./realtime_report.php?RR=4&DB=0&group=$campaign_id' style='padding: 16px 24px; color: #5f6368; text-decoration: none; font-weight: 500; font-size: 14px; border-bottom: 3px solid transparent; transition: all 0.3s ease; display: flex; align-items: center; gap: 8px;'>";
echo "<span style='font-size: 18px;'>📺</span> "._QXZ("Real-Time Screen")."</a>";

echo "</div>";

if ($SUB < 1) {
    echo "<div style='padding: 30px;'>";
    echo "<form action='$PHP_SELF' method='POST' style='margin: 0;'>";
    echo "<input type='hidden' name='ADD' value='44'>";
    echo "<input type='hidden' name='campaign_id' value='$campaign_id'>";
    echo "<input type='hidden' name='old_campaign_allow_inbound' value='$campaign_allow_inbound'>";
    
    // Form Container
    echo "<div style='max-width: 100%; background: #ffffff;'>";
    
    // Campaign Info Section
    echo "<div style='background: #f8f9fa; border-radius: 12px; padding: 24px; margin-bottom: 24px; border: 1px solid #e8eaed;'>";
    echo "<h2 style='color: #202124; font-size: 20px; font-weight: 600; margin: 0 0 20px 0; padding-bottom: 12px; border-bottom: 2px solid #1967d2;'>📌 Campaign Information</h2>";
    
    // Two Column Grid
    echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;'>";
    
    // Campaign ID
    echo "<div style='background: white; padding: 18px; border-radius: 8px; border-left: 4px solid #34a853;'>";
    echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("Campaign ID")."</label>";
    echo "<div style='color: #202124; font-size: 16px; font-weight: 600;'>$campaign_id</div>";
    echo "<span style='color: #5f6368; font-size: 11px;'>$NWB#campaigns-campaign_id$NWE</span>";
    echo "</div>";
    
    // Campaign Name
    echo "<div style='background: white; padding: 18px; border-radius: 8px; border-left: 4px solid #4285f4;'>";
    echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("Campaign Name")."</label>";
    echo "<input type='text' name='campaign_name' value='$row[1]' maxlength='40' style='width: 100%; padding: 12px 16px; border: 2px solid #e8eaed; border-radius: 8px; font-size: 14px; transition: all 0.3s ease; font-family: inherit;' onfocus='this.style.borderColor=\"#1967d2\"; this.style.boxShadow=\"0 0 0 3px rgba(25,103,210,0.1)\"' onblur='this.style.borderColor=\"#e8eaed\"; this.style.boxShadow=\"none\"'>";
    echo "<span style='color: #5f6368; font-size: 11px;'>$NWB#campaigns-campaign_name$NWE</span>";
    echo "</div>";
    
    echo "</div>"; // End two column grid
    
    // Description Row (Full Width)
    echo "<div style='background: white; padding: 18px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #fbbc04;'>";
    echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("Campaign Description")."</label>";
    echo "<div style='color: #202124; font-size: 14px; padding: 8px 0;'>$row[57]</div>";
    echo "<span style='color: #5f6368; font-size: 11px;'>$NWB#campaigns-campaign_description$NWE</span>";
    echo "</div>";
    
    // Dates Grid
    echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px; margin-top: 20px;'>";
    
    // Change Date
    echo "<div style='background: white; padding: 18px; border-radius: 8px; border-left: 4px solid #ea4335;'>";
    echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("Campaign Change Date")."</label>";
    echo "<div style='color: #202124; font-size: 14px; font-family: monospace;'>$campaign_changedate</div>";
    echo "<span style='color: #5f6368; font-size: 11px;'>$NWB#campaigns-campaign_changedate$NWE</span>";
    echo "</div>";
    
    // Login Date
    echo "<div style='background: white; padding: 18px; border-radius: 8px; border-left: 4px solid #9c27b0;'>";
    echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("Campaign Login Date")."</label>";
    echo "<div style='color: #202124; font-size: 14px; font-family: monospace;'>$campaign_logindate</div>";
    echo "<span style='color: #5f6368; font-size: 11px;'>$NWB#campaigns-campaign_logindate$NWE</span>";
    echo "</div>";
    
    echo "</div>"; // End dates grid
    echo "</div>"; // End Campaign Info Section
    
    // Settings Section
    echo "<div style='background: #f8f9fa; border-radius: 12px; padding: 24px; margin-bottom: 24px; border: 1px solid #e8eaed;'>";
    echo "<h2 style='color: #202124; font-size: 20px; font-weight: 600; margin: 0 0 20px 0; padding-bottom: 12px; border-bottom: 2px solid #ea4335;'>⚙️ Campaign Settings</h2>";
    
    echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;'>";
    
    // Active Status
    echo "<div style='background: white; padding: 18px; border-radius: 8px; border-left: 4px solid #34a853;'>";
    echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("Active")."</label>";
    echo "<select name='active' style='width: 100%; padding: 12px 16px; border: 2px solid #e8eaed; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; transition: all 0.3s ease; font-family: inherit;' onfocus='this.style.borderColor=\"#1967d2\"; this.style.boxShadow=\"0 0 0 3px rgba(25,103,210,0.1)\"' onblur='this.style.borderColor=\"#e8eaed\"; this.style.boxShadow=\"none\"'>";
    echo "<option value='Y'>"._QXZ("Y")."</option>";
    echo "<option value='N'>"._QXZ("N")."</option>";
    echo "<option value='$row[2]' selected>"._QXZ("$row[2]")."</option>";
    echo "</select>";
    echo "<span style='color: #5f6368; font-size: 11px; display: block; margin-top: 6px;'>$NWB#campaigns-active$NWE</span>";
    echo "</div>";
    
    // Admin User Group
    echo "<div style='background: white; padding: 18px; border-radius: 8px; border-left: 4px solid #4285f4;'>";
    echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("Admin User Group")."</label>";
    echo "<div style='color: #202124; font-size: 14px; padding: 8px 0; font-weight: 500;'>"._QXZ("$user_group")."</div>";
    echo "<span style='color: #5f6368; font-size: 11px;'>$NWB#campaigns-user_group$NWE</span>";
    echo "</div>";
    
    // Park Music-on-Hold
    echo "<div style='background: white; padding: 18px; border-radius: 8px; border-left: 4px solid #fbbc04;'>";
    echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("Park Music-on-Hold")."</label>";
    echo "<div style='color: #202124; font-size: 14px; padding: 8px 0;'>$row[10]</div>";
    echo "<span style='color: #5f6368; font-size: 11px;'>$NWB#campaigns-park_ext$NWE</span>";
    echo "</div>";
    
    // Web Form
    echo "<div style='background: white; padding: 18px; border-radius: 8px; border-left: 4px solid #9c27b0;'>";
    echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("Web Form")."</label>";
    echo "<div style='color: #202124; font-size: 14px; padding: 8px 0;'>$row[11]";
    if ($SSenable_first_webform < 1) {
        echo " <span style='color: #ea4335; font-weight: 600; background: #fce8e6; padding: 4px 12px; border-radius: 4px; font-size: 12px;'>"._QXZ("DISABLED")."</span>";
    }
    echo "</div>";
    echo "<span style='color: #5f6368; font-size: 11px;'>$NWB#campaigns-web_form_address$NWE</span>";
    echo "</div>";
    
    // Allow Closers
    echo "<div style='background: white; padding: 18px; border-radius: 8px; border-left: 4px solid #ea4335;'>";
    echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("Allow Closers")."</label>";
    echo "<div style='color: #202124; font-size: 14px; padding: 8px 0;'>"._QXZ("$row[12]")."</div>";
    echo "<span style='color: #5f6368; font-size: 11px;'>$NWB#campaigns-allow_closers$NWE</span>";
    echo "</div>";
    
    // Default Transfer Group
    echo "<div style='background: white; padding: 18px; border-radius: 8px; border-left: 4px solid #00acc1;'>";
    echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("Default Transfer Group")."</label>";
    echo "<div style='color: #202124; font-size: 14px; padding: 8px 0;'>"._QXZ("$default_xfer_group")."</div>";
    echo "<span style='color: #5f6368; font-size: 11px;'>$NWB#campaigns-default_xfer_group$NWE</span>";
    echo "</div>";
    
    // Allow Emails (conditional)
    if ($SSallow_emails > 0) {
        echo "<div style='background: white; padding: 18px; border-radius: 8px; border-left: 4px solid #f57c00;'>";
        echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("Allow Emails")."</label>";
        echo "<div style='color: #202124; font-size: 14px; padding: 8px 0;'>"._QXZ("$allow_emails")."</div>";
        echo "<span style='color: #5f6368; font-size: 11px;'>$NWB#campaigns-allow_emails$NWE</span>";
        echo "</div>";
    }
    
    // Allow Chats (conditional)
    if ($SSallow_chats > 0) {
        echo "<div style='background: white; padding: 18px; border-radius: 8px; border-left: 4px solid #7b1fa2;'>";
        echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("Allow Chats")."</label>";
        echo "<div style='color: #202124; font-size: 14px; padding: 8px 0;'>"._QXZ("$allow_chats")."</div>";
        echo "<span style='color: #5f6368; font-size: 11px;'>$NWB#campaigns-allow_chats$NWE</span>";
        echo "</div>";
    }
    
    echo "</div>"; // End settings grid
    echo "</div>"; // End Settings Section
    
    // Outbound Autodial Section (conditional)
    if ($SSoutbound_autodial_active > 0) {
        echo "<div style='background: #f8f9fa; border-radius: 12px; padding: 24px; margin-bottom: 24px; border: 1px solid #e8eaed;'>";
        echo "<h2 style='color: #202124; font-size: 20px; font-weight: 600; margin: 0 0 20px 0; padding-bottom: 12px; border-bottom: 2px solid #fbbc04;'>📞 Dialing & Campaign Controls</h2>";
        
        // Allow Inbound and Blended
        echo "<div style='background: white; padding: 18px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #34a853;'>";
        echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("Allow Inbound and Blended")."</label>";
        echo "<div style='color: #202124; font-size: 14px; padding: 8px 0;'>"._QXZ("$campaign_allow_inbound")."</div>";
        echo "<span style='color: #5f6368; font-size: 11px;'>$NWB#campaigns-campaign_allow_inbound$NWE</span>";
        echo "</div>";
        
        // Dial Statuses Section
        echo "<div style='background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 2px solid #e8eaed;'>";
        echo "<h3 style='color: #202124; font-size: 16px; font-weight: 600; margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px;'><span style='font-size: 20px;'>📋</span> Dial Statuses</h3>";
        
        $o = 0;
        while ($Ds_to_print > $o) {
            $o++;
            $Dstatus = $Dstatuses[$o];
            
            echo "<div style='display: flex; align-items: center; justify-content: space-between; padding: 14px 16px; margin-bottom: 10px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #4285f4;'>";
            
            if ($DEFstatusDISABLED > 0) {
                echo "<div style='color: #9aa0a6; text-decoration: line-through;'>";
                echo "<span style='font-weight: 600; font-size: 14px;'>$Dstatus</span> - ";
                echo "<span style='font-size: 13px;'>$statname_list[$Dstatus]</span>";
                echo "</div>";
                echo "<span style='color: #9aa0a6; font-size: 12px; font-weight: 500;'>"._QXZ("REMOVE")."</span>";
            } else {
                echo "<div>";
                echo "<span style='font-weight: 600; color: #202124; font-size: 14px;'>$Dstatus</span> - ";
                echo "<span style='color: #5f6368; font-size: 13px;'>$statname_list[$Dstatus]</span>";
                echo "</div>";
                echo "<a href='$PHP_SELF?ADD=68&campaign_id=$campaign_id&status=$Dstatuses[$o]' style='color: #ea4335; text-decoration: none; font-weight: 600; font-size: 12px; padding: 6px 14px; background: #fce8e6; border-radius: 6px; transition: all 0.3s ease;' onmouseover='this.style.background=\"#f8bab8\"' onmouseout='this.style.background=\"#fce8e6\"'>"._QXZ("REMOVE")."</a>";
            }
            
            echo "</div>";
        }
        
        // Add Dial Status
        echo "<div style='display: flex; gap: 12px; align-items: end; margin-top: 16px; padding: 16px; background: #e8f0fe; border-radius: 8px;'>";
        echo "<div style='flex: 1;'>";
        echo "<label style='display: block; color: #1967d2; font-size: 13px; font-weight: 600; margin-bottom: 8px;'>"._QXZ("Add A Dial Status to Call")."</label>";
        echo "<select name='dial_status' $DEFlistDISABLE style='width: 100%; padding: 12px 16px; border: 2px solid #1967d2; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; font-family: inherit;'>";
        echo "<option value=''> - "._QXZ("NONE")." - </option>";
        echo "$dial_statuses_list";
        echo "</select>";
        echo "</div>";
        echo "<input type='submit' name='submit' value='"._QXZ("ADD")."' style='padding: 12px 32px; background: linear-gradient(135deg, #1967d2 0%, #1557b0 100%); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.3s ease; font-family: inherit;' onmouseover='this.style.transform=\"translateY(-2px)\"; this.style.boxShadow=\"0 6px 20px rgba(25,103,210,0.4)\"' onmouseout='this.style.transform=\"translateY(0)\"; this.style.boxShadow=\"none\"'>";
        echo "</div>";
        echo "<span style='color: #5f6368; font-size: 11px; display: block; margin-top: 8px;'>$NWB#campaigns-dial_status$NWE</span>";
        
        echo "</div>"; // End Dial Statuses box
        
        // Lead Management Grid
        echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px;'>";
        
        // List Order
        echo "<div style='background: white; padding: 18px; border-radius: 8px; border-left: 4px solid #4285f4;'>";
        echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("List Order")."</label>";
        
        if ($ALTmultiDISABLE > 0) {
            echo "<input type='hidden' name='lead_order' value='$lead_order'>";
            echo "<div style='padding: 12px; background: #f8f9fa; border-radius: 6px; color: #5f6368;'>$ALTmultiLINK</div>";
        } else {
            if (file_exists('options.php')) {
                require('options.php');
            }
            
            echo "<select name='lead_order' style='width: 100%; padding: 12px 16px; border: 2px solid #e8eaed; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; transition: all 0.3s ease; font-family: inherit;' onfocus='this.style.borderColor=\"#1967d2\"; this.style.boxShadow=\"0 0 0 3px rgba(25,103,210,0.1)\"' onblur='this.style.borderColor=\"#e8eaed\"; this.style.boxShadow=\"none\"'>";
            echo "<option value='$lead_order' selected>"._QXZ("$lead_order")."</option>";
            echo "<option value='DOWN'>"._QXZ("DOWN")."</option>";
            echo "<option value='UP'>"._QXZ("UP")."</option>";
            echo "<option value='DOWN PHONE'>"._QXZ("DOWN PHONE")."</option>";
            echo "<option value='UP PHONE'>"._QXZ("UP PHONE")."</option>";
            echo "<option value='DOWN LAST NAME'>"._QXZ("DOWN LAST NAME")."</option>";
            echo "<option value='UP LAST NAME'>"._QXZ("UP LAST NAME")."</option>";
            echo "<option value='DOWN COUNT'>"._QXZ("DOWN COUNT")."</option>";
            echo "<option value='UP COUNT'>"._QXZ("UP COUNT")."</option>";
            
            if ($camp_lead_order_random > 0) {
                echo "<option value='RANDOM'>"._QXZ("RANDOM")."</option>";
            }
            
            echo "<option value='DOWN LAST CALL TIME'>"._QXZ("DOWN LAST CALL TIME")."</option>";
            echo "<option value='UP LAST CALL TIME'>"._QXZ("UP LAST CALL TIME")."</option>";
            echo "<option value='DOWN RANK'>"._QXZ("DOWN RANK")."</option>";
            echo "<option value='UP RANK'>"._QXZ("UP RANK")."</option>";
            echo "<option value='DOWN OWNER'>"._QXZ("DOWN OWNER")."</option>";
            echo "<option value='UP OWNER'>"._QXZ("UP OWNER")."</option>";
            echo "<option value='DOWN TIMEZONE'>"._QXZ("DOWN TIMEZONE")."</option>";
            echo "<option value='UP TIMEZONE'>"._QXZ("UP TIMEZONE")."</option>";
            
            // 2nd through 6th NEW options
            for ($i = 2; $i <= 6; $i++) {
                echo "<option value='DOWN {$i}th NEW'>"._QXZ("DOWN {$i}th NEW")."</option>";
                echo "<option value='UP {$i}th NEW'>"._QXZ("UP {$i}th NEW")."</option>";
            }
            
            echo "</select>";
        }
        echo "<span style='color: #5f6368; font-size: 11px; display: block; margin-top: 6px;'>$NWB#campaigns-lead_order$NWE</span>";
        echo "</div>";
        
        // List Mix
        echo "<div style='background: white; padding: 18px; border-radius: 8px; border-left: 4px solid #fbbc04;'>";
        echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;'>";
        echo "<a href='$PHP_SELF?ADD=31&SUB=29&campaign_id=$campaign_id&vcl_id=$list_order_mix' style='color: #5f6368; text-decoration: none;'>"._QXZ("List Mix")."</a>";
        echo "</label>";
        
        if ($ALTmultiDISABLE > 0) {
            echo "<input type='hidden' name='list_order_mix' value='$list_order_mix'>";
            echo "<div style='padding: 12px; background: #f8f9fa; border-radius: 6px; color: #5f6368;'>$ALTmultiLINK</div>";
        } else {
            echo "<select name='list_order_mix' style='width: 100%; padding: 12px 16px; border: 2px solid #e8eaed; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; transition: all 0.3s ease; font-family: inherit;' onfocus='this.style.borderColor=\"#1967d2\"; this.style.boxShadow=\"0 0 0 3px rgba(25,103,210,0.1)\"' onblur='this.style.borderColor=\"#e8eaed\"; this.style.boxShadow=\"none\"'>";
            echo "$mixes_list";
            
            if (preg_match('/DISABLED/', $list_order_mix)) {
                echo "<option selected value='$list_order_mix'>"._QXZ("$list_order_mix")." - "._QXZ("$mixname_list[$list_order_mix]")."</option>";
            } else {
                echo "<option selected value='ACTIVE'>"._QXZ("ACTIVE")." ($mixname_list[ACTIVE])</option>";
            }
            
            echo "</select>";
        }
        echo "<span style='color: #5f6368; font-size: 11px; display: block; margin-top: 6px;'>$NWB#campaigns-list_order_mix$NWE</span>";
        echo "</div>";
        
        // Lead Filter
        echo "<div style='background: white; padding: 18px; border-radius: 8px; border-left: 4px solid #ea4335;'>";
        echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;'>";
        echo "<a href='$PHP_SELF?ADD=31111111&lead_filter_id=$lead_filter_id' style='color: #5f6368; text-decoration: none;'>"._QXZ("Lead Filter")."</a>";
        echo "</label>";
        
        if ($ALTmultiDISABLE > 0) {
            echo "<input type='hidden' name='lead_filter_id' value='$lead_filter_id'>";
            echo "<div style='padding: 12px; background: #f8f9fa; border-radius: 6px; color: #5f6368;'>$ALTmultiLINK</div>";
        } else {
            echo "<select name='lead_filter_id' style='width: 100%; padding: 12px 16px; border: 2px solid #e8eaed; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; transition: all 0.3s ease; font-family: inherit;' onfocus='this.style.borderColor=\"#1967d2\"; this.style.boxShadow=\"0 0 0 3px rgba(25,103,210,0.1)\"' onblur='this.style.borderColor=\"#e8eaed\"; this.style.boxShadow=\"none\"'>";
            echo "$filters_list";
            echo "<option selected value='$lead_filter_id'>"._QXZ("$lead_filter_id")." - $filtername_list[$lead_filter_id]</option>";
            echo "</select>";
        }
        echo "<span style='color: #5f6368; font-size: 11px; display: block; margin-top: 6px;'>$NWB#campaigns-lead_filter_id$NWE</span>";
        echo "</div>";
        
        // Minimum Hopper Level
        echo "<div style='background: white; padding: 18px; border-radius: 8px; border-left: 4px solid #9c27b0;'>";
        echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("Minimum Hopper Level")."</label>";
        echo "<select name='hopper_level' style='width: 100%; padding: 12px 16px; border: 2px solid #e8eaed; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; transition: all 0.3s ease; font-family: inherit;' onfocus='this.style.borderColor=\"#1967d2\"; this.style.boxShadow=\"0 0 0 3px rgba(25,103,210,0.1)\"' onblur='this.style.borderColor=\"#e8eaed\"; this.style.boxShadow=\"none\"'>";
        echo "<option>1</option><option>5</option><option>10</option><option>20</option><option>50</option><option>100</option><option>200</option><option>500</option><option>700</option><option>1000</option><option>2000</option><option>3000</option><option>4000</option><option>5000</option>";
        echo "<option selected>$hopper_level</option>";
        echo "</select>";
        echo "<span style='color: #5f6368; font-size: 11px; display: block; margin-top: 6px;'>$NWB#campaigns-hopper_level$NWE</span>";
        echo "</div>";
        
        // Force Reset of Hopper
        echo "<div style='background: white; padding: 18px; border-radius: 8px; border-left: 4px solid #00acc1;'>";
        echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("Force Reset of Hopper")."</label>";
        echo "<select name='reset_hopper' style='width: 100%; padding: 12px 16px; border: 2px solid #e8eaed; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; transition: all 0.3s ease; font-family: inherit;' onfocus='this.style.borderColor=\"#1967d2\"; this.style.boxShadow=\"0 0 0 3px rgba(25,103,210,0.1)\"' onblur='this.style.borderColor=\"#e8eaed\"; this.style.boxShadow=\"none\"'>";
        echo "<option value='Y'>"._QXZ("Y")."</option>";
        echo "<option value='N' selected>"._QXZ("N")."</option>";
        echo "</select>";
        echo "<span style='color: #5f6368; font-size: 11px; display: block; margin-top: 6px;'>$NWB#campaigns-force_reset_hopper$NWE</span>";
        echo "</div>";
        
        echo "</div>"; // End Lead Management Grid
        
        // Auto-dial Warning (conditional)
        if (preg_match("/RATIO|ADAPT/", $dial_method) && $SSdisable_auto_dial > 0) {
            echo "<div style='background: #fce8e6; border-left: 4px solid #ea4335; padding: 16px 20px; border-radius: 8px; margin-top: 20px;'>";
            echo "<div style='display: flex; align-items: center; gap: 12px;'>";
            echo "<span style='font-size: 24px;'>⚠️</span>";
            echo "<span style='color: #c5221f; font-weight: 600; font-size: 14px;'>"._QXZ("Auto-dialing has been disabled on this system")."</span>";
            echo "</div>";
            echo "</div>";
        }
        
        // Dial Method
        echo "<div style='background: white; padding: 18px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #f57c00;'>";
        echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("Dial Method")."</label>";
        echo "<select name='dial_method' style='width: 100%; padding: 12px 16px; border: 2px solid #e8eaed; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; transition: all 0.3s ease; font-family: inherit;' onfocus='this.style.borderColor=\"#1967d2\"; this.style.boxShadow=\"0 0 0 3px rgba(25,103,210,0.1)\"' onblur='this.style.borderColor=\"#e8eaed\"; this.style.boxShadow=\"none\"'>";
        echo "<option value='MANUAL'>"._QXZ("MANUAL")."</option>";
        echo "<option value='RATIO'>"._QXZ("RATIO")."</option>";
        echo "<option value='ADAPT_HARD_LIMIT'>"._QXZ("ADAPT_HARD_LIMIT")."</option>";
        echo "<option value='ADAPT_TAPERED'>"._QXZ("ADAPT_TAPERED")."</option>";
        echo "<option value='ADAPT_AVERAGE'>"._QXZ("ADAPT_AVERAGE")."</option>";
        echo "<option value='INBOUND_MAN'>"._QXZ("INBOUND_MAN")."</option>";
        echo "<option value='$dial_method' selected>"._QXZ("$dial_method")."</option>";
        echo "</select>";
        echo "<span style='color: #5f6368; font-size: 11px; display: block; margin-top: 6px;'>$NWB#campaigns-dial_method$NWE</span>";
        echo "</div>";
        
        // Auto Dial Level
        echo "<div style='background: white; padding: 18px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #7b1fa2;'>";
        echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("Auto Dial Level")."</label>";
        echo "<select name='auto_dial_level' style='width: 100%; padding: 12px 16px; border: 2px solid #e8eaed; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; transition: all 0.3s ease; font-family: inherit;' onfocus='this.style.borderColor=\"#1967d2\"; this.style.boxShadow=\"0 0 0 3px rgba(25,103,210,0.1)\"' onblur='this.style.borderColor=\"#e8eaed\"; this.style.boxShadow=\"none\"'>";
        echo "<option selected>$auto_dial_level</option>";
        echo "<option>0</option>";
                // Continue Auto Dial Level options
        $adl=0;
        while($adl <= $SSauto_dial_limit) {
            if ($adl < 1) {
                $adl = ($adl + 1);
            } else {
                if ($adl < 3) {
                    $adl = ($adl + 0.1);
                } else {
                    if ($adl < 4) {
                        $adl = ($adl + 0.25);
                    } else {
                        if ($adl < 5) {
                            $adl = ($adl + 0.5);
                        } else {
                            if ($adl < 20) {
                                $adl = ($adl + 1);
                            } else {
                                if ($adl < 40) {
                                    $adl = ($adl + 2);
                                } else {
                                    if ($adl < 100) {
                                        $adl = ($adl + 5);
                                    } else {
                                        if ($adl < 200) {
                                            $adl = ($adl + 10);
                                        } else {
                                            if ($adl < 400) {
                                                $adl = ($adl + 50);
                                            } else {
                                                if ($adl < 1000) {
                                                    $adl = ($adl + 100);
                                                } else {
                                                    $adl = ($adl + 1);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ($adl > $SSauto_dial_limit) {
                $hmm=1;
            } else {
                echo "<option>$adl</option>";
            }
        }
        echo "</select>";
        echo "<span style='color: #5f6368; font-size: 12px; margin-left: 8px;'>(0 = "._QXZ("off").")</span>";
        echo "<span style='color: #5f6368; font-size: 11px; display: block; margin-top: 6px;'>$NWB#campaigns-auto_dial_level$NWE</span>";
        echo "</div>";
        
        // Adapt Intensity Modifier
        echo "<div style='background: white; padding: 18px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #00bcd4;'>";
        echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("Adapt Intensity Modifier")."</label>";
        echo "<select name='adaptive_intensity' style='width: 100%; padding: 12px 16px; border: 2px solid #e8eaed; border-radius: 8px; font-size: 14px; background: white; cursor: pointer; transition: all 0.3s ease; font-family: inherit;' onfocus='this.style.borderColor=\"#1967d2\"; this.style.boxShadow=\"0 0 0 3px rgba(25,103,210,0.1)\"' onblur='this.style.borderColor=\"#e8eaed\"; this.style.boxShadow=\"none\"'>";
        
        $n=40;
        while ($n>=-40) {
            $dtl = _QXZ("Balanced");
            if ($n<0) {$dtl = _QXZ("Less Intense");}
            if ($n>0) {$dtl = _QXZ("More Intense");}
            if ($n == $adaptive_intensity) {
                echo "<option selected value='$n'>$n - $dtl</option>";
            } else {
                echo "<option value='$n'>$n - $dtl</option>";
            }
            $n--;
        }
        echo "</select>";
        echo "<span style='color: #5f6368; font-size: 11px; display: block; margin-top: 6px;'>$NWB#campaigns-adaptive_intensity$NWE</span>";
        echo "</div>";
        
        echo "</div>"; // End Outbound Autodial Section
    }
    
    // Script and Get Call Launch
    echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px; margin-bottom: 24px;'>";
    
    // Script
    echo "<div style='background: white; padding: 18px; border-radius: 8px; border-left: 4px solid #673ab7; box-shadow: 0 2px 8px rgba(0,0,0,0.05);'>";
    echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;'>";
    echo "<a href='$PHP_SELF?ADD=3111111&script_id=$script_id' style='color: #5f6368; text-decoration: none;'>"._QXZ("Script")."</a>";
    echo "</label>";
    echo "<div style='color: #202124; font-size: 14px; padding: 8px 0;'>"._QXZ("$script_id")."</div>";
    echo "</div>";
    
    // Get Call Launch
    echo "<div style='background: white; padding: 18px; border-radius: 8px; border-left: 4px solid #ff5722; box-shadow: 0 2px 8px rgba(0,0,0,0.05);'>";
    echo "<label style='display: block; color: #5f6368; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("Get Call Launch")."</label>";
    echo "<div style='color: #202124; font-size: 14px; padding: 8px 0;'>"._QXZ("$get_call_launch")."</div>";
    echo "</div>";
    
    echo "</div>"; // End Script/Call Launch grid
    
    // Submit Button
    echo "<div style='display: flex; justify-content: center; padding: 30px 0; border-top: 2px solid #e8eaed; margin-top: 20px;'>";
    echo "<input type='submit' name='SUBMIT' value='"._QXZ("SUBMIT")."' style='padding: 16px 48px; background: linear-gradient(135deg, #34a853 0%, #2d8e47 100%); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 16px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(52,168,83,0.3); font-family: inherit; text-transform: uppercase; letter-spacing: 0.5px;' onmouseover='this.style.transform=\"translateY(-2px)\"; this.style.boxShadow=\"0 6px 20px rgba(52,168,83,0.4)\"' onmouseout='this.style.transform=\"translateY(0)\"; this.style.boxShadow=\"0 4px 12px rgba(52,168,83,0.3)\"'>";
    echo "</div>";
    
    echo "</div>"; // End form container
    echo "</form>";
    echo "</div>"; // End padding div
    
    // Lists Section
    if ($SSoutbound_autodial_active > 0) {
        echo "<div style='padding: 0 30px 30px 30px;'>";
        echo "<form action='$PHP_SELF' method='POST' style='margin: 0;'>";
        echo "<input type='hidden' name='ADD' value='44'>";
        echo "<input type='hidden' name='DB' value='$DB'>";
        echo "<input type='hidden' name='stage' value='list_activation'>";
        echo "<input type='hidden' name='campaign_id' value='$campaign_id'>";
        
        // Lists Header
        echo "<div style='background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); padding: 20px 24px; border-radius: 12px 12px 0 0; margin-bottom: 0;'>";
        echo "<h2 style='color: #ffffff; font-size: 20px; font-weight: 600; margin: 0; display: flex; align-items: center; gap: 10px;'>";
        echo "<span style='font-size: 24px;'>📋</span> "._QXZ("LISTS WITHIN THIS CAMPAIGN");
        echo "<span style='color: #5f6368; font-size: 11px; margin-left: 10px;'>$NWB#campaign_lists$NWE</span>";
        echo "</h2>";
        echo "</div>";
        
        // Setup sorting links
        $LISTlink='stage=LISTIDDOWN';
        $NAMElink='stage=LISTNAMEDOWN';
        $CALLTIMElink='stage=CALLTIMEDOWN';
        $TALLYlink='stage=TALLYDOWN';
        $ACTIVElink='stage=ACTIVEDOWN';
        $CAMPAIGNlink='stage=CAMPAIGNDOWN';
        $CALLDATElink='stage=CALLDATEDOWN';
        $SQLorder='order by list_id';
        
        if (preg_match('/LISTIDUP/i', $stage)) {$SQLorder='order by list_id asc'; $LISTlink='stage=LISTIDDOWN';}
        if (preg_match('/LISTIDDOWN/i', $stage)) {$SQLorder='order by list_id desc'; $LISTlink='stage=LISTIDUP';}
        if (preg_match('/LISTNAMEUP/i', $stage)) {$SQLorder='order by list_name asc'; $NAMElink='stage=LISTNAMEDOWN';}
        if (preg_match('/LISTNAMEDOWN/i', $stage)) {$SQLorder='order by list_name desc'; $NAMElink='stage=LISTNAMEUP';}
        if (preg_match('/CALLTIMEUP/i', $stage)) {$SQLorder='order by local_call_time asc'; $CALLTIMElink='stage=CALLTIMEDOWN';}
        if (preg_match('/CALLTIMEDOWN/i', $stage)) {$SQLorder='order by local_call_time desc'; $CALLTIMElink='stage=CALLTIMEUP';}
        if (preg_match('/TALLYUP/i', $stage)) {$SQLorder='order by tally asc'; $TALLYlink='stage=TALLYDOWN';}
        if (preg_match('/TALLYDOWN/i', $stage)) {$SQLorder='order by tally desc'; $TALLYlink='stage=TALLYUP';}
        if (preg_match('/ACTIVEUP/i', $stage)) {$SQLorder='order by active asc'; $ACTIVElink='stage=ACTIVEDOWN';}
        if (preg_match('/ACTIVEDOWN/i', $stage)) {$SQLorder='order by active desc'; $ACTIVElink='stage=ACTIVEUP';}
        if (preg_match('/CAMPAIGNUP/i', $stage)) {$SQLorder='order by campaign_id asc'; $CAMPAIGNlink='stage=CAMPAIGNDOWN';}
        if (preg_match('/CALLDATEUP/i', $stage)) {$SQLorder='order by list_lastcalldate asc'; $CALLDATElink='stage=CALLDATEDOWN';}
        if (preg_match('/CALLDATEDOWN/i', $stage)) {$SQLorder='order by list_lastcalldate desc'; $CALLDATElink='stage=CALLDATEUP';}
        
        $stmt="SELECT vls.list_id,list_name,list_description,count(*) as tally,active,list_lastcalldate,campaign_id,DATE_FORMAT(expiration_date,'%Y%m%d'),local_call_time from vicidial_lists vls,vicidial_list vl where vls.list_id=vl.list_id and campaign_id='$campaign_id' $LOGallowed_campaignsSQL group by list_id $SQLorder";
        if ($SSadmin_list_counts < 1) {
            $stmt="SELECT list_id,list_name,list_description,'X' as tally,active,list_lastcalldate,campaign_id,DATE_FORMAT(expiration_date,'%Y%m%d'),local_call_time from vicidial_lists where campaign_id='$campaign_id' $LOGallowed_campaignsSQL $SQLorder";
        }
        $rslt=mysql_to_mysqli($stmt, $link);
        $lists_to_print = mysqli_num_rows($rslt);
        
        // Table
        echo "<div style='background: white; border-radius: 0 0 12px 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08);'>";
        echo "<table style='width: 100%; border-collapse: collapse; font-size: 14px;'>";
        
        // Table Header
        echo "<thead>";
        echo "<tr style='background: linear-gradient(135deg, #1967d2 0%, #1557b0 100%); color: white;'>";
        echo "<th style='padding: 14px 16px; text-align: left; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;'><a href='$PHP_SELF?ADD=34&campaign_id=$campaign_id&$LISTlink' style='color: white; text-decoration: none; display: flex; align-items: center; gap: 6px;'>"._QXZ("LIST ID")." <span style='font-size: 10px;'>↕</span></a></th>";
        echo "<th style='padding: 14px 16px; text-align: left; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;'><a href='$PHP_SELF?ADD=34&campaign_id=$campaign_id&$NAMElink' style='color: white; text-decoration: none; display: flex; align-items: center; gap: 6px;'>"._QXZ("LIST NAME")." <span style='font-size: 10px;'>↕</span></a></th>";
        echo "<th style='padding: 14px 16px; text-align: left; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("DESCRIPTION")."</th>";
        echo "<th style='padding: 14px 16px; text-align: center; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;'><a href='$PHP_SELF?ADD=34&campaign_id=$campaign_id&$TALLYlink' style='color: white; text-decoration: none; display: flex; align-items: center; gap: 6px; justify-content: center;'>"._QXZ("LEADS COUNT")." <span style='font-size: 10px;'>↕</span></a></th>";
        echo "<th style='padding: 14px 16px; text-align: center; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;'><a href='$PHP_SELF?ADD=34&campaign_id=$campaign_id&$CALLTIMElink' style='color: white; text-decoration: none; display: flex; align-items: center; gap: 6px; justify-content: center;'>"._QXZ("Call Time")." <span style='font-size: 10px;'>↕</span></a></th>";
        echo "<th style='padding: 14px 16px; text-align: center; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;' colspan='3'><a href='$PHP_SELF?ADD=31&campaign_id=$campaign_id&$ACTIVElink' style='color: white; text-decoration: none; display: flex; align-items: center; gap: 6px; justify-content: center;'>"._QXZ("ACTIVE")." <span style='font-size: 10px;'>↕</span></a></th>";
        echo "<th style='padding: 14px 16px; text-align: center; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;'><a href='$PHP_SELF?ADD=34&campaign_id=$campaign_id&$CALLDATElink' style='color: white; text-decoration: none; display: flex; align-items: center; gap: 6px; justify-content: center;'>"._QXZ("LAST CALL DATE")." <span style='font-size: 10px;'>↕</span></a></th>";
        echo "<th style='padding: 14px 16px; text-align: center; font-weight: 600; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;'>"._QXZ("MODIFY")."</th>";
        echo "</tr>";
        echo "</thead>";
        
        echo "<tbody>";
        
        $o=0;
        $last_list_statuses="";
        $active_lists = 0;
        $inactive_lists = 0;
        $camp_lists = "";
        
        while ($lists_to_print > $o) {
            $row=mysqli_fetch_row($rslt);
            $last_list_statuses.="$row[0]|$row[4]|";
            
            if (preg_match('/1$|3$|5$|7$|9$/i', $o)) {
                $bgcolor='#f8f9fa';
            } else {
                $bgcolor='#ffffff';
            }
            
            echo "<tr style='background: $bgcolor; border-bottom: 1px solid #e8eaed; transition: all 0.2s ease;' onmouseover='this.style.background=\"#e8f0fe\"' onmouseout='this.style.background=\"$bgcolor\"'>";
            
            // List ID
            echo "<td style='padding: 14px 16px;'><a href='$PHP_SELF?ADD=311&list_id=$row[0]' style='color: #1967d2; text-decoration: none; font-weight: 600;'>$row[0]</a></td>";
            
            // List Name
            echo "<td style='padding: 14px 16px; color: #202124;'>$row[1]</td>";
            
            // Description
            echo "<td style='padding: 14px 16px; color: #5f6368; font-size: 13px;'>$row[2]</td>";
            
            // Tally
            echo "<td style='padding: 14px 16px; text-align: center; color: #202124; font-weight: 600;'>$row[3]</td>";
            
            // Call Time
            if ($row[8] == 'campaign') {
                echo "<td style='padding: 14px 16px; text-align: center; color: #5f6368;'>$row[8]</td>";
            } else {
                echo "<td style='padding: 14px 16px; text-align: center;'><a href='$PHP_SELF?ADD=311111111&call_time_id=$row[8]' style='color: #1967d2; text-decoration: none;'>$row[8]</a></td>";
            }
            
            // Active Status Text
            echo "<td style='padding: 14px 16px; text-align: center;'>";
            if (preg_match('/Y/', $row[4])) {
                echo "<span style='background: #e6f4ea; color: #1e8e3e; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;'>"._QXZ("$row[4]")."</span>";
            } else {
                echo "<span style='background: #fce8e6; color: #c5221f; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;'>"._QXZ("$row[4]")."</span>";
            }
            echo "</td>";
            
            // Active Checkbox
            echo "<td style='padding: 14px 16px; text-align: center;'>";
            if (preg_match('/Y/', $row[4])) {
                $active_lists++;
                $camp_lists .= "'$row[0]',";
                echo "<input type='checkbox' name='list_active_change[]' value='$row[0]' checked style='width: 18px; height: 18px; cursor: pointer; accent-color: #1967d2;'>";
            } else {
                $inactive_lists++;
                echo "<input type='checkbox' name='list_active_change[]' value='$row[0]' style='width: 18px; height: 18px; cursor: pointer; accent-color: #1967d2;'>";
            }
            echo "</td>";
            
            // Expiration
            echo "<td style='padding: 14px 16px; text-align: center;'>";
            if ($row[7] < $EXPtestdate) {
                echo "<span style='background: #fce8e6; color: #c5221f; padding: 4px 10px; border-radius: 4px; font-weight: 700; font-size: 11px;'>"._QXZ("EXP")."</span>";
            } else {
                echo "&nbsp;";
            }
            echo "</td>";
            
            // Last Call Date
            echo "<td style='padding: 14px 16px; text-align: center; color: #5f6368; font-family: monospace; font-size: 12px;'>$row[5]</td>";
            
            // Modify
            echo "<td style='padding: 14px 16px; text-align: center;'><a href='$PHP_SELF?ADD=311&list_id=$row[0]' style='color: #1967d2; text-decoration: none; font-weight: 600; padding: 6px 14px; background: #e8f0fe; border-radius: 6px; display: inline-block; transition: all 0.2s ease;' onmouseover='this.style.background=\"#d2e3fc\"' onmouseout='this.style.background=\"#e8f0fe\"'>"._QXZ("MODIFY")."</a></td>";
            
            echo "</tr>";
            $o++;
        }
        
        // Submit Row
        echo "<tr>";
        echo "<td colspan='10' style='padding: 20px; text-align: center; background: #f8f9fa; border-top: 2px solid #e8eaed;'>";
        echo "<input type='button' onClick='return ConfirmListStatusChange($SSlist_status_modification_confirmation, this.form)' value='"._QXZ("SUBMIT ACTIVE LIST CHANGES")."' style='padding: 12px 36px; background: linear-gradient(135deg, #34a853 0%, #2d8e47 100%); color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(52,168,83,0.3); font-family: inherit;' onmouseover='this.style.transform=\"translateY(-2px)\"; this.style.boxShadow=\"0 6px 20px rgba(52,168,83,0.4)\"' onmouseout='this.style.transform=\"translateY(0)\"; this.style.boxShadow=\"0 4px 12px rgba(52,168,83,0.3)\"'>";
        echo "</td>";
        echo "</tr>";
        
        echo "</tbody>";
        echo "</table>";
        echo "</div>"; // End table container
        
        echo "<input type='hidden' name='last_list_statuses' id='last_list_statuses' value='$last_list_statuses'>";
        echo "</form>";
        
        // Lists Summary
        $camp_lists = preg_replace('/.$/i', '', $camp_lists);
        echo "<div style='background: #e8f0fe; padding: 16px 20px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #1967d2;'>";
        echo "<div style='color: #1967d2; font-size: 14px; font-weight: 500;'>";
        echo _QXZ("This campaign has")." <span style='font-weight: 700; color: #34a853;'>$active_lists</span> "._QXZ("active lists and")." <span style='font-weight: 700; color: #ea4335;'>$inactive_lists</span> "._QXZ("inactive lists");
        echo "</div>";
        echo "</div>";
        
        // Lead Statuses Section
        if (($display_leads_count == 'Y') && (strlen($camp_lists) > 3)) {
            // Grab status names
            $stmt="SELECT status,status_name from vicidial_statuses order by status;";
            $rslt=mysql_to_mysqli($stmt, $link);
            $statuses_to_print = mysqli_num_rows($rslt);
            
            $o=0;
            while ($statuses_to_print > $o) {
                $rowx=mysqli_fetch_row($rslt);
                $statuses_name_list["$rowx[0]"] = "$rowx[1]";
                $o++;
            }
            
            $stmt="SELECT status,status_name from vicidial_campaign_statuses where campaign_id IN($camp_status_groups'$campaign_id') $LOGallowed_campaignsSQL order by status;";
            $rslt=mysql_to_mysqli($stmt, $link);
            $Cstatuses_to_print = mysqli_num_rows($rslt);
            
            $o=0;
            while ($Cstatuses_to_print > $o) {
                $rowx=mysqli_fetch_row($rslt);
                $statuses_name_list["$rowx[0]"] = "$rowx[1]";
                $o++;
            }
            
            echo "<div style='background: white; padding: 24px; border-radius: 12px; margin-top: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #fbbc04;'>";
            echo "<div style='display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;'>";
            echo "<h3 style='color: #202124; font-size: 18px; font-weight: 600; margin: 0; display: flex; align-items: center; gap: 10px;'><span style='font-size: 22px;'>📊</span> "._QXZ("STATUSES WITHIN THE ACTIVE LISTS FOR THIS CAMPAIGN")."</h3>";
            echo "<a href='$PHP_SELF?ADD=34&campaign_id=$campaign_id&stage=hide_leadscount' style='color: #5f6368; text-decoration: none; font-size: 12px; font-weight: 600; padding: 6px 14px; background: #f8f9fa; border-radius: 6px;'>"._QXZ("HIDE")."</a>";
            echo "</div>";
            
            echo "<table style='width: 100%; border-collapse: collapse; font-size: 14px;'>";
            echo "<thead>";
            echo "<tr style='background: #f8f9fa; border-bottom: 2px solid #e8eaed;'>";
            echo "<th style='padding: 12px 16px; text-align: left; font-weight: 600; font-size: 13px; color: #5f6368;'>"._QXZ("STATUS")."</th>";
            echo "<th style='padding: 12px 16px; text-align: left; font-weight: 600; font-size: 13px; color: #5f6368;'>"._QXZ("STATUS NAME")."</th>";
            echo "<th style='padding: 12px 16px; text-align: center; font-weight: 600; font-size: 13px; color: #5f6368;'>"._QXZ("CALLED")."</th>";
            echo "<th style='padding: 12px 16px; text-align: center; font-weight: 600; font-size: 13px; color: #5f6368;'>"._QXZ("NOT CALLED")."</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            
            $leads_in_list = 0;
            $leads_in_list_N = 0;
            $leads_in_list_Y = 0;
            
            $filterSQL = $filtersql_list[$lead_filter_id];
            $filterSQL = preg_replace("/\\\\/", "", $filterSQL);
            $filterSQL = preg_replace('/^and|and$|^or|or$/i', '', $filterSQL);
            if (strlen($filterSQL)>4) {
                $fSQL = "and ($filterSQL)";
            } else {
                $fSQL = '';
            }
            
            $stmt="SELECT status,called_since_last_reset,count(*) from vicidial_list where list_id IN($camp_lists) group by status,called_since_last_reset order by status,called_since_last_reset;";
            if ($DB) {echo "$stmt\n";}
            $rslt=mysql_to_mysqli($stmt, $link);
            $statuses_to_print = mysqli_num_rows($rslt);
            
            $o=0;
            $lead_list['count'] = 0;
            $lead_list['Y_count'] = 0;
            $lead_list['N_count'] = 0;
            
            while ($statuses_to_print > $o) {
                $rowx=mysqli_fetch_row($rslt);
                $lead_list['count'] = ($lead_list['count'] + $rowx[2]);
                
                if ($rowx[1] == 'N') {
                    $since_reset = 'N';
                    $since_resetX = 'Y';
                } else {
                    $since_reset = 'Y';
                    $since_resetX = 'N';
                }
                
                $lead_list[$since_reset][$rowx[0]] = ($lead_list[$since_reset][$rowx[0]] + $rowx[2]);
                $lead_list[$since_reset.'_count'] = ($lead_list[$since_reset.'_count'] + $rowx[2]);
                
                if (!isset($lead_list[$since_resetX][$rowx[0]])) {
                    $lead_list[$since_resetX][$rowx[0]]=0;
                }
                $o++;
            }
            
            $o=0;
            if ($lead_list['count'] > 0) {
                foreach($lead_list[$since_reset] as $dispo => $blank) {
                    if (preg_match('/1$|3$|5$|7$|9$/i', $o)) {
                        $bgcolor='#f8f9fa';
                    } else {
                        $bgcolor='#ffffff';
                    }
                    
                    if ($dispo == 'CBHOLD') {
                        $CLB="<a href='$PHP_SELF?ADD=811&list_id=$list_id' style='color: #1967d2; text-decoration: none;'>";
                        $CLE="</a>";
                    } else {
                        $CLB='';
                        $CLE='';
                    }
                    
                    echo "<tr style='background: $bgcolor; border-bottom: 1px solid #e8eaed;'>";
                    echo "<td style='padding: 12px 16px; color: #202124; font-weight: 600;'>$CLB$dispo$CLE</td>";
                    echo "<td style='padding: 12px 16px; color: #5f6368;'>$statuses_name_list[$dispo]</td>";
                    echo "<td style='padding: 12px 16px; text-align: center; color: #202124; font-weight: 500;'>".$lead_list['Y'][$dispo]."</td>";
                    echo "<td style='padding: 12px 16px; text-align: center; color: #202124; font-weight: 500;'>".$lead_list['N'][$dispo]."</td>";
                    echo "</tr>";
                    $o++;
                }
            }
            
            // Subtotals
            echo "<tr style='background: #e8f0fe; font-weight: 600;'>";
            echo "<td colspan='2' style='padding: 12px 16px; color: #1967d2;'>"._QXZ("SUBTOTALS")."</td>";
            echo "<td style='padding: 12px 16px; text-align: center; color: #1967d2;'>$lead_list[Y_count]</td>";
            echo "<td style='padding: 12px 16px; text-align: center; color: #1967d2;'>$lead_list[N_count]</td>";
            echo "</tr>";
            
            // Total
            echo "<tr style='background: #1967d2; color: white; font-weight: 700;'>";
            echo "<td style='padding: 14px 16px;'>"._QXZ("TOTAL")."</td>";
            echo "<td colspan='3' style='padding: 14px 16px; text-align: center;'>$lead_list[count]</td>";
            echo "</tr>";
            
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
            
            unset($lead_list);
        } else {
            echo "<div style='background: #f8f9fa; padding: 16px 20px; border-radius: 8px; margin-top: 20px; text-align: center;'>";
            echo "<a href='$PHP_SELF?ADD=34&campaign_id=$campaign_id&stage=show_leadscount' style='color: #1967d2; text-decoration: none; font-weight: 600; font-size: 14px;'>"._QXZ("SHOW LEAD STATUSES IN THIS CAMPAIGN")."</a>";
            echo "</div>";
        }
        
        // Dialable Leads Count
        echo "<div style='background: white; padding: 20px; border-radius: 8px; margin-top: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #34a853;'>";
        
        if ($display_dialable_count == 'Y') {
            $single_status=0;
            $only_return=0;
            dialable_leads($DB,$link,$local_call_time,$dial_statuses,$camp_lists,$drop_lockout_time,$call_count_limit,$single_status,$fSQL,$only_return);
            echo "<div style='margin-top: 10px;'>";
            echo "<a href='$PHP_SELF?ADD=34&campaign_id=$campaign_id&stage=hide_dialable' style='color: #5f6368; text-decoration: none; font-size: 12px; font-weight: 600;'>"._QXZ("HIDE")."</a>";
            echo "</div>";
        } else {
            echo "<div style='display: flex; align-items: center; gap: 12px;'>";
            echo "<a href='$PHP_SELF?ADD=73&campaign_id=$campaign_id' target='_blank' style='color: #1967d2; text-decoration: none; font-weight: 600; font-size: 14px;'>Popup Dialable Leads Count</a>";
            echo "<span style='color: #dadce0;'>|</span>";
            echo "<a href='$PHP_SELF?ADD=31&campaign_id=$campaign_id&stage=show_dialable' style='color: #5f6368; text-decoration: none; font-size: 12px; font-weight: 600;'>"._QXZ("SHOW")."</a>";
            echo "</div>";
        }
        echo "</div>";
        
        // Hopper Info
        $stmt="SELECT count(*) FROM vicidial_hopper where campaign_id='$campaign_id' and status IN('READY','RHOLD','RQUEUE') $LOGallowed_campaignsSQL;";
        if ($DB) {echo "$stmt\n";}
        $rslt=mysql_to_mysqli($stmt, $link);
        $rowx=mysqli_fetch_row($rslt);
        $hopper_leads = "$rowx[0]";
        
        echo "<div style='background: #e8f0fe; padding: 20px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #1967d2;'>";
        echo "<div style='color: #1967d2; font-size: 14px; font-weight: 500; margin-bottom: 16px;'>";
        echo _QXZ("This campaign has")." <span style='font-weight: 700; font-size: 18px; color: #1967d2;'>$hopper_leads</span> "._QXZ("leads in the dial hopper");
        echo "</div>";
        
        echo "<div style='display: flex; flex-direction: column; gap: 10px;'>";
        echo "<a href='./AST_VICIDIAL_hopperlist.php?group=$campaign_id' style='color: #1967d2; text-decoration: none; font-size: 14px; font-weight: 500;'>→ "._QXZ("Click here to see what leads are in the hopper right now")."</a>";
        echo "<a href='./AST_VDADstats.php?group=$campaign_id' style='color: #1967d2; text-decoration: none; font-size: 14px; font-weight: 500;'>→ "._QXZ("Click here to see a VDAD report for this campaign")."</a>";
        echo "</div>";
        echo "</div>";
        
        echo "</div>"; // End padding div for lists section
    }
    
    // Action Links Section
    echo "<div style='padding: 0 30px 30px 30px;'>";
    echo "<div style='background: white; padding: 24px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #ea4335;'>";
    echo "<h3 style='color: #202124; font-size: 18px; font-weight: 600; margin: 0 0 16px 0; display: flex; align-items: center; gap: 10px;'><span style='font-size: 22px;'>🔗</span> Quick Actions</h3>";
    
    echo "<div style='display: flex; flex-direction: column; gap: 12px;'>";
    echo "<a href='$PHP_SELF?ADD=81&campaign_id=$campaign_id' style='color: #1967d2; text-decoration: none; font-size: 14px; font-weight: 500; padding: 12px 16px; background: #f8f9fa; border-radius: 8px; transition: all 0.2s ease; display: inline-block;' onmouseover='this.style.background=\"#e8f0fe\"' onmouseout='this.style.background=\"#f8f9fa\"'>→ "._QXZ("Click here to see all CallBack Holds in this campaign")."</a>";
    
    if (($LOGuser_level >= 9) && ((preg_match("/Administration Change Log/", $LOGallowed_reports)) || (preg_match("/ALL REPORTS/", $LOGallowed_reports)))) {
        echo "<a href='$PHP_SELF?ADD=720000000000000&category=CAMPAIGNS&stage=$campaign_id' style='color: #1967d2; text-decoration: none; font-size: 14px; font-weight: 500; padding: 12px 16px; background: #f8f9fa; border-radius: 8px; transition: all 0.2s ease; display: inline-block;' onmouseover='this.style.background=\"#e8f0fe\"' onmouseout='this.style.background=\"#f8f9fa\"'>→ "._QXZ("Click here to see Admin changes to this campaign")."</a>";
    }
    echo "</div>";
    echo "</div>";
    
    // Agent Ranks Section
    echo "<div style='background: white; padding: 24px; border-radius: 12px; margin-top: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-left: 4px solid #9c27b0;'>";
    echo "<h3 style='color: #202124; font-size: 18px; font-weight: 600; margin: 0 0 20px 0; display: flex; align-items: center; gap: 10px;'><span style='font-size: 22px;'>👥</span> "._QXZ("AGENT RANKS FOR THIS CAMPAIGN")."</h3>";
    
    echo "<table style='width: 100%; border-collapse: collapse; font-size: 14px;'>";
    echo "<thead>";
    echo "<tr style='background: #f8f9fa; border-bottom: 2px solid #e8eaed;'>";
    echo "<th style='padding: 12px 16px; text-align: left; font-weight: 600; font-size: 13px; color: #5f6368;'>"._QXZ("USER")."</th>";
    echo "<th style='padding: 12px 16px; text-align: center; font-weight: 600; font-size: 13px; color: #5f6368;'>"._QXZ("RANK")."</th>";
    echo "<th style='padding: 12px 16px; text-align: center; font-weight: 600; font-size: 13px; color: #5f6368;'>"._QXZ("GRADE")."</th>";
    echo "<th style='padding: 12px 16px; text-align: center; font-weight: 600; font-size: 13px; color: #5f6368;'>"._QXZ("CALLS TODAY")."</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    $stmt="SELECT user_group from vicidial_user_groups where ( (allowed_campaigns LIKE \"%-ALL-CAMPAIGNS-%\") or (allowed_campaigns LIKE \"% $campaign_id %\") ) $LOGadmin_viewable_groupsSQL;";
    $rslt=mysql_to_mysqli($stmt, $link);
    $USERgroups_to_print = mysqli_num_rows($rslt);
    if ($DB) {echo "$USERgroups_to_print|$stmt\n";}
    $USERgroupsSQL="''";
    $i=0;
    while ($i < $USERgroups_to_print) {
        $row=mysqli_fetch_row($rslt);
        $USERgroupsSQL .= ",'$row[0]'";
        $i++;
    }
    
    $stmt="SELECT user,full_name from vicidial_users where user_group IN($USERgroupsSQL) and active='Y' order by user;";
    $rslt=mysql_to_mysqli($stmt, $link);
    $users_to_print = mysqli_num_rows($rslt);
    if ($DB) {echo "$users_to_print|$stmt\n";}
    $U_user=array();
    $U_full_name=array();
    $i=0;
    while ($i < $users_to_print) {
        $row=mysqli_fetch_row($rslt);
        $U_user[$i] = $row[0];
        $U_full_name[$i] = $row[1];
        $i++;
    }
    
    $o=0;
    while ($users_to_print > $o) {
        $temp_user = $U_user[$o];
        $temp_name = $U_full_name[$o];
        $campaign_rank='n/a';
        $calls_today='n/a';
        $campaign_grade='n/a';
        
        if (preg_match('/1$|3$|5$|7$|9$/i', $o)) {
            $bgcolor='#f8f9fa';
        } else {
            $bgcolor='#ffffff';
        }
        
        $stmt="SELECT campaign_rank,calls_today,campaign_grade from vicidial_campaign_agents where user='$U_user[$o]' and campaign_id='$campaign_id';";
        $rslt=mysql_to_mysqli($stmt, $link);
        $USERdetails_to_print = mysqli_num_rows($rslt);
        if ($USERdetails_to_print > 0) {
            $row=mysqli_fetch_row($rslt);
            $campaign_rank = $row[0];
            $calls_today = $row[1];
            $campaign_grade = $row[2];
        }
        $o++;
        
        echo "<tr style='background: $bgcolor; border-bottom: 1px solid #e8eaed;'>";
        echo "<td style='padding: 12px 16px;'><a href='$PHP_SELF?ADD=3&user=$temp_user' style='color: #1967d2; text-decoration: none; font-weight: 600;'>$temp_user</a> <span style='color: #5f6368;'>- $temp_name</span></td>";
        echo "<td style='padding: 12px 16px; text-align: center; color: #202124; font-weight: 500;'>$campaign_rank</td>";
        echo "<td style='padding: 12px 16px; text-align: center; color: #202124; font-weight: 500;'>$campaign_grade</td>";
        echo "<td style='padding: 12px 16px; text-align: center; color: #202124; font-weight: 500;'>$calls_today</td>";
        echo "</tr>";
    }
    
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    
    // Logout Link
    echo "<div style='background: #fff3e0; padding: 16px 20px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #ff9800;'>";
    echo "<a href='$PHP_SELF?ADD=52&campaign_id=$campaign_id&DB=$DB' style='color: #e65100; text-decoration: none; font-weight: 600; font-size: 14px;'>🔓 "._QXZ("LOG ALL AGENTS OUT OF THIS CAMPAIGN")."</a>";
    echo "</div>";
    
    // Delete Campaign Link (conditional)
    if ($LOGdelete_campaigns > 0) {
        echo "<div style='background: #fce8e6; padding: 16px 20px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #ea4335;'>";
        echo "<a href='$PHP_SELF?ADD=51&campaign_id=$campaign_id' style='color: #c5221f; text-decoration: none; font-weight: 600; font-size: 14px;'>🗑️ "._QXZ("DELETE THIS CAMPAIGN")."</a>";
        echo "</div>";
    }
    
    echo "</div>"; // End action links padding
    
} // End SUB < 1 condition
} else {
    echo "<div style='padding: 40px; text-align: center; background: #fce8e6; border-radius: 8px; margin: 20px;'>";
    echo "<div style='font-size: 48px; margin-bottom: 16px;'>⛔</div>";
    echo "<div style='color: #c5221f; font-size: 18px; font-weight: 600;'>"._QXZ("You do not have permission to view this page")."</div>";
    echo "</div>";
    exit;
}

echo "</div>";
	}