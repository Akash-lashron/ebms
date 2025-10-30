<?php
//session_start();
@ob_start();
require_once '../library/config.php';
require_once '../library/functions.php';
checkUser();
include "../library/common.php";
function dt_display($ddmmyyyy){
 $dt	= explode('-',$ddmmyyyy);
 $dd	= $dt[2];
 $mm	= $dt[1];
 $yy	= $dt[0];
 return $dd.'/'.$mm.'/'.$yy;
}

if((isset($_GET['subdivid'])!="")&&(isset($_GET['sheetid'])!="")&&(isset($_GET['rbn'])!="")){
	$subdivid 	= $_GET['subdivid'];
	$sheetid 	= $_GET['sheetid'];
	$rbn 		= $_GET['rbn'];
	$SLMRbn = $rbn;
	$RABArr = array(); $RABDateArr = array();
	
	$SelectDateQuery1 = "select DATE(min(fromdate)) as mindate, DATE(max(todate)) as maxdate from measurementbook_temp where sheetid = '$sheetid' and rbn = '$rbn' and subdivid = '$subdivid' group by sheetid";
	$SelectDateSql1 = mysql_query($SelectDateQuery1);
	if($SelectDateSql1 == true){
		if(mysql_num_rows($SelectDateSql1)>0){
			$DateList1 = mysql_fetch_object($SelectDateSql1);
			array_push($RABArr,$rbn);
			$RABDateArr[$rbn][0] = $DateList1->mindate;
			$RABDateArr[$rbn][1] = $DateList1->maxdate;
			$RABCount++;
		}
	}
	
	$RABCount = 0;
	$SelectRABQuery2 = "select distinct rbn, DATE(fromdate) as mindate, DATE(todate) as maxdate from measurementbook where sheetid = '$sheetid' and subdivid = '$subdivid' order by rbn desc";// and rbn = '$rbn'";
	$SelectRABSql2 = mysql_query($SelectRABQuery2);
	if($SelectRABSql2 == true){
		if(mysql_num_rows($SelectRABSql2)>0){
			while($DateList2 = mysql_fetch_object($SelectRABSql2)){
				array_push($RABArr,$DateList2->rbn);
				$RABDateArr[$DateList2->rbn][0] = $DateList2->mindate;
				$RABDateArr[$DateList2->rbn][1] = $DateList2->maxdate;
				$RABCount++;
			}
		}
	}

	$SelectItemDescQuery = "select * from schdule where sheet_id = '$sheetid' and subdiv_id = '$subdivid'";// and rbn = '$rbn'";
	$SelectItemDescSql = mysql_query($SelectItemDescQuery);
	if($SelectItemDescSql == true){
		if(mysql_num_rows($SelectItemDescSql)>0){
			$ItemDescList = mysql_fetch_object($SelectItemDescSql);
			$Description = $ItemDescList->description;
			if($ItemDescList->shortnotes != ""){
				$Description = $ItemDescList->shortnotes;
			}
			$ItemNo		= $ItemDescList->sno;
			$ItemUnit 	= $ItemDescList->per;
			$Decimal 	= $ItemDescList->decimal_placed;
			$ItemRate 	= $ItemDescList->rate;
		}
	}
//$RABArr = array(17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39);	
	//$SelectDetailsQuery = "select a.*, b.* from ";
	$RABCount = count($RABArr);
}
//echo $RABCount;exit;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include("PartPayHeader.php"); ?>
    </head>
    <body>
        <div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures">
            <div class="nano">
                <div class="nano-content">
                    <!--<div class="logo">&nbsp;<a><span>Item No. - <?php echo $ItemNo; ?></span></a></div>-->
                    <ul>
					<?php /*if($RABCount > 0){ foreach($RABArr as $RABKey=>$RABValue){ if($SLMRbn == $RABValue){ $ClsA = "active"; }else{ $ClsA = ""; } ?>
                        <li class="RabList <?php echo $ClsA; ?>" data-id="<?php echo $RABValue; ?>"><a class="sidebar-sub-toggle"><i class="ti-calendar"></i> RAB - <?php echo $RABValue; ?> </a></li>
					<?php } } */?>
						
						<div class="panel-group" style="margin-top:10px; border-radius:4px;">
							<div class="panel panel-primary paneA" style="margin-top:0px;">
							  	<div class="panel-heading">Item No. - <?php echo $ItemNo; ?></div>
							  	<div class="panel-body">
									
									<!--<div>
										<div style="height:5px;"></div>
											<input type="button" id="SlmAddRow" class="btn btn-info add_row" data-id="<?php echo $rbn; ?>" value="Add Row">
											<input type="button" id='SlmDelRow' class="btn btn-danger delete_row" data-id="<?php echo $rbn; ?>" value="Delete Row">
										<div style="height:5px;"></div>
									</div>-->
									Item Qty : 2400 cum
							  	</div>
							</div>
						</div>
						<div class="panel-group" style="margin-top:0px; border-radius:4px;">
							<div class="panel panel-primary paneA" style="margin-top:0px;">
							  	<div class="panel-heading">Since Last Part Payment</div>
							  	<div class="panel-body">
									<table class="table SlmTab" id="SLMaddrTab<?php echo $rbn; ?>">
										<thead>
											<tr>
												<th class="text-center">Qty</th>
												<th class="text-center">Rate</th>
												<th class="text-center">( % )</th>
												<th class="text-center">Amount</th>
											</tr>
										</thead>
										<tbody>
											<tr id='addr0'>
												<td><input type="text" name='SlmAddQty0' class="form-control CalcQty small-tbox"/></td>
												<td><input type="text" name='SlmAddRate0' class="form-control small-tbox"/></td>
												<td><input type="text" name='SlmAddPerc0' class="form-control small-tbox"/></td>
												<td><input type="text" name='SlmAddAmount0' class="form-control small-tbox"/></td>
											</tr>
											<!--<tr id='addr<?php echo $rbn; ?>RAB1'></tr>-->
										</tbody>
									</table>
									<!--<div>
										<div style="height:5px;"></div>
											<input type="button" id="SlmAddRow" class="btn btn-info add_row" data-id="<?php echo $rbn; ?>" value="Add Row">
											<input type="button" id='SlmDelRow' class="btn btn-danger delete_row" data-id="<?php echo $rbn; ?>" value="Delete Row">
										<div style="height:5px;"></div>
									</div>-->
							  	</div>
							</div>
						</div>
						
						
						<div class="panel-group" style="margin-top:0px; border-radius:4px;">
							<div class="panel panel-primary paneA" style="margin-top:0px;">
							  	<div class="panel-heading">Deduct Previous Part payment</div>
							  	<div class="panel-body">
							 		<table class="table DpmTab" id="DPMaddrTab<?php echo $rbn; ?>">
										<thead>
											<tr>
												<th class="text-center">Qty</th>
												<th class="text-center">Rate</th>
												<th class="text-center">( % )</th>
												<th class="text-center">Amount</th>
											</tr>
										</thead>
										<tbody>
											<tr id='addr0'>
												<td><input type="text" name='DpmAddQty0' class="form-control CalcQty small-tbox"/></td>
												<td><input type="text" name='DpmAddRate0' class="form-control small-tbox"/></td>
												<td><input type="text" name='DpmAddPerc0' class="form-control small-tbox"/></td>
												<td><input type="text" name='DpmAddAmount0' class="form-control small-tbox"/></td>
											</tr>
											<!--<tr id='addr<?php echo $rbn; ?>RAB1'></tr>-->
										</tbody>
									</table>
									<!--<div>
										<div style="height:5px;"></div>
											<input type="button" id="DpmAddRow" class="btn btn-info add_row" data-id="<?php echo $rbn; ?>" value="Add Row">
											<input type="button" id='DpmDelRow' class="btn btn-danger delete_row" data-id="<?php echo $rbn; ?>" value="Delete Row">
										<div style="height:5px;"></div>
									</div>-->
							  	</div>
							</div>
						</div>
	
                    </ul>
                </div>
            </div>
        </div>
		
		<div class="header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="float-left" style="color:#FFFFFF">
							 <span>Item Description &nbsp;: &nbsp; <?php echo $Description; ?></span>
                        </div>
                        <div class="float-right">
                        </div>
                    </div>
                </div>
            </div>
        </div>
		
        <div class="content-wrap">
            <div class="main">
                <div class="container-fluid">
                    <section id="main-content">
                        <div class="row">
                            <div class="col-lg-12">
							
							<?php if($RABCount > 0){ foreach($RABArr as $RABKey=>$RABValue){ 
								$rbn = $RABValue; $fromdate = $RABDateArr[$rbn][0]; $todate = $RABDateArr[$rbn][1]; $PrevDate = "";
								$SelectDetailsQuery = "select a.*, b.* from mbookheader a inner join mbookdetail b on (a.mbheaderid = b.mbheaderid) where a.sheetid = '$sheetid' and a.subdivid = '$subdivid' and a.date >= '$fromdate' and a.date <= '$todate' order by a.date asc, b.mbheaderid asc, b.mbdetail_id asc";
								//echo $SelectDetailsQuery;exit;
								$SelectDetailsSql = mysql_query($SelectDetailsQuery);
								if($SelectDetailsSql == true){
									if(mysql_num_rows($SelectDetailsSql)>0){
										$slno = 1;
										if($SLMRbn != $rbn){ $Cls = "hide"; }else{ $Cls = ""; }
							?>
								<div class="card RabDetails <?php echo $Cls; ?>" id="RAB<?php echo $rbn; ?>" style="padding-top:1px; margin-top:1px;">
									<div class="card col-12" style="padding:8px; color:#02318A; margin-top:3px;">
										<div class="row">
											<div class="col-9">
												Measurements Period From 
												<span class="roundspan"><?php echo dt_display($fromdate); ?></span> to 
												<span class="roundspan"><?php echo dt_display($todate); ?></span>
												<!--&emsp;Item Qty <span class="roundspan"><?php //echo dt_display($fromdate); ?> </span>&nbsp;&nbsp;<?php echo $ItemUnit; ?>-->
											</div>
											<div class="col-2" align="right">
												Fix All (%)
											</div>
											<div class="col-1">
												<input type="text" class="form-control fixall" name="fix_all[]" data-fixid="<?php echo $rbn; ?>" />
											</div>
										</div>
									</div>
                                    <div class="stat-widget-two">
										<div class="row">
											<div class="col-12">
												<div align="left">
													<?php foreach($RABArr as $RABKey=>$RABValue){ echo '<span class="round-span"> RAB - '.$RABValue.'</span>'; } ?>
												</div>
												<table class="table table-bordered" id="TAB<?php echo $rbn; ?>">
													<thead>
														<tr style="background:#DCDFDF">
															<th style="text-align:center">&nbsp;</th>
															<th style="text-align:center">Date</th>
															<th>Description</th>
															<th style="text-align:right;" nowrap="nowrap">Contents of Area</th>
															<th style="text-align:center" nowrap="nowrap">Paid %</th>
															<th style="text-align:center">( % )</th>
														</tr>
													</thead>
													<tbody>
												<?php while($DetList = mysql_fetch_object($SelectDetailsSql)){ ?>
														<tr>
															<td style="padding:0px 2px 0px 2px;">
																<i style="font-size:21px; padding-top:2px;" class="fa fac1 faQty<?php echo $rbn; ?>" data-qty="<?php echo $DetList->measurement_contentarea; ?>">&#xf058;</i>
															</td>
															<!--<td><?php echo $slno; ?></td>-->
															<td><?php if($PrevDate != $DetList->date){ echo dt_display($DetList->date); } $PrevDate = $DetList->date; ?></td>
															<td align="left"><?php echo $DetList->descwork; ?></td>
															<td align="right" nowrap="nowrap">
																<?php 
																	$CAreaStr  = "";
																	$CAreaStr .= "<table class='ttip'>";
																	$CAreaStr .= "<tr><td align='center' colspan='2' style='text-align:center'>Measurements Details</td></tr>";
																	$CAreaStr .= "<tr><td align='left'>&nbsp;Number &emsp;</td><td>&emsp;".number_format($DetList->measurement_no,$Decimal,'.','')."&nbsp;</td></tr>";
																	$CAreaStr .= "<tr><td align='left'>&nbsp;Length &emsp;</td><td>&emsp;".number_format($DetList->measurement_l,$Decimal,'.','')."&nbsp;</td></tr>";
																	$CAreaStr .= "<tr><td align='left'>&nbsp;Breadth &emsp;</td><td>&emsp;".number_format($DetList->measurement_b,$Decimal,'.','')."&nbsp;</td></tr>";
																	$CAreaStr .= "<tr><td align='left'>&nbsp;Depth &emsp;</td><td>&emsp;".number_format($DetList->measurement_d,$Decimal,'.','')."&nbsp;</td></tr>";
																	$CAreaStr .= "</table>";
																?>
																<span class="ttipcontent" data-toggle="tooltip" data-placement="top" title="<?php echo $CAreaStr; ?>">
																	<?php if($DetList->measurement_contentarea != 0){ echo number_format($DetList->measurement_contentarea,$Decimal,'.',''); } ?>
																</span>
															</td>
															<!--<td><?php echo $ItemUnit; ?></td>-->
															<td>&nbsp;</td>
															<td align="center" style="text-align:center; padding:1px; width:54px;">
															<?php if($DetList->measurement_contentarea != 0){ ?>
																<input type="text" name="txt_ppay_perc[]" id="MBD<?php echo $DetList->mbdetail_id; ?>" data-id="<?php echo $DetList->mbdetail_id; ?>" class="form-control small-tbox mbd"/>
															<?php } ?>
															</td>
														</tr>
												<?php $slno++; } ?>
													</tbody>
												</table>
											</div>
										</div>
                                    </div>
                                </div>
							<?php } } } } ?>
								<br/>
								<div class="card fixedcard">
									ihfihew oihf fgirwglkn 
								</div>
                            </div>
                        </div>
                    </section>
					<input type="hidden" name="viewRAB" id="viewRAB" value="<?php echo $_GET['rbn']; ?>">
					<input type="hidden" name="lastROW" id="lastROW" value="">
					<input type="hidden" name="ItemRate" id="ItemRate" value="<?php echo $ItemRate; ?>">
                </div>
            </div>
        </div>
        <?php include("PartPayFooter.php"); ?>
		
    </body>
	<!--<span class="BottomContent1">
		<div id="side-part1">
			<i style="font-size:24px" class="fa" id="OpenPart2">&#xf013;</i>
			<i style="font-size:24px" class="fa hide" id="ClosePart2">&#xf057;</i>
		</div>
	</span>-->
	<!--<span class="BottomContent2 hide">
		<div id="side-part2" style="background:#fff">
			 <table class="table" id="tab_logic">
				<thead>
					<tr >
						<th class="text-center">Qty</th>
						<th class="text-center">Rate</th>
						<th class="text-center">( % )</th>
						<th class="text-center">Amount</th>
					</tr>
				</thead>
				<tbody>
					<tr id='addr0'>
						<td><input type="text" name='AddQty0'  placeholder='Qty' class="form-control CalcQty"/></td>
						<td><input type="text" name='AddRate0'  placeholder='Rate' class="form-control"/></td>
						<td><input type="text" name='AddPerc0' placeholder='( % )' class="form-control"/></td>
						<td><input type="text" name='AddAmount0' placeholder='' class="form-control"/></td>
					</tr>
                    <tr id='addr1'></tr>
				</tbody>
			</table>
			<div>
				<div style="height:5px;"></div>
				<input type="button" id="add_row" class="backbutton col-sm-3" value="Add Row">
				<input type="button" id='delete_row' class="backbutton col-sm-4" value="Delete Row">
				<div style="height:5px;"></div>
			</div>
			
		</div>
	</span>-->
</html>
<script>
	$(function(){
		
		$('[data-toggle="tooltip"]').tooltip({html:true}); 
		$(".RabList").click(function(event){
			var id = $(this).attr("data-id");
			$('#viewRAB').val(id);
			/// For Display Selected RAB measurements
			$(".RabDetails").addClass("hide");
			$("#RAB"+id).removeClass("hide");
			/// For Highlight Selected RAB 
			$(".RabList").removeClass("active");
			$(this).addClass("active");
		});
		$(".fixall").keyup(function(event){
			var fixperc = $(this).val();
			var fixid = $(this).attr("data-fixid");
			if(fixperc <= 100){
				$("#TAB"+fixid).find("input[name='txt_ppay_perc[]']").each(function(){
					$(this).val(fixperc);
				});
			}else{
				$("#TAB"+fixid).find("input[name='txt_ppay_perc[]']").each(function(){
					$(this).val('');
				});
			}
		});
		
		
		$("#OpenPart2").click(function(event){
			$(this).addClass("hide");
			$('#ClosePart2').removeClass("hide");
			$('.BottomContent2').removeClass("hide");
		});
		$("#ClosePart2").click(function(event){
			$(this).addClass("hide");
			$('#OpenPart2').removeClass("hide");
			$('.BottomContent2').addClass("hide");
		});
		var i=1;
		$("#SlmAddRow").click(function(){
			var dataid = $(this).attr("data-id"); 
			$('#SLMaddrTab'+dataid+' tr:last').after("<tr><td><input name='SlmAddQty"+i+"' type='text' class='form-control CalcQty input-md small-tbox' /></td><td><input name='SlmAddRate"+i+"' type='text' class='form-control input-md small-tbox' /> </td><td><input name='SlmAddPerc"+i+"' type='text' class='form-control input-md small-tbox'></td><td><input name='SlmAddAmount"+i+"' type='text' class='form-control input-md small-tbox'></td></tr>");
	  		i++;
		});
     	$("#SlmDelRow").click(function(){
			var dataid = $(this).attr("data-id"); 
			$("#SLMaddrTab"+dataid+" tr:last").remove();
	 	});
		
		$("#DpmAddRow").click(function(){
			var dataid = $(this).attr("data-id"); 
			$('#DPMaddrTab'+dataid+' tr:last').after("<tr><td><input name='SlmAddQty"+i+"' type='text' class='form-control CalcQty input-md small-tbox' /></td><td><input name='SlmAddRate"+i+"' type='text' class='form-control input-md small-tbox' /> </td><td><input name='SlmAddPerc"+i+"' type='text' class='form-control input-md small-tbox'></td><td><input name='SlmAddAmount"+i+"' type='text' class='form-control input-md small-tbox'></td></tr>");
	  		i++;
		});
     	$("#DpmDelRow").click(function(){
			var dataid = $(this).attr("data-id"); 
			$("#DPMaddrTab"+dataid+" tr:last").remove();
	 	});
		
		//$(".CalcQty").keyup(function(event){
		$('body').on('change', ".CalcQty", function(){
			var ProcessQty = 0;
		 	$(".CalcQty").each(function(){
				var Qty = $(this).val();
				ProcessQty = Number(ProcessQty)+Number(Qty);
			});
			//alert(ProcessQty);
			var viewRAB = $('#viewRAB').val(); 
			var CheckedQty = 0; var TempQty = 0;
			
			$(".faQty"+viewRAB).each(function(){
				$(this).removeClass("fac2").addClass("fac1");
				$(this).closest("tr").css("background-color", "white");
			});
			
			
			$(".faQty"+viewRAB).each(function(){
				var faQty = Number($(this).attr('data-qty'));  
					CheckedQty = Number(CheckedQty) + Number(faQty);
				if(Number(CheckedQty) < Number(ProcessQty)){
					$(this).removeClass("fac1").addClass("fac2");
					TempQty = Number(TempQty) + Number(faQty);
				}else if(Number(CheckedQty) == Number(ProcessQty)){
					$(this).removeClass("fac1").addClass("fac2");
					TempQty = Number(TempQty) + Number(faQty);
					//alert("Yes complete");
					return false;
				}else{
					$(this).removeClass("fac2").addClass("fac1");
					if(TempQty < ProcessQty){
						var Differ = Number(ProcessQty)-Number(TempQty);
						alert("You have to select "+TempQty+ " OR "+CheckedQty)
						$(this).closest("tr").css("background-color", "#F95F66");
					}else{
						//alert("Yes complete");
					}
					return false;
				}
			});
	 	});
		
		
	});
</script>
<style>
	.paneA>.panel-heading{
		background:#0076EC;
		border-color:#0076EC;
		color:#fff;
	}
	.paneA{
		border-color:#0076EC;
	}
	.paneB>.panel-heading{
		background:#40C4FF;
		border-color:#40C4FF;
		color:#fff;
	}
	.paneB{
		border-color:#40C4FF;
	}
	.paneC>.panel-heading{
		background:#FA821C;
		border-color:#FA821C;
		color:#fff;
	}
	.paneC{
		border-color:#FA821C;
	}
	
	
	
	
	.table > thead > tr > th{
		font-weight:normal;
		border-bottom:none;
	}
	.table > tbody > tr > td{
		border-top:none;
	}
	.SlmTab td, .DpmTab td{
    	padding: 2px !important;
	}
	.panel-group{
		margin:8px;
	}
	.btn{
		padding:2px !important;
		padding:2px 8px 2px 8px !important;
		font-size:12px;
		font-weight:500;
		cursor:pointer;
		font-family:Verdana, Arial, Helvetica, sans-serif;
	}
	.fixedcard{
		position:fixed;
		bottom:2px;
		padding:5px;
	}
	.color1{
		border:1px solid #BCD9E7;
	}
	.form-control {
    	height:25px;
		border-color:#6FB7FF;
	}
	.form-control.small-tbox{
		padding: 1px;
		text-align:right;
		font-size:13px;
	}
	tbody tr td {
		font-family: 'Roboto', sans-serif;
		color: #02318A;
		text-shadow:none;
		font-size:12px;
	}
	.ttip tr td {
		color:#FFFFFF;
		font-weight:normal;
		border:1px solid #BCBCBC;
		font-size:14px;
	}
	.hide{
		display:none;	
	}
	.ttipcontent{
		cursor:pointer;
		color:#0045FB;
		/*background:#2A0144;*/
		/*text-decoration:underline;*/
	}
	.ttipcontent:hover{
		color:#F20048;
	}
	.roundspan{	
		padding:2px 10px; 
		border-radius:15px;
		border:1px solid #DC013E;
	}
	.tooltip-inner {
	  	color: #fff;  
	  	background-color:#033FAA;
	}
	.tooltip { opacity: 1 !important; }
	
</style>