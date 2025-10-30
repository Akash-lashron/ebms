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
   	$sheetid 	= 	trim($_POST['cmb_shortname']);
	$w_limit	=	trim($_POST['txt_w_limit']);
	//$meter_no 		= 	trim($_POST['txt_meterno']);
    //$imr 			= 	trim($_POST['txt_initial']);
    //$imr_date 		= 	dt_format(trim($_POST['txt_initial_date']));
    //$rate 			= 	trim($_POST['txt_rate']);
	//$meter_rent 	= 	trim($_POST['txt_meter_rent']);
    //$limit 			= 	trim($_POST['txt_limit']);
    //$er_date 		= 	dt_format(trim($_POST['txt_date']));
    /*$erecovery_sql 	= 	"INSERT INTO electricity_recovery set
                                            sheetid = '$sheetid',
                                            meter_no = '$meter_no',
											imr = '$imr',
                                            imr_date = '$imr_date',
                                            rate = '$rate',
											meter_rent = '$meter_rent',
                                            e_limit = '$limit',
                                            er_date = '$er_date',
											staffid = '$staffid',
                                            userid = '$userid',
											modifieddate = NOW(),
											active = 1";*/
											//modifieddate = NOW()";
    //$erecovery_query 	= 	mysql_query($erecovery_sql);
	//echo $erecovery_sql;
	$rec = explode(".", $_POST['add_set_a1']);
	//echo $_POST['add_set_a1'];exit;
	for ($c = 0; $c < count($rec); $c++) 
	{
		$x = $rec[$c];
		if($x != "")
		{
			$meter_no	=	chop($_POST['txt_meter_no'.$x]);
			$imr		=	chop($_POST['txt_imr'.$x]);
			$imr_date	=	dt_format(chop($_POST['txt_imr_date'.$x]));
			$rate		=	chop($_POST['txt_rate_unit'.$x]);
			$meter_rent	=	chop($_POST['txt_rent'.$x]);
			$factor		=	chop($_POST['txt_factor'.$x]);
			$limit		=	"";
			if($meter_no != "")
			{
				$erecovery_sql 	= 	"INSERT INTO water_recovery set
													sheetid = '$sheetid',
													meter_no = '$meter_no',
													imr = '$imr',
													imr_date = '$imr_date',
													rate = '$rate',
													meter_rent = '$meter_rent',
													factor = '$factor',
													w_limit = '$limit',
													staffid = '$staffid',
													userid = '$userid',
													modifieddate = NOW(),
													active = 1";
				$erecovery_query 	= 	mysql_query($erecovery_sql);
			}
		}
	}
    if($erecovery_query == true) 
	{
        $msg = "Electricity Charge Details Stored Successfully ";
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
	var add_row_s 		= 2;
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
		function recoverydetail()
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
            strURL = "find_recovery_details.php?workorderno=" + document.form.cmb_shortname.value;
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
                        alert("No Records Found");
                    }
                    else
                    {
                        var name = data.split("*");
                        for(i = 0; i < name.length; i++)
                        {
                            document.form.txt_rate_unit.value 	= name[7];
							document.form.txt_w_limit.value 	= name[8];
							document.getElementById("water_limit").innerHTML = " / "+name[8]+ " Liters";
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
		function RemoveRow()
		{
			 $("#table1").find("tr:not(:nth-child(1)):not(:nth-child(2))").remove();
			 add_row_s 		= 2;
		}
		function WaterMeterDetail()
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
            strURL = "find_water_meter.php?workorderno=" + document.form.cmb_shortname.value+"&temp=2";
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText
					//alert(data)
					//alert(document.getElementById("add_hidden").innerHTML)
					//document.getElementById("add_set_a1").value = "";
					//var noofadd = (add_row_s - 2)
					//document.getElementById('table1').deleteRow(noofadd)
					//var NoRows = document.getElementById('table1').getElementsByTagName('tr');
					//alert(NoRows.length);
					RemoveRow();
					document.getElementById("add_hidden").innerHTML = "";
					document.getElementById("add_set_a1").value  = "";
					
                    if (data == "")
                    {
                        alert("No Records Found");
                    }
                    else
                    {
                        var name = data.split("*");
                        for(i = 0; i < name.length; i+=6)
                        {
                            var new_row = document.getElementById("table1").insertRow(add_row_s);
							new_row.setAttribute("id", "row_" + add_row_s)
							new_row.className = "labelsmall";
							new_row.style.backgroundColor  = "#EAEAEA";
							var c1 = new_row.insertCell(0);
								c1.align = "center";
								c1.style.className = "extraItemTextbox";
							var c2 = new_row.insertCell(1);
								c2.align = "center";
								c2.style.className = "extraItemTextbox"; 
							var c3 = new_row.insertCell(2);
								c3.align = "center";
								c3.style.className = "extraItemTextbox";
							var c4 = new_row.insertCell(3);
								c4.align = "center";
								c4.style.className = "extraItemTextbox"; 
							var c5 = new_row.insertCell(4);
								c5.align = "center";
								c5.style.className = "extraItemTextbox"; 
							var c6 = new_row.insertCell(5);
								c6.align = "center";
								c6.style.className = "extraItemTextbox";
							var c7 = new_row.insertCell(6);
								c7.align = "center";
								c7.style.className = "extraItemTextbox";
							var c8 = new_row.insertCell(7);
								c8.align = "center";
								c8.style.className = "extraItemTextbox";
							c1.innerHTML = name[i];//document.form.txt_meter_no.value;
							c2.innerHTML = name[i+1];//document.form.txt_imr.value;
							c3.innerHTML = name[i+2];//document.form.txt_imr_date.value;
							c4.innerHTML = name[i+3];//document.form.txt_rate_unit.value;
							c5.innerHTML = name[i+4];//document.form.txt_rent.value;
							c6.innerHTML = name[i+5];;//document.form.txt_factor.value;
							c7.innerHTML = "<input type='button' class='buttonstyledisable' disabled='disabled' name='btn_edit_" + add_row_s + "' style='height:25px;' id='btn_edit_" + add_row_s + "'  value=' EDIT ' onClick=editrow(" + add_row_s + ",'n')>";
							c8.innerHTML = "<input type='button' class='buttonstyledisable' disabled='disabled'  name='btn_del_" + add_row_s + "' style='height:25px;'  id='btn_del_" + add_row_s + "' value=' DEL ' onClick=delrow(" + add_row_s + ")>";
							var hide_values = "";
							hide_values = "<input type='hidden' value='" + c1.innerHTML + "' name='txt_meter_no" + add_row_s + "' id='txt_meter_no" + add_row_s + "' >";
							hide_values += "<input type='hidden' value='" + c2.innerHTML + "' name='txt_imr" + add_row_s + "' id='txt_imr" + add_row_s + "' >";
							hide_values += "<input type='hidden' value='" + c3.innerHTML + "' name='txt_imr_date" + add_row_s + "' id='txt_imr_date" + add_row_s + "' >";
							hide_values += "<input type='hidden' value='" + c4.innerHTML + "' name='txt_rate_unit" + add_row_s + "' id='txt_rate_unit" + add_row_s + "' >";
							hide_values += "<input type='hidden' value='" + c5.innerHTML + "' name='txt_rent" + add_row_s + "' id='txt_rent" + add_row_s + "' >";
							hide_values += "<input type='hidden' value='" + c6.innerHTML + "' name='txt_factor" + add_row_s + "' id='txt_factor" + add_row_s + "' >";
							//document.getElementById("add_hidden").innerHTML = document.getElementById("add_hidden").innerHTML + hide_values;
							if(document.getElementById("add_set_a1").value == "")
							{
								//document.getElementById("add_set_a1").value = add_row_s;
							}
							else
							{
								//document.getElementById("add_set_a1").value = document.getElementById("add_set_a1").value + "." + add_row_s;
							}
							add_row_s++;

                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
		function clearrow()
		{
			//alert();
			document.form.txt_meter_no.value 	= "";
			document.form.txt_imr.value 		= "";
			document.form.txt_imr_date.value 	= "";
			document.form.txt_rate_unit.value 	= "";
			document.form.txt_rent.value 		= "";
			document.form.txt_factor.value 		= "";
		}
		function RowValidation()
		{
			var meter_no 	= document.form.txt_meter_no.value;
			if(meter_no == "")
			{
				swal("Please Enter Meter No.", "", "");
				event.preventDefault();
				event.returnValue = false;
			}
			var imr 		= document.form.txt_imr.value;
			if(imr == "")
			{
				swal("Please Enter IMR.", "", "");
				event.preventDefault();
				event.returnValue = false;
			}
			var imr_date 	= document.form.txt_imr_date.value;
			if(imr_date == "")
			{
				swal("Please Enter IMR Date.", "", "");
				event.preventDefault();
				event.returnValue = false;
			}
			var rate_unit 	= document.form.txt_rate_unit.value;
			if(rate_unit == "")
			{
				swal("Unit Rate is Invalid", "", "");
				event.preventDefault();
				event.returnValue = false;
			}
			var rent 		= document.form.txt_rent.value;
			if(rent == "")
			{
				swal("Invalid Meter Rent Amount.", "", "");
				event.preventDefault();
				event.returnValue = false;
			}
			var factor 		= document.form.txt_factor.value;
			if(factor == "")
			{
				swal("Invalid Factor", "", "");
				event.preventDefault();
				event.returnValue = false;
			}
		}
		function addrow()
		{
			RowValidation();
			var new_row = document.getElementById("table1").insertRow(add_row_s);
			new_row.setAttribute("id", "row_" + add_row_s)
			new_row.className = "labelsmall";
			new_row.style.backgroundColor  = "#EAEAEA";
			var c1 = new_row.insertCell(0);
				c1.align = "center";
				c1.style.className = "extraItemTextbox";
			var c2 = new_row.insertCell(1);
				c2.align = "center";
				c2.style.className = "extraItemTextbox"; 
			var c3 = new_row.insertCell(2);
				c3.align = "center";
				c3.style.className = "extraItemTextbox";
			var c4 = new_row.insertCell(3);
				c4.align = "center";
				c4.style.className = "extraItemTextbox"; 
			var c5 = new_row.insertCell(4);
				c5.align = "center";
				c5.style.className = "extraItemTextbox"; 
			var c6 = new_row.insertCell(5);
				c6.align = "center";
				c6.style.className = "extraItemTextbox";
			var c7 = new_row.insertCell(6);
				c7.align = "center";
				c7.style.className = "extraItemTextbox";
			var c8 = new_row.insertCell(7);
				c8.align = "center";
				c8.style.className = "extraItemTextbox";
			c1.innerHTML = document.form.txt_meter_no.value;
			c2.innerHTML = document.form.txt_imr.value;
			c3.innerHTML = document.form.txt_imr_date.value;
			c4.innerHTML = document.form.txt_rate_unit.value;
			c5.innerHTML = document.form.txt_rent.value;
			c6.innerHTML = document.form.txt_factor.value;
			c7.innerHTML = "<input type='button' class='buttonstyle' name='btn_edit_" + add_row_s + "' style='height:25px;' id='btn_edit_" + add_row_s + "'  value=' EDIT ' onClick=editrow(" + add_row_s + ",'n')>";
			c8.innerHTML = "<input type='button' class='buttonstyle'  name='btn_del_" + add_row_s + "' style='height:25px;'  id='btn_del_" + add_row_s + "' value=' DEL ' onClick=delrow(" + add_row_s + ")>";
			var hide_values = "";
			hide_values = "<input type='hidden' value='" + c1.innerHTML + "' name='txt_meter_no" + add_row_s + "' id='txt_meter_no" + add_row_s + "' >";
			hide_values += "<input type='hidden' value='" + c2.innerHTML + "' name='txt_imr" + add_row_s + "' id='txt_imr" + add_row_s + "' >";
			hide_values += "<input type='hidden' value='" + c3.innerHTML + "' name='txt_imr_date" + add_row_s + "' id='txt_imr_date" + add_row_s + "' >";
			hide_values += "<input type='hidden' value='" + c4.innerHTML + "' name='txt_rate_unit" + add_row_s + "' id='txt_rate_unit" + add_row_s + "' >";
			hide_values += "<input type='hidden' value='" + c5.innerHTML + "' name='txt_rent" + add_row_s + "' id='txt_rent" + add_row_s + "' >";
			hide_values += "<input type='hidden' value='" + c6.innerHTML + "' name='txt_factor" + add_row_s + "' id='txt_factor" + add_row_s + "' >";
			document.getElementById("add_hidden").innerHTML = document.getElementById("add_hidden").innerHTML + hide_values;
			if(document.getElementById("add_set_a1").value == "")
			{
				document.getElementById("add_set_a1").value = add_row_s;
			}
			else
			{
				document.getElementById("add_set_a1").value = document.getElementById("add_set_a1").value + "." + add_row_s;
			}
			add_row_s++;
		}
		function editrow(rowno, update)
		{
			var total;
			var net_value;
			var edit_row = document.getElementById("table1").rows[rowno].cells;
			if (update == 'y') // transfer controls to table row
			{
				edit_row[0].innerHTML = document.form.txt_meter_no.value;
				edit_row[1].innerHTML = document.form.txt_imr.value;
				edit_row[2].innerHTML = document.form.txt_imr_date.value;
				edit_row[3].innerHTML = document.form.txt_rate_unit.value;
				edit_row[4].innerHTML = document.form.txt_rent.value;
				edit_row[5].innerHTML = document.form.txt_factor.value;
				document.getElementById("txt_meter_no" + rowno).value 	= edit_row[0].innerHTML;
				document.getElementById("txt_imr" + rowno).value 		= edit_row[1].innerHTML;
				document.getElementById("txt_imr_date" + rowno).value 	= edit_row[2].innerHTML;
				document.getElementById("txt_rate_unit" + rowno).value 	= edit_row[3].innerHTML;
				document.getElementById("txt_rent" + rowno).value 		= edit_row[4].innerHTML;
				document.getElementById("txt_factor" + rowno).value 	= edit_row[5].innerHTML;
				clearrow();
			}//update=='y'
			else  //transfer table row to controls
			{
				document.form.txt_meter_no.value 	= edit_row[0].innerHTML;
				document.form.txt_imr.value 		= edit_row[1].innerHTML;
				document.form.txt_imr_date.value 	= edit_row[2].innerHTML;
				document.form.txt_rate_unit.value 	= edit_row[3].innerHTML;
				document.form.txt_rent.value 		= edit_row[4].innerHTML;
				document.form.txt_factor.value 		= edit_row[5].innerHTML;
			}
			if (prev_edit_row == 0)//first time edit the row
			{
				document.getElementById("row_" + rowno).style.color = "red";
				document.getElementById("btn_edit_" + rowno).value = " EDIT ";
				document.getElementById("btn_add").outerHTML = "<input type='button' class='buttonstyle' name='btn_add' id='btn_add' style='height:25px;' value=' OK ' onClick=\"editrow(" + rowno + ",'y')\">";
				prev_edit_row = rowno;
			}
			else
			{
				if (rowno == prev_edit_row)
				{
					document.getElementById("row_" + prev_edit_row).style.color = "#770000";
					document.getElementById("btn_edit_" + rowno).value = " EDIT ";
					document.getElementById("btn_add").outerHTML = "<input type='button' class='buttonstyle' name='btn_add' id='btn_add' style=' height:25px;' value=' ADD ' onClick='addrow()'>";
					prev_edit_row = 0;
					//document.getElementById("txtmbkno").value = "";
				}
				else
				{
					document.getElementById("row_" + prev_edit_row).style.color = "#770000";
					document.getElementById("btn_edit_" + prev_edit_row).value = "";
					document.getElementById("row_" + rowno).style.color = "red";
					document.getElementById("btn_edit_" + rowno).value = " EDIT ";
					document.getElementById("btn_add").outerHTML = "<input type='button' name='btn_add' class='buttonstyle' id='btn_add' style=' height:25px;' value=' EDIT ' onClick=\"editrow(" + rowno + ",'y')\">";
					prev_edit_row = rowno;
				}
			}
		}
		function delrow(rownum)
		{
			var src_row = (rownum + 1)
			var tar_row = rownum
			var noofadd = (add_row_s - 1)
			for (x = rownum; x < noofadd; x++)
			{
				document.getElementById("txt_meter_no" + tar_row).value 	= document.getElementById("txt_meter_no" + src_row).value
				document.getElementById("txt_imr" + tar_row).value 			= document.getElementById("txt_imr" + src_row).value
				document.getElementById("txt_imr_date" + tar_row).value 	= document.getElementById("txt_imr_date" + src_row).value
				document.getElementById("txt_rate_unit" + tar_row).value 	= document.getElementById("txt_rate_unit" + src_row).value
				document.getElementById("txt_rent" + tar_row).value 		= document.getElementById("txt_rent" + src_row).value
				document.getElementById("txt_factor" + tar_row).value 		= document.getElementById("txt_factor" + src_row).value
				tar_row++;
				src_row++;
				var trow = document.getElementById("table1").rows[x].cells;
				var srow = document.getElementById("table1").rows[x + 1].cells;
				trow[0].innerText = srow[0].innerText;
				trow[1].innerText = srow[1].innerText;
				trow[2].innerText = srow[2].innerText;
				trow[3].innerText = srow[3].innerText;
				trow[4].innerText = srow[4].innerText;
				trow[5].innerText = srow[5].innerText;
				//trow[6].innerText = srow[6].innerText;
			}
			document.getElementById("txt_meter_no" + tar_row).outerHTML = "";
			document.getElementById("txt_imr" + tar_row).outerHTML = "";
			document.getElementById("txt_imr_date" + tar_row).outerHTML = "";
			document.getElementById("txt_rate_unit" + tar_row).outerHTML = "";
			document.getElementById("txt_rent" + tar_row).outerHTML = "";
			document.getElementById("txt_factor" + tar_row).outerHTML = "";
			document.getElementById('table1').deleteRow(noofadd)
			document.getElementById("add_set_a1").value = "";
			for (x = 2; x < noofadd; x++)
			{
				if (document.getElementById("add_set_a1").value == "")
				{
					document.getElementById("add_set_a1").value = x;
				}
				else
				{
					document.getElementById("add_set_a1").value += ("." + x);
				}
			}
			add_row_s = noofadd++;
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
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
          		<div class="title">Water Bill New</div>
                <div class="container_12">
                    <div class="grid_12">

                        <blockquote class="bq1">
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
                        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="20%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Short Name</td> 
                                    <td>
										<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" style="width:465px" onChange="workorderdetail();recoverydetail();WaterMeterDetail();">
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
										<div class="label gradientbg" align="center">Meter Details</div>
										<div style="width:90%; height:auto;" align="center">
											<table width="100%" class="table1" id="table1">
												<tr class="label" style="background-color:#EAEAEA">
													<td align="center">Meter No.</td>
													<td align="center">IMR</td>
													<td align="center">IMR Date</td>
													<td align="center">Rate <i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></i><b id="water_limit"> / 1000 Liters</b></td>
													<td align="center">Meter Rent <i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></i></td>
													<td align="center">Factor</td>
													<td align="center" colspan="2">Action</td>
												</tr>
												<tr>
													<td align="center"><input type="text" class="extraItemTextbox" name="txt_meter_no" id="txt_meter_no"></td>
													<td align="center"><input type="number" class="extraItemTextbox" name="txt_imr" id="txt_imr" onKeyPress="return isNumberKey(event,this)"></td>
													<td align="center"><input type="text" placeholder="DD-MM-YYYY" class="extraItemTextbox" name="txt_imr_date" id="txt_imr_date"></td>
													<td align="center"><input type="number" class="extraItemTextbox" name="txt_rate_unit" id="txt_rate_unit" onKeyPress="return isNumberKey(event,this)"></td>
													<td align="center"><input type="number" class="extraItemTextbox" name="txt_rent" id="txt_rent" onKeyPress="return isNumberKey(event,this)"></td>
													<td align="center"><input type="number" class="extraItemTextbox" name="txt_factor" id="txt_factor" onKeyPress="return isNumberKey(event,this)"></td>
													<td align="center" colspan="2" valign="middle"><input type="button" class="buttonstyle" name="btn_add" id="btn_add" value="Add" onClick="addrow(); clearrow();"></td>
												</tr>
												<tr>
                                                    <span id="add_hidden"></span>
												</tr>
											</table>
                                             <input type="hidden" value="" name="add_set_a1" id="add_set_a1"/>
											 <input type="hidden" name="txt_w_limit" id="txt_w_limit">
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
