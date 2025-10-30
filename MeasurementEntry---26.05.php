<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
$msg = '';
$staffid = $_SESSION['sid'];
$userid = $_SESSION['userid'];
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);

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
    return $dd . '/' . $mm . '/' . $yy;
}
if(($_GET['mbdetailid'] != "") && ($_GET['mbheaderid'] != ""))
{ 
	$sheetid = $_SESSION['sheet-id'];
	$sql_selectmbdetail = "select mbookdetail.mbdetail_id, mbookdetail.mbheaderid, mbookdetail.descwork, mbookdetail.measurement_no, mbookdetail.measurement_l, mbookdetail.measurement_b, mbookdetail.measurement_d,  mbookdetail.measurement_dia, measurement_contentarea, mbookdetail.remarks, mbookheader.date, subdivision.subdiv_name, sheet.work_order_no, schdule.description, schdule.shortnotes, schdule.measure_type, mbookheader.divid ,mbookheader.subdivid FROM mbookheader
INNER JOIN mbookdetail ON (mbookheader.mbheaderid = mbookdetail.mbheaderid)
INNER JOIN schdule ON (mbookdetail.subdivid = schdule.subdiv_id)
INNER JOIN sheet ON (mbookheader.sheetid = sheet.sheet_id)
INNER JOIN subdivision ON (mbookdetail.subdivid = subdivision.subdiv_id) WHERE  mbookheader.sheetid = ".$sheetid." AND mbookheader.userid = '$userid' AND mbookdetail.mbdetail_id = ".$_GET['mbdetailid']." AND mbookdetail.mbheaderid = ".$_GET['mbheaderid'];
$rs_sql_selectmbdetail = mysql_query($sql_selectmbdetail);
while($List = mysql_fetch_object($rs_sql_selectmbdetail))
{
	$descwork = $List->descwork;
	$measurement_no = $List->measurement_no;
	$measurement_l = $List->measurement_l;
	$measurement_b = $List->measurement_b;
	$measurement_d = $List->measurement_d;
	$measurement_dia = $List->measurement_dia;
	$measurement_contentarea = $List->measurement_contentarea;
	$date = $List->date;
	$subdiv_name = $List->subdiv_name;
	$mbdetail_id = $List->mbdetail_id;
	$work_order_no = $List->work_order_no;
	$description = $List->description;
	$short_notes = $List->shortnotes;
	$div_id = $List->divid;
	$subdiv_id = $List->subdivid;
	$remarks = $List->remarks;
	$measure_type = $List->measure_type;
	if($measure_type != "s"){ $measure_type = "g"; } 
	$subdiv = explode('.' , $subdiv_name);
	if(count($subdiv) == 1){ $divname = $subdiv[0]; $subdivname = "---------None--------"; $subsubdivname = "---------None--------"; $divid = $subdiv_id; $subdivid = 0; $subsubdivid = 0; }
	if(count($subdiv) == 2){ $divname = $subdiv[0]; $subdivname = $subdiv[0].".".$subdiv[1]; $subsubdivname = "---------None--------"; $divid = $div_id; $subdivid = $subdiv_id; $subsubdivid = 0;}
	if(count($subdiv) == 3){ $divname = $subdiv[0]; $subdivname = $subdiv[0].".".$subdiv[1]; $subsubdivname = $subdiv[0].".".$subdiv[1].".".$subdiv[2]; $divid = $div_id; $subdivid = 0; $subsubdivid = $subdiv_id; }
	//echo $divname."<br/>";echo $subdivname."<br/>";echo $subsubdivname."<br/>";exit;
	if(($short_notes != "null") && ($short_notes != ""))
	{ $shortnotes = $short_notes; }
	else { $shortnotes = $description; }
}
}
if(isset($_POST["update_x"]))
{
    $measurement_type = $_POST['txt_measure_type'];
    
    if($measurement_type == "s")
    {
        $mbookentryupdate_sql = "UPDATE mbookdetail SET descwork = '".$_POST['txt_dec_wk_mt']."' ,   
                                                                measurement_dia = '".trim($_POST['sel_dia_mt'])."' , 
                                                                measurement_no = '".trim($_POST['txt_no_mt'])."' ,
                                                                measurement_l = '".trim($_POST['txt_l_mt'])."' ,
                                                                measurement_contentarea  = '".trim($_POST['txt_ca_mt'])."' WHERE  mbdetail_id = '".$_POST['txt_mbdetailid']."'";
        //echo $mbookentryupdate_sql;exit;
       $mbookentryupdate_query = mysql_query($mbookentryupdate_sql); 
       header('Location: ViewMeasurementEntryList.php');
    }
    else
    {
        $mbookentryupdate_sql = "UPDATE mbookdetail SET descwork = '".$_POST['txt_dec_wk']."' ,   
                                                                measurement_no = '".trim($_POST['txt_no'])."' , 
                                                                measurement_l = '".trim($_POST['txt_l'])."' ,
                                                                measurement_b = '".trim($_POST['txt_b'])."' ,
                                                                measurement_d = '".trim($_POST['txt_d'])."' ,
                                                                measurement_contentarea  = '".trim($_POST['txt_ca'])."' WHERE  mbdetail_id = '".$_POST['txt_mbdetailid']."'";
       // echo $mbookentryupdate_sql;exit;
       $mbookentryupdate_query = mysql_query($mbookentryupdate_sql);  
       header('Location: ViewMeasurementEntryList.php');
    }
  
}
if(isset($_POST["submit_x"])) {
	 $unit = $_POST['remarks'];
     $sheet_id = trim($_POST['workorderno']);
     if((trim($_POST['subitemno']) != 0) && (trim($_POST['subitemno']) != "empty"))
     {
         $subdiv_id = trim($_POST['subitemno']);
     }
     else if((trim($_POST['subsubitemno']) != 0) && (trim($_POST['subsubitemno']) != "empty"))
     {
         $subdiv_id = trim($_POST['subsubitemno']);
     }
     else
     {
         $divid= trim($_POST['itemno']);
         $sql_selectsubid = "select subdiv_id from subdivision where div_id='$divid'";
         $res_subid = mysql_query($sql_selectsubid);
         $subdiv_id = @mysql_result($res_subid,0,'subdiv_id');
     }
     //$descriptionnotes = trim($_POST['descriptionnotes']);
	 $date=$_POST['datepicker'];
	 //$dateformat = date_parse_from_format("d-m-Y", $date);
	 $pieces = explode("-", $date);
	 $arr = array($pieces[2],$pieces[1],$pieces[0]);
	 $date=implode("-",$arr);
	 //echo "new date format".$date.$sheet_id;print_r($pieces);exit;
    $mbookdetailquery = "SELECT  subdiv_id,shortnotes FROM schdule WHERE subdiv_id='$subdiv_id'";
    //echo "<br>".$mbookdetailquery;
    $mbookdetailsql = dbQuery($mbookdetailquery);
    $mbookdetaillist = dbFetchAssoc($mbookdetailsql);
    //extract($mbookdetaillist);
	//echo '<pre>';print_r($mbookdetaillist);exit();
    //if($mbookdetaillist['descshortnotes']=='') { $mbookdetailupdatequery = "UPDATE schdule SET shortnotes  ='$descriptionnotes'  WHERE subdiv_id='$subdiv_id'";
      // echo $mbookdetailupdatequery;
   // $schduleupdatesql = dbQuery($mbookdetailupdatequery);} 
    $div_id = trim($_POST['itemno']);
    $unit = trim($_POST['txt_unit']);
    $billno = trim($_POST['workorderno']);
    $agreeno = trim($_POST['txt_agmt_no']);
    $conname = trim($_POST['txt_cont_name']);
    $techsanction = trim($_POST['txt_tech_san']);
    $wrkname = trim($_POST['txt_wk_name']);
    
	$mbookheaderquery = "INSERT INTO `mbookheader`(`date`, `sheetid`, `allotmentid`,`divid`,`subdivid`, `active`, `userid`) VALUES ('$date','$sheet_id',1,'$div_id','$subdiv_id',1,'$userid')";
	//echo $mbookheaderquery; exit;
   // $mbookheaderquery = "INSERT INTO mbookheader (date,sheet_id,allotmentid,active,userid)  VALUES ('$date','$sheet_id','1',1,1)";
    $mbookheadersql = mysql_query($mbookheaderquery);
	//if ($mbookheadersql == true){echo "one pass";}else{echo "error";echo $mbookheaderquery; exit;}
    $lastmbookheaderid = mysql_insert_id();
	if($_POST['txt_measure_type']=="s")
	{
	$rec = explode(".", $_POST['add_set_a2']);
	for ($c = 0; $c < count($rec); $c++) 
	{
		$x = $rec[$c];
		if($x!=2) 
		{
		
		$mbookdetailsquery="INSERT INTO `mbookdetail`(`mbheaderid`, `subdivid`, `descwork`, `measurement_no`, `measurement_l`, `measurement_dia`, `measurement_contentarea`, `remarks`) VALUES ('" . $lastmbookheaderid . "' ,
								  		 '" . $subdiv_id . "',
										 '" . $_POST['txt_dec_wk_mt' . $x] . "' ,
										 '" . $_POST['txt_no_mt' . $x] . "' ,
										 '" . $_POST['txt_l_mt' . $x] . "' ,
										 '" . $_POST['sel_dia_mt' . $x] . "' ,
										 '" . $_POST['txt_ca_mt' . $x] . "' ,										 
										 '" . $_POST['remarks'] . "')";
        $mbookdetailssql = mysql_query($mbookdetailsquery);
         //echo "<br>".$mbookdetailsquery; 
		 //echo "hello";
		 //exit;	
		}
	}
	}
	else
	{
 	$rec = explode(".", $_POST['add_set_a1']);
    for ($c = 0; $c < count($rec); $c++) {
        $x = $rec[$c];
		if($x!=2)
		{
       //$mbookdetailsquery = "insert into mbookdetail(`mbheaderid`, `subdivid`, `descwork`, `measurement_no`, `measurement_l`, `measurement_b`, `measurement_d`, `measurement_contentarea`, `remarks`)
								//  values('" . $lastmbookheaderid . "' ,
								  //		 '" . $subdiv_id . "',
									//	 '" . $_POST['txt_dec_wk' . $x] . "' ,
										// '" . $_POST['txt_no' . $x] . "' ,
										// '" . $_POST['txt_l' . $x] . "' ,
										 //'" . $_POST['txt_b' . $x] . "' ,
										 //'" . $_POST['txt_d' . $x] . "' ,
										 //'" . $_POST['txt_ca' . $x] . "' ,										 
										 //'" . $_POST['remarks' . $x] . "')";
		
		$mbookdetailsquery="INSERT INTO `mbookdetail`(`mbheaderid`, `subdivid`, `descwork`, `measurement_no`, `measurement_l`, `measurement_b`, `measurement_d`, `measurement_contentarea`, `remarks`) VALUES ('" . $lastmbookheaderid . "' ,
								  		 '" . $subdiv_id . "',
										 '" . $_POST['txt_dec_wk' . $x] . "' ,
										 '" . $_POST['txt_no' . $x] . "' ,
										 '" . $_POST['txt_l' . $x] . "' ,
										 '" . $_POST['txt_b' . $x] . "' ,
										 '" . $_POST['txt_d' . $x] . "' ,
										 '" . $_POST['txt_ca' . $x] . "' ,										 
										 '" . $_POST['remarks'] . "')";
        $mbookdetailssql = mysql_query($mbookdetailsquery);
         //echo "<br>".$mbookdetailsquery;exit;
    }
	}
	}
    if ($mbookdetailssql == true && $mbookheadersql == true ) {
        $msg = "Data Submitted Successfully";
    }else{echo "error";} 

}//submit 
?>
<?php require_once "Header.html"; ?>

    <script type="text/javascript"  language="JavaScript">
		
		function clear()
		{
		alert("clear");
		document.getElementById("remarks").value = 0;
		}
         function validation()
        {
            if (document.form.workorderno.value == 0)
            {
                alert("Select the Work Order No");
                return false;
            }
            if (document.form.itemno.value == 0)
            {
                alert("Select the Item No");
                return false;
            }
            if (document.form.subitemno.value == 0)
            {
                alert("Select the Sub Item No");
                return false;
            }
            if (document.form.add_set_a1.value == "")
            {
                alert("Add atleast one row");
                return false;
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
                        optn.text = "--Item No.--";
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
                        document.form.txt_wk_name.value = wrkname[0];
                        document.form.txt_cont_name.value = wrkname[2];
                        document.form.txt_tech_san.value = wrkname[1];
                        document.form.txt_agmt_no.value = wrkname[3];
                        document.form.txt_runn_bill_no.value = wrkname[4];
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
                        document.form.subitemno.value = 'Select';
                        document.form.subsubitemno.value = 'Select';
                    }
                    else
                    {
                        var name = data.split("*");
                        document.form.subitemno.length = 0;
                        var optn = document.createElement("option");
                        optn.value = 0;
                        optn.text = "--Sub Item No.--";
                        document.form.subitemno.options.add(optn);
                        
                        document.form.subsubitemno.length = 0;
                        var optn1 = document.createElement("option");
                        optn1.value = 0;
                        optn1.text = "--Sub Sub Item No.--";
                        document.form.subsubitemno.options.add(optn1);
                        
                        
                        var cont = 1;
                        var c = name.length;
                        var a = c / 2;
                        var b = a + 1;
                        var pre_opt_text = "";
                        for (i = 1, j = b; i < a, j < c; i++, j++)
                        {
                            var spl_sitem = name[j].split(".");
                           /* if(c == 4)
                            //if(spl_sitem.length == 1)
                            //if((spl_sitem.length == 1) && (item == name[j]))
                            {
                                var optn = document.createElement("option")
                                optn.value = 0;
                                optn.text = "--None---";
                                document.form.subitemno.options.add(optn)
                            }*/
                           
                                if(spl_sitem.length == 2)
                                {
                                    var optn = document.createElement("option")
                                    optn.value = name[i];
                                    optn.text = name[j];
                                    document.form.subitemno.options.add(optn)
                                    cont++;
                                }
                                if(spl_sitem.length > 2)
                                {
                                    var optn = document.createElement("option")
                                    //optn.value = name[i];
                                    optn.value = "empty";
                                    optn.text =  spl_sitem[0] +"."+ spl_sitem [1];
                                    if(optn.text != pre_opt_text)
                                    {
                                        document.form.subitemno.options.add(optn)
                                    }
                                    pre_opt_text = optn.text;
                                    cont++;
                                }
                                
                            
                        } 
                        if(cont <= 1)
                        {
                           // alert("count="+cont);
                            document.getElementById("subitemno").disabled = true;
                            document.getElementById("subsubitemno").disabled = true;
                        }
                        else
                        {
                            document.getElementById("subitemno").disabled = false;
                            document.getElementById("subsubitemno").disabled = false;
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
                        document.form.subsubitemno.value = 'Select';
                    }
                    else
                    {   
                       // var subitem = ssitem.split(".");
                       // var subitem_part1 = subitem[0];
                       // alert(subitem);
                        //alert(subitem_part1);
                        var name = data.split("*");
                        document.form.subsubitemno.length = 0
                        var optn = document.createElement("option")
                        optn.value = 0;
                        optn.text = "Sub Sub Item No.";
                        document.form.subsubitemno.options.add(optn)
                        var cont = 1;
                        var c = name.length
                        var a = c / 2;
                        var b = a + 1;
                        for (i = 1, j = b; i < a, j < c; i++, j++)
                        {
                            if(name[j].length > 2)
                            {
                                var sitem = name[j].split(".");
                                var sub = sitem[0] +"."+ sitem[1];
                                if(sitem.length > 2)
                                {
                                    if(ssitem == sub)
                                    {
                                        var subsubitem = sitem[0] +"."+ sitem[1] +"."+ sitem[2];
                                        var optn = document.createElement("option")
                                        optn.value = name[i];
                                        optn.text = subsubitem;
                                        document.form.subsubitemno.options.add(optn)
                                        cont ++;
                                    }
                                }
                            }
                        }
                     if(cont <= 1)
                        {
                            document.getElementById("subsubitemno").disabled = true;
                        }
                        else
                        {
                            document.getElementById("subsubitemno").disabled = false;
                        }   
                    }
                }
            }
            xmlHttp.send(strURL);

            var e = document.getElementById("itemno");
            var strUser = e.options[e.selectedIndex].text;
            document.form.txt_item_no.value = strUser;
        }
        function getitem_desc(itemval)
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
                        document.getElementById("remarks").value = name[1];
                        document.getElementById("label_unit").innerHTML = name[1];
			if(name[2]=="")
                            { 
				document.form.descriptionnotes.value=name[0];
                            }
			else
                            {
				document.form.descriptionnotes.value=name[2];
                            } 
			//document.form.txt_unit.value = name[1];
                        
                        if(name[3] == 's')
                        {
                            document.getElementById("txt_measure_type").value = name[3];
                        }
                        else
                        {
                           document.getElementById("txt_measure_type").value = "g"; 
                        }
                    }
                }
            }   
            xmlHttp.send(strURL);
        }
        function find_desc(subitemid)
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
                        {
                                                    document.getElementById("remarks").value = name[1];
                                                    document.getElementById("label_unit").innerHTML = name[1];
                                                    if(name[2]=="")
                                                    {var j = name[1];

                                                    document.form.descriptionnotes.value=name[0];
                                                    }
                                                    else
                                                    {
                                                    document.form.descriptionnotes.value=name[2];}
                                                    //document.form.txt_unit.value = name[1];
                                                  if(name[3] == 's')
                                                    {
                                                        document.getElementById("txt_measure_type").value = name[3];
                                                    }
                                                    else
                                                    {
                                                       document.getElementById("txt_measure_type").value = "g"; 
                                                    }

                                                    //document.form.text_desc.value = name[0];                      
                        }


                    }
                }
                xmlHttp.send(strURL);

                var e = document.getElementById("subitemno");
                var strUser = e.options[e.selectedIndex].text;
                document.form.txt_boq.value = strUser;
             }
        }
  		function display()
		{
			if(document.form.txt_measure_type.value=="s")
			{   
				document.getElementById("table1").className = "hide";
				document.getElementById("table2").className = "";
				
			}
			else
			{
				document.getElementById("table1").className = "";
				document.getElementById("table2").className = "hide";
			}
		}
			
			
		function valid()
		{
			if(document.form.txt_measure_type.value=="s")
			{
				if(document.form.txt_dec_wk_mt.value=="")
					{alert("Enter Description");exit;}
				if(document.form.txt_no_mt.value=="")
					{alert("Enter Number");exit;}
				if(document.form.txt_l_mt.value=="")
					{alert("Enter Length");exit;}
				
			}
			else 
			{
			if(document.form.txt_dec_wk.value=="")
					{alert("Enter Description");exit;}
			if(document.form.txt_no.value=="")
					{alert("Enter number");exit;}
			}
							
			if(document.form.remarks.value=="Cum" || document.form.remarks.value=="cum")
			{
				if(document.form.txt_l.value=="" || document.form.txt_d.value=="" || document.form.txt_b.value== "")
				{alert("Enter Length Breadth and Depth");exit;}
			}
			if(document.form.remarks.value=="Sqm")
			{   
				if(document.form.txt_l.value=="" || document.form.txt_d.value=="")
				{alert("Enter Length and Depth");exit;}
				
			}
			
			
	}
	
	function cls()
	{document.form.descriptionnotes.value="";
	 document.form.remarks.value="";
	 document.getElementById("label_unit").innerHTML = "";
	 //document.getElementById("label_unit").outerHTML="";
	}
    //.......Multiple  Row Add Function........//
        var add_row_s = 3;
        var prev_edit_row = 0
        function addrow()
        {	
			valid();
			 /*x=alltrim(document.form.txt_boq.value);
             if(x.length==0)
             {
             alert("Please Enter the Boq")
             document.form.txt_boq.value="";
             document.form.txt_boq.focus();
             return false
             }x=alltrim(document.form.txt_dec_wk.value);
             if(x.length==0)
             {
             alert("Please Enter the Description")
             document.form.txt_dec_wk.value="";
             document.form.txt_dec_wk.focus();
             return false
             }x=alltrim(document.form.txt_no.value);
             if(x.length==0)
             {
             alert("Please Enter the Measurement No.")
             document.form.txt_no.value="";
             document.form.txt_no.focus();
             return false
             }x=alltrim(document.form.txt_l.value);
             if(x.length==0)
             {
             alert("Please Enter the Measurement L.")
             document.form.txt_l.value="";
             document.form.txt_l.focus();
             return false
             }x=alltrim(document.form.txt_b.value);
             if(x.length==0)
             {
             alert("Please Enter the Measurement B.")
             document.form.txt_b.value="";
             document.form.txt_b.focus();
             return false
             }x=alltrim(document.form.txt_d.value);
             if(x.length==0)
             {
             alert("Please Enter the Measurement D.")
             document.form.txt_d.value="";
             document.form.txt_d.focus();
             return false
             }x=alltrim(document.form.txt_ca.value);
             if(x.length==0)
             {
             alert("Please Enter the Measurement Contents or Area")
             document.form.txt_ca.value="";
             document.form.txt_ca.focus();
             return false
             }
             x=alltrim(document.form.remarks.value);
             if(x.length==0)
             {
             alert("Please Enter the Remarks")
             document.form.remarks.value="";
             document.form.remarks.focus();
             return false
             }*/
			 
     var new_row = document.getElementById("mbookdetail").insertRow(add_row_s);
            new_row.setAttribute("id", "row_" + add_row_s)
            new_row.className = "labelcenter";

            var c1 = new_row.insertCell(0);
            c1.align = "center";c1.style.border = "thin solid black";
            var c2 = new_row.insertCell(1);
            c2.align = "center";c2.style.border = "thin solid black";
            var c3 = new_row.insertCell(2);
            c3.align = "center";c3.style.border = "thin solid black";
            var c4 = new_row.insertCell(3);
            c4.align = "center";c4.style.border = "thin solid black";
            var c5 = new_row.insertCell(4);
            c5.align = "center";c5.style.border = "thin solid black";
            var c6 = new_row.insertCell(5);
            c6.align = "center";c6.style.border = "thin solid black";
            var c7 = new_row.insertCell(6);
            c7.align = "center";c7.style.border = "thin solid black";
            var c8 = new_row.insertCell(7);
            c8.align = "center";c8.style.border = "thin solid black";c8.style.display="none";
            var c9 = new_row.insertCell(8);
            c9.align = "center";c9.style.border = "thin solid black";
		
            //c1.innerText = document.form.txt_boq.value;
			c1.innerText = c1.textContent = document.form.sno.value;
            c2.innerText = c2.textContent = document.form.txt_dec_wk.value;
            c3.innerText = c3.textContent = document.form.txt_no.value;
            c4.innerText = c4.textContent = document.form.txt_l.value;
            c5.innerText = c5.textContent = document.form.txt_b.value;
            c6.innerText = c6.textContent = document.form.txt_d.value;
            c7.innerText = c7.textContent = document.form.txt_ca.value
            c8.innerText = c8.textContent = document.form.remarks.value;
            c9.innerHTML = c9.textContent = "<input type='button' class='editbtnstyle' name='btn_edit_" + add_row_s + "' id='btn_edit_" + add_row_s + "' value='' style='width: 40px' onClick=editrow(" + add_row_s + ",'n')><input class='delbtnstyle' type='button'  name='btn_del_" + add_row_s + "'  id='btn_del_" + add_row_s + "' value='' style='width: 40px' onClick=delrow(" + add_row_s + ")>";
            var hide_values = "";
			hide_values = "<input type='hidden' value='" + c1.innerText + "' name='sno" + add_row_s + "' id='sno" + add_row_s + "' >";
            hide_values += "<input type='hidden' value='" + c2.innerText + "' name='txt_dec_wk" + add_row_s + "' id='txt_dec_wk" + add_row_s + "' >";
            hide_values += "<input type='hidden' value='" + c3.innerText + "' name='txt_no" + add_row_s + "' id='txt_no" + add_row_s + "' >";
            hide_values += "<input type='hidden' value='" + c4.innerText + "' name='txt_l" + add_row_s + "' id='txt_l" + add_row_s + "' >";
            hide_values += "<input type='hidden' value='" + c5.innerText + "' name='txt_b" + add_row_s + "' id='txt_b" + add_row_s + "' >";
            hide_values += "<input type='hidden' value='" + c6.innerText + "' name='txt_d" + add_row_s + "' id='txt_d" + add_row_s + "' >";
            hide_values += "<input type='hidden' value='" + c7.innerText + "' name='txt_ca" + add_row_s + "' id='txt_ca" + add_row_s + "' >";
            hide_values += "<input type='hidden' value='" + c8.innerText + "' name='remarks" + add_row_s + "' id='remarks" + add_row_s + "' >";
            document.getElementById("add_hidden").innerHTML = document.getElementById("add_hidden").innerHTML + hide_values; 

            if (document.getElementById("add_set_a1").value == "")
                document.getElementById("add_set_a1").value = add_row_s;
            else
                document.getElementById("add_set_a1").value = document.getElementById("add_set_a1").value + "." + add_row_s; 
				 document.form.sno.value=parseInt(document.form.sno.value)+1;
            add_row_s++;
            cleartxt();
       
        }
        function editrow(rowno, update)
        {   
			
            var total;
            var net_value;
            var edit_row = document.getElementById("mbookdetail").rows[rowno].cells;
			var sno=document.form.sno.value;
			//gdocument.form.txt_dec_wk.focus();
			if(document.form.sno_hide.value=="")
			{
			document.form.sno_hide.value=document.form.sno.value;
			}
            
			if (update == 'y') // transfer controls to table row
            {	
				
				edit_row[0].innerText = edit_row[0].textContent = document.form.sno.value;
                edit_row[1].innerText = edit_row[1].textContent = document.form.txt_dec_wk.value;
                edit_row[2].innerText = edit_row[2].textContent= document.form.txt_no.value;
                edit_row[3].innerText = edit_row[3].textContent= document.form.txt_l.value;
                edit_row[4].innerText = edit_row[4].textContent= document.form.txt_b.value;
                edit_row[5].innerText = edit_row[5].textContent= document.form.txt_d.value;
                edit_row[6].innerText = edit_row[6].textContent= document.form.txt_ca.value;
                edit_row[7].innerText = edit_row[7].textContent= document.form.remarks.value;

                document.getElementById("sno" + rowno).value = edit_row[0].innerText = edit_row[0].textContent
                document.getElementById("txt_dec_wk" + rowno).value = edit_row[1].innerText = edit_row[1].textContent
                document.getElementById("txt_no" + rowno).value = edit_row[2].innerText = edit_row[2].textContent
                document.getElementById("txt_l" + rowno).value = edit_row[3].innerText = edit_row[3].textContent
                document.getElementById("txt_b" + rowno).value = edit_row[4].innerText = edit_row[4].textContent
                document.getElementById("txt_d" + rowno).value = edit_row[5].innerText = edit_row[5].textContent
                document.getElementById("txt_ca" + rowno).value = edit_row[6].innerText = edit_row[6].textContent
                document.getElementById("remarks" + rowno).value = edit_row[7].innerText = edit_row[7].textContent
            }//update=='y'

            else  //transfer table row to controls
            {
				document.form.sno.value = edit_row[0].innerText = edit_row[0].textContent
                document.form.txt_dec_wk.value = edit_row[1].innerText = edit_row[1].textContent
                document.form.txt_no.value = edit_row[2].innerText = edit_row[2].textContent
                document.form.txt_l.value = edit_row[3].innerText = edit_row[3].textContent
                document.form.txt_b.value = edit_row[4].innerText = edit_row[4].textContent
                document.form.txt_d.value = edit_row[5].innerText = edit_row[5].textContent
                document.form.txt_ca.value = edit_row[6].innerText = edit_row[6].textContent
                document.form.remarks.value = edit_row[7].innerText = edit_row[7].textContent
            }

            if (prev_edit_row == 0)//first time edit the row
            {
                document.getElementById("row_" + rowno).style.color = "red";
                document.getElementById("btn_edit_" + rowno).className = "cancelbtnstyle";
                document.getElementById("btn_add").outerHTML = "<input type='button' class='updatebtnstyle' style='width: 40px' name='btn_add' id='btn_add' value='' onClick=\"editrow(" + rowno + ",'y')\">";
                prev_edit_row = rowno;
            }
            else
            {	
				//set
				
                if (rowno == prev_edit_row)
                {
                    document.getElementById("row_" + prev_edit_row).style.color = "#770000";
                    document.getElementById("btn_edit_" + rowno).className = "editbtnstyle";
                    document.getElementById("btn_add").outerHTML = "<input type='button' class='addbtnstyle' style='width: 40px' name='btn_add' id='btn_add' value='' onClick='addrow()'>";
                    prev_edit_row = 0;
                    cleartxt();
                }

                else
                {	document.getElementById("sno").value=document.getElementById("sno_hide").value;
					document.getElementById("sno_hide").value="";
                    document.getElementById("row_" + prev_edit_row).style.color = "#770000";
                    document.getElementById("btn_edit_" + prev_edit_row).className = "editbtnstyle";
                    document.getElementById("row_" + rowno).style.color = "red";
                    document.getElementById("btn_edit_" + rowno).className = "cancelbtnstyle";
                    document.getElementById("btn_add").outerHTML = "<input type='button' claas='updatebtnstyle' style='width: 40px' name='btn_add' id='btn_add' value='' onClick=\"editrow(" + rowno + ",'y')\">";
                    prev_edit_row = rowno;
                }document.getElementById("sno").value=document.getElementById("sno_hide").value;document.getElementById("sno_hide").value="";
            }
        }

        function delrow(rownum)
        {	var no=document.getElementById("sno_hide").value=document.getElementById("sno").value;
            var src_row = (rownum + 1)
            var tar_row = rownum
            var noofadd = (add_row_s - 1)
			
            for (x = rownum; x < noofadd; x++)
            {	
				document.getElementById("sno" + tar_row).value= document.getElementById("sno" + src_row).value;
                document.getElementById("txt_dec_wk" + tar_row).value = document.getElementById("txt_dec_wk" + src_row).value
                document.getElementById("txt_no" + tar_row).value = document.getElementById("txt_no" + src_row).value
                document.getElementById("txt_l" + tar_row).value = document.getElementById("txt_l" + src_row).value
                document.getElementById("txt_b" + tar_row).value = document.getElementById("txt_b" + src_row).value
                document.getElementById("txt_d" + tar_row).value = document.getElementById("txt_d" + src_row).value
                document.getElementById("txt_ca" + tar_row).value = document.getElementById("txt_ca" + src_row).value;
                document.getElementById("remarks" + tar_row).value = document.getElementById("remarks" + src_row).value;
                tar_row++;
                src_row++;
                var trow = document.getElementById("mbookdetail").rows[x].cells;
                var srow = document.getElementById("mbookdetail").rows[x + 1].cells;
				trow[0].innerText = trow[0].textContent = srow[0].innerText = srow[0].textContent 
                trow[1].innerText = trow[1].textContent  = srow[1].innerText = srow[1].textContent
                trow[2].innerText = trow[2].textContent  = srow[2].innerText = srow[2].textContent
                trow[3].innerText = trow[3].textContent  = srow[3].innerText = srow[3].textContent
                trow[4].innerText = trow[4].textContent  = srow[4].innerText = srow[4].textContent
                trow[5].innerText = trow[5].textContent  = srow[5].innerText = srow[5].textContent
                trow[6].innerText = trow[6].textContent  = srow[6].innerText = srow[6].textContent
                trow[7].innerText = trow[7].textContent  = srow[7].innerText = srow[7].textContent
            }
			document.getElementById("sno" + tar_row).outerHTML = ""
            document.getElementById("txt_dec_wk" + tar_row).outerHTML = ""
            document.getElementById("txt_no" + tar_row).outerHTML = ""
            document.getElementById("txt_l" + tar_row).outerHTML = ""
            document.getElementById("txt_b" + tar_row).outerHTML = ""
            document.getElementById("txt_d" + tar_row).outerHTML = ""
            document.getElementById("txt_ca" + tar_row).outerHTML = ""
            document.getElementById("remarks" + tar_row).outerHTML = ""

            document.getElementById('mbookdetail').deleteRow(noofadd)
            document.getElementById("add_set_a1").value = "";

            for (x = 2; x < noofadd; x++)
            {
                if (document.getElementById("add_set_a1").value == "")
                    {document.getElementById("add_set_a1").value = x;
					document.getElementById("sno").value=x-1;
					}
                else
				{
                    document.getElementById("add_set_a1").value += ("." + x);
					document.getElementById("sno").value=x-1;
				}
            }
		
            add_row_s = noofadd++; 
			for(i=1;i<no-1;i++)
			{
			var trow = document.getElementById("mbookdetail").rows[i+2].cells; 
			trow[0].innerText = trow[0].textContent = i;
			}
			document.getElementById("sno_hide").value="";
        }
		
        function cleartxt()
        {
			//document.getElementById("sno").value = "";
            document.getElementById("txt_dec_wk").value = "";
            document.getElementById("txt_no").value = "";
            document.getElementById("txt_l").value = "";
            document.getElementById("txt_b").value = "";
            document.getElementById("txt_d").value = "";
            document.getElementById("txt_ca").value = "";
            //document.getElementById("remarks").value = "";
        }

        function contentorarea()
        {
            var no = document.form.txt_no.value
            var l = document.form.txt_l.value
            var b = document.form.txt_b.value
            var d = document.form.txt_d.value


            if (no != '')
            {
                no = no;
            }
            else
            {
                no = 1;
            }
            if (l != '')
            {
                l = l;
            }
            else
            {
                l = 1;
            }
            if (b != '')
            {
                b = b;
            }
            else
            {
                b = 1;
            }
            if (d != '')
            {
                d = d;
            }
            else
            {
                d = 1;
            }
            var ca = Number(no) * Number(l) * Number(b) * Number(d);
			ca=ca.toFixed(3);
            document.form.txt_ca.value = ca;
        }
		
		function isNumber(evt) {
			evt = (evt) ? evt : window.event;
			var charCode = (evt.which) ? evt.which : evt.keyCode; 
			if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57) && (charCode != 45)) {
				return false;
			}
			return true;
		}
		
		 function calculate()
		 {
		 var dia = document.form.sel_dia_mt.value;
		 var no = document.form.txt_no_mt.value;
         var l = document.form.txt_l_mt.value;
		 if(no == "")
		 {no=1;}
		 if(l=="")
		 {l=1;}
		 var result = Number(no) * Number(l);
		 result = result.toFixed(3);
		 document.form.txt_ca_mt.value = result;
		 if(dia=="8")
		 {document.form.txt_8.value = result;
		 document.form.txt_10.value = "";
		 document.form.txt_12.value = "";
		 document.form.txt_16.value = "";
		 document.form.txt_20.value = "";
		 document.form.txt_25.value = "";
		 document.form.txt_28.value = "";
		 document.form.txt_32.value = "";
		 }
		  if(dia=="10")
		 {document.form.txt_8.value = "";
		 document.form.txt_10.value = result;
		 document.form.txt_12.value = "";
		 document.form.txt_16.value = "";
		 document.form.txt_20.value = "";
		 document.form.txt_25.value = "";
		 document.form.txt_28.value = "";
		 document.form.txt_32.value = "";}
		 if(dia=="12")
		 {document.form.txt_8.value = "";
		 document.form.txt_10.value = "";
		 document.form.txt_12.value = result;
		 document.form.txt_16.value = "";
		 document.form.txt_20.value = "";
		 document.form.txt_25.value = "";
		 document.form.txt_28.value = "";
		 document.form.txt_32.value = "";}
		 if(dia=="16")
		 {document.form.txt_8.value = "";
		 document.form.txt_10.value = "";
		 document.form.txt_12.value = "";
		 document.form.txt_16.value = result;
		 document.form.txt_20.value = "";
		 document.form.txt_25.value = "";
		 document.form.txt_28.value = "";
		 document.form.txt_32.value = "";}
		 if(dia=="20")
		 {document.form.txt_8.value = "";
		 document.form.txt_10.value = "";
		 document.form.txt_12.value = "";
		 document.form.txt_16.value = "";
		 document.form.txt_20.value = result;
		 document.form.txt_25.value = "";
		 document.form.txt_28.value = "";
		 document.form.txt_32.value = "";}
		 if(dia=="25")
		 {document.form.txt_8.value = "";
		 document.form.txt_10.value = "";
		 document.form.txt_12.value = "";
		 document.form.txt_16.value = "";
		 document.form.txt_20.value = "";
		 document.form.txt_25.value = result;
		 document.form.txt_28.value = "";
		 document.form.txt_32.value = "";}
		 if(dia=="28")
		 {document.form.txt_8.value = "";
		 document.form.txt_10.value = "";
		 document.form.txt_12.value = "";
		 document.form.txt_16.value = "";
		 document.form.txt_20.value = "";
		 document.form.txt_25.value = "";
		 document.form.txt_28.value = result;
		 document.form.txt_32.value = "";}
		 if(dia=="32")
		 {document.form.txt_8.value = "";
		 document.form.txt_10.value = "";
		 document.form.txt_12.value = "";
		 document.form.txt_16.value = "";
		 document.form.txt_20.value = "";
		 document.form.txt_25.value = "";
		 document.form.txt_28.value = "";
		 document.form.txt_32.value = result;}
		 }
		 
		/* add row for steel */ 
		var add_row_s = 3;
        var prev_edit_row = 0
        function addrow_mt()
        {	
			 valid();
			 /*x=alltrim(document.form.txt_boq.value);
             if(x.length==0)
             {
             alert("Please Enter the Boq")
             document.form.txt_boq.value="";
             document.form.txt_boq.focus();
             return false
             }x=alltrim(document.form.txt_dec_wk.value);
             if(x.length==0)
             {
             alert("Please Enter the Description")
             document.form.txt_dec_wk.value="";
             document.form.txt_dec_wk.focus();
             return false
             }x=alltrim(document.form.txt_no.value);
             if(x.length==0)
             {
             alert("Please Enter the Measurement No.")
             document.form.txt_no.value="";
             document.form.txt_no.focus();
             return false
             }x=alltrim(document.form.txt_l.value);
             if(x.length==0)
             {
             alert("Please Enter the Measurement L.")
             document.form.txt_l.value="";
             document.form.txt_l.focus();
             return false
             }x=alltrim(document.form.txt_b.value);
             if(x.length==0)
             {
             alert("Please Enter the Measurement B.")
             document.form.txt_b.value="";
             document.form.txt_b.focus();
             return false
             }x=alltrim(document.form.txt_d.value);
             if(x.length==0)
             {
             alert("Please Enter the Measurement D.")
             document.form.txt_d.value="";
             document.form.txt_d.focus();
             return false
             }x=alltrim(document.form.txt_ca.value);
             if(x.length==0)
             {
             alert("Please Enter the Measurement Contents or Area")
             document.form.txt_ca.value="";
             document.form.txt_ca.focus();
             return false
             }
             x=alltrim(document.form.remarks.value);
             if(x.length==0)
             {
             alert("Please Enter the Remarks")
             document.form.remarks.value="";
             document.form.remarks.focus();
             return false
             }*/
			 
     		var new_row = document.getElementById("mbookmetal").insertRow(add_row_s);
            new_row.setAttribute("id", "row_" + add_row_s)
            new_row.className = "labelcenter";

            var c1 = new_row.insertCell(0);
            c1.align = "center";c1.style.border = "thin solid black";
            var c2 = new_row.insertCell(1);
            c2.align = "center";c2.style.border = "thin solid black";
            var c3 = new_row.insertCell(2);
            c3.align = "center";c3.style.border = "thin solid black";
            var c4 = new_row.insertCell(3);
            c4.align = "center";c4.style.border = "thin solid black";
            var c5 = new_row.insertCell(4);
            c5.align = "center";c5.style.border = "thin solid black";
            var c6 = new_row.insertCell(5);
            c6.align = "center";c6.style.border = "thin solid black";
            var c7 = new_row.insertCell(6);
            c7.align = "center";c7.style.border = "thin solid black";
            var c8 = new_row.insertCell(7);
            c8.align = "center";c8.style.border = "thin solid black";
            var c9 = new_row.insertCell(8);
            c9.align = "center";c9.style.border = "thin solid black";
			var c10 = new_row.insertCell(9);
            c10.align = "center";c10.style.border = "thin solid black";
			var c11 = new_row.insertCell(10);
            c11.align = "center";c11.style.border = "thin solid black";
			var c12 = new_row.insertCell(11);
            c12.align = "center";c12.style.border = "thin solid black";
			var c13 = new_row.insertCell(12);
            c13.align = "center";c13.style.border = "thin solid black";
			var c14 = new_row.insertCell(13);
            c14.align = "center";c14.style.border = "thin solid black";
			var c15 = new_row.insertCell(14);
            c15.align = "center";c15.style.border = "thin solid black";c14.style.display="none";
            //c1.innerText = document.form.txt_boq.value;
			c1.innerText = c1.textContent = document.form.sno_mt.value;
            c2.innerText = c2.textContent = document.form.txt_dec_wk_mt.value;
            c3.innerText = c3.textContent = document.form.sel_dia_mt.value;
            c4.innerText = c4.textContent = document.form.txt_no_mt.value;
            c5.innerText = c5.textContent = document.form.txt_l_mt.value;
            c6.innerText = c6.textContent = document.form.txt_8.value;
            c7.innerText = c7.textContent = document.form.txt_10.value;
			c8.innerText = c8.textContent = document.form.txt_12.value;
			c9.innerText = c9.textContent = document.form.txt_16.value;
			c10.innerText = c10.textContent = document.form.txt_20.value;
			c11.innerText = c11.textContent = document.form.txt_25.value;
			c12.innerText = c12.textContent = document.form.txt_28.value;
			c13.innerText = c13.textContent = document.form.txt_32.value;
            c14.innerText = c14.textContent = document.form.txt_ca_mt.value;
            c15.innerHTML = c15.textContent = "<input type='button' class='editbtnstyle' name='btn_mt_edit_" + add_row_s + "' id='btn_mt_edit_" + add_row_s + "' value='' style='width: 40px' onClick=editrow_mt(" + add_row_s + ",'n')><input type='button' class='delbtnstyle' style='width: 40px'  name='btn_mt_del_" + add_row_s + "'  id='btn_mt_del_" + add_row_s + "' value='' onClick=delrow_mt(" + add_row_s + ")>";
            var hide_values = "";
			hide_values = "<input type='hidden' value='" + c1.innerText + "' name='sno_mt" + add_row_s + "' id='sno_mt" + add_row_s + "' >";
            hide_values += "<input type='hidden' value='" + c2.innerText + "' name='txt_dec_wk_mt" + add_row_s + "' id='txt_dec_wk_mt" + add_row_s + "' >";
            hide_values += "<input type='hidden' value='" + c3.innerText + "' name='sel_dia_mt" + add_row_s + "' id='sel_dia_mt" + add_row_s + "' >";
            hide_values += "<input type='hidden' value='" + c4.innerText + "' name='txt_no_mt" + add_row_s + "' id='txt_no_mt" + add_row_s + "' >";
            hide_values += "<input type='hidden' value='" + c5.innerText + "' name='txt_l_mt" + add_row_s + "' id='txt_l_mt" + add_row_s + "' >";
            hide_values += "<input type='hidden' value='" + c6.innerText + "' name='txt_8" + add_row_s + "' id='txt_8" + add_row_s + "' >";
            hide_values += "<input type='hidden' value='" + c7.innerText + "' name='txt_10" + add_row_s + "' id='txt_10" + add_row_s + "' >";
            hide_values += "<input type='hidden' value='" + c8.innerText + "' name='txt_12" + add_row_s + "' id='txt_12" + add_row_s + "' >";
			hide_values += "<input type='hidden' value='" + c9.innerText + "' name='txt_16" + add_row_s + "' id='txt_16" + add_row_s + "' >";
			hide_values += "<input type='hidden' value='" + c10.innerText + "' name='txt_20" + add_row_s + "' id='txt_20" + add_row_s + "' >";
			hide_values += "<input type='hidden' value='" + c11.innerText + "' name='txt_25" + add_row_s + "' id='txt_25" + add_row_s + "' >";
			hide_values += "<input type='hidden' value='" + c12.innerText + "' name='txt_28" + add_row_s + "' id='txt_28" + add_row_s + "' >";
			hide_values += "<input type='hidden' value='" + c13.innerText + "' name='txt_32" + add_row_s + "' id='txt_32" + add_row_s + "' >";
			hide_values += "<input type='hidden' value='" + c14.innerText + "' name='txt_ca_mt" + add_row_s + "' id='txt_ca_mt" + add_row_s + "' >";
            document.getElementById("add_hidden_mt").innerHTML = document.getElementById("add_hidden_mt").innerHTML + hide_values; 

            if (document.getElementById("add_set_a2").value == "")
                document.getElementById("add_set_a2").value = add_row_s;
            else
                document.getElementById("add_set_a2").value = document.getElementById("add_set_a2").value + "." + add_row_s; 
				 document.form.sno_mt.value=parseInt(document.form.sno_mt.value)+1;
			calculate();	
            add_row_s++;
			
            cleartxt_mt();
       
        }
		function cleartxt_mt()
        {
			document.form.txt_dec_wk_mt.value="";
            document.form.sel_dia_mt.value="8";
           document.form.txt_no_mt.value="";
            document.form.txt_l_mt.value="";
            document.form.txt_8.value="";
            document.form.txt_10.value="";
			document.form.txt_12.value="";
			document.form.txt_16.value="";
			document.form.txt_20.value="";
			document.form.txt_25.value="";
			document.form.txt_28.value="";
			document.form.txt_32.value="";
        }
		
		function editrow_mt(rowno, update)
        {   
			 
            var total;
            var net_value;
            var edit_row = document.getElementById("mbookmetal").rows[rowno].cells;
			var sno=document.form.sno_mt.value;
			if(document.form.sno_hide.value=="")
			{
			document.form.sno_hide.value=document.form.sno_mt.value;
			}
            
			if (update == 'y') // transfer controls to table row
            {	
				
				edit_row[0].innerText = edit_row[0].textContent = document.form.sno_mt.value;
                edit_row[1].innerText = edit_row[1].textContent = document.form.txt_dec_wk_mt.value;
                edit_row[2].innerText = edit_row[2].textContent= document.form.sel_dia_mt.value;
                edit_row[3].innerText = edit_row[3].textContent= document.form.txt_no_mt.value;
                edit_row[4].innerText = edit_row[4].textContent= document.form.txt_l_mt.value;
                edit_row[5].innerText = edit_row[5].textContent= document.form.txt_8.value;
                edit_row[6].innerText = edit_row[6].textContent= document.form.txt_10.value;
                edit_row[7].innerText = edit_row[7].textContent= document.form.txt_12.value;
				edit_row[8].innerText = edit_row[8].textContent= document.form.txt_16.value;
				edit_row[9].innerText = edit_row[9].textContent= document.form.txt_20.value;
				edit_row[10].innerText = edit_row[10].textContent= document.form.txt_25.value;
				edit_row[11].innerText = edit_row[11].textContent= document.form.txt_28.value;
				edit_row[12].innerText = edit_row[12].textContent= document.form.txt_32.value;
				edit_row[13].innerText = edit_row[13].textContent= document.form.txt_ca_mt.value;
				
                document.getElementById("sno_mt" + rowno).value = edit_row[0].innerText = edit_row[0].textContent
                document.getElementById("txt_dec_wk_mt" + rowno).value = edit_row[1].innerText = edit_row[1].textContent
                document.getElementById("sel_dia_mt" + rowno).value = edit_row[2].innerText = edit_row[2].textContent
                document.getElementById("txt_no_mt" + rowno).value = edit_row[3].innerText = edit_row[3].textContent
                document.getElementById("txt_l_mt" + rowno).value = edit_row[4].innerText = edit_row[4].textContent
                document.getElementById("txt_8" + rowno).value = edit_row[5].innerText = edit_row[5].textContent
                document.getElementById("txt_10" + rowno).value = edit_row[6].innerText = edit_row[6].textContent
                document.getElementById("txt_12" + rowno).value = edit_row[7].innerText = edit_row[7].textContent
				document.getElementById("txt_16" + rowno).value = edit_row[8].innerText = edit_row[8].textContent
				document.getElementById("txt_20" + rowno).value = edit_row[9].innerText = edit_row[9].textContent
				document.getElementById("txt_25" + rowno).value = edit_row[10].innerText = edit_row[10].textContent
				document.getElementById("txt_28" + rowno).value = edit_row[11].innerText = edit_row[11].textContent
				document.getElementById("txt_32" + rowno).value = edit_row[12].innerText = edit_row[12].textContent
				document.getElementById("txt_ca_mt" + rowno).value = edit_row[13].innerText = edit_row[13].textContent
            }//update=='y'

            else  //transfer table row to controls
            {
				document.form.sno_mt.value = edit_row[0].innerText = edit_row[0].textContent
                document.form.txt_dec_wk_mt.value = edit_row[1].innerText = edit_row[1].textContent
                document.form.sel_dia_mt.value = edit_row[2].innerText = edit_row[2].textContent
                document.form.txt_no_mt.value = edit_row[3].innerText = edit_row[3].textContent
                document.form.txt_l_mt.value = edit_row[4].innerText = edit_row[4].textContent
                document.form.txt_8.value = edit_row[5].innerText = edit_row[5].textContent
                document.form.txt_10.value = edit_row[6].innerText = edit_row[6].textContent
                document.form.txt_12.value = edit_row[7].innerText = edit_row[7].textContent
				document.form.txt_16.value = edit_row[8].innerText = edit_row[8].textContent
				document.form.txt_20.value = edit_row[9].innerText = edit_row[9].textContent
				document.form.txt_25.value = edit_row[10].innerText = edit_row[10].textContent
				document.form.txt_28.value = edit_row[11].innerText = edit_row[11].textContent
				document.form.txt_32.value = edit_row[12].innerText = edit_row[12].textContent
				document.form.txt_ca_mt.value = edit_row[13].innerText = edit_row[13].textContent
            }

            if (prev_edit_row == 0)//first time edit the row
            {
                document.getElementById("row_" + rowno).style.color = "red";
                document.getElementById("btn_mt_edit_" + rowno).className = "cancelbtnstyle";
                document.getElementById("btn_mt_add").outerHTML = "<input type='button' class='updatebtnstyle' style='width: 40px' name='btn_mt_add' id='btn_mt_add' value='' onClick=\"editrow_mt(" + rowno + ",'y')\">";
                prev_edit_row = rowno;
            }
            else
            {	
				//set
				
                if (rowno == prev_edit_row)
                {
                    document.getElementById("row_" + prev_edit_row).style.color = "#770000";
                    document.getElementById("btn_mt_edit_" + rowno).className = "editbtnstyle";
                    document.getElementById("btn_mt_add").outerHTML = "<input type='button' class='addbtnstyle' style='width: 40px' name='btn_mt_add' id='btn_mt_add' value='' onClick='addrow_mt()'>";
                    prev_edit_row = 0;
					
                    cleartxt_mt();
                }

                else
                {	
					document.getElementById("sno_mt").value=document.getElementById("sno_hide").value;
					document.getElementById("sno_hide").value="";
                    document.getElementById("row_" + prev_edit_row).style.color = "#770000";
                    document.getElementById("btn_edit_" + prev_edit_row).className  = "editbtnstyle";
                    document.getElementById("row_" + rowno).style.color = "red";
                    document.getElementById("btn_mt_edit_" + rowno).className  = "cancelbtnstyle";
                    document.getElementById("btn_mt_add").outerHTML = "<input type='button' name='btn_mt_add' class='updatebtnstyle' style='width: 40px' id='btn_mt_add' value='' onClick=\"editrow_mt(" + rowno + ",'y')\">";
                    prev_edit_row = rowno;
                }document.getElementById("sno_mt").value = document.getElementById("sno_hide").value;document.getElementById("sno_hide").value="";
            }
        }
		
		
		function delrow_mt(rownum)
        {	var no=document.getElementById("sno_hide").value=document.getElementById("sno_mt").value;
            var src_row = (rownum + 1)
            var tar_row = rownum
            var noofadd = (add_row_s - 1)
			
            for (x = rownum; x < noofadd; x++)
            {	
				document.getElementById("sno_mt" + tar_row).value= document.getElementById("sno_mt" + src_row).value;
                document.getElementById("txt_dec_wk_mt" + tar_row).value = document.getElementById("txt_dec_wk_mt" + src_row).value
                document.getElementById("sel_dia_mt" + tar_row).value = document.getElementById("sel_dia_mt" + src_row).value
                document.getElementById("txt_no_mt" + tar_row).value = document.getElementById("txt_no_mt" + src_row).value
                document.getElementById("txt_l_mt" + tar_row).value = document.getElementById("txt_l_mt" + src_row).value
                document.getElementById("txt_8" + tar_row).value = document.getElementById("txt_8" + src_row).value
                document.getElementById("txt_10" + tar_row).value = document.getElementById("txt_10" + src_row).value;
                document.getElementById("txt_12" + tar_row).value = document.getElementById("txt_12" + src_row).value;
				document.getElementById("txt_16" + tar_row).value = document.getElementById("txt_16" + src_row).value;
                document.getElementById("txt_20" + tar_row).value = document.getElementById("txt_20" + src_row).value;
				document.getElementById("txt_25" + tar_row).value = document.getElementById("txt_25" + src_row).value;
                document.getElementById("txt_28" + tar_row).value = document.getElementById("txt_28" + src_row).value;
				document.getElementById("txt_10" + tar_row).value = document.getElementById("txt_32" + src_row).value;
                tar_row++;
                src_row++;
                var trow = document.getElementById("mbookmetal").rows[x].cells;
                var srow = document.getElementById("mbookmetal").rows[x + 1].cells;
				trow[0].innerText = trow[0].textContent = srow[0].innerText = srow[0].textContent 
                trow[1].innerText = trow[1].textContent  = srow[1].innerText = srow[1].textContent
                trow[2].innerText = trow[2].textContent  = srow[2].innerText = srow[2].textContent
                trow[3].innerText = trow[3].textContent  = srow[3].innerText = srow[3].textContent
                trow[4].innerText = trow[4].textContent  = srow[4].innerText = srow[4].textContent
                trow[5].innerText = trow[5].textContent  = srow[5].innerText = srow[5].textContent
                trow[6].innerText = trow[6].textContent  = srow[6].innerText = srow[6].textContent
                trow[7].innerText = trow[7].textContent  = srow[7].innerText = srow[7].textContent
				trow[8].innerText = trow[8].textContent  = srow[8].innerText = srow[8].textContent
                trow[9].innerText = trow[9].textContent  = srow[9].innerText = srow[9].textContent
                trow[10].innerText = trow[10].textContent  = srow[10].innerText = srow[10].textContent
				trow[11].innerText = trow[11].textContent  = srow[11].innerText = srow[11].textContent
                trow[12].innerText = trow[12].textContent  = srow[12].innerText = srow[12].textContent
                trow[13].innerText = trow[13].textContent  = srow[13].innerText = srow[13].textContent
				//trow[14].innerText = trow[14].textContent  = srow[14].innerText = srow[14].textContent
                            }
			document.getElementById("sno_mt" + tar_row).outerHTML = ""
            document.getElementById("txt_dec_wk_mt" + tar_row).outerHTML = ""
            document.getElementById("sel_dia_mt" + tar_row).outerHTML = ""
            document.getElementById("txt_no_mt" + tar_row).outerHTML = ""
            document.getElementById("txt_l_mt" + tar_row).outerHTML = ""
            document.getElementById("txt_8" + tar_row).outerHTML = ""
            document.getElementById("txt_10" + tar_row).outerHTML = ""
            document.getElementById("txt_12" + tar_row).outerHTML = ""
			document.getElementById("txt_16" + tar_row).outerHTML = ""
            document.getElementById("txt_20" + tar_row).outerHTML = ""
            document.getElementById("txt_25" + tar_row).outerHTML = ""
            document.getElementById("txt_28" + tar_row).outerHTML = ""
			document.getElementById("txt_32" + tar_row).outerHTML = ""
            document.getElementById('mbookmetal').deleteRow(noofadd)
            document.getElementById("add_set_a2").value = "";

            for (x = 2; x < noofadd; x++)
            {
                if (document.getElementById("add_set_a2").value == "")
                    {document.getElementById("add_set_a2").value = x;
					document.getElementById("sno_mt").value=x-1;
					}
                else
				{
                    document.getElementById("add_set_a2").value += ("." + x);
					document.getElementById("sno_mt").value=x-1;
				}
            }
		
            add_row_s = noofadd++; 
			for(i=1;i<no-1;i++)
			{
			var trow = document.getElementById("mbookmetal").rows[i+2].cells; 
			trow[0].innerText = trow[0].textContent = i;
			}
			document.getElementById("sno_hide").value="";
        }


    </script>
    <body class="page1">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="top">
<?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">
                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1">
                            <div class="title">Measurement Sheet Entry (M Book) </div>		
                          
                                <input type="hidden" name="txt_item_no" id="txt_item_no" value="">
                                <input type="hidden" name="txt_work_no" id="txt_work_no" value="">

                                <div class="container">
                                    <table width="1000"  bgcolor="#E8E8E8" border="1" cellpadding="0" cellspacing="0" align="center" >
                                        <tr><td width="4%">&nbsp;</td>
                                        </tr>		
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td  class="labelhead" width="14%" nowrap="nowrap">Work Order No.</td>
                                            <td class="label">
                                                <select id="workorderno" name="workorderno" onChange="func_item_no();cls()" class="textboxdisplay" style="width:505px;height:22px;" tabindex="7">
												<?php if($_GET['mbheaderid'] != "") { ?>
												<option value="<?php echo $sheetid; ?>"> <?php echo $work_order_no; ?> </option><?php } else { ?>
                                                        <option value=""> -- Select Work Order No -- </option>
                                                        <?php echo $objBind->BindWorkOrderNo(0); }?>
														
                                              </select>     
                                            </td>
                                            <td class="labelhead">Date</td>
                                            <td><input type="text" id="datepicker" name="datepicker" class="textboxdisplay" value="<?php echo date('d-m-Y');?>" readonly="" /></td>
                                        </tr>

                                        <tr><td>&nbsp;</td><td></td><td colspan="3" id="val_work" style="color:red"></tr>
<!--
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td  class="label">Name of the Work</td>
                                            <td  class="labeldisplay" colspan="3"><textarea class="textboxdisplay" style="width:700px;height:60px;" name="txt_wk_name" id="txt_wk_name" disabled="disabled"><?php //echo @mysql_result($rs_workname, 0, 'work_name'); ?></textarea></td>
                                        </tr>   <tr><td>&nbsp;</td></tr>
										<tr>
                                            <td>&nbsp;</td>
                                            <td  class="label">Short Notes</td>
                                            <td  class="labeldisplay" colspan="3"><input type="text" name='worknamenotes' id='worknamenotes' class="textboxdisplay" value="" size="90"></td>
											
										</tr>

                                        <tr><td>&nbsp;</td></tr>

                                        <tr>
                                            <td>&nbsp;</td>
                                            <td  class="label" nowrap="nowrap">Name of the contractor</td>
                                            <td  class="labeldisplay"><input type="text" name="txt_cont_name" id="txt_cont_name" class="textboxdisplay" size="35"/></td>

                                            <td  class="label">Technical Sanction</td>
                                            <td  class="labeldisplay"><input type="text" name="txt_tech_san" id="txt_tech_san" class="textboxdisplay" size="35"/></td>

                                        </tr>

                                        <tr><td>&nbsp;</td></tr>

                                        <tr>
                                            <td>&nbsp;</td>
                                            <td  class="label">Agreement No</td>
                                            <td  class="labeldisplay"><input type="text" name="txt_agmt_no" id="txt_agmt_no" class="textboxdisplay" size="35"/></td>

                                            <td  class="label" nowrap="nowrap">Running Account Bill No</td>
                                            <td  class="labeldisplay"><input type="text" name="txt_runn_bill_no" id="txt_runn_bill_no" class="textboxdisplay" size="35"/></td>

<td  class="label" nowrap="nowrap">Unit.</td>
<td class="labeldisplay"><input type="text" name="txt_unit" id="txt_unit" class="textboxdisplay" value="" size="10" disabled="disabled"></td>	
                                        </tr>-->

    <!--                                        <tr><td>&nbsp;</td></tr>-->


                                        <tr>
                                            <td>&nbsp;</td>
                                            <td  class="labelhead" nowrap="nowrap">Item No.</td>
                                            <td class="label"  colspan="3">
											<?php if($_GET['mbheaderid'] != "") { ?>
												<select name="itemno" id="itemno" class="textboxdisplay" style="width:120px;height:22px;" tabindex="7">
                                                    <option value="<?php echo $divid; ?>"><?php echo $divname; ?></option>
                                                </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
											<?php } else {?>
                                                <select onBlur="display();" name="itemno" id="itemno" class="textboxdisplay" onChange="func_subitem_no();cls();getitem_desc(this);" style="width:120px;height:22px;" tabindex="7">
                                                    <option value="0">--Item No.--</option>
                                                </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
											<?php } ?>
                                           <!-- &nbsp;&nbsp;&nbsp;&nbsp;Sub Item No.-->
                                            <?php if($_GET['mbheaderid'] != "") { ?>
                                                <select name="subitemno" id="subitemno" class="textboxdisplay" style="width:150px;height:22px;">
                                                    <option value="<?php echo $subdivid; ?>"><?php echo $subdivname; ?></option>
                                                </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                           <?php } else {?>
										   		<select onBlur="display();" name="subitemno" id="subitemno" class="textboxdisplay" style="width:150px;height:22px;" onChange="find_desc(this); find_subsubitem(this);">
                                                    <option value="0">--Sub Item No.--</option>
                                                </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										   <?php } ?>
										   <?php if($_GET['mbheaderid'] != "") { ?>
                                                <select name="subsubitemno" id="subsubitemno" class="textboxdisplay" style="width:150px;height:22px;">
                                                    <option value="<?php echo $subsubdivid; ?>"><?php echo $subsubdivname; ?></option>
                                                </select>
											<?php } else {?>
												<select onBlur="display();" name="subsubitemno" id="subsubitemno" class="textboxdisplay" style="width:150px;height:22px;"  onChange="find_desc(this);">
                                                    <option value="0">-Sub Sub Item No-</option>
                                                </select>
											<?php } ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>&nbsp;</td>
                                            <td></td>
                                            <td colspan="2" id="val_item" style="color:red"></td>
                                            <td id="val_sub" style="color:red"></td>
                                        </tr>

                    
					<tr>
                                            <td>&nbsp;</td>
                                            <td  class="labelhead">Short Notes</td>
                                            <td  class="labeldisplay">
                                               <!-- <input type="text" name='descriptionnotes' id='descriptionnotes' class="textboxdisplay" value="" size="55"/> -->
                                                <textarea name="descriptionnotes" id="descriptionnotes" class="textboxdisplay" rows="3" cols="79"><?php if($_GET['mbheaderid'] != "") { echo $shortnotes; } ?></textarea>
                                            </td>
                                            <td class="labelhead">Unit</td> 
                                            <td width="29%">
                                                <input type="text" name="remarks" value="<?php if($_GET['mbheaderid'] != "") { echo $remarks; } else { echo ""; }?>" id="remarks" class="textboxdisplay" size="10" readonly="" />
                                                <span id="label_unit" style="display:none;"></span>
                                            </td>
											
					</tr>
                                        <tr><td colspan="5">&nbsp;<?php if($_GET['mbheaderid'] != "") { ?> <input type="hidden" id="txt_mbdetailid" name="txt_mbdetailid" value="<?php echo $_GET['mbdetailid']; ?>" /> <?php } ?></td></tr>
				
                		                        <tr>

                                            <td colspan="5" style="height: 227px">
																		
		                               <div style="width:97%; overflow-x:hidden; overflow-y: auto;" id="table2" class="hide">
									    <div style="height:223px; width:96%;">
<table style="width: 940px" id="mbookmetal">
    <tr>
	<td style="border-left: thin solid grey; border-right: thin solid grey; border-top: thin solid grey; width: 60px; color:#003399; border-bottom-style: none; border-bottom-color: inherit; border-bottom-width: medium;" class="labelsmall" align="center" >Sno</td>
		  <td style="width: 505px; color:#003399;border:thin grey solid" class="labelsmall" align="center">Description of Work</td>
		  <td style="width: 39px; color:#003399;border:thin grey solid" class="labelsmall" align="center">Dia of rod</td>
		  <td style="width: 48px; color:#003399;border:thin grey solid" class="labelsmall" align="center">Nos</td>
		  <td style="width: 55px; color:#003399;border:thin grey solid" class="labelsmall" align="center">Length in metre</td>
		  <td colspan="9" class="labelsmall" style="color:#003399;border:thin grey solid" align="center">Total length in metre</td>
    </tr>
    <tr>
                  <td style="border-left: thin solid grey; border-right: thin solid grey; border-bottom: thin solid grey; width: 60px; color:#003399; border-top-style: none; border-top-color: inherit; border-top-width: medium;">&nbsp;
                  </td>
		  <td style="border-left: thin solid grey; border-right: thin solid grey; border-bottom: thin solid grey; width: 505px; color:#003399; border-top-style: none; border-top-color: inherit; border-top-width: medium;" >&nbsp;
		  </td>
		  <td style="border-left: thin solid grey; border-right: thin solid grey; border-bottom: thin solid grey; width: 39px; color:#003399; border-top-style: none; border-top-color: inherit; border-top-width: medium;">&nbsp;
		  </td>
		  <td style="border-left: thin solid grey; border-right: thin solid grey; border-bottom: thin solid grey; width: 48px; color:#003399; border-top-style: none; border-top-color: inherit; border-top-width: medium;">&nbsp;
		  </td>
		  <td style="border-left: thin solid grey; border-right: thin solid grey; border-bottom: thin solid grey; width: 55px; color:#003399; border-top-style: none; border-top-color: inherit; border-top-width: medium;">&nbsp;
		  </td>
		  <td class="labelsmall" style="color:#003399;border:thin grey solid" align="center">8</td>
		  <td style="width: 27px; color:#003399;border:thin grey solid" class="labelsmall" align="center">10</td>
		  <td style="width: 26px; color:#003399;border:thin grey solid" class="labelsmall" align="center">12</td>
		  <td class="labelsmall" style="color:#003399;border:thin grey solid" align="center">16</td>
		  <td style="width: 35px; color:#003399;border:thin grey solid" class="labelsmall" align="center">20</td>
		  <td class="labelsmall" style="color:#003399;border:thin grey solid" align="center">25</td>
		  <td style="width: 2px; color:#003399;border:thin grey solid" class="labelsmall" align="center">28</td>
		  <td style="width: 33px; color:#003399;border:thin grey solid" class="labelsmall" align="center">32</td>
		  <td style="width: 33px; color:#003399;border:thin grey solid" class="labelsmall">&nbsp;
		  </td>
    </tr>
    <tr>
		<td style="width: 60px; border:thin grey solid">
	          <input name="sno_mt" id="sno_mt" value="1" type="text" style="width: 32px;"  class="textboxdisplay" readonly="" /></td>
		<td style="width: 505px; color:#003399;border:thin grey solid">
		    <input name="txt_dec_wk_mt" type="text" style="width: 245px" class="textboxdisplay" value="<?php if($_GET['mbheaderid'] != "") { echo $descwork; } else { echo ""; } ?>" /></td>
		<td style="width: 39px; color:#003399;border:thin grey solid">
		<select style="width: 53px" class="textboxdisplay" name="sel_dia_mt" onBlur="calculate();">
			<?php if($_GET['mbheaderid'] != ""){ ?><option value="<?php echo $measurement_dia; ?>"><?php echo $measurement_dia; } ?></option>
		    <option value="8">8</option>
		    <option value="10">10</option>
		    <option value="12">12</option>
		    <option value="16">16</option>
		    <option value="20">20</option>
		    <option value="25">25</option>
		    <option value="28">28</option>
		    <option value="32">32</option>
		</select></td>
		<td style="width: 48px; color:#003399;border:thin grey solid">
                      <?php if($_GET['mbheaderid'] != "") { ?>
		    <input class="textboxdisplay" name="txt_no_mt" type="text" style="width: 51px" size="20" onBlur="calculate();" onKeyPress="return isNumber(event)" value="<?php echo $measurement_no; ?> " />
                  <?php } else { ?>
		    <input class="textboxdisplay" name="txt_no_mt" type="text" style="width: 51px" size="20" onBlur="calculate();" onKeyPress="return isNumber(event)" />
                  <?php } ?>
                </td>
		  <td style="width: 55px; color:#003399;border:thin grey solid">
                    <?php if($_GET['mbheaderid'] != "") { ?>
		    <input class="textboxdisplay" name="txt_l_mt" type="text" value="<?php echo $measurement_l; ?> " style="width: 50px" onBlur="calculate();" onKeyPress="return isNumber(event)" /></td>
                    <?php } else { ?>
                  <input class="textboxdisplay" name="txt_l_mt" type="text" style="width: 50px" onBlur="calculate();" onKeyPress="return isNumber(event)" /></td>
                    <?php } ?>
		  <td style="color:#003399;border:thin grey solid">
		    <input class="textboxdisplay" name="txt_8" type="text" style="width: 56px" readonly="" /></td>
		  <td style="width: 27px; color:#003399;border:thin grey solid">
		    <input class="textboxdisplay" name="txt_10" type="text" style="width: 50px"  readonly=""/></td>
		  <td style="width: 26px; color:#003399;border:thin grey solid">
		    <input class="textboxdisplay" name="txt_12" type="text" style="width: 50px" readonly="" /></td>
		  <td style="color:#003399;border:thin grey solid">
		    <input class="textboxdisplay" name="txt_16" type="text" style="width: 50px" readonly="" /></td>
		  <td style="width: 35px; color:#003399;border:thin grey solid">
		    <input class="textboxdisplay" name="txt_20" type="text" style="width: 50px" readonly="" /></td>
		  <td style="color:#003399;border:thin grey solid">
		    <input class="textboxdisplay" name="txt_25" type="text" style="width: 50px" readonly="" /></td>
		  <td style="width: 2px; color:#003399;border:thin grey solid">
		    <input class="textboxdisplay" name="txt_28" type="text" style="width: 49px" readonly=""/></td>
		  <td style="width: 33px;color:#003399;border:thin grey solid">
		    <input class="textboxdisplay" name="txt_32" type="text" style="width: 50px" readonly="" /></td>
		  <td style="width: 26px; color:#003399;border:thin grey solid">
		  <?php if($_GET['mbheaderid'] == "") { ?>
		  	<input type="button" class='addbtnstyle' name="btn_mt_add"   id="btn_mt_add" value="" onClick="addrow_mt()" style="width: 40px"/>
			<?php } else { ?>
			<input type="button" disabled="disabled" name="btn_mt_add"   id="btn_mt_add" value="ADD" onClick="addrow_mt()" style="width: 40px"/>
			<?php } ?>
			</td>
    </tr>
    <tr>
		                                     
		                                     <td style="width: 60px"><span id="add_hidden_mt"></span></td>
                                                        <input type="hidden" value="" name="add_set_a2" id="add_set_a2"/>
                                                        <?php if($_GET['mbheaderid'] == "") { ?>
							<input type="hidden" name="txt_ca_mt" id="txt_ca_mt" />	
                                                        <?php }  else { ?>
                                                        <input type="hidden" name="txt_ca_mt" id="txt_ca_mt" value = "<?php echo $measurement_contentarea; ?>"/>
                                                        <?php } ?>
    </tr>
</table>
</div>
</div>
									   
<div style="width:96%; overflow-x:hidden; overflow-y: auto;" id="table1">
<div style="height:223px; width:96%;">
<table border="1" cellpadding="0" cellspacing="0" align="center" id="mbookdetail" style="width: 938px; height: 116px;">
							
    <tr style="border:thin solid">
														<td class="labelsmall" style="width: 2%;border:thin grey solid"></td>
                                                        <td align="center" class="labelsmall" style="color:#003399;width: 170px;border:thin grey solid; border-bottom:none">Description of work</td>
                                                        <td  align="center" class="labelsmall" colspan="5" style="color:#003399;border:thin grey solid">Measurements Upto Date</td>
                                                        <td style="border:thin grey solid; border-left:hidden"></td>
                                                        <!--<td width="11%" rowspan="2"  align="center" class="labelsmall">&nbsp;</td>-->
    </tr>
    <tr style="border:thin grey solid">
														<td style="height: 32px; width: 2%; border:thin grey solid;color:#003399" class="labelsmall">S.no</td>
														<td style="width: 100px; height: 32px;border:thin grey solid"></td>
                                                        <td align="center" class="labelsmall" style="height: 32px; width: 30px;border:thin grey solid;color:#003399">No.</td>
                                                        <td align="center" class="labelsmall" style="height: 32px; width: 50px;border:thin grey solid;color:#003399">L.</td>
                                                        <td align="center" class="labelsmall" style="height: 32px; width: 70px;border:thin grey solid;color:#003399">B.</td>
                                                        <td align="center" class="labelsmall" style="height: 32px; width: 120px;border:thin grey solid;color:#003399">D.</td>
                                                        <td align="left" style="width: 70px; height: 32px;border:thin grey solid;color:#003399" class="labelsmall">Contents or Area</td>
                                                        <td style="border:thin grey solid"></td>
    </tr>
													
    <tr>
													  <td style="width: 2% ;border:thin grey solid">
													  <input type="text" name="sno" id="sno" class="textboxdisplay" size="4" readonly="" value="1" style="width: 26px" /></td>
                                                        <!--<td  style="display: none;">--><input type="hidden" name="txt_boq" id="txt_boq" class="textboxdisplay" size="90" readonly=""/><!--</td>-->
                                                      <td style="width: 100px;border:thin grey solid">
							<input type="text" name="txt_dec_wk" id="txt_dec_wk" style="word-break:break-all;width: 300px;" class="textboxdisplay" value="<?php if($_GET['mbheaderid'] != "") { echo $descwork; } else { echo ""; } ?> " size="53"/></td>
                                                      <td style="width: 30px;border:thin grey solid">
                                                       <?php  if($_GET['mbheaderid'] != "") { ?>
							<input type="text" name="txt_no" id="txt_no" class="textboxdisplay" size="10" onBlur="contentorarea()" style="width: 50px" value="<?php echo $measurement_no; ?> " onKeyPress="return isNumber(event)" /></td>
                                                      <td style="width: 50px;border:thin grey solid">
							<input type="text" name="txt_l" id="txt_l" class="textboxdisplay" size="10" onBlur="contentorarea()" style="width: 109px" value="<?php echo $measurement_l; ?> " onKeyPress="return isNumber(event)" /></td>
                                                      <td style="width: 70px;border:thin grey solid">
							<input type="text" name="txt_b" id="txt_b" class="textboxdisplay" size="10" onBlur="contentorarea()" style="width: 109px" value="<?php echo $measurement_b; ?> " onKeyPress="return isNumber(event)" /></td>
                                                      <td style="width: 200px;border:thin grey solid">
							<input type="text" name="txt_d" id="txt_d" class="textboxdisplay" size="10" onBlur="contentorarea()" style="width: 109px" value="<?php echo $measurement_d; ?> " onKeyPress="return isNumber(event)" />dv
                                                        <select name="cmb_depthunit">
                                                            <option value="0">-Unit-</option>
                                                            <option value="Cum">Cum</option>
                                                            <option value="KG">KG</option>
                                                            <option value="MT">MT</option>
                                                            <option value="Sqm">Sqm</option>
                                                        </select>
                                                      </td>
                                                      <td style="width: 95px;border:thin grey solid">
							<input type="text" name="txt_ca" id="txt_ca" class="textboxdisplay" size="10" readonly="" value="<?php echo $measurement_contentarea; ?> " style="width: 92px"/></td>
                                                      <?php } else { ?>
                                                      <input type="text" name="txt_no" id="txt_no" class="textboxdisplay" size="10" onBlur="contentorarea()" style="width: 50px" onKeyPress="return isNumber(event)" /></td>
                                                      <td style="width: 50px;border:thin grey solid">
							<input type="text" name="txt_l" id="txt_l" class="textboxdisplay" size="10" onBlur="contentorarea()" style="width: 109px" onKeyPress="return isNumber(event)" /></td>
                                                      <td style="width: 70px;border:thin grey solid">
							<input type="text" name="txt_b" id="txt_b" class="textboxdisplay" size="10" onBlur="contentorarea()" style="width: 109px" onKeyPress="return isNumber(event)" /></td>
                                                      <td style="width: 200px;border:thin grey solid">
							<input type="text" name="txt_d" id="txt_d" class="textboxdisplay" size="10" onBlur="contentorarea()" style="width: 109px" onKeyPress="return isNumber(event)" />
                                                        <select name="cmb_depthunit">
                                                            <option value="0">-Unit-</option>
                                                            <option value="Cum">Cum</option>
                                                            <option value="KG">KG</option>
                                                            <option value="MT">MT</option>
                                                            <option value="Sqm">Sqm</option>
                                                        </select>
                                                      </td>
                                                      <td style="width: 95px;border:thin grey solid">
							<input type="text" name="txt_ca" id="txt_ca" class="textboxdisplay" size="10" readonly="" style="width: 92px"/></td>
                                                       <?php } ?>
                                                        
                                                        <td width="3%" style="border:thin grey solid">
														<?php if($_GET['mbheaderid'] == "") { ?>
                                                         <input type="button" class="addbtnstyle" name="btn_add"   id="btn_add" value="" onClick="addrow()" style="width: 40px"/>
														<?php } else { ?>
														<input type="button" class="" name="btn_add"   id="btn_add" value="ADD" disabled="disabled" onClick="addrow()" style="width: 40px"/>
														<?php } ?>
															</td>
    </tr>
    <tr>
                                                            
                                                          <td><span id="add_hidden"></span></td>
                                                            <input type="hidden" value="" name="add_set_a1" id="add_set_a1"/>	
                                                     
    </tr>
												
</table>
</div>
</div>
                                            </td>
                                        </tr>

                                        <tr><td><input type="hidden" value="<?php if($_GET['mbheaderid'] != "") { echo $measure_type; } else { echo ""; } ?>" name="txt_measure_type" id="txt_measure_type"/></td></tr>
										 <tr>
                                            <td colspan="5">
											
                                        <center>
                                            <input type="hidden" class="text" name="submit" value="true" />
											<input type="hidden"  id="sno_hide" name="sno_hide">
											<!--<button type="button" class="btn" id="submit" data-type="submit" value=" Submit ">Submit</button>-->
											<?php if($_GET['mbheaderid'] == "") { ?>
                                            <input type="image" src="Buttons/submit.png" onMouseOver="this.src='Buttons/submit_hover.png';" onMouseOut="this.src='Buttons/submit.png';" class="btn" name="submit" value=" Submit " id="submit"/>	
											<?php } else { ?>
											<input type="image" src="Buttons/Update_Normal.png" onMouseOver="this.src='Buttons/Update_Over.png';" onMouseOut="this.src='Buttons/Update_Normal.png';" class="btn" name="update" value=" Submit " />	&nbsp;&nbsp;
                                                                                        <!--<input type="image" src="Buttons/Delete_Normal.png" onMouseOver="this.src='Buttons/Delete_Over.png';" onMouseOut="this.src='Buttons/Delete_Normal.png';" class="btn" name="delete" value=" Submit " onclick="delete_entry();" />-->	
											<?php } ?>
                                        </center>
                                        </td>
                                        </tr>
										 
                                  </table>
                            
                         
                            <div class="col2"><?php if ($msg != '') {
                                                        echo $msg;
                                                    } ?></div>
                                </div>
                        </blockquote>
                    </div>

                </div>
            </div>
    </form>
            <!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
              <script>
    $(function() { 
        
        $("#txt_no").keyup(function(){
            var numb = $("#txt_no").val();
            var i=""; var splitval = "";
            var splitval = numb.split("");
            for(i=1;i<splitval.length;i++)
            {
                if(numb.charAt(i) == "-")
                {
                    alert("invalid input");
                    document.form.txt_no.focus();
                    return false;
                }

            }
        });
        
		var measure_type = "<?php echo $measure_type; ?>";
		var mbheaderid = "<?php echo $_GET['mbheaderid']; ?>";
		var measurement_contentarea = "<?php echo $measurement_contentarea; ?>";
		var measurement_dia = "<?php echo $measurement_dia; ?>";
		if(mbheaderid != "")
		{ 
			if(measure_type=="s")
			{   
				document.getElementById("table1").className = "hide";
				document.getElementById("table2").className = "";
				if(measurement_dia != "")
				{
					if(measurement_dia == 8){ document.form.txt_20.value = measurement_contentarea; }
					if(measurement_dia == 10){ document.form.txt_20.value = measurement_contentarea; }
					if(measurement_dia == 12){ document.form.txt_20.value = measurement_contentarea; }
					if(measurement_dia == 16){ document.form.txt_20.value = measurement_contentarea; }
					if(measurement_dia == 20){ document.form.txt_20.value = measurement_contentarea; }
					if(measurement_dia == 25){ document.form.txt_20.value = measurement_contentarea; }
					if(measurement_dia == 28){ document.form.txt_20.value = measurement_contentarea; }
					if(measurement_dia == 32){ document.form.txt_20.value = measurement_contentarea; }
				}
			}
			else
			{
				document.getElementById("table1").className = "";
				document.getElementById("table2").className = "hide";
			}

		}
		 $( "#datepicker" ).datepicker({
      changeMonth: true,
      changeYear: true,
	   dateFormat: "dd-mm-yy",
	   maxDate: new Date,
	   defaultDate: new Date,
    });	
	
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
					var a="Please select the item number";
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
				if($("#subitemno").val()== "x"){ 
					var a="Please select the sub item number";
					$('#val_sub').text(a);
					event.returnValue = false;//for ie
					event.preventDefault();//for chrome
					//return false;//for firefox
					}
				else{
				var a="";
				$('#val_sub').text(a);		
				}
			}
             if(mbheaderid == "")
             {
		$.fn.validaterow = function(event) {	
			if($("#txt_measure_type").val() != "s")
				{
					if($("#add_set_a1").val()=="" || $("#add_set_a1").val()==2 ){ 
						//var a="Please select the sub item number";
						//$('#val_sub').text(a);
						alert("enter atleast one row");
						event.returnValue = false;//for ie
						event.preventDefault();//for chrome
						}                                           
				}
				
				else
				{
				if($("#add_set_a2").val()=="" || $("#add_set_a2").val()==2 ){ 
						//var a="Please select the sub item number";
						//$('#val_sub').text(a);
						alert("enter atleast one row");
						event.returnValue = false;//for ie
						event.preventDefault();//for chrome
						}      
				} 
		 }
             }
		$("#top").submit(function(event){
            $(this).validateitemno(event);
			$(this).validatesubitemno(event);
			$(this).validateworkorder(event);
			$(this).validaterow(event);
         });
		 
		$("#workorderno").change(function(event){
           $(this).validateworkorder(event);
         });
     	 $("#subitemno").change(function(event){
           $(this).validatesubitemno(event);
         });
		 $("#itemno").change(function(event){
           $(this).validateitemno(event);
         });
	  
	  });
			 
		
		

	
           </script>
		      
    
<head>
<style>
.hide
{
height:0px; width:98%; visibility:hidden; line-height:0px; font-size:0px;
}
.addbtnstyle
{
   background: url('Buttons/add.png') no-repeat;
   
}
.delbtnstyle
{
    background: url('Buttons/delete.png') no-repeat;
}
.editbtnstyle
{
   background: url('Buttons/edit.png') no-repeat; 
}
.cancelbtnstyle
{
   background: url('Buttons/cancel.png') no-repeat; 
}
.updatebtnstyle
{
   background: url('Buttons/update.png') no-repeat; 
}
</style>
</head></body>
</html>