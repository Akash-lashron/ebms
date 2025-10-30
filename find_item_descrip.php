<?php
require_once 'library/config.php';
$sql_subid = "select  distinct subdiv_id from subdivision where div_id='".$_GET['div_id']."'";
$rs_subid = mysql_query($sql_subid);
$subdiv_id = @mysql_result($rs_subid,0,'subdiv_id');
$sql_desc="select description,per,shortnotes,measure_type from schdule where subdiv_id = '$subdiv_id' AND sno='" . $_GET['div_name'] . "'";
$rs_desc=mysql_query($sql_desc);
echo @mysql_result($rs_desc,0,'description').'*'.@mysql_result($rs_desc,0,'per').'*'.@mysql_result($rs_desc,0,'shortnotes').'*'.@mysql_result($rs_desc,0,'measure_type');
?>
