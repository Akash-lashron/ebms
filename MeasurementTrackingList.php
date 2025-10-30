<?php
require_once 'library/config.php';
require_once 'library/functions.php';
checkUser();
require_once 'ExcelReader/excel_reader2.php';
include "library/common.php";
include "sysdate.php";
$msg = '';
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy.'-'.$mm.'-'.$dd;
}
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
if($_SESSION['isadmin'] == 1){
	$staffid_clause = "";
}else{
	$staffid_clause = " AND c.staffid = ".$staffid;
}
$FDate 		= ""; $ToDate = "";  $DTArr = array();
if(isset($_POST['btn_next']) == " NEXT "){
	$SearchType	 = $_POST['searchtype'];
	$WhereClause = "";
	if($SearchType == "DW"){
	    $MeasurementType  	= $_POST['measure_type'];
		$FDate 		        = dt_format($_POST['txt_from_date']);
		$ToDate 	        = dt_format($_POST['txt_to_date']);
		$Description 		= $_POST['txt_description'];
		$NameOfWOrk         = $_POST['cmb_shortname']; 
		$ItemNo             = $_POST['cmb_item_no'];
		$ZoneId             = $_POST['cmb_zone_no'];
			if($MeasurementType =="S"){
			   $MeasurementTypewhere ="$MeasurementType";
			}else{
			   $MeasurementTypewhere ='';
			}
			if(($FDate !== "")&&($TDate !== ""))
			{
			   $FromDate = dt_format($_POST['txt_from_date']);
			   $ToDate   = dt_format($_POST['txt_to_date']);
			}
			if($MeasurementType != ''){ $WhereClause .= " and b.measure_type = '$MeasurementTypewhere'"; }
			if($FDate != ''){ $WhereClause .= " and c.date >= '$FDate'"; }
			if($ToDate != ''){ $WhereClause .= " and c.date <= '$ToDate' "; }
			if($NameOfWOrk != ''){ $WhereClause .= " and b.sheet_id = '$NameOfWOrk'"; }
			if($ItemNo != ''){ $WhereClause .= " and b.sch_id = '$ItemNo'"; }
			if($ZoneId != ''){ $WhereClause .= " and d.zone_id = '$ZoneId'"; }
			if($Description != ''){ $WhereClause .= " and d.descwork LIKE '%$Description%'"; }
	}else if($SearchType == "IW"){
	    $MeasurementType 	= $_POST['measure_type'];
		$FDate 		        = $_POST['txt_from_date'];
		$ToDate 	        = $_POST['txt_to_date'];
		$Description 		= $_POST['txt_description'];
		$NameOfWOrk         = $_POST['cmb_shortname']; 
		$ItemNo             = $_POST['cmb_item_no'];
		$ZoneId             = $_POST['cmb_zone_no'];
			if($MeasurementType =="S"){
			   $MeasurementTypewhere ="$MeasurementType";
			}else{
			   $MeasurementTypewhere ='';
			}
			if(($FDate !== "") &&($TDate !== ""))
			{
			   $FromDate = dt_format($_POST['txt_from_date']);
			   $ToDate   = dt_format($_POST['txt_to_date']);
			} 
			if($MeasurementType != ''){ $WhereClause .= " and b.measure_type = '$MeasurementTypewhere'"; }
			if($FDate != ''){ $WhereClause .= " and c.date >= '$FDate'"; }
			if($ToDate != ''){ $WhereClause .= " and c.date <= '$ToDate' "; }
			if($NameOfWOrk != ''){ $WhereClause .= " and b.sheet_id = '$NameOfWOrk'"; }
			if($ItemNo != ''){ $WhereClause .= " and b.sch_id = '$ItemNo'"; }
			if($ZoneId != ''){ $WhereClause .= " and d.zone_id = '$ZoneId'"; }
			if($Description != ''){ $WhereClause .= " and d.descwork LIKE '%$Description%'"; }
	}else if($SearchType == "ZW"){
	    $MeasurementType 	= $_POST['measure_type'];
		$FDate 		        = $_POST['txt_from_date'];
		$ToDate 	        = $_POST['txt_to_date'];
		$Description 		= $_POST['txt_description'];
		$NameOfWOrk         = $_POST['cmb_shortname']; 
		$ItemNo             = $_POST['cmb_item_no'];
		$ZoneId             = $_POST['cmb_zone_no'];
			if($MeasurementType =="S"){
			   $MeasurementTypewhere ="$MeasurementType";
			}else{
			   $MeasurementTypewhere ='';
			}
			if(($FDate !== "") &&($TDate !== ""))
			{
			   $FromDate = dt_format($_POST['txt_from_date']);
			   $ToDate   = dt_format($_POST['txt_to_date']);
			} 
			if($MeasurementType != ''){ $WhereClause .= " and b.measure_type = '$MeasurementTypewhere'"; }
			if($FDate != ''){ $WhereClause .= " and c.date >= '$FDate'"; }
			if($ToDate != ''){ $WhereClause .= " and c.date <= '$ToDate' "; }
			if($NameOfWOrk != ''){ $WhereClause .= " and b.sheet_id = '$NameOfWOrk'"; }
			if($ItemNo != ''){ $WhereClause .= " and b.sch_id = '$ItemNo'"; }
			if($ZoneId != ''){ $WhereClause .= " and d.zone_id = '$ZoneId'"; }
			if($Description != ''){ $WhereClause .= " and d.descwork LIKE '%$Description%'"; }
					
    }else if($SearchType == "DSW"){
	    $MeasurementType 	= $_POST['measure_type'];
		$FDate 		        = $_POST['txt_from_date'];
		$TDate 	            = $_POST['txt_to_date'];
		
		$NameOfWOrk         = $_POST['cmb_shortname']; 
		$ItemNo             = $_POST['cmb_item_no'];
		$ZoneId             = $_POST['cmb_zone_no'];
		$Description 		= $_POST['txt_description'];
			if($MeasurementType =="S"){
			   $MeasurementTypewhere ="$MeasurementType";
			}else{
			   $MeasurementTypewhere ='';
			}
			if(($FDate !== "") &&($TDate !== ""))
			{
			   $FromDate = dt_format($_POST['txt_from_date']);
			   $ToDate   = dt_format($_POST['txt_to_date']);
			}
			if($MeasurementType != ''){ $WhereClause .= " and b.measure_type = '$MeasurementTypewhere'"; }
			if($FDate != ''){ $WhereClause .= " and c.date >= '$FromDate'"; }
			if($ToDate != ''){ $WhereClause .= " and c.date <= '$ToDate' "; }
			if($NameOfWOrk != ''){ $WhereClause .= " and b.sheet_id = '$NameOfWOrk'"; }
			if($ItemNo != ''){ $WhereClause .= " and b.sch_id = '$ItemNo'"; }
			if($ZoneId != ''){ $WhereClause .= " and d.zone_id = '$ZoneId'"; }
			if($Description != ''){ $WhereClause .= " and d.descwork LIKE '%$Description%'"; }
			
	}
	$_SESSION['sheetid'] 		    = $NameOfWOrk;
	$_SESSION['E_WhereClause'] 		= $WhereClause;
	$_SESSION['E_PassSearchType'] 	= $SearchType;
	$_SESSION['MeasurementTypewhere']	= $MeasurementType;
}
$sheetid 	        = $_SESSION['sheetid'];
$WhereClause 	    = $_SESSION['E_WhereClause'];
$SearchType         = $_SESSION['E_PassSearchType'];
$Measurement_Type   = $_SESSION['MeasurementTypewhere'];
if($SearchType != ""){
	$select_measurement_query 	= "SELECT a.*,b.*,c.*,d.*,e.* FROM subdivision a, schdule b, mbookheader c, mbookdetail d , zone e WHERE 
								   d.mbdetail_flag != 'd' AND c.mbheaderid = d.mbheaderid AND a.subdiv_id = c.subdivid AND d.zone_id = e.zone_id AND
								   b.subdiv_id = c.subdivid ".$WhereClause." 
								   ORDER BY c.date, c.mbheaderid, d.mbdetail_id ASC ";
								   //echo $select_measurement_query;exit;
	$select_measurement_sql = mysql_query($select_measurement_query);
	if($select_measurement_sql == true){
		if(mysql_num_rows($select_measurement_sql)>0){
			$PassCount = 1;
		}
	}
	$select_rbn_query 	= "SELECT fromdate, todate, rbn from abstractbook where sheetid = '$sheetid' ";
	$select_rbn_sql = mysql_query($select_rbn_query);
	if($select_rbn_sql == true){
		if(mysql_num_rows($select_rbn_sql)>0){
			while($FTList = mysql_fetch_object($select_rbn_sql)){
				$Fromdate = $FTList->fromdate;
				$Todate   = $FTList->todate;
				$rbn      = $FTList->rbn;
				$DTArr[$rbn] = 	$Fromdate.",".	$Todate;	
			}	
		}
	}
}
?>
<?php require_once "Header.html"; ?>
<link type='text/css' href='css/basic-dashboard.css' rel='stylesheet' media='screen' />
<script type='text/javascript' src='js/basic_model_jquery.js'></script>
<script type='text/javascript' src='js/jquery.simplemodal.js'></script>
<script type="text/javascript" language="javascript">
function goBack()
{
	url = "MeasurementTrackingField.php";
	window.location.replace(url);
}
</script>
<style>
.container{
display:table;
width:100%;
border-collapse: collapse;
}
.table-row{  
display:table-row;
text-align: left;
}
.col{
display:table-cell;
border: 1px solid #CCC;
padding:1px;
background-color:#f5f5f5;
}
.chbox-style
{
    height: 12px;   
    width: 15px;
}
.container{
	width:90%;
}
.labelcontenthead{
	vertical-align:middle;
}
</style>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="form">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
              <div class="title">View Measurement List</div>
                <div class="container_12">
                    <div class="grid_12" align="center">
                        <blockquote class="bq1" style=" height:500px;overflow:scroll;">
                            <div class="container" align="center">
							 
							   <table width="100%" class="table1 table2" id="example">
									<thead>
									  
									   <?php if($Measurement_Type == "S"){?>
										<tr class="heading">
										<?php if($SearchType == "DW"){?>
											<th nowrap="nowrap" align="center" colspan="12" style=" line-height: 80%;">Date Wise measurement details</th>
										<?php }else if($SearchType == "IW"){?>
											<th nowrap="nowrap" align="center" colspan="12" style=" line-height: 80%;">Item no Wise measurement details</th>
										<?php }else if($SearchType == "ZW"){?>
											<th nowrap="nowrap" align="center" colspan="12" style=" line-height: 80%;">Zone  Wise measurement details</th>
										<?php }else if($SearchType == "DSW"){?>
											<th nowrap="nowrap" align="center" colspan="12" style=" line-height: 80%;">Description Wise measurement details</th>
										<?php }?>
										</tr> 
										<tr class="heading">
											<th nowrap="nowrap">&nbsp;Slno.&nbsp;</th>
											<th align="left" style="text-align:center">Date</th>
											<th align="center">Item No.</th>
											<th align="left" nowrap="nowrap">Zone Name</th>
											<th align="center">RAB</th>
											<th nowrap="nowrap" style=" width: 50%;">Description</th>
											<th nowrap="nowrap">Dia</th>
											<th nowrap="nowrap">No</th>
											<th nowrap="nowrap">No</th>
											<th nowrap="nowrap">Length</th>
											<th nowrap="nowrap" style=" line-height: 90%;">Contents of<br/>Area</th>
											<th nowrap="nowrap">Unit</th>
										</tr>
										<?php } else{ ?>
										<tr class="heading">
										<?php if($SearchType == "DW"){?>
											<th nowrap="nowrap" align="center" colspan="12" style=" line-height: 80%;">Date Wise measurement details</th>
										<?php }else if($SearchType == "IW"){?>
											<th nowrap="nowrap" align="center" colspan="12" style=" line-height: 80%;">Item No Wise measurement details</th>
										<?php }else if($SearchType == "ZW"){?>
											<th nowrap="nowrap" align="center" colspan="12" style=" line-height: 80%;">Zone Wise measurement details</th>
										<?php }else if($SearchType == "DSW"){?>
											<th nowrap="nowrap" align="center" colspan="12" style=" line-height: 80%;">Description Wise measurement details</th>
										<?php }?>
										</tr> 
										<tr>
											<th nowrap="nowrap">&nbsp;Slno.&nbsp;</th>
											<th align="left" style="text-align:center">Date</th>
											<th align="center">Item no.</th>
											<th align="left" nowrap="nowrap" style=" width: 30%;">Zone Name</th>
											<th align="center">RAB</th>
											<th nowrap="nowrap" style=" width: 45%;">Description</th>
											<th nowrap="nowrap">No</th>
											<th nowrap="nowrap">Length</th>
											<th nowrap="nowrap">Breadth</th>
											<th nowrap="nowrap">Depth</th>
											<th nowrap="nowrap" style=" line-height: 90%;">Contents of <br/> Area</th>
											<th nowrap="nowrap">Unit</th>
										</tr>
									<?php } ?> 
									</thead>
									<tbody>
									<?php $slno = 1; if($PassCount == 1){ while($List = mysql_fetch_object($select_measurement_sql)){ $decimal 	= $List->decimal_placed; ?>
									 <?php if($measurementtype == "S"){
									       $MD = $List->date;
										   $FromDate = 0; $ToDate = 0;
				                           foreach($DTArr as $key =>$val){ 
											   $rbn    = $key;
											   $DateStr = $val;
											   $ExpDateStr = explode(',',$DateStr);
											   $FromDate = $ExpDateStr[0];
											   $ToDate   = $ExpDateStr[1];
											   if(($MD >= $FromDate)&($MD <= $ToDate)){
												   $RAB = $rbn;
											   }
									       }
									    ?>
										<tr>
											<td align="center"><?php echo $slno; ?></td>
											<td><?php echo "&nbsp".dt_display($List->date)."&nbsp"; ?></td>
											<td align="center"><?php echo $List->subdiv_name; ?></td>
											<td align="left"><?php echo $List->zone_name; ?></td>
											<td align="center"><?php echo $RAB; ?></td>
											<td><?php echo $List->descwork; ?></td>
											<td align="right"><?php if($List->measurement_dia != 0) { echo $List->measurement_dia; } ?> </td>
											<td align="right"><?php if($List->measurement_no != 0) { echo $List->measurement_no; } ?> </td>
											<td align="right"><?php if($List->measurement_no2 != 0) { echo $List->measurement_no2; } ?> </td>
											<td align="right"><?php if($List->measurement_l != 0) { echo number_format($List->measurement_l,$decimal,".",","); } ?></td>
											<td align="right"><?php if($List->measurement_contentarea != 0) { echo number_format($List->measurement_contentarea,$decimal,".",","); } ?></td>
											<td align="right"><?php echo $List->remarks; ?></td>
										</tr>
										 <?php }else{ 
											   $MD = $List->date;
											   $FromDate = 0; $ToDate = 0;
											   foreach($DTArr as $key =>$val){ 
												   $rbn     = $key;
												   $DateStr = $val;
												   $ExpDateStr = explode(',',$DateStr);
												   $FromDate = $ExpDateStr[0];
												   $ToDate   = $ExpDateStr[1];
												   if(($MD >= $FromDate)&($MD <= $ToDate)){
													   $RAB = $rbn;
												   }
											   }
										 ?>
										<tr>
											<td align="center"><?php echo $slno; ?></td>
											<td><?php echo "&nbsp".dt_display($List->date)."&nbsp"; ?></td>
											<td align="center"><?php echo $List->subdiv_name; ?></td>
											<td align="left"><?php echo $List->zone_name; ?></td>
											<td align="center"><?php echo $RAB; ?></td>
											<td><?php echo $List->descwork; ?></td>
											<td align="right"><?php if($List->measurement_no != 0) { echo $List->measurement_no; } ?></td>
											<td align="right"><?php if($List->measurement_l != 0) { echo number_format($List->measurement_l,$decimal,".",","); } ?></td>
											<td align="right"><?php if($List->measurement_b != 0) { echo number_format($List->measurement_b,$decimal,".",","); } ?></td>
											<td align="right"><?php if($List->measurement_d != 0) { echo number_format($List->measurement_d,$decimal,".",",") ." ".$List->structdepth_unit; } ?></td>
											<td align="right"><?php if($List->measurement_contentarea != 0) { echo number_format($List->measurement_contentarea,$decimal,".",","); } ?></td>
											<td align="right"><?php echo $List->remarks; ?></td>
										</tr>
									    <? } ?>	
									    <?php  $slno++; } }else{  ?>
										<tr>
										   <td align="center" colspan="12"><?php echo "No Records Fund"; ?></td>
										</tr>
									    <?php }?> 
									</tbody>
								</table>
                                 </div>
								<div class="col2">&nbsp;</div>
								<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
									<div class="buttonsection">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
									</div>
								</div>							
                        </blockquote>
                    </div>
                </div>
            </div>
             <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
		   <link rel="stylesheet" type="text/css" media="screen" href="dataTable/jquery.dataTables.min.css" />
           <script type="text/javascript" src="dataTable/jquery.dataTables.min.js"></script>
		   <script>
			$(document).ready(function() { 
				$('#example').DataTable();
			} );
			</script>
		   <style>
		   	#simplemodal-container{
				background:#FFFFFF;
				width:50%;
				height:200px;
			}#simplemodal-container a.modalCloseImg{
				background:none;
			}.gradientbgR{
				width:100%;
				font-weight:bold;
				color:#FFFFFF;
				padding:5px 0px 5px 0px;
				font-family:Verdana, Arial, Helvetica, sans-serif;
				background-repeat: repeat-x;
				background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#F60365), to(#AF0147));
				background: -webkit-linear-gradient(top, #F60365, #AF0147);
				background: -moz-linear-gradient(top, #F60365, #AF0147);
				background: -ms-linear-gradient(top, #F60365, #AF0147);
				background: -o-linear-gradient(top, #F60365, #AF0147);
			}
			.gradientbgB{
				width:100%;
				font-weight:bold;
				color:#FFFFFF;
				padding:5px 0px 5px 0px;
				font-family:Verdana, Arial, Helvetica, sans-serif;
				background-repeat: repeat-x;
				background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#0184C4), to(#035A85));
				background: -webkit-linear-gradient(top, #0184C4, #035A85);
				background: -moz-linear-gradient(top, #0184C4, #035A85);
				background: -ms-linear-gradient(top, #0184C4, #035A85);
				background: -o-linear-gradient(top, #0184C4, #035A85);
			}
			#table1, #table2, #table3{
				color:#0375C8;
				font-weight:bold;
				font-size:13px;
			}
			.table2 td{
				padding:10px;
			}
		   </style>
        </form>
    </body>
</html>
