<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
if(isset($_POST['submit'])){
$userid=trim($_POST['userid']);
$msg='';
$splitrights=explode('*',$rights);
$totpages=$_POST['tot_pages'];

for($p=1;$p<=$totpages;$p++)
{
	if(!is_numeric($_POST['add'.$p]))    { $add=trim($_POST['add'.$p]);       }
	if(!is_numeric($_POST['edit'.$p]))   { $edit=trim($_POST['edit'.$p]);     }
	if(!is_numeric($_POST['del'.$p]))    { $del=trim($_POST['del'.$p]);       }
	if(!is_numeric($_POST['view'.$p]))   { $view=trim($_POST['view'.$p]);     }
	if(!is_numeric($_POST['upload'.$p])) { $upload=trim($_POST['upload'.$p]); }
	if(trim($_POST['pageid'.$p])!='')    { $pageid=$_POST['pageid'.$p];       }
	
	$insertadd="insert into userpriviliages set pageid='$pageid',userid='$userid',ad='$add',del='$del',modd='$edit',vieww='$view',uplad='$upload',active='1',createdt=NOW()"; 
	$rsinsert=mysql_query($insertadd); 
	
	$delrec="delete from userpriviliages where ad='' and del='' and modd='' and vieww='' and uplad=''";
	$rsdelrec=mysql_query($delrec);
}
$pageid="select pageid from userpriviliages where userid='$userid' order by pageid";
$rspageid=mysql_query($pageid);
$page='';

while($row=mysql_fetch_assoc($rspageid)){ $pageid=$row['pageid']; $page=$page.'*'.$pageid;	}
$pageid=ltrim($page,'*');

$updrec="update users set pageid='$pageid' where userid='$userid'";
$rsupdrec=mysql_query($updrec);

if($rsinsert == true && $updrec == true) { 
?>
<script type="text/javascript" language="javascript">
alert("Sucessfully Saved")
window.location.href='userrights.php';
</script>
<?php
}
}
 
 
?>
<?php include "Header.html"; ?>
   <script src="js/jquery.min.js"></script>
   
   <script language="javascript">
  $(document).ready(function(){
   $('input[type="checkbox"]').click(function(){
    if($(this).prop("checked") == true){
		var chkid=$(this).attr('id'); 	
		var value=$(this).val();
		var f=$('.addc'+chkid).val();
		var e=$('.editc'+chkid).val();
		var d=$('.delec'+chkid).val();
		var v=$('.viewc'+chkid).val();
		var u=$('.uploadc'+chkid).val();

		if(f==0) { $('.addc'+chkid).val($.trim(value))     }
   else if(e==1) { $('.editc'+chkid).val($.trim(value))    }
   else	if(d==2) { $('.delec'+chkid).val($.trim(value))    }
   else if(v==3) { $('.viewc'+chkid).val($.trim(value))    } 
   else if(u==4) { $('.uploadc'+chkid).val($.trim(value))  } 
		}
		else {
		 		var chkid=$(this).attr('id'); 	
				var f=$('.addc'+chkid).val();
				var e=$('.editc'+chkid).val();
				var d=$('.delec'+chkid).val();
				var v=$('.viewc'+chkid).val();
				var u=$('.uploadc'+chkid).val();
				
			if(f!='') {  $('.addc'+chkid).val('0')  }
		   	if(e!='') { $('.editc'+chkid).val('1')  }
		   	if(d!='') { $('.delec'+chkid).val('2')  }
		   	if(v!='') { $('.viewc'+chkid).val('3')  }
			if(u!='') { $('.uploadc'+chkid).val('4')  }
		 }
		});
	});
	
	$(document).ready(function(){
	$('.classa'+2).attr("disabled", 'disabled');  //Agreement Upload Add
	$('.classb'+3).attr("disabled", 'disabled');  //Agreement Upload Edit
	$('.classc'+4).attr("disabled", 'disabled');  //Agreement Upload Delete
	$('.classd'+5).attr("disabled", 'disabled');  //Agreement Upload View
	
	$('.classa'+7).attr("disabled", 'disabled');  //View Agreement Sheet Add 
	$('.classb'+8).attr("disabled", 'disabled');  //View Agreement Sheet Edit
	$('.classc'+9).attr("disabled", 'disabled');  //View Agreement Sheet View 
	$('.classe'+11).attr("disabled", 'disabled'); //View Agreement Sheet Upload
	
	$('.classa'+12).attr("disabled", 'disabled');  //Measurement Upload Add
	$('.classb'+13).attr("disabled", 'disabled');  //Measurement Upload Edit
	$('.classc'+14).attr("disabled", 'disabled');  //Measurement Upload Delete
	$('.classd'+15).attr("disabled", 'disabled');  //Measurement Upload View
 
    $('.classe'+21).attr("disabled", 'disabled');  //Measurement Entry Upload
	$('.classe'+26).attr("disabled", 'disabled');  //Generate Upload
	$('.classa'+27).attr("disabled", 'disabled');  //View Running Bill Add
	$('.classb'+28).attr("disabled", 'disabled');  //View Running Bill Edit
	$('.classc'+29).attr("disabled", 'disabled');  //View Running Bill Delete
	$('.classe'+31).attr("disabled", 'disabled');  //View Running Bill Upload	
	});
   </script>
    <body class="page1" id="top">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
				
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1">
                            <div class="title">Module Rights</div>
							<!--<a href="index.php">Logout</a>-->
                            <table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr><td>&nbsp;</td></tr>
								<tr>
								    <td>&nbsp;</td>
								    <td>User Name</td>
									 <td align="right">
										<select name="userid" id="userid" class="" style="width:268px;height:22px;">
										<option value="">---------- Select Username ----------</option>
										<?php
											$sqlusers="select userid,username from users where pageid=''";
											$rsusers=mysql_query($sqlusers);
											while($row=@mysql_fetch_assoc($rsusers)){?><option value="<?php echo $row['userid']; ?>"><?php echo $row['username']; ?></option> <?php }
										?>
										</select>
									</td>
								</tr>
								<tr><td>&nbsp;</td></tr>
								<tr> 
								    <td>&nbsp;</td>
									<td colspan="2">
									    <table border="0" width="100%" cellpadding="0" cellspacing="0" id="tableid">
									        <tr>
												<td align="left">Pages</td>
											    <td align="left">Add</td>
												<td align="left">Edit</td>
												<td align="left">Delete</td>
												<td align="left">View</td>
												<td align="left">Upload</td>
											</tr>
											<tr><td>&nbsp;</td></tr>
											<?php
												$pagemaster="select pageid,pagename from adminpagemaster where active='1'";
												$rspage=mysql_query($pagemaster);
												
												$x=1;
												while($page=@mysql_fetch_object($rspage))
												{
													echo "<tr>";
													echo "<td style='display:none'>$x</td>";
													echo "<td>$page->pagename</td>";
													
													echo "<td><input type='checkbox' name='chkbox' id='" . ($x+1) . "' value='A' class='classa" . ($x+1) . "'></td>";
													echo "<td><input type='checkbox' name='chkbox' id='" . ($x+2) . "' value='M' class='classb" . ($x+2) . "'></td>";
													echo "<td><input type='checkbox' name='chkbox' id='" . ($x+3) . "' value='D' class='classc" . ($x+3) . "'></td>";
													echo "<td><input type='checkbox' name='chkbox' id='" . ($x+4) . "' value='V' class='classd" . ($x+4) . "'></td>";										
													echo "<td><input type='checkbox' name='chkbox' id='" . ($x+5) . "' value='U' class='classe" . ($x+5) . "'></td>";										
													echo "</tr>";
													
													echo "<tr><td>&nbsp;</td></tr>";
													
													echo "<td style='display:none'><input type='text' name='add" . ($x) . "' id='add" . ($x) . "'  value='0' class='addc" . ($x+1) . "''/></td>";
													echo "<td style='display:none'><input type='text' name='edit" . ($x) . "' id='edit" . ($x) . "'  value='1' class='editc" . ($x+2) . "'/></td>";
													echo "<td style='display:none'><input type='text' name='del" . ($x) . "' id='del" . ($x) . "'  value='2' class='delec" . ($x+3) . "'/></td>";
													echo "<td style='display:none'><input type='text' name='view" . ($x) . "' id='view" . ($x) . "'  value='3' class='viewc" . ($x+4) . "'/></td>";
													echo "<td style='display:none'><input type='text' name='upload" . ($x) . "' id='upload" . ($x) . "'  value='4' class='uploadc" . ($x+5) . "'/></td>";
													echo "<td style='display:none'><input type='text' name='pageid". ($x). "' id='pageid". ($x) . "' value='$page->pageid'/>";
													$x=$x+5;		
												}
											?>
											<input type="hidden" name="tot_pages" id="tot_pages" value="<?php echo $x-1; ?>"/>
								            <tr>
												<td colspan="10">
													<center>
													  <input type="submit" class="btn" data-type="submit" value="Submit" name="submit" id="submit" />&nbsp;&nbsp;&nbsp;
													  <input type="reset" name="btn_clear" id="btn_clear" value="Clear"/>&nbsp;&nbsp;&nbsp;
													  <!--<a href="userrightslist.php">View</a>-->
													</center>
												</td>
											</tr>
											
											
										</table>
									  </td>
									</tr>
                            </table>
                          <div class="col2"><?php if ($msg != '') {echo $msg; } ?></div>
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
