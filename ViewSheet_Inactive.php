<?php
require_once 'library/config.php';
require_once 'library/functions.php';
checkUser();
require_once 'ExcelReader/excel_reader2.php';
$msg = '';
$sheetid = $_SESSION['Sheetid'];
//$schdulesql ="SELECT      DISTINCT sno,sch_id, sheet_id,  description, total_quantity, rate, per, total_amt, subdiv_id, page_no FROM  schdule   where sheet_id= '$sheetid' AND  sno <> '' AND subdiv_id !=0 ";
$schdulesql ="SELECT DISTINCT sno,sch_id, subdiv_id, sheet_id,  description, total_quantity, rate, per, total_amt, subdiv_id, page_no FROM  schdule   where sheet_id= '$sheetid' AND  sno != 0 ";
$schdule=mysql_query($schdulesql);
 $RowCount =0;
 if(isset($_POST['submit']))
 {
     header('Location: ViewAgreementSheet_Inactive.php');
 }
?>
<?php require_once "Header.html"; ?>
     <style>
            .container{
    display:table;
    width:100%;
    border-collapse: collapse;
    }
.heading{
     font-weight: bold;
     display:table-row;
     background-color:#C91622;
     text-align: center;
     line-height: 25px;
     font-size: 14px;
     font-family:georgia;
     color:#fff;
    
}
.table-row{  
     display:table-row;
     text-align: left;
}
.col{
display:table-cell;
border: 1px solid #CCC;
}
/*.colfont{
  font-family:Constantia; 
  font-size: 11pt;
}*/
        </style>
		<SCRIPT type="text/javascript">
		window.history.forward();
		function noBack() { window.history.forward(); }
	</SCRIPT>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="phuploader">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">

                <div class="container_12">
                    <div class="grid_12">

                        
                        <blockquote class="bq1"  style="height:520px; overflow:scroll;">
                            <div class="title">View Agreement Sheet</div>
                            <div class="container" >
                                <?php 
                                if ($schdule == false) {  } else {        $RowCount = mysql_num_rows($schdule);    }
                            if ($schdule == true && $RowCount > 0) {
                                  ?>
                                <div class="heading">
                                    <div class="col" style="width:50px;">SNo</div>
                                    <div class="col">Description</div>
                                    <div class="col">Rate </div>
                                    <div class="col">Total Quantity</div>
                                    <div class="col">Total Amt </div>
                                    <div class="col">Per </div>
                                </div>
                               
                             <div class="table-row">
								 <div class="col"></div>
								<!-- <div class="col"><?php //echo "&nbsp;EARTHWORK"; ?></div>-->
								 <div class="col"></div>
								 <div class="col"></div>
								 <div class="col"></div>
								 <div class="col"></div></div>
                                <?php //$searchword_work = "1.13.a"; 
								while ($List = mysql_fetch_object($schdule)) {
                                $total_amt = ($List->rate * $List->total_quantity); 
                                 if($List->subdiv_id == 0){ $List->rate = "";$List->total_quantity = "";$total_amt = ""; }
                                ?>
							  			<div class="table-row">
	                                    <div class="col labelhead" style="width:50px;"><?php echo $List->sno; ?> </div>
    	                                <div class="col labelhead">&nbsp;<?php echo $List->description; ?> </div>
        	                            <div class="col labelhead"><?php echo $List->rate; ?> </div>
            	                        <div class="col labelhead"><?php echo $List->total_quantity; ?> </div>
                	                    <div class="col labelhead"><?php echo $total_amt; ?> </div>
                    	                <div class="col labelhead"><?php echo $List->per; ?> </div>
		                                </div>
                                <?php } }?>
                            </div>
                            <div class="col2"><?php if ($msg != '') {
    echo $msg;
} ?></div>
                        </blockquote>
                        <div>&nbsp;</div>
                        <div>
							<center>
								<table align="centre">
								   <tr><td>
									  <input type="submit" name="submit" value="Back">
								   </td></tr>
								</table>
							</center>
						</div>
                        </form>
                    </div>

                </div>
                
            </div>
            
             <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
        </form>
    </body>
</html>
