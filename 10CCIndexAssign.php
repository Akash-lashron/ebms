<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "library/common.php";
checkUser();
$msg = '';
$YearArr 	= array();
$StartArr 	= 2013;
$EndArr 	= date("Y");
for($i=$StartArr; $i<=$EndArr; $i++){
	array_push($YearArr,$StartArr);
	$StartArr++;
}
function dt_format($ddmmyyyy){
    $dt = explode('/', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
function dt_display($ddmmyyyy){
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
if(isset($_POST['submit'])){
	$YearArr	= $_POST['hid_year'];
	$DescArr	= $_POST['txt_description'];
	$DescIdArr	= $_POST['hid_description'];
	$JanArr		= $_POST['jan'];
	$FebArr		= $_POST['feb'];
	$MarArr		= $_POST['mar'];
	$AprArr		= $_POST['apr'];
	$MayArr		= $_POST['may'];
	$JunArr		= $_POST['jun'];
	$JulArr		= $_POST['jul'];
	$AugArr		= $_POST['aug'];
	$SepArr		= $_POST['sep'];
	$OctArr		= $_POST['oct'];
	$NovArr		= $_POST['nov'];
	$DecArr		= $_POST['dec'];
	$IndIdArr	= $_POST['hid_index_id'];
	$Execute	= 0;
	if(count($YearArr)>0){
		foreach($YearArr as $Key => $Value){
			$Year	= $Value;
			$Desc	= $DescArr[$Key];
			$DescId	= $DescIdArr[$Key];
			$Jan	= $JanArr[$Key];
			$Feb	= $FebArr[$Key];
			$Mar	= $MarArr[$Key];
			$Apr	= $AprArr[$Key];
			$May	= $MayArr[$Key];
			$Jun	= $JunArr[$Key];
			$Jul	= $JulArr[$Key];
			$Aug	= $AugArr[$Key];
			$Sep	= $SepArr[$Key];
			$Oct	= $OctArr[$Key];
			$Nov	= $NovArr[$Key];
			$Dec	= $DecArr[$Key];
			$IndId	= $IndIdArr[$Key];
			if($IndId == ""){
				$InsertQuery	=  "insert into monthly_index set year = '$Year', matid = '', mat_code = '$DescId', mat_category = '10CC', jan = '$Jan', feb = '$Feb', mar = '$Mar', 
									apr = '$Apr', may = '$May', jun = '$Jun', jul = '$Jul', aug = '$Aug', sep = '$Sep', oct = '$Oct', nov = '$Nov', dece = '$Dec', 
									created_by = '".$_SESSION['userid']."', created_on = NOW(), modified_by = '".$_SESSION['userid']."', modified_on = NOW(), active = 1";
				$InsertSql		= mysql_query($InsertQuery);
			}else{
				$InsertQuery	=  "update monthly_index set year = '$Year', matid = '', mat_code = '$DescId', mat_category = '10CC', jan = '$Jan', feb = '$Feb', mar = '$Mar', 
									apr = '$Apr', may = '$May', jun = '$Jun', jul = '$Jul', aug = '$Aug', sep = '$Sep', oct = '$Oct', nov = '$Nov', dece = '$Dec', 
									created_by = '".$_SESSION['userid']."', created_on = NOW(), modified_by = '".$_SESSION['userid']."', modified_on = NOW(), active = 1 where miid = '$IndId'";
				$InsertSql		= mysql_query($InsertQuery);
			}
			//echo $InsertQuery."</br>";
			if($InsertSql == true){
				$Execute++;
			}
		}
	}
	//exit;
	if($Execute > 0){
		$msg = "Monthly Index saved successfully.";
	}else{
		$msg = "Error: Monthly Index not saved. Please try again.";
	}
}
$RowCount = 0;
$SelectQuery1 = "select a.*, b.mat_desc from monthly_index a inner join material b on (a.mat_code = b.mat_code) where a.mat_category = '10CC' order by a.year desc";
$SelectSql1 = mysql_query($SelectQuery1);
if($SelectSql1 == true){
	if(mysql_num_rows($SelectSql1)>0){
		$RowCount = 1;
	}
}
?>
<?php require_once "Header.html"; ?>

<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
.TextBoxDy {
    height: 25px;
    border: 1px solid #98D8FE;
    background-color: white;
	color:#0000cc;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	/*text-align:center;*/
	box-sizing: border-box;
	width:100%;
	
}
.hRow td{
	font-size:11px;
	font-weight:600;
	text-align:center;
	vertical-align:middle;
}
.irow td{
	background-color:#ffffff;
}
.lrow td{
	background-color:#EDEFF2;
}
.trow td{
	background-color:#D8F2FA;
}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
            <div class="title">10 CC Index Assign</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
           				<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
							<div class="container">
								<div class="row clearrow"></div>
								<table width="100%"  bgcolor="#E8E8E8" border="0" cellpadding="0" cellspacing="0" align="center" class="table1" id="IndexAssign">
									<tr class="hRow">
										<td>YEAR</td>
										<td>DESCRIPTION</td>
										<td>JAN</td>
										<td>FEB</td>
										<td>MAR</td>
										<td>APR</td>
										<td>MAY</td>
										<td>JUN</td>
										<td>JUL</td>
										<td>AUG</td>
										<td>SEP</td>
										<td>OCT</td>
										<td>NOV</td>
										<td>DEC</td>
										<td>&nbsp;</td>
									</tr>
									<tr class="irow">
										<td class="labeldisplay">
											<select name="cmb_year0" id="cmb_year0" class="TextBoxDy">
												<option value="">----Select----</option>
												<?php if(count($YearArr)>0){ foreach($YearArr as $Years){
													echo '<option value="'.$Years.'">'.$Years.'</option>';
												} } 
												?>
											</select>
										</td>
										<td class="labeldisplay">
											<select name="cmb_description0" id="cmb_description0" class="TextBoxDy">
												<option value="">----Select----</option>
												<?php echo $objBind->BindEscMaterial('','10CC','ALL'); ?>
											</select>
										</td>
										<td class="labeldisplay" align="center"><input type="text" name="jan0" id="jan0" class="TextBoxDy" size="3px"></td>
										<td class="labeldisplay"><input type="text" name="feb0" id="feb0"  class="TextBoxDy" size="3px"></td>
										<td class="labeldisplay"><input type="text" name="mar0" id="mar0" class="TextBoxDy"  size="3px"></td>
										<td class="labeldisplay"><input type="text" name="apr0" id="apr0" class="TextBoxDy" size="3px"></td>
										<td class="labeldisplay"><input type="text" name="may0" id="may0" class="TextBoxDy" size="3px"></td>
										<td class="labeldisplay"><input type="text" name="jun0" id="jun0" class="TextBoxDy" size="3px"></td>
										<td class="labeldisplay"><input type="text" name="jul0" id="jul0" class="TextBoxDy" size="3px"></td>
										<td class="labeldisplay"><input type="text" name="aug0" id="aug0"  class="TextBoxDy" size="3px"></td>
										<td class="labeldisplay"><input type="text" name="sep0" id="sep0" class="TextBoxDy" size="3px"></td>
										<td class="labeldisplay"><input type="text" name="oct0" id="oct0" class="TextBoxDy" size="3px"></td>
										<td class="labeldisplay"><input type="text" name="nov0" id="nov0" class="TextBoxDy"  size="3px"></td>
										<td class="labeldisplay"><input type="text" name="dec0" id="dec0"  class="TextBoxDy" size="3px"></td>
										<td align="center"><input type="button" class="buttonstyle" name="btn_add" id="btn_add" value=" + " /></td>
									</tr>
									<?php if($RowCount == 1){ while($List1 = mysql_fetch_object($SelectSql1)){ ?>
									<tr class="lrow">
										<td class="labeldisplay">
											<input type="text" name="txt_year[]" class="TextBoxDy" size="3px" value="<?php echo $List1->year; ?>" />
											<input type="hidden" name="hid_year[]" class="TextBoxDy" size="3px" value="<?php echo $List1->year; ?>" />
											<input type="hidden" name="hid_index_id[]" class="TextBoxDy" size="3px" value="<?php echo $List1->miid; ?>" />
										</td>
										<td class="labeldisplay">
											<input type="text" name="txt_description[]" class="TextBoxDy" size="3px" value="<?php echo $List1->mat_desc; ?>" />
											<input type="hidden" name="hid_description[]" class="TextBoxDy" size="3px" value="<?php echo $List1->mat_code; ?>" />
										</td>
										<td class="labeldisplay" align="center"><input type="text" name="jan[]" id="jan" class="TextBoxDy" value="<?php if($List1->jan != 0){ echo $List1->jan; } ?>" size="3px"></td>
										<td class="labeldisplay"><input type="text" name="feb[]" id="feb"  class="TextBoxDy" value="<?php if($List1->feb != 0){ echo $List1->feb; } ?>" size="3px"></td>
										<td class="labeldisplay"><input type="text" name="mar[]" id="mar" class="TextBoxDy" value="<?php if($List1->mar != 0){ echo $List1->mar; } ?>"  size="3px"></td>
										<td class="labeldisplay"><input type="text" name="apr[]" id="apr" class="TextBoxDy" value="<?php if($List1->apr != 0){ echo $List1->apr; } ?>" size="3px"></td>
										<td class="labeldisplay"><input type="text" name="may[]" id="may" class="TextBoxDy" value="<?php if($List1->may != 0){ echo $List1->may; } ?>" size="3px"></td>
										<td class="labeldisplay"><input type="text" name="jun[]" id="jun" class="TextBoxDy" value="<?php if($List1->jun != 0){ echo $List1->jun; } ?>" size="3px"></td>
										<td class="labeldisplay"><input type="text" name="jul[]" id="jul" class="TextBoxDy" value="<?php if($List1->jul != 0){ echo $List1->jul; } ?>" size="3px"></td>
										<td class="labeldisplay"><input type="text" name="aug[]" id="aug"  class="TextBoxDy" value="<?php if($List1->aug != 0){ echo $List1->aug; } ?>" size="3px"></td>
										<td class="labeldisplay"><input type="text" name="sep[]" id="sep" class="TextBoxDy" value="<?php if($List1->sep != 0){ echo $List1->sep; } ?>" size="3px"></td>
										<td class="labeldisplay"><input type="text" name="oct[]" id="oct" class="TextBoxDy" value="<?php if($List1->oct != 0){ echo $List1->oct; } ?>" size="3px"></td>
										<td class="labeldisplay"><input type="text" name="nov[]" id="nov" class="TextBoxDy" value="<?php if($List1->nov != 0){ echo $List1->nov; } ?>"  size="3px"></td>
										<td class="labeldisplay"><input type="text" name="dec[]" id="dec"  class="TextBoxDy" value="<?php if($List1->dece != 0){ echo $List1->dece; } ?>" size="3px"></td>
										<td align="center"><input type="button" class="" name="btn_del" id="btn_del" value=" X " disabled="disabled" /></td>
									</tr>
									<?php } }?>
								</table>
								<div>&nbsp;</div>
								<div align="center">
									<input type="submit" data-type="submit" value=" Save " name="submit" id="submit"/>
								</div>
							</div>
                        </form>
                    </blockquote>
                </div>
            </div>
        </div>
         <!--==============================footer=================================-->
        <?php include "footer/footer.html"; ?>
		<script>
			var msg 	= "<?php echo $msg; ?>";
			var success = "<?php echo $success; ?>";
			if(msg != ""){
				BootstrapDialog.alert(msg);
			}
			$(document).ready(function(){
				$('body').on('click','#btn_add',function(event){
					var YearVal  = $("#cmb_year0").val(); 
					var Year 	 = $("#cmb_year0 option:selected").text();
					var DescId 	 = $("#cmb_description0").val(); 
					var Descrip  = $('#cmb_description0 option:selected').text(); 
					var Jan		 = $('#jan0').val(); 
					var Feb		 = $('#feb0').val(); 
					var Mar		 = $('#mar0').val(); 
					var Apr		 = $('#apr0').val(); 
					var May		 = $('#may0').val(); 
					var Jun		 = $('#jun0').val(); 
					var Jul		 = $('#jul0').val(); 
					var Aug		 = $('#aug0').val(); 
					var Sep		 = $('#sep0').val(); 
					var Oct		 = $('#oct0').val(); 
					var Nov		 = $('#nov0').val();
					var Dec		 = $('#dec0').val();  
					if((YearVal != '')&&(DescId != '')){
						$('#IndexAssign').find('tr:last').after('<tr class="trow"><td><input type="text" name="txt_year[]" class="TextBoxDy disable" size="3px" id="year" value="'+Year+'" /><input type="hidden" name="hid_year[]" id="cmb_year"  class="TextBoxDy" size="3px" value="'+YearVal+'" /><input type="hidden" name="hid_index_id[]" class="TextBoxDy" size="3px" value="" /> </td><td><input type="text" name="txt_description[]" class="TextBoxDy" size="3px" id="description" value="'+Descrip+'" /><input type="hidden" name="hid_description[]" class="TextBoxDy" size="3px" value="'+ DescId+'" /></td><td><input type="text" name="jan[]" class="TextBoxDy" size="3px" id="jan" value="'+Jan+'" /> </td><td><input type="text" name="feb[]" class="TextBoxDy" size="3px" id="feb" value="'+Feb+'" /></td> <td><input type="text" name="mar[]" class="TextBoxDy" size="3px" id="mar" value="'+Mar+'" /> </td><td><input type="text" name="apr[]" class="TextBoxDy" size="3px" id="apr" value="'+Apr+'" /></td> <td><input type="text" name="may[]" class="TextBoxDy" size="3px" id="may" value="'+May+'" /> </td><td><input type="text" name="jun[]" class="TextBoxDy" size="3px" id="jun" value="'+Jun+'"/> </td><td><input type="text" name="jul[]" class="TextBoxDy" size="3px" id="jul" value="'+Jul+'" /> </td><td><input type="text" name="aug[]" class="TextBoxDy" size="3px" id="aug" value="'+Aug+'" /> </td><td><input type="text" name="sep[]" class="TextBoxDy" size="3px" id="sep" value="'+Sep+'" /> </td><td><input type="text" name="oct[]" class="TextBoxDy" size="3px" id="oct" value="'+Oct+'" /> </td><td><input type="text" name="nov[]" class="TextBoxDy" size="3px" id="nov" value="'+Nov+'" /></td><td><input type="text" name="dec[]" class="TextBoxDy" size="3px" id="dec" value="'+Dec+'" /></td><td align="center"><input type="button" class="buttonstyle"  name="btn_del" id="btn_del" value=" X " /></td></tr>');
						$("#cmb_year0").val('');
						$("#cmb_description0").val('');
						$('#jan0').val('');
						$('#feb0').val('');
						$('#mar0').val('');
						$('#apr0').val('');
						$('#may0').val('');
						$('#jun0').val('');
						$('#jul0').val('');  
						$('#aug0').val(''); 
						$('#sep0').val('');
						$('#oct0').val(''); 
						$('#nov0').val(''); 
						$('#dec0').val(''); 
					}
				});
				$("#IndexAssign").on("click", "#btn_del", function() {
					$(this).closest("tr").remove();
				});
			});
		</script>
	</body>
</html>

