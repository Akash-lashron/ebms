<?php
require_once 'library/config.php';
$pagesno=0;
$sql_desc="SELECT work_name, work_order_no FROM sheet where sheet_id='" . $_GET['sheetid'] . "'";
$rs_desc=mysql_query($sql_desc);
$MeasurementBookQuery=" SELECT rbn  FROM measurementbook WHERE sheet_id='" . $_GET['sheetid'] . "'";
$MeasurementBookSQL=mysql_query($MeasurementBookQuery);
if ($MeasurementBookSQL == false) { } else { $RowCount = mysql_num_rows($MeasurementBookSQL);    }
if ($MeasurementBookSQL == true && $RowCount > 0) { $RunningBillCount =@mysql_result($MeasurementBookSQL,0,'rbn') +1; }
else { $RunningBillCount =1;}
echo @mysql_result($rs_desc,0,'work_name').'*'.$RunningBillCount.'*'.@mysql_result($rs_desc,0,'work_order_no');
?>
