<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
require_once 'library/functions.php';
checkUser();
?>
<script type="text/javascript">
			window.history.forward();
			function noBack() { window.history.forward(); }
		</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">

                <div class="container_12">
                    <div class="grid_12">

						<div align="right"><a href="AgreementEntryView.php">View</a>&nbsp;&nbsp;&nbsp;</div>
                        <blockquote class="bq1" style="overflow-y:scroll">
                            <div class="title">PG Entry</div>
                        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="18%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Choose Section</td> 
                                    <td class="label">
									<input type="radio"  name='section' id='section_civil' class="textboxdisplay" value="I">&nbsp;CIVIL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio"  name='section' id='section_elect' class="textboxdisplay" value="II">&nbsp;ELECTRICAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio"  name='section' id='section_mech' class="textboxdisplay" value="III">&nbsp;MECHANICAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="radio"  name='section' id='section_mhe' class="textboxdisplay" value="IV">&nbsp;MHE
									
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_section" style="color:red" colspan="">&nbsp;</td></tr>
								<tr id="row1" style="display:none">
                                    <td>&nbsp;</td>
                                    <td class="label">Works Under</td> 
                                    <td>
									<select name='civil_workorderno' id='civil_workorderno' class="textboxdisplay" style="width: 465px;">
										<option value="">-------------------- Select Work Short Name -----------------------</option>
										<?php echo $objBind->BindWorkOrderNoListStaff(0);?>
									</select>
									</td>
                                </tr>
                                <tr id="row2" style="display:none"><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_civil_woredrno" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order No.</td> 
                                    <td><input type="text"  name='workorderno' id='workorderno' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $work_order_no; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_woredrno" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Name of Work</td>
                                    <td><textarea name='workname' id='workname' class="textboxdisplay" rows="6" style="width: 465px;"><?php if($_GET['sheet_id'] != ''){ echo $work_name; } ?></textarea></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_wname" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Short Name of Work</td>
                                    <td><input type="text" name='shortname' id='shortname' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_shortname" style="color:red" colspan="">&nbsp;</td></tr>
                                
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Technical Sanction No. </td>
                                    <td><input type="text" name='techsanctionno' id='techsanctionno' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $tech_sanction; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_techsno" style="color:red" colspan="">&nbsp;</td></tr>
								 <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Agreement No.</td>
                                    <td> <input type="text" name='agreementno' id='agreementno' class="textboxdisplay"  value="<?php if($_GET['sheet_id'] != ''){ echo $agree_no; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_aggno" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr> 
                                    <td>&nbsp;</td>
                                    <td class="label">Name of the contractor</td>
                                    <td><input type="text" name='contractorname' id='contractorname' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $name_contractor; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_conname" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Computer Code No. </td>
                                    <td><input type="text" name='computercodeno' id='computercodeno' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $computer_code_no; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_systemcodeno" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Type </td>
                                    <td class="label">
									<?php 
									if($_GET['sheet_id'] != '')
									{ 	
										if($worktype == 1) 
										{ 
											$check1 = 'checked="checked"'; 
											$check2 = "";
										} 
										else
										{
											$check2 = 'checked="checked"'; 
											$check1 = "";
										}
									} 
									else
									{
										$check2 = 'checked="checked"'; 
										$check1 = "";
									}
									?>
										<input type="radio" name="worktype" id="worktype" value="1" <?php echo $check1; ?>>Major Work&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="worktype" id="worktype" value="2" <?php echo $check2; ?>>Minor Work
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_worktype" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order Date </td>
                                    <td><input type="text" name='workorderdate' id='workorderdate' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $work_order_date; } ?>" size="15"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_systemcodeno" style="color:red" colspan="">&nbsp;</td></tr>
                             	<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Rebate Percentage </td>
                                    <td><input type="text" name='rebatepercent' id='rebatepercent' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $rebatepercent; } else { echo 0; } ?>" size="5">&nbsp;&nbsp;( % )</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_rebatepercent" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
									<td colspan="3">&nbsp;</td>
								</tr>
								<!--<tr>
                                    <td colspan="3" height="50px;">
                                <center>
                                    
									<?php 
									/*if($_GET['sheet_id'] != '')
									{ 
									?>
										<input type="submit" name="update" id="update" value=" Update "/>&nbsp;&nbsp;
										<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
									<?php
									}
									else
									{
									?>
										<input type="submit" name="submit" id="submit" value=" Submit "/>&nbsp;&nbsp;
										<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
									<?php
									}*/
									?>
                                </center>
                                </td>
                                </tr>-->
                            
                            
                            <!--<tr><td colspan="3">&nbsp;</td></tr>-->
<!--                            <tr><td width="500" colspan="5" class="green">
                                </td></tr>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr class="labelcenter">
                                <td colspan="5" align="center">&nbsp;

                                </td>
                            </tr>
                            <tr><td colspan="5">&nbsp;</td></tr>-->
                            </table>
                            <!--<div class="col2"><?php //if ($msg != '') { echo $msg; } ?></div>-->
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
           <?php include "footer/footer.html"; ?>
		   <script>
		    	//$("#civil_workorderno").chosen();
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
