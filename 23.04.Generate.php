<?php
////session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
checkUser();
include "sysdate.php";
$msg = '';
$_SESSION["newmbookno"]='';
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
$popupwindow =0;
//echo $_SESSION['staffid'];
//if (isset($_POST['submit'])){
if($_POST["html"] == "Generate") {
    //echo "submit";
        $staffid =$_SESSION['userid'];
        $sheet_id = trim($_POST['wordorderno']);
        $mb_date = dt_format($_POST['txt_date']);
        $fromdate = dt_format($_POST['txt_fromdate']);
        $todate = dt_format($_POST['txt_todate']);
        $mb_no = trim($_POST['currentmbook']);
        $mb_page = trim($_POST['bookpageno']);
        $rbn = trim($_POST['rbnno']);
        $count = trim($_POST['count']);
        $rad_measurementtype = $_POST['rad_measurementtype'];
        $totalpages = 101;
        $break = 0;
$_SESSION["sheet_id"] = $sheet_id;  $_SESSION["mb_date"] = $mb_date;         $_SESSION["fromdate"] = $fromdate;
$_SESSION["todate"] = $todate;      $_SESSION["mb_no"] = $mb_no;             $_SESSION["mb_page"] = $mb_page;
$_SESSION["rbn"] = $rbn;            $_SESSION["count"] = $count;             $_SESSION["staffid"] = $staffid;
if($rad_measurementtype == "others")
{
	header('Location: MBook.php');
}
else
{
	header('Location: SteelMBook.php');
}
//$popupwindow =1;
// $Query =" SELECT COUNT(*) RecordCount
//FROM     mbookheader     INNER JOIN mbookdetail         ON (mbookheader.mbheader_id = mbookdetail.mbheader_id)
//WHERE  mbookheader.date  >= '$fromdate' AND mbookheader.date  <= '$todate'";
//// echo $Query;
//            $SQL = mysql_query($Query);          $count='';
//            if ($SQL == true )        {    $row = mysql_fetch_array($SQL);    $count = $row['RecordCount'];  }
        //$totalcount = ($count / 40);      // echo $totalcount;
//        if ($totalcount < $totalpages) { 
//            for ($init = $mb_page; $init <= $totalpages; $init++) {
//                if ($break == 1) {
//                    break;
//                } //echo  "<BR>  if".$init;
//                if ($init == 100) {
//                    
//
//                }
//                if ($init == 101) { $popupwindow =1;$break =1;  }
//              }
//            } else {
//            $GenerateSQL = "INSERT INTO mbookgenerate SET staffid ='$staffid', sheetid ='$sheet_id',   divid='$divid', subdivid='$subdivid'
//                     , mbgeneratedate ='$mb_date',fromdate='$fromdate',todate='$todate',
//                 mballotmentid='$mb_no', rbn='$rbn', active='1', userid ='1'";
//                    echo  "<BR>".$GenerateSQL;
//                    $GenerateSQLQuery = mysql_query($GenerateSQL);
//                    if ($GenerateSQLQuery == true) {
//                    $last_id = mysql_insert_id();
//                    header('Location: MBook.php?page=0&sheetid='.$sheet_id.'&id='.$last_id);
//                    } 
//            }
} //submit 

if($_POST["btn_Update"] == "Update")
{   //echo "update";
        $sheet_id =$_SESSION["sheet_id"];	$mb_date=$_SESSION["mb_date"];        $fromdate=$_SESSION["fromdate"];
        $todate=$_SESSION["todate"];	$mb_no=$_SESSION["mb_no"];        $mb_page=$_SESSION["mb_page"];       $rab=$_SESSION["rab"];
        $staffid= $_SESSION["staffid"];
        $count=$_SESSION["count"];   $mbnovalue = trim($_POST['mbnovalue']);
        header('Location: MBook.php');
       // $totalpages =101; $break  =0;$prev_id=0;
       // $totalcount =106;//($count /40) + ($mb_page - 1);
        //echo $totalcount;
//        if($totalcount > $totalpages) { 
//            for ($init = $mb_page; $init <=101; $init++) {
//                  if($break ==1) {break;}
//                    if($init == 100) { $GenerateSQL="INSERT INTO mbookgenerate SET sheet_id ='$sheet_id', mb_date ='$mb_date',fromdate='$fromdate',todate='$todate',
//                                        mb_no='$mb_no', mb_page='$mb_page',endpage ='100',rbn ='$rab',active='1', userid ='1',mb_endpage='0'";
//                                        $GenerateSQLQuery = mysql_query($GenerateSQL); //echo "<br>".$GenerateSQL;
//                                        if($GenerateSQLQuery == true)        {  $prev_id = mysql_insert_id(); }
//                    }
//                    else if($init == 101) {  $mb_page =1; $mb_no =$mb_no+1; $break =1; $GenerateSQL="INSERT INTO mbookgenerate SET sheet_id ='$sheet_id', mb_date ='$mb_date',fromdate='$fromdate',todate='$todate',
//                                        mb_no='$mbnovalue', mb_page='$mb_page', rbn ='$rab',active='1', userid ='1',mb_endpage='1'" ;
//                                        $GenerateSQLQuery = mysql_query($GenerateSQL); //echo "<br>".$GenerateSQL;
//                                        if($GenerateSQLQuery == true)        {          $last_id = mysql_insert_id();        
//                                        header('Location: MBook.php?page=1&sheetid='.$sheet_id.'&id='.$prev_id);
//                                        }
//                                        
//                }
//                else {
//                    
//                }
//            }
//        }
        
}
if ($_POST["xcel"] == "Excel Format") {
    $sqlsheet = "select * from sheet where active=1";
    $rssheet = mysql_query($sqlsheet, $conn);
    $workorderno = @mysql_result($rssheet, 0, 'work_order_no');
    $workname = @mysql_result($rssheet, 0, 'work_name');

    $sqlmbookheader = "select mbook_header.mbheader_id,mbook_header.tech_sanction,mbook_header.subdiv_id,mbook_header.name_contractor,mbook_header.agree_no,mbook_header.runn_acc_bill_no 
							from mbook_header INNER JOIN mbook_detail ON (mbook_detail.mbheader_id = mbook_header.mbheader_id)
							WHERE mbook_header.subdiv_id = mbook_detail.subdiv_id";
    $rsmbookheader = mysql_query($sqlmbookheader, $conn);
    $techsanction = trim(@mysql_result($rsmbookheader, 0, 'tech_sanction'));
    $contractorname = trim(@mysql_result($rsmbookheader, 0, 'name_contractor'));
    $agreeno = trim(@mysql_result($rsmbookheader, 0, 'agree_no'));
    $billno = trim(@mysql_result($rsmbookheader, 0, 'runn_acc_bill_no'));
    $subdivid = trim(@mysql_result($rsmbookheader, 0, 'subdiv_id'));
    // Export to Excel Part
    unset($_SESSION['report_header']);
    unset($_SESSION['report_header1']);
    unset($_SESSION['report_header2']);
    unset($_SESSION['report_header3']);
    unset($_SESSION['report_values']);
    unset($_SESSION['report_header4']);
    unset($_SESSION['report_header5']);
    unset($_SESSION['report_values_A']);
    unset($_SESSION['report_header6']);

    $sqlsubdivid = "SELECT mbook_header.date, mbook_header.div_id , mbook_header.subdiv_id
                                    , subdivision.subdiv_name , mbook_detail.desc_work, mbook_detail.measurement_no
                                    , mbook_detail.measurement_l , mbook_detail.measurement_b, mbook_detail.measurement_d
                                    , mbook_detail.measurement_contentarea        , mbook_detail.remarks
                                    FROM mbook_header
                                    INNER JOIN mbook_detail    ON (mbook_header.mbheader_id = mbook_detail.mbheader_id) AND (mbook_header.subdiv_id = mbook_detail.subdiv_id)
                                    INNER JOIN subdivision     ON (mbook_header.subdiv_id = subdivision.subdiv_id)";
    $rssubdivid = mysql_query($sqlsubdivid, $conn);

    $i = 0;
    while ($result = mysql_fetch_assoc($rssubdivid)) {
        $_SESSION['report_values_A'][$i][0] = $result['subdiv_name'];
        $i++;
    }

    //$sqlsubdesc="select mbook_detail.subdiv_id,mbook_detail.desc_work from mbook_detail INNER JOIN mbook_header ON (mbook_header.subdiv_id = mbook_detail.subdiv_id) order by mbook_detail.subdiv_id";
    $sqlsubdesc = "select mbook_detail.subdiv_id,mbook_detail.desc_work from mbook_detail INNER JOIN subdivision  ON (subdivision.subdiv_id = mbook_detail.subdiv_id) order by subdivision.subdiv_id";
    $rssubdesc = mysql_query($sqlsubdesc, $conn);

    $_SESSION['report_header'] = array("" . $workname . "");
    $_SESSION['report_header1'] = array("" . $techsanction . "");
    $_SESSION['report_header2'] = array("" . $contractorname . "");
    $_SESSION['report_header3'] = array("" . $agreeno . "");
    $_SESSION['report_header4'] = array("" . $workorderno . "");
    $_SESSION['report_header5'] = array("" . $billno . "");
    $_SESSION['report_header6'] = array("" . $subdivid . "");
    ?>
    <script language="javascript" type="text/javascript">
        window.location.href('excel/mbookexcel.php')
    </script>
    <?php
}
?>
<?php require_once "Header.html"; ?>

        <link href="css/modalPopLite.css" rel="stylesheet" type="text/css" />
        <script src="js/modalPopLite.js" type="text/javascript"></script>
        <script src="js/modalPopLite.min.js" type="text/javascript"></script>
        <style type="text/css">
            .container {width: 960px; margin: 0 auto; overflow: hidden;}
            #content {	float: left; width: 100%;}
            .post { margin: 0 auto; padding-bottom: 50px; float: left; width: 960px; }
            #mask {
                display: none;
                background: #000; 
                position: fixed; left: 0; top: 0; 
                z-index: 10;
                width: 100%; height: 100%;
                opacity: 0.8;
                z-index: 999;
            }
            .login-popup{
                display:none;
                background: #333;
                padding: 10px; 	
                border: 2px solid #ddd;
                float: left;
                font-size: 1.2em;
                position: fixed;
                top: 50%; left: 50%;
                z-index: 99999;
                box-shadow: 0px 0px 20px #999;
                -moz-box-shadow: 0px 0px 20px #999; /* Firefox */
                -webkit-box-shadow: 0px 0px 20px #999; /* Safari, Chrome */
                border-radius:3px 3px 3px 3px;
                -moz-border-radius: 3px; /* Firefox */
                -webkit-border-radius: 3px; /* Safari, Chrome */
            }

            img.btn_close {
                float: right; 
                margin: -28px -28px 0 0;
            }

            fieldset { 
                border:none; 
            }

            form.signin .textbox label { 
                display:block; 
                padding-bottom:7px; 
            }

            form.signin .textbox span { 
                display:block;
            }

            form.signin p, form.signin span { 
                color:#999; 
                font-size:11px; 
                line-height:18px;
            } 

            form.signin .textbox input { 
                background:#666666; 
                border-bottom:1px solid #333;
                border-left:1px solid #000;
                border-right:1px solid #333;
                border-top:1px solid #000;
                color:#fff; 
                border-radius: 3px 3px 3px 3px;
                -moz-border-radius: 3px;
                -webkit-border-radius: 3px;
                font:13px Arial, Helvetica, sans-serif;
                padding:6px 6px 4px;
                width:200px;
            }

            form.signin input:-moz-placeholder { color:#bbb; text-shadow:0 0 2px #000; }
            form.signin input::-webkit-input-placeholder { color:#bbb; text-shadow:0 0 2px #000;  }

            .button { 
                background: -moz-linear-gradient(center top, #f3f3f3, #dddddd);
                background: -webkit-gradient(linear, left top, left bottom, from(#f3f3f3), to(#dddddd));
                background:  -o-linear-gradient(top, #f3f3f3, #dddddd);
                filter: progid:DXImageTransform.Microsoft.gradient(startColorStr='#f3f3f3', EndColorStr='#dddddd');
                border-color:#000; 
                border-width:1px;
                border-radius:4px 4px 4px 4px;
                -moz-border-radius: 4px;
                -webkit-border-radius: 4px;
                color:#333;
                cursor:pointer;
                display:inline-block;
                padding:6px 6px 4px;
                margin-top:10px;
                font:12px; 
                width:214px;
            }

            .button:hover { background:#ddd; }

        </style>
        <script>
            $(function () {
                $( "#txt_date3" ).datepicker({
				  defaultDate: "+1w",
				  changeMonth: true,
				  maxDate:new Date,
				  dateFormat: "dd/mm/y",
				  //minDate:$("#date").val();
				  beforeShow: function() {
				  		//alert($( "#txt_date2" ).val());
						  var x= $( "#txt_date2" ).val();
						  $( "#txt_date3" ).datepicker( "option", "minDate", x );
							},
				});
                $.fn.validateworkorder = function(event) { 
					if($("#wordorderno").val()==""){ 
					var a="Please select the work order number";
					$('#val_work').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_work').text(a);
					}
				}
				
				$.fn.validatembookno = function(event) { 
                                      var currentmbooknovalue = $("#currentmbookno option:selected").attr('value');
					if(currentmbooknovalue=="0"){ 
					var a="Please select the Mbook number";
					$('#val_mbook').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_mbook').text(a);
					}
				}
				$.fn.validateselectrad = function(event) { 
				if ($('[name="rad_measurementtype"]').is(':checked')){
				var a="";
					$('#val_rad').text(a);

					}
					else{
					var a="Please select Measurement Type";
					$('#val_rad').text(a);
					event.preventDefault();
					event.returnValue = false;
					}
					
					
				}
				
				$("#wordorderno").change(function(event){
				   $(this).validateworkorder(event);
				 });
 
				 $("#currentmbookno").change(function(event){
				   $(this).validatembookno(event);
				 });
				  $("#rad_steel").change(function(event){
				   $(this).validateselectrad(event);
				 });
				  $("#rad_others").change(function(event){
				   $(this).validateselectrad(event);
				 });
		 
				$("#top").submit(function(event){
					$(this).validateworkorder(event);
					$(this).validatembookno(event);
					$(this).validateselectrad(event);
					});
                $("#div.btn-sign").hide();
                // When clicking on the button close or the mask layer the popup closed
                $("a.close, #mask").live("click", function () {
                    $("#mask , .login-popup").fadeOut(300, function () {
                        $("#mask").remove();
                    });
                    return(false);
                });
                $("#div.btn-sign").hide();
                // When clicking on the button close or the mask layer the popup closed
                $("a.close, #mask").live("click", function () {
                    $("#mask , .login-popup").fadeOut(300, function () {
                        $("#mask").remove();
                    });
                    return(false);
                });
            });
        </script>
        <script LANGUAGE='javascript'>
           function func_mbhead_date()
            {
            
            var xmlHttp;
            var data;
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "find_mbheader_date.php?sheetid=" + document.form.wordorderno.value;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText;
                    if (data == "")
                    {
                        alert("No Records Found");
                        document.form.itemno.value = 'Select';
                    }
                    else
                    {
                        var mbheaddate = data.split("*");
                        document.form.txt_fromdate.value = mbheaddate[0];
                        document.form.txt_todate.value = mbheaddate[1];
                    }
                }
            }
            xmlHttp.send(strURL);
        }
        </script>
        <script LANGUAGE='javascript'>
            function showHide()
            {
                var href = $('div.btn-sign').find('h2 a').attr('href');
                //var loginBox = $(this).attr('href');
                var loginBox = href;
                //Fade in the Popup and add close button
                $(loginBox).fadeIn(300);
                //Set the center alignment padding + border
                var popMargTop = ($(loginBox).height() + 24) / 2;
                var popMargLeft = ($(loginBox).width() + 24) / 2;
                $(loginBox).css({
                    'margin-top': -popMargTop,
                    'margin-left': -popMargLeft
                });
                // Add the mask to body
                $('body').append('<div id="mask"></div>');
                $('#mask').fadeIn(300);
                return false;
            }
        </script>
   

    <body class="page1" id="top">
        <!--==============================header=================================-->
        <?php include "Menu.php"; ?>
       
        <!--==============================Content=================================-->
        <div class="content">
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <div class="title">Measurement Book Generate </div>
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                       
                            <div class="content">

                                <table width="1000"  bgcolor="#E8E8E8" border="0" cellpadding="0" cellspacing="0" align="center" >
                                    <tr><td>&nbsp;</td></tr>
                                    <tr> 
                                        <td>&nbsp;</td> 
                                        <td  class="label">Date</td>

                                        <td><input type="text" name="txt_date" readonly="" id="txt_date" class="textboxdisplay" value="<?php echo date('d/m/y') ?>" size="8"/>				                 
                                            </td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                    <tr> 
                                        <td>&nbsp;</td> 
                                        <td  class="label">Work Order No </td>
                                        <td  class="labeldisplay"><?php
                                            $sql_itemno = "select sheet_id ,work_order_no  from sheet WHERE active =1";
                                            $rs_itemno = mysql_query($sql_itemno);
                                            ?>
                                            <select name="wordorderno" id="wordorderno"  class="textboxdisplay" onchange="func_mbhead_date();" style="width:400px;height:22px;" tabindex="7">
                                                        <option value=""> -- Select Work Order No -- </option>
                                                        <?php echo $objBind->BindWorkOrderNo(0); ?>
                                            </select></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr><td>&nbsp;</td><td></td><td colspan="3" id="val_work" style="color:red"></td></tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td  class="label">Name of the Work </td>
                                        <td  class="labeldisplay"><textarea name="workname" id="workname" cols="48" rows="5" class="textboxdisplay"></textarea></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                                    <tr> 
                                        <td>&nbsp;</td>
                                        <td  class="label">Measurement Type</td>
                                        <td  class="label">
					<input type="radio" name="rad_measurementtype" id="rad_steel" value="steel">Steel&nbsp;&nbsp;&nbsp;
					<input type="radio" name="rad_measurementtype" id="rad_others" value="others">Others</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr> 
                                        <td>&nbsp;</td>
                                        <td  class="label">&nbsp;</td>
                                        <td  class="labeldisplay" id="val_rad" style="color:red">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr> 
                                        <td>&nbsp;</td> 
                                        <td  class="label">From Date </td>
                                        <td  class="labeldisplay"><input type="text" readonly="" name="txt_fromdate" id="txt_date2" class="textboxdisplay" value="" size="8"/>
                                            </td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                    <tr> 
                                        <td>&nbsp;</td> 
                                        <td  class="label">To Date </td>

                                        <td  class="labeldisplay"><input type="text" readonly="" name="txt_todate" id="txt_date3" class="textboxdisplay" value="" size="8"/>
                                            </td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                <tr> 
                                        <td>&nbsp;</td> 
                                        <td  class="label">M Book  No</td>

                                        <td  class="labeldisplay">
                                            <?php 
                                            //echo "x=".$_SESSION['staffid'];
                                            if($_SESSION['staffid'] == 0) { $Logonstaffid =0;} else {  $Logonstaffid =$_SESSION['staffid']; } 
                                            //echo $objBind->BindMBook(-1,$Logonstaffid); 
                                            
                                            ?>
                                            <select name="currentmbookno" id="currentmbookno" class="textboxdisplay" style="width:400px;height:22px;" tabindex="7">
                                                <option value="0" selected="selected"> -- Select MBook No -- </option>
                                                        <?php echo $objBind->BindMBook(-1,$Logonstaffid); ?>
                                            </select>
                                            
                                            <input type="hidden" name="currentmbook" id="currentmbook" />
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                <tr><td>&nbsp;</td><td></td><td id="val_mbook" style="color:red"></td></tr>
                                    <tr> 
                                        <td>&nbsp;</td> 
                                        <td  class="label">M Book Page </td>

                                        <td  class="labeldisplay"><input type="text" name="bookpageno1" id="bookpageno1" class="textboxdisplay"  size="40" tabindex="5"/>
                                            <input type="hidden" name="bookpageno" id="bookpageno" />
                                            <input type="hidden" name="count" id="count" />
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                    <tr> 
                                        <td>&nbsp;</td> 
                                        <td  class="label">Running Account Bill No </td>

                                        <td  class="labeldisplay"><input type="text" name="rbnno1" id="rbnno1" class="textboxdisplay" disabled="disabled" size="40" tabindex="5"/>
                                            <input type="hidden" name="rbnno" id="rbnno" />
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>

                                    <tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>
                                    <tr>
                                        <td colspan="6">
                                    <center>
                                        <input type="hidden" class="text" name="submit" value="true" />
                     <!--            <input type="submit" class="btn" data-type="submit" value="submit" />	-->
<input type="submit" class="btn" data-type="submit" value="Generate" name="html" id="html"   />&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" class="btn" data-type="submit" value="Excel Format" name="xcel" id="xcel"   style="display: none;" />
                                    </center>	    </td>
                                    </tr>
                                    <tr><td></td></tr>

                                </table>


                            </div>
                            <div class="col2"><?php if ($msg != '') {
    echo $msg;
    } ?></div> 
                         
                        
                    </blockquote>
                
                                 <div class="container">
                                    <div id="content">
                                        <div class="post"><div class="btn-sign"><h2><a href="#login-box" class="login-window"></a></h2></div></div>
                                        <div id="login-box" class="login-popup">
                                            <a href="#" class="close"><img src="images/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>
                                            <fieldset class="textbox">
                                                <label class="username">
                                                    <span> New Mbook No :</span>
                                                    <input id="mbnovalue" name="mbnovalue" type="text" autocomplete="on" placeholder="MBookNo">
                                                </label><br/>
                                                <input type="submit" class="btn" data-type="submit" name="btn_Update" id="btn_Update" value="Update" />
                                            </fieldset>
                                        </div>

                                    </div>
                                </div>
                       <?php     if($popupwindow == 1)  { echo "<script LANGUAGE='javascript'>showHide();</SCRIPT>"; } ?>
                    </form>
                </div>

            </div>
        </div>
         <!--==============================footer=================================-->
    <?php   include "footer/footer.html"; ?>
     <script>

    $(function () {
        function DisplayPageDetails() {
            var currentmbooknovalue = $("#currentmbookno option:selected").attr('value');
            var currentmbooknotext = $("#currentmbookno option:selected").text();
            $.post("MBookNoService.php", {currentmbook: currentmbooknovalue}, function (data) {
                $("#bookpageno1").val(Number(data) + 1);$("#bookpageno").val(Number(data) + 1);
               $("#currentmbook").val(currentmbooknotext); 
                
            });
        }
        function DisplayRBNDetails() {
            var wordordernovalue = $("#wordorderno option:selected").attr('value');
            $.post("WorkOrderNoService.php", {wordorderno: wordordernovalue}, function (data) {
                 var workname = data.split("*");
                 $("#workname").text(workname[0]);
                $("#rbnno1").val(Number(workname[1]) + 1);$("#rbnno").val(Number(workname[1]) + 1);
            });
        }
        $("#currentmbookno").bind("change", function () {   
            DisplayPageDetails();
        });
         $("#wordorderno").bind("change", function () {   
            DisplayRBNDetails();
        });
    });
	
</script>
    </body>
</html>

