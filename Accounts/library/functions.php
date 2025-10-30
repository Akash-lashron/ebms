<?php
function checkUser()
{
	if (!isset($_SESSION['userid'])) {
		//header('Location: ' . WEB_ROOT . 'login.php');
		header('Location: ../login.php');
		exit;
	}
	
	if (isset($_GET['logout'])) {
		Logout();
	}
}
function UpdateWorkTransaction($GlobID,$sheetid,$rbn,$TransType,$Action,$Remarks){
	global $dbConn;
	if($_SESSION['staff_section'] == 2){
	$WorkTransStaffId = $_SESSION['sid_acc'];
	}else{
	$WorkTransStaffId = $_SESSION['sid'];
	}
	$InsertQuery = "insert into work_transaction set globid = '$GlobID', sheetid = '$sheetid', rbn = '$rbn', trans_type = '$TransType', action = '$Action', remarks = '$Remarks', staffid = '$WorkTransStaffId', trans_date = NOW()";
	$InsertSql = mysqli_query($dbConn,$InsertQuery);
}
function LoginLog(){
	global $dbConn, $dbConn;
	date_default_timezone_set('Asia/Kolkata');
	$TodayDate 	= date("Y-m-d");
	$TodayTime 	= date("h:i:s A");
	$IpAddress 	= getenv("REMOTE_ADDR"); 
	$MaxLogId 	= 0;
	$SelectMaxLogIdQuery 	= "select max(logid) as maxid from log_list";
	$SelectMaxLogIdSql 		= mysqli_query($dbConn,$SelectMaxLogIdQuery);
	if($SelectMaxLogIdSql == true){
		if(mysqli_num_rows($SelectMaxLogIdSql)>0){
			$MaxList 		= mysqli_fetch_object($SelectMaxLogIdSql);
			$MaxLogId 		= $MaxList->maxid;
		}
	}
	$MaxLogId++;
	$UpdateQuery = "update users set otp = '', otp_sent_time = '' where userid = '".$_SESSION['userid']."'";
	$UpdateSql   = mysqli_query($dbConn,$UpdateQuery);
	
	$InsertQuery = "insert into log_list set logid = '$MaxLogId', userid = '".$_SESSION['userid']."', username = '".$_SESSION['username']."', login_date = '$TodayDate', login_time = '$TodayTime', ip_address = '$IpAddress'";
	$InsertSql 	 = mysqli_query($dbConn,$InsertQuery);
}
function LogoutLog(){
	global $dbConn, $dbConn;
	date_default_timezone_set('Asia/Kolkata');
	$TodayDate 	= date("Y-m-d");
	$TodayTime 	= date("h:i:s A");
	$IpAddress 	= getenv("REMOTE_ADDR");
	$MaxLogId 	= 0;
	$SelectMaxLogIdQuery 	= "select max(logid) as maxid from log_list where userid = '".$_SESSION['userid']."'";
	$SelectMaxLogIdSql 		= mysqli_query($dbConn,$SelectMaxLogIdQuery);
	if($SelectMaxLogIdSql == true){
		if(mysqli_num_rows($SelectMaxLogIdSql)>0){
			$MaxList 		= mysqli_fetch_object($SelectMaxLogIdSql);
			$MaxLogId 		= $MaxList->maxid;
		}
	}
	$UpdateQuery = "update log_list set logout_date = '$TodayDate', logout_time = '$TodayTime', ip_address = '$IpAddress' where logid = '$MaxLogId'";
	$UpdateSql 	 = mysqli_query($dbConn,$UpdateQuery);
}
function Login()
{
	global $dbConn, $dbConn, $dbName2;
	$errormessage = ''; 
	$UserName = $_POST['txt_username'];
	$PassWord = $_POST['txt_password'];
	if(($UserName == '')||($PassWord == '')){
		$errormessage = 'You must enter Username or Password';
	}else{
		$LoginRow 	 = 0;
		$SelectQuery = "SELECT * FROM users WHERE username = '$UserName' AND  password  = '$PassWord' AND active = 1";
		$SelectSql   = mysqli_query($dbConn,$SelectQuery);
		if($SelectSql == true){  
			if(mysqli_num_rows($SelectSql)>0){  
				$row 		= mysqli_fetch_assoc($SelectSql);
				$LoginRow 	= 1;//echo "HI";
				$_SESSION['userid'] 		= $row['userid'];
				$_SESSION['username'] 		= $row['username'];
				$_SESSION['isadmin'] 		= $row['isadmin'];
				$_SESSION['issuperadmin'] 	= $row['issuperadmin'];
				$_SESSION['sectionid'] 		= $row['sectionid'];
				$_SESSION['ModuleRights'] 	= $row['ModuleRights'];
				$_SESSION['sid'] 			= $row['staffid'];
				$_SESSION['SorHcStaffName'] = "";
				if(($row['staffid'] == 0)&&($row['isadmin'] == 1)){
					$UserStaffName = "Admin";
					$_SESSION['SorHcStaffName'] = $UserStaffName;
				}else{
					$SelectStaffQuery = "select * from ".$dbName2.".staff where active = 1 and staffid = ".$row['staffid'];
					$SelectStaffSql   = mysqli_query($dbConn,$SelectStaffQuery);
					if($SelectStaffSql == true){
						if(mysqli_num_rows($SelectStaffSql)>0){
							$StaffList 		= mysqli_fetch_object($SelectStaffSql);
							$UserStaffName 	= $StaffList->staffname;
							$_SESSION['SorHcStaffName'] = $UserStaffName;
						}
					}
				}
				
				if($LoginRow == 1){
					LoginLog();
					header('Location: Home.php');
					exit;
				}
			}
		} 
		
		if($LoginRow == 0){
			$errormessage = 'Error : Invalid Username or Password.';
		}
	}
	return $errormessage;
}

/*
	Logout a user
*/
function Logout()
{
	LogoutLog();	
	if(isset($_SESSION['userid'])){
		unset($_SESSION['userid']);
		unset($_SESSION['sid']);
		session_unregister('userid');
		session_unregister('sid');
        session_destroy();
	}
	header('Location: login.php');
	exit;
}


