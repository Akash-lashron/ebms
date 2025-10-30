<?php
//ini_set('display_errors', 'On');
//ob_start("ob_gzhandler");
//error_reporting(E_ALL);

// start the session
if (!isset($_SESSION)) session_start();

// database connection config
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'ebms';
//$dbName2 = 'wmbook';
$thisFile = str_replace('\\', '/', __FILE__);
$docRoot = $_SERVER['DOCUMENT_ROOT'];
$webRoot  = str_replace(array($docRoot, 'library/config.php'), '', $thisFile);
$srvRoot  = str_replace('library/config.php', '', $thisFile);
define('WEB_ROOT', $webRoot);
define('SRV_ROOT', $srvRoot);
require_once 'database.php';
?>