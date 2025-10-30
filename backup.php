<?php
 @ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
$msg = '';

function backup_tables($host,$user,$pass,$name,$tables = '*')
{

    $link = mysql_connect($host,$user,$pass);
    mysql_select_db($name,$link);
    mysql_query("SET NAMES 'utf8'");

    //get all of the tables
    if($tables == '*')
    {
        $tables = array();
        $result = mysql_query('SHOW TABLES');
        while($row = mysql_fetch_row($result))
        {
            $tables[] = $row[0];
        }
    }
    else
    {
        $tables = is_array($tables) ? $tables : explode(',',$tables);
    }
    $return='';
    //cycle through
    foreach($tables as $table)
    {
        $result = mysql_query('SELECT * FROM '.$table);
        $num_fields = mysql_num_fields($result);

        $return.= 'DROP TABLE '.$table.';';
        $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
        $return.= "\n\n".$row2[1].";\n\n";

        for ($i = 0; $i < $num_fields; $i++) 
        {
            while($row = mysql_fetch_row($result))
            {
                $return.= 'INSERT INTO '.$table.' VALUES(';
                for($j=0; $j<$num_fields; $j++) 
                {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = str_replace("\n","\\n",$row[$j]);
                    if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                    if ($j<($num_fields-1)) { $return.= ','; }
                }
                $return.= ");\n";
            }
        }
        $return.="\n\n\n";
    }

    //save file
	$currtime = date("h-i-sa");
	$currdate = date('Y-m-d');
    $handle = fopen('DataBackUp/ebms_'.$currdate.'__'.$currtime.'.sql','w+');
    $res = fwrite($handle,$return);
    fclose($handle);
	return $res;
}

if($_POST['submit'])
{	
	ini_set("max_execution_time", "-1");
	ini_set("memory_limit", "-1");
	ignore_user_abort(true);
	set_time_limit(0);

	$res = backup_tables($dbHost,$dbUser,$dbPass,$dbName,$tables = '*');
	if($res === false)
	{
		$msg = "Error in taking Back up !";
	}
	else
	{
		$msg = "Backup Stored Sucessfully.";
		$success = 1;
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
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
       <!-- <form action="<?php //echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader" onSubmit="return confirm('Do you really want to Save Designation.?');">-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader" onSubmit="submitform();">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="title">Back Up</div>
                <div class="container_12">
                    <div class="grid_12">
           				
						<!--<div align="right">&nbsp;&nbsp;</div>-->

                        	<blockquote id="bq1" class="bq1">
							
                             <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td colspan="3">&nbsp;</td></tr>
								<tr><td colspan="3">&nbsp;</td></tr>
								<tr><td colspan="3">&nbsp;</td></tr>
								<tr>
                                    <td class="label" colspan="3" align="center"> 
									Please click a <a>Back Up</a> button for take a back up.
									</td>									
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td class="labeldisplay" id="val_design" style="color:red">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="3">&nbsp;</td>
								</tr>
                                <tr>
                                    <td colspan="3" height="50px">
									<div style="text-align:center">
										 <!--<input type="image" src="Buttons/submit.png" onmouseover="this.src='Buttons/submit_hover.png';" onmouseout="this.src='Buttons/submit.png';" class="btn" name="submit" id="submit" data-type="submit" value="Submit" onClick="return validation()"/>&nbsp;&nbsp;&nbsp;&nbsp;-->
										<div class="buttonsection"><input type="submit" name="submit" id="submit" data-type="submit" value="Back Up"/></div>
										<!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
										<div class="buttonsection"><input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/></div>
									</div>
									</td>
								</tr>
							    <tr>
									<td colspan="3">&nbsp;</td>
								</tr>
                            </table>
						
                          <div class="col2"><?php if ($msg != '') {/*echo $msg;*/ } ?></div>
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
			</script>
        </form>
		<script src="js/Resize-Page-Auto.js"></script>
    </body>
</html>
