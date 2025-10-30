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
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
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
if($_POST["btn_generate"] == "Generate") {
    //echo "submit";
        $staffid = $_SESSION['sid'];
        $sheet_id = trim($_POST['wordorderno']);
        $mb_date = dt_format($_POST['txt_date']);
        $fromdate = dt_format($_POST['txt_fromdate']);
        $todate = dt_format($_POST['txt_todate']);
        $mb_no = trim($_POST['currentmbook']);
		        $mb_page = trim($_POST['bookpageno']);
        $rbn = trim($_POST['rbnno']);
        $count = trim($_POST['count']);
		$abs_mbno = trim($_POST['currentmbook_abs']);
		//$absmbook_id = trim($_POST['currentmbookno_abs']);
		$abs_page = trim($_POST['bookpageno_abs']);
        $rad_measurementtype = $_POST['rad_measurementtype'];
        $totalpages = 101;
        $break = 0;
		$mbno_id = trim($_POST['currentmbookno']);
		$abs_mbno_id = trim($_POST['currentmbookno_abs']);
		$_SESSION["abs_mbno_id"] = $abs_mbno_id; $_SESSION["mbno_id"] = $mbno_id;
		//echo $abs_mbno."@@".$abs_page."@@".$rbn;exit;
$_SESSION["sheet_id"] = $sheet_id;  $_SESSION["mb_date"] = $mb_date;         $_SESSION["fromdate"] = $fromdate;
$_SESSION["todate"] = $todate;      $_SESSION["mb_no"] = $mb_no;             $_SESSION["mb_page"] = $mb_page;
$_SESSION["rbn"] = $rbn;            $_SESSION["count"] = $count;             $_SESSION["abs_mbno"] = $abs_mbno;
$_SESSION["abs_page"] = $abs_page;
if($rad_measurementtype == "G")
{
	header('Location: GeneralMBook_Composite.php?varid=1');
}
else
{
	header('Location: SteelMBook_Composite.php?varid=1');
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
			.ui-datepicker-header {
   background-color: #20b2aa;
    color: #e0e0e0;
	 border-width: 1px 1px 1px 1px;
    border-style: solid;
    border-color: #990000;
	}
	.ui-datepicker-calendar .ui-state-active {
    background: #6eafbf;
	}
/*.ui-datepicker thead {
    background-color: #20b2aa;
	}*/
        </style>
        <script>
            $(function () {
				$("input:radio[name=rad_measurementtype]").click(function() {
      			var mtype = $(this).val();
				/*if(mtype == "S")
				{
					var type = "Steel";
					$('#mbook_label').text(type)
				}
				else
				{
					var type = "General";
					$('#mbook_label').text(type)
				}*/
				var worderno = document.form.wordorderno.value;
				//alert(mtype);alert(worderno);
      			func_GenerateMBno(mtype,worderno);
       			});
				
				$( "#txt_fromdate" ).datepicker({
					changeMonth: true,
					changeYear: true,
				   	dateFormat: "dd/mm/yy",
				   	yearRange: "2010:+15",
				   	maxDate: new Date,
				   	defaultDate: new Date,
				  /*defaultDate: "+1w",
				  changeMonth: true,
				  maxDate:new Date,
				  dateFormat: "dd/mm/y",*/
				  //minDate:$("#date").val();
				 /* beforeShow: function() {
				  		//alert($( "#txt_date2" ).val());
						 // var x= $( "#txt_fromdate" ).val();
						 // $( "#txt_fromdate" ).datepicker( "option", "minDate", x );
							},*/
				});
				
                $( "#txt_todate" ).datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: "dd/mm/yy",
				yearRange: "2010:+15",
				/*maxDate: new Date,*/
				defaultDate: new Date,
				  /*defaultDate: "+1w",
				  changeMonth: true,
				  maxDate:new Date,
				  dateFormat: "dd/mm/y",
				  //minDate:$("#date").val();
				  beforeShow: function() {
				  		//alert($( "#txt_date2" ).val());
						  var x= $( "#txt_fromdate" ).val();
						  $( "#txt_todate" ).datepicker( "option", "minDate", x );
							},*/
						/*var wordordernovalue = $("#wordorderno option:selected").attr('value');
            			$.post("WorkOrderNoService.php", {wordorderno: wordordernovalue}, function (data) {
                 		var workname = data.split("*");
                 		$("#workname").text(workname[0]);	
						 });	
							*/
							
				});
				
				$.fn.checkdate = function(event) { 
					var wordordernovalue = document.form.wordorderno.value;
					var maxdate = $("#hid_maxdate").val();
					var fromdate = $("#txt_fromdate").val();
					
					/*$.post("find_prevmbookdate.php", {wordorderno: wordordernovalue, getmaxdate: maxdate, getfromdate: fromdate}, function (data) {
					var workname = data.split("*");
					alert(data);
						if(data == 1)
						{
							alert("There is Measurement avaliable ");
							event.preventDefault();
							event.returnValue = false;
						}
						if((data == 0) || (data == ""))
						{
							alert("There is No Measurement avaliable ");
							event.preventDefault();
							event.returnValue = false;
						}
					});*/
					
					var dt1 = maxdate.split("/");
					var dt2 = fromdate.split("/");
					var max_date = new Date(dt1[2], dt1[1]-1, dt1[0]);  // -1 because months are from 0 to 11
					var from_date   = new Date(dt2[2], dt2[1]-1, dt2[0]);
					if(max_date>from_date)
					{
						var a="Already measurement generated for this date";
						$('#check_date').text(a);
						event.preventDefault();
						event.returnValue = false;
					}
					else
					{
						var a="";
						$('#check_date').text(a);
					}
				}
				
				$.fn.validatedate = function(event) { 
					if(($("#txt_fromdate").val()=="") || ($("#txt_todate").val()==""))
					{ 
						var a="Please select any date";
						$('#val_date').text(a);
						event.preventDefault();
						event.returnValue = false;
						//return false;
					}
					else
					{
						var a="";
						$('#val_date').text(a);
					}
				}
				
				 $.fn.validatefromtodate = function(event) { 
				 	var from = $("#txt_fromdate").val();
					var to = $("#txt_todate").val();
					var d1 = from.split("/");
					var d2 = to.split("/");
					var fromdate = new Date(d1[2], d1[1]-1, d1[0]);
					var todate = new Date(d2[2], d2[1]-1, d2[0]);
					if(fromdate>todate)
					{
						var a="From date should be less than To date";
						$('#val_date').text(a);
						event.preventDefault();
						event.returnValue = false;
					}
					else
					{
						var a="";
						$('#val_date').text(a);
					}
				}
				
				$.fn.validateabstractpage = function(event) { 
					var pageno = $("#bookpageno_abs_1").val();
					var remainpage = (100-pageno);
					if(pageno>=90){ 
					alert("This abstract MBook has only "+remainpage+" Pages remaining. Please select another Book.");
					//var a="";
					document.getElementById("currentmbookno_abs").style.border = "1px solid red";
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else
					{
						document.getElementById('currentmbookno_abs').style.removeProperty('border');
					}
					/*else{
					alert("This abstract book has only "+remainpage+" Pages remaining. Please select another Book.");
					event.preventDefault();
					event.returnValue = false;
					}*/
				}
				
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
				 
		 		  $("#txt_fromdate").change(function(event){
				   $(this).validatefromtodate(event);
				 });
				  $("#txt_todate").change(function(event){
				   $(this).validatefromtodate(event);
				 });
				 
					$("#btn_generate").click(function(event){
				   $(this).validatedate(event);
				 });
				 $("#btn_generate").click(function(event){
				   $(this).checkdate(event);
				 });
				 $("#btn_generate").click(function(event){
				   $(this).validateabstractpage(event);
				 });
					
				$("#top").submit(function(event){
					$(this).validateworkorder(event);
					$(this).validatedate(event);
					$(this).validatembookno(event);
					$(this).validateselectrad(event);
					$(this).checkdate(event);
					$(this).validatefromtodate(event);
					$(this).validateabstractpage(event);
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
		  function func_GenerateAbstractMBno()
           { //alert("x")
			    var xmlHttp;
                var data;
				var mtype = "A"; 
                if (window.XMLHttpRequest) // For Mozilla, Safari, ...
                {
                    xmlHttp = new XMLHttpRequest();
                }
                else if (window.ActiveXObject) // For Internet Explorer
                {
                    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                strURL = "find_generatembno.php?sheetid=" + document.form.wordorderno.value + "&mtype=" + mtype;
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
                            document.form.currentmbookno_abs.value = 'Select';
							document.form.bookpageno_abs_1.value = "";
                        }
                        else
                        { 
                            var name = data.split("*");
                            document.form.currentmbookno_abs.length = 0;
							document.form.bookpageno_abs_1.value = "";
                            var optn = document.createElement("option");
                            optn.value = 0;
                            optn.text = " ----- Select ----- ";
                            document.form.currentmbookno_abs.options.add(optn);
                            var c = name.length;
                            var a = c / 2;
                            var b = a + 1;
                            for (i = 1, j = b; i < a, j < c; i++, j++)
                            {
                                var optn = document.createElement("option")
                                optn.value = name[i];
                               // optn.value = name[j];
                                optn.text = name[j];
                                document.form.currentmbookno_abs.options.add(optn)  
                            }
                        }
                    }
                }
                xmlHttp.send(strURL);
        }
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
							document.form.hid_maxdate.value = mbheaddate[0];
                        }
                    }
                }
                xmlHttp.send(strURL);
        }
		
		 function func_check_rbn()
           {
            
                var xmlHttp;
                var data;
				var current_rbn = document.form.rbnno.value;
				if((current_rbn == 0) || (current_rbn < 0))
							{
								document.getElementById('rbn_error').innerHTML = "Entered RAB No. is Invalid..";
								document.getElementById("btn_generate").disabled = true;
							}
							else
							{
								document.getElementById('rbn_error').innerHTML = "";
								document.getElementById("btn_generate").disabled = false;
							}
                if (window.XMLHttpRequest) // For Mozilla, Safari, ...
                {
                    xmlHttp = new XMLHttpRequest();
                }
                else if (window.ActiveXObject) // For Internet Explorer
                {
                    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                strURL = "findrbn.php?workordernumber=" + document.form.wordorderno.value;
                xmlHttp.open('POST', strURL, true);
                xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xmlHttp.onreadystatechange = function ()
                {

                    if (xmlHttp.readyState == 4)
                    {
                        data = xmlHttp.responseText;
                       /* if (data == "")
                        {
                            alert("No Records Found");
                            //document.form.itemno.value = 'Select';
                        }*/
                        if (data != "")
                        {
                            var rbnlist = data.split("*");
							var count = 0;
							var current_rbn = document.form.rbnno.value;
							for(i=0;i<rbnlist.length;i++)
							{
								if(rbnlist[i] == current_rbn)
								{
									count++;
								}
							}
							if(count>0)
							{
								document.getElementById('rbn_error').innerHTML = "Entered RAB No. has already been Generated..";
								document.getElementById("btn_generate").disabled = true;
							}
							else
							{
								document.getElementById('rbn_error').innerHTML = "";
								document.getElementById("btn_generate").disabled = false;
							}
                            /*document.form.txt_fromdate.value = mbheaddate[0];
                            document.form.txt_todate.value = mbheaddate[1];*/
                        }
                    }
                }
                xmlHttp.send(strURL);
        }
		
        function func_GenerateMBno(mtype,worderno)
           { 
			if(worderno == 0) { worderno = document.form.wordorderno.value; }
			if(mtype == 0) { mtype = document.form.rad_measurementtype.value; }
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
                strURL = "find_generatembno.php?sheetid=" + worderno + "&mtype=" + mtype;
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
                            var name = data.split("*");
                            document.form.currentmbookno.length = 0;
                            var optn = document.createElement("option");
                            optn.value = 0;
                            optn.text = " ----- Select ----- ";
                            document.form.currentmbookno.options.add(optn);
                            var c = name.length;
                            var a = c / 2;
                            var b = a + 1;
                            for (i = 1, j = b; i < a, j < c; i++, j++)
                            {
                                var optn = document.createElement("option")
                                optn.value = name[i];
                               // optn.value = name[j];
                                optn.text = name[j];
                                document.form.currentmbookno.options.add(optn)  
                            }
                        }
                    }
                }
                xmlHttp.send(strURL);
        }
		function findabstarctmbbokno()
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
			strURL = "findabstract_mbookno.php?sheetid=" + document.form.wordorderno.value;
			xmlHttp.open('POST', strURL, true);
			xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			xmlHttp.onreadystatechange = function ()
			{
				if (xmlHttp.readyState == 4)
				{
					data = xmlHttp.responseText;
					if (data != "")
					{
						//alert(data);
						var name = data.split("*");
						document.getElementById('bookpageno_abs_1').value = "";
						document.getElementById('bookpageno_abs').value = "";
						document.getElementById('currentmbook_abs').value = "";
						
						document.getElementById('currentmbookno_abs').value = name[2];
						document.getElementById('bookpageno_abs_1').value = name[1];
						document.getElementById('bookpageno_abs').value = name[1];
						document.getElementById('currentmbook_abs').value = name[0];
						document.getElementById('rbnno').value = name[3];
					}
				}
			}
		xmlHttp.send(strURL);
		}
		
		function checkmeasurement()
		{
			var workorderno = document.form.wordorderno.value;
			var fromdate = document.form.txt_fromdate.value;
			var todate = document.form.txt_todate.value;
			var measure_type = document.form.rad_measurementtype.value;
			var current_rbn = document.form.rbnno.value;
							if(current_rbn == "")
							{
								document.getElementById('rbn_error').innerHTML = "Entered RAB No. is Invalid..";
								document.getElementById("btn_generate").disabled = true;
							}
							else
							{
								document.getElementById('rbn_error').innerHTML = "";
								document.getElementById("btn_generate").disabled = false;
							}
			if((workorderno != "") && (fromdate != "") && (todate != "") && (measure_type != ""))
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
				strURL = "check_measurement_generate.php?sheetid=" + workorderno + "&fromdate=" + fromdate + "&todate=" + todate + "&measure_type=" + measure_type;
				xmlHttp.open('POST', strURL, true);
				xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				xmlHttp.onreadystatechange = function ()
				{
					if (xmlHttp.readyState == 4)
					{
						data = xmlHttp.responseText;
						if (data != "")
						{
							//alert(data);
							if(data == 0)
							{
								//alert("No measurements available to generate MBook..!");
								swal("No measurements available to generate MBook..!", "", "");
								return false;
							}
							else
							{
								return true;
							}
						}
					}
				}
			}
		xmlHttp.send(strURL);
		//return false;
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
	function ValidateForm(id)
	{
		if(id == "txt_fromdate")
		  {	var dt=document.form.txt_fromdate.value; }
		if(id == "txt_todate")
		  {	var dt=document.form.txt_todate.value; }
		if (isDate(dt)==false)
		{
			var a="Date format should be dd/mm/yyyy";
			$('#date_format').text(a);
			return false
		}
		if (isDate(dt)==true)
		{
			var a="";
			$('#date_format').text(a);
			return true;
		}
	 }
	function goBack()
	{
	   	url = "dashboard.php";
		window.location.replace(url);
	}
			
        </script>
  <SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</SCRIPT> 

    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <?php include "Menu.php"; ?>
       
        <!--==============================Content=================================-->
        <div class="content">
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <div class="title">Sub-Abstract Generate </div>
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
                       
                            <div class="container">

                                <table width="1000"  bgcolor="#E8E8E8" border="0" cellpadding="0" cellspacing="0" align="center" >
                                    <tr><td>&nbsp;</td></tr>
                                    <tr> 
                                        <td width="15%">&nbsp;</td> 
                                        <td  class="label">Date</td>

                                        <td><input type="text" name="txt_date" readonly="" id="txt_date" class="textboxdisplay" value="<?php echo date('d/m/Y') ?>" size="15"/>				                 
                                            </td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                    <tr> 
                                        <td>&nbsp;</td> 
                                        <td  class="label">Work Short Name</td>
                                        <td  class="labeldisplay"><?php
                                            $sql_itemno = "select sheet_id ,work_order_no  from sheet WHERE active =1";
                                            $rs_itemno = mysql_query($sql_itemno);
                                            ?>
                                            <select name="wordorderno" id="wordorderno"  class="textboxdisplay" tabindex="1" onChange="func_mbhead_date(); func_GenerateMBno(0,0);" style="width:405px;height:22px;" tabindex="7">
                                                        <option value=""> ----------------------- Select Work Order ----------------------- </option>
                                                        <?php //echo $objBind->BindWorkOrderNo(0); ?>
														<?php echo $objBind->BindWorkOrderNo_CIVIL(0); ?>
                                            </select></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr><td>&nbsp;</td><td></td><td colspan="3" id="val_work" style="color:red"></td></tr>
									<tr>
                                        <td>&nbsp;</td>
                                        <td  class="label">Work Order No.</td>
                                        <td  class="labeldisplay">
										<input type="text" name="txt_workorder_no" id="txt_workorder_no" class="textboxdisplay" style="width: 399px;" readonly="">
										</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td  class="label">Name of the Work </td>
                                        <td  class="labeldisplay">
										<textarea name="workname" class="textboxdisplay txtarea_style" id="workname" rows="4" style="width: 402px;" disabled="disabled"></textarea>
										</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
                                    <tr> 
                                        <td>&nbsp;</td>
                                        <td  class="label">Measurement Type</td>
                                        <td  class="label">
										<input type="radio" name="rad_measurementtype" id="rad_steel" tabindex="2" value="S" onClick="">Steel&nbsp;&nbsp;&nbsp;
										<input type="radio" name="rad_measurementtype" id="rad_others" tabindex="3" value="G">General</td>
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
                                        <td  class="labeldisplay">
										<input type="text" name="txt_fromdate" id="txt_fromdate" tabindex="4" class="textboxdisplay" value="" onChange="return ValidateForm('txt_fromdate');" size="15"/>
										<span id="check_date" style="color:red;"></span>
                                            </td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                    <tr> 
                                        <td>&nbsp;</td> 
                                        <td  class="label">To Date </td>

                                        <td  class="labeldisplay">
										<input type="text" name="txt_todate" id="txt_todate" tabindex="5" class="textboxdisplay" value="" onChange="return ValidateForm('txt_todate');" size="15"/>
										<span id="date_format" style="color:red;"></span>								
                                            </td>
                                         <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr> 
                                        <td>&nbsp;</td>
                                        <td  class="label">&nbsp;</td>
                                        <td  class="labeldisplay" id="val_date" style="color:red">&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
									<tr> 
                                        <td>&nbsp;</td> 
                                        <td  class="label">Running Account Bill No </td>

                                        <td  class="labeldisplay">
										<input type="number" name="rbnno" id="rbnno" tabindex="8" class="textboxdisplay" style="width:123px"  tabindex="5" onBlur="func_check_rbn();"/>
										
                                           <!-- <input type="hidden" name="rbnno" id="rbnno" />-->
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>

                                    <tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td><span id="rbn_error" style="color:red; font-weight:bold"></span></td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
                                	<tr> 
                                        <td>&nbsp;</td> 
                                        <td  class="label">Measurement Book  No</td>

                                        <td  class="label">
                                            <?php 
                                            //echo "x=".$_SESSION['staffid'];
                                           // if($_SESSION['sid'] == 0) { $Logonstaffid =0;} else {  $Logonstaffid =$_SESSION['sid']; } 
                                            //echo $objBind->BindMBook(-1,$Logonstaffid); 
                                            
                                            ?>
                                            
											<select name="currentmbookno" id="currentmbookno" class="textboxdisplay" tabindex="6" style="width:130px;height:22px;" tabindex="7" onChange="func_GenerateAbstractMBno(); findabstarctmbbokno();">
                                                <option value="0" selected="selected"> ----- Select ----- </option>
                                                        <?php // echo $objBind->BindMBook(-1,$staffid); ?>
                                            </select>
                                            <font class="">&nbsp;&nbsp;&nbsp;MBook Page &nbsp;&emsp;&nbsp;&nbsp;</font>&nbsp;
                                            <input type="hidden" name="currentmbook" id="currentmbook" />
											<input type="text" name="bookpageno1" id="bookpageno1" class="textboxdisplay"  size="15"/>
                                            <input type="hidden" name="bookpageno" id="bookpageno" />
                                            <input type="hidden" name="count" id="count" />
											
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                <tr><td>&nbsp;</td><td></td><td id="val_mbook" style="color:red"></td></tr>
                                    <tr> 
                                        <td>&nbsp;</td> 
                                        <td  class="label">Abstract MBook No. </td>

                                        <td  class="label">
											
											<select name="currentmbookno_abs" id="currentmbookno_abs" tabindex="7" class="textboxdisplay" style="width:130px;height:22px;" tabindex="7">
                                                <option value="0" selected="selected"> ----- Select ----- </option>
                                                        <?php // echo $objBind->BindMBook(-1,$staffid); ?>
                                            </select>
											<font class="">&nbsp;&nbsp;&nbsp;Abs MBook Page</font>&nbsp;
											<input type="hidden" name="currentmbook_abs" id="currentmbook_abs" />
											<input type="text" name="bookpageno_abs_1" id="bookpageno_abs_1" class="textboxdisplay"  size="15"/>
											<input type="hidden" name="bookpageno_abs" id="bookpageno_abs" />
											
											<!--<input type="hidden" name="count_abs" id="count_abs" />-->
											<!--<input type="hidden" name="hid_prev_abstmbno" id="hid_prev_abstmbno" value="<?php echo $_SESSION["abs_mbno_id"]; ?>"/>-->
											
                                        </td>
										
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                    
									<tr><td>&nbsp;</td></tr>
                                    <tr>
                                        <td colspan="6">
                                    <center>
                                        <input type="hidden" class="text" name="submit" tabindex="9" value="true" />
						 <!--           <input type="submit" class="btn" data-type="submit" value="submit" />	-->
										<!--<input type="submit" class="btn" data-type="submit" value="Generate" name="btn_generate" id="btn_generate" onMouseOver="checkmeasurement();"/>&nbsp;&nbsp;&nbsp;
										<input type="submit" class="btn" data-type="submit" value="Excel Format" name="xcel" id="xcel"   style="display: none;" />
										<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>-->
										<input type="hidden" name="hid_staffid" id="hid_staffid" value="<?php echo $staffid; ?>">
                                    </center>	    </td>
                                    </tr>
                                    <tr><td></td></tr>

                                </table>
							<input type="hidden" name="hid_maxdate" id="hid_maxdate" >
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
								<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<div class="buttonsection" style="width:110px">
								<input type="submit" class="btn" data-type="submit" value="Generate" name="btn_generate" id="btn_generate" onMouseOver="checkmeasurement();"/>
								</div>
							</div>

                            </div>
                    </blockquote>
                                <!-- <div class="container">
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
                                </div>-->
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
            var currentmbooknovalue 	= 	$("#currentmbookno option:selected").attr('value');//alert(currentmbooknovalue);
            var currentmbooknotext 		= 	$("#currentmbookno option:selected").text();
			var wordordernovalue 		= 	$("#wordorderno option:selected").attr('value');
			var staffid					=	$("#hid_staffid").val();
			var currentrbn				=	$("#rbnno").val();
			var generatetype 			= 	"cw";
            $.post("MBookNoService.php", {currentmbook: currentmbooknovalue, currentbmookname: currentmbooknotext, sheetid: wordordernovalue, generatetype: generatetype, staffid: staffid, currentrbn: currentrbn}, function (data) { //alert(data);
                //$("#bookpageno1").val(Number(data) + 1);$("#bookpageno").val(Number(data) + 1);
				$("#bookpageno1").val(data);$("#bookpageno").val(data);
               	$("#currentmbook").val(currentmbooknotext); 
                
            });
        }
		 function DisplayAbsPageDetails() {
            var currentmbooknoabsvalue 	= 	$("#currentmbookno_abs option:selected").attr('value');//alert(currentmbooknovalue);
            var currentmbooknoabstext 	= 	$("#currentmbookno_abs option:selected").text();
			var wordordernovalue 		= 	$("#wordorderno option:selected").attr('value');
			var staffid					=	$("#hid_staffid").val();
			var currentrbn				=	$("#rbnno").val();
			var generatetype 			= 	"cw";
            $.post("MBookNoService.php", {currentmbook: currentmbooknoabsvalue, currentbmookname: currentmbooknoabstext, sheetid: wordordernovalue, generatetype: generatetype, staffid: staffid, currentrbn: currentrbn}, function (data) { //alert(data);
                //$("#bookpageno_abs_1").val(Number(data) + 1);$("#bookpageno_abs").val(Number(data) + 1); 
				$("#bookpageno_abs_1").val(data);$("#bookpageno_abs").val(data);
               	$("#currentmbook_abs").val(currentmbooknoabstext); 
                
            });
        }
        function DisplayRBNDetails() {
            var wordordernovalue = $("#wordorderno option:selected").attr('value');
            $.post("WorkOrderNoService.php", {wordorderno: wordordernovalue}, function (data) {
                 var workname = data.split("*");
                 $("#workname").text(workname[0]);
				 $("#txt_workorder_no").val(workname[2]);
                //$("#rbnno1").val(Number(workname[1]) + 1);$("#rbnno").val(Number(workname[1]) + 1);
            });
        }
        $("#currentmbookno").bind("change", function () {   
            DisplayPageDetails();
        });
		 $("#currentmbookno_abs").bind("change", function () {   
            DisplayAbsPageDetails();
        });
         $("#wordorderno").bind("change", function () {   
            DisplayRBNDetails();
        });
    });
	
</script>
    </body>
</html>

