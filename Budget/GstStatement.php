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

if(isset($_POST['btn_view_x'])){

	if ($_POST['type']!="")
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
		$sql_date= "select * from memo_payment_accounts_edit where payment_dt between '" . $fromdt . "' and '" . $todt . "';";
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

	$report=true;
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
			if(document.form.cmb_mon.value=='Select')
			{
				alert("Please select the Month")
				document.form.cmb_mon.focus();
				return false
			}
			
			x=alltrim(document.form.txt_year_mon.value);
			if(x.length==0)
			{
				alert("Please Enter the Year")
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
					alert("Please Enter valid Year and should be in numeric")
					document.form.txt_year_mon.value="";
					document.form.txt_year_mon.focus();
					return false;
				}	
			}
		}
		
		if ( document.form.type[1].checked==true )
		{
			x=alltrim(document.form.txt_year.value);
			if(x.length==0)
			{
				alert("Please Enter the Year")
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
					alert("Please Enter valid Year and should be in numeric")
					document.form.txt_year.value="";
					document.form.txt_year.focus();
					return false;
				}	
			}
		}
		
		if ( document.form.type[2].checked==true )
		{
			x=alltrim(document.form.txt_fromdt.value);
			if(x.length==0)
			{
				alert("Please select From Date")
				document.form.txt_fromdt.value="";
				document.form.txt_fromdt.focus();
				return false
			}
		
			x=alltrim(document.form.txt_todt.value);
			if(x.length==0)
			{
				alert("Please select To Date")
				document.form.txt_todt.value="";
				document.form.txt_todt.focus();
				return false
			}
			
			var fromDate = document.form.txt_fromdt.value;
			var toDate = document.form.txt_todt.value;
			var regExp = /(\d{1,2})\/(\d{1,2})\/(\d{2,4})/;
			
			if(parseInt(fromDate.replace(regExp, "$3$2$1")) > parseInt(toDate.replace(regExp, "$3$2$1")))
			{
				alert("To Date should be greater than From Date");
				document.form.txt_todt.value='';
				return false;
			}
		}
	}
	function show()
	{
		if ( document.form.type[0].checked==true )
		{	
			document.getElementById("month").style.display="";
			document.getElementById("year").style.display="none";
			document.getElementById("day").style.display="none";
		}
		if ( document.form.type[1].checked==true )
		{
			document.getElementById("month").style.display="none";
			document.getElementById("year").style.display="";
			document.getElementById("day").style.display="none";
		}
		if ( document.form.type[2].checked==true )
		{
			document.getElementById("month").style.display="none";
			document.getElementById("year").style.display="none";
			document.getElementById("day").style.display="";
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
								<input type="hidden" name="max_group" id="max_group" value="1" />
								
								<?php if($report==true){ ?>
									<table class="table itemtable rtable table2excel" border="1" width="100%" align="center">
										<tr><td colspan="9" class="tablehead"> BARC-NRB-FRFCF-KALPAKKAM <?php echo $heading; ?></td></tr>
										<tr><td colspan="9" class="tablehead"> TDS ON GST RECOVERY  STATEMENT <?php echo $heading; ?></td></tr>
										<tr>
											<td class="colhead">S.No</td>
											<td class="colhead">PAN</td>
											<td class="colhead">NAME OF CONTRACTOR</td>
											<td class="colhead">DTPMT</td>
											<td class="colhead">BILVAL_TDS</td>
											<td class="colhead">CGST TDS</td>
											<td class="colhead">SGST TDS</td>
											<td class="colhead">IGST TDS</td>
											<td class="colhead">GST NO</td>
										</tr>
									<?php
									if($rs_date_sql == true){
										$sno=1;
										while($List = mysqli_fetch_object($rs_date_sql)){
											$SheetId 	= $List->sheetid;
											$SGSTPERC 	= $List->sgst_tds_perc;
											$SGSTAMT 	= $List->sgst_tds_amt;
											$CGSTPERC 	= $List->cgst_tds_perc;
											$CGSTAMT 	= $List->cgst_tds_amt;
											$IGSTPERC 	= $List->igst_tds_perc;
											$IGSTAMT 	= $List->igst_tds_amt;
											$VAmt 		= $List->vat_amt;
											$DatePay	= dt_display($List->payment_dt);
											
											$sql_select="select contid from sheet where sheet_id ='" . $SheetId . "'";
											//echo $sql_select;exit;
											$sql_selectSql 	= mysqli_query($dbConn,$sql_select);
											while($List1 = mysqli_fetch_assoc($sql_selectSql)){
												$ContId	  = $List1['contid'];
												
												$sql_select1="select * from contractor where contid ='" . $ContId . "'";
												$sql_selectSql1 	= mysqli_query($dbConn,$sql_select1);	
												while($List2 = mysqli_fetch_object($sql_selectSql1)){
													$PANNum	  = $List2->pan_no;
													$GSTNum	  = $List2->gst_no;
													$ContName = $List2->name_contractor;
													echo "<tr>";
													echo "<td class='labelcenter'>" . $sno . '.' . "</td>";
													echo "<td class='labelleft' nowrap='nowrap'>" .  $PANNum . "</td>";
													echo "<td class='labelleft' nowrap='nowrap'>" .  $ContName . "</td>";
													echo "<td class='labelcenter' nowrap='nowrap'>" .  $DatePay . "</td>";
													echo "<td class='labelright'>-</td>";
													echo "<td class='labelright' nowrap>". $CGSTAMT ."</td>";
													echo "<td class='labelright' nowrap='nowrap'>" . $SGSTAMT . "</td>";
													echo "<td class='labelright' nowrap='nowrap'>" . $IGSTAMT . "</td>";
													echo "<td class='labelcenter' nowrap='nowrap'>" . $GSTNum . "</td>";
													echo "</tr>";
												}
											}
											$sno++;
										}
										echo "<tr>";
										echo "<td class='labelcenter'></td>";
										echo "<td class='labelcenter'></td>";
										echo "<td class='labelcenter'></td>";
										echo "<td class='labelleft'>TOTAL</td>";
										echo "<td class='labelright' nowrap='nowrap'>0</td>";
										echo "<td class='labelright' nowrap='nowrap'>0</td>";
										echo "<td class='labelright' nowrap='nowrap'>0</td>";
										echo "<td class='labelright' nowrap='nowrap'>0</td>";
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
									<br />

									<center>
										<input type="button" class="btn btn-info" name="exportToExcel" id="exportToExcel" value="Export - Excel" />
										<input type="image" class="btn btn-info" name="btn_back" id="btn_back" value="Back" onClick="func_back()" />
									</center>
									<br />
									<?php }else{ ?>
										<table  class="table"  cellpadding="5" cellspacing="0" width="70%" align="center">
											<tr><td class="tablehead"> TDS ON GST RECOVERY STATEMENT</td></tr>
										</table>
										<table class="table" border="0" cellpadding="10" cellspacing="0" width="70%" align="center">
											<tr>
												<td width="6%" align="center" class="labelleft">&nbsp;</td>
												<td class="labelleft" colspan="4">
													<input type="radio" name="type" value="Month" onclick="show()" />Month&nbsp;&nbsp;
													<input type="radio" name="type" value="Year" onclick="show()" />Year&nbsp;&nbsp;
													<input type="radio" name="type" value="Day" checked="checked" onclick="show()" />Period	
												</td>
											</tr>
											
											<tr style="display:" id="day">	
												<td align="center" class="labelleft">&nbsp;</td>
												<td width="15%" class="labelleft">From Date</td>
												<td width="16%" class="labelleft"><input type="text" value="<?php echo date('d/m/Y');?>" readonly="" name="txt_fromdt" id="txt_fromdt" size="11" maxlength="10" class="datepicker textboxdisplay" /></td>
												<td width="12%" class="labelleft">To Date</td>
												<td class="labelleft"><input type="text" value="<?php echo date('d/m/Y');?>" readonly="" name="txt_todt" id="txt_todt" size="11" maxlength="10" class=" datepicker textboxdisplay" /></td>
											</tr>
											
											<tr style="display:none" id="month">
												<td align="center" class="labelleft">&nbsp;</td>
												<td class="labelleft" width="15%">Month</td>
												<td width="16%">
													<select name="cmb_mon" id="cmb_mon" class="textboxdisplay">
														<option value="Select">Select</option>
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
													<script type="text/javascript" language="javascript">
														list_search('cmb_mon',<?php echo '"' . date('m') . '"'; ?>)
													</script>&nbsp;
												</td>
												<td class="labelleft" width="10%">Year</td>
												<td class="labelleft"><input type="text" name="txt_year_mon" value="<?php echo date('Y'); ?>" class="textboxdisplay"  size="5" maxlength="4"/></td>
											</tr>
											
											<tr style="display:none" id="year">
												<td align="center" class="labelleft">&nbsp;</td>
												<td class="labelleft" width="10%">Year</td>
												<td class="labelleft" colspan="3"><input type="text" name="txt_year" value="<?php echo date('Y') ?>" class="textboxdisplay"  size="5" maxlength="4"/></td>
											</tr>
										</table>
										<br />
										<center>
											<input type="image" class="btn btn-info" name="btn_view" id="btn_view" value="View" onClick="return validation()" />
										</center>
									<?php 
									}
									?>
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
