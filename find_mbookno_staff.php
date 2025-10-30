<?php
session_start();
@ob_start();
require_once 'library/config.php';
$sheetid  =	$_POST['sheetid'];
$GenArr = array(); $StlArr = array(); $AbsArr = array(); $EscArr = array();
$MBookQuery = "SELECT agreementmbookallotment.mbno, agreementmbookallotment.mbooktype, agreementmbookallotment.allotmentid, agreementmbookallotment.mbookmode  
			   FROM agreementmbookallotment
			   WHERE NOT EXISTS ( SELECT allotmentid FROM mbookallotment WHERE agreementmbookallotment.allotmentid = mbookallotment.allotmentid)
 			   AND agreementmbookallotment.active = 1 AND agreementmbookallotment.sheetid ='$sheetid' ORDER BY agreementmbookallotment.mbno";

$MBookSql	= mysql_query($MBookQuery);
if($MBookSql == true){
	if(mysql_num_rows($MBookSql)>0){
		while($List = mysql_fetch_array($MBookSql)){
			if($List['mbooktype'] == 'G'){
				$GenArr[] = $List;
			}elseif($List['mbooktype'] == 'S'){
				$StlArr[] = $List;
			}elseif($List['mbooktype'] == 'A'){
				$AbsArr[] = $List;
			}elseif($List['mbooktype'] == 'E'){
				$EscArr[] = $List;
			}
		}
	}
}
$Result = array('GMB'=>$GenArr,'SMB'=>$StlArr,'AMB'=>$AbsArr,'EMB'=>$EscArr);
echo json_encode($Result);
//echo $MBookQuery;
?>