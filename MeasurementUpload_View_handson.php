<?php
//session_start();
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
require_once 'library/common.php';
checkUser();
$msg = '';
$userid = $_SESSION['userid'];
?>
<?php require_once "Header.html"; ?>
<!--<script src="http://docs.handsontable.com/0.15.0-beta6/scripts/jquery.min.js"></script>
<script src="http://docs.handsontable.com/0.15.0-beta6/bower_components/handsontable/dist/handsontable.full.js"></script>
<link type="text/css" rel="stylesheet" href="http://docs.handsontable.com/0.15.0-beta6/bower_components/handsontable/dist/handsontable.full.min.css">-->

<!--<script src="handsontable/dist/jquery.min.js"></script>-->
<script src="handsontable/dist/handsontable.full.js"></script>
<link type="text/css" rel="stylesheet" href="handsontable/dist/handsontable.full.min.css">

<script>
  	function goBack()
	{
	   url = "dashboard.php";
	window.location.replace(url);
	}
	
	$(function () {
		function GetMeasurements() 
		{
			var workordername = $("#txt_workshortname").val();
			//var mdateStr = "", itemnoStr = "", descriptionStr = "", numberStr = "", lengthStr = "", breadthStr = "", depthStr = "", contentareaStr = "";
			var dataStr = "", mbidStr = "";
			 $.post('MeasurementUpload_Getdata.php', { sheetid: workordername }, function(data) {
			// alert (JSON.stringify(data));
			//alert(data);
			 	$.each(data, function() {
					//alert(this.itemno);
					var mdate 		= this.date;
					var itemno 		= this.itemno;
					var description = this.description;
					var number 		= this.number;
					var length 		= this.length;
					var breadth 	= this.breadth;
					var depth 		= this.depth;
					var contentarea = this.contentarea;
					var mbdetailid 	= this.mbdetailid;
					var mbheaderid 	= this.mbheaderid;
					var iunit 		= this.iunit;
					var errorflag 	= this.errorflag;
					//alert()
					/*mdateStr = mdateStr+"<input type='hidden' name='"++"' id='"++"' value='"++"'>";
					itemnoStr = itemnoStr+"<input type='hidden' name='"++"' id='"++"' value='"++"'>";
					descriptionStr = descriptionStr+"<input type='hidden' name='"++"' id='"++"' value='"++"'>";
					numberStr = numberStr+"<input type='hidden' name='"++"' id='"++"' value='"++"'>";
					lengthStr = lengthStr+"<input type='hidden' name='"++"' id='"++"' value='"++"'>";
					breadthStr = breadthStr+"<input type='hidden' name='"++"' id='"++"' value='"++"'>";
					depthStr = depthStr+"<input type='hidden' name='"++"' id='"++"' value='"++"'>";
					contentareaStr = contentareaStr+"<input type='hidden' name='"++"' id='"++"' value='"++"'>";*/
					 	mbidStr = $('#txt_mbdetailidlist').val();
						mbidStr = mbidStr+"@@"+mbdetailid;
					
						dataStr = $('#hid_data').html();
						dataStr = dataStr+"<input type='hidden' name='txt_mbdetailid"+mbdetailid+"'  id='txt_mbdetailid"+mbdetailid+"' 	value='"+mbdetailid+"'>";
						dataStr = dataStr+"<input type='hidden' name='txt_mbheaderid"+mbdetailid+"'  id='txt_mbheaderid"+mbdetailid+"' 	value='"+mbheaderid+"'>";
						dataStr = dataStr+"<input type='hidden' name='txt_mdate"+mbdetailid+"' 		 id='txt_mdate"+mbdetailid+"' 		value='"+mdate+"'>";
						dataStr = dataStr+"<input type='hidden' name='txt_itemno"+mbdetailid+"' 	 id='txt_itemno"+mbdetailid+"' 		value='"+itemno+"'>";
						dataStr = dataStr+"<input type='hidden' name='txt_description"+mbdetailid+"' id='txt_description"+mbdetailid+"' value='"+description+"'>";
						dataStr = dataStr+"<input type='hidden' name='txt_number"+mbdetailid+"' 	 id='txt_number"+mbdetailid+"' 		value='"+number+"'>";
						dataStr = dataStr+"<input type='hidden' name='txt_length"+mbdetailid+"' 	 id='txt_length"+mbdetailid+"' 		value='"+length+"'>";
						dataStr = dataStr+"<input type='hidden' name='txt_breadth"+mbdetailid+"' 	 id='txt_breadth"+mbdetailid+"' 	value='"+breadth+"'>";
						dataStr = dataStr+"<input type='hidden' name='txt_depth"+mbdetailid+"' 	 	 id='txt_depth"+mbdetailid+"' 		value='"+depth+"'>";
						dataStr = dataStr+"<input type='hidden' name='txt_contentarea"+mbdetailid+"' id='txt_contentarea"+mbdetailid+"' value='"+contentarea+"'>";
						dataStr = dataStr+"<input type='hidden' name='txt_iunit"+mbdetailid+"'		 id='txt_iunit"+mbdetailid+"' 		value='"+iunit+"'>";
						dataStr = dataStr+"<input type='hidden' name='txt_errorflag"+mbdetailid+"'	 id='txt_errorflag"+mbdetailid+"' 	value='"+errorflag+"'>";
					var a = "";
						$('#hid_data').html(a);
						$('#hid_data').html(dataStr);
					
					var b = "";
						$('#txt_mbdetailidlist').val(b);
						$('#txt_mbdetailidlist').val(mbidStr);
				});
				//alert(mdateStr)
				//$('#hid_data').text(itemnoStr);
			 }, "json");
			 //alert(mdate)
		}
		
		$("#txt_workshortname").change(function () { 
			var a = "";
			$('#hid_data').text(a);
			var b = "";
			$('#txt_mbdetailidlist').val(b);
			GetMeasurements();
		});
		
	});
	
</script>

<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
<script>

$(function () {
//document.addEventListener("DOMContentLoaded", function() {

var $container = $("#example1");
  
  //var handsontable = $container.data('handsontable');
$("#btn_view").click(function() {

  	//$("#btn_save").addClass('backbutton');
	$('#btn_save').removeClass('hide').addClass('backbutton');
  
  function getData()
  {
  	var i;
  	var mbidStr = $('#txt_mbdetailidlist').val();
	var MbidSplit = mbidStr.split("@@");
	var myData = [];
	/*var obj1 = { 
					mdate: 'Date',
					itemno: 'item No',
					idescrip: 'Description',
					inumber: 'Number',
					ilength: 'Length',
					ibreadth: 'Breadth',
					idepth: 'Depth',
					iarea: 'Content Area',
					iremarks: 'Remarks'
				};
				myData.push(obj1);*/
	//alert(result)
	
	for(i = 0; i < MbidSplit.length; i++)
	{
		var mbid = MbidSplit[i];
		var iremarks = "", itemremarks1 = "", dateremarks1="", dateremarks2="", dateremarks3="", dateremarks4="", dateremarks5="", dateremarks6="";
		if(mbid != "")
		{
			var mdate 		= $('#txt_mdate'+mbid).val();
			var itemno 		= $('#txt_itemno'+mbid).val();
			var idescrip 	= $('#txt_description'+mbid).val();
			var inumber 	= $('#txt_number'+mbid).val();
			var ilength 	= $('#txt_length'+mbid).val();
			var ibreadth 	= $('#txt_breadth'+mbid).val();
			var idepth 		= $('#txt_depth'+mbid).val();
			var iarea 		= $('#txt_contentarea'+mbid).val();
			var iunit		= $('#txt_iunit'+mbid).val();
			var errorflag 	= $('#txt_errorflag'+mbid).val();
			var eflag		= errorflag.split("@@@");
			var erroritem	= eflag[0];
			var dateflag	= eflag[1];
			var itemflag	= eflag[2];
			if(itemflag != "")
			{
				itemremarks1 	= 	itemflag;
				//var itemno		= 	erroritem;
			}
			if(dateflag != "")
			{
				dateremarks1	=	dateflag;
			}
			iremarks = itemremarks1+"\n"+dateremarks1;
			/*if(itemflag == 1)
			{
				var itemremarks1 = "This Item Number is invalid. Please check it with your aggreement sheet.";
			}
			if(dateflag == 1){ var dateremarks1 = "Day Field is Invalid."; }
			if(dateflag == 2){ var dateremarks2 = "Month Field is Invalid."; }
			if(dateflag == 3){ var dateremarks3 = "Day Field is Invalid."; }
			if(dateflag == 4){ var dateremarks4 = "Month Field is Invalid."; }
			if(dateflag == 5){ var dateremarks5 = "Year Field is Invalid."; }
			if(dateflag == 6){ var dateremarks6 = "Date Field is Invalid."; }
				iremarks = itemremarks1+""+dateremarks1+""+dateremarks2+""+dateremarks3+""+dateremarks4+""+dateremarks5+""+dateremarks6;*/
			//result = result+"["+mdate+","+itemno+","+idescrip+","+inumber+","+ilength+","+ibreadth+","+idepth+","+iarea+","+iremarks+"],";
				
			
				var obj = { 
					mdate: mdate,
					itemno: itemno,
					idescrip: idescrip,
					inumber: inumber,
					ilength: ilength,
					ibreadth: ibreadth,
					idepth: idepth,
					iarea: iarea,
					iunit: iunit,
					iremarks: iremarks
				};
				myData.push(obj);
		}
	}
	//alert (JSON.stringify(myData));
	return myData;
  }
  
  function saveRowData(rowData)
  {
  	var mdate 		= rowData[0];
	var itemno 		= rowData[1];
	var idescrip 	= rowData[2];
	var inumber 	= rowData[3];
	var ilength 	= rowData[4];
	var ibreadth 	= rowData[5];
	var idepth 		= rowData[6];
	var iarea 		= rowData[7];
	var iunit 		= rowData[8];
	var iremarks 	= rowData[9];
	/*alert(mdate);
	alert(itemno);
	alert(idescrip);
	alert(inumber);
	alert(ilength);
	alert(ibreadth);
	alert(idepth);
	alert(iarea);
	alert(iremarks);*/
	
  	//$.post('MeasurementUpload_EditData.php', { editvalue: rowData }, function(data) {
	//alert(data)
	//});
  }
  // Instead of creating a new Handsontable instance
  // with the container element passed as an argument,
  // you can simply call .handsontable method on a jQuery DOM object.
  
  
  
  
function cellRender(row, col, prop, colProperties) {
    var cellProperties = {};
    if(row == 2 || row == 3){
        cellProperties.readOnly = true;
    }
    return cellProperties;
}

  $container.handsontable({
    data: getData(),
    rowHeaders: true,
	width:985,
	height:555,
	
    colHeaders: true,
	//minSpareRows: 1,
	columns: [
      {data: 'mdate'},
	  {data: 'itemno',className: "htRight"},
	  {data: 'idescrip'},
      {data: 'inumber',className: "htRight"},
      {data: 'ilength',className: "htRight"},
      {data: 'ibreadth',className: "htRight"},
      {data: 'idepth',className: "htRight"},
	  {data: 'iarea',className: "htRight"},
	  {data: 'iunit'},
	  {data: 'iremarks'}
    ],
    /*contextMenu: true*/
	fixedRowsTop: 0,
	//fixedColumnsLeft: 3,
	columnSorting: true,
	sortIndicator: true,
	autoColumnSize: {    
		"samplingRatio": 23
	},
	contextMenu: true,
	mergeCells: true,
	manualRowResize: true,
	manualColumnResize: true,
	manualRowMove: true,
	currentRowClassName: 'currentRow',
    currentColClassName: 'currentCol',
	colHeaders: ['Date','Item No','Description','No.','Length','Breadth','Depth','Contents of Area','Unit','Remarks'],
	manualColumnMove: false,
	cells: function (row, col, prop) {
        return cellRender(row, col, prop);
    },
	collapsibleColumns: true,
	hiddenColumns: true,
	trimRows: [    
		1,
		2,
		5
	],
	
		afterChange: function (change, source) 
		{
                if (source === 'loadData') 
				{
                    return; //don't save this change
					//alert(datarow);
                }

                if (source === 'edit') 
				{
                    var datarow = $container.handsontable('getDataAtRow', change[0][0]);
					//alert(datarow[0]);
					//var selection = $container.handsontable('getSelected');
					saveRowData(datarow);
					//alert(datarow)
					//var ht = $('#example1').handsontable('getInstance');
					//var sel = ht.getSelected();
                }
       },
	   
	
	dropdownMenu: true,
	filters: true,
	search: true
  });
  
  // This way, you can access Handsontable api methods by passing their names as an argument, e.g.:
  /*var hotInstance = $("#example1").handsontable('getInstance');
  
  function bindDumpButton() {
      if (typeof Handsontable === "undefined") {
        return;
      }
  
      Handsontable.Dom.addEvent(document.body, 'click', function (e) {
  
        var element = e.target || e.srcElement;
  
        if (element.nodeName == "BUTTON" && element.name == 'dump') {
          var name = element.getAttribute('data-dump');
          var instance = element.getAttribute('data-instance');
          var hot = window[instance];
          console.log('data of ' + name, hot.getData());
        }
      });
    }
  bindDumpButton();*/

});
/*$("#btn_save").click(function() {
alert();
var xyz = $container.data('handsontable').getData();
alert(xyz);
});*/

});



</script>
<style>
.datatable
{
	left:50px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:12px;
}
.handsontable td
{
background-color:#ffffff;
color:#0D03C9;
font-size:12px;
font-family:Verdana, Arial, Helvetica, sans-serif;
}
.handsontable th
{
background-color:#EAF2FF;
color:#0D03C9;
font-family:Verdana, Arial, Helvetica, sans-serif;
}
.hide
{
	display:none;
}
</style>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form">
           <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
            <div class="content">

                <div class="container_12">
                    <div class="grid_12">
                        <blockquote class="bq1">
                            <div class="title">Measurement Upload - View</div>
							<table width="1078px" border="0" align="center" cellpadding="0" cellspacing="0" class="color1">
									<tr>
										<td width="50px;">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td width="250px;">&nbsp;</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td class="label">Work Short Name</td> 
										<td  height="30px;" class="labeldisplay">
											<select name="txt_workshortname" id="txt_workshortname" class="textboxdisplay" style="width:439px;height:22px;" onChange="workorderdetail();" tabindex="7">
												<option value=""> ----------------------- Select Work Short Name ------------------------ </option>
												<?php echo $objBind->BindWorkOrderNo(0);?>
											</select>
										</td>
										<td><input type="button" class="backbutton" id="btn_view" id="btn_view" value=" View "></td>
									</tr>
									<tr>
										<td width="21%"><span id="hid_data"></span></td>
										<td>
											<input type="hidden" name="txt_mbdetailidlist" id="txt_mbdetailidlist">
										</td>
										<td></td>
										<td>&nbsp;</td>
									</tr>
									<!--<tr>
										<td colspan="3" align="center" height="30px;"></td>
									</tr>-->
									<tr>
										<td width="21%">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td colspan="4"><div id="example1" class="hot handsontable datatable"></div></td>
									</tr>
									<tr>
										<td width="21%">&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td colspan="4" align="center" height="30px;"><input type="button" class="hide" id="btn_save" id="btn_save" value=" Save "></td>
									</tr>
							</table>
							
                        </blockquote>
                    </div>
                </div>
            </div>
	  </form>
      <!--==============================footer=================================-->
      <?php   include "footer/footer.html"; ?>
<script>
  var $container = $("#example1");
  var handsontable = $container.data('handsontable');
$(function () {
	$("#btn_save").click(function() {
		alert();
		var xyz = $container.data('handsontable').getData();
		alert(xyz[0][0]);
	});
});
</script>
    </body>
</html>
