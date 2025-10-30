<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
include "library/common.php";
$msg = '';
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
unset($_SESSION['UpSheetid']);
?>
<?php require_once "Header.html"; ?>
<script>
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
		strURL="find_workname.php?sheetid="+document.form.txt_workshortname.value;
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
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->

         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
            <div class="title">Supporting Documents (RAB/Final Bill) - Send Accounts</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <form name="form" method="post" action="AccountsSupportingDocument.php">
                            <div class="container">
								<div class="row">
									<div class="div12 grid-empty"></div>
									<div class="div12 grid-empty"></div>
									<div class="div2" align="center">&nbsp;</div>
									<div class="div8" align="center">
										<div class="innerdiv2">
											<div class="row divhead head-b" align="center">Send to Accounts - Supporting Documents</div>
											<div class="row innerdiv group-div" align="center">
												<div class="div12 grid-empty"></div>
												<div class="div3 lboxlabel" align="left">Work Short Name</div>
												<div class="div9">
													<select name="txt_workshortname" id="txt_workshortname" onChange="find_workname();" tabindex="1">
														<option value=""> ----------- Select Work Short Name ---------- </option>
														<?php echo $objBind->BindWorkOrderNoSendAcc(0);//$objBind->BindWorkOrderNoSendAcc(0); ?>
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
											</div>
										</div>
									</div>
									<div class="div2" align="center">&nbsp;</div>
								</div>
     						</div>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection" id="view_btn_section">
									<input type="submit" class="btn" data-type="submit" value=" View " name="view" id="view"/>
								</div>
								<div class="buttonsection" id="view_btn_section">
									<input type="submit" class="btn" data-type="submit" value=" Next " name="next" id="next"/>
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
		$(function() {
			$.fn.validateworkorder = function(event) { 
				if($("#txt_workshortname").val()==""){ 
					var a = "Please select work short name";
					BootstrapDialog.alert(a);
					event.preventDefault();
					event.returnValue = false;
				}else if($("#txt_workorder_no").val()==""){ 
					var a = "Work order no. should not be empty";
					BootstrapDialog.alert(a);
					event.preventDefault();
					event.returnValue = false;
				}else if($("#txt_workname").val()==""){ 
					var a = "Name of work should not be empty";
					BootstrapDialog.alert(a);
					event.preventDefault();
					event.returnValue = false;
				}else{
					var a="";
				}
			}
			$("#next").click(function(event){
				$(this).validateworkorder(event);
			});
			$("#view").click(function(event){
				$(this).validateworkorder(event);
			});
		});

		$("#txt_workshortname").chosen();
		var msg = "<?php echo $msg; ?>";
		var success = "<?php echo $success; ?>";
		var titletext = "";
		document.querySelector('#top').onload = function(){
			if(msg != ""){
				if(success == 1){
					swal("", msg, "success");
				}else{
					swal(msg, "", "");
				}
							
			}
		};
	</script>
    </body>
</html>

