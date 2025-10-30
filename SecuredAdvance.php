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
if (isset($_POST["btn_save"])) 
{
   	$sheetid 			= 	trim($_POST['cmb_shortname']);
	$rbn 				= 	trim($_POST['txt_rbn']);
	$sec_adv 			= 	trim($_POST['txt_sec_adv']);
	$prev_rbn 			= 	trim($_POST['txt_prev_rbn']);
	$net_amt 			= 	trim($_POST['txt_net_amt']);
	$secadv_type 		= 	trim($_POST['secadv_type']);
	
	$SaUptoDtOtsAmt 	= 	trim($_POST['txt_total_ots_amt']);
	$SaDedPrevOtsAmt 	= 	trim($_POST['txt_deduct_prev_amt']);
	$SaNetAmt 			= 	trim($_POST['txt_net_amt']);
	
	if($secadv_type == "ZM"){
		$SecAdvFlag = $secadv_type;
	}else{
		$SecAdvFlag = "";
	}
	
	$DeleteQuery1 	= "delete from secured_advance where sheetid = '$sheetid' and rbn = '$rbn'";
	$DeleteSql1 	= mysql_query($DeleteQuery1);
	
	$DeleteQuery2 	= "delete from secured_advance_dt where sheetid = '$sheetid' and rbn = '$rbn'";
	$DeleteSql2 	= mysql_query($DeleteQuery2);
	
	
	$AbstMbNo = 0; $AbstMbPg = 0;
	$selectPrevRbnQry = "select endpage, mbno from mymbook where sheetid = '$sheetid' and rbn = '$rbn' and mtype = 'A' order by mbookorder desc limit 1";
	$selectPrevRbnSql = mysql_query($selectPrevRbnQry);
	if($selectPrevRbnSql == true)
	{
		if(mysql_num_rows($selectPrevRbnSql)>0){
			$SList = mysql_fetch_object($selectPrevRbnSql);
			$AbstMbNo  	= $SList->mbno;
			$AbstMbPg  	= $SList->endpage;
			$AbstMbPg 	= $AbstMbPg + 1;
		}
	}
	//echo $AbstMbNo;exit;
	
	$insertQuery 	= "insert into secured_advance set sheetid = '$sheetid', rbn = '$rbn', upto_dt_ots_amt = '$SaUptoDtOtsAmt', ded_prev_ots_amt = '$SaDedPrevOtsAmt', sec_adv_amount = '$net_amt', mbookno = '$AbstMbNo', page = '$AbstMbPg', sa_flag = '$SecAdvFlag', staffid = '".$_SESSION['sid']."', userid = '".$_SESSION['userid']."', active = 1, modifieddate = NOW()";
	$insertSQL 		= mysql_query($insertQuery);
	$said 			= mysql_insert_id();
	
	$secured_adv_update_query = "update abstractbook set secured_adv_amt = '$net_amt' where sheetid = '$sheetid' and rbn = '$rbn'";
	$secured_adv_update_sql = mysql_query($secured_adv_update_query);
	
	$prev_bill_str = $_POST['txt_prev_bill_str'];
		//echo $prev_bill_str;exit;
	$CountStr1 = count($prev_bill_str);
	for($x1=0; $x1<$CountStr1; $x1++)
	{
		$PrevBillStr = $prev_bill_str[$x1];
		if($PrevBillStr != ""){
			$expPrevBillStr = explode("@*@",$PrevBillStr);
		//echo $PrevBillStr;exit;
			$PSubdivid  		= $expPrevBillStr[0];
			$PItemno  			= $expPrevBillStr[1];
			$OtsQtyPrevBill  	= $expPrevBillStr[2];
			$UtzQtyThisBill  	= //$expPrevBillStr[3];
			$OtsQtySinceBill  	= //$expPrevBillStr[4];
			$FullAssessrate  	= $expPrevBillStr[5];
			$RedPerc  			= $expPrevBillStr[6];
			$RedRate  			= $expPrevBillStr[7];
			$UptoDtAmt  		= //$expPrevBillStr[8];
			$DivOffRef  		= $expPrevBillStr[9];
			$ResaonNonClear  	= $expPrevBillStr[10];
			$sadtid  			= $expPrevBillStr[11];
			$description  		= $expPrevBillStr[12];
			
			$UtzQtyThisBill  	= $_POST['txt_uti_qty'.$sadtid];//$expPrevBillStr[3];
			$AddQtySinceBill  	= $_POST['txt_add_qty'.$sadtid];//$expPrevBillStr[4];
			$OtsQtySinceBill  	= $_POST['txt_ots_qty'.$sadtid];//$expPrevBillStr[4];
			$UptoDtAmt  		= $_POST['txt_prev_amount'.$sadtid];//$expPrevBillStr[8];
			
			
			$InsertPrevQuery 	= 	"INSERT INTO secured_advance_dt set
									said = '$said ',
									sheetid = '$sheetid',
									rbn = '$rbn',
									subdivid = '$PSubdivid',
									itemno = '$PItemno',
									ots_qty_prev_bill = '$OtsQtyPrevBill',
									utz_qty_this_bill = '$UtzQtyThisBill',
									add_qty_this_bill = '$AddQtySinceBill',
									ots_qty_since_bill = '$OtsQtySinceBill',
									description = '$description',
									full_asses_rate = '$FullAssessrate',
									red_rate = '$RedRate',
									red_perc = '$RedPerc',
									upto_dt_amt = '$UptoDtAmt',
									div_off_ref = '$DivOffRef',
									reason_non_clear = '$ResaonNonClear',
									modifieddate = NOW(),
									active = 1";
			$InsertPrevSQl 		= 	mysql_query($InsertPrevQuery);
		}
	}
	/*$selectPrevRbnQry = "select * from secured_advance_dt where sheetid = '$sheetid' and rbn = '$prev_rbn' order by subdivid asc";
	$selectPrevRbnSql = mysql_query($selectPrevRbnQry);
	if($selectPrevRbnSql == true)
	{
		while($SList = mysql_fetch_object($selectPrevRbnSql)){
			$PSubdivid  		= $SList->subdivid;
			$PItemno  			= $SList->itemno;
			$OtsQtyPrevBill  	= $SList->ots_qty_prev_bill;
			$UtzQtyThisBill  	= $SList->utz_qty_this_bill;
			$OtsQtySinceBill  	= $SList->ots_qty_since_bill;
			$FullAssessrate  	= $SList->full_asses_rate;
			$RedRate  			= $SList->red_rate;
			$RedPerc  			= $SList->red_perc;
			$UptoDtAmt  		= $SList->upto_dt_amt;
			$DivOffRef  		= $SList->div_off_ref;
			$ResaonNonClear  	= $SList->reason_non_clear;
		}
	}*/
	//echo $_POST['add_set_a1'];exit;
	
	$rec = explode(".", $_POST['add_set_a1']);
	for ($c = 0; $c < count($rec); $c++) 
	{
		$x = $rec[$c];
		if($x != "")
		{
			$subdivid				=	trim($_POST['cmb_item'.$x]);
			$itemno					=	trim($_POST['txt_item_no'.$x]);
			$prev_outstand_qty		=	trim($_POST['txt_prev_outstand_qty'.$x]);
			$prev_utilized_qty		=	trim($_POST['txt_prev_utilized_qty'.$x]);
			$add_brought_qty		=	trim($_POST['txt_add_brought_site_qty'.$x]);
			$since_outstand_qty		=	trim($_POST['txt_since_outstand_qty'.$x]);
			$full_rate				=	trim($_POST['txt_full_rate'.$x]);
			$item_desc				=	trim($_POST['txt_item_desc'.$x]);
			$item_unit				=	trim($_POST['txt_item_unit'.$x]);
			$perc					=	trim($_POST['txt_perc'.$x]);
			$reduce_rate			=	trim($_POST['txt_reduce_rate'.$x]);
			$upto_amount			=	trim($_POST['txt_upto_amount'.$x]);
			$div_officer_ref		=	trim($_POST['txt_div_officer_ref'.$x]);
			$non_clear_reason		=	trim($_POST['txt_non_clear_reason'.$x]);
			
			if($subdivid != "")
			{
				$SecuredAdvQuery 	= 	"INSERT INTO secured_advance_dt set
									said = '$said ',
									sheetid = '$sheetid',
									rbn = '$rbn',
									subdivid = '$subdivid',
									itemno = '$itemno',
									description = '$item_desc',
									ots_qty_prev_bill = '$prev_outstand_qty',
									add_qty_this_bill = '$add_brought_qty',
									utz_qty_this_bill = '$prev_utilized_qty',
									ots_qty_since_bill = '$since_outstand_qty',
									full_asses_rate = '$full_rate',
									red_rate = '$reduce_rate',
									red_perc = '$perc',
									upto_dt_amt = '$upto_amount',
									div_off_ref = '$div_officer_ref',
									reason_non_clear = '$non_clear_reason',
									modifieddate = NOW(),
									active = 1";
									//echo $SecuredAdvQuery;exit;
				$SecuredAdvSql 	= 	mysql_query($SecuredAdvQuery);
			}
		}
	}
	unset($_SESSION["abst_method"]);
	if($secadv_type == "ZM"){
		$abstmbno 					= $_POST['cmb_mbook_no'];
		$_SESSION["rbn"] 			= $rbn; 
		$_SESSION["abstsheetid"] 	= $sheetid;   
		$_SESSION["abs_mbno"] 		= $_POST['txt_mbookno'];
		$_SESSION["abs_page"] 		= $_POST['txt_mbook_page_no'];
		$_SESSION['fromdate'] 		= date("Y-m-d");      
		$_SESSION['todate'] 		= date("Y-m-d");    
		$_SESSION["abs_mbno_id"] 	= "";
		$_SESSION["paymentpercent"] = "";
		$_SESSION["abst_method"] 	= "ZMSA";
		//echo $abstmbno;exit;
		header('Location: AbstMBook_Partpay_nextlevel.php');
	}
	
    if(($InsertPrevSQl == ture) || ($SecuredAdvSql == true))
	{
        $msg = "Secured Advance Stored Successfully ";
		$success = 1;
    }
	else
	{
		$msg = " Secured advance not saved. Please try again...!!! ";
	}
} 
$post = ""; $sid = 0; $secadv_type = "";
if(isset($_POST["btn_go"])) 
{
	$post = "GO";
   	$sheetid 			= 	trim($_POST['cmb_shortname']);
	$rbn 				= 	trim($_POST['txt_rbn']);
	$secadv_type 		=   $_POST['secadv_type'];
	$mbook_no 			=   $_POST['txt_mbookno'];
	$mbook_page 		=   $_POST['txt_mbook_page_no'];
	$PrevRbn = 0;
	$selectRbnQuery 	= "select * from secured_advance where sheetid = '$sheetid' and rbn = (select max(rbn) from secured_advance where sheetid = '$sheetid' and rbn < '$rbn')";
	//$selectRbnQuery 	= "select max(rbn) as prev_rbn, sec_adv_amount from secured_advance where sheetid = '$sheetid' and rbn < '$rbn' group by sheetid";
	$selectRbnSql 		= mysql_query($selectRbnQuery);
	if($selectRbnSql == true){
		$RabList 		= mysql_fetch_object($selectRbnSql);
		$PrevRbn 		= $RabList->rbn;
		
		//$PrevSaAmountTmp 	= $RabList->sec_adv_amount;
		$PrevSaAmountTmp 	= $RabList->upto_dt_ots_amt;
		
		$PrevSaAmount = 0; $Exe = 0;
		$selectPrevAmtQuery 	= "select upto_dt_amt from secured_advance_dt where sheetid = '$sheetid' and rbn = '$PrevRbn'";
		$selectPrevAmtSql 		= mysql_query($selectPrevAmtQuery);
		if($selectPrevAmtSql == true){
			while($PrevAmtList 	= mysql_fetch_object($selectPrevAmtSql)){
				$PrevSaAmount 	= $PrevSaAmount + $PrevAmtList->upto_dt_amt;
				$Exe++;
			}
		}
		if($Exe == 0){
			//$PrevSaAmount = round($PrevSaAmountTmp,2);
		}else{
			//$PrevSaAmount = round($PrevSaAmount,2);
		}
		$PrevSaAmount = round($PrevSaAmountTmp,2);
	}
	//echo $PrevSaAmount;exit;
	//$selectQuery = "select * from secured_advance_dt where sheetid = '$sheetid' and rbn = '$PrevRbn'";
	$selectQuery = "select sadtid, said, sheetid, rbn, subdivid, itemno, sum(ots_qty_since_bill) as qty_from_prev_bill, full_asses_rate, description, red_rate, red_perc, upto_dt_amt, div_off_ref, reason_non_clear from secured_advance_dt where sheetid = '$sheetid' and rbn = '$PrevRbn' group by subdivid order by subdivid asc";
	$selectSql = mysql_query($selectQuery);
	$sid = $sheetid;
	//echo $selectRbnQuery;exit;
}
?>

<?php require_once "Header.html"; ?>
<style>
    
</style>
<script>
	function goBack()
	{
		url = "SecuredAdvanceGenerate.php";
		window.location.replace(url);
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
                	//alert("No Records Found");
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
	
	function getAllItem()
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
        strURL = "find_all_itemno.php?sheetid=" + document.form.cmb_shortname.value;
        xmlHttp.open('POST', strURL, true);
        xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlHttp.onreadystatechange = function ()
        {
        	if (xmlHttp.readyState == 4)
            {
            	data = xmlHttp.responseText; 
                if (data == "")
                {
                	//alert("No Records Found");
                }
                else
                {
                	var name = data.split("@#*#@");
					document.form.cmb_item_no.length = 0;
					var optn = document.createElement("option");
					optn.value = "";
					optn.text = "-Select-";
					document.form.cmb_item_no.options.add(optn);
                    for(i = 0; i < name.length; i+=8)
                    {
                    	var optn = document.createElement("option")
						optn.value = name[i];
						optn.text = name[i+1];
						optn.setAttribute('data-itemno', name[i+1]);
						optn.setAttribute('data-itemrate', name[i+2]);
						optn.setAttribute('data-itemdecimal', name[i+3]);
						optn.setAttribute('data-itemunit', name[i+4]);
						optn.setAttribute('data-itemdescription', name[i+5]);
						optn.setAttribute('data-itembaserate', name[i+6]);
						optn.setAttribute('data-itemtotalqty', name[i+7]);
						document.form.cmb_item_no.options.add(optn)
                    }

                }
            }
        }
     	xmlHttp.send(strURL);
	}
	
	function getSAItemDescription()
    { 
    	var xmlHttp;
        var data;
        var i, j;
		//alert(document.form.cmb_shortname.value)
		//alert(document.form.cmb_item_no.value)
		document.form.txt_item_desc.value = "";
        if (window.XMLHttpRequest) // For Mozilla, Safari, ...
        {
        	xmlHttp = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) // For Internet Explorer
        {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        strURL = "find_SAItemDescription.php?sheetid="+document.form.cmb_shortname.value+"&Itemid="+document.form.cmb_item_no.value;
        xmlHttp.open('POST', strURL, true);
        xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlHttp.onreadystatechange = function ()
        {
        	if (xmlHttp.readyState == 4)
            {
            	data = xmlHttp.responseText;
				//alert(data)
                if(data == "")
                {
                	document.form.txt_item_desc.value = "";
                }
                else
                {
                	var ItemDescription = data;
					document.form.txt_item_desc.value = ItemDescription;
                }
            }
        }
     	xmlHttp.send(strURL);
	}
	
	
	var add_row_s 		= 4;
	var prev_edit_row 	= 0;
	function addrow()
	{
		var Err = 0;
		
		if(document.form.cmb_item_no.value == ""){
			BootstrapDialog.alert("Please select Item No.");
			return false;
			exit();
			Err++;
		}
		if((document.form.txt_prev_outstand_qty.value == "")&&(document.form.txt_add_brought_site_qty.value == "")){
			BootstrapDialog.alert("Qty from Prev Bill or Add Qty brought to site should not be empty");
			return false;
			exit();
			Err++;
		}
		if(document.form.txt_since_outstand_qty.value == ""){
			BootstrapDialog.alert("Outstanding Qty should not be empty");
			return false;
			exit();
			Err++;
		}
		if(document.form.txt_full_rate.value == ""){
			BootstrapDialog.alert("Full Rate should not be empty");
			return false;
			exit();
			Err++;
		}
		if(document.form.txt_full_rate.value == 0){
			BootstrapDialog.alert("Full Rate should not be '0'");
			return false;
			exit();
			Err++;
		}
		if(document.form.txt_item_desc.value == ""){
			BootstrapDialog.alert("Item Description should not be empty");
			return false;
			exit();
			Err++;
		}
		if(document.form.txt_perc.value == ""){
			BootstrapDialog.alert("Rate % should not be empty");
			return false;
			exit();
			Err++;
		}
		if(document.form.txt_reduce_rate.value == ""){
			BootstrapDialog.alert("Reduced Rate should not be empty");
			return false;
			exit();
			Err++;
		}
		if(document.form.txt_upto_amount.value == ""){
			BootstrapDialog.alert("Upto date amount should not be empty");
			return false;
			exit();
			Err++;
		}
		
		var new_row = document.getElementById("table2").insertRow(add_row_s);
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
		var c13 = new_row.insertCell(12);
			c13.align = "center";
			c13.style.className = "extraItemTextbox";
		var c14 = new_row.insertCell(13);
			c14.align = "center";
			c14.style.className = "extraItemTextbox";
		var c15 = new_row.insertCell(14);
			c15.align = "center";
			c15.style.className = "extraItemTextbox";
			
		var Ax = document.getElementById("cmb_item_no").selectedIndex;
		var Ay = document.getElementById("cmb_item_no").options;
		var itemNo = Ay[Ax].text;
		var itemId = Ay[Ax].value;
		//alert(itemId)
		//c1.innerHTML 	= '<span style="font-weight:bold; font-size:13px; color:#099B29; cursor:pointer" data-id="1">(<b>+</b>)</span>';// onClick=AppendRow(' + add_row_s + ')>(<b>+</b>)</span>';
		c1.innerHTML 	= itemNo;
		c2.innerHTML 	= document.form.txt_prev_outstand_qty.value;
		c3.innerHTML 	= document.form.txt_prev_utilized_qty.value;
		c4.innerHTML 	= document.form.txt_add_brought_site_qty.value;
		c5.innerHTML 	= document.form.txt_since_outstand_qty.value;
		c6.innerHTML 	= document.form.txt_full_rate.value;
		c7.innerHTML 	= document.form.txt_item_desc.value;//document.getElementById("txt_item_unit").innerHTML;//
		c8.innerHTML 	= document.form.txt_item_unit.value;
		c9.innerHTML 	= document.form.txt_perc.value;
		c10.innerHTML 	= document.form.txt_reduce_rate.value;
		c11.innerHTML 	= document.form.txt_upto_amount.value;
		c12.innerHTML 	= document.form.txt_div_officer_ref.value;
		c13.innerHTML 	= document.form.txt_non_clear_reason.value;
		c14.innerHTML 	= "<input type='button' class='buttonstyle' name='btn_edit_" + add_row_s + "' style='height:25px;' id='btn_edit_" + add_row_s + "'  value=' EDIT ' onClick=editrow(" + add_row_s + ",'n')>";
		c15.innerHTML 	= "<input type='button' class='buttonstyle'  name='btn_del_" + add_row_s + "' style='height:25px;'  id='btn_del_" + add_row_s + "' value=' DEL ' onClick=delrow(" + add_row_s + ")>";
		var hide_values = "";
		hide_values = "<input type='hidden' value='" + itemId+ "' name='cmb_item" + add_row_s + "' id='cmb_item" + add_row_s + "' >";
		hide_values += "<input type='hidden' value='" + itemNo+ "' name='txt_item_no" + add_row_s + "' id='txt_item_no" + add_row_s + "' >";
		hide_values += "<input type='hidden' value='" + c2.innerHTML + "' name='txt_prev_outstand_qty" + add_row_s + "' id='txt_prev_outstand_qty" + add_row_s + "' >";
		hide_values += "<input type='hidden' value='" + c3.innerHTML + "' name='txt_prev_utilized_qty" + add_row_s + "' id='txt_prev_utilized_qty" + add_row_s + "' >";
		hide_values += "<input type='hidden' value='" + c4.innerHTML + "' name='txt_add_brought_site_qty" + add_row_s + "' id='txt_add_brought_site_qty" + add_row_s + "' >";
		hide_values += "<input type='hidden' value='" + c5.innerHTML + "' name='txt_since_outstand_qty" + add_row_s + "' id='txt_since_outstand_qty" + add_row_s + "' >";
		hide_values += "<input type='hidden' value='" + c6.innerHTML + "' name='txt_full_rate" + add_row_s + "' id='txt_full_rate" + add_row_s + "' >";
		hide_values += "<input type='hidden' value='" + c7.innerHTML + "' name='txt_item_desc" + add_row_s + "' id='txt_item_desc" + add_row_s + "' >";
		hide_values += "<input type='hidden' value='" + c8.innerHTML + "' name='txt_item_unit" + add_row_s + "' id='txt_item_unit" + add_row_s + "' >";
		hide_values += "<input type='hidden' value='" + c9.innerHTML + "' name='txt_perc" + add_row_s + "' id='txt_perc" + add_row_s + "' >";
		hide_values += "<input type='hidden' value='" + c10.innerHTML + "' name='txt_reduce_rate" + add_row_s + "' id='txt_reduce_rate" + add_row_s + "' >";
		hide_values += "<input type='hidden' value='" + c11.innerHTML + "' name='txt_upto_amount" + add_row_s + "' id='txt_upto_amount" + add_row_s + "' >";
		hide_values += "<input type='hidden' value='" + c11.innerHTML + "' name='txt_since_upto_amount[]' id='txt_since_upto_amount" + add_row_s + "' >";
		hide_values += "<input type='hidden' value='" + c12.innerHTML + "' name='txt_div_officer_ref" + add_row_s + "' id='txt_div_officer_ref" + add_row_s + "' >";
		hide_values += "<input type='hidden' value='" + c13.innerHTML + "' name='txt_non_clear_reason" + add_row_s + "' id='txt_non_clear_reason" + add_row_s + "' >";
		document.getElementById("add_hidden").innerHTML = document.getElementById("add_hidden").innerHTML + hide_values;
		if(document.getElementById("add_set_a1").value == "")
		{
			document.getElementById("add_set_a1").value = add_row_s;
		}
		else
		{
			document.getElementById("add_set_a1").value = document.getElementById("add_set_a1").value + "." + add_row_s;
		}
		clearrow();
		CalculateNetAmount();
		add_row_s++;
	}
	
	
	function editrow(rowno, update)
	{
		var total; 
		var net_value;
		var edit_row = document.getElementById("table2").rows[rowno].cells;
		if (update == 'y') // transfer controls to table row
		{
			var Ax = document.getElementById("cmb_item_no").selectedIndex;
			var Ay = document.getElementById("cmb_item_no").options;
			var itemNo = Ay[Ax].text;
			var itemId = Ay[Ax].value;
			edit_row[0].innerHTML 	= itemNo;
			edit_row[1].innerHTML 	= document.form.txt_prev_outstand_qty.value;
			edit_row[2].innerHTML 	= document.form.txt_prev_utilized_qty.value;
			edit_row[3].innerHTML 	= document.form.txt_add_brought_site_qty.value;
			edit_row[4].innerHTML 	= document.form.txt_since_outstand_qty.value;
			edit_row[5].innerHTML 	= document.form.txt_full_rate.value;
			edit_row[6].innerHTML 	= document.form.txt_item_desc.value;
			edit_row[7].innerHTML 	= document.form.txt_item_unit.value;
			edit_row[8].innerHTML 	= document.form.txt_perc.value;
			edit_row[9].innerHTML 	= document.form.txt_reduce_rate.value;
			edit_row[10].innerHTML 	= document.form.txt_upto_amount.value;
			edit_row[11].innerHTML 	= document.form.txt_div_officer_ref.value;
			edit_row[12].innerHTML 	= document.form.txt_non_clear_reason.value;
			
			document.getElementById("cmb_item" + rowno).value 					= itemId;//edit_row[1].innerHTML;
			document.getElementById("txt_item_no" + rowno).value 				= itemNo;//edit_row[1].innerHTML;
			document.getElementById("txt_prev_outstand_qty" + rowno).value 	 	= edit_row[1].innerHTML;
			document.getElementById("txt_prev_utilized_qty" + rowno).value 		= edit_row[2].innerHTML;
			document.getElementById("txt_add_brought_site_qty" + rowno).value 	= edit_row[3].innerHTML;
			document.getElementById("txt_since_outstand_qty" + rowno).value 	= edit_row[4].innerHTML;
			document.getElementById("txt_full_rate" + rowno).value 	 			= edit_row[5].innerHTML;
			document.getElementById("txt_item_desc" + rowno).value 	 			= edit_row[6].innerHTML;
			document.getElementById("txt_item_unit" + rowno).value 		 		= edit_row[7].innerHTML;
			document.getElementById("txt_perc" + rowno).value 		 			= edit_row[8].innerHTML;
			document.getElementById("txt_reduce_rate" + rowno).value 		 	= edit_row[9].innerHTML;
			document.getElementById("txt_upto_amount" + rowno).value 	 		= edit_row[10].innerHTML;
			document.getElementById("txt_since_upto_amount" + rowno).value 	 	= edit_row[10].innerHTML; /// For Hidden Purpose
			document.getElementById("txt_div_officer_ref" + rowno).value 	 	= edit_row[11].innerHTML;
			document.getElementById("txt_non_clear_reason" + rowno).value 	 	= edit_row[12].innerHTML;
			clearrow();
		}//update=='y'
		else  //transfer table row to controls
		{
			document.form.cmb_item_no.value 			= document.getElementById("cmb_item"+ rowno).value;
			document.form.txt_prev_outstand_qty.value 	= edit_row[1].innerHTML;
			document.form.txt_prev_utilized_qty.value 	= edit_row[2].innerHTML;
			document.form.txt_add_brought_site_qty.value= edit_row[3].innerHTML;
			document.form.txt_since_outstand_qty.value 	= edit_row[4].innerHTML;
			document.form.txt_full_rate.value 			= edit_row[5].innerHTML;
			document.form.txt_item_desc.value 			= edit_row[6].innerHTML;
			document.form.txt_item_unit.value 			= edit_row[7].innerHTML;
			document.form.txt_perc.value 				= edit_row[8].innerHTML;
			document.form.txt_reduce_rate.value 		= edit_row[9].innerHTML;
			document.form.txt_upto_amount.value 		= edit_row[10].innerHTML;
			document.form.txt_div_officer_ref.value 	= edit_row[11].innerHTML;
			document.form.txt_non_clear_reason.value 	= edit_row[12].innerHTML;
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
		CalculateNetAmount();
	}
	
	function delrow(rownum)
	{
		var src_row = (rownum + 1)
		var tar_row = rownum
		var noofadd = (add_row_s - 1)
		for (x = rownum; x < noofadd; x++)
		{
			document.getElementById("cmb_item" + tar_row).value 				= document.getElementById("cmb_item" + src_row).value
			document.getElementById("txt_item_no" + tar_row).value 				= document.getElementById("txt_item_no" + src_row).value
			document.getElementById("txt_prev_outstand_qty" + tar_row).value 	= document.getElementById("txt_prev_outstand_qty" + src_row).value
			document.getElementById("txt_prev_utilized_qty" + tar_row).value 	= document.getElementById("txt_prev_utilized_qty" + src_row).value
			document.getElementById("txt_add_brought_site_qty" + tar_row).value = document.getElementById("txt_add_brought_site_qty" + src_row).value
			document.getElementById("txt_since_outstand_qty" + tar_row).value 	= document.getElementById("txt_since_outstand_qty" + src_row).value
			document.getElementById("txt_full_rate" + tar_row).value 			= document.getElementById("txt_full_rate" + src_row).value
			document.getElementById("txt_item_desc" + tar_row).value 			= document.getElementById("txt_item_desc" + src_row).value
			document.getElementById("txt_item_unit" + tar_row).value 			= document.getElementById("txt_item_unit" + src_row).value
			document.getElementById("txt_perc" + tar_row).value 				= document.getElementById("txt_perc" + src_row).value
			document.getElementById("txt_reduce_rate" + tar_row).value 			= document.getElementById("txt_reduce_rate" + src_row).value
			document.getElementById("txt_upto_amount" + tar_row).value 			= document.getElementById("txt_upto_amount" + src_row).value
			document.getElementById("txt_since_upto_amount" + tar_row).value 	= document.getElementById("txt_since_upto_amount" + src_row).value /// For Hidden Purpose
			document.getElementById("txt_div_officer_ref" + tar_row).value 		= document.getElementById("txt_div_officer_ref" + src_row).value
			document.getElementById("txt_non_clear_reason" + tar_row).value 	= document.getElementById("txt_non_clear_reason" + src_row).value
			tar_row++;
			src_row++;
			var trow = document.getElementById("table2").rows[x].cells;
			var srow = document.getElementById("table2").rows[x + 1].cells;
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
			trow[10].innerText = srow[10].innerText;
			trow[11].innerText = srow[11].innerText;
			trow[12].innerText = srow[12].innerText;
			//trow[6].innerText = srow[6].innerText;
		}
		document.getElementById("cmb_item" + tar_row).value 				= "";
		document.getElementById("txt_item_no" + tar_row).value 				= "";
		document.getElementById("txt_prev_outstand_qty" + tar_row).value 	= "";
		document.getElementById("txt_prev_utilized_qty" + tar_row).value 	= "";
		document.getElementById("txt_add_brought_site_qty" + tar_row).value = "";
		document.getElementById("txt_since_outstand_qty" + tar_row).value 	= "";
		document.getElementById("txt_full_rate" + tar_row).value 			= "";
		document.getElementById("txt_item_desc" + tar_row).value 			= "";
		document.getElementById("txt_item_unit" + tar_row).value 			= "";
		document.getElementById("txt_perc" + tar_row).value 				= "";
		document.getElementById("txt_reduce_rate" + tar_row).value 			= "";
		document.getElementById("txt_upto_amount" + tar_row).value 			= "";
		document.getElementById("txt_since_upto_amount" + tar_row).value 	= "";   /// For Hidden Purpose
		document.getElementById("txt_div_officer_ref" + tar_row).value 		= "";
		document.getElementById("txt_non_clear_reason" + tar_row).value     = "";
		document.getElementById('table2').deleteRow(noofadd)
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
		CalculateNetAmount();
		add_row_s = noofadd++;
	}
	
	
	function clearrow()
	{	//alert();
		document.form.cmb_item_no.value 			= "";
		document.form.txt_prev_outstand_qty.value 	= "";
		document.form.txt_prev_utilized_qty.value 	= "";
		document.form.txt_add_brought_site_qty.value= "";
		document.form.txt_since_outstand_qty.value 	= "";
		document.form.txt_full_rate.value 			= "";
		document.form.txt_item_desc.value 			= "";
		document.form.txt_perc.value 				= "";
		document.form.txt_item_unit.value 			= "";
		document.form.txt_reduce_rate.value 		= "";
		document.form.txt_upto_amount.value 		= "";
		document.form.txt_div_officer_ref.value 	= "";
		document.form.txt_non_clear_reason.value 	= "";
	}
	
	function getUtilizedQty(obj)
	{
		var sheetid = document.form.cmb_shortname.value;
		var itemid = obj.value;
		//alert(itemid);
		document.form.txt_prev_utilized_qty.value = 0;
		document.form.txt_prev_outstand_qty.value = 0;
		document.form.txt_since_outstand_qty.value = 0;
		document.form.txt_upto_amount.value = 0;
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
        strURL = "find_utilized_qty.php?sheetid="+sheetid+"&itemid="+itemid;
        xmlHttp.open('POST', strURL, true);
        xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlHttp.onreadystatechange = function ()
        {
        	if (xmlHttp.readyState == 4)
            {
            	data 					= xmlHttp.responseText; 
				//alert(data);
               	var QtyData 			= data.split("@@");
				var Utilized_Qty 		= QtyData[0];
				var Ots_Qty_Prev_Bill 	= QtyData[1];
				var Upto_Dt_Amt 		= QtyData[2];
				var Ots_Qty_At_Site		= Number(Ots_Qty_Prev_Bill)-Number(Utilized_Qty);
				if(Utilized_Qty != ""){
					document.form.txt_prev_utilized_qty.value 	= Utilized_Qty;
                }if(Ots_Qty_Prev_Bill != ""){
					document.form.txt_prev_outstand_qty.value 	= Ots_Qty_Prev_Bill;
                }if(Ots_Qty_At_Site != ""){
					document.form.txt_since_outstand_qty.value 	= Ots_Qty_At_Site;
                }if(Upto_Dt_Amt != ""){
					document.form.txt_upto_amount.value 		= Number(Upto_Dt_Amt).toFixed(2);
                }
				//CalculateUptoDateAmt();
            }
        }
     	xmlHttp.send(strURL);
	}
	
	function CalculateNetAmount()
	{
		var deduct_prev_amt = document.getElementById("txt_deduct_prev_amt").value;
			deduct_prev_amt = Number(deduct_prev_amt);
		var total_amount = 0;
		
		var x1 = document.getElementsByName("txt_prev_upto_amt[]");
		var i;
		for (i = 0; i < x1.length; i++) {
			var amount1 = x1[i].value;
			total_amount = Number(total_amount) + Number(amount1);
		}
		
		var x2 = document.getElementsByName("txt_since_upto_amount[]");
		var j;
		for (j = 0; j < x2.length; j++) {
			var amount2 = x2[j].value;
			total_amount = Number(total_amount) + Number(amount2);
		}
		
		total_amount = total_amount.toFixed(2);
		document.getElementById("txt_total_ots_amt").value = total_amount;
		var net_amount = 0;
		net_amount = Number(total_amount) - Number(deduct_prev_amt);
		document.getElementById("txt_net_amt").value = net_amount;
	}
	
		/*function CalculateNetAmount(){ 
			var deduct_prev_amt = $("#txt_deduct_prev_amt").val();
				deduct_prev_amt = Number(deduct_prev_amt);
			var total_amount = 0;
			
			$('input[name="txt_prev_upto_amt[]"]').each(function() {
					var amount1 = $(this).val();
					total_amount = Number(total_amount) + Number(amount1);
			});
			
			$('input[name="txt_since_upto_amount[]"]').each(function() {
					var amount2 = $(this).val();
					total_amount = Number(total_amount) + Number(amount2);
			});
			
			total_amount = total_amount.toFixed(2);
			$("#txt_total_ots_amt").val(total_amount);
			var net_amount = 0;
			net_amount = Number(total_amount) - Number(deduct_prev_amt);
			$("#txt_net_amt").val(total_amount);
		}*/
	/*function AppendRow(row)
	{
		//addrow("APD");
	}*/
	
	$(function () {
		$(".tQty").change(function(){
			var PrevOts 	= $("#txt_prev_outstand_qty").val();
			var PrevUts 	= $("#txt_prev_utilized_qty").val();
			var AddtoSite 	= $("#txt_add_brought_site_qty").val();
			var SinceOts 	= $("#txt_since_outstand_qty").val();
			/*var itemdecimal = $('#cmb_item_no option:selected').data('itemdecimal');
			if(PrevOts == ''){ PrevOts = 0; }
			if(PrevUts == ''){ PrevUts = 0; }
			if(AddtoSite == ''){ AddtoSite = 0; }
			if(SinceOts == ''){ SinceOts = 0; }*/
			if((PrevOts != '')||(PrevUts != '')||(AddtoSite != '')){
				CalculateOtsQty();
				CalculateUptoDateAmt();
			}
			
		});
		$("#cmb_item_no").change(function(){
			//alert($('#cmb_item_no option:selected').text()); alert($(this).val())
			var itemno 			= $('#cmb_item_no option:selected').data('itemno');
			var itemrate 		= $('#cmb_item_no option:selected').data('itembaserate');//$('#cmb_item_no option:selected').data('itemrate');
			var itemdecimal 	= $('#cmb_item_no option:selected').data('itemdecimal');
			var itemunit 		= $('#cmb_item_no option:selected').data('itemunit');
			var itemdescription = $('#cmb_item_no option:selected').data('itemdescription');
			$("#txt_full_rate").val(itemrate);
			//$("#txt_item_desc").html(itemdescription);
			$("#txt_item_unit").val(itemunit);
			$("#txt_reduce_rate").val(itemrate);
		});
		
		$("#txt_since_outstand_qty").change(function(){
			CalculateUptoDateAmt();
		});
		
		$("#txt_add_brought_site_qty").change(function(){
			CalculateOtsQty();
			CalculateUptoDateAmt();
		});
		
		$("#txt_perc").change(function(){
			CalculateUptoDateAmt();
		});
		
		$("#txt_full_rate").change(function(){
			CalculateUptoDateAmt();
		});
		
		
		$(".uts").change(function(){
			//var val 		= $(this).val();
			var rowid 		= $(this).data('rowid');
			var uti_qty 	= $("#txt_uti_qty"+rowid).val();
			var add_qty 	= $("#txt_add_qty"+rowid).val();
			var red_rate 	= $(this).data('red_rate');
			var prev_qty 	= $(this).data('prev_qty');
			var ots_qty 	= Number(prev_qty) - Number(uti_qty) + Number(add_qty);
			var new_amt 	= Number(ots_qty)*Number(red_rate);
			$("#txt_prev_upto_amt"+rowid).val(new_amt.toFixed(2));
			$("#txt_prev_amount"+rowid).val(new_amt.toFixed(2)); // For POST and SAVE into DATABASE Purpose
			$("#txt_ots_qty"+rowid).val(ots_qty.toFixed(3));
			var total_ots_amount = 0;
			$('input[name="txt_since_upto_amount[]"]').each(function() {
					var amount1 = $(this).val();
					total_ots_amount = Number(total_ots_amount) + Number(amount1);
			});
			
			$('input[name="txt_prev_upto_amt[]"]').each(function() {
					var amount2 = $(this).val();
					total_ots_amount = Number(total_ots_amount) + Number(amount2);
			});
			var prev_sec_adv 	= $("#txt_deduct_prev_amt").val();
			var net_amount = Number(total_ots_amount)-Number(prev_sec_adv);
			$("#txt_total_ots_amt").val(total_ots_amount.toFixed(2))
			$("#txt_net_amt").val(net_amount.toFixed(2))
			
		});
		
		$("#with_meas").click(function(){
			$("#txt_rbn").val("");
			$("#txt_rbn").addClass("DisableInput");
			$("#txt_rbn").attr("readonly", true); 
			getrbn();
			
		});
		$("#zero_meas").click(function(){
			$("#txt_rbn").val("");
			$("#txt_rbn").removeClass("DisableInput");
			$("#txt_rbn").attr("readonly", false); 
		});
		
		/*$("#btn_go").click(function(){
			$("#table2").show();
			$("#table3").show();
		});
		*/
		function CalculateUptoDateAmt(){
			var since_ots_qty 	= $("#txt_since_outstand_qty").val();
			var itemrate 		= $("#txt_full_rate").val();//$('#cmb_item_no option:selected').data('itembaserate');//$('#cmb_item_no option:selected').data('itemrate');
			var itemdecimal 	= $('#cmb_item_no option:selected').data('itemdecimal');
			var perc 			= $("#txt_perc").val();
			$("#txt_upto_amount").val(0);
			if((perc == "")||(perc == 0)){
				perc = 100;
			}
			var red_rate = Number(itemrate)*Number(perc)/100;
			$("#txt_reduce_rate").val(red_rate.toFixed(2));
			var upto_dt_amt = Number(since_ots_qty) * Number(red_rate);
			$("#txt_upto_amount").val(upto_dt_amt.toFixed(2));
		}
		
		function CalculateOtsQty(){
			var prev_ots_qty 	= $("#txt_prev_outstand_qty").val();
			var prev_utz_qty 	= $("#txt_prev_utilized_qty").val();
			var add_brgt_qty 	= $("#txt_add_brought_site_qty").val();
			var itemdecimal 	= $('#cmb_item_no option:selected').data('itemdecimal');
			if(prev_ots_qty == ""){ prev_ots_qty = 0; }
			if(prev_utz_qty == ""){ prev_utz_qty = 0; }
			if(add_brgt_qty == ""){ add_brgt_qty = 0; }
			
			var ots_qty 	= Number(prev_ots_qty) - Number(prev_utz_qty) + Number(add_brgt_qty);
				ots_qty = ots_qty.toFixed(itemdecimal);
			$("#txt_since_outstand_qty").val(ots_qty)
		}
		
		
		/*function CalculateUptoDateAmt(){
			var utilized_qty 	= $("#txt_prev_utilized_qty").val();
			var prev_ots_qty 	= $("#txt_prev_outstand_qty").val();
			var since_ots_qty 	= $("#txt_since_outstand_qty").val();
			var upto_dt_amt 	= 0;
			var itemrate 		= $('#cmb_item_no option:selected').data('itemrate');
			var itemdecimal 	= $('#cmb_item_no option:selected').data('itemdecimal');
			var perc 			= $("#txt_perc").val();
			$("#txt_upto_amount").val(0);
			if((perc == "")||(perc == 0)){
				perc = 100;
			}
			var red_rate = Number(itemrate)*Number(perc)/100;
			$("#txt_reduce_rate").val(red_rate.toFixed(2));
			
			/// Previous OutStanding Quantity is Zero
			if(prev_ots_qty == 0){ 
				if(Number(since_ots_qty) > Number(utilized_qty)){ 
					var balQty = Number(since_ots_qty)-Number(utilized_qty);
					upto_dt_amt = Number(balQty) * Number(red_rate);
					$("#txt_upto_amount").val(upto_dt_amt.toFixed(2));
				}
			}
			
		}*/
		
		
		$.fn.validateItemNo = function(event) { 
			if($("#cmb_item_no").val()==""){ 
				var a="Please Select Item No.";
				swal(a, "", "");
				$("#cmb_item_no").css("border", "#f73636 solid 1px");
				event.preventDefault();
				event.returnValue = false;
			}else{
				var a="";
				$("#cmb_item_no").css("border", "#98D8FE solid 1px");
			}
		}
		
	});
</script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<style>
	.dataTable{
		font-weight:bold;
		font-size: 11px;
	}
	.DisableInput{
		background-color:#E0E0E0;
		pointer-events:none;
	}
	#table1{
		background-color:#EEF4F8;
		margin-top:5px;
		border-radius:8px;
		border:1px solid #9BB6C9;
		border-bottom:none;
		font-size:11px;
	}
	#table1:hover{
		background-color:#D2EEFF;
	}
	.extraItemTextbox{
		width:95%;
		text-align:center;
		height: 21px;
	}
	#table2 th{
		background-color:#EEF4F8;
		border:1px solid #9BB6C9;
		font-size:11px;
		line-height: 1.2;
		padding: 2px 4px;
	}
	#table2 td{
		padding: 2px 4px;
	}
	#table1, #table2{
		width:99%;
	}
	.dheadtable{
		box-sizing: content-box;
		margin: 0 auto;
		clear: both;
		border-collapse: collapse;
		border-spacing: 0;
	}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="title">Secured Advance</div>
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1" style="overflow:auto">
						<?php if($selectSql == true){ ?>
							<div class="box-container box-container-lg">
								<div class="div12">
									<div class="card cabox">
										<div class="face-static">
											<div class="card-header inkblue-card" align="left">&nbsp;Secured Advance Details <span id="CourseChartDuration"></span></div>
											<div class="card-body padding-1 ChartCard" id="CourseChart">
												<div class="divrowbox pt-2">
													<div class="row clearrow"></div>
													<div class="div12 pd-lr-1">
														<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="dataTable" id="table2">
															<tr>
																<th colspan="15" align="center">Account of Secured Advance allowed on the Security of Materials Brought to Site<!-- - As per Previous Bill--></th>
															</tr>
															<tr>
																<th align="center">Item No</th>
																<th align="center">Qty from Prev Bill</th>
																<th align="center">Deduct Qty Utilized</th>
																<th align="center">Add Qty brought to site</th>
																<th align="center">Outstanding Qty</th>
																<th align="center">Full Rate</th>
																<th align="center">Description</th>
																<th align="center">Unit</th>
																<th align="center" nowrap="nowrap">( % )</th>
																<th align="center">Reduced Rate</th>
																<th align="center">Upto date amt</th>
																<th align="center">Divisional Officer Ref.</th>
																<th align="center">reason for Non-Clear.</th>
																<th align="center" colspan="2">Action</th>
															</tr>
															<!--<tr style="height:1px;"><td colspan="15" style="height:10px;"></td></tr>-->
															<tr>
																<!--<td><span style="font-weight:bold; font-size:13px; color:#099B29; cursor:pointer" onClick="AppendRow(1)" data-id="1">(<b>+</b>)</span></td>-->
																<td align="center" width="8%">
																	<select name="cmb_item_no" id="cmb_item_no" class="extraItemTextbox" onChange="getSAItemDescription();">
																		<option value="">-Select-</option>
																		<?php echo $objBind->BindAllSecAdvItems($sheetid); ?>
																	</select>
																</td>
																<td align="center"><input type="text" name="txt_prev_outstand_qty" id="txt_prev_outstand_qty" class="extraItemTextbox tQty"></td>
																<td align="center"><input type="text" name="txt_prev_utilized_qty" id="txt_prev_utilized_qty" class="extraItemTextbox tQty" data-rowid=""></td>
																<td align="center"><input type="text" name="txt_add_brought_site_qty" id="txt_add_brought_site_qty" class="extraItemTextbox tQty" data-rowid=""></td>
																<td align="center"><input type="text" name="txt_since_outstand_qty" id="txt_since_outstand_qty" class="extraItemTextbox tQty"></td>
																<td align="center"><input type="text" name="txt_full_rate" id="txt_full_rate" class="extraItemTextbox"></td>
																<td align="center"><input type="text" name="txt_item_desc" id="txt_item_desc" class="extraItemTextbox"></td>
																<td align="center"><input type="text" name="txt_item_unit" id="txt_item_unit" class="extraItemTextbox DisableInput" style="width:92%"></td>
																<td align="center"><input type="text" name="txt_perc" id="txt_perc" class="extraItemTextbox" style="width:92%"></td>
																<td align="center"><input type="text" name="txt_reduce_rate" id="txt_reduce_rate" class="extraItemTextbox DisableInput"></td>
																<td align="center"><input type="text" name="txt_upto_amount" id="txt_upto_amount" class="extraItemTextbox DisableInput"></td>
																<td align="center"><input type="text" name="txt_div_officer_ref" id="txt_div_officer_ref" class="extraItemTextbox DisableInput"></td>
																<td align="center"><input type="text" name="txt_non_clear_reason" id="txt_non_clear_reason" class="extraItemTextbox DisableInput"></td>
																<td colspan="2" align="center"><input type="button" name="btn_add" id="btn_add" class="buttonstyle" value=" ADD " onClick="addrow()"></td>
															</tr>
															<!--<tr>
																<td colspan="12" align="center">No Prevoius Secured Advance Records Found</td>
															</tr>-->
												<?php
												$ARR = array(120,130,140,150); $R=0; $PCnt = 0; $Total_Ots_Amt = 0;
													while($List = mysql_fetch_object($selectSql)){
														$UtiQty = 0; $PCnt++;
														$selectUtiQtyQry = "select mbtotal from mbookgenerate where sheetid = '$sheetid' and subdivid = '$List->subdivid'";
														$selectUtiQtySql = mysql_query($selectUtiQtyQry);
														if($selectUtiQtySql == true){
															$UQList = mysql_fetch_object($selectUtiQtySql);
															$UtiQty = $UQList->mbtotal;
														}
														//$UtiQty = $ARR[$R]; $R++;//$List->utz_qty_this_bill;
														$List->utz_qty_this_bill;
														//$bal_ots_qty = $List->ots_qty_since_bill - $UtiQty;
														$bal_ots_qty = $List->qty_from_prev_bill - $UtiQty;
														
														$Prev_Bill_Str = "";
														$sadtid = $List->sadtid;
														$schduledetails = 	getschduledetails($sheetid,$List->subdivid);
														$rateandunit 	= 	explode('*',$schduledetails);
														$unit 			= 	$rateandunit[1];
														//echo $unit;exit;
														//$Prev_Bill_Str = $List->subdivid."@*@".$List->itemno."@*@".$List->ots_qty_prev_bill."@*@".$UtiQty."@*@".$bal_ots_qty."@*@".$List->full_asses_rate."@*@".$List->red_perc."@*@".$List->red_rate."@*@".$List->upto_dt_amt."@*@".$List->div_off_ref."@*@".$List->reason_non_clear;
														//$Prev_Bill_Str = $List->subdivid."@*@".$List->itemno."@*@".$List->qty_from_prev_bill."@*@".$UtiQty."@*@".$bal_ots_qty."@*@".$List->full_asses_rate."@*@".$List->red_perc."@*@".$List->red_rate."@*@".$List->upto_dt_amt."@*@".$List->div_off_ref."@*@".$List->reason_non_clear;
												?>
															<tr>
																<td align="center"><?php echo $List->itemno; ?></td>
																<td align="center">
																<!-----  NEED TO CHANGE HERE ------>
																
																<?php 
																//echo $List->ots_qty_since_bill; 
																echo $List->qty_from_prev_bill;
																?>
																
																
																</td>
																<td align="center">
																<?php //echo $UtiQty; ?>
																<input type="text" name="txt_uti_qty<?php echo $sadtid; ?>" id="txt_uti_qty<?php echo $sadtid; ?>" data-rowid="<?php echo $sadtid; ?>" data-red_rate="<?php echo $List->red_rate; ?>" data-prev_qty="<?php echo $List->qty_from_prev_bill; ?>" class="extraItemTextbox uts" style="height:25px;" value="<?php echo $UtiQty; ?>">
																</td>
																<td align="center">
																<?php //echo $UtiQty; ?>
																<input type="text" name="txt_add_qty<?php echo $sadtid; ?>" id="txt_add_qty<?php echo $sadtid; ?>" data-rowid="<?php echo $sadtid; ?>" data-red_rate="<?php echo $List->red_rate; ?>" data-prev_qty="<?php echo $List->qty_from_prev_bill; ?>" class="extraItemTextbox uts" style="height:25px;">
																</td>
																<td align="center">
																<?php //echo $bal_ots_qty; ?>
																<input type="text" name="txt_ots_qty<?php echo $sadtid; ?>" id="txt_ots_qty<?php echo $sadtid; ?>" class="extraItemTextbox" style="height:25px;" value="<?php echo $bal_ots_qty; ?>">
																</td>
																<td align="center"><?php echo $List->full_asses_rate; ?></td>
																<td align="center"><?php echo $List->description; ?></td>
																<td align="center"><?php echo $unit; ?></td>
																<td align="center"><?php echo $List->red_perc; ?></td>
																<td align="center"><?php echo $List->red_rate; ?></td>
																<td align="center">
																<?php
																$upto_dt_amt =  round(($bal_ots_qty*$List->red_rate),2);
																//echo $upto_dt_amt;
																//echo $List->upto_dt_amt; 
																//$Total_Ots_Amt = $Total_Ots_Amt + $List->upto_dt_amt;
																$Total_Ots_Amt = $Total_Ots_Amt + $upto_dt_amt; 
																$Prev_Bill_Str = $List->subdivid."@*@".$List->itemno."@*@".$List->qty_from_prev_bill."@*@".$UtiQty."@*@".$bal_ots_qty."@*@".$List->full_asses_rate."@*@".$List->red_perc."@*@".$List->red_rate."@*@".$upto_dt_amt."@*@".$List->div_off_ref."@*@".$List->reason_non_clear."@*@".$sadtid."@*@".$List->description;
																?>
																<input type="text" name="txt_prev_upto_amt[]" id="txt_prev_upto_amt<?php echo $sadtid; ?>" value="<?php echo $upto_dt_amt; ?>" class="extraItemTextbox" style="height:25px;">
																<input type="hidden" name="txt_prev_amount<?php echo $sadtid; ?>" id="txt_prev_amount<?php echo $sadtid; ?>" value="<?php echo $upto_dt_amt; ?>" class="extraItemTextbox" style="height:25px;">
																</td>
																<td align="center"><?php echo $List->div_off_ref; ?></td>
																<td align="center">
																<?php echo $List->reason_non_clear; ?>
																<input type="hidden" name="txt_prev_bill_str[]" id="txt_prev_bill_str" value="<?php echo $Prev_Bill_Str; ?>">
																</td>
																<td align="center" colspan="2">&nbsp;</td>
															</tr>
														<?php } if($PCnt == 0){ ?>
															<tr>
																<td colspan="15" align="center" style="color:red">No Prevoius Secured Advance Records Found</td>
															</tr>
														<?php } } ?>
														</table>
														<span id="add_hidden"></span>
														<input type="hidden" value="" name="add_set_a1" id="add_set_a1"/>
														<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
															<div class="buttonsection">
																<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
															</div>
														<?php if($post == "GO"){ ?>
															<div class="buttonsection">
																<input type="submit" name="btn_save" id="btn_save" value=" Submit "/>
															</div>
														<?php } ?>
														</div>
														<input type="hidden" name='txt_post' id='txt_post' class="extraItemTextbox" value="<?php if($post == "GO"){ echo $post; } ?>">
														<?php $net_amount = round(($Total_Ots_Amt - $PrevSaAmount),2); ?>
														<div class="row clearrow"></div>
														<div class="row clearrow"></div>
														<div class="row clearrow"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<input type="hidden" name='txt_sid' id='txt_sid' class="extraItemTextbox" value="<?php if($post == "GO"){ echo $sid; } ?>">
						<input type="hidden" name='txt_prev_rbn' id='txt_prev_rbn' class="extraItemTextbox" value="<?php if($post == "GO"){ echo $PrevRbn; } ?>">
						<input type="hidden" name='txt_submit_val' id='txt_submit_val' class="extraItemTextbox">
						<input type="text" name="txt_total_ots_label" id="txt_total_ots_label" class="fixed-input1" readonly="" value="Total outstanding Amt">
						<input type="text" name="txt_total_ots_amt" id="txt_total_ots_amt" class="fixed-input2" readonly="" value="<?php echo round($Total_Ots_Amt,2); ?>">
						<input type="text" name="txt_deduct_prev_label" id="txt_deduct_prev_label" class="fixed-input3" readonly="" value="Deduct Previous Amt">
						<input type="text" name="txt_deduct_prev_amt" id="txt_deduct_prev_amt" class="fixed-input4" readonly="" value="<?php echo round($PrevSaAmount,2); ?>" readonly="">
						<input type="text" name="txt_net_amt_label" id="txt_net_amt_label" class="fixed-input5" readonly="" value="Net Amt">
						<input type="text" name="txt_net_amt" id="txt_net_amt" class="fixed-input6" readonly="" value="<?php echo round($net_amount,2); ?>" readonly="">
						
						<input type="hidden" name='cmb_shortname' id='cmb_shortname' class="extraItemTextbox" value="<?php if($post == "GO"){ echo $sheetid; } ?>">
						<input type="hidden" name='secadv_type' id='secadv_type' class="extraItemTextbox" value="<?php if($post == "GO"){ echo $secadv_type; } ?>">
						<input type="hidden" name='txt_rbn' id='txt_rbn' class="extraItemTextbox" value="<?php if($post == "GO"){ echo $rbn; } ?>">
						<input type="hidden" name='txt_mbookno' id='txt_mbookno' class="extraItemTextbox" value="<?php if($post == "GO"){ echo $mbook_no; } ?>">
						<input type="hidden" name='txt_mbook_page_no' id='txt_mbook_page_no' class="extraItemTextbox" value="<?php if($post == "GO"){ echo $mbook_page; } ?>">
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
				
				$(function () {
					var post = $('#txt_post').val();
					if(post == "GO"){
						getAllItem();
					}
					
					var KillEvent = 0;
					$('#btn_save').on('click', function(event){ 
						
						if(KillEvent == 0){
							if($('#table2 tr').length <= 3){
								BootstrapDialog.alert("Please add altleast one row for secured advance");
								event.preventDefault();
								event.returnValue = false;
							}else{
								event.preventDefault();
								BootstrapDialog.confirm({
									title: 'Confirmation Message',
									message: 'Are you sure want to save Secured Advance ?',
									closable: false, // <-- Default value is false
									draggable: false, // <-- Default value is false
									btnCancelLabel: 'Cancel', // <-- Default value is 'Cancel',
									btnOKLabel: 'Ok', // <-- Default value is 'OK',
									callback: function(result) {
										// result will be true if button was click, while it will be false if users close the dialog directly.
										if(result){
											KillEvent = 1;
											$("#btn_save").trigger( "click" );
										}else {
											//alert('Nope.');
											KillEvent = 0;
										}
									}
								});
							}
						}
					});
					
					$('body').on("change","#txt_add_brought_site_qty", function(event){ 
						var ItemNo = $("#cmb_item_no").val();
						var BroughtSiteQty = $(this).val();
						if((ItemNo != '')&&(ItemNo != '')){
							var AgmtQty = $("#cmb_item_no option:selected" ).attr('data-itemtotalqty');
							if(Number(BroughtSiteQty)>Number(AgmtQty)){
								swal("", "Brought to site qty should be less than Agreement Qty.", "error");
								$("#cmb_item_no").val('');
								$("#txt_prev_outstand_qty").val('');
								$("#txt_prev_utilized_qty").val('');
								$("#txt_add_brought_site_qty").val('');
								$("#txt_since_outstand_qty").val('');
								$("#txt_full_rate").val('');
								$("#txt_item_desc").val('');
								$("#txt_perc").val('');
								$("#txt_item_unit").val('');
								$("#txt_reduce_rate").val('');
								$("#txt_upto_amount").val('');
								$("#txt_div_officer_ref").val('');
								$("#txt_non_clear_reason").val('');
							}
						}
					});
				});
			</script>
        </form>
    </body>
</html>
