
<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
require('SpreadsheetReader.php');
include "common.php";
$PageName = $PTPart1.$PTIcon.'Contractor Detail Upload';
//checkUser();
$msg = ""; $del = 0;
$RowCount = 0;
$staffid  = $_SESSION['sid'];
function dt_format($ddmmyyyy){
	$dt=explode('/',$ddmmyyyy);
	$dd=$dt[0];
	$mm=$dt[1];
	$yy=$dt[2];
	return $yy .'-'. $mm .'-'.$dd;
}
function dt_display($ddmmyyyy){
	$dt=explode('-',$ddmmyyyy);
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	return $dd .'/'. $mm .'/'.$yy;
}
$PriceBidLocation = 'uploads/';//"PriceBid/";
//echo $PriceBidLocation;exit;
$RowCount = 0;

if(isset($_POST["upload"]) == "Upload File"){
	$TrId 			= 1;
	$SheetName 		= $_POST['txt_sheetname'];
	$StartRow 		= $_POST['txt_start_row'];
	$EndRow 			= $_POST['txt_end_row'];
	$WorkType		= $_POST['cmb_worktype'];
	$UploadFile 	= $_FILES['file']['name'];

	if($_FILES['file']['name'] != ""){
      $target_dir 		= $PriceBidLocation;	//$_SERVER['DOCUMENT_ROOT'].'/wcms/mbook/IGCwcMSCIVIL/PriceBid/';//"PriceBid/";
		//echo $target_dir; exit;
		$UploadDate 		= date('dmYHis');
        $target_file 		= $target_dir.$TrId.basename($_FILES["file"]["name"]);
        $currentfilename 	= $TrId.basename($_FILES["file"]["name"]);
        $checkupload 		= 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        if(file_exists($target_file)){
			unlink($target_file);
        }
		  //echo $_FILES["file"]["size"];exit;

        if($_FILES["file"]["size"] > 500000) {
            $msg = $msg." Sorry, your file is too large." . "<br/>";
            $checkupload = 0;
        }

        if(strtolower($imageFileType) != "xls" && strtolower($imageFileType) != "xlsx") {
            $msg = $msg." Sorry, only xls files are allowed." . "<br/>";
            $checkupload = 0;
        }
        if($checkupload == 0) {
            $msg = $msg." Sorry, your file was not uploaded." . "<br/>";
        }else{
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            	$checkupload = 1;
            }else{
                $checkupload = 0;
                $msg = $msg."Sorry, there was an error uploading your file." . "<br/>";
            }
        }
    } 
	//echo $target_file;exit;
	$work_order_cost = 0;
	$first = 0; $prev_item =''; $subdivisionlast_id = 0; $sheetCnt = 0;  $Exectemp = 0; $InsertTemp = 0;
	$slno = '';
}
if(isset($_POST["updatedata"]) == "Update Data"){
	$UpdatedCCNOArr 	= array();
	$InsertedCCNOArr 	= array();
	$SelectTempMastQuery = "SELECT * FROM work_master_temp";
	$SelectTempMastQuerySql = mysqli_query($dbConn,$SelectTempMastQuery);
	if($SelectTempMastQuerySql == true){
		if(mysqli_num_rows($SelectTempMastQuerySql) > 0){
			while($TempList = mysqli_fetch_object($SelectTempMastQuerySql)){
				$CCode 			= $TempList->ccode;
				//echo $CCode;
				$CCex 			= $TempList->ccex;
				$BillNumber		= $TempList->billno;	
				//$BillNumber		= substr($BillNumberStr, 0, 2);
				//echo $BillNumber;exit;
				$ContName 		= $TempList->cname;			//
				$NameWork		= $TempList->work;			//
				$WoNumber 		= $TempList->wono;			//
				$WoDate 		= $TempList->wodate;		//
				$WorkExtDate 	= $TempList->wrk_ext_date;	//
				$AggreNumber 	= $TempList->agtno;			//
				$AggreDate 		= $TempList->agtdate;		//
				$WoValue 		= $TempList->woval;			//
				$TechSanNumber = $TempList->tsno;			//
				$HoaCode 		= $TempList->hoafc;			//
				$HoaShortCode 	= $TempList->hoa_code;		//
				$GstRate 		= $TempList->gst_rate;		//
				$OvDate 		= $TempList->ovdate;
				$Validval 		= $TempList->valid;
				$ItrRate 		= $TempList->itrate;		//
				$Itr 			= $TempList->itr;
				$Crate 			= $TempList->crate;
				$SRate 			= $TempList->srate;
				$TnGst 			= $TempList->tngst;
				$Igst 			= $TempList->igst;			//
				$ClcRate 		= $TempList->clcrate;		//
				$WctRate 		= $TempList->wctrate;
				$GSTRate 		= $TempList->gstrate;
				$AmtWrate 		= $TempList->amtwrate;
				$pbg 			= $TempList->pbg;			//
				$PbgValue 		= $TempList->pbg_value;	
				$InstSerNumber  = $TempList->inst_serial_no;
				$BankName 		= $TempList->bank_name;
				$InstDate 		= $TempList->inst_date;
				$IsPbgRel 		= $TempList->is_pbg_rel;
				$PgRelDate 		= $TempList->pbg_rel_date;
				$PgValidDate 	= $TempList->pg_valid_date;	//
				$PgType 		= $TempList->pg_type;
				$IsGst 			= $TempList->is_gst_app;
				$IsLcess 		= $TempList->is_lcess_app;
				$GstIncExc 		= $TempList->gst_inc_exc;
				$SDbg 			= $TempList->sdbg;			//
				$SDbgValid 		= $TempList->sdbg_valid;	//
				$ItCode 		= $TempList->it_code;		//
				$NCost 			= $TempList->ncost;			//
				$Conmn 			= $TempList->conmn;
				$TotalSd 		= $TempList->total_sd;		//
				$UptodateSd 	= $TempList->upto_sd;		//
				$BalanceSd 		= $TempList->bala_sd;		//
				$UpdateVal 		= $TempList->update_val;	//
				$LastPayDate 	= $TempList->last_pay_date;	//
				$EngineerName 	= $TempList->engineer_name;	//
				$PayDate 		= $TempList->dat_of_pay;
				$Code 			= $TempList->code;
				$PanNumber 		= $TempList->pan_no;		//
				$TinNumber 		= $TempList->tin_no;
				$ComDt 			= $TempList->comdt;
				$DlpDt 			= $TempList->dlpdt;
				$PGdlp 			= $TempList->pgdlp;
				$Sdt 			= $TempList->sdt;
				$RetentionVal 	= $TempList->retention;
				$Compcc 		= $TempList->compcc;
				$GstNumber 		= $TempList->gst_no;		//
				$SectionVal 	= $TempList->section;
				$WorkStatusVal  = $TempList->work_status;
				$ActiveStatus 	= $TempList->active;

				$SelectWorksQuery = "SELECT * FROM works where ccno='$CCode'";
				$SelectWorksQuerySql = mysqli_query($dbConn,$SelectWorksQuery);
				if($SelectWorksQuerySql == true){
					if(mysqli_num_rows($SelectWorksQuerySql) > 0){
						$WorksList = mysqli_fetch_object($SelectWorksQuerySql);
						$GlobID = $WorksList->globid;
					}
				}
				
				if($GlobID != null){
					//	$Igst	$ItCode		$NCost	$TotalSd	$UptodateSd		$BalanceSd 	$UpdateVal	$EngineerName	$PanNumber	$GstNumber
					$InsUptQuery = "UPDATE works SET work_name='$NameWork', ts_no='$TechSanNumber', wo_no='$WoNumber', wo_amount='$WoValue', wo_date='$WoDate', name_contractor='$ContName', work_orders_ext='$WorkExtDate',
					hoa_sh_code='$HoaShortCode', gst_perc_rate='$GstRate', it_perc_rate ='$ItrRate', lbcess_rate = '$ClcRate', is_pbg='$pbg', pbg_valid_date='$PgValidDate', is_sdbg='$SDbg',sdbg_valid_date='$SDbgValid',
					agmt_no='$AggreNumber', agmt_date='$AggreDate', hoa_no='$HoaCode', final_pay_date='$LastPayDate', sd_amt='$SRate', work_status='$WorkStatusVal' WHERE globid = '$GlobID'";
					//$SelectWorksQuerySql = mysqli_query($dbConn,$InsUptQuery);
					array_push($UpdatedCCNOArr,$CCode);
				}else{
					$InsUptQuery = "INSERT INTO works SET work_name='$NameWork', ts_no='$TechSanNumber', wo_no='$WoNumber', wo_amount='$WoValue', wo_date='$WoDate',
					agmt_no='$AggreNumber', agmt_date='$AggreDate', hoa_no='$HoaCode', final_pay_date='$LastPayDate', sd_amt='$SRate', work_status='$WorkStatusVal', active=1";
					//$SelectWorksQuerySql = mysqli_query($dbConn,$InsUptQuery);
					//$GlobID = mysqli_insert_id($dbConn);
					array_push($InsertedCCNOArr,$CCode);
				}
				//echo $InsUptQuery;exit;
				$SelectSheetQuery = "SELECT * FROM sheet where globid='$GlobID'";
				$SelectSheetQuerySql = mysqli_query($dbConn,$SelectSheetQuery);
				if($SelectSheetQuerySql == true){
					if(mysqli_num_rows($SelectSheetQuerySql) > 0){
						$SheetList 	= mysqli_fetch_object($SelectSheetQuerySql);
						$SheetID 	= $SheetList->sheet_id;
					}
				}
				//	$Igst	$ItCode		$NCost		$TotalSd	$UptodateSd 	$BalanceSd 	$EngineerName	$PanNumber	$GstNumber
				if($SheetID != null){
					$InsUptSheetQuery = "UPDATE works SET sheetid='$SheetID' WHERE globid = '$GlobID'";
					$InsUptSheetQuerySql = mysqli_query($dbConn,$InsUptSheetQuery);
				}else{
					$InsUptQuery = "INSERT INTO sheet SET work_name='$NameWork', tech_sanction='$TechSanNumber', name_contractor='$ContName',
					work_order_no='$WoNumber', work_order_cost='$WoValue', work_order_date='$WoDate', computer_code_no='', rbn='$BillNumber', gst_perc_rate='$GstRate',
					work_orders_ext='$WorkExtDate',	hoa_sh_code = '$HoaShortCode', it_perc_rate ='$ItrRate', lbcess_rate = '$ClcRate', is_pbg='$pbg', pbg_valid_date='$PgValidDate', is_sdbg='$SDbg',
					final_pay_date='$LastPayDate', sdbg_valid_date='$SDbgValid', agree_no='$AggreNumber', agree_date='$AggreDate', hoa='$HoaCode', work_status='$WorkStatusVal',  sd_amt='$SRate', active=1";
					$SelectWorksQuerySql = mysqli_query($dbConn,$InsUptQuery);
					$SheetID = mysqli_insert_id($dbConn);
					
					$InsUptAbsBookQuery = "INSERT INTO abstractbook SET sheetid='$SheetID', rbn='$BillNumber', upto_date_total_amount='$UpdateVal'";
					$InsUptAbsBookQuerySql = mysqli_query($dbConn,$InsUptAbsBookQuery);
				}
				/*	
				if($GlobID != null){
					$InsUptQuery = "UPDATE works SET work_name='$NameWork', ts_no='$TechSanNumber', wo_no='$WoNumber', wo_amount='$WoValue', wo_date='$WoDate',
					agmt_no='$AggreNumber', agmt_date='$AggreDate', hoa_no='$HoaCode', final_pay_date='$LastPayDate', sd_amt='$SRate', work_status='$WorkStatusVal' WHERE globid = '$GlobID'";
					//$SelectWorksQuerySql = mysqli_query($dbConn,$InsUptQuery);
					array_push($UpdatedCCNOArr,$CCode);
				}else{
					$InsUptQuery = "INSERT INTO works SET work_name='$NameWork', ts_no='$TechSanNumber', wo_no='$WoNumber', wo_amount='$WoValue', wo_date='$WoDate',
					agmt_no='$AggreNumber', agmt_date='$AggreDate', hoa_no='$HoaCode', final_pay_date='$LastPayDate', sd_amt='$SRate', work_status='$WorkStatusVal', active=1";
					//$SelectWorksQuerySql = mysqli_query($dbConn,$InsUptQuery);
					//$GlobID = mysqli_insert_id($dbConn);
					array_push($InsertedCCNOArr,$CCode);
				}
				//echo $InsUptQuery;exit;
				$SelectSheetQuery = "SELECT * FROM sheet where globid='$GlobID'";
				$SelectSheetQuerySql = mysqli_query($dbConn,$SelectSheetQuery);
				if($SelectSheetQuerySql == true){
					if(mysqli_num_rows($SelectSheetQuerySql) > 0){
						$SheetList 	= mysqli_fetch_object($SelectSheetQuerySql);
						$SheetID 	= $SheetList->sheet_id;
					}
				}
				if($SheetID != null){
					$InsUptSheetQuery = "UPDATE works SET sheetid='$SheetID' WHERE globid = '$GlobID'";
					$InsUptSheetQuerySql = mysqli_query($dbConn,$InsUptSheetQuery);
				}else{
					$InsUptQuery = "INSERT INTO sheet SET work_name='$NameWork', tech_sanction='$TechSanNumber', name_contractor='$ContName',
					work_order_no='$WoNumber', work_order_cost='$WoValue', work_order_date='$WoDate', computer_code_no='', rbn='$BillNumber', gst_perc_rate='$GstRate',
					agree_no='$AggreNumber', agree_date='$AggreDate', hoa='$HoaCode', active=1";
					$SelectWorksQuerySql = mysqli_query($dbConn,$InsUptQuery);
					$SheetID = mysqli_insert_id($dbConn);
					$InsUptAbsBookQuery = "INSERT INTO abstractbook SET sheetid='$SheetID', rbn='$BillNumber', upto_date_total_amount='$UpdateVal'";
					$InsUptAbsBookQuerySql = mysqli_query($dbConn,$InsUptAbsBookQuery);
				}	
				*/
			}
		}

	}
}

if(isset($_POST["confirm"]) == " CONFIRM "){

	//print_r($ContMasterArr);exit;
	$TrId 			   = 1;
	$HidWorkType	   = $_POST['txt_hid_worktype'];
	$CCNoArr 		   = $_POST['txt_ccno'];
	$BillNoArr 	      = $_POST['txt_billno'];
	$ContNameArr 	   = $_POST['txt_contname'];
	$WorkNameArr 	   = $_POST['txt_wrkname'];
	$WrkOrdNumberArr 	= $_POST['txt_wo_no'];
	$WrkOrdDateArr   	= $_POST['txt_wo_date'];
	$AggrNumArr 		= $_POST['txt_agmt_no'];
	$AggrDateArr 		= $_POST['txt_agmt_date'];
	$WrkOrdValArr 		= $_POST['txt_wo_val'];
	$TsNumArr 	      = $_POST['txt_ts_no'];
	$HoaNumArr 	   	= $_POST['txt_hoa_no'];
	$HoaCodeArr 	   = $_POST['txt_hoa_code'];
	$GstRateArr 	   = $_POST['txt_gst_rate'];
	$ValidDateArr 	   = $_POST['txt_valid_date'];	 
	$ItRateArr 	   	= $_POST['txt_it_rate'];

	$ItrArr	 	   	= $_POST['txt_itr'];	 
	
	$IGstArr 	   	= $_POST['txt_igst'];	 
	$ClcRateArr 	   = $_POST['txt_clcrate'];	 

	$GSTRateArr 	   = $_POST['txt_gstrate'];	 

	$PbgPercArr 	   = $_POST['txt_pbg_val'];	 
	$PGValidDateArr	= $_POST['txt_pbg_valid_date'];

	
	$SdBgArr				= $_POST['txt_sdbg'];	 
	$SdBgValidDateArr	= $_POST['txt_sdbg_valid_date'];
	$ItCodeArr 	   	= $_POST['txt_it_code'];	 
	$NCostArr 	   	= $_POST['txt_ncost'];	 
	$ConmnArr 	   	= $_POST['txt_conmn'];	 
	$TotSdArr 	    	= $_POST['txt_tot_sd'];
	$UptoSdArr   		= $_POST['txt_upto_sd'];
	$BalaSDArr   		= $_POST['txt_bal_ann_sd'];
	$UpdateValArr   	= $_POST['txt_update_val'];
	$LastPayDateArr	= $_POST['txt_last_pay_date'];
	$EngineerNameArr	= $_POST['txt_engi_name'];

	$CodeArr				= $_POST['txt_code'];

	$PanNoArr			= $_POST['txt_pan_no'];

	$ComDateArr			= $_POST['txt_comdate'];
	$DlpDateArr			= $_POST['txt_dlpdt'];
	$PGDlpArr			= $_POST['txt_pgdlp'];

	$GstNoArr			= $_POST['txt_gst_no'];

	$Execute = 0;

	//echo $TotAmountAfReb;exit;
	

	foreach($CCNoArr as $ArrKey => $ArrValue){
		$CCNumber 	 	= $CCNoArr[$ArrKey];
		$BillNumber  	= $BillNoArr[$ArrKey];
		$ContName    	= $ContNameArr[$ArrKey];
		$WorkName  	 	= $WorkNameArr[$ArrKey];
		$WrkOrdNumber 	= $WrkOrdNumberArr[$ArrKey];
		if(($WrkOrdDateArr[$ArrKey] == null)||($WrkOrdDateArr[$ArrKey] == "")){
			$WrkOrdDate  	= "";
		}else{
			$WrkOrdDate  	= dt_format($WrkOrdDateArr[$ArrKey]);
		}
		
		$AggreNumber 	= $AggrNumArr[$ArrKey];
		if(($AggrDateArr[$ArrKey] == null)||($AggrDateArr[$ArrKey] == "")){
			$AggreDate  	= "";
		}else{
			$AggreDate 	 	= dt_format($AggrDateArr[$ArrKey]);
		}
		$WrkOrdVal 	 	= $WrkOrdValArr[$ArrKey];
		$TSNumber 	 	= $TsNumArr[$ArrKey];
		$HoaNumber   	= $HoaNumArr[$ArrKey];
		$HoaCode 	 	= $HoaCodeArr[$ArrKey];
		$GSTRate 	 	= $GstRateArr[$ArrKey];
		
		if(($ValidDateArr[$ArrKey] == null)||($ValidDateArr[$ArrKey] == "")){
			$ValidDate  	= "";
		}else{
			$ValidDate 	 	= dt_format($ValidDateArr[$ArrKey]);
		}
		$ItRate 	 	 	= $ItRateArr[$ArrKey];
		$ItR  	 	 	= $ItrArr[$ArrKey];
		
		$IGst 	 	 	= $IGstArr[$ArrKey];
		$ClcRate 	 	= $ClcRateArr[$ArrKey];
 
		$GstRate 	 	= $GSTRateArr[$ArrKey];
		
		$PbgPerc	 	= $PbgPercArr[$ArrKey];

		$Total_SD    	= $TotSdArr[$ArrKey];
		$Sdbg    		= $SdBgArr[$ArrKey];
		if(($SdBgValidDateArr[$ArrKey] == null)||($SdBgValidDateArr[$ArrKey] == "")){
			$SdBgValidDate = "";
		}else{
			$SdBgValidDate = dt_format($SdBgValidDateArr[$ArrKey]);
		}
		$ItCode 		= $ItCodeArr[$ArrKey];
		$NCost		 	= $NCostArr[$ArrKey];
		$Conmn		 	= $ConmnArr[$ArrKey];
		$UptoDate_SD	= $UptoSdArr[$ArrKey];
		$BalaSD 		= $BalaSDArr[$ArrKey];
		$UpdateVal 		= $UpdateValArr[$ArrKey];
		if(($LastPayDateArr[$ArrKey] == null)||($LastPayDateArr[$ArrKey] == "")){
			$LastPayDate = "";
		}else{
			$LastPayDate = dt_format($LastPayDateArr[$ArrKey]);
		}
		$EngineerName 	= $EngineerNameArr[$ArrKey];
		$Code 			= $CodeArr[$ArrKey];

		$PanNo 			= $PanNoArr[$ArrKey];
		$ComDate 		= $ComDateArr[$ArrKey];
		$DlpDate 		= $DlpDateArr[$ArrKey];
		$PGDlp 			= $PGDlpArr[$ArrKey];

		$GstNo 			= $GstNoArr[$ArrKey];

		if(($ComDate == null)||($ComDate == "")){
			$ComDateFormated 	= "";
		}else{
			$ComDateFormated 	= dt_format($ComDate);
		}
		if(($DlpDate == null)||($DlpDate == "")){
			$DlpDateFormated 	= "";
		}else{
			$DlpDateFormated 	= dt_format($DlpDate);
		}
		if(($PGDlp == null)||($PGDlp == "")){
			$PGDlpFormated = "";
		}else{
			$PGDlpFormated	= dt_format($PGDlp);
		}

		if(($PGValidDateArr[$ArrKey] == null)||($PGValidDateArr[$ArrKey] == "")){
			$PGValidDate 	= "";
		}else{
			$PGValidDate  	= dt_format($PGValidDateArr[$ArrKey]);
		}

		$InsertQuery1 = "INSERT INTO work_master_temp SET ccode = '$CCNumber',billno = '$BillNumber', cname = '$ContName', work = '$WorkName', 
		wono = '$WrkOrdNumber', wodate = '$WrkOrdDate',agtno ='$AggreNumber', agtdate = '$AggreDate', woval = '$WrkOrdVal', 
		tsno = '$TSNumber', hoafc = '$HoaNumber', hoa_code = '$HoaCode', gst_rate ='$GSTRate', valid = '$ValidDate', 
		itrate = '$ItRate', itr = '$ItR', igst = '$IGst', clcrate = '$ClcRate', 
		gstrate ='$GstRate', pbg = '$PbgPerc', 
		pg_valid_date = '$PGValidDate', sdbg = '$Sdbg', sdbg_valid = '$SdBgValidDate', 
		it_code = '$ItCode', ncost = '$NCost', conmn = '$Conmn', total_sd ='$Total_SD', upto_sd = '$UptoDate_SD', bala_sd = '$BalaSD', update_val = '$UpdateVal', 
		engineer_name = '$EngineerName', code = '$Code', 		
		pan_no = '$PanNo', comdt ='$ComDateFormated', dlpdt = '$DlpDateFormated', pgdlp = '$PGDlpFormated', 
		gst_no = '$GstNo', work_status = '$HidWorkType', active = '1'";

		//echo $InsertQuery1;exit;
		$InsertSql1 	= mysqli_query($dbConn,$InsertQuery1);
		//$BidderMastId	= mysqli_insert_id($dbConn);

		if($InsertSql1 == true){
			$Execute++;
		}
		//echo $Execute;
	}

	if($Execute > 0){
		$msg = "Work Details Saved Successfully";
		$success = 1;
	}else{
		$msg = "Error : Work Details Not Saved.. Please Try Again.";
		$success = 0;
	}
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php require_once "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
	function goBack()
	{
	   	url = "WorkMasterDetailsGenerate.php";
		window.location.replace(url);
	}
</script>
<style>
	.DispTable{
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:11px;
	}
	.DispTable th, .DispTable td{
		border:1px solid #BCBEBF;
		border-collapse:collapse;
		padding:2px 3px;
	}
	.DispTable th{
		background-color:#035a85;
		color:#fff;
		vertical-align:middle;
		text-align:center;
	}
	.DispTable td{
		color:#062C73;
	}
	.HideDesc{
		max-width : 768px; 
	  	white-space : nowrap;
	  	overflow : hidden;
	  	text-overflow: ellipsis;
	}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="WorkMasterDetailsUpload.php" method="post" enctype="multipart/form-data" name="form">
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
            <?php include "MainMenu.php"; ?>
            <div class="container_12">
                <div class="grid_12">
					<div align="right" class="users-icon-part">&nbsp;</div>
                    <blockquote class="bq1" style="overflow:auto">
						  		<div class="row">
									<div class="box-container box-container-lg" align="center">
										<div class="div12">
											<div class="card cabox">
												<div class="face-static">
													<div class="card-header inkblue-card" align="center">Work Master Details- Upload</div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<div class="table-responsive dt-responsive ResultTable">
																<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																	<?php if(isset($WorkType)){?>
																		<table class="DispTable" width="100%">
																			<thead>
																				<tr>
																					<input type="hidden" id="txt_hid_worktype" name="txt_hid_worktype" value="<?php echo $WorkType; ?>">
																					<th>CCODE</th>
																																										
																					<th>BILLNO</th>
																					<th>CNAME</th>
																					<th>WORK</th>
																					<th>WONO</th>
																					<th>WODATE</th>
																					
																					<th>AGTNO</th>
																					<th>AGTDATE</th>
																					<th>WOVAL</th>
																					<th>TSNO</th>
																					<th>HOFAC</th>
																					<th>HOA_CODE</th>
																					<th>GST_RATE</th>
																					<th>VALID</th>
																					<th>ITRATE</th>

																					<th>ITR</th>		<!--	Fields not available in 1st excel but available in 2nd Excel -->

																					<th>IGST</th>
																					<th>CLCRATE</th>

																					<th>GSTRATE</th>	<!--	Fields not available in 1st excel but available in 2nd Excel -->

																					<th>PBG</th>

																					<th>PBGVALID</th>

																					<th>SDBG</th>					<!-- Fields available in 1st excel and not available in 2nd Excel -->
																					<th>SDBGVALID</th>			<!-- Fields available in 1st excel and not available in 2nd Excel -->
																					
																					<th>ITCODE</th>	
																					<th>NCOST</th>
																					<th>CONMN</th>
																					<th>TOTALSD</th>
																					<th>UPTOSD</th>
																					<th>BALASD</th>
																					<th>UPDATEVAL</th>
																					<th>LASTPMT</th>
																					<th>ENGNR</th>
																					<th>CODE</th>

																					<th>PAN</th>			<!--	Fields not available in 1st excel but available in 2nd Excel -->
																					<th>COMDT</th>			<!--	Fields not available in 1st excel but available in 2nd Excel -->
																					<th>DLPDT</th>			<!--	Fields not available in 1st excel but available in 2nd Excel -->
																					<th>PGDLP</th>			<!--	Fields not available in 1st excel but available in 2nd Excel -->
																					<th>GSTNO</th>			<!--	Fields not available in 1st excel but available in 2nd Excel -->
																				</tr>
																			</thead>
																			<tbody>
																			<?php 
																			
																			$AllItemArr = array(); $TotalAmount = 0;
																			if($checkupload == 1) {	//echo $PriceBidLocation.$currentfilename;exit;
																				$Spreadsheet = new SpreadsheetReader($PriceBidLocation.$currentfilename);
																				$Sheets = $Spreadsheet -> Sheets();
																				foreach ($Sheets as $Index => $Name){ // Loop to get all sheets in a file.
																					$Spreadsheet -> ChangeSheet($Index);
																					$ExcelSheetName = $Name;
																					if($SheetName == $ExcelSheetName)
																					{
																						if(strtolower($imageFileType) == "xls"){
																							$StartRow = $StartRow - 1;
																						}
																						if(strtolower($imageFileType) == "xlsx"){
																							$StartRow = $StartRow - 1;
																						}
																						foreach ($Spreadsheet as $Key => $Row) { // loop used to get each row of the sheet
																							if(($Key >= $StartRow)&&($Key <= $EndRow)){
																								if(trim($Row[0]) != ''){

																									$CCODEval		= trim($Row[0]);
																									$BILLNOval 		= trim($Row[1]);
																									$CNAMEval		= trim($Row[2]);
																									$WORKval		= trim($Row[3]);
																									$WONOval	   	= trim($Row[4]);
																									$WODATEval		= trim($Row[5]);

																									$DateError		= 0;
																									if(strpos($WODATEval, "/") !== false){
																										$WoDateStr = $WODATEval;
																										//echo $WoDateStr;exit;
																									}else if(strlen($WODATEval) == 8){
																										$WoYear 	= substr($WODATEval, 0, 4);
																										$WoMonth   	= substr($WODATEval, 4, 2);
																										$WoDay 	  	= substr($WODATEval, 6, 2);
																										$WoDateStr	= $WoDay."/".$WoMonth."/".$WoYear;
																									}else{
																										$DateError 	= 1;
																										$WoDateStr 	= $WODATEval;
																									}


																									$AGTNOval		= trim($Row[6]);
																									$AGTDATEval		= trim($Row[7]);

																									$DateError1		= 0;
																									if(strpos($AGTDATEval, "/") !== false){
																										$AgDateStr = $AGTDATEval;
																										//echo $WoDateStr;exit;
																									}else if(strlen($AGTDATEval) == 8){
																										$WoYear 	  = substr($AGTDATEval, 0, 4);
																										$WoMonth   = substr($AGTDATEval, 4, 2);
																										$WoDay 	  = substr($AGTDATEval, 6, 2);
																										$AgDateStr = $WoDay."/".$WoMonth."/".$WoYear;
																									}else{
																										$DateError1 = 1;
																										$AgDateStr = $AGTDATEval;
																									}

																									$WOVALval		= trim($Row[8]);
																									$TSNOval	   	= trim($Row[9]);
																									$HOFACval		= trim($Row[10]);
																									$HOA_CODEval 	= trim($Row[11]);
																									$GST_RATEval 	= trim($Row[12]);

																									$DateError2		= 0;
																									if(strpos($OVDATEval, "/") !== false){
																										$OvDateStr = $OVDATEval;
																										//echo $WoDateStr;exit;
																									}else if(strlen($OVDATEval) == 8){
																										$WoYear 	  = substr($OVDATEval, 0, 4);
																										$WoMonth   = substr($OVDATEval, 4, 2);
																										$WoDay 	  = substr($OVDATEval, 6, 2);
																										$OvDateStr = $WoDay."/".$WoMonth."/".$WoYear;
																									}else{
																										$DateError2 = 1;
																										$OvDateStr = $OVDATEval;
																									}


																									$ITRATEval 		= trim($Row[13]);
																									
																									$ITRval 			= trim($Row[14]);

																									$CRATEval 		= trim($Row[19]);
																									$SRATEval 		= trim($Row[20]);
																									$TNGSTval 		= trim($Row[21]);


																									$IGSTval 		= trim($Row[15]);
																									$CLCRATEval		= trim($Row[16]);

																									$GSTRATEval		= trim($Row[17]);	///
																									$AmtWRATEval	= trim($Row[18]);	///

																									$PBGval 		= trim($Row[19]);
																									$PBGVALIDval 	= trim($Row[20]);

																									$DateError4		= 0;
																									if(strpos($PBGVALIDval, "/") !== false){
																										$PbgValidDateStr = $PBGVALIDval;
																										//echo $WoDateStr;exit;
																									}else if(strlen($PBGVALIDval) == 8){
																										$WoYear 	  = substr($PBGVALIDval, 0, 4);
																										$WoMonth   = substr($PBGVALIDval, 4, 2);
																										$WoDay 	  = substr($PBGVALIDval, 6, 2);
																										$PbgValidDateStr = $WoDay."/".$WoMonth."/".$WoYear;
																									}else{
																										$DateError4 = 1;
																										$PbgValidDateStr = $PBGVALIDval;
																									}

																									
																									$SDBGval	   		= trim($Row[21]);
																									$SDBGVALIDval 		= trim($Row[22]);

																									$DateError7		= 0;
																									if(strpos($SDBGVALIDval, "/") !== false){
																										$SDBGValidDateStr = $SDBGVALIDval;
																										//echo $WoDateStr;exit;
																									}else if(strlen($SDBGVALIDval) == 8){
																										$WoYear 	  = substr($SDBGVALIDval, 0, 4);
																										$WoMonth   = substr($SDBGVALIDval, 4, 2);
																										$WoDay 	  = substr($SDBGVALIDval, 6, 2);
																										$SDBGValidDateStr = $WoDay."/".$WoMonth."/".$WoYear;
																									}else{
																										$DateError7 = 1;
																										$SDBGValidDateStr = $SDBGVALIDval;
																									}

																									$ITCODEval 			= trim($Row[23]);
																									$NCOSTval 	 		= trim($Row[24]);
																									$CONMNval 			= trim($Row[25]);
																									$TOTALSDval 		= trim($Row[26]);
																									$UPTOSDval 			= trim($Row[27]);
																									$BALASDval	   	= trim($Row[28]);
																									$UPDATEVALval		= trim($Row[29]);
																									$LASTPMTval 		= trim($Row[30]);

																									$DateError8		= 0;
																									if(strpos($LASTPMTval, "/") !== false){
																										$LastPayDateStr = $LASTPMTval;
																										//echo $WoDateStr;exit;
																									}else if(strlen($LASTPMTval) == 8){
																										$WoYear 	  = substr($LASTPMTval, 0, 4);
																										$WoMonth   = substr($LASTPMTval, 4, 2);
																										$WoDay 	  = substr($LASTPMTval, 6, 2);
																										$LastPayDateStr = $WoDay."/".$WoMonth."/".$WoYear;
																									}else{
																										$DateError8 = 1;
																										$LastPayDateStr = $LASTPMTval;
																									}

																									$ENGNRval 			= trim($Row[31]);
																									

																									$CODEval 			= trim($Row[32]);

																									$PanNoval 			= trim($Row[33]);	///
																									$Comdtval 			= trim($Row[34]);	///
																									$Dlpdtval 			= trim($Row[35]);	///
																									$PGdlpval 			= trim($Row[36]);	///
																									
																									$GSTNoval 			= trim($Row[37]);	///

																									/*
																									$CCODEval		= trim($Row[0]);
																									$CCEXval		= trim($Row[1]);
																									$BILLNOval 		= trim($Row[2]);
																									$CNAMEval		= trim($Row[3]);
																									$WORKval		= trim($Row[4]);
																									$WONOval	   	= trim($Row[5]);
																									$WODATEval		= trim($Row[6]);

																									$DateError		= 0;
																									if(strpos($WODATEval, "/") !== false){
																										$WoDateStr = $WODATEval;
																										//echo $WoDateStr;exit;
																									}else if(strlen($WODATEval) == 8){
																										$WoYear 	= substr($WODATEval, 0, 4);
																										$WoMonth   	= substr($WODATEval, 4, 2);
																										$WoDay 	  	= substr($WODATEval, 6, 2);
																										$WoDateStr	= $WoDay."/".$WoMonth."/".$WoYear;
																									}else{
																										$DateError 	= 1;
																										$WoDateStr 	= $WODATEval;
																									}

																									$WOEXTDATEval		= trim($Row[7]);

																									$DateError10		= 0;
																									if(strpos($WOEXTDATEval, "/") !== false){
																										$WrkExtDateStr = $WOEXTDATEval;
																										//echo $WoDateStr;exit;
																									}else if(strlen($WOEXTDATEval) == 8){
																										$WoYear 	  = substr($WOEXTDATEval, 0, 4);
																										$WoMonth   = substr($WOEXTDATEval, 4, 2);
																										$WoDay 	  = substr($WOEXTDATEval, 6, 2);
																										$WrkExtDateStr = $WoDay."/".$WoMonth."/".$WoYear;
																									}else{
																										$DateError10 = 1;
																										$WrkExtDateStr = $WOEXTDATEval;
																									}

																									$AGTNOval		= trim($Row[8]);
																									$AGTDATEval		= trim($Row[9]);

																									$DateError1		= 0;
																									if(strpos($AGTDATEval, "/") !== false){
																										$AgDateStr = $AGTDATEval;
																										//echo $WoDateStr;exit;
																									}else if(strlen($AGTDATEval) == 8){
																										$WoYear 	  = substr($AGTDATEval, 0, 4);
																										$WoMonth   = substr($AGTDATEval, 4, 2);
																										$WoDay 	  = substr($AGTDATEval, 6, 2);
																										$AgDateStr = $WoDay."/".$WoMonth."/".$WoYear;
																									}else{
																										$DateError1 = 1;
																										$AgDateStr = $AGTDATEval;
																									}

																									$WOVALval		= trim($Row[10]);
																									$TSNOval	   	= trim($Row[11]);
																									$HOFACval		= trim($Row[12]);
																									$HOA_CODEval 	= trim($Row[13]);
																									$GST_RATEval 	= trim($Row[14]);
																									$OVDATEval		= trim($Row[15]);

																									$DateError2		= 0;
																									if(strpos($OVDATEval, "/") !== false){
																										$OvDateStr = $OVDATEval;
																										//echo $WoDateStr;exit;
																									}else if(strlen($OVDATEval) == 8){
																										$WoYear 	  = substr($OVDATEval, 0, 4);
																										$WoMonth   = substr($OVDATEval, 4, 2);
																										$WoDay 	  = substr($OVDATEval, 6, 2);
																										$OvDateStr = $WoDay."/".$WoMonth."/".$WoYear;
																									}else{
																										$DateError2 = 1;
																										$OvDateStr = $OVDATEval;
																									}

																									$VALIDval 		= trim($Row[16]);

																									$DateError3		= 0;
																									if(strpos($VALIDval, "/") !== false){
																										$ValidDateStr = $VALIDval;
																										//echo $WoDateStr;exit;
																									}else if(strlen($VALIDval) == 8){
																										$WoYear 	  = substr($VALIDval, 0, 4);
																										$WoMonth   = substr($VALIDval, 4, 2);
																										$WoDay 	  = substr($VALIDval, 6, 2);
																										$ValidDateStr = $WoDay."/".$WoMonth."/".$WoYear;
																									}else{
																										$DateError3 = 1;
																										$ValidDateStr = $VALIDval;
																									}


																									$ITRATEval 		= trim($Row[17]);
																									
																									$ITRval 			= trim($Row[18]);

																									$CRATEval 		= trim($Row[19]);
																									$SRATEval 		= trim($Row[20]);
																									$TNGSTval 		= trim($Row[21]);
																									$IGSTval 		= trim($Row[22]);
																									$CLCRATEval		= trim($Row[23]);

																									$WCTRATEval		= trim($Row[24]);	///
																									$GSTRATEval		= trim($Row[25]);	///
																									$AmtWRATEval	= trim($Row[26]);	///

																									$PBGval 			= trim($Row[27]);
																									$Valueval		= trim($Row[28]);
																									$PBGVALIDval 	= trim($Row[29]);

																									$DateError4		= 0;
																									if(strpos($PBGVALIDval, "/") !== false){
																										$PbgValidDateStr = $PBGVALIDval;
																										//echo $WoDateStr;exit;
																									}else if(strlen($PBGVALIDval) == 8){
																										$WoYear 	  = substr($PBGVALIDval, 0, 4);
																										$WoMonth   = substr($PBGVALIDval, 4, 2);
																										$WoDay 	  = substr($PBGVALIDval, 6, 2);
																										$PbgValidDateStr = $WoDay."/".$WoMonth."/".$WoYear;
																									}else{
																										$DateError4 = 1;
																										$PbgValidDateStr = $PBGVALIDval;
																									}

																									$PGTypeval	 	= trim($Row[30]);
																									$BGFDRSerNoval	= trim($Row[31]);
																									$BankNameval	= trim($Row[32]);
																									$BGFDRDateval	= trim($Row[33]);

																									$DateError5		= 0;
																									if(strpos($BGFDRDateval, "/") !== false){
																										$BGFDRDateStr = $BGFDRDateval;
																										//echo $WoDateStr;exit;
																									}else if(strlen($BGFDRDateval) == 8){
																										$WoYear 	  = substr($BGFDRDateval, 0, 4);
																										$WoMonth   = substr($BGFDRDateval, 4, 2);
																										$WoDay 	  = substr($BGFDRDateval, 6, 2);
																										$BGFDRDateStr = $WoDay."/".$WoMonth."/".$WoYear;
																									}else{
																										$DateError5 = 1;
																										$BGFDRDateStr = $BGFDRDateval;
																									}

																									$IsPBGReleaseval 	= trim($Row[34]);
																									$ReleaseDateval  	= trim($Row[35]);

																									$DateError6		= 0;
																									if(strpos($ReleaseDateval, "/") !== false){
																										$RelDateStr = $ReleaseDateval;
																										//echo $WoDateStr;exit;
																									}else if(strlen($ReleaseDateval) == 8){
																										$WoYear 	  = substr($ReleaseDateval, 0, 4);
																										$WoMonth   = substr($ReleaseDateval, 4, 2);
																										$WoDay 	  = substr($ReleaseDateval, 6, 2);
																										$RelDateStr = $WoDay."/".$WoMonth."/".$WoYear;
																									}else{
																										$DateError6 = 1;
																										$RelDateStr = $ReleaseDateval;
																									}

																									$IsGSTappval		= trim($Row[36]);
																									$IsLCESSAppval		= trim($Row[37]);
																									$GSTIncExcval		= trim($Row[38]);
																									$SDBGval	   		= trim($Row[39]);
																									$SDBGVALIDval 		= trim($Row[40]);

																									$DateError7		= 0;
																									if(strpos($SDBGVALIDval, "/") !== false){
																										$SDBGValidDateStr = $SDBGVALIDval;
																										//echo $WoDateStr;exit;
																									}else if(strlen($SDBGVALIDval) == 8){
																										$WoYear 	  = substr($SDBGVALIDval, 0, 4);
																										$WoMonth   = substr($SDBGVALIDval, 4, 2);
																										$WoDay 	  = substr($SDBGVALIDval, 6, 2);
																										$SDBGValidDateStr = $WoDay."/".$WoMonth."/".$WoYear;
																									}else{
																										$DateError7 = 1;
																										$SDBGValidDateStr = $SDBGVALIDval;
																									}

																									$ITCODEval 			= trim($Row[41]);
																									$NCOSTval 	 		= trim($Row[42]);
																									$CONMNval 			= trim($Row[43]);
																									$TOTALSDval 		= trim($Row[44]);
																									$UPTOSDval 			= trim($Row[45]);
																									$BALASDval	   	= trim($Row[46]);
																									$UPDATEVALval		= trim($Row[47]);
																									$LASTPMTval 		= trim($Row[48]);

																									$DateError8		= 0;
																									if(strpos($LASTPMTval, "/") !== false){
																										$LastPayDateStr = $LASTPMTval;
																										//echo $WoDateStr;exit;
																									}else if(strlen($LASTPMTval) == 8){
																										$WoYear 	  = substr($LASTPMTval, 0, 4);
																										$WoMonth   = substr($LASTPMTval, 4, 2);
																										$WoDay 	  = substr($LASTPMTval, 6, 2);
																										$LastPayDateStr = $WoDay."/".$WoMonth."/".$WoYear;
																									}else{
																										$DateError8 = 1;
																										$LastPayDateStr = $LASTPMTval;
																									}

																									$ENGNRval 			= trim($Row[49]);
																									$DTPMTval 	 		= trim($Row[50]);

																									$DateError9		= 0;
																									if(strpos($DTPMTval, "/") !== false){
																										$DateofPayStr = $DTPMTval;
																										//echo $WoDateStr;exit;
																									}else if(strlen($DTPMTval) == 8){
																										$WoYear 	  = substr($DTPMTval, 0, 4);
																										$WoMonth   = substr($DTPMTval, 4, 2);
																										$WoDay 	  = substr($DTPMTval, 6, 2);
																										$DateofPayStr = $WoDay."/".$WoMonth."/".$WoYear;
																									}else{
																										$DateError9 = 1;
																										$DateofPayStr = $DTPMTval;
																									}

																									$CODEval 			= trim($Row[51]);

																									$PanNoval 			= trim($Row[52]);	///
																									$TinNoval 			= trim($Row[53]);	///
																									$Comdtval 			= trim($Row[54]);	///
																									$Dlpdtval 			= trim($Row[55]);	///
																									$PGdlpval 			= trim($Row[56]);	///
																									$SDTval	 			= trim($Row[57]);	///
																									$Retentionval 		= trim($Row[58]);	///
																									$CompCCval 			= trim($Row[59]);	///
																									$GSTNoval 			= trim($Row[60]);	///
																									$Sectionval			= trim($Row[61]);	///

																									$WORKSTATUSval 	= trim($Row[62]);
																									*/
																								?>
																								<tr>
																									<td align="left"><?php echo $CCODEval; ?><input type="hidden" name="txt_ccno[]" value="<?php echo $CCODEval; ?>"></td>
																									<td align="left"><?php echo $BILLNOval; ?><input type="hidden" name="txt_billno[]" value="<?php echo $BILLNOval; ?>"></td>
																									<td align="left"><?php echo $CNAMEval; ?><input type="hidden" name="txt_contname[]" value="<?php echo $CNAMEval; ?>"></td>
																									<td align="left"><?php echo $WORKval; ?><input type="hidden" name="txt_wrkname[]" value="<?php echo $WORKval; ?>"></td>
																									<td align="left"><?php echo $WONOval; ?><input type="hidden" name="txt_wo_no[]" value="<?php echo $WONOval; ?>"></td>
																									<td align="left"><?php echo $WoDateStr; ?><input type="hidden" name="txt_wo_date[]" value="<?php echo $WoDateStr; ?>"></td>
																									<td align="right"><?php echo $AGTNOval; ?><input type="hidden" name="txt_agmt_no[]" value="<?php echo $AGTNOval; ?>"></td>
																									<td align="right"><?php echo $AgDateStr; ?><input type="hidden" name="txt_agmt_date[]" value="<?php echo $AgDateStr; ?>"></td>
																									<td align="right"><?php echo $WOVALval; ?><input type="hidden" name="txt_wo_val[]" value="<?php echo $WOVALval; ?>"></td>
																									<td align="right"><?php echo $TSNOval; ?><input type="hidden" name="txt_ts_no[]" value="<?php echo $TSNOval; ?>"></td>
																									<td align="justify"><?php echo $HOFACval; ?><input type="hidden" name="txt_hoa_no[]" value="<?php echo $HOFACval; ?>"></td>
																									<td align="justify"><?php echo $HOA_CODEval; ?><input type="hidden" name="txt_hoa_code[]" value="<?php echo $HOA_CODEval; ?>"></td>
																									<td align="justify"><?php echo $GST_RATEval; ?><input type="hidden" name="txt_gst_rate[]" value="<?php echo $GST_RATEval; ?>"></td>
																									<td align="justify"><?php echo $ValidDateStr; ?><input type="hidden" name="txt_valid_date[]" value="<?php echo $ValidDateStr; ?>"></td>
																									<td align="justify"><?php echo $ITRATEval; ?><input type="hidden" name="txt_it_rate[]" value="<?php echo $ITRATEval; ?>"></td>
																									
																									<td align="justify"><?php echo $ITRval; ?><input type="hidden" name="txt_itr[]" value="<?php echo $ITRval; ?>"></td>
																									
																									<td align="justify"><?php echo $IGSTval; ?><input type="hidden" name="txt_igst[]" value="<?php echo $IGSTval; ?>"></td>
																									<td align="justify"><?php echo $CLCRATEval; ?><input type="hidden" name="txt_clcrate[]" value="<?php echo $CLCRATEval; ?>"></td>
																									
																									<td align="justify"><?php echo $GSTRATEval; ?><input type="hidden" name="txt_gstrate[]" value="<?php echo $GSTRATEval; ?>"></td>
																									
																									<td align="justify"><?php echo $PBGval; ?><input type="hidden" name="txt_pbg_val[]" value="<?php echo $PBGval; ?>"></td>
																									<td align="justify"><?php echo $PbgValidDateStr; ?><input type="hidden" name="txt_pbg_valid_date[]" value="<?php echo $PbgValidDateStr; ?>"></td>
																									<td align="justify"><?php echo $SDBGval; ?><input type="hidden" name="txt_sdbg[]" value="<?php echo $SDBGval; ?>"></td>
																									<td align="justify"><?php echo $SDBGValidDateStr; ?><input type="hidden" name="txt_sdbg_valid_date[]" value="<?php echo $SDBGValidDateStr; ?>"></td>
																									<td align="justify"><?php echo $ITCODEval; ?><input type="hidden" name="txt_it_code[]" value="<?php echo $ITCODEval; ?>"></td>
																									<td align="justify"><?php echo $NCOSTval; ?><input type="hidden" name="txt_ncost[]" value="<?php echo $NCOSTval; ?>"></td>
																									<td align="justify"><?php echo $CONMNval; ?><input type="hidden" name="txt_conmn[]" value="<?php echo $CONMNval; ?>"></td>
																									<td align="justify"><?php echo $TOTALSDval; ?><input type="hidden" name="txt_tot_sd[]" value="<?php echo $TOTALSDval; ?>"></td>
																									<td align="justify"><?php echo $UPTOSDval; ?><input type="hidden" name="txt_upto_sd[]" value="<?php echo $UPTOSDval; ?>"></td>
																									<td align="justify"><?php echo $BALASDval; ?><input type="hidden" name="txt_bal_ann_sd[]" value="<?php echo $BALASDval; ?>"></td>
																									<td align="justify"><?php echo $UPDATEVALval; ?><input type="hidden" name="txt_update_val[]" value="<?php echo $UPDATEVALval; ?>"></td>
																									<td align="justify"><?php echo $LastPayDateStr; ?><input type="hidden" name="txt_last_pay_date[]" value="<?php echo $LastPayDateStr; ?>"></td>
																									<td align="justify"><?php echo $ENGNRval; ?><input type="hidden" name="txt_engi_name[]" value="<?php echo $ENGNRval; ?>"></td>
																									<td align="justify"><?php echo $CODEval; ?><input type="hidden" name="txt_code[]" value="<?php echo $CODEval; ?>"></td>
	
																									<td align="justify"><?php echo $PanNoval; ?><input type="hidden" name="txt_pan_no[]" value="<?php echo $PanNoval; ?>"></td>
																									<td align="justify"><?php echo $Comdtval; ?><input type="hidden" name="txt_comdate[]" value="<?php echo $Comdtval; ?>"></td>
																									<td align="justify"><?php echo $Dlpdtval; ?><input type="hidden" name="txt_dlpdt[]" value="<?php echo $Dlpdtval; ?>"></td>
																									<td align="justify"><?php echo $PGdlpval; ?><input type="hidden" name="txt_pgdlp[]" value="<?php echo $PGdlpval; ?>"></td>
																									<td align="justify"><?php echo $GSTNoval; ?><input type="hidden" name="txt_gst_no[]" value="<?php echo $GSTNoval; ?>"></td>
																								</tr>
																								<?php
																									if(($ItemAmtFile != 0)&&($ItemAmtFile != NULL)&&($ItemAmtFile != '')){
																										$TotalAmount = $TotalAmount + $ItemAmtFile;
																									}
																									if($ItemNo != ''){
																										array_push($AllItemArr,$ItemNo);
																									}
																								}
																							}
																						} 
																					}
																				}
																			?>
																			<?php } ?>
																			</tbody>
																		</table>
																	<?php } ?>

																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="div12" align="center">
									<input type="hidden" name="txt_TrId" id="txt_TrId" value="<?php echo $TrId; ?>">
									<input type="hidden" name="txt_bidderid" id="txt_bidderid" value="<?php echo $BidderId; ?>">
									<input type="button" class="btn btn-info" name="back" id="back" value=" BACK " onClick="goBack();"/>
									<input type="submit" class="btn btn-info" name="confirm" id="confirm" value=" CONFIRM "/>
								</div>
							</div>  
							<div class="row">&nbsp;</div>                         
						</blockquote>
					</div>

            </div>
        </div>
	</form>
         <!--==============================footer=================================-->
	<?php include "footer/footer.html"; ?>
	<script>
		var msg 	= "<?php echo $msg; ?>";
		var success = "<?php echo $success; ?>";
		if(msg != ""){
			BootstrapDialog.show({
				message: msg,
				buttons: [{
					label: ' OK ',
					action: function(dialog) {
						dialog.close();
						window.location.replace('WorkMasterDetailsGenerate.php');
					}
				}]
			});
		}

		var KillEvent = 0;
	
		
	</script>
    </body>
</html>

