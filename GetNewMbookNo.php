<?php
require_once 'library/config.php';
 $mbookno=$_POST['currentmbook'];
    $insertquery=mysql_query("INSERT INTO mymbook(mbookno,sheetid,staffid,active,userid) VALUES('$mbookno',1,1,1,1)");
   if($insertquery){
       echo "1";
    }
    else { echo "0"; }
 
?>