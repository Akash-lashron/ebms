<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
checkUser();
$msg = '';
$userid = $_SESSION['userid'];
?>
<link rel="stylesheet" href="../css/menustyle.css" type="text/css" />
<link rel="stylesheet" href="../css/button_style.css">
<link rel="stylesheet" type="text/css" href="../UploadFormatFile/css/jquery_spreadsheet.css">
<script src="../js/RightClickRestrict.js"></script>
<script src="../js/jquery.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/jquery-migrate-1.2.1.js"></script>
<script type="text/javascript" src="../UploadFormatFile/js/jquery_spreadsheet.js"></script>
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
		 
		 [ "Item No.", "Item Description", "Qty", "Rate", "Unit", "Amount"],
		 [ "1", "Sample item description for item no 1", "1", "10", "sqm", "10"],
		 [ "2", "Sample item description for item no 2", "2", "20", "Kg", "40"],
		 [ "3", "Sample item description for item no 3", "3", "30", "sqm", "90"],
		 [ "4", "Sample item description for item no 4", "", "", "", ""],
		 [ "4.a", "Sample item description for item no 4.a", "4", "40", "sqm", "160"],
		 [ "4.b", "Sample item description for item no 4.b", "5", "50", "sqm", "250"],
		 ];
         $( "div#contents2" ).spreadsheet( 
          { rows: 10, cols: 11, data: aData, zebra_striped: false, read_only: true, rowheader: true, colheader: true } );
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
	 tr:nth-child(3) > td:nth-child(2), tr:nth-child(4) > td:nth-child(2), tr:nth-child(5) > td:nth-child(2), tr:nth-child(6) > td:nth-child(2), tr:nth-child(7) > td:nth-child(2), tr:nth-child(7) > td:nth-child(5) {
	  background-color: #fff;
	  white-space: nowrap;
	}
	td:nth-child(3), td:nth-child(4) {
	  white-space: nowrap;
	}
	td:nth-child(2), td:nth-child(6) {
	 text-align: center;
	}
	td:nth-child(4), td:nth-child(5) {
	 text-align: right;
	}
	tr:nth-child(8) {
	  text-align: left;
	  background-color: #fff;
	  color: #000000;
	  white-space: nowrap;
	}
	tr:nth-child(2),tr:nth-child(2) > td:nth-child(2) {
	 background-color: #165191;
	 color: #fff;
	 font-weight:500;
	}
</style>
<body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
	<div class="content">
		<div class="container_12">
			<div class="grid_12">
				<blockquote class="bq1" style="background-color:#FFFFFF">
					<div class="title">Financial Bid Excel File Upload Format </div>
					<div id = "contents2" style="text-align:center; width:100%;">
     				</div>
					<div>&nbsp;</div>
					<div style="height:260px">
						<div class="dosection">
							<div class="dodontcontent docontenthead">&nbsp;&nbsp;Do's</div>
							<div class="dodontcontent">1. Department Estimate heading (Item No., Description, etc...) always should be in the first row and it should not be empty </font></div>
							<div class="dodontcontent">2. Uploading data always should start in the Second row.</div>
							<div class="dodontcontent">3. 
								&emsp;<font style="color:#740368">(a)</font> 1st column should be an Item No. field.<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(b)</font> 2nd column should be a Item Description field.<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(c)</font> 3rd column should be a Qty field.<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(d)</font> 4th column should be a Rate field.<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(e)</font> 5th column should be a Unit field.<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(f)</font> 6th column should be a Amount field.
							</div>
							
							<div class="dodontcontent">4. If Item description having any 'Notes' please mention in the same cell under the Item Description.</div>
						</div>
						<div class="dontsection">
							<div class="dodontcontent dontcontenthead">&nbsp;&nbsp;Don'ts</div>
							<div class="dodontcontent">1. Don't use any hidden rows and hidden columns in the uploading Excel Sheet.</div>
							<div class="dodontcontent">2. Don't use freeze option for header and also any of rows and columns.</div>				
							<div class="dodontcontent">3. Item No. and Item Description column should not be empty even though it has Item (Description) Group Heading.</div>
							<div class="dodontcontent">4. Don't use Qty,Rate,Unit,Amount column should be empty except Item (Description) Group Heading.</div>							
							<div class="dodontcontent">5. Don't use any empty rows.</div>							
							<div class="dodontcontent">6. Dont use any special character in Item No., No., Qty, Rate, Amount Column.</div>
							<div class="dodontcontent">7. No need to consider the Total Amount row in the ending row. </div>
							<div class="dodontcontent">8. Don't use merge and centre option. </div>
						</div>
					</div>
					<br/>
					<div style="text-align:center;">
						<input type="button" name="btn_close" id="btn_close" value=" Close " class="backbutton" onClick="close_window();">
					</div>
				</blockquote>
			</div>
		</div>
	</div>
</body>
</html>
