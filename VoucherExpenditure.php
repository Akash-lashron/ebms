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
      	function func_items()
        {
            var xmlHttp;
            var data;
            var i, j;
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "findrbntest.php?workordernumber=" + document.form.cmb_work_no.value;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText
                    /*if (data == "")
                    {
                        alert("No Records Found");
                        document.form.cmb_rbn.value = 'Select';
                    }*/
                    if (data != "")
                    {	
                        var name = data.split("*");
                        document.form.cmb_rbn.length = 0
                        var optn = document.createElement("option")
                        optn.value = 0;
                        optn.text = "------------------------------------ Select RBN. -------------------------------------";
                        document.form.cmb_rbn.options.add(optn)
                        var c = name.length;
                        for (i = 0 ; i < c ; i++)
                        {
                            var optn = document.createElement("option")
                            optn.value = name[i];
							optn.text = "RAB"+name[i];
                            document.form.cmb_rbn.options.add(optn)
                        }
                    }
                }
            }
            xmlHttp.send(strURL);
        }

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
		strURL="findworknametest.php?sheetid="+document.form.cmb_work_no.value;
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
					alert("No Records Found");
					document.form.workname.value='';	
				}
				else
				{	
					document.form.workname.value		=	name[0].trim();
					document.form.txt_workorder_no.value=	name[2].trim();
					document.form.txt_book_no1.value	=	Number(name[1]) + Number(1);
					document.form.txt_book_no.value		=	Number(name[1]) + Number(1);
					document.form.txt_bookpage_no1.value=	Number(name[2]) + Number(1);
					document.form.txt_bookpage_no.value	=	Number(name[2]) + Number(1);
					document.form.txt_rab_no1.value		=	Number(name[3]) + Number(1);
					document.form.txt_rab_no.value		=	Number(name[3]) + Number(1);
				}
			}
		}
		xmlHttp.send(strURL);	
	}
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
<link rel='stylesheet' href='Step-Wizard/BSMagic-min.css'/>
<script src='Library/Step-Wizard/BSMagic-min.js'></script>
<script src='Library/Step-Wizard/gsap.min.js'></script>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
			<div class="title">Voucher Expenditure </div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto">
                        <form name="form" method="post" action="VoucherExpenditureList.php">
                            <div class="div12">
								
								<div class="row justify-content-center">
									<div class="bd-example bd-example-tabs" id="JTab1">
										<div class="div12 row flex-container">
											<div class="div3"></div>
											<div class="div6 BSMagic flex-child green" style="margin-right:10px;" id="test">
												<div class="tab-content" id="v-pills-tabContent">
													<div class="tab-pane fade show active tab-body-sec" id="v-pills-application-type" role="tabpanel" aria-labelledby="v-pills-application-type-tab">
														<div class="div-tab-head">Voucher Expenditure View </div>
														<div class="div-tab-body">
															<!--<div class="erow"></div>-->
															
															
															<div class="row">
																<div class="div12" align="center">
																	<div class="innerdiv2">
																		<div class="row" align="center">
																			<div class="row">
																				
																				<!--<div class="div5 lboxlabel">Work Order / P.O. No.</div>
																				<div class="div7" align="left">
																					<select name="cmb_work_no" id="cmb_work_no" class="tboxsmclass">
																						<option value=""> -------------- Select -------------</option>
																						<option value="BARC/NRB/FRFCF/P&C/PO-002">BARC/NRB/FRFCF/P&C/PO-002</option>
																					</select>
																				</div>-->
																				<div class="div5 lboxlabel">Unit</div>
																				<div class="div7" align="left">
																					<select name="cmb_unit" id="cmb_unit" class="tboxsmclass">
																						<option value=""> ------- Select -------</option>
																						<?php echo $objBind->BindAllDaeUnits(0); ?>
																					</select>
																				</div>
																				
																				<div class="row clearrow"></div>
																				<div class="div5 lboxlabel">Voucher From Date</div>
																				<div class="div7" align="left"><input type="text" name="txt_vr_fromdate" id="txt_vr_fromdate" class="tboxsmclass datepicker"></div>
																				
																				<div class="row clearrow"></div>
																				<div class="div5 lboxlabel">Voucher To Date</div>
																				<div class="div7" align="left"><input type="text" name="txt_vr_todate" id="txt_vr_todate" class="tboxsmclass datepicker"></div>
																			
																				<!--<div class="row clearrow"></div>
																				<div class="div5 lboxlabel">Voucher Amount</div>
																				<div class="div7" align="left"><input type="text" name="txt_vr_amt" id="txt_vr_amt" class="tboxsmclass"></div>
																				
																				<div class="row clearrow"></div>
																				<div class="div5 lboxlabel">PIN No.</div>
																				<div class="div7" align="left">
																					<select name="cmb_pin_no" id="cmb_pin_no" class="tboxsmclass">
																						<option value="712">712</option>
																					</select>
																				</div>
																			
																				<div class="row clearrow"></div>
																				<div class="div5 lboxlabel">HOA</div>
																				<div class="div7" align="left">
																					<select name="cmb_hoa" id="cmb_hoa" class="tboxsmclass">
																						<option value=""> -------------- Select -------------</option>
																						<option value="4861 60 203 44 00 60">4861 60 203 44 00 60</option>
																						<option value="4861 60 203 44 00 61">4861 60 203 44 00 61</option>
																						<option value="4861 60 203 44 00 62">4861 60 203 44 00 62</option>
																					</select>
																				</div>-->
																			
																			</div>
																		</div>
								
																		<div class="row clearrow"></div>
																		
																	</div>
																</div>
															</div>
															<div class="row clearrow"></div>
															<div class="row" align="center">
																<input type="submit" class="btn" data-type="submit" value=" View " name="submit" id="submit"   />
															</div>
															
															
															<!--<div class="erow"></div>-->
														</div>
													</div>
													
												</div>
											</div>
											<div class="div3"></div>
										</div>
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

$('#cmb_work_no').chosen();
$('#cmb_item_no').chosen();
$('#cmb_pin_no').chosen();
$('#cmb_hoa').chosen();
$(function(){
	$(".datepicker").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd/mm/yy",
		maxDate: new Date,
		defaultDate: new Date,
	});
});

   //$(function() {
	/*$.fn.validaterbnno = function(event) {	
				if($("#cmb_rbn").val()==0){ 
					var a="Please select the Bill number";
					$('#val_rbn').text(a);
					event.preventDefault();
					event.returnValue = false;
					}
				else{
				var a="";
				$('#val_rbn').text(a);
				}
			}
	$.fn.validateworkorder = function(event) { 
					if($("#cmb_work_no").val()==""){ 
					var a="Please select the work order number";
					$('#val_work').text(a);
					event.preventDefault();
					event.returnValue = false;
					}
				else{
				var a="";
				$('#val_work').text(a);
				}
			}
	$("#top").submit(function(event){
           	$(this).validaterbnno(event);
			$(this).validateworkorder(event);
			
         });
	$("#cmb_work_no").change(function(event){
    	$(this).validateworkorder(event);
    });
    $("#cmb_rbn").change(function(event){
		$(this).validaterbnno(event);
	});*/
	
	

//});
</script>
<style>
.wtHolder{
	width:100% !important;
}
.handsontable .wtSpreader{
	width:100% !important;
}
.tboxsmclass{
	height:23px;
}
</style>
</body>
</html>

