<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
include "library/common.php";
$msg = '';
$staffid = $_SESSION['sid'];
$staffid_acc = $_SESSION['sid_acc'];
//echo $staffid_acc;exit;
$userid = $_SESSION['userid'];
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
	//echo "hai";exit;
	$post_id = $_POST["txt_post_id"];//view_mbook
	$dataStr = $_POST['txt_data_'.$post_id];
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
	if($mtype == "CC")
	{
		$select_locked_mb_query = "select locked_status,locked_staff from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and genlevel = 'staff' and mtype = 'CC'";
		$view_url = "MBook_Print_staff_wise_Accounts.php?workno=".$sheetid."&linkid=".$linkid;
		$update_locked_query = "update send_accounts_and_civil set locked_status = 'locked', locked_staff = '$staffid_acc' where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and mtype = 'CC' and genlevel = 'staff'";
	}
	if($mtype == "SC")
	{
		$select_locked_mb_query = "select locked_status,locked_staff from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and genlevel = 'staff' and mtype = 'SC'";
		$view_url = "SteelMBook_Print_staff_wise_Accounts.php?workno=".$sheetid."&linkid=".$linkid;
		$update_locked_query = "update send_accounts_and_civil set locked_status = 'locked', locked_staff = '$staffid_acc' where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and mtype = 'SC' and genlevel = 'staff'";
	}
	if($mtype == "E")
	{
		$select_locked_mb_query = "select locked_status,locked_staff from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and genlevel = 'staff' and mtype = 'E'";
		$view_url = "GeneralMBook_Composite_Print_Accounts.php?workno=".$sheetid."&linkid=".$linkid;
		$update_locked_query = "update send_accounts_and_civil set locked_status = 'locked', locked_staff = '$staffid_acc' where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and mtype = 'E' and genlevel = 'staff'";
	}
	/*if($mtype == "ABS")
	{
		$select_locked_mb_query = "select locked_status,locked_staff from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and genlevel = 'abstract' and mtype = 'A'";
		$view_url = "AbstMBook_Print_Common_Accounts.php?workno=".$sheetid."&linkid=".$linkid;
		$update_locked_query = "update send_accounts_and_civil set locked_status = 'locked', locked_staff = '$staffid_acc' where sheetid = '$sheetid' and rbn = '$rbn' and zone_id = '$zone_id' and mtype = 'A' and genlevel = 'abstract'";
	}*/
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
	if(($locked_status == "locked") && ($staffid_acc != $locked_staff))
	{
		header('Location: MeasurementBook_Locked_Accounts.php?locked_staff='.$locked_staff);
	}
	else
	{
		$update_locked_sql = mysql_query($update_locked_query);
		//header('Location:'.$view_url);
	}
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
$select_sheet_query = 	"select distinct (measurementbook_temp.sheetid), measurementbook_temp.rbn, measurementbook_temp.staffid, 
						staff.staffname, sheet.short_name, sheet.work_order_no, sheet.work_name, sheet.tech_sanction, 
						sheet.name_contractor, sheet.agree_no, sheet.computer_code_no 
						from measurementbook_temp 
						INNER JOIN sheet ON (sheet.sheet_id = measurementbook_temp.sheetid)
						INNER JOIN staff ON (staff.staffid = measurementbook_temp.staffid)
						ORDER BY measurementbook_temp.sheetid ASC";
$select_sheet_sql = mysql_query($select_sheet_query);
if($select_sheet_sql == true)
{
	if(mysql_num_rows($select_sheet_sql)>0)
	{
		$sheet_count = 1;
	}
	else
	{
		$sheet_count = 0;
	}
}
else
{
	$sheet_count = 0;
}
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
	   	url = "dashboard.php";
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

</style>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--==============================header=================================-->

<?php include "Menu.php"; ?>
<!--==============================Content=================================-->
<div class="content">
   <div class="container_12">
       <div class="grid_12">
            <blockquote class="bq1" style="background-color:#FFFFFF; overflow:scroll">
                <div class="title">Escalation MBook List</i></div>
					<br/>
                     <form name="form" id="form_mbook" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="container">
							<input type="hidden" name="txt_test" id="txt_test" value="hai">
							<?php
							if($sheet_count == 1)
							{
								while($SheetList = mysql_fetch_object($select_sheet_sql))
								{
									$sheetid 	  = $SheetList->sheetid;
									$rbn 		  = $SheetList->rbn;
									$staffname_mb = $SheetList->staffname;
									$staffid_mb   = $SheetList->staffid;
									//$select_acc_mb_query = "select * from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and mb_ac = 'SA' ORDER BY mbookno ASC";
									$select_acc_mb_query = "select * from send_accounts_and_civil where sheetid = '$sheetid' and rbn = '$rbn' and flag = 'ESC' ORDER BY mbookno ASC";
									$select_acc_mb_sql = mysql_query($select_acc_mb_query);
									if($select_acc_mb_sql == true)
									{
										if(mysql_num_rows($select_acc_mb_sql)>0)
										{
							echo '<table width="100%" align="center" class="acctext table1">';
									echo '<tr class="gradientbg">';
									echo '<td> &nbsp; Name of the Work  </td>';
									echo '<td colspan="6" class="acctextnormal">&nbsp;'.$SheetList->work_name.'</td>';
									echo '</tr>';
									echo '<tr class="gradientbg">';
									echo '<td> &nbsp; Work Order No.  </td>';
									echo '<td colspan="6" class="acctextnormal">&nbsp;'.$SheetList->work_order_no.'</td>';
									echo '</tr>';
									echo '<tr>';
									echo '<td width="15%" align="center">&nbsp;MBook No</td>';
									echo '<td width="20%">&nbsp;Zone Name</td>';
									echo '<td width="15%">&nbsp;MBook Type</td>';
									echo '<td width="20%">&nbsp;Staff Name</td>';
									echo '<td width="6%" align="center">&nbsp;RAB No</td>';
									echo '<td width="15%">&nbsp;&nbsp;Status</td>';
									echo '<td width="9%">&nbsp;</td>';
									echo '</tr>';
											$row = 1;
											while($AccMBList = mysql_fetch_object($select_acc_mb_sql))
											{
												$sacid = $AccMBList->sacid;
												if($AccMBList->mtype == 'CC'){ $mtype_str = "Cement Consumption "; $mtype = "CC";}
												if($AccMBList->mtype == 'SC'){ $mtype_str = "Steel Consumption"; $mtype = "SC";}
												if($AccMBList->mtype == 'E'){ $mtype_str = "Escalation"; $mtype = "E";}
												$SC_Count = CheckReturnedMBook($sheetid,$rbn);
												//if(($AccMBList->genlevel == 'cem_consum') || ($AccMBList->genlevel == 'stl_consum'))
												//{
													if($AccMBList->mb_ac == 'SA')
													{
														$status = " <font style='color:red; font-size:15px; font-weight:bold;' aria-hidden='true'>X</font> Not yet verified";
													}
													else if($AccMBList->mb_ac == 'SC')
													{
														$status = " <i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Returned to Civil";
													}
													else
													{
														$status = " <i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Verified";
													}
													$link = "ViewMBook(this)";
													$title = "";
													$tooltipClass = "";
												//}
												/*if($AccMBList->genlevel == 'escalation')
												{
													if($AccMBList->sa_ac == 'SA')
													{
														$status = " <font style='color:red; font-size:15px; font-weight:bold;' aria-hidden='true'>X</font> Not yet verified";
													}
													else if($AccMBList->sa_ac == 'SC')
													{
														$status = " <i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Returned to Civil";
													}
													else
													{
														$status = " <i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Verified";
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
												}*/
												/*if($AccMBList->genlevel == 'abstract')
												{
													if($AccMBList->ab_ac == 'SA')
													{
														$status = " <font style='color:red; font-size:15px; font-weight:bold;' aria-hidden='true'>X</font> Not yet verified";
													}
													else if($AccMBList->ab_ac == 'SC')
													{
														$status = " <i class='fa fa-backward' style='color:#07DCED; font-size:13px; padding-top:2px;' aria-hidden='true'></i> Returned to Civil";
													}
													else
													{
														$status = " <i class='fa fa-check' style='color:#14AF52; font-size:19px;' aria-hidden='true'></i> Verified";
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
												}*/
												$zone_name = getzonename($sheetid,$AccMBList->zone_id);
												$rowid = "";
												$rowid = $sheetid.$row;
												$dataStr = "";
												$dataStr = $sheetid."*".$AccMBList->zone_id."*".$staffid_mb."*".$mtype."*".$rbn."*".$sacid;
											echo '<tr class="acctextnormal">';
												echo '<td align="center">&nbsp;'.$AccMBList->mbookno.'</td>';
												echo '<td>&nbsp;'.$zone_name.'</td>';
												echo '<td>&nbsp;'.$mtype_str.'</td>';
												echo '<td>&nbsp;'.$staffname_mb.'</td>';
												echo '<td align="center">&nbsp;'.$rbn.'</td>';
												echo '<td>&nbsp;'.$status.'</td>';
												echo '<td align="center">';
												//echo '<a onClick="ViewMBook(this)" id="'.$rowid.'" style="background-color:#FFFFF; border:1px solid #FFFFF; padding:1px 8px 1px 8px;cursor:pointer;"><img src="images/book_open.png" style="padding-top:3px;" height="15"> View</a>';
												echo '<a onClick="'.$link.'" class="'.$tooltipClass.'" title="'.$title.'" id="'.$rowid.'" style="background-color:#FFFFF; border:1px solid #FFFFF; padding:1px 8px 1px 8px;cursor:pointer;"><img src="images/book_open.png" style="padding-top:3px;" height="15"> View</a>';
												echo '</td>';
											echo '</tr>';
											echo '<input type="hidden" name="txt_data_'.$rowid.'" id="txt_data_'.$rowid.'" value="'.$dataStr.'">';
											$row++;
											}
							echo '</table>';
										}
										else
										{
											//echo '<tr class="acctextnormal">';
											//echo '<td colspan="7" align="center" style="color:#A8A8A8;"> No Measurement Book Found...</td>';
											//echo '</tr>';
										}
									}
							echo '&nbsp';
								}
							}
							?>
						</div>
	 					<div style="text-align:center; height:45px; line-height:45px; color:#07DCED" class="printbutton">
							<div class="buttonsection">
							<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />
							</div>
							<!--<div class="buttonsection">
							<input type="submit" class="btn" data-type="submit" value=" View " name="submit" id="submit"   />
							</div>-->
						</div>
						<input type="hidden" name="txt_post_id" id="txt_post_id">
     				</form>
   			</blockquote>
  		</div>
  	</div>
</div>
<!--==============================footer=================================-->
<?php   include "footer/footer.html"; ?>
</body>
</html>
							<?php
							/*echo '<table width="100%" align="center" class="acctext table1">';
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
