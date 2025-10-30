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
	if($select_locked_mb_query != "")
	{
		$select_locked_mb_sql 	= mysql_query($select_locked_mb_query);
		if($select_locked_mb_sql == true)
		{
			$LockList 		= mysql_fetch_object($select_locked_mb_sql);
			$locked_status 	= $LockList->locked_status;
			$locked_staff 	= $LockList->locked_staff;
		}
	}
	//echo $view_url;exit;
	if(($locked_status == "locked") && ($staffid_acc != $locked_staff))
	{
		header('Location: MeasurementBook_Locked_Accounts.php?locked_staff='.$locked_staff."&view=".$view);
	}
	else
	{
		$update_locked_sql = mysql_query($update_locked_query);
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
$select_level_sql 	=  mysql_query($select_level_query);
if($select_level_sql == true){
	$LevelArr = mysql_fetch_array($select_level_sql);
}
echo $select_level_query;
print_r($LevelArr);exit;*/

//$select_level_query = "select distinct (a.sheetid), a.rbn, b.*, c.* from measurementbook_temp a inner join al_as b on (b.sheetid = a.sheetid)";

//echo $acc_levelid;exit;


$WorkAccLevelArr = array();
//$_SESSION['RetCivilSheet'] = '26,39,52,98,149,163,163';
if($_GET['view'] == "r"){
$select_sheet_query = 	"select distinct (a.sheetid), a.rbn, a.staffid, b.*, c.short_name, c.work_order_no, c.work_name, c.tech_sanction, 
						c.name_contractor, c.agree_no, c.computer_code_no, c.worktype, d.staffname , DATE_FORMAT(b.createddate,'%d/%m/%Y') as rec_date 
						from measurementbook_temp a 
						INNER JOIN al_as b ON (b.sheetid = a.sheetid)
						INNER JOIN sheet c ON (c.sheet_id = a.sheetid)
						INNER JOIN staff d ON (d.staffid = a.staffid)
						where a.rbn = b.rbn and c.active = 1 and a.sheetid IN (".$_SESSION['RetCivilSheet'].") and c.worktype IN (".$_SESSION['WorkSection'].")
						ORDER BY b.createddate ASC";						
}else{
$select_sheet_query = 	"select distinct (a.sheetid), a.rbn, a.staffid, b.*, c.short_name, c.work_order_no, c.work_name, c.tech_sanction, 
						c.name_contractor, c.agree_no, c.computer_code_no, c.worktype, d.staffname , DATE_FORMAT(b.createddate,'%d/%m/%Y') as rec_date 
						from measurementbook_temp a 
						INNER JOIN al_as b ON (b.sheetid = a.sheetid)
						INNER JOIN sheet c ON (c.sheet_id = a.sheetid)
						INNER JOIN staff d ON (d.staffid = a.staffid)
						where a.rbn = b.rbn and c.active = 1 and c.worktype IN (".$_SESSION['WorkSection'].")
						ORDER BY b.createddate ASC";
}	
//echo $select_sheet_query;//exit;				
$select_sheet_sql = mysql_query($select_sheet_query);
if($select_sheet_sql == true){
	if(mysql_num_rows($select_sheet_sql)>0){
		$sheet_count = 1;
		while($WALList = mysql_fetch_object($select_sheet_sql)){
			$WorkAccLevelArr[$WALList->sheetid] = $WALList->al_level;
		}
		$RoleNameArr = array();
		$select_role_query 	= "select role_name, levelid from staffrole where sectionid = ".$_SESSION['staff_section'];
		$select_role_sql 	= mysql_query($select_role_query);
		if($select_role_sql == true){
			while($RoleList = mysql_fetch_object($select_role_sql)){
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
	$select_returned_query = "select * from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and ( mb_ac = 'SC' OR sa_ac = 'SC')";
	$select_returned_sql = mysql_query($select_returned_query);
	if($select_returned_sql == true)
	{
		if(mysql_num_rows($select_returned_sql)>0)
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
$select_sheet_sql 	= mysql_query($select_sheet_query);
if($select_sheet_sql == true){
	
}*/




$RetSheetStr = "";


?>
<?php require_once "Header.html"; ?>
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
	   	url = "MyViewAccounts.php";
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
		//alert(id);
		$("#txt_post_id").val(id);
		$("#form_mbook").submit();
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
</style>
<?php include "Menu.php"; ?>
<!--==============================Content=================================-->
<div class="content">
                <div class="title">Measurement Books List</i></div>
   <div class="container_12">
       <div class="grid_12">
            <blockquote class="bq1" style="background-color:#FFFFFF; overflow:auto">
					<br/>
                     <form name="form" id="form_mbook" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <!--<div class="container">-->
							<input type="hidden" name="txt_test" id="txt_test" value="hai">
							<input type="hidden" name="txt_view" id="txt_view" value="<?php echo $_GET['view']; ?>">
							
							<?php
							$incr = 1;
							if($sheet_count == 1)
							{
								$select_sheet_sql = mysql_query($select_sheet_query);
								while($SheetList = mysql_fetch_object($select_sheet_sql))
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
									$select_level_sql 	= mysql_query($select_level_query);
									if($select_level_sql == true){
										$ALList = mysql_fetch_object($select_level_sql);
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
									
									//echo $sheetid."<br/>";//exit;
									$select_acc_mb_sql = mysql_query($select_acc_mb_query);
									if($select_acc_mb_sql == true)
									{
										if(mysql_num_rows($select_acc_mb_sql)>0)
										{
							?>			
									<div class="accordion">
										<dl>
											<dt>
												<a href="#accordion<?php echo $sheetid; ?>" id="sheet-<?php echo $sheetid; ?>" aria-expanded="false" aria-controls="accordion<?php echo $TabId; ?>" class="accordion-title accordionTitle js-accordionTrigger blue-bg <?php if((isset($_SESSION['selected_sheet']))&&($_SESSION['selected_sheet'] == $sheetid)){ ?> is-collapsed is-expanded <?php } ?>">
												&nbsp;
												<font style="color:#DF0979; font-weight:bold; background:#edeaea; border-radius:7px; padding:2px;">
												<?php echo $SheetList->work_order_no; ?>
												</font>&nbsp;
												<font style="color:#DF0979; font-weight:bold; background:#edeaea; border-radius:7px; padding:2px;">
												C.C NO : <?php echo $SheetList->computer_code_no; ?>
												</font>
												&nbsp;&nbsp; 
												<?php echo " : "; ?> 
												<?php echo $SheetList->work_name; ?> 
												<font style="color:#DF0979; font-weight:bold; background:#edeaea; border-radius:7px; padding:2px;">
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
									echo '<td width="10%" align="center">&nbsp;MBook No</td>';
									//echo '<td width="20%">&nbsp;Zone Name</td>';
									echo '<td width="15%">&nbsp;MBook Type</td>';
									echo '<td width="20%">&nbsp;Staff Name</td>';
									echo '<td width="5%" align="center" nowrap="nowrap">&nbsp;RAB No</td>';
									echo '<td width="15%" align="center">&nbsp;&nbsp;Status</td>';
									echo '<td width="10%">&nbsp;</td>';
									echo '</tr>';
											$row = 1;
											while($AccMBList = mysql_fetch_object($select_acc_mb_sql))
											{
												$sacid = $AccMBList->sacid;
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
												//echo $CurrLevelId;exit;
												$status = "";
												if(($AccMBList->genlevel == 'staff')&&($AccMBList->mb_ac == 'AC')){
													$status = "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Verified & Accepted</span>";
													$CurrStatus = "Over All Verification Completed";
												}else if(($AccMBList->genlevel == 'composite')&&($AccMBList->sa_ac == 'AC')){
													$status = "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Verified & Accepted</span>";
													$CurrStatus = "Over All Verification Completed";
												}else if(($AccMBList->genlevel == 'abstract')&&($AccMBList->ab_ac == 'AC')){
													$status = "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Verified & Accepted</span>";
													$CurrStatus = "Over All Verification Completed";
												}else if(($AccMBList->genlevel == 'staff')&&($AccMBList->mb_ac == 'SC')){
													$status = "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Rejected To CIVIL</span>";
													$CurrStatus = "Returned to Civil Section";
												}else if(($AccMBList->genlevel == 'composite')&&($AccMBList->sa_ac == 'SC')){
													$status = "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Rejected To CIVIL</span>";
													$CurrStatus = "Returned to Civil Section";
												}else if(($AccMBList->genlevel == 'abstract')&&($AccMBList->ab_ac == 'SC')){
													$status = "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Rejected To CIVIL</span>";
													$CurrStatus = "Returned to Civil Section";
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
													}
													else{ //echo $MinLevel; exit;
														if($_SESSION['levelid'] == $MinLevel){
															$status = "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Rejected To CIVIL</span>";
														}else{
															$status = "<span class='btn1 btn1-default btn1-sm' style='height:15px; padding:4px 10px; line-height:1;'><i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Return to Previous level</span>";
														}
														$CurrStatus = "This MBook is Waiting in CIVIL Section";
														
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
												}
												$zone_name = getzonename($sheetid,$AccMBList->zone_id);
												$rowid = ""; 
												$rowid = $incr; //$sheetid.$row;
												$incr++;
												$dataStr = "";
												$dataStr = $sheetid."*".$AccMBList->zone_id."*".$staffid_mb."*".$mtype."*".$rbn."*".$sacid;
											echo '<tr class="acctextnormal">';
												echo '<td align="center">&nbsp;'.$AccMBList->mbookno.'</td>';
												//echo '<td>&nbsp;'.$zone_name.'</td>';
												echo '<td>&nbsp;'.$mtype_str.'</td>';
												echo '<td>&nbsp;'.$staffname_mb.'</td>';
												echo '<td align="center">&nbsp;'.$rbn.'</td>';
												echo '<td>&nbsp;'.$status.'&nbsp;<i class="fa fa-info-circle" aria-hidden="true" style="padding-top:3px; font-size:20px; color:#7A7A7A" title="'.$CurrStatus.'"></i></td>';
												/*echo '<td>&nbsp; -- -- -- -- </td>';*/
												echo '<td align="center">';
												//echo '<a onClick="ViewMBook(this)" id="'.$rowid.'" style="background-color:#FFFFF; border:1px solid #FFFFF; padding:1px 8px 1px 8px;cursor:pointer;"><img src="images/book_open.png" style="padding-top:3px;" height="15"> View</a>';
												echo '<a onClick="'.$link.'" class="'.$tooltipClass.'" title="'.$title.'" id="'.$rowid.'" style="background-color:#FFFFF; border:1px solid #FFFFF; padding:1px 8px 1px 8px;cursor:pointer;"><span class="btn1 btn1-default btn1-sm" style="height:15px; padding:4px 10px; line-height:1.1;"><img src="images/book_open3.png" style="padding-top:0px;" height="18"> View</span></a>';
												echo '</td>';
											echo '</tr>';
											echo '<input type="hidden" name="txt_data_'.$rowid.'" id="txt_data_'.$rowid.'" value="'.$dataStr.'">';
											$row++;
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
								while($SheetList = mysql_fetch_object($select_sheet_sql))
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
									$select_acc_mb_sql = mysql_query($select_acc_mb_query);
									if($select_acc_mb_sql == true)
									{
										if(mysql_num_rows($select_acc_mb_sql)>0)
										{
											$td_count = 0;
											echo '<tr>';
											while($AccMBList = mysql_fetch_object($select_acc_mb_sql))
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
