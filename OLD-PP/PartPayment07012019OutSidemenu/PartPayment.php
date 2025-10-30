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
	
	//$SelectDetailsQuery = "select a.*, b.* from ";
	$RABCount = count($RABArr);
}
//echo $RABCount;exit;
//print_r($RABArr);exit;
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <?php include("CSSLibrary.php"); ?>
</head>

<body class="mini-navbar">
    <!-- Start Left menu area -->
    <div class="left-sidebar-pro">
        <nav id="sidebar" class="active">
            <div class="sidebar-header">
               <strong><a href="index.html"><img src="img/logo/logosn.png" alt="" /></a></strong>
            </div>
            <div class="left-custom-menu-adp-wrap comment-scrollbar">
                <nav class="sidebar-nav left-sidebar-menu-pro">
                    <ul class="metismenu" id="menu1">
                        <!--<li>
                            <a class="has-arrow" href="index.html"><button type="button" class="btn btn-custon-four btn-default colorA1" style="color:#0551E5;">1</button></a>
                            <ul class="submenu-angle" aria-expanded="true" style="background:#205B97; border:1px solid #363838; border-left:none;  box-shadow: 0px 0px 134px 0px #043068 inset;">
                                <li class="GlossyDiv" style="height:400px; overflow:auto; color:#FFFFFF;">
									<table class="table table-bordered tborder1" style="border:none;">
										<thead>
											<tr>
												<td>Date</td>
												<td>Item No</td>
												<td>Contents of Area</td>
												<td>Unit</td>
											</tr>
										</thead>
										<tbody>
										<?php for($k=0; $k<50; $k++){ ?>
											<tr>
												<td>10/12/2018</td>
												<td>1.1.a</td>
												<td>500.000</td>
												<td>cum</td>
											</tr>
										<?php } ?>	
										</tbody>
									</table>
								</li>
                            </ul>
                        </li>-->
						<?php /*$x1= 2; for($j=2; $j<21; $j++){ $ColorCls = "colorA".$x1; if($x1 == 10){ $x1 = 0; } $x1++; ?>
                        <li><a title="RAB - <?= $j; ?>" href="events.html" aria-expanded="false"><button type="button" class="btn btn-custon-four btn-default <?php echo $ColorCls; ?>" style="color:#0551E5;"><?= $j; ?></button></a></li>
						<?php } */?>
						
						<?php if($RABCount > 0){ $x1= 1; foreach($RABArr as $RABKey=>$RABValue){ $ColorCls = "colorA".$x1; if($x1 == 10){ $x1 = 0; } $x1++; if($SLMRbn == $RABValue){ $ClsA = "active"; }else{ $ClsA = ""; } ?>
                        <li>
							<a title="RAB - <?= $RABValue; ?>" href="events.html" aria-expanded="false">
								<button type="button" class="btn btn-custon-four btn-default <?php echo $ColorCls; ?>" style="color:#0551E5;"><?= $RABValue; ?></button>
							</a>
							<ul class="submenu-angle" aria-expanded="true" style="background:#205B97; border:1px solid #363838; border-left:none;  box-shadow: 0px 0px 134px 0px #043068 inset;">
                                <li class="GlossyDiv" style="height:400px; overflow:auto; color:#FFFFFF;">
									<table class="table table-bordered tborder1" style="border:none;">
										<thead>
											<tr>
												<td>Date</td>
												<td>Item No</td>
												<td>Contents of Area</td>
												<td>Unit</td>
											</tr>
										</thead>
										<tbody>
										<?php for($k=0; $k<50; $k++){ ?>
											<tr>
												<td>10/12/2018</td>
												<td>1.1.a</td>
												<td>500.000</td>
												<td>cum</td>
											</tr>
										<?php } ?>	
										</tbody>
									</table>
								</li>
                            </ul>
						</li>
						<?php } } ?>
                    </ul>
                </nav>
            </div>
        </nav>
    </div>
    <!-- End Left menu area -->
    <!-- Start Welcome area -->
    <div class="all-content-wrapper" style="margin-right:80px">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="logo-pro">
                        <a href="index.html"><img class="main-logo" src="img/logo/logo1.png" alt="" /></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-advance-area">
            <div class="header-top-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="header-top-wraper">
                                <div class="row">
                                    
                                    <div class="col-lg-9 col-md-7 col-sm-6 col-xs-9">
                                        <div class="header-top-menu tabl-d-n">
                                            <ul class="nav navbar-nav mai-top-nav">
                                                <li class="nav-item">
													<a class="nav-link">Item No. <?php echo $ItemNo; ?></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-sm text-sm-box text-white">
										<div class="col-sm-7">Agreement Qty</div>
										<div class="col-sm-5">1200.000</div>
										<div class="col-sm-7">Deviated Qty</div>
										<div class="col-sm-5">1200.000</div>
										<div class="col-sm-7">Beyond Dev. Limit Qty</div>
										<div class="col-sm-5">1200.000</div>
									</div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile Menu start -->
            <div class="mobile-menu-area">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="mobile-menu">
                                <nav id="dropdown">
                                    <ul class="mobile-menu-nav">
                                        <li><a data-toggle="collapse" data-target="#Charts" href="#">Home <span class="admin-project-icon edu-icon edu-down-arrow"></span></a>
                                            <ul class="collapse dropdown-header-top">
                                                <li><a href="index.html">Dashboard v.1</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile Menu end -->
            <div class="breadcome-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            &nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
		
		
		
		
		
        <div class="mailbox-area mg-b-15">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="hpanel responsive-mg-b-30">
                            <div class="panel-body no-padding">
                                
                                <ul class="mailbox-list mar-btm-1" style="border:1px solid #2195E3;">
									<li class="active">
                                        <a href="#" style="background:#2195E3; color:#FFFFFF; border:none"><i class="fa fa-envelope text-danger" style="color:#fff"></i>Deduct Previous Part Payment</a>
                                    </li>
                                    <li>
                                        <table class="table mtab DpmTab" id="DPMaddrTab<?php echo $rbn; ?>">
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
										<div align="center">
											<div class="btn-group ib-btn-gp active-hook mail-btn-sd mg-b-15">
												<button class="btn btn-default btn-sm add_row" id="DpmAddRow" data-id="<?php echo $rbn; ?>">Add Row</button>
												<button class="btn btn-default btn-sm delete_row" id="DpmDelRow" data-id="<?php echo $rbn; ?>">Delete Row</button>
											</div>
										</div>
                                    </li>
                                </ul>
								
								<ul class="mailbox-list mar-btm-1" style="border:1px solid #CB4265;">
									<li class="active">
                                        <a href="#" style="background:#E73C67; color:#FFFFFF; border:none"><i class="fa fa-envelope text-danger" style="color:#fff"></i>Since Last Part payment</a>
                                    </li>
                                    <li>
                                        <table class="table mtab SlmTab" id="SLMaddrTab<?php echo $rbn; ?>">
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
										<div align="center">
											<div class="btn-group ib-btn-gp active-hook mail-btn-sd mg-b-15">
												<button class="btn btn-default btn-sm add_row pd-setting" id="SlmAddRow" data-id="<?php echo $rbn; ?>">Add Row</button>
												<button class="btn btn-default btn-sm delete_row" id="SlmDelRow" data-id="<?php echo $rbn; ?>">Delete Row</button>
											</div>
										</div>
                                    </li>
                                </ul>
								<ul class="mailbox-list mar-btm-1" style="border:1px solid #02B7C9;">
                                    <li class="active" style="">
                                        <a href="#" style="background:#02B7C9; color:#FFFFFF; border:none"><span class="pull-right">12,000.00</span><i class="fa fa-envelope text-danger" style="color:#fff;"></i>Total Amount</a>
                                    </li>
                                    <li>
                                        <a href="#" style="color:#0241BE"><span class="pull-right">7000.00</span><i class="fa fa-check"></i> Since Last Amt</a>
                                    </li>
                                    <li>
                                        <a href="#" style="color:#0241BE"><span class="pull-right">5000.00</span><i class="fa fa-check"></i> Deduct Previous Amt</a>
                                    </li>
                                    <li>
                                        <a href="#" style="color:#0241BE"><span class="pull-right">12000.00</span><i class="fa fa-check"></i> Total Paid Amt</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 col-md-9 col-sm-9 col-xs-12">
                        <div class="hpanel">
                            <!--<div class="panel-heading hbuilt mailbox-hd">
                                <div class="text-center p-xs font-normal">
                                    <div class="input-group">
                                        <input type="text" class="form-control input-sm" placeholder="Search email in your inbox..."> <span class="input-group-btn active-hook"> <button type="button" class="btn btn-sm btn-default">Search
											</button> </span></div>
                                </div>
                            </div>-->
                            <div class="panel-body no-padding">
                                <div class="row">
                                    <div class="col-md-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="btn-group ib-btn-gp active-hook mail-btn-sd mg-b-15">
                                            <button class="btn btn-default btn-sm active">RAB</button>
											<?php for($i=1; $i<20; $i++){ ?>
                                            <button class="btn btn-default btn-sm"><?= $i; ?></button>
											<?php } ?>
                                        </div>
                                    </div>
                                    <!--<div class="col-md-6 col-md-6 col-sm-6 col-xs-4 mailbox-pagination">
                                        <div class="btn-group ib-btn-gp active-hook mail-btn-sd mg-b-15">
                                            <button class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i></button>
                                            <button class="btn btn-default btn-sm"><i class="fa fa-arrow-right"></i></button>
                                        </div>
                                    </div>-->
                                </div>
                                <div class="table-responsive ib-tb">
                                    <table class="table table-hover table-mailbox">
                                        <tbody>
                                            <tr class="unread">
                                                <td class="">
                                                    <div class="checkbox checkbox-single checkbox-success">
                                                        <input type="checkbox" checked>
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Jeremy Massey</a></td>
                                                <td><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a>
                                                </td>
                                                <td><i class="fa fa-paperclip"></i></td>
                                                <td class="text-right mail-date">Tue, Nov 25</td>
                                            </tr>
                                            <tr>
                                                <td class="">
                                                    <div class="checkbox">
                                                        <input type="checkbox">
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Marshall Horne</a></td>
                                                <td><a href="#">Praesent nec nisl sed neque ornare maximus at ac enim.</a>
                                                </td>
                                                <td></td>
                                                <td class="text-right mail-date">Wed, Jan 13</td>
                                            </tr>
                                            <tr class="active">
                                                <td class="">
                                                    <div class="checkbox">
                                                        <input type="checkbox">
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Grant Franco</a> <span class="label label-warning">Finance</span></td>
                                                <td><a href="#">Etiam maximus tellus a turpis tempor mollis.</a></td>
                                                <td></td>
                                                <td class="text-right mail-date">Mon, Oct 19</td>
                                            </tr>
                                            <tr class="unread active">
                                                <td class="">
                                                    <div class="checkbox checkbox-single">
                                                        <input type="checkbox" checked>
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Ferdinand Meadows</a></td>
                                                <td><a href="#">Aenean hendrerit ligula eget augue gravida semper.</a></td>
                                                <td><i class="fa fa-paperclip"></i></td>
                                                <td class="text-right mail-date">Sat, Aug 29</td>
                                            </tr>
                                            <tr>
                                                <td class="">
                                                    <div class="checkbox checkbox-single">
                                                        <input type="checkbox">
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Ivor Rios</a> <span class="label label-info">Social</span>
                                                </td>
                                                <td><a href="#">Sed quis augue in nunc venenatis finibus.</a></td>
                                                <td><i class="fa fa-paperclip"></i></td>
                                                <td class="text-right mail-date">Sat, Dec 12</td>
                                            </tr>
                                            <tr>
                                                <td class="">
                                                    <div class="checkbox">
                                                        <input type="checkbox">
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Maxwell Murphy</a></td>
                                                <td><a href="#">Quisque eu tortor quis justo viverra cursus.</a></td>
                                                <td></td>
                                                <td class="text-right mail-date">Sun, May 17</td>
                                            </tr>
                                            <tr>
                                                <td class="">
                                                    <div class="checkbox">
                                                        <input type="checkbox">
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Henry Patterson</a></td>
                                                <td><a href="#">Aliquam nec justo interdum, ornare mi non, elementum
														lacus.</a></td>
                                                <td><i class="fa fa-paperclip"></i></td>
                                                <td class="text-right mail-date">Thu, Aug 06</td>
                                            </tr>
                                            <tr>
                                                <td class="">
                                                    <div class="checkbox">
                                                        <input type="checkbox">
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Brent Rasmussen</a></td>
                                                <td><a href="#">Nam nec turpis sed quam tristique sodales.</a></td>
                                                <td></td>
                                                <td class="text-right mail-date">Sun, Nov 15</td>
                                            </tr>
                                            <tr class="unread">
                                                <td class="">
                                                    <div class="checkbox checkbox-single checkbox-success">
                                                        <input type="checkbox" checked>
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Joseph Hurley</a></td>
                                                <td><a href="#">Nullam tempus leo id urna sagittis blandit.</a></td>
                                                <td><i class="fa fa-paperclip"></i></td>
                                                <td class="text-right mail-date">Sun, Aug 10</td>
                                            </tr>
                                            <tr>
                                                <td class="">
                                                    <div class="checkbox">
                                                        <input type="checkbox">
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Alan Matthews</a></td>
                                                <td><a href="#">Quisque quis turpis ac quam sagittis scelerisque vel ut
														urna.</a></td>
                                                <td></td>
                                                <td class="text-right mail-date">Sun, Mar 27</td>
                                            </tr>
                                            <tr class="active">
                                                <td class="">
                                                    <div class="checkbox">
                                                        <input type="checkbox">
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Colby Lynch</a> <span class="label label-danger">Travel</span></td>
                                                <td><a href="#">Donec non enim pulvinar, ultrices metus eget, condimentum
														mi.</a></td>
                                                <td></td>
                                                <td class="text-right mail-date">Thu, Dec 31</td>
                                            </tr>
                                            <tr class="unread">
                                                <td class="">
                                                    <div class="checkbox checkbox-single checkbox-success">
                                                        <input type="checkbox" checked>
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Jeremy Massey</a></td>
                                                <td><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a>
                                                </td>
                                                <td><i class="fa fa-paperclip"></i></td>
                                                <td class="text-right mail-date">Tue, Nov 25</td>
                                            </tr>
                                            <tr>
                                                <td class="">
                                                    <div class="checkbox">
                                                        <input type="checkbox">
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Marshall Horne</a></td>
                                                <td><a href="#">Praesent nec nisl sed neque ornare maximus at ac enim.</a>
                                                </td>
                                                <td></td>
                                                <td class="text-right mail-date">Wed, Jan 13</td>
                                            </tr>
                                            <tr class="active">
                                                <td class="">
                                                    <div class="checkbox">
                                                        <input type="checkbox">
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Grant Franco</a> <span class="label label-warning">Finance</span></td>
                                                <td><a href="#">Etiam maximus tellus a turpis tempor mollis.</a></td>
                                                <td></td>
                                                <td class="text-right mail-date">Mon, Oct 19</td>
                                            </tr>
                                            <tr class="unread active">
                                                <td class="">
                                                    <div class="checkbox checkbox-single">
                                                        <input type="checkbox" checked>
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Ferdinand Meadows</a></td>
                                                <td><a href="#">Aenean hendrerit ligula eget augue gravida semper.</a></td>
                                                <td><i class="fa fa-paperclip"></i></td>
                                                <td class="text-right mail-date">Sat, Aug 29</td>
                                            </tr>
                                            <tr>
                                                <td class="">
                                                    <div class="checkbox checkbox-single">
                                                        <input type="checkbox">
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Ivor Rios</a> <span class="label label-info">Social</span>
                                                </td>
                                                <td><a href="#">Sed quis augue in nunc venenatis finibus.</a></td>
                                                <td><i class="fa fa-paperclip"></i></td>
                                                <td class="text-right mail-date">Sat, Dec 12</td>
                                            </tr>
                                            <tr>
                                                <td class="">
                                                    <div class="checkbox">
                                                        <input type="checkbox">
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Maxwell Murphy</a></td>
                                                <td><a href="#">Quisque eu tortor quis justo viverra cursus.</a></td>
                                                <td></td>
                                                <td class="text-right mail-date">Sun, May 17</td>
                                            </tr>
                                            <tr>
                                                <td class="">
                                                    <div class="checkbox">
                                                        <input type="checkbox">
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Henry Patterson</a></td>
                                                <td><a href="#">Aliquam nec justo interdum, ornare mi non, elementum
														lacus.</a></td>
                                                <td><i class="fa fa-paperclip"></i></td>
                                                <td class="text-right mail-date">Thu, Aug 06</td>
                                            </tr>
                                            <tr>
                                                <td class="">
                                                    <div class="checkbox">
                                                        <input type="checkbox">
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Brent Rasmussen</a></td>
                                                <td><a href="#">Nam nec turpis sed quam tristique sodales.</a></td>
                                                <td></td>
                                                <td class="text-right mail-date">Sun, Nov 15</td>
                                            </tr>
                                            <tr class="unread">
                                                <td class="">
                                                    <div class="checkbox checkbox-single checkbox-success">
                                                        <input type="checkbox" checked>
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Joseph Hurley</a></td>
                                                <td><a href="#">Nullam tempus leo id urna sagittis blandit.</a></td>
                                                <td><i class="fa fa-paperclip"></i></td>
                                                <td class="text-right mail-date">Sun, Aug 10</td>
                                            </tr>
                                            <tr>
                                                <td class="">
                                                    <div class="checkbox">
                                                        <input type="checkbox">
                                                        <label></label>
                                                    </div>
                                                </td>
                                                <td><a href="#">Alan Matthews</a></td>
                                                <td><a href="#">Quisque quis turpis ac quam sagittis scelerisque vel ut
														urna.</a></td>
                                                <td></td>
                                                <td class="text-right mail-date">Sun, Mar 27</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="panel-footer ib-ml-ft">
                                <i class="fa fa-eye"> </i> 6 unread
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="footer-copyright-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="footer-copy-right">
                            <p>Copyright © 2018. All rights reserved. Template by <a href="https://colorlib.com/wp/templates/">Colorlib</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
    </div>
	
	
    <?php include("JSLibrary.php"); ?>
</body>

</html>