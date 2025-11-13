<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ViciDial Admin Interface</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1976D2;
            --primary-dark: #0D47A1;
            --primary-light: #2196F3;
            --secondary-color: #FFC107;
            --accent-color: #FF4081;
            --background: #F5F7FA;
            --surface: #FFFFFF;
            --sidebar-bg: #1E293B;
            --sidebar-text: #E2E8F0;
            --sidebar-active: #3B82F6;
            --text-primary: #1A202C;
            --text-secondary: #4A5568;
            --border-color: #E2E8F0;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 20px rgba(0,0,0,0.15);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            line-height: 1.6;
        }
        
        .app-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            overflow-y: auto;
            z-index: 100;
        }
        
        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .sidebar-header img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
        
        .sidebar-header h2 {
            font-weight: 500;
            font-size: 18px;
            text-align: center;
            letter-spacing: 0.5px;
        }
        
        .nav-section {
            margin-bottom: 20px;
        }
        
        .nav-section-title {
            padding: 12px 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .nav-item {
            position: relative;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--sidebar-text);
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
        }
        
        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        
        .nav-item.active {
            background-color: var(--sidebar-active);
            color: white;
        }
        
        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: var(--accent-color);
        }
        
        .nav-item i {
            margin-right: 15px;
            font-size: 20px;
        }
        
        .nav-item-text {
            font-weight: 400;
            font-size: 15px;
        }
        
        .sub-menu {
            display: none;
            background-color: rgba(0, 0, 0, 0.2);
        }
        
        .sub-menu.show {
            display: block;
        }
        
        .sub-menu .nav-item {
            padding-left: 55px;
            font-size: 14px;
        }
        
        /* Main Content Area */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        /* Header Styles */
        .header {
            background-color: var(--surface);
            box-shadow: var(--shadow-sm);
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            z-index: 50;
        }
        
        .header-left {
            display: flex;
            align-items: center;
        }
        
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 24px;
            margin-right: 16px;
            cursor: pointer;
        }
        
        .breadcrumb {
            display: flex;
            align-items: center;
            color: var(--text-secondary);
            font-size: 14px;
        }
        
        .breadcrumb-item {
            margin-right: 8px;
        }
        
        .breadcrumb-item:not(:last-child)::after {
            content: '/';
            margin-left: 8px;
        }
        
        .header-right {
            display: flex;
            align-items: center;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            margin-right: 16px;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 500;
            margin-right: 12px;
        }
        
        .user-name {
            font-weight: 500;
            margin-right: 8px;
        }
        
        .user-role {
            font-size: 12px;
            color: var(--text-secondary);
        }
        
        .header-actions {
            display: flex;
            align-items: center;
        }
        
        .header-btn {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 20px;
            margin-left: 16px;
            cursor: pointer;
            position: relative;
        }
        
        .header-btn:hover {
            color: var(--primary-color);
        }
        
        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--accent-color);
        }
        
        /* Content Area */
        .content {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
        }
        
        .page-title {
            font-size: 28px;
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--text-primary);
        }
        
        .page-subtitle {
            font-size: 16px;
            color: var(--text-secondary);
            margin-bottom: 24px;
        }
        
        /* Cards */
        .card {
            background-color: var(--surface);
            border-radius: 8px;
            box-shadow: var(--shadow-sm);
            padding: 24px;
            margin-bottom: 24px;
        }
        
        .card-title {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 16px;
            color: var(--text-primary);
        }
        
        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        
        .data-table th {
            text-align: left;
            padding: 12px 16px;
            background-color: var(--background);
            font-weight: 500;
            color: var(--text-secondary);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .data-table td {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .data-table tr:last-child td {
            border-bottom: none;
        }
        
        .data-table tr:hover {
            background-color: var(--background);
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 500;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
        }
        
        .btn i {
            margin-right: 8px;
            font-size: 18px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
        }
        
        .btn-secondary {
            background-color: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }
        
        .btn-secondary:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        /* Forms */
        .form-group {
            margin-bottom: 16px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-primary);
        }
        
        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
            transition: var(--transition);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.1);
        }
        
        /* Status Indicators */
        .status {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .status-active {
            background-color: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
        }
        
        .status-inactive {
            background-color: rgba(244, 67, 54, 0.1);
            color: #F44336;
        }
        
        .status-pending {
            background-color: rgba(255, 152, 0, 0.1);
            color: #FF9800;
        }
        
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 6px;
        }
        
        .status-active .status-dot {
            background-color: #4CAF50;
        }
        
        .status-inactive .status-dot {
            background-color: #F44336;
        }
        
        .status-pending .status-dot {
            background-color: #FF9800;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -260px;
                height: 100vh;
                z-index: 1000;
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .menu-toggle {
                display: block;
            }
            
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }
            
            .sidebar-overlay.show {
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="./images/vicidial_admin_web_logo.png" alt="ViciDial Logo">
                <h2>ADMINISTRATION</h2>
            </div>
            
            <!-- Reports Section -->
            <div class="nav-section">
                <div class="nav-section-title">Reports</div>
                <a href="<?php echo $ADMIN ?>?ADD=999999" class="nav-item <?php echo ($hh=='reports') ? 'active' : ''; ?>">
                    <i class="material-icons">assessment</i>
                    <span class="nav-item-text">Reports</span>
                </a>
            </div>
            
            <!-- Users Section -->
            <div class="nav-section">
                <div class="nav-section-title">Users</div>
                <a href="<?php echo $ADMIN ?>?ADD=0A" class="nav-item <?php echo ($hh=='users') ? 'active' : ''; ?>" onclick="toggleSubMenu(this)">
                    <i class="material-icons">people</i>
                    <span class="nav-item-text">Users</span>
                    <i class="material-icons" style="margin-left: auto;">expand_more</i>
                </a>
                <div class="sub-menu <?php echo ($hh=='users') ? 'show' : ''; ?>">
                    <a href="<?php echo $ADMIN ?>?ADD=0A" class="nav-item">Show Users</a>
                    <a href="<?php echo $ADMIN ?>?ADD=1" class="nav-item">Add A New User</a>
                    <a href="<?php echo $ADMIN ?>?ADD=1A" class="nav-item">Copy User</a>
                    <a href="<?php echo $ADMIN ?>?ADD=550" class="nav-item">Search For A User</a>
                    <a href="./user_stats.php?user=<?php echo $user ?>" class="nav-item">User Stats</a>
                    <a href="./user_status.php?user=<?php echo $user ?>" class="nav-item">User Status</a>
                    <a href="./AST_agent_time_sheet.php?agent=<?php echo $user ?>" class="nav-item">Time Sheet</a>
                    <?php if (($SSuser_territories_active > 0) or ($user_territories_active > 0)) { ?>
                    <a href="./user_territories.php?agent=<?php echo $user ?>" class="nav-item">User Territories</a>
                    <?php } ?>
                    <?php if ($SSuser_new_lead_limit > 0) { ?>
                    <a href="./admin_user_list_new.php?user=---ALL---&list_id=NONE&stage=overall" class="nav-item">Overall New Lead Limits</a>
                    <?php } ?>
                </div>
            </div>
            
            <!-- Campaigns Section -->
            <div class="nav-section">
                <div class="nav-section-title">Campaigns</div>
                <a href="<?php echo $ADMIN ?>?ADD=10" class="nav-item <?php echo ($hh=='campaigns') ? 'active' : ''; ?>" onclick="toggleSubMenu(this)">
                    <i class="material-icons">campaign</i>
                    <span class="nav-item-text">Campaigns</span>
                    <i class="material-icons" style="margin-left: auto;">expand_more</i>
                </a>
                <div class="sub-menu <?php echo ($hh=='campaigns') ? 'show' : ''; ?>">
                    <a href="<?php echo $ADMIN ?>?ADD=10" class="nav-item">Campaigns Main</a>
                    <a href="<?php echo $ADMIN ?>?ADD=32" class="nav-item">Statuses</a>
                    <a href="<?php echo $ADMIN ?>?ADD=33" class="nav-item">HotKeys</a>
                    <?php if ($SSoutbound_autodial_active > 0) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=35" class="nav-item">Lead Recycle</a>
                    <a href="<?php echo $ADMIN ?>?ADD=36" class="nav-item">Auto-Alt Dial</a>
                    <a href="<?php echo $ADMIN ?>?ADD=39" class="nav-item">List Mix</a>
                    <?php } ?>
                    <a href="<?php echo $ADMIN ?>?ADD=37" class="nav-item">Pause Codes</a>
                    <a href="<?php echo $ADMIN ?>?ADD=301" class="nav-item">Presets</a>
                    <?php if ($SScampaign_cid_areacodes_enabled > 0) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=302" class="nav-item">AC-CID</a>
                    <?php } ?>
                </div>
            </div>
            
            <!-- Lists Section -->
            <?php if ($SSoutbound_autodial_active > 0) { ?>
            <div class="nav-section">
                <div class="nav-section-title">Lists</div>
                <a href="<?php echo $ADMIN ?>?ADD=100" class="nav-item <?php echo ($hh=='lists') ? 'active' : ''; ?>" onclick="toggleSubMenu(this)">
                    <i class="material-icons">list</i>
                    <span class="nav-item-text">Lists</span>
                    <i class="material-icons" style="margin-left: auto;">expand_more</i>
                </a>
                <div class="sub-menu <?php echo ($hh=='lists') ? 'show' : ''; ?>">
                    <a href="<?php echo $ADMIN ?>?ADD=100" class="nav-item">Show Lists</a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=111" class="nav-item">Add A New List</a>
                    <?php } ?>
                    <a href="admin_search_lead.php" class="nav-item">Search For A Lead</a>
                    <a href="admin_modify_lead.php" class="nav-item">Add A New Lead</a>
                    <a href="<?php echo $ADMIN ?>?ADD=121" class="nav-item"><?php echo ($LOGdelete_from_dnc > 0) ? "Add-Delete DNC Number" : "Add DNC Number"; ?></a>
                    <a href="./admin_listloader_fourth_gen.php" class="nav-item">Load New Leads</a>
                    <?php if ($SScustom_fields_enabled > 0) { ?>
                    <a href="./admin_lists_custom.php" class="nav-item">List Custom Fields</a>
                    <a href="./admin_lists_custom.php?action=COPY_FIELDS_FORM" class="nav-item">Copy Custom Fields</a>
                    <?php } ?>
                    <?php if ($SSenable_drop_lists > 0) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=130" class="nav-item">Drop Lists</a>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            
            <!-- Quality Control Section -->
            <?php if (($SSqc_features_active=='1') && ($qc_auth=='1')) { ?>
            <div class="nav-section">
                <div class="nav-section-title">Quality Control</div>
                <a href="<?php echo $ADMIN ?>?ADD=100000000000000" class="nav-item <?php echo ($hh=='qc') ? 'active' : ''; ?>" onclick="toggleSubMenu(this)">
                    <i class="material-icons">verified</i>
                    <span class="nav-item-text">Quality Control</span>
                    <i class="material-icons" style="margin-left: auto;">expand_more</i>
                </a>
                <div class="sub-menu <?php echo ($hh=='qc') ? 'show' : ''; ?>">
                    <a href="<?php echo $ADMIN ?>?ADD=100000000000000&qc_display_group_type=CAMPAIGN" class="nav-item">QC Calls by Campaign</a>
                    <a href="<?php echo $ADMIN ?>?ADD=100000000000000&qc_display_group_type=LIST" class="nav-item">QC Calls by List</a>
                    <a href="<?php echo $ADMIN ?>?ADD=100000000000000&qc_display_group_type=INGROUP" class="nav-item">QC Calls by Ingroup</a>
                    <a href="qc_scorecards.php" class="nav-item">Show QC Scorecards</a>
                    <a href="<?php echo $ADMIN ?>?ADD=341111111111111" class="nav-item">Modify QC Codes</a>
                </div>
            </div>
            <?php } ?>
            
            <!-- Scripts Section -->
            <div class="nav-section">
                <div class="nav-section-title">Scripts</div>
                <a href="<?php echo $ADMIN ?>?ADD=1000000" class="nav-item <?php echo ($hh=='scripts') ? 'active' : ''; ?>" onclick="toggleSubMenu(this)">
                    <i class="material-icons">description</i>
                    <span class="nav-item-text">Scripts</span>
                    <i class="material-icons" style="margin-left: auto;">expand_more</i>
                </a>
                <div class="sub-menu <?php echo ($hh=='scripts') ? 'show' : ''; ?>">
                    <a href="<?php echo $ADMIN ?>?ADD=1000000" class="nav-item">Show Scripts</a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=1111111" class="nav-item">Add A New Script</a>
                    <?php } ?>
                </div>
            </div>
            
            <!-- Filters Section -->
            <?php if ($SSoutbound_autodial_active > 0) { ?>
            <div class="nav-section">
                <div class="nav-section-title">Filters</div>
                <a href="<?php echo $ADMIN ?>?ADD=10000000" class="nav-item <?php echo ($hh=='filters') ? 'active' : ''; ?>" onclick="toggleSubMenu(this)">
                    <i class="material-icons">filter_list</i>
                    <span class="nav-item-text">Filters</span>
                    <i class="material-icons" style="margin-left: auto;">expand_more</i>
                </a>
                <div class="sub-menu <?php echo ($hh=='filters') ? 'show' : ''; ?>">
                    <a href="<?php echo $ADMIN ?>?ADD=10000000" class="nav-item">Show Filters</a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=11111111" class="nav-item">Add A New Filter</a>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            
            <!-- Inbound Section -->
            <div class="nav-section">
                <div class="nav-section-title">Inbound</div>
                <a href="<?php echo $ADMIN ?>?ADD=1001" class="nav-item <?php echo ($hh=='ingroups') ? 'active' : ''; ?>" onclick="toggleSubMenu(this)">
                    <i class="material-icons">call_received</i>
                    <span class="nav-item-text">Inbound</span>
                    <i class="material-icons" style="margin-left: auto;">expand_more</i>
                </a>
                <div class="sub-menu <?php echo ($hh=='ingroups') ? 'show' : ''; ?>">
                    <a href="<?php echo $ADMIN ?>?ADD=1000" class="nav-item">Show In-Groups</a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=1111" class="nav-item">Add A New In-Group</a>
                    <a href="<?php echo $ADMIN ?>?ADD=1211" class="nav-item">Copy In-Group</a>
                    <?php } ?>
                    
                    <?php if ($SSemail_enabled>0) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=1800" class="nav-item">Show Email Groups</a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=1811" class="nav-item">Add New Email Group</a>
                    <a href="<?php echo $ADMIN ?>?ADD=1911" class="nav-item">Copy Email Group</a>
                    <?php } ?>
                    <?php } ?>
                    
                    <?php if ($SSchat_enabled>0) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=1900" class="nav-item">Show Chat Groups</a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=18111" class="nav-item">Add New Chat Group</a>
                    <a href="<?php echo $ADMIN ?>?ADD=19111" class="nav-item">Copy Chat Group</a>
                    <?php } ?>
                    <?php } ?>
                    
                    <a href="<?php echo $ADMIN ?>?ADD=1300" class="nav-item">Show DIDs</a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=1311" class="nav-item">Add A New DID</a>
                    <a href="<?php echo $ADMIN ?>?ADD=1411" class="nav-item">Copy DID</a>
                    <?php } ?>
                    <?php if ($SSdid_ra_extensions_enabled > 0) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=1320" class="nav-item">RA Extensions</a>
                    <?php } ?>
                    
                    <a href="<?php echo $ADMIN ?>?ADD=1500" class="nav-item">Show Call Menus</a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=1511" class="nav-item">Add A New Call Menu</a>
                    <a href="<?php echo $ADMIN ?>?ADD=1611" class="nav-item">Copy Call Menu</a>
                    <?php } ?>
                    
                    <a href="<?php echo $ADMIN ?>?ADD=1700" class="nav-item">Filter Phone Groups</a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=1711" class="nav-item">Add Filter Phone Group</a>
                    <?php } ?>
                    <a href="<?php echo $ADMIN ?>?ADD=171" class="nav-item"><?php echo ($LOGdelete_from_dnc > 0) ? "Add-Delete FPG Number" : "Add FPG Number"; ?></a>
                </div>
            </div>
            
            <!-- User Groups Section -->
            <div class="nav-section">
                <div class="nav-section-title">User Groups</div>
                <a href="<?php echo $ADMIN ?>?ADD=100000" class="nav-item <?php echo ($hh=='usergroups') ? 'active' : ''; ?>" onclick="toggleSubMenu(this)">
                    <i class="material-icons">groups</i>
                    <span class="nav-item-text">User Groups</span>
                    <i class="material-icons" style="margin-left: auto;">expand_more</i>
                </a>
                <div class="sub-menu <?php echo ($hh=='usergroups') ? 'show' : ''; ?>">
                    <a href="<?php echo $ADMIN ?>?ADD=100000" class="nav-item">Show User Groups</a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=111111" class="nav-item">Add A New User Group</a>
                    <?php } ?>
                    <a href="group_hourly_stats.php" class="nav-item">Group Hourly Report</a>
                    <a href="user_group_bulk_change.php" class="nav-item">Bulk Group Change</a>
                </div>
            </div>
            
            <!-- Remote Agents Section -->
            <div class="nav-section">
                <div class="nav-section-title">Remote Agents</div>
                <a href="<?php echo $ADMIN ?>?ADD=10000" class="nav-item <?php echo ($hh=='remoteagent') ? 'active' : ''; ?>" onclick="toggleSubMenu(this)">
                    <i class="material-icons">computer</i>
                    <span class="nav-item-text">Remote Agents</span>
                    <i class="material-icons" style="margin-left: auto;">expand_more</i>
                </a>
                <div class="sub-menu <?php echo ($hh=='remoteagent') ? 'show' : ''; ?>">
                    <a href="<?php echo $ADMIN ?>?ADD=10000" class="nav-item">Show Remote Agents</a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=11111" class="nav-item">Add New Remote Agents</a>
                    <?php } ?>
                    <a href="<?php echo $ADMIN ?>?ADD=12000" class="nav-item">Show Extension Groups</a>
                    <?php if ($add_copy_disabled < 1) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=12111" class="nav-item">Add Extension Group</a>
                    <?php } ?>
                </div>
            </div>
            
            <!-- Admin Section -->
            <div class="nav-section">
                <div class="nav-section-title">Admin</div>
                <a href="<?php echo $ADMIN ?>?ADD=999998" class="nav-item <?php echo ($hh=='admin') ? 'active' : ''; ?>" onclick="toggleSubMenu(this)">
                    <i class="material-icons">settings</i>
                    <span class="nav-item-text">Admin</span>
                    <i class="material-icons" style="margin-left: auto;">expand_more</i>
                </a>
                <div class="sub-menu <?php echo ($hh=='admin') ? 'show' : ''; ?>">
                    <a href="<?php echo $ADMIN ?>?ADD=100000000" class="nav-item">Call Times</a>
                    <a href="<?php echo $ADMIN ?>?ADD=130000000" class="nav-item">Shifts</a>
                    <a href="<?php echo $ADMIN ?>?ADD=10000000000" class="nav-item">Phones</a>
                    <a href="<?php echo $ADMIN ?>?ADD=130000000000" class="nav-item">Templates</a>
                    <a href="<?php echo $ADMIN ?>?ADD=140000000000" class="nav-item">Carriers</a>
                    <a href="<?php echo $ADMIN ?>?ADD=100000000000" class="nav-item">Servers</a>
                    <a href="<?php echo $ADMIN ?>?ADD=1000000000000" class="nav-item">Conferences</a>
                    <a href="<?php echo $ADMIN ?>?ADD=311111111111111" class="nav-item">System Settings</a>
                    <a href="<?php echo $ADMIN ?>?ADD=180000000000" class="nav-item">Screen Labels</a>
                    <a href="<?php echo $ADMIN ?>?ADD=182000000000" class="nav-item">Screen Colors</a>
                    <a href="<?php echo $ADMIN ?>?ADD=321111111111111" class="nav-item">System Statuses</a>
                    <a href="<?php echo $ADMIN ?>?ADD=193000000000" class="nav-item">Status Groups</a>
                    <?php if ($SScampaign_cid_areacodes_enabled > 0) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=196000000000" class="nav-item">CID Groups</a>
                    <?php } ?>
                    <a href="<?php echo $ADMIN ?>?ADD=170000000000" class="nav-item">Voicemail</a>
                    <?php if ($SSemail_enabled > 0) { ?>
                    <a href="admin_email_accounts.php" class="nav-item">Email Accounts</a>
                    <?php } ?>
                    <?php if (($sounds_central_control_active > 0) or ($SSsounds_central_control_active > 0)) { ?>
                    <a href="audio_store.php" class="nav-item">Audio Store</a>
                    <a href="<?php echo $ADMIN ?>?ADD=160000000000" class="nav-item">Music On Hold</a>
                    <?php if ($SSenable_languages > 0) { ?>
                    <a href="admin_languages.php?ADD=163000000000" class="nav-item">Languages</a>
                    <?php } ?>
                    <?php if ((preg_match("/soundboard/",$SSactive_modules) ) or ($SSagent_soundboards > 0)) { ?>
                    <a href="admin_soundboard.php?ADD=162000000000" class="nav-item">Audio Soundboards</a>
                    <?php } ?>
                    <a href="<?php echo $ADMIN ?>?ADD=197000000000" class="nav-item">VM Message Groups</a>
                    <?php } ?>
                    <?php if ($SSenable_tts_integration > 0) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=150000000000" class="nav-item">Text To Speech</a>
                    <?php } ?>
                    <?php if ($SScallcard_enabled > 0) { ?>
                    <a href="callcard_admin.php" class="nav-item">CallCard Admin</a>
                    <?php } ?>
                    <?php if ($SScontacts_enabled > 0) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=190000000000" class="nav-item">Contacts</a>
                    <?php } ?>
                    <a href="<?php echo $ADMIN ?>?ADD=192000000000" class="nav-item">Settings Containers</a>
                    <?php if ($SSenable_auto_reports > 0) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=194000000000" class="nav-item">Automated Reports</a>
                    <?php } ?>
                    <?php if ($SSallow_ip_lists > 0) { ?>
                    <a href="<?php echo $ADMIN ?>?ADD=195000000000" class="nav-item">IP Lists</a>
                    <?php } ?>
                    <a href="<?php echo $ADMIN ?>?ADD=198000000000" class="nav-item">Queue Groups</a>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="material-icons">menu</i>
                    </button>
                    <div class="breadcrumb">
                        <span class="breadcrumb-item"><a href="<?php echo $admin_home_url_LU ?>" style="text-decoration: none; color: inherit;">HOME</a></span>
                        <span class="breadcrumb-item"><a href="../agc/timeclock.php?referrer=admin" style="text-decoration: none; color: inherit;">Timeclock</a></span>
                        <span class="breadcrumb-item"><a href="manager_chat_interface.php" style="text-decoration: none; color: inherit;">Chat</a></span>
                        <span class="breadcrumb-item"><a href="<?php echo $ADMIN ?>?force_logout=1" style="text-decoration: none; color: inherit;">Logout</a></span>
                        <span class="breadcrumb-item">(<?php echo $PHP_AUTH_USER ?>)</span>
                        <?php if ($SSenable_languages == '1') { ?>
                        <span class="breadcrumb-item"><a href="<?php echo $ADMIN ?>?ADD=999989" style="text-decoration: none; color: inherit;">Change language</a></span>
                        <?php } ?>
                    </div>
                </div>
                <div class="header-right">
                    <div class="user-info">
                        <div class="user-avatar"><?php echo substr($PHP_AUTH_USER, 0, 2); ?></div>
                        <div>
                            <div class="user-name"><?php echo $PHP_AUTH_USER ?></div>
                            <div class="user-role">Administrator</div>
                        </div>
                    </div>
                    <div class="header-actions">
                        <button class="header-btn">
                            <i class="material-icons">notifications</i>
                            <span class="notification-badge"></span>
                        </button>
                        <button class="header-btn">
                            <i class="material-icons">settings</i>
                        </button>
                        <button class="header-btn">
                            <i class="material-icons">logout</i>
                        </button>
                    </div>
                </div>
            </header>
            
            <!-- Content Area -->
            <main class="content">
                <h1 class="page-title">ViciDial Admin</h1>
                <p class="page-subtitle"><?php echo date("l F j, Y G:i:s A") ?></p>
                
                <!-- Main content will be inserted here -->
                <div class="card">
                    <h3 class="card-title">Welcome to ViciDial Admin Interface</h3>
                    <p>This modern interface provides easy access to all ViciDial administration functions. Use the navigation menu on the left to access different sections.</p>
                </div>
            </main>
        </div>
    </div>
    
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <script>
        // Toggle sidebar on mobile
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        });
        
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });
        
        // Toggle sub-menus
        function toggleSubMenu(element) {
            // Only toggle on desktop, not on mobile
            if (window.innerWidth > 768) {
                const nextElement = element.nextElementSibling;
                if (nextElement && nextElement.classList.contains('sub-menu')) {
                    event.preventDefault();
                    nextElement.classList.toggle('show');
                    
                    // Toggle expand/collapse icon
                    const icon = element.querySelector('.material-icons:last-child');
                    if (icon) {
                        if (nextElement.classList.contains('show')) {
                            icon.textContent = 'expand_less';
                        } else {
                            icon.textContent = 'expand_more';
                        }
                    }
                }
            }
        }
        
        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            }
        });
    </script>
</body>
</html>