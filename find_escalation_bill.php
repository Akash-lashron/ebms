<?php
require_once 'library/config.php';

$sql_workorder="SELECT sheet.work_name, sheet.tech_sanction, sheet.name_contractor, sheet.work_order_no, 
sheet.agree_no, sheet.assigned_staff, sheet.major_item, sheet.sch_act, sheet.plant_service, sheet.discipline FROM sheet where sheet.sheet_id='". $_GET['workorderno']."'";
$rs_workorder		=	mysql_query($sql_workorder);
$workorder_no		=	@mysql_result($rs_workorder,0,'work_order_no');
$work_name			=	@mysql_result($rs_workorder,0,'work_name');
$tech_sanction		=	@mysql_result($rs_workorder,0,'tech_sanction');
$name_contractor	=	@mysql_result($rs_workorder,0,'name_contractor');
$agree_no			=	@mysql_result($rs_workorder,0,'agree_no');
$runn_acc_bill_no	=	@mysql_result($rs_workorder,0,'runn_acc_bill_no');
$assigned_staff		=	@mysql_result($rs_workorder,0,'assigned_staff');
$discipline		    =	@mysql_result($rs_workorder,0,'discipline');
$plant_service		=	@mysql_result($rs_workorder,0,'plant_service');
$sch_act		    =	@mysql_result($rs_workorder,0,'sch_act');
$major_item		    =	@mysql_result($rs_workorder,0,'major_item');
$sql_abstractbook   =   "SELECT * FROM abstractbook WHERE sheetid = '" . $_GET['workorderno'] . "' AND rab_status = 'P' AND is_esc = 'Y'";
$rs_abstractbook    =   mysql_query($sql_abstractbook);
$rbn                =   @mysql_result($rs_abstractbook, 0, 'rbn');

$work 	            =	$tech_sanction.'*'.$name_contractor.'*'.$agree_no.'*'.$work_name.'*'.$runn_acc_bill_no.'*'.$workorder_no.'*'.$assigned_staff.'*'.$discipline.'*'.$plant_service.'*'.$sch_act.'*'.$major_item.'*'.$rbn;
echo $work;
	
?>
