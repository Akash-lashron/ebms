function addOption(selectObject,optionText,optionValue) 
{
    var optionObject = new Option(optionText,optionValue);
    var optionRank = selectObject.options.length;    
    selectObject.options[optionRank]=optionObject;
}


function deleteOption(selectObject,optionindex) 
{
    if (selectObject.options.length!=0) 
    {
     selectObject.options[optionindex]=null ;
    }
}


function getSelectedValues(lstobj)
{
	var selval=[];
	var cnt=0;
	for (i=0;i<lstobj.length;i++)
	{
		if(lstobj.options[i].selected){
			selval[cnt++]=lstobj.options[i].value;}
	}
	return selval


}

function getSelectedIndex(lstobj)
{
	var selval=[];
	var cnt=0;
	for (i=0;i<lstobj.length;i++)
	{
		if(lstobj.options[i].selected){
			selval[cnt++]=i;}
	}
	return selval


}

//to work with below functions, we should have our list box names as list1 and list2.


function moveright()  ///multiple value
{
	var a=document.f.elements("list1");
	var selval=getSelectedValues(a);
	var selind=getSelectedIndex(a);
	//alert(selind.length);
	
	for (i=0;i<selval.length;i++)
	{
		addOption(document.f.list2,selval[i],selval[i]);
	
	}
	while (selind.length!=0)
	{
		deleteOption(document.f.list1,selind[0]);
		selind=getSelectedIndex(a);
		//alert(selind.length);
	}
	
}

function moveleft()
{
	var a=document.f.elements("list2");
	var selval=getSelectedValues(a);
	var selind=getSelectedIndex(a);
	for (i=0;i<selval.length;i++)
	{
		addOption(document.f.list1,selval[i],selval[i]);
	
	}
	while (selind.length!=0)
	{
		deleteOption(document.f.list2,selind[0]);
		selind=getSelectedIndex(a);
		//alert(selind.length);
	}
	
}

function moveleftall()
{
	var a=document.f.elements("list2");
	
	for (i=0;i<a.length;i++)
	{
		addOption(document.f.list1,a.options[i].value,a.options[i].value);
	}
	while (a.length!=0)
	{
		deleteOption(document.f.list2,0);
	}
	
}

function moverightall()
{
	var a=document.f.elements("list1");
	
	for (i=0;i<a.length;i++)
	{
		addOption(document.f.list2,a.options[i].value,a.options[i].value);
	}
	while (a.length!=0)
	{
		deleteOption(document.f.list1,0);
	}
	
}


