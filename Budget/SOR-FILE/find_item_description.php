<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
$item_id=$_GET['item_id'];

$sql_item_desc="select * from item_master where item_id='" . $item_id . "'";
$rs_item_desc=mysqli_query($dbConn,$sql_item_desc,$conn);
//echo $sql_item_desc.'</br>';

if(@mysqli_result($rs_item_desc,0,'item_id')!="")
{
	$basic_price=@mysqli_result($rs_item_desc,0,'price');
	//$basic_price=@mysqli_result($rs_item_desc,0,'price')*@mysqli_result($rs_item_desc,0,'factor');
	//$x=($basic_price*@mysqli_result($rs_item_desc,0,'ED'))/100;
	//$y=($x*@mysqli_result($rs_item_desc,0,'CESS'))/100;
	//$sub_total1=$basic_price+$x+$y;
	//$VAT=($sub_total1*@mysqli_result($rs_item_desc,0,'VAT'))/100;
	//$CST=($sub_total1*@mysqli_result($rs_item_desc,0,'CST'))/100;
	//$amount=$sub_total1+$VAT+$CST;
	//$packing=($amount*@mysqli_result($rs_item_desc,0,'packing'))/100;
	//$freight=($amount*@mysqli_result($rs_item_desc,0,'freight'))/100;
	//$sub_total2=$amount+$packing+$freight;
	//$insurance_charge=($sub_total2*@mysqli_result($rs_item_desc,0,'insurance_charge'))/100;
	//$total=$sub_total2+$insurance_charge;
	//$total=number_format($total, 2, '.', '');	
	//$total=round($total);	
	  $total=$basic_price;
	
/*	echo "basic_price=".$basic_price.'</br>';
	echo "x=".$x.'</br>';
	echo "y=".$y.'</br>';
	echo "sub_total1=".$sub_total1.'</br>';
	echo "VAT=".$VAT.'</br>';
	echo "CST=".$CST.'</br>';
	echo "amount=".$amount.'</br>';
	echo "packing=".$packing.'</br>';
	echo "freight=".$freight.'</br>';
	echo "sub_total2=".$sub_total2.'</br>';
	echo "insurance_charge=".$insurance_charge.'</br>';
	echo "total=".$total.'</br>';
*/
	$item=@mysqli_result($rs_item_desc,0,'item_desc') . '*' .
			@mysqli_result($rs_item_desc,0,'unit') . '*' .
			$total . '*' .
			@mysqli_result($rs_item_desc,0,'item_code')
			;
	echo $item;
}
else
{
	echo '*';
}
	
?>