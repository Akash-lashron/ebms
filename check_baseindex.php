<?php
session_start();
@ob_start();
require_once 'library/config.php';
$sheetid	=	$_POST['sheetid'];
$type 		= 	$_POST['type'];
$status		=	0;
$select_escalation_query 	= "select * from escalation where sheetid = '$sheetid' ORDER BY rbn ASC, quarter ASC LIMIT 1";
$select_escalation_sql 		=  mysql_query($select_escalation_query);
if($select_escalation_sql == true)
{
	if(mysql_num_rows($select_escalation_sql)>0)
	{
		$EscList = mysql_fetch_object($select_escalation_sql);
		$flag = $EscList->flag;
		if($flag == 1)
		{
			$status = 1;
		}
		else
		{
			$status = 2;
		}
	}
}
echo $status;
?>