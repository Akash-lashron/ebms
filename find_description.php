<?php
require_once 'library/config.php';
$item_name = $_GET['item_name'];
$workorderno = $_GET['workorderno'];
$sql_desc="select description,per,shortnotes,measure_type,sub_type from schdule where sno = '$item_name' AND sheet_id = '$workorderno'";
$rs_desc=mysql_query($sql_desc);
//echo $sql_desc;
echo @mysql_result($rs_desc,0,'description').'*'.@mysql_result($rs_desc,0,'per').'*'.@mysql_result($rs_desc,0,'shortnotes').'*'.@mysql_result($rs_desc,0,'measure_type').'*'.@mysql_result($rs_desc,0,'sub_type');
?>