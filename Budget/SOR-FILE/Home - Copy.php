<?php
@ob_start();
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/binddata.php';
include "common.php";
//checkUser();
$PageName = 'Civil - SOR&nbsp;&nbsp;<i class="fa fa-angle-double-right" style="font-size:19px"></i>&nbsp;&nbsp;Home';
$msg = ""; $del = 0;
$RowCount =0;
$staffid = $_SESSION['sid'];
?>
<link rel="stylesheet" href="dashboard/MyView/bootstrap.min.css">
<?php include "Header.html"; ?>
<script src="dashboard/MyView/bootstrap.min.js"></script>
<script type="text/javascript">
	window.history.forward();
	function noBack() { window.history.forward(); }
</script>	
    <body class="page1" id="top" oncontextmenu="return false"onload="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
        <!--==============================header=================================-->
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="form" id="form1">
            <?php include "Menu.php"; ?>
            <!--==============================Content=================================-->
			<div class="content">
				<?php include "MainMenu.php"; ?>
				<div class="container_12">
					<div class="grid_12" align="center">
						<div align="right" class="users-icon-part">&nbsp;</div>
						<blockquote class="bq1" style="overflow:auto">
							<div align="center" class="col-md-3 no-padding">&nbsp;</div>
							
							<div align="center" class="col-md-3 no-padding">&nbsp;</div>
							
							
							
							
							
							<div class="div4" align="center">&nbsp;</div>
							<div class="div4" align="center">
								<div class="div3" align="center">
								  	<div id="success-box">
										<!--<div class="dot"></div>
										<div class="dot two"></div>
										<div class="face">
									  		<div class="eye"></div>
									  		<div class="eye right"></div>
									  		<div class="mouth happy"></div>
										</div>-->fgfsgfs
										<div class="shadow scale"></div>
										<div class="message"><h1 class="alert">Success!</h1></div>
										<button class="button-box"><h1 class="green">continue</h1></button>
								  	</div>
								</div>
							  
								<div class="div3" align="center">
									<div id="error-box">
										<div class="dot"></div>
										<div class="dot two"></div>
										<div class="face2">
											<div class="eye"></div>
											<div class="eye right"></div>
											<div class="mouth sad"></div>
										</div>
										<div class="shadow move"></div>
										<div class="message"><h1 class="alert">Error!</h1><p>oh no, something went wrong.</p></div>
										<button class="button-box"><h1 class="red">try again</h1></button>
									</div>
								</div>
							</div>
							<div class="div4" align="center">&nbsp;</div>
							
							
							
							
							
						</blockquote>
					</div>
				</div>
			</div>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
            <script src="js/jquery.hoverdir.js"></script>
			
        </form>
    </body>
</html>
<style>
	.green {
  color: #4ec07d;
}

.red {
  color: #e96075;
}

.alert {
  font-weight: 700;
  letter-spacing: 5px;
}

p {
  margin-top: -5px;
  font-size: 0.5em;
  font-weight: 100;
  color: #5e5e5e;
  letter-spacing: 1px;
}

button, .dot {
  cursor: pointer;
}

#success-box {
  position: absolute;
  width: 20%;
  height: 40%;
  left: 25%;
  background: linear-gradient(to bottom right, #34C0F3 40%, #23E1E1 100%);
  border-radius: 20px;
  box-shadow: 5px 5px 20px #cbcdd3;
  perspective: 40px;
}

#error-box {
  position: absolute;
  width: 20%;
  height: 40%;
  right: 25%;
  background: linear-gradient(to bottom left, #4C9EE3 40%, #75ECA7 100%);
  border-radius: 20px;
  box-shadow: 5px 5px 20px #cbcdd3;
}

.dot {
  width: 8px;
  height: 8px;
  background: #FCFCFC;
  border-radius: 50%;
  position: absolute;
  top: 4%;
  right: 6%;
}
.dot:hover {
  background: #c9c9c9;
}

.two {
  right: 12%;
  opacity: .5;
}

.face {
  position: absolute;
  width: 22%;
  height: 22%;
  background: #FCFCFC;
  border-radius: 50%;
  border: 1px solid #777777;
  top: 21%;
  left: 37.5%;
  z-index: 2;
  animation: bounce 1s ease-in infinite;
}

.face2 {
  position: absolute;
  width: 22%;
  height: 22%;
  background: #FCFCFC;
  border-radius: 50%;
  border: 1px solid #777777;
  top: 21%;
  left: 37.5%;
  z-index: 2;
  animation: roll 3s ease-in-out infinite;
}

.eye {
  position: absolute;
  width: 5px;
  height: 5px;
  background: #777777;
  border-radius: 50%;
  top: 40%;
  left: 20%;
}

.right {
  left: 68%;
}

.mouth {
  position: absolute;
  top: 43%;
  left: 41%;
  width: 7px;
  height: 7px;
  border-radius: 50%;
}

.happy {
  border: 2px solid;
  border-color: transparent #777777 #777777 transparent;
  transform: rotate(45deg);
}

.sad {
  top: 49%;
  border: 2px solid;
  border-color: #777777 transparent transparent #777777;
  transform: rotate(45deg);
}

.shadow {
  position: absolute;
  width: 21%;
  height: 3%;
  opacity: .5;
  background: #777777;
  left: 40%;
  top: 43%;
  border-radius: 50%;
  z-index: 1;
}

.scale {
  animation: scale 1s ease-in infinite;
}

.move {
  animation: move 3s ease-in-out infinite;
}

.message {
  position: absolute;
  width: 100%;
  text-align: center;
  height: 40%;
  top: 47%;
}

.button-box {
  position: absolute;
  background: #FCFCFC;
  width: 50%;
  height: 15%;
  border-radius: 20px;
  top: 73%;
  left: 25%;
  outline: 0;
  border: none;
  box-shadow: 2px 2px 10px rgba(119, 119, 119, 0.5);
  transition: all .5s ease-in-out;
}
.button-box:hover {
  background: #efefef;
  transform: scale(1.05);
  transition: all .3s ease-in-out;
}

@keyframes bounce {
  50% {
    transform: translateY(-10px);
  }
}
@keyframes scale {
  50% {
    transform: scale(0.9);
  }
}
@keyframes roll {
  0% {
    transform: rotate(0deg);
    left: 25%;
  }
  50% {
    left: 60%;
    transform: rotate(168deg);
  }
  100% {
    transform: rotate(0deg);
    left: 25%;
  }
}
@keyframes move {
  0% {
    left: 25%;
  }
  50% {
    left: 60%;
  }
  100% {
    left: 25%;
  }
}
</style>
