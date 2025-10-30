<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require('php-excel-reader/excel_reader2.php');
require_once 'library/binddata.php';
require('SpreadsheetReader.php');
$msg = '';
$userid = $_SESSION['userid'];
if (isset($_POST["submit"])) {
    $workname 		= trim($_POST['txt_workname']);
    $sheet_id 		= trim($_POST['txt_workshortname']);
	$xlsheetname 	= trim($_POST['txt_sheetname']);
	$selectworder_sql 	= "SELECT sheet_name FROM sheet WHERE sheet_id = '$sheet_id'";
    $selectworder_query = mysql_query($selectworder_sql);
    $sheetname 			= @mysql_result($selectworder_query,0,'sheet_name');
	//echo $sheet_id;exit;
    if ($_FILES['file']['name'] != "") {
        $target_dir = "uploads/";
		
		$UploadDate = date('dmYHis');
        $target_file = $target_dir .$UploadDate. basename($_FILES["file"]["name"]);
        $currentfilename = $UploadDate.basename($_FILES["file"]["name"]);
//echo "<br>Name  :".$currentfilename."<br>";exit;
        $checkupload = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
//echo "<br>Name  :".$target_file."<br>";exit;
        if (file_exists($target_file)) {
            $msg = $msg . " Sorry, file already exists." . "<br/>";
            $checkupload = 0;
        }
// Check file size
        if ($_FILES["file"]["size"] > 500000) {
            $msg = $msg . " Sorry, your file is too large." . "<br/>";
            $checkupload = 0;
        }
// Allow certain file formats
        if (strtolower($imageFileType) != "xls" && strtolower($imageFileType) != "xlsx") {
            $msg = $msg . " Sorry, only xls files are allowed." . "<br/>";
            $checkupload = 0;
        }
// Check if $checkupload is set to 0 by an error
        if ($checkupload == 0) {
            $msg = $msg . " Sorry, your file was not uploaded." . "<br/>";
            //echo  $msg;
// if everything is ok, try to upload file
        } else {

            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
//      echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
                 $checkupload = 1;
            } else {
                $checkupload =0;
                $msg = $msg .  "Sorry, there was an error uploading your file." . "<br/>";
            }
        }
    } 
			//echo $checkupload;exit;
		$work_order_cost = 0;
        $first = 0;$prev_item ='';$subdivisionlast_id =0; $sheetCnt = 0;  $Exectemp = 0; $InsertTemp = 0;
        $slno = '';
        if ($checkupload == 1) {
		
       /* $sheetquery = "UPDATE sheet set sheet_name='$currentfilename', date_upt=NOW(), active='1' WHERE sheet_id = '$sheet_id'";
        $sheetsql = mysql_query($sheetquery);
        $last_id = mysql_insert_id();*/
		
            $Spreadsheet = new SpreadsheetReader("uploads/" . $currentfilename);
			//echo'<pre>';print_r($data->sheets);exit;
			$Sheets = $Spreadsheet -> Sheets();
			//echo $xlsheetname;exit;
            foreach ($Sheets as $Index => $Name) { // Loop to get all sheets in a file.
			$Spreadsheet -> ChangeSheet($Index);
			
			$sheetname = $Name;
			//echo $sheetname;exit;
			if($xlsheetname == $sheetname)
			{
         		$sheetquery = "UPDATE sheet set sheet_name='$currentfilename', date_upt=NOW() WHERE sheet_id = '$sheet_id'";
        		$sheetsql = mysql_query($sheetquery);
        		$last_id = mysql_insert_id();
				$sheetCnt = 1;
				if(strtolower($imageFileType) == "xls")
				{
					$startRow = 1;
				} 
				if(strtolower($imageFileType) == "xlsx")
				{
					$startRow = 0;
				}
                //if (count($data -> ChangeSheet($Index)) > 0) { // checking sheet not empty
                    foreach ($Spreadsheet as $Key => $Row) { // loop used to get each row of the sheet
					if ($Key>$startRow){
						if(trim($Row[1]) != ''){
                        $item 			= trim($Row[0]);
                        $description 	= trim($Row[1]);
						$patterns = array();
						$patterns[0] = '/"/';
						$patterns[1] = "/'/";
						$patterns[2] = '/°/';
					//echo $description;exit;
						$replacements = array();
						$replacements[0] = '"';
						$replacements[1] = "\'";
						$replacements[2] = '°';
						$description 	= preg_replace($patterns, $replacements, $description);
						$description 	= str_replace("'", "'", $description);
                        $qty 			= trim($Row[2]);
                        $per 			= strtolower(preg_replace('/[.,]/', '', trim($Row[3])));
						$rate 			= trim($Row[4]);
						$amt 			= trim($Row[5]);
						$mtype 			= strtolower(trim($Row[6]));
						
						if(($amt > 0)&&($amt != "")){
							$work_order_cost = $work_order_cost + $amt;
						}
						
                        $prevsplit 		= $prev_item;
                        $prevfound 		= explode(".", $prevsplit);
                        if ($item != '' && $description != '') 
                        {
                            $found = explode(".", $item);
							if($found[0] != $prevfound[0])
							{
								$divname = $found[0];
								$sql_sheetdivision = "insert into division set sheet_id ='$sheet_id',userid ='$userid', div_name='$divname', active='1'";
								$rs_sheetdivision = mysql_query($sql_sheetdivision);
                                $divisionlast_id = mysql_insert_id();
								//if(count($found) == 1)
								//{
									if(($qty != "") && ($qty != 0))
									{
										$sql_sheetsubdivision = "insert into subdivision set subdiv_name='$item',div_id ='$divisionlast_id', sheet_id = '$sheet_id', active='1'";
										$rs_sheetsubdivision = mysql_query($sql_sheetsubdivision);
										$subdivisionlast_id = mysql_insert_id();
									}
								//}
							}
							else
							{
								if(($qty != "") && ($qty != 0))
								{
									$sql_sheetsubdivision = "insert into subdivision set subdiv_name='$item',div_id ='$divisionlast_id', sheet_id = '$sheet_id', active='1'";
                                    $rs_sheetsubdivision = mysql_query($sql_sheetsubdivision);
                                   	$subdivisionlast_id = mysql_insert_id();
								}
							}

							$prev_item = $item;
							if(($qty == "") || ($qty == 0)) 
								{ $subdivisionlastid = 0; }
							else 
								{ $subdivisionlastid = $subdivisionlast_id; }
							$sql_schedule = "insert into schdule set sheet_id='$sheet_id', sno='$item', description='$description', total_quantity='$qty', rate='$rate', rebate_percent = '0', decimal_placed = '3', per='$per', total_amt='$amt', measure_type='$mtype', item_flag = 'NI', escalation_flag = 'Y', subdiv_id ='$subdivisionlastid', active='1',create_dt=NOW(),user_id='$userid'";
                            $rs_schedule = mysql_query($sql_schedule);
							if($rs_schedule == true){
								$InsertTemp++;
							}
                        }
						$Exectemp++;
						}
					}//end of if (count($Spreadsheet)>4) condition
                    } //for  // loop used to get each row of the sheet----------------------
				}
				
               // } // checking sheet not empty
            } // Loop to get all sheets in a file.
        } //checkupload
		
		if($work_order_cost > 0){
			$work_order_cost 		= round($work_order_cost,2);
			$update_wo_cost_query 	= "update sheet set work_order_cost = '$work_order_cost' where sheet_id='$sheet_id'";
			$update_wo_cost_sql 	= mysql_query($update_wo_cost_query);
		}
		//echo $Exectemp;exit;
    	if($sheetCnt == 0){
			$msg = " Invalid sheet name. Please enter valid sheet name.";
			$success = 0;
		}else if($Exectemp == 0){
			$msg .= " Excel Sheet Not Uploaded. Invalid / Empty Excel Sheet.";
		}else{
			if($rs_schedule == true){
				$sheetquery = "UPDATE sheet set active = 1 WHERE sheet_id = '$sheet_id'";
				$sheetsql = mysql_query($sheetquery);
				$msg = " Excel Sheet Uploaded. <br/>   Data Inserted Successfully";
				$success = 1;
			}
		}
} //submit 
?>

  <?php require_once "Header.html"; ?>
  <link type='text/css' href='css/basic.css' rel='stylesheet' media='screen' />
  <script type='text/javascript' src='js/basic_model_jquery.js'></script>
  <script type='text/javascript' src='js/jquery.simplemodal.js'></script>


  <script>
  	 function goBack()
	 {
	   	url = "MyView.php";
		window.location.replace(url);
	 }
	 function OpenInNewTabWinBrowser(url) 
	 {
	  	var win = window.open(url, '_blank');
	  	win.focus();
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
            //alert(document.form.txt_workshortname.value);
            strURL = "find_worder_details.php?workorderno=" + document.form.txt_workshortname.value;
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
                        //document.form.itemno.value = 'Select';
                    }
                    else
                    {
                        var name = data.split("*");
                        for(i = 0; i < name.length; i++)
                        {
                            /*document.form.techsanctionno.value 	= name[0];
                            document.form.contractorname.value 		= name[1];
                            document.form.agreementno.value 		= name[2];*/
                            document.form.txt_workname.value 		= name[3];
							document.form.txt_workorder_no.value 	= name[5];
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
		function View_page()
		{
			 url = "AgreementDetailsView.php";
			 window.location.replace(url);
		}

</script>
<script>
$(function () {
	$("#sheet_name_info").click(function(event){
		$('#basic-modal-content').modal();
	});
	$.fn.validateworkorderno = function(event) { 
		if($("#txt_workshortname").val()==""){ 
			var a="Please Select Work Order Number";
			$('#val_woredrno').text(a);
			event.preventDefault();
			event.returnValue = false;
			//return false;
		}
		else{
			var a="";
			$('#val_woredrno').text(a);
		}
	}
	$.fn.validatesheetname = function(event) { 
		if($("#txt_sheetname").val()==""){ 
			var a="Please Enter Sheet Name of Excel File";
			$('#val_sheetname').text(a);
			event.preventDefault();
			event.returnValue = false;
			//return false;
		}
		else{
			var a="";
			$('#val_sheetname').text(a);
		}
	}
	$.fn.validateuploadfilename = function(event) { 
		if($("#file").val()==""){ 
			var a="Please Select a Excel File to Upload";
			$('#val_file').text(a);
			event.preventDefault();
			event.returnValue = false;
			//return false;
		}
		else{
			var a="";
			$('#val_file').text(a);
		}
	}
	$("#txt_workshortname").change(function(event){
		$(this).validateworkorderno(event);
	});
	$("#txt_sheetname").keyup(function(event){
		$(this).validatesheetname(event);
	});
	$("#file").change(function(event){
		$(this).validateuploadfilename(event);
	});
						 
	$("#top").submit(function(event){
		$(this).validateworkorderno(event);
		$(this).validatesheetname(event);
		$(this).validateuploadfilename(event);
	});
   
});
</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="title">Agreement Sheet - Upload</div>
                <div class="container_12">
                    <div class="grid_12">

						<div align="right"><!--<a href="AgreementDetailsView.php">Edit&nbsp;&nbsp;</a>-->&nbsp;</div>
                        <blockquote class="bq1">
							<div align="right">
								<font style="font-size:12px; font-weight:bold; color:#0066FF">
									Upload File Format :&nbsp;&nbsp;&nbsp;&nbsp;
									<a href="" onClick="OpenInNewTabWinBrowser('AgreementUpload_File_Sample.php');"><u>Agreement Sheet</u>&nbsp;&nbsp;&nbsp;&nbsp;</a>
								</font>
							</div>
                        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="21%">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Short Name</td> 
                                   <td  class="labeldisplay">
										<select name="txt_workshortname" id="txt_workshortname" class="textboxdisplay" style="width:439px;height:22px;" onChange="workorderdetail();">
											<option value="">-----------------------Select Work Order-----------------------</option>
											<?php echo $objBind->BindWorkOrderNoListUpload(0);?>
										</select>
                                    </td>
                                </tr>
                                
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_woredrno" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order No.</td>
									
                                    <td><input type="text" name='txt_workorder_no' id='txt_workorder_no' readonly="" class="textboxdisplay" style="width:435px;"></td>
                                </tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Name</td>
									
                                    <td><textarea name='txt_workname' id='txt_workname' readonly="" class="textboxdisplay" value="" rows="6" style="width:434px"></textarea></td>
                                </tr>
								
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Sheet Name</td>
									
                                    <td><input type="text" name='txt_sheetname' id='txt_sheetname' class="textboxdisplay" style="width:410px">&nbsp;<i class="fa fa-info-circle" aria-hidden="true" style="padding-top:1px; color:#0078F0; cursor:pointer; font-size:22px" id="sheet_name_info" title="Click here to View Sample"></i></td>
                                </tr>
								
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_sheetname" style="color:red" colspan="">&nbsp;</td></tr>
								
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Upload File</td>
                                    <td><input type="file" class="text" name="file" id="file" size="44" style="height:23px;" /></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_file" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td colspan="3" align="center" class="smalllabcss">Upload files allow the file formats of : .xls  , .xlsx</td>
                                </tr>
                                <!--<tr><td>&nbsp;</td></tr>-->
                                <!--<tr>
                                    <td colspan="3">
                                <center>
								<input type="submit" class="btn" data-type="submit" name="submit" id="submit" value="Upload File" />&nbsp;&nbsp;&nbsp;
								<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
                                </center>
                                </td>
                                </tr>-->
                            </table>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/></div>
								<div class="buttonsection"><input type="button" class="backbutton" name="View" id="View" value="View" onClick="View_page();"/></div>
								<div class="buttonsection" style="width:115px">
									<input type="submit" class="btn" data-type="submit" name="submit" id="submit" value="Upload File" />
								</div>
							</div>
							<div id="basic-modal-content">
								<img src="images/sheet_name.png">
							</div>
                           <!-- </td>
                            </tr>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr><td width="500" colspan="5" class="green">
                                </td></tr>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr class="labelcenter">
                                <td colspan="5" align="center">&nbsp;

                                </td>
                            </tr>
                            <tr><td colspan="5">&nbsp;</td></tr>
                            </table>-->
                           <!-- <div class="col2"><?php //if ($msg != '') { echo $msg; } ?></div>-->
                        </blockquote>
						
                    </div>

                </div>
            </div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
		   <script>
		   		//$("#txt_workshortname").chosen();
				var msg = "<?php echo $msg; ?>";
				var success = "<?php echo $success; ?>";
				var titletext = "";
				if(msg != "")
				{
					/*document.querySelector('#top').onload = function(){
							if(success == 1)
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
							}
					};*/
					BootstrapDialog.alert(msg);
				}
			</script>
        </form>
    </body>
</html>
