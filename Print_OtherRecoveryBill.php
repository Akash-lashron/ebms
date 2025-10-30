<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/common.php';
$msg = '';
$userid = $_SESSION['userid'];
$staffid = $_SESSION['sid'];
function dt_format($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);

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
    return $dd . '-' . $mm . '-' . $yy;
}
if (isset($_POST["submit"])) 
{
    $sheetid 			= 	trim($_POST['cmb_shortname']);
	$abstract_net_amt 	= 	trim($_POST['txt_abstract_amt']);
    $rbn 				= 	trim($_POST['txt_rbn']);
    $sd_percent 		= 	trim($_POST['txt_sd_perc']);
    $sd_amt 			= 	trim($_POST['txt_sd']);
    $wct_percent 		= 	trim($_POST['txt_wct_perc']);
    $wct_amt 			= 	trim($_POST['txt_wct']);
	$vat_percent 		= 	trim($_POST['txt_vat_perc']);
    $vat_amt 			= 	trim($_POST['txt_vat']);
	$mob_adv_percent 	= 	trim($_POST['txt_mob_adv_perc']);
	$mob_adv_amt 		= 	trim($_POST['txt_mob_adv']);
	$lw_cess_percent 	= 	trim($_POST['txt_lw_cess_perc']);
	$lw_cess_amt 		= 	trim($_POST['txt_lw_cess']);
	$incometax_percent 	= 	trim($_POST['txt_incometax_perc']);
	$incometax_amt 		= 	trim($_POST['txt_incometax']);
	$it_cess_percent 	= 	trim($_POST['txt_ITcess_perc']);
	$it_cess_amt 		= 	trim($_POST['txt_ITcess']);
	$it_edu_percent 	= 	trim($_POST['txt_ITEcess_perc']);
	$it_edu_amt 		= 	trim($_POST['txt_ITEcess']);
	$land_rent 			= 	trim($_POST['txt_rent_land']);
	$liquid_damage 		= 	trim($_POST['txt_liquid_damage']);
	$other_recovery 	= 	trim($_POST['txt_other_recovery']);
    $erecovery_sql 		= 	"update generate_otherrecovery set "
                                            . "sd_percent = '$sd_percent', "
                                            . "sd_amt = '$sd_amt', "
                                            . "wct_percent = '$wct_percent', "
                                            . "wct_amt = '$wct_amt', "
											. "vat_percent = '$vat_percent', "
                                            . "vat_amt = '$vat_amt', "
											. "mob_adv_percent = '$mob_adv_percent', "
											. "mob_adv_amt = '$mob_adv_amt', "
											. "lw_cess_percent = '$lw_cess_percent', "
											. "lw_cess_amt = '$lw_cess_amt', "
											. "incometax_percent = '$incometax_percent', "
											. "incometax_amt = '$incometax_amt', "
											. "it_cess_percent = '$it_cess_percent', "
											. "it_cess_amt = '$it_cess_amt', "
											. "it_edu_percent = '$it_edu_percent', "
											. "it_edu_amt = '$it_edu_amt', "
											. "land_rent = '$land_rent', "
											. "liquid_damage = '$liquid_damage', "
											. "other_recovery = '$other_recovery', "
											. "staffid = '$staffid', "
											. "userid = '$userid', "
											. "modifieddate = NOW(), "
                                            . "active = 1 where sheetid = '$sheetid' and rbn = '$rbn'";
    $erecovery_query 	= 	mysql_query($erecovery_sql);
    if($erecovery_query == true) 
	{
        $msg = "Recovery Details Stored Successfully ";
		$success = 1;
    }
	else
	{
		$msg = " Something Error...!!! ";
	}
} 
?>

  <?php require_once "Header.html"; ?>
<style>
    .textright
	{
		text-align:right;
		padding-right:2px;
	}
</style>
<script>
	   function goBack()
	   {
	   		url = "dashboard.php";
			window.location.replace(url);
	   }
</script>
<script type="text/javascript">
			window.history.forward();
			function noBack() { window.history.forward(); }
		</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">

                <div class="container_12">
                    <div class="grid_12">

						<!--<div align="right"><a href="View_Other_recovery_generate_Bill.php">View</a>&nbsp;&nbsp;&nbsp;</div>-->
                        <blockquote class="bq1">
                            <div class="title">Other Recoveries</div>
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
						<div style="height:630px; overflow-y:scroll;">
                        <table width="1040px" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="18%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Short Name</td> 
                                    <td>
										<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" style="width:465px" onChange="workorderdetail();getabstractamount();">
											<option value="">----------------------- Select Work Short Name ------------------------</option>
											<?php echo $objBind->BindWorkOrderNo(0);?>
										</select>
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_shortname" style="color:red" colspan="">&nbsp;</td></tr>
                                
                                
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order No.</td>
                                    <td><input type="text" name='txt_workorder' id='txt_workorder' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_workorder" style="color:red" colspan="">&nbsp;</td></tr>
								
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Abstract Net Amount (Rs)</td>
                                    <td>
										<input type="text" name='txt_abstract_amt' id='txt_abstract_amt' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;
										<label class="label">RAB No. </label>
										<input type="text" name='txt_rbn' id='txt_rbn' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									</td>
                                </tr>
 								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_abstract_amt"></span>
									<span id="val_rbn"></span>
									&nbsp;
									</td>
								</tr>								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Secured Desposit (Rs.)</td>
                                    <td>
										<input type="text" name='txt_sd_perc' id='txt_sd_perc' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 40px;">
										<label class="label"> % of Net Amount </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_sd' id='txt_sd' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
										
										
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_sd_perc"></span>
									<span id="val_sd"></span>
									&nbsp;
									</td>
								</tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">WCT (Rs.)</td>
                                    <td>
										<input type="text" name='txt_wct_perc' id='txt_wct_perc' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 40px;">
										<label class="label"> % of Net Amount </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_wct' id='txt_wct' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_wct_perc"></span>
									<span id="val_wct"></span>
									&nbsp;
									</td>
								</tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">VAT (Rs.)</td>
                                    <td>
										<input type="text" name='txt_vat_perc' id='txt_vat_perc' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 40px;">
										<label class="label"> % of Net Amount </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_vat' id='txt_vat' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_vat_perc"></span>
									<span id="val_vat"></span>
									&nbsp;
									</td>
								</tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Mobilization Advance (Rs.)</td>
                                    <td>
										<input type="text" name='txt_mob_adv_perc' id='txt_mob_adv_perc' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 40px;">
										<label class="label"> % of Net Amount </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_mob_adv' id='txt_mob_adv' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									</td>
                                </tr>
                                 <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_mob_adv_perc"></span>
									<span id="val_mob_adv"></span>
									&nbsp;
									</td>
								</tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Labour Welfare Cess (Rs.)</td>
                                    <td>
										<input type="text" name='txt_lw_cess_perc' id='txt_lw_cess_perc' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 40px;">
										<label class="label"> % of Net Amount </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_lw_cess' id='txt_lw_cess' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									</td>
                                </tr>
                                 <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_lw_cess_perc"></span>
									<span id="val_lw_cess"></span>
									&nbsp;
									</td>
								</tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Income Tax (Rs.)</td>
                                    <td>
										<input type="text" name='txt_incometax_perc' id='txt_incometax_perc' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 40px;">
										<label class="label"> % of Net Amount </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_incometax' id='txt_incometax' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_incometax_perc"></span>
									<span id="val_incometax"></span>
									&nbsp;
									</td>
								</tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">IT Cess (Rs.)</td>
                                    <td>
										<input type="text" name='txt_ITcess_perc' id='txt_ITcess_perc' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 40px;">
										<label class="label"> % of Income Tax </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_ITcess' id='txt_ITcess' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_ITcess_perc"></span>
									<span id="val_ITcess"></span>
									&nbsp;
									</td>
								</tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">IT Educational Cess (Rs.)</td>
                                    <td>
										<input type="text" name='txt_ITEcess_perc' id='txt_ITEcess_perc' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 40px;">
										<label class="label"> % of Income Tax </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_ITEcess' id='txt_ITEcess' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_ITEcess_perc"></span>
									<span id="val_ITEcess"></span>
									&nbsp;
									</td>
								</tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Rent for Land (Rs.)</td>
                                    <td>
										<input type="text" name='txt_rent_land' id='txt_rent_land' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
										&nbsp;&nbsp;&nbsp;&nbsp;
										<!--<input type="text" name='txt_initial' id='txt_initial' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 40px;">
										<label class="label"> % of Income Tax </label>
										&nbsp;&nbsp;&nbsp;&nbsp;-->
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_rent_land" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Liquidated Damages (Rs.)</td>
                                    <td>
										<input type="text" name='txt_liquid_damage' id='txt_liquid_damage' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
										&nbsp;&nbsp;&nbsp;&nbsp;
										<!--<input type="text" name='txt_initial' id='txt_initial' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 40px;">
										<label class="label"> % of Income Tax </label>
										&nbsp;&nbsp;&nbsp;&nbsp;-->
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_liquid_damage" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Other Recoveries (Rs.)</td>
                                    <td>
										<input type="text" name='txt_other_recovery' id='txt_other_recovery' class="textboxdisplay textright" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
										&nbsp;&nbsp;&nbsp;&nbsp;
										<!--<input type="text" name='txt_initial' id='txt_initial' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 40px;">
										<label class="label"> % of Income Tax </label>
										&nbsp;&nbsp;&nbsp;&nbsp;-->
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_other_recovery" style="color:red" colspan="">&nbsp;</td></tr>
							</table>
							</div>	
                            
									<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
										<div class="buttonsection">
										<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
										</div>
										<div class="buttonsection">
										<?php 
										if($_GET['sheet_id'] != '')
										{ 
										?>
											<input type="submit" name="update" id="update" value=" Update "/>
										<?php
										}
										else
										{
										?>
											<input type="submit" name="submit" id="submit" value=" Submit "/>
										<?php
										}
										?>
										</div>
									</div>
                        </blockquote>
                    </div>

                </div>
            </div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
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
        </form>
    </body>
</html>
