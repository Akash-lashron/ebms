<?php
require_once 'library/config.php';
require_once 'library/functions.php';
checkUser();
require_once 'ExcelReader/excel_reader2.php';
$msg = '';
$sheetid = $_SESSION['Sheetid'];
if($_GET['msg'] != "")
{
	$msg = $_GET['msg'];
}

function check_workorder_measurements($sheetid,$supp_sheetid)
{
	$SelectItemIdQuery  = "select a.subdiv_id from subdivision a inner join mbookheader b on (a.subdiv_id = b.subdivid) where a.sheet_id = '$sheetid' and a.supp_sheet_id = '$supp_sheetid' and b.sheetid = '$sheetid'";
	//echo $SelectItemIdQuery;
	$SelectItemIdSql 	= mysql_query($SelectItemIdQuery);
	if(mysql_num_rows($SelectItemIdSql)>0){
		return 1;
	}else{
		return 0;
	}
	/*$check_workorder_measurements_sql = "select mbookheader.mbheaderid, mbookheader.sheetid, mbookdetail.mbdetail_id  from mbookheader 
	INNER JOIN mbookdetail ON (mbookdetail.mbheaderid = mbookheader.mbheaderid) 
	WHERE mbookheader.sheetid = '$sheetid' AND mbookheader.active = 1 AND   mbookdetail.mbdetail_flag != 'd'";*/
	/*$check_workorder_measurements_sql = "select sheet.sheet_id, sheet_supplementary.sheetid, schdule.subdiv_id, mbookheader.mbheaderid, mbookdetail.mbdetail_id 
	from sheet inner join sheet_supplementary on (sheet_supplementary.sheetid = sheet.sheet_id)
	inner join schdule on (sheet_supplementary.sheetid = sheet.sheet_id)
	inner join mbookheader on (mbookheader.sheetid = sheet.sheet_id)
	inner join mbookdetail on (mbookdetail.mbheaderid = mbookheader.mbheaderid)
	where schdule.supp_sheet_id = '$supp_sheetid' and schdule.sheet_id = '$sheetid' and mbookheader.sheetid = '$sheetid' and mbookdetail.mbdetail_flag != 'd'";*/
	//$check_workorder_measurements_sql = "select sheet.sheet_id, sheet_supplementary.sheetid, schdule.subdiv_id from sheet inner join sheet_supplementary on (sheet_supplementary.sheetid = sheet.sheet_id)";
	
	
	//$check_workorder_measurements_query = mysql_query($check_workorder_measurements_sql);
	/*if(mysql_num_rows($check_workorder_measurements_query)>0)
	{
		return 1;
	}
	else
	{
		return 0;
	}*/
}
//$schdulesql ="SELECT      DISTINCT sno,sch_id, sheet_id,  description, total_quantity, rate, per, total_amt, subdiv_id, page_no FROM  schdule   where sheet_id= '$sheetid' AND  sno <> '' AND subdiv_id !=0 ";
$schdulesql ="SELECT a.*, b.* FROM sheet a inner join sheet_supplementary b on (a.sheet_id = b.sheetid) where a.active = 1 and b.active = 1";
$schdulequery=mysql_query($schdulesql);
$RowCount =0;
if(isset($_POST['delete']) == ' Delete ')
{
	$del_sheeid = $_POST['chck_worder'];
	//print_r($del_sheeid);exit;
	$temp = 1;
	$c = count($del_sheeid);
	for($i = 0; $i<$c; $i++)
	{
		$delete_sheet_sql 				= 	"DELETE FROM sheet_supplementary WHERE supp_sheet_id = '$del_sheeid[$i]'" ;
		//echo $delete_sheet_sql;exit;
		$delete_sheet_query 			= 	mysql_query($delete_sheet_sql);
		if($delete_sheet_query == false) { $temp = 0; }
			   
		$delete_schdule_sql 			= 	"DELETE FROM schdule WHERE supp_sheet_id = '$del_sheeid[$i]'" ;
		$delete_schdule_query 			= 	mysql_query($delete_schdule_sql); 
		if($delete_schdule_query == false) { $temp = 0; }
				   
		$delete_division_sql 			= 	"DELETE FROM division WHERE supp_sheet_id = '$del_sheeid[$i]'" ;
		$delete_division_query 			= 	mysql_query($delete_division_sql); 
		if($delete_division_query == false) { $temp = 0; }
				   
		$delete_subdivision_sql 		= 	"DELETE FROM subdivision WHERE supp_sheet_id = '$del_sheeid[$i]'" ;
		$delete_subdivision_query 		= 	mysql_query($delete_subdivision_sql);
		if($delete_subdivision_query == false) { $temp = 0; }
	}
	header('Location: SupplementaryExtraItemEdit.php?msg='.$temp);
}
?>
<?php require_once "Header.html"; ?>
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
border: 1px solid #CCC;
padding:3px;
}
</style>
<script type="text/javascript" language="javascript">
   	function goBack()
	{
	  	url = "SupplementaryExtraItemUpload.php";
		window.location.replace(url);
	}
    $(function() {
		$("#delete").click(function() {  // triggred submit
			var count_checked = $("[name='chck_worder[]']:checked").length; // count the checked
			if(count_checked == 0) 
			{
				//alert("Please select a row to delete.");
				sweetAlert("Please select a row to delete", "", "");
				return false;
			}
			if(count_checked == 1) 
			{
				return confirm("Are you sure you want to delete these row?");
			} 
			else 
			{
				return confirm("Are you sure you want to delete these rows?");
			}
		});

    });
</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
            	<div class="title">View Agreement Sheet</div>
                <div class="container_12">
                    <div class="grid_12">

                        
                        <blockquote class="bq1" style="overflow:auto">
								<div class="container" >
									<br/>
									<div class="heading">
									<div class="col labelcontenthead">&nbsp;</div>
									<div class="col labelcontenthead">SNo</div>
									<div class="col labelcontenthead">Work Order No.</div>
									<div class="col labelcontenthead">Work Short Name</div>
									<div class="col labelcontenthead">Name of Work</div>
									<div class="col labelcontenthead">Technical Sanction No.</div>
									<div class="col labelcontenthead">Name of Contractor</div>
									<div class="col labelcontenthead">Agreement No.</div>
									<div class="col labelcontenthead">C.C.No.</div>
									<div class="col labelcontenthead">W.O.Date</div>
								 </div>
                               <?php
							   $RowCount = mysql_num_rows($schdulequery);
							   if($RowCount == 0)
							   {
							   		$msg = "No Records Found......";
							   }
							   else
							   {
							   $sno = 1;
								   while($List = mysql_fetch_object($schdulequery))
								   {
								   ?>
								 
									<div class="table-row">
										<div class="col " align="center">
										<?php
										$check = check_workorder_measurements($List->sheet_id,$List->supp_sheet_id);
										if($check == 0)
										{
										?>
											<input type="checkbox" name="chck_worder[]" id="chck_worder" value="<?php echo $List->supp_sheet_id; ?>">
										<?php
										}
										else
										{
										?>
											<input type="checkbox" name="chck_worder[]" id="chck_worder" disabled="disabled" value="<?php echo $List->supp_sheet_id; ?>">
										<?php
										}
										?>
										</div>
										<div class="col labelhead"><?php echo $sno; ?> </div>
										<div class="col labelhead" style="color:#1430EF">
											<?php echo $List->work_order_no; ?>
										
										<?php
										//$check = check_workorder_measurements($List->sheet_id);
										//if($check == 0)
										//{
										?>
											<!--<a href="AgreementDetailsEdit.php?sheet_id=<?php echo $List->sheet_id; ?>"><u><?php echo $List->work_order_no; ?></u> </a>-->
										<?php
										//}
										//else
										//{
										?>
											<!--<a class="tooltipwarning" title="Already Measurements Entered for this work order. Unable to Edit."><?php echo $List->work_order_no; ?></a>-->
										<?php
										//}
										?>
										</div>
										<div class="col labelhead"><?php echo $List->short_name; ?> </div>
										<div class="col labelhead"><?php echo $List->work_name; ?> </div>
										<div class="col labelhead"><?php echo $List->tech_sanction; ?> </div>
										<div class="col labelhead"><?php echo $List->name_contractor; ?> </div>
										<div class="col labelhead"><?php echo $List->agree_no; ?> </div>
										<div class="col labelhead"><?php echo $List->computer_code_no; ?> </div>
										<div class="col labelhead"><?php echo $List->work_order_date; ?> </div>
                                                                                <?php // if($sno == 1){ ?><!--<img src="Buttons/accept1.png" style=" height:20px">-->
                                                                                    <?php // } else { ?><!--<img src="Buttons/disable.png" style=" height:20px">--> <?php // } ?>
									</div>
									<?php
									$sno++;
									}
								}
								?>
                            </div>
							<div style="text-align:center; height:30px; line-height:30px;" class="printbutton">
								<div class="buttonsection">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();">
								</div>
								<div class="buttonsection">
									<input type="submit" name="delete" id="delete" value=" Delete " />
								</div>
							</div>	
                        </blockquote>
                        <div>&nbsp;</div>
                        <div></div>
                        </form>
                    </div>

                </div>
                
            </div>
            
             <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
		   <script>
				var msg = "<?php echo $msg; ?>";
				var titletext = "";
				document.querySelector('#top').onload = function(){
					if(msg != "")
					{
						if(msg == "1")
						{
							swal("", "Extra Item Agreement Sucessfully Deleted...!!!", "success");
						}
						if(msg == "0")
						{
							sweetAlert("Unable to Delete the Agreement. Try Again...!!!", "", "");
						}
					}
				};
			</script>
        </form>
    </body>
</html>
