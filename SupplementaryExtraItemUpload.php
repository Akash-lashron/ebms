<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require('SpreadsheetReader.php');
$msg = '';
$userid = $_SESSION['userid'];
if (isset($_POST["submit"])) 
{
    $workname = trim($_POST['txt_workname']);
    $sheet_id = trim($_POST['txt_workshortname']);
	$supp_sheet_id = trim($_POST['workorderno_supp']);
	
	$selectworder_sql = "SELECT sheet_name FROM sheet_supplementary WHERE supp_sheet_id = '$supp_sheet_id'";
    $selectworder_query = mysql_query($selectworder_sql);
    $sheetname = @mysql_result($selectworder_query,0,'sheet_name');
//echo $sheet_id;exit;
    if ($_FILES['file']['name'] != "") 
	{
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $currentfilename = basename($_FILES["file"]["name"]);
//echo "<br>Name  :".$target_file."<br>";
        $checkupload = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        if (file_exists($target_file)) 
		{
            $msg = $msg . " Sorry, file already exists." . "<br/>";
            $checkupload = 0;
        }
// Check file size
        if ($_FILES["file"]["size"] > 500000) 
		{
            $msg = $msg . " Sorry, your file is too large." . "<br/>";
            $checkupload = 0;
        }
// Allow certain file formats
        if (strtolower($imageFileType) != "xls" && strtolower($imageFileType) != "xlsx") 
		{
            $msg = $msg . " Sorry, only xls files are allowed." . "<br/>";
            $checkupload = 0;
        }
// Check if $checkupload is set to 0 by an error
        if ($checkupload == 0) 
		{
            $msg = $msg . " Sorry, your file was not uploaded." . "<br/>";
            //echo  $msg;
// if everything is ok, try to upload file
        } 
		else 
		{

            if(move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) 
			{
//      echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
                 $checkupload = 1;
            } 
			else 
			{
                $checkupload =0;
                $msg = $msg .  "Sorry, there was an error uploading your file." . "<br/>";
            }
        }
    } 
       // $checkupload = 1;
        $first = 0;$prev_item ='';$subdivisionlast_id =0;
        $slno = '';
        if ($checkupload == 1) {
        $sheetquery = "UPDATE sheet_supplementary set sheet_name='$currentfilename', date_upt=NOW(), active='1' WHERE supp_sheet_id = '$supp_sheet_id'";
        //echo $sheetquery . '<Br>';
        $sheetsql = mysql_query($sheetquery);
        $last_id = mysql_insert_id();
		
		
            $Spreadsheet = new SpreadsheetReader("uploads/" . $currentfilename);
			//echo'<pre>';print_r($data->sheets);exit;
			$Sheets = $Spreadsheet -> Sheets();
            foreach ($Sheets as $Index => $Name) { // Loop to get all sheets in a file.
			$Spreadsheet -> ChangeSheet($Index);
                //if (count($data -> ChangeSheet($Index)) > 0) { // checking sheet not empty
                    foreach ($Spreadsheet as $Key => $Row) { // loop used to get each row of the sheet
					if ($Key>0){
                        $item 			= trim($Row[0]);
                        $description 	= $Row[1];
						$patterns = array();
						$patterns[0] = '/"/';
						$patterns[1] = "/'/";
						$patterns[2] = '/�/';
						
						$replacements = array();
						$replacements[0] = '"';
						$replacements[1] = "\'";
						$replacements[2] = '�';
						$description 	= preg_replace($patterns, $replacements, $description);
						$description 	= str_replace("'", "'", $description);
                        $qty 			= $Row[2];
                        $per 			= strtolower(preg_replace('/[.,]/', '', $Row[3]));
						$rate 			= $Row[4];
						$amt 			= $Row[5];
						$mtype 			= $Row[6];
						$sub_type 		= $Row[7];
                        $prevsplit 		= $prev_item;
                        $prevfound 		= explode(".", $prevsplit);
                        if ($item != '' && $description != '') 
                        {
                            $found = explode(".", $item);
							if($found[0] != $prevfound[0])
							{
								$divname = $found[0];
								$sql_sheetdivision = "insert into division set sheet_id ='$sheet_id',userid ='$userid', div_name='$divname', supp_sheet_id = '$supp_sheet_id', active='1'";
								$rs_sheetdivision = mysql_query($sql_sheetdivision);
                                $divisionlast_id = mysql_insert_id();
								//if(count($found) == 1)
								//{
									if(($qty != "") && ($qty != 0))
									{
										$sql_sheetsubdivision = "insert into subdivision set subdiv_name='$item',div_id ='$divisionlast_id', sheet_id = '$sheet_id', supp_sheet_id = '$supp_sheet_id', active='1'";
										$rs_sheetsubdivision = mysql_query($sql_sheetsubdivision);
										$subdivisionlast_id = mysql_insert_id();
									}
								//}
							}
							else
							{
								if(($qty != "") && ($qty != 0))
								{
									$sql_sheetsubdivision = "insert into subdivision set subdiv_name='$item',div_id ='$divisionlast_id', sheet_id = '$sheet_id', supp_sheet_id = '$supp_sheet_id', active='1'";
                                    $rs_sheetsubdivision = mysql_query($sql_sheetsubdivision);
                                   	$subdivisionlast_id = mysql_insert_id();
								}
							}

							$prev_item = $item;
							if(($qty == "") || ($qty == 0)) 
								{ $subdivisionlastid = 0; }
							else 
								{ $subdivisionlastid = $subdivisionlast_id; }
							$sql_schedule = "insert into schdule set sheet_id='$sheet_id', sno='$item', description='$description', total_quantity='$qty', rate='$rate', rebate_percent = '0', decimal_placed = '3', per='$per', total_amt='$amt', measure_type='$mtype', sub_type = '$sub_type', supp_sheet_id = '$supp_sheet_id', item_flag = 'EI', subdiv_id ='$subdivisionlastid', active='1',create_dt=NOW(),user_id='$userid'";
                                $rs_schedule = mysql_query($sql_schedule);
                        }
					}//end of if (count($Spreadsheet)>4) condition
                    } //for  // loop used to get each row of the sheet----------------------
               // } // checking sheet not empty
            } // Loop to get all sheets in a file.
        } //checkupload
        if ($rs_schedule == true) 
		{
            $msg = " Excel Sheet Uploaded. <br/>   Data Inserted Successfully";
			$success = 1;
        }
    
} //submit 
?>

  <?php require_once "Header.html"; ?>
  <script>
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
	function GetSupplementaryWorkOrder()
	{ 
		var xmlHttp;
		var data;
		var i, j;
		document.form.workorderno_supp.length = 1;
		document.form.txt_workorder_no_supp.value = "";
		if (window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if (window.ActiveXObject) // For Internet Explorer
		{
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL = "find_supp_worder.php?workorderno=" + document.form.txt_workshortname.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function ()
		{
			if (xmlHttp.readyState == 4)
			{
				data = xmlHttp.responseText
				if (data == "")
				{
					sweetAlert("No Supplementary Agreement for this Work Order", "", "");
				}
				else
				{
					var name = data.split("*");
					/*document.form.workorderno_supp.length = 0
					var optn1 	= 	document.createElement("option")
					optn1.value 	= 	"";
					optn1.text 	= 	" -------------------------- Select Supplementary Work Name ------------------------- ";
					document.form.workorderno_supp.options.add(optn1)*/
									
					for(i = 0; i < name.length; i+=7)
					{
						var workname	= name[i+2];
						var workid		= name[i+6];
						var optn 		= 	document.createElement("option")
						optn.value 		= 	workid;
						optn.text 		= 	workname;
						document.form.workorderno_supp.options.add(optn)
					}
				}
			}
		}
		xmlHttp.send(strURL);
	}
	function GetSupplementaryWorkOrderDetails()
	{ 
		var xmlHttp;
		var data;
		var i, j;
		document.form.txt_workorder_no_supp.value = "";
		if (window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if (window.ActiveXObject) // For Internet Explorer
		{
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL = "find_supp_worder_details.php?workorderno=" + document.form.workorderno_supp.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function ()
		{
			if (xmlHttp.readyState == 4)
			{
				data = xmlHttp.responseText; //alert(data);
				if (data == "") 
				{
					sweetAlert("Work Order No. is not exist", "", "");
				}
				else
				{
					var name = data.split("*");
					document.form.txt_workorder_no_supp.value = name[5];
				}
			}
		}
		xmlHttp.send(strURL);
	}
</script>
<script>
$(function () {
	$.fn.validateworkorderno = function(event) { 
		if($("#txt_workshortname").val()=="")
		{ 
			var a="Please Select Work Order Number";
			$('#val_woredrno').text(a);
			event.preventDefault();
			event.returnValue = false;
			//return false;
		}
		else
		{
			var a="";
			$('#val_woredrno').text(a);
		}
	}
	$("#txt_workshortname").change(function(event){
		$(this).validateworkorderno(event);
	});
	$("#top").submit(function(event){
		$(this).validateworkorderno(event);
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
                            <div class="title">Extra Item Agreement Upload</div>
                <div class="container_12">
                    <div class="grid_12">

						<div align="right"><a href="SupplementaryExtraItemEdit.php">Edit&nbsp;&nbsp;</a></div>
                        <blockquote class="bq1">
                        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="17%">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Short Name</td> 
                                   <td  class="labeldisplay">
										<select name="txt_workshortname" id="txt_workshortname" class="textboxdisplay" style="width:439px;height:22px;" onChange="workorderdetail(); GetSupplementaryWorkOrder();" tabindex="7">
											<option value="">---------------------------- Select Work Name -----------------------------</option>
											<?php echo $objBind->BindWorkOrderNo(0); ?>
										</select>
                                    </td>
                                </tr>
                                
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_woredrno" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order No.</td>
									
                                    <td><input type="text" name='txt_workorder_no' id='txt_workorder_no' class="textboxdisplay" style="width:435px;"></td>
                                </tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Name</td>
									
                                    <td><textarea name='txt_workname' id='txt_workname' class="textboxdisplay" value="" rows="6" style="width:434px"></textarea></td>
                                </tr>
								
								<tr><td>&nbsp;</td><td></td><td id="val_work_name" style="color:red"></tr>
								
                                <tr>
                                    <td>&nbsp;</td>
                                    <td  class="label" width="25%" nowrap="nowrap">Supplementary Agreement</td>
                                    <td class="label">
                                        <select id="workorderno_supp" name="workorderno_supp" onChange="GetSupplementaryWorkOrderDetails()" class="textboxdisplay" style="width:434px;height:22px;" tabindex="7">
                                             <option value=""> ------------------- Select Supplementary Work Name ------------------ </option>
                                        </select>     
                                    </td>
                                </tr>
                                <tr><td>&nbsp;</td><td></td><td id="val_work_supp" style="color:red"></tr>
										
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Supplementary Work Order No</td>
                                    <td class="labeldisplay">
                                        <input type="text" name='txt_workorder_no_supp' id='txt_workorder_no_supp' class="textboxdisplay" value="" style="width:434px;"/>
                                    </td>
                                </tr>

                                 <tr>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td id="val_workorderno_supp" style="color:red"></td>
                                 </tr>
								
								
								
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Upload File</td>
                                    <td><input type="file" class="text" name="file" size="44" style="height:23px;" /></td>
                                </tr>
                                <tr><td>&nbsp;</td></tr>
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
										<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
										</div>
										<div class="buttonsection" style="width:115px">
										<input type="submit" class="btn" data-type="submit" name="submit" id="submit" value="Upload File" />
										</div>
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
				var msg = "<?php echo $msg; ?>";
				var success = "<?php echo $success; ?>";
				var titletext = "";
				if(msg != "")
				{
					document.querySelector('#top').onload = function(){
						/*swal({
							title: titletext,
							text: msg,
							 html: true,
							//timer: 4000,
							showConfirmButton: true
						});*/
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
					};
				}
			</script>
        </form>
    </body>
</html>
