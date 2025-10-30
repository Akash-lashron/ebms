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
if ($_POST["submit"] == ' View ') 
{
   	$sheetid 		= 	trim($_POST['cmb_shortname']);
	$esc_rbn 		= 	trim($_POST['txt_rbn']);
	//echo $quarter;exit;
	$_SESSION['escal_sheetid'] 		= $sheetid;
	$_SESSION['escal_rbn'] 			= $esc_rbn;
	$_SESSION['page'] 			    = 'CACL';
	header('Location: EscalationQtrWiseRabBreakUp.php');
} 
?>

<?php require_once "Header.html"; ?>
 <script>
  	function goBack()
	{
	    url = "dashboard.php";
		window.location.replace(url);
	}
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
        strURL = "find_escalation_bill.php?workorderno=" + document.form.cmb_shortname.value;
        xmlHttp.open('POST', strURL, true);
        xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlHttp.onreadystatechange = function ()
        {
            if (xmlHttp.readyState == 4)
            {
                data = xmlHttp.responseText
                if(data == "")
                {
                    alert("No Records Found");
                }
                else
                {
                    var name = data.split("*");
                    // for(i = 0; i < name.length; i++)
                    // {
						//document.form.txt_techsanction.value 	= name[0];
						//document.form.txt_agreemntno.value 		= name[2];
                        document.form.txt_workname.value 		= name[3];
						document.form.txt_workorder.value 		= name[5];
						document.form.txt_rbn.value 		    = name[11];
                    // }
                }
            }
        }
        xmlHttp.send(strURL);
    }
		
</script>
<script>
   $(function () {
		$.fn.validateworkname = function(event) { 
			if($("#txt_workname").val()==""){ 
			var a="Please Enter Work Name";
			$('#val_wname').text(a);
			event.preventDefault();
			event.returnValue = false;
			//return false;
			}
			else{
			var a="";
			$('#val_wname').text(a);
			}
		}
		$.fn.validateworkorder = function(event) { 
			if($("#txt_workorder").val()==""){ 
			var a="Please Enter Work Order Number";
			$('#val_workorder').text(a);
			event.preventDefault();
			event.returnValue = false;
			//return false;
			}
			else{
			var a="";
			$('#val_workorder').text(a);
			}
		}
		$.fn.validaterbn = function(event) { 
			if($("#txt_rbn").val()==""){ 
			var a="Please Enter RBN No.";
			$('#val_rbn').text(a);
			event.preventDefault();
			event.returnValue = false;
			//return false;
			}
			else{
			var a="";
			$('#val_rbn').text(a);
			}
		}
		/*$("#cmb_shortname").change(function(event){
		$(this).validateworkname(event);
		});*/
		$("#txt_workname").keyup(function(event){
		$(this).validateworkname(event);
		});
		$("#txt_workorder").keyup(function(event){
		$(this).validateworkorder(event);
		});
		$("#txt_rbn").keyup(function(event){
		$(this).validaterbn(event);
		});
		$("#top").submit(function(event){
		$(this).validateworkname(event);
		$(this).validateworkorder(event);
		$(this).validaterbn(event);
		//$(this).validatesecadv(event);
		//calculateEBamount();
		});		
        $("#currentmbookno").bind("change", function () {   

        });		
        
    });
	function goBack()
	{
		url = "dashboard.php";
		window.location.replace(url);
	}
</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--==============================header=================================-->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
    <?php include "Menu.php"; ?>
    <!--==============================Content=================================-->
    <div class="content">
        <div class="title">Escalation RA Bill Breakup Generate</div>
       	<div class="container_12">
           	<div class="grid_12">
			<!--<div align="right"><a href="View_Electricity_generate_Bill.php">View</a>&nbsp;&nbsp;&nbsp;</div>-->
                 <blockquote class="bq1" style="overflow:auto">
						<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
						<input type="hidden" name="hid_staffid" id="hid_staffid" value="<?php echo $staffid; ?>">
                        	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="22%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Short Name</td> 
                                    <td>
										<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" style="width:465px" onChange="workorderdetail();">
										<!--<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" style="width:465px" onChange="workorderdetail();">-->
											<option value="">--------------- Select ---------------</option>
											<?php echo $objBind->BindWorkOrderNo(0);?>
										</select>
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_shortname" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Name of Work</td>
                                    <td><textarea name='txt_workname' id='txt_workname' class="textboxdisplay" rows="6" style="width: 465px;"><?php if($_GET['sheet_id'] != ''){ echo $work_name; } ?></textarea></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_wname" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order No.</td>
                                    <td><input type="text" name='txt_workorder' id='txt_workorder' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_workorder" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">RAB</td>
                                    <td class="label">
									<input type="text" name='txt_rbn' id='txt_rbn' class="textboxdisplay" value="" style="width: 200px;">
									&emsp;&nbsp;&nbsp;										
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_rbn" style="color:red" colspan="">&nbsp;</td></tr>
							
							</table>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<div class="buttonsection">
									<input type="submit" name="submit" id="submit" value=" View "/>
								</div>
							</div>
                  </blockquote>
              </div>
        </div>
	</div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
		   <script>
		   		$("#cmb_shortname").chosen();
				var msg = "<?php echo $msg; ?>";
				var success = "<?php echo $success; ?>";
				var titletext = "";
				document.querySelector('#top').onload = function(){
				if(msg != "")
				{
					if(success == 1)
					{
						swal("", msg, "success");
					}
					else
					{
						swal(msg, "", "");
					}
				}
				};
			</script>
        </form>
    </body>
</html>
