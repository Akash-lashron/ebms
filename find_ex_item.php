<?php
require_once 'library/config.php';
$work = "";
$measurement_type =$_GET['measure_value'];
if ($measurement_type =='G'){
   $measurement_type_value = "";
}else{
   $measurement_type_value = "$measurement_type";
}
$sheetschduleQuery=" SELECT sno,sch_id,description FROM  schdule  WHERE sheet_id ='" . $_GET['workorderno'] . "' and measure_type ='$measurement_type_value' ";
$sheetschduleSql=mysql_query($sheetschduleQuery);
if($sheetschduleSql == true)
{
	if(mysql_num_rows($sheetschduleSql)>0)
	{
		while($WOList = mysql_fetch_object($sheetschduleSql))
		{
			$sch_id		         =	$WOList->sch_id;
			$sno		         =	$WOList->sno;
			$description		 =	$WOList->description;
			
			$work 	.=	$sch_id.'*'.$sno.'*'.$description.'*';
		}
	}
}
echo rtrim($work,"*");
//echo $sheetschduleQuery;
?>
