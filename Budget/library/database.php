<?php
require_once 'config.php';
/*$conn2 = mysqli_connect('localhost','root','god') or die("Connection Failure");
mysqli_select_db($dbName2,$conn2);
$conn = mysqli_connect('localhost','root','god', true) or die("Connection Failure");
mysqli_select_db($dbName,$conn);*/


//$dbConn = mysqli_connect( $dbHost, $dbUser, $dbPass, 'ebms' );

$dbConn = mysqli_connect( $dbHost, $dbUser, $dbPass, $dbName );


/*$SelectIdQuery1 	= "select * from group_datasheet";
$SelectIdSql1 	= mysqli_query($dbConn,$SelectIdQuery1);
if($SelectIdSql1 == true){
	if(mysqli_num_rows($SelectIdSql1)>0){
		while($IDList1 = mysqli_fetch_object($SelectIdSql1)){
			echo $IDList1->id."<br/>";
		}
	}
}

$SelectIdQuery2 	= "select * from staff where active = 1";
$SelectIdSql2 	= mysqli_query($dbConn,$SelectIdQuery2);
if($SelectIdSql2 == true){
	if(mysqli_num_rows($SelectIdSql2)>0){
		while($IDList2 = mysqli_fetch_object($SelectIdSql2)){
			echo $IDList2->staffname."<br/>";
		}
	}
}*/


/*function dbQuery($sql)
{
	$result = mysqli_query($dbConn,$sql) or die(mysqli_error());
	
	return $result;
}

function dbAffectedRows()
{
	global $dbConn;
	
	return mysqli_affected_rows($dbConn);
}

function dbFetchArray($result, $resultType = mysqli_NUM) {
	return mysqli_fetch_array($result, $resultType);
}

function dbFetchAssoc($result)
{
	return mysqli_fetch_assoc($result);
}

function dbFetchRow($result) 
{
	return mysqli_fetch_row($result);
}

function dbFreeResult($result)
{
	return mysqli_free_result($result);
}

function dbNumRows($result)
{
	return mysqli_num_rows($result);
}

function dbSelect($dbName)
{
	return mysqli_select_db($dbName);
}

function dbInsertId()
{
	return mysqli_insert_id($dbConn);
}*/
?>