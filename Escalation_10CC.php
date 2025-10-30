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
	$sc = 0;
   	$sheetid  				= $_POST['cmb_shortname'];
	$esc_from_date  		= dt_format($_POST['txt_from_date']);
	$esc_to_date  			= dt_format($_POST['txt_to_date']);
	$quarter  				= $_POST['cmb_quarter'];
	$esc_rbn				= $_POST['txt_rbn'];
	$esc_id  				= $_POST['txt_esc_id'];
	
	$bid_arr  				= $_POST['txt_bid'];
	$count1 = count($bid_arr);
	if($count1>0)
	{
		$delete_tcc_query 	= 	"delete t1, t2 from escalation_tcc t1 
								JOIN escalation_tcc_details t2 ON t1.esc_tcc_id = t2.esc_tcc_id 
								where t1.sheetid = '$sheetid' and t2.sheetid = '$sheetid' and t1.esc_rbn='$esc_rbn'
								and t1.esc_id='$esc_id' and t1.quarter = '$quarter'";
		$delete_tcc_sql = mysql_query($delete_tcc_query);
	}

	for($i=0; $i<$count1; $i++)
	{
		$bid = $bid_arr[$i];
		$base_index_item 	= $_POST['txt_base_index_item'.$bid];
		$base_index_code  	= $_POST['txt_base_index_code'.$bid];
		$base_index_rate  	= $_POST['txt_base_index_rate'.$bid];
		$base_breakup_perc  = $_POST['txt_base_breakup_perc'.$bid];
		$avg_pi_rate  		= $_POST['txt_avg_pi_rate'.$bid];
		$total_rbn_amount  	= $_POST['txt_total_rbn_amount'.$bid];
		$esc_amount  		= $_POST['txt_tcc_esc_amt'.$bid];
		
		
		$esc_month_arr		= $_POST['txt_pi_month'.$bid];
		$pi_rate_arr		= $_POST['txt_pi_rate'.$bid];
		$count3 = count($esc_month_arr);
		$PriceIndexArr = array();
		for($k=0; $k<$count3; $k++)
		{
			$pi_rate 	= $pi_rate_arr[$k];
			$pi_month 	= $esc_month_arr[$k];
			$PriceIndexArr[$pi_month] = $pi_rate;
			//echo $pi_rate."<br/>";
			//echo $pi_month."<br/>";
		}
		//echo $count3."<br/>";
		//print_r($esc_month_arr); 
		$abs_mon_yr_arr			= $_POST['txt_abs_mon_yr'];
		$abs_rbn_arr			= $_POST['txt_abs_rbn'];
		$abs_mbookno_arr		= $_POST['txt_abs_mbookno'];
		$abs_mbpage_arr			= $_POST['txt_abs_mbpage'];
		$abs_upto_date_amt_arr	= $_POST['txt_abs_upto_date_amt'];
		$abs_dpm_amt_arr		= $_POST['txt_abs_dpm_amt'];
		$abs_rbn_amt_arr		= $_POST['txt_abs_rbn_amt'];
		$abs_sa_amt_paid_arr	= $_POST['txt_abs_sa_amt_paid'];
		$abs_sa_amt_recd_arr	= $_POST['txt_abs_sa_amt_recd'];
		$abs_sa_amt_esc_arr		= $_POST['txt_abs_sa_amt_esc'];
		$adv_pay_made_arr		= $_POST['txt_adv_pay_made'];
		$adv_pay_recd_arr		= $_POST['txt_adv_pay_recd'];
		$adv_pay_esc_arr		= $_POST['txt_adv_pay_esc'];
		$extra_item_arr			= $_POST['txt_extra_item'];
		$net_amt_value_arr		= $_POST['txt_M_value'];
		$net_amt_85_prec_arr	= $_POST['txt_N_value'];
		$less_mat_arr			= $_POST['txt_less_mat'];
		$abs_wr_amt_arr			= $_POST['txt_abs_wr_amt'];
		$abs_er_amt_arr			= $_POST['txt_abs_er_amt'];
		$esc_net_amt_arr		= $_POST['txt_net_amt'];
		
		
		//print_r($abs_er_amt);
		//exit;
		$insert_tcc_query 	= "insert into escalation_tcc set sheetid = '$sheetid', bid = '$bid', esc_item = '$base_index_item', esc_item_code = '$base_index_code', 
							base_index = '$base_index_rate', esc_breakup_perc = '$base_breakup_perc', avg_pi_rate = '$avg_pi_rate', quarter = '$quarter', esc_rbn = '$esc_rbn',
							esc_id = '$esc_id', esc_amount = '$esc_amount', esc_from_date = '$esc_from_date', esc_to_date = '$esc_to_date', staffid = '$staffid', modifieddate = NOW(), 
							active = 1";
		//echo $insert_tcc_query."<br/>";
		$insert_tcc_sql 	= mysql_query($insert_tcc_query);
		if($insert_tcc_sql == true)
		{
			$sc++;
		}
		$esc_tcc_id 		= mysql_insert_id();
		$count2 = count($abs_mon_yr_arr);
		for($j=0; $j<$count2; $j++)
		{
			//$esc_month		= $esc_month_arr[$j];
			//$rbn			= $txt_abs_rbn[$j];
			//$mbookno		= $mbookno_arr[$j];
			//$mbookpage		= $mbookpage_arr[$j];
			//$rbn_amount		= $rbn_amount_arr[$j];
			$abs_mon_yr			= $abs_mon_yr_arr[$j];
			$abs_rbn			= $abs_rbn_arr[$j];
			$abs_mbookno		= $abs_mbookno_arr[$j];
			$abs_mbpage			= $abs_mbpage_arr[$j];
			$abs_upto_date_amt	= $abs_upto_date_amt_arr[$j];
			$abs_dpm_amt		= $abs_dpm_amt_arr[$j];
			$abs_rbn_amt		= $abs_rbn_amt_arr[$j];
			$abs_sa_amt_paid	= $abs_sa_amt_paid_arr[$j];
			$abs_sa_amt_recd	= $abs_sa_amt_recd_arr[$j];
			$abs_sa_amt_esc		= $abs_sa_amt_esc_arr[$j];
			$adv_pay_made		= $adv_pay_made_arr[$j];
			$adv_pay_recd		= $adv_pay_recd_arr[$j];
			$adv_pay_esc		= $adv_pay_esc_arr[$j];
			$extra_item			= $extra_item_arr[$j];
			$net_amt_value		= $net_amt_value_arr[$j];
			$net_amt_85_prec	= $net_amt_85_prec_arr[$j];
			$less_mat			= $less_mat_arr[$j];
			$abs_wr_amt			= $abs_wr_amt_arr[$j];
			$abs_er_amt			= $abs_er_amt_arr[$j];
			$esc_net_amt		= $esc_net_amt_arr[$j];
			
			$pi_rate			= $PriceIndexArr[$abs_mon_yr];
			
			$insert_tcc_dt_query 	= "insert into escalation_tcc_details set esc_tcc_id = '$esc_tcc_id', 
										esc_month = '$abs_mon_yr', price_index = '$pi_rate', rbn = '$abs_rbn', mbookno = '$abs_mbookno', 
										mbpage = '$abs_mbpage', rbn_upto_date_amt = '$abs_upto_date_amt', rbn_dpm_amt = '$abs_dpm_amt',
										rbn_amt = '$abs_rbn_amt', sa_amt_paid = '$abs_sa_amt_paid', sa_amt_recd = '$abs_sa_amt_recd',
										sa_amt_esc = '$abs_sa_amt_esc', adv_pay_made = '$adv_pay_made', adv_pay_recd = '$adv_pay_recd',
										adv_pay_esc = '$adv_pay_esc', extra_item = '$extra_item', net_amt_value = '$net_amt_value',
										net_amt_85_prec = '$net_amt_85_prec', less_mat = '$less_mat', abs_wr_amt = '$abs_wr_amt',
										abs_er_amt = '$abs_er_amt', esc_net_amt = '$esc_net_amt',
										sheetid = '$sheetid', staffid = '$staffid', modifieddate = NOW(), 
										active = 1";
			$insert_tcc_dt_sql 	= mysql_query($insert_tcc_dt_query);
			//echo $insert_tcc_dt_query."<br/>";
			if($insert_tcc_dt_sql == true)
			{
				$sc++;
			}
		}
	}
	if($sc>0)
	{
		$msg = "10CC Escalation Saved Successfully";
		$success = 1;
	}
	else
	{
		$msg = "10CC Escalation Not Saved";
		$success = 0;
	}
	//exit;
	//header('Location: EscalationCalculation.php');
} 
?>

 <?php require_once "Header.html"; ?>
<style>
    
</style>
<link type='text/css' href='css/basic.css' rel='stylesheet' media='screen' />
<script type='text/javascript' src='js/basic_model_jquery.js'></script>
<script type='text/javascript' src='js/jquery.simplemodal.js'></script>
 <script>
	var add_row_s 		= 2;
	var add_row_s_x1 	= 2;
	var add_row_s_x1 	= 2;
	var prev_edit_row 	= 0;
	var color_var = 1;
  	 function goBack()
	 {
	   	url = "dashboard.php";
		window.location.replace(url);
	 }
	 function DeleteRows() 
	 {
        var rowCount = table3.rows.length;
        for (var i = rowCount - 1; i > 0; i--) {
            table3.deleteRow(i);
        }
		
		var rowCount2 = table2.rows.length;
        for (var j = rowCount2 - 1; j > 0; j--) {
            table2.deleteRow(j);
        }
     }
	 function CheckTccCalc(bid) 
	 {
        var calc_item_str 	= document.form.txt_item_calc.value;
		var split_calc_item = calc_item_str.split("*");
		var cnt = 0;
		for(var i=0; i<split_calc_item.length; i++)
		{
			var calc_item = split_calc_item[i];
			if(calc_item == bid)
			{
				//return false;
				//exit();
				cnt++;
			}
		}
		if(cnt == 0)
		{
			return 0;
		}
		else
		{
			return 1;
		}
     }
     function workorderdetail()
     { 
            var xmlHttp;
            var data;
            var i, j;
			DeleteRows();
			//document.getElementById("table3").rows.length = 0;
			document.getElementById("add_hidden_tcc").innerHTML = "";
			document.getElementById("add_hidden_rab").innerHTML = "";
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
                    if (data == "")
                    {
                        alert("No Records Found");
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
            strURL = "find_escalation_item.php?sheetid="+document.form.cmb_shortname.value+"&type=TCC";
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText;
					
					document.form.cmb_10CC.length = 0;
					var optn 	= document.createElement("option")
					optn.value  = "";
					optn.text 	= "-------------------------- Select -----------------------------";
					document.form.cmb_10CC.options.add(optn)
					
                    if (data == "")
                    {
                        alert("No Records Found");
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
							document.form.cmb_10CC.options.add(optn1);
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
		
		function get10CC_data()
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
			var bid		=	document.form.cmb_10CC.value;
			var type	=	"TCC";
			var fromdate=	document.form.txt_from_date.value;
			var todate	=	document.form.txt_to_date.value;
			var quarter	=	document.form.cmb_quarter.value;
			//alert("bef");
			var ret = CheckTccCalc(bid);
			//alert(ret);
            strURL = "find_escalation_10CC_data.php?sheetid="+sheetid+"&bid="+bid+"&type=TCC&fromdate="+fromdate+"&todate="+todate+"&quarter="+quarter;
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
                        alert("No Records Found");
                    }
                    else
                    {
                        var res1 				= data.split("@@##@@");
						var Rbn_data			= res1[0];
						var Pi_data 			= res1[1];
						var Bi_data 			= res1[2];
						var OverAllRowspanRBN 	= res1[3];
						var OverAllRowspanTCC 	= res1[4];
						var total_rbn_amount 	= res1[5];
						var testdata = Rbn_data;
						//if(Error_msg != "")
						//{
							//alert("XX");
						//}
						// The Below part is for Displaying RAB, AMOUNT and RECOVERY Calculation.
						var rbnCalc = document.form.txt_rbn_calc.value;
						var netamt_for_esc = document.form.txt_netamt_for_esc.value;
						if(rbnCalc == 0) /// This is for to display rbn calculation only one time because its common for both material and labour
						{
							var res3 = Rbn_data.split("##");
							var prev_abs_mon_yr = "";
							//var netamt_for_esc = 0;
							for(var k1=0; k1<res3.length; k1++)
							{
								var res4 = res3[k1];
								//alert(res4)
								var res5 = res4.split("*");
								var abs_mon_yr 			= res5[0];
								var abs_rbn 			= res5[1];
								var abs_mbookno 		= res5[2];
								var abs_mbpage 			= res5[3];
								var abs_rbn_amt 		= res5[4];
								if(abs_rbn_amt == "X" || abs_rbn_amt == ""){ abs_rbn_amt = 0; }
								//var abs_rbn_amt_85perc 	= res5[5];
								var abs_sa_amt 			= res5[6];
								if(abs_sa_amt == "X" || abs_sa_amt == ""){ abs_sa_amt = 0; }
								var abs_er_amt 			= res5[7];
								if(abs_er_amt == "X" || abs_er_amt == ""){ abs_er_amt = 0; }
								var abs_wr_amt 			= res5[8];
								if(abs_wr_amt == "X" || abs_wr_amt == ""){ abs_wr_amt = 0; }
								
								var rbn_row_span 		= res5[9];
								var mon_row_span 		= res5[10];
								var abs_upto_date_amt	= res5[11];
								var abs_dpm_amt 		= res5[12];
								
								var total_rab_amt 		= Number(abs_rbn_amt)+Number(abs_sa_amt);
									total_rab_amt 		= total_rab_amt.toFixed(2);
								var abs_rbn_amt_85perc 	= Number(total_rab_amt)*85/100;
									abs_rbn_amt_85perc	= abs_rbn_amt_85perc.toFixed(2);
								var net_amt 			= Number(abs_rbn_amt_85perc)-Number(abs_er_amt)-Number(abs_wr_amt);
									net_amt				= net_amt.toFixed(2);
								 	netamt_for_esc 		= Number(netamt_for_esc)+Number(net_amt);
									
								var total_recovery = Number(abs_er_amt)+Number(abs_wr_amt);
									total_recovery = total_recovery.toFixed(2);
									//alert(abs_rbn_amt);
								if(abs_rbn == "")
								{
									abs_rbn = " - ";
								}
								if(abs_mbookno == "")
								{
									abs_mbookno = " - ";
								}
								if(abs_mbpage == "")
								{
									abs_mbpage = " - ";
								}
								if(abs_sa_amt == "")
								{
									abs_sa_amt = "0.00";
								}
								if(abs_er_amt == "")
								{
									abs_er_amt = "0.00";
								}
								if(abs_rbn_amt == "")
								{
									abs_rbn_amt = "0.00";
									abs_rbn_amt_85perc = "0.00";
								}
								var x1 = Number(document.getElementById("table3").rows.length);
								add_row_s_x1 = x1;
								// 	Adding the New row in table
								var new_row = document.getElementById("table3").insertRow(add_row_s_x1);
								new_row.setAttribute("id", "row_" + add_row_s_x1)
								new_row.className = "labelsmall";
								new_row.style.height = "30px";
								new_row.style.verticalAlign = "middle";
								var cellno=0;
								if(prev_abs_mon_yr != abs_mon_yr)
								{
								var c1 = new_row.insertCell(cellno);
									c1.align = "center";
									c1.style.className = "extraItemTextbox";
									c1.rowSpan = mon_row_span;
									cellno++;
								}
								if(abs_rbn != "X")
								{
								var c2 = new_row.insertCell(cellno);
									c2.align = "center";
									c2.style.className = "extraItemTextbox";
									c2.rowSpan = rbn_row_span;
									cellno++;
								}
								if(abs_rbn != "X")
								{
								var c3 = new_row.insertCell(cellno);
									c3.align = "center";
									c3.style.className = "extraItemTextbox";
									c3.rowSpan = rbn_row_span;
									cellno++;
								}
								if(abs_rbn != "X")
								{
								var c4 = new_row.insertCell(cellno);
									c4.align = "center";
									c4.style.className = "extraItemTextbox";
									c4.rowSpan = rbn_row_span;
									cellno++;
								}
								if(abs_rbn != "X")
								{
								var c5 = new_row.insertCell(cellno);
									c5.align = "center";
									c5.style.className = "extraItemTextbox";
									c5.rowSpan = rbn_row_span;
									cellno++;
								}
								if(abs_rbn != "X")
								{
								var c6 = new_row.insertCell(cellno);
									c6.align = "center";
									c6.style.className = "extraItemTextbox"; 
									c6.rowSpan = rbn_row_span;
									cellno++;
								}
								if(abs_rbn != "X")
								{
								var c7 = new_row.insertCell(cellno);
									c7.align = "center";
									c7.style.className = "extraItemTextbox";
									c7.rowSpan = rbn_row_span;
									cellno++;
								}
								if(abs_rbn != "X")
								{
								var c8 = new_row.insertCell(cellno);
									c8.align = "center";
									c8.style.className = "extraItemTextbox";
									c8.rowSpan = rbn_row_span;
									cellno++;
								}	
								if(abs_rbn != "X")
								{
								var c9 = new_row.insertCell(cellno);
									c9.align = "center";
									c9.style.className = "extraItemTextbox";
									c9.rowSpan = rbn_row_span;
									cellno++;
								}
								if(abs_rbn != "X")
								{	
								var c10 = new_row.insertCell(cellno);
									c10.align = "center";
									c10.style.className = "extraItemTextbox";
									c10.rowSpan = rbn_row_span;
									cellno++;
								}
								
								if(prev_abs_mon_yr != abs_mon_yr)
								{
								c1.innerHTML = abs_mon_yr+"";
								}
								if(abs_rbn != "X")
								{
								c2.innerHTML = abs_rbn;
								c3.innerHTML = abs_mbookno;
								c4.innerHTML = abs_mbpage;
								c5.innerHTML = abs_rbn_amt;
								c6.innerHTML = abs_sa_amt;
								c7.innerHTML = total_rab_amt;
								c8.innerHTML = abs_rbn_amt_85perc;//Number(abs_rbn_amt)*85/100;
								c9.innerHTML = total_recovery;
								c10.innerHTML = net_amt;
								}
								/*if(k1 == 0)
								{
								c7.innerHTML = abs_rbn_amt;
								c8.innerHTML = "(a+b)x(a-b)";
								c9.innerHTML = rbn_row_span;
								c10.innerHTML = mon_row_span;
								}*/
								var prev_abs_mon_yr = abs_mon_yr;
								add_row_s_x1++;
								
							}
							// For getting Last row total
							netamt_for_esc = netamt_for_esc.toFixed(2);
							var x3 = Number(document.getElementById("table3").rows.length);
								add_row_s_x3 = x3;
								// 	Adding the Last Total row in table
								var new_row3 = document.getElementById("table3").insertRow(add_row_s_x3);
								new_row3.setAttribute("id", "row_" + add_row_s_x3)
								new_row3.className = "labelsmall";
								new_row3.style.height = "30px";
								new_row3.style.verticalAlign = "middle";
								var cellno=0;
								var LC1 = new_row3.insertCell(cellno);
									LC1.align = "right";
									LC1.style.className = "extraItemTextbox";
									LC1.colSpan = 7;
									cellno++;
								var LC2 = new_row3.insertCell(cellno);
									LC2.align = "right";
									LC2.style.className = "extraItemTextbox";
									LC2.colSpan = 3;
									cellno++;
									LC1.innerHTML = "&nbsp;&nbsp;&nbsp;<label class='labelstyle' name='test' id='test' onClick='get10CC_data_dialog()'><u>View Details</u></label>&nbsp;&nbsp;&nbsp;";
									LC2.innerHTML = "(W) : &nbsp;&nbsp;&nbsp;"+netamt_for_esc+"&nbsp;&nbsp;&nbsp;";
								add_row_s_x3++;
							document.form.txt_netamt_for_esc.value = netamt_for_esc;
							document.form.txt_rbn_calc.value = 1;
						}
						
						// The Below part is for Displaying Base Index, Price Index and Formula Calculation.
						if(ret == 0)
						{
							var res2 				= Bi_data.split("*");
							var bi_month 			= res2[0];
							var bid 				= res2[1];
							var base_index_item 	= res2[2];
							var base_index_code 	= res2[3];
							var base_index_rate 	= res2[4];
							var base_breakup_code 	= res2[5];
							var base_breakup_perc 	= res2[6];
							var avg_pi_code 		= res2[7];
							var avg_pi_rate 		= res2[8];
							var month_row_span 		= res2[9];
							
							
							var first_ltr_bi_item 		= base_index_item.charAt(0);
							var first_ltr_1 			= first_ltr_bi_item.toLowerCase()
							var tcc_formula 			= "W x ("+base_breakup_code+"/100) x<br/> ("+avg_pi_code+"-"+base_index_code+")/"+base_index_code;
							var tcc_formula_with_val 	= netamt_for_esc+" x ("+base_breakup_perc+"/100) x<br/> ("+avg_pi_rate+"-"+base_index_rate+")/"+base_index_rate;
							var tcc_amt 				= Number(netamt_for_esc) * (Number(base_breakup_perc)/100) * (Number(avg_pi_rate)-Number(base_index_rate))/Number(base_index_rate);
								tcc_amt = tcc_amt.toFixed(2);
							var calc_item = document.form.txt_item_calc.value;
							document.form.txt_item_calc.value = bid+"*"+calc_item;
							
							var res6 				= Pi_data.split("##");
							for(var k2=0; k2<res6.length; k2++)
							{
								var res7 				= res6[k2];
								var res8 				= res7.split("*");
								
								var pi_month 			= res8[0];
								var base_index_item 	= res8[1];
								var base_index_code 	= res8[2];
								var pi_rate 			= res8[3];
								var pid 				= res8[4];
								var pi_month_row_span 	= res8[5];
								
								var x2 = Number(document.getElementById("table2").rows.length);
								add_row_s_x2 = x2;
								// 	Adding the New row in table
								var new_row2 = document.getElementById("table2").insertRow(add_row_s_x2);
								new_row2.setAttribute("id", "row_" + add_row_s_x2)
								new_row2.className = "labelsmall";
								new_row2.style.height = "30px";
								new_row2.style.verticalAlign = "middle";
								
								if(k2 == 0)
								{
								var cc1 = new_row2.insertCell(0);
									cc1.align = "center";
									cc1.style.className = "extraItemTextbox";
									cc1.rowSpan = OverAllRowspanTCC;
								var cc2 = new_row2.insertCell(1);
									cc2.align = "center";
									cc2.style.className = "extraItemTextbox"; 
								var cc3 = new_row2.insertCell(2);
									cc3.align = "center";
									cc3.style.className = "extraItemTextbox";
									cc3.rowSpan = OverAllRowspanTCC;
								var cc4 = new_row2.insertCell(3);
									cc4.align = "center";
									cc4.style.className = "extraItemTextbox";
									cc4.rowSpan = OverAllRowspanTCC;
								var cc5 = new_row2.insertCell(4);
									cc5.align = "center";
									cc5.style.className = "extraItemTextbox";
									cc5.rowSpan = OverAllRowspanTCC;
								var cc6 = new_row2.insertCell(5);
									cc6.align = "center";
									cc6.style.className = "extraItemTextbox"; 
								var cc7 = new_row2.insertCell(6);
									cc7.align = "center";
									cc7.style.className = "extraItemTextbox";
									cc7.rowSpan = OverAllRowspanTCC;
								var cc8 = new_row2.insertCell(7);
									cc8.align = "center";
									cc8.style.className = "extraItemTextbox";
									cc8.rowSpan = OverAllRowspanTCC; 
								var cc9 = new_row2.insertCell(8);
									cc9.align = "center";
									cc9.style.className = "extraItemTextbox";
									cc9.rowSpan = OverAllRowspanTCC;
								var cc10 = new_row2.insertCell(9);
									cc10.align = "center";
									cc10.style.className = "extraItemTextbox";
									cc10.rowSpan = OverAllRowspanTCC;
									
								cc1.innerHTML = base_index_item;
								cc2.innerHTML = pi_month;
								cc3.innerHTML = netamt_for_esc;
								cc4.innerHTML = base_index_rate+"<br/>( "+base_index_code+" )";
								cc5.innerHTML = base_breakup_perc+"<br/>( "+base_breakup_code+" )";
								cc6.innerHTML = pi_rate;
								cc7.innerHTML = avg_pi_rate+"<br/>( "+avg_pi_code+" )";
								cc8.innerHTML = tcc_formula;
								cc9.innerHTML = tcc_formula_with_val;
								cc10.innerHTML = tcc_amt;
								var hide_values = "";
									hide_values = "<input type='hidden' value='" + bid + "' name='txt_bid[]' id='txt_bid" + add_row_s + "' >";
									hide_values += "<input type='hidden' value='" + pi_month + "' name='txt_pi_month"+bid+"[]' id='txt_pi_month" + add_row_s + "' >";
									hide_values += "<input type='hidden' value='" + base_index_item + "' name='txt_base_index_item"+bid+"' id='txt_base_index_item" + add_row_s + "' >";
									hide_values += "<input type='hidden' value='" + base_index_rate + "' name='txt_base_index_rate"+bid+"' id='txt_base_index_rate" + add_row_s + "' >";
									hide_values += "<input type='hidden' value='" + base_index_code + "' name='txt_base_index_code"+bid+"' id='txt_base_index_code" + add_row_s + "' >";
									hide_values += "<input type='hidden' value='" + base_breakup_perc + "' name='txt_base_breakup_perc"+bid+"' id='txt_base_breakup_perc" + add_row_s + "' >";
									// The below row is to be stored in Escataion_tcc_details table - So instead of bid month value is added in the textbox name because only month value is stored in escalation_tcc_details table
									hide_values += "<input type='hidden' value='" + pi_rate + "' name='txt_pi_rate"+bid+"[]' id='txt_pi_rate" + add_row_s + "' >";
									hide_values += "<input type='hidden' value='" + avg_pi_rate + "' name='txt_avg_pi_rate"+bid+"' id='txt_avg_pi_rate" + add_row_s + "' >";
									hide_values += "<input type='hidden' value='" + netamt_for_esc + "' name='txt_netamt_for_esc"+bid+"' id='txt_netamt_for_esc" + add_row_s + "' >";
									hide_values += "<input type='hidden' value='" + tcc_amt + "' name='txt_tcc_esc_amt"+bid+"' id='txt_tcc_esc_amt" + add_row_s + "' >";
									document.getElementById("add_hidden_tcc").innerHTML = document.getElementById("add_hidden_tcc").innerHTML + hide_values;
								}
								else
								{
								var cc1 = new_row2.insertCell(0);
									cc1.align = "center";
									cc1.style.className = "extraItemTextbox";
								var cc2 = new_row2.insertCell(1);
									cc2.align = "center";
									cc2.style.className = "extraItemTextbox"; 
									
								cc1.innerHTML = pi_month;
								cc2.innerHTML = pi_rate;
								
								var hide_values = "";
									//hide_values = "<input type='hidden' value='" + bid + "' name='txt_bid[]' id='txt_bid" + add_row_s + "' >";
									hide_values = "<input type='hidden' value='" + pi_month + "' name='txt_pi_month"+bid+"[]' id='txt_pi_month" + add_row_s + "' >";
									// The below row is to be stored in Escataion_tcc_details table - So instead of bid month value is added in the textbox name because only month value is stored in escalation_tcc_details table
									hide_values += "<input type='hidden' value='" + pi_rate + "' name='txt_pi_rate"+bid+"[]' id='txt_pi_rate" + add_row_s + "' >";
									
									document.getElementById("add_hidden_tcc").innerHTML = document.getElementById("add_hidden_tcc").innerHTML + hide_values;
								
								}
								add_row_s_x2++;
							}
						}
						else
						{
							var sel 	= document.getElementById("cmb_10CC");
							var text	= sel.options[sel.selectedIndex].text;
							alert("Escalation Already Generated For "+text);
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
.labelstyle
{
	cursor: pointer;
	color:#A80B02;
}
sub {font-size:xx-small; vertical-align:sub;}
.gradientbg_dialog {
  background-color: #014D62;
  width:100%; height:25px; color:#FFFFFF; vertical-align:middle;
  font-weight:bold;
  background: url(images/linear_bg_2.png);
  background-repeat: repeat-x;
  background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#037595), to(#0A9CC5));
  background: -webkit-linear-gradient(top, #0A9CC5, #037595);
  background: -moz-linear-gradient(top, #0A9CC5, #037595);
  background: -ms-linear-gradient(top, #0A9CC5, #037595);
  background: -o-linear-gradient(top, #0A9CC5, #037595);
}
.pointout
{
	color:#B70C02;
}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                            <div class="title">Escalation Caluculation - 10 CC</div>
                <div class="container_12">
                    <div class="grid_12">

						<!--<div align="right"><a href="AgreementEntryView.php">View</a>&nbsp;&nbsp;&nbsp;</div>-->
                        <blockquote class="bq1" style="overflow:scroll">
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
							<div style="width:100%;" align="center" id="myDiv1">
								<table width="100%" class="color1 label" id="table1">
									<tr>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td align="center" valign="middle" nowrap="nowrap">
											Work Short Name
										</td>
										<td align="center" valign="middle" nowrap="nowrap">
											<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" style="width:350px;" onChange="workorderdetail();GetEscQuarterRBN();getEscalationItem();">
												<option value="">------------------ Select Work Short Name -------------------</option>
												<?php echo $objBind->BindWorkOrderNo(0);?>
											</select>
										</td>
										<td align="center" valign="middle">
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
										<td align="center">RAB</td>
										<td align="">
										&emsp;&emsp;
											<input type="text" name='txt_rbn' id='txt_rbn' class="textboxdisplay" style="width:100px;">
										&nbsp;&emsp;&nbsp;
										<label class="label">Quarter</label>
										&emsp;
											<select name="cmb_quarter" id="cmb_quarter" style="width:140px;" class="textboxdisplay" onChange="find_priceindex_period();">
												<option value="">------- Select -------</option>
											</select>
											<input type="hidden" name='txt_esc_id' id='txt_esc_id' class="textboxdisplay" style="width:60px;">
										</td>
										<td align="center" valign="middle">
											From Date
										</td>
										<td align="center" valign="middle">
											<input type="text" name='txt_from_date' id='txt_from_date' class="textboxdisplay date-picker" style="width:115px;">
											&emsp;&emsp;&nbsp;To Date &emsp;
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
										<td align="center" valign="middle" nowrap="nowrap">
											Select For
										</td>
										<td align="center" valign="middle" nowrap="nowrap">
											<select name='cmb_10CC' id='cmb_10CC' class="textboxdisplay" style="width: 350px;">
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
							<div style="width:100%; height:50px;" align="center" id="myDiv2">
								<table width="80%" class="color1 label" id="table1">
									<tr>
										<td colspan="4" align="center" height="35px" valign="middle">
										<input type="button" class="backbutton" name="calculate" id="calculate" value="Calculate" onClick="get10CC_data();"/></td>
									</tr>
								</table>
							</div>
							<div style="width:100%; height:250px; overflow:scroll" align="center" id="myDiv3">
								<table width="100%" class="table1" id="table3">
									<tr class="labelsmall gradientbg" style="height:30px;">
										<!--<td align="center" valign="middle" nowrap="nowrap">Sl.No.</td>-->
										<td align="center" valign="middle" nowrap="nowrap">Month</td>
										<td align="center" valign="middle" nowrap="nowrap"> RAB. </td>
										<td align="center" valign="middle" nowrap="nowrap"> MB No. </td>
										<td align="center" valign="middle" nowrap="nowrap"> Page </td>
										<td align="center" valign="middle">RAB <br/>Value</td>
										<td align="center" valign="middle">Secured <br/>Advance</td>
										<td align="center" valign="middle">Total RAB<br/> Value </td>
										<td align="center" valign="middle">85 % of RAB <br/>Value</td>
										<td align="center" valign="middle">Total<br/> Recoveries</td>
										<td align="center" valign="middle" nowrap="nowrap">Amount &nbsp;<i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></i></td>
									</tr>
									<tr>
                                       <span id="add_hidden_rab"></span>
									</tr>
								</table>
							</div>
							<div style="width:100%;" align="center" id="myDiv">
								<table width="100%" class="table1" id="table2">
									<tr class="labelsmall gradientbg" style="height:35px;">
										<!--<td align="center" valign="middle" nowrap="nowrap">Sl.No.</td>-->
										<td align="center" valign="middle" nowrap="nowrap">Desc.</td>
										<td align="center" valign="middle" nowrap="nowrap">Month</td>
										<td align="center" valign="middle">Total RAB<br/> Value (W)</td>
										<td align="center" valign="middle">Base <br/>Index</td>
										<td align="center" valign="middle">Esc <br/>Breakup</td>
										<td align="center" valign="middle">Price <br/>Index</td>
										<td align="center" valign="middle">Avg Price <br/>Index</td>
										<td align="center" valign="middle">Formula</td>
										<td align="center" valign="middle">Formula with Values</td>
										<td align="center" valign="middle" nowrap="nowrap">Amount &nbsp;<i class='fa fa-inr' style='font-weight:normal; padding-top:5px;'></i></td>
									</tr>
									<tr>
                                       <span id="add_hidden_tcc"></span>
									</tr>
								</table>
							</div>
							<input type="hidden" name="txt_rbn_calc" id="txt_rbn_calc" value="0">
							<input type="hidden" name="txt_item_calc" id="txt_item_calc" value="">
							<input type="hidden" name="txt_netamt_for_esc" id="txt_netamt_for_esc" value="0">
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
<!--============================Dialog Window ===========================-->
<div id="basic-modal-content">
	<div align="center" class="popuptitle gradientbg_dialog">RAB Net Amount Details for Escalation - 10CC </div>
	<div style=" padding-top:4px; width:100%; height:100%;">
		<table width="100%" class="table1" id="tablex">
			<tr class="labelsmall" style="height:30px; background-color:#C9C9C9" id="det_row0">
				<td align="center" valign="middle" nowrap="nowrap">Sl.No.</td>
				<td align="center" valign="middle" nowrap="nowrap" width="30%">Description </td>
				<td align="center" valign="middle" nowrap="nowrap"> Formula </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row1">
				<td align="center" valign="middle" nowrap="nowrap">1</td>
				<td align="left" valign="middle" width="30%">Name of the Month </td>
				<td align="center" valign="middle" nowrap="nowrap">&nbsp;  </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row2">
				<td align="center" valign="middle" nowrap="nowrap">2</td>
				<td align="left" valign="middle" width="30%">RAB NO: </td>
				<td align="center" valign="middle" nowrap="nowrap">&nbsp;  </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row3">
				<td align="center" valign="middle" nowrap="nowrap">3</td>
				<td align="left" valign="middle" width="30%">MBook No: </td>
				<td align="center" valign="middle" nowrap="nowrap">&nbsp;  </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row4">
				<td align="center" valign="middle" nowrap="nowrap">4</td>
				<td align="left" valign="middle" width="30%">MBook Page No. </td>
				<td align="center" valign="middle" nowrap="nowrap">&nbsp;  </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row5">
				<td align="center" valign="middle" nowrap="nowrap">5</td>
				<td align="left" valign="middle" width="30%">gross value of work done upto <label class="pointout">this month.</label></td>
				<td align="center" valign="middle" nowrap="nowrap"> ( A ) </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row6">
				<td align="center" valign="middle" nowrap="nowrap">6</td>
				<td align="left" valign="middle" width="30%">gross value of work done upto <label class="pointout">last month</label>. </td>
				<td align="center" valign="middle" nowrap="nowrap"> ( B ) </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row7">
				<td align="center" valign="middle" nowrap="nowrap">7</td>
				<td align="left" valign="middle" width="30%">Gross value of work done since previous Month RAB. </td>
				<td align="center" valign="middle" nowrap="nowrap"> ( C ) = (A)-(B) </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row8">
				<td align="center" valign="middle" nowrap="nowrap">8</td>
				<td align="left" valign="middle" width="30%">
					Full assessed value of <label class="pointout">secured advance</label> (excluding materials covered under Cluase 10CA) fresh <label class="pointout">paid</label> in this month RAB.
				 </td>
				<td align="center" valign="middle" nowrap="nowrap"> ( D ) </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row9">
				<td align="center" valign="middle" nowrap="nowrap">9</td>
				<td align="left" valign="middle" width="30%">
				Full assessed value of <label class="pointout">secured advance</label> (excluding materials covered under Cluase 10CA)<label class="pointout">recovered</label> in this  month RAB. 
				</td>
				<td align="center" valign="middle" nowrap="nowrap"> ( E ) </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row10">
				<td align="center" valign="middle" nowrap="nowrap">10</td>
				<td align="left" valign="middle" width="30%">Full assessed value of <label class="pointout">secured advance</label> for which <label class="pointout">escalation payable</label> in this month RAB. </td>
				<td align="center" valign="middle" nowrap="nowrap"> ( F ) = (D-E) </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row11">
				<td align="center" valign="middle" nowrap="nowrap">11</td>
				<td align="left" valign="middle" width="30%">Advance payment made during this month. </td>
				<td align="center" valign="middle" nowrap="nowrap"> ( G ) </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row12">
				<td align="center" valign="middle" nowrap="nowrap">12</td>
				<td align="left" valign="middle" width="30%">Advance payment recovered during this month. </td>
				<td align="center" valign="middle" nowrap="nowrap"> ( H ) </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row13">
				<td align="center" valign="middle" nowrap="nowrap">13</td>
				<td align="left" valign="middle" width="30%">Advance payment for which escalation is payable in this month. </td>
				<td align="center" valign="middle" nowrap="nowrap"> ( I ) = (G-H) </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row14">
				<td align="center" valign="middle" nowrap="nowrap">14</td>
				<td align="left" valign="middle" width="30%">Extra items/deviated quantities paid as per Clause 12 based on prevailing market rates in this month. </td>
				<td align="center" valign="middle" nowrap="nowrap"> ( J ) </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row15">
				<td align="center" valign="middle" nowrap="nowrap">15</td>
				<td align="left" valign="middle" width="30%">M = (C+F+I-J) </td>
				<td align="center" valign="middle" nowrap="nowrap"> ( M ) </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row16">
				<td align="center" valign="middle" nowrap="nowrap">16</td>
				<td align="left" valign="middle" width="30%">N = 0.85*M </td>
				<td align="center" valign="middle" nowrap="nowrap"> ( N ) </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row17">
				<td align="center" valign="middle" nowrap="nowrap">17</td>
				<td align="left" valign="middle" width="30%">Less cost of materials  supplied by the department as per Clause 10 and recovered during the month. </td>
				<td align="center" valign="middle" nowrap="nowrap"> ( K ) </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row18">
				<td align="center" valign="middle" nowrap="nowrap">18</td>
				<td align="left" valign="middle" width="30%">Less cost if servuces rebdered at fixed charges as per Clause 34 and recovered during this month. </td>
				<td align="center" valign="middle" nowrap="nowrap"> ( L ) </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row19">
				<td align="center" valign="middle" nowrap="nowrap">&nbsp;</td>
				<td align="left" valign="middle" width="30%">1) Water Charges </td>
				<td align="center" valign="middle" nowrap="nowrap"> ( L1 ) </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row20">
				<td align="center" valign="middle" nowrap="nowrap">&nbsp;</td>
				<td align="left" valign="middle" width="30%">2) Electricity charges</td>
				<td align="center" valign="middle" nowrap="nowrap"> ( L2 ) </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row21">
				<td align="center" valign="middle" nowrap="nowrap">19</td>
				<td align="left" valign="middle" width="30%">Cost of work for which escalation is applicable for this month. </td>
				<td align="center" valign="middle" nowrap="nowrap"> W = N - (K+L1+L2) </td>
			</tr>
			<tr class="labelsmall" style="height:30px;" id="det_row22">
				<td align="center" valign="middle" nowrap="nowrap">20</td>
				<td align="left" valign="middle" width="30%">Cost of work for which escalation is applicable for this quarter. </td>
				<td align="center" valign="middle" nowrap="nowrap">&nbsp;  </td>
			</tr>
			<tr>
                <span id="add_hidden_rab"></span>
			</tr>
		</table>
	</div>
	<div align="center" style=" width:100%; height:80px;">
		<div class="buttonsection" align="center">
			<input type="button" name="btn_back" id="btn_back" value=" Back " class="buttonstyle" onClick="CloseWindow()" />
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
<script>
function CloseWindow()
{
	$.modal.close();
}
function get10CC_data_dialog()
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
	var quarter	=	document.form.cmb_quarter.value;
	var bid		=	document.form.cmb_10CC.value;
	var type	=	"TCC";
	var fromdate=	document.form.txt_from_date.value;
	var todate	=	document.form.txt_to_date.value;
    strURL = "find_escalation_10CC_data.php?sheetid="+sheetid+"&bid="+bid+"&type=TCC&fromdate="+fromdate+"&todate="+todate+"&quarter="+quarter;
    xmlHttp.open('POST', strURL, true);
    xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xmlHttp.onreadystatechange = function ()
    {
        if(xmlHttp.readyState == 4)
        {
            data = xmlHttp.responseText;
			//alert(data)
            if (data == "")
            {
                 alert("No Records Found");
            }
            else
            {
                var res1 				= data.split("@@##@@");
				var Rbn_data			= res1[0];
				var Pi_data 			= res1[1];
				var Bi_data 			= res1[2];
				var OverAllRowspanRBN 	= res1[3];
				var OverAllRowspanTCC 	= res1[4];
				var total_rbn_amount 	= res1[5];
				//alert(Rbn_data);
				// The Below part is for Displaying RAB, AMOUNT and RECOVERY Calculation.
				var res3 = Rbn_data.split("##");
				var prev_abs_mon_yr = "";
				var netamt_for_esc = 0;
				var month_text = 0;
				for(var k1=0; k1<res3.length; k1++)
				{	
					
					var res4 = res3[k1];
					var res5 = res4.split("*");
					var abs_mon_yr 			= res5[0];
					var abs_rbn 			= res5[1];
					var abs_mbookno 		= res5[2];
					var abs_mbpage 			= res5[3];
					var abs_rbn_amt 		= res5[4];
					if(abs_rbn_amt == "X" || abs_rbn_amt == ""){ abs_rbn_amt = 0; }
					//var abs_rbn_amt_85perc 	= res5[5];
					var abs_sa_amt 			= res5[6];
					if(abs_sa_amt == "X" || abs_sa_amt == ""){ abs_sa_amt = 0; }
					//alert(abs_sa_amt)	
					var abs_er_amt 			= res5[7];
					if(abs_er_amt == "X" || abs_er_amt == ""){ abs_er_amt = 0; }
					var abs_wr_amt 			= res5[8];
					if(abs_wr_amt == "X" || abs_wr_amt == ""){ abs_wr_amt = 0; }
						
					var rbn_row_span 		= res5[9];
					var mon_row_span 		= res5[10];
					var abs_upto_date_amt	= res5[11];
					var abs_dpm_amt 		= res5[12];
					
					var total_rab_amt 		= Number(abs_rbn_amt)+Number(abs_sa_amt);
						total_rab_amt 		= total_rab_amt.toFixed(2);
					var abs_rbn_amt_85perc 	= Number(total_rab_amt)*85/100;
						abs_rbn_amt_85perc	= abs_rbn_amt_85perc.toFixed(2);
					var net_amt 			= Number(abs_rbn_amt_85perc)-Number(abs_er_amt)-Number(abs_wr_amt);
						net_amt				= net_amt.toFixed(2);
					//alert(netamt_for_esc);		
						netamt_for_esc 		= Number(netamt_for_esc)+Number(net_amt);
					if(abs_rbn == "")
					{
						abs_rbn = " - ";
					}
					if(abs_mbookno == "")
					{
						abs_mbookno = " - ";
					}
					if(abs_mbpage == "")
					{
						abs_mbpage = " - ";
					}
					/*if(abs_sa_amt == "")
					{
						abs_sa_amt = "0.00";
					}
					if(abs_er_amt == "")
					{
						abs_er_amt = "0.00";
					}
					if(abs_rbn_amt == "")
					{
						abs_rbn_amt = "0.00";
						abs_rbn_amt_85perc = "0.00";
					}*/
					
// Seperate the secured advance paid and recovered amount based on negative of positive value
					if(abs_sa_amt>0)
					{
						var abs_sa_amt_paid = abs_sa_amt;  // ( D ) 
						var abs_sa_amt_recd = 0;    // ( E )
					}
					else if(abs_sa_amt<=0)
					{
						var abs_sa_amt_recd = abs_sa_amt;   //( E )
						var abs_sa_amt_paid = 0;   // ( D ) 
					}
					else
					{
						var abs_sa_amt_recd = 0;   // ( E )
						var abs_sa_amt_paid = 0;   // ( D ) 
					}
					
					var abs_sa_amt_esc = Number(abs_sa_amt_paid)-Number(abs_sa_amt_recd);   // ( F ) = (D-E) 
						abs_sa_amt_esc = abs_sa_amt_esc.toFixed(2);   // Round of to 2 digit of ( F )
//End

// Seperate the secured advance paid and recovered amount based on negative of positive value
					var adv_payment_made = 0;     // ( G ) 
					var adv_payment_recd = 0;     // ( H ) 
					var adv_payment_esc = Number(adv_payment_made)-Number(adv_payment_recd);     // ( I ) = (G-H) 
						adv_payment_esc = adv_payment_esc.toFixed(2);   // Round of to 2 digit of ( I )
//End					
						
					month_text++;
					var drow1 = document.getElementById("det_row0");
					var rc1 = drow1.insertCell(-1);
						rc1.align = "center";
						rc1.style.verticalAlign = "middle";
						rc1.colSpan = mon_row_span;
						rc1.innerHTML = "Month "+month_text+" (m"+month_text+")";
					if(prev_abs_mon_yr != abs_mon_yr) // Month and Year
					{
						var drow2 = document.getElementById("det_row1");
						var rc2 = drow2.insertCell(-1);
							rc2.align = "center";
							rc2.style.verticalAlign = "middle";
							rc2.colSpan = mon_row_span;
							rc2.innerHTML = abs_mon_yr;
					}
					if(abs_rbn != "X") // RAB Number
					{
						var drow3 = document.getElementById("det_row2");
						var rc3 = drow3.insertCell(-1);
							rc3.align = "center";
							rc3.style.verticalAlign = "middle";
							rc3.colSpan = rbn_row_span;
							rc3.innerHTML = abs_rbn;
					}
					if(abs_rbn != "X") // Abstract MBook Number
					{
						var drow4 = document.getElementById("det_row3");
						var rc4 = drow4.insertCell(-1);
							rc4.align = "center";
							rc4.style.verticalAlign = "middle";
							rc4.colSpan = rbn_row_span;
							rc4.innerHTML = abs_mbookno;
					}
					if(abs_rbn != "X") // Abstract MBook Page Number
					{
						var drow5 = document.getElementById("det_row4");
						var rc5 = drow5.insertCell(-1);
							rc5.align = "center";
							rc5.style.verticalAlign = "middle";
							rc5.colSpan = rbn_row_span;
							rc5.innerHTML = abs_mbpage;
					}
					if(abs_rbn != "X") // Upto this month workDone (A)
					{
						var drow6 = document.getElementById("det_row5");
						var rc6 = drow6.insertCell(-1);
							rc6.align = "center";
							rc6.style.verticalAlign = "middle";
							rc6.colSpan = rbn_row_span;
							rc6.innerHTML = abs_upto_date_amt;
					}
					if(abs_rbn != "X") // Deduct Previous Month workDone (B)
					{
						var drow7 = document.getElementById("det_row6");
						var rc7 = drow7.insertCell(-1);
							rc7.align = "center";
							rc7.style.verticalAlign = "middle";
							rc7.colSpan = rbn_row_span;
							rc7.innerHTML = abs_dpm_amt;
					}
					if(abs_rbn != "X") // Since Last Measurement workDone (C) = (A)-(B)
					{
						var drow8 = document.getElementById("det_row7");
						var rc8 = drow8.insertCell(-1);
							rc8.align = "center";
							rc8.style.verticalAlign = "middle";
							rc8.colSpan = rbn_row_span;
							rc8.innerHTML = abs_rbn_amt;
					}
					if(abs_rbn != "X") // Secured Advance Paid (D)
					{
						var drow9 = document.getElementById("det_row8");
						var rc9 = drow9.insertCell(-1);
							rc9.align = "center";
							rc9.style.verticalAlign = "middle";
							rc9.colSpan = rbn_row_span;
							rc9.innerHTML = abs_sa_amt_paid;
					}
					if(abs_rbn != "X") // Secured Advance Recovered (E)
					{
						var drow10 = document.getElementById("det_row9");
						var rc10 = drow10.insertCell(-1);
							rc10.align = "center";
							rc10.style.verticalAlign = "middle";
							rc10.colSpan = rbn_row_span;
							rc10.innerHTML = abs_sa_amt_recd;
					}
					if(abs_rbn != "X") // Secured Advance Payable For Escalation (F) = (D)-(E)
					{
						var drow11 = document.getElementById("det_row10");
						var rc11 = drow11.insertCell(-1);
							rc11.align = "center";
							rc11.style.verticalAlign = "middle";
							rc11.colSpan = rbn_row_span;
							rc11.innerHTML = abs_sa_amt_esc;
					}
					if(abs_rbn != "X") // Advance Payment Made (G)
					{
						var drow12 = document.getElementById("det_row11");
						var rc12 = drow12.insertCell(-1);
							rc12.align = "center";
							rc12.style.verticalAlign = "middle";
							rc12.colSpan = rbn_row_span;
							rc12.innerHTML = adv_payment_made;
					}
					if(abs_rbn != "X") // Advance Payment Recoverd (H)
					{
						var drow13 = document.getElementById("det_row12");
						var rc13 = drow13.insertCell(-1);
							rc13.align = "center";
							rc13.style.verticalAlign = "middle";
							rc13.colSpan = rbn_row_span;
							rc13.innerHTML = adv_payment_recd;
					}
					if(abs_rbn != "X") // Advance Payment for Escalation (I) = (G)-(H)
					{
						var drow14 = document.getElementById("det_row13");
						var rc14 = drow14.insertCell(-1);
							rc14.align = "center";
							rc14.style.verticalAlign = "middle";
							rc14.colSpan = rbn_row_span;
							rc14.innerHTML = adv_payment_esc;
					}
					if(abs_rbn != "X") // Extra Item / Deviated Qty Paid
					{
						var drow15 = document.getElementById("det_row14");
						var rc15 = drow15.insertCell(-1);
							rc15.align = "center";
							rc15.style.verticalAlign = "middle";
							rc15.colSpan = rbn_row_span;
							rc15.innerHTML = "";
					}
					if(abs_rbn != "X") // M = (C+F+I-J) 
					{
						var drow16 = document.getElementById("det_row15");
						var rc16 = drow16.insertCell(-1);
							rc16.align = "center";
							rc16.style.verticalAlign = "middle";
							rc16.colSpan = rbn_row_span;
							rc16.innerHTML = total_rab_amt;
					}
					if(abs_rbn != "X") 	// N = 0.85*M 
					{
						var drow17 = document.getElementById("det_row16");
						var rc17 = drow17.insertCell(-1);
							rc17.align = "center";
							rc17.style.verticalAlign = "middle";
							rc17.colSpan = rbn_row_span;
							rc17.innerHTML = abs_rbn_amt_85perc;
					}
					if(abs_rbn != "X") // Less cost of materials (K)
					{
						var drow18 = document.getElementById("det_row17");
						var rc18 = drow18.insertCell(-1);
							rc18.align = "center";
							rc18.style.verticalAlign = "middle";
							rc18.colSpan = rbn_row_span;
							rc18.innerHTML = "";
					}
					if(abs_rbn != "X") // All recovery Water, Electricity (L)
					{
						var drow19 = document.getElementById("det_row18");
						var rc19 = drow19.insertCell(-1);
							rc19.align = "center";
							rc19.style.verticalAlign = "middle";
							rc19.colSpan = rbn_row_span;
							rc19.innerHTML = "";
					}
					if(abs_rbn != "X") // Water Charges (L1)
					{
						var drow20 = document.getElementById("det_row19");
						var rc20 = drow20.insertCell(-1);
							rc20.align = "center";
							rc20.style.verticalAlign = "middle";
							rc20.colSpan = rbn_row_span;
							rc20.innerHTML = abs_wr_amt;
					}
					if(abs_rbn != "X") // Electricity  Charges (L2)
					{
						var drow21 = document.getElementById("det_row20");
						var rc21 = drow21.insertCell(-1);
							rc21.align = "center";
							rc21.style.verticalAlign = "middle";
							rc21.colSpan = rbn_row_span;
							rc21.innerHTML = abs_er_amt;
					}
					if(abs_rbn != "X") // Escalation Cost for this month   W = N - (K+L1+L2) 
					{
						var drow22 = document.getElementById("det_row21");
						var rc22 = drow22.insertCell(-1);
							rc22.align = "center";
							rc22.style.verticalAlign = "middle";
							rc22.colSpan = rbn_row_span;
							rc22.innerHTML = net_amt;
					}
					/*if(abs_rbn != "X") // Escalation Cost for this Qtr 
					{
						var drow23 = document.getElementById("det_row22");
						var rc23 = drow23.insertCell(-1);
							rc23.align = "center";
							rc23.style.verticalAlign = "middle";
							rc23.colSpan = rbn_row_span;
							rc23.innerHTML = "vsfvg"+netamt_for_esc;
					}*/
					var hide_values_rab = "";
						hide_values_rab =  "<input type='hidden' value='" + abs_mon_yr + "' 		name='txt_abs_mon_yr[]' >";
						hide_values_rab += "<input type='hidden' value='" + abs_rbn + "' 			name='txt_abs_rbn[]' >";
						hide_values_rab += "<input type='hidden' value='" + abs_mbookno + "' 		name='txt_abs_mbookno[]' >";
						hide_values_rab += "<input type='hidden' value='" + abs_mbpage + "' 		name='txt_abs_mbpage[]' >";
						hide_values_rab += "<input type='hidden' value='" + abs_upto_date_amt + "' 	name='txt_abs_upto_date_amt[]' >";
						hide_values_rab += "<input type='hidden' value='" + abs_dpm_amt + "' 		name='txt_abs_dpm_amt[]' >";
						hide_values_rab += "<input type='hidden' value='" + abs_rbn_amt + "' 		name='txt_abs_rbn_amt[]' >";
						hide_values_rab += "<input type='hidden' value='" + abs_sa_amt_paid + "' 	name='txt_abs_sa_amt_paid[]' >";
						hide_values_rab += "<input type='hidden' value='" + abs_sa_amt_recd + "' 	name='txt_abs_sa_amt_recd[]' >";
						hide_values_rab += "<input type='hidden' value='" + abs_sa_amt_esc + "' 	name='txt_abs_sa_amt_esc[]' >";
						hide_values_rab += "<input type='hidden' value='" + adv_payment_made + "' 	name='txt_adv_pay_made[]' >";
						hide_values_rab += "<input type='hidden' value='" + adv_payment_recd + "' 	name='txt_adv_pay_recd[]' >";
						hide_values_rab += "<input type='hidden' value='" + adv_payment_esc + "' 	name='txt_adv_pay_esc[]' >";
						hide_values_rab += "<input type='hidden' value='' 							name='txt_extra_item[]' >";
						hide_values_rab += "<input type='hidden' value='" + total_rab_amt + "' 		name='txt_M_value[]' >";
						hide_values_rab += "<input type='hidden' value='" + abs_rbn_amt_85perc + "' name='txt_N_value[]' >";
						hide_values_rab += "<input type='hidden' value='' 							name='txt_less_mat[]' >";
						hide_values_rab += "<input type='hidden' value='" + abs_wr_amt + "' 		name='txt_abs_wr_amt[]' >";
						hide_values_rab += "<input type='hidden' value='" + abs_er_amt + "' 		name='txt_abs_er_amt[]' >";
						hide_values_rab += "<input type='hidden' value='" + net_amt + "' 			name='txt_net_amt[]' >";
						//alert();
					document.getElementById("add_hidden_rab").innerHTML = document.getElementById("add_hidden_rab").innerHTML + hide_values_rab;
					var prev_abs_mon_yr = abs_mon_yr;
								
				}
				var drow23 = document.getElementById("det_row22");
				var rc23 = drow23.insertCell(-1);
					rc23.align = "center";
					rc23.style.verticalAlign = "middle";
					rc23.colSpan = OverAllRowspanTCC;
					rc23.innerHTML = netamt_for_esc.toFixed(2);
            }
        }
    }
    xmlHttp.send(strURL);
	$('#basic-modal-content').modal({minHeight:700,maxWidth:920});
}
</script>
</body>
</html>
		
