<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/common.php';
$msg = '';
$userid = $_SESSION['userid'];
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
//$select_recovery_query 	= 	"select * from recoveries WHERE rid = MAX(rid)";
$select_recovery_query 	= 	"SELECT r1.* FROM recoveries r1 WHERE r1.rid = (select max(r2.rid) from recoveries r2)";
//echo $select_recovery_query;
$select_recovery_sql 		= 	mysql_query($select_recovery_query);
if (isset($_POST["submit"])) 
{
    $wctnoncivil 		= 	trim($_POST['txt_wct_noncivil']);
	$wctcivil 			= 	trim($_POST['txt_wct_civil']);
    $mobadvance 		= 	trim($_POST['txt_mob_advance']);
    $labourwelfare 		= 	trim($_POST['txt_labour_welfare']);
    $incometax 			= 	trim($_POST['txt_incometax']);
    $sd 				= 	trim($_POST['txt_sd']);
    $sdrbn 				= 	trim($_POST['txt_sd_rbn']);
	$watercharge 		= 	trim($_POST['txt_water_charge']);
    $watermaxlevel 		= 	trim($_POST['txt_water_maxlevel']);
	$electricitycharge 	= 	trim($_POST['txt_electricity_charge']);
	//$landrent 			= 	trim($_POST['txt_land_rent']);
	//$liquiddamage 		= 	trim($_POST['txt_liquid_damage']);
	//$interestma 		= 	trim($_POST['txt_interest_ma']);
	//$otherrecovery 		= 	trim($_POST['txt_other_recovery']);
    $recovery_sql 			= 	"INSERT INTO recoveries set
                                            wct_noncivil = '$wctnoncivil',
                                            wct_civil = '$wctcivil',
											mob_advance = '$mobadvance',
                                            labour_welfare = '$labourwelfare',
                                            incometax = '$incometax',
                                            sd = '$sd',
                                            sd_rbn = '$sdrbn',
											water_charge = '$watercharge',
                                            water_maxlevel = '$watermaxlevel',
											electricity_charge = '$electricitycharge',
											userid = '$userid',
											modifieddate = NOW()";
	//echo $recovery_sql;
    $recovery_query 	= 	mysql_query($recovery_sql);
    if($recovery_query == true) 
	{
        $msg = "Agreement Details Stored Successfully ";
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
.labledescription
{
	font-size:11px;
}
</style>
 <script>
  	 function goBack()
	 {
	   	url = "dashboard.php";
		window.location.replace(url);
	 }
</script>
<script>
   $(function () {
        $( "#txt_initial_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd-mm-yy",
                    maxDate: new Date,
                    defaultDate: new Date,
                });
		$( "#txt_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd-mm-yy",
                    maxDate: new Date,
                    defaultDate: new Date,
                });	
        $.fn.validatewctnoncivil = function(event) { 
					if($("#txt_wct_noncivil").val()==""){ 
					var a="Please Enter WCT %";
					$('#val_wct_noncivil').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_wct_noncivil').text(a);
					}
				}
		$.fn.validatewctcivil = function(event) { 
					if($("#txt_wct_civil").val()==""){ 
					var a="Please Enter WCT %";
					$('#val_wct_civil').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_wct_civil').text(a);
					}
				}
		$.fn.validatemobadvance = function(event) { 
					if($("#txt_mob_advance").val()==""){ 
					var a="Enter this field";
					$('#val_mob_advance').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_mob_advance').text(a);
					}
				}
		$.fn.validatelabourwelfare = function(event) { 
					if($("#txt_labour_welfare").val()==""){ 
					var a="Enter this field";
					$('#val_labour_welfare').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_labour_welfare').text(a);
					}
				}
		$.fn.validateincometax = function(event) { 
					if($("#txt_incometax").val()==""){ 
					var a="Please Enter Income Tax";
					$('#val_incometax').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_incometax').text(a);
					}
				}
		$.fn.validatesd = function(event) { 
					if($("#txt_sd").val()==""){ 
					var a="Please Enter S.D.";
					$('#val_sd').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_sd').text(a);
					}
				}
		$.fn.validatesdrbn = function(event) { 
					if($("#txt_sd_rbn").val()==""){ 
					var a="Please Enter S.D. for RBN";
					$('#val_sd_rbn').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_sd_rbn').text(a);
					}
				}			
		$.fn.vaidatewatercharge = function(event) { 
					if($("#txt_water_charge").val()==""){ 
					var a="Please Enter Water Charge";
					$('#val_water_charge').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_water_charge').text(a);
					}
				}
		$.fn.vaidatewatermaxlevel = function(event) { 
					if($("#txt_water_maxlevel").val()==""){ 
					var a="Please Enter Limit";
					$('#val_water_maxlevel').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_water_maxlevel').text(a);
					}
				}
		$.fn.vaidateelectricitycharge = function(event) { 
					if($("#txt_electricity_charge").val()==""){ 
					var a="Please Enter Electricity Charge";
					$('#val_electricity_charge').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_electricity_charge').text(a);
					}
				}
		/*$.fn.vaidatelandrate = function(event) { 
					if($("#txt_land_rent").val()==""){ 
					var a="Please Enter Land Rate";
					$('#val_land_rent').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_land_rent').text(a);
					}
				}		
		$.fn.vaidateliquiddamage = function(event) { 
					if($("#txt_liquid_damage").val()==""){ 
					var a="Please Enter Liquidated Damage";
					$('#val_liquid_damage').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_liquid_damage').text(a);
					}
				}	
		$.fn.vaidateinterestma = function(event) { 
					if($("#txt_interest_ma").val()==""){ 
					var a="Please Mobilization Interest";
					$('#val_interest_ma').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_interest_ma').text(a);
					}
				}
		$.fn.vaidateotherrecovery = function(event) { 
					if($("#txt_other_recovery").val()==""){ 
					var a="Please Enter Other Recovery";
					$('#val_other_recovery').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_other_recovery').text(a);
					}
				}*/		
		$("#txt_wct_noncivil").keyup(function(event){
		$(this).validatewctnoncivil(event);
		});
		$("#txt_wct_civil").keyup(function(event){
		$(this).validatewctcivil(event);
		});
		$("#txt_mob_advance").keyup(function(event){
		$(this).validatemobadvance(event);
		});
		$("#txt_labour_welfare").keyup(function(event){
		$(this).validatelabourwelfare(event);
		});
		$("#txt_incometax").keyup(function(event){
		$(this).validateincometax(event);
		});
		$("#txt_sd").keyup(function(event){
		$(this).validatesd(event);
		});
		$("#txt_sd_rbn").keyup(function(event){
		$(this).validatesdrbn(event);
		});
		$("#txt_water_charge").keyup(function(event){
		$(this).vaidatewatercharge(event);
		});
		$("#txt_water_maxlevel").keyup(function(event){
		$(this).vaidatewatermaxlevel(event);
		});
		$("#txt_electricity_charge").keyup(function(event){
		$(this).vaidateelectricitycharge(event);
		});
		/*$("#txt_land_rent").keyup(function(event){
		$(this).vaidatelandrate(event);
		});
		$("#txt_liquid_damage").keyup(function(event){
		$(this).vaidateliquiddamage(event);
		});
		$("#txt_interest_ma").keyup(function(event){
		$(this).vaidateinterestma(event);
		});
		$("#txt_other_recovery").keyup(function(event){
		$(this).vaidateotherrecovery(event);
		});*/
		
		$("#top").submit(function(event){
		$(this).validatewctnoncivil(event);
		$(this).validatewctcivil(event);
		$(this).validatemobadvance(event);
		$(this).validatelabourwelfare(event);
		$(this).validateincometax(event);
		$(this).validatesd(event);
		$(this).validatesdrbn(event);
		$(this).vaidatewatercharge(event);
		$(this).vaidatewatermaxlevel(event);
		$(this).vaidateelectricitycharge(event);
		//$(this).vaidatelandrate(event);
		//$(this).vaidateliquiddamage(event);
		//$(this).vaidateinterestma(event);
		//$(this).vaidateotherrecovery(event);
		});
   
       });
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
    <body class="page1" id="top" oncontextmenu="return false" onLoad="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="title">Recoveries</div>
                <div class="container_12">
                    <div class="grid_12">

						<!--<div align="right"><a href="AgreementEntryView.php">View</a>&nbsp;&nbsp;&nbsp;</div>-->
                        <blockquote class="bq1" style="overflow:auto">
							<?php
									if($select_recovery_sql == true) 
									{
									
										$List = mysql_fetch_object($select_recovery_sql);
										$wctnoncivil 		= 	$List->wct_noncivil;
										$wctcivil 			= 	$List->wct_civil;
										$mobadvance 		= 	$List->mob_advance;
										$labourwelfare 		= 	$List->labour_welfare;
										$incometax 			= 	$List->incometax;
										$sd 				= 	$List->sd;
										$sdrbn 				= 	$List->sd_rbn;
										$watercharge 		= 	$List->water_charge;
										$watermaxlevel 		= 	$List->water_maxlevel;
										$electricitycharge 	= 	$List->electricity_charge;
										$landrent 			= 	$List->land_rent;
										$liquiddamage 		= 	$List->liquid_damage;
										$interestma 		= 	$List->interest_ma;
										$otherrecovery 		= 	$List->other_recovery;
									}
								
								?>
                        		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
									<tr><td colspan="6" class="label">&nbsp;</td></tr>
									<!--<tr>
										<td>&nbsp;</td>
										<td colspan="5" class="label">Secured Advance</td>
									</tr>-->
									<tr><td colspan="6" class="label">&nbsp;</td></tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td colspan="4" class="label" style="background-color:#D4D4D4">Under 8 (a)</td>
									</tr>
									<tr><td colspan="6" class="label">&nbsp;</td></tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td class="label">WCT (Non Civil)</td>
										<td>
											<input type="text" class="textboxdisplay" name="txt_wct_noncivil" id="txt_wct_noncivil" style="width:70px" value="<?php echo $wctnoncivil; ?>">
											<label class="label labledescription">&nbsp;% of Total work order cost&nbsp;</label>
										</td>
										<td class="label">WCT (Civil)</td>
										<td>
											<input type="text" class="textboxdisplay" name="txt_wct_civil" id="txt_wct_civil" style="width:70px" value="<?php echo $wctcivil; ?>">
											<label class="label labledescription">&nbsp; % of Total work order cost&nbsp;</label>
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align="center" class="labeldisplay" id="val_wct_noncivil" style="color:red" colspan="">&nbsp;</td>
										<td>&nbsp;</td>
										<td align="center" class="labeldisplay" id="val_wct_civil" style="color:red" colspan="">&nbsp;</td>
									</tr>
																		<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td class="label">VAT (Non Civil)</td>
										<td>
											<input type="text" class="textboxdisplay" name="txt_vat_noncivil" id="txt_vat_noncivil" style="width:70px" value="<?php echo $wctnoncivil; ?>">
											<label class="label labledescription">&nbsp;% of Total work order cost&nbsp;</label>
										</td>
										<td class="label">VAT (Civil)</td>
										<td>
											<input type="text" class="textboxdisplay" name="txt_vat_civil" id="txt_vat_civil" style="width:70px" value="<?php echo $wctcivil; ?>">
											<label class="label labledescription">&nbsp; % of Total work order cost&nbsp;</label>
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align="center" class="labeldisplay" id="val_vat_noncivil" style="color:red" colspan="">&nbsp;</td>
										<td>&nbsp;</td>
										<td align="center" class="labeldisplay" id="val_vat_civil" style="color:red" colspan="">&nbsp;</td>
									</tr>

									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td class="label">Mobilization Advance</td>
										<td>
											<input type="text" class="textboxdisplay" name="txt_mob_advance" id="txt_mob_advance" style="width:70px" value="<?php echo $mobadvance; ?>">
											<label class="label labledescription">&nbsp;% of Total work order cost&nbsp;</label>
										</td>
										<td class="label">Labour Welfare Cess</td>
										<td>
											<input type="text" class="textboxdisplay" name="txt_labour_welfare" id="txt_labour_welfare" style="width:70px" value="<?php echo $labourwelfare; ?>">
											<label class="label labledescription">&nbsp; % of Total work order cost &nbsp;</label>
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align="center" class="labeldisplay" id="val_mob_advance" style="color:red" colspan="">&nbsp;</td>
										<td>&nbsp;</td>
										<td align="center" class="labeldisplay" id="val_labour_welfare" style="color:red" colspan="">&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td colspan="4" class="label" style="background-color:#D4D4D4">Under 8 (b)</td>
									</tr>
									<tr><td colspan="6" class="label">&nbsp;</td></tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td class="label">Secured Deposit in <br/>Total Work Order Cost</td>
										<td>
											<input type="text" class="textboxdisplay" name="txt_sd" id="txt_sd" style="width:70px" value="<?php echo $sd; ?>">
											<label class="label labledescription">&nbsp;% of Total work order cost&nbsp;</label>
										</td>
										<td class="label">Secured Deposit in <br/>Every RBN</td>
										<td>
											<input type="text" class="textboxdisplay" name="txt_sd_rbn" id="txt_sd_rbn" style="width:70px" value="<?php echo $sdrbn; ?>">
											<label class="label labledescription">&nbsp; % of Total work order cost&nbsp;</label>
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align="center" class="labeldisplay" id="val_sd" style="color:red" colspan="">&nbsp;</td>
										<td>&nbsp;</td>
										<td align="center" class="labeldisplay" id="val_sd_rbn" style="color:red" colspan="">&nbsp;</td>
									</tr>
									<tr>
										<td width="8%">&nbsp;</td>
										<td>&nbsp;</td>
										<td class="label">Income Tax</td>
										<td>
											<input type="text" class="textboxdisplay" name="txt_incometax" id="txt_incometax" style="width:70px" value="<?php echo $incometax; ?>">
											<label class="label labledescription">&nbsp;% of Total work order cost&nbsp;</label>
										</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align="center" class="labeldisplay" id="val_incometax" style="color:red" colspan="">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td width="8%">&nbsp;</td>
										<td>&nbsp;</td>
										<td class="label">IT Cess</td>
										<td>
											<input type="text" class="textboxdisplay" name="txt_itcess" id="txt_itcess" style="width:70px" value="<?php echo $incometax; ?>">
											<label class="label labledescription">&nbsp;% of Total work order cost&nbsp;</label>
										</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align="center" class="labeldisplay" id="val_itcess" style="color:red" colspan="">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
																		<tr>
										<td width="8%">&nbsp;</td>
										<td>&nbsp;</td>
										<td class="label">IT Educational Cess</td>
										<td>
											<input type="text" class="textboxdisplay" name="txt_edu_cess" id="txt_edu_cess" style="width:70px" value="<?php echo $incometax; ?>">
											<label class="label labledescription">&nbsp;% of Total work order cost&nbsp;</label>
										</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align="center" class="labeldisplay" id="val_edu_cess" style="color:red" colspan="">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>

									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td class="label">Water Charges (Rs.)</td>
										<td colspan="3">
											<input type="text" class="textboxdisplay" name="txt_water_charge" id="txt_water_charge" style="width:150px" value="<?php echo $watercharge; ?>">
											<label class="label">&nbsp;&nbsp;/&nbsp;&nbsp;</label>
											<input type="text" class="textboxdisplay" name="txt_water_maxlevel" id="txt_water_maxlevel" style="width:150px" value="<?php echo $watermaxlevel; ?>">
											<label class="label">&nbsp;&nbsp;Litres&nbsp;&nbsp;</label>
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align="center" class="labeldisplay" id="val_water_charge" style="color:red" colspan="">&nbsp;</td>
										<td align="center" class="labeldisplay" id="val_water_maxlevel" style="color:red" colspan="">&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td class="label">Electricity Charges (Rs.)</td>
										<td colspan="3">
											<input type="text" class="textboxdisplay" name="txt_electricity_charge" id="txt_electricity_charge" style="width:150px" value="<?php echo $electricitycharge; ?>">
											<label class="label">&nbsp;&nbsp;/ unit&nbsp;&nbsp;</label>
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align="center" class="labeldisplay" id="val_electricity_charge" style="color:red" colspan="3">&nbsp;</td>
									</tr>
									<!--<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td class="label">Rent for Land (Rs.)</td>
										<td colspan="3">
											<input type="text" class="textboxdisplay" name="txt_land_rent" id="txt_land_rent" style="width:150px" value="<?php echo $landrent; ?>">
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align="center" class="labeldisplay" id="val_land_rent" style="color:red" colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td class="label">Liquidated damages <br/>recoveries (Rs.)</td>
										<td colspan="3">
											<input type="text" class="textboxdisplay" name="txt_liquid_damage" id="txt_liquid_damage" style="width:150px" value="<?php echo $liquiddamage; ?>">
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align="center" class="labeldisplay" id="val_liquid_damage" style="color:red" colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td class="label">Interest on <br/>mobilization Advance (Rs.)</td>
										<td colspan="3">
											<input type="text" class="textboxdisplay" name="txt_interest_ma" id="txt_interest_ma" style="width:150px" value="<?php echo $interestma; ?>">
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align="center" class="labeldisplay" id="val_interest_ma" style="color:red" colspan="3">&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td class="label">Other Recoveries</td>
										<td colspan="3">
											<input type="text" class="textboxdisplay" name="txt_other_recovery" id="txt_other_recovery" style="width:150px" value="<?php echo $otherrecovery; ?>">
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td align="center" class="labeldisplay" id="val_other_recovery" style="color:red" colspan="3">&nbsp;</td>
									</tr>-->

								</table>
                            
									<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
										<div class="buttonsection">
											<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
										</div>
										<div class="buttonsection">
											<input type="submit" name="submit" id="submit" value=" Save "/>
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
