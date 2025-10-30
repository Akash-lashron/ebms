<?php
 @ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once("ajax_table.class.php");
$obj = new ajax_table();
$ob1 = new ajax_table();
$temp = "role";
$temp1 = "users";
$records = $obj->getRecords($temp,0);
$userrecords = $ob1->getRecords($temp1,0);
$msg = '';
?>
<script type="text/javascript" language="javascript">
    var columns = new Array("rolename");
	 var placeholder = new Array(" Enter Role Name");
	 var inputType = new Array("text");
	 //var table = "tableDemo";
	 var table = new Array("tableDemo");
	 var temp = "rights";
	 
	 // Set button class names 
	 var savebutton = "ajaxSave";
	 var deletebutton = "ajaxDelete";
	 var editbutton = "ajaxEdit";
	 var updatebutton = "ajaxUpdate";
	 var cancelbutton = "cancel";
	 
	 var saveImage = "images/save.png"
	 var editImage = "images/edit.png"
	 var deleteImage = "images/remove.png"
	 var cancelImage = "images/back.png"
	 var updateImage = "images/save.png"

	 // Set highlight animation delay (higher the value longer will be the animation)
	 var saveAnimationDelay = 3000; 
	 var deleteAnimationDelay = 1000;
	  
	 // 2 effects available available 1) slide 2) flash
	 var effect = "flash"; 
</script>
<?php include "Header.html"; ?>
<script src="js/createmenuscript.js"></script>	
<link rel="stylesheet" href="css/createmenustyle.css">
<script>
   	function goBack()
	{
	   	url = "designationlist.php";
		window.location.replace(url);
	}

</script>
<style>
.menutypesection
{
	border:0px solid #20b2aa;
	border-top:none;
}
.dynamicrowcell
{
	width:200px;
	height:30px;
	text-align:center;
}
.dynamictextbox
{
	/*width:190px;*/
	width:320px;
	height:25px;
	border:1px solid #EFEFEF;
}
.dynamictextbox:hover, .dynamictextbox:focus
{
	outline: none;
    border-color: #9ecaed;
    box-shadow: 0 0 5px #9ecaed;
}
.hide
{
	display:none;
}
.TitleDiv
{
	color:#C70592;
	font-weight:bold;
	font-size:14px;
	height:25px;
	border-bottom:0px solid #20b2aa;
	/*background-color:#EAFFEB;*/
	background-color:#f3ecf4;
	line-height:25px;
}
.TitileDiv1
{
    background: rgba(0, 0, 0, 0) -moz-linear-gradient(center top , darkgray, #ffffff 1px, #ededed 25px) repeat scroll 0 0;
    border-color: #ffffff #ffffff #ffffff #ededed;
    border-style: solid;
    border-width: 1px;
    color: #e20404;
    font-size: 13px;
    font-weight: bold;
    padding: 1px 4px;
    text-decoration: none;
}
</style>
<!--<body class="page1" id="top" oncontextmenu="return false">-->
<body class="page1" id="top">
<!--==============================header=================================-->
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
<?php include "Menu.php"; ?>
<!--==============================Content=================================-->
  <div class="content">
     <div class="container_12">
        <div class="grid_12">
			<div align="right"><a href="">View&nbsp;&nbsp;</a></div>
            <blockquote class="bq1">
               	<div class="title">User Rights Creation</div>
				<!-----------------MAIN MENU SECTION STARTS HERE---------------->
				<div align="center" class="menutypesection" id="mainmenusection">
					<!--<div class="TitleDiv TitileDiv1">USER ROLE CREATION </div>-->
						<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class="label">Select User Role</td>
								<td>
									<select name="cmb_menu_type" id="cmb_menu_type" class="textboxdisplay" onChange="DispalyMenuTypeSection(this)" style="width:287px;">
										<option value="">---------------Select User Role----------------</option>
							<?php 
									if(count($records))
									{
										foreach($records as $key=>$eachRecord)
										{
											echo "<option value=".$eachRecord['roleid'].">".$eachRecord['roledescription']."</option>";
										}
									}
							 ?>
									</select>
								</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class="label">Select User Name</td>
								<td>
									<select name="cmb_user" id="cmb_user" class="textboxdisplay" onChange="DispalyMenuTypeSection(this)" style="width:287px;">
										<option value="">--------------Select User Name---------------</option>
							<?php 
									if(count($userrecords))
									{
										foreach($userrecords as $key=>$eachUser)
										{
											echo "<option value=".$eachUser['userid'].">".$eachUser['username']."</option>";
										}
									}
							 ?>
									</select>
								</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
						</table>
						<!--<table border="0" class="tableDemo bordered">
							<tr class="ajaxTitle">
								<th width="50px" align="left">S.No.</th>
								<th width="170px" align="left">User Role Name</th>
								
								
								<th width="100px">Action</th>
							</tr>
							<?
							/*if(count($records)){
							 $i = 1;	
							 foreach($records as $key=>$eachRecord){*/
							?>
							<tr id="<?=$eachRecord['roleid'];?>">
								<td><?=$i++;?></td>
								<td class="rolename"><?=$eachRecord['roledescription'];?></td>
								
								
								<td align="center">
									<input type="button" id="<?=$eachRecord['roleid'];?>" value=" EDIT " class="editbtnstyle ajaxEdit">
									<input type="button" id="<?=$eachRecord['roleid'];?>" value=" DEL " class="delbtnstyle ajaxDelete">
								</td>
							</tr>
							<? /*}
							}*/
							?>
						</table> -->
				</div>	
				<div class="hide" id="save_btn_section" align="center">
					<br/>
					<!--<input type="submit" name="save" id="save" value=" Save " >-->
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
var titletext = "Hi ";
document.querySelector('#top').onload = function(){
	if(msg != "")
	{
		swal({
			title: titletext,
			text: msg,
			timer: 4000,
			showConfirmButton: true
		});
	}
};
</script>
</form>
</body>
</html>
