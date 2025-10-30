<style>
.navbar-inverse{
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
#SMenuPgen {
  /*top: 628px;*/
  background-color:  #DC9E04
}
#SMenuSDen {
  /*top: 678px;*/
  background-color:  #079ED5
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
#SMenuLCharge:hover .menuLable,#SMenuReports:hover .menuLable, #SMenuPgen:hover .menuLable,#SMenuSDen:hover .menuLable {
	visibility:visible;
}
</style>

<div class="container12">
  <nav class="navbar1 navbar-inverse">
	<div class="collapse navbar-collapse js-navbar-collapse">
		<?php echo $PageName; ?>
	</div>
  </nav>
</div>

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
});
</script>
<input type="hidden" name="hid_check_ds" id="hid_check_ds" />
<input type="hidden" name="hid_check_ds_name" id="hid_check_ds_name" />
<div id="mySidenav" class="sidenav">
<?php 
$Top = 128; $TopIncr = 48;
if(isset($_SESSION['ModuleAccArr'])){
	if(in_array("BUD", $_SESSION['ModuleAccArr'])){
		if($_SESSION['isadmin'] == 1){ ?>
			<a data-url="Administrator" id="SMenuAdmin" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-cog" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">Masters</label></a>
	<?php } ?>	
			<a data-url="VoucherUpload" id="SMenuPru" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-calendar-plus-o" style="font-size:17px; padding-right:2px;padding-left:2px;"></i><label class="menuLable">Voucher Upload / Entry</label></a>
			<a data-url="VouchersView" id="SMenuPrc" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-calendar-plus-o" style="font-size:17px; padding-right:2px;padding-left:2px;"></i><label class="menuLable">Vouchers View & Edit</label></a>
			<a data-url="CommittedExpenditureUpdate" id="SMenuSor" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-calendar-check-o" style="font-size:17px; padding-right:2px;padding-left:2px;"></i><label class="menuLable">Committed Expenditure</label></a>
			<a data-url="BudgetExpenditure" id="SMenuSorc" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-rupee" style="font-size:17px; padding-right:2px;padding-left:4px;"></i><label class="menuLable">Budget Expenditure</label></a>
			<a data-url="BudgetFinancialPhysicalProgress" id="SMenuAbstract" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-check-square-o" style="font-size:17px; padding-right:2px;padding-left:2px;"></i><label class="menuLable">Financial & Physical Progress</label></a>
			<a data-url="BudgetVoucherExpenditure" id="SMenuCompare" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-sort-alpha-asc" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">Voucher Expenditure</label></a>
			<a data-url="BudgetExpenditurePlanVariations" id="SMenuHistory" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-balance-scale" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">Expenditure Plan Variations</label></a>
			<a data-url="BudgetReports" id="SMenuLCharge" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-truck" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">Reports</label></a>
			<!--<a data-url="UploadModules" id="SMenuReports" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-history" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">Upload Modules</label></a>-->
			<!--<a data-url="BudgetWorkProgressiveStatus" id="SMenuLCharge"><i class="fa fa-history" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">Work Progressive Status</label></a>-->
			<!--<a data-url="BudgetYearlyExpenditure" id="SMenuReports"><i class="fa fa-truck" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">Yearly Expenditure</label></a>
			<a data-url="BudgetMISReports" id="SMenuReports"><i class="fa fa-truck" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">Reports</label></a>-->
<?php }else{ ?>
			<a data-url="TSViewRegister" id="SMenuAdmin" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-cog" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">Technical Sanction Register</label></a>
			<a data-url="NITViewRegister" id="SMenuPru" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-calendar-plus-o" style="font-size:17px; padding-right:2px;padding-left:2px;"></i><label class="menuLable">NIT Register</label></a>
			<a data-url="DepEstViewRegister" id="SMenuPrc" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-calendar-plus-o" style="font-size:17px; padding-right:2px;padding-left:2px;"></i><label class="menuLable">Department Estimate Details</label></a>
			<a data-url="EMDRegister" id="SMenuSor" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-truck" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">EMD Register</label></a>
			<a data-url="PriceBidViewRegister" id="SMenuSorc" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-rupee" style="font-size:17px; padding-right:2px;padding-left:4px;"></i><label class="menuLable">Financial Bid Register</label></a>
			<a data-url="LOIViewRegister" id="SMenuAbstract" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-check-square-o" style="font-size:17px; padding-right:2px;padding-left:2px;"></i><label class="menuLable">LOI Register</label></a>
			<a data-url="WorkOrderRegister" id="SMenuCompare" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-sort-alpha-asc" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">Work Register</label></a>
			<!--<a data-url="WorkOrder" id="SMenuHistory" style=" <?php //echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-balance-scale" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">Work Order Entry</label></a>-->
			<a data-url="PGRegister" id="SMenuLCharge" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-truck" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">PG Register</label></a>
			<a data-url="SDRegister" id="SMenuPgen" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-calendar" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">SD Register</label></a>
			<a data-url="BiddersList" id="SMenuReports" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-history" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">Bidders Details</label></a>
			<!--<a data-url="SDEntry" id="SMenuSDen" style=" <?php echo 'top:'.$Top.'px'; $Top = $Top + $TopIncr; ?>"><i class="fa fa-rupee" style="font-size:17px; padding-left:2px;"></i><label class="menuLable">SD Entry</label></a>-->
	
<?php } 
}else{
	checkUser();
} 
?>
</div>
