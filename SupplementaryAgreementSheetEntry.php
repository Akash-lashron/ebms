<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/common.php';
$msg = '';
$userid = $_SESSION['userid'];
function dt_format($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);

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
    return $dd . '-' . $mm . '-' . $yy;
}
if (isset($_POST["submit"])) 
{
	$sheetid 		= 	trim($_POST['cmb_shortname']);
	$no_of_supp_agree = 0;
	$select_Query 	=  "select max(no_of_supp_agree) as aggno from sheet_supplementary where sheetid = '$sheetid'";
	$select_Sql = mysql_query($select_Query);
	if($select_Sql == true)
	{
		if(mysql_num_rows($select_Sql)>0)
		{
			$SList = mysql_fetch_object($select_Sql);
			$no_of_supp_agree = $SList->aggno;
		}
	}
	$no_of_supp_agree++;
    $workorder_supp = 	trim($_POST['txt_workorder_supp']);
	$workname_supp 	= 	trim($_POST['txt_workname_supp']);
    $techsanctionno = 	trim($_POST['techsanctionno']);
    $contractorname = 	trim($_POST['contractorname']);
    $agreementno 	= 	trim($_POST['agreementno']);
    $computercodeno = 	trim($_POST['computercodeno']);
	$worktype 		= 	trim($_POST['worktype']);
    $workorderdate 	= 	dt_format(trim($_POST['workorderdate']));
	$rebatepercent 	= 	trim($_POST['rebatepercent']);
    $sheet_sql 		= 	"INSERT INTO sheet_supplementary set "
											. "sheetid = '$sheetid', "
											. "no_of_supp_agree = '$no_of_supp_agree', "
                                            . "work_order_no = '$workorder_supp', "
                                            . "work_name = '$workname_supp', "
											. "short_name = '$shortname', "
                                            . "tech_sanction = '$techsanctionno', "
                                            . "name_contractor = '$contractorname', "
                                            . "agree_no = '$agreementno', "
                                            . "computer_code_no = '$computercodeno', "
											. "worktype = '$worktype', "
                                            . "work_order_date = '$workorderdate', "
											. "rebate_percent = '$rebatepercent', "
                                            . "active = '1', "
											. "date_upt = NOW()";
    $sheet_query 	= 	mysql_query($sheet_sql);
//echo $sheet_query;exit;
    if($sheet_query == true) 
	{
        $msg = "Supplementary Agreement Details Stored Successfully ";
		$success = 1;
    }
	else
	{
		$msg = " Something Error...!!! ";
	}
} 
if($_GET['sheet_id'] != "")
{
	$select_sheet_query 	= 	"select sheetid, work_order_no, work_name, short_name, tech_sanction, name_contractor, agree_no, computer_code_no, worktype, rebate_percent, work_order_date from sheet_supplementary WHERE supp_sheet_id = ".$_GET['sheet_id'];
	//$select_sheet_query ="SELECT a.*, b.* FROM sheet a inner join sheet_supplementary b on (a.sheet_id = b.sheetid) where a.active = 1 and b.active = 1 and b.supp_sheet_id = ".$_GET['sheet_id'];
	
	$select_sheet_sql 		= 	mysql_query($select_sheet_query);
	if($select_sheet_sql == true) 
	{
		$List = mysql_fetch_object($select_sheet_sql);
		$sheetid  			= 	$List->sheetid;
		$work_order_no 		= 	$List->work_order_no;
		$work_name 			= 	$List->work_name; 
		$short_name 		= 	$List->short_name; 
		$tech_sanction 		= 	$List->tech_sanction;
		$name_contractor 	= 	$List->name_contractor;
		$agree_no 			= 	$List->agree_no;
		$computer_code_no 	= 	$List->computer_code_no;
		$worktype 			= 	$List->worktype;
		$rebatepercent 		= 	$List->rebate_percent;
		$work_order_date 	= 	dt_display($List->work_order_date);
		
		$select_sheet_main_query 	= 	"select work_order_no, work_name, short_name, tech_sanction, name_contractor, agree_no, computer_code_no, worktype, rebate_percent, work_order_date from sheet WHERE sheet_id = ".$sheetid;
		$select_sheet_main_sql 		= 	mysql_query($select_sheet_main_query);
		if($select_sheet_main_sql == true) 
		{
			$MList = mysql_fetch_object($select_sheet_main_sql);
			$main_work_order_no 		= 	$MList->work_order_no;
		}
	}
}
if(isset($_POST['update']))
{
	$sheetid 			= 	trim($_POST['hid_sheetid']);
	$workname 			= 	trim($_POST['txt_workname_supp']);
	$shortname 			= 	trim($_POST['shortname']);
    $techsanctionno 	= 	trim($_POST['techsanctionno']);
    $contractorname 	= 	trim($_POST['contractorname']);
    $agreementno 		= 	trim($_POST['agreementno']);
    $workorderno 		= 	trim($_POST['txt_workorder_supp']);
    $computercodeno 	= 	trim($_POST['computercodeno']);
	$worktype 			= 	trim($_POST['worktype']);
    $workorderdate 		= 	dt_format(trim($_POST['workorderdate']));
	$rebatepercent 		= 	trim($_POST['rebatepercent']);
	$update_sheet_sql 	= 	"update sheet_supplementary set work_order_no = '$workorderno', work_name = '$workname', short_name = '$shortname', tech_sanction = '$techsanctionno', name_contractor = '$contractorname', agree_no = '$agreementno', computer_code_no = '$computercodeno', worktype = '$worktype', rebate_percent = '$rebatepercent', work_order_date = '$workorderdate' WHERE supp_sheet_id = '$sheetid'";
	$update_sheet_query = 	mysql_query($update_sheet_sql);
	if($update_sheet_query == true)
	{
		$msg = "Updated Sucessfully..!!";
		$success = 1;

	}
	else
	{
		$msg = "Error: Not Updated...!!";
	}
}
?>

  <?php require_once "Header.html"; ?>
<style>
    
</style>
<script>
   $(function () {
                $( "#workorderdate" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd-mm-yy",
                    maxDate: new Date,
                    defaultDate: new Date,
                });	
        $.fn.validateworknameSupp = function(event) { 
					if($("#txt_workname_supp").val()==""){ 
					var a="Please Enter Work Name";
					$('#val_wname_supp').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_wname_supp').text(a);
					}
				}
		$.fn.validatetechsanctionno = function(event) { 
					if($("#techsanctionno").val()==""){ 
					var a="Please Enter Technical Sanction Number";
					$('#val_techsno').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_techsno').text(a);
					}
				}
		$.fn.validatecontractorname = function(event) { 
					if($("#contractorname").val()==""){ 
					var a="Please Enter Contractor Name";
					$('#val_conname').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_conname').text(a);
					}
				}
		$.fn.validateagreementno = function(event) { 
					if($("#agreementno").val()==""){ 
					var a="Please Enter Agreement No";
					$('#val_aggno').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_aggno').text(a);
					}
				}
		$.fn.validateworkordernoSupp = function(event) { 
					if($("#txt_workorder_supp").val()==""){ 
					var a="Please Enter Work Order Number";
					$('#val_woredrno_supp').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_woredrno_supp').text(a);
					}
				}
		$.fn.validatecomputercodeno = function(event) { 
					if($("#computercodeno").val()==""){ 
					var a="Please Enter Computer Code Number";
					$('#val_systemcodeno').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_systemcodeno').text(a);
					}
				}		
		$.fn.validateshortname = function(event) { 
					if($("#cmb_shortname").val()==""){ 
					var a="Please Enter Short Name of Work";
					$('#val_shortname').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_shortname').text(a);
					}
				}			
				
		$("#txt_workname_supp").keyup(function(event){
		$(this).validateworknameSupp(event);
		});
		$("#techsanctionno").keyup(function(event){
		$(this).validatetechsanctionno(event);
		});
		$("#contractorname").keyup(function(event){
		$(this).validatecontractorname(event);
		});
		$("#agreementno").keyup(function(event){
		$(this).validateagreementno(event);
		});
		$("#txt_workorder_supp").keyup(function(event){
		$(this).validateworkordernoSupp(event);
		});
		$("#computercodeno").keyup(function(event){
		$(this).validatecomputercodeno(event);
		});
		$("#cmb_shortname").change(function(event){
		$(this).validateshortname(event);
		});
		$("#top").submit(function(event){
		$(this).validateworknameSupp(event);
		$(this).validatetechsanctionno(event);
		$(this).validatecontractorname(event);
		$(this).validateagreementno(event);
		$(this).validateworkordernoSupp(event);
		$(this).validatecomputercodeno(event);
		$(this).validateshortname(event);
		});
   
            });
	   function goBack()
	   {
	   		url = "dashboard.php";
			window.location.replace(url);
	   }
     function workorderdetail()
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
            strURL = "find_worder_details.php?workorderno=" + document.form.cmb_shortname.value;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText

                    if (data == "")
                    {
                        alert("No Records Found");
                    }
                    else
                    {
                        var name = data.split("*");
                        for(i = 0; i < name.length; i++)
                        {
							//document.form.txt_techsanction.value 	= name[0];
							//document.form.txt_agreemntno.value 		= name[2];
                            //document.form.txt_workname.value 		= name[3];
							document.form.txt_workorder.value 		= name[5];
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() 
	{ 
		window.history.forward(); 
	}
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                            <div class="title">Supplementary Agreement Sheet Entry</div>
                <div class="container_12">
                    <div class="grid_12">

						<!--<div align="right"><a href="SupplementaryAgreementSheetEntryView.php">View</a>&nbsp;&nbsp;&nbsp;</div>-->
                        <blockquote class="bq1" style="overflow-y:scroll">
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
                        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="18%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Short Name of Work.</td> 
                                    <td>
									<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" style="width:465px" onChange="workorderdetail();">
										<option value="">----------------------- Select Work Short Name ------------------------</option>
										<?php echo $objBind->BindWorkOrderNo($sheetid);?>
									</select>
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_shortname" style="color:red" colspan="">&nbsp;</td></tr>
                               <!-- <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Name of Work</td>
                                    <td><textarea name='txt_workname' id='txt_workname' class="textboxdisplay" rows="6" style="width: 465px;"><?php if($_GET['sheet_id'] != ''){ echo $work_name; } ?></textarea></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_wname" style="color:red" colspan="">&nbsp;</td></tr>-->
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order No</td>
                                    <td><input type="text" name='txt_workorder' id='txt_workorder' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $main_work_order_no; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_workorder_no" style="color:red" colspan="">&nbsp;</td></tr>
                                
								<tr>
									<td colspan="3" align="center" class="gradientbg">Supplementary Agreement Details</td>
								</tr>
								<tr><td colspan="3">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order No</td>
                                    <td><input type="text" name='txt_workorder_supp' id='txt_workorder_supp' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $work_order_no; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_woredrno_supp" style="color:red" colspan="">&nbsp;</td></tr>
                               <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Name of Work</td>
                                    <td><textarea name='txt_workname_supp' id='txt_workname_supp' class="textboxdisplay" rows="6" style="width: 465px;"><?php if($_GET['sheet_id'] != ''){ echo $work_name; } ?></textarea></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_wname_supp" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Technical Sanction No. </td>
                                    <td><input type="text" name='techsanctionno' id='techsanctionno' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $tech_sanction; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_techsno" style="color:red" colspan="">&nbsp;</td></tr>
								 <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Agreement No.</td>
                                    <td> <input type="text" name='agreementno' id='agreementno' class="textboxdisplay"  value="<?php if($_GET['sheet_id'] != ''){ echo $agree_no; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_aggno" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr> 
                                    <td>&nbsp;</td>
                                    <td class="label">Name of the contractor</td>
                                    <td><input type="text" name='contractorname' id='contractorname' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $name_contractor; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_conname" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Computer Code No. </td>
                                    <td><input type="text" name='computercodeno' id='computercodeno' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $computer_code_no; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_systemcodeno" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Type </td>
                                    <td class="label">
									<?php 
									/*if($_GET['sheet_id'] != '')
									{ 	
										if($worktype == 1) 
										{ 
											$check1 = 'checked="checked"'; 
											$check2 = "";
										} 
										else
										{
											$check2 = 'checked="checked"'; 
											$check1 = "";
										}
									} 
									else
									{
										$check2 = 'checked="checked"'; 
										$check1 = "";
									}*/
									?>
										<input type="radio" name="worktype" id="worktype" value="1" <?php echo $check1; ?>>Major Work&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="worktype" id="worktype" value="2" <?php echo $check2; ?>>Minor Work
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_worktype" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order Date </td>
                                    <td><input type="text" name='workorderdate' id='workorderdate' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $work_order_date; } ?>" size="15"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_systemcodeno" style="color:red" colspan="">&nbsp;</td></tr>
                             	<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Rebate Percentage </td>
                                    <td><input type="text" name='rebatepercent' id='rebatepercent' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $rebatepercent; } else { echo 0; } ?>" size="5">&nbsp;&nbsp;( % )</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_rebatepercent" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
									<td colspan="3">&nbsp;</td>
								</tr>
								<!--<tr>
                                    <td colspan="3" height="50px;">
                                <center>
                                    
									<?php 
									/*if($_GET['sheet_id'] != '')
									{ 
									?>
										<input type="submit" name="update" id="update" value=" Update "/>&nbsp;&nbsp;
										<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
									<?php
									}
									else
									{
									?>
										<input type="submit" name="submit" id="submit" value=" Submit "/>&nbsp;&nbsp;
										<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
									<?php
									}*/
									?>
                                </center>
                                </td>
                                </tr>-->
                            
                            
                            <!--<tr><td colspan="3">&nbsp;</td></tr>-->
<!--                            <tr><td width="500" colspan="5" class="green">
                                </td></tr>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr class="labelcenter">
                                <td colspan="5" align="center">&nbsp;

                                </td>
                            </tr>
                            <tr><td colspan="5">&nbsp;</td></tr>-->
                            </table>
                            <!--<div class="col2"><?php //if ($msg != '') { echo $msg; } ?></div>-->
									<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
										<div class="buttonsection">
										<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
										</div>
										<div class="buttonsection">
										<?php 
										if($_GET['sheet_id'] != '')
										{ 
										?>
											<input type="submit" name="update" id="update" value=" Update "/>
										<?php
										}
										else
										{
										?>
											<input type="submit" name="submit" id="submit" value=" Submit "/>
										<?php
										}
										?>
										</div>
									</div>
                        </blockquote>
                    </div>

                </div>
            </div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
		   <script>
				var msg = "<?php echo $msg; ?>";
				var success = "<?php echo $success; ?>";
				var titletext = "";
				document.querySelector('#top').onload = function(){
				if(msg != "")
				{
					if(success == 1)
					{
						swal("", msg, "success");
					}
					else
					{
						swal(msg, "", "");
					}
				}
				};
			</script>
        </form>
    </body>
</html>
