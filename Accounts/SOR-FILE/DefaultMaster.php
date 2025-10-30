<?php 
$DefValNameArr = array(); $DefValPercArr = array();  $DefValCodeArr = array();
$sql_default = "select * from default_master";// where de_name='WCT'";
$rs_default  = mysqli_query($dbConn,$sql_default);
if($rs_default == true){
	while($List = mysqli_fetch_object($rs_default)){
		$DefID 	 = $List->de_id;
		$DefName = $List->de_name;
		$DefPerc = $List->de_perc;
		$DefCode = $List->de_code;
		$DefValNameArr[$DefID] = $DefName;
		$DefValPercArr[$DefID] = $DefPerc;
		$DefValCodeArr[$DefID] = $DefCode;
	}
}
$UCRows = 0;
$SelectUnitConvQuery = "select * from unit_conversion";
$SelectUnitConvSql 	 = mysqli_query($dbConn,$SelectUnitConvQuery);
if($SelectUnitConvSql == true){
	if(mysqli_num_rows($SelectUnitConvSql)>0){
		$UCRows = 1;
	}
}

?>