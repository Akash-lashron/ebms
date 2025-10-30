<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
$msg = '';
if($_GET['sheetid'] != "")
{
	$sheetid = $_GET['sheetid'];
	$select_shortnotes_query = "select sch_id, sno, description, shortnotes from schdule where sheet_id = '$sheetid' and description != ''";
	$select_shortnotes_sql = mysql_query($select_shortnotes_query);
	//echo $select_shortnotes_query;
}
if (isset($_POST["save"])) 
{
	$sheetid = $_POST['workorderno'];
	$resid = $_POST['txt_result'];
	$expresid = explode("*",$resid);
	for($i=0; $i<count($expresid); $i++)
	{
		$id = $expresid[$i];
		if($id != "")
		{
			$shortnotes = $_POST['txt_'.$id];
			$update_snotes_sql = "update schdule set shortnotes = '$shortnotes' where sch_id = '$id'";
			$update_snotes_query = mysql_query($update_snotes_sql);
			if($update_snotes_query == true)
			{
				$msg = 1;
			}
		}
	}
	header('Location: ShortnotesList.php?sheetid='.$sheetid.'&msg='.$msg);
}
if($_GET['msg'] != "")
{
	$msg = $_GET['msg'];
}
//echo $str;
?>
<?php require_once "Header.html"; ?>

    <script type="text/javascript"  language="JavaScript">
		function goBack()
	   	{
	   		url = "ShortnotesView.php";
			window.location.replace(url);
	   	}
		window.history.forward();
		function noBack() 
		{ 
			window.history.forward(); 
		}
		function EditData(obj)
		{
			var id = obj.id;
			var value = document.getElementById(id).innerHTML.trim();
			document.getElementById(id).className = "hide";
			var txtheight = document.getElementById("td_"+id).clientHeight;
			document.getElementById("txt_"+id).style.height = txtheight+"px";
			document.getElementById("txt_"+id).value = value;
			document.getElementById("txt_"+id).className = "textboxdisplay";
			document.getElementById("save").className = "backbutton";
			var result = document.form.txt_result.value;
			document.form.txt_result.value = id+"*"+result;
			//alert(txtheight)
		}
	</script>
<style>
	.contentdata
	{
		width:1078px;
		/*height:505px;*/
		overflow:auto;
	}
	td > a
	{
		color:#006BD7;
		text-decoration:underline;
		cursor:pointer;
	}
	.hide
	{
		display:none;
	}
</style>
    <body class="page1" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="top">
<?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1" style="overflow:auto">
                            <div class="title">Short Notes - View </div>		
                          
                                <div class="container">
									<!--<div class="contentdata">-->
                                    <table width="1061px" border="0" align="center" cellpadding="3" cellspacing="3" class="table1">
										<tr class="label" height="35px" style="background-color:#d64242; color:#FFFFFF; font-size:14px">
											<td valign="middle" align="center">Item No.</td>
											<td valign="middle" align="center">Item Description</td>
											<td valign="middle" align="center">Short Notes</td>
										</tr>
									<?php
									if($select_shortnotes_sql == true)
									{
										while($SNList = mysql_fetch_object($select_shortnotes_sql))
										{
									?>
										<tr class="labelhead">
											<td valign="middle" width="80px" align="center"><?php echo $SNList->sno; ?></td>
											<td width="700px"><?php echo $SNList->description; ?></td>
											<td width="280px" valign="middle" id="td_<?php echo $SNList->sch_id;?>">
											<a id="<?php echo $SNList->sch_id;?>" onClick="EditData(this);"><?php echo $SNList->shortnotes; ?></a>
											<textarea name="txt_<?php echo $SNList->sch_id;?>" id="txt_<?php echo $SNList->sch_id;?>" style="width:279px;" class="hide"></textarea>
											</td>
										</tr>
									<?php
										}	
									}
									?>
                                  	</table>
                            		<input type="hidden" name="txt_result" id="txt_result">
									<input type="hidden" name="workorderno" id="workorderno" value="<?php echo $_GET['sheetid']; ?>">
									<input type="hidden" name="txt_msg" id="txt_msg" value="<?php echo $_GET['msg']; ?>">
                         			<!--</div>-->
                                </div>
								<div style="text-align:center">
									<div class="buttonsection" style="display:inline-table">
										<input type="button" onClick="goBack()" class="backbutton" name="back" id="back" value=" Back ">
									</div>
																 
									<div class="buttonsection" style="display:inline-table">
										<input type="submit" class="hide" name="save" value=" Save " id="save"/>
									</div>
								</div>
                        </blockquote>
                    </div>

                </div>
            </div>
    </form>
            <!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
<script>
				var msg = "<?php echo $msg; ?>";
				
				var titletext = "";
				if(msg == 1)
				{
					swal("", "Shortnotes Updated Sucessfully...!", "success");
				}
		function autoResizeDiv()
		{
		   var 	x = document.getElementsByClassName("contentdata");
				x[0].style.height = window.innerHeight-225 +'px';
		}
		window.onresize = autoResizeDiv;
		autoResizeDiv();
	</script>
		      
    
</body>
</html>