<?php
session_start();
$conn = mysqli_connect('localhost','root','god') or die("Connection Failure");
mysqli_select_db('ecms',$conn);

if ( $_SESSION['login']!="1" )
{
	?>
	<script language="javascript" type="text/javascript" >
		   window.top.parent.location.href="index.php"
	</script>
	<?php	
}
?>