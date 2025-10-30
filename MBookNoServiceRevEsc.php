<?php
require_once("library/binddata.php");
 $mbookid 			= 	$_POST['currentmbook'];
 $mbookname 		= 	$_POST['currentbmookname'];
 $sheetid 			= 	$_POST['sheetid'];
 $generatetype 		= 	$_POST['generatetype'];
 $quarter 			= 	$_POST['quarter'];
 $rbn 				= 	$_POST['rbn'];
 $esc_id 			= 	$_POST['esc_id'];
 $mtype 			= 	$_POST['mtype'];
 $EndPgArr 			= 	array();
 //if($generatetype == "CC")
 //{
 	// Below COALESCE function will return 0 if result is null
 	/*$select_mymbook_query = "select COALESCE(MAX(endpage),0) as mbpage from mymbook WHERE sheetid = '$sheetid' AND active = '1' AND mbno = '$mbookname' and mtype = '$generatetype'";
	$select_mymbook_sql = mysql_query($select_mymbook_query);
	if(mysql_num_rows($select_mymbook_sql)>0)
	{
		$MBPageList = mysql_fetch_object($select_mymbook_sql);
		$pageno 			= 	$MBPageList->mbpage;
		if($pageno == 0)
		{
			$select_mbookpage_sql	=	"select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND active = '1' AND mbno = '$mbookname' and allotmentid = '$mbookid'";
			$select_mbookpage_query	=	mysql_query($select_mbookpage_sql);
			$mbookpageno 			= 	@mysql_result($select_mbookpage_query,'mbpage');
			$mbookpageno			=	$mbookpageno+1;
		}
		else
		{
			$mbookpageno			=	$mbookpageno+1;
		}
	}
 	else
	{*/
		//$select_mbookpage_sql	=	"select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND active = '1' AND mbno = '$mbookname' and allotmentid = '$mbookid'";
		//$select_mbookpage_query	=	mysql_query($select_mbookpage_sql);
		//$mbookpageno 			= 	@mysql_result($select_mbookpage_query,'mbpage');
		//$mbookpageno			=	$mbookpageno+1;
	//}
 //}
 $temp = 0;
 //$select_mbook_query1 = "select * from mymbook where sheetid = '$sheetid' and mbno = '$mbookname' and rbn = '$rbn' and esc_id = '$esc_id' and quarter = '$quarter' and mtype = '$mtype' and genlevel = '$generatetype'";
 $select_mbook_query1 = "select * from mymbook where sheetid = '$sheetid' and mbno = '$mbookname' and rbn = '$rbn' and quarter = '$quarter' and mtype = '$mtype' and genlevel = '$generatetype'";
 $select_mbook_sql1 = mysql_query($select_mbook_query1);
 if($select_mbook_sql1 == true)
 {
 	if(mysql_num_rows($select_mbook_sql1)>0)
	{
		$MBPageList1 = mysql_fetch_object($select_mbook_sql1);
		$mbookpageno = $MBPageList1->startpage;
		$temp = 1;
	}
 }
 if($temp == 0)
 {
	 $select_mbook_query2 = "select * from mymbook where sheetid = '$sheetid' and mbno = '$mbookname' and rbn = '$rbn' and mtype = '$mtype'";// and genlevel = '$generatetype'";
	 $select_mbook_sql2 = mysql_query($select_mbook_query2);
	 if($select_mbook_sql2 == true)
	 {
		if(mysql_num_rows($select_mbook_sql2)>0)
		{
			while($MBPageList2 = mysql_fetch_object($select_mbook_sql2))
			{
			$endpage = $MBPageList2->endpage;
			//$mbookpageno = $endpage+1;
			array_push($EndPgArr,$endpage);
			}
			$temp = 1;
			$endpage = max($EndPgArr);
			$mbookpageno = $endpage+1;
		}
	 }
 }
 if($temp == 0)
 {
	$select_mbook_query3	=	"select mbpage from mbookallotment WHERE sheetid = '$sheetid' AND active = '1' AND mbno = '$mbookname' and allotmentid = '$mbookid'";
	$select_mbook_sql3		=	mysql_query($select_mbook_query3);
	$mbookpageno 			= 	@mysql_result($select_mbook_sql3,'mbpage');
	$mbookpageno			=	$mbookpageno+1;
 }
 echo $mbookpageno;
?>