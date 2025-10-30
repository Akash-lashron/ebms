<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
$msg = '';
if($_POST["submit"] == "submit") {
$rbn_no=trim($_POST['cmb_rbn']);
$mbookdetails="select sheet_id,mb_id from mbookgenerate  where rbn='$rbn_no'";
$sql_mbook=mysql_query($mbookdetails);
if($sql_mbook == true) {
$List = mysql_fetch_object($sql_mbook);
$sheet_id=$List->sheet_id;
$mb_id=$List->mb_id; }
if($sheet_id!='' && $mb_id!='') { header('Location: MBook.php?page=0&sheetid='.$sheet_id.'&id='.$mb_id); }
}
?>
<?php require_once "Header.html"; ?>
    <body class="page1" id="top">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1">
                        <div class="title">Agreement Item Staff-Wise Allotment</div>
                        <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                       
                            <div class="content">

                              <table width="1000"  bgcolor="#E8E8E8" border="1" cellpadding="0" cellspacing="0" align="center" >
                                        <tr><td>&nbsp;</td></tr>		
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td  class="label" width="15%" nowrap="nowrap">Work Order No.</td>
                                            <td class="label" colspan="3">
                                                <select id="workorderno" name="workorderno" class="textboxdisplay" style="width:450px;height:22px;" tabindex="7">
                                                        <option value=""> -- Select Work Order No -- </option>
                                                        <?php echo $objBind->BindWorkOrderNo(0); ?>
                                                    </select>     
                                            </td>

                                        </tr>
                                        <tr><td>&nbsp;</td></tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td  class="label" nowrap="nowrap">Item No.</td>
                                            <td class="label">  
                                                <select name="itemno" id="itemno" class="textboxdisplay" style="width:220px;height:22px;"   tabindex="7">
                                                        <?php echo $objBind->BindItemNo(0); ?>
                                                </select></td>	

                                            <td  class="label" nowrap="nowrap">Sub Item No.</td>
                                            <td class="label">
                                                <select name="subitemno" id="subitemno" class="textboxdisplay" multiple="true" style="width:220px;height:122px;">
                                                    <?php echo $objBind->BindSubItemNo(0); ?>
                                                </select></td>
                                        </tr>
                                        <tr><td>&nbsp;</td></tr>		
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td  class="label" width="15%" nowrap="nowrap">Engg.Name</td>
                                            <td class="label" colspan="3">
                                                <select id="staff" name="staff" class="textboxdisplay" style="width:450px;height:22px;" tabindex="7">
                                                        <option value=""> -- Select Engg Name -- </option>
                                                        <?php echo $objBind->Bind(0); ?>
                                                    </select>     
                                            </td>

                                        </tr>
                                        <tr>
                                            <td colspan="5">
                                        <center>
                                            <input type="hidden" class="text" name="submit" value="true" />
                                            <input type="submit" class="btn" data-type="submit" value=" Submit " />	
                                        </center>
                                        </td>
                                        </tr>
                                    </table>
                            </div>
                            <div class="col2"><?php if ($msg != '') {
    echo $msg;
    } ?></div> 
                        
                        </form>
                    </blockquote>
                </div>

            </div>
        </div>
         <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
         <script>

    $(function () {
        function BindItemNo() {
            $("#itemno").attr("disabled", "disabled");
            $("#itemno").html("<option>wait...</option>");
            var workordernolistvalue = $("#workorderno option:selected").attr('value');
            $.post("ItemNoService.php", {workorderno: workordernolistvalue}, function (data) {
                $("#itemno").removeAttr("disabled");
               $("#itemno").html(data);
               alert(data)
            });
        }
       function BindSubItemNo() {
            $("#subitemno").attr("disabled", "disabled");
            $("#subitemno").html("<option>wait...</option>");
            var workordernolistvalue = $("#workorderno option:selected").attr('value');
            var itemnolistVal = $("#itemno option:selected").attr('value');
            $.post("SubItemNoService.php", {workorderno: workordernolistvalue, itemno: itemnolistVal}, function (data) {
                $("#subitemno").removeAttr("disabled");
                $("#subitemno").html(data);
                alert(data)
            });
        }
        $("#workorderno").bind("change", function () {
            BindItemNo();
            BindSubItemNo(); 
        });
        $("#itemno").bind("change", function () {
            BindSubItemNo();
        });
      
    });
	
</script>

    </body>
</html>

