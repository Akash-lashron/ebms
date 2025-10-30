<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
$msg = '';
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
$view = 0;
if((isset($_GET["view"]))&&($_GET["view"] != "")){
	$RepView = $_GET["view"];
}else{
	$RepView = 1;
}

if((isset($_GET["work"]))&&($_GET["work"] != "")){
	$sheetid 	= $_GET['work'];
	$view 		= 1; $count1 = 0; $count2 = 0; $count3 = 0; $count4 = 0; $count5 = 0; $count6 = 0; $count7 = 0; $count9 =0; $count10 =0; $count11=0;
	if($RepView == 1){
		$SelectQueryA 	= "select * from sheet where sheet_id = '$sheetid'";
		$SelectSqlA 	= mysql_query($SelectQueryA);
		if($SelectSqlA == true){
			if(mysql_num_rows($SelectSqlA)){
				$ListA 			= mysql_fetch_object($SelectSqlA);
				$WorkName		= $ListA->work_name;
				$WorkShortName	= $ListA->short_name;
				$WorkCcno		= $ListA->computer_code_no;
				if($WorkShortName != ''){
					$WorkName = $WorkShortName;
				}else{
					$WorkName = $WorkName;
				}
			}
		}
		$SelectQueryB 	= "select * from abstractbook where sheetid = '$sheetid' order by rbn desc";
		$SelectSqlB 	= mysql_query($SelectQueryB);
		if($SelectSqlB == true){
			$count10 	= mysql_num_rows($SelectSqlB);
			if($count10 > 0){
				$ListB 			= mysql_fetch_object($SelectSqlB);
				$fromdate 		= $ListB->fromdate;
				$todate 		= $ListB->todate;
				$ABfromdate     = $ListB->fromdate;
				$ABtodate 	    = $ListB->todate;
				$total_amount 	= $ListB->upto_date_total_amount;
				//echo $select_abstractbook_query;
				$pass_order_date= $ListB->pass_order_date;
				$rbn 			= $ListB->rbn;
				$RabStatus 		= $ListB->rab_status;
				//$count1 = 1;
			}
		}
	
	
	
		
		
		//$rbn 		= $_POST['txt_rbn'];
		/*$select_meas_book_query = "select distinct * from measurementbook where sheetid = '$sheetid' and rbn = '$rbn'";
		$select_meas_book_sql 	= mysql_query($select_meas_book_query);
		if($select_meas_book_sql == true){
			$count1 	= mysql_num_rows($select_meas_book_sql);
			$List1 		= mysql_fetch_object($select_meas_book_sql);
			//$fromdate1 	= $List1->fromdate;
			//$todate1 	= $List1->todate;
		}*/
		$select_meas_book_temp_query = "select distinct a.*, b.staffid,b.staffname,c.designationname from measurementbook_temp a inner join  staff b on (a.staffid = b.staffid) inner join designation c on (b.designationid=c.designationid)
										where a.sheetid = '$sheetid' and a.rbn = '$rbn'";
		$select_meas_book_temp_sql 	 = mysql_query($select_meas_book_temp_query);
		if($select_meas_book_temp_sql == true){
			$count2 	= mysql_num_rows($select_meas_book_temp_sql);
			$List2 		= mysql_fetch_object($select_meas_book_temp_sql);
			/*$fromdate2 	= $List2->fromdate;
			$todate2 	= $List2->todate;*/
			$StaffName  = $List2->staffname;
			$DesignationName  = $List2->designationname;
		}
		
		$select_meas_gen_query 		= "select distinct * from mbookgenerate where sheetid = '$sheetid' and rbn = '$rbn'";
		$select_meas_gen_sql 	 	= mysql_query($select_meas_gen_query);
		if($select_meas_gen_sql == true){
			$count3 	= mysql_num_rows($select_meas_gen_sql);
			$List3 		= mysql_fetch_object($select_meas_gen_sql);
			/*$fromdate3 	= $List3->fromdate;
			$todate3 	= $List3->todate;*/
		}
		$select_meas_gen_staff_query = "select mbgenerateid from mbookgenerate_staff where sheetid = '$sheetid' and rbn = '$rbn'";
		$select_meas_gen_staff_sql 	 = mysql_query($select_meas_gen_staff_query);
		if($select_meas_gen_staff_sql == true){
			$count4 = mysql_num_rows($select_meas_gen_staff_sql);
		}
		/*if($fromdate1 != "" && $todate1){
			$fromdate 	= $fromdate1;
			$todate 	= $todate1;
		}else if($fromdate2 != "" && $todate2){
			$fromdate 	= $fromdate2;
			$todate 	= $todate2;
		}else if($fromdate3 != "" && $todate3){
			$fromdate 	= $fromdate3;
			$todate 	= $todate3;
		}else{
			$select_min_from_query 	= "select min(fromdate) as fromdate from mbookgenerate_staff where sheetid = '$sheetid' and rbn = '$rbn'";
			$select_min_from_sql 	= mysql_query($select_min_from_query);
			if($select_min_from_sql == true){
				$List4 		= mysql_fetch_object($select_min_from_sql);
				$fromdate4 	= $List4->fromdate;
			}
			$select_max_to_query 	= "select max(todate) as todate from mbookgenerate_staff where sheetid = '$sheetid' and rbn = '$rbn'";
			$select_max_to_sql 		= mysql_query($select_max_to_query);
			if($select_max_to_sql == true){
				$List5 		= mysql_fetch_object($select_max_to_sql);
				$todate4 	= $List5->todate;
			}
			if($fromdate4 != "" && $todate4 != ""){
				$fromdate 	= $fromdate4;
				$todate 	= $todate4;
			}else{
				$fromdate 	= "";
				$todate 	= "";
			}
		}*/
		if($fromdate != "" && $todate != ""){
			$select_measurement_query1 = "select a.mbheaderid, b.mbheaderid from mbookheader a inner join mbookdetail b on (a.mbheaderid = b.mbheaderid) 
										 where a.sheetid = '$sheetid' and a.date	>= '$fromdate' and a.date <= '$todate'";
										//echo $select_measurement_query1;
			$select_measurement_sql1 = mysql_query($select_measurement_query1);
			if($select_measurement_sql1 == true){
				$count6 = mysql_num_rows($select_measurement_sql1);
			}
		}else{
			$select_todate_query 	= "select max(todate) as mdate from mbookgenerate_staff where sheetid = '$sheetid' ";//and rbn = '$rbn'";
			$select_todate_sql 		= mysql_query($select_todate_query);
			if($select_todate_sql == true){
				$List6 	= mysql_fetch_object($select_todate_sql);
				$mdate 	= $List6->mdate;
			}
			if($mdate != ""){
				$select_measurement_query2 = "select a.mbheaderid, b.mbheaderid from mbookheader a inner join mbookdetail b on (a.mbheaderid = b.mbheaderid) 
											where a.sheetid = '$sheetid' and a.date > '$mdate'";
				$select_measurement_sql2 = mysql_query($select_measurement_query2);
				if($select_measurement_sql2 == true){
					$count7 = mysql_num_rows($select_measurement_sql2);
					//echo $count7 ;
				}
			}else{
				$select_measurement_query3 = "select a.mbheaderid, b.mbheaderid from mbookheader a inner join mbookdetail b on (a.mbheaderid = b.mbheaderid) 
											  where a.sheetid = '$sheetid'";
				$select_measurement_sql3 = mysql_query($select_measurement_query3);
				if($select_measurement_sql3 == true){
					$count8 = mysql_num_rows($select_measurement_sql3);
					//echo $count8;
				}
			}
		}
		if($count6>0 || $count7>0 || $count8>0){
			$MCount = 1;
		}else{
			$Mcount = 0;
		}
		//echo $MCount;
		$ZoneArr = array();
		$select_zoneid_query 	= "select distinct zone_id,zone_name from  zone  where sheetid = '$sheetid'";
		$select_zoin_sql 	= mysql_query($select_zoneid_query);
		if($select_zoin_sql == true){
			$countZ 	= mysql_num_rows($select_zoin_sql);
			while($ZList = mysql_fetch_object($select_zoin_sql)){
			 $MZoneid           = $ZList->zone_id;
			 $ZoneArr[$MZoneid] = $ZList->zone_name;
			}
		}
		$MBArray = array(); $GMBArray = array(); $GMBValArr = array(); $SMBArray = array(); $SABArray = array(); $ABSArray = array(); $ErrMBArray = array();
		$select_mbook_query  = "select * from mymbook where sheetid = '$sheetid' and rbn = '$rbn'";
		$select_mbook_sql 	 = mysql_query($select_mbook_query);
		if($select_mbook_sql == true){
			$count5 = mysql_num_rows($select_mbook_sql);
			if(mysql_num_rows($select_mbook_sql)>0){
				while($MBList = mysql_fetch_object($select_mbook_sql)){
					array_push($MBArray,$MBList->genlevel);
					if(($MBList->genlevel == 'staff') && ($MBList->mtype == 'G')){
						$zoneid = $MBList->zone_id;
						//array_push($GMBArray,$MBList->mbno);
						$GMBArray[$MBList->mbno] = $MBList->startpage.",".	$MBList->endpage.",".$ZoneArr[$zoneid];
						//echo $GMBValArr[$MBList->mbno];
						//array_push($GMBArray,$MBList->startpage);
						///array_push($GMBArray,$MBList->endpage);
						//array_push($GMBArray,$MBList->zone_name);
					}if(($MBList->genlevel == 'staff') && ($MBList->mtype == 'S')){
						 $zoneid = $MBList->zone_id;
						 $SMBArray[$MBList->mbno] = $MBList->startpage.",".	$MBList->endpage.",".$ZoneArr[$zoneid];
						 //echo $SMBArray;
						/*array_push($SMBArray,$MBList->mbno);
						array_push($SMBArray,$MBList->startpage);
						array_push($SMBArray,$MBList->endpage);
						array_push($SMBArray,$MBList->zone_name);*/
					}if(($MBList->genlevel == 'composite') && ($MBList->mtype == 'G')){
						 $SABArray[$MBList->mbno] = $MBList->startpage.",".	$MBList->endpage.",".$MBList->zone_name;
						 //echo $SABArray[$MBList->mbno];
						/*array_push($SABArray,$MBList->mbno);
						array_push($SABArray,$MBList->startpage);
						array_push($SABArray,$MBList->endpage);
						array_push($SABArray,$MBList->zone_name);*/
					}if(($MBList->genlevel == 'abstract') && ($MBList->mtype == 'A')){
						 $ABSArray[$MBList->mbno] = $MBList->startpage.",".	$MBList->endpage.",".$MBList->zone_name;
						/*array_push($ABSArray,$MBList->mbno);
						array_push($ABSArray,$MBList->startpage);
						array_push($ABSArray,$MBList->endpage);
						array_push($ABSArray,$MBList->zone_name);*/
					}if(($MBList->genlevel == 'composite') && ($MBList->mtype == 'S')){
						 $ErrMBArray[$MBList->mbno] = $MBList->startpage.",".	$MBList->endpage.",".$MBList->zone_name;
						/*array_push($ErrMBArray,$MBList->mbno);
						array_push($ErrMBArray,$MBList->startpage);
						array_push($ErrMBArray,$MBList->endpage);
						array_push($ErrMBArray,$MBList->zone_name);*/
					}
				}
			}
		}
		if(count($MBArray)>0){
			if(in_array("staff", $MBArray)){
				$MBK = 1;
			}if(in_array("composite", $MBArray)){
				$SAB = 1;
			}if(in_array("abstract", $MBArray)){
				$ABS = 1;
			}
		}
		//$SAMBArr = array(); $SAMBGArr= array();	$SAMBStatusArr = array(); $SAMBTempStatusArr = array();
		$GMBArr = array(); $GMBStatusArr = array();
		$SMBArr = array(); $SMBStatusArr = array();
		$SABArr = array(); $SABStatusArr = array();
		$AMBArr = array(); $AMBStatusArr = array();
		
		$select_send_acc_query 	= "select distinct * from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn'";
		//echo $select_send_acc_query;
		$select_send_acc_sql 	= mysql_query($select_send_acc_query);
		if($select_send_acc_sql == true){
			$count9 	= mysql_num_rows($select_send_acc_sql);
			while($SAList = mysql_fetch_object($select_send_acc_sql)){
				//// For General MBook
				if($SAList->mtype == "G" && $SAList->genlevel == "staff"){
				   $zoneid = $SAList->zone_id;
				   array_push($GMBArr,$SAList->mbookno.",". $ZoneArr[$zoneid]);
				   array_push($GMBStatusArr,$SAList->mb_ac);
				}
				if($SAList->mtype == "S" && $SAList->genlevel == "staff"){
				   $zoneid = $SAList->zone_id;
				   array_push($SMBArr,$SAList->mbookno.",".$ZoneArr[$zoneid]);
				   array_push($SMBStatusArr,$SAList->mb_ac);
				}
				if($SAList->mtype == "G" && $SAList->genlevel == "composite"){
				   $zoneid = $SAList->zone_id;
				   array_push($SABArr,$SAList->mbookno.",".$ZoneArr[$zoneid]);
				   array_push($SABStatusArr,$SAList->sa_ac);
				}
				if($SAList->mtype == "A" && $SAList->genlevel == "abstract"){
				   $zoneid = $SAList->zone_id;
				   array_push($AMBArr,$SAList->mbookno.",".$ZoneArr[$zoneid]);
				   array_push($AMBStatusArr,$SAList->ab_ac);
				}
			}
		}
		/*$select_abstractbook_query 		= "select distinct * from abstractbook where sheetid = '$sheetid' and rbn = '$rbn'";
		$select_abstractbook_sql 	 	= mysql_query($select_abstractbook_query);
		if($select_abstractbook_sql == true){
			$count10 	= mysql_num_rows($select_abstractbook_sql);
			$ListAB		= mysql_fetch_object($select_abstractbook_sql);
			$ABfromdate     = $ListAB->fromdate;
			$ABtodate 	    = $ListAB->todate;
			$total_amount 	= $ListAB->upto_date_total_amount;
			//echo $select_abstractbook_query;
			$pass_order_date= $ListAB->pass_order_date;
		}*/
	}
	if($RepView == 2){
		$SheetRABArr = array(); $SheetCCNoArr = array(); $SheetDataArr = array();
		$SelectQueryA 	= "select * from sheet where sheet_id = '$sheetid'";
		$SelectSqlA 	= mysql_query($SelectQueryA);
		if($SelectSqlA == true){
			if(mysql_num_rows($SelectSqlA)){
				$ListA 			= mysql_fetch_object($SelectSqlA);
				$WorkName		= $ListA->work_name;
				$WorkShortName	= $ListA->short_name;
				$WorkCcno		= $ListA->computer_code_no;
				if($WorkShortName != ''){
					$WorkName = $WorkShortName;
				}else{
					$WorkName = $WorkName;
				}
				$AssignedStaff 	= $ListA->assigned_staff;
				$ExpAssignedStaff = explode(",",$AssignedStaff);
				if((in_array($_SESSION['sid'],$ExpAssignedStaff))||($_SESSION['isadmin'] == 1)){
					
					$SheetCCNoArr[$sheetid]    = $ListA->computer_code_no;
					
					$SheetDataArr[$sheetid][0] = $ListA->work_name;
					$SheetDataArr[$sheetid][1] = $ListA->short_name;
					$SheetDataArr[$sheetid][2] = $ListA->work_order_no;
					$SheetDataArr[$sheetid][3] = $ListA->agree_no;
					$SheetDataArr[$sheetid][4] = $ListA->createddate;
					$Rdate 		= date_create($SheetDataArr[$sheetid][4]);
					$RecDate 	= date_format($Rdate,"d/m/Y");
					$SheetDataArr[$sheetid][5] = $ListA->work_order_cost;
				}
					
			}
		}
		$SelectQueryB 	= "select * from abstractbook where sheetid = '$sheetid' order by rbn desc";
		$SelectSqlB 	= mysql_query($SelectQueryB);
		if($SelectSqlB == true){
			$count10 	= mysql_num_rows($SelectSqlB);
			if($count10 > 0){
				$ListB 			= mysql_fetch_object($SelectSqlB);
				$fromdate 		= $ListB->fromdate;
				$todate 		= $ListB->todate;
				$ABfromdate     = $ListB->fromdate;
				$ABtodate 	    = $ListB->todate;
				$total_amount 	= $ListB->upto_date_total_amount;
				//echo $select_abstractbook_query;
				$pass_order_date= $ListB->pass_order_dt;
				$rbn 			= $ListB->rbn;
				$RabStatus 		= $ListB->rab_status;
				$SheetDataArr[$sheetid][6]  = $ListB->upto_date_total_amount;
				$SheetDataArr[$sheetid][7]  = $ListB->slm_total_amount;
				$SheetDataArr[$sheetid][8]  = $ListB->secured_adv_amt;
				$SheetDataArr[$sheetid][9]  = $ListB->total_rec_rel_amt;
				$SheetDataArr[$sheetid][10] = $ListB->is_finalbill;
				$SheetRABArr[$sheetid] 	    = $ListB->rbn;
				$count1 = 1;
			}
		}
		//echo $rbn;exit;
		
		/*$SelectWorkQuery 	= "select distinct a.sheetid, a.rbn as rab, a.is_finalbill, b.*, c.*, d.* from measurementbook_temp a inner join al_as b on (a.sheetid = b.sheetid) inner join sheet c on (a.sheetid = c.sheet_id) inner join abstractbook d on (a.sheetid = d.sheetid) where a.sheetid = '$sheetid' and b.sheetid = '$sheetid' and a.rbn = b.rbn and b.status != 'C' order by c.computer_code_no asc";
		//echo $SelectWorkQuery;exit;
		$SelectWorkSql 		= mysql_query($SelectWorkQuery);
		if($SelectWorkSql == true){
			if(mysql_num_rows($SelectWorkSql)>0){
				while($WoList = mysql_fetch_object($SelectWorkSql)){
					$AssignedStaff 	= $WoList->assigned_staff;
					$ExpAssignedStaff = explode(",",$AssignedStaff);
					if((in_array($_SESSION['sid'],$ExpAssignedStaff))||($_SESSION['isadmin'] == 1)){
						$SheetRABArr[$WoList->sheetid] 	= $WoList->rab;
						$SheetCCNoArr[$WoList->sheetid] = $WoList->computer_code_no;
						
						$SheetDataArr[$WoList->sheetid][0] = $WoList->work_name;
						$SheetDataArr[$WoList->sheetid][1] = $WoList->short_name;
						$SheetDataArr[$WoList->sheetid][2] = $WoList->work_order_no;
						$SheetDataArr[$WoList->sheetid][3] = $WoList->agree_no;
						$SheetDataArr[$WoList->sheetid][4] = $WoList->createddate;
						$Rdate 		= date_create($SheetDataArr[$WoList->sheetid][4]);
						$RecDate 	= date_format($Rdate,"d/m/Y");
						$SheetDataArr[$WoList->sheetid][5] = $WoList->work_order_cost;
						$SheetDataArr[$WoList->sheetid][6] = $WoList->upto_date_total_amount;
						$SheetDataArr[$WoList->sheetid][7] = $WoList->slm_total_amount;
						$SheetDataArr[$WoList->sheetid][8] = $WoList->secured_adv_amt;
						$SheetDataArr[$WoList->sheetid][9] = $WoList->total_rec_rel_amt;
						$SheetDataArr[$WoList->sheetid][10] = $WoList->is_finalbill;
					}
				}
			}
		}*/
	}
}
// echo $view;exit;
?>
<?php require_once "Header.html"; ?>
<script language="javascript" type="text/javascript" src="script/validfn.js"></script>
<script type="text/javascript">
	function find_workname(obj)
	{	
		var xmlHttp;
		var data;
		var Workid = obj.value;
		document.form.txt_rbn.value = "";
		if(window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if(window.ActiveXObject) // For Internet Explorer
		{ 
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL="find_workname.php?sheetid="+Workid;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText
				//alert(data)
				if(data!= "")
				{	
					var idlist 		= data.split("*");
					//var WorkNameValue 	= idlist[0];
					var WorkorderValue 	= idlist[2];
					//document.form.workname.value         = WorkNameValue;
					document.form.txt_workorder_no.value = WorkorderValue;
				}
				else
				{
					//document.form.workname.value = "";
					alert("No Records Found..");
				}
			}
		}
		xmlHttp.send(strURL);
	}
	function getrbn()
	{ 
		var xmlHttp;
		var data;
		$("#cmb_work_no").chosen("destroy");
		document.form.txt_rbn.value = "";
		var i, j;
		if (window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if (window.ActiveXObject) // For Internet Explorer
		{
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL = "findabstract_mbookno.php?sheetid=" + document.form.cmb_work_no.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function ()
		{
			if (xmlHttp.readyState == 4)
			{
				data = xmlHttp.responseText
				//alert(data)
				if (data == "")
				{
					alert("No Records Found");
				}
				else
				{
					var name = data.split("*");
					for(i = 0; i < name.length; i++)
					{
						document.form.txt_rbn.value = name[3];
					}

				}
				$("#cmb_work_no").chosen();
			}
		}
		xmlHttp.send(strURL);
	}
	/*function AllRbn()
	{ 
		var xmlHttp;
		var data;
		$("#cmb_work_no").chosen("destroy");
		document.form.txt_rbn.value = "";
		var i, j;
		if (window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if (window.ActiveXObject) // For Internet Explorer
		{
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL = "find_AllRAB.php?sheetid=" + document.form.cmb_work_no.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function ()
		{
			if (xmlHttp.readyState == 4)
			{
				data = xmlHttp.responseText
				//alert(data)
				if (data == "")
				{
					alert("No Records Found");
				}
				else
				{
					var name 		= data.split("*");
					document.form.cmb_rbn.length = 0;
					var optn = document.createElement("option");
					optn.value = "";
					optn.text = "------ Select ------";
					document.form.cmb_rbn.options.add(optn);
                    for(i = 0; i < name.length; i++)
                    {
						var optn = document.createElement("option")
						optn.value = name[i];
						optn.text  = " RAB - "+name[i];
						document.form.cmb_rbn.options.add(optn)  
                   	}
				}
				$("#cmb_work_no").chosen();
			}
		}
		xmlHttp.send(strURL);
	}*/
	function goBack(){
	   	url = "MyWorks.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack(){ 
		window.history.forward(); 
	}
</script>
<style>
	.rlable-pink{
		padding:5px;
		padding-left:6px;
		padding-right:6px;
		border:1px solid #EC94A2;
		border-radius:15px;
		white-space:nowrap;
		line-height:30px;
	}
	.table > thead > tr > th {
    	padding: 3px;
		color:#30343C;
		font-size:11px;
		text-align:center;
		vertical-align:middle;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		background:#F2F3F3;
		border: 1px solid #ddd;
		line-height: 1.42857143;
    	vertical-align: top;
	}
	.table-bordered > tbody > tr > td{
		color:#0240BA;/*:#0705C3;*/
		padding: 3px;
		font-size:11px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-weight:600;
		border: 1px solid #ddd;
		line-height: 1.42857143;
    	vertical-align: top;
	}
</style>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--==============================header=================================-->

<?php include "Menu.php"; ?>
<!--==============================Content=================================-->
<div class="content">
		<div class="title">RAB Status </div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto"> 
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="container style1">
							
								
							
								<div class="row">
									<div class="div1">&nbsp;</div>
									<div class="div10" style="width:89%; margin-left:-20px;">
										<div class="row">
										    <div class="div12" align="center" style="line-height:1%;margin-top: 8px;">&nbsp;</div>
										    <div class="div12 style1" style="margin-top:0px;"><div class="row divhead" align="center">RAB Generate Status & MBook Status </div></div>
										</div>
										<div class="row innerdiv style1">
											<div class="row">
												<div class="div12" align="center"></div>
											    
												<div class="div12 style1" style="margin-top:0px;"><div class="row divhead style1 well-A" align="left">
													&nbsp;&nbsp;Name of Work : <?php echo $WorkCcno; ?> - <?php echo $WorkName; ?>
												</div>
											</div>
											<div class="div12" align="center"></div>
											<div class="div12" align="center"></div>
											
											
											
										    <div class="row" <?php  if($view == 0){ ?> style="display:none" <?php } ?>>
										    <?php  //if($count1 == 0){ ?>
												<div class="div12 style1" style="margin-top:0px;"><div class="row divhead style1 well-B" align="left">&nbsp;&nbsp; RAB - <?php echo $rbn; ?> Current Status :
												 <?php 
												 	if($RabStatus == "C"){
														echo "Bill Process Completed ";
													}else if($RabStatus == "P"){
														echo "Bill In Progress ";
													}else{
														echo "";
													}
												 	/*if ($count9 > 0){ 
												         if($GMBArr>0 || $SMBArr>0 || $SABArr>0 || $AMBArr>0){
															  foreach($GMBArr as $key =>$val){ 
																 $GStatus         = $GMBStatusArr[$key];
															  }
															  foreach($SMBArr as $key =>$val){ 
																 $SStatus         = $SMBStatusArr[$key];
															  }
															  foreach($SABArr as $key =>$val){ 
																 $SAStatus         = $SABStatusArr[$key];
															  }
															  foreach($AMBArr as $key =>$val){ 
																 $AStatus         = $AMBStatusArr[$key];
																 
															  }
															  if($GStatus == "AC" || $SStatus == "AC" || $SAStatus == "AC" || $AStatus == "AC"){
															     $CURRRENT_STATUS ="Accounts Verified / PassOrder Not Yet Confirm";}
															  if($GStatus == "SA" || $SStatus == "SA" || $SAStatus == "SA" || $AStatus == "SA"){
															     $CURRRENT_STATUS ="Accounts Not Yet Verified";}
															  if($GStatus == "SC" || $SStatus == "SC" || $SAStatus == "SC" || $AStatus == "SC"){
															     $CURRRENT_STATUS ="Accounts Rejected"; }
															  echo $CURRRENT_STATUS;
												         }else{
														     echo "RAB Not Sent to Accounts ";
														 }
													  
													  }elseif(($MCount > 0)&&($RCount1 == 0)){ echo " Measurement Uploaded / RAB Not Generated  ";
													  }elseif($MCount > 0){ echo " Measurement Uploaded ";	 
													  }elseif ($MCount == 0) {echo "Measurement  Not Yet Uploaded"; 
													  }elseif($RCount1 == 0){ echo " RAB Not Generated ";
													  }*/
												 ?>
												 <button type="button" name="StatusBtn1" id="StatusBtn1" data-id="<?php echo $sheetid; ?>" data-view="1" class="btn-status ViewStatus"><?php if($RepView == 1){ ?><i class='fa fa-check-circle' style='font-size:14px;color:white;'></i> <?php } ?> Click here to view MB Generate Status</button>
												 <button type="button" name="StatusBtn1" id="StatusBtn1" data-id="<?php echo $sheetid; ?>" data-view="2" class="btn-status ViewStatus"><?php if($RepView == 2){ ?><i class='fa fa-check-circle' style='font-size:14px;color:white;'></i> <?php } ?> Click here to view Accounts Verification Status</button>
												</div>
												<?php //}?> </div>
										   </div>
										   
										   <?php if($RepView == 1){ ?>
										   <div class="row" <?php if($view == 0){ ?> style="display:none" <?php } ?>>
											    <?php if($count1 > 0){ ?>
											    <div class="div12" align="center"><div class="innerdiv2"><div class="row innerdiv" align="center" style="color:#bd0f20d9"> RAB Closed. <?php if($count10 >0) { if($pass_order_date !=0000-00-00) {?> On <?php echo dt_display($pass_order_date) ; } if($total_amount != ""){ ?>   Bill Amount : <?php  echo $total_amount; } }?> </div></div></div>
											    <?php } else { ?>
											    <div class="div2" align="center">
													<div class="innerdiv2 style1">
														<div class="row divhead" align="center" style=" background:#177FF4; border-color:#177FF4;">Measurement</div>
														<div class="row innerdiv" align="center" style="height:240px; border-color:#177FF4;">
															<table width="100%"  bgcolor="#E8E8E8" class="table1 style1" align="center">
																<tr class="label style1">
																  <th colspan="2" align="center">Status / Remarks</th>
																</tr>
													            <?php if($MCount > 0){ ?>
																<tr>
																	<!--<td align="center">Measurement Uploaded <br/> [ <?php echo dt_display($ABfromdate); ?> - <?php echo dt_display($ABfromdate); ?> ]</td>-->
																   <td align="center">Measurement Uploaded <br/> <?php if($ABfromdate !=""){?> [ <?php echo dt_display($ABfromdate); }?> <?php if($ABtodate !=""){?> - <?php echo dt_display($ABtodate); ?> ]<?php }?></td>
			                                                       <td align="center"><i class="fa fa-check-circle" style="font-size:20px;color:green"></i></td>
																</tr>
																<tr><?php if ($StaffName !=""){?>
			                                                       <td align="center" colspan="2">  Generated By - <?php echo $StaffName; }if($DesignationName !=""){ ?>(<?php echo $DesignationName ;?>)<?php }?></td>
																</tr>
																<?php }else{ ?>
																<tr>
			                                                       <td align="center" class="color1b">Measurement Not Yet Uploaded</td>
			                                                       <td align="center"><i class="fa fa-times-circle" style="font-size:20px;color:red"></i></td>
																</tr>
																<?php } ?>
															</table>
														</div>
													</div>
											    </div>	
												<div class="div5" align="center">
													<div class="innerdiv2">
														<div class="row divhead" align="center" style=" background:#F04C8A; border-color:#F04C8A;">General - MBook</div>
														<div class="row innerdiv" align="center" style="height:240px;border-color:#F04C8A; overflow:auto;">
															<table width="100%"  bgcolor="#E8E8E8" class="table1 style1" align="center">
																<tr class="label">
																	<th  align="center" style="vertical-align:middle;"> Mbook Details</th>
																	<th  align="center" colspan="2"> Status</th>
																	<!--<th  align="center" colspan="2">Accounts Status</th>-->
																</tr>
																<?php if($count4 > 0){  
																      foreach($GMBArray as $key =>$val){ 
																		 $MbNo1        = $key;
																		 $GMValStr1    = $val;
																		 $ExpGMValStr1 = explode(',',$GMValStr1);
																		 $StartPage1   = $ExpGMValStr1[0];
																		 $EndPage1     = $ExpGMValStr1[1];
																		 $Zone1        = $ExpGMValStr1[2];
																 ?>
																 <?php if($GMBArr > 0){ 
																	  foreach($GMBArr as $key =>$val){ 
																		 $GStatus         = $GMBStatusArr[$key];
																		 $GMbNoStr1       = $val;
																		 $ExpGMBValStr1  = explode(',',$GMbNoStr1);
																		 $GMbNo1          = $ExpGMBValStr1[0];
																		 $GZone1          = $ExpGMBValStr1[1];
																		 if($GStatus == "AC" ){ $GMBSTARUS = "Verified"; 
																		 }elseif($GStatus == "SA" ){ $GMBSTARUS ="Not Yet Verified"; 
																		 }elseif($GStatus == "SC" ){ $GMBSTARUS =" Rejected"; 
																		 }elseif($GStatus == "" ){ $GMBSTARUS =" Accepted";
																		 }else{
																		  $GMBSTARUS ="Not Fund";
																		 }
																		 if($GStatus == "SA"){ $class = 'color3a'; }
																		 elseif($GStatus == "SC"){ $class = 'color1a'; }
																		 elseif($GStatus == "AC"){ $class = 'color2a'; }
																		 elseif($GStatus == "CF"){ $class = 'color4a'; }
																		 elseif($GStatus == "NF"){ $class = 'color1a'; }
																		 else{ $class = "color1a"; } 
																	  }
																    }
																?>
																<tr> 
																	<td align="left" valign="middle"><?php echo " MB : ".$MbNo1." / P : ".$StartPage1." - ".$EndPage1." (".$Zone1.")";echo "<br/>"; ?></td>
																	<td align="left" valign="middle">Generated</td>
																	<td align="center" valign="middle"><i class="fa fa-check-circle" style="font-size:20px;color:green"></i></td>
																	<!--<td align="center" valign="middle"><font class="<?php echo $class; ?> "><?php echo $GMBSTARUS;?></td>
																	<td align="center" valign="middle">
																	<?php if($GStatus == "AC"){ ?>
																		<i class='fa fa-check-circle' style='font-size:20px;color:green'></i>
																		<?php }elseif($GStatus == "CF"){ ?>
																		<i class='fa fa-check-circle' style='font-size:20px;color:green'></i>
																		<?php }elseif($GStatus == "SA"){ ?>
																		<i class="fa fa-info-circle" style='font-size:20px;color:#F4AE0B'></i>
																		<?php }elseif($GStatus == "SC"){ ?>
																		<i class='fa fa-times-circle' style='font-size:20px;color:red'></i>
																		<?php }else{ ?>
																		<i class='fa fa-times-circle' style='font-size:20px;color:red'></i>
																		<?php } ?>
																	</td>-->
																    <?php  } }else{?>
																    <td align="center" class="color1b">Rab Not Yet Generated</td>
			                                                        <td align="center" colspan="2"><i class="fa fa-times-circle" style="font-size:20px;color:red"></i></td>
																    <?Php } ?>
																</tr>
															</table>
														</div>
													</div>
												</div>
												<div class="div5" align="center">
													<div class="innerdiv2">
														<div class="row divhead" align="center" style=" background:#01C8C5; border-color:#01C8C5;">Steel - MBook</div>
														<div class="row innerdiv style1" align="center" style="height:240px;overflow:auto;border-color:#01C8C5;">
															<table width="100%"  bgcolor="#E8E8E8" class="table1 style1" align="center" >
																<tr class="label">
																	<th  align="center" style="vertical-align:middle;"> Mbook Details</th>
																	<th  align="center" colspan="2"> Status</th>
																	<!--<th  align="center" colspan="2">Accounts  Status</th>-->
																</tr>
																<?php if($count4 > 0){ 
																      if(count($SMBArray) > 0){
																      foreach($SMBArray as $key =>$val){ 
																		 $MbNo2         = $key;
																		 $SMBValStr2    = $val;
																		 $ExpSMBValStr2 = explode(',',$SMBValStr2);
																		 $StartPage2    = $ExpSMBValStr2[0];
																		 $EndPage2      = $ExpSMBValStr2[1];
																		 $Zone2         = $ExpSMBValStr2[2];
																 ?>
																 <?php if($SMBArr > 0){   
																	  foreach($SMBArr as $key =>$val){ 
																		 $SStatus         = $SMBStatusArr[$key];
																		 $SMbNoStr1       = $val;
																		 $ExpSMBValStr1  = explode(',',$SMbNoStr1);
																		 $SMbNo1          = $ExpSMBValStr1[0];
																		 $SZone1          = $ExpSMBValStr1[1];
																		 if($SStatus == "AC" ){ $SMBSTARUS = "Verified"; 
																		 }elseif($SStatus == "SA" ){ $SMBSTARUS ="Not Yet Verified"; 
																		 }elseif($SStatus == "SC" ){ $SMBSTARUS =" Rejected"; 
																		 }elseif($SStatus == "" ){ $SMBSTARUS =" Accepted";
																		 }else{
																		  $SMBSTARUS ="Not Fund";
																		 }
																		 if($SStatus == "SA"){ $class = 'color3a'; }
																		 elseif($SStatus == "SC"){ $class = 'color1a'; }
																		 elseif($SStatus == "AC"){ $class = 'color2a'; }
																		 elseif($SStatus == "CF"){ $class = 'color4a'; }
																		 elseif($SStatus == "NF"){ $class = 'color1a'; }
																		 else{ $class = "color1a"; }
																	  }
																 }
																 ?>
																<tr> 
																	<td align="left" valign="middle"><?php echo " MB : ".$MbNo2." / P : ".$StartPage2." - ".$EndPage2." (". $Zone2.")";echo "<br/>"; ?></td>
																	<td align="left" valign="middle">Generated</td>
																	<td align="center" valign="middle"><i class="fa fa-check-circle" style="font-size:20px;color:green"></i></td>
																	<!--<td align="center" valign="middle"><font class="<?php echo $class; ?>"><?php echo $SMBSTARUS;?></td>
																	<td align="center" valign="middle">
																	<?php if($SStatus == "AC"){ ?>
																		<i class='fa fa-check-circle' style='font-size:20px;color:green'></i>
																		<?php }elseif($SStatus == "CF"){ ?>
																		<i class='fa fa-check-circle' style='font-size:20px;color:green'></i>
																		<?php }elseif($SStatus == "SA"){ ?>
																		<i class="fa fa-info-circle" style='font-size:20px;color:#F4AE0B'></i>
																		<?php }elseif($SStatus == "SC"){ ?>
																		<i class='fa fa-times-circle' style='font-size:20px;color:red'></i>
																		<?php }else{ ?>
																		<i class='fa fa-times-circle' style='font-size:20px;color:red'></i>
																		<?php } ?>
																	</td>-->
																    <?php } } }else{?>
																    <td align="center" class="color1b"> Rab Not Yet Generated</td>
			                                                        <td align="center" colspan="2"><i class="fa fa-times-circle" style="font-size:20px;color:red"></i></td>
																    <?Php } ?>
																</tr>
															</table>
														</div>
													</div>
												</div>
												 <div class="div6" align="center">
													<div class="innerdiv2 style1">
														<div class="row divhead" align="center"  style="background:#9966CC ; border-color:#9966CC;">Sub Abstract</div>
														<div class="row innerdiv style1" align="center" style="min-height:50px;border-color:#9966CC;">
															<table width="100%"  bgcolor="#E8E8E8" class="table1 style1" align="center">
																<tr class="label">
																	<th  align="center" style="vertical-align:middle;"> Mbook Details</th>
																	<th  align="center" colspan="2"> Status</th>
																	<!--<th  align="center" colspan="2">Accounts  Status</th>-->
																</tr>
																<?php if($count3 > 0){ 
																      foreach($SABArray as $key =>$val){ 
																		 $MbNo3         = $key;
																		 $SABValStr3    = $val;
																		 $ExpSABValStr3 = explode(',',$SABValStr3);
																		 $StartPage3    = $ExpSABValStr3[0];
																		 $EndPage3      = $ExpSABValStr3[1];
																		 $Zone3         = $ExpSABValStr3[2];
																 ?>
																 <?php if($SABArr > 0){ 
																	  foreach($SABArr as $key =>$val){ 
																		 $SAStatus         = $SABStatusArr[$key];
																		 $SAMbNoStr1       = $val;
																		 $ExpSAMBValStr1   = explode(',',$SAMbNoStr1);
																		 $SAMbNo1          = $ExpSAMBValStr1[0];
																		 $SAZone1          = $ExpSAMBValStr1[1];
																		 if($SAStatus == "AC" ){ $SAMBSTARUS = "Verified"; 
																		 }elseif($SAStatus == "SA" ){ $SAMBSTARUS ="Not Yet Verified"; 
																		 }elseif($SAStatus == "SC" ){ $SAMBSTARUS =" Rejected"; 
																		 }elseif($SAStatus == "" ){ $SAMBSTARUS =" Accepted";
																		 }else{
																		  $SAMBSTARUS ="Not Fund";
																		 } 
																		 if($SAStatus == "SA"){ $class = 'color3a'; }
																		 elseif($SAStatus == "SC"){ $class = 'color1a'; }
																		 elseif($SAStatus == "AC"){ $class = 'color2a'; }
																		 elseif($SAStatus == "CF"){ $class = 'color4a'; }
																		 elseif($SAStatus == "NF"){ $class = 'color1a'; }
																		 else{ $class = "color1a"; }
																	  }
																 } 
																 ?>
																<tr> 
																	<td align="left" valign="middle"><?php echo " MB : ".$MbNo3." / P : ".$StartPage3." - ".$EndPage3;echo "<br/>"; ?></td>
																	<td align="left" valign="middle">Generated</td>
																	<td align="center" valign="middle"><i class="fa fa-check-circle" style="font-size:20px;color:green"></i></td>
																	<!--<td align="center" valign="middle" style="width:25%;"><font class="<?php echo $class; ?>"><?php echo $SAMBSTARUS;?></td>
																	<td align="center" valign="middle">
																	<?php if($SAStatus == "AC"){ ?>
																		<i class='fa fa-check-circle' style='font-size:20px;color:green'></i>
																		<?php }elseif($SAStatus == "CF"){ ?>
																		<i class='fa fa-check-circle' style='font-size:20px;color:green'></i>
																		<?php }elseif($SAStatus == "SA"){ ?>
																		<i class="fa fa-info-circle" style='font-size:20px;color:#F4AE0B'></i>
																		<?php }elseif($SAStatus == "SC"){ ?>
																		<i class='fa fa-times-circle' style='font-size:20px;color:red'></i>
																		<?php }else{ ?>
																		<i class='fa fa-times-circle' style='font-size:20px;color:red'></i>
																		<?php } ?>
																	</td>-->
																    <?php  } }else{?>
																    <td align="center" class="color1b"> Rab Not Yet Generated</td>
			                                                        <td align="center" colspan="2"><i class="fa fa-times-circle" style="font-size:20px;color:red"></i></td>
																    <?Php } ?>
																</tr>
															</table>
														</div>
													</div>
												  </div>
												  <div class="div6" align="center">
														<div class="innerdiv2 style1">
															<div class="row divhead" align="center" style="background:#FF6633; border-color:#FF6633;">Abstract</div>
															<div class="row innerdiv style1" align="center" style="min-height:50px; border-color:#FF6633;">
															<table width="100%"  bgcolor="#E8E8E8" class="table1 style1" align="center">
																	<tr class="label">
																		<th  align="center" style="vertical-align:middle;"> Mbook Details</th>
																		<th  align="center" colspan="2"> Status</th>
																		<!--<th  align="center" colspan="2">Accounts  Status</th>-->
																	</tr>
																	<?php if($count2 > 0){ 
																		  foreach($ABSArray as $key =>$val){ 
																			 $MbNo4         = $key;
																			 $ABSValStr4    = $val;
																			 $ExpABSValStr4 = explode(',',$ABSValStr4);
																			 $StartPage4    = $ExpABSValStr4[0];
																			 $EndPage4      = $ExpABSValStr4[1];
																			 $Zone4         = $ExpABSValStr4[2];
																	 ?>
																	 <?php if($AMBArr > 0){ 
																		  foreach($AMBArr as $key =>$val){ 
																			 $AStatus         = $AMBStatusArr[$key];
																			 $AMbNoStr1       = $val;
																			 $ExpAMBValStr1   = explode(',',$AMbNoStr1);
																			 $AMbNo1          = $ExpAMBValStr1[0];
																			 $AZone1          = $ExpAMBValStr1[1];
																			 if($AStatus == "AC" ){ $AMBSTARUS = " Verified"; 
																			 }elseif($AStatus == "SA" ){ $AMBSTARUS ="Not Yet Verified"; 
																			 }elseif($AStatus == "SC" ){ $AMBSTARUS =" Rejected"; 
																			 }elseif($AStatus == "" ){ $AMBSTARUS =" Accepted";
																			 }else{
																			  $AMBSTARUS ="Not Fund";
																			 } 
																			 if($AStatus == "SA"){ $class = 'color3a'; }
																			 elseif($AStatus == "SC"){ $class = 'color1a'; }
																			 elseif($AStatus == "AC"){ $class = 'color2a'; }
																			 elseif($AStatus == "CF"){ $class = 'color4a'; }
																			 elseif($AStatus == "NF"){ $class = 'color1a'; }
																			 else{ $class = "color1a"; }
																		  }
																	 } 
																	 ?>
																	<tr> 
																		<td align="left" valign="middle"><?php echo " MB : ".$MbNo4." / P : ".$StartPage4." - ".$EndPage4;echo "<br/>"; ?></td>
																		<td align="left" valign="middle">Generated</td>
																		<td align="center" valign="middle"><i class="fa fa-check-circle" style="font-size:20px;color:green"></i></td>
																		<!--<td align="center" valign="middle" style="width:25%;"><font class="<?php echo $class; ?>"><?php echo $AMBSTARUS;?></td>
																		<td align="center" valign="middle">
																		<?php if($AStatus == "AC"){ ?>
																			<i class='fa fa-check-circle' style='font-size:20px;color:green'></i>
																			<?php }elseif($AStatus == "CF"){ ?>
																			<i class='fa fa-check-circle' style='font-size:20px;color:green'></i>
																			<?php }elseif($AStatus == "SA"){ ?>
																			<i class="fa fa-info-circle" style='font-size:20px;color:#F4AE0B'></i>
																			<?php }elseif($AStatus == "SC"){ ?>
																			<i class='fa fa-times-circle' style='font-size:20px;color:red'></i>
																			<?php }else{ ?>
																			<i class='fa fa-times-circle' style='font-size:20px;color:red'></i>
																		<?php } ?>
																		</td>-->
																		<?php  } }else{?>
																		<td align="center" class="color1b"> Rab Not Yet Generated</td>
																		<td align="center" colspan="2"><i class="fa fa-times-circle" style="font-size:20px;color:red"></i></td>
																		<?Php } ?>
																	</tr>
																</table>
														   </div>
														</div>
												  </div>
												<?php } ?>
										   </div>
										   <?php } ?>
										   
										   <?php if($RepView == 2){ ?>
										   		 <table align="center" class="table table-bordered" style="border:0px; margin-top:3px;">
													<tbody>
													<?php foreach($SheetRABArr as $sheetid=>$rbn){
														  $CCNO = $SheetCCNoArr[$sheetid]; ?>
													<!--<tr><td style="height:30px; vertical-align:middle; background:#8E99A8; color:#fff; font-weight:normal; font-size:12px;">Name of Work : <?php echo $SheetDataArr[$sheetid][0]; ?></td></tr>-->
													<tr>
														<td>
														   <span class="rlable-pink">Work Order No : <?php echo $SheetDataArr[$sheetid][2]; ?></span>
														   <span class="rlable-pink">Agreement No : <?php echo $SheetDataArr[$sheetid][3]; ?></span>
														   <span class="rlable-pink">CC No : <?php echo $CCNO; ?></span>
														   <span class="rlable-pink">Bill No : RAB <?php echo $rbn; if($SheetDataArr[$sheetid][10] == 'Y'){ echo " & Final Bill"; }?></span>
														   <span class="rlable-pink">Sent To Accounts on : <?php echo $RecDate; ?></span>
														   <span></br></span>
														   <span class="rlable-pink">Work Order Cost : <?php echo $SheetDataArr[$sheetid][5]; ?></span>
														   <span class="rlable-pink">Upto Paid Amount : <?php echo $SheetDataArr[$sheetid][6]; ?></span>
														   <span class="rlable-pink">This Bill Value : <?php echo $SheetDataArr[$sheetid][7]; ?></span>
														   <span class="rlable-pink">secured Advance Amount : <?php echo $SheetDataArr[$sheetid][8]; ?></span>
														   <span class="rlable-pink">Recovery amount  : <?php echo $SheetDataArr[$sheetid][9]; ?></span>
														</td>  
													</tr>
													<?php
													$TrTDStr = ""; $CivilTempDate = '0000-00-00';
													$SelectStatusQuery 	= "select a.*, DATE(b.modifieddate) as civildate from acc_log a inner join send_accounts_and_civil b on (a.linkid = b.sacid) where a.sheetid = '$sheetid' and b.sheetid = '$sheetid' and a.rbn = '$rbn' and b.rbn = '$rbn' order by b.mbookno asc";
													//echo $SelectStatusQuery;exit;
													$SelectStatusSql 	= mysql_query($SelectStatusQuery);
													if($SelectStatusSql == true){
														if(mysql_num_rows($SelectStatusSql)>0){
															while($SList = mysql_fetch_object($SelectStatusSql)){
																$MBookNo 		= $SList->mbookno;
																$MBAcStatus 	= $SList->AC_status;
																$MBLevelIds 	= $SList->staff_levelids;
																$MBLevel 		= $SList->levelid;
																$MBType 		= $SList->mtype;
																$MBGenLevel 	= $SList->genlevel;
																$MBRecDtList 	= $SList->rec_dt_list;
																$MBCompDtList	= $SList->comp_dt_list;
																$MBTypeStr 		= "";
																$MBFromCivilDt 	= $SList->civildate;
																$AcceptStatus 	= $SList->status;
																//array_push($CivilDtArr,$MBFromCivilDt);
																if($MBFromCivilDt > $CivilTempDate){
																	$MaxCivilDate = $MBFromCivilDt;
																}else{
																	$MaxCivilDate = $CivilTempDate;
																}
																$CivilTempDate = $MBFromCivilDt;
																
																if($MBType == 'G'){
																	if($MBGenLevel == 'staff'){
																		$MBTypeStr = "General";
																	}else if($MBGenLevel == "composite"){
																		$MBTypeStr = "Sub-Abstract";
																	}
																}else if($MBType == 'S'){
																	$MBTypeStr = "Steel";
																}else if($MBType == 'A'){
																	$MBTypeStr = "Abstract";
																}
																
																//if($MBLevel
																$ExpMBLevelIds = explode(",",$MBLevelIds);
																if(count($ExpMBLevelIds)>0){
																	$LastCheckedLevel = end($ExpMBLevelIds);
																}else{
																	$LastCheckedLevel = "";
																}
																
																$TrTDStr .= "<tr>";
																$TrTDStr .= "<td align='center'>".$MBookNo."</td>";
																$TrTDStr .= "<td nowrap='nowrap'>".$MBTypeStr."</td>";
																
																for($i=1; $i<=5; $i++){
																	$MaxPosition = "";
																	/// To find a Maximum poisition of level (for last transaction of particular level)
																	if(in_array($i,$ExpMBLevelIds)){
																		foreach($ExpMBLevelIds as $KeyA=>$ValueA){
																			if($i == $ValueA){
																				$MaxPosition = $KeyA;
																			}
																		}
																	}
																	
																	$ExpMBRecDtList 	= explode(",",$MBRecDtList);
																	$ExpMBCompDtList 	= explode(",",$MBCompDtList);
																	
																	$RecDate 	= $ExpMBRecDtList[$MaxPosition];
																	$CompDate 	= $ExpMBCompDtList[$MaxPosition];
																	
																	if($RecDate == ""){
																		if($i == $MBLevel){
																			$TemCnt = count($ExpMBRecDtList)-1;
																			$RecDate = $ExpMBRecDtList[$TemCnt];
																		}
																	}
																	
																	if($RecDate != ""){
																		$Rdate 		= date_create($RecDate);
																		$RecDate 	= date_format($Rdate,"d/m/Y");
																	}
																	if($CompDate != ""){
																		$Cdate		= date_create($CompDate);
																		$CompDate 	= date_format($Cdate,"d/m/Y");
																	}
																	
																	if($i < $MBLevel){
																		$TrTDStatusStr = "<td align='center'><i class='fa fa-check-circle' style='font-size:20px; color:green'></i></td>";
																		$TrTDStr .= "<td>".$RecDate.$d."</td>";
																		$TrTDStr .= "<td>".$CompDate."</td>";
																	}else if($i == $MBLevel){
																		if($MBAcStatus == 'A'){
																			$TrTDStatusStr = "<td align='center'><i class='fa fa-check-circle' style='font-size:20px; color:green'></i></td>";
																			$TrTDStr .= "<td>".$RecDate.$d."</td>";
																			$TrTDStr .= "<td>".$CompDate."</td>";
																		}else if($MBAcStatus == 'R'){
																			$TrTDStatusStr = "<td align='center'><i class='fa fa-times-circle' style='font-size:20px; color:red'></i></td>";
																			$TrTDStr .= "<td>".$RecDate.$d."</td>";
																			$TrTDStr .= "<td>&nbsp;</td>";
																		}else{
																			$TrTDStatusStr = "<td align='center'><i class='fa fa-times-circle' style='font-size:20px; color:red'></i></td>";
																			$TrTDStr .= "<td>".$RecDate.$d."</td>";
																			$TrTDStr .= "<td>&nbsp;</td>";
																		}
																	}else if($i > $MBLevel){
																		$TrTDStatusStr = "<td align='center'>&nbsp;</td>";
																		$TrTDStr .= "<td>&nbsp;</td>";
																		$TrTDStr .= "<td>&nbsp;</td>";
																	}
																	$TrTDStr .= $TrTDStatusStr;
																}
																
																$TrTDStr .= "</tr>";
															}
														}
													}
													
													/// Max Received Date from Civil Section
													?>
													<?php //echo $MaxCivilDate; ?>
													<tr style="border:0px !important">
														<td style="padding:0px; border:0px !important">
															<table class="table table-bordered">
																<thead>
																	<tr>
																		<th rowspan="2">MBook No.</th>
																		<th rowspan="2">MBook Type</th>
																		<th colspan="3">Dealing Assistant</th>
																		<th colspan="3">Accountant</th>
																		<th colspan="3">AAO</th>
																		<th colspan="3">AO</th>
																		<th colspan="3">DCA</th>
																	</tr>
																	<tr>
																		<th>Rec. Date</th>
																		<th>Comp. Date</th>
																		<th>Status</th>
																		
																		<th>Rec. Date</th>
																		<th>Comp. Date</th>
																		<th>Status</th>
																		
																		<th>Rec. Date</th>
																		<th>Comp. Date</th>
																		<th>Status</th>
																		
																		<th>Rec. Date</th>
																		<th>Comp. Date</th>
																		<th>Status</th>
																		
																		<th>Rec. Date</th>
																		<th>Comp. Date</th>
																		<th>Status</th>
																	</tr>
																</thead>
																<tbody>
																<?php echo $TrTDStr; ?>
																</tbody>
															</table>
														</td>
													</tr>
													<?php } ?>
													</tbody>
											   </table>
										   <?php } ?>
										   
										   
										</div>
										</div>
									   <div class="div1">&nbsp;</div>
								  	   <div class="div1">&nbsp;</div>
									</div>
								</div>
								<div align="center">
									<input type="button" name="back" id="back" value=" BACK " class="backbutton" onClick="goBack()">
								</div>
								<div align="center">&nbsp;</div>
								
								
                            </div>
                        </form>
                    </blockquote>
                </div>
            </div>
        </div>
<!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
<script>
$("#cmb_work_no").chosen();
$("#cmb_rbn").chosen();
$(function() { 
	$.fn.validateworkorder = function(event) { 
		if($("#cmb_work_no").val()==""){ 
			var a="Please select the work order number";
			$('#val_work').text(a);
			event.preventDefault();
			event.returnValue = false;
		}
		else{
			var a="";
			$('#val_work').text(a);
		}
	}
	$.fn.validateRAB = function(event) { 
		if($("#txt_rbn").val()==""){ 
			var a="Please Enter RAB No.";
			$('#val_rbn').text(a);
			event.preventDefault();
			event.returnValue = false;
		}
		else if($("#txt_rbn").val()==0){ 
			var a="Please Enter valid RAB Number";
			$('#val_rbn').text(a);
			event.preventDefault();
			event.returnValue = false;
		}
		else{
			var a="";
			$('#val_rbn').text(a);
		}
	}
	
	$('body').on('click', '.ViewStatus', function(){
		var view = $(this).attr('data-view');
		var id   = $(this).attr('data-id');
		$(location).attr('href','RABStatus.php?work='+id+'&view='+view);
	});
	
	$("#top").submit(function(event){
		$(this).validateworkorder(event);
		$(this).validateRAB(event);
	});
	$("#cmb_work_no").change(function(event){
    	$(this).validateworkorder(event);
    });
	$("#txt_rbn").keyup(function(event){
    	$(this).validateRAB(event);
    });
});
</script>
<style>
.table1 tr{
color:#005CB9;
font-family:Verdana;
font-size:11px;
}
.table1 td {
padding: 2px;
}
.table1 th {
border:1px solid #CCCCCC;
 background:#eae5e5e8;
}
.divhead {
    padding: 0px 0px;
}
submit{
    padding-top: 4px;
    padding-left: 6px;
    padding-right: 6px;
    padding-bottom: 4px;
}
.div12 {
    margin-top: 1px;
}
.chosen-container-single .chosen-single {
color:#005CB9;
}
.style1{ 
  font-family:Verdana;
  font-size:11px;
  font-weight:bold;
  color:#005CB9;
}
.row label {
  color:#005CB9;
}
.chosen-container .chosen-results {
  color:#005CB9;
}	
</style>
<style>
.color1a{
	color:#F40006;
}
.color1b{
	color:#F40006;
	font-weight:bold;
}
.color2a{
	color:#005CB9;
}
.color2b{
	color:#005CB9;
	font-weight:bold;
}
.color3a{
	color:#F4AE0B;
}
.color3b{
	color:#F4AE0B;
	font-weight:bold;
}
.color4a{
	color:#03A33C;
}
.color4b{
	color:#03A33C;
	font-weight:bold;
}
.chosen-container .chosen-results li.active-result {
  font-family:Verdana;
  font-size:12px;
  font-weight:bold;
}
.well-A {
    background-color: #fff;
    border: 1.5px solid #1583b9;
    font-family:Verdana;
    font-size:11px;
    font-weight:bold;
    color:#005CB9;
    cursor: pointer;
    border-radius: 15px;
    padding: 2px;
}
.well-B {
    background-color: #fff;
    border: 1.5px solid #1583b9;
    font-family:Verdana;
    font-size:11px;
    font-weight:bold;
    color:#5F1995;
    cursor: pointer;
    border-radius: 15px;
    padding: 2px;
}
.btn-status{
	background-color:#10478A;
	color:#ffffff;
	font-size:11px;
	font-family:Verdana;
	cursor: pointer;
    border-radius: 15px;
    padding: 2px 6px 3px 6px;
	border: 1.5px solid #10478A;
	font-weight:600;
}
.btn-status:hover{
	background-color:#04244B;
	color:#ffffff;
}
</style>
</body>
</html>

<script>
    $("#cmb_work_no").change(function(){
		var sheetid = $("#cmb_work_no option:selected").attr('value');
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/find_AllRAB.php', 
			data: { sheetid: sheetid }, 
			dataType: 'json',
			success: function (data) {   //alert(data);
				//$('#GetRabList').chosen('destroy');
				$('#GetRabList').children('option:not(:first)').remove();
				if(data != null){
					$.each(data, function(index, element) {
						$("#GetRabList").append('<option value="'+element.rbn+'">'+element.rbn+'</option>');
					});
				}
				//$("#GetRabList").chosen();
			}
		});
		});
</script>

