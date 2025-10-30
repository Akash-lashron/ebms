<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "library/common.php";
checkUser();
$msg = '';
$staffid 		= $_SESSION['sid'];
$staffid_acc 	= $_SESSION['sid_acc'];
//echo $_SESSION['levelid'];exit;
$userid 		= $_SESSION['userid'];
$acc_levelid 	= $_SESSION['levelid'];
$section 		= $_SESSION['staff_section'];
$_SESSION['lock'] = "";
$SectionArr = array();
$SelectSectionQuery = "SELECT * FROM section_name WHERE active = 1";
$SelectSectionSql 	= mysqli_query($dbConn,$SelectSectionQuery);
if($SelectSectionSql == true){
	if(mysqli_num_rows($SelectSectionSql) > 0){
		while($SecList = mysqli_fetch_object($SelectSectionSql)){
			$SectionArr[$SecList->section_type] = $SecList->section_name;
		}
	}
}
if(isset($_POST["txt_test"]))
{
	$post_id = $_POST["txt_post_id"];//view_mbook
	$dataStr = $_POST['txt_data_'.$post_id];
	$view = $_POST["txt_view"];
	$expdataStr = explode("*",$dataStr);
	$sheetid = $expdataStr[0];
	$zone_id = $expdataStr[1];
	$staffid = $expdataStr[2];
	$mtype 	 = $expdataStr[3];
	$rbn	 = $expdataStr[4];
	$linkid	 = $expdataStr[5];
	$_SESSION['sid'] 	= $staffid;
	$_SESSION['zone_id'] = $zone_id;
	$view_status = "";
	if($mtype == "G")
	{
		$select_locked_mb_query = "select locked_status,locked_staff from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and genlevel = 'staff' and mtype = 'G'";
		$view_url = "MBook_Print_staff_wise_Accounts.php?workno=".$sheetid."&linkid=".$linkid."&view=".$view;
		$update_locked_query = "update send_accounts_and_civil set locked_status = 'locked', locked_staff = '$staffid_acc' where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and mtype = 'G' and genlevel = 'staff'";
	}
	if($mtype == "S")
	{
		$select_locked_mb_query = "select locked_status,locked_staff from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and genlevel = 'staff' and mtype = 'S'";
		$view_url = "SteelMBook_Print_staff_wise_Accounts.php?workno=".$sheetid."&linkid=".$linkid."&view=".$view;
		$update_locked_query = "update send_accounts_and_civil set locked_status = 'locked', locked_staff = '$staffid_acc' where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and mtype = 'S' and genlevel = 'staff'";
	}
	if($mtype == "SABS")
	{
		$select_locked_mb_query = "select locked_status,locked_staff from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and genlevel = 'composite' and mtype = 'G'";
		$view_url = "GeneralMBook_Composite_Print_Accounts.php?workno=".$sheetid."&linkid=".$linkid."&view=".$view;
		$update_locked_query = "update send_accounts_and_civil set locked_status = 'locked', locked_staff = '$staffid_acc' where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and mtype = 'G' and genlevel = 'composite'";
	}
	if($mtype == "ABS")
	{
		$select_locked_mb_query = "select locked_status,locked_staff from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and genlevel = 'abstract' and mtype = 'A'";
		$view_url = "AbstMBook_Print_Common_Accounts.php?workno=".$sheetid."&linkid=".$linkid."&view=".$view;
		$update_locked_query = "update send_accounts_and_civil set locked_status = 'locked', locked_staff = '$staffid_acc' where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and mtype = 'A' and genlevel = 'abstract'";
	}
	if($mtype == "SEC"){
		$select_locked_mb_query = "select locked_status,locked_staff from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and genlevel = 'abstract' and mtype = 'A'";
		$view_url = "SecuredAdvanceView.php?workno=".$sheetid."&rbn=".$rbn."&view=".$view;
		$update_locked_query = "update send_accounts_and_civil set locked_status = 'locked', locked_staff = '$staffid_acc' where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and mtype = 'A' and genlevel = 'abstract'";
	}
	if($mtype == "CC"){
		$select_locked_mb_query = "select locked_status,locked_staff from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and genlevel = 'abstract' and mtype = 'A'";
		$view_url = "Esc_Consump_10ca_Cement_Print_Page.php?sheetid=".$sheetid."&rbn=".$rbn."&view=".$view."&quarter=".$zone_id."&escid=".$linkid;
		$update_locked_query = "update send_accounts_and_civil set locked_status = 'locked', locked_staff = '$staffid_acc' where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and mtype = 'A' and genlevel = 'abstract'";
	}
	if($mtype == "SC"){
		$select_locked_mb_query = "select locked_status,locked_staff from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and genlevel = 'abstract' and mtype = 'A'";
		$view_url = "Esc_Consump_10ca_Steel_Print_Page.php?sheetid=".$sheetid."&rbn=".$rbn."&view=".$view."&quarter=".$zone_id."&escid=".$linkid;
		$update_locked_query = "update send_accounts_and_civil set locked_status = 'locked', locked_staff = '$staffid_acc' where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and mtype = 'A' and genlevel = 'abstract'";
	}
	if($mtype == "EA"){
		$select_locked_mb_query = "select locked_status,locked_staff from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and genlevel = 'abstract' and mtype = 'A'";
		$view_url = "EscalationAbstractPrint.php?sheetid=".$sheetid."&rbn=".$rbn."&view=".$view."&quarter=".$zone_id."&escid=".$linkid;
		$update_locked_query = "update send_accounts_and_civil set locked_status = 'locked', locked_staff = '$staffid_acc' where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and mtype = 'A' and genlevel = 'abstract'";
	}
	if($mtype == "E"){
		$select_locked_mb_query = "select locked_status,locked_staff from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and genlevel = 'abstract' and mtype = 'A'";
		$view_url = "EscalationPrint.php?sheetid=".$sheetid."&rbn=".$rbn."&view=".$view."&quarter=".$zone_id."&escid=".$linkid;
		$update_locked_query = "update send_accounts_and_civil set locked_status = 'locked', locked_staff = '$staffid_acc' where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and mtype = 'A' and genlevel = 'abstract'";
	}
	if($select_locked_mb_query != "")
	{
		$select_locked_mb_sql 	= mysqli_query($dbConn,$select_locked_mb_query);
		if($select_locked_mb_sql == true)
		{
			$LockList 		= mysqli_fetch_object($select_locked_mb_sql);
			$locked_status 	= $LockList->locked_status;
			$locked_staff 	= $LockList->locked_staff;
		}
	}
	$_SESSION['selected_sheet'] = $sheetid;
	//echo $view_url;exit;
	if(($locked_status == "locked") && ($staffid_acc != $locked_staff))
	{
		//header('Location: MeasurementBook_Locked_Accounts.php?locked_staff='.$locked_staff."&view=".$view);
		$update_locked_sql = mysqli_query($dbConn,$update_locked_query);
		header('Location:'.$view_url);
	}
	else
	{
		$update_locked_sql = mysqli_query($dbConn,$update_locked_query);
		header('Location:'.$view_url);
	}
	//echo $dataStr;
}
if($_POST["submit"] == " View ") 
{
	$mbooktype 			= $_POST['cmb_mbook_type'];
	$workno 			= $_POST['cmb_work_no'];
	$_SESSION['sid'] 	= $_POST['cmb_staff_name'];
	$_SESSION['zone_id'] = $_POST['cmb_zone_name'];
	if($mbooktype == "G")
	{
		//header('Location: MBook_Print_staff_wise_Accounts.php?workno='.$workno);
	}
	if($mbooktype == "S")
	{
		//header('Location: SteelMBook_Print_staff_wise_Accounts.php?workno='.$workno); 
	}
}

/*$select_sheet_query = 	"select distinct (a.sheetid), a.rbn, a.staffid, b.*, c.*, d.staffname from measurementbook_temp a 
						INNER JOIN al_as b ON (b.sheetid = a.sheetid)
						INNER JOIN sheet c ON (c.sheet_id = a.sheetid)
						INNER JOIN staff d ON (d.staffid = a.staffid)
						where a.rbn = b.rbn and b.status = '$acc_levelid'
						ORDER BY a.sheetid ASC";*/
/*$select_level_query = "select distinct level from al_as_dt where sheetid = '$sheetid' and rbn = '$rbn'";
$select_level_sql 	=  mysqli_query($dbConn,$select_level_query);
if($select_level_sql == true){
	$LevelArr = mysqli_fetch_array($select_level_sql);
}
echo $select_level_query;
print_r($LevelArr);exit;*/

//$select_level_query = "select distinct (a.sheetid), a.rbn, b.*, c.* from measurementbook_temp a inner join al_as b on (b.sheetid = a.sheetid)";

//echo $acc_levelid;exit;


$WorkAccLevelArr = array();
//$_SESSION['RetCivilSheet'] = '26,39,52,98,149,163,163';
if($_GET['view'] == "r"){
/*$select_sheet_query = 	"select distinct (a.sheetid), a.rbn, a.staffid, b.*, c.short_name, c.work_order_no, c.work_name, c.tech_sanction, 
						c.name_contractor, c.agree_no, c.computer_code_no, c.worktype, d.staffname , DATE_FORMAT(b.createddate,'%d/%m/%Y') as rec_date 
						from measurementbook_temp a 
						INNER JOIN al_as b ON (b.sheetid = a.sheetid)
						INNER JOIN sheet c ON (c.sheet_id = a.sheetid)
						INNER JOIN staff d ON (d.staffid = a.staffid)
						where a.rbn = b.rbn and c.active = 1 and a.sheetid IN (".$_SESSION['RetCivilSheet'].") and c.worktype IN (".$_SESSION['WorkSection'].")
						ORDER BY b.createddate ASC";*/	
$select_sheet_query = 	"select a.br_no, a.sheetid, a.rbn, a.sent_by as staffid, b.*, c.short_name, c.work_order_no, c.work_name, c.tech_sanction, c.section_type,  
						c.name_contractor, c.agree_no, c.computer_code_no, c.worktype, d.staffname , DATE_FORMAT(b.createddate,'%d/%m/%Y') as rec_date 
						from bill_register a 
						INNER JOIN al_as b ON (b.sheetid = a.sheetid)
						INNER JOIN sheet c ON (c.sheet_id = a.sheetid)
						INNER JOIN staff d ON (d.staffid = a.sent_by)
						where a.reg_status = 'R' and a.rbn = b.rbn and c.active = 1 and a.sheetid IN (".$_SESSION['RetCivilSheet'].") and c.worktype IN (".$_SESSION['WorkSection'].")
						ORDER BY a.br_no ASC, b.createddate ASC";					
}else{
/*$select_sheet_query = 	"select distinct (a.sheetid), a.rbn, a.staffid, b.*, c.short_name, c.work_order_no, c.work_name, c.tech_sanction, 
						c.name_contractor, c.agree_no, c.computer_code_no, c.worktype, d.staffname , DATE_FORMAT(b.createddate,'%d/%m/%Y') as rec_date 
						from measurementbook_temp a 
						INNER JOIN al_as b ON (b.sheetid = a.sheetid)
						INNER JOIN sheet c ON (c.sheet_id = a.sheetid)
						INNER JOIN staff d ON (d.staffid = a.staffid)
						where a.rbn = b.rbn and c.active = 1 and c.worktype IN (".$_SESSION['WorkSection'].")
						ORDER BY b.createddate ASC";*/
$select_sheet_query = 	"select a.br_no, a.sheetid, a.rbn, a.sent_by as staffid, b.*, c.short_name, c.work_order_no, c.work_name, c.tech_sanction, c.section_type, 
						c.name_contractor, c.agree_no, c.computer_code_no, c.worktype, d.staffname , DATE_FORMAT(b.createddate,'%d/%m/%Y') as rec_date 
						from bill_register a 
						INNER JOIN al_as b ON (b.sheetid = a.sheetid)
						INNER JOIN sheet c ON (c.sheet_id = a.sheetid)
						INNER JOIN staff d ON (d.staffid = a.sent_by)
						where a.reg_status = 'R' and a.rbn = b.rbn and c.active = 1 
						ORDER BY a.br_no ASC, b.createddate ASC";
						//and c.worktype IN (".$_SESSION['WorkSection'].")
}	
//echo $select_sheet_query;exit;				
$select_sheet_sql = mysqli_query($dbConn,$select_sheet_query);
if($select_sheet_sql == true){
	if(mysqli_num_rows($select_sheet_sql)>0){
		$sheet_count = 1;
		while($WALList = mysqli_fetch_object($select_sheet_sql)){
			$WorkAccLevelArr[$WALList->sheetid] = $WALList->al_level;
		}
		$RoleNameArr = array();
		$select_role_query 	= "select role_name, levelid from staffrole where sectionid = ".$_SESSION['staff_section'];
		$select_role_sql 	= mysqli_query($dbConn,$select_role_query);
		if($select_role_sql == true){
			while($RoleList = mysqli_fetch_object($select_role_sql)){
				$RoleNameArr[$RoleList->levelid] = $RoleList->role_name;
			}
		}
	}else{
		$sheet_count = 0;
	}
}else{
	$sheet_count = 0;
}


//print_r($WorkAccLevelArr);exit;
function CheckReturnedMBook($sheetid,$rbn)
{
	global $dbConn;
	$select_returned_query = "select * from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and ( mb_ac = 'SC' OR sa_ac = 'SC')";
	$select_returned_sql = mysqli_query($dbConn,$select_returned_query);
	if($select_returned_sql == true)
	{
		if(mysqli_num_rows($select_returned_sql)>0)
		{
			$count = 1;
		}
		else
		{
			$count = 0;
		}
	}
	else
	{
		$count = 0;
	}
	return $count;
}

/*$select_sheet_query = "select distinct (a.sheetid), a.rbn, b.* from measurementbook_temp a inner join al_as b on (b.sheetid = a.sheetid) where a.rbn = b.rbn";
$select_sheet_sql 	= mysqli_query($dbConn,$select_sheet_query);
if($select_sheet_sql == true){
	
}*/




$RetSheetStr = "";


?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script>
     
//	function find_workname()
//	{		
//		
//		var xmlHttp;
//		var data;
//		var i,j;
//		if(window.XMLHttpRequest) // For Mozilla, Safari, ...
//		{
//			xmlHttp = new XMLHttpRequest();
//		}
//		else if(window.ActiveXObject) // For Internet Explorer
//		{ 
//			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
//		}
//		strURL="find_workname.php?sheetid="+document.form.cmb_work_no.value;
//		xmlHttp.open('POST', strURL, true);
//		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
//		xmlHttp.onreadystatechange = function()
//		{
//			if (xmlHttp.readyState == 4)
//			{
//				data=xmlHttp.responseText;
//				
//				document.form.cmb_zone_name.length=0;
//				var optn1 	= document.createElement("option")
//				optn1.value = "";
//				optn1.text 	= "------------------------ Select Zone Name --------------------------";
//				document.form.cmb_zone_name.options.add(optn1)
//				
//				/*var optnall 	= document.createElement("option")
//				optnall.value 	= "all";
//				optnall.text 	= "All";
//				document.form.cmb_zone_name.options.add(optnall);*/
//				
//				document.form.cmb_staff_name.length=0;
//				var optn2 	= document.createElement("option")
//				optn2.value = "";
//				optn2.text 	= "---------------------------------Select---------------------------------";
//				document.form.cmb_staff_name.options.add(optn2)
//				
//				document.form.cmb_mbook_type.value="";
//				
//				var name=data.split("*");
//				if(data=="")
//				{
//					alert("No Records Found");
//					document.form.workname.value='';	
//				}
//				else
//				{	
//					document.form.workname.value			=	name[0].trim();
//					document.form.txt_workorder_no.value	=	name[2].trim();
//					//document.form.txt_book_no1.value		=	Number(name[1]) + Number(1);
//					//document.form.txt_book_no.value			=	Number(name[1]) + Number(1);
//					//document.form.txt_bookpage_no1.value	=	Number(name[2]) + Number(1);
//					//document.form.txt_bookpage_no.value		=	Number(name[2]) + Number(1);
//					//document.form.txt_rab_no1.value			=	Number(name[3]) + Number(1);
//					//document.form.txt_rbn_no.value			=	Number(name[3]) + Number(1);
//	
//				}
//			}
//		}
//		xmlHttp.send(strURL);	
//	}
	function goBack()
	{
	   	url = "Home.php";
		window.location.replace(url);
	}
	/*function zonename()
    { 
       var xmlHttp;
       var data;
       var i, j;
       if (window.XMLHttpRequest) // For Mozilla, Safari, ...
       {
           xmlHttp = new XMLHttpRequest();
       }
       else if (window.ActiveXObject) // For Internet Explorer
       {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
       }
       strURL = "find_zone_name.php?workorderno=" + document.form.cmb_work_no.value;
       xmlHttp.open('POST', strURL, true);
       xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
       xmlHttp.onreadystatechange = function ()
       {
          	if (xmlHttp.readyState == 4)
          	{
             	data = xmlHttp.responseText;
				
				document.form.cmb_zone_name.length=0;
				var optn1 	= document.createElement("option")
				optn1.value = "";
				optn1.text 	= "------------------------ Select Zone Name --------------------------";
				document.form.cmb_zone_name.options.add(optn1)
				
				var optnall 	= document.createElement("option")
				optnall.value 	= "all";
				optnall.text 	= "All";
				document.form.cmb_zone_name.options.add(optnall)
				
                if (data == "")
                {
                    alert("No Records Found");
                }
                else
                {
                   var name = data.split("*");
                   for(i = 0; i < name.length; i+=2)
                   {
						var optn 	= document.createElement("option")
						optn.value 	= name[i];
						optn.text 	= name[i+1];
						document.form.cmb_zone_name.options.add(optn)
                   }
                }
             }
        }
       xmlHttp.send(strURL);
   }*/
//   	function getRbn()
//    { 
//       var xmlHttp;
//       var data;
//       var i, j;
//       if (window.XMLHttpRequest) // For Mozilla, Safari, ...
//       {
//           xmlHttp = new XMLHttpRequest();
//       }
//       else if (window.ActiveXObject) // For Internet Explorer
//       {
//            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
//       }
//       strURL = "find_mbookdetail_accounts.php?workorderno=" + document.form.cmb_work_no.value + "&TempType=1";
//       xmlHttp.open('POST', strURL, true);
//       xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
//       xmlHttp.onreadystatechange = function ()
//       {
//          	if (xmlHttp.readyState == 4)
//          	{
//             	data = xmlHttp.responseText;
//				document.form.txt_rbn_no.value = "";
//                if (data == "")
//                {
//                    alert("No Records Found");
//                }
//                else
//                {
//					document.form.txt_rbn_no.value = data;
//                }
//             }
//        }
//       xmlHttp.send(strURL);
//   }
//   	function getStaffList()
//    { 
//       var xmlHttp;
//       var data;
//       var i, j;
//	   var mbooktype = document.form.cmb_mbook_type.value;
//	   var rbn 		 = document.form.txt_rbn_no.value;
//	   
//       if (window.XMLHttpRequest) // For Mozilla, Safari, ...
//       {
//           xmlHttp = new XMLHttpRequest();
//       }
//       else if (window.ActiveXObject) // For Internet Explorer
//       {
//            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
//       }
//       strURL = "find_mbookdetail_accounts.php?workorderno=" + document.form.cmb_work_no.value + "&TempType=2&rbn=" + rbn + "&mbooktype=" +mbooktype;
//       xmlHttp.open('POST', strURL, true);
//       xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
//       xmlHttp.onreadystatechange = function ()
//       {
//          	if (xmlHttp.readyState == 4)
//          	{
//             	data = xmlHttp.responseText;
//				
//				document.form.cmb_staff_name.length=0;
//				var optn1 	= document.createElement("option")
//				optn1.value = "";
//				optn1.text 	= "---------------------------------Select---------------------------------";
//				document.form.cmb_staff_name.options.add(optn1)
//				
//				document.form.cmb_zone_name.length=0;
//				var optn2 	= document.createElement("option")
//				optn2.value = "";
//				optn2.text 	= "------------------------ Select Zone Name --------------------------";
//				document.form.cmb_zone_name.options.add(optn2)
//				
//				/*var optnall 	= document.createElement("option")
//				optnall.value 	= "all";
//				optnall.text 	= "All";
//				document.form.cmb_zone_name.options.add(optnall)*/
//				
//                if (data == "")
//                {
//                    alert("No Records Found");
//                }
//                else
//                {
//					var name = data.split("*");
//                   	for(i = 0; i < name.length; i+=2)
//                   	{
//						var optn 	= document.createElement("option")
//						optn.value 	= name[i];
//						optn.text 	= name[i+1];
//						document.form.cmb_staff_name.options.add(optn)
//                   	}
//                }
//             }
//        }
//       xmlHttp.send(strURL);
//   }
   
//   function zonename()
//    { 
//       var xmlHttp;
//       var data;
//       var i, j;
//	   var mbooktype = document.form.cmb_mbook_type.value;
//	   var rbn 		 = document.form.txt_rbn_no.value;
//	   var staffid 	 = document.form.cmb_staff_name.value;
//       if (window.XMLHttpRequest) // For Mozilla, Safari, ...
//       {
//           xmlHttp = new XMLHttpRequest();
//       }
//       else if (window.ActiveXObject) // For Internet Explorer
//       {
//            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
//       }
//       strURL = "find_mbookdetail_accounts.php?workorderno="+document.form.cmb_work_no.value+"&TempType=3&rbn="+rbn+"&mbooktype="+mbooktype+"&staffid="+staffid;
//       xmlHttp.open('POST', strURL, true);
//       xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
//       xmlHttp.onreadystatechange = function ()
//       {
//          	if (xmlHttp.readyState == 4)
//          	{
//             	data = xmlHttp.responseText;
//				//alert(data);
//				document.form.cmb_zone_name.length=0;
//				var optn1 	= document.createElement("option")
//				optn1.value = "";
//				optn1.text 	= "------------------------ Select Zone Name --------------------------";
//				document.form.cmb_zone_name.options.add(optn1)
//				
//				/*var optnall 	= document.createElement("option")
//				optnall.value 	= "all";
//				optnall.text 	= "All";
//				document.form.cmb_zone_name.options.add(optnall)*/
//
//				
//				//alert(data);
//                if (data == "")
//                {
//                    alert("No Records Found");
//                }
//                else
//                {
//					var name = data.split("*");
//                   	for(i = 0; i < name.length; i+=2)
//                   	{
//						var optn 	= document.createElement("option")
//						optn.value 	= name[i];
//						optn.text 	= name[i+1];
//						document.form.cmb_zone_name.options.add(optn)
//                   	}
//                }
//             }
//        }
//       xmlHttp.send(strURL);
//   }
//   
</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
	function ViewMBook(obj)
	{
		var id = obj.id;
		var type = obj.getAttribute("data-type");
		//alert(type);
		//alert(id);
		if(type == "SA"){
			BootstrapDialog.show({
				title: 'Alert Information',
				message: 'Secured Adance is a part of the Abstract Book. You can only view Secured Adance here. If you want to do any action like Forward, Return to EIC, Approve then go to Abstract Mbook and do the same.',
				buttons: [{
					label: 'OK',
					action: function(dialog){
						dialog.close();
						$("#txt_post_id").val(id);
						$("#form_mbook").submit();
					}
				}]
			});
			
		}else{
			$("#txt_post_id").val(id);
			$("#form_mbook").submit();
		}
		
	}
</script>
<style>
	.gradientbg 
	{
	  /* fallback */
	  background-color: #014D62;
	  background: url(images/linear_bg_2.png);
	  background-repeat: repeat-x;
	
	  /* Safari 4-5, Chrome 1-9 */
	  background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#EDEEF1), to(#F8F9FB));
	
	  /* Safari 5.1, Chrome 10+ */
	  background: -webkit-linear-gradient(top, #EDEEF1, #F8F9FB);
	
	  /* Firefox 3.6+ */
	  background: -moz-linear-gradient(top, #EDEEF1, #F8F9FB);
	
	  /* IE 10 */
	  background: -ms-linear-gradient(top, #EDEEF1, #F8F9FB);
	
	  /* Opera 11.10+ */
	  background: -o-linear-gradient(top, #EDEEF1, #F8F9FB);
	}
.blink_me {
  animation: blinker 1s linear infinite;
}
.btn1{
	width:70%;
}
@keyframes blinker {  
  50% { opacity: 0; }
}
</style>
<link rel="stylesheet" href="dashboard/css/verticalTab.css">
<script src="dashboard/js/verticalTab.js"></script>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--==============================header=================================-->
<style>
	.accordionItem {
		height: auto;
		overflow: auto;
	}
	.col-status{
		float: left;
		position: relative;
		min-height: 1px;
		padding-right: 2px;
		padding-left: 2px;
		/*width:16%;*/
		width:250px;
	}
	.well-A{
		background-color:#fff;/*#038BCF*/
		border: 2px solid #02B9E2;/*038BCF*/
		color:#032FAD;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		cursor:pointer;
		border-radius:20px;
		padding:5px 8px 5px 8px;
		font-size:12px;
		margin-bottom: 1px;
	}
	.well-A:hover{
		background-color:#02B9E2;
		border: 2px solid #02B9E2;
		color:#fff;
	}
	.well.active{
		background-color:#02B9E2;
		border: 2px solid #02B9E2;/*#055DAB;*/
		color:#fff;
		pointer-events:none;
	}
</style>
<?php include "Menu.php"; ?>
<!--==============================Content=================================-->
<div class="content">
    <?php include "MainMenu.php"; ?>
   <div class="container_12">
       <div class="grid_12">
            <blockquote class="bq1" style="background-color:#FFFFFF; overflow:auto">
                     <form name="form" id="form_mbook" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <!--<div class="container">-->
							<input type="hidden" name="txt_test" id="txt_test" value="hai">
							<input type="hidden" name="txt_view" id="txt_view" value="<?php echo $_GET['view']; ?>">
							<!--<div class="div1">&nbsp;</div>-->
							<div align="center" style="padding-left:40px;">
								<div class="row smclearrow"></div> 
								<div class="div12 no-padding-lr" align="center">
									<div class="col-status" data-id='A'><div class="well well-sm well-A active"><span class="rlable-pink">List of Works Waiting for Verification</span></div></div>
								</div>
							</div>	
							<div class="div12" style="padding-left:40px;">
							<?php
							$incr = 1;
							if($sheet_count == 1)
							{
								$select_sheet_sql = mysqli_query($dbConn,$select_sheet_query);
								while($SheetList = mysqli_fetch_object($select_sheet_sql))
								{
									$sheetid 	  	= $SheetList->sheetid; 
									$rbn 		  	= $SheetList->rbn; //echo $SheetList->rbn."=".$SheetList->sheetid."<br/>";exit;
									$staffname_mb 	= $SheetList->staffname;
									$staffid_mb   	= $SheetList->staffid;
									$WOStatus   	= $SheetList->status;
									$WorkAccLevel	= $WorkAccLevelArr[$sheetid];
									$LevelArr = array(); $index = 0; $ALLastLevel = 0;
									/*$select_level_query = "(select distinct (levelid) as status from acc_log where sheetid = '$sheetid' and rbn = '$rbn' and sectionid = ".$_SESSION['staff_section'].")
															UNION (select status from al_as where sheetid = '$sheetid' and rbn = '$rbn')";*/
															//echo $select_level_query;exit;
															
									//$select_level_query = "SELECT GROUP_CONCAT( CONCAT(`levelid`,',',`staff_levelids`) ) as levels FROM acc_log where sheetid = '$sheetid' and mtype = 'A' and rbn = '$rbn' and sectionid = ".$_SESSION['staff_section'];
									//$select_level_query = "SELECT GROUP_CONCAT( CONCAT(`levelid`,',',`staff_levelids`) ) as levels FROM acc_log where sheetid = '$sheetid' and mtype = 'A' and rbn = '$rbn' and sectionid = ".$_SESSION['staff_section'];    ////// Modified  : Previously After accept all book, only view option is there. But now need to change after accept all mbook it should disappear from the list.
									//$select_level_query = "SELECT GROUP_CONCAT( CONCAT(`levelid`,',',`staff_levelids`) ) as levels, staff_levelids as currlevel, status as currstatus, levelid as currlevelid FROM acc_log where sheetid = '$sheetid' and mtype = 'A' and rbn = '$rbn' and sectionid = ".$_SESSION['staff_section']." group by sheetid";  /// Commented on 05.12.2020 For Higher level returm to lower level but lower level can't view
									$select_level_query = "SELECT GROUP_CONCAT( CONCAT(`levelid`,',',`staff_levelids`) ) as levels, staff_levelids as currlevel, status as currstatus, levelid as currlevelid FROM acc_log where sheetid = '$sheetid' and (mtype = 'A' OR (levelid = '".$_SESSION['levelid']."' and AC_status = 'R')) and rbn = '$rbn' and sectionid = ".$_SESSION['staff_section']." group by sheetid";
									//$s = "SELECT GROUP_CONCAT(CAST(levelid AS CHAR(15)) SEPARATOR '*') as currlevel FROM acc_log where sheetid = '$sheetid' and mtype = 'A' and rbn = '$rbn' and sectionid = ".$_SESSION['staff_section']." group by sheetid";
									//echo $select_level_query;exit;
									$select_level_sql 	= mysqli_query($dbConn,$select_level_query);
									if($select_level_sql == true){
										$ALList = mysqli_fetch_object($select_level_sql);
										$ALevels 		= $ALList->levels;
										$ALcurrLevel 	= $ALList->currlevel;
										$LevelArr 		= explode(",",$ALevels);
										$ALcurrLevelArr = explode(",",$ALcurrLevel);
										$ALLastLevel 	= end($ALcurrLevelArr);
										$ALcurrStatus 	= $ALList->currstatus;
										$ALCurrLevelid 	= $ALList->currlevelid;
									}
									//echo $sheetid." = ".$_SESSION['levelid']."<br/>";
									//echo $ALevels;//exit;
									$MaxExistLevel = max($LevelArr);
									//if(in_array($_SESSION['levelid'], $LevelArr)){
									//if((in_array($_SESSION['levelid'], $LevelArr))||($MaxExistLevel >= $DecMinHighLevel)){
									if(((in_array($_SESSION['levelid'], $LevelArr))||($MaxExistLevel >= $DecMinHighLevel)) && ($ALLastLevel < $_SESSION['levelid'] || $ALCurrLevelid <= $_SESSION['levelid']) && $ALcurrStatus != "AC"){
									//$select_acc_mb_query = "select * from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and mb_ac = 'SA' ORDER BY mbookno ASC";
									//$select_acc_mb_query = "select * from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and flag = 'RAB' ORDER BY mbookno ASC";
									//$select_acc_mb_query = "select * from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and flag = 'RAB' ORDER BY mbookno ASC";
									$select_acc_mb_query = "select a.*, b.AC_status, b.levelid as acc_level, b.staff_levelids, b.staff_ids from send_accounts_and_civil a 
															inner join acc_log b on (a.sacid = b.linkid) 
															where a.sheetid = '$sheetid' and a.rbn = '$rbn' and b.sheetid = '$sheetid' and b.rbn = '$rbn' and a.flag = 'RAB' 
															ORDER BY mtype, genlevel, a.mbookno ASC";
									
									//echo $select_acc_mb_query."<br/>";//exit;
									$select_acc_mb_sql = mysqli_query($dbConn,$select_acc_mb_query);
									if($select_acc_mb_sql == true)
									{
										$AccMbCount = mysqli_num_rows($select_acc_mb_sql);
										if($AccMbCount>0)
										{
											$IsMemoOfPay = 0;
											$SelectMopQuery = "SELECT memoid FROM memo_payment_accounts_edit WHERE sheetid = '$sheetid' AND rbn = '$rbn'";
											$SelectMopSql = mysqli_query($dbConn,$SelectMopQuery);
											if($SelectMopSql == true){
												if(mysqli_num_rows($SelectMopSql)>0){
													$IsMemoOfPay = 1;
												}
											}
									?>	
										
									<div class="accordion">
										<dl>
											<dt>
												<a href="#accordion<?php echo $sheetid; ?>" id="sheet-<?php echo $sheetid; ?>" aria-expanded="false" aria-controls="accordion<?php echo $TabId; ?>" class="accordion-title accordionTitle js-accordionTrigger blue-bg <?php if((isset($_SESSION['selected_sheet']))&&($_SESSION['selected_sheet'] == $sheetid)){ ?> is-collapsed is-expanded <?php } ?>">
												&nbsp;
												<!--<font style="color:#DF0979; font-weight:bold; background:#fff; border:1px solid #DCDFE3; border-radius:7px; padding:2px;">
												<?php echo $SheetList->work_order_no; ?>
												</font>&nbsp;-->
												<span style="color:#DF0979; font-weight:bold; background:#fff; border-radius:7px; padding:2px; border:2px solid #0122A5;">
												B.R. NO. : <?php echo $SheetList->br_no; ?>
												</span>
												<font style="color:#DF0979; font-weight:bold; background:#fff; border-radius:7px; padding:2px;">
												C.C NO : <?php echo $SheetList->computer_code_no; ?>
												</font>
												&nbsp;&nbsp; 
												<font style="color:#DF0979; font-weight:bold; background:#fff; border-radius:7px; padding:2px;">
												RAB : <?php echo $rbn; ?>
												</font>
												&nbsp;&nbsp; 
												
												<?php 
												if($SectionArr[$SheetList->section_type] != ""){
													echo '<span style="color:#DF0979; font-weight:bold; background:#fff; border-radius:7px; padding:2px; border:2px solid #0122A5;">'.$SectionArr[$SheetList->section_type].'</span>';
												}
												?>
												
												&nbsp;&nbsp;
												<?php echo " : "; ?> 
												<?php echo $SheetList->work_name; ?> 
												<font style="color:#DF0979; font-weight:bold; background:#fff; border-radius:7px; padding:2px;">
												Received Date : <?php echo $SheetList->rec_date; ?>
												</font>
												<font class="test" style="color:#F4003E; background:#edeaea; border-radius:5px; padding:2px; animation: blinker 1s linear infinite;"><i class="fa fa-hand-o-left blink_me" aria-hidden="true" style="padding-top:4px;"></i> Click Here</font>											 
												</a>
											</dt>
											<dd class="accordion-content accordionItem <?php if((isset($_SESSION['selected_sheet']))&&($_SESSION['selected_sheet'] == $sheetid)){ ?> is-expanded animateIn <?php } else{ ?> is-collapsed <?php } ?>" id="accordion<?php echo $sheetid; ?>" aria-hidden="true">
											 	<div align="center">		
										
										
							<?php			
										
										
										
										
										
										
							echo '<table width="100%" align="center" class="acctext table1">';
									//echo '<tr class="gradientbg">';
									//echo '<td> &nbsp; Name of the Work  </td>';
									//echo '<td colspan="6" class="acctextnormal">&nbsp;'.$SheetList->work_name.'</td>';
									//echo '</tr>';
									//echo '<tr class="gradientbg">';
									//echo '<td> &nbsp; Work Order No.  </td>';
									//echo '<td colspan="6" class="acctextnormal">&nbsp;'.$SheetList->work_order_no.'</td>';
									//echo '</tr>';
									echo '<tr>';
									echo '<td width="10%">&nbsp;Description</td>';
									echo '<td width="10%" align="center">&nbsp;MBook No</td>';
									echo '<td width="20%">&nbsp;Zone Name</td>';
									echo '<td width="15%">&nbsp;MBook Type</td>';
									//echo '<td width="20%">&nbsp;Staff Name</td>';
									//echo '<td width="5%" align="center" nowrap="nowrap">&nbsp;RAB No</td>';
									echo '<td width="15%" align="center">&nbsp;&nbsp;Status</td>';
									echo '<td width="10%">&nbsp;</td>';
									echo '</tr>';
											$row = 1; $RowSpanFlag = 0; $Abststatus = ""; $AbstCurrStatus = "";//$IsSecAdv = 0; $IsEscal = 0; 
											while($AccMBList = mysqli_fetch_object($select_acc_mb_sql))
											{
												$sacid = $AccMBList->sacid; $IsSecAdvStr = ''; $IsEscalStr = '';
												$staffid_mb = $AccMBList->civil_staffid;
												if($AccMBList->mtype == 'G'){ $mtype_str = "General "; $mtype = "G";}
												if($AccMBList->mtype == 'S'){ $mtype_str = "Steel "; $mtype = "S";}
												$SC_Count = CheckReturnedMBook($sheetid,$rbn);
												
												
												$StaffLevelIds 	= $AccMBList->staff_levelids;
												$StaffLevelArr 	= explode(",",$StaffLevelIds);
												$StaffIds 		= $AccMBList->staff_ids;
												$CurrLevelId	= $AccMBList->acc_level;
												
												$LastLevel 		= end($StaffLevelArr);
												
												$WoAccLevelStr  = $WorkAccLevel;
												$ExpWorkAccLevel= explode(",",$WoAccLevelStr);
												$MinLevel 		= min($ExpWorkAccLevel); 
												$MaxLevel 		= max($ExpWorkAccLevel);
												
												$SelfStatus 	= $AccMBList->AC_status;
												
												if($_SESSION['levelid'] > $MinLevel){
													$IsMemoOfPay = 1;
												}
												$MemoOfPayStr = "";
												if($IsMemoOfPay == 1){
													if($AccMBList->mtype == 'A'){
														$MemoOfPayStr = " & Memo of Payment";
													}
												}
												//echo $CurrLevelId;exit;
												$status = "";
												if(($AccMBList->genlevel == 'staff')&&($AccMBList->mb_ac == 'AC')){
													$status = "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:14px;' aria-hidden='true'></i> Verified & Accepted</span>";
													$CurrStatus = "Over All Verification Completed";
												}else if(($AccMBList->genlevel == 'composite')&&($AccMBList->sa_ac == 'AC')){
													$status = "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:14px;' aria-hidden='true'></i> Verified & Accepted</span>";
													$CurrStatus = "Over All Verification Completed";
												}else if(($AccMBList->genlevel == 'abstract')&&($AccMBList->ab_ac == 'AC')){
													$status = "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:14px;' aria-hidden='true'></i> Verified & Accepted</span>";
													$CurrStatus = "Over All Verification Completed";
													$Abststatus = $status;
													$AbstCurrStatus = $CurrStatus;
												}else if(($AccMBList->genlevel == 'staff')&&($AccMBList->mb_ac == 'SC')){
													$status = "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Rejected To CIVIL</span>";
													$CurrStatus = "Returned to Civil Section";
												}else if(($AccMBList->genlevel == 'composite')&&($AccMBList->sa_ac == 'SC')){
													$status = "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Rejected To CIVIL</span>";
													$CurrStatus = "Returned to Civil Section";
												}else if(($AccMBList->genlevel == 'abstract')&&($AccMBList->ab_ac == 'SC')){
													$status = "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Rejected To CIVIL</span>";
													$CurrStatus = "Returned to Civil Section";
													$Abststatus = $status;
													$AbstCurrStatus = $CurrStatus;
												}else{
													//echo $status;exit;
													if($CurrLevelId != 0){
														if(($CurrLevelId == $MinLevel)&&($SelfStatus == 'R') && ($LastLevel == $CurrLevelId)){
															$status = "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Rejected To CIVIL</span>";
															$CurrStatus = "This MBook is Waiting in CIVIL Section";
														}else{
															//echo $WoAccLevelStr;exit;
															$status =  AccountsMbookStatus($WoAccLevelStr,$StaffLevelIds,$CurrLevelId,$SelfStatus);
															$CurrStatus = "This MBook is Waiting in '".$RoleNameArr[$CurrLevelId]."' Level";
															//$CurrStatus = $WoAccLevelStr." = ".$StaffLevelIds." = ".$CurrLevelId." = ".$_SESSION['levelid'];
														}
														if($AccMBList->mtype == 'A'){
															$Abststatus = $status;
															$AbstCurrStatus = $CurrStatus;
														}
													}
													else{ //echo $MinLevel; exit;
														if($_SESSION['levelid'] == $MinLevel){
															$status = "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Rejected To CIVIL</span>";
														}else{
															$status = "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Return to Previous level</span>";
														}
														$CurrStatus = "This MBook is Waiting in CIVIL Section";
														
														if($AccMBList->mtype == 'A'){
															$Abststatus = $status;
															$AbstCurrStatus = $CurrStatus;
														}
														
													}
												}
												
												
												//$status =  AccountsMbookStatus($WoAccLevelStr,$StaffLevelIds,$CurrLevelId,$SelfStatus);//exit;
												
												/*if($LastLevel == $CurrLevelId){
													$SelfStatus = $AccMBList->AC_status;
												}else{
													//if(){
													//}WorkAccLevel
												}*/
												
												
												if($AccMBList->genlevel == 'staff')
												{
													if($AccMBList->mb_ac == 'SA')
													{
														//$status = " <span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><font style='color:red; font-size:15px; font-weight:bold;' aria-hidden='true'>X</font> Not yet verified</span>";
													}
													else if($AccMBList->mb_ac == 'SC')
													{
														$RetSheetStr .= $sheetid.",";
														//$status = " <span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Returned to Civil</span>";
													}
													else
													{
														//$status = " <span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Verified</span>";
													}
													$link = "ViewMBook(this)";
													$title = "";
													$tooltipClass = "";
												}
												if($AccMBList->genlevel == 'composite')
												{
													if($AccMBList->sa_ac == 'SA')
													{
														//$status = " <span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><font style='color:red; font-size:15px; font-weight:bold;' aria-hidden='true'>X</font> Not yet verified</span>";
													}
													else if($AccMBList->sa_ac == 'SC')
													{
														$RetSheetStr .= $sheetid.",";
														//$status = " <span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Returned to Civil</span>";
													}
													else
													{
														//$status = " <span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Verified</span>";
													}
													$mtype_str = "Sub-Abstract ";
													$mtype = "SABS";
													if($SC_Count == 1)
													{
														$link = "";
														$tooltipClass = "tooltipleft";
														$title = "MBook returned to Civil. Changes may occur. So you can't able to view Sub-Abstract";
													}
													else
													{
														$link = "ViewMBook(this)";
														$tooltipClass = "";
														$title = "";
													}
												}
												if($AccMBList->genlevel == 'abstract')
												{
													if($AccMBList->ab_ac == 'SA')
													{
														//$status = " <span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><font style='color:red; font-size:15px; font-weight:bold;' aria-hidden='true'>X</font> Not yet verified</span>";
														//$status = " <i class='fa fa-times' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Not yet verified";
													}
													else if($AccMBList->ab_ac == 'SC')
													{
														$RetSheetStr .= $sheetid.",";
														//$status = " <span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Returned to Civil</span>";
													}
													else
													{
														//$status = " <span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Verified</span>";
													}
													$mtype_str = "Abstract ";
													$mtype = "ABS";
													if($SC_Count == 1)
													{
														$link = "";
														$tooltipClass = "tooltipleft";
														$title = "MBook returned to Civil. Changes may occur. So you can't able to view Abstract";
													}
													else
													{
														$link = "ViewMBook(this)";
														$tooltipClass = "";
														$title = "";
													}
													
													/*if($IsSecAdv == 0){
														$SelectSaQuery = "SELECT * FROM secured_advance WHERE sheetid = '$sheetid' AND rbn = '$rbn'";
														$SelectSaSql = mysqli_query($dbConn,$SelectSaQuery);
														if($SelectSaSql == true){
															if(mysqli_num_rows($SelectSaSql)>0){
																$IsSecAdv = 1;
																$IsSecAdvStr = "</br>&nbsp;<a href='SecuredAdvanceView.php?shid=".$sheetid."&rbn=".$rbn."'><u>Secured Advance</u></a>";
															}
														}
													}*/
													/*if($IsEscal == 0){
														$SelectEscQuery = "SELECT * FROM escalation WHERE sheetid = '$sheetid' AND rbn = '$rbn'";
														$SelectEscSql = mysqli_query($dbConn,$SelectEscQuery);
														if($SelectEscSql == true){
															if(mysqli_num_rows($SelectEscSql)>0){
																$IsEscal = 1;
																$IsEscalStr = "</br>&nbsp;<span class='ViewEsc' style='color:red; cursor:pointer' data-sheetid = '".$sheetid."' data-rbn = '".$rbn."'><u>Escalation</u></span>";
															}
														}
													}*/
												}
												$zone_name = getzonename($sheetid,$AccMBList->zone_id);
												$rowid = ""; 
												$rowid = $incr; //$sheetid.$row;
												$incr++;
												$dataStr = "";
												$dataStr = $sheetid."*".$AccMBList->zone_id."*".$staffid_mb."*".$mtype."*".$rbn."*".$sacid;
												if($RowSpanFlag == 0){
													echo '<tr class="acctextnormal">';
													echo '<td rowspan="'.$AccMbCount.'">&nbsp;Measurements</td>';
												}else{
													echo '<tr class="acctextnormal">';
												}
												echo '<td align="center">&nbsp;'.$AccMBList->mbookno.'</td>';
												echo '<td>&nbsp;'.$zone_name.'</td>';
												echo '<td>&nbsp;'.$mtype_str.$MemoOfPayStr.'</td>';
												//echo '<td>&nbsp;'.$staffname_mb.'</td>';
												//echo '<td align="center">&nbsp;'.$rbn.'</td>';
												echo '<td>&nbsp;'.$status.'&nbsp;<i class="fa fa-info-circle" aria-hidden="true" style="padding-top:3px; font-size:20px; color:#7A7A7A" title="'.$CurrStatus.'"></i></td>';
												echo '<td align="center">';
												echo '<a onClick="'.$link.'" class="'.$tooltipClass.'" title="'.$title.'" data-type="" id="'.$rowid.'" style="background-color:#FFFFF; border:1px solid #FFFFF; padding:1px 8px 1px 8px;cursor:pointer;"><span class="btn1 btn1-default btn1-sm" style="height:15px; padding:4px 10px; line-height:1.1;"><img src="images/book_open3.png" style="padding-top:0px;" height="18"> View</span></a>';
												echo '</td>';
												echo '</tr>';
											
											
												echo '<input type="hidden" name="txt_data_'.$rowid.'" id="txt_data_'.$rowid.'" value="'.$dataStr.'">';
											$row++; $RowSpanFlag++;
											}
											
											$SelectSaQuery = "SELECT * FROM secured_advance WHERE sheetid = '$sheetid' AND rbn = '$rbn'";
											$SelectSaSql = mysqli_query($dbConn,$SelectSaQuery);
											if($SelectSaSql == true){
												if(mysqli_num_rows($SelectSaSql)>0){
													$IsSecAdv = 1;
													$SecAdvList = mysqli_fetch_object($SelectSaSql);
													$rowid = ""; 
													$rowid = $incr; //$sheetid.$row;
													$incr++;
													$dataStr = "";
													$dataStr = $sheetid."**".$staffid_mb."*SEC*".$rbn."*".$sacid;
													echo '<tr class="acctextnormal">';
													echo '<td align="center">Secured Advance</td>';
													echo '<td align="center">'.$AccMBList->mbookno.'</td>';
													echo '<td>&nbsp;</td>';
													echo '<td>&nbsp;Secured Advance</td>';
													echo '<td>&nbsp;'.$Abststatus.'&nbsp;<i class="fa fa-info-circle" aria-hidden="true" style="padding-top:3px; font-size:20px; color:#7A7A7A" title="'.$AbstCurrStatus.'"></i></td>';
													echo '<td align="center">';
													echo '<a onClick="'.$link.'" class="'.$tooltipClass.'" title="'.$title.'" data-type="SA" id="'.$rowid.'" style="background-color:#FFFFF; border:1px solid #FFFFF; padding:1px 8px 1px 8px;cursor:pointer;"><span class="btn1 btn1-default btn1-sm" style="height:15px; padding:4px 10px; line-height:1.1;"><img src="images/book_open3.png" style="padding-top:0px;" height="18"> View</span></a>';
													echo '</td>';
													echo '</tr>';
													echo '<input type="hidden" name="txt_data_'.$rowid.'" id="txt_data_'.$rowid.'" value="'.$dataStr.'">';
													$row++;
												}
											}
											$EscRowSpanFlag = 0;
											$SelectEscQuery = "SELECT * FROM mymbook WHERE sheetid = '$sheetid' AND rbn = '$rbn' AND (mtype = 'CC' OR mtype = 'SC' OR mtype = 'EA' OR mtype = 'E') ORDER BY mtype DESC";
											$SelectEscSql = mysqli_query($dbConn,$SelectEscQuery);
											if($SelectEscSql == true){
												$EscMbCnt = mysqli_num_rows($SelectEscSql);
												if($EscMbCnt > 0){
													while($EscList = mysqli_fetch_object($SelectEscSql)){
														$rowid = ""; 
														$rowid = $incr; //$sheetid.$row;
														$incr++;
														$dataStr = "";
														$dataStr = $sheetid."*".$EscList->quarter."*".$staffid_mb."*".$EscList->mtype."*".$rbn."*".$EscList->esc_id;
														if($EscRowSpanFlag == 0){
															echo '<tr class="acctextnormal">';
															echo '<td rowspan="'.$EscMbCnt.'">&nbsp;Escalation</td>';
														}else{
															echo '<tr class="acctextnormal">';
														}
														if($EscList->mtype == "CC"){
															$EstMbStr = "Cement Consumption";
														}else if($EscList->mtype == "SC"){
															$EstMbStr = "Steel Consumption";
														}else if($EscList->mtype == "EA"){
															$EstMbStr = "Escalation Abstract";
														}else if($EscList->mtype == "E"){
															$EstMbStr = "Escalation";
														}else{
															$EstMbStr = "";
														}
														echo '<td align="center">'.$EscList->mbno.'</td>';
														echo '<td>&nbsp;</td>';
														echo '<td>&nbsp;'.$EstMbStr.'</td>';
														echo '<td>&nbsp;'.$status.'&nbsp;<i class="fa fa-info-circle" aria-hidden="true" style="padding-top:3px; font-size:20px; color:#7A7A7A" title="'.$CurrStatus.'"></i></td>';
														echo '<td align="center">';
														echo '<a onClick="'.$link.'" class="'.$tooltipClass.'" title="'.$title.'" data-type="" id="'.$rowid.'" style="background-color:#FFFFF; border:1px solid #FFFFF; padding:1px 8px 1px 8px;cursor:pointer;"><span class="btn1 btn1-default btn1-sm" style="height:15px; padding:4px 10px; line-height:1.1;"><img src="images/book_open3.png" style="padding-top:0px;" height="18"> View</span></a>';
														echo '</td>';
														echo '</tr>';
														echo '<input type="hidden" name="txt_data_'.$rowid.'" id="txt_data_'.$rowid.'" value="'.$dataStr.'">';
														$EscRowSpanFlag++; $row++;
													}
												}
											}
											
											
										echo '</table>';
							?>
								</div>
											 </dd>
										</dl>
									</div>
							<?php
										}
										else
										{
											//echo '<tr class="acctextnormal">';
											//echo '<td colspan="7" align="center" style="color:#A8A8A8;"> No Measurement Book Found...</td>';
											//echo '</tr>';
										}
									}
									
									
									}
									
							/*echo '&nbsp';*/
								}
							}
							?>
						<!--</div>-->
						<div>&nbsp;</div>
	 					<div style="text-align:center; height:45px; line-height:45px; color:#07DCED" class="printbutton">
							<div class="buttonsection">
							<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />
							</div>
							<!--<div class="buttonsection">
							<input type="submit" class="btn" data-type="submit" value=" View " name="submit" id="submit"   />
							</div>-->
						</div>
						<input type="hidden" name="txt_post_id" id="txt_post_id">
						<input type="hidden" name="txt_ret_civil_str" id="txt_ret_civil_str" value="<?php echo rtrim($RetSheetStr,','); ?>">
						<input type="hidden" name="txt_focus_work" id="txt_focus_work" value="<?php if(isset($_SESSION['selected_sheet'])){ echo $_SESSION['selected_sheet']; } ?>">
						</div>
						<!--<div class="div1">&nbsp;</div>-->
     				</form>
   			</blockquote>
  		</div>
  	</div>
</div>
<script>
	$(document).ready(function(){
		var RetSheetStr = $('#txt_ret_civil_str').val();
		if(RetSheetStr != ""){
			var SplitRetSheetStr = RetSheetStr.split(',');
			var i = 0;
			for(i=0; i<SplitRetSheetStr.length; i++){
				var SheetRowId = SplitRetSheetStr[i];
				//$("#sheet-"+SheetRowId).css("background-color", "#FA1B1B");
				//$("#sheet-"+SheetRowId).css("color", "#fff");
			}
		}
		
		
		$("body").on("click",".ViewEsc", function(event){
			var WorkId 	= $(this).attr("data-sheetid");
			var Rbn 	= $(this).attr("data-rbn");
			var MsgStr  = "";
				MsgStr 	+= "<div class='row'>";
				MsgStr 	+= "<div class='div4'>&nbsp;</div><div class='div4'><a href=''>Cement Consumption</a></div><div class='div4'>&nbsp;</div>";
				MsgStr 	+= "<div class='div4'>&nbsp;</div><div class='div4'><a href=''>Steel Consumption</a></div><div class='div4'>&nbsp;</div>";
				MsgStr 	+= "<div class='div4'>&nbsp;</div><div class='div4'><a href=''>Escalation Abstract Consumption</a></div><div class='div4'>&nbsp;</div>";
				MsgStr 	+= "<div class='div4'>&nbsp;</div><div class='div4'><a href=''>Escalation</a></div><div class='div4'>&nbsp;</div>";
				MsgStr 	+= "</div>";
			BootstrapDialog.show({
				title: 'Button Hotkey',
				message: MsgStr,
				buttons: [{
					label: ' OK ',
					cssClass: 'btn-primary',
					action: function() {
						
					}
				}]
			});
		});
		
		$(window).load(function() {
			var FocusWork = $("#txt_focus_work").val(); //alert(FocusWork);
			$("#accordion"+FocusWork).focus();
		});
		
	});
</script>
<!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
</body>
</html>
							<?php
							/*echo '<table width="100%" align="center" class="acctext table1">';F14F74
							if($sheet_count == 1)
							{
								while($SheetList = mysqli_fetch_object($select_sheet_sql))
								{
									$sheetid 	  = $SheetList->sheetid;
									$rbn 		  = $SheetList->rbn;
									$staffname_mb = $SheetList->staffname;
									echo '<tr class="acctextnormal"><td colspan="4">'.$SheetList->work_name.'</td></tr>';
									//echo '<tr class="acctextnormal">';
									//echo '<td colspan="2" align="center">';
									//echo " Staff Name : ".$staffname_mb;
									//echo '</td>';
									//echo '<td colspan="2" align="center">';
									//echo " RAB No. : ".$rbn;
									//echo '</td>';
									//echo '</tr>';
									//$select_acc_mb_query = "select * from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and mb_ac = 'SA' ORDER BY mbookno ASC";
									$select_acc_mb_query = "select * from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' ORDER BY mbookno ASC";
									$select_acc_mb_sql = mysqli_query($dbConn,$select_acc_mb_query);
									if($select_acc_mb_sql == true)
									{
										if(mysqli_num_rows($select_acc_mb_sql)>0)
										{
											$td_count = 0;
											echo '<tr>';
											while($AccMBList = mysqli_fetch_object($select_acc_mb_sql))
											{
												if($AccMBList->mtype == 'G'){ $mtype_str = "General "; }
												if($AccMBList->mtype == 'S'){ $mtype_str = "Steel "; }
												if($AccMBList->mtype == 'A'){ $mtype_str = "Abstract "; }
												if($AccMBList->genlevel == 'staff')
												{
													if($AccMBList->mb_ac == 'SA')
													{
														$status = " ";//<i class='fa fa-times' style='color:red; font-size:19px;' aria-hidden='true'></i> Not yet verified";
													}
													else
													{
														$status = " <i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Verified";
													}
												}
												if($AccMBList->genlevel == 'composite')
												{
													if($AccMBList->sa_ac == 'SA')
													{
														$status = " ";//<i class='fa fa-times' style='color:red; font-size:19px;' aria-hidden='true'></i> Not yet verified";
													}
													else
													{
														$status = " <i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Verified";
													}
												}
												if($AccMBList->genlevel == 'abstract')
												{
													if($AccMBList->ab_ac == 'SA')
													{
														$status = "";//" <i class='fa fa-times' style='color:red; font-size:19px;' aria-hidden='true'></i> Not yet verified";
													}
													else
													{
														$status = " <i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Verified";
													}
												}
												if($td_count == 4)
												{
													echo '</tr>';
													echo '<tr>';
													$td_count = 0;
												}
												echo '<td>';
												echo '<div style="background-color:#E5E6E7; height:30px; text-align:center; line-height:30px;" class="gradientbg">';
												echo $AccMBList->mbookno;
												echo '</div>';
												echo '<div style="background-color:#FFFFFF; font-weight:normal; height:23px;">&nbsp;Zone Name : '.$AccMBList->zone_id.'</div>';
												echo '<div style="background-color:#FFFFFF; font-weight:normal; height:23px;">&nbsp;MBook Type: '.$mtype_str.'</div>';
												echo '<div style="background-color:#FFFFFF; font-weight:normal;">&nbsp;Staff Name : '.$staffname_mb.'</div>';
												echo '<div style="background-color:#FFFFFF; font-weight:normal;">&nbsp;RAB No.&emsp;&nbsp;&nbsp;&nbsp;: '.$rbn.'</div>';
												echo '<div style="background-color:#FFFFFF; font-weight:normal;">&nbspStatus :'.$status.'</div>';
												echo '<div style="background-color:#FFFFFF; font-weight:normal; text-align:right;">';
												echo '<a href="">View&nbsp&nbsp</a>';
												echo '</div>';
												echo '</td>';
												
												$td_count++;
											}
											echo '</tr>';
										}
									}
								}
							}
							echo '</table>';*/
							?>
