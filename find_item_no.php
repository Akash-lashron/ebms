<?php
require_once 'library/config.php';

$sql_workorder="SELECT sheet.work_name, sheet.tech_sanction, sheet.name_contractor, sheet.work_order_no, sheet.agree_no, sheet.work_order_date, sheet.short_name  FROM sheet where sheet.sheet_id='" . $_GET['item_no'] . "'";
$rs_workorder		=	mysql_query($sql_workorder);
$workorder_no		=	@mysql_result($rs_workorder,0,'work_order_no');
$work_name			=	@mysql_result($rs_workorder,0,'work_name');
$tech_sanction		=	@mysql_result($rs_workorder,0,'tech_sanction');
$name_contractor	=	@mysql_result($rs_workorder,0,'name_contractor');
$agree_no			=	@mysql_result($rs_workorder,0,'agree_no');
$runn_acc_bill_no	=	@mysql_result($rs_workorder,0,'runn_acc_bill_no');
$wo_date			=	@mysql_result($rs_workorder,0,'work_order_date');
$short_name			=	@mysql_result($rs_workorder,0,'short_name');
$work_order_date 	= 	date('d-m-Y',strtotime($wo_date));
$measuremnt_date_sql = "select max(todate) from measurementbook WHERE sheetid = ".$_GET['item_no'];
$measuremnt_date_query = mysql_query($measuremnt_date_sql);
if(mysql_num_rows($measuremnt_date_query)>0)
{
	$max_date = @mysql_result($measuremnt_date_query,'todate');
	$measuremnt_date = date('d-m-Y',strtotime($max_date));
}
else
{
	$measuremnt_date = $work_order_date;
}
$work =$work_name.'##'.$tech_sanction.'##'.$name_contractor.'##'.$agree_no.'##'.$runn_acc_bill_no.'##'.$measuremnt_date.'##'.$workorder_no ;
    
echo $work;
	
?>
