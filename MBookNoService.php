<?php
require_once("library/binddata.php");
 $mbookid			=	$_POST[currentmbook];
 $mbookname 		= 	$_POST[currentbmookname];
 $sheetid 			= 	$_POST[sheetid];
 $generatetype 		= 	$_POST[generatetype];
 $staffid 			= 	$_POST[staffid];
 $currentrbn 		= 	$_POST[currentrbn];
 //echo $objBind->DisplayPageDetails($mbookid,$mbookname,$sheetid,$generatetype); 
 if($generatetype == "sw")
 {
 	$select_mbookpage_sql	=	"select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND allotmentid = '$mbookid'";
	$select_mbookpage_query	=	mysql_query($select_mbookpage_sql);
	$mbookpageno 			= 	@mysql_result($select_mbookpage_query,'mbpage');
	$mbookpageno			=	$mbookpageno+1;
 }
 if($generatetype == "cw")
 {
  	//$select_mbookpage_sql_1		=	"select MAX(mbpage) from mbookgenerate_staff WHERE staffid = '$staffid' AND sheetid = '$sheetid' AND mbno = '$mbookname' AND rbn = '$currentrbn'";
  	$select_mbookpage_sql_1		=	"select endpage from mymbook WHERE sheetid = '$sheetid' AND mbno = '$mbookname' AND rbn = '$currentrbn' and genlevel = 'staff' and mtype = 'G'";
	$select_mbookpage_query_1	=	mysql_query($select_mbookpage_sql_1);
	
	//$mbookpageno_temp 			= 	@mysql_result($select_mbookpage_query_1,'mbno');
	$mbookpageno_temp 			= 	@mysql_result($select_mbookpage_query_1,'endpage');
	//if(mysql_num_rows($select_mbookpage_query_1)>0)
	if($mbookpageno_temp != "")
	{
		//$mbookpageno 			= 	@mysql_result($select_mbookpage_query_1,'mbno');
		$mbookpageno 			= 	$mbookpageno_temp;
	}
	else
	{
		$select_mbookpage_sql_2		=	"select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND staffid = '$staffid' AND active = '1' AND allotmentid = '$mbookid'";
		$select_mbookpage_query_2	=	mysql_query($select_mbookpage_sql_2);
		$mbookpageno 				= 	@mysql_result($select_mbookpage_query_2,'mbpage');
	}
	$mbookpageno	=	$mbookpageno+1;
 }
 echo $mbookpageno;
//echo $currentmbook."*".$sheetid."*".$generatetype;
?>