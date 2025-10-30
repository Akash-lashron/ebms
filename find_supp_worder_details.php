<?php
require_once 'library/config.php';
$sql_workorder="SELECT * from sheet_supplementary where supp_sheet_id='".$_GET['workorderno']."'";
$rs_workorder		=	mysql_query($sql_workorder);
$workorder_no		=	@mysql_result($rs_workorder,0,'work_order_no');
$work_name			=	@mysql_result($rs_workorder,0,'work_name');
$tech_sanction		=	@mysql_result($rs_workorder,0,'tech_sanction');
$name_contractor	=	@mysql_result($rs_workorder,0,'name_contractor');
$agree_no			=	@mysql_result($rs_workorder,0,'agree_no');
$runn_acc_bill_no	=	@mysql_result($rs_workorder,0,'runn_acc_bill_no');
$work 	=	$tech_sanction.'*'.$name_contractor.'*'.$agree_no.'*'.$work_name.'*'.$runn_acc_bill_no.'*'.$workorder_no;
echo $work;
	
?>
