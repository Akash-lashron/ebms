<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
checkUser();
$msg = '';
if($_GET['designationid']!="")
{
	$sql_select="select * from designation where designationid='" . $_GET['designationid'] . "' ";
	$rs_select=mysql_query($sql_select);
	$designationid=@mysql_result($rs_select,0,'designationid');
	$designame=@mysql_result($rs_select,0,'designationname');
}
if($_POST['submit'])
{	
 	$designame=trim($_POST['designame']);
 	$designationid=trim($_POST['get_desigid']);
 	if($designationid!='') 
 	{
		$designationinsertupdate="update designation set designationname='$designame' where  designationid='$designationid'"; 
		$desiginsertupdateresult=mysql_query($designationinsertupdate);
		if($desiginsertupdateresult==true)
		{
			$msg = "Designation Updated Sucessfully..!!";
			$success = 1;
		}
		else
		{
			$msg = "Something Error..!!";
		}
 	}
 	else
 	{
   		$designationinsertupdate="insert into designation set  designationname='$designame',active='1',userid='1' "; 
		$desiginsertupdateresult=mysql_query($designationinsertupdate);
		if($desiginsertupdateresult==true)
		{
			$msg = "Designation Added Sucessfully..!!";
			$success = 1;
		}
		else
		{
			$msg = "Something Error..!!";
		}
 	}
   
  	
}
?>
<?php include "Header.html"; ?>
<script type="text/javascript" language="javascript">
	function goBack(){
	   	url = "designationlist.php";
		window.location.replace(url);
	}
	function onlyAlphabets(e, t) {
       	try{
            if (window.event) {
            	var charCode = window.event.keyCode;
            }else if (e) {
                var charCode = e.which;
            }else { 
				return true; 
			}
				//alert(charCode)
            if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 223) || (charCode==32) || (charCode==47) || (charCode==45) || (charCode==8)){
               	return true;
			}else{
                return false;
			}
		}
        catch (err) {
            alert(err.Description);
        }
	}
		
	function func_desc(){	
		if(alltrim(document.phuploader.dummyname.value)!=alltrim(document.phuploader.designame.value))
		{	
			var xmlHttp;
			var data;
			if(window.XMLHttpRequest) // For Mozilla, Safari, ...
			{
				xmlHttp = new XMLHttpRequest();
			}
			else if(window.ActiveXObject) // For Internet Explorer
			{ 
				xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			strURL="check_desc.php?desc="+alltrim(document.phuploader.designame.value);
			xmlHttp.open('POST', strURL, true);
			xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xmlHttp.onreadystatechange = function()
			{
				if (xmlHttp.readyState == 4)
				{
					data=xmlHttp.responseText
					if(data=='Y')
					{	
						alert("Designation Name Already Exist");
						document.phuploader.designame.value='';
						return false;
					}
				}
			}
			xmlHttp.send(strURL);
		}
	}
  	$(function(){
   		$.fn.validatedesignation = function(event) { 
			if($("#designame").val()==""){ 
			var a="Please Enter Designation Name";
			$('#val_design').text(a);
			event.preventDefault();
			event.returnValue = false;
			//return false;
		}else{
			var a="";
			$('#val_design').text(a);
			}
		}
		$("#designame").keyup(function(event){
			$(this).validatedesignation(event);
		});
		$("#top").submit(function(event){
			$(this).validatedesignation(event);
		});
   });
		  /*jQuery(document).ready(function($) {

  if (window.history && window.history.pushState) {

    $(window).on('popstate', function() {
      var hashLocation = location.hash;
      var hashSplit = hashLocation.split("#!/");
      var hashName = hashSplit[1];

      if (hashName !== '') {
        var hash = window.location.hash;
        if (hash === '') {
          alert('Back button was pressed.');
		  event.preventDefault();
					event.returnValue = false;
        }
      }
    });

    window.history.pushState('forward', null, './#forward');
  }

});*/
  
   </script>
   	<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
		function submitform()
		{
			var x = confirm('Do you really want to Save Designation.?');
			if(x == false)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
	</SCRIPT>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
       <!-- <form action="<?php //echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader" onSubmit="return confirm('Do you really want to Save Designation.?');">-->
       <!--<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader" onSubmit="submitform();">-->
	    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
               <div class="title">Designation</div>
                <div class="container_12">
                    <div class="grid_12">
           				
		<div align="right"><a href="designationlist.php?edit=new">View&nbsp;&nbsp;</a></div>

                        <blockquote id="bq1" class="bq1">
							
                             <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr>
									<td colspan="3">&nbsp;</td>
								</tr>
								<tr><br/>
								    <td width="19%">&nbsp;</td>
                                    <td class="label">&nbsp;&nbsp;Designation Name<input type="hidden" name="get_desigid" id="get_desigid" value="<?php echo $designationid; ?>" size="1" /><input type="hidden" name="dummyname" id="dummyname" value="<?php echo $designame; ?>" size="1" />
									</td>
									<td>
									<?php 
									if($_GET['designationid']!="")
									{
									?>
									<input type="text" name='designame' id='designame' class="textboxdisplay" onKeyPress="return onlyAlphabets(event,this);" value="<?php echo $designame; ?>" size="60" onBlur="func_desc()">
									<?php
									}
									else
									{
									?>
									<input type="text" name='designame' id='designame' class="textboxdisplay" onKeyPress="return onlyAlphabets(event,this);" size="60" onBlur="func_desc()">

									<?php
									}
									?>
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
										<div class="buttonsection"><input type="submit" name="submit" id="submit" data-type="submit" value="Submit"/></div>
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
