<?php
//session_start();
include "db_connect.php";
require_once 'Excel_read/reader.php';
$root_path=$_SERVER['DOCUMENT_ROOT'];
	echo "hello";
// Start : Single
if (isset($_POST['btn_save_single_x']))
{
	if($_POST['cmb_single_level2']=='New')
	{
		$sql_max_level2="select max(item_id) as item_id from item_master where 
							item_id like '" . $_POST['cmb_single_level1'] . "%' and char_length(item_id) = '4'";
		$rs_max_level2=mysqli_query($dbConn,$sql_max_level2,$conn);
		//echo $sql_max_level2.'</br>';
		//echo "d=".@mysqli_result($rs_max_level2,0,'item_id');
		
		$item_id_2=@mysqli_result($rs_max_level2,0,'item_id')+1;
		//echo "item_id_2=".$item_id_2.'</br>';
		$new_item_id_2='0'.$item_id_2;
		//echo "new_item_id_2=".$new_item_id_2.'</br>';
		
		$sql_insert_2="insert into item_master(item_id,item_desc)
						values('" . $new_item_id_2 . "' ,
							   '" . $_POST['txt_single_level2'] . "' )";
		$rs_insert_2=mysqli_query($dbConn,$sql_insert_2,$conn);
		//echo $sql_insert_2.'</br>';
		
		//echo "make=".$_POST['cmb_make_select'].'</br>';
		if($_POST['cmb_make_select']=='New')
		{
			$make=$_POST['txt_make_select_new'];
		}
		else if($_POST['cmb_make_select']=='None')
		{
			$make='';
		}
		else
		{
			$make=$_POST['cmb_make_select'];
		}
		
		
		$new_item_id_3=$new_item_id_2.'01';
		$sql_insert_3="insert into item_master(item_id,make,item_desc)
						values('" . $new_item_id_3 . "' ,
							   '" . $make . "' ,
							   '" . $_POST['txt_single_level3'] . "' )";
		$rs_insert_3=mysqli_query($dbConn,$sql_insert_3,$conn);
		//echo $sql_insert_3.'</br>';
		
		$new_item_id_4=$new_item_id_3.'001';
		$sql_insert_4="insert into item_master(item_id,make,item_desc)
						values('" . $new_item_id_4 . "' ,
							   '" . $make . "' ,
							   '" . $_POST['txt_single_level4'] . "' )";
		$rs_insert_4=mysqli_query($dbConn,$sql_insert_4,$conn);
		//echo $sql_insert_4.'</br>';
		
		if( ($rs_insert_2!="") && ($rs_insert_3!="") && ($rs_insert_4!="") )
		{
			?>
			<script type="text/javascript" language="javascript">
				alert("Successfully Saved")
				//window.location.href('item_master.php')
			</script>
			<?php
		}
	}
	
	if($_POST['cmb_make']=='New')
	{
		$sql_max_level3="select max(item_id) as item_id from item_master where 
							item_id like '" . $_POST['cmb_single_level2'] . "%' and char_length(item_id) = '6'";
		$rs_max_level3=mysqli_query($dbConn,$sql_max_level3,$conn);
		//echo "d=".@mysqli_result($rs_max_level3,0,'item_id').'</br>';
		$check_item_id=substr(@mysqli_result($rs_max_level3,0,item_id),4,2);
		//echo "check_item_id=".$check_item_id.'</br>';
		
		if($check_item_id=='99')
		{
			?>
			<script language="javascript" type="text/javascript">
				alert("You Cannot add items under this level... Pls add new second level")
			</script>
			<?php
		}
		
		else
		{
			$item_id_3=@mysqli_result($rs_max_level3,0,'item_id')+1;
			//echo "item_id_2=".$item_id_2.'</br>';
			$new_item_id_3='0'.$item_id_3;
			//echo "new_item_id_2=".$new_item_id_2.'</br>';
			
			$make=$_POST['txt_make_new'];
	
			$sql_insert_3="insert into item_master(item_id,make,item_desc)
							values('" . $new_item_id_3 . "' ,
								   '" . $make . "' ,
								   '" . $_POST['txt_single_level3'] . "' )";
			$rs_insert_3=mysqli_query($dbConn,$sql_insert_3,$conn);
			//echo $sql_insert_3.'</br>';
			
			$new_item_id_4=$new_item_id_3.'001';
			$sql_insert_4="insert into item_master(item_id,make,item_desc)
							values('" . $new_item_id_4 . "' ,
								   '" . $make . "' ,
								   '" . $_POST['txt_single_level4'] . "' )";
			$rs_insert_4=mysqli_query($dbConn,$sql_insert_4,$conn);
			//echo $sql_insert_4.'</br>';
		
			if( ($rs_insert_3!="") && ($rs_insert_4!="") )
			{
				?>
				<script type="text/javascript" language="javascript">
					alert("Successfully Saved")
					//window.location.href('item_master.php')
				</script>
				<?php
			}
		}
	}
	
	if($_POST['cmb_single_level3']=='New')
	{
		$sql_max_level3="select max(item_id) as item_id from item_master where 
							item_id like '" . $_POST['cmb_single_level2'] . "%' and char_length(item_id) = '6'";
		$rs_max_level3=mysqli_query($dbConn,$sql_max_level3,$conn);
		//echo $sql_max_level3.'</br>';
		//echo "d=".@mysqli_result($rs_max_level3,0,'item_id').'</br>';
		$check_item_id=substr(@mysqli_result($rs_max_level3,0,item_id),4,2);
		//echo "check_item_id=".$check_item_id.'</br>';
		
		if($check_item_id=='99')
		{
			?>
			<script language="javascript" type="text/javascript">
				alert("You Cannot add items under this level... Pls add new second level")
			</script>
			<?php
		}
		
		else
		{
			$item_id_3=@mysqli_result($rs_max_level3,0,'item_id')+1;
			//echo "item_id_3=".$item_id_3.'</br>';
			$new_item_id_3='0'.$item_id_3;
			//echo "new_item_id_3=".$new_item_id_3.'</br>';
			
			if($_POST['cmb_make']=='None')
			{
				$make='';
			}
			else
			{
				$make=$_POST['cmb_make'];
			}
			
			$sql_insert_3="insert into item_master(item_id,make,item_desc)
							values('" . $new_item_id_3 . "' ,
								   '" . $make . "' ,
								   '" . $_POST['txt_single_level3'] . "' )";
			$rs_insert_3=mysqli_query($dbConn,$sql_insert_3,$conn);
			//echo $sql_insert_3.'</br>';
			
			$new_item_id_4=$new_item_id_3.'001';
			$sql_insert_4="insert into item_master(item_id,make,item_desc)
							values('" . $new_item_id_4 . "' ,
								   '" . $make . "' ,
								   '" . $_POST['txt_single_level4'] . "' )";
			$rs_insert_4=mysqli_query($dbConn,$sql_insert_4,$conn);
			//echo $sql_insert_4.'</br>';
		
			if( ($rs_insert_3!="") && ($rs_insert_4!="") )
			{
				?>
				<script type="text/javascript" language="javascript">
					alert("Successfully Saved")
					//window.location.href('item_master.php')
				</script>
				<?php
			}
		}
	}
	
	if( ($_POST['txt_single_level4']!='') && ($_POST['cmb_make']!='New') && ($_POST['cmb_single_level3']!='Select') && ($_POST['cmb_single_level3']!='New') && ($_POST['cmb_single_level2']!='New'))
	{
		$sql_max_level4="select max(item_id) as item_id from item_master where 
							item_id like '" . $_POST['cmb_single_level3'] . "%' and char_length(item_id) = '9'";
		$rs_max_level4=mysqli_query($dbConn,$sql_max_level4,$conn);
		//echo $sql_max_level4.'</br>';
		//echo "d=".@mysqli_result($sql_max_level4,0,'item_id');
		
		if(@mysqli_result($rs_max_level4,0,'item_id')=='')
		{
			$new_item_id_4=$_POST['cmb_single_level3'].'001';
		}
		else
		{
			$item_id_4=@mysqli_result($rs_max_level4,0,'item_id')+1;
			//echo "item_id_2=".$item_id_2.'</br>';
			$new_item_id_4='0'.$item_id_4;
		//echo "new_item_id_2=".$new_item_id_2.'</br>';
		}
		
		if($_POST['cmb_make']=='None')
		{
			$make='';
		}
		else
		{
			$make=$_POST['cmb_make'];
		}
		
		$sql_insert_4="insert into item_master(item_id,make,item_desc)
						values('" . $new_item_id_4 . "' ,
							   '" . $make . "' ,
							   '" . $_POST['txt_single_level4'] . "' )";
		$rs_insert_4=mysqli_query($dbConn,$sql_insert_4,$conn);
		//echo "1=".$sql_insert_4.'</br>';
		
		if($rs_insert_4!="")
		{
			?>
			<script type="text/javascript" language="javascript">
				alert("Successfully Saved")
				//window.location.href('item_master.php')
			</script>
			<?php
		}
	}
}
// End : Single


// Start : Multiple
$col_1="";
$col_2="";
$col_3="";
$col_4="";
$col_5="";
$col_6="";
$rec_stored=false;
$sheet_no=-1;

if (isset($_POST['btn_save_multiple_x']))
{
	$excel_file_name=$_FILES["txt_multiple_level4"]["name"];
	$start_row=(int)$_POST['txt_row_start'];
	$end_row=(int)$_POST['txt_row_end'];
	
	move_uploaded_file($_FILES["txt_multiple_level4"]["tmp_name"], $root_path . "/upload/" . $excel_file_name );			
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('CP1251');
	$data->read($root_path . "/upload/" . $excel_file_name);

	$item_desc="";
	$unit="";
	
	for( $k=0; $k<count($data->boundsheets); $k++ )
	{
		if ( strtolower($data->boundsheets[$k]['name']) == strtolower($_POST['txt_sheet_name']) )
		{
			$sheet_no=$k;				
		}
	}			
	
	if ($sheet_no==-1) // if sheet not found
		echo "Sheet name '" . $_POST['txt_sheet_name'] . "' not found in '" . $excel_file_name . "'<br/>";
		
	else
	{
		$slno=1;
		
		for($row=$start_row; $row<=( $end_row) ; $row++ )
		{
			if ( trim( $data->sheets[$sheet_no]['cells'][$row][1] )=="")
			{
				$rec_stored=false;				
			}
			else
			{
				if ( trim( $data->sheets[$sheet_no]['cells'][$row][1] )=="" && $rec_stored==true )
				{
					$col_2.=$data->sheets[$sheet_no]['cells'][$row][2];
				}
				else
				{
					$col_1=$data->sheets[$sheet_no]['cells'][$row][1];
					$col_2=$data->sheets[$sheet_no]['cells'][$row][2];
					
					if($item_desc=="")
					{
						$item_desc=$col_1;
					}
					else
					{
						$item_desc=$item_desc . '*' . $col_1;
					}	
					
					if($unit=="")
					{
						$unit=$col_2;
					}
					else
					{
						$unit=$unit . '*' . $col_2;
					}	
				
					$rec_stored=true;
				}
			}
		}
	}
	
	if($item_desc!="")
	{
		$item_desc=explode('*',$item_desc);
		$unit=explode('*',$unit);
		
		for($x=0;$x<count($item_desc);$x++)
		{
			$item_desc_1=$item_desc[$x];
			$unit_1=$unit[$x];

			$sql_select_max="select max(item_id) as item_id from item_master where 
								item_id like '" . $_POST['cmb_multiple_level3'] . "%' and 
								char_length(item_id) = '9'";
			$rs_select_max=mysqli_query($dbConn,$sql_select_max,$conn);
			//echo "d=".@mysqli_result($rs_select_max,0,'item_id');
			
			$item_id=@mysqli_result($rs_select_max,0,'item_id')+1;
			$new_item_id='0'.$item_id;
			//echo "new_item_id=".$new_item_id;
	
			$sql_insert_multiple="insert into item_master(item_id,make,item_desc,unit) 
									values('" . $new_item_id . "' ,
										   '" . $_POST['cmb_multiple_make'] . "' ,
										   '" . $item_desc_1 . "' ,
										   '" . $unit_1 . "' )";
			$rs_insert_multiple=mysqli_query($dbConn,$sql_insert_multiple,$conn);
			//echo $sql_insert_multiple.'</br>';
		} 
		
		if($rs_insert_multiple!="")
		{
			?>
			<script type="text/javascript" language="javascript">
				alert("Successfully Uploaded")
			</script>
			<?php
		}
	}
}
// End : Multiple

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link rel="stylesheet" href="font.css" />
</head>

<!--<script language="javascript" type="text/javascript" src="script/Date_Calendar.js"></script>
<script language="javascript" type="text/javascript" src="script/validfn.js"></script>-->
<script type="text/javascript" language="javascript">

function show_type()
{
	if(document.form.type[0].checked==true)
	{
		document.getElementById("single").style.display="";
		document.getElementById("multiple").style.display="none";
	}
	if(document.form.type[1].checked==true)
	{
		document.getElementById("single").style.display="none";
		document.getElementById("multiple").style.display="";
	}
}


function show_single_level2()
{
	//document.form.cmb_single_level1.value='Select';	
	document.form.cmb_single_level2.value='Select';	
	document.form.cmb_make.value='Select';	
	document.form.cmb_single_level3.value='Select';	
	
	document.getElementById("single_level2").style.display="none";
	document.getElementById("make").style.display="none";
	document.getElementById("single_level3").style.display="none";
	
	document.getElementById("cmb_single_level2").disabled=false;
	document.getElementById("cmb_make").disabled=false;
	document.getElementById("txt_make_new").style.display="none";
	document.getElementById("cmb_single_level3").disabled=false;
	
	
	var xmlHttp;
    var data;
	var i,j;
	if(window.XMLHttpRequest) // For Mozilla, Safari, ...
	{
		xmlHttp = new XMLHttpRequest();
	}
	else if(window.ActiveXObject) // For Internet Explorer
	{ 
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	//alert("find_level2.php?item_id="+document.form.cmb_single_level1.value)
	strURL="find_level2.php?item_id="+document.form.cmb_single_level1.value
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	//alert(strURL)
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			//document.write(data)
			
			if(data=="")
			{
				alert("No Records Found");
			}
			else
			{
				document.form.cmb_single_level2.value='Select';	
				document.form.txt_single_level2.value='';	
				document.getElementById("single_level2").style.display="none";
				//document.getElementById("txt_single_level2").style.display="none";
				
				document.form.cmb_make.value='Select';	
				document.form.txt_make_new.value='';	
				document.getElementById("txt_make_new").style.display="none";
				
				document.form.cmb_make_select.value='Select';	
				document.form.txt_make_select_new.value='';	
				document.getElementById("make").style.display="none";
				
				document.form.cmb_single_level3.value='Select';	
				document.form.txt_single_level3.value='';	
				document.getElementById("single_level3").style.display="none";
				//document.getElementById("txt_single_level3").style.display="none";
				
				document.form.txt_single_level4.value=''
				
				var name=data.split("*");

				document.form.cmb_single_level2.length=0
				
				var optn=document.createElement("option")
				optn.value="Select";
				optn.text="Select";
				document.form.cmb_single_level2.options.add(optn)
				
				var optn=document.createElement("option")
				optn.value="New";
				optn.text="New";
				document.form.cmb_single_level2.options.add(optn)
				
				var c= name.length 
				//alert(name.length)
				var a=c/2;
				var b=a+1;
				
				for(i=1,j=b;i<a,j<c;i++,j++)
				{
					//alert("i="+i);
					//alert("name i="+name[i]);
					//alert("j="+j);
					//alert("name j="+name[j]);
					var optn=document.createElement("option")
					optn.value=name[i];
					optn.text=name[j];
					document.form.cmb_single_level2.options.add(optn)
				}
			}
		}
    }
	xmlHttp.send(strURL);	
}



function func_make()
{
	if(document.form.cmb_single_level2.value=='New')
	{
		document.getElementById("single_level2").style.display="";
		
		document.getElementById("cmb_make").value='Select';
		document.getElementById("cmb_make").disabled=true;
		document.getElementById("make").style.display="";
	
		document.getElementById("single_level3").style.display="";
		document.getElementById("cmb_single_level3").value='Select';
		document.getElementById("cmb_single_level3").disabled=true;
	}
	else
	{
		document.getElementById("cmb_make").value='Select';
		document.getElementById("cmb_make").disabled=false;
		document.getElementById("make").style.display="none";
		document.getElementById("txt_make_new").style.display="none";
		
		document.getElementById("single_level2").style.display="none";
		document.getElementById("single_level3").style.display="none";
		document.getElementById("cmb_single_level3").disabled=false;

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
		
		//alert("find_level3.php?item_id="+document.form.cmb_level2.value)
		strURL="find_level3_make.php?item_id="+document.form.cmb_single_level2.value
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText
				//document.write(data)
				
				if(data=="*")
				{
					document.form.cmb_make.length=0
					var optn=document.createElement("option")
					optn.value="Select";
					optn.text="Select";
					document.form.cmb_make.options.add(optn)
					
					var optn=document.createElement("option")
					optn.value="New";
					optn.text="New";
					document.form.cmb_make.options.add(optn)
					
					var optn=document.createElement("option")
					optn.value="None";
					optn.text="None";
					document.form.cmb_make.options.add(optn)
				}
				else
				{
					//alert(data)
					var name=data.split("*");
					//alert(name)
					document.form.cmb_make.length=0
					var optn=document.createElement("option")
					optn.value="Select";
					optn.text="Select";
					document.form.cmb_make.options.add(optn)
					
					var optn=document.createElement("option")
					optn.value="New";
					optn.text="New";
					document.form.cmb_make.options.add(optn)
					
					var optn=document.createElement("option")
					optn.value="None";
					optn.text="None";
					document.form.cmb_make.options.add(optn)
					
					var c= name.length 
					//alert(name.length)
					var a=c/2;
					var b=a+1;
					
					for(i=1,j=b;i<a,j<c;i++,j++)
					{
						//alert("i="+i);
						//alert("name i="+name[i]);
						//alert("j="+j);
						//alert("name j="+name[j]);
						var optn=document.createElement("option")
						optn.value=name[i];
						optn.text=name[j];
						document.form.cmb_make.options.add(optn)
					}
				}
			}
		}
		xmlHttp.send(strURL);	
    }
}



function func_make_select_new()
{
	if(document.form.cmb_make_select.value=='New')
	{
		document.getElementById("txt_make_select_new").style.display="";
	}
	else
	{
		document.getElementById("txt_make_select_new").style.display="none";
	}
}



function show_single_level3()
{
	if(document.form.cmb_make.value=='New')
	{
		document.getElementById("txt_make_new").style.display="";
		
		document.getElementById("single_level3").style.display="";
		document.getElementById("cmb_single_level3").value='Select';
		document.getElementById("cmb_single_level3").disabled=true;
	}
	if(document.form.cmb_make.value!='New')
	{
		document.getElementById("txt_make_new").style.display="none";
		
		document.getElementById("single_level3").style.display="none";
		document.getElementById("cmb_single_level3").disabled=false;

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
		//alert("find_level3.php?item_id="+document.form.cmb_single_level2.value)
		strURL="find_level3.php?item_id="+document.form.cmb_single_level2.value+"&make="+document.form.cmb_make.value
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText
				//document.write(data)
				
				if(data=="")
				{
					alert("No Records Found");
					document.form.cmb_single_level3.value='Select';	
				}
				else
				{
					//alert(data)
					var name=data.split("*");
					//alert(name)
					document.form.cmb_single_level3.length=0
					
					var optn=document.createElement("option")
					optn.value="Select";
					optn.text="Select";
					document.form.cmb_single_level3.options.add(optn)
	
					var optn=document.createElement("option")
					optn.value="New";
					optn.text="New";
					document.form.cmb_single_level3.options.add(optn)
					
					var c= name.length 
					//alert(name.length)
					var a=c/2;
					var b=a+1;
					
					for(i=1,j=b;i<a,j<c;i++,j++)
					{
						//alert("i="+i);
						//alert("name i="+name[i]);
						//alert("j="+j);
						//alert("name j="+name[j]);
						var optn=document.createElement("option")
						optn.value=name[i];
						optn.text=name[j];
						document.form.cmb_single_level3.options.add(optn)
					}
				}
			}
		}
		xmlHttp.send(strURL);	
	}
}


function show_single_new_level3()
{
	if(document.form.cmb_single_level3.value=='New')
	{
		document.getElementById("single_level3").style.display="";
	}
	if(document.form.cmb_single_level3.value!='New')
	{
		document.getElementById("single_level3").style.display="none";
	}
}



function show_multiple_level2()
{
	var xmlHttp;
    var data;
	var i,j;
	if(window.XMLHttpRequest) // For Mozilla, Safari, ...
	{
		xmlHttp = new XMLHttpRequest();
	}
	else if(window.ActiveXObject) // For Internet Explorer
	{ 
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	strURL="find_level2.php?item_id="+document.form.cmb_multiple_level1.value
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			//document.write(data)
			
			if(data=="")
			{
				alert("No Records Found");
				document.form.cmb_multiple_level2.value='Select';	
				document.form.cmb_multiple_level3.value='Select';	
			}
			else
			{
				//alert(data)
				var name=data.split("*");
				//alert(name)
				document.form.cmb_multiple_level2.length=0
				var optn=document.createElement("option")
				optn.value="Select";
				optn.text="Select";
				document.form.cmb_multiple_level2.options.add(optn)
				
				var c= name.length 
				//alert(name.length)
				var a=c/2;
				var b=a+1;
				
				for(i=1,j=b;i<a,j<c;i++,j++)
				{
					//alert("i="+i);
					//alert("name i="+name[i]);
					//alert("j="+j);
					//alert("name j="+name[j]);
					var optn=document.createElement("option")
					optn.value=name[i];
					optn.text=name[j];
					document.form.cmb_multiple_level2.options.add(optn)
				}
			}
		}
    }
	xmlHttp.send(strURL);	
}


function show_multiple_make()
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
	
	//alert("find_level3.php?item_id="+document.form.cmb_level2.value)
	strURL="find_level3_make.php?item_id="+document.form.cmb_multiple_level2.value
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			//document.write(data)
			
			if(data=="*")
			{
				document.form.cmb_multiple_make.length=0
				var optn=document.createElement("option")
				optn.value="Select";
				optn.text="Select";
				document.form.cmb_multiple_make.options.add(optn)
				
				var optn=document.createElement("option")
				optn.value="None";
				optn.text="None";
				document.form.cmb_multiple_make.options.add(optn)
			}
			else
			{
				//alert(data)
				var name=data.split("*");
				//alert(name)
				document.form.cmb_multiple_make.length=0
				var optn=document.createElement("option")
				optn.value="Select";
				optn.text="Select";
				document.form.cmb_multiple_make.options.add(optn)
				
				var optn=document.createElement("option")
				optn.value="None";
				optn.text="None";
				document.form.cmb_multiple_make.options.add(optn)
				
				var c= name.length 
				//alert(name.length)
				var a=c/2;
				var b=a+1;
				
				for(i=1,j=b;i<a,j<c;i++,j++)
				{
					//alert("i="+i);
					//alert("name i="+name[i]);
					//alert("j="+j);
					//alert("name j="+name[j]);
					var optn=document.createElement("option")
					optn.value=name[i];
					optn.text=name[j];
					document.form.cmb_multiple_make.options.add(optn)
				}
			}
		}
	}
	xmlHttp.send(strURL);	
}



function show_multiple_level3()
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
	//alert("find_level3.php?item_id="+document.form.cmb_multiple_level2.value)
	strURL="find_level3.php?item_id="+document.form.cmb_multiple_level2.value+"&make="+document.form.cmb_multiple_make.value
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			//document.write(data)
			
			if(data=="")
			{
				alert("No Records Found");
				document.form.cmb_multiple_level3.value='Select';	
			}
			else
			{
				//alert(data)
				var name=data.split("*");
				//alert(name)
				document.form.cmb_multiple_level3.length=0
				var optn=document.createElement("option")
				optn.value="Select";
				optn.text="Select";
				document.form.cmb_multiple_level3.options.add(optn)
				
				var c= name.length 
				//alert(name.length)
				var a=c/2;
				var b=a+1;
				
				for(i=1,j=b;i<a,j<c;i++,j++)
				{
					//alert("i="+i);
					//alert("name i="+name[i]);
					//alert("j="+j);
					//alert("name j="+name[j]);
					var optn=document.createElement("option")
					optn.value=name[i];
					optn.text=name[j];
					document.form.cmb_multiple_level3.options.add(optn)
				}
			}
		}
    }
	xmlHttp.send(strURL);	
}

</script>

<body bgcolor="c5d1dc" oncontextmenu="return false">
<form name="form" method="post" enctype="multipart/form-data" >

<table width="925" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr class="heading">
		<td width="25" height="28" background="Title bar/Titlebar_Left_Piece.jpg">&nbsp;</td>
		<td width="864" height="28" background="Title bar/Titlebar_Centre_Piece.jpg" align="left">Item Master</td>
		<td width="36" height="28" background="Title bar/Titlebar_Right_Piece.jpg">&nbsp;</td>
	</tr>
</table>

<table width="925" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
	<tr><td colspan="2">&nbsp;</td></tr>
	
	<tr>
		<td width="133" class="labelbold">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Select Item:</td>
		<td width="790" class="label">
			<input type="radio" name="type" value="Single" checked="checked"  onClick="show_type()" />&nbsp;Single Item&nbsp;&nbsp;
			<input type="radio" name="type"  value="Multiple"onClick="show_type()" />&nbsp;Multiple Item&nbsp;&nbsp;
	  </td>
	</tr>

	<tr><td colspan="2">&nbsp;</td></tr>
	
	<tr>
		<td border="0" cellpadding="0" cellspacing="0" colspan="4"> 
			<table style="display:" id="single" width="900" border="0" align="center" cellpadding="0" cellspacing="0" class="color3">

				<tr><td colspan="3">&nbsp;</td></tr>
				
				<tr>
					<td width="41" class="label">&nbsp;</td>
					<td width="84" class="labelbold">Level 1</td>
					<td width="800">
						<?php
						$sql_single_level1="select * from item_master where item_id in ('03','05','07') order by item_id";
						$rs_single_level1=mysqli_query($dbConn,$sql_single_level1,$conn);
						?>
						<select class="text" style="width:790px;height:21px;" name="cmb_single_level1" ID="cmb_single_level1" onBlur="show_single_level2()">
							<option value="Select">Select</option>
							<?php
							while($row=mysqli_fetch_assoc($rs_single_level1))
							{
								?>
								<option value="<?php echo $row['item_id'];?>"><?php echo $row['item_desc'];?></option>
								<?php
							}
						?>
				  		</select>
					</td>
				</tr>
				
				<tr><td colspan="3">&nbsp;</td></tr>
				
				<tr>
					<td width="41" class="label">&nbsp;</td>
					<td width="84" class="labelbold">Level 2</td>
					<td width="800">
						<select class="text" style="width:790px;height:21px;" name="cmb_single_level2" ID="cmb_single_level2" onBlur="func_make()">
							<option value="Select">Select</option>
							<option value="New">New</option>
				  		</select>
					</td>
				</tr>
				
				<tr style="display:none" id="single_level2">
					<td width="41">&nbsp;</td>
					<td width="84" class="labelbold">&nbsp;</td>
					<td width="800"><input type="text" style="width:790px;" name="txt_single_level2" id="txt_single_level2" class="text" value="" /></td>
				</tr>

				<tr><td colspan="3">&nbsp;</td></tr>
				
				<tr>
					<td width="41" class="label">&nbsp;</td>
					<td width="84" class="labelbold" valign="top">Make</td>
					<td width="800">
						<select class="text" style="width:600px;height:21px;" name="cmb_make" ID="cmb_make" onBlur="show_single_level3()">
							<option value="Select">Select</option>
							<option value="New">New</option>
				  		</select>
						&nbsp;&nbsp;<input type="text" class="text" style="display:none" name="txt_make_new" id="txt_make_new" value="" size="25" />
					</td>
				</tr>
				
				<tr style="display:none" id="make">
					<td width="41">&nbsp;</td>
					<td width="84" class="labelbold" valign="top">Select<br />&nbsp;&nbsp;Make</td>
					<td width="800">
						<select class="label" style="width:200px;height:21px;" name="cmb_make_select" ID="cmb_make_select" onBlur="func_make_select_new()">
							<option value="Select">Select</option>
							<option value="None">None</option>
							<option value="New">New</option>
							<option value="Comet">Comet</option>
							<option value="CRABTREE">CRABTREE</option>
							<option value="Dowells">Dowells</option>
							<option value="Havells">Havells</option>
							<option value="KEI">KEI</option>
							<option value="L and T">L and T</option>
							<option value="LAPP">LAPP</option>
							<option value="LEGRAND">LEGRAND</option>
							<option value="Power-Flex">Power-Flex</option>
							<option value="Raychem">Raychem</option>
							<option value="SIEMENS">SIEMENS</option>
					  	</select>
						&nbsp;&nbsp;<input type="text" class="text" style="display:none" name="txt_make_select_new" id="txt_make_select_new" value="" size="25" />
					</td>
				</tr>

				
				<tr><td colspan="3">&nbsp;</td></tr>
				
				
				<tr>
					<td width="41" class="label">&nbsp;</td>
					<td width="84" class="labelbold">Level 3</td>
					<td width="800">
						<select class="text" style="width:790px;height:21px;" name="cmb_single_level3" ID="cmb_single_level3" onBlur="show_single_new_level3()">
							<option value="Select">Select</option>
							<option value="New">New</option>
				  		</select>
					</td>
				</tr>
				
				<tr style="display:none" id="single_level3">
					<td width="41">&nbsp;</td>
					<td width="84" class="labelbold">&nbsp;</td>
					<td width="800"><input type="text" style="width:790px;" name="txt_single_level3" id="txt_single_level3" class="text" value="" /></td>
				</tr>

				<tr><td colspan="3">&nbsp;</td></tr>
				
				<tr>
					<td width="41" class="label">&nbsp;</td>
					<td width="84" class="labelbold">Level 4</td>
					<td width="800"><input type="text" style="width:790px;" name="txt_single_level4" id="txt_single_level4" class="text" value="" /></td>
				</tr>
				
				<tr><td colspan="3">&nbsp;</td></tr>
				
				<tr><td colspan="3" class="labelcenter"><input type="image" name="btn_save_single" id="btn_save_single" value="Save" src="Buttons/Save_Normal.jpg" onMouseOver="this.src='Buttons/Save_Over.jpg'" onMouseOut="this.src='Buttons/Save_Normal.jpg'" /></td></tr>
			
				<tr><td colspan="3">&nbsp;</td></tr>
			</table>
		</td>
	</tr>
	

	<tr>
		<td border="0" cellpadding="0" cellspacing="0" colspan="4"> 
			<table style="display:none" id="multiple" width="900" border="0" align="center" cellpadding="0" cellspacing="0" class="color3">

				<tr><td colspan="3">&nbsp;</td></tr>
				
				<tr>
					<td width="22">&nbsp;</td>
					<td width="107" class="labelbold">Level 1</td>
					<td width="769">
						<?php
						$sql_multiple_level1="select * from item_master where item_id in ('03','05') order by item_id";
						$rs_multiple_level1=mysqli_query($dbConn,$sql_multiple_level1,$conn);
						?>
						<select class="text" style="width:750px;height:21px;" name="cmb_multiple_level1" ID="cmb_multiple_level1" onBlur="show_multiple_level2()">
							<option value="Select">Select</option>
							<?php
							while($row=mysqli_fetch_assoc($rs_multiple_level1))
							{
								?>
								<option value="<?php echo $row['item_id'];?>"><?php echo $row['item_desc'];?></option>
								<?php
							}
						?>
				  </select></td>
				</tr>
				
				<tr><td colspan="3">&nbsp;</td></tr>
				
				<tr>
					<td width="22">&nbsp;</td>
					<td width="107" class="labelbold">Level 2</td>
					<td width="769">
						<select class="text" style="width:750px;height:21px;" name="cmb_multiple_level2" ID="cmb_multiple_level2" onBlur="show_multiple_make()">
							<option value="Select">Select</option>
				  </select></td>
				</tr>
				
				<tr><td colspan="3">&nbsp;</td></tr>
				
				<tr>
					<td width="41" class="label">&nbsp;</td>
					<td width="84" class="labelbold" valign="top">Make</td>
					<td width="800">
						<select class="text" style="width:600px;height:21px;" name="cmb_multiple_make" ID="cmb_multiple_make" onBlur="show_multiple_level3()">
							<option value="Select">Select</option>
				  		</select>
					</td>
				</tr>
				
				<tr><td colspan="3">&nbsp;</td></tr>
				
				<tr>
					<td width="22">&nbsp;</td>
					<td width="107" class="labelbold">Level 3</td>
					<td width="769">
						<select class="text" style="width:750px;height:21px;" name="cmb_multiple_level3" ID="cmb_multiple_level3">
							<option value="Select">Select</option>
				  </select></td>
				</tr>
				
				<tr><td colspan="3">&nbsp;</td></tr>
				
				<tr>
					<td width="22">&nbsp;</td>
					<td width="107" class="labelbold">File Name</td>
				  	<td width="769"><input type="file" name="txt_multiple_level4" id="txt_multiple_level4" value="" class="text" size="70" /></td>
				</tr>
				
				<tr><td colspan="3">&nbsp;</td></tr>
				
				<tr>
					<td width="22">&nbsp;</td>
					<td width="107" class="labelbold">Sheet Name</td>
					<td width="769">
						<input type="text" value="Sheet1" name="txt_sheet_name" id="txt_sheet_name" class="text" size="35" />
						<input type="hidden" name="retrieve_ash"  id="retrieve_ash" size="5" maxlength="31" value=""/>
				  	</td>
				</tr>
				
				<tr><td colspan="3">&nbsp;</td></tr>

				<tr>
					<td width="22">&nbsp;</td>
					<td width="107" class="labelbold">Row No. Start</td>
				  	<td width="769"><input type="text" class="text" name="txt_row_start" id="txt_row_start" size="6" maxlength="5" style="text-align:right" /></td>
				</tr>
				
				<tr><td>&nbsp;</td></tr>
				
				<tr>
					<td width="22">&nbsp;</td>
					<td width="107" class="labelbold" nowrap="nowrap">Row No. End</td>
				  	<td width="769"><input type="text" class="text" name="txt_row_end" id="txt_row_end" size="6" maxlength="5" style="text-align:right" /></td>
				</tr>
				
				<tr><td colspan="3">&nbsp;</td></tr>
				
				<tr><td colspan="3" class="labelcenter"><input type="image" name="btn_save_multiple" id="btn_save_multiple" value="Save" src="Buttons/Save_Normal.jpg" onMouseOver="this.src='Buttons/Save_Over.jpg'" onMouseOut="this.src='Buttons/Save_Normal.jpg'" /></td></tr>
				
				<tr><td colspan="3">&nbsp;</td></tr>
			</table>
		</td>
	</tr>
	
	<tr><td colspan="2">&nbsp;</td></tr>
</table>
	
</form>
</body>
</html>
