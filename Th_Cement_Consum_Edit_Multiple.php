<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'ExcelReader/excel_reader2.php';
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
	$id_str		= $_POST['txt_id'];
	$exp_id_str = explode("*",$id_str);
	$cnt 		= count($exp_id_str);
	for($i=0; $i<$cnt; $i++)
	{
		$sch_id		= $exp_id_str[$i];
		$tc_unit	= $_POST['txt_tc_unit_'.$sch_id];
				
		$update_query = "update schdule set tc_unit = '$tc_unit' where sch_id = '$sch_id'";
		$update_sql = mysql_query($update_query);
	}
	if($update_sql == true)
	{
		$msg = "Measurement Updated Sucessfully";
		$success = 1;
	}
	else
	{
		$msg = "Error";
	}
		//echo $update_query;
}
if($_GET['sheetid'] != "")
{
	$sheetid = $_GET['sheetid'];
	$edit_id_list = $_SESSION['edit_id_list'];
	$cnt = count($edit_id_list);
}
?>
<?php require_once "Header.html"; ?>
<script>
     
	function goBack()
	{
	   	url = "Th_Cement_Consum_View.php";
		window.location.replace(url);
	}
	function content_area_gen()
	{
		var no = document.form.txt_no_gen.value;
		var len = document.form.txt_length_gen.value;
		var breadth = document.form.txt_breadth_gen.value;
		var depth = document.form.txt_depth_gen.value;
		var content_area = Number(no)*Number(len)*Number(breadth)*Number(depth);
		document.form.txt_content_area_gen.value = Number(content_area).toFixed(3);
	}
	function content_area_steel()
	{
		var no = document.form.txt_no_steel.value;
		var len = document.form.txt_length_steel.value;
		var dia = document.form.txt_dia_steel.value;
		var content_area = Number(no)*Number(len);
		document.form.txt_content_area_steel.value = Number(content_area).toFixed(3);
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
                        <div class="title">Theoritical Cement Consumption - Edit</div>
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="container">
						<br/>
							<table width="100%"  bgcolor="#E8E8E8" class="label table1" cellpadding="0" cellspacing="0" align="center">
								<!--<tr><td colspan="8">&nbsp;</td></tr>-->
								<tr class="label heading" style="color:#FFFFFF">
									<!--<td align="center">Date</td>-->
									<td align="center" width="10%">Item No.</td>
									<td align="center" width="70%">Item Description</td>
									<td align="center" width="20%">Theor. Cem. Consum.</td>
								</tr>
						<?php
							if($cnt>0)
							{
								$tabindex = 0;
								for($i=0; $i<$cnt; $i++)
								{
									$id 			= $edit_id_list[$i];
									$idStr .= $id."*";
									$th_cem_cons_query ="SELECT DISTINCT sno, sch_id, subdiv_id, sheet_id, tc_unit, description, total_quantity, rate, per, total_amt FROM schdule where sch_id = '$id'";
									//echo $schdulesql."<br/>";
									$th_cem_cons_sql 	= mysql_query($th_cem_cons_query);
									if($th_cem_cons_sql == true)
									{
										$MList = mysql_fetch_object($th_cem_cons_sql);
										$sch_id 		= $MList->sch_id;
										$itemno 		= $MList->sno;
										$description 	= $MList->description;
										$tc_unit 		= $MList->tc_unit;
											?>
												<tr class="labelsmall">
													<td align="center" valign="middle"><?php echo $itemno; ?></td>
													<td align="left"><?php echo $description; ?></td>
													<td align="center" valign="middle">
														<input type="text" class="textboxnewl" tabindex="<?php $tabindex++; echo $tabindex; ?>" name="txt_tc_unit_<?php echo $sch_id; ?>" id="txt_tc_unit_<?php echo $sch_id; ?>" value="<?php echo $tc_unit; ?>" style="width:99%;">
													</td>
												</tr>
											<?php
									} 
								}
							}
						?>

							</table>
							<input type="hidden" name="txt_id" id="txt_id" value="<?php echo rtrim($idStr,"*"); ?>">
				 		</div>
						<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
							<div class="buttonsection">
							<input type="button" name="back" value="Back" id="back" class="backbutton" onClick="goBack();" />
							</div>
							<div class="buttonsection">
							<input type="submit" class="btn" data-type="submit" value=" Update " name="submit" id="submit"   />
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
$(document).on("keyup", function(e) {
	var type = $('#txt_type').val();
	if(type == 'S')
	{
        var code = e.which,
            elm = document.activeElement,
            currentTabIndex = elm.tabIndex,
            nextTabIndex = code == 40 || code == 39 ? currentTabIndex + 1 :
        code == 38 || code == 37 ? currentTabIndex - 1 : null,
            isHoriz = code == 39 || code == 37;
        $('[tabindex]').filter(function() {
            if( !$(elm).is(':text,textarea') || !isHoriz ) {
                return this.tabIndex == nextTabIndex;
            }
        })
        .focus();
	}
	else
	{
		 var code = e.which,
            elm = document.activeElement,
            currentTabIndex = elm.tabIndex,
            nextTabIndex = code == 40 || code == 39 ? currentTabIndex + 1 :
        code == 38 || code == 37 ? currentTabIndex - 1 : null,
            isHoriz = code == 39 || code == 37;
        $('[tabindex]').filter(function() {
            if( !$(elm).is(':text,textarea') || !isHoriz ) {
                return this.tabIndex == nextTabIndex;
            }
        })
        .focus();
	}	
});
   /* $(function() {
	
	$( "#txt_mbook_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd-mm-yy",
                    maxDate: new Date,
                    defaultDate: new Date,
                });
	
	
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
		$.fn.validatmbookname = function(event) { 
					if($("#txt_mbook_name").val()==""){ 
					var a="Please Enter MBook Name";
					$('#val_mbookname').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
				else{
				var a="";
				$('#val_mbookname').text(a);
				}
			}

		$("#top").submit(function(event){
				$(this).validatembooktype(event);
				$(this).validateworkorder(event);
				$(this).validatmbookname(event);
			 });
		$("#cmb_work_no").change(function(event){
			   $(this).validateworkorder(event);
			 });
		$("#cmb_mbook_type").change(function(event){
			   $(this).validatembooktype(event);
			 });
		 $("#txt_mbook_name").change(function(event){
			   $(this).validatmbookname(event);
			 });
			
	 });*/
</script>
<style>
	:focus {
    background:#BDE0FB;
	/*background: LightGreen;*/
}
</style>
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

