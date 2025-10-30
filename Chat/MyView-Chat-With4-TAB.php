<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
include "common.php";
checkUser();
$report=0;
$msg = "";
$RowCount =0;
$staffid = $_SESSION['sid'];
$SheetCount = 0;
$total_work = 0;
/*$select_sheet_query = "select * from sheet";
$select_sheet_sql 	= mysql_query($select_sheet_query);
if($select_sheet_sql == true)
{
	if(mysql_num_rows($select_sheet_sql) > 0)
	{
		while($SheetList = mysql_fetch_object($select_sheet_sql))
		{
			$assigned_staff = $SheetList->assigned_staff;
			$AssignStaff = explode(",",$assigned_staff);
			if((in_array($staffid,$AssignStaff)) || ($_SESSION['isadmin'] == 1))
			{
				$total_work++;
			}
		}
	}
}*/
$LevelArr = array();
$SelectLevelQuery = "select levelid, role_name from staffrole where active = 1 and sectionid = 1 and levelid != 5";
$SelectLevelSql = mysql_query($SelectLevelQuery);
if($SelectLevelSql == true){
	if(mysql_num_rows($SelectLevelSql)>0){
		while($LevelList = mysql_fetch_object($SelectLevelSql)){
			$LevelArr[$LevelList->levelid] = $LevelList->role_name;
		}
	}
}

$SheetAList = array(); $RbnAList = array();
$select_distinct_query = "select distinct sheetid, rbn from measurementbook_temp";// where forward_to = ".$_SESSION['levelid']."";
$select_distinct_sql = mysql_query($select_distinct_query);
if($select_distinct_sql == true){
	while($ListA = mysql_fetch_object($select_distinct_sql)){
		array_push($SheetAList,$ListA->sheetid);
		array_push($RbnAList,$ListA->rbn);
	}
}
$SheetACount = count($SheetAList);
/*
exec("wmic /node:$_SERVER[REMOTE_ADDR] COMPUTERSYSTEM Get UserName", $user);
echo($user[1])."<br/>";
$LtcLocIntpro = getHostByName(php_uname('n'));
echo $LtcLocIntpro;exit;*/
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
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
<script src="dashboard/MyView/bootstrap.min.js"></script>
<style>
	table{
		margin-top:15px;
		color:#0053A6;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		font-size:12px;
	}
	.note{
		text-decoration: none;
		padding: 2px 14px;
		color: #fff;
		border: none;
		background-color: transparent;
		font-size: 13px;
		outline:none;
	}
	.col-status{
		float: left;
		position: relative;
		min-height: 1px;
		padding-right: 2px;
		padding-left: 2px;
		width:16%;
	}
	.well-A{
		background-color:#fff;/*#038BCF*/
		border: 2px solid #02B9E2;/*038BCF*/
		color:#032FAD;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		cursor:pointer;
		border-radius:20px;
		padding:5px;
		font-size:12px;
	}
	.well-A:hover{
		background-color:#02B9E2;
		border: 2px solid #02B9E2;
		color:#fff;
	}
	.well.active{
		background-color:#02B9E2;
		border: 2px solid #02B9E2;/*#055DAB;*/
		color:#fff;
		pointer-events:none;
	}
</style>
    </head>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>	
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<div class="title">My View</div>
				<div class="container_12">
					<div class="grid_12" align="center">
						<blockquote class="bq1" style="overflow:auto; padding-left:10px; padding-right:10px">
							<div align="center">
								<div class="col-md-12 no-padding-lr" align="center">&nbsp;</div>
								<!--<div class="col-md-2 no-padding-lr" align="center">&nbsp;</div>-->
								<div class="col-md-12 no-padding-lr" align="center">
									<!--<div class="col-status" data-url=''><div class="well well-sm well-A active"><span class="rlable-pink">Check Measurements List</span></div></div>-->
									<div class="col-status" data-url='CurrentRabStatusList'><div class="well well-sm well-A"><span class="rlable-pink">My Works</span></div></div>
									<div class="col-status" data-url='MyViewWorks'><div class="well well-sm well-A"><span class="rlable-pink">Total Work List</span></div></div>
									<div class="col-status" data-url='CommanWorkDetails'><div class="well well-sm well-A"><span class="rlable-pink">Work Report</span></div></div>
									<div class="col-status" data-url='RABStatusTableCivil'><div class="well well-sm well-A"><span class="rlable-pink">Accounts All Bill Status</span></div></div>
									<div class="col-status" data-url='RABStatusCivil'><div class="well well-sm well-A"><span class="rlable-pink">Bill / MBook Accounts Status</span></div></div>
									<div class="col-status" data-url='PassOrderStatusCivil'><div class="well well-sm well-A"><span class="rlable-pink">Pass Order Notification List</span></div></div>
									<!--<div class="col-status" data-url='PassOrderStatusCivil'><div class="well well-sm well-A"><span class="rlable-pink">Graphical Representation</span></div></div>-->
								</div>
								<!--<div class="col-md-2 no-padding-lr" align="center">&nbsp;</div>-->
								
							<?php if($SheetACount > 0){ ?>
								<div></div>
								<table border="0" align="center" class="table table-bordered">
									<tr class="note" style="background-color:#0270BD;"><!--035a85-->
										<th class="" colspan="3">Check Measurement Notification &nbsp; <!--<img src="images/new1.gif" width="50" height="50">--></th>
									</tr>
							<?php
							$slno = 1; $CHMCnt = 0;
							for($x1=0; $x1<$SheetACount; $x1++){
								$sheetidA 	=  $SheetAList[$x1];
								$rbnA 		=  $RbnAList[$x1];
								$select_check_meas_query = "select a.staffid, b.short_name, b.assigned_staff, b.work_order_no, c.staffname, c.staffcode 
															from check_measurement_master a 
															inner join sheet b on (a.sheetid = b.sheet_id)
															inner join staff c on (a.staffid = c.staffid)
															where a.sheetid = '$sheetidA' and a.rbn = '$rbnA' and a.forward_to ='".$_SESSION['levelid']."' 
															and a.cmid = (select max(d.cmid) from check_measurement_master d where d.sheetid = '$sheetidA')";
								$select_check_meas_sql = mysql_query($select_check_meas_query);
								if($select_check_meas_sql == true){
									while($ListB = mysql_fetch_object($select_check_meas_sql)){
										$assigned_staff = $ListB->assigned_staff;
										$AssignStaff = explode(",",$assigned_staff);
										if((in_array($_SESSION['sid'],$AssignStaff)) || ($_SESSION['isadmin'] == 1)){ //echo $sheetidA;//exit;
										//echo $select_check_meas_query;
										$CHMCnt++;
							?>
									<tr>
										<td><?php echo $slno; ?></td>
										<td><?php echo $ListB->work_order_no; ?> - <a href="CheckMeasurementTransaction.php?sheetid=<?php echo $sheetidA; ?>&rbn=<?php echo $rbnA; ?>&Page=MyView" style="text-decoration:none; color:#DE013E"><?php echo $ListB->short_name; ?></a></td>
										<td><?php echo "Sent by  -  ".$ListB->staffname; ?></td>
									</tr>
							<?php $slno++; } } } } if($CHMCnt == 0){?>
									<tr><td align="center">No Records Found</td></tr>
							<?php } ?>
								</table>
							<?php } ?>
								
							<?php if($SheetACount > 0){ ?>
								<div></div>
								<table border="0" align="center" class="table table-bordered">
									<tr class="note" style="background-color:#0270BD;"><!--035a85-->
										<th class="" colspan="6">Accounts Returned Mbook - Notification &nbsp; <!--<img src="images/new1.gif" width="50" height="50">--></th>
									</tr>
							<?php
							
							$slno = 1; $RetMBCnt = 0;
							for($x1=0; $x1<$SheetACount; $x1++){
								$sheetidA 	=  $SheetAList[$x1];
								$rbnA 		=  $RbnAList[$x1];
								$CheckMesLevel = array();
								$SelectAccRetQuery = "select b.short_name, b.assigned_staff, b.work_order_no, c.check_meas_level, e.civil_view_level,  
													 GROUP_CONCAT(CAST(a.mbookno AS CHAR(15000)) SEPARATOR ',') as mbnolist, 
													 GROUP_CONCAT(CAST(a.mtype AS CHAR(15000)) SEPARATOR ',') as mtypelist, 
													 GROUP_CONCAT(CAST(a.genlevel AS CHAR(15000)) SEPARATOR ',') as genlevellist,
													 a.civil_staffid   
													 from send_accounts_and_civil a 
													 inner join sheet b on (a.sheetid = b.sheet_id)
													 inner join check_measure_level_assign c on (a.sheetid = c.sheetid)
													 inner join al_as e on (a.sheetid = e.sheetid)
													 where a.sheetid = '$sheetidA' and a.rbn = '$rbnA' and e.sheetid = '$sheetidA' and e.rbn = '$rbnA' and c.active = 1 and 
													 (a.mb_ac = 'SC' OR a.sa_ac = 'SC' OR a.ab_ac = 'SC') and 
													 c.laid = (select max(d.laid) from check_measure_level_assign d where d.sheetid = '$sheetidA' and d.active = 1) GROUP BY a.sheetid";
								//echo $SelectAccRetQuery;//exit;	
								$SelectAccRetSql = mysql_query($SelectAccRetQuery);
								if($SelectAccRetSql == true){
									while($ListC = mysql_fetch_object($SelectAccRetSql)){
										$assigned_staff = $ListC->assigned_staff;
										$civil_staffid = $ListC->civil_staffid;
										
										$SelectSidQuery = "select levelid from staff where staffid = '$civil_staffid'";
										$SelectSidSql = mysql_query($SelectSidQuery);
										$StList = mysql_fetch_object($SelectSidSql);
										$GenLevel = $StList->levelid;
										
										$AssignStaff = explode(",",$assigned_staff);
										if((in_array($_SESSION['sid'],$AssignStaff)) || ($_SESSION['isadmin'] == 1)){ 
										
											$check_meas_level = $ListC->check_meas_level;
											$ExpCheckMesLevel = explode(",",$check_meas_level);
											foreach($ExpCheckMesLevel as $ChKey => $Chval){
												if($Chval < $_SESSION['levelid']){
													array_push($CheckMesLevel,$Chval);
												}
											}
											if($GenLevel < $_SESSION['levelid']){
												array_push($CheckMesLevel,$GenLevel);
											}
											//array_push($CheckMesLevel,$GenLevel);
											$CheckMesLevel 	= array_unique($CheckMesLevel);
											
											$civil_view_level = $ListC->civil_view_level;
											$CivilViewLevel = explode(",",$civil_view_level);
											
											if(in_array($_SESSION['levelid'],$CivilViewLevel)){
												rsort($CheckMesLevel);
												$RetMBCnt++; $MBText = ""; $MbTextArr = array();
												$ExpMbNoList 		= explode(",",$ListC->mbnolist);
												$ExpMBTypeList 		= explode(",",$ListC->mtypelist);
												$ExpGenLevelList 	= explode(",",$ListC->genlevellist);
												foreach($ExpMbNoList as $MbNKey => $MbNoVal){
													$RetMBNo = $MbNoVal; $MBText = "";
													$RetMBType = $ExpMBTypeList[$MbNKey];
													$RetMBLevel = $ExpGenLevelList[$MbNKey];
													if($RetMBType == 'G'){
														if($RetMBLevel == 'staff'){
															$MBText = "General MB : ".$RetMBNo;
														}else{
															$MBText = "Sub - Abstract MB : ".$RetMBNo;
														}
													}else if($RetMBType == 'S'){
														if($RetMBLevel == 'staff'){
															$MBText = "Steel MB : ".$RetMBNo;
														}else{
															$MBText = "Sub - Abstract MB : ".$RetMBNo;
														}
													}else if($RetMBType == 'A'){
														if($RetMBLevel == 'abstract'){
															$MBText = "Abstract MB : ".$RetMBNo;
														}
													}
													array_push($MbTextArr,$MBText);
												}
							?>
									<tr>
										<td style="vertical-align:middle"><?php echo $slno; ?></td>
										<td style="vertical-align:middle"><?php echo $ListC->work_order_no; ?> : <?php echo $ListC->short_name; ?></td>
										<td style="vertical-align:middle" nowrap="nowrap"><?php echo implode(", ",$MbTextArr);//$MBText; ?> <?php //echo $ListC->mbookno; ?></td>
										<td style="vertical-align:middle" nowrap="nowrap">
											<select name="cmb_forward_staff" id="cmb_forward_staff<?php echo $sheetidA; ?>" class="textboxdisplay FWStaff" style="width:200px" <?php if(count($CheckMesLevel) == 0){ ?> disabled="disabled" <?php } ?>>
												<option value=""> ---Forward To--- </option>
												<?php foreach($CheckMesLevel as $LevKey => $LevId){ if($LevId < $_SESSION['levelid']){ ?>
												<option value="<?php echo $LevId; ?>"> <?php echo $LevelArr[$LevId]; ?> </option>
												<?php } } ?>
											</select>
										</td>
										<td style="vertical-align:middle">
											<a class="<?php if(count($CheckMesLevel) == 0){ ?>btn4 btn4-default btn4-sm<?php }else{ ?>btn3 btn3-default btn3-sm forward<?php } ?>" data-sheetid="<?php echo $sheetidA; ?>" data-rbn="<?php echo $rbnA; ?>" >
												<i class="fa fa-check-circle" style="font-size:15px;"></i>
												FORWARD
											</a>
										</td>
									</tr>
							<?php $slno++; } } } } } if($slno == 1){?>
									<tr><td align="center" colspan="5">No Records Found</td></tr>
							<?php } ?>
								</table>
							<?php } ?>
								<br/>
								
							</div>
							
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
<style>
.alert-minimalist {
	background-color: rgb(241, 242, 240);
	border-color: rgba(149, 149, 149, 0.3);
	border-radius: 3px;
	color: rgb(149, 149, 149);
	padding: 10px;
}
.alert-minimalist > [data-notify="icon"] {
	height: 50px;
	margin-right: 12px;
}
.alert-minimalist > [data-notify="title"] {
	color: rgb(51, 51, 51);
	display: block;
	font-weight: bold;
	margin-bottom: 5px;
}
.alert-minimalist > [data-notify="message"] {
	font-size: 80%;
}
.no-footer{
	padding-left:120px;
	padding-right:120px;
}
</style>
<script>
	$(".FWStaff").chosen();
</script>
<script src="notificationAlert/jquery.min-3.2.1.js"></script>
<script src="notificationAlert/bootstrap-notify.js"></script>
<script src="notificationAlert/bootstrap-notify.min.js"></script>

<script>
	var old_count = 0; 
	/*setInterval(function(){    
    $.ajax({
        type : "POST",
        url : "notificationAlert/GetAllUpdateForNotifyMesaage.php",
        success : function(data){ //alert(data);
			var splitData = data.split("@*@");
			var count 		= splitData[0];
			var staffid 	= splitData[1];
			var Staffname 	= splitData[2];
			var designation = splitData[3];
			var Froms 		= splitData[4];
			var image 		= splitData[5];
			var Tos 		= splitData[6];
            if (Number(count) > old_count) { 
				//$.notify("Number of records in your table is : "+data);
				$.notify({
					icon: 'uploads/'+image,//https://randomuser.me/api/portraits/med/men/77.jpg',
					title: 'Shri.'+Staffname+' '+designation+' - '+Froms,
					message: 'Check Measurement Done by '+Froms+' and waiting for the approval of '+Tos
				},{
					type: 'minimalist',
					//animate: {
						//enter: 'animated fadeInRight',
						//exit: 'animated fadeOutRight'
					//},
					offset: {
						x: 30,
						y: 10
					},
					//delay: 5000,
					icon_type: 'image',
					template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
						'<img data-notify="icon" class="img-circle pull-left">' +
						'<span data-notify="title">{1}</span>' +
						'<span data-notify="message">{2}</span>' +
					'</div>'
				});
                old_count = count;
            }
        }
    });
},1000);*/
</script>
<link rel="stylesheet" type="text/css" media="screen" href="dataTable/jquery.dataTables.min.css" />
<script type="text/javascript" src="dataTable/jquery.dataTables.min.js"></script>
<script>
	$(document).ready(function() {
		$('.col-status').click(function(event){
			var url = $(this).attr('data-url');
			if((url != undefined)&&(url != '')){
				$(location).attr('href', url+'.php');
			}
		});
		$(".forward").click(function(event){
			var sheetid = $(this).attr("data-sheetid");
			var rbn = $(this).attr("data-rbn");
			var FwStaff = $('#cmb_forward_staff'+sheetid).val();
			if(FwStaff != ""){
				$.ajax({ 
					type: 'POST', 
					url: 'FindFwdRejectedMB.php', 
					data: { sheetid: sheetid, rbn: rbn, FwStaff: FwStaff }, 
					success: function (data) {  
						if(data != ""){
							if(data == 1){
								swal({
								  title: "",
								  text: "Work Forwarded Sucessfully",
								  type: "success",
								  confirmButtonText: " OK ",
								},
								function(isConfirm){
									window.location.replace("MyView.php");
								});
							}else{
								swal("Sorry unable to Forward. Please try again.", "", "");
							}
						}
					}
				});
			}else{
				swal("Please select Forward Level");
			}
		});
		//$('#example').DataTable();
	});
</script>



<link rel="stylesheet" href="Chat/style.css">
<div class="fabs">
  <div class="chat">
    <div class="chat_header">
      <div class="chat_option">
      <div class="header_img">
        <img src="Chat/avatar.png"/>
        </div>
        <span id="chat_head">Jane Doe</span> <br> <span class="agent">Agent</span> <span class="online">(Online)</span>
       <span id="chat_fullscreen_loader" class="chat_fullscreen_loader"><i class="fullscreen zmdi zmdi-window-maximize"></i></span>

      </div>

    </div>
    <div class="chat_body chat_login">
        <a id="chat_first_screen" class="fab"><i class="zmdi zmdi-arrow-right"></i></a>
        <p>We make it simple and seamless for businesses and people to talk to each other. Ask us anything</p>
    </div>
    <div id="chat_converse" class="chat_conversion chat_converse">
            <a id="chat_second_screen" class="fab"><i class="zmdi zmdi-arrow-right"></i></a>
      <span class="chat_msg_item chat_msg_item_admin">
            <div class="chat_avatar">
               <img src="Chat/avatar.png"/>
            </div>Hey there! Any question?</span>
      <span class="chat_msg_item chat_msg_item_user">
            Hello!</span>
            <span class="status">20m ago</span>
      <span class="chat_msg_item chat_msg_item_admin">
            <div class="chat_avatar">
               <img src="Chat/avatar.png"/>
            </div>Hey! Would you like to talk sales, support, or anyone?</span>
      <span class="chat_msg_item chat_msg_item_user">
            Lorem Ipsum is simply dummy text of the printing and typesetting industry.</span>
             <span class="status2">Just now. Not seen yet</span>
    </div>
	
	
    <div id="chat_body" class="chat_body">
		<div class="chat_category">
          	<a id="chat_third_screen" class="fab"><i class="zmdi zmdi-arrow-right"></i></a>
        	<p>What would you like to talk about?</p>
        	<ul>
				<li>Tech</li>
				<li class="active">Sales</li>
				<li >Pricing</li>
				<li>other</li>
        	</ul>
        </div>
    </div>
	
	
	
	
    <div id="chat_form" class="chat_converse chat_form">
    <a id="chat_fourth_screen" class="fab"><i class="zmdi zmdi-arrow-right"></i></a>
      <span class="chat_msg_item chat_msg_item_admin">
            <div class="chat_avatar">
               <img src="Chat/avatar.png"/>
            </div>Hey there! Any question?</span>
      <span class="chat_msg_item chat_msg_item_user">
            Hello!</span>
            <span class="status">20m ago</span>
      <span class="chat_msg_item chat_msg_item_admin">
            <div class="chat_avatar">
               <img src="Chat/avatar.png"/>
            </div>Agent typically replies in a few hours. Don't miss their reply.
            <div>
              <br>
              <form class="get-notified">
                  <label for="chat_log_email">Get notified by email</label>
                  <input id="chat_log_email" placeholder="Enter your email"/>
                  <i class="zmdi zmdi-chevron-right"></i>
              </form>
            </div></span>

        <span class="chat_msg_item chat_msg_item_admin">
            <div class="chat_avatar">
               <img src="Chat/avatar.png"/>
            </div>Send message to agent.
            <div>
              <form class="message_form">
                  <input placeholder="Your email"/>
                  <input placeholder="Technical issue"/>
                  <textarea rows="4" placeholder="Your message"></textarea>
                  <button>Send</button> 
              </form>

        </div></span>   
    </div>
	
	
	
	
	
      <div id="chat_fullscreen" class="chat_conversion chat_converse">
      <span class="chat_msg_item chat_msg_item_admin">
            <div class="chat_avatar">
               <img src="Chat/avatar.png"/>
            </div>Hey there! Any question?</span>
      <span class="chat_msg_item chat_msg_item_user">
            Hello!</span>
            <div class="status">20m ago</div>
      <span class="chat_msg_item chat_msg_item_admin">
            <div class="chat_avatar">
               <img src="Chat/avatar.png"/>
            </div>Hey! Would you like to talk sales, support, or anyone?</span>
      <span class="chat_msg_item chat_msg_item_user">
            Lorem Ipsum is simply dummy text of the printing and typesetting industry.</span>
      <span class="chat_msg_item chat_msg_item_admin">
            <div class="chat_avatar">
               <img src="Chat/avatar.png"/>
             </div>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</span>
      <span class="chat_msg_item chat_msg_item_user">
            Where can I get some?</span>
      <span class="chat_msg_item chat_msg_item_admin">
            <div class="chat_avatar">
               <img src="Chat/avatar.png"/>
             </div>The standard chuck...</span>
      <span class="chat_msg_item chat_msg_item_user">
            There are many variations of passages of Lorem Ipsum available</span>
            <div class="status2">Just now, Not seen yet</div>
      <span class="chat_msg_item ">
          <ul class="tags">
            <li>Hats</li>
            <li>T-Shirts</li>
            <li>Pants</li>
          </ul>
      </span>
    </div>
    <div class="fab_field">
      <a id="fab_camera" class="fab"><i class="zmdi zmdi-camera"></i></a>
      <a id="fab_send" class="fab"><i class="zmdi zmdi-mail-send"></i></a>
      <textarea id="chatSend" name="chat_message" placeholder="Send a message" class="chat_field chat_message"></textarea>
    </div>
  </div>
    <a id="prime" class="fab"><i class="prime zmdi zmdi-comment-outline"></i></a>
</div>
<script  src="Chat/script.js"></script>
