<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'ExcelReader/excel_reader2.php';
include "library/common.php";
$msg = '';
if (isset($_POST["submit"])) {
    $workname = trim($_POST['workname']);
    $techsanctionno = trim($_POST['techsanctionno']);
    $contractorname = trim($_POST['contractorname']);
    $agreementno = trim($_POST['agreementno']);
    $workorderno = trim($_POST['workorderno']);
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
        $checkupload = 1;
        $first = 0;$previd ='';$subdivisionlast_id =0;
        $slno = '';
        if ($checkupload == 1) {
                    $sheetquery = "insert into sheet set    sheet_name='$currentfilename',work_name='$workname',tech_sanction='$techsanctionno',name_contractor='$contractorname',agree_no='$agreementno',work_order_no ='$workorderno',date_upt=NOW(),active='1'";
       // echo $sheetquery . '<Br>';
        $sheetsql = mysql_query($sheetquery);
        $last_id = mysql_insert_id();
            $data = new Spreadsheet_Excel_Reader("uploads/" . $currentfilename);

            for ($i = 0; $i < count($data->sheets); $i++) { // Loop to get all sheets in a file.
                if (count($data->sheets[$i][cells]) > 0) { // checking sheet not empty
                    for ($j = 5; $j <= count($data->sheets[$i][cells]); $j++) { // loop used to get each row of the sheet
                        $eid = $data->sheets[$i][cells][$j][1];
                        $name = $data->sheets[$i][cells][$j][2];
                        $qty = $data->sheets[$i][cells][$j][3];
                        $rate = $data->sheets[$i][cells][$j][4];
                        $per = $data->sheets[$i][cells][$j][5];
                        $source = $eid;
                        $prevsplit = $previd;
                        $prevfound = explode(".", $prevsplit);
                        if ($eid != '' && $name != '') {
                            $strsplit = $eid;
                            $found = explode(".", $strsplit);
                            // echo $found[0]." == ".$found[1] ." == ".$found[2]."<Br>";
                            if ($found[0] != "Sl") {
                                if ($found[1] != $prevfound[1]) {
                                    $divname = $found[0] . "." . $found[1];

                                    $sql_sheetdivision = "insert into division  set  sheet_id ='1',userid ='1',div_name='$divname',active='1'";
                                    //echo $sql_sheetdivision.'<Br>';
                                    $rs_sheetdivision = mysql_query($sql_sheetdivision);
                                    $divisionlast_id = mysql_insert_id();
                                }
                            }

                            //  echo $found[0]."== ". $prevfound[0] ."== ". $found[1] ."== ". $prevfound[1] ."== ". $found[2] ."== ". $prevfound[2]."<Br>";
                            if ($first == 1) {
                                $sql_sheetsubdivision = "insert into subdivision  set subdiv_name='$eid',div_id ='$divisionlast_id',active='1'";
                                //echo $sql_sheetsubdivision.'<Br>';
                                $rs_sheetsubdivision = mysql_query($sql_sheetsubdivision);
                                $subdivisionlast_id = mysql_insert_id();
                                $first++;
                            }
                            if ($found[2] != $prevfound[2]) {
                                $sql_sheetsubdivision = "insert into subdivision  set subdiv_name='$eid',div_id ='$divisionlast_id',active='1'";
                                //echo $sql_sheetsubdivision.'<Br>';
                                $rs_sheetsubdivision = mysql_query($sql_sheetsubdivision);
                                $subdivisionlast_id = mysql_insert_id();
                            }
                            $previd = $eid;
                        }
                        $sessionid = 1;
                        $sql_schedule = "insert into schdule set sheet_id='1',sno='$eid',description='$name',total_quantity='$qty',rate='$rate',per='$per',
					subdiv_id ='$subdivisionlast_id',active='1',create_dt=NOW(),user_id='$sessionid'";
                        //echo $sql_schedule.'<Br>';
                        $rs_schedule = mysql_query($sql_schedule);
                    } //for  // loop used to get each row of the sheet
                } // checking sheet not empty
            } // Loop to get all sheets in a file.
        } //checkupload
        if ($rs_schedule == true) {
            $msg = " Excel Sheet Uploaded Successfully <br>   Data Inserted Successfully";
        }
    
} //submit 
?>
<!DOCTYPE html>
<html lang="en">
    <?php require_once "Header.html"; ?>        
    <body class="page1" id="top">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"  name="phuploader">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">

                <div class="container_12">
                    <div class="grid_12">


                        <blockquote class="bq1">
                            <div class="title">Create User</div>
                              <fieldset class="row1">
                <legend>Engineer Details
                </legend>
                <p>
                    <label> Engineer Name
                    </label>
                    <select width=300px>
                        <option value="0"> -- Select Engineer --</option>
                        <option value="1">Mubarak
                        </option>
                    </select>
               </p>
                <p>
                    <label>Designation
                    </label>
                    <input type="text"/>
                    

                </p>
            </fieldset>
            <fieldset class="row1">
                <legend>Account Details
                </legend>
                <p>
                    <label> User Name
                    </label>
                    <input type="text"/>
               </p>
                <p>
                    <label>Password*
                    </label>
                    <input type="text"/>
                    <label>Repeat Password*
                    </label>
                    <input type="text"/>

                </p>
            </fieldset>
          
                       
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
