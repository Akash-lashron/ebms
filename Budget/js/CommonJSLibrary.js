//$(document).ready(function() {
	function YearlyExpenditure(){ 
		var BudUnit 		= $("#cmb_unit").val();
		var BudFinYear 	 	= $("#cmb_fy").val();
		var BudDiscipline 	= $("#cmb_discipline").val();
		var BudHoa 	 		= $("#cmb_hoa").val();
		var BudMode 	 	= $("#cmb_mode").val();
		var BudYear 	 	= $("#txt_year").val();
		var RupeesIn 	 	= $("#cmb_rupees").val();
		var ScheduleOFAct   = $("#cmb_scheulof_act").val();
		var MajorItems 	   = $("#cmb_major_item").val();
		var PlantService 	 = $("#cmb_plant_ser").val();
		var BudMonth 		= "";
		var BudThreeQtr 	= "";
		var BudHalfYear 	= "";
		var BudQuarter 		= "";
		var code 			= "EXPYE";
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/DataTableDataReports.php', 
			data: { BudUnit: BudUnit,ScheduleOFAct: ScheduleOFAct,MajorItems: MajorItems,PlantService: PlantService,BudFinYear: BudFinYear, BudDiscipline: BudDiscipline, BudHoa: BudHoa, BudMode: BudMode, BudMonth: BudMonth, RupeesIn: RupeesIn, code: code, BudYear: BudYear, BudThreeQtr: BudHalfYear, BudQuarter: BudQuarter }, 
			dataType: 'json',
			success: function (data) {   //alert(data['computer_code_no']);
				if(data != null){ //console.log(data);
					var Valid = data['valid'];
					var Result = data['data'];
					var TableStr = ""; var Sno = 1; var PrevHoa = ""; 
					var Mon1Amt = 0; var Mon2Amt = 0; var Mon3Amt = 0;
					var Mon4Amt = 0; var Mon5Amt = 0; var Mon6Amt = 0; 
					var Mon7Amt = 0; var Mon8Amt = 0; var Mon9Amt = 0; 
					var Mon10Amt = 0; var Mon11Amt = 0; var Mon12Amt = 0; 
					var HoaMon1Total = 0;  var HoaMon2Total = 0;  var HoaMon3Total = 0; var HoaQtr1Total = 0;
					var HoaMon4Total = 0;  var HoaMon5Total = 0;  var HoaMon6Total = 0; var HoaQtr2Total = 0;
					var HoaMon7Total = 0;  var HoaMon8Total = 0;  var HoaMon9Total = 0; var HoaQtr3Total = 0;
					var HoaMon10Total = 0;  var HoaMon11Total = 0;  var HoaMon12Total = 0; var HoaQtr4Total = 0;
					var HoaTotal1 = 0; var HoaTotal2 = 0; var HoaTotal3 = 0;
					var HoaTotal4 = 0; var HoaTotal5 = 0; var HoaTotal6 = 0; 
					var HoaTotal7 = 0; var HoaTotal8 = 0; var HoaTotal9 = 0;
					var HoaTotal10 = 0; var HoaTotal11 = 0; var HoaTotal12 = 0;
					var Qtr1Total = 0; var Q1Total = 0; 
					var Qtr2Total = 0; var Q2Total = 0; 
					var Qtr3Total = 0; var Q3Total = 0; 
					var Qtr4Total = 0; var Q4Total = 0; 
					$.each(Result, function(index, element) { 
						var Mon1Amt = element.apr;
						var Mon2Amt = element.may;
						var Mon3Amt = element.jun;
						var Mon4Amt = element.jul;
						var Mon5Amt = element.aug;
						var Mon6Amt = element.sep;
						var Mon7Amt = element.oct;
						var Mon8Amt = element.nov;
						var Mon9Amt = element.dec;
						var Mon10Amt = element.jan;
						var Mon11Amt = element.feb;
						var Mon12Amt = element.mar;
						var Qtr1Total = Number(Mon1Amt) + Number(Mon2Amt) + Number(Mon3Amt);
							Qtr1Total = Qtr1Total.toFixed(2);
						var Qtr2Total = Number(Mon4Amt) + Number(Mon5Amt) + Number(Mon6Amt);
							Qtr2Total = Qtr2Total.toFixed(2);
						var Qtr3Total = Number(Mon7Amt) + Number(Mon8Amt) + Number(Mon9Amt);
							Qtr3Total = Qtr3Total.toFixed(2);
						var Qtr4Total = Number(Mon10Amt) + Number(Mon11Amt) + Number(Mon12Amt);
							Qtr4Total = Qtr4Total.toFixed(2);
						if(element.hoa_no != PrevHoa){
							if(PrevHoa != ""){
								TableStr += "<tr><td colspan='5' align='right'>TOTAL</td><td></td><td></td><td align='right'>"+HoaMon1Total.toFixed(2)+"</td><td align='right'>"+HoaMon2Total.toFixed(2)+"</td><td align='right'>"+HoaMon3Total.toFixed(2)+"</td><td align='right'>"+HoaQtr1Total.toFixed(2)+"</td><td align='right'>"+HoaMon4Total.toFixed(2)+"</td><td align='right'>"+HoaMon5Total.toFixed(2)+"</td><td align='right'>"+HoaMon6Total.toFixed(2)+"</td><td align='right'>"+HoaQtr2Total.toFixed(2)+"</td><td align='right'>"+HoaMon7Total.toFixed(2)+"</td><td align='right'>"+HoaMon8Total.toFixed(2)+"</td><td align='right'>"+HoaMon9Total.toFixed(2)+"</td><td align='right'>"+HoaQtr3Total.toFixed(2)+"</td><td align='right'>"+HoaMon10Total.toFixed(2)+"</td><td align='right'>"+HoaMon11Total.toFixed(2)+"</td><td align='right'>"+HoaMon12Total.toFixed(2)+"</td><td align='right'>"+HoaQtr4Total.toFixed(2)+"</td><td></td><td></td></tr>";
							  	HoaMon1Total = 0; HoaMon2Total = 0; HoaMon3Total = 0; HoaMon4Total = 0; HoaMon5Total = 0; HoaMon6Total = 0; HoaMon7Total = 0; HoaMon8Total = 0; HoaMon9Total = 0; HoaMon10Total = 0; HoaMon11Total = 0; HoaMon12Total = 0; HoaQtr1Total = 0; HoaQtr2Total = 0; HoaQtr3Total = 0; HoaQtr4Total = 0;
							}
							TableStr += "<tr><td colspan='25' style='background-color:#10478A; color:#fff'>Head of Account : "+element.hoa_no+"</td></tr>";
						}
						if((element.ccno == "")||(element.ccno == "0")){
							var Mon1AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='4' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon1Amt+"</u></span>";
							var Mon2AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='5' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon2Amt+"</u></span>";
							var Mon3AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='6' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon3Amt+"</u></span>";
							var Mon4AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='7' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon4Amt+"</u></span>";
							var Mon5AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='8' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon5Amt+"</u></span>";
							var Mon6AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='9' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon6Amt+"</u></span>";
							var Mon7AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='10' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon7Amt+"</u></span>";
							var Mon8AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='11' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon8Amt+"</u></span>";
							var Mon9AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='12' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon9Amt+"</u></span>";
							var Mon10AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='1' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon10Amt+"</u></span>";
							var Mon11AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='2' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon11Amt+"</u></span>";
							var Mon12AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='3' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon12Amt+"</u></span>";
						}else{
							var Mon1AmtStr = Mon1Amt;
							var Mon2AmtStr = Mon2Amt;
							var Mon3AmtStr = Mon3Amt;
							var Mon4AmtStr = Mon4Amt;
							var Mon5AmtStr = Mon5Amt;
							var Mon6AmtStr = Mon6Amt;
							var Mon7AmtStr = Mon7Amt;
							var Mon8AmtStr = Mon8Amt;
							var Mon9AmtStr = Mon9Amt;
							var Mon10AmtStr = Mon10Amt;
							var Mon11AmtStr = Mon11Amt;
							var Mon12AmtStr = Mon12Amt;
						}
						TableStr += "<tr><td>"+Sno+"</td><td>"+element.hoa_no+"</td><td>"+element.work_name+"</td><td>"+element.name_contractor+"</td><td>"+element.ccno_wono+"</td><td>"+element.wo_amount+"</td><td>"+element.total_prev_fy+"</td><td align='right'>"+Mon1AmtStr+"</td><td align='right'>"+Mon2AmtStr+"</td><td align='right'>"+Mon3AmtStr+"</td><td align='right'>"+Qtr1Total+"</td><td align='right'>"+Mon4AmtStr+"</td><td align='right'>"+Mon5AmtStr+"</td><td align='right'>"+Mon6AmtStr+"</td><td align='right'>"+Qtr2Total+"</td><td align='right'>"+Mon7AmtStr+"</td><td align='right'>"+Mon8AmtStr+"</td><td align='right'>"+Mon9AmtStr+"</td><td align='right'>"+Qtr3Total+"</td><td align='right'>"+Mon10AmtStr+"</td><td align='right'>"+Mon11AmtStr+"</td><td align='right'>"+Mon12AmtStr+"</td><td align='right'>"+Qtr4Total+"</td><td>"+element.total_curr_fy+"</td><td>"+element.total_exp_upto_dt+"</td></tr>";
						Sno++; PrevHoa = element.hoa_no; 
						HoaMon1Total = Number(HoaMon1Total) + Number(Mon1Amt);
						HoaMon2Total = Number(HoaMon2Total) + Number(Mon2Amt);
						HoaMon3Total = Number(HoaMon3Total) + Number(Mon3Amt);
						HoaMon4Total = Number(HoaMon4Total) + Number(Mon4Amt);
						HoaMon5Total = Number(HoaMon5Total) + Number(Mon5Amt);
						HoaMon6Total = Number(HoaMon6Total) + Number(Mon6Amt);
						HoaMon7Total = Number(HoaMon7Total) + Number(Mon7Amt);
						HoaMon8Total = Number(HoaMon8Total) + Number(Mon8Amt);
						HoaMon9Total = Number(HoaMon9Total) + Number(Mon9Amt);
						HoaMon10Total = Number(HoaMon10Total) + Number(Mon10Amt);
						HoaMon11Total = Number(HoaMon11Total) + Number(Mon11Amt);
						HoaMon12Total = Number(HoaMon12Total) + Number(Mon12Amt);
						HoaQtr1Total  = Number(HoaQtr1Total)  + Number(Qtr1Total); 
						HoaQtr2Total  = Number(HoaQtr2Total)  + Number(Qtr2Total); 
						HoaQtr3Total  = Number(HoaQtr3Total)  + Number(Qtr3Total); 
						HoaQtr4Total  = Number(HoaQtr4Total)  + Number(Qtr4Total); 
						
						HoaTotal1 = Number(HoaTotal1) + Number(Mon1Amt); 
						HoaTotal2 = Number(HoaTotal2) + Number(Mon2Amt); 
						HoaTotal3 = Number(HoaTotal3) + Number(Mon3Amt); 
						HoaTotal4 = Number(HoaTotal4) + Number(Mon4Amt); 
						HoaTotal5 = Number(HoaTotal5) + Number(Mon5Amt); 
						HoaTotal6 = Number(HoaTotal6) + Number(Mon6Amt);
						HoaTotal7 = Number(HoaTotal7) + Number(Mon7Amt);
						HoaTotal8 = Number(HoaTotal8) + Number(Mon8Amt);
						HoaTotal9 = Number(HoaTotal9) + Number(Mon9Amt);
						HoaTotal10 = Number(HoaTotal10) + Number(Mon10Amt);
						HoaTotal11 = Number(HoaTotal11) + Number(Mon11Amt);
						HoaTotal12 = Number(HoaTotal12) + Number(Mon12Amt);
						Q1Total    = Number(Q1Total)  + Number(Qtr1Total);
						Q2Total    = Number(Q2Total)  + Number(Qtr2Total);
						Q3Total    = Number(Q3Total)  + Number(Qtr3Total);
						Q4Total    = Number(Q4Total)  + Number(Qtr4Total);
					});
					TableStr += "<tr><td colspan='5' align='right'>HOA WISE TOTAL</td><td></td><td></td><td align='right'>"+HoaMon1Total.toFixed(2)+"</td><td align='right'>"+HoaMon2Total.toFixed(2)+"</td><td align='right'>"+HoaMon3Total.toFixed(2)+"</td><td align='right'>"+HoaQtr1Total.toFixed(2)+"</td><td align='right'>"+HoaMon4Total.toFixed(2)+"</td><td align='right'>"+HoaMon5Total.toFixed(2)+"</td><td align='right'>"+HoaMon6Total.toFixed(2)+"</td><td align='right'>"+HoaQtr2Total.toFixed(2)+"</td><td align='right'>"+HoaMon7Total.toFixed(2)+"</td><td align='right'>"+HoaMon8Total.toFixed(2)+"</td><td align='right'>"+HoaMon9Total.toFixed(2)+"</td><td align='right'>"+HoaQtr3Total.toFixed(2)+"</td><td align='right'>"+HoaMon10Total.toFixed(2)+"</td><td align='right'>"+HoaMon11Total.toFixed(2)+"</td><td align='right'>"+HoaMon12Total.toFixed(2)+"</td><td align='right'>"+HoaQtr4Total.toFixed(2)+"</td><td></td><td></td></tr>";
					TableStr += "<tr><td colspan='5' align='right'>TOTAL</td><td></td><td></td><td align='right'>"+HoaTotal1.toFixed(2)+"</td><td align='right'>"+HoaTotal2.toFixed(2)+"</td><td align='right'>"+HoaTotal3.toFixed(2)+"</td><td align='right'>"+Q1Total.toFixed(2)+"</td><td align='right'>"+HoaTotal4.toFixed(2)+"</td><td align='right'>"+HoaTotal5.toFixed(2)+"</td><td align='right'>"+HoaTotal6.toFixed(2)+"</td><td align='right'>"+Q2Total.toFixed(2)+"</td><td align='right'>"+HoaTotal7.toFixed(2)+"</td><td align='right'>"+HoaTotal8.toFixed(2)+"</td><td align='right'>"+HoaTotal9.toFixed(2)+"</td><td align='right'>"+Q3Total.toFixed(2)+"</td><td align='right'>"+HoaTotal10.toFixed(2)+"</td><td align='right'>"+HoaTotal11.toFixed(2)+"</td><td align='right'>"+HoaTotal12.toFixed(2)+"</td><td align='right'>"+Q4Total.toFixed(2)+"</td><td></td><td></td></tr>";
					//$('.example tr:last').after(TableStr);
					$('.example').append(TableStr);
					$('.example').DataTable();
				}
			}
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
		var ScheduleOFAct   = $("#cmb_scheulof_act").val();
		var MajorItems 	   = $("#cmb_major_item").val();
		var PlantService 	 = $("#cmb_plant_ser").val();
		var BudMonth 		= ""; 
		var BudYear 		= "";
		var BudHalfYear 	= "";
		var BudQuarter 		= "";
		var code 			= "EXPTQ"; 
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/DataTableDataReports.php', 
			data: { BudUnit: BudUnit,ScheduleOFAct: ScheduleOFAct,MajorItems: MajorItems,PlantService: PlantService, BudFinYear: BudFinYear, BudDiscipline: BudDiscipline, BudHoa: BudHoa, BudMode: BudMode, BudMonth: BudMonth, RupeesIn: RupeesIn, code: code, BudYear: BudYear, BudThreeQtr: BudHalfYear, BudQuarter: BudQuarter }, 
			dataType: 'json',
			success: function (data) {   //alert(data['computer_code_no']);
				if(data != null){ //console.log(data);
					var Valid = data['valid'];
					var Result = data['data'];
					var TableStr = ""; var Sno = 1; var PrevHoa = ""; 
					var Mon1Amt = 0; var Mon2Amt = 0; var Mon3Amt = 0;
					var Mon4Amt = 0; var Mon5Amt = 0; var Mon6Amt = 0; 
					var Mon7Amt = 0; var Mon8Amt = 0; var Mon9Amt = 0; 
					var HoaMon1Total = 0;  var HoaMon2Total = 0;  var HoaMon3Total = 0; var HoaQtr1Total = 0;
					var HoaMon4Total = 0;  var HoaMon5Total = 0;  var HoaMon6Total = 0; var HoaQtr2Total = 0;
					var HoaMon7Total = 0;  var HoaMon8Total = 0;  var HoaMon9Total = 0; var HoaQtr3Total = 0;
					var HoaTotal1 = 0; var HoaTotal2 = 0; var HoaTotal3 = 0;
					var HoaTotal4 = 0; var HoaTotal5 = 0; var HoaTotal6 = 0; 
					var HoaTotal7 = 0; var HoaTotal8 = 0; var HoaTotal9 = 0;
					var Qtr1Total = 0; var Q1Total = 0; 
					var Qtr2Total = 0; var Q2Total = 0; 
					var Qtr3Total = 0; var Q3Total = 0; 
					$.each(Result, function(index, element) { 
						if(BudThreeQtr == "TQ1"){
							var Mon1Amt = element.apr;
							var Mon2Amt = element.may;
							var Mon3Amt = element.jun;
							var Mon4Amt = element.jul;
							var Mon5Amt = element.aug;
							var Mon6Amt = element.sep;
							var Mon7Amt = element.oct;
							var Mon8Amt = element.nov;
							var Mon9Amt = element.dec;
							var Month1  = 4;
							var Month2  = 5;
							var Month3  = 6;
							var Month4  = 7;
							var Month5  = 8;
							var Month6  = 9;
							var Month7  = 10;
							var Month8  = 11;
							var Month9  = 12;
						}
						var Qtr1Total = Number(Mon1Amt) + Number(Mon2Amt) + Number(Mon3Amt);
							Qtr1Total = Qtr1Total.toFixed(2);
						var Qtr2Total = Number(Mon4Amt) + Number(Mon5Amt) + Number(Mon6Amt);
							Qtr2Total = Qtr2Total.toFixed(2);
						var Qtr3Total = Number(Mon7Amt) + Number(Mon8Amt) + Number(Mon9Amt);
							Qtr3Total = Qtr3Total.toFixed(2);
						if(element.hoa_no != PrevHoa){
							if(PrevHoa != ""){
								TableStr += "<tr><td colspan='5' align='right'>TOTAL</td><td></td><td></td><td align='right'>"+HoaMon1Total.toFixed(2)+"</td><td align='right'>"+HoaMon2Total.toFixed(2)+"</td><td align='right'>"+HoaMon3Total.toFixed(2)+"</td><td align='right'>"+HoaQtr1Total.toFixed(2)+"</td><td align='right'>"+HoaMon4Total.toFixed(2)+"</td><td align='right'>"+HoaMon5Total.toFixed(2)+"</td><td align='right'>"+HoaMon6Total.toFixed(2)+"</td><td align='right'>"+HoaQtr2Total.toFixed(2)+"</td><td align='right'>"+HoaMon7Total.toFixed(2)+"</td><td align='right'>"+HoaMon8Total.toFixed(2)+"</td><td align='right'>"+HoaMon9Total.toFixed(2)+"</td><td align='right'>"+HoaQtr3Total.toFixed(2)+"</td><td></td><td></td></tr>";
							  	HoaMon1Total = 0; HoaMon2Total = 0; HoaMon3Total = 0; HoaMon4Total = 0; HoaMon5Total = 0; HoaMon6Total = 0; HoaMon7Total = 0; HoaMon8Total = 0; HoaMon9Total = 0; HoaQtr1Total = 0; HoaQtr2Total = 0; HoaQtr3Total = 0;
							}
							TableStr += "<tr><td colspan='21' style='background-color:#10478A; color:#fff'>Head of Account : "+element.hoa_no+"</td></tr>";
						}
						if((element.ccno == "")||(element.ccno == "0")){
							var Mon1AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='"+Month1+"' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon1Amt+"</u></span>";
							var Mon2AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='"+Month2+"' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon2Amt+"</u></span>";
							var Mon3AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='"+Month3+"' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon3Amt+"</u></span>";
							var Mon4AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='"+Month4+"' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon4Amt+"</u></span>";
							var Mon5AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='"+Month5+"' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon5Amt+"</u></span>";
							var Mon6AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='"+Month6+"' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon6Amt+"</u></span>";
							var Mon7AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='"+Month7+"' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon7Amt+"</u></span>";
							var Mon8AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='"+Month8+"' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon8Amt+"</u></span>";
							var Mon9AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='"+Month9+"' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon9Amt+"</u></span>";
						}else{
							var Mon1AmtStr = Mon1Amt;
							var Mon2AmtStr = Mon2Amt;
							var Mon3AmtStr = Mon3Amt;
							var Mon4AmtStr = Mon4Amt;
							var Mon5AmtStr = Mon5Amt;
							var Mon6AmtStr = Mon6Amt;
							var Mon7AmtStr = Mon7Amt;
							var Mon8AmtStr = Mon8Amt;
							var Mon9AmtStr = Mon9Amt;
						}
						TableStr += "<tr><td>"+Sno+"</td><td>"+element.hoa_no+"</td><td>"+element.work_name+"</td><td>"+element.name_contractor+"</td><td>"+element.ccno_wono+"</td><td>"+element.wo_amount+"</td><td>"+element.total_prev_fy+"</td><td align='right'>"+Mon1AmtStr+"</td><td align='right'>"+Mon2AmtStr+"</td><td align='right'>"+Mon3AmtStr+"</td><td align='right'>"+Qtr1Total+"</td><td align='right'>"+Mon4AmtStr+"</td><td align='right'>"+Mon5AmtStr+"</td><td align='right'>"+Mon6AmtStr+"</td><td align='right'>"+Qtr2Total+"</td><td align='right'>"+Mon7AmtStr+"</td><td align='right'>"+Mon8AmtStr+"</td><td align='right'>"+Mon9AmtStr+"</td><td align='right'>"+Qtr3Total+"</td><td>"+element.total_curr_fy+"</td><td>"+element.total_exp_upto_dt+"</td></tr>";
						Sno++; PrevHoa = element.hoa_no; 
						HoaMon1Total = Number(HoaMon1Total) + Number(Mon1Amt);
						HoaMon2Total = Number(HoaMon2Total) + Number(Mon2Amt);
						HoaMon3Total = Number(HoaMon3Total) + Number(Mon3Amt);
						HoaMon4Total = Number(HoaMon4Total) + Number(Mon4Amt);
						HoaMon5Total = Number(HoaMon5Total) + Number(Mon5Amt);
						HoaMon6Total = Number(HoaMon6Total) + Number(Mon6Amt);
						HoaMon7Total = Number(HoaMon7Total) + Number(Mon7Amt);
						HoaMon8Total = Number(HoaMon8Total) + Number(Mon8Amt);
						HoaMon9Total = Number(HoaMon9Total) + Number(Mon9Amt);
						HoaQtr1Total  = Number(HoaQtr1Total)  + Number(Qtr1Total); 
						HoaQtr2Total  = Number(HoaQtr2Total)  + Number(Qtr2Total); 
						HoaQtr3Total  = Number(HoaQtr3Total)  + Number(Qtr3Total); 
						
						HoaTotal1 = Number(HoaTotal1) + Number(Mon1Amt); 
						HoaTotal2 = Number(HoaTotal2) + Number(Mon2Amt); 
						HoaTotal3 = Number(HoaTotal3) + Number(Mon3Amt); 
						HoaTotal4 = Number(HoaTotal4) + Number(Mon4Amt); 
						HoaTotal5 = Number(HoaTotal5) + Number(Mon5Amt); 
						HoaTotal6 = Number(HoaTotal6) + Number(Mon6Amt);
						HoaTotal7 = Number(HoaTotal7) + Number(Mon7Amt);
						HoaTotal8 = Number(HoaTotal8) + Number(Mon8Amt);
						HoaTotal9 = Number(HoaTotal9) + Number(Mon9Amt);
						Q1Total    = Number(Q1Total)  + Number(Qtr1Total);
						Q2Total    = Number(Q2Total)  + Number(Qtr2Total);
						Q3Total    = Number(Q3Total)  + Number(Qtr3Total);
					});
					TableStr += "<tr><td colspan='5' align='right'>HOA WISE TOTAL</td><td></td><td></td><td align='right'>"+HoaMon1Total.toFixed(2)+"</td><td align='right'>"+HoaMon2Total.toFixed(2)+"</td><td align='right'>"+HoaMon3Total.toFixed(2)+"</td><td align='right'>"+HoaQtr1Total.toFixed(2)+"</td><td align='right'>"+HoaMon4Total.toFixed(2)+"</td><td align='right'>"+HoaMon5Total.toFixed(2)+"</td><td align='right'>"+HoaMon6Total.toFixed(2)+"</td><td align='right'>"+HoaQtr2Total.toFixed(2)+"</td><td align='right'>"+HoaMon7Total.toFixed(2)+"</td><td align='right'>"+HoaMon8Total.toFixed(2)+"</td><td align='right'>"+HoaMon9Total.toFixed(2)+"</td><td align='right'>"+HoaQtr3Total.toFixed(2)+"</td><td></td><td></td></tr>";
					TableStr += "<tr><td colspan='5' align='right'>TOTAL</td><td></td><td></td><td align='right'>"+HoaTotal1.toFixed(2)+"</td><td align='right'>"+HoaTotal2.toFixed(2)+"</td><td align='right'>"+HoaTotal3.toFixed(2)+"</td><td align='right'>"+Q1Total.toFixed(2)+"</td><td align='right'>"+HoaTotal4.toFixed(2)+"</td><td align='right'>"+HoaTotal5.toFixed(2)+"</td><td align='right'>"+HoaTotal6.toFixed(2)+"</td><td align='right'>"+Q2Total.toFixed(2)+"</td><td align='right'>"+HoaTotal7.toFixed(2)+"</td><td align='right'>"+HoaTotal8.toFixed(2)+"</td><td align='right'>"+HoaTotal9.toFixed(2)+"</td><td align='right'>"+Q3Total.toFixed(2)+"</td><td></td><td></td></tr>";
					//$('.example tr:last').after(TableStr);
					$('.example').append(TableStr);
					$('.example').DataTable();
				}
			}
		});
	}
	
	function HalfYearlyExpenditure(){ 
		var BudUnit 		= $("#cmb_unit").val();
		var BudFinYear 	 	= $("#cmb_fy").val();
		var BudDiscipline 	= $("#cmb_discipline").val();
		var BudHoa 	 		= $("#cmb_hoa").val();
		var BudMode 	 	= $("#cmb_mode").val();
		var BudHalfYear 	= $("#cmb_half_year").val();
		var RupeesIn 	 	= $("#cmb_rupees").val();
		var ScheduleOFAct   = $("#cmb_scheulof_act").val();
		var MajorItems 	   = $("#cmb_major_item").val();
		var PlantService 	 = $("#cmb_plant_ser").val();
		var BudMonth 		= "";
		var BudYear 		= "";
		var BudThreeQtr 	= "";
		var BudQuarter 		= "";
		var code 			= "EXPHY"; 
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/DataTableDataReports.php', 
			data: { BudUnit: BudUnit,ScheduleOFAct: ScheduleOFAct,MajorItems: MajorItems,PlantService: PlantService, BudFinYear: BudFinYear, BudDiscipline: BudDiscipline, BudHoa: BudHoa, BudMode: BudMode, BudMonth: BudMonth, RupeesIn: RupeesIn, code: code, BudYear: BudYear, BudThreeQtr: BudHalfYear, BudQuarter: BudQuarter }, 
			dataType: 'json',
			success: function (data) {   //alert(data['computer_code_no']);
				if(data != null){ //console.log(data);
					var Valid = data['valid'];
					var Result = data['data'];
					var TableStr = ""; var Sno = 1; var PrevHoa = ""; 
					var Mon1Amt = 0; var Mon2Amt = 0; var Mon3Amt = 0; 
					var HoaMon1Total = 0;  var HoaMon2Total = 0;  var HoaMon3Total = 0; var HoaQtr1Total = 0;
					var HoaMon4Total = 0;  var HoaMon5Total = 0;  var HoaMon6Total = 0; var HoaQtr2Total = 0;
					var HoaTotal1 = 0; var HoaTotal2 = 0; var HoaTotal3 = 0; 
					var HoaTotal4 = 0; var HoaTotal5 = 0; var HoaTotal6 = 0; 
					var Qtr1Total = 0; var Q1Total = 0;
					var Qtr2Total = 0; var Q2Total = 0;
					$.each(Result, function(index, element) { 
						if(BudHalfYear == "H1"){
							var Mon1Amt = element.apr;
							var Mon2Amt = element.may;
							var Mon3Amt = element.jun;
							var Mon4Amt = element.jul;
							var Mon5Amt = element.aug;
							var Mon6Amt = element.sep;
							var Month1  = 4;
							var Month2  = 5;
							var Month3  = 6;
							var Month4  = 7;
							var Month5  = 8;
							var Month6  = 9;
						}
						if(BudHalfYear == "H2"){
							var Mon1Amt = element.oct;
							var Mon2Amt = element.nov;
							var Mon3Amt = element.dec;
							var Mon4Amt = element.jan;
							var Mon5Amt = element.feb;
							var Mon6Amt = element.mar;
							var Month1  = 10;
							var Month2  = 11;
							var Month3  = 12;
							var Month4  = 1;
							var Month5  = 2;
							var Month6  = 3;
						}
						var Qtr1Total = Number(Mon1Amt) + Number(Mon2Amt) + Number(Mon3Amt);
							Qtr1Total = Qtr1Total.toFixed(2);
						var Qtr2Total = Number(Mon4Amt) + Number(Mon5Amt) + Number(Mon6Amt);
							Qtr2Total = Qtr2Total.toFixed(2);
						if(element.hoa_no != PrevHoa){
							if(PrevHoa != ""){
								TableStr += "<tr><td colspan='5' align='right'>TOTAL</td><td></td><td></td><td align='right'>"+HoaMon1Total.toFixed(2)+"</td><td align='right'>"+HoaMon2Total.toFixed(2)+"</td><td align='right'>"+HoaMon3Total.toFixed(2)+"</td><td align='right'>"+HoaQtr1Total.toFixed(2)+"</td><td align='right'>"+HoaMon4Total.toFixed(2)+"</td><td align='right'>"+HoaMon5Total.toFixed(2)+"</td><td align='right'>"+HoaMon6Total.toFixed(2)+"</td><td align='right'>"+HoaQtr2Total.toFixed(2)+"</td><td></td><td></td></tr>";
							  	HoaMon1Total = 0; HoaMon2Total = 0; HoaMon3Total = 0; HoaMon4Total = 0; HoaMon5Total = 0; HoaMon6Total = 0; HoaQtr1Total = 0; HoaQtr2Total = 0;
							}
							TableStr += "<tr><td colspan='17' style='background-color:#10478A; color:#fff'>Head of Account : "+element.hoa_no+"</td></tr>";
						}
						if((element.ccno == "")||(element.ccno == "0")){
							var Mon1AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='"+Month1+"' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon1Amt+"</u></span>";
							var Mon2AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='"+Month2+"' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon2Amt+"</u></span>";
							var Mon3AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='"+Month3+"' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon3Amt+"</u></span>";
							var Mon4AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='"+Month4+"' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon4Amt+"</u></span>";
							var Mon5AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='"+Month5+"' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon5Amt+"</u></span>";
							var Mon6AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='"+Month6+"' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon6Amt+"</u></span>";
						}else{
							var Mon1AmtStr = Mon1Amt;
							var Mon2AmtStr = Mon2Amt;
							var Mon3AmtStr = Mon3Amt;
							var Mon4AmtStr = Mon4Amt;
							var Mon5AmtStr = Mon5Amt;
							var Mon6AmtStr = Mon6Amt;
						}
						TableStr += "<tr><td>"+Sno+"</td><td>"+element.hoa_no+"</td><td>"+element.work_name+"</td><td>"+element.name_contractor+"</td><td>"+element.ccno_wono+"</td><td>"+element.wo_amount+"</td><td>"+element.total_prev_fy+"</td><td align='right'>"+Mon1AmtStr+"</td><td align='right'>"+Mon2AmtStr+"</td><td align='right'>"+Mon3AmtStr+"</td><td align='right'>"+Qtr1Total+"</td><td align='right'>"+Mon4AmtStr+"</td><td align='right'>"+Mon5AmtStr+"</td><td align='right'>"+Mon6AmtStr+"</td><td align='right'>"+Qtr2Total+"</td><td>"+element.total_curr_fy+"</td><td>"+element.total_exp_upto_dt+"</td></tr>";
						Sno++; PrevHoa = element.hoa_no; 
						HoaMon1Total = Number(HoaMon1Total) + Number(Mon1Amt);
						HoaMon2Total = Number(HoaMon2Total) + Number(Mon2Amt);
						HoaMon3Total = Number(HoaMon3Total) + Number(Mon3Amt);
						HoaMon4Total = Number(HoaMon4Total) + Number(Mon4Amt);
						HoaMon5Total = Number(HoaMon5Total) + Number(Mon5Amt);
						HoaMon6Total = Number(HoaMon6Total) + Number(Mon6Amt);
						HoaQtr1Total  = Number(HoaQtr1Total)  + Number(Qtr1Total); 
						HoaQtr2Total  = Number(HoaQtr2Total)  + Number(Qtr2Total); 
						
						HoaTotal1 = Number(HoaTotal1) + Number(Mon1Amt); 
						HoaTotal2 = Number(HoaTotal2) + Number(Mon2Amt); 
						HoaTotal3 = Number(HoaTotal3) + Number(Mon3Amt); 
						HoaTotal4 = Number(HoaTotal4) + Number(Mon4Amt); 
						HoaTotal5 = Number(HoaTotal5) + Number(Mon5Amt); 
						HoaTotal6 = Number(HoaTotal6) + Number(Mon6Amt);
						Q1Total    = Number(Q1Total)  + Number(Qtr1Total);
						Q2Total    = Number(Q2Total)  + Number(Qtr2Total);
					});
					TableStr += "<tr><td colspan='5' align='right'>HOA WISE TOTAL</td><td></td><td></td><td align='right'>"+HoaMon1Total.toFixed(2)+"</td><td align='right'>"+HoaMon2Total.toFixed(2)+"</td><td align='right'>"+HoaMon3Total.toFixed(2)+"</td><td align='right'>"+HoaQtr1Total.toFixed(2)+"</td><td align='right'>"+HoaMon4Total.toFixed(2)+"</td><td align='right'>"+HoaMon5Total.toFixed(2)+"</td><td align='right'>"+HoaMon6Total.toFixed(2)+"</td><td align='right'>"+HoaQtr2Total.toFixed(2)+"</td><td></td><td></td></tr>";
					TableStr += "<tr><td colspan='5' align='right'>TOTAL</td><td></td><td></td><td align='right'>"+HoaTotal1.toFixed(2)+"</td><td align='right'>"+HoaTotal2.toFixed(2)+"</td><td align='right'>"+HoaTotal3.toFixed(2)+"</td><td align='right'>"+Q1Total.toFixed(2)+"</td><td align='right'>"+HoaTotal4.toFixed(2)+"</td><td align='right'>"+HoaTotal5.toFixed(2)+"</td><td align='right'>"+HoaTotal6.toFixed(2)+"</td><td align='right'>"+Q2Total.toFixed(2)+"</td><td></td><td></td></tr>";
					//$('.example tr:last').after(TableStr);
					$('.example').append(TableStr);
					$('.example').DataTable();
				}
			}
		});
	}

	function QuarterExpenditure(){ 
		var BudUnit 		= $("#cmb_unit").val();
		var BudFinYear 	 	= $("#cmb_fy").val();
		var BudDiscipline 	= $("#cmb_discipline").val();
		var BudHoa 	 		= $("#cmb_hoa").val();
		var BudMode 	 	= $("#cmb_mode").val();
		var BudMonth 	 	= "";
		var RupeesIn 	 	= $("#cmb_rupees").val();
		var code 			= "EXPQU";
		var BudYear 		= "";
		var BudThreeQtr 	= "";
		var BudHalfYear 	= "";
		var BudQuarter 	 	= $("#cmb_quarter").val();
		var ScheduleOFAct   = $("#cmb_scheulof_act").val();
		var MajorItems 	   = $("#cmb_major_item").val();
		var PlantService 	 = $("#cmb_plant_ser").val();
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/DataTableDataReports.php', 
			data: { BudUnit: BudUnit,ScheduleOFAct: ScheduleOFAct,MajorItems: MajorItems,PlantService: PlantService, BudFinYear: BudFinYear, BudDiscipline: BudDiscipline, BudHoa: BudHoa, BudMode: BudMode, BudMonth: BudMonth, RupeesIn: RupeesIn, code: code, BudYear: BudYear, BudThreeQtr: BudHalfYear, BudQuarter: BudQuarter }, 
			dataType: 'json',
			success: function (data) {   //alert(data['computer_code_no']);
				if(data != null){ //console.log(data);
					var Valid = data['valid'];
					var Result = data['data'];
					var TableStr = ""; var Sno = 1; var PrevHoa = ""; 
					var Mon1Amt = 0; var Mon2Amt = 0; var Mon3Amt = 0; 
					var HoaMon1Total = 0;  var HoaMon2Total = 0;  var HoaMon3Total = 0; var HoaQtrTotal = 0;
					var HoaTotal1 = 0; var HoaTotal2 = 0; var HoaTotal3 = 0; var QtrTotal = 0; var QTotal = 0;
					$.each(Result, function(index, element) { 
						if(BudQuarter == "Q1"){
							var Mon1Amt = element.apr;
							var Mon2Amt = element.may;
							var Mon3Amt = element.jun;
							var Month1  = 4;
							var Month2  = 5;
							var Month3  = 6;
						}
						if(BudQuarter == "Q2"){
							var Mon1Amt = element.jul;
							var Mon2Amt = element.aug;
							var Mon3Amt = element.sep;
							var Month1  = 7;
							var Month2  = 8;
							var Month3  = 9;
						}
						if(BudQuarter == "Q3"){
							var Mon1Amt = element.oct;
							var Mon2Amt = element.nov;
							var Mon3Amt = element.dec;
							var Month1  = 10;
							var Month2  = 11;
							var Month3  = 12;
						}
						if(BudQuarter == "Q4"){
							var Mon1Amt = element.jan;
							var Mon2Amt = element.feb;
							var Mon3Amt = element.mar;
							var Month1  = 1;
							var Month2  = 2;
							var Month3  = 3;
						}
						var QtrTotal = Number(Mon1Amt) + Number(Mon2Amt) + Number(Mon3Amt);
							QtrTotal = QtrTotal.toFixed(2);
						if(element.hoa_no != PrevHoa){
							if(PrevHoa != ""){
								TableStr += "<tr><td colspan='5' align='right'>TOTAL</td><td></td><td></td><td align='right'>"+HoaMon1Total.toFixed(2)+"</td><td align='right'>"+HoaMon2Total.toFixed(2)+"</td><td align='right'>"+HoaMon3Total.toFixed(2)+"</td><td align='right'>"+HoaQtrTotal.toFixed(2)+"</td><td></td><td></td></tr>";
							  	HoaMon1Total = 0; HoaMon2Total = 0; HoaMon3Total = 0; HoaQtrTotal  = 0;
							}
							TableStr += "<tr><td colspan='13' style='background-color:#10478A; color:#fff'>Head of Account : "+element.hoa_no+"</td></tr>";
						}
						if((element.ccno == "")||(element.ccno == "0")){
							var Mon1AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='"+Month1+"' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon1Amt+"</u></span>";
							var Mon2AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='"+Month2+"' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon2Amt+"</u></span>";
							var Mon3AmtStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='"+Month3+"' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+Mon3Amt+"</u></span>";
						}else{
							var Mon1AmtStr = Mon1Amt;
							var Mon2AmtStr = Mon2Amt;
							var Mon3AmtStr = Mon3Amt;
						}
						TableStr += "<tr><td>"+Sno+"</td><td>"+element.hoa_no+"</td><td>"+element.work_name+"</td><td>"+element.name_contractor+"</td><td>"+element.ccno_wono+"</td><td>"+element.wo_amount+"</td><td>"+element.total_prev_fy+"</td><td align='right'>"+Mon1AmtStr+"</td><td align='right'>"+Mon2AmtStr+"</td><td align='right'>"+Mon3AmtStr+"</td><td align='right'>"+QtrTotal+"</td><td>"+element.total_curr_fy+"</td><td>"+element.total_exp_upto_dt+"</td></tr>";
						Sno++; PrevHoa = element.hoa_no; 
						HoaMon1Total = Number(HoaMon1Total) + Number(Mon1Amt);
						HoaMon2Total = Number(HoaMon2Total) + Number(Mon2Amt);
						HoaMon3Total = Number(HoaMon3Total) + Number(Mon3Amt);
						HoaQtrTotal  = Number(HoaQtrTotal)  + Number(QtrTotal); 
						
						HoaTotal1 = Number(HoaTotal1) + Number(Mon1Amt); 
						HoaTotal2 = Number(HoaTotal2) + Number(Mon2Amt); 
						HoaTotal3 = Number(HoaTotal3) + Number(Mon3Amt); 
						QTotal    = Number(QTotal)  + Number(QtrTotal);
					});
					TableStr += "<tr><td colspan='5' align='right'>HOA WISE TOTAL</td><td></td><td></td><td align='right'>"+HoaMon1Total.toFixed(2)+"</td><td align='right'>"+HoaMon2Total.toFixed(2)+"</td><td align='right'>"+HoaMon3Total.toFixed(2)+"</td align='right'><td>"+HoaQtrTotal.toFixed(2)+"</td><td></td><td></td></tr>";
					TableStr += "<tr><td colspan='5' align='right'>TOTAL</td><td></td><td></td><td align='right'>"+HoaTotal1.toFixed(2)+"</td><td align='right'>"+HoaTotal2.toFixed(2)+"</td><td align='right'>"+HoaTotal3.toFixed(2)+"</td><td align='right'>"+QTotal.toFixed(2)+"</td><td></td><td></td></tr>";
					//$('.example tr:last').after(TableStr);
					$('.example').append(TableStr);
					$('.example').DataTable();
				}
			}
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
		var BudQuarter 	 	= $("#cmb_quarter").val();
		var ScheduleOFAct   = $("#cmb_scheulof_act").val();
		var MajorItems 	   = $("#cmb_major_item").val();
		var PlantService 	 = $("#cmb_plant_ser").val();
		var code 			= "EXPMO";
		var BudYear 		= "";
		var BudThreeQtr 	= "";
		var BudHalfYear 	= "";
		var BudQuarter 		= "";
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/DataTableDataReports.php', 
			data: { BudUnit: BudUnit,ScheduleOFAct: ScheduleOFAct,MajorItems: MajorItems,PlantService: PlantService, BudFinYear: BudFinYear, BudDiscipline: BudDiscipline, BudHoa: BudHoa, BudMode: BudMode, BudMonth: BudMonth, RupeesIn: RupeesIn, code: code, BudYear: BudYear, BudThreeQtr: BudHalfYear, BudQuarter: BudQuarter }, 
			dataType: 'json',
			success: function (data) {   //alert(data['computer_code_no']);
				if(data != null){ console.log(data);
					var Valid = data['valid'];
					var Result = data['data']; //alert(JSON.stringify(Result));
					var TableStr = ""; var Sno = 1; var PrevHoa = ""; var HoaMonTotal = 0; var HoaTotal = 0;
					$.each(Result, function(index, element) { 
						if(element.hoa_no != PrevHoa){
							if(PrevHoa != ""){
								TableStr += "<tr><td colspan='5' align='right'>TOTAL</td><td></td><td></td><td align='right'>"+HoaMonTotal.toFixed(2)+"</td><td></td><td></td></tr>";
								HoaTotal = Number(HoaTotal) + Number(HoaMonTotal); HoaMonTotal = 0; 
							}
							TableStr += "<tr><td colspan='10' style='background-color:#10478A; color:#fff'>Head of Account : "+element.hoa_no+"</td></tr>";
						}
						if((element.ccno == "")||(element.ccno == "0")){
							var AmountStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='"+BudMonth+"' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+element.month+"</u></span>";
						}else{
							var AmountStr = element.month;
						}
						TableStr += "<tr><td>"+Sno+"</td><td>"+element.hoa_no+"</td><td>"+element.work_name+"</td><td>"+element.name_contractor+"</td><td>"+element.ccno_wono+"</td><td>"+element.wo_amount+"</td><td>"+element.total_prev_fy+"</td><td align='right'>"+AmountStr+"</td><td>"+element.total_curr_fy+"</td><td>"+element.total_exp_upto_dt+"</td></tr>";
						Sno++; PrevHoa = element.hoa_no; HoaMonTotal = Number(HoaMonTotal) + Number(element.month);
					});
					HoaTotal = Number(HoaTotal) + Number(HoaMonTotal);
					TableStr += "<tr><td colspan='5' align='right'>HOA WISE TOTAL</td><td></td><td></td><td align='right'>"+HoaMonTotal.toFixed(2)+"</td><td></td><td></td></tr>";
					TableStr += "<tr><td colspan='5' align='right'>TOTAL</td><td></td><td></td><td align='right'>"+HoaTotal.toFixed(2)+"</td><td></td><td></td></tr>";
					//$('.example tr:last').after(TableStr);
					$('.example').append(TableStr);
					$('.example').DataTable();
				}
			}
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
		var BudQuarter 	 	= $("#cmb_quarter").val();
		var ScheduleOFAct   = $("#cmb_scheulof_act").val();
		var MajorItems 	   = $("#cmb_major_item").val();
		var PlantService 	 = $("#cmb_plant_ser").val();	
		var code 			= "EXPPE";
		var BudYear 		= "";
		var BudThreeQtr 	= "";
		var BudHalfYear 	= "";
		var BudQuarter 		= "";
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/DataTableDataReports.php', 
			data: { BudUnit: BudUnit,ScheduleOFAct: ScheduleOFAct,MajorItems: MajorItems,PlantService: PlantService, BudFinYear: BudFinYear, BudDiscipline: BudDiscipline, BudHoa: BudHoa, BudMode: BudMode, BudMonth: BudMonth, RupeesIn: RupeesIn, code: code, BudYear: BudYear, BudThreeQtr: BudHalfYear, BudQuarter: BudQuarter, FromDate: FromDate, ToDate: ToDate }, 
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
						if((element.ccno == "")||(element.ccno == "0")){
							var AmountStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='' data-unit='"+BudUnit+"' data-disc='"+BudDiscipline+"' data-hoa='"+element.hoa_no+"' data-view='HOA'><u>"+element.month+"</u></span>";
						}else{
							var AmountStr = element.month;
						}
						TableStr += "<tr><td>"+Sno+"</td><td>"+element.hoa_no+"</td><td>"+element.work_name+"</td><td>"+element.name_contractor+"</td><td>"+element.ccno_wono+"</td><td>"+element.wo_amount+"</td><td>"+element.total_prev_fy+"</td><td align='right'>"+AmountStr+"</td><td>"+element.total_curr_fy+"</td><td>"+element.total_exp_upto_dt+"</td></tr>";
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
	
	function ObjectHeadConsolidExpenditure(){ 
		$("#StmtDuration").html('');
		var RupeesIn 	 	= $("#cmb_rupees").val();
		var code 			= "OHCE";
		var FromDate 		= $("#txt_from_date").val();
		var ToDate 			= $("#txt_to_date").val();
		if($("#cmb_rupees").val() == "C"){
			var RupeesStr = "(&#x20b9; in Crores)";
		}else{
			var RupeesStr = "(&#x20b9; in Lakhs)";
		}
		var TitleStr = " for the period of "+FromDate+" - "+ToDate;
		$("#StmtDuration").html(TitleStr);
		$("#example").find("tr:gt(0)").remove();
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/DataTableDataReports.php', 
			contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			data: { code: code, FromDate: FromDate, ToDate: ToDate, RupeesIn: RupeesIn }, 
			dataType: 'json',
			success: function (data) {  
				if(data != null){ 
					var Valid = data['valid'];
					var Result = data['data'];
					var TableStr = ""; var TotalAmount = 0; var Amount = 0; console.log(Result);
					$.each(Result, function(index, element) {
						if(element.amount !== null){
							Amount = Number(element.amount);
						}else{
							Amount = 0;	
						}
						TotalAmount = Number(TotalAmount) + Number(Amount);
						TableStr 	+= "<tr><td>"+element.object_head+"</td><td align='right'>"+Amount.toFixed(2)+"</td></tr>";
					});
					TableStr += "<tr><td>TOTAL</td><td align='right'>"+TotalAmount.toFixed(2)+"</td></tr>";
					$('#example').append(TableStr);
					//$('#example').DataTable();
				}
			}
		});
		
	}
	
	function ObjectHeadCommitActual(){ 
		$("#StmtDuration").html('');
		var RupeesIn 	 	= $("#cmb_rupees").val();
		var code 			= "OHCA";
		var BudFinYear 		= $("#cmb_fy").val();
		var BudMonth 		= $("#cmb_month").val();
		var MonthText 		= $("#cmb_month option:selected").text();
		if($("#cmb_rupees").val() == "C"){
			var RupeesStr = "(&#x20b9; in Crores)";
		}else{
			var RupeesStr = "(&#x20b9; in Lakhs)";
		}
		var TitleStr = " for the financial year "+BudFinYear+" & upto month "+MonthText;
		$("#StmtDuration").html(TitleStr);
		$(".AmtTitle").html(RupeesStr);
		$("#example").find("tr:gt(0)").remove();
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/DataTableDataReports.php', 
			contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			data: { code: code, BudFinYear: BudFinYear, BudMonth: BudMonth, RupeesIn: RupeesIn }, 
			dataType: 'json',
			success: function (data) {  
				if(data != null){ 
					var Valid = data['valid'];
					var Result = data['data'];
					var TableStr = ""; 
					var TotSancAmt = 0; var TotCommitAmtt = 0; var TotUpDtAmt = 0; var TotBalCommAmt = 0; var TotBalToBeCommAmt = 0; 
					var SancAmt = 0; var CommitAmt = 0; var UpDtAmt = 0; var BalCommAmt = 0; var BalToBeCommAmt = 0; 
					$.each(Result, function(index, element) {
						if(element.sanc_amount !== null){ SancAmt = Number(element.sanc_amount); }else{ SancAmt = 0; }
						if(element.commited !== null){ CommitAmt = Number(element.commited); }else{ CommitAmt = 0; }
						if(element.exp_upto_amt !== null){ UpDtAmt = Number(element.exp_upto_amt); }else{ UpDtAmt = 0; }
						if(element.bal_commit !== null){ BalCommAmt = Number(element.bal_commit); }else{ BalCommAmt = 0; }
						if(element.bal_to_be_commit !== null){ BalToBeCommAmt = Number(element.bal_to_be_commit); }else{ BalToBeCommAmt = 0; }
						
						TotSancAmt 			= Number(TotSancAmt) + Number(SancAmt);
						TotCommitAmtt 		= Number(TotCommitAmtt) + Number(CommitAmt);
						TotUpDtAmt 			= Number(TotUpDtAmt) + Number(UpDtAmt);
						TotBalCommAmt 		= Number(TotBalCommAmt) + Number(BalCommAmt);
						TotBalToBeCommAmt 	= Number(TotBalToBeCommAmt) + Number(BalToBeCommAmt);
						TableStr 	+= "<tr><td>"+element.object_head+"</td><td align='right'>"+SancAmt.toFixed(2)+"</td><td align='right'>"+CommitAmt.toFixed(2)+"</td><td align='right'>"+UpDtAmt.toFixed(2)+"</td><td align='right'>"+BalCommAmt.toFixed(2)+"</td><td align='right'>"+BalToBeCommAmt.toFixed(2)+"</td></tr>";
					});
					TableStr += "<tr><td>TOTAL</td><td align='right'>"+TotSancAmt.toFixed(2)+"</td><td align='right'>"+TotCommitAmtt.toFixed(2)+"</td><td align='right'>"+TotUpDtAmt.toFixed(2)+"</td><td align='right'>"+TotBalCommAmt.toFixed(2)+"</td><td align='right'>"+TotBalToBeCommAmt.toFixed(2)+"</td></tr>";
					$('#example').append(TableStr);
					//$('#example').DataTable();
				}
			}
		});
	}
	
	function ObjectHeadBeRe(){ 
		$("#StmtDuration").html('');
		var RupeesIn 	 	= $("#cmb_rupees").val();
		var code 			= "OHBERE";
		var BudFinYear 		= $("#cmb_fy").val();
		var BudMonth 		= $("#cmb_month").val();
		var MonthText 		= $("#cmb_month option:selected").text();
		if($("#cmb_rupees").val() == "C"){
			var RupeesStr = "(&#x20b9; in Crores)";
		}else{
			var RupeesStr = "(&#x20b9; in Lakhs)";
		}
		var TitleStr = " for the financial year "+BudFinYear+" & upto month "+MonthText;
		$("#StmtDuration").html(TitleStr);
		$(".FyTitle").html(BudFinYear);
		$(".MonTitle").html(MonthText);
		$(".AmtTitle").html(RupeesStr);
		$("#example").find("tr:gt(0)").remove();
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/DataTableDataReports.php', 
			contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			data: { code: code, BudFinYear: BudFinYear, BudMonth: BudMonth, RupeesIn: RupeesIn }, 
			dataType: 'json',
			success: function (data) {  
				if(data != null){ 
					var Valid = data['valid'];
					var Result = data['data'];
					var TableStr = ""; 
					var TotBePropAmt = 0; var TotBeApprAmt = 0; var TotRePropAmt = 0; var TotReApprAmt = 0; var TotActExpAmt = 0; 
					var BePropAmt = 0; var BeApprAmt = 0; var RePropAmt = 0; var ReApprAmt = 0; var ActExpAmt = 0; 
					$.each(Result, function(index, element) {
						if(element.be_proposed !== null){ BePropAmt = Number(element.be_proposed); }else{ BePropAmt = 0; }
						if(element.be_approved !== null){ BeApprAmt = Number(element.be_approved); }else{ BeApprAmt = 0; }
						if(element.re_proposed !== null){ RePropAmt = Number(element.re_proposed); }else{ RePropAmt = 0; }
						if(element.re_approved !== null){ ReApprAmt = Number(element.re_approved); }else{ ReApprAmt = 0; }
						if(element.act_emp_amt !== null){ ActExpAmt = Number(element.act_emp_amt); }else{ ActExpAmt = 0; }
						
						TotBePropAmt 	= Number(TotBePropAmt) + Number(BePropAmt);
						TotBeApprAmt 	= Number(TotBeApprAmt) + Number(BeApprAmt);
						TotRePropAmt 	= Number(TotRePropAmt) + Number(RePropAmt);
						TotReApprAmt 	= Number(TotReApprAmt) + Number(ReApprAmt);
						TotActExpAmt 	= Number(TotActExpAmt) + Number(ActExpAmt);
						TableStr 	+= "<tr><td>"+element.object_head+"</td><td align='right'>"+BePropAmt.toFixed(2)+"</td><td align='right'>"+BeApprAmt.toFixed(2)+"</td><td align='right'>"+RePropAmt.toFixed(2)+"</td><td align='right'>"+ReApprAmt.toFixed(2)+"</td><td align='right'>"+ActExpAmt.toFixed(2)+"</td></tr>";
					});
					TableStr += "<tr><td align='right'>TOTAL</td><td align='right'>"+TotBePropAmt.toFixed(2)+"</td><td align='right'>"+TotBeApprAmt.toFixed(2)+"</td><td align='right'>"+TotRePropAmt.toFixed(2)+"</td><td align='right'>"+TotReApprAmt.toFixed(2)+"</td><td align='right'>"+TotActExpAmt.toFixed(2)+"</td></tr>";
					$('#example').append(TableStr);
					//$('#example').DataTable();
				}
			}
		});
		
	}
	
	function ObjectHeadBudgetEstimate(){ 
		$("#StmtDuration").html('');
		var RupeesIn 	 	= $("#cmb_rupees").val();
		var code 			= "OHBERE";
		var BudFinYear 		= $("#cmb_fy").val();
		var BudMonth 		= $("#cmb_month").val();
		var MonthText 		= $("#cmb_month option:selected").text();
		if($("#cmb_rupees").val() == "C"){
			var RupeesStr = "(&#x20b9; in Crores)";
		}else{
			var RupeesStr = "(&#x20b9; in Lakhs)";
		}
		var TitleStr = " for the financial year "+BudFinYear+" & upto month "+MonthText;
		$("#StmtDuration").html(TitleStr);
		$(".AmtTitle").html(RupeesStr);
		//$(".MonTitle").html(MonthText);
		$("#example").find("tr:gt(0)").remove();
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/DataTableDataReports.php', 
			contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			data: { code: code, BudFinYear: BudFinYear, BudMonth: BudMonth, RupeesIn: RupeesIn }, 
			dataType: 'json',
			success: function (data) {  
				if(data != null){ 
					var Valid = data['valid'];
					var Result = data['data'];
					var TableStr = ""; 
					var TotBePropAmt = 0; 
					var BePropAmt = 0; 
					$.each(Result, function(index, element) {
						if(element.be_proposed !== null){ BePropAmt = Number(element.be_proposed); }else{ BePropAmt = 0; }
						TotBePropAmt 	= Number(TotBePropAmt) + Number(BePropAmt);
						TableStr 	+= "<tr><td>"+element.object_head+"</td><td align='right'>"+BePropAmt.toFixed(2)+"</td></tr>";
					});
					TableStr += "<tr><td align='right'>TOTAL</td><td align='right'>"+TotBePropAmt.toFixed(2)+"</td></tr>";
					$('#example').append(TableStr);
					//$('#example').DataTable();
				}
			}
		});
	}
	
	function BudgetExpenditurePlanCurrFY(){ 
		$("#StmtDuration").html('');
		var code = "BECFY";
		//$(".MonTitle").html(MonthText);
		$("#example").find("tr:gt(0)").remove();
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/DataTableDataReports.php', 
			contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			data: { code: code }, 
			dataType: 'json',
			success: function (data) {  
				if(data != null){ 
					var Valid = data['valid'];
					var Result = data['data'];
					var TableStr = ""; 
					var TotBePropAmt = 0; 
					var BePropAmt = 0; 
					var TotalMonAmt = 0;
					$.each(Result, function(index, element) {
						if(element.be_proposed !== null){ BePropAmt = Number(element.be_proposed); }else{ BePropAmt = 0; }
						if(element.be_approved !== null){ BeApprAmt = Number(element.be_approved); }else{ BeApprAmt = 0; }
						if(element.re_proposed !== null){ RePropAmt = Number(element.re_proposed); }else{ RePropAmt = 0; }
						if(element.re_approved !== null){ ReApprAmt = Number(element.re_approved); }else{ ReApprAmt = 0; }
						TotBePropAmt 	= Number(TotBePropAmt) + Number(BePropAmt);
						TotalMonAmt = Number(element.M1) + Number(element.M2) + Number(element.M3) + Number(element.M4) + Number(element.M5) + Number(element.M6) + Number(element.M7) + Number(element.M8) + Number(element.M9) + Number(element.M10) + Number(element.M11) + Number(element.M12);
						TotalMonAmt = TotalMonAmt.toFixed(2);
						TableStr 	+= "<tr><td>712</td><td>FRFCF</td><td align='right'>"+BePropAmt.toFixed(2)+"</td><td align='right'>"+BeApprAmt.toFixed(2)+"</td><td align='right'>"+RePropAmt.toFixed(2)+"</td><td align='right'>"+ReApprAmt.toFixed(2)+"</td><td align='right'>"+element.M1+"</td><td align='right'>"+element.M2+"</td><td align='right'>"+element.M3+"</td><td align='right'>"+element.M4+"</td><td align='right'>"+element.M5+"</td><td align='right'>"+element.M6+"</td><td align='right'>"+element.M7+"</td><td align='right'>"+element.M8+"</td><td align='right'>"+element.M9+"</td><td align='right'>"+element.M10+"</td><td align='right'>"+element.M11+"</td><td align='right'>"+element.M12+"</td><td align='right'>"+TotalMonAmt+"</td></tr>";
					});
					$('#example').append(TableStr);
					//$('#example').DataTable();
				}
			}
		});
	}
	
	function BudgetExpenditurePlanUpcomeFY(){ 
		$("#StmtDuration").html('');
		var code = "BEUCFY";
		//$(".MonTitle").html(MonthText);
		$("#example").find("tr:gt(0)").remove();
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/DataTableDataReports.php', 
			contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			data: { code: code }, 
			dataType: 'json',
			success: function (data) {  
				if(data != null){ 
					var Valid = data['valid'];
					var Result = data['data'];
					var TableStr = ""; 
					var TotBePropAmt = 0; 
					var BePropAmt = 0; 
					$.each(Result, function(index, element) {
						if(element.be_proposed !== null){ BePropAmt = Number(element.be_proposed); }else{ BePropAmt = 0; }
						if(element.be_approved !== null){ BeApprAmt = Number(element.be_approved); }else{ BeApprAmt = 0; }
						if(element.re_proposed !== null){ RePropAmt = Number(element.re_proposed); }else{ RePropAmt = 0; }
						if(element.re_approved !== null){ ReApprAmt = Number(element.re_approved); }else{ ReApprAmt = 0; }
						TotBePropAmt 	= Number(TotBePropAmt) + Number(BePropAmt);
						TableStr 	+= "<tr><td>712</td><td>FRFCF</td><td align='right'>"+BePropAmt.toFixed(2)+"</td><td align='right'>"+BeApprAmt.toFixed(2)+"</td><td align='right'>"+RePropAmt.toFixed(2)+"</td><td align='right'>"+ReApprAmt.toFixed(2)+"</td><td align='right'>"+element.M1+"</td><td align='right'>"+element.M2+"</td><td align='right'>"+element.M3+"</td><td align='right'>"+element.M4+"</td><td align='right'>"+element.M4+"</td><td align='right'>"+element.M5+"</td><td align='right'>"+element.M6+"</td><td align='right'>"+element.M7+"</td><td align='right'>"+element.M8+"</td><td align='right'>"+element.M9+"</td><td align='right'>"+element.M10+"</td><td align='right'>"+element.M11+"</td><td align='right'>"+element.M12+"</td></tr>";
					});
					$('#example').append(TableStr);
					//$('#example').DataTable();
				}
			}
		});
	}
	
	function DisciplineConsolidExpenditure(){ 
		$("#StmtDuration").html('');
		var RupeesIn 	 	= $("#cmb_rupees").val();
		var code 			= "DICE";
		var FromDate 		= $("#txt_from_date").val();
		var ToDate 			= $("#txt_to_date").val();
		if($("#cmb_rupees").val() == "C"){
			var RupeesStr = "(&#x20b9; in Crores)";
		}else{
			var RupeesStr = "(&#x20b9; in Lakhs)";
		}
		var TitleStr = " for the period of "+FromDate+" - "+ToDate;
		$("#StmtDuration").html(TitleStr);
		$("#example").find("tr:gt(0)").remove();
		$.ajax({ 
			type: 'POST', 
			url: 'ajax/DataTableDataReports.php', 
			contentType: "application/x-www-form-urlencoded;charset=UTF-8",
			data: { code: code, FromDate: FromDate, ToDate: ToDate, RupeesIn: RupeesIn }, 
			dataType: 'json',
			success: function (data) {  
				if(data != null){ 
					var Valid = data['valid'];
					var Result = data['data'];
					var TableStr = ""; var TotalAmount = 0; var Amount = 0; console.log(Result);
					$.each(Result, function(index, element) {
						if(element.amount !== null){
							Amount = Number(element.amount);
						}else{
							Amount = 0;	
						}
						TotalAmount = Number(TotalAmount) + Number(Amount);
						TableStr 	+= "<tr><td>"+element.object_head+"</td><td align='right'>"+Amount.toFixed(2)+"</td></tr>";
					});
					TableStr += "<tr><td>TOTAL</td><td align='right'>"+TotalAmount.toFixed(2)+"</td></tr>";
					$('#example').append(TableStr);
					//$('#example').DataTable();
				}
			}
		});
		
	}
	
	function MonthlyExpenditureDisicp(TitleStr){ 
		var BudUnit 		= $("#cmb_unit").val();
		var BudFinYear 	 	= $("#cmb_fy").val();
		var BudDiscipline 	= $("#cmb_discipline").val();
		var BudHoa 	 		= "";//$("#cmb_hoa").val();
		var BudMode 	 	= "MO";//$("#cmb_mode").val();
		var BudMonth 	 	= $("#cmb_month").val();
		var RupeesIn 	 	= $("#cmb_rupees").val();
		var code 			= "DISMO";
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
						if(element.grp_div_sec != PrevHoa){
							if(PrevHoa != ""){
								TableStr += "<tr><td colspan='5' align='right'>TOTAL</td><td></td><td></td><td align='right'>"+HoaMonTotal.toFixed(2)+"</td><td></td><td></td></tr>";
								HoaTotal = Number(HoaTotal) + Number(HoaMonTotal); HoaMonTotal = 0; 
							}
							TableStr += "<tr><td colspan='10' style='background-color:#10478A; color:#fff'>Discipline : "+element.grp_div_sec+"</td></tr>";
						}
						if((element.ccno == "")||(element.ccno == "0")){
							var AmountStr = "<span class='modal-hl ExpModalData' data-id='"+element.globid+"' data-fy='"+BudFinYear+"' data-month='"+BudMonth+"' data-unit='"+BudUnit+"' data-disc='"+element.grp_div_sec+"' data-hoa='"+BudHoa+"' data-view='DIS'><u>"+element.month+"</u></span>";
						}else{
							var AmountStr = element.month;
						}
						TableStr += "<tr><td>"+Sno+"</td><td>"+element.hoa_no+"</td><td>"+element.work_name+"</td><td>"+element.name_contractor+"</td><td>"+element.ccno_wono+"</td><td>"+element.wo_amount+"</td><td>"+element.total_prev_fy+"</td><td align='right'>"+AmountStr+"</td><td>"+element.total_curr_fy+"</td><td>"+element.total_exp_upto_dt+"</td></tr>";
						Sno++; PrevHoa = element.grp_div_sec; HoaMonTotal = Number(HoaMonTotal) + Number(element.month);
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
	
//});
