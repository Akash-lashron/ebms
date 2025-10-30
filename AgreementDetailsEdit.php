<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require('php-excel-reader/excel_reader2.php');
require('SpreadsheetReader.php');
$msg = '';
$userid = $_SESSION['userid'];
function dt_format($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);

    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
function dt_display($ddmmyyyy)
{
	 $dt	=	explode('-',$ddmmyyyy);
	 $dd	=	$dt[2];
	 $mm	=	$dt[1];
	 $yy	=	$dt[0];
	 return $dd . '-' . $mm . '-' . $yy;
}
if(isset($_GET['sheet_id']))
{
	$sheetid = $_GET['sheet_id'];
	$selectsheetsql 	= 	"SELECT * FROM sheet WHERE sheet_id = ".$_GET['sheet_id'];
	$selectsheetquery 	= 	mysql_query($selectsheetsql);
	$sheet_name 		= 	@mysql_result($selectsheetquery,0,'sheet_name');
	$workorder_no 		= 	@mysql_result($selectsheetquery,0,'work_order_no');
	$workshort_name		= 	@mysql_result($selectsheetquery,0,'short_name');
	$workorder_name 	= 	@mysql_result($selectsheetquery,0,'work_name');
	$tech_sanct_no 		= 	@mysql_result($selectsheetquery,0,'tech_sanction');
	$contractor_name 	= 	@mysql_result($selectsheetquery,0,'name_contractor');
	$agreemnt_no 		= 	@mysql_result($selectsheetquery,0,'agree_no');
	$ccno 				= 	@mysql_result($selectsheetquery,0,'computer_code_no');
	$worktype 			= 	@mysql_result($selectsheetquery,0,'worktype');
	$rebatepercent 		= 	@mysql_result($selectsheetquery,0,'rebate_percent');
	$workorder_date 	= 	dt_display(@mysql_result($selectsheetquery,0,'work_order_date'));
}
if (isset($_POST["submit"])) {
		$workorder_no = trim($_POST['txt_workorder_no']);
		$workorder_name = trim($_POST['txt_workname']);
       /* $workorderno 	= 	trim($_POST['workorderno']);
        $workname 		= 	trim($_POST['workname']);
		$workshortname	=	trim($_POST['workshortname']);
        $techsanctionno = 	trim($_POST['techsanctionno']);
        $contractorname = 	trim($_POST['contractorname']);
        $agreementno 	= 	trim($_POST['agreementno']);
        $computercodeno = 	trim($_POST['computercodeno']);
		$worktype 		= 	trim($_POST['worktype']);
        $workorderdate 	= 	dt_format(trim($_POST['workorderdate']));
		$rebatepercent 	= 	trim($_POST['rebatepercent']);*/
		$sheetid 		= 	$_POST['txt_workshortname'];
		$sheetid 		= 	trim($_POST['sheetid']);
		$sheetname 		= 	trim($_POST['sheetname']);
        $selectworder_sql 	= 	"SELECT sheet_name, short_name FROM sheet WHERE sheet_id = '$sheetid'";
        $selectworder_query = 	mysql_query($selectworder_sql);
        $sname 				= 	@mysql_result($selectworder_query,0,'sheet_name');
		$workshort_name 	= 	@mysql_result($selectworder_query,0,'sheet_name');
    	if ($_FILES['file']['name'] != "") 
        {
            $target_dir 		= 	"uploads/";
            $target_file 		= 	$target_dir . basename($_FILES["file"]["name"]);
            $currentfilename 	= 	basename($_FILES["file"]["name"]);
            $checkupload 		= 	1;
            $imageFileType 		= 	pathinfo($target_file, PATHINFO_EXTENSION);
            
            if ($_FILES["file"]["size"] > 500000) 
            {
            	$msg 			= 	$msg . " Sorry, your file is too large." . "<br/>";
                $checkupload 	= 	0;
            }
            if (strtolower($imageFileType) != "xls" && strtolower($imageFileType) != "xlsx") 
            {
                    $msg 			= 	$msg . " Sorry, only xls files are allowed." . "<br/>";
                    $checkupload 	= 	0;
            }
            if (file_exists($target_file)) 
            {
            ?>
                <script type="text/javascript" language="javascript">
					//alert("hai hello");
					var x = confirm('File Already Exists. Do You Want to Replace?');
					if(x == false)
					{
								//alert("if")
						var sheetid = "<?php echo $sheetid; ?>";
						window.location.href = "AgreementDetailsEdit.php?sheet_id="+sheetid;
						exit();
					} //alert("true");
					else
					{
									//alert("else")
						<?php $checkupload = 1; ?>
					}
               </script>
        	<?php 
            }
            if ($checkupload == 0) 
            {
            	$msg 	= 	$msg . " Sorry, your file was not uploaded." . "<br/>";
            } 
           	else 
           	{
            	if (file_exists($target_file)) { unlink($target_file); }
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) 
                {
                    $checkupload 	= 	1;
                } 
                else 
                {
                    $checkupload 	=	0;
                    $msg 			= 	$msg .  "Sorry, there was an error uploading your file." . "<br/>";
                }
            }
    	} 
   		/*if ($_FILES['file']['name'] == "")
    	{
		   	$updatesheet 	= 	"UPDATE sheet set work_name='$workname',short_name = '$workshortname',tech_sanction='$techsanctionno',name_contractor='$contractorname',agree_no='$agreementno',work_order_no ='$workorderno',computer_code_no ='$computercodeno', worktype='$worktype', rebate_percent = '$rebatepercent', work_order_date ='$workorderdate',date_upt=NOW(),active='1' WHERE sheet_id = '$sheetid'";
		   	$updatesheetsql 	= 	mysql_query($updatesheet); 
		   	$msg 			= 	"Updated Sucessfully...";
		   	$success = 1;
    	}*/
       // $checkupload = 1;
        $first 				= 	0;
		$previd 			=	'';
		$subdivisionlast_id =	0;
        $slno 				= 	'';
		$Exectemp = 0; $InsertTemp = 0;
        if ($checkupload == 1) {
            $delete_sql 	= 	"DELETE a,b,c FROM schdule a JOIN division b ON b.sheet_id = a.sheet_id JOIN subdivision c ON c.div_id = b.div_id WHERE a.sheet_id = '$sheetid'";
        	//echo $delete_sql;
        	$$delete_query 		= 	mysql_query($delete_sql);
        	$sheetquery 		= 	"UPDATE sheet set sheet_name='$currentfilename', date_upt=NOW(), active='1' WHERE sheet_id = '$sheetid'";
        	//echo $sheetquery . '<Br>';
        	$sheetsql 			= 	mysql_query($sheetquery);
       		$last_id 			= 	mysql_insert_id();
			$sheetCnt = 1;
			if(strtolower($imageFileType) == "xls")
			{
				$startRow = 1;
			} 
			if(strtolower($imageFileType) == "xlsx")
			{
				$startRow = 0;
			}
			
            $Spreadsheet 	= 	new SpreadsheetReader("uploads/" . $currentfilename);
			$Sheets 		= 	$Spreadsheet -> Sheets();
            foreach ($Sheets as $Index => $Name) { // Loop to get all sheets in a file.
			$Spreadsheet -> ChangeSheet($Index);
                //if (count($data->sheets[$i][cells]) > 0) { // checking sheet not empty
                    foreach ($Spreadsheet as $Key => $Row) { // loop used to get each row of the sheet
					//if (count($Spreadsheet)>3){
					if ($Key>$startRow){
						if(trim($Row[1]) != ''){
					
                        $item 			= 	trim($Row[0]);
                        $description 	= 	trim($Row[1]);
						$patterns = array();
						$patterns[0] = '/"/';
						$patterns[1] = "/'/";
						$patterns[2] = '/°/';
						
						$replacements = array();
						$replacements[0] = '"';
						$replacements[1] = "\'";
						$replacements[2] = '°';
						$description 	= preg_replace($patterns, $replacements, $description);
						$description 	= str_replace("'", "'", $description);
                        $qty 			= 	trim($Row[2]);
                        $per 			= 	strtolower(preg_replace('/[.,]/', '', trim($Row[3])));
						$rate 			= 	trim($Row[4]);
						$amt 			= 	trim($Row[5]);
						$mtype 			= 	trim($Row[6]);
                        $source 		= 	$eid;
						//echo "Item name =".$item ."*"."Desc =".$description."*"."Qty=".$qty."*"."Unit=".$per."*"."Rate=".$rate."*"."Amount=".$amt."<br/>";
                        $prevsplit 		= 	$prev_item;
                        $prevfound 		= 	explode(".", $prevsplit);
                        if ($item != '' && $description != '') 
                        {
						//echo "hi hello";
                            $found 		= 	explode(".", $item);
							if($found[0] != $prevfound[0])
							{
								$divname 			= 	$found[0];
								$sql_sheetdivision 	= 	"insert into division set sheet_id ='$sheetid',userid ='$userid', div_name='$divname', active='1'";
								$rs_sheetdivision 	= 	mysql_query($sql_sheetdivision);
                                $divisionlast_id 	= 	mysql_insert_id();
								//if(count($found) == 1)
								//{
									if(($qty != "") && ($qty != 0))
									{
										$sql_sheetsubdivision 	= 	"insert into subdivision set subdiv_name='$item',div_id ='$divisionlast_id', sheet_id = '$sheetid', active='1'";
										$rs_sheetsubdivision 	= 	mysql_query($sql_sheetsubdivision);
										$subdivisionlast_id 	= 	mysql_insert_id();
									}
								//}
							}
							else
							{
								if(($qty != "") && ($qty != 0))
								{
									$sql_sheetsubdivision 		= 	"insert into subdivision set subdiv_name='$item',div_id ='$divisionlast_id', sheet_id = '$sheetid', active='1'";
                                    $rs_sheetsubdivision 		= 	mysql_query($sql_sheetsubdivision);
                                   	$subdivisionlast_id 		= 	mysql_insert_id();
								}
							}

							$prev_item 		= 	$item;
							if(($qty == "") || ($qty == 0)) 
							{ 
								$subdivisionlastid = 0; 
							}
							else 
							{ 
								$subdivisionlastid = $subdivisionlast_id; 
							}
							$sql_schedule	= 	"insert into schdule set sheet_id='$sheetid', sno='$item', description='$description', total_quantity='$qty', rate='$rate',  rebate_percent = '0', decimal_placed = '3', per='$per', total_amt='$amt', measure_type='$mtype', subdiv_id ='$subdivisionlastid', item_flag = 'NI', active='1',create_dt=NOW(),user_id='$userid'";
                            $rs_schedule 	= 	mysql_query($sql_schedule);
							if($rs_schedule == true){
								$InsertTemp++;
							}
                        } //for  // loop used to get each row of the sheet
						$Exectemp++;
						}
					}
                } // checking sheet not empty
           // } // Loop to get all sheets in a file.
        } 
		if($Exectemp == 0){
			$msg .= " Excel Sheet Not Uploaded. Invalid / Empty Excel Sheet.";
		}else{
			if ($rs_schedule == true) 
			{
				$msg = " Excel Sheet Uploaded Successfully..";
				$success = 1;
				/*echo '<script>window.location.href = "AgreementDetailsEdit.php";</script>';*/
			}
		}
  	}  //checkupload
} //submit 
?>

<?php require_once "Header.html"; ?>
<script>
	function upload_file(){
 		document.getElementById("sheetname").style.display="none";
 	}
	function goBack(){
		url = "AgreementDetailsView.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
               <div class="title">Agreement Sheet - Edit</div>
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1" style="overflow:auto">
                        	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr>
									<td width="20%">&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Short Name</td> 
                                   <td  class="labeldisplay">
										<select name="txt_workshortname" id="txt_workshortname" required class="textboxdisplay" style="width:439px;height:22px;" onChange="workorderdetail();" tabindex="7">
											<option value="<?php echo $sheetid; ?>"><?php echo $workshort_name; ?></option>
										</select>
                                    </td>
                                </tr>
                                
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_woredrno" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order No.</td>
									
                                    <td><input type="text" name='txt_workorder_no' id='txt_workorder_no' required class="textboxdisplay" readonly="" style="width:435px;" value="<?php if($sheetid != ""){ echo $workorder_no; } ?>"></td>
                                </tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Name</td>
									
                                    <td><textarea name='txt_workname' id='txt_workname' class="textboxdisplay" required readonly="readonly" rows="6" style="width:434px"><?php if($sheetid != ""){ echo $workorder_name; } ?></textarea></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="" style="color:red" colspan="">&nbsp;</td></tr>
								<!--<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order No.</td> 
                                    <td><input type="text"  name='workorderno' id='workorderno' readonly="" class="textboxdisplay" style="width: 465px;" value="<?php if($_GET['sheet_id'] != ""){ echo $workorder_no; } ?>"/></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_woredrno" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Short Name</td>
                                    <td>
									<input type="text" name='workshortname' id='workshortname' readonly="" class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ""){ echo $workshort_name; } ?>" style="width: 465px;">
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_wshortname" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Name of Work</td>
                                    <td>
									<textarea name='workname' id='workname' class="textboxdisplay" readonly="" value="" rows="6" style="width: 465px;"><?php if($_GET['sheet_id'] != ""){ echo $workorder_name; } ?></textarea>
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_wname" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Technical Sanction No. </td>
                                    <td><input type="text" name='techsanctionno' id='techsanctionno' readonly="" class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ""){ echo $tech_sanct_no; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_techsno" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr> 
                                    <td>&nbsp;</td>
                                    <td class="label">Name of contractor</td>
                                    <td><input type="text" name='contractorname' id='contractorname' readonly="" class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ""){ echo $contractor_name; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_conname" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Agreement No.</td>
                                    <td> <input type="text" name='agreementno' id='agreementno' readonly="" class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ""){ echo $agreemnt_no; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_aggno" style="color:red" colspan="">&nbsp;</td></tr>
                                
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">CC No.</td> 
                                    <td><input type="text"  name='computercodeno' id='computercodeno' readonly="" class="textboxdisplay" style="width: 465px;" value="<?php if($_GET['sheet_id'] != ""){ echo $ccno; } ?>"/></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_computercodeno" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Type </td>
                                    <td class="label">
									<?php 
									if($_GET['sheet_id'] != '')
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
									}
									?>
										<input type="radio" name="worktype" id="worktype" value="1" <?php echo $check1; ?>>Major Work&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="worktype" id="worktype" value="2" <?php echo $check2; ?>>Minor Work
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_worktype" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order Date</td> 
                                    <td><input type="text"  name='workorderdate' id='workorderdate' readonly="" class="textboxdisplay" size="15" value="<?php if($_GET['sheet_id'] != ""){ echo $workorder_date; } ?>"/></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_workorderdate" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Rebate Percentage</td> 
                                    <td><input type="text"  name='rebatepercent' id='rebatepercent' readonly="" class="textboxdisplay" size="6" value="<?php if($_GET['sheet_id'] != ""){ echo $rebatepercent; } ?>"/>&nbsp;&nbsp;( % )</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_rebatepercent" style="color:red" colspan="">&nbsp;</td></tr>-->
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Upload File</td>
                                    <td>
									<?php //if($sheetid != ""){ ?>
									<!--<input type="file" class="text" name="file" size="44" style="height:23px; width: 79px;" onChange="this.style.width = '71%';" onBlur="upload_file()" />
									<span id="sheetname" style="display:"><?php echo $sheet_name; ?></span>-->
									<?php //}else { ?>
									<input type="file" class="text" name="file" size="44" style="height:23px;" required />
									<?php //} ?>
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;
									<input type="hidden" name="sheetid" id="sheetid" value="<?php if($sheetid != ""){ echo $sheetid; } ?>">
									<input type="hidden" name="sheetname" id="sheetname" value="<?php if($sheetid != ""){ echo $sheet_name; } ?>">
									</td>
								</tr>
                                <tr>
                                    <td colspan="3" align="center" class="smalllabcss">Upload files allow the file formats of : .xls  , .xlsx</td>
                                </tr>
                                <tr>
                                    <td colspan="3">
									<center>
									<input type="submit" class="btn" data-type="submit" name="submit" id="submit" value="Upload File" />&nbsp;&nbsp;&nbsp;
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
									</center>
                                </td>
                                </tr>
                            </table>
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
						BootstrapDialog.alert(msg);
							/*if(success == 1)
							{
								//swal("", msg, "success");
								swal({
								title: titletext,
								text: msg,
								type:"success",
								 html: true,
								//timer: 4000,
								showConfirmButton: true
								})
							}
							else
							{
								swal({
								title: msg,
								text: "",
								 html: true,
								//timer: 4000,
								showConfirmButton: true
								})
							}*/
					}
				};
			</script>
        </form>
    </body>
</html>
