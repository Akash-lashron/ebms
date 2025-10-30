<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
//checkUser();
$msg = ""; $del = 0;
$RowCount = 0;
$staffid  = $_SESSION['sid'];

$SelectDetailQuery   = "(select * from group_datasheet_hc where group_type='SP' ORDER BY type ASC) UNION (select * from group_datasheet_hc where group_type='MD' ORDER BY type ASC)";
$result              = mysqli_query($dbConn,$SelectDetailQuery);
   while($row = mysqli_fetch_array($result)){
        $sub_data["id"]              =$row["id"];
        $sub_data["group_desc"]      =$row["group_desc"];
        $sub_data["type"]            =$row["type"];
        $sub_data["text"]            =$row["group_desc"];
        $sub_data["par_id"]          =$row["par_id"];
        $data[]                      =$sub_data;
    }

         foreach($data as $key=>&$value)
           {
             $output[$value["id"]]=&$value;
           }
         foreach($data as $key=>&$value)
           {
             $output[$value["type"]]=&$value;
           }
         foreach($data as $key=>&$value)
           {
              if($value["par_id"]&&isset($output[$value["par_id"]]))
              {
                 $output[$value["par_id"]]["nodes"][]=&$value;
              }
           }
         foreach($data as $key=>&$value)
           {
              if($value["par_id"]&&isset($output[$value["par_id"]]))
              {
                 unset($data[$key]);
              }
           }  
               //echo json_encode($data);
                  /*  echo '<pre>';
                   print_r($data);
                   echo '</pre>'; */
            ?>

<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
	<script src="dashboard/MyView/bootstrap.min.js"></script>
	<script type="text/javascript">
  		window.history.forward();
  		function noBack() { window.history.forward(); }
  </script>
	  <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="form1">
                <?php include "Menu.php"; ?>   
            <!--==============================Content=================================-->
      			<div class="content">
      				<?php include "MainMenu.php"; ?>
      				<div class="container_12">
      					<div class="grid_12" align="center">
      						<div align="right" class="users-icon-part">&nbsp;</div>
      						  <blockquote class="bq1" style="overflow:auto">
      							  <div class="row">
      								    <div class="div12" align="center">&nbsp;</div>
      							  </div>
      							     <div class="row">
      								    <div class="div2" align="center">&nbsp;</div>
      								      <div class="div8" align="center">
      									     <table align="left" class="table itemtable formtable" width="100%">
                										    <thead>
                											    <tr>
                    												<th colspan="2" class="fhead">Horticulture Group List</th>
                    												<th colspan="3" class="fhead">Current SOR Rates 2018</th>
                    											  <th colspan="2" class="fhead">Previous SOR 
                                                                    (20-6-2016) Rates </th>
                                            <th colspan="2" class="fhead">% of variation</th>
                											    </tr>
                											    <tr>
                    												<th>Group Code</th>
                    												<th>Group Description</th>
                    												<th>Township Rs. Ps.</th>
                                            <th>PlantSite
                                                 Rs.  Ps.</th>
                                            <th>Unit</th>
                                            <th>Township Rs. Ps.</th>
                                            <th>PlantSite
                                                 Rs.  Ps.</th>
                                            <th>Township Rs. Ps.</th>
                                            <th>PlantSite
                                                 Rs.  Ps.</th>
                											    </tr>
                										    </thead>
                										    <tbody>
                                        	  <?php  function printTree($tree)  {
                                              if(!is_null($tree) && count($tree) > 0) { 
                                        				foreach($tree as $node) {
                                                  $TotalAmount = 0; $Amount = 0;   $TotalAmountMD=0; $TotalAmountLabMD=0;  
                                                  $TotAmountHirM=0; $TotAmountMatM=0; $TotAmountLabM=0; $TotalAmountMD1=0;
                                        	        $SelectQuery1 	= "select a.* , b.*, a.quantity as cost_quantity , b.quantity as item_quantity from datasheet_master_hc a inner join datasheet_a1_details_hc b on (a.ref_id= b.ref_id) where a.id='".$node['id']."'";
                                        	        $SelectQuery1Sql = mysqli_query($dbConn,$SelectQuery1);
                                        	         if($SelectQuery1Sql == true){
                                        		          if(mysqli_num_rows($SelectQuery1Sql)>0){
                                        			          while($ListM1 = mysqli_fetch_object($SelectQuery1Sql)){
                                            			        $Qty1 		  = $ListM1->item_quantity;
                                            			        $CostQty1	  = $ListM1->cost_quantity;
                                            			        $Desc1 	    = $ListM1->item_desc;
                                            			        $ItemId1 	  = $ListM1->item_id;
                                            		          $Unit1 	    = $ListM1->unit; 
                                                          $HcType1    = $ListM1->hc_type;
                                      	                  $SelectQuery2 = "select * from item_master_hc where item_id='$ListM1->item_id'";
                                      	                  $SelectQuery2Sql = mysqli_query($dbConn,$SelectQuery2);    
                                        	                  if($SelectQuery2Sql == true){
                                        	                    if(mysqli_num_rows($SelectQuery2Sql)>0){
                                                		         	   $ListM2       = mysqli_fetch_object($SelectQuery2Sql);
                                                			           $Price2       = $ListM2->price;
                                                                 $Desc2      	 = $ListM2->item_desc;
                                                                 $ItemType2 	 = $ListM2->item_type;
                                                              }
                                        	                  }
                                                              if($HcType1=="SP"){
                                                                if($ItemType2=="MA"){
                                          	                        $QtyM             =($Qty1);
                                          	                        $AmountMat        =($QtyM*$Price2);
                                          	                        $TransPer         =($AmountMat *5/100);
                                          	                        $ProvPer          =($AmountMat *3/100);
                                          	                        $TotAmountMat     =($AmountMat+$TransPer+$ProvPer); 
                                                                }else if($ItemType2=="LA"){
                                          	                        $QtyL             =($Qty1);
                                          	                        $AmountLab        =($QtyL*$Price2);
                                                                }
                                                              }elseif($HcType1=="MD"){
                                                              	if($ItemType2=="LA"){     
                                        	                          $QtyLMD          =($Qty1);	
                                        	                          $AmountLabM      =($QtyLMD*$Price2);
                                        	                          $TotAmountLabM   =($TotAmountLabM +$AmountLabM);
                                        	                      }elseif($ItemType2=="MA"){
                                          	                        $QtyMMD          =($Qty1);
                                          	                        $AmountMatM      =($QtyMMD*$Price2);
                                          	                        $TotAmountMatM   =($TotAmountMatM +$AmountMatM );
                                                                }elseif($ItemType2=="HC"){
                                        	                         $QtyHMD          =($Qty1);
                                        	                         $AmountHirM      =($QtyHMD*$Price2);
                                                                   $TotAmountHirM   =($TotAmountHirM +$AmountHirM);
                                        	                      }                         
                                                              } 
                                        	             }             
                                                              if($HcType1=="SP"){
                                                                  $TotalAmount  =($TotAmountMat+$AmountLab);
                                                                  $ToolPer      =($TotalAmount*1/100);
                                                                  $SafePer      =($TotalAmount*0.5/100);
                                                                  $ContrPer     =($TotalAmount*15/100);
                                                                  $Total        =($TotalAmount+$ToolPer+$SafePer+$ContrPer);
                                                                  $TownshipStF  =($Total/$QtyM);
                                                                  $TownshipSt   =round(($Total/$QtyM),2);
                                                                  $SecPerF      =(($TotalAmount*4/100)/$QtyM);
                                                                  $SecPer       =round((($TotalAmount*4/100)/$QtyM),2);
                                                                  $PlantSt      =round(($SecPer + $TownshipStF),2);
                                                              }elseif($HcType1=="MD"){
                                                                	$TotalAmountMD =($TotalAmountMD+$TotAmountLabM + $TotAmountMatM + $TotAmountHirM);
                                                                 	$ToolsperMD   =($TotalAmountMD*1/100);
                                                                 	$SafePerMD    =($TotalAmountMD*0.5/100);
                                                                 	$ContrPerMD   =($TotalAmountMD*15/100);
                                                                 	$TotalMD      =($TotalAmountMD+$ToolsperMD +$SafePerMD+$ContrPerMD);
                                                                  $CostOne      =($TotalMD/$CostQty1);
                                                                  //echo $CostOne ;
                                                                  $TownshipM    =round(($TotalMD/$CostQty1),2);
                                                                  $SecPerFMD    =(($TotalAmountMD*4/100)/$CostQty1);
                                                                  $SecPerFMDR   =round((($TotalAmountMD*4/100)/$CostQty1),2);
                                                                  $PlantSitM    =round(($CostOne +$SecPerFMDR),2);
                                                              }
                                                      }
                                                   }  ?>  
                                          <tr>
                                              <?php  printTree($node['nodes']); ?>
                															<td><?php echo $node['type']; ?></td>
                                              <td class="lboxlabel">
                                                  <?php  echo $node['group_desc']; ?>
                                              </td>
                															<td>
                                                  <?php  if($HcType1=="SP"){ echo $TownshipSt; } elseif($HcType1=="MD"){ echo $TownshipM; } ?>
                                              </td>
                															<td>
                                                <?php if($HcType1=="SP"){ echo $PlantSt; }elseif($HcType1=="MD"){ echo $PlantSitM; } ?>
                                              </td>
                															<td>
                                                 <?php echo $Unit1 ;?>
                                              </td>
                															<td></td>
                															<td></td>
                													    <td></td>
                													    <td></td>
                                          </tr>  
                			               <?php      }   
                													    }   
                                            }   
                                            //$result = parseTree($tree);
                                            printTree($data); ?>
                										</tbody>	
      									</table>
      								</div>
      								<div class="div3" align="left">&nbsp;</div>
      							</div>
      						</blockquote>
      					</div>
      				</div>
      			</div>
            <!--==============================footer=================================-->
                <?php   include "footer/footer.html"; ?>
                <script src="js/jquery.hoverdir.js"></script>
        </form>
    </body>
</html>
<script>
$(document).ready(function(){
	$('.dropdown-submenu a.test').on("click", function(e){
    	$(this).next('ul').toggle();
    	e.stopPropagation();
    	e.preventDefault();
  	});
  	$('#btn_view_single').click(function(event){ 
  		$(location).attr("href","ItemMasterView.php");
		event.preventDefault();
		return false;
  	});
});
</script>
