<?php
////session_start();
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
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
	function workorderdetail()
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
        strURL = "find_worder_details.php?workorderno=" + document.form.cmb_shortname.value;
        xmlHttp.open('POST', strURL, true);
        xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlHttp.onreadystatechange = function ()
        {
            if (xmlHttp.readyState == 4)
            {
                data = xmlHttp.responseText
                if (data == "")
                {
                    alert("No Records Found");
                }
                else
                {
                   	var name = data.split("*");
					document.form.txt_workname.value 		= name[3];
					document.form.txt_workorder.value 		= name[5];
                }
            }
        }
		xmlHttp.send(strURL);
	}
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
            <div class="title">Escalation Configuration</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <form name="form" method="post" action="EscalationSettings.php">
                            <div class="container">
								<div class="row ">
									<div class="row clearrow"></div>
									<div class="div2">&nbsp;</div>
									<div class="div8">
										<div class="row"><div class="div12" style="margin-top:0px;"><div class="row divhead" align="center">Escalation Item Settings</div></div></div>
										<div class="row innerdiv">
											<div class="row">
												<div class="div4">
													<label for="fname">Work Short Name</label>
												</div>
												<div class="div8">
													<select id="cmb_shortname" name="cmb_shortname" class="tboxsmclass" onchange='workorderdetail();'>
														<option value="">--------------- Select --------------- </option>
														<?php echo $objBind->BindWorkOrderNo('');?>
													</select>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div4">
													<label for="fname">Work Order No.</label>
												</div>
												<div class="div8">
													<input type="text" name='txt_workorder' id='txt_workorder' class="tboxsmclass" readonly="" value="">
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div4">
													<label for="fname">Name of Work</label>
												</div>
												<div class="div8">
													<textarea name='txt_workname' id='txt_workname' class="tboxsmclass" readonly="" rows="2"></textarea>
												</div>
											</div>
											<div class="smediv">&nbsp;</div>
										</div>
										<div class="smediv">&nbsp;</div>
									</div>
									<div class="div2">&nbsp;</div>
								</div>
								
								   
								<div class="row">
									<div class="div12" align="center">
										<input type="submit" class="backbutton" name="next" id="next" value=" Next "/>
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
		$("#cmb_shortname").chosen();
		$(document).ready(function(){ 
			$("body").on("click","#next", function(event){
				var ShortName 	= $("#cmb_shortname").val();
				var WorkOrderNo = $("#txt_workorder").val();
				var WorkName 	= $("#txt_workname").val();
				if(ShortName == ""){
					BootstrapDialog.alert("Please select work short name");
					event.preventDefault();
					event.returnValue = false;
				}else if(WorkOrderNo == ""){
					BootstrapDialog.alert("Work order no. should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}else if(WorkName == ""){
					BootstrapDialog.alert("Name of work should not be empty");
					event.preventDefault();
					event.returnValue = false;
				}
			});
		});
	</script>
    </body>
</html>

