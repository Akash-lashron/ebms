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
$view = 0;$count = 0;
if($_POST["submit"] == " View "){
	$CC_NO 	    = trim($_POST['txt_cc_no']);
	$view 		= 1; $count1 = 0; $count2 = 0; $count3 = 0; $count4 = 0; $count5 = 0; $count6 = 0; $staff_assign="";
	if ($CC_NO !=''){
		$select_sheet_query = "select distinct * from sheet where computer_code_no = '$CC_NO' and active=1 ";
		//echo $select_sheet_query;
		$select_sheet_sql 	= mysql_query($select_sheet_query);
		if($select_sheet_sql == true){
			$count1 	= mysql_num_rows($select_sheet_sql);
			$List1 		= mysql_fetch_object($select_sheet_sql);
			if($count1 > 0){
			   $sheet_id 	  = $List1->sheet_id;
			   $staff_assign  = $List1->assigned_staff;
			}else{
			  $msg     = "Invalid CCNO. (Or) Entered CCNO Does Not Exists";
			  $success = 1;
			}
		}
		$select_sch_query = "select distinct * from schdule  where sheet_id = '$sheet_id' ";
		$select_sch_sql 	 = mysql_query($select_sch_query);
		if($select_sch_sql == true){
			$count2 	= mysql_num_rows($select_sch_sql);
			$List2 		= mysql_fetch_object($select_sch_sql);
			$sheet_id 	= $List1->sheet_id;
			//echo $select_sch_query;
		}
		$select_mbook_query 		= "select distinct * from mbookallotment where sheetid = '$sheet_id' ";
		$select_mbook_sql 	 	= mysql_query($select_mbook_query);
		if($select_mbook_sql == true){
			$count3 	= mysql_num_rows($select_mbook_sql);
			$List3 		= mysql_fetch_object($select_mbook_sql);
			
		}
    }	
}
?>
<?php require_once "Header.html"; ?>
<style>
.table1{
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:13px;
}
.tboxclass {
    width: 90%;
}
.divhead {
    padding: 1px 1px;
}
.innerdiv {
    padding: 8px;
}
.well-A{
	background-color:#F4F5F7;/*#038BCF*/
	border: 2px solid #055DAB;/*038BCF*/
	color:#032FAD;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	cursor:pointer;
	border-radius:8px;
	margin-right:2px;
	padding:8px 8px;
}
.well-A.active{
	background-color:#055DAB;
	border: 2px solid #055DAB;
	color:#fff;
}
.rspan {
    padding: 4px;
    border: 2px solid #D02068;
    border-radius: 8px;
    background: #D02068 !important;
    color: #ffffff;
}
.rspangreen{
    padding: 4px;
    border: 2px solid #33FFCC;
    border-radius: 8px;
    background: #1fad9b !important;
    color: #ffffff;
}
.rspanred{
    padding: 4px;
    border: 2px solid #FFAED7;
    border-radius: 8px;
    background: #D02068 !important;
    color: #ffffff;
}		
</style>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<!--==============================header=================================-->
<?php include "Menu.php"; ?>
<!--==============================Content=================================-->
<div class="content">
 <div class="title">SOQ Status</div>
	<div class="container_12">
		<div class="grid_12">
			<blockquote class="bq1" style="overflow:auto">
				<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<div class="container">
						<div class="row ">
							<div class="div2">&nbsp;</div>
							<div class="div8">
								<div class="row"><div class="div12" style="margin-top:0px;"><div class="row divhead" align="center">SOQ Status</div></div></div>
								<div class="row innerdiv"> 
									<div class="row" align="center" style="margin-top:0px;">
									    <div class="div2">&nbsp;</div>
										<div class="div3" align="right">
											<label for="fname">CC No.</label>
										</div>
										<div class="div3" align="left" style="width:150px;">
											<input type="text" name='txt_cc_no' id='txt_cc_no' class="tboxclass" autocomplete="off" value="<?php echo $CC_NO; ?>">
										</div>
										<div class="div2" style="width:100px;" align="left"><input type="submit" class="btn" data-type="submit" value=" View " name="submit" id="submit" style ="padding: 6px 8px; "/></div>
									    <div class="div2" align="left" style="height:0px; color:red;" id="val_ccno"></div>
										<!--<label align="left" style="height:0px; color:red;" id="val_ccno"> </label>-->
										<!--<div class="div12" align="center" style="height:0px; color:red;" id="val_ccno"></div>-->
									</div>
									<div class="div12" style="height:0px;">&nbsp;</div>
								</div>
								
							</div>
						</div>
					</div>
					<!--<div style="text-align:center; height:30px; line-height:30px;" class="printbutton">
						<div class="buttonsection">
							<input type="submit" class="btn" data-type="submit" value=" View " name="submit" id="submit" style ="padding: 3px 10px; " />
							<input type="hidden" id="hidde_ccno" name="hidde_ccno" value="
							">
						</div>
					</div>--> 
					<?php //echo $view ; ?>
					<div class="row" <?php if($view == 0){ ?> style="display:none" <?php } ?>>
					    <div class="div4">&nbsp;</div>
					    <?php if($count1 != 0){ ?>
						<div class="div4">
						 <?php if($count2 > 0){ ?>
							<div class="col-md-2 well-A level rspangreen" align="left"><i class='fa fa-check-circle' style='font-size:20px; color:#CACACA'></i> SOQ Uploaded </div> <br/>
						 <?php }else{?>
						   <div class="col-md-2 well-A level rspanred" align="left"><i class='fa fa-times-circle' style='font-size:20px; color:#CACACA'></i> SOQ Not Uploaded</div> <br/>
						<?php }?>	
						<?php if($staff_assign != ""){ ?>
							<div class="col-md-2 well-A level rspangreen" align="left"><i class='fa fa-check-circle' style='font-size:20px; color:#CACACA'></i> Staff  Assigned</div> <br/>
						<?php }else{ ?>
							<div class="col-md-2 well-A level rspanred" align="left"><i class='fa fa-times-circle' style='font-size:20px; color:#CACACA'></i> Staff Not Assigned</div> <br/>
						<?php } ?>
						<?php if($count2 > 0 ){ ?>
							<div class="col-md-2 well-A level rspangreen" align="left"><i class='fa fa-check-circle' style='font-size:20px; color:#CACACA'></i> Mbook Assigned </div> <br/>
						<?php }else{ ?>
							<div class="col-md-2 well-A level rspanred" align="left"><i class='fa fa-times-circle' style='font-size:20px; color:#CACACA'></i> Mbook Not Assigned </div> <br/>
						<?php } ?>
						</div>
						<?php }else {?>
						<div class="div4">&nbsp;</div>
						<?php }?>
						<div class="div4">&nbsp;</div>
					</div>
					<div style="text-align:center; height:30px; line-height:30px;" class="printbutton">
						<div class="buttonsection">
							<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
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
$(function() {
	$.fn.validateCCno = function(event) { 
		if($("#txt_cc_no").val()==""){ 
			var a="Please Enter your CC Number";
			$('#val_ccno').text(a);
			event.preventDefault();
			event.returnValue = false;
		}
		else if($("#txt_cc_no").val()==0){ 
			var a="Please Enter valid CC Number";
			$('#val_ccno').text(a);
			event.preventDefault();
			event.returnValue = false;
		}
		else{
			var a="";
			$('#val_ccno').text(a);
		}
	}
	$("#top").submit(function(event){
		$(this).validateCCno(event);
	});
	$("#txt_cc_no").keyup(function(event){
    	$(this).validateCCno(event);
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
				swal("", msg, "");
			}
			else
			{
				swal(msg, "", "");
			}
		}
	};
	function goBack()
	{
		url = "dashboard.php";
		window.location.replace(url);
	}
</script>
</body>
</html>

