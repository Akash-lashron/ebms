<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'ExcelReader/excel_reader2.php';
$msg = '';
include "library/common.php";
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
$sheetid = $_GET['workno'];
$select_comment_query = "select subdivid, accounts_remarks from measurementbook_temp where sheetid = '$sheetid' and accounts_remarks != ''";
$select_comment_sql = mysql_query($select_comment_query);

$sheet_count = 0;
$select_sheet_query = "select distinct(measurementbook_temp.rbn), sheet.short_name from measurementbook_temp
INNER JOIN sheet ON (measurementbook_temp.sheetid = sheet.sheet_id) where sheetid = '$sheetid'";
$select_sheet_sql = mysql_query($select_sheet_query);
if($select_sheet_sql == true)
{
	if(mysql_num_rows($select_sheet_sql)>0)
	{
		$SheetList = mysql_fetch_object($select_sheet_sql);
		$work_short_name = $SheetList->short_name;
		$rbn = $SheetList->rbn;
		$sheet_count = 1;
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
	   	url = "AccountsComments.php";
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
                        <div class="title">Accounts Comments</div>
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                       
                            <div class="container" style="text-align:center">
							<br/>
							 <table width="100%"  bgcolor="#E8E8E8" border="0" cellpadding="4" cellspacing="4" align="center" class="label table1">
							<?php
							if($sheet_count == 1)
							{
							?>
								<tr>
									<td align="left" colspan="2">Work Short Name</td>
									<td align="left" ><?php echo $work_short_name; ?></td>
								</tr>
								<tr>
									<td align="left" colspan="2">RAB No.</td>
									<td align="left" >&nbsp;<?php echo $rbn; ?></td>
								</tr>

							<?php	
							}
							?>
							 	<tr style="background-color:#E4E4E4">
									<td align="center" width="50px">Sl.No.</td>
									<td align="center" width="200px">Item Name</td>
									<td>Accounts Remarks</td>
								</tr>
							<?php
							if($select_comment_sql == true)
							{
								if(mysql_num_rows($select_comment_sql)>0)
								{
									$count = 1;
									while($CMDList = mysql_fetch_object($select_comment_sql))
									{
										$item_name = getsubdivname($CMDList->subdivid);
							?>
									 <tr>
									 	<td class="labelsmall"><?php echo $count; ?></td>
									 	<td class="labelsmall"><?php echo $item_name; ?></td>
										<td class="labelsmall"><?php echo $CMDList->accounts_remarks; ?></td>
									 </tr>
							<?php
										$count++;
									}
								}
							}
							?>
							</table>
     						</div>
       <div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
			<div class="buttonsection">
			<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" /> 
			</div>
			<!--<div class="buttonsection">
			<input type="submit" class="btn" data-type="submit" value=" View " name="submit" id="submit"   />
			</div>-->
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
	$.fn.validatembooktype = function(event) {	
				if($("#cmb_mbook_type").val()==""){ 
					var a="Please select the Measurement Type";
					$('#val_mbooktype').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
				else{
				var a="";
				$('#val_mbooktype').text(a);
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
            $(this).validatembooktype(event);
			$(this).validateworkorder(event);
         });
	$("#cmb_work_no").change(function(event){
           $(this).validateworkorder(event);
         });
    $("#cmb_mbook_type").change(function(event){
           $(this).validatembooktype(event);
         });
			
	 });
</script>
    </body>
</html>

