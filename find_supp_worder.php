<?php
require_once 'library/config.php';
$work = "";
$sql_workorder="SELECT * FROM sheet_supplementary where sheetid='". $_GET['workorderno']."'";
$rs_workorder		=	mysql_query($sql_workorder);
if($rs_workorder == true)
{
	if(mysql_num_rows($rs_workorder)>0)
	{
		while($WOList = mysql_fetch_object($rs_workorder))
		{
			$workorder_no		=	$WOList->work_order_no;
			$work_name			=	$WOList->work_name;
			$tech_sanction		=	$WOList->tech_sanction;
			$name_contractor	=	$WOList->name_contractor;
			$agree_no			=	$WOList->agree_no;
			$runn_acc_bill_no	=	$WOList->runn_acc_bill_no;
			$supp_sheet_id		=	$WOList->supp_sheet_id;
			$work 	.=	$tech_sanction.'*'.$name_contractor.'*'.$agree_no.'*'.$work_name.'*'.$runn_acc_bill_no.'*'.$workorder_no.'*'.$supp_sheet_id."*";
		}
	}
}
echo rtrim($work,"*");;
	
?>
