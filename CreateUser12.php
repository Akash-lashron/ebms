<?php
require_once 'library/config.php';
require_once 'library/functions.php';
checkUser();
$_SESSION['login_return_url'] = $_SERVER['REQUEST_URI'];

?>
<!DOCTYPE html>
<html lang="en">
    <head>
         <title><?php echo $pagetitle; ?> </title>
        <meta charset="utf-8">
        <meta name = "format-detection" content = "telephone=no" />
        <link rel="icon" href="images/favicon.ico">
        <link rel="shortcut icon" href="images/favicon.ico" />
        <link rel="stylesheet" href="css/form.css">
        <link rel="stylesheet" href="script/igstyle1.css">
        <link rel="stylesheet" href="Font style/font.css" />
        <link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/default.css">
        <script src="js/jquery.js"></script>
        <script src="js/jquery-migrate-1.2.1.js"></script>
        <script src="js/script.js"></script>
        <script src="js/superfish.js"></script>
        <script src="js/sForm.js"></script>
        <script src="js/jquery.ui.totop.js"></script>
        <script src="js/jquery.equalheights.js"></script>
        <script src="js/jquery.easing.1.3.js"></script>
        <script src="js/jquery.iosslider.min.js"></script>
        <script language="JavaScript" type="text/javascript" src="script/Date_Calendar.js"></script>
        <script language="JavaScript" type="text/javascript" src="script/validfn.js"></script>
        <link rel="stylesheet" href="css/menustyle.css" type="text/css" />
        <script type="text/javascript" src="js/menuscripts.js"></script>
        


        <!--[if lt IE 8]>
        <div style=' clear: both; text-align:center; position: relative;'>
                <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode">
                        <img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." />
                </a>
        </div>
        <![endif]-->
        <!--[if lt IE 9]>
        <script src="js/html5shiv.js"></script>
        <link rel="stylesheet" media="screen" href="css/ie.css">
 <link href="css/menustyle.css" rel="stylesheet" type="text/css"/>
        <![endif]-->
    </head>
    <body class="page1" id="top">
        <!--==============================header=================================-->
            <?php include_once("Menu.php"); ?>
            <!--==============================Content=================================-->
            
      
        <form action="" class="register">
            <h1> User Registration</h1>
             <fieldset class="row1">
                <legend>Engineer Details
                </legend>
                <p>
                    <label> Engineer Name
                    </label>
                    <select width=300px>
                        <option value="0"> -- Select Engineer --</option>
                        <option value="1">Mubarak
                        </option>
                    </select>
               </p>
                <p>
                    <label>Designation
                    </label>
                    <input type="text"/>
                    

                </p>
            </fieldset>
            <fieldset class="row1">
                <legend>Account Details
                </legend>
                <p>
                    <label> User Name
                    </label>
                    <input type="text"/>
               </p>
                <p>
                    <label>Password*
                    </label>
                    <input type="text"/>
                    <label>Repeat Password*
                    </label>
                    <input type="text"/>

                </p>
            </fieldset>
          
            <div><button class="button">Register</button>
            <input name="btnCancel" type="button" id="btnCancel" class="button"  value="Cancel" onClick="window.location.href='CreateUser.php';">  </div>
        </form>
            
            
    </body>
            <!--==============================footer=================================-->
           <?php   include "footer/footer.html"; ?>
        </form>
    </body>
</html>
