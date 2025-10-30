<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
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
if($_POST["submit"] == " Update ") 
{
	$sheet_id = $_POST['cmb_work_no'];
	$pageno   = $_POST['txt_page_no'];
	$mbno     = $_POST['cmb_MBook_no'];
	
	$insert_mb_page_no_query	= 	"update mbookallotment set mbpage='$pageno', active = 1 where mbno = '$mbno'";
	$insert_mb_page_no_sql 	    = 	mysql_query($insert_mb_page_no_query);
	if($insert_mb_page_no_sql == true) 
	{
        $msg = " Page No updated Successfully ...!!! ";
		$success = 1;
    }
	else
	{
		$msg = " Something Error...!!! ";
	}
}
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
					alert("No Records Found");
					document.form.workname.value='';	
				}
				else
				{	
					document.form.workname.value			=	name[0].trim();
					document.form.txt_workorder_no.value	=	name[2].trim();
					document.form.txt_book_no1.value		=	Number(name[1]) + Number(1);
					document.form.txt_book_no.value			=	Number(name[1]) + Number(1);
					document.form.txt_bookpage_no1.value	=	Number(name[2]) + Number(1);
					document.form.txt_bookpage_no.value		=	Number(name[2]) + Number(1);
					document.form.txt_rab_no1.value			=	Number(name[3]) + Number(1);
					document.form.txt_rab_no.value			=	Number(name[3]) + Number(1);
	
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
	function MBookDetail()
	{ 
    	var xmlHttp;
        var data;
        var i, j;
		$("#cmb_MBook_no").chosen("destroy");
        document.form.cmb_MBook_no.length = 0;
		document.form.cmb_MBook_no.value = "";
		
        var optn1 = document.createElement("option");
            optn1.value = 0;
            optn1.text = " --- Select --- ";
        document.form.cmb_MBook_no.options.add(optn1);
		
        if (window.XMLHttpRequest) // For Mozilla, Safari, ...
        {
        	xmlHttp = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) // For Internet Explorer
        {
           	xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        strURL = "find_Mbook_no.php?workorderno=" + document.form.cmb_work_no.value+"&temp=2";
        xmlHttp.open('POST', strURL, true);
        xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlHttp.onreadystatechange = function ()
        {
        	if(xmlHttp.readyState == 4)
            {
            	data = xmlHttp.responseText
				//alert(data)
                if (data == "")
                {
                	alert("No Records Found");
                }
                else
                {
                    var name = data.split("*");
						//alert(name)
					
                    for(i = 0; i < name.length; i++)
                   	{
						var optn 	= 	document.createElement("option")
                        optn.value 	= 	name[i];
                        optn.text 	= 	name[i];
                        document.form.cmb_MBook_no.options.add(optn)
						//document.form.txt_rbn.value	= name[i];
							
                    }
					$("#cmb_MBook_no").chosen();
                }
            }
        }
        xmlHttp.send(strURL);
    }
</script>
<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</SCRIPT>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->

         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
          <div class="title">MBook Page Update</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="container"><br/>
							<table width="100%"  bgcolor="#E8E8E8" border="0" cellpadding="0" cellspacing="0" align="center" >
								<tr><td width="22%">&nbsp;</td></tr>
							   	<tr>
									<td>&nbsp;</td> 
								  	<td  class="label">Work Short Name</td>
								  	<td  class="labeldisplay">
									<select name="cmb_work_no" id="cmb_work_no" onChange="find_workname();" class="textboxdisplay" style="width:400px;height:22px;" tabindex="7">
										 <option value="">---------------------- Select ----------------------</option>
										 <?php echo $objBind->BindWorkOrderNoListAccounts(0); ?>
										 <?php //echo $objBind->BindWorkOrderNo(0); ?>
									 </select>
								  	</td>
								  	<td>&nbsp;</td>
								  	<td>&nbsp;</td>
							   	</tr>
							   	<tr><td>&nbsp;</td><td></td><td id="val_work" style="color:red"></td></tr>
							   	<tr>
								  	<td>&nbsp;</td>
								  	<td  class="label">Work Order No.</td>
								  	<td  class="labeldisplay">
								        <input type="text" name="txt_workorder_no" id="txt_workorder_no" class="textboxdisplay" style="width:397px;" disabled="disabled">
								  	</td>
								  	<td>&nbsp;</td>
								  	<td>&nbsp;</td>
							   </tr>
							   <tr><td>&nbsp;</td><td></td><td id="val_workorder" style="color:red"></td></tr>			
							   <tr>
								  	<td>&nbsp;</td>
								  	<td  class="label">Name of the Work </td>
								  	<td  class="labeldisplay">
								       <textarea name="workname" class="textboxdisplay txtarea_style" style="width: 400px;" rows="5" disabled="disabled"></textarea>
								  	</td>
								  	<td>&nbsp;</td>
								  	<td>&nbsp;</td>
							   </tr>
							   <tr><td>&nbsp;</td><td></td><td id="val_work" style="color:red"></td></tr>
							   <tr>
								  	<td>&nbsp;</td>
								  	<td class="label">&nbsp;</td>
								  	<td class="labeldisplay">
										<span class="label">MB No</span>&nbsp;&nbsp;
										<select name="cmb_MBook_no" id="cmb_MBook_no" class="textboxdisplay" style="width:160px;">
										   <option value=""> ----- Select ----- </option>
										</select>
										&emsp;&nbsp;
										<span class="label">MB Page No</span>&nbsp;&nbsp;
										<input type="text" name="txt_page_no" id="txt_page_no" class="textboxdisplay" style="width:50px;"/>
										&emsp;&emsp;
								  	</td>
								  	<td>&nbsp;</td>
								  	<td>&nbsp;</td>
							   	</tr>
								<tr><td>&nbsp;</td><td></td><td class="labeldisplay" style="color:#797979; font-size:12px">* Please enter previous page number of your starting page no.</td></tr>
							    <tr><td>&nbsp;</td><td></td><td id="val_page" style="color:red; text-align:center;"></td></tr>
							 </table>
                        </div>
						<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
							<div class="buttonsection">
							   <input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />
							</div>
							<div class="buttonsection" id="view_btn_section">
							  <input type="submit" class="btn" data-type="submit" value=" Update " name="submit" id="submit"/>
							</div>
						</div>
                        </form>
                    </blockquote>
                </div>
            </div>
        </div>
         <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
<script>
$("#cmb_work_no").chosen();
$("#cmb_MBook_no").chosen();
$(function() {
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
	$.fn.validateRAB = function(event) { 
		if($("#txt_page_no").val()==""){ 
			var a="Please Enter your Page Number";
			$('#val_page').text(a);
			event.preventDefault();
			event.returnValue = false;
		}
		else{
			var a="";
			$('#val_page').text(a);
		}
	}
	$("#top").submit(function(event){
		$(this).validateworkorder(event);
		$(this).validateRAB(event);
	});
	$("#cmb_work_no").change(function(event){
    	$(this).validateworkorder(event);
    });
	$("#txt_page_no").keyup(function(event){
    	$(this).validateRAB(event);
    });
	$("#cmb_work_no").change(function(event){
		//$("#cmb_MBook_no").chosen('destroy');
    	MBookDetail();
		//$("#cmb_MBook_no").chosen();
    });
});
</script>
<script>
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
    </body>
</html>

