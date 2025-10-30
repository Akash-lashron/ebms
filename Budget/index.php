<?php
	if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
	} else {
		$uri = 'http://';
	}
	$uri .= $_SERVER['HTTP_HOST'];
	header('Location: '.$uri.'/BARC/CIVIL-SOR-NONOTP/login.php');
	exit;
?>
Something is wrong with the XAMPP installation :-(
