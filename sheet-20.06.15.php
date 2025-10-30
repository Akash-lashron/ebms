<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'ExcelReader/excel_reader2.php';
$msg = '';
$userid = $_SESSION['userid'];
if (isset($_POST["submit"])) {
    $workname = trim($_POST['workname']);
    $sheet_id = trim($_POST['workorderno']);
	$selectworder_sql = "SELECT sheet_name FROM sheet WHERE sheet_id = '$sheet_id'";
    $selectworder_query = mysql_query($selectworder_sql);
    $sheetname = @mysql_result($selectworder_query,0,'sheet_name');
	//echo $sheet_id;exit;
    if ($_FILES['file']['name'] != "") {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $currentfilename = basename($_FILES["file"]["name"]);
//echo "<br>Name  :".$target_file."<br>";
        $checkupload = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        if (file_exists($target_file)) {
            $msg = $msg . " Sorry, file already exists." . "<BR>";
            $checkupload = 0;
        }
// Check file size
        if ($_FILES["file"]["size"] > 500000) {
            $msg = $msg . " Sorry, your file is too large." . "<BR>";
            $checkupload = 0;
        }
// Allow certain file formats
        if (strtolower($imageFileType) != "xls" && strtolower($imageFileType) != "xlsx") {
            $msg = $msg . " Sorry, only xls files are allowed." . "<BR>";
            $checkupload = 0;
        }
// Check if $checkupload is set to 0 by an error
        if ($checkupload == 0) {
            $msg = $msg . " Sorry, your file was not uploaded." . "<BR>";
            //echo  $msg;
// if everything is ok, try to upload file
        } else {

            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
//      echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
                 $checkupload = 1;
            } else {
                $checkupload =0;
                $msg = $msg .  "Sorry, there was an error uploading your file." . "<BR>";
            }
        }
    } 
       // $checkupload = 1;
        $first = 0;$prev_item ='';$subdivisionlast_id =0;
        $slno = '';
        if ($checkupload == 1) {
                    $sheetquery = "UPDATE sheet set sheet_name='$currentfilename', date_upt=NOW(), active='1' WHERE sheet_id = '$sheet_id'";
        //echo $sheetquery . '<Br>';
        $sheetsql = mysql_query($sheetquery);
        $last_id = mysql_insert_id();
            $data = new Spreadsheet_Excel_Reader("uploads/" . $currentfilename);
            for ($i = 0; $i < count($data->sheets); $i++) { // Loop to get all sheets in a file.
                if (count($data->sheets[$i][cells]) > 0) { // checking sheet not empty
                    for ($j = 5; $j <= count($data->sheets[$i][cells]); $j++) { // loop used to get each row of the sheet
                        $item = $data->sheets[$i][cells][$j][1];
                        $description = $data->sheets[$i][cells][$j][2];
                        $qty = $data->sheets[$i][cells][$j][3];
                        $rate = $data->sheets[$i][cells][$j][4];
                        $per = $data->sheets[$i][cells][$j][5];
						$amt = $data->sheets[$i][cells][$j][6];
						$mtype = $data->sheets[$i][cells][$j][7];
                        $prevsplit = $prev_item;
                        $prevfound = explode(".", $prevsplit);
                        if ($item != '' && $description != '') 
                        {
                            $found = explode(".", $item);
							if($found[0] != $prevfound[0])
							{
								$divname = $found[0];
								$sql_sheetdivision = "insert into division set sheet_id ='$sheet_id',userid ='$userid', div_name='$divname', active='1'";
								$rs_sheetdivision = mysql_query($sql_sheetdivision);
                                $divisionlast_id = mysql_insert_id();
								if(count($found) == 1)
								{
									if(($qty != "") && ($qty != 0))
									{
										$sql_sheetsubdivision = "insert into subdivision set subdiv_name='$item',div_id ='$divisionlast_id', active='1'";
										$rs_sheetsubdivision = mysql_query($sql_sheetsubdivision);
										$subdivisionlast_id = mysql_insert_id();
									}
								}
							}
							else
							{
								if(($qty != "") && ($qty != 0))
								{
									$sql_sheetsubdivision = "insert into subdivision set subdiv_name='$item',div_id ='$divisionlast_id', active='1'";
                                    $rs_sheetsubdivision = mysql_query($sql_sheetsubdivision);
                                   	$subdivisionlast_id = mysql_insert_id();
								}
							}

							$prev_item = $item;
							if(($qty == "") || ($qty == 0)) 
								{ $subdivisionlastid = 0; }
							else 
								{ $subdivisionlastid = $subdivisionlast_id; }
							$sql_schedule = "insert into schdule set sheet_id='$sheet_id', sno='$item', description='$description', total_quantity='$qty', rate='$rate', per='$per', total_amt='$amt', measure_type='$mtype', subdiv_id ='$subdivisionlastid', active='1',create_dt=NOW(),user_id='$userid'";
                                $rs_schedule = mysql_query($sql_schedule);
                        }
                    } //for  // loop used to get each row of the sheet
                } // checking sheet not empty
            } // Loop to get all sheets in a file.
        } //checkupload
        if ($rs_schedule == true) {
            $msg = " Excel Sheet Uploaded Successfully <br>   Data Inserted Successfully";
        }
    
} //submit 
?>

  <?php require_once "Header.html"; ?>
  <script>
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
            //alert(document.form.workorderno.value);
            strURL = "find_worder_details.php?workorderno=" + document.form.workorderno.value;
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
                            /*document.form.techsanctionno.value = name[0];
                            document.form.contractorname.value = name[1];
                            document.form.agreementno.value = name[2];*/
                            document.form.workname.value = name[3];
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
</script>
<script>
   $(function () {
                    $.fn.validateworkname = function(event) { 
					if($("#workname").val()==""){ 
					var a="Please Enter Work Name";
					$('#val_wname').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_wname').text(a);
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
				$.fn.validateworkorderno = function(event) { 
					if($("#workorderno").val()==""){ 
					var a="Please Enter Work Order Number";
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
				$("#workname").keyup(function(event){
				   $(this).validateworkname(event);
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
				 $("#workorderno").keyup(function(event){
				   $(this).validateworkorderno(event);
				 });
				 	 
				$("#top").submit(function(event){
					$(this).validateworkname(event);
					$(this).validatetechsanctionno(event);
					$(this).validatecontractorname(event);
					$(this).validateagreementno(event);
					$(this).validateworkorderno(event);
					});
   
            });
        </script>
    <body class="page1" id="top">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">

                <div class="container_12">
                    <div class="grid_12">

						<div align="right"><a href="AgreementDetailsView.php">View</a></div>
                        <blockquote class="bq1">
                            <div class="title">Agreement Sheet</div>
                        <table width="900" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="10%">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order No</td> 
                                   <td  class="labeldisplay">
                                        <?php 
					$sql_itemno="select sheet_id ,work_order_no  from sheet WHERE active != 1"; 
					$rs_itemno=mysql_query($sql_itemno);
					?>
                                        <select name="workorderno" id="workorderno" class="textboxdisplay" style="width:383px;height:22px;" onChange="workorderdetail();" tabindex="7">
					<option value="">-----------------------Select Work Order-----------------------</option>
					<?php while($rows=mysql_fetch_assoc($rs_itemno)){ ?>
					<option value="<?php echo $rows['sheet_id']; ?>"><?php echo $rows['work_order_no']; ?></option>
					<?php } ?>
					</select>
                                    </td>
                                </tr>
								
                                
                                
                               
                                
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_aggno" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Name</td>
                                    <td><input type="text" name='workname' id='workname' class="textboxdisplay" value="" size="60"></td>
                                </tr>
								
                                
								
								
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_woredrno" style="color:red" colspan="">&nbsp;</td></tr>
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
                                <tr>
                                    <td colspan="3">
                                <center><input type="submit" class="btn" data-type="submit" name="submit" id="submit" value="Upload File" />
                                </center>
                                </td>
                                </tr>
                            </table>
                            </td>
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
                            </table>
                            <div class="col2"><?php if ($msg != '') {
    echo $msg;
} ?></div>
                        </blockquote>
						
                    </div>

                </div>
            </div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
        </form>
    </body>
</html>
