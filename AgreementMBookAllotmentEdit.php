<?php	
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
checkUser();
$distinctsheet	= "select DISTINCT sheetid from agreementmbookallotment order by mbno ASC";
$rsdistinct		= mysql_query($distinctsheet);
$RowCount 		= 0;
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
$RowCount = 0;
$staffid = $_SESSION['sid'];
if($_SESSION['isadmin'] == 1){
	$WhereClause = "";
}else{
	$WhereClause = " and (b.assigned_staff LIKE '$staffid,%' OR b.assigned_staff LIKE '%,$staffid,%' OR b.assigned_staff LIKE '%,$staffid')";
}
if($_GET['sheetid'] != ""){
	$sheetid = $_GET['sheetid'];
	$SelectMbookQuery = "select a.allotmentid, a.sheetid, a.mbno, 
					GROUP_CONCAT(CAST(IF(a.mbooktype = 'G', a.mbno, NULL) AS CHAR(15000)) SEPARATOR ', ') as genmb, 
					GROUP_CONCAT(CAST(IF(a.mbooktype = 'S', a.mbno, NULL) AS CHAR(15000)) SEPARATOR ', ') as stlmb, 
					GROUP_CONCAT(CAST(IF(a.mbooktype = 'A', a.mbno, NULL) AS CHAR(15000)) SEPARATOR ', ') as absmb, 
					DATE_FORMAT(a.createddate,'%d-%m-%Y') as mbdate, b.* from agreementmbookallotment a inner join sheet b on (a.sheetid = b.sheet_id) 
					where (b.active = 1 OR b.active = 2) and sheetid='$sheetid'".$WhereClause." GROUP BY a.sheetid";
}else{
     $SelectMbookQuery = "select a.allotmentid, a.sheetid, a.mbno, 
					GROUP_CONCAT(CAST(IF(a.mbooktype = 'G', a.mbno, NULL) AS CHAR(15000)) SEPARATOR ', ') as genmb, 
					GROUP_CONCAT(CAST(IF(a.mbooktype = 'S', a.mbno, NULL) AS CHAR(15000)) SEPARATOR ', ') as stlmb, 
					GROUP_CONCAT(CAST(IF(a.mbooktype = 'A', a.mbno, NULL) AS CHAR(15000)) SEPARATOR ', ') as absmb, 
					DATE_FORMAT(a.createddate,'%d-%m-%Y') as mbdate, b.* from agreementmbookallotment a inner join sheet b on (a.sheetid = b.sheet_id) 
					where (b.active = 1 OR b.active = 2)".$WhereClause." GROUP BY a.sheetid";

}
//echo $SelectMbookQuery;exit;
$SelectMbookSql = mysql_query($SelectMbookQuery);
if($SelectMbookSql ==true){
	if(mysql_num_rows($SelectMbookSql)>0){
		$RowCount = 1;
	}
}
//echo $SelectMbookQuery;exit;
?>
<?php include "Header.html"; ?>

		<script type="text/javascript" language="javascript">
		/*function Delete(staffid)
		{	
		 	if(confirm("delete this designation"))
		 	{
		  	window.location.href='engineerlist.php?delete='+staffid;
		 	}
		}*/
		function goBack()
		{
			url = "AgreementMBookAllotment.php";
			window.location.replace(url);
		}
		function goBackUser()
		{
			url = "MyWorks.php";
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
					<div class="title">Work - Wise MBook Allotment List</div>
           <div class="container_12">
				<div class="grid_12">
                   	<!--<div align="right"><a href="AgreementMBookAllotment.php?page=engineer" <?php echo ModuleRights('MWOA'); ?>>AddNew</a></div>-->
					<blockquote class="bq1" id="bq1" style="overflow:auto">
					<div class="container" align="center">
					
						<table width="99%" class="table1 table2" id="example">
							<thead>
								<tr>
									<th nowrap="nowrap">&nbsp;Slno.&nbsp;</th>
									<th align="left">Name of Work</th>
									<th align="left">Work Order No.</th>
									<th nowrap="nowrap">General MBook Nos.</th>
									<th nowrap="nowrap">Steel MBook Nos.</th>
									<th nowrap="nowrap">Abstract MBook Nos.</th>
									<th nowrap="nowrap">Created On</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
							<?php $slno = 1; if($RowCount == 1){ while($MBList = mysql_fetch_object($SelectMbookSql)){ ?>
								<tr>
									<td align="center"><?php echo $slno; ?></td>
									<td><?php echo $MBList->short_name; ?></td>
									<td><?php echo $MBList->work_order_no; ?></td>
									<td><?php echo $MBList->genmb; ?></td>
									<td><?php echo $MBList->stlmb; ?></td>
									<td><?php echo $MBList->absmb; ?></td>
									<td><?php echo $MBList->mbdate; ?></td>
									<td>
										<!--<a class="btn3 btn3-default btn3-sm Edit" href='AgreementMBookAllotmentEditPage.php?sheetid=<?php echo $MBList->sheetid;?>' <?php echo ModuleRights('MWOE'); ?>>
											<i class="fa fa-edit" style="font-size:17px"></i>EDIT
										</a>-->
									<?php if($_SESSION['isadmin'] == 1) { ?>
										<a class="btn3 btn3-default btn3-sm Delete" href='AgreementMBookAllotmentEditPage.php?sheetid=<?php echo $MBList->sheetid;?>'>
											<i class="fa fa-times" style="font-size:17px"></i> Remove
										</a>
									<?php }else{ ?>
										<a class="btn3 btn3-default btn3-sm Delete" style="pointer-events:none; color:#666666">
											<i class="fa fa-times" style="font-size:17px"></i> Remove
										</a>
									<?php } ?>
									</td>
								</tr>
							<?php $slno++; } } ?>
							</tbody>
						</table>
					
					
								
                    	</div>
						<table width="100%">
							<tr>
								<td align="center">&nbsp;
									
								</td>
							</tr>
							<tr>
								<td align="center">
								<?php if($_SESSION['isadmin'] == 1) { ?>
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								<?php }else{ ?>
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBackUser();"/>
								<?php } ?>
								</td>
							</tr>
						</table>
						<div class="row clearrow"></div>
                 	</blockquote>
				</div>
            </div>
		</div>
<link rel="stylesheet" type="text/css" media="screen" href="dataTable/jquery.dataTables.min.css" />
<script type="text/javascript" src="dataTable/jquery.dataTables.min.js"></script>
<script>
	$(document).ready(function() { 
		$('#example').DataTable({ "info":false }); 
	} );
</script>
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
