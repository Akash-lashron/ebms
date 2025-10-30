<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$GroupId 	= $_POST['groupid'];
$GroupIdLen = strlen($GroupId);
$GroupIdLen = $GroupIdLen+2;
$Id 		= $_POST['level'];

$TotalAmount = 0; //$rows[] = array();
//$SelectDetailQuery 	= "select a.*, b.item_desc, b.unit, b.price, b.item_code from datasheet_a1_details a inner join item_master b on (a.item_id = b.item_id) where a.ref_id = '$RefId'";
//$SelectDetailQuery	= "select * from group_datasheet where char_length( group_id ) = '$GroupIdLen' and group_id like '".$GroupId. "%' order by group_id";
$SelectDetailQuery	= "select * from group_datasheet_hc where par_id='$GroupId' and active = 1 order by group_id";
$SelectDetailSql 	= mysqli_query($dbConn,$SelectDetailQuery);
if($SelectDetailSql == true){
	if(mysqli_num_rows($SelectDetailSql)>0){
		while($ListD = mysqli_fetch_array($SelectDetailSql)){
			$rows[] = $ListD;
		}
	}
}
echo json_encode($rows);

?>