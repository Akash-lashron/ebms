<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "library/common.php";
$report=0;
if(isset($_POST['back']))
 {
     header('Location: CreateUser.php');
 }
if($_GET['delete']!='')
{
	$staffdelete="update staff set active = 0 where staffid='" . $_GET['delete'] . "'";
	$rsstaffdelete=mysql_query($staffdelete);
	
	$staffrecord="update users set active = 0 where staffid='" . $_GET['delete'] . "'";
	$rsrecord=mysql_query($staffrecord);	
	
	if($rsstaffdelete!='') {
	?>
	<script type="text/javascript" language="javascript">
	alert("Sucessfully Deleted");
	window.location.href='EngineerList.php'
	</script>
	<?php
	}
}
$users_list_sql = "SELECT users.username, users.isadmin, users.staffid, staff.staffcode, staff.staffname, 
staff.image, staff.intercom, staff.designationid, designation.designationname 
FROM users 
INNER JOIN staff ON(users.staffid = staff.staffid) 
INNER JOIN designation ON(designation.designationid = staff.designationid) 
WHERE staff.active = 1 AND users.sectionid = 2 AND designation.active = 1 AND users.active = 1";
$users_list_query = mysql_query($users_list_sql);
/*echo $users_list_sql;
$staffsql = "SELECT staff.staffid, staff.staffcode, staff.staffname, staff.image, staff.email, staff.intercom, staff.designationid, staff.active, designation.designationname, designation.designationid, designation.active FROM staff 
INNER JOIN designation ON(designation.designationid = staff.designationid) WHERE staff.active = 1 AND designation.active = 1";
//echo $staffsql;exit;
$staff=mysql_query($staffsql);*/
$RowCount =0;

if($_GET['msg']!=''){ $msg="Updated Successfully"; }
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
    });
    $.noConflict();
</script>
		<script type="text/javascript" language="javascript">
		function goBack()
		{
			url = "dashboard.php";
			window.location.replace(url);
		}
		function check_staff_mbook(staffid)
		{
			var xmlHttp;
            var data;
            var i, j;
			var ret_val = 0;
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "check_staff_mbook.php?staffid=" + staffid;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText; 
					document.form.hid_delete_flag.value = "";
                    if(data != "")
                    {
                       document.form.hid_delete_flag.value = data;
                    }
                }
            }
            xmlHttp.send(strURL);
		}
		
		function Delete(staffid)
		{	
		 	if(confirm("Do You Want to Delete this Engineer Record ?"))
		 	{	
				var delete_flag = document.form.hid_delete_flag.value;
				if(delete_flag == 1)
				{
					alert(" Still MBook is active for this staff \n First delete the mbook and then delete staff..\n");
				}
				else
				{
		  			window.location.href='engineerlist.php?delete='+staffid;
				}
		 	}
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
						<div align="right"><a href="CreateUser_Accounts.php">AddNew&nbsp;&nbsp;</a></div>
							<blockquote class="bq1">
								<div class="title">Users List</div>
								<div class="container" align="center" >
									<?php 
									if ($users_list_query == false) { $msg = "No Records Found..."; } else { $RowCount = mysql_num_rows($users_list_query);    }
									if ($users_list_query == true && $RowCount > 0) {
									  ?>
								   <div class="heading">
								   		<div class="col label" style="width:30px;"></div>
										<div class="col label" style="color:#FFFFFF" align="center">IC No.</div>
										<div class="col label" style="color:#FFFFFF">Staff Name</div>
										<div class="col label" style="color:#FFFFFF">User Name</div>
										<div class="col label" style="color:#FFFFFF">Role Name</div>
										<div class="col label" style="color:#FFFFFF">Intercom No.</div>
										<!--<div class="col label" style="color:#FFFFFF; width:81px">Actions</div>-->
								   </div>
								   	<input type="hidden" name="hid_delete_flag" id="hid_delete_flag">
									  <?php  while ($List = mysql_fetch_object($users_list_query)) 
									  { 
									  	$target_dir = "uploads/";
										$target_file = $target_dir . basename($List->image);
										if (file_exists($target_file)) 
										{
											$staffimage = $List->image;
										}
										else
										{
											$staffimage = "profile_default.png";
										}
										//echo "NAME = ".$target_file."<br/>";
									  ?>
									<div class="table-row"><?php //echo $List->image;?>
									 	<div class="col"><img class="fancybox" title="<?php echo $List->staffname; ?>" src="uploads/<?php echo $staffimage; ?>" width="30px" height="25px"  /> </div>
										<div class="col label" align="center"><?php echo $List->staffcode; ?></div>
										<div class="col label">&nbsp;&nbsp;<?php echo $List->staffname; ?> </div>
										<div class="col label">&nbsp;&nbsp;<?php echo $List->username; ?> </div>
										<div class="col label">&nbsp;&nbsp;<?php echo $List->designationname; ?></div>
										<div class="col label" align="center"><?php echo $List->intercom; ?></div>
										<!--<div class="col" style="padding-top:4px;">
										&nbsp;&nbsp;
										<a href='engineer.php?staffid=<?php echo $List->staffid;?>'>
										<img src="Buttons/edit.png" title="Edit" />
										</a>
                                       &nbsp;|&nbsp;&nbsp;
									   <a href="javascript:Delete(<?php echo  $List->staffid; ?>)">
									   <img src="Buttons/delete.png" title="Delete" onClick="check_staff_mbook(<?php echo  $List->staffid; ?>)" />
									   </a>
									   </div>-->
									</div>
										<?php } } else { echo "<center class='message'><br/>No Records Found..</center>";  }?>
									</div>
									<table width="1076">
										<tr>
											<td align="center">&nbsp;
												
											</td>
										</tr>
										<tr>
											<td align="center">
												<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
											</td>
										</tr>
									</table>
									<!--<div class="col2">
									<?php /*if ($msg != '') 
										{
											echo $msg;
										} */?>
									</div>--><!--<br/><br/><br/>
									<table align="center" width="100%">
									<tr>
									<td width="100%" align="center">
									<br/><br/><br/>
										<input type="submit" name="back" id="back" value=" Back ">
									</td>
									</tr>
									</table>-->
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
