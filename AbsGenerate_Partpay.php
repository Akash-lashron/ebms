<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
checkUser();
require_once 'ExcelReader/excel_reader2.php';
$msg='';
$staffid = $_SESSION['sid'];
//$abs_mbno = $_SESSION["abs_mbno"];
//$abs_page = $_SESSION["abs_page"];
//$abs_mbno_id = $_SESSION["abs_mbno_id"];
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
    
        //$staffid =$_SESSION['userid'];
        $sheetid = trim($_POST['cmb_work_no']);
        $mb_date = dt_format($_POST['txt_date']);
        $fromdate = dt_format($_POST['txt_fromdate']);
        $todate = dt_format($_POST['txt_todate']);
        $mb_no = trim($_POST['currentmbook']);
        $mb_page = trim($_POST['bookpageno1']);
		$abs_mbno_id = trim($_POST['absmbookid']);
		$rbn = trim($_POST['txt_rbn_no']);
		//$paymentpercent = trim($_POST['txt_paymentpercent']);
		//$is_finalbill_query = "update mbookgenerate set is_finalbill = '$is_finalbill'";
		//$is_finalbill_sql = mysql_query($is_finalbill_query);		
        //$rbn = trim($_POST['rbnno']);
    $_SESSION["abstsheetid"] = $sheetid; //$_SESSION["abstmbno"] = $mb_no;      //$_SESSION["abstmbpage"] = $mb_page;
    $_SESSION['fromdate'] = $fromdate; $_SESSION['todate'] = $todate; //$_SESSION['rbn'] = $rbn;
	$_SESSION["abs_mbno"] = $mb_no;
	$_SESSION["abs_page"] = $mb_page;
	//$_SESSION["paymentpercent"] = $paymentpercent;
	//echo $mb_page;
	//echo $_SESSION["abs_page"];exit;
	$_SESSION["abs_mbno_id"] = $abs_mbno_id;
	$_SESSION["rbn"] = $rbn;
    header('Location: AbstMBook_Partpay.php'); }
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
                        //alert("No Records Found");
                        //document.form.itemno.value = 'Select';
						document.getElementById('generate_error').innerHTML = "You must Generate Steel/General MBook first..";
						document.getElementById("generate_html").disabled = true;
                    }
                    else
                    {
                        var absheaddate = data.split("*");
                        document.form.txt_fromdate.value = absheaddate[0];
                        document.form.txt_todate.value = absheaddate[1];
						//document.getElementById('generate_error').innerHTML = "Sucess";
                    }
                }
            }
            xmlHttp.send(strURL);
        }

            $(function () {
			
			/*$( "#txt_fromdate" ).datepicker({
					changeMonth: true,
					changeYear: true,
				   	dateFormat: "dd/mm/yy",
				   	yearRange: "2009:2015",
				   	maxDate: new Date,
				   	defaultDate: new Date,
				  /*defaultDate: "+1w",
				  changeMonth: true,
				  maxDate:new Date,
				  dateFormat: "dd/mm/y",*/
				  //minDate:$("#date").val();
				 /* beforeShow: function() {
				  		//alert($( "#txt_date2" ).val());
						 // var x= $( "#txt_fromdate" ).val();
						 // $( "#txt_fromdate" ).datepicker( "option", "minDate", x );
							},
				});*/
				
               /* $( "#txt_todate" ).datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: "dd/mm/yy",
				yearRange: "2009:2015",
				maxDate: new Date,
				defaultDate: new Date,
				  /*defaultDate: "+1w",
				  changeMonth: true,
				  maxDate:new Date,
				  dateFormat: "dd/mm/y",
				  //minDate:$("#date").val();
				  beforeShow: function() {
				  		//alert($( "#txt_date2" ).val());
						  var x= $( "#txt_fromdate" ).val();
						  $( "#txt_todate" ).datepicker( "option", "minDate", x );
							},
				});*/
			
			
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
            function func_AbsGenerateMBno()
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
                strURL = "find_Absgeneratembno.php?sheetid=" + document.form.cmb_work_no.value;
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
                            var name = data.split("*");
                            document.form.currentmbookno.length = 0;
                            var optn = document.createElement("option");
                            optn.value = 0;
                            optn.text = " -- Select MBook No -- ";
                            document.form.currentmbookno.options.add(optn);
                            var c = name.length;
                            var a = c / 2;
                            var b = a + 1;
                            for (i = 1, j = b; i < a, j < c; i++, j++)
                            {
                                var optn = document.createElement("option")
                               // optn.value = name[i];
                                optn.value = name[j];
                                optn.text = name[j];
                                document.form.currentmbookno.options.add(optn)  
                            }
                        }
                    }
                }
                xmlHttp.send(strURL);
        }
		
		function findabstarctmbbokno()
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
			strURL = "findabstract_mbookno.php?sheetid=" + document.form.cmb_work_no.value;
			xmlHttp.open('POST', strURL, true);
			xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xmlHttp.onreadystatechange = function ()
			{
				if (xmlHttp.readyState == 4)
				{
					data = xmlHttp.responseText;
					if (data != "")
					{
						//alert(data);
						var name = data.split("*");
						//document.getElementById('bookpageno_abs_1').value = "";
						//document.getElementById('bookpageno_abs').value = "";
						//document.getElementById('currentmbook_abs').value = "";
						
						document.getElementById('currentmbook').value = name[0];
						document.getElementById('bookpageno1').value = name[1];
						document.getElementById('absmbookid').value = name[2];
						document.getElementById('txt_rbn_no').value = name[3];
						//document.getElementById('rbnno').value = name[3];
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
					<blockquote class="bq1" style="overflow:scroll">
						<div class="title">Abstract  Generate </div>
<form name="form" method="post">
<div class="container">
  <table width="1000"  bgcolor="#E8E8E8" border="0" cellpadding="0" cellspacing="0" align="center" >
      <tr><td style=" width: 17%">&nbsp;</td></tr>
     <tr> 
	     <td>&nbsp;</td> 
         <td  class="label">Date</td>
		 
         <td><input type="text" readonly="" name="txt_date" id="txt_date" class="textboxdisplay" value="<?php echo date('d/m/y') ?>" size="15"/>				              
             </td>
			 <td></td>
			 <td>&nbsp;</td>
     </tr>
	 <tr><td>&nbsp;</td></tr>
     <tr> 
	     <td>&nbsp;</td> 
         <td  class="label">Work Short Name</td>
         <td  class="labeldisplay"><?php 
				 // $sql_itemno="select sheet_id ,short_name from sheet WHERE active =1"; 
				 // $rs_itemno=mysql_query($sql_itemno);
			   ?>
           <select name="cmb_work_no" id="cmb_work_no" onChange="find_workname(); func_AbsGenerateMBno(); func_abshead_date();findabstarctmbbokno();" class="textboxdisplay" style="width:400px;height:22px;" tabindex="7">
             <option value="">---------------------------------Select----------------------------------</option>
          		 <?php //echo $objBind->BindWorkOrderNo(0); ?>
				<?php echo $objBind->BindWorkOrderNo_CIVIL(0); ?>
		   </select>
		   </td>
         <td>&nbsp;</td>
			 <td>&nbsp;</td>
     </tr>
     <tr><td>&nbsp;</td><td></td><td colspan="3" id="val_work" style="color:red"></td></tr>
	 <tr>
        <td>&nbsp;</td>
        <td  class="label">Work Order No.</td>
        <td  class="labeldisplay">
		<input type="text" name="txt_workorder_no" id="txt_workorder_no" class="textboxdisplay" style="width: 395px;" readonly="">
		</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
     </tr>
     <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
     <tr>
       <td>&nbsp;</td>
       <td  class="label">Name of the Work </td>
       <td  class="labeldisplay"><textarea name="workname" class="textboxdisplay txtarea_style" style="width: 398px;" rows="5" disabled="disabled"></textarea></td>
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
         <td  class="labeldisplay"><input type="text" name="txt_fromdate" readonly="" id="txt_fromdate" class="textboxdisplay" value="" size="15"/>
            </td>
         <td>&nbsp;</td>
			 <td>&nbsp;</td>
     </tr>
     <tr>
	 <td>&nbsp;</td>
	 <td>&nbsp;</td>
	 <td><span id="generate_error" style="color:red; font-weight:bold"></span></td>
	 <td>&nbsp;</td>
	 <td>&nbsp;</td>
	 </tr>
     <tr> 
	     <td>&nbsp;</td> 
         <td  class="label">To Date </td>

         <td  class="labeldisplay"><input type="text" name="txt_todate" readonly="" id="txt_todate" class="textboxdisplay" value="" size="15"/>
           </td>
         <td>&nbsp;</td>
			 <td>&nbsp;</td>
     </tr>
     <tr><td>&nbsp;</td></tr>
     <tr> 
        <td>&nbsp;</td> 
        <td  class="label">Abstract MBook  No</td>

        <td  class="labeldisplay">
          <?php 
           //  if($_SESSION['sid'] ==0) { $Logonstaffid =0;} else {$Logonstaffid =$_SESSION['sid'];} 
            //  echo $objBind->BindMBook(-1,$Logonstaffid); 
          ?>
          <!-- <select name="currentmbookno" id="currentmbookno" class="textboxdisplay" style="width:400px;height:22px;" tabindex="7">
          <option value="0"> -- Select MBook No -- </option>
          <?php //echo $objBind->BindMBook(-1,$staffid);?>
          </select>-->
                                            
          <input type="text" name="currentmbook" id="currentmbook" class="textboxdisplay" size="54"/>
        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr><td>&nbsp;</td><td></td><td id="val_mbook" style="color:red"></td></tr>
    <tr> 
        <td>&nbsp;</td> 
        <td  class="label">Abstract MBook Page </td>

        <td  class="labeldisplay">
		<input type="text" name="bookpageno1" id="bookpageno1"  class="textboxdisplay"  size="54" tabindex="5"/>
		<input type="hidden" name="absmbookid" id="absmbookid" />
        <input type="hidden" name="bookpageno" id="bookpageno" />
        <input type="hidden" name="count" id="count" />
        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr><td>&nbsp;</td></tr>
    
    <tr> 
        <td>&nbsp;</td> 
        <td  class="label">Running Account Bill No. </td>

        <td  class="labeldisplay">
		<input type="text" name="txt_rbn_no" id="txt_rbn_no"  class="textboxdisplay"  size="54" tabindex="5"/>
        </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
     <tr><td>&nbsp;</td></tr>
	<!-- <tr>
        <td>&nbsp;</td>
        <td  class="label">Payment Percentage</td>
        <td  class="labeldisplay">
		<input type="text" name="txt_paymentpercent" id="txt_paymentpercent" maxlength="2" class="textboxdisplay" size="3" value="100"> <label class="label">( % )</label>
		</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
     <tr><td>&nbsp;</td><td></td><td id="val_work" style="color:red"></td></tr>-->
     <tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>
     <tr>
         <td colspan="6">
	       <center>
                   <input type="hidden" class="text" name="submit" value="true" />
           <!-- <input type="submit" class="btn" data-type="submit" value="Generate" name="html" id="generate_html"   />&nbsp;&nbsp;&nbsp;
            <input type="submit" class="btn"   data-type="submit" value="Excel Format" name="xcel" id="xcel"  style="display: none;" /> 
			<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>-->
	      </center>	    </td>
<!--  style="display: none;"-->
     </tr>
 </table>
</div>
		<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
			<div class="buttonsection">
			<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
			</div>
			<div class="buttonsection" style="width:105px">
			<input type="submit" class="btn" data-type="submit" value="Generate" name="html" id="generate_html"   />
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

    $(function () {
      /*  function DisplayPageDetails() {
            var currentmbooknovalue = $("#currentmbookno option:selected").attr('value');
            var currentmbooknotext = $("#currentmbookno option:selected").text();
            $.post("MBookNoService.php", {currentmbook: currentmbooknovalue}, function (data) {
                $("#bookpageno1").val(Number(data) + 1);$("#bookpageno").val(Number(data) + 1);
               $("#currentmbook").val(currentmbooknotext); 
                
            });
        }*/
        function DisplayRBNDetails() {
            var wordordernovalue = $("#wordorderno option:selected").attr('value');
            $.post("WorkOrderNoService.php", {wordorderno: wordordernovalue}, function (data) {
                 var workname = data.split("*");
                 $("#workname").text(workname[0]);
				 //$("#txt_workorder_no").val(workname[2]);
                 $("#rbnno1").val(Number(workname[1]) + 1);$("#rbnno").val(Number(workname[1]) + 1);
            });
        }
       /* $("#currentmbookno").bind("change", function () {   
            DisplayPageDetails();
        });*/
         $("#wordorderno").bind("change", function () {   
            DisplayRBNDetails();
        });
    });
	
</script>
		</body>
</html>
