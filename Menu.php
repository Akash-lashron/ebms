<?php
if (!defined('WEB_ROOT')) {
	exit;
}
$self = WEB_ROOT . 'login.php';
function ModuleRights($code)
{
	$ModuleRights = $_SESSION['ModuleRights'];
	if($ModuleRights == "")
	{
		logout();
	}
	else if(in_array($code, $ModuleRights)) 
	{
		$LinkClass = "";
	}
	else
	{
		$LinkClass = "style='pointer-events: none; color:#666666'";
	}
	return $LinkClass;
}
if($_SESSION['staff_section'] == 2)
{
	$staffid_acc 			= $_SESSION['sid_acc'];
	if(($staffid_acc != "") && ($staffid_acc != 0))
	{
	$staff_level_str 		= getstafflevel($staffid_acc);
	$exp_staff_level_str 	= explode("@#*#@",$staff_level_str);
	$staff_roleid 			= $exp_staff_level_str[0];
	$staff_levelid 			= $exp_staff_level_str[1];
	 
	$minmax_level_str 		= getstaff_minmax_level();
	$exp_minmax_level_str 	= explode("@#*#@",$minmax_level_str);
	$min_levelid 			= $exp_minmax_level_str[0];
	$max_levelid 			= $exp_minmax_level_str[1];
	}
}
?>
<link rel="stylesheet" href="css/menu.css" type="text/css" />
<header>
            <div class="container_12" style="background:none">
                <div class="grid_12">
                    <h1 style="padding-top:4px;">
                        <a>
                            <img src="images/igcar_logo_1.png" width="70" height="70">
                        </a>
						
                    </h1>
                 	<h4 style="line-height:13px; padding-top:20px">
                        <a>
							<!--<img src="images/frfcf_with_blue.png">-->
							<font style="color:#fff; font-size:23px; font-weight:500; font-family: Georgia;">Fast Reactor Fuel Cycle Facility - EBMS</font>
							<br/><div style="padding-top:10px;color:#FFFFFF; font-size:13px; font-family:'Roboto Condensed',sans-serif; font-style:italic; letter-spacing:0.6">Electronic Billing Measurement System, NRB, FRFCF</div>
                        </a>
						
                    </h4>
<!--super 2791DF; 0a9cc5 CB3462--><!--#0a9cc5 // SKY BLUE COLOR : 04/09/2019-->
                    <div class="menu_block ">
						<div align="right" style="padding-top:5px;">
						<?php if($_SESSION['staff_section'] == 1){ if($_SESSION['isadmin'] == 1) { ?>
							<a href="Faq.php" class="HomeBtn" style="color:#FFFFFF;" >Faq <i style="font-size:16px; padding-top:3px;color:#FFFFFF;" class="fa">&#xf059;</i></a>
						<?php } } ?>
						<a class="mheadmenu2" style="color:#FFFFFF; background:none"><i class="fa fa-user" style="color:#FFFFFF"></i><span>Welcome <?php echo $_SESSION['staffname']; ?></span></a>
						<?php if($_SESSION['isadmin'] == 1) { ?>
						<a class="mheadmenu mbtn2" href="Budget/Home.php" title="Click here to go to Budget Module"><i class="fa fa-book" style="color:#FFFFFF"></i><span>Budget</span></a>
						<?php }else{ ?>
						<a class="mheadmenu mbtn2" href="Budget/Home.php" title="Click here to go to Tendering Module"><i class="fa fa-book" style="color:#FFFFFF"></i><span>Tendering</span></a>
						<?php } ?>
					    <a class="mheadmenu mbtn0" href="MyWorks.php" title="Dashboard"><i class="fa fa-home" style="color:#fff"></i></a>
                      	<a class="mheadmenu mbtn0" href="ChangePassword.php" title="Change Password"><i class="fa fa-lock" style="color:#fff"></i></a>
                        <a class="mheadmenu mbtn1" href="<?php echo $self; ?>?logout" title="Logout"><i class="fa fa-power-off" style="color:#fff"></i></a>
				<?php
				/*if($_SESSION['staff_section'] != 2)
				{
				?>   
					<a href="download.php?filename=User_Manual_EBMS.pdf" class="userlogin" title="User Manual - Click here to download" style="background-color:#FFFFFF;padding: 0px 7px 0px 0px;">
						<img src="images/book1.png" width="30" height="28">
					</a>
					<a href="download.php?filename=STEPS_TO_FOLLOW_EBMS.pdf" class="userlogin" title="Steps to follow - Click here to download" style="background-color:#FFFFFF;padding: 0px 7px 0px 0px;">
						<img src="images/steps_to_follow_icon.png" width="30" height="28">
					</a>
				<?php
				}*/
				?>		
					</div>	
						<div class="clear"></div>
				<?php
				if($_SESSION['staff_section'] == 2)
				{
				?>
					<ul id="menu">
					<?php if($_SESSION['isadmin'] == 1) { ?>
						<li><a href="" class="drop">Accounts</a>
							<div class="dropdown_2columns"><!-- Begin 2 columns container -->
						
								<div class="col_1">
									<h3>Admin</h3>
									<ul class="greybox">
										<li><a href="EngineerList_Accounts.php">Staff Registration</a></li>
										<li><a href="UsersList_Accounts.php">Create User</a></li>
										<li><a href="MbookLockRelease.php">MB Lock Release</a></li>
									</ul> 
								</div>
								<div class="col_1">
									<h3>Voucher</h3>
									<ul class="greybox">
										<!--<li><a href="PinCreate.php">PIN</a></li>
										<li><a href="HoaCreate.php">Head of Account</a></li>
										<li><a href="HoaValueUpdate.php">Hoa Value Update</a></li>
										<li><a href="CommittedExpenditureUpdate.php">Committed Expenditure Update</a></li>-->
										<li><a href="VoucherEntry.php">Voucher Entry</a></li>
										<!--<li><a href="VoucherUpload.php">Voucher Upload</a></li>-->
									</ul> 
								</div>
								

							  
							</div><!-- End 2 columns container -->
						</li>	
						<li><a href="" class="drop">Budget</a>
							<div class="dropdown_3columns align_right"><!-- Begin 2 columns container -->
						
								<div class="col_1">
									<h3>Budget</h3>
									<ul class="greybox">
										<!--<li><a href="PinCreate.php">PIN</a></li>-->
										<li><a href="HoaCreate.php">Head of Account</a></li>
										<li><a href="HoaValueUpdate.php">Hoa Value Update</a></li>
										<li><a href="CommittedExpenditureUpdate.php">Committed Expenditure Update</a></li>
									</ul> 
								</div>
								<div class="col_1">
									<h3>Voucher </h3>
									<ul class="greybox">
										<li><a href="VoucherEntryOtherUnits.php">Other Units <br/>Voucher Entry</a></li>
										<li><a href="VoucherUploadOtherUnits.php">Other Units <br/>Voucher Upload</a></li>
									</ul> 
								</div>
								<div class="col_1">
									<h3>Expenditure </h3>
									<ul class="greybox">
										<li><a href="VoucherExpenditure.php">Monthly Voucher Expenditure</a></li>
										<li><a href="BudgetExpenditureReportGenerate.php">Budget Expenditure</a></li>
									</ul> 
								</div>
							  
							</div><!-- End 2 columns container -->
						</li>	
						<li><a href="" class="drop">Users</a>
							<div class="dropdown_1column"><!-- Begin 2 columns container -->
						
								<div class="col_1">
									<h3>Budget</h3>
									<ul class="greybox">
										<li><a href="TechnicalSanctionCreation.php">Technical Sanction</a></li>
										<li><a href="NITCreation.php">NIT</a></li>
										<li><a href="CommittedExpenditureUpdate.php">Committed Expenditure Update</a></li>
									</ul> 
								</div>
								

							  
							</div><!-- End 2 columns container -->
						</li>						
					<?php  } ?>
						<li><a href="ViewAgreementSheet.php" class="drop">Agreement Sheet</a></li>
					<?php 
					if(($staff_levelid >= $min_levelid)&&($staff_levelid <= $max_levelid))
					{
						if($staff_levelid == $min_levelid)
						{
							$accurl = "MeasurementBookPrint_staff_Accounts.php";
						}
						else
						{
							$accurl = "MeasurementBookPrint_staff_AccountsL".$staff_levelid.".php";
						}
					}
					?>	
						<li><a href="<?php echo $accurl; ?>" class="drop">Measurement Book</a></li>
					</ul>
				<?php
				}
				else
				{
				?>
                        <ul id="menu">
							  <?php if($_SESSION['isadmin'] == 1) { ?>
							  
								<li><a href="" class="drop">Admin</a>
									<div class="dropdown_1column align_left"><!-- Begin 2 columns container -->
								
										<div class="col_1">
											<h3>Admin</h3>
											<ul class="greybox">
												<!--<li><a href="designationlist.php" <?php echo ModuleRights('DESV'); ?>>Designation</a></li>
												<li><a href="EngineerList.php" <?php echo ModuleRights('ENGV'); ?>>Staff </a></li>
												<li><a href="UsersList.php" <?php echo ModuleRights('CRUV'); ?>>Create User</a></li>
												<li><a href="ModuleRights.php" <?php echo ModuleRights('MODR'); ?>>Module Rights</a></li>
												<li><a href="Recoveries.php">Recovery</a></li>-->
												<li><a href="UsersList.php" <?php echo ModuleRights('CRUV'); ?>>Create User</a></li>
												<li><a href="StaffWorkMigration.php">Staff Work Migration</a></li>
												<li><a href="backup.php" <?php echo ModuleRights('BKUP'); ?>>Backup</a></li>
												<li><a href="RABResetList.php">RAB Reset</a></li>
											</ul> 
										</div>
									  
									</div><!-- End 2 columns container -->
								</li>							
							<?php  } ?>
							
								<?php if($_SESSION['isadmin'] != 1) { ?>
								<li><q><a href="MyWorks.php" class="drop"><i class="fa fa-home" style="font-size:14px; padding-top:3px;"></i> Dashboard</a></q></li>
								<?php } ?>
								<li><q><a href="" class="drop">Works <i class="fa fa-caret-down" style="font-size:22px; padding-top:1px;"></i></a></q>
									<div class="dropdown_4columns align_right">
										<div class="col_1">
											<h3>Works</h3>
											<ul class="greybox">
											<?php if($_SESSION['isadmin'] == 1) { ?>
												<!--<li><a href="AgreementSheetEntry.php" <?php echo ModuleRights('AGEN'); ?>>Entry</a></li>
												<li><a href="sheet.php" <?php echo ModuleRights('AGUP'); ?>>Upload</a></li>-->
											<?php  } ?>
												<li><a href="ViewAgreementSheet.php">BOQ View</a></li>
												<li><a href="AgreementStaffAssign.php">Staff Assign</a></li>
												<li><a href="ZoneCreate.php">Zone Create</a></li>
												<li><a href="ZoneView.php">Zone View</a></li>
												<li><a href="WorkExtensionList.php">Work Extension</a></li>
												
												<!--<li><a href="L2RateGenerate.php" <?php echo ModuleRights('DEQV'); ?>>L2 Rate Assign</a></li>-->
											</ul>  
										</div>
										<!--<div class="col_1">
											<h3>Deviation</h3>
											<ul class="greybox">
												<li><a href="ExtraItemCreation.php">Additional Qty <br/>Beyond the <br/>Deviation Limit</a></li>
											</ul>   
										</div>-->
										<div class="col_1">
											<h3>MBook Issue</h3>
											<ul class="greybox">
												<li><a href="AgreementMBookAllotment.php">MB Issue to Work</a></li>
												<li><a href="MBookAllotment.php">MB Assign to Staff</a></li>
												
											</ul>   
										</div>
										<div class="col_1">
											<h3>Work Settings</h3>
											<ul class="greybox">
												<li><a href="ShortNotesGenerate.php">Short Notes Create</a></li>
												<li><a href="DecimalAssign.php">Decimal Assign</a></li>
												<li><a href="DeviationQuantity.php">Deviation Qty %</a></li>
												<li><a href="ItemTypeChange.php">Item Type Change</a></li>
												<li><a href="ElectricityBill_New.php">Electricity Charge</a></li>
												<li><a href="WaterBill_New.php">Water Charge</a></li>
												<li><a href="CementVariationEntry.php">Cement Variation</a></li>
												<li><a href="MBookPageChange.php">MB Page Update</a></li>
											</ul>   
										</div>
										<div class="col_1">
											<h3>Supplementary</h3>
											<ul class="greybox">
												<?php if($_SESSION['isadmin'] == 1) { ?>
												<li><a href="SupplementaryAgreementSheetEntry.php" <?php echo ModuleRights('AGEN'); ?>>Agreement Details Entry</a></li>
												<li><a href="SupplementaryAgreementSheetEntryView.php" <?php echo ModuleRights('AGEN'); ?>>Agreement Details View</a></li>
												<!--<li><a href="SupplementaryItemEntry.php" <?php echo ModuleRights('AGUP'); ?>> Dev. Item Entry </a></li>
												<li><a href="SupplementaryExtraItemUpload.php" <?php echo ModuleRights('AGUP'); ?>>Extra Item Entry</a></li>
												<li><a href="SupplementarySubstituteItemEntry.php" <?php echo ModuleRights('AGUP'); ?>>Substitute Item Entry</a></li>-->
												<li><a href="SupplementaryExtraItemUpload.php" <?php echo ModuleRights('AGUP'); ?>>Agreement Item Upload</a></li>
												<?php  } ?>
												<li><a href="SupplementaryItemView.php" <?php echo ModuleRights('AGUP'); ?>><!--Supplementary--> Suppl. BOQ View <!--(BeyondDeviation)--></a></li>
												<!--<li><a href="SupplementaryExtraItemView.php" <?php echo ModuleRights('AGUP'); ?>>Extra Item View</a></li>-->
												<!--<li><a href="SupplementaryEscalationItemAssign.php" <?php echo ModuleRights('AGUP'); ?>>Escalation Mark</a></li>-->
											</ul>   
										</div>
									</div>
								</li>
								<li><a href="#" class="drop">Measurements <i class="fa fa-caret-down" style="font-size:22px; padding-top:1px;"></i></a>
									<div class="dropdown_1column align_right">
										<div class="col_1">
											<h3>Measurements</h3>
											<ul class="greybox">
												<li><a href="MeasurementUpload.php" <?php echo ModuleRights('MSTU'); ?>>Upload</a></li>
												<li><a href="MeasurementQuantityEntryGenerate.php" <?php echo ModuleRights('MSTV'); ?>>Measurement Qty. Entry</a></li>
												<!--<li><a href="MeasurementEntry.php" <?php echo ModuleRights('MSTC'); ?>>Entry</a></li>-->
												<li><a href="ViewMeasurementEntry.php" <?php echo ModuleRights('MSTV'); ?>>View & Edit</a></li>
												<!--<li><a href="ViewMeasurementEntryMBFormat.php" <?php echo ModuleRights('MSTV'); ?>>MB Format View</a></li>-->
												<li><a href="MeasurementTrackingField.php">Measurement Tracking</a></li>
											</ul>  
										</div>
									</div>
								</li>
								<li><a href="#" class="drop">RAB <i class="fa fa-caret-down" style="font-size:22px; padding-top:1px;"></i></a>
									<div class="dropdown_5columns align_right">
										<div class="col_1">
											<h3>RAB</h3>
											<ul class="greybox">
												<!-- <li><a href="RABResetList.php">RAB Reset</a></li> -->
												<li><a href="RABCreation.php" <?php echo ModuleRights('MBKG'); ?>>RAB Create</a></li>
												<li><a href="RABGenerateSteps.php" <?php echo ModuleRights('MBKG'); ?>>MBook Generate <br>(General, Steel, Subabstract & Abstract)</a></li>
												<li><a href="MemoOfPaymentCreate.php">Memo of Payment</a></li>
												<li><a href="SendToAccountsRAB.php">RAB Forward to Accounts</a></li>
												<li><a href="AccountsSupportingDocumentGen.php">Attach Supporting Doc. to Accounts</a></li>
											</ul>   
										</div>
										<div class="col_1">
											<h3>MBook Print</h3>
											<ul class="greybox">
												<li><a href="MeasurementBookPrint_staff.php" <?php echo ModuleRights('MBKP'); ?>>MBook</a></li>
												<li><a href="MeasurementBookPrint_composite.php" <?php echo ModuleRights('SABP'); ?>>Sub-Abstract</a></li>
												<li><a href="AbstractBookPrint_Common.php" <?php echo ModuleRights('ABSP'); ?>>Abstract</a></li>
												<li><a href="CombinedAbstractGenerate.php" <?php echo ModuleRights('ABSG'); ?>>CombinedAbstract</a></li>
												
											</ul>   
										</div>
										<div class="col_1">
											<h3>Abstract Features</h3>
											<ul class="greybox">
												<!--<li><a href="RABGenerateSteps.php" <?php echo ModuleRights('MBKG'); ?>>RAB Generate</a></li>-->
												<li><a href="CementVariationGenerate.php" <?php echo ModuleRights('ABSG'); ?>>Cement Variation</a></li>
												<?php /*if($_SESSION['sid'] == '42'){ ?>
												<li><a href="AbsGenerate_Partpay.php" <?php echo ModuleRights('ABSG'); ?>>Abstract Generate</a></li>
												<?php }*/ ?>
												<!--<li><a href="Generate_Staff_Wise.php" <?php echo ModuleRights('MBKG'); ?>>MBook Generate</a></li>
												<li><a href="Generate_Composite.php" <?php echo ModuleRights('SABG'); ?>>Sub-Abstract Generate</a></li>
												<li><a href="CementVariationGenerate.php" <?php echo ModuleRights('ABSG'); ?>>Cement Variation Generate</a></li>
												<li><a href="AbsGenerate_Partpay.php" <?php echo ModuleRights('ABSG'); ?>>Abstract Generate</a></li>-->
												<li><a href="PartpaymentAbstractGenerate.php" <?php echo ModuleRights('ABSG'); ?>>PartPay. Abstract Generate</a></li>
												<li><a href="PartpaymentAbstractPrintGenerate.php" <?php echo ModuleRights('ABSP'); ?>>PartPayment Abstract</a></li>
												
												
												<li><a href="AbstractBookBill_Confirm.php">Pass Order</a></li>
												<!--<li><a href="RABStatus.php">RAB Status</a></li>-->
											</ul>   
										</div>
										
										<div class="col_1">
											<h3>Others</h3>
											<ul class="greybox">
												<li><a href="BillFormGenerate.php">Bill Form</a></li>
												<li><a href="FirstandFinalBillGenerate.php">Ist & Final Bill Form</a></li>
												<li><a href="VariationStatementGenerate.php">Variation Statement</a></li>
												<li><a href="TenderParityStatement.php">Tender Parity</a></li>
												<li><a href="MbookFrontPage.php">MBook Front Page</a></li>
											</ul>   
										</div>
										<div class="col_1">
											<h3>Recovery</h3>
											<ul class="greybox">
												<li><a href="Generate_ElectricityBill_New.php">Electricity</a></li>
												<li><a href="Generate_WaterBill_New.php">Water</a></li>
												<!--<li><a href="Generate_OtherRecovery.php">General Recovery</a></li>-->
												
												<li><a href="RecoveryRelease.php">Recovery Release</a></li>
											</ul>   
										</div>
										
									</div>
								</li>	
								<li><a href="#" class="drop">Advances <i class="fa fa-caret-down" style="font-size:22px; padding-top:1px;"></i></a>
									<div class="dropdown_2columns align_right">
										<div class="col_1">
											<h3>Secured Advance</h3>
											<ul class="greybox">
												<li><a href="SecuredAdvanceGenerate.php">Entry</a></li>
												<li><a href="SecuredAdvanceViewGenerate.php">View</a></li>
												<li><a href="SecuredAdvancePrintGenerate.php">Print</a></li>
												<!--<li><a href="SecuredAdvanceNew.php">Secured Advance</a></li>-->
											</ul>   
										</div>
										<div class="col_1">
											<h3>Mob. Advance</h3>
											<ul class="greybox">
												<li><a href="SecuredAdvanceGenerate.php">Mobilization Advance Entry</a></li>
												<li><a href="SecuredAdvanceViewGenerate.php">Mobilization Interest Calc.</a></li>
											</ul>   
										</div>
									</div>
								</li>
								
								
								<li><a href="#" class="drop">Escalation <i class="fa fa-caret-down" style="font-size:22px; padding-top:1px;"></i></a>
									<div class="dropdown_5columns align_right">
										<div class="col_1">
											<h3>Configuration</h3>
											<ul class="greybox">
												<!--<li><a href="Material.php">Material</a></li>
												<li><a href="MaterialBroughtToSite.php">Mat - Brought to site</a></li>-->
												<!--<li><a href="Material.php">Material</a></li>-->
												<li><a href="EscalationSettingsGenerate.php">Escalation Settings</a></li>
												<li><a href="MaterialBroughtToSiteSteel.php">Brought to Site Steel</a></li>
												<li><a href="MaterialBroughtToSiteSteelListGenerate.php">Brought to Site <br/>Steel View</a></li>
												<li><a href="MaterialBroughtToSiteOthers.php">Brought to Site Cement / Others</a></li>
												<li><a href="MaterialBroughtToSiteOthersListGenerate.php">Brought to Site Others View</a></li>
												
												<!--<li><a href="ItemIndexMonthMappingGenerate.php">Item & Index Month</a></li>
												<li><a href="ItemBaseRateAssign.php">Item Base Rate</a></li>
												<li><a href="EscalationItemAssign.php">Escalation Item Assign</a></li>
												<li><a href="Th_Cement_Consum_Assign.php">Theoritical Cement Assign</a></li>
												<li><a href="Th_Cement_Consum.php">Theoritical Cement View & Edit</a></li>-->
											</ul>  
										</div>
										<div class="col_1">
											<h3>Index Assign</h3>
											<ul class="greybox">
												<li><a href="10CAIndexAssign.php">10-CA Monthly Index</a></li>
												<li><a href="10CCIndexAssign.php">10-CC Monthly Index</a></li>
												<li><a href="BaseIndex_10CA.php">Base Index 10-CA</a></li>
												<li><a href="BaseIndex_10CC.php">Base Index 10-CC</a></li>
												<li><a href="PriceIndex_10CA.php">Price Index 10-CA</a></li>
												<li><a href="PriceIndex_10CC.php">Price Index 10-CC</a></li>
											</ul>   
										</div>
										<div class="col_1">
											<h3>10CA Consumption</h3>
											<ul class="greybox">
												<!--<li><a href="10CAConsumptionGenerate.php">Material Consumption</a></li>-->
												<li><a href="Escalation_Cement_Consump_General.php">Cement <br/>(RMC Consumption)</a></li>
												<li><a href="Escalation_Cement_Site_Consump_General.php">Cement <br/>(Site Consumption)</a></li>
												<li><a href="Escalation_Steel_Consump.php">Steel</a></li>
												<li><a href="Escalation_Steel_Site_Consump.php">Steel <br/>(Site Consumption)</a></li>
											</ul>   
										</div>
										<div class="col_1">
											<h3>Escalation</h3>
											<ul class="greybox">
												<li><a href="Escalation_10CA.php">Calculation-10 CA</a></li>
												<li><a href="EscalationAbstractGenerate.php">EscalationAbstract</a></li>
												<li><a href="EscalationQtrWiseRabBreakUpGenerate.php">10 CC - Quarter Wise RA Bill Breakup</a></li>
												<li><a href="EscalationQtrWiseWorkDoneGenerate.php">10CC - RA Bill Work Done Value</a></li>
												<li><a href="Escalation_10CC.php">Calculation-10 CC</a></li>
												<li><a href="EscalationGenerate.php">EscalationGenerate</a></li>
											</ul>   
										</div>
										<div class="col_1">
											<h3>View & Print</h3>
											<ul class="greybox">
												<li><a href="Esc_Consump_10ca_Cement_Print.php">Cement <br/>(RMC Consumption)</a></li>
												<li><a href="Esc_Site_Consump_10ca_Cement_Print.php">Cement <br/>(Site Consumption)</a></li>
												<li><a href="Esc_Consump_10ca_Steel_Print.php">Steel Consumption</a></li>
												<li><a href="Esc_Consump_10ca_Steel_Site_Print.php">Steel <br/>(Site Consumption)</a></li>
												<li><a href="EscalationAbstractPrintGenerate.php">Escalation Abstract</a></li>
												<li><a href="EscalationPrintGenerate.php">Escalation</a></li>
												<li><a href="EscalationReset.php">Reset Escalation</a></li>
											</ul>   
										</div>
										<!--<div class="col_1">
											<h3>Revised Escalation</h3>
											<ul class="greybox">
												<li><a href="PriceIndex_10CARevised.php">Revised Price Index 10-CA</a></li>
												<li><a href="PriceIndex_10CCRevised.php">Revised Price Index 10-CC</a></li>
												<li><a href="EscalationGenerateRevised.php">Revised EscalationGenerate</a></li>
											</ul>   
										</div>-->
									</div>
								</li>
								
	
								<!--<li><a href="#" class="drop">Escalation <i class="fa fa-caret-down" style="font-size:22px; padding-top:1px;"></i></a>
									<div class="dropdown_5columns align_right">
										<div class="col_1">
											<h3>Theoritical</h3>
											<ul class="greybox">
												<li><a href="Th_Cement_Consum_Assign.php">Theoritical Cement Assign</a></li>
												<li><a href="Th_Cement_Consum.php">Theoritical Cement View & Edit</a></li>
											</ul>  
										</div>
										<div class="col_1">
											<h3>index Assign</h3>
											<ul class="greybox">
												<li><a href="BaseIndex_10CA.php">Base Index 10-CA</a></li>
												<li><a href="BaseIndex_10CC.php">Base Index 10-CC</a></li>
												<li><a href="PriceIndex_10CA.php">Price Index 10-CA</a></li>
												<li><a href="PriceIndex_10CC.php">Price Index 10-CC</a></li>
											</ul>  
										</div>
										<div class="col_1">
											<h3>10CA Consumption</h3>
											<ul class="greybox">
												<li><a href="Escalation_Cement_Consump_General.php">Cement</a></li>
												<li><a href="Escalation_Steel_Consump.php">Steel</a></li>
											</ul>   
										</div>
										<div class="col_1">
											<h3>Escalation</h3>
											<ul class="greybox">
												<li><a href="EscalationAbstractGenerate.php">EscalationAbstract</a></li>
												<li><a href="Escalation_10CA.php">Calculation-10 CA</a></li>
												<li><a href="Escalation_10CC.php">Calculation-10 CC</a></li>
												<li><a href="EscalationGenerate.php">Escalation</a></li>
											</ul>   
										</div>
										<div class="col_1">
											<h3>Escalation Print</h3>
											<ul class="greybox">
												<li><a href="Esc_Consump_10ca_Cement_Print.php">Cement Consum.</a></li>
												<li><a href="Esc_Consump_10ca_Steel_Print.php">Steel Consum.</a></li>
												<li><a href="EscalationAbstractPrintGenerate.php">Escalation Abstract</a></li>
												<li><a href="EscalationPrintGenerate.php">Escalation Print</a></li>
											</ul>   
										</div>
										<div><h3>Revised Escalation</h3></div>
										<div class="col_6">
											<div class="col_1">
												<h3>Index Assign</h3>
												<ul class="greybox">
													<li><a href="RevisedBaseIndex_10CA.php" style="pointer-events:none; z-index:-1;">Base Index 10-CA</a></li>
													<li><a href="RevisedBaseIndex_10CC.php" style="pointer-events:none; z-index:-1;">Base Index 10-CC</a></li>
													<li><a href="RevisedPriceIndex_10CA.php" style="pointer-events:none; z-index:-1;">Price Index 10-CA</a></li>
													<li><a href="RevisedPriceIndex_10CC.php" style="pointer-events:none; z-index:-1;">Price Index 10-CC</a></li>
												</ul>  
											</div>
											<div class="col_1">
												<h3>10CA Consumption</h3>
												<ul class="greybox">
													<li><a href="RevisedEscalation_Cement_Consump_General.php" style="pointer-events:none; z-index:-1;">Cement</a></li>
													<li><a href="RevisedEscalation_Steel_Consump.php" style="pointer-events:none; z-index:-1;">Steel</a></li>
												</ul>   
											</div>
											<div class="col_1">
												<h3>Rev. Escalation</h3>
												<ul class="greybox">
													<li><a href="RevisedEscalationAbstractGenerate.php" style="pointer-events:none; z-index:-1;">EscalationAbstract</a></li>
													<li><a href="RevisedEscalation_10CA.php" style="pointer-events:none; z-index:-1;">Calculation-10 CA</a></li>
													<li><a href="RevisedEscalation_10CC.php" style="pointer-events:none; z-index:-1;">Calculation-10 CC</a></li>
													<li><a href="RevisedEscalationGenerate.php" style="pointer-events:none; z-index:-1;">Escalation</a></li>
												</ul>   
											</div>
											<div class="col_1">
												<h3>Rev. Escalation Print</h3>
												<ul class="greybox">
													<li><a href="RevisedEsc_Consump_10ca_Cement_Print.php" style="pointer-events:none; z-index:-1;">Cement</a></li>
													<li><a href="RevisedEsc_Consump_10ca_Steel_Print.php" style="pointer-events:none; z-index:-1;">Steel</a></li>
													<li><a href="RevisedEscalationAbstractPrintGenerate.php" style="pointer-events:none; z-index:-1;">Escalation Abstract Print</a></li>
													<li><a href="RevisedEscalationPrintGenerate.php" style="pointer-events:none; z-index:-1;">Escalation Print</a></li>
												</ul>   
											</div>
										</div>
									</div>
									
									
								</li>-->	
								
								<!--<li><a href="#" class="drop">Revised Escalation <i class="fa fa-caret-down" style="font-size:22px; padding-top:1px;"></i></a>
									<div class="dropdown_4columns align_right">
										<div class="col_1">
											<h3>index Assign</h3>
											<ul class="greybox">
												<li><a href="RevisedBaseIndex_10CA.php" style="pointer-events:none; z-index:-1;">Base Index 10-CA</a></li>
												<li><a href="RevisedBaseIndex_10CC.php" style="pointer-events:none; z-index:-1;">Base Index 10-CC</a></li>
												<li><a href="RevisedPriceIndex_10CA.php" style="pointer-events:none; z-index:-1;">Price Index 10-CA</a></li>
												<li><a href="RevisedPriceIndex_10CC.php" style="pointer-events:none; z-index:-1;">Price Index 10-CC</a></li>
											</ul>  
										</div>
										<div class="col_1">
											<h3>10CA Consumption</h3>
											<ul class="greybox">
												<li><a href="RevisedEscalation_Cement_Consump_General.php" style="pointer-events:none; z-index:-1;">Cement</a></li>
												<li><a href="RevisedEscalation_Steel_Consump.php" style="pointer-events:none; z-index:-1;">Steel</a></li>
											</ul>   
										</div>
										<div class="col_1">
											<h3>Rev. Escalation</h3>
											<ul class="greybox">
												<li><a href="RevisedEscalationAbstractGenerate.php" style="pointer-events:none; z-index:-1;">EscalationAbstract</a></li>
												<li><a href="RevisedEscalation_10CA.php" style="pointer-events:none; z-index:-1;">Calculation-10 CA</a></li>
												<li><a href="RevisedEscalation_10CC.php" style="pointer-events:none; z-index:-1;">Calculation-10 CC</a></li>
												<li><a href="RevisedEscalationGenerate.php" style="pointer-events:none; z-index:-1;">Escalation</a></li>
											</ul>   
										</div>
										<div class="col_1">
											<h3>Rev. Escalation Print</h3>
											<ul class="greybox">
												<li><a href="RevisedEsc_Consump_10ca_Cement_Print.php" style="pointer-events:none; z-index:-1;">Cement</a></li>
												<li><a href="RevisedEsc_Consump_10ca_Steel_Print.php" style="pointer-events:none; z-index:-1;">Steel</a></li>
												<li><a href="RevisedEscalationAbstractPrintGenerate.php" style="pointer-events:none; z-index:-1;">Escalation Abstract Print</a></li>
												<li><a href="RevisedEscalationPrintGenerate.php" style="pointer-events:none; z-index:-1;">Escalation Print</a></li>
											</ul>   
										</div>
									</div>
								</li>-->	
										<!--<div class="col_1">
											<h3>Revised Escalation</h3>
											<ul class="greybox">
												<li><a href="BaseIndex_10CARevised.php">Base Index 10-CA</a></li>
												<li><a href="BaseIndex_10CCRevised.php">Base Index 10-CC</a></li>
												<li><a href="PriceIndex_10CARevised.php">Price Index 10-CA</a></li>
												<li><a href="PriceIndex_10CCRevised.php">Price Index 10-CC</a></li>
												<li><a href="EscalationGenerateRevised.php">Escalation Generate</a></li>
											</ul>   
										</div>-->
														
								<li><a href="" class="drop">Reports <i class="fa fa-caret-down" style="font-size:22px; padding-top:1px;"></i></a>
									<div class="dropdown_3columns align_right">
										<div class="col_1">
											<h3>History</h3>
											<ul class="greybox">
												<li><a href="MBookGenerate_History_Staff.php" <?php echo ModuleRights('MBKH'); ?>>MBook</a></li>
												<li><a href="MBookGenerate_History_Composite.php" <?php echo ModuleRights('SABH'); ?>>Sub-Abstract</a></li>
												<li><a href="RunningbillView.php" <?php echo ModuleRights('RABL'); ?>>Abstract</a></li>
											</ul>   
										</div>
										<div class="col_1">
											<h3>Reports</h3>
											<ul class="greybox">
												<li><a href="OngoingCompletedWorkGenerate.php">Ongoing / Completed Works</a></li>
												<li><a href="RABStatus.php">Bill Status</a></li>
												<li><a href="PartPaymentPage.php">PartPayment Details</a></li>
												<li><a href="AccountsComments.php">Accounts Comment</a></li>
											</ul>   
										</div>
										<div class="col_1">
											<h3>Reports - Qty</h3>
											<ul class="greybox">
												<li><a href="ReportsZoneWiseItemQtyGenerate.php">Zone Wise Qty</a></li>
												<li><a href="ReportsItemUsedQty.php">Item Qty. Report</a></li>
												<li><a href="MeasurementBookPrint_composite_column.php" <?php echo ModuleRights('SABP'); ?>>Sub-Abstract ZoneWise</a></li>
												<!--<li><a href="ReportsZoneItemQtyAll.php" <?php echo ModuleRights('SABP'); ?>>ZoneWise Item Qty</a></li>-->
											</ul>   
										</div>
									</div>
								</li>							
                        </ul> 
				<?php
				}
				?>
                    </div>
                </div>
            </div>
        </header> 
<!--<div class="container_12" style="height:13px"></div>-->

<!--<link rel="stylesheet" href="Chat/style.css">
<div class="fabs">
	<div class="chat">
    	<div class="chat_header">
      		<div class="chat_option">
      			<div class="header_img">
        			<img src="Chat/profile_default.png"/>
        		</div>
        		<span id="chat_head"><?php echo $_SESSION['staffname']; ?></span> <br> <span class="agent">User</span> <span class="online">(Online)</span>
       			<span id="chat_fullscreen_loader" class="chat_fullscreen_loader"><i class="fullscreen fa full-screen"></i></span>
      		</div>
    	</div>
		<div id="chat_body" class="chat_body">
		  <div class="chat_category">
				Select CCNo. of your work
				<ul>
					<li class="active">
						<select name="chat_ccno" id="chat_ccno">
							<option value=""> ---- Select -----</option>
						</select>
					</li>
				</ul>
			  <a id="chat_third_screen" class="fab"> <i class="fa fa-arrow-circle-o-right"></i></a>			</div>
		</div>
    	<div id="chat_form" class="chat_converse chat_form">
			
			
			<span class="chat_msg_item chat_msg_item_admin" style="margin-bottom:3px; margin-top:0px;">
				<div>
						<div class="chat-form-row">
				  			Your Email : <br/><input type="text" class="chat-tbox" name="chat_from_email" id="chat_from_email" placeholder="Enter your email"/>
				  		</div>
				  		<div class="chat-form-row">
				  			Your Password : <br/><input type="password" class="chat-tbox" name="chat_from_pwd" id="chat_from_pwd" placeholder="Enter your password"/>
				  		</div>
						<div class="chat-form-row">
					  		Send To : <br/> 
							<span class="chat-rbox">info@lashron.com</span> 
							<span class="chat-rbox">helpdesk@lashron.com</span>
							<input type="hidden" class="chat-tbox" name="chat_send_to" id="chat_send_to" value="info@lashron.com,helpdesk@lashron.com" placeholder="" readonly=""/>
						</div>
						<div class="chat-form-row">
					  		CC To : <br/>
							<div class="chat-div" id="chat_cc_div"></div>
							<input type="hidden" name="chat_cc" id="chat_cc">
						</div>
						<div class="chat-form-row">
					  		Your Message : <br/><textarea class="chat-tbox" rows="2" name="chat_message" id="chat_message" placeholder="Enter your message"></textarea>
						</div>
						<div class="chat-form-row" align="center">
					  		<button type="button" name="chat_fourth_screen" id="chat_fourth_screen" class="chatBtn">Back</button> 
							<button type="button" name="chat_send" id="chat_send" class="chatBtn">Send &nbsp;<i class="fa fa-send SendIcon"></i><i class="fa fa-spinner fa-spin hide SpinIcon"></i></button> 
						</div>
				</div>
			</span> 
    	</div>
  	</div>
    <a id="prime" class="fab">
		<i class="helpdesk fa chat-icon"></i>
	</a>
</div>
<script>
	var GlobChatLocStr = "";
</script>
<script  src="Chat/ChatScript.js"></script>-->