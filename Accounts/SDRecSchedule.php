<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
checkUser();
$PageName = $PTPart1.$PTIcon.'GST Statement';
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);
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
$msg = ""; $RowCount = 0; 
$IsEdit = 0; $report = false;
function moneyFormat($amt)
{
	/* 
	1. IMPORTANT NOTES: Plz use this result of this funtion for only print output in following format. 
	2. DONT USE addtion, subtraction, multiplication and division function using this result. 
	3. Because it gives the result in string - data type. If we use this output will be wrong.
	*/
	$result = "";
	$amount = number_format($amt, 2, '.', '');
	$explodeRes = explode(".",$amount);
	$ratePart = $explodeRes[0];
	$decimalPart = $explodeRes[1];
	$length = strlen($ratePart);
	if($length>3)
	{
		$getArray = str_split($ratePart);
		$count = count($getArray);
		if(($count%2) == 0)
		{
			$i = 0;
			while($i<$count)
			{
				if($i == ($count-3))
				{
					$result .= $getArray[$i].$getArray[$i+1].$getArray[$i+2];
					$i = $count-1;
				}
				else if($i == 0)
				{
					$result .= $getArray[$i].",";
				}
				else
				{
					$result .= $getArray[$i].$getArray[$i+1].",";
					$i++;
				}
				$i++;
			}
		}
		else
		{
			$i = 0;
			while($i<$count)
			{
				if($i == ($count-3))
				{
					$result .= $getArray[$i].$getArray[$i+1].$getArray[$i+2];
					$i = $count-1;
				}
				else
				{
					$result .= $getArray[$i].$getArray[$i+1].",";
					$i++;
				}
				$i++;
			}
		}
		$result = $result.".".$decimalPart;
	}
	else
	{
		$result = $amount;
	}
	return $result;
}


if(isset($_POST['btn_view'])){
//echo $_POST['type'];exit;
	/*if ($_POST['type']!="")
		$type=$_POST['type'];

	if ($_POST['txt_fromdt']!="")
		$fromdt=dt_format($_POST['txt_fromdt']);
		//echo $fromdt;exit;
	if ($_POST['txt_todt']!="")
		$todt=dt_format($_POST['txt_todt']);

	if ($_POST['cmb_mon']!="")
		$month=$_POST['cmb_mon'];
		
		
	if($type=='Month')
	{
		if ($_POST['txt_year_mon']!="")
			$year=$_POST['txt_year_mon'];
	}
	
	if($type=='Year')
	{
		if ($_POST['txt_year']!="")
			$year=$_POST['txt_year'];
	}
	//echo $year;exit;
	if($type=='Day') 
	{
		$sql_date= "select * from memo_payment_accounts_edit where payment_dt between '" . $fromdt . "' and '" . $todt . "'";
		//"select * from memo_payment_accounts_edit where alloted_place='" . $name . "' and 
		//			checkin_date between '" . $fromdt . "' and '" . $todt . "'";
		$heading= 'BETWEEN ' . dt_display($fromdt) . ' AND ' . dt_display($todt);
	}
	
	if($type=='Month') 
	{
		$sql_date="select * from memo_payment_accounts_edit where MONTH(payment_dt)=" . $month . " and YEAR(payment_dt) =" . $year;
		//echo $sql_date;exit;
		$mon=strtoupper(date("M",mktime(0,0,0,($month+1),0,0)));
		$heading='FOR - ' . $mon . '/' . $year;
	}
	
	if($type=='Year') 
	{
		$sql_date="select * from memo_payment_accounts_edit where YEAR(payment_dt) ='" . $year . "'";
		// SELECT * FROM memo_payment_accounts_edit WHERE YEAR(`modifieddate`) = '2022'
		$heading='FOR YEAR - ' . $year;
	}
	//echo $sql_date.'<br/>';
	$rs_date_sql=mysqli_query($dbConn,$sql_date);

	$report=true;*/

	$FromToDateVal = 0;
	$YearMonthVal 	= 0;
	$NoRecVal = 0;
	$sql_date		= null;
	if(isset($_POST['txt_year'])){
		if($_POST['txt_year'] != null){
			$year = $_POST['txt_year'];
			$YearMonthVal = $YearMonthVal + 1;
		}
	}
	if(isset($_POST['cmb_mon'])){
		if($_POST['cmb_mon'] != null){
			$month = $_POST['cmb_mon'];
			$YearMonthVal = $YearMonthVal + 1;
		}
	}
	if(isset($_POST['txt_fromdt'])){
		if($_POST['txt_fromdt'] != null){
			$fromdt=dt_format($_POST['txt_fromdt']);
			$FromToDateVal = $FromToDateVal + 1;
		}
	}
	if(isset($_POST['txt_todt'])){
		if($_POST['txt_todt'] != null){
			$todt=dt_format($_POST['txt_todt']);
			$FromToDateVal = $FromToDateVal + 1;
		}
	}
	//echo $YearMonthVal;exit;
	if(($FromToDateVal == 2) && ($YearMonthVal == 2)){
		$msg = "Please Select Only one Option Year and Month or Particular Period ..!!";
	}else{
		if($FromToDateVal == 2){
			$sql_date= "SELECT * FROM memo_payment_accounts_edit WHERE sd_amt != 0 AND payment_dt BETWEEN '" . $fromdt . "' and '" . $todt . "' ORDER BY payment_dt ASC;";
			//"select * from memo_payment_accounts_edit where alloted_place='" . $name . "' and 
			//			checkin_date between '" . $fromdt . "' and '" . $todt . "'";
			$heading= 'BETWEEN ' . dt_display($fromdt) . ' AND ' . dt_display($todt);
		}
		if($YearMonthVal == 2){
			if($month == 'ALL'){
				$sql_date = "SELECT * FROM memo_payment_accounts_edit WHERE sd_amt != 0 AND YEAR(payment_dt) =" . $year . " ORDER BY payment_dt ASC;";
				//$mon=strtoupper(date("M",mktime(0,0,0,($month+1),0,0)));
				$heading='FOR - '. $year;	
			}else{
				$sql_date = "SELECT * FROM memo_payment_accounts_edit WHERE sd_amt != 0 AND MONTH(payment_dt)=" . $month . " AND YEAR(payment_dt) =" . $year ." ORDER BY payment_dt ASC;";
				$mon=strtoupper(date("M",mktime(0,0,0,($month+1),0,0)));
				$heading='FOR - ' . $mon . '/' . $year;	
			}
		}
		if($sql_date != null){
			$rs_date_sql = mysqli_query($dbConn,$sql_date);
			if($rs_date_sql == true){
				if(mysqli_num_rows($rs_date_sql)>0){
					$report = true;
				}else{
					$NoRecVal = 1;
				}
			}
		}
	}

	/*	$year = $_POST['txt_year'];
	$month = $_POST['cmb_mon'];
	$sql_date = "select * from memo_payment_accounts_edit where MONTH(payment_dt)=" . $month . " and YEAR(payment_dt) =" . $year;
	$rs_date_sql = mysqli_query($dbConn,$sql_date);
	$report = true;
	$mon=strtoupper(date("M",mktime(0,0,0,($month+1),0,0)));
	$heading='FOR - ' . $mon . '/' . $year;	*/
}

?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }

	function validation()
	{
		if ( document.form.type[0].checked==true )
		{
			x=alltrim(document.form.txt_year.value);
			if(x.length==0)
			{
				BootstrapDialog.alert("Please Enter the Year")
				document.form.txt_year.value="";
				document.form.txt_year.focus();
				return false
			}
			else
			{
				document.form.txt_year.value=x;
				x=numeric_only(document.form.txt_year.value)
				if(x==0)
				{
					BootstrapDialog.alert("Please Enter valid Year and should be in numeric")
					document.form.txt_year.value="";
					document.form.txt_year.focus();
					return false;
				}	
			}
		}else if ( document.form.type[1].checked==true )
		{
			if(document.form.cmb_mon.value=='Select')
			{
				BootstrapDialog.alert("Please select the Month..!!")
				document.form.cmb_mon.focus();
				return false
			}
			
			x=alltrim(document.form.txt_year_mon.value);
			if(x.length==0)
			{
				BootstrapDialog.alert("Please Enter the Year..!!")
				document.form.txt_year_mon.value="";
				document.form.txt_year_mon.focus();
				return false
			}
			else
			{
				document.form.txt_year_mon.value=x;
				x=numeric_only(document.form.txt_year_mon.value)
				if(x==0)
				{
					BootstrapDialog.alert("Please Enter valid Year and should be in numeric..!!")
					document.form.txt_year_mon.value="";
					document.form.txt_year_mon.focus();
					return false;
				}	
			}
		}else if( document.form.type[2].checked==true )
		{
			x=alltrim(document.form.txt_fromdt.value);
			if(x.length==0)
			{
				BootstrapDialog.alert("Please select From Date..!!")
				document.form.txt_fromdt.value="";
				document.form.txt_fromdt.focus();
				return false
			}
		
			x=alltrim(document.form.txt_todt.value);
			if(x.length==0)
			{
				BootstrapDialog.alert("Please select To Date..!!")
				document.form.txt_todt.value="";
				document.form.txt_todt.focus();
				return false
			}
			
			var fromDate = document.form.txt_fromdt.value;
			var toDate = document.form.txt_todt.value;
			var regExp = /(\d{1,2})\/(\d{1,2})\/(\d{2,4})/;
			
			if(parseInt(fromDate.replace(regExp, "$3$2$1")) > parseInt(toDate.replace(regExp, "$3$2$1")))
			{
				BootstrapDialog.alert("To Date should be greater than From Date");
				document.form.txt_todt.value='';
				return false;
			}
		}else if((document.form.type[2].checked!=true)&&(document.form.type[1].checked!=true)&&(document.form.type[0].checked!=true)){
			BootstrapDialog.alert("Please Select Year/Month/Period of Statement..!!");
		}
	}
	function show()
	{
		if ( document.form.type[0].checked==true )
		{	//alert(JSON.stringify(document.form.type[0]));
			$('#year').show();
			$('#month').hide();
			$('#day').hide();
			$('#day1').hide();
			//document.getElementById("month").style.display="";
			//document.getElementById("year").style.display="none";
			//document.getElementById("day").style.display="none";
		}
		if ( document.form.type[1].checked==true )
		{
			$('#month').show();
			$('#year').hide();
			$('#day').hide();
			$('#day1').hide();
			//document.getElementById("month").style.display="none";
			//document.getElementById("year").style.display="";
			//document.getElementById("day").style.display="none";
		}
		if ( document.form.type[2].checked==true )
		{
			$('#day').show();
			$('#day1').show();
			$('#month').hide();
			$('#year').hide();
			//document.getElementById("month").style.display="none";
			//document.getElementById("year").style.display="none";
			//document.getElementById("day").style.display="";
		}
	}
	function list_search(id,val)
	{
		cnt=document.getElementById(id).length
		for(x=0; x<cnt; x++ )
		{
			if( document.getElementById(id).options(x).value==val)
			{
				document.getElementById(id).options(x).selected=true
				break;
			}
		}
	} 
</script>	
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<?php include "MainMenu.php"; ?>
				<div class="container_12">
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" style="overflow:auto">
							
							<div class="row">
								<div class="box-container box-container-lg">
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;SD Recovery Schedule <span id="CourseChartDuration"></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<!--<div class="div2 pd-lr-1">
															<div class="lboxlabel-sm">Year</div>
															<div>
																<div class="div12 inputGroup">
																	<input type="radio" name="type" value="Year" id="type_year" onClick="show()" />
																	<label for="type_year" style="padding:3px 0px; width:99%; font-size:11px;" class="cboxlabel">&nbsp; Year</label>
																</div>
															</div>
														</div>
														<div class="div2 pd-lr-1">
															<div class="lboxlabel-sm">&nbsp;</div>
															<div>
																<div class="div12 inputGroup">
																	<input type="radio" name="type" value="Month" id="type_month" onClick="show()"/>
																	<label for="type_month" style="padding:3px 0px; width:99%; font-size:11px;" class="cboxlabel">&nbsp; Month</label>
																</div>
															</div>
														</div>
														<div class="div2 pd-lr-1">
															<div class="lboxlabel-sm">&nbsp;</div>
															<div>
																<div class="div12 inputGroup">
																	<input type="radio" name="type" value="Day" id="type_day" onClick="show()"/>
																	<label for="type_day" style="padding:3px 0px; width:99%; font-size:11px;" class="cboxlabel">&nbsp; Period</label>
																</div>
															</div>
														</div>-->
														
														<div class="div2 pd-lr-1" id="YearRow">
															<div class="lboxlabel-sm">&nbsp;Select Year</div>
																<select name='txt_year' id='txt_year' class='tboxclass'>
																	<option value="">---Select---</option>
																	<?php echo $objBind->BindYear(0); ?>
																</select>
														</div>
														
														<div class="div2 pd-lr-1" id="MonthRow">
															<div class="lboxlabel-sm">&nbsp;Select Month</div>
															<div>
																<select name="cmb_mon" id="cmb_mon" class="tboxclass">
																	<option value="">---Select---</option>
																	<option value="ALL">ALL Months</option>
																	<option value="01">JAN</option>
																	<option value="02">FEB</option>
																	<option value="03">MAR</option>
																	<option value="04">APR</option>
																	<option value="05">MAY</option>
																	<option value="06">JUN</option>
																	<option value="07">JUL</option>
																	<option value="08">AUG</option>
																	<option value="09">SEP</option>
																	<option value="10">OCT</option>
																	<option value="11">NOV</option>
																	<option value="12">DEC</option>
																</select>
															</div>
														</div>
														<div class="div2" id="PeriodRow1">
															<div class="cboxlabel-sm">&nbsp;</div>
															<div class="cboxlabel">
																(OR Select Period)
															</div>
														</div>
														<div class="div2 pd-lr-1" id="PeriodRow1">
															<div class="lboxlabel-sm">&nbsp;From Date</div>
															<div>
																<input type="text" value="" readonly="" name="txt_fromdt" id="txt_fromdt" class="tboxclass datepicker" />
															</div>
														</div>
														<div class="div2 pd-lr-1" id="PeriodRow2">
															<div class="lboxlabel-sm">&nbsp;To Date</div>
															<div>
																<input type="text" value="" readonly="" name="txt_todt" id="txt_todt" class="tboxclass datepicker" />
															</div>
														</div>
														
														<div class="div1 pd-lr-1">
															<div class="lboxlabel-sm">&nbsp;</div>
															<div>
																<input type="submit" name="btn_view" id="btn_view" class="btn btn-info" value="View">
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
									<div class="box-container box-container-lg" align="center">
										<div class="div12" id="appendrow">
											<div class="card cabox">
												<div class="face-static">
													<!-- <div class="card-header inkblue-card" align="center">BARC-NRB-FRFCF-KALPAKKAM <?php //echo $heading; ?></div> -->
													<div class="card-header inkblue-card" align="center">SECURITY DEPOSIT RECOVERY SCHEDULE <?php echo $heading; ?> <span class="ralignbox fright"><span class="xldownload" id="exportToExcel"> Download Excel <i class="fa fa-file-excel-o"></i> </span></span></div>
													<div class="card-body padding-1 ChartCard" id="CourseChart">
														<div class="divrowbox pt-2">
															<div class="table-responsive dt-responsive ResultTable">
																<div class="table-responsive dt-responsive rtabdiv" id="table-stmt">
																	<div class="div12">&nbsp;</div>
																	<?php if($report==true){ ?>
																		<table class="table dataTable rtable table2excel example" border="1" width="100%" align="center">
																			<tr>
																				<td class="colhead">S.No</td>
																				<td class="colhead">CCNO.</td>
																				<td class="colhead">NAME OF THE CONTRACTOR</td>
																				<td class="colhead">NAME OF THE WORK</td>
																				<td class="colhead">RAB NO.</td>
																				<td class="colhead">DATE OF PAYMENT</td>
																				<td class="colhead">SD RECOVERY AMOUNT</td>
																				<td class="colhead" style="font-weight:bold;">REMARKS</td>
																			</tr>
																		<?php
																		$TotSumBillVal = 0;
																		if($rs_date_sql == true){
																			$sno=1;
																			$TotSumBillVal = 0;
																			while($List = mysqli_fetch_object($rs_date_sql)){
																				$SheetId 	= $List->sheetid;
																				$RabNo 		= $List->rbn;
																				$SDAmt 		= $List->sd_amt;
																				$DatePay		= dt_display($List->payment_dt);
																				//echo $SheetId;
																				$sql_select="select contid,computer_code_no,work_name from sheet where sheet_id ='" . $SheetId . "'";
																				$sql_selectSql = mysqli_query($dbConn,$sql_select);
																				while($List1 = mysqli_fetch_assoc($sql_selectSql)){
																					$ContId	 = $List1['contid'];
																					$CCNum	 = $List1['computer_code_no'];
																					$WorkName = $List1['work_name'];
																					$sql_select1 	 ="select * from contractor where contid ='" . $ContId . "'";
																					$sql_selectSql1 = mysqli_query($dbConn,$sql_select1);	
																					while($List2 = mysqli_fetch_object($sql_selectSql1)){
																						$ContName = $List2->name_contractor;
																						
																						echo "<tr>";
																						echo "<td class='labelcenter'>" . $sno . '.' . "</td>";
																						echo "<td class='labelcenter' nowrap='nowrap'>" .  $CCNum . "</td>";
																						echo "<td class='labelleft' nowrap='nowrap'>" .  $ContName . "</td>";
																						echo "<td class='labelleft'>" . $WorkName . "</td>";
																						echo "<td class='labelcenter' nowrap='nowrap'>" .  $RabNo . "</td>";
																						echo "<td class='labelcenter' nowrap>". $DatePay ."</td>";
																						echo "<td class='labelright' nowrap='nowrap'>" . $SDAmt . "</td>";
																						echo "<td class='labelright' nowrap='nowrap'></td>";
																						echo "</tr>";
																					}
																				}
																				$TotSumBillVal = $TotSumBillVal+$SDAmt;
																				$sno++;
																			}
																			$TotSumValIndFormat = moneyFormat($TotSumBillVal);

																			echo "<tr>";
																			echo "<td class='labelcenter'></td>";
																			echo "<td class='labelcenter'></td>";
																			echo "<td class='labelcenter'></td>";
																			echo "<td class='labelleft'>TOTAL</td>";
																			echo "<td class='labelright' nowrap='nowrap'></td>";
																			echo "<td class='labelright' nowrap='nowrap'></td>";
																			echo "<td class='labelright' nowrap='nowrap'>" . $TotSumValIndFormat . "</td>";
																			echo "<td class='labelcenter'></td>";
																			echo "</tr>";
																		}
																		else
																		{
																			echo"<tr>";
																			echo"<td class='labelcenter' colspan='9'>No Records Found</td>";
																			echo"</tr>";
																		}
																		?>
																		</table>
																	<?php } if($NoRecVal == 1){
																				echo"<div>";
																				echo"<div class='labelcenter' colspan='9'>No Records Found</div>";
																				echo"</div>";
																			} ?>
																	<div class="div12">&nbsp;</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

						</blockquote>
					</div>
				</div>
			</div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
</html>
<script src="js/CommonJSLibrary.js"></script>
<script type="text/javascript" language="javascript">
	$('#month').hide();
	$('#year').hide();
	$('#day').hide();
	$('#day1').hide();
$(function(){
	var msg = "<?php echo $msg; ?>";
	document.querySelector('#top').onload = function(){
		if(msg != ""){
			//BootstrapDialog.alert(msg);
			BootstrapDialog.show({
				title: 'Information',
				closable: false,
				message: msg,
				buttons: [{
					label: '&nbsp; OK &nbsp;',
					action: function(dialog) {
						$(location).attr("href","VouchersList.php");
					}
				}]
			});
		}
	};
	$("#check_all").click(function(){
		$('input:checkbox').not(this).prop('checked', this.checked);
	});
});
$("#exportToExcel").click(function(e){ 
	var table = $('body').find('.table2excel');
	if(table.length){ 
		$(table).table2excel({
			exclude: ".noExl",
			name: "Excel Document Name",
			filename: "GSTStatement-" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
			fileext: ".xls",
			exclude_img: true,
			exclude_links: true,
			exclude_inputs: true
			//preserveColors: preserveColors
		});
	}
});
/*$("#txt_todt").datepicker({
	dateFormat: "dd/mm/yy",
	changeMonth: true,
	changeYear: true,
});*/
$("#txt_fromdt").datepicker({
	dateFormat: "dd/mm/yy",
	changeMonth: true,
	changeYear: true,
	onSelect: function (selectedDate) {                                           
		$('#txt_todt').datepicker('option', 'minDate', selectedDate);
	}
});
if(window.history.replaceState ) {
	window.history.replaceState( null, null, window.location.href );
}

var OktoView = 0;
$("body").on("click","#btn_view", function(event){
	var SelYear		= $("#txt_year").val();
	var SelMonth	= $("#cmb_mon").val();                  
	var SelFrDate	= $("#txt_fromdt").val();
	var SelToDate	= $("#txt_todt").val();

	OktoView = 1;
	switch(true) {
		case ((SelYear == "") && (SelMonth == "") && (SelFrDate == "") && (SelToDate == "")) :
			BootstrapDialog.alert("Please select atleast one period..!!");
			event.preventDefault();
			event.returnValue = false;
		break;
		case ((SelYear != "") && (SelMonth != "") && (SelFrDate != "") && (SelToDate != "")) :
			BootstrapDialog.alert("Please select (Year - Month) or (From Date - To Date) any one period..!!");
			event.preventDefault();
			event.returnValue = false;
		break;
		case ((SelYear == "") && (SelMonth == "") && (SelFrDate == "") && (SelToDate != "")) :
			BootstrapDialog.alert("Please select Valid From Date - To Date period..!!");
			event.preventDefault();
			event.returnValue = false;
		break;
		case ((SelYear == "") && (SelMonth == "") && (SelFrDate != "") && (SelToDate == "")) :
			BootstrapDialog.alert("Please select Valid From Date - To Date period..!!");
			event.preventDefault();
			event.returnValue = false;
		break;
		case ((SelYear != "") && (SelMonth == "") && (SelFrDate == "") && (SelToDate == "")) :
			BootstrapDialog.alert("Please select Valid Month..!!");
			event.preventDefault();
			event.returnValue = false;
		break;
		case ((SelYear != "") && (SelMonth == "") && (SelFrDate == "") && (SelToDate != "")) :
			BootstrapDialog.alert("Please select Valid period..!!");
			event.preventDefault();
			event.returnValue = false;
		break;
		case ((SelYear != "") && (SelMonth == "") && (SelFrDate != "") && (SelToDate == "")) :
			BootstrapDialog.alert("Please select Valid period..!!");
			event.preventDefault();
			event.returnValue = false;
		break;		
		case ((SelYear == "") && (SelMonth != "") && (SelFrDate == "") && (SelToDate == "")) :
			BootstrapDialog.alert("Please Enter Valid Year..!!");
			event.preventDefault();
			event.returnValue = false;
		break;		
		case ((SelYear == "") && (SelMonth != "") && (SelFrDate == "") && (SelToDate != "")) :
			BootstrapDialog.alert("Please select Valid period..!!");
			event.preventDefault();
			event.returnValue = false;
		break;
		case ((SelYear == "") && (SelMonth != "") && (SelFrDate != "") && (SelToDate == "")) :
			BootstrapDialog.alert("Please select Valid period..!!");
			event.preventDefault();
			event.returnValue = false;
	}
});

</script>
<style>
	.tboxclass{
		width:99%;
	}
	table.dataTable thead > tr > th.sorting::before{
		bottom: 0%;
		content: "";
	}
	table.dataTable thead > tr > th.sorting::after{
		top: 0%;
		content: "";
	}
	th.tabtitle{
		text-align:left !important;
	}
	.mgtb-8 td{
		padding:2px !important;
		font-size:10px !important;
		font-weight:500;
	}
	.mgtb-8 th{
		background-color:#F2F3F4 !important;
		font-size:10px !important;
		padding:2px !important;
	}
</style>
