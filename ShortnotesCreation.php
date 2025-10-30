<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
$msg = '';

if (isset($_POST["submit"])) {

     $sheet_id = trim($_POST['workorderno']);
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
         $divid= trim($_POST['itemno']);
         $sql_selectsubid = "select subdiv_id from subdivision where div_id='$divid'";
         $res_subid = mysql_query($sql_selectsubid);
         $subdiv_id = @mysql_result($res_subid,0,'subdiv_id');
     }
	// echo $subdiv_id;
    $shortnotes = trim($_POST['shortnotes']);
    $mbookdetailquery = "SELECT  subdiv_id,shortnotes FROM schdule WHERE subdiv_id='$subdiv_id' AND sheet_id='$sheet_id'";
    $mbookdetailsql = dbQuery($mbookdetailquery);
    $mbookdetaillist = dbFetchAssoc($mbookdetailsql);
    //if($mbookdetaillist['shortnotes']=='') 
	//{ 
		$mbookdetailupdatequery = "UPDATE schdule SET shortnotes  ='$shortnotes'  WHERE subdiv_id='$subdiv_id' AND sheet_id='$sheet_id'";
		$schduleupdatesql = dbQuery($mbookdetailupdatequery);
		
	//} 
	if($schduleupdatesql==true)
	{
		$msg = "Shortnotes Created Sucessfully..!!";
		$success = 1;
	}
	else
	{
		$msg = "Something Error..!!";
	}

}//submit 
?>
<?php require_once "Header.html"; ?>

    <script type="text/javascript"  language="JavaScript">
		function goBack()
	   	{
	   		url = "dashboard.php";
			window.location.replace(url);
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
                            <div class="title">Short Notes Creation </div>		
                          
                                <input type="hidden" name="txt_item_no" id="txt_item_no" value="">
                                <input type="hidden" name="txt_work_no" id="txt_work_no" value="">

                                <div class="container">
                                    <table width="1060" border="1" cellpadding="0" cellspacing="0" align="center" >
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
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td class="label">Short Notes</td>
                                            <td class="labeldisplay">
                                                <!--<input type="text" name='shortnotes' id='shortnotes' class="textboxdisplay" value="" style="width:500px;height:22px;"/>-->
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
                                            
                                        </tr>
										<tr>
                                            <td colspan="5">
                                                <center>
                                                    <input type="hidden" class="text" name="submit" value="true" />
                                                    <input type="hidden"  id="sno_hide" name="sno_hide">
                                                                                                <!--<button type="button" class="btn" id="submit" data-type="submit" value=" Submit ">Submit</button>-->
                                                    <!--<input type="image" src="Buttons/submit.png" onmouseover="this.src='Buttons/submit_hover.png';" onmouseout="this.src='Buttons/submit.png';" class="btn" name="submit" value=" Submit " id="submit"/>-->
                                                    <!--<input type="submit" name="submit" value=" Submit " id="submit"/>&nbsp;&nbsp;&nbsp;
													<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>-->
                                                </center>
                                            </td>
                                     	</tr>
										 
                                  </table>
                            		<div style="text-align:center; height:45px; line-height:45px;" class="printbutton">
										<div class="buttonsection">
										<input type="button" class="backbutton" name="back" id="back" value="Back" onClick="goBack();"/>
										</div>
										<div class="buttonsection">
										<input type="submit" name="submit" value=" Submit " id="submit"/>
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
			
           $.fn.validateshortnotes = function(event) {	
				if($("#shortnotes").val()== ""){ 
					var a="Please Enter Shortnotes";
					$('#val_shortnotes').text(a);
					event.returnValue = false;//for ie
					event.preventDefault();//for chrome
					//return false;//for firefox
					}
				else{
				var a="";
				$('#val_shortnotes').text(a);		
				}
			}
		
		$("#top").submit(function(event){
            $(this).validateitemno(event);
			$(this).validatesubitemno(event);
			$(this).validatesubsubitemno(event);
			$(this).validatesubsubsubitemno(event);
			$(this).validateworkorder(event);
            $(this).validateshortnotes(event);
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
        $("#shortnotes").keyup(function(event){
          $(this).validateshortnotes(event);
         });
	  });
 </script>
		      
</body>
</html>