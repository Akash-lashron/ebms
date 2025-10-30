<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>eBMS</title>
<link rel="shortcut icon" href="">
<link rel="apple-touch-icon" sizes="144x144" href="http://placehold.it/144.png/000/fff">
<link rel="apple-touch-icon" sizes="114x114" href="http://placehold.it/114.png/000/fff">
<link rel="apple-touch-icon" sizes="72x72" href="http://placehold.it/72.png/000/fff">
<link rel="apple-touch-icon" sizes="57x57" href="http://placehold.it/57.png/000/fff">
<link href="assets/css/lib/weather-icons.css" rel="stylesheet" />
<link href="assets/css/lib/owl.carousel.min.css" rel="stylesheet" />
<link href="assets/css/lib/owl.theme.default.min.css" rel="stylesheet" />
<link href="assets/css/lib/font-awesome.min.css" rel="stylesheet">
<link href="assets/css/lib/themify-icons.css" rel="stylesheet">
<link href="assets/css/lib/menubar/sidebar.css" rel="stylesheet">
<link href="assets/css/lib/bootstrap.min.css" rel="stylesheet">
<link href="assets/css/lib/helper.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">
<link href="../scss/button_style.css" rel="stylesheet">
<style>
	input[type="submit"], input[type="reset"] { 
	   border-top: 1px solid #025976;
	   border:none;
	   background: #033140;
	   background: -webkit-gradient(linear, left top, left bottom, from(#025976), to(#033140));
	   background: -webkit-linear-gradient(top, #025976, #033140);
	   background: -moz-linear-gradient(top, #025976, #033140);
	   background: -ms-linear-gradient(top, #025976, #033140);
	   background: -o-linear-gradient(top, #025976, #033140);
	   padding: 6px 18px;
	   -webkit-border-radius: 3px;
	   -moz-border-radius: 3px;
	   border-radius: 3px;
	   -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
	   -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
	   box-shadow: rgba(0,0,0,1) 0 1px 0;
	   text-shadow: rgba(0,0,0,.4) 0 1px 0;
	   color: white;
	   font-size: 13px;
	   font-family:Verdana, Arial, Helvetica, sans-serif;
	   text-decoration: none;
	   vertical-align: middle;
	   font-weight:bold;
	   cursor:pointer;
	}
	input[type="submit"]:hover, input[type="reset"]:hover  , .button:hover{
	   border-top-color: #035a82;
	   background: #035a82;
	   color: #fff;
	   cursor:pointer;
	}
	input[type="submit"]:active, input[type="reset"]:active, .button:active{
	   border-top-color: #850909;
	   background: #850909;
	}
	.backbutton { 
	   border-top: 1px solid #025976;
	   border:none;
	   background: #033140;
	   background: -webkit-gradient(linear, left top, left bottom, from(#025976), to(#033140));
	   background: -webkit-linear-gradient(top, #025976, #033140);
	   background: -moz-linear-gradient(top, #025976, #033140);
	   background: -ms-linear-gradient(top, #025976, #033140);
	   background: -o-linear-gradient(top, #025976, #033140);
	   padding: 6px 18px;
	   -webkit-border-radius: 3px;
	   -moz-border-radius: 3px;
	   border-radius: 3px;
	   -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
	   -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
	   box-shadow: rgba(0,0,0,1) 0 1px 0;
	   text-shadow: rgba(0,0,0,.4) 0 1px 0;
	   color: white;
	   font-size: 13px;
	   font-family:Verdana, Arial, Helvetica, sans-serif;
	   text-decoration: none;
	   vertical-align: middle;
	   font-weight:bold;
	   cursor:pointer;
	}
	.backbutton:hover{
	   border-top-color: #035a82;
	   background: #035a82;
	   color: #fff;
	   cursor:pointer;
	}
	.backbutton:active{
	   border-top-color: #850909;
	   background: #850909;
	}
	/*.backbutton{
		background-image: linear-gradient(to right, #04befe, #3f86ed, #04befe, #3f86ed);
		box-shadow: 0 0px 0px 0 rgba(252, 145, 175, 0.75);
	}
	input[type="submit"]{
		margin:0px;
		box-shadow: 0 0px 0px 0 rgba(252, 145, 175, 0.75);
	}*/
	.BottomContent1 {
		position: fixed;
		top: 100px;
		right: 0px;
		z-index: 99;
		border: none;
		outline: none;
		background-color: #f24343;
		color: white;
		padding: 2px 2px 1px 2px;
		border-radius: 13px;
		width: 30px;
		font-weight: bold;
		text-align: center;
		vertical-align: middle;
		cursor:pointer;
	}	
	.BottomContent2 {
		position: fixed;
		top: 133px;
		right: 0px;
		z-index: 99;
		border: none;
		outline: none;
		background-color:#fff;
		color: white;
		padding: 2px;
		border-radius: 3px;
		width: 400px;
		font-weight: bold;
		text-align: center;
		vertical-align: middle;
		border:2px solid #f24343;
		max-height:400px;
		overflow-y:auto;
	}
	.fac1{ color:#F0F2F2; cursor:pointer; }
	.fac2{ color:#0B9996; cursor:pointer; }
	.round-span{
		padding:1px 3px 1px 3px;
		border:1px solid #0076EC;
		color:#0076EC;
		border-radius:6px;
		margin:2px;
		cursor:pointer;
		font-size:11px;
		pointer-events:auto;
		
		background:#0076EC;
		border:1px solid #0076EC;
		color:#FFFFFF;
	}
	.round-span:hover{
		background:#0076EC;
		border:1px solid #0076EC;
		color:#FFFFFF;
	}
	.round-span:active{
		background:#0076EC;
		border:1px solid #0076EC;
		color:#FFFFFF;
	}
</style>
