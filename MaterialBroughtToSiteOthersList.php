<?php
require_once 'library/config.php';
require_once 'library/functions.php';
include "library/common.php";
checkUser();
$msg = '';
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);

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
    return $dd . '/' . $mm . '/' . $yy;
}
$RowCount = 0;
$UnitArrView = array();
$SelectQuery  =	"SELECT * from unit"; 
$ResultQuery	=	mysql_query($SelectQuery);
if($ResultQuery == true){
	if(mysql_num_rows($ResultQuery)>0){
		while($UnitList = mysql_fetch_object($ResultQuery)){
			$UnitArrView[$UnitList->id] = $UnitList->unit_name;
		}
	}
}
if(isset($_POST['view']) == ' View '){
	$SheetId 		= $_POST['cmb_work_no'];
	$MaterialType 	= $_POST['cmb_type'];
	if($MaterialType == "ALL"){
		$WhereClause = "";
	}else{
		$WhereClause = " and a.mat_code = '$MaterialType'"; 
	}
	$SelectQuery  	= "SELECT a.*, b.* from mat_invoice a inner join material b on (a.mat_code = b.mat_code) where a.active='1' and a.sheetid = '$SheetId' ".$WhereClause." order by a.invoice_dt asc"; 
	$ResultQuery	= mysql_query($SelectQuery);
	if($ResultQuery == true){
		if(mysql_num_rows($ResultQuery)>0){
			$RowCount = 1;
		}
	}
}
?>
<?php require_once "Header.html"; ?>
<script type="text/javascript">
	function goBack(){
	   	url = "MaterialBroughtToSiteOthersListGenerate.php";
		window.location.replace(url);
	}
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">  
                <div class="title">Material View</div>
                <div class="container_12">  
                    <div class="grid_12" align="center"> 
                        <blockquote class="bq1" id="bq1" style="overflow:auto;">
                            <div class="container" align="center">
								<div class="row clearrow"></div>
								<table class="DTable" width="70%">
									<thead>
										<tr align="center">
											<th>SNo.</th>
											<th>Material Name</th>
											<th>Invoice Date</th>
											<th>Invoice No.</th>
											<th>Invoice Qty.</th>
											<th>Unit</th>
											<th>Received at Site On</th>
										</tr>
									</thead>
									<tbody>
									<?php $Sno = 1; if($RowCount == 1){ while($MatList = mysql_fetch_object($ResultQuery)){ ?>
										<tr>
											<td align="center"><?php echo $Sno; ?></td>
											<td><?php echo $MatList->mat_desc; ?></td>
											<td align="center"><?php echo dt_display($MatList->invoice_dt); ?></td>
											<td><?php echo $MatList->invoice_no; ?></td>
											<td align="right"><?php echo $MatList->qty; ?></td>
											<td align="center"><?php echo $UnitArrView[$MatList->qty_unit]; ?></td>
											<td align="center"><?php echo dt_display($MatList->received_dt); ?></td>
										</tr>
									<?php $Sno++; } }else{ ?>
										<tr>
											<td align="center" colspan="7">No Data Found</td>
										</tr>
									<?php } ?>
									</tbody>
								</table>
								
                           </div>
							<div style="text-align:center; height:30px; line-height:30px;" class="printbutton">
								<div class="buttonsection">
									<input type="button" class="backbutton" name="back" value="Back" onClick="goBack();">
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
