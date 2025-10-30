<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/common.php';
checkUser();
$msg = '';
$userid = $_SESSION['userid'];
$staffid = $_SESSION['sid'];
function dt_format($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);

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
    return $dd . '-' . $mm . '-' . $yy;
}
?>

<?php require_once "Header.html"; ?>
<style>
    
</style>
<script>
	function goBack(){
		url = "MyView.php";
		window.location.replace(url);
	}
	
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
		strURL="find_workname.php?sheetid="+document.form.cmb_shortname.value;
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
					document.form.workname.value			=	name[0].trim();
					document.form.txt_workorder_no.value	=	name[2].trim();
					//document.form.txt_book_no1.value		=	Number(name[1]) + Number(1);
					//document.form.txt_book_no.value			=	Number(name[1]) + Number(1);
					//document.form.txt_bookpage_no1.value	=	Number(name[2]) + Number(1);
					//document.form.txt_bookpage_no.value		=	Number(name[2]) + Number(1);
					//document.form.txt_rab_no1.value			=	Number(name[3]) + Number(1);
					//document.form.txt_rab_no.value			=	Number(name[3]) + Number(1);
	
				}
			}
		}
		xmlHttp.send(strURL);	
	}
	function FindAbstractMBno()
    { //alert("x")
		var xmlHttp;
        var data;
		var mtype = "A"; 
        if (window.XMLHttpRequest) // For Mozilla, Safari, ...
        {
        	xmlHttp = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) // For Internet Explorer
        {
        	xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        strURL = "find_generatembno.php?sheetid=" + document.form.cmb_shortname.value + "&mtype=" + mtype;
        xmlHttp.open('POST', strURL, true);
        xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlHttp.onreadystatechange = function ()
        {

        	if(xmlHttp.readyState == 4)
            {
            	data = xmlHttp.responseText;
				$("#cmb_mbook_no").chosen('destroy');
                if (data == "")
                {
                    document.form.cmb_mbook_no.length = 0;
                    var optn = document.createElement("option");
                    optn.value = '';
                    optn.text = "--- Select ---";
                    document.form.cmb_mbook_no.options.add(optn);
                }
                else
                { 
                	var name = data.split("*");
                    document.form.cmb_mbook_no.length = 0;
                    var optn = document.createElement("option");
                    optn.value = '';
                    optn.text = "--- Select ---";
                    document.form.cmb_mbook_no.options.add(optn);
                    var c = name.length;
                    var a = c / 2;
                    var b = a + 1;
                    for (i = 1, j = b; i < a, j < c; i++, j++)
                    {
                    	var optn = document.createElement("option")
                        optn.value = name[i];
                        // optn.value = name[j];
                        optn.text = name[j];
                        document.form.cmb_mbook_no.options.add(optn)  
                    }
                }
				$("#cmb_mbook_no").chosen();
        	}
       	}
        xmlHttp.send(strURL);
	}
	function getrbn()
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
        strURL = "findabstract_mbookno.php?sheetid=" + document.form.cmb_shortname.value;
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
                    for(i = 0; i < name.length; i++)
                    {
                    	document.form.txt_rbn.value = name[3];
                    }

                }
            }
        }
     	xmlHttp.send(strURL);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
	.DisableInput{
		background-color:#E0E0E0;
		pointer-events:none;
	}
</style>

    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="SecuredAdvance.php" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="title">Secured Advance</div>
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1" style="overflow:auto">
							<div class="box-container box-container-lg">
								<div class="row clearrow"></div>
								<div class="div2">&nbsp;</div>
								<div class="div8">
									<div class="card cabox">
										<div class="face-static">
											<div class="card-header inkblue-card" align="left">&nbsp;Secured Advance With Measurements / Zero Measurements <span id="CourseChartDuration"></span></div>
											<div class="card-body padding-1 ChartCard" id="CourseChart">
												<div class="divrowbox pt-2">
													<div class="row clearrow"></div>
													<div class="row clearrow"></div>
													<div class="div4 pd-lr-1">
														<div class="lboxlabel">Work Short Name</div>
													</div>
													<div class="div8 pd-lr-1">
														<div>
															<select name="cmb_shortname" id="cmb_shortname" class="tboxsmclass" onChange="find_workname();">
																<option value="">-------- Select ---------</option>
																<?php echo $objBind->BindWorkOrderNo($sid);?>
															</select>
														</div>
													</div>
													<div class="row clearrow"></div>
													
													
													<div class="div4 pd-lr-1 label">
														<div class="lboxlabel">Work Order No.</div>
													</div>
													<div class="div8 pd-lr-1 label">
														<input type="text" name="txt_workorder_no" id="txt_workorder_no" class="tboxsmclass" disabled="disabled">
													</div>
													<div class="row clearrow"></div>
													
													
													<div class="div4 pd-lr-1 label">
														<div class="lboxlabel">Name of the Work</div>
													</div>
													<div class="div8 pd-lr-1 label">
														<textarea name="workname" id="workname" class="tboxsmclass" rows="3" disabled="disabled"></textarea>
													</div>
													<!--<div class="row clearrow"></div>
													
													
													<div class="div4 pd-lr-1 label">
														<div class="label">&nbsp;</div>
													</div>
													<div class="div8 pd-lr-1 label">
														<input type="radio" name="secadv_type" id="with_meas" value="WM" > With Measurements 
														&nbsp;&nbsp;
														<input type="radio" name="secadv_type" id="zero_meas" value="ZM" > Zero Measurements 
													</div>-->
													<div class="row clearrow"></div>
													<div class="div4 pd-lr-1 label">
														<div class="lboxlabel">RAB</div>
													</div>
													<div class="div3 pd-lr-1 label">
														<input type="text" name='txt_rbn' id='txt_rbn' class="tboxsmclass" readonly="" value="">
													</div>
													<div class="div5 pd-lr-1 label">
														<div class="label">&nbsp;</div>
													</div>
													<div class="row clearrow"></div>
													
													<div class="div4 pd-lr-1 hide zm">
														<div class="lboxlabel">Abstract MBook No.</div>
													</div>
													<div class="div3 pd-lr-1 hide zm">
														<div>
															<select name="cmb_mbook_no" id="cmb_mbook_no" class="tboxsmclass">
																<option value="">--- Select ---</option>
															</select>
														</div>
													</div>
													<div class="div5 pd-lr-1 label hide zm">
														<div class="label">&nbsp;</div>
													</div>
													
													<div class="row clearrow hide zm"></div>
													<div class="div4 pd-lr-1 label hide zm">
														<div class="lboxlabel">Abstract MBook Page No.</div>
													</div>
													<div class="div3 pd-lr-1 label hide zm">
														<input type="text" name='txt_mbook_page_no' id='txt_mbook_page_no' class="tboxsmclass" readonly="" value="">
														<input type="hidden" name='txt_mbookno' id='txt_mbookno' class="tboxsmclass" readonly="" value="">
													</div>
													<div class="div5 pd-lr-1 label hide zm">
														<div class="label">&nbsp;</div>
													</div>
													<div class="row clearrow hide zm"></div>
													<div class="div12 pd-lr-1" align="center">
														<input type="submit" class="btn btn-info" name="btn_go" id="btn_go" value=" NEXT ">
														<input type="hidden" name="hid_staffid" id="hid_staffid" value="<?php echo $staffid; ?>">
													</div>
													<div class="row clearrow"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="div2">&nbsp;</div>
							</div>
					    </blockquote>
                    </div>
                </div>
            </div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
		   <script>
		   		$("#cmb_shortname").chosen();
				$("#cmb_mbook_no").chosen();
				var Zm = 0; var SaErr = 0;
				$(function(){
					var KillEvent = 0;
					$('#btn_go').on('click', function(event){ 
						if(KillEvent == 0){
							var WorkShortName = $("#cmb_shortname").val();
							var WorkOrderNo = $("#txt_workorder_no").val();
							var WorkName = $("#workname").val();
							var RabNo = $("#txt_rbn").val();
							var MbErr = 0; var MbPgErr = 0;
							if(Zm == 1){
								if($("#cmb_mbook_no").val() == ""){
									MbErr = 1;
								}
								if($("#txt_mbook_page_no").val() == ""){
									MbPgErr = 1;
								}
							}
							if(WorkShortName == ""){
								BootstrapDialog.alert("Please select work short name");
								event.preventDefault();
								event.returnValue = false;
							}else if(WorkOrderNo == ""){
								BootstrapDialog.alert("Work order number should not be empty");
								event.preventDefault();
								event.returnValue = false;
							}else if(WorkName == ""){
								BootstrapDialog.alert("Work name should not be empty");
								event.preventDefault();
								event.returnValue = false;
							}else if(RabNo == ""){
								BootstrapDialog.alert("Please enter RAB number");
								event.preventDefault();
								event.returnValue = false;
							}else if(SaErr == 1){
								BootstrapDialog.alert("Access denied ! Secured advance option not enabled for this RAB in RAB Create module");
								event.preventDefault();
								event.returnValue = false;
							}else if(MbErr == 1){
								BootstrapDialog.alert("Please select MBook no.");
								event.preventDefault();
								event.returnValue = false;
							}else if(MbPgErr == 1){
								BootstrapDialog.alert("MBook page no. should not be empty");
								event.preventDefault();
								event.returnValue = false;
							}else{
								event.preventDefault();
								BootstrapDialog.confirm({
									title: 'Confirmation Message',
									message: 'Are you sure want to generate Secured Advance ?',
									closable: false, // <-- Default value is false
									draggable: false, // <-- Default value is false
									btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
									btnOKLabel: 'Ok', // <-- Default value is 'OK',
									callback: function(result) {
										// result will be true if button was click, while it will be false if users close the dialog directly.
										if(result){
											KillEvent = 1;
											$("#btn_go").trigger( "click" );
										}else {
											//alert('Nope.');
											KillEvent = 0;
										}
									}
								});
							}
						}
					});
					
					function DisplayPageDetails() {
						$("#txt_mbook_page_no").val('');
						$("#txt_mbookno").val('');
						var currentmbooknovalue 	= 	$("#cmb_mbook_no option:selected").attr('value');//alert(currentmbooknovalue);
						var currentmbooknotext 		= 	$("#cmb_mbook_no option:selected").text();
						var wordordernovalue 		= 	$("#cmb_shortname option:selected").attr('value');
						var staffid					=	$("#hid_staffid").val();
						var currentrbn				=	$("#txt_rbn").val();
						var generatetype 			= 	"cw";
						$.post("MBookNoService.php", {currentmbook: currentmbooknovalue, currentbmookname: currentmbooknotext, sheetid: wordordernovalue, generatetype: generatetype, staffid: staffid, currentrbn: currentrbn}, function (data) { //alert(data);
							//$("#bookpageno1").val(Number(data) + 1);$("#bookpageno").val(Number(data) + 1);
							if(currentmbooknovalue != 0){
								$("#txt_mbook_page_no").val(data);
								$("#txt_mbookno").val(currentmbooknotext);
							}
						});
					}
					$("#with_meas").click(function(){
						$("#txt_rbn").val("");
						$("#txt_rbn").addClass("DisableInput");
						$("#txt_rbn").attr("readonly", true);
						$(".zm").addClass("hide");
						getrbn();
						
					});
					$("#zero_meas").click(function(){
						$("#txt_rbn").val("");
						$("#txt_rbn").removeClass("DisableInput");
						$("#txt_rbn").attr("readonly", false); 
						$("#cmb_mbook_no").chosen('destroy');
						$(".zm").removeClass("hide");
						$("#cmb_mbook_no").chosen();
					});
					$("#txt_rbn").bind("change", function () {   
						FindAbstractMBno();
					});
					$("#cmb_mbook_no").bind("change", function () {   
						DisplayPageDetails();
					});
					
					$("#cmb_shortname").bind("change", function(){ 
						Zm = 0; SaErr = 0;
						var WorkId = $(this).val();
						$(".zm").addClass("hide");
						$("#txt_rbn").val('')
						$.ajax({
							type: 'POST', 
							url: 'FindBillForwardToAcc.php', 
							data: { WorkId: WorkId }, 
							dataType: 'json',
							success: function (data) { //alert(data);
								if(data != null){
									if((data['rbn'] != null)&&(data['rbn'] != '')){
										$("#txt_rbn").val(data['rbn']);
										if((data['is_rab'] != "Y")&&(data['is_sec_adv'] == "Y")){ 
											$("#cmb_mbook_no").chosen('destroy');
											$(".zm").removeClass("hide");
											$("#cmb_mbook_no").chosen();
											FindAbstractMBno();
											Zm = 1;
										}
										if(data['is_sec_adv'] != "Y"){ 
											SaErr = 1;
										}
									}
								}
							}
						});
					});
					
				});
				
			</script>
        </form>
    </body>
</html>
