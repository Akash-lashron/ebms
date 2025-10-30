<?php
session_start();
$conn_db2 = mysqli_connect('localhost','root','god') or die("Connection Failure");
mysqli_select_db('estimator_db2',$conn_db2);

if ( $_SESSION['login']!="1" )
{
	?>
	<script language="javascript" type="text/javascript" >
		   window.top.parent.location.href="index.php"
	</script>
	<?php	
}
?>