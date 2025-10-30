<?php
require_once 'library/config.php';
require_once 'library/functions.php';
checkUser();
$_SESSION['login_return_url'] = $_SERVER['REQUEST_URI'];
//echo $_SESSION['login_return_url'];
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '-' . $mm . '-' . $yy;
}
$user_design_sql = "select username from users WHERE userid = '$userid' AND active = 1";
$user_design_query = mysql_query($user_design_sql);
$userList = mysql_fetch_object($user_design_query);
$username = $userList->username;
$staff_sql = "select  staff.staffcode, staff.staffname, designation.designationname, staff.email, staff.designationid, staff.mobile, staff.intercom, staff.DOJ, staff.DOB, staff.image from staff 
INNER JOIN designation ON (designation.designationid = staff.designationid) 
WHERE staff.staffid = '$staffid' AND staff.active = 1 AND designation.active = 1";
//echo $staff_sql;
$staff_query = mysql_query($staff_sql);
$staffList = mysql_fetch_object($staff_query);
$staffname = $staffList->staffname;
$icno = $staffList->staffcode;
$email = $staffList->email;
$designationname  = $staffList->designationname;
$designationid = $staffList->designationid;
$mobile = $staffList->mobile;
$intercom = $staffList->intercom;
$DOB = dt_display($staffList->DOB);
$DOJ = dt_display($staffList->DOJ);
$image = $staffList->image;
if($staffid == 0)
{
	$image = "profile_default.png";
}
$directory = "uploads/";
$staffimage = $directory.$image;
//echo $staffimage;
?>
<?php include "Header.html"; ?>
<script type="text/javascript" language="javascript" src="js/prototype/prototype.js"></script>
	<script type="text/javascript" language="javascript" src="js/jPint.js"></script>
	<script type="text/javascript" language="javascript" src="js/scal/scal.js"></script>

	
	<style>
	.profiletextbox
	{
		width:175px;
		border:none;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		/*color:#12719E;*/
		color:#600B5E;
	}
	.profileedirtextbox
	{
		width:175px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		/*color:#12719E;*/
		color:#600B5E;
		border:solid 1px #1E9EAC;
		box-shadow: 0 0 10px #9ecaed;
		height:21px;
	}
	.hide
	{
		display:none;
	}
	.closebuttonstyle
	{
		width:13px;
		height:11px;
		background-color:#189CAB;
		color:#FFFFFF;
		font-weight:bold;
	}
	.welcomeuser
	{
		left: 307px;
		position: absolute;
		width: 400px;
		top: 114px;
	}
	.clocksection
	{
		font-size:16px;
		height:30px;
		line-height:30px;
		color:#0a9cc5;
		font-weight:bold;
		background-color:#F0F0F0;
	}
	</style>
	<script>
	
	function edituserdetails(id)
	{
		var classname = id;
		var idname = id;
		if(idname == "txt_design")
		{
			getDesignation();
			var designvalue = document.getElementById("hid_designationid").value;
			//document.getElementById("cmb_designation").value = designvalue;
			document.getElementById("txt_design").style.display = "none";
			document.getElementById("cmb_designation").className = "";
			document.getElementById("cmb_designation").className = "profileedirtextbox";
			
			
			
		}
		/*else
		{
			document.getElementById("txt_design").style.display = "";
			document.getElementById("cmb_designation").className = "hide";
		}*/
		document.getElementById(idname).className = "profileedirtextbox";
		document.getElementById(idname+"_edit").className = "hide";
		document.getElementById(idname+"_accept").className = "";
		document.getElementById("SaveButton").style.display = "";
		document.getElementById(idname).readOnly = false;
		
	}
	function updateuserdetails(id)
	{
		var classname = id;
		var idname = id;
		if(idname == "txt_design")
		{
			var newidname = document.getElementById("cmb_designation");
			var newdesignationname = newidname.options[newidname.selectedIndex].text;
			var newdesignationid = document.getElementById("cmb_designation").value;
			document.getElementById("txt_design").value = newdesignationname;
			document.getElementById("txt_design").style.display = "";
			document.getElementById("cmb_designation").className = "hide";
		}
		document.getElementById(idname).className = "profiletextbox";
		document.getElementById(idname+"_edit").className = "";
		document.getElementById(idname+"_accept").className = "hide";
		document.getElementById(idname).readOnly = true;
	}
	function getResult()
	{
			var staffname 	= document.getElementById("txt_name").value;
			var designid 	= document.getElementById("hid_designationid").value;
			var designation = document.getElementById("txt_design").value;
			var icno 		= document.getElementById("txt_icno").value;
			var username 	= document.getElementById("txt_username").value;
			var email 		= document.getElementById("txt_email").value;
			var mobileno 	= document.getElementById("txt_mobileno").value;
			var intercomno 	= document.getElementById("txt_intercomno").value;
			var dob 		= document.getElementById("txt_dob").value;
			var doj 		= document.getElementById("txt_doj").value;
			var staffid		= document.getElementById("hid_staffid").value;
			var input_str = staffname+"***"+designid+"***"+icno+"***"+username+"***"+email+"***"+mobileno+"***"+intercomno+"***"+dob+"***"+doj+"***"+staffid;
		 	var xmlHttp;
            var data;
            var i, j;
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "find_user_profile.php?user_details=" + input_str;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText

                    if (data == "S")
                    {
						document.getElementById("txt_name").value 		= staffname;
						document.getElementById("txt_design").value 	= designation;
						document.getElementById("txt_icno").value 		= icno;
						document.getElementById("txt_username").value 	= username;
						document.getElementById("txt_email").value 		= email;
						document.getElementById("txt_mobileno").value 	= mobileno;
						document.getElementById("txt_intercomno").value = intercomno;
						document.getElementById("txt_dob").value 		= dob;
						document.getElementById("txt_doj").value 		= doj;
						document.getElementById("result").className = "";
						document.getElementById("result").style.display = "";
						var close_btn = "<label for ='btn_close' name='btn_close' id='btn_close' class='closebuttonstyle' onclick='closemessage();'>&nbsp;X&nbsp;</label>";
						document.getElementById("result").innerHTML	= "&nbsp;&nbsp;Sucessfully Updated...!!!&nbsp;&nbsp;"+close_btn;
                    }
					else
					{
						document.getElementById("result").className = "";
						document.getElementById("result").style.display = "";
						var close_btn = "<label for ='btn_close' name='btn_close' id='btn_close' class='closebuttonstyle' onclick='closemessage();'>&nbsp;X&nbsp;</label>";
						document.getElementById("result").innerHTML	= "&nbsp;&nbsp;Something Error...!!!&nbsp;&nbsp;"+close_btn;
					}
					
                }
            }
            xmlHttp.send(strURL);
	}
	function closemessage()
	{
		document.getElementById("result").className = "hide";
		
	}
	function getDesignation()
	{
			var xmlHttp;
            var data;
            var i, j;
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "find_designation_list.php?id=1";
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText

                    if (data != "")
                    {
						document.form.cmb_designation.length = 0;
						/*var optn1 = document.createElement("option")
                        optn1.value = "";
                        optn1.text = "--------Select-------";
                        document.form.cmb_designation.options.add(optn1)*/
						
						
						var design = data.split("*");
						for(i=0; i<design.length; i+=2)
						{
							var designationid = design[i];
							var designationname = design[i+1];
							var optn = document.createElement("option")
                            optn.value = designationid;
                            optn.text = designationname;
                            document.form.cmb_designation.options.add(optn)
						}
						var designvalue = document.getElementById("hid_designationid").value;
						document.getElementById("cmb_designation").value = designvalue;
                    }
                }
            }
            xmlHttp.send(strURL);
	}
	function designationChange(obj)
	{
		var designationid = obj.value;
		var cmb_id = document.getElementById("cmb_designation");
		var designationname = cmb_id.options[cmb_id.selectedIndex].text;
		document.getElementById("txt_design").value = designationname;
		document.getElementById("hid_designationid").value = designationid;
		document.getElementById("cmb_designation").value = designationid;
	}
	
	
	</script>
<body class="page1" id="top" onLoad="startTime()">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
            <?php include_once("Menu.php"); ?>
            <!--==============================Content=================================-->
            <div class="content">

                <div class="container_12">
                    <div class="grid_12">


                        <blockquote id="bq1" class="bq1 message" style="border:1px solid #FBEBED;">
							
							<!--<div align="left">  
							<span style="text-align:center">
							Welcome &nbsp;<a style="color:#2178D8"><?php echo $username ; ?></a> to Electronic Billing Measurement System 
                           </span>-->
						   <div style="display:inline-block;" id="showclock" class="clocksection">&nbsp;<br/></div>
						   <div class="welcomeuser" align="center" style="display:inline-block;">
						   <span style="text-align:center; color:#00008b;">
							Welcome &nbsp;
							<a style="color:#c70592">
							<?php 
							if($staffid == 0)
							{
								echo strtoupper($username); 
							}
							else
							{
								echo strtoupper($staffname); 
							}
							?>
							</a><br/> to <br/>Electronic Billing Measurement System
                           </span>
						   </div>
						   <input type="hidden" name="hid_staffid" id="hid_staffid" value="<?php echo $staffid ; ?>" >
						   <div class="jPintPageSet" style="display:inline-block;">

								<div id="mainMenuPage" class="jPintPage IconMenu">
									<ul>
										<li><a href="#calendarPage"><img src="images/calendar-icon-png.png" title="Calendar"></a></li>
										<!--<li><a href="#musicPage"><img src="images/NavButtonMusic.png" title="Music"></a></li>-->
										<!--<li><a href="#notesPage"><img src="images/NavButtonNotes.png" title="Notes"></a></li>-->
										<li><a href="#creditsPage"><img src="images/NavButtonCredits.png" title="Profile"></a></li>
										<li><a href=""><img src="<?php echo $staffimage; ?>" title="" style="width:60px; height:60px;"></a></li>
									</ul>
								</div>
							
								<div id="calendarPage" class="jPintPage Calendar HasTitle">
									<h1>
										<a class="BackButton">Back</a>
									</h1>
									<div id="page1Calendar" class="iPhoneCal">
									</div>
									<div id="showDate" style="display:none">
									</div>
								</div>
							
								<div id="musicPage" class="jPintPage EdgedList HasTitle">
								
									<h1>
										Artists
										<a class="BackButton">Back</a>
									</h1>
							
									<ul>
										<li class="withArrow"><a href="#artistPage">Artist 1</a></li>
										<li class="withArrow"><a href="#artistPage">Artist 2</a></li>
										<li class="withArrow"><a href="#artistPage">Artist 3</a></li>
										<li class="withArrow"><a href="#artistPage">Artist 4</a></li>
										<li class="withArrow"><a href="#artistPage">Artist 5</a></li>
									</ul>
							
								</div>
									
								<div id="artistPage" class="jPintPage EdgedList HasTitle">
								
									<h1>
										Albums
										<a class="BackButton">Back</a>
									</h1>
							
									<ul>
										<li class="withArrow"><a href="#albumPage">Album 1</a></li>
										<li class="withArrow"><a href="#albumPage">Album 2</a></li>
										<li class="withArrow"><a href="#albumPage">Album 3</a></li>
										<li class="withArrow"><a href="#albumPage">Album 4</a></li>
										<li class="withArrow"><a href="#albumPage">Album 5</a></li>
									</ul>
							
								</div>
									
								<div id="albumPage" class="jPintPage EdgedList HasTitle">
								
									<h1>The Album
										<a class="BackButton">Back</a>
									</h1>
									<ul>
										<li>Song 1</li>
										<li>Song 2</li>
										<li>Song 3</li>
										<li>Song 4</li>
										<li>Song 5</li>
									</ul>
							
								</div>
									
								<div id="creditsPage" class="jPintPage RoundedList HasTitle">
								
									<h1>
										
										<a class="BackButton">Back</a>
										<a class="RightButton" id="SaveButton" style="display:none;" onClick="getResult();">Save</a>
										<span id="result" style=" background-color:ghostwhite;border:1px solid #cdcdcd; display:none;"></span>
									</h1>
									<h2>Official Detail</h2>
									<ul>
										<li>
											Name
											<span class="secondary">
											<input type="text" class="profiletextbox" readonly="" name="txt_name" id="txt_name" value="<?php echo $staffname; ?>">
											<img src="images/edit_icon.png" title="" height="14" onClick="edituserdetails('txt_name');" id="txt_name_edit">
											<img src="images/ok.png" title="" height="14" onClick="updateuserdetails('txt_name');" id="txt_name_accept" class="hide">
											</span>
										</li>
										<li>
											IC No.
											<span class="secondary">
											<input type="text" class="profiletextbox" readonly="" name="txt_icno" id="txt_icno" value="<?php echo $icno; ?>">
											<img src="images/disable.png" title="" height="14" onClick="" id="txt_icno_edit">
											<img src="images/ok.png" title="" height="14" onClick="updateuserdetails('txt_icno');" id="txt_icno_accept" class="hide">
											</span>
										</li>
										<li>
											Grade
											<span class="secondary">
											<select name="cmb_designation" id="cmb_designation" class="hide" onChange="designationChange(this);">
											<!--<option value="">--------Select-------</option>-->
											</select>
											<input type="text" class="profiletextbox" readonly="" name="txt_design" id="txt_design" value="<?php echo $designationname; ?>">
											<img src="images/edit_icon.png" title="" height="14" onClick="edituserdetails('txt_design');" id="txt_design_edit">
											<img src="images/ok.png" title="" height="14" onClick="updateuserdetails('txt_design');" id="txt_design_accept" class="hide">
											</span>
											<input type="hidden" name="hid_designationid" id="hid_designationid" value="<?php echo $designationid; ?>" >
										</li>
										
									</ul>
							
									<h2>Personal Details</h2>
									<ul>
										<!--<li class="withArrow">-->
										<li class="">
											User Name
											<span class="secondary">
											<input type="text" class="profiletextbox" readonly="" name="txt_username" id="txt_username" value="<?php echo $username; ?>">
											<img src="images/edit_icon.png" title="" height="14" onClick="edituserdetails('txt_username');" id="txt_username_edit">
											<img src="images/ok.png" title="" height="14" onClick="updateuserdetails('txt_username');" id="txt_username_accept" class="hide">
											<!--<img src="images/edit.png" title="">-->
											</span>
										</li>
										<li class="">
											DOB
											<span class="secondary">
											<input type="text" class="profiletextbox" readonly="" name="txt_dob" id="txt_dob" value="<?php echo $DOB; ?>">
											<img src="images/edit_icon.png" title="" height="14" onClick="edituserdetails('txt_dob');" id="txt_dob_edit">
											<img src="images/ok.png" title="" height="14" onClick="updateuserdetails('txt_dob');" id="txt_dob_accept" class="hide">
											</span>
										</li>
										<li class="">
											DOJ
											<span class="secondary">
											<input type="text" class="profiletextbox" readonly="" name="txt_doj" id="txt_doj" value="<?php echo $DOJ; ?>">
											<img src="images/edit_icon.png" title="" height="14" onClick="edituserdetails('txt_doj');" id="txt_doj_edit">
											<img src="images/ok.png" title="" height="14" onClick="updateuserdetails('txt_doj');" id="txt_doj_accept" class="hide">
											</span>
										</li>
										<li class="">
											Intercom
											<span class="secondary">
											<input type="text" class="profiletextbox" readonly="" name="txt_intercomno" id="txt_intercomno" value="<?php echo $intercom; ?>">
											<img src="images/edit_icon.png" title="" height="14" onClick="edituserdetails('txt_intercomno');" id="txt_intercomno_edit">
											<img src="images/ok.png" title="" height="14" onClick="updateuserdetails('txt_intercomno');" id="txt_intercomno_accept" class="hide">
											</span>
										</li>
										<li class="">
											Mobile.
											<span class="secondary">
											<input type="text" class="profiletextbox" readonly="" name="txt_mobileno" id="txt_mobileno" value="<?php echo $mobile; ?>">
											<img src="images/edit_icon.png" title="" height="14" onClick="edituserdetails('txt_mobileno');" id="txt_mobileno_edit">
											<img src="images/ok.png" title="" height="14" onClick="updateuserdetails('txt_mobileno');" id="txt_mobileno_accept" class="hide">
											</span>
										</li>
										<li class="">
											Email
											<span class="secondary">
											<input type="text" class="profiletextbox" readonly="" name="txt_email" id="txt_email" value="<?php echo $email; ?>">
											<img src="images/edit_icon.png" title="" height="14" onClick="edituserdetails('txt_email');" id="txt_email_edit">
											<img src="images/ok.png" title="" height="14" onClick="updateuserdetails('txt_email');" id="txt_email_accept" class="hide">
											</span>
										</li>
									</ul>
							
								</div>
									
								<!--The WebNotes sample seen here was written by Matt East 29 Jan. 2008 -->
							
								<div id="notesPage" class="jPintPage HasTitle EdgedList EditModeOff Notes">
									<!-- Home Level Begin -->    
									<h1>Notes
										<div class="EditModeInvisible">
											<a class="BackButton">Back</a>
											<a href="#" onClick="jPintEdit.toggleDeleteMode( this, Element.DOMremove ).toggleSortMode( this );return false;" class="RightButton">Edit</a>
										</div>
										<div class="EditModeVisible">
											<a class="LeftButton SymbolButton" href="#enter">+</a>
											<a href="#" onClick="jPintEdit.toggleDeleteMode( this, Element.DOMremove ).toggleSortMode( this );return false;" class="RightButton ActiveButton">Done</a>
										</div>
										</h1>
										<ul class="DeletableItems SortableItems">
											<li class="withArrow"><a href="#ToDolist">ToDo list</a></li>
											<li class="withArrow"><a href="#Gamestobuy">Games to buy</a></li>
											<li class="withArrow"><a href="#Grocerylist">Grocery list</a></li>
											<li class="withArrow"><a href="#Stockstowatch">Stocks to watch</a></li>
											<li class="withArrow"><a href="#Giftstobuy">Gifts to buy</a></li>
											<li class="withArrow"><a href="#Moviestorent">Movies to rent</a></li>
											<li class="withArrow"><a href="#Moviestobuy">Movies to buy</a></li>
											<li class="withArrow"><a href="#Moviestosee">Movies to see</a></li>
											<li class="withArrow"><a href="#Gamestorent">Games to rent</a></li>
											<li class="withArrow"><a href="#Prosandcons">Pros and cons</a></li>
										</ul>
									<!-- Home Level End --> 
								</div>
							
								<div id="enter" class="jPintPage HasTitle EdgedList Notes">
									<!-- Enter WebNotes Page Begin -->
										<h1>Enter
										<a class="BackButton">Cancel</a>
										<a class="RightButton ">Save</a>
										</h1>
										<ul>
											<textarea name="wnBody" cols="45" rows="30"></textarea>
										</ul>
									<!-- Enter WebNotes Page End -->
								</div>
							
								<div id="ToDolist" class="jPintPage EdgedList HasTitle Notes">
									<!-- Edit Page Begin -->
										<ul>
											<h1>Edit
											<a class="BackButton">Cancel</a>
											<a class="RightButton">Save</a>
											</h1>
											<textarea name="wnBodyEdit" cols="45" rows="30">ToDo list
												
							1. Prototype look and feel (done)
							2. Create DB (done)
							3. Work on getting python to talk to MySQL DB (done)
							4. Prototype accessing data from DB with python (done)
							5. Prototype inserting data into DB with python (working)
							6. Modify mockup code to allow display of data from DB (not there yet)
							7. Modify mockup code to allow input of data to DB from browser (not there yet)
							8. Clean up any lose ends (not there yet)
							9. Pat my self on the back and keep working
							10. See plans for webnotes2
							
							See about starting to build other webapps I have plans for as they are very similar to this one.
												</textarea><br>
										</ul>
									<!-- Edit Page End -->	
								</div>
							
								<div id="Gamestobuy" class="jPintPage EdgedList HasTitle Notes">
									<!-- Edit Page Begin -->
										<ul>
											<h1>Edit
											<a class="BackButton">Cancel</a>
											<a class="RightButton">Save</a>
											</h1>
											<textarea name="wnBodyEdit" cols="45" rows="30">Games to buy
												
							Matt
							Jam Sessions (DS)
							Blazing Angles (Wii)
							Endless Ocean (Wii)
							Super Smash Bros Melee (Wii)
							Daffy Duck: Duck Amuck (DS)
							
							Caleb
							Spiderman3 DS (29.92)
							
							
							Nathan for his SP
							Sonic the hedgehog (19.82)
							Over the hedge hammy goes nuts (19.82)
							Mario kart super circut (19.82)
							Spiderman3 (29.92)
							
							Kayleigh
							Spyro shadow legacy DS (29.94)
							
												</textarea><br>
										</ul>
									<!-- Edit Page End -->	
								</div>
								
									<div id="Grocerylist" class="jPintPage EdgedList HasTitle Notes">
									<!-- Edit Page Begin -->
										<ul>
											<h1>Edit
											<a class="BackButton">Cancel</a>
											<a class="RightButton">Save</a>
											</h1>
											<textarea name="wnBodyEdit" cols="45" rows="30">Grocery list
												
							Milk
							Wheat bread
							Microwave
							Light weiners
							Execedrine
							Chips
												</textarea><br>
										</ul>
									<!-- Edit Page End -->	
								</div>
								
								<div id="Stockstowatch" class="jPintPage EdgedList HasTitle Notes">
									<!-- Edit Page Begin -->
										<ul>
											<h1>Edit
											<a class="BackButton">Cancel</a>
											<a class="RightButton">Save</a>
											</h1>
											<textarea name="wnBodyEdit" cols="45" rows="30">Stocks to watch
												
							Apple
							Google
							Yahoo
							AT&T
												</textarea><br>
										</ul>
									<!-- Edit Page End -->	
								</div>
							
								<div id="Giftstobuy" class="jPintPage EdgedList HasTitle Notes">
									<!-- Edit Page Begin -->
										<ul>
											<h1>Edit
											<a class="BackButton">Cancel</a>
											<a class="RightButton">Save</a>
											</h1>
											<textarea name="wnBodyEdit" cols="45" rows="30">Gifts to buy
												
							80 Gig iPod for Michelle
							17' MacBook Pro ME 
							Nintendo Wii for Mom and Dad
							Blank DVD's for Bob
							DVDR for Mike
							
												</textarea><br>
										</ul>
									<!-- Edit Page End -->	
								</div>
							
								<div id="Moviestorent" class="jPintPage EdgedList HasTitle Notes">
									<!-- Edit Page Begin -->
										<ul>
											<h1>Edit
											<a class="BackButton">Cancel</a>
											<a class="RightButton">Save</a>
											</h1>
											<textarea name="wnBodyEdit" cols="45" rows="30">Movies to rent
												
							Mystery Men
							Monty Python 
							Godzilla Vs. Gigan
							Godzilla Vs. King Kong
							
							
												</textarea><br>
										</ul>
									<!-- Edit Page End -->	
								</div>
								
									<div id="Moviestobuy" class="jPintPage EdgedList HasTitle Notes">
									<!-- Edit Page Begin -->
										<ul>
											<h1>Edit
											<a class="BackButton">Cancel</a>
											<a class="RightButton">Save</a>
											</h1>
											<textarea name="wnBodyEdit" cols="45" rows="30">Movies to buy
												
							Herbie Goes Bannans
							Monty Python Holy Grail
							Godzilla Vs. King Gihdorah
							Godzilla Vs. Hedora
							
							
												</textarea><br>
										</ul>
									<!-- Edit Page End -->	
								</div>
								
										<div id="Moviestosee" class="jPintPage EdgedList HasTitle Notes">
									<!-- Edit Page Begin -->
										<ul>
											<h1>Edit
											<a class="BackButton">Cancel</a>
											<a class="RightButton">Save</a>
											</h1>
											<textarea name="wnBodyEdit" cols="45" rows="30">Movies to see
												
							Cloverfield
							3:10 to Yuma
							Any Gamera Movies I can find
							Rush Hour 3
												</textarea><br>
										</ul>
									<!-- Edit Page End -->	
								</div>
							
							<div id="Gamestorent" class="jPintPage EdgedList HasTitle Notes">
									<!-- Edit Page Begin -->
										<ul>
											<h1>Edit
											<a class="BackButton">Cancel</a>
											<a class="RightButton">Save</a>
											</h1>
											<textarea name="wnBodyEdit" cols="45" rows="30">Games to rent
												
							Blazing Angles
							Endless Oceans
							
												</textarea><br>
										</ul>
									<!-- Edit Page End -->	
								</div>
								
								<div id="Prosandcons" class="jPintPage EdgedList HasTitle Notes">
									<!-- Edit Page Begin -->
										<ul>
											<h1>Edit
											<a class="BackButton">Cancel</a>
											<a class="RightButton">Save</a>
											</h1>
											<textarea name="wnBodyEdit" cols="45" rows="30">Pros and cons
												
							Pros:
							Portable
							OSX
							Built in cam
							Python builtin
							Garage Band and other iLife software included
							
							
							Cons:
							Cost
							
												</textarea><br>
										</ul>
									<!-- Edit Page End -->	
								</div>
							
							</div>
						   <!-- </div>-->
                           
                           <br><br>

                        </blockquote>
                    </div>

                </div>
            </div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
        </form>
    </body>
	
</html>
