<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
if (isset($_POST['btn_update_x']))
{
	$sql_update="update emd_values set emd='" . $_POST['txt_emd'] . "' ,
									  sd='" . $_POST['txt_sd'] . "' ,
									  sd_above5='" . $_POST['txt_sd_above5'] . "' ,
									  pbg='" . $_POST['txt_pbg'] . "' ,
									  cond='" . $_POST['txt_cond'] . "',
									  st='" . $_POST['txt_st'] . "'";
									  
									   
	//echo $sql_update;
	$rs_update=mysqli_query($dbConn,$sql_update,$conn);
	

	$sql_update_a="update cost_tender set value2='" . $_POST['txt_value_a2'] . "' ,
									  default_value='" . $_POST['txt_value_a3'] . "' ,
									  percentage='" . $_POST['txt_value_a4'] . "' ,
									  cost_tender='" . $_POST['txt_l1'] . "'
									  where sr_no='1'";
	$rs_update_a=mysqli_query($dbConn,$sql_update_a,$conn);
	
	
	$sql_update_b="update cost_tender set value1='" . $_POST['txt_value_b1'] . "' ,
									  value2='" . $_POST['txt_value_b2'] . "' ,
									  default_value='" . $_POST['txt_value_b3'] . "' ,
									  percentage='" . $_POST['txt_value_b4'] . "' ,
									  cost_tender='" . $_POST['txt_l2'] . "'
									  where sr_no='2'";
	$rs_update_b=mysqli_query($dbConn,$sql_update_b,$conn);
	
	
	$sql_update_c="update cost_tender set value1='" . $_POST['txt_value_c1'] . "' ,
									  value2='" . $_POST['txt_value_c2'] . "' ,
									  default_value='" . $_POST['txt_value_c3'] . "' ,
									  percentage='" . $_POST['txt_value_c4'] . "' ,
									  cost_tender='" . $_POST['txt_l3'] . "'
									  where sr_no='3'";
	$rs_update_c=mysqli_query($dbConn,$sql_update_c,$conn);
	
	
	$sql_update_d="update cost_tender set value1='" . $_POST['txt_value_d1'] . "' ,
									  default_value='" . $_POST['txt_value_d3'] . "' ,
									  percentage='" . $_POST['txt_value_d4'] . "' ,
									  cost_tender='" . $_POST['txt_l4'] . "'
									  where sr_no='4'";
	$rs_update_d=mysqli_query($dbConn,$sql_update_d,$conn);
	

	if($rs_update!="")
	{
		?>
		<script type="text/javascript" language="javascript">
			alert("Successfully Updated")
		</script>
		<?php
	}
	$rs_update='';
}


$sql_emd="select * from emd_values";
$rs_emd=mysqli_query($dbConn,$sql_emd,$conn);

$sql_cost_tender_a="select * from cost_tender where sr_no='1'";
$rs_cost_tender_a=mysqli_query($dbConn,$sql_cost_tender_a,$conn);

$sql_cost_tender_b="select * from cost_tender where sr_no='2'";
$rs_cost_tender_b=mysqli_query($dbConn,$sql_cost_tender_b,$conn);

$sql_cost_tender_c="select * from cost_tender where sr_no='3'";
$rs_cost_tender_c=mysqli_query($dbConn,$sql_cost_tender_c,$conn);

$sql_cost_tender_d="select * from cost_tender where sr_no='4'";
$rs_cost_tender_d=mysqli_query($dbConn,$sql_cost_tender_d,$conn);

?>
<script type="text/javascript" language="javascript">
function autocal1()
{
a=Math.round(parseFloat(document.getElementById("txt_value_a3").value)+parseFloat(((document.getElementById("txt_value_a3").value)*(document.getElementById("txt_value_a4").value))/100));
document.form.txt_l1.value=a;
//alert(a)
}
function autocal2()
{
b=Math.round(parseFloat(document.getElementById("txt_value_b3").value)+parseFloat(((document.getElementById("txt_value_b3").value)*(document.getElementById("txt_value_b4").value))/100));
document.form.txt_l2.value=b;
//alert(b)
}
function autocal3()
{
c=Math.round(parseFloat(document.getElementById("txt_value_c3").value)+parseFloat(((document.getElementById("txt_value_c3").value)*(document.getElementById("txt_value_c4").value))/100));
document.form.txt_l3.value=c;
//alert(c)
}
function autocal4()
{
d=Math.round(parseFloat(document.getElementById("txt_value_d3").value)+parseFloat(((document.getElementById("txt_value_d3").value)*(document.getElementById("txt_value_d4").value))/100));
document.form.txt_l4.value=d;
//alert(d)
}

</script>
<?php 
//checkUser();
$msg = ""; $del = 0;
$RowCount =0;
$staffid = $_SESSION['sid'];
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>	
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
							

<table width="625" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
	<tr><td width="34">&nbsp;</td></tr>

	<tr class="label">
		<td width="34">&nbsp;</td>
		<td width="188" class="labelbold">EMD</td>
	  <td width="100" align="left" class="text"><input type="text" class="text" name="txt_emd" id="txt_emd" value="<?php echo @mysqli_result($rs_emd,0,'emd'); ?>" size="5" />(%)</td>
		<td width="186" class="labelbold">Contingency</td>
	  	<td class="text"><input type="text" class="text" name="txt_cond" id="txt_cond" value="<?php echo @mysqli_result($rs_emd,0,'cond'); ?>" size="5" />(%)</td>
	</tr>
	
	<tr><td width="34">&nbsp;</td></tr>
	
	<tr class="label">
		<td width="34">&nbsp;</td>
		<td width="188" class="labelbold">SD (For below 5 Lakhs)</td>
	  <td width="100" align="left" class="text"><input type="text" class="text" name="txt_sd" id="txt_sd" value="<?php echo @mysqli_result($rs_emd,0,'sd'); ?>" size="5" />(%)</td>
		<td width="186" class="labelbold">SD (For Above 5 Lakhs)</td>
	  <td width="115" align="left" class="text"><input type="text" class="text" name="txt_sd_above5" id="txt_sd_above5" value="<?php echo @mysqli_result($rs_emd,0,'sd_above5'); ?>" size="5" />(%)</td>
	</tr>

	<tr><td width="34">&nbsp;</td></tr>
	
	<tr class="label">
		<td width="34">&nbsp;</td>
		<td width="188" class="labelbold">PBG</td>
	  	<td class="text" ><input type="text" class="text" name="txt_pbg" id="txt_pbg" value="<?php echo @mysqli_result($rs_emd,0,'pbg'); ?>" size="5" />(%)</td>
		<td width="188" class="labelbold">Service Tax</td>
	  	<td class="text" colspan="3"><input type="text" class="text" name="txt_st" id="txt_st" value="<?php echo @mysqli_result($rs_emd,0,'st'); ?>" size="5" />(%)</td>
	</tr>
	
	<tr><td colspan="5">&nbsp;</td></tr>
	
	<tr><td colspan="5">&nbsp;</td></tr>
	
	<tr>
		<td colspan="5" class="labelboldcenter">Cost of Tender
			<table width="575" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
				<tr><td width="41">&nbsp;</td></tr>
				
				<tr class="label">
					<td width="41" align="center">I</td>
					<td width="68" class="labelbold">Up To</td>
					<td width="49" align="center">-</td>
					<td width="104" align="left" class="text"><input type="text" class="text" name="txt_value_a2" id="txt_value_a2" value="<?php echo @mysqli_result($rs_cost_tender_a,0,'value2'); ?>" size="10" /></td>
					<td width="35" align="left" class="text"><input type="text" class="text" name="txt_value_a3" id="txt_value_a3" value="<?php echo @mysqli_result($rs_cost_tender_a,0,'default_value'); ?>" size="4" onBlur="autocal1()" /></td>
					<td width="40" class="label"align="center">+</td>
					<td width="69" align="left" class="text"><input type="text" class="text" name="txt_value_a4" id="txt_value_a4" value="<?php echo @mysqli_result($rs_cost_tender_a,0,'percentage'); ?>" size="2" onBlur="autocal1()" />% </td>
					<td width="92" class="label"align="center">=</td>
					<td width="95" class="label"><input type="text" class="text" name="txt_l1" id="txt_l1" value="<?php echo @mysqli_result($rs_cost_tender_a,0,'cost_tender'); ?>" size="4" /></td>
				</tr>
				
				<tr><td width="41">&nbsp;</td></tr>
				
				<tr class="label">
					<td width="41" align="center">II</td>
					<td width="68" align="left" class="text"><input type="text" class="text" name="txt_value_b1" id="txt_value_b1" value="<?php echo @mysqli_result($rs_cost_tender_b,0,'value1'); ?>" size="10" /></td>
					<td width="49" align="center" class="label">To</td>
					<td width="104" align="left" class="text"><input type="text" class="text" name="txt_value_b2" id="txt_value_b2" value="<?php echo @mysqli_result($rs_cost_tender_b,0,'value2'); ?>" size="10" /></td>
					<td width="35" align="left" class="text"><input type="text" class="text" name="txt_value_b3" id="txt_value_b3" value="<?php echo @mysqli_result($rs_cost_tender_b,0,'default_value'); ?>" size="4" onBlur="autocal2()" /></td>
					<td width="40" class="label"align="center">+</td>
					<td width="69" align="left" class="text"><input type="text" class="text" name="txt_value_b4" id="txt_value_b4" value="<?php echo @mysqli_result($rs_cost_tender_b,0,'percentage'); ?>" size="2" onBlur="autocal2()" />%</td>
					<td width="92"  class="label"align="center">=</td>
					<td class="label"><input type="text" class="text" name="txt_l2" id="txt_l2" value="<?php echo @mysqli_result($rs_cost_tender_b,0,'cost_tender');  ?>" size="4" /></td>
				</tr>
				
				<tr><td width="41">&nbsp;</td></tr>
				
				<tr class="label">
					<td width="41" align="center">III</td>
					<td width="68" align="left" class="text"><input type="text" class="text" name="txt_value_c1" id="txt_value_c1" value="<?php echo @mysqli_result($rs_cost_tender_c,0,'value1'); ?>" size="10" /></td>
					<td width="49" align="center" class="label">To</td>
					<td width="104" align="left" class="labelbold"><input type="text" class="text" name="txt_value_c2" id="txt_value_c2" value="<?php echo @mysqli_result($rs_cost_tender_c,0,'value2'); ?>" size="10" /></td>
					<td width="35" align="left" class="text"><input type="text" class="text" name="txt_value_c3" id="txt_value_c3" value="<?php echo @mysqli_result($rs_cost_tender_c,0,'default_value'); ?>" size="4" onBlur="autocal3()" /></td>
					<td width="40" class="label"align="center">+</td>
					<td width="69" align="left" class="text"><input type="text" class="text" name="txt_value_c4" id="txt_value_c4" value="<?php echo @mysqli_result($rs_cost_tender_c,0,'percentage'); ?>" size="2" onBlur="autocal3()" />%</td>
					<td width="92" class="label"align="center">=</td>
					<td class="label"><input type="text" class="text" name="txt_l3" id="txt_l3" value="<?php echo @mysqli_result($rs_cost_tender_c,0,'cost_tender'); ?>" size="4" /></td>
				</tr>
				
				<tr><td width="41">&nbsp;</td>
				
				<tr class="label">
					<td width="41" align="center">IV</td>
					<td width="68" align="left" class="text"><input type="text" class="text" name="txt_value_d1" id="txt_value_d1" value="<?php echo @mysqli_result($rs_cost_tender_d,0,'value1'); ?>" size="10" /></td>
					<td width="49" align="center" class="label">& </td>
					<td width="104" align="left" class="labelbold">Above</td>
					<td width="35" align="left" class="text"><input type="text" class="text" name="txt_value_d3" id="txt_value_d3" value="<?php echo @mysqli_result($rs_cost_tender_d,0,'default_value'); ?>" size="4" onBlur="autocal4()"  /></td>
					<td width="40"  class="label"align="center">+</td>
					<td width="69" align="left" class="text"><input type="text" class="text" name="txt_value_d4" id="txt_value_d4" value="<?php echo @mysqli_result($rs_cost_tender_d,0,'percentage'); ?>" size="2" onBlur="autocal4()"  />%</td>
					<td width="92"  class="label"align="center">=</td>
					<td class="label"><input type="text" class="text" name="txt_l4" id="txt_l4" value="<?php echo @mysqli_result($rs_cost_tender_d,0,'cost_tender'); ?>" size="4" /></td>
				</tr>
				
				<tr><td width="41">&nbsp;</td></tr>
			</table>	
		</td>
	</tr>
	
	<tr><td width="34">&nbsp;</td></tr>
	
	<tr>
		<td align="center" colspan="5">
			<input type="image" name="btn_update" id="btn_update" value="Update" src="Buttons/Update_Normal.png" onMouseOver="this.src='Buttons/Update_Over.png'" onMouseOut="this.src='Buttons/Update_Normal.png'" />
		</td>
	</tr>
	
	<tr><td width="34">&nbsp;</td>
	
</table>
							
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
  $('.dropdown-submenu a.test').on("click", function(e){
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
  });
});
</script>
