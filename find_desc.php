<?php
require_once 'library/config.php';
$sql_desc="select description,per,shortnotes,measure_type from schdule where subdiv_id='" . $_GET['subitem_no'] . "'";
$rs_desc=mysql_query($sql_desc);

echo @mysql_result($rs_desc,0,'description').'*'.@mysql_result($rs_desc,0,'per').'*'.@mysql_result($rs_desc,0,'shortnotes').'*'.@mysql_result($rs_desc,0,'measure_type');
?>