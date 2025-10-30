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
{
   	$sheetid  				= $_POST['cmb_shortname'];
	$esc_from_date  		= dt_format($_POST['txt_from_date']);
	$esc_to_date  			= dt_format($_POST['txt_to_date']);
	$quarter  				= $_POST['cmb_quarter'];
	$esc_rbn  				= $_POST['txt_rbn'];
	$esc_id  				= $_POST['txt_esc_id'];
	$bid_arr  				= $_POST['txt_bid'];
	$base_index_code_arr  	= $_POST['txt_base_index_code'];
	$base_index_item_arr  	= $_POST['txt_base_index_item'];
	$esc_month_arr  		= $_POST['txt_esc_month'];
	$qty_month_wise_mt_arr  = $_POST['txt_qty_month_wise'];
	$base_index_rate_arr  	= $_POST['txt_base_index_rate'];
	$base_price_rate_arr  	= $_POST['txt_base_price_rate'];
	$pi_rate_arr  			= $_POST['txt_pi_rate'];
	$esc_amount_arr  		= $_POST['txt_esc_amount'];
	$esc_item_type_arr		= $_POST['txt_esc_item_type'];
	$count = count($bid_arr);
	if($count>0)
	{
		$delete_tca_query = "delete from escalation_10ca_details where sheetid = '$sheetid' and esc_rbn = '$esc_rbn' and esc_id = '$esc_id' and quarter = '$quarter'";
		//echo $delete_tca_query;
		$delete_tca_sql = mysql_query($delete_tca_query);
	}
	for($i=0; $i<$count; $i++)
	{
		$bid  				= $bid_arr[$i];
		$base_index_code  	= $base_index_code_arr[$i];
		$base_index_item  	= $base_index_item_arr[$i];
		$esc_month 			= $esc_month_arr[$i];
		$qty_month_wise_mt  = $qty_month_wise_mt_arr[$i];
		$base_index_rate  	= $base_index_rate_arr[$i];
		$base_price_rate  	= $base_price_rate_arr[$i];
		$pi_rate  			= $pi_rate_arr[$i];
		$esc_amount  		= $esc_amount_arr[$i];
		$esc_item_type 		= $esc_item_type_arr[$i];
		$insert_tca_query = "insert into escalation_10ca_details set 
											sheetid = '$sheetid',
											bid = '$bid',
											quarter = '$quarter', 
											esc_rbn = '$esc_rbn',
											esc_id = '$esc_id',
											esc_item = '$base_index_item',
											esc_item_code = '$base_index_code',
											esc_month = '$esc_month',
											esc_qty = '$qty_month_wise_mt',
											base_index = '$base_index_rate',
											base_price = '$base_price_rate',
											price_index = '$pi_rate',
											esc_amount = '$esc_amount',
											esc_from_date = '$esc_from_date',
											esc_to_date = '$esc_to_date',
											esc_item_type = '$esc_item_type',
											esc_created_date = NOW(),
											staffid = '$staffid',
											active = '1'";
											//echo $insert_tca_query."<br/>";
			$insert_tca_sql = mysql_query($insert_tca_query);
	}
	//print_r($bid_arr);
	//exit;
	//header('Location: EscalationCalculation.php');
} 
?>

  <?php require_once "Header.html"; ?>
<style>
    
</style>
 <script>
	var add_row_s 		= 2;
	var prev_edit_row 	= 0;
	var color_var = 1;
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
                            //document.form.txt_workname.value 	= name[3];
							document.form.txt_workorder.value 	= name[5];
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
		function GetEscQuarterRBN()
     	{ 
            var xmlHttp;
            var data;
            var i, j;
			document.form.cmb_quarter.length = 0;
			document.getElementById("txt_rbn").value  = "";
			var optn = document.createElement("option");
				optn.value = "";
				optn.text = "------- Select -------";
			document.form.cmb_quarter.options.add(optn);
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "find_EscQuarterRBN.php?sheetid=" + document.form.cmb_shortname.value;
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
                        var name 		= data.split("@");
						var rbn 		= name[0];
						var QtrStr 		= name[1];
						document.getElementById("txt_rbn").value  = rbn;
						var SplitQtrStr = QtrStr.split("*");
						document.form.cmb_quarter.length = 0;
						var optn = document.createElement("option");
						optn.value = "";
						optn.text = "------- Select -------";
						document.form.cmb_quarter.options.add(optn);
                        for(i = 0; i < SplitQtrStr.length; i++)
                        {
							//document.form.txt_techsanction.value 	= name[0];
							//document.form.txt_agreemntno.value 		= name[2];
                            //document.form.txt_workname.value 		= name[3];
							//document.form.txt_workorder.value 		= name[5];
							var optn = document.createElement("option")
							optn.value = SplitQtrStr[i];
							optn.text = SplitQtrStr[i];
							document.form.cmb_quarter.options.add(optn)  
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
		function getEscalationItem()
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
            strURL = "find_escalation_item.php?sheetid="+document.form.cmb_shortname.value+"&type=TCA";
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText;
					
					document.form.cmb_10CA.length = 0;
					var optn 	= document.createElement("option")
					optn.value  = "";
					optn.text 	= "-------------------------- Select -----------------------------";
					document.form.cmb_10CA.options.add(optn)
					
                    if (data == "")
                    {
                        //alert("No Records Found");
                    }
                    else
                    {
                        var name = data.split("*@*");
                        for(i = 0; i < name.length; i+=3)
                        {
							var bid 		  = name[i+0];
							var esc_item_desc = name[i+1];
							var esc_item_code = name[i+2];
							var optn1 	= document.createElement("option");
							optn1.value = bid;
							optn1.text 	= esc_item_desc;
							optn1.setAttribute('data-code', esc_item_code);
							document.form.cmb_10CA.options.add(optn1);
                        }
                    }
                }
            }
            xmlHttp.send(strURL);
        }
		
		function find_priceindex_period()
     	{ 
			document.getElementById("txt_from_date").value  = "";
			document.getElementById("txt_to_date").value  = "";
			document.getElementById("txt_esc_id").value = "";
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
			
			var sheetid = document.form.cmb_shortname.value;
			var quarter = document.form.cmb_quarter.value;
			var rbn 	= document.form.txt_rbn.value;
            //strURL = "find_priceindex_period.php?sheetid=" + document.form.cmb_shortname.value+"&type=TCA&base_index_code=CIo";/// This is for Cement - So base_index_code is CIo;
            strURL = "find_priceindex_period.php?sheetid="+sheetid+"&quarter="+quarter+"&rbn="+rbn;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText;
					//alert(data);
                    if (data != "")
                    {
                        var name = data.split("*@*");
						var esc_id 		 	= name[0];
						var tcc_fromdate 	= name[1];
						var tcc_todate 		= name[2];
						var tca_fromdate 	= name[3];
						var tca_todate  	= name[4];
						
						
						
						/*var pid 		 = name[0];
						var bid 		 = name[1];
						var pi_from_date = name[2];
						var pi_to_date 	 = name[3];
						var avg_pi_code  = name[4];
						var avg_pi_rate  = name[5];
						var type 		 = name[6];
						var quarter		 = name[7];
						var rbn		 	 = name[8];
						var esc_id		 = name[9];*/
						//alert(pi_from_date)
						document.getElementById("txt_from_date").value  = tca_fromdate;
						document.getElementById("txt_to_date").value  = tca_todate;
						//document.getElementById("txt_quarter").value  = quarter;
						//document.getElementById("txt_rbn").value  = rbn;
						document.getElementById("txt_esc_id").value  = esc_id;
						//document.getElementById("txt_price_index_rate_m3"+bid).value  = pi_rate3;
						//document.getElementById("txt_avg_price_index_code"+bid).value = avg_pi_code;
						//document.getElementById("txt_avg_price_index_rate"+bid).value = avg_pi_rate;

                    }
                }
            }
            xmlHttp.send(strURL);
     	}
		const Calculated = [];
		function get10CA_data()
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
			var sheetid	=	document.form.cmb_shortname.value;
			var bid		=	document.form.cmb_10CA.value;
			var type	=	"TCA";
			var fromdate=	document.form.txt_from_date.value;
			var todate	=	document.form.txt_to_date.value;
			var quarter	=	document.form.cmb_quarter.value;
			var sel1 	= 	document.getElementById('cmb_10CA');
			var sel2 	= 	sel1.options[sel1.selectedIndex];
			var code 	= 	sel2.getAttribute('data-code');
			var StopExe = 0;
			document.form.cmb_10CA.value = '';
			Calculated.forEach(async function(Calc) {
				if(Calc == code){
					StopExe = 1;
				}
			})
			if(StopExe == 1){
				swal("Selected material is already calculated");
				return false;
				exit();
			}else{
				Calculated.push(code);
			}
			
            strURL = "find_escalation_10CA_data.php?sheetid="+sheetid+"&bid="+bid+"&type=TCA&fromdate="+fromdate+"&todate="+todate+"&quarter="+quarter+"&code="+code;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText;
					//alert(data);
                    if (data == "")
                    {
                       // alert("No Records Found");
                    }
                    else
                    {
                        var res 		= data.split("@@##@@");
						var tca_data	= res[0];
						var month_count = res[1];
						//alert(month_count)
						var name 		= tca_data.split("*@*");
						//alert(tca_data)
						if(color_var % 2 == 0)
						{
							var color = "#FAEFF6";
						}
						else
						{
							var color = "#D1F8F7";
						}
                        for(i = 0; i < name.length; i+=14)
                        {
							var bid 		  		= name[i+0];
							var pid 				= name[i+1];
							var base_index_item 	= name[i+2];
							var esc_month 			= name[i+3];
							var base_index_rate 	= name[i+4];
							var base_index_code 	= name[i+5];
							var pi_rate 			= name[i+6];
							var pi_code 			= name[i+7];
							var base_price_rate 	= name[i+8];
							var base_price_code 	= name[i+9];
							var qty_month_wise 		= name[i+10];
							var decimal_placed 		= name[i+11];
							var qty_month_wise_mt 	= name[i+12];
							var esc_item_type 		= name[i+13];
							
							var first_ltr_bi_item 		= base_index_item.charAt(0);
							var first_ltr_1 			= first_ltr_bi_item.toLowerCase()
							var tca_formula 			= base_price_code+" x "+"Q"+first_ltr_1+" x ("+pi_code+" - "+base_index_code+")/"+base_index_code;
							var tca_formula_with_val 	= base_price_rate+" x "+qty_month_wise_mt+" x ("+pi_rate+" - "+base_index_rate+")/"+base_index_rate;
							
							var esc_amount = Number(base_price_rate)*Number(qty_month_wise_mt)*(Number(pi_rate)-Number(base_index_rate))/Number(base_index_rate);
								esc_amount = esc_amount.toFixed(2);
							
							var new_row = document.getElementById("table2").insertRow(add_row_s);
							new_row.setAttribute("id", "row_" + add_row_s)
							new_row.className = "labelsmall";
							new_row.style.height = "30px";
							new_row.style.verticalAlign = "middle";
							new_row.style.backgroundColor  = color;
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
								c9.align = "right";
								c9.style.className = "extraItemTextbox";
								c1.innerHTML = base_index_item+"";
								c2.innerHTML = esc_month;
								c3.innerHTML = qty_month_wise_mt+" <font color='#D50E04'>(Q"+first_ltr_1+")</font>";
								c4.innerHTML = base_index_rate+" <font color='#D50E04'>("+base_index_code+")</font>";
								c5.innerHTML = base_price_rate+" <font color='#D50E04'>("+base_price_code+")</font>";
								c6.innerHTML = pi_rate+" <font color='#D50E04'>("+pi_code+")</font>";
								c7.innerHTML = "<font color='#D50E04'>"+tca_formula+"</font>";
								c8.innerHTML = tca_formula_with_val;
								c9.innerHTML = esc_amount+"&nbsp;";
							
							
							hide_values = "<input type='hidden' value='" + bid + "' name='txt_bid[]' id='txt_bid" + add_row_s + "' >";
							hide_values += "<input type='hidden' value='" + base_index_item + "' name='txt_base_index_item[]' id='txt_base_index_item" + add_row_s + "' >";
							hide_values += "<input type='hidden' value='" + esc_month + "' name='txt_esc_month[]' id='txt_esc_month" + add_row_s + "' >";
							hide_values += "<input type='hidden' value='" + base_index_rate + "' name='txt_base_index_rate[]' id='txt_base_index_rate" + add_row_s + "' >";
							hide_values += "<input type='hidden' value='" + base_index_code + "' name='txt_base_index_code[]' id='txt_base_index_code" + add_row_s + "' >";
							hide_values += "<input type='hidden' value='" + pi_rate + "' name='txt_pi_rate[]' id='txt_pi_rate" + add_row_s + "' >";
							hide_values += "<input type='hidden' value='" + pi_code + "' name='txt_pi_code[]' id='txt_pi_code" + add_row_s + "' >";
							hide_values += "<input type='hidden' value='" + base_price_rate + "' name='txt_base_price_rate[]' id='txt_base_price_rate" + add_row_s + "' >";
							hide_values += "<input type='hidden' value='" + base_price_code + "' name='txt_base_price_code[]' id='txt_base_price_code" + add_row_s + "' >";
							hide_values += "<input type='hidden' value='" + qty_month_wise_mt + "' name='txt_qty_month_wise[]' id='txt_qty_month_wise" + add_row_s + "' >";
							hide_values += "<input type='hidden' value='" + esc_amount + "' name='txt_esc_amount[]' id='txt_esc_amount" + add_row_s + "' >";
							hide_values += "<input type='hidden' value='" + esc_item_type + "' name='txt_esc_item_type[]' id='txt_esc_item_type" + add_row_s + "' >";
							
							document.getElementById("add_hidden").innerHTML = document.getElementById("add_hidden").innerHTML + hide_values;
							//if(document.getElementById("add_set_a1").value == "")
							//{
								//document.getElementById("add_set_a1").value = add_row_s;
							//}
							//else
							//{
								//document.getElementById("add_set_a1").value = document.getElementById("add_set_a1").value + "." + add_row_s;
							//}
							
							add_row_s++;
                        }
                    }
                }
            }
            xmlHttp.send(strURL);
			color_var++;
        }
</script>
<script>
   $(function(){
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
		/*$("#calculate").click(function(event){
			$("#myDiv").load(location.href+" #myDiv>*","");
		});*/
		
		
		$("#cmb_shortname").change(function(event){
			$(this).validateshortname(event);
		});
		$("#txt_workorder").keyup(function(event){
			$(this).validateworkorder(event);
		});
		$("#top").submit(function(event){
		$(this).validateshortname(event);
		$(this).validateworkorder(event);
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
.hide
{
	display:none;
}
sub {font-size:xx-small; vertical-align:sub;}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                            <div class="title">Escalation Caluculation - 10 CA</div>
                <div class="container_12">
                    <div class="grid_12">

						<!--<div align="right"><a href="AgreementEntryView.php">View</a>&nbsp;&nbsp;&nbsp;</div>-->
                        <blockquote class="bq1" style="overflow:auto">
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
							<div style="width:100%;" align="center" id="myDiv1">
								<table width="100%" class="color1" id="table1">
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td align="left" valign="middle" nowrap="nowrap" class="label">
											&emsp;&emsp;Work Short Name
										</td>
										<td align="center" valign="middle">
											<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" style="width:350px;" onChange="workorderdetail();GetEscQuarterRBN();getEscalationItem();">
												<option value="">--------------- Select ---------------</option>
												<?php echo $objBind->BindWorkOrderNo(0);?>
											</select>
										</td>
										<td align="center" valign="middle" class="label">
											Work Order No.
										</td>
										<td align="center" valign="middle">
											<input type="text" name='txt_workorder' id='txt_workorder' class="textboxdisplay" style="width:350px;">
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td align="left" class="label">&emsp;&emsp;RAB</td>
										<td align="">
										&emsp;&emsp;&nbsp;&emsp;&emsp;&emsp;&emsp;&emsp;
											<input type="text" name='txt_rbn' id='txt_rbn' class="textboxdisplay" style="width:100px;">
										&nbsp;&emsp;&nbsp;
										<label class="label">Quarter</label>
										&emsp;
											<select name="cmb_quarter" id="cmb_quarter" style="width:143px;" class="textboxdisplay" onChange="find_priceindex_period();">
												<option value="">------- Select -------</option>
											</select>
											<input type="hidden" name='txt_esc_id' id='txt_esc_id' class="textboxdisplay" style="width:60px;">
										</td>
										<td align="center" valign="middle" class="label">
											From Date
										</td>
										<td align="center" valign="middle">
											<input type="text" name='txt_from_date' id='txt_from_date' class="textboxdisplay date-picker" style="width:115px;">
											&emsp;&emsp;&nbsp;<label class="label">To Date</label> &emsp;
											<input type="text" name='txt_to_date' id='txt_to_date' class="textboxdisplay date-picker" style="width:115px;">
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td align="left" valign="middle" nowrap="nowrap" class="label">
											&emsp;&emsp;Select For
										</td>
										<td align="center" valign="middle" nowrap="nowrap">
											<select name='cmb_10CA' id='cmb_10CA' class="textboxdisplay" style="width: 350px;">
												<option value="">-------------------------- Select -----------------------------</option>
											</select>
										</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
								</table>
							</div>
							<!--<div style="width:100%; height:100px;" align="center" id="myDiv2">
								<table width="80%" class="table1" id="table1">
									<tr class="label" style="background-color:#CECECE; height:30px;">
										<td align="center" valign="middle" nowrap="nowrap">Sl.No.</td>
										<td align="center" valign="middle" nowrap="nowrap">Mar-2016</td>
										<td align="center" valign="middle" nowrap="nowrap">Apr-2016</td>
										<td align="center" valign="middle" nowrap="nowrap">May-2016</td>
									</tr>
									<tr class="labelsmall" id="tr_cement">
										<td align="center" valign="middle" nowrap="nowrap">Cement Consumption (Qc)</td>
										<td align="center" valign="middle" nowrap="nowrap">60.900</td>
										<td align="center" valign="middle" nowrap="nowrap">39.000</td>
										<td align="center" valign="middle" nowrap="nowrap">42.000</td>
									</tr>
									<tr class="labelsmall hide" id="tr_steel">
										<td align="center" valign="middle" nowrap="nowrap">Steel Consumption (Qs)</td>
										<td align="center" valign="middle" nowrap="nowrap">60.900</td>
										<td align="center" valign="middle" nowrap="nowrap">39.000</td>
										<td align="center" valign="middle" nowrap="nowrap">42.000</td>
									</tr>
									<tr class="labelsmall hide" id="tr_ssteel">
										<td align="center" valign="middle" nowrap="nowrap">Structural Steel Consumption (Qst)</td>
										<td align="center" valign="middle" nowrap="nowrap">1.000</td>
										<td align="center" valign="middle" nowrap="nowrap">2.000</td>
										<td align="center" valign="middle" nowrap="nowrap">3.000</td>
									</tr>
								</table>
							</div>-->
							<div style="width:100%; height:50px;" align="center" id="myDiv3">
								<table width="80%" class="color1 label" id="table1">
									<tr>
										<td colspan="4" align="center" height="35px" valign="middle">
										<input type="button" class="backbutton" name="calculate" id="calculate" value="Calculate" onClick="get10CA_data();"/></td>
									</tr>
								</table>
							</div>
							<div style="width:100%;" align="center" id="myDiv">
								<table width="100%" class="table1" id="table2">
									<tr class="label gradientbg" style=" height:35px;">
										<!--<td align="center" valign="middle" nowrap="nowrap">Sl.No.</td>-->
										<td align="center" valign="middle" nowrap="nowrap">Description</td>
										<td align="center" valign="middle" nowrap="nowrap">Month</td>
										<td align="center" valign="middle" nowrap="nowrap"> Qty.<br/>in mt. </td>
										<td align="center" valign="middle">Base <br/>Index</td>
										<td align="center" valign="middle">Base <br/>Price</td>
										<td align="center" valign="middle">Price <br/>Index</td>
										<td align="center" valign="middle">Formula</td>
										<td align="center" valign="middle">Formula with Values</td>
										<td align="center" valign="middle" nowrap="nowrap">Amount &nbsp;<i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></i></td>
									</tr>
									<tr>
                                       <span id="add_hidden"></span>
									</tr>
								</table>
							</div>
							<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
								<div class="buttonsection">
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
								</div>
								<div class="buttonsection">
									<input type="submit" name="submit" id="submit" value=" Submit "/>
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
