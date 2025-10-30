<?php
require_once("library/binddata.php");
 $wordorderno=$_POST[wordorderno];
 echo $objBind->DisplayRBNDetails($wordorderno); 
?>