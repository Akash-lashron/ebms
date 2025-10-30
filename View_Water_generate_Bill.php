<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'ExcelReader/excel_reader2.php';
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
$popupwindow =0;
if(isset($_POST["submit"])) {
//$cmb_work_no = $_POST['cmb_work_no'];
// $cmb_work_no;
//echo "hai";
//echo $_SESSION['RBN']=$_POST['cmb_rbn'];
$_SESSION['Sheetid']= $_POST['cmb_work_no'];

header('Location: Print_WaterRecoveryBill.php'); 
}
/*$rbn_no=trim($_POST['cmb_rbn']);
$mbookdetails="select sheet_id,mb_id from mbookgenerate  where rbn='$rbn_no'";
$sql_mbook=mysql_query($mbookdetails);

if($sql_mbook == true) {
$List = mysql_fetch_object($sql_mbook);
$sheet_id=$List->sheet_id;
$mb_id=$List->mb_id; }

if($sheet_id!='' && $mb_id!='') { header('Location: MBook.php?page=0&sheetid='.$sheet_id.'&id='.$mb_id); }
}
*/

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
            strURL = "findrbn.php?workordernumber=" + document.form.cmb_work_no.value;
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
                        document.form.cmb_rbn.value = 'Select';
                    }
                    else
                    {	
                        var name = data.split("*");
                        document.form.cmb_rbn.length = 0
                        var optn = document.createElement("option")
                        optn.value = 0;
                        optn.text = "--------------RBN.-------------";
                        document.form.cmb_rbn.options.add(optn)

                        var c = name.length
                        var a = c / 2;
                        var b = a + 1;
                        for (i = 1, j = b; i < a, j < c; i++, j++)
                        {
                            var optn = document.createElement("option")
                            optn.value = name[i];
							optn.text = "RAB"+name[j];
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
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <div class="title">Water Bill - View</div>
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                       
                            <div class="container">

                             <table width="1078px"  bgcolor="#E8E8E8" border="0" cellpadding="0" cellspacing="0" align="center" >
                                <tr>
									<td width="19%">&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td> 
									<td  class="label">Work Short Name </td>
									<td  class="labeldisplay"><?php 
											  $sql_itemno="select sheet_id ,short_name from sheet WHERE active =1"; 
											  $rs_itemno=mysql_query($sql_itemno);
										   ?>
									   <select name="cmb_work_no" id="cmb_work_no" onChange="find_workname()" class="textboxdisplay" style="width:470px;height:22px;" tabindex="7">
										 <option value="">--------------------------------Select Work Order--------------------------------</option>
										 <?php while($rows=mysql_fetch_assoc($rs_itemno)){ ?>
										 <option value="<?php echo $rows['sheet_id']; ?>"><?php echo $rows['short_name']; ?></option>
										 <?php } ?>
									   </select>
									</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td></td>
									<td id="val_work" style="color:red" colspan="3"></td>
								</tr>
								<tr>
								   	<td>&nbsp;</td>
								   	<td  class="label">Work Order No. </td>
								   	<td  class="labeldisplay"><input type="text" name="txt_workorder_no" id="txt_workorder_no" class="textboxdisplay" style="width: 465px;"></td>
								   	<td>&nbsp;</td>
								   	<td>&nbsp;</td>
								</tr>
                                <tr>
									<td>&nbsp;</td>
									<td></td>
									<td id="val_workorder" style="color:red" colspan="3"></td>
								</tr>
								<tr>
								   	<td>&nbsp;</td>
								   	<td  class="label">Name of the Work </td>
								   	<td  class="labeldisplay"><textarea name="workname" rows="6" class="textboxdisplay" style="width: 465px;"></textarea></td>
								   	<td>&nbsp;</td>
								   	<td>&nbsp;</td>
								</tr>
                                <tr>
									<td>&nbsp;</td>
									<td></td>
									<td id="val_work" style="color:red" colspan="3"></td>
								</tr>
                                   
								<tr>
									<td>&nbsp;&nbsp;</td>
									<td width="" class="label"></td>
									<td id="val_rbn" style="color:red" colspan="3"></td>
								</tr>
                                <tr>
                                    <td colspan="5">
                                    <center>
                                        <input type="hidden" class="text" name="submit" value="true" />
										<input  type="hidden" class="text" name="runningbilltext" id="runningbilltext" value=""/>
                                        <!--<input type="image" src="Buttons/View_Normal.png" onmouseover="this.src='Buttons/View_Over.png';" onmouseout="this.src='Buttons/View_Normal.png';" class="btn" data-type="submit" value="View" name="submit" id="submit"   />-->
                                        <!--<input type="submit" data-type="submit" value=" View " name="submit" id="submit"/>&nbsp;&nbsp;&nbsp;
										<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>-->
                                    </center>	    
									</td>
                               </tr>
                               <tr>
							   		<td colspan="5"></td>
							   </tr>

                             </table>
                            </div>
								<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
										<div class="buttonsection">
										<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
										</div>
										<div class="buttonsection">
										<input type="submit" data-type="submit" value=" View " name="submit" id="submit"/>
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
    $(function() {
	$.fn.validaterbnno = function(event) {	
				if($("#cmb_rbn").val()==0){ 
					var a="Please select the Bill number";
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
	$.fn.validateworkorder = function(event) { 
					if($("#cmb_work_no").val()==""){ 
					var a="Please select the work order number";
					$('#val_work').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
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
         });
			
	 });
</script>
    </body>
</html>

