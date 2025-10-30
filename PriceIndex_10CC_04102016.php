<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/common.php';
$msg = '';
$userid = $_SESSION['userid'];
$staffid = $_SESSION['sid'];
function dt_format($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);

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
    return $dd . '-' . $mm . '-' . $yy;
}
if (isset($_POST["submit"])) 
{
   	$sheetid 				= 	trim($_POST['cmb_shortname']);
	$x = array();
	$pi_month1			= 	$_POST['txt_month1'];
	$pi_month2 			= 	$_POST['txt_month2'];
	$pi_month3 			= 	$_POST['txt_month3'];
	$from_period 		= 	new DateTime($pi_month1);
	$to_period 			= 	new DateTime($pi_month3);
	$pi_from_period 	=	date_format($from_period,'Y-m-d');
	$pi_to_period 		=	date_format($to_period,'Y-m-t');
	//echo $pi_from_period."<br/>";
	//echo $pi_to_period."<br/>";exit;
	$bid_arr			= 	$_POST['txt_bid'];
	$pi_rate1_arr 		= 	$_POST['txt_price_index_rate_m1'];
	$pi_rate2_arr 		= 	$_POST['txt_price_index_rate_m2'];
	$pi_rate3_arr 		= 	$_POST['txt_price_index_rate_m3'];
	$avg_pi_code_arr	= 	$_POST['txt_avg_price_index_code'];
	$avg_pi_rate_arr	= 	$_POST['txt_avg_price_index_rate'];
	$count = count($bid_arr);
	//echo $base_index_item;
	//print_r($base_price_rate_arr);exit;
	//$delete_tca_query = "update base_index set active = '0', modifieddate  = NOW() where sheetid = '$sheetid' and type='TCA'";
	//$delete_tca_sql = mysql_query($delete_tca_query);
	for ($c = 0; $c<$count; $c++) 
	{
		$bid 			= $bid_arr[$c];
		$pi_rate1 		= $pi_rate1_arr[$c];
		$pi_rate2 		= $pi_rate2_arr[$c];
		$pi_rate3 		= $pi_rate3_arr[$c];
		$avg_pi_code 	= $avg_pi_code_arr[$c];
		$avg_pi_rate 	= $avg_pi_rate_arr[$c];
		
		$delete_tca_query = "update price_index set active = '0', modifieddate  = NOW() where sheetid = '$sheetid' and type='TCC' and bid = '$bid'
							and pi_from_period = '$pi_from_period' and pi_to_period = '$pi_to_period'";
		$delete_tca_sql = mysql_query($delete_tca_query);

		
		$tca_query 		= 	"INSERT INTO price_index set
									bid = '$bid',
									pi_from_period = '$pi_from_period',
									pi_to_period = '$pi_to_period',
									pi_rate1 = '$pi_rate1',
									pi_rate2 = '$pi_rate2',
									pi_rate3 = '$pi_rate3',
									avg_pi_code = '$avg_pi_code',
									avg_pi_rate = '$avg_pi_rate',
									type = 'TCC',
									sheetid = '$sheetid',
									active = '1',
									modifieddate  = NOW(),
									staffid = '$staffid'";
		$tca_sql = mysql_query($tca_query);
		//echo $tca_query."<br/>";
	}
	//exit;
    if($tca_sql == true) 
	{
        $msg = "Price Index for 10CC Stored Successfully ";
		$success = 1;
    }
	else
	{
		$msg = " Something Error...!!! ";
		//die(mysql_error());
	}
} 
?>

  <?php require_once "Header.html"; ?>
<style>
.ui-datepicker-calendar 
{
    display: none;
}   
</style>
 <script>
	var add_row_s 		= 5;
	var prev_edit_row 	= 0;
  	 function goBack()
	 {
	   	url = "dashboard.php";
		window.location.replace(url);
	 }
     function workorderdetail()
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
            strURL = "find_worder_details.php?workorderno=" + document.form.cmb_shortname.value;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText

                    if (data == "")
                    {
                        alert("No Records Found");
                    }
                    else
                    {
                        var name = data.split("*");
                        for(i = 0; i < name.length; i++)
                        {
                            document.form.txt_workname.value 	= name[3];
							document.form.txt_workorder.value 	= name[5];
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
	 function find_baseindex()
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
         strURL = "find_baseindex.php?workorderno=" + document.form.cmb_shortname.value + "&type=TCC";
         xmlHttp.open('POST', strURL, true);
         xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
         xmlHttp.onreadystatechange = function ()
         {
             if (xmlHttp.readyState == 4)
             {
                data = xmlHttp.responseText
//alert(data);
                if (data == "")
                {
                    alert("No Records Found");
                }
                else
                {
                   	var name = data.split("*@*");
                   	for(i = 0; i < name.length; i+=8)
                   	{
                       	var bid 				= name[i+0];
						var base_index_item 	= name[i+1];
						var base_index_code 	= name[i+2];
						var base_index_rate 	= name[i+3];
						var base_breakup_code 	= name[i+4];
						var base_breakup_perc 	= name[i+5];
						var base_price_code 	= name[i+6];
						var base_price_rate 	= name[i+7];
						var x = Number(document.getElementById("table1").rows.length);
						index = x;
						var table=document.getElementById("table1");
						var row=table.insertRow(table.rows.length);
						row.id = "rowid"+bid;
						row.style.backgroundColor  = "#EAEAEA";
						
						var cell1=row.insertCell(0);
						var txt_box1 = document.createElement("input");
							txt_box1.name = "txt_base_index_item[]";
							txt_box1.id = "txt_base_index_item"+bid;
							txt_box1.readOnly = true;
							txt_box1.value = base_index_item;
							txt_box1.setAttribute('class', "extraItemTextboxDisable"); 
							cell1.appendChild(txt_box1);
							txt_box1.onblur = (function (bid) {
								return function() {
								//calculateAmount(this,ind,"qty","slm")
									//ValidateSlm();
								};
										})(bid);
						var cell2=row.insertCell(1);
						var txt_box2 = document.createElement("input");
							txt_box2.name = "txt_base_index_code[]";
							txt_box2.id = "txt_base_index_code"+bid;
							txt_box2.readOnly = true;
							txt_box2.value = base_index_code;
							txt_box2.setAttribute('class', "extraItemTextboxDisable"); 
							cell2.appendChild(txt_box2);
							txt_box2.onblur = (function (bid) {
								return function() {
								//calculateAmount(this,ind,"qty","slm")
									//ValidateSlm();
								};
							})(bid);
						var cell3=row.insertCell(2);
						var txt_box3 = document.createElement("input");
							txt_box3.name = "txt_base_index_rate[]";
							txt_box3.id = "txt_base_index_rate"+bid;
							txt_box3.readOnly = true;
							txt_box3.value = base_index_rate;
							txt_box3.setAttribute('class', "extraItemTextboxDisable"); 
							cell3.appendChild(txt_box3);
							txt_box3.onblur = (function (bid) {
								return function() {
								//calculateAmount(this,ind,"qty","slm")
									//ValidateSlm();
								};
							})(bid);
						var cell4=row.insertCell(3);
						var txt_box4 = document.createElement("input");
							txt_box4.name = "txt_base_breakup_code[]";
							txt_box4.id = "txt_base_breakup_code"+bid;
							txt_box4.readOnly = true;
							txt_box4.value = base_breakup_code;
							txt_box4.setAttribute('class', "extraItemTextboxDisable"); 
							cell4.appendChild(txt_box4);
							txt_box4.onblur = (function (bid) {
								return function() {
								//calculateAmount(this,ind,"qty","slm")
									//ValidateSlm();
								};
							})(bid);
						var cell5=row.insertCell(4);
						var txt_box5 = document.createElement("input");
							txt_box5.name = "txt_base_breakup_perc[]";
							txt_box5.id = "txt_base_breakup_perc"+bid;
							txt_box5.readOnly = true;
							txt_box5.value = base_breakup_perc;
							txt_box5.setAttribute('class', "extraItemTextboxDisable"); 
							cell5.appendChild(txt_box5);
							txt_box5.onblur = (function (bid) {
								return function() {
								//calculateAmount(this,ind,"qty","slm")
									//ValidateSlm();
								};
							})(bid);
						var cell6=row.insertCell(5);
						var txt_box6 = document.createElement("input");
							txt_box6.name = "txt_price_index_rate_m1[]";
							txt_box6.id = "txt_price_index_rate_m1"+bid;
							txt_box6.value = "";
							txt_box6.setAttribute('class', "extraItemTextbox"); 
							cell6.appendChild(txt_box6);
							txt_box6.onblur = (function (bid) {
								return function() {
								CalculateAvgIndex(this,bid);
									//ValidateSlm();
								};
							})(bid);
						var cell7=row.insertCell(6);
						var txt_box7 = document.createElement("input");
							txt_box7.name = "txt_price_index_rate_m2[]";
							txt_box7.id = "txt_price_index_rate_m2"+bid;
							txt_box7.value = "";
							txt_box7.setAttribute('class', "extraItemTextbox"); 
							cell7.appendChild(txt_box7);
							txt_box7.onblur = (function (bid) {
								return function() {
								CalculateAvgIndex(this,bid);
									//ValidateSlm();
								};
							})(bid);
						var cell8=row.insertCell(7);
						var txt_box8 = document.createElement("input");
							txt_box8.name = "txt_price_index_rate_m3[]";
							txt_box8.id = "txt_price_index_rate_m3"+bid;
							txt_box8.value = "";
							txt_box8.setAttribute('class', "extraItemTextbox"); 
							cell8.appendChild(txt_box8);
							txt_box8.onblur = (function (bid) {
								return function() {
								CalculateAvgIndex(this,bid);
									//ValidateSlm();
								};
							})(bid);
						var cell9=row.insertCell(8);
						var txt_box9 = document.createElement("input");
							txt_box9.name = "txt_avg_price_index_code[]";
							txt_box9.id = "txt_avg_price_index_code"+bid;
							txt_box9.value = "";
							txt_box9.setAttribute('class', "extraItemTextbox"); 
							cell9.appendChild(txt_box9);
							txt_box9.onblur = (function (bid) {
								return function() {
								//calculateAmount(this,ind,"qty","slm")
									//ValidateSlm();
								};
							})(bid);
						var cell10=row.insertCell(9);
						var txt_box10 = document.createElement("input");
							txt_box10.name = "txt_avg_price_index_rate[]";
							txt_box10.id = "txt_avg_price_index_rate"+bid;
							txt_box10.readOnly = true;
							txt_box10.value = "";
							txt_box10.setAttribute('class', "extraItemTextboxDisable"); 
							cell10.appendChild(txt_box10);
							txt_box10.onblur = (function (bid) {
								return function() {
								//calculateAmount(this,ind,"qty","slm")
									//ValidateSlm();
								};
							})(bid);
						//var cell11=row.insertCell(10);
						var txt_box11 = document.createElement("input");
							txt_box11.type = "hidden";
							txt_box11.name = "txt_bid[]";
							txt_box11.id = "txt_bid"+bid;
							txt_box11.readOnly = true;
							txt_box11.value = bid;
							txt_box11.setAttribute('class', "extraItemTextboxDisable"); 
							cell10.appendChild(txt_box11);
							txt_box11.onblur = (function (bid) {
								return function() {
								//calculateAmount(this,ind,"qty","slm")
									//ValidateSlm();
								};
							})(bid);
						/*var cell6=row.insertCell(5);
							cell6.style.verticalAlign = "middle";
						var delbtn=document.createElement("input");
							delbtn.type = "button";
							delbtn.value = "DEL";
							delbtn.id = "btn_delete"+index;
							delbtn.name = "btn_delete";
							delbtn.setAttribute('class', "buttonstyle");
							delbtn.onclick = function () {
											  deleteRow(this);
											}
							cell6.appendChild(delbtn);*/
							index++;
							
                    }

                }
             }
          }
          xmlHttp.send(strURL);
       }
		function CalculateAvgIndex(obj,bid)
		{
			var id 		= obj.id;
			//var value 	= obj.value;
			var bid 	= bid;
			//alert(id);
			//alert(value);
			//alert(rowid);
			var price_index_1 = Number(document.getElementById("txt_price_index_rate_m1"+bid).value);
			var price_index_2 = Number(document.getElementById("txt_price_index_rate_m2"+bid).value);
			var price_index_3 = Number(document.getElementById("txt_price_index_rate_m3"+bid).value);
			var avg_price_index = (price_index_1+price_index_2+price_index_3)/3;
			document.getElementById("txt_avg_price_index_rate"+bid).value = avg_price_index.toFixed(2);
		}
		function check_price_index(month1,month3)
		{
			clear();
			var xmlHttp;
            var data;
            var i, j;
			var month1 = month1;
			var month3 = month3;
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "find_check_priceindex.php?sheetid=" + document.form.cmb_shortname.value+"&month1="+month1+"&month3="+month3+"&type=TCC";
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                { 
                    data = xmlHttp.responseText
					//alert(data)
                    if (data == "")
                    {
						swal("", "No Records Found", "");
                    }
					else if(data == 1)
					{
						swal("", "Already Data Exist for this period", "");
						find_priceindex(month1,month3);
					}
                    else
                    {
						find_priceindex(month1,month3);
                    }
                }
            }
            xmlHttp.send(strURL);
		}
	 function find_priceindex(month1,month3)
     { 
            var xmlHttp;
            var data;
            var i, j;
			var month1 = month1;
			var month3 = month3;
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "find_priceindex.php?sheetid=" + document.form.cmb_shortname.value+"&month1="+month1+"&month3="+month3+"&type=TCC";
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText
                    if (data != "")
                    {
                        var name = data.split("*@*");
                        for(i = 0; i < name.length; i+=9)
                        {
							var pid 			= name[i+0];
							var bid 			= name[i+1];
							var pi_from_period 	= name[i+2];
							var pi_to_period 	= name[i+3];
							var pi_rate1 		= name[i+4];
							var pi_rate2 		= name[i+5];
							var pi_rate3 		= name[i+6];
							var avg_pi_code 	= name[i+7];
                            var avg_pi_rate		= name[i+8];
							document.getElementById("txt_price_index_rate_m1"+bid).value  = pi_rate1;
							document.getElementById("txt_price_index_rate_m2"+bid).value  = pi_rate2;
							document.getElementById("txt_price_index_rate_m3"+bid).value  = pi_rate3;
							document.getElementById("txt_avg_price_index_code"+bid).value = avg_pi_code;
							document.getElementById("txt_avg_price_index_rate"+bid).value = avg_pi_rate;
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
     }
	 function clear()
	 {
	 	$('input[name="txt_bid[]"]').each(function() {
			var res1 = $(this).val();
			if(res1 != "")
			{
				//result1 = res1 + "@"+ result1;
				$('#txt_price_index_rate_m1'+res1).val('');
				$('#txt_price_index_rate_m2'+res1).val('');
				$('#txt_price_index_rate_m3'+res1).val('');
				$('#txt_avg_price_index_code'+res1).val('');
				$('#txt_avg_price_index_rate'+res1).val('');
			}
		});
	 }
</script>
<script>
   $(function () {
        $('.date-picker').datepicker( {
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'M-yy',
        onClose: function(dateText, inst) { 
            $('#txt_month1').datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
			$('#txt_month2').datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth+1, 1));
			$('#txt_month3').datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth+2, 1));
			var month1 =  $("#txt_month1").val();
			var month2 =  $("#txt_month2").val();
			var month3 =  $("#txt_month3").val();
			if((month1 != "") && (month2 != "") && (month3 != ""))
			{
				check_price_index(month1,month3);
			}
        }
    });
				function isNumberKey(evt, element) 
				{
					var charCode = (evt.which) ? evt.which : event.keyCode
					if (charCode > 31 && (charCode < 48 || charCode > 57) && !(charCode == 46 || charcode == 8))
						return false;
					else 
					{
						var len = $(element).val().length;
						var index = $(element).val().indexOf('.');
						if (index > 0 && charCode == 46) 
						{
						  return false;
						}
						if (index > 0) 
						{
						  var CharAfterdot = (len + 1) - index;
						  if (CharAfterdot > 3) 
						  {
							return false;
						  }
						}
					
					}
					return true;
				}
		/*$( "#txt_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd-mm-yy",
                    maxDate: new Date,
                    defaultDate: new Date,
                });*/	
        $.fn.validateshortname = function(event) { 
					if($("#cmb_shortname").val()==""){ 
					var a="Please Select Work Short Name";
					$('#val_shortname').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_shortname').text(a);
					}
				}
		$.fn.validateworkname = function(event) { 
					if($("#txt_workname").val()==""){ 
					var a="Please Enter Work Name";
					$('#val_wname').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_wname').text(a);
					}
				}
		$.fn.validateworkorder = function(event) { 
					if($("#txt_workorder").val()==""){ 
					var a="Please Enter Work Order Number";
					$('#val_workorder').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_workorder').text(a);
					}
				}
		$.fn.validatemeterno = function(event) { 
					if($("#txt_meter_no").val()==""){ 
					var a="Please Enter Meter No.";
					//$('#val_meterno').text(a);
					swal(a, "", "");
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					//$('#val_meterno').text(a);
					}
				}
		$.fn.validateimr = function(event) { 
					if($("#txt_imr").val()==""){ 
					var a="Please Enter Initial Reading";
					swal(a, "", "");
					//$('#val_initial').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					//$('#val_initial').text(a);
					}
				}
		$.fn.validateimrdate = function(event) { 
					if($("#txt_imr_date").val()==""){ 
					var a="Please Select Initial Reading Date";
					swal(a, "", "");
					//$('#val_initialdate').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					//$('#val_initialdate').text(a);
					}
				}
		$.fn.validaterate = function(event) { 
					if($("#txt_rate_unit").val()==""){ 
					var a="Please Enter Rate";
					swal(a, "", "");
					//$('#val_rate').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					//$('#val_rate').text(a);
					}
				}			
		$.fn.vaidaterent = function(event) { 
					if($("#txt_rent").val()==""){ 
					var a="Please Select Meter Rent";
					swal(a, "", "");
					//$('#val_date').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					//$('#val_date').text(a);
					}
				}
		$.fn.vaidatefactor = function(event) { 
					if($("#txt_factor").val()==""){ 
					var a="Please Enter Factor.";
					swal(a, "", "");
					//$('#val_date').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					//$('#val_date').text(a);
					}
				}				
				
		$("#cmb_shortname").change(function(event){
		$(this).validateshortname(event);
		});
		$("#txt_workname").keyup(function(event){
		$(this).validateworkname(event);
		});
		$("#txt_workorder").keyup(function(event){
		$(this).validateworkorder(event);
		});
		$("#txt_meter_no").keyup(function(event){
		$(this).validatemeterno(event);
		});
		$("#txt_imr").keyup(function(event){
		$(this).validateimr(event);
		});
		$("#txt_imr_date").change(function(event){
		$(this).validateimrdate(event);
		});
		$("#txt_rate_unit").keyup(function(event){
		$(this).validaterate(event);
		});
		$("#txt_rent").keyup(function(event){
		$(this).vaidaterent(event);
		});
		$("#txt_factor").keyup(function(event){
		$(this).vaidatefactor(event);
		});
		$("#top").submit(function(event){
		$(this).validateshortname(event);
		$(this).validateworkname(event);
		$(this).validateworkorder(event);
		//$(this).validatemeterno(event);
		//$(this).validateimr(event);
		//$(this).validateimrdate(event);
		//$(this).validaterate(event);
		//$(this).vaidaterent(event);
		//$(this).vaidatefactor(event);
		});
   
      });
	   function goBack()
	   {
	   		url = "dashboard.php";
			window.location.replace(url);
	   }
</script>
<script type="text/javascript">
			window.history.forward();
			function noBack() { window.history.forward(); }
</script>
<style>
input[type="date"]:before {
    content: attr(placeholder) !important;
    color: #aaa;
    margin-right: 0.5em;
  }
  input[type="date"]:focus:before,
  input[type="date"]:valid:before {
    content: "";
  }
.extraItemTextbox {
    height: 30px;
    position: relative;
    outline: none;
    border: 1px solid #98D8FE;
   /* border-color: rgba(0,0,0,.15);*/
    background-color: white;
	color:#0000cc;
	width:98%;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	text-align:center;
}
.extraItemTextArea
{
    position: relative;
    outline: none;
    /*border: 1px solid #cdcdcd;*/
	border: 1px solid #98D8FE;
    /*border-color: rgba(0,0,0,.15);*/
    background-color: white;
	color:#0000cc;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:12px;
}
.extraItemTextboxDisable {
    height: 30px;
    position: relative;
    outline: none;
   /* border: 1px solid #EAEAEA;*/
   border:none;
    background-color: #EAEAEA;
	color:#0000cc;
	width:98%;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	text-align:center;
	vertical-align:middle;
	cursor:default;
}
.gradientbg {
  /* fallback */
  background-color: #014D62;
  width:90%; height:25px; color:#FFFFFF; vertical-align:middle;
  background: url(images/linear_bg_2.png);
  background-repeat: repeat-x;

  /* Safari 4-5, Chrome 1-9 */
  background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#037595), to(#0A9CC5));

  /* Safari 5.1, Chrome 10+ */
  background: -webkit-linear-gradient(top, #0A9CC5, #037595);

  /* Firefox 3.6+ */
  background: -moz-linear-gradient(top, #0A9CC5, #037595);

  /* IE 10 */
  background: -ms-linear-gradient(top, #0A9CC5, #037595);

  /* Opera 11.10+ */
  background: -o-linear-gradient(top, #0A9CC5, #037595);
}
.buttonstyle
{
	background-color:#0A9CC5;
	/*width:80px;*/
	height:25px;
	color:#FFFFFF;
	-moz-box-shadow: 0px 1px 0px 0px #0A9CC5;
	-webkit-box-shadow: 0px 1px 0px 0px #0A9CC5;
	box-shadow: 0px 1px 0px 0px #0A9CC5;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #0080FF), color-stop(1, #0A9CC5));
	background:-moz-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:-webkit-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:-o-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:-ms-linear-gradient(top, #0080FF 5%, #0A9CC5 100%);
	background:linear-gradient(to bottom, #0080FF 5%, #0A9CC5 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#0080FF', endColorstr='#0A9CC5',GradientType=0);
	border:1px solid #0080FF;
	display:inline-block;
	cursor:pointer;
	font-weight:bold;

}
.buttonstyle:hover
{
	/*font-size:14px;*/
	/*padding: 0.1em 1em;*/
	-moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    -webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    box-shadow:0px 1px 4px rgba(0,0,0,5);
	background:#E80017;
	border:1px solid #E80017;
}
.buttonstyledisable
{
	background-color:#CECECE;
	color:#A0A0A0;
	background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #E6E6E6), color-stop(1, #CECECE));
	background:-moz-linear-gradient(top, #E6E6E6 5%, #CECECE 100%);
	background:-webkit-linear-gradient(top, #E6E6E6 5%, #CECECE 100%);
	background:-o-linear-gradient(top, #E6E6E6 5%, #CECECE 100%);
	background:-ms-linear-gradient(top, #E6E6E6 5%, #CECECE 100%);
	background:linear-gradient(to bottom, #E6E6E6 5%, #CECECE 100%);
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#E6E6E6', endColorstr='#CECECE',GradientType=0);
	border:1px solid #CECECE;
}
.buttonstyledisable:hover
{
	/*font-size:14px;*/
	/*padding: 0.1em 1em;*/
	-moz-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    -webkit-box-shadow: 0px 1px 4px rgba(0,0,0,5);
    box-shadow:0px 1px 4px rgba(0,0,0,5);
	background:#E6E6E6;
	border:1px solid #E6E6E6;
}
sub {font-size:xx-small; vertical-align:sub;}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">

                <div class="container_12">
                    <div class="grid_12">

						<!--<div align="right"><a href="AgreementEntryView.php">View</a>&nbsp;&nbsp;&nbsp;</div>-->
                        <blockquote class="bq1">
                            <div class="title">Price Index - 10CC</div>
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
                        <table width="1078px" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="18%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Short Name</td> 
                                    <td>
										<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" style="width:465px" onChange="workorderdetail();find_baseindex();">
											<option value="">----------------------- Select Work Short Name ------------------------</option>
											<?php echo $objBind->BindWorkOrderNo(0);?>
										</select>
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_shortname" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Name of Work</td>
                                    <td><textarea name='txt_workname' id='txt_workname' class="textboxdisplay" rows="6" style="width: 465px;"><?php if($_GET['sheet_id'] != ''){ echo $work_name; } ?></textarea></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_wname" style="color:red" colspan="">&nbsp;</td></tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order No.</td>
                                    <td><input type="text" name='txt_workorder' id='txt_workorder' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_workorder" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
									<td colspan="3" align="center">
										<div style="width:85%;" class="label gradientbg" align="center">Price Index</div>
										<div style="width:85%; height:auto;" align="center">
											<table width="100%" class="table1" id="table1">
												<tr class="label" style="background-color:#EAEAEA; height:35px;">
													<td align="center" rowspan="2" valign="middle" nowrap="nowrap">&nbsp;Description&nbsp;</td>
													<td align="center" colspan="2" valign="middle" nowrap="nowrap">Base Index</td>
													<td align="center" colspan="2" valign="middle">Escalation Breakup</td>
													<td align="center" valign="middle">
														Month - 1
													</td>
													<td align="center" valign="middle">
														Month - 2
													</td>
													<td align="center" valign="middle">
														Month - 3
													</td>
													<td align="center" colspan="2" valign="middle" nowrap="nowrap">
														Average Index
													</td>
												</tr>
												<tr class="label" style="background-color:#EAEAEA; height:35px;">
													<td align="center" valign="middle" nowrap="nowrap">
														Code
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														Rate <i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></i>
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														Code
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														( % )
													</td>
													<td align="center" valign="middle">
														<input type="text" class="extraItemTextbox date-picker" name="txt_month1" id="txt_month1">
													</td>
													<td align="center" valign="middle">
														<input type="text" class="extraItemTextbox date-picker" readonly="" name="txt_month2" id="txt_month2">
													</td>
													<td align="center" valign="middle">
														<input type="text" class="extraItemTextbox date-picker" readonly="" name="txt_month3" id="txt_month3">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														Code
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														Rate <i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></i>
													</td>
												</tr>
												<!--<tr class="labeldisplay" style="background-color:#EAEAEA">
													<td align="left" valign="middle" nowrap="nowrap">
														&nbsp;Cement
														<input type="hidden" name="txt_cement_desc" id="txt_cement_desc" value="Material (MIo)">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextboxDisable" name="txt_material_desc" id="txt_material_desc" value="( CIo )" readonly="">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_material_baseindex" id="txt_material_baseindex" value="172.00">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextboxDisable" name="txt_material_desc" id="txt_material_desc" value="( Pc )" readonly="">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_material_baseindex" id="txt_material_baseindex" value="0">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_cement_baseindex" id="txt_cement_baseindex">
													</td>
													<td align="center" valign="middle">
														<input type="text" class="extraItemTextbox" name="txt_material_desc" id="txt_material_desc">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_cement_escal_perc" id="txt_cement_escal_perc">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextboxDisable" name="txt_material_desc" id="txt_material_desc" value="( CI )" readonly="">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_material_escal_perc" id="txt_material_escal_perc">
													</td>
												</tr>
												<tr class="labeldisplay" style="background-color:#EAEAEA">
													<td align="left" valign="middle" nowrap="nowrap">
														&nbsp;Steel
														<input type="hidden" name="txt_steel_desc" id="txt_steel_desc" value="Material (MIo)">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextboxDisable" name="txt_material_desc" id="txt_material_desc" value="( SIo )" readonly="">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_material_baseindex" id="txt_material_baseindex" value="162.00">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextboxDisable" name="txt_material_desc" id="txt_material_desc" value="( Ps )" readonly="">
													</td>
													<td align="center" width="200px">
														<input type="text" class="extraItemTextbox" name="txt_material_baseindex" id="txt_material_baseindex" value="0">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_steel_baseindex" id="txt_steel_baseindex">
													</td>
													<td align="center" valign="middle">
														<input type="text" class="extraItemTextbox" name="txt_material_desc" id="txt_material_desc">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_steel_escal_perc" id="txt_steel_escal_perc">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextboxDisable" name="txt_material_desc" id="txt_material_desc" value="( SI )" readonly="">
													</td>
													<td align="center">
														<input type="text" class="extraItemTextbox" name="txt_material_escal_perc" id="txt_material_escal_perc">
													</td>
												</tr>-->
											</table>
                                             <input type="hidden" value="" name="add_set_a1" id="add_set_a1"/>
										</div>
										
									</td>
								</tr>
							</table>
									<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
										<div class="buttonsection">
										<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
										</div>
										<div class="buttonsection">
										<?php 
										if($_GET['sheet_id'] != '')
										{ 
										?>
											<input type="submit" name="update" id="update" value=" Update "/>
										<?php
										}
										else
										{
										?>
											<input type="submit" name="submit" id="submit" value=" Submit "/>
										<?php
										}
										?>
										</div>
									</div>
                        </blockquote>
                    </div>

                </div>
            </div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
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
    </body>
</html>
