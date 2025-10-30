<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
require_once 'library/common.php';
checkUser();
$msg = '';
$userid = $_SESSION['userid'];
?>
<link rel="stylesheet" href="css/menustyle.css" type="text/css" />
<link rel="stylesheet" href="css/button_style.css">
<link rel="stylesheet" type="text/css" href="UploadFormatFile/css/jquery_spreadsheet-Agreement.css">
<script src="js/RightClickRestrict.js"></script>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery-migrate-1.2.1.js"></script>
<script type="text/javascript" src="UploadFormatFile/js/jquery_spreadsheet.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() 
	{ 
		window.history.forward();  
	}
	function close_window()
	{
		window.close();
	}
    $(document).ready(function () {  
         var aData = [ 
		 [ "S.NO/Item No", "DESCRIPTION OF ITEMS", "QUANTITY", "UNIT", "RATE", "AMOUNT", "TYPE" ], 
		 [ "1", "EXCAVATION ", "", "", "", "", "" ], 
		 [ "1.1", "Earth work excavation in all types of soil, hard gravelly soil, clayey soil, mixture of gravel and disintegrated rock for open foundation, trenches etc.", "", "", "", "", "" ], 
		 [ "1.1.1", "Depth up to 2.5 m from unfinished floor level.", "50", "cum", "20", "1000", "" ], 
		 [ "1.1.2", "Depth from 2.5 m to 4.0 m below unfinished floor level.", "500", "cum", "20", "10000", "" ], 
		 [ "2", "REINFORCEMENT STEEL", "", "", "", "", "" ], 
		 [ "2.1", "Providing, straightening, decoiling wherever necessary bending, cutting, binding and fixing in position steel reinforcement TMT bars of Fe 500 D grade", "", "", "", "", "" ], 
		 [ "2.1.1", "From foundation to unfinished floor level & Diameter of bars 20 mm and below", "250", "MT", "100", "25000", "s" ], 
		 [ "2.1.2", "From foundation to unfinished floor level &  Diameter above 20 mm ", "500", "MT", "100", "50000", "s" ], 
		 ];
         $( "div#contents2" ).spreadsheet( 
          { rows: 18, cols: 12, data: aData, zebra_striped: false, read_only: true, rowheader: true, colheader: true } );
    } );
</script>
<style>
 	.title
	{
	  background-color: #014D62;
	  background: url(images/linear_bg_2.png);
	  background-repeat: repeat-x;
	  background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#037595), to(#0A9CC5));/* Safari 4-5, Chrome 1-9 */
	  background: -webkit-linear-gradient(top, #0A9CC5, #037595);/* Safari 5.1, Chrome 10+ */
	  background: -moz-linear-gradient(top, #0A9CC5, #037595);/* Firefox 3.6+ */
	  background: -ms-linear-gradient(top, #0A9CC5, #037595);/* IE 10 */
	  background: -o-linear-gradient(top, #0A9CC5, #037595);/* Opera 11.10+ */
	  color:#FFFFFF;
	  font-weight:bold;
	  font-family:Verdana, Arial, Helvetica, sans-serif;
	  font-size:12px;
	  height:30px;
	  text-align:center;
	  line-height:28px;
	}
	.dosection
	{
		width:49%; 
		height:260px;
		float:left;
		border:1px solid #009999;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		overflow-y:scroll;
	}
	.dontsection
	{
		width:49%; 
		height:260px; 
		float:right;
		border:1px solid #CC0000;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		overflow-y:scroll;
	}
	.dodontcontent
	{
		font-size:11px;
		font-weight:bold;
		color:#0063C6;
		line-height:18px;
		padding-left:2px;
	}
	.docontenthead
	{
		font-size:14px;
		color:#0C7213;
		height:20px;
		line-height:17px;
		background-color:#009999;
		color:#FFFFFF;
	}
	.dontcontenthead
	{
		font-size:14px;
		color:#CA0000;
		height:20px;
		line-height:17px;
		background-color:#EB3051;
		color:#FFFFFF;
	}
</style>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
	<div class="content">
		<div class="container_12">
			<div class="grid_12">
				<blockquote class="bq1" style="background-color:#FFFFFF">
					&nbsp;
					<div class="title">Agreement Upload Sample File Format </div>
					<div id = "contents2" style="text-align:center; width:100%;">
     				</div>
					<div>&nbsp;</div>
					<div style="height:260px">
						<div class="dosection">
							<div class="dodontcontent docontenthead">&nbsp;&nbsp;Do's</div>
							<div class="dodontcontent">1. Agreement heading (date, item no., description, etc...) always should start in 1st row and it should not be empty <font style="color:#03BDE4">(Refer Row No: 1)</font></div>
							<div class="dodontcontent">2. Agreement Soq always should start in 2nd row.</div>
							<div class="dodontcontent">3. 
								&emsp;<font style="color:#740368">(a)</font> 1st column should be an Item No.<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(b)</font> 2nd column should be an Item Description.<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(c)</font> 3rd column should be an Item Qunatity.<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(d)</font> 4th column should be an Item Unit.<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(e)</font> 5th column should be an Item Rate.<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(f)</font> 6th column should be Total Amount(Qunatity x Rate).<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(f)</font> 7th column should be an Item Type.
							</div>
							<div class="dodontcontent">4. 
								&emsp;<font style="color:#740368">(a)</font> For Steel Item, type column should be mentioned as "s".<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(b)</font> For Structural Steel Item, type column should be mentioned as "st".<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(c)</font> For general Item type column should be left as blank. No need to mention anything for this type.<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(c)</font> Do not use single quote '' or double quotes "" in type column.<br/>
							</div>
							<div cla
							<div class="dodontcontent">5. Mention full name of the Year in date field (eg 2014, 2015, 2016 etc....).</div>
							<div class="dodontcontent">6. If Item description having any 'Heading' - the coulmn of Qty, Unit, Rate, Amount, Type should be left as blank. <font style="color:#03BDE4">(Refer Row No: 1 and 6)</font>.</div>
						</div>
						<div class="dontsection">
							<div class="dodontcontent dontcontenthead">&nbsp;&nbsp;Don'ts</div>
							<div class="dodontcontent">1. Don't use any hidden rows and hidden columns.</div>
							<div class="dodontcontent">2. Don't use freeze option for header and also any of rows and columns.</div>
							<div class="dodontcontent">3. Description column should not be empty.</div>
							<div class="dodontcontent">4. Don't use any empty rows.</div>
							<div class="dodontcontent">5. Dont use any special character in Qty, Unit, Rate, Amount and Type. If these fields doesn't have any value please leave it as on.</div>
							<div class="dodontcontent">6. Don't use merge and centre option. </div>
						</div>
					</div>
					<div style="text-align:center;">
						&nbsp;
					</div>
					<div style="text-align:center;">
						<input type="button" name="btn_close" id="btn_close" value=" Close " class="backbutton" onClick="close_window();">
					</div>
				</blockquote>
			</div>
		</div>
	</div>
</body>
</html>
