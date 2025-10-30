<?php
//session_start();
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
		 [ "Name of Work", "", "" ], 
		 [ "Technical Sanction No.", "", "" ], 
		 [ "Contractor Name", "", "" ], 
		 [ "Work Order No.", "", "" ], 
		 [ "Agreement No.", "", "" ], 
		 [ "RAB No", "", "", "CC No.", "", "", "", "" ],
		 [ "Date", "Item No.", "Description", "Dia", "No.1", "No.2", "L", "", "" ],
		 [ "02.01.2016", "8.1.a", "F-001 Footings (Some Mesaurements 1)", "8", "2", "1", "0.123", "", "" ],
		 [ "", "", "F-001 Footings (Some Mesaurements 1)", "10", "3", "1", "0.123", "", "" ],
		 [ "", "", "F-001 Footings (Some Mesaurements 2)", "16", "4", "1", "0.345", "", "" ],
		 [ "", "", "F-001 Footings (Some Mesaurements 3)", "20", "5", "1", "0.567", "", "" ],
		 [ "03.01.2016", "8.1.b", "FOOTINGS", "", "", "", "", "", "" ],
		 [ "", "", "Footings 1 (Work Description 1)", "25", "6", "1", "0.123", "", "" ],
		 [ "", "", "Footings 2 (Work Description 2)", "32", "7", "1", "0.345", "", "" ],
		 [ "03.01.2016", "8.5.a", "FOOTINGS", "", "", "", "", "", "" ],
		 [ "", "", "Work Description 1", "8", "11", "1", "0.567", "", "" ],
		 [ "", "", "Work Description 2", "10", "12", "1", "0.567", "", "" ],
		 ];
         $( "div#contents2" ).spreadsheet( 
          { rows: 18, cols: 11, data: aData, zebra_striped: false, read_only: true, rowheader: true, colheader: true } );
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
					<div class="title">Measurement Upload Sample File Format - Steel </div>
					<div id = "contents2" style="text-align:center; width:100%;">
     				</div>
					<div>&nbsp;</div>
					<div style="height:260px">
						<div class="dosection">
							<div class="dodontcontent docontenthead">&nbsp;&nbsp;Do's</div>
							<div class="dodontcontent">1. Measurement heading (date, item no., description, etc...) always should start in 7th row and it should not be empty <font style="color:#03BDE4">(Refer Row No: 7)</font></div>
							<div class="dodontcontent">2. Measurements always should start in 8th row.</div>
							<div class="dodontcontent">3. 
								&emsp;<font style="color:#740368">(a)</font> 1st column should be a Date field.<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(b)</font> 2nd column should be an Item No. field.<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(c)</font> 3rd column should be a Description field.<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(d)</font> 4th column should be a Dia.(Dia of Rod) field.<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(e)</font> 5th column should be a No.(Number) field.<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(f)</font> 6th column should be a L.(Length) field.
							</div>
							<div class="dodontcontent">4. Date format should be DD.MM.YYYY (eg 01.01.2016 , 15.07.2016).</div>
							<div class="dodontcontent">5. Mention full name of the Year in date field (eg 2014, 2015, 2016 etc....).</div>
							<div class="dodontcontent">6. If Item description having any 'Heading' please mention the date and Item No. in same row <font style="color:#03BDE4">(Refer Row No: 12)</font>.</div>
						</div>
						<div class="dontsection">
							<div class="dodontcontent dontcontenthead">&nbsp;&nbsp;Don'ts</div>
							<div class="dodontcontent">1. Don't use any hidden rows and hidden columns.</div>
							<div class="dodontcontent">2. Don't use freeze option for header and also any of rows and columns.</div>
							<div class="dodontcontent">3. Don't use date format as  below <br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(a)</font> MM/DD/YYYY. (eg: 05/31/2016 )<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(b)</font> MM-DD-YYYY. (eg: 05-31-2016 )<br/>
								&emsp;&emsp;&nbsp;<font style="color:#740368">(c)</font> DD.MM.YY. (eg: 05.31.16 )<br/>
							</div>
							<div class="dodontcontent">4. Don't use short form of the year (eg: 2016 as 16, 2015 as 15)</div>
							<div class="dodontcontent">5. Description column should not be empty.</div>
							<div class="dodontcontent">6. Don't use any empty rows.</div>
							<div class="dodontcontent">7. Top six rows should not be empty and it should contain any content.</div>
							<div class="dodontcontent">8. Dont use any special character in Date, Item No., Dia, No. and L. Column if these field dont have any value please be left it as an empty.</div>
							<div class="dodontcontent">9. No need to mention Item Description or shotnotes. </div>
							<div class="dodontcontent">10. Don't use merge and centre option. </div>
						</div>
					</div>
					<div>
						<p><font style="font-family:Verdana, Arial, Helvetica, sans-serif; color:#BF0500; font-weight:bold; font-size:11px">* The Above Instruction are only applicable for "Steel Measurements".</font></p>
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
