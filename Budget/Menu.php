<?php



//////////////////////////		  SEARCH "LOADER INCLUSION" IN SEARCH BOX FOR INCLUSIONS OF LOADER		  		  //////////////////////////
//////////////////////////		  LOADER INCLUSION UPDATE FILES "TestForLoader", "LoaderPage.php", "Menu.php"     //////////////////////////





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
function CheckMenuCode($CodeStr)
{
	$count = 0;
	$expCodeStr 	= explode(",",$CodeStr);
	$ModuleRights 	= $_SESSION['ModuleRights'];
	for($i=0; $i<count($expCodeStr); $i++)
	{
		$menuCode = $expCodeStr[$i];
		if(in_array($menuCode, $ModuleRights)) 
		{
			$count++;
		}
	}
	return $count;
}

?>
<script>
	/* LOADER INCLUSION - STARTS HERE */
	function triggerLoader(){
		$(".loaderPage").show();
	}
	/* LOADER INCLUSION - ENDS HERE */
	$(document).ready(function(){
    	//$('a').removeAttr("tabindex");
		$('a').attr('tabindex', 1 - 2);
	});
</script>
<link rel="stylesheet" href="css/menu.css" type="text/css" />

<header>
	<div class="container_13">
		<div class="grid_12">
			<!-- ====================== Logo and Title Section Starts=================== -->
			<h1>
				<a>
					<img src="images/igcar_logo_1.png" width="80" height="80" style="padding-top:2px;padding-bottom:2px;">
				</a>
			</h1>
			<h4>
				<a>
					<div class="titleHead">Fast Reactor Fuel Cycle Facility - EBMS</div>
					<div class="sub-titleHead">Electronic Billing Measurement System, NRB, FRFCF</div>
				</a>
			</h4>
			<!-- ====================== Top Left Menu Section Starts=================== -->
			<div class="menu_block" align="right">
				<div class="dropdown">
					<div align="right" class="wel-msg">
						<i class="fa fa-user" aria-hidden="true" style="padding-top:5px; text-align:right"></i> Welcome <?php if($_SESSION['isadmin'] == 1){ echo "Admin"; }else{ echo $_SESSION['staffname']; } ?> !
						<?php if(in_array("BIL", $_SESSION['ModuleAccArr'])){ ?>
						<a data-url="../MyWorks" class="btn-hom"><i class="fa fa-rupee" style="font-size:12px; padding-top:5px"></i> Billing</a>
						<?php }else if($_SESSION['isadmin'] == 1){ ?>
						<a data-url="../MyWorks" class="btn-hom"><i class="fa fa-rupee" style="font-size:12px; padding-top:5px"></i> Billing</a>
						<?php } ?>
						<a data-url="Home" class="btn-hom"><i class="fa fa-home" style="font-size:12px; padding-top:5px"></i></a>
						<a data-url="ChangePassword" class="btn-hom" title="Change Password"><i class="fa fa-lock" style="font-size:12px; padding-top:5px"></i></a>
						<a data-url="Logout" class="btn-log"><i class="fa fa-power-off" style="font-size:12px; padding-top:5px"></i> Logout</a>
					</div>
					<div class="h-menu">
						<!--<?php //if(in_array("BIL", $_SESSION['ModuleAccArr'])){ ?>
						<a data-url="../MyWorks" class="btn-bill"><i class="fa fa-rupee" style="font-size:12px; padding-top:5px"></i> Billing</a>
						<?php //}else if($_SESSION['isadmin'] == 1){ ?>
						<a data-url="../MyWorks" class="btn-bill"><i class="fa fa-rupee" style="font-size:12px; padding-top:5px"></i> Billing</a>
						<?php //} ?>
						<a data-url="Home" class="btn-hom"><i class="fa fa-home" style="font-size:12px; padding-top:5px"></i> Home</a>
						<a data-url="Logout" class="btn-log"><i class="fa fa-power-off" style="font-size:12px; padding-top:5px"></i> Logout</a>-->
					
					</div>
				</div>
				<!-- ====================== Main Menu Section for ACCOUNTS Starts=================== -->
				
				
				<ul id="menu">
					<!--<li><q><a href="" class="drop">Works <i class="fa fa-caret-down down-arr"></i></a></q>
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<h3>Works</h3>
								<ul class="greybox">
									<li><a data-url="WorksCSTList">CST Confirm</a></li>
									<li><a data-url="PGEntry">PG Entry</a></li>
									<li><a data-url="SDEntry">SD Entry</a></li>
									<li><a data-url="PGReturn">PG Release</a></li>
									<li><a data-url="SDReturn">SD Release</a></li>
									<li><a data-url="EMDReturn">EMD Return</a></li>
								</ul> 
							</div>
						</div>
					</li>
					<li><q><a href="" class="drop">Bill Transaction <i class="fa fa-caret-down down-arr"></i></a></q>
						<div class="dropdown_3columns align_right">
							<div class="col_1">
								<h3>Works</h3>
								<ul class="greybox">
									<?php if($_SESSION['levelid'] == 1){ ?>
									<li><a data-url="WorkRegistration">Bill Registration</a></li>
									<?php } ?>
									<li><a data-url="MeasurementBookPrint_staff_Accounts">Bill Verification</a></li>
									<li><a data-url="MemoOfPaymentCreateAdvance">Advance Payment</a></li>
									<li><a data-url="MemoOfPaymentCreate">Memo of Payment</a></li>
									<li><a data-url="AccountsStatementSteps">MOP Statements</a></li>
									<li><a data-url="PassOrderCreate">Pass Order</a></li>
									<li><a data-url="PayOrderCreate">Pay Order</a></li>
								</ul> 
							</div>
							<div class="col_1">
								<h3>Miscellaneous</h3>
								<ul class="greybox">
									<li><a data-url="MemoOfPaymentCreateMisc">Memo of Payment</a></li>
									<li><a data-url="MemoOfPaymentCreate">MOP Statements</a></li>
								</ul> 
							</div>
							<div class="col_1">
								<h3>Other Misc.</h3>
								<ul class="greybox">
									<li><a data-url="MemoOfPaymentSDRelease">Memo of Payment - <br/>SD Release / EPF / GST</a></li>
									<li><a data-url="MemoOfPaymentLCess">Memo of Payment - LCess</a></li>
									<li><a data-url="MemoOfPaymentSalary">Memo of Payment - Salary</a></li>
								</ul> 
							</div>
						</div>
					</li>
					<li><q><a href="" class="drop">Voucher <i class="fa fa-caret-down down-arr"></i></a></q>
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<h3>Voucher</h3>
								<ul class="greybox">
									<li><a data-url="VoucherEntryList">Voucher Entry</a></li>
									<li><a data-url="VoucherEntryList">View Vouchers</a></li>
								</ul> 
							</div>
						</div>
					</li>
					<li><q><a href="" class="drop">Other Advance & Escalation <i class="fa fa-caret-down down-arr"></i></a></q>
						<div class="dropdown_2columns align_right">
							<div class="col_1">
								<h3>Mob. Advance</h3>
								<ul class="greybox">
									<li><a data-url="MOBEntry">Mobilization Advance</a></li>
									<li><a data-url="MobIntCalFrontPage">Mobilization Advance Int. Calc</a></li>
								</ul> 
							</div>
							<div class="col_1">
								<h3>Escalation</h3>
								<ul class="greybox">
									<li><a data-url="Esc_Consump_10ca_Cement_Print">Cement Consumption</a></li>
									<li><a data-url="Esc_Consump_10ca_Steel_Print">Steel Consumption</a></li>
									<li><a data-url="EscalationAbstractPrintGenerate">Escalation Abstract</a></li>
									<li><a data-url="EscalationPrintGenerate">Escalation</a></li>
								</ul> 
							</div>
						</div>
					</li>-->
					<li><q><a data-url="Home" class="drop"><i class="fa fa-home"></i> &nbsp;Dashboard</a></q></li>
					<li><q><a href="" class="drop">Tender Process <i class="fa fa-caret-down down-arr"></i></a></q>
						<div class="dropdown_6columns align_right">
							<div class="col_1">
								<h3>Dept. Estimate</h3>
								<ul class="greybox">
									<li><a data-url="DeptEstimateUploadGenerate">Upload</a></li>
									<li><a data-url="DepEstViewEdit">View</a></li>
									<!-- <li><a data-url="LCessStatement">Registers</a></li> -->
								</ul>
							</div>
							<div class="col_1">
								<h3>TS & NIT</h3>
								<ul class="greybox">
									<li><a data-url="TechnicalSanction">TS - Entry</a></li>
									<li><a data-url="TSViewEdit">TS - View & Edit</a></li>
									<li><a data-url="NIT">NIT - Entry</a></li>
									<li><a data-url="NITViewEdit">NIT - View & Edit</a></li>
								</ul> 
							</div>
							
						
							
							<div class="col_1">
								<h3>EMD</h3>
								<ul class="greybox">
									<li><a data-url="EMDEntry">Entry</a></li>
									<li><a data-url="EMDEntryView">View & Edit</a></li>
									<!-- <li><a data-url="LCessStatement">Registers</a></li> -->
									<li><a data-url="EMDReturn">EMD Return</a></li>
								</ul>
							</div>
							<div class="col_1">
								<h3>Financial Bid</h3>
								<ul class="greybox">
									<li><a data-url="PriceBidUploadGenerate">Upload</a></li>
									<li><a data-url="PriceBidViewEdit">View</a></li>
									<!--<li><a data-url="LCessStatement">Registers</a></li>-->
									<li><a data-url="Bidders">Bidders - Entry</a></li>
									<li><a data-url="BiddersList">Bidders - View & Edit</a></li>
								</ul>
							</div>
							<div class="col_1">
								<h3>CST & Lottery</h3>
								<ul class="greybox">
									<li><a data-url="ComparativeStatementGenerate?csid=1">Generate</a></li>
									<li><a data-url="ComparativeStatementGenerate?csid=2">Forward to Accounts</a></li>
									<li><a data-url="LotteryGenerate?loiid=1">Lottery Update</a></li>
									<li><a data-url="LotteryView">Lottery View</a></li>
									<li><a data-url="NegotiationEntryGenerate?ncsid=1">Negotiation Entry</a></li>
									<li><a data-url="NegotiatedComparativeStatementGenerate?ncsid=2">Negotiation Generate</a></li>
									<li><a data-url="NegotiatedComparativeStatementGenerate?ncsid=3">Negotiation Forward to Accounts</a></li>
									<!-- <li><a data-url="TenderParityStatement">Tender Parity</a></li> -->
								</ul>
							</div>
							
							<div class="col_1">
								<h3>LOI & PG</h3>
								<ul class="greybox">
									<li><a data-url="LOIEntry">LOI Entry</a></li>
									<li><a data-url="LOIViewEdit">LOI View & Edit</a></li>
									<li><a data-url="PGEntry">PG Entry</a></li>
									<li><a data-url="PGEntryView">PG View & Edit</a></li>
									
								</ul>
							</div>
							
						</div>
					</li>
					<li><q><a href="" class="drop">Work Process <i class="fa fa-caret-down down-arr"></i></a></q>
						<div class="dropdown_2columns align_right">
							<div class="col_1">
								<h3>Work Order</h3>
								<ul class="greybox">
									<li><a data-url="WorkOrder">Entry</a></li>
									<li><a data-url="WorkOrderList">View & Edit</a></li>
									<li><a data-url="TenderParityStatement">Tender Parity </a></li>
								</ul> 
							</div>
							<div class="col_1">
								<h3>SD</h3>
								<ul class="greybox">
									<li><a data-url="SDEntry">Entry</a></li>
									<li><a data-url="SDEntryView">View & Edit</a></li>
									<li><a data-url="PGReturn">PG Release</a></li>
									<li><a data-url="SDReturn">SD Release</a></li>
								</ul> 
							</div>
						</div>
					</li>
					<li><q><a href="" class="drop">Committed Plan <i class="fa fa-caret-down down-arr"></i></a></q>
						<div class="dropdown_1column align_right">
							<div class="col_1">
								<h3>Committed Plan</h3>
								<ul class="greybox">
									<li><a data-url="CommittedExpenditureUpdate">Entry</a></li>
									<li><a data-url="CommitedExpViewEdit">View & Edit</a></li>
								</ul> 
							</div>
						</div>
					</li>
				</ul>
				
				
				
			</div>
		</div>
	</div>
</header> 

<div class="container_12">
	<!--<br/>-->
</div>

<script>

</script>
<!--<link rel="stylesheet" href="../Chat/style.css">
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
	var GlobChatLocStr = "../";
</script>
<script  src="../Chat/ChatScript.js"></script>-->