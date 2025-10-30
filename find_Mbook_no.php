<?php
require_once 'library/config.php';
function dt_display($ddmmyyyy)
{
 $dt=explode('-',$ddmmyyyy);
 $dd=$dt[2];
 $mm=$dt[1];
 $yy=$dt[0];
 return $dd . '-' . $mm . '-' . $yy;
}
$sheetid = $_GET['workorderno'];
$temp = $_GET['temp'];

if($temp == 2)
{
	$select_mbook_no_query 	= 	"SELECT mbno FROM mbookallotment WHERE sheetid = '$sheetid' order by mbno desc ";
	$select_mbook_no_sql	=	mysql_query($select_mbook_no_query);
	if($select_mbook_no_sql == true) 
	{
		while($List = mysql_fetch_object($select_mbook_no_sql))
		{
		  $mbookno 		= 	$List->mbno;
		  $rbn		    = 	$List->rbn;
		  $MB_data 	   .= 	$mbookno."*".$rbn;
		}   
	}
	echo rtrim($MB_data,"*");
	//echo $select_mbook_no_query;
}
	
?>
