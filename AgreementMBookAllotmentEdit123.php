<?php	
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';

$distinctsheet="select DISTINCT sheetid from agreementmbookallotment order by mbno ASC";
$rsdistinct=mysql_query($distinctsheet);
$RowCount =0;
if($_GET['msg'] != "")
{
	$msgflag = $_GET['msg'];
	if($msgflag == 1)
	{
		$msg = "Selected MBook removed sucessfully..!!";
		$success = 1;
	}
	else
	{
		$msg = "Error..!!";
	}
}
?>
<?php include "Header.html"; ?>


        <script language="JavaScript" type="text/javascript" src="script/Date_Calendar.js"></script>
        <script language="JavaScript" type="text/javascript" src="script/validfn.js"></script>
        <script type="text/javascript" src="js/menuscripts.js"></script>
		<script type="text/javascript" language="javascript">
		function Delete(staffid)
		{	
		 	if(confirm("delete this designation"))
		 	{
		  	window.location.href='engineerlist.php?delete='+staffid;
		 	}
		}
		function goBack()
		{
			url = "AgreementMBookAllotment.php";
			window.location.replace(url);
		}	
		</script>
		

        <style>
		.container{
			display:table;
			width:100%;
			border-collapse: collapse;
			}
		
		.table-row{  
			 display:table-row;
			 text-align: left;
		}
		.col{
		display:table-cell;
		border: 1px solid #CCC; word-break:break-all;
		font-size:12px;
		}
        </style>
    </head>
	<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</SCRIPT>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
		<div class="content">
           <div class="container_12">
				<div class="grid_12">
                   	<div align="right"><a href="AgreementMBookAllotment.php?page=engineer">AddNew</a></div>
					<blockquote class="bq1" id="bq1">
					<div class="title">Agreement-Wise MBook Allotment List</div>
					<div class="container" align="center" >
						<?php 
						if ($rsdistinct == false) {  } else {$RowCount = mysql_num_rows($rsdistinct);}
											
						if ($rsdistinct == true && $RowCount > 0) {
						?>
							<div class="heading">
                                    <div class="col labelcontenthead" style=" width: 5%">S.No</div>
                                    <div class="col labelcontenthead">Work Order No.</div>
                                    <div class="col labelcontenthead"> Mbook No.
                                        <div>
                                            <table width="100%">
                                                <tr>
                                                    <td class="col labelcontenthead" width="33%">General</td>
                                                    <td class="col labelcontenthead" width="33%">Steel</td>
                                                    <td class="col labelcontenthead" width="33%">Abstract</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col labelcontenthead" style=" width: 5%">Total<br/> Pages</div>
                                    <div class="col labelcontenthead" style=" width: 8%">Operation</div>
                           	</div>
									   
                           <?php  
                            while ($List = mysql_fetch_object($rsdistinct)) 
                            { 
								$wkorderno="select work_order_no, active from sheet where sheet_id='$List->sheetid'";
								$rsorderno=mysql_query($wkorderno);
								$Listorderno=mysql_fetch_object($rsorderno);
										
										/*$wkorderno="select sheet.work_order_no from sheet INNER JOIN mbookallotment ON(mbookallotment.sheet_id = agreementmbookallotment.sheet_id) where mbookallotment.sheetid ='$List->sheetid'";
										echo $wkorderno.'<Br>';
										$rsorderno=mysql_query($wkorderno);*/
								$mbNo='';
								$mbno="select mbno,totalpages,mbooktype from agreementmbookallotment where sheetid='$List->sheetid' AND active = 1 order by mbno ASC";
								$rsmbno=mysql_query($mbno);
										while($res=mysql_fetch_object($rsmbno)) 
                                        {
                                            if($res->mbooktype == "G")
                                            {
                                             $GMbNo .= $res->mbno.',';
                                            }
                                            if($res->mbooktype == "S")
                                            {
                                             $SMbNo .= $res->mbno.',';
                                            }
                                            if($res->mbooktype == "A")
                                            {
                                             $AMbNo .= $res->mbno.',';
                                            }
                                        // $mbNo=$mbNo.','.$res->mbno; 
                                                                                     
                                        }
							?>
							<div class="table-row label"><?php $sno++; ?>
								<div class="col" label><center><?php echo $sno.'.'; ?></center> </div>
								<!--<div class="col"><center><?php echo $Listorderno->work_order_no; ?></center> </div>-->
								<div class="col" label><center><?php echo @mysql_result($rsorderno,0,'work_order_no'); ?></center> </div>
								<div class="col">
													<div>
													<table width="100%">
													<tr>
													<td class="col label" width="33%"><center><?php echo trim($GMbNo,','); ?></center></td>
													<td class="col label" width="33%"><center><?php echo trim($SMbNo,','); ?></center></td>
													<td class="col label" width="33%"><center><?php echo trim($AMbNo,','); ?></center></td>
													</tr>
													</table>
													</div>
													</div>
								<div class="col"><center><?php echo @mysql_result($rsmbno,0,'totalpages'); ?></center> </div>
								<div class="col">
									<center>
										<?php 
										if($Listorderno->active == 1)
										{ 
										?>
											<a href='AgreementMBookAllotmentEditPage.php?sheetid=<?php echo $List->sheetid;?>'>&nbsp;&nbsp;Edit</a>
										<?php
										}
										else
										{
										?>
											<a class="tooltipwarning" title="This Workorder is Inactive">&nbsp;&nbsp;Edit</a>
										<?php
										}
										?>
									</center>
								</div>
							</div>
							<?php 
							  $GMbNo = "";$SMbNo = "";$AMbNo = "";
							}
							}
							else 
							{ 
								echo "<center class='message'><br/>No Records Found..</center>";  
							}?>
								
                    	</div>
						<table width="1076">
							<tr>
								<td align="center">&nbsp;
									
								</td>
							</tr>
							<tr>
								<td align="center">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</td>
							</tr>
						</table>
                 	</blockquote>
				</div>
            </div>
		</div>
			
            <!--==============================footer=================================-->
            <?php   include "footer/footer.html"; ?>
			<script>
				var msg = "<?php echo $msg; ?>";
				var success = "<?php echo $success; ?>";
				var titletext = "";
				document.querySelector('#top').onload = function(){
				if(msg != "")
				{
					if(success == 1)
					{
						swal("", msg, "success");
					}
					else
					{
						swal(msg, "", "");
					}
				}
				};
			</script>
        </form>
    </body>
</html>