
function fgoto(ntarget,nfile)
{
	eval(ntarget+".document.location.href = nfile");
}

function fresizeframe(nmin,nmax)
{
	if(parent.document.body.rows==nmin)
	{
		parent.document.body.rows=nmax;
		
	}
	else
	{
		parent.document.body.rows=nmin;
		
		
	}
}

function resizediv(fsize,maxheight,minheight)
{
	para2=fsize+"%,*"
	fresizeframe('0,*',para2)
	if (document.getElementById("maxmin").innerText=="Max"	)
	{
		document.getElementById("maxmin").innerText="Min"
		document.getElementById("iwindow").style.height=maxheight
		
	}
	else
	{
		document.getElementById("maxmin").innerText="Max"
		document.getElementById("iwindow").style.height=minheight
		
	}
}

function maxmin(maxheight,minheight)
{
	if(parent.document.body.rows=="0,*")
	{
		document.getElementById("iwindow").style.height=maxheight;
		document.getElementById("maxmin").innerText="Min"
	}
	else
	{
		document.getElementById("iwindow").style.height=minheight
		document.getElementById("maxmin").innerText="Max"
	}
}