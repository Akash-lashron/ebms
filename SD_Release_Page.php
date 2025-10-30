<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/common.php';

?>

<?php require_once "Header.html"; ?>
<link rel="stylesheet" href="script/font.css" />
<style>
    tr{ height:25px; }
	table { border:2px double #000000; }
	tr { border: none; }
	.labelheadprint { border: none; font-size:15px; }
	/*.labelheadprint
	{
		font-weight:normal;
		color:#000000;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:15px;
	}*/
	@media print 
	{
		.printbutton
		{
			display: none !important;
		}
		.labelheadprint { border: none; font-size:15px; }
	}
</style>
<script>
  	function goBack()
	{
	   	url = "";
		window.location.replace(url);
	}
	function printBook()
	{
		window.print();
	}
</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1" style="overflow-y:scroll;">
                            <div class="title printbutton">SD Release Page - Print View</div>
							<div style="">
                        	<table width="1060px" border="0" align="center" cellpadding="0" cellspacing="0" class="color4">
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">Government of India</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">Department of Atomic Energy</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">Indira Gandhi Centre for Atomic Research</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">Fast Reactor Fuel Cycle Facility (FRFCF)</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">Civil Engineering Division </td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr>
									<td>&nbsp;</td>
									<td align="left" colspan="3" class="labelheadprint">Ref: IGCAR/FRFCF/CIVIL/Tr.093/SD Release/2018/01 </td>
<!--							        <td align="right" colspan="2" class="labelheadprint">&nbsp;&nbsp;&nbsp;&nbsp;(L.Davy Herbert)<br>Addl. Cheief Engineer<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Construction)</td>
-->									<td align="right" colspan="2" class="labelheadprint">Kalpakkam,<br> August 01,2018.</br></td>
									<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
									<!--<td colspan="2" align="left" class="labelheadprint">
										:&nbsp;&nbsp;&nbsp;&nbsp;<b><u></u></b> 
									</td>-->
								</tr>
								
								
								
								<tr>
									<td>&nbsp;</td>
									<td align="left" colspan="4" class="labelheadprint">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sub: Providing Technical Assistants in Civil Construction works for Nuclear Island area of FRFCF <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;at Kalpakkam - Release the <b>Security deposit</b> recovered Reg.<b>(CC No.84750)</b> </td>
									<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								</tr>	
								
																
									<tr>
									<td>&nbsp;</td>
									<td align="left" colspan="4" class="labelheadprint">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									Ref: 1)Agreement No: IGCAR/FRFCF/CIVIL/A.65/2017<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									     2)T S No: IGCAR/TS/CIVIL/2016/4165 Dt-02.11.2016<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										 3)W O No: IGCAR/FRFCF/CIVIL/Tr-093/WO/2017<br><Br></td>
									</tr>
									
									
								<tr>
									<td>&nbsp;</td>
									<td align="left" colspan="3" class="labelheadprint">
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										Name of Contractor: GRS Enterprises, Kalpakkam<br><br>
									
										It is Certified that;<br>
										
										    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1) The work was completed on 30.04.2018 in all respects before the  schedule date of completion.<br>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2) No apparent defects have been noticed during the defect liability period and the defect liability <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;period is over.<br>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3) There are no recoveries due from the constractor as for as this work is concern.<br>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4) Completion certificate has been furnished on page no: 18 of MB 124<br>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5) Final bill passed on 14.06.2018.
									 </td>
								</tr>
								
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								
								<tr>
									<td>&nbsp;</td>
									<td align="left" colspan="3" class="labelheadprint">
									The Security Deposite with held by Department may please be refunded to the Contractor. 
									 </td>
								</tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								
								<tr>
									<td width="10%">&nbsp;</td>
									
									<!--<td align="left" class="labelheadprint"></td>-->
									<td align="right" colspan="2" class="labelheadprint">&nbsp;&nbsp;&nbsp;&nbsp;(L.Davy Herbert)<br>Addl. Cheief Engineer<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Construction)</td>
									
								</tr><tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr>
									<td>&nbsp;</td>
								<td colspan="3" class="labelheadprint">Encl.: MBook No:126</td>
								</tr>
								
								<tr>
									<td>&nbsp;</td>
								<td colspan="3" class="labelheadprint">Dy. Controller of Accounts,<br>IGCAR, Kalpakkam.</td>
								</tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr>
									<td>&nbsp;</td>
								<td colspan="3" class="labelheadprint">Copy to :1) Assistant Accounts Officer(Works), IGCAR<br>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2) Assistant Accounts Officer(Bills), IGCAR<br>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3) Records
								</td>
								</tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
								<tr><td colspan="4" align="center" class="labelheadprint">&nbsp;</td></tr>
							</table>
							</div>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<div class="buttonsection">
									<input type="button" class="backbutton" name="print" id="print" value="Print" onClick="javascript:window.open('Print_MbookFrontPage.php','','TOOLBAR=NO,RESIZABLE=NO,SCROLLBARS=YES,HEIGHT=350,WIDTH=685,LEFT=100,TOP=40')"/>
								</div>
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
