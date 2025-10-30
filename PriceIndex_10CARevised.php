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
	$no_of_month 		= 	trim($_POST['txt_no_of_month']);
	$quarter 			= 	trim($_POST['cmb_quarter']);
	$rbn 				= 	trim($_POST['txt_rbn']);
	
	$pidArr				= 	$_POST['txt_pid'];
	for($x1=0; $x1<count($pidArr); $x1++)
	{
		$pid = $pidArr[$x1];
		$rev_avg_pi_rate = trim($_POST['txt_avg_rev_pirate'.$pid]);
		$update_avg_pi_query = "update price_index set rev_avg_pi_rate = '$rev_avg_pi_rate', rev_modifieddate = NOW() where pid = '$pid'";
		$update_avg_pi_sql = mysql_query($update_avg_pi_query);
	}
	
	$pdtidArr			= 	$_POST['txt_pdtid'];
	for($x2=0; $x2<count($pdtidArr); $x2++)
	{
		$pdtid = $pdtidArr[$x2];
		$rev_pirate = trim($_POST['txt_rev_pirate'.$pdtid]);
		$update_pi_query = "update price_index_detail set rev_pi_rate = '$rev_pirate', rev_modifieddate = NOW() where pdtid = '$pdtid'";
		$update_pi_sql = mysql_query($update_pi_query);
	}
    if($update_pi_sql == true) 
	{
        $msg = "Price Index for 10CA Stored Successfully ";
		$success = 1;
		$update_escalation_query = "update escalation set flag = 0 where sheetid = '$sheetid' and quarter = '$quarter'";// and rbn = ''";
		$update_escalation_sql = mysql_query($update_escalation_query);

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
							
						var no_of_month = document.getElementById("txt_no_of_month").value;
						var j;
						var td_cell = 5;
						for(j=0; j<no_of_month; j++)
						{
							var cell6=row.insertCell(td_cell);
							var txt_box6 = document.createElement("input");
								txt_box6.name = "txt_price_index_rate"+bid+"[]";
								txt_box6.id = "txt_price_index_rate"+j+bid;
								txt_box6.value = "";
								txt_box6.setAttribute('class', "extraItemTextbox"); 
								cell6.appendChild(txt_box6);
								txt_box6.onblur = (function (bid) {
									return function() {
									CalculateAvgIndex(this,bid);
										//ValidateSlm();
									};
								})(bid);
							td_cell++;
						}
						/*var cell7=row.insertCell(6);
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
							})(bid);*/
						var cell9=row.insertCell(td_cell);
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
						td_cell++;
						var cell10=row.insertCell(td_cell);
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
		function CalculateAvgIndex(obj,bid,MonLen)
		{
			var id 			= obj.id;
			var bid 		= bid;
			var MonLen 		= MonLen;
			//alert("txt_rev_pirate"+bid1);
			var no_of_month = MonLen;//document.getElementById("txt_no_of_month").value;
			var total_price_index = 0;
			for(var i=0; i<no_of_month; i++)
			{
				//alert("txt_rev_pirate"+i+bid);
				var price_index = Number(document.getElementById("txt_rev_pirate"+i+bid).value);
				total_price_index = total_price_index + price_index;
				//alert(bid)
			}
			var avg_price_index = Number(total_price_index)/Number(no_of_month);
			//alert(avg_price_index)
			document.getElementById("txt_avg_rev_pirate"+bid).value = avg_price_index.toFixed(2);
		}
		function AllQuarter()
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
            strURL = "find_all_quarter.php?sheetid=" + document.form.cmb_shortname.value;
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
						var QtrStr 		= data;
						var SplitQtrStr = QtrStr.split("*");
						document.form.cmb_quarter.length = 0;
						var optn = document.createElement("option");
						optn.value = "";
						optn.text = "--------------Select-------------";
						document.form.cmb_quarter.options.add(optn);
                        for(i = 0; i < SplitQtrStr.length; i++)
                        {
							var optn = document.createElement("option")
							optn.value = SplitQtrStr[i];
							optn.text = "Quarter - "+SplitQtrStr[i];
							document.form.cmb_quarter.options.add(optn)  
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
		function getQtrDetails()
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
            strURL = "find_qtr_details.php?sheetid="+document.form.cmb_shortname.value+"&quarter="+document.form.cmb_quarter.value+"&type=TCA";
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
						var DataStr 		= data;
						var SplitDataStr1 = DataStr.split("#@#");
						var x1, x2, x3;
						for(x1=0; x1<SplitDataStr1.length; x1++)
						{
							var SplitDataStr2 = SplitDataStr1[x1];
							var SplitDataStr3 = SplitDataStr2.split("#");
							
							var MasterData 	= SplitDataStr3[0];
							var DetailData 	= SplitDataStr3[1];
							
							var SplitDataStr4 	= MasterData.split("*");
							
							var pid 			= SplitDataStr4[0];
							var pi_code 		= SplitDataStr4[1];
							var pi_from_date 	= SplitDataStr4[2];
							var pi_to_date 		= SplitDataStr4[3];
							var avg_pi_rate 	= SplitDataStr4[4];
							var esc_rbn 		= SplitDataStr4[5];
							var esc_id 			= SplitDataStr4[6];
							var quarter 		= SplitDataStr4[7];
							var pi_desc = "";
							//alert(pi_code)
							if(pi_code == "CI")
							{
								pi_desc = "Cement";
							}
							else if(pi_code == "SI")
							{
								pi_desc = "Steel";
							}
							else
							{
								pi_desc = "";
							}
							var SplitDataStr5 = DetailData.split("@");
							var cno = 0;	
							if(x1==0)
							{
								var xy = Number(document.getElementById("table1").rows.length);
								index1 = xy;
								var table			=	document.getElementById("table1");
								var hrow			=	table.insertRow(table.rows.length);
								hrow.style.height 	= 	"30px";
								hrow.id 			= 	"rowid"+index1;
								hrow.style.backgroundColor  = "#EAEAEA";
									
								var hcell	=	hrow.insertCell(cno);
									hcell.innerHTML = " &nbsp; ";
									hcell.setAttribute('class', "label");
									hcell.align = "center";
									hcell.style.verticalAlign = "middle";	
										
								cno++;
							}
								
							var x = Number(document.getElementById("table1").rows.length);
							index = x;
							var table			=	document.getElementById("table1");
							var row1			=	table.insertRow(table.rows.length);
							row1.style.height 	= 	"30px";
							row1.id 			= 	"rowid"+index;
							row1.style.backgroundColor  = "#EAEAEA";
							
								var cell1	=	row1.insertCell(0);
									cell1.innerHTML = "&nbsp;"+pi_desc+"&nbsp;";
									cell1.setAttribute('class', "label");
									//cell1.rowSpan = 2;
									cell1.align = "center";
									cell1.style.verticalAlign = "middle";
								var cellno = 1;
								var monthArr = [];
								var PdtidArr = [];
							for(x2=0; x2<SplitDataStr5.length; x2++)
							{
								var SplitDataStr6 	= SplitDataStr5[x2];
								var SplitDataStr7 	= SplitDataStr6.split("*");
								var pdtid 			= SplitDataStr7[0];
								var pi_month 		= SplitDataStr7[1];
								var pi_rate 		= SplitDataStr7[2];
								monthArr.push(pi_month);
								PdtidArr.push(pdtid);
								if(x1==0)
								{
									var hcell	=	hrow.insertCell(cno);
										hcell.innerHTML = pi_month;
										hcell.setAttribute('class', "label");
										hcell.align = "center";
										hcell.style.verticalAlign = "middle";	
									
									cno++;
								}
									
								var cell	=	row1.insertCell(cellno);
									cell.style.verticalAlign = "middle";
									cell.align = "center";
								var txt_box = document.createElement("input");
									txt_box.name = "txt_month[]";
									txt_box.id = "txt_month"+x2;
									txt_box.value = pi_rate;
									txt_box.setAttribute('class', "extraItemTextboxDisable"); 
									cell.appendChild(txt_box);	
									
									cellno++;
								
							}
							
							//  This is for Aveage Cell for Floating Price Index
							var cell1	=	row1.insertCell(cellno);
								cell1.style.verticalAlign = "middle";
								cell1.align = "center";
							var txt_box1 = document.createElement("input");
								txt_box1.name = "txt_month[]";
								txt_box1.id = "txt_month"+x2;
								txt_box1.value = " -- ";//avg_pi_rate;
								txt_box1.setAttribute('class', "extraItemTextboxDisable"); 
								cell1.appendChild(txt_box1);
								cellno++;
							if(x1==0)
							{
								var hcell	=	hrow.insertCell(cno);
									hcell.innerHTML = " Avg Index ";
									hcell.setAttribute('class', "label");
									hcell.align = "center";
									hcell.style.verticalAlign = "middle";	
									
								cno++;
							}
							
							for(x3=0; x3<SplitDataStr5.length; x3++)
							{
								var MonLen 	= SplitDataStr5.length;
								var ids = pid;
								var cell2	=	row1.insertCell(cellno);
									cell2.style.verticalAlign = "middle";
									cell2.align = "center";
								var txt_box2 = document.createElement("input");
									txt_box2.name = "txt_rev_pirate"+PdtidArr[x3];
									txt_box2.id = "txt_rev_pirate"+x3+pid;
									//txt_box2.value = PdtidArr[x3];//"txt_rev_pirate"+x3+pid;
									txt_box2.setAttribute('class', "extraItemTextbox"); 
									cell2.appendChild(txt_box2);
									txt_box2.onblur = (function (ids) {
										return function() {
										CalculateAvgIndex(this,ids,MonLen);
											//ValidateSlm();
										};
									})(ids);
								var txt_box21 = document.createElement("input");
									txt_box21.type = "hidden";
									txt_box21.name = "txt_pdtid[]";
									txt_box21.id = "txt_pdtid";
									txt_box21.value = PdtidArr[x3];
									txt_box21.setAttribute('class', "extraItemTextbox"); 
									cell2.appendChild(txt_box21);
									cellno++;
								if(x1==0)
								{
									var hcell	=	hrow.insertCell(cno);
										hcell.innerHTML = monthArr[x3];
										hcell.setAttribute('class', "label");
										hcell.align = "center";
										hcell.style.verticalAlign = "middle";	
										
									cno++;
								}
							}
							
							
							
							//  This is for Aveage Cell for Fixed Price Index	
							var cell5	=	row1.insertCell(cellno);
								cell5.style.verticalAlign = "middle";
								cell5.align = "center";
							var txt_box5 = document.createElement("input");
								txt_box5.name = "txt_avg_rev_pirate"+pid;
								txt_box5.id = "txt_avg_rev_pirate"+pid;
								//txt_box5.value = "AVG";
								txt_box5.setAttribute('class', "extraItemTextbox"); 
								cell5.appendChild(txt_box5);
							var txt_box6 = document.createElement("input");
								txt_box6.type = "hidden";
								txt_box6.name = "txt_pid[]";
								txt_box6.id = "txt_pid";
								txt_box6.value = pid;
								txt_box6.setAttribute('class', "extraItemTextbox"); 
								cell5.appendChild(txt_box6);
							if(x1==0)
							{
								var hcell	=	hrow.insertCell(cno);
									hcell.innerHTML = " Avg Index ";
									hcell.setAttribute('class', "label");
									hcell.align = "center";
									hcell.style.verticalAlign = "middle";	
									
								cno++;
							}
								index++;
								
						}
                    }
                }
            }
            xmlHttp.send(strURL);
        }
		
		/*function getrbn()
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
        }*/
		
	 /*function SetMonthField()
	 {
	 	var month_table = document.getElementById("table1");
	 	while(month_table.rows.length > 0) 
		{
		  month_table.deleteRow(0);
		}
		
	 	var no_of_month = 3;//document.getElementById("txt_no_of_month").value;
		
		var x = Number(document.getElementById("table1").rows.length);
		index = x;
		var table			=	document.getElementById("table1");
		var row1			=	table.insertRow(table.rows.length);
		row1.style.height 	= 	"30px";
		row1.id 			= 	"rowid"+index;
		row1.style.backgroundColor  = "#EAEAEA";
		
		var cell1	=	row1.insertCell(0);
			cell1.innerHTML = " Description ";
			cell1.setAttribute('class', "label");
			cell1.rowSpan = 2;
			cell1.align = "center";
			cell1.style.verticalAlign = "middle";
			
		var cell2	=	row1.insertCell(1);
			cell2.colSpan = 2;
			cell2.innerHTML = " Base Index ";
			cell2.setAttribute('class', "label");
			cell2.align = "center";
			cell2.style.verticalAlign = "middle";
			
		var cell3	=	row1.insertCell(2);
			cell3.colSpan = 2;
			cell3.innerHTML = " Escalation Breakup";
			cell3.setAttribute('class', "label");
			cell3.align = "center";
			cell3.style.verticalAlign = "middle";
			
		var cell4	=	row1.insertCell(3);
			cell4.innerHTML = " Month ";
			cell4.colSpan = no_of_month;
			cell4.setAttribute('class', "label");
			cell4.align = "center";
			cell4.style.verticalAlign = "middle";
			
		var cell5	=	row1.insertCell(4);
			cell5.innerHTML = " Average Price Index ";
			cell5.colSpan = 2;
			cell5.setAttribute('class', "label");
			cell5.align = "center";
			cell5.style.verticalAlign = "middle";
			index++;
			
		var x2 	= Number(document.getElementById("table1").rows.length);
		index 	= x2;
		var table2			=	document.getElementById("table1");
		var row2			=	table2.insertRow(table2.rows.length);
		row2.style.height 	= 	"30px";
		row2.id 			= 	"rowid"+index;
		row2.style.backgroundColor  = "#EAEAEA";
		
		
		var cell1	=	row2.insertCell(0);
			cell1.innerHTML = " Code ";
			cell1.setAttribute('class', "label");
			cell1.align = "center";
			cell1.style.verticalAlign = "middle";
			
		var cell2	=	row2.insertCell(1);
			cell2.innerHTML = " Rate ";
			cell2.setAttribute('class', "label");
			cell2.align = "center";
			cell2.style.verticalAlign = "middle";
			
		var cell3	=	row2.insertCell(2);
			cell3.innerHTML = " Code ";
			cell3.setAttribute('class', "label");
			cell3.align = "center";
			cell3.style.verticalAlign = "middle";
			
		var cell4	=	row2.insertCell(3);
			cell4.innerHTML = " Rate ";
			cell4.setAttribute('class', "label");
			cell4.align = "center";
			cell4.style.verticalAlign = "middle";
		
		var td_cell = 4; var i;
		var date_pick_class = "extraItemTextbox  date-picker";
		for(i=0; i<no_of_month; i++)
		{
			var cell5=row2.insertCell(td_cell);
			var txt_box5 = document.createElement("input");
				txt_box5.name = "txt_month[]";
				txt_box5.id = "txt_month"+i;
				txt_box5.value = "";
				txt_box5.setAttribute('class', date_pick_class); 
				cell5.appendChild(txt_box5);
			td_cell++;
			var date_pick_class = "extraItemTextbox  date-picker-disable";
		} 
		
		var cell6	=	row2.insertCell(td_cell);
			cell6.innerHTML = " Code ";
			cell6.setAttribute('class', "label");
			cell6.align = "center";
			cell6.style.verticalAlign = "middle";
			td_cell++;
			
		var cell7	=	row2.insertCell(td_cell);
			cell7.innerHTML = " Rate ";
			cell7.setAttribute('class', "label");
			cell7.align = "center";
			cell7.style.verticalAlign = "middle";
			td_cell++;
		
		find_baseindex();
		index++;
		   $('.date-picker').datepicker({
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true,
				dateFormat: 'M-yy',
				onClose: function(dateText, inst) { 
					$(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
					var no_of_month = document.getElementById("txt_no_of_month").value;
					var month_count = 1;
					for(var k = 0; k<no_of_month-1; k++)
					{
						var obj_date_field_id = document.getElementsByClassName('date-picker-disable')[k];
						var date_field_id = obj_date_field_id.id;
						//$("#"+date_field_id).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth+month_count, 1));
						$("#"+date_field_id).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth+month_count, 1));
						month_count++;
					}
				}
			});
			$('.date-picker-disable').datepicker({
				//changeMonth: true,
				//changeYear: true,
				//disabled:true,
				//showButtonPanel: true,
				dateFormat: 'M-yy'
			});
	 }*/
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
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">

                <div class="container_12">
                    <div class="grid_12">

						<div align="right"><a href="PriceIndexView_10CC.php">View</a>&nbsp;&nbsp;&nbsp;</div>
                        <blockquote class="bq1">
                            <div class="title">Price Index - 10CC (Revised)</div>
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
                        <table width="1078px" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="18%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Short Name</td> 
                                    <td>
										<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" style="width:465px" onChange="workorderdetail();AllQuarter();">
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
                                    <td>&nbsp;</td>
                                    <td class="label">Quarter</td>
                                    <td>
									<!--<input type="text" name='txt_quarter' id='txt_quarter' class="textboxdisplay" value="" style="width: 200px;">-->
									<select name="cmb_quarter" id="cmb_quarter" class="textboxdisplay" style="width: 200px;" onChange="getQtrDetails()">
										<option value="">--------------Select-------------</option>
									</select>
									&emsp;&emsp;&emsp;&nbsp;&emsp;&emsp;&emsp;
									<label class="label">RAB</label>
									&emsp;&emsp;
									<input type="text" name='txt_rbn' id='txt_rbn' class="textboxdisplay" value="" style="width: 100px;">
									<!--&emsp;&nbsp:&nbsp;
									<label class="label">No.of Month</label>
									&emsp;
									<input type="text" name='txt_no_of_month' id='txt_no_of_month' class="textboxdisplay" value="" style="width: 40px;" onBlur="SetMonthField();">-->
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_no_of_month" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
									<td colspan="3" align="center">
										<div style="width:85%;" class="label gradientbg" align="center"> Price Index</div>
										<div style="width:85%; height:auto;" align="center">
											<table width="100%" class="table1" id="table1">
												<tr class="label" style="background-color:#EAEAEA; height:35px;">
													<td align="center" valign="middle" nowrap="nowrap">&nbsp;&nbsp;</td>
													<td align="center" colspan="4" valign="middle" nowrap="nowrap">Floating Price Index</td>
													<td align="center" colspan="4" valign="middle" nowrap="nowrap">Fixed Price Index</td>
												</tr>
												<!--<tr class="label" style="background-color:#EAEAEA; height:35px;">
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextbox date-picker" name="txt_month[]" id="txt_month1">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextbox date-picker" name="txt_month[]" id="txt_month1">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextbox date-picker" name="txt_month[]" id="txt_month1">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextbox date-picker" name="txt_month[]" id="txt_month1">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextbox date-picker" name="txt_month[]" id="txt_month1">
													</td>
													<td align="center" valign="middle">
														<input type="text" class="extraItemTextbox date-picker" name="txt_month[]" id="txt_month1">
													</td>
													<td align="center" valign="middle">
														<input type="text" class="extraItemTextbox date-picker" name="txt_month[]" id="txt_month1">
													</td>
													<td align="center" valign="middle" nowrap="nowrap">
														<input type="text" class="extraItemTextbox date-picker" name="txt_month[]" id="txt_month1">
													</td>
													<td align="center" valign="middle">
														<input type="text" class="extraItemTextbox date-picker" readonly="" name="txt_month[]" id="txt_month2">
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
