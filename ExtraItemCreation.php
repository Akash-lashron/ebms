<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
$userid = $_SESSION['userid'];
$msg = '';
$checkbox_count = "";
$checkbox_count = $_POST['txt_checkbox_count'];
if($checkbox_count != "")
{
	//echo "CHECK BOX COUNT = ".$checkbox_count;
	$check_single   = $_POST['check_single'];
	$checkbox_count = count($check_single);
	$prev_mbheaderid = "";
	$prev_mbdetailid = "";
	$MBIDStr = "";
	$MbHead = array();
	for($c1 = 0; $c1<$checkbox_count; $c1++)
	{
		$check_single_value =  $check_single[$c1];
		$exp_check_single_value = explode("*",$check_single_value);
		$mbheaderid = $exp_check_single_value[0];
		$mbdetailid = $exp_check_single_value[1];
		if(($mbheaderid != $prev_mbheaderid) && ($prev_mbheaderid != ""))
		{
			$MbhMbdIDStr .= $prev_mbheaderid."@".rtrim($MBIDStr,"*")."##";
			$MBIDStr = "";
			//array_push($MbHead,$mbheaderid);
		}
		
		/*if(prev_mbheaderid == "")
		{
			$MBIDStr .= $mbheaderid."*";
		}
		if(prev_mbheaderid == $mbheaderid)
		{
			$MBIDStr .= $mbheaderid."*";
		}*/
		/*if(prev_mbheaderid != $mbheaderid)
		{
			$NEW_MBIDStr .= $MBIDStr;
			$MBIDStr = "";
			$MBIDStr .= $mbheaderid."*";
		}*/
		
		$MBIDStr .= $mbdetailid."*";
		$prev_mbheaderid = $mbheaderid;
		$prev_mbdetailid = $mbdetailid;
	}
	$MbhMbdIDStr .= $prev_mbheaderid."@".rtrim($MBIDStr,"*");
	//echo $MbhMbdIDStr;
}




if (isset($_POST["submit"])) {
     $sheet_id 	= trim($_POST['workorderno']);
	 $divid		= trim($_POST['itemno']);
	 $extra_item_no		= trim($_POST['txt_extra_item_no']);
	 $extra_item_qty	= trim($_POST['txt_extra_item_qty']);
	 $extra_item_rate	= trim($_POST['txt_extra_item_rate']);
	 $extra_item_unit	= trim($_POST['txt_extra_item_unit']);
	 $extra_item_desc	= trim($_POST['txt_extra_item_desc']);
	 $extra_item_total_amt = $extra_item_qty*$extra_item_rate;
	 if((trim($_POST['subsubsubitemno']) != 0) && (trim($_POST['subsubsubitemno']) != ""))
     {
         $subdiv_id = trim($_POST['subsubsubitemno']);
     }
	 else if((trim($_POST['subsubitemno']) != 0) && (trim($_POST['subsubitemno']) != ""))
     {
         $subdiv_id = trim($_POST['subsubitemno']);
     }
     else if((trim($_POST['subitemno']) != 0) && (trim($_POST['subitemno']) != ""))
     {
         $subdiv_id = trim($_POST['subitemno']);
     }
     else
     {
         $sql_selectsubid = "select subdiv_id from subdivision where div_id='$divid'";
         $res_subid = mysql_query($sql_selectsubid);
         $subdiv_id = @mysql_result($res_subid,0,'subdiv_id');
     }
	 $extra_item_divid 			= $divid;
	 $insert_extra_item_query 	= "insert into subdivision set subdiv_name = '$extra_item_no', sheet_id = '$sheet_id', div_id = '$extra_item_divid', active = 1";
	 $insert_extra_item_sql 	= mysql_query($insert_extra_item_query);
	 $extra_item_subdivid 		= mysql_insert_id();
	 
	 $insert_schdule_query 	= "insert into schdule set 
	 							sheet_id = '$sheet_id', 
								sno = '$extra_item_no',
								description = '$extra_item_desc',
								total_quantity = '$extra_item_qty',
								rate = '$extra_item_rate',
								per = '$extra_item_unit',
								decimal_placed = '3',
								total_amt = '$extra_item_total_amt',
								active = 1,
								subdiv_id = '$extra_item_subdivid', 
								create_dt = NOW(),
								user_id = '$userid',
								item_flag = 'DI',
								measure_type = ''";
	 $insert_schdule_sql 	= mysql_query($insert_schdule_query);
	 if($insert_schdule_sql == true)
	 {
	 	$msg = "Extra Item Created Sucessfully";
		$success = 1;
	 }
	 else
	 {
	 	$msg - "Error";
	 }
	 $str 	= $_POST['txt_mbheader_id_str'];
	 //echo $idstr;
	 $exp_str = explode("##",$str);
	 //echo $idstr."<br/>";
	 for($i=0; $i<count($exp_str); $i++)
	 {
	 	$id_str 		= $exp_str[$i];
	 	$exp_idstr 		= explode("@",$id_str);
	 	$mbheaderid 	= $exp_idstr[0];
		$mbid_str 		= $exp_idstr[1];
		$exp_mbid_str 	= explode("*",$mbid_str);
		$mbdetailid_count = count($exp_mbid_str);
		$mbheader_count_query 	= "select count(*) as num from mbookdetail_temp where mbheaderid = '$mbheaderid'";
		$mbheader_count_sql 	= mysql_query($mbheader_count_query);
		if($mbheader_count_sql == true)
		{
			$CountList = mysql_fetch_object($mbheader_count_sql);
			$count = $CountList->num;
		}
		if($mbdetailid_count < $count)
		{
			$select_mbheader_query 	= "select * from mbookheader_temp where mbheaderid = '$mbheaderid'";
			$select_mbheader_sql 	= mysql_query($select_mbheader_query);
			if($select_mbheader_sql == true)
			{
				$MBHeadList = mysql_fetch_object($select_mbheader_sql);
				$mbhead_date 			= $MBHeadList->date;
				$mbhead_sheetid 		= $MBHeadList->sheetid;
				$mbhead_measure_type 	= $MBHeadList->measure_type;
				$mbhead_zone_id 		= $MBHeadList->zone_id;
				$mbhead_active 			= $MBHeadList->active;
				$mbhead_staffid 		= $MBHeadList->staffid;
				$mbhead_userid 			= $MBHeadList->userid;
				$mbhead_mbheader_flag 	= $MBHeadList->mbheader_flag;
				$mbhead_divid 			= $extra_item_divid;
				$mbhead_subdivid 		= $extra_item_subdivid;
				$mbhead_subdiv_name		= $extra_item_no;
				
				$insert_mbheader_query = "insert into mbookheader_temp set 
										  date = '$mbhead_date', 
										  sheetid = '$mbhead_sheetid',
										  divid = '$mbhead_divid',
										  subdivid = '$mbhead_subdivid',
										  subdiv_name = '$mbhead_subdiv_name',
										  measure_type = '$mbhead_measure_type',
										  zone_id = '$mbhead_zone_id',
										  active = '$mbhead_active',
										  staffid = '$mbhead_staffid',
										  userid = '$mbhead_userid',
										  mbheader_flag  = '$mbhead_mbheader_flag'";
				$insert_mbheader_sql = mysql_query($insert_mbheader_query);
				$new_mbheader_id = mysql_insert_id();
				//echo $mbid_str."<br/>";
				for($j=0; $j<count($exp_mbid_str); $j++)
				{
					$Error_new = ""; $QtyError= ""; $ItemError = ""; $DateError = "";
					$select_mbdetail_error_query = "select mbdetail_flag from mbookdetail_temp where mbdetail_id = '$mbdetail_id'";
					$select_mbdetail_error_sql = mysql_query($select_mbdetail_error_query);
					if($select_mbdetail_error_sql == true)
					{
						$ErrorList = mysql_fetch_object($select_mbdetail_error_sql);
						$Error = $ErrorList->mbdetail_flag;
						$experror		= explode("@@",$Error);
						$ErrorItemNo	= $experror[0];
						$DateError		= $experror[1];
						$ItemError		= $experror[2];
						$QtyError		= $experror[3];
					}
					if($DateError == "")
					{
						$Error_new = "";
					}
					else
					{
						$ItemError_new = "";
						$Error_new = $mbhead_subdiv_name."@@".$DateError."@@".$ItemError_new."@@".$QtyError;
					}
					$mbdetail_id = $exp_mbid_str[$j];
					$update_mbookdetail_query = "update mbookdetail_temp set 
												 mbheaderid = '$new_mbheader_id', 
												 subdivid =  '$mbhead_subdivid', 
												 subdiv_name =  '$mbhead_subdiv_name',
												 mbdetail_flag =  '$Error_new',
												 entry_date  =  NOW() where mbdetail_id = '$mbdetail_id'";
					$update_mbookdetail_sql = mysql_query($update_mbookdetail_query);
					//echo $update_mbookdetail_query;
				}
			}
		}
		
	 }
}
?>
<?php require_once "Header.html"; ?>

    <script type="text/javascript"  language="JavaScript">
		$(function() {
  			$('#extra_item_div').on('keydown', '#txt_extra_item_qty', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
  			$('#extra_item_div').on('keydown', '#txt_extra_item_rate', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});
		});
		function goBack()
	   	{
	   		url = "dashboard.php";
			window.location.replace(url);
	   	}
        function ExtraItemNo_Validation()
        {
			var extra_item_no	= document.form.txt_extra_item_no.value;
			
			var item_level_1	= document.form.itemno.value;
			var item_level_2	= document.form.subitemno.value;
			var item_level_3	= document.form.subsubitemno.value;
			var item_level_4	= document.form.subsubsubitemno.value;
			
			var item_level_1_text 	= document.getElementById('itemno').options[document.getElementById('itemno').selectedIndex].text;
			var item_level_2_text 	= document.getElementById('subitemno').options[document.getElementById('subitemno').selectedIndex].text;
			var item_level_3_text 	= document.getElementById('subsubitemno').options[document.getElementById('subsubitemno').selectedIndex].text;
			var item_level_4_text 	= document.getElementById('subsubsubitemno').options[document.getElementById('subsubsubitemno').selectedIndex].text;
			
			var length1 = document.form.itemno.length;
			var length2 = document.form.subitemno.length;
			var length3 = document.form.subsubitemno.length;
			var length4 = document.form.subsubsubitemno.length;
			
			if((item_level_4 != 0) && (item_level_4 != ""))
			{
				var item_id 	= item_level_4;
				var item_no 	= item_level_4_text;
			}
			else if((item_level_3 != 0) && (item_level_3 != ""))
			{
				if(length4 == 1)
				{
					var item_id 	= item_level_3;
					var item_no 	= item_level_3_text;
				}
				else
				{
					var item_id 	= "";
					var item_no 	= "";
				}
			}
			else if((item_level_2 != 0) && (item_level_2 != ""))
			{
				if(length3 == 1)
				{
					var item_id 	= item_level_2;
					var item_not 	= item_level_2_text;
				}
				else
				{
					var item_id 	= "";
					var item_no 	= "";
				}
			}
			else if((item_level_1 != 0) && (item_level_1 != ""))
			{
				if(length2 == 1)
				{
					var item_id 	= item_level_1;
					var item_no 	= item_level_1_text;
				}
				else
				{
					var item_id 	= "";
					var item_no 	= "";
				}
			}
			else
			{
				var item_id = "";
				var item_no = "";
			}
			if(item_id == "") 
			{
				//alert("select item no");
				swal("Please Select an Item No.", "", "");
				event.preventDefault();
				event.returnValue = false;
				//return false;
			}
			else
			{
				var split_item_no 		= item_no.split(".");
				var split_extra_item_no = extra_item_no.split(".");
				var count=0, i;
				for(i=0; i<split_item_no.length; i++)
				{
					var item_no_char 		= split_item_no[i];
					var extra_item_no_char 	= split_extra_item_no[i];
					if(item_no_char != extra_item_no_char)
					{
						count++;
					}
				}
			}
			if(count>0)
			{
				//alert("invalid item no");
				swal("Entered Item No. is Invalid", "", "");
				event.preventDefault();
				event.returnValue = false;
				//return false;
			}
			else
			{
				//return true;
				event.preventDefault();
				event.returnValue = false;
			}
			
        }
        
        function func_items()
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
            //alert(document.form.workorderno.value);
            strURL = "find_items.php?item_no=" + document.form.workorderno.value;
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
                        document.form.itemno.value = 'Select';
                    }
                    else
                    {
                        
                        var name = data.split("*");
                        document.form.itemno.length = 0
                        var optn = document.createElement("option")
                        optn.value = 0;
                        optn.text = "Item No";
                        document.form.itemno.options.add(optn)

                        var c = name.length
                        var a = c / 2;
                        var b = a + 1;
                        for (i = 1, j = b; i < a, j < c; i++, j++)
                        {
                            var optn = document.createElement("option")
                            optn.value = name[i];
                            optn.text = name[j];
                            document.form.itemno.options.add(optn)
                        }

                    }
                }
            }
            xmlHttp.send(strURL);
        }
        function func_item_no()
        {
            func_items()

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
            strURL = "find_item_no.php?item_no=" + document.form.workorderno.value;
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
                        var wrkname = data.split("##");
						document.form.txt_workorder_no.value 	= wrkname[6];
                        document.form.txt_wk_name.value 		= wrkname[0];
                        document.form.txt_cont_name.value 		= wrkname[2];
                        document.form.txt_tech_san.value		= wrkname[1];
                        document.form.txt_agmt_no.value 		= wrkname[3];
                        document.form.txt_runn_bill_no.value 	= wrkname[4];
                    }
                }
            }
            xmlHttp.send(strURL);

            var e = document.getElementById("workorderno");
            var strUser = e.options[e.selectedIndex].text;
            document.form.txt_work_no.value = strUser;
        }
        function func_subitem_no()
        { 
            //var item = selitem.options[selitem.selectedIndex].text;
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
            strURL = "find_subitem_no.php?work_no=" + document.form.workorderno.value + "&div_id=" + document.form.itemno.value;
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
                        document.form.subitemno.value = '';
                        document.form.subsubitemno.value = '';
                    }
                    else
                    {
                        var name = data.split("*");
                        document.form.subitemno.length = 0;
                        var optn = document.createElement("option");
                        optn.value = 0;
                        optn.text = "Sub Item 1";
                        document.form.subitemno.options.add(optn);
                        
                        document.form.subsubitemno.length = 0;
                        var optn1 = document.createElement("option");
                        optn1.value = 0;
                        optn1.text = "Sub Item 2";
                        document.form.subsubitemno.options.add(optn1);
						
						document.form.subsubsubitemno.length = 0;
                        var optn1 = document.createElement("option");
                        optn1.value = 0;
                        optn1.text = "Sub Item 3";
                        document.form.subsubsubitemno.options.add(optn1);
                        
                        
                        var cont = 1;
                        var c = name.length;
                        var a = c / 2;
                        var b = a + 1;
                        var pre_opt_text = "";
                        for (i = 1, j = b; i < a, j < c; i++, j++)
                        {
                            var spl_sitem 	= name[j].split(".");
                                if(spl_sitem.length == 2)
                                {
                                    var optn 	= 	document.createElement("option")
                                    optn.value 	= 	name[i];
                                    optn.text 	= 	name[j];
                                    document.form.subitemno.options.add(optn)
									pre_opt_text = 	optn.text;
                                    cont++;
                                }
                                if(spl_sitem.length > 2)
                                {
                                    var optn 	= 	document.createElement("option")
                                    //optn.value = name[i];
                                    optn.value 	= 	"";
                                    optn.text 	=  	spl_sitem[0] +"."+ spl_sitem [1];
                                    if(optn.text != pre_opt_text)
                                    {
                                        document.form.subitemno.options.add(optn)
                                    }
                                    pre_opt_text = 	optn.text;
                                    cont++;
                                }
                                
                            
                        } 
                        if(cont <= 1)
                        {
                           // alert("count="+cont);
                            document.getElementById("subitemno").disabled = true;
                            document.getElementById("subsubitemno").disabled = true;
							document.getElementById("subsubsubitemno").disabled = true;
                        }
                        else
                        {
                            document.getElementById("subitemno").disabled = false;
                            document.getElementById("subsubitemno").disabled = false;
							document.getElementById("subsubsubitemno").disabled = false;
                        }
                    }
                }
            }
            xmlHttp.send(strURL);

            var e = document.getElementById("itemno");
            var strUser = e.options[e.selectedIndex].text;
            document.form.txt_item_no.value = strUser;
        }
        function find_subsubitem(selsubitem)
        {
            var ssitem = selsubitem.options[selsubitem.selectedIndex].text;
			var ssitemvalue = selsubitem.options[selsubitem.selectedIndex].value;
			//alert(ssitem)
			//alert(ssitemvalue)
			if((ssitemvalue == 0) && (ssitemvalue != ""))
			{
				return false;
				exit();
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
            strURL = "find_subitem_no.php?work_no=" + document.form.workorderno.value + "&div_id=" + document.form.itemno.value;
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
                        document.form.subsubitemno.value = '';
                    }
                    else
                    {   
                        var name = data.split("*");
						
                        document.form.subsubitemno.length = 0
                        var optn = document.createElement("option")
                        optn.value = 0;
                        optn.text = "Sub Item 2";
                        document.form.subsubitemno.options.add(optn)
						
						document.form.subsubsubitemno.length = 0;
                        var optn1 = document.createElement("option");
                        optn1.value = 0;
                        optn1.text = "Sub Item 3";
                        document.form.subsubsubitemno.options.add(optn1);
						
                        var cont = 1;
                        var c = name.length
                        var a = c / 2;
                        var b = a + 1;
						var pre_opt_text = "";
                        for (i = 1, j = b; i < a, j < c; i++, j++)
                        {
							//alert(name[j]);
                           // if(name[j].length > 2)
                            //{
                                var sitem 		= 	name[j].split(".");
                              //  var sub = sitem[0] +"."+ sitem[1];
								var subsubitem 	= 	sitem[0] +"."+ sitem[1] +"."+ sitem[2];
								var ssitemtemp 	= 	sitem[0] +"."+ sitem[1];
								if(ssitem == ssitemtemp)
								{
									if(sitem.length == 3)
									{
										var optn 	= 	document.createElement("option")
										optn.value 	= 	name[i];
										optn.text 	= 	subsubitem;
										document.form.subsubitemno.options.add(optn)
										pre_opt_text = 	optn.text;
										cont++;
									}
									if(sitem.length > 3)
									{
									   // if(ssitem == sub)
									   // {
											
											var optn 	= 	document.createElement("option")
											optn.value 	= 	"";
											optn.text 	= 	subsubitem;
											if(optn.text != pre_opt_text)
											{
												document.form.subsubitemno.options.add(optn);
											}
											pre_opt_text = 	optn.text;
											cont ++;
											//pre_opt_text = optn.text;
									   // }
									}
								}
                            //}
                        }
                     if(cont <= 1)
                        {
                            document.getElementById("subsubitemno").disabled = true;
							document.getElementById("subsubsubitemno").disabled = true;
                        }
                        else
                        {
                            document.getElementById("subsubitemno").disabled = false;
							document.getElementById("subsubsubitemno").disabled = false;
                        }   
                    }
                }
            }
            xmlHttp.send(strURL);

            var e = document.getElementById("itemno");
            var strUser = e.options[e.selectedIndex].text;
            document.form.txt_item_no.value = strUser;
        }
		
		function find_subsubsubitem(selsubsubitem)
        {
            var sssitem = selsubsubitem.options[selsubsubitem.selectedIndex].text;
			var sssitemvalue = selsubsubitem.options[selsubsubitem.selectedIndex].value;
			//alert(sssitem)
			//alert(sssitemvalue)
			if((sssitemvalue == 0) && (sssitemvalue != ""))
			{
				return false;
				exit();
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
            strURL = "find_subitem_no.php?work_no=" + document.form.workorderno.value + "&div_id=" + document.form.itemno.value;
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
                        document.form.subsubsubitemno.value = '';
                    }
                    else
                    {   
                        var name 	= 	data.split("*");
                        document.form.subsubsubitemno.length = 0
                        var optn 	= 	document.createElement("option")
                        optn.value 	= 	0;
                        optn.text 	= 	"Sub Item 3";
                        document.form.subsubsubitemno.options.add(optn)
                        var cont 	= 	1;
                        var c 		= 	name.length
                        var a 		= 	c / 2;
                        var b 		= 	a + 1;
						var pre_opt_text = "";
                        for (i = 1, j = b; i < a, j < c; i++, j++)
                        {
							//alert(name[j]);
                           // if(name[j].length > 2)
                            //{
                                var sitem 		= 	name[j].split(".");
                                var sssitemtemp = 	sitem[0] +"."+ sitem[1] +"."+ sitem[2];
                                if(sitem.length > 3)
                                {
                                    if(sssitem == sssitemtemp)
                                    {
                                        var subsubsubitem 	= 	sitem[0] +"."+ sitem[1] +"."+ sitem[2] +"."+ sitem[3];
                                        var optn 			= 	document.createElement("option")
                                        optn.value 			= 	name[i];
                                        optn.text 			= 	subsubsubitem;
										if(optn.text != pre_opt_text)
                                   		{
                                        document.form.subsubsubitemno.options.add(optn);
										}
										pre_opt_text 		= 	optn.text;
                                        cont ++;
										//pre_opt_text = optn.text;
                                    }
                                }
                            //}
                        }
                     if(cont <= 1)
                        {
                            document.getElementById("subsubsubitemno").disabled = true;
                        }
                        else
                        {
                            document.getElementById("subsubsubitemno").disabled = false;
                        }   
                    }
                }
            }
            xmlHttp.send(strURL);

            var e = document.getElementById("itemno");
            var strUser = e.options[e.selectedIndex].text;
            document.form.txt_item_no.value = strUser;
        }
		
        /*function getitem_desc(itemval)
        { 
            var item_name = itemval.options[itemval.selectedIndex].text;
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
            strURL = "find_item_descrip.php?div_name=" + item_name + "&div_id=" + document.form.itemno.value;
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
                        document.form.txt_desc.value = '';
                    }
                    else
                    {
                        var name = data.split("*");

//			if(name[2]=="")
//                            {
//                                var j = name[1];
//				document.form.descriptionnotes.value=name[0];
//                            }
//			else
//                            {
//				document.form.descriptionnotes.value=name[2];
//                            }
                        document.form.descriptionnotes.value=name[0];
                        document.form.shortnotes.value=name[2];

                    }
                }
            }   
            xmlHttp.send(strURL);
        }*/
        /*function find_desc(subitemid)
        {
            var sub_item_id = subitemid.options[subitemid.selectedIndex].value;
            if(sub_item_id != "empty")
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
               // strURL = "find_desc.php?subitem_no=" + document.form.subitemno.value;
                strURL = "find_desc.php?subitem_no=" + sub_item_id;
                xmlHttp.open('POST', strURL, true);
                xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xmlHttp.onreadystatechange = function ()
                {
                    if (xmlHttp.readyState == 4)
                    {
                        data = xmlHttp.responseText

                        var name = data.split("*");
                       if (data == "")
                        {
                            alert("No Records Found");
                            document.form.txt_desc.value = '';
                        }
                        else
                        {	//alert(name);

//                                                    if(name[2]=="")
//                                                    {var j = name[1];
//
//                                                    document.form.descriptionnotes.value=name[0];
//                                                    }
//                                                    else
//                                                    {
//                                                    document.form.descriptionnotes.value=name[2];}
                        document.form.descriptionnotes.value=name[0];
                        document.form.shortnotes.value=name[2];
                      
                        }


                    }
                }
                xmlHttp.send(strURL);

             }
        }*/
  	function item_description(obj)
	{
		var cmb_id = obj.id;
		//var item_name = cmb_id.options[cmb_id.selectedIndex].value;
		//if(cmb_id = "itemno") 			{ var row_1_class =  "subitem_1_desc"; var row_2_class =  "subitem_1"; }
		//if(cmb_id == "subitemno") 		{ var row_1_class =  "subitem_1_desc"; var row_2_class =  "subitem_1"; }
		//if(cmb_id == "subsubitemno") 	{ var row_1_class =  "subitem_2_desc"; var row_2_class =  "subitem_2"; }
		//if(cmb_id == "subsubsubitemno") 	{ var row_1_class =  "subitem_3_desc"; var row_2_class =  "subitem_3"; }
		var item_name = obj.options[obj.selectedIndex].text;
		var workorderno = document.form.workorderno.value;
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
        strURL = "find_description.php?item_name=" + item_name + "&workorderno=" + workorderno;
        xmlHttp.open('POST', strURL, true);
        xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlHttp.onreadystatechange = function ()
       	{
           if (xmlHttp.readyState == 4)
           {
               data = xmlHttp.responseText
               if (data != "")
               {
               		var name = data.split("*");
					if(cmb_id == "itemno")
					{
						if(name[0] != "")
						{
							document.form.descriptionnotes.value = name[0];
						}
						document.form.subitem_1_desc.value = "";
						document.form.subitem_2_desc.value = "";
						document.form.subitem_3_desc.value = "";
						document.getElementById("subitem_1_desc").className = "hide";
						document.getElementById("subitem_1").className = "hide";
						document.getElementById("subitem_2_desc").className = "hide";
						document.getElementById("subitem_2").className = "hide";
						document.getElementById("subitem_3_desc").className = "hide";
						document.getElementById("subitem_3").className = "hide";
					}
					if(cmb_id == "subitemno")
					{
						if(name[0] != "")
						{
							document.form.subitem_1_desc.value = name[0];
						}
						//document.form.subitemno.value = "";
						document.form.subitem_2_desc.value = "";
						document.form.subitem_3_desc.value = "";
						document.getElementById("subitem_1_desc").className = "";
						document.getElementById("subitem_1").className = "";
						document.getElementById("subitem_2_desc").className = "hide";
						document.getElementById("subitem_2").className = "hide";
						document.getElementById("subitem_3_desc").className = "hide";
						document.getElementById("subitem_3").className = "hide";

					}
					if(cmb_id == "subsubitemno")
					{
						if(name[0] != "")
						{
							document.form.subitem_2_desc.value = name[0];
						}
						//document.form.subitemno.value = "";
						//document.form.subsubitemno.value = "";
						document.form.subitem_3_desc.value = "";
						document.getElementById("subitem_1_desc").className = "";
						document.getElementById("subitem_1").className = "";
						document.getElementById("subitem_2_desc").className = "";
						document.getElementById("subitem_2").className = "";
						document.getElementById("subitem_3_desc").className = "hide";
						document.getElementById("subitem_3").className = "hide";
					}
					if(cmb_id == "subsubsubitemno")
					{
						document.form.subitem_3_desc.value = name[0];
						//document.form.subitemno.value = "";
						//document.form.subsubitemno.value = "";
						//document.form.subsubsubitemno.value = "";
						document.getElementById("subitem_1_desc").className = "";
						document.getElementById("subitem_1").className = "";
						document.getElementById("subitem_2_desc").className = "";
						document.getElementById("subitem_2").className = "";
						document.getElementById("subitem_3_desc").className = "";
						document.getElementById("subitem_3").className = "";
					}
					/*else
					{
						if(name[0] != "")
						{
							document.getElementById(row_1_class).className = "";
							document.getElementById(row_2_class).className = "";
							document.form.cmb_id.value = name[0];
						}
						else
						{
							document.getElementById(row_1_class).className = "hide";
							document.getElementById(row_2_class).className = "hide";
							document.form.cmb_id.value = "";
						}
					}*/
               }
          }
        }
      	xmlHttp.send(strURL);
	}	
	function cls(obj)
	{
		var cmb_id = obj.id;
		var x = "dummy variable";
		if(cmb_id == "workorderno")
		{
			document.form.descriptionnotes.value="";
			document.form.subitem_1_desc.value="";
			document.form.subitem_2_desc.value="";
			document.form.subitem_3_desc.value="";
			document.form.itemno.length = 1;
			document.form.subitemno.length = 1;
			document.form.subsubitemno.length = 1;
			document.form.subsubsubitemno.length = 1;
			document.getElementById("subitem_1_desc").className = "hide";
			document.getElementById("subitem_1").className = "hide";
			document.getElementById("subitem_2_desc").className = "hide";
			document.getElementById("subitem_2").className = "hide";
			document.getElementById("subitem_3_desc").className = "hide";
			document.getElementById("subitem_3").className = "hide";
			
		}
		else if (cmb_id == "itemno")
		{
			document.form.subitem_1_desc.value="";
			document.form.subitem_2_desc.value="";
			document.form.subitem_3_desc.value="";
			document.form.subitemno.length = 1;
			document.form.subsubitemno.length = 1;
			document.form.subsubsubitemno.length = 1;
			document.getElementById("subitem_1_desc").className = "hide";
			document.getElementById("subitem_1").className = "hide";
			document.getElementById("subitem_2_desc").className = "hide";
			document.getElementById("subitem_2").className = "hide";
			document.getElementById("subitem_3_desc").className = "hide";
			document.getElementById("subitem_3").className = "hide";			
		}
		else if (cmb_id == "subitemno")
		{
			document.form.subitem_2_desc.value="";
			document.form.subitem_3_desc.value="";
			document.form.subsubitemno.length = 1;
			document.form.subsubsubitemno.length = 1;
			document.getElementById("subitem_2_desc").className = "hide";
			document.getElementById("subitem_2").className = "hide";
			document.getElementById("subitem_3_desc").className = "hide";
			document.getElementById("subitem_3").className = "hide";			
		}
		else if (cmb_id == "subsubitemno")
		{
			document.form.subitem_3_desc.value="";
			document.form.subsubsubitemno.length = 1;
			document.getElementById("subitem_3_desc").className = "hide";
			document.getElementById("subitem_3").className = "hide";
		}
		else
		{
			x = "";
		}
	}

    </script>
	<style>
	.hide
	{
		display:none;
	}
	</style>
	<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</SCRIPT>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="top">
<?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1" style="overflow-y:scroll;">
                            <div class="title">Additional Qty Beyond the Deviation Limit <?php //print_r($MbHead); ?> </div>		
                          	
                                <input type="hidden" name="txt_item_no" id="txt_item_no" value="">
                                <input type="hidden" name="txt_work_no" id="txt_work_no" value="">

                                <div class="container">
                                    <table width="1060" border="1" cellpadding="0" cellspacing="0" align="center">
                                        <tr><td width="17%">&nbsp;</td>
                                        </tr>	
                                        <tr><td colspan="5">&nbsp;&nbsp;</td></tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td  class="label" width="14%" nowrap="nowrap">Work Short Name</td>
                                            <td class="label">
                                                <select id="workorderno" name="workorderno" onChange="func_item_no();cls(this)" class="textboxdisplay" style="width:505px;height:22px;" tabindex="7">
                                                        <option value=""> ------------------------------------ Select Work Name ----------------------------------- </option>
                                                        <?php echo $objBind->BindWorkOrderNo(0); ?>
                                              </select>     
                                            </td>
                                            <td class="label">&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr><td>&nbsp;</td><td></td><td colspan="3" id="val_work" style="color:red"></tr>
										<tr>
                                            <td>&nbsp;</td>
                                            <td class="label">Work Order No</td>
                                            <td class="labeldisplay">
                                                <input type="text" name='txt_workorder_no' id='txt_workorder_no' class="textboxdisplay" value="" style="width:500px;"/>
                                            </td>
                                            <td class="label">&nbsp;</td>
                                            <td class="label">&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td>&nbsp;</td>
                                            <td></td>
                                            <td id="val_workorderno" colspan="2" style="color:red"></td>
                                            <td></td>
                                            
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td  class="label" nowrap="nowrap">Item No.</td>
                                            <td class="label"  colspan="3">
                                                <select onBlur="display();" name="itemno" id="itemno" class="textboxdisplay" onChange="cls(this);func_subitem_no();item_description(this);" style="width:100px;height:22px;" tabindex="7">
                                                    <option value="0">Item No</option>
                                                </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	

                                           <!-- &nbsp;&nbsp;&nbsp;&nbsp;Sub Item No.-->
                                            
                                                <select onBlur="display();" name="subitemno" id="subitemno" class="textboxdisplay" style="width:100px;height:22px;" onChange="cls(this);find_subsubitem(this); item_description(this);">
                                                    <option value="0">Sub Item 1</option>
                                                </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                           
                                                <select onBlur="display();" name="subsubitemno" id="subsubitemno" class="textboxdisplay" style="width:100px;height:22px;"  onChange="cls(this);find_subsubsubitem(this); item_description(this);">
                                                    <option value="0">Sub Item 2</option>
                                                </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<select onBlur="display();" name="subsubsubitemno" id="subsubsubitemno" class="textboxdisplay" style="width:100px;height:22px;"  onChange="item_description(this);">
                                                    <option value="0">Sub Item 3</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td style="color:red">
											<span id="val_item"></span>
											<span id="val_sub"></span>
											<span id="val_subsub"></span>
											<span id="val_subsubsub"></span>
											</td>
                                        </tr>

                    
										<tr>
                                            <td>&nbsp;</td>
                                            <td  class="label">Item Level Desc.</td>
                                            <td  class="labeldisplay">
                                               <!-- <input type="text" name='descriptionnotes' id='descriptionnotes' class="textboxdisplay" value="" size="55"/> -->
                                                <textarea name="descriptionnotes" id="descriptionnotes" class="textboxdisplay txtarea_style" style="width: 505px;" rows="8"></textarea>
                                            </td>
                                            <td class="label">&nbsp;</td> 
                                            <td width="20%">
<!--                                                <input type="text" name="remarks" id="remarks" class="textboxdisplay" size="10" readonly="" />-->
                                                
                                            </td>
											
										</tr>
                                        <tr><td colspan="5">&nbsp;&nbsp;</td></tr>
										<tr id="subitem_1_desc" class="hide">
                                            <td>&nbsp;</td>
                                            <td  class="label">Sub Level 1 Desc.</td>
                                            <td  class="labeldisplay">
                                               <!-- <input type="text" name='descriptionnotes' id='descriptionnotes' class="textboxdisplay" value="" size="55"/> -->
                                                <textarea name="subitem_1_desc" id="subitem_1_desc" class="textboxdisplay txtarea_style" style="width: 505px;" rows="3"></textarea>
                                            </td>
                                            <td class="label">&nbsp;</td> 
                                            <td width="20%">
<!--                                                <input type="text" name="remarks" id="remarks" class="textboxdisplay" size="10" readonly="" />-->
                                                
                                            </td>
											
										</tr>
                                        <tr id="subitem_1" class="hide"><td colspan="5">&nbsp;&nbsp;</td></tr>
										<tr id="subitem_2_desc" class="hide">
                                            <td>&nbsp;</td>
                                            <td  class="label">Sub Level 2 Desc.</td>
                                            <td  class="labeldisplay">
                                               <!-- <input type="text" name='descriptionnotes' id='descriptionnotes' class="textboxdisplay" value="" size="55"/> -->
                                                <textarea name="subitem_2_desc" id="subitem_2_desc" class="textboxdisplay txtarea_style" style="width: 505px;" rows="3"></textarea>
                                            </td>
                                            <td class="label">&nbsp;</td> 
                                            <td width="20%">
<!--                                                <input type="text" name="remarks" id="remarks" class="textboxdisplay" size="10" readonly="" />-->
                                                
                                            </td>
											
										</tr>
                                        <tr id="subitem_2" class="hide"><td colspan="5">&nbsp;&nbsp;</td></tr>
										<tr id='subitem_3_desc' class="hide">
                                            <td>&nbsp;</td>
                                            <td  class="label">Sub Level 3 Desc.</td>
                                            <td  class="labeldisplay">
                                               <!-- <input type="text" name='descriptionnotes' id='descriptionnotes' class="textboxdisplay" value="" size="55"/> -->
                                                <textarea name="subitem_3_desc" id="subitem_3_desc" class="textboxdisplay txtarea_style" style="width: 505px;" rows="3"></textarea>
                                            </td>
                                            <td class="label">&nbsp;</td> 
                                            <td width="20%">
<!--                                                <input type="text" name="remarks" id="remarks" class="textboxdisplay" size="10" readonly="" />-->
                                                
                                            </td>
											
										</tr>
                                        <tr id="subitem_3" class="hide"><td colspan="5">&nbsp;&nbsp;</td></tr>
                                        <!--<tr>
                                            <td>&nbsp;</td>
                                            <td class="label">Short Notes</td>
                                            <td class="labeldisplay">
												<textarea name="shortnotes" id="shortnotes" class="textboxdisplay" style="width:500px;" rows="4"></textarea>
                                            </td>
                                            <td class="label">&nbsp;</td>
                                            <td class="label">&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td>&nbsp;</td>
                                            <td></td>
                                            <td id="val_shortnotes" colspan="2" style="color:red"></td>
                                            <td></td>
                                            
                                        </tr>-->
										<tr>
											<td>&nbsp;</td>
											<td colspan="2" align="center">
												 <div style=" background-color:#EEEEEE; border:1px solid #D4D4D4" id="extra_item_div">
								  					<table width="100%" class="label">
														<tr style="background-color:#F0F0F0; height:25px; color:#FFFFFF; vertical-align:middle"><td align="center" colspan="4" class="gradientbg">Additional Qty Beyond the Deviation Limit </td></tr>
														<tr>
															<td align="center">Item No</td>
															<td align="center">Qty</td>
															<td align="center">Rate</td>
															<td align="center">Unit</td>
														</tr>
														<tr>
															<td align="center"><input type="text" class="extraItemTextbox" name="txt_extra_item_no" id="txt_extra_item_no" onBlur="ExtraItemNo_Validation();"></td>
															<td align="center"><input type="text" class="extraItemTextbox" name="txt_extra_item_qty" id="txt_extra_item_qty"></td>
															<td align="center"><input type="text" class="extraItemTextbox" name="txt_extra_item_rate" id="txt_extra_item_rate"></td>
															<td align="center"><input type="text" class="extraItemTextbox" name="txt_extra_item_unit" id="txt_extra_item_unit"></td>
														</tr>
														<tr>
															<td align="left" colspan="4" id="val_extra_item" style="color:red"></td>
														</tr>
														<tr>
															<td align="center" colspan="4">Item Description</td>
														</tr>
														<tr>
															<td align="center" colspan="4">
																<textarea class="extraItemTextArea" name="txt_extra_item_desc" id="txt_extra_item_desc" rows="3" cols="100"></textarea>
															</td>
														</tr>
														<tr id="extra_item_desc">
															<td align="left" colspan="4" id="val_extra_item_desc" style="color:red;"></td>
														</tr>
													</table>
								  				</div>
											</td>
											<td>&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
										<tr>
                                            <td colspan="5">
                                                <center>
                                                    <input type="hidden" class="text" name="submit" value="true" />
                                                    <input type="hidden"  id="sno_hide" name="sno_hide">
                                                </center>
                                            </td>
                                     	</tr>
                                  </table>
								 <input type="hidden" name="txt_mbheader_id_str" id="txt_mbheader_id_str" value="<?php echo $MbhMbdIDStr; ?>">
                            		<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
										<div class="buttonsection">
										<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
										</div>
										<div class="buttonsection">
										<input type="submit" name="submit" value=" Submit " id="submit"/>
										</div>
									</div>
                         		</div>
                        </blockquote>
                    </div>

                </div>
            </div>
    </form>
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
<!--==============================footer=================================-->
<style>
.extraItemTextbox {
    height: 30px;
    position: relative;
    outline: none;
    border: 1px solid #98D8FE;
   /* border-color: rgba(0,0,0,.15);*/
    background-color: white;
	color:#0000cc;
	width:155px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
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
</style>
<?php include "footer/footer.html"; ?>
              <script>
    $(function() {
     function DisplayPer(){
            var subitemnoValue = $("#subitemno option:selected").attr('value');
            $.post("PerService.php", {subitemno:subitemnoValue}, function(data){
            $('#remarks').val(data);
            });
        }
		
	
        	$("#subitemno").bind("change", function() {
            	DisplayPer();
         	});
         	$("#itemno").bind("change", function() {
            	$('#remarks').val('');
         	});
		 
		 	$.fn.validateworkorder = function(event) { 
					if($("#workorderno").val()==""){ 
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
			
			$.fn.validateitemno = function(event) {	
				if($("#itemno").val()==0){ 
					var a="Select item no.";
					$('#val_item').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
				else{
				var a="";
				$('#val_item').text(a);
				}
			}
			
			$.fn.validatesubitemno = function(event) {	
				var itemno = $("#itemno").val();
				if((itemno != "") && (itemno != 0))
				{
					var length = $('#subitemno > option').length;
					if(length > 1)
					{ 
						var subitemno = $("#subitemno").val();
						if((subitemno == 0) && (subitemno != ""))
						{
							$('#val_sub').css('padding-left', '9em');
							var a = "Select Sub item no.";
							$('#val_sub').text(a);
							event.returnValue = false;//for ie
							event.preventDefault();//for chrome
							//return false;//for firefox
						}
						else
						{
							var a="";
							$('#val_sub').css('padding-left', '0em');
							$('#val_sub').text(a);		
						}
					}
				}
				/*else
				{
					var a="";
					$('#val_sub').text(a);		
				}*/
			}
			$.fn.validatesubsubitemno = function(event) {	
				var subitemno = $("#subitemno").val();
				if(subitemno == "")
				{
					var length = $('#subsubitemno > option').length;
					if(length > 1)
					{ 
						var subsubitemno = $("#subsubitemno").val();
						if((subsubitemno == 0) && (subsubitemno != ""))
						{
							$('#val_subsub').css('padding-left', '19em');
							var a = "Select Sub item 2.";
							$('#val_subsub').text(a);
							event.returnValue = false;//for ie
							event.preventDefault();//for chrome
							//return false;//for firefox
						}
						else
						{
							var a="";
							$('#val_subsub').css('padding-left', '0em');
							$('#val_subsub').text(a);		
						}
					}
				}
			}
			$.fn.validatesubsubsubitemno = function(event) {	
				var subsubitemno = $("#subsubitemno").val();
				if(subsubitemno == "")
				{
					var length = $('#subsubsubitemno > option').length;
					if(length > 1)
					{ 
						var subsubsubitemno = $("#subsubsubitemno").val();
						if((subsubsubitemno == 0) && (subsubsubitemno != ""))
						{
							$('#val_subsubsub').css('padding-left', '29em');
							var a = "Select Sub item 3.";
							$('#val_subsubsub').text(a);
							event.returnValue = false;//for ie
							event.preventDefault();//for chrome
							//return false;//for firefox
						}
						else
						{
							var a="";
							$('#val_subsubsub').css('padding-left', '0em');
							$('#val_subsubsub').text(a);		
						}
					}
				}
			}
			
           $.fn.validateextraitem = function(event) {	
		   		var x=0;
				$("#txt_extra_item_no").css({"border-color": "#98D8FE","border-width":"1px","border-style":"solid"});
				$("#txt_extra_item_qty").css({"border-color": "#98D8FE","border-width":"1px","border-style":"solid"});
				$("#txt_extra_item_rate").css({"border-color": "#98D8FE","border-width":"1px","border-style":"solid"});
				$("#txt_extra_item_unit").css({"border-color": "#98D8FE","border-width":"1px","border-style":"solid"});	
					
				if($("#txt_extra_item_no").val()== "")
				{
					$("#txt_extra_item_no").css({"border-color": "#ff8080","border-width":"1px","border-style":"solid"});
					x++; 
				}
				if($("#txt_extra_item_qty").val()== "")
				{
					$("#txt_extra_item_qty").css({"border-color": "#ff8080","border-width":"1px","border-style":"solid"});
					x++; 
				}
				if($("#txt_extra_item_rate").val()== "")
				{
					$("#txt_extra_item_rate").css({"border-color": "#ff8080","border-width":"1px","border-style":"solid"});
					x++; 
				}
				if($("#txt_extra_item_unit").val()== "")
				{
					$("#txt_extra_item_unit").css({"border-color": "#ff8080","border-width":"1px","border-style":"solid"});
					x++; 
				}
				if(x>0)
				{
					var a="Please Enter Required Field";
					$('#val_extra_item').text(a);
					event.returnValue = false;//for ie
					event.preventDefault();//for chrome
					//return false;//for firefox
				}
				else
				{
					var a="";
					$('#val_extra_item').text(a);
				}
			}
			$.fn.validateextraitemdesc = function(event) {	
				$("#txt_extra_item_desc").css({"border-color": "#98D8FE","border-width":"1px","border-style":"solid"});	
				if($("#txt_extra_item_desc").val()==0){ 
					var a="Please Enter Extra Item Description.";
					$("#txt_extra_item_desc").css({"border-color": "#ff8080","border-width":"1px","border-style":"solid"});
					$('#val_extra_item_desc').text(a);
					event.preventDefault();
					event.returnValue = false;
					//return false;
					}
				else{
				var a="";
				$('#val_extra_item_desc').text(a);
				}
			}
		
		$("#top").submit(function(event){
            $(this).validateitemno(event);
			$(this).validatesubitemno(event);
			$(this).validatesubsubitemno(event);
			$(this).validatesubsubsubitemno(event);
			$(this).validateworkorder(event);
            $(this).validateextraitem(event);
			$(this).validateextraitemdesc(event);
			ExtraItemNo_Validation();
         });
		 
		$("#workorderno").change(function(event){
           $(this).validateworkorder(event);
         });
     	$("#subitemno").change(function(event){
           $(this).validatesubitemno(event);
         });
		 $("#subsubitemno").change(function(event){
           $(this).validatesubsubitemno(event);
         });
		 $("#subsubsubitemno").change(function(event){
           $(this).validatesubsubsubitemno(event);
         });
		$("#itemno").change(function(event){
           $(this).validateitemno(event);
         });
		 
        /*$("#txt_extra_item_no").keyup(function(event){
          $(this).validateextraitem(event);
         });*/
		 $("#txt_extra_item_qty").keyup(function(event){
          $(this).validateextraitem(event);
         });
		 $("#txt_extra_item_rate").keyup(function(event){
          $(this).validateextraitem(event);
         });
		 $("#txt_extra_item_unit").keyup(function(event){
          $(this).validateextraitem(event);
         });
		 
		  $("#txt_extra_item_desc").keyup(function(event){
          $(this).validateextraitemdesc(event);
         });
		 
	  });
	  
// $('#extra_item_div').on('keydown', '#txt_extra_item_qty', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});})
 //$('#top').on('keydown', '#txt_extra_item_rate', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});})
 </script>
		      
</body>
</html>