<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "library/common.php";
$report=0;
$msg = "";
$sucess = 0;
$failure = 0;
if(isset($_POST['btn_submit']))
{
	$delete_id_list = $_POST['ch_mbook'];
	//print_r($delete_id_list);
    $cnt = count($delete_id_list);
    for($i = 0; $i<$cnt; $i++)
    {
		$deleteid = $delete_id_list[$i];
		$lock_release_query = "update send_accounts_and_civil set locked_status = '' where sacid = '$deleteid'";
		$lock_release_sql = mysql_query($lock_release_query);
		if($lock_release_sql == true)
		{
			$sucess++;
		}
		else
		{
			$failure++;
		}
	}
	if($failure>0)
	{
		$msg = "Unable to Release Lock";
	}
	else
	{
		$msg = "MBook Released Successfully";
	}
}
?>
<?php include "Header.html"; ?>
<link rel="stylesheet" type="text/css" media="screen" href="css/fancybox.css" />
<style type="text/css">
    a.fancybox img {
        border: none;
		/*  OLD STYLE
		box-shadow: 0 1px 7px rgba(0,0,0,0.6); 
		 -o-transition: all 0.2s ease-in-out; -ms-transition: all 0.2s ease-in-out; -moz-transition: all 0.2s ease-in-out; -webkit-transition: all 0.2s ease-in-out; transition: all 0.2s ease-in-out;
		*/
        box-shadow: 0 0px 0px rgba(0,0,0,0.6);
        -o-transform: scale(1,1); -ms-transform: scale(1,1); -moz-transform: scale(1,1); -webkit-transform: scale(1,1); transform: scale(1,1); -o-transition: all 0s ease-in-out; -ms-transition: all 0s ease-in-out; -moz-transition: all 0s ease-in-out; -webkit-transition: all 0s ease-in-out; transition: all 0s ease-in-out;
    } 
    a.fancybox:hover img {
        position: relative; z-index: 999; -o-transform: scale(1.03,1.03); -ms-transform: scale(1.03,1.03); -moz-transform: scale(1.03,1.03); -webkit-transform: scale(1.03,1.03); transform: scale(1.03,1.03);
    }
</style>
 <script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.2.1.js"></script>
<script type="text/javascript" src="js/image_enlarge_style_js.js"></script>
<script type="text/javascript">
    $(function($){
        var addToAll = false;
        var gallery = false;
        var titlePosition = 'inside';
        $(addToAll ? 'img' : 'img.fancybox').each(function(){
            var $this = $(this);
            var title = $this.attr('title');
            var src = $this.attr('data-big') || $this.attr('src');
            var a = $('<a href="#" class="fancybox"></a>').attr('href', src).attr('title', title);
            $this.wrap(a);
        });
        if (gallery)
            $('a.fancybox').attr('rel', 'fancyboxgallery');
        $('a.fancybox').fancybox({
            titlePosition: titlePosition
        });
		
		$("#check_all").click(function(){
			$('input:checkbox').not(this).prop('checked', this.checked);
		});

		
    });
    $.noConflict();
	function goBack()
	{
		url = "dashboard.php";
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
		border: 1px solid #CCC;
		}
        </style>
    </head>
<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
</SCRIPT>	
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
				<div class="content">
					<div class="container_12">
						<div class="grid_12">
						  <div align="right"><a href="engineer_Accounts.php?page=engineer">AddNew&nbsp;&nbsp;</a></div>
							<blockquote class="bq1">
								<div class="title">Staff List</div>
								<div class="container" align="center" >
								   <div class="heading">
								   		<div class="col label" style="width:30px;"><input type="checkbox" name="check_all" id="check_all"></div>
										<div class="col label" style="width:30px;color:#FFFFFF">&nbsp;SNo.&nbsp;</div>
										<div class="col label" style="color:#FFFFFF" align="center">MBook No</div>
										<div class="col label" style="color:#FFFFFF">MBook Type</div>
										<div class="col label" style="color:#FFFFFF">Status</div>
										<div class="col label" style="color:#FFFFFF">Locked By</div>
								   </div>
								<?php
								$prev_rbn = ""; $slno = 1;
								$select_mbook_query = "select send_accounts_and_civil.sacid, send_accounts_and_civil.sheetid, send_accounts_and_civil.rbn, 
								send_accounts_and_civil.mbookno, send_accounts_and_civil.mtype, send_accounts_and_civil.locked_status, 
								send_accounts_and_civil.locked_staff, staff.staffname
								from send_accounts_and_civil 
								LEFT JOIN staff ON (staff.staffid = send_accounts_and_civil.locked_staff)
								where send_accounts_and_civil.locked_status = 'locked' ORDER BY send_accounts_and_civil.mbookno ASC";
								$select_mbook_sql = mysql_query($select_mbook_query);
								if($select_mbook_sql == true)
								{
									if(mysql_num_rows($select_mbook_sql)>0)
									{
										while($MBList = mysql_fetch_object($select_mbook_sql))
										{	
											$sacid 			= $MBList->sacid;
											$rbn 			= $MBList->rbn;
											$mbookno 		= $MBList->mbookno;
											$mtype 			= $MBList->mtype;
											$locked_status 	= $MBList->locked_status;
											$staffname 		= $MBList->staffname;
											if($mtype == 'G'){ $mbookStr = "General"; }
											if($mtype == 'S'){ $mbookStr = "Steel"; }
											if($mtype == 'A'){ $mbookStr = "Abstract"; }
										?>
										<div class="table-row">
											<div class="col label" style="width:30px;"><input type="checkbox" name="ch_mbook[]" id="ch_mbook" value="<?php echo $sacid; ?>"></div>
											<div class="col label" style="width:30px;" align="center"><?php echo $slno; ?></div>
											<div class="col label" style="" align="center"><?php echo $mbookno; ?></div>
											<div class="col label" style="" align="center"><?php echo $mbookStr; ?></div>
											<div class="col label" style="" align="center"><?php echo $locked_status; ?></div>
											<div class="col label" style="" align="center"><?php echo $staffname; ?></div>
									   </div>									
									   <?php
											$prev_rbn = $rbn; $slno++;
										}
									}
								}
								?>
								</div>
								<br/>
									<table width="1076">
										<tr>
											<td align="center">&nbsp;
												<input type="submit" class="backbutton" name="btn_submit" id="btn_submit" value="UnLock"/>
											</td>
										</tr>
										<tr>
											<td align="center">
												<!--<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>-->
											</td>
										</tr>
									</table>
							</blockquote>
						</div>
	
					</div>
				</div>
			
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
			
            <script src="js/jquery.hoverdir.js"></script>
			<script>
				var msg = "<?php echo $msg; ?>";
				var failure = "<?php echo $failure; ?>";
				var titletext = "";
				document.querySelector('#top').onload = function(){
					if(msg != "")
					{
						if(failure > 0)
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
