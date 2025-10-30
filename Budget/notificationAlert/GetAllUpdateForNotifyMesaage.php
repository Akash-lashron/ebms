<?php
@ob_start();
//echo "hi";exit;//
$maxid = 0;
$staffid = ""; $Staffname = ""; $designation = ""; $role_name = ""; $forward_to = ""; $forward_flag = "";
require_once '../library/config.php';
$select_check_measure_query = "select max(cmid) as maxid, levelid, staffid, forward_to, forward_flag from check_measurement_master where forward_to = ".$_SESSION['levelid'];
$select_check_measure_sql = mysqli_query($dbConn,$select_check_measure_query);
if($select_check_measure_sql == true){
	if(mysqli_num_rows($select_check_measure_sql)>0){
		$List = mysqli_fetch_object($select_check_measure_sql);
		$maxid = $List->maxid;
		$staffid = $List->staffid;
		$levelid = $List->levelid;
		$forward_to = $List->forward_to;
		$forward_flag = $List->forward_flag;
	}
}
if($forward_flag == "FW"){
	$status = "Forward to ";
}elseif($forward_flag == "BW"){
	$status = "Retrun Back to ";
}else{
	$status = "";
}

$RoleArray = array();
if($status != ""){
	$select_role_query = "select role_name, levelid from staffrole where active = 1 and sectionid = ".$_SESSION['staff_section'];
	$select_role_sql = mysqli_query($dbConn,$select_role_query);
	if($select_role_sql == true){
		while($RList = mysqli_fetch_object($select_role_sql))
		{
			$level 				= $RList->levelid;
			$role 				= $RList->role_name;
			$RoleArray[$level] 	= $role;
		}
	}
}
$FromLevelName = $RoleArray[$levelid];
$ToLevelName = $RoleArray[$forward_to];

if($staffid != ""){
	$select_staff_query = 	"select a.staffname, a.image, b.designationname from staff a 
							inner join designation b on (a.designationid = b.designationid) where staffid = '$staffid'";
	$select_staff_sql = mysqli_query($dbConn,$select_staff_query);
	if($select_staff_sql == true){
		$List2 = mysqli_fetch_object($select_staff_sql);
		$Staffname 		= $List2->staffname;
		$designation 	= $List2->designationname;
		$image 			= $List2->image;
	}
}
echo $maxid."@*@".$staffid."@*@".$Staffname."@*@".$designation."@*@".$FromLevelName."@*@".$image."@*@".$ToLevelName;
//echo $select_staff_query;
?>