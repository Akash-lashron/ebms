<?php
if (!defined('WEB_ROOT')) {
	exit;
}
$self = WEB_ROOT . 'login.php';
function ModuleRights($code)
{
	$ModuleRights = $_SESSION['ModuleRights'];
	if(in_array($code, $ModuleRights)) 
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
            <div class="container_12">
                <div class="grid_12">
                    <h1>
                        <a href="">
                            <img src="images/ebms_new_dark.png">
                        </a>
						
                    </h1>
                 	<h4>
                        <a href="">
							<img src="images/frfcf_with_blue.png">
                        </a>
						
                    </h4>

                    <div class="menu_block ">
                        <a class="userlogin" href="<?php echo $self; ?>?logout" style="background-color:#0a9cc5">Logout</a>
                      	<a href="ChangePassword.php" class="userlogin">Change Password</a>
					    <a href="dashboard.php" class="userlogin" style="background-color:#0a9cc5">Home</a>
				<?php
				if($_SESSION['staff_section'] != 2)
				{
				?>   
						<a href="download.php?filename=User_Manual_EBMS.pdf" class="userlogin" title="User Manual - Click here to download" style="background-color:#FFFFFF;padding: 0px 7px 0px 0px;">
						<img src="images/book1.png" width="30" height="28">
						</a>
						<a href="download.php?filename=STEPS_TO_FOLLOW_EBMS.pdf" class="userlogin" title="Steps to follow - Click here to download" style="background-color:#FFFFFF;padding: 0px 7px 0px 0px;">
						<img src="images/steps_to_follow_icon.png" width="30" height="28">
				<?php
				}
				?>		</a>
						
						<div class="clear"></div>
				<?php
				if($_SESSION['staff_section'] == 2)
				{
				?>
					<ul id="menu">
					<?php if($_SESSION['isadmin'] == 1) { ?>
						<li><a href="" class="drop">Admin</a>
							<div class="dropdown_2columns"><!-- Begin 2 columns container -->
						
								<div class="col_2">
									<h3>Admin</h3>
									<ul class="greybox">
										<li><a href="EngineerList_Accounts.php">Staff Registration</a></li>
										<li><a href="UsersList_Accounts.php">Create User</a></li>
										<li><a href="MbookLockRelease.php">MB Lock Release</a></li>
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
									<div class="dropdown_1column"><!-- Begin 2 columns container -->
								
										<div class="col_1">
											<h3>Admin</h3>
											<ul class="greybox">
												<li><a href="designationlist.php" <?php echo ModuleRights('DESV'); ?>>Designation</a></li>
												<li><a href="EngineerList.php" <?php echo ModuleRights('ENGV'); ?>>Staff </a></li>
												<li><a href="UsersList.php" <?php echo ModuleRights('CRUV'); ?>>Create User</a></li>
												<li><a href="ModuleRights.php" <?php echo ModuleRights('MODR'); ?>>Module Rights</a></li>
												<li><a href="Recoveries.php">Recovery</a></li>
												<li><a href="backup.php" <?php echo ModuleRights('BKUP'); ?>>Backup</a></li>
											</ul> 
										</div>
									  
									</div><!-- End 2 columns container -->
								</li>							
							<?php  } ?>
							
							
								<li><q><a href="" class="drop">Agreement</a></q>
									<div class="dropdown_3columns align_right">
										<div class="col_1">
											<h3>Agreements</h3>
											<ul class="greybox">
												<li><a href="AgreementSheetEntry.php" <?php echo ModuleRights('AGEN'); ?>>Entry</a></li>
												<li><a href="sheet.php" <?php echo ModuleRights('AGUP'); ?>>Upload</a></li>
												<li><a href="ViewAgreementSheet.php" <?php echo ModuleRights('AGVW'); ?>>View</a></li>
												<li><a href="DecimalAssign.php" <?php echo ModuleRights('DEAV'); ?>>Decimal Assign</a></li>
												<li><a href="DeviationQuantity.php" <?php echo ModuleRights('DEQV'); ?>>Deviation Qty %</a></li>
												<li><a href="ZoneCreate.php">Zone Create</a></li>
												<li><a href="ElectricityBill_New.php">Electricity Charge</a></li>
												<li><a href="WaterBill_New.php">Water Charge</a></li>
											</ul>  
										</div>
										<div class="col_1">
											<h3>MBook Allotment</h3>
											<ul class="greybox">
												<li><a href="AgreementMBookAllotment.php" <?php echo ModuleRights('MWOA'); ?>>Work Order</a></li>
												<li><a href="MBookAllotment.php" <?php echo ModuleRights('MSTA'); ?>>Staff</a></li>
											</ul>   
										</div>
										<div class="col_1">
											<h3>Shortnotes</h3>
											<ul class="greybox">
												<li><a href="ShortnotesCreation.php" <?php echo ModuleRights('SNCR'); ?>>Short Notes Create</a></li>
												<li><a href="ShortnotesView.php" <?php echo ModuleRights('SNVW'); ?>>Short Notes Edit</a></li>
											</ul>   
										</div>
										
									</div>
								</li>
						
								<li><a href="#" class="drop">Measurements</a>
									<div class="dropdown_4columns align_right">
										<div class="col_1">
											<h3>Measurements</h3>
											<ul class="greybox">
												<li><a href="MeasurementUpload.php" <?php echo ModuleRights('MSTU'); ?>>Upload</a></li>
												<li><a href="MeasurementEntry.php" <?php echo ModuleRights('MSTC'); ?>>Entry</a></li>
												<li><a href="ViewMeasurementEntry.php" <?php echo ModuleRights('MSTV'); ?>>View & Edit</a></li>
											</ul>  
										</div>
										<div class="col_1">
											<h3>MBook</h3>
											<ul class="greybox">
												<li><a href="Generate_Staff_Wise.php" <?php echo ModuleRights('MBKG'); ?>>MBook Generate</a></li>
												<li><a href="Generate_Composite.php" <?php echo ModuleRights('SABG'); ?>>Sub-Abstract Generate</a></li>
												<li><a href="AbsGenerate_Partpay.php" <?php echo ModuleRights('ABSG'); ?>>Abstract Generate</a></li>
												<li><a href="CombinedAbstractGenerate.php" <?php echo ModuleRights('ABSG'); ?>>CombinedAbstract</a></li>
											</ul>   
										</div>
										<div class="col_1">
											<h3>Secured Advance</h3>
											<ul class="greybox">
												<li><a href="SecuredAdvance.php">Secured Advance</a></li>
											</ul>   
										</div>
										<div class="col_1">
											<h3>Recovery</h3>
											<ul class="greybox">
												<li><a href="Generate_ElectricityBill_New.php">Electricity</a></li>
												<li><a href="Generate_WaterBill_New.php">Water</a></li>
												<li><a href="Generate_OtherRecovery.php">General Recovery</a></li>
												<li><a href="RecoveryRelease.php">Recovery Release</a></li>
											</ul>   
										</div>
									</div>
								</li>	
	
									
	
								<li><a href="" class="drop">Print</a>
									<div class="dropdown_2columns align_right">
										<div class="col_1">
											<h3>MBook</h3>
											<ul class="greybox">
												<li><a href="MeasurementBookPrint_staff.php" <?php echo ModuleRights('MBKP'); ?>>MBook</a></li>
												<li><a href="MeasurementBookPrint_composite.php" <?php echo ModuleRights('SABP'); ?>>Sub-Abstract</a></li>
												<li><a href="AbstractBookPrint_Common.php" <?php echo ModuleRights('ABSP'); ?>>Abstract</a></li>
											</ul>  
										</div>
										<div class="col_1">
											<h3>Others</h3>
											<ul class="greybox">
												<li><a href="MbookFrontPage.php">MBook Front Page</a></li>
												<li><a href="ReportsItemUsedQty.php">Item Qty. Report</a></li>
											</ul>   
										</div>
									</div>
								</li>	
														
								<li><a href="" class="drop">Accounts</a>
									<div class="dropdown_3columns align_right">
										<div class="col_1">
											<h3>Send to Accounts</h3>
											<ul class="greybox">
												<li><a href="Bill_Send_to_Accounts.php">Send to Accounts</a></li>
												<li><a href="AccountsComments.php">Accounts Comments</a></li>
											</ul>  
										</div>
										<div class="col_1">
											<h3>Pass Order</h3>
											<ul class="greybox">
												<li><a href="AbstractBookBill_Confirm.php">Pass Order</a></li>
											</ul>   
										</div>
										<div class="col_1">
											<h3>History</h3>
											<ul class="greybox">
												<li><a href="MBookGenerate_History_Staff.php" <?php echo ModuleRights('MBKH'); ?>>MBook</a></li>
												<li><a href="MBookGenerate_History_Composite.php" <?php echo ModuleRights('SABH'); ?>>Sub-Abstract</a></li>
												<li><a href="RunningbillView.php" <?php echo ModuleRights('RABL'); ?>>Abstract</a></li>
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
<div class="container_12">
<br/>
</div>