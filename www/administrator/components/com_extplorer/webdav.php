<?php

# Author: Vincent JAULIN
# Copyright: Keyphrene.com 2008 @ all rights reserved

# Tests on:
# PHP 5 mode CGI
# PHP5 mode mod_php

# INSTALL
# 1 - Create a folder
# 2 - Extract and Copy package on your site with a client FTP
# 3 - Configure webdav.php (change this script name for security reason)
# 4 - Create .htaccess in your webdav folder (only PHP-CGI)
# <IfModule mod_rewrite.c>
#	RewriteEngine on
#	RewriteRule .* - [E=REMOTE_USER:%{HTTP:Authorization},L]
# </IfModule>
# 5 - Test with a Webdav Client (Naja for example ;-))
define('_JEXEC', 1 );

require_once(dirname(__FILE__)."/webdav_authenticate.php");
include_once(dirname(__FILE__)."/config/conf.php");

if( empty($GLOBALS['allow_webdav'] ) || isset( $_REQUEST['allow_webdav'] ) ) {
	header('HTTP/1.0 403 Forbidden');
	die('403 Forbidden');
}

include_once(dirname(__FILE__)."/include/users.php");
include_once(dirname(__FILE__)."/include/functions.php");
define('_EXT_PATH', dirname(__FILE__));

ini_set("default_charset", "UTF-8");
ini_set("display_errors", 0 );

# Activate if your PHP is CGI mode
$phpcgi = substr(PHP_SAPI, 0, 3) == 'cgi';
$realm = 'Restricted Area: eXtplorer WebDAV';

load_users();

foreach( $GLOBALS["users"] as $user ) {
	$users[$user[0]] = $user[1];
}

// Authenticate the user or ask to login. We have to use basic authorization, because the passwords are stored in an encrypted format
AuthenticationBasicHTTP($realm, $users, $phpcgi);

require_once( dirname(__FILE__). "/libraries/HTTP/WebDAV/Server/Filesystem.php");
$server = new HTTP_WebDAV_Server_Filesystem();

if( stristr( $_SERVER['SCRIPT_NAME'], 'administrator/components/com_extplorer')) {
	$jconfig_file = dirname(__FILE__). "/../../../configuration.php";
	$mosConfig_absolute_path = '';
	if( file_exists( $jconfig_file )) {
		include_once($jconfig_file);
	}
	if( class_exists('jconfig')) {
		// Joomla! >= 1.5
		$config = new JConfig;
		$server->db_host = $config->host;
		$server->db_name = $config->db;
		$server->db_user = $config->user;
		$server->db_passwd = $config->password;
		$server->db_type = $config->dbtype;
		$server->db_prefix = $config->dbprefix.'_extwebdav_';
		
	} elseif(!empty($mosConfig_absolute_path)) {
		// Joomla! 1.0
		$server->db_host = $mosConfig_host;
		$server->db_name = $mosConfig_db;
		$server->db_user = $mosConfig_user;
		$server->db_passwd = $mosConfig_password;
		$server->db_type = 'mysql';
		$server->db_prefix = $mosConfig_dbprefix.'_extwebdav_';
	} else {
		header( 'HTTP/1.0 500 Internal Error');
		echo 'Internal Error: Configuration File not found.';
		exit;
	}
} else {
	$server->db_host = $GLOBALS['DB_HOST'];
	$server->db_name = $GLOBALS['DB_NAME'];
	$server->db_user = $GLOBALS['DB_USER'];
	$server->db_passwd = $GLOBALS['DB_PASSWORD'];
	$server->db_type = $GLOBALS['DB_TYPE'];
}

# Real path of your site
$server->ServeRequest($GLOBALS["home_dir"]);

?>