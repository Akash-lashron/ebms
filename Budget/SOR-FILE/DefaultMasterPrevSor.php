<?php 
$DefValNameArrPrev = array(); $DefValPercArrPrev = array();  $DefValCodeArrPrev = array();
$sql_default = "select * from pdm_detail where puid = '$PruId'";// where de_name='WCT'";
$rs_default  = mysqli_query($dbConn,$sql_default);
if($rs_default == true){
	while($List = mysqli_fetch_object($rs_default)){
		$DefID 	 = $List->de_id;
		$DefName = $List->de_name;
		$DefPerc = $List->de_perc;
		$DefCode = $List->de_code;
		$DefValNameArrPrev[$DefID] = $DefName;
		$DefValPercArrPrev[$DefID] = $DefPerc;
		$DefValCodeArrPrev[$DefID] = $DefCode;
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