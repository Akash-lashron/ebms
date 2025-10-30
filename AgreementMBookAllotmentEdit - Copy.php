<?php	
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';

$distinctsheet="select DISTINCT sheetid from agreementmbookallotment order by mbno ASC";
$rsdistinct=mysql_query($distinctsheet);
$RowCount =0;

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
			
		</script>
		

        <style>
		.container{
			display:table;
			width:100%;
			border-collapse: collapse;
			}
		.heading{
			 font-weight: bold;
			 display:table-row;
			 background-color:#C91622;
			 text-align: center;
			 line-height: 25px;
			 font-size: 14px;
			 font-family:georgia;
			 color:#fff;
			
		}
		.table-row{  
			 display:table-row;
			 text-align: left;
		}
		.col{
		display:table-cell;
		border: 1px solid #CCC; word-break:break-all;
		}
        </style>
    </head>
    <body class="page1" id="top">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
		<div class="content">
                    <div class="container_12">
			<div class="grid_12">
                            <div align="right"><a href="AgreementMBookAllotment.php?page=engineer">AddNew</a></div>
				<blockquote class="bq1">
				<div class="title">Agreement-Wise MBook Allotment Pages List</div>
				<div class="container" align="center" >
				<?php 
				if ($rsdistinct == false) {  } else {$RowCount = mysql_num_rows($rsdistinct);}
									
				if ($rsdistinct == true && $RowCount > 0) {
				?>
				<div class="heading">
                                    <div class="col" style=" width: 5%">S.No</div>
                                    <div class="col">Work Order No.</div>
										  
                                    <div class="col"> Mbook No.
                                        <div>
                                            <table width="100%">
                                                <tr>
                                                    <td class="col" width="33%">General</td>
                                                    <td class="col" width="33%">Steel</td>
                                                    <td class="col" width="33%">Abstract</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col" style=" width: 5%">Total Pages</div>
                                    <div class="col" style=" width: 8%">Operation</div>
                                    </div>
									   
                                    <?php  
                                    while ($List = mysql_fetch_object($rsdistinct)) 
                                    { 
					$wkorderno="select work_order_no from sheet where sheet_id='$List->sheetid'";
					$rsorderno=mysql_query($wkorderno);
					$Listorderno=mysql_fetch_object($rsorderno);
										
										/*$wkorderno="select sheet.work_order_no from sheet INNER JOIN mbookallotment ON(mbookallotment.sheet_id = agreementmbookallotment.sheet_id) where mbookallotment.sheetid ='$List->sheetid'";
										echo $wkorderno.'<Br>';
										$rsorderno=mysql_query($wkorderno);*/
					$mbNo='';
					$mbno="select mbno,totalpages,mbooktype from agreementmbookallotment where sheetid='$List->sheetid' order by mbno ASC";
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
					<div class="table-row"><?php $sno++; ?>
					<div class="col"><center><?php echo $sno.'.'; ?></center> </div>
					<!--<div class="col"><center><?php echo $Listorderno->work_order_no; ?></center> </div>-->
					<div class="col"><center><?php echo @mysql_result($rsorderno,0,'work_order_no'); ?></center> </div>
					<div class="col">
                                        <div>
                                        <table width="100%">
                                        <tr>
                                        <td class="col" width="33%"><center><?php echo trim($GMbNo,','); ?></center></td>
                                        <td class="col" width="33%"><center><?php echo trim($SMbNo,','); ?></center></td>
                                        <td class="col" width="33%"><center><?php echo trim($AMbNo,','); ?></center></td>
                                        </tr>
                                        </table>
                                        </div>
                                        </div>
					<div class="col"><center><?php echo @mysql_result($rsmbno,0,'totalpages'); ?></center> </div>
					<div class="col"><center><a href='AgreementMBookAllotmentEditPage.php?sheetid=<?php echo $List->sheetid;?>'>&nbsp;&nbsp;Edit</a></center></div>
					</div>
					<?php 
                                        $GMbNo = "";$SMbNo = "";$AMbNo = "";
					}
					}
					else { echo "No Record Found";  }?>
                                </div>
                            </blockquote>
			</div>
	
                    </div>
		</div>
			
            <!--==============================footer=================================-->
            <footer>
                <div class="container_12">
                    <div class="grid_12">
                        <div class="copy">
                            &copy; 2015 | <a href="#">Privacy Policy</a> <br> 	 <a href="#" rel="nofollow">lashron.com</a>
                        </div>
                    </div>
                </div>
            </footer>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
</html>