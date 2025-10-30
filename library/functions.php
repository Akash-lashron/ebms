<?php
function checkUser()
{
	if (!isset($_SESSION['userid'])) {
		header('Location: ' . WEB_ROOT . 'login.php');
		exit;
	}
	
	if (isset($_GET['logout'])) {
		Logout();
	}
}
/*function UpdateWorkTransaction($GlobID,$sheetid,$rbn,$TransType,$Action,$Remarks){
	global $dbConn;
	if($_SESSION['staff_section'] == 2){
	$WorkTransStaffId = $_SESSION['sid_acc'];
	}else{
	$WorkTransStaffId = $_SESSION['sid'];
	}
	$InsertQuery = "insert into work_transaction set globid = '$GlobID', sheetid = '$sheetid', rbn = '$rbn', trans_type = '$TransType', action = '$Action', remarks = '$Remarks', staffid = '$WorkTransStaffId', trans_date = NOW()";
	$InsertSql = mysqli_query($dbConn,$InsertQuery);
}*/
function Login()
{
	$errormessage = '';
	$userName = $_POST['username'];
	$password = md5($_POST['password']);
	if($userName == ''){
		$errormessage = 'You must enter username';
	}else if($password == ''){
		$errormessage = 'You must enter password';
	}else{

		$sql = "SELECT * FROM users WHERE username = '$userName' AND password = '$password' AND active = 1";
		$result = dbQuery($sql);
	
		if (dbNumRows($result) == 1) {
			$row = dbFetchAssoc($result);
			$_SESSION['userid'] 		= $row['userid'];
			$_SESSION['username'] 		= $row['username'];
			$_SESSION['isadmin'] 		= $row['isadmin'];
            $_SESSION['sid']			= $row['staffid'];
			$_SESSION['sid_acc']		= $row['staffid'];
			$_SESSION['staff_section']	= $row['sectionid'];
			$staffid 					= $row['staffid'];
			$Rights						= $row['ModuleRights'];
			$ModuleAccStr				= $row['module_access'];
			$ModuleAccArr				= explode(",",$ModuleAccStr);
			$_SESSION['ModuleAccArr']	= $ModuleAccArr;
			if($staffid != 0)
			{
				$sectionquery = "SELECT staffname, staffcode, sectionid, levelid, dedicated_to FROM staff WHERE staffid = '$staffid' AND active = 1";
				$sectionsql = dbQuery($sectionquery);
				if(dbNumRows($sectionsql) == 1)
				{
					$row2 = dbFetchAssoc($sectionsql);
					$staffname 	= $row2['staffname'];
					$staffcode 	= $row2['staffcode'];
					//$sectionid 	= $row2['sectionid'];
					//$_SESSION['staff_section'] = $sectionid;
					$_SESSION['levelid'] = $row2['levelid'];
					$_SESSION['dedicated_to']	= $row2['dedicated_to'];
					if($row2['dedicated_to'] == "MJR"){
						$_SESSION['WorkSection'] = "1,3";
					}else if($row2['dedicated_to'] == "MNR"){
						$_SESSION['WorkSection'] = "2,4";
					}else{
						$_SESSION['WorkSection'] = "1,2,3,4";
					}
				}
			}
			
			if($staffname != ""){
				$_SESSION['staffname'] = $staffname;
			}else{
				$_SESSION['staffname'] = $_SESSION['username'];
			}
			if($Rights != "")
			{
				$ModuleRights = explode(",",$Rights);
				$_SESSION['ModuleRights'] = $ModuleRights;
			}
			else
			{
				$ModuleRights = array();
				$_SESSION['ModuleRights'] = $ModuleRights;
			}
			if (isset($_SESSION['login_return_url'])) {
				header('Location: ' . $_SESSION['login_return_url']);
				exit;
			}
			else 
			{
				/*if($_SESSION['staff_section'] == 3){
					header('Location: Budget/Home.php');
				}else if($_SESSION['staff_section'] == 2){
					header('Location: Accounts/Home.php');
				}else{
					header('Location: dashboard.php');
				}*/
				if(in_array("ACC", $ModuleAccArr)){
					header('Location: Accounts/Home.php');
				}else{
					header('Location: Budget/Home.php');
				}
				exit;
			}
		} else {
			$errormessage = 'Invalid username or password';
		}		
			
	}
	
	return $errormessage;
}

/*
	Logout a user
*/
function Logout()
{
	if (isset($_SESSION['userid'])) {
		unset($_SESSION['userid']);
		unset($_SESSION['sid']);
		session_unregister('userid');
		session_unregister('sid');
                session_destroy();
	}
		
	header('Location: login.php');
	exit;
}


