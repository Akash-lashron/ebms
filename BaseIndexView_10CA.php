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
$select_sheet_query = "select sheet_id, work_order_no, short_name, agree_no from sheet where active = 1";
$select_sheet_sql = mysql_query($select_sheet_query);
?>
<?php require_once "Header.html"; ?>
<style>
    
</style>
<script>
	var add_row_s 		= 3;
	var prev_edit_row 	= 0;
  	function goBack()
	{
	   	url = "BaseIndex_10CA.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
input[type="date"]:before {
    content: attr(placeholder) !important;
    color: #aaa;
    margin-right: 0.5em;
  }
  input[type="date"]:focus:before,
  input[type="date"]:valid:before {
    content: "";
  }
.extraItemTextbox {
    height: 30px;
    position: relative;
    outline: none;
    border: 1px solid #98D8FE;
   /* border-color: rgba(0,0,0,.15);*/
    background-color: white;
	color:#0000cc;
	width:98%;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	text-align:center;
}
.extraItemTextArea
{
    position: relative;
    outline: none;
    /*border: 1px solid #cdcdcd;*/
	border: 1px solid #98D8FE;
    /*border-color: rgba(0,0,0,.15);*/
    background-color: white;
	color:#0000cc;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:12px;
}
.gradientbg {
  /* fallback */
  background-color: #014D62;
  width:90%; height:25px; color:#FFFFFF; vertical-align:middle;
  background: url(images/linear_bg_2.png);
  background-repeat: repeat-x;

  /* Safari 4-5, Chrome 1-9 */
  background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#037595), to(#0A9CC5));

  /* Safari 5.1, Chrome 10+ */
  background: -webkit-linear-gradient(top, #0A9CC5, #037595);

  /* Firefox 3.6+ */
  background: -moz-linear-gradient(top, #0A9CC5, #037595);

  /* IE 10 */
  background: -ms-linear-gradient(top, #0A9CC5, #037595);

  /* Opera 11.10+ */
  background: -o-linear-gradient(top, #0A9CC5, #037595);
}
.buttonstyle
{
	background-color:#0A9CC5;
	/*width:80px;*/
	height:25px;
	color:#FFFFFF;
	-moz-box-shadow: 0px 1px 0px 0px #0A9CC5;
	-webkit-box-shadow: 0px 1px 0px 0px #0A9CC5;
	box-shadow: 0px 1px 0px 0px #0A9CC5;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #0080FF), color-stop(1, #0A9CC5));
	background:-moz-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:-webkit-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:-o-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:-ms-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:linear-gradient(to bottom, #0080FF 5%, #0A9CC5 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#0080FF', endColorstr='#0A9CC5',GradientType=0);
	border:1px solid #0080FF;
	display:inline-block;
	cursor:pointer;
	font-weight:bold;

}
.buttonstyle:hover
{
	/*font-size:14px;*/
	/*padding: 0.1em 1em;*/
	-moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    -webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    box-shadow:0px 1px 4px rgba(0,0,0,5);
	background:#E80017;
	border:1px solid #E80017;
}
.buttonstyledisable
{
	background-color:#CECECE;
	color:#A0A0A0;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #E6E6E6), color-stop(1, #CECECE));
	background:-moz-linear-gradient(top, #E6E6E6 5%, #CECECE 100%);
	background:-webkit-linear-gradient(top, #E6E6E6 5%, #CECECE 100%);
	background:-o-linear-gradient(top, #E6E6E6 5%, #CECECE 100%);
	background:-ms-linear-gradient(top, #E6E6E6 5%, #CECECE 100%);
	background:linear-gradient(to bottom, #E6E6E6 5%, #CECECE 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#E6E6E6', endColorstr='#CECECE',GradientType=0);
	border:1px solid #CECECE;
}
.buttonstyledisable:hover
{
	/*font-size:14px;*/
	/*padding: 0.1em 1em;*/
	-moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    -webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    box-shadow:0px 1px 4px rgba(0,0,0,5);
	background:#E6E6E6;
	border:1px solid #E6E6E6;
}
sub {font-size:xx-small; vertical-align:sub;}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                        <div class="title">Base Index - 10CA View</div>
                <div class="container_12">
                  <div class="grid_12" align="center">
					<div align="right"><a href="BaseIndex_10CA.php">Add</a>&nbsp;&nbsp;&nbsp;</div>
                      <blockquote class="bq1" style="overflow:auto">
						<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
							<table width="98%" class="table1" id="table1">
							<?php 
							if($select_sheet_sql == true){
								if(mysql_num_rows($select_sheet_sql)>0){
									while($SheetList = mysql_fetch_object($select_sheet_sql)){
										$sheetid = $SheetList->sheet_id;
							?>
								<tr style="background-color:#EAEAEA;">
									<td class="label">&nbsp;Work Short Name</td>
									<td colspan="5" class="labeldisplay">&nbsp;<?= $SheetList->short_name; ?></td>
								</tr>
								<tr style="background-color:#EAEAEA;">
									<td class="label">&nbsp;Work Order No.</td>
									<td colspan="5" class="labeldisplay">&nbsp;<?= $SheetList->work_order_no; ?></td>
								</tr>
								<tr style="background-color:#EAEAEA;">
									<td class="label">&nbsp;Agreement No.</td>
									<td colspan="5" class="labeldisplay">&nbsp;<?= $SheetList->agree_no; ?></td>
								</tr>
							<?php
										$select_baseindex_query = "select * from base_index where sheetid = '$sheetid' and type = 'TCA'";
										$select_baseindex_sql = mysql_query($select_baseindex_query);
										if($select_baseindex_sql == true){
											if(mysql_num_rows($select_baseindex_sql)>0){
							?>
								<tr class="label">
									<td align="center" rowspan="2" valign="middle" nowrap="nowrap">
										&nbsp;Description
									</td>
									<td align="center" valign="middle" colspan="2">
										Base Index
									</td>
									<td align="center" valign="middle" colspan="2">
										Base Price
									</td>
									<td rowspan="2" align="center" valign="middle">
										Action
									</td>
								</tr>
								<tr class="label">
									<td align="center" valign="middle" nowrap="nowrap">
										Code
									</td>
									<td align="center" valign="middle" nowrap="nowrap">
										Rate <i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></i>
									</td>
									<td align="center" valign="middle">
										Code
									</td>
									<td align="center" valign="middle">
										Rate <i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></i>
									</td>
								</tr>
							<?php
							while($BIList = mysql_fetch_object($select_baseindex_sql)){
							?>
								<tr class="label">
									<td align="center" valign="middle" nowrap="nowrap">
										<?= $BIList->base_index_item; ?>
									</td>
									<td align="center" valign="middle" nowrap="nowrap">
										<?= $BIList->base_index_code; ?>
									</td>
									<td align="center" valign="middle">
										<?= $BIList->base_index_rate; ?>
									</td>
									<td align="center" valign="middle">
										<?= $BIList->base_price_code; ?>
									</td>
									<td align="center" valign="middle">
										<?= $BIList->base_price_rate; ?> 
									</td>
									<td align="center" valign="middle">
										
									</td>
								</tr>
								<?php } } else{?>
								<tr class="label" style="color:#FF6251"><td colspan="6" align="center" valign="middle">Base Index Details Not Entered for this Work.</td></tr>
								 <?php } } } } } ?>
							</table>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
								<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<!--<div class="buttonsection">
									<input type="submit" name="update" id="update" value=" Update "/>
								</div>-->
							</div>
                        </blockquote>
                    </div>
                </div>
            </div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
        </form>
    </body>
</html>
