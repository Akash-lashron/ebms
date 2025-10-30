<?php
 @ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
$msg = '';
$userid = $_SESSION['userid'];
if($_POST['submit'])
{	
	$Modules = $_POST['check_module'];
	$userid = $_POST['cmb_username'];
	if($Modules != "")
	{
		foreach ($Modules as $MRights)
		{
			$ModuleRights .= $MRights.",";
		}
		//echo $_POST['cmb_username']."<br/>";exit;
		$UpdateMRightsSql = "update users set ModuleRights = '$ModuleRights' where userid = '$userid'";
		$UpdateMRightsQuery = mysql_query($UpdateMRightsSql);
	}
	if($UpdateMRightsQuery == true)
	{
		$msg = "Module Rights Saved Sucessfully.";
		$success = 1;
	}
	else
	{
		$msg = "Error..!";
	}
}
?>
<?php include "Header.html"; ?>
   	<script type="text/javascript">
		window.history.forward();
		function noBack() 
		{ 
			window.history.forward(); 
		}
		function goBack()
	   {
	   		url = "dashboard.php";
			window.location.replace(url);
	   }
	</script>
	<style>
		.moduletable1
		{
			border-collapse:collapse;
			border: 1px solid #C0C0C0;
			background-color:#FFFFFF;
		}
		.trclass1
		{
			border:1px solid #C0C0C0;
		}
		.tdclass1
		{
			border:1px solid #C0C0C0;
		}
		.checkboxcell
		{
			/*background:#EFEFEF;*/
			text-align:right;
			padding-right:15px;
			vertical-align:middle;
		}
		.mainmenutext
		{
			text-align:center;
			vertical-align:middle;
		}
		.divsection
		{
			width:250px;
			vertical-align:middle;
		}
	</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
       <!-- <form action="<?php //echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader" onSubmit="return confirm('Do you really want to Save Designation.?');">-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader" onSubmit="submitform();">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="title">Module Rights</div>
                <div class="container_12">
                    <div class="grid_12">
           				
						<div align="right">&nbsp;&nbsp;</div>

                        	<blockquote id="bq1" class="bq1" style="overflow:scroll">
								<div align="center">
									<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0">
										<tr>
											<td width="200px">&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td class="label">Select User Name</td>
											<td>
												<select name="cmb_username" id="cmb_username" class="textboxdisplay" style="width:300px">
													<option value="">------------------------select-----------------------</option>
											<?php
											$UserSql = "SELECT users.userid, users.username, users.staffid, users.active,
											staff.staffid, staff.staffcode, staff.staffname FROM staff 
											INNER JOIN users ON (users.staffid = staff.staffid)
											WHERE users.active=1 AND staff.sectionid = 1 ORDER BY users.username ASC";
											$UserQuery = mysql_query($UserSql);
										   	if ($UserQuery == true )
										   	{
												while($UserList = mysql_fetch_object($UserQuery))
												{
													echo "<option value='".$UserList->userid."'>".$UserList->username."</option>";
												}
											}
											?>
												</select>
											</td>
											<td>&nbsp;</td>
											<td class="label" align="right">
												Check All &nbsp;<input type="checkbox" id="check_all" name="check_all">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
									</table>
								<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" class="labelhead moduletable1">
<?php
	$MainModuleSql = "select moduleid, moduledname, modulecode, parentcode, type, flag from modules where type = 1 and parentcode = 0";
	$MainModuleQuery = mysql_query($MainModuleSql);
	if($MainModuleQuery == true)
	{
		if(mysql_num_rows($MainModuleQuery)>0)
		{
			while($MainModuleList = mysql_fetch_object($MainModuleQuery))
			{
				$LevelOne = 0;
				$LevelOneSql = "select moduleid, moduledname, modulecode, parentcode, type, flag from modules where type = 2 and parentcode = '$MainModuleList->modulecode'";
				$LevelOneQuery = mysql_query($LevelOneSql);
				if($LevelOneQuery == true)
				{
					if(mysql_num_rows($LevelOneQuery)>0)
					{
						$LevelOne = 1;
					}
				}
				//echo "<table width='1076' border='0' align='center' cellpadding='0' cellspacing='0' class='labelhead moduletable1'>";
				echo "<tr class='trclass1'>";
				
				if($LevelOne == 0)
				{
					echo "<td class='tdclass1 mainmenutext' style='font-weight:bold'>";
					echo $MainModuleList->moduledname;
					echo "</td>";
				}
				else
				{
					echo "<td class='tdclass1 mainmenutext' style='font-weight:bold'>";
					echo $MainModuleList->moduledname;
					echo "</td>";
					echo "<td class='tdclass1'>";
					echo "<table width='100%' border='1' align='center' cellpadding='0' cellspacing='0'>";
					while($LevelOneList = mysql_fetch_object($LevelOneQuery))
					{
						$LevelTwo = 0;
						$LevelTwoSql = "select moduleid, moduledname, modulecode, parentcode, type, flag from modules where type = 3 and parentcode = '$LevelOneList->modulecode'";
						$LevelTwoQuery = mysql_query($LevelTwoSql);
						if($LevelTwoQuery == true)
						{
							if(mysql_num_rows($LevelTwoQuery)>0)
							{
								$LevelTwo = 1;
							}
						}
						echo "<tr class='trclass1'>";
						if($LevelTwo == 0)
						{
							echo "<td class='tdclass1'>";
							echo "&nbsp;".$LevelOneList->moduledname;
							echo "</td>";
							echo "<td class='tdclass1 checkboxcell'>";
							echo "<input type='checkbox' name='check_module[]' value='".$LevelOneList->modulecode."'>";
							echo "</td>";
						}
						else
						{
							echo "<td class='tdclass1 mainmenutext'>";
							echo $LevelOneList->moduledname;
							echo "</td>";
							echo "<td class='tdclass1'>";
							echo "<table width='100%' border='1' align='center' cellpadding='0' cellspacing='0'>";
							while($LevelTwoList = mysql_fetch_object($LevelTwoQuery))
							{
								echo "<tr>";
								echo "<td class='tdclass1'>";
								echo "&nbsp;".$LevelTwoList->moduledname;
								echo "</td>";
								echo "<td class='tdclass1 checkboxcell'>";
								echo "<input type='checkbox' name='check_module[]' value='".$LevelTwoList->modulecode."'>";
								echo "</td>";
								echo "</tr>";
							}
							echo "</table>";
							echo "</td>";
						}
						echo "</tr>";
					}
					echo "</table>";
					echo "</td>";
				}
				
				echo "</tr>";
			}
		}
	}
?>			
							</table>
							<div style="text-align:center">
								<div class="buttonsection"><input type="submit" name="submit" id="submit" data-type="submit" value=" Save "/></div>
								<div class="buttonsection"><input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/></div>
							</div>
                        </blockquote>
                    </div>
                </div>
            </div>
                 <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
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
				
				$("#check_all").click(function(){
					$('input:checkbox').not(this).prop('checked', this.checked);
				});
			</script>
        </form>
		<script src="js/Resize-Page-Auto.js"></script>
    </body>
</html>
