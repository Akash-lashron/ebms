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