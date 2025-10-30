<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'ExcelReader/excel_reader2.php';
$msg = '';
$userid = $_SESSION['userid'];
if(isset($_GET['sheet_id']))
{
	$selectsheetsql = "SELECT * FROM sheet WHERE sheet_id = ".$_GET['sheet_id'];
	$selectsheetquery = mysql_query($selectsheetsql);
	$sheet_name = @mysql_result($selectsheetquery,0,'sheet_name');
	$workorder_no = @mysql_result($selectsheetquery,0,'work_order_no');
	$workorder_name = @mysql_result($selectsheetquery,0,'work_name');
	$tech_sanct_no = @mysql_result($selectsheetquery,0,'tech_sanction');
	$contractor_name = @mysql_result($selectsheetquery,0,'name_contractor');
	$agreemnt_no = @mysql_result($selectsheetquery,0,'agree_no');
	$ccno = @mysql_result($selectsheetquery,0,'computer_code_no');
	$workorder_date = @mysql_result($selectsheetquery,0,'work_order_date');
}
if (isset($_POST["submit"])) {
   $workorderno = trim($_POST['workorderno']);
    $workname = trim($_POST['workname']);
    $techsanctionno = trim($_POST['techsanctionno']);
    $contractorname = trim($_POST['contractorname']);
    $agreementno = trim($_POST['agreementno']);
    $computercodeno = trim($_POST['computercodeno']);
	$workorderdate = trim($_POST['workorderdate']);
	$sheetid = trim($_POST['sheetid']);
	$sheetname = trim($_POST['sheetname']);
	/*echo $workorderno."<br/>";echo $workname."<br/>";echo $techsanctionno."<br/>";echo $contractorname."<br/>";echo $agreementno."<br/>";echo $computercodeno."<br/>";echo $workorderdate."<br/>";*/
	
	//echo $sheet_id;exit;
    if ($_FILES['file']['name'] != "") {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $currentfilename = basename($_FILES["file"]["name"]);
//echo "<br>Name  :".$target_file."<br>";
        $checkupload = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        
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
		
		if (file_exists($target_file)) {
		
			?>
            <script type="text/javascript" language="javascript">
			//alert("hai hello");
                var x = confirm('File Already Exists. Do You Want to Replace?');
                if(x == false)
                {
				var sheetid = "<?php //echo $sheetid; ?>";
                    window.location.href = "AgreementDetailsEdit.php?sheet_id="+sheetid;
                    exit();
                } //alert("true");
            </script>
        	<?php 
			 unlink($target_file);
					if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) 
					{
						$checkupload = 1;
					}
					else
					{
						$checkupload =0;
					}
			// echo $checkupload;exit;
        }
// Check if $checkupload is set to 0 by an error
        if ($checkupload == 0) {
            $msg = $msg . " Sorry, your file was not uploaded." . "<BR>";
            //echo  $msg;
// if everything is ok, try to upload file
        } /*else {

            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
//      echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";
                 $checkupload = 1;
            } else {
                $checkupload =0;
                $msg = $msg .  "Sorry, there was an error uploading your file." . "<BR>";
            }
        }*/
    }  //echo "CHECK UPLOAD=".$checkupload;exit;
       // $checkupload = 1;
        $first = 0;$previd ='';$subdivisionlast_id =0;
        $slno = '';
        if ($checkupload == 1) 
		{
		 $delete_sql = "DELETE a,b,c FROM schdule a 
JOIN division b ON b.sheet_id = a.sheet_id
JOIN subdivision c ON c.div_id = b.div_id WHERE a.sheet_id = '$sheetid'";
       // echo $delete_sql;exit;
        $$delete_query = mysql_query($delete_sql);
                    $sheetquery = "UPDATE sheet set    sheet_name='$currentfilename',work_name='$workname',tech_sanction='$techsanctionno',name_contractor='$contractorname',agree_no='$agreementno',work_order_no ='$workorderno',computer_code_no ='$computercodeno',work_order_date ='$workorderdate',date_upt=NOW(),active='1' WHERE sheet_id = '$sheetid'";
        //echo $sheetquery . '<Br>';
        $sheetsql = mysql_query($sheetquery);
        $last_id = mysql_insert_id();
            $data = new Spreadsheet_Excel_Reader("uploads/" . $currentfilename);

            for ($i = 0; $i < count($data->sheets); $i++) { // Loop to get all sheets in a file.
                if (count($data->sheets[$i][cells]) > 0) { // checking sheet not empty
                    for ($j = 5; $j <= count($data->sheets[$i][cells]); $j++) { // loop used to get each row of the sheet
                        $eid = $data->sheets[$i][cells][$j][1];
                        $name = $data->sheets[$i][cells][$j][2];
                        $qty = $data->sheets[$i][cells][$j][3];
                        $rate = $data->sheets[$i][cells][$j][5];
                        $per = $data->sheets[$i][cells][$j][4];
                        $source = $eid;
                        $prevsplit = $previd;
                        $prevfound = explode(".", $prevsplit);
                        if ($eid != '' && $name != '') 
                        {
                            $strsplit = $eid;
                            $found = explode(".", $strsplit);
                            // echo $found[0]." == ".$found[1] ." == ".$found[2]."<Br>";
                            if((count($found) == 1) && ($found[0] != $prevfound[0]))
                            {
                                $divname = $found[0];
                                $sql_sheetdivision = "insert into division  set  sheet_id ='$sheetid',userid ='$userid',div_name='$divname',active='1'";
                                    //echo $sql_sheetdivision.'<Br>';
                                $rs_sheetdivision = mysql_query($sql_sheetdivision);
                                $divisionlast_id = mysql_insert_id();
                            }
                            
                         // if (($found[0] != "Sl") && (count($found) != 1)) 
                            else 
                            {
                                if ($found[0] != $prevfound[0]) 
                                {
                                   // $divname = $found[0] . "." . $found[1];   BLOCKED BY PS
                                    $divname = $found[0];
                                    $sql_sheetdivision = "insert into division  set  sheet_id ='$sheetid',userid ='$userid',div_name='$divname',active='1'";
                                    //echo $sql_sheetdivision.'<Br>';
                                    $rs_sheetdivision = mysql_query($sql_sheetdivision);
                                    $divisionlast_id = mysql_insert_id();
                                }
                            }

                            //  echo $found[0]."== ". $prevfound[0] ."== ". $found[1] ."== ". $prevfound[1] ."== ". $found[2] ."== ". $prevfound[2]."<Br>";
                            //      BLOCKED BY PS
                           /* if ($first == 1) {
                                $sql_sheetsubdivision = "insert into subdivision  set subdiv_name='$eid',div_id ='$divisionlast_id',active='1'";
                                //echo $sql_sheetsubdivision.'<Br>';
                                $rs_sheetsubdivision = mysql_query($sql_sheetsubdivision);
                                $subdivisionlast_id = mysql_insert_id();
                                $first++;
                            }*/
                            //if ($found[2] != $prevfound[2]) {             BLOCKED BY PS
                          //// if (count($found) != 1)
                           //// {
                               //// if (($found[1] != $prevfound[1]) || ($found[2] != $prevfound[2])) 
                                   //// {
                         if(($qty != "") && ($rate != "") &&($per != ""))  
                         {
                                        $sql_sheetsubdivision = "insert into subdivision  set subdiv_name='$eid',div_id ='$divisionlast_id',active='1'";
                                        //echo $sql_sheetsubdivision.'<Br>';
                                        $rs_sheetsubdivision = mysql_query($sql_sheetsubdivision);
                                        $subdivisionlast_id = mysql_insert_id();
                        }
                                   //// }
                           //// }
                            $previd = $eid;
                      //  }
                        //$sessionid = 1;
                        if($name != "")
                        {
                           // if(($qty == "") && ($rate == "") &&($per == "")) // COMMENTED ON 18.05.2015 FOR DEMO
                            if($eid == '')
                            {
                                $subdivisionlastid = 0;
//                                $sql_schedule = "insert into schdule set sheet_id='1',sno='$eid',description='$name',total_quantity='$qty',rate='$rate',per='$per',
//					subdiv_id ='0',active='1',create_dt=NOW(),user_id='$sessionid'";
//                        //echo $sql_schedule.'<Br>';
//                                $rs_schedule = mysql_query($sql_schedule);
                            }
                            else
                            {
                                $subdivisionlastid = $subdivisionlast_id;
                            }
                                $sql_schedule = "insert into schdule set sheet_id='$sheetid',sno='$eid',description='$name',total_quantity='$qty',rate='$rate',per='$per',
                                                subdiv_id ='$subdivisionlastid',active='1',create_dt=NOW(),user_id='$userid'";
                                //echo $sql_schedule.'<Br>';
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
//header('Location: AgreementDetailsView.php');
//header('Location: sheet.php');
  }
?>

  <?php require_once "Header.html"; ?>
 <script>
 function upload_file()
 {
 	document.getElementById("sheetname").style.display="none";
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
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">

                <div class="container_12">
                    <div class="grid_12">


                        <blockquote class="bq1">
                            <div class="title">Agreement Sheet</div>
                        <table width="900" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td>&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td>Work Order No.</td> 
                                    <td><input type="text"  name='workorderno' id='workorderno' class="textboxdisplay" size="60" value="<?php if($_GET['sheet_id'] != ""){ echo $workorder_name; } ?>"/></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_woredrno" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>Name of Work</td>
                                    <td><input type="text" name='workname' id='workname' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ""){ echo $workorder_name; } ?>" size="60"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_wname" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>Technical Sanction No. </td>
                                    <td><input type="text" name='techsanctionno' id='techsanctionno' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ""){ echo $tech_sanct_no; } ?>" size="60"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_techsno" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr> 
                                    <td>&nbsp;</td>
                                    <td>Name of contractor</td>
                                    <td><input type="text" name='contractorname' id='contractorname' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ""){ echo $contractor_name; } ?>" size="60"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_conname" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>Agreement No.</td>
                                    <td> <input type="text" name='agreementno' id='agreementno' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ""){ echo $agreemnt_no; } ?>" size="60"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_aggno" style="color:red" colspan="">&nbsp;</td></tr>
                                
								<tr>
                                    <td>&nbsp;</td>
                                    <td>CC No.</td> 
                                    <td><input type="text"  name='computercodeno' id='computercodeno' class="textboxdisplay" size="60" value="<?php if($_GET['sheet_id'] != ""){ echo $ccno; } ?>"/></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_computercodeno" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td>Work Order Date</td> 
                                    <td><input type="text"  name='workorderdate' id='workorderdate' class="textboxdisplay" size="60" value="<?php if($_GET['sheet_id'] != ""){ echo $workorder_date; } ?>"/></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_workorderdate" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>Upload File</td>
                                    <td>
									<?php 
									if($_GET['sheet_id'] != "")
									{
									?>
									<input type="file" class="text" name="file" size="44" style="height:23px; width: 90px;" onChange="this.style.width = '71%';" onBlur="upload_file()" />
									<span id="sheetname" style="display:"><?php echo $sheet_name; ?></span></td>
									<?php
									}
									else { ?>
									<input type="file" class="text" name="file" size="44" style="height:23px;" />
									<?php } ?>
									</td>
                                </tr>
                                <tr><td>&nbsp;
								<input type="hidden" name="sheetid" id="sheetid" value="<?php if($_GET['sheet_id'] != ""){ echo $_GET['sheet_id']; } ?>">
								<input type="hidden" name="sheetname" id="sheetname" value="<?php if($_GET['sheet_id'] != ""){ echo $sheet_name; } ?>">
								</td></tr>
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
