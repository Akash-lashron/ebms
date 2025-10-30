<?php
session_start();
@ob_start();
require_once 'library/config.php';
$workorderno=$_GET['wkrorderno'];
$workorderno="select mbno from agreementmbookallotment where sheetid='$workorderno' AND active = 1";
$rsworkorderno=mysql_query($workorderno);
$mbno='';
while($rows=mysql_fetch_assoc($rsworkorderno)){	 $mbno=$mbno.'*'.$rows['mbno']; }
echo ltrim($mbno,'*');
?>