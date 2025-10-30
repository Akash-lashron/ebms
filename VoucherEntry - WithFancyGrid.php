<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
require_once 'ExcelReader/excel_reader2.php';
checkUser();
$msg = '';
function dt_format($ddmmyyyy) {
    $dt = explode('/', $ddmmyyyy);
    $dd = $dt[0];
    $mm = $dt[1];
    $yy = $dt[2];
    return $yy . '-' . $mm . '-' . $dd;
}
function dt_display($ddmmyyyy) {
    $dt = explode('-', $ddmmyyyy);
    $dd = $dt[2];
    $mm = $dt[1];
    $yy = $dt[0];
    return $dd . '/' . $mm . '/' . $yy;
}
?>
<?php require_once "Header.html"; ?>
<script>
      	function func_items()
        {
            var xmlHttp;
            var data;
            var i, j;
            if (window.XMLHttpRequest) // For Mozilla, Safari, ...
            {
                xmlHttp = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) // For Internet Explorer
            {
                xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            strURL = "findrbntest.php?workordernumber=" + document.form.cmb_work_no.value;
            xmlHttp.open('POST', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xmlHttp.onreadystatechange = function ()
            {
                if (xmlHttp.readyState == 4)
                {
                    data = xmlHttp.responseText
                    /*if (data == "")
                    {
                        alert("No Records Found");
                        document.form.cmb_rbn.value = 'Select';
                    }*/
                    if (data != "")
                    {	
                        var name = data.split("*");
                        document.form.cmb_rbn.length = 0
                        var optn = document.createElement("option")
                        optn.value = 0;
                        optn.text = "------------------------------------ Select RBN. -------------------------------------";
                        document.form.cmb_rbn.options.add(optn)
                        var c = name.length;
                        for (i = 0 ; i < c ; i++)
                        {
                            var optn = document.createElement("option")
                            optn.value = name[i];
							optn.text = "RAB"+name[i];
                            document.form.cmb_rbn.options.add(optn)
                        }
                    }
                }
            }
            xmlHttp.send(strURL);
        }

	function find_workname()
	{		
		var xmlHttp;
		var data;
		var i,j;
			
		if(window.XMLHttpRequest) // For Mozilla, Safari, ...
		{
			xmlHttp = new XMLHttpRequest();
		}
		else if(window.ActiveXObject) // For Internet Explorer
		{ 
			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		}
		strURL="findworknametest.php?sheetid="+document.form.cmb_work_no.value;
		xmlHttp.open('POST', strURL, true);
		xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlHttp.onreadystatechange = function()
		{
			if (xmlHttp.readyState == 4)
			{
				data=xmlHttp.responseText
				var name=data.split("*");
				if(data=="")
				{
					alert("No Records Found");
					document.form.workname.value='';	
				}
				else
				{	
					document.form.workname.value		=	name[0].trim();
					document.form.txt_workorder_no.value=	name[2].trim();
					document.form.txt_book_no1.value	=	Number(name[1]) + Number(1);
					document.form.txt_book_no.value		=	Number(name[1]) + Number(1);
					document.form.txt_bookpage_no1.value=	Number(name[2]) + Number(1);
					document.form.txt_bookpage_no.value	=	Number(name[2]) + Number(1);
					document.form.txt_rab_no1.value		=	Number(name[3]) + Number(1);
					document.form.txt_rab_no.value		=	Number(name[3]) + Number(1);
				}
			}
		}
		xmlHttp.send(strURL);	
	}
	function goBack()
	{
	   	url = "dashboard.php";
		window.location.replace(url);
	}

	window.history.forward();
	function noBack() { window.history.forward(); }
</script>
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
         <?php include "Menu.php"; ?>
        <!--==============================Content=================================-->
        <div class="content">
			<div class="title">Voucher Entry</div>
            <div class="container_12">
                <div class="grid_12">
                    <blockquote class="bq1" style="overflow:auto">
                        <form name="form" method="post" action="PartPaymentPageView.php">
                            <div class="GridContainer div12">
                                <div style="position:relative;height: 400px;" class="div12">
								  <div id="formData" class="div4"></div>
								  <div id="gridData" class="div8"></div>
								</div>
								
                            </div>
							<div style="text-align:center">
								<div class="buttonsection" style="display:inline-table">
									<input type="button" onClick="goBack()" class="backbutton" name="back" id="back" value="Back">
								</div>
								<div class="buttonsection" style="display:inline-table">
									<input type="submit" class="btn" data-type="submit" value=" View " name="submit" id="submit"   />
								</div>
							</div>
                        </form>
                    </blockquote>
                </div>
            </div>
        </div>
		
		
         <!--==============================footer=================================-->
<?php include "footer/footer.html"; ?>
<link href="fancygrid/fancy.min.css" rel="stylesheet">
<script src="fancygrid/fancy.full.js"></script>

<script>
//$('#cmb_work_no').chosen();
   //$(function() {
	/*$.fn.validaterbnno = function(event) {	
				if($("#cmb_rbn").val()==0){ 
					var a="Please select the Bill number";
					$('#val_rbn').text(a);
					event.preventDefault();
					event.returnValue = false;
					}
				else{
				var a="";
				$('#val_rbn').text(a);
				}
			}
	$.fn.validateworkorder = function(event) { 
					if($("#cmb_work_no").val()==""){ 
					var a="Please select the work order number";
					$('#val_work').text(a);
					event.preventDefault();
					event.returnValue = false;
					}
				else{
				var a="";
				$('#val_work').text(a);
				}
			}
	$("#top").submit(function(event){
           	$(this).validaterbnno(event);
			$(this).validateworkorder(event);
			
         });
	$("#cmb_work_no").change(function(event){
    	$(this).validateworkorder(event);
    });
    $("#cmb_rbn").change(function(event){
		$(this).validaterbnno(event);
	});*/
	
	document.addEventListener("DOMContentLoaded", function() { 
	  var grid = new FancyGrid({
		title: 'Employee',
		renderTo: 'gridData',
		height: 450,
		data: data,
		selModel: 'row',
		trackOver: true,
		defaults: {
		  type: 'string',
		  width: 155,
		  sortable: true,
		  resizable: true,
		  editable: true,
		  vtype: 'notempty',
		  ellipsis: true,
		  filter: {
			header: true
		  }
		},
		events: [{
		  cellclick: function(grid, o) {
			form.set(o.data);
		  }
		}],
		columns: [{
		  index: 'id',
		  width: 40,
		  type: 'number',
		  filter: false
		}, {
		   index: 'name',
		  title: 'Name',
		  width: 100
		}, {
		  index: 'birthday',
		  title: 'Birthday',
		  type: 'date',
		  width: 100
		}, {
		  index: 'country',
		  title: 'Country',
		  type: 'combo',
		  data: ['USA', 'England', 'Canada', 'Germany']
		}, {
		  index: 'position',
		  title: 'Position',
		  width: 100
		}, {
		  index: 'hour',
		  type: 'currency',
		  title: 'Hour rate',
		  width: 70
		}, {
		  index: 'active',
		  type: 'checkbox',
		  title: 'Active?',
		  width: 60
		}, {
		  title: 'email',
		  index: 'email',
		  width: 150
		}]
	  });
	
	  var comboData = [{
		country: 'USA'
	  }, {
		country: 'Canada'
	  }, {
		country: 'England'
	  }, {
		country: 'Germany'
	  }];
	
	  var form = new FancyForm({
		renderTo: 'formData',
		title: 'User Data',
		height: 450,
		defaults: {
		  type: 'string'
		},
		items: [{
		  name: 'id',
		  type: 'hidden'
		}, {
		  label: 'Name',
		  emptyText: 'Name',
		  name: 'name'
		}, {
		  label: 'SurName',
		  emptyText: 'SurName',
		  name: 'surname'
		}, {
		  label: 'E-mail',
		  emptyText: 'E-mail',
		  name: 'email',
		  valid: {
			type: 'email',
			blank: false,
			blankText: 'Required',
			text: 'Incorect email'
		  }
		}, {
		  type: 'date',
		  label: 'Birthday',
		  name: 'birthday'
		}, {
		  type: 'checkbox',
		  label: 'Active',
		  name: 'active',
		  value: true
		}, {
		  type: 'number',
		  label: 'Hour rate',
		  name: 'hour',
		  min: 0
		}, {
		  type: 'string',
		  label: 'Position',
		  name: 'position'
		}, {
		  type: 'combo',
		  label: 'Country',
		  name: 'country',
		  data: comboData,
		  displayKey: 'country',
		  valueKey: 'country'
		}, {
		  type: 'textarea',
		  label: 'About',
		  name: 'about'
		}],
		buttons: ['side', {
		  text: 'Clear',
		  handler: function() {
			form.clear();
		  }
		}, {
		  text: 'Save',
		  handler: function() {
			var values = form.get();
	
			if (!values.id) {
			  return;
			}
	
			grid.getById(values.id).set(values);
			grid.update();
		  }
		}]
	  });
	
	});
	
	var data = [{
	  id: 1,
	  name: 'Ted',
	  surname: 'Smith',
	  email: 'ted.smith@fancygrid.com',
	  position: 'Java Developer',
	  birthday: '6/22/1953',
	  country: 'USA',
	  hour: 52,
	  active: true,
	  about: 'Likes to play on guitar.'
	}, {
	  id: 2,
	  name: 'Ed',
	  surname: 'Johnson',
	  email: 'ed.johnson@fancygrid.com',
	  position: 'C/C++ Market Data Developer',
	  birthday: '9/12/1971',
	  country: 'England',
	  hour: 26,
	  active: true,
	  about: 'Proffesional persone.'
	}, {
	  id: 3,
	  name: 'Sam',
	  surname: 'Williams',
	  email: 'sam.williams@fancygrid.com',
	  position: 'Technical Analyst',
	  birthday: '9/2/1973',
	  country: 'USA',
	  hour: 40,
	  active: true,
	  about: 'Dreams about travel around the world.'
	}, {
	  id: 4,
	  name: 'Alexander',
	  surname: 'Brown',
	  email: 'alexander.brown@fancygrid.com',
	  position: 'Project Manager',
	  country: 'USA',
	  birthday: '9/25/1991',
	  hour: 33,
	  active: true,
	  about: 'Has big family.'
	}, {
	  id: 5,
	  name: 'Nicholas',
	  surname: 'Miller',
	  email: 'nicholas.miller@fancygrid.com',
	  position: 'Senior Software Engineer',
	  country: 'Canada',
	  birthday: '8/24/1959',
	  hour: 50,
	  active: false,
	  about: 'Has country house where team spent holidays.'
	}, {
	  id: 6,
	  name: 'Andrew',
	  surname: 'Thompson',
	  email: 'andrew.thompson@fancygrid.com',
	  position: 'Agile Project Manager',
	  country: 'Germany',
	  birthday: '8/18/1978',
	  hour: 27,
	  active: true,
	  about: 'Encourages team.'
	}, {
	  id: 7,
	  name: 'Ryan',
	  surname: 'Walker',
	  email: 'ryan.walker@fancygrid.com',
	  position: 'Application Support Engineer',
	  country: 'England',
	  birthday: '4/2/1981',
	  hour: 40,
	  active: true,
	  about: 'Intelligent and polite, works with VIP clients'
	}, {
	  id: 8,
	  name: 'John',
	  surname: 'Scott',
	  email: 'john.scott@fancygrid.com',
	  position: 'Flex Developer',
	  country: 'USA',
	  birthday: '2/14/1960',
	  hour: 71,
	  active: true,
	  about: 'Cool at support.'
	}, {
	  id: 9,
	  name: 'James',
	  surname: 'Phillips',
	  email: 'james.phillips@fancygrid.com',
	  position: 'Senior C++/C# Developer',
	  country: 'USA',
	  birthday: '10/18/1991',
	  hour: 58,
	  active: true,
	  about: 'Works 24 hours per day.'
	}, {
	  id: 10,
	  name: 'Brian',
	  surname: 'Edwards',
	  email: 'brian.edwards@fancygrid.com',
	  position: 'UNIX/C++ Developer',
	  country: 'USA',
	  birthday: '4/16/1963',
	  hour: 64,
	  active: false,
	  about: 'Dreams to built self car.'
	}, {
	  id: 11,
	  name: 'Jack',
	  surname: 'Richardson',
	  email: 'jack.richardson@fancygrid.com',
	  position: 'Ruby Developer',
	  country: 'Germany',
	  birthday: '11/20/1982',
	  hour: 58,
	  active: true,
	  about: 'Helps in office.'
	}, {
	  id: 12,
	  name: 'Alex',
	  surname: 'Howard',
	  email: 'alex.howard@fancygrid.com',
	  position: 'CSS3/HTML5 Developer',
	  country: 'USA',
	  birthday: '7/22/1987',
	  hour: 66,
	  active: true,
	  about: 'Manages meetings.'
	}, {
	  id: 13,
	  name: 'Carlos',
	  surname: 'Wood',
	  email: 'carlos.wood@fancygrid.com',
	  position: 'Node.js Developer',
	  country: 'USA',
	  birthday: '8/8/1988',
	  hour: 41,
	  active: true,
	  about: 'Like to present product on performances.'
	}, {
	  id: 14,
	  name: 'Adrian',
	  surname: 'Russell',
	  email: 'adrian.russell@fancygrid.com',
	  position: 'Frontend Developer',
	  country: 'Canada',
	  birthday: '6/19/1969',
	  hour: 25,
	  active: true,
	  about: 'Dreams to buy plain.'
	}, {
	  id: 15,
	  name: 'Jeremy',
	  surname: 'Hamilton',
	  email: 'jeremy.hamilton@fancygrid.com',
	  position: 'Scala Developer',
	  country: 'USA',
	  birthday: '4/17/1950',
	  hour: 69,
	  active: true,
	  about: 'Dreams to go in cruis with all team.'
	}, {
	  id: 16,
	  name: 'Ivan',
	  surname: 'Woods',
	  email: 'ivan.woods@fancygrid.com',
	  position: 'Objective C Developer',
	  country: 'USA',
	  birthday: '3/25/1964',
	  hour: 64,
	  active: false,
	  about: 'Likes to play hockey.'
	}, {
	  id: 17,
	  name: 'Peter',
	  surname: 'West',
	  email: 'peter.west@fancygrid.com',
	  position: 'PHP/HTML Developer',
	  country: 'England',
	  birthday: '9/6/1977',
	  hour: 30,
	  active: true,
	  about: 'Just married.'
	}];

//});
</script>
</body>
</html>

