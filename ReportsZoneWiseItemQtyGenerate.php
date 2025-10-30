<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "library/common.php";
include "sysdate.php";
checkUser();
$msg = '';
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
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
if($_POST["submit"] == " View ") 
{
	$report_type 	= $_POST['rad_report_type'];
	$workno 		= $_POST['cmb_work_no'];
	header('Location: ReportsZoneItemQtyAllView.php?workno='.$workno.'&ReportType='.$report_type);
}

?>
<?php require_once "Header.html"; ?>
<script>
	function find_workname(){		
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
		strURL="find_workname.php?sheetid="+document.form.cmb_workshortname.value;
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
					document.form.txt_workname.value = '';
					document.form.txt_workorder_no.value = '';
				}
				else
				{	
					document.form.txt_workname.value		=	name[0].trim();
					document.form.txt_workorder_no.value	=	name[2].trim();
				}
			}
		}
		xmlHttp.send(strURL);	
	}
	
	function GetAllRAB(){
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
		strURL = "findrbn.php?workordernumber=" + document.form.cmb_workshortname.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function ()
		{
			if (xmlHttp.readyState == 4)
			{
				data = xmlHttp.responseText
				if (data != "")
				{	
					var name = data.split("*");
					$("#cmb_rbn").chosen("destroy");
					document.form.cmb_rbn.length = 0
					var optn = document.createElement("option")
					optn.value = 0;
					optn.text = "---- Select RAB ----";
					document.form.cmb_rbn.options.add(optn)
					var c = name.length;
					for (i = 0 ; i < c ; i++)
					{
						var optn = document.createElement("option")
						optn.value = name[i];
						optn.text = "RAB - "+name[i];
						document.form.cmb_rbn.options.add(optn)
					}
					$("#cmb_rbn").chosen();
				}
				else
				{
					$("#cmb_rbn").chosen("destroy");
					document.form.cmb_rbn.length = 0
					var optn = document.createElement("option")
					optn.value = 0;
					optn.text = "---- Select RAB ----";
					document.form.cmb_rbn.options.add(optn)
					$("#cmb_rbn").chosen();
				}
			}
		}
		xmlHttp.send(strURL);
	}

	function goBack(){
		url = "dashboard.php";
		window.location.replace(url);
	}

	window.history.forward();
	function noBack(){ window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
		<div class="content">
            <div class="title">Report - Zone / Building Wise Utilized Qty. </div>
			<div class="container_12">
				<div class="grid_12">
                    <blockquote class="bq1">
                        <form name="form" method="post" action="ReportsZoneWiseItemQty.php">
                            <div class="container">
								<div class="row">
									<div class="div12 grid-empty"></div>
									<div class="div12 grid-empty"></div>
									<div class="div2" align="center">&nbsp;</div>
									<div class="div8" align="center">
										<div class="innerdiv2">
											<div class="row divhead head-b" align="center">Zone / Building Wise Utilized Qty. Report Generate</div>
											<div class="row innerdiv group-div" align="center">
												<div class="div12 grid-empty"></div>
												<div class="div3 lboxlabel" align="left">Work Short Name</div>
												<div class="div9" align="left">
													<select name="cmb_workshortname" id="cmb_workshortname" onChange="find_workname(); GetAllRAB();" tabindex="1">
														<option value=""> ----------- Select Work Short Name ---------- </option>
														<?php echo $objBind->BindWorkOrderNo(0);//$objBind->BindWorkOrderNoSendAcc(0); ?>
													</select>
												</div>
												<div class="div12 grid-empty"></div>
												<div class="div3 lboxlabel" align="left">Work Order No.</div>
												<div class="div9">
													<input type="text" name='txt_workorder_no' id='txt_workorder_no' readonly="" class="divtbox">
												</div>
												<div class="div12 grid-empty"></div>
												<div class="div3 lboxlabel" align="left">Name of Work</div>
												<div class="div9">
													<textarea name='txt_workname' id='txt_workname' readonly="readonly"  required rows="3" class="divtarea"></textarea>
												</div>
												<div class="div12 grid-empty"></div>
												<div class="div3 lboxlabel" align="left">RAB</div>
												<div class="div9" align="left">
													<select name="cmb_rbn" id="cmb_rbn" tabindex="2" style="width:200px">
														<option value="">---- Select RAB ----</option>
													</select>
												</div>
												<div class="div12 grid-empty"></div>
												<div class="div3 lboxlabel" align="left">&nbsp;</div>
												<div class="div9" align="left">
													<input type="radio" name="rad_report_type" id="rad_only_this_rab" value="OTRAB">
												   <span class="lboxlabel">Only This RAB &emsp;&emsp;</span>
												   <input type="radio" name="rad_report_type" id="rad_upto_this_rab" value="UTRAB">
												   <span class="lboxlabel">Upto This RAB</span>
												</div>
												<div class="div12 grid-empty"></div>
											</div>
										</div>
									</div>
									<div class="div2" align="center">&nbsp;</div>
								</div>
							</div>
						   	<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
								<input type="submit" class="btn" data-type="submit" value=" View " name="submit" id="submit"   />
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
	$("#cmb_workshortname").chosen();
	$("#cmb_rbn").chosen();
    $(function() {
		$.fn.validatembooktype = function(event) {	
			if($("#cmb_mbook_type").val()==""){ 
				var a = "Please select the Measurement Type";
				BootatrapDialog.alert(a);
				event.preventDefault();
				event.returnValue = false;
			}
		}
		$.fn.validateworkorder = function(event) { 
			if($("#cmb_work_no").val()==""){ 
				var a = "Please select the work order number";
				BootatrapDialog.alert(a);
				event.preventDefault();
				event.returnValue = false;
			}
		}
		$.fn.validateworkorder = function(event) { 
			var SearchType = $('input[type=radio][name=rad_report_type]:checked').val();
			if(SearchType == ""){ 
				var a = "Please select RAB search type";
				BootatrapDialog.alert(a);
				event.preventDefault();
				event.returnValue = false;
			}
		}
		$("#top").submit(function(event){
			$(this).validatembooktype(event);
			$(this).validateworkorder(event);
         });
	});
</script>
    </body>
</html>

