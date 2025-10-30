document.onmousedown=disableclick;
//status="Right Click Disabled";
function disableclick(event)
{
   if(event.button==2)
   {
     //swal("You are not allowed to right click...!", "", "error");
     //return false;    
   }
}

function startTime() {
	var months = new Array(12);
		months[0] = "Jan";
		months[1] = "Feb";
		months[2] = "Mar";
		months[3] = "Apr";
		months[4] = "May";
		months[5] = "Jun";
		months[6] = "Jul";
		months[7] = "Aug";
		months[8] = "Sep";
		months[9] = "Oct";
		months[10] = "Nov"; 
		months[11] = "Dec";
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
	month_value 	= 	today.getMonth();
	day_value	 	= 	today.getDate();
	year_value 		= 	today.getFullYear();
    m = checkTime(m);
    s = checkTime(s);
    document.getElementById('showclock').innerHTML =
    "<font style='font-size:15px;'>"+months[month_value]+" "+day_value+", "+year_value+"</font><br/> &nbsp;&nbsp;&nbsp;"+h + ":" + m + ":" + s+" &nbsp;&nbsp;";
    var t = setTimeout(startTime, 500);
}
function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers color:#c70592 < 10
    return i;
}
function isDecimalValue(evt, c) {
	var charCode = (evt.which) ? evt.which : event.keyCode;
    var dot1 = c.value.indexOf('.');
    var dot2 = c.value.lastIndexOf('.'); 
    if(charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
    	return false;
    else if (charCode == 46 && (dot1 == dot2) && dot1 != -1 && dot2 != -1)
        return false;
    return true;
}
function isNumberWithTwoDecimal(evt, c) { 
	var charCode = (evt.which) ? evt.which : event.keyCode;
    var dot1 	 = c.value.indexOf('.');
    var dot2 	 = c.value.lastIndexOf('.'); 
	var val 	 = c.value;
	var SplitVal = val.split(".");
	var len 	 = SplitVal.length;
	var Fraction = SplitVal[1];
	if(Fraction){
		var fractLen = Fraction.length;
	}else{
		var fractLen = 0;
	}
    if(charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)){
    	return false;
	}else if (charCode == 46 && (dot1 == dot2) && dot1 != -1 && dot2 != -1){
        return false;
	}else if(isNaN(SplitVal[0])){
		c.value = 'x';
		return false;
	}else if(isNaN(SplitVal[1]) && Number(fractLen) > 0){
		c.value = 'x';
		return false;
    }else if (fractLen > 1){
		return false;
	}else{
		return true;
	}
}
function isIntegerValue(evt, c) { 
	var ctrlDown = false;
	var charCode = (evt.which) ? evt.which : event.keyCode;
    var dot1 = c.value.indexOf('.');
    var dot2 = c.value.lastIndexOf('.');
	var val = c.value;
	var SplitVal = val.split(".");
	var len = SplitVal.length;
    if(charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
    	return false;
    else if (charCode == 46 && (dot1 == dot2) )
        return false;
	else if (len > 1)
		return false;
	else if (ctrlDown && (charCode == 86 || charCode == 67))
		return false;
    return true;
}
function isIntegerValueWithLimit(evt, c, limit) { 
	var charCode = (evt.which) ? evt.which : event.keyCode;
    var dot1 = c.value.indexOf('.');
    var dot2 = c.value.lastIndexOf('.');
	var val = c.value;
	var SplitVal = val.split(".");
	var len1 = SplitVal.length;
	var len2 = val.length; 
	if(charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
    	return false;
    else if (charCode == 46 && (dot1 == dot2) )
        return false;
	else if (len1 > 1)
		return false;
	else if (len2 > limit)
		return false;
    return true;
}
function isPercentageValue(evt, c) {  
	var charCode = (evt.which) ? evt.which : event.keyCode;
    var dot1 = c.value.indexOf('.');
    var dot2 = c.value.lastIndexOf('.');
    if(charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
    	return false;
    else if (charCode == 46 && (dot1 == dot2) && dot1 != -1 && dot2 != -1)
        return false;
    return true;
}
function toFixed2DecimalNoRound(x, n) {
  const v = (typeof x === 'string' ? x : x.toString()).split('.');
  if (n <= 0) return v[0];
  let f = v[1] || '';
  if (f.length > n) return `${v[0]}.${f.substr(0,n)}`;
  while (f.length < n) f += '0';
  return `${v[0]}.${f}`
}
