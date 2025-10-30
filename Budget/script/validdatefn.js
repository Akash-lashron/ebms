var dtCh= "/";
var minYear=1900;
var maxYear=2100;

function isInteger(s){
	var i;
    for (i = 0; i < s.length; i++){   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function stripCharsInBag(s, bag){
	var i;
    var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++){   
        var c = s.charAt(i);
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}

function daysInFebruary (year){
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}
function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
		if (i==2) {this[i] = 29}
   } 
   return this
}

function isDate(dtStr){
	var daysInMonth = DaysArray(12)
	var pos1=dtStr.indexOf(dtCh)
	var pos2=dtStr.indexOf(dtCh,pos1+1)
	var strDay=dtStr.substring(0,pos1)
	var strMonth=dtStr.substring(pos1+1,pos2)
	var strYear=dtStr.substring(pos2+1)
	strYr=strYear
	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
	if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
	for (var i = 1; i <= 3; i++) {
		if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
	}
	month=parseInt(strMonth)
	day=parseInt(strDay)
	year=parseInt(strYr)
	if (pos1==-1 || pos2==-1){
		//alert("The date format should be : dd/mm/yyyy")
		return false
	}
	if (strMonth.length<1 || month<1 || month>12){
		//alert("Please enter a valid month")
		return false
	}
	if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
		//alert("Please enter a valid day")
		return false
	}
	if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
		//alert("Please enter a valid 4 digit year between "+minYear+" and "+maxYear)
		return false
	}
	if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
		//alert("Please enter a valid date")
		return false
	}
return true
}

function getYearMonthDays(date_1, date_2)
{
  
//convert to UTC
var date2_UTC = new Date(Date.UTC(date_2.getUTCFullYear(), date_2.getUTCMonth(), date_2.getUTCDate()));
var date1_UTC = new Date(Date.UTC(date_1.getUTCFullYear(), date_1.getUTCMonth(), date_1.getUTCDate()));


var yAppendix, mAppendix, dAppendix;


//--------------------------------------------------------------
var days = date2_UTC.getDate() - date1_UTC.getDate();
if (days < 0)
{

    date2_UTC.setMonth(date2_UTC.getMonth() - 1);
    days += DaysInMonth(date2_UTC);
}
//--------------------------------------------------------------
var months = date2_UTC.getMonth() - date1_UTC.getMonth();
if (months < 0)
{
    date2_UTC.setFullYear(date2_UTC.getFullYear() - 1);
    months += 12;
}
//--------------------------------------------------------------
var years = date2_UTC.getFullYear() - date1_UTC.getFullYear();




if (years > 1) yAppendix = " years";
else yAppendix = " year";
if (months > 1) mAppendix = " months";
else mAppendix = " month";
if (days > 1) dAppendix = " days";
else dAppendix = " day";


//return years + yAppendix + ", " + months + mAppendix + ", and " + days + dAppendix + " old.";
return years+","+months+","+days;
}


function DaysInMonth(date2_UTC)
{
var monthStart = new Date(date2_UTC.getFullYear(), date2_UTC.getMonth(), 1);
var monthEnd = new Date(date2_UTC.getFullYear(), date2_UTC.getMonth() + 1, 1);
var monthLength = (monthEnd - monthStart) / (1000 * 60 * 60 * 24);
return monthLength;
}