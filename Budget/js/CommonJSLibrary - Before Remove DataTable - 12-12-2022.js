//$(document).ready(function() {
	function YearlyExpenditure(){ 
		var BudUnit 		= $("#cmb_unit").val();
		var BudFinYear 	 	= $("#cmb_fy").val();
		var BudDiscipline 	= $("#cmb_discipline").val();
		var BudHoa 	 		= $("#cmb_hoa").val();
		var BudMode 	 	= $("#cmb_mode").val();
		var BudYear 	 	= $("#txt_year").val();
		var RupeesIn 	 	= $("#cmb_rupees").val();
		/*if( $.fn.DataTable.isDataTable('#example') ) {
		  $('#example').DataTable().destroy();
		}
		$('#example tbody').empty();*/
		$('.example').DataTable({	
			"ajax": {
				"url": "ajax/DataTableDataReports.php",
				"contentType": "application/x-www-form-urlencoded;charset=UTF-8",
				"dataType":"json",
				"data": function ( d ) {
					d.code 			= "EXPYE";
					d.BudUnit 		= BudUnit;
					d.BudFinYear  	= BudFinYear;
					d.BudDiscipline = BudDiscipline;
					d.BudHoa 		= BudHoa;
					d.BudMode 		= BudMode;
					d.BudMonth 		= "";
					d.BudYear 		= BudYear;
					d.BudThreeQtr 	= "";
					d.BudHalfYear 	= "";
					d.BudQuarter 	= "";
					d.RupeesIn 		= RupeesIn;
				},
				type:"post",
				/*"dataSrc": function ( json ) {
					//Make your callback here.
					alert(JSON.stringify(json.valid));
					//return json.data;
				} */
			},				
			"columns": [
								
				{
					"data": "vuid",
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},	{ "data": "work_name", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},					
				{ "data": "name_contractor", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "ccno_wono", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "wo_amount", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "apr", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (Number(data)).toLocaleString('en-IN', {minimumFractionDigits: 2});
				}}	,	
				{ "data": "may", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (Number(data)).toLocaleString('en-IN', {minimumFractionDigits: 2});
				}} ,	
				{ "data": "jun", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (Number(data)).toLocaleString('en-IN', {minimumFractionDigits: 2});
				}}	,	
				{ "data": "work_qtr1_amt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (Number(data)).toLocaleString('en-IN', {minimumFractionDigits: 2});
				}}	,	
				{ "data": "jul", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (Number(data)).toLocaleString('en-IN', {minimumFractionDigits: 2});
				}},	
				{ "data": "aug", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (Number(data)).toLocaleString('en-IN', {minimumFractionDigits: 2});
				}},	
				{ "data": "sep", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (Number(data)).toLocaleString('en-IN', {minimumFractionDigits: 2});
				}},	
				{ "data": "work_qtr2_amt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (Number(data)).toLocaleString('en-IN', {minimumFractionDigits: 2});
				}},	
				{ "data": "oct", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (Number(data)).toLocaleString('en-IN', {minimumFractionDigits: 2});
				}}	,	
				{ "data": "nov", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (Number(data)).toLocaleString('en-IN', {minimumFractionDigits: 2});
				}}	,	
				{ "data": "dec", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (Number(data)).toLocaleString('en-IN', {minimumFractionDigits: 2});
				}}	,	
				{ "data": "work_qtr3_amt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (Number(data)).toLocaleString('en-IN', {minimumFractionDigits: 2});
				}}	,	
				{ "data": "jan", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (Number(data)).toLocaleString('en-IN', {minimumFractionDigits: 2});
				}}	,	
				{ "data": "feb", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (Number(data)).toLocaleString('en-IN', {minimumFractionDigits: 2});
				}}	,	
				{ "data": "mar", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (Number(data)).toLocaleString('en-IN', {minimumFractionDigits: 2});
				}}	,	
				{ "data": "work_qtr4_amt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (Number(data)).toLocaleString('en-IN', {minimumFractionDigits: 2});
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}				
			],
			//CALULATE FOOTER TOTALS ON FOTER CALLBACK	
			"footerCallback": function ( row, data, start, end, display ) {
				var api = this.api(), data;
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
				 
				api.columns('.sum', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
				api.columns('.sum1', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
			},
			// "fnRowCallback" : function(nRow, aData, iDisplayIndex){
				// $("td:first", nRow).html(iDisplayIndex +1);
			   // return nRow;
			// },
			"columnDefs": [
			{ className: "text-right", "targets": [] },
			{
				targets: [],
				render(v){
				  return Number(v).toFixed() 
				}
			},
			{ "visible": false, "targets": 2 }
			],
			
			
			//dom: "<'row'<'col-sm-1'l><'col-sm-7'B><'col-sm-1'<'toolbar'>><'col-sm-2'f>>rtip",
			dom:"lBfrtip",
			// dom:  "<'row'<'col-sm-1'l><'col-sm-1'B><'col-sm-6'<'toolbar'>><'col-sm-3'f>>",
			fnInitComplete: function(){
			   $('div.toolbar').html('<h3 class="text-info"> LIST OF ENTREPRENEUR WITH BUSINESS PLAN</h3>');
			 },
			 buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print'
			],
			"iDisplayLength": 10,
			/*scrollY:        "300px",
			fixedHeader: {
				header: true,
				"offsetTop" : 100 
			},*/
			/*scrollX: "700px",
            fixedColumns: {
				columns: true,
                leftColumns: 3
            }*/
			/*fixedColumns: {
				column: true,
				left: 5,
            	right: 1
			},*/
		});
	}
	function ThreeQtrExpenditure(){ 
		var BudUnit 		= $("#cmb_unit").val();
		var BudFinYear 	 	= $("#cmb_fy").val();
		var BudDiscipline 	= $("#cmb_discipline").val();
		var BudHoa 	 		= $("#cmb_hoa").val();
		var BudMode 	 	= $("#cmb_mode").val();
		var BudThreeQtr 	= $("#cmb_three_quarter").val();
		var RupeesIn 	 	= $("#cmb_rupees").val();
		//alert();
		/*if( $.fn.DataTable.isDataTable('#example') ) {
		  $('#example').DataTable().destroy();
		}
		$('#example tbody').empty();*/
		$('.example').DataTable({	
			"ajax": {
				"url": "ajax/DataTableDataReports.php",
				"contentType": "application/x-www-form-urlencoded;charset=UTF-8",
				"dataType":"json",
				"data": function ( d ) {
					d.code 			= "EXPTQ";
					d.BudUnit 		= BudUnit;
					d.BudFinYear  	= BudFinYear;
					d.BudDiscipline = BudDiscipline;
					d.BudHoa 		= BudHoa;
					d.BudMode 		= BudMode;
					d.BudMonth 		= "";
					d.BudYear 		= "";
					d.BudThreeQtr 	= BudThreeQtr;
					d.BudHalfYear 	= "";
					d.BudQuarter 	= "";
					d.RupeesIn 		= RupeesIn;
				},
				type:"post",
				/*"dataSrc": function ( json ) {
					//Make your callback here.
					alert(JSON.stringify(json.valid));
					//return json.data;
				} */
			},				
			"columns": [
				{
					"data": "vuid",
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},					
				{ "data": "work_name", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},					
				{ "data": "name_contractor", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "ccno_wono", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "wo_amount", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "apr", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "may", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}} ,	
				{ "data": "jun", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "work_qtr1_amt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "jul", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}},	
				{ "data": "aug", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}},	
				{ "data": "sep", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}},	
				{ "data": "work_qtr2_amt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}},	
				{ "data": "oct", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "nov", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "dec", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "work_qtr3_amt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}				
			],
			//CALULATE FOOTER TOTALS ON FOTER CALLBACK	
			"footerCallback": function ( row, data, start, end, display ) {
				var api = this.api(), data;
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
				 
				api.columns('.sum', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
				api.columns('.sum1', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
			},
			// "fnRowCallback" : function(nRow, aData, iDisplayIndex){
				// $("td:first", nRow).html(iDisplayIndex +1);
			   // return nRow;
			// },
			"columnDefs": [
			{ className: "text-right", "targets": [] },
			{
				targets: [],
				render(v){
				  return Number(v).toFixed() 
				}
			}
			],
			
			
			//dom: "<'row'<'col-sm-1'l><'col-sm-7'B><'col-sm-1'<'toolbar'>><'col-sm-2'f>>rtip",
			dom:"lBfrtip",
			// dom:  "<'row'<'col-sm-1'l><'col-sm-1'B><'col-sm-6'<'toolbar'>><'col-sm-3'f>>",
			fnInitComplete: function(){
			   $('div.toolbar').html('<h3 class="text-info"> LIST OF ENTREPRENEUR WITH BUSINESS PLAN</h3>');
			 },
			 buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print'
			],
			"iDisplayLength": 10,
			/*scrollY:        "300px",
			fixedHeader: {
				header: true,
				"offsetTop" : 100 
			},*/
			/*scrollX: "700px",
            fixedColumns: {
				columns: true,
                leftColumns: 3
            }*/
			/*fixedColumns: {
				column: true,
				left: 5,
            	right: 1
			},*/
		});
	}

	function FirstHalfYearlyExpenditure(){ 
		var BudUnit 		= $("#cmb_unit").val();
		var BudFinYear 	 	= $("#cmb_fy").val();
		var BudDiscipline 	= $("#cmb_discipline").val();
		var BudHoa 	 		= $("#cmb_hoa").val();
		var BudMode 	 	= $("#cmb_mode").val();
		var BudHalfYear 	= $("#cmb_half_year").val();
		var RupeesIn 	 	= $("#cmb_rupees").val();
		//alert();
		/*if( $.fn.DataTable.isDataTable('#example') ) {
		  $('#example').DataTable().destroy();
		}
		$('#example tbody').empty();*/
		$('.example').DataTable({	
			"ajax": {
				"url": "ajax/DataTableDataReports.php",
				"contentType": "application/x-www-form-urlencoded;charset=UTF-8",
				"dataType":"json",
				"data": function ( d ) {
					d.code 			= "EXPHY";
					d.BudUnit 		= BudUnit;
					d.BudFinYear  	= BudFinYear;
					d.BudDiscipline = BudDiscipline;
					d.BudHoa 		= BudHoa;
					d.BudMode 		= BudMode;
					d.BudMonth 		= "";
					d.BudYear 		= "";
					d.BudThreeQtr 	= "";
					d.BudHalfYear 	= BudHalfYear;
					d.BudQuarter 	= "";
					d.RupeesIn 		= RupeesIn;
				},
				type:"post",
				/*"dataSrc": function ( json ) {
					//Make your callback here.
					alert(JSON.stringify(json.valid));
					//return json.data;
				} */
			},				
			"columns": [
				{
					"data": "vuid",
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},					
				{ "data": "work_name", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},					
				{ "data": "name_contractor", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "ccno_wono", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "wo_amount", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "apr", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "may", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}} ,	
				{ "data": "jun", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "work_qtr1_amt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "jul", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}},	
				{ "data": "aug", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}},	
				{ "data": "sep", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}},	
				{ "data": "work_qtr2_amt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}				
			],
			//CALULATE FOOTER TOTALS ON FOTER CALLBACK	
			"footerCallback": function ( row, data, start, end, display ) {
				var api = this.api(), data;
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
				 
				api.columns('.sum', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
				api.columns('.sum1', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
			},
			// "fnRowCallback" : function(nRow, aData, iDisplayIndex){
				// $("td:first", nRow).html(iDisplayIndex +1);
			   // return nRow;
			// },
			"columnDefs": [
			{ className: "text-right", "targets": [] },
			{
				targets: [],
				render(v){
				  return Number(v).toFixed() 
				}
			}
			],
			
			
			//dom: "<'row'<'col-sm-1'l><'col-sm-7'B><'col-sm-1'<'toolbar'>><'col-sm-2'f>>rtip",
			dom:"lBfrtip",
			// dom:  "<'row'<'col-sm-1'l><'col-sm-1'B><'col-sm-6'<'toolbar'>><'col-sm-3'f>>",
			fnInitComplete: function(){
			   $('div.toolbar').html('<h3 class="text-info"> LIST OF ENTREPRENEUR WITH BUSINESS PLAN</h3>');
			 },
			 buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print'
			],
			"iDisplayLength": 10,
			/*scrollY:        "300px",
			fixedHeader: {
				header: true,
				"offsetTop" : 100 
			},*/
			/*scrollX: "700px",
            fixedColumns: {
				columns: true,
                leftColumns: 3
            }*/
			/*fixedColumns: {
				column: true,
				left: 5,
            	right: 1
			},*/
		});
	}


	function SecondHalfYearlyExpenditure(){ 
		var BudUnit 		= $("#cmb_unit").val();
		var BudFinYear 	 	= $("#cmb_fy").val();
		var BudDiscipline 	= $("#cmb_discipline").val();
		var BudHoa 	 		= $("#cmb_hoa").val();
		var BudMode 	 	= $("#cmb_mode").val();
		var BudHalfYear 	= $("#cmb_half_year").val();
		var RupeesIn 	 	= $("#cmb_rupees").val();
		//alert();
		/*if( $.fn.DataTable.isDataTable('#example') ) {
		  $('#example').DataTable().destroy();
		}
		$('#example tbody').empty();*/
		$('.example').DataTable({	
			"ajax": {
				"url": "ajax/DataTableDataReports.php",
				"contentType": "application/x-www-form-urlencoded;charset=UTF-8",
				"dataType":"json",
				"data": function ( d ) {
					d.code 			= "EXPHY";
					d.BudUnit 		= BudUnit;
					d.BudFinYear  	= BudFinYear;
					d.BudDiscipline = BudDiscipline;
					d.BudHoa 		= BudHoa;
					d.BudMode 		= BudMode;
					d.BudMonth 		= "";
					d.BudYear 		= "";
					d.BudThreeQtr 	= "";
					d.BudHalfYear 	= BudHalfYear;
					d.BudQuarter 	= "";
					d.RupeesIn 		= RupeesIn;
				},
				type:"post",
				/*"dataSrc": function ( json ) {
					//Make your callback here.
					alert(JSON.stringify(json.valid));
					//return json.data;
				} */
			},				
			"columns": [
				{
					"data": "vuid",
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},					
				{ "data": "work_name", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},					
				{ "data": "name_contractor", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "ccno_wono", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "wo_amount", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "oct", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "nov", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}} ,	
				{ "data": "dec", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "work_qtr3_amt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "jan", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}},	
				{ "data": "feb", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}},	
				{ "data": "mar", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}},	
				{ "data": "work_qtr4_amt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}} ,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}				
			],
			//CALULATE FOOTER TOTALS ON FOTER CALLBACK	
			"footerCallback": function ( row, data, start, end, display ) {
				var api = this.api(), data;
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
				 
				api.columns('.sum', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
				api.columns('.sum1', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
			},
			// "fnRowCallback" : function(nRow, aData, iDisplayIndex){
				// $("td:first", nRow).html(iDisplayIndex +1);
			   // return nRow;
			// },
			"columnDefs": [
			{ className: "text-right", "targets": [] },
			{
				targets: [],
				render(v){
				  return Number(v).toFixed() 
				}
			}
			],
			
			
			//dom: "<'row'<'col-sm-1'l><'col-sm-7'B><'col-sm-1'<'toolbar'>><'col-sm-2'f>>rtip",
			dom:"lBfrtip",
			// dom:  "<'row'<'col-sm-1'l><'col-sm-1'B><'col-sm-6'<'toolbar'>><'col-sm-3'f>>",
			fnInitComplete: function(){
			   $('div.toolbar').html('<h3 class="text-info"> LIST OF ENTREPRENEUR WITH BUSINESS PLAN</h3>');
			 },
			 buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print'
			],
			"iDisplayLength": 10,
			/*scrollY:        "300px",
			fixedHeader: {
				header: true,
				"offsetTop" : 100 
			},*/
			/*scrollX: "700px",
            fixedColumns: {
				columns: true,
                leftColumns: 3
            }*/
			/*fixedColumns: {
				column: true,
				left: 5,
            	right: 1
			},*/
		});
	}


	function FirstQuarterExpenditure(){ 
		var BudUnit 		= $("#cmb_unit").val();
		var BudFinYear 	 	= $("#cmb_fy").val();
		var BudDiscipline 	= $("#cmb_discipline").val();
		var BudHoa 	 		= $("#cmb_hoa").val();
		var BudMode 	 	= $("#cmb_mode").val();
		var BudQuarter 	 	= $("#cmb_quarter").val();
		var RupeesIn 	 	= $("#cmb_rupees").val();
		//alert();
		/*if( $.fn.DataTable.isDataTable('#example') ) {
		  $('#example').DataTable().destroy();
		}
		$('#example tbody').empty();*/
		$('.example').DataTable({	
			"ajax": {
				"url": "ajax/DataTableDataReports.php",
				"contentType": "application/x-www-form-urlencoded;charset=UTF-8",
				"dataType":"json",
				"data": function ( d ) {
					d.code 			= "EXPQU";
					d.BudUnit 		= BudUnit;
					d.BudFinYear  	= BudFinYear;
					d.BudDiscipline = BudDiscipline;
					d.BudHoa 		= BudHoa;
					d.BudMode 		= BudMode;
					d.BudMonth 		= "";
					d.BudYear 		= "";
					d.BudThreeQtr 	= "";
					d.BudHalfYear 	= "";
					d.BudQuarter 	= BudQuarter;
					d.RupeesIn 		= RupeesIn;
				},
				type:"post",
				/*"dataSrc": function ( json ) {
					//Make your callback here.
					alert(JSON.stringify(json.valid));
					//return json.data;
				} */
			},				
			"columns": [
				{
					"data": "vuid",
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},					
				{ "data": "work_name", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},					
				{ "data": "name_contractor", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "ccno_wono", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "wo_amount", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "apr", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "may", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}} ,	
				{ "data": "jun", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "work_qtr_amt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}				
			],
			//CALULATE FOOTER TOTALS ON FOTER CALLBACK	
			"footerCallback": function ( row, data, start, end, display ) {
				var api = this.api(), data;
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
				 
				api.columns('.sum', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
				api.columns('.sum1', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
			},
			// "fnRowCallback" : function(nRow, aData, iDisplayIndex){
				// $("td:first", nRow).html(iDisplayIndex +1);
			   // return nRow;
			// },
			"columnDefs": [
			{ className: "text-right", "targets": [] },
			{
				targets: [],
				render(v){
				  return Number(v).toFixed() 
				}
			}
			],
			
			
			//dom: "<'row'<'col-sm-1'l><'col-sm-7'B><'col-sm-1'<'toolbar'>><'col-sm-2'f>>rtip",
			dom:"lBfrtip",
			// dom:  "<'row'<'col-sm-1'l><'col-sm-1'B><'col-sm-6'<'toolbar'>><'col-sm-3'f>>",
			fnInitComplete: function(){
			   $('div.toolbar').html('<h3 class="text-info"> LIST OF ENTREPRENEUR WITH BUSINESS PLAN</h3>');
			 },
			 buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print'
			],
			"iDisplayLength": 10,
			/*scrollY:        "300px",
			fixedHeader: {
				header: true,
				"offsetTop" : 100 
			},*/
			/*scrollX: "700px",
            fixedColumns: {
				columns: true,
                leftColumns: 3
            }*/
			/*fixedColumns: {
				column: true,
				left: 5,
            	right: 1
			},*/
		});
	}


	function SecondQuarterExpenditure(){ 
		var BudUnit 		= $("#cmb_unit").val();
		var BudFinYear 	 	= $("#cmb_fy").val();
		var BudDiscipline 	= $("#cmb_discipline").val();
		var BudHoa 	 		= $("#cmb_hoa").val();
		var BudMode 	 	= $("#cmb_mode").val();
		var BudQuarter 	 	= $("#cmb_quarter").val();
		var RupeesIn 	 	= $("#cmb_rupees").val();
		//alert();
		/*if( $.fn.DataTable.isDataTable('#example') ) {
		  $('#example').DataTable().destroy();
		}
		$('#example tbody').empty();*/
		$('.example').DataTable({	
			"ajax": {
				"url": "ajax/DataTableDataReports.php",
				"contentType": "application/x-www-form-urlencoded;charset=UTF-8",
				"dataType":"json",
				"data": function ( d ) {
					d.code 			= "EXPQU";
					d.BudUnit 		= BudUnit;
					d.BudFinYear  	= BudFinYear;
					d.BudDiscipline = BudDiscipline;
					d.BudHoa 		= BudHoa;
					d.BudMode 		= BudMode;
					d.BudMonth 		= "";
					d.BudYear 		= "";
					d.BudThreeQtr 	= "";
					d.BudHalfYear 	= "";
					d.BudQuarter 	= BudQuarter;
					d.RupeesIn 		= RupeesIn;
				},
				type:"post",
				/*"dataSrc": function ( json ) {
					//Make your callback here.
					alert(JSON.stringify(json.valid));
					//return json.data;
				} */
			},				
			"columns": [
				{
					"data": "vuid",
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},					
				{ "data": "work_name", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},					
				{ "data": "name_contractor", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "ccno_wono", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "wo_amount", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "jul", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "aug", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}} ,	
				{ "data": "sep", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "work_qtr_amt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}				
			],
			//CALULATE FOOTER TOTALS ON FOTER CALLBACK	
			"footerCallback": function ( row, data, start, end, display ) {
				var api = this.api(), data;
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
				 
				api.columns('.sum', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
				api.columns('.sum1', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
			},
			// "fnRowCallback" : function(nRow, aData, iDisplayIndex){
				// $("td:first", nRow).html(iDisplayIndex +1);
			   // return nRow;
			// },
			"columnDefs": [
			{ className: "text-right", "targets": [] },
			{
				targets: [],
				render(v){
				  return Number(v).toFixed() 
				}
			}
			],
			
			
			//dom: "<'row'<'col-sm-1'l><'col-sm-7'B><'col-sm-1'<'toolbar'>><'col-sm-2'f>>rtip",
			dom:"lBfrtip",
			// dom:  "<'row'<'col-sm-1'l><'col-sm-1'B><'col-sm-6'<'toolbar'>><'col-sm-3'f>>",
			fnInitComplete: function(){
			   $('div.toolbar').html('<h3 class="text-info"> LIST OF ENTREPRENEUR WITH BUSINESS PLAN</h3>');
			 },
			 buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print'
			],
			"iDisplayLength": 10,
			/*scrollY:        "300px",
			fixedHeader: {
				header: true,
				"offsetTop" : 100 
			},*/
			/*scrollX: "700px",
            fixedColumns: {
				columns: true,
                leftColumns: 3
            }*/
			/*fixedColumns: {
				column: true,
				left: 5,
            	right: 1
			},*/
		});
	}


	function ThirdQuarterExpenditure(){ 
		var BudUnit 		= $("#cmb_unit").val();
		var BudFinYear 	 	= $("#cmb_fy").val();
		var BudDiscipline 	= $("#cmb_discipline").val();
		var BudHoa 	 		= $("#cmb_hoa").val();
		var BudMode 	 	= $("#cmb_mode").val();
		var BudQuarter 	 	= $("#cmb_quarter").val();
		var RupeesIn 	 	= $("#cmb_rupees").val();
		//alert();
		/*if( $.fn.DataTable.isDataTable('#example') ) {
		  $('#example').DataTable().destroy();
		}
		$('#example tbody').empty();*/
		$('.example').DataTable({	
			"ajax": {
				"url": "ajax/DataTableDataReports.php",
				"contentType": "application/x-www-form-urlencoded;charset=UTF-8",
				"dataType":"json",
				"data": function ( d ) {
					d.code 			= "EXPQU";
					d.BudUnit 		= BudUnit;
					d.BudFinYear  	= BudFinYear;
					d.BudDiscipline = BudDiscipline;
					d.BudHoa 		= BudHoa;
					d.BudMode 		= BudMode;
					d.BudMonth 		= "";
					d.BudYear 		= "";
					d.BudThreeQtr 	= "";
					d.BudHalfYear 	= "";
					d.BudQuarter 	= BudQuarter;
					d.RupeesIn 		= RupeesIn;
				},
				type:"post",
				/*"dataSrc": function ( json ) {
					//Make your callback here.
					alert(JSON.stringify(json.valid));
					//return json.data;
				} */
			},				
			"columns": [
				{
					"data": "vuid",
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},					
				{ "data": "work_name", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},					
				{ "data": "name_contractor", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "ccno_wono", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "wo_amount", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "oct", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "nov", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}} ,	
				{ "data": "dec", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "work_qtr_amt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}				
			],
			//CALULATE FOOTER TOTALS ON FOTER CALLBACK	
			"footerCallback": function ( row, data, start, end, display ) {
				var api = this.api(), data;
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
				 
				api.columns('.sum', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
				api.columns('.sum1', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
			},
			// "fnRowCallback" : function(nRow, aData, iDisplayIndex){
				// $("td:first", nRow).html(iDisplayIndex +1);
			   // return nRow;
			// },
			"columnDefs": [
			{ className: "text-right", "targets": [] },
			{
				targets: [],
				render(v){
				  return Number(v).toFixed() 
				}
			}
			],
			
			
			//dom: "<'row'<'col-sm-1'l><'col-sm-7'B><'col-sm-1'<'toolbar'>><'col-sm-2'f>>rtip",
			dom:"lBfrtip",
			// dom:  "<'row'<'col-sm-1'l><'col-sm-1'B><'col-sm-6'<'toolbar'>><'col-sm-3'f>>",
			fnInitComplete: function(){
			   $('div.toolbar').html('<h3 class="text-info"> LIST OF ENTREPRENEUR WITH BUSINESS PLAN</h3>');
			 },
			 buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print'
			],
			"iDisplayLength": 10,
			/*scrollY:        "300px",
			fixedHeader: {
				header: true,
				"offsetTop" : 100 
			},*/
			/*scrollX: "700px",
            fixedColumns: {
				columns: true,
                leftColumns: 3
            }*/
			/*fixedColumns: {
				column: true,
				left: 5,
            	right: 1
			},*/
		});
	}


	function FourthQuarterExpenditure(){ 
		var BudUnit 		= $("#cmb_unit").val();
		var BudFinYear 	 	= $("#cmb_fy").val();
		var BudDiscipline 	= $("#cmb_discipline").val();
		var BudHoa 	 		= $("#cmb_hoa").val();
		var BudMode 	 	= $("#cmb_mode").val();
		var BudQuarter 	 	= $("#cmb_quarter").val();
		var RupeesIn 	 	= $("#cmb_rupees").val();
		//alert();
		/*if( $.fn.DataTable.isDataTable('#example') ) {
		  $('#example').DataTable().destroy();
		}
		$('#example tbody').empty();*/
		$('.example').DataTable({	
			"ajax": {
				"url": "ajax/DataTableDataReports.php",
				"contentType": "application/x-www-form-urlencoded;charset=UTF-8",
				"dataType":"json",
				"data": function ( d ) {
					d.code 			= "EXPQU";
					d.BudUnit 		= BudUnit;
					d.BudFinYear  	= BudFinYear;
					d.BudDiscipline = BudDiscipline;
					d.BudHoa 		= BudHoa;
					d.BudMode 		= BudMode;
					d.BudMonth 		= "";
					d.BudYear 		= "";
					d.BudThreeQtr 	= "";
					d.BudHalfYear 	= "";
					d.BudQuarter 	= BudQuarter;
					d.RupeesIn 		= RupeesIn;
				},
				type:"post",
				/*"dataSrc": function ( json ) {
					//Make your callback here.
					alert(JSON.stringify(json.valid));
					//return json.data;
				} */
			},				
			"columns": [
				{
					"data": "vuid",
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},					
				{ "data": "work_name", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},					
				{ "data": "name_contractor", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "ccno_wono", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "wo_amount", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "jan", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "feb", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}} ,	
				{ "data": "mar", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "work_qtr_amt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}	,	
				{ "data": "", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}}				
			],
			//CALULATE FOOTER TOTALS ON FOTER CALLBACK	
			"footerCallback": function ( row, data, start, end, display ) {
				var api = this.api(), data;
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
				 
				api.columns('.sum', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
				api.columns('.sum1', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
			},
			// "fnRowCallback" : function(nRow, aData, iDisplayIndex){
				// $("td:first", nRow).html(iDisplayIndex +1);
			   // return nRow;
			// },
			"columnDefs": [
			{ className: "text-right", "targets": [] },
			{
				targets: [],
				render(v){
				  return Number(v).toFixed() 
				}
			}
			],
			
			
			//dom: "<'row'<'col-sm-1'l><'col-sm-7'B><'col-sm-1'<'toolbar'>><'col-sm-2'f>>rtip",
			dom:"lBfrtip",
			// dom:  "<'row'<'col-sm-1'l><'col-sm-1'B><'col-sm-6'<'toolbar'>><'col-sm-3'f>>",
			fnInitComplete: function(){
			   $('div.toolbar').html('<h3 class="text-info"> LIST OF ENTREPRENEUR WITH BUSINESS PLAN</h3>');
			 },
			 buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print'
			],
			"iDisplayLength": 10,
			/*scrollY:        "300px",
			fixedHeader: {
				header: true,
				"offsetTop" : 100 
			},*/
			/*scrollX: "700px",
            fixedColumns: {
				columns: true,
                leftColumns: 3
            }*/
			/*fixedColumns: {
				column: true,
				left: 5,
            	right: 1
			},*/
		});
	}

	function MonthlyExpenditure(TitleStr){ 
		var BudUnit 		= $("#cmb_unit").val();
		var BudFinYear 	 	= $("#cmb_fy").val();
		var BudDiscipline 	= $("#cmb_discipline").val();
		var BudHoa 	 		= $("#cmb_hoa").val();
		var BudMode 	 	= $("#cmb_mode").val();
		var BudMonth 	 	= $("#cmb_month").val();
		var RupeesIn 	 	= $("#cmb_rupees").val();
		var code 			= "EXPMO";
		var BudYear 		= "";
		var BudThreeQtr 	= "";
		var BudHalfYear 	= "";
		var BudQuarter 		= "";
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/DataTableDataReports.php', 
			data: { BudUnit: BudUnit, BudFinYear: BudFinYear, BudDiscipline: BudDiscipline, BudHoa: BudHoa, BudMode: BudMode, BudMonth: BudMonth, RupeesIn: RupeesIn, code: code, BudYear: BudYear, BudThreeQtr: BudHalfYear, BudQuarter: BudQuarter }, 
			dataType: 'json',
			success: function (data) {   //alert(data['computer_code_no']);
				if(data != null){ console.log(data);
					var Valid = data['valid'];
					var Result = data['data'];
					var TableStr = ""; var Sno = 1; var PrevHoa = ""; var HoaMonTotal = 0; var HoaTotal = 0;
					$.each(Result, function(index, element) { 
						if(element.hoa_no != PrevHoa){
							if(PrevHoa != ""){
								TableStr += "<tr><td colspan='5' align='right'>TOTAL</td><td></td><td></td><td align='right'>"+HoaMonTotal.toFixed(2)+"</td><td></td><td></td></tr>";
								HoaTotal = Number(HoaTotal) + Number(HoaMonTotal); HoaMonTotal = 0; 
							}
							TableStr += "<tr><td colspan='10' style='background-color:#10478A; color:#fff'>Head of Account : "+element.hoa_no+"</td></tr>";
						}
						TableStr += "<tr><td>"+Sno+"</td><td>"+element.hoa_no+"</td><td>"+element.work_name+"</td><td>"+element.name_contractor+"</td><td>"+element.ccno_wono+"</td><td>"+element.wo_amount+"</td><td>"+element.total_prev_fy+"</td><td align='right'>"+element.month+"</td><td>"+element.total_curr_fy+"</td><td>"+element.total_exp_upto_dt+"</td></tr>";
						Sno++; PrevHoa = element.hoa_no; HoaMonTotal = Number(HoaMonTotal) + Number(element.month);
					});
					TableStr += "<tr><td colspan='5' align='right'>HOA WISE TOTAL</td><td></td><td></td><td align='right'>"+HoaMonTotal.toFixed(2)+"</td><td></td><td></td></tr>";
					TableStr += "<tr><td colspan='5' align='right'>TOTAL</td><td></td><td></td><td align='right'>"+HoaTotal.toFixed(2)+"</td><td></td><td></td></tr>";
					//$('.example tr:last').after(TableStr);
					$('.example').append(TableStr);
					$('.example').DataTable();
				}
			}
		});
	}
	
	function MonthlyExpenditure1(TitleStr){ 
		var BudUnit 		= $("#cmb_unit").val();
		var BudFinYear 	 	= $("#cmb_fy").val();
		var BudDiscipline 	= $("#cmb_discipline").val();
		var BudHoa 	 		= $("#cmb_hoa").val();
		var BudMode 	 	= $("#cmb_mode").val();
		var BudMonth 	 	= $("#cmb_month").val();
		var RupeesIn 	 	= $("#cmb_rupees").val();
		$('.example').DataTable({	
			"ajax": {
				"url": "ajax/DataTableDataReports.php",
				"contentType": "application/x-www-form-urlencoded;charset=UTF-8",
				"dataType":"json",
				"data": function ( d ) {
					d.code 			= "EXPMO";
					d.BudUnit 		= BudUnit;
					d.BudFinYear  	= BudFinYear;
					d.BudDiscipline = BudDiscipline;
					d.BudHoa 		= BudHoa;
					d.BudMode 		= BudMode;
					d.BudMonth 		= BudMonth;
					d.BudYear 		= "";
					d.BudThreeQtr 	= "";
					d.BudHalfYear 	= "";
					d.BudQuarter 	= "";
					d.RupeesIn 		= RupeesIn;
				},
				type:"post",
				/*"dataSrc": function ( json ) {
					//Make your callback here.
					alert(JSON.stringify(json.valid));
					//return json.data;
				} */
			},				
			"columns": [
				{
					"data": "vuid",
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},	
				{ "data": "hoa_no", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "work_name", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},					
				{ "data": "name_contractor", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "ccno_wono", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "wo_amount", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}else if(data == null){
						return "";
					}else{
						return (Number(data)).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
					}
				}},	
				{ "data": "total_prev_fy", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}else if(data == null){
						return "";
					}else{
						return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
					}
				}},	
				{ "data": "month", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}else if(data == null){
						return "";
					}else{
						return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
					}
				}},	
				{ "data": "total_curr_fy", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}else if(data == null){
						return "";
					}else{
						return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
					}
				}},	
				{ "data": "total_exp_upto_dt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}else if(data == null){
						return "";
					}else{
						return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
					}
				}}				
			],
			//CALULATE FOOTER TOTALS ON FOTER CALLBACK	
			"footerCallback": function ( row, data, start, end, display ) {
				var api = this.api(), data;
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
				 
				api.columns('.sum', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
				api.columns('.sum1', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
			},
			// "fnRowCallback" : function(nRow, aData, iDisplayIndex){
				// $("td:first", nRow).html(iDisplayIndex +1);
			   // return nRow;
			// },
			"columnDefs": [
				{ className: "text-right", "targets": [5,6,7,8] },
				{
					//targets: [4,5,6,7,8],
					/*render(v){
					  var x = Number(v).toFixed(2);
					  return x.toLocaleString('en-IN')
					}*/
					/*"render": function ( data, type, row ) {
						return data.toLocaleString('en-IN');
					},
					"targets": 4*/
				},
				{ "visible": false, "targets": [1] }
			],
			
			"drawCallback": function(settings){ 
				var api = this.api(); //console.log(this);//alert(JSON.stringify(api));
				var rows = api.rows({page:'current'}).nodes(); //console.log("CONSOLE-PRINT = "+rows);//alert(rows);
				var last=null;
				var subTotal = new Array();
				var groupID = -1;
				var aData = new Array();
				var index = 0;
				api.column(1, {page:'current'}).data().each(function (group, i){
				   	//console.log(group+">>>"+i);
					var vals = api.row(api.row($(rows).eq(i)).index()).data();  //alert(JSON.stringify(vals));
					var woAmt = vals['wo_amount'] ? parseFloat(vals['wo_amount']) : 0; //alert(woAmt);
					var monAmt = vals['month'] ? parseFloat(vals['month']) : 0;
					if(typeof aData[group] == 'undefined'){
						aData[group] = new Array();
						aData[group].rows = [];
						aData[group].woAmt = [];
						aData[group].monAmt = [];
					}
				  
					aData[group].rows.push(i); 
					aData[group].woAmt.push(woAmt); 
					aData[group].monAmt.push(monAmt); 
					//console.log(JSON.stringify(rows)+">>>"+i);
					//alert (JSON.stringify(myObj));
					if ( last !== group ) {
						$(rows).eq( i ).before(
							'<tr class="group"><td colspan="10" align="left" style="background:#551885; color:#fff"><span class="mup_pending group-text">Head of Account Number : '+group+'</span></td></tr>'
						);
	 
						last = group;
					}
					
				});
		
				var idx= 0;
				for(var worder in aData){
					idx =  Math.max.apply(Math,aData[worder].rows);
					var sum = 0; var sum1 = 0; 
					$.each(aData[worder].woAmt,function(k,v){
						sum = sum + v;
						sum1 = sum1 + v;
					});
					//console.log(aData[worder].woAmt);
					var WoSum = sum.toFixed(2);
					var MonSum = sum1.toFixed(2);
					//$(rows).eq( idx ).before('<tr class="group"><td colspan="10" align="left" style="background:#EBEBEB; color:#fff"><span class="mup_pending group-text">Markup list for the Month of '+group+'</span></td></tr>');
					$(rows).eq( idx ).after('<tr class="group"><td colspan="4" align="right"><b>TOTAL</b></td>'+'<td align="right">'+WoSum+'</td><td></td><td>'+MonSum+'</td><td></td><td></td></tr>');
				};
				
				
				/*var api = this.api();
				var rows = api.rows( {page:'current'} ).nodes();
				var last=null;
	 
				api.column(1, {page:'current'} ).data().each( function ( group, i ) {
					if ( last !== group ) {
						$(rows).eq( i ).before(
							'<tr class="group"><td colspan="10" align="left" style="background:#EBEBEB; color:#fff"><span class="mup_pending group-text">Markup list for the Month of '+group+'</span></td></tr>'
						);
	 
						last = group;
					}
				} );*/
				
				/*var api   = this.api();
				var rows  = api.rows( {page:'current'} ).nodes();
				var last  = null;     
				var sum   = 0;
				var groupColumn = 1; //index of column which you are going to group by.
				var amtColumn   = 7; // index of column which you are going to sum.
				api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
				  if ( last !== group ) {
					api.rows().data().each( function(item){ //alert(group);//
																	alert(JSON.stringify(item));
					  //if (item[groupColumn] == group){ 
						sum = sum + item[amtColumn]; //alert(item[amtColumn]);
					  //}
					});
					$(rows).eq( i ).before(
						'<tr class="group"><td colspan="10" align="left" style="background:#EBEBEB; color:#fff"><span class="mup_pending group-text">Markup list for the Month of '+group+'<b>'+ sum +'</b></span></td></tr>'
					);
					last = group;
					sum  = 0;
				  }
				});*/
				
				
				
			},
			
			
			//dom: "<'row'<'col-sm-12'<'toolbar'>>>lBfrtip",
			dom:"lBfrtip",
			// dom:  "<'row'<'col-sm-1'l><'col-sm-1'B><'col-sm-6'<'toolbar'>><'col-sm-3'f>>",
			fnInitComplete: function(){
			   $('div.toolbar').html(' LIST OF ENTREPRENEUR WITH BUSINESS PLAN');
			 },
			 buttons: [
				'copy', 
				'csv', 
				'excel', 
				{
					extend: 'pdf',
				 	text: 'PDF',
				 	title: TitleStr,
				 	download: 'download',
				 	header: true
			   	}, 
			   	'print', 
			],
			"iDisplayLength": -1,
			/*scrollY:        "300px",
			fixedHeader: {
				header: true,
				"offsetTop" : 100 
			},*/
			/*scrollX: "700px",
            fixedColumns: {
				columns: true,
                leftColumns: 3
            }*/
			/*fixedColumns: {
				column: true,
				left: 5,
            	right: 1
			},*/
		});
	}
	
	
	
	function FinancialPhysicalProgress(){ 
		var BudUnit 		= $("#cmb_unit").val();
		var BudFinYear 	 	= $("#cmb_fy").val();
		var BudMonth 	 	= $("#cmb_month").val();
		var RupeesIn 	 	= $("#cmb_rupees").val();
		var code 			= "FPPM";
		var BudDiscipline 	= "";
		var BudHoa 			= "";
		var BudMode 		= "";
		var BudYear 		= "";
		var BudThreeQtr 	= "";
		var BudHalfYear 	= "";
		var BudQuarter 		= "";
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/DataTableDataReports.php', 
			contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			data: { code: code, BudUnit: BudUnit, BudFinYear: BudFinYear, BudDiscipline: BudDiscipline, BudHoa: BudHoa, BudMode: BudMode, BudMonth: BudMonth, BudYear: BudYear, BudThreeQtr: BudThreeQtr, BudHalfYear: BudHalfYear, BudQuarter: BudQuarter, RupeesIn: RupeesIn }, 
			dataType: 'json',
			success: function (data) {  
				if(data != null){ 
					$.each(data, function(index, element) {
						var ProSanctAmt 	= Number(element.ProSanctAmt);
						var TotalActAmt 	= Number(element.TotalActAmt);
						var TotalCommitAmt 	= Number(element.TotalCommitAmt);
						var ActualExpAmt 	= Number(element.ActualExpAmt);
						var FinProgPerc 	= Number(element.FinProgPerc);
						var PhyProgPerc 	= Number(element.PhyProgPerc);
						$("#PsAmt").html(ProSanctAmt.toFixed(2));
						$("#TotActTaken").html(TotalActAmt.toFixed(2));
						$("#TotCommAmt").html(TotalCommitAmt.toFixed(2));
						$("#ActExpUpDt").html(ActualExpAmt.toFixed(2));
						$("#FinPro").html(FinProgPerc.toFixed(2));
						$("#PhyPro").html(PhyProgPerc.toFixed(2));
					});
				}
			}
		});
		
	}
	
	
	function PeriodicExpenditure(TitleStr){ 
		var BudUnit 		= $("#cmb_unit").val();
		var BudFinYear 	 	= $("#cmb_fy").val();
		var BudDiscipline 	= $("#cmb_discipline").val();
		var BudHoa 	 		= $("#cmb_hoa").val();
		var BudMode 	 	= $("#cmb_mode").val();
		var BudMonth 	 	= $("#cmb_month").val();
		var RupeesIn 	 	= $("#cmb_rupees").val();
		var FromDate 		= $("#txt_from_date").val();
		var ToDate 			= $("#txt_to_date").val();
		$('.example').DataTable({	
			"ajax": {
				"url": "ajax/DataTableDataReports.php",
				"contentType": "application/x-www-form-urlencoded;charset=UTF-8",
				"dataType":"json",
				"data": function ( d ) {
					d.code 			= "EXPPE";
					d.BudUnit 		= BudUnit;
					d.BudFinYear  	= BudFinYear;
					d.BudDiscipline = BudDiscipline;
					d.BudHoa 		= BudHoa;
					d.BudMode 		= BudMode;
					d.BudMonth 		= BudMonth;
					d.BudYear 		= "";
					d.BudThreeQtr 	= "";
					d.BudHalfYear 	= "";
					d.BudQuarter 	= "";
					d.RupeesIn 		= RupeesIn;
					d.FromDate		= FromDate;
					d.ToDate		= ToDate;
				},
				type:"post",
				/*"dataSrc": function ( json ) {
					//Make your callback here.
					alert(JSON.stringify(json.valid));
					//return json.data;
				} */
			},				
			"columns": [
				{
					"data": "vuid",
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},	
				{ "data": "hoa_no", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "work_name", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},					
				{ "data": "name_contractor", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "ccno_wono", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "wo_amount", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}else if(data == null){
						return "";
					}else{
						return (Number(data)).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
					}
				}},	
				{ "data": "total_prev_fy", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}else if(data == null){
						return "";
					}else{
						return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
					}
				}},	
				{ "data": "month", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}else if(data == null){
						return "";
					}else{
						return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
					}
				}},	
				{ "data": "total_curr_fy", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}else if(data == null){
						return "";
					}else{
						return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
					}
				}},	
				{ "data": "total_exp_upto_dt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}else if(data == null){
						return "";
					}else{
						return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
					}
				}}				
			],
			//CALULATE FOOTER TOTALS ON FOTER CALLBACK	
			"footerCallback": function ( row, data, start, end, display ) {
				var api = this.api(), data;
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
				 
				api.columns('.sum', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
				api.columns('.sum1', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
			},
			// "fnRowCallback" : function(nRow, aData, iDisplayIndex){
				// $("td:first", nRow).html(iDisplayIndex +1);
			   // return nRow;
			// },
			"columnDefs": [
				{ className: "text-right", "targets": [5,6,7,8] },
				{
					//targets: [4,5,6,7,8],
					/*render(v){
					  var x = Number(v).toFixed(2);
					  return x.toLocaleString('en-IN')
					}*/
					/*"render": function ( data, type, row ) {
						return data.toLocaleString('en-IN');
					},
					"targets": 4*/
				},
				{ "visible": false, "targets": [1] }
			],
			
			"drawCallback": function(settings){ 
				var api = this.api(); //console.log(this);//alert(JSON.stringify(api));
				var rows = api.rows({page:'current'}).nodes(); //console.log("CONSOLE-PRINT = "+rows);//alert(rows);
				var last=null;
				var subTotal = new Array();
				var groupID = -1;
				var aData = new Array();
				var index = 0;
				api.column(1, {page:'current'}).data().each(function (group, i){
				   	//console.log(group+">>>"+i);
					var vals = api.row(api.row($(rows).eq(i)).index()).data();  //alert(JSON.stringify(vals));
					var woAmt = vals['wo_amount'] ? parseFloat(vals['wo_amount']) : 0; //alert(woAmt);
					if(typeof aData[group] == 'undefined'){
						aData[group] = new Array();
						aData[group].rows = [];
						aData[group].woAmt = [];
					}
				  
					aData[group].rows.push(i); 
					aData[group].woAmt.push(woAmt); 
					//console.log(JSON.stringify(rows)+">>>"+i);
					//alert (JSON.stringify(myObj));
					if ( last !== group ) {
						$(rows).eq( i ).before(
							'<tr class="group"><td colspan="10" align="left" style="background:#551885; color:#fff"><span class="mup_pending group-text">Head of Account Number : '+group+'</span></td></tr>'
						);
	 
						last = group;
					}
					
				});
		
				var idx= 0;
				for(var worder in aData){
					idx =  Math.max.apply(Math,aData[worder].rows);
					var sum = 0; 
					$.each(aData[worder].woAmt,function(k,v){
						sum = sum + v;
					});
					console.log(aData[worder].woAmt);
					var WoSum = sum.toFixed(2);
					//$(rows).eq( idx ).before('<tr class="group"><td colspan="10" align="left" style="background:#EBEBEB; color:#fff"><span class="mup_pending group-text">Markup list for the Month of '+group+'</span></td></tr>');
					$(rows).eq( idx ).after('<tr class="group"><td colspan="4" align="right"><b>TOTAL</b></td>'+'<td align="right">'+WoSum+'</td><td></td><td></td><td></td><td></td></tr>');
				};
				
				
				/*var api = this.api();
				var rows = api.rows( {page:'current'} ).nodes();
				var last=null;
	 
				api.column(1, {page:'current'} ).data().each( function ( group, i ) {
					if ( last !== group ) {
						$(rows).eq( i ).before(
							'<tr class="group"><td colspan="10" align="left" style="background:#EBEBEB; color:#fff"><span class="mup_pending group-text">Markup list for the Month of '+group+'</span></td></tr>'
						);
	 
						last = group;
					}
				} );*/
				
				/*var api   = this.api();
				var rows  = api.rows( {page:'current'} ).nodes();
				var last  = null;     
				var sum   = 0;
				var groupColumn = 1; //index of column which you are going to group by.
				var amtColumn   = 7; // index of column which you are going to sum.
				api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
				  if ( last !== group ) {
					api.rows().data().each( function(item){ //alert(group);//
																	alert(JSON.stringify(item));
					  //if (item[groupColumn] == group){ 
						sum = sum + item[amtColumn]; //alert(item[amtColumn]);
					  //}
					});
					$(rows).eq( i ).before(
						'<tr class="group"><td colspan="10" align="left" style="background:#EBEBEB; color:#fff"><span class="mup_pending group-text">Markup list for the Month of '+group+'<b>'+ sum +'</b></span></td></tr>'
					);
					last = group;
					sum  = 0;
				  }
				});*/
				
				
				
			},
			
			
			//dom: "<'row'<'col-sm-12'<'toolbar'>>>lBfrtip",
			dom:"lBfrtip",
			// dom:  "<'row'<'col-sm-1'l><'col-sm-1'B><'col-sm-6'<'toolbar'>><'col-sm-3'f>>",
			fnInitComplete: function(){
			   $('div.toolbar').html(' LIST OF ENTREPRENEUR WITH BUSINESS PLAN');
			 },
			 buttons: [
				'copy', 
				'csv', 
				'excel', 
				{
					extend: 'pdf',
				 	text: 'PDF',
				 	title: TitleStr,
				 	download: 'download',
				 	header: true
			   	}, 
			   	'print', 
			],
			"iDisplayLength": -1,
			/*scrollY:        "300px",
			fixedHeader: {
				header: true,
				"offsetTop" : 100 
			},*/
			/*scrollX: "700px",
            fixedColumns: {
				columns: true,
                leftColumns: 3
            }*/
			/*fixedColumns: {
				column: true,
				left: 5,
            	right: 1
			},*/
		});
	}
	
	
	function VoucherExpenditure(TitleStr){ 
		var BudUnit 		= $("#cmb_unit").val(); 
		var BudFinYear 	 	= $("#cmb_fy").val(); 
		var BudDiscipline 	= $("#cmb_discipline").val(); 
		var BudHoa 	 		= $("#cmb_hoa").val();
		//var BudMode 	 	= $("#cmb_mode").val();
		var RupeesIn 	 	= $("#cmb_rupees").val();
		var FromDate 		= $("#txt_from_date").val();
		var ToDate 			= $("#txt_to_date").val();	
		$('.example').DataTable({	
			"ajax": {
				"url": "ajax/DataTableDataReports.php",
				"contentType": "application/x-www-form-urlencoded;charset=UTF-8",
				"dataType":"json",
				"data": function ( d ) {
					d.code 			= "EXPVOU";
					d.BudUnit 		= BudUnit;
					d.BudFinYear  	= BudFinYear;
					d.BudDiscipline = BudDiscipline;
					d.BudHoa 		= BudHoa;
					d.BudMode 		= "";
					d.BudMonth 		= "";
					d.BudYear 		= "";
					d.BudThreeQtr 	= "";
					d.BudHalfYear 	= "";
					d.BudQuarter 	= "";
					d.FromDate 		= FromDate;
					d.ToDate 		= ToDate;
					d.RupeesIn 		= RupeesIn;
				},
				type:"post",
				/*"dataSrc": function ( json ) {
					//Make your callback here.
					alert(JSON.stringify(json.valid));
					//return json.data;
				} */
			},				
			"columns": [
				{
					"data": "vuid",
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},	
				{ "data": "vr_no", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "vr_dt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},					
				{ "data": "item", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return data;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "indentor", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}
					if(data == null){
						return "";
					}
					return data;
				}},	
				{ "data": "vr_amt", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}else if(data == null){
						return "";
					}else{
						return (Number(data)).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
					}
				}},	
				{ "data": "ccno", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}else if(data == null){
						return "";
					}else{
						return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
					}
				}},	
				{ "data": "hoa", "render": function ( data, type, full, meta ) {
					if(data == 0){
						return null;
					}else if(data == null){
						return "";
					}else{
						return (data).toLocaleString('en-IN', {minimumFractionDigits: 2});//meta.settings.fnFormatNumber(row.month);
					}
				}}				
			],
			//CALULATE FOOTER TOTALS ON FOTER CALLBACK	
			"footerCallback": function ( row, data, start, end, display ) {
				var api = this.api(), data;
				// Remove the formatting to get integer data for summation
				var intVal = function ( i ) {
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '')*1 :
						typeof i === 'number' ?
							i : 0;
				};
				 
				api.columns('.sum', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
				api.columns('.sum1', { page: 'current'}).every( function () {
				  var sum = this
					.data()
					.reduce( function (a, b) {
						return intVal(a) + intVal(b);
					}, 0 );
				  
				  this.footer().innerHTML = (sum).toLocaleString('en-IN', {minimumFractionDigits: 2});//.toFixed(2);
				} );
			},
			// "fnRowCallback" : function(nRow, aData, iDisplayIndex){
				// $("td:first", nRow).html(iDisplayIndex +1);
			   // return nRow;
			// },
			
			
			
			//dom: "<'row'<'col-sm-12'<'toolbar'>>>lBfrtip",
			dom:"lBfrtip",
			// dom:  "<'row'<'col-sm-1'l><'col-sm-1'B><'col-sm-6'<'toolbar'>><'col-sm-3'f>>",
			fnInitComplete: function(){
			   $('div.toolbar').html(' LIST OF ENTREPRENEUR WITH BUSINESS PLAN');
			 },
			 buttons: [
				'copy', 
				'csv', 
				'excel', 
				{
					extend: 'pdf',
				 	text: 'PDF',
				 	title: TitleStr,
				 	download: 'download',
				 	header: true
			   	}, 
			   	'print', 
			],
			"iDisplayLength": -1,
			/*scrollY:        "300px",
			fixedHeader: {
				header: true,
				"offsetTop" : 100 
			},*/
			/*scrollX: "700px",
            fixedColumns: {
				columns: true,
                leftColumns: 3
            }*/
			/*fixedColumns: {
				column: true,
				left: 5,
            	right: 1
			},*/
		});
	}
	
	function BudgetExpenditureVariation(){ 
		var BudUnit 		= $("#cmb_unit").val();
		var BudFinYear 	 	= $("#cmb_fy").val();
		var RupeesIn 	 	= $("#cmb_rupees").val();
		var code 			= "BEPV";
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/DataTableDataReports.php', 
			contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			data: { code: code, BudUnit: BudUnit, BudFinYear: BudFinYear, RupeesIn: RupeesIn }, 
			dataType: 'json',
			success: function (data) {  
				var ExpData = data['data'];
				var PropAmt 	= Number(ExpData['PropAmt']); 
				var RevAmt 		= Number(ExpData['RevAmt']); 
				$("#PBE").html(PropAmt.toFixed(2));
				$("#PRE").html(RevAmt.toFixed(2));
				var CommitPlan 	= ExpData['CommitPlan']; 
				var ActPlan 	= ExpData['ActPlan']; 
				var i;
				var TotalCommitAmt = 0; var TotalActAmt = 0;
				for(i = 1; i <= 12; i++){
					var CommitAmt 	= CommitPlan[i];
					var ActAmt 		= ActPlan[i];
					if(RupeesIn == "L"){
						ActAmt = ActAmt / 100000;
					}else if(RupeesIn == "C"){
						ActAmt = ActAmt / 10000000;
					}
					var VariAmt 	= Number(CommitAmt) - Number(ActAmt); //alert("C = "+CommitAmt+" ; A = "+ActAmt+" ; V = "+VariAmt);
					$("#PE"+i).html(CommitAmt.toFixed(2));
					$("#AE"+i).html(ActAmt.toFixed(2));
					$("#VE"+i).html(VariAmt.toFixed(2));
					TotalCommitAmt 	= Number(TotalCommitAmt) + Number(CommitAmt);
					TotalActAmt 	= Number(TotalActAmt) + Number(ActAmt);
					
				}
				$("#PETOT").html(TotalCommitAmt.toFixed(2));
				$("#AETOT").html(TotalActAmt.toFixed(2));
				var TotalVarAmt = Number(TotalCommitAmt) - Number(TotalActAmt);
				$("#VETOT").html(TotalVarAmt.toFixed(2));
				/*if(data != null){ 
					$.each(ExpData, function(index, element) { 
						var PropAmt 	= Number(element.PropAmt); 
						var RevAmt 		= Number(element.RevAmt);
						var CommitPlan 	= element.CommitPlan;
						var ActPlan 	= element.ActPlan;
						$("#PBE").html(PropAmt.toFixed(2));
						$("#PRE").html(RevAmt.toFixed(2));
						var i; //alert(CommitPlan);
						for(i = 0; i <= 12; i++){
							//alert(CommitPlan[i]);
						}
					});
				}*/
			}
		});
		
	}
//});
