<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'library/common.php';
checkUser();
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
{//exit;
   	$sheetid 				= 	trim($_POST['cmb_shortname']);
	$x = array();
	$base_index_item_arr		= 	$_POST['txt_base_index_item'];
	$base_index_code_arr 		= 	$_POST['txt_base_index_code'];
	$base_index_rate_arr 		= 	$_POST['txt_base_index_rate'];
	$base_price_code_arr 		= 	$_POST['txt_base_price_code'];
	$base_price_rate_arr 		= 	$_POST['txt_base_price_rate'];
	$count = count($base_index_item_arr);
	//echo $count;exit;
	//print_r($base_index_item_arr);exit;
	$delete_tca_query = "update base_index set active = '0', modifieddate  = NOW() where sheetid = '$sheetid' and type='TCA'";
	$delete_tca_sql = mysql_query($delete_tca_query);
	for ($c = 0; $c<$count; $c++) 
	{
		$base_index_item = $base_index_item_arr[$c];
		$base_index_code = $base_index_code_arr[$c];
		$base_index_rate = $base_index_rate_arr[$c];
		$base_price_code = $base_price_code_arr[$c];
		$base_price_rate = $base_price_rate_arr[$c];
		$tca_query 	= 	"INSERT INTO base_index set
									base_index_item = '$base_index_item',
									base_index_code = '$base_index_code',
									base_index_rate = '$base_index_rate',
									base_price_code = '$base_price_code',
									base_price_rate = '$base_price_rate',
									type = 'TCA',
									active = '1',
									sheetid = '$sheetid',
									modifieddate  = NOW(),
									staffid = '$staffid'";
		$tca_sql = mysql_query($tca_query);
		//echo $tca_query."<br/>";
	}
	//exit;
    if($tca_sql == true) 
	{
        $msg = "Base Index added sucessfully";
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
    
</style>
 <script>
	var add_row_s 		= 3;
	var prev_edit_row 	= 0;
	var ItemArr = ["Cement(RMC)","Cement (Site Consumption)","Steel","Steel (Site Consumption)"];
	var CodeArr1 = ["CIo","CsIo","SIo","SsIo"];
	var CodeArr2 = ["Pc","Psc","Ps","Pss"];
	var CodeArr3 = ["RMC","STE","STL","SSTE"];
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
                        //alert("No Records Found");
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
         strURL = "find_baseindex.php?workorderno=" + document.form.cmb_shortname.value + "&type=TCA";
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
                    //alert("No Records Found");
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
						row.id = "rowid"+index;
						row.style.backgroundColor  = "#EAEAEA";
						
						var cell1=row.insertCell(0);
						var txt_box1 = document.createElement("select");
							txt_box1.name = "txt_base_index_item[]";
							txt_box1.id = "txt_base_index_item"+index;
							txt_box1.setAttribute('class', "extraItemTextbox"); 
							var option = document.createElement("option");
								option.value = "";
								option.text  = "--- Select ---";
							txt_box1.appendChild(option);
							for(var k1 = 0; k1 < ItemArr.length; k1++){
								var option = document.createElement("option");
									option.value = ItemArr[k1];
									option.text  = ItemArr[k1];
								txt_box1.appendChild(option);
							}
							txt_box1.value = base_index_item;
							cell1.appendChild(txt_box1);
							txt_box1.onchange = (function (ind) {
								return function() {
								AssignCode(this,ind);
								//calculateAmount(this,ind,"qty","slm")
									//ValidateSlm();
								};
										})(index);
						var cell2=row.insertCell(1);
						var txt_box2 = document.createElement("input");
							txt_box2.name = "txt_base_index_code[]";
							txt_box2.id = "txt_base_index_code"+index;
							txt_box2.setAttribute('class', "extraItemTextbox"); 
							txt_box2.value = base_index_code;
							cell2.appendChild(txt_box2);
							txt_box2.onblur = (function (ind) {
								return function() {
								//calculateAmount(this,ind,"qty","slm")
									//ValidateSlm();
								};
							})(index);
						var cell3=row.insertCell(2);
						var txt_box3 = document.createElement("input");
							txt_box3.name = "txt_base_index_rate[]";
							txt_box3.id = "txt_base_index_rate"+index;
							txt_box3.value = base_index_rate;
							txt_box3.setAttribute('class', "extraItemTextbox"); 
							cell3.appendChild(txt_box3);
							txt_box3.onblur = (function (ind) {
								return function() {
								//calculateAmount(this,ind,"qty","slm")
									//ValidateSlm();
								};
							})(index);
						var cell4=row.insertCell(3);
						var txt_box4 = document.createElement("input");
							txt_box4.name = "txt_base_price_code[]";
							txt_box4.id = "txt_base_price_code"+index;
							txt_box4.value = base_price_code;
							txt_box4.setAttribute('class', "extraItemTextbox"); 
							cell4.appendChild(txt_box4);
							txt_box4.onblur = (function (ind) {
								return function() {
								//calculateAmount(this,ind,"qty","slm")
									//ValidateSlm();
								};
							})(index);
						var cell5=row.insertCell(4);
						var txt_box5 = document.createElement("input");
							txt_box5.name = "txt_base_price_rate[]";
							txt_box5.id = "txt_base_price_rate"+index;
							txt_box5.value = base_price_rate;
							txt_box5.setAttribute('class', "extraItemTextbox"); 
							cell5.appendChild(txt_box5);
							txt_box5.onblur = (function (ind) {
								return function() {
								//calculateAmount(this,ind,"qty","slm")
									//ValidateSlm();
								};
							})(index);
						var cell6=row.insertCell(5);
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
							cell6.appendChild(delbtn);
							index++;
							
                    }

                }
             }
          }
          xmlHttp.send(strURL);
       }

		function addrow()
		{
			var x = Number(document.getElementById("table1").rows.length);
			index = x;
			var table=document.getElementById("table1");
			var row=table.insertRow(table.rows.length);
			row.id = "rowid"+index;
			row.style.backgroundColor  = "#EAEAEA";
			
			var cell1=row.insertCell(0);
			var txt_box1 = document.createElement("select");
				txt_box1.name = "txt_base_index_item[]";
				txt_box1.id = "txt_base_index_item"+index;
				txt_box1.setAttribute('class', "extraItemTextbox"); 
				var option = document.createElement("option");
					option.value = "";
					option.text  = "--- Select ---";
				txt_box1.appendChild(option);
				for(var i = 0; i < ItemArr.length; i++){
					var option = document.createElement("option");
						option.value = ItemArr[i];
						option.text  = ItemArr[i];
					txt_box1.appendChild(option);
				}
				
				cell1.appendChild(txt_box1);
				txt_box1.onchange  = (function (ind) {
					return function() {
					AssignCode(this,ind);
					//calculateAmount(this,ind,"qty","slm")
						//ValidateSlm();
					};
				})(index);
			var cell2=row.insertCell(1);
			
			var txt_box2 = document.createElement("input");
				txt_box2.name = "txt_base_index_code[]";
				txt_box2.id = "txt_base_index_code"+index;
				txt_box2.setAttribute('class', "extraItemTextbox");
				/*var option = document.createElement("option");
					option.value = "";
					option.text  = "--- Select ---";
				txt_box2.appendChild(option);
				for(var i = 0; i < CodeArr1.length; i++){
					var option = document.createElement("option");
						option.value = CodeArr1[i];
						option.text  = CodeArr1[i];
					txt_box2.appendChild(option);
				}*/
				
				cell2.appendChild(txt_box2);
				txt_box2.onblur = (function (ind) {
					return function() {
					//calculateAmount(this,ind,"qty","slm")
						//ValidateSlm();
					};
				})(index);
			
			/*var txt_box2 = document.createElement("input");
				txt_box2.name = "txt_base_index_code[]";
				txt_box2.id = "txt_base_index_code"+index;
				txt_box2.setAttribute('class', "extraItemTextbox"); 
				cell2.appendChild(txt_box2);
				txt_box2.onblur = (function (ind) {
					return function() {
					};
				})(index);*/
				
				
			var cell3=row.insertCell(2);
			var txt_box3 = document.createElement("input");
				txt_box3.name = "txt_base_index_rate[]";
				txt_box3.id = "txt_base_index_rate"+index;
				txt_box3.setAttribute('class', "extraItemTextbox"); 
				cell3.appendChild(txt_box3);
				txt_box3.onblur = (function (ind) {
					return function() {
					//calculateAmount(this,ind,"qty","slm")
						//ValidateSlm();
					};
				})(index);
			var cell4=row.insertCell(3);
			var txt_box4 = document.createElement("input");
				txt_box4.name = "txt_base_price_code[]";
				txt_box4.id = "txt_base_price_code"+index;
				txt_box4.setAttribute('class', "extraItemTextbox");
				/*var option = document.createElement("option");
					option.value = "";
					option.text  = "--- Select ---";
				txt_box4.appendChild(option);
				for(var i = 0; i < CodeArr2.length; i++){
					var option = document.createElement("option");
						option.value = CodeArr2[i];
						option.text  = CodeArr2[i];
					txt_box4.appendChild(option);
				}*/
				cell4.appendChild(txt_box4);
				txt_box4.onblur = (function (ind) {
					return function() {
					//calculateAmount(this,ind,"qty","slm")
						//ValidateSlm();
					};
				})(index);
			var cell5=row.insertCell(4);
			var txt_box5 = document.createElement("input");
				txt_box5.name = "txt_base_price_rate[]";
				txt_box5.id = "txt_base_price_rate"+index;
				txt_box5.setAttribute('class', "extraItemTextbox"); 
				cell5.appendChild(txt_box5);
				txt_box5.onblur = (function (ind) {
					return function() {
					//calculateAmount(this,ind,"qty","slm")
						//ValidateSlm();
					};
				})(index);
			var cell6=row.insertCell(5);
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
				cell6.appendChild(delbtn);
				index++;
		}
		function deleteRow(r) 
		{
			var i = r.parentNode.parentNode.rowIndex;
			document.getElementById("table1").deleteRow(i);
		}
		function AssignCode(obj,ind){
			var selectedItem = obj.value;
			var count = 0;
			var AllItem = document.getElementsByName("txt_base_index_item[]");
			for (var j = 0, maxi = AllItem.length; j < maxi; j++) {
				if(selectedItem != ""){
					if(AllItem[j].value == selectedItem) {
						count++;
					}
				}
			}
			document.getElementById("txt_base_index_code"+ind).value = "";
			document.getElementById("txt_base_price_code"+ind).value = "";
			if(count>1){
				swal("", "Already You heave selected this Description", "");//alert("Already You heave selected this Description");
				obj.value = '';
				return false;
				exit();
			}
			for(var i=0; i<ItemArr.length; i++){
				var itemDesc = ItemArr[i];
				if(itemDesc == selectedItem){
					document.getElementById("txt_base_index_code"+ind).value = CodeArr1[i];
					document.getElementById("txt_base_price_code"+ind).value = CodeArr2[i];
					getIndex(ind,CodeArr1[i],CodeArr3[i]);
				}
			}
		}
		function getIndex(ind,IndexCode,MatCode){
			var xmlHttp;
            var data;
            var i, j;
			document.getElementById("txt_base_index_rate"+ind).value = '';
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "find_material_index.php?workorderno=" + document.form.cmb_shortname.value+"&IndexCode="+IndexCode+"&MatCode="+MatCode+"&MatCata=10CA";
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText
                    if(data != "")
                    { 
						document.getElementById("txt_base_index_rate"+ind).value = data;
                    }
                }
            }
            xmlHttp.send(strURL);
			
		}
</script>
<script>
   $(function () {
        $( "#txt_imr_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd-mm-yy",
                    maxDate: new Date,
                    defaultDate: new Date,
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
		$.fn.vaidateTCA = function(event) { 
					$("#table1 :input").each(function () {
					if (!$(this).val()) 
					{             
						var a="Please Enter All required Field";
						//$(this).css("background-color", "#F7BE81");
						$(this).css("border", "#ff704d solid 1px");
						swal(a, "", "");
						event.preventDefault();
						event.returnValue = false;
					}
					else
					{
						var a = "";
						$(this).css("border", "#98d8fe solid 1px");
						//$(this).css("background-color", "#98d8fe");
					}
					});
				}
		$.fn.CheckBaseIndex = function(event) {
			var status = CheckEscalationStatus();
			/*if(status == 1)
			{
				swal("", "Already Escalation Sent for this Work. Unable to Edit.", "");
				event.preventDefault();
				event.returnValue = false;
			}
			else */if(status == 2)
			{
				swal("Already Escalation Generated for this Work. To Edit Reset the Escalation.", "", "");
				event.preventDefault();
				event.returnValue = false;
			}
			else
			{
				return true;
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
		$(this).vaidateTCA(event);
		$(this).CheckBaseIndex(event);
		//$(this).validatemeterno(event);
		//$(this).validateimr(event);
		//$(this).validateimrdate(event);
		//$(this).validaterate(event);
		//$(this).vaidaterent(event);
		//$(this).vaidatefactor(event);
		});
		
		function CheckEscalationStatus() 
		{
            var count;
			var sheetid = 'sheetid='+2+"&type=TCA";
            $.ajax({
                type: "POST",
                url: "check_baseindex.php",
                //dataType: "json",
				data: 'sheetid=2&type=TCA',
                async:false,
                //contentType: "application/json; charset=utf-8",
                success: function (data) {                            
                    count = data;//.d;
                } //success
            });
            return count;
        }
   
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
                <div class="title">Base Index - 10CA</div>
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1" style="overflow:auto;">
							<div align="right"><a href="BaseIndexView_10CA.php">View</a>&nbsp;&nbsp;&nbsp;</div>
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
									<tr><td width="22%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
									<tr>
										<td>&nbsp;</td>
										<td class="label">Work Short Name</td> 
										<td>
											<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" onChange="workorderdetail();find_baseindex();">
												<option value="">--------------- Select ---------------</option>
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
											<div style="width:70%;" class="label gradientbg" align="center">Base Index</div>
											<div style="width:70%; height:auto;" align="center">
												<table width="100%" class="table1" id="table1">
													<tr class="label" style="background-color:#EAEAEA; height:35px;">
														<td align="center" rowspan="2" valign="middle" nowrap="nowrap">
															&nbsp;Description
														</td>
														<td align="center" valign="middle" colspan="2">
															Base Index <!--<i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></i>-->
														</td>
														<td align="center" valign="middle" colspan="2">
															Base Price <!--<i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></i>-->
														</td>
														<td rowspan="2" align="center" valign="middle">
															<input type="button" class="buttonstyle" name="btn_add" id="btn_add" value="ADD" onClick="addrow()">
														</td>
													</tr>
													<tr class="label" style="background-color:#EAEAEA; height:35px;">
														<td align="center" valign="middle" nowrap="nowrap">
															Code
														</td>
														<td align="center" valign="middle" nowrap="nowrap">
															Index <!--<i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></i>-->
														</td>
														<td align="center" valign="middle">
															Code
														</td>
														<td align="center" valign="middle">
															Rate <i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></i>
														</td>
													</tr>
													<!--<tr class="labeldisplay" style="background-color:#EAEAEA">
														<td align="left" valign="middle" nowrap="nowrap">
														&nbsp;Cement 
														<input type="hidden" name="base_index_item" id="base_index_item1" value="Cement">
														</td>
														<td align="center" valign="middle">
															&emsp;CI<sub>O</sub>&emsp;
															<input type="hidden" name="base_index_code" id="base_index_code1" value="MIo">
														</td>
														<td align="center">
															<input type="text" class="extraItemTextbox" name="base_index_rate" id="base_index_rate1">
														</td>
														<td align="center" valign="middle">
															&emsp;P<sub>C</sub>&emsp;
															<input type="hidden" name="base_price_code" id="base_price_code1" value="Pc">
														</td>
														<td align="center">
															<input type="text" class="extraItemTextbox" name="base_price_rate" id="base_price_rate1">
														</td>
														<td align="center" valign="middle">&nbsp;</td>
													</tr>
													<tr class="labeldisplay" style="background-color:#EAEAEA">
														<td align="left" valign="middle" nowrap="nowrap">
														&nbsp;Steel 
														<input type="hidden" name="base_index_item" id="base_index_item2" value="Steel">
														</td>
														<td align="center" valign="middle">
															&emsp;SI<sub>O</sub>&emsp;
															<input type="hidden" name="base_index_code" id="base_index_code2" value="SIo">
														</td>
														<td align="center">
															<input type="text" class="extraItemTextbox" name="base_index_rate" id="base_index_rate2">
														</td>
														<td align="center" valign="middle">
															&emsp;P<sub>S</sub>&emsp;
															<input type="hidden" name="base_price_code" id="base_price_code2" value="Ps">
														</td>
														<td align="center">
															<input type="text" class="extraItemTextbox" name="base_price_rate" id="base_price_rate2">
														</td>
														<td align="center" valign="middle">&nbsp;</td>
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
		   		$("#cmb_shortname").chosen();
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
