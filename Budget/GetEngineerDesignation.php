<?php
@ob_start();
require_once 'library/config.php';
$StaffCode		= $_POST['StaffCode'];
$SelectQuery 	= "select staff.*, designation.designationname from staff JOIN designation ON staff.designationid = designation.designationid where staff.staffid = '$StaffCode'";
//echo $SelectQuery;
$SelectSql 	= mysqli_query($dbConn,$SelectQuery);

//$SelectSql 	 	= mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql)>0){
		while($List = mysqli_fetch_array($SelectSql)){
			$rows[] = $List;
		}
	}
}
echo json_encode($rows);
?> 