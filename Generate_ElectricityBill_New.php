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
   	$sheetid 			= 	trim($_POST['cmb_shortname']);
	$rbn 				= 	trim($_POST['txt_rbn']);
	$ebill_no 			= 	trim($_POST['txt_ebill_no']);
	//$meter_no 			= 	trim($_POST['txt_meterno']);
	//$ebill_no 			= 	trim($_POST['txt_billno']);
    //$imr 				= 	trim($_POST['txt_initial']);
    //$imr_date 			= 	dt_format(trim($_POST['txt_initial_date']));
	//$fmr 				= 	trim($_POST['txt_final']);
    //$fmr_date 			= 	dt_format(trim($_POST['txt_final_date']));
    //$rate 				= 	trim($_POST['txt_rate']);
	//$meter_rent 		= 	trim($_POST['txt_meter_rent']);
    //$electricity_cost	= 	trim($_POST['txt_electricity_cost']);
   // $er_date 			= 	dt_format(trim($_POST['txt_date']));
	if(($rbn != "") && ($sheetid != ""))
	//echo $sheetid;
	{
		$select_query = "select * from measurementbook where sheetid = '$sheetid' and rbn = '$rbn'";
		$select_sql = mysql_query($select_query);
		//echo $select_query;
		if($select_sql == true)
		{
			if(mysql_num_rows($select_sql) == 0)
			{
				$delete_query = "delete from generate_electricitybill where sheetid = '$sheetid' and rbn = '$rbn'";
				$delete_sql = mysql_query($delete_query);
				$rec = explode(".", $_POST['add_set_a1']);
				for ($c = 0; $c < count($rec); $c++) 
				{
					$x = $rec[$c];
					if($x != "")
					{
						$meter_no	=	chop($_POST['cmb_meter_no'.$x]);
						$imr		=	chop($_POST['txt_imr'.$x]);
						$imr_date	=	dt_format(chop($_POST['txt_imr_date'.$x]));
						$fmr		=	chop($_POST['txt_fmr'.$x]);
						$fmr_date	=	dt_format(chop($_POST['txt_fmr_date'.$x]));
						$rate		=	chop($_POST['txt_rate_unit'.$x]);
						$meter_rent	=	chop($_POST['txt_rent'.$x]);
						$unit		=	chop($_POST['txt_unit'.$x]);
						$factor		=	chop($_POST['txt_factor'.$x]);
						$amount		=	chop($_POST['txt_amount'.$x]);
						$limit		=	"";
						if($meter_no != "")
						{
							$erecovery_sql 	= 	"INSERT INTO generate_electricitybill set
																sheetid = '$sheetid',
																rbn = '$rbn',
																meter_no = '$meter_no',
																ebill_no = '$ebill_no',
																imr = '$imr',
																imr_date = '$imr_date',
																fmr = '$fmr',
																fmr_date = '$fmr_date',
																rate = '$rate',
																meter_rent = '$meter_rent',
																factor = '$factor',
																unit_consum = '$unit',
																electricity_cost = '$amount',
																er_date  = NOW(),
																staffid = '$staffid',
																userid = '$userid',
																modifieddate = NOW(),
																active = 1";
							//echo $erecovery_sql."<br/>";
							$erecovery_query 	= 	mysql_query($erecovery_sql);
						}
					}
				}
    			/*$erecovery_sql 		= 	"INSERT INTO generate_electricitybill set
                                            sheetid 		= '$sheetid',
											rbn 			= '$rbn',
                                            meter_no 		= '$meter_no',
											ebill_no 		= '$ebill_no',
											imr 			= '$imr',
                                            imr_date 		= '$imr_date',
											fmr 			= '$fmr',
                                            fmr_date 		= '$fmr_date',
                                            rate 			= '$rate',
											meter_rent 		= '$meter_rent',
											electricity_cost = '$electricity_cost',
                                            er_date 		= '$er_date',
											staffid 		= '$staffid',
                                            userid 			= '$userid',
											modifieddate 	= NOW(),
											active 			= 1";
											//modifieddate = NOW()";
    			$erecovery_query 	= 	mysql_query($erecovery_sql);*/
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
 //strURL = "findabstract_mbookno.php?sheetid=" + document.form.cmb_work_no.value;
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
							//document.form.txt_techsanction.value 	= name[0];
							//document.form.txt_agreemntno.value 		= name[2];
                            document.form.txt_workname.value 		= name[3];
							document.form.txt_workorder.value 		= name[5];
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
		function getrbn()
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
            strURL = "findabstract_mbookno.php?sheetid=" + document.form.cmb_shortname.value;
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
                            document.form.txt_rbn.value = name[3];
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
		function ElectricityMeterDetail()
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
            strURL = "find_electricity_meter.php?workorderno=" + document.form.cmb_shortname.value+"&temp=2";
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
                        for(i = 0; i < name.length; i+=6)
                        {
							var optn 	= 	document.createElement("option")
                            optn.value 	= 	name[i];
                            optn.text 	= 	name[i];
                            document.form.cmb_meter_no.options.add(optn)
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }

		function meter_details()
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
            strURL = "find_electricity_meter_details.php?workorderno=" + document.form.cmb_shortname.value+"&meterno="+document.form.cmb_meter_no.value;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText;
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
							var fmr 		= name[8];
							var fmr_date	= name[9];
							if(fmr != "")
							{
								document.form.txt_imr.value 		= fmr;
								document.form.txt_imr_date.value 	= fmr_date;
							}
							else
							{
								document.form.txt_imr.value 		= name[1];
								document.form.txt_imr_date.value 	= name[2];
							}
                            //document.form.txt_meterno.value 		= name[0];
							//document.form.txt_initial.value 		= name[1];
							//document.form.txt_initial_date.value 	= name[2];
							document.form.txt_rate_unit.value 		= name[3];
							document.form.txt_rent.value 			= name[4];
							document.form.txt_factor.value 			= name[7];
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
		function calculateEBamount()
		{
			var meter_no = Number(document.form.cmb_meter_no.value);
			//if(meter_no != "")
			//{
				var imr 	= Number(document.form.txt_imr.value);
				var fmr 	= Number(document.form.txt_fmr.value);
				if(fmr != "")
				{
					if(fmr>imr)
					{
						var unitrate 	= document.form.txt_rate_unit.value;
						var meterrent 	= document.form.txt_rent.value;
						var factor 		= document.form.txt_factor.value;
						var usedunit 	= Number(fmr)-Number(imr);
						var usedamount 	= (Number(unitrate)*Number(usedunit)*Number(factor))+Number(meterrent);
						//alert(usedamount);
						//var used_unit 	= Number(fmr)-Number(imr);
						document.form.txt_amount.value 	= usedamount.toFixed(2);
						document.form.txt_unit.value 	= usedunit.toFixed(2);
					}
					else
					{
						swal("FMR should be greater than IMR", "", "");
						event.preventDefault();
						event.returnValue = false;
					}
				}
			//}
			//else
			//{
				//swal("please Select Meter No.", "", "");
			//}
		}
		function ClearOldData()
		{
			document.form.txt_fmr.value = "";
			document.form.txt_fmr_date.value = "";
			document.form.txt_amount.value = "";
			document.form.txt_unit.value = "";
		}
		function clearrow()
		{
			document.form.cmb_meter_no.value 	= "";
			document.form.txt_imr.value 		= "";
			document.form.txt_imr_date.value 	= "";
			document.form.txt_fmr.value 		= "";
			document.form.txt_fmr_date.value 	= "";
			document.form.txt_rate_unit.value 	= "";
			document.form.txt_rent.value 		= "";
			document.form.txt_unit.value 		= "";
			document.form.txt_factor.value 		= "";
			document.form.txt_amount.value 		= "";
		}
		function RowValidation()
		{
			var meter_no 	= document.form.cmb_meter_no.value;
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
			var fmr 		= document.form.txt_fmr.value;
			if(fmr == "")
			{
				swal("Please Enter FMR.", "", "");
				event.preventDefault();
				event.returnValue = false;
			}
			var fmr_date 	= document.form.txt_fmr_date.value;
			if(fmr_date == "")
			{
				swal("Please Select FMR Date.", "", "");
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
			var unit_consum = document.form.txt_unit.value;
			if(unit_consum == "")
			{
				swal("Invalid Unit Consumption.", "", "");
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
			var amount 		= document.form.txt_amount.value;
			if(amount == "")
			{
				swal("E-Bill Amount is Invalid.", "", "");
				event.preventDefault();
				event.returnValue = false;
			}
		}
		function addrow()
		{
			RowValidation();
			calculateEBamount();
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
			var c9 = new_row.insertCell(8);
				c9.align = "center";
				c9.style.className = "extraItemTextbox";
			var c10 = new_row.insertCell(9);
				c10.align = "center";
				c10.style.className = "extraItemTextbox";
			var c11 = new_row.insertCell(10);
				c11.align = "center";
				c11.style.className = "extraItemTextbox";
			var c12 = new_row.insertCell(11);
				c12.align = "center";
				c12.style.className = "extraItemTextbox";
			c1.innerHTML 	= document.form.cmb_meter_no.value;
			c2.innerHTML 	= document.form.txt_imr.value;
			c3.innerHTML 	= document.form.txt_imr_date.value;
			c4.innerHTML 	= document.form.txt_fmr.value;
			c5.innerHTML 	= document.form.txt_fmr_date.value;
			c6.innerHTML 	= document.form.txt_rate_unit.value;
			c7.innerHTML 	= document.form.txt_rent.value;
			c8.innerHTML 	= document.form.txt_unit.value;
			c9.innerHTML 	= document.form.txt_factor.value;
			c10.innerHTML 	= document.form.txt_amount.value;
			c11.innerHTML 	= "<input type='button' class='buttonstyle' name='btn_edit_" + add_row_s + "' style='height:25px;' id='btn_edit_" + add_row_s + "'  value=' EDIT ' onClick=editrow(" + add_row_s + ",'n')>";
			c12.innerHTML 	= "<input type='button' class='buttonstyle'  name='btn_del_" + add_row_s + "' style='height:25px;'  id='btn_del_" + add_row_s + "' value=' DEL ' onClick=delrow(" + add_row_s + ")>";
			var hide_values = "";
			hide_values = "<input type='hidden' value='" + c1.innerHTML + "' name='cmb_meter_no" + add_row_s + "' id='cmb_meter_no" + add_row_s + "' >";
			hide_values += "<input type='hidden' value='" + c2.innerHTML + "' name='txt_imr" + add_row_s + "' id='txt_imr" + add_row_s + "' >";
			hide_values += "<input type='hidden' value='" + c3.innerHTML + "' name='txt_imr_date" + add_row_s + "' id='txt_imr_date" + add_row_s + "' >";
			hide_values += "<input type='hidden' value='" + c4.innerHTML + "' name='txt_fmr" + add_row_s + "' id='txt_fmr" + add_row_s + "' >";
			hide_values += "<input type='hidden' value='" + c5.innerHTML + "' name='txt_fmr_date" + add_row_s + "' id='txt_fmr_date" + add_row_s + "' >";
			hide_values += "<input type='hidden' value='" + c6.innerHTML + "' name='txt_rate_unit" + add_row_s + "' id='txt_rate_unit" + add_row_s + "' >";
			hide_values += "<input type='hidden' value='" + c7.innerHTML + "' name='txt_rent" + add_row_s + "' id='txt_rent" + add_row_s + "' >";
			hide_values += "<input type='hidden' value='" + c8.innerHTML + "' name='txt_unit" + add_row_s + "' id='txt_unit" + add_row_s + "' >";
			hide_values += "<input type='hidden' value='" + c9.innerHTML + "' name='txt_factor" + add_row_s + "' id='txt_factor" + add_row_s + "' >";
			hide_values += "<input type='hidden' value='" + c10.innerHTML + "' name='txt_amount" + add_row_s + "' id='txt_amount" + add_row_s + "' >";
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
			clearrow();
		}
		function editrow(rowno, update)
		{
			var total;
			var net_value;
			var edit_row = document.getElementById("table1").rows[rowno].cells;
			if (update == 'y') // transfer controls to table row
			{
				edit_row[0].innerHTML = document.form.cmb_meter_no.value;
				edit_row[1].innerHTML = document.form.txt_imr.value;
				edit_row[2].innerHTML = document.form.txt_imr_date.value;
				edit_row[3].innerHTML = document.form.txt_fmr.value;
				edit_row[4].innerHTML = document.form.txt_fmr_date.value;
				edit_row[5].innerHTML = document.form.txt_rate_unit.value;
				edit_row[6].innerHTML = document.form.txt_rent.value;
				edit_row[7].innerHTML = document.form.txt_unit.value;
				edit_row[8].innerHTML = document.form.txt_factor.value;
				edit_row[9].innerHTML = document.form.txt_amount.value;
				document.getElementById("cmb_meter_no" + rowno).value 	= edit_row[0].innerHTML;
				document.getElementById("txt_imr" + rowno).value 		= edit_row[1].innerHTML;
				document.getElementById("txt_imr_date" + rowno).value 	= edit_row[2].innerHTML;
				document.getElementById("txt_fmr" + rowno).value 		= edit_row[3].innerHTML;
				document.getElementById("txt_fmr_date" + rowno).value 	= edit_row[4].innerHTML;
				document.getElementById("txt_rate_unit" + rowno).value 	= edit_row[5].innerHTML;
				document.getElementById("txt_rent" + rowno).value 		= edit_row[6].innerHTML;
				document.getElementById("txt_unit" + rowno).value 		= edit_row[7].innerHTML;
				document.getElementById("txt_factor" + rowno).value 	= edit_row[8].innerHTML;
				document.getElementById("txt_amount" + rowno).value 	= edit_row[9].innerHTML;
				clearrow();
			}//update=='y'
			else  //transfer table row to controls
			{
				document.form.cmb_meter_no.value 	= edit_row[0].innerHTML;
				document.form.txt_imr.value 		= edit_row[1].innerHTML;
				document.form.txt_imr_date.value 	= edit_row[2].innerHTML;
				document.form.txt_fmr.value 		= edit_row[3].innerHTML;
				document.form.txt_fmr_date.value 	= edit_row[4].innerHTML;
				document.form.txt_rate_unit.value 	= edit_row[5].innerHTML;
				document.form.txt_rent.value 		= edit_row[6].innerHTML;
				document.form.txt_unit.value 		= edit_row[7].innerHTML;
				document.form.txt_factor.value 		= edit_row[8].innerHTML;
				document.form.txt_amount.value 		= edit_row[9].innerHTML;
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
			total_unit_amount_consumption();
		}
		function delrow(rownum)
		{
			var src_row = (rownum + 1)
			var tar_row = rownum
			var noofadd = (add_row_s - 1)
			for (x = rownum; x < noofadd; x++)
			{
				document.getElementById("cmb_meter_no" + tar_row).value 	= document.getElementById("cmb_meter_no" + src_row).value
				document.getElementById("txt_imr" + tar_row).value 			= document.getElementById("txt_imr" + src_row).value
				document.getElementById("txt_imr_date" + tar_row).value 	= document.getElementById("txt_imr_date" + src_row).value
				document.getElementById("txt_fmr" + tar_row).value 			= document.getElementById("txt_fmr" + src_row).value
				document.getElementById("txt_fmr_date" + tar_row).value 	= document.getElementById("txt_fmr_date" + src_row).value
				document.getElementById("txt_rate_unit" + tar_row).value 	= document.getElementById("txt_rate_unit" + src_row).value
				document.getElementById("txt_rent" + tar_row).value 		= document.getElementById("txt_rent" + src_row).value
				document.getElementById("txt_unit" + tar_row).value 		= document.getElementById("txt_unit" + src_row).value
				document.getElementById("txt_factor" + tar_row).value 		= document.getElementById("txt_factor" + src_row).value
				document.getElementById("txt_amount" + tar_row).value 		= document.getElementById("txt_amount" + src_row).value
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
				trow[6].innerText = srow[6].innerText;
				trow[7].innerText = srow[7].innerText;
				trow[8].innerText = srow[8].innerText;
				trow[9].innerText = srow[9].innerText;
				//trow[6].innerText = srow[6].innerText;
			}
			document.getElementById("cmb_meter_no" + tar_row).outerHTML = "";
			document.getElementById("txt_imr" + tar_row).outerHTML = "";
			document.getElementById("txt_imr_date" + tar_row).outerHTML = "";
			document.getElementById("txt_fmr" + tar_row).outerHTML = "";
			document.getElementById("txt_fmr_date" + tar_row).outerHTML = "";
			document.getElementById("txt_rate_unit" + tar_row).outerHTML = "";
			document.getElementById("txt_rent" + tar_row).outerHTML = "";
			document.getElementById("txt_unit" + tar_row).outerHTML = "";
			document.getElementById("txt_factor" + tar_row).outerHTML = "";
			document.getElementById("txt_amount" + tar_row).outerHTML = "";
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
			total_unit_amount_consumption();
		}
		function total_unit_amount_consumption()
		{
			var i, total_amount = 0, total_unit_consum = 0;
			var rowstr = document.getElementById("add_set_a1").value;
			var id = rowstr.split(".");
			for(i=0; i<id.length; i++)
			{
				var amount = Number(document.getElementById("txt_amount" + id[i]).value);
				total_amount = total_amount + amount;
				var unit_consum = Number(document.getElementById("txt_unit" + id[i]).value);
				total_unit_consum = total_unit_consum + unit_consum;
			}
			document.getElementById("txt_electricity_cost").value = total_amount.toFixed(2);
			document.getElementById("txt_electricity_unit").value = total_unit_consum;
		}
</script>
<script>
   $(function () {
        /*$( "#txt_initial_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd-mm-yy",
                    maxDate: new Date,
                    defaultDate: new Date,
                });
		$( "#txt_final_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd-mm-yy",
                    maxDate: new Date,
                    defaultDate: new Date,
                });*/
		$( "#txt_fmr_date" ).datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: "dd-mm-yy",
                    maxDate: new Date,
                    defaultDate: new Date,
                });	
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
		$.fn.validaterbn = function(event) { 
					if($("#txt_rbn").val()==""){ 
					var a="Please Enter RBN No.";
					$('#val_rbn').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_rbn').text(a);
					}
				}
		$.fn.validatebillno = function(event) { 
					if($("#txt_ebill_no").val()==""){ 
					var a="Please Enter Electricity Bill No.";
					$('#val_ebill_no').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_ebill_no').text(a);
					}
				}
		$.fn.validatementerno = function(event) { 
					if($("#cmb_meter_no").val()==""){ 
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
		$.fn.validateinitial = function(event) { 
					if($("#txt_imr").val()==""){ 
					var a="Please Enter Initial Reading";
					//$('#val_initial').text(a);
					swal(a, "", "");
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					//$('#val_initial').text(a);
					}
				}
		$.fn.validateinitialdate = function(event) { 
					if($("#txt_imr_date").val()==""){ 
					var a="Please Select Initial Reading Date";
					//$('#val_initialdate').text(a);
					swal(a, "", "");
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					//$('#val_initialdate').text(a);
					}
				}
		$.fn.validatefinal = function(event) { 
					if($("#txt_fmr").val()==""){ 
					var a="Please Enter FMR";
					//$('#val_final').text(a);
					swal(a, "", "");
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					//$('#val_final').text(a);
					}
				}
		$.fn.validatefinaldate = function(event) { 
					if($("#txt_fmr_date").val()==""){ 
					var a="Please Enter FMR Date";
					//$('#val_finaldate').text(a);
					swal(a, "", "");
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					//$('#val_finaldate').text(a);
					}
				}
		$.fn.validaterate = function(event) { 
					if($("#txt_rate_unit").val()==""){ 
					var a="Please Enter Rate";
					//$('#val_rate').text(a);
					swal(a, "", "");
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					//$('#val_rate').text(a);
					}
				}
		$.fn.validatemeterrent = function(event) { 
					if($("#txt_rent").val()==""){ 
					var a="Please Enter Rate";
					//$('#val_meter_rent').text(a);
					swal(a, "", "");
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					//$('#val_meter_rent').text(a);
					}
				}
		$.fn.validateunit = function(event) { 
					if($("#txt_unit").val()==""){ 
					var a="Please Enter Rate";
					//$('#val_meter_rent').text(a);
					swal(a, "", "");
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					//$('#val_meter_rent').text(a);
					}
				}	
		$.fn.validatefactor = function(event) { 
					if($("#txt_factor").val()==""){ 
					var a="Please Enter Rate";
					//$('#val_meter_rent').text(a);
					swal(a, "", "");
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					//$('#val_meter_rent').text(a);
					}
				}		
		$.fn.vaidateamonut = function(event) { 
					if($("#txt_amount").val()==""){ 
					var a="Please Select Date";
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
		$.fn.vaidaterow = function(event) { 
					if($("#add_set_a1").val()==""){ 
					var a="Please Enter Atleast One Row";
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
		//$("#txt_workname").keyup(function(event){
		//$(this).validateworkname(event);
		//});
		//$("#txt_workorder").keyup(function(event){
		//$(this).validateworkorder(event);
		//});
		//$("#txt_rbn").keyup(function(event){
		//$(this).validaterbn(event);
		//});
		$("#txt_ebill_no").keyup(function(event){
		$(this).validatebillno(event);
		});
		$("#cmb_meter_no").change(function(event){
		$(this).validatementerno(event);
		});
		//$("#txt_imr").keyup(function(event){
		//$(this).validateinitial(event);
		//});
		//$("#txt_imr_date").change(function(event){
		//$(this).validateinitialdate(event);
		//});
		$("#txt_fmr").keyup(function(event){
		$(this).validatefinal(event);
		});
		$("#txt_fmr_date").change(function(event){
		$(this).validatefinaldate(event);
		});
		//$("#txt_rate_unit").keyup(function(event){
		//$(this).validaterate(event);
		//});
		//$("#txt_rent").keyup(function(event){
		//$(this).validatemeterrent(event);
		//});
		//$("#txt_unit").change(function(event){
		//$(this).validateunit(event);
		//});
		//$("#txt_factor").keyup(function(event){
		//$(this).validatefactor(event);
		//});
		//$("#txt_amount").change(function(event){
		//$(this).vaidateamonut(event);
		//});
		
		/*$("#btn_add").click(function(event){
		$(this).validatementerno(event);
		$(this).validateinitial(event);
		$(this).validateinitialdate(event);
		$(this).validatefinal(event);
		$(this).validatefinaldate(event);
		$(this).validaterate(event);
		$(this).validatemeterrent(event);
		$(this).validateunit(event);
		$(this).validatefactor(event);
		$(this).vaidateamonut(event);
		calculateEBamount();
		});*/
		
		$("#top").submit(function(event){
		$(this).validateshortname(event);
		$(this).validateworkname(event);
		$(this).validateworkorder(event);
		$(this).validaterbn(event);
		$(this).validatebillno(event);
		$(this).vaidaterow(event);
		//$(this).validatementerno(event);
		//$(this).validateinitial(event);
		//$(this).validateinitialdate(event);
		//$(this).validatefinal(event);
		//$(this).validatefinaldate(event);
		//$(this).validaterate(event);
		//$(this).validatemeterrent(event);
		//$(this).validateunit(event);
		//$(this).validatefactor(event);
		//$(this).vaidateamonut(event);
		calculateEBamount();
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

</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                            <div class="title">Generate - Electricity Bill New</div>
                <div class="container_12">
                    <div class="grid_12">

						<!--<div align="right"><a href="View_Electricity_generate_Bill.php">View</a>&nbsp;&nbsp;&nbsp;</div>-->
                        <blockquote class="bq1" style="overflow:scroll">
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
                        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="18%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Short Name</td> 
                                    <td>
										<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" style="width:465px" onChange="workorderdetail();getrbn();ElectricityMeterDetail();recovery();">
											<option value="">--------- Select Work Short Name ---------</option>
											<?php echo $objBind->BindWorkOrderNo_CIVIL(0); ?>
										</select>
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_shortname" style="color:red" colspan="">&nbsp;</td></tr>
                                
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Name of Work</td>
                                    <td><textarea name='txt_workname' id='txt_workname' class="textboxdisplay" readonly="readonly" rows="6" style="width: 465px;"><?php if($_GET['sheet_id'] != ''){ echo $work_name; } ?></textarea></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_wname" style="color:red" colspan="">&nbsp;</td></tr>
                                
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order No.</td>
                                    <td><input type="text" name='txt_workorder' id='txt_workorder' class="textboxdisplay" readonly="" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_workorder" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">RAB No.</td>
                                    <td>
									<input type="text" name='txt_rbn' id='txt_rbn' class="textboxdisplay" readonly="" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									<label class="label">
									&emsp;&emsp;&emsp;
									Electricity Bill No.
									&emsp;&emsp;&emsp;
									</label>
									<input type="text" name='txt_ebill_no' id='txt_ebill_no' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" colspan="">
									<div class="labeldisplay" style="width:345px;float:left;color:red;" id="val_rbn">&nbsp;</div>
									<div class="labeldisplay" style="float:right;color:red; width:380px;" id="val_ebill_no"></div>
									</td>
								</tr>
								
								
								<tr>
									<td colspan="3" align="center">
										<div class="label gradientbg" align="center">Meter Details</div>
										<div style="width:90%; height:auto;" align="center">
											<table width="100%" class="table1" id="table1">
												<tr class="label" style="background-color:#EAEAEA">
													<td align="center">Meter No.</td>
													<td align="center">IMR</td>
													<td align="center">IMR Date</td>
													<td align="center">FMR</td>
													<td align="center">FMR Date</td>
													<td align="center">Rate/unit <i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></td>
													<td align="center">Meter Rent <i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></td>
													<td align="center">Unit </td>
													<td align="center">Factor </td>
													<td align="center">Amount <i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></td>
													<td align="center" colspan="2">Action</td>
												</tr>
												<tr>
													<td align="center" style="vertical-align:middle">
														<select name="cmb_meter_no" id="cmb_meter_no" class="extraItemTextbox" style="text-align:center; width:80px;" onChange="meter_details();ClearOldData();">
															<option value="">-Select-</option>
														</select>
													</td>
													<td align="center"><input type="text" class="extraItemTextbox" name="txt_imr" id="txt_imr"  style="background-color:#D7D2D5; text-align:center"></td>
													<td align="center"><input type="text" class="extraItemTextbox" name="txt_imr_date" id="txt_imr_date"  style="background-color:#D7D2D5; text-align:center"></td>
													<td align="center"><input type="text" class="extraItemTextbox" name="txt_fmr" id="txt_fmr" onBlur="calculateEBamount();"></td>
													<td align="center"><input type="text" class="extraItemTextbox" name="txt_fmr_date" id="txt_fmr_date" placeholder="dd-mm-yyyy"></td>
													<td align="center"><input type="text" class="extraItemTextbox" name="txt_rate_unit" id="txt_rate_unit" style="background-color:#D7D2D5; text-align:center"></td>
													<td align="center"><input type="text" class="extraItemTextbox" name="txt_rent" id="txt_rent" style="background-color:#D7D2D5; text-align:center"></td>
													<td align="center"><input type="text" class="extraItemTextbox" name="txt_unit" id="txt_unit" style="background-color:#D7D2D5; text-align:center"></td>
													<td align="center"><input type="text" class="extraItemTextbox" name="txt_factor" id="txt_factor" style="background-color:#D7D2D5; text-align:center"></td>
													<td align="center"><input type="text" class="extraItemTextbox" name="txt_amount" id="txt_amount" readonly="" style="background-color:#D7D2D5; text-align:center"></td>
													<td align="center" colspan="2" valign="middle"><input type="button" class="buttonstyle" name="btn_add" id="btn_add" value="Add" onClick="addrow();total_unit_amount_consumption();"></td>
												</tr>
												<tr>
                                                    <span id="add_hidden"></span>
												</tr>
											</table>
											<input type="hidden" value="" name="add_set_a1" id="add_set_a1"/>
										</div>
									</td>
								</tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_electricity_row" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
									<!--<td>&nbsp;</td>-->
									<td class="label" colspan="2" align="right">Total Unit consumption&emsp;&emsp;</td>
									<td>
										<input type="text" name='txt_electricity_unit' readonly="" id='txt_electricity_unit' class="textboxdisplay" value="220" style="width: 120px;">
									</td>
								</tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_electricity_cost" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
									<!--<td>&nbsp;</td>-->
									<td class="label" colspan="2" align="right">Total Amount <i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'>&emsp;&emsp;</td>
									<td>
										<input type="text" name='txt_electricity_cost' readonly="" id='txt_electricity_cost' class="textboxdisplay" value="405.00" style="width: 120px;">
									</td>
								</tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_electricity_cost" style="color:red" colspan="">&nbsp;</td></tr>
								
							</table>
								
							<!--<table width="1078px" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
								<tr>
									<td>&nbsp;</td>
									<td colspan="5" style="background-color:#D8D8D8; height:25px; vertical-align:middle" class="label"> &nbsp;Electricity Recovery Details</td>
								</tr>
								<tr>
									<td width="18%">&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td width="18%">&nbsp;</td>
								</tr>
								<tr>
									<td width="18%">&nbsp;</td>
									<td class="label" width="227px;">Meter No.</td>
									<td colspan="4">
										<input type="text" name='txt_meterno' id='txt_meterno' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 430px;">
									</td>
								</tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_meterno" style="color:red" colspan="4">&nbsp;</td></tr>
								<tr>
									<td>&nbsp;</td>
									<td class="label">Initial Meter Reading</td>
									<td colspan="4">
										<input type="text" name='txt_initial' id='txt_initial' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<label class="label">IMR Date </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_initial_date' id='txt_initial_date' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="4">
									<span id="val_initial"></span>
									<span id="val_initialdate"></span>
									&nbsp;
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td class="label">Final Meter Reading</td>
									<td colspan="4">
										<input type="text" name='txt_initial' id='txt_initial' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<label class="label">FMR Date </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_initial_date' id='txt_initial_date' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="4">
									<span id="val_initial"></span>
									<span id="val_initialdate"></span>
									&nbsp;
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
									<td class="label">Rate of Electricity ( Rs.)</td>
									<td colspan="4">
										<input type="text" name='txt_rate' id='txt_rate' class="textboxdisplay" value="<?php if($_GET['sheet_id'] != ''){ echo $short_name; } ?>" style="width: 120px;">
										&nbsp;&nbsp;
										<label class="label"> /&nbsp;unit </label>
									</td>
								</tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_rate" style="color:red" colspan="4">&nbsp;</td></tr>
								<tr>
									<td>&nbsp;</td>
									<td class="label">Date</td>
									<td colspan="4"><input type="text" name="txt_date" id='txt_date' class="textboxdisplay" style="width: 120px;"></td>
								</tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_date" style="color:red" colspan="4">&nbsp;</td></tr>
							</table>-->
                            
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
		   		$('#cmb_shortname').chosen();
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
<style>
	.chosen-container{
		width:466px !important;
	}
</style>
