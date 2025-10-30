<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
$msg = '';
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
?>
<?php require_once "Header.html"; ?>
<script>
	function goBack()
	{
	   	url = "dashboard.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<script src="handsontable/handsontable/dist/handsontable.full.js"></script>
<link type="text/css" rel="stylesheet" href="handsontable/handsontable/dist/handsontable.full.min.css">
<link href="css/CustomFancyStyle.css" rel="stylesheet">
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
	<!--==============================header=================================-->
	 <?php include "Menu.php"; ?>
	<!--==============================Content=================================-->
	<div class="content">
		<div class="title">Voucher Upload - Other Units</div>
		<div class="container_12">
			<div class="grid_12">
				<blockquote class="bq1" style="overflow:auto">
					<form name="form" method="post" action="">
						<div class="div12">
							<div class="div12 card-div-body">
								<div class="top-card">
									<!--<div class="top-card-header">sdsdf</div>-->
									<div class="top-card-container">
										<div>
											<label for="name" class="card-label">Unit </label>
											<select name="cmb_unit" id="cmb_unit" class="card-label-selectbox">
												<option value=""> -- Select --</option>
												<?php echo $objBind->BindAllDaeUnits(0); ?>
											</select>
											&emsp;
											<label for="name" class="card-label">Excel Sheet Name </label>
											<input type="text" name="txt_sheet_name" id="txt_sheet_name" class="card-label-tbox">
											&emsp;
											<label for="name" class="card-label">End Row </label>
											<input type="text" name="txt_end_row" id="txt_end_row" class="card-label-tbox">
											&emsp;
											<label for="name" class="card-label">Excel File </label>
											<input type="file" name="file" id="file" class="card-label-tbox">
											&emsp;
											<input type="button" class="backbutton" name="UploadBtn" id="UploadBtn" value=" Upload File ">
										</div>
									</div>
								</div>
							</div>
							
						</div>
						<div class="GridContainer div12">
						   <div id="voucherData" class="hot" align="center"></div>
						</div>
						<div style="text-align:center">
							<div class="buttonsection" style="display:inline-table">
								<input type="button" onClick="goBack()" class="backbutton" name="back" id="back" value="Back">
							</div>
							<div class="buttonsection" style="display:inline-table">
								<input type="submit" class="btn" data-type="submit" value=" Save " name="submit" id="submit"   />
							</div>
						</div>
					</form>
				</blockquote>
			</div>
		</div>
	</div>
	<!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
<script>
	/*const container = document.getElementById('example1');
	const data = [
	  ['File/PO', 'Item', 'PO-val[L]', 'Vr. no', 'Vr. Date', 'VrAmt', 'PO-Rel-dt.', 'O PIN', 'N PIN', 'Code', 'Paid[L]', 'Head of Account', 'New HoA', 'Indentor', 'GrpDivSec', 'Sanction OM \nactivity Sl.No', 'Sanction OM \nMW/ME Sl.No',''],
	];
	const hot = new Handsontable(container, {
	  	data,
	  	startRows: 5,
	  	startCols: 5,
	  	height: 'auto',
	  	width: 'auto',
	  	colHeaders: false,
	  	minSpareRows: 5,
	  	manualColumnResize: true,
		manualRowResize: true,
	  	renderer: 'html'
	});*/
	//hot.setDataAtCell(0,17, '<input type="button" value="+"/>')
	$(function(){
		$("#UploadBtn").click(function () {  
			$("#voucherData").html('');
			var form = $('form')[0]; // You need to use standart javascript object here
			var formData = new FormData(form);
			$.ajax({ 
				type      	: 'POST', 
				url       	: 'VoucherExcelUpload.php',
				data	  	:  formData,
				contentType	:  false,   // The content type used when sending data to the server.
				cache		:  false,   // To unable request pages to be cached
				processData	:  false, 	// To send DOMDocument or non processed data file it is set to false
				dataType	: 'json',       
				success   	: function(data){ 
					//var result 		= data.split("@@");
					var sheeterror 	= data['0'];
					var formaterror = data['1'];
					var msg 		= data['2'];
					var TotalLines 	= data['3'];
					var InsertLines = data['4'];
					var NotUploaded = data['5'];  
					var UploadedData = data['datas']; 
					const HandData = [];
					$.each(data['datas'], function(index, element) {
						HandData.push(element);
					});
					
					var ErrorLines	= Number(TotalLines)-Number(InsertLines);
					if(sheeterror == 0)
					{
						swal("Invalid Sheet Name.", "", "");
					}
					if(formaterror != "")
					{
						swal("Invalid Excel File Format.", "", "");
					} 
					if(msg != "")
					{
						if(Number(ErrorLines) == 0){
							var UploadMsg = "Vouchers Uploaded Sucessfully";
						}else{
							var UploadMsg = "Partial Vouchers Uploaded";
						} 
						var $ReturnMsg = $('<div></div>');
						$ReturnMsg.append('<div><i style="font-size:20px; color:#169C71;" class="fa">&#xf058;</i> '+UploadMsg+'</div>');
						$ReturnMsg.append('<div>&nbsp;</div>');
						$ReturnMsg.append('<div class="alert-success-row"><i style="font-size:20px; color:#0483B0;" class="fa">&#xf05a;</i>&nbsp;Total number of rows in Excel file : <span class="round-span">'+TotalLines+'</span></div>');
						$ReturnMsg.append('<div class="alert-success-row"><i style="font-size:20px; color:#169C71;" class="fa">&#xf058;</i>&nbsp;Total number of rows Uploaded : <span class="round-span">'+InsertLines+'</span></div>');
						$ReturnMsg.append('<div class="alert-danger-row"><i style="font-size:20px; color:#F30307;" class="fa">&#xf057;</i>&nbsp;Total number of rows not Uploaded : <span class="round-span">'+ErrorLines+'</span></div>');
						if(NotUploaded != ""){
							var SplitNotUploaded = NotUploaded.split(",");
							$ReturnMsg.append('<div class="alert-row-head">Not Uploaded Rows List</div>');
							var $RowList = $('<div class="alert-row-body"></div>');
							for(var i=0; i<SplitNotUploaded.length; i++){
								$RowList.append('<div class="grid_3 bottom-margin">Row : '+SplitNotUploaded[i]+'</div>');
							}
							$ReturnMsg.append($RowList);
							
						}
						BootstrapDialog.show({
							title: 'Uploaded Vouchers Details',
							message: $ReturnMsg,
							buttons: [{
								label: 'OK',
								action: function(dialogRef){
									const container = document.getElementById('voucherData');
									const data =  HandData;
									const hot = new Handsontable(container, {
										data,
										startRows: 5,
										startCols: 5,
										height: 'auto',
										width: 'auto',
										colWidths: [50, 50, 20, 20, 40, 30, 30, 20, 20, 30, 40, 70, 70, 40, 30, 40, 30],
										cell: [
											{row: 0, col: 0, className: "htMiddle"},
											{row: 0, col: 1, className: "htMiddle"},
											{row: 0, col: 2, className: "htMiddle"},
											{row: 0, col: 3, className: "htMiddle"},
											{row: 0, col: 4, className: "htMiddle"},
											{row: 0, col: 5, className: "htMiddle"},
											{row: 0, col: 6, className: "htMiddle"},
											{row: 0, col: 7, className: "htMiddle"},
											{row: 0, col: 8, className: "htMiddle"},
											{row: 0, col: 9, className: "htMiddle"},
											{row: 0, col: 10, className: "htMiddle"},
											{row: 0, col: 11, className: "htMiddle"},
											{row: 0, col: 12, className: "htMiddle"},
											{row: 0, col: 13, className: "htMiddle"},
											{row: 0, col: 14, className: "htMiddle"},
											{row: 0, col: 15, className: "htMiddle"},
											{row: 0, col: 16, className: "htMiddle"},
											{row: 0, col: 17, className: "htMiddle"}
										],
										colHeaders: ['File/<br/>PO', 'Item', 'PO<br/>Val[L]','Vr.<br/>No.', 'Vr.<br/>Date', 'Vr.<br/>Amt.', 'PO<br/>Rel.<br/>Dt.', 'O PIN', 'N PIN', 'Code', 'Paid', 'HOA', 'New<br/>HOA', 'Indentor', 'Grp<br/>Div<br/>Sec', 'Plant/<br/>Service', 'Sanction<br/> OM<br/> activity<br/> Sl.No', 'Sanction<br/> OM<br/> MW/ME<br/> Sl.No'],
										manualColumnResize: true,
										manualRowResize: true,
										renderer: 'html'
									});
									dialogRef.close();
								}
							}]
						});
					}
				}
			});
		});
	});
</script>
<style>
	.wtHolder,.wtHider,.handsontable table.htCore{
		width:100% !important;
	}
	.handsontable .wtSpreader{
		width:100% !important;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:11px;
	}
	.htCore thead th {
	  	background: #046CA8;
		color:#fff;
		vertical-align:middle;
		font-weight:600;
	}
	.handsontable td{
		line-height: 14px;
	}
</style>
</body>
</html>

