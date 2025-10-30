<?php
//session_start();
@ob_start();
include "db_connect.php";
include "library/common.php";
require_once 'ExcelReader/excel_reader2.php';
$msg = '';

if ($_POST["submit"] == "Upload") {
//if (isset($_POST["submit"])) {
    if ($_FILES['file']['name'] != "") {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $currentfilename = basename($_FILES["file"]["name"]);
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
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                //$msg = $msg . "File Uploaded Sucessfully" . "<BR>";
                //echo $msg;
                  $checkupload = 1;
            } else {
                 $msg = $msg . "Sorry, there was an error uploading your file." . "<BR>";
                  $checkupload = 0;
            }
        }
    } else {
        $checkupload = 0;
        $msg = "Sorry, there was an error uploading your file." . "<BR>";
    }
   
    if ($checkupload == 1) {
        $header='';$sdiv='';
        $headerrecord = array();
        $headerdetail = array();
        $list = '';
        $detail = array();
       // $currentfilename="EH-1V RAB 8.xls";
        $data = new Spreadsheet_Excel_Reader("uploads/".$currentfilename);
        for ($i = 0; $i < count($data->sheets); $i++) { // Loop to get all sheets in a file.
            if (count($data->sheets[$i][cells]) > 0) { // checking sheet not empty
                $last_mbookid = 0;
                
                for ($j = 1; $j <= count($data->sheets[$i][cells]); $j++) { // loop used to get each row of the sheet   
                    $list_a = $data->sheets[$i][cells][$j][1];
                    $list_b = $data->sheets[$i][cells][$j][2]; 
                    $list_c = $data->sheets[$i][cells][$j][3]; 
                    $list_d = $data->sheets[$i][cells][$j][4];//
                    $list_e = $data->sheets[$i][cells][$j][6];///
                    $list_f = $data->sheets[$i][cells][$j][7];
                    $list_g = $data->sheets[$i][cells][$j][8];
                    $list_h = $data->sheets[$i][cells][$j][9];
                    $list_i = $data->sheets[$i][cells][$j][10];
                    $header='';
                    // echo  $list_b."--<br>";                    
                   // echo  $list_a."--".$list_c."<br>";                    
                    //echo $list_a." <br> ". $list_b." <br> ". $list_c." <br> ". $list_d." <br> ". $list_e." <br>". $list_f." <br> ". $list_g.'<br>'.$list_h." <br> ". $list_i;
                    $searchword_work = "Name of work:";
                    if (strpos($list_a, $searchword_work) !== false) {
                        $work_name = $list_c;
                          $headerrecord[0] = 1; $header =$header."*"."1";
                    } else {
                        $workheader = 0; $headerrecord[0] = 0; $header =$header."*"."2";
                    }
                    $searchword_sanction = "Technical Sanction No.";
                    if (strpos($list_a, $searchword_sanction) !== false) {
                        $sanction = $list_c;
                         $headerrecord[1] = 1; $header =$header."*"."1";
                    } else {
                        $Sanctionheader = 0;$header =$header."*"."2";$headerrecord[1] = 0;
                    }
                    $searchword_workorder = "Work Order No.";
                    if (strpos($list_a, $searchword_workorder) !== false) {
                        $work_order = $list_c;
                       $header =$header."*"."1";$headerrecord[2] = 1;
                    } else {
                        $orderheader = 0;$header =$header."*"."2";$headerrecord[2] = 0;
                    }
                    $searchword_contractor = "Name of the contractor";
                    if (strpos($list_a, $searchword_contractor) !== false) {
                        $contractor = $list_c;
                        $header =$header."*"."1";$headerrecord[3] = 1;
                    } else {
                        $contractorheader = 0;$header =$header."*"."2";$headerrecord[3] = 0;
                    }
                    $searchword_contractor = "Agreement No.";
                    if (strpos($list_a, $searchword_contractor) !== false) {
                        $agreement = $list_c;
                       $header =$header."*"."1";$headerrecord[4] = 1;
                    } else {
                        $agreementheader = 0;$header =$header."*"."2";$headerrecord[4] = 0;
                    }
                    $searchword_billing = "Running Account bill No.";
                    if (strpos($list_a, $searchword_billing) !== false) {
                        $billing = $list_c;
                        $header =$header."*"."1";$headerrecord[5] = 1;
                    } else {
                        $header =$header."*"."8";$headerrecord[5] = 0;
                    }
                    if(in_array("1", $headerrecord))       {   $insertheader = 1;  }     else {  $insertheader = 0; }
                    
                    
                     //$explodevalues = explode("*", $header);
//                    for($init = 0; $init < count($explodevalues); $init++){
//                        if($explodevalues[$init] == "1") {  $init=count($explodevalues) +1 ;  }
//                        else {  $insertheader = 0; }
//                    }
//                     for($init = 0; $init < count($headerrecord); $init++){
//                      //  echo $headerrecord[$init]."<br>";
//                        
//                    }
                      //echo $work_name."&nbsp;&nbsp;". $sanction." &nbsp;&nbsp; ". $work_order." &nbsp;&nbsp; ". $contractor." &nbsp;&nbsp; ". $agreement." &nbsp;&nbsp; ". $billing."<br>";
               //     echo $headerrecord[0] ."&nbsp;&nbsp;". $headerrecord[1] ."&nbsp;&nbsp;". $headerrecord[2] ."&nbsp;&nbsp;". $headerrecord[3]."&nbsp;&nbsp;". $headerrecord[4] ."&nbsp;&nbsp;". $headerrecord[5]."<br>";                    
                     if ($work_order != '' && $last_mbookid == 0) {
                        $sql_subdivname = " SELECT   DISTINCT subdivision.subdiv_id  , subdivision.div_id ,sheet.sheet_id
												FROM   subdivision
												INNER JOIN schdule      ON (subdivision.subdiv_id = schdule.subdiv_id)
												INNER JOIN sheet        ON (schdule.sheet_id = sheet.sheet_id)
											 	WHERE sheet.work_order_no='$work_order'";
                        //echo $sql_subdivname."<br>";
                        $rs_subdivname = mysql_query($sql_subdivname, $conn);
                        if ($rs_subdivname == true) {
                            $subdivlist = mysql_fetch_object($rs_subdivname);
                            $subdiv_id = $subdivlist->subdiv_id;
                            $div_id = $subdivlist->div_id;
                            $sheetid = $subdivlist->sheet_id;
                        }
                        $sql_mbookheader = "insert into mbook_header set date=NOW(),
                                                    div_id ='$div_id',subdiv_id ='$subdiv_id',tech_sanction='$sanction',
                                                    name_contractor='$contractor',agree_no='$agreement',
						    sheet_id ='$sheetid',runn_acc_bill_no='$billing' , active=1,userid =1   ";

                      //  echo $sql_mbookheader.'<br>';
                       $rs_mbookheader = mysql_query($sql_mbookheader);

                        $lastmbook_id = mysql_insert_id();
                     $last_mbookid = 1; }
                     if ($list_b != ''){ $bb= $list_b;
                         $getid=getsubdivid($list_b);
}
                      //echo "subdiv_id".$getid."&nbsp;&nbsp;list_b &nbsp;&nbsp;".$bb."&nbsp;&nbsp;list_b".$list_h."<br>";
                        if ($list_c != '' && $list_d != '' && $list_h != '') {
                            
                           
                          
                                $sql_mbookdetail = "insert into mbook_detail set mbheader_id='$last_mbookid', subdiv_id='$getid', desc_work='$list_c',
                                                                      measurement_no ='$list_d', measurement_l ='$list_e',measurement_b  ='$list_f', measurement_d ='$list_g', measurement_contentarea ='$list_h', remarks ='$list_i'";
                               //echo $sql_mbookdetail."<br>";
                               // echo "<br>";
                               $rs_mbookdetails = mysql_query($sql_mbookdetail);
                                        
                            }
                   $sdiv = $list_b;
                }// loop used to get each row of the sheet
            }// checking sheet not empty
        }// Loop to get all sheets in a file.
    } //$checkupload

    if ($rs_mbookheader == true && $rs_mbookdetails == true) {
        $msg = " Excel Sheet Uploaded Successfully <br>   Data Inserted Successfully";
        //echo $msg;
    }
} //submit 
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title> :: Measurement :: </title>
        <meta charset="utf-8">
        <meta name = "format-detection" content = "telephone=no" />
        <link rel="icon" href="images/favicon.ico">
        <link rel="shortcut icon" href="images/favicon.ico" />
        <link rel="stylesheet" href="css/form.css">
        <link rel="stylesheet" href="script/igstyle1.css">
        <link rel="stylesheet" href="Font style/font.css" />
        <link rel="stylesheet" href="css/style.css">
        <script src="js/jquery.js"></script>
        <script src="js/jquery-migrate-1.2.1.js"></script>
        <script src="js/script.js"></script>
        <script src="js/superfish.js"></script>
        <script src="js/sForm.js"></script>
        <script src="js/jquery.ui.totop.js"></script>
        <script src="js/jquery.equalheights.js"></script>
        <script src="js/jquery.easing.1.3.js"></script>
        <script src="js/jquery.iosslider.min.js"></script>
        <script language="JavaScript" type="text/javascript" src="script/Date_Calendar.js"></script>
        <script language="JavaScript" type="text/javascript" src="script/validfn.js"></script>
        <link rel="stylesheet" href="css/menustyle.css" type="text/css" />
        <script type="text/javascript" src="js/menuscripts.js"></script>

        <!--[if lt IE 8]>
        <div style=' clear: both; text-align:center; position: relative;'>
                <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
                        <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
                </a>
        </div>
        <![endif]-->
        <!--[if lt IE 9]>
        <script src="js/html5shiv.js"></script>
        <link rel="stylesheet" media="screen" href="css/ie.css">
        <![endif]-->
    </head>
    <body class="page1" id="top">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
        <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1">
                            <div class="title">Measurement Sheet (M Book) </div>
                            <table width="900" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr>
                                    <td colspan="4"  align="center">&nbsp;</td>
                                </tr>
                                <tr><td colspan="4"  align="center">Upload File :&nbsp;<input type="file" class="text" name="file" size="40" style="height:23px;" /></td></tr>
                                <tr>
                                    <td align="center"class="smalllabcss" >Upload files allow the file formats of : .xls  , .xlsx</td>
                                <tr>
                                    <td align="center">&nbsp;</td>
                                <tr>
                                    <td align="center" width="50%">
                                        <input type="hidden" class="text" name="submit" value="true" />
                                        <input type="submit" class="btn" data-type="submit" value="Upload" name="submit" id="submit" />
<!--                                        <input type="submit" class="btn" name="btn_export" id="btn_export" value="Excel" />-->
                                    </td>
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
            <footer>
                <div class="container_12">
                    <div class="grid_12">
                        <div class="copy">
                            &copy; 2015 | <a href="#">Privacy Policy</a> <br> 	 <a href="#" rel="nofollow">lashron.com</a>
                        </div>
                    </div>
                </div>
            </footer>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
</html>
