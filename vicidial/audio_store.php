<?php
# audio_store.php
# 
# Copyright (C) 2022  Matt Florell <vicidial@gmail.com>    LICENSE: AGPLv2




 $version = '2.14-27';
 $build = '220222-2348';

 $MT[0]='';

require("dbconnect_mysqli.php");
require("functions.php");
require ('classAudioFile.php');

 $audio_store_GSM_allowed=0;
if (file_exists('options.php'))
    {
    require('options.php');
    }

 $server_name = getenv("SERVER_NAME");
 $PHP_SELF=$_SERVER['PHP_SELF'];
 $PHP_SELF = preg_replace('/\.php.*/i','.php',$PHP_SELF);
 $audiofile=$_FILES["audiofile"];
    $AF_orig = $_FILES['audiofile']['name'];
    $AF_path = $_FILES['audiofile']['tmp_name'];
if (isset($_GET["submit_file"]))			{$submit_file=$_GET["submit_file"];}
    elseif (isset($_POST["submit_file"]))	{$submit_file=$_POST["submit_file"];}
if (isset($_GET["delete_file"]))			{$delete_file=$_GET["delete_file"];}
    elseif (isset($_POST["delete_file"]))	{$delete_file=$_POST["delete_file"];}
if (isset($_GET["DB"]))						{$DB=$_GET["DB"];}
    elseif (isset($_POST["DB"]))			{$DB=$_POST["DB"];}
if (isset($_GET["overwrite"]))				{$overwrite=$_GET["overwrite"];}
    elseif (isset($_POST["overwrite"]))		{$overwrite=$_POST["overwrite"];}
if (isset($_GET["action"]))					{$action=$_GET["action"];}
    elseif (isset($_POST["action"]))		{$action=$_POST["action"];}
if (isset($_GET["audio_server_ip"]))			{$audio_server_ip=$_GET["audio_server_ip"];}
    elseif (isset($_POST["audio_server_ip"]))	{$audio_server_ip=$_POST["audio_server_ip"];}
if (isset($_GET["SUBMIT"]))					{$SUBMIT=$_GET["SUBMIT"];}
    elseif (isset($_POST["SUBMIT"]))		{$SUBMIT=$_POST["SUBMIT"];}
if (isset($_GET["audiofile_name"]))				{$audiofile_name=$_GET["audiofile_name"];}
    elseif (isset($_POST["audiofile_name"]))	{$audiofile_name=$_POST["audiofile_name"];}
if (isset($_GET["master_audiofile"]))			{$master_audiofile=$_GET["master_audiofile"];}
    elseif (isset($_POST["master_audiofile"]))	{$master_audiofile=$_POST["master_audiofile"];}
if (isset($_GET["new_audiofile"]))			{$new_audiofile=$_GET["new_audiofile"];}
    elseif (isset($_POST["new_audiofile"]))	{$new_audiofile=$_POST["new_audiofile"];}
if (isset($_FILES["audiofile"]))			{$audiofile_name=$_FILES["audiofile"]['name'];}
if (isset($_GET["lead_file"]))				{$lead_file=$_GET["lead_file"];}
    elseif (isset($_POST["lead_file"]))		{$lead_file=$_POST["lead_file"];}
if (isset($_GET["force_allow"]))			{$force_allow=$_GET["force_allow"];}
    elseif (isset($_POST["force_allow"]))	{$force_allow=$_POST["force_allow"];}

 $DB=preg_replace("/[^0-9a-zA-Z]/","",$DB);

header ("Content-type: text/html; charset=utf-8");
header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0

#############################################
##### START SYSTEM_SETTINGS LOOKUP #####
 $stmt = "SELECT use_non_latin,sounds_central_control_active,sounds_web_server,sounds_web_directory,outbound_autodial_active,enable_languages,language_method,active_modules,contacts_enabled,allow_emails,qc_features_active,allow_web_debug FROM system_settings;";
 $rslt=mysql_to_mysqli($stmt, $link);
#if ($DB) {echo "$stmt\n";}
 $ss_conf_ct = mysqli_num_rows($rslt);
if ($ss_conf_ct > 0)
    {
    $row=mysqli_fetch_row($rslt);
    $non_latin =						$row[0];
    $sounds_central_control_active =	$row[1];
    $sounds_web_server =				$row[2];
    $sounds_web_directory =				$row[3];
    $SSoutbound_autodial_active =		$row[4];
    $SSenable_languages =				$row[5];
    $SSlanguage_method =				$row[6];
    $SSactive_modules =					$row[7];
    $SScontacts_enabled =				$row[8];
    $SSemail_enabled =					$row[9];
    $SSqc_features_active =				$row[10];
    $SSallow_web_debug =				$row[11];
    }
if ($SSallow_web_debug < 1) {$DB=0;}
##### END SETTINGS LOOKUP #####
###########################################

 $action = preg_replace('/[^-_0-9a-zA-Z]/','',$action);
 $SUBMIT = preg_replace('/[^-_0-9a-zA-Z]/','',$SUBMIT);
 $overwrite = preg_replace('/[^-_0-9a-zA-Z]/','',$overwrite);
 $force_allow = preg_replace('/[^-_0-9a-zA-Z]/','',$force_allow);

# Variables filter further down in the code
#	$audiofile_name

if ($non_latin < 1)
    {
    $delete_file = preg_replace('/[^-\._0-9a-zA-Z]/','',$delete_file);
    $submit_file = preg_replace('/[^-\._0-9a-zA-Z]/','',$submit_file);
    $master_audiofile = preg_replace('/[^-\._0-9a-zA-Z]/','',$master_audiofile);
    $new_audiofile = preg_replace('/[^-\._0-9a-zA-Z]/','',$new_audiofile);
    $lead_file = preg_replace('/[^-\._0-9a-zA-Z]/','',$lead_file);
    $audio_server_ip = preg_replace('/[^-\.\:\_0-9a-zA-Z]/','',$audio_server_ip);
    }
else
    {
    $delete_file = preg_replace('/[^-\._0-9\p{L}]/u','',$delete_file);
    $submit_file = preg_replace('/[^-\._0-9\p{L}]/u','',$submit_file);
    $master_audiofile = preg_replace('/[^-\._0-9\p{L}]/u','',$master_audiofile);
    $new_audiofile = preg_replace('/[^-\._0-9\p{L}]/u','',$new_audiofile);
    $lead_file = preg_replace('/[^-\._0-9\p{L}]/u','',$lead_file);
    $audio_server_ip = preg_replace('/[^-\.\:\_0-9\p{L}]/u','',$audio_server_ip);
    }

### check if sounds server matches this server IP, if not then exit with an error
 $sounds_web_server = str_replace(array('http://','https://'), '', $sounds_web_server);
if ( ( ( (strlen($sounds_web_server)) != (strlen($server_name)) ) or (!preg_match("/$sounds_web_server/i",$server_name) ) ) and ($force_allow!='FORCED') )
    {
    echo _QXZ("ERROR").": "._QXZ("server")."($server_name) "._QXZ("does not match sounds web server ip")."($sounds_web_server)\n";
    exit;
    }

if (preg_match("/;|:|\/|\^|\[|\]|\"|\'|\*/",$AF_orig))
    {
    echo _QXZ("ERROR").": "._QXZ("Invalid File Name").": $AF_orig\n";
    exit;
    }

### check if web directory exists, if not generate one
if (strlen($sounds_web_directory) < 30)
    {
    $sounds_web_directory = '';
    $possible = "0123456789cdfghjkmnpqrstvwxyz";  
    $i = 0; 
    $length = 30;
    while ($i < $length) 
        { 
        $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
        $sounds_web_directory .= $char;
        $i++;
        }
    mkdir("$WeBServeRRooT/$sounds_web_directory");
    chmod("$WeBServeRRooT/$sounds_web_directory", 0766);
    if ($DB > 0) {echo "$WeBServeRRooT/$sounds_web_directory\n";}

    $stmt="UPDATE system_settings set sounds_web_directory='$sounds_web_directory';";
    $rslt=mysql_to_mysqli($stmt, $link);
    echo _QXZ("NOTICE").": "._QXZ("new web directory created")."\n";
    }

if (!file_exists("$WeBServeRRooT/$sounds_web_directory")) 
    {
    echo _QXZ("ERROR").": "._QXZ("audio store web directory does not exist").": $WeBServeRRooT/$sounds_web_directory\n";
    exit;
    }

### get list of all servers, if not one of them, then force authentication check
 $stmt = "SELECT server_ip FROM servers;";
 $rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {echo "$stmt\n";}
 $sv_conf_ct = mysqli_num_rows($rslt);
 $i=0;
 $server_ips ='|';
while ($sv_conf_ct > $i)
    {
    $row=mysqli_fetch_row($rslt);
    $server_ips .=	"$row[0]|";
    $i++;
    }

 $user_set=0;
 $formIPvalid=0;
if (strlen($audio_server_ip) > 6)
    {
    if (preg_match("/\|$audio_server_ip\|/", $server_ips))
        {$formIPvalid=1;}
    }
 $ip = getenv("REMOTE_ADDR");

if ( (!preg_match("/\|$ip\|/", $server_ips)) and ($formIPvalid < 1) )
    {
    $user_set=1;
    $PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
    $PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
    if ($non_latin < 1)
        {
        $PHP_AUTH_USER = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_USER);
        $PHP_AUTH_PW = preg_replace('/[^-_0-9a-zA-Z]/', '', $PHP_AUTH_PW);
        }
    else
        {
        $PHP_AUTH_USER = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_USER);
        $PHP_AUTH_PW = preg_replace('/[^-_0-9\p{L}]/u', '', $PHP_AUTH_PW);
        }

    $stmt="SELECT selected_language,qc_enabled from vicidial_users where user='$PHP_AUTH_USER';";
    if ($DB) {echo "|$stmt|\n";}
    $rslt=mysql_to_mysqli($stmt, $link);
    $sl_ct = mysqli_num_rows($rslt);
    if ($sl_ct > 0)
        {
        $row=mysqli_fetch_row($rslt);
        $VUselected_language =		$row[0];
        $qc_auth =					$row[1];
        }

    $auth=0;
    $reports_auth=0;
    $admin_auth=0;
    $auth_message = user_authorization($PHP_AUTH_USER,$PHP_AUTH_PW,'REPORTS',1,0);
    if ( ($auth_message == 'GOOD') or ($auth_message == '2FA') )
        {
        $auth=1;
        }

    if ($auth > 0)
        {
        $stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and user_level > 7 and (modify_audiostore='1');";
        if ($DB) {echo "|$stmt|\n";}
        $rslt=mysql_to_mysqli($stmt, $link);
        $row=mysqli_fetch_row($rslt);
        $admin_auth=$row[0];

        if ($admin_auth < 1)
            {
            $VDdisplayMESSAGE = _QXZ("You are not allowed to upload audio files");
            Header ("Content-type: text/html; charset=utf-8");
            echo "$VDdisplayMESSAGE: |$PHP_AUTH_USER|$auth_message|\n";
            exit;
            }
        }
    else
        {
        $VDdisplayMESSAGE = _QXZ("Login incorrect, please try again");
        if ($auth_message == 'LOCK')
            {
            $VDdisplayMESSAGE = _QXZ("Too many login attempts, try again in 15 minutes");
            Header ("Content-type: text/html; charset=utf-8");
            echo "$VDdisplayMESSAGE: |$PHP_AUTH_USER|$auth_message|\n";
            exit;
            }
    if ($auth_message == 'IPBLOCK')
        {
        $VDdisplayMESSAGE = _QXZ("Your IP Address is not allowed") . ": $ip";
        Header ("Content-type: text/html; charset=utf-8");
        echo "$VDdisplayMESSAGE: |$PHP_AUTH_USER|$auth_message|\n";
        exit;
        }
        Header("WWW-Authenticate: Basic realm=\"CONTACT-CENTER-ADMIN\"");
        Header("HTTP/1.0 401 Unauthorized");
        echo "$VDdisplayMESSAGE: |$PHP_AUTH_USER|$PHP_AUTH_PW|$auth_message|\n";
        exit;
        }

    $stmt="SELECT count(*) from vicidial_users where user='$PHP_AUTH_USER' and user_level > 8 and ( (ast_admin_access='1') and (modify_audiostore='1') )";
    if ($DB) {echo "|$stmt|\n";}
    $rslt=mysql_to_mysqli($stmt, $link);
    $row=mysqli_fetch_row($rslt);
    $auth_delete=$row[0];
    }

 $delete_message='';
### delete a file from audio store
if ( ($action == "DELETE") and ($auth_delete > 0) )
    {
    if (strlen($delete_file) > 0)
        {
        $gsm='.gsm';
        $wav='.wav';
        unlink("$WeBServeRRooT/$sounds_web_directory/$delete_file$gsm");
        unlink("$WeBServeRRooT/$sounds_web_directory/$delete_file$wav");

        $stmt="UPDATE servers SET sounds_update='Y',audio_store_purge=CONCAT(audio_store_purge,\"$delete_file\\n\");";
        if ($DB) {echo "|$stmt|\n";}
        $rslt=mysql_to_mysqli($stmt, $link);

        $stmt="UPDATE system_settings SET audio_store_purge=CONCAT(audio_store_purge,\"$delete_file\\n\");";
        if ($DB) {echo "|$stmt|\n";}
        $rslt=mysql_to_mysqli($stmt, $link);

        $delete_message = _QXZ("AUDIO FILE SET FOR DELETION").": $delete_file\n";
        }
    }



### list all files in sounds web directory
if ($action == "LIST")
    {
    $i=0;
    $filename_sort=$MT;
    $dirpath = "$WeBServeRRooT/$sounds_web_directory";
    $dh = opendir($dirpath);
    while (false !== ($file = readdir($dh))) 
        {
        # Do not list subdirectories
        if ( (!is_dir("$dirpath/$file")) and (preg_match('/\.wav$|\.gsm$/', $file)) )
            {
            if (file_exists("$dirpath/$file")) 
                {
                $file_names[$i] = $file;
                $file_epoch[$i] = filemtime("$dirpath/$file");
                $file_dates[$i] = date ("Y-m-d H:i:s.", filemtime("$dirpath/$file"));
                $file_sizes[$i] = filesize("$dirpath/$file");
                $filename_sort[$i] = $file . "----------" . $i . "----------" . $file_sizes[$i];
                $i++;
                }
            }
        }
    closedir($dh);

    sort($filename_sort);

    sleep(1);

    $k=0;
    while($k < $i)
        {
        $filename_split = explode('----------',$filename_sort[$k]);
        $m = $filename_split[1];
        $size = $filename_split[2];
        $NOWsize = filesize("$dirpath/$file_names[$m]");
        if ($size == $NOWsize)
            {
            echo "$k\t$file_names[$m]\t$file_dates[$m]\t$file_sizes[$m]\t$file_epoch[$m]\n";
            }
        $k++;
        }
    exit;
    }


##### BEGIN go through all Audio Store WAV files and validate for asterisk compatibility #####
 $stmt = "SELECT count(*) FROM audio_store_details where audio_format='wav' and wav_asterisk_valid='';";
 $rslt=mysql_to_mysqli($stmt, $link);
if ($DB) {echo "$stmt\n";}
 $row=mysqli_fetch_row($rslt);
 $wuv_ct = $row[0];
if ( ($wuv_ct > 0) or ($action == "ALL_WAV_VALIDATION") )
    {
    if ($DB) {echo "Starting WAV file validation process...\n";}
    $i=0;
    $filename_sort=$MT;
    $dirpath = "$WeBServeRRooT/$sounds_web_directory";
    $dh = opendir($dirpath);
    while (false !== ($file = readdir($dh))) 
        {
        # Do not list subdirectories
        if ( (!is_dir("$dirpath/$file")) and (preg_match('/\.wav$|\.gsm$/', $file)) )
            {
            if (file_exists("$dirpath/$file")) 
                {
                $file_names[$i] = $file;
                $file_epoch[$i] = filemtime("$dirpath/$file");
                $file_dates[$i] = date ("Y-m-d H:i:s.", filemtime("$dirpath/$file"));
                $file_sizes[$i] = filesize("$dirpath/$file");
                $filename_sort[$i] = $file . "----------" . $i . "----------" . $file_sizes[$i];
                $i++;
                }
            }
        }
    closedir($dh);

    sort($filename_sort);

    sleep(1);

    $wav_valid=0;
    $wav_invalid=0;
    $not_wav=0;
    $k=0;
    while($k < $i)
        {
        $filename_split = explode('----------',$filename_sort[$k]);
        $m = $filename_split[1];
        $size = $filename_split[2];
        $audio_filesize = filesize("$dirpath/$file_names[$m]");
        if ($size == $audio_filesize)
            {
            $audio_filename = $file_names[$m];
            $audio_epoch = date("U");
            $wav_format_details='';
            $wav_asterisk_valid='NA';

            if (preg_match("/\.wav$/", $audio_filename))
                {
                $audio_format='wav';
                $audio_length = ($audio_filesize / 16000);

                $AF = new AudioFile;
                $AF->loadFile("$dirpath/$file_names[$m]");

                $wav_type = $AF->wave_type;
                $wav_compression = $AF->getCompression ($AF->wave_compression);
                $wav_channels = $AF->wave_channels;
                $wav_framerate = $AF->wave_framerate;
                $wav_bits=$AF->wave_bits;
                $audio_length=number_format ($AF->wave_length,"0");
                $invalid_wav=0;

                if (!preg_match('/^wav/i',$wav_type)) {$invalid_wav++;}
                if (!preg_match('/^pcm/i',$wav_compression)) {$invalid_wav++;}
                if ($wav_channels > 1) {$invalid_wav++;}
                if ( ($wav_framerate > 8000) or ($wav_framerate < 8000) ) {$invalid_wav++;}
                if ( ($wav_bits > 16) or ($wav_bits < 16) ) {$invalid_wav++;}

                $wav_format_details = "$wav_type   channels: $wav_channels   framerate: $wav_framerate   bits: $wav_bits   length: $audio_length   compression: $wav_compression";

                if ($invalid_wav > 0)
                    {
                    $wav_asterisk_valid='BAD';
                    $wav_invalid++;
                    }
                else
                    {
                    $wav_asterisk_valid='GOOD';
                    $wav_valid++;
                    }

                $stmt="INSERT IGNORE INTO audio_store_details SET audio_filename='$audio_filename',audio_format='$audio_format',audio_filesize='$audio_filesize',audio_epoch='$audio_epoch',audio_length='$audio_length',wav_format_details='$wav_format_details',wav_asterisk_valid='$wav_asterisk_valid' ON DUPLICATE KEY UPDATE audio_filesize='$audio_filesize',audio_epoch='$audio_epoch',audio_length='$audio_length',wav_format_details='$wav_format_details',wav_asterisk_valid='$wav_asterisk_valid';";
                if ($DB) {echo "|$stmt|\n";}
                $rslt=mysql_to_mysqli($stmt, $link);
                $affected_rowsX = mysqli_affected_rows($link);
                }
            else
                {
                $not_wav++;
                }

            if ($DB) {echo "$k\t$file_names[$m]\t$wav_asterisk_valid\n";}
            }
        $k++;
        }
    
    if ($DB) {echo "SUMMARY: WAV VALID: $wav_valid   WAV INVALID: $wav_invalid   NOT WAV: $not_wav\n";}
    }
##### END go through all Audio Store WAV files and validate for asterisk compatibility #####



### upload audio file from server to webserver
# curl 'http://10.0.0.4/vicidial/audio_store.php?action=AUTOUPLOAD' -F "audiofile=@/var/lib/asterisk/sounds/beep.gsm"
if ($action == "AUTOUPLOAD")
    {
    if ($audiofile)
        {
        $AF_path = preg_replace("/ /",'\ ',$AF_path);
        $AF_path = preg_replace("/@/",'\@',$AF_path);
        $AF_path = preg_replace("/\(/",'\(',$AF_path);
        $AF_path = preg_replace("/\)/",'\)',$AF_path);
        $AF_path = preg_replace("/\#/",'\#',$AF_path);
        $AF_path = preg_replace("/\&/",'\&',$AF_path);
        $AF_path = preg_replace("/\*/",'\*',$AF_path);
        $AF_path = preg_replace("/\!/",'\!',$AF_path);
        $AF_path = preg_replace("/\%/",'\%',$AF_path);
        $AF_path = preg_replace("/\^/",'\^',$AF_path);
        $audiofile_name = preg_replace("/ /",'',$audiofile_name);
        $audiofile_name = preg_replace("/@/",'',$audiofile_name);
        $audiofile_name = preg_replace("/\(/",'',$audiofile_name);
        $audiofile_name = preg_replace("/\)/",'',$audiofile_name);
        $audiofile_name = preg_replace("/\#/",'',$audiofile_name);
        $audiofile_name = preg_replace("/\&/",'',$audiofile_name);
        $audiofile_name = preg_replace("/\*/",'',$audiofile_name);
        $audiofile_name = preg_replace("/\!/",'',$audiofile_name);
        $audiofile_name = preg_replace("/\%/",'',$audiofile_name);
        $audiofile_name = preg_replace("/\^/",'',$audiofile_name);
        if (preg_match("/\.wav$|\.gsm$/", $audiofile_name))
            {
            copy($AF_path, "$WeBServeRRooT/$sounds_web_directory/$audiofile_name");
            chmod("$WeBServeRRooT/$sounds_web_directory/$audiofile_name", 0766);

            echo _QXZ("SUCCESS").": $audiofile_name "._QXZ("uploaded")."     "._QXZ("size").":" . filesize("$WeBServeRRooT/$sounds_web_directory/$audiofile_name") . "\n";
            exit;
            }
        else
            {
            echo _QXZ("ERROR").": "._QXZ("only wav and gsm files are allowed in audio store")."\n";
            }
        }
    else
        {
        echo _QXZ("ERROR").": "._QXZ("no file uploaded")."\n";
        }
    exit;
    }


### copy audio file to new name on webserver
if ($action == "COPYFILE")
    {
    if ($DB) {echo "COPYFILE: |$new_audiofile|$master_audiofile|\n";}
    if ( (strlen($new_audiofile)>0) and (strlen($master_audiofile)>0) )
        {
        $master_audiofile = preg_replace("/ /",'\ ',$master_audiofile);
        $master_audiofile = preg_replace("/@/",'\@',$master_audiofile);
        $master_audiofile = preg_replace("/\(/",'\(',$master_audiofile);
        $master_audiofile = preg_replace("/\)/",'\)',$master_audiofile);
        $master_audiofile = preg_replace("/\#/",'\#',$master_audiofile);
        $master_audiofile = preg_replace("/\&/",'\&',$master_audiofile);
        $master_audiofile = preg_replace("/\*/",'\*',$master_audiofile);
        $master_audiofile = preg_replace("/\!/",'\!',$master_audiofile);
        $master_audiofile = preg_replace("/\%/",'\%',$master_audiofile);
        $master_audiofile = preg_replace("/\^/",'\^',$master_audiofile);
        $master_audiofile = preg_replace("/\"/",'\^',$master_audiofile);
        $new_audiofile = preg_replace("/ /",'',$new_audiofile);
        $new_audiofile = preg_replace("/@/",'',$new_audiofile);
        $new_audiofile = preg_replace("/\(/",'',$new_audiofile);
        $new_audiofile = preg_replace("/\)/",'',$new_audiofile);
        $new_audiofile = preg_replace("/\#/",'',$new_audiofile);
        $new_audiofile = preg_replace("/\&/",'',$new_audiofile);
        $new_audiofile = preg_replace("/\*/",'',$new_audiofile);
        $new_audiofile = preg_replace("/\!/",'',$new_audiofile);
        $new_audiofile = preg_replace("/\%/",'',$new_audiofile);
        $new_audiofile = preg_replace("/\^/",'',$new_audiofile);
        $new_audiofile = preg_replace("/\"/",'',$new_audiofile);

        $copied=0;
        $suffix='.wav';
        if (file_exists("$WeBServeRRooT/$sounds_web_directory/$master_audiofile$suffix"))
            {
            copy("$WeBServeRRooT/$sounds_web_directory/$master_audiofile$suffix", "$WeBServeRRooT/$sounds_web_directory/$new_audiofile$suffix");
            chmod("$WeBServeRRooT/$sounds_web_directory/$new_audiofile$suffix", 0766);

            $new_filesize = filesize("$WeBServeRRooT/$sounds_web_directory/$new_audiofile$suffix");
            $copy_message = _QXZ("SUCCESS").": $new_audiofile$suffix "._QXZ("copied")."     "._QXZ("size").": $new_filesize "._QXZ("from")." $master_audiofile$suffix\n";
            $copied++;
            }

        $suffix='.gsm';
        if (file_exists("$WeBServeRRooT/$sounds_web_directory/$master_audiofile$suffix"))
            {
            copy("$WeBServeRRooT/$sounds_web_directory/$master_audiofile$suffix", "$WeBServeRRooT/$sounds_web_directory/$new_audiofile$suffix");
            chmod("$WeBServeRRooT/$sounds_web_directory/$new_audiofile$suffix", 0766);

            $new_filesize = filesize("$WeBServeRRooT/$sounds_web_directory/$new_audiofile$suffix");
            $copy_message = _QXZ("SUCCESS").": $new_audiofile$suffix "._QXZ("copied")."     "._QXZ("size").": $new_filesize "._QXZ("from")." $master_audiofile$suffix\n";
            $copied++;
            }

        if ($copied < 1)
            {
            $copy_message = _QXZ("ERROR").": "._QXZ("original file not found").": |$master_audiofile|\n";
            }
        else
            {
            $stmt="UPDATE servers SET sounds_update='Y';";
            $rslt=mysql_to_mysqli($stmt, $link);

            ### LOG INSERTION Admin Log Table ###
            $SQL_log = "$stmt|";
            $SQL_log = preg_replace('/;/', '', $SQL_log);
            $SQL_log = addslashes($SQL_log);
            $stmt="INSERT INTO vicidial_admin_log set event_date=NOW(), user='$PHP_AUTH_USER', ip_address='$ip', event_section='AUDIOSTORE', event_type='COPY', record_id='manualupload', event_code='$new_audiofile $new_filesize', event_sql=\"$SQL_log\", event_notes='NEW: $new_audiofile   ORIGINAL: $master_audiofile';";
            if ($DB) {echo "|$stmt|\n";}
            $rslt=mysql_to_mysqli($stmt, $link);
            }
        }
    else
        {
        $copy_message = _QXZ("ERROR").": "._QXZ("you must define an original and new filename").": |$master_audiofile|$new_audiofile|\n";
        }
    }



?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- VERSION: <?php echo $version ?>     BUILD: <?php echo $build ?> -->
<title><?php echo _QXZ("ADMINISTRATION"); ?>: <?php echo _QXZ("Audio Store"); ?></title>
<style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    line-height: 1.6;
    color: #333;
    background-color: #f5f7fa;
    padding: 20px;
}

.container {
    max-width: 100%;
    margin: 0 auto;
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.header {
    background: white;
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    animation: slideDown 0.5s ease-out;
}

.header-content {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.header-icon {
    width: 60px;
    height: 60px;
    margin-right: 20px;
    filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
}

.header-title h1 {
    color: #2d3748;
    font-size: 32px;
    font-weight: 700;
    margin: 0;
}

.header-title p {
    color: #718096;
    font-size: 18px;
    margin-top: 5px;
}

.header-info {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 20px;
    border-radius: 8px;
    font-weight: 600;
    text-align: center;
    font-size: 18px;
}

.card {
    background: white;
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    cursor: pointer;
}

.card-title {
    color: #2d3748;
    font-size: 24px;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.card-title-icon {
    margin-right: 10px;
    font-size: 28px;
}

.toggle-icon {
    color: #667eea;
    font-size: 24px;
    transition: transform 0.3s;
}

.card-content {
    overflow-x: auto;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    color: #4a5568;
    font-weight: 600;
    margin-bottom: 10px;
    font-size: 15px;
}

.form-input {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 15px;
    transition: all 0.3s;
}

.form-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    outline: none;
}

.form-select {
    width: 100%;
    padding: 14px 18px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 15px;
    background: white;
    cursor: pointer;
    transition: all 0.3s;
}

.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    outline: none;
}

.btn {
    display: inline-block;
    padding: 14px 30px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    text-align: center;
    text-decoration: none;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.btn-danger {
    background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
    color: white;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.btn-success {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
}

.form-grid-2 {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 25px;
}

.form-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
}

.form-actions {
    display: flex;
    justify-content: center;
    margin-top: 30px;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-success {
    background: #f0fff4;
    color: #22543d;
    border-left: 4px solid #48bb78;
}

.alert-error {
    background: #fff5f5;
    color: #742a2a;
    border-left: 4px solid #f56565;
}

.alert-warning {
    background: #fffbf0;
    color: #7c2d12;
    border-left: 4px solid #ed8936;
}

.help-text {
    font-size: 14px;
    color: #718096;
    margin-top: 5px;
}

.version-info {
    text-align: right;
    font-size: 14px;
    color: #718096;
    margin-top: 20px;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .form-grid-2, .form-grid-3 {
        grid-template-columns: 1fr;
    }
    
    .header-content {
        flex-direction: column;
        text-align: center;
    }
    
    .header-icon {
        margin-right: 0;
        margin-bottom: 15px;
    }
}
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="header-content">
            <img src="images/icon_audiostore.png" alt="Audio Store" class="header-icon">
            <div class="header-title">
                <h1><?php echo _QXZ("Audio Store Management"); ?></h1>
                <p><?php echo _QXZ("Upload, copy, and manage audio files"); ?></p>
            </div>
        </div>
        <div class="header-info">
            <?php echo _QXZ("Audio Store"); ?> - <?php echo _QXZ("VERSION"); ?>: <?php echo $version ?> - <?php echo _QXZ("BUILD"); ?>: <?php echo $build ?>
        </div>
    </div>

    <?php
    if ($user_set < 1)
        {
        $PHP_AUTH_USER=$_SERVER['PHP_AUTH_USER'];
        $PHP_AUTH_PW=$_SERVER['PHP_AUTH_PW'];
        $PHP_AUTH_USER = preg_replace('/[^-_0-9a-zA-Z]/','',$PHP_AUTH_USER);
        $PHP_AUTH_PW = preg_replace('/[^-_0-9a-zA-Z]/','',$PHP_AUTH_PW);
        }
    ##### BEGIN Set variables to make header show properly #####
    $ADD =					'311111111111111';
    $hh =					'admin';
    $LOGast_admin_access =	'1';
    $ADMIN =				'admin.php';
    $page_width='100%';
    $section_width='100%';
    $header_font_size='3';
    $subheader_font_size='2';
    $subcamp_font_size='2';
    $header_selected_bold='<b>';
    $header_nonselected_bold='';
    $admin_color =		'#FFFF99';
    $admin_font =		'BLACK';
    $admin_color =		'#E6E6E6';
    $subcamp_color =	'#C6C6C6';
    ##### END Set variables to make header show properly #####

    require("admin_header.php");

    $STARTtime = date("U");
    $TODAY = date("Y-m-d");
    $NOW_TIME = date("Y-m-d H:i:s");
    $FILE_datetime = $STARTtime;

    $date = date("r");
    $browser = getenv("HTTP_USER_AGENT");
    $script_name = getenv("SCRIPT_NAME");
    $server_name = getenv("SERVER_NAME");
    $server_port = getenv("SERVER_PORT");
    if (preg_match("/443/i",$server_port)) {$HTTPprotocol = 'https://';}
      else {$HTTPprotocol = 'http://';}
    $admDIR = "$HTTPprotocol$server_name:$server_port$script_name";
    $admDIR = preg_replace('/audio_store\.php/i', '',$admDIR);
    $admSCR = 'admin.php';
    # $NWB = " &nbsp; <a href=\"javascript:openNewWindow('help.php?ADD=99999";
    # $NWE = "')\"><IMG SRC=\"help.png\" WIDTH=20 HEIGHT=20 BORDER=0 ALT=\"HELP\" ALIGN=TOP></A>";

    $NWB = "<IMG SRC=\"help.png\" onClick=\"FillAndShowHelpDiv(event, '";
    $NWE = "')\" WIDTH=20 HEIGHT=20 BORDER=0 ALT=\"HELP\" ALIGN=TOP>";

    $secX = date("U");
    $pulldate0 = "$year-$mon-$mday $hour:$min:$sec";

    // Display messages
    if (!empty($delete_message)) {
        echo "<div class='alert alert-success'>$delete_message</div>";
    }
    
    if (!empty($copy_message)) {
        echo "<div class='alert alert-success'>$copy_message</div>";
    }

    if ($action == "MANUALUPLOAD")
        {
        if ($audiofile) 
            {
            $AF_path = preg_replace("/ /",'\ ',$AF_path);
            $AF_path = preg_replace("/@/",'\@',$AF_path);
            $AF_path = preg_replace("/\(/",'\(',$AF_path);
            $AF_path = preg_replace("/\)/",'\)',$AF_path);
            $AF_path = preg_replace("/\#/",'\#',$AF_path);
            $AF_path = preg_replace("/\&/",'\&',$AF_path);
            $AF_path = preg_replace("/\*/",'\*',$AF_path);
            $AF_path = preg_replace("/\!/",'\!',$AF_path);
            $AF_path = preg_replace("/\%/",'\%',$AF_path);
            $AF_path = preg_replace("/\^/",'\^',$AF_path);
            $audiofile_name = preg_replace("/ /",'',$audiofile_name);
            $audiofile_name = preg_replace("/@/",'',$audiofile_name);
            $audiofile_name = preg_replace("/\(/",'',$audiofile_name);
            $audiofile_name = preg_replace("/\)/",'',$audiofile_name);
            $audiofile_name = preg_replace("/\#/",'',$audiofile_name);
            $audiofile_name = preg_replace("/\&/",'',$audiofile_name);
            $audiofile_name = preg_replace("/\*/",'',$audiofile_name);
            $audiofile_name = preg_replace("/\!/",'',$audiofile_name);
            $audiofile_name = preg_replace("/\%/",'',$audiofile_name);
            $audiofile_name = preg_replace("/\^/",'',$audiofile_name);
            if ( (preg_match("/\.wav$/", $audiofile_name)) or ( (preg_match("/\.gsm$/", $audiofile_name)) and ($audio_store_GSM_allowed > 0) ) )
                {
                copy($AF_path, "$WeBServeRRooT/$sounds_web_directory/$audiofile_name");
                chmod("$WeBServeRRooT/$sounds_web_directory/$audiofile_name", 0766);
                
                $audio_epoch = date("U");
                $audio_format='gsm';
                $audio_filesize = filesize("$WeBServeRRooT/$sounds_web_directory/$audiofile_name");
                $audio_length = ($audio_filesize / 1650);
                $wav_format_details='';
                $wav_asterisk_valid='NA';
                
                echo "<div class='alert alert-success'>"._QXZ("SUCCESS").": $audiofile_name "._QXZ("uploaded")."     "._QXZ("size").": $audio_filesize</div>";

                if (preg_match("/\.wav$/", $audiofile_name))
                    {
                    $audio_format='wav';
                    $audio_length = ($audio_filesize / 16000);

                    $AF = new AudioFile;
                    $AF->loadFile("$WeBServeRRooT/$sounds_web_directory/$audiofile_name");

                    $wav_type = $AF->wave_type;
                    $wav_compression = $AF->getCompression ($AF->wave_compression);
                    $wav_channels = $AF->wave_channels;
                    $wav_framerate = $AF->wave_framerate;
                    $wav_bits=$AF->wave_bits;
                    $audio_length=number_format ($AF->wave_length,"0");
                    $invalid_wav=0;

                    if (!preg_match('/^wav/i',$wav_type)) {$invalid_wav++;}
                    if (!preg_match('/^pcm/i',$wav_compression)) {$invalid_wav++;}
                    if ($wav_channels > 1) {$invalid_wav++;}
                    if ( ($wav_framerate > 8000) or ($wav_framerate < 8000) ) {$invalid_wav++;}
                    if ( ($wav_bits > 16) or ($wav_bits < 16) ) {$invalid_wav++;}

                    $wav_format_details = "$wav_type   channels: $wav_channels   framerate: $wav_framerate   bits: $wav_bits   length: $audio_length   compression: $wav_compression";

                    if ($invalid_wav > 0)
                        {
                        $wav_asterisk_valid='BAD';
                        echo "<div class='alert alert-error'>"._QXZ("INVALID WAV FILE FORMAT").": ($audiofile_name)<br>"._QXZ("channels").": $wav_channels &nbsp; "._QXZ("framerate").": $wav_framerate &nbsp; "._QXZ("bits").": $wav_bits &nbsp; "._QXZ("compression").": $wav_compression</div>";
                        }
                    else
                        {
                        $wav_asterisk_valid='GOOD';
                        echo "<div class='alert alert-success'>"._QXZ("WAV FILE FORMAT VALIDATED").": $wav_type &nbsp; "._QXZ("channels").": $wav_channels &nbsp; "._QXZ("framerate").": $wav_framerate &nbsp; "._QXZ("bits").": $wav_bits &nbsp; "._QXZ("compression").": $wav_compression</div>";
                        }
                    }

                $stmt="INSERT IGNORE INTO audio_store_details SET audio_filename='$audiofile_name',audio_format='$audio_format',audio_filesize='$audio_filesize',audio_epoch='$audio_epoch',audio_length='$audio_length',wav_format_details='$wav_format_details',wav_asterisk_valid='$wav_asterisk_valid' ON DUPLICATE KEY UPDATE audio_filesize='$audio_filesize',audio_epoch='$audio_epoch',audio_length='$audio_length',wav_format_details='$wav_format_details',wav_asterisk_valid='$wav_asterisk_valid';";
                if ($DB) {echo "|$stmt|\n";}
                $rslt=mysql_to_mysqli($stmt, $link);
                $affected_rowsX = mysqli_affected_rows($link);

                $stmt="UPDATE servers SET sounds_update='Y';";
                $rslt=mysql_to_mysqli($stmt, $link);
                $affected_rowsY = mysqli_affected_rows($link);

                ### LOG INSERTION Admin Log Table ###
                $SQL_log = "$stmt|";
                $SQL_log = preg_replace('/;/', '', $SQL_log);
                $SQL_log = addslashes($SQL_log);
                $stmt="INSERT INTO vicidial_admin_log set event_date=NOW(), user='$PHP_AUTH_USER', ip_address='$ip', event_section='AUDIOSTORE', event_type='LOAD', record_id='manualupload', event_code='$audiofile_name $audio_filesize', event_sql=\"$SQL_log\", event_notes='$audiofile_name $AF_path $AF_orig   Invalid: $invalid_wav $wav_type &nbsp; "._QXZ("channels").": $wav_channels &nbsp; "._QXZ("framerate").": $wav_framerate &nbsp; "._QXZ("bits").": $wav_bits &nbsp; "._QXZ("compression").": $wav_compression   "._QXZ("length").": $audio_length   $affected_rowsX|$affected_rowsY';";
                if ($DB) {echo "|$stmt|\n";}
                $rslt=mysql_to_mysqli($stmt, $link);
                }
            else
                {
                if ( (preg_match("/\.gsm$/", $audiofile_name)) and ($audio_store_GSM_allowed < 1) )
                    {echo "<div class='alert alert-error'>"._QXZ("ERROR").": "._QXZ("only wav files are allowed in audio store")."</div>";}
                else
                    {echo "<div class='alert alert-error'>"._QXZ("ERROR").": "._QXZ("only wav and gsm files are allowed in audio store")."</div>";}
                }
            }
        else
            {
            echo "<div class='alert alert-error'>"._QXZ("ERROR").": "._QXZ("no file uploaded")."</div>";
            }
        }
    ?>

    <!-- Upload Audio File Card -->
    <div class="card">
        <div class="card-header" onclick="toggleSection('uploadSection')">
            <h2 class="card-title">
                <span class="card-title-icon">📤</span>
                <?php echo _QXZ("Upload Audio File"); ?>
            </h2>
            <span id="uploadSectionToggle" class="toggle-icon">▼</span>
        </div>
        <div id="uploadSection" class="card-content">
            <form action="<?php echo $PHP_SELF ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="MANUALUPLOAD">
                <input type="hidden" name="DB" value="<?php echo $DB; ?>">
                <input type="hidden" name="force_allow" value="<?php echo $force_allow; ?>">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="audiofile"><?php echo _QXZ("Audio File to Upload"); ?>:</label>
                        <input type="file" name="audiofile" id="audiofile" class="form-input">
                        <div class="help-text"><?php echo "$NWB#audio_store$NWE"; ?></div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" name="submit" class="btn btn-primary"><?php echo _QXZ("submit"); ?></button>
                </div>
            </form>
            
            <div class="alert alert-warning">
                <strong><?php echo _QXZ("We STRONGLY recommend uploading only 16bit Mono 8k PCM WAV audio files"); ?>(.wav)</strong>
                <br><br>
                <div class="help-text"><?php echo _QXZ("All spaces will be stripped from uploaded audio file names"); ?></div>
            </div>
            
            <div class="form-actions">
                <a href="javascript:launch_chooser('master_audiofile','date');" class="btn btn-success"><?php echo _QXZ("audio file list"); ?></a>
            </div>
        </div>
    </div>

    <!-- Copy Audio File Card -->
    <div class="card">
        <div class="card-header" onclick="toggleSection('copySection')">
            <h2 class="card-title">
                <span class="card-title-icon">📋</span>
                <?php echo _QXZ("Copy Audio File"); ?>
            </h2>
            <span id="copySectionToggle" class="toggle-icon">▼</span>
        </div>
        <div id="copySection" class="card-content">
            <form action="<?php echo $PHP_SELF ?>" method="post">
                <input type="hidden" name="action" value="COPYFILE">
                <input type="hidden" name="DB" value="<?php echo $DB; ?>">
                <input type="hidden" name="force_allow" value="<?php echo $force_allow; ?>">
                
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label" for="master_audiofile"><?php echo _QXZ("Original file"); ?>:</label>
                        <input type="text" size="50" maxlength="100" name="master_audiofile" id="master_audiofile" class="form-input">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="new_audiofile"><?php echo _QXZ("New file"); ?>:</label>
                        <input type="text" size="50" maxlength="100" name="new_audiofile" id="new_audiofile" class="form-input">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" name="submit" class="btn btn-primary"><?php echo _QXZ("submit"); ?></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Audio File Card -->
    <div class="card">
        <div class="card-header" onclick="toggleSection('deleteSection')">
            <h2 class="card-title">
                <span class="card-title-icon">🗑️</span>
                <?php echo _QXZ("Delete Audio File"); ?>
            </h2>
            <span id="deleteSectionToggle" class="toggle-icon">▼</span>
        </div>
        <div id="deleteSection" class="card-content">
            <?php
            if ($auth_delete > 0)
                {
                echo "<p>"._QXZ("File to Delete").": <a href=\"javascript:launch_chooser('delete_file','date');\">"._QXZ("select file")."</a></p>";
                echo "<form action=$PHP_SELF method=post>\n";
                echo "<input type=hidden name=action value=\"DELETE\">\n";
                echo "<input type=hidden name=DB value=\"$DB\">\n";
                echo "<input type=hidden name=force_allow value=\"$force_allow\">\n";
                echo "<div class='form-group'>";
                echo "<input type=text size=50 maxlength=100 name=delete_file id=delete_file class='form-input'>\n";
                echo "</div>";
                echo "<div class='form-actions'>";
                echo "<button type=submit name=submit class='btn btn-danger'>"._QXZ("submit")."</button>\n";
                echo "</div>";
                echo "</form>\n";
                }
            else
                {
                echo "<div class='alert alert-warning'>"._QXZ("You do not have permission to delete audio files")."</div>";
                }
            ?>
        </div>
    </div>

    <!-- Upload Log Link -->
    <div class="card">
        <div class="card-content">
            <div class="form-actions">
                <a href="admin.php?ADD=720000000000000&category=AUDIOSTORE&stage=manualupload" class="btn btn-primary">
                    <?php echo _QXZ("Click here to see a log of uploads to audio store"); ?>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSection(sectionId) {
    const section = document.getElementById(sectionId);
    const toggle = document.getElementById(sectionId + 'Toggle');
    
    if (section.style.display === 'none') {
        section.style.display = 'block';
        toggle.style.transform = 'rotate(0deg)';
    } else {
        section.style.display = 'none';
        toggle.style.transform = 'rotate(-90deg)';
    }
}

// Initialize animations
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.animation = `slideUp 0.5s ease-out ${index * 0.1}s both`;
    });
});
</script>
</body>
</html>