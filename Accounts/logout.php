<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
LogoutLog();
if (isset($_SESSION['userid'])){
	unset($_SESSION['userid']);
	unset($_SESSION['sid']);
	session_unregister('userid');
	session_unregister('sid');
	session_destroy();
}
header('Location: ../login.php');
exit;
?>