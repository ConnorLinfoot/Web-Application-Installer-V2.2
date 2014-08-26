<!-- ======================================= -->
<!-- =           EnkelHosting              = -->
<!-- =      Web App Install Script 2.2     = -->
<!-- =   http://devbox.enkelhosting.com/   = -->
<!-- ======================================= -->
<?php 
/*
Auto Web Application Download/Install Script!

Version 2.1
Copyright 2014 All Rights Reserved Enkel Hosting Ltd

More PHP Scrips at http://devbox.enkelhosting.com

*/

//		Options
$security = 'none'; //Security method

/*

Security Modes:

ip: Use a whitelist IP and only allow access to someone on that IP
password: User will be asked to enter the password chosen
none: No security will be used

*/


//		IP Whitelist Options
$ip = '000.000.000.000'; //Remote IP


//		Password Options
$password = 'Password123';

$debug = false;

if( $security == 'ip' ){
	if( $ip != $_SERVER['REMOTE_ADDR'] ){
		echo 'Your IP is not whitelisted!';
		exit;
	}
} else if( $security == 'password' && !isset($_GET['password'] ) ){
	echo 'Please type the password!';
	echo '<form>';
	echo '<input type="password" name="password">';
	echo '<input type="submit" value="Login">';
	echo '</form>';
	exit;
} else if( $security == 'password' && isset($_GET['password'] ) ){
	if( $password != $_GET['password'] ){
		echo 'Incorrect Password';
		exit;
	}
} else if( $security == 'none' ){
	
} else {
	echo 'Invalid Security Mode';
	exit;
}

if( !$debug ){ error_reporting(0); }

if( !$_POST ){
?>
<h2>Web Application Downloader/Installer Script</h2>
<p>Welcome to the Web Application script installer. (CS) Means coming soon!</p>
<form method="post">
	Web App To Install<select name="app">
    	<option value="">Please Select...</option>
        <option value="">--- CMS ---</option>
    	<option value="wordpress">Wordpress (Latest Version)</option>
    	<option value="wordpress4b4">Wordpress 4.0 Beta 4</option>
        <option value="joomla3">Joomla 3.2.3</option>
        <option value="joomla2">Joomla 2.5.19</option>
        <option value="drupal7">Drupal 7.26</option>
        <option value="eazycms">EazyCMS 0.3</option>
        <option value="">--- GAMING ---</option>
        <option value="multicraft">MultiCraft Panel (CS)</option>
        <option value="">--- BILLING ---</option>
        <option value="boxbilling">BoxBilling (Latest Version)</option>
        <option value="">--- FORUM ---</option>
        <option value="phpbb">phpBB 3.0.12</option>
    </select><br />
    Install Location (Leave blank for same as file) <input type="text" name="installDir" /> (No trailing slash)<br />
    <input type="submit" name="confirm" value="Install" />
</form>
<?php
exit;
} else if ( isset( $_POST['confirm']) && $_POST['confirm'] == 'Install' ){ 
	$app = $_POST['app'];
	$installDir = $_POST['installDir'];
	
	if( $installDir != '' ){
		$installDir .= '/';
	}
		
	if( !checkCMS($app) ){ echo 'Please choose a supported script!<br>'; exit; } else { echo '<strong>Script Verified.</strong><br>'; } //Checks what CMS is chosen and checks its supported by the script
	
	echo '<strong>Starting download of ' . $app . ',</strong> this may take a while...<br>'; //Starting download message
	
	if( !downloadCMS($app) ){ echo 'Sorry, the download failed!<br>'; exit; } else { echo '<strong>Download Completed.</strong><br>'; } //Attempts download of file
	
	echo '<strong>Starting extraction of ' . $app . ',</strong> this may take a while...<br>'; //Starting extraction message
	
	if( !extractCMS($app, $installDir) ){ echo 'Sorry, the extraction failed!<br>'; exit; } else { echo '<strong>Extraction Completed.</strong><br>'; } //Attempts download of file
	
	if( $app == 'wordpress' || $app == 'wordpress4b4' ){ //Only run if Wordpress
		echo '<strong>Starting file move of ' . $app . ',</strong> this may take a while...<br>'; //Starting move message
		
		if( !movefileswp($installDir) ){ echo 'Sorry, the move failed!<br>'; exit; } else { echo '<strong>Move Completed.</strong><br>'; } //Attempts move of files
	} else if( $app == 'drupal7' ){ //Only run if Drupal
		echo '<strong>Starting file move of ' . $app . ',</strong> this may take a while...<br>'; //Starting move message
		
		if( !movefilesdrupal($installDir) ){ echo 'Sorry, the move failed!<br>'; exit; } else { echo '<strong>Move Completed.</strong><br>'; } //Attempts move of files
	} else if( $app == 'phpbb' ){ //Only run if Drupal
		echo '<strong>Starting file move of ' . $app . ',</strong> this may take a while...<br>'; //Starting move message
		
		if( !movefilesphpbb($installDir) ){ echo 'Sorry, the move failed!<br>'; exit; } else { echo '<strong>Move Completed.</strong><br>'; } //Attempts move of files
	}
	echo '<strong>Install of ' . $app . ' has completed.</strong> Click <a href="' . $installDir . 'index.php">Here</a> to go to the install page. <strong>Remember to delete this file!</strong><br>'; //Completed message
	
	cleanUp($installDir); //Run Cleanup
}



//FUNCTIONS
function checkCMS($app){
	if( $app == 'wordpress' || $app == 'wordpress4b4' || $app == 'joomla2' || $app == 'joomla3' || $app == 'drupal7' || $app == 'eazycms' || $app == 'boxbilling' || $app == 'phpbb' ){
		return true;
	} else {
		return false;
	}
}

function downloadCMS($app){
	if( $app == 'wordpress' ){ $downloadFile = 'http://wordpress.org/latest.zip'; } else if( $app == 'wordpress4b4' ){ $downloadFile = 'http://wordpress.org/wordpress-4.0-beta4.zip'; } else if( $app == 'joomla2' ){ $downloadFile = 'http://joomlacode.org/gf/download/frsrelease/19238/158101/Joomla_2.5.19-Stable-Full_Package.zip'; } else if( $app == 'joomla3' ){ $downloadFile = 'http://joomlacode.org/gf/download/frsrelease/19239/158104/Joomla_3.2.3-Stable-Full_Package.zip'; } else if( $app == 'drupal7' ){ $downloadFile = 'http://ftp.drupal.org/files/projects/drupal-7.26.zip'; } else if( $app == 'eazycms' ){ $downloadFile = 'http://eazycms.tk/download/EazyCMS_0.3.zip'; } else if( $app == 'boxbilling' ){ $downloadFile = 'http://www.boxbilling.com/version/latest.zip'; } else if( $app == 'phpbb' ){ $downloadFile = 'https://www.phpbb.com/files/release/phpBB-3.0.12.zip'; }
	if( file_put_contents("app.zip", fopen($downloadFile, 'r')) ){
		return true;
	} else {
		return false;
	}
}

function extractCMS($app, $installDir){
	$file = $app . '.zip';
	if( $installDir == '' ){ $installDir = './'; }
	$zip = new ZipArchive;
	if ($zip->open('app.zip') === TRUE) {
		$zip->extractTo($installDir);
		$zip->close();
		return true;
	} else {
		return false;
	}
}

function movefileswp($installDir) {
	if( $installDir == '' ){ $installDir = './'; }
	$mydir = $installDir;
	if(!is_dir($mydir) && $mydir != '' ){
		mkdir($mydir);
	}
	
	//Moves all root files
	$files = glob("$mydir/wordpress/*.*");
	foreach($files as $file){
		$file_to_go = str_replace("$mydir/wordpress/",$mydir,$file);
		copy($file, $file_to_go);
	}
	
	$len = strlen($mydir) + 11;
	
	//Move (Rename) folders
	$folders = glob("$mydir/wordpress/*",GLOB_ONLYDIR);
	foreach($folders as $folder){
		$folder2 = substr($folder, $len);;
		rename($folder, $mydir . $folder2);
	}
	
	return true;
}

function movefilesdrupal($installDir) {
	if( $installDir == '' ){ $installDir = './'; }
	$mydir = $installDir;
	if(!is_dir($mydir) && $mydir != '' ){
		mkdir($mydir);
	}
	
	//Moves all root files
	$files = glob("$mydir/drupal-7.26/*.*");
	foreach($files as $file){
		$file_to_go = str_replace("$mydir/drupal-7.26/",$mydir,$file);
		copy($file, $file_to_go);
	}
	
	//Moves all dot files
	$files = glob("$mydir/drupal-7.26/.*");
	foreach($files as $file){
		if( $file == '.' || $file == '..' ){
			echo 'no';
		} else {
			$file_to_go = str_replace("$mydir/drupal-7.26/",$mydir,$file);
			copy($file, $file_to_go);
		}
	}
	
	$len = strlen($mydir) + 13;
	
	//Move (Rename) folders
	$folders = glob("$mydir/drupal-7.26/*",GLOB_ONLYDIR);
	foreach($folders as $folder){
		$folder2 = substr($folder, $len);;
		rename($folder, $mydir . $folder2);
	}
	
	return true;
}

function movefilesphpbb($installDir) {
	if( $installDir == '' ){ $installDir = './'; }
	$mydir = $installDir;
	if(!is_dir($mydir) && $mydir != '' ){
		mkdir($mydir);
	}
	
	//Moves all root files
	$files = glob("$mydir/phpBB3/*.*");
	foreach($files as $file){
		$file_to_go = str_replace("$mydir/phpBB3/",$mydir,$file);
		copy($file, $file_to_go);
	}
	
	//Moves all dot files
	$files = glob("$mydir/phpBB3/.*");
	foreach($files as $file){
		if( $file == '.' || $file == '..' ){
			echo 'no';
		} else {
			$file_to_go = str_replace("$mydir/phpBB3/",$mydir,$file);
			copy($file, $file_to_go);
		}
	}
	
	$len = strlen($mydir) + 8;
	
	//Move (Rename) folders
	$folders = glob("$mydir/phpBB3/*",GLOB_ONLYDIR);
	foreach($folders as $folder){
		$folder2 = substr($folder, $len);;
		rename($folder, $mydir . $folder2);
	}
	
	return true;
}

function cleanUp($mydir){
	if( $mydir == '' ){ $mydir = './'; }
	if (is_dir("$mydir/wordpress")) {
		$files = glob("$mydir/wordpress/*");
		foreach($files as $file){ 
		  if(is_file($file))
			unlink($file);
		}
	}
	
	if (is_dir("$mydir/drupal-7.26")) {
		$files = glob("$mydir/drupal-7.26/*");
		foreach($files as $file){ 
			if(is_file($file))
				unlink($file);
			}
			
		$files = glob("$mydir/drupal-7.26/.*");
		foreach($files as $file){ 
			if(is_file($file))
				unlink($file);
			}
	}
	
	if (is_dir("$mydir/phpBB3")) {
		$files = glob("$mydir/phpBB3/*");
		foreach($files as $file){ 
			if(is_file($file))
				unlink($file);
			}
			
		$files = glob("$mydir/phpBB3/.*");
		foreach($files as $file){ 
			if(is_file($file))
				unlink($file);
			}
	}
	
	if (is_dir("$mydir/wordpress")) {
    	rmdir("$mydir/wordpress");
	}
	
	if (is_dir("$mydir/drupal-7.26")) {
    	rmdir("$mydir/drupal-7.26");
	}
		
	if (is_dir("$mydir/wordpress")) {
    	rmdir("$mydir/phpBB3");
	}
	
	unlink('app.zip');
}
