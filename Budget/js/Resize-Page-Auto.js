function autoResizeDiv()
{
   //document.getElementById('bq1').style.height = screen.height-300 +'px';
   //document.getElementById('bq1').style.height = window.innerHeight-150 +'px';
   var 	x = document.getElementsByClassName("bq1");
    	x[0].style.height = window.innerHeight-148 +'px';
		//x[0].style.minHeight = window.innerHeight-150 +'px';
}
window.onresize = autoResizeDiv;
autoResizeDiv();