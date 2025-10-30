<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "library/common.php";
checkUser();
$msg = '';

?>
<?php require_once "Header.html"; ?>
<script>
   $(function () {
         $( "#txt_from_date" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "dd/mm/yy",
			//maxDate: new Date,
			defaultDate: new Date,
         });
	});
	function find_workname()
	{		
		
		var xmlHttp;
		var data;
		var i,j;
			
		if(window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if(window.ActiveXObject) // For Internet Explorer
		{ 
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL="find_workname.php?sheetid="+document.form.cmb_work_no.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText
				var name=data.split("*");
				if(data=="")
				{
					document.form.txt_workorder_no.value = '';
				}
				else
				{	
					document.form.txt_workorder_no.value = name[2].trim();
				}
			}
		}
		xmlHttp.send(strURL);	
	}
	function goBack()
	{
	   	url = "MaterialBroughtToSiteReport.php";
		window.location.replace(url);
	}
	function goBackAcc()
	{
	   	url = "MyViewAccounts.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
	.DispSelectBox{
		border:1px solid #0195D5;
		font-size:11px;
		padding:4px 4px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		width:100%;
		margin-top:2px;
		margin-bottom:2px;
		color:#03447E;
		font-weight:600;
		box-sizing: border-box;
	}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!-- ==============================header================================= -->
        <?php include "Menu.php"; ?>
        <!-- ==============================Content================================ -->
        <div class="content">
            <div class="title">Material Brought to Site</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <form name="form" method="post" action="MaterialBroughtToSiteOthersList.php">
							<div class="container">
								<div class="row clearrow"></div>
								<div class="row">
									<div class="div2">&nbsp;</div>
									<div class="div8">
										<div class="row"><div class="div12" style="margin-top:0px;"><div class="row divhead" align="center">Material Brought to Site Details View</div></div></div>
										<div class="row innerdiv">
											
											<div class="row">
												<div class="div1">&nbsp;</div>
												<div class="div4">
													<label for="fname">Wok Short Name</label>
												</div>
												<div class="div6">
													<select name="cmb_work_no" id="cmb_work_no" onChange="find_workname()" class="DispSelectBox">
													 	<option value="">--------------- Select ---------------</option>
													 	<?php echo $objBind->BindAllAgreement(0); ?>
												   </select>
												</div>
												<div class="div1">&nbsp;</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div1">&nbsp;</div>
												<div class="div4">
													<label for="fname">Work Order No.</label>
												</div>
												<div class="div6">
													<input type="text" name="txt_workorder_no" id="txt_workorder_no" readonly="" class="DispSelectBox">
												</div>
												<div class="div1">&nbsp;</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div1">&nbsp;</div>
												<div class="div4">
													<label for="fname">Material Type </label>
												</div>
												<div class="div6">
													<select name="cmb_type" id="cmb_type" class="DispSelectBox">
										 				<option value="">----------------- Select -----------------</option>
														<!--<option value="ALL">All Material</option>-->
										 				<?php echo $objBind->BindEscMaterial('','10CA',"G"); ?>
									   				</select>
												</div>
												<div class="div3">&nbsp;</div>
											</div>
											<div class="smediv">&nbsp;</div>
										</div>
										<div class="smediv">&nbsp;</div>
									</div>
									<div class="div2">&nbsp;</div>
								</div>
								
								   
								<div class="row">
									<div class="div12" align="center">
										 <input type="submit" data-type="submit" value=" View " name="view" id="view"/>
									</div>
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
	$("#cmb_work_no").chosen();
	if(window.history.replaceState ) {
		window.history.replaceState( null, null, window.location.href );
	}
    $(document).ready(function () {
		$(".DatePick").datepicker({
			changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            maxDate: new Date,
            defaultDate: new Date,
        });
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
					}
				}]
			});
		}
		var KillEvent = 0;
		$('body').on("click","#save", function(event){ 
			if(KillEvent == 0){
				var WorkName 	= $('#cmb_work_no').val();
				var WorkOrderNo = $('#txt_workorder_no').val();
				var MatType 	= $('#cmb_type').val();
				var InvoiceDate = $('#txt_invoice_date').val();
				var InvoiceNo 	= $('#txt_invoice_no').val();
				var InvoiceQty 	= $('#txt_qty').val();
				var QtyUnit 	= $('#cmb_unit').val();
				var SiteRecDate = $('#txt_site_rec_date').val();
				var WorderDate  = $('#txt_workorder_dt').val();
				var WoDateError = 0;
				if((InvoiceDate != "")&&(WorderDate != "")){  
					var d1 = InvoiceDate.split("/");
					var d2 = WorderDate.split("-");
					var InvoiceDt = new Date(d1[2], d1[1]-1, d1[0]);
					var WorderDt  = new Date(d2[0], d2[1]-1, d2[2]); 
					if(InvoiceDt < WorderDt){
						WoDateError = 1;
					}
				}
				var RecDateError = 0;
				if((InvoiceDate != "")&&(SiteRecDate != "")){  
					var d1 = InvoiceDate.split("/");
					var d2 = SiteRecDate.split("/");
					var InvoiceDt = new Date(d1[2], d1[1]-1, d1[0]);
					var SiteRecDt = new Date(d2[2], d2[1]-1, d2[0]);
					if(InvoiceDt > SiteRecDt){
						RecDateError = 1;
					}
				}
				if(WorkName == ""){
					BootstrapDialog.alert("Please select work name");
					event.preventDefault();
					event.returnValue = false;
				}else if(WorkOrderNo == ""){
					BootstrapDialog.alert("Work order No. should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}else if(MatType == ""){
					BootstrapDialog.alert("Please select material type");
					event.preventDefault();
					event.returnValue = false;
				}else if(InvoiceDate == ""){
					BootstrapDialog.alert("Please select invoice date");
					event.preventDefault();
					event.returnValue = false;
				}else if(WoDateError == 1){
					BootstrapDialog.alert("Invoice date should be less than work order date");
					event.preventDefault();
					event.returnValue = false;
				}else if(isDate(InvoiceDate)==false){
					BootstrapDialog.alert("Invoice date format should be dd/mm/yyyy");
					event.preventDefault();
					event.returnValue = false;
				}else if(InvoiceNo == ""){
					BootstrapDialog.alert("Please enter invoice no.");
					event.preventDefault();
					event.returnValue = false;
				}else if(InvoiceQty == ""){
					BootstrapDialog.alert("Please enter material quantity.");
					event.preventDefault();
					event.returnValue = false;
				}else if(QtyUnit == ""){
					BootstrapDialog.alert("Please select unit");
					event.preventDefault();
					event.returnValue = false;
				}else if(SiteRecDate == ""){
					BootstrapDialog.alert("Please enter material received date");
					event.preventDefault();
					event.returnValue = false;
				}else if(isDate(SiteRecDate)==false){
					BootstrapDialog.alert("Material received date format should be dd/mm/yyyy");
					event.preventDefault();
					event.returnValue = false;
				}else if(RecDateError == 1){
					BootstrapDialog.alert("Material received date should be greater than or equal to invoice date");
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
    </body>
</html>

