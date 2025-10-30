<!--<style>
#navi1 {
    list-style:none inside;
    margin:0;
    padding:0;
    text-align:center;
	width:100%;
	background:#189DDC;
	min-height:30px;
    }

#navi1 li {
    display:block;
    position:relative;
    float:left;
    background: #189DDC; /* menu background color */
	padding-top: 0px !important;
	border:1px solid #0389C8;
    }

#navi1 li a {
    display:block;
    padding:0;
    text-decoration:none;
     /* this is the width of the menu items */
    line-height:28px; /* this is the hieght of the menu items */
    color:#ffffff; /* list item font color */
	font-family:sans-serif;
	padding:0px 15px;
	font-weight:bold;
	font-size:13px;
    }
        
#navi1 li li a {font-size:80%; width:200px;} /* smaller font size for sub menu items */
    
#navi1 li:hover {background:#0673A6;} /* highlights current hovered list item and the parent list items when hovering over sub menues */



/*--- Sublist Styles ---*/
#navi1 ul {
    position:absolute;
    padding:0;
    left:0;
    display:none; /* hides sublists */
	z-index:9999;
    }

#navi1 li:hover ul ul {display:none;} /* hides sub-sublists */

#navi1 li:hover ul {display:block;} /* shows sublist on hover */

#navi1 li li:hover ul {
    display:block; /* shows sub-sublist on hover */
    margin-left:200px; /* this should be the same width as the parent list item */
    margin-top:-35px; /* aligns top of sub menu with top of list item */
    }
</style>-->
<style>
	#main_nav ul {
		background: #03BCD3;
		float: left;
		-webkit-transition: .5s;
		transition: .5s;
		padding-top: 0px;
		width:100%;
	}
	#main_nav > ul > li{
		border-right:1px solid #047EA9;
	}
	#main_nav li {
		float: right;
		position: relative;
		list-style: none;
		-webkit-transition: .1s;
		transition: .1s;
		padding-top: 0px;
		display:inline;
	}
	/*#main_nav > ul > li > a, h1 {
	}
	#main_nav ul li ul li a{
		width:164px;
	}
	#main_nav a {
		display: block;
		text-decoration: none;
		padding: 4px 15px;
		color: #fff;
		font-weight:500;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:12px;
	}
	#main_nav ul ul {
		position: absolute;
		left: 0;
		top: 100%;
		visibility: hidden;
		opacity: 0;
	}
	#main_nav ul ul ul {
		left: 100%;
		top: 8px;
	}
	
	#main_nav li:hover > ul {
		visibility: visible;
		opacity: 1;
	}
	#main_nav ul > li > ul > li {
		background:#044D85;
		border-top:none;
	    font-weight:500;
		box-shadow: 1px 40px 30px 4px #888888;
		border-left:2px solid #022B4B;
		border-right:2px solid #022B4B;
		border-bottom:1px solid #022B4B;
	}
	#main_nav ul > li > ul > li:first-child {
		border-top:1px solid #022B4B;
	}
	#main_nav ul > li > ul > li:last-child {
	  	border-bottom:2px solid #022B4B;
	}
	#main_nav li :hover, #main_nav:hover {
		background: #044D85;
	}
	#main_menu > li:hover > a
	{
	  background-color: #044D85;
	}
	
	#main_menu >  li > .submenu > li:hover
	{
	  background-color:#fff;
	  color:#022B4B;
	}
	#main_menu >  li > .submenu > li:hover > a
	{
	  color:#045797;
	  font-weight:500;
	}
	#main_menu >  li{
		z-index:1000;
	}*/
</style>


<nav id="main_nav">
	<ul id="main_menu">
		<li>&nbsp; </li>
	</ul>
	<!--<ul id="main_menu">
		
		<li><a href="#">Reports</a> 
			<ul class="submenu">
				<li><a data-url="GstStatement">GST Statement</a></li>
				<li><a data-url="ITStatement">IT Statement</a></li>
				<li><a data-url="LCessStatement">Labour Cess Statement</a></li>
				<li><a data-url="SDregisterReleaseListStatement">SD Release (BG/FDR)Statement</a></li>
				<li><a data-url="SDRecSchedule">SD Recovery Schedule</a></li>
				<li><a data-url="SDAnnualBroadsheet">SD Annual Broadsheet</a></li>
				<li><a data-url="VoucherExpenditureAcc">Voucher Expenditure</a></li>
			</ul>
		</li>
		<li><a href="#">Registers</a> 
			<ul class="submenu">
				<li><a data-url="EMDRegister">EMD Register</a></li>
				<li><a data-url="PGRegister">PG Register</a></li>
				<li><a data-url="SDRegister">SD Register</a></li>
				<li><a data-url="BGRegisterStatement">BG Register</a></li>
				<li><a data-url="FDRRegisterStatement">FDR Register</a></li>
			</ul>
		</li>
		<li><a href="#">Escalation</a> 
			<ul class="submenu">
				<li><a data-url="Esc_Consump_10ca_Cement_Print">Cement Consumption</a></li>
				<li><a data-url="Esc_Consump_10ca_Steel_Print">Steel Consumption</a></li>
				<li><a data-url="EscalationAbstractPrintGenerate">Escalation Abstract</a></li>
				<li><a data-url="EscalationPrintGenerate">Escalation</a></li>
			</ul>
		</li>
		<li><a href="#">Mobilization Advance</a> 
			<ul class="submenu">
				<li><a data-url="MOBEntry">Mobilization Advance</a></li>
				<li><a data-url="MobIntCalFrontPage">Mobilization Advance Int. Calc</a></li>
			</ul>
		</li>
		<li><a href="#">Memo of Payment</a> 
			<ul class="submenu">
				<li><a data-url="MemoOfPaymentSDRelease">Memo of Payment - <br/>SD Release / EPF / GST</a></li>
				<li><a data-url="MemoOfPaymentLCess">Memo of Payment - LCess</a></li>
				<li><a data-url="MemoOfPaymentSalary">Memo of Payment - Salary</a></li>
			</ul>
		</li>
		
		<li><a href="#">Bill Transaction</a> 
			<ul class="submenu">
				<?php if($_SESSION['levelid'] == 1){ ?>
				<li><a data-url="WorkRegistration">Bill Registration</a></li>
				<?php } ?>
				<li><a data-url="MeasurementBookPrint_staff_Accounts">Bill Verification</a></li>
				<li><a data-url="MemoOfPaymentCreateAdvance">Advance Payment - Works</a></li>
				<li><a data-url="MemoOfPaymentCreate">Memo of Payment - Works</a></li>
				<li><a data-url="AccountsStatementSteps">MOP Statements - Works</a></li>
				<li><a data-url="MemoOfPaymentCreateMisc">Memo of Payment - Misc.</a></li>
				<li><a data-url="MemoOfPaymentCreate">MOP Statements - Misc.</a></li>
				<li><a data-url="PassOrderCreate">Pass Order</a></li>
				<li><a data-url="PayOrderCreate">Pay Order</a></li>
				<li><a data-url="VoucherEntryList">Voucher Entry</a></li>
			</ul>
		</li>
		<li><a href="#">Works</a> 
			<ul class="submenu">
				<li><a data-url="WorksCSTList">CST Confirm</a></li>
				<li><a data-url="PGEntry">PG Entry</a></li>
				<li><a data-url="SDEntry">SD Entry</a></li>
				<li><a data-url="PGReturn">PG Release</a></li>
				<li><a data-url="SDReturn">SD Release</a></li>
				<li><a data-url="EMDReturn">EMD Return</a></li>
			</ul>
		</li>
	</ul>-->	
</nav>
<input type="hidden" name="hid_check_ds" id="hid_check_ds" />
<input type="hidden" name="hid_check_ds_name" id="hid_check_ds_name" />


<style>
.navbar-inverse{
	background-color:#03BCD3;
}
#main_menu{
	background-color:#03BCD3;
}
.hide{
	display:none;
}
.navbar-collapse{
	padding-left:0px;
	padding-right:0px;
	color:#FFFFFF;
	font: 14px/20px 'Open Sans', sans-serif;
	font-size:12px;
	padding:3px;
	padding-bottom:3px !important;
	font-weight:600;
}
#mySidenav a {
  position: fixed;
  left: 30px;
  transition: 0.3s;
  padding: 10px;
  width: 20px;
  text-decoration: none;
  font-size: 20px;
  color: white;
  border-radius: 0 5px 5px 0;
  white-space:nowrap;
  z-index:9999 !important;
}
#mySidenav a:hover {
  /*left: 0;*/
  width: 200px;
}
#mySidenav > a > .fa{
	line-height:18px;
}
#SMenuAdmin {
  /*top: 128px;*/
  background-color: #079ED5;
}
#SMenuPru {
  /*top: 178px;*/
  background-color: #1154A2;
}
#SMenuPrc {
  /*top: 228px;*/
  background-color: #674BA0;
}
#SMenuSor {
  /*top: 278px;*/
  background-color: #9750A0;
}
#SMenuSorc {
  /*top: 328px;*/
  background-color: #EA4679
}
#SMenuAbstract {
  /*top: 378px;*/
  background-color: #F54040
}
#SMenuCompare {
  /*top: 428px;*/
  background-color: #F16B24
}
#SMenuHistory {
  /*top: 478px;*/
  background-color: #DC9E04
}
#SMenuLCharge {
  /*top: 528px;*/
  background-color: #1AC498
}
#SMenuReports {
  /*top: 578px;*/
  background-color: #C70039
}
.menuLable{
	font-size:12px;
	padding-left:10px;
	cursor:pointer;
	visibility:hidden;
}
#SMenuAdmin:hover .menuLable,#SMenuPru:hover .menuLable,
#SMenuPrc:hover .menuLable,#SMenuSor:hover .menuLable,
#SMenuSorc:hover .menuLable,#SMenuAbstract:hover .menuLable,
#SMenuCompare:hover .menuLable,#SMenuHistory:hover .menuLable,
#SMenuLCharge:hover .menuLable,#SMenuReports:hover .menuLable{
	visibility:visible;
}
</style>

<!--<div class="container12">
  <nav class="navbar1 navbar-inverse">
	<div class="collapse navbar-collapse js-navbar-collapse">
		<?php echo $PageName; ?>
	</div>
  </nav>
</div>-->

<script>
$(document).ready(function(){
	$(document).on("click","a", function(e){
		var DatUrl = $(this).attr("data-url");
		var SplitUrl = DatUrl.split("?");
		var Len = SplitUrl.length;
		if(Len > 0){
			if(Len == 1){
				var Url = SplitUrl[0]+".php";
			}else{
				var Url = SplitUrl[0]+".php?"+SplitUrl[1];
			}
			window.location.href = Url;
		}
	});
	$(document).on("click",".BtnHref", function(e){
		var DatUrl = $(this).attr("data-url");
		var SplitUrl = DatUrl.split("?");
		var Len = SplitUrl.length;
		if(Len > 0){
			if(Len == 1){
				var Url = SplitUrl[0]+".php";
			}else{
				var Url = SplitUrl[0]+".php?"+SplitUrl[1];
			}
			window.location.href = Url;
		}
	});
});
</script>
<input type="hidden" name="hid_check_ds" id="hid_check_ds" />
<input type="hidden" name="hid_check_ds_name" id="hid_check_ds_name" />
<?php 
$Top = 118; $TopIncr = 50;
?>
<div id="mySidenav" class="sidenav">
	<a data-url="WorkList" id="SMenuAdmin" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-cog" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">Works Master</label></a>
	<a data-url="BiddersList" id="SMenuPru" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-user" style="font-size:17px; padding-right:2px;padding-left:2px;"></i><label class="menuLable">Vendors Master</label></a>
	<a data-url="EMDRegister" id="SMenuPrc" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-table" style="font-size:17px; padding-right:2px;padding-left:2px;"></i><label class="menuLable">EMD Register</label></a>
	<a data-url="PGRegister" id="SMenuSor" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-list" style="font-size:17px; padding-right:2px;padding-left:4px;"></i><label class="menuLable">PG Register</label></a>
	<a data-url="SDRegister" id="SMenuSorc" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-university" style="font-size:17px; padding-right:2px;padding-left:2px;"></i><label class="menuLable">SD Register</label></a>
	<a data-url="EMDReturnRemainder" id="SMenuAbstract" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-bell" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">EMD Return Reminders</label></a>
	<a data-url="PGReleaseRemainder" id="SMenuCompare" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-bell" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">PG Release Reminders</label></a>
	<a data-url="SDReleaseRemainder" id="SMenuHistory" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-bell" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">SD Release Reminders</label></a>
	<a data-url="Configuration" id="SMenuLCharge" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-exchange" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">Configuration</label></a>
	<!--<a data-url="Tendering" id="Tendering"><i class="fa fa-table"style="font-size:17px; padding-right:2px;padding-left:2px;"></i><label class="menuLable">Instrument Entry</label></a> -->
</div>
