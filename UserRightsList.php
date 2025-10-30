<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
$sql_userights="select userid,username,staffid from users where 1=1" ;
$rs_userights = dbQuery($sql_userights);

$Rowcount=0;
if($_GET['msg']!=''){ $msg="Updated Successfully"; }
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
		border: 1px solid #CCC;
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
						  <div align="right"><a href="userrights.php?">AddNew</a></div>
							<blockquote class="bq1">
								<div class="title">User Modules List</div>
								<div class="container" align="center" >
									<?php
									 if($rs_userights == false) { } else { $RowCount = mysql_num_rows($rs_userights); }
									 if ($rs_userights == true && $RowCount > 0) {
									
									?>
								   <div class="heading">
										<div class="col">Staff Code</div>
										<div class="col">Staff Name</div>
										<div class="col">User Name</div>
										<div class="col">Module Name</div>
								   </div>
								   
								    <?php  while ($List = mysql_fetch_object($rs_userights)) { 
										$sqlstaffname="select staffname,staffcode from staff where staffid='$List->staffid'";
										$rstaffname=mysql_query($sqlstaffname,$conn);
										?>
									<div class="table-row">
										<div class="col" align="center"><?php echo @mysql_result($rstaffname,0,'staffcode'); ?> </div>
										<div class="col">&nbsp;&nbsp;<a href="userrights_edit.php?staffid=<?php echo $List->staffid; ?>&userid=<?php echo $List->userid; ?>"><?php echo @mysql_result($rstaffname,0,'staffname'); ?></a> </div>									
										<div class="col">&nbsp;&nbsp;<?php echo $List->username; ?></div>
										
										<?php
											$modules=explode("*",$List->rights);
											$module_name='';
											$sno=1;
											
											for($c=0;$c<count($modules);$c++)
											{
												$x=$modules[$c];
												
												$modulemaster = "select module_name from modulemaster where module_id='$x'";
												$rsmodule=mysql_query($modulemaster,$conn);
												$module_name=$module_name. $sno . '. '  .@mysql_result($rsmodule,0,'module_name').'<br>';
												$sno++;
											}
											
											?>
									<div class="col"><?php echo $module_name; ?></div>
									</div>
										<?php } } else { echo "No Record Found";  }?>
									</div>
								   
									 
									<div class="col2"><?php if ($msg != '') 
										{
											echo $msg;
										} ?>
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
