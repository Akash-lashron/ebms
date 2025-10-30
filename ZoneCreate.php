<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/common.php';
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
if(isset($_POST["submit"])){
   	$sheetid 			= 	trim($_POST['cmb_shortname']);
	$zone_name 			= 	trim($_POST['txt_zone_name']);
	$zone_name_query = "insert into zone set zone_name = '$zone_name', sheetid = '$sheetid', staffid = '$staffid', userid = '$userid', active = '1', modifieddate = NOW()";
	$zone_name_sql   = mysql_query($zone_name_query);
    if($zone_name_sql == true){
        $msg = "Zone Name Saved Successfully ";
		$success = 1;
    }else{
		$msg = "Zone Name Not Saved...!!! ";
	}
} 
if(isset($_POST["update"])){
   	$sheetid 			= 	trim($_POST['cmb_shortname']);
	$zone_name 			= 	trim($_POST['txt_zone_name']);
	$zoneid 			= 	trim($_POST['txt_zoneid']);
	$zone_name_query = "update zone set zone_name = '$zone_name', sheetid = '$sheetid', staffid = '$staffid', userid = '$userid', active = '1', modifieddate = NOW() where zone_id = '$zoneid' and sheetid = '$sheetid'";
	$zone_name_sql   = mysql_query($zone_name_query);
    if($zone_name_sql == true){
        $msg = "Zone Name Updated Successfully ";
		$success = 1;
    }else{
		$msg = "Zone Name Not Updated...!!! ";
	}
} 
if(isset($_GET["zoneid"])){
	$zoneid = $_GET["zoneid"];
	$SelectQuery = "Select * from zone where zone_id = '$zoneid'";
	$SelectSql = mysql_query($SelectQuery);
	if($SelectSql == true){
		if(mysql_num_rows($SelectSql)>0){
			$List = mysql_fetch_object($SelectSql);
			$ZoneName = $List->zone_name;
			$sheetid = $List->sheetid;
			$SelectSheetQuery = "select * from sheet where sheet_id = '$sheetid'";
			$SelectSheetSql = mysql_query($SelectSheetQuery);
			if($SelectSheetSql == true){
				if(mysql_num_rows($SelectSheetSql)>0){
					$SheetList = mysql_fetch_object($SelectSheetSql);
					$WorkName = $SheetList->work_name;
					$WorkOrderNo = $SheetList->work_order_no;
				}
			}
		}
	}
}
?>

  <?php require_once "Header.html"; ?>
<style>
    
</style>
 <script>strURL = "findabstract_mbookno.php?sheetid=" + document.form.cmb_work_no.value;
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
                        for(i = 0; i < name.length; i++)
                        {
							//document.form.txt_techsanction.value 	= name[0];
							//document.form.txt_agreemntno.value 		= name[2];
                            document.form.txt_workname.value 		= name[3];
							document.form.txt_workorder.value 		= name[5];
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
</script>
<script>
   $(function () {
        $.fn.validateshortname = function(event) { 
					if($("#cmb_shortname").val()==""){ 
					var a="Please Select Work Short Name";
					$('#val_shortname').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_shortname').text(a);
					}
				}
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
		$.fn.validatezonename = function(event) { 
					if($("#txt_zone_name").val()==""){ 
					var a="Please Enter Zone Name";
					$('#val_zone_name').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_zone_name').text(a);
					}
				}
				
		$("#cmb_shortname").change(function(event){
		$(this).validateshortname(event);
		});
		$("#txt_workname").keyup(function(event){
		$(this).validateworkname(event);
		});
		$("#txt_workorder").keyup(function(event){
		$(this).validateworkorder(event);
		});
		$("#txt_zone_name").keyup(function(event){
		$(this).validatezonename(event);
		});
		$("#top").submit(function(event){
		$(this).validateshortname(event);
		$(this).validateworkname(event);
		$(this).validateworkorder(event);
		$(this).validatezonename(event);
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
                <div class="title">Zone - Creation</div>
                <div class="container_12">
                    <div class="grid_12">

						<!--<div align="right"><a href="View_Electricity_generate_Bill.php">View</a>&nbsp;&nbsp;&nbsp;</div>-->
                        <blockquote class="bq1">
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
                        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="23%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Short Name</td> 
                                    <td class="labeldisplay">
										<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" style="width:465px" onChange="workorderdetail();getrbn();recovery();">
											<option value="">----------------- Select Work Short Name -----------------</option>
											<?php echo $objBind->BindWorkOrderNo($zoneid);?>
										</select>
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_shortname" style="color:red" colspan="">&nbsp;</td></tr>
                                
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Name of Work</td>
                                    <td><textarea name='txt_workname' id='txt_workname' class="textboxdisplay" rows="6" style="width: 465px;"><?php if($_GET['zoneid'] != ''){ echo $WorkName; } ?></textarea></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_wname" style="color:red" colspan="">&nbsp;</td></tr>
                                
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order No.</td>
                                    <td><input type="text" name='txt_workorder' id='txt_workorder' class="textboxdisplay" value="<?php if($_GET['zoneid'] != ''){ echo $WorkOrderNo; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_workorder" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Zone Name</td>
                                    <td><input type="text" name='txt_zone_name' id='txt_zone_name' class="textboxdisplay" value="<?php if($_GET['zoneid'] != ''){ echo $ZoneName; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_zone_name" style="color:red" colspan="">&nbsp;</td></tr>
							
							</table>
									<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
										<div class="buttonsection">
										<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
										</div>
										<div class="buttonsection">
										<?php if($_GET['zoneid'] != ''){ ?>
											<input type="submit" name="update" id="update" value=" Update "/>
										<?php }else{ ?>
											<input type="submit" name="submit" id="submit" value=" Submit "/>
										<?php } ?>
										<input type="hidden" name="txt_zoneid" id="txt_zoneid" value="<?php if($_GET['zoneid'] != ''){ echo $zoneid; } ?>">
										<input type="hidden" name="txt_sheetid" id="txt_sheetid" value="<?php if($_GET['zoneid'] != ''){ echo $sheetid; } ?>">
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
