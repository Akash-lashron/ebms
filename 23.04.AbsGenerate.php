<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
checkUser();
require_once 'ExcelReader/excel_reader2.php';
$msg='';
function dt_format($ddmmyyyy)
{
 $dt=explode('/',$ddmmyyyy);
 $dd=$dt[0];
 $mm=$dt[1];
 $yy=$dt[2];
 return $yy . '-' . $mm . '-' . $dd;
 }
function dt_display($ddmmyyyy)
{
 $dt=explode('-',$ddmmyyyy);
 $dd=$dt[2];
 $mm=$dt[1];
 $yy=$dt[0];
 return $dd . '/' . $mm . '/' . $yy;
}
if($_POST["xcel"] == "Excel Format") { header('Location: excel/AbstMBookExcel.php?sheetid=1&id=1'); }
if($_POST["html"] == "Generate") { 
    
        $staffid =$_SESSION['userid'];
        $sheetid = trim($_POST['cmb_work_no']);
        $mb_date = dt_format($_POST['txt_date']);
        $fromdate = dt_format($_POST['txt_fromdate']);
        $todate = dt_format($_POST['txt_todate']);
        $mb_no = trim($_POST['currentmbook']);
        $mb_page = trim($_POST['bookpageno']);
        $rbn = trim($_POST['rbnno']);
    $_SESSION["abstsheetid"] = $sheetid; $_SESSION["abstmbno"] = $mb_no;      $_SESSION["abstmbpage"] = $mb_page;
    $_SESSION['fromdate'] = $fromdate; $_SESSION['todate'] = $fromdate;
    header('Location: AbstMBook.php'); }
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
				document.form.workname.value=name[0].trim();
                                document.form.txt_book_no1.value=Number(name[1]) + Number(1);
				document.form.txt_book_no.value=Number(name[1]) + Number(1);
                                document.form.txt_bookpage_no1.value=Number(name[2]) + Number(1);
				document.form.txt_bookpage_no.value=Number(name[2]) + Number(1);
                                document.form.txt_rab_no1.value=Number(name[3]) + Number(1);
				document.form.txt_rab_no.value=Number(name[3]) + Number(1);

                        }
		}
    }
	xmlHttp.send(strURL);	
}
function func_abshead_date()
            {
            
            var xmlHttp;
            var data;
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "find_absheader_date.php?sheetid=" + document.form.cmb_work_no.value;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText;
                    if (data == "")
                    {
                        alert("No Records Found");
                        document.form.itemno.value = 'Select';
                    }
                    else
                    {
                        var absheaddate = data.split("*");
                        document.form.txt_fromdate.value = absheaddate[0];
                        document.form.txt_todate.value = absheaddate[1];
                    }
                }
            }
            xmlHttp.send(strURL);
        }
 </script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
 <script>
            $(function () {
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
				
				$.fn.validatembookno = function(event) { 
                                    var currentmbooknovalue = $("#currentmbookno option:selected").attr('value');
					if(currentmbooknovalue=="0"){ 
					var a="Please select the Mbook number";
					$('#val_mbook').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_mbook').text(a);
					}
				}
				
				
				$("#cmb_work_no").change(function(event){
				   $(this).validateworkorder(event);
				 });
 
				 $("#currentmbookno").change(function(event){
				   $(this).validatembookno(event);
				 });
				  
		 
				$("#top").submit(function(event){
					$(this).validateworkorder(event);
					$(this).validatembookno(event);
					$(this).validateselectrad(event);
					});
                
                
            });
        </script>
	
	
	<body class="page1" id="top">
<!--==============================header=================================-->

		 <?php include "Menu.php"; ?>
<!--==============================Content=================================-->
		<div class="content">
			<div class="container_12">
				<div class="grid_12">
					<blockquote class="bq1">
						<div class="title">Abstract  Generate </div>
<form name="form" method="post">
<div class="content">
  <table width="1000"  bgcolor="#E8E8E8" border="0" cellpadding="0" cellspacing="0" align="center" >
     <tr><td>&nbsp;</td></tr>
     <tr> 
	     <td>&nbsp;</td> 
         <td  class="label">Date</td>
		 
         <td><input type="text" readonly="" name="txt_date" id="txt_date" class="textboxdisplay" value="<?php echo date('d/m/y') ?>" size="8"/>				                 
             </td>
			 <td>&nbsp;</td>
			 <td>&nbsp;</td>
     </tr>
	 <tr><td>&nbsp;</td></tr>
     <tr> 
	     <td>&nbsp;</td> 
         <td  class="label">Work Order No </td>
         <td  class="labeldisplay"><?php 
				  $sql_itemno="select sheet_id ,work_order_no  from sheet WHERE active =1"; 
				  $rs_itemno=mysql_query($sql_itemno);
			   ?>
           <select name="cmb_work_no" id="cmb_work_no" onChange="find_workname(); func_abshead_date();" class="textboxdisplay" style="width:400px;height:22px;" tabindex="7">
             <option value="">Select</option>
             <?php while($rows=mysql_fetch_assoc($rs_itemno)){ ?>
             <option value="<?php echo $rows['sheet_id']; ?>"><?php echo $rows['work_order_no']; ?></option>
             <?php } ?>
           </select></td>
         <td>&nbsp;</td>
			 <td>&nbsp;</td>
     </tr>
     <tr><td>&nbsp;</td><td></td><td colspan="3" id="val_work" style="color:red"></td></tr>
     <tr>
       <td>&nbsp;</td>
       <td  class="label">Name of the Work </td>
       <td  class="labeldisplay"><textarea name="workname" cols="48" rows="5" class="textboxdisplay"></textarea></td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
     </tr>
     <tr>
       <td>&nbsp;</td>
       <td  class="label">&nbsp;</td>
       <td  class="labeldisplay">&nbsp;</td>
       <td>&nbsp;</td>
       <td>&nbsp;</td>
     </tr>
     <tr> 
	     <td>&nbsp;</td> 
         <td  class="label">From Date </td>
         <td  class="labeldisplay"><input type="text" readonly="" name="txt_fromdate" id="txt_date2" class="textboxdisplay" value="" size="8"/>
            </td>
         <td>&nbsp;</td>
			 <td>&nbsp;</td>
     </tr>
     <tr><td>&nbsp;</td></tr>
     <tr> 
	     <td>&nbsp;</td> 
         <td  class="label">To Date </td>

         <td  class="labeldisplay"><input type="text" readonly="" name="txt_todate" id="txt_date3" class="textboxdisplay" value="" size="8"/>
           </td>
         <td>&nbsp;</td>
			 <td>&nbsp;</td>
     </tr>
     <tr><td>&nbsp;</td></tr>
      <tr> 
                                        <td>&nbsp;</td> 
                                        <td  class="label">M Book  No</td>

                                        <td  class="labeldisplay">
                                             <?php //echo $objBind->BindMBook(-1,$_SESSION['staffid']); 
                                            if($_SESSION['staffid'] ==0) { $Logonstaffid =0;} else {$Logonstaffid =$_SESSION['staffid'];} 
                                            ?>
                                            <select name="currentmbookno" id="currentmbookno" class="textboxdisplay" style="width:400px;height:22px;" tabindex="7">
                                                <option value="0"> -- Select MBook No -- </option>
                                                        <?php echo $objBind->BindMBook(-1,$Logonstaffid);?>
                                            </select>
                                            
                                            <input type="hidden" name="currentmbook" id="currentmbook" />
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                <tr><td>&nbsp;</td><td></td><td id="val_mbook" style="color:red"></td></tr>
                                    <tr> 
                                        <td>&nbsp;</td> 
                                        <td  class="label">M Book Page </td>

                                        <td  class="labeldisplay"><input type="text" name="bookpageno1" id="bookpageno1" class="textboxdisplay"  size="40" tabindex="5"/>
                                            <input type="hidden" name="bookpageno" id="bookpageno" />
                                            <input type="hidden" name="count" id="count" />
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
    
     

     <tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>
     <tr>
         <td colspan="6">
	       <center>
                   <input type="hidden" class="text" name="submit" value="true" />
            <input type="submit" class="btn" data-type="submit" value="Generate" name="html" id="html"   />&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="submit" class="btn"   data-type="submit" value="Excel Format" name="xcel" id="xcel"  style="display: none;" /> 
	      </center>	    </td>
<!--  style="display: none;"-->
     </tr>
     <tr><td>&nbsp;</td></tr>
 </table>
</div>
</form>
    <div class="col2"><?php if($msg != '') { echo $msg; } ?></div>
					</blockquote>
				</div>
				
			</div>
		</div>
  <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?> 
		  <script>

    $(function () {
        function DisplayPageDetails() {
            var currentmbooknovalue = $("#currentmbookno option:selected").attr('value');
            var currentmbooknotext = $("#currentmbookno option:selected").text();
            $.post("MBookNoService.php", {currentmbook: currentmbooknovalue}, function (data) {
                $("#bookpageno1").val(Number(data) + 1);$("#bookpageno").val(Number(data) + 1);
               $("#currentmbook").val(currentmbooknotext); 
                
            });
        }
        function DisplayRBNDetails() {
            var wordordernovalue = $("#wordorderno option:selected").attr('value');
            $.post("WorkOrderNoService.php", {wordorderno: wordordernovalue}, function (data) {
                 var workname = data.split("*");
                 $("#workname").text(workname[0]);
                $("#rbnno1").val(Number(workname[1]) + 1);$("#rbnno").val(Number(workname[1]) + 1);
            });
        }
        $("#currentmbookno").bind("change", function () {   
            DisplayPageDetails();
        });
         $("#wordorderno").bind("change", function () {   
            DisplayRBNDetails();
        });
    });
	
</script>
		</body>
</html>
