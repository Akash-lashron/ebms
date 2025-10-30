<?php 
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/declaration.php';
include "common.php";
$PageName = $PTPart1.$PTIcon.'Financial and Physical Progress';
$msg = "";
//print_r($ChildArr);exit;
/*$SelectSheetQuery = "select * from sheet";// where sheet_id = '$SheetId'";
$SelectSheetSql = mysqli_query($dbConn, $SelectSheetQuery);
if($SelectSheetSql == true){
	if(mysqli_num_rows($SelectSheetSql)>0){
		while($List = mysqli_fetch_object($SelectSheetSql)){
			$WorkName 	= $List->short_name;
			$ContName 	= $List->name_contractor;
			$CCNo 		= $List->computer_code_no;
			$WoNo 		= $List->work_order_no;
			$WoValue 	= $List->work_order_cost;
			$IsData		= 1;
			$Rows['item'] 				= $WorkName;
			$Rows['name_contractor'] 	= $ContName;
			$Rows['ccno_wono'] 			= $CCNo."/".$WoNo;
			$Rows['wo_amt'] 			= $WoValue;
			$InsertQuery = "insert into works set sheetid = '$List->sheet_id', ccno = '$List->computer_code_no', work_name = '$List->work_name', ts_no = '$List->tech_sanction', 
			wo_no = '$List->work_order_no', wo_amount = '$List->work_order_cost', wo_date = '$List->work_order_date', agmt_no = '$List->agree_no', name_contractor = '$List->name_contractor'";
			$InsertSql = mysqli_query($dbConn, $InsertQuery);
		}
	}
}*/
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>	
<style>
/* Checkbox Styles */
input[type="checkbox"] {
  -webkit-appearance: none;
  outline: none;
  /*position: absolute;*/
  height: 30px;
  width: 80px;
  border: 5px solid var(--body);
  border-radius: 2rem;
  cursor: pointer;
  box-shadow: 9px 9px 16px rgba(189, 189, 189, 0.6), -9px -9px 16px rgba(255, 255, 255, 0.5), inset 10px 10px 15px -10px #c3c3c3, inset -10px -10px 15px -10px #fff;
  /* Toggle Indicator */
  /* Label */
  /* Checked Styles */
  /*margin:-2px -30px;*/
}
@media (prefers-color-scheme: light) {
  input[type="checkbox"] {
    box-shadow: 9px 9px 16px rgba(189, 189, 189, 0.6), -9px -9px 16px rgba(255, 255, 255, 0.5), inset 10px 10px 15px -10px #c3c3c3, inset -10px -10px 15px -10px #fff;
  }
}
@media (prefers-color-scheme: dark) {
  input[type="checkbox"] {
    box-shadow: -8px -4px 8px 0px rgba(255, 255, 255, 0.05), 8px 4px 12px 0px rgba(0, 0, 0, 0.5), inset -4px -4px 4px 0px rgba(255, 255, 255, 0.05), inset 4px 4px 4px 0px rgba(0, 0, 0, 0.5);
  }
}
input[type="checkbox"]::before {
  /*content: "";*/
  height: 15px;
  width: 15px;
  background-color: var(--body);
  position: absolute;
  margin: auto;
  top: 0;
  left: 15px;
  bottom: 0;
  border-radius: 50%;
  box-shadow: 7px 7px 15px #c3c3c3, 9px 9px 16px rgba(189, 189, 189, 0.6);
  transition: 0.15s;
}
@media (prefers-color-scheme: light) {
  input[type="checkbox"]::before {
    box-shadow: 7px 7px 15px #c3c3c3, 9px 9px 16px rgba(189, 189, 189, 0.6);
  }
}
@media (prefers-color-scheme: dark) {
  input[type="checkbox"]::before {
    box-shadow: -8px -4px 8px 0px rgba(255, 255, 255, 0.05), 8px 4px 12px 0px rgba(0, 0, 0, 0.5);
  }
}
input[type="checkbox"]::after {
  content: "";
  position: absolute;
  font-size: 1.625rem;
  top: 1px;
  right: 1.5625rem;
  color: var(--text-secondary);
  font-family: "SF Pro Text", "Helvetica Neue", "Helvetica", "Arial", sans-serif;
  font-weight: 400;
  letter-spacing: 0.004em;
}
input[type="checkbox"]:checked {
  background: #0071e3;
}
@media (prefers-color-scheme: light) {
  input[type="checkbox"]:checked {
    box-shadow: 9px 9px 16px rgba(189, 189, 189, 0.6), -9px -9px 16px rgba(255, 255, 255, 0.5), inset 10px 10px 15px -10px #0047b9, inset -10px -10px 15px -10px #0047b9;
	border:2px solid #E3E5E9;
	background:#94F1EB;
  }
}
input[type="checkbox"]:checked::before {
  left: 45px;
  box-shadow: none;
}
input[type="checkbox"]:checked::after {
  content: "\f00c";
  left: 30px;
  color:#088212; /*#f5f5f7;*/
  position:relative;
  font-size:15px;
  font-family: FontAwesome;
  top:4px;
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
							<div class="row">
								<input type="hidden" name="max_group" id="max_group" value="1" />
								
								
								
								
								
								
								<div class="box-container box-container-lg" align="center">
									<!--<div class="div2">&nbsp;</div>-->
									<div class="div12">
										<div class="card cabox">
											<div class="face-static">
												<div class="card-header inkblue-card" align="left">&nbsp;Work Progressive Status <span id="CourseChartDuration"></span></div>
												<div class="card-body padding-1 ChartCard" id="CourseChart">
													<div class="divrowbox pt-2">
														<div class="table-responsive dt-responsive ResultTable">
															<div class="table-responsive dt-responsive rtabdiv" id="table-stmt" style="height:420px; overflow:auto">
																<table id="example" class="display rtable mgtb-8" style="width:100%">
																	<thead>
																		<tr>
																			<th>SNo.</th>
																			<th>Name of the Work</th>
																			<th nowrap="nowrap">Release of NIT</th>
																			<th>Prebid Meetings</th>
																			<th>Part-I Recommendation</th>
																			<th>Part-II Recommendation</th>
																			<th>WO Released</th>
																			<th>&nbsp;</th>
																		</tr>
																	</thead>
																	<tbody>
																	<?php 
																	$Sno = 1;
																	$SelectQuery1 = "select * from works where active = 1 and (wo_date = '0000-00-00' OR wo_date IS NULL OR wo_date = '')";
																	$SelectSql1 = mysqli_query($dbConn,$SelectQuery1);
																	if($SelectSql1 == true){
																		if(mysqli_num_rows($SelectSql1)>0){
																			while($WorkList = mysqli_fetch_object($SelectSql1)){
																	?>	
																		<tr>
																			<td align="center"><?php echo $Sno; ?></td>
																			<td align="justify"><?php echo $WorkList->work_name; ?></td>
																			<td align="center" style="vertical-align:middle"><input type="checkbox" name="ch_nit_rel" id="ch_nit_rel<?php echo $WorkList->globid; ?>" value="<?php echo $WorkList->globid; ?>" <?php if($WorkList->is_nit_rel == "Y"){ ?> checked="checked"<?php } ?> disabled="disabled"></td>
																			<td align="center" style="vertical-align:middle"><input type="checkbox" name="ch_prebid_meet" id="ch_prebid_meet<?php echo $WorkList->globid; ?>" value="<?php echo $WorkList->globid; ?>" <?php if($WorkList->is_prebid_meet == "Y"){ ?> checked="checked"<?php } ?>></td>
																			<td align="center" style="vertical-align:middle"><input type="checkbox" name="ch_part1_recom" id="ch_part1_recom<?php echo $WorkList->globid; ?>" value="<?php echo $WorkList->globid; ?>" <?php if($WorkList->is_part1_recom == "Y"){ ?> checked="checked"<?php } ?>></td>
																			<td align="center" style="vertical-align:middle"><input type="checkbox" name="ch_part2_recom" id="ch_part2_recom<?php echo $WorkList->globid; ?>" value="<?php echo $WorkList->globid; ?>" <?php if($WorkList->is_part2_recom == "Y"){ ?> checked="checked"<?php } ?>></td>
																			<td align="center" style="vertical-align:middle"><input type="checkbox" name="ch_wo_rel" id="ch_wo_rel<?php echo $WorkList->globid; ?>" value="<?php echo $WorkList->globid; ?>" <?php if($WorkList->is_wo_rel == "Y"){ ?> checked="checked"<?php } ?> disabled="disabled"></td>
																			<td align="center" style="vertical-align:middle"><input type="button" class="btn btn-info Save" id="<?php echo $WorkList->globid; ?>" value="Save" id="toggle"></td>
																		</tr>
																	<?php $Sno++; } } } ?>	
																	</tbody>
																</table>
																
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!--<div class="div2">&nbsp;</div>-->
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
<script src="js/CommonJSLibrary.js"></script>
<script type="text/javascript" language="javascript">
$(function(){
	var msg = "<?php echo $msg; ?>";
	/*$('#example').DataTable( {
        dom: 'lBfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
		lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
        ],
    } );*/
	/*var table = $('#example').DataTable( {
		scrollY:        "300px",
		scrollX:        true,
		scrollCollapse: true,
		paging:         false
	} );
	new $.fn.dataTable.FixedColumns( table );*/
	document.querySelector('#top').onload = function(){
		if(msg != ""){
			//BootstrapDialog.alert(msg);
			BootstrapDialog.show({
				title: 'Information',
				closable: false,
				message: msg,
				buttons: [{
					label: '&nbsp; OK &nbsp;',
					action: function(dialog) {
						$(location).attr("href","DescriptionGroupCreate.php");
					}
				}]
			});
		}
	};
	$('body').on("click",".Save", function(e){ 
		var Id 			= $(this).attr("id");
		var NitRel 		= $("#ch_nit_rel"+Id).val();
		var PreBidMeet 	= $("#ch_prebid_meet"+Id).val();
		var Part1Recom 	= $("#ch_part1_recom"+Id).val();
		var Part2Recom 	= $("#ch_part2_recom"+Id).val();
		var WoRel 		= $("#ch_wo_rel"+Id).val();
		if($('#ch_nit_rel'+Id).is(":checked")){ NitRel = "Y"; }
		if($('#ch_prebid_meet'+Id).is(":checked")){ PreBidMeet = "Y"; }
		if($('#ch_part1_recom'+Id).is(":checked")){ Part1Recom = "Y"; }
		if($('#ch_part2_recom'+Id).is(":checked")){ Part2Recom = "Y"; }
		if($('#ch_wo_rel'+Id).is(":checked")){ WoRel = "Y"; }
		$.ajax({ 
			type: 'POST', 
			url: 'UpdateWorkProgress.php', 
			contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			data: {Id: Id, NitRel: NitRel, PreBidMeet: PreBidMeet, Part1Recom: Part1Recom, Part2Recom: Part2Recom, WoRel: WoRel }, 
			success: function (data) {  
				if(data != null){ 
					BootstrapDialog.alert(data);
				}
			}
		});
	});
	
});
if(window.history.replaceState ) {
	window.history.replaceState( null, null, window.location.href );
}
</script>
<style>
	.tboxclass{
		width:99%;
	}
	.chosen-container-single .chosen-single{
		padding: 3px 4px;
	}
	
	div.dt-buttons{
		padding-left: 5px;
	}
	table.dataTable thead > tr > th.sorting::before{
		bottom: 0%;
		content: "";
	}
	table.dataTable thead > tr > th.sorting::after{
		top: 0%;
		content: "";
	}
	.modal-header{
		padding: 6px;
	}
	.bootstrap-dialog .bootstrap-dialog-title{
		font-size: 13px;
	}
	.close{
		font-size: 16px;
	}
	th.tabtitle{
		text-align:left !important;
	}
	:root {
  --body: #fafafa;
  --text-default: #1d1d1f;
  --text-secondary: #86868b;
}
@media (prefers-color-scheme: light) {
  :root {
    --body: #fafafa;
    --text-default: #1d1d1f;
  }
}





/* Checkbox Styles */
/*input[type="checkbox"] {
  -webkit-appearance: none;
  outline: none;
  height: 30px;
  width: 80px;
  border: 5px solid var(--body);
  border: 2px solid #E1E4E8;
  border-radius: 2rem;
  cursor: pointer;
  box-shadow: 9px 9px 16px rgba(189, 189, 189, 0.6), -9px -9px 16px rgba(255, 255, 255, 0.5), inset 10px 10px 15px -10px #c3c3c3, inset -10px -10px 15px -10px #fff;
  margin:0px;
}
@media (prefers-color-scheme: light) {
  input[type="checkbox"] {
    box-shadow: 9px 9px 16px rgba(189, 189, 189, 0.6), -9px -9px 16px rgba(255, 255, 255, 0.5), inset 10px 10px 15px -10px #c3c3c3, inset -10px -10px 15px -10px #fff;
  }
}
@media (prefers-color-scheme: dark) {
  input[type="checkbox"] {
    box-shadow: -8px -4px 8px 0px rgba(255, 255, 255, 0.05), 8px 4px 12px 0px rgba(0, 0, 0, 0.5), inset -4px -4px 4px 0px rgba(255, 255, 255, 0.05), inset 4px 4px 4px 0px rgba(0, 0, 0, 0.5);
  }
}
input[type="checkbox"]::before {
  height: 15px;
  width: 15px;
  background-color: var(--body);
  position: absolute;
  margin: auto;
  top: 0;
  left: 15px;
  bottom: 0;
  border-radius: 50%;
  box-shadow: 7px 7px 15px #c3c3c3, 9px 9px 16px rgba(189, 189, 189, 0.6);
  transition: 0.15s;
}
@media (prefers-color-scheme: light) {
  input[type="checkbox"]::before {
    box-shadow: 7px 7px 15px #c3c3c3, 9px 9px 16px rgba(189, 189, 189, 0.6);
  }
}
@media (prefers-color-scheme: dark) {
  input[type="checkbox"]::before {
    box-shadow: -8px -4px 8px 0px rgba(255, 255, 255, 0.05), 8px 4px 12px 0px rgba(0, 0, 0, 0.5);
  }
}
input[type="checkbox"]::after {
  content: "";
  position: absolute;
  font-size: 1.625rem;
  top: 1px;
  right: 1.5625rem;
  color: var(--text-secondary);
  font-family: "SF Pro Text", "Helvetica Neue", "Helvetica", "Arial", sans-serif;
  font-weight: 400;
  letter-spacing: 0.004em;
}
input[type="checkbox"]:checked {
  background: #0071e3;
}
@media (prefers-color-scheme: light) {
  input[type="checkbox"]:checked {
    box-shadow: 9px 9px 16px rgba(189, 189, 189, 0.6), -9px -9px 16px rgba(255, 255, 255, 0.5), inset 10px 10px 15px -10px #0047b9, inset -10px -10px 15px -10px #0047b9;
	border:2px solid #000;
  }
}
input[type="checkbox"]:checked::before {
  left: 45px;
  box-shadow: none;
}
input[type="checkbox"]:checked::after {
  content: "c";
  left: 150px;
  color: #f5f5f7;
  position: absolute;
}*/
/*@charset "UTF-8";
input[type=checkbox] {
  position: absolute;
  visibility: hidden;
  width: 60px;
  height: 60px;
  z-index: 2;
}

.checkbox {
  position: relative;
}

label {
  position: relative;
  display: inline-block;
  width: 20px;
  height: 20px;
  background: #efefef;
  border: 4px solid #aaaaaa;
  cursor: pointer;
  transition: all 0.3s ease-out;
}
.checkbox label{
	padding: 0px 0px 0px 0px;
}
input[type=checkbox]:checked + label {
  border: 4px solid #3d9970;
  animation: confirm 0.15s linear;
}

input[type=checkbox]:checked + label:after {
  content: "\f00c";
  font-family: FontAwesome;
  font-size: 35px;
  line-height: 50px;
  width: 50px;
  height: 50px;
  background: #2ECC40;
  position: absolute;
  top: 0px;
  left: 0px;
  color: #ffffff;
}

@keyframes confirm {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(0.95);
  }
  75% {
    transform: scale(0.8);
  }
  100% {
    transform: scale(1);
  }
}*/
</style>
<!--<script src="//cdn.jsdelivr.net/mojs/latest/mo.min.js"></script>
<script>
const square = new mojs.Shape({
  radius: 70,
  radiusY: 70,
  shape: 'rect',
  stroke: '#2ECC40',
  strokeWidth: { 10: 50 },
  fill: 'none',
  scale: { 0.45: 0.55 },
  opacity: { 1: 0 },
  duration: 350,
  easing: 'sin.out',
  isShowEnd: false });


const lines = new mojs.Burst({
  left: 0, top: 0,
  radius: { 35: 75 },
  angle: 0,
  count: 8,
  children: {
    shape: 'line',
    radius: 10,
    scale: 1,
    stroke: '#2Ecc40',
    strokeDasharray: '100%',
    strokeDashoffset: { '-100%': '100%' },
    duration: 700,
    easing: 'quad.out' } });



const check = document.querySelector('label');
let checked = check.checked;


function fireBurst(e) {
  if (!checked) {
    const pos = this.getBoundingClientRect();

    const timeline = new mojs.Timeline({ speed: 1.5 });

    timeline.add(square, lines);

    square.tune({
      'left': pos.left + 35,
      'top': pos.top + 35 });

    lines.tune({
      x: pos.left + 35,
      y: pos.top + 35 });


    if ("vibrate" in navigator) {
      navigator.vibrate = navigator.vibrate || navigator.webkitVibrate || navigator.mozVibrate || navigator.msVibrate;

      navigator.vibrate([100, 200, 400]);
    }

    timeline.play();
  }
  checked = !checked;

}

check.addEventListener('click', fireBurst);
</script>-->
