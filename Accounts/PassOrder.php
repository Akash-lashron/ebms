<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName 	= $PTPart1.$PTIcon.'Home';
$msg 		= ""; $del = 0;
$RowCount 	= 0;
$staffid 	= $_SESSION['sid'];
$UnconfirmSor = 0; $UnconfirmRate = 0;
if(isset($_POST['submit'])){
	$SaveCcno 		= $_POST['txt_ccno'];
	$SaveSrNo 		= $_POST['txt_sr_no'];
	$SaveRab 		= $_POST['txt_rab'];
	$SaveChRab 		= $_POST['ch_rab'];
	$SaveChFbill 	= $_POST['ch_finalbill'];
	$SaveSecAdv 	= $_POST['ch_sec_adv'];
	$SaveEsc 	 	= $_POST['ch_esc'];
	$SaveMobAdv 	= $_POST['ch_mob_adv'];
	$SaveSentBy 	= $_POST['cmb_sent_by'];
	$SaveSentOn 	= $_POST['txt_sent_on'];
	$SaveRecOn 		= $_POST['txt_rec_on'];
	$SaveSheetId 	= $_POST['txt_sheetid'];
	$WorkExist 		= 0;
	$SelectQuery = "SELECT * FROM bill_register WHERE sheetid = '$SaveSheetId' AND rbn = '$SaveRab'";
	$SelectSql   = mysqli_query($dbConn,$SelectQuery);
	if($SelectSql == true){
		if(mysqli_num_rows($SelectSql)>0){
			$WorkExist = 1;
		}
	}
	if($WorkExist == 0){
		$SaveBillRegQuery = "insert into bill_register set sheetid = '$SaveSheetId', rbn = '$SaveRab', br_no = '$SaveSrNo', is_rab = '$SaveChRab', is_finalbill = '$SaveChFbill', is_escalation = '$SaveEsc', is_secured_adv = '$SaveSecAdv', is_mob_adv = '$SaveMobAdv', sent_by = '".$_SESSION['sid']."', sent_on = NOW(), received_by = '', received_on = NOW(), civil_status = 'C', acc_status = 'P', reg_status = 'R', active = 1";
		$SaveBillRegSql   = mysqli_query($dbConn,$SaveBillRegQuery);
	}else{
		$SaveBillRegQuery = "update bill_register set sheetid = '$SaveSheetId', rbn = '$SaveRab', br_no = '$SaveSrNo', is_rab = '$SaveChRab', is_finalbill = '$SaveChFbill', is_escalation = '$SaveEsc', is_secured_adv = '$SaveSecAdv', is_mob_adv = '$SaveMobAdv', sent_by = '".$_SESSION['sid']."', sent_on = NOW(), received_by = '', received_on = NOW(), civil_status = 'C', acc_status = 'P', reg_status = 'R', active = 1 WHERE sheetid = '$SaveSheetId' AND rbn = '$SaveRab'";
		$SaveBillRegSql   = mysqli_query($dbConn,$SaveBillRegQuery);
	}
	if($SaveBillRegSql == true){
		$msg = "Bill register saved successfully";
	}else{
		$msg = "Sorry, Bill register not saved. Please try again.";
	}
}

$BRCount = 0;
$SelectQuery = "SELECT a.*, b.short_name, b.computer_code_no FROM bill_register a INNER JOIN sheet b ON (a.sheetid = b.sheet_id) WHERE reg_status = ''";
$SelectSql   = mysqli_query($dbConn,$SelectQuery);
if($SelectSql == true){
	if(mysqli_num_rows($SelectSql)>0){
		$BRCount = 1;
	}
}
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>	
<style>
.pagetitle
{
	text-shadow:
    -1px -1px 0 #7F7F7F,
    1px -1px 0 #7F7F7F,
    -1px 1px 0 #7F7F7F,
    1px 1px 0 #7F7F7F; 
}
.table1
{
	color:#BF0602;
	/*color:#921601;*/
	border: 1px solid #cacaca;
	border-collapse: collapse;
}
.table1 td
{ 
	border: 1px solid #cacaca;
	border-collapse: collapse;
}
.fontcolor1
{
	color:#FFFFFF;
}

.table2
{
	color:#071A98;
	border:1px solid #cacaca;
	border-collapse: collapse;
}
.table2 td
{
	border:1px solid #cacaca;
	border-collapse: collapse;
}
.labelprint
{
	font-weight:normal;
	color:#000000;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10pt;
}
.labelprinterror
{
	font-weight:normal;
	color:#F00000;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:10pt;
}
.memo_label
{
	font-size:11px;
}
.memo_textbox
{
	font-size:11px;
	text-align:right;
	border:1px solid #09A5C6;
	height:20px;
}
.memo_pecrcenttextbox
{
	width:45px;
	font-size:11px;
	text-align:right;
	border:1px solid #09A5C6;
	height:20px;
}
.memo_table
{
	border:1px solid #C7C7C7;
	border-collapse:collapse;
}
@media print 
{
	.printbutton
	{
		display: none !important;
	}
}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<?php include "MainMenu.php"; ?>
				<div class="container_12">
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" style="overflow:auto">
							
							<div class="box-container box-container-lg">
								
								
								<div class="div12">
									<div class="card">
										<div class="face-static">
											<div class="card-header inkblue-card">Pass Order</div>
											<div class="card-body padding-1 ChartCard billrowform">
												<div class="row">
													<div class="div12" align="center">
														<div class="innerdiv2">
															<?php 														
															$page = $abstmbpage;
															$title = '<table width="1087px" border="0"  cellpadding="2" cellspacing="2" align="center" bgcolor="#FFFFFF" style="border:none;" class="labelprint">
																		<tr style="border:none;"><td align="center" style="border:none;">Abstract M.Book No. '.$abstmbno.'&nbsp;&nbsp;<br/>&nbsp;</td></tr>
																		</table>';
															echo $title;
															//$Line = $Line+2;
															$table = $table . "<table width='1087px'  bgcolor='#FFFFFF' border='0' cellpadding='1' cellspacing='1' align='center' class='table1 labelprint' >";
															$table = $table . "<tr>";
															$table = $table . "<td width='17%' class=''>Name of work</td>";
															$table = $table . "<td width='43%' style='word-wrap:break-word' class=''>" .$work_name."</td>";
															$table = $table . "<td width='18%' class=''>Name of the contractor</td>";
															$table = $table . "<td width='22%' class='' colspan='3'>" . $name_contractor . "</td>";
															$table = $table . "</tr>";
															$table = $table . "<tr>";
															$table = $table . "<td class=''>Technical Sanction No.</td>";
															$table = $table . "<td class=''>" . $tech_sanction . "</td>";
															$table = $table . "<td class=''>Agreement No.</td>";
															$table = $table . "<td class='' colspan='3'>" . $agree_no . "</td>";
															$table = $table . "</tr>";
															$table = $table . "<tr>";
															$table = $table . "<td class=''>Work order No.</td>";
															$table = $table . "<td class=''>" . $work_order_no . "</td>";
															$table = $table . "<td class=''>Running Account bill No. </td>";
															$table = $table . "<td class=''>" . $runn_acc_bill_no . "</td>";
															$table = $table . "<td class='' align='right'>CC No. </td>";
															$table = $table . "<td class=''>" . $ccno . "</td>";
															$table = $table . "</tr>";
															//$table = $table . "<tr>";
															//$table = $table . "<td colspan ='4' class='labelprint' align='center'>Abstract Cost for ".$short_name." for the period of ".date("d/m/Y", strtotime($fromdate))." to ".date("d/m/Y", strtotime($todate))."</td>";
															//$table = $table . "</tr>";
															$table = $table . "</table>";
															//$Line = $Line+6;
															//$tablehead = $tablehead . "<table width='1087px' frame=''  bgcolor='#0A9CC5' border='1' cellpadding='3' cellspacing='3' align='center' style='color:#ffffff;' id='mbookdetail' class='label table1'>";
															$tablehead = $tablehead . "<tr style='background-color:#EEEEEE;' class='labelprint'>";
															$tablehead = $tablehead . "<td  align='center' class='labelsmall labelheadblue' width='12px' style='' rowspan='2'></td>";
															$tablehead = $tablehead . "<td  align='center' class='' width='44px' rowspan='2'>Item No.</td>";
															$tablehead = $tablehead . "<td  align='center' class='' width='130px' rowspan='2'>Description of work</td>";
															$tablehead = $tablehead . "<td  align='center'  width='40px' rowspan='2'>Contents of Area</td>";
															$tablehead = $tablehead . "<td  align='center' class='' width='40px' rowspan='2'>Rate&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'></td>";
															$tablehead = $tablehead . "<td  align='center' class='' width='40px' rowspan='2'>Per</td>";
															$tablehead = $tablehead . "<td  align='center' class='' width='40px' rowspan='2'>Total value to Date&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'></td>";
															$tablehead = $tablehead . "<td  align='center' class='' width='100px' colspan='3'>Deduct previous Measurements</td>";
															$tablehead = $tablehead . "<td  align='center' class='' width='120px' colspan='3'>Since Last Measurement</td>";
															$tablehead = $tablehead . "</tr>";
															$tablehead = $tablehead . "<tr style='background-color:#EEEEEE;' class='labelprint'>";
															$tablehead = $tablehead . "<td width='30px' align='center' class=''>Page</td>";
															$tablehead = $tablehead . "<td width='40px' align='center' class=''>Quantity</td>";
															$tablehead = $tablehead . "<td width='40px' align='center' class=''>Amount&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'></td>";
															$tablehead = $tablehead . "<td width='40px' align='center' class=''>Quantity</td>";
															$tablehead = $tablehead . "<td width='40px' align='center' class=''>Value&nbsp;<i class='fa fa-inr' style='font-weight:normal; width:4px; height:5px;'></td>";
															$tablehead = $tablehead . "<td width='40px' align='center' class=''>Remark</td>";
															$tablehead = $tablehead . "</tr>";
															//$tablehead = $tablehead . "</table>";
															?>
															
															<?php echo $table; ?>
															<?php
															echo "<table width='1087px' bgcolor='white' cellpadding='3' cellspacing='3' align='center' class='label table1'>";
															echo $tablehead;
															echo "<tr style='border:none'>
															<td style='border:none' class='labelbold' align='left' colspan='5'></td>
															<td style='border:none' class='labelbold' align='left' colspan='8'><u>Memo of Payment</u></td>
															</tr>";
															echo '<tr><td align="left" class="labelsmall" colspan="13">';
															?>
															<div>Passed for payment Rs. ()</div>
															<?php
															echo '</td></tr>';
															echo "</table>";
															?>
															
					
															<div class="row clearrow"></div>
															
														</div>
													</div>
												</div>                         
											</div>
										</div>
									</div>
								</div>
							
							</div>
						</blockquote>
					</div>
				</div>
			</div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
</html>
<script>
$(document).ready(function(){
	$('.notBtn').click(function(event){ 
		var PageUrl = $(this).attr("data-url");
  		$(location).attr("href",PageUrl+".php");
		event.preventDefault();
		return false;
  	});
	function WorkDetails(Work,Rab,Ccno,Type){
		$("#txt_ccno").val('');
		$("#txt_sr_no").val('');
		$("#txt_work_name").val('');
		$("#txt_rab").val('');
		$("#cmb_sent_by").val('');
		$("#txt_sent_on").val('');
		$("#txt_sheetid").val('');
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/FindWorksData.php', 
			data: { Work: Work, Rab: Rab, Ccno: Ccno, Type: Type }, 
			dataType: 'json',
			success: function (data) {   //alert(data['computer_code_no']);
				if(data != null){
					$.each(data, function(index, element) { 
						$("#txt_ccno").val(element.computer_code_no);
						$("#txt_sr_no").val(element.bill_serial_no);
						$("#txt_work_name").val(element.short_name);
						$("#txt_rab").val(element.rbn);
						$("#cmb_sent_by").val(element.sent_by);
						$("#txt_sent_on").val(element.sent_on);
						$("#txt_sheetid").val(element.sheet_id);
					});
				}
			}
		});
	}
	$('.BillData').click(function(event){ 
		var Work  = $(this).attr("id");
		var Rab   = $(this).attr("data-rab");
		var Ccno  = $(this).attr("data-ccno");
		$(".BillData").removeClass("billrow-active");
		 $(this).addClass("billrow-active");
		WorkDetails(Work,Rab,Ccno,'A');
  	});
	$('#txt_ccno').change(function(event){ 
		var Work  = null;
		var Rab   = null;
		var Ccno  = $(this).val();;
		WorkDetails(Work,Rab,Ccno,'M');
  	});
	
});
</script>
<link rel="stylesheet" href="css/notyBox.css">

