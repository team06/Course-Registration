<?php
include("include/session.php");
?>

<?php
if(!$session->logged_in) {
	header('Location: login.php');
}
?>
<html>
<title>Honors Academy</title>
<body>

<table align="center">

</table>
</body>
</html>
