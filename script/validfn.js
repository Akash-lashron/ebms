var myObject = new Object();

//it checks the entered text box is empty or not.
//parameters are Form name and text box name 
//returns 0 if it is empty else 1.
		
function CheckNull(Form,Field)
{ var Fieldval;
	
	Fieldval =document[Form][Field].value;
	ValTrim=CheckTrim(Form,Field)	
	
	if ((Fieldval == "")||(ValTrim==0))
	{ 
		
		//document[Form][Field].focus();
		return(0); 
	}
	else
	{
	return(1);
	}
}


//it checks length of the field.
//parameters are Form name and text box name and actual len required
//returns 0 if it is empty else 1.


function CheckLength(Form,Field,Length)
{
 var Fieldval;
 Fieldval = document[Form][Field].value;
 if( (Fieldval).length < Length )
 { 
   document[Form][Field].focus();
   return(0);
 }
 else {return(1);}   
}


//it trims the blank spaces in the field.
//if user enters continuous blank space,it will trim and check.
//parameters are Form name and text box name.
//returns 0 if it is empty else 1.


function CheckTrim(Form,Field)
{
	
	str=document[Form][Field].value;  	
	FildLen =str.length;
		j=0;
		for(i=0;i< FildLen;i++)
		{
			if(str.charAt(i)!=" ")
			{	
				break; 
			}
			else 
			{
				j=j+1;
			}  
		}
		if (j == FildLen)
		{
			//Alert("White spaces are Not Allowed");
			//document[Form][Field].value="";
			//document[Form][Field].focus(); 
			return(0);
		}
		else
		{
			return(1);
		}
}

//it checks the key entered by keyboard is valid or not.
//parameter is the type (alpha,alnum etc)
//removes the character if it is not valid

function ValidKey(KeyValType)
{
	Key = window.event.keyCode;
	switch (KeyValType)
	{
	case "NoRes": //cannot key in anything
		window.event.keyCode=0
		break;
		
	case "Agency": //valid a-z||A-Z||blankspace|| & || - || : || . || , || ( || )
		if ((Key>=65 && Key<=90) || (Key>=97 && Key<=122) ||(Key==32)||(Key==46)||(Key==38)||(Key==45)||(Key==44)||(Key==39)||(Key==40)||(Key==41)){}//Alphabet
		else{window.event.keyCode=0;}
		break;
		
	case "Treatment":  //valid a-z||A-Z||blankspace||  ( || )
		if ((Key>=65 && Key<=90) || (Key>=97 && Key<=122) ||(Key==32)||(Key==40)||(Key==41)){}//Alphabet
		else{window.event.keyCode=0;}
		break;
		
	case "Alpha":
		if ((Key>=65 && Key<=90) || (Key>=97 && Key<=122) ||(Key==32)||(Key==46)){}//Alphabet & blankspace and dot
		else{window.event.keyCode=0;}
		break;
		
	case "AN":
		if ((Key>=65 && Key<=90) || (Key>=97 && Key<=122)||(Key==32)){}//Alphabet and blank space
		else if (Key>=48 && Key<=57){}//Numeric
		else{window.event.keyCode=0;}
		break;

	case "Alnumhypen": ////Alphabet & number and hyphen
		if ((Key>=65 && Key<=90) || (Key>=97 && Key<=122)){}//Alphabet
		else if (Key>=48 && Key<=57){}//Numeric
		else if (Key==45){}  //hyphen
		else{window.event.keyCode=0;}
		break;
	
	case "Num":
		if ((Key>=48 && Key<=57)||(Key==46)){}//Numeric & Period
		else{window.event.keyCode=0;}
		break;
	case "Int":
		if ((Key>=48 && Key<=57)||Key==13){}//Numeric
		else{window.event.keyCode=0;}
		break;
	case "AD":
		if (Key!=39){}//Single Quatation
		//else if (Key>=48 && Key<=57){}//Numeric
		//else if((Key==32)|| (Key==35)|| (Key==40)||(Key==41)||(Key==44)||(Key==45)||(Key==58) ) {}
		else{window.event.keyCode=0;}
		break;
	
	case "T": //TelePhone Number
		if ((Key>46 && Key<=57)||(Key==32)||(Key==40)||(Key==41)||(Key==45)) {}//Numeric
		else{window.event.keyCode=0;}
		break;	
	
	case "M": //Email Checking
		if ((Key>=64 && Key<=90) || (Key>=97 && Key<=122)||(Key==45)||(Key==46)||(Key==95)){}//Alphabet
		else if (Key>=48 && Key<=57){}//Numeric
		else{window.event.keyCode=0;}
		break;

	default:
	}
}

//it compares 2 dates and checks first is greater than second.
//parameters are Form name,day,month,year of date1 and day,month,year of date2
//returns 0 if it is empty else 1.

function CompareFromToDate(Form,dd1,mm1,yy1,dd2,mm2,yy2)
{
	
	var dateobj=new Date();
	var flag
	date1=new Date(mm1+"/"+dd1+"/"+yy1)
	date2=new Date(mm2+"/"+dd2+"/"+yy2)
	//alert(date2);
	if(date1>=date2)
	{
		
		//document[Form][day2].value=""
		//document[Form][day2].focus();
		return(0); 
	}
	else
	{return(1);}
}

//*** For Claim
// departure date should be > than entry date
function date_lesser(dt1,dt2)
{
   dt1=dt1.substr(3,2)+"/"+dt1.substr(0,2)+"/"+dt1.substr(6,4);
   dt2=dt2.substr(3,2)+"/"+dt2.substr(0,2)+"/"+dt2.substr(6,4);

   x1=new Date(dt1);
   x2=new Date(dt2);
  
   d1=x1.getDate();
   m1=(x1.getMonth()+1);
   y1=(x1.getYear());

   d2=x2.getDate();
   m2=(x2.getMonth()+1);
   y2=(x2.getYear());

  flag=0;

  if ( y2==y1 )
  {
     if ( m1==m2 )
     {
	 	if ( d2<d1 )
	 	{ 
	   		flag=1;
	 	}
	 }
	 else if (m2<m1 && m2<=12) 
	 {
		 flag=1;
	 }
  }
  
  if(y2<y1)
  {
	  if(m1<=12 && m2>=1)
	  {
		  flag=1;
	  }
  }
  
  return flag;
}
//*** For Claim

///

function date_lesser_equal(dt1,dt2)
{
   dt1=dt1.substr(3,2)+"/"+dt1.substr(0,2)+"/"+dt1.substr(6,4);
   dt2=dt2.substr(3,2)+"/"+dt2.substr(0,2)+"/"+dt2.substr(6,4);

   x1=new Date(dt1);
   x2=new Date(dt2);
  
   d1=x1.getDate();
   m1=(x1.getMonth()+1);
   y1=(x1.getYear());

   d2=x2.getDate();
   m2=(x2.getMonth()+1);
   y2=(x2.getYear());

  flag=0;

  if ( y2==y1 )
  {
     if ( m1==m2 )
     {
	 	if ( d2<=d1 )
	 	{ 
	   		flag=1;
	 	}
	 }
	 else if (m2<m1 && m2<=12) 
	 {
		 flag=1;
	 }
  }
  
  if(y2<y1)
  {
	  if(m1<=12 && m2>=1)
	  {
		  flag=1;
	  }
  }
  
  return flag;
}

///

// departure date should be > than entry date
function date_lesser_dep(dt1,dt2)
{
   dt1=dt1.substr(3,2)+"/"+dt1.substr(0,2)+"/"+dt1.substr(6,4);
   dt2=dt2.substr(3,2)+"/"+dt2.substr(0,2)+"/"+dt2.substr(6,4);

   x1=new Date(dt1);
   x2=new Date(dt2);
  
   d1=x1.getDate();
   m1=(x1.getMonth()+1);
   y1=(x1.getYear());

   d2=x2.getDate();
   m2=(x2.getMonth()+1);
   y2=(x2.getYear());

  flag=0;

  if ( y2==y1 )
  {
     if ( m1==m2 )
     {
	 	if ( d2>d1 )
	 	{ 
	   		flag=1;
	 	}
	 }
	 else if (m2>m1 && m2<=12) 
	 {
		 flag=1;
	 }
  }
  
  if(y2>y1)
  {
	  if(m1<=12 && m2>=1)
	  {
		  flag=1;
	  }
  }
  
  return flag;
}

// arrivale date >= departure-date
function date_lesser_ari(dt1,dt2)
{
   dt1=dt1.substr(3,2)+"/"+dt1.substr(0,2)+"/"+dt1.substr(6,4);
   dt2=dt2.substr(3,2)+"/"+dt2.substr(0,2)+"/"+dt2.substr(6,4);

   x1=new Date(dt1);
   x2=new Date(dt2);
  
   d1=x1.getDate();
   m1=(x1.getMonth()+1);
   y1=(x1.getYear());

   d2=x2.getDate();
   m2=(x2.getMonth()+1);
   y2=(x2.getYear());

  flag=0;

  if ( y2==y1 )
  {
	if ( m2==m1 )
	{
		if ( d2>=d1 )
		{ 
		  flag=1;
		}
	}
	else if (m2>m1 && m2<=12)
	{
		flag=1;
	}
  }
  
  if(y2>y1)
  {
	  if(m1<=12 && m2>=1)
	  {
		  flag=1;
	  }
  }
  
  return flag;
}

// arrivale date >= departure-date
function date_equal(dt1,dt2)
{
   dt1=dt1.substr(3,2)+"/"+dt1.substr(0,2)+"/"+dt1.substr(6,4);
   dt2=dt2.substr(3,2)+"/"+dt2.substr(0,2)+"/"+dt2.substr(6,4);

   x1=new Date(dt1);
   x2=new Date(dt2);
  
   d1=x1.getDate();
   m1=(x1.getMonth()+1);
   y1=(x1.getYear());

   d2=x2.getDate();
   m2=(x2.getMonth()+1);
   y2=(x2.getYear());

  flag=0;

  if ( y2==y1 )
  {
	if ( m2==m1 )
	{
		if ( d2==d1 )
		{ 
		  flag=1;
		}
	}
  }
  return flag;
}

function CheckInt(Form,Field) //Check Number Only With  alert box (MSg)
{
	
	val=document[Form][Field].value;
	ValTrim=CheckTrim(Form,Field)	
	//fnAlert(ValTrim);
	if ((val == "")||(ValTrim==0))
	{	

		document[Form][Field].value="";
		document[Form][Field].focus();
		return(0);
	}
	else
	{ 
		for(i=0;i<val.length;i++)
		{
			Key=parseInt(val.charAt(i))
			alert(Key)
			if ((Key>=0 && Key<=9))
				{ return(1)}
			else {
				document[Form][Field].value="";
				document[Form][Field].focus();
				return(0)
				}
			}
	return(1);
	}
}

// Trim a value
function alltrim(strval)
{
myval=strval;
if (myval.length!=0 )
{
  // this is for ltrim()
  while( myval.substring(0,1)==" ")
  {
	myval=myval.substring(1,myval.length);
		if (myval.length==0)
		{
		 myval="";	
		 break;
		}
  }
}

if (myval.length!=0 )
{
  // this is for rtrim()
  while( myval.substring(myval.length-1,myval.length)==" ")
  {
	myval=myval.substring(0,myval.length-1);
		if (myval.length==0)
		{
		 myval="";	
		 break;
		}
  }
}
	return myval;
}

function numeric_only(val)
{
	flag=0;
for(i=0;i<val.length;i++)
{
  key=parseInt(val.charAt(i))
  if ( key>=0 && key<=9 )
	flag=1;
  else
	{ flag=0; break; }
}
return flag;
}


function float_only(val)
{
 
 flag=0;
 dot_count=0;
 
	for(i=0;i<val.length;i++)
	{
	  key=val.charAt(i)
	  
	  if (key==".")
	  	dot_count++;
	  
	  if (dot_count>1)			
	  {
	  	flag=0;
		return flag;		
	  }
	  
	  if ( (key>=0 && key<=9) || key=="." )
		flag=1;
	  else
		{ flag=0; break; }
	} 
 return flag;
}

//---
function isdate(v)
{
  x=v;
  d1=parseInt(x.charAt(0))
  d2=parseInt(x.charAt(1))

  m1=parseInt(x.charAt(3))
  m2=parseInt(x.charAt(4))

  y1=parseInt(x.charAt(6))
  y2=parseInt(x.charAt(7))
  y3=parseInt(x.charAt(8))
  y4=parseInt(x.charAt(9))

  flag=0;
  if ( (d1>=0 && d1<=9) && (d2>=0 && d2<=9) )
  {
      if ( (m1>=0 && m1<=9) && (m2>=0 && m2<=9) )
     {
	      if ( (y1>=0 && y1<=9) && (y2>=0 && y2<=9) && (y3>=0 && y3<=9) && (y4>=0 && y4<=9) )
	      {
		flag=1;
	      }
     }  
  }  
return flag;
}
//---

//it checks the value exist in the string or not
//str is the concatenated string of one column. (either empno or houseno)
//concatenation symbol is |(pipe)
//val is the value to be checked in the str.(eg employee no or one house no)
//returns true or false

function DataExists(str,val)
{
		flag=0;
		str1=str.split("|")
		for (i=0;i<str1.length;i++)
		{
			//alert(str1[i]);
			if (str1[i]==val)
				return true;
		}
		return false;
}


//it converts the key enter to upper case as and when typed
function UpperKey()
{
	Key = window.event.keyCode;
	if ((Key>=97 && Key<=122))
		window.event.keyCode=Key-32;
	
}
function DataIndex(str,val)
{
		flag=0;
		str1=str.split("|")
		for (i=0;i<str1.length;i++)
		{
			//alert(str1[i]);
			if (str1[i]==val)
				return i;
		}
		return -1;
}


function Check_Email(val)
{ 
	
	if (!oneOnly(val,'@',true))
		return 0;			// test for only one @
	
	if (adjacent(val,'@.') || adjacent(val,'.@')|| adjacent(val,'..'))
		return 0;		// test that .@ @. and .. do not occur
	
	
	// other validations for this field to be added here
	return 1;
} 


function CheckEmail(Form,Field)
{ 
	var fld;
	fld =document[Form][Field].value;
	if (!oneOnly(fld,'@',true))
		return 0;			// test for only one @
	
	if (adjacent(fld,'@.') || adjacent(fld,'.@')|| adjacent(fld,'..'))
		return 0;		// test that .@ @. and .. do not occur
	
	
	// other validations for this field to be added here
	return 1;
} 

function adjacent(parm,chrs) {
return(parm.indexOf(chrs,0) != -1);
}

function oneOnly(parm,chr,must) 
{
	var atPos = parm.indexOf(chr,0);
	if (atPos == -1) {return !must;}
	if (parm.indexOf(chr, atPos + 1) > - 1) {return false;}
	return true; 
} 

function DateRange(d1,m1,y1,d2,m2,y2,d3,m3,y3)
{
	/*
	d1,m1,y1 === date to be checked   
	d2,m2,y2 === lower range
	d3,m3,y3 === upper range
	m1,m2,m3 starts from 0 to 11
	*/
	
	var dt1= new Date(m1+"/"+d1+"/"+y1)
	var dt2= new Date(m2+"/"+d2+"/"+y2)
	var dt3= new Date(m3+"/"+d3+"/"+y3)
	
	//alert (dt1+"   "+dt2+"    "+dt3)
	
	return !(dt1>=dt2 && dt1<=dt3)
}


//it checks the entered text box is empty or not.
//parameters are Form name and text box names 
//returns FieldName if it is empty else 1.
	
function CheckNullforAllthefields(Form,Field)
{ var Fieldval;
	a=Field
	arr=a.split("|")
	NumOfFields=a
	for(k=0;k< arr.length;k++)
	{
		Fieldval =document[Form][arr[k]].value;
		ValTrim=CheckTrim(Form,arr[k])	
		if ((Fieldval == "")||(ValTrim==0))
		{ 
			//document[Form][arr[k]].focus();
			return(arr[k]); 
		}
	}
	return(1);
}	


function resizediv220330()
{
	fresizeframe('0,*','30%,*')
	if (document.getElementById("maxmin").innerText=="Max"	)
	{
		document.getElementById("maxmin").innerText="Min"
		document.getElementById("iwindow").style.height=330
	}
	else
	{
		document.getElementById("maxmin").innerText="Max"
		document.getElementById("iwindow").style.height=220
	}
}
function notwoPeriods(KeyValType)
{
	Key = window.event.keyCode;
	switch (KeyValType)
	{
		case "twoPeriods": 
					if (((document.frm.Quantity.value).indexOf(".")!=-1)&&(Key==46))
					{
						window.event.keyCode=0;
						break;
					}	
					else
					{
						//alert((document.frm.Quantity.value).indexOf("."))
						if ((Key>=48 && Key<=57)||(Key==46)){}//Numeric & Period
						else{window.event.keyCode=0;}
						break;
					}
	}				
}	

function ngtv_float_only(val)
{
 
 flag=0;
 dot_count=0;
 hyp_count=0;
 
	for(i=0;i<val.length;i++)
	{
	  key=val.charAt(i)
	  
	  if (key==".")
	  	dot_count++;
	  
	  if (dot_count>1)			
	  {
	  	flag=0;
		return flag;		
	  }

	  if (key=="-")
	  	hyp_count++;

	  if (hyp_count>1)			
	  {
	  	flag=0;
		return flag;		
	  }
	  
	  if ( (key>=0 && key<=9) || key=="." || key=="-")
		flag=1;
	  else
		{ flag=0; break; }
	} 
 return flag;
}
