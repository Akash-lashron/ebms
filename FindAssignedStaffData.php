<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
//include "common.php";
$Staffid 	 = $_SESSION['sid'];
$Levelid 	 = $_SESSION['levelid'];
$Ccno 	 = $_POST['Ccno'];
$CCEmailArr = array(); 
$FromEmailArr = array(); 
$SelectQuery1 = "SELECT * FROM sheet WHERE computer_code_no = '$Ccno'";
$SelectSql1 = mysql_query($SelectQuery1);
if($SelectSql1 == true){
	if(mysql_num_rows($SelectSql1)>0){
		while($List = mysql_fetch_object($SelectSql1)){
			$AssignedStaff = $List->assigned_staff;
			$SelectQuery2 = "SELECT a.staffid, a.email FROM staff a INNER JOIN users b ON (a.staffid = b.staffid) WHERE a.levelid >= '$Levelid' AND a.active = 1 AND (a.staffid IN ($AssignedStaff) OR b.isadmin = 1) AND b.sectionid = 1 ORDER BY a.sroleid DESC";
			//echo $SelectQuery2;exit;
			$SelectSql2 = mysql_query($SelectQuery2);
			if($SelectSql2 == true){
				if(mysql_num_rows($SelectSql2)>0){
					while($List2 = mysql_fetch_object($SelectSql2)){
						if($List2->staffid == $Staffid){
							if(in_array($List2->email, $FromEmailArr)){
							
							}else{
								array_push($FromEmailArr,$List2->email);
							}
						}else{
							if(in_array($List2->email, $CCEmailArr)){
							
							}else{
								array_push($CCEmailArr,$List2->email);
							}
						}
					}
				}
			}
		}
	}
}
$EmailArr = array("From"=>$FromEmailArr,"Cc"=>$CCEmailArr);
echo json_encode($EmailArr);
?>
