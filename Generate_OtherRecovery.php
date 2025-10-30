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
	$abstract_net_amt 	= 	trim($_POST['txt_abstract_amt']);
    $rbn 				= 	trim($_POST['txt_rbn']);
	$bill_amt_gst 		= 	trim($_POST['txt_bill_amt_gst']);
    $sd_percent 		= 	trim($_POST['txt_sd_perc']);
    $sd_amt 			= 	trim($_POST['txt_sd']);
    $wct_percent 		= 	0;//trim($_POST['txt_wct_perc']);
    $wct_amt 			= 	0;//trim($_POST['txt_wct']);
	$vat_percent 		= 	0;//trim($_POST['txt_vat_perc']);
    $vat_amt 			= 	0;//trim($_POST['txt_vat']);
	$mob_adv_percent 	= 	trim($_POST['txt_mob_adv_perc']);
	$mob_adv_amt 		= 	trim($_POST['txt_mob_adv']);
	$lw_cess_percent 	= 	trim($_POST['txt_lw_cess_perc']);
	$lw_cess_amt 		= 	trim($_POST['txt_lw_cess']);
	$incometax_percent 	= 	trim($_POST['txt_incometax_perc']);
	$incometax_amt 		= 	trim($_POST['txt_incometax']);
	$it_cess_percent 	= 	trim($_POST['txt_ITcess_perc']);
	$it_cess_amt 		= 	trim($_POST['txt_ITcess']);
	$it_edu_percent 	= 	trim($_POST['txt_ITEcess_perc']);
	$it_edu_amt 		= 	trim($_POST['txt_ITEcess']);
	$land_rent 			= 	trim($_POST['txt_rent_land']);
	$liquid_damage 		= 	trim($_POST['txt_liquid_damage']);
	$other_recovery_1 	= 	trim($_POST['txt_other_recovery_1']);
	$other_recovery_2 	= 	trim($_POST['txt_other_recovery_2']);
	$other_recovery_3 	= 	trim($_POST['txt_other_recovery_3']);
	$other_recovery_1_desc 	= 	trim($_POST['txt_other_recovery_1_desc']);
	$other_recovery_2_desc	= 	trim($_POST['txt_other_recovery_2_desc']);
	$other_recovery_3_desc	= 	trim($_POST['txt_other_recovery_3_desc']);
	$nodep_machine 		= 	trim($_POST['txt_nodep_machine']);
	$nodep_mp 			= 	trim($_POST['txt_nodep_mp']);
	$nonsubmission_qa	=	trim($_POST['txt_nonsubmission_qa']);
	
	$sgst_perc			=	trim($_POST['txt_sgst_perc']);
	$sgst_amt			=	trim($_POST['txt_sgst']);
	$cgst_perc			=	trim($_POST['txt_cgst_perc']);
	$cgst_amt			=	trim($_POST['txt_cgst']);
	$igst_perc			=	trim($_POST['txt_igst_perc']);
	$igst_amt			=	trim($_POST['txt_igst']);
	$gst_rate			=	trim($_POST['txt_gst_rate']);
	$gst_amt			=	trim($_POST['txt_gst_amt']);
	$pan_type			=	trim($_POST['txt_pan_type']);
	$is_ldc				=	trim($_POST['txt_is_ldc']);
	
    $erecovery_sql 		= 	"update generate_otherrecovery set "
                                            . "sd_percent = '$sd_percent', "
                                            . "sd_amt = '$sd_amt', "
											. "bill_amt_gst = '$bill_amt_gst', "
											. "gst_rate = '$gst_rate', "
											. "gst_amount = '$gst_amt', "
											. "sgst_tds_perc = '$sgst_perc', "
											. "sgst_tds_amt = '$sgst_amt', "
											. "cgst_tds_perc = '$cgst_perc', "
											. "cgst_tds_amt = '$cgst_amt', "
											. "igst_tds_perc = '$igst_perc', "
											. "igst_tds_amt = '$igst_amt', "
                                            . "wct_percent = '$wct_percent', "
                                            . "wct_amt = '$wct_amt', "
											. "vat_percent = '$vat_percent', "
                                            . "vat_amt = '$vat_amt', "
											. "mob_adv_percent = '$mob_adv_percent', "
											. "mob_adv_amt = '$mob_adv_amt', "
											. "lw_cess_percent = '$lw_cess_percent', "
											. "lw_cess_amt = '$lw_cess_amt', "
											. "is_ldc_appl = '$is_ldc', "
											. "pan_type = '$pan_type', "
											. "incometax_percent = '$incometax_percent', "
											. "incometax_amt = '$incometax_amt', "
											. "it_cess_percent = '$it_cess_percent', "
											. "it_cess_amt = '$it_cess_amt', "
											. "it_edu_percent = '$it_edu_percent', "
											. "it_edu_amt = '$it_edu_amt', "
											. "land_rent = '$land_rent', "
											. "liquid_damage = '$liquid_damage', "
											. "other_recovery_1 = '$other_recovery_1', "
											. "other_recovery_1_desc = '$other_recovery_1_desc', "
											. "other_recovery_2 = '$other_recovery_2', "
											. "other_recovery_2_desc = '$other_recovery_2_desc', "
											. "other_recovery_3 = '$other_recovery_3', "
											. "other_recovery_3_desc = '$other_recovery_3_desc', "
											. "non_dep_machine_equip = '$nodep_machine', "
											. "non_dep_man_power = '$nodep_mp', "
											. "nonsubmission_qa = '$nonsubmission_qa', "
											. "staffid = '$staffid', "
											. "userid = '$userid', "
											. "modifieddate = NOW(), "
                                            . "active = 1 where sheetid = '$sheetid' and rbn = '$rbn'";
											//echo $erecovery_sql;exit;
    $erecovery_query 	= 	mysql_query($erecovery_sql);
    if($erecovery_query == true) 
	{
        $msg = "Recovery Details Stored Successfully ";
		$success = 1;
    }
	else
	{
		$msg = " Something Error...!!! ";
	}
} 
?>

  <?php require_once "Header.html"; ?>
<style>
    .textright
	{
		text-align:right;
		padding-right:2px;
	}
</style>
 <script>
  	 function goBack()
	 {
	   	url = "dashboard.php";
		window.location.replace(url);
	 }
	 function cls()
	 {
	 	document.form.txt_workorder.value = "";
		document.form.txt_abstract_amt.value = "";
		document.form.txt_rbn.value = "";
		document.form.txt_sd_perc.value = "";
		document.form.txt_sd.value = "";
		//document.form.txt_wct_perc.value = "";
		//document.form.txt_wct.value = "";
		//document.form.txt_vat_perc.value = "";
		//document.form.txt_vat.value = "";
		document.form.txt_mob_adv_perc.value = "";
		document.form.txt_mob_adv.value = "";
		document.form.txt_lw_cess_perc.value = "";
		document.form.txt_lw_cess.value = "";
		document.form.txt_incometax_perc.value = "";
		document.form.txt_incometax.value = "";
		document.form.txt_ITcess_perc.value = "";
		document.form.txt_ITcess.value = "";
		document.form.txt_ITEcess_perc.value = "";
		document.form.txt_ITEcess.value = "";
		document.form.txt_rent_land.value = "";
		document.form.txt_liquid_damage.value = "";
		document.form.txt_other_recovery_1.value = "";
		document.form.txt_other_recovery_2.value = "";
		document.form.txt_other_recovery_1_desc.value = "Other Recoveries 1";
		document.form.txt_other_recovery_2_desc.value = "Other Recoveries 2";
		document.form.txt_nodep_machine.value = "";
		document.form.txt_nodep_mp.value = "";
		document.form.txt_nonsubmission_qa.value = "";
		document.form.txt_sec_adv.value = "";
		
		document.form.txt_sgst_perc.value = "";
		document.form.txt_sgst.value = "";
		document.form.txt_cgst_perc.value = "";
		document.form.txt_cgst.value = "";
		document.form.txt_igst_perc.value = "";
		document.form.txt_igst.value = "";
		document.form.txt_gst_rate.value = "";
		document.form.txt_gst_amt.value = "";
		
		
	 }
     function workorderdetail()
     { 
	 		cls();
			if(document.form.cmb_shortname.value == "" || document.form.cmb_shortname.value == 0)
			{
				exit();
				return false;
			}
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
                           // document.form.txt_workname.value 		= name[3];
							document.form.txt_workorder.value 		= name[5];
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
		function getabstractamount()
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
            strURL = "find_abstract_data.php?sheetid="+document.form.cmb_shortname.value;
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
                        var name = data.split("***");
                        for(i = 0; i < name.length; i++)
                        {
							var abstract_net_amt = name[0];
							var sec_adv = name[2];
							document.form.txt_abstract_amt.value = name[0];
							//alert(name[0])
                            document.form.txt_rbn.value = name[1];
							document.form.txt_sec_adv.value = name[2];
                        }
						recovery(abstract_net_amt,sec_adv)

                    }
                }
            }
            xmlHttp.send(strURL);
        }
		function recovery(amount,sec_adv)
     	{ 
			var abstract_net_amt = Number(amount);
			var secured_advance = Number(sec_adv);
			var total_amt_for_it = Number(abstract_net_amt)+Number(secured_advance);
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
                    if (data == "")
                    {
                        alert("No Records Found");
                    }
                    else
                    {
                        var name = data.split("*");
                        //for(i = 0; i < name.length; i++)
                        //{
							//document.form.txt_wct_perc.value 		= name[1];
							document.form.txt_mob_adv_perc.value 	= name[2];
							document.form.txt_lw_cess_perc.value 	= name[3];
							document.form.txt_incometax_perc.value 	= name[4];
							document.form.txt_sd_perc.value 		= name[6];
							//document.form.txt_vat_perc.value 		= name[15];
							document.form.txt_ITcess_perc.value 	= name[16];
							document.form.txt_ITEcess_perc.value 	= name[17];
							document.form.txt_gst_rate.value 		= name[18];
							document.form.txt_pan_type.value 		= name[23];
							document.form.txt_is_ldc.value 			= name[24];
							var GstIncExe = name[25];
							if(GstIncExe == "E"){
								var AmtForGstCalc 	= Number(abstract_net_amt);
								var GstAmount 		= Number(AmtForGstCalc)*Number(name[18])/100;
							}else{
								var AmtForGstCalc 	= Number(abstract_net_amt)*100/(Number(name[18])+100);
								var GstAmount 		= Number(AmtForGstCalc)*Number(name[18])/100;
							}
							document.form.txt_gst_amt.value = GstAmount.toFixed(2);
							document.form.txt_bill_amt_gst.value = AmtForGstCalc.toFixed(2);
							

							if(name[19] == "Y"){
								document.form.txt_igst_perc.value 	= name[22];
								document.form.txt_sgst_perc.value 	= "0.00";
								document.form.txt_sgst.value 		= "0.00";
								document.form.txt_cgst_perc.value 	= "0.00";
								document.form.txt_cgst.value 		= "0.00";
								var IGstAmt = Number(AmtForGstCalc)*Number(name[22])/100;
								document.form.txt_igst.value 	= IGstAmt.toFixed(2);
								//document.getElementById("igstLab").innerHTML  = name[18]+"% of";
							}else{
								document.form.txt_sgst_perc.value 	= name[20];
								document.form.txt_cgst_perc.value 	= name[21];
								document.form.txt_igst_perc.value 	= "0.00";
								document.form.txt_igst.value 		= "0.00";
								var SGstAmt = Number(AmtForGstCalc)*Number(name[20])/100;
								var CGstAmt = Number(AmtForGstCalc)*Number(name[21])/100;
								document.form.txt_cgst.value = SGstAmt.toFixed(2);
								document.form.txt_sgst.value = CGstAmt.toFixed(2);
								//document.getElementById("cgstLab").innerHTML  = name[18]+"% of";
								//document.getElementById("sgstLab").innerHTML  = name[18]+"% of";
							}
							
							var wct_amt 		= Number(abstract_net_amt)*Number(name[1])/100;
							var mob_adv_amt 	= Number(abstract_net_amt)*Number(name[2])/100;
							var lw_cess_amt 	= Number(abstract_net_amt)*Number(name[3])/100;
							
							//var it_amt 			= Number(abstract_net_amt)*Number(name[4])/100;
							
							var it_amt 			= Number(total_amt_for_it)*Number(name[4])/100;
							
							
							var sd_amt 			= Number(abstract_net_amt)*Number(name[6])/100;
							var vat_amt 		= Number(abstract_net_amt)*Number(name[15])/100;
							var it_cess_amt 	= Number(it_amt)*Number(name[16])/100;
							var it_ecess_amt 	= Number(it_amt)*Number(name[17])/100;
							
							//document.form.txt_wct.value 		= wct_amt.toFixed(2);
							document.form.txt_mob_adv.value 	= mob_adv_amt.toFixed(2);
							document.form.txt_lw_cess.value 	= lw_cess_amt.toFixed(2);
							document.form.txt_incometax.value 	= it_amt.toFixed(2);
							document.form.txt_sd.value 			= sd_amt.toFixed(2);
							//document.form.txt_vat.value 		= vat_amt.toFixed(2);
							document.form.txt_ITcess.value 		= it_cess_amt.toFixed(2);
							document.form.txt_ITEcess.value 	= it_ecess_amt.toFixed(2);
							
                        //}

                    }
                }
            }
            xmlHttp.send(strURL);
        }
</script>
<script>
   $(function () {
        $( "#txt_initial_date" ).datepicker({
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
                });
		$( "#txt_date" ).datepicker({
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
		$.fn.validateabstractamount = function(event) { 
					if($("#txt_abstract_amt").val()==""){ 
					var a="Please Enter Abstract Amount";
					$('#val_abstract_amt').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_abstract_amt').text(a);
					}
				}
		$.fn.validaterbnno = function(event) { 
					if($("#txt_rbn").val()==""){ 
					var a="Please Enter RBN Number";
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
		$.fn.validategstbillamt = function(event) { 
					if($("#txt_bill_amt_gst").val()==""){ 
					var a="Please Enter Bill Amount for GST";
					$('#val_bill_amt_gst').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_bill_amt_gst').text(a);
					}
				}		
				
		$.fn.validatesdpercent = function(event) { 
					if($("#txt_sd_perc").val()==""){ 
					var a="Please Enter Secured Deposit %";
					$('#val_sd_perc').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_sd_perc').text(a);
					}
				}
		$.fn.validatesd = function(event) { 
					if($("#txt_sd").val()==""){ 
					var a="Please Enter Secured Deposit";
					$('#val_sd').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_sd').text(a);
					}
				}
		/*$.fn.validatewctpercent = function(event) { 
					if($("#txt_wct_perc").val()==""){ 
					var a="Please Enter WCT %";
					$('#val_wct_perc').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_wct_perc').text(a);
					}
				}*/
		/*$.fn.validatewct = function(event) { 
					if($("#txt_wct").val()==""){ 
					var a="Please Enter WCT";
					$('#val_wct').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_wct').text(a);
					}
				}	*/		
		/*$.fn.validatevatpercent = function(event) { 
					if($("#txt_vat_perc").val()==""){ 
					var a="Please Enter VAT %";
					$('#val_vat_perc').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_vat_perc').text(a);
					}
				}	*/
		/*$.fn.validatevat = function(event) { 
					if($("#txt_vat").val()==""){ 
					var a="Please Enter VAT";
					$('#val_vat').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_vat').text(a);
					}
				}*/		
		$.fn.validatemobadvpercent = function(event) { 
					if($("#txt_mob_adv_perc").val()==""){ 
					var a="Please Enter Mobilization Advance %";
					$('#val_mob_adv_perc').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_mob_adv_perc').text(a);
					}
				}		
		$.fn.validatemobadv = function(event) { 
					if($("#txt_mob_adv").val()==""){ 
					var a="Please Enter Mobilization Advance";
					$('#val_mob_adv').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_mob_adv').text(a);
					}
				}		
		$.fn.validatelwpercent = function(event) { 
					if($("#txt_lw_cess_perc").val()==""){ 
					var a="Please Enter LW Cess %";
					$('#val_lw_cess_perc').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_lw_cess_perc').text(a);
					}
				}		
		$.fn.validatelwcess = function(event) { 
					if($("#txt_lw_cess").val()==""){ 
					var a="Please Enter Labour Welfare Cess";
					$('#val_lw_cess').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_lw_cess').text(a);
					}
				}		
		$.fn.validateitpercent = function(event) { 
					if($("#txt_incometax_perc").val()==""){ 
					var a="Please Enter IT %";
					$('#val_incometax_perc').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_incometax_perc').text(a);
					}
				}		
		$.fn.validateincometax = function(event) { 
					if($("#txt_incometax").val()==""){ 
					var a="Please Enter Income Tax";
					$('#val_incometax').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_incometax').text(a);
					}
				}		
		$.fn.validateitcesspercent = function(event) { 
					if($("#txt_ITcess_perc").val()==""){ 
					var a="Please Enter IT Cess %";
					$('#val_ITcess_perc').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_ITcess_perc').text(a);
					}
				}		
		$.fn.validateitcess = function(event) { 
					if($("#txt_ITcess").val()==""){ 
					var a="Please Enter IT Cess";
					$('#val_ITcess').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_ITcess').text(a);
					}
				}		
		$.fn.validateitEpercent = function(event) { 
					if($("#txt_ITEcess_perc").val()==""){ 
					var a="Please Enter ITE Cess %";
					$('#val_ITEcess_perc').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_ITEcess_perc').text(a);
					}
				}		
		$.fn.validateitE = function(event) { 
					if($("#txt_ITEcess").val()==""){ 
					var a="Please Enter IT Educational Cess";
					$('#val_ITEcess').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_ITEcess').text(a);
					}
				}		
		$.fn.validatelandrent = function(event) { 
					if($("#txt_rent_land").val()==""){ 
					var a="Please Enter Land Rent";
					$('#val_rent_land').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_rent_land').text(a);
					}
				}
		$.fn.validateliquiddamage = function(event) { 
					if($("#txt_liquid_damage").val()==""){ 
					var a="Please Enter Liquidated Damages";
					$('#val_liquid_damage').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_liquid_damage').text(a);
					}
				}		
		$.fn.validateotherrecovery_1 = function(event) { 
					if($("#txt_other_recovery_1").val()==""){ 
					var a="Please Enter Other Recovery";
					$('#val_other_recovery_1').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_other_recovery_1').text(a);
					}
				}
		$.fn.validateotherrecovery_1_desc = function(event) { 
					if($("#txt_other_recovery_1_desc").val()==""){ 
					var a="Please Enter Other Recovery Description";
					$('#val_other_recovery_1_desc').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_other_recovery_1_desc').text(a);
					}
				}	
		$.fn.validateotherrecovery_2 = function(event) { 
					if($("#txt_other_recovery_2").val()==""){ 
					var a="Please Enter Other Recovery";
					$('#val_other_recovery_2').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_other_recovery_2').text(a);
					}
				}	
		$.fn.validateotherrecovery_2_desc = function(event) { 
					if($("#txt_other_recovery_2_desc").val()==""){ 
					var a="Please Enter Other Recovery Description";
					$('#val_other_recovery_2_desc').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_other_recovery_2_desc').text(a);
					}
				}	
				
				
		$.fn.validateotherrecovery_3 = function(event) { 
					if($("#txt_other_recovery_3").val()==""){ 
					var a="Please Enter Other Recovery";
					$('#val_other_recovery_3').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_other_recovery_3').text(a);
					}
				}	
		$.fn.validateotherrecovery_3_desc = function(event) { 
					if($("#txt_other_recovery_3_desc").val()==""){ 
					var a="Please Enter Other Recovery Description";
					$('#val_other_recovery_3_desc').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_other_recovery_3_desc').text(a);
					}
				}	
				
		$.fn.validatesgstperc = function(event) { 
					if($("#txt_sgst_perc").val()==""){ 
					var a="SGST % should not be blank";
					$('#val_sgst_perc').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_sgst_perc').text(a);
					}
				}
				
		$.fn.validatesgst = function(event) { 
					if($("#txt_sgst").val()==""){ 
					var a="SGST amount should not be blank";
					$('#val_sgst').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_sgst').text(a);
					}
				}
		$.fn.validatecgstperc = function(event) { 
					if($("#txt_cgst_perc").val()==""){ 
					var a="CGST % should not be blank";
					$('#val_cgst_perc').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_cgst_perc').text(a);
					}
				}
		$.fn.validatecgst = function(event) { 
					if($("#txt_cgst").val()==""){ 
					var a="CGST amount should not be blank";
					$('#val_cgst').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_cgst').text(a);
					}
				}
		$.fn.validateigstperc = function(event) { 
					if($("#txt_igst_perc").val()==""){ 
					var a="IGST % should not be blank";
					$('#val_igst_perc').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_igst_perc').text(a);
					}
				}
		$.fn.validateigst = function(event) { 
					if($("#txt_igst").val()==""){ 
					var a="IGST amount should not be blank";
					$('#val_igst').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
					else{
					var a="";
					$('#val_igst').text(a);
					}
				}
				
				
		$("#cmb_shortname").change(function(event){
		$(this).validateshortname(event);
		});
		$("#txt_workorder").keyup(function(event){
		$(this).validateworkorder(event);
		});
		$("#txt_abstract_amt").keyup(function(event){
		$(this).validateabstractamount(event);
		});
		$("#txt_rbn").keyup(function(event){
		$(this).validaterbnno(event);
		});
		$("#txt_bill_amt_gst").keyup(function(event){
		$(this).validategstbillamt(event);
		});
		$("#txt_sd_perc").keyup(function(event){
		$(this).validatesdpercent(event);
		});
		$("#txt_sd").keyup(function(event){
		$(this).validatesd(event);
		});
		/*$("#txt_wct_perc").keyup(function(event){
		$(this).validatewctpercent(event);
		});
		$("#txt_wct").keyup(function(event){
		$(this).validatewct(event);
		});
		$("#txt_vat_perc").keyup(function(event){
		$(this).validatevatpercent(event);
		});
		$("#txt_vat").keyup(function(event){
		$(this).validatevat(event);
		});*/
		$("#txt_mob_adv_perc").keyup(function(event){
		$(this).validatemobadvpercent(event);
		});
		$("#txt_mob_adv").keyup(function(event){
		$(this).validatemobadv(event);
		});
		$("#txt_lw_cess_perc").keyup(function(event){
		$(this).validatelwpercent(event);
		});
		$("#txt_lw_cess").keyup(function(event){
		$(this).validatelwcess(event);
		});
		$("#txt_incometax_perc").keyup(function(event){
		$(this).validateitpercent(event);
		});
		$("#txt_incometax").keyup(function(event){
		$(this).validateincometax(event);
		});
		$("#txt_ITcess_perc").keyup(function(event){
		$(this).validateitcesspercent(event);
		});
		$("#txt_ITcess").keyup(function(event){
		$(this).validateitcess(event);
		});
		$("#txt_ITEcess_perc").keyup(function(event){
		$(this).validateitEpercent(event);
		});
		$("#txt_ITEcess").keyup(function(event){
		$(this).validateitE(event);
		});
		$("#txt_rent_land").keyup(function(event){
		$(this).validatelandrent(event);
		});
		$("#txt_liquid_damage").keyup(function(event){
		$(this).validateliquiddamage(event);
		});
		$("#txt_other_recovery_1").keyup(function(event){
		$(this).validateotherrecovery_1(event);
		});
		$("#txt_other_recovery_1_desc").keyup(function(event){
		$(this).validateotherrecovery_1_desc(event);
		});
		$("#txt_other_recovery_2").keyup(function(event){
		$(this).validateotherrecovery_2(event);
		});
		$("#txt_other_recovery_2_desc").keyup(function(event){
		$(this).validateotherrecovery_2_desc(event);
		});
		$("#txt_other_recovery_3").keyup(function(event){
		$(this).validateotherrecovery_3(event);
		});
		$("#txt_other_recovery_3_desc").keyup(function(event){
		$(this).validateotherrecovery_3_desc(event);
		});
		$("#top").submit(function(event){
		$(this).validateshortname(event);
		$(this).validateworkorder(event);
		$(this).validateabstractamount(event);
		$(this).validaterbnno(event);
		$(this).validategstbillamt(event);
		
		$(this).validatesdpercent(event);
		$(this).validatesd(event);
		//$(this).validatewctpercent(event);
		//$(this).validatewct(event);
		//$(this).validatevatpercent(event);
		//$(this).validatevat(event);
		$(this).validatemobadvpercent(event);
		$(this).validatemobadv(event);
		$(this).validatelwpercent(event);
		$(this).validatelwcess(event);
		$(this).validateitpercent(event);
		$(this).validateincometax(event);
		$(this).validateitcesspercent(event);
		$(this).validateitcess(event);
		$(this).validateitEpercent(event);
		$(this).validateitE(event);
		$(this).validatelandrent(event);
		$(this).validateliquiddamage(event);
		$(this).validateotherrecovery_1(event);
		$(this).validateotherrecovery_2(event);
		$(this).validateotherrecovery_3(event);
		$(this).validateotherrecovery_1_desc(event);
		$(this).validateotherrecovery_2_desc(event);
		$(this).validateotherrecovery_3_desc(event);
		
		$(this).validatesgstperc(event);
		$(this).validatesgst(event);
		$(this).validatecgstperc(event);
		$(this).validatecgst(event);
		$(this).validateigstperc(event);
		$(this).validateigst(event);
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
                <div class="title">Generate - General Recoveries</div>
                <div class="container_12">
                    <div class="grid_12">

						<!--<div align="right"><a href="View_Other_recovery_generate_Bill.php">View</a>&nbsp;&nbsp;&nbsp;</div>-->
                        <blockquote class="bq1" style="overflow-y:auto;">
							<input type="hidden" name="hid_sheetid" id="hid_sheetid" value="<?php if($_GET['sheet_id'] != ''){ echo $_GET['sheet_id']; } ?>">
						<div>
                        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
                                <tr><td width="18%">&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Short Name</td> 
                                    <td>
										<select name="cmb_shortname" id="cmb_shortname" class="textboxdisplay" style="width:465px" onChange="workorderdetail();getabstractamount();">
											<option value="">--------- Select Work Short Name ---------</option>
											<?php echo $objBind->BindWorkOrderNo(0);?>
										</select>
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td  align="center" class="labeldisplay" id="val_shortname" style="color:red" colspan="">&nbsp;</td></tr>
                                
								<!--<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Name of Work</td>
                                    <td><textarea name='txt_workname' id='txt_workname' class="textboxdisplay" rows="6" style="width: 465px;"><?php if($_GET['sheet_id'] != ''){ echo $work_name; } ?></textarea></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_wname" style="color:red" colspan="">&nbsp;</td></tr>-->
                                
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Work Order No.</td>
                                    <td><input type="text" name='txt_workorder' id='txt_workorder' class="textboxdisplay" value="" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_workorder" style="color:red" colspan="">&nbsp;</td></tr>
								
								<!--<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Electricity Bill No.</td>
                                    <td><input type="text" name='txt_billno' id='txt_billno' class="textboxdisplay" value="" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_billno" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Meter No.</td>
                                    <td><input type="text" name='txt_meterno' id='txt_meterno' class="textboxdisplay" value="" style="width: 465px;"></td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_meterno" style="color:red" colspan="">&nbsp;</td></tr>-->
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Abstract Net Amount (Rs)</td>
                                    <td>
										<input type="text" name='txt_abstract_amt' id='txt_abstract_amt' class="textboxdisplay textright" value="" style="width: 120px;">
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;
										<label class="label">RAB No. </label>
										<input type="text" name='txt_rbn' id='txt_rbn' class="textboxdisplay textright" value="" style="width: 120px;">
									</td>
                                </tr>
 								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_abstract_amt"></span>
									<span id="val_rbn"></span>
									&nbsp;
									</td>
								</tr>		
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Secured Advance (Rs)</td>
                                    <td>
										<input type="text" name='txt_sec_adv' id='txt_sec_adv' class="textboxdisplay textright" value="" style="width: 120px;">
									</td>
                                </tr>
 								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_sec_adv"></span>
									&nbsp;
									</td>
								</tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Bill Amount for GST (Rs)</td>
                                    <td>
										<input type="text" name='txt_bill_amt_gst' id='txt_bill_amt_gst' class="textboxdisplay textright" value="" style="width: 120px;">
									</td>
                                </tr>
 								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_bill_amt_gst"></span>
									&nbsp;
									</td>
								</tr>	
								<!--<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Bill Amount for IT (Rs)</td>
                                    <td>
										<input type="text" name='txt_bill_amt_it' id='txt_bill_amt_it' class="textboxdisplay textright" value="" style="width: 120px;">
									</td>
                                </tr>
 								<tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_sec_adv"></span>
									&nbsp;
									</td>
								</tr>-->						
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Security Desposit (Rs.)</td>
                                    <td>
										<input type="text" name='txt_sd_perc' id='txt_sd_perc' class="textboxdisplay textright" value="" style="width: 40px;">
										<label class="label"> % of Net Amount </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_sd' id='txt_sd' class="textboxdisplay textright" value="" style="width: 120px;">
										
										
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_sd_perc"></span>
									<span id="val_sd"></span>
									&nbsp;
									</td>
								</tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">SGST (Rs.)</td>
                                    <td>
										<input type="text" name='txt_sgst_perc' id='txt_sgst_perc' readonly="" class="textboxdisplay textright" value="" style="width: 40px;">
										<label class="label"> % of <span id="sgstLab"></span> Net Amount </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_sgst' id='txt_sgst' readonly="" class="textboxdisplay textright" value="" style="width: 120px;">
										<input type="hidden" name='txt_gst_rate' id='txt_gst_rate' readonly="" class="textboxdisplay textright" value="" style="width: 120px;">
										<input type="hidden" name='txt_gst_amt' id='txt_gst_amt' readonly="" class="textboxdisplay textright" value="" style="width: 120px;">
										<input type="hidden" name='txt_pan_type' id='txt_pan_type' readonly="" class="textboxdisplay textright" value="" style="width: 120px;">
										<input type="hidden" name='txt_is_ldc' id='txt_is_ldc' readonly="" class="textboxdisplay textright" value="" style="width: 120px;">
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_sgst_perc"></span>
									<span id="val_sgst"></span>
									&nbsp;
									</td>
								</tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">CGST (Rs.)</td>
                                    <td>
										<input type="text" name='txt_cgst_perc' id='txt_cgst_perc' readonly="" class="textboxdisplay textright" value="" style="width: 40px;">
										<label class="label"> % of <span id="cgstLab"></span> Net Amount </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_cgst' id='txt_cgst' readonly="" class="textboxdisplay textright" value="" style="width: 120px;">
										
										
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_cgst_perc"></span>
									<span id="val_cgst"></span>
									&nbsp;
									</td>
								</tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">IGST (Rs.)</td>
                                    <td>
										<input type="text" name='txt_igst_perc' id='txt_igst_perc' readonly="" class="textboxdisplay textright" value="" style="width: 40px;">
										<label class="label"> % of <span id="igstLab"></span> Net Amount </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_igst' id='txt_igst' readonly="" class="textboxdisplay textright" value="" style="width: 120px;">
										
										
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_igst_perc"></span>
									<span id="val_igst"></span>
									&nbsp;
									</td>
								</tr>
								<!--<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">WCT (Rs.)</td>
                                    <td>
										<input type="text" name='txt_wct_perc' id='txt_wct_perc' class="textboxdisplay textright" value="" style="width: 40px;">
										<label class="label"> % of Net Amount </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_wct' id='txt_wct' class="textboxdisplay textright" value="" style="width: 120px;">
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_wct_perc"></span>
									<span id="val_wct"></span>
									&nbsp;
									</td>
								</tr>-->
								<!--<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">VAT (Rs.)</td>
                                    <td>
										<input type="text" name='txt_vat_perc' id='txt_vat_perc' class="textboxdisplay textright" value="" style="width: 40px;">
										<label class="label"> % of Net Amount </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_vat' id='txt_vat' class="textboxdisplay textright" value="" style="width: 120px;">
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_vat_perc"></span>
									<span id="val_vat"></span>
									&nbsp;
									</td>
								</tr>-->
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Mobilization Advance (Rs.)</td>
                                    <td>
										<input type="text" name='txt_mob_adv_perc' id='txt_mob_adv_perc' class="textboxdisplay textright" value="" style="width: 40px;">
										<label class="label"> % of Net Amount </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_mob_adv' id='txt_mob_adv' class="textboxdisplay textright" value="" style="width: 120px;">
									</td>
                                </tr>
                                 <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_mob_adv_perc"></span>
									<span id="val_mob_adv"></span>
									&nbsp;
									</td>
								</tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Labour Welfare Cess (Rs.)</td>
                                    <td>
										<input type="text" name='txt_lw_cess_perc' id='txt_lw_cess_perc' class="textboxdisplay textright" value="" style="width: 40px;">
										<label class="label"> % of Net Amount </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_lw_cess' id='txt_lw_cess' class="textboxdisplay textright" value="" style="width: 120px;">
									</td>
                                </tr>
                                 <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_lw_cess_perc"></span>
									<span id="val_lw_cess"></span>
									&nbsp;
									</td>
								</tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Income Tax (Rs.)</td>
                                    <td>
										<input type="text" name='txt_incometax_perc' id='txt_incometax_perc'  readonly="" class="textboxdisplay textright" value="" style="width: 40px;">
										<label class="label"> % of ( Net + SA )  </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_incometax' id='txt_incometax'  readonly="" class="textboxdisplay textright" value="" style="width: 120px;">
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_incometax_perc"></span>
									<span id="val_incometax"></span>
									&nbsp;
									</td>
								</tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">IT Cess (Rs.)</td>
                                    <td>
										<input type="text" name='txt_ITcess_perc' id='txt_ITcess_perc' class="textboxdisplay textright" value="" style="width: 40px;">
										<label class="label"> % of Income Tax </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_ITcess' id='txt_ITcess' class="textboxdisplay textright" value="" style="width: 120px;">
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_ITcess_perc"></span>
									<span id="val_ITcess"></span>
									&nbsp;
									</td>
								</tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">IT Educational Cess (Rs.)</td>
                                    <td>
										<input type="text" name='txt_ITEcess_perc' id='txt_ITEcess_perc' class="textboxdisplay textright" value="" style="width: 40px;">
										<label class="label"> % of Income Tax </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_ITEcess' id='txt_ITEcess' class="textboxdisplay textright" value="" style="width: 120px;">
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td align="center" class="labeldisplay" id="" style="color:red" colspan="">
									<span id="val_ITEcess_perc"></span>
									<span id="val_ITEcess"></span>
									&nbsp;
									</td>
								</tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Rent for Land (Rs.)</td>
                                    <td>
										<input type="text" name='txt_rent_land' id='txt_rent_land' class="textboxdisplay textright" value="" style="width: 120px;">
										&nbsp;&nbsp;&nbsp;&nbsp;
										<!--<input type="text" name='txt_initial' id='txt_initial' class="textboxdisplay" value="" style="width: 40px;">
										<label class="label"> % of Income Tax </label>
										&nbsp;&nbsp;&nbsp;&nbsp;-->
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_rent_land" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Liquidated Damages (Rs.)</td>
                                    <td>
										<input type="text" name='txt_liquid_damage' id='txt_liquid_damage' class="textboxdisplay textright" value="" style="width: 120px;">
										&nbsp;&nbsp;&nbsp;&nbsp;
										<!--<input type="text" name='txt_initial' id='txt_initial' class="textboxdisplay" value="" style="width: 40px;">
										<label class="label"> % of Income Tax </label>
										&nbsp;&nbsp;&nbsp;&nbsp;-->
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_liquid_damage" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Non Deployment of machineries (Rs.)</td>
                                    <td>
										<input type="text" name='txt_nodep_machine' id='txt_nodep_machine' class="textboxdisplay textright" value="" style="width: 120px;">
										&nbsp;&nbsp;&nbsp;&nbsp;
										<!--<input type="text" name='txt_initial' id='txt_initial' class="textboxdisplay" value="" style="width: 40px;">
										<label class="label"> % of Income Tax </label>
										&nbsp;&nbsp;&nbsp;&nbsp;-->
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_nodep_machine" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Non Deployment of Manpower (Rs.)</td>
                                    <td>
										<input type="text" name='txt_nodep_mp' id='txt_nodep_mp' class="textboxdisplay textright" value="" style="width: 120px;">
										&nbsp;&nbsp;&nbsp;&nbsp;
										<!--<input type="text" name='txt_initial' id='txt_initial' class="textboxdisplay" value="" style="width: 40px;">
										<label class="label"> % of Income Tax </label>
										&nbsp;&nbsp;&nbsp;&nbsp;-->
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_nodep_mp" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Non Submission of QA related document (Rs.)</td>
                                    <td>
										<input type="text" name='txt_nonsubmission_qa' id='txt_nonsubmission_qa' class="textboxdisplay textright" value="" style="width: 120px;">
										&nbsp;&nbsp;&nbsp;&nbsp;
										<!--<input type="text" name='txt_initial' id='txt_initial' class="textboxdisplay" value="" style="width: 40px;">
										<label class="label"> % of Income Tax </label>
										&nbsp;&nbsp;&nbsp;&nbsp;-->
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_nonsubmission_qa" style="color:red" colspan="">&nbsp;</td></tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">
									<input type="text" name="txt_other_recovery_1_desc" id="txt_other_recovery_1_desc" value="Other Recoveries 1" class="textboxdisplay label" style="width:80%"/>
									</td>
                                    <td>
										<input type="text" name='txt_other_recovery_1' id='txt_other_recovery_1' class="textboxdisplay textright" value="" style="width: 120px;">
										&nbsp;&nbsp;&nbsp;&nbsp;
										<!--<input type="text" name='txt_initial' id='txt_initial' class="textboxdisplay" value="" style="width: 40px;">
										<label class="label"> % of Income Tax </label>
										&nbsp;&nbsp;&nbsp;&nbsp;-->
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td class="labeldisplay" id="val_other_recovery_1_desc" style="color:red">&nbsp;</td>
									<td align="center" class="labeldisplay" id="val_other_recovery_1" style="color:red" colspan="">&nbsp;</td>
								</tr>
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">
									<input type="text" name="txt_other_recovery_2_desc" id="txt_other_recovery_2_desc" value="Other Recoveries 2" class="textboxdisplay label" style="width:80%"/>
									</td>
                                    <td>
										<input type="text" name='txt_other_recovery_2' id='txt_other_recovery_2' class="textboxdisplay textright" value="" style="width: 120px;">
										&nbsp;&nbsp;&nbsp;&nbsp;
										<!--<input type="text" name='txt_initial' id='txt_initial' class="textboxdisplay" value="" style="width: 40px;">
										<label class="label"> % of Income Tax </label>
										&nbsp;&nbsp;&nbsp;&nbsp;-->
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td class="labeldisplay" id="val_other_recovery_2_desc" style="color:red">&nbsp;</td>
									<td align="center" class="labeldisplay" id="val_other_recovery_2" style="color:red" colspan="">&nbsp;</td>
								</tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">
									<input type="text" name="txt_other_recovery_3_desc" id="txt_other_recovery_3_desc" value="Other Recoveries 3" class="textboxdisplay label" style="width:80%"/>
									</td>
                                    <td>
										<input type="text" name='txt_other_recovery_3' id='txt_other_recovery_3' class="textboxdisplay textright" value="" style="width: 120px;">
										&nbsp;&nbsp;&nbsp;&nbsp;
										<!--<input type="text" name='txt_initial' id='txt_initial' class="textboxdisplay" value="" style="width: 40px;">
										<label class="label"> % of Income Tax </label>
										&nbsp;&nbsp;&nbsp;&nbsp;-->
									</td>
                                </tr>
                                <tr>
									<td>&nbsp;</td>
									<td class="labeldisplay" id="val_other_recovery_3_desc" style="color:red">&nbsp;</td>
									<td align="center" class="labeldisplay" id="val_other_recovery_3" style="color:red" colspan="">&nbsp;</td>
								</tr>
								<!--<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Initial Meter Reading</td>
                                    <td>
										<input type="text" name='txt_initial' id='txt_initial' class="textboxdisplay" value="" style="width: 120px;">
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<label class="label">IMR Date </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_initial_date' id='txt_initial_date' class="textboxdisplay" value="" style="width: 120px;">
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_initial" style="color:red" colspan="">&nbsp;</td></tr>
								
								<tr>
                                    <td>&nbsp;</td>
                                    <td class="label">Final Meter Reading</td>
                                    <td>
										<input type="text" name='txt_final' id='txt_final' class="textboxdisplay" value="" style="width: 120px;">
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<label class="label">FMR Date </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name='txt_final_date' id='txt_final_date' class="textboxdisplay" value="" style="width: 120px;">
									</td>
                                </tr>
                                <tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_final" style="color:red" colspan="">&nbsp;</td></tr>-->
								
								
								<!--<tr>
									<td>&nbsp;</td>
									<td class="label">Rate of Electricity ( Rs.)</td>
									<td>
										<input type="text" name='txt_rate' id='txt_rate' class="textboxdisplay" value="" style="width: 120px;">
										&nbsp;&nbsp;
										<label class="label"> /&nbsp;unit </label>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<label class="label">Date </label>
										&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="text" name="txt_date" id='txt_date' class="textboxdisplay" style="width: 120px;">
									</td>
								</tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_rate" style="color:red" colspan="">&nbsp;</td></tr>
							
								<tr>
									<td>&nbsp;</td>
									<td class="label">Water Charges (Rs.)</td>
									<td>
										<input type="text" name='txt_rate' id='txt_rate' class="textboxdisplay" value="" style="width: 465px;">
									</td>
								</tr>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td align="center" class="labeldisplay" id="val_rate" style="color:red" colspan="">&nbsp;</td></tr>-->
							
							</table>
							</div>	
                            
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
			<script>
				$(function () {
					$("#txt_sd_perc").blur(function(){
						var abst_amt = $("#txt_abstract_amt").val();
						var sd_percent = $("#txt_sd_perc").val();
						if((abst_amt != "") && (sd_percent != ""))
						{
							var sd_amt = Number(abst_amt)*Number(sd_percent)/100;
							$("#txt_sd").val(sd_amt.toFixed(2));
						}
						else
						{
							$("#txt_sd").val();
						}
					});
					
					/*$("#txt_wct_perc").blur(function(){
						var abst_amt = $("#txt_abstract_amt").val();
						var wct_percent = $("#txt_wct_perc").val();
						if((abst_amt != "") && (wct_percent != ""))
						{
							var wct_amt = Number(abst_amt)*Number(wct_percent)/100;
							$("#txt_wct").val(wct_amt.toFixed(2));
						}
						else
						{
							$("#txt_wct").val();
						}
					});*/
					
					/*$("#txt_vat_perc").blur(function(){
						var abst_amt = $("#txt_abstract_amt").val();
						var vat_percent = $("#txt_vat_perc").val();
						if((abst_amt != "") && (vat_percent != ""))
						{
							var vat_amt = Number(abst_amt)*Number(vat_percent)/100;
							$("#txt_vat").val(vat_amt.toFixed(2));
						}
						else
						{
							$("#txt_vat").val();
						}
					});*/
					
					$("#txt_mob_adv_perc").blur(function(){
						var abst_amt = $("#txt_abstract_amt").val();
						var mob_adv_percent = $("#txt_mob_adv_perc").val();
						if((abst_amt != "") && (mob_adv_percent != ""))
						{
							var mob_adv_amt = Number(abst_amt)*Number(mob_adv_percent)/100;
							$("#txt_mob_adv").val(mob_adv_amt.toFixed(2));
						}
						else
						{
							$("#txt_mob_adv").val();
						}
					});
					
					$("#txt_lw_cess_perc").blur(function(){
						var abst_amt = $("#txt_abstract_amt").val();
						var lw_cess_percent = $("#txt_lw_cess_perc").val();
						if((abst_amt != "") && (lw_cess_percent != ""))
						{
							var lw_cess_amt = Number(abst_amt)*Number(lw_cess_percent)/100;
							$("#txt_lw_cess").val(lw_cess_amt.toFixed(2));
						}
						else
						{
							$("#txt_lw_cess").val();
						}
					});
					
					$("#txt_incometax_perc").blur(function(){
						var abst_amt = $("#txt_abstract_amt").val();
						var sec_adv_amt = $("#txt_sec_adv").val();
						var total_amt_for_it = Number(abst_amt)+Number(sec_adv_amt);
						var incometax_percent = $("#txt_incometax_perc").val();
						if((total_amt_for_it != "") && (incometax_percent != ""))
						{
							var it_amt = Number(total_amt_for_it)*Number(incometax_percent)/100;
							$("#txt_incometax").val(it_amt.toFixed(2));
						}
						else
						{
							$("#txt_incometax").val();
						}
					});
					
					$("#txt_ITcess_perc").blur(function(){
						var it_amt = $("#txt_incometax").val();
						var itcess_percent = $("#txt_ITcess_perc").val();
						if((it_amt != "") && (itcess_percent != ""))
						{
							var itcess_amt = Number(it_amt)*Number(itcess_percent)/100;
							$("#txt_ITcess").val(itcess_amt.toFixed(2));
						}
						else
						{
							$("#txt_ITcess").val();
						}
					});
					
					$("#txt_ITEcess_perc").blur(function(){
						var it_amt = $("#txt_incometax").val();
						var itEcess_percent = $("#txt_ITEcess_perc").val();
						if((it_amt != "") && (itEcess_percent != ""))
						{
							var itEcess_amt = Number(it_amt)*Number(itEcess_percent)/100;
							$("#txt_ITEcess").val(itEcess_amt.toFixed(2));
						}
						else
						{
							$("#txt_ITEcess").val();
						}
					});
					
					$("#txt_sec_adv").blur(function(){
						var abst_amt = $("#txt_abstract_amt").val();
						var sec_adv_amt = $("#txt_sec_adv").val();
						var total_amt_for_it = Number(abst_amt)+Number(sec_adv_amt);
						var incometax_percent = $("#txt_incometax_perc").val();
						if((total_amt_for_it != "") && (incometax_percent != ""))
						{
							var it_amt = Number(total_amt_for_it)*Number(incometax_percent)/100;
							$("#txt_incometax").val(it_amt.toFixed(2));
						}
						else
						{
							$("#txt_incometax").val();
						}
					});
				});
			</script>
        </form>
    </body>
</html>
