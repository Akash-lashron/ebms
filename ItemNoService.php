<?php
require_once("library/binddata.php");
 $workorderno=$_POST[workorderno];
echo $objBind->BindItemNo($workorderno); 
?>