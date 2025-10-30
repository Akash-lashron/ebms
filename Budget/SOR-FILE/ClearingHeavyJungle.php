<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
//checkUser();
$sql_default_overhead="select * from default_master where de_name='Over head'";
$rs_default_overhead=mysqli_query($dbConn,$sql_default_overhead,$conn);
$overhead_percentage=@mysqli_result($rs_default_overhead,0,'de_perc');

$sql_default_profit="select * from default_master where de_name='Profit'";
$rs_default_profit=mysqli_query($dbConn,$sql_default_profit,$conn);
$profit_percentage=@mysqli_result($rs_default_profit,0,'de_perc');

$sql_default_safety="select * from default_master where de_name='Safety'";
$rs_default_safety=mysqli_query($dbConn,$sql_default_safety,$conn);
$safety_percentage=@mysqli_result($rs_default_safety,0,'de_perc');

$sql_default_security="select * from default_master where de_name='Security'";
$rs_default_security=mysqli_query($dbConn,$sql_default_security,$conn);
$security_percentage=@mysqli_result($rs_default_security,0,'de_perc');

$sql_default_wct="select * from default_master where de_name='WCT'";
$rs_default_wct=mysqli_query($dbConn,$sql_default_wct,$conn);
$wct_percentage=@mysqli_result($rs_default_wct,0,'de_perc');


if (isset($_POST['btn_save_x']))
{
	mysqli_select_db('estimator_sequence',$conn);
	$sql_ref_id="SELECT nextval_datasheet_master('ref_id') as next_sequence";
	$rs_ref_id=mysqli_query($dbConn,$sql_ref_id,$conn);
	$ref_id=mysqli_result($rs_ref_id,0,'next_sequence');
	mysqli_select_db('estimator',$conn);
	
	if($_POST['cmb_group1']=='New')
	{	
		$sql_group1_max="select max(group_id) as group_id from group_datasheet where char_length(group_id)='2'";
		$rs_group1_max=mysqli_query($dbConn,$sql_group1_max,$conn);
		$group_id1=@mysqli_result($rs_group1_max,0,'group_id')+1;
		$group_id2=$group_id1 . '01';
		$group_id=$group_id2 . '01';
		
		$group3_desc=$_POST['txt_group3'];
		
		$sql_insert_group1="insert into group_datasheet(group_id,group_desc,type)
								values('" . $group_id1 . "' ,
							   		   '" . $_POST['txt_group1'] . "' ,
									   'internal_wiring' )";
		$rs_insert_group1=mysqli_query($dbConn,$sql_insert_group1,$conn);
		//echo $sql_insert_group1.'</br>';
		
		$sql_insert_group2="insert into group_datasheet(group_id,group_desc)
								values('" . $group_id2 . "' ,
							   		   '" . $_POST['txt_group2'] . "' )";
		$rs_insert_group2=mysqli_query($dbConn,$sql_insert_group2,$conn);
		//echo $sql_insert_group2.'</br>';
		
		$sql_insert_group3="insert into group_datasheet(group_id,group_desc)
								values('" . $group_id . "' ,
							   		   '" . $_POST['txt_group3'] . "' )";
		$rs_insert_group3=mysqli_query($dbConn,$sql_insert_group3,$conn);
		//echo $sql_insert_group3.'</br>';
	}
	
	else if($_POST['cmb_group2']=='New')
	{	
		$sql_group2_max="select max(group_id) as group_id from group_datasheet where 
							group_id like '" . $_POST['cmb_group1'] . "%' and char_length(group_id)='4'";
		$rs_group2_max=mysqli_query($dbConn,$sql_group2_max,$conn);
		$group2=substr(@mysqli_result($rs_group2_max,0,group_id),2,4);
		$group2_id=$group2+1;
		$group_id1=substr(@mysqli_result($rs_group2_max,0,group_id),0,2);
		
		$group3_desc=$_POST['txt_group3'];
		
		if(strlen($group2_id)=='1')
			$group_id2=$group_id1.'0'.$group2_id;
		else
			$group_id2=$group_id1.$group2_id;
			
		$group_id=$group_id2.'01';
		
		$sql_insert_group2="insert into group_datasheet(group_id,group_desc)
								values('" . $group_id2 . "' ,
							   		   '" . $_POST['txt_group2'] . "' )";
		$rs_insert_group2=mysqli_query($dbConn,$sql_insert_group2,$conn);
		//echo $sql_insert_group2.'</br>';
		
		$sql_insert_group3="insert into group_datasheet(group_id,group_desc)
								values('" . $group_id . "' ,
							   		   '" . $_POST['txt_group3'] . "' )";
		$rs_insert_group3=mysqli_query($dbConn,$sql_insert_group3,$conn);
		//echo $sql_insert_group3.'</br>';
	}
	
	else if($_POST['cmb_group3']=='New')
	{	
		$sql_group3_max="select max(group_id) as group_id from group_datasheet where 
							group_id like '" . $_POST['cmb_group2'] . "%' and char_length(group_id)='6'";
		$rs_group3_max=mysqli_query($dbConn,$sql_group3_max,$conn);
			
		if(@mysqli_result($rs_group3_max,0,'group_id')=='')
		{
			$group_id=$_POST['cmb_group2'].'01';
			$group3_desc=$_POST['txt_group3'];
		}
		
		else
		{
			$group3=substr(@mysqli_result($rs_group3_max,0,group_id),4,2);
			$group3_id=$group3+1;
			$group_id2=substr(@mysqli_result($rs_group3_max,0,group_id),0,4);
			
			$group3_desc=$_POST['txt_group3'];
			
			if(strlen($group3_id)=='1')
				$group_id=$group_id2.'0'.$group3_id;
			else
				$group_id=$group_id2.$group3_id;
		}
		
		$sql_insert_group3="insert into group_datasheet(group_id,group_desc)
								values('" . $group_id . "' ,
							   		   '" . $_POST['txt_group3'] . "' )";
		$rs_insert_group3=mysqli_query($dbConn,$sql_insert_group3,$conn);
		//echo $sql_insert_group3.'</br>';
	}
	
	else
	{
		$group_id=$_POST['cmb_group3'];
		$group3_desc=$_POST['txt_group3_desc'];
	}
		
	$sql_insert="insert into datasheet_master(ref_id,group_id,group3_description,group4_description,quantity,unit,type)
					values('" . $ref_id . "' ,
						   '" . $group_id . "' ,
						   '" . $group3_desc . "' ,
						   '" . $_POST['txt_group4_desc'] . "' ,
						   '" . $_POST['txt_cost'] . "' ,
						   '" . $_POST['cmb_unit'] . "' ,
						   'internal_wiring')";
	$rs_insert=mysqli_query($dbConn,$sql_insert,$conn);
	//echo $sql_insert.'<br />';
	
	
	$a1_rec=explode(".",$_POST['add_set_a1']);
	
	for($c=0;$c<count($a1_rec);$c++)
	{
		$x=$a1_rec[$c];
		$sno=$c+1;
		
		$sql_insert_details="insert into datasheet_a1_details(sno,ref_id,item_id,quantity,SI)
					  values('" . $sno . "' ,
					  		 '" . $ref_id . "' ,
							 '" . $_POST['txt_item_id'.$x] . "' ,
							 '" . $_POST['txt_quantity'.$x] . "' ,
							 '" . $_POST['cmb_SI'.$x] . "'
							 )";
		//echo $sql_insert_details.'<br />';
		$rs_insert_details=mysqli_query($dbConn,$sql_insert_details,$conn);
	}
	if(($rs_insert!="") && ($rs_insert_details!=""))
	{
		?>
		<script language="javascript" type="text/javascript">
		x=confirm("Record saved for Reference ID: "+<?php echo '"' . $ref_id . '"';?>);
		if (x==true)
			w=window.location.href('internal_wiring_create.php?ref_id=<?php echo $ref_id;  ?>')
		</script>
		<?php
	}
}

if (isset($_POST['btn_save_full_x']))
{
	if($_POST['add_set_a2']!='')
	{
		$a2_rec=explode(".",$_POST['add_set_a2']);
		
		for($c=0;$c<count($a2_rec);$c++)
		{
			$x=$a2_rec[$c];
			$sno=$c+1;
			
			$sql_select_a2="SELECT * from equip_master where description='" . $_POST['cmb_a2_material'.$x] . "'";
			$rs_select_a2=mysqli_query($dbConn,$sql_select_a2,$conn);
			//echo $sql_select_a2.'<br />';
			
			$sql_insert_details_a2="insert into datasheet_a2_details(sno,ref_id,equip_id,method,sno_a1,quantity,hour_quantity)
									values('" . $sno . "' ,
										   '" . $_GET['ref_id'] . "' ,
										   '" . @mysqli_result($rs_select_a2,0,'equip_id') . "' ,
										   '" . @mysqli_result($rs_select_a2,0,'type') . "' ,
										   '" . $_POST['cmb_a2_item'.$x] . "' ,
										   '" . $_POST['txt_quantity_a2'.$x] . "' ,
										   '" . $_POST['txt_hour_quantity_a2'.$x] . "'
										)";
			//echo $sql_insert_details_a2.'<br />';
			$rs_insert_details_a2=mysqli_query($dbConn,$sql_insert_details_a2,$conn);
		}
	}
	
	
	if($_POST['add_set_b']!='')
	{
		$b_rec=explode(".",$_POST['add_set_b']);
		
		for($c=0;$c<count($b_rec);$c++)
		{
			$x=$b_rec[$c];
			$sno=$c+1;
			
			$sql_select_b="SELECT * from hr_master where hr_desc='" . $_POST['cmb_labour'.$x] . "'";
			$rs_select_b=mysqli_query($dbConn,$sql_select_b,$conn);
			//echo $sql_select_b.'<br />';
			
			$sql_insert_details_b="insert into datasheet_b_details(sno,ref_id,hr_id,quantity)
									values('" . $sno . "' ,
										   '" . $_GET['ref_id'] . "' ,
										   '" . @mysqli_result($rs_select_b,0,'hr_id') . "' ,
										   '" . $_POST['txt_days_hours'.$x] . "'  )";
			//echo $sql_insert_details_b.'<br />';
			$rs_insert_details_b=mysqli_query($dbConn,$sql_insert_details_b,$conn);
		}
	}
	
	if(($rs_insert_details_a2!="") && ($rs_insert_details_b!=""))
	{
		?>
		<script language="javascript" type="text/javascript">
			alert("Successfully Saved")
		</script>
		<?php
	}
}


if($_GET['ref_id']!='')
{
	$sql_master="SELECT * from datasheet_master where ref_id='" . $_GET['ref_id'] . "' and 
						type='internal_wiring'";
	$rs_master=mysqli_query($dbConn,$sql_master,$conn);
	
	if(@mysqli_result($rs_master,0,'ref_id')!='')
	{
		$id_group1=substr(@mysqli_result($rs_master,0,group_id),0,2);
		$id_group2=substr(@mysqli_result($rs_master,0,group_id),0,4);
		$id_group3=substr(@mysqli_result($rs_master,0,group_id),0,6);
		
		$sql_group1="select group_id,group_desc from group_datasheet where group_id='" . $id_group1 . "'";
		$rs_group1=mysqli_query($dbConn,$sql_group1,$conn);
		//echo $sql_group1.'<br />';
		$group1=@mysqli_result($rs_group1,0,group_id);
		$group1_desc=@mysqli_result($rs_group1,0,group_desc);
		
		$sql_group2="select group_id,group_desc from group_datasheet where group_id='" . $id_group2 . "'";
		$rs_group2=mysqli_query($dbConn,$sql_group2,$conn);
		//echo $sql_group2.'<br />';
		$group2=@mysqli_result($rs_group2,0,group_id);
		$group2_desc=@mysqli_result($rs_group2,0,group_desc);
		
		$sql_group3="select group_id,group_desc from group_datasheet where group_id='" . $id_group3 . "'";
		$rs_group3=mysqli_query($dbConn,$sql_group3,$conn);
		//echo $sql_group3.'<br />';
		$group3=@mysqli_result($rs_group3,0,group_id);
		$group3_desc=@mysqli_result($rs_group3,0,group_desc);
		
		$sql_select_a1="select * from datasheet_a1_details where ref_id='" . $_GET['ref_id'] . "' order by sno";
		$rs_select_a1=mysqli_query($dbConn,$sql_select_a1,$conn);
		
		//$sql_select_a2="select * from datasheet_a2_details where ref_id='" . $_GET['ref_id'] . "' order by sno";
		//$rs_select_a2=mysqli_query($dbConn,$sql_select_a2,$conn);
		
		//$sql_select_b="select * from datasheet_b_details where ref_id='" . $_GET['ref_id'] . "' order by sno";
		//$rs_select_b=mysqli_query($dbConn,$sql_select_b,$conn);
		
		$sql_supply_items="select * from datasheet_a1_details where ref_id ='" . $_GET['ref_id'] . "' and SI='Supply' order by sno";
		//echo $sql_supply_items;
		$rs_supply_items=mysqli_query($dbConn,$sql_supply_items,$conn);
		$supply_amt=0;
		
		while($row_supply=mysqli_fetch_array($rs_supply_items))
		{
			$sql_select_supply_items="select * from item_master where item_id='" . $row_supply['item_id'] . "'";
			//echo $sql_select_supply_items.'<br />';
			$rs_select_supply_items=mysqli_query($dbConn,$sql_select_supply_items,$conn);
			
			$sql_supply_details="select * from datasheet_a1_details where item_id='" . $row_supply['item_id'] . "' and ref_id='" . $_GET['ref_id'] . "' and SI='Supply'";
			//echo $sql_supply_details.'<br />';
			$rs_supply_details=mysqli_query($dbConn,$sql_supply_details,$conn);
	
			$item_basic_price=@mysqli_result($rs_select_supply_items,0,'price')*@mysqli_result($rs_select_supply_items,0,'factor');
			$item_x=($item_basic_price*@mysqli_result($rs_select_supply_items,0,'ED'))/100;
			$item_y=($item_x*@mysqli_result($rs_select_supply_items,0,'CESS'))/100;
			$item_sub_total1=$item_basic_price+$item_x+$item_y;
			$item_VAT=($item_sub_total1*@mysqli_result($rs_select_supply_items,0,'VAT'))/100;
			$item_CST=($item_sub_total1*@mysqli_result($rs_select_supply_items,0,'CST'))/100;
			$item_amount=$item_sub_total1+$item_VAT+$item_CST;
			$item_packing=($item_amount*@mysqli_result($rs_select_supply_items,0,'packing'))/100;
			$item_freight=($item_amount*@mysqli_result($rs_select_supply_items,0,'freight'))/100;
			$item_sub_total2=$item_amount+$item_packing+$item_freight;
			$item_insurance_charge=($item_sub_total2*@mysqli_result($rs_select_supply_items,0,'insurance_charge'))/100;
			$item_total=$item_sub_total2+$item_insurance_charge;
			$item_total=number_format($item_total, 2, '.', '');	
			//$item_total=round($item_total);	
								
			$supply_amt=$supply_amt+($item_total*@mysqli_result($rs_supply_details,0,'quantity'));
		}
		//echo $amt.'<br />';
		
		$overhead_supply_amt=($supply_amt*$overhead_percentage)/100;
		$overhead_supply_amt=number_format($overhead_supply_amt, 2, '.', '');	
		
		$profit_supply_amt=($supply_amt*$profit_percentage)/100;
		$profit_supply_amt=number_format($profit_supply_amt, 2, '.', '');	
		
		$sub_supply_amt=$supply_amt+$overhead_supply_amt+$profit_supply_amt;
		$sub_supply_amt=number_format($sub_supply_amt, 2, '.', '');	
		
		$wct_supply_amt=($sub_supply_amt*$wct_percentage)/100;
		$wct_supply_amt=number_format($wct_supply_amt, 2, '.', '');	
		
		$total_supply_amt=$sub_supply_amt+$wct_supply_amt;
		$total_supply_amt=number_format($total_supply_amt, 2, '.', '');	
		
		//$supply_amt_per_mtr=$total_supply_amt/@mysqli_result($rs_master,0,'quantity');
		//$supply_amt_per_mtr=round($supply_amt_per_mtr);
		
		
		$sql_install_1_items="select * from datasheet_a1_details where ref_id ='" . $_GET['ref_id'] . "' and SI='Inst Variable' order by sno";
		//echo $sql_install_1_items.'<br />';
		$rs_install_1_items=mysqli_query($dbConn,$sql_install_1_items,$conn);
		$install_1_a1_amt=0;
		
		while($row_install_1=mysqli_fetch_array($rs_install_1_items))
		{
			$sql_select_install_1_items="select * from item_master where item_id='" . $row_install_1['item_id'] . "'";
			//echo $sql_select_install_1_items.'<br />';
			$rs_select_install_1_items=mysqli_query($dbConn,$sql_select_install_1_items,$conn);
			
			$sql_install_1_details="select * from datasheet_a1_details where item_id='" . $row_install_1['item_id'] . "' and ref_id='" . $_GET['ref_id'] . "' and SI='Inst Variable'";
			//echo $sql_install_1_details.'<br />';
			$rs_install_1_details=mysqli_query($dbConn,$sql_install_1_details,$conn);
			
			$item_install_1_basic_price=@mysqli_result($rs_select_install_1_items,0,'price')*@mysqli_result($rs_select_install_1_items,0,'factor');
			$item_install_1_x=($item_install_1_basic_price*@mysqli_result($rs_select_install_1_items,0,'ED'))/100;
			$item_install_1_y=($item_install_1_x*@mysqli_result($rs_select_install_1_items,0,'CESS'))/100;
			$item_install_1_sub_total1=$item_install_1_basic_price+$item_install_1_x+$item_install_1_y;
			$item_install_1_VAT=($item_install_1_sub_total1*@mysqli_result($rs_select_install_1_items,0,'VAT'))/100;
			$item_install_1_CST=($item_install_1_sub_total1*@mysqli_result($rs_select_install_1_items,0,'CST'))/100;
			$item_install_1_amount=$item_install_1_sub_total1+$item_install_1_VAT+$item_install_1_CST;
			$item_install_1_packing=($item_install_1_amount*@mysqli_result($rs_select_install_1_items,0,'packing'))/100;
			$item_install_1_freight=($item_install_1_amount*@mysqli_result($rs_select_install_1_items,0,'freight'))/100;
			$item_install_1_sub_total2=$item_install_1_amount+$item_install_1_packing+$item_install_1_freight;
			$item_install_1_insurance_charge=($item_install_1_sub_total2*@mysqli_result($rs_select_install_1_items,0,'insurance_charge'))/100;
			$item_install_1_total=$item_install_1_sub_total2+$item_install_1_insurance_charge;
			$item_install_1_total=number_format($item_install_1_total, 2, '.', '');	
			//$item_install_1_total=round($item_install_1_total);	
			
			/*echo "basic_price=".$item_install_basic_price.'</br>';
			echo "x=".$item_install_x.'</br>';
			echo "y=".$item_install_y.'</br>';
			echo "sub_total1=".$item_install_sub_total1.'</br>';
			echo "VAT=".$item_install_VAT.'</br>';
			echo "CST=".$item_install_CST.'</br>';
			echo "amount=".$item_install_amount.'</br>';
			echo "packing=".$item_install_packing.'</br>';
			echo "freight=".$item_install_freight.'</br>';
			echo "sub_total2=".$item_install_sub_total2.'</br>';
			echo "insurance_charge=".$item_install_total.'</br>';
			
			echo "install_a1_amt=".$install_a1_amt.'</br>';
			echo "item_install_total=".$item_install_total.'</br>';
			echo "quantity=".@mysqli_result($rs_install_details,0,'quantity').'</br>';*/
									
			$install_1_a1_amt=$install_1_a1_amt+($item_install_1_total*@mysqli_result($rs_install_1_details,0,'quantity'));
		}
		$install_1_a1_amt=number_format($install_1_a1_amt, 2, '.', '');	
		//echo $install_1_a1_amt.'<br />';
		
		
		$sql_install_2_items="select * from datasheet_a1_details where ref_id ='" . $_GET['ref_id'] . "' and SI='Inst Fixed' order by sno";
		//echo $sql_install_2_items.'<br />';
		$rs_install_2_items=mysqli_query($dbConn,$sql_install_2_items,$conn);
		$install_2_a1_amt=0;
		
		while($row_install_2=mysqli_fetch_array($rs_install_2_items))
		{
			$sql_select_install_2_items="select * from item_master where item_id='" . $row_install_2['item_id'] . "'";
			//echo $sql_select_install_2_items.'<br />';
			$rs_select_install_2_items=mysqli_query($dbConn,$sql_select_install_2_items,$conn);
			
			$sql_install_2_details="select * from datasheet_a1_details where item_id='" . $row_install_2['item_id'] . "' and ref_id='" . $_GET['ref_id'] . "' and SI='Inst Fixed'";
			//echo $sql_install_2_details.'<br />';
			$rs_install_2_details=mysqli_query($dbConn,$sql_install_2_details,$conn);
			
			$item_install_2_basic_price=@mysqli_result($rs_select_install_2_items,0,'price')*@mysqli_result($rs_select_install_2_items,0,'factor');
			$item_install_2_x=($item_install_2_basic_price*@mysqli_result($rs_select_install_2_items,0,'ED'))/100;
			$item_install_2_y=($item_install_2_x*@mysqli_result($rs_select_install_2_items,0,'CESS'))/100;
			$item_install_2_sub_total1=$item_install_2_basic_price+$item_install_2_x+$item_install_2_y;
			$item_install_2_VAT=($item_install_2_sub_total1*@mysqli_result($rs_select_install_2_items,0,'VAT'))/100;
			$item_install_2_CST=($item_install_2_sub_total1*@mysqli_result($rs_select_install_2_items,0,'CST'))/100;
			$item_install_2_amount=$item_install_2_sub_total1+$item_install_2_VAT+$item_install_2_CST;
			$item_install_2_packing=($item_install_2_amount*@mysqli_result($rs_select_install_2_items,0,'packing'))/100;
			$item_install_2_freight=($item_install_2_amount*@mysqli_result($rs_select_install_2_items,0,'freight'))/100;
			$item_install_2_sub_total2=$item_install_2_amount+$item_install_2_packing+$item_install_2_freight;
			$item_install_2_insurance_charge=($item_install_2_sub_total2*@mysqli_result($rs_select_install_2_items,0,'insurance_charge'))/100;
			$item_install_2_total=$item_install_2_sub_total2+$item_install_2_insurance_charge;
			$item_install_2_total=number_format($item_install_2_total, 2, '.', '');	
			//$item_install_2_total=round($item_install_2_total);	
			
			/*echo "item_install_2_basic_price=".$item_install_2_basic_price.'</br>';
			echo "item_install_2_x=".$item_install_2_x.'</br>';
			echo "item_install_2_y=".$item_install_2_y.'</br>';
			echo "item_install_2_sub_total1=".$item_install_2_sub_total1.'</br>';
			echo "item_install_2_VAT=".$item_install_2_VAT.'</br>';
			echo "item_install_2_CST=".$item_install_2_CST.'</br>';
			echo "item_install_2_amount=".$item_install_2_amount.'</br>';
			echo "item_install_2_packing=".$item_install_2_packing.'</br>';
			echo "item_install_2_freight=".$item_install_2_freight.'</br>';
			echo "item_install_2_sub_total2=".$item_install_2_sub_total2.'</br>';
			echo "item_install_2_insurance_charge=".$item_install_2_insurance_charge.'</br>';
			echo "item_install_2_total=".$item_install_2_total.'</br>';*/
									
			$install_2_a1=$item_install_2_total*@mysqli_result($rs_install_2_details,0,'quantity');
			$install_2_a1_amt+=$install_2_a1;
			//echo "install_2_a1_amt=".$install_2_a1_amt.'</br>';
		}
		$install_2_a1_amt=number_format($install_2_a1_amt, 2, '.', '');	
		//echo $install_2_a1_amt.'<br />';
		
		
		$sql_select_a2="select * from datasheet_a2_details where ref_id='" . $_GET['ref_id'] . "' order by sno";
		$rs_select_a2=mysqli_query($dbConn,$sql_select_a2,$conn);
	
		$install_a2_amt='';
		while($result=@mysqli_fetch_assoc($rs_select_a2))
		{
			$sql_select_equip="select * from equip_master where equip_id='" . $result['equip_id'] . "'";
			//echo $sql_select_equip.'<br />';
			$rs_select_equip=mysqli_query($dbConn,$sql_select_equip,$conn);
			$equip_name=@mysqli_result($rs_select_equip,0,'equip_desc');
			
			$sql_select_a1_details="select * from datasheet_a1_details where ref_id='" . $_GET['ref_id'] . "'";
			$rs_select_a1_details=mysqli_query($dbConn,$sql_select_a1_details,$conn);
			//echo $sql_select_per.'</br>';
			
			if($result['method']=='Rate')
			{
				$rate='';
				$quantity=$result['quantity'];
				$hour_quantity='-';
				$amount='';
				$sno_a1=$result['sno_a1'];
				if($result['sno_a1']=='All')
				{
					while($row=mysqli_fetch_array($rs_select_a1_details))
					{
						$sql_select_item_master="select *," . $equip_name . " as equip_desc from item_master 
													where item_id='" . $row['item_id'] . "'";
						$rs_select_item_master=mysqli_query($dbConn,$sql_select_item_master,$conn);
						//echo $sql_select_item_master.'</br>';
						
						$item_basic_price=@mysqli_result($rs_select_item_master,0,'price')*@mysqli_result($rs_select_item_master,0,'factor');
						$item_x=($item_basic_price*@mysqli_result($rs_select_item_master,0,'ED'))/100;
						$item_y=($item_x*@mysqli_result($rs_select_item_master,0,'CESS'))/100;
						$item_sub_total1=$item_basic_price+$item_x+$item_y;
						$item_VAT=($item_sub_total1*@mysqli_result($rs_select_item_master,0,'VAT'))/100;
						$item_CST=($item_sub_total1*@mysqli_result($rs_select_item_master,0,'CST'))/100;
						$item_amount=$item_sub_total1+$item_VAT+$item_CST;
						$item_packing=($item_amount*@mysqli_result($rs_select_item_master,0,'packing'))/100;
						$item_freight=($item_amount*@mysqli_result($rs_select_item_master,0,'freight'))/100;
						$item_sub_total2=$item_amount+$item_packing+$item_freight;
						$item_insurance_charge=($item_sub_total2*@mysqli_result($rs_select_item_master,0,'insurance_charge'))/100;
						$item_total=$item_sub_total2+$item_insurance_charge;
						$item_total=number_format($item_total, 2, '.', '');	
						//$item_total=round($item_total);	
						
						$rate+=($item_total*@mysqli_result($rs_select_item_master,0,'equip_desc'))/100;
					}
					$rate=number_format($rate, 2, '.', '');
					$total=$rate*$result['quantity'];
					$amount=number_format($total, 2, '.', '');
				}
				else
				{
					$sql_select_sno_a1="select * from datasheet_a1_details where ref_id='" . $_GET['ref_id'] . "'
										and sno='" . $result['sno_a1'] . "'";
					$rs_select_sno_a1=mysqli_query($dbConn,$sql_select_sno_a1,$conn);
					//echo $sql_select_sno_a1.'</br>';
					
					$sql_select_item_master="select *," . $equip_name . " as equip_desc from item_master where 
												item_id='" . @mysqli_result($rs_select_sno_a1,0,'item_id') . "'";
					//echo $sql_select_item_master.'<br />';
					$rs_select_item_master=mysqli_query($dbConn,$sql_select_item_master,$conn);
						
					$item_basic_price=@mysqli_result($rs_select_item_master,0,'price')*@mysqli_result($rs_select_item_master,0,'factor');
					$item_x=($item_basic_price*@mysqli_result($rs_select_item_master,0,'ED'))/100;
					$item_y=($item_x*@mysqli_result($rs_select_item_master,0,'CESS'))/100;
					$item_sub_total1=$item_basic_price+$item_x+$item_y;
					$item_VAT=($item_sub_total1*@mysqli_result($rs_select_item_master,0,'VAT'))/100;
					$item_CST=($item_sub_total1*@mysqli_result($rs_select_item_master,0,'CST'))/100;
					$item_amount=$item_sub_total1+$item_VAT+$item_CST;
					$item_packing=($item_amount*@mysqli_result($rs_select_item_master,0,'packing'))/100;
					$item_freight=($item_amount*@mysqli_result($rs_select_item_master,0,'freight'))/100;
					$item_sub_total2=$item_amount+$item_packing+$item_freight;
					$item_insurance_charge=($item_sub_total2*@mysqli_result($rs_select_item_master,0,'insurance_charge'))/100;
					$item_total=$item_sub_total2+$item_insurance_charge;
					$item_total=number_format($item_total, 2, '.', '');	
					//$item_total=round($item_total);	
						
					$rate=($item_total*@mysqli_result($rs_select_item_master,0,'equip_desc'))/100;
					$rate=number_format($rate, 2, '.', '');
					$total=$rate*$result['quantity'];
					$amount=number_format($total, 2, '.', '');
				}
			}
			
			
			if($result['method']=='Percentage')
			{
				$rate='';
				$quantity=$result['quantity'];
				$hour_quantity='-';
				$amount='';
				$sno_a1=$result['sno_a1'];
				if($result['sno_a1']=='All')
				{
					while($row=mysqli_fetch_array($rs_select_a1_details))
					{
						$sql_select_item_master="select * from item_master where item_id='" . $row['item_id'] . "'";
						$rs_select_item_master=mysqli_query($dbConn,$sql_select_item_master,$conn);
						//echo $sql_select_item_master.'</br>';
						
						$item_basic_price=@mysqli_result($rs_select_item_master,0,'price')*@mysqli_result($rs_select_item_master,0,'factor');
						$item_x=($item_basic_price*@mysqli_result($rs_select_item_master,0,'ED'))/100;
						$item_y=($item_x*@mysqli_result($rs_select_item_master,0,'CESS'))/100;
						$item_sub_total1=$item_basic_price+$item_x+$item_y;
						$item_VAT=($item_sub_total1*@mysqli_result($rs_select_item_master,0,'VAT'))/100;
						$item_CST=($item_sub_total1*@mysqli_result($rs_select_item_master,0,'CST'))/100;
						$item_amount=$item_sub_total1+$item_VAT+$item_CST;
						$item_packing=($item_amount*@mysqli_result($rs_select_item_master,0,'packing'))/100;
						$item_freight=($item_amount*@mysqli_result($rs_select_item_master,0,'freight'))/100;
						$item_sub_total2=$item_amount+$item_packing+$item_freight;
						$item_insurance_charge=($item_sub_total2*@mysqli_result($rs_select_item_master,0,'insurance_charge'))/100;
						$item_total=$item_sub_total2+$item_insurance_charge;
						$item_total=number_format($item_total, 2, '.', '');	
						//$item_total=round($item_total);	
						
						$rate+=$item_total*$row['quantity'];
						$amount+=($item_total*$row['quantity']*$result['quantity'])/100;
					}
					$amount=number_format($amount, 2, '.', '');	
				}
				else
				{
					$sql_select_sno_a1_details="select * from datasheet_a1_details where ref_id='" . $_GET['ref_id'] . "' and
													sno='" . $result['sno_a1'] . "'";
					$rs_select_sno_a1_details=mysqli_query($dbConn,$sql_select_sno_a1_details,$conn);
					//echo $sql_select_sno_a1_details.'</br>';
					
					$sql_select_item_master="select * from item_master where item_id='" . @mysqli_result($rs_select_sno_a1_details,0,'item_id') . "'";
					//echo $sql_select_item_master.'<br />';
					$rs_select_item_master=mysqli_query($dbConn,$sql_select_item_master,$conn);
					
					$item_basic_price=@mysqli_result($rs_select_item_master,0,'price')*@mysqli_result($rs_select_item_master,0,'factor');
					$item_x=($item_basic_price*@mysqli_result($rs_select_item_master,0,'ED'))/100;
					$item_y=($item_x*@mysqli_result($rs_select_item_master,0,'CESS'))/100;
					$item_sub_total1=$item_basic_price+$item_x+$item_y;
					$item_VAT=($item_sub_total1*@mysqli_result($rs_select_item_master,0,'VAT'))/100;
					$item_CST=($item_sub_total1*@mysqli_result($rs_select_item_master,0,'CST'))/100;
					$item_amount=$item_sub_total1+$item_VAT+$item_CST;
					$item_packing=($item_amount*@mysqli_result($rs_select_item_master,0,'packing'))/100;
					$item_freight=($item_amount*@mysqli_result($rs_select_item_master,0,'freight'))/100;
					$item_sub_total2=$item_amount+$item_packing+$item_freight;
					$item_insurance_charge=($item_sub_total2*@mysqli_result($rs_select_item_master,0,'insurance_charge'))/100;
					$item_total=$item_sub_total2+$item_insurance_charge;
					$item_total=number_format($item_total, 2, '.', '');	
					
					$rate=$item_total*@mysqli_result($rs_select_sno_a1_details,0,'quantity');
					$amount=($rate*$result['quantity'])/100;
					$amount=number_format($amount, 2, '.', '');	
				}
			}
			
			
			if($result['method']=='Hour')
			{
				$rate='';
				$quantity=$result['quantity'];
				$hour_quantity=$result['hour_quantity'];
				$amount='';
				$sno_a1='-';
				$sql_select_equip="select * from equip_master where equip_id='" . $result['equip_id'] . "'";
				$rs_select_equip=mysqli_query($dbConn,$sql_select_equip,$conn);
				//echo $sql_select_equip.'</br>';
				
				$rate=@mysqli_result($rs_select_equip,0,'rate_hour');
				//$amount=(@mysqli_result($rs_select_equip,0,'rate_hour')*$result['quantity']*@mysqli_result($rs_master,0,'quantity'))/$result['hour_quantity'];
				$amount=(@mysqli_result($rs_select_equip,0,'rate_hour')*$result['quantity']*$result['hour_quantity']);
			}
			
			if($result['method']=='Value')
			{
				$rate='';
				$quantity=$result['quantity'];
				$hour_quantity='-';
				$amount='';
				$sno_a1='-';
				
				$sql_route_indicator="select * from route_indicator";
				$rs_route_indicator=mysqli_query($dbConn,$sql_route_indicator,$conn);
				
				$height1=@mysqli_result($rs_route_indicator,0,'height1');
				$breadth1=@mysqli_result($rs_route_indicator,0,'breadth1');
				$thick1=@mysqli_result($rs_route_indicator,0,'thick1');
				$pie=3.14;
				$height2=@mysqli_result($rs_route_indicator,0,'height2');
				$breadth2=@mysqli_result($rs_route_indicator,0,'breadth2');
				$thick2=@mysqli_result($rs_route_indicator,0,'thick2');
				$rcc_value=@mysqli_result($rs_route_indicator,0,'rcc_value');
				
				$value1=$height1*$breadth1*$thick1;
				$value2=($pie*$height2*$breadth2*$thick2)/2;
				$total_value=($value1+$value2)*$rcc_value;
				$total_value=number_format($total_value, 2, '.', '');
				$final_value=$total_value/$result['quantity'];
				$rate=number_format($final_value, 2, '.', '');
				
				$amount=$rate*$result['quantity'];
			}		
			
			if($result['method']=='Unit')
			{
				$rate='';
				$quantity=$result['quantity'];
				$hour_quantity=$result['hour_quantity'];
				$amount='';
				$sno_a1='-';
				$sql_select_equip="select * from equip_master where equip_id='" . $result['equip_id'] . "'";
				$rs_select_equip=mysqli_query($dbConn,$sql_select_equip,$conn);
				$rate=@mysqli_result($rs_select_equip,0,'rate_hour');
			
				$amount=($rate*$result['quantity']);
			}
				
			$install_a2_amt+=$amount;
		}
		$install_a2_amt=number_format($install_a2_amt, 2, '.', '');
		//echo $install_a2_amt;
		
		
		$sql_select_b="select * from datasheet_b_details where ref_id='" . $_GET['ref_id'] . "' order by sno";
		$rs_select_b=mysqli_query($dbConn,$sql_select_b,$conn);
		
		while($result=@mysqli_fetch_assoc($rs_select_b))
		{
			$sql_select_details_b="SELECT * from hr_master where hr_id='" . $result['hr_id'] . "'";
			$rs_select_details_b=mysqli_query($dbConn,$sql_select_details_b,$conn);
			//echo $sql_select_details_b.'<br />';
			
			$day_hour_rate=@mysqli_result($rs_select_details_b,0,'rate_day');
			
			$amount=$day_hour_rate*$result['quantity'];
			$install_b_amt+=$amount;
		}
		$install_b_amt=number_format($install_b_amt, 2, '.', '');	
	
		$install_1_amt=($install_1_a1_amt+$install_a2_amt)/@mysqli_result($rs_master,0,'quantity');
		$install_1_amt=number_format($install_1_amt, 2, '.', '');	
		
		$overhead_install_1_amt=($install_1_amt*$overhead_percentage)/100;
		$overhead_install_1_amt=number_format($overhead_install_1_amt, 2, '.', '');	
		
		$profit_install_1_amt=($install_1_amt*$profit_percentage)/100;
		$profit_install_1_amt=number_format($profit_install_1_amt, 2, '.', '');	
		
		$safety_install_1_amt=($install_1_amt*$safety_percentage)/100;
		$safety_install_1_amt=number_format($safety_install_1_amt, 2, '.', '');	
		
		$security_install_1_amt=($install_1_amt*$security_percentage)/100;
		$security_install_1_amt=number_format($security_install_1_amt, 2, '.', '');	
		
		$sub_install_1_amt=$install_1_amt+$overhead_install_1_amt+$profit_install_1_amt+$safety_install_1_amt+$security_install_1_amt;
		$sub_install_1_amt=number_format($sub_install_1_amt, 2, '.', '');	
		
		$wct_install_1_amt=($sub_install_1_amt*$wct_percentage)/100;
		$wct_install_1_amt=number_format($wct_install_1_amt, 2, '.', '');	
		
		$install_1=$sub_install_1_amt+$wct_install_1_amt;
		
		
		$install_2_amt=$install_2_a1_amt+$install_b_amt;
		
		$overhead_install_2_amt=($install_2_amt*$overhead_percentage)/100;
		$overhead_install_2_amt=number_format($overhead_install_2_amt, 2, '.', '');	
		
		$profit_install_2_amt=($install_2_amt*$profit_percentage)/100;
		$profit_install_2_amt=number_format($profit_install_2_amt, 2, '.', '');	
		
		$safety_install_2_amt=($install_2_amt*$safety_percentage)/100;
		$safety_install_2_amt=number_format($safety_install_2_amt, 2, '.', '');	
		
		$security_install_2_amt=($install_2_amt*$security_percentage)/100;
		$security_install_2_amt=number_format($security_install_2_amt, 2, '.', '');	
		
		$sub_install_2_amt=$install_2_amt+$overhead_install_2_amt+$profit_install_2_amt+$safety_install_2_amt+$security_install_2_amt;
		$sub_install_2_amt=number_format($sub_install_2_amt, 2, '.', '');	
		
		$wct_install_2_amt=($sub_install_2_amt*$wct_percentage)/100;
		$wct_install_2_amt=number_format($wct_install_2_amt, 2, '.', '');	
		
		$install_2=$sub_install_2_amt+$wct_install_2_amt;
	}
	else
	{
		?>
		<script language="javascript" type="text/javascript">
			alert("Invalid Reference ID")
		</script>
		<?php
		$_GET['ref_id']='';
	}
}

$msg = ""; $del = 0;
$RowCount =0;
$staffid = $_SESSION['sid'];
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>	
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<?php include "MainMenu.php"; ?>
				<div class="container_12">
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" style="overflow:auto">
							<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>General</title>
<link rel="stylesheet" href="font.css" />
</head>

<script language="javascript" type="text/javascript" src="script/Date_Calendar.js"></script>
<script language="javascript" type="text/javascript" src="script/validfn.js"></script>
<script type="text/javascript" language="javascript">

function func_ref_id()
{
	document.form.method="post";
	document.form.action="internal_wiring_create.php?ref_id="+document.form.txt_ref_id.value
	document.form.submit();
}



function list_search(id,val)
 {
     cnt=document.getElementById(id).length
	 //alert(cnt)
	 for(x=0; x<cnt; x++ )
	 {
		 if( document.getElementById(id).options(x).value==val)
		 {
		 		//alert()
			 document.getElementById(id).options(x).selected=true
			 break;
		 }
	 }
} 


function validation()
{
	x=alltrim(document.form.txt_cost.value);
	if(x.length==0)
	{
		alert("Please Enter the Cost")
		document.form.txt_cost.value="";
		document.form.txt_cost.focus();
		return false
	}
	
	if(document.form.cmb_unit.value=='Select')
	{
		alert("Please Select the Unit")
		document.form.cmb_unit.focus();
		return false
	}
	
	if(document.form.cmb_group1.value=='Select')
	{
		alert("Please Select the Group1")
		document.form.cmb_group1.focus();
		return false
	}
	else if(document.form.cmb_group1.value=='New')
	{
		x=alltrim(document.form.txt_group1.value);
		if(x.length==0)
		{
			alert("Please Enter the Group1")
			document.form.txt_group1.value="";
			document.form.txt_group1.focus();
			return false
		}
		x=alltrim(document.form.txt_group2.value);
		if(x.length==0)
		{
			alert("Please Enter the Group2")
			document.form.txt_group2.value="";
			document.form.txt_group2.focus();
			return false
		}
		x=alltrim(document.form.txt_group3.value);
		if(x.length==0)
		{
			alert("Please Enter the Group3")
			document.form.txt_group3.value="";
			document.form.txt_group3.focus();
			return false
		}
	}
	
	else if(document.form.cmb_group2.value=='Select')
	{
		alert("Please Select the Group2")
		document.form.cmb_group2.focus();
		return false
	}
	else if(document.form.cmb_group2.value=='New')
	{
		x=alltrim(document.form.txt_group2.value);
		if(x.length==0)
		{
			alert("Please Enter the Group2")
			document.form.txt_group2.value="";
			document.form.txt_group2.focus();
			return false
		}
		x=alltrim(document.form.txt_group3.value);
		if(x.length==0)
		{
			alert("Please Enter the Group3")
			document.form.txt_group3.value="";
			document.form.txt_group3.focus();
			return false
		}
	}
	
	else if(document.form.cmb_group3.value=='Select')
	{
		alert("Please Select the Group3")
		document.form.cmb_group3.focus();
		return false
	}
	else if(document.form.cmb_group3.value=='New')
	{
		x=alltrim(document.form.txt_group3.value);
		if(x.length==0)
		{
			alert("Please Enter the Group3")
			document.form.txt_group3.value="";
			document.form.txt_group3.focus();
			return false
		}
	}
	
	if(add_row<=2)
	{
		alert("Please Add Atleast one Record")
		document.form.txt_desc.focus();
		return false
	}
}


function func_group2()
{
	if(document.form.cmb_group1.value=='New')
	{
		document.getElementById("new_group1").style.display="";
		document.getElementById("new_group2").style.display="";
		document.getElementById("cmb_group2").disabled=true;
		document.getElementById("new_group3").style.display="";
		document.getElementById("cmb_group3").disabled=true;
		
		document.getElementById("desc_group3").style.display="none";
	}
	
	else
	{
		document.getElementById("new_group1").style.display="none";
		document.getElementById("new_group2").style.display="none";
		document.getElementById("cmb_group2").disabled=false;
		document.getElementById("new_group3").style.display="none";
		document.getElementById("cmb_group3").disabled=false;
		
		document.getElementById("desc_group3").style.display="";
		
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
		//alert("find_group2.php?group_id="+document.form.cmb_group1.value)
		strURL="find_group2.php?group_id="+document.form.cmb_group1.value
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
					document.form.cmb_group2.value='Select';	
					document.form.cmb_group3.value='Select';	
				}
				else
				{
					var name=data.split("*");
					document.form.cmb_group2.length=0
					var optn=document.createElement("option")
					optn.value="Select";
					optn.text="Select";
					document.form.cmb_group2.options.add(optn)
					
					var optn=document.createElement("option")
					optn.value="New";
					optn.text="New";
					document.form.cmb_group2.options.add(optn)
					
					var c= name.length 
					var a=c/2;
					var b=a+1;
					
					for(i=1,j=b;i<a,j<c;i++,j++)
					{
						var optn=document.createElement("option")
						optn.value=name[i];
						optn.text=name[j];
						document.form.cmb_group2.options.add(optn)
					}
				}
			}
		}
		xmlHttp.send(strURL);	
	}
}

function func_group3()
{
	if(document.form.cmb_group2.value=='New')
	{
		document.getElementById("new_group2").style.display="";
		//document.getElementById("cmb_group2").disabled=true;
		document.getElementById("new_group3").style.display="";
		document.getElementById("cmb_group3").disabled=true;
		
		document.getElementById("desc_group3").style.display="none";
	}
	
	else
	{
		document.getElementById("new_group2").style.display="none";
		//document.getElementById("cmb_group2").disabled=false;
		document.getElementById("new_group3").style.display="none";
		document.getElementById("cmb_group3").disabled=false;
		
		document.getElementById("desc_group3").style.display="";
		
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
		//alert("find_group3.php?group_id="+document.form.cmb_group2.value)
		strURL="find_group3.php?group_id="+document.form.cmb_group2.value
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText
				//document.write(data)
				
				document.form.txt_group3_desc.value='';	
				if(data=="")
				{
					alert("No Records Found");
					document.form.cmb_group3.value='Select';	
				}
				else
				{
					var name=data.split("*");
					document.form.cmb_group3.length=0
					var optn=document.createElement("option")
					optn.value="Select";
					optn.text="Select";
					document.form.cmb_group3.options.add(optn)
					
					var optn=document.createElement("option")
					optn.value="New";
					optn.text="New";
					document.form.cmb_group3.options.add(optn)
					
					var c= name.length 
					var a=c/2;
					var b=a+1;
					
					for(i=1,j=b;i<a,j<c;i++,j++)
					{
						var optn=document.createElement("option")
						optn.value=name[i];
						optn.text=name[j];
						document.form.cmb_group3.options.add(optn)
					}
				}
			}
		}
		xmlHttp.send(strURL);	
	}
}

function func_group3_desc()
{
	if(document.form.cmb_group3.value=='New')
	{
		document.getElementById("new_group3").style.display="";
		document.getElementById("desc_group3").style.display="none";
	}
	
	else
	{
		document.getElementById("new_group3").style.display="none";
		document.getElementById("desc_group3").style.display="";
		
		var selIndex = document.form.cmb_group3.selectedIndex;
		var comboValue = document.form.cmb_group3.options[selIndex].text;
		document.form.txt_group3_desc.value=comboValue
	}
}

/********************* Start : A1 Material *********************/

function func_amount()
{
	var amount=parseFloat(document.form.txt_rate.value)*parseFloat(document.form.txt_quantity.value);
	document.form.txt_amount.value=(amount.toFixed(2));
}


//.......Multiple  Row Add Function........//

var add_row=2;
var prev_edit_row=0
function addrow()
{
	var new_row=document.getElementById("tab_a1_material").insertRow(add_row);
	new_row.setAttribute("id","row_" +add_row)
	new_row.className="labelcenter"

	x=alltrim(document.form.txt_desc.value);
	if(x.length==0)
	{
		alert("Please Select the Description")
		document.form.txt_desc.value="";
		document.form.txt_desc.focus();
		return false
	}
	
	if(document.form.cmb_SI.value=='Select')
	{
		alert("Please select the Type")
		document.form.cmb_SI.focus();
		return false
	}

	x=alltrim(document.form.txt_quantity.value);
	if(x.length==0)
	{
		alert("Please Enter the Quantity")
		document.form.txt_quantity.value="";
		document.form.txt_quantity.focus();
		return false
	}
	/*else
	{
		document.form.txt_quantity.value=x;
		x=numeric_only(document.form.txt_quantity.value)
		if(x==0)
		{
			alert("Please Enter valid Quantity and should be in numeric")
			document.form.txt_quantity.value="";
			document.form.txt_quantity.focus();
			return false;
		}		
	}*/

	var c1=new_row.insertCell(0);
		c1.align="center";
	var c2=new_row.insertCell(1);
		c2.align="left";
	var c3=new_row.insertCell(2);
		c3.align="center";
	var c4=new_row.insertCell(3);
		c4.align="right";
	var c5=new_row.insertCell(4);
		c5.align="center";
	var c6=new_row.insertCell(5);
		c6.align="center";
	var c7=new_row.insertCell(6);
		c7.align="center";
	var c8=new_row.insertCell(7);
		c8.align="right";
	var c9=new_row.insertCell(8);
		c9.align="center";

	c1.innerText=document.form.txt_sno_a1.value;
	c2.innerText=document.form.txt_desc.value;
	c3.innerText=document.form.txt_item_id.value;
	c4.innerText=document.form.txt_rate.value;
	c5.innerText=document.form.txt_unit.value;
	c6.innerText=document.form.cmb_SI.value;
	c7.innerText=document.form.txt_quantity.value;
	c8.innerText=document.form.txt_amount.value;

	c9.innerHTML="<input type='button' name='btn_edit_"+add_row+"' id='btn_edit_"+add_row+"' value='Edit' onClick=editrow("+add_row+",'n')>&nbsp;&nbsp;&nbsp;&nbsp;<input type='button'  name='btn_del_"+add_row+"'  id='btn_del_"+add_row+"' value='Delete' onClick=delrow("+add_row+")>";
	
	var hide_values="";
	hide_values="<input type='hidden' value='"+c1.innerText+"' name='txt_sno_a1"+add_row+"' id='txt_sno_a1"+add_row+"' >";
	hide_values+="<input type='hidden' value='"+c2.innerText+"' name='txt_desc"+add_row+"' id='txt_desc"+add_row+"' >";
	hide_values+="<input type='hidden' value='"+c3.innerText+"' name='txt_item_id"+add_row+"' id='txt_item_id"+add_row+"' >";
	hide_values+="<input type='hidden' value='"+c4.innerText+"' name='txt_rate"+add_row+"' id='txt_rate"+add_row+"' >";
	hide_values+="<input type='hidden' value='"+c5.innerText+"' name='txt_unit"+add_row+"' id='txt_unit"+add_row+"' >";
	hide_values+="<input type='hidden' value='"+c6.innerText+"' name='cmb_SI"+add_row+"' id='cmb_SI"+add_row+"' >";
	hide_values+="<input type='hidden' value='"+c7.innerText+"' name='txt_quantity"+add_row+"' id='txt_quantity"+add_row+"' >";
	hide_values+="<input type='hidden' value='"+c8.innerText+"' name='txt_amount"+add_row+"' id='txt_amount"+add_row+"' >";
	
	document.getElementById("add_hidden").innerHTML=document.getElementById("add_hidden").innerHTML+hide_values;
	
	if(document.getElementById("add_set_a1").value=="")
		document.getElementById("add_set_a1").value=add_row;
	else
		document.getElementById("add_set_a1").value=document.getElementById("add_set_a1").value +"."+add_row;
		
	var tot_amt=0;
	for(x=2; x<=add_row;x++)
	{
		tot_amt+=parseFloat(document.getElementById("txt_amount"+x).value)
	}
	document.getElementById("txt_total_amount").value=tot_amt;
	
	cleartxt();
	add_row++;	
	var sno=parseInt(document.getElementById("txt_sno_a1").value)+1;
	document.getElementById("txt_sno_a1").value=sno;
}

//.......Multiple  Row Delete Function........//

function delrow(rownum)
{
	var src_row=(rownum+1)
	var tar_row=rownum
	var noofadd=(add_row-1)
	
	var total=document.getElementById("txt_total_amount").value;
	var tot_amt=total-document.getElementById("txt_amount"+rownum).value;
	//alert(tot_amt)
	document.getElementById("txt_total_amount").value=tot_amt;	
	
	for(x=rownum; x<noofadd; x++)
	{
		document.getElementById("txt_sno_a1"+tar_row).value=document.getElementById("txt_sno_a1"+src_row).value
		document.getElementById("txt_desc"+tar_row).value=document.getElementById("txt_desc"+src_row).value
		document.getElementById("txt_item_id"+tar_row).value=document.getElementById("txt_item_id"+src_row).value
		document.getElementById("txt_rate"+tar_row).value=document.getElementById("txt_rate"+src_row).value
		document.getElementById("txt_unit"+tar_row).value=document.getElementById("txt_unit"+src_row).value
		document.getElementById("cmb_SI"+tar_row).value=document.getElementById("cmb_SI"+src_row).value
		document.getElementById("txt_quantity"+tar_row).value=document.getElementById("txt_quantity"+src_row).value
		document.getElementById("txt_amount"+tar_row).value=document.getElementById("txt_amount"+src_row).value
		
		tar_row++;
		src_row++;
		
		var trow=document.getElementById("tab_a1_material").rows[x].cells;
		var srow=document.getElementById("tab_a1_material").rows[x+1].cells;
		
		trow[0].innerText=srow[0].innerText
		trow[1].innerText=srow[1].innerText
		trow[2].innerText=srow[2].innerText
		trow[3].innerText=srow[3].innerText
		trow[4].innerText=srow[4].innerText
		trow[5].innerText=srow[5].innerText
		trow[6].innerText=srow[6].innerText
		trow[7].innerText=srow[7].innerText
	}
		
	document.getElementById("txt_sno_a1"+tar_row).outerHTML=""  
	document.getElementById("txt_desc"+tar_row).outerHTML=""  
	document.getElementById("txt_item_id"+tar_row).outerHTML=""
	document.getElementById("txt_rate"+tar_row).outerHTML=""
	document.getElementById("txt_unit"+tar_row).outerHTML=""  
	document.getElementById("cmb_SI"+tar_row).outerHTML=""  
	document.getElementById("txt_quantity"+tar_row).outerHTML=""
	document.getElementById("txt_amount"+tar_row).outerHTML=""
	
	document.getElementById('tab_a1_material').deleteRow(noofadd)
		
	document.getElementById("add_set_a1").value="";
		
	for(x=2; x<noofadd; x++)
	{
		if (document.getElementById("add_set_a1").value=="" )
			document.getElementById("add_set_a1").value=x;
		else
			document.getElementById("add_set_a1").value+=("."+x);
	}
	add_row=noofadd		
}

function editrow(rowno,update)
{
	var total;
	var net_value;
	var edit_row=document.getElementById("tab_a1_material").rows[rowno].cells;

	if(update=='y') // transfer controls to table row
	{
		x=alltrim(document.form.txt_desc.value);
		if(x.length==0)
		{
			alert("Please Select the Description")
			document.form.txt_desc.value="";
			document.form.txt_desc.focus();
			return false
		}
	
		if((document.form.cmb_SI.value)=='Select')
		{
			alert("Please select the Type")
			document.form.cmb_SI.focus();
			return false
		}

		x=alltrim(document.form.txt_quantity.value);
		if(x.length==0)
		{
			alert("Please Enter the Quantity")
			document.form.txt_quantity.value="";
			document.form.txt_quantity.focus();
			return false
		}
		/*else
		{
			document.form.txt_quantity.value=x;
			x=numeric_only(document.form.txt_quantity.value)
			if(x==0)
			{
				alert("Please Enter valid Quantity and should be in numeric")
				document.form.txt_quantity.value="";
				document.form.txt_quantity.focus();
				return false;
			}		
		}*/
			
		edit_row[0].innerText=document.form.txt_sno_a1.value;
		edit_row[1].innerText=document.form.txt_desc.value;
		edit_row[2].innerText=document.form.txt_item_id.value;
		edit_row[3].innerText=document.form.txt_rate.value;
		edit_row[4].innerText=document.form.txt_unit.value;
		edit_row[5].innerText=document.form.cmb_SI.value;
		edit_row[6].innerText=document.form.txt_quantity.value;
		edit_row[7].innerText=document.form.txt_amount.value;
			
		document.getElementById("txt_sno_a1"+rowno).value=edit_row[0].innerText
		document.getElementById("txt_desc"+rowno).value=edit_row[1].innerText
		document.getElementById("txt_item_id"+rowno).value=edit_row[2].innerText
		document.getElementById("txt_rate"+rowno).value=edit_row[3].innerText
		document.getElementById("txt_unit"+rowno).value=edit_row[4].innerText
		document.getElementById("cmb_SI"+rowno).value=edit_row[5].innerText
		document.getElementById("txt_quantity"+rowno).value=edit_row[6].innerText
		document.getElementById("txt_amount"+rowno).value=edit_row[7].innerText
	}//update=='y'
	
	else  //transfer table row to controls
	{
		document.form.txt_sno_a1.value=edit_row[0].innerText
		document.form.txt_desc.value=edit_row[1].innerText
		document.form.txt_item_id.value=edit_row[2].innerText
		document.form.txt_rate.value=edit_row[3].innerText
		document.form.txt_unit.value=edit_row[4].innerText
		document.form.cmb_SI.value=edit_row[5].innerText
		document.form.txt_quantity.value=edit_row[6].innerText
		document.form.txt_amount.value=edit_row[7].innerText
	}
	
	if(prev_edit_row==0)//first time edit the row
	{
		document.getElementById("row_"+rowno).style.color="red";
		document.getElementById("btn_edit_"+rowno).value="Cancel";
		document.getElementById("btn_add").outerHTML="<input type='button' name='btn_add' id='btn_add' value='Accept' onClick=\"editrow("+rowno+",'y')\">";
		prev_edit_row=rowno;  
	}
	else
	{
		if(rowno == prev_edit_row)
		{
			document.getElementById("row_"+prev_edit_row).style.color="#770000";
			document.getElementById("btn_edit_"+rowno).value="Edit";
			document.getElementById("btn_add").outerHTML="<input type='button' name='btn_add' id='btn_add' value='Add' onClick='addrow()'>";
			prev_edit_row=0;
			cleartxt();
		}
		
		else
		{
			document.getElementById("row_"+prev_edit_row).style.color="#770000";
			document.getElementById("btn_edit_"+prev_edit_row).value="Edit";
			
			document.getElementById("row_"+rowno).style.color="red";
			document.getElementById("btn_edit_"+rowno).value="Cancel";
			document.getElementById("btn_add").outerHTML="<input type='button' name='btn_add' id='btn_add' value='Accept' onClick=\"editrow("+rowno+",'y')\">";
			prev_edit_row=rowno;
		}
	}
	
	var tot_amt=0;
	for(x=2; x<add_row;x++)
	{
		tot_amt+=parseFloat(document.getElementById("txt_amount"+x).value)
	}
	document.getElementById("txt_total_amount").value=tot_amt;
}
//FUNCTION EDITROW

function cleartxt()
{
	document.getElementById("txt_desc").value="";
	document.getElementById("txt_item_id").value="";
	document.getElementById("txt_rate").value="";
	document.getElementById("txt_unit").value="";
	document.getElementById("cmb_SI").value="Select";
	document.getElementById("txt_quantity").value="";
	document.getElementById("txt_amount").value="";
}

/********************* End : A1 Material *********************/


/********************* Start : A2 Material *********************/

function func_a2()
{
	//alert();
	var xmlHttp;
    var data;
	data='';
	if(window.XMLHttpRequest) // For Mozilla, Safari, ...
	{
		xmlHttp = new XMLHttpRequest();
	}
	else if(window.ActiveXObject) // For Internet Explorer
	{ 
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	strURL="find_a2_amount.php?&material="+document.form.cmb_a2_material.value;
	//strURL="find_a2_amount.php?item_id="+document.form.cmb_a2_item.value+"&cost="+document.form.txt_cost.value+"&material="+document.form.cmb_a2_material.value;
	//alert(strURL)
	
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			//document.write(data)
			type_int=data;
			if(data==1)
			{
				type='Rate';
				document.getElementById("cmb_a2_item").disabled=false;
				
				document.form.txt_rate_a2.value='';
				document.getElementById("txt_rate_a2").disabled=false;
				
				document.form.txt_quantity_a2.value='';
				document.getElementById("txt_quantity_a2").disabled=false;
				
				document.form.txt_hour_quantity_a2.value='';
				document.getElementById("txt_hour_quantity_a2").disabled=true;
				
				//document.getElementById("txt_image").style.display="none";
				func_rate();
			}
			else if(data==2)
			{
				type='Percentage';
				document.getElementById("cmb_a2_item").disabled=false;
				
				document.form.txt_rate_a2.value='';
				document.getElementById("txt_rate_a2").disabled=true;
				
				document.form.txt_quantity_a2.value='';
				document.getElementById("txt_quantity_a2").disabled=false;
				
				document.form.txt_hour_quantity_a2.value='';
				document.getElementById("txt_hour_quantity_a2").disabled=true;
				
				//document.getElementById("txt_image").style.display="none";
				func_percentage();
			}
			else if(data==3)
			{
				type='Hour';
				document.form.cmb_a2_item.value='Select';
				document.getElementById("cmb_a2_item").disabled=true;
				
				document.getElementById("txt_rate_a2").disabled=false;
				document.getElementById("txt_quantity_a2").disabled=false;
				document.getElementById("txt_hour_quantity_a2").disabled=false;
				//document.getElementById("txt_image").style.display="";
				func_hour();
			}
			else if(data==4)
			{
				type='Value';
				document.form.cmb_a2_item.value='Select';
				document.getElementById("cmb_a2_item").disabled=true;
				
				document.getElementById("txt_rate_a2").disabled=false;
				document.getElementById("txt_quantity_a2").disabled=false;
				document.getElementById("txt_hour_quantity_a2").disabled=true;
				//document.getElementById("txt_image").style.display="none";
				func_value();
			}
			else if(data==5)
			{
				type='Unit';
				document.form.cmb_a2_item.value='Select';
				document.getElementById("cmb_a2_item").disabled=true;
				
				document.getElementById("txt_rate_a2").disabled=false;
				document.getElementById("txt_quantity_a2").disabled=false;
				document.getElementById("txt_hour_quantity_a2").disabled=true;
				//document.getElementById("txt_image").style.display="none";
				func_unit();
			}
	   }
   }
	xmlHttp.send(strURL);	
}

function func_rate()
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
	
	strURL="find_a2_rate.php?ref_id="+document.form.txt_ref_id.value+"&sno_a1="+document.form.cmb_a2_item.value+"&cost="+document.form.txt_cost.value+"&material="+document.form.cmb_a2_material.value;
	//alert(strURL)
	
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			//document.write(data)
			if(data!='*')
			{
				//alert(data)
				document.form.txt_rate_a2.value=data;
			}
	   }
   }
	xmlHttp.send(strURL);	
}


function func_percentage()
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
	
	strURL="find_a2_percentage.php?ref_id="+document.form.txt_ref_id.value+"&sno_a1="+document.form.cmb_a2_item.value+"&cost="+document.form.txt_cost.value+"&material="+document.form.cmb_a2_material.value;
	//alert(strURL)
	
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			//document.write(data)
			if(data!='*')
			{
				//alert(data)
				document.form.txt_rate_a2.value=data;
			}
	   }
   }
	xmlHttp.send(strURL);	
}


function func_hour()
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
	
	strURL="find_a2_hour.php?material="+document.form.cmb_a2_material.value;
	//alert(strURL)
	
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			//document.write(data)
			if(data!='*')
			{
				//alert(data)
				document.form.txt_rate_a2.value=data;
			}
	   }
   }
	xmlHttp.send(strURL);	
}

function func_value()
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
	
	strURL="find_route_indicator.php?cost="+document.form.txt_cost.value;
	//alert(strURL)
	
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			//document.write(data)
			if(data!='*')
			{
				//alert(data)
				document.form.txt_rate_a2.value=data;
			}
	   }
   }
	xmlHttp.send(strURL);	
}


function func_unit()
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
	
	strURL="find_a2_hour.php?material="+document.form.cmb_a2_material.value;
	//alert(strURL)
	
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			//document.write(data)
			if(data!='*')
			{
				//alert(data)
				document.form.txt_rate_a2.value=data;
			}
	   }
   }
	xmlHttp.send(strURL);	
}


function func_amount_a2()
{
	if(type_int==1)
	{
		var amount_a2;
		amount_a2=(parseFloat(document.form.txt_rate_a2.value)*parseFloat(document.form.txt_quantity_a2.value));
		document.form.txt_amount_a2.value=(amount_a2.toFixed(2));
	}
	if(type_int==2)
	{
		var amount_a2;
		amount_a2=(parseFloat(document.form.txt_rate_a2.value)*parseFloat(document.form.txt_quantity_a2.value))/100;
		document.form.txt_amount_a2.value=(amount_a2.toFixed(2));
	}
	if(type_int==4)
	{
		var amount_a2;
		amount_a2=(parseFloat(document.form.txt_rate_a2.value)*parseFloat(document.form.txt_quantity_a2.value));
		document.form.txt_amount_a2.value=(amount_a2.toFixed(2));
	}
	if(type_int==5)
	{
		var amount_a2;
		amount_a2=parseFloat(document.form.txt_rate_a2.value)*parseFloat(document.form.txt_quantity_a2.value);
		document.form.txt_amount_a2.value=(amount_a2.toFixed(2));
	}
	/*if(type_int==3)
	{
		var amount_a2;
		amount_a2=((parseFloat(document.form.txt_rate_a2.value)*parseFloat(document.form.txt_quantity_a2.value))*parseFloat(document.form.txt_cost.value))/1000;
		document.form.txt_amount_a2.value=(amount_a2.toFixed(2));
	}*/
}

function func_hour_amount_a2()
{
	if(type_int==3)
	{
		var amount_a2,rate_a2,quantity_a2,cost,hour_quantity_a2;
		rate_a2=parseFloat(document.form.txt_rate_a2.value);
		quantity_a2=parseFloat(document.form.txt_quantity_a2.value);
		//cost=parseFloat(document.form.txt_cost.value);
		hour_quantity_a2=parseFloat(document.form.txt_hour_quantity_a2.value);
		amount_a2=(rate_a2*quantity_a2*hour_quantity_a2);
		//amount_a2=((parseFloat(document.form.txt_rate_a2.value)*parseFloat(document.form.txt_quantity_a2.value))*parseFloat(document.form.txt_cost.value))/(parseFloat(document.form.txt_hour_quantity_a2.value));
		document.form.txt_amount_a2.value=(amount_a2.toFixed(2));
	}
}


//.......Multiple  Row Add Function........//
var add_row_a2=2;
var prev_edit_row_a2=0
function addrow_a2()
{
	var new_row_a2=document.getElementById("tab_a2_material").insertRow(add_row_a2);
	new_row_a2.setAttribute("id","row_a2_" +add_row_a2)
	new_row_a2.className="labelcenter"
	
	if(document.form.cmb_a2_material.value=='Select')
	{
		alert("Please Select the Description")
		document.form.cmb_a2_material.focus();
		return false
	}
	
	/*if(document.form.cmb_a2_item.value=='Select')
	{
		alert("Please Select the A1 Materials")
		document.form.cmb_a2_item.focus();
		return false
	}*/
	

	var c1=new_row_a2.insertCell(0);
		c1.align="left";
	var c2=new_row_a2.insertCell(1);
		c2.align="center";
	var c3=new_row_a2.insertCell(2);
		c3.align="right";
	var c4=new_row_a2.insertCell(3);
		c4.align="center";
	var c5=new_row_a2.insertCell(4);
		c5.align="center";
	var c6=new_row_a2.insertCell(5);
		c6.align="right";
	var c7=new_row_a2.insertCell(6);
		c7.align="center";

	c1.innerText=document.form.cmb_a2_material.value;
	
	if(document.form.cmb_a2_item.value=='Select')
		c2.innerText='-';
	else
		c2.innerText=document.form.cmb_a2_item.value;
		
	if(document.form.txt_rate_a2.value=='')
		c3.innerText='-';
	else
		c3.innerText=document.form.txt_rate_a2.value;

	if(document.form.txt_quantity_a2.value=='')
		c4.innerText='-';
	else
		c4.innerText=document.form.txt_quantity_a2.value;
	
	if(document.form.txt_hour_quantity_a2.value=='')
		c5.innerText='-';
	else
		c5.innerText=document.form.txt_hour_quantity_a2.value;

	c6.innerText=document.form.txt_amount_a2.value;

	//c6.innerHTML="<input type='button' name='btn_edit_a2_"+add_row_a2+"' id='btn_edit_a2_"+add_row_a2+"' value='Edit' onClick=editrow_a2("+add_row_a2+",'n')>&nbsp;&nbsp;&nbsp;&nbsp;<input type='button'  name='btn_del_a2_"+add_row_a2+"'  id='btn_del_a2_"+add_row_a2+"' value='Delete' onClick=delrow_a2("+add_row_a2+")>";
	c7.innerHTML="<input type='button'  name='btn_del_a2_"+add_row_a2+"'  id='btn_del_a2_"+add_row_a2+"' value='Delete' onClick=delrow_a2("+add_row_a2+")>";
	
	var hide_values="";
	hide_values="<input type='hidden' value='"+c1.innerText+"' name='cmb_a2_material"+add_row_a2+"' id='cmb_a2_material"+add_row_a2+"' >";
	hide_values+="<input type='hidden' value='"+c2.innerText+"' name='cmb_a2_item"+add_row_a2+"' id='cmb_a2_item"+add_row_a2+"' >";
	hide_values+="<input type='hidden' value='"+c3.innerText+"' name='txt_rate_a2"+add_row_a2+"' id='txt_rate_a2"+add_row_a2+"' >";
	hide_values+="<input type='hidden' value='"+c4.innerText+"' name='txt_quantity_a2"+add_row_a2+"' id='txt_quantity_a2"+add_row_a2+"' >";
	hide_values+="<input type='hidden' value='"+c5.innerText+"' name='txt_hour_quantity_a2"+add_row_a2+"' id='txt_hour_quantity_a2"+add_row_a2+"' >";
	hide_values+="<input type='hidden' value='"+c6.innerText+"' name='txt_amount_a2"+add_row_a2+"' id='txt_amount_a2"+add_row_a2+"' >";
	
	document.getElementById("add_hidden_a2").innerHTML=document.getElementById("add_hidden_a2").innerHTML+hide_values;
	
	if(document.getElementById("add_set_a2").value=="")
		document.getElementById("add_set_a2").value=add_row_a2;
	else
		document.getElementById("add_set_a2").value=document.getElementById("add_set_a2").value +"."+add_row_a2;
		
	var tot_amt_a2=0;
	for(x=2; x<=add_row_a2;x++)
	{
		tot_amt_a2+=parseFloat(document.getElementById("txt_amount_a2"+x).value)
	}
	//(x.toFixed(2))
	document.getElementById("txt_total_amount_a2").value=(tot_amt_a2.toFixed(2));
	
	cleartxt_a2();
	add_row_a2++;	
	document.getElementById("cmb_a2_item").disabled=false;
}

//.......Multiple  Row Delete Function........//

function delrow_a2(rownum)
{
	var src_row=(rownum+1)
	var tar_row=rownum
	var noofadd=(add_row_a2-1)
	
	var total_a2=document.getElementById("txt_total_amount_a2").value;
	var tot_amt_a2=total_a2-document.getElementById("txt_amount_a2"+rownum).value;
	//alert(rownum)
	document.getElementById("txt_total_amount_a2").value=(tot_amt_a2.toFixed(2));
	
	for(x=rownum; x<noofadd; x++)
	{
		document.getElementById("cmb_a2_material"+tar_row).value=document.getElementById("cmb_a2_material"+src_row).value
		document.getElementById("cmb_a2_item"+tar_row).value=document.getElementById("cmb_a2_item"+src_row).value
		document.getElementById("txt_rate_a2"+tar_row).value=document.getElementById("txt_rate_a2"+src_row).value
		document.getElementById("txt_quantity_a2"+tar_row).value=document.getElementById("txt_quantity_a2"+src_row).value
		document.getElementById("txt_hour_quantity_a2"+tar_row).value=document.getElementById("txt_hour_quantity_a2"+src_row).value
		document.getElementById("txt_amount_a2"+tar_row).value=document.getElementById("txt_amount_a2"+src_row).value
		
		tar_row++;
		src_row++;
		
		var trow=document.getElementById("tab_a2_material").rows[x].cells;
		var srow=document.getElementById("tab_a2_material").rows[x+1].cells;
		
		trow[0].innerText=srow[0].innerText
		trow[1].innerText=srow[1].innerText
		trow[2].innerText=srow[2].innerText
		trow[3].innerText=srow[3].innerText
		trow[4].innerText=srow[4].innerText
		trow[5].innerText=srow[5].innerText
	}
		
	document.getElementById("cmb_a2_material"+tar_row).outerHTML=""  
	document.getElementById("cmb_a2_item"+tar_row).outerHTML=""  
	document.getElementById("txt_rate_a2"+tar_row).outerHTML=""  
	document.getElementById("txt_quantity_a2"+tar_row).outerHTML=""
	document.getElementById("txt_hour_quantity_a2"+tar_row).outerHTML=""
	document.getElementById("txt_amount_a2"+tar_row).outerHTML=""  
  	
	document.getElementById('tab_a2_material').deleteRow(noofadd)
		
	document.getElementById("add_set_a2").value="";
		
	for(x=2; x<noofadd; x++)
	{
		if (document.getElementById("add_set_a2").value=="" )
			document.getElementById("add_set_a2").value=x;
		else
			document.getElementById("add_set_a2").value+=("."+x);
	}
	add_row_a2=noofadd		
}


function cleartxt_a2()
{
	document.getElementById("cmb_a2_material").value="Select";
	document.getElementById("cmb_a2_item").disabled=false;
	
	document.getElementById("cmb_a2_item").value="Select";
	
	document.getElementById("txt_rate_a2").value="";
	//document.getElementById("txt_rate_a2").disabled=false;
	
	document.getElementById("txt_quantity_a2").value="";
	//document.getElementById("txt_quantity_a2").disabled=false;
	
	document.getElementById("txt_hour_quantity_a2").value="";
	document.getElementById("txt_hour_quantity_a2").disabled=false;
	
	document.getElementById("cmb_a2_item").disabled=false;	
	
	document.getElementById("txt_amount_a2").value="";
}

/********************* End : A2 Material *********************/


/********************* Start : B Labour *********************/

function func_labour()
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
	
	strURL="find_labour_amount.php?hr_desc="+document.form.cmb_labour.value;
	xmlHttp.open('POST', strURL, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.onreadystatechange = function()
	{
		if (xmlHttp.readyState == 4)
		{
			data=xmlHttp.responseText
			//document.write(data)
			if(data!='*')
			{
				//alert(data)
				document.form.txt_rate_b.value=data;
			}
	   }
   }
	xmlHttp.send(strURL);	
}

function func_labour_amt()
{
	document.form.txt_amount_b.value=document.form.txt_rate_b.value*document.form.txt_days_hours.value
}


//.......Multiple  Row Add Function........//
var add_row_b=2;
var prev_edit_row_b=0
function addrow_b()
{
	var new_row_b=document.getElementById("tab_b_labour").insertRow(add_row_b);
	new_row_b.setAttribute("id","row_b_" +add_row_b)
	new_row_b.className="labelcenter"
	
	if(document.form.cmb_labour.value=='Select')
	{
		alert("Please Select the Labour")
		document.form.cmb_labour.focus();
		return false
	}
	
	x=alltrim(document.form.txt_days_hours.value);
	if(x.length==0)
	{
		alert("Please Select the No. of Days")
		document.form.txt_days_hours.value="";
		document.form.txt_days_hours.focus();
		return false
	}

	var c1=new_row_b.insertCell(0);
		c1.align="left";
	var c2=new_row_b.insertCell(1);
		c2.align="right";
	var c3=new_row_b.insertCell(2);
		c3.align="center";
	var c4=new_row_b.insertCell(3);
		c4.align="right";
	var c5=new_row_b.insertCell(4);
		c5.align="center";
		
	c1.innerText=document.form.cmb_labour.value;
	c2.innerText=document.form.txt_rate_b.value;
	c3.innerText=document.form.txt_days_hours.value;
	c4.innerText=document.form.txt_amount_b.value;

	//c5.innerHTML="<input type='button' name='btn_edit_b_"+add_row_b+"' id='btn_edit_b_"+add_row+"' value='Edit' onClick=editrow_b("+add_row_b+",'n')>&nbsp;&nbsp;&nbsp;&nbsp;<input type='button'  name='btn_del_b_"+add_row_b+"'  id='btn_del_b_"+add_row_b+"' value='Delete' onClick=delrow_b("+add_row_b+")>";
	c5.innerHTML="<input type='button'  name='btn_del_b_"+add_row_b+"'  id='btn_del_b_"+add_row_b+"' value='Delete' onClick=delrow_b("+add_row_b+")>";
	
	var hide_values="";
	hide_values="<input type='hidden' value='"+c1.innerText+"' name='cmb_labour"+add_row_b+"' id='cmb_labour"+add_row_b+"' >";
	hide_values+="<input type='hidden' value='"+c2.innerText+"' name='txt_rate_b"+add_row_b+"' id='txt_rate_b"+add_row_b+"' >";
	hide_values+="<input type='hidden' value='"+c3.innerText+"' name='txt_days_hours"+add_row_b+"' id='txt_days_hours"+add_row_b+"' >";
	hide_values+="<input type='hidden' value='"+c4.innerText+"' name='txt_amount_b"+add_row_b+"' id='txt_amount_b"+add_row_b+"' >";
	
	document.getElementById("add_hidden_b").innerHTML=document.getElementById("add_hidden_b").innerHTML+hide_values;
	
	if(document.getElementById("add_set_b").value=="")
		document.getElementById("add_set_b").value=add_row_b;
	else
		document.getElementById("add_set_b").value=document.getElementById("add_set_b").value +"."+add_row_b;
		
	var tot_amt_b=0;
	for(x=2; x<=add_row_b;x++)
	{
		tot_amt_b+=parseFloat(document.getElementById("txt_amount_b"+x).value)
	}
	document.getElementById("txt_total_amount_b").value=(tot_amt_b.toFixed(2));
	
	cleartxt_b();
	add_row_b++;	
}

//.......Multiple  Row Delete Function........//

function delrow_b(rownum)
{
	var src_row=(rownum+1)
	var tar_row=rownum
	var noofadd=(add_row_b-1)
	
	var total_b=document.getElementById("txt_total_amount_b").value;
	var tot_amt_b=total_b-document.getElementById("txt_amount_b"+rownum).value;
	//alert(tot_amt)
	document.getElementById("txt_total_amount_b").value=(tot_amt_b.toFixed(2));
	
	for(x=rownum; x<noofadd; x++)
	{
		document.getElementById("cmb_labour"+tar_row).value=document.getElementById("cmb_labour"+src_row).value
		document.getElementById("txt_rate_b"+tar_row).value=document.getElementById("txt_rate_b"+src_row).value
		document.getElementById("txt_days_hours"+tar_row).value=document.getElementById("txt_days_hours"+src_row).value
		document.getElementById("txt_amount_b"+tar_row).value=document.getElementById("txt_amount_b"+src_row).value
	
		tar_row++;
		src_row++;
		
		var trow=document.getElementById("tab_b_labour").rows[x].cells;
		var srow=document.getElementById("tab_b_labour").rows[x+1].cells;
		
		trow[0].innerText=srow[0].innerText
		trow[1].innerText=srow[1].innerText
		trow[2].innerText=srow[2].innerText
		trow[3].innerText=srow[3].innerText
	}
		
	document.getElementById("cmb_labour"+tar_row).outerHTML=""  
	document.getElementById("txt_rate_b"+tar_row).outerHTML=""  
	document.getElementById("txt_days_hours"+tar_row).outerHTML=""
	document.getElementById("txt_amount_b"+tar_row).outerHTML=""  
  	
	document.getElementById('tab_b_labour').deleteRow(noofadd)
		
	document.getElementById("add_set_b").value="";
		
	for(x=2; x<noofadd; x++)
	{
		if (document.getElementById("add_set_b").value=="" )
			document.getElementById("add_set_b").value=x;
		else
			document.getElementById("add_set_b").value+=("."+x);
	}
	add_row_b=noofadd		
}


function cleartxt_b()
{
	document.getElementById("cmb_labour").value="Select";
	document.getElementById("txt_rate_b").value="";
	document.getElementById("txt_days_hours").value="";
	document.getElementById("txt_amount_b").value="";
}

/********************* End : B Labour *********************/


</script>

<body bgcolor="c5d1dc" oncontextmenu="return false">
<form name="form" method="post">

<table width="925" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr class="heading">
		<td width="25" height="28" background="Title bar/Titlebar_Left_Piece.jpg">&nbsp;</td>
		<td width="864" height="28" background="Title bar/Titlebar_Centre_Piece.jpg" align="left">Data Sheet - Internal (Wiring) -Create </td>
		<td width="36" height="28" background="Title bar/Titlebar_Right_Piece.jpg">&nbsp;</td>
	</tr>
</table>

<table width="925" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
	<tr><td colspan="4">&nbsp;</td></tr>
	
	<tr>
		<td border="0" cellpadding="0" cellspacing="0" colspan="4"> 
			<table width="875" border="0" cellpadding="0" cellspacing="0" class="color3" align="center">
			
				<tr><td colspan="4">&nbsp;</td></tr>
				
				<tr>
					<td width="65">&nbsp;</td>
					<td class="labelbold" width="119">Reference ID</td>
					<td colspan="2">
						<input type="text" name="txt_ref_id" id="txt_ref_id" value="<?php if ($_GET['ref_id']!='') { echo $_GET['ref_id']; } else { echo '';}?>" size="5" class="labelfield" onBlur="func_ref_id()" />
						<img src="Buttons/search.gif" align="absmiddle" onClick="javascript:window.open('find_datasheet.php?page=internal_wiring_create.php&type=internal_wiring','','TOOLBAR=NO,RESIZABLE=NO,SCROLLBARS=YES,HEIGHT=400,WIDTH=600,LEFT=400,TOP=50')"  />
					</td>
				</tr>
				
				<tr><td colspan="4">&nbsp;</td></tr>
				
				<?php 
				/*if ($_GET['ref_id']=='') 
				{
					?>
					<tr>
						<td width="65">&nbsp;</td>
						<td class="labelbold" width="119">Reference ID</td>
						<td colspan="2">
							<input type="text" name="txt_ref_id" id="txt_ref_id" value="" size="5" class="labelfield" onBlur="func_ref_id()" readonly="" />
							<img src="Buttons/search.gif" align="absmiddle" onClick="javascript:window.open('find_datasheet.php?page=internal_wiring_create.php','','TOOLBAR=NO,RESIZABLE=NO,SCROLLBARS=YES,HEIGHT=400,WIDTH=600,LEFT=400,TOP=50')"  />
						</td>
					</tr>
				
					<tr><td colspan="4">&nbsp;</td></tr>
					<?php
				}*/
				?>
				<!--<input type="hidden" name="txt_ref_id" id="txt_ref_id" value="" size="5" class="labelfield" />-->
				
				<tr>
					<td width="65">&nbsp;</td>
					<td width="119" class="labelbold">Cost for</td>
					<td width="69"><input type="text" name="txt_cost" id="txt_cost" value="" size="5" class="labelfield" /></td>
					<td width="672">
						<?php
						$sql_unit="select * from unit";
						$rs_unit=mysqli_query($dbConn,$sql_unit,$conn);
						?>
						<select class="text" name="cmb_unit" ID="cmb_unit">
							<option value="Select">Select</option>
							<?php
							while($row_unit=mysqli_fetch_assoc($rs_unit))
							{
								?>
								<option value="<?php echo $row_unit['unit_name'];?>"><?php echo $row_unit['unit_name'];?></option>
								<?php
							}
							?>
						</select>
						<script language="javascript" type="text/javascript">
							list_search('cmb_unit',<?php echo "'" . '' . "'";?>)
						</script>
					</td>
				</tr>
				
				<tr><td colspan="4">&nbsp;</td></tr>
				

				<tr>
					<td width="50">&nbsp;</td>
					<td class="labelbold" width="138">Group 1</td>
					<td colspan="2">
						<?php
						$sql_group1="select * from group_datasheet where char_length( group_id ) = '2' and
										type='internal_wiring' order by group_id";
						//echo $sql_group1;				
						$rs_group1=mysqli_query($dbConn,$sql_group1,$conn);
						?>
						<select class="text" style="width:400px;height:21px;" name="cmb_group1" ID="cmb_group1" onBlur="func_group2()">
							<option value="Select">Select</option>
							<option value="New">New</option>
							<?php
							while($row=mysqli_fetch_assoc($rs_group1))
							{
								?>
								<option value="<?php echo $row['group_id'];?>"><?php echo $row['group_desc'];?></option>
								<?php
							}
							?>
						</select>
						<script language="javascript" type="text/javascript">
							list_search('cmb_group1',<?php echo "'" . $group1 . "'";?>)
						</script>
					</td>
				</tr>
				
				<tr style="display:none" id="new_group1">
					<td width="50">&nbsp;</td>
					<td class="labelbold" width="138">&nbsp;</td>
					<td colspan="2"><input type="text" name="txt_group1" id="txt_group1" value="" style="width:400px;" class="label" /></td>
				</tr>
				
				<tr><td colspan="4">&nbsp;</td></tr>
				
				<tr>
					<td width="50">&nbsp;</td>
					<td class="labelbold" width="138">Group 2</td>
					<td colspan="2">
						<select name="cmb_group2" id="cmb_group2" class="labelfield" style="width:400px;height:21px;" onBlur="func_group3()"> 
							<option value="Select">Select</option>
							<option value="New">New</option>
							<?php
							if ($_GET['ref_id']!='')
							{
								?>
								<option value="<?php echo $group2;?>"><?php echo $group2_desc;?></option>
								<?php
							}
							?>
						</select>
						<script language="javascript" type="text/javascript">
							list_search('cmb_group2',<?php echo "'" . $group2 . "'";?>)
						</script>
					</td>
				</tr>
				
				<tr style="display:none" id="new_group2">
					<td width="50">&nbsp;</td>
					<td class="labelbold" width="138">&nbsp;</td>
					<td colspan="2"><input type="text" name="txt_group2" id="txt_group2" value="" style="width:400px;" class="label" /></td>
				</tr>
				
				<tr><td colspan="4">&nbsp;</td></tr>
				
				<!--<tr>
					<td width="50">&nbsp;</td>
					<td class="labelbold" width="138" valign="top">Group 3 Description</td>
					<td colspan="2"><textarea name="txt_group3_desc" id="txt_group3_desc" class="labelfield" rows="5" cols="87"><?php if ($_GET['ref_id']!='') echo @mysqli_result($rs_master,0,'group3_description'); else echo '';?></textarea></td>
				</tr>
				
				<tr><td colspan="4">&nbsp;</td></tr>-->
				
				<tr>
					<td width="50">&nbsp;</td>
					<td class="labelbold" width="138">Group 3</td>
					<td colspan="2">
						<select name="cmb_group3" id="cmb_group3" class="labelfield" style="width:700px;height:21px;" onBlur="func_group3_desc()"> 
							<option value="Select">Select</option>
							<option value="New">New</option>
							<?php
							if ($_GET['ref_id']!='')
							{
								?>
								<option value="<?php echo $group3;?>"><?php echo $group3_desc;?></option>
								<?php
							}
							?>
						</select>
						<script language="javascript" type="text/javascript">
							list_search('cmb_group3',<?php echo "'" . $group3 . "'";?>)
						</script>
					</td>
				</tr>
				
				<tr style="display:none" id="new_group3">
					<td width="50">&nbsp;</td>
					<td class="labelbold" width="138">&nbsp;</td>
					<td colspan="2"><textarea name="txt_group3" id="txt_group3" class="labelfield" rows="5" cols="87"></textarea></td>
				</tr>

				<tr><td colspan="4">&nbsp;</td></tr>
				
				<tr style="display:" id="desc_group3">
					<td width="50">&nbsp;</td>
					<td class="labelbold" width="138" valign="top">Group 3 Description</td>
					<td colspan="2"><textarea name="txt_group3_desc" id="txt_group3_desc" class="labelfield" rows="5" cols="87" readonly="readonly"><?php if ($_GET['ref_id']!='') echo $group3_desc; else echo '';?></textarea></td>
				</tr>
				
				<tr><td colspan="4">&nbsp;</td></tr>
				
				<tr>
					<td width="65">&nbsp;</td>
					<td class="labelbold" width="119" valign="top">Group 4 Description</td>
					<td colspan="2"><textarea name="txt_group4_desc" id="txt_group4_desc" class="labelfield" rows="5" cols="87"><?php if ($_GET['ref_id']!='') { echo @mysqli_result($rs_master,0,'group4_description'); } else { echo '';}?></textarea></td>
				</tr>
				
				<tr><td colspan="4">&nbsp;</td></tr>
			
			</table>
		</td>
	</tr>
	
	<tr><td colspan="4">&nbsp;</td></tr>
	
	<tr>
		<td width="64">&nbsp;</td>
		<td width="859" colspan="3" class="labelbold"><font color="#990000">A1 Materials</font></td>
	</tr>
	
	<tr>
		<td border="0" cellpadding="0" cellspacing="0" colspan="4"> 
			<table width="875" border="1" cellpadding="0" cellspacing="0" class="color2" align="center" id="tab_a1_material">
				<tr height="25">
					<td class="labelboldcenter">S.No</td>
					<td class="labelboldcenter">Description</td>
					<td class="labelboldcenter">Item ID</td>
					<td class="labelboldcenter">Rate</td>
					<td class="labelboldcenter">Unit</td>
					<td class="labelboldcenter">Type</td>
					<td class="labelboldcenter">Quantity</td>
					<td class="labelboldcenter">Amount</td>
					<td class="labelboldcenter">&nbsp;</td>
				</tr>
				
				<?php
				if(@mysqli_result($rs_select_a1,0,'ref_id')!='') 
				{
					$sql_max_sno="select max(sno) as sno from datasheet_a1_details where ref_id='" . @mysqli_result($rs_select_a1,0,'ref_id') . "'";
					$rs_max_sno=mysqli_query($dbConn,$sql_max_sno,$conn);
					$sno=@mysqli_result($rs_max_sno,0,'sno')+1;
				}
				else
				{
					$sno=1;
				}
				?>
				
				<tr>
					<td class="labelcenter">&nbsp;<input type="text" size="2" name="txt_sno_a1" id="txt_sno_a1" value="<?php echo $sno; ?>" class="labelcenter" readonly="" />&nbsp;</td>
					<td class="labelcenter" nowrap="nowrap">&nbsp;
						<input type="text" class="labelfield" size="25" name="txt_desc" id="txt_desc" value="" readonly="" />
						<img src="Buttons/search.gif" align="absmiddle" onClick="javascript:window.open('find_item.php','','TOOLBAR=NO,RESIZABLE=NO,SCROLLBARS=YES,HEIGHT=450,WIDTH=900,LEFT=80,TOP=50')"  />
						<!--<input type="button" name="btn_find" id="btn_find" value="Find" onclick="javascript:window.open('find_item.php','','TOOLBAR=NO,RESIZABLE=NO,SCROLLBARS=YES,HEIGHT=350,WIDTH=600,LEFT=400,TOP=50')" title="Click 'Find' to select Address" />&nbsp;-->
					</td>
					<td class="labelcenter">&nbsp;<input type="text" class="labelfield" size="9" name="txt_item_id" id="txt_item_id" value="" readonly="" />&nbsp;</td>
					<td class="labelcenter">&nbsp;<input type="text" class="labelfieldright" size="5" name="txt_rate" id="txt_rate" value="" readonly="" />&nbsp;</td>
					<td class="labelcenter">&nbsp;<input type="text" class="labelfield" size="5" name="txt_unit" id="txt_unit" value="" readonly="" />&nbsp;</td>
					<td class="labelcenter">&nbsp;
						<select name="cmb_SI" id="cmb_SI" class="labelfield">
							<option value="Select">Select</option>
							<option value="Supply">Supply</option>
							<option value="Inst Variable">Inst Variable</option>
							<option value="Inst Fixed">Inst Fixed</option>
						</select>&nbsp;					
					</td>
				  	<td class="labelcenter" nowrap="nowrap">&nbsp;
						<input type="text" class="labelfield" size="5" name="txt_quantity" id="txt_quantity" value="" onBlur="func_amount()" />
						<!--<img src="Buttons/convert.gif" align="absmiddle" onClick="javascript:window.open('compaingshapes.php?rate='+document.form.txt_rate.value,'','TOOLBAR=NO,RESIZABLE=NO,SCROLLBARS=YES,HEIGHT=350,WIDTH=600,LEFT=400,TOP=50')"  />-->
					</td>
					<td class="labelcenter">&nbsp;<input type="text" class="labelfieldright" size="8" name="txt_amount" id="txt_amount" value="" readonly="" />&nbsp;</td>
					<td class="labelcenter">&nbsp;	
						<input type="button" name="btn_add"   id="btn_add" value="Add" onClick="addrow()" />&nbsp;&nbsp;
						<input type="button" name="btn_clear" id="btn_clear" value="Clear" onClick="cleartxt()"/>&nbsp;&nbsp;	
						<span id="add_hidden"></span>
						<input type="hidden" value="" name="add_set_a1" id="add_set_a1"/>	
					</td>
					
					<?php
					if(@mysqli_result($rs_select_a1,0,'ref_id')!='') 
					{
						$row=2;
						$rs_select_a1=@mysqli_query($dbConn,$sql_select_a1,$conn);
						while($result=@mysqli_fetch_assoc($rs_select_a1))
						{
							$sql_item="select * from item_master where item_id ='" . $result['item_id'] . "'";
							$rs_item=mysqli_query($dbConn,$sql_item,$conn);
							//echo $sql_item.'</br>';
							
							$item_basic_price=@mysqli_result($rs_item,0,'price')*@mysqli_result($rs_item,0,'factor');
							$item_x=($item_basic_price*@mysqli_result($rs_item,0,'ED'))/100;
							$item_y=($item_x*@mysqli_result($rs_item,0,'CESS'))/100;
							$item_sub_total1=$item_basic_price+$item_x+$item_y;
							$item_VAT=($item_sub_total1*@mysqli_result($rs_item,0,'VAT'))/100;
							$item_CST=($item_sub_total1*@mysqli_result($rs_item,0,'CST'))/100;
							$item_amount=$item_sub_total1+$item_VAT+$item_CST;
							$item_packing=($item_amount*@mysqli_result($rs_item,0,'packing'))/100;
							$item_freight=($item_amount*@mysqli_result($rs_item,0,'freight'))/100;
							$item_sub_total2=$item_amount+$item_packing+$item_freight;
							$item_insurance_charge=($item_sub_total2*@mysqli_result($rs_item,0,'insurance_charge'))/100;
							$item_total=$item_sub_total2+$item_insurance_charge;
							$item_total=number_format($item_total, 2, '.', '');	
							//$item_total=round($item_total);	
							
							$amount=$item_total*$result['quantity'];
							$total_a1_amount+=$amount;
							
							echo "<tr id='row_" . $row . "' >";
							echo "<td class='labelcenter'>" . $result['sno'] . "</td>";
							echo "<td class='label'>" . @mysqli_result($rs_item,0,'item_desc') . "</td>";
							echo "<td class='labelcenter'>" . $result['item_id'] . "</td>";
							echo "<td class='labelright'>" . $item_total . "</td>";
							echo "<td class='labelcenter'>" . @mysqli_result($rs_item,0,'unit') . "</td>";
							echo "<td class='labelcenter'>" . $result['SI'] . "</td>";
							echo "<td class='labelcenter'>" . $result['quantity'] . "</td>";
							echo "<td class='labelright'>" . $amount . "</td>";
							
							echo "<td nowrap>&nbsp;&nbsp;<input type='button' name='btn_edit_" . $row .  "'  id='btn_edit_" . $row .  "' value='Edit' onClick=editrow(" . $row . ",'n')>&nbsp;&nbsp;<input type='button' name='btn_del_" . $row .  "' id='btn_del_" . $row .  "' value='Delete' onClick=delrow(".$row.") ></td>";
							
							echo "<input type='hidden' value='" . $result['sno'] . "' name='txt_sno_a1". $row ."' id='txt_sno_a1" . $row . "'/>";
							echo "<input type='hidden' value='" . @mysqli_result($rs_item,0,'item_desc') . "' name='txt_desc". $row ."' id='txt_desc" . $row . "'/>";
							echo "<input type='hidden' value='" . $result['item_id'] . "' name='txt_item_id". $row ."' id='txt_item_id" . $row . "'/>";
							echo "<input type='hidden' value='" . $item_total . "' name='txt_rate". $row ."' id='txt_rate" . $row . "'/>";
							echo "<input type='hidden' value='" . @mysqli_result($rs_item,0,'unit') . "' name='txt_unit". $row ."' id='txt_unit" . $row . "'/>";
							echo "<input type='hidden' value='" . $result['SI'] . "' name='cmb_SI". $row ."' id='cmb_SI" . $row . "'/>";
							echo "<input type='hidden' value='" . $result['quantity'] . "' name='txt_quantity". $row ."' id='txt_quantity" . $row . "'/>";
							echo "<input type='hidden' value='" . $amount . "' name='txt_amount". $row ."' id='txt_amount" . $row . "'/>";
							echo "</tr>";
							
							if ($row==2)
							{
								?>
								<script language="javascript" type="text/javascript" >
								document.getElementById("add_set_a1").value=<?php echo $row ?>;
								</script>
								<?php		
							}
							else
							{
								?>
								<script language="javascript" type="text/javascript" >
								document.getElementById("add_set_a1").value=document.getElementById("add_set_a1").value+"."+<?php echo $row; ?>
								</script>
								<?php		
							}
							$row++;
						}
						?>
						<script language="javascript" type="text/javascript">
							add_row=<?php echo $row; ?>
						</script>
						<?php
					}	
					$total_a1_amount=number_format($total_a1_amount, 2, '.', '');	
					?>
				</tr>
				
				<tr>
					<td colspan="7" class="labelboldright">Total of A1&nbsp;&nbsp;</td>
					<td class="labelboldcenter"><input type="text" class="labelfieldright" size="8" name="txt_total_amount" id="txt_total_amount" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $total_a1_amount; } else { echo '';}?>" readonly="" /></td>
					<td class="labelboldcenter">
						<?php
						if($_GET['ref_id']!='')
						{
							echo '&nbsp;';
						}
						else
						{
							?>
							<input type="image" name="btn_save" id="btn_save" value="Save" src="Buttons/Save_Normal.jpg" onMouseOver="this.src='Buttons/Save_Over.jpg'" onMouseOut="this.src='Buttons/Save_Normal.jpg'" onClick="return validation()" />
							<?php
						}
						?>					
					</td>
				</tr>
			</table>
		</td>
	</tr>
	
	<tr><td colspan="4">&nbsp;</td></tr>
	
	<tr>
		<td border="0" cellpadding="0" cellspacing="0" colspan="4"> 
			<table width="875" border="0" class="color3" align="center" cellpadding="0" cellspacing="0">
				<tr><td>&nbsp;</td></tr>
				
				<tr>
					<td width="305">&nbsp;</td>
					<td width="273" nowrap="nowrap" class="label">Supply Cost for Fixed</td>
					<td width="104">&nbsp;</td>	
					<td width="124" nowrap="nowrap" class="label" valign="top"><input type="text" name="txt_supply_amt" id="txt_supply_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $supply_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
					<td width="77">&nbsp;</td>
				</tr>
				<input type="hidden" name="supply_items" id="supply_items" value="" />
				
				<tr><td colspan="5">&nbsp;</td></tr>
				
				<tr>
					<td width="305">&nbsp;</td>
					<td class="label" nowrap="nowrap">Over Head</td>
					<td class="label"><input type="text" name="txt_overhead_supply_per" id="txt_overhead_supply_per" value="<?php echo $overhead_percentage; ?>" size="2" class="labelfield" readonly="" />%</td>
					<td class="label" nowrap="nowrap"><input type="text" name="txt_overhead_supply_amt" id="txt_overhead_supply_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $overhead_supply_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
					<td width="77">&nbsp;</td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
				
				<tr>
					<td width="305">&nbsp;</td>
					<td class="label" nowrap="nowrap">Profit</td>
					<td class="label"><input type="text" name="txt_profit_supply_per" id="txt_profit_supply_per" value="<?php echo $profit_percentage; ?>" size="2" class="labelfield" readonly="" />%</td>
					<td class="label" nowrap="nowrap"><input type="text" name="txt_profit_supply_amt" id="txt_profit_supply_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $profit_supply_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
					<td width="77">&nbsp;</td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
				
				<tr>
					<td width="305">&nbsp;</td>
					<td class="labelbold" nowrap="nowrap">Sub total</td>
					<td>&nbsp;</td>
					<td class="label" nowrap="nowrap"><input type="text" name="txt_sub_supply_amt" id="txt_sub_supply_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $sub_supply_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
					<td width="77">&nbsp;</td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
				
				<tr>
					<td width="305">&nbsp;</td>
					<td class="label" nowrap="nowrap">WCT</td>
					<td class="label"><input type="text" name="txt_wct_supply_per" id="txt_wct_supply_per" value="<?php echo $wct_percentage; ?>" size="2" class="labelfield" readonly="" />%</td>
					<td class="label" nowrap="nowrap"><input type="text" name="txt_wct_supply_amt" id="txt_wct_supply_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $wct_supply_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
					<td width="77">&nbsp;</td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
				
				<tr>
					<td width="305">&nbsp;</td>
					<td class="labelbold" nowrap="nowrap">Supply Cost for Fixed</td>
					<td>&nbsp;</td>
					<td class="label" nowrap="nowrap"><input type="text" name="txt_total_supply_amt" id="txt_total_supply_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $total_supply_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
					<td width="77">&nbsp;</td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
			</table>	
		</td>
	</tr>
	
	<tr><td colspan="4">&nbsp;</td></tr>
	
	<tr>
		<td width="64">&nbsp;</td>
		<td class="labelbold" colspan="3"><font color="#990000">A2 Items</font></td>
	</tr>
	
	<tr>
		<td border="0" cellpadding="0" cellspacing="0" colspan="4"> 
			<table width="850" border="1" cellpadding="0" cellspacing="0" class="color2"align="center" id="tab_a2_material">
				<tr height="25">
					<td class="labelboldcenter">Description</td>
					<td class="labelboldcenter">A1 Materials</td>
					<td class="labelboldcenter">Rate</td>
					<td class="labelboldcenter">Qty/<br />%ge / Hr</td>
					<td class="labelboldcenter">Rate per<br />Quantity</td>
					<td class="labelboldcenter">Amount</td>
					<td class="labelboldcenter">&nbsp;</td>
				</tr>
				
				<tr>
					<td class="labelcenter">
						<?php 
						$sql_equip="select * from equip_master";
						//echo $sql_equip;
						$rs_equip=mysqli_query($dbConn,$sql_equip,$conn);
						if (($rs_equip)!="")
						{
							?>
							<select name="cmb_a2_material" id="cmb_a2_material" style="width:250px;height:21px;" class="labelfield">
								<option value="Select">Select</option>
								<?php
								while($rows = mysqli_fetch_assoc($rs_equip))
								{
									?>
									<option value="<?php echo $rows['description']; ?>"><?php echo $rows['description']; ?></option>
									<?php
								}
								?>
							</select>
							<?php
							}
						?>
					</td>
					
					<td class="labelcenter">
						<?php 
						$sql_items="select * from datasheet_a1_details where ref_id ='" . $_GET['ref_id'] . "' order by sno";
						//echo $sql_cno_code;
						$rs_items=mysqli_query($dbConn,$sql_items,$conn);
						if (($rs_items)!="")
						{
							?>
							<select name="cmb_a2_item" id="cmb_a2_item" onBlur="func_a2()" class="labelfield">
								<option value="Select">Select</option>
								<option value="All">All</option>
								<?php
								while($rows = mysqli_fetch_assoc($rs_items))
								{
									?>
									<option value="<?php echo $rows['sno']; ?>"><?php echo $rows['sno']; ?></option>
									<?php
								}
								?>
							</select>
							<?php
							}
						?>					
					</td>
					
					<td class="labelcenter">&nbsp;<input type="text" class="labelfieldright" size="6" name="txt_rate_a2" id="txt_rate_a2" value="" readonly="" />&nbsp;</td>
					<td class="labelcenter">&nbsp;<input type="text" class="labelfield" size="5" name="txt_quantity_a2" id="txt_quantity_a2" value="" onBlur="func_amount_a2()" /></td>
					
					<td class="labelcenter">&nbsp;
						<input type="text" class="labelfield" size="5" name="txt_hour_quantity_a2" id="txt_hour_quantity_a2" value="" onBlur="func_hour_amount_a2()" disabled="disabled" />
						<!--<span id="txt_image" style="display:none"><img src="Buttons/convert.gif" align="absmiddle" onClick="javascript:window.open('conversion.php?rate='+document.form.txt_rate_a2.value+'&quantity='+document.form.txt_quantity_a2.value+'&cost='+document.form.txt_cost.value,'','TOOLBAR=NO,RESIZABLE=NO,SCROLLBARS=YES,HEIGHT=200,WIDTH=600,LEFT=400,TOP=50')"  /></span>&nbsp;-->
					</td>
					<td class="labelcenter"><input type="text" class="labelfieldright" size="8" name="txt_amount_a2" id="txt_amount_a2" value="" readonly="" /></td>
					
					<td class="labelcenter">
						<input type="button" name="btn_add_a2"   id="btn_add_a2" value="Add" onClick="addrow_a2()" />&nbsp;&nbsp;
						<input type="button" name="btn_clear_a2" id="btn_clear_a2" value="Clear" onClick="cleartxt_a2()"/>
						<span id="add_hidden_a2"></span>
						<input type="hidden" value="" name="add_set_a2" id="add_set_a2"/>			
					</td>
					
					<?php
					$sql_select_a2="select * from datasheet_a2_details where ref_id='" . $_GET['ref_id'] . "' order by sno";
					$rs_select_a2=mysqli_query($dbConn,$sql_select_a2,$conn);
					
					if(@mysqli_result($rs_select_a2,0,'ref_id')!='') 
					{
						$row_a2=2;
						$rs_select_a2=@mysqli_query($dbConn,$sql_select_a2,$conn);
						while($result=@mysqli_fetch_assoc($rs_select_a2))
						{
							$sql_select_equip="select * from equip_master where equip_id='" . $result['equip_id'] . "'";
							//echo $sql_select_equip.'<br />';
							$rs_select_equip=mysqli_query($dbConn,$sql_select_equip,$conn);
							$equip_name=@mysqli_result($rs_select_equip,0,'equip_desc');
							
							$sql_select_a1_details="select * from datasheet_a1_details where ref_id='" . $_GET['ref_id'] . "'";
							$rs_select_a1_details=mysqli_query($dbConn,$sql_select_a1_details,$conn);
							//echo $sql_select_a1_details.'</br>';
							
							if($result['method']=='Rate')
							{
								$rate='';
								$quantity=$result['quantity'];
								$hour_quantity='-';
								$amount='';
								$sno_a1=$result['sno_a1'];
								if($result['sno_a1']=='All')
								{
									while($row=mysqli_fetch_array($rs_select_a1_details))
									{
										$sql_select_item_master="select *," . $equip_name . " as equip_desc from item_master 
																	where item_id='" . $row['item_id'] . "'";
										$rs_select_item_master=mysqli_query($dbConn,$sql_select_item_master,$conn);
										//echo $sql_select_item_master.'</br>';
		
										$item_basic_price=@mysqli_result($rs_select_item_master,0,'price')*@mysqli_result($rs_select_item_master,0,'factor');
										$item_x=($item_basic_price*@mysqli_result($rs_select_item_master,0,'ED'))/100;
										$item_y=($item_x*@mysqli_result($rs_select_item_master,0,'CESS'))/100;
										$item_sub_total1=$item_basic_price+$item_x+$item_y;
										$item_VAT=($item_sub_total1*@mysqli_result($rs_select_item_master,0,'VAT'))/100;
										$item_CST=($item_sub_total1*@mysqli_result($rs_select_item_master,0,'CST'))/100;
										$item_amount=$item_sub_total1+$item_VAT+$item_CST;
										$item_packing=($item_amount*@mysqli_result($rs_select_item_master,0,'packing'))/100;
										$item_freight=($item_amount*@mysqli_result($rs_select_item_master,0,'freight'))/100;
										$item_sub_total2=$item_amount+$item_packing+$item_freight;
										$item_insurance_charge=($item_sub_total2*@mysqli_result($rs_select_item_master,0,'insurance_charge'))/100;
										$item_total=$item_sub_total2+$item_insurance_charge;
										$item_total=number_format($item_total, 2, '.', '');	
										//$item_total=round($item_total);	
										
										$rate+=($item_total*@mysqli_result($rs_select_item_master,0,'equip_desc'))/100;
									}
									$rate=number_format($rate, 2, '.', '');
									$total=$rate*$result['quantity'];
									$amount=number_format($total, 2, '.', '');
								}
								else
								{
									$sql_select_sno_a1="select * from datasheet_a1_details where ref_id='" . $_GET['ref_id'] . "'
														and sno='" . $result['sno_a1'] . "'";
									$rs_select_sno_a1=mysqli_query($dbConn,$sql_select_sno_a1,$conn);
									//echo $sql_select_sno_a1.'</br>';
									
									$sql_select_item_master="select *," . $equip_name . " as equip_desc from item_master where 
																item_id='" . @mysqli_result($rs_select_sno_a1,0,'item_id') . "'";
									//echo $sql_select_item_master.'<br />';
									$rs_select_item_master=mysqli_query($dbConn,$sql_select_item_master,$conn);
		
									$item_basic_price=@mysqli_result($rs_select_item_master,0,'price')*@mysqli_result($rs_select_item_master,0,'factor');
									$item_x=($item_basic_price*@mysqli_result($rs_select_item_master,0,'ED'))/100;
									$item_y=($item_x*@mysqli_result($rs_select_item_master,0,'CESS'))/100;
									$item_sub_total1=$item_basic_price+$item_x+$item_y;
									$item_VAT=($item_sub_total1*@mysqli_result($rs_select_item_master,0,'VAT'))/100;
									$item_CST=($item_sub_total1*@mysqli_result($rs_select_item_master,0,'CST'))/100;
									$item_amount=$item_sub_total1+$item_VAT+$item_CST;
									$item_packing=($item_amount*@mysqli_result($rs_select_item_master,0,'packing'))/100;
									$item_freight=($item_amount*@mysqli_result($rs_select_item_master,0,'freight'))/100;
									$item_sub_total2=$item_amount+$item_packing+$item_freight;
									$item_insurance_charge=($item_sub_total2*@mysqli_result($rs_select_item_master,0,'insurance_charge'))/100;
									$item_total=$item_sub_total2+$item_insurance_charge;
									$item_total=number_format($item_total, 2, '.', '');	
									//$item_total=round($item_total);	
	
									$rate=($item_total*@mysqli_result($rs_select_item_master,0,'equip_desc'))/100;
									$rate=number_format($rate, 2, '.', '');
									$total=$rate*$result['quantity'];
									$amount=number_format($total, 2, '.', '');
								}
							}
							
							
							if($result['method']=='Percentage')
							{
								$rate='';
								$quantity=$result['quantity'];
								$hour_quantity='-';
								$amount='';
								$sno_a1=$result['sno_a1'];
								if($result['sno_a1']=='All')
								{
									while($row=mysqli_fetch_array($rs_select_a1_details))
									{
										$sql_select_item_master="select * from item_master where item_id='" . $row['item_id'] . "'";
										$rs_select_item_master=mysqli_query($dbConn,$sql_select_item_master,$conn);
										//echo $sql_select_item_master.'</br>';
										
										$item_basic_price=@mysqli_result($rs_select_item_master,0,'price')*@mysqli_result($rs_select_item_master,0,'factor');
										$item_x=($item_basic_price*@mysqli_result($rs_select_item_master,0,'ED'))/100;
										$item_y=($item_x*@mysqli_result($rs_select_item_master,0,'CESS'))/100;
										$item_sub_total1=$item_basic_price+$item_x+$item_y;
										$item_VAT=($item_sub_total1*@mysqli_result($rs_select_item_master,0,'VAT'))/100;
										$item_CST=($item_sub_total1*@mysqli_result($rs_select_item_master,0,'CST'))/100;
										$item_amount=$item_sub_total1+$item_VAT+$item_CST;
										$item_packing=($item_amount*@mysqli_result($rs_select_item_master,0,'packing'))/100;
										$item_freight=($item_amount*@mysqli_result($rs_select_item_master,0,'freight'))/100;
										$item_sub_total2=$item_amount+$item_packing+$item_freight;
										$item_insurance_charge=($item_sub_total2*@mysqli_result($rs_select_item_master,0,'insurance_charge'))/100;
										$item_total=$item_sub_total2+$item_insurance_charge;
										$item_total=number_format($item_total, 2, '.', '');	
										//$item_total=round($item_total);	
										
										$rate+=$item_total*$row['quantity'];
										$amount+=($item_total*$row['quantity']*$result['quantity'])/100;
									}
									$amount=number_format($amount, 2, '.', '');	
								}
								else
								{
									$sql_select_sno_a1_details="select * from datasheet_a1_details where ref_id='" . $_GET['ref_id'] . "' and
																	sno='" . $result['sno_a1'] . "'";
									$rs_select_sno_a1_details=mysqli_query($dbConn,$sql_select_sno_a1_details,$conn);
									//echo $sql_select_sno_a1_details.'</br>';
									
									$sql_select_item_master="select * from item_master where item_id='" . @mysqli_result($rs_select_sno_a1_details,0,'item_id') . "'";
									//echo $sql_select_item_master.'<br />';
									$rs_select_item_master=mysqli_query($dbConn,$sql_select_item_master,$conn);
									
									$item_basic_price=@mysqli_result($rs_select_item_master,0,'price')*@mysqli_result($rs_select_item_master,0,'factor');
									$item_x=($item_basic_price*@mysqli_result($rs_select_item_master,0,'ED'))/100;
									$item_y=($item_x*@mysqli_result($rs_select_item_master,0,'CESS'))/100;
									$item_sub_total1=$item_basic_price+$item_x+$item_y;
									$item_VAT=($item_sub_total1*@mysqli_result($rs_select_item_master,0,'VAT'))/100;
									$item_CST=($item_sub_total1*@mysqli_result($rs_select_item_master,0,'CST'))/100;
									$item_amount=$item_sub_total1+$item_VAT+$item_CST;
									$item_packing=($item_amount*@mysqli_result($rs_select_item_master,0,'packing'))/100;
									$item_freight=($item_amount*@mysqli_result($rs_select_item_master,0,'freight'))/100;
									$item_sub_total2=$item_amount+$item_packing+$item_freight;
									$item_insurance_charge=($item_sub_total2*@mysqli_result($rs_select_item_master,0,'insurance_charge'))/100;
									$item_total=$item_sub_total2+$item_insurance_charge;
									$item_total=number_format($item_total, 2, '.', '');	
									
									$rate=$item_total*@mysqli_result($rs_select_sno_a1_details,0,'quantity');
									$amount=($rate*$result['quantity'])/100;
									$amount=number_format($amount, 2, '.', '');	
								}
							}
							
							
							if($result['method']=='Hour')
							{
								$rate='';
								$quantity=$result['quantity'];
								$hour_quantity=$result['hour_quantity'];
								$amount='';
								$sno_a1='-';
								$sql_select_equip="select * from equip_master where equip_id='" . $result['equip_id'] . "'";
								$rs_select_equip=mysqli_query($dbConn,$sql_select_equip,$conn);
								//echo $sql_select_equip.'</br>';
								
								$rate=@mysqli_result($rs_select_equip,0,'rate_hour');
								//$amount=(@mysqli_result($rs_select_equip,0,'rate_hour')*$result['quantity']*@mysqli_result($rs_master,0,'quantity'))/$result['hour_quantity'];
								$amount=(@mysqli_result($rs_select_equip,0,'rate_hour')*$result['quantity']*$result['hour_quantity']);
							}
							
							if($result['method']=='Value')
							{
								$rate='';
								$quantity=$result['quantity'];
								$hour_quantity='-';
								$amount='';
								$sno_a1='-';
								
								$sql_route_indicator="select * from route_indicator";
								$rs_route_indicator=mysqli_query($dbConn,$sql_route_indicator,$conn);
								
								$height1=@mysqli_result($rs_route_indicator,0,'height1');
								$breadth1=@mysqli_result($rs_route_indicator,0,'breadth1');
								$thick1=@mysqli_result($rs_route_indicator,0,'thick1');
								$pie=3.14;
								$height2=@mysqli_result($rs_route_indicator,0,'height2');
								$breadth2=@mysqli_result($rs_route_indicator,0,'breadth2');
								$thick2=@mysqli_result($rs_route_indicator,0,'thick2');
								$rcc_value=@mysqli_result($rs_route_indicator,0,'rcc_value');
								
								$value1=$height1*$breadth1*$thick1;
								$value2=($pie*$height2*$breadth2*$thick2)/2;
								$total_value=($value1+$value2)*$rcc_value;
								$total_value=number_format($total_value, 2, '.', '');
								$final_value=$total_value/$result['quantity'];
								$rate=number_format($final_value, 2, '.', '');
								
								$amount=$rate*$result['quantity'];
							}
			
							if($result['method']=='Unit')
							{
								$rate='';
								$quantity=$result['quantity'];
								$hour_quantity=$result['hour_quantity'];
								$amount='';
								$sno_a1='-';
								$sql_select_equip="select * from equip_master where equip_id='" . $result['equip_id'] . "'";
								$rs_select_equip=mysqli_query($dbConn,$sql_select_equip,$conn);
								$rate=@mysqli_result($rs_select_equip,0,'rate_hour');
							
								$amount=($rate*$result['quantity']);
							}
				
							$total_a2_amount+=$amount;
							
							echo "<tr id='row_a2_" . $row_a2 . "' >";
							echo "<td class='label'>" . @mysqli_result($rs_select_equip,0,'description') . "</td>";
							echo "<td class='labelcenter'>" . $sno_a1 . "</td>";
							echo "<td class='labelright'>" . $rate . "</td>";
							echo "<td class='labelcenter'>" . $quantity . "</td>";
							echo "<td class='labelcenter'>" . $hour_quantity . "</td>";
							echo "<td class='labelright'>" . $amount . "</td>";
							
							//echo "<td nowrap align='center'><input type='button' name='btn_edit_a2_" . $row_a2 .  "'  id='btn_edit_a2_" . $row_a2 .  "' value='Edit' onClick=editrow_a2(" . $row_a2 . ",'n')>&nbsp;&nbsp;<input type='button' name='btn_del_a2_" . $row_a2 .  "' id='btn_del_a2_" . $row_a2 .  "' value='Delete' onClick=delrow_a2(".$row_a2.") ></td>";
							echo "<td nowrap align='center'><input type='button' name='btn_del_a2_" . $row_a2 .  "' id='btn_del_a2_" . $row_a2 .  "' value='Delete' onClick=delrow_a2(".$row_a2.") ></td>";
							
							echo "<input type='hidden' value='" . @mysqli_result($rs_select_equip,0,'description') . "' name='cmb_a2_material". $row_a2 ."' id='cmb_a2_material" . $row_a2 . "'/>";
							echo "<input type='hidden' value='" . $sno_a1 . "' name='cmb_a2_item". $row_a2 ."' id='cmb_a2_item" . $row_a2 . "'/>";
							echo "<input type='hidden' value='" . $rate . "' name='txt_rate_a2". $row_a2 ."' id='txt_rate_a2" . $row_a2 . "'/>";
							echo "<input type='hidden' value='" . $quantity . "' name='txt_quantity_a2". $row_a2 ."' id='txt_quantity_a2" . $row_a2 . "'/>";
							echo "<input type='hidden' value='" . $hour_quantity . "' name='txt_hour_quantity_a2". $row_a2 ."' id='txt_hour_quantity_a2" . $row_a2 . "'/>";
							echo "<input type='hidden' value='" . $amount . "' name='txt_amount_a2". $row_a2 ."' id='txt_amount_a2" . $row_a2 . "'/>";
							echo "</tr>";
							
							if ($row_a2==2)
							{
								?>
								<script language="javascript" type="text/javascript" >
								document.getElementById("add_set_a2").value=<?php echo $row_a2?>;
								</script>
								<?php		
							}
							else
							{
								?>
								<script language="javascript" type="text/javascript" >
								document.getElementById("add_set_a2").value=document.getElementById("add_set_a2").value+"."+<?php echo $row_a2; ?>
								</script>
								<?php		
							}
							$row_a2++;
						}
						?>
						
						<script language="javascript" type="text/javascript">
							add_row_a2=<?php echo $row_a2; ?>
						</script>
						<?php
					}	
					$total_a2_amount=number_format($total_a2_amount, 2, '.', '');
					?>
				</tr>
				
				<tr>
					<td colspan="5" class="labelboldright">Total of A2&nbsp;&nbsp;</td>
					<td class="labelboldcenter"><input type="text" class="labelfieldright" size="8" name="txt_total_amount_a2" id="txt_total_amount_a2" value="<?php if(@mysqli_result($rs_select_a2,0,'ref_id')!='') echo $total_a2_amount; else echo ''; ?>" readonly="" /></td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<br />
		</td>
	</tr>
	
	
	<tr>
		<td width="64">&nbsp;</td>
		<td class="labelbold" colspan="3"><font color="#990000">B Labour</font></td>
	</tr>
	
	<tr>
		<td border="0" cellpadding="0" cellspacing="0" colspan="4"> 
			<table width="800" border="1" cellpadding="0" cellspacing="0" class="color2" align="center" id="tab_b_labour">
				<tr height="25">
					<td class="labelboldcenter">Labour</td>
					<td class="labelboldcenter">Rate per <br />Day</td>
					<td class="labelboldcenter">No. of <br />Days</td>
					<td class="labelboldcenter">Amount</td>
					<td class="labelboldcenter">&nbsp;</td>
				</tr>
				
				<tr>
					<td class="labelcenter">
						<?php 
						$sql_hr="select * from hr_master";
						//echo $sql_hr;
						$rs_hr=mysqli_query($dbConn,$sql_hr,$conn);
						
						if (($rs_hr)!="")
						{
							?>
							<select name="cmb_labour" id="cmb_labour" class="labelfield" onBlur="func_labour()" onChange="func_labour()">
								<option value="Select">Select</option>
								<?php
								while($rows = mysqli_fetch_assoc($rs_hr))
								{
									$hr_id =$rows['hr_id'];
									$hr_desc =$rows['hr_desc'];
									?>
									<option value="<?php echo $hr_desc; ?>"><?php echo $hr_desc; ?></option>
									<?php
								}
								?>
							</select>
							<?php
						}
						?>					
					</td>
					
					<td class="labelcenter"><input type="text" class="labelfieldright" size="8" name="txt_rate_b" id="txt_rate_b" value="" readonly="" /></td>
					<td class="labelcenter"><input type="text" class="labelfield" size="8" name="txt_days_hours" id="txt_days_hours" value="" onBlur="func_labour_amt()" /></td>
					<td class="labelcenter"><input type="text" class="labelfieldright" size="8" name="txt_amount_b" id="txt_amount_b" value="" readonly="" /></td>
					
					<td class="labelcenter">
						<input type="button" name="btn_add_b"   id="btn_add_b" value="Add" onClick="addrow_b()" />&nbsp;&nbsp;
						<input type="button" name="btn_clear_b" id="btn_clear_b" value="Clear" onClick="cleartxt_b()"/>
						<span id="add_hidden_b"></span>
						<input type="hidden" value="" name="add_set_b" id="add_set_b"/>				
					</td>
					
					<?php
					if(@mysqli_result($rs_select_b,0,'ref_id')!='') 
					{
						$row_b=2;
						$rs_select_b=@mysqli_query($dbConn,$sql_select_b,$conn);
						while($result=@mysqli_fetch_assoc($rs_select_b))
						{
							$sql_select_details_b="SELECT * from hr_master where hr_id='" . $result['hr_id'] . "'";
							$rs_select_details_b=mysqli_query($dbConn,$sql_select_details_b,$conn);
							//echo $sql_select_details_b.'<br />';
							
							$day_hour_rate=@mysqli_result($rs_select_details_b,0,'rate_day');
							
							$amount=$day_hour_rate*$result['quantity'];
							$total_b_amount+=$amount;
							
							echo "<tr id='row_b_" . $row_b . "' >";
							echo "<td class='label'>" . @mysqli_result($rs_select_details_b,0,'hr_desc') . "</td>";
							echo "<td class='labelright'>" . $day_hour_rate . "</td>";
							echo "<td class='labelcenter'>" . $result['quantity'] . "</td>";
							echo "<td class='labelright'>" . $amount . "</td>";
							
							//echo "<td nowrap align='center'><input type='button' name='btn_edit_b_" . $row_b .  "'  id='btn_edit_b_" . $row_b .  "' value='Edit' onClick=editrow_b(" . $row_b . ",'n')>&nbsp;&nbsp;<input type='button' name='btn_del_b_" . $row_b .  "' id='btn_del_b_" . $row_b .  "' value='Delete' onClick=delrow_b(".$row_b.") ></td>";
							echo "<td nowrap align='center'><input type='button' name='btn_del_b_" . $row_b .  "' id='btn_del_b_" . $row_b .  "' value='Delete' onClick=delrow_b(".$row_b.") ></td>";
							
							echo "<input type='hidden' value='" . @mysqli_result($rs_select_details_b,0,'hr_desc') . "' name='cmb_labour". $row_b ."' id='cmb_labour" . $row_b . "'/>";
							echo "<input type='hidden' value='" . $day_hour_rate . "' name='txt_rate_b". $row_b ."' id='txt_rate_b" . $row_b . "'/>";
							echo "<input type='hidden' value='" . $result['quantity'] . "' name='txt_days_hours". $row_b ."' id='txt_days_hours" . $row_b . "'/>";
							echo "<input type='hidden' value='" . $amount . "' name='txt_amount_b". $row_b ."' id='txt_amount_b" . $row_b . "'/>";
							echo "</tr>";
							
							if ($row_b==2)
							{
								?>
								<script language="javascript" type="text/javascript" >
								document.getElementById("add_set_b").value=<?php echo $row_b?>;
								</script>
								<?php		
							}
							else
							{
								?>
								<script language="javascript" type="text/javascript" >
								document.getElementById("add_set_b").value=document.getElementById("add_set_b").value+"."+<?php echo $row_b; ?>
								</script>
								<?php		
							}
							$row_b++;
						}
						?>
						<script language="javascript" type="text/javascript">
							add_row_b=<?php echo $row_b; ?>
						</script>
						<?php
					}	
					$total_b_amount=number_format($total_b_amount, 2, '.', '');	
					?>
				</tr>
				
				<tr>
					<td colspan="3" class="labelboldright">Total of B&nbsp;&nbsp;</td>
					<td class="labelboldcenter"><input type="text" class="labelfieldright" size="8" name="txt_total_amount_b" id="txt_total_amount_b" value="<?php if(@mysqli_result($rs_select_b,0,'ref_id')!='') echo $total_b_amount; else echo ''; ?>" readonly="" /></td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	
	<tr><td colspan="4">&nbsp;</td></tr>
	
	<tr>
		<td border="0" cellpadding="0" cellspacing="0" colspan="4"> 
			<table width="800" border="0" class="color3" align="center" cellpadding="0" cellspacing="0">
				<tr><td width="66">&nbsp;</td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
					<td width="281" nowrap="nowrap" class="label">&nbsp;</td>	
					<td width="364" valign="top" nowrap="nowrap" class="labelbold">
						( A1(Install Variable) Total
						&nbsp;+&nbsp;
						A2 Total )
						&nbsp;/&nbsp;
						
						&nbsp;&nbsp;=&nbsp;					</td>
					<td width="87" class="labelbold">Installation Amount</td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>

				<tr>
					<td>&nbsp;</td>
					<td nowrap="nowrap" class="label"><b>Installation Total of Variable Items&nbsp;</b></td>	
					<td width="364" valign="top" nowrap="nowrap" class="label">
						( <input type="text" name="txt_a1_install_1_amt" id="txt_a1_install_1_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $install_1_a1_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" />
						&nbsp;&nbsp;+&nbsp;&nbsp;
						<input type="text" name="txt_a2_install_amt" id="txt_a2_install_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $install_a2_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /> ) / 
						
						&nbsp;&nbsp;=&nbsp;&nbsp;
				  	</td>
				  <td width="87" class="label"><input type="text" name="txt_install_1_amt" id="txt_install_1_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $install_1_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
				
				<tr>
					<td>&nbsp;</td>
					<td class="label" nowrap="nowrap">Over Head</td>
					<td class="label"><input type="text" name="txt_overhead_install_per" id="txt_overhead_install_per" value="<?php echo $overhead_percentage; ?>" size="2" class="labelfield" readonly=""  />%</td>
					<td class="label" nowrap="nowrap"><input type="text" name="txt_overhead_install_amt" id="txt_overhead_install_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $overhead_install_1_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
				
				<tr>
					<td>&nbsp;</td>
					<td class="label" nowrap="nowrap">Profit</td>
					<td class="label"><input type="text" name="txt_profit_install_per" id="txt_profit_install_per" value="<?php echo $profit_percentage; ?>" size="2" class="labelfield" readonly=""  />%</td>
					<td class="label" nowrap="nowrap"><input type="text" name="txt_profit_install_amt" id="txt_profit_install_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $profit_install_1_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
				
				<tr>
					<td>&nbsp;</td>
					<td class="label" nowrap="nowrap">Safety</td>
					<td class="label"><input type="text" name="txt_safety_install_per" id="txt_safety_install_per" value="<?php echo $safety_percentage; ?>" size="2" class="labelfield" readonly=""  />%</td>
					<td class="label" nowrap="nowrap"><input type="text" name="txt_safety_install_amt" id="txt_safety_install_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $safety_install_1_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
				
				<tr>
					<td>&nbsp;</td>
					<td class="label" nowrap="nowrap">Security</td>
					<td class="label"><input type="text" name="txt_security_install_per" id="txt_security_install_per" value="<?php echo $security_percentage; ?>" size="2" class="labelfield" readonly=""  />%</td>
					<td class="label" nowrap="nowrap"><input type="text" name="txt_security_install_amt" id="txt_security_install_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $security_install_1_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
				
				<tr>
					<td>&nbsp;</td>
					<td class="labelbold" nowrap="nowrap">Sub total</td>
					<td>&nbsp;</td>
					<td class="label" nowrap="nowrap"><input type="text" name="txt_sub_install_amt" id="txt_sub_install_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $sub_install_1_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
				
				<tr>
					<td>&nbsp;</td>
					<td class="label" nowrap="nowrap">WCT</td>
					<td class="label"><input type="text" name="txt_wct_install_per" id="txt_wct_install_per" value="<?php echo $wct_percentage; ?>" size="2" class="labelfield" readonly=""  />%&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td class="label" nowrap="nowrap"><input type="text" name="txt_wct_install_amt" id="txt_wct_install_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $wct_install_1_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
				
				<tr>
					<td>&nbsp;</td>
					<td class="labelbold" nowrap="nowrap">Installation Cost of Variable Items</td>
					<td>&nbsp;</td>
				  <td width="87" nowrap="nowrap" class="label"><input type="text" name="txt_install_variable_amt" id="txt_install_variable_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $install_1; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
			</table>
		</td>
	</tr>
	
	<tr><td colspan="4">&nbsp;</td></tr>
	
	<tr>
		<td border="0" cellpadding="0" cellspacing="0" colspan="4"> 
			<table width="800" border="0" class="color3" align="center" cellpadding="0" cellspacing="0">
				<tr><td width="112">&nbsp;</td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
					<td width="258" nowrap="nowrap" class="label">&nbsp;</td>	
					<td width="341" valign="top" nowrap="nowrap" class="labelbold">
						( A1(Install Fixed) Total
						&nbsp;+&nbsp;
						B Total )
						&nbsp;&nbsp;=&nbsp;					
					</td>
					<td width="87" class="labelbold">Installation Amount</td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>

				<tr>
					<td>&nbsp;</td>
					<td nowrap="nowrap" class="label"><b>Installation Total of Fixed Items&nbsp;</b></td>	
					<td width="341" valign="top" nowrap="nowrap" class="label">
						<input type="text" name="txt_a1_install_2_amt" id="txt_a1_install_2_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $install_2_a1_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" />
						&nbsp;&nbsp;+&nbsp;&nbsp;
						<input type="text" name="txt_b_install_amt" id="txt_b_install_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $install_b_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" />
						&nbsp;&nbsp;=&nbsp;&nbsp;
				  	</td>
				  	<td width="87" class="label"><input type="text" name="txt_install_2_amt" id="txt_install_2_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $install_2_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
				
				<tr>
					<td>&nbsp;</td>
					<td class="label" nowrap="nowrap">Over Head</td>
					<td class="label"><input type="text" name="txt_overhead_install_per" id="txt_overhead_install_per" value="<?php echo $overhead_percentage; ?>" size="2" class="labelfield" readonly=""  />%</td>
					<td class="label" nowrap="nowrap"><input type="text" name="txt_overhead_install_amt" id="txt_overhead_install_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $overhead_install_2_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
				
				<tr>
					<td>&nbsp;</td>
					<td class="label" nowrap="nowrap">Profit</td>
					<td class="label"><input type="text" name="txt_profit_install_per" id="txt_profit_install_per" value="<?php echo $profit_percentage; ?>" size="2" class="labelfield" readonly=""  />%</td>
					<td class="label" nowrap="nowrap"><input type="text" name="txt_profit_install_amt" id="txt_profit_install_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $profit_install_2_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
				
				<tr>
					<td>&nbsp;</td>
					<td class="label" nowrap="nowrap">Safety</td>
					<td class="label"><input type="text" name="txt_safety_install_per" id="txt_safety_install_per" value="<?php echo $safety_percentage; ?>" size="2" class="labelfield" readonly=""  />%</td>
					<td class="label" nowrap="nowrap"><input type="text" name="txt_safety_install_amt" id="txt_safety_install_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $safety_install_2_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
				
				<tr>
					<td>&nbsp;</td>
					<td class="label" nowrap="nowrap">Security</td>
					<td class="label"><input type="text" name="txt_security_install_per" id="txt_security_install_per" value="<?php echo $security_percentage; ?>" size="2" class="labelfield" readonly=""  />%</td>
					<td class="label" nowrap="nowrap"><input type="text" name="txt_security_install_amt" id="txt_security_install_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $security_install_2_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
				
				<tr>
					<td>&nbsp;</td>
					<td class="labelbold" nowrap="nowrap">Sub total</td>
					<td>&nbsp;</td>
					<td class="label" nowrap="nowrap"><input type="text" name="txt_sub_install_amt" id="txt_sub_install_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $sub_install_2_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
				</tr>

				<tr><td colspan="5">&nbsp;</td></tr>
				
				<tr>
					<td>&nbsp;</td>
					<td class="label" nowrap="nowrap">WCT</td>
					<td class="label"><input type="text" name="txt_wct_install_per" id="txt_wct_install_per" value="<?php echo $wct_percentage; ?>" size="2" class="labelfield" readonly=""  />%&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td class="label" nowrap="nowrap"><input type="text" name="txt_wct_install_amt" id="txt_wct_install_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $wct_install_2_amt; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
				
				<tr>
					<td>&nbsp;</td>
					<td class="labelbold" nowrap="nowrap">Installation Cost of Fixed Items</td>
					<td>&nbsp;</td>
				  <td width="87" nowrap="nowrap" class="label"><input type="text" name="txt_install_fixed_amt" id="txt_install_fixed_amt" value="<?php if (@mysqli_result($rs_master,0,'ref_id')!='') { echo $install_2; } else { echo '';}?>" size="7" readonly="" class="labelfieldright" /></td>
				</tr>
				
				<tr><td colspan="5">&nbsp;</td></tr>
			</table>
		</td>
	</tr>

	<tr><td colspan="4">&nbsp;</td></tr>
	
	<?php
	if(@mysqli_result($rs_select_a2,0,'ref_id')=="")
	{	
		?>
		<tr class="labelcenter"><td colspan="5"><input type="image" name="btn_save_full" id="btn_save_full" value="Save" src="Buttons/Save_Normal.jpg" onMouseOver="this.src='Buttons/Save_Over.jpg'" onMouseOut="this.src='Buttons/Save_Normal.jpg'" /></td></tr>
		<tr><td colspan="5">&nbsp;</td></tr>
		<?php
	}
	?>
</table>
			
</form>
</body>
</html>
						</blockquote>
					</div>
				</div>
			</div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
			
        </form>
    </body>
</html>
<script>
$(document).ready(function(){
  $('.dropdown-submenu a.test').on("click", function(e){
    $(this).next('ul').toggle();
    e.stopPropagation();
    e.preventDefault();
  });
});
</script>
