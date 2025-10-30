<?php
session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
checkUser();
$msg = '';
$Allotid = $_GET['allotid'];

if(isset($_POST['Submit'])){
	$sheetid  = chop($_POST['workorderno']);
	$GenCheck = trim($_POST['radbookGen']);
	$StlCheck = trim($_POST['radbookStl']);
	$AbsCheck = trim($_POST['radbookAbs']);
	$EscCheck = trim($_POST['radbookEsc']);
	$userid	  = chop($_SESSION['userid']);
	$MBMode   = trim($_POST['radbookMode']);
	// MBMode is only for Single mbook concept. Alloted Mbook will be treated as General MBook. So MBMode mentioned only in General MBook insert query.
	
	
	$TempG1 = 0; $TempG2 = 0; $TempS1 = 0; $TempS2 = 0; $TempA1 = 0; $TempA2 = 0; $TempE1 = 0; $TempE2 = 0; $Error = 0;
	if($GenCheck != ''){
		if($GenCheck == 'S'){
			$GenStart 	= $_POST['mbokstartGen'];
			$GenEnd 	= $_POST['mbokendGen'];
			$TempG1 = $GenEnd - $GenStart + 1;
			for($Mbno = $GenStart; $Mbno <= $GenEnd; $Mbno++){
				if($Mbno != ''){
					$InsertQuery 	= "insert into agreementmbookallotment set sheetid='$sheetid', totalpages='100', mbno='$Mbno', active='1', flag='1', userid='$userid', createddate=NOW(), mbooktype='G', mbookmode = '$MBMode'";
					$InsertSql 		= mysql_query($InsertQuery);
					if($InsertSql == true){ $TempG2++; }
				}else{
					$TempG1 = $TempG1 - 1;
				}
			}
		}
		if($GenCheck == 'NS'){
			$MBList 	= $_POST['mbokGen'];
			$TempG1 = count($MBList);
			for($x1 = 0; $x1 < count($MBList); $x1++){
				$Mbno 	= $MBList[$x1];
				if($Mbno != ''){
					$InsertQuery 	= "insert into agreementmbookallotment set sheetid='$sheetid', totalpages='100', mbno='$Mbno', active='1', flag='1', userid='$userid', createddate=NOW(), mbooktype='G', mbookmode = '$MBMode'";
					$InsertSql 		= mysql_query($InsertQuery);
					if($InsertSql == true){ $TempG2++; }
				}else{
					$TempG1 = $TempG1 - 1;
				}
			}
		}
	}
	if($TempG1 != $TempG2){ $Error++; }
	
	if($StlCheck != ''){
		if($StlCheck == 'S'){
			$StlStart 	= $_POST['mbokstartStl'];
			$StlEnd 	= $_POST['mbokendStl'];
			$TempS1 = $StlEnd - $StlStart + 1;
			for($Mbno = $StlStart; $Mbno <= $StlEnd; $Mbno++){
				if($Mbno != ''){
					$InsertQuery 	= "insert into agreementmbookallotment set sheetid='$sheetid', totalpages='100', mbno='$Mbno', active='1', flag='1', userid='$userid', createddate=NOW(), mbooktype='S'";
					$InsertSql 		= mysql_query($InsertQuery);
					if($InsertSql == true){ $TempS2++; }
				}else{
					$TempS1 = $TempS1 - 1;
				}
			}
		}
		if($StlCheck == 'NS'){
			$MBList 	= $_POST['mbokStl'];
			$TempS1 = count($MBList);
			for($x1 = 0; $x1 < count($MBList); $x1++){
				$Mbno 	= $MBList[$x1];
				if($Mbno != ''){
					$InsertQuery 	= "insert into agreementmbookallotment set sheetid='$sheetid', totalpages='100', mbno='$Mbno', active='1', flag='1', userid='$userid', createddate=NOW(), mbooktype='S'";
					$InsertSql 		= mysql_query($InsertQuery);
					if($InsertSql == true){ $TempS2++; }
				}else{
					$TempS1 = $TempS1 - 1;
				}
			}
		}
	}
	if($TempS1 != $TempS2){ $Error++; }
	
	if($AbsCheck != ''){
		if($AbsCheck == 'S'){
			$AbsStart 	= $_POST['mbokstartAbs'];
			$AbsEnd 	= $_POST['mbokendAbs'];
			$TempA1 	= $AbsEnd - $AbsStart + 1;
			for($Mbno = $AbsStart; $Mbno <= $AbsEnd; $Mbno++){
				if($Mbno != ''){
					$InsertQuery 	= "insert into agreementmbookallotment set sheetid='$sheetid', totalpages='100', mbno='$Mbno', active='1', flag='1', userid='$userid', createddate=NOW(), mbooktype='A'";
					$InsertSql 		= mysql_query($InsertQuery);
					if($InsertSql == true){ $TempA2++; }
				}else{
					$TempA1 = $TempA1 - 1;
				}
			}
		}
		if($AbsCheck == 'NS'){
			$MBList = $_POST['mbokAbs'];
			$TempA1 = count($MBList);
			for($x1 = 0; $x1 < count($MBList); $x1++){
				$Mbno 	= $MBList[$x1];
				if($Mbno != ''){
					$InsertQuery 	= "insert into agreementmbookallotment set sheetid='$sheetid', totalpages='100', mbno='$Mbno', active='1', flag='1', userid='$userid', createddate=NOW(), mbooktype='A'";
					$InsertSql 		= mysql_query($InsertQuery);
					if($InsertSql == true){ $TempA2++; }
				}else{
					$TempA1 = $TempA1 - 1;
				}
			}
		}
	}
	if($TempA1 != $TempA2){ $Error++; }
	
	if($EscCheck != ''){
		if($EscCheck == 'S'){
			$EscStart 	= $_POST['mbokstartEsc'];
			$EscEnd 	= $_POST['mbokendEsc'];
			$TempE1 	= $EscEnd - $EscStart + 1;
			for($Mbno = $EscStart; $Mbno <= $EscEnd; $Mbno++){
				if($Mbno != ''){
					$InsertQuery 	= "insert into agreementmbookallotment set sheetid='$sheetid', totalpages='100', mbno='$Mbno', active='1', flag='1', userid='$userid', createddate=NOW(), mbooktype='E'";
					$InsertSql 		= mysql_query($InsertQuery);
					if($InsertSql == true){ $TempE2++; }
				}else{
					$TempE1 = $TempE1 - 1;
				}
			}
		}
		if($EscCheck == 'NS'){
			$MBList 	= $_POST['mbokEsc'];
			$TempE1 = count($MBList);
			for($x1 = 0; $x1 < count($MBList); $x1++){
				$Mbno 	= $MBList[$x1];
				if($Mbno != ''){
					$InsertQuery 	= "insert into agreementmbookallotment set sheetid='$sheetid', totalpages='100', mbno='$Mbno', active='1', flag='1', userid='$userid', createddate=NOW(), mbooktype='E'";
					$InsertSql 		= mysql_query($InsertQuery);
					if($InsertSql == true){ $TempE2++; }
				}else{
					$TempE1 = $TempE1 - 1;
				}
			}
		}
	}
	if($TempE1 != $TempE2){ $Error++; }
	//if(($TempE1 == 0) && ($TempE2 == 0)){ $Error++; }
	if($Error == 0){
		$msg = "MBook Alloted Sucessfully for Work Order..!!";
		$success = 1;
		/*if($TempG2 > 0){
			UpdateWorkTransaction($sheetid,0,"W","General MBook issued to work","");
		}
		if($TempS2 > 0){
			UpdateWorkTransaction($sheetid,0,"W","Steel MBook issued to work","");
		}
		if($TempA2 > 0){
			UpdateWorkTransaction($sheetid,0,"W","Abstract MBook issued to work","");
		}
		if($TempE2 > 0){
			UpdateWorkTransaction($sheetid,0,"W","Escalation MBook issued to work","");
		}*/
		
	}else{
		$msg = "Error: Sorry Mbook not Alloted. Please try again..!";
	}
	//echo $Error." == ".$Error;
	//exit;
}	
	/*$singlebook=chop($_POST['bookno']);
	$userid=chop($_SESSION['userid']);
	$books=chop($_POST['radbook']);
	$mbook_type=chop($_POST['rad_mbooktype']);
	if($mbook_type == "GMB"){ $mbook_type = "G"; }
	if($mbook_type == "SMB"){ $mbook_type = "S"; }
	if($mbook_type == "AMB"){ $mbook_type = "A"; }
	if($mbook_type == "EMB"){ $mbook_type = "E"; }
	if($mbook_type == "CCMB"){ $mbook_type = "CC"; }
	if($mbook_type == "SCMB"){ $mbook_type = "SC"; }
	if($mbook_type == "EAMB"){ $mbook_type = "EA"; }
	if($books == 'S') 
	{
		$mbkstartno=chop($_POST['mbokstart']);
		$mbkendno=chop($_POST['mbokend']);
		for($no=$mbkstartno;$no<=$mbkendno;$no++)
		{
			$agreementmbkallot="insert into agreementmbookallotment set sheetid='$sheetid',totalpages='100',mbno='$no',active='1',flag='1',userid='$userid',createddate=NOW(),mbooktype='$mbook_type'";
			$rsagreementmbkallot=mysql_query($agreementmbkallot);
		}
	}
	else if($books == 'NS')
	{
		$rec = explode(".", $_POST['add_set_a1']);
		for ($c = 0; $c < count($rec); $c++) 
		{
			$x = $rec[$c];
			$no=chop($_POST['txtmbkno'.$x]);
		  
			$agreementmbkallot="insert into agreementmbookallotment set sheetid='$sheetid',totalpages='100',mbno='$no',active='1',flag='1',userid='$userid',createddate=NOW(),mbooktype='$mbook_type'";
			$rsagreementmbkallot=mysql_query($agreementmbkallot);
		}
	}

	if($rsagreementmbkallot == true) 
	{
		$msg = "Sucessfully MBook Alloted for Work Order..!!";
		$success = 1;
	} 
	else
	{
		$msg = "Error..!!";
	}
}*/

if($_GET['sheetid'] != ''){
	$SelectSheetQuery 	= "select * from sheet where sheet_id = '".$_GET['sheetid']."'";
	$SelectSheetSql 	= mysql_query($SelectSheetQuery);
	if($SelectSheetSql == true){
		if(mysql_num_rows($SelectSheetSql)>0){
			$SheetList 		= mysql_fetch_object($SelectSheetSql);
			$WorkOrderNo 	= $SheetList->work_order_no;
			$WorkName 		= $SheetList->work_name;
		}
	}
}
?>
<?php require_once "Header.html"; ?>
<script language="javascript" type="text/javascript" src="script/validfn.js"></script>
<script type="text/javascript">
	function workorderdetail()
    { 
    	var xmlHttp;
        var data;
        var i, j; //alert();
		//document.form.txt_workname.value = '';
		document.form.txt_workorder_no.value = ''; 
        if (window.XMLHttpRequest) // For Mozilla, Safari, ...
        {
        	xmlHttp = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) // For Internet Explorer
        {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        strURL = "find_worder_details.php?workorderno=" + document.form.workorderno.value;
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
                    	//document.form.txt_workname.value 		= name[3];
						document.form.txt_workorder_no.value 	= name[5];
                    }

                }
            }
        }
        xmlHttp.send(strURL);
	}

/*var add_row_s = 2;
var prev_edit_row = 0
function addrow()
{
	if(document.form.workorderno.value == "")
	{
		//alert("First Select Work Order No.");
		swal("First Select Work Order No.", "", "");
		document.form.workorderno.focus();
		return false;
	}
	if ( ( form.rad_mbooktype[0].checked == false ) && ( form.rad_mbooktype[1].checked == false ) && ( form.rad_mbooktype[2].checked == false ) && ( form.rad_mbooktype[3].checked == false ))// && (form.rad_mbooktype[4].checked == false) && (form.rad_mbooktype[5].checked == false) && (form.rad_mbooktype[6].checked == false))
	{
		//alert("Please Select MBook type ");
		swal("Please Select MBook type ", "", "");
		//document.form.workorderno.focus();
		return false;
	}
	x=alltrim(document.form.txtmbkno.value)
	if(x.length == 0)
	{
		//alert("Please Enter MBook No.");
		swal("Please Select MBook No. ", "", "");
		document.form.txtmbkno.value='';
		document.form.txtmbkno.focus();
		return false;
	}
	
	var new_row = document.getElementById("mbookdetail").insertRow(add_row_s);
	new_row.setAttribute("id", "row_" + add_row_s)
	new_row.className = "labelcenter labelhead";
	new_row.style.height = "26px";

	var c1 = new_row.insertCell(0);
	c1.align = "center";c1.style.border = "thin solid grey"; 
	var c2 = new_row.insertCell(1);
	c2.align = "center";c2.style.border = "thin solid grey"; 
	var c3 = new_row.insertCell(2);
	c3.align = "center";c3.style.border = "thin solid grey"; 

	c1.innerHTML = document.form.txtmbkno.value;
	c2.innerHTML = document.form.txtnopages.value;
	c3.innerHTML = "<input type='button' class='addbtnstyle' name='btn_edit_" + add_row_s + "' style='height:25px;' id='btn_edit_" + add_row_s + "'  value=' EDIT ' onClick=editrow(" + add_row_s + ",'n')><input type='button' class='delbtnstyle'  name='btn_del_" + add_row_s + "' style='height:25px;'  id='btn_del_" + add_row_s + "' value=' DEL ' onClick=delrow(" + add_row_s + ")>";
	var hide_values = "";
	hide_values = "<input type='hidden' value='" + c1.innerHTML + "' name='txtmbkno" + add_row_s + "' id='txtmbkno" + add_row_s + "' >";
	hide_values += "<input type='hidden' value='" + c2.innerHTML + "' name='txtnopages" + add_row_s + "' id='txtnopages" + add_row_s + "' >";
	document.getElementById("add_hidden").innerHTML = document.getElementById("add_hidden").innerHTML + hide_values;

	if (document.getElementById("add_set_a1").value == "")
		document.getElementById("add_set_a1").value = add_row_s;
	else
		document.getElementById("add_set_a1").value = document.getElementById("add_set_a1").value + "." + add_row_s;
	add_row_s++;
	document.getElementById("txtmbkno").value = "";
	//clear();
}

function editrow(rowno, update)
{
	var total;
	var net_value;
	var edit_row = document.getElementById("mbookdetail").rows[rowno].cells;

	if (update == 'y') // transfer controls to table row
	{
		edit_row[0].innerHTML = document.form.txtmbkno.value;
		edit_row[1].innerHTML = document.form.txtnopages.value;
		
		document.getElementById("txtmbkno" + rowno).value = edit_row[0].innerHTML
		document.getElementById("txtnopages" + rowno).value = edit_row[1].innerHTML
	}//update=='y'



	else  //transfer table row to controls
	{
		document.form.txtmbkno.value = edit_row[0].innerHTML
		document.form.txtnopages.value = edit_row[1].innerHTML
	}

	if (prev_edit_row == 0)//first time edit the row
	{
		document.getElementById("row_" + rowno).style.color = "red";
		document.getElementById("btn_edit_" + rowno).value = " EDIT ";
		document.getElementById("btn_add").outerHTML = "<input type='button' class='addbtnstyle' name='btn_add' id='btn_add' style='height:25px;' value=' OK ' onClick=\"editrow(" + rowno + ",'y')\">";
		prev_edit_row = rowno;
	}
	else
	{
		if (rowno == prev_edit_row)
		{
			document.getElementById("row_" + prev_edit_row).style.color = "#770000";
			document.getElementById("btn_edit_" + rowno).value = " EDIT ";
			document.getElementById("btn_add").outerHTML = "<input type='button' class='addbtnstyle' name='btn_add' id='btn_add' style=' height:25px;' value=' ADD ' onClick='addrow()'>";
			prev_edit_row = 0;
			document.getElementById("txtmbkno").value = "";
		}

		else
		{
			document.getElementById("row_" + prev_edit_row).style.color = "#770000";
			document.getElementById("btn_edit_" + prev_edit_row).value = "";

			document.getElementById("row_" + rowno).style.color = "red";
			document.getElementById("btn_edit_" + rowno).value = " EDIT ";
			document.getElementById("btn_add").outerHTML = "<input type='button' name='btn_add' class='addbtnstyle' id='btn_add' style=' height:25px;' value=' EDIT ' onClick=\"editrow(" + rowno + ",'y')\">";
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
		document.getElementById("txtmbkno" + tar_row).value = document.getElementById("txtmbkno" + src_row).value
		document.getElementById("txtnopages" + tar_row).value = document.getElementById("txtnopages" + src_row).value
		tar_row++;
		src_row++;
		var trow = document.getElementById("mbookdetail").rows[x].cells;
		var srow = document.getElementById("mbookdetail").rows[x + 1].cells;

		trow[0].innerText = srow[0].innerText
		trow[1].innerText = srow[1].innerText
	}

	document.getElementById("txtmbkno" + tar_row).outerHTML = ""
	document.getElementById("txtnopages" + tar_row).outerHTML = ""
	
	document.getElementById('mbookdetail').deleteRow(noofadd)
	document.getElementById("add_set_a1").value = "";

	for (x = 2; x < noofadd; x++)
	{
		if (document.getElementById("add_set_a1").value == "")
			document.getElementById("add_set_a1").value = x;
		else
			document.getElementById("add_set_a1").value += ("." + x);
	}
	add_row_s = noofadd++;
}*/
/*function clear(){ document.getElementById("txtmbkno").value = ""; document.getElementById("txtnopages").value = "";}

function func_book()
{
	if(document.form.radbook[0].checked == true)
	{
		document.getElementById("mbookstart").style.display="";
		document.getElementById("mbookend").style.display="";
		document.getElementById("singlebook").style.display='none';
		document.getElementById("multiplespace").style.display='';
	}
	else
	{	
		document.getElementById("mbookstart").style.display="none";
		document.getElementById("mbookend").style.display="none";
		document.getElementById("multiplespace").style.display='none';
		document.getElementById("singlebook").style.display='';
	}
}*/
		
/*function totalmbook()
{
 	var xmlHttp;
    var data;
	if(window.XMLHttpRequest) // For Mozilla, Safari, ...
	{
		xmlHttp = new XMLHttpRequest();
	}
	else if(window.ActiveXObject) // For Internet Explorer
	{ 
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	strURL="countmbookno.php?wkrorderno="+document.form.workorderno.value;	
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			
			if(data!='*')
			{	
				document.form.totmbkno.value=data;		
			}
		}
    }
	xmlHttp.send(strURL);			
 }*/	
 
 /*function CheckFirstChar(o) {
             var arr = ['A', 'B', 'C', 'D'];
             if (o.value.length > 0) {
                 for (var i = 0; i < arr.length; i++) {
                     if (o.value.charAt(0) == arr[i]) {
                        alert('Valid');
                         return true;
                   }
                    else {
                       alert('InValid');
                       return false;
                   }
                }
           }
       }*/
/*function findbookno()
{
	var arr = [0];
	var valstart = alltrim(document.form.mbokstart.value);
	var valend = alltrim(document.form.mbokend.value);
	var singlembookno=alltrim(document.form.txtmbkno.value);
	
	var start=valstart.length;
	if(start>0) {
	for (var i = 0; i < arr.length; i++) {
	if (valstart.charAt(0) == arr[i]) {
	//alert("Please Enter Valid Mbook Start No.");
	swal("Please Enter Valid Mbook Start No.", "", "");
	document.form.mbokstart.value=''
	document.form.mbokstart.focus();
	return false; } } } 
	
	var end=valend.length;
	if(end>0) {
	for (var i = 0; i < arr.length; i++) {
	if (valend.charAt(0) == arr[i]) {
	//alert("Please Enter Valid Mbook End No.");
	swal("Please Enter Valid Mbook End No.", "", "");
	document.form.mbokend.value='';
	document.form.mbokend.focus();
	return false; } } }
	
	var singlebook=singlembookno.length;
	if(singlebook>0) {
	for (var i = 0; i < arr.length; i++) {
	if (singlembookno.charAt(0) == arr[i]) {
	//alert("Please Enter Valid Mbook No.");
	swal("Please Enter Valid Mbook No.", "", "");
	document.form.txtmbkno.value='';
	document.form.txtmbkno.focus();
	return false; } } }
	
	
	
	
	
	
	
	if(start != 0 || end!=0 || singlebook!=0) {
	if(document.form.workorderno.value == "")
	{
		//alert("First Select Work Order No.");
		BootstrapDialog.alert("Please Select Work Order No");
		document.form.workorderno.focus();
		document.form.mbokstart.value='';
		document.form.mbokend.value=''
		document.form.txtmbkno.value=''
		return false;
	} }
	var totbookno=document.form.totmbkno.value;	
	var mbokstart=document.form.mbokstart.value;
	var mbokend=document.form.mbokend.value;
	var mbno=document.form.txtmbkno.value;
		bookno=totbookno.split('*');
                
                if(document.getElementById("radbooks").checked == true)
		{
			if(bookno!='')
			{
				for(i=0;i<bookno.length;i++)
				{	
					if(mbokstart==bookno[i])
					{
						//alert("Mbook No."+ " " +mbokstart+ " "+" already alloted. Please assign differnt MBook")
						swal("Mbook No."+" "+ mbokstart+ " "+"already alloted. Please assign different MBook.", "", "");
						document.form.mbokstart.value='';
						document.form.mbokstart.focus();
						return false;
					}
					else if(mbokend==bookno[i])
					{
						//alert("Mbook No."+ " " +mbokend+ " "+"already alloted. Please assign differnt MBook")
						swal("Mbook No."+" "+ mbokend+ " "+"already alloted. Please assign different MBook.", "", "");
						document.form.mbokend.value='';
						document.form.mbokend.focus();
						return false;
					}
				}
			}
		}
		else
		{
			if(mbno!='')
			{
				for(i=0;i<bookno.length;i++)
				{	
					if(mbno==bookno[i])
					{
						//alert("Mbook No."+ " " +mbno+ " "+"already alloted for this workorder no.")
						swal("Mbook No."+" "+ mbno+ " "+"already alloted. Please assign different MBook.", "", "");
						document.form.txtmbkno.value='';
						document.form.txtmbkno.focus();
						return false;
					}
				}
			}
		}
		check_mbookno()
}*/
 /*function check_mbookno()
 {
 		var startno=parseInt(document.form.mbokstart.value);
		var endno=parseInt(document.form.mbokend.value);
		
		if(endno<=startno)
		{
			//alert("Mbook End No. must greater than Mbook Start No.")
			swal("Mbook End No. must greater than Mbook Start No.", "", "");
			document.form.mbokend.value=''
			document.form.mbokend.focus();
			return false;
		}
}*/

/*function validation()
{
	if(document.form.workorderno.value=="")
	{
		BootstrapDialog.alert("Please Select Work Order No.");
		document.form.workorderno.focus();
		return false;
	}
	if((form.rad_mbooktype[0].checked == false) && (form.rad_mbooktype[1].checked == false) && (form.rad_mbooktype[2].checked == false) && (form.rad_mbooktype[3].checked == false))// && (form.rad_mbooktype[4].checked == false) && (form.rad_mbooktype[5].checked == false) && (form.rad_mbooktype[6].checked == false))
	{
		//alert("Please Select MBook type ");
		swal("Please Select MBook type", "", "");
		//document.form.workorderno.focus();
		return false;
	}
	if((document.getElementById("radbooks").checked == true) && (document.form.workorderno.value!=""))
	{
	 	x=alltrim(document.form.mbokstart.value)
	    if(x.length == 0)
	    {	
		 //alert("Please Enter the Mbook Start No.");
		 swal("Please Enter the Mbook Start No", "", "");
 		 document.form.mbokstart.value='';
		 document.form.mbokstart.focus();
		 return false;
	    }
		
		x=alltrim(document.form.mbokend.value)
	    if(x.length == 0)
	    {	
		 //alert("Please Enter the Mbook End No.");
		 swal("Please Enter the Mbook End No.", "", "");
 		 document.form.mbokend.value='';
		 document.form.mbokend.focus();
		 return false;
	    }
	}
	else
	{
            if(document.getElementById("radbookns").checked == true) {
		if(add_row_s<3)
		{
			//alert("Please Add Atleast One Row");
			swal("Please Add Atleast One Row", "", "");
			return false;
		} }
	}
}*/
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
function goBack()
{
	url = "MyView.php";
	window.location.replace(url);
}
</script>
<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</SCRIPT>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
            <div class="title">Work - Wise MBook Allotment</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto"> 
						<div align="right"><a href="AgreementMBookAllotmentEdit.php?edit=new">Over All View</a></div>
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <div class="container">
								<div class="row ">
									<div class="div2">&nbsp;</div>
									
									<div class="div8">
										<div class="row"><div class="div12" style="margin-top:0px;"><div class="row divhead" align="center">Work - Wise General / Steel / Abstract / Escalation MBook Allotment</div></div></div>
										<div class="row innerdiv">
											<div class="row">
												<div class="div4">
													<label for="lboxlabel">Work Short Name</label>
												</div>
												<div class="div8">
													<select id="workorderno" name="workorderno" class="tboxclass" onchange='workorderdetail();'>
														<option value="">--------------- Select ---------------</option>
														<?php echo $objBind->BindWorkOrderNo($_GET['sheetid']);?>
													</select>
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div4">
													<label for="lboxlabel">Work Order No.</label>
												</div>
												<div class="div8">
													<input type="text" name='txt_workorder_no' id='txt_workorder_no' class="tboxclass" readonly="" value="<?php if($_GET['sheetid'] != ''){ echo $WorkOrderNo; } ?>">
												</div>
											</div>
											<div class="row clearrow"></div>
											<div class="row">
												<div class="div4">
													<label for="lboxlabel">MBook Mode</label>
												</div>
												<div class="div8">
													<!--<textarea name='txt_workname' id='txt_workname' class="tboxclass" readonly="" rows="2"><?php if($_GET['sheetid'] != ''){ echo $WorkName; } ?></textarea>-->
													 <div class="div6" align="center">
														<div class="innerdiv">
														<div class="label lboxlabel" align="left">
															<input type="radio" name="radbookMode" id="SEPMB" value="SEPMB" data-type="SEPMB" class="MBMode" data-index="1"/>
															Seperate MBook
															<div style="color:#666666; font-size:11px">[Seperate MBook for General, Steel, Abstract..]</div>
														</div>
														</div>
													</div>
													<div class="div6" align="center">
														<div class="innerdiv">
														<div class="label lboxlabel" align="left">
															<input type="radio" name="radbookMode" id="SINMB" value="SINMB" data-type="SINMB" class="MBMode" data-index="1"/>
															Single MBook
															<br/> 
															<div style="color:#666666; font-size:11px">[Common MBook (General) for General, Steel, Abstract..]</div>
														</div>
														</div>
													</div>
													
												</div>
											</div>
											<div class="row clearrow"></div>
											
											<div class="row">
												<div class="div3" align="center">
													<div class="innerdiv2">
														<div class="row divhead" align="center">General</div>
														<div class="row innerdiv" align="center">
															<div class="boxdiv1 label boxtitle GenLabel" align="left">
																<input type="radio" name="radbookGen" id="GENS" value="S" data-type="GEN" class="RAD" onClick="func_book();" data-index="1"/>
																Sequential
															</div>
															<div class="boxdiv1 label one-mar-top SINMBMode hide GEN" align="left" id="GENSROW">
																<div class="row clearrow"></div>
																<div class="row">
																	<div class="div6 no-mar-top lboxlabel">&nbsp;Start No.</div>
																	<div class="div6 no-mar-top">
																		<input type="text" name="mbokstartGen" class="tboxsmclass mbno start1 GENS" id="mbokstartGen" value="" onBlur="findbookno()" onKeyPress=" return isNumber(event);"/>
																	</div>
																</div>
																<div class="row clearrow"></div>
																<div class="row">
																	<div class="div6 lboxlabel">&nbsp;End No.</div>
																	<div class="div6">
																		<input type="text" name="mbokendGen" class="tboxsmclass mbno end1 GENS" id="mbokendGen" value="" onBlur="findbookno()" onKeyPress=" return isNumber(event);"/>
																	</div>
																</div>
																<div class="row clearrow"></div>
															</div>
															<div class="boxdiv1 label boxtitle GenLabel" align="left">
																<input type="radio" name="radbookGen" id="GENNS" value="NS" data-type="GEN" class="RAD" onClick="func_book();" data-index="1"/>
																Non-Sequential
															</div>
															<div class="boxdiv1 one-mar-top SINMBMode hide GEN" align="left" id="GENNSROW">
																<div class="row" id="GENNSADD">
																	<div class="div10 no-mar-top">
																		<input type="text" name="mbokGen[]" id="MBNO1" class="tboxsmclass mbno GENNS" onKeyPress="return isNumber(event);">
																	</div>
																	<div class="div2 no-mar-top" align="center">
																		<i class="fa fa-plus-circle add" data-id="GENNS" data-type="mbokGen" style="font-size:25px; color:#109A60; cursor:pointer"></i>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="div3" align="center">
													<div class="innerdiv2">
														<div class="row divhead" align="center">Steel</div>
														<div class="row innerdiv" align="center">
															<div class="boxdiv1 label boxtitle StlLabel" align="left">
																<input type="radio" name="radbookStl" id="STLS" value="S" data-type="STL" class="RAD" onClick="func_book();" data-index="2"/>
																Sequential
															</div>
															<div class="boxdiv1 label one-mar-top SEPMBMode hide STL" align="left" id="STLSROW">
																<div class="row clearrow"></div>
																<div class="row">
																	<div class="div6 no-mar-top lboxlabel">&nbsp;Start No.</div>
																	<div class="div6 no-mar-top">
																		<input type="text" name="mbokstartStl" class="tboxsmclass mbno start2 STLS" id="mbokstartStl" value="" onBlur="findbookno()" onKeyPress=" return isNumber(event);"/>
																	</div>
																</div>
																<div class="row clearrow"></div>
																<div class="row">
																	<div class="div6 lboxlabel">&nbsp;End No.</div>
																	<div class="div6">
																		<input type="text" name="mbokendStl" class="tboxsmclass mbno end2 STLS" id="mbokendStl" value="" onBlur="findbookno()" onKeyPress=" return isNumber(event);"/>
																	</div>
																</div>
																<div class="row clearrow"></div>
															</div>
															<div class="boxdiv1 label boxtitle StlLabel" align="left">
																<input type="radio" name="radbookStl" id="STLNS" value="NS" data-type="STL" class="RAD" onClick="func_book();" data-index="2"/>
																Non-Sequential
															</div>
															<div class="boxdiv1 one-mar-top hide SEPMBMode STL" align="left" id="STLNSROW">
																<div class="row" id="STLNSADD">
																	<div class="div10 no-mar-top">
																		<input type="text" name="mbokStl[]" id="MBNO2" class="tboxsmclass mbno STLNS" onKeyPress=" return isNumber(event);">
																	</div>
																	<div class="div2 no-mar-top" align="center">
																		<i class="fa fa-plus-circle add" data-id="STLNS" data-type="mbokStl" style="font-size:25px; color:#109A60; cursor:pointer"></i>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="div3" align="center">
													<div class="innerdiv2">
														<div class="row divhead" align="center">Abstract</div>
														<div class="row innerdiv" align="center">
															<div class="boxdiv1 label boxtitle AbsLabel" align="left">
																<input type="radio" name="radbookAbs" id="ABSS" value="S" data-type="ABS" class="RAD" onClick="func_book();" data-index="3"/>
																Sequential
															</div>
															<div class="boxdiv1 label one-mar-top SEPMBMode hide ABS" align="left" id="ABSSROW">
																<div class="row clearrow"></div>
																<div class="row">
																	<div class="div6 no-mar-top lboxlabel">&nbsp;Start No.</div>
																	<div class="div6 no-mar-top">
																		<input type="text" name="mbokstartAbs" class="tboxsmclass mbno start3 ABSS" id="mbokstartAbs" value="" onBlur="findbookno()" onKeyPress="return isNumber(event);"/>
																	</div>
																</div>
																<div class="row clearrow"></div>
																<div class="row">
																	<div class="div6 lboxlabel">&nbsp;End No.</div>
																	<div class="div6">
																		<input type="text" name="mbokendAbs" class="tboxsmclass mbno end3 ABSS" id="mbokendAbs" value="" onBlur="findbookno()" onKeyPress="return isNumber(event);"/>
																	</div>
																</div>
																<div class="row clearrow"></div>
															</div>
															<div class="boxdiv1 label boxtitle AbsLabel" align="left">
																<input type="radio" name="radbookAbs" id="ABSNS" value="NS" data-type="ABS" class="RAD" onClick="func_book();" data-index="3"/>
																Non-Sequential
															</div>
															<div class="boxdiv1 one-mar-top hide SEPMBMode ABS" align="left" id="ABSNSROW">
																<div class="row" id="ABSNSADD">
																	<div class="div10 no-mar-top">
																		<input type="text" name="mbokAbs[]" id="MBNO3" class="tboxsmclass mbno ABSNS" onKeyPress=" return isNumber(event);">
																	</div>
																	<div class="div2 no-mar-top" align="center">
																		<i class="fa fa-plus-circle add" data-id="ABSNS" data-type="mbokAbs" style="font-size:25px; color:#109A60; cursor:pointer"></i>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="div3" align="center">
													<div class="innerdiv2">
														<div class="row divhead" align="center">Escalation</div>
														<div class="row innerdiv" align="center">
															<div class="boxdiv1 label boxtitle EscLabel" align="left">
																<input type="radio" name="radbookEsc" id="ESCS" value="S" data-type="ESC" class="RAD" onClick="func_book();" data-index="4"/>
																Sequential
															</div>
															<div class="boxdiv1 label one-mar-top SEPMBMode hide ESC" align="left" id="ESCSROW">
																<div class="row clearrow"></div>
																<div class="row">
																	<div class="div6 no-mar-top lboxlabel">&nbsp;Start No.</div>
																	<div class="div6 no-mar-top">
																		<input type="text" name="mbokstartEsc" class="tboxsmclass mbno start4 ESCS" id="mbokstartEsc" value="" onBlur="findbookno()" onKeyPress="return isNumber(event);"/>
																	</div>
																</div>
																<div class="row clearrow"></div>
																<div class="row">
																	<div class="div6 lboxlabel">&nbsp;End No.</div>
																	<div class="div6">
																		<input type="text" name="mbokendEsc" class="tboxsmclass mbno end4 ESCS" id="mbokendEsc" value="" onBlur="findbookno()" onKeyPress="return isNumber(event);"/>
																	</div>
																</div>
																<div class="row clearrow"></div>
															</div>
															<div class="boxdiv1 label boxtitle EscLabel" align="left">
																<input type="radio" name="radbookEsc" id="ESCNS" value="NS" data-type="ESC" class="RAD" onClick="func_book();" data-index="4"/>
																Non-Sequential
															</div>
															<div class="boxdiv1 one-mar-top SEPMBMode hide ESC" align="left" id="ESCNSROW">
																<div class="row" id="ESCNSADD">
																	<div class="div10 no-mar-top">
																		<input type="text" name="mbokEsc[]" id="MBNO4" class="tboxsmclass mbno ESCNS" onKeyPress=" return isNumber(event);">
																	</div>
																	<div class="div2 no-mar-top" align="center">
																		<i class="fa fa-plus-circle add" data-id="ESCNS" data-type="mbokEsc" style="font-size:25px; color:#109A60; cursor:pointer"></i>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											
										</div>
										
													
									</div>
									
								  	<div class="div2">&nbsp;</div>
								</div>
							</div>
							<div class="smediv"></div>
							<div class="row">
								<div class="div12" align="center">
									<input type="submit" class="backbutton" name="btn_view" id="btn_view" value="View"/>
									<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
									<?php if(in_array('MWOA', $_SESSION['ModuleRights'])){ ?>
									<input type="submit" data-type="submit" value=" Submit " name="Submit" id="Submit" onClick="return validation()"/>
									<?php } ?>
								</div>
							</div>
                        </form>
                    </blockquote>
                </div>
            </div>
        </div>
         <!--==============================footer=================================-->
		<?php include "footer/footer.html"; ?>
        <script>
		$(document).ready(function() {
			$("#workorderno").chosen();
			var msg = "<?php echo $msg; ?>";
			var success = "<?php echo $success; ?>";
			var titletext = "";
			document.querySelector('#top').onload = function(){
			if(msg != "")
			{
				if(success == 1)
				{
					BootstrapDialog.alert(msg);
				}
				else
				{
					BootstrapDialog.alert(msg);
				}
					
			}
			};
			$("#btn_view").click(function(e){ 
			    var sheetid 	= $('#workorderno').val();
				if(sheetid!==""){
					$(location).attr('href','AgreementMBookAllotmentEdit.php?sheetid='+sheetid);
				}else{
				    BootstrapDialog.alert('Please Select Work Name ');
					e.preventDefault();
					return false; 
				}
			});
			var max_fields      = 10; //maximum input boxes allowed
			var wrapper   		= $(".input_fields_wrap"); //Fields wrapper
			var add_button      = $(".add"); //Add button ID
			var del_button      = $(".delete");
			
			var x = 5; //initlal text box count
			$(add_button).click(function(e){ //on add input button click
				var appendrow = $(this).attr("data-id");
				var type = $(this).attr("data-type");
				e.preventDefault();
				//if(x < max_fields){ //max input box allowed
					 //text box increment
					$('#'+appendrow+"ADD").append('<div class="row"><div class="div10"><input type="text" name="'+type+'[]" id="MBNO'+x+'" class="tboxsmclass mbno '+appendrow+'" onKeyPress="return isNumber(event);"></div><div class="div2" align="center"><i class="fa fa-times-circle delete" style="font-size:25px; color:red; cursor:pointer"></i></div></div>'); //add input box
					x++;
				//}
			});
			
			$('body').on("click",".delete", function(e){ //user click on remove text
				e.preventDefault(); 
				$(this).closest('.row').remove(); x--;
			});
			
			
			$(".MBMode").click(function(event){
				var MBMode = $(this).val();
				$(".SEPMBMode").addClass("hide");
				$(".SINMBMode").addClass("hide");
				if(MBMode == "SINMB"){
					$('#GENS').attr('checked', false);
					$('#GENNS').attr('checked', false);
					$('#STLS').attr("disabled",true);
					$('#STLS').attr('checked', false);
					$('#STLNS').attr("disabled",true);
					$('#STLNS').attr('checked', false);
					$('#ABSS').attr("disabled",true);
					$('#ABSS').attr('checked', false);
					$('#ABSNS').attr("disabled",true);
					$('#ABSNS').attr('checked', false);
					$('#ESCS').attr("disabled",true);
					$('#ESCS').attr('checked', false);
					$('#ESCNS').attr("disabled",true);
					$('#ESCNS').attr('checked', false);
					$('.StlLabel').css('color', '#A4A6A7');
					$('.AbsLabel').css('color', '#A4A6A7');
					$('.EscLabel').css('color', '#A4A6A7');
				}else{
					$('#GENS').attr("disabled",false);
					$('#GENNS').attr("disabled",false);
					$('#STLS').attr("disabled",false);
					$('#STLNS').attr("disabled",false);
					$('#ABSS').attr("disabled",false);
					$('#ABSNS').attr("disabled",false);
					$('#ESCS').attr("disabled",false);
					$('#ESCNS').attr("disabled",false);
					$('#GENS').attr('checked', false);
					$('#GENNS').attr('checked', false);
					$('#STLS').attr('checked', false);
					$('#STLNS').attr('checked', false);
					$('#ABSS').attr('checked', false);
					$('#ABSNS').attr('checked', false);
					$('#ESCS').attr('checked', false);
					$('#ESCNS').attr('checked', false);
					$('.StlLabel').css('color', '#0058B0');
					$('.AbsLabel').css('color', '#0058B0');
					$('.EscLabel').css('color', '#0058B0');
				}
				
			});
			
			$(".RAD").click(function(event){
				var value = $(this).val();
				var id = $(this).attr('id');
				var type = $(this).attr('data-type'); 
				if(value == 'S'){
					$("."+type+"NS").val('');
				}
				if(value == 'NS'){
					$("."+type+"S").val('');
				}
				$("."+type).removeClass("hide");
				$("."+type).addClass("hide");
				 //alert("#"+id+value+"ROW");
				$("#"+id+"ROW").removeClass("hide");
				
			});
			function StartEndMBNo(){
				var i = 1; var j = 4; //alert()
				for(var x = i; x<=j; x++){
					var Start = $('.start'+x).val();
					var End   = $('.end'+x).val();
					//alert();
					if(Start != '' && End != ''){
						if(Number(Start)>Number(End)){
							var AlertMsg = "Start MBook No. should be greater than End Mbook No.";
							BootstrapDialog.alert(AlertMsg);
							exit();
						}
					}
				}
			}
			function CurrentMBValidation(){
				var arr = [];
				$(".mbno").each(function(){
					var value = $(this).val();
					if(value != ''){
						if (arr.indexOf(value) == -1){
							arr.push(value);
						}else{
							var AlertMsg = "MBook No. "+value+ " already alloted in current process. Please try different MBook No. ";
							BootstrapDialog.alert(AlertMsg);
							$(this).val('');
							exit();
						}
					}
				});
			}
			$('body').on("change",".mbno", function(e){ 
				var mbno = $(this).val();
				var id = $(this).attr("id"); 
				$.ajax({ 
					type: 'POST', 
					url: 'find_existing_mbno.php', 
					data: { mbno: mbno }, 
					success: function (data) {   //alert(data);
						if(data == 1){
							var AlertMsg = "MBook No. "+mbno+ " already alloted. Please try different MBook No. ";
							BootstrapDialog.alert(AlertMsg);
							$("#"+id).val('');
						}
					}
				});
				StartEndMBNo();
				CurrentMBValidation();
			});
			$.fn.Validation = function(event) { 
				var ch = 0; var ch2 = 0; var ch3 = 0; var ch4 = 0; var ch5 = 0; var ch6 = 0;
				$(".RAD").each(function(index){
					if($(this).prop("checked")){
						ch++;
						if($(this).val() == 'S'){
							var RadIndex = $(this).attr('data-index');
							var StartVal = $(".start"+RadIndex).val();
							var EndVal 	 = $(".end"+RadIndex).val();
							if(StartVal == ""){
								ch5++;
							}
							if(EndVal == ""){
								ch6++;
							}
						}
					}
				});
				
				
				$(".mbno").each(function(index){
					if($(this).val() != ''){
						ch2++;
						if(Number($(this).val()) > 2147483647){
							ch3++;
							$(this).val('');
						}
						if(/[0-9]/.test($(this).val()) == false) {
							ch4++;
							$(this).val('');
						}
					}
				});
				if($("#workorderno").val() == ''){
					var AlertMsg = "Please Select Work Order No.";
					BootstrapDialog.alert(AlertMsg);
					event.preventDefault();
					event.returnValue = false;
				}else if(!$("input[name='radbookMode']").is(':checked')) {
					var AlertMsg = "Please Select MBook Mode";
					BootstrapDialog.alert(AlertMsg);
					event.preventDefault();
					event.returnValue = false;
				}else if(ch == 0){
					var AlertMsg = "Please Select Atleast One MBook";
					BootstrapDialog.alert(AlertMsg);
					event.preventDefault();
					event.returnValue = false;
				}else if(ch2 == 0){
					var AlertMsg = "Please Enter Atleast One MBook to Allot";
					BootstrapDialog.alert(AlertMsg);
					event.preventDefault();
					event.returnValue = false;
				}else if(ch3 > 0){
					var AlertMsg = "MBook No. should not be greater than '2147483647'";
					BootstrapDialog.alert(AlertMsg);
					event.preventDefault();
					event.returnValue = false;
				}else if(ch4 > 0){
					var AlertMsg = "Alphabets / Special Characters not allowed in MBook No.";
					BootstrapDialog.alert(AlertMsg);
					event.preventDefault();
					event.returnValue = false;
				}else if(ch5 > 0){
					var AlertMsg = "Start No. should not be empty.";
					BootstrapDialog.alert(AlertMsg);
					event.preventDefault();
					event.returnValue = false;
				}else if(ch6 > 0){
					var AlertMsg = "End No. should not be empty.";
					BootstrapDialog.alert(AlertMsg);
					event.preventDefault();
					event.returnValue = false;
				}
			}
			$("#top").submit(function(event){
				$(this).Validation(event);
			});
		});
		</script>
    </body>
</html>

