<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "library/common.php";
require('SpreadsheetReader.php');
$msg = ''; 

function isValidDateDMY($date) {
    // Match format d.m.Y (e.g., 20.09.2025)
    if (preg_match('/^([0-2][0-9]|3[01])\.(0[1-9]|1[0-2])\.(19|20)\d\d$/', $date)) {
        list($day, $month, $year) = explode('.', $date);
        return checkdate((int)$month, (int)$day, (int)$year);
    }
    return false;
}
function dt_format($ddmmyyyy) {
    $dt = explode('.', $ddmmyyyy);
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
$UnitQuery  =	"SELECT * from unit "; 
$ResultQuery2	=	mysql_query($UnitQuery);
$UnitArr = array();
if ($ResultQuery2) {
    if (mysql_num_rows($ResultQuery2) > 0) {
        while ($UnitData = mysql_fetch_object($ResultQuery2)) {
            $UnitArr[$UnitData->id] = $UnitData->unit_name;
        }
    }
}
if(isset($_POST["btn_upload"])){ 
	if ($_FILES['excel_file']['name'] != "") 
	{
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["excel_file"]["name"]);
        $currentfilename = basename($_FILES["excel_file"]["name"]);
//echo "<br>Name  :".$target_file."<br>";		
        $checkupload = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        if ($_FILES["excel_file"]["size"] > 500000) 
		{
            $msg = $msg . " Sorry, your file is too large." . "<br/>";
            $checkupload = 0;
        }
        if (strtolower($imageFileType) != "xls" && strtolower($imageFileType) != "xlsx") 
		{
            $msg = $msg . " Sorry, only xls or xlsx files are allowed." . "<br/>";
            $checkupload = 0;
        }

        if(move_uploaded_file($_FILES["excel_file"]["tmp_name"], $target_file)) 
		{
             $checkupload = 1;
        }  
        if($checkupload == 1) {            

            $SheetId 		= $_POST['cmb_work_no'];
            $Material 		= $_POST['cmb_material'];
	        $SheetName 		= $_POST['txt_excel_sheet'];
	        $ExcelStrRow	= $_POST['txt_excel_strow'];
	        $ExcelEndRow    = $_POST['txt_excel_endrow'];	            
            $Spreadsheet = new SpreadsheetReader("uploads/" . $currentfilename);
			//echo'<pre>';print_r($data->sheets);exit;
			$Sheets = $Spreadsheet->Sheets(); 
            foreach ($Sheets as $Index => $Name) { // Loop to get all sheets in a file.
                if($Name == $SheetName ){  
                    $ExcelData = $Spreadsheet->ChangeSheet($Index);
                    $X = 0;
                    $AllDataArr = array(); 
                    foreach ($Spreadsheet as $Key => $Row) { 
                        if ($Key >= ($ExcelStrRow-1) && $Key <= ($ExcelEndRow-1)) {
                            if($Material == 'RMC' || $Material == 'STE'){

                                $InvExcelDate   = isset($Row[0]) ? trim($Row[0]) : '';
                                $InvNo          = isset($Row[1]) ? trim($Row[1]) : '';
                                $Co_eff         = isset($Row[2]) ? trim($Row[2]) : '';
                                $BtsAccQty      = isset($Row[3]) ? trim($Row[3]) : '';
                                $BtsQtyCalc     = isset($Row[4]) ? trim($Row[4]) : '';
                                $BtsQtyUnit     = isset($Row[5]) ? trim($Row[5]) : '';
                                $CemSubCat      = isset($Row[6]) ? trim($Row[6]) : '';
                                $CemGrade       = isset($Row[7]) ? trim($Row[7]) : '';
                                $ReceivdDateExc = isset($Row[8]) ? trim($Row[8]) : '';
                                $Remarks        = isset($Row[9]) ? trim($Row[9]) : '';
                                $errors = array();

                                if (empty($InvExcelDate) || !isValidDateDMY($InvExcelDate)) {
                                    $errors[] = "Invalid Invoice Date.";
                                }
                            
                                if (empty($InvNo) || strlen($InvNo) > 50) {
                                    $errors[] = "Invalid Invoice No.";
                                }
                            
                                if (!isset($BtsAccQty) || !is_numeric($BtsAccQty)) {
                                    $errors[] = "Invalid Invoice Quantity.";
                                }
                            
                                if (!empty($InvExcelDate) && !empty($InvNo) && isset($BtsAccQty)) {
                                    if (!isset($Co_eff ) || !is_numeric($Co_eff )) {
                                        $errors[] = "nvalid Coefficient.";
                                    }
                                }
                            
                                if (!empty($InvExcelDate) && !empty($InvNo) && isset($BtsAccQty)) {
                                    if (!isset($BtsQtyCalc) || !is_numeric($BtsQtyCalc)) {
                                        $errors[] = "Invalid Calculation Quantity.";
                                    }
                                }
                                if (!empty($InvExcelDate) && !empty($InvNo) && isset($BtsAccQty)) {
                                    if (empty($BtsQtyUnit) || !in_array($BtsQtyUnit, $UnitArr)) {
                                        $errors[] = "Invalid Unit.";
                                    }
                                }

                                if (!empty($InvExcelDate) && !empty($InvNo) && isset($BtsAccQty)) {
                                    if (empty($CemSubCat) || 
                                        !preg_match('/^[a-zA-Z0-9\-_@ ]+$/', $CemSubCat) || 
                                        strlen($CemSubCat) > 50) {
                                        $errors[] = "Invalid Material Sub Category.";
                                    }
                                }
                            
                                if (!empty($InvExcelDate) && !empty($InvNo) && isset($BtsAccQty)) {
                                    if (empty($CemGrade) || 
                                        !preg_match('/^[a-zA-Z0-9\-_@ ]+$/', $CemGrade) || 
                                        strlen($CemGrade) > 50) {
                                        $errors[] = "Invalid Grade.";
                                    }
                                }
                            

                                if (!empty($ReceivdDateExc) && !isValidDateDMY($ReceivdDateExc)) {
                                    $errors[] = "Invalid Received Date.";
                                }
                                if(count($errors) > 0){ 
                                    $ErrorRem = implode(',',$errors);
                                }else{
                                    $ErrorRem = '';
                                }
                               
                                $AllDataArr[$x]['INVDATE']    = $InvExcelDate;
                                $AllDataArr[$x]['INVNUM']     = $InvNo;
                                $AllDataArr[$x]['COEFF']      = $Co_eff;
                                $AllDataArr[$x]['BTSACCQTY']  = $BtsAccQty;
                                $AllDataArr[$x]['BTSQTYCALC'] = $BtsQtyCalc;
                                $AllDataArr[$x]['BTSQTYUNIT'] = $BtsQtyUnit;
                                $AllDataArr[$x]['CEMSUBCAT']  = $CemSubCat;
                                $AllDataArr[$x]['CEMGRADE']   = $CemGrade;
                                $AllDataArr[$x]['RECDATE']    = $ReceivdDateExc;
                                $AllDataArr[$x]['REMARKS']    = $Remarks;
                                $AllDataArr[$x]['ERROR']    = $ErrorRem;
                            }else{
                                $InvExcelDate   = isset($Row[0]) ? trim($Row[0]) : '';
                                $InvNo          = isset($Row[1]) ? trim($Row[1]) : '';
                                $MatSubCat      = isset($Row[2]) ? trim($Row[2]) : '';
                                $BtsQty         = isset($Row[3]) ? trim($Row[3]) : '';
                                $BtsQtyUnit     = isset($Row[4]) ? trim($Row[4]) : '';
                                $ReceivdDateExc = isset($Row[5]) ? trim($Row[5]) : '';
                                $Remarks        = isset($Row[6]) ? trim($Row[6]) : '';

                                $errors = array();

                                if (empty($InvExcelDate) || !isValidDateDMY($InvExcelDate)) {
                                    $errors[] = "Invalid Invoice Date";
                                }

                                if (empty($InvNo) || strlen($InvNo) > 50) {
                                    $errors[] = "Invalid Invoice No.";
                                }

                                if (!isset($BtsQty) || !is_numeric($BtsQty)) {
                                    $errors[] = "Invalid Invoice Quantity.";
                                }
                                if (!empty($InvExcelDate) && !empty($InvNo) && isset($BtsQty)) {
                                    if (empty($BtsQtyUnit)) {
                                        $errors[] = "Invalid Unit.";
                                    }
                                }
                                if (!empty($ReceivdDateExc) && !isValidDateDMY($ReceivdDateExc)) {
                                    $errors[] = "Invalid Received Date.";
                                }

                                if(count($errors) > 0){ 
                                    $ErrRemarkStr = implode(',',$errors);
                                }else{
                                    $ErrRemarkStr = '';
                                }

                                $AllDataArr[$x]['INVDATE']    = $InvExcelDate;
                                $AllDataArr[$x]['INVNUM']     = $InvNo;
                                $AllDataArr[$x]['MATSUBCAT']  = $MatSubCat;
                                $AllDataArr[$x]['BTSQTY']     = $BtsQty;
                                $AllDataArr[$x]['BTSQTYUNIT'] = $BtsQtyUnit;
                                $AllDataArr[$x]['RECDATE']    = $ReceivdDateExc;
                                $AllDataArr[$x]['REMARKS']    = $Remarks;
                                $AllDataArr[$x]['ERROR']      = $ErrRemarkStr;
                            }
                        }
                        $x++;
                    }
                }
			     
            } // Loop to get all sheets in a file.
        } 
    }
 
}
if(isset($_POST["save"])){
	$SheetId 		= $_POST['txt_sheetid'];
	$HidMaterial 		= $_POST['txt_mat_type_code'];
    $MatType = "";
	$SelectQuery  =	"SELECT mat_type from material where mat_code = '$HidMaterial'"; 
	$ResultQuery	=	mysql_query($SelectQuery);
	if($ResultQuery == true){
		if(mysql_num_rows($ResultQuery)>0){
			$MatList = mysql_fetch_object($ResultQuery);
			$MatType = $MatList->mat_type;
		}
	}    
    if($HidMaterial == 'RMC' || $HidMaterial == 'STE'){
        $InvoiceDts = $_POST['txt_invdate'];
        $InvoiceNos = $_POST['txt_invno'];
        $Co_effs = $_POST['txt_coeff'];
        $BtsActQtys = $_POST['txt_btsactualqty'];
        $BtsQtyCals = $_POST['txt_btsqtycalc'];
        $BtsQtyUnits = $_POST['txt_btsqtyunit'];
        $CemSubCats = $_POST['txt_cemsubcat'];
        $CemGrades = $_POST['txt_cemgrade'];
        $ReceivdDateExc = $_POST['txt_recdate'];
        $Remarks = $_POST['txt_remarks'];
        foreach($InvoiceDts as $Key => $value){
            $BtsUnit = array_search($BtsQtyUnits[$Key], $UnitArr);
            $InsertQuery 	= "insert into mat_invoice set sheetid= '$SheetId', matid = '', mat_code = '$HidMaterial', mat_type = '$MatType', invoice_dt = '$InvoiceDts[$Key]', received_dt = '$ReceivdDateExc[$Key]', qty = '$BtsActQtys[$Key]', qty_unit = '$BtsUnit', invoice_no = '$InvoiceNos[$Key]', active = 1, created_by = '".$_SESSION['userid']."', created_on = NOW()";
            $InsertSql		= mysql_query($InsertQuery);
        }

    }else{
        $InvoiceDts = $_POST['txt_invdate'];
        $InvoiceNos = $_POST['txt_invno'];
        $MatSubCats = $_POST['txt_matsubcat'];
        $BtsQtys    = $_POST['txt_btsqty'];
        $BtsQtyUnits = $_POST['txt_btsqtyunit'];
        $ReceivdDateExcs = $_POST['txt_recdate'];
        $Remarks    = $_POST['txt_remarks'];
        foreach($InvoiceDts as $Key => $value){
            $InsertQuery 	= "insert into mat_invoice set sheetid= '$SheetId', matid = '', mat_code = '$HidMaterial', mat_type = '$MatType', invoice_dt = '$InvoiceDts[$Key]', received_dt = '$ReceivdDateExcs[$Key]', qty = '$BtsQtys[$Key]', qty_unit = '$BtsQtyUnits[$Key]', invoice_no = '$InvoiceNos[$Key]', active = 1, created_by = '".$_SESSION['userid']."', created_on = NOW()";
            $InsertSql		= mysql_query($InsertQuery);
        }
    }
	if($InsertSql){
		$msg = "Invoice Details Saved Successfully";
	}else{
		$msg = "Error : Invoice Details Not Saved. Please try again.";
	}
}
?>
<script>
    function goBack()
	{
	   	url = "MaterialBroughtToSiteOthers.php";
		window.location.replace(url);
	}
   
</script>
<?php require_once "Header.html"; ?>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">  
                <div class="title">Material View</div>
                <div class="container_12">  
                    <div class="grid_12" align="center"> 
                        <blockquote class="bq1" id="bq1" style="overflow:auto;">
                            <div class="container" align="center">
                                <div class="row clearrow"></div>                                
                                <table class='DTable' border='1' cellpadding='5' cellspacing='0'>
                                    <thead>
                                        <?php if($Material == 'RMC' || $Material =='STE'){ ?>
                                        <tr>
                                            <th style="text-align:center">S.No.</th>
											<th style="text-align:center">Invoice Date</th>
											<th style="text-align:center">Invoice No.</th>
											<th style="text-align:center">Co-efficient</th>
											<th style="text-align:center">Brought to site Actual Quantity</th>
											<th style="text-align:center">Brought to site Quantity for calculation</th>
											<th style="text-align:center">Brought to site Unit</th>
											<th style="text-align:center">Cement Sub-Category</th>
											<th style="text-align:center">Cement Grade</th>
											<th style="text-align:center">Received Date</th>
											<th style="text-align:center">Remarks</th>
											<th style="text-align:center">Error</th>
                                        </tr>
                                        <?php }else{ ?>
                                            <tr>
                                                <th style="text-align:center">S.No.</th>
												<th style="text-align:center">Invoice Date</th>
												<th style="text-align:center">Invoice No.</th>
												<th style="text-align:center">Material Sub Category</th>
												<th style="text-align:center">Brought to site Quantity</th>
												<th style="text-align:center">Brought to site Unit</th>
												<th style="text-align:center">Received Date</th>
												<th style="text-align:center">Remarks</th>
												<th style="text-align:center">Error</th>
                                            </tr>
                                        <?php } ?>
                                    </thead>
                                    <tbody>
                                    <?php $Sno = 1;
                                        if(!empty($AllDataArr)){ 
                                            foreach ($AllDataArr as $Data) { 
                                                if($Material == 'RMC' || $Material =='STE'){                                               
                                                    $InvExcelDate   = isset($Data['INVDATE']) ? trim($Data['INVDATE']) : '';
                                                    $InvNo          = isset($Data['INVNUM']) ? trim($Data['INVNUM']) : '';
                                                    $Co_eff         = isset($Data['COEFF']) ? trim($Data['COEFF']) : '';
                                                    $BtsAccQty      = isset($Data['BTSACCQTY']) ? trim($Data['BTSACCQTY']) : '';
                                                    $BtsQtyCalc     = isset($Data['BTSQTYCALC']) ? trim($Data['BTSQTYCALC']) : '';
                                                    $BtsQtyUnit     = isset($Data['BTSQTYUNIT']) ? trim($Data['BTSQTYUNIT']) : '';
                                                    $CemSubCat      = isset($Data['CEMSUBCAT']) ? trim($Data['CEMSUBCAT']) : '';
                                                    $CemGrade       = isset($Data['CEMGRADE']) ? trim($Data['CEMGRADE']) : '';
                                                    $ReceivdDateExc = isset($Data['RECDATE']) ? trim($Data['RECDATE']) : '';
                                                    $Remarks        = isset($Data['REMARKS']) ? trim($Data['REMARKS']) : '';
                                                    $ErrorRemarks   = isset($Data['ERROR']) ? trim($Data['ERROR']) : '';
                                        ?>
                                            <tr>
                                                <td><?php echo $Sno; ?></td>
                                                <td><?php echo $InvExcelDate; ?><input type="hidden" name="txt_invdate[]" id="txt_invdate" value="<?php echo dt_format($InvExcelDate); ?>"  /></td>
                                                <td><?php echo $InvNo; ?><input type="hidden" name="txt_invno[]" id="txt_invno" value="<?php echo $InvNo; ?>" /></td>
                                                <td><?php echo $Co_eff; ?><input type="hidden" name="txt_coeff[]" id="txt_coeff" value="<?php echo $Co_eff; ?>"  /></td>
                                                <td><?php echo $BtsAccQty; ?><input type="hidden" name="txt_btsactualqty[]" id="txt_btsactualqty" value="<?php echo $BtsAccQty; ?>"  /></td>
                                                <td><?php echo $BtsQtyCalc; ?><input type="hidden" name="txt_btsqtycalc[]" id="txt_btsqtycalc" value="<?php echo $BtsQtyCalc; ?>"  /></td>
                                                <td><?php echo $BtsQtyUnit; ?><input type="hidden" name="txt_btsqtyunit[]" id="txt_btsqtyunit" value="<?php echo $BtsQtyUnit; ?>"  /></td>
                                                <td><?php echo $CemSubCat; ?><input type="hidden" name="txt_cemsubcat[]" id="txt_cemsubcat" value="<?php echo $CemSubCat; ?>"  /></td>
                                                <td><?php echo $CemGrade; ?><input type="hidden" name="txt_cemgrade[]" id="txt_cemgrade" value="<?php echo $CemGrade; ?>"  /></td>
                                                <td><?php echo $ReceivdDateExc; ?><input type="hidden" name="txt_recdate[]" id="txt_recdate" value="<?php echo dt_format($ReceivdDateExc); ?>" /></td>
                                                <td><?php echo $Remarks; ?><input type="hidden" name="txt_remarks[]" id="txt_remarks" value="<?php echo $Remarks; ?>" /></td>
                                                <td style="text-align:left;color:red;"><?php echo $ErrorRemarks; ?><input type="hidden" value="<?php echo $ErrorRemarks; ?>" class="error_rem" /></td>
                                            </tr>
                                        <?php 
                                                }else{
                                                    $InvExcelDate   = isset($Data['INVDATE']) ? trim($Data['INVDATE']) : '';
                                                    $InvNo          = isset($Data['INVNUM']) ? trim($Data['INVNUM']) : '';
                                                    $MatSubCat      = isset($Data['MATSUBCAT']) ? trim($Data['MATSUBCAT']) : '';
                                                    $BtsQty         = isset($Data['BTSQTY']) ? trim($Data['BTSQTY']) : '';
                                                    $BtsQtyUnit     = isset($Data['BTSQTYUNIT']) ? trim($Data['BTSQTYUNIT']) : '';
                                                    $ReceivdDateExc = isset($Data['RECDATE']) ? trim($Data['RECDATE']) : '';
                                                    $Remarks        = isset($Data['REMARKS']) ? trim($Data['REMARKS']) : '';
                                                    $ErrorRemarks   = isset($Data['ERROR']) ? trim($Data['ERROR']) : '';
                                        ?>
                                            <tr>
                                                <td><?php echo $Sno; ?></td>
                                                <td><?php echo $InvExcelDate; ?><input type="hidden" name="txt_invdate[]" id="txt_invdate" value="<?php echo dt_format($InvExcelDate); ?>"/></td>
                                                <td><?php echo $InvNo; ?><input type="hidden" name="txt_invno[]" id="txt_invno" value="<?php echo $InvNo; ?>"  /></td>
                                                <td><?php echo $MatSubCat; ?><input type="hidden" name="txt_matsubcat[]" id="txt_matsubcat" value="<?php echo $MatSubCat; ?>"  /></td>
                                                <td><?php echo $BtsQty; ?><input type="hidden" name="txt_btsqty[]" id="txt_btsqty" value="<?php echo $BtsQty; ?>"  /></td>
                                                <td><?php echo $BtsQtyUnit; ?><input type="hidden" name="txt_btsqtyunit[]" id="txt_btsqtyunit" value="<?php echo $BtsQtyUnit; ?>" /></td>
                                                <td><?php echo $ReceivdDateExc; ?><input type="hidden" name="txt_recdate[]" id="txt_recdate" value="<?php echo dt_format($ReceivdDateExc); ?>"  /></td>
                                                <td><?php echo $Remarks; ?><input type="hidden" name="txt_remarks[]" id="txt_remarks" value="<?php echo $Remarks; ?>"  /></td>
                                                <td style="text-align:left;color:red;"><?php echo $ErrorRemarks; ?><input type="hidden" value="<?php echo $ErrorRemarks; ?>" class="error_rem"  /></td>
                                            </tr>
                                        <?php
                                                }
                                                $Sno++;
                                            }
                                        }
                                    ?>

                                    </tbody>
                                </table>
                                <div class="row clearrow"></div>
                                <div>
                                    <input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php echo $SheetId ;?>" />
                                    <input type="hidden" name="txt_mat_type_code" id="txt_mat_type_code" value="<?php echo $Material ;?>" />
                                    <input type="submit" data-type="submit" value=" Save " name="save" id="save"/>
									<input type="button" data-type="submit" value=" back " class="backbutton" name="btn_back" id="btn_back" onclick="goBack()"/>
                                </div>                            
                            </div>
                        </blockquote>        
                    </div>
                </div>                 
            </div>             
             <!--==============================footer=================================-->

        </form>
    </body>
</html>
<?php   include "footer/footer.html"; ?>
<script>
     $(document).ready(function () {
        var msg = "<?php echo $msg; ?>";
		if(msg != ""){
			BootstrapDialog.show({
				title: 'Information',
				closable: false,
				message: msg,
				buttons: [{
					label: ' OK ',
					cssClass: 'btn-primary',
					action: function(dialogRef) {
						dialogRef.close();
                        url = "MaterialBroughtToSiteOthers.php";
		                window.location.replace(url);
					}
				}]
			});
		}
        var KillEvent = 0;
		$('body').on("click","#save", function(event){ 
			if(KillEvent == 0){
                var Error = 0;
                $(".error_rem").each(function() {
                    var ErrorRem = $(this).val();
                    if(ErrorRem != ''){
                        Error = 1;
                        return false;
                    }
                });
                if(Error == 1){
					BootstrapDialog.alert("Invalid Entry..!");
					event.preventDefault();
					event.returnValue = false;
				}else{
					event.preventDefault();
					BootstrapDialog.confirm('Are you sure want to Save ?', function(result){
						if(result) {
							KillEvent = 1;
							$("#save").trigger( "click" );
						}
					});
				}
            }
        });
    });
</script>
