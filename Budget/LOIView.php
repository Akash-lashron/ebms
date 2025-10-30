<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
checkUser();
$success = 0;
function dt_display($ddmmyyyy)
{
	$dt=explode('-',$ddmmyyyy);
	//print_r($dt);
	
	$dd=$dt[2];
	$mm=$dt[1];
	$yy=$dt[0];
	
	return $dd .'-'. $mm .'-'.$yy;
}
$result = mysqli_query($dbConn,"SELECT * FROM technical_sanction ORDER BY ts_date asc");
// ORDER BY type asc, group_id asc");
//$result_sql = mysqli_query($dbConn,$result); //mysqli_query($insert_query);


?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript" language="javascript">
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<?php include "MainMenu.php"; ?>
				<div class="container_12">
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1 stable" style="overflow:auto">
						
							<table width="100%" align="center" class="color1 dataTable itemtable rtable table2excel">
								<thead>
									<tr>
										<th align="center" colspan="5" style="background:#136BCA; color:#ffffff; border-color:#136BCA">Technical Sanction-View</th>
									</tr>
									<tr>
										<th valign="middle">SNo.</th>
										<th valign="middle">Technical Sanction No.</th>
										<th valign="middle">Work Name</th>
										<th valign="middle">Amount</th>
										<th valign="middle">Date</th>
									</tr>
								</thead>
								<tbody>
								<tr class='labeldisplay'>
									<?php
										$SNO = 1;
										while($List = mysqli_fetch_object($result)){
									?>
									<td class='tdrowbold' valign='middle' align='center'><?php echo $SNO; ?></td>
									<td valign='middle' class='tdrow' align = 'justify'><?php echo $List->ts_no; ?></td>
									<td valign='middle' class='tdrow' align = 'justify'><?php echo $List->ts_work_name; ?></td>
									<td class='tdrow' align='right' valign='middle'><?php echo $List->ts_amount; ?></td>
									<td class='tdrow' align='center' valign='middle'><?php echo dt_display($List->ts_date); ?></td>
								</tr>
								<?php  $SNO++; } ?>
								</tbody>
							</table>
							<div align="center">
								<input type="button" class="btn btn-info" name="exportToExcel" id="exportToExcel" value="Export - Excel" />
								<!--<input type="submit" class="btn btn-info" name="btn_save" id="btn_save" value="Save" />-->
							</div>
							<div align="center">&nbsp;</div>
						</blockquote>
						
					</div>
				</div>
			</div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
</html>
<script>
$(document).ready(function(){ 
	$('.dataTable').DataTable({"paging":false,"ordering": false});
	$("#exportToExcel").click(function(e){ 
		var table = $('body').find('.table2excel');
		if(table.length){ 
			$(table).table2excel({
				exclude: ".noExl",
				name: "Excel Document Name",
				filename: "SingleLineAbstract-" + new Date().toISOString().replace(/[\-\:\.]/g, "") + ".xls",
				fileext: ".xls",
				exclude_img: true,
				exclude_links: true,
				exclude_inputs: true
				//preserveColors: preserveColors
			});
		}
	});
});
</script>
<script>
	var msg = "<?php echo $msg; ?>";
	var titletext = "";
		document.querySelector('#top').onload = function(){
		if(msg != "")
		{
			//swal(msg, "");
			swal({
				 title: "",
				 text: msg,
				 confirmButtonColor: "#3dae38",
				 type:"success",
				 confirmButtonText: " OK ",
				 closeOnConfirm: false,
			},
			function(isConfirm){
				 if (isConfirm) {
					url = "ShortDescCreate.php";
					window.location.replace(url);
				 }
			});
		}
	};
</script>